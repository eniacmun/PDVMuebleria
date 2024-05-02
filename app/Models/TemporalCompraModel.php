<?php
namespace App\Models;

use CodeIgniter\Model;
 class TemporalCompraModel extends Model{
    protected $table      = 'temporal_compra';
    protected $primaryKey = 'id_temporal_compra';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['folio', 'idProducto', 'codigo', 'nombre', 'cantidad', 'precio', 'subtotal'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function porIdProductoCompra($idProducto, $folio){
      $this->select('*');
      $this->where('folio', $folio);
      $this->where('idProducto', $idProducto);
      $datos=$this->get()->getRow();
      return $datos;
    }
    public function porCompra($folio){
      $this->select('*');
      $this->where('folio', $folio);
      $datos=$this->findAll();
      return $datos;
    }

    public function actualizarProductoCompra($idProducto, $folio, $cantidad, $subtotal){
      $this->set('cantidad', $cantidad);
      $this->set('subtotal', $subtotal);
      $this->where('idProducto', $idProducto);
      $this->where('folio', $folio);
      $this->update();
    }

    public function eliminarProductoCompra($idProducto, $folio){
      $this->where('idProducto', $idProducto);
      $this->where('folio', $folio);
      $this->delete();
    }

    public function eliminarCompra($folio){
      $this->where('folio', $folio);
      $this->delete();
    }
 }

?>