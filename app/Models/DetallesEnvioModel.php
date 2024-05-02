<?php
namespace App\Models;

use CodeIgniter\Model;
 class DetallesEnvioModel extends Model{
    protected $table      = 'detalle_envio';
    protected $primaryKey = 'id_detalle_envio';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_envio', 'id_producto','codigo', 'nombre', 'cantidad'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


 }

?>