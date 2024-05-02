<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LogsModel;
use App\Models\DetalleRolesPermisosModel;

class Logs extends BaseController{
    protected $logs;
    protected $reglas, $session;
    protected $detalleRoles;

    public function __construct(){
        $this->logs=new LogsModel();
        $this->detalleRoles=new DetalleRolesPermisosModel();

        $this->session=session();
        helper(['form']);


    }

    public function index($activo = 1) {
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $permiso=$this->detalleRoles->verificaPermisos($this->session->id_rol, 'Logs de acceso');

        if(!$permiso){
            echo view('header');
            echo view('401');
            echo view('footer');
            exit();
        }
        $logs=$this->logs->findAll();//Igual a Select * from logs where activo=1
        $data=['titulo'=> 'Logs', 'datos' => $logs];

        echo view('header');
        echo view('logs/logs', $data);
        echo view('footer');
    }
}
