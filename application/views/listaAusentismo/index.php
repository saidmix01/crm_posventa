<?php $this->load->view('ListaAusentismo/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section>
        <?php $fecha =  date("d") . " del " . date("m") . " de " . date("Y"); ?>
        <div class="card">
            <div class="container">
                <div class="label alert col-lg-12 text-center lead">Listado de Ausuentismo para la fecha <?= $fecha ?></div>
            </div>
        </div>
        <div class="card">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-center">
                    <br>

                    <?php foreach ($ausentismos->result() as $key) {
                        $color = "";
                        $estado = $key->Estado;

                        if ($estado == 'Autorizado') {
                            $color = "style='background-color:#7AC665;'";
                        } elseif ($estado == "Pendiente") {
                            $color = "style='background-color:#1BAFEF;'";
                        }
                    ?>
    
                        <div class="card shadow p-2 m-2 tarjeta" style="width:20rem;">
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="card-title col-lg-12 text-center list-group-item"><strong>Ausentismo</strong></li>
                                </ul>
                                <p class="card-text" style="font-family:Times New Roman, Times, serif;">
                                    Inicia en la fecha <?= $key->fechainicial ?> a las <?= $key->hora_ini ?> y regresa el dia <?= $key->fechafinal ?> a las <?= $key->hora_fin ?>
                                </p>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li style="font-size:14px;" class="list-group-item"><strong>Nombre Jefe:</strong> <br> <?= ucfirst($key->nombres); ?></li>
                                <li style="font-size:14px;" class="list-group-item"><strong>Nombre Empleado:</strong> <br><?= ucfirst($key->nombre_jefe) ?></li>
                                <li <?= $color ?> class="list-group-item text-white"><?= $key->Estado ?></li>

                            </ul>
                            <div class="card-body">
                                <?php echo " <button onclick='enviarconfirmacion(this.id)' id='$key->id_ausen' name='dato' class='card-link btn btn-info shadow'>Confirmar</button>"; ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('ListaAusentismo/footer') ?>