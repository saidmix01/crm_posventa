<?php
$this->load->view('Informe_taller/header');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <br>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12" align="center">
                <h4>Ingreso de Vehículos</h4>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9" id="cambiosBuscar">
                <div class="accordion" id="accordionExample">
                    <div class="card"> <!--  Vehículos con cita programada -->
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Vehículos con cita programada
                                </button>
                            </h2>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">
                                <div id="res">
                                    <?php foreach ($citas as $key) {
                                        $color = "";
                                        $fondo = "";
                                        $img = "";
                                        if ($key['bod'] == 1 || $key['bod'] == 8) {
                                            $color = "card-primary";
                                            $img = "silueta-vhs.png";
                                            $fondo = "bg-primary";
                                        } elseif ($key['bod'] == 11 || $key['bod'] == 16) {
                                            $color = "card-success";
                                            $img = "silueta-camion.png";
                                            $fondo = "bg-success";
                                        } elseif ($key['bod'] == 9 || $key['bod'] == 21 || $key['bod'] == 14 || $key['bod'] == 22) {
                                            $color = "card-danger";
                                            $img = "silueta-vh-colision.png";
                                            $fondo = "bg-warning";
                                        }
										
                                    ?>

                                        <div class="card card-outline <?= $color ?>">
                                            <div class="card-body">

                                                <div class="row">
                                                    <div class="col-md-2 <?= $fondo ?>">
                                                        <div class="row">
                                                            <div class="col-md-12" style="display: table; height: 200px;">
                                                                <div class="contenedor" style="display: table-cell; vertical-align: middle;">
                                                                    <img src="<?= base_url() ?>media/<?= $img ?>" style="width: 90%;" />
                                                                    <div class="centrado"><?= $key['placa'] ?></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <blockquote class="quote-secondary">
                                                            <div class="table-responsive">
                                                                <table class="table table-sm" align="left" style="font-size: 13px;">
                                                                    <tr>
                                                                        <th>Cliente:</th>
                                                                        <td><?= $key['cliente'] ?></td>
                                                                        <th>Encargado:</th>
                                                                        <td><?= $key['encargado'] ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Vehículo:</th>
                                                                        <td><?= $key['vehiculo'] ?></td>
                                                                        <th>Fecha/Hora cita:</th>
                                                                        <td><?= $key['fecha_cita'] ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Bahia/Tecnico:</th>
                                                                        <td><?= $key['bahia'] ?></td>
                                                                        <th>Notas:</th>
                                                                        <td><?= $key['notas'] ?></td>
                                                                    </tr>
                                                                </table>
                                                            </div>

                                                            <div class="row" align="center">
																					<div class="col-md-12"><a href="#" onclick="marcar_entrada(<?= $key['id_cita']?>,'<?=$key['fecha_cita_v'] ?>');" class="btn btn-sm btn-info">Marcar entrada</a></div>
                                                            </div>
                                                        </blockquote>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card"> <!-- Vehículos sin orden de trabajo -->
                        <div class="card-header" id="headingThree"> 
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Vehículos sin orden de trabajo
                                </button>
                            </h2>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                            <div class="card-body">
                                <?php foreach ($citas_sin_ot as $key) { ?>
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="row">
                                                        <div class="col-md-12" style="display: table; height: 200px;">
                                                            <div class="contenedor" style="display: table-cell; vertical-align: middle;">
                                                                <img src="<?= base_url() ?>media/silueta-vhs.png" style="width: 90%;" />
                                                                <div class="centrado"><?= $key['placa'] ?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-10">
                                                    <blockquote class="quote-secondary">
                                                        <div class="table-responsive">
                                                            <table class="table table-sm" align="left" style="font-size: 13px;">
                                                                <tr>
                                                                    <th>Cliente:</th>
                                                                    <td><?= $key['cliente'] ?></td>
                                                                    <th>Encargado:</th>
                                                                    <td><?= $key['encargado'] ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Vehículo:</th>
                                                                    <td><?= $key['vh'] ?></td>
                                                                    <th>Fecha/Hora cita:</th>
                                                                    <td><?= $key['fecha'] ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Bahia/Tecnico:</th>
                                                                    <td><?= $key['bahia'] ?></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </blockquote>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card"><!--  Vehiculos con cita atendida -->
                    <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Vehiculos con cita atendida
                            </button>
                        </h2>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            <?php foreach ($citas_atendidas as $key) { ?>
                                <div class="card">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-md-12" style="display: table; height: 200px;">
                                                        <div class="contenedor" style="display: table-cell; vertical-align: middle;">
                                                            <img src="<?= base_url() ?>media/silueta-vhs.png" style="width: 90%;" />
                                                            <div class="centrado"><?= $key['placa'] ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <blockquote class="quote-secondary">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm" align="left" style="font-size: 13px;">
                                                            <tr>
                                                                <th>Cliente:</th>
                                                                <td><?= $key['cliente'] ?></td>
                                                                <th>Encargado:</th>
                                                                <td><?= $key['encargado'] ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Vehículo:</th>
                                                                <td><?= $key['vehiculo'] ?></td>
                                                                <th>Fecha/Hora cita:</th>
                                                                <td><?= $key['fecha_cita'] ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Bahia/Tecnico:</th>
                                                                <td><?= $key['bahia'] ?></td>
                                                                <th>Notas:</th>
                                                                <td><?= $key['notas'] ?></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </blockquote>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="card">  <!-- Vehiculos sin cita -->
                    <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#vh_sin_cita" aria-expanded="false" aria-controls="vh_sin_cita">
                                Vehiculos sin cita
                            </button>
                        </h2>
                    </div>
                    <div id="vh_sin_cita" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            <?php foreach ($vh_sin_cita->result() as $key) { ?>
                                <div class="card">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-md-12" style="display: table; height: 200px;">
                                                        <div class="contenedor" style="display: table-cell; vertical-align: middle;">
                                                            <img src="<?= base_url() ?>media/silueta-vhs.png" style="width: 90%;" />
                                                            <div class="centrado"><?= $key->placa ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <blockquote class="quote-secondary">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm" align="left" style="font-size: 13px;">
                                                            <tr>
                                                                <th>Cliente:</th>
                                                                <td><?= $key->nombre_cliente ?></td>
                                                                <th>Placa:</th>
                                                                <td><?= $key->placa ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Fecha/Hora cita:</th>
                                                                <td><?= $key->fecha_cita ?></td>
                                                                <th>Motivo:</th>
                                                                <td><?= $key->motivo_visita ?></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </blockquote>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>


            </div>
            <div class="col-md-3" style="height: 100%;">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <label>Ingrese la fecha para buscar</label>
                                    <div class="col-md-9">
                                        <input type="date" name="fecha_b" id="fecha_b" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <a href="#" class="btn btn-success" onclick="buscar_fecha();"><i class="fas fa-search"></i></a>
                                    </div>
                                </div>

                                <div class="row">
                                    <label>Ingrese la placa para buscar</label>
                                    <div class="col-md-9">
                                        <input type="text" name="placa" id="placa" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <a href="#" class="btn btn-success" onclick="buscar_placa();"><i class="fas fa-search"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <label>Ingresar Vehículo sin cita</label>
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary w-100" data-toggle="modal" data-target="#modalIngresoVehSinCita">Ingresar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>
    <div class="modal fade" id="modalIngresoVehSinCita" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ingreso Vehículo sin Cita</h5>
                    <button id="btnCloseModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="placa">Placa del Vehiculo:</label>
                                <input type="text" class="form-control" id="placaVeh">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="bod">Taller al que se dirige</label>
                                <select id="bod" class="form-control">
                                    <option value="">Seleccione una bodega</option>
                                    <?php foreach ($sedes->result() as $key) { ?>
                                        <option value="<?= $key->idsede ?>"><?= $key->descripcion ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nombreCliente">Nombre del Cliente::</label>
                                <input type="text" class="form-control" id="nombreCliente">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="motivoVisita">Motivo de la Visita:</label>
                                <textarea class="form-control" id="motivoVisita" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="guardarVehSinCita();">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
<?php
$this->load->view('Informe_taller/footer');
?>
