<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientesModel;
use App\Models\DetalleRolesPermisosModel;
use App\Models\RutasModel;

class Clientes extends BaseController{
    protected $clientes, $rutas;
    protected $reglas, $session;
    protected $detalleRoles;
  
    public function __construct(){
        $this->clientes=new ClientesModel();
        $this->detalleRoles=new DetalleRolesPermisosModel();
        $this->rutas=new RutasModel();
        $this->session=session();
        helper(['form']);
        $this->reglas=[
        'nombre'=>['rules'=>'required','errors'=>[
            'required'=>'El campo {field} es obligatorio.',
        ]], 
        'direccion'=>['rules'=>'required','errors'=>[
            'required'=>'El campo {field} es obligatorio.'
        ]],
        'telefono'=> ['rules'=>'required','errors'=>[
            'required'=>'El campo {field} es obligatorio.'
        ]],
        'correo'=>['rules'=>'required','errors'=>[
            'required'=>'El campo {field} es obligatorio.'
        ]],
        'ruta'=>['rules'=>'required','errors'=>[
            'required'=>'El campo {field} es obligatorio.'
        ]]   
        ];
    }

    public function index($activo = 1) {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú clientes');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $clientes=$this->clientes->where('activo',$activo)->findAll();//Igual a Select * from clientes where activo=1
        $data=['titulo'=> 'Clientes', 'datos' => $clientes];

        echo view('header');
        echo view('clientes/clientes', $data);
        echo view('footer');
    }

    public function nuevo(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú clientes');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $rutas=$this->rutas->where('estatus',1)->findAll();
        $data=['titulo'=> 'Agregar cliente', 'rutas'=>$rutas];
        echo view('header');
        echo view('clientes/nuevo', $data);
        echo view('footer');
    }

    public function insertar(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú clientes');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        if($this->request->getMethod()=="post" && $this->validate($this->reglas)){
            $date = date("Y-m-d H:i:s");
            $this->clientes->save([
            'nombre'=>$this->request->getPost('nombre'), 
            'fecha_alta'=>$date,
            'direccion'=>$this->request->getPost('direccion'), 
            'telefono'=>$this->request->getPost('telefono'),
            'correo'=>$this->request->getPost('correo'),
            'ruta'=>$this->request->getPost('ruta'), 
            ]);
            return redirect()->to(base_url().'/clientes');
        }
        else{
            $data=['titulo'=> 'Agregar cliente', 'validation'=>$this->validator];
            echo view('header');
            echo view('clientes/nuevo', $data);
            echo view('footer');
        }
    }



    public function editar($id, $valid=null){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú clientes');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $cliente=$this->clientes->where('id_cliente',$id)->first();
        $rutas=$this->rutas->where('estatus',1)->findAll();
        if($valid!=null){
            $data=['titulo'=> 'Editar cliente', 'cliente'=>$cliente,'rutas'=>$rutas,'validation'=>$valid];
        }
        else{
            $data=['titulo'=> 'Editar cliente', 'cliente'=>$cliente,'rutas'=>$rutas ];
        }
        echo view('header');
        echo view('clientes/editar', $data);
        echo view('footer');
    }

    public function actualizar(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú clientes');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        if($this->request->getMethod()== "post" && $this->validate($this->reglas)){
            $date = date("Y-m-d H:i:s");
            $this->clientes->update($this->request->getPost('id_cliente'),[
            'nombre'=>$this->request->getPost('nombre'), 
            'fecha_edit'=>$date,
            'direccion'=>$this->request->getPost('direccion'), 
            'telefono'=>$this->request->getPost('telefono'),
            'correo'=>$this->request->getPost('correo'),
            'ruta'=>$this->request->getPost('ruta')
            ]);
            return redirect()->to(base_url().'/clientes');
        }
        else{
            return $this->editar($this->request->getPost('id_cliente'), $this->validator);
        }
        
        
        return redirect()->to(base_url().'/clientes');
    }

    public function eliminar($id){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú clientes');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $date = date("Y-m-d H:i:s"); 
        $this->clientes->update($id,['activo'=>0, 'fecha_del'=>$date]);
        return redirect()->to(base_url().'/clientes');
    }

    public function eliminados($activo = 0) {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú clientes');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $clientes=$this->clientes->where('activo',$activo)->findAll();//Igual a Select * from clientes where activo=1
        $data=['titulo'=> 'Clientes eliminadas', 'datos' => $clientes];
        echo view('header');
        echo view('clientes/eliminados', $data);
        echo view('footer');
    }

    public function reingresar($id){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Menú clientes');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $date = NULL; 
        $this->clientes->update($id,['activo'=>1, 'fecha_del'=>$date]);
        return redirect()->to(base_url().'/clientes/eliminados');
    }

    public function autocompleteData(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $returnData=array();
        $valor=$this->request->getGet('term');

        $clientes=$this->clientes->like('nombre', $valor)->where('activo', 1)->findAll();
        if(!empty($clientes)){
            foreach($clientes as $row){
                $data['id_cliente']=$row['id_cliente'];
                $data['value']=$row['nombre'];
                array_push($returnData, $data);
            }
        }

        echo json_encode($returnData);
    }
}
