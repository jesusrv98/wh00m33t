<?php
ob_start();
$c = new Controller();
$m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);
?>

<script type="text/javascript" src="js/jqueryGoogle.js"></script>
<script>
    $(document).ready( function() {
        $(".borrarPublicacion").click(function() {
            if (confirm("¿Desea borrar su publicación?")) {
                var boton = $(this);
                var idEstado = boton.attr("id");
                var idUsuario = boton.parent().attr("id");

                var parametros = {
                        'idUsuario': idUsuario,
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
                            boton.parent().parent().parent().parent().css("display","none");
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
                var idUsuario = boton.parent().parent().attr("id");

                var parametros = {
                        'idUsuario': idUsuario,
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
                            boton.parent().parent().parent().parent().css("display","none");
                        } else {
                            alert(msg);
                        }
                    }
                });
            }
        });
    });
</script>
<style>
    .page-item.active .page-link {
        background: #33cbad;
        border-color: inherit;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <form method="POST" action="index.php?ctl=gestionContenido">
                <div class="form-row">
                    <div class="col-8">
                        <input type="text" class="form-control form-control-sm" autocomplete="off" name="publicacionBuscada" placeholder="Nombre del usuario..." value="<?= $params['publicacionBuscada'] ?>">
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-info btn-block"> <i class="fas fa-search text-white"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12">
            <?php foreach ($params['publicacionesUsuarios'] as $publicacion) : ?>
                <section style="border-bottom: 1px solid #33cbad;" class=" p-2 mt-2">
                    <div class="media">
                        <div class="media-left">
                            <img src="images/<?= $publicacion['fotoPerfil'] ?>" width="69" height="64" alt="Foto perfil" class="media-object rounded-circle mr-2 mt-1 border">
                        </div>
                        <div class="media-body">
                            <div class="d-flex justify-content-between" id=<?= $publicacion['id'] ?>>
                                <h4 class="media-heading" style="color: #33cbad;"><?= $publicacion['nombre'] . " " . $publicacion['apellidos'] ?></h4>
                                <i class="fas fa-times text-danger borrarPublicacion" style="cursor:pointer" id="<?= $publicacion['idEstado'] ?>" title="Borrar publicación"></i>
                            </div>
                            <p><?= $publicacion['estadoCuerpo'] ?></p>
                            <!-- Botón de responder -->
                            <p class="d-flex justify-content-between align-items-center">
                                <button class="btn btn-xs mr-3" style="background: #33cbad; color:white;" type="button" data-toggle="collapse" data-target="#collapseForm<?= $publicacion['idEstado'] ?>" aria-expanded="false" aria-controls="collapseForm">Ver comentarios</button>
                                <small class="text-muted">
                                    <?= $c->formatearFecha($publicacion['fecha']); ?>
                                </small>
                            </p>
                            <!-- Este es el cajón de respuesta -->
                            <section class="collapse" id="collapseForm<?= $publicacion['idEstado'] ?>">
                                <form method="post" onsubmit="return false;">
                                    <input type="text" name="comentarioNuevo" autocomplete="off" disabled class="form-control comentarioNuevo" placeholder="Escribe tu comentario..."></textarea>
                                    <p id="btn-form-answer">
                                        <button type="button" style="background: #93ECFF; color:white;" disabled class="btn btn-xs mt-2 form-control botonComentar">Comentar <i class="far fa-comment"></i></button>
                                    </p>
                                </form>
                                <!-- Aqui va la respuesta -->
                                <?php
                                $idEspacio = $publicacion['idEstado'];
                                $listaComentarios = $m->findComentarioByIdEspacio($idEspacio);
                                ?>
                                <section style="max-height:150px !important;overflow: scroll;overflow-y: auto;overflow-x: hidden;">
                                    <?php foreach ($listaComentarios as $comentario) : ?>
                                        <div class="media mt-2">
                                            <div class="media-left">
                                                <img src="images/<?= $comentario['fotoPerfil'] ?>" width="60" height="60" alt="Foto perfil" class="media-object rounded-circle mr-2 mt-1 border">
                                            </div>
                                            <div class="media-body">
                                                <h4 class="media-heading"><?= $comentario['nombre'] . " " . $comentario['apellidos'] ?>
                                                    <small style="font-size: 0.8rem" class="text-muted">
                                                        <?= $c->formatearFecha($comentario['fecha_comentario']) ?>
                                                    </small></h4>
                                                <div id="<?= $comentario['id'] ?>" class="d-flex justify-content-between">
                                                    <p><?= $comentario['textoComentario'] ?>.</p>
                                                    <small id="<?= $publicacion['idEstado'] ?>"><i class="fas fa-times text-secondary borrarComentario" style="cursor:pointer" id="<?= $comentario['idComentario'] ?>" title="Borrar comentario"></i></small>
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
            <?php endforeach; ?>
            <!--  -->
            <!--  -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center mt-2">
                    <?php
                    for ($i = 1; $i <= $params['totalPaginas']; $i++) :
                        if ($params['pagina'] == $i) {
                            echo "<li class='page-item active'><a class='page-link' href='index.php?ctl=gestionContenido&pagina=" . $i . "'>" . $i . "</a></li>";
                        } else {
                            echo "<li class='page-item'><a class='page-link' style='color: #33cbad;' href='index.php?ctl=gestionContenido&pagina=" . $i . "'>" . $i . "</a></li>";
                        }
                    endfor;
                    ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php $contenido = ob_get_clean() ?>

<head>
    <title>WhoMeet - Gestión de Contenido </title>
</head>
<?php include 'layout.php' ?>