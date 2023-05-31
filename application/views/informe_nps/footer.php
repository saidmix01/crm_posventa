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
<!-- Bootstrap -->
<script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Camvasjs  libreria para graficar -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<!-- jQuery UI -->
<script src="<?= base_url() ?>plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- Toastr -->
<script src="<?= base_url() ?>plugins/toastr/toastr.min.js"></script>
<!-- SweetAlert2 -->
<script src="<?= base_url() ?>plugins/sweetalert2/sweetalert2.min.js"></script>
<!--table2excel -->
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!--seelct2-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!--usar botones en datatable-->
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<!--animate.css-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<!-- overlayScrollbars -->
<script src="<?= base_url() ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>


<script type="text/javascript">
	$(document).ready(function() {
		$("#buscar_items").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#menu_items li").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});

		$('.js-example-basic-single').select2({

			theme: "classic"
		});
	});
</script>
<script>
	//funcion para activar busqueda
	function buscar() {
		var mes = document.getElementById('mes').value;
		var sede = document.getElementById('sede').value;
		var tabla = document.getElementById('filtro');

		var url = '<?= base_url() ?>Informes/buscar_nps';
		var datos = new FormData();
		datos.append('mes', mes);
		datos.append('sede', sede)
		var request = new XMLHttpRequest();
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				tabla.innerHTML = request.responseText;
				document.getElementById('tabladatos').style.display = 'none';
				document.getElementById('btn-filtro').style.display = 'none';
			}
		}
		request.open("POST", url);
		request.send(datos);
	}
</script>


<script type="text/javascript">
	//funcion para convetir tabla en documento de excel

	var f = new Date();
	fecha = f.getDate() + "-" + (f.getMonth() + 1) + "-" + f.getFullYear();

	function bajar_excel() {
		$("#padretabla").table2excel({
			exclude: ".noExl",
			name: "Worksheet Name",
			filename: "Informe-NPS-" + "-" + fecha, //do not include extension
			fileext: ".xlsx" // file extension
		});
	}

	function bajar_excel_filtro() {
		$("#tablafiltro").table2excel({
			exclude: ".noExl",
			name: "Worksheet Name",
			filename: "Informe-NPS-" + "-" + fecha, //do not include extension
			fileext: ".xlsx" // file extension
		});
	}

	function bajar_excel_nps() {
		$("#tabladatosnps").table2excel({
			exclude: ".noExl",
			name: "Worksheet Name",
			filename: "Informe-NPS-" + "-" + fecha, //do not include extension
			fileext: ".xlsx" // file extension
		});
	}
</script>

<script src="<?= base_url() ?>dist/js/demo.js"></script>
<?php
$this->load->model('usuarios');
$usu = $this->usuarios->getUserById($id_usu);
$p = "";
$nit = "";
foreach ($usu->result() as $key) {
	$p = $key->pass;
	$nit = $key->nit;
}
//echo $p." ".$nit;
$this->load->library('encrypt');
$pass_desencript = $this->encrypt->decode($p);
if ($pass_desencript == $nit) {
?>
	<script type="text/javascript">
		$('#pass-modal').show('true')
	</script>
<?php
}
?>
<script type="text/javascript">
	setTimeout(function() {
		$('#alert_err').alert('close');
	}, 1500); //soy un comentario
</script>


<script>
	function aplicarPopoer() {
		//$(document).ready(function() {
		$('[data-toggle="popover"]').popover();
		//});
	}
</script>

<script>
	function vista() {

		/**
		 * FUNCION PARA VALIDAR QUE SEA UNA TABLA O UNA GRFICA
		 * ANDRES GOMEZ
		 * 2022-26-01
		 */
		var vista = document.getElementById('npsvista').value;
		if (vista == "info_grafica") {
			var mes = document.getElementById('mesnps');
			mes.removeAttribute("multiple");
			var sede = document.getElementById('sedenps');
			sede.removeAttribute('multiple');

		} else if (vista == "info_tabla") {
			var mes = document.getElementById('mesnps');
			mes.setAttribute('multiple', 'multiple');
			var sede = document.getElementById('sedenps');
			sede.setAttribute('multiple', 'multiple');

		}
	}


	/*
	FUNCION PARA  HACER LA BUSQUEDA Y RETORNAR UNA RESPUESTA SEA UNA TABLA O UNA GRAFICA
	ANDRES GOMEZ
	2022-26-01
	 */
	var boton = document.getElementById('buscar');
	boton.addEventListener('click', function() {

		var fecha = document.getElementById('fechanps').value;
		var mes = $('#mesnps').val();
		var sede = $('#sedenps').val();
		var nps = document.getElementById('nps').value;
		var vista = document.getElementById('npsvista').value;
		var tabla = document.getElementById('resultado_tabla');
		var grafica = document.getElementById('grafica');

		mes = mes == 'todos' ? "1,2,3,4,5,6,7,8,9,10,11,12" : mes;
		var url = '<?= base_url() ?>Informes/filtro_nps_final';
		var datos = new FormData();
		datos.append('fecha', fecha);
		datos.append('mes', mes)
		datos.append('sede', sede);
		datos.append('nps', nps)
		datos.append('vista', vista)
		var request = new XMLHttpRequest();
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				if (vista == 'info_grafica') {

					var dtos = JSON.parse(request.responseText);
					tabla.style.display = "none";
					grafica.style.display = "block";
					var chart = new CanvasJS.Chart("grafica", {
						animationEnabled: true,
						exportEnabled: true,
						theme: "light1", // "light1", "light2", "dark1", "dark2"
						title: {
							text: "Grafica de Informe nps"
						},
						axisY: {
							//includeZero: true,
							title: "Total en nps"
						},
						data: [{
							type: "column", //change type to bar, line, area, pie, etc
							//indexLabel: "{y}", //Shows y value on all Data Points
							indexLabelFontColor: "#5A5757",
							indexLabelPlacement: "outside",
							yValueFormatString: "#,##0.## ",
							dataPoints: dtos

						}]
					});

					chart.render();
				} else if ('info_tabla') {
					tabla.innerHTML = request.responseText;
					grafica.style.display = "none";
					tabla.style.display = "block";
					aplicarPopoer();
				}

			}
		}
		request.open("POST", url);
		request.send(datos);
	})
	//inicializar la libreria selec2 para el campo mese
	$(".meses").select2({});
	//inicializar la libreria selec2 para el campo sede
	$(".sedenps").select2({});
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
