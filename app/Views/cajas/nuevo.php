<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4>
            <?php if(isset($validation)){?>
                <div class="alert alert-danger">
                    <?php echo $validation->listErrors();?>
                </div>                
            <?php } ?>
            
            <form method="POST" action="<?php echo base_url();?>/cajas/insertar" autocomplete="off">
                <?php csrf_field();?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Número caja</label>
                            <input class="form-control" id="numero_caja" name="numero_caja" type="number" min="1" max="1000" value="<?php echo set_value('numero_caja');?>" autofocus required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Nombre</label>
                            <input class="form-control" id="nombre" name="nombre" type="text" value="<?php echo set_value('nombre');?>" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Folio</label>
                            <input class="form-control" id="folio" name="folio" type="text" value="<?php echo set_value('folio');?>" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Sucursal</label>
                            <select class="form-control" name="id_unidad" id="id_unidad" required>
                                <option value="">Seleccionar sucursal</option>
                                <?php foreach($unidades as $unidad){?>
                                    <option value="<?php echo $unidad['id_unidad']; ?>"<?php if(set_value('id_unidad')==$unidad['id_unidad']) echo 'selected'?>><?php echo $unidad['nombre']?>
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
                