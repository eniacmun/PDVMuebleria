<div id="layoutSidenav_content">
    <?php 
        $user_session=session();
        $id_caja=$user_session->id_caja;
    ?>
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4>
            <?php $idEnvioTmp=uniqid();?>
            <form id="form_envio" name="form_envio" class="form-horizontal" method="POST" action="<?php echo base_url(); ?>/envios/guarda" autocomplete="off">
                <input type="hidden" id="id_envio" name="id_envio" value="<?php echo $idEnvioTmp; ?>" />
                <input type="hidden" id="id_caja" name="id_caja" value="<?php echo $id_caja; ?>" />
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <label>Sucursal origen</label>
                            <select class="form-control" name="id_sucursalSalida" id="id_sucursalSalida" required onchange="yesnoCheck1(this)" disabled>
                                <option value="">Seleccionar sucursal</option>
                                <?php foreach($unidades as $unidad){?>
                                    <option value="<?php echo $unidad['id_unidad']; ?>" <?php if($cajaU['id_unidad']==$unidad['id_unidad']) echo 'selected'; ?>><?php echo $unidad['nombre']?>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label>Sucursal destino</label>
                            <select class="form-control" name="id_sucursalEntrada" id="id_sucursalEntrada" required onchange="yesnoCheck2(this)">
                                <option value="">Seleccionar sucursal</option>
                                <?php foreach($unidades as $unidad){?>
                                    <option value="<?php echo $unidad['id_unidad']; ?>"><?php echo $unidad['nombre']?>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label>Conductor</label>
                            <select class="form-control" name="id_camioneta" id="id_camioneta" required >
                                <option value="">Seleccionar conductor</option>
                                <?php foreach($camionetas as $camionetas){?>
                                    <option value="<?php echo $camionetas['id_camioneta']; ?>"><?php echo $camionetas['nombre']."-".$camionetas['modelo']?>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <input type="hidden" id="idProducto" name="idProducto"/>
                            <label>Código de producto:</label>
                            <input class="form-control" id="codigo" name="codigo" type="text" autofocus placeholder="Escribe el código y enter" onkeyup="agregarProducto(event,codigo.value, 1, '<?php echo $idEnvioTmp; ?>', id_caja.value)"/>
                        </div>
                        <div class="col-sm-2">
                            <label for="codigo" id="resultado_error" style="color:red"></label>
                        </div>
                    </div>
                </div>
                <div class="form-group"><br>
                    <button type="button" id="completa_envio" class="btn btn-success">Completar envio</button>
                </div><br />

                <div class="row">
                    <table id="tablaProductos" class="table table-hover table-striped table-sm table-responsive tablaProductos" width="100%">
                        <thead class="table-dark table-hover">
                            <th>#</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
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
        function yesnoCheck1(that) {
            if (that.value==id_sucursalEntrada.value) {
                that.value="";
                alert("La sucursal debe ser distinta");
            }
        }
        function yesnoCheck2(that) {
            if (that.value==id_sucursalSalida.value) {
                that.value="";
                alert("La sucursal debe ser distinta");
            }
        }
    </script>

    <script>
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
                            agregarProducto(e, ui.item.idProducto, 1, '<?php echo $idEnvioTmp; ?>', id_caja.value);
                        }
                    );
                }
            });
        });

        function agregarProducto(e, idProducto, cantidad, id_envio, id_caja){
            let enterKey=13;
            if(codigo !=''){
                if(e.which==enterKey){
                    if(idProducto != null && idProducto !=0 && cantidad > 0){
                        $.ajax({                            
                            url: '<?php echo base_url(); ?>/TemporalEnvio/consultaEnvio/' + idProducto + "/" + cantidad + "/" + id_envio+ "/" + id_caja,
                            success: function(result){
                                if(result==0){
                                    console.log("Error");
                                    console.log(result);
                                }else{
                                    var result=JSON.parse(result);
                                    console.log(result);
                                    if(result.InventarioSuc == 'No hay en inventario para enviar en esta surcursal'){
                                        alert('No se cuenta con suficientes productos en esta sucursal'+'\nSe cuenta con: '+result.InventarioTot+' en otras sucursales');
                                    }else{
                                        if(result.InventarioSuc>=result.cantidad){
                                            $.ajax({
                                                url: '<?php echo base_url(); ?>/TemporalEnvio/insertaEnvio/' + idProducto + "/" + cantidad + "/" + id_envio,
                                                success: function(resultado){
                                                    if(resultado==0){
                                                        console.log("Error");
                                                        console.log(resultado);
                                                    }else{
                                                        var resultado=JSON.parse(resultado);
                                                        console.log(resultado);
                                                        if(resultado.error == ''){
                                                            $("#tablaProductos tbody").empty();
                                                            $("#tablaProductos tbody").append(resultado.datos);
                                                            $("#idProducto").val('');
                                                            $("#codigo").val('');
                                                            $("#nombre").val('');
                                                            $("#cantidad").val('');
                                                        }
                                                    }
                                                }
                                            });
                                        }
                                        else{
                                            alert('No se pueden agregar más productos'+'\nProductos en sucursal:'+result.InventarioSuc);
                                        }
                                    }
                                    /*if(resultado.error == ''){
                                        $("#tablaProductos tbody").empty();
                                        $("#tablaProductos tbody").append(resultado.datos);
                                        $("#idProducto").val('');
                                        $("#codigo").val('');
                                        $("#nombre").val('');
                                        $("#cantidad").val('');
                                    }*/
                                }
                            }
                        });
                    }
                }
            }
            
        }

        function eliminaProducto(idProducto, id_envio){
            $.ajax({
                url: '<?php echo base_url(); ?>/TemporalEnvio/eliminar/'+idProducto+"/"+id_envio,
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

        function eliminaProductos(idProducto, id_envio){
            $.ajax({
                url: '<?php echo base_url(); ?>/TemporalEnvio/eliminarTodo/'+idProducto+"/"+id_envio,
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

        $(function(){
            $("#completa_envio").click(function(){
                let nFilas=$("#tablaProductos tr").length;
                if(nFilas<2){                    
                    alert("Debe agregar un prooducto");
                }
                else{
                    if(id_sucursalSalida.value=="" || id_sucursalEntrada.value=="" || id_camioneta.value==""){
                        alert("Complete los campos");
                    }
                    else{
                        $("#form_envio").submit();
                    }                    
                }
            });
        });

    </script>