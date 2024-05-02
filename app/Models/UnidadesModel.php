<?php
namespace App\Models;

use CodeIgniter\Model;
 class UnidadesModel extends Model{
    protected $table      = 'unidades';
    protected $primaryKey = 'id_unidad ';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre', 'nombreCort', 'telefono', 'direccion', 'activo', 'fecha_alta', 'fecha_edit','fecha_del'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
 }

?>