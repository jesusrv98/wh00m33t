<?php ob_start() ?>
 <script src="js/jqueryGoogle.js"></script>


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
               <img src="images/<?php echo $amigo['fotoPerfil'] ?>" class="card-img p-2" style="width: 5rem;height:6rem" alt="Foto de perfil de <?php echo $amigo['correo'] ?>">
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
                    if ($esAmigo == false) {
                      echo "<form method='post' class='form-inline my-2 my-md-0 mr-1'>
                              <button type='button' class='btn btn-sm btn-success'>
                                <i class='fas fa-user-plus'></i>
                                Enviar solicitud de amistad 
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