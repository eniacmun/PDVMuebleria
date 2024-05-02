<?php
namespace App\Models;

use CodeIgniter\Model;
 class UsuariosModel extends Model{
    protected $table      = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['usuario', 'password', 'nombre', 'id_caja', 'id_rol', 'activo', 'fecha_alta', 'fecha_edit','fecha_del'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
 }

?>