<?php
session_start();
class Controller
{

    public function inicio()
    {
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, config::$mvc_bd_hostname);

        $correo = implode(array_column($_SESSION['usuarioconectado'], "correo"));
        $arrayUsuario = $m->buscarSoloUsuario($correo);
        $visitas = implode(array_column($arrayUsuario, "visitas"));
        $idUsuario = implode(array_column($arrayUsuario, "id"));
        $correoUsuario = implode(array_column($arrayUsuario, "correo"));

        $arrayNotificaciones = $m->findNotificaciones($idUsuario);
        $arrayMensajesPrivados = $m->findCountMensajesPvById($idUsuario);
        $arrayPeticionesAmistad = $m->findCountPeticionesById($idUsuario);
        $arrayComentarios = $m->findCountComentariosById($idUsuario);
        $arrayComentariosFotos = $m->findCountComentariosFotosById($idUsuario);
        $countNotificaciones = implode(array_column($arrayNotificaciones, "count(*)"));
        $countMensajesPV = implode(array_column($arrayMensajesPrivados, "count(*)"));
        $countPeticiones = implode(array_column($arrayPeticionesAmistad, "count(*)"));
        $countComentarios = implode(array_column($arrayComentarios, "count(*)"));
        $countComentariosFotos = implode(array_column($arrayComentariosFotos, "count(*)"));

        $arrayEstado = $m->findEstadoById($idUsuario);
        $arrayPublicaciones = $m->findEstadosAmigos($correoUsuario);

        $estadoActualTexto = implode(array_column($arrayEstado, "estadoCuerpo"));
        $estadoActualFecha = implode(array_column($arrayEstado, "fecha"));

        $arrayUsuariosConectados = $m->findUsuariosConectado();
        $countUsuariosConectado = $m->countfindUsuariosConectado();

        if ($estadoActualFecha != "0000-00-00 00:00:00") {
            $fechaActual = new DateTime('now');
            $fechaDeEstado = new DateTime($estadoActualFecha);
            $diff = $fechaActual->diff($fechaDeEstado);

            $estadoActualFechaSegundos = $diff->s;
            $estadoActualFechaMinutos = $diff->i;
            $estadoActualFechaHoras = $diff->h;
            $estadoActualFechaDias = $diff->d;

            if ($estadoActualFechaDias > 7) {
                $estadoActualFecha = "más de una semana.";
            } else {
                if ($estadoActualFechaDias == 0) {
                    if ($estadoActualFechaHoras < 1) {
                        if ($estadoActualFechaHoras < 1) {
                            if ($estadoActualFechaMinutos < 1) {
                                if ($estadoActualFechaSegundos < 2) {
                                    if ($estadoActualFechaSegundos == 0) {
                                        $estadoActualFecha = $estadoActualFechaSegundos . " segundos.";
                                    } else {
                                        $estadoActualFecha = $estadoActualFechaSegundos . " segundo.";
                                    }
                                } else {
                                    $estadoActualFecha = $estadoActualFechaSegundos . " segundos.";
                                }
                            } else {
                                if ($estadoActualFechaMinutos < 2) {
                                    $estadoActualFecha = $estadoActualFechaMinutos . " minuto.";
                                } else {
                                    $estadoActualFecha = $estadoActualFechaMinutos . " minutos.";
                                }
                            }
                        } else {
                            $estadoActualFecha = $estadoActualFechaHoras . " hora.";
                        }
                    } else {
                        if ($estadoActualFechaHoras < 2) {
                            $estadoActualFecha = $estadoActualFechaHoras . " hora.";
                        } else {
                            $estadoActualFecha = $estadoActualFechaHoras . " horas.";
                        }
                    }
                } elseif ($estadoActualFechaDias > 0) {
                    if ($estadoActualFechaDias < 2) {
                        $estadoActualFecha = $estadoActualFechaDias . " día.";
                    } else {
                        $estadoActualFecha = $estadoActualFechaDias . " días.";
                    }
                }
            }
        } else {
            $estadoActualFecha = null;
        }


        $params = array(
            'visitas' => $visitas,
            'existeNotificaciones' => $countNotificaciones,
            'countMensajesPV' => $countMensajesPV,
            'countPeticiones' => $countPeticiones,
            'countComentarios' => $countComentarios,
            'countComentariosFotos' => $countComentariosFotos,
            'estadoActual' => $estadoActualTexto,
            'estadoActualFecha' => $estadoActualFecha,
            'publicacionesAmigos' => $arrayPublicaciones,
            'idUsuario' => $idUsuario,
            'nuevoEstado' => '',
            'nombreBusqueda' =>'',
            'countUsuariosConectados' => $countUsuariosConectado,
            'listaUsuariosConectados' => $arrayUsuariosConectados

        );

        require __DIR__ . '/templates/inicio.php';
    }
   

    public function busqueda() {
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombreBusqueda'];
        }

        $correo = implode(array_column($_SESSION['usuarioconectado'], "correo"));
        $arrayUsuario = $m->buscarSoloUsuario($correo);
        $idUsuario = implode(array_column($arrayUsuario, "id"));

        $listaUsuarios = $m->findUsuariosByNombre(trim($nombre));
        $countBusqueda = $m->countfindUsuariosByNombre(trim($nombre));

        $arrayMensajesPrivados = $m->findCountMensajesPvById($idUsuario);
        $countMensajesPV = implode(array_column($arrayMensajesPrivados, "count(*)"));

        $mensaje = '';
        if($nombre == ""){
            if($countBusqueda >=2) {
                $mensaje ="Se han encontrado <strong>".$countBusqueda."</strong> resultados.";
            }elseif ($countBusqueda == 1) {
                $mensaje ="Solo hemos encontrado <strong>".$countBusqueda."</strong> resultado.";
            }else{
                $mensaje ="Oh, hemos encontrado <strong>".$countBusqueda."</strong> resultados para tu busqueda.";
            } 
        }else{
            if($countBusqueda >=2) {
                $mensaje ="Se han encontrado ".$countBusqueda." resultados.";
            }elseif ($countBusqueda == 1) {
                $mensaje ="Solo hemos encontrado <strong>".$countBusqueda."</strong> resultado.";
            }else{
                $mensaje ="Oh, hemos encontrado <strong>".$countBusqueda."</strong> resultados para tu busqueda.";
            } 
        }

        $params = array(
            'countMensajesPV' => $countMensajesPV,
            'nombreBusqueda' =>'',
            'busqueda' => $listaUsuarios,
            'palabraBuscada' => trim($nombre),
            'mensajeBusqueda' => $mensaje,
            'idUsuarioConectado' => $idUsuario
        );

        

        require __DIR__ . '/templates/busquedaUsuarios.php';
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
            $params['resultado'] = $m->buscarSoloUsuario($params['email']);
            $verificacion = $m->verificar($params['password'], $params['email']);
            $idUsuario = $m->findIdUsuario($params['correo']);

            if ($verificacion == 1) {
                $_SESSION['usuarioconectado'] = $params['resultado'];
                $correo = $params['email'];
                $arrayUsuario = $m->buscarSoloUsuario($correo);
                $idUsuario = implode(array_column($arrayUsuario, "id"));
                $m->setConectado($idUsuario);
            } else {
                $params['mensaje'] = "No existe ese usuario-password en la base de datos";
            }
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
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);
        $correo = implode(array_column($_SESSION['usuarioconectado'], "correo"));
        $arrayUsuario = $m->buscarSoloUsuario($correo);
        $idUsuario = implode(array_column($arrayUsuario, "id"));
        $m->setDesconectado($idUsuario);
        session_destroy();
        header('Location: ../web/index.php?ctl=login');
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
