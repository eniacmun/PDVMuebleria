<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4>
            <?php if(isset($validation)){?>
                <div class="alert alert-danger">
                    <?php echo $validation->listErrors();?>
                </div>                
            <?php } ?>
            <form method="POST" action="<?php echo base_url();?>/cajas/actualizar" autocomplete="off">
                <input type="hidden" value="<?php echo $datos['id_caja'];?>" name="id_caja"/>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Número de caja</label>
                            <input class="form-control" id="numero_caja" name="numero_caja" type="number" min="1" max="1000" autofocus required value="<?php echo $datos['numero_caja'];?>">
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
                            <label>Folio</label>
                            <input class="form-control" id="folio" name="folio" type="text" autofocus required value="<?php echo $datos['folio'];?>">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Sucursal</label>
                            <select class="form-control" name="id_unidad" id="id_unidad" required>
                                <option value="">Seleccionar sucursal</option>
                                <?php foreach($unidades as $unidad){?>
                                    <option value="<?php echo $unidad['id_unidad']; ?>"<?php if($datos['id_unidad']==$unidad['id_unidad']) echo 'selected'?>><?php echo $unidad['nombre']?>
                                <?php }?>
                            </select>
                        </div>
                    </div>                    
                </div>
                <a href="<?php echo base_url();?>/cajas" class="btn btn-primary">Regresar</a>
                <button type="submit" class="btn btn-success">Guardar</button>
                
            </form>
        </div>
    </main>
                