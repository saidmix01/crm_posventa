const startDate = document.getElementById('startDate');
const endDate = document.getElementById('endDate');

$(document).ready(function () {
    document.getElementById('cargando').style.display = 'block';

    getData(startDate.value,endDate.value);

    $('.js-example-basic-single').select2({
        width: '100%',
        placeholder: 'Seleccione una opción',
        theme: "classic",
        allowClear: true
    });

});

function getData(startDate,endDate) {

    const infDate = new FormData();
    infDate.append('startDate', startDate);
    infDate.append('endDate', endDate);

    fetch(baseURL + "InfTiempoEntrevistaConsultiva/load_data_tiempo", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
        body: infDate,
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (json) {
            if (json['response'] == 'success') {
                document.getElementById('tBodytablePrincipal').innerHTML = json['data'];
                document.getElementById('cargando').style.display = 'none';
            } else if (json['response'] == 'error') {
                Swal.fire({
                    title: 'Error',
                    html: `No se ha encontrado información.`,
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
                document.getElementById('cargando').style.display = 'none';
            }
        })
        .catch(function (error) {
            console.error(error);
            Swal.fire({
                title: 'Error',
                html: `Ha ocurrido un error al realizar la peticion a la api de la Intranet de Posventa, intente nuevamente`,
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
            document.getElementById('cargando').style.display = 'none';
        });

}

function verDetalleBodega(bodega) {
    document.getElementById('cargando').style.display = 'block';
    const form = new FormData();
    form.append('bodega', bodega);
    form.append('startDate', startDate.value);
    form.append('endDate', endDate.value);

    fetch(baseURL + "InfTiempoEntrevistaConsultiva/load_data_tiempo_detail", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
        body: form,
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (json) {
            if (json['response'] == 'success') {
                $('#tableSecundaria').dataTable().fnDestroy();
                document.getElementById('tBodytableSecundaria').innerHTML = json['data'];
                loadDatatable('tableSecundaria');


                document.getElementById('cargando').style.display = 'none';
            } else if (json['response'] == 'error') {
                Swal.fire({
                    title: 'Error',
                    html: `No se ha encontrado información.`,
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
                document.getElementById('cargando').style.display = 'none';
            }
        })
        .catch(function (error) {
            console.error(error);
            Swal.fire({
                title: 'Error',
                html: `Ha ocurrido un error al realizar la peticion a la api de la Intranet de Posventa, intente nuevamente`,
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

const btnGenerar = document.getElementById("btnGenerar");
btnGenerar.addEventListener("click", function () {

    if (new Date(startDate.value) <= new Date(endDate.value)){
        document.getElementById('cargando').style.display = "block";
        getData(startDate.value,endDate.value);
    }else{
        Swal.fire({
            title: 'Error',
            html: `Fecha inicial: <strong>${startDate.value}</strong><br>Fecha final: <strong>${endDate.value}</strong><br><strong>Nota:</strong> La fecha final es menor que la fecha inicial, seleccione una fecha mayor a la fecha inicial.  `,
            icon: 'warning',
            confirmButtonText: 'Ok'
        });
    }



}, false);