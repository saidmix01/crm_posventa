<?php $this->load->view('Cotizador/header') ?>

<div class="content-wrapper">
	<section class="content">
		<div class="alert alert-light col-lg-12 text-center" role="alert">
			<h4>Agregar Campo de Busqueda Repuestos</h4>
		</div>
	</section>
	<!-- Main content -->
	<div class="loader" id="cargando"></div>
	<section class="content">

		<div class="card">

			<!-- <div class="card-header">
				
			</div> -->
			<div class="card-body">
				<div class="row table-responsive text-center">
					<table class="table table-bordered" id="tablaCotizacion">
						<thead>
							<tr>
								<th scope="col">CÓDIGO</th>
								<th scope="col">DESCRIPCIÓN</th>
								<th scope="col">BUSQUEDA</th>
								<th scope="col">VALOR UNITARIO</th>
								<th scope="col">VALOR CONTRATO</th>
								<th scope="col">CANTIDAD CONTRATO</th>
								<th scope="col">DISPONIBLES</th>
								<th scope="col">BODEGA</th>
								<th scope="col">OPCIÓN</th>
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

</div>
<?php $this->load->view('Cotizador/footer') ?>
<script>
	$(document).ready(function() {


		CargarInformacion();



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

			}
		}
		xmlhttp.open("POST", "<?= base_url() ?>Sacyr/cargarRepuestos", true);
		xmlhttp.send();
	}

	function addItem(codigo, id_busqueda) {
		var fiedSearch = document.getElementById(id_busqueda).value;
		if (fiedSearch != "" && fiedSearch.length > 6) {
			
			var datos = new FormData();
			datos.append('codigo', codigo);
			datos.append('fiedSearch', fiedSearch);
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			/* xmlhttp.responseType = 'json'; */
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					var resp = xmlhttp.responseText;
					console.log(resp);
					if (resp) {
						Swal.fire({
							title: 'Exito',
							html: `<p>Se ha realizdo el registro en la base de datos</p>`,
							icon: 'success',
							confirmButtonText: 'Ok',
							denyButtonText: 'Cancelar',
							showDenyButton: false,
							allowOutsideClick: false,
							allowEscapeKey: false,
							showCloseButton: false
						}).then((result) => {
							if (result.isConfirmed) {
								location.reload();
							}

						});
					} else {
						Swal.fire({
							title: 'Advertencia',
							html: `<p>Ha ocurrido un error, intente nuevamente...</p>`,
							icon: 'warning',
							confirmButtonText: 'Ok',
						});
					}

					document.getElementById('cargando').style.display = 'none';

				}
			}
			xmlhttp.open("POST", "<?= base_url() ?>Sacyr/addFieldSearch", true);
			xmlhttp.send(datos);
		} else {
			Swal.fire({
				title: 'Advertencia',
				html: `<p>Debe completar el campo de busqueda y que sea mayor a 7 caracteres</p>`,
				icon: 'warning',
				confirmButtonText: `OK`,

			}).then((result) => {
				if (result.isConfirmed) {
					const newspaperSpinning = [{
							background: "#ff000059"
						},
						{
							background: "none"
						}
					];

					const newspaperTiming = {
						duration: 500,
						iterations: 5,
					}
					document.getElementById(id_busqueda).animate(newspaperSpinning, newspaperTiming);
					document.getElementById(id_busqueda).focus();

				}

			});

		}

		

	}
</script>