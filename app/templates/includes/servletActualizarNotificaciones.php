<?php
require_once __DIR__ . '/../../Config.php';
require_once __DIR__ . '/../../Model.php';

$m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, config::$mvc_bd_hostname);
header("Content-Type: text/html; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idEspacio = $_POST['idEspacio'];
    $idUsuario = $_POST['idUsuario'];
    $tipo = $_POST['tipo'];

    $consulta = $m->actualizarNotificaciones($idEspacio,$tipo,$idUsuario);

    if (!$consulta) {
        $msg = "ok";
    } else {
        $msg = "error";
    }
    echo $msg;
}
