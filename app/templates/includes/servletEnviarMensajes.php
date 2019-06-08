<?php
require_once __DIR__ . '/../../Config.php';
require_once __DIR__ . '/../../Model.php';

$m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, config::$mvc_bd_hostname);
header("Content-Type: text/html; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['tipo'] == "mensaje") {
    $mensajeNuevo = $_POST['mensajeNuevo'];
    $idUsuarioEnvia = $_POST['idUsuarioEnvia'];
    $idUsuarioRecibe = $_POST['idUsuarioRecibe'];
    $tipoNotificacion = "mensajePV";

    $fecha = new DateTime("now");

    $consulta = $m->enviarMensaje($idUsuarioEnvia, $idUsuarioRecibe, $mensajeNuevo, $fecha->format('Y-m-d H:i:s'));
    $consulta2 = $m->insertarNotificacionByTipo($idUsuarioEnvia, $tipoNotificacion, $idUsuarioRecibe);

    if ($consulta) {
        $msg = "ok";
    } else {
        $msg = "error";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['tipo'] == "actualizar") {
    $idUsuarioEnvia = $_POST['idUsuarioEnvia'];
    $idUsuarioRecibe = $_POST['idUsuarioRecibe'];
    $tipoNotificacion = "mensajePV";

    $consulta = $m->actualizarNotificaciones($idUsuarioEnvia, $tipoNotificacion, $idUsuarioRecibe);
    $consulta2 = $m->actualizarVistoMensajes($idUsuarioEnvia, $idUsuarioRecibe);
    
    if (!$consulta) {
        $msg = "ok";
    } else {
        $msg = "error";
    }
}

echo $msg;
