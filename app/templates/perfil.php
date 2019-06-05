<?php
ob_start();
$c = new Controller();
$m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);
?>

<script type="text/javascript" src="js/jqueryGoogle.js"></script>
<div class="container-fluid">
    <div class="row">

        <!-- Parte izquierda -->
        <?php foreach ($params['perfilUsuario'] as $perfil) :?>
            <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                <div class="row">
                    <div class="col-12 text-center">
                        <img width="200" height="194" class="rounded-circle border" src="images/<?= $perfil['fotoPerfil'] ?>" />
                    </div>
                    <div class="col-12 border-right">
                        <p class="text-muted border-bottom border-top mt-3"><b>Datos</b></p>
                        <p><small style="color:#33cbad">Comunidad autónoma: <?= $perfil['comunidad'] ?></small></p>
                        <p><small style="color:#33cbad">Provincia: <?= $perfil['provincia'] ?></small></p>
                        <p><small style="color:#33cbad">Municipio: <?= $perfil['poblacion'] ?></small></p>
                        <p><small style="color:#33cbad">Edad: <?= $c->aniosHastaHoy($perfil['fechanac']) ?></small></p>
                        <p><small style="color:#33cbad">Sexo: <?= $perfil['sexo'] ?></small></p>
                        <p><small style="color:#33cbad">Estado civil: <?= $perfil['estadocivil'] ?></small></p>
                        <p><small style="color:#33cbad">Cumpleaños: <?= $c->fechaCumpleanios($perfil['fechanac']) ?></small></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <!-- Fin parte izquierda -->
        
    </div>
</div>
</div>

<?php $contenido = ob_get_clean() ?>

<head>
    <title>WhoMeet - Gestión de Contenido </title>
</head>
<?php include 'layout.php' ?>