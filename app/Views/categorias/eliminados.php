<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4>

            <div>
                <p>
                    <a href="<?php echo base_url();?>/categorias" class="btn btn-warning">Categorias</a>
                </p>
            </div>

                <div class="table-responsive">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Reingresar</th>
                                <th>Fecha baja</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($datos as $dato){ ?>
                                <tr>
                                    <td><?php echo $dato['id_categoria']; ?></td>
                                    <td><?php echo $dato['nombre']; ?></td>
                                    <td><a href="<?php echo base_url()."/categorias/reingresar/".$dato['id_categoria']; ?>"><i class="fa-solid fa-arrows-rotate btn btn-success"></i></a></td>
                                    <td><?php echo $dato['fecha_del']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </main>
                