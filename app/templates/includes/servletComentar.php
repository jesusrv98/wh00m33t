<?php
require_once __DIR__ . '/../../Config.php';
require_once __DIR__ . '/../../Model.php';

$m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, config::$mvc_bd_hostname);
header("Content-Type: text/html; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idEspacio= $_POST['idEspacio'];
    $textoComentario = $_POST['textoComentario'];
    $idUsuario = $_POST['idUsuario'];
    $tipo = $_POST['tipo'];
    $idUsuarioDirigido = $_POST['idUsuarioDirigido'];
    $fecha = new DateTime("now");


    $consulta = $m->insertarComentarioByIdEspacio($idUsuario, $idEspacio, $textoComentario, $fecha->format('Y-m-d H:i:s'));
    $consulta2 = $m->insertarNotificacionByTipo($idEspacio, $tipo, $idUsuarioDirigido);


    if ($consulta && $consulta2) {
        $msg = "error";
    } else {
        $msg = "ok";
    }
    echo $msg;
}