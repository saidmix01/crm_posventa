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
                <h3 class="col-lg-12 text-center">Informe Flotas Actualizadas</h3>
                <hr>
                <div class="table-responsive" id="flotasActualizadas">
                    <table class="table table-bordered table-hover" id="tabladatos" style="font-size:14px;width:100%;">
                        <thead class="thead-dark" align="center">
                            <tr>
                                <th scope="col">Nit Flota</th>
                                <th scope="col">Flota</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Placa</th>
                                <th scope="col">Asesor</th>
                                <th scope="col">Comisiona</th>
                                <th scope="col">Observación Comisión</th>
                                <th scope="col">Acción</th>
                            </tr>
                        </thead>
                        <tbody align="center">
                            <?php
                                $count=0;
                                foreach ($flotas->result() as $key) {
                                $count++;

                                $asesor = $this->usuarios->getUserByNit($key->asesor); ?>
                                <tr id="<?= $key->nit ?>">
                                    <td><?= $key->nit ?></td>
                                    <td><?= $key->nombre_flota ?></td>
                                    <td><?= $key->cliente ?></td>
                                    <td><?= $key->placa ?></td>
                                    <td><?= $asesor->nombres ?></td>
                                    <td><input type="checkbox" id="com_check_<?= $key->id_flota ?>"></td>
                                    <td><textarea style="width: 130px;" class="form-control" id="com_obs_<?= $key->id_flota ?>" rows="1"></textarea></td>
                                    <td><button type="button" class="btn btn-primary" onclick="fijarComision(0, <?= $key->id_flota ?>);">Fijar Comisión</button></td>
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