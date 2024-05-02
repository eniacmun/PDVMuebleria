<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
          <h4 class="mt-4"><?php echo $titulo;?></h4>
            <?php if(isset($validation)){?>
                <div class="alert alert-danger">
                    <?php echo $validation->listErrors();?>
                </div>                
            <?php } ?>
            
            <form method="POST" enctype="multipart/form-data" action="<?php echo base_url();?>/configuracion/actualizar" autocomplete="off">
                <?php csrf_field();?>
                <div class="form-group">
                  <div class="row">
                    <div class="col-12 col-sm-6">
                      <label>Nombre de la tienda</label>
                      <input class="form-control" id="tienda_nombre" name="tienda_nombre" type="text" value="<?php echo $nombre['valor'];?>" autofocus required>
                    </div>
                    <div class="col-12 col-sm-6">
                      <label>RFC</label>
                      <input class="form-control" id="tienda_rfc" name="tienda_rfc" type="text" value="<?php echo $rfc['valor'];?>" required>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12 col-sm-6">
                      <label>Teléfono de la tienda</label>
                      <input class="form-control" tupe="tel" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="10" id="tienda_telefono" name="tienda_telefono" type="text" value="<?php echo $telefono['valor'];?>" required>
                    </div>
                    <div class="col-12 col-sm-6">
                      <label>Correo de la tienda</label>
                      <input class="form-control" type="email" id="tienda_email" name="tienda_email" type="text" value="<?php echo $email['valor'];?>" required>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12 col-sm-6">
                      <label>Dirección de la tienda</label>
                      <textarea class="form-control" id="tienda_direccion" name="tienda_direccion" type="text" maxlength="280" required><?php echo $direccion['valor'];?></textarea>
                    </div>
                    <div class="col-12 col-sm-6">
                      <label>Leyenda del ticket</label>
                      <textarea class="form-control" id="tienda_leyenda" name="tienda_leyenda" type="text" maxlength="280" required><?php echo $leyenda['valor'];?></textarea>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-12 col-sm-6">
                      <label >Logotipo</label><br>
                      <img src="<?php echo base_url().'/images/logotipo.png'; ?>" class="img-responsive" width="200"/>

                      <input type="file" id="tienda_logo" name="tienda_logo" accept="image/png"/>
                      <p class="text-danger">Cargar imagen en formato png de 150x150 pixeles</p>
                    </div>
                  </div>
                </div>

                <a href="<?php echo base_url();?>/configuracion" class="btn btn-primary">Regresar</a>
                <button type="submit" class="btn btn-success">Guardar</button>
                
            </form>
        </div>
    </main>

<!-- Modal -->
<div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel">Eliminar registro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>¿Desea eliminar este registro?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">No</button>
        <a class="btn btn-danger btn-ok" id="btn-ok">Sí</a>
      </div>
    </div>
  </div>
</div>        