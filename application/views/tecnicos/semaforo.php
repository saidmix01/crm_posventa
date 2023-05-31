<?php $this->load->view('Tecnicos/header') ?>
<style>
	.select2-container .select2-selection--single {
		height: auto;
	}

	.semaforoRojo {
		height: 50px;
		width: 50px;
		border: 1px solid red;
		background: red;
		margin: auto;
		align-self: center;
	}

	.semaforoAmarillo {
		height: 50px;
		width: 50px;
		border: 1px solid orange;
		background: orange;
		margin: auto;
		align-self: center;
	}

	.semaforoVerde {
		height: 50px;
		width: 50px;
		border: 1px solid green;
		background: green;
		margin: auto;
		align-self: center;
	}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<br>
	<!-- Main content -->
	<section class="content">
		<div class="card">
			<div class="card-header" align="center">
				<h3><strong>Semaforo - Salida o reintegro de Vehiculos</strong></h3>
			</div>
			<div class="card-body">

				<form>
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<div class="form-group">
								<label>Placa:</label>
								<input type="text" name="placa" id="placa">
							</div>
						</div>

						<div class="col-md-4 mb-3">
							<div class="form-group">
								<label>Orden de trabajo:</label>
								<select class="custom-select js-example-basic-single" name="ordenT" id="ordenT" required>
									<option value="">Seleccione la orden de trabajo</option>
									<option value="1">#0001</option>
									<option value="2">#0002</option>
									<option value="3">#0003</option>
								</select>
							</div>
						</div>
						<div class="col-md-4 mb-3">
							<div class="form-group">
								<label>Motivo:</label>
								<select class="custom-select js-example-basic-single" name="motivoV" id="motivoV" onchange="areaOtro(this.value);" required>
									<option value="">Seleccione el motivo de la visita</option>
									<option value="1">#0001</option>
									<option value="2">#0002</option>
									<option value="3">#0003</option>
									<option value="otroMotivo">Otro</option>
								</select>
							</div>
						</div>
						<div class="col" id="otroM" style="display:none;">
							<div class="form-group">
								<label for="contenido">Otro motivo:</label>
								<textarea class="form-control" id="contenido" name="contenido"></textarea>
							</div>
						</div>
					</div>
					<div class="form-row">

						<div class="col-sm-2 align-self-center" >
							<div class="rounded-circle semaforoRojo" onclick="cambiarSemaforo('rojo');"></div>
						</div>
						<div class="col">
							<label for="contenido">Semaforo rojo:</label>
							<textarea disabled class="form-control" id="contenidoRojo" name="contenido"></textarea>
						</div>


					</div>
					<div class="form-row">

						<div class="col-sm-2 align-self-center">
							<div class="rounded-circle semaforoAmarillo" value="amarillo" onclick="cambiarSemaforo('amarillo');"></div>
						</div>
						<div class="col">
							<label for="contenido">Semaforo naranja:</label>
							<textarea disabled class="form-control" id="contenidoAmarillo" name="contenido"></textarea>
						</div>

					</div>
					<div class="form-row">

						<div class="col-sm-2 align-self-center">
							<div class="rounded-circle semaforoVerde" value="verde" onclick="cambiarSemaforo('verde');">
							</div>
						</div>
						<div class="col">
							<label for="contenido">Semaforo verde:</label>
							<textarea disabled class="form-control" id="contenidoVerde" name="contenido"></textarea>
						</div>

					</div>


				</form>

			</div>
			<div class="card-footer">
			<div class="col justify-content-center">
					<input type="submit" class="btn btn-success" value="Guardar">
					
				</div>

			</div>
		</div>
	</section>
</div>
<?php $this->load->view('Tecnicos/footer') ?>
