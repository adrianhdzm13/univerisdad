<?php

	function conexion()
	{
		return mysqli_connect('localhost','root','','administrador');
	}

	$conexion = conexion();
	$sql = "SELECT 	ID,	NROIDENTIFI,NOMBRE,APELLIDO,GENERO,DIRECCION,TELEFONO,EMAIL,USUARIO,FECHA_HORA,SENTENCIA FROM triguer_estudiante";

	$resultado = mysqli_query($conexion,$sql);

	$datos = mysqli_fetch_all($resultado,MYSQLI_ASSOC);//para que nos traiga todos los datos de resultado y que lo traiga en un  arreglo asociativo con MYSLI_ASSOC

	//verificamos que nos traiga los datos
	///var_dump($datos);

	//validacion si es distinto de vacio 
	if(!empty($datos))
	{
		//muestra arreglo datos lo imprime en un formato JSON
		echo json_encode($datos);
	}else{
		//si no imprime un arreglo vacio 
		echo json_encode([]);
	}

?>