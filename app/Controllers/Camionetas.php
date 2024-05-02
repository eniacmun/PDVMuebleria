<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CamionetasModel;
use App\Models\DetalleRolesPermisosModel;

class Camionetas extends BaseController{
    protected $camionetas;
    protected $reglas, $session;
    protected $detalleRoles;

    public function __construct(){
        $this->camionetas=new CamionetasModel();
        $this->detalleRoles=new DetalleRolesPermisosModel();

        $this->session=session();
        helper(['form']);
        $this->reglas=['placa'=>[
            'rules'=>'required|is_unique[camionetas.placa, id_camioneta,{id_camioneta}]',
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
        $camionetas=$this->camionetas->where('activo',$activo)->findAll();//Igual a Select * from camionetas where activo=1
        $data=['titulo'=> 'Camionetas', 'datos' => $camionetas];

        echo view('header');
        echo view('camionetas/camionetas', $data);
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
        $data=['titulo'=> 'Agregar camioneta'];
        echo view('header');
        echo view('camionetas/nuevo', $data);
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
            $this->camionetas->save(['nombre'=>$this->request->getPost('nombre'),'modelo'=>$this->request->getPost('modelo'),'placa'=>$this->request->getPost('placa'), 'fecha_alta'=>$date]);
            return redirect()->to(base_url().'/camionetas');
        }
        else{
            $data=['titulo'=> 'Agregar categoría', 'validation'=>$this->validator];
            echo view('header');
            echo view('camionetas/nuevo', $data);
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
        $camioneta=$this->camionetas->where('id_camioneta',$id)->first();
        if($valid!=null){
            $data=['titulo'=> 'Editar camioneta', 'datos'=>$camioneta, 'validation'=>$valid];
        }
        else{
             $data=['titulo'=> 'Editar camioneta', 'datos'=>$camioneta];
        }        
        echo view('header');
        echo view('camionetas/editar', $data);
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
            $this->camionetas->update($this->request->getPost('id_camioneta'),['nombre'=>$this->request->getPost('nombre'),'modelo'=>$this->request->getPost('modelo'),'placa'=>$this->request->getPost('placa'), 'fecha_edit'=>$date]);
            return redirect()->to(base_url().'/camionetas');
        }
        else{
            return $this->editar($this->request->getPost('id_camioneta'), $this->validator);
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
        $this->camionetas->update($id,['activo'=>0, 'fecha_baja'=>$date]);
        return redirect()->to(base_url().'/camionetas');
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
        $camionetas=$this->camionetas->where('activo',$activo)->findAll();//Igual a Select * from camionetas where activo=1
        $data=['titulo'=> 'Camionetas eliminadas', 'datos' => $camionetas];

        echo view('header');
        echo view('camionetas/eliminados', $data);
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
        $this->camionetas->update($id,['activo'=>1, 'fecha_baja'=>$date]);
        return redirect()->to(base_url().'/camionetas/eliminados');
    }
}
