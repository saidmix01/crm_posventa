<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Post Venta</title>
	<!-- Le dice al navegador que la web es responsiva -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Estilos Font Awesome -->
	<link rel="stylesheet" href="<?= base_url() ?>plugins/fontawesome-free/css/all.min.css">
	<!-- Pack de Iconos Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Estilos Tempusdominus Bootstrap 4 -->
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
				<div class="card">
					<div class="card-body">
						<h3 class="col-lg-12 text-center">Informe NPS por tecnicos</h3>
						<hr>
						<form>
							<div class="form-row">
								<div class="col">
									<div class="form-group">
										<label for="origen">Origen de datos</label>
										<select class="form-control" id="origen">
											<option value="0">Seleccione una opción...</option>
											<option value="nps_int">NPS Interno</option>
											<option value="nps_col">NPS Colmotores</option>
										</select>
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label for="sede">Sede</label>
										<select class="form-control" id="sede" name="sede">
											<option value="todas">Todas</option>
											<option value="giron">Girón</option>
											<option value="rosita">La Rosita</option>
											<option value="bocono">Cúcuta Boconó</option>
											<option value="barranca">Barrancabermeja</option>
										</select>
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label for="sede">Mes</label>
										<select class="form-control" id="mes" name="mes">
											<option value="0">Todos</option>
											<option value="1">Enero</option>
											<option value="2">Febrero</option>
											<option value="3">Marzo</option>
											<option value="4">Abril</option>
											<option value="5">Mayo</option>
											<option value="6">Junio</option>
											<option value="7">Julio</option>
											<option value="8">Agosto</option>
											<option value="9">Septiembre</option>
											<option value="10">Octubre</option>
											<option value="11">Noviembre</option>
											<option value="12">Diciembre</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col" align="center">
								<a href="#" class="btn btn-primary" onclick="cargar_resultados();">Buscar</a>
							</div>
						</form>
						<hr>
						<div class="card">
							<div class="card-header" align="right">
								<a href="#" class="btn btn-success" onclick="toexcel();"><i class="far fa-file-excel"></i> Exportar a excel</a>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table">
										<thead class="thead-dark" align="center">
											<tr>
												<th scope="col">Tecnico</th>
												<th scope="col">NPS</th>
												<th scope="col">Cantidad de encuestas 0 a 6</th>
												<th scope="col">Cantidad de encuestas 7 a 8</th>
												<th scope="col">Cantidad de encuestas 9 a 10</th>
												<th scope="col">Mes</th>
												<th scope="col">Detalle</th>
											</tr>
										</thead>
										<tbody id="tabla" align="center">
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- /.content -->


		</div>
		<div class='modal fade bd-example-modal-xl' tabindex='-1' role='dialog' aria-labelledby='myExtraLargeModalLabel' aria-hidden='true'>
			<div class='modal-dialog modal-xl'>
				<div class='modal-content'>
					<div class='modal-body'>

						<div id="respuesta"></div>

					</div>
					<div class='modal-footer'>
						<button type='button' class='btn btn-danger' data-dismiss='modal'>Cerrar</button>
					</div>
				</div>
			</div>
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

	<!-- MENSAJE FLOTANTE-->
	<?php
	$log = $this->input->get('log');
	if ($log == "err_p") {
	?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert_err" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
			Error... Las contraseñas no coinciden
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	<?php } ?>
	<div id="notifi"></div>


	<!-- jQuery -->
	<script src="<?= base_url() ?>plugins/jquery/jquery.min.js"></script>
	<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="<?= base_url() ?>plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
		$.widget.bridge('uibutton', $.ui.button)
	</script>
	<!-- Bootstrap 4 -->
	<script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- overlayScrollbars -->
	<script src="<?= base_url() ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?= base_url() ?>dist/js/adminlte.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="<?= base_url() ?>dist/js/pages/dashboard.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script type="text/javascript">
		$(document).ready(function() {
					$("#buscar_items").on("keyup", function() {
						var value = $(this).val().toLowerCase();
						$("#menu_items li").filter(function() {
							$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
						});

					});
				});
	</script>
	<script src="<?= base_url() ?>dist/js/demo.js"></script>
	<?php
	$this->load->model('usuarios');
	$usu = $this->usuarios->getUserById($id_usu);
	$p = "";
	$nit = "";
	foreach ($usu->result() as $key) {
		$p = $key->pass;
		$nit = $key->nit;
	}
	//echo $p." ".$nit;
	$this->load->library('encrypt');
	$pass_desencript = $this->encrypt->decode($p);
	if ($pass_desencript == $nit) {
	?>
		<script type="text/javascript">
			$('#pass-modal').show('true')
		</script>
	<?php
	}
	?>
	<script type="text/javascript">
		setTimeout(function() {
			$('#alert_err').alert('close');
		}, 1500);
	</script>

	<script type="text/javascript">
		function cargar_resultados() {
			var origen = $('#origen').val();
			var sede = $('#sede').val();
			var mes = $('#mes').val();
			if (origen != "0") {
				if (origen == "nps_int") {
					if (sede == "todas") {
						var result = document.getElementById("tabla");
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
						xmlhttp.open("GET", "<?= base_url() ?>Informes/listar_nps_tec?var=1&mes=" + mes, true);
						xmlhttp.send();
					} else if (sede == "giron") {
						var result = document.getElementById("tabla");
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
						xmlhttp.open("GET", "<?= base_url() ?>Informes/listar_nps_tec?var=2&mes=" + mes, true);
						xmlhttp.send();
					} else if (sede == "rosita") {
						var result = document.getElementById("tabla");
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
						xmlhttp.open("GET", "<?= base_url() ?>Informes/listar_nps_tec?var=3&mes=" + mes, true);
						xmlhttp.send();
					} else if (sede == "bocono") {
						var result = document.getElementById("tabla");
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
						xmlhttp.open("GET", "<?= base_url() ?>Informes/listar_nps_tec?var=4&mes=" + mes, true);
						xmlhttp.send();
					} else if (sede == "barranca") {
						var result = document.getElementById("tabla");
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
						xmlhttp.open("GET", "<?= base_url() ?>Informes/listar_nps_tec?var=5&mes=" + mes, true);
						xmlhttp.send();
					}
				} else if (origen == "nps_col") {
					//NPS COLMOTORES
					if (sede == "todas") {
						var result = document.getElementById("tabla");
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
						xmlhttp.open("GET", "<?= base_url() ?>Informes/listar_nps_tec_col?var=1&mes=" + mes, true);
						xmlhttp.send();
					} else if (sede == "giron") {
						var result = document.getElementById("tabla");
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
						xmlhttp.open("GET", "<?= base_url() ?>Informes/listar_nps_tec_col?var=2&mes=" + mes, true);
						xmlhttp.send();
					} else if (sede == "rosita") {
						var result = document.getElementById("tabla");
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
						xmlhttp.open("GET", "<?= base_url() ?>Informes/listar_nps_tec_col?var=3&mes=" + mes, true);
						xmlhttp.send();
					} else if (sede == "bocono") {
						var result = document.getElementById("tabla");
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
						xmlhttp.open("GET", "<?= base_url() ?>Informes/listar_nps_tec_col?var=4&mes=" + mes, true);
						xmlhttp.send();
					} else if (sede == "barranca") {
						var result = document.getElementById("tabla");
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
						xmlhttp.open("GET", "<?= base_url() ?>Informes/listar_nps_tec_col?var=5&mes=" + mes, true);
						xmlhttp.send();
					}
				}
			} else {
				alert("Debe seleccionar un origen");
			}
		}
	</script>
	<script type="text/javascript">
		function toexcel() {
			$("#tabla").table2excel({
				exclude: ".noExl",
				name: "Worksheet Name",
				filename: "NPS detallado por tecnico", //do not include extension
				fileext: ".xlsx" // file extension
			});
		}
	</script>
	<script>
		function traerdatos(id, nit, orden) {
			var mes = document.getElementById('mes').value;
			var nit = nit;
			var orden = orden;
			var bodega = document.getElementById('sede').value;
			var id = id;
			if (bodega == 'todas') {
				bodega = "1,9,11,21,7,6,19,8,14,16,22";
			} else if (bodega == giron) {
				bodega = "1,9,11,21";
			} else if (bodega == rosita) {
				bodega = "7";
			} else if (bodega == bocono) {
				bodega = "8,14,16,22";
			} else if (bodega == barranca) {
				bodega = "6,19";
			}

			if (mes == 0) {
				mes = "1,2,3,4,5,6,7,8,9,10,11,12";
			} else {
				var mes = document.getElementById('mes').value;
			}


			var respuesta = document.getElementById('respuesta');
			var url = '<?= base_url() ?>Informes/traerEncuesta';
			var request = new XMLHttpRequest();
			var datos = new FormData();
			datos.append('mes', mes);
			datos.append('nit', nit);
			datos.append('orden', orden);
			datos.append('bodega', bodega);
			datos.append('id', id);
			//valioras respuesta de la peticion HTTP
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					respuesta.innerHTML = request.responseText;
				}
			}
			//definiendo metodo y ruta
			request.open("POST", url);
			//envar los dattos creando un objeto de clse fordata del formulario
			request.send(datos);
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