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
            'nombreBusqueda' => '',
            'countUsuariosConectados' => $countUsuariosConectado,
            'listaUsuariosConectados' => $arrayUsuariosConectados

        );

        require __DIR__ . '/templates/inicio.php';
    }


    public function busqueda()
    {
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombreBusqueda'];
        } else {
            $nombre = "";
        }


        $correo = implode(array_column($_SESSION['usuarioconectado'], "correo"));
        $arrayUsuario = $m->buscarSoloUsuario($correo);
        $idUsuario = implode(array_column($arrayUsuario, "id"));

        $listaUsuarios = $m->findUsuariosByNombre(trim($nombre));
        $countBusqueda = $m->countfindUsuariosByNombre(trim($nombre));

        $arrayMensajesPrivados = $m->findCountMensajesPvById($idUsuario);
        $countMensajesPV = implode(array_column($arrayMensajesPrivados, "count(*)"));

        $mensaje = '';
        if ($nombre == "") {
            if ($countBusqueda >= 2) {
                $mensaje = "Se han encontrado <strong>" . $countBusqueda . "</strong> resultados.";
            } elseif ($countBusqueda == 1) {
                $mensaje = "Solo hemos encontrado <strong>" . $countBusqueda . "</strong> resultado.";
            } else {
                $mensaje = "Oh, hemos encontrado <strong>" . $countBusqueda . "</strong> resultados para tu busqueda.";
            }
        } else {
            if ($countBusqueda >= 2) {
                $mensaje = "Se han encontrado " . $countBusqueda . " resultados.";
            } elseif ($countBusqueda == 1) {
                $mensaje = "Solo hemos encontrado <strong>" . $countBusqueda . "</strong> resultado.";
            } else {
                $mensaje = "Oh, hemos encontrado <strong>" . $countBusqueda . "</strong> resultados para tu busqueda.";
            }
        }

        $params = array(
            'countMensajesPV' => $countMensajesPV,
            'nombre' => '',
            'nombreBusqueda' => '',
            'busqueda' => $listaUsuarios,
            'palabraBuscada' => trim($nombre),
            'mensajeBusqueda' => $mensaje,
            'idUsuarioConectado' => $idUsuario
        );



        require __DIR__ . '/templates/busquedaUsuarios.php';
    }

    public function solicitudesAmistad()
    {
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombreBusqueda'];
        } else {
            $nombre = "";
        }


        $correo = implode(array_column($_SESSION['usuarioconectado'], "correo"));
        $arrayUsuario = $m->buscarSoloUsuario($correo);
        $idUsuario = implode(array_column($arrayUsuario, "id"));

        $arrayPeticionesAmistad = $m->findCountPeticionesById($idUsuario);
        $listaSolicitudes = $m->findSolicitudesAmistad($idUsuario);
        $countPeticiones = implode(array_column($arrayPeticionesAmistad, "count(*)"));

        $arrayMensajesPrivados = $m->findCountMensajesPvById($idUsuario);
        $countMensajesPV = implode(array_column($arrayMensajesPrivados, "count(*)"));


        $params = array(
            'countMensajesPV' => $countMensajesPV,
            'solicitudes' => $listaSolicitudes,
            'countPeticiones' => $countPeticiones,
            'nombre' => '',
            'nombreBusqueda' => '',
            'idUsuarioConectado' => $idUsuario
        );



        require __DIR__ . '/templates/solicitudesAmistad.php';
    }

    function formatearFecha($fechaEntrada)
    {
        $estadoActualFecha = $fechaEntrada;
        if ($estadoActualFecha != "0000-00-00 00:00:00") {
            $fechaActual = new DateTime('now');
            $fechaDeEstado = new DateTime($estadoActualFecha);
            $diff = $fechaActual->diff($fechaDeEstado);

            $estadoActualFechaSegundos = $diff->s;
            $estadoActualFechaMinutos = $diff->i;
            $estadoActualFechaHoras = $diff->h;
            $estadoActualFechaDias = $diff->d;

            $textoFecha = "hola";
            if ($estadoActualFechaDias > 7) {
                $textoFecha =  "Hace más de una semana.";
            } else {
                if ($estadoActualFechaDias == 0) {
                    if ($estadoActualFechaHoras < 1) {
                        if ($estadoActualFechaHoras < 1) {
                            if ($estadoActualFechaMinutos < 1) {
                                if ($estadoActualFechaSegundos < 2) {
                                    if ($estadoActualFechaSegundos == 0) {
                                        $textoFecha =  "Hace " . $estadoActualFechaSegundos . " segundos.";
                                    } else {
                                        $textoFecha =  "Hace " . $estadoActualFechaSegundos . " segundo.";
                                    }
                                } else {
                                    $textoFecha =  "Hace " . $estadoActualFechaSegundos . " segundos.";
                                }
                            } else {
                                if ($estadoActualFechaMinutos < 2) {
                                    $textoFecha =  "Hace " . $estadoActualFechaMinutos . " minuto.";
                                } else {
                                    $textoFecha =  "Hace " . $estadoActualFechaMinutos . " minutos.";
                                }
                            }
                        } else {
                            $textoFecha =  "Hace " . $estadoActualFechaHoras . " hora.";
                        }
                    } else {
                        if ($estadoActualFechaHoras < 2) {
                            $textoFecha = "Hace " . $estadoActualFechaHoras . " hora.";
                        } else {
                            $textoFecha = "Hace " . $estadoActualFechaHoras . " horas.";
                        }
                    }
                } elseif ($estadoActualFechaDias > 0) {
                    if ($estadoActualFechaDias < 2) {
                        $textoFecha = "Hace " . $estadoActualFechaDias . " día.";
                    } else {
                        $textoFecha = "Hace " . $estadoActualFechaDias . " días.";
                    }
                }
            }
        }
        return $textoFecha;
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
            $params['resultado'] = $m->verificar($params['password'], $params['email']);

            if ($params['resultado']) {
                $correo = $params['email'];
                $arrayUsuario = $m->buscarSoloUsuario($correo);
                $_SESSION['usuarioconectado'] = $arrayUsuario;
                $idUsuario = implode(array_column($arrayUsuario, "id"));
                $m->setConectado($idUsuario);
            } else {
                $params['mensaje'] = "Usuario o contraseña incorrecta.";
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
}