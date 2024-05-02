<?php
namespace App\Models;

use CodeIgniter\Model;
 class RolesModel extends Model{
    protected $table      = 'roles';
    protected $primaryKey = 'id_rol ';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre', 'activo', 'fecha_alta', 'fecha_edit','fecha_del'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
 }

?>