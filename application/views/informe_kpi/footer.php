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
	<!-- jQuery UI 1.11.4 -->
	<!-- Bootstrap 4 -->
	<script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- overlayScrollbars -->
	<script src="<?= base_url() ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- DataTables -->
	<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
	<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>

	<script src="<?= base_url() ?>dist/js/adminlte.js"></script>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<!--select2-->
	<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$("#buscar_items").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#menu_items li").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});

		$('.js-example-basic-single').select2({
  			placeholder: 'Seleccione una opción',
		});
		
	});
</script>
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

<script type="text/javascript">
	function load_inf1() {

		var rd1 = document.getElementById('rd1');
		var inf1 = document.getElementById('inf1');
		var inf2 = document.getElementById('inf2').style.display = 'none';
		var inf3 = document.getElementById('inf3').style.display = 'none';
		if (rd1.checked) {
			inf1.style.display = 'block';
		}
		var elem = document.getElementById("inf1");
		var ancho = 0;
		if(elem) {
		   var rect = elem.getBoundingClientRect();
		   ancho = rect.width;  
		}
		var chart = new CanvasJS.Chart("graf_inf1", {
		title: {
			text: "Gráfica Cantidad OT mantenimiento preventivo"
		},
		theme: "light2",
		animationEnabled: true,
		toolTip:{
			shared: true,
			reversed: true
		},
		axisY: {
			title: "Cantidad de ordenes",
			suffix: ""
		},
		legend: {
			cursor: "pointer",
			itemclick: toggleDataSeries
		},width: ancho,
		data: [
			{
				type: "stackedColumn",
				name: "BOCONO DIESEL EXPRESS",
				showInLegend: true,
				yValueFormatString: "",
				dataPoints: <?php echo json_encode($data_graf11, JSON_NUMERIC_CHECK); ?>
			},{
				type: "stackedColumn",
				name: "CHEVYEXPRESS BARRANCA",
				showInLegend: true,
				yValueFormatString: "",
				dataPoints: <?php echo json_encode($data_graf12, JSON_NUMERIC_CHECK); ?>
			},{
				type: "stackedColumn",
				name: "CHEVYEXPRESS LA ROSITA",
				showInLegend: true,
				yValueFormatString: "",
				dataPoints: <?php echo json_encode($data_graf13, JSON_NUMERIC_CHECK); ?>
			},{
				type: "stackedColumn",
				name: "CODIESEL PRINCIPAL",
				showInLegend: true,
				yValueFormatString: "",
				dataPoints: <?php echo json_encode($data_graf14, JSON_NUMERIC_CHECK); ?>
			},{
				type: "stackedColumn",
				name: "CODIESEL VILLA DEL ROSARIO",
				showInLegend: true,
				yValueFormatString: "",
				dataPoints: <?php echo json_encode($data_graf15, JSON_NUMERIC_CHECK); ?>
			},{
				type: "stackedColumn",
				name: "DIESEL EXPRESS BARRANCA",
				showInLegend: true,
				yValueFormatString: "",
				dataPoints: <?php echo json_encode($data_graf16, JSON_NUMERIC_CHECK); ?>
			},{
				type: "stackedColumn",
				name: "DIESEL EXPRESS GIRON",
				showInLegend: true,
				yValueFormatString: "",
				dataPoints: <?php echo json_encode($data_graf17, JSON_NUMERIC_CHECK); ?>
			}

		]
	});
	 
	chart.render();
	 
	function toggleDataSeries(e) {
		if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
			e.dataSeries.visible = false;
		} else {
			e.dataSeries.visible = true;
		}
		e.chart.render();
	}
	 
	}
	function load_inf2() {
		var rd2 = document.getElementById('rd2');
		var inf2 = document.getElementById('inf2');
		var inf1 = document.getElementById('inf1').style.display = 'none';
		var inf3 = document.getElementById('inf3').style.display = 'none';
		if (rd2.checked) {
			inf2.style.display = 'block';
		}
		var elem = document.getElementById("inf2");
		var ancho = 0;
		if(elem) {
		   var rect = elem.getBoundingClientRect();
		   ancho = rect.width;  
		}
		var graf_inf2 = new CanvasJS.Chart("graf_inf2", {
		title: {
			text: "Gráfica Cantidad OT cargo a cliente"
		},
		theme: "light2",
		animationEnabled: true,
		toolTip:{
			shared: true,
			reversed: true
		},
		axisY: {
			title: "Cantidad de ordenes",
			suffix: ""
		},
		legend: {
			cursor: "pointer",
			itemclick: toggleDataSeries
		},width: ancho,
		data: [
			{
				type: "stackedColumn",
				name: "BOCONO DIESEL EXPRESS",
				showInLegend: true,
				yValueFormatString: "",
				dataPoints: <?php echo json_encode($data_graf21, JSON_NUMERIC_CHECK); ?>
			},{
				type: "stackedColumn",
				name: "CHEVYEXPRESS BARRANCA",
				showInLegend: true,
				yValueFormatString: "",
				dataPoints: <?php echo json_encode($data_graf22, JSON_NUMERIC_CHECK); ?>
			},{
				type: "stackedColumn",
				name: "CHEVYEXPRESS LA ROSITA",
				showInLegend: true,
				yValueFormatString: "",
				dataPoints: <?php echo json_encode($data_graf23, JSON_NUMERIC_CHECK); ?>
			},{
				type: "stackedColumn",
				name: "CODIESEL PRINCIPAL",
				showInLegend: true,
				yValueFormatString: "",
				dataPoints: <?php echo json_encode($data_graf24, JSON_NUMERIC_CHECK); ?>
			},{
				type: "stackedColumn",
				name: "CODIESEL VILLA DEL ROSARIO",
				showInLegend: true,
				yValueFormatString: "",
				dataPoints: <?php echo json_encode($data_graf25, JSON_NUMERIC_CHECK); ?>
			},{
				type: "stackedColumn",
				name: "DIESEL EXPRESS BARRANCA",
				showInLegend: true,
				yValueFormatString: "",
				dataPoints: <?php echo json_encode($data_graf26, JSON_NUMERIC_CHECK); ?>
			},{
				type: "stackedColumn",
				name: "DIESEL EXPRESS GIRON",
				showInLegend: true,
				yValueFormatString: "",
				dataPoints: <?php echo json_encode($data_graf27, JSON_NUMERIC_CHECK); ?>
			}

		]
	});
	 
	graf_inf2.render();
	 
	function toggleDataSeries(e) {
		if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
			e.dataSeries.visible = false;
		} else {
			e.dataSeries.visible = true;
		}
		e.graf_inf2.render();
	}
	}

	function load_inf3() {
		var rd3 = document.getElementById('rd3');
		var inf3 = document.getElementById('inf3');
		var inf1 = document.getElementById('inf1').style.display = 'none';
		var inf2 = document.getElementById('inf2').style.display = 'none';
		if (rd3.checked) {
			inf3.style.display = 'block';
		}
	}
</script>
<script>
		$(document).ready(function() {
			$('.example1').DataTable({
				"paging": true,
				"pageLength": 25,
				"lengthChange": true,
				"searching": true,
				"ordering": true,
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
		});
	</script>
	<script>
window.onload = function () {
 

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
</html>