<?php
ob_start();
?>
<!--Parte izquierda -->
<div class="container-fluid">
    <div class="row">
        <div class="container col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 mt-2" style="border-right: 1px solid #33cbad; height: auto">
            <!-- Resumen perfil -->
            <div class="row">
                <div class="col-12" style="margin-top: 1em">
                    <div class="row">
                        <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4 ">
                            <img class="img-thumbnail img-fluid" src="images/<?php echo implode(array_column($_SESSION['usuarioconectado'], 'fotoPerfil')); ?>" />
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                            <div class="row">
                                <div class="col-12">
                                    <p style="color: #33cbad"><?php echo implode(array_column($_SESSION['usuarioconectado'], 'nombre')) . " " . implode(array_column($_SESSION['usuarioconectado'], 'apellidos')); ?></p>
                                </div>
                                <div class="col-12">
                                    <p class="text-muted"><i class="fas fa-chart-bar"></i> <?php echo $params['visitas'] ?> visitas a tu perfil</p>
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
                    <form action="index.php" method="POST">
                        <input class="form-control" style="border-radius: 1em" type="text" name="nuevapubli" placeholder="Nuevo estado..." required />
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                    <p style="font-size:13px; margin-left: 0.5em; margin-top: 1em" class="text-muted"><b>Última actualización:</b>
                        <?php echo $params['estadoActual'] ?>
                        <?php
                        if ($params['estadoActualFecha']) {
                            echo "Hace " . $params['estadoActualFecha'];
                        }
                        ?>
                    </p>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                    <input style="margin-top: 0.8em;border: 0px;border-radius: 1em;background: #e6fbff;width: auto; color: #33cbad" class="btn" id="guardarpubli" type="submit" value="Guardar" />
                </div>
                </form>
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
                            <!-- Publicación -->
                            <!-- 
                                
                             -->
                            <!-- 
                                 
                              -->
                            <!-- AQUÍ FOREACH PARA CADA PUBLIC -->
                            <?php foreach ($params['publicacionesAmigos'] as $publicacion) : ?>
                                <div class="row mt-3">
                                    <div class="col-12 col-sm-12 col-md-1 col-lg-1 col-xl-1"><img class="rounded border" width="40" height="40" src="images/<?php echo $publicacion['fotoPerfil'] ?>" /></div>
                                    <div class="col-12 col-sm-12 col-md-11 col-lg-11 col-xl-11">
                                        <p style="color: #33cbad"><?php echo $publicacion['nombre'] ?> <?php echo $publicacion['apellidos'] ?></p>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <p><?php echo $publicacion['estadoCuerpo'] ?></p>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <small class="text-muted">
                                            <?php 
                                               $estadoActualFecha = $publicacion['fecha'];
                                                if ($estadoActualFecha != "0000-00-00 00:00:00") {
                                                    $fechaActual = new DateTime('now');
                                                    $fechaDeEstado = new DateTime($estadoActualFecha);
                                                    $diff = $fechaActual->diff($fechaDeEstado);
                                        
                                                    $estadoActualFechaSegundos = $diff->s;
                                                    $estadoActualFechaMinutos = $diff->i;
                                                    $estadoActualFechaHoras = $diff->h;
                                                    $estadoActualFechaDias = $diff->d;
                                        
                                                    if ($estadoActualFechaDias > 7) {
                                                       echo "Hace más de una semana.";
                                                    } else {
                                                        if ($estadoActualFechaDias == 0) {
                                                            if ($estadoActualFechaHoras < 1) {
                                                                if ($estadoActualFechaHoras < 1) {
                                                                    if ($estadoActualFechaMinutos < 1) {
                                                                        if ($estadoActualFechaSegundos < 2) {
                                                                            if ($estadoActualFechaSegundos == 0) {
                                                                                echo "Hace ". $estadoActualFechaSegundos . " segundos.";
                                                                            } else {
                                                                                echo "Hace ". $estadoActualFechaSegundos . " segundo.";
                                                                            }
                                                                        } else {
                                                                            echo "Hace ". $estadoActualFechaSegundos . " segundos.";
                                                                        }
                                                                    } else {
                                                                        if ($estadoActualFechaMinutos < 2) {
                                                                            echo "Hace ". $estadoActualFechaMinutos . " minuto.";
                                                                        } else {
                                                                            echo "Hace ". $estadoActualFechaMinutos . " minutos.";
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo "Hace ". $estadoActualFechaHoras . " hora.";
                                                                }
                                                            } else {
                                                                if ($estadoActualFechaHoras < 2) {
                                                                    echo "Hace ". $estadoActualFechaHoras . " hora.";
                                                                } else {
                                                                    echo "Hace ". $estadoActualFechaHoras . " horas.";
                                                                }
                                                            }
                                                        } elseif ($estadoActualFechaDias > 0) {
                                                            if ($estadoActualFechaDias < 2) {
                                                                echo "Hace ". $estadoActualFechaDias . " día.";
                                                            } else {
                                                                echo "Hace ". $estadoActualFechaDias . " días.";
                                                            }
                                                        }
                                                    }
                                                }
                                            ?>
                                        </small>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <form class="" action="index.php" method="POST">
                                            <input class="form-control" style="border-radius: 1em" type="text" name="comentarioamigo" placeholder="Comentar..." required /><input type="submit" value="Aceptar" style="border: 0px;border-radius: 1em;background-color: lightgrey;margin-top: 5px;width: 100%;display: none">
                                            <hr>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <!--  -->
                            <!--  -->

                            <!-- Fin publicación -->
                        </div>
                        <!-- Fin pestaña amigos -->
                        <!-- Pestaña sitios -->
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <!-- sitio -->
                            <div class="row mt-3">
                                <div class="col-12 col-sm-12 col-md-1 col-lg-1 col-xl-1"><img class="rounded border" width="40" height="40" src="../images/ejemploevento.jpg" /></div>
                                <div class="col-12 col-sm-12 col-md-11 col-lg-11 col-xl-11">
                                    <p style="color: #33cbad">Partido Real Madrid Club de Fútbol - Getafe Club de Fútbol</p>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 d-flex justify-content-start">
                                    <p style="color: #77bf5c"><i class="fas fa-users"></i> 245 personas irán</p>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 d-flex justify-content-end">
                                    <p class="text-muted">Día 23 de mayo de 2018</p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <form class="" action="index.php" method="POST">
                                        <input class="form-control" style="border-radius: 1em;background:#F0F0F0;color: #33cbad;cursor: pointer" type="submit" name="asistir" value="Asistiré" /><input type="hidden" value="1" style="display: none">
                                        <hr>
                                    </form>
                                </div>
                            </div>
                            <!-- Fin sitio -->
                            <!-- Segundo sitio -->
                            <div class="row mt-3">
                                <div class="col-12 col-sm-12 col-md-1 col-lg-1 col-xl-1"><img class="rounded border" width="40" height="40" src="../images/ejemploevento2.jpg" /></div>
                                <div class="col-12 col-sm-12 col-md-11 col-lg-11 col-xl-11">
                                    <p style="color: #33cbad">Festival of colors - Sevilla</p>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 d-flex justify-content-start">
                                    <p style="color: #77bf5c"><i class="fas fa-users"></i> 1934 personas irán</p>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 d-flex justify-content-end">
                                    <p class="text-muted">Día 17 de julio de 2018</p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <form class="" action="index.php" method="POST">
                                        <input class="form-control" style="border-radius: 1em;background:#F0F0F0;color: #33cbad;cursor: pointer" type="submit" name="asistire" value="Asistiré" /><input type="hidden" value="1" style="display: none">
                                        <hr>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Fin pestaña sitios -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Final parte centro -->
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
                                <div class="col-12 border pb-1 pt-1">
                                    <h6>Chat(20) <i class="fas fa-circle" style="color:#77bf5c;"></i></h6>
                                </div>
                                <div class="col-12">
                                    <i class="fas fa-video" style="color:#77bf5c;font-size: 11px;"></i> Alejandro Rubio Peronomoreno
                                </div>
                                <div class="col-12">
                                    <i class="fas fa-video" style="color:#77bf5c;font-size: 11px;"></i> Josema Delga2 Gor2
                                </div>
                                <div class="col-12">
                                    <i class="fas fa-video" style="color:#77bf5c;font-size: 11px;"></i> Joni Mela Bolculo
                                </div>
                                <div class="col-12">
                                    <i class="fas fa-video" style="color:#77bf5c;font-size: 11px;"></i> Jose Antonio Amieva Yamirocio
                                </div>
                                <div class="col-12">
                                    <i class="fas fa-video" style="color:#77bf5c;font-size: 11px;"></i> Natividad de María y de Mario
                                </div>
                                <div class="col-12">
                                    <i class="fas fa-circle" style="color:#77bf5c;font-size: 11px;"></i> Palomo Cojo Sinfrío
                                </div>
                                <div class="col-12">
                                    <i class="fas fa-video" style="color:#77bf5c;font-size: 11px;"></i> Ricar2 Biyalovos
                                </div>
                            </div>
                        </div>
                        <!-- Fin chat -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Pie de chat -->
        <div class="container-fluid fixed-bottom">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
                    <button class="btn btn-primary" type="button">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Conectando con el chat
                    </button>
                </div>
            </div>
        </div>;
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