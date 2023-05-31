<?php $this->load->view('Cotizador/header') ?>
<div class="content-wrapper">
	<section class="content">
		<div class="alert alert-light text-center" role="alert">
			<h4>Cotización</h4>
		</div>
	</section>
	<!-- Main content -->
	<div class="loader" id="cargando"></div>
	<section class="content">
		<div class="card">
			<div class="card-header">
				<div class="row" style="align-items:end;">
					<div class="col-6 col-md-2 col-sm-3">
						<label class="control-label">Placa:</label>
						<input placeholder="Escriba aquí la placa" class="form-control" type="text" id="placa" name="placa" onchange="cargarClase();">
					</div>
					<div class="col-4 col-md-1 col-sm-2">
						<button type="button" class="btn btn-sm btn-danger fas fa-question-circle" data-placement="bottom" data-toggle="popover" data-trigger="focus" title="" data-content="La placa debe ser mayor a 6 caracteres, para poder realizar la busqueda..." data-original-title="Advertencia"></button>
					</div>
					<div class="col-6 col-md-2 col-sm-3">
						<label class="control-label">Prepagado:</label>
						<input readonly class="form-control" type="text" id="mttoPre" name="mttoPre">
					</div>
					<div class="col-6 col-md-2 col-sm-3">
						<label class="control-label">Tipo de Mantenimiento:</label>
						<select onchange="checkMtto(this.value);" class="form-control js-example-basic-single" id="comboMtto_2" name="comboMtto_2">
							<option value="">Seleccione una opción</option>
							<option value="0">MTTO GARANTÍA</option>
							<option value="1">MTTO A LA MEDIDA</option>
						</select>
					</div>
					<div class="col" style="align-self: flex-start; text-align: end;">
						<button type="button" class="btn btn-sm bg-red" data-toggle="tooltip" title="Agregar o Crear un Posible Retorno" onclick="FnPosibleRetorno()"><i class="fas fa-plus-square"> POSIBLE RETORNO</i></button>
					</div>
				</div>
			</div>
			<div class="card-body">
				<form method="POST" enctype="multipart/form-data">
					<div class="row" style="align-items:end;">
						<div class="col-12 col-md-2 col-sm-6">
							<label class="control-label">Doc. Cliente</label>
							<input placeholder="Número de documento del cliente" class="form-control" type="number" id="docCliente" name="docCliente" readonly>
						</div>
						<div class="col-12 col-md-4 col-sm-6">
							<label class="control-label">Nombre Cliente</label>
							<input placeholder="Nombre del cliente" class="form-control" type="text" id="nomCliente" name="nomCliente" readonly>
						</div>
						<div class="col-12 col-md-2 col-sm-6">
							<label class="control-label">Celular Cliente:</label>
							<input placeholder="Número de celular del cliente" class="form-control" type="number" id="telfCliente" name="telfCliente" required="true">
						</div>
						<div class="col-12 col-md-4 col-sm-6">
							<label class="control-label">Correo Cliente:</label>
							<input placeholder="Correo del cliente" class="form-control" type="email" id="emailCliente" name="emailCliente" required="true" onchange="validarEmail(this.value);">
						</div>

					</div>
					<div class="row">
						<div class="col-12 col-md-2 col-sm-6">
							<label class="control-label">Clase:</label>
							<input placeholder="Clase del vehículo" class="form-control" type="text" id="clase" name="clase" readonly>
						</div>
						<div class="col-12 col-md-2 col-sm-6">
							<label class="control-label">Año:</label>
							<select class="form-control js-example-basic-single-year desc" type="text" id="year_model" name="year_model" disabled>
								<option value="">Seleccione una año</option>
								<?= $year = date('Y');
								for ($i = 1985; $i <= $year; $i++) {
									echo '<option value="' . $i . '">' . $i . '</option>';
								}
								?>
							</select>
						</div>
						<div class="col-12 col-md-2 col-sm-6" id="divDesc">
							<label class="control-label">Descripción:</label>
							<input placeholder="Descripción clase del vehículo" class="form-control desc" type="text" id="desc" name="desc" readonly>
						</div>
						<div class="col-12 col-md-2 col-sm-6" id="descSelect" style="display: none;">
							<label class="control-label">Descripción:</label>
							<select class="form-control js-example-basic-single desc" type="text" id="descS" name="descS" onchange="selectClase();" readonly>
								<option value="">Seleccione una opción</option>
								<?php foreach ($descClase->result() as $key) { ?>
									<option value="<?= $key->clase ?>"><?= $key->descripcion ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-12 col-md-4 col-sm-6" id="divDescModelo">
							<label class="control-label">Descripción modelo:</label>
							<input placeholder="Descripcion modelo" class="form-control modelo" type="text" id="descModelo" name="descModel0" readonly>
						</div>
						<div class="col-12 col-md-4 col-sm-6" id="modeloSelect" style="display: none;">
							<label class="control-label">Descripción modelo:</label>
							<select class="form-control js-example-basic-single modelo" type="text" id="descModeloS" name="descModel0S" readonly>
								<option value="">Seleccione una opción</option>
							</select>
						</div>
						<div class="col-12 col-md-2 col-sm-6">
							<label class="control-label">Bodega:</label>
							<select class="form-control js-example-basic-single" id="bodega" name="bodega">
								<option value="">Seleccione una opción</option>
								<option value="1">Girón Gasolina</option>
								<option value="6">Barranca</option>
								<option value="7">Rosita</option>
								<option value="8">Cúcuta</option>

							</select>
						</div>
						<div class="col-12 col-md-2 col-sm-6">
							<label class="control-label">Revisión:</label>
							<select class="form-control js-example-basic-single" id="revision" name="revision" readonly>
								<option value="">Seleccione una opción</option>
							</select>
						</div>
						<div class="col-12 col-sm-2">
							<label class="control-label">km actual:</label>
							<input placeholder="Kilometraje actual" class="form-control" type="number" id="KmA" name="KmA" readonly>
						</div>
						<div class="col-12 col-sm-2">
							<label class="control-label">km estimado:</label>
							<input placeholder="Kilometraje estimado" class="form-control" type="number" id="KmE" name="KmE" readonly>
						</div>
						<div class="col-12 col-sm-2">
							<label class="control-label">km cliente:</label>
							<input placeholder="Kilometraje informado por el cliente" class="form-control" type="number" id="KmC" name="kmC" onchange="verificarKms();" required="true">
						</div>
						<div class="col-6 col-md-3 col-sm-6 mt-2" style="align-self:flex-end; text-align: center;">
							<button type="button" class="btn btn-warning" id="btnCargarDatosCotizacion" onclick="cargarDatosCotizacion();">Cargar</button>
						</div>
					</div>
					<hr>
					<div class="row text-center">
						<div class="col-6 col-md-4 col-sm-6">
							<label class="control-label">Adicionales</label>
							<select class="form-control js-example-basic-single-adicional" type="text" id="AggAdicional" name="AggAdicional">
								<option value="">Seleccione un adicional</option>
								<?php foreach ($nameAdicionales->result() as $key) {
									echo '<option value="' . $key->adicional . '">' . $key->adicional . '</option>';
								} ?>

							</select>
						</div>
						<div class="col-6 col-md-4 col-sm-6" style="align-self: center;">
							<button class="btn btn-success" type="button" id="btnModalAggAdicional" onclick="AddAdicionales()">Agregar adicional</button>
						</div>
					</div>
					<hr>
					<div class="row">

						<div class="col-12 table-responsive-md" id="tablaInfo">

						</div>
					</div>
					<div class="row" id="cardAdicionales" style="display:none">
						<div class="col-12 table-responsive-md" id="tablaRepuestosAdicionales">
							<table class="table table-bordered" id="tableRAdicionales">
								<thead>
									<tr class="text-center">
										<th scope="col" colspan="6">Repuestos</th>
									</tr>
									<tr class="text-center">
										<th scope="col">Codigo</th>
										<th scope="col">Descripción</th>
										<th scope="col">Cantidad</th>
										<th scope="col">Unidades disponibles</th>
										<th scope="col">Valor</th>
										<th scope="col">OPCIÓN</th>
									</tr>
								</thead>
								<tbody id="bodyTableRAdicionales">
								</tbody>
								<!-- <tfoot>
									<tr>
										<th class="text-right" colspan="4">Subtotal</th>
										<td class="text-right" colspan="1" id="sutTotalAdicionalRepuestos"></td>
										<td colspan="1"></td>
									</tr>
								</tfoot> -->
							</table>
						</div>
						<div class="col-12 table-responsive-md" id="tablaManoObraAdicionales">
							<table class="table table-bordered" id="tableMAdicionales">
								<thead>
									<tr>
										<th class="text-center" scope="col" colspan="6">Mano de obra</th>
									</tr>
									<tr class="text-center">
										<th scope="col" colspan="3">Descripcion</th>
										<th scope="col" colspan="1">Tiempo</th>
										<th scope="col" colspan="1">Valor</th>
										<th scope="col">OPCIÓN</th>
									</tr>
								</thead>
								<tbody id="bodyTableMAdicionales">
								</tbody>
								<!-- <tfoot>
									<tr>
										<th class="text-right" colspan="3">Subtotal</th>
										<td class="text-center" colspan="1" id="sutTotalHorasAdicionalMtto"></td>
										<td class="text-right" colspan="1" id="sutTotalAdicionalMtto"></td>
									</tr>
								</tfoot> -->
							</table>
						</div>
					</div>
				</form>

			</div>
			<div class="card-footer">
				<div class="row">
					<div class="col-12 col-md-12 col-sm-12" id="divObs">
						<label>Observaciones:</label>
						<textarea class="form-control" id="obs" rows="4" name="obs" placeholder="Escriba aquí las observaciones hechas por el cliente..."></textarea>
					</div>
					<div class="col-12 col-md-6 col-sm-6" id="divTotal" style="display:none;">
						<label>Total cotización:</label>
						<span>El total de horas en el taller son: <i id="sutTotalHorasAdicionalMtto">0</i> horas</span>
						<table style="width:100%" class="table table-sm table-bordered">
							<tbody>
								<tr>
									<th style="text-align: right;width:50%;">Subtotal Repuestos</th>
									<td style="text-align: right;width:50%;" id="subtotalRepuestosT"></td>
								</tr>
								<tr>
									<th style="text-align: right;width:50%;">Subtotal Mano Obra</th>
									<td style="text-align: right;width:50%;" id="subtotalManoT"></td>
								</tr>
								<tr style="background-color: #80808063;">
									<th style="text-align: right;width:50%;">Total</th>
									<td style="text-align: right;width:50%;" id="totalCotizacion"></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="row" id="btnGuardarMttoGarantia">
					<div class="col-12 col-md-12 col-sm-12 mt-2" style="align-self:flex-end; text-align: end;">
						<button type="button" disabled="true" class="btn btn-outline-success btnInput" id="" onclick="guardarDatosInput(0);"><em class="fas fa-save">&nbsp;Guardar</em></button>
						<button type="button" disabled="true" class="btn btn-outline-success btnSelect" id="" onclick="guardarDatosSelect(0);" style="display: none ;"><em class="fas fa-save">&nbsp;Guardar</em></button>

						<button type="button" disabled="true" class="btn btn-outline-primary btnInput" id="" onclick="guardarDatosInput(1);"><em class="fas fa-paper-plane">&nbsp;Enviar</em></button>
						<button type="button" disabled="true" class="btn btn-outline-primary btnSelect" id="" onclick="guardarDatosSelect(1);" style="display: none ;"><em class="fas fa-paper-plane">&nbsp;Enviar</em></button>

						<button type="button" disabled="true" class="btn btn-outline-danger btnInput" id="" onclick="guardarDatosInput(2);"><em class="fas fa-envelope">&nbsp;Acepta
								agenda</em></button>
						<button type="button" disabled="true" class="btn btn-outline-danger btnSelect" id="" onclick="guardarDatosSelect(2);" style="display: none ;"><em class="fas fa-envelope">&nbsp;Acepta agenda</em></button>
					</div>
				</div>
				<div class="row" id="btnGuardarMttoMedida" style="display:none;">
					<div class="col-12 col-md-12 col-sm-12 mt-2" style="align-self:flex-end; text-align: end;">
						<button type="button" class="btn btn-outline-success" id="" onclick="guardarDatos_2(0);"><em class="fas fa-save">&nbsp;Guardar</em></button>

						<button type="button" class="btn btn-outline-primary" id="" onclick="guardarDatos_2(1);"><em class="fas fa-paper-plane">&nbsp;Enviar</em></button>

						<button type="button" class="btn btn-outline-danger" id="" onclick="guardarDatos_2(2);"><em class="fas fa-envelope">&nbsp;Acepta
								agenda</em></button>
					</div>
				</div>
			</div>


		</div>


	</section>
	<!-- /.content -->
	<!-- Modal para mostrar los adicionales de Repuestos -->
	<div class="modal fade" id="modalRAdicionales" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-lg modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Agregar adicionales</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row table-responsive" id="tablaRepuestoAdicional">

					</div>
				</div>
				<div class="modal-footer" id="btnsAdicionales">
					<button id="btnAggAdicional" class="btn btn-success" onclick="aggAdicionalCotizacion();">Agregar</button>
					<button id="btnAggAdicionalH" style="display:none;" class="btn btn-success" onclick="aggAdicionalCotizacionH();">Agregar</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal para agregar o crear un posible retorno asociado a la placa -->
	<div class="modal fade" id="modalPosibleRetorno" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-lg modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Agregar Posible Retorno</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-3">
							<label for="placaPosibleRetorno">Placa</label>
							<input class="form-control" type="text" id="placaPosibleRetorno">
						</div>
						<div class="col-4">
							<label for="bodegaPosibleRetorno">Bodega</label>
							<select class="form-control js-example-basic-single" name="bodegaPosibleRetorno" id="bodegaPosibleRetorno">
								<option value="">--</option>
								<option value="1">CODIESEL PRINCIPAL</option>
								<option value="6">CHEVYEXPRESS BARRANCA</option>
								<option value="7">CHEVYEXPRESS LA ROSITA</option>
								<option value="8">CODIESEL VILLA DEL ROSARIO</option>
								<option value="9">LAMINA Y PINTURA AUTOMOVILES-GIRON</option>
								<option value="10">ACCESORIZACION </option>
								<option value="11">DIESEL EXPRESS GIRON </option>
								<option value="14">LAMINA Y PINTURA AUTOMOVILES-BOCONO </option>
								<option value="16">BOCONO DIESEL EXPRESS </option>
								<option value="19">DIESEL EXPRESS BARRANCA </option>
								<option value="21">LAMINA Y PINTURA CAMIONES-GIRON</option>
								<option value="22">LAMINA Y PINTURA CAMIONES-BOCONO</option>
							</select>
						</div>
						<div class="col-4">
							<label for="tipoPosibleRetorno">Tipo Posible Retorno</label>
							<select class="form-control js-example-basic-single" name="tipoPosibleRetorno" id="tipoPosibleRetorno">
								<option value="">Seleccione un tipo de retorno</option>
								<?php foreach ($tipos_retornos->result() as $tipo_r) {
									echo '<option value="' . $tipo_r->id_tipo_retorno . '">' . $tipo_r->tipo_retorno . '</option>';
								}  ?>
							</select>
						</div>
						<div class="col-12">
							<label for="obsPosibleRetorno">Observación</label>
							<textarea class="form-control" id="obsPosibleRetorno" placeholder="Escriba aquí la observación realizada por el cliente"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-success" onclick="aggPosibleRetorno();">Agregar</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

</div>
<?php $this->load->view('Cotizador/footer') ?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#buscar_items").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#menu_items li").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		$('.js-example-basic-single').select2({
			width: '100%',
			placeholder: 'Seleccione una opción',
			theme: "classic",
			allowClear: true
		});
		$('.js-example-basic-single-year').select2({
			width: '100%',
			placeholder: 'Seleccione un año',
			theme: "classic",
			allowClear: true
		});
		$('.js-example-basic-single-adicional').select2({
			width: '100%',
			placeholder: 'Seleccione un adicional',
			theme: "classic",
			allowClear: true
		});

		$("#btnSelect").hide();

		$(function() {
			$('[data-toggle="popover"]').popover()
		});



	});

	$("#descSelect").hide();

	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 5000
	});


	function cargarClase() {
		var placa = document.getElementById('placa').value;
		document.getElementById('cargando').style.display = 'block';
		if (placa != "" && placa.length >= 6) {
			var datos = new FormData();
			datos.append('placa', placa);
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.responseType = 'json';
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					/* var resp = xmlhttp.responseText; */
					var resp = xmlhttp.response;

					if (resp != null) {
						$("#divDesc").show();
						$("#divDescModelo").show();
						$("#modeloSelect").hide();
						$("#descSelect").hide();

						$(".btnInput").show();
						$(".btnInput").addClass("btn-Mostrar");
						$(".btnSelect").hide();
						document.getElementById('tablaInfo').innerHTML = "";
						document.getElementById('docCliente').value = resp[0].nit;
						document.getElementById('nomCliente').value = resp[0].cliente;
						document.getElementById('emailCliente').value = resp[0].mail;
						document.getElementById('telfCliente').value = resp[0].celular;
						document.getElementById('clase').value = resp[0].clase;
						$("#year_model").val(`${resp[0].year}`).change();
						document.getElementById('desc').value = resp[0].descripcion;
						document.getElementById('descModelo').value = resp[0].des_modelo;
						document.getElementById('KmA').value = resp[0].kilometraje;
						document.getElementById('KmE').value = resp[0].km_estimado;
						document.getElementById('KmC').min = (resp[0].kilometraje + 1);
						document.getElementById('KmC').value = "";
						cargarSelectRevisionKm(resp[0].clase);
						document.getElementById('cargando').style.display = 'none';
						cargarMttoPrepagado(placa);
						document.getElementById('year_model').disabled = true;
						document.getElementById('descS').value = "";

					} else {
						document.getElementById('KmC').value = "";
						document.getElementById('tablaInfo').innerHTML = "";
						document.getElementById('clase').value = "";
						document.getElementById('year_model').value = "";
						document.getElementById('desc').value = "";
						document.getElementById('descS').value = "";
						document.getElementById('descModelo').value = "";
						document.getElementById('KmA').value = "";
						document.getElementById('KmE').value = "";
						document.getElementById('KmC').value = "";
						document.getElementById('docCliente').value = "";
						document.getElementById('nomCliente').value = "";
						document.getElementById('emailCliente').value = "";
						document.getElementById('telfCliente').value = "";

						document.getElementById('docCliente').readOnly = false;
						document.getElementById('nomCliente').readOnly = false;
						document.getElementById('emailCliente').readOnly = false;
						document.getElementById('telfCliente').readOnly = false;
						document.getElementById('year_model').disabled = false;
						$("#year_model").val(`0`).change();
						$("#revision").empty();
						Swal.fire({
							title: 'Advertencia!',
							text: 'La placa no se encuentra registrada, debe seleccionar la descricíon del vehiculo para agregar la clase',
							icon: 'warning',
							confirmButtonText: 'Ok'
						});
						$("#divDesc").hide();
						$("#descSelect").show();
						$("#divDescModelo").hide();
						$("#modeloSelect").show();
						$(".btnInput").hide();
						$(".btnSelect").show();
						$(".btnSelect").addClass("btn-Mostrar");
						document.getElementById('cargando').style.display = 'none';

					}
				}
			}
			xmlhttp.open("POST", "<?= base_url() ?>Cotizador/cargarClasePlaca", true);
			xmlhttp.send(datos);
		} else {
			Swal.fire({
				title: 'Advertencia!',
				text: 'El campo se encuentra vacio o debe tener al menos 6 caracteres',
				icon: 'warning',
				confirmButtonText: 'Ok'
			});
			document.getElementById('cargando').style.display = 'none';
		}
	}

	function selectClase() {
		var clase = document.getElementById('descS').value;
		document.getElementById('clase').value = clase;
		/* Seleccionar el texto del select */
		var desc = $('select[name="descS"] option:selected').text();
		cargarDescModelSelect(desc);
		cargarSelectRevisionKm(clase);
	}

	function cargarSelectRevisionKm(clase) {
		var datos = new FormData();
		datos.append('clase', clase);
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.responseType = 'json';
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				var resp = xmlhttp.response;

				resp.unshift('seleccione una opcion');

				// expected output: Array [4, 5, 1, 2, 3]
				$("#revision").empty();
				for (let index = 0; index < resp.length; index++) {
					$('#revision').append($('<option>', {
						value: resp[index].Revision,
						text: resp[index].Revision
					}));

				}


			}
		}
		xmlhttp.open("POST", "<?= base_url() ?>Cotizador/cargarRevisionSelect", true);
		xmlhttp.send(datos);
	}

	function cargarDescModelSelect(desc) {

		var datos = new FormData();
		datos.append('desc', desc);
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.responseType = 'json';
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				var resp = xmlhttp.response;
				$("#divDescModelo").hide();
				$("#modeloSelect").show();
				$("#descModeloS").empty();
				for (let j = 0; j < resp.length; j++) {
					$('#descModeloS').append($('<option>', {
						value: resp[j].des_modelo,
						text: resp[j].des_modelo
					}));

				}

			}
		}
		xmlhttp.open("POST", "<?= base_url() ?>Cotizador/cargarDescModelSelect", true);
		xmlhttp.send(datos);
	}

	function verificarKms() {
		var kmC = parseInt(document.getElementById('KmC').value);
		var kmA = parseInt(document.getElementById('KmA').value);
		if (kmC <= kmA) {
			Swal.fire({
				title: 'Advertencia',
				text: 'El kilometraje indicado por el cliente, no puede ser menor al kilometraje actual',
				icon: 'warning',
				confirmButtonText: 'Ok',
				allowOutsideClick: false,
				showCloseButton: true
			});
			document.getElementById('KmC').value = "";
		}
	}

	function cargarDatosCotizacion() {
		var clase = document.getElementById('clase').value;
		var year = document.getElementById('year_model').value;
		var bodega = document.getElementById('bodega').value;
		var revision = document.getElementById('revision').value;
		var kmC = parseInt(document.getElementById('KmC').value);
		let tipo = document.getElementById('comboMtto_2').value;

		var kmA = parseInt(document.getElementById('KmA').value);

		if (isNaN(kmA)) {
			kmA = 0;
		}


		if (clase != "" && bodega != "" && revision != "" && kmC > kmA && year != "" && tipo != "") {
			document.getElementById('cargando').style.display = 'block';
			var datos = new FormData();
			datos.append('clase', clase);
			datos.append('year', year);
			datos.append('bodega', bodega);
			datos.append('revision', revision);
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			/* xmlhttp.responseType = 'json'; */
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					var resp = xmlhttp.responseText;
					document.getElementById('tablaInfo').innerHTML = resp;
					document.getElementById('cargando').style.display = 'none';
					if ($(".btnInput")) {
						$(".btn-Mostrar").prop('disabled', false);
					} else if ($("#btnSelect")) {
						$(".btn-Mostrar").prop('disabled', false);
					}
					document.getElementById('obs').readOnly = false;



				}
			}
			xmlhttp.open("POST", "<?= base_url() ?>Cotizador/cargarCotizacionDetalle", true);
			xmlhttp.send(datos);

		} else {
			Swal.fire({
				title: 'Advertencia',
				text: 'Debe llenar todos los campos del formulario',
				icon: 'warning',
				confirmButtonText: 'Ok',
				allowOutsideClick: false,
				showCloseButton: true
			});
		}
	}
	/* Funcion para Repuestos */
	function ValidarCheckListDep_1(categoria, check_1, codigo_1, check_2, codigo_2) {

		var seq_1 = document.getElementById('check' + check_1); //Repuestos
		var seq_2 = document.getElementById('check' + check_2); //Repuestos
		var seq_3 = document.getElementById('check3' + check_1); //Mano obra
		var seq_4 = document.getElementById('check3' + check_2); //Mano obra

		if (!seq_1.checked) {

			seq_2.checked = false;
			seq_3.checked = false;
			seq_4.checked = false;

			Swal.fire({
				title: 'Advertencia',
				text: 'Este repuesto es mandatorio, por lo tanto si no se realiza perderás la garantía de su vehículo, en caso de que aún cuente con garantía.',
				icon: 'warning',
				confirmButtonText: 'Ok',
				denyButtonText: 'Cancelar',
				showDenyButton: true,
				allowOutsideClick: false,
				showCloseButton: false
			}).then((result) => {
				if (result.isConfirmed) {
					document.getElementById('E' + codigo_1).innerText = 'No autorizado';
					document.getElementById('E' + codigo_2).innerText = 'No autorizado';
					document.getElementById('A' + codigo_1).innerText = 'No autorizado';
					document.getElementById('A' + codigo_2).innerText = 'No autorizado';
					checkList();
				} else if (result.isDenied) {
					if (seq_1.checked) {
						seq_1.checked = false;
						seq_2.checked = false;
						seq_3.checked = false;
						seq_4.checked = false;

					} else {
						seq_1.checked = true;
						seq_2.checked = true;
						seq_3.checked = true;
						seq_4.checked = true;
					}

				}
			});
		} else {
			document.getElementById('E' + codigo_1).innerText = 'Autorizado';
			document.getElementById('E' + codigo_2).innerText = 'Autorizado';
			document.getElementById('A' + codigo_1).innerText = 'Autorizado';
			document.getElementById('A' + codigo_2).innerText = 'Autorizado';

			seq_1.checked = true;
			seq_2.checked = true;
			seq_3.checked = true;
			seq_4.checked = true;

			checkList();
		}
	}
	/* Funcion para Mano de Obra */
	function ValidarCheckListDep_2(categoria, check_1, codigo_1, check_2, codigo_2) {
		var seq_3 = document.getElementById('check3' + check_1); //Mano obra
		var seq_4 = document.getElementById('check3' + check_2); //Mano obra
		var seq_1 = document.getElementById('check' + check_1); //Repuestos
		var seq_2 = document.getElementById('check' + check_2); //Repuestos
		if (!seq_3.checked) {
			seq_1.checked = false;
			seq_2.checked = false;
			seq_4.checked = false;

			Swal.fire({
				title: 'Advertencia',
				text: 'Este repuesto es mandatorio, por lo tanto si no se realiza perderás la garantía de su vehículo, en caso de que aún cuente con garantía.',
				icon: 'warning',
				confirmButtonText: 'Ok',
				denyButtonText: 'Cancelar',
				showDenyButton: true,
				allowOutsideClick: false,
				showCloseButton: false
			}).then((result) => {
				if (result.isConfirmed) {
					document.getElementById('E' + codigo_1).innerText = 'No autorizado';
					document.getElementById('E' + codigo_2).innerText = 'No autorizado';
					document.getElementById('A' + codigo_1).innerText = 'No autorizado';
					document.getElementById('A' + codigo_2).innerText = 'No autorizado';
					checkList();
				} else if (result.isDenied) {
					if (seq_3.checked) {
						seq_1.checked = false;
						seq_2.checked = false;
						seq_3.checked = false;
						seq_4.checked = false;

					} else {
						seq_1.checked = true;
						seq_2.checked = true;
						seq_3.checked = true;
						seq_4.checked = true;
					}

				}
			});
		} else {
			document.getElementById('E' + codigo_1).innerText = 'Autorizado';
			document.getElementById('E' + codigo_2).innerText = 'Autorizado';
			document.getElementById('A' + codigo_1).innerText = 'Autorizado';
			document.getElementById('A' + codigo_2).innerText = 'Autorizado';

			seq_1.checked = true;
			seq_2.checked = true;
			seq_3.checked = true;
			seq_4.checked = true;

			checkList();
		}
	}

	function ValidarCheckList3(categoria, check, codigo) {
		var checkV1 = document.getElementById('check' + check);
		var checkV3 = document.getElementById('check3' + check);
		if (!checkV3.checked) {
			checkV1.checked = false;
			Swal.fire({
				title: 'Advertencia',
				text: 'Este repuesto es mandatorio, por lo tanto si no se realiza perderás la garantía de su vehículo, en caso de que aún cuente con garantía.',
				icon: 'warning',
				confirmButtonText: 'Ok',
				denyButtonText: 'Cancelar',
				showDenyButton: true,
				allowOutsideClick: false,
				showCloseButton: false
			}).then((result) => {
				if (result.isConfirmed) {
					document.getElementById('E' + codigo).innerText = 'No autorizado';
					document.getElementById('A' + codigo).innerText = 'No autorizado';
					checkList();
				} else if (result.isDenied) {
					if (checkV1.checked) {
						checkV1.checked = false;
						checkV3.checked = false;

					} else {
						checkV1.checked = true;
						checkV3.checked = true;

					}

				}
			});
		} else {
			document.getElementById('E' + codigo).innerText = 'Autorizado';
			document.getElementById('A' + codigo).innerText = 'Autorizado';

			checkV1.checked = true;
			checkV3.checked = true;

			checkList();
		}

	}

	function ValidarCheckList(categoria, check, codigo) {
		var checkV1 = document.getElementById('check' + check);
		var checkV2 = document.getElementById(check); //
		var checkV3 = document.getElementById('check3' + check);
		if (categoria === 'MANDATORIO') {
			if (!checkV1.checked) {
				//IMPORTANTE VALIDAR SI VIENE O NO NULL
				if (checkV3 != null) {
					checkV3.checked = false;
				}
				Swal.fire({
					title: 'Advertencia',
					text: 'Este repuesto es mandatorio, por lo tanto si no se realiza perderás la garantía de su vehículo, en caso de que aún cuente con garantía.',
					icon: 'warning',
					confirmButtonText: 'Ok',
					denyButtonText: 'Cancelar',
					showDenyButton: true,
					allowOutsideClick: false,
					showCloseButton: false
				}).then((result) => {
					if (result.isConfirmed) {
						document.getElementById('E' + codigo).innerText = 'No autorizado';
						if (checkV3 != null) {
							document.getElementById('A' + codigo).innerText = 'No autorizado';
						}

						checkList();
					} else if (result.isDenied) {
						if (checkV1.checked) {
							checkV1.checked = false;
							//IMPORTANTE VALIDAR SI VIENE O NO NULL
							if (checkV3 != null) {
								checkV3.checked = false;
							}

						} else {
							checkV1.checked = true;
							if (checkV3 != null) {
								checkV3.checked = true;
							}

						}

					}
				});
			} else {
				document.getElementById('E' + codigo).innerText = 'Autorizado';
				checkV1.checked = true;
				if (checkV3 != null) {
					document.getElementById('A' + codigo).innerText = 'Autorizado';
					checkV3.checked = true;
				}
				checkList();
			}


		} else if (categoria === 'MANTENIMIENTO') {
			Swal.fire({
				title: 'Advertencia',
				text: 'El mantenimiento no se puede desmarcar',
				icon: 'warning',
				confirmButtonText: 'Ok',
				allowOutsideClick: false,
				showCloseButton: true
			});
			if (document.getElementById(check).checked) {
				document.getElementById(check).checked = false;
				document.getElementById('E' + codigo).innerText = 'No autorizado';
			} else {
				document.getElementById(check).checked = true;
				document.getElementById('E' + codigo).innerText = 'Autorizado';
			}
		} else if (categoria === 'AIRLIFE' || categoria === 'ALINEACION Y BALANCEO') {
			if (checkV2.checked === true) {
				document.getElementById('E' + codigo).innerText = 'Autorizado';
				checkList();
			} else if ((checkV2.checked === false)) {
				document.getElementById('E' + codigo).innerText = 'No autorizado';
				checkList();
			}
		} else {
			if (checkV1.checked === true) {
				document.getElementById('E' + codigo).innerText = 'Autorizado';
				checkList();
			} else if ((checkV1.checked === false)) {
				document.getElementById('E' + codigo).innerText = 'No autorizado';
				checkList();
			}
		}
	}

	function checkList() {
		/* Valores de repuestos */
		var array = $("[name='check']").map(function() {
			if (this.checked) {
				var rpto = document.getElementById('R' + this.value).innerText;
				console.log(rpto);
				var t = rpto.replaceAll('.', '');

				return parseInt(t);
			}
		}).get();

		var vsubTotal = 0;
		for (let index = 0; index < array.length; index++) {

			vsubTotal += parseInt(array[index]);

		}


		/* Valores de mano de obra */
		var array2 = $("[name='check2']").map(function() {
			if (this.checked) {
				var mtto = document.getElementById('V' + this.value).innerText;
				var t = mtto.replaceAll('.', '');
				mtto2 = parseInt(t)
				return mtto2;
				/* return parseInt(this.value); */
			}
		}).get();

		var MsubTotal = 0;
		for (let index = 0; index < array2.length; index++) {

			MsubTotal += array2[index];


		}



		/* Subtotal */
		var tSub = vsubTotal.toLocaleString('co-CO', {
			minimumFractionDigits: 0
		})
		document.getElementById('subTotal2').innerText = '$' + tSub;

		/* Total Horas Agenda */
		var arrayH = $("[name='check2']").map(function() {
			if (this.checked) {
				return this.value;
			}
		}).get();

		var datosHora = [];
		for (let index = 0; index < arrayH.length; index++) {
			datosHora.push($("#" + arrayH[index] + " td").map(function() {

				if (this.classList.value === "cantHorasAgenda") {
					return this.innerText;
				}
			}).get());

		}

		var sumaHoras = 0;
		for (let index = 0; index < datosHora.length; index++) {
			sumaHoras += parseFloat(datosHora[index]);
		}

		document.getElementById('tHorasAgenda').innerText = sumaHoras;

		/* Total */
		var suma = vsubTotal + MsubTotal;

		var mSub = suma.toLocaleString('co-CO', {
			minimumFractionDigits: 0
		})

		document.getElementById('totalM').innerText = '$' + (mSub);
	}

	function validarEmail(email) {
		expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (!expr.test(email)) {
			Swal.fire({
				title: 'Advertencia',
				text: "Error: La dirección de correo " + email + " es incorrecta.",
				icon: 'warning',
				confirmButtonText: 'Ok',
				allowOutsideClick: false,
				showCloseButton: true
			});
			document.getElementById('emailCliente').value = "";
		}

	}

	function guardarDatosInput(estado) {

		var placa = document.getElementById('placa').value;
		var clase = document.getElementById('clase').value;
		var desc = document.getElementById('desc').value;
		var descModelo = document.getElementById('descModelo').value;
		var kmC = document.getElementById('KmC').value;
		var kmA = document.getElementById('KmA').value; //Puede ser vacio
		var kmE = document.getElementById('KmE').value; //puede ser vacio
		var bodega = document.getElementById('bodega').value;
		var revision = document.getElementById('revision').value;
		var email = document.getElementById('emailCliente').value;
		var docCliente = document.getElementById('docCliente').value;
		var nomCliente = document.getElementById('nomCliente').value;
		var telCliente = document.getElementById('telfCliente').value;
		var obs = document.getElementById('obs').value; //Puede ser vacio

		var tablaInf = document.getElementById('tablaInfo').innerText;

		var datos = new FormData();
		datos.append('placa', placa);
		datos.append('clase', clase);
		datos.append('desc', desc);
		datos.append('descModelo', descModelo);
		datos.append('kmA', kmA);
		datos.append('kmE', kmE);
		datos.append('kmC', kmC);
		datos.append('bodega', bodega);
		datos.append('revision', revision);
		datos.append('email', email);
		datos.append('docCliente', docCliente);
		datos.append('nomCliente', nomCliente);
		datos.append('telCliente', telCliente);
		datos.append('obs', obs);
		datos.append('estado', estado);


		if (placa == "" || clase == "" || desc == "" || descModelo == "" || kmC == "" || bodega == "" || revision == "" || email == "" || docCliente == "" || nomCliente == "" || telCliente == "" || tablaInf == "") {
			Swal.fire({
				title: 'Advertencia',
				text: 'Debe llenar todos los campos del formulario',
				icon: 'warning',
				confirmButtonText: 'Ok',
				allowOutsideClick: false,
				showCloseButton: true
			});
		} else {
			/* Obtenemos datos chequeados */
			var arrayR = $("[name='check']").map(function() {
				return this.value;
				/* return (this.value); */
			}).get();

			var arrayM = $("[name='check2']").map(function() {
				/* if (this.checked) { */
				return this.value;
				/* } */
			}).get();

			/* Obtenemos los datos de los repuestos a guardar */
			datos.append('CantidadDatosRepuesto', arrayR.length);
			var datosRepuesto = [];
			for (let index = 0; index < arrayR.length; index++) {

				datosRepuesto.push($("#" + arrayR[index] + " td").map(function() {
					return this.innerText;
				}).get());
				datos.append('datosRepuesto' + index, datosRepuesto[index]);
			}

			/* Obtenemos los datos del Mantenimiento a guardar */
			datos.append('CantidadDatosMtto', arrayM.length);
			var datosMtto = [];
			for (let index = 0; index < arrayM.length; index++) {

				datosMtto.push($("#" + arrayM[index] + " td").map(function() {

					return this.innerText;

				}).get());
				datos.append('datosMtto' + index, datosMtto[index]);
			}

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			/* xmlhttp.responseType = 'json'; */
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					var resp = xmlhttp.responseText;
					if (resp != 'Error') {
						Swal.fire({
							title: 'Exito',
							text: 'Los datos de la cotización se han guardado correctamente',
							icon: 'success',
							confirmButtonText: 'Ok',
							allowOutsideClick: false,
							showCloseButton: false
						}).then((result) => {
							/* Confirmar*/
							if (result.isConfirmed) {
								if (estado == 1 || estado == 2 || estado == 0) {
									enviarEmailCotizacion(resp, estado);
								} else {
									document.location.reload();
								}
							}
						});
					} else if (resp === 'Error') {
						Swal.fire({
							title: 'Error',
							text: 'Hubo un error al momento de guardar la información, intente nuevamente.',
							icon: 'warning',
							confirmButtonText: 'Ok',
							allowOutsideClick: false,
							showCloseButton: false
						});
					}
				}
			}
			xmlhttp.open("POST", "<?= base_url() ?>Cotizador/guardarDatosCotizacion", true);
			xmlhttp.send(datos);

		}
	}

	function guardarDatosSelect(estado) {
		var descS = document.getElementById('descS').value;
		var descModeloS = document.getElementById('descModeloS').value;
		var placa = document.getElementById('placa').value;
		var clase = document.getElementById('clase').value;
		var kmC = document.getElementById('KmC').value;
		var bodega = document.getElementById('bodega').value;
		var revision = document.getElementById('revision').value;
		var email = document.getElementById('emailCliente').value;
		var docCliente = document.getElementById('docCliente').value;
		var nomCliente = document.getElementById('nomCliente').value;
		var telCliente = document.getElementById('telfCliente').value;
		var obs = document.getElementById('obs').value;
		var kmA = "";
		var kmE = "";
		var datos = new FormData();
		datos.append('placa', placa);
		datos.append('clase', clase);
		datos.append('desc', descS);
		datos.append('descModelo', descModeloS);
		datos.append('kmA', kmA);
		datos.append('kmE', kmE);
		datos.append('kmC', kmC);
		datos.append('bodega', bodega);
		datos.append('revision', revision);
		datos.append('email', email);
		datos.append('docCliente', docCliente);
		datos.append('nomCliente', nomCliente);
		datos.append('telCliente', telCliente);
		datos.append('obs', obs);
		datos.append('estado', estado);



		if (placa == "" || clase == "" || descS == "" || descModeloS == "" || kmC == "" || bodega == "" || revision == "" || docCliente == "" || nomCliente == "" || telCliente == "" || email == "") {
			Swal.fire({
				title: 'Advertencia',
				text: 'Debe llenar todos los campos del formulario',
				icon: 'warning',
				confirmButtonText: 'Ok',
				allowOutsideClick: false,
				showCloseButton: true
			});


		} else {
			/* Obtenemos datos chequeados */
			var arrayR = $("[name='check']").map(function() {

				return parseInt(this.value);

			}).get();
			var arrayM = $("[name='check2']").map(function() {

				return this.value;

			}).get();

			/* Obtenemos los datos de los repuestos a guardar */
			datos.append('CantidadDatosRepuesto', arrayR.length);
			var datosRepuesto = [];
			for (let index = 0; index < arrayR.length; index++) {

				datosRepuesto.push($("#" + arrayR[index] + ' td').map(function() {
					return this.innerText;
				}).get());
				datos.append('datosRepuesto' + index, datosRepuesto[index]);
			}

			/* Obtenemos los datos del Mantenimiento a guardar */
			datos.append('CantidadDatosMtto', arrayM.length);
			var datosMtto = [];
			for (let index = 0; index < arrayM.length; index++) {

				datosMtto.push($("#" + arrayM[index] + ' td').map(function() {

					return this.innerText;

				}).get());
				datos.append('datosMtto' + index, datosMtto[index]);
			}

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			/* xmlhttp.responseType = 'json'; */
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					var resp = xmlhttp.responseText;
					if (resp != 'Error') {
						Swal.fire({
							title: 'Exito',
							text: 'Los datos de la cotización se han guardado correctamente',
							icon: 'success',
							confirmButtonText: 'Ok',
							allowOutsideClick: false,
							showCloseButton: false
						}).then((result) => {
							/* Confirmar*/
							if (result.isConfirmed) {
								if (estado == 1 || estado == 2 || estado == 0) {
									enviarEmailCotizacion(resp, estado);
								} else {
									document.location.reload();
								}
							}
						});

					} else if (resp === 'Error') {
						Swal.fire({
							title: 'Error',
							text: 'Hubo un error al guardar los datos de la cotización, conctacte con un experto...',
							icon: 'warning',
							confirmButtonText: 'Ok',
							allowOutsideClick: false,
							showCloseButton: true
						});
					}
				}
			}
			xmlhttp.open("POST", "<?= base_url() ?>Cotizador/guardarDatosCotizacion", true);
			xmlhttp.send(datos);
		}
	}

	function verCotizacion(id, placa) {
		/* Creamos un elemento tipo form->Formulario con metodo post y accion */
		var mapForm = document.createElement("form");
		mapForm.target = "Cotizacion";
		mapForm.method = "POST";
		mapForm.action = "<?= base_url() ?>Cotizador/verPdfCotizacion";
		/* Creamos los input dentro del formulario creado anteriormente */
		var varId = document.createElement("input");
		varId.type = "hidden";
		varId.name = "id";
		varId.value = id;
		mapForm.appendChild(varId);

		var varPlaca = document.createElement("input");
		varPlaca.type = "hidden";
		varPlaca.name = "placa";
		varPlaca.value = placa;
		mapForm.appendChild(varPlaca);

		/* Agregamos el formulario creado al body */
		document.body.appendChild(mapForm);
		/* Script para abrir una nueva ventana */
		map = window.open("", "Cotizacion", "status=0,title=0,height=600,width=800,scrollbars=1");

		if (map) {
			mapForm.submit();
		}
	}

	function enviarEmailCotizacion(id, estado) {
		document.getElementById('cargando').style.display = 'block';
		var placa = document.getElementById('placa').value;

		var datos = new FormData();
		datos.append('id', id);
		datos.append('placa', placa);
		datos.append('estado', estado);
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		/* xmlhttp.responseType = 'json'; */
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				document.getElementById('cargando').style.display = 'none';
				var resp = xmlhttp.responseText;
				if (resp === 'Exito') {
					Swal.fire({
						title: 'Exito',
						text: 'Se ha enviado el correo con exito',
						icon: 'success',
						confirmButtonText: 'Ver cotizacion',
						denyButtonText: 'Cerrar',
						allowOutsideClick: false,
						showCloseButton: false,
						showDenyButton: true,
					}).then((result) => {
						/* Confirmar*/
						if (result.isConfirmed) {
							verCotizacion(id, placa);
							document.location.reload();
						} else if (result.isDenied) {
							document.location.reload();
						}
					});

				} else if (resp === 'Error') {
					Swal.fire({
						title: 'Error',
						text: 'Hubo un error al enviar el correo, por favor intente nuevamente!',
						icon: 'warning',
						confirmButtonText: 'Ok',
						allowOutsideClick: false,
						showCloseButton: false
					});

				}

			}
		}
		xmlhttp.open("POST", "<?= base_url() ?>Cotizador/enviarEmailCotizacion", true);
		xmlhttp.send(datos);
	}

	function cargarMttoPrepagado(placa) {
		var datos = new FormData();
		datos.append('placa', placa);
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		/* xmlhttp.responseType = 'json'; */
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				document.getElementById('mttoPre').value = xmlhttp.responseText;

			}
		}
		xmlhttp.open("POST", "<?= base_url() ?>Cotizador/mttoPrepagado", true);
		xmlhttp.send(datos);
	}

	function AddAdicionales() {
		let opcionMtto = document.getElementById('comboMtto_2').value;
		let selectAdicional = document.getElementById('AggAdicional').value;

		switch (true) {
			case (opcionMtto == ""):
				Swal.fire({
					title: 'Advertencia',
					text: 'Seleccione el tipo de mantenimiento',
					icon: 'warning',
					confirmButtonText: 'Ok',
					allowOutsideClick: false,
					showCloseButton: false
				});
				break;
			case (selectAdicional == ""):
				Swal.fire({
					title: 'Advertencia',
					text: 'Seleccione el adicional',
					icon: 'warning',
					confirmButtonText: 'Ok',
					allowOutsideClick: false,
					showCloseButton: false
				});
				break;
			case (selectAdicional != 'DIAGNOSTICO' && opcionMtto == 0):

				Swal.fire({
					title: 'Advertencia',
					text: 'Solo puedes agregar Diagnóstico como adicional, cuando el tipo de mantenimiento es "MTTO GARANTÍA"',
					icon: 'warning',
					confirmButtonText: 'Ok',
					allowOutsideClick: false,
					showCloseButton: false
				});
				break;

			default:
				cargarRepuestoAdicional(selectAdicional);
				/* cargarMttoAdicional(); */
				break;
		}
	}


	function cargarRepuestoAdicional(selectAdicional) {

		if (selectAdicional === 'DIAGNOSTICO') {
			document.getElementById('btnAggAdicional').style.display = 'none';
			document.getElementById('btnAggAdicionalH').style.display = 'none';
		} else if (selectAdicional === 'HIGIENIZACION') {
			document.getElementById('btnAggAdicional').style.display = 'none';
			document.getElementById('btnAggAdicionalH').style.display = 'block';
		} else {
			document.getElementById('btnAggAdicional').style.display = 'block';
			document.getElementById('btnAggAdicionalH').style.display = 'none';
		}

		var clase = document.getElementById('clase').value;
		var year = document.getElementById('year_model').value;
		var bodega = document.getElementById('bodega').value;
		if (clase != "" && year != "" && bodega != "") {
			document.getElementById('cargando').style.display = 'block';
			var dataRepuestosAd = new FormData();
			dataRepuestosAd.append('clase', clase);
			dataRepuestosAd.append('year', year);
			dataRepuestosAd.append('bodega', bodega);
			dataRepuestosAd.append('adicional', selectAdicional);

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			/* xmlhttp.responseType = 'json'; */
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {

					document.getElementById('tablaRepuestoAdicional').innerHTML = xmlhttp.responseText;
					$('#modalRAdicionales').modal('show');
					document.getElementById('cargando').style.display = 'none';
				}
			}
			xmlhttp.open("POST", "<?= base_url() ?>Cotizador/getRepuestosAdicionales", true);
			xmlhttp.send(dataRepuestosAd);

		} else {
			Swal.fire({
				title: 'Advertencia',
				text: 'Para cargar los repuestos adicionales debe llenar los campos de clase, año del modelo y seleccionar una bodega. ',
				icon: 'warning',
				confirmButtonText: 'Ok',
				allowOutsideClick: false,
				showCloseButton: false
			});
		}

	}

	function checkMtto(tipo) {
		if (tipo == 1) { //Mtto a la medida
			document.getElementById('btnCargarDatosCotizacion').disabled = true;
			document.getElementById('cardAdicionales').style.display = 'block';
			document.getElementById('tablaInfo').innerHTML = "";
			document.getElementById('bodyTableMAdicionales').innerHTML = "";
			document.getElementById('bodyTableRAdicionales').innerHTML = "";
			document.getElementById('btnGuardarMttoGarantia').style.display = 'none';
			document.getElementById('btnGuardarMttoMedida').style.display = 'block';
			let validarSelect = document.getElementById('descSelect').style.display;
			/* if(validarSelect == 'none'){

			} */

			$('#divObs').removeClass();
			$('#divObs').addClass('col-12 col-md-6 col-sm-6');
			$('#divTotal').css({
				"display": "block"
			});

			sumValoresRepAdicional();
		} else { //Mtto garantía
			document.getElementById('btnCargarDatosCotizacion').disabled = false;
			document.getElementById('cardAdicionales').style.display = 'none';
			document.getElementById('bodyTableMAdicionales').innerHTML = "";
			document.getElementById('bodyTableRAdicionales').innerHTML = "";
			document.getElementById('btnGuardarMttoGarantia').style.display = 'block';
			document.getElementById('btnGuardarMttoMedida').style.display = 'none';

			$('#divObs').removeClass();
			$('#divObs').addClass('col-12 col-md-12 col-sm-12');
			$('#divTotal').css({
				"display": "none"
			});

			sumValoresRepAdicional();
		}
	}

	function validateIsAdicionalExis() {
		let selectAdicional = document.getElementById('AggAdicional').value;

		var tAdicional = $(".tipo_adicional").map(function() {

			return this.innerText;

		}).get();

		return tAdicional.indexOf(selectAdicional);

	}

	function aggAdicionalCotizacion() {
		let selectAdicional = document.getElementById('AggAdicional').value;

		var tAdicional = $(".tipo_adicional").map(function() {

			return this.innerText;

		}).get();

		if (validateIsAdicionalExis() != -1) {
			Swal.fire({
				title: 'Advertencia',
				html: `El mantenimiento ${selectAdicional} ya se encuentra agregado a la cotización`,
				icon: 'warning',
				confirmButtonText: 'Ok',
				allowOutsideClick: false,
				showCloseButton: false
			});
		} else {
			$('#modalRAdicionales').modal('hide');
			aggAdicionalCotizacionRe();
			aggAdicionalCotizacionMa();
			sumValoresRepAdicional();
		}


	}

	function aggAdicionalCotizacionRe() {
		var clase = document.getElementById('clase').value;
		var year = document.getElementById('year_model').value;
		var bodega = document.getElementById('bodega').value;
		let selectAdicional = document.getElementById('AggAdicional').value;

		var bandera = $("#bodyTableRAdicionales tr");
		var idTrR = bandera.length;

		if (clase != "" && year != "" && bodega != "") {
			document.getElementById('cargando').style.display = 'block';
			var dataRepuestosAd = new FormData();
			dataRepuestosAd.append('clase', clase);
			dataRepuestosAd.append('year', year);
			dataRepuestosAd.append('bodega', bodega);
			dataRepuestosAd.append('adicional', selectAdicional);
			dataRepuestosAd.append('bandera', idTrR);

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			/* xmlhttp.responseType = 'json'; */
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					document.getElementById('bodyTableRAdicionales').innerHTML += xmlhttp.responseText;
				}
			}
			xmlhttp.open("POST", "<?= base_url() ?>Cotizador/cargarCotizacionDetalleAdicionalesR", false);
			xmlhttp.send(dataRepuestosAd);

		} else {
			Swal.fire({
				title: 'Advertencia',
				text: 'Para agregar los repuestos adicionales debe llenar los campos de clase, año del modelo y seleccionar una bodega. ',
				icon: 'warning',
				confirmButtonText: 'Ok',
				allowOutsideClick: false,
				showCloseButton: false
			});
		}
	}

	function aggAdicionalCotizacionMa() {

		var clase = document.getElementById('clase').value;
		var year = document.getElementById('year_model').value;
		var bodega = document.getElementById('bodega').value;
		let selectAdicional = document.getElementById('AggAdicional').value;
		var bandera = $("#bodyTableMAdicionales tr");
		var idTrM = 0;
		if (bandera.length != 0) {
			idTrM = bandera[bandera.length - 1].id;
		}
		if (clase != "" && year != "" && bodega != "") {
			var dataRepuestosAd = new FormData();
			dataRepuestosAd.append('clase', clase);
			dataRepuestosAd.append('year', year);
			dataRepuestosAd.append('bodega', bodega);
			dataRepuestosAd.append('adicional', selectAdicional);
			dataRepuestosAd.append('bandera', idTrM);

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			/* xmlhttp.responseType = 'json'; */
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					document.getElementById('bodyTableMAdicionales').innerHTML += xmlhttp.responseText;
					document.getElementById('cargando').style.display = 'none';
				}
			}
			xmlhttp.open("POST", "<?= base_url() ?>Cotizador/cargarCotizacionDetalleAdicionalesM", false);
			xmlhttp.send(dataRepuestosAd);

		} else {
			Swal.fire({
				title: 'Advertencia',
				text: 'Para agregar los repuestos adicionales debe llenar los campos de clase, año del modelo y seleccionar una bodega. ',
				icon: 'warning',
				confirmButtonText: 'Ok',
				allowOutsideClick: false,
				showCloseButton: false
			});
		}

	}

	function deleteAdicional(adicional) {
		var filas = $(`.${adicional}`);
		filas.remove();
		sumValoresRepAdicional();
	}

	function sumValoresRepAdicional() {
		//Script para sumar el valor de los repuestos
		var sumaValorAdicional = $(".valor_ad").map(function() {
			return parseInt(formatDeleteDots(this.innerText));
		}).get();
		let subTotalRepAdicionales = 0;
		for (let index = 0; index < sumaValorAdicional.length; index++) {
			subTotalRepAdicionales += sumaValorAdicional[index];
		}
		/* document.getElementById('sutTotalAdicionalRepuestos').innerText = formatAddDots(subTotalRepAdicionales); */
		//Script para sumar el valor de la mano de obra
		var sumaValorAdicionalM = $(".valor_ad_M").map(function() {
			return parseInt(formatDeleteDots(this.innerText));
		}).get();
		let subTotalMttoAdicionales = 0;
		for (let index = 0; index < sumaValorAdicionalM.length; index++) {
			subTotalMttoAdicionales += sumaValorAdicionalM[index];
		}
		/* document.getElementById('sutTotalAdicionalMtto').innerText = formatAddDots(subTotalMttoAdicionales); */
		//Script para sumar la cantidad de horas en el taller por mano de obra
		var sumaValorAdicionalH = $(".cantHorasAgenda_ad").map(function() {
			return parseInt(formatDeleteDots(this.innerText));
		}).get();
		let subTotalHorasAdicionales = 0;
		for (let index = 0; index < sumaValorAdicionalH.length; index++) {
			subTotalHorasAdicionales += sumaValorAdicionalH[index];
		}
		document.getElementById('sutTotalHorasAdicionalMtto').innerText = formatAddDots(subTotalHorasAdicionales);

		let subtotalRepuestosT = document.getElementById('subtotalRepuestosT');
		let subtotalManoT = document.getElementById('subtotalManoT');
		let totalCotizacion = document.getElementById('totalCotizacion');

		subtotalRepuestosT.innerText = formatAddDots(subTotalRepAdicionales);
		subtotalManoT.innerText = formatAddDots(subTotalMttoAdicionales);
		totalCotizacion.innerText = formatAddDots(subTotalRepAdicionales + subTotalMttoAdicionales);




	}


	function formatDeleteDots(numero) {
		return numero.replaceAll('.', '');
	}
	/* Funcion para agregar puntos a un numero */
	function formatAddDots(numero) {
		var num
		num = numero.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1.');
		return num = num.split('').reverse().join('').replace(/^[\.]/, '');
	}


	function addOperacionMttoAdicional(adicional, operacion) {
		let tipo = document.getElementById('comboMtto_2').value;
		if (tipo == 1) { //Mtto a la medida
			if (validateIsAdicionalExis() != -1) {
				Swal.fire({
					title: 'Advertencia',
					html: `El mantenimiento ${adicional} ya se encuentra agregado a la cotización`,
					icon: 'warning',
					confirmButtonText: 'Ok',
					allowOutsideClick: false,
					showCloseButton: false
				});
			} else {
				var clase = document.getElementById('clase').value;
				var year = document.getElementById('year_model').value;
				var bodega = document.getElementById('bodega').value;
				var bandera = $("#bodyTableMAdicionales tr");
				var idTrM = 0;
				if (bandera.length != 0) {
					idTrM = bandera[bandera.length - 1].id;
				}


				if (clase != "" && year != "" && bodega != "") {
					document.getElementById('cargando').style.display = 'block';
					var dataRepuestosAd = new FormData();
					dataRepuestosAd.append('clase', clase);
					dataRepuestosAd.append('year', year);
					dataRepuestosAd.append('bodega', bodega);
					dataRepuestosAd.append('adicional', adicional);
					dataRepuestosAd.append('operacion', operacion);
					dataRepuestosAd.append('bandera', idTrM);

					if (window.XMLHttpRequest) {
						xmlhttp = new XMLHttpRequest();
					} else {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					/* xmlhttp.responseType = 'json'; */
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
							document.getElementById('bodyTableMAdicionales').innerHTML += xmlhttp.responseText;
							sumValoresRepAdicional();
							document.getElementById('cargando').style.display = 'none';
						}
					}
					xmlhttp.open("POST", "<?= base_url() ?>Cotizador/cargarCotizacionDetalleAdicionalesM", false);
					xmlhttp.send(dataRepuestosAd);

				} else {
					Swal.fire({
						title: 'Advertencia',
						text: 'Para agregar los repuestos adicionales debe llenar los campos de clase, año del modelo y seleccionar una bodega. ',
						icon: 'warning',
						confirmButtonText: 'Ok',
						allowOutsideClick: false,
						showCloseButton: false
					});
				}
			}
		} else {
			let validate_adicional = validarSiExisteMttoAdicional(adicional);
			if (validate_adicional == -1) {

				var clase = document.getElementById('clase').value;
				var year = document.getElementById('year_model').value;
				var bodega = document.getElementById('bodega').value;
				let body = document.getElementById('tableCotizacion_body');

				if (clase != "" && year != "" && bodega != "" && body != null) {
					document.getElementById('cargando').style.display = 'block';
					var formAdicional = new FormData();
					formAdicional.append('clase', clase);
					formAdicional.append('year', year);
					formAdicional.append('bodega', bodega);
					formAdicional.append('adicional', adicional);
					formAdicional.append('operacion', operacion);

					if (window.XMLHttpRequest) {
						xmlhttp = new XMLHttpRequest();
					} else {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.responseType = 'json';
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
							const dataM = xmlhttp.response;
							/* 	adicional	clase	id	operacion	tiempo	costo */
							let numFila = body.rows.length;

							var fila = document.createElement("tr");
							fila.id = dataM['adicional'];

							var column_1 = document.createElement("td");
							column_1.classList.add(`Ad_Mtto`);
							column_1.appendChild(document.createTextNode(dataM['adicional']));
							fila.appendChild(column_1);

							var column_2 = document.createElement("td");

							var column_2 = document.createElement("td");
							column_2.setAttribute('colspan', 3);
							column_2.appendChild(document.createTextNode(dataM['operacion']));
							fila.appendChild(column_2);

							var column_3 = document.createElement("td");

							var column_3 = document.createElement("td");
							column_3.style.textAlign = 'center';
							column_3.classList.add('cantHorasAgenda');
							column_3.appendChild(document.createTextNode(dataM['tiempo']));
							fila.appendChild(column_3);

							var column_4 = document.createElement("td");

							var column_4 = document.createElement("td");
							column_4.id = `E${dataM['adicional']}`;
							column_4.appendChild(document.createTextNode('Autorizado'));
							fila.appendChild(column_4);

							var column_5 = document.createElement("td");

							var column_5 = document.createElement("td");
							column_5.id = `V${dataM['adicional']}`;
							column_5.classList.add('valor');
							column_5.appendChild(document.createTextNode(dataM['costo']));
							fila.appendChild(column_5);

							/* Script para crear el checkbox */
							let bandera_2 = parseInt(document.getElementById('bander_2').value);
							document.getElementById('bander_2').value = bandera_2 + 1;
							var column_6 = document.createElement("td");
							var inputCheck = document.createElement("input");
							inputCheck.id = `check2${(bandera_2 + 1)}`;
							inputCheck.name = 'check2';
							inputCheck.classList.add('check2');
							inputCheck.value = dataM['adicional'];
							inputCheck.type = 'checkbox';
							inputCheck.checked = true;
							inputCheck.setAttribute("onclick", `ValidarCheckListAdicionales("${dataM['adicional']}","check2${bandera_2 + 1}","${dataM['adicional']}");`);



							var column_6 = document.createElement("td");
							column_6.appendChild(inputCheck);
							fila.appendChild(column_6);

							body.appendChild(fila);

							checkList();

							document.getElementById('cargando').style.display = 'none';
						}
					}
					xmlhttp.open("POST", "<?= base_url() ?>Cotizador/cargarCotizacionDetalleM", true);
					xmlhttp.send(formAdicional);

				} else {
					Swal.fire({
						title: 'Advertencia',
						html: 'Para agregar los repuestos adicionales debe llenar los campos de clase, año del modelo y seleccionar una bodega.<br>Nota: Debe cargar primero la revisión, para poder agregar adicionales.',
						icon: 'warning',
						confirmButtonText: 'Ok',
						allowOutsideClick: false,
						showCloseButton: false
					});
				}
			} else {
				Swal.fire({
					title: 'Advertencia',
					html: `El mantenimiento ${adicional} ya se encuentra agregado a la cotización`,
					icon: 'warning',
					confirmButtonText: 'Ok',
					allowOutsideClick: false,
					showCloseButton: false
				});
			}
		}


	}

	function ValidarCheckListAdicionales(categoria, check, codigo) {
		var checkV1 = document.getElementById(check);
		if (checkV1.checked === true) {
			document.getElementById('E' + codigo).innerText = 'Autorizado';
			checkList();
		} else if ((checkV1.checked === false)) {
			document.getElementById('E' + codigo).innerText = 'No autorizado';
			checkList();
		}
	}

	function validarSiExisteMttoAdicional(adicional) {

		var arrayMtto = $(".Ad_Mtto").map(function() {
			return this.innerText;
		}).get();

		return arrayMtto.indexOf(adicional);
	}


	function guardarDatos_2(estado) {

		var placa = document.getElementById('placa').value;
		var clase = document.getElementById('clase').value;
		//Placa Registrada
		var desc = document.getElementById('desc').value;
		var descModelo = document.getElementById('descModelo').value;
		//Placa no registrada
		var descNo = document.getElementById('descS').value;
		var descModeloNo = document.getElementById('descModeloS').value;

		var kmC = document.getElementById('KmC').value;
		var kmA = document.getElementById('KmA').value; //Puede ser vacio
		var kmE = document.getElementById('KmE').value; //puede ser vacio
		var bodega = document.getElementById('bodega').value;
		var revision = document.getElementById('revision').value;
		var email = document.getElementById('emailCliente').value;
		var docCliente = document.getElementById('docCliente').value;
		var nomCliente = document.getElementById('nomCliente').value;
		var telCliente = document.getElementById('telfCliente').value;
		var obs = document.getElementById('obs').value; //Puede ser vacio.

		let descripcion = "";
		let modelo = "";

		if (descModelo != "") {
			modelo = descModelo;
		} else {
			modelo = descModeloNo;
		}

		if (desc != "") {
			descripcion = desc;
		} else {
			descripcion = descNo;
		}

		var datos = new FormData();
		datos.append('placa', placa);
		datos.append('clase', clase);
		datos.append('desc', descripcion);
		datos.append('descModelo', modelo);
		datos.append('kmA', kmA);
		datos.append('kmE', kmE);
		datos.append('kmC', kmC);
		datos.append('bodega', bodega);
		datos.append('revision', revision);
		datos.append('email', email);
		datos.append('docCliente', docCliente);
		datos.append('nomCliente', nomCliente);
		datos.append('telCliente', telCliente);
		datos.append('obs', obs);
		datos.append('estado', estado);

		/* Validar que las tablas no esten vacias */
		let tabla_R = document.getElementById('bodyTableRAdicionales').innerText;
		let tabla_M = document.getElementById('bodyTableMAdicionales').innerText;


		if (placa == "" || clase == "" || descripcion == "" || modelo == "" || kmC == "" || bodega == "" || email == "" || docCliente == "" || nomCliente == "" || telCliente == "" || (tabla_R == "" && tabla_M == "")) {
			Swal.fire({
				title: 'Advertencia',
				text: 'Debe llenar todos los campos del formulario',
				icon: 'warning',
				confirmButtonText: 'Ok',
				allowOutsideClick: false,
				showCloseButton: true
			});
		} else {
			/* Obtenemos datos chequeados */
			var arrayR_Ad = $("[name='fila_adicional_R']").map(function() {
				return this.id;
				/* return (this.value); */
			}).get();

			var arrayM_Ad = $("[name='fila_adicional_M']").map(function() {
				/* if (this.checked) { */
				return this.id;
				/* } */
			}).get();

			/* Obtenemos los datos de los repuestos a guardar */
			datos.append('CantidadDatosRepuesto', arrayR_Ad.length);
			var datosRepuesto_ad = [];
			for (let index = 0; index < arrayR_Ad.length; index++) {

				datosRepuesto_ad.push($("#" + arrayR_Ad[index] + " td").map(function() {
					return this.innerText;
				}).get());
				datos.append('datosRepuesto' + index, datosRepuesto_ad[index]);
			}

			/* Obtenemos los datos del Mantenimiento a guardar */
			datos.append('CantidadDatosMtto', arrayM_Ad.length);
			var datosMtto_ad = [];
			for (let index = 0; index < arrayM_Ad.length; index++) {

				datosMtto_ad.push($("#" + arrayM_Ad[index] + " td").map(function() {

					return this.innerText;

				}).get());
				datos.append('datosMtto' + index, datosMtto_ad[index]);
			}

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			/* xmlhttp.responseType = 'json'; */
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					var resp = xmlhttp.responseText;
					if (resp != 'Error') {
						Swal.fire({
							title: 'Exito',
							text: 'Los datos de la cotización se han guardado correctamente',
							icon: 'success',
							confirmButtonText: 'Ok',
							allowOutsideClick: false,
							showCloseButton: false
						}).then((result) => {
							/* Confirmar*/
							if (result.isConfirmed) {
								if (estado == 1 || estado == 2 || estado == 0) {
									enviarEmailCotizacion(resp, estado);
								} else {
									document.location.reload();
								}
							}
						});
					} else if (resp === 'Error') {
						Swal.fire({
							title: 'Error',
							text: 'Hubo un error al momento de guardar la información, intente nuevamente.',
							icon: 'warning',
							confirmButtonText: 'Ok',
							allowOutsideClick: false,
							showCloseButton: false
						});
					}
				}
			}
			xmlhttp.open("POST", "<?= base_url() ?>Cotizador/guardarDatosCotizacionAdicional", true);
			xmlhttp.send(datos);

		}
	}

	function checkHigienizador(inputRadio) {
		const checkboxR = document.getElementsByName(`${inputRadio.name}`);
		const checkboxT = document.getElementsByName(`TotH`);

		if (checkboxR.length == 2) {
			if (inputRadio.id == checkboxR[0].id) {
				if (inputRadio.checked == true) {
					checkboxR[1].checked = false;
				}
			} else {
				if (checkboxR[1].checked == true) {
					checkboxR[0].checked = false;
				}
			}
		}

		totalBeforeAddAdicional();
	}

	function totalBeforeAddAdicional() {
		let sumaT = 0;

		$('.filaRptos').map(function() {
			if (this.children[5].children[0].checked == true) {
				sumaT += Number.parseInt(formatDeleteDots(this.children[3].innerText));
			};
		});

		let totalA = document.getElementById('totalBeforeAddAdicional');

		totalA.innerText = `$${formatAddDots(sumaT)}`;

	}

	function aggAdicionalCotizacionH() {
		const adicional = document.getElementById('AggAdicional').selectedOptions[0].innerHTML;
		if (validateIsAdicionalExis() == -1) {
			const reptosSelect = $('.filaRptos').map(function() {
				if (this.children[5].children[0].checked == true) {
					return this;
				};
			}).get();


			const manosSelect = $('.filaMano').map(function() {
				return this;
			}).get();

			const tbodyReptos = document.getElementById('bodyTableRAdicionales');
			const tbodyMano = document.getElementById('bodyTableMAdicionales');

			var banderaR = $("#bodyTableRAdicionales tr");
			var idTrR = 0;

			if (banderaR.length != 0) {
				idTrR = banderaR[banderaR.length - 1].id;
			}

			var banderaM = $("#bodyTableMAdicionales tr");
			var idTrM = 0;

			if (banderaM.length != 0) {
				idTrM = banderaM[banderaM.length - 1].id;
			}
			if (reptosSelect.length > 0) {
				for (let i = 0; i < reptosSelect.length; i++) {

					let btnEliminar = "";

					if (i == 0) {
						btnEliminar = `<td style="vertical-align: middle !important;" rowspan="${reptosSelect.length}" scope="col" class="text-center">
					<button class="btn btn-danger" onclick="deleteAdicional('${adicional}');">Eliminar</button>
					</td>`;
					}

					let tagHtml = `<tr name="fila_adicional_R" class="HIGIENIZACION" id="ADR${idTrR}">
					<td scope="col">${reptosSelect[i].children[0].innerText}</td>
					<td scope="col">${reptosSelect[i].children[1].innerText}</td>
					<td style="text-align: center;" scope="col">${reptosSelect[i].children[2].innerText}</td>
					<td style="text-align: center;" scope="col">${reptosSelect[i].children[4].innerText}</td>
					<td scope="col" id="R_ad0" class="valor_ad text-right">${reptosSelect[i].children[3].innerText}</td>
					<td class="tipo_adicional d-none">${adicional}</td>
					${btnEliminar}
					</tr>`;
					tbodyReptos.insertAdjacentHTML('beforeend', tagHtml);
					idTrR++;
				}



			}
			if (manosSelect.length > 0) {

				for (let i = 0; i < manosSelect.length; i++) {

					let btnEliminar = "";

					if (i == 0) {
						btnEliminar = `<td style="vertical-align: middle !important;" rowspan="1" scope="col" class="text-center">
										<button class="btn btn-danger" onclick="deleteAdicional('${adicional}');">Eliminar</button>
										</td>`;
					}

					let tagHtml = `<tr name="fila_adicional_M" class="${adicional}" id="ADM_${idTrM}">
										<td scope="col" colspan="3">${manosSelect[i].children[0].innerText}</td>
										<td style="text-align: center;" scope="col" colspan="1" class="cantHorasAgenda_ad">${manosSelect[i].children[1].innerText}</td>
										<td scope="col" colspan="1" id="V${manosSelect[i].children[0].innerText}" class="valor_ad_M text-right">${manosSelect[i].children[2].innerText}</td>
										<td class="tipo_adicional d-none">${adicional}</td>
										${btnEliminar}
									</tr>`;
					tbodyMano.insertAdjacentHTML('beforeend', tagHtml);
					idTrM++;

				}



			}

			sumValoresRepAdicional();

		} else {
			Swal.fire({
				title: 'Advertencia',
				html: `El mantenimiento ${adicional} ya se encuentra agregado a la cotización`,
				icon: 'warning',
				confirmButtonText: 'Ok',
				allowOutsideClick: false,
				showCloseButton: false
			});
		}
	}

	function FnPosibleRetorno() {
		document.getElementById('placaPosibleRetorno').value = document.getElementById('placa').value;
		$('#modalPosibleRetorno').modal('show');
	}

	function aggPosibleRetorno() {

		const placa = document.getElementById('placaPosibleRetorno');
		const tipo_retorno = document.getElementById('tipoPosibleRetorno');
		const observacion = document.getElementById('obsPosibleRetorno');
		const bodega = document.getElementById('bodegaPosibleRetorno');


		const newspaperSpinning = [{
			background: "yellow"
		}, {
			background: "none"
		}];
		const newspaperTiming = {
			duration: 500,
			iterations: 5,
		};

		if (placa.value != "" && tipo_retorno.value > 0 && observacion.value != "" && observacion.value.length > 30 && bodega.value != "") {

			const formPosibleRetorno = new FormData();
			formPosibleRetorno.append("placa", placa.value);
			formPosibleRetorno.append("tipo_retorno", tipo_retorno.value);
			formPosibleRetorno.append("observacion", observacion.value);
			formPosibleRetorno.append("bodega", bodega.value);

			fetch("<?= base_url() ?>Posible_retorno/create_posible_retorno", {
					headers: {
						"Content-type": "application/json",
					},
					mode: 'no-cors',
					method: "POST",
					body: formPosibleRetorno,
				})
				.then(function(response) {
					return response.json();
				})
				.then(function(json) {
					console.log(json);
					if (json['response'] == 'success') {

						Swal.fire({
							title: 'Exito',
							html: `${json['msm']}`,
							icon: 'success',
							confirmButtonText: 'Ok'
						});

						placa.value = "";
						tipo_retorno.value = "";
						observacion.value = "";

						$('#modalPosibleRetorno').modal('hide');

					} else if (json['response'] == 'error') {
						Swal.fire({
							title: 'Error',
							html: `${json['msm']}`,
							icon: 'warning',
							confirmButtonText: 'Ok'
						});
					}
				})
				.catch(function(error) {
					Swal.fire({
						title: 'Error',
						html: `Ha ocurrido un error al realizar la peticion a la api de la Intranet de Posventa, intente nuevamente`,
						icon: 'warning',
						confirmButtonText: 'Ok'
					});
				});
		} else {
			Swal.fire({
				title: 'Advertencia',
				html: `Los siguientes campos se encuentran vacios:`,
				icon: 'warning',
				confirmButtonText: 'Ok',
				willClose: () => {
					if (placa.value == "") {
						placa.animate(newspaperSpinning, newspaperTiming);
						placa.focus();
					}
					if (tipo_retorno.value == "") {
						tipo_retorno.parentElement.children[2].children[0].children[0].children[0].animate(newspaperSpinning, newspaperTiming);
						tipo_retorno.parentElement.children[2].children[0].children[0].children[0].focus();
					}
					if (observacion.value == "" || observacion.length < 30) {
						observacion.animate(newspaperSpinning, newspaperTiming);
						observacion.focus();
					}
					if (bodega.value == "") {
						bodega.animate(newspaperSpinning, newspaperTiming);
						bodega.focus();
					}
				}
			});
		}
	}
</script>