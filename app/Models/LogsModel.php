<?php
namespace App\Models;

use CodeIgniter\Model;
 class LogsModel extends Model{
    protected $table      = 'logs';
    protected $primaryKey = 'id_log';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_usuario', 'evento', 'fecha', 'ip', 'detalles'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
 }

?>