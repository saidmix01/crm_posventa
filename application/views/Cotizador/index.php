<?php $this->load->view('Cotizador/header') ?>
<div class="content-wrapper">
	<section class="content">
		<div class="alert alert-light col-lg-12 text-center" role="alert">
			<h4>Cotización</h4>
		</div>
	</section>
	<!-- Main content -->
	<div class="loader" id="cargando"></div>
	<section class="content">

		<div class="card">

			<div class="card-header">
				<div class="row">
					<div class="col-12 col-md-4 col-sm-6">
						<button type="submit" class="btn btn-info" title="Informe de Livianos" onclick="loadInfoClientLivianos();">Livianos</button>
						<button type="submit" class="btn btn-info" title="Informe de Pesados" onclick="loadInfoClientPesados();">Pesados</button>
						<button type="submit" class="btn btn-success" id="btn_excel2" title="Descargar Excel" onclick="ResultsToTable2('Detalle Nomina Lamina y Pintura');">Exportar a Excel</button>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered" id="tablaCotizacion">
						<thead>
							<tr scope="row">
								<th scope="col">ID</th>
								<th scope="col">ASESOR</th>
								<th scope="col">PLACA</th>
								<th scope="col">CLASE</th>
								<th scope="col">MODELO</th>
								<th scope="col">KM</th>
								<th scope="col">REVISIÓN</th>
								<th scope="col">BODEGA</th>
								<th scope="col">ESTADO</th>
								<th scope="col">FECHA</th>
								<th class="noExl" scope="col">PDF</th>
								<th class="noExl" scope="col">EMAIL</th>
								<th class="noExl" scope="col">AGENDA</th>
							</tr>

						</thead>
						<tbody id="resultTableInf">

						</tbody>
					</table>
				</div>
			</div>

		</div>
	</section>
	<!-- /.content -->

</div>
<?php $this->load->view('Cotizador/footer') ?>
<script>
	const base_url = '<?php echo base_url(); ?>';

	$(document).ready(function() {
		
	});


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
		/* Creamos los input dentro del formulario creado anteriormente */
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

	function enviarEmailCotizacion(id, placa) {
		document.getElementById('cargando').style.display = 'block';
		var datos = new FormData();
		datos.append('id', id);
		datos.append('placa', placa);
		datos.append('estado', 1);
		/* datos.append('actualizarEstado', 'SI'); */
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
						showCloseButton: true,
						showDenyButton: true,
					}).then((result) => {
						/* Confirmar*/
						if (result.isConfirmed) {
							verCotizacion(id, placa);
							location.reload();
						} else {
							location.reload();
						}
					});

				} else if (resp === 'Error') {
					Swal.fire({
						title: 'Error',
						text: 'Hubo un error al enviar el correo, conctacte con un experto...',
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

	function actualizarEstado(id) {

		document.getElementById('cargando').style.display = 'block';
		var datos = new FormData();
		datos.append('id', id);
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
						text: 'Estado de agenda actualizada',
						icon: 'success',
						confirmButtonText: 'Ok',
						allowOutsideClick: false,
						showCloseButton: false,
						showDenyButton: false,
					}).then((result) => {
						/* Confirmar*/
						if (result.isConfirmed) {
							location.reload();
						}
					});

				} else if (resp === 'Error') {
					Swal.fire({
						title: 'Error',
						text: 'Ha ocurrido un error al actualizar el estado de agenda',
						icon: 'warning',
						confirmButtonText: 'Ok',
						allowOutsideClick: false,
						showCloseButton: false
					});

				}

			}
		}
		xmlhttp.open("POST", "<?= base_url() ?>Cotizador/actualizarEstado", true);
		xmlhttp.send(datos);

	}

	function ResultsToTable2() {
		$("#tablaCotizacion").table2excel({
			exclude: ".noExl",
			name: "Cotizaciones",
			filename: "Cotizaciones",
			preserveColors: true,
		});
	}

	function loadInfoClientLivianos() {

		document.getElementById('cargando').style.display = 'block';
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		/* xmlhttp.responseType = 'json'; */
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {

				if ($.fn.DataTable.isDataTable('#tablaCotizacion')) {
					$('#tablaCotizacion').dataTable().fnDestroy();
				}
				document.getElementById('resultTableInf').innerHTML = xmlhttp.responseText;
				loadDataTable();
				document.getElementById('cargando').style.display = 'none';
			}
		}
		xmlhttp.open("POST", "<?= base_url() ?>Cotizador/paintTableInfoCotizacion", true);
		xmlhttp.send();
	}

	function loadInfoClientPesados() {

		document.getElementById('cargando').style.display = 'block';
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		/* xmlhttp.responseType = 'json'; */
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				if ($.fn.DataTable.isDataTable('#tablaCotizacion')) {
					$('#tablaCotizacion').dataTable().fnDestroy();
				}
				document.getElementById('resultTableInf').innerHTML = xmlhttp.responseText;
				loadDataTable();
				document.getElementById('cargando').style.display = 'none';
			}
		}
		xmlhttp.open("POST", "<?= base_url() ?>CotizadorPesados/informeCotizacionPesados", true);
		xmlhttp.send();
	}

	function loadDataTable() {
		$('#tablaCotizacion').DataTable({
			"paging": true,
			"pageLength": -1,
			"lengthChange": true,
			"lengthMenu": [
				[-1, 10, 50, 100],
				["Todos", 10, 50, 100]
			],
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

	function verCotizacionP(id, placa) {
		/* Creamos un elemento tipo form->Formulario con metodo post y accion */
		var mapForm = document.createElement("form");
		mapForm.target = "Cotizacion";
		mapForm.method = "POST";
		mapForm.action = base_url + "CotizadorPesados/verPdfCotizacion";
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

	function enviarEmailCotizacionP(id, placa) {
		cargando.style.display = 'block';
		const infoEmail = new FormData();
		infoEmail.append('id_cotizacion', id);
		infoEmail.append('placa_vh', placa);
		infoEmail.append('estado', 1);

		fetch(base_url + "CotizadorPesados/sendEmailCotizacion", {
				headers: {
					"Content-type": "application/json",
				},
				mode: 'no-cors',
				method: "POST",
				body: infoEmail,
			})
			.then(function(response) {
				// Transforma la respuesta. En este caso lo convierte a JSON
				return response.json();
			})
			.then(function(json) {
				cargando.style.display = 'none';
				if (json['result'] === 'success') {
					Swal.fire({
						title: 'Exito',
						text: 'Se ha guardado la cotización y se ha enviado el correo con exito',
						icon: 'success',
						confirmButtonText: 'Ver cotizacion',
						denyButtonText: 'Cerrar',
						allowOutsideClick: false,
						showCloseButton: false,
						showDenyButton: true,
					}).then((result) => {
						/* Confirmar*/
						if (result.isConfirmed) {
							verCotizacionP(json['id_cotizacion'], json['placa_vh']);
						} else if (result.isDenied) {

						}
					});
				} else if (json['result'] === 'error') {
					swal.fire({
						icon: 'error',
						title: 'Advertencia',
						html: 'No se pudo guardar la cotización',
						confirmButtonText: 'OK',
					});
				}

			})
			.catch(function(error) {
				swal.fire({
					icon: 'error',
					title: 'Error',
					html: 'Ha ocurrido un error en la api de la intranet de postventa, intente nuevamente.',
					confirmButtonText: 'OK',
				});
			});
	}

	function actualizarEstadoP(id) {

		document.getElementById('cargando').style.display = 'block';
		var datos = new FormData();
		datos.append('id', id);
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
						text: 'Estado de agenda actualizada',
						icon: 'success',
						confirmButtonText: 'Ok',
						allowOutsideClick: false,
						showCloseButton: false,
						showDenyButton: false,
					}).then((result) => {
						/* Confirmar*/
						if (result.isConfirmed) {
							loadInfoClientPesados();
						}
					});

				} else if (resp === 'Error') {
					Swal.fire({
						title: 'Error',
						text: 'Ha ocurrido un error al actualizar el estado de agenda',
						icon: 'warning',
						confirmButtonText: 'Ok',
						allowOutsideClick: false,
						showCloseButton: false
					});
				}

			}
		}
		xmlhttp.open("POST", "<?= base_url() ?>CotizadorPesados/actualizarEstado", true);
		xmlhttp.send(datos);

	}
</script>