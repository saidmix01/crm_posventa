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

<!-- Modal Creación Flotas -->
<div class="modal fade" id="modalFlotas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Flotas</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<div class="form-group row">
							<select class="js-example-data-ajax" name="nit_cliente" id="nit_cliente"></select>
							<!-- <input class="col-sm-7" type="text" name="nit_cliente" id="nit_cliente" placeholder="Ingrese Nit del Cliente"> -->
							<button type="button" class="ml-4 btn btn-primary" onclick="buscarCliente();">Buscar</button>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="placa">Placa</label>
							<input type="text" class="form-control text-uppercase" id="placa" onkeyup="validarPlaca(this);" maxlength="6">
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="nombre_cliente">Nombre Cliente</label>
							<input type="text" class="form-control" id="nombre_cliente" disabled>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div id="capaAsesor" class="form-group">
							<label for="nit_cliente">Asesor</label>
						</div>
					</div>
					<div class="col-md-6">
						<div id="capaFlota" class="form-group">
							<label for="nombre_flota">Flota</label>
							<input type="text" class="form-control" id="nombre_flota">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="periodicidad_vh">Periodicidad</label>
							<input type="text" class="form-control" id="periodicidad_vh">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="guardarFlota();">Guardar</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Desvinculación Flotas -->
<div class="modal fade" id="modalVehiculosFlotas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Desvincular Vehículos de la Flota</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<div class="form-group row">
							<!-- <input class="col-sm-7" type="text" name="nit_cliente_vh" id="nit_cliente_vh" placeholder="Ingrese Nit del Cliente"> -->
							<select class="js-example-data-ajax" name="nit_cliente_vh" id="nit_cliente_vh"></select>
							<button type="button" class="ml-4 btn btn-primary" onclick="obtenerTablaVehiculosFlota();">Buscar</button>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="table-responsive">
							<table class="table table-bordered table-hover datatables" id="tabla_desvincular_vehiculos" style="font-size:14px;width:100%;">
								<thead class="thead-dark" align="center">
									<tr>
										<th scope="col">Nombre Flota</th>
										<th scope="col">Placa</th>
										<th scope="col">Observación</th>
										<th scope="col">Acción</th>
									</tr>
								</thead>
								<tbody id="vehiculosFlota" align="center">
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="guardarFlota();">Guardar</button>
			</div> -->
		</div>
	</div>
</div>

<!-- Modal Vehículos por Flota -->
<div class="modal fade" id="modalVehiculosFlotasTotal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Vehículos de la Flota</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<div class="form-group row">
							<!-- <input class="col-sm-7" type="text" name="nitCliente_vh" id="nitCliente_vh" placeholder="Ingrese Nit del Cliente"> -->
							<select class="js-example-data-ajax" name="nitCliente_vh" id="nitCliente_vh"></select>
							<button type="button" class="ml-4 btn btn-primary" onclick="obtenerTablaVehiculosFlotaTotal();">Buscar</button>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="table-responsive">
							<table class="table table-bordered table-hover datatables" id="tabla_todos_vehiculos" style="font-size:14px;width:100%;">
								<thead class="thead-dark" align="center">
									<tr>
										<th scope="col">Nombre Flota</th>
										<th scope="col">Placa</th>
										<th scope="col">Observación</th>
										<th scope="col">Asesor</th>
										<th scope="col">Comisiona</th>
										<th scope="col">Estado</th>
										<th scope="col">Acción</th>
									</tr>
								</thead>
								<tbody id="vehiculosFlotaTotal" align="center">
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="guardarFlota();">Guardar</button>
			</div> -->
		</div>
	</div>
</div>

<!-- Modal Contactos Flota -->
<div class="modal fade" id="modalContactosFlotas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Añadir Contactos de la Flota</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<div class="form-group row">
							<!-- <input class="col-sm-7" type="text" name="nitCliente_contact" id="nitCliente_contact" placeholder="Ingrese Nit del Cliente"> -->
							<select class="js-example-data-ajax" name="nitCliente_contact" id="nitCliente_contact"></select>
							<button type="button" class="ml-4 btn btn-primary" onclick="obtenerTablaFlotas();">Buscar</button>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="table-responsive">
							<table class="table table-bordered table-hover datatables" id="tabla_contactos_flotas" style="font-size:14px;width:100%;">
								<thead class="thead-dark" align="center">
									<tr>
										<th scope="col">Nombre Flota</th>
										<th scope="col">Acción</th>
									</tr>
								</thead>
								<tbody id="addContactosFlota" align="center">
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="guardarFlota();">Guardar</button>
			</div> -->
		</div>
	</div>
</div>

<!-- Modal Añadir Contactos Flota -->
<div class="modal fade" id="modalAddContactosFlotas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Añadir Contactos de la Flota</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input type="text" id="nitFlota" class="d-none">
				<input type="text" id="nombreFlota" class="d-none">
				<form class="form-inline repeater">
					<div data-repeater-list="group-a">
						<div data-repeater-item class="d-flex mb-2">
							<label class="sr-only" for="inlineFormInputGroup1">Contactos</label>
							<div class="form-group mb-2 mr-sm-2 mb-sm-0">
								<input type="text" class="form-control nombres" placeholder="Nombre">
							</div>
							<div class="form-group mb-2 mr-sm-2 mb-sm-0">
								<input type="text" class="form-control cargos" placeholder="Cargo">
							</div>
							<div class="form-group mb-2 mr-sm-2 mb-sm-0">
								<input type="email" class="form-control correos" placeholder="Correo">
							</div>
							<div class="form-group mb-2 mr-sm-2 mb-sm-0">
								<input type="number" class="form-control telefonos" placeholder="Celular">
							</div>
							<div class="form-group mb-2 mr-sm-2 mb-sm-0">
								<input type="text" class="form-control direcciones" placeholder="Dirección">
							</div>
							<button data-repeater-delete type="button" class="btn btn-danger btn-icon ml-2"><i class="fas fa-trash"></i></button>
						</div>
					</div>
					<button data-repeater-create type="button" class="btn btn-success btn-icon mb-2"><i class="fas fa-plus"></i></button>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="guardarContactosFlota();">Guardar</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Ver Flotas Aprobar -->
<div class="modal fade" id="modalFlotasAprob" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Flotas por Aprobar</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<div id="tabla_flotas_aprobar" class="table-responsive">
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="guardarFlota();">Guardar</button>
			</div> -->
		</div>
	</div>
</div>

<!-- Modal Ver Contactos Flota -->
<div class="modal fade" id="modalVerContactosFlotas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Contactos de la Flota</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<div class="table-responsive">
							<table class="table table-bordered table-hover datatables" id="tabla_ver_contactos_flotas" style="font-size:14px;width:100%;">
								<thead class="thead-dark" align="center">
									<tr>
										<th scope="col">Nit</th>
										<th scope="col">Nombre Flota</th>
										<th scope="col">Nombre Contacto</th>
										<th scope="col">Cargo</th>
										<th scope="col">Correo</th>
										<th scope="col">Celular</th>
										<th scope="col">Dirección</th>
									</tr>
								</thead>
								<tbody id="verContactosFlota" align="center">
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="guardarFlota();">Guardar</button>
			</div> -->
		</div>
	</div>
</div>

<!-- Modal Ver log estados Flota -->
<div class="modal fade" id="modalVerLogVehiculo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Log estados del Vehículo</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<div class="table-responsive">
							<table class="table table-bordered table-hover datatables" id="tabla_ver_log_vh" style="font-size:14px;width:100%;">
								<thead class="thead-dark" align="center">
									<tr>
										<th scope="col">Nit</th>
										<th scope="col">Placa</th>
										<th scope="col">Asesor</th>
										<th scope="col">Observación</th>
										<th scope="col">activo</th>
										<th scope="col">Fecha</th>
									</tr>
								</thead>
								<tbody id="verLogVehiculo" align="center">
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="guardarFlota();">Guardar</button>
			</div> -->
		</div>
	</div>
</div>

<!-- Modal Ver Flotas Aprobadas -->
<div class="modal fade" id="modalFlotasAprobadas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Flotas Aprobadas</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<div id="tabla_flotas_aprobadas" class="table-responsive">
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="guardarFlota();">Guardar</button>
			</div> -->
		</div>
	</div>
</div>

<!-- Modal Ver Flotas Rechazadas -->
<div class="modal fade" id="modalFlotasRechazadas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Flotas Rechazadas</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<div id="tabla_flotas_rechazadas" class="table-responsive">
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="guardarFlota();">Guardar</button>
			</div> -->
		</div>
	</div>
</div>

<!-- jQuery -->
<script src="<?= base_url() ?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Camvasjs  libreria para graficar -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<!-- jQuery UI -->
<script src="<?= base_url() ?>plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- Toastr -->
<script src="<?= base_url() ?>plugins/toastr/toastr.min.js"></script>
<!-- SweetAlert2 -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--table2excel -->
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!--repeater-->
<script src="<?= base_url() ?>plugins/jquery.repeater/jquery.repeater.min.js"></script>
<!--seelct2-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!--datatable-->
<script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<!--animate.css-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />


<script type="text/javascript">
	$(document).ready(function() {
		$("#buscar_items").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#menu_items li").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});

		$('.repeater').repeater({
			// (Required if there is a nested repeater)
			// Specify the configuration of the nested repeaters.
			// Nested configuration follows the same format as the base configuration,
			// supporting options "defaultValues", "show", "hide", etc.
			// Nested repeaters additionally require a "selector" field.
			repeaters: [{
				// (Required)
				// Specify the jQuery selector for this nested repeater
				selector: '.inner-repeater'
			}],
			isFirstItemUndeletable: true
		});

	});

	$('.js-example-data-ajax').select2({
		width: '80%',
		placeholder: 'Ingrese el nombre del cliente',
		minimumInputLength: 3,
		language: {
			searching: function() {
				return "Buscando...";
			},
			inputTooShort: function() {
				return 'Por favor ingrese 3 o más caracteres';
			}
		},
		ajax: {
			delay: 250,
			url: '<?= base_url() ?>Flotas/buscar_nit_combo',
			dataType: 'json',
			data: function(params) {
				var query = {
					cliente: params.term
				}
				// Query parameters will be ?search=[term]&type=public
				return query;
			},
			processResults: function(data) {
				return {
					results: data.data
				};
			},
		}
	});

	var table = $('#tabladatos').DataTable({
		"paging": true,
		"pageLength": 25,
		"lengthChange": true,
		"searching": true,
		"ordering": false,
		"info": true,
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

	// Add event listener for opening and closing details
	$('#tabladatos tbody').on('click', 'td.dt-clase', function() {
		var tr = $(this).closest('tr');
		var row = table.row(tr);

		if (row.child.isShown()) {
			// This row is already open - close it
			row.child.hide();
			tr.removeClass('shown');
		} else {
			// Open this row
			obtenerDetalle(tr, row);
		}
	});
</script>
<!-- <script>
	//funcion para activar busqueda
	function buscar() {
		var mes = document.getElementById('mes').value;
		var sede = document.getElementById('sede').value;
		var tabla = document.getElementById('filtro');

		var url = '<?= base_url() ?>Informes/buscar_nps';
		var datos = new FormData();
		datos.append('mes', mes);
		datos.append('sede', sede)
		var request = new XMLHttpRequest();
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				tabla.innerHTML = request.responseText;
				document.getElementById('tabladatos').style.display = 'none';
				document.getElementById('btn-filtro').style.display = 'none';
			}
		}
		request.open("POST", url);
		request.send(datos);
	}
</script> -->


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
	}, 1500); //soy un comentario
</script>


<script>
	function aplicarPopoer() {
		//$(document).ready(function() {
		$('[data-toggle="popover"]').popover();
		//});
	}

	function validarPlaca(e) {
		var val = $(e).val().replace(/[^A-Za-z0-9]/g, '');
		$(e).val(val);
	}

	function validarPeriodicidad(input) {
		var val = $(input).val() > 0 && $(input).val() <= 12 ? $(input).val() : 0;
		$(input).val(val);
	}
</script>

<script>
	function obtenerDetalle(tr, row) {
		$('.loader').css('display','block');
		var http;
		if (window.XMLHttpRequest) {
			http = new XMLHttpRequest();
		} else {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var url = '<?= base_url() ?>Flotas/load_tabla_flotas_detallada';
		var params = 'nit_cliente=' + tr[0].id;
		http.open('POST', url, true);

		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		http.responseType = 'text';
		http.onreadystatechange = function() { //Call a function when the state changes.
			if (http.readyState == 4 && http.status == 200) {
				$('.loader').css('display','none');
				var resp = http.response;
				row.child(resp).show();
				tr.addClass('shown');
				$('.js-example-basic-single').select2();
			}
		}
		http.send(params);
	}

	function asignarFlota(nitCliente, placa) {
		var nombreFlota = $('#nombreFlota_' + placa).val();
		var asesor = $('#combo_' + placa).val();
		var periodicidad = $('#periodicidad_' + placa).val();

		if (nombreFlota == '' || asesor == '' || periodicidad == '') {
			Swal.fire({
				title: 'Advertencia!',
				text: 'Por favor ingrese los campos requeridos: nombre flota, asesor y periodicidad',
				icon: 'error',
				confirmButtonText: 'Ok'
			});
		} else {
			var http;
			if (window.XMLHttpRequest) {
				http = new XMLHttpRequest();
			} else {
				http = new ActiveXObject("Microsoft.XMLHTTP");
			}
			var url = '<?= base_url() ?>Flotas/asignar_flota';
			var params = 'nitCliente=' + nitCliente + '&placa=' + placa.toUpperCase() + '&nombreFlota=' + nombreFlota + '&asesor=' + asesor + '&periodicidad=' + periodicidad;
			http.open('POST', url, true);

			//Send the proper header information along with the request
			http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			// http.responseType = 'json';
			http.onreadystatechange = function() { //Call a function when the state changes.
				if (http.readyState == 4 && http.status == 200) {
					var resp = http.response;
					// console.log(resp);
					if (resp == 1) {
						Swal.fire({
							title: 'Success!',
							text: 'Las flota se asignó correctamente',
							icon: 'success',
							confirmButtonText: 'Ok'
						}).then((result) => {
							if (result.isConfirmed) {
								// location.reload();
								var fila = $("#asignar_" + placa).parents('tr').last().prev();
								var row = table.row(fila);
								obtenerDetalle(fila, row);
							}
						});
					} else {
						Swal.fire({
							title: 'Error!',
							text: 'No se asignaron las flotas',
							icon: 'error',
							confirmButtonText: 'Ok'
						});
					}
				}
			}
			http.send(params);
		}
	}

	function buscarCliente() {
		var nitCliente = $('#nit_cliente').val();

		if (nitCliente == '') {
			Swal.fire({
				title: 'Advertncia!',
				text: 'Ingrese una placa para realizar la busqueda',
				icon: 'warning',
				confirmButtonText: 'Ok'
			});
		} else {
			var http;
			if (window.XMLHttpRequest) {
				http = new XMLHttpRequest();
			} else {
				http = new ActiveXObject("Microsoft.XMLHTTP");
			}
			var url = '<?= base_url() ?>Flotas/consultar_cliente';
			var params = 'nitCliente=' + nitCliente;
			http.open('POST', url, true);

			//Send the proper header information along with the request
			http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			http.responseType = 'json';
			http.onreadystatechange = function() { //Call a function when the state changes.
				if (http.readyState == 4 && http.status == 200) {
					var resp = http.response;
					console.log(resp);

					if (resp[0] == 1) {
						$('#nombre_cliente').val(resp[1]);
						if (resp[3] == 1) {
							obtenerComboFlotas(nitCliente);
						} else {
							var label = '<label for="nombre_flota">Flota</label>';
							var input = '<input type="text" class="form-control" id="nombre_flota">';
							document.getElementById('capaFlota').innerHTML = label + input;
							$('#nombre_flota').val(resp[3]);
							$('#combo_asesor').val(resp[2]);
							$('#combo_asesor').select2().trigger('change');
							$('.js-example-basic-single').select2({
								tags: true,
								width: '100%',
								placeholder: "Seleccione un asesor",
								dropdownParent: $('#modalFlotas')
							});
						}
					} else if (resp[0] == 2) {
						$('#nombre_cliente').val(resp[1]);
						Swal.fire({
							title: 'Advertencia!',
							text: 'No hay flota asignada a este cliente',
							icon: 'warning',
							confirmButtonText: 'Ok'
						});
					} else {
						Swal.fire({
							title: 'Error!',
							text: 'El Nit ingresado no existe',
							icon: 'error',
							confirmButtonText: 'Ok'
						});
					}
				}
			}
			http.send(params);
		}
	}

	function obtenerComboAsesor() {
		var http;
		if (window.XMLHttpRequest) {
			http = new XMLHttpRequest();
		} else {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var url = '<?= base_url() ?>Flotas/load_combo_asesores';
		http.open('POST', url, true);

		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		// http.responseType = 'json';
		http.onreadystatechange = function() { //Call a function when the state changes.
			if (http.readyState == 4 && http.status == 200) {
				var resp = http.response;
				// console.log(resp);
				var label = '<label for="combo_asesor">Asesor</label>';
				document.getElementById('capaAsesor').innerHTML = label + resp;
				$('.js-example-basic-single').select2({
					tags: true,
					width: '100%',
					placeholder: "Seleccione un asesor",
					dropdownParent: $('#modalFlotas')
				});
			}
		}
		http.send();
	}

	function obtenerComboFlotas(nitCliente) {
		var http;
		if (window.XMLHttpRequest) {
			http = new XMLHttpRequest();
		} else {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var url = '<?= base_url() ?>Flotas/load_combo_flotas';
		var params = 'nitCliente=' + nitCliente;
		http.open('POST', url, true);

		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		// http.responseType = 'json';
		http.onreadystatechange = function() { //Call a function when the state changes.
			if (http.readyState == 4 && http.status == 200) {
				var resp = http.response;
				// console.log(resp);
				var label = '<label for="nombre_flota">Flota</label>';
				document.getElementById('capaFlota').innerHTML = label + resp;
				$('.js-example-basic-single-flota').select2({
					tags: true,
					width: '100%',
					placeholder: "Seleccione una flota",
					dropdownParent: $('#modalFlotas')
				});
			}
		}
		http.send(params);
	}

	function guardarFlota() {
		var placa = $('#placa').val();
		var nitCliente = $('#nit_cliente').val();
		var nombreFlota = $('#nombre_flota').prop("tagName") == 'INPUT' ? $('#nombre_flota').val() : $("#nombre_flota option:selected").text();
		var asesor = $('#combo_asesor').val();
		var periodicidad = $('#periodicidad_vh').val();

		if (placa == '' || nitCliente == '' || nombreFlota == '' || asesor == '' || periodicidad == '') {
			Swal.fire({
				title: 'Advertncia!',
				text: 'Faltan campos por llenar',
				icon: 'warning',
				confirmButtonText: 'Ok'
			});
		} else {
			var http;
			if (window.XMLHttpRequest) {
				http = new XMLHttpRequest();
			} else {
				http = new ActiveXObject("Microsoft.XMLHTTP");
			}
			var url = '<?= base_url() ?>Flotas/guardar_flota';
			var params = 'placa=' + placa + '&nitCliente=' + nitCliente + '&nombreFlota=' + nombreFlota + '&asesor=' + asesor + '&periodicidad=' + periodicidad;
			http.open('POST', url, true);

			//Send the proper header information along with the request
			http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			// http.responseType = 'json';
			http.onreadystatechange = function() { //Call a function when the state changes.
				if (http.readyState == 4 && http.status == 200) {
					var resp = http.response;
					// console.log(resp);
					if (resp == 1) {
						Swal.fire({
							title: 'Excelente!',
							text: 'Las flotas se asignaron correctamente',
							icon: 'success',
							confirmButtonText: 'Ok'
						}).then((result) => {
							if (result.isConfirmed) {
								location.reload();
							}
						});
					} else if (resp == 2) {
						Swal.fire({
							title: 'Advertncia!',
							text: 'La placa ingresada ya pertenece a esta flota',
							icon: 'warning',
							confirmButtonText: 'Ok'
						});
					} else {
						Swal.fire({
							title: 'Error!',
							text: 'No se guardó la flota',
							icon: 'error',
							confirmButtonText: 'Ok'
						});
					}
				}
			}
			http.send(params);
		}
	}

	function actualizarAsesor() {
		var asesor = $('#nombre_flota').val();
		$('#combo_asesor').val(asesor);
		$('#combo_asesor').select2().trigger('change');
		$('.js-example-basic-single').select2({
			tags: true,
			width: '100%',
			placeholder: "Seleccione un asesor",
			dropdownParent: $('#modalFlotas')
		});
	}

	function obtenerTablaVehiculosFlota() {
		document.getElementById('vehiculosFlota').innerHTML = 'Cargando...';
		var nitCliente = $('#nit_cliente_vh').val();
		if (nitCliente == '') {
			Swal.fire({
				title: 'Advertencia!',
				text: 'Por favor, ingrese el NIT del cliente',
				icon: 'warning',
				confirmButtonText: 'Ok'
			});
		} else {
			var http;
			if (window.XMLHttpRequest) {
				http = new XMLHttpRequest();
			} else {
				http = new ActiveXObject("Microsoft.XMLHTTP");
			}
			var url = '<?= base_url() ?>Flotas/load_tabla_vehiculos_flotas';
			var params = 'nit_cliente=' + nitCliente;
			http.open('POST', url, true);

			//Send the proper header information along with the request
			http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			// http.responseType = 'json';
			http.onreadystatechange = function() { //Call a function when the state changes.
				if (http.readyState == 4 && http.status == 200) {
					var resp = http.response;
					// console.log(resp);
					document.getElementById('vehiculosFlota').innerHTML = resp;
					aplicarDataTable('tabla_desvincular_vehiculos');
				}
			}
			http.send(params);
		}
	}

	function obtenerTablaVehiculosFlotaTotal() {
		document.getElementById('vehiculosFlotaTotal').innerHTML = 'Cargando...';
		var nitCliente = $('#nitCliente_vh').val();
		if (nitCliente == '') {
			Swal.fire({
				title: 'Advertencia!',
				text: 'Por favor, ingrese el NIT del cliente',
				icon: 'warning',
				confirmButtonText: 'Ok'
			});
		} else {
			var http;
			if (window.XMLHttpRequest) {
				http = new XMLHttpRequest();
			} else {
				http = new ActiveXObject("Microsoft.XMLHTTP");
			}
			var url = '<?= base_url() ?>Flotas/load_tabla_vehiculos_flotas_total';
			var params = 'nit_cliente=' + nitCliente;
			http.open('POST', url, true);

			//Send the proper header information along with the request
			http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			// http.responseType = 'json';
			http.onreadystatechange = function() { //Call a function when the state changes.
				if (http.readyState == 4 && http.status == 200) {
					var resp = http.response;
					// console.log(resp);
					document.getElementById('vehiculosFlotaTotal').innerHTML = resp;
					aplicarDataTable('tabla_todos_vehiculos');
				}
			}
			http.send(params);
		}
	}

	function obtenerTablaFlotas() {
		document.getElementById('addContactosFlota').innerHTML = 'Cargando...';
		var nitCliente = $('#nitCliente_contact').val();
		if (nitCliente == '') {
			Swal.fire({
				title: 'Advertencia!',
				text: 'Por favor, ingrese el NIT del cliente',
				icon: 'warning',
				confirmButtonText: 'Ok'
			});
		} else {
			var http;
			if (window.XMLHttpRequest) {
				http = new XMLHttpRequest();
			} else {
				http = new ActiveXObject("Microsoft.XMLHTTP");
			}
			var url = '<?= base_url() ?>Flotas/load_tabla_flotas';
			var params = 'nit_cliente=' + nitCliente;
			http.open('POST', url, true);

			//Send the proper header information along with the request
			http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			// http.responseType = 'json';
			http.onreadystatechange = function() { //Call a function when the state changes.
				if (http.readyState == 4 && http.status == 200) {
					var resp = http.response;
					// console.log(resp);
					document.getElementById('addContactosFlota').innerHTML = resp;
					aplicarDataTable('tabla_contactos_flotas');
				}
			}
			http.send(params);
		}
	}

	function asignarDataFlota(nit, nombre_flota) {
		$('#nitFlota').val(nit);
		$('#nombreFlota').val(nombre_flota);
	}

	function guardarContactosFlota() {
		var validacion = $("[data-repeater-item] input").map(function() {
			if (this.value != '') return this.value;
		}).get().length;

		if (validacion % 5 != 0 || validacion == 0) {
			Swal.fire({
				title: 'Advertencia!',
				text: 'Por favor, complete todos los campos',
				icon: 'warning',
				confirmButtonText: 'Ok'
			});
		} else {
			var nitFlota = $('#nitFlota').val();
			var flota = $('#nombreFlota').val();
			var nombres = $(".nombres").map(function() {
				return this.value;
			}).get().join('_');
			var cargos = $(".cargos").map(function() {
				return this.value;
			}).get().join('_');
			var correos = $(".correos").map(function() {
				return this.value;
			}).get().join('_');
			var telefonos = $(".telefonos").map(function() {
				return this.value;
			}).get().join('_');
			var direcciones = $(".direcciones").map(function() {
				return this.value;
			}).get().join('_');
			var http;
			if (window.XMLHttpRequest) {
				http = new XMLHttpRequest();
			} else {
				http = new ActiveXObject("Microsoft.XMLHTTP");
			}
			var url = '<?= base_url() ?>Flotas/guardar_contactos_flota';
			var params = 'nitFlota=' + nitFlota + '&flota=' + flota + '&nombres=' + nombres + '&cargos=' + cargos + '&correos=' + correos + '&telefonos=' + telefonos + '&direcciones=' + direcciones;
			http.open('POST', url, true);

			//Send the proper header information along with the request
			http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			// http.responseType = 'json';
			http.onreadystatechange = function() { //Call a function when the state changes.
				if (http.readyState == 4 && http.status == 200) {
					var resp = http.response;
					// console.log(resp);
					if (resp == 1) {
						Swal.fire({
							title: 'Success!',
							text: 'Los contactos se han creado correctamente',
							icon: 'success',
							confirmButtonText: 'Ok'
						});
					} else {
						Swal.fire({
							title: 'Ooops!',
							text: 'Los contactos no se han creado',
							icon: 'error',
							confirmButtonText: 'Ok'
						});
					}
				}
			}
			http.send(params);
		}
	}

	function obtenerContactosFlota(nit, flota) {
		document.getElementById('verContactosFlota').innerHTML = 'Cargando...';
		var http;
		if (window.XMLHttpRequest) {
			http = new XMLHttpRequest();
		} else {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var url = '<?= base_url() ?>Flotas/load_tabla_contactos_flota';
		var params = 'nit=' + nit + '&flota=' + flota;
		http.open('POST', url, true);

		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		// http.responseType = 'json';
		http.onreadystatechange = function() { //Call a function when the state changes.
			if (http.readyState == 4 && http.status == 200) {
				var resp = http.response;
				// console.log(resp);
				document.getElementById('verContactosFlota').innerHTML = resp;
			}
		}
		http.send(params);
	}

	function obtenerLogVehiculo(nit, placa) {
		document.getElementById('verLogVehiculo').innerHTML = 'Cargando...';
		var http;
		if (window.XMLHttpRequest) {
			http = new XMLHttpRequest();
		} else {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var url = '<?= base_url() ?>Flotas/load_tabla_log_estados_vh';
		var params = 'nit=' + nit + '&placa=' + placa;
		http.open('POST', url, true);

		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		// http.responseType = 'json';
		http.onreadystatechange = function() { //Call a function when the state changes.
			if (http.readyState == 4 && http.status == 200) {
				var resp = http.response;
				// console.log(resp);
				document.getElementById('verLogVehiculo').innerHTML = resp;
			}
		}
		http.send(params);
	}

	function desvincularVehiculo(idFlota, placa) {
		var observacion = $('#combo_obs_' + placa).val() == 'Otro' ? $('#observacion_' + placa).val() : $('#combo_obs_' + placa).val();
		var http;
		if (window.XMLHttpRequest) {
			http = new XMLHttpRequest();
		} else {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var url = '<?= base_url() ?>Flotas/desvincular_vehiculo_flota';
		var params = 'idFlota=' + idFlota + '&observacion=' + observacion;
		http.open('POST', url, true);

		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		// http.responseType = 'json';
		http.onreadystatechange = function() { //Call a function when the state changes.
			if (http.readyState == 4 && http.status == 200) {
				var resp = http.response;
				// console.log(resp);
				if (resp == 1) {
					Swal.fire({
						title: 'Success!',
						text: 'El vehículo se desviculó correctamente',
						icon: 'success',
						confirmButtonText: 'Ok'
					}).then((result) => {
						if (result.isConfirmed) {
							obtenerTablaVehiculosFlota();
						}
					});
				} else {
					Swal.fire({
						title: 'Error!',
						text: 'El vehículo no se desviculó',
						icon: 'error',
						confirmButtonText: 'Ok'
					});
				}
			}
		}
		http.send(params);
	}

	function desactivarVehiculo(nitCliente, placa) {
		var nombreFlota = $('#nombreFlota_' + placa).val();
		var asesor = $('#combo_' + placa).val();

		var params = '?nitCliente=' + nitCliente + '&placa=' + placa + '&nombreFlota=' + nombreFlota + '&asesor=' + asesor + '&observacion=';
		var url = '<?= base_url() ?>Flotas/desactivar_vh_flota' + params;
		if (nombreFlota == '' && asesor == '') {
			Swal.fire({
				title: 'Advertencia!',
				text: 'Por favor, ingrese un nombre a la flota y seleccione un asesor',
				icon: 'warning',
				confirmButtonText: 'Ok'
			});
		} else {
			Swal.fire({
				title: 'Por favor escriba el motivo por el cual se desactivará el vehículo',
				input: 'select',
				inputOptions: {
					'Perdida Total': 'Perdida Total',
					'Vendido': 'Vendido',
					'Cambio de Asesor': 'Cambio de Asesor',
					'Otro': 'Otro'
				},
				inputAttributes: {
					autocapitalize: 'off',
					onchange: 'val_observacion(1);'
				},
				inputPlaceholder: 'Seleccione una opción',
				showCancelButton: false,
				confirmButtonText: 'Desactivar',
				showLoaderOnConfirm: true,
				preConfirm: (input) => {
					if (input == '') {
						Swal.showValidationMessage(
							`Por favor seleccione una opción`
						)
					} else {
						var observacion = input == 'Otro' ? $('.swal2-textarea').val() : input;
						return fetch(url + observacion)
							.then(response => {
								if (!response.ok) {
									throw new Error(response.statusText)
								}
								return response.json()
							})
							.catch(error => {
								Swal.showValidationMessage(
									`Request failed: ${error}`
								)
							})
					}
				},
				allowOutsideClick: () => !Swal.isLoading()
			}).then((result) => {
				if (result.isConfirmed) {
					console.log(result);
					if (result.value == 1) {
						Swal.fire({
							title: 'Success!',
							text: 'El vehículo se desactivó correctamente',
							icon: 'success',
							confirmButtonText: 'Ok'
						}).then((result) => {
							if (result.isConfirmed) {
								var fila = $("#asignar_" + placa).parents('tr').last().prev();
								var row = table.row(fila);
								obtenerDetalle(fila, row);
							}
						});
					} else {
						Swal.fire({
							title: 'Error!',
							text: 'El vehículo no se desactivó',
							icon: 'error',
							confirmButtonText: 'Ok'
						});
					}
				}
			});
		}
	}

	function val_observacion(op, placa = '') {
		if (op == 1) {
			var opcion = $('.swal2-select').val();
			if (opcion == 'Otro') {
				$('.swal2-textarea').css('display', 'block');
			} else {
				$('.swal2-textarea').css('display', 'none');
			}
		} else {
			var opcion = $('#combo_obs_' + placa).val();
			if (opcion == 'Otro') {
				$('#observacion_' + placa).removeClass('d-none');
			} else {
				$('#observacion_' + placa).addClass('d-none');
			}
		}
	}

	function obtenerFlotasAprobar() {
		document.getElementById('tabla_flotas_aprobar').innerHTML = 'Cargando...';
		var http;
		if (window.XMLHttpRequest) {
			http = new XMLHttpRequest();
		} else {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var url = '<?= base_url() ?>Flotas/load_tabla_flotas_aprobar';
		http.open('POST', url, true);

		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		// http.responseType = 'json';
		http.onreadystatechange = function() { //Call a function when the state changes.
			if (http.readyState == 4 && http.status == 200) {
				var resp = http.response;
				// console.log(resp);
				document.getElementById('tabla_flotas_aprobar').innerHTML = resp;
				aplicarDataTable("tabla_flotas_aprob");
				$('.js-example-basic-single').select2();
			}
		}
		http.send();
	}

	function obtenerFlotasAprobadas() {
		document.getElementById('tabla_flotas_aprobadas').innerHTML = 'Cargando...';
		var http;
		if (window.XMLHttpRequest) {
			http = new XMLHttpRequest();
		} else {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var url = '<?= base_url() ?>Flotas/load_tabla_flotas_aprobadas';
		http.open('POST', url, true);

		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		// http.responseType = 'json';
		http.onreadystatechange = function() { //Call a function when the state changes.
			if (http.readyState == 4 && http.status == 200) {
				var resp = http.response;
				// console.log(resp);
				document.getElementById('tabla_flotas_aprobadas').innerHTML = resp;
				aplicarDataTable("table_flotas_aprobadas");
			}
		}
		http.send();
	}

	function obtenerFlotasRechazadas() {
		document.getElementById('tabla_flotas_rechazadas').innerHTML = 'Cargando...';
		var http;
		if (window.XMLHttpRequest) {
			http = new XMLHttpRequest();
		} else {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var url = '<?= base_url() ?>Flotas/load_tabla_flotas_rechazadas';
		http.open('POST', url, true);

		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		// http.responseType = 'json';
		http.onreadystatechange = function() { //Call a function when the state changes.
			if (http.readyState == 4 && http.status == 200) {
				var resp = http.response;
				// console.log(resp);
				document.getElementById('tabla_flotas_rechazadas').innerHTML = resp;
				aplicarDataTable("table_flotas_rechazadas");
			}
		}
		http.send();
	}

	function aprobarFlota(nitFlota, idsFlotas, opUsuario) {
		/* var asesor = $('#combo_' + idFlota).val();
		var comisiona = $('#check_' + idFlota).is(':checked') ? 1 : 0; */

		var params = 'idsFlotas=' + idsFlotas;

		if (opUsuario == 1) {			
			var comisiones = $(".aprob_check_" + nitFlota).map(function() {
				return $(this).is(':checked') ? 1 : 0;
			}).get().join('_');
			
			params = params + '&comisiones=' + comisiones;
		} else {
			var asesores = $(".aprob_combo_" + nitFlota).map(function() {
				return this.value;
			}).get().join('_');
			
			params = params + '&asesores=' + asesores;
		}

		var http;
		if (window.XMLHttpRequest) {
			http = new XMLHttpRequest();
		} else {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var url = '<?= base_url() ?>Flotas/aprobar_flota';
		http.open('POST', url, true);

		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		// http.responseType = 'json';
		http.onreadystatechange = function() { //Call a function when the state changes.
			if (http.readyState == 4 && http.status == 200) {
				var resp = http.response;
				// console.log(resp);
				if (resp == 1) {
					Swal.fire({
						title: 'Success!',
						text: 'La flota se asignó correctamente',
						icon: 'success',
						confirmButtonText: 'Ok'
					}).then((result) => {
						if (result.isConfirmed) {
							obtenerFlotasAprobar();
						}
					});
				} else {
					Swal.fire({
						title: 'Error!',
						text: 'Hubo un error al asignar la flota',
						icon: 'error',
						confirmButtonText: 'Ok'
					});
				}
			}
		}
		http.send(params);
	}

	function rechazarFlota(idsFlotas) {
		var params = 'idsFlotas=' + idsFlotas;

		var http;
		if (window.XMLHttpRequest) {
			http = new XMLHttpRequest();
		} else {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var url = '<?= base_url() ?>Flotas/rechazar_flota';
		http.open('POST', url, true);

		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		// http.responseType = 'json';
		http.onreadystatechange = function() { //Call a function when the state changes.
			if (http.readyState == 4 && http.status == 200) {
				var resp = http.response;
				// console.log(resp);
				if (resp == 1) {
					Swal.fire({
						title: 'Success!',
						text: 'La flota se rechazó correctamente',
						icon: 'success',
						confirmButtonText: 'Ok'
					}).then((result) => {
						if (result.isConfirmed) {
							obtenerFlotasAprobar();
						}
					});
				} else {
					Swal.fire({
						title: 'Error!',
						text: 'Hubo un error al rechazar la flota',
						icon: 'error',
						confirmButtonText: 'Ok'
					});
				}
			}
		}
		http.send(params);
	}

	function fijarComision(nitFlota, idFlota) {
		var comisiona = $('#com_check_' + idFlota).is(':checked') ? 1 : 0;
		var obs_comision = $('#com_obs_' + idFlota).val();

		var http;
		if (window.XMLHttpRequest) {
			http = new XMLHttpRequest();
		} else {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var url = '<?= base_url() ?>Flotas/registrar_comision';
		http.open('POST', url, true);
		var params = 'idFlota=' + idFlota + '&comisiona=' + comisiona + '&observacion=' + obs_comision;
		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		// http.responseType = 'json';
		http.onreadystatechange = function() { //Call a function when the state changes.
			if (http.readyState == 4 && http.status == 200) {
				var resp = http.response;
				// console.log(resp);
				if (resp == 1) {
					Swal.fire({
						title: 'Success!',
						text: 'La comisión se asignó correctamente',
						icon: 'success',
						confirmButtonText: 'Ok',
						allowOutsideClick: false
					}).then((result) => {
						if (result.isConfirmed) {
							window.location.reload();
						}
					});
				} else {
					Swal.fire({
						title: 'Error!',
						text: 'Hubo un error al asignar la comisión',
						icon: 'error',
						confirmButtonText: 'Ok'
					});
				}
			}
		}
		http.send(params);
	}

	function aplicarDataTable(id) {
		$('#' + id).DataTable({
			"scrollY": '50vh',
			"retrieve": true,
			"scrollCollapse": true,
			"paging": false,
			// "pageLength": 5,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": true,
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
	}



	$('#modalVehiculosFlotas').on('hidden.bs.modal', function(event) {
		$('#nit_cliente_vh').val('');
		$('#tabla_desvincular_vehiculos').DataTable().clear().destroy();
	});
	$('#modalVehiculosFlotasTotal').on('hidden.bs.modal', function(event) {
		$('#nitCliente_vh').val('');
		$('#tabla_todos_vehiculos').DataTable().clear().destroy();
	});
	$('#modalContactosFlotas').on('hidden.bs.modal', function(event) {
		$('#nitCliente_contact').val('');
		$('#tabla_contactos_flotas').DataTable().clear().destroy();
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