<?php
namespace App\Models;

use CodeIgniter\Model;
 class ProductosModel extends Model{
    protected $table      = 'productos';
    protected $primaryKey = 'idProducto';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['codigo','nombre', 'precio_venta', 'precio_compra', 'activo', 'descripcion','fecha_alta','fecha_del','fecha_edit','cantidad','stock_minimo','inventariable','id_categoria','claveSucursal' ];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


   public function actualizaStock($idProducto, $cantidad, $operador='+'){
      if($operador=='+'){
         $this->set('cantidad', "cantidad $operador $cantidad", false);
         $this->where('idProducto', $idProducto);
         $this->update();
      }      
      else{
         $this->set('cantidad', "cantidad $operador $cantidad", false);
         $this->where('idProducto', $idProducto);
         $this->update();
      }
   }

   public function totalProductos(){
      return $this->where('activo', 1)->countAllResults();//num_rows
   }

   public function productosMinimo(){
      $where="stock_minimo >= cantidad AND inventariable=1 AND activo=1";
      $this->where($where);
      $sql=$this->countAllResults();
      return $sql;
   }

   public function getproductosMinimo(){
      $where="stock_minimo >= cantidad AND inventariable=1 AND activo=1";
      return $this->where($where)->findAll();
   }
 }

?>