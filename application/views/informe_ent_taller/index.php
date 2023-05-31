<?php $this->load->view('Informe_ent_taller/header'); ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<br>
	<!-- Main content -->

	<section class="content">
		<div class="row container-fluid" align="center"><h4 class="m-0 text-dark" style="text-align: center;">Informe entrada de vehículos Taller</h4></div>
		<form id="form_buscar" method="post" action="<?=base_url()?>Informes/Informe_entrada_vh">
			<div class="row container">
				<div class="col-md-4">
					<select id="mes" name="mes" class="form-control">
						<option value="">Seleccione Algo...</option>
						<option value="1">Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
				</div>
				<div class="col-md-4">
					<select id="anio" name="anio" class="form-control">
						<option value="">Seleccione Algo...</option>
						<option value="2021">2021</option>
						<option value="2022" selected>2022</option>
					</select>
				</div>
				<div class="col-md-2"><button class="btn btn-info btn">Buscar</button></div>
				<div class="col-md-2"><a href="#" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal">Datos en Tabla</a></div>
			</div>
		</form>
		<hr>
		<div class="row">
			<div class="col-md-12" align="center">
				<h3>Informe del Mes <?=$mes?> del año <?=$anio?></h3>
			</div>
		</div>
		<div class="row container-fluid">
			<div class="col-lg-4 col-4">
				<!-- small box -->
				<div class="small-box bg-info">
					<div class="inner">
						<h1><?=round($dat_porcen['porcen_citas_cumplidas'])?>%</h1>

						<p>Porcentaje Citas Cumplidas</p>
					</div>
					<div class="icon">
						<i class="fas fa-chart-line"></i>
					</div>
					<a href="<?=base_url()?>Informes/inf_citas_cumplidas?mes=<?=$mes?>&anio=<?=$anio?>" class="small-box-footer">Mas Información <i class="fas fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<!-- /.col -->
			<div class="col-lg-4 col-4">
				<!-- small box -->
				<div class="small-box bg-primary">
					<div class="inner">
						<div class="row" align="center">
							<div class="col-md-4"><strong>Temprano: </strong><h5><?=round($dat_porcen['temprano'])?> %</h5></div>
							<div class="col-md-4"><strong>A Tiempo: </strong><h5><?=round($dat_porcen['atiempo'])?> %</h5></div>
							<div class="col-md-4"><strong>Llegó Tarde: </strong><h5><?=round($dat_porcen['tarde'])?> %</h5></div>
						</div>

						<p>Cumplimiento de Citas</p>
					</div>
					<div class="icon">
						<i class="far fa-clock"></i>
					</div>
					<a href="<?=base_url()?>Informes/inf_cumplimiento_citas?mes=<?=$mes?>&anio=<?=$anio?>" class="small-box-footer">Mas Información <i class="fas fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<!-- /.col -->
			<div class="col-lg-4 col-4">
				<!-- small box -->
				<div class="small-box bg-warning">
					<div class="inner">
						<h1><?=round($dat_porcen['porcen_vh_agendados'])?>%</h1>

						<p>Porcentaje VH Agendados</p>
					</div>
					<div class="icon">
						<i class="fas fa-car"></i>
					</div>
					<a href="<?=base_url()?>Informes/inf_vh_agendados?mes=<?=$mes?>&anio=<?=$anio?>" class="small-box-footer">Mas Información <i class="fas fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<!-- /.col -->
		</div>
		<div class="row container-fluid">
			<div class="col-md-4">
				<div class="card">
					<div class="card-body" align="center">
						<div id="graf_citas_cumplidas" style="height: 370px; width: 100%;"></div>
						<hr>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body" align="center">
						<div id="graf_estado_citas" style="height: 370px; width: 100%;"></div>
						<hr>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body" align="center">
						<div id="graf_vh_agen" style="height: 370px; width: 100%;"></div>
						<hr>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal Datos en tabla-->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Generar datos en tabla</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       	<form>
       		<div class="form-row">
       			<div class="col">
       				<label>Ingrese el mes</label>
       				<select id="mes1" name="mes1" class="form-control">
						<option value="">Seleccione Algo...</option>
						<option value="1">Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
       			</div>
       			<div class="col">
       				<label>Ingrese el anio</label>
       				<select id="anio1" name="anio1" class="form-control">
						<option value="">Seleccione Algo...</option>
						<option value="2021">2021</option>
						<option value="2022" selected>2022</option>
					</select>
       			</div>
       		</div>
       	</form>
      </div>
    </div>
  </div>
</div>


<?php $this->load->view('Informe_ent_taller/footer'); ?>