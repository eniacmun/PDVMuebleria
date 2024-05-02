<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4>

            <div>
                <p>
                    <a href="<?php echo base_url();?>/clientes/nuevo" class="btn btn-info">Agregar</a>
                    <a href="<?php echo base_url();?>/clientes/eliminados" class="btn btn-warning">Eliminados</a>
                    <a href="<?php echo base_url();?>/ventas/venta" class="btn btn-success">Ir a caja</a>
                </p>
            </div>
                <div class="table-responsive">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                <th>Ruta</th>
                                <th>Editar</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($datos as $dato){ ?>
                                <tr>
                                    <td><?php echo $dato['id_cliente']; ?></td>
                                    <td><?php echo $dato['nombre']; ?></td>
                                    <td><?php echo $dato['direccion']; ?></td>
                                    <td><?php echo $dato['telefono']; ?></td>
                                    <td><?php echo $dato['correo']; ?></td>
                                    <td><?php echo $dato['ruta']; ?></td>
                                    <td><a href="<?php echo base_url()."/clientes/editar/".$dato['id_cliente']; ?>" class="btn btn-warning"><i class="fa-solid fa-pencil"></i></a></td>
                                    <td><a href="#exampleModalToggle" data-href="<?php echo base_url()."/clientes/eliminar/".$dato['id_cliente']; ?>" data-bs-toggle="modal" data-target="#exampleModalToggle"
                                    data-placement="top" title="Eliminar registro"><i class="fa-solid fa-xmark btn btn-danger"></i></a></td> 
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