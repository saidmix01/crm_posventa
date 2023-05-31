<?php $this->load->view('headerPrincipal') ?>
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
            <h4>GENERAR ORDENES DE SALIDA</h4>
        </div>
    </section>
    <!-- Main content -->
    <div class="loader" id="cargando"></div>
    <section class="content">
        <div class="card" id="card-padre">
            <div class="card-header">
                <div class="row">
                    <div class="col-auto">
                        <label for="inputPlacaOrden">PLACA:</label>
                        <input inputmode="text" oninput="this.value = this.value.toUpperCase();" type="text" id="inputPlacaOrden" name="inputPlacaOrden" placeholder="PLACA" class="form-control">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary" type="button" id="btnBuscarOrdenesByPlaca" name="btnBuscarOrdenesByPlaca"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-auto table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table_ordenes_by_placa">
                            <thead>
                                <tr>
                                    <th class="text-center">NÚMERO DE ORDEN</th>
                                    <th class="text-center">BODEGA</th>
                                    <th class="text-center">PLACA</th>
                                    <th class="text-center">DESCRIPCION MODELO</th>
                                    <th class="text-center">FECHA</th>
                                    <th class="text-center">OPCIÓN</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_table_ordenes_by_placa">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>



<script src="<?php base_url() ?>js/taller/crear_orden_salida.js"></script>
<script>
    const base_url = '<?php echo base_url() ?>';
</script>
<?php $this->load->view('footerPrincipal') ?>
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