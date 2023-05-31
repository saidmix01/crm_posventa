<?php $this->load->view('Informe_taller/header') ?>
<div class="content-wrapper">
	<br>
	<!-- Main content -->
	<section class="content">
		<div class="card">
			<div class="card-header" align="center"><h4>Informe ordenes de taller abiertas</h4></div>
			<div class="card-body">
				<?php if ($tipo_inf=="general") { ?>
					<div class="row">
						<div class="col-md-3">
							<div class="small-box bg-warning" style="width: 18rem;">
								<div class="inner">
									<h3><?=$total_sedes['giron']?></h3>
									<p>Cantidad de Ordenes Abiertas GIRÓN PRINCIPAL</p>
								</div>
								<div class="icon">
									<i class="fas fa-taxi"></i>
								</div>
								<a href="<?=base_url()?>taller/Informe_ot_taller_by_sede?sede=giron" class="small-box-footer">Mas Información <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<div class="col-md-3">
							<div class="small-box bg-warning" style="width: 18rem;">
								<div class="inner">
									<h3><?=$total_sedes['rosita']?></h3>
									<p>Cantidad de Ordenes Abiertas LA ROSITA</p>
								</div>
								<div class="icon">
									<i class="fas fa-taxi"></i>
								</div>
								<a href="<?=base_url()?>taller/Informe_ot_taller_by_sede?sede=rosita" class="small-box-footer">Mas Información <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<div class="col-md-3">
							<div class="small-box bg-warning" style="width: 18rem;">
								<div class="inner">
									<h3><?=$total_sedes['barranca']?></h3>
									<p>Cantidad de Ordenes Abiertas BARRANCABERMEJA</p>
								</div>
								<div class="icon">
									<i class="fas fa-taxi"></i>
								</div>
								<a href="<?=base_url()?>taller/Informe_ot_taller_by_sede?sede=barranca" class="small-box-footer">Mas Información <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<div class="col-md-3">
							<div class="small-box bg-warning" style="width: 18rem;">
								<div class="inner">
									<h3><?=$total_sedes['bocono']?></h3>
									<p>Cantidad de Ordenes Abiertas CUCUTA BOCONÓ</p>
								</div>
								<div class="icon">
									<i class="fas fa-taxi"></i>
								</div>
								<a href="<?=base_url()?>taller/Informe_ot_taller_by_sede?sede=bocono" class="small-box-footer">Mas Información <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-hover" id="tabla_ot_abiertas">
									<thead>
										<tr align="center">
											<th scope="col">Numero Orden</th>
											<th scope="col">Bodega</th>
											<th scope="col">Cliente</th>
											<th scope="col">Asesor</th>
											<th scope="col">Fecha Entrada</th>
											<th scope="col">Vehiculo</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($ot_abiertas->result() as $key) { ?>
											<tr align="center">
												<th><?=$key->numero?></th>
												<td><?=$key->descripcion?></td>
												<td><?=$key->cliente?></td>
												<td><?=$key->asesor?></td>
												<td><?=$key->fecha?></td>
												<td><?=$key->vh?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				<?php }elseif ($tipo_inf=="sedes") { ?>
					<div class="row">
						<?php foreach ($n_ot->result() as $key) { ?>
							<div class="col-md-3">
							<div class="small-box bg-success" style="width: 18rem;">
								<div class="inner">
									<h3><?=$key->n?></h3>
									<p>Cantidad de Ordenes Abiertas <?=$key->descripcion?></p>
								</div>
								<div class="icon">
									<i class="fas fa-taxi"></i>
								</div>
								<a href="<?=base_url()?>taller/Informe_ot_taller_by_taller?taller=<?=$key->bodega?>" class="small-box-footer">Mas Información <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<?php } ?>	
					</div>
					<hr>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-hover" id="tabla_ot_abiertas">
									<thead>
										<tr align="center">
											<th scope="col">Numero Orden</th>
											<th scope="col">Bodega</th>
											<th scope="col">Cliente</th>
											<th scope="col">Asesor</th>
											<th scope="col">Fecha Entrada</th>
											<th scope="col">Vehiculo</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($ot_abiertas->result() as $key) { ?>
											<tr align="center">
												<th><?=$key->numero?></th>
												<td><?=$key->descripcion?></td>
												<td><?=$key->cliente?></td>
												<td><?=$key->asesor?></td>
												<td><?=$key->fecha?></td>
												<td><?=$key->vh?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				<?php }elseif($tipo_inf=="tecnico"){?>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-hover" id="tabla_ot_tec">
									<thead>
										<tr align="center">
											<th scope="col">Asesor</th>
											<th scope="col">Ordenes abiertas</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($data_ot_tec->result() as $key) { ?>
											<tr align="center">
												<th><?=$key->nombres?></th>
												<td><?=$key->n?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>
<?php $this->load->view('Informe_taller/footer') ?>