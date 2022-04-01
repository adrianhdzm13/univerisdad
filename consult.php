<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <title>REST</title>



    <link rel="stylesheet" href="style/estiloss.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src='js/script.js'></script>
</head>

<body>
<header>
		<nav>
			<ul>
				<a href='quien.html'>
					<li>Quienes somos</li>
				</a>
				<a href='consult.php'>
					<li>Consulta PHP</li>
				</a>
			</ul>
	
			<a href="index.html"><img id='logo' src='pictures/logo_san_mateo.jpg	'></a>
	
			<ul>
				<a href='programas.html'>
					<li>Nuestros Programas</li>
				</a>
				<a href='rrest.html'>
					<li>Rest</li>
				</a>
				<a href="LoginPHP/index.php"><img src="pictures/usuario.png" alt="usuario" id="icono"></a>
			</ul>
		</nav>
	</header>

	
    <section id='banner'>

    </section id="tabla-modelo">

    <section id='body'>
	<?php
	// Ejemplo de conexión a base de datos MySQL con PHP.
	//
	// Ejemplo realizado por Oscar Abad Folgueira: http://www.oscarabadfolgueira.com y https://www.dinapyme.com
	
	// Datos de la base de datos
	$usuario = "root";
	$password = "";
	$servidor = "localhost";
	$basededatos = "administrador";
	
	// creación de la conexión a la base de datos con mysql_connect()
	$conexion = mysqli_connect( $servidor, $usuario, "" ) or die ("No se ha podido conectar al servidor de Base de datos");
	
	// Selección del a base de datos a utilizar
	$db = mysqli_select_db( $conexion, $basededatos ) or die ( "Upps! Pues va a ser que no se ha podido conectar a la base de datos" );

	// establecer y realizar consulta. guardamos en variable.
	$consulta = "SELECT * FROM estudiante";
	$resultado = mysqli_query( $conexion, $consulta ) or die ( "Algo ha ido mal en la consulta a la base de datos");
	
	// Motrar el resultado de los registro de la base de datos
	// Encabezado de la tabla
	echo "<table borde='2' id='tablaRest'>";
	echo "<tr>";
	echo "<th>ID</th>";
	echo "<th>NOMBRE</th>";
	echo "<th>APELLIDO</th>";
	echo "<th>GENERO</th>";
	echo "<th>DIRECCION</th>";
	echo "<th>TELEFONO</th>";
	echo "<th>CORREO</th>";
	echo "</tr>";
	
	// Bucle while que recorre cada registro y muestra cada campo en la tabla.
	while ($columna = mysqli_fetch_array( $resultado ))
	{
		echo "<tr>";
		echo "<td>" . $columna['ID']."</td><td>".$columna['NOMBRE']."</td><td>".$columna['APELLIDO']."</td><td>" .$columna['GENERO']. "</td><td>".$columna['DIRECCION']."</td><td>" .$columna['TELEFONO']."</td><td>" .$columna['EMAIL']. "</td>";
		echo "</tr>";
	}
	
	echo "</table>"; // Fin de la tabla

	// cerrar conexión de base de datos
	mysqli_close( $conexion );
?>
	
	<img id="modelo" src="pictures/UnivesidadDB.png" alt="Modelo">

    </section>

    <!--
    <section class="portafolio">
        <h1>Lorem ipsum dolor sit </h1>
        <div class="portafolio-container">
            <section class="portafolio-item">
                <img src="pictures/logo_san_mateo.jpg" alt="" class="portafolio-img">
                <section class="portafolio-text">
                    <h2>Lorem ipsum</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsam distinctio illum iure facilis
                        cumque saepe, dolorem, minus praesentium maiores id.</p>
                </section>
            </section>
            <section class="portafolio-item">
                <img src="pictures/logo_san_mateo.jpg" alt="" class="portafolio-img">
                <section class="portafolio-text">
                    <h2>Lorem ipsum</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsam distinctio illum iure facilis
                        cumque saepe, dolorem, minus praesentium maiores id.</p>
                </section>
            </section>
            <section class="portafolio-item">
                <img src="pictures/logo_san_mateo.jpg" alt="" class="portafolio-img">
                <section class="portafolio-text">
                    <h2>Lorem ipsum</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsam distinctio illum iure facilis
                        cumque saepe, dolorem, minus praesentium maiores id.</p>
                </section>
            </section>

        </div>
    </section>
    -->

	<footer>
    <p>&copy; Yeison Adrián Rico, Saul Leonardo Barrera & Cristian David Vergel - V Semestre - 2021 - Fundacion Universitaria San Mateo</p>
	</footer>
	




</body>


</html>