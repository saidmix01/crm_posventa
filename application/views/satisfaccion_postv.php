<!DOCTYPE html>
<html>

<head>
	<title>Encuesta de satisfaccion - CODIESEL</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<!-- Estilos Font Awesome -->
	<link rel="stylesheet" href="<?= base_url() ?>plugins/fontawesome-free/css/all.min.css">
	<style type="text/css">
		@media only screen and (min-width: 1366px) {
			#cuerpo {
				background: url(<?= base_url() ?>/media/img/fondo_encuesta.png);
				background-repeat: no-repeat;
				background-size: cover;
				margin-top: 150px;
			}

			#contenido {
				background: rgba(255, 255, 255, 0.5) !important;
			}
		}

		@media only screen and (min-width: 414px) {
			body {
				background-color: #E7E7E7;
				--background-repeat: no-repeat;
				--background-size: cover;
			}

			#contenido {
				margin-top: 30px;
				background: rgba(255, 255, 255, 0.5) !important;
			}
		}

		@media only screen and (min-width: 360px) {
			body {
				background-color: #E7E7E7;
				--background-repeat: no-repeat;
				--background-size: cover;
			}

			#contenido {
				margin-top: 10px;
				background: rgba(255, 255, 255, 0.5) !important;
			}
		}

		@media only screen and (min-width: 320px) {
			body {
				background-color: #E7E7E7;
				--background-repeat: no-repeat;
				--background-size: cover;
			}

			#contenido {
				margin-top: 10px;
				background: rgba(255, 255, 255, 0.5) !important;
			}
		}
	</style>
</head>

<body id="cuerpo">

	<?php if ($data != 404) { ?>
		<div class="container">
			<div class="card" id="contenido">
				<div class="card-body">
					<h2 align="center">Encuesta de satisfacción CODIESEL SA</h2>
					<hr>
					<?php

					$sede = $_GET['bod'];
					$nueva = $sede == 1 ? 'CODIESEL PRINCIPAL' : ($sede == 6 ? 'Chevy exprés Barranca' : ($sede == 7 ? 'Chevy exprés Rosita' : ($sede == 8  ? 'CODIESEL VILLA DEL ROSARIO' : ($sede == 11 ? 'Taller Diesel Girón' : ($sede == 14 ? 'Taller Lámina y Pintura - Colisión Boconó' : ($sede == 16 ? 'Taller Diesel Boconó' : ($sede == 21 ? 'Taller Lámina y Pintura  Diesel Girón' : 'no existe')))))));
					?>
					<p style="font-style: oblique;font-size: 12px;">A continuación elija el estado que represente su nivel de satisfacción con el servicio del taller de <?= $nueva ?> hoy <?= $fecha->fecha ?></p>
					<div class="container">
						<form method="POST" action="<?= base_url() ?>encuesta/resp_satisf_qr">
							<div class="col">
								<label>Ingrese La Placa De Su Vehiculo</label>
								<input type="text" name="n_orden" id="n_orden" class="form-control" required placeholder="Ingresa Tu Placa" onkeyup="val_placa();mayus(this);">
								<small id="msg_ot" class="form-text text-muted"></small>
							</div>
							<div><input type="hidden" name="bod" id="bod" value="<?= $bod ?>"></div>
							<?php foreach ($preguntas->result() as $key) { ?>
								<?php if ($key->id != 1) { ?>
								<div class="form-row">
									<div class="col">
										<label for="exampleInputPassword1"><?= $key->pregunta ?></label>
									</div>
								</div>
								<div class="form-row">
									<div class="col" align="center">
										<?php if ($key->tipo == "1-10" && $key->id != 1) { ?>
											<div class="btn-group btn-group-toggle" data-toggle="buttons">
												<label class="btn btn-outline-danger btn-lg">
													<input type="radio" name="<?= $key->id ?>" id="option<?= $key->id ?>" autocomplete="off" value="6" style="font-size: 20px;"> <span style="font-size: 10px;">0-6</span> <i class="far fa-frown"></i>
												</label>
												<label class="btn btn-outline-warning btn-lg">
													<input type="radio" name="<?= $key->id ?>" id="option<?= $key->id ?>" autocomplete="off" value="8"> <span style="font-size: 10px;">7-8</span> <i class="far fa-meh"></i>
												</label>
												<label class="btn btn-outline-success btn-lg active">
													<input type="radio" name="<?= $key->id ?>" id="option<?= $key->id ?>" autocomplete="off" value="10"> <span style="font-size: 10px;">9-10</span> <i class="far fa-smile"></i>
												</label>
											</div>

										<?php } elseif ($key->tipo == "sn") { ?>
											<div class="btn-group btn-group-toggle" data-toggle="buttons">
												<label class="btn btn-outline-secondary btn-lg">
													<input type="radio" name="<?= $key->id ?>" id="option<?= $key->id ?>" autocomplete="off" value="NO"> <i class="far fa-thumbs-down"></i>
												</label>
												<label class="btn btn-outline-primary btn-lg">
													<input type="radio" name="<?= $key->id ?>" id="option<?= $key->id ?>" autocomplete="off" value="SI"> <i class="far fa-thumbs-up"></i>
												</label>

											</div>
										<?php } elseif ($key->tipo == "op") { ?>
											<td>
												<textarea id="<?= $key->id ?>" name="<?= $key->id ?>" class="form-control form-control-sm"></textarea>
											</td>
										<?php
										}
										?>
									</div>
								</div>
								<?php } ?>
							<?php } ?>
							<hr>
							<div class="form-row">
								<div class="col">
									<div align="center"><button type="submit" id="btn_env" class="btn btn-warning btn-lg">Enviar Respuestas</button></div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<?php } elseif ($this->input->get('data') == "0") { ?>
		<div class="container">
			<div class="card" id="contenido">
				<div class="card-body">
					<h1 align="center">GRACIAS POR TU CALIFICACIÓN</h1>
				</div>
			</div>
		</div>
	<?php } elseif ($data == "404" || $data == "403") {
	?>
		<div class="container">
			<div class="card" id="contenido">
				<div class="card-body">
					<h1 align="center">ENCUESTA NO EXISTE O HA CADUCADO</h1>
				</div>
			</div>
		</div>
	<?php
	}
	?>
</body>


<!-- jQuery -->
<script src="<?= base_url() ?>plugins/jquery/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

<script type="text/javascript">
	function mayus(e) {
		e.value = e.value.toUpperCase();
	}

	function val_placa() {
		var placa = document.getElementById("n_orden").value;
		var msg_ot = document.getElementById("msg_ot");
		var btn_tot = document.getElementById("btn_env");
		btn_tot.disabled = true;
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				if (xmlhttp.responseText == "Error, La Placa No Existe") {
					msg_ot.innerHTML = '<strong style="color: red">' + xmlhttp.responseText + '</strong>';
				} else if (xmlhttp.responseText == "Bien, La Placa Existe") {
					msg_ot.innerHTML = '<strong style="color: green">' + xmlhttp.responseText + '</strong>';
					btn_tot.disabled = false;
				}
				msg_ot.style.color = "orange";
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>taller/val_placa?placa=" + placa, true);
		xmlhttp.send();
	}
</script>

</html>
