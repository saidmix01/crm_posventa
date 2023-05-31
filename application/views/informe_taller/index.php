 <?php
    $this->load->view('Informe_taller/header');
    ?>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
     <br>
     <!-- Main content -->
     <section class="content">
         <div class="card">
             <div class="card-body">
                 <div class="row">
                     <div class="col-md-3">
                         <form class="form-inline">
                             <div class="row">
                                 <div class="col">
                                     <label for="combo_bod">Selecciona una bodega</label>
                                     <select class="form-control" id="combo_bod" onchange="cargar_tabla()">
                                         <option value="todas">Todas</option>
                                         <?php foreach ($data_bod->result() as $key) { ?>
                                             <option value="<?= $key->idsede ?>"><?= $key->descripcion ?></option>
                                         <?php } ?>
                                     </select>
                                 </div>
                             </div>
                         </form>
                     </div>
                     <div class="col-md-9" align="right">
                         <div class="info-box mb-3" style="width: 15rem;">
                             <span class="info-box-icon bg-success elevation-1"><i class="far fa-file-alt"></i></span>

                             <div class="info-box-content">
                                 <span class="info-box-text">Ordenes abiertas</span>
                                 <span class="info-box-number">
                                     <h4 id="ot_abiertas"></h4>
                                 </span>
                             </div>
                             <!-- /.info-box-content -->
                         </div>
                     </div>

                 </div>
             </div>
         </div>
         <div class="card">
             <div class="card-body">
                 <div class="row">
                     <div class="col-md-12" align="right">
                         <button class="btn btn-success btn-sm" onclick="toexcel();" aling="right"><i class="far fa-file-excel"></i> Descargar Excel</button>
                     </div>
                 </div>
                 <div class="table-responsive row">
                     <table class="table table-hover" id="tabla_data" style="font-size: 12px;">
                         <thead>
                             <tr>
                                 <th scope="col" class="text-center">Acciones</th>
                                 <th scope="col">Bodega</th>
                                 <th scope="col">Orden N</th>
                                 <th scope="col" style="background-color: #E5E5E5;">Estado</th>
                                 <th scope="col" style="background-color: #E5E5E5;">Notas</th>
                                 <th scope="col" style="background-color: #E5E5E5;">Fecha Promesa entrega</th>
                                 <th scope="col">Fecha Ingreso</th>
                                 <th scope="col">Cliente</th>
                                 <th scope="col">Placa VH</th>
                                 <th scope="col">Rombo</th>
                                 <th scope="col">Aseguradora</th>
                                 <th scope="col">Razon de entrada</th>
                                 <th scope="col">Segunda razon entrada</th>

                                 <th scope="col">Asesor</th>
                                 <th scope="col">Kilometraje</th>
                                 <th scope="col">Vehiculo</th>
                                 <th scope="col">Días</th>


                             </tr>
                         </thead>
                         <tbody id="data_tabla_fil">
                             <?php
                                $this->load->model('talleres');
                                $color = "";
                                $border = "";
                                $estado = "";
                                foreach ($data_ot->result() as $key) {

                                    if($key->fecha_prom_ent != ''){
                                        $diff = $this->talleres->get_diff_dias_fecha($key->fecha_prom_ent)->ndias;
                                    }else{
                                        $diff = '';
                                    }
                                    //$bodegas = explode(',', $bods);
                                    if (
                                        $estado == 'EN ESPERA DE  RTOS G.M.'
                                        || $estado == 'EN ESPERA POR ASIGNACION DE MO'
                                        || $estado == 'EN ESPERA DE RPTOS G.M.'
                                        || $estado == 'EN ESPERA AUTORIZACIÓN'
                                        || $estado == 'EN ESPERA DIAGNÓSTICO'
                                    ) {
                                        $border = 'border: solid red 5px;';
                                    } else {
                                        $border = 'border: solid transparent 1px;';
                                    }

                                    if ($bods = "9,21") {
                                        if ($key->razon2 == 1) {
                                            if ($diff > 0 && $diff <= 3) {
                                                $color = "table-danger";
                                            } elseif ($diff > 4 && $diff <= 6) {
                                                $color = "table-warning";
                                            } elseif ($diff > 6 && $diff <= 7) {
                                                $color = "table-success";
                                            }
                                        } elseif ($key->razon2 == 2) {
                                            if ($diff > 0 && $diff <= 4) {
                                                $color = "table-danger";
                                            } elseif ($diff > 4 && $diff <= 8) {
                                                $color = "table-warning";
                                            } elseif ($diff > 8 && $diff <= 10) {
                                                $color = "table-success";
                                            }
                                        } elseif ($key->razon2 == 3) {
                                            if ($diff > 0 && $diff <= 6) {
                                                $color = "table-danger";
                                            } elseif ($diff > 6 && $diff <= 11) {
                                                $color = "table-warning";
                                            } elseif ($diff > 11 && $diff <= 16) {
                                                $color = "table-success";
                                            }
                                        }
                                    }
                                ?>
                                 <?php
                                    $razon2 = "";
                                    if ($key->razon2 == 1) {
                                        $razon2 = "Colisión Leve";
                                    } elseif ($key->razon2 == 2) {
                                        $razon2 = "Colisión Media";
                                    } elseif ($key->razon2 == 3) {
                                        $razon2 = "Colisión Fuerte";
                                    } elseif ($key->razon2 == 4) {
                                        $razon2 = "Mecanica Rapida";
                                    } elseif ($key->razon2 == 5) {
                                        $razon2 = "Mecanica Especializada";
                                    } elseif ($key->razon2 == 6) {
                                        $razon2 = "Accesorios";
                                    } elseif ($key->razon2 == 7) {
                                        $razon2 = "Garantia G.M.C";
                                    } elseif ($key->razon2 == 8) {
                                        $razon2 = "Alistamiento y Peritaje";
                                    } elseif ($key->razon2 == 9) {
                                        $razon2 = "Retorno";
                                    } elseif ($key->razon2 == 10) {
                                        $razon2 = "Interno";
                                    } else {
                                        $razon2 = $key->razon2;
                                    }
                                    $razon = "";
                                    if ($key->razon == 1) {
                                        $razon = "Colisión Leve";
                                    } elseif ($key->razon == 2) {
                                        $razon = "Colisión Media";
                                    } elseif ($key->razon == 3) {
                                        $razon = "Colisión Fuerte";
                                    } elseif ($key->razon == 4) {
                                        $razon = "Mecanica Rapida";
                                    } elseif ($key->razon == 5) {
                                        $razon = "Mecanica Especializada";
                                    } elseif ($key->razon == 6) {
                                        $razon = "Accesorios";
                                    } elseif ($key->razon == 7) {
                                        $razon = "Garantia G.M.C";
                                    } elseif ($key->razon == 8) {
                                        $razon = "Alistamiento y Peritaje";
                                    } elseif ($key->razon == 9) {
                                        $razon = "Retorno";
                                    } elseif ($key->razon == 10) {
                                        $razon = "Interno";
                                    } else {
                                        $razon = $key->razon;
                                    }

                                    /* Sergio */
                                    $btnSacyr = "";
                                    $cotizacionesSacyr = $this->Sacyr_model->getCotizacionByOrden($key->numero);
                                    $nCotizaciones = count($cotizacionesSacyr->result());
                                    if($nCotizaciones > 0){
                                        $idSacyr=[];
                                        foreach($cotizacionesSacyr->result() as $sacyr){
                                            $idSacyr[]= $sacyr->id;
                                        }
                                        $idSacyrC = implode('-',$idSacyr);
                                        $btnSacyr = '<a href="#" type="button" data-toggle="tooltip" data-placement="top" title="Mostrar Id de Cotizaciones de Sacyr" class="btn btn-outline-warning btn-sm m-1" style="font-size: 12px;" onclick="loadCotizaciones('.$key->numero.',\''.$idSacyrC.'\');"><i class="fas fa-edit"></i></a>';
                                    }
                                    ?>
                                 <tr class="<?= $color ?>" style="<?= $border ?>">
                                     <td>
                                         <div class="row">
                                             <div class="col-auto">
                                                 <a href="#" class="btn btn-outline-primary btn-sm m-1" style="font-size: 12px;" onclick="open_form_addev(<?= $key->numero ?>)"><i class="fas fa-plus-square"></i></a>
                                                 <a href="#" class="btn btn-outline-success btn-sm m-1" style="font-size: 12px;" onclick="open_form_hist(<?= $key->numero ?>)"><i class="fas fa-book-medical"></i></a>
                                                 <?=$btnSacyr?>
                                            </div>
                                         </div>
                                     </td>

                                     <td><?= $key->bodega ?></td>
                                     <td><strong><?= $key->numero ?></strong></td>
                                     <td style="background-color: #E5E5E5;"><?= $key->estado ?></td>
                                     <td style="background-color: #E5E5E5;"><?= $key->notas ?></td>
                                     <td style="background-color: #E5E5E5;"><?= $key->fecha_prom_ent ?></td>
                                     <td><?= $key->fecha ?></td>
                                     <td><?= $key->cliente ?></td>
                                     <td><strong><?= $key->placa ?></strong></td>
                                     <td><strong><?= $key->rombo ?></strong></td>
                                     <td><?= $key->aseguradora ?></td>
                                     <td><?= $razon ?></td>
                                     <td><?= $razon2 ?></td>
                                     <td><?= $key->asesor ?></td>
                                     <td><?= $key->kilometraje ?></td>
                                     <td><?= $key->descripcion ?></td>
                                     <td><?= $key->dias_ot_abierta ?></td>
                                 </tr>
                             <?php } ?>
                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
     </section>
     <!-- /.content -->
 </div>

 <?php
    $this->load->view('Informe_taller/modals_estado_taller');
    $this->load->view('Informe_taller/footer');
?>