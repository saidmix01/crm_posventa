<?php $this->load->view('Informe_kpi/header') ?>

<div class="content-wrapper">
	<br>
	<!-- Main content -->
	<section class="content">
		<div class="card">
			<div class="card-body">
				<div class="row" align="center">
					<h3>Informe KPI</h3>

				</div>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group clearfix">
							<div class="icheck-primary d-inline">
								<input type="radio" id="rd1" name="r1" onclick="load_inf1();">
								<label for="rd1">
									Cantidad OT mantenimiento preventivo
								</label>
							</div>
							<div class="icheck-primary d-inline">
								<input type="radio" id="rd2" name="r1" onclick="load_inf2();">
								<label for="rd2">
									Cantidad OT cargo a cliente
								</label>
							</div>
							<div class="icheck-primary d-inline">
								<input type="radio" id="rd3" name="r1" onclick="load_inf3();">
								<label for="rd3">
									FACTURACION TOTAL Y OT POR TECNICO
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="row" id="inf1" style="display: none; width: 100%;">
					<h4>Cantidad OT mantenimiento preventivo</h4>
					<hr>
					<div class="table-responsive">
						<table class="table example1" id="">
							<thead>
								<tr style="background-color: gray; color: white;">
									<th scope="row">Sede</th>
									<td>Enero</td>
									<td>Febrero</td>
									<td>Marzo</td>
									<td>Abril</td>
									<td>Mayo</td>
									<td>Junio</td>
									<td>Julio</td>
									<td>Agosto</td>
									<td>Septiembre</td>
									<td>Octubre</td>
									<td>Noviembre</td>
									<td>Diciembre</td>
								</tr>
							</thead>
							<tbody>

								<?php foreach ($data_inf1->result() as $key) { ?>
									<tr>
										<th scope="row"><?=$key->Sede?></th>
										<td><?=$key->enero?></td>
										<td><?=$key->febrero?></td>
										<td><?=$key->marzo?></td>
										<td><?=$key->abril?></td>
										<td><?=$key->mayo?></td>
										<td><?=$key->junio?></td>
										<td><?=$key->julio?></td>
										<td><?=$key->agosto?></td>
										<td><?=$key->septiembre?></td>
										<td><?=$key->octubre?></td>
										<td><?=$key->noviembre?></td>
										<td><?=$key->diciembre?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<div id="graf_inf1"  style="height: 370px; width: 100%;"></div>
				</div>

				<div class="row" id="inf2" style="display: none;">
					<h4>Cantidad OT cargo a cliente</h4>
					<hr>
					<div class="table-responsive">
						<table class="table example1">
							<thead>
								<tr style="background-color: gray; color: white;">
									<th scope="row">Sede</th>
									<td>Enero</td>
									<td>Febrero</td>
									<td>Marzo</td>
									<td>Abril</td>
									<td>Mayo</td>
									<td>Junio</td>
									<td>Julio</td>
									<td>Agosto</td>
									<td>Septiembre</td>
									<td>Octubre</td>
									<td>Noviembre</td>
									<td>Diciembre</td>
								</tr>
							</thead>
							<tbody>
								
								<?php foreach ($data_inf2->result() as $key) { ?>
									<tr>
										<th scope="row"><?=$key->Sede?></th>
										<td><?=$key->enero?></td>
										<td><?=$key->febrero?></td>
										<td><?=$key->marzo?></td>
										<td><?=$key->abril?></td>
										<td><?=$key->mayo?></td>
										<td><?=$key->junio?></td>
										<td><?=$key->julio?></td>
										<td><?=$key->agosto?></td>
										<td><?=$key->septiembre?></td>
										<td><?=$key->octubre?></td>
										<td><?=$key->noviembre?></td>
										<td><?=$key->diciembre?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<div id="graf_inf2"  style="height: 370px; width: 100%;"></div>
				</div>
				<div class="row" id="inf3" style="display: none;">
					<h4>FACTURACION TOTAL Y OT POR TECNICO</h4>
					<hr>
					<div class="table-responsive">
						<table class="table example1" style="font-size: 12px;">
							<thead>
								<tr style="background-color: gray; color: white;">
									<th scope="row">Tecnico</th>
									<td>Enero</td>
									<td>Febrero</td>
									<td>Marzo</td>
									<td>Abril</td>
									<td>Mayo</td>
									<td>Junio</td>
									<td>Julio</td>
									<td>Agosto</td>
									<td>Septiembre</td>
									<td>Octubre</td>
									<td>Noviembre</td>
									<td>Diciembre</td>
								</tr>
							</thead>
							<tbody>
								
								<?php foreach ($data_inf31->result() as $key) { ?>
									<tr>
										<th scope="row"><?=$key->tecnico?></th>
										<td>
											<?php 
											$ot = $key->enero;
											$rep = 0;
											$mo = 0;
											foreach ($data_inf32->result() as $key1) {
												if ($key->operario == $key1->operario) { 
													$rep = $key1->enero;
												}
											} ?>
											<?php foreach ($data_inf33->result() as $key2) {
												if ($key->operario == $key2->operario) {
													$mo = $key2->enero;
												}
											} ?>
											<table>
												<tr>
													<th>OT</th>
													<td><?=$ot?></td>	
												</tr>
												<tr>
													<th>REP</th>
													<td><?=$rep?></td>
												</tr>
												<tr>
													<th>MO</th>
													<td><?=$mo?></td>
												</tr>
											</table>
										</td>
										<td>
											<?php 
											$ot = $key->febrero;
											$rep = 0;
											$mo = 0;
											foreach ($data_inf32->result() as $key1) {
												if ($key->operario == $key1->operario) { 
													$rep = $key1->febrero;
												}
											} ?>
											<?php foreach ($data_inf33->result() as $key2) {
												if ($key->operario == $key2->operario) {
													$mo = $key2->febrero;
												}
											} ?>
											<table>
												<tr>
													<th>OT</th>
													<td><?=$ot?></td>	
												</tr>
												<tr>
													<th>REP</th>
													<td><?=$rep?></td>
												</tr>
												<tr>
													<th>MO</th>
													<td><?=$mo?></td>
												</tr>
											</table>
										</td>
										<td>
											<?php 
											$ot = $key->marzo;
											$rep = 0;
											$mo = 0;
											foreach ($data_inf32->result() as $key1) {
												if ($key->operario == $key1->operario) { 
													$rep = $key1->marzo;
												}
											} ?>
											<?php foreach ($data_inf33->result() as $key2) {
												if ($key->operario == $key2->operario) {
													$mo = $key2->marzo;
												}
											} ?>
											<table>
												<tr>
													<th>OT</th>
													<td><?=$ot?></td>	
												</tr>
												<tr>
													<th>REP</th>
													<td><?=$rep?></td>
												</tr>
												<tr>
													<th>MO</th>
													<td><?=$mo?></td>
												</tr>
											</table>
										</td>
										<td>
											<?php 
											$ot = $key->abril;
											$rep = 0;
											$mo = 0;
											foreach ($data_inf32->result() as $key1) {
												if ($key->operario == $key1->operario) { 
													$rep = $key1->abril;
												}
											} ?>
											<?php foreach ($data_inf33->result() as $key2) {
												if ($key->operario == $key2->operario) {
													$mo = $key2->abril;
												}
											} ?>
											<table>
												<tr>
													<th>OT</th>
													<td><?=$ot?></td>	
												</tr>
												<tr>
													<th>REP</th>
													<td><?=$rep?></td>
												</tr>
												<tr>
													<th>MO</th>
													<td><?=$mo?></td>
												</tr>
											</table>
										</td>
										<td>
											<?php 
											$ot = $key->mayo;
											$rep = 0;
											$mo = 0;
											foreach ($data_inf32->result() as $key1) {
												if ($key->operario == $key1->operario) { 
													$rep = $key1->mayo;
												}
											} ?>
											<?php foreach ($data_inf33->result() as $key2) {
												if ($key->operario == $key2->operario) {
													$mo = $key2->mayo;
												}
											} ?>
											<table>
												<tr>
													<th>OT</th>
													<td><?=$ot?></td>	
												</tr>
												<tr>
													<th>REP</th>
													<td><?=$rep?></td>
												</tr>
												<tr>
													<th>MO</th>
													<td><?=$mo?></td>
												</tr>
											</table>
										</td>
										<td>
											<?php 
											$ot = $key->junio;
											$rep = 0;
											$mo = 0;
											foreach ($data_inf32->result() as $key1) {
												if ($key->operario == $key1->operario) { 
													$rep = $key1->junio;
												}
											} ?>
											<?php foreach ($data_inf33->result() as $key2) {
												if ($key->operario == $key2->operario) {
													$mo = $key2->junio;
												}
											} ?>
											<table>
												<tr>
													<th>OT</th>
													<td><?=$ot?></td>	
												</tr>
												<tr>
													<th>REP</th>
													<td><?=$rep?></td>
												</tr>
												<tr>
													<th>MO</th>
													<td><?=$mo?></td>
												</tr>
											</table>
										</td>
										<td>
											<?php 
											$ot = $key->julio;
											$rep = 0;
											$mo = 0;
											foreach ($data_inf32->result() as $key1) {
												if ($key->operario == $key1->operario) { 
													$rep = $key1->julio;
												}
											} ?>
											<?php foreach ($data_inf33->result() as $key2) {
												if ($key->operario == $key2->operario) {
													$mo = $key2->julio;
												}
											} ?>
											<table>
												<tr>
													<th>OT</th>
													<td><?=$ot?></td>	
												</tr>
												<tr>
													<th>REP</th>
													<td><?=$rep?></td>
												</tr>
												<tr>
													<th>MO</th>
													<td><?=$mo?></td>
												</tr>
											</table>
										</td>
										<td>
											<?php 
											$ot = $key->agosto;
											$rep = 0;
											$mo = 0;
											foreach ($data_inf32->result() as $key1) {
												if ($key->operario == $key1->operario) { 
													$rep = $key1->agosto;
												}
											} ?>
											<?php foreach ($data_inf33->result() as $key2) {
												if ($key->operario == $key2->operario) {
													$mo = $key2->agosto;
												}
											} ?>
											<table>
												<tr>
													<th>OT</th>
													<td><?=$ot?></td>	
												</tr>
												<tr>
													<th>REP</th>
													<td><?=$rep?></td>
												</tr>
												<tr>
													<th>MO</th>
													<td><?=$mo?></td>
												</tr>
											</table>
										</td>
										<td>
											<?php 
											$ot = $key->septiembre;
											$rep = 0;
											$mo = 0;
											foreach ($data_inf32->result() as $key1) {
												if ($key->operario == $key1->operario) { 
													$rep = $key1->septiembre;
												}
											} ?>
											<?php foreach ($data_inf33->result() as $key2) {
												if ($key->operario == $key2->operario) {
													$mo = $key2->septiembre;
												}
											} ?>
											<table>
												<tr>
													<th>OT</th>
													<td><?=$ot?></td>	
												</tr>
												<tr>
													<th>REP</th>
													<td><?=$rep?></td>
												</tr>
												<tr>
													<th>MO</th>
													<td><?=$mo?></td>
												</tr>
											</table>
										</td>
										<td>
											<?php 
											$ot = $key->octubre;
											$rep = 0;
											$mo = 0;
											foreach ($data_inf32->result() as $key1) {
												if ($key->operario == $key1->operario) { 
													$rep = $key1->octubre;
												}
											} ?>
											<?php foreach ($data_inf33->result() as $key2) {
												if ($key->operario == $key2->operario) {
													$mo = $key2->octubre;
												}
											} ?>
											<table>
												<tr>
													<th>OT</th>
													<td><?=$ot?></td>	
												</tr>
												<tr>
													<th>REP</th>
													<td><?=$rep?></td>
												</tr>
												<tr>
													<th>MO</th>
													<td><?=$mo?></td>
												</tr>
											</table>
										</td>
										<td>
											<?php 
											$ot = $key->noviembre;
											$rep = 0;
											$mo = 0;
											foreach ($data_inf32->result() as $key1) {
												if ($key->operario == $key1->operario) { 
													$rep = $key1->noviembre;
												}
											} ?>
											<?php foreach ($data_inf33->result() as $key2) {
												if ($key->operario == $key2->operario) {
													$mo = $key2->noviembre;
												}
											} ?>
											<table>
												<tr>
													<th>OT</th>
													<td><?=$ot?></td>	
												</tr>
												<tr>
													<th>REP</th>
													<td><?=$rep?></td>
												</tr>
												<tr>
													<th>MO</th>
													<td><?=$mo?></td>
												</tr>
											</table>
										</td>
										<td>
											<?php 
											$ot = $key->diciembre;
											$rep = 0;
											$mo = 0;
											foreach ($data_inf32->result() as $key1) {
												if ($key->operario == $key1->operario) { 
													$rep = $key1->diciembre;
												}
											} ?>
											<?php foreach ($data_inf33->result() as $key2) {
												if ($key->operario == $key2->operario) {
													$mo = $key2->diciembre;
												}
											} ?>
											<table>
												<tr>
													<th>OT</th>
													<td><?=$ot?></td>	
												</tr>
												<tr>
													<th>REP</th>
													<td><?=$rep?></td>
												</tr>
												<tr>
													<th>MO</th>
													<td><?=$mo?></td>
												</tr>
											</table>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>

<?php $this->load->view('Informe_kpi/footer') ?>

