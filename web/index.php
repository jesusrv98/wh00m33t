<?php
// web/index.php

// carga del modelo y los controladores
require_once __DIR__ . '/../app/Config.php';
require_once __DIR__ . '/../app/Model.php';
require_once __DIR__ . '/../app/Controller.php';

// enrutamiento
$map = array(
    'inicio' => array('controller' => 'Controller', 'action' => 'inicio'),
    'perfil' => array('controller' => 'Controller', 'action' => 'perfil'),
    'mensajes' => array('controller' => 'Controller', 'action' => 'mensajes'),
    'buscar' => array('controller' => 'Controller', 'action' => 'configuracion'),
    'contacto' => array('controller' => 'Controller', 'action' => 'contacto'),
    'logout' => array('controller' => 'Controller', 'action' => 'logout'),
    'login' => array('controller' => 'Controller', 'action' => 'login'),
    'busqueda' => array('controller' => 'Controller', 'action' => 'busqueda'),
    'solicitudes' => array('controller' => 'Controller', 'action' => 'solicitudesAmistad'),
    'comentariosEstados' => array('controller' => 'Controller', 'action' => 'comentariosEstados'),
    'gestionAmigos' => array('controller' => 'Controller', 'action' => 'gestionAmigos'),
    'gestionUsuarios' => array('controller' => 'Controller', 'action' => 'gestionUsuarios')
    
);

// Parseo de la ruta
if (isset($_GET['ctl'])) {
    if (isset($map[$_GET['ctl']])) {
        $ruta = $_GET['ctl'];
    } else {
        header('Status: 404 Not Found');
        echo "<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous>";
        echo "<nav class='navbar navbar-expand-md sticky-top' style='background: linear-gradient(#93ECFF,white); color: #027F6D'>
                <a class='navbar-brand' style='color:#027F6D' href='index.php?ctl=inicio'><img src='images/logo.png' width='100' height='100' class='d-inline-block align-top' alt='Logo' /></a>
                </nav>";
        echo "<html><body><h1 class='display-4 text-center' style='color: #027F6D'>Error 404: No existe la ruta <i>" . $_GET['ctl'] . "</h1></body></html>";
        exit;
    }
} else {
    $ruta = 'inicio';
}

$controlador = $map[$ruta];
// Ejecución del controlador asociado a la ruta

if (method_exists($controlador['controller'], $controlador['action'])) {
    call_user_func(array(new $controlador['controller'], $controlador['action']));
} else {

    header('Status: 404 Not Found');
    echo "<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous>";
    echo "<nav class='navbar navbar-expand-md sticky-top' style='background: linear-gradient(#93ECFF,white); color: #027F6D'>
            <a class='navbar-brand' style='color:#027F6D' href='index.php?ctl=inicio'><img src='images/logo.png' width='100' height='100' class='d-inline-block align-top' alt='Logo' /></a>
        </nav>";
    echo "<html><body><h1 class='display-4 text-center' style='color: #027F6D'>Error 404: El controlador <i> " . $controlador['controller'] . "->"  . $controlador['action'] . "</i> no existe</h1></body></html>";
}
