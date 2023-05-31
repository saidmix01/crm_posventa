<?php $this->load->view('Informe_nps/header.php'); ?>

<?php
$mesactual = date('m');
$yearactual = date('Y');
$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

?>

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
                <form id="formulariobusqueda" method="post" class="d-none">
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
                        <a href="#" class="btn btn-success" onclick="bajar_excel_nps();"><i class="far fa-file-excel"></i> Exportar a excel</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="padretabla">
                            <table class="table table-bordered table-hover" id="tabladatosnps" style="font-size:14px;width:100%;">
                                <thead class="thead-dark" align="center" id="fjo">
                                    <tr>
                                        <th scope="col">Tecnico</th>
                                        <th scope="col">Sede</th>
                                        <th scope="col">Datos</th>
                                        <?php
                                        for ($i = 0; $i <= ($mesactual - 1); $i++) {
                                            echo "<th scope='col'>$meses[$i]</th>";
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody align="center">


                                    <?php
                                    $tecnicos = $this->Informe->obtener_tecnicos_nps($yearactual);
                                    $num_tec = $tecnicos->num_rows();

                                    for ($i = 1; $i <= $num_tec; $i++) {
                                        $sedes = array();
                                        $valoresNPS = array();
                                        $valoresOT = array();
                                        $sedesCadena = '';
                                        $row_tec = $tecnicos->row($i - 1);

                                        /* $dato_ot = $this->Informe->obtener_datos_encuestas_ot($yearactual,$j,$row_tec->nit);
                                        $numOT = $dato_ot->num_rows();

                                        if ($numOT > 0) {
                                            for ($o=0; $o < $numOT; $o++) { 
                                                $rowOT = $dato_ot->row_array($o);
                                            }
                                        } */

                                        $val = '';
                                        $valOT = '';
                                        for ($j = 1; $j <= count($meses); $j++) {
                                            $datosencuesta = $this->Informe->datos_de_Informe_nps($j, $yearactual, $row_tec->nit);
                                            $numTotal = $datosencuesta->num_rows();

                                            if ($numTotal > 0) {
                                                $valor1 = 0;
                                                $valor2 = 0;
                                                $valor3 = 0;
                                                for ($k = 0; $k < $numTotal; $k++) {
                                                    $row = $datosencuesta->row_array($k);
                                                    $valor1 = $valor1 + $row['enc0a6'];
                                                    $valor2 = $valor2 + $row['enc7a8'];
                                                    $valor3 = $valor3 + $row['enc9a10'];

                                                    if (in_array($row['descripcion'], $sedes)) {
                                                    } else {
                                                        $sedes[] =  $row['descripcion'];
                                                    }
                                                }
                                                $to_enc = $valor1 + $valor2 + $valor3;
                                                $nps = ((($valor3 - $valor1) / $to_enc) * 100);


                                                $val =  '<td style="background: #C5D9FC ;"> ' . intval($nps) . "%" . '</td>';
                                                $valOT =  '<td style="background:#DCFACF;" >' . intval($to_enc) . '</td>';
                                               // $valECU =  '<td style="background:#DCFACF;" >' . intval($to_enc) . '</td>';
                                            } else {
                                                $val = '<td style="background: #C5D9FC ;">0</td>';
                                                $valOT = '<td style="background:#DCFACF;">0</td>';
                                                //$valECU = '<td style="background:#DCFACF;">0</td>';
                                            }
                                            $valoresNPS[] = $val;
                                            $valoresOT[] = $valOT;
                                            //$valoresEC[] = $valECU;
                                        }

                                        echo '<tr>';
                                        echo '<td rowspan="3" class="text-left">' . $row_tec->nombres . '</td>';

                                        for ($l = 0; $l < count($sedes); $l++) {
                                            $coma = count($sedes) >= 2 ? ',' : '';
                                            $sedesCadena = $sedes[$l] .$coma.$sedesCadena;
                                        }
                                        echo '<td rowspan="3">' . $sedesCadena . '</td>';

                                        echo '<td style="background: #C5D9FC ;">NPS</td>';

                                        for ($p = 0; $p < count($valoresNPS); $p++) {
                                            echo $valoresNPS[$p];
                                        }
                                        echo '</tr>';


                                        echo '<tr>';
                                        echo '<td style="background:#DCFACF;">Encuestas</td>';
                                        for ($o = 0; $o < count($valoresOT); $o++) {
                                            echo $valoresOT[$o];
                                        }
                                        echo '</tr>';
                                        echo '<tr>';
                                        echo '<td style="background:#EFF3AE;">OT</td>';
                                        /*for ($z = 0; $z < count($valoresEC); $z++) {
                                            echo $valoresEC[$o];
                                        }*/
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
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
                <button type="button" class="btn btn-danger" data-dismiss="modal" >Cerrar</button>
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