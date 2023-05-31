<?php $this->load->view('administracion/header') ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <br>
    <!-- Main content -->
    <section class="content">
        <div class="card">
            <label class="card-title col-lg-12 text-center">Seccion de Filtros</label>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form>
                            <div class="form-row">
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="FechaUno" id="FechaUno">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="FechaDos" id="FechaDos">
                                </div>
                                <div class="">
                                    <input value="<?php echo $this->session->userdata('user'); ?>" type="hidden" class="form-control" name="userr" id="userr">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" id="sedes" name="sedes">
                                        <option class="d-none" value="">Selecciones una sede</option>
                                        <option value="Giron">Girón</option>
                                        <option value="Rosita">Rosita</option>
                                        <option value="Chevropartes">Chevropartes</option>
                                        <option value="Solochevrolet">Solochevrolet</option>
                                        <option value="Barrancabermeja">Barrancabermeja</option>
                                        <option value="Bocono">Boconó</option>
                                        <option value="Malecon">Malecón</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" name="areas" id="areas">
                                        <option class="d-none" value="">Selecciones un Area</option>
                                        <option value="Administración Servicio">Administración</option>
                                        <option value="Administración">Administración Servicio</option>
										<option value="Central de Beneficios">Central de Beneficios</option>
										<option value="Vehiculos Nuevos">Vehiculos Nuevos</option>
										<option value="Vehiculos Usados">Vehiculos Usados</option>
										<option value="Repuestos">Repuestos</option>
										<option value="Taller Gasolina">Taller Gasolina</option>
										<option value="Taller Diesel">Taller Diesel</option>
										<option value="Lamina y Pintura">Lamina y Pintura</option>
										<option value="Alistamiento">Alistamiento</option>
										<option value="Contac Center">Contac Center</option>
										<option value="Accesorios">Accesorios</option>
                                       
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button onclick="enviarFechasAusentimso()" style=" text-shadow: 5px 3px 4px #000000; width: 100%" type="button" class="btn btn-info " id="enviarFechas"> Buscar &nbsp; <i class="fas fa-search"></i></button>
                                </div>
                                <div class="col-md-2">
                                    <button onclick="window.location.reload();" style=" text-shadow: 5px 3px 4px #000000; width: 100%" type="button" class="btn btn-success " id="enviarFechas"> Recargar &nbsp; <i class="fas fa-sync"></i></button>
                                </div>
                            </div>
                        </form>
                        <!--<button type="submit" id="refrescar" name="refrescar" value="63369607" style=" text-shadow: 5px 3px 4px #000000" class=" btn btn-success shadow">Ver Listado General</button>-->
                    </div>

                </div>
            </div>
        </div>

        </style>
        <div class="card table-responsive" id="tableause">
            <div class="card-body">
                <table id='infomeAusentismo' class='table table-sm table-responsive-sm table-bordered tabla-hover' style='font-size: 12px;'>
                    <label class='col-lg-12 text-center lead'>Listado General de Ausentismo</label>
                    <a style=" text-shadow: 5px 3px 4px #000000" href="#" id="infoAusentismo" class=" btn btn-success shadow">Descargar &nbsp; <i class="far fa-file-excel"></i></a>
                    <thead class='table-dark'>

                        <tr>
                            <th style='white-space: nowrap; width: 1px;' scope='col'>Nombre</th>
                            <th style='white-space: nowrap; width: 1px;' scope='col'>Documento</th>
							<th class="text-center  " style='white-space: nowrap; width: 1px;' scope='col'>Cargo</th>
                            <th class="text-center  " style='white-space: nowrap; width: 1px;' scope='col'>Sede</th>
                            <th class="text-center" style='white-space: nowrap; width: 1px;' scope='col'>Area</th>
                            <th class="text-center" style='white-space: nowrap; width: 1px;' scope='col'>Fecha inicio ausentismo</th>
                            <th class="text-center" style='white-space: nowrap; width: 1px;' scope='col'>Hora inicio ausentismo</th>
                            <th class="text-center" style='white-space: nowrap; width: 1px;' scope='col'>Fecha fin ausentismo</th>
                            <th class="text-center" style='white-space: nowrap; width: 1px;' scope='col'>Hora fin ausentismo</th>
                            <th style='white-space: nowrap; width: 1px;' scope='col'>Motivo</th>
                            <th style='white-space: nowrap; width: 1px;' scope='col'>Descripción</th>
                            <th class="text-center" style='white-space: nowrap; width: 1px;' scope='col'>Estado</th>
                            <th style='white-space: nowrap; width: 1px;' scope='col'>Quien autoriza</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $color = "";
                        foreach ($datos->result() as $key) {
                            if ($key->Estado == "Autorizado") {
                                $color = "#06C858";
                            } else if ($key->Estado == "Negado") {
                                $color = "#BB3128";
                            } else if ($key->Estado == 0) {
                                $color = "#E4D40F";
                            }
                            echo "<tr>
                                <td class='text-left'style='font-weight:normal; white-space: nowrap; width: 1px;'>" . $key->nombres . "</td>
                                <td class='text-left'style='font-weight:normal; white-space: nowrap; width: 1px;'>" . $key->empleado . "</td>
								<td class='text-left'style='font-weight:normal; white-space: nowrap; width: 1px;'>" . $key->cargo_emp . "</td>
                                <td class='text-center text'>" . $key->sede . "</td>
                                <td class='text-center'>" . $key->area . "</td>
                                <td class='text-center'>" . $key->fecha_ini . "</td>
                                <td class='text-center'>" . $key->hora_ini . "</td>
                                <td class='text-center'>" . $key->fecha_fin . "</td>
                                <td class='text-center'>" . $key->hora_fin . "</td>
                                <td>" . $key->motivo . "</td>
                                <td>" . $key->descripcion . "</td>
                                <td class='text-center' style = 'background-color:" . $color . "'><strong>" . $key->Estado . "</strong></td>
                                <td class='text-left'style='font-weight:normal; white-space: nowrap; width: 1px;'>" . $key->nombre_jefe . "</td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="tblaFechas"></div>
        <div id="tblasedes"></div>
        <div id="tblareas"></div>


    </section>
    <!-- /.content -->
</div>

<?php $this->load->view('administracion/footer') ?>
