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
            <h4>SALIDA DE VEHÍCULOS</h4>
        </div>
    </section>
    <!-- Main content -->
    <div class="loader" id="cargando"></div>
    <section class="content">
        <div class="card" id="card-padre">
            <div class="card-header">
                <div class="row">
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary" onclick="load_data();">REFRESCAR</button>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <div class="row" id="contenedor_vh">
                    
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>



<script src="<?php echo base_url() ?>js/taller/salida_vh_porteria.js"></script>
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