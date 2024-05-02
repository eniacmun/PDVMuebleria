<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CajasModel;
use App\Models\DetalleRolesPermisosModel;
use App\Models\ArqueoCajaModel;
use App\Models\VentasModel;
use App\Models\UnidadesModel;
use App\Models\ConfiguracionModel;
use App\Models\UsuariosModel;

class Cajas extends BaseController{
    protected $cajas, $arqueoModel, $ventasModel, $configuracion, $unidades;
    protected $reglas, $session;
    protected $detalleRoles, $usuarios;

    public function __construct(){
        $this->cajas=new CajasModel();
        $this->detalleRoles=new DetalleRolesPermisosModel();
        $this->arqueoModel=new ArqueoCajaModel();
        $this->ventasModel=new VentasModel();
        $this->configuracion=new ConfiguracionModel();
        $this->usuarios=new UsuariosModel();
        $this->unidades=new UnidadesModel();

        $this->session=session();
        helper(['form']);
        $this->reglas=['numero_caja'=>[
            'rules'=>'required|numeric|greater_than[0]|less_than[1001]|is_unique[cajas.numero_caja, id_caja,{id_caja}]',
            'errors'=>[
                'required'=>'El campo {field} es obligatorio.',
                'is_unique'=>'El campo {field} debe ser único.',
                'numeric'=>'El campo {field} debe ser numérico.',
                'greater_than'=>'El campo {field} debe estar en el rango 1 a 1000.',
                'less_than'=>'El campo {field} debe estar en el rango 1 a 1000.'
            ]
        ], 'nombre'=>[
            'rules'=>'required',
            'errors'=>[
                'required'=>'El campo {field} es obligatorio.'
            ]
        ], 'folio'=>[
            'rules'=>'required|is_unique[cajas.folio, id_caja,{id_caja}]',
            'errors'=>[
                'required'=>'El campo {field} es obligatorio.',
                'is_unique'=>'El campo {field} debe ser único.'
            ]
        ],  'id_unidad'=>['rules'=>'required','errors'=>[
                'required'=>'El campo sucursal es obligatorio.'
            ]]  
        ];


    }

    public function index($activo = 1) {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Cajas');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $cajas=$this->cajas->where('activo',$activo)->findAll();//Igual a Select * from cajas where activo=1
        $data=['titulo'=> 'Cajas', 'datos' => $cajas];

        echo view('header');
        echo view('cajas/cajas', $data);
        echo view('footer');
    }

    public function nuevo(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Cajas');
        $permiso2=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Permisos admin');
        if(!$permiso or !$permiso2){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $unidades=$this->unidades->where('activo',1)->findAll();
        $data=['titulo'=> 'Agregar caja', 'unidades'=>$unidades];
        echo view('header');
        echo view('cajas/nuevo', $data);
        echo view('footer');
    }

    public function insertar(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Cajas');
        $permiso2=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Permisos admin');
        if(!$permiso or !$permiso2){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        if($this->request->getMethod()== "post" && $this->validate($this->reglas)){
            $date = date("Y-m-d H:i:s");
            $this->cajas->save(['nombre'=>$this->request->getPost('nombre'),'numero_caja'=>$this->request->getPost('numero_caja'), 'folio'=>$this->request->getPost('folio'),'id_unidad'=>$this->request->getPost('id_unidad'), 'fecha_alta'=>$date]);
            return redirect()->to(base_url().'/cajas');
        }
        else{
            $unidades=$this->unidades->where('activo',1)->findAll();
            $data=['titulo'=> 'Agregar caja', 'validation'=>$this->validator, 'unidades'=>$unidades];
            echo view('header');
            echo view('cajas/nuevo', $data);
            echo view('footer');
        }
    }



    public function editar($id, $valid=null){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Cajas');
        $permiso2=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Permisos admin');
        if(!$permiso or !$permiso2){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $caja=$this->cajas->where('id_caja',$id)->first();
        $unidades=$this->unidades->where('activo',1)->findAll();
        if($valid!=null){
            $data=['titulo'=> 'Editar caja', 'datos'=>$caja,'unidades'=>$unidades, 'validation'=> $valid];
        }
        else{
            $data=['titulo'=> 'Editar caja', 'datos'=>$caja,'unidades'=>$unidades];
        }
        
        echo view('header');
        echo view('cajas/editar', $data);
        echo view('footer');
    }

    public function actualizar(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Cajas');
        $permiso2=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Permisos admin');
        if(!$permiso or !$permiso2){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        if($this->request->getMethod()== "post" && $this->validate($this->reglas)){
            $date = date("Y-m-d H:i:s");
            $this->cajas->update($this->request->getPost('id_caja'),['nombre'=>$this->request->getPost('nombre'),'numero_caja'=>$this->request->getPost('numero_caja'),'folio'=>$this->request->getPost('folio'),'id_unidad'=>$this->request->getPost('id_unidad'), 'fecha_edit'=>$date]);
            return redirect()->to(base_url().'/cajas');
        }
        else{
            return $this->editar($this->request->getPost('id_caja'), $this->validator);
        }
    }

    public function eliminar($id){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Cajas');
        $permiso2=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Permisos admin');
        if(!$permiso or !$permiso2){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $date = date("Y-m-d H:i:s"); 
        $this->cajas->update($id,['activo'=>0, 'fecha_del'=>$date]);
        return redirect()->to(base_url().'/cajas');
    }

    public function eliminados($activo = 0) {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Cajas');
        $permiso2=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Permisos admin');
        if(!$permiso or !$permiso2){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $cajas=$this->cajas->where('activo',$activo)->findAll();//Igual a Select * from cajas where activo=1
        $data=['titulo'=> 'Cajas eliminadas', 'datos' => $cajas];
        echo view('header');
        echo view('cajas/eliminados', $data);
        echo view('footer');
    }

    public function reingresar($id){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Cajas');
        $permiso2=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Permisos admin');
        if(!$permiso or !$permiso2){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $date = NULL; 
        $this->cajas->update($id,['activo'=>1, 'fecha_del'=>$date]);
        return redirect()->to(base_url().'/cajas/eliminados');
    }

    public function arqueo($id_caja){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Cajas');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $arqueos=$this->arqueoModel->getDatos($id_caja );
        $data=['titulo'=>'Cierres de caja', 'datos'=>$arqueos];
        echo view('header');
        echo view('cajas/arqueos', $data);
        echo view('footer');
    }

    public function nuevo_arqueo(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Cajas');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $session=session();
        $existe=$this->arqueoModel->where(['id_caja' => $session->id_caja, 'estatus'=>1])->countAllResults();

            if($existe>0){
                echo view('header');
                echo view('abierta');
                echo view('footer');
                exit;
            }
        if($this->request->getMethod()=="post"){
            $fecha = date("Y-m-d H:i:s");
            $existe=0;
            $this->arqueoModel->save(['id_caja'=>$this->session->id_caja, 'id_usuario'=>$this->session->id_usuario, 'fecha_inicio'=>$fecha,'monto_inicial'=>$this->request->getPost('monto_inicial'), 'estatus'=>1]);
            $this->session->set('estadoCaja', 1);
            return redirect()->to(base_url().'/cajas');
        }else{
            $caja=$this->cajas->where('id_caja', $this->session->id_caja)->first();
            $data=['titulo'=>'Apertura de caja', 'caja'=>$caja, 'session'=>$session];
            echo view('header');
            echo view('cajas/nuevo_arqueo', $data);
            echo view('footer');
        }
    }
    public function cerrar(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Cajas');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $session=session();
        if($this->request->getMethod()=="post"){
            $fecha = date("Y-m-d H:i:s");
            $this->arqueoModel->update($this->request->getPost('id_arqueo'),['fecha_fin'=>$fecha,'monto_final'=>$this->request->getPost('monto_final'), 'total_ventas'=>$this->request->getPost('total_ventas'), 'estatus'=>0]);
            $this->session->set('estadoCaja', 0);
            return redirect()->to(base_url().'/cajas');
        }else{            
            $arqueo=$this->arqueoModel->where(['id_caja' => $session->id_caja, 'estatus'=>1])->first();
            $montoTotal=$this->ventasModel->totalVentasUsuarioCaja($arqueo['fecha_inicio'], $this->session->id_caja, $this->session->id_usuario);
            $caja=$this->cajas->where('id_caja', $this->session->id_caja)->first();
            $data=['titulo'=>'Carrar caja', 'caja'=>$caja, 'session'=>$session, 'arqueo'=>$arqueo, 'monto'=>$montoTotal];
            echo view('header');
            echo view('cajas/cerrar', $data);
            echo view('footer');
        }
    }

    function generarReporteCierre($id_arqueo){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Cajas');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $data['id_arqueo'] = $id_arqueo;

        echo view('header');
        echo view('cajas/ver_reporte', $data);
        echo view('footer');
    }

    function generaTicket($id_arqueo){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Cajas');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }


        $datosCierre=$this->arqueoModel->where('id_arqueo', $id_arqueo)->first();
        $fechaini=$datosCierre['fecha_inicio'];
        $fechafin=$datosCierre['fecha_fin'];
        $id_usuario=$datosCierre['id_usuario'];
        $id_caja=$datosCierre['id_caja'];
        $datosCajaCliente=$this->ventasModel->where('id_usuario',$id_usuario);
        $datosCajaCliente=$this->ventasModel->where('id_caja',$id_caja);
        $datosCajaCliente=$this->ventasModel->where('fecha_alta>=',$fechaini);
        $datosCajaCliente=$this->ventasModel->where('fecha_alta<=',$fechafin)->findAll();
        $usuarioData=$this->usuarios->where('id_usuario', $id_usuario)->first();

        $nombreTienda=$this->configuracion->select('valor')->where('nombre', 'tienda_nombre')->get()->getRow()->valor;
        $direccionTienda=$this->configuracion->select('valor')->where('nombre', 'tienda_direccion')->get()->getRow()->valor;
        $leyendaTicket=$this->configuracion->select('valor')->where('nombre', 'tienda_leyenda')->get()->getRow()->valor;

        $pdf = new \FPDF('P','mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(10,10,10);
        $pdf->SetTitle("Compra");
        $pdf->SetFont('Arial', 'B',10);
        //ENCABEZADO
        $pdf->Cell(195, 5, "Detalle cierre de caja", 0, 1, 'C');
        $pdf->SetFont('Arial', 'B',9);

        $pdf->image(base_url(). '/images/logotipo.png', 185,10, 20, 20, 'png');
        $pdf->Cell(50, 5,utf8_decode($nombreTienda), 0, 1, 'L');
        $pdf->Cell(20, 5,utf8_decode('Dirección: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,utf8_decode($direccionTienda), 0, 0, 'L');

        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(39 , 5,utf8_decode('Fecha generado recibo: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,date("Y-m-d H:i:s"), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(39 , 5,utf8_decode('Fecha inicio: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,$fechaini, 0, 1, 'L');
        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(39 , 5,utf8_decode('Fecha cierre: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,$fechafin, 0, 1, 'L');

        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(39 , 5,utf8_decode('Id de caja: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,$id_caja, 0, 1, 'L');
        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(39 , 5,utf8_decode('Id de usuario: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,utf8_decode($usuarioData['id_usuario']), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B',9);
        $pdf->Cell(30 , 5,utf8_decode('Nombre: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '',9);
        $pdf->Cell(50, 5,utf8_decode($usuarioData['nombre']), 0, 1, 'L');

        $pdf->Ln();
        //ENCABEZADO TABLA
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(0,0,0,);
        $pdf->setTextColor(255,255,255);
        $pdf->Cell(195, 5, 'Detalle de ventas', 1,1,'C',1);
        $pdf->setTextColor(0,0,0);
        $pdf->Cell(14,5, '#', 1, 0, 'L');
        $pdf->Cell(20,5, 'id Venta', 1, 0, 'L');
        $pdf->Cell(29,5, 'Forma de pago', 1, 0, 'L');
        $pdf->Cell(77,5, utf8_decode('Fecha operación '), 1, 0, 'L');
        $pdf->Cell(25,5, 'id Caja', 1, 0, 'L');
        $pdf->Cell(30,5, 'Total', 1, 1, 'L'); //1 para salto de linea
        
        $pdf->SetFont('Arial', '',8);
        //CONTENIDO TABLA
        $contador=1;
        $sumaTotal=0;
        foreach($datosCajaCliente as $row){
            $pdf->Cell(14,5,$contador, 1, 0, 'L');
            $pdf->Cell(20,5, $row['id_venta'], 1, 0, 'L');
            if($row['forma_pago']==1 or $row['forma_pago']=='001'){
                $pdf->Cell(29,5, 'Efectivo', 1, 0, 'L');
            }else{
                $pdf->Cell(29,5, 'Crédito', 1, 0, 'L');
            }
            $pdf->Cell(77,5, $row['fecha_alta'], 1, 0, 'L');
            $pdf->Cell(25,5, $row['id_caja'], 1, 0, 'L');
            $pdf->Cell(30,5, $row['total'], 1, 1, 'L');
            $contador++;
            $sumaTotal=$row['total']+$sumaTotal;
        }
        $pdf->Ln();
        //CONTENIDO TABLA
        $pdf->SetFont('Arial', 'B',8);
        $pdf->Cell(195,5,'Total cierre: $ '.$sumaTotal, 0,1,'R');

        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output("cierre_pdf.pdf", "I");
    }
}
