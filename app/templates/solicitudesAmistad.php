<?php ob_start() ?>
<script src="js/jqueryGoogle.js"></script>
<script>
    $(document).ready(function() {
        $(".botonAceptar").click(function() {
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
                        alert("HEY BIEN");
                        
                    } else {
                        alert(msg);
                    }
                },
                error: function() {
                    alert("HOLA");
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
                if($params['countPeticiones']>1 ) {
                    echo " <h2><i class='fas fa-user-plus'></i> Tienes ".$params['countPeticiones']." peticiones de amistad.</h2>";
                }else if($params['countPeticiones'] == 1) {
                    echo " <h2><i class='fas fa-user-plus'></i> Tienes ".$params['countPeticiones']." petición de amistad.</h2>";
                }else{
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
                        <h3 class="card-title font-weight-bold" style="color:#42cfb3;"><?php echo $solicitud['nombre'] . " " . $solicitud['apellidos'] ?></h3>
                        <div class="d-flex justify-content-end align-items-center">
                            <form method='post' class='form-inline my-2 my-md-0 mr-1'>
                                <button type='button' value='<?= $solicitud['id']?>' id="<?= $solicitud['idSolicitud'] ?> '" class='btn btn-sm btn-success botonAceptar'>
                                    <i  class='fas fa-user-plus'></i> <span class="textoCambiarAceptar">Aceptar</span>
                                </button>
                            </form>
                            <form method='post' class='form-inline my-2 my-md-0 mr-1'>
                                <button type='button' value='<?= $solicitud['id'] ?>' class='btn btn-sm btn-danger botonCancelar'>
                                    <i  class='fas fa-user-times'></i> <span class="textoCambiarCancelar">Cancelar</span>
                                </button>
                            </form>
                            <form method="post" class="form-inline my-2 my-md-0">
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