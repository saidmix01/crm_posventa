<?php $this->load->view('mantenimiento/header') ?>


<style>
	.loader-2 {
		position: absolute;
		left: -140px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 9999;
		background: url('<?= base_url() ?>media/cargando6.gif') 100% 100% no-repeat;
		opacity: .9;
		display: none;
	}

	.loader {
		position: fixed;
		/* left: 100px;
		top: 0px; */
		width: 100%;
		height: 100%;
		z-index: 9999;
		background: url('<?= base_url() ?>media/cargando7.gif') 50% 50% no-repeat rgb(249, 249, 249);
		opacity: .9;
		display: none;
	}

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

	#tablatotal {
		overflow: scroll;
		width: 100%;
	}

	#tabla_uno {
		width: 100%;
	}

	thead tr th {
		position: sticky;
		top: 0;
		z-index: 10;
		background-color: black;
		color: white;
	}

	.pInput {
		width: 5em;
	}

	.table thead th {
		vertical-align: middle;
	}
</style>
<!-- Main content -->

<div class="content-wrapper">

	<section class="content">
		<div class="loader" id="cargando"></div>
		<div class="card">
			
				<div class="card-body">
					<div><label class="col-lg-12 text-center lead">Informe de retención 72 - 0</label></div>
					<hr>
					<div class="form-row justify-content-center">
						<ul class="nav nav-tabs">
							<li class="nav-item">
								<a class="nav-link btn btn-outline-info" href="#" onclick="showGeneral();cargarInformacionObjetivos('tabla1');">General</a>
							</li>
							<li class="nav-item">
								<a class="nav-link dropdown-toggle btn btn-outline-info" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Autos</a>
								<div class="dropdown-menu">

									<a class="dropdown-item" href="#" onclick="filtroInformeAu('Autos');imagenCargando();">General de Autos</a>
									<div class="dropdown-divider"></div>
									<?php foreach ($dataAutos->result() as $key) { ?>
										<a class="dropdown-item" href="#" onclick="filtroInformeAu('<?= $key->segmento ?>');imagenCargando();"><?= $key->segmento ?></a>
									<?php } ?>


								</div>
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle btn btn-outline-info" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">B&C</a>
								<div class="dropdown-menu">
									<a class="dropdown-item" href="#" onclick="filtroInformeBC('B&C');imagenCargando();">General de B&C</a>
									<div class="dropdown-divider"></div>
									<?php foreach ($dataByC->result() as $key) { ?>
										<a class="dropdown-item" href="#" onclick="filtroInformeBC('<?= $key->segmento ?>');imagenCargando();"><?= $key->segmento ?></a>
									<?php } ?>
								</div>
							</li>
							<li class="nav-item">
								<a class="nav-link btn btn-outline-info" href="#" data-toggle="modal" data-target="#FiltroModal">
									Filtrar por Familia
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link btn btn-outline-info" href="#" data-toggle="modal" data-target="#ObjetivosModal">
									Objetivos
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link btn btn-outline-info" href="<?= base_url() ?>Informes/tablaInfVentas72" target="_blank">
									Tabla general
								</a>

							</li>
							<li class="nav-item">
								<a class="nav-link btn btn-outline-info" href="<?= base_url() ?>Informes/InformeVentasVs" target="_blank">
									Comparación o Vs
								</a>

							</li>
							<li class="nav-item">
								<button type="button" class="nav-link btn btn-outline-info" onclick="vhUltimos12();" >
									Vehículo Ultimos 12 meses
								</button>
							</li>
							<li class="nav-item">
								<button type="button" class="nav-link btn btn-outline-info" onclick="vhYearActual();">
									Vehículos año actual
								</button>
							</li>

						</ul>
					</div>


					<!-- Modal -->
					<div class="modal fade" id="FiltroModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="loader-2" id="cargandoModalFiltro"></div>
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Filtrar por familia</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="form-row">
										<div class="col-lg-12 col-sm-6 col-xs-6">
											<label for="">Seleccione el tipo</label>
											<select class="form-control" name="tipo" id="tipo" onchange="cambia_segmento();">
												<option value="0" default>Auto</option>
												<option value="1">B&C</option>
											</select>
										</div>
									</div>
									<div class="form-row">
										<div class="col-lg-12 col-sm-6 col-xs-6">
											<label for="">Seleccione el segmento</label>
											<select class="form-control" id="segmento" name="segmento" onchange="cambia_familia();">

											</select>
										</div>
									</div>

									<div class="form-row">

										<div id="rtaFamilia" class="col-lg-12 col-sm-6 col-xs-6">

										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
									<button type="button" class="btn btn-info" onclick="filtrarFamilia()">Buscar</button>
								</div>
							</div>
						</div>
					</div>
					<!-- Modal para los objetivos -->
					<div class="modal fade bd-example-modal-lg" id="ObjetivosModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Verificar Objetivos</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">


									<div class="table-responsive scrol">
										<table class="table table-hover table-bordered" align="center" id="tabla_data" style="font-size: 14px;">
											<thead align="center" class="thead-light">
												<tr>
													<th class="titulo colun1 fijar" scope="col">Parque</th>
													<th class="titulo colun1 fijar" scope="col">Entradas</th>
													<th class="titulo colun1 fijar" scope="col">Ventas</th>
													<th class="titulo colun1 fijar" scope="col">% Retencion total HOY</th>
													<th class="titulo colun1 fijar" scope="col">% Objetivo</th>
													<th class="titulo colun1 fijar" scope="col">% Faltante</th>
													<th class="titulo colun1 fijar" scope="col">Entradas pendientes</th>
												</tr>

											</thead>
											<tbody>
												<tr>
													<th class="text-center">12-0</th>
													<td class="text-center parque12"></td>
													<td class="text-center parque12"></td>
													<td class="text-center parque12"></td>
													<td class="text-center"><input id="porcentajeMeta12" class="pInput limpiar" type="number" min="1" max="100" onchange="newObjetivos(12);"></td>
													<td class="text-center limpiar" id="newPorcentaje12"></td>
													<td class="text-center limpiar" id="newEntradas12"></td>
												</tr>
												<tr>
													<th class="text-center">24-12</th>
													<td class="text-center parque24"></td>
													<td class="text-center parque24"></td>
													<td class="text-center parque24"></td>
													<td class="text-center"><input id="porcentajeMeta24" class="pInput limpiar" type="number" min="1" max="100" onchange="newObjetivos(24);"></td>
													<td class="text-center limpiar" id="newPorcentaje24"></td>
													<td class="text-center limpiar" id="newEntradas24"></td>

												</tr>
												<tr>
													<th class="text-center">36-24</th>
													<td class="text-center parque36"></td>
													<td class="text-center parque36"></td>
													<td class="text-center parque36"></td>
													<td class="text-center"><input id="porcentajeMeta36" class="pInput limpiar" type="number" min="1" max="100" onchange="newObjetivos(36);"></td>
													<td class="text-center limpiar" id="newPorcentaje36"></td>
													<td class="text-center limpiar" id="newEntradas36"></td>
												</tr>
												<tr>
													<th class="text-center">48-36</th>
													<td class="text-center parque48"></td>
													<td class="text-center parque48"></td>
													<td class="text-center parque48"></td>
													<td class="text-center"><input id="porcentajeMeta48" class="pInput limpiar" type="number" min="1" max="100" onchange="newObjetivos(48);"></td>
													<td class="text-center limpiar" id="newPorcentaje48"></td>
													<td class="text-center limpiar" id="newEntradas48"></td>
												</tr>
												<tr>
													<th class="text-center">60-48</th>
													<td class="text-center parque60"></td>
													<td class="text-center parque60"></td>
													<td class="text-center parque60"></td>
													<td class="text-center"><input id="porcentajeMeta60" class="pInput limpiar" type="number" min="1" max="100" onchange="newObjetivos(60);"></td>
													<td class="text-center limpiar" id="newPorcentaje60"></td>
													<td class="text-center limpiar" id="newEntradas60"></td>

												</tr>
												<tr>
													<th class="text-center">72-60</th>
													<td class="text-center parque72"></td>
													<td class="text-center parque72"></td>
													<td class="text-center parque72"></td>
													<td class="text-center"><input id="porcentajeMeta72" class="pInput limpiar" type="number" min="1" max="100" onchange="newObjetivos(72);"></td>
													<td class="text-center limpiar" id="newPorcentaje72"></td>
													<td class="text-center limpiar" id="newEntradas72"></td>

												</tr>
											</tbody>
										</table>
									</div>

								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
								</div>
							</div>
						</div>
					</div>
					<hr>
					<div class="table-responsive" id="tablatotal"></div>
					<div class="table-responsive" id="tablaFiltros"></div>
					<div class="table-responsive" id="tablaVehiculos"></div>
					<!-- 	<div class="table-responsive" id="tablaFiltrosFamilia"></div> -->
				</div>


			
		</div>
	</section>
</div>
<!-- /.content -->
<!-- /.content-wrapper -->
<footer class="main-footer">
	<strong>Copyright &copy; 2020 <a href="http://adminlte.io">CODIESEL</a>.</strong>
	Todos los derechos reservados.
	<div class="float-right d-none d-sm-inline-block">
		<b>Version</b> 1.0.0-pre
	</div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
	<!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>


<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">'¿Has terminado ya?'</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">Estas seguro que deseas cerrar sesion</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-dismiss="modal">No</button>
				<a class="btn btn-info" href="<?= base_url() ?>login/logout">Si</a>
			</div>
		</div>
	</div>
</div>

<!-- PASS Modal-->
<div class="modal" tabindex="-1" id="pass-modal" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Es necesario que cambies tu contraseña</h5>
				</div>
				<div class="modal-body">
					<!-- <form method="POST" action="<?= base_url() ?>usuarios_admin/changepass"> -->
					<form>
						<div class="row">
							<div class="col">
								<label for="pass1">Ingrese la nueva contraseña</label>
								<input type="password" id="pass1_one" name="pass2_one" class="form-control" placeholder="Ingrese nueva contraseña">
							</div>
							<div class="col">
								<label for="pass2">Confirme la contraseña</label>
								<input type="password" id="pass2_one" name="pass1_one" class="form-control" placeholder="Confirma la contraseña">
								<?php
								foreach ($userdata->result() as $key) {
								?>
									<input type="hidden" id="id_usu_one" name="id_usu" value="<?= $key->id_usuario ?>">
								<?php
								}
								?>
							</div>
						</div>

				</div>
				<div class="modal-footer">
					<a href="<?= base_url() ?>login/logout" class="btn btn-secondary">Cerrar</a>
					<button type="button" class="btn btn-primary" onclick="cambiarPass_One();">Cambiar</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- PASS Modal-->
	<div class="modal" tabindex="-1" id="pass-modal2" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Cambio de Contraseña</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<!-- <form method="POST" action="<?= base_url() ?>usuarios_admin/changepass"> -->
					<form>
						<div class="row">
							<div class="col">
								<label for="pass1">Ingrese la nueva contraseña</label>
								<input type="password" id="pass1_two" name="pass2" class="form-control" placeholder="Ingrese nueva contraseña">
							</div>
							<div class="col">
								<label for="pass2">Confirme la contraseña</label>
								<input type="password" id="pass2_two" name="pass1" class="form-control" placeholder="Confirma la contraseña">
								<?php
								foreach ($userdata->result() as $key) {
								?>
									<input type="hidden" id="id_usu_two" name="id_usu" value="<?= $key->id_usuario ?>">
								<?php
								}
								?>
							</div>
						</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="cambiarPass_Two();">Cambiar</button>
					</form>
				</div>
			</div>
		</div>
	</div>

<!-- jQuery -->
<script src="<?= base_url() ?>plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= base_url() ?>plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
	$.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="<?= base_url() ?>plugins/chart.js/Chart.min.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?= base_url() ?>plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?= base_url() ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url() ?>plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= base_url() ?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?= base_url() ?>plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url() ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>dist/js/adminlte.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!--select2-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- SweetAlert2 -->
<!-- <script src="<?= base_url() ?>plugins/sweetalert2/sweetalert2.min.js"></script> -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Toastr -->
<script src="<?= base_url() ?>plugins/toastr/toastr.min.js"></script>
<!--table2excel-->
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<!--usar botones en datatable-->
<!-- <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script> -->

<script>
	document.addEventListener("DOMContentLoaded", () => {
		/* document.getElementById("cargando").style.display = "block"; */
		var images = [];

		function preload() {
			for (var i = 0; i < arguments.length; i++) {
				images[i] = new Image();
				images[i].src = preload.arguments[i];
			}
		}
		preload('<?= base_url() ?>media/cargando6.gif');

	});

	function showGeneral() {
		document.getElementById("tablaFiltros").style.display = "none";
		document.getElementById("tablatotal").style.display = "block";
	}


	window.onload = function() {
		InformeVentas72PostCarga();
		cambia_segmento();
	}

	function imagenCargando() {
		document.getElementById("cargando").style.display = "block";
	}


	function InformeVentas72PostCarga() {
		document.getElementById("cargando").style.display = "block";
		var request = new XMLHttpRequest();
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				document.getElementById('tablatotal').innerHTML = request.responseText;
				getInformeGraf();
				cargarInformacionObjetivos('tabla1');


			}
		}
		request.open("GET", "<?= base_url() ?>Informes/InformeVentas72PostCarga", true);
		request.send();
	}

	function getInformeGraf() {

		var xmlhttp1;
		if (window.XMLHttpRequest) {
			xmlhttp1 = new XMLHttpRequest();
		} else {
			xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp1.responseType = 'json';
		xmlhttp1.onreadystatechange = function() {
			if (xmlhttp1.readyState === 4 && xmlhttp1.status === 200) {
				var flag1 = xmlhttp1.response;
				if (flag1 == "") {
					Toast.fire({
						type: 'error',
						title: ' No hay datos'
					});
				} else {
					CargaGeneralGraf(flag1["dataFlotaGraf"], flag1["dataRetailGraf"], flag1["dataPorcentajeTotal"]);
					document.getElementById("cargando").style.display = "none";
				}
			}
		}
		xmlhttp1.open("GET", "<?= base_url() ?>Informes/GrafInformeVentas72PostCarga", true);
		xmlhttp1.send();
	}

	function CargaGeneralGraf(flota, retal, total) {
		var chartFlota = new CanvasJS.Chart("chartContainerFlota", {
			animationEnabled: true,
			exportEnabled: true,
			theme: "light1", // "light1", "light2", "dark1", "dark2"
			title: {
				text: ""
			},
			axisY: {
				includeZero: true,
				title: "Porcentaje"
			},
			legend: {
				cursor: "pointer",
				itemclick: toggleDataSeries
			},
			data: [{
				type: "column",
				showInLegend: true,
				yValueFormatString: "#'%'",
				legendText: "Flota",
				dataPoints: flota
			}]
		});

		var chartRetail = new CanvasJS.Chart("chartContainerRetail", {
			animationEnabled: true,
			exportEnabled: true,
			theme: "light1", // "light1", "light2", "dark1", "dark2"
			title: {
				text: ""
			},
			axisY: {
				includeZero: true,
				title: "Porcentaje"
			},
			legend: {
				cursor: "pointer",
				itemclick: toggleDataSeries
			},
			data: [{
				type: "column",
				showInLegend: true,
				yValueFormatString: "#'%'",
				legendText: "Retail",
				dataPoints: retal
			}]
		});
		var chartTotal = new CanvasJS.Chart("chartContainerTotal", {
			animationEnabled: true,
			exportEnabled: true,
			theme: "light1", // "light1", "light2", "dark1", "dark2"
			title: {
				text: ""
			},
			axisY: {
				includeZero: true,
				title: "Porcentaje"
			},
			legend: {
				cursor: "pointer",
				itemclick: toggleDataSeries
			},

			data: [{
				type: "column",
				showInLegend: true,
				legendText: "Total",
				yValueFormatString: "#'%'",
				dataPoints: total
			}]
		});
		chartFlota.render();
		chartRetail.render();
		chartTotal.render();
	}

	function toggleDataSeries(e) {
		if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
			e.dataSeries.visible = false;
		} else {
			e.dataSeries.visible = true;
		}
		e.chart.render();
	}

	function filtroInformeAu(auto) {
		if (auto == "") {
			Swal.fire({
				title: 'Advertencia!',
				text: 'Por favor seleccione un filtro',
				icon: 'warning',
				confirmButtonText: 'Ok'
			});
		} else {
			document.getElementById("cargando").style.display = "block";
			var request = new XMLHttpRequest();
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					document.getElementById('tablatotal').style.display = "none";
					document.getElementById('tablaFiltros').innerHTML = request.responseText;

					getFiltroInformeGraf(auto);
					document.getElementById("tablaFiltros").style.display = "block";
					cargarInformacionObjetivos('tabla2');

				}
			}
			request.open("GET", "<?= base_url() ?>Informes/filtroInforme72Auto?filtro=" + auto, true);
			request.send();
		}
	}

	function filtroInformeBC(byc) {
		if (byc == "") {
			Swal.fire({
				title: 'Advertencia!',
				text: 'Por favor seleccione un filtro',
				icon: 'warning',
				confirmButtonText: 'Ok'
			});
		} else {
			var datos = new FormData();
			datos.append('filtro', byc);

			document.getElementById("cargando").style.display = "block";
			var request = new XMLHttpRequest();
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					document.getElementById('tablatotal').style.display = "none";
					document.getElementById('tablaFiltros').innerHTML = request.responseText;
					getFiltroInformeGrafByC(byc);
					cargarInformacionObjetivos('tabla2');
				}
			}
			request.open("POST", "<?= base_url() ?>Informes/filtroInforme72ByC", true);
			request.send(datos);
		}
	}

	function getFiltroInformeGraf(filtro) {
		var xmlhttp1;
		if (window.XMLHttpRequest) {
			xmlhttp1 = new XMLHttpRequest();
		} else {
			xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp1.responseType = 'json';
		xmlhttp1.onreadystatechange = function() {
			if (xmlhttp1.readyState === 4 && xmlhttp1.status === 200) {
				var flag1 = xmlhttp1.response;
				if (flag1 == "") {
					Toast.fire({
						type: 'error',
						title: ' No hay datos'
					});
				} else {
					datosGrafica(flag1["dataFlotaGraf"], flag1["dataRetailGraf"], flag1["dataPorcentajeTotal"], flag1["dataFlotaGrafG"], flag1["dataRetailGrafG"], flag1["dataPorcentajeTotalG"], flag1["nameColumnsSecond"], filtro);
				}
			}
		}
		xmlhttp1.open("GET", "<?= base_url() ?>Informes/getDatosGraficasAuto?filtro=" + filtro, true);
		xmlhttp1.send();
	}

	function getFiltroInformeGrafByC(filtro) {
		var xmlhttp1;
		if (window.XMLHttpRequest) {
			xmlhttp1 = new XMLHttpRequest();
		} else {
			xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var datos = new FormData();
		datos.append('filtro', filtro);

		xmlhttp1.responseType = 'json';
		xmlhttp1.onreadystatechange = function() {
			if (xmlhttp1.readyState === 4 && xmlhttp1.status === 200) {
				var flag1 = xmlhttp1.response;
				if (flag1 == "") {
					Toast.fire({
						type: 'error',
						title: ' No hay datos'
					});
				} else {
					datosGrafica(flag1["dataFlotaGraf"], flag1["dataRetailGraf"], flag1["dataPorcentajeTotal"], flag1["dataFlotaGrafG"], flag1["dataRetailGrafG"], flag1["dataPorcentajeTotalG"], flag1["nameColumnsSecond"], filtro);
				}
			}
		}
		xmlhttp1.open("POST", "<?= base_url() ?>Informes/getDatosGraficasByC", true);
		xmlhttp1.send(datos);
	}

	function datosGrafica(flota, retail, total, flotaG, retailG, totalG, nameColumnsSecond, filtro) {
		var validarDivFlota2 = document.getElementById('chartContainerFlota2');
		if (validarDivFlota2 != null) {
			var chartFlota2 = new CanvasJS.Chart("chartContainerFlota2", {
				animationEnabled: true,
				exportEnabled: true,
				theme: "light1", // "light1", "light2", "dark1", "dark2"
				title: {
					text: ""
				},
				axisY: {
					includeZero: true,
					title: "Porcentaje"
				},
				axisY2: {
					title: "",
					titleFontColor: "#C0504E",
					lineColor: "#C0504E",
					labelFontColor: "#C0504E",
					tickColor: "#C0504E"
				},
				legend: {
					cursor: "pointer",
					itemclick: toggleDataSeries
				},
				data: [{
						type: "column",
						name: "",
						legendText: filtro,
						yValueFormatString: "#'%'",
						showInLegend: true,
						dataPoints: flota
					},
					{
						type: "column",
						name: "",
						legendText: nameColumnsSecond,
						yValueFormatString: "#'%'",
						showInLegend: true,
						dataPoints: flotaG
					}
				]


			});
		}

		var validarDivRetail2 = document.getElementById('chartContainerRetail2');
		if (validarDivRetail2 != null) {
			var chartRetail2 = new CanvasJS.Chart("chartContainerRetail2", {
				animationEnabled: true,
				exportEnabled: true,
				theme: "light1", // "light1", "light2", "dark1", "dark2"
				title: {
					text: ""
				},
				axisY: {
					includeZero: true,
					title: "Porcentaje"
				},
				legend: {
					cursor: "pointer",
					itemclick: toggleDataSeries
				},
				data: [{
						type: "column",
						name: "",
						legendText: filtro,
						yValueFormatString: "#'%'",
						showInLegend: true,
						dataPoints: retail,
					},
					{
						type: "column",
						name: "",
						legendText: nameColumnsSecond,
						yValueFormatString: "#'%'",
						showInLegend: true,
						dataPoints: retailG
					}
				]
			});
		}

		var validarDivTotal2 = document.getElementById('chartContainerTotal2');
		if (validarDivTotal2 != null) {
			var chartTotal2 = new CanvasJS.Chart("chartContainerTotal2", {
				animationEnabled: true,
				exportEnabled: true,
				theme: "light1", // "light1", "light2", "dark1", "dark2"
				title: {
					text: ""
				},
				axisY: {
					includeZero: true,
					title: "Porcentaje"
				},
				legend: {
					cursor: "pointer",
					itemclick: toggleDataSeries
				},
				data: [{
						type: "column",
						name: "",
						legendText: filtro,
						yValueFormatString: "#'%'",
						showInLegend: true,
						dataPoints: total
					},
					{
						type: "column",
						name: "",
						legendText: nameColumnsSecond,
						yValueFormatString: "#'%'",
						showInLegend: true,
						dataPoints: totalG
					}
				]
			});
		}

		document.getElementById("cargando").style.display = "none";

		if (validarDivFlota2 != null) {
			chartFlota2.render();
		}
		if (validarDivRetail2 != null) {
			chartRetail2.render();
		}
		if (validarDivTotal2 != null) {
			chartTotal2.render();
		}




	}
</script>


<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<script>
	<?php $dataAutos2 = array();
	foreach ($dataAutos->result() as $key) {
		array_push($dataAutos2, "$key->segmento");
	}
	$dataAutos3 = array();
	foreach ($dataByC->result() as $key) {
		array_push($dataAutos3, "$key->segmento");
	}
	?>
	var segmentos_1 = <?= json_encode($dataAutos2) ?>;
	var segmentos_2 = <?= json_encode($dataAutos3) ?>;
	var todassegmentos = [
		segmentos_1,
		segmentos_2,
	];
	/* console.log(todassegmentos); */

	function cambia_segmento() {
		//tomo el valor del select del tipo elegido 
		var tipo = document.getElementById('tipo').value;

		//miro a ver si el tipo está definido 
		if (tipo != "") {
			//si estaba definido, entonces coloco las opciones de la segmento correspondiente. 
			//selecciono el array de segmento adecuado 
			mis_segmentos = todassegmentos[tipo];
			//calculo el numero de segmentos 
			num_segmentos = mis_segmentos.length;
			//marco el número de segmentos en el select 
			$("#segmento").empty();
			document.getElementById('segmento').length = num_segmentos;
			/* console.log(num_segmentos); */
			//para cada segmento del array, la introduzco en el select 
			for (i = 0; i < num_segmentos; i++) {
				document.getElementById('segmento').options[i].value = mis_segmentos[i];
				/* $('#mySelect').append($('<option>').val('head').text('Head')); */
				/* document.f1.segmento.options[i].value = mis_segmentos[i] */
				document.getElementById('segmento').options[i].text = mis_segmentos[i];

			}
			cambia_familia();
		} else {
			//si no había segmento seleccionada, elimino las segmentos del select 
			document.getElementById('segmento').length = 1;
			//coloco un guión en la única opción que he dejado 
			document.getElementById('segmento').options[0].value = "-";
			document.getElementById('segmento').options[0].text = "-";
		}

	}

	function cambia_familia() {
		var segmento = document.getElementById("segmento").value;
		/* console.log(segmento); */
		if (segmento == "") {
			alert("Funcion fallida");
		} else {
			document.getElementById("cargandoModalFiltro").style.display = "block";

			var request = new XMLHttpRequest();
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					document.getElementById('rtaFamilia').innerHTML = request.responseText;
					$("#familia").select2({
						placeholder: "Seleccione una familia",
					});
					document.getElementById("cargandoModalFiltro").style.display = "none";
				}
			}
			request.open("GET", "<?= base_url() ?>Informes/getFamilia?segmento=" + segmento, true);
			request.send();
		}

	}

	function filtrarFamilia() {
		var selected = $("#familia").val();


		familia = JSON.stringify(selected);

		/* console.log(selected); */
		if (selected != "") {
			$('#FiltroModal').modal('hide');
			document.getElementById("cargando").style.display = "block";
			var request = new XMLHttpRequest();
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					document.getElementById('tablatotal').style.display = "none";
					/* document.getElementById('tablaFiltros').style.display = "none"; */
					/* document.getElementById('tablaFiltrosFamilia').innerHTML = request.responseText; */
					document.getElementById('tablaFiltros').innerHTML = request.responseText;

					/* document.getElementById('tablaFiltrosFamilia').style.display = "block"; */
					getFiltroInformeGrafFamilia(familia);
					cargarInformacionObjetivos('tabla2');
				}
			}
			request.open("GET", "<?= base_url() ?>Informes/filtroInformeFamilia?selected=" + familia, true);
			request.send();

		} else {
			Toast.fire({
				type: 'error',
				title: 'Debe seleccionar el tipo, el segmento y la familia para filtrar'
			});
		}
	}

	function getFiltroInformeGrafFamilia(familia) {
		var xmlhttp1;
		if (window.XMLHttpRequest) {
			xmlhttp1 = new XMLHttpRequest();
		} else {
			xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp1.responseType = 'json';
		xmlhttp1.onreadystatechange = function() {
			if (xmlhttp1.readyState === 4 && xmlhttp1.status === 200) {
				var flag1 = xmlhttp1.response;
				if (flag1 == "") {
					Toast.fire({
						type: 'error',
						title: ' No hay datos'
					});
				} else {
					datosGrafica(flag1["dataFlotaGraf"], flag1["dataRetailGraf"], flag1["dataPorcentajeTotal"], flag1["dataFlotaGrafG"], flag1["dataRetailGrafG"], flag1["dataPorcentajeTotalG"], flag1["nameColumnsSecond"], familia);
				}
			}
		}
		xmlhttp1.open("GET", "<?= base_url() ?>Informes/getDatosGraficasFamilia?filtro=" + familia, true);
		xmlhttp1.send();
	}

	function cargarInformacionObjetivos(tabla) {
		$('input.limpiar').val(''); /* Limpiando los datos que se encuentran en el input */
		$('td.limpiar').html(''); /* Limpiando los datos que se encuentran en los td de porcentaje faltante y entradas faltantes */

		var parque12 = $('.' + tabla + ' .parque-12').map(function() {
			return this.innerText;
		}).get();
		document.getElementsByClassName('parque12')[0].innerText = parque12[0];
		document.getElementsByClassName('parque12')[1].innerText = parque12[1];
		document.getElementsByClassName('parque12')[2].innerText = parque12[2];
		var parque24 = $('.' + tabla + ' .parque-24').map(function() {
			return this.innerText;
		}).get();
		document.getElementsByClassName('parque24')[0].innerText = parque24[0];
		document.getElementsByClassName('parque24')[1].innerText = parque24[1];
		document.getElementsByClassName('parque24')[2].innerText = parque24[2];
		var parque36 = $('.' + tabla + ' .parque-36').map(function() {
			return this.innerText;
		}).get();
		document.getElementsByClassName('parque36')[0].innerText = parque36[0];
		document.getElementsByClassName('parque36')[1].innerText = parque36[1];
		document.getElementsByClassName('parque36')[2].innerText = parque36[2];
		var parque48 = $('.' + tabla + ' .parque-48').map(function() {
			return this.innerText;
		}).get();
		document.getElementsByClassName('parque48')[0].innerText = parque48[0];
		document.getElementsByClassName('parque48')[1].innerText = parque48[1];
		document.getElementsByClassName('parque48')[2].innerText = parque48[2];
		var parque60 = $('.' + tabla + ' .parque-60').map(function() {
			return this.innerText;
		}).get();
		document.getElementsByClassName('parque60')[0].innerText = parque60[0];
		document.getElementsByClassName('parque60')[1].innerText = parque60[1];
		document.getElementsByClassName('parque60')[2].innerText = parque60[2];
		var parque72 = $('.' + tabla + ' .parque-72').map(function() {
			return this.innerText;
		}).get();
		document.getElementsByClassName('parque72')[0].innerText = parque72[0];
		document.getElementsByClassName('parque72')[1].innerText = parque72[1];
		document.getElementsByClassName('parque72')[2].innerText = parque72[2];
	}

	function newObjetivos(indice) {
		/* Obtenemos el porcentaje agregado  */
		var newP = parseInt(document.getElementById('porcentajeMeta' + indice).value);
		var array = $('.parque' + indice).map(function() {
			return this.innerText;
		}).get();
		var eTotal = array[0];
		var vTotal = array[1];
		var pTotal = array[2];
		if (newP <= 100) {
			if (parseInt(pTotal) >= newP) {
				Swal.fire({
					title: 'Error!',
					text: 'No se pueden agregar un porcentaje menor o igual que ' + pTotal,
					icon: 'warning',
					confirmButtonText: 'Cerrar'
				});
				$('#newEntradas' + indice).html('');
				$('#newPorcentaje' + indice).html('');
				$('input#porcentajeMeta' + indice).val('')
			} else {
				var faltantes = vTotal * (newP / 100);
				var efaltantesC = faltantes - eTotal;


				var pFaltante = newP - parseInt(pTotal);
				var eFaltantes = vTotal * (pFaltante / 100);
				$('#porcentajeMeta' + indice).val(newP);
				document.getElementById('newPorcentaje' + indice).innerText = pFaltante.toFixed(0) + "%";
				document.getElementById('newEntradas' + indice).innerText = efaltantesC.toFixed(0);
			}
		} else {
			Swal.fire({
				title: 'Error!',
				text: 'El porcentaje objetivo no puede sobrepasar el 100%',
				icon: 'warning',
				confirmButtonText: 'Cerrar'
			});
			$('#newEntradas' + indice).html('');
			$('#newPorcentaje' + indice).html('');
			$('input#porcentajeMeta' + indice).val('')
		}

	}
	/* Funcion para traer la informacion de la venta de vehiculo de los ultimos 12 meses */
	function vhUltimos12() {
		document.getElementById("cargando").style.display = "block";
		var xmlhttp1;
		if (window.XMLHttpRequest) {
			xmlhttp1 = new XMLHttpRequest();
		} else {
			xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
		}
		/* xmlhttp1.responseType = 'json'; */
		xmlhttp1.onreadystatechange = function() {
			if (xmlhttp1.readyState === 4 && xmlhttp1.status === 200) {
				var flag1 = xmlhttp1.responseText;
				if (flag1 != 'Error') {
					document.getElementById('tablaVehiculos').innerHTML = xmlhttp1.responseText;
					descargarTablaVehiculos('Vehiculos ultimos 12 meses');
					document.getElementById('tablaVehiculos').innerHTML = "";
					document.getElementById("cargando").style.display = "none";
				} else {
					Swal.fire({
						title: 'Error!',
						text: 'No se encontro información...',
						icon: 'warning',
						confirmButtonText: 'Cerrar'
					});
				}
			}
		}
		xmlhttp1.open("GET", "<?= base_url() ?>Informes/getVehiculos12meses", true);
		xmlhttp1.send();
	}
	/* Funcion para traer la informacion de la venta de vehiculo del año actual o en curso */
	function vhYearActual() {
		document.getElementById("cargando").style.display = "block";
		var xmlhttp1;
		if (window.XMLHttpRequest) {
			xmlhttp1 = new XMLHttpRequest();
		} else {
			xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
		}
		/* xmlhttp1.responseType = 'json'; */
		xmlhttp1.onreadystatechange = function() {
			if (xmlhttp1.readyState === 4 && xmlhttp1.status === 200) {
				var flag1 = xmlhttp1.responseText;
				if (flag1 != 'Error') {
					document.getElementById('tablaVehiculos').innerHTML = xmlhttp1.responseText;
					descargarTablaVehiculos('Vehiculos Año Actual');
					document.getElementById('tablaVehiculos').innerHTML = "";
					document.getElementById("cargando").style.display = "none";
				} else {
					Swal.fire({
						title: 'Error!',
						text: 'No se encontro información...',
						icon: 'warning',
						confirmButtonText: 'Cerrar'
					});
				}


			}
		}
		xmlhttp1.open("GET", "<?= base_url() ?>Informes/getVehiculosYearActual", true);
		xmlhttp1.send();
	}

	/* Sergio Galvis
	27/07/2022 */
	var f = new Date();
	fecha = f.getDate() + "-" + (f.getMonth() + 1) + "-" + f.getFullYear();
	function descargarTablaVehiculos(title) {
		$("#tablaExcel").table2excel({
			exclude: ".noExl",
			name: "Worksheet Name",
			filename: title + "-" + fecha, //do not include extension
			fileext: ".xlsx" // file extension
		});
	}

</script>
<script src="<?= base_url() ?>dist/js/md5.js"></script>
<script>
		function cambiarPass_One() {
			console.log('Cambiando contraseña');
			let pass1 = document.getElementById('pass1_one').value;
			let pass2 = document.getElementById('pass2_one').value;
			let id_usuario = document.getElementById('id_usu_one').value;
			let clave = hex_md5(pass1);
			console.log(pass1 + "=" + pass2);
			if (pass1 === pass2 && pass1 != "" && pass2 != "") {
				let form = new FormData();
				/* 
					$pass1 = $this->input->POST('pass1');
					$pass2 = $this->input->POST('pass2');
					$id_usu = $this->input->POST('id_usu');
					$clave = $this->input->POST('clave'); 
				*/
				form.append('pass1', pass1);
				form.append('pass2', pass2);
				form.append('id_usu', id_usuario);
				form.append('clave', clave);
				var xmlhttp;
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						var resp = xmlhttp.responseText;
						if (resp == 1) {
							Swal.fire({
								title: 'Exito!',
								text: 'Se ha cambiado con exito la contraseña',
								icon: 'success',
								confirmButtonText: 'Ok',
								allowOutsideClick: false
							}).then((result) => {
								/* Read more about isConfirmed, isDenied below */
								if (result.isConfirmed) {
									window.location.reload();
								}
							});
						} else if (resp == 2) {
							Swal.fire({
								title: 'Error!',
								text: 'No se ha actualizado la contraseña.',
								icon: 'error',
								confirmButtonText: 'Ok',
								allowOutsideClick: false
							}).then((result) => {
								/* Read more about isConfirmed, isDenied below */
								if (result.isConfirmed) {
									location.reload();
								}
							});
						}

					}
				}
				xmlhttp.open("POST", "<?= base_url() ?>usuarios_admin/changepass", true);
				xmlhttp.send(form);
			} else {
				Swal.fire({
					title: 'Error!',
					text: 'Las contraseñas no coinciden',
					icon: 'error',
					confirmButtonText: 'Cerrar'
				});
			}
		}

		function cambiarPass_Two() {
			console.log('Cambiando contraseña');
			let pass1 = document.getElementById('pass1_two').value;
			let pass2 = document.getElementById('pass2_two').value;
			let id_usuario = document.getElementById('id_usu_two').value;
			let clave = hex_md5(pass1);
			console.log(pass1 + "=" + pass2);
			if (pass1 === pass2 && pass1 != "" && pass2 != "") {
				let form = new FormData();
				form.append('pass1', pass1);
				form.append('pass2', pass2);
				form.append('id_usu', id_usuario);
				form.append('clave', clave);
				var xmlhttp;
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						var resp = xmlhttp.responseText;
						if (resp == 1) {
							Swal.fire({
								title: 'Exito!',
								text: 'Se ha actualizado la contraseña.',
								icon: 'success',
								confirmButtonText: 'Ok',
								allowOutsideClick: false
							}).then((result) => {
								/* Read more about isConfirmed, isDenied below */
								if (result.isConfirmed) {
									location.reload();
								}
							});
						} else if (resp == 2) {
							Swal.fire({
								title: 'Error!',
								text: 'No se ha actualizado la contraseña.',
								icon: 'error',
								confirmButtonText: 'Ok',
								allowOutsideClick: false
							}).then((result) => {
								/* Read more about isConfirmed, isDenied below */
								if (result.isConfirmed) {
									location.reload();
								}
							});
						}

					}
				}
				xmlhttp.open("POST", "<?= base_url() ?>usuarios_admin/changepass", true);
				xmlhttp.send(form);
			} else {
				Swal.fire({
					title: 'Error!',
					text: 'Las contraseñas no coinciden',
					icon: 'error',
					confirmButtonText: 'Cerrar'
				});
			}



		}
	</script>
