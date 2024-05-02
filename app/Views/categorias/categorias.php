<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4>

            <div>
                <p>
                    <a href="<?php echo base_url();?>/categorias/nuevo" class="btn btn-info">Agregar</a>
                    <a href="<?php echo base_url();?>/categorias/eliminados" class="btn btn-warning">Eliminados</a>
                </p>
            </div>

                <div class="table-responsive">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Editar</th>
                                <th>Eliminar</th>
                                <th>Alta</th>
                                <th>Última edición</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($datos as $dato){ ?>
                                <tr>
                                    <td><?php echo $dato['id_categoria']; ?></td>
                                    <td><?php echo $dato['nombre']; ?></td>
                                    <td><a href="<?php echo base_url()."/categorias/editar/".$dato['id_categoria']; ?>" class="btn btn-warning"><i class="fa-solid fa-pencil"></i></a></td>
                                    <td><a href="<?php echo base_url()."/categorias/eliminar/".$dato['id_categoria']; ?>" class="btn btn-danger"><i class="fa-solid fa-xmark"></i></a></td>
                                    <td><?php echo $dato['fecha_alta']; ?></td>
                                    <td><?php echo $dato['fecha_edit']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </main>
                