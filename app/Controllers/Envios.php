<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EnviosModel;
use App\Models\UnidadesModel;
use App\Models\TemporalEnvioModel;
use App\Models\DetallesEnvioModel;
use App\Models\DetallesInventarioModel;
use App\Models\CajasModel;
use App\Models\ProductosModel;
use App\Models\CamionetasModel;
use App\Models\ConfiguracionModel;
use App\Models\DetalleRolesPermisosModel;

class Envios extends BaseController{
    protected $envios, $temporal_envio, $detalle_envio, $productos, $cajas, $camionetas, $unidades, $configuracion, $session;
    protected $detalleRoles;

    public function __construct(){
        $this->envios=new EnviosModel();
        $this->detalle_envio=new DetallesEnvioModel();
        $this->productos=new ProductosModel();
        $this->unidades=new UnidadesModel();
        $this->cajas=new CajasModel();
        $this->camionetas=new CamionetasModel();
        $this->configuracion=new ConfiguracionModel();
        $this->detalleRoles=new DetalleRolesPermisosModel();

        $this->session=session();
        helper(['form']);

    }

    public function index() {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Permisos envio');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $datos=$this->envios->obtener(1);
        $data=['titulo'=> 'Envios', 'datos'=>$datos];

        echo view('header');
        echo view('envios/envios', $data);
        echo view('footer');
    }

    public function terminados() {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Permisos envio');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $datos=$this->envios->obtener(0);
        $data=['titulo'=> 'Envios terminados', 'datos'=>$datos];

        echo view('header');
        echo view('envios/terminados', $data);
        echo view('footer');
    }

    public function envio(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Permisos envio');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $unidades=$this->unidades->where('activo',1)->findAll();
        $camionetas=$this->camionetas->where('activo',1)->findAll();
        $caja=$this->cajas->where('id_caja',$this->session->id_caja)->first();
        $data=['titulo'=> 'Envio de producto', 'unidades'=>$unidades, 'camionetas'=>$camionetas, 'cajaU'=>$caja];
        echo view('header');
        echo view('envios/caja',$data);
        echo view('footer');
    }

    public function guarda(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Permisos envio');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $id_envio=$this->request->getPost('id_envio');
        $caja=$this->cajas->where('id_caja',$this->session->id_caja)->first();
        //$id_sucursalSalida=$this->request->getPost('id_sucursalSalida');
        $id_sucursalSalida=$caja['id_unidad'];
        $id_sucursalEntrada=$this->request->getPost('id_sucursalEntrada');
        $id_camioneta=$this->request->getPost('id_camioneta');
        $session=session();
        $resultadoId=$this->envios->insertaEnvio($id_envio,$id_sucursalSalida, $id_sucursalEntrada,$id_camioneta);

        $this->temporal_envio=new TemporalEnvioModel();

        if($resultadoId){
            $resultadoEnvio =$this->temporal_envio->porEnvio($id_envio);
            foreach ($resultadoEnvio as $row){
                $this->detalle_envio->save([
                    'id_envio'=>$resultadoId,
                    'id_producto'=>$row['idProducto'],
                    'codigo'=>$row['codigo'],
                    'nombre'=>$row['nombre'],
                    'cantidad'=>$row['cantidad']
                ]);
                $this->productos=new ProductosModel();
                $this->productos->actualizaStock($row['idProducto'], $row['cantidad'], '-');
                $this->detalle_inventario=new DetallesInventarioModel();
                $this->detalle_inventario->actualizaStock($row['idProducto'], $row['cantidad'], $id_sucursalSalida,'-');
            }
            $this->temporal_envio->eliminarEnvio($id_envio);
        }

        return redirect()->to(base_url()."/envios/muestraTicket/".$resultadoId);
    }

    function muestraTicket($id_envio){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Permisos envio');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $data['id_envio'] = $id_envio;

        echo view('header');
        echo view('envios/ver_ticket', $data);
        echo view('footer');
    }

    function generaTicket($id_envio){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Permisos envio');

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
        $datosVenta=$this->envios->where('id_envio', $id_envio)->first();
        $detalle_envio=$this->detalle_envio->select('*')->where('id_envio', $id_envio)->findAll();
        //$datosSucursalSalida=$this->envios->where('id_sucursalSalida', $datosVenta['id_sucursalSalida'])->first();
        //$datosSucursalEntrada=$this->envios->where('id_sucursalEntrada', $datosVenta['id_sucursalEntrada'])->first();
        $nombreSalida=$this->unidades->where('id_unidad', $datosVenta['id_sucursalSalida'])->first();
        $nombreEntrada=$this->unidades->where('id_unidad', $datosVenta['id_sucursalEntrada'])->first();
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
        $pdf->Cell(30 , 5,'Orden de envio: ', 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,$datosVenta['folio'], 0, 1, 'L');

        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(30 , 5,'Sucursal origen: ', 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,$nombreSalida['nombre'], 0, 1, 'L');
        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(30 , 5,'Sucursal destino: ', 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,$nombreEntrada['nombre'], 0, 1, 'L');
        //Lema
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B',10);
        $pdf->Multicell(70, 4, $leyendaTicket, 0, 'C', 0);        
        $pdf->SetFont('Arial', '',8);
        $pdf->Multicell(70, 4, utf8_decode('Muebles y artículos de calidad para el hogar'), 0, 'C', 0);        

        $pdf->Ln();
        //ENCABEZADO TABLA
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(8,5, '#', 0, 0, 'L');
        $pdf->Cell(35,5, 'Nombre', 0, 0, 'L');
        $pdf->Cell(15,5, 'Cantidad', 0, 1, 'L'); //1 para salto de linea
        
        $pdf->SetFont('Arial', '',7);
        //CONTENIDO TABLA
        $contador=1;
        $contadorProd=0;
        foreach($detalle_envio as $row){
            MultiCellRow(3, [8,35,15], 5, [$contador, $row['nombre'], $row['cantidad']], $pdf);
            $contador++;
            $contadorProd+=$row['cantidad'];
        }
        $pdf->Ln();
        //CONTENIDO TABLA
        $pdf->SetFont('Arial', 'B',8);
        $pdf->Cell(70,5,'Total de productos enviados '.$contadorProd, 0, 1, 'R');


        //Pie ticket
        $pdf->Ln();
        $pdf->Multicell(70, 4, utf8_decode('Envío generado exitosamente'), 0, 'C', 0);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output("ticket.pdf", "I");
    }

    public function entregar($id_envio){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Permisos envio');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        
        $productos=$this->detalle_envio->where('id_envio', $id_envio)->findAll();
        $envios=$this->envios->where('id_envio', $id_envio)->first();
        $id_sucursalEntrada=$envios['id_sucursalEntrada'];//para verificar si hay o no en la tabla de detalleinventario
        $this->detalle_inventario=new DetallesInventarioModel();
        foreach($productos as $producto){
            $this->productos->actualizaStock($producto['id_producto'], $producto['cantidad'], '+');

            $this->detalle_inventario->where('idProducto', $producto['id_producto']);
            $this->detalle_inventario->where('id_unidad', $id_sucursalEntrada);
            $query=$this->detalle_inventario->first();
            if($query){
                $this->detalle_inventario->actualizaStock($producto['id_producto'], $producto['cantidad'], $id_sucursalEntrada);
            }
            else{
                $this->detalle_inventario->save([
                'codigo'=>$producto['codigo'],
                'idProducto'=>$producto['id_producto'],
                'nombre'=>$producto['nombre'],
                'id_unidad'=>$id_sucursalEntrada,
                'cantidad'=>$producto['cantidad']
                ]);
            }

        }

        $this->envios->update($id_envio, ['estado'=>0, 'fecha_llegada'=>date("Y-m-d H:i:s")]);

        return redirect()->to(base_url().'/envios');
    }

    /*-----------------------------------------------------*/
    function reporteEnvios(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú reportes');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $camionetas=$this->camionetas->select('nombre');
        $camionetas=$this->camionetas->distinct();
        $camionetas=$this->camionetas->where('activo',1)->findAll();
        $data=['titulo'=> 'Generar reporte de ventas','camionetas'=>$camionetas];        
        echo view('header');
        echo view('envios/detalleReporteEnvios', $data);
        echo view('footer');
    }

    function detalleEnvios(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú reportes');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $nombre=$this->request->getPost('nombre');

        $data=['titulo'=> 'Reporte de envios','nombre'=>$nombre,];        
        echo view('header');
        echo view('envios/ver_detalle_envios', $data);
        echo view('footer');
    }

    function generarReporteEnvioConductor($nombre){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú reportes');

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
            $maxwidth=0;
            for ($i = 0; $i <=$cells; $i++) {
                $pdf->Line($x + $maxwidth, $y, $x + $maxwidth, $y + $maxheight);
                if($i!=$cells){
                    $maxwidth+=$width[$i];
                }
            }
            $pdf->Line($x, $y, $x + $maxwidth, $y);
            $pdf->Line($x, $y + $maxheight, $x + $maxwidth, $y + $maxheight);
            $pdf->Ln($maxheight);
        }
        $camionetas=$this->camionetas->select(['id_camioneta', 'modelo', 'placa']);
        $camionetas=$this->camionetas->where('nombre', $nombre)->findAll();

        $pdf = new \FPDF('P','mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(10,10,10);
        $pdf->SetTitle(utf8_decode("Reporte de envios"));
        $pdf->SetFont('Arial', 'B',10);
        //ENCABEZADO
        $pdf->Image("images/logotipo.png", 10, 5, 20);
        $pdf->Cell(0, 5, utf8_decode("Reporte de envios de conductor"), 0, 1, 'C');
        $pdf->Ln(10);
        
        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(39 , 5,utf8_decode('Conductor: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,$nombre, 0, 1, 'L');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '',9);
        
        //CONTENIDO TABLA
        $contador=0;
        $suma=0;
        foreach ($camionetas as $camioneta){
            $id_camioneta=$camioneta['id_camioneta'];
            
            $modelo=$camioneta['modelo'];
            $placa=$camioneta['placa'];
            $envios=$this->envios->where('id_camioneta', $id_camioneta)->findAll();
            $pdf->SetFont('Arial', 'B',9);
            $pdf->Cell(39 , 5,utf8_decode('Envios con: '), 0, 0, 'L');
            $pdf->SetFont('Arial', '',9);
            $pdf->Cell(50, 5,$modelo, 0, 1, 'L');
            $pdf->SetFont('Arial', 'B',9);
            $pdf->Cell(39 , 5,utf8_decode('Placa: '), 0, 0, 'L');
            $pdf->SetFont('Arial', '',9);
            $pdf->Cell(50, 5,$placa, 0, 1, 'L');
            $pdf->Ln(5);
            foreach ($envios as $envio){
                $id_envio=$envio['id_envio'];
                $estado=$envio['estado'];
                $pdf->SetFont('Arial', 'B',9);
                $pdf->Cell(39 , 5,utf8_decode('Id de envio: '), 0, 0, 'L');
                $pdf->SetFont('Arial', '',9);
                $pdf->Cell(50, 5,$id_envio, 0, 1, 'L');
                $pdf->SetFont('Arial', 'B',9);
                $pdf->Cell(39 , 5,utf8_decode('Estado: '), 0, 0, 'L');
                $pdf->SetFont('Arial', '',9);
                if($estado==0)
                    $pdf->Cell(50, 5,utf8_decode("Entragado"), 0, 1, 'L');
                else
                    $pdf->Cell(50, 5,utf8_decode("En proceso"), 0, 1, 'L');

                $detalle_envio=$this->detalle_envio->select('*')->where('id_envio', $id_envio)->findAll();
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(25,5, '#', 1, 0, 'L');
                $pdf->Cell(110,5, 'Nombre', 1, 0, 'L');
                $pdf->Cell(50,5, 'Cantidad', 1, 1, 'L');
                $contador=1;
                $contadorProd=0;
                foreach($detalle_envio as $row){
                    MultiCellRow(3, [25,110,50], 5, [$contador, $row['nombre'], $row['cantidad']], $pdf);
                    $contador++;
                    $contadorProd+=$row['cantidad'];
                }
                $pdf->Ln();
            }
            $pdf->Ln(10);
        }
        $pdf->Ln(10);
        /*$pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(39 , 5,utf8_decode('Número de ventas: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,$contador, 0, 1, 'L');

        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(39 , 5,utf8_decode('Total de ventas: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,$suma, 0, 1, 'L');*/

        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output("compra_pdf.pdf", "I");
    }
    
}
