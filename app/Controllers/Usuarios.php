<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Models\CajasModel;
use App\Models\RolesModel;
use App\Models\LogsModel;
use App\Models\DetalleRolesPermisosModel;
use App\Models\ArqueoCajaModel;

class Usuarios extends BaseController{
    protected $usuarios, $cajas, $roles, $detalleRoles, $arqueocajas;
    protected $reglas, $reglasLogin, $reglasCambia, $session, $logs;

    public function __construct(){
        $this->usuarios=new UsuariosModel();
        $this->cajas=new CajasModel();
        $this->roles=new RolesModel();
        $this->logs=new LogsModel();
        $this->detalleRoles=new DetalleRolesPermisosModel();
        $this->arqueocajas=new ArqueoCajaModel();

        $this->session=session();
        helper(['form']);
        $this->reglas=[
            'usuario'=>[
                'rules'=>'required|is_unique[usuarios.usuario, id_usuario,{id_usuario}]',
                'errors'=>['required'=>'El campo {field} es obligatorio.',
                'is_unique'=>'El campo {field} debe ser único.'
            ]], 
            'nombre'=>[
            'rules'=>'required',
            'errors'=>[
                'required'=>'El campo {field} es obligatorio.'
            ]],
            'password'=>[
            'rules'=>'required',
            'errors'=>[
                'required'=>'El campo {field} es obligatorio.'
            ]],
            'repassword'=>[
            'rules'=>'required|matches[password] ',
            'errors'=>[
                'required'=>'El campo {field} es obligatorio.',
                'matches'=>'Las contraseñas no coninciden.'
            ]],
            'id_caja'=>[
            'rules'=>'required',
            'errors'=>[
                'required'=>'El campo {field} es obligatorio.'
            ]],
            'id_rol'=>[
            'rules'=>'required',
            'errors'=>[
                'required'=>'El campo {field} es obligatorio.'
            ]]
        ];
        $this->reglasLogin=[
            'usuario'=>[
                'rules'=>'required',
                'errors'=>['required'=>'El campo {field} es obligatorio.'
            ]], 
            'password'=>[
            'rules'=>'required',
            'errors'=>['required'=>'El campo {field} es obligatorio.'
            ]]
        ];
        $this->reglasCambia=[
            'password'=>[
            'rules'=>'required',
            'errors'=>[
                'required'=>'El campo {field} es obligatorio.'
            ]],
            'repassword'=>[
            'rules'=>'required|matches[password] ',
            'errors'=>[
                'required'=>'El campo {field} es obligatorio.',
                'matches'=>'Las contraseñas no coninciden.'
            ]]
        ];

    }

    public function index($activo = 1) {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Usuarios');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $usuarios=$this->usuarios->where('activo',$activo)->findAll();//Igual a Select * from usuarios where activo=1
        $data=['titulo'=> 'Usuarios', 'datos' => $usuarios];

        echo view('header');
        echo view('usuarios/usuarios', $data);
        echo view('footer');
    }

    public function nuevo(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Usuarios');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $cajas=$this->cajas->where('activo',1)->findAll();
        $roles=$this->roles->where('activo',1)->findAll();
        $data=['titulo'=> 'Agregar usuario', 'cajas'=>$cajas,'roles'=>$roles];
        echo view('header');
        echo view('usuarios/nuevo', $data);
        echo view('footer');
    }

    public function insertar(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Usuarios');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        if($this->request->getMethod()== "post" && $this->validate($this->reglas)){
            $date = date("Y-m-d H:i:s");
            $hash=password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            $this->usuarios->save(['usuario'=>$this->request->getPost('usuario'),
            'nombre'=>$this->request->getPost('nombre'), 
            'id_caja'=>$this->request->getPost('id_caja'), 
            'id_rol'=>$this->request->getPost('id_rol'),
            'password'=>$hash,
            'fecha_alta'=>$date]);
            return redirect()->to(base_url().'/usuarios');
        }
        else{
            $cajas=$this->cajas->where('activo',1)->findAll();
            $roles=$this->roles->where('activo',1)->findAll();
            $data=['titulo'=> 'Agregar usuario', 'validation'=>$this->validator, 'cajas'=>$cajas, 'roles'=>$roles];
            echo view('header');
            echo view('usuarios/nuevo', $data);
            echo view('footer');
        }
    }



    public function editar($id, $valid=null){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Usuarios');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $usuario=$this->usuarios->where('id_usuario',$id)->first();
        if($valid!=null){
            $cajas=$this->cajas->where('activo',1)->findAll();
            $roles=$this->roles->where('activo',1)->findAll();
            $data=['titulo'=> 'Editar usuario', 'datos'=>$usuario, 'validation'=> $valid, 'cajas'=>$cajas, 'roles'=>$roles,];
        }
        else{
            $cajas=$this->cajas->where('activo',1)->findAll();
            $roles=$this->roles->where('activo',1)->findAll();
            $data=['titulo'=> 'Editar usuario', 'datos'=>$usuario, 'cajas'=>$cajas, 'roles'=>$roles,];
        }
        
        echo view('header');
        echo view('usuarios/editar', $data);
        echo view('footer');
    }

    public function actualizar(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Usuarios');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        if($this->request->getMethod()== "post" && $this->validate($this->reglas)){
            $date = date("Y-m-d H:i:s");
            $hash=password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            $this->usuarios->update($this->request->getPost('id_usuario'),['nombre'=>$this->request->getPost('nombre'),
            'usuario'=>$this->request->getPost('usuario'),
            'id_rol'=>$this->request->getPost('id_rol'),
            'id_caja'=>$this->request->getPost('id_caja'), 
            'password'=>$hash,
            'fecha_edit'=>$date]);
            return redirect()->to(base_url().'/usuarios');
        }
        else{
            return $this->editar($this->request->getPost('id_usuario'), $this->validator);
        }
    }

    public function eliminar($id){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Usuarios');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $date = date("Y-m-d H:i:s"); 
        $this->usuarios->update($id,['activo'=>0, 'fecha_del'=>$date]);
        return redirect()->to(base_url().'/usuarios');
    }

    public function eliminados($activo = 0) {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Usuarios');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $usuarios=$this->usuarios->where('activo',$activo)->findAll();//Igual a Select * from usuarios where activo=1
        $data=['titulo'=> 'Usuarios eliminados', 'datos' => $usuarios];
        echo view('header');
        echo view('usuarios/eliminados', $data);
        echo view('footer');
    }

    public function reingresar($id){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Usuarios');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $date = NULL; 
        $this->usuarios->update($id,['activo'=>1, 'fecha_del'=>$date]);
        return redirect()->to(base_url().'/usuarios/eliminados');
    }

    public function login(){
        if(isset($this->session->id_usuario)){ return redirect()->to(base_url().'/inicio');}
        echo view('login');
    }

    public function valida(){
        if($this->request->getMethod()== "post" && $this->validate($this->reglasLogin)){
            $password=$this->request->getPost('password');
            $usuario=$this->request->getPost('usuario');
            $datosusuario=$this->usuarios->where('usuario',$usuario)->first();
            
            

            if($datosusuario!=null){
                $rolusuario=$this->roles->where('id_rol',$datosusuario['id_rol'])->first();
                if(password_verify($password, $datosusuario['password'])){
                    $permisos=$this->detalleRoles->obtienePermisos($rolusuario['id_rol']);
                    $arqueocaja=$this->arqueocajas->where('id_usuario',$datosusuario['id_usuario'])->orderBy('id_arqueo',"DESC")->first();                    
                    if(isset($arqueocaja['estatus'])){
                        $datosSesion=[
                        'id_usuario'=>$datosusuario['id_usuario'],
                        'nombre'=>$datosusuario['nombre'],
                        'id_caja'=>$datosusuario['id_caja'],
                        'id_rol'=>$datosusuario['id_rol'],
                        'rol'=>$rolusuario['nombre'],
                        'permisos'=>$permisos,
                        'estadoCaja'=>$arqueocaja['estatus'],                       
                    ];                        
                    }else{
                        $datosSesion=[
                        'id_usuario'=>$datosusuario['id_usuario'],
                        'nombre'=>$datosusuario['nombre'],
                        'id_caja'=>$datosusuario['id_caja'],
                        'id_rol'=>$datosusuario['id_rol'],
                        'rol'=>$rolusuario['nombre'],
                        'permisos'=>$permisos,                     
                    ];                        
                    }
                    $ip=$_SERVER['REMOTE_ADDR'];
                    $detalles=$_SERVER['HTTP_USER_AGENT'];
                    $this->logs->save([
                        'id_usuario'=>$datosusuario['id_usuario'],
                        'evento'=> 'Inicio de sesión',
                        'fecha'=>date("Y-m-d H:i:s"),
                        'ip'=> $ip,
                        'detalles'=> $detalles
                    ]);

                    $session=session();
                    $session->set($datosSesion);
                    return redirect()->to(base_url().'/inicio');
                }else{
                    $data['error']="Contraseña errónea.";
                    echo view('login',$data);
                }
            }else{
                $data['error']="El usuario no existe.";
                echo view('login',$data);
            }
        }
        else{
            $data=['validation'=>$this->validator];
            echo view('login', $data);
        }
    }

    public function logout(){
        $session=session();

        $ip=$_SERVER['REMOTE_ADDR'];
        $detalles=$_SERVER['HTTP_USER_AGENT'];
        $this->logs->save([
            'id_usuario'=>$session->id_usuario,
            'evento'=> 'Cierre de sesión',
            'fecha'=>date("Y-m-d H:i:s"),
            'ip'=> $ip,
            'detalles'=> $detalles
        ]);

        $session->destroy();//session_destroy
        return redirect()->to(base_url());
    }
    
    public function cambia_password(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        
        $session=session();
        $usuario=$this->usuarios->where('id_usuario',$session->id_usuario)->first();
        $data=['titulo'=> 'Cambiar contraseña', 'usuario'=>$usuario];
        echo view('header');
        echo view('usuarios/cambia_password', $data);
        echo view('footer');
    }

    public function actualiza_password(){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        
        if($this->request->getMethod()== "post" && $this->validate($this->reglasCambia)){
            $session=session();
            $id_usuario=$session->id_usuario;
            $hash=password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

            $this->usuarios->update($id_usuario,['password'=>$hash]);

            $usuario=$this->usuarios->where('id_usuario', $session->id_usuario)->first();
            $data=['titulo'=> 'Cambiar contraseña', 'usuario'=>$usuario, 'mensaje'=>'Contraseña actualizada'];
            echo view('header');
            echo view('usuarios/cambia_password', $data);
            echo view('footer');
        }
        else{
            $session=session();
            $usuario=$this->usuarios->where('id_usuario',$session->id_usuario)->first();
            $data=['titulo'=> 'Cambiar contraseña', 'usuario'=>$usuario, 'validation'=>$this->validator];
            echo view('header');
            echo view('usuarios/cambia_password', $data);
            echo view('footer');
        }
    }
}
