<?php $this->load->view('Informe_nps/header.php'); ?>

<?php
$mesactual = date('m');
$yearactual = date('Y');
$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

?>

<style>
	.padretabla {
		overflow: scroll;
		height: 400px;
		width: 100%;
	}

	.tabladatos {
		width: 100%;
	}

	thead tr th {
		position: sticky;
		top: 0;
		z-index: 10;
		background-color: white;
		color: black;
	}
</style>
<div class="content-wrapper">
	<section class="content">
		<div class="card">
			<div class="card-body">
				<h3 class="col-lg-12 text-center fant" style="font-family: fantasy;">Informe NPS Final</h3>
				<hr>
				<form id="formularionpsfinal" method="POST" class="">
					<div class="form-row">
						<div class="col">
							<div class="form-group">
								<label for="nps">NPS</label>
								<select class="form-control" id="nps" name="nps">
									<option value="nps_int">NPS Interno</option>
									<option value="nps_col">NPS Colmotores</option>
								</select>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label for="npsvista">VISTA</label>
								<select class="form-control" id="npsvista" name="npsvista" onchange="vista()">
									<option value="info_tabla">Tabla</option>
									<option value="info_grafica">Grafica</option>
								</select>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label for="fechanps">AÑO</label>
								<select class="form-control" id="fechanps" name="fechanps">
									<option value="2021">2021</option>
									<option value="2022">2022</option>
								</select>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label for="mesnps">MES</label>
								<select class="form-control meses" multiple="multiple" id="mesnps" name="mesnps">
									<option id="mestodos" value="todos">Todos</option>
									<option value="1">Enero</option>
									<option value="2">Febrero</option>
									<option value="3">Marzo</option>
									<option value="4">Abril</option>
									<option value="5">Mayo</option>
									<option value="6">Junio</option>
									<option value="7">Julio</option>
									<option value="8">Agosto</option>
									<option value="9">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label for="sedenps">SEDE</label>
								<select class="form-control sedenps" multiple="multiple" id="sedenps" name="sedenps">
									<option id="sedetodos" value="giron,rosita,bocono,barranca">Todas</option>
									<option value="giron">Girón</option>
									<option value="rosita">La Rosita</option>
									<option value="bocono">Cúcuta Boconó</option>
									<option value="barranca">Barrancabermeja</option>
								</select>
							</div>
						</div>
					</div>
					<hr>
					<div class="col">
						<input type="button" id="buscar" class="btn btn-primary" value="Buscar">
					</div>
			</div>

			</form>
			<hr>
			<div class="card">
				<div class="card-header" id="btn-filtro">
					<!--<a href="#" class="btn btn-success" onclick="bajar_excel_nps();"><i class="far fa-file-excel"></i> Exportar a excel</a>-->
				</div>
				<div class="card-body">
					<div class="table-responsive" id="">
						<!--espacio para la tabla-->
						<div id="resultado_tabla"></div>
						<!--espacio para la grafica-->
						<div id="grafica" style="height: 370px; width: 100%;"></div>
					</div>
				</div>
			</div>
		</div>
</div>
</section>
</div>

<?php $this->load->view('Informe_nps/footer.php'); ?>
