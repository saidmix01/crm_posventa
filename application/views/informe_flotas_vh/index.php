<?php $this->load->view('Informe_flotas_vh/header.php'); ?>

<style>
    /* #padretabla {
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
    } */
    .loader {
		position: fixed;
		left: 100px;
		top: 0px;
		width: 100%;
		max-width: 100%;
		height: 100%;
		z-index: 999999;
		background: url('<?=base_url()?>media/cargando7.gif') 50% 50% no-repeat;
		opacity: .9;
		display: none;
	}
</style>
<div class="loader" id="cargando"></div>
<div class="content-wrapper">
    <section class="content">
        <div class="card">
            <div class="card-body">
                <h3 class="col-lg-12 text-center">Informe Flotas</h3>
                <hr>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalFlotas" onclick="obtenerComboAsesor();">Crear/Asignar Flota</button>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalVehiculosFlotas">Desvincular Vehículo Flota</button>
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalVehiculosFlotasTotal">Vehículos por Flota</button>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalContactosFlotas">Agregar Contactos Flota</button>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalFlotasAprob" onclick="obtenerFlotasAprobar();">Flotas por aprobación</button>
                <a class="btn btn-success" href="<?=base_url()?>Flotas/flotas_actualizadas" role="button" target="_blank">Flotas Actualizadas</a>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalFlotasAprobadas" onclick="obtenerFlotasAprobadas();">Flotas Aprobadas</button>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalFlotasRechazadas" onclick="obtenerFlotasRechazadas();">Flotas Rechazadas</button>
                <hr>
                <div class="table-responsive" id="padretabla">
                    <table class="table table-bordered table-hover" id="tabladatos" style="font-size:14px;width:100%;">
                        <thead class="thead-dark" align="center">
                            <tr>
                                <th scope="col">Cliente</th>
                                <th scope="col">Vehículos</th>
                                <th scope="col">Asesores</th>
                                <th scope="col">Trabaja Codiesel</th>
                            </tr>
                        </thead>
                        <tbody align="center">
                            <?php
                                $count=0;
                                foreach ($flotas->result() as $key) { 
                                $count++; ?>
                                <tr id="<?= $key->nit_cliente ?>">
                                    <td class="dt-clase" style="cursor: pointer;"><?= $key->cliente ?></td>
                                    <td><?= $key->vehiculos ?></td>
                                    <td><?= $key->asesores ?></td>
                                    <td><?= $key->trabaja_codiesel ?></td>
                                </tr>                                
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>


<?php $this->load->view('Informe_flotas_vh/footer.php'); ?>