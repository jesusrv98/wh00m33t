<?php
ob_start();
$c = new Controller();
if ($params['idUsuarioConectado'] != 30) {
    header("Location: index.php?ctl=inicio");
}
?>
<script src="js/jqueryGoogle.js"></script>
<style>
    .page-item.active .page-link {
        background: #33cbad;
        border-color: inherit;
    }

    .overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100%;
        width: 100%;
        opacity: 0;
        transition: .5s ease;
        background-color: #008CBA;
    }

    .contenedorFoto:hover .overlay {
        opacity: 1;
    }

    .text {
        color: white;
        font-size: 20px;
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        text-align: center;
    }
</style>
<script>
    $(document).ready(function() {
        $('.botonEliminar').click(function() {
            if (confirm("¿Estás seguro de que quieres borrar tu amistad con el usuario seleccionado?")) {
                var idUsuario2 = $(this).val();
                var botonPulsado = $(this);

                var parametros = {
                    'idUsuario1': idUsuario1,
                    'idUsuario2': idUsuario2,
                };
                $.ajax({
                    data: parametros,
                    url: '../app/templates/includes/servletEliminarAmigo.php',
                    type: 'post',
                    async: true,
                    success: function(msg) {
                        if (msg == 'ok') {
                            window.location.replace("http://localhost/proyectoGIT/wh00m33t/web/index.php?ctl=gestionUsuarios");

                        } else {
                            alert("No se pudo enviar la solicitud de amistad a la siguiente persona: " + msg);
                        }
                    },
                    error: function() {
                        alert("Ha ocurrido un error y no se puede agregar.");
                    }
                });
            }
        });
        $(".contenedorFoto").click(function() {
            if (confirm("¿Desea cambiarle la foto al usuario escogido?")) {
                var foto = $(this).find("img");
                var idUsuario = foto.attr("id");

                var parametros = {
                    'idUsuario': idUsuario,
                    'fotoPerfil': 'avatar.jpg',
                    'opcion': 'cambiarFoto'
                };
                $.ajax({
                    data: parametros,
                    url: '../app/templates/includes/servletGestionUsuarios.php',
                    type: 'post',
                    async: true,
                    success: function(msg) {
                        if (msg == 'ok') {
                            foto.attr("src", "images/avatar.jpg");

                        } else {
                            alert("No se pudo cambiar la foto de perfil a la siguiente persona: " + msg);
                        }
                    },
                    error: function() {
                        alert("Ha ocurrido un error y no se puede cambiar.");
                    }
                });
            }
        });
        $(".botonBorrar").click(function() {
            if (confirm("¿Desea eliminar permanéntemente el usuario escogido?")) {
                var boton = $(this);
                var idUsuario = $(this).attr("id");

                var parametros = {
                    'idUsuario': idUsuario,
                    'opcion': 'borrar'
                };
                $.ajax({
                    data: parametros,
                    url: '../app/templates/includes/servletGestionUsuarios.php',
                    type: 'post',
                    async: true,
                    success: function(msg) {
                        if (msg == 'ok') {
                            boton.find(".textoCambiarBorrar").text("Usuario eliminado");
                            boton.find("i").removeClass("fa-user-times");
                            boton.find("i").addClass("fa fa-user-check");
                            boton.attr("disabled", "true");

                        } else {
                            alert("No se pudo borraar: " + msg);
                        }
                    },
                    error: function() {
                        alert("Ha ocurrido un error y no se puede borrar.");
                    }
                });
            }
        });
        $(".botonBanear").click(function() {
            if (confirm("¿Desea banear el usuario escogido?")) {
                var boton = $(this);
                var idUsuario = $(this).attr("id");

                var parametros = {
                    'idUsuario': idUsuario,
                    'opcion': 'banear'
                };
                $.ajax({
                    data: parametros,
                    url: '../app/templates/includes/servletGestionUsuarios.php',
                    type: 'post',
                    async: true,
                    success: function(msg) {
                        if (msg == 'ok') {
                            boton.find(".textoCambiarBanear").text("Desbanear");
                            boton.removeClass("botonBanear");
                            boton.addClass("botonDesbanear")
                            boton.find("i").removeClass("fa-ban");
                            boton.find("i").addClass("fas fa-unlock")

                        } else {
                            alert("No se pudo banear al usuario: " + msg);
                        }
                    },
                    error: function() {
                        alert("Ha ocurrido un error y no se puede banear.");
                    }
                });
            }
        });
        $(".botonDesbanear").click(function() {
            if (confirm("¿Desea desbanear el usuario escogido?")) {
                var boton = $(this);
                var idUsuario = $(this).attr("id");

                var parametros = {
                    'idUsuario': idUsuario,
                    'opcion': 'desbanear'
                };
                $.ajax({
                    data: parametros,
                    url: '../app/templates/includes/servletGestionUsuarios.php',
                    type: 'post',
                    async: true,
                    success: function(msg) {
                        if (msg == 'ok') {
                            boton.find(".textoCambiarDesbanear").text("Banear");
                            boton.removeClass("botonDesbanear");
                            boton.addClass("botonBanear")
                            boton.find("i").removeClass("fas fa-unlock");
                            boton.find("i").addClass("fas fa-ban")

                        } else {
                            alert("No se pudo desbanear al usuario: " + msg);
                        }
                    },
                    error: function() {
                        alert("Ha ocurrido un error y no se puede banear.");
                    }
                });
            }
        });
    });
</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info" role="alert">
                <h3>Gestion de usuarios</h3>
            </div>
        </div>
        <div class="col-12">
            <form method="POST" action="index.php?ctl=gestionUsuarios">
                <div class="form-row">
                    <div class="col-8">
                        <input type="text" class="form-control form-control-sm" autocomplete="off" name="usuarioBuscado" placeholder="Nombre usuario..." value="<?= $params['usuarioBuscado'] ?>">
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-info btn-block"> <i class="fas fa-search text-white"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12">
            <?php foreach ($params['listaUsuarios'] as $usuario) : ?>
                <div class="card mt-2">
                    <div class="row no-gutters">
                        <div class="col-5 col-sm-5 col-md-5 col-lg-1 col-xl-1 d-flex justify-content-center align-items-center contenedorFoto" style="position:relative;cursor: pointer">
                            <img src="images/<?php echo $usuario['fotoPerfil'] ?>" id="<?= $usuario['id'] ?>" class="media-object rounded-circle mr-2 mt-1 border fotoPerfil" width="89" height="84" alt="Foto de perfil de <?php echo $usuario['correo'] ?>">
                            <div class="overlay">
                                <div class="text">Restablecer foto</div>
                            </div>
                        </div>
                        <div class="col-7 col-sm-7 col-md-7 col-lg-11 col-xl-11">
                            <div class="card-body">
                                <form method="post" action="index.php?ctl=perfil">
                                    <input type="hidden" value="<?= $usuario['id'] ?>" name="perfilUsuario">
                                    <h3 class="card-title font-weight-bold nombreCompleto" onclick="$(this).parent().submit()" style="color:#42cfb3;cursor:pointer"><?php echo $usuario['nombre'] . " " . $usuario['apellidos'] ?></h3> <small class="text-muted">Id: <?= $usuario['id'] ?></small>
                                </form>
                                <h6><small class="text-muted">Correo: <?= $usuario['correo'] ?></small></h6>
                                <h6><small class="text-muted">Edad: <?= $c->aniosHastaHoy($usuario['fechanac']) ?></small></h6>
                                <h6><small class="text-muted">Sexo: <?= $usuario['sexo'] ?></small></h6>
                                <h6><small class="text-muted">Teléfono: <?= $usuario['telefono'] ?></small></h6>
                                <?php if ($usuario['estado'] == "online") : ?>
                                    <h6><small class="text-muted">Estado: <small style="color:green"><?= $usuario['estado'] ?></small></small></h6>
                                <?php endif; ?>
                                <?php if ($usuario['estado'] == "offline") : ?>
                                    <h6><small class="text-muted">Estado: <small style="color:red"><?= $usuario['estado'] ?></small></small></h6>
                                <?php endif ?>
                                <h6><small class="text-muted">Estado civil: <?= $usuario['estadocivil'] ?></small></h6>


                                <div class="d-flex justify-content-end align-items-center">
                                    <form method='post' class='form-inline my-2 my-md-0 mr-1 form-cancelar'>
                                        <button type='button' id='<?= $usuario['id'] ?>' class='btn btn-sm btn-danger botonBorrar'>
                                            <i class='fas fa-user-times'></i> <span class="textoCambiarBorrar">Borrar usuario</span>
                                        </button>
                                    </form>
                                    <?php
                                    $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);
                                    $isBanned = $m->isBaneado($usuario['id']);
                                    ?>
                                    <form method=" post" class="form-inline my-2 my-md-0">
                                        <?php if (!$isBanned) : ?>
                                            <button type="button" id='<?= $usuario['id'] ?>' class="btn btn-sm botonBanear" style="background: lightgrey" title="Banear usuario">
                                                <i class="fas fa-ban text-white"></i> <span class="textoCambiarBanear text-white">Banear usuario</span>
                                            </button>
                                        <?php endif; ?>
                                        <?php if ($isBanned) : ?>
                                            <button type="button" id='<?= $usuario['id'] ?>' class="btn btn-sm botonDesbanear" style="background: lightgrey" title="Desbanear usuario">
                                                <i class="fas fa-unlock text-white"></i> <span class="textoCambiarDesbanear text-white">Desbanear usuario</span>
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="card-text"><small class="text-muted"><?php echo $usuario['poblacion'] . ", " . $usuario['provincia'] . "." ?></small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php $contenido = ob_get_clean() ?>

<head>
    <title>WhoMeet - Gestión de usuarios </title>
</head>
<?php include 'layout.php' ?>