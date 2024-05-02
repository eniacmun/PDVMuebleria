<?php
namespace App\Models;

use CodeIgniter\Model;
 class CajasModel extends Model{
    protected $table      = 'cajas';
    protected $primaryKey = 'id_caja';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['numero_caja', 'nombre', 'folio', 'activo', 'id_unidad','fecha_alta', 'fecha_edit','fecha_del'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
 }

?>