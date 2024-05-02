<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TemporalCompraModel;
use App\Models\ProductosModel;

class TemporalCompra extends BaseController{
    protected $temporal_compra, $productos, $session;

    public function __construct(){ 
        $this->temporal_compra=new TemporalCompraModel();
        $this->productos=new ProductosModel();
        $this->session=session();
    }

    public function inserta($idProducto, $cantidad, $id_compra){//para compras
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $error='';
        $producto=$this->productos->where('idProducto', $idProducto)->first();
        if($producto){
            $datosExiste=$this->temporal_compra->porIdProductoCompra($idProducto, $id_compra);

            if($datosExiste){
                $cantidad=$datosExiste->cantidad+$cantidad;
                $subtotal=$cantidad*$datosExiste->precio;

                $this->temporal_compra->actualizarProductoCompra($idProducto, $id_compra, $cantidad, $subtotal);
            }else{
                $subtotal=$cantidad*$producto['precio_compra'];
                $this->temporal_compra->save([
                    'folio'=>$id_compra,
                    'idProducto'=>$idProducto,
                    'codigo'=>$producto['codigo'],
                    'nombre'=>$producto['nombre'],
                    'precio'=>$producto['precio_compra'],
                    'cantidad'=>$cantidad,
                    'subtotal'=>$subtotal,
                ]);
            }
        }else{
            $error='No existe el producto.';

        }
        $res['datos']=$this->cargaProductos($id_compra);
        $res['total']=number_format($this->totalProductos($id_compra),2,'.',',');
        $res['error']=$error;
        echo json_encode($res);
    }
    public function insertaVenta($idProducto, $cantidad, $id_venta, $forma_pago){//para ventas
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $error='';
        $comision=0;
        $producto=$this->productos->where('idProducto', $idProducto)->first();
        if($producto){
            $datosExiste=$this->temporal_compra->porIdProductoCompra($idProducto, $id_venta);

            if($datosExiste){
                $cantidad=$datosExiste->cantidad+$cantidad;
                $subtotal=$cantidad*$datosExiste->precio;

                $this->temporal_compra->actualizarProductoCompra($idProducto, $id_venta, $cantidad, $subtotal);
            }else{
                $subtotal=$cantidad*$producto['precio_venta'];
                $this->temporal_compra->save([
                    'folio'=>$id_venta,
                    'idProducto'=>$idProducto,
                    'codigo'=>$producto['codigo'],
                    'nombre'=>$producto['nombre'],
                    'precio'=>$producto['precio_venta'],
                    'cantidad'=>$cantidad,
                    'subtotal'=>$subtotal,
                ]);
            }
        }else{
            $error='No existe el producto.';

        }
        if($this->totalProductos($id_venta)>0 && $forma_pago=="2" ||  $forma_pago=="3"){
            $comision=intdiv($this->totalProductos($id_venta),1000);
            $comision=$comision*85;
        }
        $res['datos']=$this->cargaProductos($id_venta);
        $res['total']=number_format(($this->totalProductos($id_venta)+$comision),2,'.',',');
        $res['subtotal']=number_format($this->totalProductos($id_venta),2,'.',',');
        $res['comision']=number_format($comision,2,'.',',');
        $res['error']=$error;
        echo json_encode($res);
    }

    public  function cargaProductos($id_compra){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $resultado=$this->temporal_compra->porCompra($id_compra);
        $fila='';
        $numFila=0;
        foreach($resultado as $row){
            $numFila++;
            $fila.="<tr id='fila".$numFila."'>";
            $fila.="<td>".$numFila."</td>";
            $fila.="<td>".$row['codigo']."</td>";
            $fila.="<td>".$row['nombre']."</td>";
            $fila.="<td>".$row['precio']."</td>";
            $fila.="<td>".$row['cantidad']."</td>";
            $fila.="<td>".$row['subtotal']."</td>";
            $fila.="<td><a onclick=\"eliminaProducto(".$row['idProducto'].", '".$id_compra."')\" class='borrar'><span class='fas fa-fw fa-trash'></span></a></td>";
            $fila.="<td><a onclick=\"eliminaProductos(".$row['idProducto'].", '".$id_compra."')\" class='borrar'><span class='fas fa-fw fa-trash btn-danger'></span></a></td>";
            $fila.="</tr>";
        }
        return $fila;
    }

    public  function totalProductos($id_compra){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $resultado=$this->temporal_compra->porCompra($id_compra);
        $total=0;
        foreach($resultado as $row){
            $total+=$row['subtotal'];
        }
        return $total;
    }

    public  function eliminar($idProducto, $id_compra){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $datosExiste=$this->temporal_compra->porIdProductoCompra($idProducto, $id_compra);
        $comision=0;
        if($datosExiste){
            if($datosExiste->cantidad>1){
                $cantidad=$datosExiste->cantidad-1;
                $subtotal=$cantidad*$datosExiste->precio;
                $this->temporal_compra->actualizarProductoCompra($idProducto, $id_compra, $cantidad, $subtotal);
            }else{
                $this->temporal_compra->eliminarProductoCompra($idProducto, $id_compra);
            }
        }
        $res['datos']=$this->cargaProductos($id_compra);
        $res['total']=number_format($this->totalProductos($id_compra),2,'.',',');
        $res['subtotal']=number_format($this->totalProductos($id_compra),2,'.',',');
        $res['comision']=number_format($comision,2,'.',',');
        $res['error']='';
        echo json_encode($res);
    }
    public  function eliminarTodo($idProducto, $id_compra){
        if(!isset($this->session->id_usuario)){ return redirect()->to(base_url());}
        $datosExiste=$this->temporal_compra->porIdProductoCompra($idProducto, $id_compra);
        $comision=0;
        if($datosExiste){
            $this->temporal_compra->eliminarProductoCompra($idProducto, $id_compra);
        }
        $res['datos']=$this->cargaProductos($id_compra);
        $res['total']=number_format($this->totalProductos($id_compra),2,'.',',');
        $res['subtotal']=number_format($this->totalProductos($id_compra),2,'.',',');
        $res['comision']=number_format($comision,2,'.',',');
        $res['error']='';
        echo json_encode($res);
    }
}
