<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">            
            <h4 class="mt-4"><?php echo $titulo;?></h4>
            <?php if(isset($validation)){?>
                <div class="alert alert-danger">
                    <?php echo $validation->listErrors();?>
                </div>                
            <?php } ?>
            <form method="POST" action="<?php echo base_url();?>/productos/detalleVentas" autocomplete="off" id=form_date>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Fecha de inicio:</label>
                            <input class="form-control" id="fecha_inicio" name="fecha_inicio" type="date" value="<?php echo set_value('codigo');?>" autofocus required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Fecha de fin (Se consideran días terminados):</label>
                            <input class="form-control" id="fecha_fin" name="fecha_fin" type="date" value="<?php echo set_value('nombre');?>" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Caja</label>
                            <select class="form-control" name="id_caja" id="id_caja" required>
                                <option value="Todas">Todas</option>
                                <?php foreach($cajas as $caja){?>
                                    <option value="<?php echo $caja['id_caja']; ?>"><?php echo $caja['nombre']?>
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
        $("#form_date").submit(function(event) {
            event.preventDefault();
            const dateIni = document.getElementById('fecha_inicio').value;
            const dateFin = document.getElementById('fecha_fin').value;
            if(dateIni>dateFin){
                alert("Introduce un rango válido");
                return;
            }
            this.submit();
            
        });
    </script>