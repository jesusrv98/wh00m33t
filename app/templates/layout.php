<?php

ob_start();

if (!isset($_SESSION['usuarioconectado'])) {
    header('Location:index.php?ctl=logout');
}
if ($params['baneado']) {
    header("Location: index.php?ctl=logout");
}

?>
<!DOCTYPE HTML>
<html lang="es-ES">

<head>
    <title>WhoMeet - Inicio</title>
    <!-- ETIQUETAS META -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="NoIndex, Follow">
    <meta name="google" content="nositelinkssearchbox">
    <meta name=”googlebot” content=”nosnippet”>
    <meta name="author" content="Jesús Rodríguez Vargas">
    <!-- FIN ETIQUETAS META -->

    <!-- ETIQUETAS LINK DE IMPORTACIÓN -->
    <!-- LINKS BOOTSTRAP -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap.css">
    <!-- LINKS POTLY JS(GRÁFICAS) -->
    <script src="js/potly.js"></script>
    <!-- LINK FONTAWESOME -->
    <link href="fonts/fontawesome/css/all.css" rel="stylesheet">
    <!-- FIN DE ETIQUETAS DE IMPORTACIÓN -->
    <!-- Estilos y favicon -->
    <link rel="icon" href="images/logoInicio.png">
    <style type="text/css">
        #fondodelmenu {
            background: transparent;
        }

        #fondodelmenu:hover {
            background-color: #539689;
            color: white;
            border-radius: 1em;
        }

        #fondosubmenu:hover {
            background-color: #539689;
            color: white;
        }

        #guardarpubli:hover {
            background: #99EFFF;
            color: white;
            cursor: pointer;
        }

        #fondoDegradado {
            background: linear-gradient(#93ECFF, white);
            color: #027F6D;
        }

        .texto {
            font-size: 1.1rem;
        }
    </style>
    <!-- Fin style -->

</head>

<body>
    <!-- BARRA DE NAVEGACIÓN -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: linear-gradient(#93ECFF,white); color: #027F6D">
        <a class="navbar-brand" style="color:#027F6D" href='index.php?ctl=inicio'><img src="images/logo.png" width="110" height="55" class="d-inline-block align-top" alt="Logo" /></a>
        <button class="navbar-toggler" style="background:#33cbad" type="button" data-toggle="collapse" data-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
            <span style="color:#027F6D" class="navbar-toggler-icon"></span>
        </button>
        <div style="color:#027F6D" class="collapse navbar-collapse" id="navbarsExample05">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link texto" style="color:#33cbad;" id="fondodelmenu" href='index.php?ctl=inicio'><i class="fas fa-home"></i> Inicio</a>
                </li>
                <li class="nav-item">
                    <form method="post" action="index.php?ctl=perfil">
                        <button type="submit" name="perfilUsuario" value="<?= implode(array_column($_SESSION['usuarioconectado'], "id")) ?>" class="nav-link texto" style="color:#33cbad; border:none" id="fondodelmenu"><i class="far fa-user"></i> Perfil</button>
                    </form>
                </li>
                <li class="nav-item">
                    <a class="nav-link texto" style="color:#33cbad" id="fondodelmenu" href='index.php?ctl=mensajes'><i class="fas fa-comment-alt"></i> Mensajes
                        <?php
                        if ($params['countMensajesPV']) {
                            echo "<span class='badge badge-success'>" . $params['countMensajesPV'] . "</span></a>";
                        }
                        ?>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle texto" id="fondodelmenu" style="color:#33cbad" href="#" id="dropdown05" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="far fa-bell"></i>
                        Gestión del perfil
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdown05">
                        <a class="dropdown-item texto" style="color:#33cbad" id="fondosubmenu" href='index.php?ctl=solicitudes'><i class="fas fa-user"></i> Solicitudes</a>
                        <a class="dropdown-item texto" style="color:#33cbad" id="fondosubmenu" href='index.php?ctl=gestionAmigos'><i class="fas fa-users"></i> Amigos</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle texto" id="fondodelmenu" style="color:#33cbad" href="#" id="dropdown05" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cog"></i>
                        Configuración
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdown05">
                        <a class="dropdown-item texto" style="color:#33cbad" id="fondosubmenu" href='index.php?ctl=configuracion'><i class="fas fa-wrench"></i> Configuración</a>
                        <a class="dropdown-item texto" style="color:#33cbad" id="fondosubmenu" data-toggle="modal" data-target="#modalContacta" href="#"><i class="fas fa-phone"></i> Contacto</a>
                        <a class="dropdown-item texto" style="color:#33cbad" id="fondosubmenu" href='index.php?ctl=logout'><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                    </div>
                </li>
            </ul>
            <?php if (implode(array_column($_SESSION['usuarioconectado'], "id")) == 30) : ?>
                <ul class="navbar-nav d-flex justify-content-end">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle texto" id="fondodelmenu" style="color:#33cbad" href="#" id="dropdown05" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-key"></i>
                            Administración
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown05">
                            <a class="dropdown-item texto" style="color:#33cbad" id="fondosubmenu" href='index.php?ctl=gestionUsuarios'><i class="fas fa-users-cog"></i> Gestión de usuarios</a>
                            <a class="dropdown-item texto" style="color:#33cbad" id="fondosubmenu" href='index.php?ctl=gestionContenido'><i class="fas fa-trash-alt"></i> Gestión de contenido</a>
                            <a class="dropdown-item texto" style="color:#33cbad" id="fondosubmenu" href='index.php?ctl=estadisticas'><i class="fas fa-chart-bar"></i> Estadísticas</a>
                        </div>
                    </li>
                </ul>
            <?php endif; ?>
            <form action="index.php?ctl=busqueda" method="post" class="form-inline my-2 my-md-0">
                <input class="form-control form-control-sm" autocomplete="off" type="text" name="nombreBusqueda" value="<?php $params['nombreBusqueda'] ?>" placeholder="Buscar usuarios..." />
            </form>
        </div>
    </nav>
    <!-- FIN DE BARRA DE NAVEGACIÓN -->

    <!-- CONTENIDO -->
    <div>
        <?php echo $contenido ?>
    </div>
    <!-- FIN DE CONTENIDO -->

    <!-- PIE -->
    <div id="piePagina">
        <div style="text-align: center; vertical-align: bottom;color:#33cbad;font-weight: bold">- WhoMeet &copy; 2019 -</div>
    </div>
    <!-- FIN DE PIE -->
    <!-- MODAL CONTACTA -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" id="modalContacta" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <!-- CUERPO MODAL CONTACTA -->
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="width: 100%;">
                <div class="modal-content">
                    <form action="https://mailthis.to/rodriguezvargasjesus@gmail.com" method="POST">
                        <div class="card border-primary rounded-0">
                            <div class="card-header">
                                <div class="bg-info text-white text-center py-2">
                                    <h3><i class="fa fa-envelope"></i> Contactanos</h3>
                                    <p class="m-0">Con gusto te ayudaremos</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-user text-info"></i></div>
                                        </div>
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre y Apellido" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-envelope text-info"></i></div>
                                        </div>
                                        <input type="email" class="form-control" id="nombre" name="email" placeholder="ejemplo@gmail.com" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-comment text-info"></i></div>
                                        </div>
                                        <textarea class="form-control" name="Mensaje" placeholder="Envianos tu Mensaje" required></textarea>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <input type="submit" value="Enviar" class="btn btn-info btn-block rounded-0 py-2">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- FIN CUERPO MODAL CONTACTA -->
        </div>
    </div>

    <!-- LISTA DE SCRIPTS DE BOOTSTRAP -->
    <script src="js/bootstrap/popper.min.js" type="text/javascript"></script>
    <script src="js/bootstrap/bootstrap.min.js" type="text/javascript"></script>
    <!-- FIN DE LISTA DE SCRIPTS DE BOOTSTRAP -->
</body>

</html>