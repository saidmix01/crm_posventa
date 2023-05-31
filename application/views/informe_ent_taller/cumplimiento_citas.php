<?php $this->load->view('Informe_ent_taller/header') ?>

<div class="content-wrapper">
	<br>
	<!-- Main content -->
	<section class="content">
		<div class="row container-fluid"><h3>Informe detallado Porcentaje Cumplimiento Citas</h3></div>
		
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
							<!-- /.col -->
							<div class="col-lg-4 col-4">
								<!-- small box -->
								<div class="small-box bg-primary">
									<div class="inner">
										<div class="row" align="center">
											<div class="col-md-4"><strong>Temprano: </strong><h5><?=round($key['temprano'])?> %</h5></div>
											<div class="col-md-4"><strong>A Tiempo: </strong><h5><?=round($key['atiempo'])?> %</h5></div>
											<div class="col-md-4"><strong>Llegó Tarde: </strong><h5><?=round($key['tarde'])?> %</h5></div>
										</div>

										<p>Cumplimiento de Citas</p>
									</div>
									<div class="icon">
										<i class="far fa-clock"></i>
									</div>
								
								</div>
							</div>
							<div class="col-md-8">
								<div id="b<?=$key['bod']?>" style="height: 370px; width: 100%;"></div>
							</div>
							<!-- /.col -->
							<!-- /.col -->
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		
	</section>
	<!-- /.content -->
</div>

<?php $this->load->view('Informe_ent_taller/footer') ?>