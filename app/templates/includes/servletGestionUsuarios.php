<?php
require_once __DIR__ . '/../../Config.php';
require_once __DIR__ . '/../../Model.php';

$m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, config::$mvc_bd_hostname);
header("Content-Type: text/html; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fotoPerfil']) && !empty($_POST['fotoPerfil']) && isset($_POST['idUsuario']) && !empty($_POST['idUsuario']) && $_POST['opcion'] == "cambiarFoto") {
    $idUsuario = $_POST['idUsuario'];
    $fotoPerfil = $_POST['fotoPerfil'];

    $consulta = $m->actualizarFotoPerfil($idUsuario,$fotoPerfil);

    if (!$consulta) {
        $msg = "ok";
    } else {
        $msg = "error";
    }
    echo $msg;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idUsuario']) && !empty($_POST['idUsuario']) && $_POST['opcion'] == 'borrar') {
    $idUsuario = $_POST['idUsuario'];

    $consulta = $m->borrarUsuario($idUsuario);

    if ($consulta) {
        $msg = "ok";
    } else {
        $msg = "error";
    }
    echo $msg;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idUsuario']) && !empty($_POST['idUsuario']) && $_POST['opcion'] == 'banear') {
    $idUsuario = $_POST['idUsuario'];

    $fecha = new DateTime("now");
    $consulta = $m->banearUsuario($idUsuario, $fecha->format('Y-m-d H:i:s'));

    if ($consulta) {
        $msg = "ok";
    } else {
        $msg = "error";
    }
    echo $msg;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idUsuario']) && !empty($_POST['idUsuario']) && $_POST['opcion'] == 'desbanear') {
    $idUsuario = $_POST['idUsuario'];

    $consulta = $m->desbanearUsuario($idUsuario);

    if ($consulta) {
        $msg = "ok";
    } else {
        $msg = "error";
    }
    echo $msg;
}
