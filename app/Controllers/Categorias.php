<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoriasModel;
use App\Models\DetalleRolesPermisosModel;

class Categorias extends BaseController{
    protected $categorias;
    protected $reglas, $session;
    protected $detalleRoles;

    public function __construct(){
        $this->categorias=new CategoriasModel();
        $this->detalleRoles=new DetalleRolesPermisosModel();

        $this->session=session();
        helper(['form']);
        $this->reglas=['nombre'=>[
            'rules'=>'required|is_unique[categorias.nombre, id_categoria,{id_categoria}]',
            'errors'=>[
                'required'=>'El campo {field} es obligatorio.',
                'is_unique'=>'El campo {field} debe ser único.'
            ]
        ]
        ];
    }

    public function index($activo = 1) {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Categorías');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $categorias=$this->categorias->where('activo',$activo)->findAll();//Igual a Select * from categorias where activo=1
        $data=['titulo'=> 'Categorias', 'datos' => $categorias];

        echo view('header');
        echo view('categorias/categorias', $data);
        echo view('footer');
    }

    public function nuevo(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Categorías');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $data=['titulo'=> 'Agregar categoria'];
        echo view('header');
        echo view('categorias/nuevo', $data);
        echo view('footer');
    }

    public function insertar(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Categorías');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        if($this->request->getMethod()== "post" && $this->validate($this->reglas)){
            $date = date("Y-m-d H:i:s"); 
            $this->categorias->save(['nombre'=>$this->request->getPost('nombre'), 'fecha_alta'=>$date]);
            return redirect()->to(base_url().'/categorias');
        }
        else{
            $data=['titulo'=> 'Agregar categoría', 'validation'=>$this->validator];
            echo view('header');
            echo view('categorias/nuevo', $data);
            echo view('footer');
        }
       
    }

    public function editar($id, $valid=null){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Categorías');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $categoria=$this->categorias->where('id_categoria',$id)->first();
        if($valid!=null){
            $data=['titulo'=> 'Editar categoria', 'datos'=>$categoria, 'validation'=>$valid];
        }
        else{
             $data=['titulo'=> 'Editar categoria', 'datos'=>$categoria];
        }        
        echo view('header');
        echo view('categorias/editar', $data);
        echo view('footer');
    }

    public function actualizar(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Categorías');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        if($this->request->getMethod()=="post" && $this->validate($this->reglas)){
            $date = date("Y-m-d H:i:s"); 
            $this->categorias->update($this->request->getPost('id_categoria'),['nombre'=>$this->request->getPost('nombre'), 'fecha_edit'=>$date]);
            return redirect()->to(base_url().'/categorias');
        }
        else{
            return $this->editar($this->request->getPost('id_categoria'), $this->validator);
        }
    }

    public function eliminar($id){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Categorías');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $date = date("Y-m-d H:i:s"); 
        $this->categorias->update($id,['activo'=>0, 'fecha_del'=>$date]);
        return redirect()->to(base_url().'/categorias');
    }

    public function eliminados($activo = 0) {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Categorías');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $categorias=$this->categorias->where('activo',$activo)->findAll();//Igual a Select * from categorias where activo=1
        $data=['titulo'=> 'Categorias eliminadas', 'datos' => $categorias];

        echo view('header');
        echo view('categorias/eliminados', $data);
        echo view('footer');
    }

    public function reingresar($id){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Categorías');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $date = NULL; 
        $this->categorias->update($id,['activo'=>1, 'fecha_del'=>$date]);
        return redirect()->to(base_url().'/categorias/eliminados');
    }
}
