<?php
namespace App\Models;

use CodeIgniter\Model;
 class RutasModel extends Model{
    protected $table      = 'rutas';
    protected $primaryKey = 'id_ruta';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['ruta', 'estatus'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
 }

?>