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
				<div><label class="col-lg-12 text-center lead">Informe de retención 72 - 0 </label></div>
				<div><label class="col-lg-12 text-center lead">Comparación o Versus </label></div>
				<hr>
				<div class="form-row justify-content-center">
					<form action="" method="post">
						<ul class="nav nav-tabs">
							<li class="nav-item">
								<a class="nav-link btn btn-outline-info" href="<?= base_url() ?>Informes/InformeVentas72" target="_blank">Inicio</a>
							</li>
							<li class="nav-item">
								<a class="nav-link btn btn-outline-info" href="#" onclick="mostrarGeneral();">General</a>
							</li>
							<li class="nav-item">
								<a class="nav-link dropdown-toggle btn btn-outline-info" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Autos</a>
								<div class="dropdown-menu">

									<a class="dropdown-item" href="#" onclick="getFiltroInformeGrafVs('Autos','Autos');imagenCargando();">General de Autos</a>
									<div class="dropdown-divider"></div>
									<?php foreach ($dataAutos->result() as $key) { ?>
										<a class="dropdown-item" href="#" onclick="getFiltroInformeGrafVs('<?= $key->segmento ?>','Autos');imagenCargando();"><?= $key->segmento ?></a>
									<?php } ?>


								</div>
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle btn btn-outline-info" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">B&C</a>
								<div class="dropdown-menu">
									<a class="dropdown-item" href="#" onclick="getFiltroInformeGrafVs('B&C','B&C');imagenCargando();">General de B&C</a>
									<div class="dropdown-divider"></div>
									<?php foreach ($dataByC->result() as $key) { ?>
										<a class="dropdown-item" href="#" onclick="getFiltroInformeGrafVs('<?= $key->segmento ?>','B&C');imagenCargando();"><?= $key->segmento ?></a>
									<?php } ?>
								</div>
							</li>
							<li class="nav-item">
								<a class="nav-link btn btn-outline-info" target="_blank" href="<?= base_url() ?>Informes/tablaInfVentas72">
									Tabla general
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
					</form>
				</div>
				<hr>
				<div class="table-responsive" id="tablatotal">
					<div class="card card-info" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
						<div class="card-header">
							<h3 class="card-title">General</h3>

							<div class="card-tools">
								<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
								</button>
							</div>
							<!-- /.card-tools -->
						</div>
						<!-- /.card-header -->
						<div class="card-body">
							<div id="chartContainerGeneral" style="height: 420px; width: 100%;"></div>
						</div>
						<!-- /.card-body -->
					</div>
				</div>
				<div class="table-responsive" id="tablatotal_1" style="display: none ;">
					<div class="card card-info" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
						<div class="card-header">
							<h3 class="card-title"></h3>

							<div class="card-tools">
								<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
								</button>
							</div>
							<!-- /.card-tools -->
						</div>
						<!-- /.card-header -->
						<div class="card-body">
							<div id="chartContainerFiltro" style="height: 420px; width: 100%;"></div>
						</div>
						<!-- /.card-body -->
					</div>
				</div>
				<div class="table-responsive" id="tablaVehiculos"></div>
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
				<a class="btn btn-primary" href="<?= base_url() ?>login/logout">Si</a>
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




	window.onload = function() {
		/* Se debera cargar un grafico con los datos de autos y B&C */
		document.getElementById("cargando").style.display = "block";
		getInformeGrafVs();
		/* cambia_segmento(); */
	}

	function mostrarGeneral() {
		document.getElementById("tablatotal_1").style.display = "none";
		document.getElementById("tablatotal").style.display = "block";
	}

	function imagenCargando() {
		document.getElementById("cargando").style.display = "block";
	}

	function getInformeGrafVs() {

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
					CargaGeneralGrafVs(flag1["dataGrafAutos"], flag1["dataGrafByC"]);

				}
			}
		}
		xmlhttp1.open("GET", "<?= base_url() ?>Informes/GrafInformeVentas72Vs", true);
		xmlhttp1.send();
	}

	function CargaGeneralGrafVs(Autos, ByC) {
		var chartGeneral = new CanvasJS.Chart("chartContainerGeneral", {
			animationEnabled: true,
			exportEnabled: true,

			title: {
				text: "General"
			},
			axisX: {
				title: "Parque"
			},
			axisY: {
				includeZero: true,
				title: "Porcentaje",
				suffix: "%",

			},
			legend: {
				cursor: "pointer",
				itemclick: toggleDataSeries
			},
			data: [{
					type: "column",
					click: ayuda,
					showInLegend: true,
					legendText: "Autos",
					yValueFormatString: "#'%'",
					dataPoints: Autos
				},
				{
					type: "column",
					click: ayuda,
					showInLegend: true,
					legendText: "B&C",
					yValueFormatString: "#'%'",
					dataPoints: ByC
				}
			]
		});

		chartGeneral.render();
		document.getElementById("cargando").style.display = "none";

	}

	function ayuda(e) {
		if (e.dataSeries.type === 'column') {
			e.dataSeries.type = 'line';
			e.dataSeries.markerSize = 15;
		} else {
			e.dataSeries.type = 'column';
		}
		e.chart.render();

	}

	function toggleDataSeries(e) {
		if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
			e.dataSeries.visible = false;
		} else {
			e.dataSeries.visible = true;

		}
		e.chart.render();
		
	}


	function getFiltroInformeGrafVs(filtro, tipo) {
		var xmlhttp1;
		if (window.XMLHttpRequest) {
			xmlhttp1 = new XMLHttpRequest();
		} else {
			xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
		}

		var datos = new FormData();
		datos.append('filtro', filtro);
		datos.append('tipo', tipo);



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
					datosGrafica(flag1["arr_total"], filtro, tipo);
				}
			}
		}
		xmlhttp1.open("POST", "<?= base_url() ?>Informes/GrafInformeVentas72VsFiltro");
		xmlhttp1.send(datos);
	}



	function datosGrafica(data, filtro, tipo) {

		CanvasJS.addColorSet("greenShades",
			[ //colorSet Array
				"#005b70",
				"#FA691B",
				"#F0432D",
				"#DB2A2A",
				"#D0FF3B",
				"#42DB4C",
				"#948CF0",
				"#FFC354",
				"#A2DB58",
				"#DBC25A",
				"#F0EB62",
				"#837DFA",
				"#d62550",
				"#70a42c",
				"#ffa600",
				"#0a0e5c",
				"#530365",
				"#870065",
				"#b3005e"

			]);


		var ban0 = 0;
		var ban1 = 1;
		var ban2 = 2;
		var ban3 = 3;
		var ban4 = 4;
		var ban5 = 5;
		var ban6 = 6;
		var ban7 = 7;
		var ban8 = 8;
		var ban9 = 9;
		var ban10 = 10;
		var ban11 = 11;
		var ban12 = 12;
		var ban13 = 13;
		var ban14 = 14;
		var ban15 = 15;
		var ban16 = 16;
		var ban17 = 17;
		var ban18 = 18;
		var data_arr = new Array();
		for (let index = 0; index < (data.length) / 19; index++) {
			data_arr.push({
				type: "column",
				click: ayuda,
				name: "",
				toolTipContent: "<strong>" + data[ban0] + "</strong><br><b>{label}</b>: {y}%<br><strong>{z}</strong>",
				indexLabelFontSize: 16,
				/* yValueFormatString: "#'%'", */
				legendText: data[ban0],
				showInLegend: true,
				dataPoints: [{
						label: "12-0",
						y: data[ban1],
						z: "Entradas:" + data[ban7] + ";  Ventas:" + data[ban8]
					},
					{
						label: "24-12",
						y: data[ban2],
						z: "Entradas:" + data[ban9] + ";  Ventas:" + data[ban10]
					},
					{
						label: "36-24",
						y: data[ban3],
						z: "Entradas:" + data[ban11] + ";  Ventas:" + data[ban12]
					},
					{
						label: "48-36",
						y: data[ban4],
						z: "Entradas:" + data[ban13] + ";  Ventas:" + data[ban14]
					},
					{
						label: "60-48",
						y: data[ban5],
						z: "Entradas:" + data[ban15] + ";  Ventas:" + data[ban16]
					},
					{
						label: "72-60",
						y: data[ban6],
						z: "Entradas:" + data[ban17] + "  Ventas:" + data[ban18]
					}
				]
			});

			ban0 += 19;
			ban1 += 19;
			ban2 += 19;
			ban3 += 19;
			ban4 += 19;
			ban5 += 19;
			ban6 += 19;
			ban7 += 19;
			ban8 += 19;
			ban9 += 19;
			ban10 += 19;
			ban11 += 19;
			ban12 += 19;
			ban13 += 19;
			ban14 += 19;
			ban15 += 19;
			ban16 += 19;
			ban17 += 19;
			ban18 += 19;


		}

		var chartFlota2 = new CanvasJS.Chart("chartContainerFiltro", {
			colorSet: "greenShades",
			animationEnabled: true,
			exportEnabled: true,
			theme: "light1", // "light1", "light2", "light2", "dark2"
			title: {
				text: filtro,
			},
			axisY: {
				includeZero: true,
				title: "Porcentaje",
				suffix: "%",
				maximum: 100,
				minimum: -1
			},
			legend: {
				cursor: "pointer",
				itemclick: toggleDataSeries
			},

			data: data_arr

		});




		document.getElementById("cargando").style.display = "none";
		document.getElementById("tablatotal").style.display = "none";
		document.getElementById("tablatotal_1").style.display = "Block";


		chartFlota2.render();



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


<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
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
