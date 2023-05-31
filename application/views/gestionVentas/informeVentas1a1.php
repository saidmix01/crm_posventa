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
		color: black;
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
</style>
<!-- Main content -->

<div class="content-wrapper">

	<section class="content">
		<div class="loader" id="cargando"></div>
		<div class="card">
			<div class="card-body">
				<div><label class="col-lg-12 text-center lead">Informe General de ventas ultimos 72 meses</label></div>
				<form>
					<div class="form-row justify-content-center">
						<div class="col-md-2">
							<label for="Select2">Año:</label>
							<input type="text" class="form-control" name="datepicker" id="datepicker" />
						</div>
						<div class="col-md-2">
							<label for="Select2">Asesor:</label>
							<select class="form-control js-example-basic-single" style="width:100%" id="Select2" onclick="">';
								<option></option>
								<?php foreach ($infAsesores->result() as $key) {
									echo '<option value="' . $key->nit_asesor . '">' . $key->asesor . '</option>';
								} ?>

							</select>
						</div>
						<div class="col-md-2">
							<label for="Select2"></label>
							<button onclick="buscar();" style=" text-shadow: 5px 3px 4px #000000; width: 100%" type="button" class="btn btn-info " id="enviarFechas"> Buscar &nbsp; <i class="fas fa-search"></i></button>
						</div>
						<div class="col-md-2">
							<label for="Select2"></label>
							<button onclick="window.location.reload();" style=" text-shadow: 5px 3px 4px #000000; width: 100%" type="button" class="btn btn-success " id="enviarFechas"> Recargar &nbsp; <i class="fas fa-sync"></i></button>
						</div>
					</div>
				</form>
				<br>
				<div class="form-row" id="bodyTabla">
					<div class="table-responsive">
						<span><a href="#" class="btn btn-success" onclick="bajar_excel();"><i class="fas fa-plus-squares"></i>Descargar Excel </a></span>
						<table class="table table-hover table-bordered" align="center" id="tabla_data" style="font-size: 14px;">
							<thead align="center" class="thead-light">

								<tr>
									<th class="titulo colun1 fijar" scope="col">#</th>
									<th class="titulo colun1 fijar" scope="col">Año</th>
									<th class="titulo colun1 fijar" scope="col">Nit Asesor</th>
									<th class="titulo colun1 fijar" scope="col">Asesor</th>
									<th class="titulo colun1 fijar" scope="col">Mano de obra</th>
									<th class="titulo colun1 fijar" scope="col">Venta de repuestos</th>
									<th class="titulo colun1 fijar" scope="col">Costo de repuestos</th>
									<th class="titulo colun1 fijar" scope="col">Utilidad</th>
									<th class="titulo colun1 fijar" scope="col">Porcentaje</th>

								</tr>

							</thead>
							<tbody align="center">
								<?php
								/* $contador = 0;

									foreach ($dataInforme->result() as $key){
										
									
										echo '
										<tr>
										<td>' .($contador+1) .'</td>
										<td>' . $key->año . '</td>
										<td>' . $key->nit_asesor . '</td>
										<td>' . $key->asesor . '</td>
										<td>' . number_format($key->Venta_mano_obra, 0, ",", ".") . '</td>
										<td>' . number_format($key->venta_rptos, 0, ",", ".") . '</td>
										<td>' . number_format($key->costo_rptos, 0, ",", ".") . '</td>
										<td>' . number_format(round(($key->venta_rptos) - ($key->costo_rptos)), 0, ",", ".") . '</td>
										<td></td>
										<td>' . round(($rowData->entradas / $rowData->ventas) * 100, 2) . '%</td>
										</tr>
										';										
									
								} */
								/* foreach ($dataPorcentaje->result() as $value) {
									$rowData = $dataInforme->row($contador);
									if ($value->vendedor == $rowData->nit_asesor) {
										echo '
										<tr>
										<td>' . ($contador + 1) . '</td>
										<td>' . $rowData->año . '</td>
										<td>' . $rowData->nit_asesor . '</td>
										<td>' . $rowData->asesor . '</td>
										<td>' . number_format($rowData->Venta_mano_obra, 0, ",", ".") . '</td>
										<td>' . number_format($rowData->venta_rptos, 0, ",", ".") . '</td>
										<td>' . number_format($rowData->costo_rptos, 0, ",", ".") . '</td>
										<td>' . number_format(round(($rowData->venta_rptos) - ($rowData->costo_rptos)), 0, ",", ".") . '</td>
										<td>' . round(($value->entradas / $value->ventas) * 100, 2) . '%</td>
										</tr>
										';
										$contador += 1;
										
									}
									
								} */

								?>
							</tbody>


						</table>
					</div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>

<script>
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 5000
	});

	function buscar() {
		var asesor = document.getElementById('Select2').value;
		var year = document.getElementById('datepicker').value;
		var yearV = new Date();
		fechaV = yearV.getFullYear();

		if (parseInt(year) >= 2020 && parseInt(year) <= fechaV) {
			document.getElementById("cargando").style.display = "block";
			var datos = new FormData();
			datos.append('asesor', asesor);
			datos.append('year', year);
			if (asesor == "" && year == "") {
				Swal.fire({
					title: 'Error!',
					text: 'Debe seleccionar por lo menos el año...',
					icon: 'warning',
					confirmButtonText: 'Cerrar'
				});
				document.getElementById("cargando").style.display = "none";
			} else if (asesor == "" && year != "") {
				var datos = new FormData();
				var asesor1 = "";
				datos.append('asesor', asesor1);
				datos.append('year', year);

				var request = new XMLHttpRequest();
				request.onreadystatechange = function() {
					if (request.readyState === 4 && request.status === 200) {

						$('#tabla_data').DataTable().destroy();
						document.getElementById('tabla_data').innerHTML = request.responseText;
						$('[data-toggle="popover"]').popover();
						loadDatatable();
						document.getElementById("cargando").style.display = "none";



					}
				}
				request.open("POST", "<?= base_url() ?>Informes/cargarInformeVentas", true);
				request.send(datos);


			} else if (asesor != "" && year != "") {
				var datos = new FormData();
				datos.append('asesor', asesor);
				datos.append('year', year);

				var request = new XMLHttpRequest();
				request.onreadystatechange = function() {
					if (request.readyState === 4 && request.status === 200) {

						$('#tabla_data').DataTable().destroy();
						document.getElementById('tabla_data').innerHTML = request.responseText;
						$('[data-toggle="popover"]').popover();
						loadDatatable();
						document.getElementById("cargando").style.display = "none";



					}
				}
				request.open("POST", "<?= base_url() ?>Informes/cargarInformeVentas", true);
				request.send(datos);
			} else {
				Swal.fire({
					title: 'Error!',
					text: 'Debe seleccionar el año, para realizar al busqueda',
					icon: 'warning',
					confirmButtonText: 'Cerrar'
				});
				document.getElementById("cargando").style.display = "none";
			}
		} else {
			Swal.fire({
				title: 'Error!',
				text: 'Debe seleccionar un año desde el 2020 hasta ' + fechaV,
				icon: 'warning',
				confirmButtonText: 'Cerrar'
			});
		}





	}

	var f = new Date();
	fecha = f.getDate() + "-" + (f.getMonth() + 1) + "-" + f.getFullYear();

	function bajar_excel() {
		$("#tabla_data").table2excel({
			exclude: ".d-none",
			name: "Worksheet Name",
			filename: "InformeVentas1a1-" + "-" + fecha, //do not include extension
			fileext: ".xlsx" // file extension
		});
	}

	function loadDatatable() {
		$('#tabla_data').DataTable({
			"paging": true,
			"pageLength": 10,
			"lengthChange": true,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": true,
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


	$(document).ready(function() {
		$("#datepicker").datepicker({
			format: "yyyy",
			viewMode: "years",
			minViewMode: "years",
			autoclose: true,
			minDate: 2020
		});

		$('.js-example-basic-single').select2({
			theme: "classic",
			placeholder: 'Seleccione un asesor',
			allowClear: true,
			height: '36px'
		});
		cargarInfVentas1a1();
		loadDatatable();
		document.getElementById("cargando").style.display = "block";

	})

	function cargarInfVentas1a1() {
		var result = document.getElementById("bodyTabla");
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				result.innerHTML = xmlhttp.responseText;
				$('[data-toggle="popover"]').popover();
				document.getElementById("cargando").style.display = "none";
				loadDatatable();


			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>Informes/cargarInformeVentasTotal", true);
		xmlhttp.send();
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
