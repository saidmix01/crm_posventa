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
			height: 500px;
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
		}
	</style>
	<!-- Main content -->
	<section class="content">
		<div class="card">
			<div class="card-body">
				<div><label class="col-lg-12 text-center lead">Horarios de Llegada y Salida</label></div>
				<hr>
				<form id="formulariobusqueda" method="post">
					<div class="form-row">
						<div class="col">
							<div class="form-group">
								<label for="sede">Sede</label>
								<select class="form-control" id="sede" name="sede">
									<option value="giron">Girón</option>
									<!--<option value="todas">Todas</option>-->
									<option value="rosita">La Rosita</option>
									<option value="bocono">Cúcuta Boconó</option>
									<option value="Malecon">Cúcuta Malecon</option>
									<option value="Barrancabermeja">Barrancabermeja</option>

								</select>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label for="sede">fecha Inicial</label>
								<input type="date" name="fecha_ini" id="fecha_ini" class="form-control">
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label for="sede">fecha Final</label>
								<input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
							</div>
						</div>
						<div class="col">
							
							<div class="form-group">
								<label for="sede">Empleado</label>
								<select class="form-control" id="nit" name="nit">
									<option value="">Seleccione un empleado...</option>
									<?php foreach ($data_emp->result() as $key){?>
										<option value="<?=$key->nit?>"><?=$key->nombres?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="col" align="center">
						<a href="#" onclick="filtar_horas();" class="btn btn-primary">Buscar</a>
					</div>
				</form>
				<hr>
				<div id="tablaresult"></div>
			</div>
		</div>
	</section>


	<!-- /.content -->
</div>
<?php $this->load->view('Informe_hora_laboral/footer') ?>
