<?php $this->load->view('ranking/header') ?>
<style>
    .padretabla {
        overflow: scroll;
        height: 400px;
        width: 100%;
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
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section>
        <div class="card">
            <div class="container">
                <div class="label alert col-lg-12 text-center lead">
                    <h2>Ranking ventas por Tecnicos Trimestral </h2>
                </div>
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
                    <div class="responsive  padretabla p-3">
                        <table class="table table-bordered table-striped tabladatos">
                            <thead>
                                <tr>
                                    <th scope="col">Tecnico</th>
                                    <th scope="col">Total ventas de mes 1 </th>
                                    <th scope="col">Total ventas de mes 2 </th>
                                    <th scope="col">Total ventas de mes 3 </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rankin->result() as $ranking) {

                                    $mes = $ranking->Mes;
                                 
                                ?>
                                    <tr>
                                        <th><?= $ranking->tecnico ?></th>
                                        <td>$<?= number_format($ranking->suma_todo, 0, ",", ",") ?></td>
                                        <td>$<?= number_format($ranking->suma_todo, 0, ",", ",") ?></td>
                                        <td>$<?= number_format($ranking->suma_todo, 0, ",", ",") ?></td>
                                        <td><?= $ranking->Mes ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </section>
</div>
<?php $this->load->view('ranking/footer') ?>