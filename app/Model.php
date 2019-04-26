<?php

 class Model
 {
     protected $conexion;

     public function __construct($dbname,$dbuser,$dbpass,$dbhost)
     {
       $mvc_bd_conexion = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);

       if (!$mvc_bd_conexion) {
           die('No ha sido posible realizar la conexión con la base de datos: ' . mysql_error());
       }
       mysqli_select_db($mvc_bd_conexion,$dbname);

       mysqli_set_charset($mvc_bd_conexion,'utf8');

       $this->conexion = $mvc_bd_conexion;
     }



     public function bd_conexion()
     {

     }

     // Buscar solo el nombre de usuario para registrarse
     public function buscarSoloUsuario($email)
     {
         $email= htmlspecialchars($email);
         $sql = "select * from usuarios WHERE correo ='".$email."'";

         $result = mysqli_query($this->conexion,$sql);

         $usuarios = array();
         while ($row = mysqli_fetch_assoc($result))
         {
             $usuarios[] = $row;
         }

         return $usuarios;
     }
     
    // Insertar un usuario
     public function insertarUsuario($correo,$pass,$nombre,$apellidos,$fechanac,$sexo,$telefono,$codpueblo,$estadocivil)
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

         $result = mysqli_query($this->conexion,$sql);
     }

     public function listaProvincias()
     {
         $sql = "select * from provincia order by provincia ASC";

         $result = mysqli_query($this->conexion,$sql);

         $provincias = array();
         while ($row = mysqli_fetch_assoc($result))
         {
             $provincias[] = $row;
         }

         return $provincias;
     }

     public function listaPueblos($idprovincia)
     {
         $sql = "select * from poblacion where idprovincia =".$idprovincia." order by poblacion ASC";

         $result = mysqli_query($this->conexion,$sql);

         $pueblos = array();
         while ($row = mysqli_fetch_assoc($result))
         {
             $pueblos[] = $row;
         }

         return $pueblos;
     }

     public function verificar($variable,$variable2) {
        $sql = "select pass from usuarios where correo LIKE '$variable2'";
		$result = mysqli_query($this->conexion, $sql);
		$passwordbd = mysqli_fetch_row($result);
		return password_verify($variable, $passwordbd[0]);
    }
    
    public function encriptar($variable) {
		$hashed_password = password_hash($variable, PASSWORD_DEFAULT);
		return $hashed_password;
	}

     public function nombrePueblo($idpueblo) {
        $sql = "select poblacion from problacion where idpoblacion =".$idpueblo."";
        $result = mysqli_query($this->conexion, $sql);
        $pueblo = mysqli_fetch_array($result);
        return $pueblo;
     }



     public function eliminarAlimento($id)
     {
         $sql = "DELETE FROM alimentos WHERE `id` = '$id'";

         $result = mysqli_query($this->conexion,$sql);
         
         return $result;

     }
     public function editarAlimento($id,$alimento,$energia,$proteina,$hidratocarbono,$fibra,$grasa)
     {
         $sql = "UPDATE alimentos SET `nombre` = '$alimento', `energia` = '$energia', `proteina` = '$proteina', `hidratocarbono` = '$hidratocarbono', `fibra` = '$fibra', `grasatotal` = '$grasa' WHERE `id` = '$id'";
         
         $result = mysqli_query($this->conexion,$sql);
         
         return $result;

     }

     public function buscarAlimentosPorNombre($nombre)
     {
         $nombre = htmlspecialchars($nombre);

         $sql = "select * from alimentos where nombre like '" . $nombre . "' order by energia desc";

         $result = mysqli_query($this->conexion,$sql);

         $alimentos = array();
         while ($row = mysqli_fetch_assoc($result))
         {
             $alimentos[] = $row;
         }

         return $alimentos;
     }

		public function buscarAlimentosPorEnergia($energia)
     {
         $energia = htmlspecialchars($energia);

         $sql = "select * from alimentos where energia=". $energia . " order by energia desc";

         $result = mysqli_query($this->conexion,$sql);

         $alimentos = array();
         while ($row = mysqli_fetch_assoc($result))
         {
             $alimentos[] = $row;
         }

         return $alimentos;
     }
		public function buscarAlimentosCombinada($energia,$nombre)
     {
         $energia = htmlspecialchars($energia);
				 $nombre = htmlspecialchars($nombre);
         $sql = "select * from alimentos where energia=". $energia . " and nombre like '" . $nombre . "' order by nombre desc";

         $result = mysqli_query($this->conexion,$sql);

         $alimentos = array();
         while ($row = mysqli_fetch_assoc($result))
         {
             $alimentos[] = $row;
         }

         return $alimentos;
     }



     // LOGIN
     public function buscarUsuario($email,$password)
     {
        $correo = htmlspecialchars($email);
        $pass = htmlspecialchars($password);
        $sql = "SELECT * FROM usuarios WHERE correo='". $correo . "' AND pass ='" . $pass . "'";
        $result = mysqli_query($this->conexion,$sql);
        $usuario = array();
        while ($row = mysqli_fetch_assoc($result))
        {
            $usuario[] = $row;
        }
        return $usuario;
    }
     // FIN LOGIN



     public function dameAlimento($id)
     {
         $id = htmlspecialchars($id);

         $sql = "select * from alimentos where id=".$id;

         $result = mysqli_query($this->conexion,$sql);

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

         $result = mysqli_query($this->conexion,$sql);

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
