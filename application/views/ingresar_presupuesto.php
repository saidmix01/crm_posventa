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
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
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
				<div class="container-fluid" align="center">
					<div class="card">
						<br>
						<label class="col-lg-12 text-center lead">Ingreso de Presupuesto</label>
						<hr>
						<div class="card-body  btn-group-justified">
							<button onclick="validar_sede(this.id);" id="giron" value="giron" class="btn btn-default shadow col-lg-1" data-toggle="modal" data-target="#crear_presupuesto">Girón</button>
							<button onclick="validar_sede(this.id);" id="rosita" value="rosita" class="btn btn-default shadow col-lg-2" data-toggle="modal" data-target="#crear_presupuesto">La Rosita</button>
							<button onclick="validar_sede(this.id);" id="bocono" value="bocono" class="btn btn-default shadow col-lg-2" data-toggle="modal" data-target="#crear_presupuesto">Boconó</button>
							<button onclick="validar_sede(this.id);" id="chevro" value="chevro" class="btn btn-default shadow col-lg-2" data-toggle="modal" data-target="#crear_presupuesto">Chevropartes</button>
							<button onclick="validar_sede(this.id);" id="solochevro" value="solochevro" class="btn btn-default shadow col-lg-2" data-toggle="modal" data-target="#crear_presupuesto">Solochevrolet</button>
							<button onclick="validar_sede(this.id);" id="barranca" value="barranca" class="btn btn-default shadow col-lg-2" data-toggle="modal" data-target="#crear_presupuesto">Barrancabermeja</button>
						</div>
					</div>

					<div class="card">
						<div class="card-body">
							<form method="POST" action="<?= base_url() ?>admin_presupuesto/ingresar_presupuesto">
								<div class="form-row">
									<div class="col-lg-6">
										<label for="year">Seleccione el Año a Consultar</label>
										<select class="form-control" id="year" name="year">
											<option value=""></option>
											<option value="2020">2020</option>
											<option value="2021">2021</option>

										</select>
									</div>

									<div class="col-lg-6">
										<label for="mes">Seleccione el mes a Consultar</label>
										<select class="form-control" id="exampleFormControlSelect1" name="mes">
											<option value=""></option>
											<option value="Enero">Enero</option>
											<option value="Febrero">Febrero</option>
											<option value="Marzo">Marzo</option>
											<option value="Abril">Abril</option>
											<option value="Mayo">Mayo</option>
											<option value="Junio">Junio</option>
											<option value="Julio">Julio</option>
											<option value="Agosto">Agosto</option>
											<option value="Septiembre">Septiembre</option>
											<option value="Octubre">Octubre</option>
											<option value="Noviembre">Noviembre</option>
											<option value="Diciembre">Diciembre</option>
										</select>
									</div>

									<div class="col">
										<button type="input" name="btn" class="btn btn-info" style="margin-top: 15px;">Buscar</button>
									</div>
								</div>
							</form>
							<hr>
							<?php
							if ($all_presu != null) {

							?>
								<div class="table-responsive" id="tablasfiltro">
									<!--  Tabla usuarios  -->
									<table id="example1" class="table table-bordered table-hover">
										<thead class="thead-dark">
											<tr>
												<th scope="col">SEDE</th>
												<th class="text-left" scope="col">PRESUPUESTO</th>
												<th class="text-center" scope="col">MES</th>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach ($all_presu->result() as $key) {
											?>
												<tr">
													<td class="text-left"><?= $key->sede ?></td>
													<td class="text-left">$<?= number_format($key->presupuesto, 0, ",", ","); ?></td>
													<td class="text-center"><?= $key->mes ?></td>
													</tr>
												<?php
											}
												?>
										</tbody>
									</table>
								</div>
							<?php
							}
							?>
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

	<!-- Modal -->
	<div class="modal fade" id="crear_presupuesto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title col-lg-12 text-center" id="exampleModalLabel">Ingresar presupuesto <?= $mes ?></h5>
				</div>
				<div class="modal-body">
					<form method="POST">
						<div class="form-row">
							<div class="col-lg-4 d-none">
								<alert>sede</alert>
								<input type="text" value="" class="form-control" name="sede" id="sede">
							</div>
							<div class="col-lg-4" id="r_gasolina">
								<alert>Repuesto Gasolina</alert>
								<input type="text" class="form-control" name="presupuestorepsutogasolina" id="presupuestorepsutogasolina" placeholder="Sin puntos ni comas">
							</div>
							<div class="col-lg-4 d-none">
								<alert>Repuesto Gasolina</alert>
								<input type="text" value="REPUESTOS GASOLINA" class="form-control" name="sedepresupuestorepsutogasolina" id="sedepresupuestorepsutogasolina">
							</div>
							<div class="col-lg-4" id="t_gasolina">
								<alert>TOT Gasolina</alert>
								<input type="text" class="form-control" name="presupuestotot_gasolina" id="presupuestotot_gasolina" placeholder="Sin puntos ni comas">
							</div>
							<div class="col-lg-4 d-none">
								<alert>TOT Gasolina</alert>
								<input type="text" value="TOT GASOLINA" class="form-control" name="sedepresupuestotot_gasolina" id="sedepresupuestotot_gasolina">
							</div>
							<div class="col-lg-4" id="m_gasolina">
								<alert>MO Gasolina</alert>
								<input type="text" class="form-control" name="presupuestomd_gasolina" id="presupuestomd_gasolina" placeholder="Sin puntos ni comas">
							</div>
							<div class="col-lg-4 d-none">
								<alert>MO Gasolina</alert>
								<input type="text" value="MO GASOLINA" class="form-control" name="sedepresupuestomd_gasolina" id="sedepresupuestomd_gasolina">
							</div>
							<div class="col-lg-4" id="r_diesel">
								<alert">Repuesto Diesel</alert>
									<input type="text" class="form-control" name="presupuestorepuesto_diesel" id="presupuestorepuesto_diesel" placeholder="Sin puntos ni comas">
							</div>
							<div class="col-lg-4 d-none">
								<alert>Repuesto Diesel</alert>
								<input type="text" value="REPUESTOS DIESEL" class="form-control" name="sedepresupuestorepuesto_diesel" id="sedepresupuestorepuesto_diesel">
							</div>
							<div class="col-lg-4" id="t_diesel">
								<alert>TOT Diesel</alert>
								<input type="text" class="form-control" name="presupuestotot_diesel" id="presupuestotot_diesel" placeholder="Sin puntos ni comas">
							</div>
							<div class="col-lg-4 d-none">
								<alert>TOT Diesel</alert>
								<input type="text" value="TOT DIESEL" class="form-control" name="sedepresupuestotot_diesel" id="sedepresupuestotot_diesel">
							</div>
							<div class="col-lg-4" id="m_diesel">
								<alert>MO Diesel</alert>
								<input type="text" class="form-control" name="presupuestomo_diesel" id="presupuestomo_diesel" placeholder="Sin puntos ni comas">
							</div>
							<div class="col-lg-4 d-none">
								<alert>MO Diesel</alert>
								<input type="text" value="MO DIESEL" class="form-control" name="sedepresupuestomo_diesel" id="sedepresupuestomo_diesel">
							</div>
							<div class="col-lg-4" id="p_mostrador">
								<alert>Repuesto Mostrador</alert>
								<input type="text" class="form-control" name="presupuestorepuesto_mostrador" id="presupuestorepuesto_mostrador" placeholder="Sin puntos ni comas">
							</div>
							<div class="col-lg-4 d-none">
								<alert>Repuesto Mostrador</alert>
								<input type="text" value="REPUESTOS MOSTRADOR" class="form-control" name="sedepresupuestorepuesto_mostrador" id="sedepresupuestorepuesto_mostrador">
							</div>
							<div class="col-lg-4" id="r_lamina">
								<alert>Repuesto LYM</alert>
								<input type="text" class="form-control" name="presupuesto_lamina" id="presupuesto_lamina" placeholder="Sin puntos ni comas">
							</div>
							<div class="col-lg-4 d-none" id="p_lamina">
								<alert>Repuesto LYM</alert>
								<input type="text" value="REPUESTOS LYP " class="form-control" name="sede_presupuesto_lamina" id="sede_presupuesto_lamina">
							</div>
							<div class="col-lg-4" id="t_lamina">
								<alert>TOT LYM</alert>
								<input type="text" class="form-control" name="presupuesto_tot_lym" id="presupuesto_tot_lym" placeholder="Sin puntos ni comas">
							</div>
							<div class="col-lg-4 d-none">
								<alert>TOT LYM</alert>
								<input type="text" value="TOT LYP " class="form-control" name="sede_presupuesto_tot_lym" id="sede_presupuesto_tot_lym">
							</div>
							<div class="col-lg-4" id="m_lamina">
								<alert>MO LYM</alert>
								<input type="text" class="form-control" name="presupuesto_mo_lym" id="presupuesto_mo_lym" placeholder="Sin puntos ni comas">
							</div>
							<div class="col-lg-4 d-none">
								<alert>MO LYM</alert>
								<input type="text" value="MO LYP " class="form-control" name="sede_presupuesto_mo_lym" id="sede_presupuesto_mo_lym">
							</div>
						</div>
						<br>
						<div class="modal-footer">
							<button type="button" onclick="agregar_presupuesto();" class="btn btn-success">Ingresar</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>



	<script>
		function validar_sede(id) {
			var r_gasolina = document.getElementById('r_gasolina');
			var t_gasolina = document.getElementById('t_gasolina');
			var m_gasolina = document.getElementById('m_gasolina');
			var r_diesel = document.getElementById('r_diesel');
			var t_diesel = document.getElementById('t_diesel');
			var m_diesel = document.getElementById('m_diesel');
			var p_mostrador = document.getElementById('p_mostrador');
			var r_laminia = document.getElementById('r_lamina');
			var p_lamina = document.getElementById('p_lamina');
			var t_lamina = document.getElementById('t_lamina');
			var m_lamina = document.getElementById('m_lamina');

			if (id == 'barranca') {
				document.getElementById('sede').value = "BARRANCA";
				r_gasolina.style.display = "block";
				t_gasolina.style.display = "block";
				m_gasolina.style.display = "block";
				r_diesel.style.display = "block";
				m_diesel.style.display = "block";
				t_diesel.style.display = "block";
				t_diesel.style.display = "block";
				p_mostrador.style.display = "block";
				m_lamina.style.display = "none";
				t_lamina.style.display = "none";
				p_lamina.style.display = "none";
				r_lamina.style.display = "none";

			} else if (id == 'giron') {
				document.getElementById('sede').value = "GIRON";
				r_gasolina.style.display = "block";
				t_gasolina.style.display = "block";
				m_gasolina.style.display = "block";
				r_diesel.style.display = "block";
				m_diesel.style.display = "block";
				t_diesel.style.display = "block";
				p_mostrador.style.display = "block";
				m_lamina.style.display = "block";
				t_lamina.style.display = "block";
				p_lamina.style.display = "block";
				r_lamina.style.display = "block";

			} else if (id == 'bocono') {
				document.getElementById('sede').value = "BOCONO";
				r_gasolina.style.display = "block";
				t_gasolina.style.display = "block";
				m_gasolina.style.display = "block";
				r_diesel.style.display = "block";
				m_diesel.style.display = "block";
				t_diesel.style.display = "block";
				p_mostrador.style.display = "block";
				m_lamina.style.display = "block";
				t_lamina.style.display = "block";
				p_lamina.style.display = "block";
				r_lamina.style.display = "block";

			} else if (id == 'chevro') {
				document.getElementById('sede').value = 'CHEVROPARTES';
				r_gasolina.style.display = "none";
				t_gasolina.style.display = "none";
				m_gasolina.style.display = "none";
				r_diesel.style.display = "none";
				m_diesel.style.display = "none";
				t_diesel.style.display = "none";
				t_diesel.style.display = "none";
				p_mostrador.style.display = "block";
				m_lamina.style.display = "none";
				t_lamina.style.display = "none";
				p_lamina.style.display = "none";
				r_lamina.style.display = "none";

			} else if (id == 'rosita') {
				document.getElementById('sede').value = "ROSITA";
				r_diesel.style.display = "none";
				m_diesel.style.display = "none";
				t_diesel.style.display = "none";
				m_lamina.style.display = "none";
				t_lamina.style.display = "none";
				p_lamina.style.display = "none";
				r_lamina.style.display = "none";


			} else if (id == 'solochevro') {
				document.getElementById('sede').value = 'SOLOCHEVROLET';
				r_gasolina.style.display = "none";
				t_gasolina.style.display = "none";
				m_gasolina.style.display = "none";
				r_diesel.style.display = "none";
				m_diesel.style.display = "none";
				t_diesel.style.display = "none";
				t_diesel.style.display = "none";
				p_mostrador.style.display = "block";
				m_lamina.style.display = "none";
				t_lamina.style.display = "none";
				p_lamina.style.display = "none";
				r_lamina.style.display = "none";

			}
		}



		function agregar_presupuesto() {
			/* valores*/
			var repuesto_mostrador = document.getElementById('presupuestorepuesto_mostrador').value;
			var repuesto_gasolina = document.getElementById('presupuestorepsutogasolina').value;
			var repuesto_diesel = document.getElementById('presupuestorepuesto_diesel').value;
			var tot_gasolina = document.getElementById('presupuestotot_gasolina').value;
			var mo_gasolina = document.getElementById('presupuestomd_gasolina').value;
			var tot_diesel = document.getElementById('presupuestotot_diesel').value;
			var mo_diesel = document.getElementById('presupuestomo_diesel').value;
			var repuesto_lamina = document.getElementById('presupuesto_lamina').value;
			var tot_lamina = document.getElementById('presupuesto_tot_lym').value;
			var mo_lamina = document.getElementById('presupuesto_mo_lym').value;
		/* 	console.log(repuesto_mostrador);
			console.log(repuesto_gasolina);
			console.log(repuesto_diesel);
			console.log(tot_gasolina);
			console.log(mo_gasolina);
			console.log(tot_diesel);
			console.log(mo_diesel);
			console.log(repuesto_lamina);
			console.log(tot_lamina);
			console.log(mo_lamina); */

			/*sede */
			var sede = document.getElementById('sede').value;
			var sede_repuesto_gasolina = document.getElementById('sedepresupuestorepsutogasolina').value;
			var sede_tot_gasolina = document.getElementById('sedepresupuestotot_gasolina').value;
			var sede_mo_gasolina = document.getElementById('sedepresupuestomd_gasolina').value;
			var sede_respuesto_diesel = document.getElementById('sedepresupuestorepuesto_diesel').value;
			var sede_tot_diesel = document.getElementById('sedepresupuestotot_diesel').value;
			var sede_mo_disel = document.getElementById('sedepresupuestomo_diesel').value;
			var sede_respuesto_mostrador = document.getElementById('sedepresupuestorepuesto_mostrador').value;
			var sede_repuesto_lamina = document.getElementById('sede_presupuesto_lamina').value;
			var sede_tot_lamina = document.getElementById('sede_presupuesto_tot_lym').value;
			var sede_mo_lamina = document.getElementById('sede_presupuesto_mo_lym').value;

			if (!isNaN(repuesto_mostrador) || !isNaN(repuesto_gasolina) || !isNaN(repuesto_diesel) || !isNaN(tot_gasolina) || !isNaN(mo_gasolina) || !isNaN(tot_diesel) || !isNaN(mo_diesel)) {
				url = '<?= base_url() ?>admin_presupuesto/crear_presupuesto';
				var datos = new FormData();
				datos.append('r_gasolina', repuesto_gasolina);
				datos.append('t_gasolina', tot_gasolina);
				datos.append('m_gasolina', mo_gasolina);
				datos.append('r_diesel', repuesto_diesel);
				datos.append('t_diesel', tot_diesel);
				datos.append('m_diesel', mo_diesel);
				datos.append('r_mostrador', repuesto_mostrador);
				datos.append('r_lamina', repuesto_lamina);
				datos.append('t_lamina', tot_lamina);
				datos.append('m_lamina', mo_lamina);

				datos.append('sede', sede);
				datos.append('sede_r_gasolina', sede_repuesto_gasolina)
				datos.append('sede_t_gasolina', sede_tot_gasolina);
				datos.append('sede_m_gasolina', sede_mo_gasolina);
				datos.append('sede_r_diesel', sede_respuesto_diesel);
				datos.append('sede_t_diesel', sede_tot_diesel);
				datos.append('sede_m_diesel', sede_mo_disel);
				datos.append('sede_r_mostrador', sede_respuesto_mostrador);
				datos.append('sede_r_lamina', sede_repuesto_lamina);
				datos.append('sede_t_lamina', sede_tot_lamina);
				datos.append('sede_m_lamina', sede_mo_lamina);

				var request = new XMLHttpRequest();
				request.onreadystatechange = function() {
					if (request.readyState === 4 && request.status === 200) {
						location.reload();
					}
				}
				request.open("POST", url);
				request.send(datos);
			} else {
				alert("Los campos deben contener solo numeros");
			}
		}

		function vertabla() {
			var tabla = document.getElementById('tablasfiltro');
			tabla.classList.remove('d-none');
		}
	</script>


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
	<!-- overlayScrollbars -->
	<script src="<?= base_url() ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?= base_url() ?>dist/js/adminlte.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="<?= base_url() ?>dist/js/pages/dashboard.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
	<!-- DataTables -->
	<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
	<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>

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
		$(document).ready(function() {
			$('#bodegas').select2();
		});
	</script>
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