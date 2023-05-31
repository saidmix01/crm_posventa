<?php $this->load->view('listahorasExtras/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section>
        <?php
        setlocale(LC_ALL, "es_ES");
        $fecha =  date("d") . " del " . date("M") . " de " . date("Y"); ?>
        <div class="card">
            <div class="container">
                <div class="label alert col-lg-12 text-center lead">Listado de Tiempo suplementario para la fecha <?= $fecha ?></div>
            </div>
        </div>
        <div class="card">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-center">
                    <br>

                    <?php foreach ($extras->result() as $key) :
                        $color = "";
                        $estado = $key->autorizacion;

                        if ($estado == 'Aprobado') {
                            $color = 'background-color:#D0F9A3;';
                        } elseif ($estado == "Pendiente") {
                            $color = 'background-color:#1BAFEF;';
                        }
                    ?>

                        <style>
                            .noticia:hover {
                                -webkit-animation: tiembla 1s linear;
                            }

                            @-webkit-keyframes tiembla {
                                0% {
                                    -webkit-transform: rotateZ(-5deg);
                                }

                                50% {
                                    -webkit-transform: rotateZ(0deg) scale(.8);
                                }

                                100% {
                                    -webkit-transform: rotateZ(5deg);
                                }
                            }
                        </style>

                        <div class="card shadow p-2 m-2" style="width:20rem; <?= $color ?>">
                            <div class="card-body" style="<?= $color ?>;">
                                <ul class="list-group list-group-flush">
                                    <li style="<?= $color ?>" class="card-title col-lg-12 text-center list-group-item"><strong>Tiempo Suplementario </strong></li>
                                </ul>
                                <p class="card-text" style="font-family:Times New Roman, Times, serif;">
                                    Inicia el <?= $key->fechainical ?> a las <?= $key->hora_ini ?> y termina a las <?= $key->hora_fin ?>
                                </p>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li style="font-size:14px;<?= $color ?>" class="list-group-item"><strong>Autorizado a:</strong> <br> <?= $key->nombreempleado ?></li>
                                <li style="font-size:14px;<?= $color ?>" class="list-group-item"><strong>Autorizado por:</strong> <br> <?= $key->nombrejefe ?></li>
                                <li style="font-size:14px;<?= $color ?>" class="list-group-item"><strong>Sede:</strong> <br> <?= $key->sede ?></li>
                                <li style="<?= $color ?>" class="list-group-item text-dark"><strong> <?= $key->autorizacion ?> </strong></li>

                            </ul>
                            <div class="card-body">
                                <?php echo " <button onclick='confirmar(this.id)'  id='' name='dato' class='card-link text-dark shadow btn btn-outline-success bg-white font-weight-bold'>Confirmar</button>"; ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('ListahorasExtras/footer') ?>