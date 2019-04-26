<?php
session_start();
class Controller
{

    public function inicio()
    {
     $params = array(
            'mensaje' => 'Bienvenido al curso de symfony 1.4',
            'fecha' => date('d-m-Y'),
            'resultado' => $_SESSION['usuarioconectado']
        );
        require __DIR__ . '/templates/inicio.php';
    }

    public function listar()
    {
        $m = new Model(
            Config::$mvc_bd_nombre,
            Config::$mvc_bd_usuario,
            Config::$mvc_bd_clave,
            Config::$mvc_bd_hostname
        );

        $params = array(
            'alimentos' => $m->dameAlimentos(),
        );

        require __DIR__ . '/templates/mostrarAlimentos.php';
    }

    public function eliminar()
    {
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, config::$mvc_bd_hostname);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $borrar = $_POST['oculto'];
            $m->eliminarAlimento($borrar);
        }

        $params = array(
            'alimentos' => $m->dameAlimentos(),
        );
        require __DIR__ . '/templates/eliminarAlimentos.php';
    }
    public function editar()
    {
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, config::$mvc_bd_hostname);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['oculto'];
            $alimento = $_POST['alimento'];
            $energia = $_POST['energia'];
            $proteina = $_POST['proteina'];
            $hidratocarbono = $_POST['hidratocarbono'];
            $fibra = $_POST['fibra'];
            $grasa = $_POST['grasa'];
            $m->editarAlimento($id, $alimento, $energia, $proteina, $hidratocarbono, $fibra, $grasa);
        }

        $params = array(
            'alimentos' => $m->dameAlimentos(),
        );
        require __DIR__ . '/templates/editarAlimentos.php';
    }

    public function wiki()
    {
        $m = new Model(
            Config::$mvc_bd_nombre,
            Config::$mvc_bd_usuario,
            Config::$mvc_bd_clave,
            Config::$mvc_bd_hostname
        );

        $params = array(
            'alimentos' => $m->dameAlimentos(),
        );

        require __DIR__ . '/templates/wikiAlimentos.php';
    }
    public function login()
    {
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        $params = array(
            'email' => '',
            'password' => '',
            'resultado' => array(),
            'provincias' => $m->listaProvincias(),
            'mensaje' => '',
            'mensaje2' => '',
            'error' => '',
            'nombreR' => '',
            'apellidosR' => '',
            'correoR' => '',
            'contrasenaR' => '',
            'contrasenaR2' => '',
            'telefonoR' => '',
            'fechanacR' => '',
            'selectPueblosR' => '',
            'selectGeneroR' => '',
            'selectEstadoCivilR' => ''
        );


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $params['email'] = $_POST['email'];
            $params['password'] = $_POST['password'];

            $verificacion = $m->verificar($params['password'], $params['email']);

            if($verificacion == 1) {
                $params['resultado'] = $m->buscarUsuario($params['email'], $params['password']);
                $_SESSION['usuarioconectado'] = $params['resultado'];
            } else{
                $params['mensaje'] = "No existe ese usuario-password en la base de datos.";
            }

            
            // implode($params['resultado'])

            // if (count($params['resultado']) == 0) {
            //     $params['mensaje'] = "No existe ese usuario-password en la base de datos: $verificacion ";
            // } else {
            //     $_SESSION['usuarioconectado'] = $params['resultado'];
            // }
        }
        require __DIR__ . '/templates/login.php';
    }
    public function signin()
    {
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        $params = array(
            'email' => '',
            'password' => '',
            'resultado' => array(),
            'provincias' => $m->listaProvincias(),
            'mensaje' => '',
            'mensaje2' => '',
            'error' => '',
            'nombreR' => '',
            'apellidosR' => '',
            'correoR' => '',
            'contrasenaR' => '',
            'contrasenaR2' => '',
            'telefonoR' => '',
            'fechanacR' => '',
            'selectPueblosR' => '',
            'selectGeneroR' => '',
            'selectEstadoCivilR' => ''
        );

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $params['nombre'] = $_POST['nombreR'];
            $params['apellidos'] = $_POST['apellidosR'];
            $params['correo'] = $_POST['correoR'];
            $params['contrasena'] = $_POST['contrasenaR'];
            $params['contrasena2'] = $_POST['contrasena2R'];
            $params['telefono'] = $_POST['telefonoR'];
            $params['fechanac'] = $_POST['fechanacR'];
            $params['selectPueblos'] = $_POST['selectPueblosR'];
            $params['selectGenero'] = $_POST['selectGeneroR'];
            $params['selectEstadoCivil'] = $_POST['selectEstadoCivilR'];
            $params['resultado'] = $m->buscarSoloUsuario($_POST['correoR']);

            $genero =  $params['selectGenero'];
            $estadocivil = $params['selectEstadoCivil'];

            switch ($params['selectGenero']) {
                case '1':
                    $genero = "Masculino";
                    break;
                case '2':
                    $genero = "Femenino";
                    break;
                case '3':
                    $genero = "Otro";
                    break;
                default:
                    $genero = "No especificado";
                    break;
            }

            switch ($params['selectEstadoCivil']) {
                case '1':
                    if ($genero == "Masculino") {
                        $estadocivil = "Soltero";
                    } elseif ($genero == "Femenino") {
                        $estadocivil = "Soltera";
                    } else {
                        $estadocivil = "Solterx";
                    }
                    break;
                case '2':
                    $estadocivil = "Con pareja";
                    break;
                case '3':
                    if ($genero == "Masculino") {
                        $estadocivil = "Casado";
                    } elseif ($genero == "Femenino") {
                        $estadocivil = "Casada";
                    } else {
                        $estadocivil = "Casadx";
                    }
                    break;
                case '4':
                    if ($genero == "Masculino") {
                        $estadocivil = "Divorciado";
                    } elseif ($genero == "Femenino") {
                        $estadocivil = "Divorciada";
                    } else {
                        $estadocivil = "Divorciadx";
                    }
                    break;
                case '5':
                    if ($genero == "Masculino") {
                        $estadocivil = "Viudo";
                    } elseif ($genero == "Femenino") {
                        $estadocivil = "Viuda";
                    } else {
                        $estadocivil = "Viudx";
                    }
                    break;
                default:
                    $estadocivil = "No especificar";
                    break;
            }

            if ($params['contrasena'] == $params['contrasena2']) {
                if (count($params['resultado']) > 0) {
                    $params['error'] = "Utilice un correo electrónico que no esté ya registrado.";
                } else {
                    $m->insertarUsuario($_POST['correoR'], $_POST['contrasenaR'], $_POST['nombreR'], $_POST['apellidosR'], $_POST['fechanacR'], $genero, $_POST['telefonoR'], $_POST['selectPueblosR'], $estadocivil);
                    $params['mensaje2'] = "El usuario con correo: " . $params['correo'] . " ha sido registrado satisfactoriamente a WhoMeet, ¡Disfruta!";
                }
            } else {
                $params['error'] = "Las contraseñas deben coincidir.";
            }
        }
        require __DIR__ . '/templates/login.php';
    }


    public function logout()
    {
        session_destroy();
        Location('../web/index.php');
    }

    public function insertar()
    {
        $params = array(
            'nombre' => '',
            'energia' => '',
            'proteina' => '',
            'hc' => '',
            'fibra' => '',
            'grasa' => '',
        );

        $m = new Model(
            Config::$mvc_bd_nombre,
            Config::$mvc_bd_usuario,
            Config::$mvc_bd_clave,
            Config::$mvc_bd_hostname
        );

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // comprobar campos formulario
            if ($m->validarDatos(
                $_POST['nombre'],
                $_POST['energia'],
                $_POST['proteina'],
                $_POST['hc'],
                $_POST['fibra'],
                $_POST['grasa']
            )) {
                $m->insertarAlimento(
                    $_POST['nombre'],
                    $_POST['energia'],
                    $_POST['proteina'],
                    $_POST['hc'],
                    $_POST['fibra'],
                    $_POST['grasa']
                );
                header('Location: index.php?ctl=listar');
            } else {
                $params = array(
                    'nombre' => $_POST['nombre'],
                    'energia' => $_POST['energia'],
                    'proteina' => $_POST['proteina'],
                    'hc' => $_POST['hc'],
                    'fibra' => $_POST['fibra'],
                    'grasa' => $_POST['grasa'],
                );
                $params['mensaje'] = 'No se ha podido insertar el alimento. Revisa el formulario';
            }
        }

        require __DIR__ . '/templates/formInsertar.php';
    }

    public function buscarPorNombre()
    {
        $params = array(
            'nombre' => '',
            'resultado' => array(),
        );

        $m = new Model(
            Config::$mvc_bd_nombre,
            Config::$mvc_bd_usuario,
            Config::$mvc_bd_clave,
            Config::$mvc_bd_hostname
        );

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $params['nombre'] = $_POST['nombre'];
            $params['resultado'] = $m->buscarAlimentosPorNombre($_POST['nombre']);
        }

        require __DIR__ . '/templates/buscarPorNombre.php';
    }
    public function buscarPorEnergia()
    {
        $params = array(
            'energia' => '',
            'resultado' => array(),
            'mensaje' => ''
        );

        $m = new Model(
            Config::$mvc_bd_nombre,
            Config::$mvc_bd_usuario,
            Config::$mvc_bd_clave,
            Config::$mvc_bd_hostname
        );

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $params['energia'] = $_POST['energia'];
            $params['resultado'] = $m->buscarAlimentosPorEnergia($_POST['energia']);
            if (count($params['resultado']) == 0)
                $params['mensaje'] = 'No se han encontrado alimentos con la energía indicada';
        }

        require __DIR__ . '/templates/buscarPorEnergia.php';
    }

    public function buscarAlimentosCombinada()
    {
        $params = array(
            'energia' => '',
            'nombre' => '',
            'resultado' => array(),
            'mensaje' => ''
        );

        $m = new Model(
            Config::$mvc_bd_nombre,
            Config::$mvc_bd_usuario,
            Config::$mvc_bd_clave,
            Config::$mvc_bd_hostname
        );

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $params['energia'] = $_POST['energia'];
            $params['nombre'] = $_POST['nombre'];
            $params['resultado'] = $m->buscarAlimentosCombinada($_POST['energia'], $_POST['nombre']);
            if (count($params['resultado']) == 0)
                $params['mensaje'] = 'No se han encontrado alimentos con la energía y nombre indicados';
        }

        require __DIR__ . '/templates/buscarCombinada.php';
    }


    public function ver()
    {
        if (!isset($_GET['id'])) {
            throw new Exception('Página no encontrada');
        }

        $id = $_GET['id'];

        $m = new Model(
            Config::$mvc_bd_nombre,
            Config::$mvc_bd_usuario,
            Config::$mvc_bd_clave,
            Config::$mvc_bd_hostname
        );

        $alimento = $m->dameAlimento($id);

        $params = $alimento;

        require __DIR__ . '/templates/verAlimento.php';
    }
}
