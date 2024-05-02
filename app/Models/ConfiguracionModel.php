<?php
namespace App\Models;

use CodeIgniter\Model;
 class ConfiguracionModel extends Model{
    protected $table      = 'configuracion';
    protected $primaryKey = 'id_configuracion ';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $useSoftUpdates = false;
    protected $useSoftCreates = false;

    protected $allowedFields = ['nombre', 'valor'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
 }

?>