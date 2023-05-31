<?php $this->load->view('tecnicos/header') ?>
<div class="content-wrapper">
    <section class="content">
        <div class="alert alert-light col-lg-12 text-center" role="alert">
            <h4>PRODUCTIVIDAD TECNICOS</h4>
        </div>
    </section>
    <!-- Main content -->
    <div class="loader" id="cargando"></div>
    <section class="content">

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-auto">
                        <label for="inputDate">Fecha:</label>
                        <input value="<?= date('Y-m') ?>" id="inputDate" name="inputDate" type="month" class="form-control" max="<?= date('Y-m') ?>">
                    </div>
                    <div class="col-auto">
                        <label for="selectPatio">PATIO:</label>
                        <select name="selectPatio" multiple="multiple" id="selectPatio" class="form-control js-example-basic-single">
                            <option value=""></option>
                            <option value="1">GIRÓN GASOLINA</option>
                            <option value="2">GIRÓN COLISIÓN</option>
                            <option value="3">GIRÓN DIESEL</option>
                            <option value="4">ROSITA</option>
                            <option value="5">BARRANCA GASOLINA</option>
                            <option value="6">BARRANCA DIESEL</option>
                            <option value="7">VILLA GASOLINA</option>
                            <option value="8">VILLA DIESEL</option>
                            <option value="9">VILLA COLISIÓN</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button id="btn-generar-inf" name="btn-generar-inf" class="btn btn-primary">GENERAR</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                <div class="col-12">
                        <h2 class="text-center title">MES ACTUAL</h2>
                    </div>
                    <div class="col-12 table-responsive">
                        <table id="tableData1" class="table table-bordered table-hover">
                            <!-- /* nit	nombres	patio	horas_cliente	horas_garantia	horas_servicio	horas_interno	total_horas	horas_disp */ -->
                            <thead>
                                <tr>
                                    <th class="text-center" style="vertical-align: middle;">NIT</th>
                                    <th class="text-center" style="vertical-align: middle;">NOMBRE</th>
                                    <th class="text-center" style="vertical-align: middle;">PATIO</th>
                                    <th class="text-center" style="vertical-align: middle;">HORAS CLIENTE</th>
                                    <th class="text-center" style="vertical-align: middle;">HORAS GARANTIA</th>
                                    <th class="text-center" style="vertical-align: middle;">HORAS SERVICIO</th>
                                    <th class="text-center" style="vertical-align: middle;">HORAS INTERNAS</th>
                                    <th class="text-center" style="vertical-align: middle;">TOTAL HORAS</th>
                                    <th class="text-center" style="vertical-align: middle;">HORAS DISPONIBLES</th>
                                    <th class="text-center" style="vertical-align: middle;">PRODUCTIVIDAD</th>
                                </tr>
                            </thead>
                            <tbody id="tBodyTable1">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12">
                        <h2 class="text-center title">CONSOLIDADO</h2>
                    </div>
                    <div class="col-12 table-responsive">
                        <table id="tableData2" class="table table-bordered table-hover">
                            <!-- /* nit	nombres	patio	horas_cliente	horas_garantia	horas_servicio	horas_interno	total_horas	horas_disp */ -->
                            <thead>
                                <tr>
                                    <th class="text-center" style="vertical-align: middle;">NIT</th>
                                    <th class="text-center" style="vertical-align: middle;">NOMBRE</th>
                                    <th class="text-center" style="vertical-align: middle;">PATIO</th>
                                    <th class="text-center" style="vertical-align: middle;">HORAS CLIENTE</th>
                                    <th class="text-center" style="vertical-align: middle;">HORAS GARANTIA</th>
                                    <th class="text-center" style="vertical-align: middle;">HORAS SERVICIO</th>
                                    <th class="text-center" style="vertical-align: middle;">HORAS INTERNAS</th>
                                    <th class="text-center" style="vertical-align: middle;">TOTAL HORAS</th>
                                    <th class="text-center" style="vertical-align: middle;">HORAS DISPONIBLES</th>
                                    <th class="text-center" style="vertical-align: middle;">PRODUCTIVIDAD</th>
                                </tr>
                            </thead>
                            <tbody id="tBodyTable2">

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
    const dateActual = '<?php echo date('Y-m') ?>';
</script>
<?php $this->load->view('footerPrincipal') ?>
<script src="<?php base_url() ?>js/informes/productividad_tecnicos.js"></script>
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