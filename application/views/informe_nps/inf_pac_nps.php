<?php $this->load->view('Informe_nps/header.php'); ?>

<div class="content-wrapper">
	<section class="content">
		<div class="card" style="margin-top: 10px;">
			<div class="card-body">
				<div class="row">
					<div class="col-md-9" id="content_inf">

					</div>
					<div class="col-md-3">
						<div class="table-responsive">
							<table class="table table-bordered" style="font-size: 12px;">
								<thead align="center">
									<tr>
										<th style="background-color: red; color: #fff;" width="30" nowrap>D</th>
										<th style="background-color: yellow; color: black;" width="30" nowrap>N</th>
										<th style="background-color: green; color: black;" width="30" nowrap>P</th>
										<th style="background-color: cyan; color: black;" width="30" nowrap>PA</th>
										<th width="30" nowrap>NPS</th>
										<th width="30" nowrap>META NPS</th>
									</tr>
								</thead>
								<tbody align="center" id="tabla_nps">
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<!-- Modal ver encuestas tec-->
<div class="modal fade" id="modal_enc_tec" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Detalle Encuestas</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="info_tec">
				
			</div>
		</div>
	</div>
</div>

<!-- Modal ver encuestas sedes-->
<div class="modal fade" id="modal_enc_sedes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Detalle Encuestas</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="info_sede">
				
			</div>
		</div>
	</div>
</div>

<!-- Modal ver encuestas gral-->
<div class="modal fade" id="modal_enc_gral" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Detalle Encuestas</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="info_gral">
				
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('Informe_nps/footer.php'); ?>

<script type="text/javascript">
	$(document).ready(function() {
		load_info_panel();
		load_info_tabla();
	});
	function load_info_panel() {
		document.getElementById("cargando").style.display = "block";
		var result = document.getElementById("content_inf");
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				result.innerHTML = xmlhttp.responseText;
				document.getElementById("cargando").style.display = "none";
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>Informes/load_panel_indicadores", true);
		xmlhttp.send();
	}

	function load_info_tabla() {
		var result = document.getElementById("tabla_nps");
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				result.innerHTML = xmlhttp.responseText;
				
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>Informes/load_tabla_indicadores", true);
		xmlhttp.send();
	}

	function load_info_tec(nit,mes,sede) {
		var result = document.getElementById("info_tec");
		$('#modal_enc_tec').modal('show');
		result.innerHTML = "";
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				result.innerHTML = xmlhttp.responseText;
				
				
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>Informes/load_info_tec_nps?nit="+nit+"&mes="+mes+"&sede="+sede, true);
		xmlhttp.send();
	}

	function load_info_sede(sede,mes) {
		var result = document.getElementById("info_sede");
		result.innerHTML = "";
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				result.innerHTML = xmlhttp.responseText;
				$('#modal_enc_sedes').modal('show');
				
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>Informes/load_info_sedes_nps?sede="+sede+"&mes="+mes, true);
		xmlhttp.send();
	}

	function load_info_all_sede(mes) {
		var result = document.getElementById("info_gral");
		result.innerHTML = "";
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				result.innerHTML = xmlhttp.responseText;
				$('#modal_enc_gral').modal('show');
				
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>Informes/load_info_all_nps_gm?mes="+mes, true);
		xmlhttp.send();
	}
</script>