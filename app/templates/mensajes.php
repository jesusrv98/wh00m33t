<?php
ob_start();
$m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);
$c = new Controller();
?>

<head>
    <link rel="stylesheet" href="css/mensajes.css">
</head>
<style>
    .inbox-chat {
        height: 100%;
    }
    form{
        margin-bottom: 1;
    }
</style>
<script src="js/jqueryGoogle.js"></script>

<!-- MENSAJES -->
<div class="container-fluid">
    <div class="messaging elementoHeight">
        <div class="inbox_msg">
            <section class="row">
                <div class="inbox_people col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="headind_srch">
                        <div class="recent_heading">
                            <h4>Chats</h4>
                        </div>
                        <div class="srch_bar">
                            <div class="stylish-input-group">
                                <input type="text" class="search-bar" placeholder="Buscar">
                                <span class="input-group-addon">
                                    <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                                </span> </div>
                        </div>
                    </div>
                    <div class="inbox_chat">
                        <?php foreach ($params['listaUsuariosMensajes'] as $usuarioMensaje) : ?>
                            <?php if (sizeof($params['listaUsuariosMensajes']) > 0) : ?>
                                <?php if (implode(array_column($m->countMensajesSinVerPantallaMensajesGeneral($params['idUsuario'], $usuarioMensaje['id']), "contador")) > 0) : ?>
                                    <form action="index.php?ctl=conversacion" method="POST">
                                        <input type="hidden" name="idUsuarioConversacion" value="<?= $usuarioMensaje['id'] ?>">
                                        <div onclick="$(this).parent().submit()" class="chat_list active_chat border border-success rounded" style="cursor:pointer">
                                            <div class="chat_people">
                                                <div class="chat_img"> <img src="images/<?= $usuarioMensaje['fotoPerfil'] ?>" class="media-object rounded-circle mr-2 mt-1 border" width="90" height="84" alt="foto_perfil"> </div>
                                                <div class="chat_ib">
                                                    <div class="d-flex justify-content-between">
                                                        <section>
                                                            <h5><?= $usuarioMensaje['nombre'] . " " . $usuarioMensaje['apellidos'] ?></h5>
                                                            <p><?= $usuarioMensaje['cuerpoMensaje'] ?></p>
                                                        </section>
                                                        <section>
                                                            <label class="text-muted"><?= $c->formatearFecha($usuarioMensaje['fechaMensaje']) ?></label>
                                                        </section>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end align-items-center col-12">
                                                    <button type="button" style="max-width: 50px;max-height: 50px;" class="btn btn-success">
                                                        <span class="badge badge-light"><?= implode(array_column($m->countMensajesSinVerPantallaMensajesGeneral($params['idUsuario'], $usuarioMensaje['id']), "contador")) ?></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                <?php endif; ?>
                                <?php if (implode(array_column($m->countMensajesSinVerPantallaMensajesGeneral($params['idUsuario'], $usuarioMensaje['id']), "contador")) == 0) : ?>
                                    <form action="index.php?ctl=conversacion" method="POST">
                                        <input type="hidden" name="idUsuarioConversacion" value="<?= $usuarioMensaje['id'] ?>">
                                        <div onclick="$(this).parent().submit()" class="chat_list border rounded" style="border-color: none !important; cursor:pointer">
                                            <div class="chat_people">
                                                <div class="chat_img"> <img src="images/<?= $usuarioMensaje['fotoPerfil'] ?>" class="media-object rounded-circle mr-2 mt-1 border" width="90" height="84" alt="foto_perfil"> </div>
                                                <div class="chat_ib">
                                                    <div class="d-flex justify-content-between">
                                                        <section>
                                                            <h5><?= $usuarioMensaje['nombre'] . " " . $usuarioMensaje['apellidos'] ?></h5>
                                                            <p><?= $usuarioMensaje['cuerpoMensaje'] ?></p>
                                                        </section>
                                                        <section>
                                                            <label class="text-muted"><?= $c->formatearFecha($usuarioMensaje['fechaMensaje']) ?></label>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (sizeof($params['listaUsuariosMensajes']) < 1) : ?>
                            <section class="text-center mt-5 pt-5">
                                <h5 class="text-muted">No tienes conversaciones aún.</h5>
                            </section>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- FIN DE MENSAJES -->

<script>
    window.onresize = function() {
        autoResizeMensajes();
    }

    window.onload = function() {
        autoResizeMensajes();
    }

    $(document).ready(function() {
        autoResizeMensajes();
        $(window).resize(function() {
            autoResizeMensajes();
        });
    });

    function autoResizeMensajes() {
        var altoPantalla = window.innerHeight;
        var elemento = $('.elementoHeight');
        var alturaElemento = elemento.height();
        var resto = altoPantalla - alturaElemento;
        elemento.css("height", altoPantalla - resto - 20 + "px");
        elemento.css("max-height", altoPantalla - resto - 20 + "px");
    }
</script>

<?php $contenido = ob_get_clean() ?>

<head>
    <title>WhoMeet - Mensajes </title>
</head>
<?php include 'layout.php' ?>