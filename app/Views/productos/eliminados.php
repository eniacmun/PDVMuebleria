<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4>
            <div>
                <p>
                    <a href="<?php echo base_url();?>/productos" class="btn btn-warning">Productos</a>
                </p>
            </div>
                <div class="table-responsive">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Existencias</th>
                                <th>Descripción</th>
                                <th>Reingresar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($datos as $dato){ ?>
                                <tr>
                                    <td><?php echo $dato['idProducto']; ?></td>
                                    <td><?php echo $dato['codigo']; ?></td>
                                    <td><?php echo $dato['nombre']; ?></td>
                                    <td><?php echo $dato['precio_venta']; ?></td>
                                    <td><?php echo $dato['cantidad']; ?></td>
                                    <td><?php echo $dato['descripcion']; ?></td>
                                    <td><a href="#exampleModalToggle" data-href="<?php echo base_url()."/productos/reingresar/".$dato['idProducto']; ?>" data-bs-toggle="modal" data-target="#exampleModalToggle"
                                    data-placement="top" title="Reingresar registro"><i class="fa-solid fa-arrows-rotate btn-success btn"></i></a></td> 
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