<?php
require_once __DIR__ . '/../../Config.php';
require_once __DIR__ . '/../../Model.php';

$m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, config::$mvc_bd_hostname);
header("Content-Type: text/html; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $estadoNuevo= $_POST['estadoNuevo'];
    $estadoViejo = $_POST['estadoViejo'];
    $idUsuario = $_POST['idUsuario'];
    $fecha = new DateTime("now");


    if ($estadoNuevo != $estadoViejo) {
        $m->insertarEstadoNuevo($estadoNuevo, $fecha->format('Y-m-d H:i:s'), $idUsuario);
        $msg = "ok";
    } else {
        $msg = "errEstado";
    }
    echo $msg;
}
