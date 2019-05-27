<?php
    ob_start();
    $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);
    $c = new Controller();
?>
<script src="js/jqueryGoogle.js"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
        <div class="alert alert-success" role="alert">
            <?php 
                if($params['countComentariosEstados']>1 ) {
                    echo " <h2><i class='fas fa-user-plus'></i> Tienes ".$params['countComentariosEstados']." comentarios nuevos en estados sin ver.</h2>";
                }else if($params['countComentariosEstados'] == 1) {
                    echo " <h2><i class='fas fa-user-plus'></i> Tienes ".$params['countComentariosEstados']." comentario nuevo en algún estado sin ver.</h2>";
                }else{
                    echo " <h2><i class='fas fa-user-plus'></i> No tienes ningún comentario nuevo en ningun estado sin ver.</h2>";
                }
            ?>
        </div>
        </div>
        <div class="col-12">
        <?php foreach ($params['publicaciones'] as $publicacion) : ?>
            <?php
            $tieneSolicitud = $m->tieneSolicitud($publicacion['id'], implode(array_column($_SESSION['usuarioconectado'], 'id')));
            ?>
            <?php if (!$tieneSolicitud) { ?>
                <section style="border-bottom: 1px solid #33cbad;" class="container p-2 mt-2">
                    <div class="media">
                        <div class="media-left">
                            <img src="images/<?= $publicacion['fotoPerfil'] ?>" width="69" height="64" alt="Foto perfil" class="media-object rounded-circle mr-2 mt-1 border">
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
                                        <div class="media mt-2">
                                            <div class="media-left">
                                                <img src="images/<?= $comentario['fotoPerfil'] ?>" width="60" height="60" alt="Foto perfil" class="media-object rounded-circle mr-2 mt-1 border">
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
            <?php } else { ?>
                <section style="border-bottom: 1px solid #33cbad;" class="container p-2 mt-2 border border-success rounded">
                    <div class="media">
                        <div class="media-left">
                            <img src="images/<?= $publicacion['fotoPerfil'] ?>" width="69" height="64" alt="Foto perfil" class="media-object rounded-circle mr-2 mt-1 border">
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading" style="color: #33cbad;"><?= $publicacion['nombre'] . " " . $publicacion['apellidos'] ?></h4>

                            <p><?= $publicacion['estadoCuerpo'] ?></p>
                            <!-- Botón de responder -->
                            <p class="d-flex justify-content-between align-items-center">
                                <span class="text-success"><i class="fas fa-user-plus"></i> Tiene 1 solicitud de amistad de esta persona </span>
                                <small class="text-muted">
                                    <?= $c->formatearFecha($publicacion['fecha']);
                                    ?>
                                </small>
                            </p>
                        </div>
                    </div>
                </section>
            <?php } ?>
        <?php endforeach; ?>
        </div>
    </div>
</div>
<?php $contenido = ob_get_clean() ?>

<head>
    <title>WhoMeet - Comentarios en estados </title>
</head>
<?php include 'layout.php' ?>