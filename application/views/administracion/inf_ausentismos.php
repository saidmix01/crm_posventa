<?php $this->load->view('administracion/header') ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="loader" id="cargando"></div>
    <br>
    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Informe Tiempo de Ausentismos</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-auto" style="align-self: self-end;">
                        <label for="nit_empleado">Empleado:</label>
                        <select id="nit_empleado" name="nit_empleado" class="form-control js-example-basic-single">
                            <option></option>
                            <?php foreach ($usuariosActivos->result() as $key) { ?>
                                <option value="<?= $key->nit ?>"><?= $key->nombres ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <input max="<?= Date('Y-m'); ?>" value="" class="form-control" type="month" name="dateSelect" id="dateSelect">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-success" onclick="getInformeByDate()">Buscar</button>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary" onclick="donwloadExecl()">Descargar</button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12 table-responisve">
                        <table id='Inf_Tiempo_Ausentismo' class='table table-bordered tabla-hover'>
                            <thead>
                                <!-- nit_empleado	nombres	motivo	fecha_ini	fecha_fin	hora_ini	hora_fin -->
                                <tr>
                                    <th class="text-center" scope='col'>Documento</th>
                                    <th class="text-center" scope='col'>Nombre</th>
                                    <th class="text-center" scope='col'>Motivo</th>
                                    <th class="text-center" scope='col'>Fecha Inicial</th>
                                    <th class="text-center" scope='col'>Fecha Final</th>
                                    <th class="text-center" scope='col'>Hora Inicial</th>
                                    <th class="text-center" scope='col'>Hora Final</th>
                                </tr>
                            </thead>

                            <tbody id="resultTbody">
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<?php $this->load->view('footerPrincipal') ?>

<script>
    $(document).ready(function() {
        /* getInformeByDate(); */

        $('.js-example-basic-single').select2({
            placeholder: 'Seleccione un empleado',
            theme: "classic",
            allowClear: true
        });

    });


    function getInformeByDate() {

        const params = document.getElementById('dateSelect').value;
        const nit_empleado = document.getElementById('nit_empleado').value;
        const form = new FormData();
        const dateSelect = params.split('-');

        if (params != "") {
            document.getElementById('cargando').style.display = 'block';

            form.append('year', dateSelect[0]);
            form.append('month', dateSelect[1]);
            form.append('nit_empleado', nit_empleado);
            fetch("<?= base_url() ?>Administracion/loadDataInfAusentismos", {
                    headers: {
                        "Content-type": "application/json",
                    },
                    mode: 'no-cors',
                    method: "POST",
                    body: form,
                })
                .then(function(response) {
                    // Transforma la respuesta. En este caso lo convierte a JSON
                    return response.json();
                })
                .then(function(json) {
                    if (json['response'] == 'success') {
                        if ($.fn.DataTable.isDataTable('#Inf_Tiempo_Ausentismo')) {
                            $('#Inf_Tiempo_Ausentismo').dataTable().fnDestroy();
                        }
                        document.getElementById("resultTbody").innerHTML = json['tbody'];
                        loadDataTable();
                        Swal.fire({
                            title: 'Exito',
                            text: 'Cargando la información encontrada',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });
                        document.getElementById('cargando').style.display = 'none';
                    } else if (json['response'] == 'error') {
                        Swal.fire({
                            title: 'Advertencia',
                            text: 'La fecha seleccionada no cuenta con datos almacenados en Ausentismos',
                            icon: 'warning',
                            confirmButtonText: 'Ok'
                        });
                        document.getElementById('cargando').style.display = 'none';
                    }

                })
                .catch(function(error) {
                    Swal.fire({
                        title: 'Error',
                        html: `Error fatal, llame a un experto, o recargue la página.`,
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                    document.getElementById('cargando').style.display = 'none';
                });
        } else {
            Swal.fire({
                            title: 'Advertencia',
                            text: 'Seleccione una fecha',
                            icon: 'warning',
                            confirmButtonText: 'Ok'
                        });
        }



    }

    //Informe de ausentimso por filtros
    function donwloadExecl() {
        $("#Inf_Tiempo_Ausentismo").table2excel({
            exclude: ".noExl",
            name: "Worksheet Name",
            filename: "Informe-Ausentismo-tiempo", //do not include extension
            fileext: ".xlsx" // file extension
        });
    }

    function loadDataTable() {
        $('#Inf_Tiempo_Ausentismo').DataTable({
            "paging": true,
            "pageLength": 10,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            "scrollX": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
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
</script>