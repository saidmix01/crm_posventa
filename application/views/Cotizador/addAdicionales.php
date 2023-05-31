<?php $this->load->view('Cotizador/header') ?>
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
            <h4>Gestión Cotizador</h4>
        </div>
    </section>
    <!-- Main content -->
    <div class="loader" id="cargando"></div>
    <section class="content">
        <div class="card" id="card-padre">
            <div class="card-header">
                <div class="row">
                    <div class="col-12">
                        <nav>
                            <div class="nav nav-tabs nav-pills mb-3" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link" id="btnCreateAdicional" name="btnCreateAdicional" data-toggle="tab" role="tab" aria-selected="true">CREAR ADICIONALES</a>
                                <a class="nav-item nav-link" id="btnAddAdicional" name="btnAddAdicional" data-toggle="tab" role="tab" aria-selected="false">CARGAR ADICIONALES</a>
                                <a class="nav-item nav-link" id="btnListAdicional" name="btnListAdicional" data-toggle="tab" role="tab" aria-selected="false">LISTA ADICIONALES</a>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- CREAR ADICIONALES -->
                <div class="card" id="formCreateAdicional" name="formCreateAdicional" style="display:none">
                    <div class="card-header">
                        <h5 class="card-title">Formulario para crear adicionales de mantenimiento al cotizador de livianos</h5>
                    </div>
                    <!-- oninput="this.value = this.value.toUpperCase()" -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-auto">
                                <label for="inputCreateAdicional">NOMBRE NUEVO ADICIONAL:</label>
                                <input oninput="this.value = this.value.toUpperCase()" type="text" class="form-control" id="inputCreateAdicional" name="inputCreateAdicional">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-auto table-responsive">
                                <table id="tableListAdicionalesCreate" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr><th class="text-center" colspan="2">ADICIONALES DISPONIBLES</th></tr>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">ADICIONAL</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableListAdicionalesBodyCreate">
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-auto">
                                <button id="btnCreateAdicionalDB" type="button" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Crear adicional">CREAR</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- AGREGAR ADICIONALES -->
                <div class="card" id="formAddAdicional" name="formAddAdicional" style="display:none">
                    <div class="card-header">
                        <h5 class="card-title">Formulario para agregar adicionales de mantenimiento al cotizador de livianos y pesados</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-auto pb-2">
                                <label for="inputNameAdicional">ADICIONAL</label>
                                <select type="text" class="form-control select-adicional-disp" id="inputNameAdicional" name="inputNameAdicional" placeholder="Nombre de Adicional">

                                </select>
                            </div>
                            <div class="col-8">
                                <label for="selectClases">CLASES</label>
                                <button id="btnAllClass" name="btnAllClass" type="button" class="btn btn-info btn-sm" aria-label="Seleccionar todas las clases">
                                    TODAS
                                </button>
                                <button id="btnAllClassClear" name="btnAllClassClear" type="button" class="btn btn-warning btn-sm" aria-label="Eliminar todas las clases">
                                    LIMPIAR
                                </button>
                                <select multiple class="form-control js-example-basic-single" id="selectClases" name="selectClases" size="1">
                                    <?php
                                    foreach ($selectClases->result() as $row) {
                                        echo '<option value="' . $row->clase . '">' . $row->descripcion . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table id="table_respuestos" class="table table-striped table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center" colspan="4">REPUESTOS <button id="btnAddFilaRepto" name="btnAddFilaRepto" class="btn btn-warning"><i class="fas fa-plus-circle"></i></button></th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">CÓDIGO</th>
                                            <th class="text-center">DESCRIPCIÓN</th>
                                            <th class="text-center">CANTIDAD</th>
                                            <th class="text-center">OPCIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tBodyRepuestos" name="tBodyRepuestos">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table id="table_mano_obra" class="table table-striped table-hover table-bordered">
                                    <thead>
                                        <!-- id	clase	operacion	tiempo	valor_menos_5anos	valor_mas_5anos	adicional -->
                                        <tr>
                                            <th class="text-center" colspan="5">MANO DE OBRA <button id="btnAddFilaManoObra" name="btnAddFilaManoObra" class="btn btn-warning"><i class="fas fa-plus-circle"></i></button></th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">OPERACIÓN</th>
                                            <th class="text-center">TIEMPO</th>
                                            <th class="text-center">VALOR MENOS 5 AÑOS</th>
                                            <th class="text-center">VALOR MAYOR 5 AÑOS</th>
                                            <th class="text-center">OPCIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tBody_mano_obra" name="tBody_mano_obra">
                                        <tr id="fila_mano_obra0" class="fila_mano" num_fila="0">
                                            <td><textarea id="textOperacionMano0" class="form-control bloquear-paste" placeholder="Escriba aquí la descripción de la operación" maxlength="300" rows="1" onKeyPress="evitarCarateres()" oninput="this.value = this.value.toUpperCase()"></textarea></td>
                                            <td><input type="number" id="cantHora_0" class="form-control" min="0" step="0.25" style="width: 100%; display: inline;"></td>
                                            <td><input type="number" id="mas5_0" class="form-control" onkeydown="noPuntoComa()" style="width: 100%; display: inline; text-align: right;"></td>
                                            <td id="v_hora_0" class="valor_total_hora"><input type="number" id="menos50" class="form-control" onkeydown="noPuntoComa()" style="width: 100%; display: inline; text-align: right;"></td>
                                            <td><button type="button" id="delete_mano_0" class="btn btn-danger" onclick="eliminarItemManoObra(0)">Eliminar</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-auto">
                                <button id="btnSaveAdicional" type="button" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Guardar adicional">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- LISTAR ADICIONALES -->
                <div class="card" id="tableListAdicionales" name="tableListAdicionales" style="display:none">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-auto">
                                <label for="selectAdicional">ADICIONALES</label>
                                <select id="selectAdicional" name="selectAdicional" class="form-control js-example-basic-single-list-ad">

                                </select>
                            </div>
                            <div class="col-8">
                                <label for="selectClasesList">CLASES</label>
                                <button id="btnAllClassClearList" name="btnAllClassClearList" type="button" class="btn btn-warning btn-sm" aria-label="Eliminar todas las clases">
                                    LIMPIAR
                                </button>
                                <select multiple class="form-control js-example-basic-single-clases-list" id="selectClasesList" name="selectClasesList" size="1">
                                    <?php
                                    foreach ($selectClases->result() as $row) {
                                        echo '<option value="' . $row->clase . '">' . $row->descripcion . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-auto m-1">

                            </div>
                            <div class="col-auto" style="align-self: flex-end;">
                                <button type="button" class="btn btn-success" id="btnBuscarAdicional" name="btnBuscarAdicional">BUSCAR</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 table-responsive m-1">
                                <table class="table table-striped table-bordered table-hover" id="table_reptos_list">
                                    <thead>
                                        <!-- seq	clase	codigo	descripcion	cantidad	clase_operacion	adicional -->
                                        <tr>
                                            <th class="text-center" colspan="5">REPUESTOS</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">CLASE</th>
                                            <th class="text-center">CODIGO</th>
                                            <th class="text-center">DESCRIPCIÓN</th>
                                            <th class="text-center">CANTIDAD</th>
                                            <th class="text-center">ADICIONAL</th>
                                            <th class="text-center">OPCIÓN</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-12 table-responsive m-1">
                                <table class="table table-striped table-bordered table-hover" id="table_mano_list">
                                    <!-- id	clase	operacion	tiempo	valor_menos_5anos	valor_mas_5anos	adicional -->
                                    <thead>
                                        <tr>
                                            <th class="text-center" colspan="6">MANO DE OBRA</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">CLASE</th>
                                            <th class="text-center">OPERACIÓN</th>
                                            <th class="text-center">TIEMPO</th>
                                            <th class="text-center">VALOR MAS 5 AÑOS</th>
                                            <th class="text-center">VALOR MENOS 5 AÑOS</th>
                                            <th class="text-center">ADICIONAL</th>
                                            <th class="text-center">OPCIÓN</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>



<script src="<?php base_url() ?>js/Gestion_Cotizador/add_adicional.js"></script>
<script>
    const base_url = '<?php echo base_url() ?>';
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