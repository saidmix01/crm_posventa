<?php $this->load->view('Cotizador/header') ?>
<div class="content-wrapper">
	<section class="content">
		<div class="alert alert-light col-lg-12 text-center" role="alert">
			<h4>Control Repuestos</h4>
		</div>
	</section>
	<!-- Main content -->
	<div class="loader" id="cargando"></div>
	<section class="content">

		<div class="card">

			<div class="card-header">
				<div class="row">
					<h3 class="card-title">Control repuestos cotizaciones</h3>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover" id="tabla1">
						<thead>
							<tr style="vertical-align: middle;text-align: center; background-color: #ff000080" scope="row">
								<td colspan="13">TOTAL DISPONIBLE ES INFERIOR A LA CANTIDAD AGENDADA O EL TOTAL DISPONIBLE ES EQUIVALENTE A "0"</td>
							</tr>
							<tr style="vertical-align: middle;text-align: center;" scope="row">
								<th style="vertical-align: middle;text-align: center;" rowspan="2" scope="col">REFERENCIA</th>
								<th colspan="2" scope="col">PRINCIPAL</th>
								<th colspan="2" scope="col">BARRANCABERMEJA</th>
								<th colspan="2" scope="col">ROSITA</th>
								<th colspan="2" scope="col">CÚCUTA</th>
								<th colspan="4" scope="col">TOTALES</th>
							</tr>
							<tr style="vertical-align: middle;text-align: center;">
								<th scope="col">CANTIDAD</th>
								<th scope="col">UND</th>
								<th scope="col">CANTIDAD</th>
								<th scope="col">UND</th>
								<th scope="col">CANTIDAD</th>
								<th scope="col">UND</th>
								<th scope="col">CANTIDAD</th>
								<th scope="col">UND</th>
								<th scope="col">CANTIDAD</th>
								<th scope="col">DISPONIBLES</th>
								<th scope="col">STOCK MIN</th>
								<th scope="col">STOCK MAX</th>
							</tr>

						</thead>
						<tbody>
							<?php
							/* if ($perfil == 33 || $perfil==1 || $perfil==20) { */

							foreach ($dataCotizaciones->result() as $key) {
								//Suma de las cantidades agendadas
								$sumCant = $key->principal + $key->barranca + $key->rosita + $key->villa;
								//Suma de las cantidades disponibles
								$sumDisponibles = $key->disp_principal + $key->disp_barranca + $key->disp_rosita + $key->disp_villa;
								//Suma de stock min
								$sumaMin = $key->min_principal + $key->min_barranca + $key->min_rosita + $key->min_villa;
								//Suma de stock max
								$sumaMax = $key->max_principal + $key->max_barranca + $key->max_rosita + $key->max_villa;

								//VALIDAR SI LA SUMA DE LOS DISPONIBLES ES IGUAL A 0 Y LA SUMA DE LAS CANTIDADES ES MAYOR AL DISPONIBLE
								if ($sumCant > $sumDisponibles || $sumDisponibles == 0) {

							?>
									<tr class="text-center">
										<td scope="col"><?= $key->codigo ?></td>
										<td scope="col"><?= $key->principal ?></td>
										<td scope="col"><?= number_format($key->disp_principal, 0, ',', ' ') ?></td>
										<td scope="col"><?= $key->barranca ?></td>
										<td scope="col"><?= number_format($key->disp_barranca, 0, ',', ' ') ?></td>
										<td scope="col"><?= $key->rosita ?></td>
										<td scope="col"><?= number_format($key->disp_rosita, 0, ',', ' ') ?></td>
										<td scope="col"><?= $key->villa ?></td>
										<td scope="col"><?= number_format($key->disp_villa, 0, ',', ' ') ?></td>
										<td scope="col"><?= $sumCant ?></td>
										<td scope="col"><?= $sumDisponibles ?></td>
										<td scope="col"><?= $sumaMin ?></td>
										<td scope="col"><?= $sumaMax ?></td>
									</tr>
							<?PHP
								}
							}
							?>
						</tbody>
					</table>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered table-hover" id="tabla2">
						<thead>
							<tr style="vertical-align: middle;text-align: center; background-color: #ffff0080" scope="row">
								<td colspan="13">TOTAL DISPONIBLE INFERIOR AL STOCK MIN</td>
							</tr>
							<tr style="vertical-align: middle;text-align: center;" scope="row">
								<th style="vertical-align: middle;text-align: center;" rowspan="2" scope="col">REFERENCIA</th>
								<th colspan="2" scope="col">PRINCIPAL</th>
								<th colspan="2" scope="col">BARRANCABERMEJA</th>
								<th colspan="2" scope="col">ROSITA</th>
								<th colspan="2" scope="col">CÚCUTA</th>
								<th colspan="4" scope="col">TOTALES</th>
							</tr>
							<tr style="vertical-align: middle;text-align: center;">
								<th scope="col">CANTIDAD</th>
								<th scope="col">UND</th>
								<th scope="col">CANTIDAD</th>
								<th scope="col">UND</th>
								<th scope="col">CANTIDAD</th>
								<th scope="col">UND</th>
								<th scope="col">CANTIDAD</th>
								<th scope="col">UND</th>
								<th scope="col">CANTIDAD</th>
								<th scope="col">DISPONIBLES</th>
								<th scope="col">STOCK MIN</th>
								<th scope="col">STOCK MAX</th>
							</tr>

						</thead>
						<tbody>
							<?php
							/* if ($perfil == 33 || $perfil==1 || $perfil==20) { */

							foreach ($dataCotizaciones->result() as $key) {
								//Suma de las cantidades agendadas
								$sumCant = $key->principal + $key->barranca + $key->rosita + $key->villa;
								//Suma de las cantidades disponibles
								$sumDisponibles = $key->disp_principal + $key->disp_barranca + $key->disp_rosita + $key->disp_villa;
								//Suma de stock min
								$sumaMin = $key->min_principal + $key->min_barranca + $key->min_rosita + $key->min_villa;
								//Suma de stock max
								$sumaMax = $key->max_principal + $key->max_barranca + $key->max_rosita + $key->max_villa;
								//VALIDAR SI LA SUMA DE LOS DISPONIBLES ES IGUAL A 0
								if ($sumDisponibles != 0 && $sumDisponibles > $sumCant && $sumDisponibles < $sumaMin ) {

							?>
								<tr class="text-center">
									<td scope="col"><?= $key->codigo ?></td>
									<td scope="col"><?= $key->principal ?></td>
									<td scope="col"><?= number_format($key->disp_principal, 0, ',', ' ') ?></td>
									<td scope="col"><?= $key->barranca ?></td>
									<td scope="col"><?= number_format($key->disp_barranca, 0, ',', ' ') ?></td>
									<td scope="col"><?= $key->rosita ?></td>
									<td scope="col"><?= number_format($key->disp_rosita, 0, ',', ' ') ?></td>
									<td scope="col"><?= $key->villa ?></td>
									<td scope="col"><?= number_format($key->disp_villa, 0, ',', ' ') ?></td>
									<td scope="col"><?= $sumCant ?></td>
									<td scope="col"><?= $sumDisponibles ?></td>
									<td scope="col"><?= $sumaMin ?></td>
									<td scope="col"><?= $sumaMax ?></td>
								</tr>
							<?PHP
								}
							}
							?>
						</tbody>
					</table>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered table-hover" id="tabla3">
						<thead>
							<tr style="vertical-align: middle;text-align: center; background-color: #0000ff80" scope="row">
								<td colspan="13">TOTAL DISPONIBLE ES SUPERIOR AL STOCK MIN</td>
							</tr>
							<tr style="vertical-align: middle;text-align: center;" scope="row">
								<th style="vertical-align: middle;text-align: center;" rowspan="2" scope="col">REFERENCIA</th>
								<th colspan="2" scope="col">PRINCIPAL</th>
								<th colspan="2" scope="col">BARRANCABERMEJA</th>
								<th colspan="2" scope="col">ROSITA</th>
								<th colspan="2" scope="col">CÚCUTA</th>
								<th colspan="4" scope="col">TOTALES</th>
							</tr>
							<tr style="vertical-align: middle;text-align: center;">
								<th scope="col">CANTIDAD</th>
								<th scope="col">UND</th>
								<th scope="col">CANTIDAD</th>
								<th scope="col">UND</th>
								<th scope="col">CANTIDAD</th>
								<th scope="col">UND</th>
								<th scope="col">CANTIDAD</th>
								<th scope="col">UND</th>
								<th scope="col">CANTIDAD</th>
								<th scope="col">DISPONIBLES</th>
								<th scope="col">STOCK MIN</th>
								<th scope="col">STOCK MAX</th>
							</tr>

						</thead>
						<tbody>
							<?php
							/* if ($perfil == 33 || $perfil==1 || $perfil==20) { */

							foreach ($dataCotizaciones->result() as $key) {
								//Suma de las cantidades agendadas
								$sumCant = $key->principal + $key->barranca + $key->rosita + $key->villa;
								//Suma de las cantidades disponibles
								$sumDisponibles = $key->disp_principal + $key->disp_barranca + $key->disp_rosita + $key->disp_villa;
								//Suma de stock min
								$sumaMin = $key->min_principal + $key->min_barranca + $key->min_rosita + $key->min_villa;
								//Suma de stock max
								$sumaMax = $key->max_principal + $key->max_barranca + $key->max_rosita + $key->max_villa;
								//VALIDAR SI LA SUMA DE LOS DISPONIBLES ES IGUAL A 0
								if ($sumDisponibles != 0 && $sumDisponibles > $sumCant && $sumDisponibles > $sumaMin )  {

							?>
								<tr class="text-center">
									<td scope="col"><?= $key->codigo ?></td>
									<td scope="col"><?= $key->principal ?></td>
									<td scope="col"><?= number_format($key->disp_principal, 0, ',', ' ') ?></td>
									<td scope="col"><?= $key->barranca ?></td>
									<td scope="col"><?= number_format($key->disp_barranca, 0, ',', ' ') ?></td>
									<td scope="col"><?= $key->rosita ?></td>
									<td scope="col"><?= number_format($key->disp_rosita, 0, ',', ' ') ?></td>
									<td scope="col"><?= $key->villa ?></td>
									<td scope="col"><?= number_format($key->disp_villa, 0, ',', ' ') ?></td>
									<td scope="col"><?= $sumCant ?></td>
									<td scope="col"><?= $sumDisponibles ?></td>
									<td scope="col"><?= $sumaMin ?></td>
									<td scope="col"><?= $sumaMax ?></td>
								</tr>
							<?PHP
								}
							}
							?>
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
		//Tabla no lo tengo::: Unidades disponibles equivalente a 0
		$('#tabla1').DataTable({
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
		//Tabla por debajo del minimo
		$('#tabla2').DataTable({
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
		//Tabla todo bien
		$('#tabla3').DataTable({
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