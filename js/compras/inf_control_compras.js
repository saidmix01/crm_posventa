const inputOrden = document.getElementById('inputOrden');
const cargando = document.getElementById('cargando');
/* $(document).ready(function () {

}); */

function getData(orden) {
    cargando.style.display = 'block';
    const infDate = new FormData();
    infDate.append('orden', orden);

    fetch(baseURL + "Inf_Control_Compras/load_data", {
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
                $('#tableData').dataTable().fnDestroy();
                document.getElementById('tBodyTable').innerHTML = json['data'];
                document.getElementById('cargando').style.display = 'none';
                loadDatatable('tableData');
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

const btnGenerar = document.getElementById("btn-generar-inf");
btnGenerar.addEventListener("click", function () {

    if (inputOrden.value != "") {
        document.getElementById('cargando').style.display = "block";
        getData(inputOrden.value);
    } else {
        Swal.fire({
            title: 'Error',
            html: `El campo del número de orden se encuentra vacio!`,
            icon: 'warning',
            confirmButtonText: 'Ok'
        });
    }
}, false);


inputOrden.addEventListener("keyup", function () {

    if (inputOrden.value != "") {
        btnGenerar.disabled = false;
    } else {
        btnGenerar.disabled = true;
    }

}, false);