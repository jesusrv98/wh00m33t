<?php ob_start() ?>
<script src="js/jqueryGoogle.js"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
        <div class="alert alert-success" role="alert">
            <?php 
                if($params['countPeticiones']>1 ) {
                    echo " <h2><i class='fas fa-user-plus'></i> Tienes ".$params['countPeticiones']." comentario en algún estado sin ver.</h2>";
                }else if($params['countPeticiones'] == 1) {
                    echo " <h2><i class='fas fa-user-plus'></i> Tienes ".$params['countPeticiones']." comentarios en estados sin ver.</h2>";
                }else{
                    echo " <h2><i class='fas fa-user-plus'></i> No tienes ningún comentario en ningun estado nuevo sin ver.</h2>";
                }
            ?>
        </div>
        </div>
        <div class="col-12">

        </div>
    </div>
</div>
<?php $contenido = ob_get_clean() ?>

<head>
    <title>WhoMeet - Comentarios en estados </title>
</head>
<?php include 'layout.php' ?>