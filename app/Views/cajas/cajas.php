<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4>

            <div>
                <p>
                    <?php
                    $user_session=session();
                    $permisos=$user_session->permisos;
                    $arreglo=array();
                    foreach ($permisos as $permiso){
                        $arreglo[]= $permiso['nombre'];
                    }
                    if(in_array('Permisos admin', $arreglo)){?>
                      <a href="<?php echo base_url();?>/cajas/nuevo" class="btn btn-info">Agregar</a>
                      <a href="<?php echo base_url();?>/cajas/eliminados" class="btn btn-warning">Eliminados</a>
                    <?php } ?>
                </p>
            </div>

                <div class="table-responsive">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Número de caja</th>
                                <th>Nombre</th>
                                <th>Folio</th>
                                <th>Sucursal</th>
                                <th>Apertura y cierre</th>                                
                                <?php if(in_array('Permisos admin', $arreglo)){?>
                                <th>Editar</th>
                                <th>Eliminar</th>
                                <?php }?>
                                <th>Alta</th>
                                <th>Última edición</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($datos as $dato){ ?>
                                <tr>
                                    <?php if(in_array('Permisos admin', $arreglo) OR $user_session->id_caja==$dato['id_caja']){?>
                                    <td><?php echo $dato['id_caja']; ?></td>
                                    <td><?php echo $dato['numero_caja']; ?></td>
                                    <td><?php echo $dato['nombre']; ?></td>
                                    <td><?php echo $dato['folio']; ?></td>
                                    <td><?php echo $dato['id_unidad']; ?></td>
                                    <td><a href="<?php echo base_url()."/cajas/arqueo/".$dato['id_caja']; ?>" class="btn btn-primary"><i class="fa-solid fa-clipboard-list"></i></i></a></td>                                    
                                    <?php if(in_array('Permisos admin', $arreglo)){?>
                                    <td><a href="<?php echo base_url()."/cajas/editar/".$dato['id_caja']; ?>" class="btn btn-warning"><i class="fa-solid fa-pencil"></i></a></td>
                                    <td><a href="#exampleModalToggle" data-href="<?php echo base_url()."/cajas/eliminar/".$dato['id_caja']; ?>" data-bs-toggle="modal" data-target="#exampleModalToggle"
                                    data-placement="top" title="Eliminar registro"><i class="fa-solid fa-xmark btn btn-danger"></i></a></td>
                                    <?php } ?>
                                    <td><?php echo $dato['fecha_alta']; ?></td>
                                    <td><?php echo $dato['fecha_edit']; ?></td>
                                    <?php } ?> 
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
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