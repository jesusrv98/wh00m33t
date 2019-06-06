<?php ob_start() ?>
<script src="js/jqueryGoogle.js"></script>
<style>
  .page-item.active .page-link {
    background: #33cbad;
    border-color: inherit;
  }
</style>
<script>
  $(document).ready(function() {
    $('.botonSolicitud').click(function() {
      var idSolicitante = "<?php echo implode(array_column($_SESSION['usuarioconectado'], 'id')) ?>";
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
              window.location.replace("http://localhost/proyectoGIT/wh00m33t/web/index.php?ctl=busqueda");

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

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="alert alert-success" role="alert">
        <span><?php echo $params['mensajeBusqueda'] ?></span>
      </div>
    </div>
    <div class="col-12">
      <?php foreach ($params['busqueda'] as $amigo) : ?>
        <div class="card mt-2">
          <div class="row no-gutters">
            <div class="col-5 col-sm-5 col-md-5 col-lg-1 col-xl-1 d-flex justify-content-center align-items-center">
              <img src="images/<?php echo $amigo['fotoPerfil'] ?>" class="media-object rounded-circle mr-2 mt-1 border" width="90" height="84" alt="Foto de perfil de <?php echo $amigo['correo'] ?>">
            </div>
            <div class="col-7 col-sm-7 col-md-7 col-lg-11 col-xl-11">
              <div class="card-body">
                <form method="post" action="index.php?ctl=perfil">
                  <input type="hidden" value="<?= $amigo['id'] ?>" name="perfilUsuario">
                  <h3 class="card-title font-weight-bold" onclick="$(this).parent().submit()" style="color: #33cbad;cursor:pointer;"><?= $amigo['nombre'] . " " . $amigo['apellidos'] ?></h3>
                </form>
                <div class="d-flex justify-content-end align-items-center">
                  <?php

                  $idUsuarioConectado = $params['idUsuarioConectado'];
                  $idUsuarioBuscado = $amigo['id'];

                  $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);
                  $esAmigo = $m->isAmigo($idUsuarioConectado, $idUsuarioBuscado);
                  $tieneSolicitud = $m->tieneSolicitud($idUsuarioConectado, $idUsuarioBuscado);
                  $tieneSolicitudInversa = $m->tieneSolicitud($idUsuarioBuscado, $idUsuarioConectado);
                  if ($esAmigo == false && $tieneSolicitud == false) {
                    if (!$tieneSolicitudInversa) {
                      echo "<form method='post' class='form-inline my-2 my-md-0 mr-1'>
                      <button type='button' value='" . $amigo['id'] . "' class='btn btn-sm btn-success botonSolicitud'>
                        <i  class='fas fa-user-plus'></i> <span>Enviar solicitud de amistad</span>
                      </button>
                    </form>";
                    } else {
                      echo "<a href='index.php?ctl=solicitudes' style='text-decoration: none' class='btn btn-sm btn-success'>
                      <i class='fas fa-user-plus'></i> <span>Gestionar solicitudes</span>
                  </a>";
                    }
                  } elseif ($tieneSolicitud == true) {
                    echo "<form method='post' class='form-inline my-2 my-md-0 mr-1'>
                              <button type='button' value='" . $amigo['id'] . "' class='btn btn-sm btn-success' disabled='true'>
                                <i class='fas fa-user-check'></i> <span>Solicitud de amistad enviada</span>
                              </button>
                            </form>";
                  } else {
                    echo "<form method='post' class='form-inline my-2 my-md-0 mr-1'>
                              <button type='button' value='" . $amigo['id'] . "' class='btn btn-sm btn-danger botonEliminar'>
                                <i  class='fas fa-user-times'></i>
                              </button>
                            </form>";
                  }



                  ?>
                  <form method="post" class="form-inline my-2 my-md-0">
                    <button type="button" class="btn btn-sm" style="background: lightgrey" title="Bloquear usuario"><i class="fas fa-ban text-white"></i></button>
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
      <?php endforeach; ?>
    </div>
    <div class="col-12 d-flex justify-content-center">
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center mt-2">
          <?php
          for ($i = 1; $i <= $params['totalPaginas']; $i++) :
            if ($i <= 10) {
              if ($params['page'] == $i) {
                echo "<li class='page-item active'><a class='page-link' href='index.php?ctl=busqueda&pagina=" . $i . "'>" . $i . "</a></li>";
              } else {
                echo "<li class='page-item'><a class='page-link' style='color: #33cbad;' href='index.php?ctl=busqueda&pagina=" . $i . "'>" . $i . "</a></li>";
              }
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
  <title>WhoMeet - Busqueda </title>
</head>
<?php include 'layout.php' ?>