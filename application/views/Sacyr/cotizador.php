<!--
    Autor:Sergio Galvis
    Fecha: 08/11/2022
-->
<?php $this->load->view('Sacyr/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <div class="loader" id="cargando"></div>
    <section class="content">
        <div class="card">
            <div class="card-header" align="center">
                <h3><strong>Cotizador Sacyr</strong></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                        <label class="control-label">Placa:</label>
                        <input placeholder="Escriba aquí la placa" class="form-control" type="text" id="placa" name="placa" onchange="cargarClase();">
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                        <label class="control-label">Orden de Trabajo:</label>
                        <input placeholder="Escriba aquí la orden de trabajo" class="form-control" type="text" id="ordenT" name="ordenT">
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                        <label class="control-label">Descripción modelo:</label>
                        <input disabled placeholder="Modelo del vehículo" class="form-control" type="text" id="descVh" name="descVh">
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                        <label class="control-label">Kilometraje actual:</label>
                        <input placeholder="Escriba aquí el kilometraje" class="form-control" type="number" id="kmVh" name="kmVh">
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-md-12 container table-responsive">
                        <table id="tablaItem" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10%;" scope="col">CÓDIGO</th>
                                    <th style="width: 20%;" scope="col">DESCRIPCIÓN</th>
                                    <th style="width: 10%;" scope="col">CANTIDAD</th>
                                    <th style="width: 10%;" scope="col">DESCUENTO %</th>
                                    <th style="width: 10%;" scope="col">VALOR UNITARIO</th>
                                    <th style="width: 10%;" scope="col">CANT CONTRATO</th>
                                    <th style="width: 10%;" scope="col">VALOR DESC</th>
                                    <th class="d-none" style="width: 10%;" scope="col">VALOR SIN DESC</th>
                                    <th style="width: 10%;" scope="col">DISPONIBILIDAD</th>
                                    <th style="width: 10%;" scope="col">BODEGA</th>
                                    <th style="width: 10%;" scope="col">VALOR TOTAL</th>
                                    <th style="width: 10%;" scope="col">AUTORIZACIÓN</th>
                                    <th style="width: 10%;" scope="col">ELIMINAR</th>
                                </tr>
                            </thead>
                            <tbody id="cotizadorItem">

                            </tbody>
                            <tfoot>
                                <tr style=" background-color: #80808063;">
                                    <th colspan="2"></th>
                                    <th id="cellCantidadTotal"></th>
                                    <th></th>
                                    <th></th>
                                    <th colspan="6">
                                        <table style="width: 100% ;">
                                            <tr>
                                                <th style="width: 50% ;">Subtotal repuestos</th>
                                                <th style="width: 50% ;" id="subtotalRepuestos"></th>
                                            </tr>
                                            <tr>
                                                <th style="width: 50% ;">Subtotal descuento</th>
                                                <th style="width: 50% ;" id="subTotalDescuentos"></th>
                                            </tr>
                                            <tr>
                                                <th style="width: 50% ;">Subtotal</th>
                                                <th style="width: 50% ;" id="subTotalRepuestosDesc"></th>
                                            </tr>
                                            <tr class="d-none">
                                                <th style="width: 50% ;">Total Sin Iva</th>
                                                <th style="width: 50% ;" id="cellValorTotalSinIVA"></th>
                                            </tr>
                                            <tr class="d-none">
                                                <th style="width: 50% ;">Total Con Iva</th>
                                                <th style="width: 50% ;" id="cellValorTotal"></th>

                                            </tr>
                                        </table>
                                    </th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row text-center">
                    <div id="tablaManoObra" class="col-md-6 container table-responsive">
                        <label class="control-label">Mano de Obra:</label>
                        <table id="tablaItemMano" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Operación</th>
                                    <th>Horas</th>
                                    <th>Valor*Hora</th>
                                    <th>Valor</th>
                                    <th>Autorización</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="tBodyManoObra">

                            </tbody>
                            <tfoot>
                                <tr style=" background-color: #80808063;">
                                    <th></th>
                                    <th id="horasManoObra"></th>
                                    <th></th>
                                    <th id="valor_mano"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    <div id="tablaToT" class="col-md-6 container table-responsive">
                        <label class="control-label">ToT:</label>
                        <table id="tablaItemToT" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Operación</th>
                                    <th>Valor</th>
                                    <th>Autorización</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="tBodyToT">

                            </tbody>
                            <tfoot>
                                <tr style=" background-color: #80808063;">
                                    <th></th>
                                    <th id="valor_ToT"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label class="control-label">Observación:</label>
                        <textarea class="form-control bloquear-paste" id="obs" rows="4" name="obs" placeholder="Escriba aquí las observaciones" onkeydown="noPuntoComa( event )"></textarea>
                    </div>
                    <div class="col-6">
                        <table style="width:100%" class="table table-sm table-bordered">
                            <tr>
                                <th style="text-align: right;width:50%;">Subtotal Repuestos</th>
                                <td style="text-align: right;width:50%;" id="subtotalRepuestosT"></td>
                            </tr>
                            <tr>
                                <th style="text-align: right;width:50%;">Subtotal Mano Obra</th>
                                <td style="text-align: right;width:50%;" id="subtotalManoT"></td>
                            </tr>
                            <tr>
                                <th style="text-align: right;width:50%;">Subtotal ToT</th>
                                <td style="text-align: right;width:50%;" id="subtotalToT_T"></td>
                            </tr>
                            <tr>
                                <th style="text-align: right;width:50%;">Subtotal</th>
                                <td style="text-align: right;width:50%;" id="subtotal"></td>
                            </tr>
                            <tr>
                                <th style="text-align: right;width:50%;">IVA</th>
                                <td style="text-align: right;width:50%;" id="total_iva"></td>
                            </tr>
                            <tr style="background-color: #80808063;">
                                <th style="text-align: right;width:50%;">Toal</th>
                                <td style="text-align: right;width:50%;" id="totalCotizacion"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button style="float: right;" onclick="guardarCotizacion()" class="btn btn-warning">Guardar</button>
                            <button class="btn btn-primary " id="btnMostrar" onclick="mostrarItems()">Mostrar Items</button>
                            <button onclick="addManoObra()" class="btn btn-info">Agregar Mano de Obra</button>
                            <button onclick="addTot()" class="btn btn-info">Agregar ToT</button>

                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
</div>

<!--Modal para mostrar los Items que se pueden agregar-->
<div class="modal" tabindex="-1" id="modalRepuestos" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Busqueda de Repuestos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row table-responsive">
                    <div id="busqueda" class="row text-center d-none">
                        <div class="col-md-12 container table-responsive">
                            <table id="tablaCotizador" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">CÓDIGO</th>
                                        <th scope="col">DESCRIPCIÓN</th>
                                        <th scope="col">BUSQUEDA</th>
                                        <th scope="col">VALOR UNITARIO</th>
                                        <th scope="col">VALOR CONTRATO</th>
                                        <th scope="col">CANTIDAD CONTRATO</th>
                                        <th scope="col">DISPONIBLES</th>
                                        <th scope="col">BODEGA</th>
                                        <th scope="col">OPCIÓN</th>
                                    </tr>
                                </thead>
                                <tbody id="cotizador">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('Sacyr/footer'); ?>

<script>
    $(document).ready(function() {
        $("#buscar_items").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#menu_items li").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        loadReportSacyr();


        $('[data-toggle="tooltip"]').tooltip({
            delay: {
                "show": 500,
                "hide": 100
            },
            html: true,
            template: '<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
        });

        evitarPegarTextAndInput();

    });

    function loadReportSacyr() {
        document.getElementById('cargando').style.display = 'block';
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                document.getElementById('cotizador').innerHTML = xmlhttp.responseText;
                loadDataTable("tablaCotizador");
                document.getElementById('cargando').style.display = 'none';
            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>Sacyr/loadCotizador", true);
        xmlhttp.send();
    }


    function loadDataTable(name) {
        var table = $('#' + name).DataTable({
            "paging": true,
            "pageLength": 7,
            "lengthChange": false,
            "searching": true,
            "columnDefs": [{
                "searchable": false,
                "targets": [1, 3, 4, 5, 6, 7]
            }],
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla =(",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Visibilidad"
                }
            }
        });
    }
    /* Funcion para agregar Items, validar si el item ya se agrego */
    function addItem(codigo, descripcion, v_unitario, v_contrato, c_contrato, disponible, bodega) {

        if (document.getElementById(`${codigo}_tr`) == null) {

            var body = document.getElementById('cotizadorItem');

            var bandera = body.childElementCount;

            if (bandera > 0) {
                var numFila = parseInt(body.lastChild.id.replace('_tr', ''));
                bandera = (numFila + 1);
            }



            var fila = document.createElement("tr");
            fila.id = `${bandera}_tr`;
            fila.classList.add('fila_cotizador');

            var column_1 = document.createElement("td");
            var column_2 = document.createElement("td");
            var column_3 = document.createElement("td");
            var column_4 = document.createElement("td");
            var column_5 = document.createElement("td");
            var column_6 = document.createElement("td");
            var column_7 = document.createElement("td");
            var column_8 = document.createElement("td");
            var column_9 = document.createElement("td");
            var column_10 = document.createElement("td");
            var column_11 = document.createElement("td");
            var column_12 = document.createElement("td");
            var column_13 = document.createElement("td");

            column_1.appendChild(document.createTextNode(codigo));
            fila.appendChild(column_1);

            column_2.appendChild(document.createTextNode(descripcion));
            fila.appendChild(column_2);

            /* Creando Input para agregar la cantidad */
            var inputCantidad = document.createElement('input');
            inputCantidad.type = 'number';
            inputCantidad.id = `${bandera}`;
            inputCantidad.classList.add('cantidadTotal');
            inputCantidad.classList.add('form-control');
            inputCantidad.value = 1;
            inputCantidad.min = 1;
            inputCantidad.style.width = '70px';
            inputCantidad.style.display = 'inline';

            inputCantidad.setAttribute("onchange", `calcularValorTotal("${bandera}")`);

            column_3.appendChild(inputCantidad);
            fila.appendChild(column_3);

            /* Creando Input para agregar el descuento del item */
            var inputDesc = document.createElement('input');
            inputDesc.type = 'number';
            inputDesc.id = `${bandera}_d`;
            inputDesc.classList.add('form-control');
            inputDesc.value = '0';
            inputDesc.min = 0;
            inputDesc.max = 100;
            inputDesc.style.width = '70px';
            inputDesc.style.display = 'inline';

            inputDesc.setAttribute("onchange", `validarDescuento("${bandera}");calcularValorTotal("${bandera}")`);

            column_4.appendChild(inputDesc);
            fila.appendChild(column_4);

            if (!parseInt(v_contrato) > 0) {
                column_5.appendChild(document.createTextNode(v_unitario));
                column_5.id = `${bandera}_v`;
                fila.appendChild(column_5);

                column_9.appendChild(document.createTextNode('N/A C'));
                column_9.id = `${bandera}_cto`;
                fila.appendChild(column_9);


            } else {
                column_5.appendChild(document.createTextNode(v_contrato));
                column_5.id = `${bandera}_v`;
                fila.appendChild(column_5);

                column_9.appendChild(document.createTextNode(c_contrato));
                fila.appendChild(column_9);

            }

            column_10.appendChild(document.createTextNode(0));
            column_10.id = `${bandera}_desc`;
            column_10.classList.add('v_desc');
            /* column_10.classList.add('d-none'); */
            fila.appendChild(column_10);

            column_6.appendChild(document.createTextNode('--'));
            column_6.id = `${bandera}_t`;
            column_6.classList.add('valor_total');
            column_6.classList.add('d-none');
            fila.appendChild(column_6);



            column_12.appendChild(document.createTextNode(disponible));
            column_12.id = `${bandera}_unid`;
            fila.appendChild(column_12);
            column_13.appendChild(document.createTextNode(bodega));
            column_13.id = `${bandera}_bod`;
            fila.appendChild(column_13);

            column_11.appendChild(document.createTextNode(0));
            column_11.id = `${bandera}_desc_T`;
            column_11.classList.add('v_desc_T');
            /* column_11.classList.add('d-none'); */
            fila.appendChild(column_11);


            /* Creando Input para agregar la autorización o checklist */
            var inputCheck = document.createElement('input');
            inputCheck.type = 'checkbox';
            inputCheck.id = `${bandera}_check`;
            inputCheck.classList.add('form-control');
            inputCheck.value = 1;
            inputCheck.checked = true;
            inputCheck.disabled = true;
            /* inputCheck.setAttribute("onclick", `validar_check("${bandera}")`); */

            column_7.appendChild(inputCheck);
            fila.appendChild(column_7);

            /* Creando boton para eliinar la fila seleccionada */
            var btnEliminar = document.createElement('button');
            btnEliminar.type = 'button';
            btnEliminar.id = `${bandera}`;
            btnEliminar.classList.add('btn');
            btnEliminar.classList.add('btn-danger');
            btnEliminar.textContent = 'Eliminar';
            btnEliminar.setAttribute("onclick", `eliminarItem("${bandera}")`);

            column_8.appendChild(btnEliminar);
            fila.appendChild(column_8);




            body.appendChild(fila);
        } else {
            Swal.fire({
                title: 'Error',
                text: 'El Item que esta intentado agregar ya se encuentra en la cotización',
                icon: 'error'
            });
        }

        calcularValorTotal(bandera);


    }
    /* Funcion validar Check */
    function validar_check(codigo) {
        var validar = document.getElementById(`${codigo}_check`);
        if (validar.checked == true) {
            document.getElementById(`${codigo}_check`).value = 1;
        } else {
            document.getElementById(`${codigo}_check`).value = 0;
        }
        sumarValoresTotales();
    }

    /* Funcion validar Check */
    function validar_checkManoObra(codigo) {
        var validar = document.getElementById(`v_hora_${codigo}_ck`);
        if (validar.checked == true) {
            validar.value = 1;
        } else {
            validar.value = 0;
        }
        calcularValorTotal_Hora_Mano(codigo);
        calcularTotalHorasManoObra();
        calcularSumaValorTotalManoObra();
    }

    /* Funcion validar Check */
    function validar_checkToT(codigo) {
        var validar = document.getElementById(`v_tot_${codigo}_ck`);
        if (validar.checked == true) {
            validar.value = 1;
        } else {
            validar.value = 0;
        }
        calcularValorTotal_ToT();

    }
    /* Funcion para calcular el valor total segun la cantidad de los items */
    function calcularValorTotal(codigo) {
        var validarCantidad = document.getElementById(`${codigo}`).value;
        if (validarCantidad == 0 || validarCantidad == "") {
            document.getElementById(`${codigo}`).value = 1;
        }
        var cantidad = (validarCantidad == 0 || validarCantidad == "") ? 1 : validarCantidad;
        var v_unitario = formatDeleteDots(document.getElementById(`${codigo}_v`).innerText);

        var v_total = document.getElementById(`${codigo}_t`);
        var p_descuento = document.getElementById(`${codigo}_d`).value;

        var total = 0;

        if (cantidad != "") {
            if (p_descuento != "" && p_descuento <= 100) {
                total = (parseFloat(v_unitario) * parseFloat(cantidad));
                var descuentoReal = Math.ceil(total * parseFloat(p_descuento / 100));
                //Valor de descuento realizado al total del articulo
                document.getElementById(`${codigo}_desc`).innerText = formatAddDots(Math.ceil(descuentoReal));
                document.getElementById(`${codigo}_desc_T`).innerText = formatAddDots(Math.ceil(total));
                var num_format = formatAddDots(total - descuentoReal);
                v_total.innerText = num_format;

            } else {

                var num_format = formatAddDots(parseFloat(v_unitario) * parseFloat(cantidad));
                v_total.innerText = num_format;

            }

        } else {
            v_total.innerText = '--'
        }

        sumarValoresTotales();
    }

    function validarDescuento(codigo) {
        var p_descuento = document.getElementById(`${codigo}_d`).value;
        if (parseInt(p_descuento) > 100) {
            document.getElementById(`${codigo}_d`).value = 0;
        }
    }


    /* Funcion para eliminar o quitar las filas en caso de error */
    function eliminarItem(codigo) {
        $(`#${codigo}_tr`).closest("tr").remove();
        sumarValoresTotales();
    }
    /* Funcion para calcular la suma total de los items agregando el 19% del IVA */
    function sumarValoresTotales() {
        var total = 0;
        var sumaTotal = $(".valor_total").map(function() {

            if (this.innerText != "--" && this.innerText != 'NaN') {
                var idValor = this.id;
                var idValorCheck = idValor.replace('_t', '_check');
                var checked = document.getElementById(idValorCheck);
                if (checked.checked) {
                    var valor = formatDeleteDots(this.innerText);
                    return total += parseInt(valor);
                }
            }

        }).get();
        var valorTotal = formatAddDots(Math.ceil(total + (total * 0.19)));
        document.getElementById('cellValorTotalSinIVA').textContent = formatAddDots(Math.ceil(total));
        document.getElementById('cellValorTotal').textContent = valorTotal;



        var totalC = 0;
        var sumaTotalC = $(".cantidadTotal").map(function() {
            var idValor = this.id;
            var checked = document.getElementById(idValor + '_check');
            if (checked.checked) {
                if (this.value != "") {
                    return totalC += parseInt(this.value);
                }
            }



        }).get();

        document.getElementById('cellCantidadTotal').textContent = Math.ceil(totalC).toFixed(0);


        var subTotalDescuento = 0;
        var sumaTotalC = $(".v_desc ").map(function() {
            var idValor = this.id;
            var checked = document.getElementById(idValor.replace('_desc', '_check'));
            if (checked.checked) {
                if (this.textContent != "") {
                    return subTotalDescuento += parseInt(formatDeleteDots(this.textContent));
                }
            }



        }).get();

        document.getElementById('subTotalDescuentos').textContent = formatAddDots(Math.ceil(subTotalDescuento).toFixed(0));


        var subTotalRepuesto = 0;
        var sumaTotalC = $(".v_desc_T").map(function() {
            var idValor = this.id;
            var checked = document.getElementById(idValor.replace('_desc_T', '_check'));
            if (checked.checked) {
                if (this.textContent != "") {
                    return subTotalRepuesto += parseInt(formatDeleteDots(this.textContent));
                }
            }



        }).get();

        document.getElementById('subtotalRepuestos').textContent = formatAddDots(Math.ceil(subTotalRepuesto).toFixed(0));

        document.getElementById('subTotalRepuestosDesc').textContent = formatAddDots(Math.ceil(subTotalRepuesto - subTotalDescuento));

        sumaTotalCotizacion();

    }
    /* Funcion para Mostrar el contendor de la tabla de busquedad de Items */
    function mostrarItems() {
        $("#busqueda").removeClass("d-none");
        $('#modalRepuestos').modal('show');
    }
    const valorHora = 65000; //Valor Hora para ToT y mano de obra

    function addManoObra() {
        var bandera = validarFilasManoObra();
        if (bandera === false) {
            Swal.fire({
                title: 'Advertencia!',
                text: 'Para agregar un nuevo item a ToT debes completar los campos de Operacion y Valor',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        } else {
            addManoObraTable(bandera);

        }
    }


    function validarFilasManoObra() {

        var tabla = document.getElementById('tablaManoObra');

        /* if (tabla.classList.contains('d-none')) {
            tabla.classList.remove('d-none');
        } */

        var total = [];
        $(".fila_mano").map(function() {

            return total.push(this.id);

        }).get();
        var bandera = total.length;

        if (total.length > 0) {
            var filas_mano_obra = new Array();
            filas_mano_obra.push($(`#${total[total.length -1]} td`).map(function() {
                if (this.innerText == "") {
                    return this.firstChild.value;
                } else {
                    return this.innerText;
                }
            }).get());
            var filas_mano_obra_array = filas_mano_obra[0];
            if (filas_mano_obra_array[0] == "" || filas_mano_obra_array[1] == "") {
                return false;
                /* Swal.fire({
                    title: 'Advertencia!',
                    text: 'Para agregar un nuevo item a mano de obra debes completar los campos de Operacion y Cantidad de horas',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }); */
            } else {
                return bandera;
                /* addManoObraTable(bandera); */
            }

        } else {
            return bandera;
            /* addManoObraTable(bandera); */
        }

    }

    function addManoObraTable(bandera) {
        var body = document.getElementById('tBodyManoObra');

        var fila = document.createElement("tr");
        fila.id = 'fila_mano_obra' + bandera;
        fila.classList.add('fila_mano');

        var column_1 = document.createElement("td");
        var column_2 = document.createElement("td");
        var column_3 = document.createElement("td");
        var column_4 = document.createElement("td");
        var column_5 = document.createElement("td");
        var column_6 = document.createElement("td");

        /* Creando Input para agregar el codigo de mano de obra */
        var textOperacion = document.createElement('textarea');
        textOperacion.id = 'textOperacionMano' + bandera;
        textOperacion.classList.add('form-control', 'bloquear-paste');
        textOperacion.placeholder = 'Escriba aquí la descripción de la operación';
        textOperacion.maxLength = 300;
        textOperacion.setAttribute("onkeydown", `noPuntoComa(event);`);



        column_1.appendChild(textOperacion);
        fila.appendChild(column_1);

        /* Creando Input para agregar la cantidad */
        var inputHoras = document.createElement('input');
        inputHoras.type = 'number';
        inputHoras.id = `cantHora_${bandera}`;
        inputHoras.classList.add('cantidadTotal_Horas');
        inputHoras.classList.add('form-control');
        inputHoras.value = 0.5;
        inputHoras.min = 0;
        inputHoras.step = 0.25;
        inputHoras.style.width = '70px';
        inputHoras.style.display = 'inline';
        inputHoras.setAttribute("onchange", `calcularValorTotal_Hora_Mano("${bandera}")`);

        column_2.appendChild(inputHoras);
        fila.appendChild(column_2);

        column_3.appendChild(document.createTextNode(formatAddDots(valorHora)));
        fila.appendChild(column_3);

        column_4.appendChild(document.createTextNode(0));
        column_4.id = `v_hora_${bandera}`;
        column_4.classList.add('valor_total_hora');
        fila.appendChild(column_4);

        /* Creando Input para agregar la autorización o checklist */
        var inputCheck = document.createElement('input');
        inputCheck.type = 'checkbox';
        inputCheck.id = `v_hora_${bandera}_ck`;
        inputCheck.classList.add('form-control');
        inputCheck.value = 1;
        inputCheck.checked = true;
        inputCheck.disabled = true;
        /* inputCheck.setAttribute("onclick", `validar_checkManoObra("${bandera}")`); */

        column_6.appendChild(inputCheck);
        fila.appendChild(column_6);

        /* Creando boton para eliinar la fila seleccionada */
        var btnEliminar = document.createElement('button');
        btnEliminar.type = 'button';
        btnEliminar.id = `${bandera}`;
        btnEliminar.classList.add('btn');
        btnEliminar.classList.add('btn-danger');
        btnEliminar.textContent = 'Eliminar';
        btnEliminar.setAttribute("onclick", `eliminarItemManoObra("${bandera}")`);

        column_5.appendChild(btnEliminar);
        fila.appendChild(column_5);

        body.appendChild(fila);

        calcularValorTotal_Hora_Mano(bandera);

        evitarPegarTextAndInput();
    }

    function addTot() {
        var bandera = validarFilasToT();
        if (bandera === false) {
            Swal.fire({
                title: 'Advertencia!',
                text: 'Para agregar un nuevo item a ToT debes completar los campos de Operacion y Valor',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        } else {
            addToTable(bandera);
        }


    }

    function validarFilasToT() {
        var tabla = document.getElementById('tablaToT');

        /* if (tabla.classList.contains('d-none')) {
            tabla.classList.remove('d-none');
        } */

        var total = [];
        $(".fila_ToT").map(function() {

            return total.push(this.id);

        }).get();

        var bandera = total.length;
        if (total.length > 0) {
            var filas_ToT = new Array();
            filas_ToT.push($(`#${total[total.length -1]} td`).map(function() {
                if (this.innerText == "") {
                    return this.firstChild.value;
                } else {
                    return this.innerText;
                }
            }).get());
            var filas_ToT_array = filas_ToT[0];
            if (filas_ToT_array[0] == "" || filas_ToT_array[1] <= 0) {
                return false;
                /*  Swal.fire({
                     title: 'Advertencia!',
                     text: 'Para agregar un nuevo item a ToT debes completar los campos de Operacion y Valor',
                     icon: 'warning',
                     confirmButtonText: 'Ok'
                 }); */
            } else {
                return bandera;
                /* addToTable(bandera); */
            }

        } else {
            return bandera;
            /* addToTable(bandera); */
        }
    }

    function addToTable(bandera) {


        var body = document.getElementById('tBodyToT');

        var fila = document.createElement("tr");
        fila.id = 'fila_ToT' + bandera;
        fila.classList.add('fila_ToT');

        var column_1 = document.createElement("td");
        var column_2 = document.createElement("td");
        var column_3 = document.createElement("td");
        var column_4 = document.createElement("td");

        /* Creando Input para agregar el codigo de mano de obra */
        var textOperacion = document.createElement('textarea');
        textOperacion.id = '';
        textOperacion.classList.add('form-control', 'bloquear-paste');
        textOperacion.placeholder = 'Escriba aquí la descripción de la operación';
        textOperacion.maxLength = 300;
        textOperacion.setAttribute("onkeydown", `noPuntoComa(event);`);


        column_1.appendChild(textOperacion);
        fila.appendChild(column_1);

        /* Creando Input para agregar el valor del item */
        var inputValorToT = document.createElement('input');
        inputValorToT.type = 'text';
        inputValorToT.id = `v_${bandera}_ToT`;
        inputValorToT.classList.add('form-control');
        inputValorToT.classList.add('valor_total_ToT');
        inputValorToT.value = 0;
        inputValorToT.min = 0;
        inputValorToT.style.width = '120px';
        inputValorToT.style.display = 'inline';
        inputValorToT.setAttribute("onkeyup", `isNumero(this)`);
        /* inputValorToT.setAttribute("onchange", `calcularValorTotal_ToT()`); */

        column_2.appendChild(inputValorToT);
        fila.appendChild(column_2);

        /* Creando Input para agregar la autorización o checklist */
        var inputCheck = document.createElement('input');
        inputCheck.type = 'checkbox';
        inputCheck.id = `v_tot_${bandera}_ck`;
        inputCheck.classList.add('form-control');
        inputCheck.value = 1;
        inputCheck.checked = true;
        inputCheck.disabled = true;
        /* inputCheck.setAttribute("onclick", `validar_checkToT("${bandera}")`); */

        column_4.appendChild(inputCheck);
        fila.appendChild(column_4);

        /* Creando boton para eliinar la fila seleccionada */
        var btnEliminar = document.createElement('button');
        btnEliminar.type = 'button';
        btnEliminar.id = `${bandera}`;
        btnEliminar.classList.add('btn');
        btnEliminar.classList.add('btn-danger');
        btnEliminar.textContent = 'Eliminar';
        btnEliminar.setAttribute("onclick", `eliminarItemToT("${bandera}")`);

        column_3.appendChild(btnEliminar);
        fila.appendChild(column_3);


        body.appendChild(fila);

        calcularValorTotal_ToT();

        evitarPegarTextAndInput();
    }

    function cargarClase() {
        var placa = document.getElementById('placa').value;
        if (placa != "" && placa.length >= 6) {
            document.getElementById('cargando').style.display = 'block';
            var datos = new FormData();
            datos.append('placa', placa);
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.responseType = 'json';
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    /* var resp = xmlhttp.responseText; */
                    var resp = xmlhttp.response;
                    if (resp != null) {
                        document.getElementById('descVh').value = resp[0].des_modelo;
                        document.getElementById('descVh').disabled = true;
                    } else {
                        document.getElementById('descVh').disabled = false;
                    }
                    document.getElementById('cargando').style.display = 'none';

                }
            }

            xmlhttp.open("POST", "<?= base_url() ?>Cotizador/cargarClasePlaca", true);
            xmlhttp.send(datos);
        } else {
            Swal.fire({
                title: 'Advertencia!',
                text: 'El campo se encuentra vacio o debe tener al menos 6 caracteres',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
    }

    /* Funcion para calcular el valor total segun la cantidad de los items */
    function calcularValorTotal_Hora_Mano(codigo) {
        var cantidad = document.getElementById(`cantHora_${codigo}`).value;
        var v_total_horas = Math.ceil(cantidad * valorHora);
        document.getElementById(`v_hora_${codigo}`).innerText = formatAddDots(v_total_horas);

        calcularTotalHorasManoObra();
        calcularSumaValorTotalManoObra();
    }

    function calcularSumaValorTotalManoObra() {
        var totalC = 0;
        var sumaTotalC = $(".valor_total_hora").map(function() {
            var idCheck = this.id;
            var checked = document.getElementById(idCheck + '_ck');
            if (checked.checked) {
                return totalC += parseFloat(formatDeleteDots(this.innerText));
            }



        }).get();

        document.getElementById('valor_mano').textContent = formatAddDots(Math.ceil(totalC));

        sumaTotalCotizacion();

    }

    function calcularTotalHorasManoObra() {
        var totalC = 0;
        var sumaTotalC = $(".cantidadTotal_Horas").map(function() {
            var idChecked = this.id.replace('cantHora', 'v_hora');
            var checked = document.getElementById(idChecked + '_ck');
            if (checked.checked) {
                if (this.value != "") {
                    return totalC += parseFloat(this.value);
                }
            }



        }).get();

        document.getElementById('horasManoObra').textContent = totalC;
    }

    /* Funcion para verifcar si el campo llenado es numero */
    function isNumero(input) {
        var num = input.value.replace(/\./g, '');
        if (!isNaN(num)) {
            num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1.');
            num = num.split('').reverse().join('').replace(/^[\.]/, '');
            input.value = num;

            calcularValorTotal_ToT();
        } else {
            Swal.fire({
                title: 'Advertencia!',
                text: 'Solo se permiten valores numericos',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
            input.value = 0;
        }
    }

    function calcularValorTotal_ToT() {
        var totalC = 0;
        var sumaTotalC = $(".valor_total_ToT").map(function() {
            var idCheck = this.id;
            var idCheck = idCheck.replace('v_', 'v_tot_');
            idCheck = idCheck.replace('ToT', 'ck');
            var checked = document.getElementById(idCheck);
            if (checked.checked) {
                if (this.value != "") {
                    return totalC += parseFloat(formatDeleteDots(this.value));
                }
            }

        }).get();


        document.getElementById('valor_ToT').textContent = formatAddDots(Math.ceil(totalC));
        sumaTotalCotizacion();
    }

    /* Funcion para eliminar o quitar las filas en caso de error */
    function eliminarItemManoObra(codigo) {
        $(`#fila_mano_obra${codigo}`).closest("tr").remove();
        calcularSumaValorTotalManoObra();
        calcularTotalHorasManoObra();
    }

    /* Funcion para eliminar o quitar las filas en caso de error */
    function eliminarItemToT(codigo) {
        $(`#fila_ToT${codigo}`).closest("tr").remove();
        calcularValorTotal_ToT();
    }

    /* Funcion para guardar los items de la cotizacion */
    function guardarCotizacion() {
        //Obtenemos los valores principales del formulario a enviar
        let placa = document.getElementById('placa').value;
        let descModelo = document.getElementById('descVh').value;
        let km_actual = document.getElementById('kmVh').value;
        let obs = document.getElementById('obs').value;
        let ordenT = document.getElementById('ordenT').value;

        let isFilasToTVacias = validarFilasToT();
        let isFilasManoObraVacias = validarFilasManoObra();

        var totalFilasItems = [];
        $(".fila_cotizador").map(function() {

            return totalFilasItems.push(this.id);

        }).get();
        //Validación de placa / DescModelo / km 
        let validacion_1 = (placa != "" && descModelo != "" && km_actual != "" && placa.length >= 6);
        //Validacion de filas de mano de obra
        let validacion_2 = isFilasManoObraVacias != false;
        //Validacion de filas de ToT
        let validacion_3 = isFilasToTVacias != false;
        //Validcaion de filas de repuestos
        let validacion_4 = totalFilasItems.length; // Si Validacion es > 0 las filas de mano de obra debe tener al menos una...
        //Validacion de observacion...
        let validacion_5 = (obs != "");

        var opciones = [validacion_1, validacion_2, validacion_3, validacion_4, validacion_5];

        if (validacion_1 && validacion_5) {
            switch (true) {
                case (validacion_4 > 0 && validacion_2):
                    if ((validacion_2 == false)) {
                        Swal.fire({
                            title: 'Advertencia!',
                            html: '<p><strong>Nota:</strong> Validar que no tenga campos vacios en las casillas de Mano de Obra</p>',
                            icon: 'warning',
                            confirmButtonText: 'Ok',
                            allowEscapeKey: false,
                        });
                        break;
                    }
                    EnviarDatosCotizacion(placa, descModelo, km_actual, obs,ordenT);
                    break;

                case (validacion_4 > 0 && (validacion_2 == false)):
                    Swal.fire({
                        title: 'Advertencia!',
                        html: '<p><strong>Nota: </strong>Has agregado repuestos, por lo tanto debes agregar items en mano de obra.</p>',
                        icon: 'warning',
                        confirmButtonText: 'Ok',
                        allowEscapeKey: false,
                    });
                    break;

                case (validacion_3):
                    EnviarDatosCotizacion(placa, descModelo, km_actual, obs,ordenT);
                    break;

                case (validacion_4 == 0 && validacion_2):
                    Swal.fire({
                        title: 'Advertencia!',
                        html: '<p><strong>Nota: </strong>No ha agregado repuestos a la cotización.</p>',
                        icon: 'warning',
                        confirmButtonText: 'Ok',
                        allowEscapeKey: false,
                    });
                    break;
                default:
                    Swal.fire({
                        title: 'Advertencia!',
                        html: '<p><strong>Nota: </strong>Para crear una cotización debe agregar repuestos, mano de obra o ToT</p>',
                        icon: 'warning',
                        confirmButtonText: 'Ok',
                        allowEscapeKey: false,
                    });

                    break;

            }
        } else {
            switch (true) {
                case (opciones[0] == false):
                    Swal.fire({
                        title: 'Advertencia!',
                        html: '<p>Para guardar la cotización debe llenar los campos de Placa (La placa debe tener por minimo 6 caracteres), Descripción de modelo y Kilometraje actual.</p>',
                        icon: 'warning',
                        confirmButtonText: 'Ok',
                        allowEscapeKey: false,
                    });
                    break;
                case (opciones[4] == 0):
                    Swal.fire({
                        title: 'Advertencia!',
                        html: '<p><strong>Nota: </strong>El cambo de obsevación no puede enviarse vacío.</p>',
                        icon: 'warning',
                        confirmButtonText: 'Ok',
                        allowEscapeKey: false,
                    });
                    break;

            }

        }


    }

    function EnviarDatosCotizacion(placa, descModelo, km_actual, obs,ordenT) {
        document.getElementById('cargando').style.display = 'block';

        var datos = new FormData();

        //Agregamos los campos al formulario que se va a enviar
        datos.append('placa', placa);
        datos.append('descModelo', descModelo);
        datos.append('km_actual', km_actual);
        datos.append('obs', obs);
        datos.append('ordenT', ordenT);

        //Cantidad de Items o Repuestos
        var total = [];
        $(".fila_cotizador").map(function() {

            return total.push(this.id);

        }).get();
        //Agregamos la cantidad de Items a guardar
        datos.append('cantItems', total.length);

        var datosItems = [];

        for (let index = 0; index < total.length; index++) {

            datosItems.push($(`#${total[index]} td`).map(function() {
                if (this.innerText == "") {
                    return this.firstChild.value;
                } else {
                    return this.innerText;
                }
            }).get());

            datos.append('datosItems' + index, datosItems[index]);
        }

        //Cantidad de Items o Mano de Obra
        var total_mano_obra = [];
        $(".fila_mano").map(function() {

            return total_mano_obra.push(this.id);

        }).get();
        //Agregamos la cantidad de Items o mano de Obra a guardar
        datos.append('cantItemsManoObra', total_mano_obra.length);

        var datosItems_mano_obra = [];

        for (let index = 0; index < total_mano_obra.length; index++) {

            datosItems_mano_obra.push($(`#${total_mano_obra[index]} td`).map(function() {
                if (this.innerText == "") {
                    return this.firstChild.value;
                } else {
                    return this.innerText;
                }
            }).get());

            datos.append('datosItemsManoObra' + index, datosItems_mano_obra[index]);
        }


        //Cantidad de Items o ToT
        var total_ToT = [];
        $(".fila_ToT").map(function() {

            return total_ToT.push(this.id);

        }).get();
        //Agregamos la cantidad de Items o ToT a guardar
        datos.append('cantItemsToT', total_ToT.length);

        var datosItemsToT = [];

        for (let index = 0; index < total_ToT.length; index++) {

            datosItemsToT.push($(`#${total_ToT[index]} td`).map(function() {
                if (this.innerText == "") {
                    return this.firstChild.value;
                } else {
                    return this.innerText;
                }
            }).get());

            datos.append('datosItemsToT' + index, datosItemsToT[index]);
        }


        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.responseType = 'json';
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                var resp = xmlhttp.response;
                if (resp != null) {
                    Swal.fire({
                        title: 'Exito',
                        html: `<p>Se ha realizado con exito el registro de la cotización en el sistema</p>
                            <p><strong>Id Cotizacion: </strong>${resp["id_cotizacion"]}</p>
                            <p><strong>Cantidad de Repuestos: </strong>${resp["nRepuestosAdd"]}</p>
                            <p><strong>Cantidad de Items Mano de Obra: </strong>${resp["nManoObraAdd"]}</p>
                            <p><strong>Cantidad de Items ToT: </strong>${resp["nToTAdd"]}</p>`,
                        icon: 'success',
                        confirmButtonText: 'Ok',
                        denyButtonText: 'Cancelar',
                        showDenyButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showCloseButton: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            verCotizacion(resp["id_cotizacion"], placa);
                            location.reload();
                        }

                    });
                } else {

                    Swal.fire({
                        title: 'Error',
                        text: 'Ha ocurrido un error, intente nuevamente.',
                        icon: 'error',
                        confirmButtonText: 'Ok',
                        denyButtonText: 'Cancelar',
                        allowEscapeKey: false,
                        showDenyButton: false,
                        showCloseButton: true
                    });

                }
                document.getElementById('cargando').style.display = 'none';

            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>Sacyr/guardarDatosCotizacion", true);
        xmlhttp.send(datos);
    }


    function verCotizacion(id, placa) {
        /* Creamos un elemento tipo form->Formulario con metodo post y accion */
        var mapForm = document.createElement("form");
        mapForm.target = "Cotizacion";
        mapForm.method = "POST";
        mapForm.action = "<?= base_url() ?>Sacyr/verPdfCotizacion";
        /* Creamos los input dentro del formulario creado anteriormente */
        var varId = document.createElement("input");
        varId.type = "hidden";
        varId.name = "id_cotizacion";
        varId.value = id;
        mapForm.appendChild(varId);

        var varPlaca = document.createElement("input");
        varPlaca.type = "hidden";
        varPlaca.name = "placa";
        varPlaca.value = placa;
        mapForm.appendChild(varPlaca);

        /* Agregamos el formulario creado al body */
        document.body.appendChild(mapForm);
        /* Script para abrir una nueva ventana */
        map = window.open("", "Cotizacion", "status=0,title=0,height=600,width=800,scrollbars=1");

        if (map) {
            mapForm.submit();
        }
    }


    // necesarios para aplicar el formato
    function number_format(numero) {
        var numero2 = numero.value;
        var numParseado = null;
        if (numero2 != 0) {
            var cant = customRound(numero2.length / 3);
            var exp = '';
            var variables = '';
            for (let i = 1; i <= cant; i++) {
                if (i == cant) {
                    variables = variables + '$' + i;
                } else {
                    exp = '(\\d{3})' + exp;
                    variables = variables + '$' + i + '.'
                }
            }
            numParseado = numero2.replace(RegExp('(\\d+)' + exp), variables);
        } else {
            numParseado = 0;
        }
        /* return numParseado; */
        numero.value = numParseado;

    }

    function customRound(n) {
        var h = (n * 100) % 10;
        return h >= 1 ?
            parseInt(n.toString().charAt(0)) + 1 :
            parseInt(n.toString().charAt(0));
    }

    function aplicarNuberFormat(elemento) {
        var id = elemento.id;
        $("#" + id).map(function() {
            return this.value = number_format(this.value.replace(/[^0-9]/g, ''));
        });
    }

    function format(input) {
        var num = input.value.replace(/\./g, '');
        if (!isNaN(num)) {
            num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1.');
            num = num.split('').reverse().join('').replace(/^[\.]/, '');
            input.value = num;
        } else {
            Swal.fire({
                title: 'Advertencia!',
                text: 'Solo se permiten valores numericos',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
            input.value = input.value.replace(/[^\d\.]*/g, '');
        }
    }
    /* Funcion para eliminar los puntos de un numero */
    function formatDeleteDots(numero) {

        return numero.replaceAll('.', '');

    }
    /* Funcion para agregar puntos a un numero */
    function formatAddDots(numero) {
        var num
        num = numero.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1.');
        return num = num.split('').reverse().join('').replace(/^[\.]/, '');
    }

    /* funcion para sumar o calcular la totalidad de la cotización */
    function sumaTotalCotizacion() {

        var repuestos = formatDeleteDots(document.getElementById('cellValorTotal').innerText);
        var manoObra = formatDeleteDots(document.getElementById('valor_mano').innerText);
        var ToT = formatDeleteDots(document.getElementById('valor_ToT').innerText);

        repuestos = (repuestos != "") ? repuestos : 0;
        manoObra = (manoObra != "") ? manoObra : 0;
        ToT = (ToT != "") ? ToT : 0;


        var subtotal_rep = formatDeleteDots(document.getElementById('subTotalRepuestosDesc').innerText);
        subtotal_rep = (subtotal_rep != "") ? subtotal_rep : 0;


        var subtotalCoti = (parseInt(subtotal_rep) + parseInt(manoObra) + parseInt(ToT));
        var iva = Math.ceil((subtotalCoti) * 0.19).toFixed(0);

        var totalCotizacion = formatAddDots(parseInt(subtotalCoti) + parseInt(iva));


        document.getElementById('subtotalRepuestosT').innerText = formatAddDots(subtotal_rep);
        document.getElementById('subtotalManoT').innerText = formatAddDots(manoObra);
        document.getElementById('subtotalToT_T').innerText = formatAddDots(ToT);

        document.getElementById('subtotal').innerText = formatAddDots(subtotalCoti);

        document.getElementById('total_iva').innerText = formatAddDots(iva);

        document.getElementById('totalCotizacion').innerText = totalCotizacion;

    }

    function noPuntoComa(event) {

        var e = event || window.event;
        var key = e.keyCode || e.which;

        if (key === 110 || key === 190 || key === 188) {

            e.preventDefault();
        }
    }

    function evitarPegarTextAndInput() {
        $(".bloquear-paste").on('paste', function(e) {
            e.preventDefault();
            alert('Esta acción está prohibida');
        });
    }
</script>