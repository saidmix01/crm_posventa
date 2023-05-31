<?php $this->load->view('administracion/header');
$this->load->model('AdministracionCodiesel');
$usu = $this->session->userdata('user');
$isjefe = $this->AdministracionCodiesel->val_jefe($usu)->n;
if ($isjefe != 0) {
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<br>
	<!-- Main content -->
	<section class="content">
		<div class="card">
			<div class="card-body">
				<div id="divBtnModal" class="row mb-3">
					<div class="col-md-12">
						<a id="btnModal" href="#" class="btn btn-success" onclick="$('#modal_au').modal('show');">Nuevo Ausentismo</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header" align="center">
								<h4>Evaluacion de desempeño</h4>
							</div>
							<div class="card-body p-0" align="center">
								<div class="container row">
									<div class="col-md-6">
										<label>Nombre del empleado a valorar</label>
										<select class="form-control js-example-basic-single" style="width: 100%" id="combo_empleado">
											<option value="">Seleccione un empleado...</option>
											<?php foreach ($empleados->result() as $key) {?>
												<option value="<?=$key->nit?>"><?=$key->nombres?></option>
											<?php } ?>
										</select>
									</div>
									<div class="col-md-6">
										<label>Area</label>
										<select class="form-control js-example-basic-single" style="width: 100%" id="combo_area">
											<option value="">Seleccione una opción</option>
											<option value="Administración">Administración</option>
											<option value="Central de Beneficios">Central de Beneficios</option>
											<option value="Vehiculos Nuevos">Vehiculos Nuevos</option>
											<option value="Vehiculos Usados">Vehiculos Usados</option>
											<option value="Repuestos">Repuestos</option>
											<option value="Taller Gasolina">Taller Gasolina</option>
											<option value="Taller Diesel">Taller Diesel</option>
											<option value="Lamina y Pintura">Lamina y Pintura</option>
											<option value="Alistamiento">Alistamiento</option>
											<option value="Contac Center">Contac Center</option>
											<option value="Accesorios">Accesorios</option>
										</select>
									</div>
								</div>
								<div class="row container">
									<div class="col-md-6">
										<label>Cargo</label>
										<input type="text" name="cargo" id="cargo" placeholder="Escriba el cargo del empleado" class="form-control">
									</div>
									<div class="col-md-6">
										<label>Sede</label>
										<select class="form-control js-example-basic-single" style="width: 100%" id="combo_sede">
											<option value="">Seleccione una sede</option>
											<option value="giron">Girón</option>
											<option value="rosita">La Rosita</option>
											<option value="barranca">Barrancabermeja</option>
											<option value="bocono">Cucuta Boconó</option>
											<option value="malecon">Cucuta Malecon</option>
											<option value="solochevrolet">SoloChevrolet</option>
											<option value="chevropartes">Chevropartes</option>
										</select>
									</div>
								</div>
								<br>
								<div class="row container">
									<div class="col-md-12">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<td style="width: 50%;"><strong>Fecha:</strong></td>
													<td style="width: 50%;"><strong><?php echo date('d-m-Y') ?></strong></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="row container">
									<div class="col-md-12" align="justify">
										<p>OBJETIVO:  Este formulario de Evaluación de Desempeño tiene como objetivo complementar la información obtenida sobre el comportamiento laboral de los empleados en los últimos doce (12) meses, para así lograr una apreciación real y objetiva del rendimiento actual y del desarrollo futuro de cada funcionario.</p>
									</div>
								</div>
								<div class="row container">
									<div class="col-md-12" align="justify">
										<p>Marque la Alternativa de Respuesta que caracteriza a su colaborador.</p>
									</div>
								</div>
								<hr>
								<div class="row container" align="center">
									<div class="col-md-12">
										<div class="table-responsive">
											<table class="table table-bordered">
												<thead>
													<tr align="center">
														<th scope="col">#</th>
														<th scope="col">FACTORES Y SUS RESPECTIVAS DEFINICIONES</th>
														<th scope="col">SOBRESALIENTE</th>
														<th scope="col">BUENO</th>
														<th scope="col">REGULAR</th>
													</tr>
												</thead>
												<tbody>
													<?php 
													$cant_preguntas = count($preguntas->result());
													foreach ($preguntas->result() as $key) { ?>
													<tr align="center">
														<th scope="row" style="width: 4%;"><?=$key->id_pregunta?></th>
														<td style="width: 60%;"><?=$key->pregunta?></td>
														<td style="width: 12%;">
															<input class="" type="radio" name="<?=$key->id_pregunta?>" id="inlineRadio1" value="1">
														</td>
														<td style="width: 12%;">
															<input class="" type="radio" name="<?=$key->id_pregunta?>" id="inlineRadio1" value="2">
														</td>
														<td style="width: 12%;">
															<input class="" type="radio" name="<?=$key->id_pregunta?>" id="inlineRadio1" value="3">
														</td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>

								<div class="row container">
									<div class="col-md-12">
										<label>OBSERVACIONES Y SEGUIMIENTO</label>
										<textarea class="form-control" id="observaciones" name="observaciones"></textarea>
									</div>
								</div>
							</div>
							<br>
							<div class="card-footer text-muted">
							    <div class="row"> 
									<div class="col-md-12" align="center">
										<button class="btn btn-success btn-lg" onclick="guardar_resp();">Guardar Respuestas</button>
									</div>
								</div>
							</div>
							<!-- /.card-body -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>
<?php }else{?>
	<div class="content-wrapper">
		<br>
		<!-- Main content -->
		<section class="content">
			<div class="alert alert-light col-lg-12 text-center" role="alert"><h4>No tiene permisos para acceder a este modulo!</h4></div>
		</section>
		<!-- /.content -->
	</div>

<?php 
}
$this->load->view('administracion/footer_ev') ?>

<script type="text/javascript">
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 5000
		});
		function guardar_resp() {
			var area = document.getElementById("combo_area").value;
			var sede = document.getElementById("combo_sede").value;
			var cargo = document.getElementById("cargo").value;
			var empleado = document.getElementById("combo_empleado").value;
			var observaciones = document.getElementById("observaciones").value;
			if (area == "" || sede == "" || cargo == "" || empleado == "" || observaciones == "") {
				Toast.fire({
					type: 'warning',
					title: ' Hay campos vacios'
				}); 
			}else{
				let cant_select = $('input[type="radio"]:checked').length;
				var cant_preguntas = <?=$cant_preguntas?>;
				var empleado = document.getElementById("combo_empleado").value;
				if (cant_preguntas == cant_select) {
					for (var i = 1; i <= cant_preguntas; i++) {
						let resp = $('input[name="'+i+'"]:checked').val();
						insert_resp(i,resp,empleado);
					}
					insert_encabeza_resp();
				}else{
					Toast.fire({
						type: 'warning',
						title: ' Debes responder todas las preguntas'
					}); 
				}
				
			}

		}

	function insert_resp(pregunta,respuesta,empleado) {
		var area = document.getElementById("combo_area").value;
		var sede = document.getElementById("combo_sede").value;
		var cargo = document.getElementById("cargo").value;
		var empleado = document.getElementById("combo_empleado").value;
		if (area != "" || sede != "" || cargo != "" || empleado != "") {
			
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					resp = xmlhttp.responseText;
					console.log(resp);
				}
			}
			xmlhttp.open("GET", "<?= base_url() ?>Administracion/insert_respuestas?empleado="+empleado+"&pregunta="+pregunta+"&respuesta="+respuesta, true);
			xmlhttp.send();
		}
		
	}

	function insert_encabeza_resp() {
		var area = document.getElementById("combo_area").value;
		var sede = document.getElementById("combo_sede").value;
		var cargo = document.getElementById("cargo").value;
		var empleado = document.getElementById("combo_empleado").value;
		var observaciones = document.getElementById("observaciones").value;
		if (area != "" || sede != "" || cargo != "" || empleado != "") {
			if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					resp = xmlhttp.responseText;
					if (resp == "ok") {
						Toast.fire({
								type: 'success',
								title: ' Evaluacion guardada exitosamente'
							}); 
						location.reload();
					}else{
						Toast.fire({
							type: 'error',
							title: ' Error al guardar la evaluacion'
						}); 
					}
				}
			}
			xmlhttp.open("GET", "<?= base_url() ?>Administracion/insert_encabeza_evaluacion?empleado="+empleado+"&cargo="+cargo+"&sede="+sede+"&area="+area+"&observaciones="+observaciones, true);
			xmlhttp.send();
		}else{
			Toast.fire({
				type: 'warning',
				title: ' Hay campos vacios...'
			}); 
		}
	}
</script>
