<?php $this->load->view('Informe_qr_taller/header.php'); ?>



<div class="content-wrapper" id="cuerpo">
    <section class="content">
        <div class="container">
            <div class="card" id="contenido">
                <div class="card-body">
                    <h2 align="center">Encuesta de satisfacción CODIESEL SA</h2>
                    <hr>
                    <p style="font-style: oblique;font-size: 12px;">A continuación elija el estado que represente su nivel de satisfacción con el servicio del taller hoy 2022-01-24</p>
                    <hr>
                    <div class="container">
                        <form method="POST" id="formularioqr">
                            <div id="respuesta"></div>
                            <div class="form-row">
                                <div class="col">
                                    <label>Ingrese el Taller</label>
                                    <select class="form-control" id="bodega" name="bodega">
                                        <option value="0" class="d-none">Seleccione un taller</option>
                                        <option value="1">GASOLINA GIRON</option>
                                        <option value="11">DIESEL GIRON</option>
                                        <option value="21">LAMINA Y PINTURA GIRON</option>
                                        <option value="6">GASOLINA BARRANCA</option>
                                        <option value="7">GASOLINA ROSITA</option>
                                        <option value="8">GASOLINA BOCONO</option>
                                        <option value="16">DIESEL BOCONO</option>
                                        <option value="14">LAMINA Y PINTURA BOCONO</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label>Ingrese La Placa</label>
                                    <input type="text" name="placa" id="placa" class="form-control" required placeholder="Ingresa Tu Placa" onkeyup="val_placa();mayus(this);">
                                    <small id="msg_ot" class="form-text text-muted"></small>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row">
                                <div class="col" align="center">
                                    <label for="exampleInputPassword1">Satisfacción con el concesionario</label><br>
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-danger btn-lg">
                                            <input type="radio" name="2" id="option2" autocomplete="off" value="6" style="font-size: 20px;"> <span style="font-size: 10px;">0-6</span> <i class="far fa-frown"></i>
                                        </label>
                                        <label class="btn btn-outline-warning btn-lg">
                                            <input type="radio" name="2" id="option2" autocomplete="off" value="8"> <span style="font-size: 10px;">7-8</span> <i class="far fa-meh"></i>
                                        </label>
                                        <label class="btn btn-outline-success btn-lg active">
                                            <input type="radio" name="2" id="option2" autocomplete="off" value="10"> <span style="font-size: 10px;">9-10</span> <i class="far fa-smile"></i>
                                        </label>
                                    </div>
                                </div>
                                <div class="col" align="center">
                                    <label for="exampleInputPassword1">Satisfacción con el trabajo realizado</label> <br>
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-danger btn-lg">
                                            <input type="radio" name="3" id="option3" autocomplete="off" value="6" style="font-size: 20px;"> <span style="font-size: 10px;">0-6</span> <i class="far fa-frown"></i>
                                        </label>
                                        <label class="btn btn-outline-warning btn-lg">
                                            <input type="radio" name="3" id="option3" autocomplete="off" value="8"> <span style="font-size: 10px;">7-8</span> <i class="far fa-meh"></i>
                                        </label>
                                        <label class="btn btn-outline-success btn-lg active">
                                            <input type="radio" name="3" id="option3" autocomplete="off" value="10"> <span style="font-size: 10px;">9-10</span> <i class="far fa-smile"></i>
                                        </label>
                                    </div>
                                </div>

                                <div class="col" align="center">
                                    <label for="exampleInputPassword1">Satisfacción con el trabajo realizado</label> <br>
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-danger btn-lg">
                                            <input type="radio" name="3" id="option3" autocomplete="off" value="6" style="font-size: 20px;"> <span style="font-size: 10px;">0-6</span> <i class="far fa-frown"></i>
                                        </label>
                                        <label class="btn btn-outline-warning btn-lg">
                                            <input type="radio" name="3" id="option3" autocomplete="off" value="8"> <span style="font-size: 10px;">7-8</span> <i class="far fa-meh"></i>
                                        </label>
                                        <label class="btn btn-outline-success btn-lg active">
                                            <input type="radio" name="3" id="option3" autocomplete="off" value="10"> <span style="font-size: 10px;">9-10</span> <i class="far fa-smile"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row">
                                <div class="col" align="center">
                                    <label for="exampleInputPassword1">Explicación todo el trabajo realizado</label> <br>
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-secondary btn-lg">
                                            <input type="radio" name="4" id="option4" autocomplete="off" value="NO"> <i class="far fa-thumbs-down"></i>
                                        </label>
                                        <label class="btn btn-outline-primary btn-lg">
                                            <input type="radio" name="4" id="option4" autocomplete="off" value="SI"> <i class="far fa-thumbs-up"></i>
                                        </label>
                                    </div>
                                </div>
                                <div class="col" align="center">
                                    <label for="exampleInputPassword1">Se cumplieron los compromisos pactados (Tiempo Porceso)</label> <br>
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-secondary btn-lg">
                                            <input type="radio" name="5" id="option5" autocomplete="off" value="NO"> <i class="far fa-thumbs-down"></i>
                                        </label>
                                        <label class="btn btn-outline-primary btn-lg">
                                            <input type="radio" name="5" id="option5" autocomplete="off" value="SI"> <i class="far fa-thumbs-up"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row">
                                <div class="col" align="center">
                                    <td>
                                        <textarea id="7" name="7" class="form-control form-control-sm"></textarea>
                                    </td>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row">
                                <div class="col">
                                    <div align="center"> <button type="button" id="btn_env" class="btn btn-warning btn-lg" onclick="enviar_datos_encuesta_qr_ventanilla();">Enviar Respuestas</button></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('Informe_qr_taller/footer.php'); ?>