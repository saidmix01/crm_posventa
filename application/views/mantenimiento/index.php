<?php $this->load->view('mantenimiento/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content">
		<div class="card">

			<div class="card-body">
				<div><label class="col-lg-12 text-center lead">Gestion de Equipos y Mantenimiento</label></div>
				<hr>
				<form action="" method="post">
					<div class="form-row">
						<div class="form-group col-lg-5 col-md-4 col-sm-4">
							<label for="exampleInputEmail1">Sede:</label>
							<select class="form-control js-example-basic-single" id="buscarsede" name="buscarsede">
								<option value="">Seleccione una opción</option>
								<option value="Giron">Giron</option>
								<option value="Barrancabermeja">Barrancabermeja</option>
								<option value="Rosita">Rosita</option>
								<option value="Cucuta">Cucuta</option>
							</select>
						</div>
						<div class="form-group col-lg-5 col-md-4 col-sm-4">
							<label for="exampleInputPassword1">Área:</label>
							<select class="form-control js-example-basic-single" id="buscarbodega" name="buscarbodega">
								<option value="" class="d-none">Seleccione el área</option>
								<option value="Lamina y pintura">Lamina y pintura</option>
								<option value="Gasolina">Gasolina</option>
								<option value="Mecanica diesel">Mecanica diesel</option>
								<option value="Alistamiento">Alistamiento</option>
								<option value="Chevy express">Chevy express</option>
							</select>
						</div>
						<div class="form-group col-lg-2 col-md-4 col-sm-4" style="align-self: end;">
							<button type="button" class="btn btn-info" onclick="buscar_equipos_por_sede_y_bodega()">Buscar</button>
						</div>
					</div>
				</form>
				<div id="msjerror"></div>
				<hr>
				<div id="respuesta"></div>
				<button type="button" id="nuevoEquipo" class="btn btn-primary" data-toggle='modal' data-target='#modalEquipos'>
					Nuevo Equipo &nbsp; <span><i class='fas fa-plus-square'></i></span>
				</button>

				<hr>
				<!-- inicio de la tbla para visalizar campos de la base de datos-->
				<div class="table-responsive">
					<div id="tablafiltro"></div>
				</div>
				<div class="table-responsive" id="tablatotal">
					<table class="table nowrap table-striped" id="tabla_uno">
						<button type="button" id="nuevoEquipo" class="btn btn-success float-right " onclick="bajar_excel_tabla_uno()">
							Descargar Excel &nbsp; <span><i class='fas fa-file-excel'></i></span>
						</button>
						<thead class="bg-dark">
							<tr>
								<th style='vertical-align: middle'>Codigo</th>
								<th style='vertical-align: middle'>Familia del Equipo</th>
								<th style='vertical-align: middle'>Nombre del Equipo</th>
								<th style='vertical-align: middle' class="text-center">Estado</th>
								<th style='vertical-align: middle' class='text-center'>Bodega</th>
								<th style='vertical-align: middle' class='text-center'>Area</th>
								<th style='vertical-align: middle' class="text-center">Mantenimiento</th>
								<th style='vertical-align: middle' class="text-center">CV Equipo</th>
								<th style='vertical-align: middle' class="text-center">Historial</th>
								<th style='vertical-align: middle' class="text-center">Editar</th>
								<th style='vertical-align: middle' class="text-center">Retirar</th>
							</tr>
						</thead>
						<tbody>

							<?php foreach ($data_tabla->result() as $key) {
								$color = "";
								if ($key->estado == 'Activo') {
									$color = "#56CC0A";
								} else if ($key->estado == 'Reparacion') {
									$color = "#F3DA0A ";
								}


								$estado = "";
								if ($key->estado == 'Reparacion') {
									$estado = 'disabled';
								} else if ($key->estado == 'Activo') {
									$estado = 'enabled';
								}
								$bandera = "";
								if ($key->cv_equipo != "") {
									$bandera = '<td class="text-center"><a href="' . base_url() . 'public/mantenimiento/cv_equipos/' . $key->cv_equipo . '" target="_blank" type="button" class="btn btn-primary"><i class="fas fa-file-download"></i></a></td>';
								} else {
									$bandera = '<td class="text-center"><button disabled target="_blank" type="button" class="btn btn-primary"><i class="fas fa-file-excel"></i></button></td>';
								}

								echo '
                        <tr>
							<td class="text-center" >' . $key->codigo . '</td>
                            <td class="text-center" >' . $key->nombre_equipo . '</td>
							<td class="text-center" >' . (($key->alias_equipo != "") ? $key->alias_equipo  : '--') . '</td>
							<td class="text-center" style = "color:" . $color . "" ><strong>' . $key->estado . ' </strong></td>  
							<td class="text-center"  >' . $key->bodega . '</td>               
							<td class="text-center"  >' . $key->area . '</td>  
							<td class="text-center"><button ' . $estado . ' class="tx btn btn-success shadow" data-toggle="modal" data-target="#modalMantenimiento" id="' . $key->id_equipo . '" onclick="traer(this.id);" ><i class="fas fa-plus-square"></i></button></td>
                            ' . $bandera . '
							<td class="text-center"><button  class="tx btn btn-info shadow"  id="' . $key->id_equipo . '" onclick="ver(this.id)"><i class="far fa-eye"></i></button></td>  
							<td class="text-center"><button class="tx btn btn-warning shadow" data-toggle="modal" data-target="#modaledtequipo" id="' . $key->id_equipo . '" onclick="pintar_datos(this.id)"><i class="text-white far fa-edit"></i></button></td>  
							
							<td class="text-center"><button class="tx btn btn-danger shadow" id="' . $key->id_equipo . '" onclick="solicitud_retiro(' . $key->id_equipo . ')"><i class="far fa-times-circle"></i></button></td>
                        </tr>
                        ';
							} ?>
						</tbody>
						<!-- <td class='text-center'><button class='tx btn btn-danger shadow' id='" . $key->id_equipo . "' onclick='estado(this.id)'><i class='far fa-times-circle'></i></button></td> -->
					</table>
				</div> <br>
				<hr>

			</div>

		</div>
	</section>
	<!-- modal para ver el historial del equipo -->
	<div class="modal fade bd-example-modal-lg" id="modalHistorial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title col-lg-12 text-center" id="exampleModalLabel">Historial de mantenimiento del Equipo</h5>
				</div>
				<div class="modal-body">
					<div class="table-responsive" id="mantenimientos"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- modal para ver el historial en detalle del equipo -->
	<div class="modal fade bd-example-modal-lg" id="modalHistorialDetalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title col-lg-12 text-center" id="exampleModalLabel">Detalles de mantenimiento del Equipo</h5>
				</div>
				<div class="modal-body">

					<div id="mantenimientos_detalle"></div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- modal para agrgar un servio a la tabla equipo -->
	<div class="modal fade bd-example-modal-lg" id="modalEquipos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title col-lg-12 text-center" id="exampleModalLabel">Ingreso de Nuevos Equipos</h5>
				</div>
				<div class="modal-body">
					<form id="aggCodigo" action="<?= base_url() ?>mantenimiento/agregar_equipo" method="POST" enctype="multipart/form-data">
						<div class="form-row">
							<div class="col-lg-6 col-sm-6 col-xs-6">
								<label for="">Alias del equipo</label>
								<input class="form-control" type="text" id="aliasEquipo" name="aliasEquipo" placeholder="Escriba el alias del equipo aquí." required>
							</div>
							<div class="col-lg-6 col-sm-6 col-xs-6">
								<label for="">Familia de Equipos</label>
								<select class="form-control" id="nombreEquipo" name="nombreEquipo" onclick="cargarNombreEquipos();" required>
									<?php
									foreach ($equiposF->result() as $key) {
										echo
										'<option autocapitalize="true" value="' . $key->codigo . '">' . $key->nombre . '</option>';
									}
									?>
								</select>
							</div>
							<div id="rtaNombreEquipos" class="col-lg-6 col-sm-6 col-xs-6">

							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 ">
								<label for="">Bodega</label>
								<!-- 	<select class="js-example-responsive js-states form-control p-5 m-4" id="nombreBodega" name="nombreBodega"> -->
								<select class=" form-control" id="nombreBodega" name="nombreBodega" required>
									<option value="" class="d-none">Seleccione una Bodega</option>
									<option value="B">Barrancabermeja</option>
									<option value="C">Cucuta</option>
									<option value="G">Giron</option>
									<option value="R">Rosita</option>

								</select>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 ">
								<label for="">Area</label>
								<select class="form-control" id="nombrearea" name="nombrearea" required>
									<option value="" class="d-none">Seleccione el área</option>
									<option value="L">Lamina y pintura</option>
									<option value="M">Gasolina</option>
									<option value="D">Mecanica diesel</option>
									<option value="A">Alistamiento</option>
									<option value="X">Chevy express</option>
								</select>
							</div>
							<div class="col-lg-6 col-sm-6 d-none">
								<input value="Activo" class="form-control" id="estado" name="estado">
							</div>
							<div class="col-lg-6 col-sm-6">
								<label for="">Código</label>
								<input value="" class="form-control" id="codigoE" name="codigoE" readonly>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 ">
								<label for="">Hoja de vida del Equipo</label>
								<input type="file" id="imagen_cv" name="imagen_cv" required>
							</div>
						</div>
						<div class="modal-footer">

							<button class="btn btn-success">Registrar</button>

							<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- modal para agrgar mantenimiento a un equipo -->
	<div class="modal fade bd-example-modal-lg" id="modalMantenimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-header">
					<h5 class="modal-title col-lg-12 text-center">Orden de Mantenimiento Preventivo</h5>
				</div>
				<hr>
				<!-------------------------------------formulario para mantenimiento preventivo----------------------------------------->
				<div class="modal-body">
					<form id="aggMantenimientoPre" action="<?= base_url() ?>mantenimiento/orden_mto_preventivo" method="POST" enctype="multipart/form-data">
						<div class='col-lg-6 col-sm-6 col-xs-6 d-none'>
							<input id='id_equipoMp' value="" name='id_equipoMp' type='text' class='form-control' required>
						</div>
						<div class='form-row'>

							<div class='col-lg-4 col-sm-6 col-xs-6'>
								<label for=''>Codigo de Equipo:</label>
								<input value='' id='codigoEquipoMp' name='codigoEquipoMp' type='text' class='form-control' readonly>
							</div>
							<div class='col-lg-4 col-sm-6 col-xs-6 d-none'>
								<label for=''>Sede de Equipo:</label>
								<input value='' id='sedeMp' name='sedeMp' type='text' class='form-control' readonly>
							</div>


							<div class='col-lg-4 col-sm-6 col-xs-6'>
								<label for=''>Nombre del Equipo:</label>
								<input value='' id='nombreMp' name='nombreMp' type='text' class='form-control' readonly>
							</div>
							<div class='col-lg-4 col-sm-6 col-xs-6'>
								<label>Mantenimiento:</label>
								<select class='form-control' id='tipoMantenimientoMp' name='tipoMantenimientoMp' ; required>
									<!-- <option class='d-none'></option> -->
									<option value='1'>Preventivo</option>
									<!-- <option value='2'>Correctivo</option> -->
								</select>
							</div>

						</div>
						<div class="form-row">
							<?php
							$fecha = date("Y/m/d");
							?>
							<div class='col-lg-4 col-sm-6 col-xs-6'>
								<label for='fechaMantenimiento'>Fecha de Solicitud:</label>
								<input type='text' name='f_solicitud' id='f_solicitud' class='form-control' value="<?= $fecha ?>" placeholder="<?= $fecha ?>" readonly>
							</div>

							<div class='col-lg-4 col-sm-6 col-xs-6'>
								<label for='fechaRequerida'>Fecha Requerida:</label>
								<input type='date' name='f_requerida' id='f_requerida' class='form-control' required>
							</div>

							<div class='col-lg-4 col-sm-6 col-xs-6'>
								<label for='tiempo_estimado'>Tiempo estimado[Horas]:</label>
								<input type='number' min="1" name='tiempo_estimado' id='tiempo_estimado' class='form-control' required>
							</div>

							<div class='col-lg-12 col-sm-12' id='campo1'>
								<label>Descripcion:</label>
								<textarea placeholder="Escriba aqui la descripción del mantenimiento" name='descripcionMp' id='descripcionMp' rows='4' class='form-control' required></textarea>
							</div>
						</div>
						<div class='modal-footer'>
							<button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
							<button class="btn btn-success">Agregar Mantenimiento</button>

						</div>


						<hr>

					</form>
				</div>
			</div>
		</div>
	</div>
	<!--------------------------------------------------------- Modal para editar informacion de equipo--------------------------------- -->
	<div class="modal fade bd-example-modal-lg" id="modaledtequipo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title alert text-center col-lg-12" id="exampleModalLabel">Editar infomacion de Equipo</h5>
				</div>
				<div class="modal-body">
					<div id="edi_datos"></div>
				</div>

			</div>
		</div>
	</div>
	<!--------------------------------------------------------- Modal para solicitar retiro de equipo--------------------------------- -->
	<div class="modal fade bd-example-modal-lg" id="modaledRetiro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title alert text-center col-lg-12" id="exampleModalLabel">Solicitud retiro de Equipo</h5>
				</div>
				<div class="modal-body">
					<form action="<?= base_url() ?>mantenimiento/retirar_equipo" method="POST" enctype="multipart/form-data">
						<div class="form-row">
							<div class="form-group col-lg-6">
								<label for="exampleInputEmail1">Motivo de solicitud:</label>
								<textarea class="form-control" cols="50" id="motivo_solicitud" name="motivo_solicitud" placeholder="Escriba el motivo del retiro" required></textarea>
							</div>
							<div class="form-group col-lg-6">
								<label for="Select2">Jefe Encargado:</label>
								<select id="Select2" class="form-control js-example-basic-single" name="jefe">
									<?php foreach ($jefes->result() as $key) {
									?>
										<option value="<?= $key->nit ?>"><?= $key->nombres ?></option>
									<?php
									}
									?>
								</select>

							</div>

						</div>
						<div class="form-row">
							<div class="form-group col-lg-6">
								<label for="exampleInputEmail1">Imagen del retiro</label>
								<input type="file" id="imagen_solicitud" name="imagen_solicitud" required>
								<input type="hidden" id="nitUsiario" name="nitUsiario" value="<?= $nit ?>">
								<input type="hidden" id="id_equipo" name="id_equipo">
							</div>
						</div>

				</div>
				<div class="modal-footer">
					<div class="form-row">
						<div class="form-group col-lg-6">
							<button class="btn btn-success">Retirar</button>
						</div>
					</div>
					<div class="form-group col-lg-6">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
					</div>
				</div>
				</form>

			</div>
		</div>
	</div>
	<!-- /.content -->
</div>
<?php
$log = $this->input->get('log');
if ($log == "ok") {
?>
	<div class="alert alert-success alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
		Solicitud de retiro creada exitosamente
		<button onclick="location.href='index';" type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php
} elseif ($log == "falla") {
?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
		Ya existe una solicitud de retiro para este activo fijo, por lo tanto no se puede crear otra solicitud.
		<button onclick="location.href='index';" type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php
} elseif ($log == "0") {
?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
		Ya existe una solicitud de retiro en estado pendiente.
		<button onclick="location.href='index';" type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

<?php
} elseif ($log == "2") {
?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
		Ya existe una solicitud de retiro en estado autorizado.
		<button onclick="location.href='index';" type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php
}elseif ($log == "okCrear") {
	?>
		<div class="alert alert-success alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
			Equipo creado con exito.
			<button onclick="location.href='index';" type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	<?php
	}elseif ($log == "errCrear") {
		?>
			<div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
				Error al crear el equipo.
				<button onclick="location.href='index';" type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		<?php
		}
?>
<?php $this->load->view('mantenimiento/footer') ?>

<script>
	$(document).ready(function() {
		$("#buscar_items").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#menu_items li").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
			});
		});

		$('.js-example-basic-single').select2({
			theme: "classic",
			placeholder: 'Seleccione una opción',
			width: '100%'
		});
		cargarDatatable();
		cargarNombreEquipos();

		/* Carga el codigo apenas cargue el modal de agregar nuevo mantenimiento preventivo */
		var codigoF = document.getElementById("nombreEquipo2").value;
		var codigoU = document.getElementById("nombreBodega").value;
		var codigoA = document.getElementById("nombrearea").value;
		/* var serie = document.getElementById("serie").value;
		serie */

		var codigoYa = "" + codigoF + codigoU + codigoA;
		$("#codigoE").val(codigoYa);

	});

	var btnAgg = document.getElementById("aggCodigo");
	btnAgg.addEventListener("click", function() {

		var codigoF = document.getElementById("nombreEquipo2").value;
		var codigoU = document.getElementById("nombreBodega").value;
		var codigoA = document.getElementById("nombrearea").value;
		/* var serie = document.getElementById("serie").value;
		serie */

		var codigoYa = "" + codigoF + codigoU + codigoA;
		$("#codigoE").val(codigoYa);
	});

	function cargarNombreEquipos() {
		var codigoF = document.getElementById("nombreEquipo").value;
		console.log(codigoF);
		if (codigoF == "") {
			alert("Funcion fallida");
		} else {
			var request = new XMLHttpRequest();
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					document.getElementById('rtaNombreEquipos').innerHTML = request.responseText;
				}
			}
			request.open("GET", "<?= base_url() ?>mantenimiento/getCodigoEquipo?codigo=" + codigoF, false);
			request.send();
		}
	}


	function cargarDatatable2() {
		$('#tabla_historial').DataTable({
			"paging": false,
			"pageLength": 10,
			"lengthChange": true,
			"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "Todos"] ],
			"searching": false,
			"ordering": false,
			"info": false,
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

	function cargarDatatable() {
		$('#tabla_uno').DataTable({
			"paging": true,
			"pageLength": 10,
			"lengthChange": true,
			"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "Todos"] ],
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

	}
</script>