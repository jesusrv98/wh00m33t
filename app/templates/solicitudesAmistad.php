<?php ob_start() ?>
<script src="js/jqueryGoogle.js"></script>
<script>
    $(document).ready(function() {
        $(".botonAceptar").click(function() {
            var boton = $(this);
            var formularioCancelar = boton.parent().parent().find(".form-cancelar");
            var formularioBloquear = boton.parent().parent().find(".form-bloquear");
            var idNuevoAmigo = $(this).val();
            var idSolicitud = $(this).attr("id");
            var parametros = {
                'tipo': "aceptar",
                'idUsuario': <?php echo $params['idUsuarioConectado'] ?>,
                'idNuevoAmigo': idNuevoAmigo,
                'idSolicitud': idSolicitud
            };
            $.ajax({
                data: parametros,
                url: '../app/templates/includes/servletGestionSolicitudesAmistad.php',
                type: 'post',
                async: true,
                success: function(msg) {
                    if (msg == 'ok') {
                        boton.attr("disabled", "true");
                        boton.find("i").removeClass("fa-user-plus");
                        boton.find("i").addClass("fa-user-check");
                        boton.find("span").text("¡Ya sois amigos!");
                        formularioBloquear.css("display", "none");
                        formularioCancelar.css("display", "none");
                    } else {
                        alert(msg);
                    }
                }
            });
        });
        $(".botonCancelar").click(function() {
            var boton = $(this);
            var formularioAceptar = boton.parent().parent().find(".form-aceptar");
            var formularioBloquear = boton.parent().parent().find(".form-bloquear");
            var idNuevoAmigo = $(this).val();
            var idSolicitud = $(this).attr("id");
            var parametros = {
                'tipo': "cancelar",
                'idUsuario': <?php echo $params['idUsuarioConectado'] ?>,
                'idNuevoAmigo': idNuevoAmigo,
                'idSolicitud': idSolicitud
            };
            $.ajax({
                data: parametros,
                url: '../app/templates/includes/servletGestionSolicitudesAmistad.php',
                type: 'post',
                async: true,
                success: function(msg) {
                    if (msg == 'ok') {
                        boton.attr("disabled", "true");
                        boton.find("i").removeClass("fa-user-plus");
                        boton.find("i").addClass("fa-user-check");
                        boton.find("span").text("Solicitud de amistad rechazada");
                        formularioBloquear.css("display", "none");
                        formularioAceptar.css("display", "none");
                    } else {
                        alert(msg);
                    }
                }
            });
        });
    });
</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success" role="alert">
                <?php
                if ($params['countPeticiones'] > 1) {
                    echo " <h2><i class='fas fa-user-plus'></i> Tienes " . $params['countPeticiones'] . " peticiones de amistad.</h2>";
                } else if ($params['countPeticiones'] == 1) {
                    echo " <h2><i class='fas fa-user-plus'></i> Tienes " . $params['countPeticiones'] . " petición de amistad.</h2>";
                } else {
                    echo " <h2><i class='fas fa-user-plus'></i> No tienes ninguna petición de amistad.</h2>";
                }
                ?>
            </div>
        </div>
        <div class="col-12">
            <section class="container">
                <?php foreach ($params['solicitudes'] as $solicitud) : ?>
                    <div class="card mt-2">
                        <div class="row no-gutters">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-1 col-xl-1 d-flex justify-content-center align-items-center media-left">
                                <img src="images/<?php echo $solicitud['fotoPerfil'] ?>" class="rounded-circle border media-object" width="69" height="64" alt="Foto de perfil de <?php echo $solicitud['correo'] ?>">
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-11 col-xl-11">
                                <div class="card-body">
                                    <form method="post" action="index.php?ctl=perfil">
                                        <input type="hidden" value="<?= $solicitud['id'] ?>" name="perfilUsuario">
                                        <h3 class="media-heading" onclick="$(this).parent().submit()" style="color: #33cbad;cursor:pointer;"><?= $solicitud['nombre'] . " " . $solicitud['apellidos'] ?></h3>
                                    </form>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <form method='post' class='form-inline my-2 my-md-0 mr-1 form-aceptar'>
                                            <button type='button' value='<?= $solicitud['id'] ?>' id="<?= $solicitud['idSolicitud'] ?> '" class='btn btn-sm btn-success botonAceptar'>
                                                <i class='fas fa-user-plus'></i> <span class="textoCambiarAceptar">Aceptar</span>
                                            </button>
                                        </form>
                                        <form method='post' class='form-inline my-2 my-md-0 mr-1 form-cancelar'>
                                            <button type='button' value='<?= $solicitud['id'] ?>' id="<?= $solicitud['idSolicitud'] ?>" class='btn btn-sm btn-danger botonCancelar'>
                                                <i class='fas fa-user-times'></i> <span class="textoCambiarCancelar">Cancelar</span>
                                            </button>
                                        </form>
                                        <form method="post" class="form-inline my-2 my-md-0 form-bloquear">
                                            <button type="button" class="btn btn-sm botonBloquear" value='<?= $solicitud['id'] ?>' style="background: lightgrey" title="Bloquear usuario">
                                                <i class="fas fa-ban text-white"></i> <span class="text-white textoCambiarBloquear">Bloquear</span>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="card-text">Sexo: <?php echo $solicitud['sexo'] ?> </p>
                                        <p class="card-text"><small class="text-muted"><?php echo $solicitud['poblacion'] . ", " . $solicitud['provincia'] . "." ?></small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        </div>
    </div>
</div>
<?php $contenido = ob_get_clean() ?>

<head>
    <title>WhoMeet - Solicitudes de amistad </title>
</head>
<?php include 'layout.php' ?>