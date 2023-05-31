<?php $this->load->view('Informe_kpi/header') ?>

<div class="content-wrapper">
	<br>
	<!-- Main content -->
	<section class="content">
		<div class="loader" id="cargando"></div>
		<div class="card">
			<div class="card-body">
				<div class="row" align="center">
					<h3>Indicador KPI</h3>
				</div>
				<hr>
				<div class="row">
					<div class="col-12 col-md-8 col-sm-6">
						<label class="control-label">KPI:</label>
						<select class="form-control js-example-basic-single" id="ind_kpi" name="ind_kpi">
							<option value="">Seleccione un KPI</option>
							<!-- <option value="95">Recomendación del cliente</option> -->
							<option value="96">Cantidad de OT's de mantenimiento preventivo</option>
							<option value="97">Cantidad de OT's cargo cliente</option>
							<option value="98">Facturación total del taller</option>
							<option value="99">Facturación total / tecnico / día Hábil</option>
							<option value="100">OT's / técnico / día hábil</option>
						</select>
					</div>
					<div class="col-12 col-md-4 col-sm-6" style="align-self: flex-end;">
						<button type="button" class="btn btn-success" onclick="cargarTablaKpi();">Generar</button>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-12 table-responsive">
						<div id="tabla_result">

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




<!-- jQuery -->
	<script src="<?= base_url() ?>plugins/jquery/jquery.min.js"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="<?= base_url() ?>plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- jQuery UI 1.11.4 -->
	<!-- Bootstrap 4 -->
	<script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- overlayScrollbars -->
	<script src="<?= base_url() ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- DataTables -->
	<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
	<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>

	<script src="<?= base_url() ?>dist/js/adminlte.js"></script>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<!--select2-->
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">

	$(document).ready(function () {
		$('.js-example-basic-single').select2({
  			placeholder: 'Seleccione una opción',
		});
	});

	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 5000
	});

	function cargarTablaKpi() {
		let selectKpi = document.getElementById('ind_kpi').value;
		if (selectKpi != "") {
			document.getElementById('cargando').style.display = 'block';
			var datos = new FormData();
			datos.append('ind_kpi', selectKpi);
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			/* xmlhttp.responseType = 'json'; */
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					document.getElementById('tabla_result').innerHTML = xmlhttp.responseText;
					document.getElementById('cargando').style.display = 'none';
				}
			}
			xmlhttp.open("POST", "<?= base_url() ?>Informe_kpi/cargarTablaKPI", true);
			xmlhttp.send(datos);
		} else {
			Swal.fire({
				title: 'Advertencia',
				text: 'Seleccione un KPI para generar el Informe',
				icon: 'warning',
				confirmButtonText: 'Ok',
			});
		}
	}


</script>