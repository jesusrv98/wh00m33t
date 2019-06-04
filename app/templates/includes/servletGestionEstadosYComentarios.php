<?php
require_once __DIR__ . '/../../Config.php';
require_once __DIR__ . '/../../Model.php';

$m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, config::$mvc_bd_hostname);
header("Content-Type: text/html; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idEstado']) && !empty($_POST['idEstado']) && isset($_POST['idUsuario']) && !empty($_POST['idUsuario']) && $_POST['tipo'] == "estado") {
    $idUsuario = $_POST['idUsuario'];
    $idEstado = $_POST['idEstado'];

    $consulta = $m->borrarEstado($idEstado,$idUsuario);

    if (!$consulta) {
        $msg = "ok";
    } else {
        $msg = "error";
    }
    echo $msg;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idComentario']) && !empty($_POST['idComentario']) && isset($_POST['idUsuario']) && !empty($_POST['idUsuario']) && $_POST['tipo'] == 'comentario') {
    $idUsuario = $_POST['idUsuario'];
    $idComentario = $_POST['idComentario'];
    $idEstado = $_POST['idEstado'];

    $consulta = $m->borrarComentario($idComentario, $idUsuario);
    $consulta2 = $m->actualizarNotificaciones($idEstado, "comentarioEstado", $idUsuario);

    if (!$consulta) {
        $msg = "ok";
    } else {
        $msg = "error";
    }
    echo $msg;
}
