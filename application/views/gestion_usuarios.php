<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Post Venta</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?= base_url() ?>plugins/fontawesome-free/css/all.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Tempusdominus Bbootstrap 4 -->
	<link rel="stylesheet" href="<?= base_url() ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?= base_url() ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- JQVMap -->
	<link rel="stylesheet" href="<?= base_url() ?>plugins/jqvmap/jqvmap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?= base_url() ?>dist/css/adminlte.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="<?= base_url() ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="<?= base_url() ?>plugins/daterangepicker/daterangepicker.css">
	<!-- summernote -->
	<link rel="stylesheet" href="<?= base_url() ?>plugins/summernote/summernote-bs4.css">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<link rel="shortcut icon" href="<?= base_url() ?>media/logo/logo_codiesel_sinfondo.png" />
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: #2F3C4F;">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" style="color: #fff;" href="#"><i class="fas fa-bars"></i></a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="<?= base_url() ?>login/iniciar" style="color: #fff;" class="nav-link"><i class="fas fa-home"></i>&nbsp;&nbsp; Inicio</a>
				</li>
			</ul>
			<!-- Right navbar links -->
			<ul class="navbar-nav ml-auto">
				<!-- Notifications Dropdown Menu -->
				<img src="<?= base_url() ?>media/img/user-img.png" alt="User Avatar" class="img-size-50 mr-3 img-circle" style="position: relative; left: 25px; top: 0px; height: 35px; width: 35px;">
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="#" style="color: #fff;">
						<?php
						foreach ($userdata->result() as $key) {
						?>
							<?= $key->nombres ?>

					</a>
					<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
						<span class="dropdown-item dropdown-header"><?= $key->nombres ?></span>
					<?php
						}
					?>
					<div class="dropdown-divider"></div>
					<a href="#" class="dropdown-item" data-toggle="modal" data-target="#pass-modal2">
						<i class="fas fa-key mr-2"></i>Cambiar Contraseña
					</a>
					<div class="dropdown-divider"></div>
					<a href="#" class="dropdown-item" data-toggle="modal" data-target="#logoutModal">
						<i class="fas far fa-sign-out-alt"></i> Cerrar Sesion
					</a>
					</div>
				</li>
			</ul>


		</nav>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #3D3D3D; ">
			<!-- Brand Logo -->
			<br>

			<!-- Sidebar -->
			<div class="sidebar">
				<!-- Sidebar user panel (optional) -->
				<div class="">
					<div class="image">
						<img src="<?= base_url() ?>media/logo/logo_codiesel.png" class="img-fluid" alt="Responsive image">
					</div>
				</div>
				<a href="#" class="brand-link">

				</a>

				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<div class="input-group input-group-sm">
						<input class="form-control form-control-navbar" id="buscar_items" type="search" placeholder="Buscar" aria-label="Search" style="background-color: #3D3D3D; color: #fff; border-top: 0; border-left: 0; border-right: 0 border-color: gray;">
						<div class="input-group-append">
							<button class="btn btn-navbar" type="submit">
								<i class="fas fa-search" style="color: #fff;"></i>
							</button>
						</div>
					</div>
					<!--  Menus dinamicos  -->
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" id="menu_items">
						<?php
						foreach ($menus->result() as $menu) {

						?>
							<li class="nav-item has-treeview">
								<a href="#" class="nav-link">
									<i class="nav-icon <?= $menu->icono ?>"></i>
									<p>
										<?= $menu->menu ?>
										<i class="right fas fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<?php
									$this->load->model('menus');
									$submenus = $this->menus->getSubmenusByPerfil($menu->id_menu, $menu->id_perfil);
									foreach ($submenus->result() as $submenu) {
									?>
										<li class="nav-item">
											<a style="white-space:normal; display:flex;" href="<?= base_url() ?><?= $submenu->vista ?>" class="nav-link">
												<i style="align-self: center;" class="<?= $submenu->icono ?> nav-icon"></i>
												<p><?= $submenu->submenu ?></p>
											</a>
										</li>
									<?php
									}
									?>
								</ul>
							</li>
						<?php
						}
						?>

					</ul>
				</nav>
				<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<br>
			<!-- Main content -->
			<section class="content">
				<!-- contenido aqui -->
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?= base_url() ?>usuarios_admin">Usuarios /</a></li>
					</ol>
				</nav>
				<div class="card">
					<div class="card-header" align="center">
						<h3>Administracion de Usuarios</h3>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<a href="#" class="btn btn-info" data-toggle="modal" data-target="#modal_crear_usuario">Nuevo usuario</a>
							</div>
							<div class="col-md-6">
								<a href="#" class="btn btn-warning" data-toggle="modal" data-target="#modal_crear_jefe">Nuevo jefe</a>
							</div>
						</div>

						<hr>
						<div class="table-responsive">
							<!--  Tabla usuarios  -->
							<table id="example1" class="table table-sm table-bordered table-hover">
								<thead>
									<tr align="center">
										<th>ID USUARIO</th>
										<th>NOMBRE</th>
										<th>USUARIO</th>
										<th>PERFIL</th>
										<th>ACCIONES</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($allusers->result() as $key) {
									?>
										<tr align="center">
											<td><?= $key->id_usuario ?></td>
											<td><?= $key->nombres ?></td>
											<td><?= $key->nit ?></td>
											<td><?= $key->nom_perfil ?></td>
											<td>
												<a href="#" onclick="mostrarsubmenus('<?= $key->id_usuario ?>')"><i class="far fa-edit btn btn-outline-warning"></i></a>
												<?php
												if ($key->estado == 0) {
												?>
													<a href="<?= base_url() ?>usuarios_admin/activar_OR_desactivar?act=1&id_usu=<?= $key->id_usuario ?>"><i class="far btn btn-outline-secondary fa-check-circle"></i></a>
												<?php
												} elseif ($key->estado == 1) {
												?>
													<a href="<?= base_url() ?>usuarios_admin/activar_OR_desactivar?act=0&id_usu=<?= $key->id_usuario ?>"><i class="far btn btn-secondary fa-times-circle" style="background-color: #2F3C4F;"></i></a>
												<?php
												}
												?>
												<a href="<?= base_url() ?>usuarios_admin/add_sedes?id_usu=<?= $key->id_usuario ?>"><i class="far btn btn-outline-info fa-building"></i></a>

												<a id="<?= $key->nit ?>" onclick="idUsuario(this.id);cargarJefesEmp(this.id);" namme="<?= $key->nit ?>" data-toggle="modal" data-target="#agregarjefes" title="Agregar jefes"><i class="btn btn-outline-success fas fa-user"></i></a>
											</td>
										</tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</section>
			<!--modal para asignar jefes a empleados-->
			<div class="modal fade" id="agregarjefes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="border-radius:100px !important;">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title col-lg-12 text-center lead" id="exampleModalLabel">Asignar Jefes</h5>
						</div>
						<div class="modal-body">
							<form>
								<div class="form-group">
									<input type="text" class="form-control d-none" id="id_user" name="id_user" aria-describedby="emailHelp">
								</div>
								<div class="form-group">
									<label for="exampleInputPassword1">Selecciona el jefe</label>

									<select class="form-control js-example-theme-multiple" id="idjefe" name="idjefe[]" multiple="multiple" style="width: 100%">
										<option class="form-control" value="no">Seleccione los jefes</option>
										<?php foreach ($listadejefes->result() as $listajefes) : ?>
											<option value="<?= $listajefes->id_jefe ?>"><?= $listajefes->nombres ?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div id="rest"></div>
								<div class="modal-footer">
									<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
									<button onclick="agragar_jefes()" type="button" class="btn btn-success">Asignar</button>
								</div>

								<div id="resultadoTablaJefes"></div>

							</form>

						</div>

					</div>
				</div>
			</div>


			<script>
				function idUsuario(id) {
					var id_empelado = document.getElementById('id_user').value = id;
				}

				function agragar_jefes() {
					var id_jefes = $('#idjefe').val();
					var id_empleado = document.getElementById('id_user').value;
					var valida = document.getElementById('rest');

					if (id_jefes == "no" || id_jefes == "") {
						valida.innerHTML = '<div class="alert alert-warning  text-center fade show" role="alert">EL CAMPO NO DEBE IR VACIO!.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
					} else {

						var datos = new FormData();
						datos.append('id_empleado', id_empleado);
						datos.append('id_jefes', id_jefes);
						var url = '<?= base_url() ?>Usuarios_admin/insertar_empleados_a_jefes';
						var request = new XMLHttpRequest();

						request.onreadystatechange = function() {
							if (request.readyState === 4 && request.status === 200) {
								if (request.responseText == 1) {
									cargarJefesEmp(id_empleado);
								} else {
									alert('Error al asignar jefes al empleado');
								}
							}

						}
						request.open("POST", url);
						request.send(datos);
					}
				}
			</script>

			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->
		<footer class="main-footer">
			<strong>Copyright &copy; 2020 <a href="http://adminlte.io">CODIESEL</a>.</strong>
			Todos los derechos reservados.
			<div class="float-right d-none d-sm-inline-block">
				<b>Version</b> 1.0.0-pre
			</div>
		</footer>

		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<!-- Control sidebar content goes here -->
		</aside>
		<!-- /.control-sidebar -->
	</div>


	<!-- Logout Modal-->
	<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">'¿Has terminado ya?'</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">Estas seguro que deseas cerrar sesion</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" type="button" data-dismiss="modal">No</button>
					<a class="btn btn-primary" href="<?= base_url() ?>login/logout">Si</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal editar usuario -->
	<div class="modal fade" id="modal-editar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Actualizar Usuario</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post" action="<?= base_url() ?>usuarios_admin/actualizarUsuario">
						<div class="form-group row">
							<div class="col-md-12" id="perfiles_content">
								<div id="usuarios_content" class="" align="center">

								</div>
							</div>
						</div>
						<div class="row" align="center">
							<div class="col-md-12">
								<button class="btn btn-secondary">Actualizar Usuario</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


	<!-- Modal CREAR USUARIO-->
	<div class="modal fade" id="modal_crear_usuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<h5 class="modal-title col-lg-12 text-center lead" id="exampleModalLabel">Nuevo usuario</h5>
				</div>

				<div class="modal-body">
					<form method="POST" id="datosUser">
						<!--action="<?= base_url() ?>usuarios_admin/crear_usuario"-->
						<div class="form-group">
							<label for="exampleInputEmail1">NIT/CEDULA</label>
							<input type="text" class="form-control" id="cedula" name="cedula" placeholder="Ingrese NIT/CC" required="true">
							<small id="emailHelp" class="form-text text-muted">El NIT debe ser SIN digito de verificación</small>
						</div>
						<div class="form-group">
							<label for="perfil">Perfil</label>
							<select class="form-control js-example-theme-multiple" name="perfil" id="perfil" required="true" style="width: 100%">
								<option value="">Seleccione una Opción...</option>
								<?php
								foreach ($perfiles->result() as $key) {
								?>
									<option value="<?= $key->id_perfil ?>"><?= $key->nom_perfil ?></option>
								<?php } ?>
							</select>
						</div>
						<div id="respuesta"></div>
						<div id="respuestaCedula"></div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
							<button onclick="validar();" type="button" class="btn btn-success">Crear Usuario</button>
						</div>

					</form>
					<!--formulario crado para grgar listado de jefes-->
					<form id="formularioFefes" class="d-none">
						<div id="pintarformualrio"></div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success">Agregar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal CREAR JEFE-->
	<div class="modal fade" id="modal_crear_jefe" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<h5 class="modal-title col-lg-12 text-center lead" id="exampleModalLabel">Nuevo Jefe</h5>
				</div>

				<div class="modal-body">
					<form method="POST" id="datosJefe" name="datosJefe">
						<!--action="<?= base_url() ?>usuarios_admin/crear_usuario"-->
						<div class="form-group">
							<label for="nitJefe">USUARIO:</label>
							<select class="form-control js-example-theme-multiple" name="nitJefe" id="nitJefe" required="true" style="width: 100%">
								<option value="">Seleccione una Opción...</option>
								<?php
								foreach ($allusers->result() as $key) {
								?>
									<option value="<?= $key->nit ?>"><?= $key->nombres ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label for="mailJefe">Correo:</label>
							<input class="form-control" type="email" name="mailJefe" id="mailJefe" required="true">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
							<button onclick="eventoFormCrearJefe();" type="submit" class="btn btn-success">Crear Jefe</button>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>

	<!------------------FORMULARIO PARA REGISTRAR NUMERO DE DOCUMENTO EN LA TABLA POSTV_EMPLEADOS------------->
	<div class="container">
		<div class="row">
			<form class="" id="infousuarioCreado">
				<div class="form-group">
					<input type="text" class="form-control" id="DocumentoUsuarioCreado" name="DocumentoUsuarioCreado">
				</div>
			</form>

		</div>
	</div>
	<!----------- FUNCION PARA REGISTRAR NUMERO DE DOCUMENTO EN LA TABLA POSTV_EMPLEADOS-------------->
	<script>
		function AgregarUser() {
			var cedula = document.getElementById('DocumentoUsuarioCreado').value;
			var respuesta = document.getElementById('respuestaCedula');
			var pintarFormulario = document.getElementById('pintarformualrio');

			if (cedula == "") {
				respuesta.innerHTML = '<div class="alert alert-danger  text-center fade show" role="alert">El NIT NO FUE CAPTURADO .<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
			} else {
				//definir ruta
				var url = '<?= base_url() ?>usuarios_admin/insertarNitUser';
				//definir el id del formulario para recoger los datos
				var DatosFormulario = document.getElementById('infousuarioCreado');
				//crear objeto de la clase XMLHttpRequest
				var request = new XMLHttpRequest();
				//valioras respuesta de la peticion HTTP
				request.onreadystatechange = function() {
					if (request.readyState === 4 && request.status === 200) {
						//pinta datos en la vista por medio de innerhtml 
						document.getElementById('datosUser').style.display = 'none';
						pintarFormulario.innerHTML = request.responseText;
					}
				}
				//definiendo metodo y ruta
				request.open("POST", url);
				//envar los dattos creando un objeto de clse fordata del formulario
				request.send(new FormData(DatosFormulario));
			}

		}
	</script>

	<script>
		function validar() {
			var cedula = document.getElementById('cedula').value;
			var perfil = document.getElementById('perfil').value;
			var respuesta = document.getElementById('respuesta');
			var recogerdocumento = document.getElementById('DocumentoUsuarioCreado').value = cedula;


			if (cedula == "" || perfil == "") {
				Swal.fire({
					title: 'Advertencia!',
					text: 'Debe llenar los campos del formulario',
					icon: 'warning',
					confirmButtonText: 'Ok'
				});
			} else {
				//definir ruta
				var url = '<?= base_url() ?>usuarios_admin/crear_usuario';
				//definir el id del formulario para recoger los datos
				var formulario = document.getElementById("datosUser");
				//crear objeto de la clase XMLHttpRequest
				var request = new XMLHttpRequest();
				//valioras respuesta de la peticion HTTP
				request.onreadystatechange = function() {
					if (request.readyState === 4 && request.status === 200) {
						//pinta datos en la vista por medio de innerhtml 
						if (request.responseText == 0) {
							Swal.fire({
								title: 'Advertencia!',
								text: 'El usuario aun no ha sido creado en la tabla terceros',
								icon: 'warning',
								confirmButtonText: 'Ok'
							});
						} else if (request.responseText == 1) {
							Swal.fire({
								title: 'Exito!',
								text: 'El usuario ha sido creado con exito',
								icon: 'success',
								confirmButtonText: 'Ok',
								allowOutsideClick: false
							}).then((result) => {
								/* Read more about isConfirmed, isDenied below */
								if (result.isConfirmed) {
									window.location.reload();
								}
							});
						} else if (request.responseText == 2) {
							Swal.fire({
								title: 'Advertencia!',
								text: 'Error al intentar de crear el usuario',
								icon: 'success',
								confirmButtonText: 'Ok'
							});
						} else if (request.responseText == 3) {


							Swal.fire({
								title: 'Advertencia!',
								text: 'El usuario ya ha sido registrado anteriormente',
								icon: 'warning',
								confirmButtonText: 'Ok'

							});

						}
					}
				}
				//definiendo metodo y ruta
				request.open("POST", url);
				//envar los dattos creando un objeto de clse fordata del formulario
				request.send(new FormData(formulario));

				/* AgregarUser(); */

			}

		}
	</script>

	<!-- PASS Modal-->
	<div class="modal" tabindex="-1" id="pass-modal" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Es necesario que cambies tu contraseña</h5>
				</div>
				<div class="modal-body">
					<!-- <form method="POST" action="<?= base_url() ?>usuarios_admin/changepass"> -->
					<form>
						<div class="row">
							<div class="col">
								<label for="pass1">Ingrese la nueva contraseña</label>
								<input type="password" id="pass1_one" name="pass2_one" class="form-control" placeholder="Ingrese nueva contraseña">
							</div>
							<div class="col">
								<label for="pass2">Confirme la contraseña</label>
								<input type="password" id="pass2_one" name="pass1_one" class="form-control" placeholder="Confirma la contraseña">
								<?php
								foreach ($userdata->result() as $key) {
								?>
									<input type="hidden" id="id_usu_one" name="id_usu" value="<?= $key->id_usuario ?>">
								<?php
								}
								?>
							</div>
						</div>

				</div>
				<div class="modal-footer">
					<a href="<?= base_url() ?>login/logout" class="btn btn-secondary">Cerrar</a>
					<button type="button" class="btn btn-primary" onclick="cambiarPass_One();">Cambiar</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- PASS Modal-->
	<div class="modal" tabindex="-1" id="pass-modal2" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Cambio de Contraseña</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<!-- <form method="POST" action="<?= base_url() ?>usuarios_admin/changepass"> -->
					<form>
						<div class="row">
							<div class="col">
								<label for="pass1">Ingrese la nueva contraseña</label>
								<input type="password" id="pass1_two" name="pass2" class="form-control" placeholder="Ingrese nueva contraseña">
							</div>
							<div class="col">
								<label for="pass2">Confirme la contraseña</label>
								<input type="password" id="pass2_two" name="pass1" class="form-control" placeholder="Confirma la contraseña">
								<?php
								foreach ($userdata->result() as $key) {
								?>
									<input type="hidden" id="id_usu_two" name="id_usu" value="<?= $key->id_usuario ?>">
								<?php
								}
								?>
							</div>
						</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="cambiarPass_Two();">Cambiar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="notifi"></div>

	<!-- alertas -->
	<?php
	$log = $this->input->get('log');
	if ($log == "ok") {

	?>
		<div class="alert alert-success alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
			Operacion realizada con exito
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	<?php
	}
	?>
	<!-- jQuery -->
	<script src="<?= base_url() ?>plugins/jquery/jquery.min.js"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="<?= base_url() ?>plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
		$.widget.bridge('uibutton', $.ui.button)
	</script>
	<!--select2-->
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- ChartJS -->
	<script src="<?= base_url() ?>plugins/chart.js/Chart.min.js"></script>
	<!-- Sparkline 
	<script src="<?= base_url() ?>plugins/sparklines/sparkline.js"></script>
	-->
	<!-- JQVMap -->
	<script src="<?= base_url() ?>plugins/jqvmap/jquery.vmap.min.js"></script>
	<script src="<?= base_url() ?>plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
	<!-- jQuery Knob Chart -->
	<script src="<?= base_url() ?>plugins/jquery-knob/jquery.knob.min.js"></script>
	<!-- daterangepicker -->
	<script src="<?= base_url() ?>plugins/moment/moment.min.js"></script>
	<script src="<?= base_url() ?>plugins/daterangepicker/daterangepicker.js"></script>
	<!-- Tempusdominus Bootstrap 4 -->
	<script src="<?= base_url() ?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
	<!-- Summernote -->
	<script src="<?= base_url() ?>plugins/summernote/summernote-bs4.min.js"></script>
	<!-- overlayScrollbars -->
	<script src="<?= base_url() ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- DataTables -->
	<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
	<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
	<!-- AdminLTE App -->
	<script src="<?= base_url() ?>dist/js/adminlte.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="<?= base_url() ?>dist/js/pages/dashboard.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="<?= base_url() ?>dist/js/demo.js"></script>
	<!-- SweetAlert2 -->
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<!--                   DOM                            -->
	<script type="text/javascript">
		$(document).ready(function() {
			$("#buscar_items").on("keyup", function() {
				var value = $(this).val().toLowerCase();
				$("#menu_items li").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
			});
			setInterval('notificacion()', 10000);
		});

		function notificacion() {
			/*
			var result = document.getElementById("notifi");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					result.innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "<?= base_url() ?>admin_presupuesto/notifi_prueba?var=1", true);
			xmlhttp.send();
			*/
		}
	</script>
	<!--  Funcion que cierra los alerts  -->
	<script type="text/javascript">
		setTimeout(function() {
			$('#alert_err').alert('close');
		}, 1500);
		setTimeout(function() {
			$('#alert_ok').alert('close');
		}, 1500);
	</script>
	<!-- Funcion que cambia el lenguaje al datatable -->
	<script>
		$(document).ready(function() {
			$('#example1').DataTable({
				"paging": true,
				"pageLength": 25,
				"lengthChange": true,
				"searching": true,
				"ordering": true,
				"info": true,
				"autoWidth": false,
				"language": {
					"sProcessing": "Procesando...",
					"sLengthMenu": "Mostrar _MENU_ registros",
					"sZeroRecords": "No se encontraron resultados",
					"sEmptyTable": "Ningún dato disponible en esta tabla =(",
					"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
					"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
					"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
					"sInfoPostFix": "",
					"sSearch": "Buscar:",
					"sUrl": "",
					"sInfoThousands": ",",
					"sLoadingRecords": "Cargando...",
					"oPaginate": {
						"sFirst": "Primero",
						"sLast": "Último",
						"sNext": "Siguiente",
						"sPrevious": "Anterior"
					},
					"oAria": {
						"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
						"sSortDescending": ": Activar para ordenar la columna de manera descendente"
					},
					"buttons": {
						"copy": "Copiar",
						"colvis": "Visibilidad"
					}
				}
			});
		});
	</script>
	<!--  ajax para el editar de usuarios   -->
	<script type="text/javascript">
		function mostrarsubmenus(user) {
			var result = document.getElementById("usuarios_content");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					result.innerHTML = xmlhttp.responseText;

					$("#combo_perfil").select2({
						theme: "classic",
						width: '100%'
					});
				}
			}
			console.log(user);
			xmlhttp.open("GET", "<?= base_url() ?>usuarios_admin/info_update?idu=" + user, true);
			xmlhttp.send();
			if (user != null) {
				$('#modal-editar').modal('show');
			}

		}
	</script>
	<!--  inicalizar seelct2   -->
	<script>
		$(".js-example-theme-multiple").select2({
			theme: "classic",
			placeholder: 'Seleccione una opción'
		});
	</script>
	<!-- 
		Script para cargar los jefes asignados al empleado
		Realizado por: Sergio Gavlis
		fecha: 04/Agosto/20022
	 -->
	<script>
		function cargarJefesEmp(id) {
			var result = document.getElementById("resultadoTablaJefes");

			var datos = new FormData();
			datos.append('id', id);
			var url = '<?= base_url() ?>Usuarios_admin/ViewUpdateEmpleadoJefe';
			if (window.XMLHttpRequest) {
				request = new XMLHttpRequest();
			} else {
				request = new ActiveXObject("Microsoft.XMLHTTP");
			}

			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					result.innerHTML = request.responseText
				}

			}
			request.open("POST", url);
			request.send(datos);

		}

		function eliminarJefeEmpleado(jefe, empleado) {
			var nit_empleado = document.getElementById('id_user').value;
			var result = document.getElementById("resultadoTablaJefes");

			var datos = new FormData();
			datos.append('jefe', jefe);
			datos.append('empleado', empleado);
			var url = '<?= base_url() ?>Usuarios_admin/EliminarEmpleadoJefe';
			if (window.XMLHttpRequest) {
				request = new XMLHttpRequest();
			} else {
				request = new ActiveXObject("Microsoft.XMLHTTP");
			}

			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					if (request.responseText == 'Ok') {
						Swal.fire({
							title: 'Exito!',
							text: 'Jefe eliminado con exito',
							icon: 'success',
							confirmButtonText: 'Ok'
						});
						cargarJefesEmp(nit_empleado);
					} else if (request.responseText == 'Error') {
						Swal.fire({
							title: 'Advertencia!',
							text: 'Ha ocurrido un error al intentar eliminar el jefe asignado',
							icon: 'warning',
							confirmButtonText: 'Ok'
						});
					}

				}

			}
			request.open("POST", url);
			request.send(datos);
		}
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 5000
		});
		// eventos del formulario crear Jefe ...
		function eventoFormCrearJefe() {
			var form = document.getElementById('datosJefe');
			if (form.attachEvent) {
				form.attachEvent("submit", crearJefe);
			} else {
				form.addEventListener("submit", crearJefe);
			}
		}

		function crearJefe(e) {
			if (e.preventDefault) e.preventDefault(); //evita que se envien los datos si los campos están vacios

			var formulario = document.getElementById("datosJefe");
			formData = new FormData(formulario);
			var url = '<?= base_url() ?>Usuarios_admin/insertJefe';

			if (window.XMLHttpRequest) {
				request = new XMLHttpRequest();
			} else {
				request = new ActiveXObject("Microsoft.XMLHTTP");
			}

			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					if (request.responseText == 'Ok') {
						Swal.fire({
							title: 'Exito!',
							text: 'Jefe agregado con exito',
							icon: 'success',
							confirmButtonText: 'Ok'
						});
						formulario.reset();
					} else if (request.responseText == 'Error') {
						Swal.fire({
							title: 'Advertencia!',
							text: 'Ha ocurrido un error al intentar agregar el jefe',
							icon: 'warning',
							confirmButtonText: 'Ok'
						});

					}

				}

			}
			request.open("POST", url);
			request.send(formData);

			return false;
		}
	</script>
	<script src="<?= base_url() ?>dist/js/md5.js"></script>
	<script>
		function cambiarPass_One() {
			console.log('Cambiando contraseña');
			let pass1 = document.getElementById('pass1_one').value;
			let pass2 = document.getElementById('pass2_one').value;
			let id_usuario = document.getElementById('id_usu_one').value;
			let clave = hex_md5(pass1);
			console.log(pass1 + "=" + pass2);
			if (pass1 === pass2 && pass1 != "" && pass2 != "") {
				let form = new FormData();
				/* 
					$pass1 = $this->input->POST('pass1');
					$pass2 = $this->input->POST('pass2');
					$id_usu = $this->input->POST('id_usu');
					$clave = $this->input->POST('clave'); 
				*/
				form.append('pass1', pass1);
				form.append('pass2', pass2);
				form.append('id_usu', id_usuario);
				form.append('clave', clave);
				var xmlhttp;
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						var resp = xmlhttp.responseText;
						if (resp == 1) {
							Swal.fire({
								title: 'Exito!',
								text: 'Se ha cambiado con exito la contraseña',
								icon: 'success',
								confirmButtonText: 'Ok',
								allowOutsideClick: false
							}).then((result) => {
								/* Read more about isConfirmed, isDenied below */
								if (result.isConfirmed) {
									window.location.reload();
								}
							});
						} else if (resp == 2) {
							Swal.fire({
								title: 'Error!',
								text: 'No se ha actualizado la contraseña.',
								icon: 'error',
								confirmButtonText: 'Ok',
								allowOutsideClick: false
							}).then((result) => {
								/* Read more about isConfirmed, isDenied below */
								if (result.isConfirmed) {
									location.reload();
								}
							});
						}

					}
				}
				xmlhttp.open("POST", "<?= base_url() ?>usuarios_admin/changepass", true);
				xmlhttp.send(form);
			} else {
				Swal.fire({
					title: 'Error!',
					text: 'Las contraseñas no coinciden',
					icon: 'error',
					confirmButtonText: 'Cerrar'
				});
			}
		}

		function cambiarPass_Two() {
			console.log('Cambiando contraseña');
			let pass1 = document.getElementById('pass1_two').value;
			let pass2 = document.getElementById('pass2_two').value;
			let id_usuario = document.getElementById('id_usu_two').value;
			let clave = hex_md5(pass1);
			console.log(pass1 + "=" + pass2);
			if (pass1 === pass2 && pass1 != "" && pass2 != "") {
				let form = new FormData();
				form.append('pass1', pass1);
				form.append('pass2', pass2);
				form.append('id_usu', id_usuario);
				form.append('clave', clave);
				var xmlhttp;
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						var resp = xmlhttp.responseText;
						if (resp == 1) {
							Swal.fire({
								title: 'Exito!',
								text: 'Se ha actualizado la contraseña.',
								icon: 'success',
								confirmButtonText: 'Ok',
								allowOutsideClick: false
							}).then((result) => {
								/* Read more about isConfirmed, isDenied below */
								if (result.isConfirmed) {
									location.reload();
								}
							});
						} else if (resp == 2) {
							Swal.fire({
								title: 'Error!',
								text: 'No se ha actualizado la contraseña.',
								icon: 'error',
								confirmButtonText: 'Ok',
								allowOutsideClick: false
							}).then((result) => {
								/* Read more about isConfirmed, isDenied below */
								if (result.isConfirmed) {
									location.reload();
								}
							});
						}

					}
				}
				xmlhttp.open("POST", "<?= base_url() ?>usuarios_admin/changepass", true);
				xmlhttp.send(form);
			} else {
				Swal.fire({
					title: 'Error!',
					text: 'Las contraseñas no coinciden',
					icon: 'error',
					confirmButtonText: 'Cerrar'
				});
			}



		}
	</script>
</body>

</html>