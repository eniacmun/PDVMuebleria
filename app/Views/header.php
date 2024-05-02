<?php 
$user_session=session();
$permisos=$user_session->permisos;
$arreglo=array();
foreach ($permisos as $permiso){
    $arreglo[]= $permiso['nombre'];
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Mueblería Doña Luci</title>
        <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" />
        <link href="<?php echo base_url();?>/css/styles.css" rel="stylesheet" />
        <link href="<?php echo base_url();?>/js/jquery-ui/jquery-ui.min.css" rel="stylesheet"/>
        <script src="<?php echo base_url();?>/js/jquery-ui/external/jquery/jquery.js"></script>   
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <script src="<?php echo base_url();?>/js/jquery-ui/jquery-ui.min.js"></script>  
        <script src="<?php echo base_url();?>/js/chart.min.js"></script>    
          
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="<?php echo base_url();?>/inicio">Mueblería Doña Luci</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto md-0 me-3 me-lg-4 me-md-3 my-2 my-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo $user_session->nombre;?><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?php echo base_url(); ?>/Usuarios/cambia_password">Cambiar contraseña</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="<?php echo base_url(); ?>/Usuarios/logout">Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Opciones</div>
                                <?php                   
                               
                                if(in_array('Menú productos', $arreglo)){?>
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                
                                        <div class="sb-nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                                        Productos
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <?php
                                            if(in_array('Productos', $arreglo)){?>
                                            <a class="nav-link" href="<?php echo base_url(); ?>/productos">Productos</a>
                                            <?php }?>
                                            <?php
                                            if(in_array('Unidades', $arreglo)){?>
                                            <a class="nav-link" href="<?php echo base_url(); ?>/unidades">Sucursales</a>
                                            <?php }?>                                               
                                            <?php
                                            if(in_array('Categorías', $arreglo)){?>
                                            <a class="nav-link" href="<?php echo base_url(); ?>/categorias">Categorías</a>
                                            <?php }?> 
                                        </nav>
                                    </div>
                               <?php }?>
                               <?php
                                if(in_array('Menú clientes', $arreglo)){?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>/clientes">
                                        <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                                        Cientes
                                    </a>
                                <?php }?>                            
                                <?php
                                    if(in_array('Menú Inventario', $arreglo)){?>
                                        <a class="nav-link" href="<?php echo base_url(); ?>/compras">
                                            <div class="sb-nav-link-icon"><i class="fa-solid fa-cash-register"></i></div>
                                            Inventario
                                        </a>
                                <?php }?> 
                                <?php
                                    if((in_array('Menú caja', $arreglo)) and $user_session->estadoCaja==1){?>
                                        <a class="nav-link" href="<?php echo base_url(); ?>/ventas/venta">
                                            <div class="sb-nav-link-icon"><i class="fa-solid fa-cash-register"></i></div>
                                            Caja
                                        </a>
                                <?php }?> 
                                <?php
                                    if(in_array('Menú ventas', $arreglo)){?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>/ventas">
                                        <div class="sb-nav-link-icon"><i class="fa-solid fa-shopping-cart"></i></div>
                                        Ventas
                                    </a>
                                <?php }?>
                                <?php
                                    if(in_array('Permisos envio', $arreglo)){?>
                                    <a class="nav-link" href="<?php echo base_url(); ?>/envios">
                                        <div class="sb-nav-link-icon"><i class="fa-solid fa-truck"></i></div>
                                        Envío a otra sucursal
                                    </a>
                                <?php }?>  
                                <?php
                                    if(in_array('Menú reportes', $arreglo)){?>
                                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#menuReportes" aria-expanded="false" aria-controls="menuReportes">
                                            <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div>
                                            Reportes
                                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                        </a>
                                        <div class="collapse" id="menuReportes" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                            <nav class="sb-sidenav-menu-nested nav">
                                                <a class="nav-link" href="<?php echo base_url(); ?>/productos/mostrarMinimos">Reporte productos con mínimos</a>
                                                <a class="nav-link" href="<?php echo base_url(); ?>/productos/reporteVentas">Reporte de ventas</a>
                                                <a class="nav-link" href="<?php echo base_url(); ?>/envios/reporteEnvios">Reporte de envios por conductor</a>
                                            </nav>
                                        </div>
                                <?php }?> 
                                <?php
                                    if((in_array('Menú administración', $arreglo) or (in_array('Cajas', $arreglo)))){?>
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#subAdmin" aria-expanded="false" aria-controls="subAdmin">
                                        <div class="sb-nav-link-icon"><i class="fa-solid fa-wrench"></i></i></div>
                                        Administración
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="subAdmin" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <?php
                                            if(in_array('Configuración', $arreglo)){?>
                                                <a class="nav-link" href="<?php echo base_url(); ?>/configuracion">Configuración</a>
                                            <?php }?>
                                            <?php
                                            if(in_array('Usuarios', $arreglo)){?>
                                                <a class="nav-link" href="<?php echo base_url(); ?>/Usuarios">Usuarios</a>
                                            <?php }?>
                                            <?php
                                            if(in_array('Permisos envio', $arreglo)){?>
                                                <a class="nav-link" href="<?php echo base_url(); ?>/Camionetas">Camionetas</a>
                                            <?php }?> 
                                            <?php
                                            if(in_array('Roles', $arreglo)){?>
                                                <a class="nav-link" href="<?php echo base_url(); ?>/Roles">Roles</a>
                                            <?php }?> 
                                            <?php
                                            if(in_array('Cajas', $arreglo)){?>
                                                <a class="nav-link" href="<?php echo base_url(); ?>/Cajas">Cajas</a>
                                            <?php }?>                                            
                                            <?php
                                            if(in_array('Logs de acceso', $arreglo)){?>
                                                <a class="nav-link" href="<?php echo base_url(); ?>/Logs">Registros de acceso</a>
                                            <?php }?> 
                                        </nav>
                                    </div>
                                <?php }?> 
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Permisos:</div>
                        <?php echo $user_session->rol;echo '<br/>';
                         //print_r($arreglo);
                        ?>
                    </div>
                </nav>
            </div>