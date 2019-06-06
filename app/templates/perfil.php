<?php
ob_start();
$c = new Controller();
$m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);
?>
<style>
    .page-item.active .page-link {
        background: #33cbad;
        border-color: inherit;
    }
</style>
<script type="text/javascript" src="js/jqueryGoogle.js"></script>
<script>
    $(document).ready(function() {
        $('.botonComentar').click(function() {
            var formulario = $(this).parent().parent();
            var comentarioNuevo = $(formulario.find(".comentarioNuevo")).val();
            var idEstado = $(formulario.find('.idEstado')).val();
            var idUsuarioDirigido = $(formulario.find('.idUsuarioDirigido')).val();
            var fotoPerfil = "<?= $params['fotoPerfil'] ?>";
            var nombre = "<?php echo implode(array_column($_SESSION['usuarioconectado'], 'nombre')) ?>";
            var apellido = "<?php echo implode(array_column($_SESSION['usuarioconectado'], 'apellidos')) ?>";
            var nombreUsuario = nombre + " " + apellido;

            if (comentarioNuevo.trim() == '') {
                alert('No puede comentar en blanco.');
                $(this).focus();
                return false;
            } else {
                var parametros = {
                    'textoComentario': comentarioNuevo,
                    'idUsuario': <?php echo $params['idUsuario'] ?>,
                    'idEspacio': idEstado,
                    'tipo': 'comentarioEstado',
                    'idUsuarioDirigido': idUsuarioDirigido
                };
                $.ajax({
                    data: parametros,
                    url: '../app/templates/includes/servletComentar.php',
                    type: 'post',
                    async: true,
                    success: function(msg) {
                        if (msg == 'ok') {
                            $(formulario.parent().find('.contenedorNuevosComentarios')).removeClass('d-none');
                            $(formulario.parent().find('.contenedorNuevosComentarios')).append("<div class='media mt-2'><div class='media-left'><img src='images/" + fotoPerfil + "' width='60' height='60' alt='Foto perfil' class='media-object rounded-circle mr-2 mt-1'></div><div class='media-body'><h4 class='media-heading'>" + nombreUsuario + "<small style='font-size: 0.8rem' class='text-muted'> Hace un momento</small></h4><p>" + comentarioNuevo + ".</p></div></div>");
                            $(formulario.find(".comentarioNuevo")).val('');
                        } else {
                            alert("mal comentado: " + msg);
                        }
                    },
                    error: function() {
                        alert("Ha ocurrido un error y no se puede comentar.");

                    }

                });
            }
        });
        $(".comentarioNuevo").keypress(function(e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                var formulario = $(this).parent();
                var comentarioNuevo = $(formulario.find(".comentarioNuevo")).val();
                var idEstado = $(formulario.find('.idEstado')).val();
                var idUsuarioDirigido = $(formulario.find('.idUsuarioDirigido')).val();
                var fotoPerfil = "<?= $params['fotoPerfil'] ?>";
                var nombre = "<?php echo implode(array_column($_SESSION['usuarioconectado'], 'nombre')) ?>";
                var apellido = "<?php echo implode(array_column($_SESSION['usuarioconectado'], 'apellidos')) ?>";
                var nombreUsuario = nombre + " " + apellido;

                if (comentarioNuevo.trim() == '') {
                    alert('No puede comentar en blanco.');
                    $(this).focus();
                    return false;
                } else {
                    var parametros = {
                        'textoComentario': comentarioNuevo,
                        'idUsuario': <?php echo $params['idUsuario'] ?>,
                        'idEspacio': idEstado,
                        'tipo': 'comentarioEstado',
                        'idUsuarioDirigido': idUsuarioDirigido
                    };
                    $.ajax({
                        data: parametros,
                        url: '../app/templates/includes/servletComentar.php',
                        type: 'post',
                        async: true,
                        success: function(msg) {
                            if (msg == 'ok') {
                                $(formulario.parent().find('.contenedorNuevosComentarios')).removeClass('d-none');
                                $(formulario.parent().find('.contenedorNuevosComentarios')).append("<div class='media mt-2'><div class='media-left'><img src='images/" + fotoPerfil + "' width='60' height='60' alt='Foto perfil' class='media-object rounded-circle mr-2 mt-1'></div><div class='media-body'><h4 class='media-heading'>" + nombreUsuario + "<small style='font-size: 0.8rem' class='text-muted'> Hace un momento</small></h4><p>" + comentarioNuevo + ".</p></div></div>");
                                $(formulario.find(".comentarioNuevo")).val('');
                            } else {
                                alert("mal comentado: " + msg);
                            }
                        },
                        error: function() {
                            alert("Ha ocurrido un error y no se puede comentar.");

                        }

                    });
                }
            }
        });
        $(".borrarPublicacion").click(function() {
            if (confirm("¿Desea borrar su publicación?")) {
                var boton = $(this);
                var idEstado = boton.attr("id");

                var parametros = {
                    'idUsuario': <?php echo $params['idUsuario'] ?>,
                    'idEstado': idEstado,
                    'tipo': 'estado'
                };
                $.ajax({
                    data: parametros,
                    url: '../app/templates/includes/servletGestionEstadosYComentarios.php',
                    type: 'post',
                    async: true,
                    success: function(msg) {
                        if (msg == 'ok') {
                            boton.parent().parent().parent().parent().css("display", "none");
                        } else {
                            alert(msg);
                        }
                    }
                });
            }
        });
        $(".borrarComentario").click(function() {
            if (confirm("¿Desea borrar su comentario?")) {
                var boton = $(this);
                var idComentario = boton.attr("id");
                var idEstado = boton.parent().attr("id");

                var parametros = {
                    'idUsuario': <?php echo $params['idUsuario'] ?>,
                    'idComentario': idComentario,
                    'idEstado': idEstado,
                    'tipo': 'comentario'
                };
                $.ajax({
                    data: parametros,
                    url: '../app/templates/includes/servletGestionEstadosYComentarios.php',
                    type: 'post',
                    async: true,
                    success: function(msg) {
                        if (msg == 'ok') {
                            boton.parent().parent().parent().parent().css("display", "none");
                        } else {
                            alert(msg);
                        }
                    }
                });
            }
        });
        $('.botonSolicitud').click(function() {
            var idSolicitante = "<?= $params['idUsuario'] ?>";
            var idSolicitado = $(this).val();
            var botonPulsado = $(this);
            var textoBoton = botonPulsado.find('span');
            var iconoBoton = botonPulsado.find('i')

            var parametros = {
                'idSolicitante': idSolicitante,
                'idSolicitado': idSolicitado,
                'tipo': "peticionAmistad"
            };
            $.ajax({
                data: parametros,
                url: '../app/templates/includes/servletSolicitudAmistad.php',
                type: 'post',
                async: true,
                success: function(msg) {
                    if (msg == 'ok') {
                        iconoBoton.removeClass('fa-user-plus');
                        iconoBoton.addClass('fa-user-check');
                        textoBoton.text('Solicitud de amistad enviada');
                        botonPulsado.attr('disabled', 'true');

                    } else {
                        alert("No se pudo enviar la solicitud de amistad a la siguiente persona: " + msg);
                    }
                },
                error: function() {
                    alert("Ha ocurrido un error y no se puede agregar.");
                }
            });
        });
        $("#fotoSubir").change(function() {
            $(this).parent().submit();
        });
    });
</script>
<div class="container-fluid">
    <div class="row">

        <!-- Parte izquierda -->
        <?php foreach ($params['perfilUsuarioDatos'] as $perfil) : ?>
            <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                <div class="row">
                    <div class="col-12 text-center">
                        <img width="200" height="194" class="rounded-circle border" src="images/<?= $perfil['fotoPerfil'] ?>" />
                    </div>
                    <div class="col-12 border-right">
                        <p class="text-muted border-bottom border-top mt-3"><b>Datos</b></p>
                        <?php if ($m->isAmigoPerfil($params['idUsuario'], $perfil['id'])) : ?>
                            <p><small style="color:#33cbad"><i class="fas fa-globe-europe"></i> Comunidad autónoma: <?= $perfil['comunidad'] ?></small></p>
                            <p><small style="color:#33cbad"><i class="fas fa-city"></i> Provincia: <?= $perfil['provincia'] ?></small></p>
                            <p><small style="color:#33cbad"><i class="fas fa-home"></i> Municipio: <?= $perfil['poblacion'] ?></small></p>
                            <p><small style="color:#33cbad"><i class="fas fa-phone"></i> Teléfono: <?= $perfil['telefono'] ?></small></p>
                            <p><small style="color:#33cbad"><i class="fas fa-plus"></i> Edad: <?= $c->aniosHastaHoy($perfil['fechanac']) ?></small></p>
                            <p><small style="color:#33cbad"><i class="fas fa-venus-mars"></i> Sexo: <?= $perfil['sexo'] ?></small></p>
                            <p><small style="color:#33cbad"><i class="fas fa-user-friends"></i> Estado civil: <?= $perfil['estadocivil'] ?></small></p>
                            <p><small style="color:#33cbad"><i class="fas fa-birthday-cake"></i> Cumpleaños: <?= $c->fechaCumpleanios($perfil['fechanac']) ?></small></p>
                            <?php if ($perfil['estado'] == "online") : ?>
                                <p><small class="text-success"><i class="fas fa-circle text-success"></i> Estado: <span class="text-success">Conectado</span></small></p>
                            <?php endif; ?>
                            <?php if ($perfil['estado'] != "online") : ?>
                                <p><small class="text-danger"><i class="fas fa-circle text-danger"></i> Estado: <span class="text-danger">Desconectado</span></small></p>
                            <?php endif; ?>
                            <p><small style="color:#33cbad"><i class="fas fa-chart-bar"></i> Visitas: <?= $params['visitas'] ?></small></p>
                        <?php endif; ?>
                        <?php if (!$m->isAmigoPerfil($params['idUsuario'], $perfil['id'])) : ?>
                            <h6 class="text-muted">No puedes ver los datos de este usuario ya que no sois amigos.</h6>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
        <!-- Fin parte izquierda -->


        <!-- Comienzo parte central -->
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="row">
                <!-- Nombre de usuario -->
                <?php foreach ($params['perfilUsuarioEstado'] as $perfilUsuarioEstado) : ?>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <h2><?php echo $perfilUsuarioEstado['nombre'] . " " . $perfilUsuarioEstado['apellidos']; ?></h2>
                    </div>
                    <!-- Cabecera de mensaje privado -->
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 d-flex justify-content-end">
                                <?php if ($m->isAmigoPerfil($params['idUsuario'], $perfilUsuarioEstado['id'])) : ?>
                                    <?php if ($m->tieneSolicitud($perfilUsuarioEstado['id'], $params['idUsuario'])) : ?>
                                        <a href="index.php?ctl=solicitudes" style="text-decoration: none" class='btn btn-sm btn-success'>
                                            <i class='fas fa-user-plus'></i> <span>Gestionar solicitudes</span>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!$m->tieneSolicitud($perfilUsuarioEstado['id'], $params['idUsuario'])) : ?>
                                        <?php if ($params['idUsuario'] != $params['idUsuarioPerfil']) : ?>
                                            <a href="index.php?ctl=mensajes" style="text-decoration: none;color:grey;">
                                                <div class="form-group btn" style="background-color: #cecece">
                                                    <i class="fas fa-envelope"></i> Enviar mensaje privado
                                                </div>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($params['idUsuario'] == $params['idUsuarioPerfil']) : ?>
                                            <a href="index.php?ctl=configuracion" style="text-decoration: none;color:grey;">
                                                <div class="form-group btn" style="background-color: #cecece">
                                                    <i class="fas fa-edit"></i> Editar perfil
                                                </div>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if (!$m->isAmigoPerfil($params['idUsuario'], $perfilUsuarioEstado['id'])) : ?>
                                    <?php if (!$m->tieneSolicitud($params['idUsuario'], $perfilUsuarioEstado['id'])) : ?>
                                        <form method='post' class='form-inline my-2 my-md-0 mr-1'>
                                            <button type='button' value='<?= $perfilUsuarioEstado['id'] ?>' class='btn btn-sm btn-success botonSolicitud'>
                                                <i class='fas fa-user-plus'></i> <span>Enviar solicitud de amistad</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if ($m->tieneSolicitud($params['idUsuario'], $perfilUsuarioEstado['id'])) : ?>
                                        <button type='button' class='btn btn-sm btn-success' disabled>
                                            <i class='fas fa-user-check'></i> <span>Solicitud de amistad enviada</span>
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- Estado -->
                    <?php if ($m->isAmigoPerfil($params['idUsuario'], $perfilUsuarioEstado['id'])) : ?>
                        <div class=" col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <h6>Última actualización:</h6>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 rounded bg-light border d-flex justify-content-between">
                                    <p class="text-muted mt-2"><?= $perfilUsuarioEstado['estadoCuerpo'] ?> <small><?= $c->formatearFecha($perfilUsuarioEstado['fecha']); ?></small></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <!-- Tablón -->

                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
                    <div class="row">
                        <div class="col-12">
                            <?php if ($m->isAmigoPerfil($params['idUsuario'], $perfilUsuarioEstado['id'])) : ?>
                                <h4>Historial de publicaciones</h4>
                            <?php endif; ?>
                            <!-- Comentarios -->
                            <?php foreach ($params['perfilUsuarioPublicaciones'] as $publicacion) : ?>
                                <?php if ($m->isAmigoPerfil($params['idUsuario'], $perfilUsuarioEstado['id'])) : ?>
                                    <?php $esSuEstado = $m->isSuEstado(implode(array_column($_SESSION['usuarioconectado'], 'id')), $publicacion['idEstado']); ?>

                                    <section style="border-bottom: 1px solid #33cbad;" class="container p-2 mt-2">
                                        <div class="media">
                                            <div class="media-left">
                                                <img src="images/<?= $publicacion['fotoPerfil'] ?>" width="69" height="64" alt="Foto perfil" class="media-object rounded-circle mr-2 mt-1 border">
                                            </div>
                                            <div class="media-body">
                                                <div class="d-flex justify-content-between">
                                                    <form method="post" action="index.php?ctl=perfil">
                                                        <input type="hidden" value="<?= $publicacion['id'] ?>" name="perfilUsuario">
                                                        <h4 class="media-heading" onclick="$(this).parent().submit()" style="color: #33cbad;cursor:pointer;"><?= $publicacion['nombre'] . " " . $publicacion['apellidos'] ?></h4>
                                                    </form>
                                                    <?php if ($esSuEstado) : ?>
                                                        <i class="fas fa-times text-danger borrarPublicacion" style="cursor:pointer" id="<?= $publicacion['idEstado'] ?>" title="Borrar publicación"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <p><?= $publicacion['estadoCuerpo'] ?></p>
                                                <!-- Botón de responder -->
                                                <p class="d-flex justify-content-between align-items-center">
                                                    <button class="btn btn-xs mr-3" style="background: #33cbad; color:white;" type="button" data-toggle="collapse" data-target="#collapseForm<?= $publicacion['idEstado'] ?>" aria-expanded="false" aria-controls="collapseForm">Ver comentarios</button>
                                                    <small class="text-muted">
                                                        <?= $c->formatearFecha($publicacion['fecha']);
                                                        ?>
                                                    </small>
                                                </p>
                                                <!-- Este es el cajón de respuesta -->
                                                <section class="collapse" id="collapseForm<?= $publicacion['idEstado'] ?>">
                                                    <form method="post" onsubmit="return false;">
                                                        <input type="text" name="comentarioNuevo" autocomplete="off" class="form-control comentarioNuevo" placeholder="Escribe tu comentario..."></textarea>
                                                        <input type="hidden" class="idEstado" value="<?= $publicacion['idEstado'] ?>">
                                                        <input type="hidden" class="idUsuarioDirigido" value="<?= $publicacion['id'] ?>">
                                                        <p id="btn-form-answer">
                                                            <button type="button" style="background: #93ECFF; color:white;" class="btn btn-xs mt-2 form-control botonComentar">Comentar <i class="far fa-comment"></i></button>
                                                        </p>
                                                    </form>
                                                    <!-- Aqui va la respuesta -->
                                                    <?php
                                                    $idEspacio = $publicacion['idEstado'];
                                                    $listaComentarios = $m->findComentarioByIdEspacio($idEspacio);
                                                    ?>
                                                    <section style="max-height:150px !important;overflow: scroll;overflow-y: auto;overflow-x: hidden;">
                                                        <?php foreach ($listaComentarios as $comentario) : ?>
                                                            <?php $esSuComentario = $m->isSuComentario($comentario['idComentario'], $params['idUsuario']); ?>
                                                            <div class="media mt-2">
                                                                <div class="media-left">
                                                                    <img src="images/<?= $comentario['fotoPerfil'] ?>" width="60" height="60" alt="Foto perfil" class="media-object rounded-circle mr-2 mt-1 border">
                                                                </div>
                                                                <div class="media-body">
                                                                    <form method="post" action="index.php?ctl=perfil">
                                                                        <input type="hidden" value="<?= $comentario['id'] ?>" name="perfilUsuario">
                                                                        <h4 class="media-heading" onclick="$(this).parent().submit()" style="cursor:pointer;"><?= $comentario['nombre'] . " " . $comentario['apellidos'] ?>
                                                                    </form>
                                                                    <small style="font-size: 0.8rem" class="text-muted">
                                                                        <?= $c->formatearFecha($comentario['fecha_comentario']) ?>
                                                                    </small></h4>
                                                                    <div class="d-flex justify-content-between">
                                                                        <p><?= $comentario['textoComentario'] ?>.</p>
                                                                        <?php if ($esSuComentario) : ?>
                                                                            <small id="<?= $publicacion['idEstado'] ?>"><i class="fas fa-times text-secondary borrarComentario" style="cursor:pointer" id="<?= $comentario['idComentario'] ?>" title="Borrar comentario"></i></small>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                        <div class="contenedorNuevosComentarios d-none"></div>
                                                    </section>
                                                </section>
                                            </div>
                                        </div>
                                    </section>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php if (!$m->isAmigoPerfil($params['idUsuario'], $perfilUsuarioEstado['id'])) : ?>
                                <h5 class="text-muted bg-light rounded p-4 border mt-5">Hazte amigo de este usuario para poder ver sus publicaciones</h5>
                            <?php endif; ?>
                            <!-- Fin comentarios -->
                        </div>
                    </div>
                </div>
                <!-- Fin de tablón -->
            </div>
        </div>
        <!-- Fin parte central -->


        <!-- Comienzo parte derecha -->
        <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
            <div class="row">
                <div class="col-12 text-center">
                    <form method="post" action="index.php?ctl=galeria">
                        <input type="hidden" name="idGaleria" value="<?= $params['idUsuarioPerfil'] ?>">
                        <h4 style="cursor:pointer" onclick="$(this).parent().submit()">Fotos de <?= $params['nombreUsuario'] ?>(<?= $params['contadorFotosUsuario'] ?>)</h4>
                    </form>
                    <hr>
                    </a>
                </div>
                <!-- Apartado fotos derecha -->
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="row d-flex justify-content-center">
                        <?php foreach ($params['listaFotosLimit'] as $foto) : ?>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" style="border:1px">
                                <form method="post" action="index.php?ctl=galeria">
                                    <input type="hidden" name="idGaleria" value="<?= $params['idUsuarioPerfil'] ?>">
                                    <img onclick="$(this).parent().submit()" src="fotosUsuarios/<?= $foto['rutaFoto'] ?>" style="cursor:pointer" class="img-fluid border border-info rounded mb-1">
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php if ($params['idUsuario'] == $params['idUsuarioPerfil']) : ?>
                    <div class="col-12 text-center mt-3">
                        <form method="post" action="index.php?ctl=perfil" enctype="multipart/form-data">
                            <input type="file" accept="image/png, .jpeg, .jpg, image/gif" id="fotoSubir" name="fotoSubir" class="btn btn-sm btn-info" title="Cambiar foto de perfil" style="display: none" />
                            <label class="btn btn-sm text-white btn-block" style="background: #33cbad" for="fotoSubir"><i class="fas fa-upload"></i>&nbsp; Subir foto</label>
                            <input type="text" class="form-control form-control-sm" name="tituloFoto" placeholder="Título de foto">
                        </form>
                    </div>
                    <?php if ($params['mensajeFoto']) : ?>
                        <div class="statusMessage justify-content-center align-items-center col-12 mt-1">
                            <div class='row'>
                                <div class='alert alert-success col-12' role='alert'>
                                    <span><?= $params['mensajeFoto'] ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <!--Fin apartado fotos derecha -->
            </div>
        </div>

    </div>
</div>

<?php $contenido = ob_get_clean() ?>

<head>
    <title>WhoMeet - Perfil de usuario </title>
</head>
<?php include 'layout.php' ?>