<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TemporalEnvioModel;
use App\Models\ProductosModel;
use App\Models\CajasModel;
use App\Models\UnidadesModel;
use App\Models\DetallesInventarioModel;


class TemporalEnvio extends BaseController{
    protected $temporal_envio, $productos, $session, $cajas, $unidades, $detalle_inventario;

    public function __construct(){ 
        $this->temporal_envio=new TemporalEnvioModel();
        $this->productos=new ProductosModel();
        $this->cajas=new CajasModel();
        $this->unidades=new UnidadesModel();
        $this->detalle_inventario=new DetallesInventarioModel();
        $this->session=session();
    }

    public function inserta($idProducto, $cantidad, $id_envio){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $error='';
        $producto=$this->productos->where('idProducto', $idProducto)->first();
        if($producto){
            $datosExiste=$this->temporal_envio->porIdProductoEnvio($idProducto, $id_envio);

            if($datosExiste){
                $cantidad=$datosExiste->cantidad+$cantidad;
                $subtotal=$cantidad*$datosExiste->precio;

                $this->temporal_envio->actualizarProductoEnvio($idProducto, $id_envio, $cantidad, $subtotal);
            }else{
                $subtotal=$cantidad*$producto['precio_envio'];
                $this->temporal_envio->save([
                    'folio'=>$id_envio,
                    'idProducto'=>$idProducto,
                    'codigo'=>$producto['codigo'],
                    'nombre'=>$producto['nombre'],
                    'precio'=>$producto['precio_envio'],
                    'cantidad'=>$cantidad,
                    'subtotal'=>$subtotal,
                ]);
            }
        }else{
            $error='No existe el producto.';

        }
        $res['datos']=$this->cargaProductos($id_envio);
        $res['total']=number_format($this->totalProductos($id_envio),2,'.',',');
        $res['error']=$error;
        echo json_encode($res);
    }
    public function insertaEnvio($idProducto, $cantidad, $id_envio){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $error='';
        $producto=$this->productos->where('idProducto', $idProducto)->first();
        if($producto){

            $datosExiste=$this->temporal_envio->porIdProductoEnvio($idProducto, $id_envio);

            if($datosExiste){
                $cantidad=$datosExiste->cantidad+$cantidad;

                $this->temporal_envio->actualizarProductoEnvio($idProducto, $id_envio, $cantidad);
            }else{
                $this->temporal_envio->save([
                    'folio'=>$id_envio,
                    'idProducto'=>$idProducto,
                    'codigo'=>$producto['codigo'],
                    'nombre'=>$producto['nombre'],
                    'cantidad'=>$cantidad,
                ]);
            }
        }else{
            $error='No existe el producto.';

        }
        $res['datos']=$this->cargaProductos($id_envio);
        //$res['total']=number_format($this->totalProductos($id_envio),2,'.',',');
        $res['error']=$error;
        echo json_encode($res);
    }

    public  function cargaProductos($id_envio){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $resultado=$this->temporal_envio->porEnvio($id_envio);
        $fila='';
        $numFila=0;
        foreach($resultado as $row){
            $numFila++;
            $fila.="<tr id='fila".$numFila."'>";
            $fila.="<td>".$numFila."</td>";
            $fila.="<td>".$row['codigo']."</td>";
            $fila.="<td>".$row['nombre']."</td>";
            $fila.="<td>".$row['cantidad']."</td>";
            $fila.="<td><a onclick=\"eliminaProducto(".$row['idProducto'].", '".$id_envio."')\" class='borrar'><span class='fas fa-fw fa-trash'></span></a></td>";
            $fila.="<td><a onclick=\"eliminaProductos(".$row['idProducto'].", '".$id_envio."')\" class='borrar'><span class='fas fa-fw fa-trash btn-danger'></span></a></td>";
            $fila.="</tr>";
        }
        return $fila;
    }

    public  function totalProductos($id_envio){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $resultado=$this->temporal_envio->porEnvio($id_envio);
        $total=0;
        foreach($resultado as $row){
            $total+=$row['subtotal'];
        }
        return $total;
    }

    public  function eliminar($idProducto, $id_envio){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $datosExiste=$this->temporal_envio->porIdProductoEnvio($idProducto, $id_envio);
        if($datosExiste){
            if($datosExiste->cantidad>1){
                $cantidad=$datosExiste->cantidad-1;
                $this->temporal_envio->actualizarProductoEnvio($idProducto, $id_envio, $cantidad);
            }else{
                $this->temporal_envio->eliminarProductoEnvio($idProducto, $id_envio);
            }
        }
        $res['datos']=$this->cargaProductos($id_envio);
        $res['error']='';
        echo json_encode($res);
    }
    public  function eliminarTodo($idProducto, $id_envio){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $datosExiste=$this->temporal_envio->porIdProductoEnvio($idProducto, $id_envio);
        if($datosExiste){
            $this->temporal_envio->eliminarProductoEnvio($idProducto, $id_envio);
        }
        $res['datos']=$this->cargaProductos($id_envio);
        $res['error']='';
        echo json_encode($res);
    }


    public function consultaEnvio($idProducto, $cantidad, $id_envio, $id_caja){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $error='';
        $producto=$this->productos->where('idProducto', $idProducto)->first();
        $caja=$this->cajas->where('id_caja', $id_caja)->first();
        $idCaja=$caja['id_unidad'];
        $this->detalle_inventario->where('idProducto', $idProducto);
        $detalle_inventario=$this->detalle_inventario->where('id_unidad', $idCaja)->first();
        if($producto){
            $datosExiste=$this->temporal_envio->porIdProductoEnvio($idProducto, $id_envio);

            if($datosExiste){
                $cantidad=$datosExiste->cantidad+$cantidad;
            }
        }else{
            $error='No existe el producto.';
        }
        if($detalle_inventario){
            $res['InventarioSuc']=$detalle_inventario['cantidad'];
        }else{
            $res['InventarioSuc']='No hay en inventario para enviar en esta surcursal';
        }
        $res['InventarioTot']=$producto['cantidad'];
        $res['cantidad']= $cantidad;
        $res['error']=$error;
        echo json_encode($res);
    }
}
