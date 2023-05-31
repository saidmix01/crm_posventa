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

	.scrol {
		overflow: scroll;
		max-height: 70vh; 
		width: 100%;
	}

	thead tr th {
		position: sticky;
		top: 0;
		z-index: 10;
		color: white;
	}

	.scrol table .fijar {
		position: sticky;
		background-color: #e9ecef;
	}

	.scrol table .fijar2 {
		position: sticky;
		background-color: #e9ecef;
		bottom: 0px;
	}

	.scrol table .titulo {
		position: sticky;
		z-index: 20;
		color: black;
	}

	table .titulo2 {
		top: 46.6px;
	}



	.tamaño {
		width: 110px;
		min-width: 100px;
		max-width: 180px;
	}

	.table td,
	.table th {
		vertical-align: middle;
	}

	.colun1 {
		width: 100px;
		min-width: 100px;
		max-width: 100px;
		left: 0px;
	}

	.numberT {
		width: 4em;

	}

	.color-1 {
		background-color: #F9EBC8;
	}

	.color-2 {
		background-color: #F4FCD9;
	}

	.color-3 {
		background-color: #A0BCC2;
	}

	.color-4 {
		background-color: #D9D7F1;
	}

	.color-5 {
		background-color: #F4BFBF;
	}

	.color-6 {
		background-color: #FFD9C0;
	}

	.color-7 {
		background-color: #8CC0DE;
	}
</style>
<!-- Main content -->

<div class="content-wrapper">

	<section class="content">

		<div class="card">
			<div class="loader" id="cargando"></div>
			<div class="card-body">
				<div><label class="col-lg-12 text-center lead">Informe de retención 72 - 0 </label></div>
				<div><label class="col-lg-12 text-center lead">Tabla general</label></div>
				<div class="form-row justify-content-center">
					<ul class="nav nav-tabs">
						<li class="nav-item">
							<a href="<?= base_url() ?>Informes/InformeVentas72" target="_blank" class="nav-link btn btn-outline-info">Inicio</a>
						</li>
						<li class="nav-item">
							<a class="nav-link btn btn-outline-info" href="#" onclick="PostCargaTabla();imagenCargando();destruirTabla();">General</a>
						</li>
						<li class="nav-item">
							<a class="nav-link dropdown-toggle btn btn-outline-info" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Autos</a>
							<div class="dropdown-menu">

								<a class="dropdown-item" href="#" onclick="filtroInformeSegmentoFamilia('Autos');imagenCargando();">General de Autos</a>
								<div class="dropdown-divider"></div>
								<?php foreach ($dataAutos->result() as $key) { ?>
									<a class="dropdown-item" href="#" onclick="filtroInformeSegmentoFamilia('<?= $key->segmento ?>');imagenCargando();"><?= $key->segmento ?></a>
								<?php } ?>


							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle btn btn-outline-info" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">B&C</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="#" onclick="filtroInformeSegmentoFamilia('B&C');imagenCargando();">General de B&C</a>
								<div class="dropdown-divider"></div>
								<?php foreach ($dataByC->result() as $key) { ?>
									<a class="dropdown-item" href="#" onclick="filtroInformeSegmentoFamilia('<?= $key->segmento ?>');imagenCargando();"><?= $key->segmento ?></a>
								<?php } ?>
							</div>
						</li>
						<li class="nav-item">
							<a class="nav-link btn btn-outline-info" href="#" data-toggle="modal" data-target="#FiltroModal">
								Filtrar por Segmento
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link btn btn-outline-info" href="#" data-toggle="modal" data-target="#filtroFamiliaTabla">
								Filtrar por Familia
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
				<div class="form-row">
					<div class="table-responsive scrol">

						<a href="#" class="btn btn-success" onclick="bajar_excel();"><i class="fas fa-plus-squares"></i>Descargar Excel </a>

						<table class="table table-hover table-bordered" align="center" id="tabla_data" style="font-size: 14px;">

						</table>
					</div>
					<div class="table-responsive" id="tablaVehiculos"></div>
				</div>
				<hr>
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

<!-- Modal por segmento y familias-->
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
				<button type="button" class="btn btn-info" onclick="filtrarFamilia();imagenCargando();">Buscar</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal Filtrar por familia-->
<div class="modal fade" id="filtroFamiliaTabla" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	
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
						<label for="">Seleccione las familias</label>
						<select class="form-control js-example-basic-multiple" multiple="true" name="familia2" id="familia2">
						<option value="">Seleccione uno o varias familias</option>
							<?php foreach($allFamilia->result() as $key) { ?>
								<option value="<?=$key->familia?>"><?=$key->familia?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-info" onclick="filtrarFamiliaAll();">Buscar</button>
			</div>
		</div>
	</div>
</div>

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

</script>



<script type="text/javascript">
	/* Funciones para el filtro por Familia */
	window.onload = function() {
		document.getElementById("cargando").style.display = "block";
		PostCargaTabla();
		cambia_segmento();

		$('.js-example-basic-multiple').select2({
			width: '100%',
			placeholder: 'Seleccione una o varias opciones'
		});

	}

	function imagenCargando() {
		document.getElementById("cargando").style.display = "block";
		$('#FiltroModal').modal('hide');
	}

	function destruirTabla() {
		$('#tabla_data').DataTable().destroy();
	}

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

	function PostCargaTabla() {

		var request = new XMLHttpRequest();
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				document.getElementById('tabla_data').innerHTML = request.responseText;
				totalFooterTable();
				document.getElementById("cargando").style.display = "none";
				load_datatable();
			}
		}
		request.open("POST", "<?= base_url() ?>Informes/PostCargaTablaInfVentas72", true);
		request.send();

	}

	function filtrarFamilia() {
		var selected = $("#familia").val();
		familia = JSON.stringify(selected);

		/* console.log(selected); */
		if (selected != "") {
			var request = new XMLHttpRequest();
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					$('#FiltroModal').modal('hide');
					$('#tabla_data').DataTable().destroy();
					/* document.getElementById('tablaFiltros').style.display = "none"; */
					/* document.getElementById('tablaFiltrosFamilia').innerHTML = request.responseText; */
					document.getElementById('tabla_data').innerHTML = request.responseText;
					totalFooterTable();
					/* document.getElementById('tablaFiltrosFamilia').style.display = "block"; */
					document.getElementById("cargando").style.display = "none";
					load_datatable();

				}
			}
			request.open("GET", "<?= base_url() ?>Informes/tablaFiltros?selected=" + familia, true);
			request.send();

		} else {
			document.getElementById("cargando").style.display = "none";
			Toast.fire({
				type: 'error',
				title: 'Debe seleccionar el tipo, el segmento y la familia para filtrar'
			});
			
		}
	}
	function filtrarFamiliaAll() {
		
		var selected = $("#familia2").val();
		familia = JSON.stringify(selected);

		/* console.log(selected); */
		if (selected != "") {
			$('#filtroFamiliaTabla').modal('hide');
			document.getElementById("cargando").style.display = "block";
			var request = new XMLHttpRequest();
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					$('#tabla_data').DataTable().destroy();
					document.getElementById('tabla_data').innerHTML = request.responseText;
					totalFooterTable();
					document.getElementById("cargando").style.display = "none";
					load_datatable();

				}
			}
			request.open("GET", "<?= base_url() ?>Informes/tablaFiltros?selected=" + familia, true);
			request.send();

		} else {
			
			Toast.fire({
				type: 'error',
				title: 'Debe seleccionar al menos una opción'
			});
			
		}
	}

	function filtroInformeSegmentoFamilia(filtro) {
		if (filtro == "") {
			Swal.fire({
				title: 'Advertencia!',
				text: 'Por favor seleccione un filtro',
				icon: 'warning',
				confirmButtonText: 'Ok'
			});
		} else {
			var datos = new FormData();
			datos.append('filtro', filtro);


			var request = new XMLHttpRequest();
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					$('#tabla_data').DataTable().destroy();
					document.getElementById('tabla_data').innerHTML = request.responseText;
					totalFooterTable();
					document.getElementById("cargando").style.display = "none";
					load_datatable();
				}
			}
			request.open("POST", "<?= base_url() ?>Informes/tablaFiltroSegmentoFamilia", true);
			request.send(datos);
		}
	}


	//funcion para convetir tabla en documento de excel

	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 5000
	});

	var f = new Date();
	fecha = f.getDate() + "-" + (f.getMonth() + 1) + "-" + f.getFullYear();

	function bajar_excel() {
		$("#tabla_data").table2excel({
			exclude: ".d-none",
			name: "Worksheet Name",
			filename: "InformeVentas-" + "-" + fecha, //do not include extension
			fileext: ".xlsx" // file extension
		});
	}

	function totalEntradaAdicionales(ban) {
		/* Facturas adicionales de 0 a 72 */
		var sumaF = 0;
		var fAdicionales = $('.fAdicionales' + ban).map(function() {
			sumaF = sumaF + parseInt(this.innerText);
		});
		document.getElementById('fAdicionaltotalVE_' + ban).innerHTML = sumaF.toLocaleString('co-CO', {
			style: 'decimal',
			minimumFractionDigits: 0
		});;
		document.getElementById('fAdicional-totalVE_' + ban).innerHTML = sumaF

		/* Entradas adicionales de 0 a 72 */
		var entrada12 = parseInt(document.getElementById('entradaAtotalVE_' + ban + '12').value);
		var entrada24 = parseInt(document.getElementById('entradaAtotalVE_' + ban + '24').value);
		var entrada36 = parseInt(document.getElementById('entradaAtotalVE_' + ban + '36').value);
		var entrada48 = parseInt(document.getElementById('entradaAtotalVE_' + ban + '48').value);
		var entrada60 = parseInt(document.getElementById('entradaAtotalVE_' + ban + '60').value);
		var entrada72 = parseInt(document.getElementById('entradaAtotalVE_' + ban + '72').value);
		/* Validar que no sean NaN */
		if (isNaN(entrada12)) {
			var e12 = 0;
		} else {
			var e12 = entrada12;
		}
		if (isNaN(entrada24)) {
			var e24 = 0;
		} else {
			var e24 = entrada24;
		}
		if (isNaN(entrada36)) {
			var e36 = 0;
		} else {
			var e36 = entrada36;
		}
		if (isNaN(entrada48)) {
			var e48 = 0;
		} else {
			var e48 = entrada48;
		}
		if (isNaN(entrada60)) {
			var e60 = 0;
		} else {
			var e60 = entrada60;
		}
		if (isNaN(entrada72)) {
			var e72 = 0;
		} else {
			var e72 = entrada72;
		}
		/* Sumar las entradas  */
		var sumaEntradas = e12 + e24 + e36 + e48 + e60 + e72;
		/* Imprimir la sumatoria */
		document.getElementById('entradaAtotalVE_' + ban).innerHTML = sumaEntradas;

		/* Array[0] valores de entrada Array[1] valores de ventas  */
		var array = $('.totalVE_' + ban).map(function() {
			return this.innerText;
		}).get();
		var newPorcentaje = ((parseInt(array[0]) + sumaEntradas) / parseInt(array[1])) * 100;
		/* Imprimir el porcentaje obtenido con las entradas adicionales */
		document.getElementById('pEstimadoTtotalVE_' + ban).innerHTML = newPorcentaje.toFixed(0) + '%';



	}

	function entradaAdicional(codigo, indice) {

		/* Array[0] valores de entrada Array[1] valores de ventas  */
		var array = $('.' + codigo).map(function() {
			return this.innerText;
		}).get();
		var eFaltantes = parseInt(array[1]) - parseInt(array[0]);
		var entradaAdicional = parseInt(document.getElementById('entradaA' + codigo).value);
		if (parseInt(array[1]) > 0) {

			if (entradaAdicional <= eFaltantes) {

				var newPorcentaje = ((parseInt(array[0]) + entradaAdicional) / parseInt(array[1])) * 100;
				document.getElementById('pEstimadoT' + codigo).innerHTML = newPorcentaje.toFixed(0) + '%';
				var facturaT = parseFloat($('#facturaT' + codigo)[0].innerText);
				var sFacturaT = facturaT * entradaAdicional;
				document.getElementById('fAdicional-' + codigo).innerHTML = sFacturaT;
				document.getElementById('fAdicional' + codigo).innerHTML = sFacturaT.toLocaleString('co-CO', {
					style: 'decimal',
					minimumFractionDigits: 0
				});
				totalEntradaAdicionales(indice);
				totalFooterTable();

			} else {
				Swal.fire({
					title: 'Error!',
					text: 'No se pueden agregar entradas adicionales que superen el monto de venta del parque: ' + parseInt(array[1]) + ' unidades. ' +
						'Entradas maxima que puedes agregar: ' + eFaltantes,
					icon: 'warning',
					confirmButtonText: 'Cerrar'
				});
				$('input.limpiar-' + codigo).val('0');
				$('#fAdicional-' + codigo).html('0')
				$('#fAdicional' + codigo).html('0')
				$('#pEstimadoT' + codigo).text('0');
				totalFooterTable();
			}

		} else {
			Swal.fire({
				title: 'Error!',
				text: 'No se pueden agregar entradas adicionales ya que no hay existencias en el parque',
				icon: 'warning',
				confirmButtonText: 'Cerrar'
			});
			$('input.limpiar-' + codigo).val('0');
			$('#fAdicional-' + codigo).html('0')
			$('#fAdicional' + codigo).html('0')
			$('#pEstimadoT' + codigo).text('0');
			totalFooterTable();

		}


	}

	function totalFooterTable() {
		/* Sumatoria de para los Totales */
		for (let index = 1; index <= 35; index++) {
			var suma = 0;
			if (index == 8 || index == 13 || index == 18 || index == 23 || index == 28 || index == 33) {
				$('.tc' + index).map(function() {
					suma = suma + parseInt(this.value);
				});
				document.getElementById('th' + index).innerHTML = suma.toLocaleString('co-CO', {
					style: 'decimal',
					minimumFractionDigits: 0
				});;
			} else {
				$('.tc' + index).map(function() {
					suma = suma + parseInt(this.innerText);
				});
				document.getElementById('th' + index).innerHTML = suma.toLocaleString('co-CO', {
					style: 'decimal',
					minimumFractionDigits: 0
				});;
			}



		}


	}

	function load_datatable() {
		$('#tabla_data').DataTable({
			"paging": true,
			"pageLength": 10,
			"lengthChange": true,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"language": {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_ registros",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla =(",
				"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
				"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix": "",
				"sSearch": "Buscar:",
				"sUrl": "",
				"sInfoThousands": ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst": "Primero",
					"sLast": "Último",
					"sNext": "Siguiente",
					"sPrevious": "Anterior"
				},
				"oAria": {
					"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				},
				"buttons": {
					"copy": "Copiar",
					"colvis": "Visibilidad"
				}
			}
		});
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
