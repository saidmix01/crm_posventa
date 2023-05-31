<?php $this->load->view('Informe_lista_flotas/header.php'); ?>

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
                <h3 class="col-lg-12 text-center">Informe Flotas Ingresadas</h3>
                <hr>
                <div class="table-responsive" id="padretabla">
                    <table class="table table-bordered table-hover" id="tabladatos" style="font-size:14px;width:100%;">
                        <thead class="thead-dark" align="center">
                            <tr>
                                <th scope="col">Flota</th>
                                <th scope="col">Cant. Vehículos</th>
                            </tr>
                        </thead>
                        <tbody align="center">
                            <?php
                                $count=0;
                                foreach ($flotas->result() as $key) { 
                                $count++; ?>
                                <tr id="<?= $key->nombre_flota ?>">
                                    <td style="width:80%;cursor: pointer;" class="dt-clase"><?= $key->nombre_flota ?></td>
                                    <td style="width:20%;"><?= $key->cant_vh ?></td>
                                </tr>                                
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>


<?php $this->load->view('Informe_lista_flotas/footer.php'); ?>