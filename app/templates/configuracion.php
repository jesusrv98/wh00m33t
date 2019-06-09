<?php
ob_start();
$c = new Controller();
$m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);
?>
<script src="js/jqueryGoogle.js"></script>
<script>
    $(document).ready(function() {
        $("#selectProvincias").change(function() {
            $("#selectProvincias option:selected").each(function() {
                var idprovincia = $(this).val();
                $("#selectPueblos").removeAttr("disabled");
                $("#selectPueblos").attr("required");
                $.post("../app/templates/includes/getMunicipio.php", {
                    idprovincia: idprovincia
                }).done(
                    function(data) {
                        $("#selectPueblos").html(data);
                    });
            });
        });
    });
</script>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <form method="POST" action="index.php?ctl=configuracion">
                            <div class="mb-3">
                                <h1 for="validationInput" class="text-center mb-5" style="color:#33cbad;">Configuración de cuenta de usuario</h1>
                                <?php if ($params['msg'] != "") : ?>
                                    <div class="alert alert-success" role="alert">
                                        <span><?= $params['msg'] ?></span>
                                    </div>
                                <?php endif; ?>
                                <label>
                                    Datos de acceso:
                                </label>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <input type="text" autocomplete="off" class="form-control validated" id="validationTextarea" name="correoActual" placeholder="Correo electrónico actual..." required />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text" autocomplete="off" class="form-control" name="correoNuevo" placeholder="Correo electrónico nuevo..." />
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label>
                                    Contraseña:
                                </label>
                                <input type="password" name="contrasenaNueva" autocomplete="off" class="form-control" onload="$(this).val('jj')" placeholder="Contraseña..." />
                            </div>

                            <label class="mt-2">
                                Municipio:
                            </label>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <select id="selectProvincias" name="selectProvinciasR" class="custom-select">
                                        <option selected="" value=""> -- Seleccionar Provincia -- </option>
                                        <?php foreach ($params['provincias'] as $provincia) : ?>
                                            <option value="<?= $provincia['idprovincia'] ?>"><?= $provincia['provincia'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <select name="selectPueblos" id="selectPueblos" class="custom-select" disabled>
                                        <option> -- Seleccionar municipio -- </option>
                                    </select>
                                </div>
                            </div>
                            <label class="mt-2">
                                Teléfono:
                            </label>
                            <div>
                                <input type="text" name="telefonoNuevo" size="9" autocomplete="off" class="form-control" placeholder="Teléfono nuevo..." />
                            </div>

                            <label class="mt-2">
                                Sexo:
                            </label>
                            <div>
                                <select name="selectGeneroR" id="selectGenero" value="<?php echo $params['selectGeneroR'] ?>" class="custom-select">
                                    <option value=""> -- Seleccionar Género --</option>
                                    <option value="1">Masculino</option>
                                    <option value="2">Femenino</option>
                                    <option value="3">Otro</option>
                                    <option value="4">No especificar</option>
                                </select>
                            </div>

                            <label class="mt-2">
                                Estado civil:
                            </label>
                            <div class="form-group">
                                <select class="custom-select" name="estadoCivilNuevo">
                                    <option value="">--Seleccione uno--</option>
                                    <option value="1">En matrimonio</option>
                                    <option value="2">En soltería</option>
                                    <option value="3">En divorcio</option>
                                    <option value="3">En viudez</option>
                                </select>
                                <div class="invalid-feedback">Debe elegir su estado civil</div>
                            </div>

                            <label class="mt-2">
                                Editar nombre:
                            </label>
                            <div class="alert alert-warning" role="alert">
                                <div class="form-group mt-1">
                                    <input type="text" autocomplete="off" name="nombreNuevo" class="form-control" placeholder="Nuevo nombre...">
                                </div>
                                <div class="form-group mt-1">
                                    <input type="text" autocomplete="off" name="apellidosNuevos" class="form-control" placeholder="Nuevos apellidos...">
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="submit" value="Guardar cambios" class="btn btn-success form-control">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $contenido = ob_get_clean() ?>

<head>
    <title>WhoMeet - Perfil </title>
</head>
<?php include 'layout.php' ?>