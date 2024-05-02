<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4>

            <div>
                <p>
                    <a href="<?php echo base_url();?>/cajas/nuevo_arqueo" class="btn btn-info">Abrir caja</a>
                </p>
            </div>

                <div class="table-responsive">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Fecha apertura</th>
                                <th>Fecha cierra</th>
                                <th>Monto inicial</th>
                                <th>Monto final</th>
                                <th>Total ventas</th>
                                <th>Estatus</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($datos as $dato){ ?>
                                <tr>
                                    <td><?php echo $dato['id_arqueo']; ?></td>
                                    <td><?php echo $dato['fecha_inicio']; ?></td>
                                    <td><?php echo $dato['fecha_fin']; ?></td>
                                    <td><?php echo $dato['monto_inicial']; ?></td>
                                    <td><?php echo $dato['monto_final']; ?></td>
                                    <td><?php echo $dato['total_ventas']; ?></td>
                                    <?php if($dato['estatus']==1){?>
                                      <td>Abierta</td>
                                      <td><a href="#exampleModalToggle" data-href="<?php echo base_url()."/cajas/cerrar/".$dato['id_caja']; ?>" data-bs-toggle="modal" data-target="#exampleModalToggle"
                                      data-placement="top" title="Eliminar registro"><i class="fa-solid fa-lock btn btn-danger"></i></a></td>
                                      <?php }else{?>
                                      <td>Cerrada</td>
                                      <td><a href="<?php echo base_url()."/cajas/generarReporteCierre/".$dato['id_arqueo']; ?>"><i class="fa-solid fa-print btn btn-success"></i></a></td>
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
        <p>¿Desea cerrar la caja?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">No</button>
        <a class="btn btn-danger btn-ok" id="btn-ok">Sí</a>
      </div>
    </div>
  </div>
</div>        