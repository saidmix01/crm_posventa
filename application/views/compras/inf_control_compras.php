<?php $this->load->view('Retornos/header') ?>
<div class="content-wrapper">
    <section class="content">
        <div class="alert alert-light col-lg-12 text-center" role="alert">
            <h4>CONTROL COMPRAS</h4>
        </div>
    </section>
    <!-- Main content -->
    <div class="loader" id="cargando"></div>
    <section class="content">

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-auto">
                        <label for="inputOrden">N° Orden:</label>
                        <input id="inputOrden" name="inputOrden" type="number" class="form-control" placeholder="Número de Orden">
                    </div>
                    <div class="col-auto">
                        <button disabled id="btn-generar-inf" name="btn-generar-inf" class="btn btn-primary">GENERAR</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table id="tableData" class="table table-bordered table-hover">
                        <!-- numero	fecha	codigo	descripcion	cantidad	valor_unitario	valor_total	calificacion_abc	ultima_compra	ultima_venta	Giron	Chevropartes	Barranca	Rosita	Villa	Solochevrolet -->
                            <thead>
                                <tr>
                                    <th class="text-center" style="vertical-align: middle;">CÓDIGO</th>
                                    <th class="text-center" style="vertical-align: middle;">DESCRIPCIÓN</th>
                                    <th class="text-center" style="vertical-align: middle;">CANTIDAD</th>
                                    <th class="text-center" style="vertical-align: middle;">VALOR UNITARIO</th>
                                    <th class="text-center" style="vertical-align: middle;">VALOR TOTAL</th>
                                    <th class="text-center" style="vertical-align: middle;">CALIFICACIÓN</th>
                                    <th class="text-center" style="vertical-align: middle;">ULTIMA COMPRA</th>
                                    <th class="text-center" style="vertical-align: middle;">ULTIMA VENTA</th>
                                    <th class="text-center" style="vertical-align: middle;">GIRÓN</th>
                                    <th class="text-center" style="vertical-align: middle;">CHEVROPARTES</th>
                                    <th class="text-center" style="vertical-align: middle;">BARRANCA</th>
                                    <th class="text-center" style="vertical-align: middle;">ROSITA</th>
                                    <th class="text-center" style="vertical-align: middle;">VILLA DEL ROSARIO</th>
                                    <th class="text-center" style="vertical-align: middle;">SOLOCHEVROLET</th>
                                </tr>
                            </thead>
                            <tbody id="tBodyTable">

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
<?php $this->load->view('footerPrincipal') ?>
<script src="<?php base_url() ?>js/compras/inf_control_compras.js"></script>
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