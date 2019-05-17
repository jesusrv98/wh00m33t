<?php
ob_start();
$c = new Controller();
?>
<!--Parte izquierda -->
<script src="js/jqueryGoogle.js"></script>
<script>
    $(document).ready(function() {
        $("#botonEstado").click(function() {
            var estadoNuevo = $("#estadoNuevo").val();
            var estadoViejo = $("#estadoViejo").val();


            if (estadoNuevo.trim() == '') {
                alert('No puede dejar su estado en blanco.');
                $(this).focus();
                return false;
            } else {
                var parametros = {
                    'estadoNuevo': estadoNuevo,
                    'estadoViejo': estadoViejo,
                    'idUsuario': <?php echo $params['idUsuario'] ?>
                };
                $.ajax({
                    data: parametros,
                    url: '../app/templates/includes/servletActualizarEstado.php',
                    type: 'post',
                    async: true,
                    success: function(msg) {
                        if (msg == 'ok') {

                            $("#estadoNuevo").val('');
                            $("#estadoViejo").text(estadoNuevo);
                            $("#fechaCambiar").text('Hace un instante')

                            $('.statusMessage').removeClass('d-none');
                            $('.statusMessage').addClass('d-block');
                            $('.statusMessage').html("<div class='alert alert-success' role='alert' >Estado actualizado</div>");

                            $('.contenedorNuevosEstados').removeClass('d-none');
                            $('.contenedorNuevosEstados').prepend("<div class='container border rounded bg-light'>"+estadoNuevo+"</div>");
                            
                        } else {
                            $('.statusMessage').removeClass('d-none');
                            $('.statusMessage').addClass('d-block');
                            $('.statusMessage').html("<div class='alert alert-danger' role='alert' >Tu nuevo estado no puede ser el mismo que el anterior.</div>");
                            alert(msg);
                        }
                    },
                    error: function() {
                        $('.statusMessage').removeClass('d-none');
                        $('.statusMessage').addClass('d-block');
                        $('.statusMessage').html("<div class='row'><div class='alert alert-danger col-12'  role='alert' >Ha habido un error.</div></div>");
                    }

                });
            }
        });
        $('.botonComentar').click( function() {
            var formulario = $(this).parent().parent();
            var comentarioNuevo = $(formulario.find(".comentarioNuevo")).val();
            var idEstado = $(formulario.find('.idEstado')).val();
            var idUsuarioDirigido = $(formulario.find('.idUsuarioDirigido')).val();
            var fotoPerfil = "<?php echo implode(array_column($_SESSION['usuarioconectado'], 'fotoPerfil')) ?>";
            var nombre = "<?php echo implode(array_column($_SESSION['usuarioconectado'], 'nombre')) ?>";
            var apellido = "<?php echo implode(array_column($_SESSION['usuarioconectado'], 'apellidos')) ?>";
            var nombreUsuario = nombre+" "+apellido;

            if (comentarioNuevo.trim() == '') {
                alert('No puede comentar en blanco.');
                $(this).focus();
                return false;
            } else {
                var parametros = {
                    'textoComentario': comentarioNuevo,
                    'idUsuario': <?php echo $params['idUsuario'] ?>,
                    'idEspacio': idEstado,
                    'tipo': 'comentario',
                    'idUsuarioDirigido' : idUsuarioDirigido
                };
                $.ajax({
                    data: parametros,
                    url: '../app/templates/includes/servletComentar.php',
                    type: 'post',
                    async: true,
                    success: function(msg) {
                        if (msg == 'ok') {
                            $(formulario.parent().find('.contenedorNuevosComentarios')).removeClass('d-none');
                            $(formulario.parent().find('.contenedorNuevosComentarios')).append("<div class='media mt-2'><div class='media-left'><img src='images/"+fotoPerfil+"' width='60' height='60' alt='Foto perfil' class='media-object rounded-circle mr-2 mt-1'></div><div class='media-body'><h4 class='media-heading'>"+nombreUsuario+"<small style='font-size: 0.8rem' class='text-muted'> Hace un momento</small></h4><p>"+comentarioNuevo+".</p></div></div>");
                            $(formulario.find(".comentarioNuevo")).val('');
                        } else {
                           alert("mal comentado: "+msg);
                        }
                    },
                    error: function() {
                        alert("Ha ocurrido un error y no se puede comentar.");

                    }

                });
            }
        });
    });
</script>
<div class="container-fluid">
    <div class="row">
        <div class="container col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 mt-2" style="border-right: 1px solid #33cbad; height: auto">
            <!-- Resumen perfil -->
            <div class="row">
                <div class="col-12" style="margin-top: 1em">
                    <div class="row">
                        <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4 ">
                            <img class="img-thumbnail img-fluid" src="images/<?= implode(array_column($_SESSION['usuarioconectado'], 'fotoPerfil')); ?>" />
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                            <div class="row">
                                <div class="col-12">
                                    <p style="color: #33cbad"><?= implode(array_column($_SESSION['usuarioconectado'], 'nombre')) . " " . implode(array_column($_SESSION['usuarioconectado'], 'apellidos')); ?></p>
                                </div>
                                <div class="col-12">
                                    <p class="text-muted"><i class="fas fa-chart-bar"></i> <?= $params['visitas'] ?> visitas a tu perfil.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-lg-12 col-md-12 col-xl-12">
                            <hr>
                            <?php
                                if ($params['existeNotificaciones']) {
                                    if ($params['countMensajesPV']) {
                                        if ($params['countMensajesPV'] == 1) {
                                            echo "<p style='color:#77bf5c;font-weight:inherit;'><i class='fas fa-envelope'></i> Tienes " . $params['countMensajesPV'] . " mensaje privado nuevo.</p>";
                                        } else {
                                            echo "<p style='color:#77bf5c;font-weight:inherit;'><i class='fas fa-user-friends'></i> Tienes " . $params['countMensajesPV'] . " mensajes privados nuevos.</p>";
                                        }
                                    }
                                    if ($params['countPeticiones']) {
                                        if ($params['countPeticiones'] == 1) {
                                            echo "<p style='color:#77bf5c;font-weight:inherit;'><i class='fas fa-user-friends'></i> Tienes " . $params['countPeticiones'] . " solicitud de amistad nueva.</p>";
                                        } else {
                                            echo "<p style='color:#77bf5c;font-weight:inherit;'><i class='fas fa-user-friends'></i> Tienes " . $params['countPeticiones'] . " solicitudes de amistades nuevas.</p>";
                                        }
                                    }
                                    if ($params['countComentarios']) {
                                        if ($params['countComentarios'] == 1) {
                                            echo "<p style='color:#77bf5c;font-weight:inherit;'><i class='fas fa-comment'></i> Tienes " . $params['countComentarios'] . " comentario nuevo.</p>";
                                        } else {
                                            echo "<p style='color:#77bf5c;font-weight:inherit;'><i class='fas fa-comment'></i> Tienes " . $params['countComentarios'] . " comentarios nuevos.</p>";
                                        }
                                    }
                                    if ($params['countComentariosFotos']) {
                                        if ($params['countComentariosFotos'] == 1) {
                                            echo "<p style='color:#77bf5c;font-weight:inherit;'><i class='fas fa-comment-dots'></i> Tienes " . $params['countComentariosFotos'] . " comentario en foto nuevo.</p>";
                                        } else {
                                            echo "<p style='color:#77bf5c;font-weight:inherit;'><i class='fas fa-comment-dots'></i> Tienes " . $params['countComentariosFotos'] . " comentarios en fotos nuevos.</p>";
                                        }
                                    }
                                } else {
                                    echo "<h4 class='text-muted'>No tienes notificaciones.</h4>";
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin parte izquierda -->
        <!-- Comienzo centro -->
        <div class="container col-12 col-sm-12 col-md-12 col-lg-5 col-xl-5 mt-2" style="height: auto">
            <div class="row">
                <div class="col-12 mt-3">
                    <form method="post" name="formEstado" onsubmit="return false;$('#botonEstado').click();">
                        <input class="form-control" id="estadoNuevo" style="border-radius: 1em" type="text" value="<?= $params['nuevoEstado'] ?>" name="nuevapubli" placeholder="Nuevo estado..." autocomplete="off"/>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                    <p style="font-size:13px; margin-left: 0.5em; margin-top: 1em" class="text-muted"><b>Última actualización:</b>
                        <?= "<span id='estadoViejo'>".$params['estadoActual']."</span>"; ?>
                        <?php
                            if ($params['estadoActualFecha']) {
                                echo "<small class='font-italic' id='fechaCambiar'>Hace " . $params['estadoActualFecha']."</small>";
                            }
                        ?>
                    </p>
                </div>

                <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                    <button type="button" id="botonEstado" style="margin-top: 0.8em;border: 0px;border-radius: 1em;background: #e6fbff;width: auto; color: #33cbad" class="btn"> Guardar </button>
                </div>
                </form>
                <div class="statusMessage justify-content-center align-items-center d-none col-12 mt-1"></div>
                <!--Comienzo novedades-->
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
                    <span style="color:#509184;font-size: 16px">Novedades</span>
                </div>
                <div class="col-12 mt-1">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" style="color: #33cbad" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Amigos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" style="color: #33cbad" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Eventos</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <!-- Pestaña amigos -->
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <!-- AQUÍ FOREACH PARA CADA PUBLIC -->
                            <div class="contenedorNuevosEstados d-none"></div>   
                            <?php foreach ($params['publicacionesAmigos'] as $publicacion) : ?>
                                <section style="border-bottom: 1px solid #33cbad;" class="container p-2 mt-2">
                                    <div class="media">
                                        <div class="media-left">
                                            <img src="images/<?= $publicacion['fotoPerfil'] ?>" width="64" height="64" alt="Foto perfil" class="media-object rounded-circle mr-2 mt-1">
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading" style="color: #33cbad;"><?= $publicacion['nombre'] . " " . $publicacion['apellidos'] ?></h4>

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
                                                    <input type="text" name="comentarioNuevo" class="form-control comentarioNuevo" placeholder="Escribe tu comentario..."></textarea>
                                                    <input type="hidden" class="idEstado" value="<?= $publicacion['idEstado'] ?>">
                                                    <input type="hidden" class="idUsuarioDirigido" value="<?= $publicacion['id'] ?>">
                                                    <p id="btn-form-answer">
                                                        <button type="button" style="background: #93ECFF; color:white;" class="btn btn-xs mt-2 form-control botonComentar">Comentar <i class="far fa-comment"></i></button>
                                                    </p>
                                                </form>
                                                <!-- Aqui va la respuesta -->
                                                <?php
                                                    $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);
                                                    $idEspacio = $publicacion['idEstado'];
                                                    $listaComentarios = $m->findComentarioByIdEspacio($idEspacio);
                                                ?>
                                                <section style="max-height:150px !important;overflow: scroll;overflow-y: auto;overflow-x: hidden;">
                                                    <?php foreach ($listaComentarios as $comentario) : ?>
                                                        <div class="media mt-2">
                                                            <div class="media-left">
                                                                <img src="images/<?= $comentario['fotoPerfil'] ?>" width="60" height="60" alt="Foto perfil" class="media-object rounded-circle mr-2 mt-1">
                                                            </div>
                                                            <div class="media-body">
                                                                <h4 class="media-heading"><?= $comentario['nombre'] . " " . $comentario['apellidos'] ?>
                                                                    <small style="font-size: 0.8rem" class="text-muted">
                                                                        <?= $c->formatearFecha($comentario['fecha_comentario']) ?>
                                                                    </small></h4>
                                                                <p><?= $comentario['textoComentario'] ?>.</p>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                    <div class="contenedorNuevosComentarios d-none"></div>
                                                </section>
                                            </section>
                                        </div>
                                    </div>
                                </section>
                            <?php endforeach; ?>
                            <!--  -->
                            <!--  -->

                            <!-- Fin publicación -->
                        </div>
                        <!-- Fin pestaña amigos -->
                        <!-- Pestaña sitios -->
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <!-- sitio -->
                            
                            <!-- Fin sitio -->
                            <!-- Segundo sitio -->
                        </div><!-- Fin pestaña sitios -->
                    </div> <!-- Fin tab-content -->
                </div>
            </div><!-- Final row centro -->
        </div><!-- Final parte centro -->

        <!-- Comienzo parte derecha -->
        <div class="container col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 mt-2" style="border-left: 1px solid #33cbad; height: auto">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <span>Invita a tus amigos</span>
                    <hr>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="row">
                        <div class="col-8">
                            <form action="index.php" method="POST">
                                <input class="form-control" type="text" name="invitausuario" placeholder="E-mail..." required />
                        </div>
                        <div class="col-4">
                            <input type="submit" value="Invitar" class="btn btn-warning">
                            </form>
                        </div>
                        <div class="col-12">
                            <p class="text-muted small">¡Invita a tus amigos a nuestra red social y empieza a interactuar con ellos desde ya!</p>
                        </div>
                        <!-- Inicio chat -->
                        <div class="col-12 border rounded pb-2 ml-1">
                            <div class="row">
                                <div class="col-12 pb- pt-1">
                                    <h6>Usuarios conectados (<?= $params['countUsuariosConectados'] ?>) <i class="fas fa-circle" style="color:#77bf5c;"></i></h6>
                                </div>
                                <?php foreach ($params['listaUsuariosConectados'] as $usuarioConectado) : ?>
                                    <div class="col-12">
                                        <i class="fas fa-circle" style="color:#77bf5c;font-size: 11px;"></i> <?= $usuarioConectado['nombre'] . " " . $usuarioConectado['apellidos'] ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <!-- Fin chat -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Pie de chat -->
        <!-- <div class="container-fluid fixed-bottom">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
                    <button class="btn btn-primary" type="button">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Conectando con el chat
                    </button>
                </div>
            </div>
        </div> -->
        <!-- Final de pie de chat -->
        <!-- Final parte derecha -->
    </div>
</div>

<!-- MODAL CONTACTA -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" id="modalContacta" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- CUERPO MODAL CONTACTA -->
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="width: 100%;">
            <div class="modal-content">
                <form action="#" onsubmit="return false;" method="post">
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
                                    <textarea class="form-control" placeholder="Envianos tu Mensaje" required></textarea>
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
<!-- FIN MODAL CONTACTA -->


<!-- FIN VISTA COMPLETA -->


<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>