<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4>
            <?php if(isset($validation)){?>
                <div class="alert alert-danger">
                    <?php echo $validation->listErrors();?>
                </div>                
            <?php } ?>
            <form method="POST" action="<?php echo base_url();?>/usuarios/actualizar" autocomplete="off">
                <input type="hidden" value="<?php echo $datos['id_usuario'];?>" name="id_usuario"/>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Usuario</label>
                            <input class="form-control" id="usuario" name="usuario" type="text" autofocus required value="<?php echo $datos['usuario'];?>">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Nombre</label>
                            <input class="form-control" id="nombre" name="nombre" type="text" autofocus required value="<?php echo $datos['nombre'];?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Contraseña</label>
                            <input class="form-control" id="password" name="password" type="password" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Verificar contraseña</label>
                            <input class="form-control" id="repassword" name="repassword" type="password" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Caja</label>
                            <select class="form-control" name="id_caja" id="id_caja" required>
                                <option value="">Seleccionar caja</option>
                                <?php foreach($cajas as $caja){?>
                                    <option value="<?php echo $caja['id_caja']; ?>"<?php if($caja['id_caja'] == $datos['id_caja']){ echo 'selected'; }?> ><?php echo $caja['nombre']?>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Rol</label>
                            <select class="form-control" name="id_rol" id="id_rol" required>
                                <option value="">Seleccionar rol</option>
                                <?php foreach($roles as $rol){?>
                                    <option value="<?php echo $rol['id_rol'];?>" <?php if($rol['id_rol'] == $datos['id_rol']){ echo 'selected'; }?>><?php echo $rol['nombre']?>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url();?>/usuarios" class="btn btn-primary">Regresar</a>
                <button type="submit" class="btn btn-success">Guardar</button>
                
            </form>
        </div>
    </main>
                