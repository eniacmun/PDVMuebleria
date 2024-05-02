<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4>
            <div>
                <p>
                    <a href="<?php echo base_url();?>/camionetas" class="btn btn-warning">Camionetas</a>
                </p>
            </div>
                <div class="table-responsive">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Modelo</th>
                                <th>Conductor</th>
                                <th>Placa</th>
                                <th>Reingresar</th>
                                <th>Fecha eliminación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($datos as $dato){ ?>
                                <tr>
                                    <td><?php echo $dato['id_camioneta']; ?></td>
                                    <td><?php echo $dato['modelo']; ?></td>
                                    <td><?php echo $dato['nombre']; ?></td>
                                    <td><?php echo $dato['placa']; ?></td>
                                    <td><a href="#exampleModalToggle" data-href="<?php echo base_url()."/camionetas/reingresar/".$dato['id_camioneta']; ?>" data-bs-toggle="modal" data-target="#exampleModalToggle"
                                    data-placement="top" title="Reingresar registro"><i class="fa-solid fa-arrows-rotate btn-success btn"></i></a></td>                                    
                                    <td><?php echo $dato['fecha_baja']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </main>
<div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel">Reingresar registro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>¿Desea reingresar este registro?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">No</button>
        <a class="btn btn-danger btn-ok" id="btn-ok">Sí</a>
      </div>
    </div>
  </div>
</div>                  