<?php ob_start() ?>

<head>
    <link rel="stylesheet" href="lightbox/css/lightbox.css">
</head>
<script src="js/jqueryGoogle.js"></script>
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
                <h4 style="color:#33cbad" class="bg-light border p-4 rounded">Fotos de <u><?= $params['nombreUsuario']." ".$params['apellidosUsuario'] ?></u> (<?= $params['countFotosUsuario'] ?>)</h4>
            </div>
            <div class="col-12">
                <article class="row">
                    <?php foreach ($params['listaFotos'] as $foto) : ?>
                        <article class="col-md-3 text-center">
                            <a href="fotosUsuarios/<?= $foto['rutaFoto'] ?>" data-lightbox="example-set" data-title="<?= $foto['tituloFoto'] ?>">
                                <img src="fotosUsuarios/<?= $foto['rutaFoto'] ?>" style="width:40%; height:25%" alt="<?= $foto['tituloFoto'] ?>" class="img-thumbnail"></a>
                            <p class="mt-1"><?= $foto['tituloFoto'] ?></p>
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