<?php
ob_start();
$m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);
$c = new Controller();
?>

<head>
    <link rel="stylesheet" href="lightbox/css/lightbox.css">
</head>
<script src="js/jqueryGoogle.js"></script>
<script>
    $(document).ready(function() {
        $(".noMeGusta").click(function() {
            var corazon = $(this);
            var texto = corazon.parent().find("span");
            var idFoto = corazon.attr("id");
            var parametros = {
                'idUsuario': <?php echo $params['idUsuarioConectado'] ?>,
                'idFoto': idFoto,
                'tipo': 'meGusta'
            };
            $.ajax({
                data: parametros,
                url: '../app/templates/includes/servletGestionMeGustaNoMeGusta.php',
                type: 'post',
                async: true,
                success: function(msg) {
                    if (msg == 'ok') {
                        corazon.removeClass("far");
                        corazon.removeClass("noMeGusta")
                        texto.text("Te gusta esta foto");
                        corazon.addClass("fas");
                        corazon.addClass("siMeGusta");
                        corazon.addClass("text-danger");
                    } else {
                        texto.text("No se pudo dar me gusta a esta foto");
                    }
                }
            });
        });

        $(".siMeGusta").click(function() {
            var corazon = $(this);
            var texto = corazon.parent().find("span");
            var idFoto = corazon.attr("id");
            var parametros = {
                'idUsuario': <?php echo $params['idUsuarioConectado'] ?>,
                'idFoto': idFoto,
                'tipo': 'noMeGusta'
            };
            $.ajax({
                data: parametros,
                url: '../app/templates/includes/servletGestionMeGustaNoMeGusta.php',
                type: 'post',
                async: true,
                success: function(msg) {
                    if (msg == 'ok') {
                        corazon.removeClass("fas");
                        corazon.removeClass("siMeGusta")
                        corazon.addClass("far");
                        corazon.addClass("noMeGusta");
                        corazon.addClass("text-dark");
                        texto.text("Ya no te gusta esta foto");
                    } else {
                        texto.text("No se pudo quitar el me gusta a esta foto");
                    }
                }
            });
        });

        $(".borrarFoto").click(function() {
            if (confirm("¿Desea borrar su foto?")) {
                var boton = $(this);
                var idFoto = boton.attr("id");
                var rutaFoto= boton .parent().parent().find("a").find("img").attr("src");

                var parametros = { 
                    'idFoto': idFoto,
                    'tipo': 'borrarFoto',
                    'rutaFoto': rutaFoto
                };
                $.ajax({
                    data: parametros,
                    url: '../app/templates/includes/servletGestionMeGustaNoMeGusta.php',
                    type: 'post',
                    async: true,
                    success: function(msg) {
                        if (msg == 'ok') {
                            boton.parent().parent().css("display", "none");
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

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h4 style="color:#33cbad" class="bg-light border p-4 rounded">Fotos de <u><?= $params['nombreUsuario'] . " " . $params['apellidosUsuario'] ?></u> (<?= $params['countFotosUsuario'] ?>)</h4>
            </div>
            <div class="col-12">
                <article class="row">
                    <?php foreach ($params['listaFotos'] as $foto) : ?>
                        <?php
                        $arrayMeGusta = $m->getCountMeGustaByFoto($foto['idFoto']);
                        $countMeGusta = implode(array_column($arrayMeGusta, "countMeGusta"));
                        ?>
                        <article class="col-md-3 text-center mb-5">
                            <a href="fotosUsuarios/<?= $foto['rutaFoto'] ?>" data-lightbox="example-set" data-title="<?= $foto['tituloFoto'] ?>">
                                <img src="fotosUsuarios/<?= $foto['rutaFoto'] ?>" style="width:40%; height:25%" alt="<?= $foto['tituloFoto'] ?>" class="img-thumbnail">
                            </a>
                            <p class="mt-1"><?= $foto['tituloFoto'] ?></p>
                            <div class="d-flex align-items-center text-center justify-content-center">
                                <?php if ($m->tieneTuMeGusta($foto['idFoto'], $params['idUsuarioConectado'])) : ?>
                                    <i class="fas text-danger fa-heart fa-2x siMeGusta" id=<?= $foto['idFoto'] ?> style="cursor: pointer"></i>
                                <?php endif; ?>
                                <?php if (!$m->tieneTuMeGusta($foto['idFoto'], $params['idUsuarioConectado'])) : ?>
                                    <i class="far fa-heart fa-2x noMeGusta" id=<?= $foto['idFoto'] ?> style="cursor: pointer"></i>
                                <?php endif; ?>
                                <?php if ($countMeGusta == 1) : ?>
                                    <span class="text-muted ml-2">Le gusta a <?= $countMeGusta ?> usuario</span>
                                <?php endif; ?>
                                <?php if ($countMeGusta > 1) : ?>
                                    <span class="text-muted ml-2">Le gusta a <?= $countMeGusta ?> usuarios</span>
                                <?php endif; ?>
                                <?php if ($countMeGusta == 0) : ?>
                                    <span class="text-muted ml-2">La foto aún no tiene likes</span>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                <small class="text-muted"><?= $c->formatearFecha($foto['fechaSubida']) ?></small>
                                <?php if ($params['idUsuarioConectado'] == $params['idUsuarioPerfil']) : ?>
                                    <i class="ml-3 fas fa-times text-danger borrarFoto" style="cursor:pointer" id="<?= $foto['idFoto'] ?>" title="Borrar foto"></i>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </article>
            </div>
        </div>
    </div>
    <script src="lightbox/js/lightbox.js"></script>
    <script>
        lightbox.option({
            'albumLabel': "Imagen %1 de %2"
        })
    </script>
</body>
<?php $contenido = ob_get_clean() ?>

<head>
    <title>WhoMeet - Busqueda </title>
</head>
<?php include 'layout.php' ?>