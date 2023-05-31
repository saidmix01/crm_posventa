<?php $this->load->view('administracion/header.php') ?>
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content">
		<div class="loader2" id="cargando"></div>
		<div class="card">
			<div class="card-header" align="center">
				<h4>Informe pausas activas</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-md-4 col-sm-6">
						<label>Empleado</label>
						<select class="form-control js-example-basic-single" id="nitEmpleado" name="nitEmpleado">
							<option value="">Seleccione un empleado...</option>
							<?php foreach ($allUser->result() as $key) { ?>
								<option value="<?= $key->nit ?>"><?= $key->nombres ?></option>
							<?php } ?>
						</select>
					</div>

					<div class="col-12 col-md-4 col-sm-6">
						<label>Sede</label>
						<select class="form-control js-example-basic-single" id="sede" name="sede">
							<option value="">Seleccione una sede</option>
							<option value="Barrancabermeja">Barrancabermeja</option>
							<option value="Bocono">Bocono</option>
							<option value="Chevropartes">Chevropartes</option>
							<option value="Giron">Giron</option>
							<option value="Malecon">Malecon</option>
							<option value="Rosita">Rosita</option>
							<option value="Solochevrolet">Solochevrolet</option>
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
						<button class="btn btn-success" name="btnBuscar" id="btnBuscar" onclick="filtroPausasActivas();">Buscar</button>
						<button class="btn btn-warning" name="btnBuscar" id="btnBuscar" onclick="descargarPausasActivas();">Excel</button>
					</div>
				</div>
				<hr>
				<div class="row mt-2">
					<div class="table-responsive">
						<table class="table table-bordered text-center" id="ExcelPausasActivas">
							<thead>
								<th scope="col" width="15%">DOCUMENTO</th>
								<th scope="col" width="40%" >NOMBRE</th>
								<th scope="col" width="15%">SEDE</th>
								<th scope="col" width="15%">AM</th>
								<th scope="col" width="15%">PM</th>
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
<?php $this->load->view('administracion/footer.php') ?>

<script>
	$(document).ready(function() {
		filtroPausasActivas();
	});

	function filtroPausasActivas() {
		document.getElementById("cargando").style.display = "block";

		var empleado = document.getElementById('nitEmpleado').value;
		var sede = document.getElementById('sede').value;
		var fechaDia = document.getElementById('filtroDia').value;
		var fechaMes = document.getElementById('filtroMes').value;


		var url = '<?= base_url() ?>Administracion/info_pausas_activas';
		//definir el id del formulario para recoger los datos
		var datos = new FormData();
		datos.append('empleado', empleado);
		datos.append('sede', sede);
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
				$('#ExcelPausasActivas').dataTable().fnDestroy();
				document.getElementById('bodyTable').innerHTML = request.responseText;
				CargarDatatable();
			}
		}
		//definiendo metodo y ruta

		request.open("POST", url);
		request.send(datos);


	}

	function CargarDatatable() {
		$('#ExcelPausasActivas').DataTable({
			"paging": false,
			"pageLength": Infinity,
			"lengthChange": true,
			"searching": true,
			"ordering":  true,
			"order": [],
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
		document.getElementById("cargando").style.display = "none";
	}

	function descargarPausasActivas() {
		//funcion para convetir tabla en documento de excel
		var f = new Date();
		fecha = f.getDate() + "-" + (f.getMonth() + 1) + "-" + f.getFullYear();

		$("#ExcelPausasActivas").table2excel({
			exclude: ".noExl",
			name: "Worksheet Name",
			filename: "Informe-Pausas-Activas" + "-" + fecha, //do not include extension
			fileext: ".xlsx" // file extension
		});

	}
</script>
