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
</style>
<script src="js/jqueryGoogle.js"></script>

<!-- MENSAJES -->
<div class="container-fluid elementoHeight">
    <div class="modal-content" style="width: 100%;">
        <div class="modal-header">
            <div class="d-flex align-items-center container-fluid row">
                <div class="incoming_msg_img col-4 col-sm-4 col-md-3 col-lg-2 col-xl-2"> <img src="images/<?= $params['fotoPerfilOtro'] ?>" alt="foto_perfil" width="60" height="60" class="media-object rounded-circle mr-2 mt-1 border"> </div>
                <h5 class="modal-title col-7 col-sm-7 col-md-8 col-lg-9 col-xl-9"><?= $params['nombreOtro'] . " " . $params['apellidosOtro'] ?></h5>
            </div>
        </div>
        <div class="modal-body">
            <!-- CONTENIDO DE MODAL -->
            <div class="mesgs">
                <div class="msg_history" id="msg_history">
                    <?php foreach ($params['listaMensajes'] as $mensaje) : ?>
                        <?php if ($mensaje['idUsuarioRecibe'] == $params['idUsuario']) : ?>
                            <div class="received_msg message">
                                <div class="received_withd_msg">
                                    <p><?= $mensaje['cuerpoMensaje'] ?></p>
                                    <span class="time_date"> <?= $c->horaMensajes($mensaje['fechaMensaje']) ?> | <?= $c->fechaMensajes($mensaje['fechaMensaje']) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($mensaje['idUsuarioRecibe'] != $params['idUsuario']) : ?>
                            <div class="outgoing_msg message">
                                <div class="sent_msg">
                                    <p><?= $mensaje['cuerpoMensaje'] ?></p>
                                    <span class="time_date"> <?= $c->horaMensajes($mensaje['fechaMensaje']) ?> | <?= $c->fechaMensajes($mensaje['fechaMensaje']) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <div class="contenedorNuevoMensaje d-none">

                    </div>
                </div>
                <!-- FIN DE CONTENIDO DE MODAL -->
            </div>
            <div class="modal-footer">
                <div class="type_msg">
                    <div class="input_msg_write">
                        <section style="width:95%">
                            <input type="text" id="mensajeNuevo" class="write_msg" placeholder="Escribe tu mensaje..." />
                        </section>
                        <section class="d-flex justify-content-center" style="width:5%;">
                            <button class="msg_send_btn" id="botonEnviarMensaje" type="button"><i class="far fa-paper-plane" id="<?= $params['idUsuarioConversacion'] ?>" aria-hidden="true"></i></button>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.onresize = function() {
        autoResizeConversacion();
    }

    window.onload = function() {
        autoResizeConversacion();
        scrollToBottom();
    }

    $(document).ready(function() {
        autoResizeConversacion();
        scrollToBottom();
        $(window).resize(function() {
            autoResizeConversacion();
        });
        $("#botonEnviarMensaje").click(function() {
            var mensajeNuevo = $("#mensajeNuevo").val();
            var idUsuarioRecibe = $(this).find("i").attr("id");
            var fechaHoy = new Date();


            if (mensajeNuevo.trim() == '') {
                alert('No se puede enviar un mensaje en blanco.');
                $(this).focus();
                return false;
            } else {
                var parametros = {
                    'mensajeNuevo': mensajeNuevo,
                    'idUsuarioEnvia': <?php echo $params['idUsuario'] ?>,
                    'idUsuarioRecibe': idUsuarioRecibe
                };
                $.ajax({
                    data: parametros,
                    url: '../app/templates/includes/servletEnviarMensajes.php',
                    type: 'post',
                    async: true,
                    success: function(msg) {
                        if (msg == 'ok') {

                            $('.contenedorNuevoMensaje').removeClass('d-none');
                            $('.contenedorNuevoMensaje').append("<div class='outgoing_msg message'><div class='sent_msg'><p>"+mensajeNuevo+"</p><span class='time_date> <?= $c->horaMensajes($mensaje['fechaMensaje']) ?> | Hoy</span></div></div>");

                        } else {
                            alert(msg);
                        }
                    }
                });
            }
        });
    });

    function autoResizeConversacion() {
        var altoPantalla = window.innerHeight;
        var elemento = $('.msg_history');
        var distanciaTopElemento = elemento.offset().top;
        var resto = altoPantalla - distanciaTopElemento;
        var pie = $('.modal-footer');
        var distanciaTopPie = pie.height();
        elemento.css("height", resto - distanciaTopPie - 60 + "px");
        elemento.css("max-height", resto - distanciaTopPie - 60 + "px")
    }


    const messages = document.getElementById('msg_history');

    function scrollToBottom() {
        messages.scrollTop = messages.scrollHeight;
    }
</script>

<?php $contenido = ob_get_clean() ?>

<head>
    <title>WhoMeet - Conversación </title>
</head>
<?php include 'layout.php' ?>