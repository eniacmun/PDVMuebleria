<?php
namespace App\Models;

use CodeIgniter\Model;
 class EnviosModel extends Model{
    protected $table      = 'envios';
    protected $primaryKey = 'id_envio';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_sucursalSalida', 'id_sucursalEntrada', 'id_camioneta', 'folio', 'estado', 'fecha_alta', 'fecha_llegada'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    public function insertaEnvio($id_envio, $id_sucursalSalida, $id_sucursalEntrada, $id_camioneta){
        $this->insert([
            'folio'=>$id_envio,
            'id_sucursalSalida'=>$id_sucursalSalida,
            'id_sucursalEntrada'=>$id_sucursalEntrada,
            'id_camioneta'=>$id_camioneta,
            'fecha_alta'=>date("Y-m-d H:i:s")
        ]);

        return $this->insertID();
    }

    public function obtener($estado=1){
        $this->select('envios.*,u.nombre AS id_sucursalSalida,w.nombre AS id_sucursalEntrada, c.nombre AS id_camioneta, c.placa AS placa');
        $this->join('unidades AS u', 'envios.id_sucursalSalida=u.id_unidad');//INNER JOIN
        $this->join('unidades AS w', 'envios.id_sucursalEntrada=w.id_unidad');//INNER JOIN
        $this->join('camionetas AS c', 'envios.id_camioneta=c.id_camioneta');//INNER JOIN
        $this->where('envios.estado', $estado);
        $this->orderBy('envios.fecha_alta', 'DESC');

        $datos=$this->findAll();
        return $datos;

    }

    public function totalDia($fecha){
        $where="estado = 1 AND DATE(fecha_alta)='$fecha'";
        return $this->where($where)->countAllResults();//num_rows
    }
    public function totalEnvios($fecha){
        $this->select("sum(total) as total");
        $where="estado = 1 AND DATE(fecha_alta)='$fecha'";
        return $this->where($where)->first();//num_rows
    }
    public function totalEnviosUsuarioCaja($fecha, $id_caja, $id_usuario){
        $this->select("sum(total) as total");
        $where="estado = 1 AND fecha_alta>='$fecha' AND id_caja=$id_caja AND id_usuario=$id_usuario";
        return $this->where($where)->first();//num_rows
    }
 }

?>