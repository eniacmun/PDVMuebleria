<?php
namespace App\Models;

use CodeIgniter\Model;
 class ComprasModel extends Model{
    protected $table      = 'compras';
    protected $primaryKey = 'id_compra ';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['folio', 'total', 'id_usuario', 'activo', 'fecha_alta'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    public function insertaCompra($id_compra, $total, $id_usuario){
        $this->insert([
            'folio'=>$id_compra,
            'total'=>$total,
            'id_usuario'=>$id_usuario,
            'fecha_alta'=>date("Y-m-d H:i:s")
        ]);

        return $this->insertID();
    }
 }

?>