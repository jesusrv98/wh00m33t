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
                        <a href="#" data-toggle="modal" data-target="#exampleModal">
                            <?php foreach ($params['listaUsuariosMensajes'] as $usuarioMensaje) : ?>
                                <div class="chat_list active_chat border border-success rounded">
                                    <div class="chat_people">
                                        <div class="chat_img"> <img src="images/<?= $usuarioMensaje['fotoPerfil'] ?>" class="rounded-circle" alt="foto_perfil"> </div>
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
                                                <span class="badge badge-light">2</span>
                                                <span class="sr-only">unread messages</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- FIN DE MENSAJES -->

<!-- MODALES -->
<div class="modal" id="exampleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="width: 100%;" role="document">
        <div class="modal-content" style="width: 100%;">
            <div class="modal-header">
                <div class="d-flex align-items-center container-fluid row">
                    <div class="incoming_msg_img col-4 col-sm-4 col-md-3 col-lg-2 col-xl-2"> <img src="../images/avatar.jpg" alt="foto_perfil" class="rounded-circle img-thumbnail img-fluid"> </div>
                    <h5 class="modal-title col-7 col-sm-7 col-md-8 col-lg-9 col-xl-9">Josema Delgado Peña</h5>
                    <button type="button" class="close col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <!-- CONTENIDO DE MODAL -->
                <div class="mesgs">
                    <div class="msg_history">
                        <div class="incoming_msg">
                            <div class="received_msg">
                                <div class="received_withd_msg">
                                    <p>Hola tío, qué pasa</p>
                                    <span class="time_date"> 11:01 | Enero 21</span>
                                </div>
                            </div>
                        </div>
                        <div class="outgoing_msg">
                            <div class="sent_msg">
                                <p>Aquí estoy haciendo cosas tela de liao, háblame mañana mejor</p>
                                <span class="time_date"> 13:46 | Enero 21</span>
                            </div>
                        </div>
                        <div class="incoming_msg">
                            <div class="received_msg">
                                <div class="received_withd_msg">
                                    <p>Illo, puedes hablar ya o qué?</p>
                                    <span class="time_date"> 09:30 | Ayer</span>
                                </div>
                            </div>
                        </div>
                        <div class="outgoing_msg">
                            <div class="sent_msg">
                                <p>Illo que escuchame que no pude ayer, qué quieres</p>
                                <span class="time_date"> 21:05 | Hoy</span>
                            </div>
                        </div>
                        <div class="incoming_msg">
                            <div class="received_msg">
                                <div class="received_withd_msg">
                                    <p>Illo me he dado cuenta de que no funcionan los modales con Ayax, eso era coño, que llevo tres putos días para decírtelo jajaja 😂😂.</p>
                                    <span class="time_date"> 14:32 | Hoy</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN DE CONTENIDO DE MODAL -->
            </div>
            <div class="modal-footer">
                <div class="type_msg">
                    <div class="input_msg_write">
                        <input type="text" class="write_msg" placeholder="Escribe tu mensaje..." />
                        <button class="msg_send_btn" type="button"><i class="far fa-paper-plane" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- FIN MODALES -->
<script>
    window.onresize = function() {
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
    <title>WhoMeet - Busqueda </title>
</head>
<?php include 'layout.php' ?>