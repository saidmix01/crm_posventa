<?php $this->load->view('Retornos/header') ?>
<div class="content-wrapper">
    <section class="content">
        <div class="alert alert-light col-lg-12 text-center" role="alert">
            <h4>INFORME POSIBLES RETORNOS</h4>
        </div>
    </section>
    <!-- Main content -->
    <div class="loader" id="cargando"></div>
    <section class="content">

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-auto">
                        <label for="year">AÑO</label>
                        <input type="text" class="form-control" name="year" id="year" value="<?=Date('Y')?>"/>
                    </div>
                    <div class="col-4" onclick="changeSelectSede();">
                        <label>TECNICO</label>
                        <select id="tecnico" name="tecnico" class="form-control js-example-basic-single">
                            <option value="">Seleccione</option>
                            <?php if($all_tecnicos->num_rows() > 0){
                                foreach ($all_tecnicos->result() as $row){
                                    echo '<option value="'.$row->nit_usuario.'">'.$row->nombres.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-4" onclick="changeSelectTecncio();">
                        <label>SEDE</label>
                        <select id="sede" name="sede" class="form-control js-example-basic-single">
                            <option value="">TODAS</option>
                            <?php 
                                foreach ($getBodegas->result() as $bodega){
                                    echo '<option value="'.$bodega->bodega.'">'.$bodega->descripcion.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-auto p-2" style="align-self: self-end;">
                        <button class="btn btn-success" id="loadGraph" name="loadGraph">GENERAR</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
            </div>
        </div>
    </section>
</div>
<script>
    const baseURL = '<?php echo base_url() ?>';
</script>
<?php $this->load->view('Retornos/footer') ?>
<script src="<?=base_url() ?>js/Retornos/inf_posible_retorno.js"></script>
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