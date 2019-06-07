<?php
require_once __DIR__ . '/../../Config.php';
require_once __DIR__ . '/../../Model.php';

$m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, config::$mvc_bd_hostname);
header("Content-Type: text/html; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idUsuario']) && !empty($_POST['idUsuario']) && isset($_POST['idFoto']) && !empty($_POST['idFoto']) && $_POST['tipo'] == "meGusta") {
    $idUsuario = $_POST['idUsuario'];
    $idFoto = $_POST['idFoto'];

    $consulta = $m->setMeGusta($idFoto,$idUsuario);

    if (!$consulta) {
        $msg = "ok";
    } else {
        $msg = "error";
    }
    echo $msg;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idUsuario']) && !empty($_POST['idUsuario']) && isset($_POST['idFoto']) && !empty($_POST['idFoto']) && $_POST['tipo'] == "noMeGusta") {
    $idUsuario = $_POST['idUsuario'];
    $idFoto = $_POST['idFoto'];

    $consulta = $m->borrarMeGusta($idFoto, $idUsuario);

    if (!$consulta) {
        $msg = "ok";
    } else {
        $msg = "error";
    }
    echo $msg;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idFoto']) && !empty($_POST['idFoto']) && $_POST['tipo'] == "borrarFoto") {
    $idFoto = $_POST['idFoto'];
    $rutaFoto = $_POST['rutaFoto'];

    
    $consulta = $m->borrarFoto($idFoto);
    unlink("../../../web/".$rutaFoto);

    if ($consulta) {
        $msg = "ok";
    } else {
        $msg = "error";
    }
    echo $msg;
}