document.addEventListener("DOMContentLoaded", () => {
    /* alert('SE ESTA CARGANDO DESPUES DEL DOM'); */
});

const newspaperSpinning = [{ background: "yellow" }, { background: "none" }];
const newspaperTiming = { duration: 500, iterations: 5, };

const inputPlacaOrden = document.getElementById('inputPlacaOrden');
const btnBuscarOrdenesByPlaca = document.getElementById('btnBuscarOrdenesByPlaca');
const tbody_table_ordenes_by_placa = document.getElementById('tbody_table_ordenes_by_placa');
const orden_salida = document.getElementsByClassName('orden_salida');

/* SCRIPT PARA EVITAR QUE SE AGREGUEN CARACTERES ESPECIALES */
inputPlacaOrden.addEventListener('keypress', function (event) {

    var e = event || window.event;
    var key = e.keyCode || e.which;

    if ((key >= 48 && key <= 57) || (key >= 97 && key <= 122) || (key >= 65 && key <= 90) || key === 209 || key === 241) {

    } else {
        e.preventDefault();
    }

});
/* SCRIPT PARA CARGAR LAS ORDENES DE SALIDA POR PLACA*/
btnBuscarOrdenesByPlaca.addEventListener('click', function (event) {
    if (inputPlacaOrden.value != "" && inputPlacaOrden.value.length >= 6) {
        searchOrderByPlaca(inputPlacaOrden.value);
    } else {
        swal.fire({
            title: 'ADVERTENCIA',
            icon: 'warning',
            html: `El campo de placa esta vacio o tiene menos de 6 caracteres`,
            confirmButtonText: 'OK',
            willClose: () => {
                inputPlacaOrden.animate(newspaperSpinning, newspaperTiming);
                inputPlacaOrden.focus();
            },

        });
    }
});
inputPlacaOrden.addEventListener('keypress', function (event) {

    if (event.key === "Enter") {
        if (inputPlacaOrden.value != "" && inputPlacaOrden.value.length >= 6) {
            searchOrderByPlaca(inputPlacaOrden.value);
        } else {
            swal.fire({
                title: 'ADVERTENCIA',
                icon: 'warning',
                html: `El campo de placa esta vacio o tiene menos de 6 caracteres`,
                confirmButtonText: 'OK',
                willClose: () => {
                    inputPlacaOrden.animate(newspaperSpinning, newspaperTiming);
                    inputPlacaOrden.focus();
                },

            });
        }
    }

});

function searchOrderByPlaca(placa) {
    cargando.style.display = 'block';
    const datos = new FormData();
    datos.append('placa', placa);
    fetch(base_url + "orden_salida/get_orden_salida_by_placa", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
        body: datos,
    })
        .then(function (response) {
            // Transforma la respuesta. En este caso lo convierte a JSON
            return response.json();
        })
        .then(function (json) {
            if (json['response'] === 'success') {
                tbody_table_ordenes_by_placa.innerHTML = json['tbody'];
            } else if (json['response'] === 'error') {
                swal.fire({
                    title: 'Error',
                    html: `No se ha encontrado registros de la placa ingresada`,
                    icon: 'error',
                    willClose: () => {
                        tbody_table_ordenes_by_placa.innerHTML = "";
                    }
                });
            }
            cargando.style.display = 'none';
        })
        .catch(function (error) {
            swal.fire({
                icon: 'error',
                title: 'Error',
                html: `Ha ocurrido un error en la api de la intranet de postventa, intente nuevamente.
                <strong>ERROR:</strong>${error}`,
                confirmButtonText: 'OK',
            });
            cargando.style.display = 'none';
        });
}

function gestionar_reporte(numero, opc,placa,bodega) {

    if (opc === 0) {
        swal.fire({
            title: 'TODO OK',
            html: `<button class="btn btn-success btn-lg">&#128077;</button>`,
            icon: 'success',
            showDenyButton: true,
            confirmButtonColor: '#3085d6',
            denyButtonColor: '#dc3741',
            confirmButtonText: 'CONTINUAR',
            denyButtonText: `REGRESAR`,
            allowEnterKey: false,
            allowEscapeKey: false,
            allowOutsideClick: false,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                const data_report = new FormData();
                data_report.append('numero', numero);
                data_report.append('estado', opc)
                data_report.append('placa', placa)
                data_report.append('bodega', bodega)
                generar_reporte(data_report);
            }
        });
    } else if (opc === 1) {

        const { value } = Swal.fire({
            title: 'ALGO SALIO MAL',
            icon: 'warning',
            html: `<button class="btn btn-danger btn-lg">&#128078;</button>`,
            input: 'textarea',
            inputLabel: 'Escriba aquí las observaciones',
            showDenyButton: true,
            confirmButtonText: 'CONTINUAR',
            denyButtonText: `REGRESAR`,
            confirmButtonColor: '#3085d6',
            denyButtonColor: '#dc3741',
            allowEnterKey: false,
            allowEscapeKey: false,
            allowOutsideClick: false,
            inputAttributes: {
                autocapitalize: 'ON',
                autocorrect: 'ON'
            },
            inputValidator: (value) => {
                if (!value || value.length < 10) {
                    return 'Agregue una observación mayor a 10 caracteres'
                } else {
                    const data_report = new FormData();
                    data_report.append('numero', numero);
                    data_report.append('estado', opc)
                    data_report.append('observacion', value)
                    data_report.append('placa', placa)
                    data_report.append('bodega', bodega)
                    generar_reporte(data_report);
                }
            }
        });
    }

}

function generar_reporte(data_report) {
    cargando.style.display = 'block';
    fetch(base_url + "orden_salida/insert_orden_salida", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
        body: data_report,
    })
        .then(function (response) {
            // Transforma la respuesta. En este caso lo convierte a JSON
            return response.json();
        })
        .then(function (json) {
            if (json['response'] === 'success') {

                swal.fire({
                    title: 'Exito',
                    html: `Se ha realizado con exito el registro de información`,
                    icon: 'success',
                    allowEnterKey: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    willClose: () => {
                        btnBuscarOrdenesByPlaca.click();
                    },
                });

            } else if (json['response'] === 'error') {
                swal.fire({
                    title: 'Error',
                    html: `Ha ocurrio un error a la hora de realizar el registro, intente nuevamente`,
                    icon: 'error'
                });
            }
            cargando.style.display = 'none';
        })
        .catch(function (error) {
            swal.fire({
                icon: 'error',
                title: 'Error',
                html: `Ha ocurrido un error en la api de la intranet de postventa, intente nuevamente.
                <strong>ERROR:</strong>${error}`,
                confirmButtonText: 'OK',
            });
            cargando.style.display = 'none';
        });
}


function generar_orden_salida(numero) {
    cargando.style.display = 'block';
    const data_update = new FormData();
    data_update.append('numero',numero);
    fetch(base_url + "orden_salida/update_orden_salida", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
        body: data_update,
    })
        .then(function (response) {
            // Transforma la respuesta. En este caso lo convierte a JSON
            return response.json();
        })
        .then(function (json) {
            if (json['response'] === 'success') {

                swal.fire({
                    title: 'Exito',
                    html: `Se ha realizado con exito el registro de información`,
                    icon: 'success',
                    allowEnterKey: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    willClose: () => {
                        tbody_table_ordenes_by_placa.innerHTML = "";
                    },
                });

            } else if (json['response'] === 'error') {
                swal.fire({
                    title: 'Error',
                    html: `Ha ocurrio un error a la hora de realizar el registro, intente nuevamente`,
                    icon: 'error'
                });
            }
            cargando.style.display = 'none';
        })
        .catch(function (error) {
            swal.fire({
                icon: 'error',
                title: 'Error',
                html: `Ha ocurrido un error en la api de la intranet de postventa, intente nuevamente.
                <strong>ERROR:</strong>${error}`,
                confirmButtonText: 'OK',
            });
            cargando.style.display = 'none';
        });
}
