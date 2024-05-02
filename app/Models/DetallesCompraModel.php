<?php
namespace App\Models;

use CodeIgniter\Model;
 class DetallesCompraModel extends Model{
    protected $table      = 'detalle_compra';
    protected $primaryKey = 'id_detalle_compra';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_compra', 'idProducto', 'nombre', 'cantidad', 'precio','fecha_alta'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    public function insertaCompra($id_compra, $total, $id_usuario){
        $this->insert([
            'folio'=>$id_compra,
            'total'=>$total,
            'id_usuario'=>$id_usuario
        ]);

        return $this->insertID();
    }
 }

?>