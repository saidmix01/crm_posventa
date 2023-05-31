<?php $this->load->view('administracion/header') ?>
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content">
		<div class="loader2" id="cargando"></div>
		<div class="card">
			<div class="card-header" align="center">
				<h4>Gestión de ausentismos</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-md-4 col-sm-6">
						<label>Empleado</label>
						<select class="form-control js-example-basic-single" id="nitEmpleado" name="nitEmpleado">
							<option value=""></option>
							<?php foreach ($allUser->result() as $key) { ?>
								<option value="<?= $key->nit ?>"><?= $key->nombres ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-md-4 col-sm-4">
						<label>Por día:</label>
						<input class="form-control" type="date" name="filtroDia" id="filtroDia" value="<?= date('Y-m-d') ?>" onchange="javascript:document.getElementById('filtroMes').value = '';">
					</div>
					<div class="col-12 col-md-4 col-sm-4">
						<label>Por Mes:</label>
						<input class="form-control" type="month" name="filtroMes" id="filtroMes" value="" onchange="javascript:document.getElementById('filtroDia').value = '';">
					</div>
					<div class="col-12 col-md-4 col-sm-2" style="align-self: flex-end ;">
						<button class="btn btn-success" name="btnBuscar" id="btnBuscar" onclick="buscarAusentismo();">Buscar</button>
					</div>
				</div>
				<hr>
				<div class="row mt-2">
					<div class="table-responsive">
						<table class="table table-bordered text-center" id="ExcelPausasActivas">
							<thead>
								<tr>
									<th scope="col">DOCUMENTO</th>
									<th scope="col">NOMBRE</th>
									<th scope="col">SEDE</th>
									<th scope="col">AREA</th>
									<th scope="col">DESCRIPCIÓN</th>
									<th scope="col">FECHA INICIO</th>
									<th scope="col">FECHA FINAL</th>
									<th class="text-center" scope="col">EDITAR</th>
									<th class="text-center" scope="col">ELIMINAR</th>
								</tr>
							</thead>
							<tbody id="bodyTable">

							</tbody>
						</table>
					</div>
				</div>

			</div>

			<div class="card-footer">
				<div class="row">

				</div>
			</div>
			<!-- /.card-body -->
		</div>
	</section>
	<!-- /.content -->
</div>
<!-- Modal ver ausentismo-->
<div class="modal fade" id="modal_au_ver" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Ver Ausentismo</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST" action="<?= base_url() ?>Administracion/editAusen">
				<div class="modal-body" id="load_event">

				</div>
				<div class="modal-footer" id="load_event">
					<button class="btn btn-warning" type="submit">Guardar</button>
				</div>
			</form>

		</div>
	</div>
</div>

<!-- jQuery -->
<script src="<?= base_url() ?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
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
<script src="<?= base_url() ?>plugins/sweetalert2/sweetalert2.min.js"></script>
<!--table2excel -->
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url() ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<script>
	$(document).ready(function() {
		let opc = 1;
		buscarAusentismo(opc);

		$(".js-example-basic-single").select2({
			theme: "classic",
			placeholder: "Seleccione un empleado"
		});
		cargarDataTable();

	});

	function notificacion() {
		<?php
		if (isset($_GET["log"])) {
			$var = $_GET["log"];
		} else {
			$var = 2;
		}
		?>
		let msm = <?php echo $var ?>;
		console.log('Mensaje de error: ' + msm);
		switch (msm) {
			case 0:
				Swal.fire({
					title: 'Advertencia!',
					text: 'No se ha realizado el cambio al ausentismo',
					icon: 'warning',
					confirmButtonText: 'Cerrar'
				});
				break;
			case 1:
				Swal.fire({
					title: 'Advertencia!',
					text: 'Ausentismo modificado correctamente.',
					icon: 'success',
					confirmButtonText: 'Cerrar'
				});
				break;
			case 2:
				break;
		}


	}

	function ver_evento(id) {
		var result = document.getElementById("load_event");
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				$('#modal_au_ver').modal('show');
				result.innerHTML = xmlhttp.responseText;

			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>Administracion/ver_info_evento?id=" + id, true);
		xmlhttp.send();
	}

	function eliminarAusentismo(id) {
		document.getElementById("cargando").style.display = "block";

		var url = '<?= base_url() ?>Administracion/deleteAusentismo';
		//definir el id del formulario para recoger los datos
		var datos = new FormData();
		datos.append('id_ausen', id);
		var request;
		if (window.XMLHttpRequest) {
			request = new XMLHttpRequest();
		} else {
			request = new ActiveXObject("Microsoft.XMLHTTP");
		}
		//crear objeto de la clase XMLHttpRequest
		//validar respuesta de la peticion HTTP
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				//pinta datos en la vista por medio de innerhtml 
				let result = request.responseText;
				
				switch (result) {
					case '0':
						Swal.fire({
							title: 'Advertencia!',
							text: 'No se ha eliminado el ausentismo',
							icon: 'warning',
							confirmButtonText: 'Cerrar'
						});
						break;
					case '1':
						Swal.fire({
							title: 'Advertencia!',
							text: 'Ausentismo eliminado correctamente.',
							icon: 'success',
							confirmButtonText: 'Cerrar'
						});
						break;
					default:
						Swal.fire({
							title: 'Advertencia!',
							text: 'Ha ocurrido un error inesperado.',
							icon: 'warning',
							confirmButtonText: 'Cerrar'
						});
						break;
				}
				document.getElementById("cargando").style.display = "none";

			}
		}
		//definiendo metodo y ruta

		request.open("POST", url, false);
		request.send(datos);
	}



	function buscarAusentismo(opc) {
		document.getElementById("cargando").style.display = "block";

		var empleado = document.getElementById('nitEmpleado').value;
		var fechaDia = document.getElementById('filtroDia').value;
		var fechaMes = document.getElementById('filtroMes').value;


		var url = '<?= base_url() ?>Administracion/getListAusentismos';
		//definir el id del formulario para recoger los datos
		var datos = new FormData();
		datos.append('empleado', empleado);
		datos.append('fechaDia', fechaDia);
		datos.append('fechaMes', fechaMes);


		var request;
		if (window.XMLHttpRequest) {
			request = new XMLHttpRequest();
		} else {
			request = new ActiveXObject("Microsoft.XMLHTTP");
		}
		//crear objeto de la clase XMLHttpRequest
		//validar respuesta de la peticion HTTP
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				//pinta datos en la vista por medio de innerhtml
				
				if (opc == undefined) {
					$('#ExcelPausasActivas').dataTable().fnDestroy();
					document.getElementById('bodyTable').innerHTML = request.responseText;
					cargarDataTable();
				}else {
					document.getElementById('bodyTable').innerHTML = request.responseText;
				}
				document.getElementById("cargando").style.display = "none";
				notificacion();

			}
		}
		//definiendo metodo y ruta
		request.open("POST", url, false);
		request.send(datos);
	}

	function cargarDataTable() {
		$('#ExcelPausasActivas').DataTable({
			"paging": true,
			"pageLength": Infinity,
			"lengthChange": true,
			"searching": true,
			"ordering": true,
			"order": [],
			"info": true,
			"autoWidth": true,
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
</script>