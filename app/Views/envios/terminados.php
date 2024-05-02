<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4>

            <div>
                <p>
                    <a href="<?php echo base_url();?>/envios" class="btn btn-success">Envios</a>
                </p>
            </div>
                <div class="table-responsive">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Fecha registro</th>
                                <th>Fecha entrega</th>
                                <th>Sucursal origen</th>
                                <th>Sucursal destino</th>
                                <th>Conductor</th>
                                <th>Placa</th>
                                <th>Visualizar ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($datos as $dato){ ?>
                                <tr>
                                    <td><?php echo $dato['folio']; ?></td>
                                    <td><?php echo $dato['fecha_alta']; ?></td>
                                    <td><?php echo $dato['fecha_llegada']; ?></td>
                                    <td><?php echo $dato['id_sucursalSalida']; ?></td>
                                    <td><?php echo $dato['id_sucursalEntrada']; ?></td>
                                    <td><?php echo $dato['id_camioneta']; ?></td>
                                    <td><?php echo $dato['placa']; ?></td>
                                    <td><a href="<?php echo base_url()."/envios/muestraTicket/".$dato['id_envio']; ?>" class="btn btn-primary"><i class="fa-solid fa-file-pdf"></i></i></a></td>
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