<?php
namespace App\Models;

use CodeIgniter\Model;
 class TemporalEnvioModel extends Model{
    protected $table      = 'temporal_envio';
    protected $primaryKey = 'id_temporal_envio';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['folio', 'idProducto', 'codigo', 'nombre', 'cantidad', 'subtotal'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function porIdProductoEnvio($idProducto, $folio){
      $this->select('*');
      $this->where('folio', $folio);
      $this->where('idProducto', $idProducto);
      $datos=$this->get()->getRow();
      return $datos;
    }
    public function porEnvio($folio){
      $this->select('*');
      $this->where('folio', $folio);
      $datos=$this->findAll();
      return $datos;
    }

    public function actualizarProductoEnvio($idProducto, $folio, $cantidad){
      $this->set('cantidad', $cantidad);
      $this->where('idProducto', $idProducto);
      $this->where('folio', $folio);
      $this->update();
    }

    public function eliminarProductoEnvio($idProducto, $folio){
      $this->where('idProducto', $idProducto);
      $this->where('folio', $folio);
      $this->delete();
    }

    public function eliminarEnvio($folio){
      $this->where('folio', $folio);
      $this->delete();
    }
 }

?>