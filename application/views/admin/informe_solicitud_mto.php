<!-- ******************************************************* VISTA SOLICITUD DE MANTENIMIENTO PARA EL PERFIL DE JEFE ****************************************************** -->
<!--
	Autor: Sergio Galvis
	Fecha: 20/04/2022
	Vista para el perfil de Mantenimiento en el Home
 -->
<div class="card">
	<div class="card-header">
		<h3>Resumen solicitudes de mantenimiento correctivo</h3>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-sm-4">
				<div class="info-box">
					<span style="background-color: #0064FFDE ;" class="info-box-icon elevation-1"><i class="fa fa-minus-square"></i></span>

					<div class="info-box-content">
						<span class="info-box-text">Solicitudes Pendientes</span>
						<span class="info-box-number">
							<?= $pendientes ?>
						</span>
					</div>
					<!-- /.info-box-content -->
				</div>
				<!-- /.info-box -->
			</div>
			<!-- /.col -->
			<div class="col-sm-4">
				<div class="info-box mb-3">
					<span style="background-color: #ffc107 ;" class="info-box-icon elevation-1"><i class="fa fa-play"></i></i></span>

					<div class="info-box-content">
						<span class="info-box-text">Solicitudes en Proceso</span>

						<span class="info-box-number"><?= $proceso ?></span>
					</div>
					<!-- /.info-box-content -->
				</div>
				<!-- /.info-box -->
			</div>
			<!-- /.col -->
			<!-- /.col -->
			<div class="col-sm-4">
				<div class="info-box mb-3">
					<span style="background-color: #009C13C7 ;" class="info-box-icon elevation-1"><i class="fa fa-check"></i></span>

					<div class="info-box-content">
						<span class="info-box-text">Solicitudes Finalizadas</span>
						<span class="info-box-number"><?= $finalizadas ?></span>
					</div>
					<!-- /.info-box-content -->
				</div>
				<!-- /.info-box -->
			</div>
		</div>
	</div>
</div>
<div class="card">
	<div class="card-header">
		<h3>Resumen solicitudes de mantenimiento preventivo <?=date('Y-m-d'); ?></h3>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-sm-4">
				<div class="info-box">
					<span style="background-color: #0064FFDE ;" class="info-box-icon elevation-1"><i class="fa fa-minus-square"></i></span>

					<div class="info-box-content">
						<span class="info-box-text">Solicitudes Pendientes</span>
						<span class="info-box-number">
							<?= $pendientesPre ?>
						</span>
					</div>
					<!-- /.info-box-content -->
				</div>
				<!-- /.info-box -->
			</div>
			<!-- /.col -->
			<div class="col-sm-4">
				<div class="info-box mb-3">
					<span style="background-color: #ffc107 ;" class="info-box-icon elevation-1"><i class="fa fa-play"></i></i></span>

					<div class="info-box-content">
						<span class="info-box-text">Solicitudes en Proceso</span>

						<span class="info-box-number"><?= $procesoPre ?></span>
					</div>
					<!-- /.info-box-content -->
				</div>
				<!-- /.info-box -->
			</div>
			<!-- /.col -->
			<!-- /.col -->
			<div class="col-sm-4">
				<div class="info-box mb-3">
					<span style="background-color: #009C13C7 ;" class="info-box-icon elevation-1"><i class="fa fa-check"></i></span>

					<div class="info-box-content">
						<span class="info-box-text">Solicitudes Finalizadas</span>
						<span class="info-box-number"><?= $finalizadasPre ?></span>
					</div>
					<!-- /.info-box-content -->
				</div>
				<!-- /.info-box -->
			</div>
		</div>
	</div>
</div>
