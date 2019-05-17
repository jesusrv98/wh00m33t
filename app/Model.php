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

    public function findNotificaciones($idUsuario)
    {
        $sql = "select count(*) from notificaciones WHERE idUsuario =" . $idUsuario . "";

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

    public function insertarNotificacionByTipo($id_fkTipo, $tipo, $idUsuario)
    {
        $sql="INSERT INTO `notificaciones` (`id`, `id_fkTipo`, `tipo`, `vista`, `idUsuario`) VALUES (NULL, $id_fkTipo, '$tipo', '0', $idUsuario);";
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

    public function isAmigo($idUno, $idDos) {
        $sql = "SELECT * FROM es_amigo WHERE (amigo =" . $idUno." AND amigo_fk_a = ".$idDos." ) OR (amigo=".$idDos." AND amigo_fk_a =".$idUno.") ";
        $result = mysqli_query($this->conexion, $sql);
        $isAmigo = false;

        if($result) {
            $numeroFilas = mysqli_num_rows($result);
            if($numeroFilas == 2) {
                $isAmigo = true;
            }
        }
        return $isAmigo;
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


    public function findEstadosAmigos($idUsuario)
    {
        // $sql = "select e.*, u.* from estadose join usuarios u on e.idUsuario = u.id WHERE e.idUsuario IN (SELECT amigo FROM es_amigo WHERE amigo_fk_a IN (SELECT id FROM usuarios WHERE correo = 'admin@whomeet.es'))";
        $sql = "SELECT e.*, u.* FROM estados e JOIN usuarios u ON e.idUsuario = u.id WHERE e.idUsuario in ( SELECT amigo FROM es_amigo WHERE amigo_fk_a IN (SELECT id FROM usuarios WHERE correo = \"$idUsuario\")) ORDER BY e.fecha DESC";
        $result = mysqli_query($this->conexion, $sql);

        $estados = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $estados[] = $row;
        }

        return $estados;
    }

    public function findIdUsuario($correo)
    {

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
        $sql = "SELECT c.*, u.* FROM (comentarios c JOIN estados e ON c.id_espacioComentado = e.idEstado) JOIN usuarios u on c.id_usuario = u.id WHERE e.idEstado = $idEspacio";
        $result = mysqli_query($this->conexion, $sql);

        $comentarios = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $comentarios[] = $row;
        }

        return $comentarios;
    }

    public function insertarComentarioByIdEspacio($idUsuario, $idEspacio, $textoComentario, $fechaActual)
    {   
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
        $sql = "INSERT INTO `estados`(`idUsuario`,`fecha`) VALUES ($idUsuario, $fecha) ";
        $result = mysqli_query($this->conexion, $sql);
    }

    public function findUsuariosByNombre($nombre)
    {           
        $sql = "SELECT DISTINCT(u.id),u.*, po.*, pr.* FROM (usuarios u JOIN poblacion po ON u.codpueblo = po.idpoblacion) JOIN provincia pr ON po.idprovincia = pr.idprovincia WHERE CONCAT(u.nombre, ' ', u.apellidos) LIKE '%".$nombre."%' AND u.id != ".implode(array_column($_SESSION['usuarioconectado'], "id"))." order by u.nombre ASC";

        $result = mysqli_query($this->conexion, $sql);

        $usuarios = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $usuarios[] = $row;
        }

        return $usuarios;
    }

    public function countfindUsuariosByNombre($nombre)
    {           
        $sql = "SELECT DISTINCT(u.id),u.*, po.*, pr.* FROM (usuarios u JOIN poblacion po ON u.codpueblo = po.idpoblacion) JOIN provincia pr ON po.idprovincia = pr.idprovincia WHERE CONCAT(u.nombre, ' ', u.apellidos) LIKE '%".$nombre."%' AND u.id != ".implode(array_column($_SESSION['usuarioconectado'], "id"))." order by u.nombre ASC";

        $result = mysqli_query($this->conexion, $sql);

        $contador = mysqli_num_rows($result);

        return $contador;
    }

    public function findUsuariosConectado()
    {           
        $sql = "SELECT DISTINCT(u.id),u.*, po.*, pr.* FROM (usuarios u JOIN poblacion po ON u.codpueblo = po.idpoblacion) JOIN provincia pr ON po.idprovincia = pr.idprovincia WHERE u.id != ".implode(array_column($_SESSION['usuarioconectado'], "id"))." AND u.estado = 'Online' order by u.nombre ASC";

        $result = mysqli_query($this->conexion, $sql);

        $usuarios = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $usuarios[] = $row;
        }

        return $usuarios;
    }

    public function countfindUsuariosConectado()
    {           
        $sql = "SELECT DISTINCT(u.id),u.*, po.*, pr.* FROM (usuarios u JOIN poblacion po ON u.codpueblo = po.idpoblacion) JOIN provincia pr ON po.idprovincia = pr.idprovincia WHERE u.id != ".implode(array_column($_SESSION['usuarioconectado'], "id"))." AND u.estado = 'Online' order by u.nombre ASC";

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
        $sql = "select * from poblacion where idprovincia =" . $idprovincia . " order by poblacion ASC";

        $result = mysqli_query($this->conexion, $sql);

        $pueblos = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $pueblos[] = $row;
        }

        return $pueblos;
    }

    public function verificar($variable, $variable2)
    {
        $sql = "select pass from usuarios where correo LIKE '$variable2'";
        $result = mysqli_query($this->conexion, $sql);
        $passwordbd = mysqli_fetch_row($result);
        return password_verify($variable, $passwordbd[0]);
    }

    public function encriptar($variable)
    {
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



    public function eliminarAlimento($id)
    {
        $sql = "DELETE FROM alimentos WHERE `id` = '$id'";

        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }
    public function editarAlimento($id, $alimento, $energia, $proteina, $hidratocarbono, $fibra, $grasa)
    {
        $sql = "UPDATE alimentos SET `nombre` = '$alimento', `energia` = '$energia', `proteina` = '$proteina', `hidratocarbono` = '$hidratocarbono', `fibra` = '$fibra', `grasatotal` = '$grasa' WHERE `id` = '$id'";

        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function buscarAlimentosPorNombre($nombre)
    {
        $nombre = htmlspecialchars($nombre);

        $sql = "select * from alimentos where nombre like '" . $nombre . "' order by energia desc";

        $result = mysqli_query($this->conexion, $sql);

        $alimentos = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $alimentos[] = $row;
        }

        return $alimentos;
    }

    public function buscarAlimentosPorEnergia($energia)
    {
        $energia = htmlspecialchars($energia);

        $sql = "select * from alimentos where energia=" . $energia . " order by energia desc";

        $result = mysqli_query($this->conexion, $sql);

        $alimentos = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $alimentos[] = $row;
        }

        return $alimentos;
    }
    public function buscarAlimentosCombinada($energia, $nombre)
    {
        $energia = htmlspecialchars($energia);
        $nombre = htmlspecialchars($nombre);
        $sql = "select * from alimentos where energia=" . $energia . " and nombre like '" . $nombre . "' order by nombre desc";

        $result = mysqli_query($this->conexion, $sql);

        $alimentos = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $alimentos[] = $row;
        }

        return $alimentos;
    }



    // LOGIN
    public function buscarUsuario($email, $password)
    {
        $correo = htmlspecialchars($email);
        $pass = htmlspecialchars($password);
        $sql = "SELECT * FROM usuarios WHERE correo='" . $correo . "' AND pass ='" . $pass . "'";
        $result = mysqli_query($this->conexion, $sql);
        $usuario = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $usuario[] = $row;
        }
        return $usuario;
    }
    // FIN LOGIN



    public function dameAlimento($id)
    {
        $id = htmlspecialchars($id);

        $sql = "select * from alimentos where id=" . $id;

        $result = mysqli_query($this->conexion, $sql);

        $alimentos = array();
        $row = mysqli_fetch_assoc($result);

        return $row;
    }

    public function insertarAlimento($n, $e, $p, $hc, $f, $g)
    {
        $n = htmlspecialchars($n);
        $e = htmlspecialchars($e);
        $p = htmlspecialchars($p);
        $hc = htmlspecialchars($hc);
        $f = htmlspecialchars($f);
        $g = htmlspecialchars($g);

        $sql = "insert into alimentos (nombre, energia, proteina, hidratocarbono, fibra, grasatotal) values ('$n','$e','$p','$hc','$f','$g')";

        $result = mysqli_query($this->conexion, $sql);

        return $result;
    }

    public function validarDatos($n, $e, $p, $hc, $f, $g)
    {
        return (is_string($n) &
            is_numeric($e) &
            is_numeric($p) &
            is_numeric($hc) &
            is_numeric($f) &
            is_numeric($g));
    }
}
