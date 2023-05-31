<?php $this->load->view('Cotizador/header'); ?>

<div class="content-wrapper">
    <section class="content">
        <div class="alert alert-light col-lg-12 text-center" role="alert">
            <h3 class="title">Informe Cotizado Vs Facturado</h3>
        </div>
    </section>
    <!-- Main content -->
    <div class="loader" id="cargando"></div>
    <section class="content">

        <div class="card">

            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <label for="inputDateInicial" class="font-weight-bold">Desde</label>
                        <input type="date" id="inputDateInicial" name="inputDateInicial" class="form-control" min="2022-09-01">
                    </div>
                    <div class="col">
                        <label for="inputDateFinal" class="font-weight-bold">Hasta</label>
                        <input type="date" id="inputDateFinal" name="inputDateFinal" class="form-control" max="<?= Date('Y-m-d') ?>">
                    </div>
                    <div class="col">
                        <label for="inputBodega" class="font-weight-bold">Bodega</label>
                        <select id="inputBodega" name="inputBodega" class="form-control">
                            <option value="">Seleccione una bodega</option>
                            <option value="1">Girón Gasolina</option>
                            <option value="6">Barranca</option>
                            <option value="7">Rosita</option>
                            <option value="8">Cúcuta</option>
                        </select>
                    </div>
                    <div class="col align-self-end">
                        <button type="button" id="btnInforme" name="btnInforme" class="btn btn-success btn-sm" onclick="loadAllCountCotizaciones();">GENERAR</button>
                    </div>
                </div>
                <hr>
                <div class="row" id="cardsCotizaciones">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-chart-line"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">ASISTIDAS</span>
                                <span class="info-box-number" id="boxCantAsistidas"> -- </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-chart-line"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">AGENDADAS</span>
                                <span class="info-box-number" id="boxCantAgendadas"> -- </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-chart-line"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">SIN AGENDA</span>
                                <span class="info-box-number" id="boxCantSinAgenda"> -- </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-chart-line"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">TOTAL</span>
                                <span class="info-box-number" id="boxCantTotal"> -- </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <div class="table-responsive p-2" id="tableAsistidas"></div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive p-2" id="tableCotizadoToFacturado"></div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive p-2" id="tableFacturadoToCotizado"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

</div>
<?php $this->load->view('footerPrincipal'); ?>
<script>
    $(document).ready(function() {
        loadAllCountCotizaciones();
    });
    const inputDateInicial = document.getElementById('inputDateInicial');
    const inputDateFinal = document.getElementById('inputDateFinal');
    const inputBodega = document.getElementById('inputBodega');
    const boxCantAsistidas = document.getElementById('boxCantAsistidas');
    const boxCantAgendadas = document.getElementById('boxCantAgendadas');
    const boxCantSinAgenda = document.getElementById('boxCantSinAgenda');
    const boxCantTotal = document.getElementById('boxCantTotal');
    const tableAsistidas = document.getElementById('tableAsistidas');
    const tableCotizadoToFacturado = document.getElementById('tableCotizadoToFacturado');
    const tableFacturadoToCotizado = document.getElementById('tableFacturadoToCotizado');

    function loadAllCountCotizaciones() {
        document.getElementById('cargando').style.display = 'block';
        const datosFiltro = new FormData();
        datosFiltro.append('inputDateInicial', inputDateInicial.value);
        datosFiltro.append('inputDateFinal', inputDateFinal.value);
        datosFiltro.append('inputBodega', inputBodega.value);
        fetch("<?= base_url() ?>InformeCotizador/loadAllCountCotizaciones", {
                headers: {
                    "Content-type": "application/json",
                },
                mode: 'no-cors',
                method: "POST",
                body: datosFiltro,
            })
            .then(function(response) {
                // Transforma la respuesta. En este caso lo convierte a JSON
                return response.json();
            })
            .then(function(json) {
                if (json['response'] == 'success') {
                    const dataCount = json['data'][0];
                    /* total_cotizaciones	env_sin_agenda	env_agendadas	asistidas */
                    boxCantAsistidas.innerText = dataCount.asistidas;
                    boxCantAgendadas.innerText = dataCount.env_agendadas;
                    boxCantSinAgenda.innerText = dataCount.env_sin_agenda;
                    boxCantTotal.innerText = dataCount.total_cotizaciones;
                    loadInfoValorTotalCotizaciones();
                } else if (json['response'] == 'error') {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se han encontrado datos en el rango de las fechas y la bodega seleccionada',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            })
            .catch(function(error) {
                Swal.fire({
                    title: 'Advertencia',
                    html: `Ha ocurrido un error al realizar la peticion a la api de la Intranet de Posventa
                    <br/><strong>Nota:</strong> Este cotizador es para vehículos pesados.`,
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
                document.getElementById('cargando').style.display = 'none';
            });
    }

    function loadInfoValorTotalCotizaciones() {
        const datosAsitidas = new FormData();
        datosAsitidas.append('inputDateInicial', inputDateInicial.value);
        datosAsitidas.append('inputDateFinal', inputDateFinal.value);
        datosAsitidas.append('inputBodega', inputBodega.value);
        fetch("<?= base_url() ?>InformeCotizador/loadInfoValorTotalCotizaciones", {
                headers: {
                    "Content-type": "application/json",
                },
                mode: 'no-cors',
                method: "POST",
                body: datosAsitidas,
            })
            .then(function(response) {
                // Transforma la respuesta. En este caso lo convierte a JSON
                return response.json();
            })
            .then(function(json) {
                if (json['response'] == 'success') {
                    tableAsistidas.innerHTML = json['data'];
                    fnTableCotizadoToFacturado();
                    /* total_cotizaciones	env_sin_agenda	env_agendadas	asistidas */
                } else if (json['response'] == 'error') {
                    document.getElementById('cargando').style.display = 'none';
                    Swal.fire({
                        title: 'Error',
                        text: 'No se han encontrado datos en el rango de las fechas y la bodega seleccionada',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }

            })
            .catch(function(error) {
                Swal.fire({
                    title: 'Advertencia',
                    html: `Ha ocurrido un error al realizar la peticion a la api de la Intranet de Posventa
                    <br/><strong>Nota:</strong> Este cotizador es para vehículos pesados.`,
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
                document.getElementById('cargando').style.display = 'none';
            });
    }

    function fnTableCotizadoToFacturado() {
        const datosCotizado = new FormData();
        datosCotizado.append('inputDateInicial', inputDateInicial.value);
        datosCotizado.append('inputDateFinal', inputDateFinal.value);
        datosCotizado.append('inputBodega', inputBodega.value);
        fetch("<?= base_url() ?>InformeCotizador/cotizacionToFacturado", {
                headers: {
                    "Content-type": "application/json",
                },
                mode: 'no-cors',
                method: "POST",
                body: datosCotizado,
            })
            .then(function(response) {
                // Transforma la respuesta. En este caso lo convierte a JSON
                return response.json();
            })
            .then(function(json) {
                if (json['response'] == 'success') {
                    if ($.fn.DataTable.isDataTable('#tablaCotizacion')) {
                        $('#CotizadoToFacturado').dataTable().fnDestroy();
                    }
                    tableCotizadoToFacturado.innerHTML = json['data'];
                    loadDatatable('CotizadoToFacturado');
                    /* total_cotizaciones	env_sin_agenda	env_agendadas	asistidas */
                    fnTableFacturadoToCotizado();

                } else if (json['response'] == 'error') {
                    document.getElementById('cargando').style.display = 'none';
                    Swal.fire({
                        title: 'Error',
                        text: 'No se han encontrado datos en el rango de las fechas y la bodega seleccionada',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            })
            .catch(function(error) {
                Swal.fire({
                    title: 'Advertencia',
                    html: `Ha ocurrido un error al realizar la peticion a la api de la Intranet de Posventa
                    <br/><strong>Nota:</strong> Este cotizador es para vehículos pesados.`,
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
                document.getElementById('cargando').style.display = 'none';
            });
    }

    function fnTableFacturadoToCotizado() {
        const tableFacturado = new FormData();
        tableFacturado.append('inputDateInicial', inputDateInicial.value);
        tableFacturado.append('inputDateFinal', inputDateFinal.value);
        tableFacturado.append('inputBodega', inputBodega.value);
        fetch("<?= base_url() ?>InformeCotizador/facturadoToCotizacion", {
                headers: {
                    "Content-type": "application/json",
                },
                mode: 'no-cors',
                method: "POST",
                body: tableFacturado,
            })
            .then(function(response) {
                // Transforma la respuesta. En este caso lo convierte a JSON
                return response.json();
            })
            .then(function(json) {
                if (json['response'] == 'success') {
                    if ($.fn.DataTable.isDataTable('#tablaCotizacion')) {
                        $('#FacturadoToCotizado').dataTable().fnDestroy();
                    }
                    tableFacturadoToCotizado.innerHTML = json['data'];
                    loadDatatable('FacturadoToCotizado');
                    /* total_cotizaciones	env_sin_agenda	env_agendadas	asistidas */
                } else if (json['response'] == 'error') {

                    Swal.fire({
                        title: 'Error',
                        text: 'No se han encontrado datos en el rango de las fechas y la bodega seleccionada',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
                document.getElementById('cargando').style.display = 'none';
            })
            .catch(function(error) {
                Swal.fire({
                    title: 'Advertencia',
                    html: `Ha ocurrido un error al realizar la peticion a la api de la Intranet de Posventa
                    <br/><strong>Nota:</strong> Este cotizador es para vehículos pesados.`,
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
                document.getElementById('cargando').style.display = 'none';
            });
    }

    function loadDatatable(id) {
        $('#' + id).DataTable({
            "scrollY": "500px",
            "scrollCollapse": true,
            "paging": true,
            "pageLength": 5,
            "lengthMenu": [
                [5, 10, 20, -1],
                [5, 10, 20, "Todo"]
            ],
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