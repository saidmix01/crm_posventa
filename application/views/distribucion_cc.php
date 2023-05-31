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
	<link rel="stylesheet" href="<?=base_url()?>plugins/daterangepicker/daterangepicker.css">
	<!-- summernote -->
	<link rel="stylesheet" href="<?=base_url()?>plugins/summernote/summernote-bs4.css">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<link rel="shortcut icon" href="<?=base_url()?>media/logo/logo_codiesel_sinfondo.png" />
	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="<?=base_url()?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
	<!-- Toastr -->
	<link rel="stylesheet" href="<?=base_url()?>plugins/toastr/toastr.min.css">
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
					<a href="<?=base_url()?>login/iniciar" style="color: #fff;" class="nav-link"><i class="fas fa-home"></i>&nbsp;&nbsp; Inicio</a>
				</li>
			</ul>
			<!-- Right navbar links -->
			<ul class="navbar-nav ml-auto">
				<!-- Notifications Dropdown Menu -->
				<img src="<?=base_url()?>media/img/user-img.png" alt="User Avatar" class="img-size-50 mr-3 img-circle" style="position: relative; left: 25px; top: 0px; height: 35px; width: 35px;">
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="#" style="color: #fff;">
						<?php 
						foreach ($userdata->result() as $key) {
							?>
							<?=$key->nombres?>

						</a>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
							<span class="dropdown-item dropdown-header"><?=$key->nombres?></span>
							<?php 
						}
						?>
						<div class="dropdown-divider" style=""></div>
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
			<section class="content">
				<div class="card">
					<div class="card-body">
						<h3>Distribución</h3>
						<hr>
						<div class="row">
							<!--<a href="#" class="btn btn-success" data-toggle="modal" data-target="#moda_asignacion">Asignar</a>-->
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-hover">
										<thead align="center">
											<tr>
												<th scope="col">Agente</th>
												<th>Sedes</th>
												<th>Distribución</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($agentes->result() as $key) { ?>
												<tr>
													<th scope="row">
														<div class="form-check">
															<label class="form-check-label" for="exampleRadios1">
																<?=$key->nombres?>
															</label>
														</div>
													</th>
													<th>
														<table class="table-borderless">
															<tbody>
																<tr>
																	<?php foreach ($bodegas->result() as $bod) { 
																		// $resultadoeck=($hxd>0)?'checked="checked"':''
																		$this->load->model('Contac_Center');
																		$fecha = $this->Contac_Center->get_mes_anio();
																		$data = array('agente' => $key->nit_real,'bodega' => $bod->bodega,'mes'=>$fecha->mes,'anio'=>$fecha->anio);
																		$n_val = $this->Contac_Center->validar_agente($data);
																		$checket = "";
																		if ($n_val->n != 0) {
																			$checket = "checked";
																		}
																		?>
																		<th>
																			<div class="form-check form-check-inline">
																				<input class="form-check-input" type="checkbox" id="bod" value="<?=$bod->bodega?>" onclick="validar_agente(<?=$bod->bodega?>,<?=$key->nit_real?>)" <?=$checket?>>
																				<label class="form-check-label" for="bod"><?=$bod->bodega?></label>
																			</div>
																		</th>
																	<?php } ?>
																</tr>
															</tbody>
														</table>
													</th>
													<th>
														<table class="table-borderless">
															<tbody>
																<tr>
																	<?php foreach ($bodegas->result() as $bod) { 
																		$distribucion = 0;
																		$id_input = $bod->bodega."_".$key->nit_real;
																		$this->load->model('Contac_Center');
																		$fecha = $this->Contac_Center->get_mes_anio();
																		$data = array('agente' => $key->nit_real,'bodega' => $bod->bodega,'mes'=>$fecha->mes,'anio'=>$fecha->anio);
																		$data_dist_agente = $this->Contac_Center->get_distribucion_agentes($data);
																		$active = "";
																		if (empty($data_dist_agente)) {
																			$distribucion = "";
																			$active = "readonly";
																		}else{
																			$distribucion = $data_dist_agente->distribucion;
																		}
																		
																		
																		?>
																		<th>
																			<div class="form-group" align="center">
																				<label><?=$bod->bodega?></label>
																				<input type="text" name="" id="<?=$id_input?>" size="1" onkeyup = "if(event.keyCode == 13) add_distribucion(document.getElementById('<?=$id_input?>').value,<?=$bod->bodega?>,<?=$key->nit_real?>)" value="<?=$distribucion?>" <?=$active?>>
																			</div>
																		</th>
																	<?php } ?>
																</tr>
															</tbody>
														</table>
													</th>
												</tr>
											<?php } ?>
											<tr align="center">
												<th colspan="2"><h4>Totales</h4></th>
												<th>
													<table class="table">
														<thead align="center">
															<tr>
															<?php foreach ($bodegas->result() as $key){  ?>
																
																	<th><?=$key->bodega?></th>
																
															<?php } ?>
															</tr>
														</thead>
														<tbody align="center">
															<tr id="tabla_totales">
																
															</tr>
														</tbody>
													</table>
												</th>
											</tr>
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

	<!-- Modal Asignacion-->
	<div class="modal fade" id="moda_asignacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Nueva Asignacion</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					...
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
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
	<!-- ChartJS -->
	<script src="<?=base_url()?>plugins/chart.js/Chart.min.js"></script>
	<!-- Sparkline -->
	<script src="<?=base_url()?>plugins/sparklines/sparkline.js"></script>
	<!-- JQVMap -->
	<script src="<?=base_url()?>plugins/jqvmap/jquery.vmap.min.js"></script>
	<script src="<?=base_url()?>plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
	<!-- jQuery Knob Chart -->
	<script src="<?=base_url()?>plugins/jquery-knob/jquery.knob.min.js"></script>
	<!-- daterangepicker -->
	<script src="<?=base_url()?>plugins/moment/moment.min.js"></script>
	<script src="<?=base_url()?>plugins/daterangepicker/daterangepicker.js"></script>
	<!-- Tempusdominus Bootstrap 4 -->
	<script src="<?=base_url()?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
	<!-- Summernote -->
	<script src="<?=base_url()?>plugins/summernote/summernote-bs4.min.js"></script>
	<!-- overlayScrollbars -->
	<script src="<?=base_url()?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?=base_url()?>dist/js/adminlte.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="<?=base_url()?>dist/js/pages/dashboard.js"></script>
	<!-- AdminLTE for demo purposes -->
	<!-- SweetAlert2 -->
	<script src="<?=base_url()?>plugins/sweetalert2/sweetalert2.min.js"></script>
	<!-- Toastr -->
	<script src="<?=base_url()?>plugins/toastr/toastr.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#buscar_items").on("keyup", function() {
				var value = $(this).val().toLowerCase();
				$("#menu_items li").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
			});
			cargar_totales();
		});

		
	</script>
	<script src="<?=base_url()?>dist/js/demo.js"></script>
	<?php 
	$this->load->model('usuarios');
	$usu = $this->usuarios->getUserById($id_usu);
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
	</script>

	<script type="text/javascript">
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 5000
		});
		function validar_agente(bod,agente) {
			var xmlhttp;
			var vali = 0;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					vali = xmlhttp.responseText;
					if (vali == 1) {
						insert_agente(bod,agente,1);
					}else{
						insert_agente(bod,agente,0);
					}
				}
			}
			xmlhttp.open("GET", "<?=base_url()?>contaccenter/validar_agente?agente="+agente+"&sede="+bod, true);
			xmlhttp.send();		
		}

		function insert_agente(sede,agente,vali) {
			if (vali == 1) {
				var xmlhttp;
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function () {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						var resp = xmlhttp.responseText;
						if (resp == "err") {
							Toast.fire({
								type: 'success',
								title: ' Se ha DESASIGNADO la bodega '+sede+' al agente '+agente
							}); 
						}else{
							Toast.fire({
								type: 'error',
								title: ' Ha ocurriod un error inesperado.'
							}); 
						}
						location.reload();
					}
				}
				xmlhttp.open("GET", "<?=base_url()?>contaccenter/delete_distribucion?agente="+agente+"&sede="+sede, true);
				xmlhttp.send();
			}else{
				var xmlhttp;
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function () {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						var resp = xmlhttp.responseText;
						if (resp == "ok") {
							Toast.fire({
								type: 'success',
								title: ' Se ha ASIGNADO la bodega '+sede+' al agente '+agente
							}); 
						}else{
							Toast.fire({
								type: 'error',
								title: ' Ha ocurriod un error inesperado.'
							}); 
						}
						location.reload();
					}
				}
				xmlhttp.open("GET", "<?=base_url()?>contaccenter/insert_distribucion?agente="+agente+"&sede="+sede, true);
				xmlhttp.send();
			}
			
		}
		function add_distribucion(dist,bod,agente){
			if (dist == "") {
				Toast.fire({
					type: 'error',
					title: ' No se pueden dejar campos vacios'
				});
			}else{
				var xmlhttp;
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function () {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						if (xmlhttp.responseText == "ok") {
							Toast.fire({
								type: 'success',
								title: ' Distribución agregada correctamente'
							});
						}else if(xmlhttp.responseText == "err"){
							Toast.fire({
								type: 'error',
								title: ' Ha ocurriod un error inesperado.'
							});
						}else if(xmlhttp.responseText == "err_sum"){
							Toast.fire({
								type: 'error',
								title: ' La distribución para esta bodega supera los 100 puntos...'
							});
						}
						cargar_totales();
					}
				}
				xmlhttp.open("GET", "<?=base_url()?>contaccenter/update_distribucion?agente="+agente+"&sede="+bod+"&dist="+dist, true);
				xmlhttp.send();
			}
			
		}

		function cargar_totales() {
			var result = document.getElementById("tabla_totales");
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
			xmlhttp.open("GET", "<?=base_url()?>contaccenter/cargar_totales?var=1", true);
			xmlhttp.send();
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