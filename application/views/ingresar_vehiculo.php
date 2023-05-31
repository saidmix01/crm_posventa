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
	<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
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
						<img src="<?=base_url()?>media/logo/logo_codiesel.png" class="img-fluid" alt="Responsive image">
					</div>
				</div>
				<a href="#" class="brand-link">

				</a>

				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<div class="input-group input-group-sm">
						<input class="form-control form-control-navbar" id="buscar_items" type="search" placeholder="Buscar" aria-label="Search" style="background-color: #3D3D3D; color: #fff; border-top: 0; border-left: 0; border-right: 0 border-color'gray';">
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
				<div id="loadingmsg" class="loadingmsg" style="width: 80%; height: 100%; z-index: 999; background-color: gray; position: absolute; margin-top: -15px; opacity: 0.3;" align="center"><img style="margin-top: 25%; width: 150px; height: 90px;" src="<?=base_url()?>media/cargando2.gif"></div>
				<div class="container-fluid tabla" id="tabla" style="display: none;">
					<div class="card">
						<div class="card-body" align="center">
							<button type="button" class="btn btn-lg btn-default" data-toggle="modal" data-target="#modalvehiculo">
								Dar salida <?=$tipo?> 
							</button>

						</div>
					</div>
					<div class="card">
				  <!--<div class="card-body">
				    <form method="POST" action="<?=base_url()?>admin_vehiculos/buscar_por_bodega">
					  <div class="form-row">
					    <div class="form-group col-md-6">
					      <label for="placa">Buscar por bodegas</label>
					      <select class="form-control form-control-sm" name="combo_bodega" required="true" id="bodegas">
								<option value="">Seleccione algo...</option>
								<?php 
									foreach ($bodegas->result() as $key) {
								 ?>
								<option value="<?=$key->bodega?>"><?=$key->descripcion?></option>
								 <?php 
								 	}
								  ?>
								      
						  </select>
					    </div>
					    <div class="form-group col-md-6" align="center" style="margin-top: 12px;">
					      <button type="submit" class="btn btn-default"><i class="fas fa-search"></i>  Buscar</button>
					    </div>
					  </div>
					</form>
				</div>-->
							<?php if($tipo=="Orden General"){
					?>
					<div class="table-responsive container-fluid tabla table-sm" id="tabla">
						<!--  Tabla usuarios  -->
						<table id="example1" class="table table-sm table-bordered table-hover">
							<thead>
								<tr align="center">
									<th>SERIAL</th>
									<th>DESCRIPCION</th>
									<th>FECHA REGISTRO</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								foreach ($vehiculos->result() as $key) {
									$color = '';
									if ($key->fecha_salida == NULL) {
										$color = "table-warning";
									}elseif ($key->fecha_reingreso == NULL) {
										$color = "table-danger";
									}
									?>
									<tr align="center" class="<?=$color?>">
										<td><?=$key->placa?></td>
										<td><?=$key->contenido?></td>
										<td><?=$key->fecha_ingreso?></td>
									</tr>
									<?php 
								}
								?>
							</tbody>
						</table>
					</div>
					<?php 
				}else{
					?>
					<div class="table-responsive container-fluid tabla" id="tabla" style="font-size: 13px;">
						<br>
						<div class="row">
							<div class="col-md-12" align="right">
								<a href="#" onclick="load_tabla_tot(2);" class="btn btn-secondary">Ver todos los Registros</a>
							</div>
						</div>
						<!--  Tabla usuarios  -->
						<table id="example1" class="table table-sm table-bordered table-hover">
							<thead>
								<tr align="">
									<th>N° DE ORDEN </th>
									<th align="center">PLACA</th>
									<th>VEHICULO</th>
									<th>PROVEEDOR</th>
									<th>CONTENIDO</th>
									<th>FECHA SALIDA</th>
									<th>FECHA REINGRESO</th>
									<th>ACCIONES</th>
								</tr>
							</thead>
							<tbody id="tabla_tot">
								
							</tbody>
						</table>
					</div>
					<?php 
				}
				?>
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
<!-- alertas-->
<?php 
$log = $this->input->get('log');
if($log == "ok"){

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

<!-- Modal Registro Vehiculos-->
<div class="modal fade" id="modalvehiculo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Registrar <?=$tipo?> para Salida</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php 
				if ($tipo == "vehiculo") {
					?>
					<form class="form" action="<?=base_url()?>/admin_vehiculos/crear_vehiculo" method="POST">
						<div class="form-group">
							<label for="placa">Placa</label>
							<input type="text" class="form-control" id="placa" name="placa" placeholder="ABC123" required="true">
						</div>
						<div class="form-group">
							<label for="orden">Orden</label>
							<input type="text" class="form-control" id="orden" name="orden" placeholder="######" required="true">
						</div>
						<button type="submit" class="btn btn-success mb-2 btn-lg">Dar salida Vehiculo</button>
					</form>
					<?php 
				}elseif ($tipo == "tot") {
					?>
					<form class="form" action="<?=base_url()?>/admin_vehiculos/crear_tot" method="POST">
						<div class="form-group" style="display: none;">
							<label for="placa">Placa</label>
							<input type="hidden" class="form-control" id="placa" name="placa" placeholder="ABC123" value="0" required="true">
						</div>
						<div class="form-group">
							<label for="orden">Orden</label>
							<input type="text" class="form-control" id="orden" name="orden" onkeyup="val_orden();" placeholder="######" required="true">
							<small id="msg_ot" class="form-text text-muted"></small>
						</div>
						<div class="form-group">
							<label for="proveedor">Proveedor</label>
							<input type="text" class="form-control" id="proveedor" name="proveedor" placeholder="Escriba algo...">
						</div>
						<div class="form-group">
							<label for="contenido">Contiene</label>
							<textarea class="form-control" id="contenido" name="contenido"></textarea>
						</div>
						<button type="submit" id="btn_tot" onclick="generar_recibo_tot();" class="btn btn-success mb-2 btn-lg">Dar salida TOT</button>
					</form>

					<?php 
				}elseif($tipo == "Orden General"){
					?>
					<form class="form" action="<?=base_url()?>/admin_vehiculos/crear_ord_gral" method="POST">
						<div class="form-group">
							<label for="serial">Serial(SN)</label>
							<input type="text" class="form-control" id="serial" name="serial" placeholder="xxxxx" required="true">
						</div>
						<div class="form-group">
							<label for="descripcion">Descripcion</label>
							<textarea class="form-control" id="descripcion" name="descripcion"></textarea>
						</div>
						<button type="submit" class="btn btn-success mb-2 btn-lg">Dar salida orden generla</button>
					</form>
					<?php 
				}
				?>
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
<script src="<?=base_url()?>dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?=base_url()?>dist/js/pages/dashboard.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- DataTables -->
<script src="<?=base_url()?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?=base_url()?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- SweetAlert2 -->
<script src="<?=base_url()?>plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="<?=base_url()?>plugins/toastr/toastr.min.js"></script>
<!--  Funcion que cierra los alerts  -->
<script type="text/javascript">
	setTimeout(function(){ 
		$('#alert_err').alert('close');
	}, 1500);
	setTimeout(function(){ 
		$('#alert_ok').alert('close');
	}, 1500);
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#buscar_items").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#menu_items li").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		$('#bodegas').select2();
		load_tabla_tot(1);
		//setInterval('notificacion()',10000);
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
	function val_orden() {
		var orden = document.getElementById("orden").value;
		var msg_ot = document.getElementById("msg_ot");
		var btn_tot = document.getElementById("btn_tot");
		btn_tot.disabled=true;
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				if (xmlhttp.responseText=="Error, orden no existe") {
					msg_ot.innerHTML='<strong style="color: red">'+xmlhttp.responseText+'</strong>';
				}else if(xmlhttp.responseText=="Bien, la orden existe"){
					msg_ot.innerHTML='<strong style="color: green">'+xmlhttp.responseText+'</strong>';
					btn_tot.disabled=false;
				}
				msg_ot.style.color = "orange";
			}
		}
		xmlhttp.open("GET", "<?=base_url()?>taller/val_orden?orden="+orden, true);
		xmlhttp.send();
	}

	function generar_recibo_tot() {
		var orden = document.getElementById("orden").value;
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				
			}
		}
		xmlhttp.open("GET", "<?=base_url()?>taller/generar_recibo_tot?orden="+orden, true);
		xmlhttp.send();
	}

	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 5000
	});

	function reload_page() {
		location.reload();
	}

	function marcar_reingreso(orden) {
		//var orden = document.getElementById("orden").value;
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				var resp = xmlhttp.responseText;
				if (resp=="ok") {
					Toast.fire({
						type: 'success',
						title: ' Operacion realizada correctamente...'
					}); 
				}else if(resp=="err"){
					Toast.fire({
						type: 'error',
						title: ' Ha ocurrido un error al marcar el reingreso.'
					}); 
				}else{
					Toast.fire({
						type: 'error',
						title: ' Ha ocurriod un error inesperado.'
					}); 
				}
				setTimeout(reload_page,3000);	
			}
		}
		xmlhttp.open("GET", "<?=base_url()?>admin_vehiculos/cambiar_estado_reingreso?id="+orden, true);
		xmlhttp.send();
	}
</script>
<script>
	/*$(document).ready(function(){
		$('#example1').DataTable({
			"paging": true,
			"pageLength": 25,
			"lengthChange": true,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
		      //"processing": true,
		      "fnDrawCallback": function() {
				// Hide the Loading Spinner...
				document.getElementById('loadingmsg').style.display = 'none';
				document.getElementById('tabla').style.display = 'block';
			},
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
	});*/ 
	function data_tabla_tot() {
		//table.destroy();
		table = $('#example1').DataTable({
			"paging": true,
			"pageLength": 25,
			"lengthChange": true,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
		      //"processing": true,
		      "fnDrawCallback": function() {
				// Hide the Loading Spinner...
				document.getElementById('loadingmsg').style.display = 'none';
				document.getElementById('tabla').style.display = 'block';
			},
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
		//table.destroy();
	}
	function load_tabla_tot(val) {
		$("#example1").dataTable().fnDestroy();
		var result = document.getElementById("tabla_tot");
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				result.innerHTML = xmlhttp.responseText;
				data_tabla_tot();
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>admin_vehiculos/load_tabla_reg_tot?var="+val, true);
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
