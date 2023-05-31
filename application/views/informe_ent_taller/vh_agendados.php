<?php $this->load->view('Informe_ent_taller/header') ?>

<div class="content-wrapper">
	<br>
	<!-- Main content -->
	<section class="content">
		<div class="row container-fluid"><h3>Informe detallado Porcentaje VH Agendados</h3></div>
		
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
								<div class="small-box bg-warning">
									<div class="inner">
										<h1><?=round($key['porcen_vh_agendados'])?>%</h1>

										<p>Porcentaje VH Agendados</p>
									</div>
									<div class="icon">
										<i class="fas fa-car"></i>
									</div>
									
								</div>
							</div>
							<div class="col-md-8"><div id="a<?=$key['bod']?>" style="height: 370px; width: 100%;"></div></div>
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