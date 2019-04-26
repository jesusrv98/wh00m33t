<?php
	require ('../conexion.php');
    
    header("Content-Type: text/html; charset=utf-8");
	$idprovincia = $_POST['idprovincia'];
	
	$queryM = "SELECT idpoblacion, poblacion FROM poblacion WHERE idprovincia = '$idprovincia' ORDER BY poblacion";
	$resultadoM = $mysqli->query($queryM);
	
	$html= "";
	
	while($rowM = $resultadoM->fetch_assoc())
	{
		$html.= "<option value='".$rowM['idpoblacion']."'>".$rowM['poblacion']."</option>";
	}
	
	echo utf8_encode($html);
?>