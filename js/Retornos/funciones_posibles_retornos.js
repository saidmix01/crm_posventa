console.log("Url: " + baseURL + "");

$(document).ready(function () {

    getData();

    $('.js-example-basic-single').select2({
        width: '100%',
        placeholder: 'Seleccione una opción',
        theme: "classic",
        allowClear: true
    });

});

function getData(numero="",placa="",bodega="") {
    var table = $('#tableDataRetorno').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            url: baseURL + "Posible_retorno/load_data_retornos?placa="+placa+"&bodega="+bodega+"&numero="+numero,
            type: "GET",
            /* "dataSrc": function (json) {
                //Make your callback here.
                console.log(json.recordsFiltered);
                return json.data;
            } */

        },
        "columnDefs": [{
            "targets": [0, 2],
            "searchable": true,
            "orderable": false,
        },
        {
            "targets": [1, 3, 4],
            "searchable": false,
            "orderable": false,
        }],
        "paging": true,
        "pageLength": 5,
        "lengthChange": true,
        "lengthMenu": [[-1, 10, 50, 100], ["Todos", 10, 50, 100]],
        "searching": false,
        "search": {
            "regex": true
        },
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "language": {
            "sProcessing": "<span class='fa-stack fa-lg'>\n\
									<i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
		   						</span>&emsp;Procesando ...",
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

function verDetalle(placa, numero) {

    const placaInput = document.getElementById('placa');
    const des_modeloInput = document.getElementById('des_modelo');
    const clienteInput = document.getElementById('cliente');
    const cant_retornosInput = document.getElementById('cant_retornos');
    const posibleR_orden = document.getElementById('posibleR_orden');

    const bodyOrden = document.getElementById('BodyDetalleRetornoOrdenes');
    const bodyClientes = document.getElementById('BodyDetalleRetornoTecnicos');


    if (placa != "") {
        document.getElementById('cargando').style.display = 'block';
        const formDetRetorno = new FormData();
        formDetRetorno.append("placa", placa);

        fetch(baseURL + "Posible_retorno/load_data_detalle_retornos", {
            headers: {
                "Content-type": "application/json",
            },
            mode: 'no-cors',
            method: "POST",
            body: formDetRetorno,
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (json) {
                if (json['response'] == 'success') {

                    const dataCliente = json['data_cliente'];

                    placaInput.value = dataCliente[0].placa;
                    des_modeloInput.value = dataCliente[0].des_modelo;
                    clienteInput.value = dataCliente[0].cliente;
                    cant_retornosInput.value = dataCliente[0].cant_retornos;
                    posibleR_orden.value = numero;

                    bodyOrden.innerHTML = json['data_ordenes'];
                    bodyClientes.innerHTML = json['data_tecnicos'];

                    $('#ModalDetalleRetorno').modal('show');

                    const array_ordenes = json['array_ordenes'];
                    const array_tecnicos = json['array_tecnicos'];

                    addOptionsSelectGestion(array_ordenes, array_tecnicos);


                    document.getElementById('cargando').style.display = 'none';

                } else if (json['response'] == 'error') {
                    Swal.fire({
                        title: 'Error',
                        html: `No se ha encontrado información.`,
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
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
            });
    } else {
        Swal.fire({
            title: 'Error',
            html: `Este vehículo no se encuentra registrado con placa.`,
            icon: 'warning',
            confirmButtonText: 'Ok'
        });
    }

}


function addOptionsSelectGestion(array_ordenes, array_tecnicos) {


    const selectTecnicos = document.getElementById('tecnicoR');
    const selectOrdenes = document.getElementById('ordenR');

    const limpiarselectTecnicos = () => {

        for (let i = selectTecnicos.options.length; i >= 0; i--) {
            selectTecnicos.remove(i);
        }

        for (let i = selectOrdenes.options.length; i >= 0; i--) {
            selectOrdenes.remove(i);
        }

    };

    limpiarselectTecnicos();

    const option = document.createElement('option');
    option.value = "";
    option.text = "Seleccione una Orden";
    selectOrdenes.appendChild(option);

    array_ordenes.map(function (orden) {
        const option1 = document.createElement('option');
        option1.value = orden;
        option1.text = orden;
        selectOrdenes.appendChild(option1);
    });

    const option2 = document.createElement('option');
    option2.value = "";
    option2.text = "Seleccione un Tecnico";
    selectTecnicos.appendChild(option2);

    array_tecnicos.map(function (tecnico) {
        const option3 = document.createElement('option');
        option3.value = tecnico;
        option3.text = tecnico;
        selectTecnicos.appendChild(option3);
    });

    $('.js-example-basic-single-g').select2({
        width: '100%',
        placeholder: 'Seleccione una opción',
        theme: "classic",
        allowClear: true
    });
}

function definicionRetorno(def) {
    const selectRazonDiv = document.getElementById('selectRazonDiv');
    const selectRazonDivNo = document.getElementById('selectRazonDivNo');
    const obs_razon_div = document.getElementById('obs_razon_div');
    const select_sist_inv_div = document.getElementById('select_sist_inv_div');
    const obs_sist_inv_div = document.getElementById('obs_sist_inv_div');

    const ordenR = document.getElementById('datosOrden');
    const tecnicoR = document.getElementById('datosTecnico');

    if (def == 1) {
        selectRazonDiv.style.display = 'block';
        obs_razon_div.style.display = 'block';
        select_sist_inv_div.style.display = 'block';
        obs_sist_inv_div.style.display = 'block';
        ordenR.style.display = 'block';
        tecnicoR.style.display = 'block';
        selectRazonDivNo.style.display = 'none';
    } else {
        selectRazonDivNo.style.display = 'block';
        selectRazonDiv.style.display = 'none';
        select_sist_inv_div.style.display = 'none';
        obs_sist_inv_div.style.display = 'none';
        ordenR.style.display = 'none';
        tecnicoR.style.display = 'none';
    }
}



function gestionarRetorno() {
    const definicion = document.querySelector('input[name="inlineRadioOptions"]:checked').value

    const selectRazon = document.getElementById('selectRazon');
    const selectRazonNo = document.getElementById('selectRazonNo');
    const obs_razon = document.getElementById('obs_razon');
    const select_sist_inv = document.getElementById('select_sist_inv');
    const obs_sist_inv = document.getElementById('obs_sist_inv');
    const ordenR = document.getElementById('ordenR');
    const tecnicoR = document.getElementById('tecnicoR');
    const selectPlan = document.getElementById('selectPlan');
    const obs_plan = document.getElementById('obs_plan');
    const precio_costo_1 = document.getElementById('precio_costo_1');
    const precio_costo_2 = document.getElementById('precio_costo_2');
    const precio_costo_3 = document.getElementById('precio_costo_3');
    const obs_costos = document.getElementById('obs_costos');
    const posibleR_orden = document.getElementById('posibleR_orden');

    if (definicion == 1) {
        if (definicion != "" && selectRazon.value != "" && obs_razon.value != "" && select_sist_inv.value != "" && obs_sist_inv.value != "" && ordenR.value != "" && tecnicoR.value != "" && selectPlan.value != "" && obs_plan.value != "" && obs_costos.value != "" && precio_costo_1.value != "" && precio_costo_2.value != "" && precio_costo_3.value != "") {

            const formData = new FormData();

            formData.append('definicion', definicion);
            formData.append('selectRazon', selectRazon.value);
            formData.append('obs_razon', obs_razon.value);
            formData.append('select_sist_inv', select_sist_inv.value);
            formData.append('obs_sist_inv', obs_sist_inv.value);
            formData.append('ordenR', ordenR.value);
            formData.append('ordenR_origen', posibleR_orden.value);
            formData.append('tecnicoR', tecnicoR.value);
            formData.append('selectPlan', selectPlan.value);
            formData.append('obs_plan', obs_plan.value);
            formData.append('precio_costo_1', precio_costo_1.value);
            formData.append('precio_costo_2', precio_costo_2.value);
            formData.append('precio_costo_3', precio_costo_3.value);
            formData.append('obs_costos', obs_costos.value);

            fnEnviarDatos(formData);
        } else {
            Swal.fire({
                title: 'Advertencia',
                html: `Debe completar todos los campos del formulario`,
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
    } else {
        if (definicion != "" && selectRazonNo.value != "" && selectPlan.value != "" && obs_plan.value != "" && obs_costos.value != "" && precio_costo_1.value != "" && precio_costo_2.value != "" && precio_costo_3.value != "") {
            const formData = new FormData();

            formData.append('definicion', definicion);
            formData.append('selectRazon', selectRazonNo.value);
            formData.append('ordenR_origen', posibleR_orden.value);
            formData.append('selectPlan', selectPlan.value);
            formData.append('obs_plan', obs_plan.value);
            formData.append('precio_costo_1', precio_costo_1.value);
            formData.append('precio_costo_2', precio_costo_2.value);
            formData.append('precio_costo_3', precio_costo_3.value);
            formData.append('obs_costos', obs_costos.value);

            fnEnviarDatos(formData);
        } else {
            Swal.fire({
                title: 'Advertencia',
                html: `Debe completar todos los campos del formulario`,
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
    }

}


function fnEnviarDatos(formData) {
    fetch(baseURL + "Posible_retorno/insertDefinicionRetorno", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
        body: formData,
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (json) {
            if (json['response'] == 'success') {
                Swal.fire({
                    title: 'Exito',
                    html: `Se ha realizado el registro de gestión para el posible retorno`,
                    icon: 'success',
                    confirmButtonText: 'Ok',
                    willClose: () => {
                        location.reload();
                    }
                });
            } else if (json['response'] == 'error') {
                Swal.fire({
                    title: 'Error',
                    html: `No se ha podido guardar la información, intente nuevamente.`,
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
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
        });
}

/* Funcion para eliminar los puntos de un numero */
function formatDeleteDots(event) {
    var e = event || window.event;
    var key = e.keyCode || e.which;

    if (key === 110 || key === 190 || key === 188 || key === 109 || key === 82) {

        e.preventDefault();
    }

}


function verSolucion(numero) {

    document.getElementById('cargando').style.display = 'block';

    const formData = new FormData();
    formData.append('numero', numero);

    fetch(baseURL + "Posible_retorno/loadPosibleRetornoSoluciones", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
        body: formData,
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (json) {
            if (json['response'] == 'success') {
                document.getElementById('tbodySoluciones').innerHTML = json['tbody'];

                $('#ModalSolucionRetorno').modal('show');

                document.getElementById('cargando').style.display = 'none';
            } else if (json['response'] == 'error') {
                Swal.fire({
                    title: 'Error',
                    html: `No se ha podido guardar la información, intente nuevamente.`,
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
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
        });

}

function filtro(){
    let filtroPlaca = document.getElementById('filtroPlaca').value;
    let filtroBodega = document.getElementById('filtroBodega').value;
    let filtroNumero = document.getElementById('filtroNumero').value;
    if (filtroPlaca != "" || filtroBodega != "") {
        $('#tableDataRetorno').dataTable().fnDestroy();
        getData(filtroNumero,filtroPlaca,filtroBodega);
    } else {
        alert('Seleccione una Bodega o digite una placa.');
    }
}

function cerrarPosibleBDC(id_posible_bdc){
    document.getElementById('cargando').style.display = 'block';

    const formData = new FormData();
    formData.append('id_posible_bdc', id_posible_bdc);

    fetch(baseURL + "Posible_retorno/cerrarPosiblesRetornosBDC", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
        body: formData,
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (json) {
            if (json['response'] == 'success') {
                document.getElementById('cargando').style.display = 'none';
                location.reload();
            } else if (json['response'] == 'error') {
                Swal.fire({
                    title: 'Error',
                    html: `No se ha podido guardar la información, intente nuevamente.`,
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
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
        });

}