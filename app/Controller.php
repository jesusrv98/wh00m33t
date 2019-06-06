<?php
session_start();
class Controller
{

    public function inicio()
    {
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, config::$mvc_bd_hostname);

        if (!isset($_SESSION['usuarioconectado'])) {
            header("Location: index.php?ctl=login");
        }
        $correo = implode(array_column($_SESSION['usuarioconectado'], "correo"));
        $arrayUsuario = $m->buscarSoloUsuario($correo);
        $visitas = implode(array_column($arrayUsuario, "visitas"));
        $idUsuario = implode(array_column($arrayUsuario, "id"));
        $correoUsuario = implode(array_column($arrayUsuario, "correo"));
        $fotoPerfil = implode(array_column($arrayUsuario, "fotoPerfil"));

        $baneado = $m->isBaneado($idUsuario);

        $arrayNotificaciones = $m->findNotificaciones($idUsuario);
        $arrayMensajesPrivados = $m->findCountMensajesPvById($idUsuario);
        $arrayPeticionesAmistad = $m->findCountPeticionesById($idUsuario);
        $arrayComentarios = $m->findCountComentariosById($idUsuario);
        $arrayComentariosEstados = $m->findCountComentariosEstadosById($idUsuario);
        $arrayComentariosFotos = $m->findCountComentariosFotosById($idUsuario);
        $countNotificaciones = implode(array_column($arrayNotificaciones, "count(*)"));
        $countMensajesPV = implode(array_column($arrayMensajesPrivados, "count(*)"));
        $countPeticiones = implode(array_column($arrayPeticionesAmistad, "count(*)"));
        $countComentarios = implode(array_column($arrayComentarios, "count(*)"));
        $countComentariosEstados = implode(array_column($arrayComentariosEstados, "count(*)"));
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
        $mensaje = null;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $carpetaDestino = "images/";

            # si hay algun archivo que subir
            if (isset($_FILES["fotoSubir"]) && $_FILES["fotoSubir"]["name"][0]) {

                # si es un formato de imagen
                if ($_FILES["fotoSubir"]["type"][0] == "image/jpeg" || $_FILES["fotoSubir"]["type"][0] == "image/pjpeg" || $_FILES["fotoSubir"]["type"][0] == "image/gif" || $_FILES["fotoSubir"]["type"][0] == "image/png") {
                    # si exsite la carpeta o se ha creado
                    if (file_exists($carpetaDestino) || @mkdir($carpetaDestino)) {
                        $origen = $_FILES["fotoSubir"]["tmp_name"][0];
                        $destino = $carpetaDestino . $idUsuario . time() . $_FILES["fotoSubir"]["name"][0];
                        if (@move_uploaded_file($origen, $destino)) {
                            $imgh = $this->icreate($destino);
                            $imgr = $this->simpleresize($imgh, 400, 400);
                            $m->actualizarFotoPerfil($idUsuario, $idUsuario . time() . $_FILES["fotoSubir"]["name"][0]);
                            $mensaje = "Foto cambiada correctamente";
                        } else {
                            $mensaje = "<br>No se ha podido mover el archivo: " . $_FILES["fotoSubir"]["name"][0];
                        }
                    } else {
                        $mensaje = "<br>No se ha podido crear la carpeta: " . $carpetaDestino;
                    }
                } else {
                    $mensaje = "<br>" . $_FILES["fotoSubir"]["name"][0] . " - NO es imagen jpg, png o gif";
                }
            } else {
                $mensaje = "<br>No se ha subido ninguna imagen";
            }
        }

        error_reporting(E_ALL ^ E_NOTICE);
        //Cantidad de resultados por página (debe ser INT, no string/varchar)
        $cantidad_resultados_por_pagina = 10;

        //Comprueba si está seteado el GET de HTTP
        if (isset($_GET["pagina"])) {
            //Si el GET de HTTP SÍ es una string / cadena, procede
            if (is_string($_GET["pagina"])) {
                //Si la string es numérica, define la variable 'pagina'
                if (is_numeric($_GET["pagina"])) {
                    //Si la petición desde la paginación es la página uno
                    //en lugar de ir a 'index.php?pagina=1' se iría directamente a 'index.php'
                    if ($_GET["pagina"] == 1) {
                        header("Location: index.php?ctl=inicio");
                        die();
                    } else { //Si la petición desde la paginación no es para ir a la pagina 1, va a la que sea
                        $pagina = $_GET["pagina"];
                    };
                } else { //Si la string no es numérica, redirige al index (por ejemplo: index.php?pagina=AAA)
                    header("Location: index.php?ctl=inicio");
                    die();
                };
            };
        } else { //Si el GET de HTTP no está seteado, lleva a la primera página (puede ser cambiado al index.php o lo que sea)
            $pagina = 1;
        };

        //Define el número 0 para empezar a paginar multiplicado por la cantidad de resultados por página
        $empezar_desde = ($pagina - 1) * $cantidad_resultados_por_pagina;

        $consulta_todo = $m->findEstadosAmigos($correo);
        //Cuenta el número total de registros
        $total_registros = mysqli_num_rows($consulta_todo);
        //Obtiene el total de páginas existentes
        $total_paginas = ceil($total_registros / $cantidad_resultados_por_pagina);
        //Realiza la consulta en el orden de ID ascendente (cambiar "id" por, por ejemplo, "nombre" o "edad", alfabéticamente, etc.)
        //Limitada por la cantidad de cantidad por página
        $consulta_resultados = $m->findEstadosAmigosPaginacion($idUsuario, $empezar_desde, $cantidad_resultados_por_pagina);

        $params = array(
            'visitas' => $visitas,
            'existeNotificaciones' => $countNotificaciones,
            'countMensajesPV' => $countMensajesPV,
            'countPeticiones' => $countPeticiones,
            'countComentarios' => $countComentarios,
            'countComentariosEstados' => $countComentariosEstados,
            'countComentariosFotos' => $countComentariosFotos,
            'estadoActual' => $estadoActualTexto,
            'estadoActualFecha' => $estadoActualFecha,
            'publicacionesAmigos' => $consulta_resultados,
            'totalPaginas' => $total_paginas,
            'idUsuario' => $idUsuario,
            'nuevoEstado' => '',
            'pagina' => $pagina,
            'nombreBusqueda' => '',
            'countUsuariosConectados' => $countUsuariosConectado,
            'listaUsuariosConectados' => $arrayUsuariosConectados,
            'fotoPerfil' =>  $fotoPerfil,
            'mensajeSubida' => $mensaje,
            'baneado' => $baneado
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
        $baneado = $m->isBaneado($idUsuario);


        $countBusqueda = $m->countfindUsuariosByNombre(trim($nombre));

        $arrayMensajesPrivados = $m->findCountMensajesPvById($idUsuario);
        $countMensajesPV = implode(array_column($arrayMensajesPrivados, "count(*)"));

        $cantidad_resultados_por_pagina = 10;
        $page = false;

        //Comprueba si está seteado el GET de HTTP
        if (isset($_GET["pagina"])) {
            //Si el GET de HTTP SÍ es una string / cadena, procede
            if (is_string($_GET["pagina"])) {
                //Si la string es numérica, define la variable 'pagina'
                if (is_numeric($_GET["pagina"])) {
                    //Si la petición desde la paginación es la página uno
                    //en lugar de ir a 'index.php?pagina=1' se iría directamente a 'index.php'
                    if ($_GET["pagina"] == 1) {
                        header("Location: index.php?ctl=busqueda");
                        die();
                    } else { //Si la petición desde la paginación no es para ir a la pagina 1, va a la que sea
                        $pagina = $_GET["pagina"];
                    };
                } else { //Si la string no es numérica, redirige al index (por ejemplo: index.php?pagina=AAA)
                    header("Location: index.php?ctl=busqueda");
                    die();
                };
            };
        } else { //Si el GET de HTTP no está seteado, lleva a la primera página (puede ser cambiado al index.php o lo que sea)
            $pagina = 1;
        }

        //Define el número 0 para empezar a paginar multiplicado por la cantidad de resultados por página
        $empezar_desde = ($pagina - 1) * $cantidad_resultados_por_pagina;

        $consulta_todo = $m->findUsuariosNombre(trim($nombre));
        //Cuenta el número total de registros
        $total_registros = mysqli_num_rows($consulta_todo);
        //Obtiene el total de páginas existentes
        $total_paginas = ceil($total_registros / $cantidad_resultados_por_pagina);
        //Realiza la consulta en el orden de ID ascendente (cambiar "id" por, por ejemplo, "nombre" o "edad", alfabéticamente, etc.)
        //Limitada por la cantidad de cantidad por página
        $consulta_resultados = $m->findUsuariosByNombre(trim($nombre), $empezar_desde, $cantidad_resultados_por_pagina);

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
                $mensaje = "Se han encontrado <strong>" . $countBusqueda . "</strong> resultados.";
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
            'busqueda' => $consulta_resultados,
            'totalPaginas' => $total_paginas,
            'palabraBuscada' => trim($nombre),
            'mensajeBusqueda' => $mensaje,
            'idUsuarioConectado' => $idUsuario,
            'page' => $pagina,
            'baneado' => $baneado
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

        $baneado = $m->isBaneado($idUsuario);

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
            'idUsuarioConectado' => $idUsuario,
            'baneado' => $baneado
        );



        require __DIR__ . '/templates/solicitudesAmistad.php';
    }

    public function comentariosEstados()
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
        $fotoPerfil = implode(array_column($arrayUsuario, "fotoPerfil"));
        $nombre = implode(array_column($arrayUsuario, "nombre"));
        $apellidos = implode(array_column($arrayUsuario, "apellidos"));
        $baneado = $m->isBaneado($idUsuario);

        $arrayCountComentariosEstados = $m->findCountComentariosEstadosById($idUsuario);
        $arrayComentariosEstados = $m->findPublicacionesConComentarioByCorreo($correo);
        $countComentariosEstados = implode(array_column($arrayCountComentariosEstados, "count(*)"));


        $arrayMensajesPrivados = $m->findCountMensajesPvById($idUsuario);
        $countMensajesPV = implode(array_column($arrayMensajesPrivados, "count(*)"));


        $params = array(
            'countMensajesPV' => $countMensajesPV,
            'countComentariosEstados' => $countComentariosEstados,
            'nombre' => '',
            'nombreBusqueda' => '',
            'idUsuarioConectado' => $idUsuario,
            'publicaciones' => $arrayComentariosEstados,
            'fotoPerfil' => $fotoPerfil,
            'baneado' => $baneado
        );



        require __DIR__ . '/templates/comentariosEstados.php';
    }

    public function gestionAmigos()
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

        $baneado = $m->isBaneado($idUsuario);

        $arrayAmigos = $m->findAmigosByIdUsuario($idUsuario);

        $arrayCountAmigos = $m->countAmigosByIdUsuario($idUsuario);
        $countAmigos = implode(array_column($arrayCountAmigos, "count(*)"));


        $arrayMensajesPrivados = $m->findCountMensajesPvById($idUsuario);
        $countMensajesPV = implode(array_column($arrayMensajesPrivados, "count(*)"));

        $cantidad_resultados_por_pagina = 10;

        //Comprueba si está seteado el GET de HTTP
        if (isset($_GET["pagina"])) {
            //Si el GET de HTTP SÍ es una string / cadena, procede
            if (is_string($_GET["pagina"])) {
                //Si la string es numérica, define la variable 'pagina'
                if (is_numeric($_GET["pagina"])) {
                    //Si la petición desde la paginación es la página uno
                    //en lugar de ir a 'index.php?pagina=1' se iría directamente a 'index.php'
                    if ($_GET["pagina"] == 1) {
                        header("Location: index.php?ctl=gestionAmigos");
                        die();
                    } else { //Si la petición desde la paginación no es para ir a la pagina 1, va a la que sea
                        $pagina = $_GET["pagina"];
                    };
                } else { //Si la string no es numérica, redirige al index (por ejemplo: index.php?pagina=AAA)
                    header("Location: index.php?ctl=gestionAmigos");
                    die();
                };
            };
        } else { //Si el GET de HTTP no está seteado, lleva a la primera página (puede ser cambiado al index.php o lo que sea)
            $pagina = 1;
        }

        //Define el número 0 para empezar a paginar multiplicado por la cantidad de resultados por página
        $empezar_desde = ($pagina - 1) * $cantidad_resultados_por_pagina;

        $consulta_todo = $m->findAmigosByIdUsuario($idUsuario);
        //Cuenta el número total de registros
        $total_registros = mysqli_num_rows($consulta_todo);
        //Obtiene el total de páginas existentes
        $total_paginas = ceil($total_registros / $cantidad_resultados_por_pagina);
        //Realiza la consulta en el orden de ID ascendente (cambiar "id" por, por ejemplo, "nombre" o "edad", alfabéticamente, etc.)
        //Limitada por la cantidad de cantidad por página
        $consulta_resultados = $m->findAmigosByIdUsuarioPaginacion($idUsuario, $empezar_desde, $cantidad_resultados_por_pagina);


        $params = array(
            'countMensajesPV' => $countMensajesPV,
            'listaAmigos' => $consulta_resultados,
            'totalPaginas' => $total_paginas,
            'countAmigos' => $countAmigos,
            'nombre' => '',
            'nombreBusqueda' => '',
            'idUsuarioConectado' => $idUsuario,
            'pagina' => $pagina,
            'baneado' => $baneado
        );

        require __DIR__ . '/templates/gestionAmigos.php';
    }

    public function gestionUsuarios()
    {
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        $correo = implode(array_column($_SESSION['usuarioconectado'], "correo"));
        $arrayUsuario = $m->buscarSoloUsuario($correo);
        $idUsuario = implode(array_column($arrayUsuario, "id"));
        $arrayMensajesPrivados = $m->findCountMensajesPvById($idUsuario);
        $countMensajesPV = implode(array_column($arrayMensajesPrivados, "count(*)"));
        $baneado = $m->isBaneado($idUsuario);

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombreBusqueda']) && !empty($_POST['nombreBusqueda'])) {
            $nombre = $_POST['nombreBusqueda'];
        } else {
            $nombre = "";
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usuarioBuscado']) && !empty($_POST['usuarioBuscado'])) {
            $usuarioBuscado = $_POST['usuarioBuscado'];
        } else {
            $usuarioBuscado = "";
        }

        $listaUsuarios = $m->findAllUsuariosByNombre($usuarioBuscado);

        $params = array(
            'countMensajesPV' => $countMensajesPV,
            'listaUsuarios' => $listaUsuarios,
            'nombre' => '',
            'nombreBusqueda' => '',
            'idUsuarioConectado' => $idUsuario,
            'usuarioBuscado' => $usuarioBuscado,
            'baneado' => $baneado
        );

        require __DIR__ . '/templates/gestionUsuarios.php';
    }
    public function estadisticas()
    {
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        $correo = implode(array_column($_SESSION['usuarioconectado'], "correo"));
        $arrayUsuario = $m->buscarSoloUsuario($correo);
        $idUsuario = implode(array_column($arrayUsuario, "id"));
        $arrayMensajesPrivados = $m->findCountMensajesPvById($idUsuario);
        $countMensajesPV = implode(array_column($arrayMensajesPrivados, "count(*)"));
        $baneado = $m->isBaneado($idUsuario);

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombreBusqueda']) && !empty($_POST['nombreBusqueda'])) {
            $nombre = $_POST['nombreBusqueda'];
        } else {
            $nombre = "";
        }

        $resultadoPorComunidad = $m->findUsuariosPorComunidad();
        $resultadoPorProvincia = $m->findUsuariosPorProvincia();
        $cantidadEstadosPorUsuario = $m->findCantidadEstadosPorUsuario();

        $params = array(
            'countMensajesPV' => $countMensajesPV,
            'resultadoPorComunidad' => $resultadoPorComunidad,
            'resultadoPorProvincia' => $resultadoPorProvincia,
            'cantidadEstadosPorUsuario' => $cantidadEstadosPorUsuario,
            'nombre' => '',
            'nombreBusqueda' => '',
            'idUsuarioConectado' => $idUsuario,
            'baneado' => $baneado
        );

        require __DIR__ . '/templates/estadisticas.php';
    }

    public function gestionContenido()
    {

        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        $correo = implode(array_column($_SESSION['usuarioconectado'], "correo"));
        $arrayUsuario = $m->buscarSoloUsuario($correo);
        $idUsuario = implode(array_column($arrayUsuario, "id"));
        $arrayMensajesPrivados = $m->findCountMensajesPvById($idUsuario);
        $countMensajesPV = implode(array_column($arrayMensajesPrivados, "count(*)"));
        $baneado = $m->isBaneado($idUsuario);

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombreBusqueda']) && !empty($_POST['nombreBusqueda'])) {
            $nombre = $_POST['nombreBusqueda'];
        } else {
            $nombre = "";
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['publicacionBuscada']) && !empty($_POST['publicacionBuscada'])) {
            $publicacionBuscada = $_POST['publicacionBuscada'];
        } else {
            $publicacionBuscada = "";
        }

        error_reporting(E_ALL ^ E_NOTICE);
        //Cantidad de resultados por página (debe ser INT, no string/varchar)
        $cantidad_resultados_por_pagina = 10;

        //Comprueba si está seteado el GET de HTTP
        if (isset($_GET["pagina"])) {
            //Si el GET de HTTP SÍ es una string / cadena, procede
            if (is_string($_GET["pagina"])) {
                //Si la string es numérica, define la variable 'pagina'
                if (is_numeric($_GET["pagina"])) {
                    //Si la petición desde la paginación es la página uno
                    //en lugar de ir a 'index.php?pagina=1' se iría directamente a 'index.php'
                    if ($_GET["pagina"] == 1) {
                        header("Location: index.php?ctl=gestionContenido");
                        die();
                    } else { //Si la petición desde la paginación no es para ir a la pagina 1, va a la que sea
                        $pagina = $_GET["pagina"];
                    };
                } else { //Si la string no es numérica, redirige al index (por ejemplo: index.php?pagina=AAA)
                    header("Location: index.php?ctl=gestionContenido");
                    die();
                };
            };
        } else { //Si el GET de HTTP no está seteado, lleva a la primera página (puede ser cambiado al index.php o lo que sea)
            $pagina = 1;
        };

        //Define el número 0 para empezar a paginar multiplicado por la cantidad de resultados por página
        $empezar_desde = ($pagina - 1) * $cantidad_resultados_por_pagina;

        $consulta_todo = $m->findPublicaciones($publicacionBuscada);
        //Cuenta el número total de registros
        $total_registros = mysqli_num_rows($consulta_todo);
        //Obtiene el total de páginas existentes
        $total_paginas = ceil($total_registros / $cantidad_resultados_por_pagina);
        //Realiza la consulta en el orden de ID ascendente (cambiar "id" por, por ejemplo, "nombre" o "edad", alfabéticamente, etc.)
        //Limitada por la cantidad de cantidad por página
        $consulta_resultados = $m->findPublicacionesPaginacion($publicacionBuscada, $empezar_desde, $cantidad_resultados_por_pagina);

        $params = array(
            'countMensajesPV' => $countMensajesPV,
            'nombre' => '',
            'nombreBusqueda' => '',
            'idUsuarioConectado' => $idUsuario,
            'publicacionesUsuarios' => $consulta_resultados,
            'totalPaginas' => $total_paginas,
            'pagina' => $pagina,
            'usuarioBuscado' => $publicacionBuscada,
            'baneado' => $baneado
        );

        require __DIR__ . '/templates/gestionContenido.php';
    }

    public function perfil()
    {
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        $correo = implode(array_column($_SESSION['usuarioconectado'], "correo"));
        $arrayUsuario = $m->buscarSoloUsuario($correo);
        $idUsuario = implode(array_column($arrayUsuario, "id"));
        $fotoPerfil = implode(array_column($arrayUsuario, "fotoPerfil"));
        $arrayMensajesPrivados = $m->findCountMensajesPvById($idUsuario);
        $countMensajesPV = implode(array_column($arrayMensajesPrivados, "count(*)"));
        $baneado = $m->isBaneado($idUsuario);

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombreBusqueda']) && !empty($_POST['nombreBusqueda'])) {
            $nombre = $_POST['nombreBusqueda'];
        } else {
            $nombre = "";
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['perfilUsuario']) && !empty($_POST['perfilUsuario'])) {
            $perfilUsuario = $_POST['perfilUsuario'];
            setcookie('perfilUsuario', $perfilUsuario, time() + 3600);
        } else {
            $perfilUsuario = $_COOKIE['perfilUsuario'];
        }

        $mensajeFoto = "";
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tituloFoto'])) {
            $mensajeFoto = "entra";
            if (isset($_POST['tituloFoto']) && !empty($_POST['tituloFoto'])) {
                $tituloFoto = $_POST['tituloFoto'];
            } else {
                $tituloFoto = "";
            }

            $carpetaDestino = "fotosUsuarios/";

            # si hay algun archivo que subir
            if (isset($_FILES["fotoSubir"]) && $_FILES["fotoSubir"]["name"]) {

                # si es un formato de imagen
                if ($_FILES["fotoSubir"]["type"] == "image/jpeg" || $_FILES["fotoSubir"]["type"] == "image/pjpeg" || $_FILES["fotoSubir"]["type"] == "image/gif" || $_FILES["fotoSubir"]["type"] == "image/png") {
                    # si exsite la carpeta o se ha creado
                    if (file_exists($carpetaDestino) || @mkdir($carpetaDestino)) {
                        $origen = $_FILES["fotoSubir"]["tmp_name"];
                        $destino = $carpetaDestino . $idUsuario . " - " . time() . $_FILES["fotoSubir"]["name"];
                        if (@move_uploaded_file($origen, $destino)) {
                            $imgh = $this->icreate($destino);
                            $imgr = $this->simpleresize($imgh, 400, 400);
                            $fecha = new DateTime("now");
                            $resultado = $m->setFotoUsuario($idUsuario, $idUsuario . " - " . time() . $_FILES["fotoSubir"]["name"], $fecha->format('Y-m-d H:i:s'), $tituloFoto);
                            $mensajeFoto = "Foto subida correctamente";
                            
                        } else {
                            $mensajeFoto = "<br>No se ha podido mover el archivo: " . $_FILES["fotoSubir"]["name"];
                        }
                    } else {
                        $mensajeFoto = "<br>No se ha podido crear la carpeta: " . $carpetaDestino;
                    }
                } else {
                    $mensajeFoto = "<br>" . $_FILES["fotoSubir"]["name"] . " - NO es imagen jpg, png o gif";
                }
            } else {
                $mensajeFoto = "<br>No se ha subido ninguna imagen";
            }
        }


        $arrayPerfilUsuarioDatos = $m->findPerfilUsuarioDatos($perfilUsuario);
        $arrayPerfilUsuarioEstado = $m->findPerfilUsuarioEstado($perfilUsuario);
        $visitas = implode(array_column($arrayPerfilUsuarioDatos, "visitas"));
        $idUsuarioPerfil = implode(array_column($arrayPerfilUsuarioDatos, "id"));
        $nombreUsuario = implode(array_column($arrayPerfilUsuarioDatos, "nombre"));
        $arrayCountFotosUsuario = $m->getCountFotoPerfil($idUsuarioPerfil);
        $countFotosUsuario = implode(array_column($arrayCountFotosUsuario, "COUNT(*)"));
        $visitasNuevo = $visitas;
        if ($idUsuario != $idUsuarioPerfil) {
            $visitasNuevo = $visitas + 1;
            $m->updateVisitasUsuarios($perfilUsuario, $visitasNuevo);
        }

        $params = array(
            'countMensajesPV' => $countMensajesPV,
            'perfilUsuarioDatos' => $arrayPerfilUsuarioDatos,
            'perfilUsuarioEstado' => $arrayPerfilUsuarioEstado,
            'perfilUsuarioPublicaciones' => $m->findPerfilUsuarioPublicacionesById($perfilUsuario),
            'listaFotosLimit' => $m->getFotoPerfilLimit($idUsuarioPerfil),
            'visitas' => $visitasNuevo,
            'contadorFotosUsuario' => $countFotosUsuario,
            'nombre' => '',
            'nombreBusqueda' => '',
            'mensajeFoto' => $mensajeFoto,
            'idUsuario' => $idUsuario,
            'idUsuarioPerfil' => $idUsuarioPerfil,
            'nombreUsuario' => $nombreUsuario,
            'fotoPerfil' => $fotoPerfil,
            'baneado' => $baneado
        );

        require __DIR__ . '/templates/perfil.php';
    }

    public function galeria()
    {
        $m = new Model(Config::$mvc_bd_nombre, Config::$mvc_bd_usuario, Config::$mvc_bd_clave, Config::$mvc_bd_hostname);

        $correo = implode(array_column($_SESSION['usuarioconectado'], "correo"));
        $arrayUsuario = $m->buscarSoloUsuario($correo);
        $idUsuario = implode(array_column($arrayUsuario, "id"));
        $fotoPerfil = implode(array_column($arrayUsuario, "fotoPerfil"));
        $arrayMensajesPrivados = $m->findCountMensajesPvById($idUsuario);
        $countMensajesPV = implode(array_column($arrayMensajesPrivados, "count(*)"));
        $baneado = $m->isBaneado($idUsuario);

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idGaleria = $_POST['idGaleria'];
            setcookie('idGaleria',$idGaleria, time() +3600);
        }else{
            $idGaleria = $_COOKIE['idGaleria'];
        }

        $arrayUsuarioPerfil = $m->findPerfilUsuarioDatos($idGaleria);
        $nombreUsuario = implode(array_column($arrayUsuarioPerfil, "nombre"));
        $apellidosUsuario = implode(array_column($arrayUsuarioPerfil, "apellidos"));

        $arrayCountFotosUsuario = $m->getCountFotoPerfil($idGaleria);
        $countFotosUsuario = implode(array_column($arrayCountFotosUsuario, "COUNT(*)"));

        $params = array(
            'countMensajesPV' => $countMensajesPV,
            'nombre' => '',
            'nombreBusqueda' => '',
            'listaFotos' => $m->getFotoPerfil($idGaleria),
            'idUsuario' => $idUsuario,
            'fotoPerfil' => $fotoPerfil,
            'nombreUsuario' => $nombreUsuario,
            'apellidosUsuario' => $apellidosUsuario,
            'countFotosUsuario' => $countFotosUsuario,
            'baneado' => $baneado
        );

        require __DIR__ . '/templates/galeria.php';
    }

    function icreate($filename)
    {
        $isize = getimagesize($filename);
        if ($isize['mime'] == 'image/jpeg') {
            return imagecreatefromjpeg($filename);
        } elseif ($isize['mime'] == 'image/png') {
            return imagecreatefrompng($filename);
        } elseif ($isize['mime'] == 'image/gif') {
            return imagecreatefromgif($filename);
        } else {
            return null;
        }
        /* Add as many formats as you can */
    }

    function simpleresize($image, $width, $height)
    {
        /**
         * Simple image resample into new image
         *
         * @param $image Image resource
         * @param $width
         * @param $height
         */
        $new = imageCreateTrueColor($width, $height);
        imagecopyresampled($new, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
        return $new;
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

    function aniosHastaHoy($fecha)
    {
        date_default_timezone_set('Europe/Madrid');
        $date1 = new DateTime($fecha);
        $date2 = new DateTime("now");
        $diff = $date1->diff($date2);
        echo $diff->y . ' años.';
    }

    function fechaCumpleanios($fecha)
    {
        date_default_timezone_set('Europe/Madrid');
        setlocale(LC_ALL, "es_ES");
        $fechaNac = new DateTime($fecha);
        $dia = $fechaNac->format("d");
        $mes = $fechaNac->format("m");
        $mesTexto = "";

        switch ($mes) {
            case '01':
                $mesTexto = "enero";
                break;
            case '02':
                $mesTexto = "febrero";
                break;
            case '03':
                $mesTexto = "marzo";
                break;
            case '04':
                $mesTexto = "abril";
                break;
            case '05':
                $mesTexto = "mayo";
                break;
            case '06':
                $mesTexto = "junio";
                break;
            case '07':
                $mesTexto = "julio";
                break;
            case '08':
                $mesTexto = "agosto";
                break;
            case '09':
                $mesTexto = "septiembre";
                break;
            case '10':
                $mesTexto = "octubre";
                break;
            case '11':
                $mesTexto = "noviembre";
                break;
            case '12':
                $mesTexto = "diciembre";
                break;
        }
        echo $dia . " de " . $mesTexto;
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
        setcookie('idGaleria', null, time() -1);
        setcookie('perfilUsuario', null, time() -1);
        session_destroy();
        header('Location: ../web/paginaInicio');
    }
}
