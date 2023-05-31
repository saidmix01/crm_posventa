<?php $this->load->view('Informe_nps/header.php'); ?>
<style>
	.table thead th {
		vertical-align: middle;
	}
</style>
<div class="content-wrapper">
	<section class="content">
		<div class="loader2" id="cargando"></div>
		<div class="card">
			<div class="card-header">
				<h3 class="col-lg-12 text-center">Informe Ordenes Finalizadas vs Encuestas realizadas</h3>
			</div>
			<div class="card-body">
				<div class="row mb-2">
					<div class="col-12 col-md-6 col-sm-3">
						<label>Año y mes</label>
						<input class="form-control" type="month" id="mesSel" name="mesSel" value="2022-08">
					</div>
					<div class="col-12 col-md-6 col-sm-3" style="align-self: end;">
						<button class="btn btn-primary" onclick="filtarFecha(<?= Date('Y-m');?>)">Buscar</button>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-12 col-md-6 col-sm-3">
						<label>Total ordenes finalizadas:</label>
						<input type="number" class="form-control" id="cantOrdF" name="cantOrdF" value="" readonly="true">
					</div>
					<div class="col-12 col-md-6 col-sm-3">
						<label>Total encuestas realizadas:</label>
						<input type="number" class="form-control" id="cantEncF" name="cantEncF" value="" readonly="true">
					</div>
				</div>
				<div class="row">
					<div class="table-responsive" id="dataFiltro">

					</div>
				</div>

			</div>
		</div>
		<div class="card">

			<div class="card-body">

				<div class="row" id="encuestasBodega">

				</div>
			</div>
		</div>
	</section>
</div>
<!-- Logout Modal-->
<div class="modal fade" id="tablaEncuestas" tabindex="-1" role="dialog" aria-labelledby="tablaEncuestas" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="table-responsive p-0" id="detalleEncuesta">
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('Informe_nps/footer.php'); ?>

<script>
	$(document).ready(function() {
		filtarFecha();
	});

	function verEncuestasBodega(bodega, ord_fin) {
		var fecha = document.getElementById('mesSel').value;
		var data = new FormData;
		data.append('bodega', bodega);
		data.append('ord_fin', ord_fin);
		data.append('fecha', fecha);

		var url = '<?= base_url() ?>Informes/verEncuestasBodega';
		var request;
		if (window.XMLHttpRequest) {
			request = new XMLHttpRequest();
		} else {
			request = new ActiveXObject("Microsoft.XMLHTTP");
		}

		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				document.getElementById('encuestasBodega').innerHTML = request.responseText;
			}

		}
		request.open("POST", url);
		request.send(data);


	}

	function verEncuestas(nombre) {
		document.getElementById("cargando").style.display = "block";
		var fecha = document.getElementById('mesSel').value;
		var data = new FormData;
		data.append('nombre', nombre);
		data.append('fecha', fecha);

		var url = '<?= base_url() ?>Informes/detalle_encuesta_tecnico';
		var request;
		if (window.XMLHttpRequest) {
			request = new XMLHttpRequest();
		} else {
			request = new ActiveXObject("Microsoft.XMLHTTP");
		}

		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				document.getElementById('detalleEncuesta').innerHTML = request.responseText;
				$('#tablaEncuestas').modal('show');
				document.getElementById("cargando").style.display = "none";
			}

		}
		request.open("POST", url);
		request.send(data);

	}

	function filtarFecha() {

		var fecha = document.getElementById('mesSel').value;
		if (fecha != "") {
			document.getElementById("cargando").style.display = "block";
			var data = new FormData;
			data.append('fecha', fecha);

			console.log(fecha);

		var url = '<?= base_url() ?>Informes/PacNpsInternoDetalladoCargar';
		var request;
		if (window.XMLHttpRequest) {
			request = new XMLHttpRequest();
		} else {
			request = new ActiveXObject("Microsoft.XMLHTTP");
		}
		request.responseType = 'json';
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				var datos = request.response;
				document.getElementById('encuestasBodega').innerHTML = "";
				document.getElementById('cantOrdF').value = datos['cantOrdenes'];
				document.getElementById('cantEncF').value = datos['cantEncuestas'];
				document.getElementById('dataFiltro').innerHTML = datos['htmlTabla'];
				document.getElementById("cargando").style.display = "none";
				
				
			}

		}
		request.open("POST", url,true);
		request.send(data);

		} else {
			alert('Seleccione una fecha para realizar la busqueda');
			document.getElementById("cargando").style.display = "none";
		}

	}
</script>
