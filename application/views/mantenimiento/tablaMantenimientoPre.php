<?php $this->load->view('mantenimiento/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<br>
	<!-- Main content -->
	<section class="content">
		<!-- contenido aqui -->
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="javascript:history.back()"><i class="fas fa-undo"></i> Volver Atrás</a></li>
			</ol>
		</nav>
		<div class="card">
			<div class="card-header" align="center">
				<h3>Mantenimientos preventivos</h3>
			</div>
			<div class="card-body">

				<hr>
				<div class="table-responsive">
					<!--  Tabla usuarios  -->
					<table id="example1" class="table table-sm table-bordered table-hover">
						<thead>
							<tr align="center">
								<th>CODIGO</th>
								<th>NOMBRE</th>
								<th>DESCRIPCION MANTENIMIENTO</th>
								<th>AREA</th>
								<th>BODEGA</th>
								<th>FECHA REQUERIDA</th>
								<th>VER</th>
								<th>ELIMINAR</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($cronograma->result() as $key) {
							?>
								<tr align="center">
									<td><?= $key->codigo ?></td>
									<td><?= $key->nombre_equipo ?></td>
									<td><?= $key->descripcion ?></td>
									<td><?= $key->area ?></td>
									<td><?= $key->bodega ?></td>
									<td><?= $key->fecha_requerida ?></td>
									<td><button onclick="ver_orden_mto(<?= $key->id_mantenimientos ?>);" type="button" class="btn btn-outline-primary"><i class="fas fa-table"></i></button></td>
									<td><button onclick="eliminar_orden_mto(<?= $key->id_mantenimientos ?>,'<?= $key->nombre_equipo ?>');" type="button" class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button></td>
								</tr>
							<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- Modal ver detalle de mantenimiento-->
		<div class="modal fade" id="modal_detalle_mto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Orden de Mantenimiento Preventivo</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div id="OrdenMantenimientoId"></div>
				</div>
			</div>
		</div>
	</section>
	<script>

	</script>

	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php $this->load->view('mantenimiento/footer') ?>
<script>
	/* Ver información detallada del mantenimiento preventivo segun la planeacion o cronograma */
	function ver_orden_mto(id) {
		var respuesta = document.getElementById('OrdenMantenimientoId');
		var xmlhttp1;
		if (window.XMLHttpRequest) {
			xmlhttp1 = new XMLHttpRequest();
		} else {
			xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp1.onreadystatechange = function() {
			if (xmlhttp1.readyState === 4 && xmlhttp1.status === 200) {
				var flag1 = xmlhttp1.response;
				if (flag1 == "") {
					Toast.fire({
						type: 'error',
						title: ' No hay datos'
					});
				} else {
					respuesta.innerHTML = xmlhttp1.responseText;
					$('#modal_detalle_mto').modal('show');
				}
			}
		}
		xmlhttp1.open("GET", "<?= base_url() ?>mantenimiento/pintarDatosCorrectivoTableById?id=" + id, true);
		xmlhttp1.send();
	}

	function eliminar_orden_mto(id, nombre) {
		Swal.fire({
			title: 'Estas seguro de eliminar la orden de mantenimiento preventivo del equipo: ' + nombre + ' ?',
			text: "¡No podrás revertir esto!",
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Si, eliminar!',
			cancelButtonText: 'No, cancelar'
		}).then((result) => {
			if (result.isConfirmed) {

				var xmlhttp1;
				if (window.XMLHttpRequest) {
					xmlhttp1 = new XMLHttpRequest();
				} else {
					xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp1.onreadystatechange = function() {
					if (xmlhttp1.readyState === 4 && xmlhttp1.status === 200) {
						Swal.fire(
							'Orden eliminada!',
							'La orden de mantenimiento ha sido eliminada!',
							'success'
						)
						location.reload();
					}
				}
				xmlhttp1.open("GET", "<?= base_url() ?>mantenimiento/eliminarOrdenMto?id=" + id, true);
				xmlhttp1.send();


			}
		});
	}

	
	$(document).ready(function() {
		$('#example1').DataTable({
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

</script>
