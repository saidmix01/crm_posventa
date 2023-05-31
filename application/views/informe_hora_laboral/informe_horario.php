<?php $this->load->view('Informe_hora_laboral/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content">
		<div class="card">
			<div class="card-body">
				<div><label class="col-lg-12 text-center lead">Horarios de Llegada y Salida</label></div>
				<hr>
				<form id="formulariobusqueda">
					<div class="form-row">
						<div class="col">
							<div class="form-group">
								<label for="sede">Sede</label>
								<select class="form-control" id="sede" name="sede">
									<option value="giron">Girón</option>
									<!--<option value="todas">Todas</option>-->
									<option value="rosita">La Rosita</option>
									<option value="bocono">Cúcuta Boconó</option>
									<option value="Malecon">Cúcuta Malecon</option>
									<option value="Barrancabermeja">Barrancabermeja</option>

								</select>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label for="sede">fecha Inicial</label>
								<input type="date" name="fecha_ini" id="fecha_ini" class="form-control">
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label for="sede">fecha Final</label>
								<input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
							</div>
						</div>
						<div class="col">
							
							<div class="form-group">
								<label for="sede">Empleado</label>
								<select class="form-control" id="nit" name="nit">
									<option value="">Seleccione un empleado...</option>
									<?php foreach ($data_emp->result() as $key){?>
										<option value="<?=$key->nit?>"><?=$key->nombres?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="col" align="center">
						<a href="#" onclick="load_tabla();" class="btn btn-primary">Buscar</a>
						<button type="button" onclick="bajar_excel_inf_horario();" class="btn btn-success" id="btn_export_excel" disabled="">Exportar a Excel</button>
					</div>
				</form>
				<hr>
				<div class="table-responsive">
					<table class="table table-hover" id="example1">
						<thead>
							<tr align="center">
								<th>CC</th>
								<th>Nombres</th>
								<th>Sede</th>
								<th>Dia</th>
								<th>Fecha</th>
								<th>Horario Entrada AM</th>
								<th>Horario Salida AM</th>
								<th>Horario Entrada PM</th>
								<th>Horario Salida PM</th>
								<th>Hora Inicio Ausentismo</th>
								<th>Hora Reintegro Ausentismo</th>
								<th>Hora Llegada AM</th>
								<th>Hora Salida AM</th>
								<th>Hora Llegada PM</th>
								<th>Hora Salida PM</th>
								<th>Diferencia Entrada AM</th>
								<th>Diferencua Salida AM</th>
								<th>Diferencia Entrada PM</th>
								<th>Diferencia Salida PM</th>
							</tr>
						</thead>
						<tbody id="tablaresult">
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>


	<!-- /.content -->
</div>
<?php $this->load->view('Informe_hora_laboral/footer') ?>

<script type="text/javascript">

	function load_tabla() {
		$('#example1').dataTable().fnDestroy();
		document.getElementById("cargando").style.display = "block";
		
		var sede = document.getElementById('sede').value;
		var fecha_ini = document.getElementById('fecha_ini').value;
		var fecha_fin = document.getElementById('fecha_fin').value;
		var nit = document.getElementById('nit').value;

		if (fecha_ini == "" || fecha_fin == "") {
			alert("los campos estan vacios!");
		} else {
			var xmlhttp1;
			if (window.XMLHttpRequest) {

				var url = "<?= base_url() ?>Informes/load_tabla_reg_ingreso";
				xmlhttp1 = new XMLHttpRequest();
				var datos = new FormData();
				datos.append('sede', sede);
				datos.append('fecha_ini', fecha_ini);
				datos.append('fecha_fin', fecha_fin);
				datos.append('nit', nit);
			} else {
				xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp1.onreadystatechange = function() {
				if (xmlhttp1.readyState === 4 && xmlhttp1.status === 200) {
					document.getElementById('tablaresult').innerHTML = xmlhttp1.responseText;
					load_data_table();
					document.getElementById("cargando").style.display = "none";	
					document.getElementById("btn_export_excel").disabled = false;
				}
			}
			xmlhttp1.open("POST", url);
			xmlhttp1.send(datos);
		}
	}
</script>

<script>
	function bajar_excel_inf_horario() {
		$("#example1").table2excel({
			exclude: ".noExl",
			name: "Worksheet Name",
			filename: "Informe-horarios", //do not include extension
			fileext: ".xlsx" // file extension
		});
	}
</script>
<script>
	function load_data_table() {
		$('#example1').DataTable({
			"paging": false,
			"pageLength": 10,
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
	}
</script>