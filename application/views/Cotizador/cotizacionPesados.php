<?php $this->load->view('Cotizador/header') ?>
<style type="text/css">
    .is-required:after {
        content: '*';
        margin-left: 3px;
        color: red;
        font-weight: bold;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="alert alert-light text-center" role="alert">
            <h4>Cotización para vehículos pesados</h4>
            
            <div class="col" style="align-self: flex-start; text-align: end;">
                <button type="button" class="btn btn-sm bg-red" data-toggle="tooltip" title="Agregar o Crear un Posible Retorno" onclick="FnPosibleRetorno()"><i class="fas fa-plus-square"> POSIBLE RETORNO</i></button>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <div class="loader" id="cargando"></div>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <!--  sm: 540px,
                            md: 720px,
                            lg: 960px,
                            xl: 1140px -->

                            <div class="form-row">
                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-6">
                                    <label class="is-required" for="inputPlaca" style="margin: 0;">Placa</label>
                                    <input type="text" class="form-control form-control-sm" id="inputPlaca" name="inputPlaca" placeholder="Placa" value="" required onchange="loadInfoClient(this.value);">
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-3 col-6">
                                    <label class="" for="inputPrepagado" style="margin: 0;">Prepagado</label>
                                    <input type="text" class="form-control form-control-sm" id="inputPrepagado" name="inputPrepagado" placeholder="Prepagado" value="" disabled>
                                </div>

                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-3 col-6">
                                    <label class="is-required" for="inputDocCliente" style="margin: 0;">Doc. Cliente</label>
                                    <input type="text" class="form-control form-control-sm" id="inputDocCliente" name="inputDocCliente" placeholder="Doc. Cliente" value="" required disabled="false">
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-3 col-6">
                                    <label class="is-required" for="inputPhone" style="margin: 0;">Celular</label>
                                    <input type="text" class="form-control form-control-sm" id="inputPhone" name="inputPhone" placeholder="Celular" value="" required disabled="false">
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                                    <label class="is-required" for="inputName" style="margin: 0;">Nombre</label>
                                    <input type="text" class="form-control form-control-sm" id="inputName" name="inputName" placeholder="Nombre" value="" required disabled="false">
                                </div>

                                <div class="col-xl-3 col-lg-4 col-md-5 col-sm-6 col-12">
                                    <label class="is-required" for="inputMail" style="margin: 0;">Correo</label>
                                    <input type="email" class="form-control form-control-sm" id="inputMail" name="inputMail" placeholder="Correo" value="" required disabled="false" onchange="validarEmail(this.value);">
                                </div>

                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-3 col-6">
                                    <label class="is-required" for="inputClase" style="margin: 0;">Clase</label>
                                    <input type="text" class="form-control form-control-sm" id="inputClase" name="inputClase" placeholder="Clase" value="" required disabled="false">
                                </div>
                                <div class="col-xl-3 col-lg-4 col-md-3 col-sm-4 col-6">
                                    <label class="is-required" for="inputDescripcion" style="margin: 0;">Descripción</label>
                                    <select class="form-control form-control-sm js-example-basic-single-desc" type="text" id="inputDescripcion" name="inputDescripcion" required disabled="false" onchange="loadModelVh(this.value)"><!-- Agregar disabled -->
                                        <option value="">Seleccione una descripción</option>
                                        <?php
                                        foreach ($descripciones->result() as $key) {
                                            echo '<option value="' . $key->clase . '">' . $key->descripcion . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-5 col-sm-5 col-12">
                                    <label class="is-required" for="inputModelo" style="margin: 0;">Modelo</label>
                                    <select class="form-control form-control-sm js-example-basic-single-model" type="text" id="inputModelo" name="inputModelo" required disabled="false"><!-- Agregar disabled -->

                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-6">
                                    <label class="is-required" for="inputYear" style="margin: 0;">Año</label>
                                    <select class="form-control form-control-sm js-example-basic-single-year" type="text" id="inputYear" name="inputYear" required disabled="false"><!-- Agregar disabled -->
                                        <option value="">Año</option>
                                        <?= $year = date('Y');
                                        for ($i = 2016; $i <= $year; $i++) {
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-6">
                                    <label class="" for="inputKmActual" style="margin: 0;">Km actual</label>
                                    <input type="text" class="form-control form-control-sm" id="inputKmActual" name="inputKmActual" placeholder="Km actual" value="" required disabled="false">
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-6">
                                    <label class="" for="inputKmEstimado" style="margin: 0;">Km estimado</label>
                                    <input type="text" class="form-control form-control-sm" id="inputKmEstimado" name="inputKmEstimado" placeholder="Km estimado" value="" required disabled="false">
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-6">
                                    <label class="is-required" for="inputKmCliente" style="margin: 0;">Km cliente</label>
                                    <input type="number" class="form-control form-control-sm" id="inputKmCliente" name="inputKmCliente" placeholder="Km cliente" value="" required disabled="false" onchange="validarKmVh(this.value);">
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-6">
                                    <label class="is-required" for="inputBodega" style="margin: 0;">Bodega</label>
                                    <select class="form-control form-control-sm js-example-basic-single-bodega" id="inputBodega" name="inputBodega" placeholder="Bodega" value="" required disabled="false">
                                        <option value="">Seleccione una sede</option>
                                        <option value="11">Girón Diesel</option>
                                        <option value="19">Barranca</option>
                                        <option value="16">Cúcuta</option>
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-6">
                                    <label class="is-required" for="inputRevision" style="margin: 0;">Revisión</label>
                                    <select class="form-control form-control-sm js-example-basic-single-revision" id="inputRevision" name="inputRevision" placeholder="Revisión" value="" required disabled="false">
                                        <option value="">Seleccione una Revisión</option>
                                    </select>
                                </div>

                            </div>

                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col text-right">
                                <button class="btn btn-primary" type="button" onclick="loadInfoMtto();">Cargar</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="container_table_body" class="row">
                    <div id="tabla_ACDelco" class="col-xl-6 col-lg-6 col-auto table-responsive"></div>
                    <div id="tabla_GM" class="col-xl-6 col-lg-6 col-auto table-responsive"></div>
                </div>
            </div>
            <div class="card-footer">
                <div id="container_table_footer" class="row">
                    <div class="col-4 col-auto">
                        <label class="is-required" for="inputComentarios" style="margin: 0;">Comentarios</label>
                        <textarea class="form-control" placeholder="Escriba aquí los comentarios de la cotización" rows="5" name="inputComentarios" id="inputComentarios" autocomplete=""></textarea>
                    </div>
                    <div class="col-4 col-auto table-responsive">
                        <div class="form-check col radioGrupo" align="center">
                            <input class="form-check-input" type="radio" name="inputRadioGrupo" id="inputRadioGrupo" value="ACDelco">
                            <label for="inputRadioGrupo">ACDelco</label>
                        </div>
                        <table style="width:100%" class="table table-sm table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th colspan="2" class="text-center">Total cotización: ACdelco</th>
                                </tr>
                                <tr>
                                    <th colspan="2">
                                        <span class="text-justify">El total de horas en el taller son: <strong id="HorasACDelco">0</strong> horas</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th style="text-align: right;width:50%;">Subtotal Repuestos</th>
                                    <td style="text-align: right;width:50%;" id="subToReptoAC"></td>
                                </tr>
                                <tr>
                                    <th style="text-align: right;width:50%;">Subtotal Mano Obra</th>
                                    <td style="text-align: right;width:50%;" id="subToManoAC"></td>
                                </tr>
                                <tr style="background-color: #80808063;">
                                    <th style="text-align: right;width:50%;">Total</th>
                                    <td style="text-align: right;width:50%;" id="totalCotizacionAC"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-4 col-auto table-responsive">
                        <div class="form-check col radioGrupo" align="center">
                            <input class="form-check-input" type="radio" name="inputRadioGrupo" id="inputRadioGrupo" value="GM">
                            <label for="inputRadioGrupo">GM</label>
                        </div>
                        <table style="width:100%" class="table table-sm table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th colspan="2" class="text-center">Total cotización: GM</th>
                                </tr>
                                <tr>
                                    <th colspan="2">
                                        <span class="text-justify">El total de horas en el taller son: <strong id="HorasGM">0</strong> horas</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th style="text-align: right;width:50%;">Subtotal Repuestos</th>
                                    <td style="text-align: right;width:50%;" id="subToReptoGM"></td>
                                </tr>
                                <tr>
                                    <th style="text-align: right;width:50%;">Subtotal Mano Obra</th>
                                    <td style="text-align: right;width:50%;" id="subToManoGM"></td>
                                </tr>
                                <tr style="background-color: #80808063;">
                                    <th style="text-align: right;width:50%;">Total</th>
                                    <td style="text-align: right;width:50%;" id="totalCotizacionGM"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="row" id="container_buttons" style="text-align:right;">
                    <!-- <div class="col-12">
                        <button type="button" class="btn btn-warning" onclick="agendarCotizacion(1);">Guardar</button>
                        <button type="button" class="btn btn-success" onclick="agendarCotizacion(2);">Agendar</button>
                    </div> -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<!-- Modal para agregar o crear un posible retorno asociado a la placa -->
<div class="modal fade" id="modalPosibleRetorno" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-lg modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Posible Retorno</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-3">
                        <label for="placaPosibleRetorno">Placa</label>
                        <input class="form-control" type="text" id="placaPosibleRetorno">
                    </div>
                    <div class="col-4">
							<label for="bodegaPosibleRetorno">Bodega</label>
							<select class="form-control js-example-basic-single" name="bodegaPosibleRetorno" id="bodegaPosibleRetorno">
								<option value="">--</option>
								<option value="1">CODIESEL PRINCIPAL</option>
								<option value="6">CHEVYEXPRESS BARRANCA</option>
								<option value="7">CHEVYEXPRESS LA ROSITA</option>
								<option value="8">CODIESEL VILLA DEL ROSARIO</option>
								<option value="9">LAMINA Y PINTURA AUTOMOVILES-GIRON</option>
								<option value="10">ACCESORIZACION </option>
								<option value="11">DIESEL EXPRESS GIRON </option>
								<option value="14">LAMINA Y PINTURA AUTOMOVILES-BOCONO </option>
								<option value="16">BOCONO DIESEL EXPRESS </option>
								<option value="19">DIESEL EXPRESS BARRANCA </option>
								<option value="21">LAMINA Y PINTURA CAMIONES-GIRON</option>
								<option value="22">LAMINA Y PINTURA CAMIONES-BOCONO</option>
							</select>
						</div>
                    <div class="col-4">
                        <label for="tipoPosibleRetorno">Tipo Posible Retorno</label>
                        <select class="form-control js-example-basic-single" name="tipoPosibleRetorno" id="tipoPosibleRetorno">
                            <option value="">Seleccione un tipo de retorno</option>
                            <?php foreach ($tipos_retornos->result() as $tipo_r) {
                                echo '<option value="' . $tipo_r->id_tipo_retorno . '">' . $tipo_r->tipo_retorno . '</option>';
                            }  ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="obsPosibleRetorno">Observación</label>
                        <textarea class="form-control" id="obsPosibleRetorno" placeholder="Escriba aquí la observación realizada por el cliente"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="aggPosibleRetorno();">Agregar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php base_url() ?>js/pesados.js?val=1"></script>
<script>
    const base_url = '<?php echo base_url() ?>';
    document.getElementById('cargando').style.display = 'block';
</script>
<?php $this->load->view('Cotizador/footer') ?>
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