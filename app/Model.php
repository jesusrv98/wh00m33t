<?php

class Model
{
    protected $conexion;

    public function __construct($dbname, $dbuser, $dbpass, $dbhost)
    {
        $mvc_bd_conexion = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

        if (!$mvc_bd_conexion) {
            die('No ha sido posible realizar la conexión con la base de datos: ' . mysql_error());
        }
        mysqli_select_db($mvc_bd_conexion, $dbname);

        mysqli_set_charset($mvc_bd_conexion, 'utf8');

        $this->conexion = $mvc_bd_conexion;
    }



    public function bd_conexion()
    { }

    // Buscar solo el nombre de usuario para registrarse
    public function buscarSoloUsuario($email)
    {
        $email = htmlspecialchars($email);
        $sql = "select * from usuarios WHERE correo ='" . $email . "'";

        $result = mysqli_query($this->conexion, $sql);

        $usuarios = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $usuarios[] = $row;
        }

        return $usuarios;
    }

    public function findUsuarioById($idUsuario)
    {
        $sql = "select * from usuarios WHERE id ='" . $idUsuario . "'";

        $result = mysqli_query($this->conexion, $sql);

        $usuario = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $usuario[] = $row;
        }

        return $usuario;
    }

    public function findNotificaciones($idUsuario)
    {

        $sql = "select count(*) from notificaciones WHERE idUsuario =" . $idUsuario . " AND vista = 0 AND id_fkTipo NOT IN (SELECT idUsuario FROM comentarios WHERE id_usuario = $idUsuario )";

        $result = mysqli_query($this->conexion, $sql);

        $notificaciones = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $notificaciones[] = $row;
        }

        return $notificaciones;
    }

    public function findCountMensajesPvById($idUsuario)
    {
        $sql = "select count(*) from notificaciones WHERE idUsuario =" . $idUsuario . " and tipo ='mensajePV' and vista = 0 ";

        $result = mysqli_query($this->conexion, $sql);

        $mensajesPV = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $mensajesPV[] = $row;
        }

        return $mensajesPV;
    }

    public function actualizarSolicitudAmistad($idSolicitud, $estado)
    {
        $idSolicitud = (int)$idSolicitud;
        $sql = "UPDATE `solicitudes` SET `estadoSolicitud`= $estado WHERE `idSolicitud` = $idSolicitud";
        $result = mysqli_query($this->conexion, $sql);
    }

    public function actualizarNotificaciones($idEspacio, $tipo, $usuario)
    {
        $tipo = htmlspecialchars($tipo);
        $sql = "UPDATE notificaciones SET `vista` = 1 WHERE `idUsuario`= $usuario AND `tipo` = '$tipo' AND `id_fkTipo`=$idEspacio ";
        $result = mysqli_query($this->conexion, $sql);
    }


    public function insertarNotificacionByTipo($id_fkTipo, $tipo, $idUsuario)
    {
        $tipo = htmlspecialchars($tipo);
        $sql = "INSERT INTO `notificaciones` (`id`, `id_fkTipo`, `tipo`, `vista`, `idUsuario`) VALUES (NULL, $id_fkTipo, '$tipo', '0', $idUsuario);";
        $result = mysqli_query($this->conexion, $sql);
    }

    public function findCountComentariosById($idUsuario)
    {
        $sql = "select count(*) from notificaciones WHERE idUsuario =" . $idUsuario . " and tipo ='comentario' and vista = 0 ";

        $result = mysqli_query($this->conexion, $sql);

        $comentarios = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $comentarios[] = $row;
        }

        return $comentarios;
    }

    public function findCountComentariosEstadosById($idUsuario)
    {
        $sql = "select count(*) from notificaciones WHERE idUsuario =" . $idUsuario . " and tipo ='comentarioEstado' and vista = 0 ";

        $result = mysqli_query($this->conexion, $sql);

        $comentarios = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $comentarios[] = $row;
        }

        return $comentarios;
    }

    public function actualizarFotoPerfil($idUsuario, $nombreFoto)
    {
        $sql = "UPDATE usuarios SET fotoPerfil='$nombreFoto' WHERE id=$idUsuario";
        $result = mysqli_query($this->conexion, $sql);
    }

    public function findCountComentariosFotosById($idUsuario)
    {
        $sql = "select count(*) from notificaciones WHERE idUsuario =" . $idUsuario . " and tipo ='comentarioFoto' and vista = 0 ";

        $result = mysqli_query($this->conexion, $sql);

        $comentariosFotos = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $comentariosFotos[] = $row;
        }

        return $comentariosFotos;
    }

    public function findEstadoById($idUsuario)
    {
        $sql = "select * from estados WHERE idUsuario =" . $idUsuario . " ORDER BY fecha DESC limit 1";

        $result = mysqli_query($this->conexion, $sql);

        $estados = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $estados[] = $row;
        }

        return $estados;
    }

    public function isAmigo($idUno, $idDos)
    {
        $sql = "SELECT * FROM es_amigo WHERE (amigo =" . $idUno . " AND amigo_fk_a = " . $idDos . " ) OR (amigo=" . $idDos . " AND amigo_fk_a =" . $idUno . ") ";
        $result = mysqli_query($this->conexion, $sql);
        $isAmigo = false;

        if ($result) {
            $numeroFilas = mysqli_num_rows($result);
            if ($numeroFilas == 2) {
                $isAmigo = true;
            }
        }
        return $isAmigo;
    }

    public function isAmigoPerfil($idConectado, $idDos)
    {
        $sql = "SELECT * FROM es_amigo WHERE (amigo =" . $idDos . " AND amigo_fk_a = " . $idConectado . " )";
        $result = mysqli_query($this->conexion, $sql);

        if (mysqli_num_rows($result) >= 1) {
            return true;
        } else {
            return false;
        }

        return $result;
    }

    public function setConectado($idUsuario)
    {

        $sql = "UPDATE usuarios SET estado = 'online' WHERE `id` = '$idUsuario'";

        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function setDesconectado($idUsuario)
    {
        $sql = "UPDATE usuarios SET estado = 'offline' WHERE `id` = '$idUsuario'";

        $result = mysqli_query($this->conexion, $sql);
    }

    public function insertarSolicitudAmistad($idSolicitante, $idSolicitado, $tipo)
    {
        $sql = "INSERT INTO `solicitudes`(`idSolicitud`, `idSolicitante`, `idSolicitado`, `estadoSolicitud`) VALUES (null, $idSolicitante, $idSolicitado , '$tipo')";
        $result = mysqli_query($this->conexion, $sql);
    }

    public function findSolicitudesAmistad($idUsuario)
    {
        $sql = "SELECT u.*, po.*, pr.*, s.* FROM ((usuarios u JOIN solicitudes s ON s.idSolicitante = u.id) JOIN poblacion po ON u.codpueblo = po.idpoblacion) JOIN provincia pr ON po.codprovincia = pr.idprovincia WHERE s.idSolicitado = $idUsuario AND s.estadoSolicitud = 0";
        $result = mysqli_query($this->conexion, $sql);

        $solicitudes = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $solicitudes[] = $row;
        }

        return $solicitudes;
    }

    public function findPublicacionesConComentarioByCorreo($correo)
    {
        $sql = "SELECT e.*, u.* FROM estados e JOIN usuarios u ON e.idUsuario = u.id WHERE e.idUsuario in ( SELECT amigo_fk_a FROM es_amigo WHERE amigo_fk_a IN (SELECT id FROM usuarios WHERE correo = '$correo' )) AND e.idEstado IN (SELECT id_fkTipo FROM notificaciones WHERE vista = 0)";
        $result = mysqli_query($this->conexion, $sql);

        $comentarios = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $comentarios[] = $row;
        }

        return $comentarios;
    }

    public function findCountPeticionesById($idUsuario)
    {
        $sql = "select count(*) from notificaciones WHERE idUsuario =" . $idUsuario . " and tipo ='peticionAmistad' and vista = 0 ";

        $result = mysqli_query($this->conexion, $sql);

        $peticiones = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $peticiones[] = $row;
        }

        return $peticiones;
    }


    public function findAmigosByIdUsuarioPaginacion($idUsuario, $empezar_desde, $cantidad_resultados_por_pagina)
    {
        $sql = "SELECT * FROM (usuarios u JOIN poblacion p ON u.codpueblo = p.idpoblacion) JOIN provincia pr ON pr.idprovincia = p.codprovincia  WHERE id IN (SELECT amigo FROM es_amigo WHERE amigo_fk_a = $idUsuario AND amigo != $idUsuario) ORDER BY u.nombre LIMIT $empezar_desde, $cantidad_resultados_por_pagina";
        $result = mysqli_query($this->conexion, $sql);

        $amigo = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $amigo[] = $row;
        }

        return $amigo;
    }
    public function findAmigosByIdUsuario($idUsuario)
    {
        $sql = "SELECT * FROM (usuarios u JOIN poblacion p ON u.codpueblo = p.idpoblacion) JOIN provincia pr ON pr.idprovincia = p.codprovincia  WHERE id IN (SELECT amigo FROM es_amigo WHERE amigo_fk_a = $idUsuario AND amigo != $idUsuario)";
        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function countAmigosByIdUsuario($idUsuario)
    {
        $sql = "SELECT count(*) FROM usuarios WHERE id IN (SELECT amigo FROM es_amigo WHERE amigo_fk_a = $idUsuario AND amigo != $idUsuario)";
        $result = mysqli_query($this->conexion, $sql);

        $amigo = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $amigo[] = $row;
        }

        return $amigo;
    }

    public function findEstadosAmigos($idUsuario)
    {
        // $sql = "select e.*, u.* from estadose join usuarios u on e.idUsuario = u.id WHERE e.idUsuario IN (SELECT amigo FROM es_amigo WHERE amigo_fk_a IN (SELECT id FROM usuarios WHERE correo = 'admin@whomeet.es'))";
        $sql = "SELECT e.*, u.* FROM estados e JOIN usuarios u ON e.idUsuario = u.id WHERE e.idUsuario in ( SELECT amigo FROM es_amigo WHERE amigo_fk_a IN (SELECT id FROM usuarios WHERE correo = '$idUsuario' ) AND bloqueado = 0)";
        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function agregarAmigo($idSolicitante, $idSolicitado)
    {
        $sql = "INSERT INTO `es_amigo` (`idamigo`, `amigo_fk_a`, `amigo`, `tipo`, `bloqueado`) VALUES (NULL, $idSolicitante, $idSolicitado, 'Amigos', '0')";
        $result = mysqli_query($this->conexion, $sql);
    }

    public function findAllEstados()
    {
        $sql = "SELECT * FROM estados";
        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function tieneSolicitud($idSolicitante, $idSolicitado)
    {
        $sql = "SELECT * FROM `solicitudes` WHERE idSolicitante = $idSolicitante AND idSolicitado = $idSolicitado and estadoSolicitud = 0";
        $result = mysqli_query($this->conexion, $sql);

        $tieneSolicitud = false;

        if ($result) {
            $numeroFilas = mysqli_num_rows($result);
            if ($numeroFilas == 1) {
                $tieneSolicitud = true;
            }
        }
        return $tieneSolicitud;
    }

    public function tieneSolicitudPorAceptar($idSolicitante, $idSolicitado)
    {
        $sql = "SELECT * FROM `solicitudes` WHERE idSolicitante = $idSolicitante AND idSolicitado = $idSolicitado and estadoSolicitud = 0";
        $result = mysqli_query($this->conexion, $sql);

        $tieneSolicitud = false;

        if ($result) {
            $numeroFilas = mysqli_num_rows($result);
            if ($numeroFilas == 1) {
                $tieneSolicitud = true;
            }
        }
        return $tieneSolicitud;
    }

    public function borrarAmigo($idUsuario1, $idUsuario2)
    {
        $sql = "DELETE FROM es_amigo WHERE (`amigo` = $idUsuario1 AND `amigo_fk_a` = $idUsuario2) OR (`amigo` = $idUsuario2 AND `amigo_fk_a` = $idUsuario1)";

        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function findEstadosAmigosPaginacion($idUsuario, $empezar_desde, $cantidad_resultados_por_pagina)
    {
        // $sql = "select e.*, u.* from estadose join usuarios u on e.idUsuario = u.id WHERE e.idUsuario IN (SELECT amigo FROM es_amigo WHERE amigo_fk_a IN (SELECT id FROM usuarios WHERE correo = 'admin@whomeet.es'))";
        $sql = "SELECT e.*, u.* FROM estados e JOIN usuarios u ON e.idUsuario = u.id WHERE e.idUsuario in ( SELECT amigo FROM es_amigo WHERE amigo_fk_a IN (SELECT id FROM usuarios WHERE id = '$idUsuario' ) AND bloqueado = 0) ORDER BY e.fecha DESC LIMIT $empezar_desde, $cantidad_resultados_por_pagina";
        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function findIdUsuario($correo)
    {

        $correo = htmlspecialchars($correo);

        $sql = "select id from usuarios WHERE correo =" . $correo;

        $result = mysqli_query($this->conexion, $sql);

        $idUsuario = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $idUsuario = $row;
        }
        $resultado = mysqli_fetch_row($result);

        return $idUsuario;
    }

    //Buscar comentarios de publicacion
    public function findComentarioByIdEspacio($idEspacio)
    {
        $sql = "SELECT c.*, u.* FROM (comentarios c JOIN estados e ON c.id_espacioComentado = e.idEstado) JOIN usuarios u on c.id_usuario = u.id WHERE e.idEstado = $idEspacio ORDER BY c.fecha_comentario ASC";
        $result = mysqli_query($this->conexion, $sql);

        $comentarios = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $comentarios[] = $row;
        }

        return $comentarios;
    }

    public function insertarComentarioByIdEspacio($idUsuario, $idEspacio, $textoComentario, $fechaActual)
    {
        $textoComentario = htmlspecialchars($textoComentario);
        $sql = "INSERT INTO comentarios (idComentario, id_espacioComentado, id_usuario, textoComentario, fecha_comentario) VALUES (NULL, $idEspacio, $idUsuario, '$textoComentario', '$fechaActual')";
        $result = mysqli_query($this->conexion, $sql);
    }

    // Insertar un usuario
    public function insertarUsuario($correo, $pass, $nombre, $apellidos, $fechanac, $sexo, $telefono, $codpueblo, $estadocivil)
    {
        $correo = htmlspecialchars($correo);
        $pass = htmlspecialchars($pass);
        $nombre = htmlspecialchars($nombre);
        $apellidos = htmlspecialchars($apellidos);
        $fechanac = htmlspecialchars($fechanac);
        $sexo = htmlspecialchars($sexo);
        $telefono = htmlspecialchars($telefono);
        $codpueblo = htmlspecialchars($codpueblo);
        $estadocivil = htmlspecialchars($estadocivil);
        $sql = "insert into usuarios(correo, pass, nombre, apellidos, fechanac, sexo, telefono, codpueblo, estadocivil) values ('$correo','$pass','$nombre','$apellidos','$fechanac','$sexo','$telefono',$codpueblo,'$estadocivil')";

        $result = mysqli_query($this->conexion, $sql);
    }

    public function insertarEstado($idUsuario, $fecha)
    {
        $sql = "INSERT INTO `estados`(`idEstado`, `estadoCuerpo`, `fecha`, `idUsuario`) VALUES (NULL,'¡Hola, estoy usando WhoMeet por primera vez!', '$fecha', $idUsuario)";
        $result = mysqli_query($this->conexion, $sql);
    }

    public function findUsuariosByNombre($nombre, $empezar_desde, $cantidad_resultados_por_pagina)
    {
        $nombre = htmlspecialchars($nombre);
        $sql = "SELECT DISTINCT(u.id),u.*, po.*, pr.* FROM (usuarios u JOIN poblacion po ON u.codpueblo = po.idpoblacion) JOIN provincia pr ON po.codprovincia = pr.idprovincia WHERE CONCAT(u.nombre, ' ', u.apellidos) LIKE '%" . $nombre . "%' AND u.id != " . implode(array_column($_SESSION['usuarioconectado'], "id")) . " order by u.nombre ASC  LIMIT $empezar_desde, $cantidad_resultados_por_pagina";

        $result = mysqli_query($this->conexion, $sql);

        $usuarios = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $usuarios[] = $row;
        }

        return $usuarios;
    }

    public function findAllUsuariosByNombre($nombre)
    {
        $nombre = htmlspecialchars($nombre);
        $sql = "SELECT DISTINCT(u.id),u.*, po.*, pr.* FROM (usuarios u JOIN poblacion po ON u.codpueblo = po.idpoblacion) JOIN provincia pr ON po.codprovincia = pr.idprovincia WHERE CONCAT(u.nombre, ' ', u.apellidos) LIKE '%" . $nombre . "%' AND u.id != " . implode(array_column($_SESSION['usuarioconectado'], "id")) . " order by u.nombre ASC";

        $result = mysqli_query($this->conexion, $sql);

        $usuarios = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $usuarios[] = $row;
        }

        return $usuarios;
    }

    public function findUsuariosNombre($nombre)
    {
        $nombre = htmlspecialchars($nombre);
        $sql = "SELECT DISTINCT(u.id),u.*, po.*, pr.* FROM (usuarios u JOIN poblacion po ON u.codpueblo = po.idpoblacion) JOIN provincia pr ON po.codprovincia = pr.idprovincia WHERE CONCAT(u.nombre, ' ', u.apellidos) LIKE '%" . $nombre . "%' AND u.id != " . implode(array_column($_SESSION['usuarioconectado'], "id")) . " order by u.nombre ASC";

        $result = mysqli_query($this->conexion, $sql);
        return $result;
    }

    public function countfindUsuariosByNombre($nombre)
    {
        $nombre = htmlspecialchars($nombre);
        $sql = "SELECT DISTINCT(u.id),u.*, po.*, pr.* FROM (usuarios u JOIN poblacion po ON u.codpueblo = po.idpoblacion) JOIN provincia pr ON po.codprovincia = pr.idprovincia WHERE CONCAT(u.nombre, ' ', u.apellidos) LIKE '%" . $nombre . "%' AND u.id != " . implode(array_column($_SESSION['usuarioconectado'], "id")) . " order by u.nombre ASC";

        $result = mysqli_query($this->conexion, $sql);

        $contador = mysqli_num_rows($result);

        return $contador;
    }

    public function findUsuariosConectado()
    {
        $sql = "SELECT DISTINCT(u.id),u.*  FROM usuarios u JOIN es_amigo am ON u.id = am.amigo_fk_a WHERE u.id != " . implode(array_column($_SESSION['usuarioconectado'], "id")) . " AND u.estado = 'Online' AND u.id IN(SELECT amigo FROM es_amigo WHERE amigo_fk_a = " . implode(array_column($_SESSION['usuarioconectado'], "id")) . ") order by u.nombre ASC";

        $result = mysqli_query($this->conexion, $sql);

        $usuarios = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $usuarios[] = $row;
        }

        return $usuarios;
    }

    public function countfindUsuariosConectado()
    {
        $sql = "SELECT DISTINCT(u.id),u.*  FROM usuarios u JOIN es_amigo am ON u.id = am.amigo_fk_a WHERE u.id != " . implode(array_column($_SESSION['usuarioconectado'], "id")) . " AND u.estado = 'Online' AND u.id IN(SELECT amigo FROM es_amigo WHERE amigo_fk_a = " . implode(array_column($_SESSION['usuarioconectado'], "id")) . ") ORDER BY u.nombre ASC";

        $result = mysqli_query($this->conexion, $sql);

        $contador = mysqli_num_rows($result);

        return $contador;
    }

    public function agregarPorDefecto($idUsuario)
    {
        // INSERT INTO `es_amigo` (`idamigo`, `amigo_fk_a`, `amigo`, `tipo`, `bloqueado`) VALUES (NULL, '30', '37', 'Amigos', '0'), (NULL, '37', '30', 'Amigos', '0');
        $sql = "INSERT INTO es_amigo (amigo_fk_a, amigo) VALUES (30, $idUsuario), ($idUsuario, 30), ($idUsuario,$idUsuario)";
        $result = mysqli_query($this->conexion, $sql);
    }


    public function insertarEstadoNuevo($estadoNuevo, $fechaActual, $idUsuario)
    {
        $estadoNuevo = htmlspecialchars($estadoNuevo);
        $sql = "INSERT INTO estados (estadoCuerpo, fecha, idUsuario) VALUES ('$estadoNuevo', '$fechaActual', $idUsuario) ";
        $result = mysqli_query($this->conexion, $sql);
    }

    public function listaProvincias()
    {
        $sql = "select * from provincia order by provincia ASC";

        $result = mysqli_query($this->conexion, $sql);

        $provincias = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $provincias[] = $row;
        }

        return $provincias;
    }

    public function listaPueblos($idprovincia)
    {
        $sql = "select * from poblacion where codprovincia =" . $idprovincia . " order by poblacion ASC";

        $result = mysqli_query($this->conexion, $sql);

        $pueblos = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $pueblos[] = $row;
        }

        return $pueblos;
    }

    public function verificar($variable, $variable2)
    {
        $variable = htmlspecialchars($variable);
        $variable2 = htmlspecialchars($variable2);
        $sql = "select pass from usuarios where correo LIKE '$variable2' AND baneado = 0";
        $result = mysqli_query($this->conexion, $sql);
        $passwordbd = mysqli_fetch_row($result);
        return password_verify($variable, $passwordbd[0]);
    }

    public function encriptar($variable)
    {
        $variable = htmlspecialchars($variable);
        $hashed_password = password_hash($variable, PASSWORD_DEFAULT);
        return $hashed_password;
    }

    public function nombrePueblo($idpueblo)
    {
        $sql = "select poblacion from problacion where idpoblacion =" . $idpueblo . "";
        $result = mysqli_query($this->conexion, $sql);
        $pueblo = mysqli_fetch_array($result);
        return $pueblo;
    }

    public function borrarUsuario($idUSuario)
    {
        $sql = "DELETE FROM usuarios WHERE id = $idUSuario";
        $result = mysqli_query($this->conexion, $sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function isBaneado($idUsuario)
    {
        $sql = "SELECT * FROM usuarios WHERE id = $idUsuario AND baneado = 1";
        $result = mysqli_query($this->conexion, $sql);
        if (mysqli_num_rows($result) == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function banearUsuario($idUsuario, $fecha)
    {
        $sql = "UPDATE usuarios SET baneado = 1, fecha_baneo = '$fecha' WHERE id = $idUsuario";
        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function desbanearUsuario($idUsuario)
    {
        $sql = "UPDATE usuarios SET baneado = 0 WHERE id = $idUsuario";
        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function isSuEstado($idUsuario, $idEstado)
    {
        $sql = "SELECT * FROM estados WHERE idUsuario = $idUsuario AND idEstado = $idEstado";
        $result = mysqli_query($this->conexion, $sql);

        if (mysqli_num_rows($result) == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function isSuComentario($idComentario, $idUsuario)
    {
        $sql = "SELECT * FROM comentarios WHERE id_usuario = $idUsuario AND idComentario = $idComentario";
        $result = mysqli_query($this->conexion, $sql);

        if (mysqli_num_rows($result) == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function borrarEstado($idEstado, $idUsuario)
    {
        $sql = "DELETE FROM estados WHERE idEstado = $idEstado AND idUsuario = $idUsuario";
        $result = mysqli_query($this->conexion, $sql);
    }

    public function borrarComentario($idComentario, $idUsuario)
    {
        $sql = "DELETE FROM comentarios WHERE idComentario = $idComentario AND id_usuario = $idUsuario";
        $result = mysqli_query($this->conexion, $sql);
    }

    public function findUsuariosPorComunidad()
    {
        $sql = "SELECT co.comunidad ,COUNT(*) FROM ((usuarios u JOIN poblacion po ON u.codpueblo = po.idpoblacion) JOIN provincia pr ON po.codprovincia = pr.idprovincia) JOIN comunidad co ON pr.codComunidad = co.idComunidad GROUP BY co.comunidad";

        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function findUsuariosPorProvincia()
    {
        $sql = "SELECT pr.provincia ,COUNT(*) FROM ((usuarios u JOIN poblacion po ON u.codpueblo = po.idpoblacion) JOIN provincia pr ON po.codprovincia = pr.idprovincia) JOIN comunidad co ON pr.codComunidad = co.idComunidad GROUP BY pr.provincia";

        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function findCantidadEstadosPorUsuario()
    {
        $sql = "SELECT CONCAT(u.nombre,' ',u.apellidos) AS nombre, COUNT(*) FROM usuarios u JOIN estados e ON u.id = e.idUsuario GROUP BY u.nombre";
        $result = mysqli_query($this->conexion, $sql);
        return $result;
    }

    public function findPublicacionesPaginacion($publicacionBuscada, $empezar_desde, $cantidad_resultados_por_pagina)
    {
        // $sql = "select e.*, u.* from estadose join usuarios u on e.idUsuario = u.id WHERE e.idUsuario IN (SELECT amigo FROM es_amigo WHERE amigo_fk_a IN (SELECT id FROM usuarios WHERE correo = 'admin@whomeet.es'))";
        $sql = "SELECT e.*, u.* FROM estados e JOIN usuarios u ON e.idUsuario = u.id WHERE u.baneado = 0 AND CONCAT(u.nombre, ' ', u.apellidos) LIKE '%" . $publicacionBuscada . "%' ORDER BY e.fecha DESC LIMIT $empezar_desde, $cantidad_resultados_por_pagina";
        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function findPublicaciones($publicacionBuscada)
    {
        $sql = "SELECT e.*, u.* FROM estados e JOIN usuarios u ON e.idUsuario = u.id WHERE u.baneado = 0 AND CONCAT(u.nombre, ' ', u.apellidos) LIKE '%" . $publicacionBuscada . "%' ORDER BY e.fecha DESC";
        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function findPerfilUsuarioDatos($idUsuario)
    {

        $sql = "SELECT u.*, p.*, pr.*, c.* FROM ((usuarios u JOIN poblacion p ON u.codpueblo = p.idpoblacion) JOIN provincia pr ON p.codprovincia = pr.idprovincia) JOIN comunidad c ON c.idComunidad = pr.codComunidad WHERE u.id = $idUsuario";

        $result = mysqli_query($this->conexion, $sql);

        $usuario = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $usuario[] = $row;
        }

        return $usuario;
    }

    public function findPerfilUsuarioEstado($idUsuario)
    {
        $sql = "SELECT u.*, e.* FROM usuarios u JOIN estados e ON e.idUsuario = u.id WHERE u.id = $idUsuario ORDER BY e.fecha DESC LIMIT 1";

        $result = mysqli_query($this->conexion, $sql);

        $usuario = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $usuario[] = $row;
        }

        return $usuario;
    }

    public function findPerfilUsuarioPublicacionesById($idUsuario)
    {
        $sql = "SELECT e.*, u.* FROM estados e JOIN usuarios u ON u.id = e.idUsuario WHERE u.id = $idUsuario ORDER BY e.fecha DESC";
        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function findPerfilUsuarioPublicacionesByIdPaginacion($idUsuario, $empezar_desde, $cantidad_resultados_por_pagina)
    {
        $sql = "SELECT e.*, u.* FROM estados e JOIN usuarios u ON u.id = e.idUsuario WHERE u.id = $idUsuario ORDER BY e.fecha DESC LIMIT $empezar_desde, $cantidad_resultados_por_pagina";
        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function updateVisitasUsuarios($idUsuario, $visitas)
    {
        $sql = "UPDATE usuarios SET visitas = $visitas WHERE id = $idUsuario";
        $result = mysqli_query($this->conexion, $sql);
    }

    public function validarDatos($n, $e, $p, $hc, $f, $g)
    {
        return (is_string($n) & is_numeric($e) & is_numeric($p) & is_numeric($hc) & is_numeric($f) & is_numeric($g));
    }

    public function getFotoPerfil($idUsuario)
    {
        $sql = "SELECT * FROM `fotosusuario` WHERE idUsuario = $idUsuario ORDER BY fechaSubida DESC";
        $result = mysqli_query($this->conexion, $sql);

        $foto = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $foto[] = $row;
        }

        return $foto;
    }

    public function getFotoPerfilLimit($idUsuario)
    {
        $sql = "SELECT * FROM `fotosusuario` WHERE idUsuario = $idUsuario ORDER BY fechaSubida DESC LIMIT 4";
        $result = mysqli_query($this->conexion, $sql);

        $foto = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $foto[] = $row;
        }

        return $foto;
    }

    public function getCountFotoPerfil($idUsuario)
    {
        $sql = "SELECT COUNT(*) FROM `fotosusuario` WHERE idUsuario = $idUsuario";
        $result = mysqli_query($this->conexion, $sql);

        $foto = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $foto[] = $row;
        }

        return $foto;
    }

    public function setFotoUsuario($idUsuario, $rutaFoto, $fecha, $tituloFoto)
    {
        $tituloFoto = htmlspecialchars($tituloFoto);
        $sql = "INSERT INTO `fotosusuario`(`idFoto`, `rutaFoto`, `tituloFoto`, `fechaSubida`, `idUsuario`) VALUES (null, '$rutaFoto', '$tituloFoto', '$fecha', $idUsuario)";
        $result = mysqli_query($this->conexion, $sql);
    }

    public function getCountMeGustaByFoto($idFoto)
    {
        $sql = "SELECT COUNT(DISTINCT(idUsuario)) AS 'countMeGusta' FROM megusta WHERE idFoto = $idFoto";
        $result = mysqli_query($this->conexion, $sql);

        $meGusta = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $meGusta[] = $row;
        }

        return $meGusta;
    }

    public function setMeGusta($idFoto, $idUsuario)
    {
        $sql = "INSERT INTO `megusta`(`idMeGusta`, `idFoto`, `idUsuario`) VALUES (null, $idFoto, $idUsuario)";
        $result = mysqli_query($this->conexion, $sql);
    }

    public function borrarMeGusta($idFoto, $idUsuario)
    {
        $sql = "DELETE FROM megusta WHERE idFoto = $idFoto AND idUsuario = $idUsuario";
        $result = mysqli_query($this->conexion, $sql);
    }

    public function tieneTuMeGusta($idFoto, $idUsuario)
    {
        $sql = "SELECT * FROM megusta WHERE idFoto = $idFoto AND idUsuario = $idUsuario";
        $result = mysqli_query($this->conexion, $sql);

        if (mysqli_num_rows($result) == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function borrarFoto($idFoto)
    {
        $sql = "DELETE FROM fotosusuario WHERE idfoto = $idFoto";
        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function getListaAmigosConMensajes($idUsuario)
    {
        $sql = "SELECT DISTINCT * FROM usuarios u JOIN mensajes m ON u.id IN(m.idUsuarioEnvia, m.idUsuarioRecibe) WHERE u.id IN(SELECT idUsuarioEnvia FROM mensajes WHERE idUsuarioRecibe = $idUsuario) OR id IN(SELECT idUsuarioRecibe FROM mensajes WHERE idUsuarioEnvia = $idUsuario) GROUP BY u.id ORDER BY m.fechaMensaje DESC";
        $result = mysqli_query($this->conexion, $sql);

        $listaAmigos = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $listaAmigos[] = $row;
        }

        return $listaAmigos;
    }

    public function countMensajesSinVerPantallaMensajesGeneral($idUsuarioEnvia, $idUsuarioRecibe)
    {
        $sql = "SELECT COUNT(DISTINCT(idMensaje)) as 'contador' FROM mensajes WHERE (idUsuarioEnvia = $idUsuarioRecibe AND idUsuarioRecibe = $idUsuarioEnvia AND mensajeVisto = 0)";
        $result = mysqli_query($this->conexion, $sql);

        $contador = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $contador[] = $row;
        }

        return $contador;
    }

    public function getListaMensajesConversacion($idUsuarioEnvia, $idUsuarioRecibe)
    {
        $sql = "SELECT * FROM mensajes m JOIN usuarios u ON u.id IN(m.idUsuarioEnvia, m.idUsuarioRecibe) WHERE (m.idUsuarioEnvia = $idUsuarioEnvia AND m.idUsuarioRecibe = $idUsuarioRecibe) OR (m.idUsuarioEnvia = $idUsuarioRecibe and m.idUsuarioRecibe = $idUsuarioEnvia) GROUP BY m.idMensaje ORDER BY m.fechaMensaje";
        $result = mysqli_query($this->conexion, $sql);

        $mensajes = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $mensajes[] = $row;
        }

        return $mensajes;
    }

    public function enviarMensaje($idUsuarioEnvia, $idUsuarioRecibe, $cuerpoMensaje, $fecha)
    {
        $sql = "INSERT INTO `mensajes`(`idMensaje`, `idUsuarioEnvia`, `idUsuarioRecibe`, `cuerpoMensaje`, `fechaMensaje`, `mensajeVisto`) VALUES (null, $idUsuarioEnvia, $idUsuarioRecibe, '$cuerpoMensaje', '$fecha', 0)";
        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function actualizarVistoMensajes($idUsuarioEnvia, $idUsuarioRecibe)
    {
        $sql = "UPDATE `mensajes` SET `mensajeVisto`= 1 WHERE `idUsuarioEnvia` = $idUsuarioEnvia AND `idUsuarioRecibe` = $idUsuarioRecibe";
        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }
}
