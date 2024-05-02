<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4>
            <?php if(isset($validation)){?>
                <div class="alert alert-danger">
                    <?php echo $validation->listErrors();?>
                </div>                
            <?php } ?>
            <form method="POST" action="<?php echo base_url();?>/camionetas/actualizar" autocomplete="off">
                <input type="hidden" value="<?php echo $datos['id_camioneta'];?>" name="id_camioneta"/>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Modelo</label>
                            <input class="form-control" id="modelo" name="modelo" type="text" autofocus required value="<?php echo $datos['modelo'];?>">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label>Conductor</label>
                            <input class="form-control" id="nombre" name="nombre" type="text" autofocus required value="<?php echo $datos['nombre'];?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label>Placa</label>
                            <input class="form-control" id="placa" name="placa" type="text" autofocus required value="<?php echo $datos['placa'];?>">
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url();?>/camionetas" class="btn btn-primary">Regresar</a>
                <button type="submit" class="btn btn-success">Guardar</button>
                
            </form>
        </div>
    </main>
                