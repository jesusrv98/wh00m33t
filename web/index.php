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
    'insertar' => array('controller' => 'Controller', 'action' => 'mensajes'),
    'buscar' => array('controller' => 'Controller', 'action' => 'configuracion'),
    'contacto' => array('controller' => 'Controller', 'action' => 'contacto'),
    'signin' => array('controller' => 'Controller', 'action' => 'signin'),
    'logout' => array('controller' => 'Controller', 'action' => 'logout'),
    'login' => array('controller' => 'Controller', 'action' => 'login')
);

// Parseo de la ruta
if (isset($_GET['ctl'])) {
    if (isset($map[$_GET['ctl']])) {
        $ruta = $_GET['ctl'];
    } else {
        header('Status: 404 Not Found');
        echo '<html><body><h1>Error 404: No existe la ruta <i>' . $_GET['ctl'] . '</p></body></html>';
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
    echo '<html><body><h1>Error 404: El controlador <i>' . $controlador['controller'] . '->'  . $controlador['action'] . '</i> no existe</h1></body></html>';
}
 