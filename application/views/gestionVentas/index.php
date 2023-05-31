<?php $this->load->view('gestionVentas/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<br>

	<!-- Main content -->
	<section class="content">
		<div class="card">
			<div class="card-body">
				<div><label class="col-lg-12 text-center lead">Gestion de Ventas</label></div>
				<hr>
				<div class="card">
					<div class="card-body">
						<div class="form">
							<div class="form-row justify-content-center">
								<div class="custom-control custom-radio custom-control-inline m-3">
									<input type="radio" id="Vehiculos" name="customRadioInline1" class="custom-control-input">
									<label class="custom-control-label" for="Vehiculos">Vehiculos</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline m-3">
									<input type="radio" id="F&I" name="customRadioInline1" class="custom-control-input">
									<label class="custom-control-label" for="F&I">F&I</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline m-3">
									<input type="radio" id="NPS" name="customRadioInline1" class="custom-control-input">
									<label class="custom-control-label" for="NPS">Ventas NPS</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline m-3">
									<input type="radio" id="MPC" name="customRadioInline1" class="custom-control-input">
									<label class="custom-control-label" for="MPC">Ventas MPC</label>
								</div>
							</div>
							<div class="form-row justify-content-center">
								<div class="form-group col-lg-4">
									<select class="form-control" id="exampleFormControlSelect1">
										<option>2021</option>
									</select>
								</div>
								<div class="form-group col-lg-4">
									<select class="form-control" id="exampleFormControlSelect1">
										<option>Giron</option>
									</select>
								</div>

							</div>
						</div>
					</div>
				</div>

				<hr>
				<!-- inicio de la tbla para visalizar campos de la base de datos-->
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead class='thead-dark'>
							<tr>
								<th scope="col">Asesor</th>
								<th scope="col">Enero</th>
								<th scope="col">Febrero</th>
								<th scope="col">Marzo</th>
								<th scope="col">Abril</th>
								<th scope="col">Mayo</th>
								<th scope="col">Junio</th>
								<th scope="col">Julio</th>
								<th scope="col">Agosto</th>
								<th scope="col">Septiembre</th>
								<th scope="col">Octubre</th>
								<th scope="col">Noviembre</th>
								<th scope="col">Diciembre</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Juan Pantaja Diaz</td>
								<td>Otto</td>
								<td>@mdo</td>
								<td>Mark</td>
								<td>Otto</td>
								<td>@mdo</td>
								<td>Mark</td>
								<td>Otto</td>
								<td>@mdo</td>
								<td>Mark</td>
								<td>Otto</td>
								<td>@mdo</td>
								<td>@mdo</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<th class='bg-light' colspan="1">Valor Total</th>
								<td class='text-center bg-light' colspan="12">$10,00</td>
							</tr>
						</tfoot>
					</table>

				</div>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>
<?php $this->load->view('gestionVentas/footer') ?>