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

<!-- Modal Nuevo ausentismo-->
<div class="modal fade" id="modal_au" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<!-- <div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Nuevo Ausentismo</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div> -->
			<div class="modal-body">
				<div class="loader" id="cargando"></div>
				<form role="form" autocomplete="off">
					<div class="card-body">
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="cargo">Fecha en la que se ausentará</label>
									<input type="date" class="form-control" id="fecha_ini" min="<?= Date('Y-m-d'); ?>" value="<?= Date('Y-m-d'); ?>" placeholder="Ingrese la fecha" required onfocusout="val_fecha_inicial();">
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="cargo">Hora inicio ausentismo</label>
									<input type="text" class="form-control" name="hora_ini" id="hora_ini" placeholder="Ingrese la hora" readonly required />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<!-- <label for="cargo">Fecha En Que Termina El Ausentismo</label> -->
									<span class="text-justify font-weight-bold font-italic">Los ausentismo solo se podran deligenciar maximo por un día. Si desea tomar más de un día debe hacerlo por separado</span>
									<!-- <input type="date" class="form-control" id="fecha_fin" onchange="val_fecha();" placeholder="Ingrese la fecha" required> -->
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="cargo">Hora En Que Termina El Ausentismo</label>
									<input type="text" class="form-control" name="hora_fin" id="hora_fin" placeholder="Ingrese la hora" readonly onchange="diferenciaHoras();" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="exampleInputPassword1">Area donde labora</label>
									<select class="form-control js-example-basic-single" style="width: 100%" id="area" required="true">
										<option value="">Seleccione una opción</option>
										<option value="Administración">Administración</option>
										<option value="Administración Servicio">Administración Servicio</option>
										<option value="Central de Beneficios">Central de Beneficios</option>
										<option value="Vehiculos Nuevos">Vehiculos Nuevos</option>
										<option value="Vehiculos Usados">Vehiculos Usados</option>
										<option value="Repuestos">Repuestos</option>
										<option value="Taller Gasolina">Taller Gasolina</option>
										<option value="Taller Diesel">Taller Diesel</option>
										<option value="Lamina y Pintura">Lamina y Pintura</option>
										<option value="Alistamiento">Alistamiento</option>
										<option value="Contac Center">Contac Center</option>
										<option value="Accesorios">Accesorios</option>
									</select>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="cargo">Cargo del empleado</label>
									<input type="text" class="form-control" id="cargo_emp" placeholder="Ingese el cargo" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="exampleInputPassword1">Sede</label>
									<select class="form-control js-example-basic-single" style="width: 100%" id="sede" required="true">
										<option class="d-none">Seleccione una opción</option>
										<option value="Giron">Girón</option>
										<option value="Rosita">Rosita</option>
										<option value="Chevropartes">Chevropartes</option>
										<option value="Solochevrolet">Solochevrolet</option>
										<option value="Barrancabermeja">Barrancabermeja</option>
										<option value="Bocono">Boconó</option>
										<option value="Malecon">Malecón</option>
									</select>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<!-- CUANDO SE ACTIVE EL BANCO DE TIEMOP COLOCAR EN EL ONCHANGE getHorasDisponibles();-->
									<label for="exampleInputPassword1">Motivo del permiso</label>
									<select class="form-control js-example-basic-single" style="width: 100%" id="motivo" required="true" onchange="">
										<option value="">Seleccione una opción</option>
										<option value="Cumpleaños">Cumpleaños</option>
										<option value="Cita Medica/Odontológica">Cita Medica/Odontológica del trabajador</option>
										<option value="Licencias(Paternidad o Luto)">Licencias(Paternidad o Luto)</option>
										<option value="Calamidad Doméstica">Grave Calamidad Doméstica Comprobada</option>
										<option value="Reunión o capacitación">Reunión o capacitación programada por la Empresa</option>
										<option value="Permiso no remunerado con descuento de nomina">Permiso no remunerado con descuento de nómina</option>
										<option value="Personal">Personal</option>
										<option value="Estudio">Estudio</option>
										<option value="Dia de la familia">Día de la familia</option>
										<option value="Compensatorio (Indicar en descripción la fecha que laboró)">Compensatorio (Indicar en descripción la fecha que laboró)</option>
									</select>
								</div>
							</div>
						</div>
						<?php
						if ($tiempoAusen == 0) {
						?>
							<div class="row" style="display: none;" id="contentTiempoAusen">
								<label for="">Escriba la fecha y hora en que recuperará el tiempo.</label>
								<input type="text" name="recuperarTiempo" id="recuperarTiempo" class="form-control" value="null">
							</div>
						<?php } ?>
						<div class="row">
							<label for="descrp">Describe el motivo del permiso</label>
							<textarea class="form-control" minlength="15" id="descripcion" required="true"></textarea>
						</div>
					</div>
					<!-- /.card-body -->
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" id="btn_agregar" class="btn btn-success btn-flat" onclick="crear_evento();">Agregar</button>

				<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
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
			<div class="modal-body" id="load_event">

			</div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--table2excel -->
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url() ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script type="text/javascript">
	const nit_doc = <?php echo $this->session->userdata('user'); ?>;
	$(document).ready(function() {
		$("#buscar_items").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#menu_items li").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		$('.js-example-basic-single').select2({
			theme: "classic"
		});

	});
	const tiempoAusen = '<?php echo $tiempoAusen; ?>';
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 5000
	});
	$('#hora_ini').timepicker({
		zindex: 9999999,
		timeFormat: 'HH:mm',
		interval: 5,
		minTime: '6',
		maxTime: '8:00pm'
	});
	$('#hora_fin').timepicker({
		zindex: 9999999,
		timeFormat: 'HH:mm',
		interval: 5,
		minTime: '6',
		maxTime: '8:00pm'
	});
	//$('#time').timepicker();
</script>
<?php if (isset($ausentismos)) { ?>
	<script>
		function val_fecha() {
			var fecha_ini = document.getElementById("fecha_ini").value;
			var fecha_fin = document.getElementById("fecha_fin").value;
			if (fecha_ini > fecha_fin) {
				Toast.fire({
					type: 'warning',
					title: ' No puedes ingresar una fecha anterior a la seleccionada'
				});
				$('#fecha_fin').val("")

			}
		}
		function val_fecha_inicial() {
			var fecha_ini = document.getElementById("fecha_ini").value;
			if (fecha_ini < "<?=Date('Y-m-d')?>") {
				Swal.fire({
					type: 'warning',
					title: ' No puedes ingresar una fecha anterior a la actual'
				});
				$('#fecha_ini').val("<?=Date('Y-m-d')?>")
			}
		}
		/* Btn Mostrar el modal para crear un nuevo ausentismo */
		function mostrarModalNewAusentismo() {
			var diaEsFestivo = document.getElementById('diaActualEsFestivo');
			var f_actual1 = document.getElementById('fecha_actual').value;
			var f = new Date(f_actual1);
			f.setDate(f.getDate() - 1);
			f.setHours(0, 0, 0);

			var d = new Date(f_actual1);
			var dia_actual = d.getDay();
			var hora_actual = d.getHours();
			var minutos_actual = d.getMinutes();


			if (diaEsFestivo == 1) {
				Toast.fire({
					type: 'warning',
					title: ' No puedes crear ausentismo en días festivos'
				});
			} else if (dia_actual == 0) {
				Toast.fire({
					type: 'warning',
					title: ' No puedes crear ausentismo en días dominicales'
				});
			} else if (dia_actual == 6 && hora_actual >= 12 && minutos_actual > 30) {
				Toast.fire({
					type: 'warning',
					title: ' No puedes crear ausentismo en horarios no laborales'
				});
			} else {
				$('#modal_au').modal('show');
			}

		}
		document.addEventListener('DOMContentLoaded', function() {
			var calendarEl = document.getElementById('calendar');
			var diaEsFestivo = document.getElementById('diaActualEsFestivo');
			//import interactionPlugin from '@fullcalendar/interaction';
			var calendar = new FullCalendar.Calendar(calendarEl, {
				initialView: 'dayGridMonth',
				//plugins: [ interactionPlugin ],
				selectable: true,
				eventLongPressDelay: 1,
				longPressDelay: 1,
				selectLongPressDelay: 1,
				events: <?= $ausentismos ?>,
				select: function(arg) {

					var f_actual1 = document.getElementById('fecha_actual').value;
					var f = new Date(f_actual1);
					f.setDate(f.getDate() - 1);
					f.setHours(0, 0, 0);

					var d = new Date(f_actual1);
					var dia_actual = d.getDay();
					var hora_actual = d.getHours();
					var minutos_actual = d.getMinutes();

					if (arg.start < f) {
						Toast.fire({
							type: 'warning',
							title: ' No puedes escoger un dia anterior'
						});
					} else if (diaEsFestivo == 1) {
						Toast.fire({
							type: 'warning',
							title: ' No puedes crear ausentismo en días festivos'
						});
					} else if (dia_actual == 0) {
						Toast.fire({
							type: 'warning',
							title: ' No puedes crear ausentismo en días dominicales'
						});
					} else if (dia_actual == 6 && hora_actual >= 12 && minutos_actual > 30) {
						Toast.fire({
							type: 'warning',
							title: ' No puedes crear ausentismo en horarios no laborales'
						});
					} else {
						$('#fecha_ini').val(arg.startStr);
						$('#modal_au').modal('show');
					}
					calendar.unselect();
				},
				eventClick: function(arg) {
					ver_evento(arg.event.id);
					$('#modal_au_ver').modal('show');
				},
				headerToolbar: {
					left: 'prev,next,today',
					center: 'title',
					right: ''
				}
			});
			calendar.setOption('locale', 'Es');
			calendar.render();
		});

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
					result.innerHTML = xmlhttp.responseText;

				}
			}
			xmlhttp.open("GET", "<?= base_url() ?>Administracion/ver_info_evento?id=" + id, true);
			xmlhttp.send();
		}

		function getHorasDisponibles() {
			var fecha_ini = document.getElementById("fecha_ini").value;
			var hora_ini = document.getElementById("hora_ini").value;
			var fecha_fin = document.getElementById("fecha_fin").value;
			var hora_fin = document.getElementById("hora_fin").value;
			var motivo = document.getElementById("motivo").value;

			if (motivo == "Personal") {
				if (fecha_ini != "" && fecha_fin != "" && hora_ini != "" && hora_fin != "") {
					if (tiempoAusen == 0) {
						document.getElementById('contentTiempoAusen').style.display = "block"
					}
					/* Script para determinar el tiempo estimado del ausentismo */
					var f_inicial = new Date(fecha_ini + " " + hora_ini);
					var f_final = new Date(fecha_fin + " " + hora_fin);


					var diferencia = (f_final - f_inicial) / 1000;
					var dias = diferencia / 86400;
					var number = dias;
					var diasV = Math.trunc(dias);
					var decimals = +number.toString().replace(/^[^\.]+/, '0');
					var horas = (decimals * 86400) / 3600;
					var number = horas;
					var horasV = Math.trunc(horas);
					var decimals = +number.toString().replace(/^[^\.]+/, '0');
					var minutos = (decimals * 3600) / 60;
					var minutosV = Math.trunc(minutos);
					let horaAusen = horasV + ":" + minutosV;
					/* Fin tiempo estimado del ausentismo */

					var xmlhttp;
					if (window.XMLHttpRequest) {
						xmlhttp = new XMLHttpRequest();
					} else {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
							let resp = xmlhttp.responseText;
							if (resp == "err") {
								Swal.fire({
									title: 'Atención!',
									html: resp,
									icon: 'warning',
									confirmButtonText: 'Ok'
								});
							} else if (resp != "err") {
								Swal.fire({
									title: 'Atención!',
									html: resp,
									icon: 'warning',
									confirmButtonText: 'Ok',
									willClose: () => {
										const newspaperSpinning = [{
											background: "orange"
										}, {
											background: "none"
										}];
										const newspaperTiming = {
											duration: 500,
											iterations: 5,
										};
										document.getElementById('recuperarTiempo').animate(newspaperSpinning, newspaperTiming);
										document.getElementById('recuperarTiempo').focus();
									}
								});
							}

						}
					}
					xmlhttp.open("GET", "<?= base_url() ?>Administracion/calcularTiempoRestante?horaAusen=" + horaAusen + "&nit=" + nit_doc, true);
					xmlhttp.send();
				} else {
					Swal.fire({
						title: 'Advertencia!',
						html: 'Debes llegar los campos de fecha y hora primero',
						icon: 'warning',
						confirmButtonText: 'Ok'
					});
				}
			}

		}


		function crear_evento() {

			var fecha_ini = document.getElementById("fecha_ini").value;
			var hora_ini = document.getElementById("hora_ini").value;
			var fecha_fin = fecha_ini;
			var hora_fin = document.getElementById("hora_fin").value;
			var area = document.getElementById("area").value;
			var cargo = document.getElementById("cargo_emp").value;
			var sede = document.getElementById("sede").value;
			var motivo = document.getElementById("motivo").value;
			var descripcion = document.getElementById("descripcion").value;
			//let recuperarTiempo = document.getElementById("recuperarTiempo").value;
			let recuperarTiempo = "NULL";
			/* Script para determinar el tiempo estimado del ausentismo */
			var f_inicial = new Date(fecha_ini + " " + hora_ini);
			var f_final = new Date(fecha_fin + " " + hora_fin);


			var diferencia = (f_final - f_inicial) / 1000;
			var dias = diferencia / 86400;
			var number = dias;
			var diasV = Math.trunc(dias);
			var decimals = +number.toString().replace(/^[^\.]+/, '0');
			var horas = (decimals * 86400) / 3600;
			var number = horas;
			var horasV = Math.trunc(horas);
			var decimals = +number.toString().replace(/^[^\.]+/, '0');
			var minutos = (decimals * 3600) / 60;
			var minutosV = Math.trunc(minutos);
			let horaAusen = horasV + ":" + minutosV;
			/* Fin tiempo estimado del ausentismo */
			/* Verificar que no sea un día hábil para crear el ausentismo */
			var fecha_actual = new Date();
			if (fecha_actual.getDay() == 7) {
				Swal.fire({
					title: 'No puede crear ausentismo en días no laborales',
					html: 'El día de hoy es Domingo',
					icon: 'success',
					confirmButtonText: 'Ok'
				});
			} else if (fecha_actual.getDay() == 6) {
				if (fecha_actual.getHours() > 12 && fecha_actual.getMinutes() > 30) {
					Swal.fire({
						title: 'No puede crear ausentismo en días no laborales',
						html: 'El día de hoy es Sabado, pasadas las 12:30pm',
						icon: 'success',
						confirmButtonText: 'Ok'
					});
				}
			}
			if (fecha_ini == "" || hora_ini == "" || fecha_fin == "" || hora_fin == "" || area == "" || cargo == "" || sede == "" || motivo == "" || descripcion == "") {
				Toast.fire({
					type: 'error',
					title: 'Todos los campos deben ser completados'
				});
			} else if (descripcion.length < 5) {
				Toast.fire({
					type: 'error',
					title: 'El campo descripcion debe tener como minimo 15 caracteres'
				});

			} else {
				Swal.fire({
					title: 'Tiempo estimado de ausentismo',
					html: 'Número de días:	<i style="color: red">' + diasV + '</i><br>Número de horas:	<i style="color: red">' + horasV + '</i><br>Número de minutos:	<i style="color: red">' + minutosV + '</i>',
					icon: 'success',
					confirmButtonText: 'Ok'
				});
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
								title: ' Error al crear el evento'
							});
						} else if (flag == "ok") {
							Toast.fire({
								type: 'success',
								title: ' Evento creado exitosamente'
							});
							location.reload();
							//result.innerHTML = xmlhttp.responseText;
						} else if (flag == "war2") {
							Toast.fire({
								type: 'warning',
								title: ' No puedes solicitar un dia o mas de permisos personales'
							});
						}
						document.getElementById("cargando").style.display = "none";
						$('#modal_au').modal('hide');
						document.getElementById('btn_agregar').disabled = false;
						//result.innerHTML = xmlhttp.responseText;

					}
				}

				xmlhttp.open("GET", "<?= base_url() ?>Administracion/new_ausentismo?fecha_ini=" + fecha_ini +
					"&hora_ini=" + hora_ini + "&fecha_fin=" + fecha_ini + "&hora_fin=" + hora_fin + "&area=" + area + "&sede=" + sede +
					"&motivo=" + motivo + "&descripcion=" + descripcion + "&cargo=" + cargo + "&horaAusen=" + horaAusen + "&diaAusen=" + diasV + "&recuperarTiempo=" + recuperarTiempo, true);
				xmlhttp.send();
			}
		}

		function enviar_correo(id) {
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
							title: ' Error al crear el evento'

						});
					} else if (flag == "ok") {
						Toast.fire({
							type: 'success',
							title: ' Evento creado exitosamente'
						});
						document.getElementById("cargando").style.display = "none";
						$('#modal_au').modal('hide');

						location.reload();
						//result.innerHTML = xmlhttp.responseText;
					}
					//result.innerHTML = xmlhttp.responseText;

				}
			}
			xmlhttp.open("GET", "<?= base_url() ?>Administracion/enviar?id=" + id, true);
			xmlhttp.send();
		}
	</script>
<?php } ?>


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


<!------------------------------------------------- funciones para Informes de ausentismo------------------------------------------->

<script>
	//var btn = document.getElementById('enviarFechas');
	function enviarFechasAusentimso() {
		var xmlhttp;
		var FechaUno = document.getElementById('FechaUno').value;
		var FechaDos = document.getElementById('FechaDos').value;
		var codigo = document.getElementById('userr').value;
		var sedes = document.getElementById('sedes').value;
		var areas = document.getElementById('areas').value;
		if (FechaUno == "" || FechaDos == "" || codigo == "") {
			Toast.fire({
				type: 'error',
				title: 'Todos los Campos de Fechas esta Vacios'
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
					if (flag == "") {
						Toast.fire({
							type: 'error',
							title: ' Error al traer los datos'
						});
					} else {

						document.getElementById('tblaFechas').innerHTML = xmlhttp.responseText;
						document.getElementById('tableause').style.display = 'none';

					}
				}
			}
			xmlhttp.open("GET", "<?= base_url() ?>Administracion/traerausentimosSede?codigo=" + codigo + "&FechaUno=" + FechaUno + "&FechaDos=" + FechaDos + "&areas=" + areas + "&sedes=" + sedes, true);
			xmlhttp.send();
		}
	}


	//funcion para convetir tabla en documento de excel
	var f = new Date();
	fecha = f.getDate() + "-" + (f.getMonth() + 1) + "-" + f.getFullYear();
	var btnAsentismo = document.getElementById("infoAusentismo");
	btnAsentismo.addEventListener("click", function() {
		$("#infomeAusentismo").table2excel({
			exclude: ".noExl",
			name: "Worksheet Name",
			filename: "Informe-Ausentismo-" + "-" + fecha, //do not include extension
			fileext: ".xlsx" // file extension
		});
	});
	//Informe de ausentimso por filtros
	function imprimedos() {
		$("#tbSede").table2excel({
			exclude: ".noExl",
			name: "Worksheet Name",
			filename: "Informe-Ausentismo-por-sede" + "-" + fecha, //do not include extension
			fileext: ".xlsx" // file extension
		});
	}
</script>
<!-------------------------------------------funciones para Informe horas extras------------------------------------->

<script>
	function infExtras() {
		$("#infomeExtras").table2excel({
			exclude: ".noExl",
			name: "Worksheet Name",
			filename: "Informe-horas" + "-" + fecha, //do not include extension
			fileext: ".xlsx" // file extension
		});
	}

	function infoextrados() {
		$("#tbextrasdos").table2excel({
			exclude: ".noExl",
			name: "Worksheet Name",
			filename: "Informe-horas" + "-" + fecha, //do not include extension
			fileext: ".xlsx" // file extension
		});
	}




	var btnExtras = document.getElementById('Extras');
	btnExtras.addEventListener("click", function() {
		var userExtra = document.getElementById('userrExtra').value;
		var sedeExtra = document.getElementById('extraSede').value;
		var areaExtra = document.getElementById('extraAreas').value;
		var respuesta = document.getElementById('respuestaextra');
		var campotebal = document.getElementById('filtroExtra');
		var mes = document.getElementById('FechaMes').value;
		var emp = document.getElementById('empleado').value;
		var arrayFecha = mes.split(["-"]);
		var mesFecha = arrayFecha[1];
		var añoFecha = arrayFecha[0];
		if (mes == "") {
			respuesta.innerHTML = " <div class='alert bg-dark alert-dismissible fade show col-lg-12 text-center'> El campo fecha no debe ir vacio.<button type='button' class='close text-white' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
		} else {
			//definir ruta
			var url = '<?= base_url() ?>Administracion/filtroHoraExtra';
			//definir el id del formulario para recoger los datos
			var datos = new FormData();
			datos.append('userrExtra', userExtra);
			datos.append('extraSede', sedeExtra);
			datos.append('extraAreas', areaExtra);
			datos.append('mes', mesFecha);
			datos.append('año', añoFecha);
			datos.append('emp', emp);
			//crear objeto de la clase XMLHttpRequest
			var request = new XMLHttpRequest();
			//valioras respuesta de la peticion HTTP
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					//pinta datos en la vista por medio de innerhtml 
					document.getElementById('tablaExtra').style.display = 'none';
					campotebal.innerHTML = request.responseText;
				}
			}
			//definiendo metodo y ruta

			request.open("POST", url);
			request.send(datos);

		}
	})
</script>

<script>
	$(document).ready(function() {
		$('#infomeExtras').DataTable({
			"paging": false,
			"pageLength": 20,
			"scrollX": true,
			"scrollY": "500px",
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
	});
</script>

<script>
	var idioma = {
		"sProcessing": "Procesando...",
		"sLengthMenu": "Mostrar _MENU_ registros",
		"sZeroRecords": "No se encontraron resultados",
		"sEmptyTable": "Ningún dato disponible en esta tabla",
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
			"sLast": "Ãšltimo",
			"sNext": "Siguiente",
			"sPrevious": "Anterior"
		},
		"oAria": {
			"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
			"sSortDescending": ": Activar para ordenar la columna de manera descendente"
		},
		"buttons": {
			"copyTitle": 'Informacion copiada',
			"copyKeys": 'Use your keyboard or menu to select the copy command',
			"copySuccess": {
				"_": '%d filas copiadas al portapapeles',
				"1": '1 fila copiada al portapapeles'
			},

			"pageLength": {
				"_": "Mostrar %d filas",
				"-1": "Mostrar Todo"
			}
		}
	};

	$(document).ready(function() {
		var table = $('#infomeAusentismo').DataTable({
			"paging": false,
			"filter": true,
			"lengthChange": true,
			"scrollX": true,
			"scrollY": "500px",
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": true,
			"language": idioma,
			"lengthMenu": [
				[5, 10, 20, -1],
				[5, 10, 50, "Mostrar Todo"]
			],
			dom: 'Bfrt<"col-md-12 inline"i> <"col-md-12 inline"p>',
		});
	});
</script>

<script>
	$(document).ready(function() {
		$('#tbSede').DataTable({
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
	});

	function diferenciaHoras() {
		var fecha_ini = document.getElementById('fecha_ini').value;
		var fecha_fin = document.getElementById('fecha_fin').value;
		var hora_ini = document.getElementById('hora_ini').value;
		var hora_fin = document.getElementById('hora_fin').value;
		var f_inicial = new Date(fecha_ini + " " + hora_ini);
		var f_final = new Date(fecha_fin + " " + hora_fin);
	}
</script>
<script src="<?= base_url() ?>dist/js/md5.js"></script>
<script>
	function cambiarPass_One() {
		let pass1 = document.getElementById('pass1_one').value;
		let pass2 = document.getElementById('pass2_one').value;
		let id_usuario = document.getElementById('id_usu_one').value;
		let clave = hex_md5(pass1);
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
		
		let pass1 = document.getElementById('pass1_two').value;
		let pass2 = document.getElementById('pass2_two').value;
		let id_usuario = document.getElementById('id_usu_two').value;
		let clave = hex_md5(pass1);
		
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