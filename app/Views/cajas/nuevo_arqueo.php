<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4>
            <?php if(isset($validation)){?>
                <div class="alert alert-danger">
                    <?php echo $validation->listErrors();?>
                </div>                
            <?php } ?>
            
            <form method="POST" action="<?php echo base_url();?>/cajas/nuevo_arqueo" autocomplete="off">
                <?php csrf_field();?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Número de caja</label>
                            <input class="form-control" id="numero_caja" name="numero_caja" type="number" min="1" max="1000" value="<?php echo $caja['numero_caja'];?>" readonly autofocus required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Nombre</label>
                            <input class="form-control" id="nombre" name="nombre" type="text" value="<?php echo $caja['nombre'];?>" readonly required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Monto inicial</label>
                            <input class="form-control" id="monto_inicial" name="monto_inicial" type="text" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Folio inicial</label>
                            <input class="form-control" id="folio" name="folio" type="text" value="<?php echo $caja['folio'];?>" readonly required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Fecha</label>
                            <input class="form-control" id="fecha" name="fecha" type="text" value="<?php echo date('Y-m-d');?>" readonly required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Hora</label>
                            <input class="form-control" id="hora" name="hora" type="text" value="<?php echo date('H:i:s');?>" readonly required>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url();?>/cajas" class="btn btn-primary">Regresar</a>
                <button type="submit" class="btn btn-success">Guardar</button>
                
            </form>
        </div>
    </main>
                