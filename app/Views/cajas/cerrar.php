<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4>
            <?php if(isset($validation)){?>
                <div class="alert alert-danger">
                    <?php echo $validation->listErrors();?>
                </div>                
            <?php } ?>
            
            <form method="POST" action="<?php echo base_url();?>/cajas/cerrar" autocomplete="off">
                <?php csrf_field();?>
                <input id="id_arqueo" name="id_arqueo" type="hidden" value="<?php echo $arqueo['id_arqueo'];?>" />
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
                            <input class="form-control" id="monto_inicial" name="monto_inicial" value="<?php echo $arqueo['monto_inicial'];?>" type="text" readonly required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Monto final en cajero </label>
                            <input class="form-control" id="monto_final" name="monto_final" type="text" value="<?php echo number_format(($monto['total']+$arqueo['monto_inicial']),2,'.','');?>" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Fecha cierre</label>
                            <input class="form-control" id="fecha" name="fecha" type="text" value="<?php echo date("Y-m-d");?>" readonly required>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label>Hora cierre</label>
                            <input class="form-control" id="hora" name="hora" type="text" value="<?php echo date("H:i:s");?>" readonly required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Total de ventas</label>
                            <input class="form-control" id="total_ventas" name="total_ventas" type="text" value="<?php echo number_format($monto['total'],2,'.',''); ?>" readonly required>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url();?>/cajas" class="btn btn-primary">Regresar</a>
                <button type="submit" class="btn btn-success">Guardar</button>
                
            </form>
        </div>
    </main>
                