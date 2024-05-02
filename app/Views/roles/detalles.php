<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
        <h4 class="mt-4"><?php echo $titulo;?></h4>
        <form id="form_permisos" name="fomr_permisos" method="POST" action="<?php echo base_url();?>/roles/guardarPermisos">
          <input type="hidden" name="id_rol" value="<?php echo $id_rol; ?>" />
        
          <ul style="list-style: none; padding-left: 5px;">
          <?php 
            foreach($permisos as $permiso){?>
              <li></li><input type="checkbox" value="<?php echo $permiso['id_permiso'];?>" name="permisos[]" <?php if(isset($asignado[$permiso['id_permiso']])) {echo 'checked';} ?> /><label ><?php echo $permiso['nombre']; ?></label></li>
              <br/>
            <?php } ?>
            </ul>
          <button type="submit" class="btn btn-primary">Guardar</button>
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