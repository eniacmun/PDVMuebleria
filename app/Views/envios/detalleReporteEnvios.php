<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">            
            <h4 class="mt-4"><?php echo $titulo;?></h4>
            <?php if(isset($validation)){?>
                <div class="alert alert-danger">
                    <?php echo $validation->listErrors();?>
                </div>                
            <?php } ?>
            <form method="POST" action="<?php echo base_url();?>/envios/detalleEnvios" autocomplete="off">
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Seleccione conductor</label>
                            <select class="form-control" name="nombre" id="nombre" required>
                                <option value="">Seleccione a un conductor</option>
                                <?php foreach($camionetas as $camioneta){?>
                                    <option value="<?php echo $camioneta['nombre']; ?>"><?php echo $camioneta['nombre']?>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                </div>                
                <button type="submit" class="btn btn-success">Generar reporte</button>
                
            </form>
        </div>
    </main>
    <script>
    </script>