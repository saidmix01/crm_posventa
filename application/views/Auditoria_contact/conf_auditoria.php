<?php $this->load->view('Auditoria_contact/header'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="alert alert-light col-lg-12 text-center" role="alert">
            <h4>Configuración</h4>
        </div>
    </section>
    <!-- Main content -->

    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="row text-center">
                    <div class="col-6 col-md-3 col-sm-3">
                        <button type="button" class="btn btn-outline-primary" onclick="cargarIndicadores();">Indicadores</button>
                    </div>
                    <div class="col-6 col-md-3 col-sm-3">
                        <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#ItemsModal">Items</button>

                    </div>
                    <div class="col-6 col-md-3 col-sm-3">
                        <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#ObservacionesModal">Observaciones</button>
                    </div>
                    <div class="col-6 col-md-3 col-sm-3">
                        <button type="button" class="btn btn-outline-warning" onclick="CargarFormAud();">Ver vista previa</button>
                    </div>


                </div>
            </div>
            <div class="card-body">
                <div class="responsive" id="CargarFormAud">

                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- Modal Indicadores-->
<div class="modal fade" id="IndicadoresModal" tabindex="-1" role="dialog" aria-labelledby="IndicadoresModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="IndicadoresModalTitle">INDICADORES</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button> -->
            </div>
            <div class="modal-body">
                <div class="table-responsive" id="cargarIndicadoresTable">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" id="btnCrearInd" onclick="addNewIndicador();">Crear Indicador</button>
                <button disabled="true" type="button" class="btn btn-outline-warning" id="btnAggInd" onclick="aggIndicador();">Agregar Indicador</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Indicadores cambio de estado-->
<div class="modal fade" id="modalCambiarEstadoItem" tabindex="-1" role="dialog" aria-labelledby="modalCambiarEstadoItem" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCambiarEstadoItemTitle">INDICADORES</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button> -->
            </div>
            <div class="modal-body">
                <div class="table-responsive" id="cargarIndicadoresTablePuntos">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" id="btnGuardarCambios" onclick="GuardarCambios();">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Items-->
<div class="modal fade" id="ItemsModal" tabindex="-1" role="dialog" aria-labelledby="ItemsModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ItemsModalTitle">AGREGAR NUEVO ITEM</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button> -->
            </div>
            <div class="modal-body">
                <div class="row">
                    <label>Indicadores: </label>
                    <select id="indicadorItem" name="indicadorItem" class="form-control indicadores" onchange="mostrarItems(this.value);">
                        <option value="">Seleccione una opción</option>
                        <?php
                        foreach ($indicadores->result() as $key) {
                            echo '<option value="' . $key->id_indicador . '">' . $key->nombres . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label>Items: </label>
                        <div class="table-responsive">
                            <table class="table table-bordered ">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Concepto</th>
                                        <th>Opción</th>
                                    </tr>
                                </thead>
                                <tbody id="cargarItemsXind">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label>Concepto: </label>
                    <textarea id="conceptoItem" name="conceptoItem" class="form-control" placeholder="Escriba el concepto del nuevo item" rows="4"></textarea>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" id="btnCrearItem" onclick="addNewItem();">Crear Item</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Observaciones-->
<div class="modal fade" id="ObservacionesModal" tabindex="-1" role="dialog" aria-labelledby="ObservacionesModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ObservacionesModal">OBSERVACIONES</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button> -->
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6 col-m6 col-sm-6">
                        <label>Indicadores: </label>
                        <select id="indicadorItemObs" name="indicadorItemObs" class="form-control indicadores" onchange="cargarSeletecItems(this.value);">
                            <option value="">Seleccione una opción</option>
                            <?php
                            foreach ($indicadores->result() as $key) {
                                echo '<option value="' . $key->id_indicador . '">' . $key->nombres . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-6 col-m6 col-sm-6">
                        <label>Items: </label>
                        <select id="ItemObs" name="ItemObs" class="form-control items" onchange="mostrarObs(this.value);">
                            <option value="">Seleccione una opción</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label>Observaciones: </label>
                        <div class="table-responsive">
                            <table class="table table-bordered ">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Observación</th>
                                        <th>Opción</th>
                                    </tr>
                                </thead>
                                <tbody id="cargarObsXitem">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label>Observación: </label>
                    <textarea id="conceptoObs" name="conceptoObs" class="form-control" placeholder="Escriba la observación que desea agregar al item seleccionado" rows="4"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button disabled type="button" class="btn btn-outline-success" id="btnCrearObs" onclick="addNewObs();">Crear Observación</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Observaciones luego de agregar el Item-->
<div class="modal fade" id="ObservacionesModalAfterItem" tabindex="-1" role="dialog" aria-labelledby="ObservacionesModalAfterItem" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ObservacionesModalAfterItemTitle">OBSERVACIONES</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button> -->
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="idItemAfter" name="idItemAfter" value="">
                    <label>Observación: </label>
                    <textarea id="conceptoObsAfter" name="conceptoObsAfter" class="form-control" placeholder="Escriba la observación que desea agregar al item seleccionado" rows="4"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" id="btnCrearObs" onclick="addNewObsAfter();">Crear Observación</button>
                <button type="button" class="btn btn-outline-danger" id="btnCrearObs" onclick="location.reload();">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    function CargarFormAud() {
        document.getElementById('cargando').style.display = 'block';
        var opt = 1;
        var info = new FormData();
        info.append('opcion', opt);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        /* xmlhttp.responseType = 'json'; */
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                document.getElementById('CargarFormAud').innerHTML = xmlhttp.responseText;
                document.getElementById('cargando').style.display = 'none';

            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/CargarFormAudVistaPrevia", true);
        xmlhttp.send(info);

    }

    function cargarIndicadores() {
        document.getElementById('cargando').style.display = 'block';
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        /* xmlhttp.responseType = 'json'; */
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                document.getElementById('cargarIndicadoresTable').innerHTML = xmlhttp.responseText;
                document.getElementById('cargando').style.display = 'none';
                $("#IndicadoresModal").modal().show();

            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/cargarIndicadores", true);
        xmlhttp.send();

    }
    /* Validar Cantidad de Auditorías */
    function validateCantAuditorias() {
        var result;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        /* xmlhttp.responseType = 'json'; */
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {

                result = xmlhttp.responseText;

            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/validateCantAuditorias", false);
        xmlhttp.send();

        return result;

    }
    //Function para Cambiar el estado de un Indicador
    function cambiarEstado(id, estado) {
        var validate = validateCantAuditorias();
        if (validate == 0) {
            cargarIndicadoresPuntos(id, estado);
            document.getElementById('cargarIndicadoresTable').innerHTML = "";
            $('#IndicadoresModal').modal('hide');
            $('#modalCambiarEstadoItem').modal('show');
        } else {
            Swal.fire({
                title: 'Advertencia',
                text: 'No puede Inhabilitar indicadores ya que existen auditorias por finalizar.',
                icon: 'error',
                confirmButtonText: 'Ok',
                allowOutsideClick: false,
                showCloseButton: false
            });
        }


    }

    function cargarIndicadoresPuntos(id, estado) {
        var form = new FormData();
            form.append('id_indicador', id);
            form.append('estado', estado);
        document.getElementById('cargando').style.display = 'block';
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        /* xmlhttp.responseType = 'json'; */
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                document.getElementById('cargarIndicadoresTablePuntos').innerHTML = xmlhttp.responseText;
                document.getElementById('cargando').style.display = 'none';
                $("#modalCambiarEstadoItem").modal().show();

            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/cargarIndicadoresPuntos", true);
        xmlhttp.send(form);

    }

    /* Funcion para cambiar el estado del indicador */
    function cambiarEstadoIndPuntos(){
        document.getElementById('cargando').style.display = 'block';
            var form = new FormData();
            form.append('id_indicador', id);
            form.append('estado', estado);
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            /* xmlhttp.responseType = 'json'; */
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {

                    if (xmlhttp.responseText == 1) {

                        /* A PHP code that is being executed in a HTML file. */
                        Swal.fire({
                            title: 'Exito',
                            text: 'El indicador se ha Inhabilitado con exito',
                            icon: 'success',
                            confirmButtonText: 'Ok',
                            allowOutsideClick: false,
                            showCloseButton: false
                        }).then((result) => {
                            // Read more about isConfirmed, isDenied below 
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else if (xmlhttp.responseText == 2) {

                        Swal.fire({
                            title: 'Exito',
                            text: 'El indicador se ha habilitado con exito',
                            icon: 'success',
                            confirmButtonText: 'Ok',
                            allowOutsideClick: false,
                            showCloseButton: false
                        }).then((result) => {
                            // Read more about isConfirmed, isDenied below 
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error, al tratar de actualizar el estado del indicador. La página se refrescara automaticamente despues' +
                                +'de dar clic al "OK", intente nuevamente realizar la operación.',
                            icon: 'warning',
                            confirmButtonText: 'Ok',
                            allowOutsideClick: false,
                            showCloseButton: false
                        }).then((result) => {
                            // Read more about isConfirmed, isDenied below 
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    }
                    document.getElementById('cargando').style.display = 'none';



                }
            }
            xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/estadoIndicador", true);
            xmlhttp.send(form);
    }

    /* Funcion para guardar cambios estados indicadores */
    function GuardarCambios(){

        var puntos = document.getElementById('sumaPuntos').value; // Suma de los puntos
        var estado = document.getElementById('estadoIndCambiar').value;
        var idIndicador = document.getElementById('idIndicadorCambiar').value;

        if (puntos == 100) {

            document.getElementById('cargando').style.display = 'block';

            var datosInd = [];

            datosInd.push($(".filas td").map(function(e) {
                var lol = this.innerText == '' ? $(this).children('input').val() : this.innerText;

                return lol;
            }).get());

            var form = new FormData();
            form.append('datosInd', datosInd);
            form.append('estado', estado);
            form.append('idIndicador', idIndicador);

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            /* xmlhttp.responseType = 'json'; */
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    if (xmlhttp.responseText == 1){
                        alert('Cambio realizado');
                    }else {
                        alert('Cambio no se ha realizado');
                    }
                }
            }
            xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/updateIndEstado", true);
            xmlhttp.send(form);

        } else {
            Swal.fire({
                title: 'Advertencia',
                text: 'Para cambiar el estado del indicador, debes debes hacer que la suma de los puntos sea igual a 100',
                icon: 'error',
                confirmButtonText: 'Ok',
                allowOutsideClick: false,
                showCloseButton: false
            });
        }
    }

    function cargarObservaciones() {
        document.getElementById('cargando').style.display = 'block';
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        /* xmlhttp.responseType = 'json'; */
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                document.getElementById('cargarObservacionesTable').innerHTML = xmlhttp.responseText;
                document.getElementById('cargando').style.display = 'none';

            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/cargarObservaciones", true);
        xmlhttp.send();

    }

    function sumarPuntos(puntos, id_ind) {
        //console.log(id_ind + '\n' + puntos);
        var suma = 0;
        var inputSelect = document.getElementById(id_ind).value;

        if (inputSelect > 0) {

            $("input[name='indPuntos']").each(function() {
                suma += parseInt(this.value);

            });

            document.getElementById('sumaPuntos').value = suma;

        } else {
            Swal.fire({
                title: 'Advertencia',
                text: 'El indicador no puede tener un puntaje igual a 0',
                icon: 'warning',
                confirmButtonText: 'Ok',
                allowOutsideClick: false,
                showCloseButton: false
            });
            document.getElementById(id_ind).value = puntos;
        }

    }

    function addNewIndicador() {
        //Elimina todos los td con la clase btnIndHabilitar
        $("td[class='btnIndHabilitar']").each(function() {
            this.remove();
        });
        //Habilita todos los inputs con el name indPuntos
        $("input[name='indPuntos']").each(function() {
            this.disabled = false;
        });
        //Deshabilita los btn de cambiar de Estado
        $("button[name='btnEstado']").each(function() {
            this.disabled = true;
        });

        var Tbl = document.getElementById("tablaprueba");
        var cantInd = document.getElementById('cantIndicadores').value;

        var tblBody = document.getElementById("tablapruebaBody");

        var tr = document.createElement("tr");

        var td1 = document.createElement("td");
        var td2 = document.createElement("td");
        var td3 = document.createElement("td");

        var input2 = document.createElement("input");
        var input3 = document.createElement("input");

        input2.type = "text";
        input2.id = "nameIndicador";
        input2.className = "form-control"; // set the CSS class

        input3.type = "number";
        input3.id = "ind_" + (parseInt(cantInd) + 1);
        input3.value = 0;
        input3.min = 1;
        input3.name = "indPuntos";
        input3.className = "form-control indPuntos";
        input3.setAttribute("onchange", "sumarPuntos(this.value,this.id);");

        td1.appendChild(document.createTextNode(parseInt(cantInd) + 1));
        td2.appendChild(input2);
        td3.appendChild(input3);
        tr.appendChild(td1);
        tr.appendChild(td2);
        tr.appendChild(td3);

        tblBody.appendChild(tr);

        document.getElementById('btnCrearInd').disabled = true;
        document.getElementById('btnAggInd').disabled = false;

    }

    function aggIndicador() {
        var cantInd = document.getElementById('cantIndicadores').value; //Cantidad de indicadores
        var puntosInd = document.getElementById('ind_' + (parseInt(cantInd) + 1)).value; //Obtener el valor de los puntos a crear del indicador

        var puntos = document.getElementById('sumaPuntos').value; // Suma de los puntos
        var nameInd = document.getElementById('nameIndicador').value; // Nombre del indicador

        if (puntos == 100 && nameInd != "" && puntosInd > 0) {

            document.getElementById('cargando').style.display = 'block';

            var datosInd = [];

            datosInd.push($(".filas td").map(function(e) {
                var lol = this.innerText == '' ? $(this).children('input').val() : this.innerText;

                return lol;
            }).get());

            var form = new FormData();
            form.append('datosInd', datosInd);
            form.append('newInd', nameInd);
            form.append('newIndPuntos', puntosInd);

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            /* xmlhttp.responseType = 'json'; */
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    if (xmlhttp.responseText === "OK") {
                        Swal.fire({
                            title: 'Exito',
                            text: 'Indicador agregado con exito.',
                            icon: 'success',
                            confirmButtonText: 'Ok',
                            allowOutsideClick: false,
                            showCloseButton: false
                        }).then((result) => {
                            // Read more about isConfirmed, isDenied below 
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                        //Ocultar el cargando
                        document.getElementById('cargando').style.display = 'none';
                    } else if (xmlhttp.responseText === "ERROR") {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al intentar actualizar y crear el nuevo indicador.' +
                                +'Intente nuevamente.',
                            icon: 'warning',
                            confirmButtonText: 'Ok',
                            allowOutsideClick: false,
                            showCloseButton: false
                        });
                    }




                }
            }
            xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/updateInd", true);
            xmlhttp.send(form);

        } else {
            Swal.fire({
                title: 'Advertencia',
                text: 'Para agregar un nuevo indicador, debes agregar el nombre y dar un puntaje a este. Luego debes hacer que la suma de los puntos sea igual a 100',
                icon: 'error',
                confirmButtonText: 'Ok',
                allowOutsideClick: false,
                showCloseButton: false
            });
        }

    }

    /* Agregar nuevo item a un indicador */
    function addNewItem() {
        document.getElementById('cargando').style.display = 'block';
        var id_indicador = document.getElementById('indicadorItem').value;
        var concepto = document.getElementById('conceptoItem').value;

        console.log(id_indicador + "\n" + concepto);

        if (id_indicador != "" && concepto != "") {
            Swal.fire({
                title: 'Advertencia',
                html: 'Esta seguro de crear un nuevo item con el concepto indicado:<br><strong>' + concepto + '</strong>',
                icon: 'info',
                confirmButtonText: 'Continuar',
                denyButtonText: 'Modificar concepto',
                showDenyButton: true,
                allowOutsideClick: false,
                showCloseButton: false,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    insertItem(id_indicador, concepto);
                } else if (result.isDenied) {

                }
            });
            document.getElementById('cargando').style.display = 'none';
        } else {
            Swal.fire({
                title: 'Advertencia',
                text: 'Para agregar un nuevo Item, debes seleccionar un Indicador, y especificar el concepto del Item',
                icon: 'error',
                confirmButtonText: 'Ok',
                allowOutsideClick: false,
                showCloseButton: false
            });
            document.getElementById('cargando').style.display = 'none';
        }

    }
    /* Funcion para mostrar los items de los indicadores seleccionados */
    function mostrarItems(id) {
        var form = new FormData();
        form.append('id_indicador', id);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        /* xmlhttp.responseType = 'json'; */
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                document.getElementById('cargarItemsXind').innerHTML = xmlhttp.responseText;

                document.getElementById('cargando').style.display = 'none';


            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/getItemXind", true);
        xmlhttp.send(form);
    }
    //Function para Cambiar el estado de un Item
    function cambiarEstadoItem(id, estado) {
        var id_item = document.getElementById('indicadorItem').value;
        document.getElementById('cargando').style.display = 'block';
        var form = new FormData();
        form.append('id_item', id);
        form.append('estado', estado);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        /* xmlhttp.responseType = 'json'; */
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {

                if (xmlhttp.responseText == 1) {

                    Swal.fire({
                        title: 'Exito',
                        text: 'El Item se ha Inhabilitado con exito',
                        icon: 'success',
                        confirmButtonText: 'Ok',
                        allowOutsideClick: false,
                        showCloseButton: false
                    }).then((result) => {
                        // Read more about isConfirmed, isDenied below 
                        if (result.isConfirmed) {
                            mostrarItems(id_item);
                        }
                    });
                } else if (xmlhttp.responseText == 2) {

                    Swal.fire({
                        title: 'Exito',
                        text: 'El Item se ha habilitado con exito',
                        icon: 'success',
                        confirmButtonText: 'Ok',
                        allowOutsideClick: false,
                        showCloseButton: false
                    }).then((result) => {
                        // Read more about isConfirmed, isDenied below 
                        if (result.isConfirmed) {
                            mostrarItems(id_item);
                        }
                    });
                } else if (xmlhttp.responseText == 3) {
                    Swal.fire({
                        title: 'Advertencia',
                        text: 'No puede Inhabilitar Items ya que existen auditorias por finalizar.',
                        icon: 'error',
                        confirmButtonText: 'Ok',
                        allowOutsideClick: false,
                        showCloseButton: false
                    });
                } else {

                    Swal.fire({
                        title: 'Error',
                        text: 'Ha ocurrido un error, al tratar de actualizar el estado del Item. La página se refrescara automaticamente despues' +
                            +'de dar clic al "OK", intente nuevamente realizar la operación.',
                        icon: 'warning',
                        confirmButtonText: 'Ok',
                        allowOutsideClick: false,
                        showCloseButton: false
                    }).then((result) => {
                        // Read more about isConfirmed, isDenied below 
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
                document.getElementById('cargando').style.display = 'none';



            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/estadoItem", true);
        xmlhttp.send(form);
    }
    /* Funcion para realizar el registro y alter table */
    function insertItem(id_indicador, concepto) {
        document.getElementById('cargando').style.display = 'block';
        var form = new FormData();
        form.append('id_indicador', id_indicador);
        form.append('concepto', concepto);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        /* xmlhttp.responseType = 'json'; */
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                if (xmlhttp.responseText == 0) {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se ha podido añadir un nuevo Item',
                        icon: 'warning',
                        confirmButtonText: 'Ok',
                        allowOutsideClick: false,
                        showCloseButton: false
                    });
                    document.getElementById('cargando').style.display = 'none';
                } else if (xmlhttp.responseText != 0 || xmlhttp.responseText != 2) {
                    Swal.fire({
                        title: 'Exito',
                        text: 'Se ha añadido con exito el nuevo Item, y se a actualizado la auditoría.',
                        icon: 'success',
                        confirmButtonText: 'Agregar una observación',
                        denyButtonText: 'Cerrar',
                        showDenyButton: true,
                        allowOutsideClick: false,
                        showCloseButton: false,
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            var opc = xmlhttp.responseText; //Id Item que se ha creado con exito.
                            insertObservaciónAfterItem(id_indicador, opc);
                        } else if (result.isDenied) {
                            location.reload();
                        }
                    });
                    document.getElementById('cargando').style.display = 'none';
                } else if (xmlhttp.responseText == 2) {
                    Swal.fire({
                        title: 'Advertencia',
                        text: 'Se ha añadido el Item, pero se genero un error al realizar la actualización en la evaluación.',
                        icon: 'warning',
                        confirmButtonText: 'Ok',
                        allowOutsideClick: false,
                        showCloseButton: false
                    });
                    document.getElementById('cargando').style.display = 'none';
                } else {
                    alert('Error inexperado');
                    document.getElementById('cargando').style.display = 'none';
                }

                document.getElementById('cargando').style.display = 'none';


            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/addItemXind", true);
        xmlhttp.send(form);
    }

    function cargarSeletecItems(id) {
        console.log('Id_indicador = ' + id);
        document.getElementById('cargarObsXitem').innerHTML = "";
        var form = new FormData();
        form.append('id_indicador', id);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.responseType = 'json';
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                var resp = xmlhttp.response;
                if ($("#ItemObs").text() != "") {
                    $("#ItemObs").empty();
                }
                $('#ItemObs').append($('<option>', {
                    value: '',
                    text: 'Seleccione un item'
                }));
                for (let j = 0; j < resp.length; j++) {
                    $('#ItemObs').append($('<option>', {
                        value: resp[j].id_item,
                        text: resp[j].concepto
                    }));

                }
            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/getItemXindObs", true);
        xmlhttp.send(form);
    }

    function mostrarObs(id) {
        console.log('Id_item: ' + id);
        document.getElementById('cargando').style.display = 'block';
        var form = new FormData();
        form.append('id_item', id);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        /* xmlhttp.responseType = 'json'; */
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                document.getElementById('cargarObsXitem').innerHTML = xmlhttp.responseText;

                document.getElementById('cargando').style.display = 'none';
                document.getElementById('btnCrearObs').disabled = false;

            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/getObsXitem", true);
        xmlhttp.send(form);
    }

    function addNewObs() {
        document.getElementById('cargando').style.display = 'block';


        var id_item = document.getElementById('ItemObs').value;
        var item = $("#ItemObs option:selected").text(); //Obtiene el texto del select seleccionado
        var obs = document.getElementById('conceptoObs').value;


        if (id_item != "" && obs != "") {
            document.getElementById('cargando').style.display = 'none';
            Swal.fire({
                title: 'Advertencia',
                html: 'Esta seguro de crear una nueva Observación al item seleccionado:<br><strong>Item: ' + item + '.</strong><br><strong>Observación: ' + obs + '</strong>',
                icon: 'info',
                confirmButtonText: 'Continuar',
                denyButtonText: 'Modificar Observación',
                showDenyButton: true,
                allowOutsideClick: false,
                showCloseButton: false,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    insertObs(id_item, obs);
                } else if (result.isDenied) {

                }
            });
        } else {
            Swal.fire({
                title: 'Error',
                html: '<strong>Para crear una nueva observación debe seleccionar un indicador, luego un item, y añadir la descripcion o observación.</strong>',
                icon: 'error',
                confirmButtonText: 'Ok',
                allowOutsideClick: false,
                showCloseButton: false
            });
            document.getElementById('cargando').style.display = 'none';
        }
    }

    function insertObservación() {


        $('#ObservacionesModal').modal({
            backdrop: 'static',
            keyboard: false
        }).show();
    }

    function insertObservaciónAfterItem(id_indicador, id_item) {
        document.getElementById('idItemAfter').value = id_item;
        $('#ObservacionesModalAfterItem').modal({
            backdrop: 'static',
            keyboard: false
        }).show();
    }

    function addNewObsAfter() {

        var id_item = document.getElementById('idItemAfter').value;
        var obs = document.getElementById('conceptoObsAfter').value;


        if (id_item != "" && obs != "") {
            insertObs(id_item, obs);
        } else {
            Swal.fire({
                title: 'Error',
                html: '<strong>Para crear una nueva observación debe añadir la descripcion o observación.</strong>',
                icon: 'error',
                confirmButtonText: 'Ok',
                allowOutsideClick: false,
                showCloseButton: false
            });
            document.getElementById('cargando').style.display = 'none';
        }
    }

    function insertObs(id_item, obs) {
        var form = new FormData();
        form.append('id_item', id_item);
        form.append('obs', obs);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        /* xmlhttp.responseType = 'json'; */
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                if (xmlhttp.responseText == 0) {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se ha podido añadir la nueva observación',
                        icon: 'warning',
                        confirmButtonText: 'Ok',
                        allowOutsideClick: false,
                        showCloseButton: false
                    });
                } else if (xmlhttp.responseText == 1) {
                    Swal.fire({
                        title: 'Exito',
                        text: 'Se ha añadido con exito la nueva Observacion',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                        showCloseButton: false,
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }

                document.getElementById('cargando').style.display = 'none';


            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/addObsXitem", true);
        xmlhttp.send(form);
    }

    function cambiarEstadoObs(id, estado) {
        var id_item = document.getElementById('ItemObs').value;
        var form = new FormData();
        form.append('id_obs', id);
        form.append('estado', estado);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        /* xmlhttp.responseType = 'json'; */
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {

                if (xmlhttp.responseText == 1) {

                    Swal.fire({
                        title: 'Exito',
                        text: 'La Observación se ha Inhabilitado con exito',
                        icon: 'success',
                        confirmButtonText: 'Ok',
                        allowOutsideClick: false,
                        showCloseButton: false
                    }).then((result) => {
                        // Read more about isConfirmed, isDenied below 
                        if (result.isConfirmed) {
                            mostrarObs(id_item);
                        }
                    });
                } else if (xmlhttp.responseText == 2) {

                    Swal.fire({
                        title: 'Exito',
                        text: 'La Observación se ha habilitado con exito',
                        icon: 'success',
                        confirmButtonText: 'Ok',
                        allowOutsideClick: false,
                        showCloseButton: false
                    }).then((result) => {
                        // Read more about isConfirmed, isDenied below 
                        if (result.isConfirmed) {
                            mostrarObs(id_item);
                        }
                    });
                } else if (xmlhttp.responseText == 3) {
                    Swal.fire({
                        title: 'Advertencia',
                        text: 'No puede Inhabilitar Observaciones ya que existen auditorias por finalizar.',
                        icon: 'error',
                        confirmButtonText: 'Ok',
                        allowOutsideClick: false,
                        showCloseButton: false
                    });
                } else {

                    Swal.fire({
                        title: 'Error',
                        text: 'Ha ocurrido un error, al tratar de actualizar el estado de la Observación. La página se refrescara automaticamente despues' +
                            +'de dar clic al "OK", intente nuevamente realizar la operación.',
                        icon: 'warning',
                        confirmButtonText: 'Ok',
                        allowOutsideClick: false,
                        showCloseButton: false
                    }).then((result) => {
                        // Read more about isConfirmed, isDenied below 
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
                document.getElementById('cargando').style.display = 'none';



            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/estadoObs", true);
        xmlhttp.send(form);
    }
</script>
<?php $this->load->view('Auditoria_contact/footer'); ?>
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2({
            placeholder: 'Seleccione un agente',
            width: '100%'
        });
        $('.indicadores').select2({
            placeholder: 'Seleccione una opción',
            width: '100%'
        });
        $('.items').select2({
            placeholder: 'Seleccione una opción',
            width: '100%'
        });
    });
</script>