<?php
namespace App\Models;

use CodeIgniter\Model;
 class DetallesVentaModel extends Model{
    protected $table      = 'detalle_venta';
    protected $primaryKey = 'id_detalle_venta';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_venta', 'idProducto', 'nombre', 'cantidad', 'precio','fecha_alta'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


 }

?>