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
	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="<?= base_url() ?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
	<!-- Toastr -->
	<link rel="stylesheet" href="<?= base_url() ?>plugins/toastr/toastr.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed" onload="mostrarsubmenus();">
	<div class="wrapper">
		<!-- Navbar -->
		<?php $this->load->view('nav_user.php'); ?>
		<!-- <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: #2F3C4F;">
			
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" style="color: #fff;" href="#"><i class="fas fa-bars"></i></a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="<?= base_url() ?>login/iniciar" style="color: #fff;" class="nav-link"><i class="fas fa-home"></i>&nbsp;&nbsp; Inicio</a>
				</li>
			</ul>
			
			<ul class="navbar-nav ml-auto">
				
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
					<div class="dropdown-divider" style=""></div>
					<a href="#" class="dropdown-item" data-toggle="modal" data-target="#pass-modal2">
						<i class="fas fa-key mr-2"></i>Cambiar Contraseña
					</a>
					<div class="dropdown-divider" style=""></div>
					<a href="#" class="dropdown-item" data-toggle="modal" data-target="#pass-modal2">
						<i class="fas fa-user mr-2"></i>Cambiar Imagen de Perfil
					</a>
					<div class="dropdown-divider"></div>
					<a href="#" class="dropdown-item" data-toggle="modal" data-target="#logoutModal">
						<i class="fas far fa-sign-out-alt"></i> Cerrar Sesion
					</a>
					</div>
				</li>
			</ul>


		</nav> -->
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
						<input type="hidden" name="diaActualEsFestivo" id="diaActualEsFestivo" value="<?= $dia_actual ?>">
						<input type="hidden" name="fecha_actual" id="fecha_actual" value="<?= $fecha_actual ?>">
						<li class="nav-item has-treeview" onclick="inIntranetVentas(<?= $this->session->userdata('user') ?>);">
							<a href="#" class="nav-link">
								<i class="nav-icon fas fa-home"></i>
								<p>
									Intranet Ventas
									<i class="right fas fa-arrow-right"></i>
								</p>
							</a>
						</li>
						<li class="nav-item has-treeview" onclick="irIntranetActas(<?= $this->session->userdata('user') ?>);">
							<a href="#" class="nav-link">
								<i class="nav-icon fas fa-tasks"></i>
								<p>
									Actas Codiesel
									<i class="right fas fa-arrow-right"></i>
								</p>
							</a>
						</li>
						<li data-toggle="modal" data-target="#pausaActivas" class="nav-item has-treeview">
							<a href="#" class="nav-link">
								<i class="nav-icon fas fa-walking"></i>
								<p>
									Pausas Activas
									<i class="right fas fa-heart"></i>
								</p>
							</a>
						</li>

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
				<?php if ($this->session->userdata('perfil') == 31) {
					$this->load->view('admin/agentecc.php');
				?>
				<?php } ?>
				<?php
				if ($this->session->userdata('perfil') == 46) {
					$this->load->view('admin/Informe_solicitud_mto.php');
				}
				?>
				<?php if ($this->session->userdata('perfil') != 1 && $this->session->userdata('perfil') != 20 && $this->session->userdata('perfil') != 22 && $this->session->userdata('perfil') != 23 && $this->session->userdata('perfil') != 32 && $this->session->userdata('perfil') != 31 && $this->session->userdata('perfil') != 33 && $this->session->userdata('perfil') != 34 && $this->session->userdata('perfil') != 46 && $this->session->userdata('perfil') != 28) {
					$this->load->view('admin/empty.php');
				?>
				<?php } else if ($this->session->userdata('perfil') == 1 || $this->session->userdata('perfil') == 20 || $this->session->userdata('perfil') == 32) {
					$this->load->view('admin/admin.php');
				?>

				<?php } elseif ($this->session->userdata('perfil') == 22 || $this->session->userdata('perfil') == 23) {
					$this->load->view('admin/gerencia.php');
				?>
				<?php } elseif ($this->session->userdata('perfil') == 33) {
					$this->load->view('admin/jefe_taller.php');
				} elseif ($this->session->userdata('perfil') == 34) {

					$this->load->view('admin/asesor_rep');
				} ?>
				<?php if ($this->session->userdata('perfil') == 28) {
					$this->load->view('admin/compras');
				} ?>

			</section>
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


	<!-- Modales de nav_user.php -->

	<!-- Pausas acivas Modal   fecha: 06/08/2022 Autor:Sergio Galvis-->
	<div class="modal fade" id="pausaActivas" tabindex="-1" role="dialog" aria-labelledby="pausaActivas" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="pausaActivas">Pausas Activas</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row" id="ctxPausaActiva">
						Haz clic en el botón registrar para el cumplimiento de la pausa activa.
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-success" onclick="validarPausaActiva(<?= $this->session->userdata('user') ?>);" type="button" data-dismiss="modal">Registrar</button>

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
	<?php
	}
	?>
	<div id="notifi">

	</div>

	<!-- jQuery -->
	<script src="<?= base_url() ?>plugins/jquery/jquery.min.js"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="<?= base_url() ?>plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
		$.widget.bridge('uibutton', $.ui.button)
	</script>
	<!-- Bootstrap 4 -->
	<script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- ChartJS -->
	<script src="<?= base_url() ?>plugins/chart.js/Chart.min.js"></script>
	<!-- Sparkline -->
	<script src="<?= base_url() ?>plugins/sparklines/sparkline.js"></script>
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
	<!-- AdminLTE App -->
	<script src="<?= base_url() ?>dist/js/adminlte.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="<?= base_url() ?>dist/js/pages/dashboard.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	<!-- SweetAlert2 -->
	<!-- <script src="<?= base_url() ?>plugins/sweetalert2/sweetalert2.min.js"></script> -->
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- Toastr -->
	<script src="<?= base_url() ?>plugins/toastr/toastr.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#buscar_items").on("keyup", function() {
				var value = $(this).val().toLowerCase();
				$("#menu_items li").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
			});
			//setInterval('notificacion()',60000);
			//$('.t_sedes').hide();
			mostrar_sedes();
			oculta_tall_g();
			ocultar_tall_ro();
			ocultar_tall_ba();
			ocultar_tall_bo();
			ocultar_det_tall_g();
			ocultar_det_tall_di_bo();
			ocultar_det_tall_ba();

			const Toast = Swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 5000
			});
		});

		function ocultar_det_tall_ba() {
			$('#det_tall_di_ba').hide("swing");
			$('#det_tall_gas_ba').hide("swing");
		}

		function mostrar_det_tall_gas_ba() {
			if ($('#det_tall_gas_ba').is(':hidden')) {
				$('#det_tall_gas_ba').show("swing");
			} else {
				$('#det_tall_gas_ba').hide("swing");
				ocultar_det_tall_ba();
			}
		}

		function mostrar_det_tall_di_ba() {
			if ($('#det_tall_di_ba').is(':hidden')) {
				$('#det_tall_di_ba').show("swing");
			} else {
				$('#det_tall_di_ba').hide("swing");
			}
		}

		function ocultar_det_tall_di_bo() {
			$('#det_tall_di_bo').hide("swing");
			$('#det_tall_gas_bo').hide("swing");
			$('#det_tall_lyp_bo').hide("swing");
		}

		function mostrar_det_tall_gas_bo() {
			if ($('#det_tall_gas_bo').is(':hidden')) {
				$('#det_tall_gas_bo').show("swing");
			} else {
				$('#det_tall_gas_bo').hide("swing");
			}
		}

		function mostrar_det_tall_di_bo() {
			if ($('#det_tall_di_bo').is(':hidden')) {
				$('#det_tall_di_bo').show("swing");
			} else {
				$('#det_tall_di_bo').hide("swing");
			}
		}

		function mostrar_det_tall_lyp_bo() {
			if ($('#det_tall_lyp_bo').is(':hidden')) {
				$('#det_tall_lyp_bo').show("swing");
			} else {
				$('#det_tall_lyp_bo').hide("swing");
			}
		}

		function ocultar_det_tall_ro() {
			$('#det_tall_ro').hide("swing");
		}

		function mostrar_det_tall_ro() {
			if ($('#det_tall_ro').is(':hidden')) {
				$('#det_tall_ro').show("swing");
			} else {
				$('#det_tall_ro').hide("swing");
			}
		}

		function ocultar_det_tall_g() {
			$('#det_tall_gas_g').hide("swing");
			$('#det_tall_di_g').hide("swing");
			$('#det_tall_lyp_g').hide("swing");
		}

		function mostrar_det_tall_gas_g() {
			if ($('#det_tall_gas_g').is(':hidden')) {
				$('#det_tall_gas_g').show("swing");
			} else {
				$('#det_tall_gas_g').hide("swing");
			}
		}

		function mostrar_det_tall_di_g() {
			if ($('#det_tall_di_g').is(':hidden')) {
				$('#det_tall_di_g').show("swing");
			} else {
				$('#det_tall_di_g').hide("swing");
			}
		}

		function mostrar_det_tall_lyp_g() {
			if ($('#det_tall_lyp_g').is(':hidden')) {
				$('#det_tall_lyp_g').show("swing");
			} else {
				$('#det_tall_lyp_g').hide("swing");
			}
		}

		function ocultar_tall_bo() {
			$('#tall_gas_bo').hide("swing");
			$('#tall_di_bo').hide("swing");
			$('#tall_lyp_bo').hide("swing");
			$('#tall_mos_bo').hide("swing");
		}

		function mostrar_tall_bo() {
			if ($('#tall_di_bo').is(':hidden')) {
				$('#tall_di_bo').show("swing");
			} else {
				$('#tall_di_bo').hide("swing");
			}
			if ($('#tall_gas_bo').is(':hidden')) {
				$('#tall_gas_bo').show("swing");
			} else {
				$('#tall_gas_bo').hide("swing");
			}
			if ($('#tall_lyp_bo').is(':hidden')) {
				$('#tall_lyp_bo').show("swing");
			} else {
				$('#tall_lyp_bo').hide("swing");
			}
			if ($('#tall_mos_bo').is(':hidden')) {
				$('#tall_mos_bo').show("swing");
			} else {
				$('#tall_mos_bo').hide("swing");
			}
			ocultar_det_tall_di_bo();
		}

		function oculta_tall_g() {
			$('#tall_gas_g').hide("swing");
			$('#tall_di_g').hide("swing");
			$('#tall_lyp_g').hide("swing");
			$('#tall_mos_g').hide("swing");
		}

		function mostrar_tall_g() {
			if ($('#tall_di_g').is(':hidden')) {
				$('#tall_di_g').show("swing");
			} else {
				$('#tall_di_g').hide("swing");
			}
			if ($('#tall_gas_g').is(':hidden')) {
				$('#tall_gas_g').show("swing");
			} else {
				$('#tall_gas_g').hide("swing");
			}
			if ($('#tall_lyp_g').is(':hidden')) {
				$('#tall_lyp_g').show("swing");
			} else {
				$('#tall_lyp_g').hide("swing");
			}
			if ($('#tall_mos_g').is(':hidden')) {
				$('#tall_mos_g').show("swing");
			} else {
				$('#tall_mos_g').hide("swing");
			}
			ocultar_det_tall_g();
		}



		function ocultar_tall_ba() {
			$('#tall_gas_ba').hide("swing");
			$('#tall_di_ba').hide("swing");
			$('#tall_mos_ba').hide("swing");
		}

		function mostrar_tall_ba() {
			if ($('#tall_di_ba').is(':hidden')) {
				$('#tall_di_ba').show("swing");
			} else {
				$('#tall_di_ba').hide("swing");
			}
			if ($('#tall_gas_ba').is(':hidden')) {
				$('#tall_gas_ba').show("swing");
			} else {
				$('#tall_gas_ba').hide("swing");
			}

			if ($('#tall_mos_ba').is(':hidden')) {
				$('#tall_mos_ba').show("swing");
			} else {
				$('#tall_mos_ba').hide("swing");
			}
			ocultar_det_tall_ba();
		}

		function ocultar_tall_ro() {
			$('#tall_gas_ro').hide("swing");
			$('#tall_mos_ro').hide("swing");
		}

		function mostrar_tall_ro() {
			if ($('#tall_gas_ro').is(':hidden')) {
				$('#tall_gas_ro').show("swing");
			} else {
				$('#tall_gas_ro').hide("swing");
			}
			if ($('#tall_mos_ro').is(':hidden')) {
				$('#tall_mos_ro').show("swing");
			} else {
				$('#tall_mos_ro').hide("swing");
			}
			ocultar_det_tall_ro();
		}

		function mostrar_sedes() {
			if ($('#t_sedes_giron').is(':hidden')) {
				$('#t_sedes_giron').show("swing");
			} else {
				$('#t_sedes_giron').hide("swing");
			}

			if ($('#t_sedes_rosita').is(':hidden')) {
				$('#t_sedes_rosita').show("swing");
			} else {
				$('#t_sedes_rosita').hide("swing");
			}

			if ($('#t_sedes_barranca').is(':hidden')) {
				$('#t_sedes_barranca').show("swing");
			} else {
				$('#t_sedes_barranca').hide("swing");
			}

			if ($('#t_sedes_bocono').is(':hidden')) {
				$('#t_sedes_bocono').show("swing");
			} else {
				$('#t_sedes_bocono').hide("swing");
			}

			if ($('#t_sedes_soloch').is(':hidden')) {
				$('#t_sedes_soloch').show("swing");
			} else {
				$('#t_sedes_soloch').hide("swing");
			}

			if ($('#t_sedes_chevr').is(':hidden')) {
				$('#t_sedes_chevr').show("swing");
			} else {
				$('#t_sedes_chevr').hide("swing");
			}
			ocultar_det_tall_g();
			ocultar_tall_bo();
			ocultar_tall_ro();
			ocultar_tall_ba();
			oculta_tall_g();
			ocultar_tall_ro();
			ocultar_det_tall_di_bo();
			ocultar_det_tall_ro();
			ocultar_det_tall_ba();
		}

		function notificacion() {
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
		}
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
	<?php
	$data_graf = array();
	if (isset($graf_sedes)) {
		foreach ($graf_sedes->result() as $key) {
			$data_graf[] = array('label' => $key->sede, 'y' => $key->total);
		}
	} else {
		$data_graf = array();
	}

	//print_r($data_graf);
	?>
	<script>
		window.onload = function() {

			var container = document.getElementById('chartContainer');

			if (container != null) {
				var chart = new CanvasJS.Chart("chartContainer", {
					animationEnabled: true,
					exportEnabled: true,
					title: {
						text: "Total vendido por Sedes"
					},
					axisY: {
						title: "Total en pesos",
						valueFormatString: "#0",
						suffix: "",
						prefix: "$"
					},
					data: [{
						type: "pie",
						markerSize: 5,
						xValueFormatString: "YYYY",
						yValueFormatString: "$#,##0.##",
						indexLabel: "{label} ({y})",
						xValueType: "dateTime",
						dataPoints: <?php echo json_encode($data_graf, JSON_NUMERIC_CHECK); ?>
					}]
				});

				chart.render();
			}



		}
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 5000
		});

		function cambiar_estado() {
			var estado = "";
			if (document.getElementById('chk_estado').checked) {
				estado = "Activo";
			} else {
				estado = "Inactivo";
			}
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
						Toast.fire({
							type: 'success',
							title: ' Cambio de estado exitoso'
						});
					} else {
						Toast.fire({
							type: 'error',
							title: ' Error al cambiar de estado'
						});
					}
					location.reload();
				}
			}
			xmlhttp.open("GET", "<?= base_url() ?>contaccenter/change_estado?estado=" + estado, true);
			xmlhttp.send();
		}
	</script>
	<?php if ($this->session->userdata('perfil') == 33) {
		$this->load->model('talleres');
		$date = $this->Informe->get_mes_ano_actual();
		$ano = $date->ano;
		$to_mo = 0;
		$to_tot = 0;
		$to_rep = 0;
		$total_v = 0;
		$mesActual = date("m");
		for ($i = $mesActual; $i >= 1; $i--) {
			$data_ventas_vendedor = $this->talleres->get_ventas_bod_graf($bod, $i, $ano);
			foreach ($data_ventas_vendedor->result() as $key) {
				$to_mo = $key->MO;
				$to_rep = $key->rptos;
				$to_tot = $key->TOT;
				$total_v = $key->rptos + $key->MO + $to_tot;
				$total_horas = $key->horas_facturadas;
				$dataPoints1[] = array("label" => $key->mes_nom, "y" => $key->MO);
				$dataPoints2[] = array("label" => $key->mes_nom, "y" => $key->rptos);
				$dataPoints3[] = array("label" => $key->mes_nom, "y" => $key->TOT);
				$dataPoints4[] = array("label" => $key->mes_nom, "y" => $total_v);
				$dataPoints5[] = array("label" => $key->mes_nom, "y" => $total_horas);
			}
			//NPS INTERNO
			$data_nps_tec = $this->Informe->get_nps_int_bod_graf($bod, $i, $ano);
			if (empty($data_nps_tec->result())) {
				$dataPoints6[] = array("label" => "", "y" => 0);
				$meta_nps_int[] = array("label" => "", "y" => 0);
			} else {
				$nps_int = 0;
				$to_enc = 0;
				$to_enc_06 = 0;
				$to_enc_78 = 0;
				$to_enc_910 = 0;
				$mes_n = "";
				foreach ($data_nps_tec->result() as $key) {
					$to_enc_06 = $to_enc_06 + $key->enc0a6;
					$to_enc_78 = $to_enc_78 + $key->enc7a8;
					$to_enc_910 = $to_enc_910 + $key->enc9a10;
					$mes_n = $key->mes_nom;
				}
				$meta_nps_int[] = array("label" => $mes_n, "y" => 80);
				$to_enc = $to_enc_06 + $to_enc_78 + $to_enc_910;
				$nps_int = (($to_enc_910 - $to_enc_06) / $to_enc) * 100;
				$dataPoints6[] = array("label" => $mes_n, "y" => $nps_int);
			}

			//NPS GM
			//CALIFICACION COLMOTORES
			$sede = "";
			switch ($bod) {
				case '1':
					$sede = "giron";
					break;
				case '6':
					$sede = "barranca";
					break;
				case '8,14,16,22':
					$sede = "bocono";
					break;
				case '8':
					$sede = "bocono";
					break;
				case '7':
					$sede = "rosita";
					break;

				default:
					// code...
					break;
			}
			$tecnicos = $this->Informe->get_nps_by_bod_gm_graf($sede, $i, $ano);
			if (empty($tecnicos->result())) {
				$dataPoints7[] = array("label" => "", "y" => 0);
				$meta_nps_gm[] = array("label" => "", 'y' => 0);
			} else {
				$nps_gm = 0;
				$to_enc = 0;
				$to_enc_06 = 0;
				$to_enc_78 = 0;
				$to_enc_910 = 0;
				$mes_n = "";
				foreach ($tecnicos->result() as $key) {
					$total_encu = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
					$nps = (($key->enc9a10 - $key->enc0a6) / $total_encu) * 100;
					$to_enc_06 = $to_enc_06 + $key->enc0a6;
					$to_enc_78 = $to_enc_78 + $key->enc7a8;
					$to_enc_910 = $to_enc_910 + $key->enc9a10;
					$mes_n = $key->mes_nom;
				}
				$meta_nps_gm[] = array("label" => $mes_n, 'y' => 81);
				$to_enc = $to_enc_06 + $to_enc_78 + $to_enc_910;
				$nps_gm = (($to_enc_910 - $to_enc_06) / $to_enc) * 100;
				$dataPoints7[] = array("label" => $mes_n, "y" => $nps_gm);
			}
		}
	?>
		<script type="text/javascript">
			window.onload = function() {

				var container2 = document.getElementById('chartContainer');

				if (container2 != null) {
					var chart = new CanvasJS.Chart("chartContainer", {
						title: {
							text: "Gráfica Total Vendido"
						},
						theme: "light1",
						animationEnabled: true,
						exportEnabled: true,
						toolTip: {
							shared: true,
							reversed: true
						},
						axisY: {
							title: "Valor en Pesos",
							suffix: ""
						},
						legend: {
							cursor: "pointer",
							itemclick: toggleDataSeries
						},
						data: [{
							type: "stackedColumn",
							name: "Mano de Obra",
							showInLegend: true,
							color: "#FF6C00",
							yValueFormatString: "#,##0.#",
							dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
						}, {
							type: "stackedColumn",
							name: "Repuestos",
							showInLegend: true,
							color: "#00FFEA",
							yValueFormatString: "#,##0.#",
							dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
						}, {
							type: "stackedColumn",
							name: "TOT",
							showInLegend: true,
							color: "#A569BD",
							yValueFormatString: "#,##0.#",
							dataPoints: <?php echo json_encode($dataPoints3, JSON_NUMERIC_CHECK); ?>
						}, {
							type: "line",
							name: "Total",
							showInLegend: true,
							color: "#45DC06",
							yValueFormatString: "#,##0.#",
							dataPoints: <?php echo json_encode($dataPoints4, JSON_NUMERIC_CHECK); ?>
						}]
					});
				}



				var charthoras = new CanvasJS.Chart("charthoras", {
					title: {
						text: "Gráfica Horas Facturadas"
					},
					theme: "light1",
					animationEnabled: true,
					exportEnabled: true,
					toolTip: {
						shared: true,
						reversed: true
					},
					axisY: {
						title: "Horas",
						suffix: ""
					},
					legend: {
						cursor: "pointer",
						itemclick: toggleDataSeries
					},
					data: [{
						type: "stackedColumn",
						name: "Horas Facturadas",
						showInLegend: true,
						color: "#000AFF",
						yValueFormatString: "#,##0.#",
						dataPoints: <?php echo json_encode($dataPoints5, JSON_NUMERIC_CHECK); ?>
					}]
				});

				var chartnpsint = new CanvasJS.Chart("chartnpsint", {
					title: {
						text: "Gráfica NPS Interno"
					},
					theme: "light1",
					animationEnabled: true,
					exportEnabled: true,
					toolTip: {
						shared: true,
						reversed: true
					},
					axisY: {
						title: "NPS",
						suffix: ""
					},
					legend: {
						cursor: "pointer",
						itemclick: toggleDataSeries
					},
					data: [{
						type: "line",
						name: "Meta",
						showInLegend: true,
						color: "#FF0000",
						yValueFormatString: "#,##0.#",
						dataPoints: <?php echo json_encode($meta_nps_int, JSON_NUMERIC_CHECK); ?>
					}, {
						type: "stackedColumn",
						name: "NPS Interno",
						showInLegend: true,
						color: "#00CFFF",
						yValueFormatString: "#,##0.#",
						dataPoints: <?php echo json_encode($dataPoints6, JSON_NUMERIC_CHECK); ?>
					}]
				});

				var chartnpsgm = new CanvasJS.Chart("chartnpsgm", {
					title: {
						text: "Gráfica NPS GM"
					},
					theme: "light1",
					animationEnabled: true,
					exportEnabled: true,
					toolTip: {
						shared: true,
						reversed: true
					},
					axisY: {
						title: "NPS",
						suffix: ""
					},
					legend: {
						cursor: "pointer",
						itemclick: toggleDataSeries
					},
					data: [{
						type: "line",
						name: "META",
						showInLegend: true,
						color: "#FF0000",
						yValueFormatString: "#,##0.#",
						dataPoints: <?php echo json_encode($meta_nps_gm, JSON_NUMERIC_CHECK); ?>
					}, {
						type: "stackedColumn",
						name: "NPS GM",
						showInLegend: true,
						color: "#FFA600",
						yValueFormatString: "#,##0.#",
						dataPoints: <?php echo json_encode($dataPoints7, JSON_NUMERIC_CHECK); ?>
					}]
				});


				chartnpsint.render();
				chart.render();
				charthoras.render();
				chartnpsgm.render();

				function toggleDataSeries(e) {
					if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
						e.dataSeries.visible = false;
					} else {
						e.dataSeries.visible = true;
					}
					e.chart.render();
					e.charthoras.render();
					e.chartnpsint.render();
					e.chartnpsgm.render();
				}
			}
		</script>
	<?php } elseif ($this->session->userdata('perfil') == 34) {
		/*MODELOS*/
		$this->load->model('nominas');
		$this->load->model('usuarios');
		$this->load->model('Informe');
		/* VARIABLES */
		$nit = $this->session->userdata('user');
		$nom_usu = $this->usuarios->getUserByNit($nit)->nombres;
		$mes = $this->Informe->get_mes_ano_actual()->mes;
		$ano = $this->Informe->get_mes_ano_actual()->ano;
		/*ARRAY ASESORES*/
		$asesores[] = array('nombre' => "QUIÑONEZ NAVAS DIEGO ALONSO", 'sede' => "MOSTRADOR");
		$asesores[] = array('nombre' => "QUIÑONEZ NAVAS DIEGO ALONSO", 'sede' => "TALLER");
		$asesores[] = array('nombre' => "CASTRO BLANCO LUIS EDUARDO", 'sede' => "SOLOCHEVROLET");
		$asesores[] = array('nombre' => "OLAYA CALDERON JOSE ALLENDY", 'sede' => "MOSTRADOR-MAYOR");
		$asesores[] = array('nombre' => "OLAYA CALDERON JOSE ALLENDY", 'sede' => "TALLER");
		$asesores[] = array('nombre' => "CARRILLO ANGARITA FIDEL", 'sede' => "CUCUTA ASEGURADORA");
		$asesores[] = array('nombre' => "RANGEL REYES CRISTIAN ORLANDO", 'sede' => "CUCUTA MOSTRADOR");
		$asesores[] = array('nombre' => "LOPEZ JUAN MANUEL", 'sede' => "CUCUTA TALLER");
		$asesores[] = array('nombre' => "CADENA RAMIREZ FERNANDO ANTONIO", 'sede' => "GIRON ASEGURADORA");
		$asesores[] = array('nombre' => "ABRIL RAMIREZ LEONARDO", 'sede' => "GIRON TALLER");
		$asesores[] = array('nombre' => "ARDILA SANCHEZ JOSUE", 'sede' => "GIRON MOSTRADOR");
		$asesores[] = array('nombre' => "ARDILA SANCHEZ JOSUE", 'sede' => "GIRON ASEGURADORA-TALLER");
		$asesores[] = array('nombre' => "MEJIA VARGAS OSCAR ALFONSO", 'sede' => "GIRON ASEGURADORA");
		$asesores[] = array('nombre' => "OCHOA RUEDA JHON FREDDY", 'sede' => "CHEVROPARTES MAYOR");
		$asesores[] = array('nombre' => "OCHOA RUEDA JHON FREDDY", 'sede' => "CHEVROPARTES MOSTRADOR");
		$asesores[] = array('nombre' => "OCHOA RUEDA JHON FREDDY", 'sede' => "CHEVROPARTES ACEITE GRANEL");
		/*LOGICA PARA LA GRAFICA*/
		$data_grafi[] = array();
		$to_ven = 0;
		$numeromes = date("m");
		$nom_mes = "";
		for ($j = $numeromes; $j >= 1; $j--) {
			for ($i = 0; $i < count($asesores); $i++) {
				$nom = $asesores[$i]["nombre"];
				$sede = $asesores[$i]["sede"];
				if ($nom_usu == $nom) {
					$venta_neta = 0;
					$margen_bruto = 0;
					$utilidad_bruta = 0;
					$comision = 0;
					$valor_comision = 0;
					$total_comision = 0;


					$nom_mes = $this->Informe->get_nombre_mes($ano . "-01-" . $j)->mes;
					switch ($nom) {
						case 'QUIÑONEZ NAVAS DIEGO ALONSO':
							$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $j, $ano);
							$data_tall = $this->nominas->get_comision_rep_taller('QDIEGO', $j, $ano);
							if ($sede == "MOSTRADOR") {
								foreach ($data_mos->result() as $key) {
									$venta_neta = $key->venta_neta;
									$margen_bruto = $key->margen;
									$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
									$comision = 12.0;
									$valor_comision = $utilidad_bruta * ($comision / 100);
									$total_comision = $valor_comision;
								}
								$to_ven = $to_ven + $venta_neta;
							} elseif ($sede == "TALLER") {
								foreach ($data_tall->result() as $key) {
									$venta_neta = $key->venta_neta;
									$margen_bruto = $key->margen;
									$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
									$comision = 8.0;
									$valor_comision = $utilidad_bruta * ($comision / 100);
									$total_comision = $valor_comision;
								}
								$to_ven = $to_ven + $venta_neta;
							}
							//$data_grafi[] = array("label"=> $nom_mes, "y"=> $to_ven);

							break;

						case 'CASTRO BLANCO LUIS EDUARDO':
							$data_mos = $this->nominas->get_comision_rep_mostrador_luis_e($nom, $j, $ano);
							foreach ($data_mos->result() as $key) {
								$venta_neta = $key->venta_neta;
								$margen_bruto = $key->margen;
								$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
								$comision = 10.0;
								$valor_comision = $utilidad_bruta * ($comision / 100);
								$total_comision = $valor_comision;
							}
							$to_ven = $to_ven + $venta_neta;
							//$data_grafi[] = array("label"=> $nom_mes, "y"=> $to_ven);
							break;
						case 'OLAYA CALDERON JOSE ALLENDY':
							$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $j, $ano);
							$data_tall = $this->nominas->get_comision_rep_taller('JOLAYA', $mes, $ano);
							if ($sede == "MOSTRADOR-MAYOR") {
								foreach ($data_mos->result() as $key) {
									$venta_neta = $key->venta_neta;
									$margen_bruto = $key->margen;
									$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
									$comision = 12.0;
									$valor_comision = $utilidad_bruta * ($comision / 100);
									$total_comision = $valor_comision;
								}
								$to_ven = $to_ven + $venta_neta;
							} elseif ($sede == "TALLER") {
								foreach ($data_tall->result() as $key) {
									$venta_neta = $key->venta_neta;
									$margen_bruto = $key->margen;
									$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
									$comision = 4.0;
									$valor_comision = $utilidad_bruta * ($comision / 100);
									$total_comision = $valor_comision;
								}
								$to_ven = $to_ven + $venta_neta;
							}
							//$data_grafi[] = array("label"=> $nom_mes, "y"=> $to_ven);
							break;
						case 'CARRILLO ANGARITA FIDEL':
							$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $j, $ano);
							$data_tall = $this->nominas->get_comision_rep_taller('FIDEL', $j, $ano);
							foreach ($data_mos->result() as $key) {
								$venta_neta = $key->venta_neta;
								$utilidad = $key->utilidad;
							}
							foreach ($data_tall->result() as $key) {
								$venta_neta += $key->venta_neta;
								$utilidad += $key->utilidad;
							}
							$margen_bruto = ($utilidad / $venta_neta) * 100;
							$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
							$comision = 4.0;
							$comision_v = 0.0037;
							$valor_comision_v = $venta_neta * $comision_v;
							$valor_comision = $utilidad_bruta * ($comision / 100);
							$total_comision = $valor_comision + $valor_comision_v;
							$to_ven = $to_ven + $venta_neta;
							//$data_grafi[] = array("label"=> $nom_mes, "y"=> $to_ven);
							break;
						case 'RANGEL REYES CRISTIAN ORLANDO':
							$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $j, $ano);
							$data_tall = $this->nominas->get_comision_rep_taller('CRANGEL', $j, $ano);
							foreach ($data_mos->result() as $key) {
								$venta_neta = $key->venta_neta;
								$utilidad = $key->utilidad;
							}
							foreach ($data_tall->result() as $key) {
								$venta_neta += $key->venta_neta;
								$utilidad += $key->utilidad;
							}
							$margen_bruto = ($utilidad / $venta_neta) * 100;
							$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
							$comision = 7.5;
							$valor_comision = $utilidad_bruta * ($comision / 100);
							$total_comision = $valor_comision;
							$to_ven = $to_ven + $venta_neta;
							//$data_grafi[] = array("label"=> $nom_mes, "y"=> $to_ven);
							break;
						case 'LOPEZ JUAN MANUEL':
							$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $j, $ano);
							$data_tall = $this->nominas->get_comision_rep_taller('JMANUEL', $j, $ano);

							foreach ($data_mos->result() as $key) {
								$venta_neta = $key->venta_neta;
								$utilidad = $key->utilidad;
							}
							foreach ($data_tall->result() as $key) {
								$venta_neta += $key->venta_neta;
								$utilidad += $key->utilidad;
							}
							$margen_bruto = ($utilidad / $venta_neta) * 100;
							$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
							$comision = 2.0;
							$valor_comision = $utilidad_bruta * ($comision / 100);
							$total_comision = $valor_comision;
							$to_ven = $to_ven + $venta_neta;
							//$data_grafi[] = array("label"=> $nom_mes, "y"=> $to_ven);
							break;
						case 'CADENA RAMIREZ FERNANDO ANTONIO':
							$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $j, $ano);
							$data_tall = $this->nominas->get_comision_rep_taller('FERNANDO', $j, $ano);
							foreach ($data_mos->result() as $key) {
								$venta_neta = $key->venta_neta;
								$utilidad = $key->utilidad;
							}
							foreach ($data_tall->result() as $key) {
								$venta_neta += $key->venta_neta;
								$utilidad += $key->utilidad;
							}
							$margen_bruto = ($utilidad / $venta_neta) * 100;
							$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
							$comision = 4.0;
							$comision_v = 0.0037;
							$valor_comision_v = $venta_neta * $comision_v;
							$valor_comision = $utilidad_bruta * ($comision / 100);
							$total_comision = $valor_comision + $valor_comision_v;
							$to_ven = $to_ven + $venta_neta;
							//$data_grafi[] = array("label"=> $nom_mes, "y"=> $to_ven);
							break;
						case 'ABRIL RAMIREZ LEONARDO':
							$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $j, $ano);
							$data_tall_M = $this->nominas->get_comision_rep_taller('M-ABRIL', $j, $ano);
							$data_tall = $this->nominas->get_comision_rep_taller('LEONARDO', $j, $ano);
							foreach ($data_mos->result() as $key) {
								$venta_neta = $key->venta_neta;
								$utilidad = $key->utilidad;
							}
							foreach ($data_tall->result() as $key) {
								$venta_neta += $key->venta_neta;
								$margen_bruto = $key->margen;
								$utilidad += $key->utilidad;
							}
							foreach ($data_tall_M->result() as $key) {
								$venta_neta += $key->venta_neta;
								$utilidad += $key->utilidad;
							}
							$margen_bruto = ($utilidad / $venta_neta) * 100;
							$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
							$comision = 2.0;
							$valor_comision = $utilidad_bruta * ($comision / 100);
							$total_comision = $valor_comision;
							$to_ven = $to_ven + $venta_neta;
							//$data_grafi[] = array("label"=> $nom_mes, "y"=> $to_ven);
							break;
						case 'ARDILA SANCHEZ JOSUE':
							$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $j, $ano);
							$data_tall = $this->nominas->get_comision_rep_taller('JARDILA', $j, $ano);
							if ($sede == "GIRON MOSTRADOR") {
								foreach ($data_mos->result() as $key) {
									$venta_neta = $key->venta_neta;
									$margen_bruto = $key->margen;
									$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
									$comision = 7.5;
									$valor_comision = $utilidad_bruta * ($comision / 100);
									$total_comision = $valor_comision;
								}
								$to_ven = $to_ven + $venta_neta;
							} elseif ($sede == "GIRON ASEGURADORA-TALLER") {
								foreach ($data_tall->result() as $key) {
									$venta_neta = $key->venta_neta;
									$margen_bruto = $key->margen;
									$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
									$comision = 3.5;
									$valor_comision = $utilidad_bruta * ($comision / 100);
									$total_comision = $valor_comision;
								}
								$to_ven = $to_ven + $venta_neta;
							}
							//$data_grafi[] = array("label"=> $nom_mes, "y"=> $to_ven);
							break;
						case 'OCHOA RUEDA JHON FREDDY':
							$data_mos = $this->nominas->get_comision_rep_mostrador_sin_mayor($nom, $j, $ano);
							$data_mos_m = $this->nominas->get_comision_rep_mostrados_mayor($nom, $j, $ano);
							if ($sede == "CHEVROPARTES MAYOR") {
								foreach ($data_mos_m->result() as $key) {
									$venta_neta = $key->venta_neta;
									$margen_bruto = $key->margen;
									$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
									$comision = 0;
									$comision_v = 0.0060;
									$valor_comision_v = $venta_neta * $comision_v;
									$valor_comision = $utilidad_bruta * ($comision / 100);
									$total_comision = $valor_comision + $valor_comision_v;
								}
								$to_ven = $to_ven + $venta_neta;
							} elseif ($sede == "CHEVROPARTES MOSTRADOR") {
								foreach ($data_mos->result() as $key) {
									$venta_neta = $key->venta_neta;
									$margen_bruto = $key->margen;
									$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
									$comision = 10.0;
									$valor_comision = $utilidad_bruta * ($comision / 100);
									$total_comision = $valor_comision;
								}
								$to_ven = $to_ven + $venta_neta;
							}
							//$data_grafi[] = array("label"=> $nom_mes, "y"=> $to_ven);
							break;
						case 'MEJIA VARGAS OSCAR ALFONSO':
							$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $j, $ano);
							foreach ($data_mos->result() as $key) {
								$venta_neta = $key->venta_neta;
								$margen_bruto = $key->margen;
							}
							$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
							$comision = 8.0;
							$comision_v = 0.0040;
							$valor_comision_v = $venta_neta * $comision_v;
							$valor_comision = $utilidad_bruta * ($comision / 100);
							$total_comision = $valor_comision + $valor_comision_v;
							$to_ven = $to_ven + $venta_neta;
							//$data_grafi[] = array("label"=> $nom_mes, "y"=> $to_ven);
							break;
					}
				}
			}
			$data_grafi[] = array("label" => $nom_mes, "y" => $to_ven);
			$to_ven = 0;
		}
	?>
		<script type="text/javascript">
			var chart_ase_rep = new CanvasJS.Chart("chart_ase_rep", {
				title: {
					text: "Gráfica Total Repuestos Vendidos"
				},
				theme: "light1",
				animationEnabled: true,
				exportEnabled: true,
				toolTip: {
					shared: true,
					reversed: true
				},
				axisY: {
					title: "Pesos",
					suffix: "",
					prefix: "$"
				},
				legend: {
					cursor: "pointer",
					itemclick: toggleDataSeries
				},
				data: [{
					type: "stackedColumn",
					name: "Valor en Pesos",
					showInLegend: true,
					color: "#000AFF",
					yValueFormatString: "#,##0.#",
					dataPoints: <?php echo json_encode($data_grafi, JSON_NUMERIC_CHECK); ?>
				}]
			});
			chart_ase_rep.render();

			function toggleDataSeries(e) {
				if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
					e.dataSeries.visible = false;
				} else {
					e.dataSeries.visible = true;
				}
				e.chart_ase_rep.render();
			}
		</script>
	<?php } ?>

	<script type="text/javascript">
		function irIntranetActas(nitUsuario) {
			var mapForm = document.createElement("form");
			mapForm.method = "POST";
			mapForm.action = 'https://intranet.codiesel.co/actas_codiesel/Home/Login';

			var mapInput = document.createElement("input");
			mapInput.type = "hidden";
			mapInput.name = "nitUsuario";
			mapInput.value = nitUsuario;
			mapForm.appendChild(mapInput);

			document.body.appendChild(mapForm);
			mapForm.submit();

		}
	</script>

	<script>
		function validarPausaActiva(user) {

			$('#pausaActivas').modal('show');

			var f_actual1 = document.getElementById('fecha_actual').value;
			var diaEsFestivo = document.getElementById('diaActualEsFestivo');


			var d = new Date(f_actual1);
			var dia_actual = d.getDay();
			var hora_actual = d.getHours();
			var minutos_actual = d.getMinutes();

			if (diaEsFestivo == 1) {
				Swal.fire({
					title: 'Advertencia!',
					text: 'No puede realizar registros de pausas acivas en días festivos',
					icon: 'warning',
					confirmButtonText: 'Ok'
				});
			} else if (dia_actual == 0) {
				Swal.fire({
					title: 'Advertencia!',
					text: 'No puede realizar registros de pausas acivas en días dominicales',
					icon: 'warning',
					confirmButtonText: 'Ok'
				});
			} else if (dia_actual == 6 && hora_actual >= 12 && minutos_actual > 30) {
				Swal.fire({
					title: 'Advertencia!',
					text: 'No puede realizar registros de pausas acivas en horarios no establecidos',
					icon: 'warning',
					confirmButtonText: 'Ok'
				});
			} else if (hora_actual >= 6 && hora_actual <= 12) {

				var data = new FormData;
				data.append('user', user);

				var url = '<?= base_url() ?>login/addPausaActivaAm';
				var request;
				if (window.XMLHttpRequest) {
					request = new XMLHttpRequest();
				} else {
					request = new ActiveXObject("Microsoft.XMLHTTP");
				}

				request.onreadystatechange = function() {
					if (request.readyState === 4 && request.status === 200) {
						if (request.responseText == 1) {
							Swal.fire({
								title: 'Advertencia!',
								text: 'El usuario ya tiene un registro de pausa activa en la jornada AM',
								icon: 'warning',
								confirmButtonText: 'Ok'
							});
						} else if (request.responseText == 2) {
							Swal.fire({
								title: 'Exito!',
								text: 'El usuario ha realizado con exito el registro de pausas activas en la jornada AM',
								icon: 'success',
								confirmButtonText: 'Ok'
							});
						} else {
							Swal.fire({
								title: 'Error!',
								text: 'Ha ocurrido un error inesperado, conctacte con el departamento de sistemas',
								icon: 'error',
								confirmButtonText: 'Ok'
							});
						}
					}

				}
				request.open("POST", url);
				request.send(data);

			} else if (hora_actual >= 1 && hora_actual <= 18) {

				var data = new FormData;
				data.append('user', user);

				var url = '<?= base_url() ?>login/addPausaActivaPm';
				var request;
				if (window.XMLHttpRequest) {
					request = new XMLHttpRequest();
				} else {
					request = new ActiveXObject("Microsoft.XMLHTTP");
				}

				request.onreadystatechange = function() {
					if (request.readyState === 4 && request.status === 200) {
						if (request.responseText == 1) {
							Swal.fire({
								title: 'Advertencia!',
								text: 'El usuario ya tiene un registro de pausa activa en la jornada PM',
								icon: 'warning',
								confirmButtonText: 'Ok'
							});
						} else if (request.responseText == 2) {
							Swal.fire({
								title: 'Exito!',
								text: 'El usuario ha realizado con exito el registro de pausas activas en la jornada PM',
								icon: 'success',
								confirmButtonText: 'Ok'
							});
						} else {
							Swal.fire({
								title: 'Error!',
								text: 'Ha ocurrido un error inesperado, conctacte con el departamento de sistemas',
								icon: 'error',
								confirmButtonText: 'Ok'
							});
						}
					}

				}
				request.open("POST", url);
				request.send(data);

			} else {
				Swal.fire({
					title: 'Advertencia!',
					text: 'No puede realizar registros de pausas acivas en horarios no establecidos',
					icon: 'warning',
					confirmButtonText: 'Ok'
				});
			}

		}
	</script>


	<script>
		function inIntranetVentas(nit) {
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			let url = "https://intranet.codiesel.co/postventa/Login/getClaveIntranetVentas";
			/* let url = "http://localhost:8080/postventa/Login/getClaveIntranetVentas"; */
			xmlhttp.open('POST', url, true);
			/* xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded'); */
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					const result = xmlhttp.responseText;
					inIntranetVentasLogin(nit, result);
				}
			}
			xmlhttp.send();
		}

		function inIntranetVentasLogin(nit, pass) {

			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}

			var url = 'https://intranet.codiesel.co/ventas/Login/sesion';
			/* var url = 'http://localhost:8080/ventas/Login/sesion'; */
			var params = 'nit=' + nit + '&contra=' + pass;

			xmlhttp.open('POST', url, true);
			xmlhttp.responseType = 'json';
			xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					const result = xmlhttp.response;
					console.log(result);
					const url = "https://intranet.codiesel.co/ventas/Home";
					/* const url = 'http://localhost:8080/ventas/Home'; */
					var mapForm = document.createElement("form");
					mapForm.target = "_blank";
					mapForm.method = "POST";
					mapForm.action = 'https://intranet.codiesel.co/ventas/Home';
					/* mapForm.action = 'http://localhost:8080/ventas/Home'; */
					document.body.appendChild(mapForm);
					mapForm.submit();
				}
			}
			xmlhttp.send(params);
		}
	</script>

</body>

</html>