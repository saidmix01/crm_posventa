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
<!-- Sparkline 
<script src="<?= base_url() ?>plugins/sparklines/sparkline.js"></script>
-->
<!-- JQVMap 
<script src="<?= base_url() ?>plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?= base_url() ?>plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
-->

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
<!-- AdminLTE dashboard demo (This is only for demo purposes)
<script src="<?= base_url() ?>dist/js/pages/dashboard.js"></script>
 -->
<!-- DataTables -->
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!--select2-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- SweetAlert2 -->
<script src="<?= base_url() ?>plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="<?= base_url() ?>plugins/toastr/toastr.min.js"></script>
<!--table2excel-->
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script type="text/javascript">
	$(document).ready(function() {
		$("#buscar_items").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#menu_items li").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
	});
</script>


<script>
	/*funciones para el modulo de lamina y pintura */

	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 5000
	});

	function PintarFormualrioAgregar() {

		var campoformulario = document.getElementById('verFormulario');
		//definir ruta
		var url = '<?= base_url() ?>LaminaPintura/PintarFormulario';
		//definir el id del formulario para recoger los datos
		//var formulario = document.getElementById("formulario_lyt");
		//crear objeto de la clase XMLHttpRequest
		var request = new XMLHttpRequest();
		//valioras respuesta de la peticion HTTP
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				//pinta datos en la vista por medio de innerhtml 
				campoformulario.innerHTML = request.responseText;
			}
		}
		//definiendo metodo y ruta
		request.open("POST", url);
		//envar los dattos creando un objeto de clse fordata del formulario
		request.send();
	};

	function RegistrarProductoLaminaPintura() {
		var nombre = document.getElementById('nombreProducto').value;
		var marca = document.getElementById('MarcaProducto').value;
		var cantidad = document.getElementById('catidadProducto').value;
		var respuesta = document.getElementById('validaRespuesta');
		if (nombre == "" || marca == "" || cantidad == "") {
			respuesta.innerHTML = "<div class=' text-center alert alert-danger alert-dismissible fade show' role='alert'><strong>Todos Los Campos deben ser Completados<button type='button' class='close' data-dismiss='alert'aria-label='Close'> <span aria-hidden='true'>&times;</span></button></div>";
		} else if (isNaN(cantidad)) {
			respuesta.innerHTML = "<div class=' text-center alert alert-danger alert-dismissible fade show' role='alert'><strong>Este  Campos solo debe contener numeros<button type='button' class='close' data-dismiss='alert'aria-label='Close'> <span aria-hidden='true'>&times;</span></button></div>";
		} else {
			//definir ruta
			var url = '<?= base_url() ?>LaminaPintura/AgregarProductoLaminaPintura';
			//definir el id del formulario para recoger los datos
			var formulario = document.getElementById("formProducto");
			//crear objeto de la clase XMLHttpRequest
			var request = new XMLHttpRequest();
			//valioras respuesta de la peticion HTTP
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					//campoformulario.innerHTML = request.responseText;
					location.reload();
				}
			}
			//definiendo metodo y ruta
			request.open("POST", url);
			//envar los dattos creando un objeto de clse fordata del formulario
			request.send(new FormData(formulario));
		}
	}

	function EliminarProductoLaminaPintura(id) {

		var codigo = id;
		var request = new XMLHttpRequest();
		//valioras respuesta de la peticion HTTP
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				//campoformulario.innerHTML = request.responseText;
				location.reload();
			}
		}
		request.open("GET", "<?= base_url() ?>LaminaPintura/EliminarProductoLaminaPintura?codigo=" + codigo, false);
		request.send();
	}

	function PintarFormualrioEditar(id) {
		var codigoProducto = id;
		var pintarform = document.getElementById('FormEditar');
		var request = new XMLHttpRequest();
		//valioras respuesta de la peticion HTTP
		//request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		//request.responseType= 'json';
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				pintarform.innerHTML = request.responseText;
			}
		}
		request.open("GET", "<?= base_url() ?>LaminaPintura/PintarFormualrioEditar?codigoProducto=" + codigoProducto, false);
		request.send();
	}

	function EditarProductoLaminaPintura() {
		var respuestas = document.getElementById('validarespuestaEditar');
		var producto = document.getElementById('editarnombreProducto').value;
		var marca = document.getElementById('editarMarcaProducto').value;
		var cantidad = document.getElementById('editarcatidadProducto').value;
		if (producto == "" || marca == "" || cantidad == "") {
			respuestas.innerHTML = "<div class=' text-center alert alert-danger alert-dismissible fade show' role='alert'><strong>Todos Los Campos deben ser Completados<button type='button' class='close' data-dismiss='alert'aria-label='Close'> <span aria-hidden='true'>&times;</span></button></div>";
		} else if (isNaN(cantidad)) {
			respuestas.innerHTML = "<div class=' text-center alert alert-danger alert-dismissible fade show' role='alert'><strong>Este  Campos solo debe contener numeros<button type='button' class='close' data-dismiss='alert'aria-label='Close'> <span aria-hidden='true'>&times;</span></button></div>";
		} else {
			//definir ruta
			var url = '<?= base_url() ?>LaminaPintura/AgregarFormualrioEditar';
			//definir el id del formulario para recoger los datos
			var formularioEditar = document.getElementById('formularioeditarlaminapintura');
			//crear objeto de la clase XMLHttpRequest
			var request = new XMLHttpRequest();
			//valioras respuesta de la peticion HTTP
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					location.reload();

				}
			}
			//definiendo metodo y ruta
			request.open("POST", url);
			//envar los dattos creando un objeto de clse formdata del formulario
			request.send(new FormData(formularioEditar));

		}


	}
</script>

<script>
	/*funciones para salida de productos de la tabla inventario lamina y pintura  */

	function buscrainfocarro() {

		var campoformulario = document.getElementById('respuestanumerooreden');
		//definir ruta
		var url = '<?= base_url() ?>LaminaPintura/datosvehiculo';
		var formulario = document.getElementById("formalrioorden");
		var request = new XMLHttpRequest();
		//valioras respuesta de la peticion HTTP
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				//pinta datos en la vista por medio de innerhtml 
				campoformulario.innerHTML = request.responseText;
			} else {
				//respuesta.innerHTML = "<div class=' text-center alert alert-danger alert-dismissible fade show' role='alert'><strong>El numero de oreden no Existe<button type='button' class='close' data-dismiss='alert'aria-label='Close'> <span aria-hidden='true'>&times;</span></button></div>";
			}
		}
		//definiendo metodo y ruta
		request.open("POST", url);
		//envar los dattos creando un objeto de clse fordata del formulario
		request.send(new FormData(formulario));


	}

	function vertablaAgrgar() {
		var orden = document.getElementById('numeroorden').value;
		var ver = document.getElementById('formagrgargastos');
		var campoorde = document.getElementById('idrefreencia').value = orden;
		$fomulario = document.getElementById('formver');
		//definir ruta
		var url = '<?= base_url() ?>LaminaPintura/datosinventario';
		var request = new XMLHttpRequest();
		//valioras respuesta de la peticion HTTP
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				//pinta datos en la vista por medio de innerhtml 
				ver.innerHTML = request.responseText;
			} else {
				//respuesta.innerHTML = "<div class=' text-center alert alert-danger alert-dismissible fade show' role='alert'><strong>El numero de oreden no Existe<button type='button' class='close' data-dismiss='alert'aria-label='Close'> <span aria-hidden='true'>&times;</span></button></div>";
			}
		}
		//definiendo metodo y ruta
		request.open("POST", url);
		//envar los dattos creando un objeto de clse fordata del formulario
		request.send(new FormData($fomulario));
	}

	function tablaproductoslistados() {
		var orden = document.getElementById('numeroorden').value;
		var ver = document.getElementById('formagrgargastos');
		var tabla = document.getElementById('listacompras');
		var campoorde = document.getElementById('idrefreencia').value = orden;
		var fomulario = document.getElementById('formver');
		//definir ruta
		var url = '<?= base_url() ?>LaminaPintura/traertableproductos';
		var request = new XMLHttpRequest();
		//valioras respuesta de la peticion HTTP
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				//pinta datos en la vista por medio de innerhtml 
				tabla.innerHTML = request.responseText;
			} else {
				//respuesta.innerHTML = "<div class=' text-center alert alert-danger alert-dismissible fade show' role='alert'><strong>El numero de oreden no Existe<button type='button' class='close' data-dismiss='alert'aria-label='Close'> <span aria-hidden='true'>&times;</span></button></div>";
			}
		}
		//definiendo metodo y ruta
		request.open("POST", url);
		//envar los dattos creando un objeto de clse fordata del formulario
		request.send(new FormData(fomulario));

	}

	function registarorden() {
		var codigo = document.getElementById('numeroorden').value;
		var valor = document.getElementById('presupuesto').value;
		var respuesta = document.getElementById('validaresp');
		if (codigo == "" || valor == "") {
			respuesta.innerHTML = "<div class=' text-center alert alert-danger alert-dismissible fade show' role='alert'><strong>Todos los Caampos deben ser Completados<button type='button' class='close' data-dismiss='alert'aria-label='Close'> <span aria-hidden='true'>&times;</span></button></div>";
		} else {
			$fomulario = document.getElementById('formalrioorden');
			//definir rutaformalrioorden
			var url = '<?= base_url() ?>LaminaPintura/resgitarnumerooreden';
			var request = new XMLHttpRequest();
			//valioras respuesta de la peticion HTTP
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					respuesta.innerHTML = "<div class=' text-center alert alert-info alert-dismissible fade show' role='alert'><strong> " + request.responseText + "  <button type='button' class='close' data-dismiss='alert'aria-label='Close'> <span aria-hidden='true'>&times;</span></button></div>";

				}
			}
			//definiendo metodo y ruta
			request.open("POST", url);
			//envar los dattos creando un objeto de clse fordata del formulario
			request.send(new FormData($fomulario));
		}
	}

	function registroproductorequerido() {

		var orden = document.getElementById('idrefreencia').value;
		var producto = document.getElementById('valor').value;
		var fecha = document.getElementById('requefecha').value;
		var color = document.getElementById('requecolor').value;
		var medida = document.getElementById('requemedia').value;
		var responsable = document.getElementById('requeuser').value;
		var cantidad = document.getElementById('requecantidad').value;
		var disponibles = document.getElementById('requedisponible').value;
		var precio = document.getElementById('requeprecio').value;
		var respuesta = document.getElementById('inforequeri');
		var tabla = document.getElementById('listacompras');
		var totalvalor = cantidad*precio;


		var datos = new FormData();
		datos.append('orden', orden);
		datos.append('idproducto', producto);
		datos.append('fecha', fecha);
		datos.append('color', color);
		datos.append('medida', medida);
		datos.append('requerido', responsable);
		datos.append('cantidad', cantidad);
		datos.append('disponible', disponibles);
		datos.append('precio', totalvalor);


		if (orden == "" || producto == "" || responsable == "" || cantidad == "" || fecha == "") {
			respuesta.innerHTML = "<div class=' text-center alert alert-danger  show col-lg-12' role='alert'><strong>Todos los Campos deben ser Completados<button type='button' class='close' data-dismiss='alert'aria-label='Close'> <span aria-hidden='true'>&times;</span></button></div>";
		} else if (isNaN(orden)) {
			respuesta.innerHTML = "<div class=' text-center alert alert-danger show col-lg-12' role='alert'><strong>El campo solo deben conter numeros<button type='button' class='close' data-dismiss='alert'aria-label='Close'> <span aria-hidden='true'>&times;</span></button></div>";

		} else {

			var fomulario = document.getElementById('formver');
			//definir rutaformalrioorden
			var url = '<?= base_url() ?>LaminaPintura/registrarrequerido';
			var request = new XMLHttpRequest();
			//valioras respuesta de la peticion HTTP
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					//tabla.innerHTML = request.responseText;

				}
			}
			//definiendo metodo y ruta
			request.open("POST", url);
			//envar los dattos creando un objeto de clse fordata del formulario
			request.send(datos);
		}

	}

	/* */
	function traertablavalores() {
		var codigo = document.getElementById('numeroorden').value;
		var campovalores = document.getElementById('sumadevalores');
		var url = '<?= base_url() ?>LaminaPintura/pintartablatotal';
		var datos = new FormData();
		datos.append('orden', codigo)
		var request = new XMLHttpRequest();
		//valioras respuesta de la peticion HTTP
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				campovalores.innerHTML = request.responseText;
			}
		}
		//definiendo metodo y ruta
		request.open("POST", url);
		//envar los dattos creando un objeto de clse fordata del formulario
		request.send(datos);
	}

		/*function para ter el */
		function valorescolorconsumible() {
		var codigo = document.getElementById('numeroorden').value;
		var url = '<?= base_url() ?>LaminaPintura/';
		var datos = new FormData();
		datos.append('orden', codigo)
		var request = new XMLHttpRequest();
		//valioras respuesta de la peticion HTTP
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
			}
		}
		//definiendo metodo y ruta
		request.open("POST", url);
		//envar los dattos creando un objeto de clse fordata del formulario
		request.send(datos);


	}

	/*funcion para genrar los valores resultantes */
	function sumarYguardar() {
		var totalcosto = document.getElementById('totalcosto');
		var totalcolor = document.getElementById('totalcolor');
		var totalnose = document.gteElementById('totalnose');
		var totalconsumo = document.getElementVyId('totalconsumo');
	}
</script>

<script>
	$(document).ready(function() {
		$('#valor').select2({
			placeholder: 'Select an option',
			theme: "classic"
		});

	});

	/*
	$(document).ready(function() {
		$('#idrefreencia').select2({
			placeholder: 'Select an option',
			theme: "classic"
		});

	});
	*/
</script>



<script>
	$('#TablaLamina').DataTable({
		"paging": true,
		"lengthChange": true,
		"searching": true,
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