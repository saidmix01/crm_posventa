<?php $this->load->view( 'MPC/header' ) ?>
<div class='content-wrapper'>
    <!-- Main content -->
    <section class='content'>
        <div class='alert alert-light text-center' role='alert'>
            <h4>
                <?=$page?>
            </h4>
        </div>
    </section>
    <div class='loader' id='cargando'></div>
    <section class='content'>

        <div class='card'>
            <div class='card-header'>
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-success" data-toggle="tooltip" data-placement="right" title="Descargara un archivo 'Infome_mpc.xls' con los datos que se muestran en la tabla, seleccionar en el cambio 'Mostrar' la opción Todos." onclick="ver_excel();"><i
                                class="fas fa-download"></i>Descargar</button>
                    </div>
                </div>
            </div>
            <div class='card-body'>
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='table-responsive p-4'>
                            <table class='table' id='example1'>
                                <thead class='text-center'>
                                    <tr>
                                        <th scope="col">FECHA</th>
                                        <th scope="col">PLACA</th>
                                        <th scope="col">MODELO</th>
                                        <th scope="col">PLAN VENDIDO</th>
                                        <th scope="col">VALOR</th>
                                        <th scope="col">REDIMIDO</th>
                                        <th scope="col">SALDO</th>
                                        <th scope="col">VENDIDO POR</th>
                                    </tr>
                                </thead>
                                <tbody id='data_tabla' class='text-center'>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
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
            <div class='card-footer'>

            </div>
        </div>

    </section>
    <!-- /.content -->

</div>
<?php $this->load->view( 'MPC/footer' ) ?>

<script>
$(document).ready(function() {
    cargarInforme();

    $('[data-toggle="tooltip"]').tooltip();

});


function cargarInforme() {
    document.getElementById('cargando').style.display = 'block';
    let result = document.getElementById("data_tabla");
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    /* xmlhttp.responseType = 'json'; */
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            if ($.fn.DataTable.isDataTable('#example1')) {
                $('#example1').dataTable().fnDestroy();
            }
            result.innerHTML = xmlhttp.responseText;
            dataTableActividades();
            document.getElementById('cargando').style.display = 'none';

        }
    }
    xmlhttp.open("POST", "<?=base_url()?>Mpc/getDataInforme", true);
    xmlhttp.send();

}


function ver_excel() {
    let result = document.getElementById("data_tabla").rows[0].innerText;
    if (result != '\t\t\t\t\t\t' && result != 'No se encontraron resultados') {
        $("#example1").table2excel({
            exclude: ".noExl",
            name: "Worksheet Name",
            filename: "Informe MPC", //do not include extension
            fileext: ".xlsx" // file extension
        });
    } else {
        Swal.fire({
            title: 'Advertencia!',
            text: 'No hay registros en la tabla para descargar...',
            icon: 'warning',
            confirmButtonText: 'Ok'
        });
    }

}

function dataTableActividades() {
    $('#example1').DataTable({
        "paging": true,
        "retrieve": true,
        "pageLength": 10,
        "lengthChange": true,
        "lengthMenu": [
            [-1, 10, 50, 100],
            ["Todos", 10, 50, 100]
        ],
        "searching": true,
        "ordering": true,
        "order": [
            [0, 'desc']
        ],
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
            },
            "MENU": {
                "All": 'Todos',
            }
        }
    });
}
</script>