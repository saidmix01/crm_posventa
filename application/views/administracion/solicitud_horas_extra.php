<?php $this->load->view('administracion/header');

$this->load->model('AdministracionCodiesel');
$usu = $this->session->userdata('user');
$isjefe = $this->AdministracionCodiesel->val_jefe($usu)->n;

if ($isjefe != 0) {
?>

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<br>
		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-md-12" align="center">
					<h4>Solicitud labores en tiempo suplementario</h4>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12 container">
									<div id="calendar_horas_e" style="width: 50%;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->

	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					...
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
	</div>

<?php
} elseif ($isjefe == 0) {
?>
	<div class="content-wrapper">
		<br>
		<!-- Main content -->
		<section class="content">
			<div class="alert alert-light col-lg-12 text-center" role="alert"><h4>No tiene permisos para acceder a este modulo!</h4></div>
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->
<?php }
$this->load->view('administracion/footer_h_e');
?>
