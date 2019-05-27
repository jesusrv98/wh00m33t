<?php
require_once __DIR__ . '/../../Config.php';
require_once __DIR__ . '/../../Model.php';

$m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, config::$mvc_bd_hostname);
header("Content-Type: text/html; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idSolicitud = $_POST['idSolicitud'];
    $idUsuarioAceptante = $_POST['idUsuario'];
    $idUsuarioSolicitante = $_POST['idNuevoAmigo'];
    $tipo = $_POST['tipo'];
    $tipoNotificacion = "peticionAmistad";


    switch ($tipo) {
        case 'aceptar':
            $consulta = $m->actualizarSolicitudAmistad($idSolicitud,1);
            $consulta2 = $m->agregarAmigo($idUsuarioSolicitante, $idUsuarioAceptante);
            $consulta3 = $m->actualizarNotificaciones($idUsuarioSolicitante, $tipoNotificacion, $idUsuarioAceptante);
            break;
        case 'cancelar':
            $consulta = $m->actualizarSolicitudAmistad($idSolicitud,1);
            $consulta2 = $m->borrarAmigo($idUsuarioAceptante, $idUsuarioSolicitante);
            $consulta3 = $m->actualizarNotificaciones($idUsuarioSolicitante, $tipoNotificacion, $idUsuarioAceptante);
            break;
        case 'bloquear':

            break;
    }

    if ($consulta && $consulta2 && $consulta3) {
        $msg = "ok";
    } else {
        $msg = "error";
    }
    echo $msg;
}
