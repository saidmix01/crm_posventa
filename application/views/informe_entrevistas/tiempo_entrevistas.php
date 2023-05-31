<?php $this->load->view('Retornos/header') ?>
<div class="content-wrapper">
    <section class="content">
        <div class="alert alert-light col-lg-12 text-center" role="alert">
            <h4>TIEMPOS ENTREVISTAS CONSULTIVA</h4>
        </div>
    </section>
    <!-- Main content -->
    <div class="loader" id="cargando"></div>
    <section class="content">

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-4">
                        <label for="startDate">FECHA INICIAL</label>
                        <input value="<?=date('Y-m-01')?>" type="date" class="form-control" id="startDate" name="startDate">
                    </div>
                    <div class="col-4">
                        <label for="endDate">FECHA FINAL</label>
                        <input value="<?=date('Y-m-d')?>" type="date" class="form-control" id="endDate" name="endDate">
                    </div>
                    <div class="col-4">
                        <button class="btn btn-primary" id="btnGenerar">Generar</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="tablePrincipal" name="tablePrincipal">
                            <thead>
                                <tr>
                                    <!-- bodega	registros_citas	citas_marcadas	citas_no_marcadas	citas_cumplidas	citas_no_cumplidas	no_asistieron	ot_abiertas	tiempo_entrevista_consultiva -->
                                    <th>BODEGA</th>
                                    <th>REGISTRO CITAS</th>
                                    <th>CITAS MARCADAS</th>
                                    <th>CITAS NO MARCADAS</th>
                                    <th>CITAS CUMPLIDAS</th>
                                    <th>CITAS NO CUMPLIDAS</th>
                                    <th>NO ASISTIERON</th>
                                    <th>OT ABIERTAS</th>
                                    <th>TIEMPO ENTREVISTAS</th>
                                </tr>
                            </thead>
                            <tbody id="tBodytablePrincipal" name="tBodytablePrincipal">

                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row" id="tableDetail" name="tableDetail">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="tableSecundaria" name="tableSecundaria">
                                <thead>
                                    <tr>
                                        <!-- id_cita	placa	fecha_cita	bodega	hora_llegada	numero_orden_taller	hora_orden	tiempo_orden-->
                                        <th>ID CITA</th>
                                        <th>PLACA</th>
                                        <th>FECHA CITA</th>
                                        <th>BODEGA</th>
                                        <th>HORA LLEGADA</th>
                                        <th>N° ORDEN</th>
                                        <th>HORA ORDEN</th>
                                        <th>TIEMPO ORDEN</th>
                                    </tr>
                                </thead>
                                <tbody id="tBodytableSecundaria" name="tBodytableSecundaria">

                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
        </div>
    </section>
</div>
<script>
    const baseURL = '<?php echo base_url() ?>';
</script>
<?php $this->load->view('informe_entrevistas/footer') ?>
<script src="<?php base_url() ?>js/informes/tiempo_entrevistas.js"></script>
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