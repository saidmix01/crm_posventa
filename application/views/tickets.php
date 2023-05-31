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
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="<?= base_url() ?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
	<!-- Toastr -->
	<link rel="stylesheet" href="<?= base_url() ?>plugins/toastr/toastr.min.css">
	<style type="text/css">
	.loader {
		position: fixed;
		left: 100px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 9999;
		background: url('<?=base_url()?>media/cargando7.gif') 50% 50% no-repeat rgb(249,249,249);
		opacity: .9;
		display: none;
	}
	</style>

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
				<a href="#" class="brand-link"></a>

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
			<div class="loader" id="cargando"></div>
			<section class="content">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal_ticket"><i class="far fa-plus-square"></i> Nuevo Ticket</a>
							</div>
						</div>
						<hr>
						<nav>
							<div class="nav nav-tabs" id="nav-tab" role="tablist">
								<?php if ($admin == 0) { ?>
									<a class="nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">
										<h4>Tickets Asignados</h4>
									</a>
								<?php } ?>
								<a class="nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">
									<h4>Mis Tickets</h4>
								</a>
							</div>
						</nav>
						<?php
						if ($admin == 0) {
							$loadp = "";
						} else {
							$loadp = "show active";
						}
						?>
						<?php if ($admin == 0) { ?>
							<div class="tab-content" id="nav-tabContent">
								<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
									<div class="row">
										<div class="col-md-12">
											<div class="table-responsive">
												<table class="table table-hover" id="tabla_tickets" style="font-size: 13px;">
													<thead align="center">
														<tr>
															<th scope="col"># Ticket</th>
															<?php if ($admin == 0) { ?>
																<th scope="col">Accion</th>
															<?php } ?>
															<th scope="col">Estado</th>
															<th scope="col">Prioridad</th>
															<th scope="col">Tipo de soporte</th>
															<th scope="col">Usuario</th>
															<th scope="col">Encargado</th>
															<th scope="col">Fecha Creación</th>
														</tr>
													</thead>
													<tbody align="center">
														<?php foreach ($all_tickets->result() as $key) {
															$resp = "";
															if ($key->estado == "activo") {
																$color = "btn-success";
																$estado = "";
															} elseif ($key->estado == "En Proceso") {
																$color = "btn-warning";
																$estado = "";
															} elseif ($key->estado == "Cerrado") {
																$color = "btn-danger";
																$estado = "disabled";
															}
														?>
															<tr>
																<th scope="row" background="<?= base_url() ?>media/img/ticket.png" style="background-size: 100%; background-repeat: no-repeat; position: relative;top: 5px;">
																	<a href="#" class="" data-toggle="modal" data-target="#modal_ticket_view" onclick="ver_ticket(<?= $key->id_ticket ?>)" style="color: black; position: relative;top: -7px;"><?= $key->id_ticket; ?></a>
																</th>
																<?php

																if ($usu == $key->usuario) {
																	$estado = "disabled";
																}
																?>
																<?php
																if ($admin == 0) {
																?>
																	<th>
																		<button href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal_asignar_t" onclick="asignar_ticket(<?= $key->id_ticket; ?>)" <?= $estado ?>><i class="fas fa-retweet"></i> Reasignar Ticket</button>

																		<button href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#resp_ticket" onclick="resp_ticket(<?= $key->id_ticket; ?>)" <?= $estado ?>><i class="fab fa-facebook-messenger"></i> Responder</button>
																	</th>
																<?php } ?>
																<td>
																	<a href="#" class="btn <?= $color ?> btn-sm"><?= $key->estado; ?></a>
																</td>
																<td><?= $key->prioridad; ?></td>
																<td><?= $key->tipo_soporte; ?></td>
																<td><?= $key->nom_usu; ?></td>
																<td><?= $key->encargado; ?></td>
																<td><?= $key->fecha_creacion; ?></td>
															</tr>
														<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
							<div class="tab-pane fade <?= $loadp ?>" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
								<div class="row">
									<div class="col-md-12">
										<div class="table-responsive">
											<table class="table table-hover" id="tabla_tickets2" style="font-size: 13px;">
												<thead align="center">
													<tr>
														<th scope="col"># Ticket</th>
														<th scope="col">Accion</th>
														<th scope="col">Estado</th>
														<th scope="col">Prioridad</th>
														<th scope="col">Tipo de soporte</th>
														<th scope="col">Usuario</th>
														<th scope="col">Encargado</th>
														<th scope="col">Fecha Creación</th>
													</tr>
												</thead>
												<tbody align="center">
													<?php foreach ($mis_tickets->result() as $key) {
														$resp = "";
														$estado1 = "";
														if ($key->estado == "activo") {
															$color = "btn-success";
															$estado = "";
														} elseif ($key->estado == "En Proceso") {
															$color = "btn-warning";
															$estado = "";
														} elseif ($key->estado == "Cerrado") {
															$color = "btn-danger";
															$estado = "disabled";
															$estado1 = "disabled";
														}
													?>
														<tr>
															<th scope="row" background="<?= base_url() ?>media/img/ticket.png" style="background-size: 100%; background-repeat: no-repeat; position: relative;top: 5px;">
																<a href="#" style="color: black; position: relative;top: -7px;" data-toggle="modal" data-target="#modal_ticket_view" onclick="ver_ticket(<?= $key->id_ticket ?>)"># <?= $key->id_ticket; ?></a>
															</th>
															<?php

															if ($usu == $key->usuario) {
																$estado = "disabled";
															}
															?>
															<?php
															if ($admin == 0) {
															?>
																<th>
																	<button href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal_asignar_t" onclick="asignar_ticket(<?= $key->id_ticket; ?>)" <?= $estado ?>><i class="fas fa-retweet"></i> Reasignar Ticket</button>

																	<button href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#resp_ticket" onclick="resp_ticket(<?= $key->id_ticket; ?>)" <?= $estado ?>><i class="fab fa-facebook-messenger"></i> Responder</button>
																</th>
															<?php } ?>
															<th>
																<button href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#resp_ticket" onclick="resp_ticket(<?= $key->id_ticket; ?>)" <?= $estado1 ?>><i class="fab fa-facebook-messenger"></i> Responder</button>
															</th>
															<td>
																<a href="#" class="btn <?= $color ?> btn-sm"><?= $key->estado; ?></a>
															</td>
															<td><?= $key->prioridad; ?></td>
															<td><?= $key->tipo_soporte; ?></td>
															<td><?= $key->nom_usu; ?></td>
															<td><?= $key->encargado; ?></td>
															<td><?= $key->fecha_creacion; ?></td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
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

	<!-- Modal Nuevo ticket-->
	<div class="modal fade" id="modal_ticket" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Nuevo Ticket</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="POST" enctype="multipart/form-data">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="combo_tipo_soporte">Tipo de Soporte</label>
								<select class="form-control js-example-basic-single" style="width: 100%" id="combo_tipo_soporte" name="combo_tipo_soporte" required="true">
									<option value="">Seleccione una Opcion...</option>
									<option value="Insumos de Impresora(Toner)">Insumos de Impresora(Toner)</option>
									<option value="Hardware">Hardware</option>
									<option value="Software">Software</option>
									<option value="CRM Comercial">CRM Comercial</option>
									<option value="CRM PosVenta">CRM PosVenta</option>
									<option value="CRM DMS">CRM DMS</option>
									<option value="DMS">DMS</option>
								</select>
							</div>
							<div class="form-group col-md-6">
								<label for="anydesk">AnyDesk</label>
								<input type="text" class="form-control" id="anydesk" name="anydesk" placeholder="Ingresa el numero del AnyDesk">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="descripcion">Descripción del problema</label>
								<textarea name="descripcion" class="form-control" id="descripcion" required="true"></textarea>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label>Cargar un archivo</label>
								<input type="file" class="form-control-file" name="archivo" id="archivo">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<button id="btnCrearTicket" onclick="validarCrearTicket();" class="btn btn-success">Crear Ticket</button>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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

	<!-- Modal Asignar ticket-->
	<div class="modal fade" id="modal_asignar_t" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Asignar ticket</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="POST" action="<?= base_url() ?>tickets/asignar_ticket">
						<input type="hidden" name="ticket" id="ticket">
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="descripcion">Encargado</label>
								<select class="form-control js-example-basic-single" style="width: 100%" name="encargado" required="true">
									<option value="">Seleccione una opción</option>
									<option value="1098758579">SAID ANDRES AVENDAÑO - SISTEMAS</option>
									<option value="1096219894">ANDRES GOMEZ RUBIO - SISTEMAS</option>
									<option value="1110602826">CRISTIAN TUNJANO DIAZ - SISTEMAS</option>
									<option value="1098625558">NATHALIA RAMIREZ - SISTEMAS</option>
									<option value="1232892531">FERLEY AGILAR - SISTEMAS</option>
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="descripcion">Prioridad</label>
								<select class="form-control" name="prioridad" required="true">
									<option value="">Seleccione una opción</option>
									<option value="Alta">Alta</option>
									<option value="Media">Media</option>
									<option value="Baja">Baja</option>
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">

								<button class="btn btn-primary">Asignar Ticket</button>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Respuesta Ticket-->
	<div class="modal fade" id="resp_ticket" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Respuesta Ticket</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="card text-center">
						<div class="card-body">
							<input type="hidden" id="tick_asig">
							<div id="detalle_ticket">

							</div>

							<input type="hidden" id="tick_asig" name="ticket">
							<div class="form-row" align="justify">
								<div class="form-group col-md-12">
									<label for="resp1">Respuesta</label>
									<textarea class="form-control" name="resp1" id="resp1" required="true" onkeydown="noPuntoComa( event )"></textarea>
								</div>
							</div>
							<?php if ($admin == 0) { ?>
								<div class="form-row" align="justify">
									<div class="form-group clearfix">
										<div class="icheck-primary d-inline">
											<input type="checkbox" id="cerrar" name="cerrar">
											<label for="cerrar">
												Cerrar Ticket?
											</label>
										</div>
									</div>
								</div>
								<div class="form-row" align="justify">
									<div class="form-group col-md-12">
										<button class="btn btn-primary" onclick="crear_respuesta()">Responder</button>
									</div>
								</div>
							<?php } else { ?>
								<div class="form-row" align="justify">
									<div class="form-group col-md-12">
										<button class="btn btn-primary" onclick="crear_respuesta_u()">Responder</button>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Ver Ticket-->
	<div class="modal fade" id="modal_ticket_view" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Ticket Nro: </h5>
					<h5 class="modal-title" id="titulo_ticket"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="table-responsive">
						<div id="ticket_detalle">

						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" id="ticket_asig">
					<?php if ($admin == 0) { ?>
						<a href="#" class="btn btn-success" onclick="asig_ticket()">Asignar Ticket</a>
					<?php } ?>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- MENSAJE FLOTANTE-->
	<?php
	$log = $this->input->get('log');
	if ($log == "ok") {


	?>
		<script>
			$('#btnCrearTicket').prop('disabled', false);
		</script>
		<div class="alert alert-success alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
			Ticket creado exitosamente
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	<?php
	} elseif ($log == "err") {
	?>
		<script>
			$('#btnCrearTicket').prop('disabled', false);
		</script>
		<div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
			Error al crear el ticket
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
	<!-- SweetAlert2 -->
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<!-- Toastr -->
	<script src="<?= base_url() ?>plugins/toastr/toastr.min.js"></script>
	<!-- DataTables -->
	<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
	<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script type="text/javascript">
		$(document).ready(function() {
			$("#buscar_items").on("keyup", function() {
				var value = $(this).val().toLowerCase();
				$("#menu_items li").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
			});
			$('.js-example-basic-single').select2({
				theme: "classic"
			});
		});

		function noPuntoComa(event) {

			var e = event || window.event;
			var key = e.keyCode || e.which;

			if (key === 110 || key === 190 || key === 188) {

				e.preventDefault();
			}
		}
	</script>
	<!-- Funcion que cambia el lenguaje al datatable -->
	<script>
		$(document).ready(function() {
			$('#tabla_tickets').DataTable({
				"paging": true,
				"pageLength": 10,
				"lengthChange": true,
				"searching": true,
				"ordering": false,
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
			$('#tabla_tickets2').DataTable({
				"paging": true,
				"pageLength": 10,
				"lengthChange": true,
				"searching": true,
				"ordering": false,
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
	<!--  Funcion que cierra los alerts  -->
	<script type="text/javascript">
		setTimeout(function() {
			$('#alert_err').alert('close');
		}, 1500);
		setTimeout(function() {
			$('#alert_ok').alert('close');
		}, 1500);
	</script>

	<script type="text/javascript">
		function asignar_ticket(ticket) {
			$('#ticket').val(ticket);
		}
	</script>

	<script type="text/javascript">
		function ver_ticket(ticket) {
			var result = document.getElementById("ticket_detalle");
			$('#titulo_ticket').html(ticket);
			$('#ticket_asig').val(ticket);
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
			xmlhttp.open("GET", "<?= base_url() ?>tickets/get_ticket_by_id?ticket=" + ticket, true);
			xmlhttp.send();
		}

		function asig_ticket() {
			var ticket = $('#ticket_asig').val();
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					//result.innerHTML = xmlhttp.responseText;
					location.reload();
				}
			}
			xmlhttp.open("GET", "<?= base_url() ?>tickets/asig_ticket?ticket=" + ticket, true);
			xmlhttp.send();
		}

		function resp_ticket(ticket) {
			$('#tick_asig').val(ticket);
			detalle_ticket();
		}

		function detalle_ticket() {
			var ticket = $('#tick_asig').val();
			var result = document.getElementById("detalle_ticket");
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
			xmlhttp.open("GET", "<?= base_url() ?>tickets/get_ticket_by_id?ticket=" + ticket, true);
			xmlhttp.send();
		}

		function crear_respuesta() {
			var ticket = $('#tick_asig').val();
			var resp = $('#resp1').val();
			if (resp == "") {
				alert("Hay campos vacios");
			} else {
				document.getElementById("cargando").style.display = "block";
				var cerrar = "";
				if ($('#cerrar').is(':checked')) {
					cerrar = "on";
				}
				var xmlhttp;
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						//result.innerHTML = xmlhttp.responseText;
						//location.reload();
						var user = "admin";
						sendEmailChat(ticket,user);
					}
				}
				xmlhttp.open("GET", "<?= base_url() ?>tickets/resp_ticket?ticket=" + ticket + "&resp=" + resp + "&cerrar=" + cerrar + "&t_u=admin", true);
				xmlhttp.send();
			}

		}

		function crear_respuesta_u() {
			var ticket = $('#tick_asig').val();
			var resp = $('#resp1').val();
			if (resp == "") {
				alert("Hay campos vacios");
			} else {
				document.getElementById("cargando").style.display = "block";
				var cerrar = "";
				if ($('#cerrar').is(':checked')) {
					cerrar = "on";
				}
				var xmlhttp;
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						console.log(xmlhttp.responseText);
						var user = 'user';
						sendEmailChat(ticket,user);
						//location.reload();
					}
				}
				xmlhttp.open("GET", "<?= base_url() ?>tickets/resp_ticket?ticket=" + ticket + "&resp=" + resp + "&cerrar=" + cerrar + "&t_u=user", true);
				xmlhttp.send();
			}

		}

		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 5000
		});

		function validarCrearTicket() {
			var descripcion = document.getElementById('descripcion').value;
			var anydesk = document.getElementById('anydesk').value;
			var combo_tipo_soporte = document.getElementById('combo_tipo_soporte').value;
			var url = '<?= base_url() ?>tickets/new_ticket';
			var datos = new FormData();
			if (descripcion == "" || combo_tipo_soporte == "") {

			} else {
				$('#btnCrearTicket').prop('disabled', true);
				datos.append('archivo', document.querySelector("#archivo").files[0]);
				datos.append('descripcion', descripcion);
				datos.append('anydesk', anydesk);
				datos.append('combo_tipo_soporte', combo_tipo_soporte);

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						var flag = xmlhttp.responseText;
						if (flag = "ok") {
							Swal.fire({
								title: 'Ticket Creado',
								icon: 'success',
								confirmButtonText: 'Ok',
								allowOutsideClick: false,
								showCloseButton: true
							}).then((result) => {
							if (result.isConfirmed) {
								console.log('confirmado');
								location.reload();
							} 
							});								
						} else {
							Swal.fire({
								title: 'Error al crear el Ticket',
								icon: 'error',
								confirmButtonText: 'Ok',
								allowOutsideClick: false,
								showCloseButton: true
							});
						}


					}
				}
				xmlhttp.open("POST", url);
				xmlhttp.send(datos);

			}
		}

		function sendEmailChat(id,user){
			var url = '<?= base_url() ?>tickets/sendEmailRespuestas';
			var datos = new FormData();
			datos.append('id_ticket', id);
			datos.append('user', user);
			if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						var flag = xmlhttp.responseText;
						if (flag = "ok") {
							document.getElementById("cargando").style.display = "none";
							Swal.fire({
								title: 'Correo de notificación enviado',
								icon: 'success',
								confirmButtonText: 'Ok',
								allowOutsideClick: false,
								showCloseButton: true
							}).then((result) => {
							if (result.isConfirmed) {
								location.reload();
							} 
							});								
						} else if (flag == 'error') {
							document.getElementById("cargando").style.display = "none";
							Swal.fire({
								title: 'Error al enviar el correo de notificación',
								icon: 'error',
								confirmButtonText: 'Ok',
								allowOutsideClick: false,
								showCloseButton: true
							});
						}


					}
				}
				xmlhttp.open("POST", url);
				xmlhttp.send(datos);
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
