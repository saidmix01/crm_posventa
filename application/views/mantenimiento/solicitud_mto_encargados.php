<!-- VISTA SOLICITUD DE MANTENIMIENTO PARA EL PERFIL DE MANTENIMIENTO ****************************************************** -->
<?php $this->load->view('mantenimiento/header') ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<br>
	<!-- Main content -->
	<section class="content">
		<div class="card">
			<div class="card-header" align="center">
				<h3><strong>SOLICITUDES DE MANTENIMIENTO</strong></h3>
			</div>
			<div class="card-body">
				<div class="row">
					<?php
					if ($perfil != 46) {
						echo '
							<div class="col-3">
								<a href="#" class="btn btn-success btn-lg" data-toggle="modal" data-target="#modal_solicitud" onclick="new_solicitud();"><i class="fas fa-file"></i> Nueva Solicitud</a>
							</div> 
							';
					}
					?>

					<div class="col">
						<div class="row">
							<div class="col-md-4">
								<div class="icheck-success d-inline">
									<input type="radio" id="radioPrimary1" name="r1" checked="">
									<label for="radioPrimary1">Urgencia 1</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="icheck-warning d-inline">
									<input type="radio" id="radioPrimary2" name="r2" checked="">
									<label for="radioPrimary1">Urgencia 2</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="icheck-danger d-inline">
									<input type="radio" id="radioPrimary3" name="r3" checked="">
									<label for="radioPrimary1">Urgencia 3</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 container table-responsive">
						<table class="table table-hover" id="tab_soli">
							<?php if ($perfil == 1 || $perfil == 46 || $perfil == 20 || $perfil == 26) {
								echo '<button type="button" id="nuevoEquipo" class="btn btn-success float-right " onclick="bajar_excel_tabla_uno()">
									Descargar Excel &nbsp; <span><i class="fas fa-file-download"></i></span>
								</button>';
							} ?>
							<thead>
								<tr align="center">
									<th scope="col">#</th>
									<th scope="col">Codigo Equipo</th>
									<th scope="col">Solicitud</th>
									<th scope="col">Estado</th>
									<th scope="col">Urgencia</th>
									<th scope="col">Sede</th>
									<th scope="col">Jefe</th>
									<th scope="col">Encargado</th>
									<th scope="col">Fecha de Inicio</th>
									<th scope="col">Fecha de Finalización</th>
									<th scope="col">Dias en Gestion</th>
								</tr>
							</thead>
							<tbody id="tabla_solicitudes" align="center">

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>

<!-- Modal ver Solicitud de Mantenimiento-->
<div class="modal fade" id="modal_au_ver" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Solicitud de Mantenimiento</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="load_event">

			</div>
		</div>
	</div>
</div>
<!-- Modal Nueva Solicitud de Mantenimiento-->
<div class="modal fade" id="modal_au" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Nueva Solicitud de Mantenimiento</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="loader" id="cargando"></div>
				<form action="<?= base_url() ?>mantenimiento/new_solicitud" method="POST" onsubmit="return document.getElementById('cargando').style.display='block';" enctype="multipart/form-data" role="form">
					<div class="card-body">
						<div class="row">
							<div class="col-12 col-md-6 col-sm-6">
								<div class="form-group">
									<label for="cargo">Seleccione el equipo</label>
									<select style="width:100%" class="form-control js-example-basic-single" required="false" id="equipoId" name="equipoId">
										<option></option>
										<option value="N/A">LOCATIVO</option>
										<?php
										foreach ($equiposMto->result() as $key) {
										?>
											<option value="<?= $key->id_equipo ?>"><?= $key->codigo . "--" . $key->nombre_equipo ?></option>
										<?php
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-12 col-md-6 col-sm-6">
								<div class="form-group">
									<label for="cargo">Seleccionar Sede o Bodega</label>
									<select class="form-control" id="sedeBodega" name="sedeBodega" required>
										<?php foreach ($bodegas->result() as $bodega) {	?>
											<option value="<?= $bodega->bodega ?>"><?= $bodega->descripcion ?></option>
										<?php	}	?>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-6">
								<div class="form-group">
									<label>Nivel de urgencia del Mantenimiento: siendo 3 más urgente</label>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="urgencia" id="inlineRadio1" value="1" required="true">
										<label class="form-check-label" for="inlineRadio1">1</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="urgencia" id="inlineRadio2" value="2">
										<label class="form-check-label" for="inlineRadio2">2</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="urgencia" id="inlineRadio3" value="3">
										<label class="form-check-label" for="inlineRadio2">3</label>
									</div>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group col-md-12">
									<label>Cargar un archivo</label>
									<input type="file" class="form-control-file" name="archivo" id="archivo">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="">Descripcion de la solicitud</label>
									<textarea class="form-control" minlength="15" name="solicitud" id="solicitud" required="true"></textarea>
								</div>
							</div>
						</div>

					</div>
					<!-- /.card-body -->
					<div class="modal-footer">
						<button class="btn btn-success">Agregar</button>
						<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cerrar</button>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>

<!-- Modal Mensajes o conversacion en la solicitud de mantenimiento-->
<div class="modal fade" id="modal_msn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="titulo_modal">Mensajes</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php
				foreach ($userdata->result() as $key) {
					$emisor = $key->nombres;
					$nit = $key->nit_usuario;
				}



				echo '
									<form>
										<div class="form-row">
											<div class="col">
												<label>' . $emisor . ' DICE...</label>
												<textarea class="form-control" rows="2" id="new_msm" name="new_msm"></textarea>
											</div>
										</div>
										<br>				  
									</form>
									<div class="form-row">
									<div class="col">
										<a class="btn btn-success btn-sm" id="btn_msn" onclick="enviar_msn();">Enviar</a>
									</div>
									</div>
									<hr>
									';



				?>
				<input type="hidden" name="nit" id="nit" value="<?= $nit ?>">
				<div class="table-responsive" id="tabla_msn">

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
<!--Modal para finalizar la solicitud de mantenimiento-->
<div class="modal fade" id="modal_finalizar_evento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="titulo_modal">Respuesta</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="<?= base_url() ?>mantenimiento/finalizar_solicitud" method="POST" enctype="multipart/form-data" role="form">
				<div class="modal-body">

					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="cargo">Respuesta</label>
								<textarea class="form-control" minlength="15" name="respuesta_solicitud" id="respuesta_solicitud" placeholder="Escriba la solución" required="true"></textarea>

							</div>
						</div>
						<div class="col-6">
							<div class="form-group col-md-12">
								<label>Cargar un archivo</label>
								<input type="file" class="form-control-file" name="archivo-resp" id="archivo-resp">
							</div>
						</div>
					</div>
					<input type="hidden" name="id_soli_f" id="id_soli_f">
					<input type="hidden" name="nit" id="nit" value="<?= $nit ?>">

				</div>
				<div class="modal-footer">
					<button class="btn btn-warning btn-flat ">Finalizar</button>
					<!-- <button type="button" id="btn_finalizar" class="btn btn-warning btn-flat" onclick="finalizar_evento();">Finalizar</button> -->
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!--Modal para cambiar el codigo del equipo en la solicitud de mantenimiento-->
<div class="modal fade" id="udpate_codigo" tabindex="-1" role="dialog" aria-labelledby="udpate_codigo" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="udpate_codigo_title">Actualizar código</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form>
					<div class="form-group">
						<label for="id_solicitud_up" class="col-form-label">Id solicitud</label>
						<input type="number" class="form-control" name="id_solicitud_up" id="id_solicitud_up" readonly disabled>
					</div>
					<div class="form-group">
						<label for="codigoUpdate" class="col-form-label">Código:</label>
						<select style="width:100%" class="form-control" required="false" id="equipoIdUp" name="equipoIdUp">
							<option value="NULL">N/A</option>
							<?php
							foreach ($equiposMto->result() as $key) {
							?>
								<option value="<?= $key->id_equipo ?>"><?= $key->codigo . "--" . $key->nombre_equipo ?></option>
							<?php
							}
							?>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="updateCodigoAjax();">Actualizar</button>
			</div>
		</div>
	</div>
</div>


<?php $this->load->view('mantenimiento/footer') ?>
<?php
$log = $this->input->get('log');
if ($log == "ok") {
?>
	<div class="alert alert-success alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
		Solicitud de mantenimiento finalizada exitosamente
		<button onclick="location.href='solicitud_mantenimieto';" type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php
} elseif ($log == "n_msm") { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
		Se ha agregado un nuevo mensaje a la solicitud
		<button onclick="location.href='solicitud_mantenimieto';" type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php } elseif ($log == "f_msm") { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
		El mensaje no ha sido guardado...
		<button onclick="location.href='solicitud_mantenimieto';" type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php } elseif ($log == "use") { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
		El usuario no tiene permiso para finalizar la solicitud
		<button onclick="location.href='solicitud_mantenimieto';" type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php } elseif ($log == "err") { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
		Error inesperado al finalizar la solicitud
		<button onclick="location.href='solicitud_mantenimieto';" type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php }
?>
?>
<script>
	/* Sergio Galvis
	23/05/2022 */
	function bajar_excel_tabla_uno() {
		$("#tab_soli").table2excel({
			exclude: ".noExl",
			name: "Worksheet Name",
			filename: "Mantenimientos-correctivos-" + "-" + fecha, //do not include extension
			fileext: ".xlsx" // file extension
		});
	}
	/* Cargar a detalle la solicitud de mantenimiento */
	function ver_evento(id) {
		console.log(id);
		var result = document.getElementById("load_event");
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
		xmlhttp.open("GET", "<?= base_url() ?>mantenimiento/ver_info_evento_encargado?id=" + id, true);
		xmlhttp.send();
		$('#modal_au_ver').modal('show'); //mostrar el modal de ver solicitud
	}
	/* Iniciar la solicitud de mantenimiento */
	function iniciar_evento() {
		var tiempoEstimado = document.getElementById("tiempoEstimado").value;

		if (tiempoEstimado != "") {
			var f_inicio = document.getElementById("fecha_inicio").value;
			var encargado = document.getElementById("encargado").value;
			var id = document.getElementById("id_solicitud").value;
			if (!encargado == "") {
				Toast.fire({
					type: 'error',
					title: 'El campo encargado ya se encuentra actualizado'
				});
			} else {
				var xmlhttp;

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						var flag = xmlhttp.responseText;
						if (flag == "err") {
							Toast.fire({
								type: 'error',
								title: ' Error al actualizar la solicitud de mantenimiento'
							});
						} else if (flag == "ok") {
							Toast.fire({
								type: 'success',
								title: ' Solicitud actualizada exitosamente'
							});
							//document.getElementById("cargando").style.display = "none";
							$('#modal_au').modal('hide');

							location.reload();
							//result.innerHTML = xmlhttp.responseText;
						}
						//result.innerHTML = xmlhttp.responseText;

					}
				}

				xmlhttp.open("GET", "<?= base_url() ?>mantenimiento/iniciar_solicitud?id_solicitud=" + id + "&fecha_inicio=" + f_inicio + "&encargado=" + encargado + "&tiempoEstimado=" + tiempoEstimado, true);
				console.log(xmlhttp);

				xmlhttp.send();


			}
		} else {
			Toast.fire({
				type: 'error',
				title: 'Debe digitar o agregar un tiempo estimado el cual va a demorar el mantenimiento.'
			});
		}


	}

	function modal_finalizar_evento(id) {
		$('#modal_finalizar_evento').modal('show'); //mostrar el modal de crear solicitud
		document.getElementById("id_soli_f").value = id;

	}
	/* Finalizar la solicitud de mantenimiento */
	function finalizar_evento() {
		var f_inicio = document.getElementById("fecha_inicio").value;
		var f_final = document.getElementById("fecha_finalizacion").value;
		var respuesta = document.getElementById("respuesta_solicitud").value;
		var encargado = document.getElementById("encargado").value;
		var encargadoNit = document.getElementById("encargadoNit").value;
		var id = document.getElementById("id_solicitud").value;
		var nit = document.getElementById("nit").value;

		console.log(f_inicio, "--", f_final, "--", respuesta, "--", encargado, "--", encargadoNit, "--", id, "-", nit);

		if (!f_inicio == "") {
			if (respuesta == "") {
				Toast.fire({
					type: 'error',
					title: 'El campo respuesta no puede estar vacio'
				});
			} else {
				var xmlhttp;

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						var flag = xmlhttp.responseText;
						if (flag == "err") {
							Toast.fire({
								type: 'error',
								title: ' Error al finalizar la solicitud de mantenimiento: Debes agregar un mensaje!'
							});
						} else if (flag == "ok") {
							Toast.fire({
								type: 'success',
								title: ' Solicitud finalizada exitosamente'
							});
							//document.getElementById("cargando").style.display = "none";
							$('#modal_au').modal('hide');

							location.reload();
							//result.innerHTML = xmlhttp.responseText;
						} else if (flag == "use") {
							Toast.fire({
								type: 'error',
								title: ' Error la solicitud ha sido iniciada por otro usuario'
							});
						}
						//result.innerHTML = xmlhttp.responseText;

					}
				}

				xmlhttp.open("GET", "<?= base_url() ?>mantenimiento/finalizar_solicitud?id_solicitud=" + id + "&fecha_finalizacion=" + f_final + "&respuesta=" + respuesta + "&encargado=" + encargado + "&encargadoNit=" + encargadoNit, true);
				console.log(xmlhttp);

				xmlhttp.send();


			}
		} else {
			Toast.fire({
				type: 'error',
				title: 'No puedes finalizar la solicitud sin haber iniciado'
			});
		}


	}
	/* Crear nuevas solicitudes de mantenimiento en el calendario */
	function crear_evento() {
		var fecha_solicitud = document.getElementById("fecha_solicitud").value;
		var solicitud = document.getElementById("solicitud").value;
		if (fecha_solicitud == "" || solicitud == "") {
			Toast.fire({
				type: 'error',
				title: 'Todos los campos deben ser completados'
			});
		} else if (solicitud.length < 15) {
			Toast.fire({
				type: 'error',
				title: 'El campo solicitud debe tener como minimo 15 caracteres'
			});
		} else {
			var xmlhttp;
			document.getElementById("cargando").style.display = "block";
			document.getElementById("btn_agregar").disabled = "true";
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					var flag = xmlhttp.responseText;
					if (flag == "err") {
						Toast.fire({
							type: 'error',
							title: ' Error al crear la solicitud de mantenimiento'
						});
					} else if (flag == "ok") {
						Toast.fire({
							type: 'success',
							title: ' Solicitud creada exitosamente'
						});
						document.getElementById("cargando").style.display = "none";
						$('#modal_au').modal('hide');

						location.reload();
						//result.innerHTML = xmlhttp.responseText;
					}
					//result.innerHTML = xmlhttp.responseText;

				}
			}

			xmlhttp.open("GET", "<?= base_url() ?>mantenimiento/new_solicitud?fecha_solicitud=" + fecha_solicitud +
				"&solicitud=" + solicitud, true);
			xmlhttp.send();


		}

	}

	function modal_msn(id) {
		$('#modal_msn').modal('toggle');
		console.log(id);
		load_tabla_msn_soli(id);
	}

	function load_tabla_msn_soli(id) {
		var result = document.getElementById("tabla_msn");
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
		xmlhttp.open("GET", "<?= base_url() ?>mantenimiento/load_msn_solicitudes?id_soli=" + id, true);
		xmlhttp.send();
	}

	function enviar_msn() {
		var msn = document.getElementById('new_msm').value;
		var id_soli = document.getElementById('id_prueba').value;
		var jefe_s = document.getElementById('jefe_s').value;
		var encargado_s = document.getElementById('encargado_s').value;
		var f_soli = document.getElementById('f_soli').value;
		var f_inicio = document.getElementById('f_inicio').value;
		var f_final = document.getElementById('f_final').value;
		var estado = document.getElementById('estado').value;
		var nit = document.getElementById('nit').value;

		if (msn != "") {
			if (f_final == "") {
				if (f_inicio != "") {
					if (nit == jefe_s || nit == encargado_s) {
						var xmlhttp;
						if (window.XMLHttpRequest) {
							xmlhttp = new XMLHttpRequest();
						} else {
							xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
						}
						xmlhttp.onreadystatechange = function() {
							if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
								var resp = xmlhttp.responseText;
								if (resp == "ok") {

									load_tabla_msn_soli(id_soli);
								} else {
									Swal.fire({
										title: 'Error!',
										text: 'Mensaje No Enviado',
										icon: 'error',
										confirmButtonText: 'Ok'
									});
								}
							}
						}
						xmlhttp.open("GET", "<?= base_url() ?>mantenimiento/agregar_new_msm?msn=" + msn + "&id_soli_msn=" + id_soli, true);
						xmlhttp.send();
						document.getElementById("new_msm").value = "";
					} else {
						Swal.fire({
							title: 'Advertencia!',
							text: 'No estas autorizado para enviar mensajes en esta solicitud',
							icon: 'warning',
							confirmButtonText: 'Ok'
						});
					}
				} else {
					Swal.fire({
						title: 'Advertencia!',
						text: 'La solicitud no ha sido iniciada',
						icon: 'warning',
						confirmButtonText: 'Ok'
					});
				}
			} else {
				Swal.fire({
					title: 'Advertencia!',
					text: 'La solicitud ha sido finalizada',
					icon: 'warning',
					confirmButtonText: 'Ok'
				});
			}
		} else {
			Swal.fire({
				title: 'Advertencia!',
				text: 'Hay Campos Vacios',
				icon: 'warning',
				confirmButtonText: 'Ok'
			});

		}



	}

	function new_solicitud() {
		$('#modal_au').modal('show'); //mostrar el modal de crear solicitud
	}

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
			width: 'resolve'
		});

		load_tabla_solicitudesMto();


	});

	function load_tabla_solicitudesMto() {
		document.getElementById('cargando').style.display = 'block';
		$('#tab_soli').dataTable().fnDestroy();
		var result = document.getElementById("tabla_solicitudes");
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				result.innerHTML = xmlhttp.responseText;
				load_datatable();
				document.getElementById('cargando').style.display = 'none';
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>mantenimiento/getSolicitudesMto", true);
		xmlhttp.send();
	}

	function load_datatable() {
		$('#tab_soli').DataTable({
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
	}

	function updateCodigo(id, id_equipo, codigo) {

		$("#equipoIdUp option[value=" + id_equipo + "]").attr("selected", true);

		$("#equipoIdUp").addClass("js-example-basic-single-up");

		$('.js-example-basic-single-up').select2({
			theme: "classic",
			width: 'resolve',
		});

		document.getElementById('id_solicitud_up').value = id;

		$('#udpate_codigo').modal('show');
	}

	function updateCodigoAjax() {
		const id_solicitud_mtto = document.getElementById('id_solicitud_up').value;
		const id_equipo_mtto = document.getElementById('equipoIdUp').value;
		let form = new FormData();
		form.append('id_mtto', id_solicitud_mtto);
		form.append('id_equipo', id_equipo_mtto);
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				if (xmlhttp.responseText == 1) {
					location.reload();
				} else {
					Swal.fire({
						title: 'Error!',
						text: 'Ha ocurrido un error, y no se pudo realizar la actualización del codigo del equipo',
						icon: 'warning',
						confirmButtonText: 'Ok'
					});
				}

			}
		}
		xmlhttp.open("POST", "<?= base_url() ?>mantenimiento/UpdateCodigoMttoCorrectivo", true);
		xmlhttp.send(form);

	}
</script>
<!-- DataTables -->
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- Estilo para mostrar el nombre completo de la solicitud en el calendario:: -->
<style>
	.fc-daygrid-event {
		white-space: normal;
	}

	#circulo {
		width: 22px;
		height: 22px;
		border-radius: 50%;
		content: "";
		display: inline-block;
		position: absolute;
		width: 26px;
		height: 26px;
		border: 1px solid #D3CFC8;
		margin-left: -29px
	}
</style>