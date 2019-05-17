<?php
    ob_start();
?>

<!DOCTYPE html>
<html lang="es-ES">

<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="images/logo.png">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <title>WhoMeet - Iniciar sesión</title>

    <style type="text/css">
        .divider-text {
            position: relative;
            text-align: center;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .divider-text span {
            padding: 7px;
            font-size: 12px;
            position: relative;
            z-index: 2;
        }

        .divider-text:after {
            content: "";
            position: absolute;
            width: 100%;
            border-bottom: 1px solid #ddd;
            top: 55%;
            left: 0;
            z-index: 1;
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


    <script src="js/jqueryGoogle.js"></script>

    <script type="text/javascript">
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

        function registrarOtraVez() {
                $('.modal-body').css('display', 'block');
                $('.statusMessage').removeClass('d-block');
                $('.statusMessage').addClass('d-none');
                $("#selectPueblos").attr('disabled');
                $('.statusMessage').addClass('d-none');
        }



        function registrar() {

            var reg = /^[A-Z0-9._%+-]+@([A-Z0-9-]+.)+[A-Z]{2,4}$/i;
            var nombre = $("#nombre").val();
            var apellidos = $("#apellidos").val();
            var correo = $("#correo").val();
            var contrasena = $("#contrasena").val();
            var contrasena2 = $("#contrasena2").val();
            var telefono = $("#telefono").val();
            var fechanac = $("#fechanac").val();
            var selectPueblos = $("#selectPueblos").val();
            var selectGenero = $("#selectGenero").val();
            var selectEstadoCivil = $("#selectEstadoCivil").val();

            if (nombre.trim() == '') {
                alert('Por favor, introduzca su nombre.');
                $('#nombre').focus();
                return false;
            } else if (apellidos.trim() == '') {
                alert('Por favor, introduzca su apellido.');
                $('#apellidos').focus();
                return false;
            } else if (correo.trim() == '') {
                alert('Por favor, introduzca su correo.');
                $('#correo').focus();
                return false;
            } else if (correo.trim() != '' && !reg.test(correo)) {
                alert('Por favor, introduzca un correo válida.');
                $('#correo').focus();
                return false;
            } else if (contrasena.trim() == '') {
                alert('Por favor, introduzca su contraseña.');
                $('#contrasena').focus();
                return false;
            } else if (contrasena2.trim() == '') {
                alert('Por favor, vuelva a escribir su contraseña.');
                $('#contrasena2').focus();
                return false;
            } else if (contrasena.trim() != contrasena2.trim()) {
                alert('Ambas contraseñas deben coincidir.');
                $("#contrasena").val = "";
                $("#contrasena2").val = "";
                $('#contrasena').focus();
                return false;
            } else if (telefono.trim() == '') {
                alert('Por favor, vuelva a escribir su teléfono.');
                $('#telefono').focus();
                return false;
            } else if (telefono.length != 9) {
                alert('Un número de teléfono sólo puede contener  9 dígitos.');
                $('#telefono').focus();
                return false;
            } else if (fechanac.trim() == '') {
                alert('Por favor, vuelva introduzca su fecha de nacimiento correctamente.');
                $('#fechanac').focus();
                return false;
            } else if (selectPueblos.trim() == '') {
                alert('Por favor, seleccione su municipio.');
                $('#selectPueblos').focus();
                return false;
            } else if (selectGenero.trim() == '') {
                alert('Por favor, seleccione su género.');
                $('#selectGenero').focus();
                return false;
            } else if (selectEstadoCivil.trim() == '') {
                alert('Por favor, seleccione su estado civil.');
                $('#selectEstadoCivil').focus();
                return false;
            } else {
                var parametros = {
                    'nombre': nombre,
                    'apellidos': apellidos,
                    'correo': correo,
                    'contrasena': contrasena,
                    'contrasena2': contrasena2,
                    'telefono': telefono,
                    'fechanac': fechanac,
                    'selectPueblos': selectPueblos,
                    'selectGenero': selectGenero,
                    'selectEstadoCivil': selectEstadoCivil
                };
                $.ajax({
                    data: parametros,
                    url: '../app/templates/includes/servletRegistrar.php',
                    type: 'get',
                    async: true,
                    success: function(msg) {
                        if (msg == 'ok') {
                            $('#nombre').val('');
                            $('#apellidos').val('');
                            $('#correo').val('');
                            $('#contrasena').val('');
                            $('#contrasena2').val('');
                            $("#telefono").val('');
                            $("#fechanac").val('');
                            $("#selectPueblos").val('-- Seleccionar municipio --');
                            $("#selectPueblos").attr('disabled');
                            $("#selectGenero").val('');
                            $("#selectEstadoCivil").val('');

                            $(".modal-body").css('display', 'none');
                            $('.statusMessage').removeClass('d-none');
                            $('.statusMessage').addClass('d-block')

                            $('.statusMessage').html("<div class='alert alert-success' role='alert' >El usuario con correo: " + correo + " ha sido registrado satisfactoriamente a WhoMeet, ¡Disfruta!</div><p id='registroNuevo' style='cursor:pointer' onclick='registrarOtraVez()' class='text-primary text-center'>¿Quieres registrar otra cuenta?</p>");
                        } else {
                            $('.statusMessage').removeClass('d-none');
                            $('.statusMessage').addClass('d-block');
                            $('.statusMessage').html("<div class='alert alert-danger' role='alert' >Ha ocurrido algún problema, por favor inténtelo de nuevo.</div>");
                        }

                    }
                });
            }
        }
    </script>


</head>

<body class="text-center" style="background: linear-gradient(#93ECFF, white);background-repeat: no-repeat;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <img class="img-fluid" src="images/logo.png" alt="logoWhoMeet.png">
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <form class="form-signin" method="POST" action="index.php?ctl=login">
                    <div class="form-group">
                        <label for="inputEmail" class="sr-only">E-mail</label>
                        <input type="email" id="inputEmail" class="form-control" name="email" value="<?php echo $params['email'] ?>" placeholder="E-mail" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword" class="sr-only">Contraseña</label>
                        <input type="password" id="inputPassword" class="form-control" value="<?php echo $params['password'] ?>" name="password" placeholder="Contraseña" required>
                    </div>
                    <div class="form-group">
                        <input class="btn btn-lg btn-block" style="color:white;background-color: #3bd1b3" type="submit" value="Iniciar sesión" />
                        <!-- Botón para el modal de registro -->
                        <a data-toggle="modal" data-target="#exampleModal" class="btn btn-lg btn-block mt-2" style="text-decoration: none;color:white;background-color: #79E9FF;cursor:pointer">
                            ¿Eres nuevo?
                        </a>
                    </div>

                    <?php
                    if (($params['resultado'])) {
                        header('Location:index.php?ctl=inicio');
                    } elseif (($params['mensaje'])) {
                        echo "<div class='alert alert-danger' role='alert' >" . $params['mensaje'] . "</div>";
                    }
                    ?>
                </form>
                <footer class="col-12">
                    <b>
                        <p class="mt-5 mb-3" style="color:#33cbad">&copy;<a target="blank" style="color:#33cbad; text-decoration: none">WhoMeet</a> <?= date("Y") ?></p>
                    </b>
                </footer>
                <!-- Aquí comienza el contenido del modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <!-- Cuerpo del modal -->
                            <div class="modal-body">
                                <div class="card bg-light">
                                    <article class="card-body mx-auto">
                                        <h4 class="card-title mt-2 text-center">Registrarse</h4>
                                        <p class="text-center">Comienza creando una cuenta nueva</p>
                                        <form method="get">
                                            <!-- FORM ROW -->
                                            <div class="form-row" >
                                                <div class="form-group input-group col-md-6">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                                    </div>
                                                    <input name="nombreR" id="nombre" class="form-control" value="<?php echo $params['nombreR'] ?>" placeholder="Nombre..." type="text">
                                                </div>
                                                <div class="form-group input-group col-md-6">
                                                    <input name="apellidosR" id="apellidos" class="form-control" value="<?php echo $params['apellidosR'] ?>" placeholder="Apellidos..." type="text">
                                                </div>
                                                <div class="form-group input-group col-md-12">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-envelope"></i>
                                                        </span>
                                                    </div>
                                                    <input name="correoR" id="correo" class="form-control" autocomplete="off" placeholder="Correo electrónico..." value="<?php echo $params['correoR'] ?>" type="email">
                                                </div>
                                                <div class="form-group input-group col-md-6">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-lock"></i>
                                                        </span>
                                                    </div>
                                                    <input class="form-control" id="contrasena" name="contrasenaR" autocomplete="off" value="<?php echo $params['contrasenaR'] ?>" placeholder="Contraseña..." type="password">
                                                </div>
                                                <div class="form-group input-group col-md-6">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-lock"></i>
                                                        </span>
                                                    </div>
                                                    <input class="form-control" name="contrasenaR2" id="contrasena2" autocomplete="off" value="<?php echo $params['contrasenaR2'] ?>" placeholder="Repetir contraseña..." type="password">
                                                </div>
                                                <div class="form-group input-group col-md-12">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-phone"></i>
                                                        </span>
                                                    </div>
                                                    <input name="telefonoR" id="telefono" class="form-control" value="<?php echo $params['telefonoR'] ?>" placeholder="Teléfono móvil..." type="text">
                                                </div>
                                                <div class="form-group input-group col-md-12">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <input type="date" name="fechanacR" id="fechanac" value="<?php echo $params['fechanacR'] ?>" placeholder="Fecha de nacimiento..." class="form-control">
                                                </div>
                                                <div class="form-group input-group col-md-6">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                        </span>
                                                    </div>
                                                    <select id="selectProvincias" name="selectProvinciasR" class="custom-select" required>
                                                        <option selected="" value=""> -- Seleccionar Provincia -- </option>
                                                        <?php foreach ($params['provincias'] as $provincia) : ?>
                                                            <option value="<?= $provincia['idprovincia'] ?>"><?= $provincia['provincia'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group input-group col-md-6">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-city"></i>
                                                        </span>
                                                    </div>
                                                    <select name="selectPueblosR" value="<?php echo $params['selectPueblosR'] ?>" id="selectPueblos" class="custom-select" disabled>
                                                        <option> -- Seleccionar municipio -- </option>
                                                    </select>
                                                </div>
                                                <div class="form-group input-group col-md-6">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-venus"></i>
                                                        </span>
                                                    </div>
                                                    <select name="selectGeneroR" id="selectGenero" value="<?php echo $params['selectGeneroR'] ?>" class="custom-select">
                                                        <option value=""> -- Seleccionar Género --</option>
                                                        <option value="1">Masculino</option>
                                                        <option value="2">Femenino</option>
                                                        <option value="3">Otro</option>
                                                        <option value="4">No especificar</option>
                                                    </select>
                                                </div>
                                                <div class="form-group input-group col-md-6">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-heart"></i>
                                                        </span>
                                                    </div>
                                                    <select name="selectEstadoCivilR" id="selectEstadoCivil" value="<?php echo $params['selectEstadoCivilR'] ?>" class="custom-select">
                                                        <option value=''> -- Estado civil --</option>
                                                        <option value="1">Soltero/a</option>
                                                        <option value="2">Con pareja</option>
                                                        <option value="3">Casado/a</option>
                                                        <option value="4">Divorciado/a</option>
                                                        <option value="5">Viudo/a</option>
                                                        <option value="6">No especificar</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <button type="button" id="botonRegistrar" onclick="registrar()" class="btn btn-primary btn-block">Crear cuenta</button>
                                                </div>
                                                <?php
                                                if ($params['mensaje2']) {
                                                    echo "<div class='alert alert-success' role='alert' >" . $params['mensaje2'] . "</div>";
                                                } elseif ($params['error']) {
                                                    echo "<div class='alert alert-danger' role='alert' >" . $params['error'] . "</div>";
                                                }
                                                ?>
                                                <div class="form-group col-md-12">
                                                    <p class="text-center">¿Ya estás registrado? <a href="index.php?ctl=login">Inicie sesión</a> </p>
                                                </div>
                                            </div>
                                        </form>
                                    </article>
                                </div>
                            </div>
                            <!-- Fin del cuerpo del modal -->
                            <div class="container">
                                <div class="statusMessage justify-content-center align-items-center d-none"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Aquí termina por completo el modal de registro -->
            </div>
        </div>
    </div>

</body>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</html>