<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VentasModel;
use App\Models\TemporalCompraModel;
use App\Models\DetallesVentaModel;
use App\Models\ProductosModel;
use App\Models\CajasModel;
use App\Models\DetallesInventarioModel;
use App\Models\ConfiguracionModel;
use App\Models\DetalleRolesPermisosModel;

class Ventas extends BaseController{
    protected $ventas,$reglas, $temporal_compra, $detalle_venta, $productos,$cajas, $detalle_inventario, $configuracion, $session;
    protected $detalleRoles;

    public function __construct(){
        $this->ventas=new VentasModel();
        $this->detalle_venta=new DetallesVentaModel();
        $this->productos=new ProductosModel();
        $this->cajas=new CajasModel();
        $this->detalle_inventario=new DetallesInventarioModel();
        $this->temporal_compra=new TemporalCompraModel();
        $this->configuracion=new ConfiguracionModel();
        $this->detalleRoles=new DetalleRolesPermisosModel();

        $this->session=session();
        helper(['form']);
    }

    public function index() {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú ventas');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $datos=$this->ventas->obtener(1);
        $data=['titulo'=> 'Ventas', 'datos'=>$datos];

        echo view('header');
        echo view('ventas/ventas', $data);
        echo view('footer');
    }

    public function eliminados() {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú ventas');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $datos=$this->ventas->obtener(0);
        $data=['titulo'=> 'Ventas eliminadas', 'datos'=>$datos];

        echo view('header');
        echo view('ventas/eliminados', $data);
        echo view('footer');
    }

    public function venta(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú caja');

        if(!$permiso or $this->session->estadoCaja!=1){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }        
        $detalle_inventario=$this->detalle_inventario->where('activo',1)->findAll();
        $productos=$this->productos->where('activo',1)->findAll();
        $data=['detalle_inventario'=>$detalle_inventario, 'productos'=>$productos];
        echo view('header');
        echo view('ventas/caja', $data);
        echo view('footer');
    }

    public function guarda(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú ventas');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $id_venta=$this->request->getPost('id_venta');
        $total=$this->request->getPost('subtotal');
        $total=str_replace(',', '', $total);
        $forma_pago=$this->request->getPost('forma_pago');
        $id_cliente=$this->request->getPost('id_cliente');
        $session=session();
        switch ($forma_pago) {
            case 1:
                $resultadoId=$this->ventas->insertaVenta($id_venta, $total, $session->id_usuario, $session->id_caja,$id_cliente,$forma_pago, null, $total,0);
                break;
            case 2:
                $resultadoId=$this->ventas->insertaVenta($id_venta, $total, $session->id_usuario, $session->id_caja,$id_cliente,$forma_pago, $this->request->getPost('tipo_credito'), 0, $total, $this->request->getPost('comision'));
                break;
            case 3: 
                $pagado=$this->request->getPost('pagado');
                $pagado=str_replace(',', '', $pagado);
                $pendiente=$total-$pagado;
                $resultadoId=$this->ventas->insertaVenta($id_venta, $total, $session->id_usuario, $session->id_caja,$id_cliente,$forma_pago, $this->request->getPost('tipo_credito'),$this->request->getPost('pagado'), $pendiente, $this->request->getPost('comision'));
                break;
        }
        $this->temporal_compra=new temporalCompraModel();
        $caja=$this->cajas->where('id_caja', $this->session->id_caja)->first();
        if($resultadoId){
            $resultadoCompra =$this->temporal_compra->porCompra($id_venta);
            $date=date("Y-m-d H:i:s");
            foreach ($resultadoCompra as $row){
                $this->detalle_venta->save([
                    'id_venta'=>$resultadoId,
                    'idProducto'=>$row['idProducto'],
                    'nombre'=>$row['nombre'],
                    'cantidad'=>$row['cantidad'],
                    'precio'=>$row['precio'],                    
                    'fecha_alta'=>$date
                ]);
                $this->productos=new ProductosModel();
                $this->productos->actualizaStock($row['idProducto'], $row['cantidad'], '-');
                $this->detalle_inventario->actualizaStock($row['idProducto'], $row['cantidad'], $caja['id_unidad'],'-');
            }
            $this->temporal_compra->eliminarCompra($id_venta);
        }

        return redirect()->to(base_url()."/ventas/muestraTicket/".$resultadoId);
    }

    function muestraTicket($id_venta){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú ventas');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $data['id_venta'] = $id_venta;

        echo view('header');
        echo view('ventas/ver_ticket', $data);
        echo view('footer');
    }

    function generaTicket($id_venta){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú ventas');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        function MultiCellRow($cells, $width, $height, $data, $pdf){
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $maxheight = 0;
            $maxwidth=0;
            for ($i = 0; $i < $cells; $i++) {
                $pdf->MultiCell($width[$i], $height, utf8_decode($data[$i]),0, 'L');
                if ($pdf->GetY() - $y > $maxheight) $maxheight = $pdf->GetY() - $y;
                $maxwidth+=$width[$i];
                $pdf->SetXY($x + ($maxwidth), $y);                
            }
            $pdf->Ln($maxheight);
        }
        $datosVenta=$this->ventas->where('id_venta', $id_venta)->first();
        $detalle_venta=$this->detalle_venta->select('*')->where('id_venta', $id_venta)->findAll();

        $nombreTienda=$this->configuracion->select('valor')->where('nombre', 'tienda_nombre')->get()->getRow()->valor;
        $direccionTienda=$this->configuracion->select('valor')->where('nombre', 'tienda_direccion')->get()->getRow()->valor;
        $leyendaTicket=$this->configuracion->select('valor')->where('nombre', 'tienda_leyenda')->get()->getRow()->valor;

        $pdf = new \FPDF('P','mm', array(80,200));//Tamaño impresora térmica
        $pdf->AddPage();
        $pdf->SetMargins(5,5,5);
        $pdf->SetTitle("Venta");
        $pdf->SetFont('Arial', 'B',10);
        //ENCABEZADO
        $pdf->Cell(70, 5, utf8_decode($nombreTienda), 0, 1, 'C');
        $pdf->SetFont('Arial', 'B',9);

        $pdf->image(base_url(). '/images/logotipo.png', 5,5, 15, 15, 'png');//logo
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(70, 5,utf8_decode($direccionTienda), 0, 1, 'C');

        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(25 , 5,'Fecha y hora:  ', 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,$datosVenta['fecha_alta'], 0, 1, 'L');


        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(15 , 5,'Ticket:  ', 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,$datosVenta['folio'], 0, 1, 'L');

        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(30 , 5,utf8_decode('Método de pago:  '), 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        if($datosVenta['forma_pago']==1){
            $pdf->Cell(15, 5,utf8_decode('Efectivo'), 0, 1, 'L');
        }else{
            if($datosVenta['forma_pago']==2){
                $pdf->Cell(14, 5,utf8_decode('Crédito a '), 0, 0, 'L');
                $pdf->Cell(25, 5,$datosVenta['tipo_credito'].' meses', 0, 1, 'L');
            }else{
                $pdf->Cell(22, 5,utf8_decode('Crédito mixto a '), 0, 0, 'L');
                $pdf->Cell(14, 5,$datosVenta['tipo_credito'].' meses', 0, 1, 'L');
                $pdf->SetFont('Arial', 'B',9);
                $pdf->Cell(15 , 5,utf8_decode('Pagado: $'), 0, 0, 'L');
                $pdf->SetFont('Arial', '',9);
                $pdf->Cell(14, 5,utf8_decode($datosVenta['pagado']), 0, 0, 'L');
                $pdf->SetFont('Arial', 'B',9);
                $pdf->Cell(20 , 5,utf8_decode('Pendiente: $'), 0, 0, 'L');
                $pdf->SetFont('Arial', '',9);
                $pdf->Cell(14, 5,utf8_decode($datosVenta['pendiente']+$datosVenta['comision']), 0, 0, 'L');
            }
            
        }
        
        //Lema
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B',10);
        $pdf->Multicell(70, 4, $leyendaTicket, 0, 'C', 0);        
        $pdf->SetFont('Arial', '',8);
        $pdf->Multicell(70, 4, utf8_decode('Muebles y artículos de calidad para el hogar'), 0, 'C', 0);        

        $pdf->Ln();
        //ENCABEZADO TABLA
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(8,5, 'Cant. ', 0, 0, 'L');
        $pdf->Cell(35,5, 'Nombre', 0, 0, 'L');
        $pdf->Cell(15,5, 'Precio', 0, 0, 'L');
        $pdf->Cell(15,5, 'Importe', 0, 1, 'L'); //1 para salto de linea
        
        $pdf->SetFont('Arial', '',7);
        //CONTENIDO TABLA
        $contador=1;
        foreach($detalle_venta as $row){
            $importe=number_format($row['precio']*$row['cantidad'], 2, '.', ',');
            MultiCellRow(4, [8,35,15,15], 5, [$row['cantidad'], $row['nombre'],$row['precio'],$importe], $pdf);
            $contador++;
        }
        $pdf->Ln();
        //CONTENIDO TABLA
        
        $pdf->SetFont('Arial', 'B',8);
        $pdf->Cell(70,5,'Subtotal: $ '.number_format($datosVenta['total'],2, '.', ','), 0, 1, 'R');
        if($datosVenta['forma_pago']==2 || $datosVenta['forma_pago']==3){
            $pdf->SetFont('Arial', 'B',8);
            $pdf->Cell(70,5,utf8_decode('Impuesto de crédito: $ '.number_format($datosVenta['comision'],2, '.', ',')), 0, 1, 'R');
        }
        $pdf->SetFont('Arial', 'B',8);
        $pdf->Cell(70,5,'Total: $ '.number_format($datosVenta['total']+$datosVenta['comision'],2, '.', ','), 0, 1, 'R');


        //Pie ticket
        $pdf->Ln();
        $pdf->Multicell(70, 4, 'Gracias por su compra', 0, 'C', 0);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output("ticket.pdf", "I");
    }

    public function eliminar($id_venta){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú ventas');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }

        $productos=$this->detalle_venta->where('id_venta', $id_venta)->findAll();
        $caja=$this->cajas->where('id_caja', $this->session->id_caja)->first();
        foreach($productos as $producto){
            $this->productos->actualizaStock($producto['idProducto'], $producto['cantidad'], '+');
            $this->detalle_inventario->actualizaStock($producto['idProducto'], $producto['cantidad'], $caja['id_unidad'],'+');
        }

        $this->ventas->update($id_venta, ['activo'=>0]);

        return redirect()->to(base_url().'/ventas');
    }

    public function consultaVenta($idProducto, $cantidad, $id_venta, $id_caja){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $error='';
        $producto=$this->productos->where('idProducto', $idProducto)->first();
        $caja=$this->cajas->where('id_caja', $id_caja)->first();
        $idCaja=$caja['id_unidad'];
        $this->detalle_inventario->where('idProducto', $idProducto);
        $detalle_inventario=$this->detalle_inventario->where('id_unidad', $idCaja)->first();
        if($producto){
            $datosExiste=$this->temporal_compra->porIdProductoCompra($idProducto, $id_venta);
            if($datosExiste){
                $cantidad=$datosExiste->cantidad+$cantidad;
            }           
        }else{
            $error='No existe el producto.';
        }
        if($detalle_inventario){            
            $res['InventarioSuc']=$detalle_inventario['cantidad'];
        }else{
            $res['InventarioSuc']='No hay en inventario en esta surcursal';
        }
        $res['InventarioTot']=$producto['cantidad'];
        $res['cantidad']= $cantidad;
        $res['error']=$error;
        echo json_encode($res);
    }
    
}
