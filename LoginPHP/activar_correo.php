<?php 
include("conexion.php");
session_start();
//SI EXISTE LA VARIABLE EMAIL A TRAVEZ DEL METODO get y NO ESTA VACIA
if(isset($_GET["email"]) && !empty($_GET["email"]) AND isset($_GET["token"]) && !empty($_GET["token"])) {//VALIDA EL BOTON INGREASR
	$correo = mysqli_real_escape_string($conexion,$_GET['email']);
	$token = mysqli_real_escape_string($conexion,$_GET['token']);
	
	$sql = "SELECT * FROM usuarios 
					WHERE Correo = '$correo' AND Token = '$token' AND Estado = '0'  ";

	$resultado = $conexion->query($sql);
	$rows  = $resultado->num_rows;//cuenta los registros que existen
	if($rows === 0){
		echo "<script>
				alert('Tu cuenta ya fue activada!');
				window.location = 'index.php';
		</script>";
		
	
		$sqlA = "UPDATE usuarios SET Estado = '1'  
					WHERE Correo = '$correo' ";
		$resultadoA = $conexion->query($sqlA);
	}
}else{
	echo "<script>
				alert('La URL contiene información incorrecta !');
				window.location = 'index.php';
		</script>";
}


?>