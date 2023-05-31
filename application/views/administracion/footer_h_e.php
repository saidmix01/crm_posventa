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
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Solicitud jornada adicional</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="loader" id="cargando"></div>
				<form role="form">
					<div class="card-body">
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="cargo">Fecha de inicio jornada adicional</label>
									<input type="date" class="form-control" id="fecha_ini" placeholder="Ingrese la fecha" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="cargo">Hora de inicio jornada adicional</label>
									<input type="text" class="form-control" id="hora_ini" placeholder="Ingrese la hora" required>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="cargo">Hora de fininalización jornada adicional</label>
									<input type="text" class="form-control" id="hora_fin" placeholder="Ingrese la hora" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="exampleInputPassword1">Area donde labora</label>
									<select class="form-control js-example-basic-single" style="width: 100%" id="area" required="true">
										<option value="area">Seleccione una opción</option>
										<option value="Administración">Administración</option>
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
										<option value="sede">Seleccione una opción</option>
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
									<label for="exampleInputPassword1">Empleado</label>
									<select class="form-control js-example-basic-single" style="width: 100%" id="empleado" required="true">
										<option value="empleado">Seleccione una opción</option>

										<?php foreach ($emp_jefes->result() as $key) { ?>
											<option value="<?= $key->nit ?>"><?= $key->nombres ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<label for="descrp">Describe el motivo de la solicitud</label>
							<textarea class="form-control" id="descripcion" required="true"></textarea>
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
				<h5 class="modal-title" id="exampleModalLabel">Ver Solicitud de horas extra</h5>
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
<script src="<?= base_url() ?>plugins/sweetalert2/sweetalert2.min.js"></script>
<!--table2excel -->
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- overlayScrollbars -->
<script src="<?=base_url()?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script type="text/javascript">
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
		$('#hora_ini').timepicker({ zindex: 9999999,timeFormat: 'H:mm',interval: 5,minTime: '6',maxTime: '6:00pm'});
	$('#hora_fin').timepicker({ zindex: 9999999,timeFormat: 'H:mm',interval: 5,minTime: '6',maxTime: '11:00pm'});
	});
</script>

<script>
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 5000
	});

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
	document.addEventListener('DOMContentLoaded', function() {
		var calendarEl = document.getElementById('calendar_horas_e');
		//import interactionPlugin from '@fullcalendar/interaction';
		var calendar = new FullCalendar.Calendar(calendarEl, {
			initialView: 'dayGridMonth',
			//plugins: [ interactionPlugin ],
			selectable: true,
			events: <?= $horas_extra ?>,
			select: function(arg) {
				var d = new Date();

				d.setDate(d.getDate() - 1);

				//document.write('<br>5 days ago was: ' + d.toLocaleString());
				if (arg.start < d) {
					Toast.fire({
						type: 'warning',
						title: ' No puedes escoger un dia anterior'
					});
				} else {
					$('#fecha_ini').val(arg.startStr);
					$('#modal_au').modal('show');
				}
				calendar.unselect();
			},
			eventClick: function(arg) {
				console.log(arg.event.id);
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
		xmlhttp.open("GET", "<?= base_url() ?>Administracion/ver_info_horas_extra?id=" + id, true);
		xmlhttp.send();
	}


	function crear_evento() {
		var fecha_ini = document.getElementById("fecha_ini").value;
		var hora_ini = document.getElementById("hora_ini").value;
		var hora_fin = document.getElementById("hora_fin").value;
		var area = document.getElementById("area").value;
		var cargo = document.getElementById("cargo_emp").value;
		var sede = document.getElementById("sede").value;
		var descripcion = document.getElementById("descripcion").value;
		var empleado = document.getElementById("empleado").value;


		if (fecha_ini == "" || hora_ini == "" || hora_fin == "" || area == "area" || cargo == "" || sede == "sede" || descripcion == "" || empleado == "empleado") {
			Toast.fire({
				type: 'error',
				title: ' Todos los campos deben ser completados'
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
			xmlhttp.open("GET", "<?= base_url() ?>Administracion/add_horas_extra?fecha_ini=" + fecha_ini +
				"&hora_ini=" + hora_ini + "&area=" + area + "&sede=" + sede + "&descripcion=" + descripcion +
				"&cargo=" + cargo + "&empleado=" + empleado + "&hora_fin=" + hora_fin, true);
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
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 5000
	});
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
