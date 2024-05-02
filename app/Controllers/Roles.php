<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RolesModel;
use App\Models\PermisosModel;
use App\Models\DetalleRolesPermisosModel;

class Roles extends BaseController{
    protected $roles, $session, $permisos, $detalleRoles;
    protected $reglas;

    public function __construct(){
        $this->roles=new RolesModel();
        $this->permisos=new PermisosModel();
        $this->detalleRoles=new DetalleRolesPermisosModel();
        $this->session=session();
        helper(['form']);
        $this->reglas=['nombre'=>[
            'rules'=>'required|is_unique[roles.nombre, id_rol,{id_rol}]',
            'errors'=>[
                'required'=>'El campo {field} es obligatorio.',
                'is_unique'=>'Nombre en uso.'
            ]
        ]
    ];


    }

    public function index($activo = 1) {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Roles');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $roles=$this->roles->where('activo',$activo)->findAll();//Igual a Select * from roles where activo=1
        $data=['titulo'=> 'Roles', 'datos' => $roles];

        echo view('header');
        echo view('roles/roles', $data);
        echo view('footer');
    }

    public function nuevo(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Roles');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $data=['titulo'=> 'Agregar rol'];
        echo view('header');
        echo view('roles/nuevo', $data);
        echo view('footer');
    }

    public function insertar(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Roles');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        if($this->request->getMethod()== "post" && $this->validate($this->reglas)){
            $date = date("Y-m-d H:i:s");
            $this->roles->save(['nombre'=>$this->request->getPost('nombre'), 'fecha_alta'=>$date]);
            return redirect()->to(base_url().'/roles');
        }
        else{
            $data=['titulo'=> 'Agregar rol', 'validation'=>$this->validator];
            echo view('header');
            echo view('roles/nuevo', $data);
            echo view('footer');
        }
    }



    public function editar($id, $valid=null){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Roles');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $rol=$this->roles->where('id_rol',$id)->first();
        if($valid!=null){
            $data=['titulo'=> 'Editar rol', 'datos'=>$rol, 'validation'=> $valid];
        }
        else{
            $data=['titulo'=> 'Editar rol', 'datos'=>$rol];
        }
        
        echo view('header');
        echo view('roles/editar', $data);
        echo view('footer');
    }

    public function actualizar(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Roles');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        if($this->request->getMethod()== "post" && $this->validate($this->reglas)){
            $date = date("Y-m-d H:i:s");
            $this->roles->update($this->request->getPost('id_rol'),['nombre'=>$this->request->getPost('nombre'), 'fecha_edit'=>$date]);
            return redirect()->to(base_url().'/roles');
        }
        else{
            return $this->editar($this->request->getPost('id_rol'), $this->validator);
        }
    }

    public function eliminar($id){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Roles');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $date = date("Y-m-d H:i:s"); 
        $this->roles->update($id,['activo'=>0, 'fecha_del'=>$date]);
        return redirect()->to(base_url().'/roles');
    }

    public function eliminados($activo = 0) {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Roles');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $roles=$this->roles->where('activo',$activo)->findAll();//Igual a Select * from roles where activo=1
        $data=['titulo'=> 'Roles eliminados', 'datos' => $roles];
        echo view('header');
        echo view('roles/eliminados', $data);
        echo view('footer');
    }

    public function reingresar($id){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        
        $date = NULL; 
        $this->roles->update($id,['activo'=>1, 'fecha_del'=>$date]);
        return redirect()->to(base_url().'/roles/eliminados');
    }


    public function detalles($id_rol){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Roles');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $permisos=$this->permisos->findAll();
        $permisosAsignados=$this->detalleRoles->where('id_rol', $id_rol)->findAll();
        $datos=array();

        
        foreach ($permisosAsignados as $permisoAsignado){
            $datos[$permisoAsignado['id_permiso']]=true;
        }


        $data=['titulo'=>'Asignar permisos', 'permisos' => $permisos, 'id_rol'=>$id_rol, 'asignado'=>$datos];
        echo view('header');
        echo view('roles/detalles', $data);
        echo view('footer');
    }

    public function guardarPermisos(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Roles');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        if($this->request->getMethod()=="post"){
            $id_rol=$this->request->getPost('id_rol');
            $permisos=$this->request->getPost('permisos');
            
            $this->detalleRoles->where('id_rol', $id_rol)->delete();
            foreach($permisos as $permiso){
                $this->detalleRoles->save(['id_rol' => $id_rol, 'id_permiso'=>$permiso]);
            }
            return redirect()->to(base_url()."/roles");
        }
    }

}
