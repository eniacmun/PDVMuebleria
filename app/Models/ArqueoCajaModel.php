<?php
namespace App\Models;

use CodeIgniter\Model;
 class ArqueoCajaModel extends Model{
    protected $table      = 'arqueo_cajas';
    protected $primaryKey = 'id_arqueo';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_caja', 'id_usuario', 'fecha_inicio', 'fecha_fin','monto_inicial', 'monto_final','total_ventas', 'estatus'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
 
   
    public function getDatos($id_caja){
      $this->select('arqueo_cajas.*, cajas.nombre');
      $this->join('cajas', 'arqueo_cajas.id_caja=cajas.id_caja');
      $this->where('arqueo_cajas.id_caja', $id_caja);
      $this->orderBy('arqueo_cajas.id_caja', 'DESC');

      $datos=$this->findAll();
      return $datos;
    }

   }

?>