<?php
namespace App\Models;

use CodeIgniter\Model;
 class DetallesInventarioModel extends Model{
    protected $table      = 'detalle_inventario';
    protected $primaryKey = 'id_detalle_inventario';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['codigo', 'idProducto', 'nombre', 'id_unidad', 'cantidad','activo'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

   public function actualizaStock($idProducto, $cantidad, $id_unidad, $operador='+'){
      $this->set('cantidad', "cantidad $operador $cantidad", false);
      $this->where('idProducto', $idProducto);
      $this->where('id_unidad', $id_unidad);
      $this->update();
   }
 }

?>