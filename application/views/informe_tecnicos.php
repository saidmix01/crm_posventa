<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Post Venta</title>
	<!-- Le dice al navegador que la web es responsiva -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Estilos Font Awesome -->
	<link rel="stylesheet" href="<?=base_url()?>plugins/fontawesome-free/css/all.min.css">
	<!-- Pack de Iconos Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Estilos Tempusdominus Bootstrap 4 -->
	<link rel="stylesheet" href="<?=base_url()?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?=base_url()?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- JQVMap -->
	<link rel="stylesheet" href="<?=base_url()?>plugins/jqvmap/jqvmap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?=base_url()?>dist/css/adminlte.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="<?=base_url()?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<!-- summernote -->
	<link rel="stylesheet" href="<?=base_url()?>plugins/summernote/summernote-bs4.css">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<link rel="shortcut icon" href="<?=base_url()?>media/logo/logo_codiesel_sinfondo.png" />
</head>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php $this->load->view('nav_user'); ?>
		

		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #3D3D3D; ">
			<!-- Brand Logo -->
			<br>

			<!-- Sidebar -->
			<div class="sidebar">
				<!-- Sidebar user panel (optional) -->
				<div class="">
					<div class="image">
						<img src="<?=base_url()?>media/logo/logo_codiesel.png" class="img-fluid" alt="Responsive image">
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
						<li class="nav-item has-treeview" onclick="alert('Script para realizar el cambio de intranet')">
							<a href="#" class="nav-link">
								<i class="nav-icon fas fa-home"></i>
								<p>
									Intranet Ventas
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
									<i class="nav-icon <?=$menu->icono?>"></i>
									<p>
										<?=$menu->menu?>
										<i class="right fas fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<?php 
									$this->load->model('menus');
									$submenus = $this->menus->getSubmenusByPerfil($menu->id_menu,$menu->id_perfil);
									foreach ($submenus->result() as $submenu) {
										?>
										<li class="nav-item">
											<a href="<?=base_url()?><?=$submenu->vista?>" class="nav-link">
												<i class="<?=$submenu->icono?> nav-icon"></i>
												<p><?=$submenu->submenu?></p>
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
			<section class="content" style="margin: 30px; margin-top: 0px;">
				<div class="row">
					<input type="month" name="fecha" id="fecha" class="form-control" value="2021" onchange="load_inf_tec();">
				</div>
				<hr>
				<div class="row">
					<h1 class="m-0 text-dark">Informe Diario</h1>
				</div>
				<br>
				<div class="row">
					<div class="col-md-2">
						<div class="info-box">
							<span class="info-box-icon bg-info elevation-1"><i class="fas fa-chart-line"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">NPS Interno</span>
								<span class="info-box-number" style="font-size: 23px;" id="nps_interno">
									<?=round($nps_int)?>
									<small>%</small>
								</span>
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->
					<div class="col-md-2">
						<div class="info-box mb-3">
							<span class="info-box-icon bg-warning elevation-1"><i class="fas fa-chart-line"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">NPS COLMOTORES</span>
								<span class="info-box-number" style="font-size: 23px;" id="nps_col"> 
									<?=round($nps_col)?> %
								</span>
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->
					<div class="col-md-2">
						<div class="info-box mb-3">
							<span class="info-box-icon bg-success elevation-1"><i class="fas fa-dollar-sign"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Total Vendido</span>
								<span class="info-box-number" style="font-size: 23px;" id="total_ven">$<?=number_format($total_ventas,0,",",",")?></span>
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->
					<div class="col-md-2">
						<div class="info-box mb-3">
							<span class="info-box-icon elevation-1" style="background-color: #FF6C00;color: #fff;"><i class="fas fa-dollar-sign"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Total M.O</span>
								<span class="info-box-number" style="font-size: 23px;" id="total_mo">$<?=number_format($mo,0,",",",")?></span>
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->
					<div class="col-md-2">
						<div class="info-box mb-3">
							<span class="info-box-icon elevation-1" style="background-color: #00FFEA;"><i class="fas fa-dollar-sign"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Total Rptos</span>
								<span class="info-box-number" style="font-size: 23px;" id="total_rep">$<?=number_format($rep,0,",",",")?></span>
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->
					<div class="col-md-2">
						<div class="info-box mb-3">
							<span class="info-box-icon elevation-1" style="background-color: #001AFF;color: #fff;"><i class="far fa-clock"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Horas Facturadas</span>
								<span class="info-box-number" style="font-size: 23px;" id="horas_fac">$<?=$horas_fac?></span>
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->
				</div>
				<div class="row">
					<div class="col-md-9">
						<div class="row">
							<div class="col-md-6">
								<div class="card">
									<div class="card-body">
										<div id="chartContainer" style="height: 370px; width: 100%;"></div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card">
									<div class="card-body">
										<div id="charthoras" style="height: 370px; width: 100%;"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="card">
									<div class="card-body">
										<div id="chartnpsint" style="height: 370px; width: 100%;"></div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card">
									<div class="card-body">
										<div id="chartnpsgm" style="height: 370px; width: 100%;"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card">
							<div class="card-body" style="background-color: #F4F6F9;">
								<div class="row"><h3>Ranking por taller</h3></div>
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-12">
					                    <p class="text-center">
					                      <strong>Ranking de Facturación MO más Repuestos</strong>
					                    </p>
					                    <?php foreach ($ranking_presupuesto->result() as $key) { 
					                    	$porcen_ran = ($key->suma_todo * 100)/$tope_ran_pres;
					                    	if ($porcen_ran > 100) {
					                    		$porcen_ran = 100;
					                    	}
					                    	$user = $this->session->userdata('user');
					                    	$text = "";
					                    	if ($key->operario == $user) {
					                    		$text = $key->tecnico;
					                    	}else{
					                    		$text = "Otro Técnico";
					                    	}
					                    	$color_bar = "";
					                    	if ($key->suma_todo < $tope_ran_pres) {
					                    		$color_bar = "bg-secondary";
					                    	}else{
					                    		$color_bar = "bg-success";
					                    	}
					                    ?>
					                    <div class="progress-group">
					                      	<?=$text?>
					                      <span class="float-right"><b><?=number_format($key->suma_todo, 0, ",", ",")?></b>/<?=number_format($tope_ran_pres, 0, ",", ",")?></span>
					                      <div class="progress progress-sm">
					                        <div class="progress-bar <?=$color_bar?>" style="width: <?=$porcen_ran?>%"></div>
					                      </div>
					                    </div>
					                    <!-- /.progress-group -->
					                	<?php } ?>
					                  </div>
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-body" style="background-color: #F4F6F9;">
								<div class="row"><h3>Ranking por taller</h3></div>
								<div class="row">
									<div class="col-md-12">
										<div class="info-box">
											<span class="info-box-icon bg-primary elevation-1"><i class="fas fa-star" style="font-size: 50px; color: yellow;"></i></span>

											<div class="info-box-content" align="center">
												<span class="info-box-text">Ranking NPS</span>
												<span class="info-box-number" style="font-size: 23px;" id="nps_interno">
													<?=$ranking_talleres['ran_nps']?>
												</span>
											</div>
											<!-- /.info-box-content -->
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="info-box">
											<span class="info-box-icon bg-primary elevation-1"><i class="fas fa-star" style="font-size: 50px; color: yellow;"></i></span>

											<div class="info-box-content" align="center">
												<span class="info-box-text">Ranking Ventas</span>
												<span class="info-box-number" style="font-size: 23px;" id="nps_interno">
													<?=$ranking_talleres['ran_vendido']?>
												</span>
											</div>
											<!-- /.info-box-content -->
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-body" style="background-color: #F4F6F9;">
								<div class="row"><h3>Ranking por área</h3></div>
								<div class="row">
									<div class="col-md-12">
										<div class="info-box">
											<span class="info-box-icon bg-primary elevation-1"><i class="fas fa-star" style="font-size: 50px; color: yellow;"></i></span>

											<div class="info-box-content" align="center">
												<span class="info-box-text">Ranking NPS</span>
												<span class="info-box-number" style="font-size: 23px;" id="nps_interno">
													<?php echo $ranking_sedes['ran_nps']; ?>
												</span>
											</div>
											<!-- /.info-box-content -->
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="info-box">
											<span class="info-box-icon bg-primary elevation-1"><i class="fas fa-star" style="font-size: 50px; color: yellow;"></i></span>

											<div class="info-box-content" align="center">
												<span class="info-box-text">Ranking Ventas</span>
												<span class="info-box-number" style="font-size: 23px;" id="nps_interno">
													<?php echo $ranking_sedes['ran_vendido']; ?>
												</span>
											</div>
											<!-- /.info-box-content -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
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
					<a class="btn btn-primary" href="<?=base_url()?>login/logout">Si</a>
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
	if($log == "err_p"){
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
	<script src="<?=base_url()?>plugins/jquery/jquery.min.js"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="<?=base_url()?>plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
		$.widget.bridge('uibutton', $.ui.button)
	</script>
	<!-- Bootstrap 4 -->
	<script src="<?=base_url()?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- overlayScrollbars -->
	<script src="<?=base_url()?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?=base_url()?>plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="<?=base_url()?>dist/js/adminlte.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="<?=base_url()?>dist/js/pages/dashboard.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<!-- DataTables -->
	<script src="<?=base_url()?>plugins/datatables/jquery.dataTables.js"></script>
	<script src="<?=base_url()?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
	<!-- charts canvas -->
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#buscar_items").on("keyup", function() {
				var value = $(this).val().toLowerCase();
				$("#menu_items li").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
			});

			const Toast = Swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 5000
			});
			//cargar_tabla_ventas();
			/*setInterval('actualizar_total_vendido()',10000);
			setInterval('actualizar_nps_int()',10000);
			setInterval('actualizar_nps_col()',10000);
			setInterval('horas_fac_tec()',10000);*/
		});
		function actualizar_total_vendido() {
			var result = document.getElementById("total_ven");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					result.innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "<?=base_url()?>tecnicos/valor_vendido_tec?var=1", true);
			xmlhttp.send();
		}
		function actualizar_nps_int() {
			var result = document.getElementById("nps_interno");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					result.innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "<?=base_url()?>tecnicos/nps_interno?var=1", true);
			xmlhttp.send();
		}
		function actualizar_nps_col() {
			var result = document.getElementById("nps_col");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					result.innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "<?=base_url()?>tecnicos/calficacion_tec_colmotores?var=1", true);
			xmlhttp.send();
		}
		
		function horas_fac_tec() {
			var result = document.getElementById("horas_fac");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					result.innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "<?=base_url()?>tecnicos/horas_fac_tec?var=1", true);
			xmlhttp.send();
		}
		
	</script>

	<script type="text/javascript">
		//funciones para realizar la busqueda
		function actualizar_total_vendido_buscar(fecha) {
			var result = document.getElementById("total_ven");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					result.innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "<?=base_url()?>tecnicos/valor_vendido_tec_buscar?fecha="+fecha, true);
			xmlhttp.send();
		}
		function actualizar_total_mo_buscar(fecha) {
			var result = document.getElementById("total_mo");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					result.innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "<?=base_url()?>tecnicos/valor_vendido_mo_buscar?fecha="+fecha, true);
			xmlhttp.send();
		}
		function actualizar_total_rep_buscar(fecha) {
			var result = document.getElementById("total_rep");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					result.innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "<?=base_url()?>tecnicos/valor_vendido_rep_buscar?fecha="+fecha, true);
			xmlhttp.send();
		}
		function actualizar_nps_int_buscar(fecha) {
			var result = document.getElementById("nps_interno");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					result.innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "<?=base_url()?>tecnicos/nps_interno_buscar?fecha="+fecha, true);
			xmlhttp.send();
		}
		function actualizar_nps_col_buscar(fecha) {
			var result = document.getElementById("nps_col");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					result.innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "<?=base_url()?>tecnicos/calficacion_tec_colmotores_buscar?fecha="+fecha, true);
			xmlhttp.send();
		}
		
		function horas_fac_tec_buscar(fecha) {
			var result = document.getElementById("horas_fac");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					result.innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "<?=base_url()?>tecnicos/horas_fac_tec_buscar?fecha="+fecha, true);
			xmlhttp.send();
		}
		function load_inf_tec() {
			var fecha = document.getElementById("fecha").value;
			actualizar_total_vendido_buscar(fecha);
			actualizar_nps_int_buscar(fecha);
			actualizar_nps_col_buscar(fecha);
			horas_fac_tec_buscar(fecha);
			actualizar_total_mo_buscar(fecha);
			actualizar_total_rep_buscar(fecha);
		}
	</script>

	<script src="<?=base_url()?>dist/js/demo.js"></script>
	<?php 
	$this->load->model('usuarios');
	$usu = $this->usuarios->getUserById($id_usu);
	echo $id_usu;
	$p="";
	$nit="";
	foreach ($usu->result() as $key) {
		$p = $key->pass;
		$nit = $key->nit;
	}
		//echo $p." ".$nit;
	$this->load->library('encrypt');
	$pass_desencript = $this->encrypt->decode($p);
	if($pass_desencript == $nit){
		?>
		<script type="text/javascript">
			$('#pass-modal').show('true')
		</script>
		<?php 
	}
	?>
	<script type="text/javascript">
		setTimeout(function(){ 
			$('#alert_err').alert('close');
		}, 1500);
		$('#reservation').datepicker();
	</script>
	<script>
		$(document).ready(function(){
			$('#tabla_ventas_tec').DataTable({
				"paging": true,
				"pageLength": 25,
				"lengthChange": true,
				"searching": true,
				"ordering": true,
				"info": true,
				"autoWidth": false,
				"language":{
					"sProcessing":     "Procesando...",
					"sLengthMenu":     "Mostrar _MENU_ registros",
					"sZeroRecords":    "No se encontraron resultados",
					"sEmptyTable":     "Ningún dato disponible en esta tabla =(",
					"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
					"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
					"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
					"sInfoPostFix":    "",
					"sSearch":         "Buscar:",
					"sUrl":            "",
					"sInfoThousands":  ",",
					"sLoadingRecords": "Cargando...",
					"oPaginate": {
						"sFirst":    "Primero",
						"sLast":     "Último",
						"sNext":     "Siguiente",
						"sPrevious": "Anterior"
					},
					"oAria": {
						"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
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
	<!-- graficas -->
	<?php
	//cargamos el modelo
	$this->load->model('talleres');
	$this->load->model('Informe');
	$usu = $this->session->userdata('user');
	$date = $this->Informe->get_mes_ano_actual();
	$ano = $date->ano;
	$to_mo = 0;
	$to_rep = 0;
	$total_v = 0;
	$mes = $this->Informe->get_mes_ano_actual()->mes;
	
	for ($i=1; $i <= $mes; $i++) { 
		//PRESUPUESTO
		$data_ventas_vendedor = $this->talleres->get_ventas_tec_graf($usu,$i,$ano);
		foreach ($data_ventas_vendedor->result() as $key) {
			$to_mo = $key->MO;
			$to_rep = $key->rptos;
			$total_v = $key->rptos + $key->MO;
			$total_horas = $key->horas_facturadas;
			$dataPoints1[] = array("label"=> $key->mes_nom, "y"=> $key->MO);
			$dataPoints2[] = array("label"=> $key->mes_nom, "y"=> $key->rptos);
			$dataPoints3[] = array("label"=> $key->mes_nom, "y"=> $total_v);
			$dataPoints4[] = array("label"=> $key->mes_nom, "y"=> $total_horas);

		}
		//NPS INTERNO
		$data_nps_tec = $this->Informe->get_nps_int_tec_graf($usu,$i,$ano);
		$mes_n = "";
		if (!empty($data_nps_tec->result())) {
			$nps_int = 0;
			$to_enc = 0;
			$to_enc_06 = 0;
			$to_enc_78 = 0;
			$to_enc_910 = 0;
			
			foreach ($data_nps_tec->result() as $key) {
				$to_enc_06 = $to_enc_06 + $key->enc0a6;
				$to_enc_78 = $to_enc_78 + $key->enc7a8;
				$to_enc_910 = $to_enc_910 + $key->enc9a10;
				$mes_n = $key->mes_nom;

			}
			$meta_nps_int[] = array("label" => $mes_n, "y"=>80);
			$to_enc = $to_enc_06 + $to_enc_78 + $to_enc_910;
			$nps_int = (($to_enc_910 - $to_enc_06) / $to_enc) * 100;
			$dataPoints5[] = array("label"=> $mes_n, "y"=> $nps_int);
		}else{
			$meta_nps_int[] = array("label" => $mes_n, "y"=>80);
			$dataPoints5[] = array("label"=> "0", "y"=> 0);
		}
		
		//NPS GM
		$tecnicos = $this->Informe->get_nps_by_tec_gm_graf($usu,$i,$ano);
		if (!empty($tecnicos->result())) {
			$nps_gm = 0;
			$to_enc = 0;
			$to_enc_06 = 0;
			$to_enc_78 = 0;
			$to_enc_910 = 0;
			foreach ($tecnicos->result() as $key) {
				$total_encu = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
				$nps = (($key->enc9a10 - $key->enc0a6) / $total_encu) * 100;
				$to_enc_06 = $to_enc_06 +$key->enc0a6;
				$to_enc_78 = $to_enc_78 + $key->enc7a8;
				$to_enc_910 = $to_enc_910 + $key->enc9a10;
				$mes_n = $key->mes_nom;

			}
			$meta_nps_gm[] = array("label" => $mes_n, 'y'=> 75);
			$to_enc = $to_enc_06 + $to_enc_78 + $to_enc_910;
			$nps_gm = (($to_enc_910 - $to_enc_06) / $to_enc) * 100;
			$dataPoints6[] = array("label"=> $mes_n, "y"=> $nps_gm);
		}else{
			$meta_nps_gm[] = array("label" => $mes_n, 'y'=> 75);
			$dataPoints6[] = array("label"=> "0", "y"=> 0);
		}
		
	}
	?>
	<script>
		window.onload = function () {

			var chart = new CanvasJS.Chart("chartContainer", {
				title: {
					text: "Gráfica Total Vendido"
				},
				theme: "light1",
				animationEnabled: true,
				exportEnabled: true,
				toolTip:{
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
				data: [
				{
					type: "stackedColumn",
					name: "Mano de Obra",
					showInLegend: true,
					color: "#FF6C00",
					yValueFormatString: "#,##0.#",
					dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
				},{
					type: "stackedColumn",
					name: "Repuestos",
					showInLegend: true,
					color: "#00FFEA",
					yValueFormatString: "#,##0.#",
					dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
				},{
					type: "line",
					name: "Total",
					showInLegend: true,
					color: "#E74C3C",
					yValueFormatString: "#,##0.#",
					dataPoints: <?php echo json_encode($dataPoints3, JSON_NUMERIC_CHECK); ?>
				}]
			});
			var charthoras = new CanvasJS.Chart("charthoras", {
				title: {
					text: "Gráfica Horas Facturadas"
				},
				theme: "light1",
				animationEnabled: true,
				exportEnabled: true,
				toolTip:{
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
					dataPoints: <?php echo json_encode($dataPoints4, JSON_NUMERIC_CHECK); ?>
				}]
			});

			var chartnpsint = new CanvasJS.Chart("chartnpsint", {
				title: {
					text: "Gráfica NPS Interno"
				},
				theme: "light1",
				animationEnabled: true,
				exportEnabled: true,
				toolTip:{
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
				},{
					type: "stackedColumn",
					name: "NPS Interno",
					showInLegend: true,
					color: "#00CFFF",
					yValueFormatString: "#,##0.#",
					dataPoints: <?php echo json_encode($dataPoints5, JSON_NUMERIC_CHECK); ?>
				}]
			});

			var chartnpsgm = new CanvasJS.Chart("chartnpsgm", {
				title: {
					text: "Gráfica NPS GM"
				},
				theme: "light1",
				animationEnabled: true,
				exportEnabled: true,
				toolTip:{
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
				},{
					type: "stackedColumn",
					name: "NPS GM",
					showInLegend: true,
					color: "#FFA600",
					yValueFormatString: "#,##0.#",
					dataPoints: <?php echo json_encode($dataPoints6, JSON_NUMERIC_CHECK); ?>
				}]
			});


			chartnpsint.render();
			chart.render();
			charthoras.render();
			chartnpsgm.render();

			function toggleDataSeries(e) {
				if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
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

			var url = 'https://intranet.codiesel.co/codiesel/validarUser.php';
			/* var url = 'https://localhost/codiesel/validarUser.php'; */
			var params = 'usu=' + nit + '&pas=' + pass;

			xmlhttp.open('POST', url, true);
			xmlhttp.responseType = 'json';
			xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					const result = xmlhttp.response;
					console.log(result);
					const url = "https://intranet.codiesel.co/codiesel/index.php";
					/* const url = "https://localhost/codiesel/index.php"; */
					var mapForm = document.createElement("form");
					mapForm.target = "_blank";
					mapForm.method = "POST";
					mapForm.action = 'https://intranet.codiesel.co/codiesel/index.php';
					/* mapForm.action = 'https://localhost/codiesel/index.php'; */
					document.body.appendChild(mapForm);
					mapForm.submit();

					/* location.href = url; */
				}
			}
			xmlhttp.send(params);
		}
	</script>
</body>
</html>
