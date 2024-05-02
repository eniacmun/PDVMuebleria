<?php
namespace App\Models;

use CodeIgniter\Model;
 class CamionetasModel extends Model{
    protected $table      = 'camionetas';
    protected $primaryKey = 'id_camioneta';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['modelo', 'nombre', 'placa', 'activo', 'fecha_alta', 'fecha_edit','fecha_baja'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
 }

?>