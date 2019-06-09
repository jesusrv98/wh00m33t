<?php
ob_start();
$c = new Controller();
$m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <form class="was-validated">
                            <div class="mb-3">
                                <h1 for="validationInput" class="text-center mb-5" style="color:#33cbad;">Configuración de cuenta de usuario</h1>
                                <label>
                                    Datos de acceso:
                                </label>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <input type="text" class="form-control validated" id="validationTextarea" name="correoActual" placeholder="Correo electrónico actual..." required />
                                        <div class="invalid-feedback">
                                            Para editar introduzca su correo actual.
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text" class="form-control" name="correoNuevo" placeholder="Correo electrónico nuevo..." />
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label>
                                    Contraseña:
                                </label>
                                <input type="password" class="form-control validated" id="validationTextarea" placeholder="Contraseña..." />
                                <div class="invalid-feedback">
                                    Para editar introduzca su contraseña.
                                </div>
                            </div>

                            <label>
                                Sexo:
                            </label>
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="customControlValidation2" name="radio-stacked" required>
                                <label class="custom-control-label" for="customControlValidation2">Hombre</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="customControlValidation3" name="radio-stacked" required>
                                <label class="custom-control-label" for="customControlValidation3">Mujer</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="customControlValidation2" name="radio-stacked" required>
                                <label class="custom-control-label" for="customControlValidation2">Otro</label>
                                <div class="invalid-feedback">Debes seleccionar uno</div>
                            </div>

                            <label>
                                Estado civil:
                            </label>
                            <div class="form-group">
                                <select class="custom-select" required>
                                    <option value="">--Seleccione uno--</option>
                                    <option value="1">En matrimonio</option>
                                    <option value="2">En soltería</option>
                                    <option value="3">En divorcio</option>
                                    <option value="3">En viudez</option>
                                </select>
                                <div class="invalid-feedback">Debe elegir su estado civil</div>
                            </div>

                            <div class="form-group">
                                <div class="col-4 col-sm-4 col-md-2 col-lg-2 col-xl-2">
                                    <img id="foto" class="img-thumbnail">
                                </div>
                            </div>

                            <div class="custom-file">
                                <input type="file" name="foto" id="botonFoto">
                                <label class="custom-file-label" for="botonFoto">Cambiar foto de perfil...</label>
                            </div>
                            <label class="mt-2">
                                Editar nombre:
                            </label>
                            <div class="alert alert-warning" role="alert">
                                <span>Recuerda que sólo puedes cambiar una vez de nombre.</span>
                                <div class="form-group mt-1">
                                    <input type="text" name="nuevo" class="form-control" placeholder="Nuevo nombre...">
                                </div>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="customControlValidation1" required>
                                <label class="custom-control-label" for="customControlValidation1">Confirmo que deseo hacer cambios en mi perfil</label>
                                <div class="invalid-feedback">Este campo es necesario</div>
                            </div>

                            <div class="form-group">
                                <input type="submit" value="Guardar cambios" class="btn btn-success form-control" onsubmit="return false;">
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