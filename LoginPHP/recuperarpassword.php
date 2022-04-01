<?php 
include("conexion.php");
session_start();
//SI ESTO SE ESTA ENVIANDO ATRAVES DEL METODO POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    //enviamos las variables del formulario nuevopass y confirmarpass
    //validacion para comparar las contraseñas 
	if($_POST['nuevopass'] === $_POST['confirmarpass']){
			$password = mysqli_real_escape_string($conexion,$_POST['nuevopass']);
			$password_nuevo = sha1($password);

			$correo = mysqli_real_escape_string($conexion,$_POST['correo']); // METODO POST RECUPERA 
  			$token = mysqli_real_escape_string($conexion,$_POST['token']); 

  			//CONSULTA ACTUALIZACION DB
  			$sql = "UPDATE usuarios SET  Password='$password_nuevo'
  			                  WHERE Correo='$correo'";
  			$resultado = $conexion->query($sql);
 			
 			if($resultado > 0){
 				echo "<script>
				alert('Tu contraseña ha sido actualizada!');
				window.location = 'index.php';
				</script>";
				exit();
            }
	}else{
            echo "<script>
			alert('Las contraseñas no coinciden!');
			window.location = 'index.php';
			</script>";
			exit();
            }
}
?>