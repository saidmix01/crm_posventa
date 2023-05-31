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

<!-- MENSAJE FLOTANTE-->
<?php
$log = $this->input->get('log');
if ($log == "err_p") {
?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert_err" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
		Error... Las contraseñas no coinciden
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php
}
?>
<div id="notifi">

</div>


<!-- jQuery -->
<script src="<?= base_url() ?>plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= base_url() ?>plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
	$.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="<?= base_url() ?>plugins/chart.js/Chart.min.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?= base_url() ?>plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?= base_url() ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url() ?>plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= base_url() ?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?= base_url() ?>plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url() ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>dist/js/adminlte.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>

<!--select2-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- SweetAlert2 -->
<!-- <script src="<?= base_url() ?>plugins/sweetalert2/sweetalert2.min.js"></script> -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Toastr -->
<script src="<?= base_url() ?>plugins/toastr/toastr.min.js"></script>
<!--table2excel-->
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<!--usar botones en datatable-->
<!-- <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script> -->




<script>
	//inicio de funciones para la tabla equipos

	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 7000
	});

	function estado(id) {
		var estado = 'inactivo';
		Swal.fire({
			text: "¡Desea Retirar este equipo del Inventario!",
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Retirar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (!result.dismiss) {
				var xmlhttp1;
				if (window.XMLHttpRequest) {
					xmlhttp1 = new XMLHttpRequest();
				} else {
					xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp1.onreadystatechange = function() {
					if (xmlhttp1.readyState === 4 && xmlhttp1.status === 200) {
						location.reload();
					}
				}
				xmlhttp1.open("GET", "<?= base_url() ?>mantenimiento/cambio_estado?id=" + id + "&estado=" + estado, false);
				xmlhttp1.send();
			}
		})
	}

	function pintar_datos(id) {
		
		var xmlhttp;
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
						title: ' No hay datos'
					});
				} else {
					document.getElementById('edi_datos').innerHTML = xmlhttp.responseText;
				}
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>mantenimiento/traer_datos?id=" + id, true);
		xmlhttp.send();
	}
</script>

<script>
	//----------------------------------------funciones para la tabla mantenimiento preventivo----------------------------->

	function traer(id) {

		console.log(id);
		var xmlhttp1;
		if (window.XMLHttpRequest) {
			xmlhttp1 = new XMLHttpRequest();
		} else {
			xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp1.responseType = 'json';
		xmlhttp1.onreadystatechange = function() {
			if (xmlhttp1.readyState === 4 && xmlhttp1.status === 200) {
				var flag1 = xmlhttp1.response;
				if (flag1 == "") {
					Toast.fire({
						type: 'error',
						title: ' No hay datos'
					});
				} else {
					// document.getElementById('modalCorrecti').innerHTML = xmlhttp1.responseText;
					document.getElementById('id_equipoMp').value = flag1[0]["id_equipo"];
					document.getElementById('nombreMp').value = flag1[0]["nombre_equipo"];
					document.getElementById('codigoEquipoMp').value = flag1[0]["codigo"];

				}
			}
		}
		xmlhttp1.open("GET", "<?= base_url() ?>mantenimiento/pintarDatosCorrectivo?codigo=" + id, true);
		xmlhttp1.send();
	};


	//funcion para visualizar campos segun la opcion escogida
	function vercampos() {
		var valorCampo = document.getElementById('tipoMantenimientoMp').value;
		var descripcion = document.getElementById('campo1');
		var diagnostico = document.getElementById('campo2');
		var observar = document.getElementById('campo3');
		var piezas = document.getElementById('campo4');
		var trabajo = document.getElementById('campo5');


		// 1 = preventivo
		if (valorCampo == 1) {
			descripcion.style.display = "block";
			diagnostico.style.display = "none";
			observar.style.display = "block";
			piezas.style.display = "block";
			trabajo.style.display = "none";
			// 2 = coreectivo		
		} else if (valorCampo == 2) {
			descripcion.style.display = "block";
			diagnostico.style.display = "block";
			observar.style.display = "none";
			piezas.style.display = "block";
			trabajo.style.display = "block";

		}

	}

	function ver(id) {
		var codigo = id;
		console.log(id);
		var respuesta = document.getElementById('mantenimientos');
		if (codigo == "") {
			respuesta.innerHTML = '<div class="alert alert-danger text-center" role="alert">Error el codigo de este equipo no fue asignado!</div>';
		} else {
			var request = new XMLHttpRequest();
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					document.getElementById('mantenimientos').innerHTML = request.responseText;
					
					$('#modalHistorial').modal('show');
					cargarDatatable2();
				}
			}
			request.open("GET", "<?= base_url() ?>mantenimiento/dtoManteid?codigo=" + codigo, true);
			request.send();
		}
	}

	function ver_historial(id) {
		var codigo = id;
		console.log(id);
		console.log(codigo);
		var respuesta = document.getElementById('mantenimientos_detalle');
		if (codigo == "") {
			respuesta.innerHTML = '<div class="alert alert-danger text-center" role="alert">Error el codigo de este equipo no fue asignado!</div>';
		} else {
			var request = new XMLHttpRequest();
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					document.getElementById('mantenimientos_detalle').innerHTML = request.responseText;
					$('#modalHistorialDetalle').modal('show');
				}
			}
			request.open("GET", "<?= base_url() ?>mantenimiento/detalle_historial_mantenimiento?codigo=" + codigo, false);
			request.send();
		}
	}

	function ver_historialCorrectivo(id) {
		var codigo = id;
		console.log(id);
		console.log(codigo);
		var respuesta = document.getElementById('mantenimientos_detalle');
		if (codigo == "") {
			respuesta.innerHTML = '<div class="alert alert-danger text-center" role="alert">Error el codigo de este equipo no fue asignado!</div>';
		} else {
			var request = new XMLHttpRequest();
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					document.getElementById('mantenimientos_detalle').innerHTML = request.responseText;
					$('#modalHistorialDetalle').modal('show');
				}
			}
			request.open("GET", "<?= base_url() ?>mantenimiento/detalle_historial_mantenimientoCorrectivo?codigo=" + codigo, false);
			request.send();
		}
	}
</script>

<script>
	//funcion hacer busquedas por sede y boodega

	function buscar_equipos_por_sede_y_bodega() {

		var sede = document.getElementById('buscarsede').value;
		var bodega = document.getElementById('buscarbodega').value;
		
		var filtro = document.getElementById('tablafiltro');
		if (sede == "" && bodega == "") {
			Toast.fire({
						type: 'error',
						title: 'Debe seleccionar una bodega o una sede'
					});
		} else {
			document.getElementById('cargando').style.display = 'block';
			var datos = new FormData();
			datos.append('sede', sede);
			datos.append('bodega', bodega);

			var url = '<?= base_url() ?>Mantenimiento/pintar_tabla_por_sede_y_bodega';
			var request = new XMLHttpRequest();
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					$("#tabla_uno").dataTable().fnDestroy();
					filtro.innerHTML = request.responseText;
					cargarDatatable();
					document.getElementById('tablatotal').style.display = "none";
					document.getElementById('cargando').style.display = 'none';
				}
			}
			request.open("POST", url);
			request.send(datos);

		}


	}
</script>


<script type="text/javascript">
	//funcion para convetir tabla en documento de excel

	var f = new Date();
	fecha = f.getDate() + "-" + (f.getMonth() + 1) + "-" + f.getFullYear();

	function bajar_excel_tabla_uno() {
		$("#tabla_uno").table2excel({
			exclude: ".noExl",
			name: "Worksheet Name",
			filename: "PQR-" + "-" + fecha, //do not include extension
			fileext: ".xlsx" // file extension
		});
	}
</script>

<script type="text/javascript">
	//funcion para convetir tabla en documento de excel

	var f = new Date();
	fecha = f.getDate() + "-" + (f.getMonth() + 1) + "-" + f.getFullYear();

	function bajar_excel_tabla_dos() {
		$("#tabla_dos").table2excel({
			exclude: ".noExl",
			name: "Worksheet Name",
			filename: "PQR-" + "-" + fecha, //do not include extension
			fileext: ".xlsx" // file extension
		});
	}
</script>

<!-- /*************************************SOLICITUD DE RETIRO DE EQUIPOS ************************************************/
	/**Autor: Sergio Galvis 
	 * Fecha: 11 de Abril del 2022
	 */ -->
<script>
	function solicitud_retiro(id) {
		$("#id_equipo").val(id);
		$('#modaledRetiro').modal('show');
	}
</script>
<script src="<?= base_url() ?>dist/js/demo.js"></script>

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
</body>

</html>
