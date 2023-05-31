<?php $this->load->view('Cotizador/header') ?>
<div class="content-wrapper">
	<section class="content">
		<div class="alert alert-light col-lg-12 text-center" role="alert">
			<h4>Informe Cotizaciones Sacyr</h4>
		</div>
	</section>
	<!-- Main content -->
	<div class="loader" id="cargando"></div>
	<section class="content">

		<div class="card">

			<!-- <div class="card-header">
				
			</div> -->
			<div class="card-body">
				<div class="table-responsive text-center">
					<table class="table table-bordered" id="tablaCotizacion">
						<thead>
							<tr scope="row">
								<th scope="col">ID</th>
								<th scope="col">USUARIO</th>
								<th scope="col">PLACA</th>
								<th scope="col">NUMERO ORDEN</th>
								<th scope="col">MODELO</th>
								<th scope="col">KM</th>
								<th scope="col">FECHA</th>
								<th scope="col">OPCIONES</th>
							</tr>

						</thead>
						<tbody id="bodyTableCotizacion">

						</tbody>
					</table>
				</div>
			</div>

		</div>
	</section>
	<!-- /.content -->
	<div class="modal fade" id="editarCotizacionById" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Editar Cotización -  Autorizaciones</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
			<div class="col-12" id="bodyTableCotizacionId">

			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

</div>
<?php $this->load->view('Cotizador/footer') ?>
<script>
	$(document).ready(function() {
		CargarInformacion();
	});

	function CargarInformacion() {
		document.getElementById('cargando').style.display = 'block';
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		/* xmlhttp.responseType = 'json'; */
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				var resp = xmlhttp.responseText;
				document.getElementById('bodyTableCotizacion').innerHTML = resp;
				document.getElementById('cargando').style.display = 'none';
				addDataTable();

			}
		}
		xmlhttp.open("POST", "<?= base_url() ?>Sacyr/cargarInformeCotizacion", true);
		xmlhttp.send();
	}

	function verCotizacion(id, placa) {
		/* Creamos un elemento tipo form->Formulario con metodo post y accion */
		var mapForm = document.createElement("form");
		mapForm.target = "Cotizacion";
		mapForm.method = "POST";
		mapForm.action = "<?= base_url() ?>Sacyr/verPdfCotizacion";
		/* Creamos los input dentro del formulario creado anteriormente */
		var varId = document.createElement("input");
		varId.type = "hidden";
		varId.name = "id_cotizacion";
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

	function EditCotizacion(id) {
		/* Creamos un elemento tipo form->Formulario con metodo post y accion */
		var mapForm = document.createElement("form");
		mapForm.target = "EditarCotizacion";
		mapForm.method = "POST";
		mapForm.action = "<?= base_url() ?>Sacyr/EditarCotizacion";
		/* Creamos los input dentro del formulario creado anteriormente */
		var varId = document.createElement("input");
		varId.type = "hidden";
		varId.name = "id_cotizacion";
		varId.value = id;
		mapForm.appendChild(varId);
		/* Agregamos el formulario creado al body */
		document.body.appendChild(mapForm);
		/* Script para abrir una nueva ventana */
		/* map = window.open('http://webdesign.about.com/','open_window', 'menubar, toolbar, location, directories, status, scrollbars, resizable,dependent, width = 640, height = 480, left = 0, top = 0 ') */
		map = window.open("", "EditarCotizacion", "menubar, toolbar, location, directories, status, scrollbars, resizable,dependent,,title=editar cotizacion,height=auto,width=auto,scrollbars=1");

		if (map) {
			mapForm.submit();
		}
	}

	function copy_Cotizacion(id, placa) {
		/* Creamos un elemento tipo form->Formulario con metodo post y accion */
		var mapForm = document.createElement("form");
		mapForm.target = "CopyCotizacion";
		mapForm.method = "POST";
		mapForm.action = "<?= base_url() ?>Sacyr/CopyCotizacion";
		/* Creamos los input dentro del formulario creado anteriormente */
		var varId = document.createElement("input");
		varId.type = "hidden";
		varId.name = "id_cotizacion";
		varId.value = id;
		mapForm.appendChild(varId);

		var varPlaca = document.createElement("input");
		varPlaca.type = "hidden";
		varPlaca.name = "placa";
		varPlaca.value = placa;
		mapForm.appendChild(varPlaca);

		var varOpcion = document.createElement("input");
		varOpcion.type = "hidden";
		varOpcion.name = "opcion";
		varOpcion.value = 1; //Duplicar cotización
		mapForm.appendChild(varOpcion);

		/* Agregamos el formulario creado al body */
		document.body.appendChild(mapForm);
		/* Script para abrir una nueva ventana */
		/* map = window.open('http://webdesign.about.com/','open_window', 'menubar, toolbar, location, directories, status, scrollbars, resizable,dependent, width = 640, height = 480, left = 0, top = 0 ') */
		map = window.open("", "CopyCotizacion", "menubar, toolbar, location, directories, status, scrollbars, resizable,dependent,,title=editar cotizacion,height=auto,width=auto,scrollbars=1");

		if (map) {
			mapForm.submit();
		}
	}

	function addDataTable() {
		$('#tablaCotizacion').DataTable({
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

	function guardarOrdenT(id) {
		const ordenT = document.getElementById('ordenT').value;
		if (ordenT != "") {
			document.getElementById('cargando').style.display = 'block';
			var datos = new FormData();
			datos.append('ordenT', ordenT);
			datos.append('id', id);
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			/* xmlhttp.responseType = 'json'; */
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					if (xmlhttp.responseText === 'success') {
						Swal.fire({
							title: 'Exito!',
							text: 'Se ha realizado el registro con exito',
							icon: 'success',
							confirmButtonText: 'Ok',
							willClose: () => {
								location.reload();
							}
						});
					} else {
						Swal.fire({
							title: 'Error!',
							text: 'Ha ocurrido un error, recargue la pagina y intentenuevamente',
							icon: 'error',
							confirmButtonText: 'Ok',
							willClose: () => {
								location.reload();
							}
						});
					}

					document.getElementById('cargando').style.display = 'none';


				}
			}
			xmlhttp.open("POST", "<?= base_url() ?>Sacyr/insertOrdenT", true);
			xmlhttp.send(datos);
		} else {
			Swal.fire({
				title: 'Advertencia!',
				text: 'El campo se encuentra vacio',
				icon: 'warning',
				confirmButtonText: 'Ok'
			});
		}
	}
</script>