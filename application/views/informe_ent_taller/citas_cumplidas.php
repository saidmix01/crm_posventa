<?php $this->load->view('Informe_ent_taller/header') ?>

<div class="content-wrapper">
	<br>
	<!-- Main content -->
	<section class="content">
		<div class="row container-fluid"><h3>Informe detallado Porcentaje Citas Cumplidas</h3></div>

		<?php foreach ($data as $key) { 
			$nom_bod = "";
			if ($key['bod'] == 1) {
				$nom_bod = "Giron Gasolina";
			}else if ($key['bod'] == 11) {
				$nom_bod = "Giron Diesel";
			}else if ($key['bod'] == 9) {
				$nom_bod = "Giron Colisión Livianos";
			}else if ($key['bod'] == 21) {
				$nom_bod = "Giron Colisión Pesados";
			}
			?>
			<div class="row container-fluid">
				<div class="card" style="width: 100%;">
					<div class="card-header" align="center">
						<?=$nom_bod?>
					</div>
					<div class="card-body">
						<div class="row container-fluid">
							<div class="col-lg-4 col-4">
								<div class="small-box bg-info">
									<div class="inner">
										<h1><?=round($key['porcen_citas_cumplidas'])?>%</h1>
										<p>Porcentaje Citas Cumplidas</p>
									</div>
									<div class="icon">
										<i class="fas fa-chart-line"></i>
									</div>
								</div>
							</div>
							<div class="col-lg-8 col-8">
								<div id="c<?=$key['bod']?>" style="height: 370px; width: 100%;"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

	</section>
	<!-- /.content -->
</div>

<?php $this->load->view('Informe_ent_taller/footer') ?>