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
<!-- <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script> -->
<script src="<?= base_url() ?>plugins/jquery.table2excel.min.js"></script>
<!--  DataTables -->
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>

<!--usar botones en datatable-->
<!-- <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script> -->
<!--animate.css-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<!-- overlayScrollbars -->
<script src="<?= base_url() ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>


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
	});
</script>


<script>
	//inicializar tooltip

	$(document).ready(function() {
		$('[data-toggle="popover"]').popover();
	});
</script>


<script>
	$(document).ready(function() {
		loadDataTable();
		//Creamos una fila en el head de la tabla y lo clonamos para cada columna


	});
</script>

<script type="text/javascript">
	function loadDataTable() {
		var table = $('#tabla_data').DataTable({
			"paging": true,
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

		$('#tabla_data thead tr').clone(true).appendTo('#tabla_data thead');

		$('#tabla_data thead tr:eq(1) th').each(function(i) {
			var title = $(this).text(); //es el nombre de la columna
			$(this).html('<input class="form-control col-lg-12 tamaño" type="text" placeholder="' + title + '" />');
			$('input', this).on('keyup change', function() {
				if (table.column(i).search() !== this.value) {
					table
						.column(i)
						.search(this.value)
						.draw();
				}
			});
		});
	}
	//funcion para convetir tabla en documento de excel

	var f = new Date();
	fecha = f.getDate() + "-" + (f.getMonth() + 1) + "-" + f.getFullYear();

	function bajar_excel() {
		$("#tabla_data").table2excel({
			exclude: ".noExl",
			name: "Worksheet Name",
			filename: "PQR-" + "-" + fecha, //do not include extension
			fileext: ".xlsx" // file extension
		});
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

<script type="text/javascript">
	/*CREAR PQR*/
	function crear_pqr() {
		var tipo = document.getElementById('fuente').value;
		var sede = document.getElementById('sede').value;
		var fecha = document.getElementById('fecha').value;
		var placa = document.getElementById('placa').value;
		var cliente = document.getElementById('cliente').value;
		var mod_vh = document.getElementById('mod_vh').value;
		var ot = document.getElementById('ot').value;
		var mail = document.getElementById('mail').value;
		var telef = document.getElementById('telef').value;
		var tecnico = document.getElementById('tecnico').value;
		var comentarios_clientes = document.getElementById('comentarios_clientes').value;

		if (tipo == "" || sede == "" || fecha == "" || placa == "" || cliente == "" || mod_vh == "" ||
			ot == "" || mail == "" || telef == "" || tecnico == "" || comentarios_clientes == "") {
			Swal.fire({
				title: 'Ooops!',
				text: 'Para crear una nueva PQR todos los campos deben de ser llenados',
				icon: 'error',
				confirmButtonText: 'Ok'
			});

		} else {
			if (tipo == 'PQR Escrita' || tipo == 'PQR Telefono' || tipo == 'PQR Verbal' || tipo == 'PQR Whatsapp' || tipo == "Exiware" || tipo == "GM" || tipo == 'Interno') {
				var url = '<?= base_url() ?>Informes/crear_pqr';
				var formulario = document.getElementById("form_pqr");
				var request = new XMLHttpRequest();
				request.onreadystatechange = function() {
					if (request.readyState === 4 && request.status === 200) {
						/* document.getElementById('tablaExtra').style.display = 'none';
						campotebal.innerHTML = request.responseText; */
						var res = request.responseText;
						if (res == 1) {
							Swal.fire({
								title: 'Exito!',
								text: 'Se actualizaron los campos',
								icon: 'sucess',
								confirmButtonText: 'Ok'
							}).then((result) => {
								/* Read more about isConfirmed, isDenied below */
								if (result.isConfirmed) {
									formulario.reset();
								}
							});

							location.reload();
						} else {
							Swal.fire({
								title: 'Ooops!',
								text: res,
								icon: 'error',
								confirmButtonText: 'Ok'
							});
						}

					}
				}
				request.open("POST", url);
				request.send(new FormData(formulario));
			}
		}




		/*else if (tipo == 'Exiware') {
			alert('Exiware');
		} else if (tipo == 'GM') {
			alert('GM');
		}
		*/
	}

	function validar_cliente() {
		var nit = document.getElementById('cliente').value;
		var input_nit = document.getElementById('cliente');
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				var nom = xmlhttp.responseText;
				input_nit.value = nom;
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>Informes/validar_cliente?nit=" + nit, true);
		xmlhttp.send();
	}
</script>

<script type="text/javascript">
	function cargar_info_vh(event) {
		if (event.keyCode == 13) {
			var placa = document.getElementById('placa').value;
			var cliente = document.getElementById('cliente');
			var modelo_vh = document.getElementById('mod_vh');
			var mail = document.getElementById('mail');
			var telef = document.getElementById('telef');
			//var input_placa = document.getElementById('placa');
			if (placa != '') {
				var xmlhttp;
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				//xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				xmlhttp.responseType = 'json';
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						var data = xmlhttp.response;
						for (dat of data) {
							console.log(dat.celular);
							cliente.value = dat.nit;
							modelo_vh.value = dat.modelo;
							mail.value = dat.mail;
							telef.value = dat.celular;
						}
					}
				}
				xmlhttp.open("GET", "<?= base_url() ?>Informes/cargar_info_vh?placa=" + placa, true);
				xmlhttp.send();
			} else {
				alert('Hay campos vacios');
			}
		}
	}

	function modal_comentarios(tipo, id) {
		document.getElementById('id_fuente').value = id;
		document.getElementById('fuentee').value = tipo;

		var http;
		if (window.XMLHttpRequest) {
			http = new XMLHttpRequest();
		} else {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var url = '<?= base_url() ?>Informes/get_data_pqr_nps';
		var params = 'tipo=' + tipo + '&id=' + id;
		http.open('POST', url, true);

		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		http.responseType = 'json';
		http.onreadystatechange = function() { //Call a function when the state changes.
			if (http.readyState == 4 && http.status == 200) {
				var resp = http.response;

				var selectTipificacion = document.getElementById("tipificacion_encuesta");
				var selectEstado = document.getElementById("estado_caso");
				var selectTipificacionCierre = document.getElementById("tipificacion_cierre");
				var txtComentario = document.getElementById("comentarios_final_caso");

				if (resp == 0) {
					selectTipificacion.disabled = false;
					selectEstado.disabled = false;
					selectTipificacionCierre.disabled = true;
					txtComentario.readOnly = true;

					$('#tipificacion_encuesta').val('').select2();
					$('#estado_caso').val('').select2();
					$('#tipificacion_cierre').val('').select2();
					$('#comentarios_final_caso').val('');
				} else {
					selectTipificacion.disabled = true;
					selectTipificacionCierre.disabled = false;
					txtComentario.readOnly = false;

					for (data of resp) {
						$('#tipificacion_encuesta').val(data.tipificacion_encuesta).select2();
						$('#estado_caso').val(data.estado).select2();
						$('#tipificacion_cierre').val(data.tipificacion_cierre).select2();
						$('#comentarios_final_caso').val(data.comentarios_final_caso);
					}
				}
				/* $('.js-example-basic-single').select2({
					theme: "classic",
					placeholder: 'Seleccione una opción',
				}); */
				$('.js-example-basic-single').select2({
					theme: "classic",
					placeholder: 'Seleccione una opción',
					width: '100%'
				});
				$('#modal_comentarios').modal('show');
			}
		}
		http.send(params);
	}

	function crear_pqr_nps() {
		var selectTipificacion = document.getElementById("tipificacion_encuesta");
		selectTipificacion.disabled = false;

		var url = '<?= base_url() ?>Informes/crear_pqr_nps';
		var formulario = document.getElementById("form_pqr_nps");
		var request = new XMLHttpRequest();
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				/* document.getElementById('tablaExtra').style.display = 'none';
				campotebal.innerHTML = request.responseText; */
				var res = request.responseText;
				console.log("valor de res: " + res);
				if (res == 1) {
					Swal.fire({
						title: 'Exito!',
						text: 'Se actualizaron los campos',
						icon: 'sucess',
						confirmButtonText: 'Ok'
					}).then((result) => {
						/* Read more about isConfirmed, isDenied below */
						window.location.reload();
					});

				} else {
					Swal.fire({
						title: 'Ooops!',
						text: res,
						icon: 'error',
						confirmButtonText: 'Ok'
					});
				}
			}
		}
		request.open("POST", url);
		request.send(new FormData(formulario));
	}

	function open_form_verb(id) {
		$('#modal_verb').modal('show');
		//alert(id);
		document.getElementById('id_pqr_nps').value = id;
	}

	function open_list_verb(id) {
		var http;
		if (window.XMLHttpRequest) {
			http = new XMLHttpRequest();
		} else {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var url = '<?= base_url() ?>Informes/get_list_verbs';
		var params = 'id=' + id;
		http.open('POST', url, true);

		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		console.log(http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded'));
		http.responseType = 'json';
		console.log(http.responseType = 'json');
		http.onreadystatechange = function() { //Call a function when the state changes.
			if (http.readyState == 4 && http.status == 200) {
				var resp = http.response;
				console.log(resp);
				var bodyTabla = document.getElementById("listaVerbs");
				//var nameTable = "tablaClientes" + id;
				//var tablaClientes = document.getElementById("tablaClientes");
				if (resp == 0) {
					Swal.fire({
						title: 'Ooops!',
						text: "No hay verbalizaciones para mostrar",
						icon: 'error',
						confirmButtonText: 'Ok'
					});
				} else {
					//se valida si la tabla ya tiene datos y la borra
					while (bodyTabla.firstChild) {
						bodyTabla.removeChild(bodyTabla.firstChild);
					}
					//llena la tabla nuevamente
					for (data of resp) {
						tr = document.createElement("tr");
						tdCon = document.createElement("td");
						tdCon.innerHTML = data.contacto;
						tdVerb = document.createElement("td");
						tdVerb.innerHTML = data.verbalizacion;
						tdFec = document.createElement("td");
						tdFec.innerHTML = data.fecha_contacto;
						tr.appendChild(tdCon);
						tr.appendChild(tdVerb);
						tr.appendChild(tdFec);
						bodyTabla.appendChild(tr);
						// tablaClientes.appendChild(bodyTabla);
						$('#modal_list_verb').modal('show');


					}
					console.log(bodyTabla);

				}
			}
		}

		http.send(params);

	}

	function open_list_verb_Comentarios(id) {
		var http;
		if (window.XMLHttpRequest) {
			http = new XMLHttpRequest();
		} else {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var url = '<?= base_url() ?>Informes/get_list_verbs';
		var params = 'id=' + id;
		http.open('POST', url, true);

		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

		http.responseType = 'json';
		// console.log(http.responseType = 'json');
		http.onreadystatechange = function() { //Call a function when the state changes.
			if (http.readyState == 4 && http.status == 200) {
				var resp = http.response;
				// console.log(resp);

				var bodyTabla = document.getElementById('cuerpoTabla-' + id);
				// var body2Tabla = document.getElementById('SinComentario-' + id);

				if (resp == 0) {

					// body2Tabla.remove();

				} else {

					//se valida si la tabla ya tiene datos y la borra
					// while (bodyTabla.firstChild) {
					// 	bodyTabla.removeChild(bodyTabla.firstChild);
					// } 
					//llena la tabla nuevamente
					for (data of resp) {

						tr = document.createElement("tr");
						tr.classList.add("noExl");
						tdCon = document.createElement("td");
						tdCon.classList.add("noExl");
						tdCon.innerHTML = data.contacto;
						tdVerb = document.createElement("td");
						tdVerb.classList.add("noExl");
						tdVerb.innerHTML = data.verbalizacion;
						tdFec = document.createElement("td");
						tdFec.classList.add("noExl");
						tdFec.innerHTML = data.fecha_contacto;
						tr.appendChild(tdCon);
						tr.appendChild(tdVerb);
						tr.appendChild(tdFec);
						bodyTabla.appendChild(tr);
						// tablaClientes.appendChild(bodyTabla);
						// $('#modal_list_verb').modal('show');


					}
					// console.log(bodyTabla);			 

				}
			}
		}

		http.send(params);

	}

	function add_verb() {
		var url = '<?= base_url() ?>Informes/crear_verb_pqr_nps';
		var hoy = new Date();
		var fechaYHora = hoy.toLocaleString("sv-SE").replace(" ", "T").split(".")[0]; //Se parsea la fecha al formato que acepta en la BD    

		var formulario = document.getElementById("form_verb");

		formData = new FormData(formulario);
		formData.append("fecha_contacto", fechaYHora); //se añade la fecha al formData

		var request = new XMLHttpRequest();
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				var res = request.responseText;
				if (res == 1) {
					Swal.fire({
						title: 'Exito!',
						text: 'Se actualizaron los campos',
						icon: 'sucess',
						confirmButtonText: 'Ok'
					}).then((result) => {
						window.location.reload();
					});
				} else {
					Swal.fire({
						title: 'Ooops!',
						text: res,
						icon: 'error',
						confirmButtonText: 'Ok'
					});
				}
			}
		}
		request.open("POST", url);
		request.send(formData);
	}



	function open_new_pqr() {
		$('#new_pqr').modal('show');
	}


	/*function para abrir modal con comnetario */

	function comentario(comen) {
		$('#modalcomentario').modal('show');
		document.getElementById('respuesta').innerHTML = comen;

	};

	/*function para abrir modal con comnetario */

	function comentariofinal(come) {
		$('#modalcomentariofinal').modal('show');
		document.getElementById('respuestacometariofinal').innerHTML = come;

	};
</script>
<script>
	function mostrarCerrados() {

		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				document.getElementById("cargando").style.display = "block";
				$('#tabla_data').DataTable().destroy();
				document.getElementById('tabla_data').innerHTML = xmlhttp.responseText;
				loadDataTable();
				document.getElementById("cargando").style.display = "none";
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>Informes/mostrarPqrCerrados", true);
		xmlhttp.send();
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
