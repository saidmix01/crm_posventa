<?php $this->load->view('taller/header_encuesta') ?>
<style type="text/css">
    .is-required:after {
        content: '*';
        margin-left: 3px;
        color: red;
        font-weight: bold;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #aaa !important;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="alert alert-light text-center" role="alert">
            <h4>GENERAR ORDEN DE SALIDA</h4>
        </div>
    </section>
    <!-- Main content -->
    <div class="loader" id="cargando"></div>
    <section class="content">
        <div class="card" id="card-padre">
            <div class="card-header">
                <div class="row">
                    <div class="col-auto">
                        <label class="is-required" for="inputPlacaOrden">DIGITE LA PLACA DE SU VEHÍCULO:</label>
                        <input inputmode="text" oninput="this.value = this.value.toUpperCase();" type="text" id="inputPlacaOrden" name="inputPlacaOrden" placeholder="PLACA" class="form-control">
                    </div>
                    <div class="col-auto" style="align-self: self-end;">
                        <button class="btn btn-primary" type="button" id="btnBuscarOrdenesByPlaca" name="btnBuscarOrdenesByPlaca"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="card" id="isYourVh" style="display: none;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="">INFORMACIÓN DEL VEHIÍCULO</label>
                                <div class="col-12">
                                    <div class="alert alert-light" role="alert" id="alertInfoVh">

                                    </div>
                                    <p class="card-text">Si la información del vehículo es correcta realice click en continuar, de lo contrario digite nuevamente la placa.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-auto">
                                <button type="button" id="btnisYourVh" name="btnisYourVh" class="btn btn-primary btn-sm">CONTINUAR</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" id="validarPropietario" style="display: none;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="is-required">¿ES PROPIETARIO DEL VEHÍCULO?</label>
                                <div class="form-group clearfix">
                                    <div class="icheck-success d-inline">
                                        <input type="radio" name="isOwner" id="isOwnerYes" value="1">
                                        <label for="isOwnerYes">
                                            SI
                                        </label>
                                    </div>
                                    <div class="icheck-danger d-inline">
                                        <input type="radio" name="isOwner" id="isOwnerNo" value="0">
                                        <label for="isOwnerNo">
                                            NO
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-auto">
                                <button type="button" id="btnIsOwner" name="btnIsOwner" class="btn btn-primary btn-sm">CONTINUAR</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" id="updateInfo" style="display: none;">
                    <div class="card-header">
                        <h5 class="card-header">ACTUALIZAR DATOS PERSONALES</h5>
                        <!-- <div class="alert alert-warning" role="alert">
                            This is a warning alert—check it out!
                        </div> -->
                    </div>


                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <label for="fieldName">NOMBRE</label>
                                <input readonly type="text" id="fieldName" name="fieldName" class="form-control">
                            </div>
                            <div class="col-sm-6 col-12">
                                <label class="is-required" for="fieldMailUpdate">EMAIL</label>
                                <input type="text" id="fieldMailUpdate" name="fieldMailUpdate" class="form-control">
                            </div>
                            <div class="col-sm-6 col-12">
                                <label class="is-required" for="fieldPhoneUpdate">CELULAR</label>
                                <input type="text" id="fieldPhoneUpdate" name="fieldPhoneUpdate" class="form-control">

                                <input type="hidden" id="fieldNit" name="fieldNit" class="form-control">
                                <input type="hidden" id="fieldMail" name="fieldMail" class="form-control">
                                <input type="hidden" id="fieldPhone" name="fieldPhone" class="form-control">
                                <input type="hidden" id="fieldBodega" name="fieldBodega" class="form-control">
                                <input type="hidden" id="fieldModelo" name="fieldModelo" class="form-control">
                                <input type="hidden" id="fieldOrden" name="fieldOrden" class="form-control">
                                <input type="hidden" id="fieldPlaca" name="fieldPlaca" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-auto">
                                <button type="button" id="btnUpdateInfo" name="btnUpdateInfo" class="btn btn-primary">ACTUALIZAR</button>
                            </div>
                            <div class="col-auto">
                                <button type="button" id="btnContinueInfo" name="btnContinueInfo" class="btn btn-warning">CONTINUAR</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" id="card-encuesta" style="display: none;">
                    <div class="card-body">
                        <h2 class="text-center">Encuesta de satisfacción CODIESEL S.A</h2>
                        <p class="text-center" style="font-style: oblique;font-size: 12px;">A continuación elija el estado que represente su nivel de satisfacción con el servicio</p>
                        <div class="container">
                            <form id="form_encuesta" name="form_encuesta">
                                <?php foreach ($preguntas->result() as $key) { ?>
                                    <?php if ($key->id != 2 && $key->id != 3) { ?>
                                        <div class="form-row m-1 p-1 rounded border border-info" style="align-items: center;">

                                            <div class="col-sm-6 col-12">
                                                <label for="pregunta_encuesta"><?= $key->pregunta ?>:</label>
                                            </div>

                                            <div class="col-sm-6 col-12" align="center">
                                                <?php if ($key->tipo == "1-10" && $key->id == '1') { ?>
                                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                        <label class="btn btn-outline-danger btn-lg">
                                                            <input type="radio" name="pregunta<?= $key->id ?>" id="option<?= $key->id ?>" autocomplete="off" value="6" style="font-size: 20px;"> <span style="font-size: 10px;">0-6</span> <i class="far fa-frown"></i>
                                                        </label>
                                                        <label class="btn btn-outline-warning btn-lg">
                                                            <input type="radio" name="pregunta<?= $key->id ?>" id="option<?= $key->id ?>" autocomplete="off" value="8"> <span style="font-size: 10px;">7-8</span> <i class="far fa-meh"></i>
                                                        </label>
                                                        <label class="btn btn-outline-success btn-lg">
                                                            <input type="radio" name="pregunta<?= $key->id ?>" id="option<?= $key->id ?>" autocomplete="off" value="10"> <span style="font-size: 10px;">9-10</span> <i class="far fa-smile"></i>
                                                        </label>
                                                    </div>

                                                <?php } elseif ($key->tipo == "sn") { ?>
                                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                        <label class="btn btn-outline-secondary btn-lg">
                                                            <input type="radio" name="pregunta<?= $key->id ?>" id="option<?= $key->id ?>" autocomplete="off" value="NO"> <i class="far fa-thumbs-down"></i>
                                                        </label>
                                                        <label class="btn btn-outline-primary btn-lg">
                                                            <input type="radio" name="pregunta<?= $key->id ?>" id="option<?= $key->id ?>" autocomplete="off" value="SI"> <i class="far fa-thumbs-up"></i>
                                                        </label>

                                                    </div>
                                                <?php } elseif ($key->tipo == "op") { ?>
                                                    <td>
                                                        <textarea rows="5" id="option_<?= $key->id ?>" name="pregunta<?= $key->id ?>" placeholder="Escriba aquí su opinión acerca del servicio prestado" class="form-control form-control-sm"></textarea>
                                                    </td>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <hr>
                                <div class="form-row">
                                    <div class="col">
                                        <div align="center">
                                            <button type="button" id="btn_env_encuesta" class="btn btn-warning btn-lg">Enviar Respuestas</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>



<script src="<?php echo base_url() ?>js/taller/encuesta_orden_salida.js"></script>
<script>
    const base_url = '<?php echo base_url() ?>';
</script>
<?php $this->load->view('taller/footer_encuesta') ?>
<script>
    $(document).ready(function() {
        $("#buscar_items").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#menu_items li").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>