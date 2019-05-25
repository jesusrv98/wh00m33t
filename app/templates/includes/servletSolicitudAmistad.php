<?php
require_once __DIR__ . '/../../Config.php';
require_once __DIR__ . '/../../Model.php';

$m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, config::$mvc_bd_hostname);
header("Content-Type: text/html; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idSolicitante= $_POST['idSolicitante'];
    $idSolicitado = $_POST['idSolicitado'];
    $idEspacio = $idSolicitante;
    $tipo = $_POST['tipo'];

    $consulta = $m->insertarSolicitudAmistad($idSolicitante,$idSolicitado,$tipo);
    $consulta2 = $m->insertarNotificacionByTipo($idEspacio, $tipo, $idSolicitado);
    
    $consulta3 = $m->agregarAmigo($idSolicitado, $idSolicitante);


    if ($consulta && $consulta2) {
        $msg = "error";
    } else {
        $msg = "ok";
    }
    echo $msg;
}