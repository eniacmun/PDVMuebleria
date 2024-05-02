<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnidadesModel;
use App\Models\DetalleRolesPermisosModel;

class Unidades extends BaseController{
    protected $unidades;
    protected $reglas, $session;
    protected $detalleRoles;

    public function __construct(){
        $this->unidades=new UnidadesModel();
        $this->detalleRoles=new DetalleRolesPermisosModel();

        $this->session=session();
        helper(['form']);
        $this->reglas=['nombre'=>[
            'rules'=>'required',
            'errors'=>[
                'required'=>'El campo {field} es obligatorio.'
            ]
        ], 'nombreCort'=>[
            'rules'=>'required',
            'errors'=>[
                'required'=>'El campo {field} es obligatorio.'
            ]
        ], 'telefono'=>[
            'rules'=>'required',
            'errors'=>[
                'required'=>'El campo {field} es obligatorio.'
            ]
        ], 'direccion'=>[
            'rules'=>'required',
            'errors'=>[
                'required'=>'El campo {field} es obligatorio.'
            ]
        ]
        ];
    }

    public function index($activo = 1) {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Unidades');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $unidades=$this->unidades->where('activo',$activo)->findAll();//Igual a Select * from unidades where activo=1
        $data=['titulo'=> 'Sucursales', 'datos' => $unidades];

        echo view('header');
        echo view('unidades/unidades', $data);
        echo view('footer');
    }

    public function nuevo(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Unidades');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $data=['titulo'=> 'Agregar sucursal'];
        echo view('header');
        echo view('unidades/nuevo', $data);
        echo view('footer');
    }

    public function insertar(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Unidades');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        if($this->request->getMethod()== "post" && $this->validate($this->reglas)){
            $date = date("Y-m-d H:i:s");
            $this->unidades->save(['nombre'=>$this->request->getPost('nombre'),'nombreCort'=>$this->request->getPost('nombreCort'),'telefono'=>$this->request->getPost('telefono'),'direccion'=>$this->request->getPost('direccion'), 'fecha_alta'=>$date]);
            return redirect()->to(base_url().'/unidades');
        }
        else{
            $data=['titulo'=> 'Agregar sucursal', 'validation'=>$this->validator];
            echo view('header');
            echo view('unidades/nuevo', $data);
            echo view('footer');
        }
    }



    public function editar($id, $valid=null){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Unidades');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $unidad=$this->unidades->where('id_unidad',$id)->first();
        if($valid!=null){
            $data=['titulo'=> 'Editar sucursal', 'datos'=>$unidad, 'validation'=> $valid];
        }
        else{
            $data=['titulo'=> 'Editar sucursal', 'datos'=>$unidad];
        }
        
        echo view('header');
        echo view('unidades/editar', $data);
        echo view('footer');
    }

    public function actualizar(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Unidades');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        if($this->request->getMethod()== "post" && $this->validate($this->reglas)){
            $date = date("Y-m-d H:i:s");
            $this->unidades->update($this->request->getPost('id_unidad'),['nombre'=>$this->request->getPost('nombre'),'nombreCort'=>$this->request->getPost('nombreCort'),'telefono'=>$this->request->getPost('telefono'),'direccion'=>$this->request->getPost('direccion'), 'fecha_edit'=>$date]);
            return redirect()->to(base_url().'/unidades');
        }
        else{
            return $this->editar($this->request->getPost('id_unidad'), $this->validator);
        }
    }

    public function eliminar($id){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Unidades');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $date = date("Y-m-d H:i:s"); 
        $this->unidades->update($id,['activo'=>0, 'fecha_del'=>$date]);
        return redirect()->to(base_url().'/unidades');
    }

    public function eliminados($activo = 0) {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Unidades');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $unidades=$this->unidades->where('activo',$activo)->findAll();//Igual a Select * from unidades where activo=1
        $data=['titulo'=> 'Sucursales eliminadas', 'datos' => $unidades];
        echo view('header');
        echo view('unidades/eliminados', $data);
        echo view('footer');
    }

    public function reingresar($id){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Unidades');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $date = NULL; 
        $this->unidades->update($id,['activo'=>1, 'fecha_del'=>$date]);
        return redirect()->to(base_url().'/unidades/eliminados');
    }
}
