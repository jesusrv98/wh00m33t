<?php

ob_start();

if (!isset($_SESSION['usuarioconectado'])) {
    header('Location:index.php?ctl=login');
}

?>
<!DOCTYPE HTML>
<html lang="es-ES">

<head>
    <title>WhoMeet - Inicio</title>
    <!-- ETIQUETAS META -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- FIN ETIQUETAS META -->

    <!-- ETIQUETAS LINK DE IMPORTACIÓN -->
        <!-- LINKS BOOTSTRAP -->
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <!-- FIN DE ETIQUETAS DE IMPORTACIÓN -->

    <!-- Estilos y favicon -->
    <link rel="icon" href="images/logo.png">
    <style type="text/css">
        #fondodelmenu:hover {
            background-color: #539689;
            color: white;
            font-size: 1em;
            border-radius: 1em;
        }

        #fondosubmenu:hover {
            background-color: #539689;
            color: white;
            font-size: 1em;
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
    </style>
    <!-- Fin style -->

</head>

<body>
    <!-- BARRA DE NAVEGACIÓN -->
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background: linear-gradient(#93ECFF,white); color: #027F6D">
        <a class="navbar-brand" style="color:#027F6D" href='index.php?ctl=inicio'><img src="images/logo.png" width="100" height="100" class="d-inline-block align-top" alt="Logo" /></a>
        <button class="navbar-toggler" style="background:#33cbad" type="button" data-toggle="collapse" data-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
            <span style="color:#027F6D" class="navbar-toggler-icon"></span>
        </button>
        <div style="color:#027F6D" class="collapse navbar-collapse" id="navbarsExample05">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" style="color:#33cbad" id="fondodelmenu" href='index.php?ctl=inicio'><i class="fas fa-home"></i> Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="color:#33cbad" id="fondodelmenu" href='index.php?ctl=perfil'><i class="far fa-user"></i> Perfil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="color:#33cbad" id="fondodelmenu" href='index.php?ctl=mensajes'><i class="fas fa-comment-alt"></i> Mensajes
                        <?php
                            if ($params['countMensajesPV']) {
                                echo "<span class='badge badge-success'>" . $params['countMensajesPV'] . "</span></a>";
                            }
                        ?>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="fondodelmenu" style="color:#33cbad" href="#" id="dropdown05" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="far fa-bell"></i>
                        Gestión del perfil
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdown05">
                        <a class="dropdown-item" style="color:#33cbad" id="fondosubmenu" href='index.php?ctl=solicitudes'><i class="fas fa-user"></i> Solicitudes</a>
                        <a class="dropdown-item" style="color:#33cbad" id="fondosubmenu" href='index.php?ctl=gestionAmigos'><i class="fas fa-users"></i> Amigos</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="fondodelmenu" style="color:#33cbad" href="#" id="dropdown05" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cog"></i>
                        Configuración
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdown05">
                        <a class="dropdown-item" style="color:#33cbad" id="fondosubmenu" href='index.php?ctl=configuracion'><i class="fas fa-wrench"></i> Configuración</a>
                        <a class="dropdown-item" style="color:#33cbad" id="fondosubmenu" href='index.php?ctl=contacto'><i class="fas fa-phone"></i> Contacto</a>
                        <a class="dropdown-item" style="color:#33cbad" id="fondosubmenu" href='index.php?ctl=logout'><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                    </div>
                </li>
            </ul>
            <?php if(implode(array_column($_SESSION['usuarioconectado'], "id")) == 30) : ?>
                <ul class="navbar-nav d-flex justify-content-end">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="fondodelmenu" style="color:#33cbad" href="#" id="dropdown05" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-key"></i>
                            Administración
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown05">
                            <a class="dropdown-item" style="color:#33cbad" id="fondosubmenu" href='index.php?ctl=solicitudes'><i class="fas fa-users-cog"></i> Gestión de usuarios</a>
                            <a class="dropdown-item" style="color:#33cbad" id="fondosubmenu" href='index.php?ctl=solicitudes'><i class="fas fa-trash-alt"></i> Gestión de contenido</a>
                            <a class="dropdown-item" style="color:#33cbad" id="fondosubmenu" href='index.php?ctl=solicitudes'><i class="fas fa-chart-bar"></i> Estadísticas</a>
                        </div>
                    </li>
                </ul>
                <?php endif; ?>
            <form action="index.php?ctl=busqueda" method="post" class="form-inline my-2 my-md-0">
                <input class="form-control form-control-sm" autocomplete="off" size="25" type="text" name="nombreBusqueda" value="<?php $params['nombreBusqueda'] ?>" placeholder="Buscar usuarios..." />
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
    <div>
        <div style="text-align: center; vertical-align: bottom;color:#33cbad;font-weight: bold" >- WhoMeet &copy; 2019 -</div>
    </div>
    <!-- FIN DE PIE -->

    <!-- LISTA DE SCRIPTS DE BOOTSTRAP -->
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!-- FIN DE LISTA DE SCRIPTS DE BOOTSTRAP -->
</body>

</html>