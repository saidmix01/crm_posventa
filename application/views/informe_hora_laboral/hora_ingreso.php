<?php $this->load->view('Informe_hora_laboral/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<br>
	<style>
		.tx {
			transition: 0.4s ease;
			-moz-transition: 0.4s ease;
			-webkit-transition: 0.4s ease;
			-o-transition: 0.4s ease;
		}

		.tx:hover {
			transform: scale(1.3);
			-moz-transform: scale(1.3);
			-webkit-transform: scale(1.3);
			-o-transform: scale(1.3);
			-ms-transform: scale(1.3);
		}


		#padretabla {
			overflow: scroll;
			height: 350px;
			width: 100%;
		}

		#tabladatos {
			width: 100%;
		}

		thead tr th {
			position: sticky;
			top: 0;
			z-index: 10;
			background-color: black;
			color: white;
		}
	</style>
	<!-- Main content -->
	<section class="content">
		<div class="card">
			<div class="card-body">
			<h3 class="col-lg-12 text-center fant" style="font-family: fantasy;">Regitro Ingreso y Salida de <?php foreach ($userdata->result() as $key) {echo $key->nombres;} ?> </h3>
				<hr>
				<form id="formulariobusqueda" method="POST">
					<div class="form-row">
						<div class="col">
							<div class="form-group">
								<label for="sede">fecha</label>
								<input type="date" name="fecha_filtro" id="fecha_filtro" class="form-control">
							</div>
						</div>
					</div>
					<div class="col" align="center">
						<a href="#" onclick="filtar_horas_empleado();" class="btn btn-primary">Buscar</a>
					</div>
					<br>
					<div id="respuesta"></div>
				</form>
				<hr>
				<div class="table-responsive" id='padretabla'>
					<table class="table table-bordered table-hover" id="tablaprincipal">
						<thead>
							<tr>
								<th scope="col">Nombre</th>
								<th class="text-center" scope="col">Fecha</th>
								<th class="text-center" scope="col">Hora</th>
								<th class="text-center" scope="col">Accion</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($datos->result() as $info) {
								$color = $info->accion == 'Ingreso' ? "#AAE8FA" : "#FAAAB1";
							?>

								<tr style="background-color:<?= $color ?> ;">
									<td><?= $info->nombres ?></td>
									<td class="text-center"><?= $info->fechas ?></td>
									<td class="text-center"><?= $info->horas ?></td>
									<td class="text-center"><?= $info->accion ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					<div id="tabla_horario"></div>
				</div>
			</div>
		</div>
	</section>


	<!-- /.content -->
</div>
<?php $this->load->view('Informe_hora_laboral/footer') ?>
