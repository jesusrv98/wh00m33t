<?php ob_start() ?>
 <script src="js/jqueryGoogle.js"></script>
 <script>
   $(document).ready( function() {
           $('.botonSolicitud').click( function() {
            var idSolicitante = "<?php echo implode(array_column($_SESSION['usuarioconectado'], 'id')) ?>";
            var idSolicitado = $(this).val();
            var botonPulsado = $(this);
            var textoBoton= botonPulsado.find('span');
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
                        botonPulsado.attr('disabled','true');

                    } else {
                        alert("No se pudo enviar la solicitud de amistad a la siguiente persona: "+msg);
                    }
                },
                error: function() {
                    alert("Ha ocurrido un error y no se puede agregar.");
                }
            });
        });

        $('.botonEliminar').click( function() {
          if(confirm("¿Estás seguro de que quieres borrar tu amistad con el usuario seleccionado?")){
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
                        alert("No se pudo enviar la solicitud de amistad a la siguiente persona: "+msg);
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
               <img src="images/<?php echo $amigo['fotoPerfil'] ?>" class="card-img p-2" style="width: 6rem;height:6rem" alt="Foto de perfil de <?php echo $amigo['correo'] ?>">
             </div>
             <div class="col-7 col-sm-7 col-md-7 col-lg-11 col-xl-11">
               <div class="card-body">
                 <h3 class="card-title font-weight-bold" style="color:#42cfb3;"><?php echo $amigo['nombre'] . " " . $amigo['apellidos'] ?></h3>
                 <div class="d-flex justify-content-end align-items-center">
                   <?php

                    $idUsuarioConectado = $params['idUsuarioConectado'];
                    $idUsuarioBuscado = $amigo['id'];

                    $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);
                    $esAmigo = $m->isAmigo($idUsuarioConectado, $idUsuarioBuscado);
                    $tieneSolicitud = $m->tieneSolicitud($idUsuarioConectado, $idUsuarioBuscado);
                    if ($esAmigo == false && $tieneSolicitud == false) {
                      echo "<form method='post' class='form-inline my-2 my-md-0 mr-1'>
                              <button type='button' value='".$amigo['id']."' class='btn btn-sm btn-success botonSolicitud'>
                                <i  class='fas fa-user-plus'></i> <span>Enviar solicitud de amistad</span>
                              </button>
                            </form>";
                    } elseif($tieneSolicitud == true) {
                      echo "<form method='post' class='form-inline my-2 my-md-0 mr-1'>
                              <button type='button' value='".$amigo['id']."' class='btn btn-sm btn-success' disabled='true'>
                                <i class='fas fa-user-check'></i> <span>Solicitud de amistad enviada</span>
                              </button>
                            </form>";
                    } else{
                      echo "<form method='post' class='form-inline my-2 my-md-0 mr-1'>
                              <button type='button' value='".$amigo['id']."' class='btn btn-sm btn-danger botonEliminar'>
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
   </div>
 </div>
 <?php $contenido = ob_get_clean() ?>

 <head>
   <title>WhoMeet - Busqueda </title>
 </head>
 <?php include 'layout.php' ?>