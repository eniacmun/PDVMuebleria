<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductosModel;
use App\Models\UnidadesModel;
use App\Models\CajasModel;
use App\Models\CategoriasModel;
use App\Models\DetalleRolesPermisosModel;
use App\Models\VentasModel;
use App\Models\DetallesInventarioModel;

class Productos extends BaseController{
    protected $productos, $session, $detalleRoles, $cajas, $ventas,$detalle_inventario;
    protected $reglas;  
    public function __construct(){
        $this->productos=new ProductosModel();
        $this->unidades=new UnidadesModel();
        $this->categorias=new CategoriasModel();
        $this->cajas=new CajasModel();
        $this->detalleRoles=new DetalleRolesPermisosModel();
        $this->ventas=new VentasModel();
        $this->detalle_inventario=new DetallesInventarioModel();
        $this->session=session();

        helper(['form']);
        $this->reglas=['codigo'=>['rules'=>'required|is_unique[productos.codigo, idProducto,{idProducto}]',//de esta manera se asegura que el update sea solo para ese id se sigue respetando la regla
            'errors'=>['required'=>'El campo {field} es obligatorio.',
                'is_unique'=>'El campo {field} debe ser único.'
            ]], 
        'nombre'=>['rules'=>'required','errors'=>[
                'required'=>'El campo {field} es obligatorio.'
            ]
            ],
        'precio_venta'=> ['rules'=>'required','errors'=>[
                'required'=>'El campo {field} es obligatorio.'
            ]
        ],
        'precio_compra'=>['rules'=>'required','errors'=>[
                'required'=>'El campo {field} es obligatorio.'
            ]
        ],
        'stock_minimo'=>['rules'=>'required','errors'=>[
                'required'=>'El campo {field} es obligatorio.'
            ]
        ],
        'inventariable'=>['rules'=>'required','errors'=>[
                'required'=>'El campo {field} es obligatorio.'
            ]
        ],
        'id_categoria'=>['rules'=>'required','errors'=>[
                'required'=>'El campo {field} es obligatorio.'
            ]
        ],
        'descripcion'=>['rules'=>'required','errors'=>[
                'required'=>'El campo {field} es obligatorio.'
            ]
        ]    
        ];
    }

    public function index($activo = 1) {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Productos');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $productos=$this->productos->where('activo',$activo)->findAll();//Igual a Select * from productos where activo=1
        $data=['titulo'=> 'Productos', 'datos' => $productos];

        echo view('header');
        echo view('productos/productos', $data);
        echo view('footer');
    }

    public function nuevo(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Productos');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }

        //$unidades=$this->unidades->where('activo',1)->findAll();//Obtener todos los activos de unidades y categorias
        $categorias=$this->categorias->where('activo',1)->findAll();
        $data=['titulo'=> 'Agregar producto', 'categorias'=>$categorias];
        echo view('header');
        echo view('productos/nuevo', $data);
        echo view('footer');
    }
    public function productosSucursal(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Productos');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }

        $detalle_inventario=$this->detalle_inventario->where('activo',1)->findAll();//Igual a Select * from productos where activo=1
        $data=['titulo'=> 'Productos', 'datos' => $detalle_inventario];

        echo view('header');
        echo view('productos/productosSucursal', $data);
        echo view('footer');
    }

    public function insertar(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Productos');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        if($this->request->getMethod()=="post" && $this->validate($this->reglas)){
            $date = date("Y-m-d H:i:s");
            $this->productos->save(['codigo'=>$this->request->getPost('codigo'),
            'nombre'=>$this->request->getPost('nombre'), 
            'fecha_alta'=>$date,
            'precio_venta'=>$this->request->getPost('precio_venta'), 
            'precio_compra'=>$this->request->getPost('precio_compra'),
            'stock_minimo'=>$this->request->getPost('stock_minimo'),
            'inventariable'=>$this->request->getPost('inventariable'),
            'id_categoria'=>$this->request->getPost('id_categoria'),
            'descripcion'=>$this->request->getPost('descripcion')
            ]);
            return redirect()->to(base_url().'/productos');
        }
        else{
            //$unidades=$this->unidades->where('activo',1)->findAll();//Obtener todos los activos de unidades y categorias
            $categorias=$this->categorias->where('activo',1)->findAll();
            $data=['titulo'=> 'Agregar producto', 'validation'=>$this->validator, 'categorias'=>$categorias];
            echo view('header');
            echo view('productos/nuevo', $data);
            echo view('footer');
        }
    }



    public function editar($id, $valid=null){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Productos');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        //$unidades=$this->unidades->where('activo',1)->findAll();//Obtener todos los activos de unidades y categorias
        $categorias=$this->categorias->where('activo',1)->findAll();
        $producto=$this->productos->where('idProducto',$id)->first();
        if($valid!=null){
            $data=['titulo'=> 'Editar producto', 'producto'=>$producto, 'categorias'=>$categorias, 'validation'=>$valid];
        }
        else{
            $data=['titulo'=> 'Editar producto', 'producto'=>$producto, 'categorias'=>$categorias];
        }
        echo view('header');
        echo view('productos/editar', $data);
        echo view('footer');
    }

    public function actualizar(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Productos');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        if($this->request->getMethod()== "post" && $this->validate($this->reglas)){
            $date = date("Y-m-d H:i:s");
            $this->productos->update($this->request->getPost('idProducto'),[
            'codigo'=>$this->request->getPost('codigo'),
            'nombre'=>$this->request->getPost('nombre'),
            'precio_venta'=>$this->request->getPost('precio_venta'), 
            'precio_compra'=>$this->request->getPost('precio_compra'),
            'stock_minimo'=>$this->request->getPost('stock_minimo'),
            'inventariable'=>$this->request->getPost('inventariable'),
            'id_categoria'=>$this->request->getPost('id_categoria'),
            'descripcion'=>$this->request->getPost('descripcion'),
            'fecha_edit'=>$date
            ]);
            return redirect()->to(base_url().'/productos');
        }
        else{
            return $this->editar($this->request->getPost('idProducto'), $this->validator);
        }
        
        
        return redirect()->to(base_url().'/productos');
    }

    public function eliminar($id){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Productos');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $date = date("Y-m-d H:i:s"); 
        $this->productos->update($id,['activo'=>0, 'fecha_del'=>$date]);

        $prodInfo=$this->productos->where('idProducto',$id)->first();
        $codigo=$prodInfo['codigo'];
        $this->detalle_inventario->set('activo', 0);
        $this->detalle_inventario->where('codigo',$codigo);
        $this->detalle_inventario->update();
        return redirect()->to(base_url().'/productos');
    }

    public function eliminados($activo = 0) {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Productos');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $productos=$this->productos->where('activo',$activo)->findAll();//Igual a Select * from productos where activo=1
        $data=['titulo'=> 'Productos eliminados', 'datos' => $productos];
        echo view('header');
        echo view('productos/eliminados', $data);
        echo view('footer');
    }

    public function reingresar($id){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Productos');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $date = NULL; 
        $this->productos->update($id,['activo'=>1, 'fecha_del'=>$date]);
        $prodInfo=$this->productos->where('idProducto',$id)->first();
        $codigo=$prodInfo['codigo'];
        $this->detalle_inventario->set('activo', 1);
        $this->detalle_inventario->where('codigo',$codigo);
        $this->detalle_inventario->update();
        return redirect()->to(base_url().'/productos/eliminados');
    }

    public function buscarPorCodigo($codigo){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        
        $this->productos->select('*');
        $this->productos->where('codigo', $codigo);
        $this->productos->where('activo', 1);
        $datos=$this->productos->get()->getRow();

        $res['existe']=false;
        $res['datos']='';        
        $res['error']='';

        if($datos){
            $res['datos']=$datos;
            $res['existe']=true;
        }else{
            $res['error']='No existe el producto';
            $res['existe']=false;
        }

        echo json_encode($res);
    }


    public function autocompleteData(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $returnData=array();
        $valor=$this->request->getGet('term');

        $productos=$this->productos->like('codigo', $valor)->where('activo', 1)->findAll();
        if(!empty($productos)){
            foreach($productos as $row){
                $data['idProducto']=$row['idProducto'];
                $data['value']=$row['codigo'];
                $data['label']=$row['codigo'].' - '.$row['nombre'];
                array_push($returnData, $data);
            }
        }

        echo json_encode($returnData);
    }

    function mostrarMinimos(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú reportes');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        echo view('header');
        echo view('productos/ver_minimos');
        echo view('footer');
    }

    function generaMinimos(){
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
        $pdf = new \FPDF('P','mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(10,10,10);
        $pdf->SetTitle(utf8_decode("Productos con stock mímino"));
        $pdf->SetFont('Arial', 'B',10);
        //ENCABEZADO
        $pdf->Image("images/logotipo.png", 10, 5, 20);
        $pdf->Cell(0, 5, utf8_decode("Reporte de productos con stock mínimo"), 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->Cell(40, 5, utf8_decode("Código"), 1, 0, 'C');
        $pdf->Cell(85, 5, utf8_decode("Nombre"), 1, 0, 'C');
        $pdf->Cell(30, 5, utf8_decode("Existencias"), 1, 0, 'C');
        $pdf->Cell(30, 5, utf8_decode("Stock Mínimo"), 1, 1, 'C');
        $pdf->SetFont('Arial', '',9);
        $datosProductos=$this->productos->getproductosMinimo();
        
        //CONTENIDO TABLA
        foreach ($datosProductos as $producto){
            MultiCellRow(4, [40,85,30,30], 5, [$producto['codigo'],$producto['nombre'], $producto['cantidad'],$producto['stock_minimo']], $pdf);
        }
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output("compra_pdf.pdf", "I");
    }
    /*----------------------------------*/
    function reporteVentas(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú reportes');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $cajas=$this->cajas->where('activo',1)->findAll();
        $data=['titulo'=> 'Generar reporte de ventas','cajas'=>$cajas];        
        echo view('header');
        echo view('productos/detalleReporteVenta', $data);
        echo view('footer');
    }

    function detalleVentas(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú reportes');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }        
        $fecha_inicio=$this->request->getPost('fecha_inicio');
        $fecha_fin=$this->request->getPost('fecha_fin');
        $id_caja=$this->request->getPost('id_caja');

        $data=['titulo'=> 'Reporte de ventas','fecha_inicio'=>$fecha_inicio,'fecha_fin'=>$fecha_fin,'id_caja'=>$id_caja,];        
        echo view('header');
        echo view('productos/ver_detalle_ventas', $data);
        echo view('footer');
    }

    function generarReporteVentas($fecha_inicio,$fecha_fin,$id_caja){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú reportes');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        if($id_caja!='Todas'){
            $ventas=$this->ventas->where('id_caja', $id_caja);
        }
        $ventas=$this->ventas->where('fecha_alta>=', $fecha_inicio);
        $ventas=$this->ventas->where('fecha_alta<=', $fecha_fin)->findAll();

        $pdf = new \FPDF('P','mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(10,10,10);
        $pdf->SetTitle(utf8_decode("Reporte de ventas"));
        $pdf->SetFont('Arial', 'B',10);
        //ENCABEZADO
        $pdf->Image("images/logotipo.png", 10, 5, 20);
        $pdf->Cell(0, 5, utf8_decode("Reporte de ventas"), 0, 1, 'C');
        $pdf->Ln(10);
        
        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(39 , 5,utf8_decode('Inicio de periodo: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,$fecha_inicio, 0, 1, 'L');
        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(39 , 5,utf8_decode('Fin de periodo: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,$fecha_fin, 0, 1, 'L');
        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(39 , 5,utf8_decode('Id de caja: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,$id_caja, 0, 1, 'L');
        $pdf->Ln(10);

        $pdf->Cell(40, 5, utf8_decode("Fecha"), 1, 0, 'C');
        $pdf->Cell(85, 5, utf8_decode("Folio"), 1, 0, 'C');
        $pdf->Cell(30, 5, utf8_decode("Id de venta"), 1, 0, 'C');
        $pdf->Cell(30, 5, utf8_decode("Total"), 1, 1, 'C');
        $pdf->SetFont('Arial', '',9);
        
        //CONTENIDO TABLA
        $contador=0;
        $suma=0;
        foreach ($ventas as $venta){
            $pdf->Cell(40, 5, utf8_decode($venta['fecha_alta']), 1, 0, 'C');
            $pdf->Cell(85, 5, utf8_decode($venta['folio']), 1, 0, 'C');
            $pdf->Cell(30, 5, utf8_decode($venta['id_venta']), 1, 0, 'C');
            $pdf->Cell(30, 5, utf8_decode($venta['total']), 1, 1, 'C');
            $contador+=1;
            $suma+=$venta['total'];
        }
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(39 , 5,utf8_decode('Número de ventas: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,$contador, 0, 1, 'L');

        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(39 , 5,utf8_decode('Total de ventas: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,$suma, 0, 1, 'L');

        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output("compra_pdf.pdf", "I");
    }




}
