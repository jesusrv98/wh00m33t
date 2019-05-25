<?php
require_once __DIR__ . '/../../Config.php';
require_once __DIR__ . '/../../Model.php';

$m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, config::$mvc_bd_hostname);
header("Content-Type: text/html; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idUsuario1= $_POST['idUsuario1'];
    $idUsuario2 = $_POST['idUsuario2'];

    $consulta = $m->borrarAmigo($idUsuario1, $idUsuario2);

    if ($consulta) {
        $msg = "ok";
    } else {
        $msg = "error";
    }
    echo $msg;
}