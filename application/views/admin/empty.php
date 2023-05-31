<div class="container" align="center" style="margin-top: 15%;">
	<?php
	$idUsuario = $this->session->userdata('id_user');
	$perfilUsu = $this->session->userdata('perfil');

	if ($idUsuario == 477 || $idUsuario == 648 || $idUsuario == 441 || $idUsuario == 459 || $perfilUsu == 1 || $perfilUsu == 20) {
		$this->load->model('usuarios');
		$info = $this->usuarios->v_lealtad_repuestos();
		//lealtad_bruta_total

		$dataRepuestos = $info->row(0);
		//[lealtad_bruta_livianos] => 80.4200 [lealtad_neta_livianos] => 82.3200 [lealtad_bruta_pesados] => 91.8000 [lealtad_neta_pesados] => 92.8900 )
	}
	?>


	<div class="card">
		<div class="card-body">
			<?php if ($idUsuario == 477 || $idUsuario == 648 || $idUsuario == 441 || $idUsuario == 459 || $perfilUsu == 1 || $perfilUsu == 20) { ?>
				<div class="row">
					<div class="col-12 col-sm-6 col-md-2">
						<div class="info-box">
							<span class="info-box-icon bg-info elevation-1"><i class="fas fa-car-side"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Lealtad bruta livianos</span>
								<span class="info-box-number">
									<?= number_format($dataRepuestos->lealtad_bruta_livianos, 2, ',', '.') ?>
								</span>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-md-2">
						<div class="info-box">
							<span class="info-box-icon bg-info elevation-1"><i class="fas fa-car-side"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Lealtad neta livianos</span>
								<span class="info-box-number">
									<?= number_format($dataRepuestos->lealtad_neta_livianos, 2, ',', '.') ?>
								</span>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-md-2">
						<div class="info-box">
							<span class="info-box-icon bg-info elevation-1"><i class="fas fa-truck"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Lealtad bruta pesados</span>
								<span class="info-box-number">
									<?= number_format($dataRepuestos->lealtad_bruta_pesados, 2, ',', '.') ?>
								</span>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-md-2">
						<div class="info-box">
							<span class="info-box-icon bg-info elevation-1"><i class="fas fa-truck"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Lealtad neta pesados</span>
								<span class="info-box-number">
									<?= number_format($dataRepuestos->lealtad_neta_pesados, 2, ',', '.') ?>
								</span>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-md-3">
						<div class="info-box">
							<span class="info-box-icon bg-success elevation-1"><i class="fas fa-plus"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">Lealtad bruta total</span>
								<span class="info-box-number">
									<?= number_format($dataRepuestos->lealtad_bruta_total, 2, ',', '.') ?>
								</span>
							</div>
						</div>
					</div>
				</div>
		</div>
	</div>
<?php } ?>
<img src="<?= base_url() ?>media/logo/logo_codiesel_sinfondo.png" class="img-fluid" alt="Responsive image">
</div>