<?php
ob_start();
?>
<script src="js/jqueryGoogle.js"></script>
<script>
    $(document).ready(function() {
        $('.botonEliminar').click(function() {
            if (confirm("¿Estás seguro de que quieres borrar tu amistad con el usuario seleccionado?")) {
                var idUsuario1 = "<?php echo implode(array_column($_SESSION['usuarioconectado'], 'id')) ?>";
                var idUsuario2 = $(this).val();
                var botonPulsado = $(this);

                var parametros = {
                    'idUsuario1': idUsuario1,
                    'idUsuario2': idUsuario2,
                };
                $.ajax({
                    data: parametros,
                    url: '../app/templates/includes/servletEliminarAmigo.php',
                    type: 'post',
                    async: true,
                    success: function(msg) {
                        if (msg == 'ok') {
                            window.location.replace("http://localhost/proyectoGIT/wh00m33t/web/index.php?ctl=gestionAmigos");

                        } else {
                            alert("No se pudo enviar la solicitud de amistad a la siguiente persona: " + msg);
                        }
                    },
                    error: function() {
                        alert("Ha ocurrido un error y no se puede agregar.");
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
    #piePagina{
        display:none;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success" role="alert">
                <?php
                if ($params['countAmigos'] > 1) {
                    echo " <h2><i class='fas fa-users'></i> Tienes un total de " . $params['countAmigos'] . " amigos.</h2>";
                } else if ($params['countAmigos'] == 1) {
                    echo " <h2><i class='fas fa-user-plus'></i> Tienes sólo " . $params['countAmigos'] . " amigo.</h2>";
                } else {
                    echo " <h2><i class='fas fa-user-plus'></i> ¡Oh! no tienes amigos, ¿a qué esperas para ponerte a agregar gente?</h2>";
                }
                ?>
            </div>
        </div>
        <div class="col-12">
            <?php foreach ($params['listaAmigos'] as $amigo) : ?>
                <?php if ($amigo['id'] != 30) : ?>
                    <div class="card mt-2">
                        <div class="row no-gutters">
                            <div class="col-5 col-sm-5 col-md-5 col-lg-1 col-xl-1 d-flex justify-content-center align-items-center">
                                <img src="images/<?php echo $amigo['fotoPerfil'] ?>" class="media-object rounded-circle mr-2 mt-1 border" width="90" height="84" alt="Foto de perfil de <?php echo $amigo['correo'] ?>">
                            </div>
                            <div class="col-7 col-sm-7 col-md-7 col-lg-11 col-xl-11">
                                <div class="card-body">
                                    <form method="post" action="index.php?ctl=perfil">
                                        <input type="hidden" value="<?= $amigo['id'] ?>" name="perfilUsuario">
                                        <h3 class="card-title font-weight-bold" onclick="$(this).parent().submit()" style="color: #33cbad;cursor:pointer;"><?= $amigo['nombre'] . " " . $amigo['apellidos'] ?>
                                            <?php if ($amigo['verificado'] != null) { ?>
                                                <i style="color:#33cbad;font-size: 0.8em" title="Perfil verificado" class="fas fa-check-circle"></i>
                                            <?php } ?>
                                        </h3>
                                    </form>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <form method='post' class='form-inline my-2 my-md-0 mr-1'>
                                            <button type='button' value='<?= $amigo['id'] ?>' class='btn btn-sm btn-danger botonEliminar'>
                                                <i class='fas fa-user-times'></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="card-text">Sexo: <?php echo $amigo['sexo'] ?> </p>
                                        <p class="card-text"><small class="text-muted"><?php echo $amigo['poblacion'] . ", " . $amigo['provincia'] . "." ?></small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <!--  -->
        <div class="col-12 d-flex justify-content-center">
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center mt-2">
                    <?php
                    for ($i = 1; $i <= $params['totalPaginas']; $i++) :
                        if ($params['pagina'] == $i) {
                            echo "<li class='page-item active'><a class='page-link' href='index.php?ctl=gestionAmigos&pagina=" . $i . "'>" . $i . "</a></li>";
                        } else {
                            echo "<li class='page-item'><a class='page-link' style='color: #33cbad;' href='index.php?ctl=gestionAmigos&pagina=" . $i . "'>" . $i . "</a></li>";
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
    <title>WhoMeet - Gestión de amigos </title>
</head>
<?php include 'layout.php' ?>