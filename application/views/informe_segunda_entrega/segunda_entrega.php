<?php $this->load->view('administracion/header.php'); ?>
<style>
    #padretabla {
        overflow: scroll;
        height: 500px;
        width: 100%;
    }

    #tabladatos {
        width: 100%;
    }

    thead tr th {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #ffffff;
    }
	.loader {
        position: absolute;
        left: -40%;
        top: -50%;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('<?=base_url()?>media/cargando6.gif') 100% 100% no-repeat;
        opacity: .9;
        display: none;
    }
</style>
<div class="content-wrapper">
    <section class="content">
	<div class="loader" id="cargando"></div>
        <div class="card">
		
            <div class="card-body">
				<!-- Opcion de listar por fecha -->
                
                
                <div class="card">
				<h3 class="col-lg-12 text-center">Informe Segunda Entrega </h3>
				<hr>
				
				<form id="formulariobusqueda" method="post">
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Ingrese la fecha, desde:</label>
								<input class="form-control" type="date" name="f_inicial" id="f_inicial">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Ingrese la fecha, hasta</label>
								<input class="form-control" type="date" name="f_final" id="f_final">
                            </div>
                        </div>
                    </div>
                    <div class="col" align="center">
                        <a href="#" onclick="inf_segunda_entrega();" class="btn btn-primary">Buscar</a>
                    </div>
                </form>
                <hr>
                    <div class="card-header" id="btn-filtro" align="right">
					
                        <a href="#" class="btn btn-success" onclick="bajar_excel();"><i class="far fa-file-excel"></i> Exportar a excel</a>
                    </div>
                    <div class="card-body">
					
                        <div class="table-responsive" id="padretabla">
                            <table class="table table-bordered table-hover" id="tabladatos" style="font-size:14px;width:100%;">
                                <thead class="thead-dark" align="center" id="fjo">
								<tr><td scope="col" colspan="6"><strong>Informe de Segunda Entrega desde: <?= $fecha_inicio?> hasta: <?= $fecha_final ?></strong></td></tr>
                                    <tr>
                                        <th scope="col"><strong>Año</strong></th>
                                        <th scope="col"><strong>Mes</strong></th>
                                        <th scope="col"><strong>Día</strong></th>
                                        <th scope="col"><strong>Entregas</strong></th>
                                        <th scope="col"><strong>Agendas</strong></th>
                                    </tr>
                                </thead>
                                <tbody align="center">
                                    <?php foreach ($datos->result() as $dato) { ?>
                                        <tr>
                                            <td><?= $dato->año ?></td>
                                            <td><?= $dato->mes ?></td>
                                            <td><?= $dato->dia ?></td>
                                            <td><?= $dato->entregas ?></td>
                                            <td><?= $dato->agendas ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
								<thead class="thead-dark" align="center" id="fjo">
									<tr height="20px"></tr>
									<tr><td scope="col" colspan="6"><strong>Informe Detallado de Segunda Entrega desde: <?= $fecha_inicio?> hasta: <?= $fecha_final ?></strong></td></tr>
                                    <tr>
                                        <th scope="col"><strong>Año</strong></th>
                                        <th scope="col"><strong>Mes</strong></th>
                                        <th scope="col"><strong>Día</strong></th>
                                        <th scope="col"><strong>Vehiculo</strong></th>
                                        <th scope="col"><strong>Sede</strong></th>
										<th scope="col"><strong>Agendado por</strong></th>
                                    </tr>
                                </thead>
                                <tbody align="center">
                                    <?php foreach ($detalles->result() as $detalle) { ?>
                                        <tr>
                                            <td><?= $detalle->año ?></td>
                                            <td><?= $detalle->mes ?></td>
                                            <td><?= $detalle->dia ?></td>
                                            <td><?= $detalle->vehiculo ?></td>
                                            <td><?= $detalle->sede ?></td>
											<td><?= $detalle->Agendado_por ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <div id="filtro"></div>
                        </div>
                    </div>
                </div>
            </div>
			
        </div>
    </section>
</div>


<?php $this->load->view('Informe_segunda_entrega/footer.php'); ?>
