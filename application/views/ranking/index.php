<?php $this->load->view('ranking/header') ?>
<style>
    .padretabla {
        overflow: scroll;
        height: 400px;
        width: 50%;
    }

    .tabladatos {
        width: 100%;
    }

    thead tr th {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: white;
        color: black;
    }


    .padretabla::-webkit-scrollbar {
        width: 5px;
    }

    .padretabla::-webkit-scrollbar-track {
        background-color: #DBE8EC;
    }

    .padretabla::-webkit-scrollbar-thumb {
        background-color: white;
        border-radius: 5px;
        border: 1px solid black;
    }

  
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section>
        <div class="card">
            <div class="container">
                <div class="label alert col-lg-12 text-center lead"><h2>Ranking de ventas por Tecnicos </h2></div>
                <hr>
                <form id="formulariobusqueda" method="POST" class="justify-content-center align-content-center">
                    <div class="form-row">
                        <div class="form-group col-lg-6">
                            <label for="ano">AÑO</label>
                            <select class="form-control" id="ano" name="ano">
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>MES</label>
                                <select class="form-control" id="mes" name="mes">
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
                </form>
                <div class="col-lg-6"><button type="button" class="btn btn-info" onclick="filtar_mes();">Filtrar</button></div>
            </div>
            <br>
        </div>
        <div class="card">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-center" id="vista">
                    <br>

                    <div class="responsive p-4 padretabla">
                        <table class="table table-bordered table-striped tabladatos">
                            <thead>
                                <tr>
                                    <th colspan="4"><label class="text-center col-lg-12 cabeza">Giron Gasolina</label></th>
                                </tr>
                                <tr>
                                    <th scope="col">Tecnico</th>
                                    <th scope="col">Ventas Repuesto</th>
                                    <th scope="col">Ventas Trabajo</th>
                                    <th scope="col">Total Ventas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rankin_g_g->result() as $gg) {


                                ?>
                                    <tr>
                                        <th><?= $gg->tecnico ?></th>
                                        <td>$<?= number_format($gg->rptos, 0, ",", ",") ?></td>
                                        <td>$<?= number_format($gg->MO, 0, ",", ",") ?></td>
                                        <td>$<?= number_format($gg->suma_todo, 0, ",", ",") ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="responsive p-4 padretabla">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <td colspan="4"><label class="text-center col-lg-12">Giron Diesel</label></td>
                                </tr>
                                <tr>
                                    <th scope="col">Tecnico</th>
                                    <th scope="col">Ventas Repuesto</th>
                                    <th scope="col">Ventas Trabajo</th>
                                    <th scope="col">Total Ventas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rankin_d_g->result() as $gd) {

                                ?>
                                    <tr>
                                        <th><?= $gd->tecnico ?></th>
                                        <td>$<?= number_format($gd->rptos, 0, ",", ",") ?></td>
                                        <td>$<?= number_format($gd->MO, 0, ",", ",") ?></td>
                                        <td>$<?= number_format($gd->suma_todo, 0, ",", ",") ?></td>

                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="responsive p-4 padretabla">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <td colspan="4"><label class="text-center col-lg-12">Cucuta</label></td>
                                </tr>
                                <tr>
                                    <th scope="col">Tecnico</th>
                                    <th scope="col">Ventas Repuesto</th>
                                    <th scope="col">Ventas Trabajo</th>
                                    <th scope="col">Total Ventas</th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php foreach ($rankin_d_c->result() as $dc) { ?>
                                    <tr>
                                        <th><?= $dc->tecnico ?></th>
                                        <td>$<?= number_format($dc->rptos, 0, ",", ",") ?></td>
                                        <td>$<?= number_format($dc->MO, 0, ",", ",") ?></td>
                                        <td>$<?= number_format($dc->suma_todo, 0, ",", ",") ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="responsive p-4 padretabla">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <td colspan="4"><label class="text-center col-lg-12">La Rosita</label></td>
                                </tr>
                                <tr>
                                    <th scope="col">Tecnico</th>
                                    <th scope="col">Ventas Repuesto</th>
                                    <th scope="col">Ventas Trabajo</th>
                                    <th scope="col">Total Ventas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rankin_r->result() as $rc) { ?>
                                    <tr>
                                        <th><?= $rc->tecnico ?></th>
                                        <td>$<?= number_format($rc->rptos, 0, ",", ",") ?></td>
                                        <td>$<?= number_format($rc->MO, 0, ",", ",") ?></td>
                                        <td>$<?= number_format($rc->suma_todo, 0, ",", ",") ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>


                    <div class="responsive p-4 padretabla">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <td colspan="4"><label class="text-center col-lg-12">Barrancabermeja</label></td>
                                </tr>
                                <tr>
                                    <th scope="col">Tecnico</th>
                                    <th scope="col">Ventas Repuesto</th>
                                    <th scope="col">Ventas Trabajo</th>
                                    <th scope="col">Total Ventas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rankin_b->result() as $bc) { ?>
                                    <tr>
                                        <th><?= $bc->tecnico ?></th>
                                        <td>$<?= number_format($bc->rptos, 0, ",", ",") ?></td>
                                        <td>$<?= number_format($bc->MO, 0, ",", ",") ?></td>
                                        <td>$<?= number_format($bc->suma_todo, 0, ",", ",") ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>


                <div id="filtropormes"></div>
            </div>
        </div>


    </section>
</div>
<?php $this->load->view('ranking/footer') ?>