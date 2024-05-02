<?php
namespace App\Models;

use CodeIgniter\Model;
 class ClientesModel extends Model{
    protected $table      = 'clientes';
    protected $primaryKey = 'id_cliente';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre', 'direccion', 'telefono', 'correo', 'ruta','activo','fecha_alta','fecha_del','fecha_edit'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
 }

?>