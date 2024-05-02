<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4>
            <?php if(isset($validation)){?>
                <div class="alert alert-danger">
                    <?php echo $validation->listErrors();?>
                </div>                
            <?php } ?>
            <form method="POST" action="<?php echo base_url();?>/clientes/actualizar" autocomplete="off">
                <?php csrf_field();?>
                <input class="form-control" type="hidden" id="id_cliente" name="id_cliente" type="text" value="<?php echo $cliente['id_cliente']?>">
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Nombre</label>
                            <input class="form-control" id="nombre" name="nombre" type="text" value="<?php echo $cliente['nombre']?>" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Dirección</label>
                            <textarea class="form-control" id="direccion" name="direccion" type="text" maxlength="150" required><?php echo $cliente['direccion']?></textarea>
                        </div>
                    </div>
                </div>                
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Telefono</label>
                            <input class="form-control" id="telefono" name="telefono" type="tel" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="10"  value="<?php echo $cliente ['telefono'];?>" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Correo</label>
                            <input class="form-control" id="correo" name="correo" type="email" value="<?php echo $cliente['correo']?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Ruta</label>
                            <select class="form-control" name="ruta" id="ruta" required>
                                <option value="">Seleccionar ruta</option>
                                <?php foreach($rutas as $ruta){?>
                                    <option value="<?php echo $ruta['ruta']; ?>"<?php if($cliente['ruta']==$ruta['ruta']) echo 'selected'?>><?php echo $ruta['ruta']?>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url();?>/clientes" class="btn btn-primary">Regresar</a>
                <button type="submit" class="btn btn-success">Guardar</button>
                
            </form>
        </div>
    </main>
                