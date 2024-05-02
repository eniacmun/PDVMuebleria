<?php 
$id_compra=uniqid();
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4> 
            <?php if(isset($validation)){?>
                <div class="alert alert-danger">
                    <?php echo $validation->listErrors();?>
                </div>                
            <?php } ?>
            <a href="<?php echo base_url();?>/compras" class="btn btn-warning">Regresar</a>          
            <form method="POST" id="form_compra" name="form_compra" action="<?php echo base_url();?>/compras/guarda" autocomplete="off">
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <input type="hidden" id="idProducto" name="idProducto"/>
                            <input type="hidden" id="id_compra" name="id_compra" value="<?php echo $id_compra;?>"/>
                            <label>Código</label>
                            <input class="form-control" id="codigo" name="codigo" type="text" placeholder="Escribe tu código y enter"  onkeyup="buscarProducto(event, this, this.value)" autofocus>
                            <label for="codigo" id=resultado_error style="color: red"></label>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label>Nombre del producto</label>
                            <input class="form-control" id="nombre" name="nombre" type="text" disabled>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label>Cantidad</label>
                            <input class="form-control" id="cantidad" name="cantidad" type="text" onchange="myFunction()">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <label>Precio de compra</label>
                            <input class="form-control" id="precio_compra" name="precio_compra" type="text" disabled>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label>Subtotal</label>
                            <input class="form-control" id="subtotal" name="subtotal" type="text" disabled>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label>Sucursal</label>
                            <select class="form-control" name="id_unidad" id="id_unidad" required>
                                <option value="">Seleccionar sucursal</option>
                                <?php foreach($unidades as $unidad){?>
                                    <option value="<?php echo $unidad['id_unidad']; ?>"><?php echo $unidad['nombre']?>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <label><br>&nbsp;</label>
                            <button id="agregar_producto" name="agregar_producto" type="button" class="btn btn-primary" onclick="agregarProducto(idProducto.value, cantidad.value, '<?php echo $id_compra; ?>')">Agregar producto</button>
                        </div>
                    </div><br>
                </div>
                <div class="row">
                    <table id="tablaProductos" class="table table-hover table-striped table-sm table-responsive tablaProductos" width="100%">
                        <thead class="thead-dark">
                            <th>#</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th width="1%"></th>
                            <th width="1%"></th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6 offset-md-6">
                        <label style="font-weight: bold; font-size: 30px; text-align: center;">Total $</label>
                        <input type="text" id="total" name="total" size="7" readonly="true" value="0.00" style="font-weight: bold; font-size: 30px; text-align: center;"/>
                        <button type="button" id="completa_compra" class="btn btn-success">Agregar al inventario</button>
                    </div>
                </div>                
            </form>
        </div>
    </main>
    

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">No hay productos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       Añade productos al carrito.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>
    <script>
        function myFunction() {           
            document.getElementById('subtotal').value= Number.parseFloat(document.getElementById('cantidad').value*document.getElementById('precio_compra').value).toFixed(2); 
        }        
    </script>
    <script>
        $(document).ready(function(){
            if( myModal = document.getElementById('exampleModal')){
            }
            $("#completa_compra").click(function(){
                let nFila=$("#tablaProductos tr").length;
                if(nFila<=1){
                    //myModal.addEventListener('shown.bs.modal');
                    alert("Debe agregar un prooducto");
                }else{
                    $("#form_compra").submit();
                }
            });
        });

        function buscarProducto(e, tagCodigo, codigo){
            var enterKey=13;

            if(codigo!=''){
                if(e.which == enterKey){
                    $.ajax({
                        url: '<?php echo base_url(); ?>/productos/buscarPorCodigo/'+codigo,
                        dataType: 'json',
                        success: function(resultado){
                            if(resultado==0){
                                $(tagCodigo).val('');
                            }else{

                                $("#resultado_error").html(resultado.error);

                                if(resultado.existe){
                                    $("#idProducto").val(resultado.datos.idProducto);
                                    $("#nombre").val(resultado.datos.nombre);
                                    $("#cantidad").val(1);
                                    $("#precio_compra").val(resultado.datos.precio_compra);
                                    $("#subtotal").val(resultado.datos.precio_compra );
                                    $("#cantidad").focus();
                                }else{
                                    $("#idProducto").val('');
                                    $("#nombre").val('');
                                    $("#cantidad").val('');
                                    $("#precio_compra").val('');
                                    $("#subtotal").val('');
                                }
                            }
                        }
                    });
                }
            }
        }

        function agregarProducto(idProducto, cantidad, id_compra){
            if(idProducto != null && idProducto !=0 && cantidad > 0){
                $.ajax({
                    url: '<?php echo base_url(); ?>/TemporalCompra/inserta/' + idProducto + "/" + cantidad + "/" + id_compra,
                    success: function(resultado){
                        if(resultado==0){
                            
                        }else{
                            var resultado=JSON.parse(resultado);
                            if(resultado.error == ''){
                                $("#tablaProductos tbody").empty();
                                $("#tablaProductos tbody").append(resultado.datos);
                                $("#total").val(resultado.total);
                                $("#idProducto").val('');
                                $("#codigo").val('');
                                $("#nombre").val('');
                                $("#cantidad").val('');
                                $("#precio_compra").val('');
                                $("#subtotal").val('');
                            }
                        }
                    }
                });
            }
            
        }

        function eliminaProducto(idProducto, id_compra){
            $.ajax({
                url: '<?php echo base_url(); ?>/TemporalCompra/eliminar/'+idProducto+"/"+id_compra,
                success: function(resultado){
                    if(resultado==0){
                        $(tagCodigo).val('');
                    }else{
                        var resultado=JSON.parse(resultado);
                        $("#tablaProductos tbody").empty();
                        $("#tablaProductos tbody").append(resultado.datos);
                        $("#total").val(resultado.total);
                    }
                }
            });
        }

        function eliminaProductos(idProducto, id_compra){
            $.ajax({
                url: '<?php echo base_url(); ?>/TemporalCompra/eliminarTodo/'+idProducto+"/"+id_compra,
                success: function(resultado){
                    if(resultado==0){
                        $(tagCodigo).val('');
                    }else{
                        var resultado=JSON.parse(resultado);
                        $("#tablaProductos tbody").empty();
                        $("#tablaProductos tbody").append(resultado.datos);
                        $("#total").val(resultado.total);
                    }
                }
            });
        }

    </script>