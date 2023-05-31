<?php $this->load->view('mantenimiento/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<br>
	<!-- Main content -->
	<section class="content">
		<div class="card">
			<div class="card-header" align="center">
				<h3><strong>Plan o Cronograma de Mantenimiento Preventivo</strong></h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-3">
						<?php echo $cargarArchivoPreMto; ?>
					</div>
					<div class="col-3">
						<?php echo $descargar; ?>
						<!-- Boton de descargar plantilla..... :D XD XD -->
					</div>
					<div class="col-3">
						<?php echo $tablaMtoPrev; ?><!-- Enlace para ver listado en tablas los mantenimientos preventivos -->
						<!-- Boton de descargar plantilla..... :D XD XD -->
					</div>
				</div>
				<div class="row">
					<div id="calendar"></div>
				</div>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>
<!-- Modal ver detalle de mantenimiento-->
<div class="modal fade" id="modal_detalle_mto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Orden de Mantenimiento Preventivo</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div id="OrdenMantenimientoId"></div>
		</div>
	</div>
</div>
<!-- Modal cargar archivo plan de mantenimiento preventivo-->
<div class="modal" id="modal_archivo_mto" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Cargar plan de mantenimiento</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input type="file" id="archivoPlan">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="cargarExcel();">Cargar</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
			<div class="loader" id="cargando"></div>
		</div>
	</div>
</div>

<?php $this->load->view('mantenimiento/footer') ?>
<script>
	/* Cargar información de los mantenimientos preventivos en el calendario */
	document.addEventListener('DOMContentLoaded', function() {
		var calendarEl = document.getElementById('calendar');
		//import interactionPlugin from '@fullcalendar/interaction';
		var calendar = new FullCalendar.Calendar(calendarEl, {
			initialView: 'dayGridMonth',
			events: <?= $mtoPreventivo ?>,
			eventClick: function(arg) {
				ver_orden_mto(arg.event.id);
				$('#modal_detalle_mto').modal('show');
			},
			headerToolbar: {
				left: 'prev,next,today',
				center: 'title',
				right: 'dayGridMonth,listWeek'
			}

		});
		calendar.setOption('aspectRatio', 1.8);
		calendar.setOption('locale', 'Es');
		calendar.render();
	});
	/* Ver información detallada del mantenimiento preventivo segun la planeacion o cronograma */
	function ver_orden_mto(id) {
		var respuesta = document.getElementById('OrdenMantenimientoId');
		var xmlhttp1;
		if (window.XMLHttpRequest) {
			xmlhttp1 = new XMLHttpRequest();
		} else {
			xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp1.onreadystatechange = function() {
			if (xmlhttp1.readyState === 4 && xmlhttp1.status === 200) {
				var flag1 = xmlhttp1.response;
				if (flag1 == "") {
					Toast.fire({
						type: 'error',
						title: ' No hay datos'
					});
				} else {
					respuesta.innerHTML = xmlhttp1.responseText;
				}
			}
		}
		xmlhttp1.open("GET", "<?= base_url() ?>mantenimiento/pintarDatosCorrectivoById?id=" + id, true);
		xmlhttp1.send();
	}
	/* Script para Iniciar la orden de mantenimiento Preventivo */
	function iniciar_orden(id) {
		var id_equipo = document.getElementById('idEquipo').value;
		var asignado = document.getElementById("asignadoMp").value;
		var xmlhttp;
		if (asignado == "*") {
			Toast.fire({
				type: 'error',
				title: 'Debe seleccionar un asignado para iniciar el mantenimiento preventivo'
			});
		} else {
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
							title: ' Error al iniciar el mantenimiento preventivo'
						});
					} else if (flag == "ok") {
						Toast.fire({
							type: 'success',
							title: 'Mantenimiento preventivo iniciado'
						});
						$('#modal_detalle_mto').modal('hide');

						location.reload();
					}
				}
			}

			xmlhttp.open("GET", "<?= base_url() ?>mantenimiento/iniciar_orden?id=" + id + "&asignado=" + asignado + "&id_equipo=" + id_equipo, true);
			console.log(xmlhttp);

			xmlhttp.send();

		}



	}
	/* Script para finalizar la orden de mantenimiento Preventivo */
	function finalizar_orden(id) {
		var observarMp = document.getElementById("observarMp").value;
		var piezasMp = document.getElementById("piezasMp").value;
		var id_equipo = document.getElementById('idEquipo').value;
		if (observarMp == "" || piezasMp == "") {
			Toast.fire({
				type: 'error',
				title: 'Debe llenar los campos de Observacion y detalle de piezas para poder finalizar el mantenimiento...'
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
							title: ' Error al finalizar la orden de mantenimiento'
						});

					} else if (flag == "ok") {
						Toast.fire({
							type: 'success',
							title: ' Orden de mantenimiento finalizada exitosamente'
						});
						$('#modal_detalle_mto').modal('hide');

						location.reload();

					} else {
						Toast.fire({
							type: 'error',
							title: ' Error inesperado al finalizar la orden, recargue la página e intenete nuevamente... evite agregar el caracter numeral (#)'

						});
					}

				}
			}

			xmlhttp.open("GET", "<?= base_url() ?>mantenimiento/finalizar_orden?id=" + id + "&observarMp=" + observarMp + "&piezasMp=" + piezasMp + "&id_equipo=" + id_equipo, true);
			console.log(xmlhttp);

			xmlhttp.send();


		}

	}

	function cargarExcel(tabla) {
		var archivo = document.getElementById("archivoPlan").value;
		if (archivo.length == 0) {
			Swal.fire({
				title: 'Advertencia!',
				text: 'Por favor seleccione un archivo',
				icon: 'warning',
				confirmButtonText: 'Ok'
			});
			document.getElementById("archivoPlan").value = "";
		} else {
			document.getElementById("cargando").style.display = "block";
			let formData = new FormData();
			let excel = $("#archivoPlan")[0].files[0];
			formData.append('excel', excel);
			formData.append('cert', tabla);
			$.ajax({
				url: "<?= base_url() ?>mantenimiento/upload_data",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function(data) {
					console.log(typeof data);
					var data_resp = JSON.parse(data);
					console.log(data_resp["ok"]);
					document.getElementById("cargando").style.display = "none";
					if (data_resp["ok"] != 0 && data_resp["err_db"] == 0) {

						Swal.fire({
							title: 'Excelente!',
							text: 'Los datos se cargaron exitosamente, se insertaron ' + data_resp['ok'] + ' datos',
							icon: 'success',
							confirmButtonText: 'Ok'
						}).then((result) => {
							if (result.isConfirmed) {
								window.location = "<?= base_url() ?>mantenimiento/getCronograma";
								location.reload();
							}
						});
					} else if (data_resp["ok"] != 0 && data_resp["err_db"] != 0) {

						Swal.fire({
							title: 'Advertencia!',
							text: 'Se insertaron ' + data_resp['ok'] + ', y faltaron ' + data_resp['err_db'] + ' por insertar.',
							icon: 'warning',
							confirmButtonText: 'Ok'

						}).then((result) => {
							if (result.isConfirmed) {
								window.location = "<?= base_url() ?>mantenimiento/getCronograma";
								location.reload();
							}
						});
					} else if (data_resp["ok"] == 0 && data_resp["err_db"] != 0) {

						Swal.fire({
							title: 'Ooops!',
							text: 'Error al insertar los datos',
							icon: 'error',
							confirmButtonText: 'Ok'

						});
					} else if (data_resp["ok"] == 0 && data_resp["err_db"] == 0) {
						Swal.fire({
							title: 'Ooops!',
							text: 'Error inesperado, verifique que no tenga campos vacios en las filas del archivo de excel...',
							icon: 'error',
							confirmButtonText: 'Ok'

						});
					}

				}
			});

			return false;

		}

	}

	function updateDateMttoPreSave() {
		let start = Date.now(); // milisegundos transcurridos a partir del 1° de Enero de 1970
		let id_mtto = document.getElementById('id_equipoMp').value;
		let date = (document.getElementById('fecha_requerida').value).split('-');
		let dateRequerida = document.getElementById('fecha_requerida').value;
		let dateRequeridaOld = document.getElementById('fecha_requerida_old').value;

		const fecha_select = new Date(`${date[1]}/${date[2]}/${date[0]}`); //fecha seleccionada
		const fecha_actual = new Date(); //fecha actual

		if (id_mtto != "" && date != "" && (fecha_select > fecha_actual)) {

			let form = new FormData();
			form.append('id_mtto', id_mtto);
			form.append('date', dateRequerida);
			form.append('date_old', dateRequeridaOld);

			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					if (xmlhttp.responseText == 1) {
						Swal.fire({
							title: 'Exito!',
							text: 'Se ha actualizado la fecha requerida para el mantenimiento prevenvito',
							icon: 'success',
							confirmButtonText: 'Ok',
							willClose: () => {
								location.reload(true);
							},
						});
					} else {
						Swal.fire({
							title: 'Error!',
							text: 'Ha ocurrido un error al momento de actualizar la fecha requerida para el mantenimiento prevenvito, intente nuevamente!',
							icon: 'error',
							confirmButtonText: 'Ok',
							willClose: () => {
								location.reload(true);
							}
						});
					}

				}
			}
			xmlhttp.open("POST", "<?= base_url() ?>mantenimiento/updateDateMttoPre", true);
			xmlhttp.send(form);
		} else {
			Swal.fire({
				title: 'Advertencia!',
				html: `Debe seleccionar una fecha mayor a la fecha actual ${fecha_actual.toLocaleDateString()}`,
				icon: 'warning',
				confirmButtonText: 'Ok'
			});
		}
	}
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#buscar_items").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#menu_items li").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
			});
		});
	});
</script>