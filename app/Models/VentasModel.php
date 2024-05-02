<?php
namespace App\Models;

use CodeIgniter\Model;
 class VentasModel extends Model{
    protected $table      = 'ventas';
    protected $primaryKey = 'id_venta ';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['folio', 'total', 'id_usuario', 'id_caja', 'id_cliente', 'forma_pago','pagado','pendiente','comision','tipo_credito', 'activo', 'fecha_alta'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    public function insertaVenta($id_venta, $total, $id_usuario, $id_caja, $id_cliente, $forma_pago, $tipo_credito=null, $pagado=null, $pendiente=null, $comision=0 ){
        $this->insert([
            'folio'=>$id_venta,
            'total'=>$total,
            'id_usuario'=>$id_usuario,
            'id_caja'=>$id_caja,
            'id_cliente'=>$id_cliente,            
            'forma_pago'=>$forma_pago,
            'tipo_credito'=>$tipo_credito,
            'pagado'=>$pagado,
            'pendiente'=>$pendiente,
            'comision'=>$comision,
            'fecha_alta'=>date("Y-m-d H:i:s")
        ]);

        return $this->insertID();
    }

    public function obtener($activo=1){
        $this->select('ventas.*,u.usuario AS cajero, c.nombre AS cliente');
        $this->join('usuarios AS u', 'ventas.id_usuario=u.id_usuario');//INNER JOIN
        $this->join('clientes AS c', 'ventas.id_cliente=c.id_cliente');//INNER JOIN
        $this->where('ventas.activo', $activo);
        $this->orderBy('ventas.fecha_alta', 'DESC');

        $datos=$this->findAll();
        return $datos;

    }

    public function totalDia($fecha){
        $where="activo = 1 AND DATE(fecha_alta)='$fecha'";
        return $this->where($where)->countAllResults();//num_rows
    }
    public function totalVentas($fecha){
        $this->select("sum(total) as total");
        $where="activo = 1 AND DATE(fecha_alta)='$fecha'";
        return $this->where($where)->first();//num_rows
    }
    public function totalVentasUsuarioCaja($fecha, $id_caja, $id_usuario){
        $this->select("sum(total) as total");
        $where="activo = 1 AND fecha_alta>='$fecha' AND id_caja=$id_caja AND id_usuario=$id_usuario";
        return $this->where($where)->first();//num_rows
    }
 }

?>