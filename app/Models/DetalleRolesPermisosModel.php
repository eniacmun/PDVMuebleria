<?php
namespace App\Models;

use CodeIgniter\Model;
 class DetalleRolesPermisosModel extends Model{
    protected $table      = 'detalle_permisos';
    protected $primaryKey = 'id_detalle_permiso';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_detalle_permiso ', 'id_rol', 'id_permiso'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;



    function verificaPermisos($id_rol, $permiso){
      $tieneAcceso=false;
      $this->select('*');
      $this->join('permisos', 'detalle_permisos.id_permiso=permisos.id_permiso');
      $existe=$this->where(['id_rol'=>$id_rol, 'permisos.nombre'=>$permiso])->first();


      if($existe!=null){
         $tieneAcceso=true;
      }
      return $tieneAcceso;
    }
    function obtienePermisos($id_rol){
      $this->select('nombre');
      $this->join('permisos', 'detalle_permisos.id_permiso=permisos.id_permiso');
      $query=$this->where('id_rol', $id_rol)->findAll();
      return $query;
    }
 }

?>