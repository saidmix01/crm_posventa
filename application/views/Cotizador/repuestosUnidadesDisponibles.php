<?php $this->load->view('Cotizador/header') ?>
<div class="content-wrapper">
	<section class="content">
		<div class="alert alert-light col-lg-12 text-center" role="alert">
			<h4>REPUESTOS DE COTIZACIÓN</h4>
		</div>
	</section>
	<!-- Main content -->
	<div class="loader" id="cargando"></div>
	<section class="content">

		<div class="card">

			<div class="card-header">
				<div class="row">
				<div class="col-12 col-md-2 col-sm-6">
							<label class="control-label">Bodega:</label>
							<select class="form-control js-example-basic-single" type="text" id="bodega" name="bodega">
								<option value="">Seleccione una opción</option>
								<option value="1">Girón Gasolina</option>
								<option value="6">Barranca</option>
								<option value="7">Rosita</option>
								<option value="8">Cúcuta</option>

							</select>
						</div>
					<div class="col-12 col-md-4 col-sm-5">
					<!-- Cargamos la fecha por semana -->
					<?php
					 $date = date('Y-m-d');
					?>
						<label>Fecha Inicio</label>
						<input class="form-control" type="date" name="f_inicio" id="f_inicio" value="<?= date("Y-m-d",strtotime($date."- 1 week")); ?>">
					</div>
					<div class="col-12 col-md-4 col-sm-5">
						<label>Fecha Final</label>
						<input class="form-control" type="date" name="f_final" id="f_final" value="<?=$date?>">
					</div>
					<div class="col-12 col-md-2 col-sm-2" style="align-self: flex-end ;">
						<button class="btn btn-success" onclick="cargarDataRepuestos();">Buscar</button>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered text-center" id="tablaRepuestos">
						<thead>
							<tr scope="row">
								<th scope="col">BODEGA</th>
								<th scope="col">CANTIDAD</th>
								<th scope="col">CODIGO</th>
								<th scope="col">DESCRIPCIÓN</th>
							</tr>

						</thead>
						<tbody id="bodyRepuestos">
							
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
		cargarDataRepuestos();
		$('.js-example-basic-single').select2({
			width: '100%',
			placeholder: 'Seleccione una opción',
			theme: "classic"
		});
	});

	function cargarDatatable() {
		$('#tablaRepuestos').DataTable({
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

	function cargarDataRepuestos(){
		
		var f_final = document.getElementById('f_final').value;
		var f_inicio = document.getElementById('f_inicio').value;
		var bodega = document.getElementById('bodega').value;

		if(f_final > f_inicio){
			document.getElementById('cargando').style.display = 'block';
			var datos = new FormData();
			datos.append('f_inicio', f_inicio);
			datos.append('f_final', f_final);
			datos.append('bodega', bodega);
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			/* xmlhttp.responseType = 'json'; */
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					document.getElementById('cargando').style.display = 'none';
					$('#tablaRepuestos').dataTable().fnDestroy();
					document.getElementById('bodyRepuestos').innerHTML = xmlhttp.responseText;
					cargarDatatable();

				}
			}
			xmlhttp.open("POST", "<?= base_url() ?>Cotizador/repuestosUndDispCargar", true);
			xmlhttp.send(datos);
		}else {
			Swal.fire({
				title: 'Advertencia!',
				text: 'La fecha de inicio debe ser menor a la fecha final',
				icon: 'warning',
				confirmButtonText: 'Ok'
			});
		}

		

		

	}
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 5000
	});
</script>

