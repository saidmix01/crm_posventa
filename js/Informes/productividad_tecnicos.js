const inputDate = document.getElementById('inputDate');
const cargando = document.getElementById('cargando');

$(document).ready(function () {
    $('.sidebar-mini').addClass('sidebar-collapse');

    $('.js-example-basic-single').select2({
        width: '100%',
        placeholder: 'Seleccione una opción',
        theme: "classic",
        allowClear: true,
        multiple: true
    });

});

function getData(inputDate) {

    cargando.style.display = 'block';


    const infDate = new FormData();
    infDate.append('year', inputDate.split('-')[0]);
    infDate.append('month', inputDate.split('-')[1]);
    infDate.append('patio', $("#selectPatio").val());

    fetch(baseURL + "Inf_Productividad_Tecnicos/load_data", {
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
                $('#tableData1').dataTable().fnDestroy();
                $('#tableData2').dataTable().fnDestroy();
                document.getElementById('tBodyTable1').innerHTML = json['data'];
                document.getElementById('tBodyTable2').innerHTML = json['data2'];
                document.getElementById('cargando').style.display = 'none';
                loadDatatable('tableData1');
                loadDatatable('tableData2');
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
        "order": [[2, 'asc'],[1,'asc']],
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

const btnGenerar = document.getElementById("btn-generar-inf");
btnGenerar.addEventListener("click", function () {

    if (inputDate.value != "" && new Date(inputDate.value) <= new Date(dateActual)) {
        document.getElementById('cargando').style.display = "block";
        getData(inputDate.value);
    } else {
        Swal.fire({
            title: 'Error',
            html: `Seleccione una fecha correcta`,
            icon: 'warning',
            confirmButtonText: 'Ok'
        });
    }
}, false);
