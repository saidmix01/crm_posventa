<!-- /.content-wrapper -->
<footer class="main-footer">
	<strong>Copyright &copy; 2022 <a href="http://adminlte.io">CODIESEL</a>.</strong>
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






<!-- jQuery -->
<script src="<?= base_url() ?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jQuery UI -->
<script src="<?= base_url() ?>plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- Toastr -->
<script src="<?= base_url() ?>plugins/toastr/toastr.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script src="<?= base_url() ?>plugins/sweetalert2/sweetalert2.min.js"></script> -->
<!--table2excel -->
<!-- <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script> -->
<script src="<?= base_url() ?>plugins/jquery.table2excel.min.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url() ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
			theme: "classic"
		});
		$('#combo_gerente').select2({
			width: '100%',
			theme: "classic"
		});
		load_tabla_solicitudes();
	});
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 5000
	});

	function modal_msn(id, est) {
		$('#modal_msn').modal('toggle');
		if (est == 1) {
			document.getElementById('msn_soli').disabled = "true";
			document.getElementById('btn_msn').disabled;
		}
		document.getElementById('id_soli_msn').value = id;
		load_tabla_msn_soli(id);
	}

	function enviar_msn() {
		var msn = document.getElementById('msn_soli').value;
		var id_soli = document.getElementById('id_soli_msn').value;
		if (msn != "") {
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
						document.getElementById('form_msn').reset();
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
			xmlhttp.open("GET", "<?= base_url() ?>compras/insert_msn_gest_compra?msn=" + msn + "&id_soli_msn=" + id_soli, true);
			xmlhttp.send();
		} else {
			Swal.fire({
				title: 'Advertencia!',
				text: 'Hay Campos Vacios',
				icon: 'warning',
				confirmButtonText: 'Ok'
			});
		}
	}

	function load_tabla_solicitudes() {
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
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>compras/get_solicitudes", true);
		xmlhttp.send();
	}

	function load_tabla_msn_soli(id) {
		var result = document.getElementById("tabla_msn");
		var id_soli = document.getElementById('id_soli_msn').value;
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
		xmlhttp.open("GET", "<?= base_url() ?>compras/load_msn_solicitudes?id_soli=" + id_soli, true);
		xmlhttp.send();
	}

	function cargar_detalles(id_solicitud) {
		var result = document.getElementById("form_solicitud_ver");
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				result.innerHTML = xmlhttp.responseText;
				$('#modal_ver_solicitud').modal('toggle');
				document.getElementById('btn_gen_soli').style.display = "none";
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>compras/detalle_solicitudes?id_soli=" + id_solicitud, true);
		xmlhttp.send();
	}

	function modal_solicitar_autorizacion(id) {
		//alert(id);
		console.log("Modal solicitar autorización");
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				var resp = xmlhttp.responseText;

				if (resp == "no_aut") {
					document.getElementById('titulo_modal').innerHTML = "Numero de Solicitud ->" + id;
					document.getElementById('id_soli1').value = id;
					$('#modal_autorizacion').modal('toggle');
				} else if (resp == "ok_aut") {
					ver_autorizacion_aprobada(id);
					$('#modal_auto').modal('toggle');
				}
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>compras/consultar_autorizacion?id_soli=" + id, true);
		xmlhttp.send();

	}

	function ver_autorizacion_aprobada(id) {
		var result = document.getElementById("ver_soli_apro");
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
		xmlhttp.open("GET", "<?= base_url() ?>compras/ver_autorizacion_aprobada?id_soli=" + id, true);
		xmlhttp.send();
	}

	function modal_estado(id) {
		document.getElementById('id_soli_est').value = id;
		$('#modal_estado').modal('toggle');
	}

	function con_factura(chk) {
		var estado = document.getElementById('chk_' + chk).checked;
		var accion = "";
		if (estado == true) {
			accion = "Si";
		} else {
			accion = "No";
		}
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
					Swal.fire({
						title: 'Exito!',
						text: 'Acción Realizada correctamente',
						icon: 'success',
						confirmButtonText: 'Ok'
					}).then((result) => {
						window.location = "<?= base_url() ?>" + "compras/gestion_compras";

					});
				} else {
					Swal.fire({
						title: 'Error!',
						text: 'Ha ocurrido un error!!',
						icon: 'error',
						confirmButtonText: 'Ok'
					});
				}
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>compras/con_factura?id_soli=" + chk + "&accion=" + accion, true);
		xmlhttp.send();
	}

	function cambiar_estado_compra() {
		var id_solicitud = document.getElementById('id_soli_est').value;
		var estado = document.getElementById('combo_estado').value;
		$('#tab_soli').dataTable().fnDestroy();
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
					Swal.fire({
						title: 'Exito!',
						text: 'Registro almacenado correctamente',
						icon: 'success',
						confirmButtonText: 'Ok',
						willClose: () => {
							load_tabla_solicitudes();
							/* window.location = "<?= base_url() ?>" + "compras/gestion_compras"; */
						}
					});
				} else {
					Swal.fire({
						title: 'Error!',
						text: 'No se insertó el registro',
						icon: 'error',
						confirmButtonText: 'Ok'
					});
				}
				$('#modal_estado').modal('toggle');
				load_datatable();
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>compras/cambiar_estado_compra?id_soli=" + id_solicitud + "&est=" + estado, true);
		xmlhttp.send();
	}

	function enviar_solicitud() {
		var archivo = document.getElementById('file1[]').value;
		var desc = document.getElementById('comentarios').value;
		if (archivo == "" || desc == "") {
			Swal.fire({
				title: 'Error!',
				text: 'Debe subir el archivo de cotización y debe realizar un comentario',
				icon: 'error',
				confirmButtonText: 'Ok'
			});

		} else {
			var http;
			document.getElementById("cargando-1").style.display = "block";
			if (window.XMLHttpRequest) {
				http = new XMLHttpRequest();
			} else {
				http = new ActiveXObject("Microsoft.XMLHTTP");
			}
			var url = '<?= base_url() ?>compras/solicitar_autorizacion';
			var formulario = document.getElementById("data_autolizacion");
			formData = new FormData(formulario);
			http.open('POST', url, true);
			//Send the proper header information along with the request
			//http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			//http.responseType = 'json';
			http.onreadystatechange = function() { //Call a function when the state changes.

				if (http.readyState == 4 && http.status == 200) {

					var resp = http.response;
					if (resp == "ok") {
						document.getElementById("cargando-1").style.display = "none";
						Swal.fire({
							title: 'Exito!',
							text: 'Registro almacenado correctamente',
							icon: 'success',
							confirmButtonText: 'Ok'
						}).then((result) => {
							window.location = "<?= base_url() ?>" + "compras/gestion_compras";

						});
					} else {
						document.getElementById("cargando-1").style.display = "none";
						Swal.fire({
							title: 'Error!',
							text: 'No se insertó el registro',
							icon: 'error',
							confirmButtonText: 'Ok'
						});
					}

				}
			}
			http.send(formData);
		}


	}

	function load_datatable() {
		$('#tab_soli').DataTable({
			"paging": true,
			"pageLength": 25,
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