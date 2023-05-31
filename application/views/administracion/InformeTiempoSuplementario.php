<?php $this->load->view('administracion/header') ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<br>
	<!-- Main content -->
	<section class="content">
		<div class="card">
			<label class="card-title col-lg-12 text-center">Seccion de Filtros</label>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<form id="datos" name="datos">
							<div class="form-row">
								<div class="col-lg-3">
									<label>Seleccione el mes</label>
									<!--  <input type="date" class="form-control" name="FechaExtraUno" id="FechaExtraUno"> -->
									<input type="month" class="form-control" name="FechaMes" id="FechaMes">
								</div>
								<div class="col-3 d-none">
									<input value="<?php echo $this->session->userdata('user'); ?>" type="text" class="form-control" name="userrExtra" id="userrExtra">
								</div>
								<div class="col-lg-3">
									<label>Seleccione una sede</label>
									<select class="form-control" id="extraSede" name="extraSede">
										<option class="d-none" value="">Selecciones una sede</option>
										<option value="Giron">Girón</option>
										<option value="Rosita">Rosita</option>
										<option value="Chevropartes">Chevropartes</option>
										<option value="Solochevrolet">Solochevrolet</option>
										<option value="Barrancabermeja">Barrancabermeja</option>
										<option value="Bocono">Boconó</option>
										<option value="Malecon">Malecón</option>
									</select>
								</div>
								<div class="col-lg-3">
									<label>Seleccione una área</label>
									<select class="form-control" name="extraAreas" id="extraAreas">
										<option class="d-none" value="">Selecciones un Area</option>
										<option value="Administración">Administración</option>
										<option value="Central de Beneficios">Central de Beneficios</option>
										<option value="Vehiculos Nuevos">Vehiculos Nuevos</option>
										<option value="Vehiculos Usados">Vehiculos Usados</option>
										<option value="Repuestos">Repuestos</option>
										<option value="Taller Gasolina">Taller Gasolina</option>
										<option value="Taller Diesel">Taller Diesel</option>
										<option value="Lamina y Pintura">Lamina y Pintura</option>
										<option value="Alistamiento">Alistamiento</option>
										<option value="Contac Center">Contac Center</option>
										<option value="Accesorios">Accesorios</option>

									</select>
								</div>
								<div class="col-lg-3">
									<label for="Select2">Empleado:</label>
									<select class="form-control js-example-basic-single" style="width:100%" name="empleado" id="empleado" onclick="">';
									<option class="d-none" value="">Seleccione un empleado</option>
										<?php foreach ($empleados->result() as $key) {
											echo '<option value="' . $key->nit . '">' . $key->nombres . '</option>';
										}
										?>

									</select>
								</div>
							</div>
							<hr>
							<div class="form-row">

								<div class="col-lg-3">
									<button style=" text-shadow: 5px 3px 4px #000000" type="button" class="btn btn-info " id="Extras"> Buscar &nbsp; <i class="fas fa-search"></i></button>
									<button onclick="window.location.reload();" style=" text-shadow: 5px 3px 4px #000000" type="button" class="btn btn-success " id=""> General &nbsp; <i class="fas fa-sync"></i></button>
								</div>

							</div>

					</div>

					</form>
					<!--<button type="submit" id="refrescar" name="refrescar" value="63369607" style=" text-shadow: 5px 3px 4px #000000" class=" btn btn-success shadow">Ver Listado General</button>-->
				</div>


			</div>
		</div>

		</style>
		<div class="col-lg-12" id="respuestaextra"></div>
		<div class="card table-responsive" id="tablaExtra">
			<div class="card-body">
				<table id='infomeExtras' class='table table-sm table-responsive-sm table-bordered tabla-hover' style='font-size: 12px;'>
					<label class='col-lg-12 text-center lead'>Listado de Solicitudes labores en tiempo suplementario</label>
					<a style=" text-shadow: 5px 3px 4px #000000" href="#" id="infExtras" onclick="infExtras()" class=" btn btn-success shadow">Descargar &nbsp; <i class="far fa-file-excel"></i></a>
					<thead class='table-dark'>


						<tr>
							<th style='white-space: nowrap; width: 1px;' scope='col'>Nombre del Jefe</th>
							<th class="text-center  " style='white-space: nowrap; width: 1px;' scope='col'>Nombre del Empleado</th>
							<th class="text-center" style='white-space: nowrap; width: 1px;' scope='col'>Sede</th>
							<th class="text-center" style='white-space: nowrap; width: 1px;' scope='col'>Area</th>
							<th class="text-center" style='white-space: nowrap; width: 1px;' scope='col'>Cargo</th>
							<th class="text-center" style='white-space: nowrap; width: 1px;' scope='col'>Fecha de Inicio</th>
							<th class="text-center" style='white-space: nowrap; width: 1px;' scope='col'>Hora de Inicio</th>
							<th class="text-center" style='white-space: nowrap; width: 1px;' scope='col'>Hora de salida</th>
							<th class="text-center" style='white-space: nowrap; width: 1px;' scope='col'>Fecha de Solicitud</th>
							<th style='white-space: nowrap; width: 1px;' scope='col'>Descripción</th>
							<th class="text-center" style='white-space: nowrap; width: 1px;' scope='col'>Autorizacion</th>

						</tr>
					</thead>

					<tbody>
						<?php
						$color = "";
						foreach ($datos->result() as $key) {

							if ($key->autorizacion == 'Aprobado') {
								$color = "#82E0AA";
							} else {
								$color = "#E4320B";
							}
							echo "<tr>
                                <td class='text-left'style='font-weight:normal; white-space: nowrap; width: 1px;'>" . $key->nombrejefe . "</td>
                                <td class='text-left'style='font-weight:normal; white-space: nowrap; width: 1px;'>" . $key->nombreempleado . "</td>
                                <td class='text-center'>" . $key->sede . "</td>
                                <td class='text-center'>" . $key->area . "</td>
                                <td class='text-center'>" . $key->cargo . "</td>
                                <td class='text-center'>" . $key->fecha_ini . "</td>
                                <td class='text-center'>" . $key->hora_ini . "</td>
                                <td class='text-center'>" . $key->hora_fin . "</td>
                                <td class='text-center'>" . $key->fecha_solicitud . "</td>
                                <td class='text-left'style='font-weight:normal; white-space: nowrap; width: 1px;'>" . $key->descripcion . "</td>
                                <td class='text-center text-white' style = 'background-color:" . $color . "'><strong> " . $key->autorizacion . " </strong></td>
                                
                             </tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>

		<!-----------pintar tabla con datos foltrados-->
		<div id="filtroExtra"></div>



	</section>
	<!-- /.content -->
</div>

<?php $this->load->view('administracion/footer') ?>
