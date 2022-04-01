<?php
include("configuracion.php");
//instancia de configuracion.php 
$conexion = new mysqli($server,$user,$pass,$bd);
if (mysqli_connect_errno()){
	echo "No conectado", mysqli_connect_error();
	exit();
}//else{ 
	//echo 'Conectado';
//}
	
?>