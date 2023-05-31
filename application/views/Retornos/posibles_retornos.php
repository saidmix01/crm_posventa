<?php $this->load->view('Retornos/header') ?>
<div class="content-wrapper">
    <section class="content">
        <div class="alert alert-light col-lg-12 text-center" role="alert">
            <h4>POSIBLES RETORNOS</h4>
        </div>
    </section>
    <!-- Main content -->
    <div class="loader" id="cargando"></div>
    <section class="content">

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                        <label for="filtroNumero">N° ORDEN</label>
                        <input type="number" id="filtroNumero" name="filtroNumero" placeholder="N° de Orden" class="form-control">
                    </div>
                    <?php 
                        if ($getBodegas->num_rows() > 0) {
                    ?>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                        <label for="filtroBodega">BODEGA</label>
                        <select id="filtroBodega" name="filtroBodega" class="form-control">
                            <option value="-1">TODAS</option>
                            <?php 
                                foreach ($getBodegas->result() as $bodega){
                                    echo '<option value="'.$bodega->bodega.'">'.$bodega->descripcion.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <?php } ?><!-- Cierre de If si existen Bodegas -->
                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                        <label for="filtroPlaca">PLACA</label>
                        <input type="text" id="filtroPlaca" name="filtroPlaca" placeholder="PLACA" class="form-control">
                    </div>
                    <div class="col p-2" style="align-self: self-end;">
                        <button class="btn btn-success" name="buscarTabla" id="buscarTabla" onclick="filtro();">BUSCAR</button>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="tableContent">
                            <table class="table table-striped" id="tableDataRetorno">
                                <!-- placa	des_modelo	origen -->

                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">ORDEN</th>
                                        <th class="text-center">PLACA</th>
                                        <th class="text-center">DESC MODELO</th>
                                        <th class="text-center">ORIGEN</th>
                                        <th class="text-center">BODEGA</th>
                                        <th class="text-center">ESTADO</th>
                                        <!-- <th>REPETICIONES</th> -->
                                        <th class="text-center">DETALLE</th>
                                    </tr>
                                </thead>

                                <tbody id="body_tableDataRetorno">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">

            </div>
        </div>
    </section>
</div>

<!-- Detalle de Retornos By Placa -->
<div id="ModalDetalleRetorno" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="ModalDetalleRetorno" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">

        <div class="modal-content">
            <div class="modal-header">
                <div class="row text-center" style="margin: auto;">
                    <!-- placa	des_modelo	cliente	cant_retornos -->
                    <div class="col-sm-2 col-6">
                        <label for="placa">Placa:</label>
                        <input readonly id="placa" type="text" class="form-control">
                    </div>
                    <div class="col-sm-4 col-6">
                        <label for="des_modelo">Desc Modelo:</label>
                        <input readonly id="des_modelo" type="text" class="form-control">
                    </div>
                    <div class="col-sm-4 col-6 ">
                        <label for="cliente">Cliente:</label>
                        <input readonly id="cliente" type="text" class="form-control">
                    </div>
                    <div class="col-sm-2 col-6">
                        <label for="cant_retornos">N° Retornos</label>
                        <input readonly id="cant_retornos" type="text" class="form-control">
                        <input type="hidden" name="posibleR_orden" id="posibleR_orden">
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped" id="DetalleRetornoOrdenes">
                            <thead>
                                <!-- rnk	placa	numero	solicitud	respuesta -->
                                <tr>
                                    <th class="text-center" scope="col" colspan="5">CLIENTE DICE</th>
                                </tr>
                                <tr>
                                    <th class="text-center" scope="col">RNK</th>
                                    <th class="text-center" scope="col">PLACA</th>
                                    <th class="text-center" scope="col">N° ORDEN</th>
                                    <th class="text-center" scope="col">SOLICITUD</th>
                                    <th class="text-center" scope="col">RESPUESTA</th>
                                </tr>
                            </thead>
                            <tbody id="BodyDetalleRetornoOrdenes">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped" id="DetalleRetornoTecnicos">
                            <thead>
                                <tr>
                                    <th class="text-center" scope="col" colspan="4">TECNICOS</th>
                                </tr>
                                <tr>
                                    <th class="text-center" scope="col">RNK</th>
                                    <th class="text-center" scope="col">PLACA</th>
                                    <th class="text-center" scope="col">N° ORDEN</th>
                                    <th class="text-center" scope="col">TECNICO</th>
                                </tr>
                            </thead>
                            <tbody id="BodyDetalleRetornoTecnicos">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalGestionRetorno">Gestionar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Detalle de Gestionar Retorno By Placa -->
<div id="ModalGestionRetorno" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="ModalDetalleRetorno" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">

        <div class="modal-content">
            <div class="modal-header">

            </div>
            <div class="modal-body">
                <div class="row m-2" style="align-items: end;">
                    <div class="col">
                        <label>Definición Retorno</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="def_retorno_Si" value="1" onclick="definicionRetorno(this.value);">
                            <label class="form-check-label" for="inlineRadio1">SI</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="def_retorno_No" value="0" onclick="definicionRetorno(this.value);">
                            <label class="form-check-label" for="inlineRadio2">NO</label>
                        </div>
                    </div>
                </div>
                <div class="row m-2" style="align-items: end;">
                    <div id="selectRazonDiv" class="col-6">
                        <label>RAZON RETORNO</label>
                        <select id="selectRazon" name="selectRazon" class="form-control js-example-basic-single">
                            <option value="">Seleccione un opción</option>
                            <?php
                            foreach ($getRazonRetorno->result() as $row) {
                                if($row->definicion > 0){
                                    echo '<option value="' . $row->id_razon . '">' . $row->razon . '</option>';
                                }                                
                            }
                            ?>
                        </select>
                    </div>
                    <div id="selectRazonDivNo" class="col-6" style="display: none">
                        <label>RAZON RETORNO</label>
                        <select id="selectRazonNo" name="selectRazonNo" class="form-control js-example-basic-single">
                            <option value="">Seleccione un opción</option>
                            <?php
                            foreach ($getRazonRetorno->result() as $row) {
                                if($row->definicion < 1){
                                    echo '<option value="' . $row->id_razon . '">' . $row->razon . '</option>';
                                }                                
                            }
                            ?>
                        </select>
                    </div>
                    <div id="obs_razon_div" class="col-6">
                        <textarea id="obs_razon" name="obs_razon" class="form-control" placeholder="Escriba aquí las observación de la razon del retorno"></textarea>
                    </div>
                </div>
                <div class="row m-2" style="align-items: end;">
                    <div id="select_sist_inv_div" class="col">
                        <label>SISTEMA INTERVENIDO</label>
                        <select id="select_sist_inv" name="select_sist_inv" class="form-control js-example-basic-single">
                            <option value="">Seleccione un opción</option>
                            <?php foreach ($getSistemaIvn->result() as $row) {
                                echo '<option value="' . $row->id_sistema_inv . '">' . $row->sistema_inv . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div id="obs_sist_inv_div" class="col">
                        <textarea id="obs_sist_inv" name="obs_sist_inv" class="form-control" placeholder="Escriba aquí las observaciones para el sistema de inventario."></textarea>
                    </div>
                </div>
                <div class="row m-2" style="align-items: end;">
                    <div id="datosOrden" class="col">
                        <label>ORDEN</label>
                        <select class="form-control js-example-basic-single-g" name="ordenR" id="ordenR">

                        </select>
                    </div>
                    <div id="datosTecnico" class="col">
                        <label>TECNICO</label>
                        <select class="form-control js-example-basic-single-g" name="tecnicoR" id="tecnicoR">

                        </select>
                    </div>
                </div>
                <div class="row m-2" style="align-items: end;">
                    <div class="col">
                        <label>PLAN DE ACCIÓN</label>
                        <select id="selectPlan" name="selectPlan" class="form-control js-example-basic-single">
                            <option value="">Seleccione un opción</option>
                            <?php foreach ($getPlanAccion->result() as $row) {
                                echo '<option value="' . $row->id_plan . '">' . $row->plan_accion . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <textarea id="obs_plan" name="obs_plan" class="form-control" placeholder="Escriba aquí las observaciones del plan de acción"></textarea>
                    </div>
                </div>
                <div class="row m-2" style="align-items: end;">
                    <div class="col-12">
                        <label>COSTOS</label>
                        <div class="row m-1">
                            <div class="col-6">
                                <label>REPUESTOS</label>
                            </div>
                            <div class="col-6">
                                <input id="precio_costo_1" onkeydown="formatDeleteDots(event);" type="number" class="form-control" placeholder="Escriba aqui el precio de los Repuestos.">
                            </div>
                        </div>
                        <div class="row m-1">
                            <div class="col-6">
                                <label>MANO DE OBRA</label>
                            </div>
                            <div class="col-6">
                                <input id="precio_costo_2" onkeydown="formatDeleteDots(event);" type="number" class="form-control" placeholder="Escriba aqui el precio de la Mano de obra.">
                            </div>
                        </div>
                        <div class="row m-1">
                            <div class="col-6">
                                <label>TOT</label>
                            </div>
                            <div class="col-6">
                                <input id="precio_costo_3" onkeydown="formatDeleteDots(event);" type="number" class="form-control" placeholder="Escriba aqui el precio de TOT.">
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <textarea id="obs_costos" name="obs_plan" class="form-control" placeholder="Escriba aqui las observaciones de los costos"></textarea>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="gestionarRetorno();">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar las posibles soluciones -->
<div id="ModalSolucionRetorno" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="ModalSolucionRetorno" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">

        <div class="modal-content">
            <div class="modal-header">
                <div class="alert alert-light col-lg-12 text-center" role="alert">
                    <h4>SOLUCIONES A POSIBLES RETORNOS</h4>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center" style="vertical-align: middle;">ORDEN ORIGEN</th>
                                    <th class="text-center" style="vertical-align: middle;">DEFINICION</th>
                                    <th class="text-center" style="vertical-align: middle;">RAZÓN RETORNO</th>
                                    <th class="text-center" style="vertical-align: middle;">OBSERVACIÓN</th>
                                    <th class="text-center" style="vertical-align: middle;">SISTEMA INTERVENIDO</th>
                                    <th class="text-center" style="vertical-align: middle;">OBSERVACION</th>
                                    <th class="text-center" style="vertical-align: middle;">PLAN DE ACCIÓN</th>
                                    <th class="text-center" style="vertical-align: middle;">OBSERVACIÓN</th>
                                    <th class="text-center" style="vertical-align: middle;">REPUESTOS</th>
                                    <th class="text-center" style="vertical-align: middle;">MANO DE OBRA</th>
                                    <th class="text-center" style="vertical-align: middle;">TOT</th>
                                    <th class="text-center" style="vertical-align: middle;">OBSERVACIÓN</th>
                                    <th class="text-center" style="vertical-align: middle;">TECNICO</th>
                                    <th class="text-center" style="vertical-align: middle;">ORDEN RETORNO</th>
                                    <th class="text-center" style="vertical-align: middle;">FECHA</th>
                                    <th class="text-center" style="vertical-align: middle;">USUARIO</th>
                                </tr>
                            </thead>
                            <tbody id="tbodySoluciones">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<script>
    const baseURL = '<?php echo base_url() ?>';
</script>
<?php $this->load->view('Retornos/footer') ?>
<script src="<?php base_url() ?>js/Retornos/funciones_posibles_retornos.js"></script>
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