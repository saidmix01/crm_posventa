<?php $this->load->view('Informes_nomina/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <br>

    <!-- Main content -->
    <!--style="display: grid;place-items:center;"-->
 

    <section class="content shadow">
        <div class="container-fluid">
            <div class="row">
                <div class="card bg-white col-lg-12">
                    <label class=" text-center lead h3 py-2">Comisión de asesor </label>
                    <hr>
                    <div class="card-body">
                        <form id="formulario_lyt">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <select class="form-control" name="ano" id="ano">
                                            <option value="2021">2021</option>
                                            <option value="2022">2022</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <select class="form-control" name="mes" id="mes" onchange="getval(this);">
                                            <option class="d-none">Selecciones un mes</option>
                                            <option value="1">Enero</option>
                                            <option value="2">Febrero</option>
                                            <option value="3">Marzo</option>
                                            <option value="4">Abril</option>
                                            <option value="5">Mayo</option>
                                            <option value="6">Junio</option>
                                            <option value="7">Julio</option>
                                            <option value="8">Agosto</option>
                                            <option value="9">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <!--<button type="button" onclick="datos_typ()" id="consulta" class="btn btn-success">Buscar</button>-->
                        </form>
                        <div id="datos_nomina" class="p-2 m-2 col-lg-12"></div>
                        <hr>

                        <div id="datos_filtro" class="p-2 m-2 col-lg-12"></div>


                    </div>
                </div>
            </div>



    </section>
</div>
<!-- /.content -->
</div>


<?php $this->load->view('Informes_nomina/footer') ?>