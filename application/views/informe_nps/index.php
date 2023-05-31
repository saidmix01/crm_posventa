<?php $this->load->view('Informe_nps/header.php'); ?>

<style>
    #padretabla {
        overflow: scroll;
        height: 500px;
        width: 100%;
    }

    #tabladatos {
        width: 100%;
    }

    thead tr th {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #ffffff;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="card">
            <div class="card-body">
                <h3 class="col-lg-12 text-center">Informe NPS</h3>
                <hr>
                <form id="formulariobusqueda" method="post">
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label for="sede">Sede</label>
                                <select class="form-control" id="sede" name="sede">
                                    <option value="todas">Todas</option>
                                    <option value="giron">Girón</option>
                                    <option value="rosita">La Rosita</option>
                                    <option value="bocono">Cúcuta Boconó</option>
                                    <option value="barranca">Barrancabermeja</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="sede">Mes</label>
                                <select class="form-control" id="mes" name="mes">
                                    <option value="0">Todos</option>
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
                    <div class="col" align="center">
                        <a href="#" onclick="buscar();" class="btn btn-primary">Buscar</a>
                    </div>
                </form>
                <hr>
                <div class="card">
                    <div class="card-header" id="btn-filtro" align="right">
                        <a href="#" class="btn btn-success" onclick="bajar_excel();"><i class="far fa-file-excel"></i> Exportar a excel</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="padretabla">
                            <table class="table table-bordered table-hover" id="tabladatos" style="font-size:14px;width:100%;">
                                <thead class="thead-dark" align="center" id="fjo">
                                    <tr>
                                        <th scope="col">Documento</th>
                                        <th scope="col">Tecnico</th>
                                        <th scope="col">Satisfaccion con el concesionario</th>
                                        <th scope="col">Satisfaccion con el trabajo</th>
                                        <th scope="col">Explicacion todo el trabajo realizado</th>
                                        <th scope="col">Se cumplieron los compromisos pactados</th>
                                        <th scope="col">Verbalizacion</th>
                                    </tr>
                                </thead>
                                <tbody align="center">
                                    <?php foreach ($datosencuesta->result() as $datos) { ?>
                                        <tr>
                                            <td><?= $datos->nit ?></td>
                                            <td><?= $datos->nombres ?></td>
                                            <td><?= $datos->pregunta1 ?></td>
                                            <td><?= $datos->pregunta2 ?></td>
                                            <td><?= $datos->pregunta3 ?></td>
                                            <td><?= $datos->pregunta4 ?></td>
                                            <td class="d-none"><?= $datos->pregunta5 ?></td>
                                            <td class="fitwidth" data-toggle="modal" data-target="#comentario"><a href="#" class="btn btn-outline-info mr-3 >" onclick="verbalizacion('<?= $datos->pregunta5 ?>')" id="<?= $datos->n_orden ?>"> <i class="fas fa-eye"></i></a></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <div id="filtro"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- modal con verbalizacion-->
<div class="modal fade" id="comentario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title col-lg-12 text-center lead" id="exampleModalLabel">Verbalizacion</h5>
            </div>
            <div class="modal-body">
                <p id="respuesta"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function verbalizacion(verbalizacion) {
        $('#comentario').modal('show');
        document.getElementById('respuesta').innerHTML = verbalizacion;
    };
</script>

<?php $this->load->view('Informe_nps/footer.php'); ?>