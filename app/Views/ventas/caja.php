<div id="layoutSidenav_content">
    <?php 
        $user_session=session();
        $id_caja=$user_session->id_caja;
        ?>
    <main>
        <div class="container-fluid px-4">
            <?php $idVentaTmp=uniqid();?>
            <br>
            <form id="form_venta" name="form_venta" class="form-horizontal" method="POST" action="<?php echo base_url(); ?>/ventas/guarda" autocomplete="off">
                <input type="hidden" id="id_venta" name="id_venta" value="<?php echo $idVentaTmp; ?>" />
                <input type="hidden" id="id_caja" name="id_caja" value="<?php echo $id_caja; ?>" />
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="ui-widget">
                                <label >Cliente: </label>
                                <input type="hidden" id="id_cliente" name="id_cliente" value="1000"/>
                                <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Escribe el nombre del cliente" value="Público en general" onkeyup="" autocomplete="off" requiered/>
                            </div>                        
                        </div>
                        <div class="col-sm-6">
                            <label >Forma de pago: </label>
                            <select id="forma_pago" name="forma_pago" class="form-control"  onchange="yesnoCheck(this); credito(this);" required>
                                <option value="1">Efectivo</option>
                                <option value="2">Crédito</option>
                                <option value="3">Mixto</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" id="ifYes" style="display: none;">
                        <div class="col-sm-6">
                            <div class="ui-widget">
                                <label >Opción de crédito: </label>
                                <select id="tipo_credito" name="tipo_credito" class="form-control" required>
                                    <option value="4">4 meses</option>
                                    <option value="12">12 meses</option>
                                </select>
                            </div>                       
                        </div>                        
                        <div class="col-sm-6">
                            <label style="font-weight: bold; font-size: 20px; text-align: center;">Comision $</label>
                            <input type="text" id="comision" name="comision" size="7" readonly="true" value="0.00" style="font-weight: bold; font-size: 30px; text-align: center; border-style:none; outline: none;"/>
                        </div>
                    </div>
                    <div class="row" id="ifMix" style="display: none;">
                        <div class="col-12 col-sm-6">
                            <div class="ui-widget">
                                <label >Deja pagado:</label>
                                <input type="number" class="form-control" id="pagado" name="pagado" value="0.00" min="0" step="0.01" placeholder="Ingrese cantidad pagada" autocomplete="off" requiered/>
                            </div>                        
                        </div>
                        <div class="col-12 col-sm-6"><br>
                                <label style="font-weight: bold; font-size: 20px; text-align: center;">Pendiente $</label>
                                <input type="text" id="pendiente" name="pendiente" size="7" readonly="true" value="0.00" style="font-weight: bold; font-size: 30px; text-align: center; border-style:none; outline: none;"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <input type="hidden" id="idProducto" name="idProducto"/>
                            <label>Código de producto:</label>
                            <input class="form-control" id="codigo" name="codigo" type="text" autofocus placeholder="Escribe el código y enter" onkeyup="agregarProducto(event,codigo.value, 1, '<?php echo $idVentaTmp; ?>', id_caja.value)"/>
                        </div>
                        <div class="col-12 col-sm-4"><br>
                            <label style="font-weight: bold; font-size: 20px; text-align: center;">Subtotal $</label>
                            <input type="text" id="subtotal" name="subtotal" size="7" readonly="true" value="0.00" style="font-weight: bold; font-size: 30px; text-align: center; border-style:none; outline: none;"/>                            
                        </div>
                        <div class="col-12 col-sm-4"><br>
                            <label style="font-weight: bold; font-size: 30px; text-align: center;">Total $</label>
                            <input type="text" id="total" name="total" size="7" readonly="true" value="0.00" style="font-weight: bold; font-size: 30px; text-align: center; border-style:none; outline: none;"/>                            
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" id="completa_venta" class="btn btn-success">Completar venta</button>
                </div><br />

                <div class="row">
                    <table id="tablaProductos" class="table table-hover table-striped table-sm table-responsive tablaProductos" width="100%">
                        <thead class="table-dark table-hover">
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
            </form>
        </div>
    </main>
    <script>
        /* event listener */
        document.getElementsByName("pagado")[0].addEventListener('change', doThing);
        /* function */
        function doThing(){
            var pendiente = document.getElementById("pendiente");
            var pagado = document.getElementById("pagado").value;
            var pag = document.getElementById("pagado");
            var total = document.getElementById("total").value;
            total=total.replace(/\,/g,''); 
            total=parseInt(total,10);
            if(total-pagado<0){
                pag.setAttribute('value','');
                alert('La cantidad de paga no puede ser mayor al total');                
            }else{
                //pendiente.setAttribute('value',(total-pagado));
                const options = { 
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2 
                };
                let res=total-pagado;
                var usFormat = res.toLocaleString('en', options);
                pendiente.setAttribute('value',usFormat);
            }        
        }
    </script>
    <script>
        function yesnoCheck(that) {
            if (that.value == "2" && id_cliente.value==1000) {
                alert("Primero registre al cliente en el sistema");
                window.location = '<?php echo base_url(); ?>/clientes/nuevo';
            }
            if (that.value == "3" && id_cliente.value==1000) {
                alert("Primero registre al cliente en el sistema");
                window.location = '<?php echo base_url(); ?>/clientes/nuevo';
            }
        }
        function credito(that) {
            switch(that.value){
                case "2":
                    document.getElementById("ifYes").style.display = "block";
                    document.getElementById("ifMix").style.display = "none";
                    break;
                case "3":
                    document.getElementById("ifYes").style.display = "block";
                    document.getElementById("ifMix").style.display = "block";
                    break;
                
                default:
                    document.getElementById("ifYes").style.display = "none";
                    document.getElementById("ifMix").style.display = "none";
            }
        }
    </script>

    <script>
        $(function(){
            $("#cliente").autocomplete({
                source: "<?php echo base_url(); ?>/clientes/autocompleteData",
                minLength: 1, 
                select: function(event, ui){
                    event.preventDefault();
                    $("#id_cliente").val(ui.item.id_cliente);
                    $("#cliente").val(ui.item.value);
                }
            });
        });
        $(function(){
            $("#codigo").autocomplete({
                source: "<?php echo base_url(); ?>/productos/autocompleteData",
                minLength: 1, 
                select: function(event, ui){
                    event.preventDefault();
                    $("#codigo").val(ui.item.value);
                    setTimeout(
                        function(){
                            e=jQuery.Event("keypress");
                            e.which=13;
                            agregarProducto(e, ui.item.idProducto, 1, '<?php echo $idVentaTmp; ?>', id_cliente.value, forma_pago.value);
                        }
                    );
                }
            });
        });

        function agregarProducto(e, idProducto, cantidad, id_venta, id_caja, forma_pago){
            let enterKey=13;
            var band=0;
            if(codigo !=''){
                if(e.which==enterKey){
                    if(idProducto != null && idProducto !=0 && cantidad > 0){
                        $.ajax({
                            url: '<?php echo base_url(); ?>/Ventas/consultaVenta/' + idProducto + "/" + cantidad + "/" + id_venta + "/" + id_caja,
                            success: function(result){
                                if(result==0){
                                    
                                }else{
                                    var result=JSON.parse(result);
                                    if(result.InventarioSuc == 'No hay en inventario en esta surcursal'){
                                        alert('No se cuenta con suficientes productos en esta sucursal'+'\nSe cuenta con: '+result.InventarioTot+' en otras sucursales');
                                    }
                                    else{
                                        if(result.InventarioSuc>=result.cantidad){
                                            $.ajax({
                                                url: '<?php echo base_url(); ?>/TemporalCompra/insertaVenta/' + idProducto + "/" + cantidad + "/" + id_venta + "/" + forma_pago,
                                                success: function(resultado){
                                                    //console.log(resultado);   
                                                    if(resultado==0){
                                                        
                                                    }else{
                                                        var resultado=JSON.parse(resultado);
                                                        if(resultado.error == ''){
                                                            $("#tablaProductos tbody").empty();
                                                            $("#tablaProductos tbody").append(resultado.datos);
                                                            $("#total").val(resultado.total);
                                                            $("#subtotal").val(resultado.subtotal);
                                                            $("#comision").val(resultado.comision);
                                                            $("#idProducto").val('');
                                                            $("#codigo").val('');
                                                            $("#nombre").val('');
                                                            $("#cantidad").val('');
                                                            $("#precio_venta").val('');
                                                        }
                                                    }
                                                }
                                            });
                                        }
                                        else{
                                            alert('No se puede vender más, consulte existencia en otras sucursales');
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            }
            
        }

        function eliminaProducto(idProducto, id_venta){
            $.ajax({
                url: '<?php echo base_url(); ?>/TemporalCompra/eliminar/'+idProducto+"/"+id_venta,
                success: function(resultado){
                    if(resultado==0){
                        $(tagCodigo).val('');
                    }else{
                        var resultado=JSON.parse(resultado);
                        var forma_pago = document.getElementById("forma_pago").value;
                        var comision=0;
                        if(forma_pago==3 || forma_pago==2){
                            total=resultado.total.replace(/\,/g,''); 
                            total=parseInt(total,10);
                            var mult=Math.floor(total/1000);
                            comision=mult*85;
                            const options = { 
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2 
                            };
                            let res=total+comision;
                            var usFormat = res.toLocaleString('en', options);
                            $("#tablaProductos tbody").empty();
                            $("#tablaProductos tbody").append(resultado.datos);
                            $("#total").val(usFormat);
                            $("#subtotal").val(resultado.total);
                            $("#comision").val(comision.toLocaleString('en', options));
                            
                        }else{
                            $("#tablaProductos tbody").empty();
                            $("#tablaProductos tbody").append(resultado.datos);
                            $("#total").val(resultado.total);
                            $("#subtotal").val(resultado.total);
                            $("#comision").val(resultado.comision);
                        }
                        
                    }
                }
            });
        }

        function eliminaProductos(idProducto, id_venta){
            $.ajax({
                url: '<?php echo base_url(); ?>/TemporalCompra/eliminarTodo/'+idProducto+"/"+id_venta,
                success: function(resultado){
                    if(resultado==0){
                        $(tagCodigo).val('');
                    }else{
                        var forma_pago = document.getElementById("forma_pago").value;
                        var resultado=JSON.parse(resultado);
                        var comision=0;
                        if(forma_pago==3 || forma_pago==2){
                            total=resultado.total.replace(/\,/g,''); 
                            total=parseInt(total,10);
                            var mult=Math.floor(total/1000);
                            comision=mult*85;
                            const options = { 
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2 
                            };
                            let res=total+comision;
                            var usFormat = res.toLocaleString('en', options);
                            $("#tablaProductos tbody").empty();
                            $("#tablaProductos tbody").append(resultado.datos);
                            $("#total").val(usFormat);
                            $("#subtotal").val(resultado.total);
                            $("#comision").val(comision.toLocaleString('en', options));
                            
                        }else{
                            $("#tablaProductos tbody").empty();
                            $("#tablaProductos tbody").append(resultado.datos);
                            $("#total").val(resultado.total);
                            $("#subtotal").val(resultado.total);
                            $("#comision").val(resultado.comision);
                        }
                    }
                }
            });
        }

        $(function(){
            $("#completa_venta").click(function(){
                let nFilas=$("#tablaProductos tr").length;
                if(nFilas<2){
                    alert("Debe agregar un prooducto");
                }
                else{
                    $("#form_venta").submit();
                }
            });
        });

    </script>