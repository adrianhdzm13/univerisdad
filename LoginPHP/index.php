<?php
include("conexion.php");
include("enviar_correo.php");
session_start();
if (isset($_SESSION['id_usuario'])){
	header("Location: admin.php");//dirigimos al usuario a la pagina admin.php
}
//login
//verifico si no esta vacio
//if (!empty($_POST)){
if(isset($_POST["ingresar"])) {//VALIDA EL BOTON INGREASR
	$usuario = mysqli_real_escape_string($conexion,$_POST['user']);
	$password = mysqli_real_escape_string($conexion,$_POST['pass']);
	$password_encriptada = sha1($password);
	$sql = "SELECT idusuarios FROM usuarios 
					WHERE usuario = '$usuario' AND password = '$password_encriptada' ";

	$resultado = $conexion->query($sql);
	$rows  = $resultado->num_rows;//cuenta los registros que existen
	if($rows > 0){
		$row = $resultado->fetch_assoc();
		$_SESSION['id_usuario'] = $row["idusuarios"];
		header("Location: admin.php");//dirigimos al usuario al pagina principal
	}else{
		echo "<script>
				alert('Usuario o Password incorrecto');
				window.location = 'index.php';
		</script>";
	}


}

//registro usuarios invitado
if (isset($_POST["registrar"])) {
	$nombre = mysqli_real_escape_string($conexion,$_POST['nombre']);//valida si se envia inyeccion sql
	$correo = mysqli_real_escape_string($conexion,$_POST['correo']);
	$usuario = mysqli_real_escape_string($conexion,$_POST['user']);
	$password = mysqli_real_escape_string($conexion,$_POST['pass']);
	$password_encriptada = sha1($password);
	$token = sha1(rand(0,1000));

	//para que no me permita ingresar un nombre  usuario ya registrado
	$sqluser = "SELECT idusuarios FROM usuarios WHERE usuario = '$usuario'";

	$resultadouser = $conexion->query($sqluser);
	$filas = $resultadouser->num_rows;

	$sqluser1 = "SELECT idusuarios FROM usuarios WHERE Correo = '$correo'";

	$resultadouser1 = $conexion->query($sqluser1);
	$filas1 = $resultadouser1->num_rows;

	//validacion correo si ya existe
	if ($filas1 > 0  ){
		echo "<script>
				alert('El correo ya existe, favor registrarte con otro correo');
				window.location = 'index.php';
		</script>";
	//validacion usuario si existe
	}else if ($filas > 0  ){
		echo "<script>
				alert('El usuario ya existe');
				window.location = 'index.php';
		</script>";
	}else{
		//insertar informacion del usuario 
		$estado = 0;
		$sqlusuario = " INSERT INTO usuarios(Nombre,Correo,Usuario,Password,Token,Estado) 
		VALUES ('$nombre','$correo','$usuario','$password_encriptada','$token','$estado')";
		$resultadousuario = $conexion->query($sqlusuario);
		if($resultadousuario > 0 ){
			echo "<script>
				alert('Registro exitoso $nombre, Tu cuenta fue creada! Te acabamos de enviar un correo, por favor confirma tu cuenta haciendo click en el link enviado ');
				window.location = 'index.php';
		</script>";

		$para_usuario = $correo;
		$asunto = 'Verifica tu cuenta (login.com)';
		$mensaje = 'Hola ' .$nombre.'  gracias por registrarse! 
		Por favor confirma tu cuenta haciendo click en este link:
		http://127.0.0.1:80/pagina_universidad/loginPHP/activar_correo.php?email='.$correo.'&token='.$token.'1';//envio al correo electronico
		//pasamos la informacion anuestaran funcion
	    sendEmail($para_usuario, $asunto, $mensaje);

		}else{
			echo "<script>
				alert('Error al registrarse');
				window.location = 'index.php';
		</script>";
		}
	}


}
//Restaurar contraseña
if (isset($_POST["recuperarpass"])) {
	$correo = mysqli_real_escape_string($conexion,$_POST['correo']);
	$sqlrecuperar = "SELECT * FROM usuarios WHERE Correo ='$correo'";
	$resultadoRecupera = $conexion->query($sqlrecuperar);
	$resultado = $resultadoRecupera->num_rows;

	if ($resultado === 0){
		echo "<script>
				alert('El usuario con ese correo no fue encontrado');
				window.location = 'index.php';
		</script>";
		exit();
	}else{
		$user = $resultadoRecupera->fetch_assoc();

		$correo = $user['Correo'];
		$token = $user['Token'];
		$nombre = $user['Nombre'];

		$para_usuario = $correo;
		$asunto = 'Cambiar Password (login.com)';
		$mensaje = 'Hola '.$nombre.'  Has pedido un cambio de Password! 
			<br/>
			Por favor has click en link para cambiar tu contraseña:
			<br/>
			<a href="http://127.0.0.1:80/pagina_universidad/LoginPHP/recuperar.php?correo='.$correo.'&token='.$token.' "><b> Click aqui para Cambiar Password</b></a> ';
		
	    sendEmail($para_usuario, $asunto, $mensaje);
	    echo "<script>
				alert('Por favor revisa tu correo [$correo] por un link para completar el cambio de contraseña!');
				window.location = 'index.php';
		</script>";

}
}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Login  - Sistema de Usuarios</title>

		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="assets/css/ace.min.css" />

		
		<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />

		
	</head>

	<body class="login-layout"  style="background-color:#DFE0E2;">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h1>
									<i class="ace-icon fa fa-leaf green"></i>
									<span class="red">Universidad</span>
									<span class="red" id="id-text2">de la Vida</span>
								</h1>
								<h4 class="blue" id="id-company-text">&copy; Control de acceso</h4>
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
												<i class="ace-icon fa fa-coffee green"></i>
												Ingresa tu Informacion
											</h4>

											<div class="space-6"></div>

											<form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST" >
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control"  name="user"placeholder="Usuario" required />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" name="pass"class="form-control" placeholder="Contraseña" required />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													<div class="space"></div>

													<div class="clearfix">
														<label class="inline">
															<input type="checkbox" class="ace" />
															<span class="lbl"> Recordarme</span>
														</label>

											<button type="submit" name="ingresar" class="width-35 pull-right btn btn-sm btn-primary">
												<i class="ace-icon fa fa-key"></i>
												<span class="bigger-110">Ingresar</span>
											</button>


													</div>

													<div class="space-4"></div>
												</fieldset>
											</form>

											<div class="social-or-login center">
												<span class="bigger-110">Suscribete</span>
											</div>

											<div class="space-6"></div>

											<div class="social-login center">
												<a href="#" target="_blank" class="btn btn-danger">
													<i class="ace-icon fa fa-youtube" ></i>
												</a>
												<a href="#" target="_blank" class="btn btn-primary">
													<i class="ace-icon fa fa-facebook"></i>
												</a>

												<a href="#" target="_blank" class="btn btn-info">
													<i class="ace-icon fa fa-twitter"></i>
												</a>

												<a href="#" target="_blank" class="btn btn-danger">
													<i class="ace-icon fa fa-instagram"></i>
												</a>
											</div>
										</div><!-- /.widget-main -->

										<div class="toolbar clearfix">
											<div>
												<a href="#" data-target="#forgot-box" class="forgot-password-link">
													<i class="ace-icon fa fa-arrow-left"></i>
													Olvide mi contraseña
												</a>
											</div>

											<div>
												<a href="#" data-target="#signup-box" class="user-signup-link">
													 Invitado
													<i class="ace-icon fa fa-arrow-right"></i>
												</a>
											</div>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->

								<div id="forgot-box" class="forgot-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header red lighter bigger">
												<i class="ace-icon fa fa-key"></i>
												Recuperar Contraseña
											</h4>

											<div class="space-6"></div>
											<p>
												Ingresa tu correo electronico para recibir las instrucciones
											</p>

						<form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST" >
							<fieldset>
								<label class="block clearfix">
									<span class="block input-icon input-icon-right">
									<input type="email" class="form-control" placeholder="Email" name="correo" required />
									<i class="ace-icon fa fa-envelope"></i>
									</span>
								</label>
							<div class="clearfix">
								<button type="submit" name="recuperarpass" class="width-35 pull-right btn btn-sm btn-danger">
								<i class="ace-icon fa fa-lightbulb-o"></i>
								<span class="bigger-110">Enviar</span>
								</button>
							</div>
							</fieldset>
						</form>
				</div><!-- /.widget-main -->

	<div class="toolbar center">
		<a href="#" data-target="#login-box" class="back-to-login-link">
			Regresar al Login
			<i class="ace-icon fa fa-arrow-right"></i>
			</a>
			</div>
			</div><!-- /.widget-body -->
			</div><!-- /.forgot-box -->

	<div id="signup-box" class="signup-box widget-box no-border">
             	<div class="widget-body">
			<div class="widget-main">
				<h4 class="header green lighter bigger">
					<i class="ace-icon fa fa-users blue"></i>
						Registro de Usuario Invitado
				</h4>
	<div class="space-6"></div>
		<p>Ingresa los datos solicitados acontinuacion: </p>
		<form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST" >
			<fieldset>
			            <label class="block clearfix">
					<span class="block input-icon input-icon-right">
						<input type="text" class="form-control"  name="nombre" placeholder="Nombre Completo"  required />
							<i class="ace-icon fa fa-users"></i>
					</span>
				</label>
			
				<label class="block clearfix">
					<span class="block input-icon input-icon-right">
				             	<input type="email" class="form-control" name="correo" placeholder="Email"  required />
					                        <i class="ace-icon fa fa-envelope"></i>
					</span>
				</label>
					<label class="block clearfix">
						<span class="block input-icon input-icon-right">
			                     		<input type="text" class="form-control" name="user" placeholder="Usuario"  required />
                                       				<i class="ace-icon fa fa-user"></i>
  						</span>
				</label>
				<label class="block clearfix">
                     				<span class="block input-icon input-icon-right">
		                      			<input type="password" class="form-control" name="pass" placeholder="Password"  required />
							<i class="ace-icon fa fa-lock"></i>
					</span>
				</label>

				<label class="block clearfix">
					<span class="block input-icon input-icon-right">
						<input type="password" class="form-control" name="passr" placeholder="Repetir password" />
							<i class="ace-icon fa fa-retweet"></i>
									</span>
				</label>

				<label class="block">
					<input type="checkbox" class="ace" />
						<span class="lbl">
						Acepto los
						<a href="#">Terminos de Uso</a>
						</span>
				</label>
				<div class="space-24"></div>
				<div class="clearfix">
					<button type="reset" class="width-30 pull-left btn btn-sm">
						<i class="ace-icon fa fa-refresh"></i>
							<span class="bigger-110">Reset</span>
					</button>
					
					<button type="submit" name="registrar"   class="width-65 pull-right btn btn-sm btn-success">
						<span class="bigger-110">Registrar</span>
							<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
					</button>
					 </div>
			</fieldset>
		</form>
	</div>

			<div class="toolbar center">
				<a href="#" data-target="#login-box" class="back-to-login-link">
					<i class="ace-icon fa fa-arrow-left"></i>
						Regresar al Login
				</a>
			</div>
		</div><!-- /.widget-body -->
	</div><!-- /.signup-box -->
</div><!-- /.position-relative -->

							<!-- crea tres enlaces pata darle el color que nosotros queramos a la pagina <div class="navbar-fixed-top align-right">
								<br />
								&nbsp;
								<a id="btn-login-dark" href="#">Oscuro</a>
								&nbsp;
								<span class="blue">/</span>
								&nbsp;
								<a id="btn-login-blur" href="#">Azul</a>
								&nbsp;
								<span class="blue">/</span>
								&nbsp;
								<a id="btn-login-light" href="#">Claro</a>
								&nbsp; &nbsp; &nbsp;
							</div>
						</div>
					-->
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="assets/js/jquery-2.1.4.min.js"></script>

		<!-- <![endif]-->

		<!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
			 $(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible');//hide others
				$(target).addClass('visible');//show target
			 });
			});



			//you don't need this, just used for changing background
			jQuery(function($) {
			 $('#btn-login-dark').on('click', function(e) {
				$('body').attr('class', 'login-layout');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'blue');

				e.preventDefault();
			 });
			 $('#btn-login-light').on('click', function(e) {
				$('body').attr('class', 'login-layout light-login');
				$('#id-text2').attr('class', 'grey');
				$('#id-company-text').attr('class', 'blue');

				e.preventDefault();
			 });
			 $('#btn-login-blur').on('click', function(e) {
				$('body').attr('class', 'login-layout blur-login');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'light-blue');

				e.preventDefault();
			 });

			});
		</script>
	</body>
</html>
