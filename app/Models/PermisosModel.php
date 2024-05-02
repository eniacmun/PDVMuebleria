<?php
namespace App\Models;

use CodeIgniter\Model;
 class PermisosModel extends Model{
    protected $table      = 'permisos';
    protected $primaryKey = 'id_permiso';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre', 'tipo'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
 }

?>