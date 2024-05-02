<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo;?></h4>

                <div class="table-responsive">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Id usuario</th>
                                <th>Evento</th>
                                <th>Fecha</th>
                                <th>Detalles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($datos as $dato){ ?>
                                <tr>
                                    <td><?php echo $dato['id_log']; ?></td>
                                    <td><?php echo $dato['id_usuario']; ?></td>
                                    <td><?php echo $dato['evento']; ?></td>
                                    <td><?php echo $dato['fecha']; ?></td>
                                    <td><?php echo $dato['detalles']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </main>