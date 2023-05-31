<?php $this->load->view('Informe_taller/header'); ?>
<div class="content-wrapper">
	<br>
	<!-- Main content -->
	<section class="content">
		<div class="card">
			<div class="card-header" align="center"><h4>Informe Mantenimiento Prepagado CODIESEL SA</h4></div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<a href="#" onclick="toexcel();" class="btn btn-success btn-sm">Descargar a excel</a>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-hover table-sm" id="tabla_mant_prep" style="font-size: 12px;">
					<thead align="center">
						<tr>
							<th scope="col">Codigo</th>
							<th scope="col">Placa</th>
							<th scope="col">NIT</th>
							<th scope="col">Cliente</th>
							<th scope="col">Mantenimiento</th>
							<th scope="col">Rev 5000KM</th>
							<th scope="col">Rev 10000KM</th>
							<th scope="col">Rev 15000KM</th>
							<th scope="col">Rev 20000KM</th>
							<th scope="col">Rev 25000KM</th>
							<th scope="col">Rev 30000KM</th>
							<th scope="col">Rev 35000KM</th>
							<th scope="col">Rev 40000KM</th>
							<th scope="col">Rev 45000KM</th>
							<th scope="col">Rev 50000KM</th>
							<th scope="col">Saldo</th>
						</tr>
					</thead>
					<tbody align="center">
						<?php foreach ($mant_prep->result() as $key) { ?>
						<tr>
							<th scope="row"><?=$key->codigo?></th>
							<td><?=$key->placa?></td>
							<td><?=$key->nit?></td>
							<td><?=$key->cliente?></td>
							<td><?=$key->mto?></td>
							<td>$<?= number_format($key->revision_5000KM,0,",",",")?></td>
							<td>$<?= number_format($key->revision_10000KM,0,",",",")?></td>
							<td>$<?= number_format($key->revision_15000KM,0,",",",")?></td>
							<td>$<?= number_format($key->revision_20000KM,0,",",",")?></td>
							<td>$<?= number_format($key->revision_25000KM,0,",",",")?></td>
							<td>$<?= number_format($key->revision_30000KM,0,",",",")?></td>
							<td>$<?= number_format($key->revision_35000KM,0,",",",")?></td>
							<td>$<?= number_format($key->revision_40000KM,0,",",",")?></td>
							<td>$<?= number_format($key->revision_45000KM,0,",",",")?></td>
							<td>$<?= number_format($key->revision_50000KM,0,",",",")?></td>
							<td>$<?=number_format(($key->valor_pagado-$key->valor_facturado),0,",",",")?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				</div>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>
<?php $this->load->view('Informe_taller/footer'); ?>