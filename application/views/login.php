<!--   Funcion que cierra cualquier sesion  abierta  -->
<?php 
$data = array('user' => null, 'pass' => null, 'login' => false);
	//enviamos los datos de session al navegador
$this->session->set_userdata($data);
if ($this->session->userdata('login')) 
{
	$this->session->sess_destroy();
	header("Location: " . base_url());
}
	/*$redirect = $this->input->get('mod');
	if ($this->session->userdata('login')) 
	{
		if ($mod == "tickets") {
			header("Location: " . base_url()."tickets");
		}else{
			header("Location: " . base_url());
		}
		
	}*/
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Login PostVenta</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!-- Tell the browser to be responsive to screen width -->
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Font Awesome -->
		<link rel="stylesheet" href="<?=base_url()?>plugins/fontawesome-free/css/all.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
		<!-- icheck bootstrap -->
		<link rel="stylesheet" href="<?=base_url()?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="<?=base_url()?>dist/css/adminlte.min.css">
		<!-- Google Font: Source Sans Pro -->
		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
		<link rel="shortcut icon" href="<?=base_url()?>media/logo/logo_codiesel_sinfondo.png" />
	</head>
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo row">
				<div class="col-md-12"><a href="<?=base_url()?>" style="font-size: 50px;"><b>Pos</b>Venta</a></div>
				<div class="col-md-12">
					<img src="<?=base_url()?>media/logo/logosinfondo.png" style="width: 55%; height: 75%" class="img-fluid" alt="Responsive image">
				</div>
			</div>
			<!-- /.login-logo -->
			<div class="card" style="top: -40px;">
				<div class="card-body login-card-body">
					<p class="login-box-msg"><strong>Iniciar Sesiòn</strong></p>
					<!--  Formulario de Login  -->
					<form action="<?=base_url()?>home" method="post">
						<div class="input-group mb-3">
							<input type="text" class="form-control" name="usu" placeholder="Usuario" required="TRUE">
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-user"></span>
								</div>
							</div>
						</div>
						<div class="input-group mb-3">
							<input type="password" class="form-control" name="pass" placeholder="Contraseña" required="TRUE">
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-lock"></span>
								</div>
							</div>
						</div>
						<div class="input-group mb-3">
							<a href="#" class="link" data-toggle="modal" data-target="#modal_rest">¿Olvidó su contraseña?</a>
						</div>
						<div class="row">

							<!-- /.col -->
							<div class="col-12">
								<button type="submit" class="btn btn-primary btn-block " style="background-color: #D79408; border-left-color: #D79408; border-top-color: #D79408; border-right-color: #D79408; border-bottom-color: #D79408;"><i class="fas fa-sign-in-alt"></i> Iniciar Sesion</button>
							</div>
							<!-- /.col -->
							<?php 
							$err = $this->input->GET('log');
							if($err == "err"){
								?>
								<div class="alert alert-warning alert-dismissible fade show" role="alert" id="alert_err" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
									Este usuario no esta activo
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<?php 
							}elseif ($err == "pass_err") {
								?>
								<div class="alert alert-warning alert-dismissible fade show" role="alert" id="alert_err" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
									Contraseña Incorrecta
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<?php 
							}elseif ($err == "non_user") {
								?>
								<div class="alert alert-warning alert-dismissible fade show" role="alert" id="alert_err" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
									El usuario no existe
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<?php 
							}
							?>
						</div>
					</form>
				</div>
				<!-- /.login-card-body -->
			</div>
		</div>
		<!-- /.login-box -->

		<!-- Modal Restablecer password-->
		<div class="modal fade" id="modal_rest" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Reestablecer contraseña Intranet POS-VENTA</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p>1- Por favor ingresa tu numero de identificacion o cedula</p>
						<form class="form-inline">
							<div class="form-group mx-sm-3 mb-2">
								<label for="inputPassword2" class="sr-only">Ingresa tu cedula</label>
								<input type="text" class="form-control" id="nit" placeholder="Número de Cedula">
							</div>
							<a href="#" class="btn btn-primary mb-2" onclick="enviar_codigo_verifi()">Enviar código</a>
						</form>

						<div style="display: none;" id="paso2">
							<hr>
							<p>2- Un código fue enviado a tu correo electronico corporativo.</p>
							<p>Por favor introducelo en el siguiente campo de texto y presiona el botón <strong>Validar código</strong></p>
							<form class="form-inline">
								<div class="form-group mx-sm-3 mb-2">
									<label for="inputPassword2" class="sr-only">Ingresa el código de verificación</label>
									<input type="text" class="form-control" id="cod_verifi" placeholder="Código de verificación" onkeyup="javascript:this.value=this.value.toUpperCase();">
								</div>
								<a href="#" class="btn btn-primary mb-2" onclick="validar_cod_verificacion();">Validar código</a>
							</form>
						</div>

						<div style="display: none;" id="paso3">
							<hr>
							<p>3- Por favor ingresa con tu <strong>número de cedula</strong> en los campos de usuario y contraseña</p>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>




		<!-- jQuery -->
		<script src="<?=base_url()?>plugins/jquery/jquery.min.js"></script>
		<!-- Bootstrap 4 -->
		<script src="<?=base_url()?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
		<!-- AdminLTE App -->
		<script src="<?=base_url()?>dist/js/adminlte.min.js"></script>
		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<!--  funcion que cierra la alerta a los 2.5 seg  -->
		<script type="text/javascript">
			setTimeout(function(){ 
				$('#alert_err').alert('close');
			}, 2500);
		</script>

		<script type="text/javascript">
			function enviar_codigo_verifi() {
				//traemos el valor del campo de text
				var cedula = document.getElementById('nit').value;
				var paso2 = document.getElementById('paso2');
				if (cedula == "") {
					Swal.fire({
						title: 'Advertencia!',
						text: ' No puedes dejar campos vacios',
						icon: 'warning',
						confirmButtonText: 'Cool'
					});
				}else{
					/*CODIGO PARA GENERAR EL CODIGO DE VERIFICACION*/
					const  generateRandomString = (num) => {
						const characters ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
						let result1= ' ';
						const charactersLength = characters.length;
						for ( let i = 0; i < num; i++ ) {
							result1 += characters.charAt(Math.floor(Math.random() * charactersLength));
						}
						return result1;
					}
					const displayRandomString = () =>{
						let randomStringContainer = document.getElementById('random_string'); 
						randomStringContainer.innerHTML =  generateRandomString(8);    
					}
					var codigo =generateRandomString(5).toUpperCase();
					/*FIN*/
				//ENVIO DEL INFORMACION
				var xmlhttp;
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						var resp = xmlhttp.responseText;
						//alert(resp);
						if (resp == "ok") {
							paso2.style.display = "block";
						}else if(resp == "err"){
							Swal.fire({
								title: 'Error!',
								text: ' La cedula es incorrecta o el usuario no existe',
								icon: 'error',
								confirmButtonText: 'Cerrar'
							});
						}
					}
				}
				xmlhttp.open("GET", "<?= base_url() ?>login/enviar_correo_verifi?nit="+cedula+"&codigo="+codigo, true);
				xmlhttp.send();
			}
		}
		function validar_cod_verificacion() {
			var codigo = document.getElementById('cod_verifi').value;
			var cedula = document.getElementById('nit').value;
			var paso3 = document.getElementById('paso3');
			if (codigo == "") {
					Swal.fire({
					  title: 'Advertencia!',
					  text: ' No puedes dejar campos vacios',
					  icon: 'warning',
					  confirmButtonText: 'Cool'
					});
				}
			//alert(cedula + " "+codigo);
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					var resp = xmlhttp.responseText;
					if (resp == "ok") {
						paso3.style.display = "block";
					}else{
						Swal.fire({
								title: 'Error!',
								text: ' El codigo es incorrecto',
								icon: 'error',
								confirmButtonText: 'Cerrar'
							});
					}
				}
			}
			xmlhttp.open("GET", "<?= base_url() ?>login/validar_cod_verifi?nit="+cedula+"&codigo="+codigo, true);
			xmlhttp.send();
		}
	</script>
</body>
</html>