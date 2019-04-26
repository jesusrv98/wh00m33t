<?php
    require_once __DIR__ . '/../../Config.php';
    require_once __DIR__ . '/../../Model.php';
    
    $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, config::$mvc_bd_hostname);
    header("Content-Type: text/html; charset=utf-8");
    
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $params['nombre'] = $_GET['nombre'];
        $params['apellidos'] = $_GET['apellidos'];
        $params['correo'] = $_GET['correo'];
        $params['contrasena'] = $_GET['contrasena'];
        $params['contrasena2'] = $_GET['contrasena2'];
        $params['telefono'] = $_GET['telefono'];
        $params['fechanac'] = $_GET['fechanac'];
        $params['selectPueblos'] = $_GET['selectPueblos'];
        $params['selectGenero'] = $_GET['selectGenero'];
        $params['selectEstadoCivil'] = $_GET['selectEstadoCivil'];
        $params['resultado'] = $m->buscarSoloUsuario($_GET['correo']);

        $genero =  $params['selectGenero'];
        $estadocivil = $params['selectEstadoCivil'];

        switch ($params['selectGenero']) {
            case '1':
                $genero = "Masculino";
                break;
            case '2':
                $genero = "Femenino";
                break;
            case '3':
                $genero = "Otro";
                break;
            default:
                $genero = "No especificado";
                break;
        }

        switch ($params['selectEstadoCivil']) {
            case '1':
                if ($genero == "Masculino") {
                    $estadocivil = "Soltero";
                } elseif ($genero == "Femenino") {
                    $estadocivil = "Soltera";
                } else {
                    $estadocivil = "Solterx";
                }
                break;
            case '2':
                $estadocivil = "Con pareja";
                break;
            case '3':
                if ($genero == "Masculino") {
                    $estadocivil = "Casado";
                } elseif ($genero == "Femenino") {
                    $estadocivil = "Casada";
                } else {
                    $estadocivil = "Casadx";
                }
                break;
            case '4':
                if ($genero == "Masculino") {
                    $estadocivil = "Divorciado";
                } elseif ($genero == "Femenino") {
                    $estadocivil = "Divorciada";
                } else {
                    $estadocivil = "Divorciadx";
                }
                break;
            case '5':
                if ($genero == "Masculino") {
                    $estadocivil = "Viudo";
                } elseif ($genero == "Femenino") {
                    $estadocivil = "Viuda";
                } else {
                    $estadocivil = "Viudx";
                }
                break;
            default:
                $estadocivil = "No especificar";
                break;
        }

        if ($params['contrasena'] == $params['contrasena2']) {
            if (count($params['resultado']) > 0) {
                $msg = "errEmail";
            } else {
                $contrasenaEncriptada = $m -> encriptar($params['contrasena']);
                $m->insertarUsuario($_GET['correo'], $contrasenaEncriptada, $_GET['nombre'], $_GET['apellidos'], $_GET['fechanac'], $genero, $_GET['telefono'], $_GET['selectPueblos'], $estadocivil);
                $msg = "ok";
            }
        } else {
            $msg = "errPassword";
        }
        echo $msg;
    }
?>