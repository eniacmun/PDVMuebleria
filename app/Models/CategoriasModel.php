<?php
namespace App\Models;

use CodeIgniter\Model;
 class CategoriasModel extends Model{
    protected $table      = 'categorias';
    protected $primaryKey = 'id_categoria ';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre', 'activo', 'fecha_del', 'fecha_alta', 'fecha_edit'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
 }

?>