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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
	<!-- Sparkline -->
	<script src="<?= base_url() ?>plugins/sparklines/sparkline.js"></script>
	<!-- JQVMap -->
	<script src="<?= base_url() ?>plugins/jqvmap/jquery.vmap.min.js"></script>
	<script src="<?= base_url() ?>plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
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
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="<?= base_url() ?>dist/js/pages/dashboard.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="<?= base_url() ?>dist/js/demo.js"></script>
	<!-- DataTables -->
	<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
	<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
	<!-- Select2 -->
	<script src="<?= base_url() ?>plugins/select2/js/select2.full.min.js"></script>

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
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

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

<?php //print_r($var); ?>
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
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>




<!-- SCRIPS PARA EL INDEX INF GENERAL -->
<?php if (isset($graf_citas_cumplidas) && isset($graf_estado_cita) && isset($graf_vh_agen)) {?>
<script>
window.onload = function() {
 
var chart_cc = new CanvasJS.Chart("graf_citas_cumplidas", {
	theme: "light2",
	animationEnabled: true,
	title: {
		text: "Gráfica Citas Cumplidas"
	},
	data: [{
		type: "doughnut",
		indexLabel: "{symbol} - {y}",
		yValueFormatString: "",
		showInLegend: true,
		legendText: "{label} : {y}",
		dataPoints: <?php echo json_encode($graf_citas_cumplidas, JSON_NUMERIC_CHECK); ?>
	}]
});
chart_cc.render();
var chart_ec = new CanvasJS.Chart("graf_estado_citas", {
	theme: "light2",
	animationEnabled: true,
	title: {
		text: "Gráfica Estado Citas"
	},
	data: [{
		type: "doughnut",
		indexLabel: "{symbol} - {y}",
		yValueFormatString: "",
		showInLegend: true,
		legendText: "{label} : {y}",
		dataPoints: <?php echo json_encode($graf_estado_cita, JSON_NUMERIC_CHECK); ?>
	}]
});
chart_ec.render();
var chart_ac = new CanvasJS.Chart("graf_vh_agen", {
	theme: "light2",
	animationEnabled: true,
	title: {
		text: "Gráfica VH Agendados"
	},
	data: [{
		type: "doughnut",
		indexLabel: "{symbol} - {y}",
		yValueFormatString: "",
		showInLegend: true,
		legendText: "{label} : {y}",
		dataPoints: <?php echo json_encode($graf_vh_agen, JSON_NUMERIC_CHECK); ?>
	}]
});
chart_ac.render();
 
}
</script>
<?php } ?>

<?php if (isset($data) && $var == "c") { ?>
	<script>
			window.onload = function() {
<?php
	foreach ($data as $key) {?>
		
			 
			var chart_c<?=$key['bod']?> = new CanvasJS.Chart("c<?=$key['bod']?>", {
				theme: "light2",
				animationEnabled: true,
				title: {
					text: "Gráfica Citas Cumplidas"
				},
				data: [{
					type: "doughnut",
					indexLabel: "{symbol} - {y}",
					yValueFormatString: "",
					showInLegend: true,
					legendText: "{label} : {y}",
					dataPoints: <?php echo json_encode($key['graf_citas_cumplidas'], JSON_NUMERIC_CHECK); ?>
				}]
			});
			chart_c<?=$key['bod']?>.render();
		
<?php 
	}?>
}
	</script>
<?php
}
 ?>

 <?php if (isset($data) && $var == "b") { ?>
	<script>
			window.onload = function() {
<?php
	foreach ($data as $key) {?>
		
			 
			var chart_b<?=$key['bod']?> = new CanvasJS.Chart("b<?=$key['bod']?>", {
				theme: "light2",
				animationEnabled: true,
				title: {
					text: "Gráfica Cumplimiento Citas"
				},
				data: [{
					type: "doughnut",
					indexLabel: "{symbol} - {y}",
					yValueFormatString: "",
					showInLegend: true,
					legendText: "{label} : {y}",
					dataPoints: <?php echo json_encode($key['graf_estado_cita'], JSON_NUMERIC_CHECK); ?>
				}]
			});
			chart_b<?=$key['bod']?>.render();
		
<?php 
	}?>
}
	</script>
<?php
}
 ?>

  <?php if (isset($data) && $var == "a") { ?>
	<script>
			window.onload = function() {
<?php
	foreach ($data as $key) {?>
		
			 
			var chart_a<?=$key['bod']?> = new CanvasJS.Chart("a<?=$key['bod']?>", {
				theme: "light2",
				animationEnabled: true,
				title: {
					text: "Gráfica Cumplimiento Citas"
				},
				data: [{
					type: "doughnut",
					indexLabel: "{symbol} - {y}",
					yValueFormatString: "",
					showInLegend: true,
					legendText: "{label} : {y}",
					dataPoints: <?php echo json_encode($key['graf_vh_agen'], JSON_NUMERIC_CHECK); ?>
				}]
			});
			chart_a<?=$key['bod']?>.render();
		
<?php 
	}?>
}
	</script>
<?php
}
 ?>
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
