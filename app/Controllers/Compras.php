<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ComprasModel;
use App\Models\TemporalCompraModel;
use App\Models\DetallesCompraModel;
use App\Models\ProductosModel;
use App\Models\UnidadesModel;
use App\Models\ConfiguracionModel;
use App\Models\DetalleRolesPermisosModel;
use App\Models\DetallesInventarioModel;

class Compras extends BaseController{
    protected $compras, $temporal_compra, $detalle_compra, $detalle_inventario, $productos, $unidades, $configuracion, $session, $reglas;
    protected $detalleRoles;    
    public function __construct(){
        $this->compras=new ComprasModel();
        $this->detalle_inventario=new DetallesInventarioModel();
        $this->unidades=new UnidadesModel();
        $this->detalle_compra=new DetallesCompraModel();
        $this->configuracion=new ConfiguracionModel();
        $this->detalleRoles=new DetalleRolesPermisosModel();
        $this->session=session();
        helper(['form']);
        $this->reglas=[
        'id_unidad'=>['rules'=>'required','errors'=>[
                'required'=>'El campo sucursal es obligatorio.'
            ]]    
        ];

    }

    public function index($activo = 1) {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú Inventario');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $compras=$this->compras->where('activo',$activo)->findAll();//Igual a Select * from compras where activo=1
        $data=['titulo'=> 'Inventario', 'compras'=>$compras];

        echo view('header');
        echo view('compras/compras', $data);
        echo view('footer');
    }

    public function nuevo(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú Inventario');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $unidades=$this->unidades->where('activo',1)->findAll();//Igual a Select * from unidades where activo=1
        $data=['titulo'=> 'Nuevo producto a inventario', 'unidades'=>$unidades];
        echo view('header');
        echo view('compras/nuevo', $data);
        echo view('footer');
    }

    public function guarda(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú Inventario');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        if($this->request->getMethod()=="post" && $this->validate($this->reglas)){
            $id_compra=$this->request->getPost('id_compra');
            $id_unidad=$this->request->getPost('id_unidad');
            $total=$this->request->getPost('total');
            $total=str_replace(',', '', $total);
            $session=session();
            $resultadoId=$this->compras->insertaCompra($id_compra, $total, $session->id_usuario);

            $this->temporal_compra=new temporalCompraModel();

            if($resultadoId){
                $resultadoCompra =$this->temporal_compra->porCompra($id_compra);
                $date=date("Y-m-d H:i:s");
                foreach ($resultadoCompra as $row){
                    $this->detalle_compra->save([
                        'id_compra'=>$resultadoId,
                        'idProducto'=>$row['idProducto'],
                        'nombre'=>$row['nombre'],
                        'cantidad'=>$row['cantidad'],
                        'precio'=>$row['precio'],                    
                        'fecha_alta'=>$date
                    ]);
                    $this->productos=new ProductosModel();
                    $this->productos->actualizaStock($row['idProducto'], $row['cantidad']);

                    $this->detalle_inventario->where('idProducto', $row['idProducto']);
                    $this->detalle_inventario->where('id_unidad', $id_unidad);
                    $query=$this->detalle_inventario->first();
                    if($query){
                        $this->detalle_inventario->actualizaStock($row['idProducto'], $row['cantidad'], $id_unidad);
                    }
                    else{
                        $this->detalle_inventario->save([
                        'codigo'=>$row['codigo'],
                        'idProducto'=>$row['idProducto'],
                        'nombre'=>$row['nombre'],
                        'id_unidad'=>$id_unidad,
                        'cantidad'=>$row['cantidad']
                        ]);
                    }
                }
                $this->temporal_compra->eliminarCompra($id_compra);
            }
            return redirect()->to(base_url()."/compras/muestraCompraPdf/".$resultadoId);
        }
        else{
            //$unidades=$this->unidades->where('activo',1)->findAll();//Obtener todos los activos de unidades y categorias
            $unidades=$this->unidades->where('activo',1)->findAll();//Igual a Select * from unidades where activo=1
            $data=['titulo'=> 'Nuevo producto a inventario','validation'=>$this->validator,'unidades'=>$unidades];
            echo view('header');
            echo view('compras/nuevo', $data);
            echo view('footer');
        }
        
    }

    function muestraCompraPdf($id_compra){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú Inventario');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $data['id_compra'] = $id_compra;

        echo view('header');
        echo view('compras/ver_compra_pdf', $data);
        echo view('footer');
    }
    function generaCompraPdf($id_compra){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú Inventario');

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
        $datosCompra=$this->compras->where('id_compra', $id_compra)->first();
        $detalle_compra=$this->detalle_compra->select('*')->where('id_compra', $id_compra)->findAll();

        $nombreTienda=$this->configuracion->select('valor')->where('nombre', 'tienda_nombre')->get()->getRow()->valor;
        $direccionTienda=$this->configuracion->select('valor')->where('nombre', 'tienda_direccion')->get()->getRow()->valor;

        $pdf = new \FPDF('P','mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(10,10,10);
        $pdf->SetTitle("Inventario");
        $pdf->SetFont('Arial', 'B',10);
        //ENCABEZADO
        $pdf->Cell(195, 5, "Entrada de productos", 0, 1, 'C');
        $pdf->SetFont('Arial', 'B',9);

        $pdf->image(base_url(). '/images/logotipo.png', 185,10, 20, 20, 'png');
        $pdf->Cell(50, 5,utf8_decode($nombreTienda), 0, 1, 'L');
        $pdf->Cell(20, 5,utf8_decode('Dirección: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,utf8_decode($direccionTienda), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(25 , 5,'Fecha y hora:  ', 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,$datosCompra['fecha_alta'], 0, 1, 'L');

        $pdf->Ln();
        //ENCABEZADO TABLA
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(0,0,0,);
        $pdf->setTextColor(255,255,255);
        $pdf->Cell(196, 5, 'Detalle de productos', 1,1,'C',1);
        $pdf->setTextColor(0,0,0);
        $pdf->Cell(14,5, 'No', 1, 0, 'L');
        $pdf->Cell(25,5, 'Codigo', 1, 0, 'L');
        $pdf->Cell(77,5, 'Nombre', 1, 0, 'L');
        $pdf->Cell(25,5, 'Precio', 1, 0, 'L');
        $pdf->Cell(25,5, 'Cantidad', 1, 0, 'L');
        $pdf->Cell(30,5, 'Importe', 1, 1, 'L'); //1 para salto de linea
        
        $pdf->SetFont('Arial', '',8);
        //CONTENIDO TABLA
        $contador=1;
        foreach($detalle_compra as $row){
            $importe=number_format($row['precio']*$row['cantidad'], 2, '.', ',');
            MultiCellRow(6, [14,25,77,25,25,30], 5, [$contador,$row['idProducto'], $row['nombre'],$row['precio'],$row['cantidad'],$importe], $pdf);
            $contador++;
        }
        $pdf->Ln();
        //CONTENIDO TABLA
        $pdf->SetFont('Arial', 'B',8);
        $pdf->Cell(195,5,'Total: $ '.number_format($datosCompra['total'],2, '.', ','), 0, 1, 'R');

        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output("compra_pdf.pdf", "I");
    }
    
}
