document.addEventListener("DOMContentLoaded", () => {
    /* alert('SE ESTA CARGANDO DESPUES DEL DOM'); */
});

const newspaperSpinning = [{ background: "yellow" }, { background: "none" }];
const newspaperTiming = { duration: 500, iterations: 5, };

const inputPlacaOrden = document.getElementById('inputPlacaOrden');
const btnBuscarOrdenesByPlaca = document.getElementById('btnBuscarOrdenesByPlaca');
const card_encuesta = document.getElementById('card-encuesta');
const validarPropietario = document.getElementById('validarPropietario');
const updateInfo = document.getElementById('updateInfo');
const btnIsOwner = document.getElementById('btnIsOwner');
const isOwner = document.getElementsByName('isOwner');

const form_encuesta = document.getElementById('form_encuesta');
const fieldNit = document.getElementById('fieldNit');
const fieldName = document.getElementById('fieldName');
const fieldMail = document.getElementById('fieldMail');
const fieldPhone = document.getElementById('fieldPhone');
const fieldMailUpdate = document.getElementById('fieldMailUpdate');
const fieldPhoneUpdate = document.getElementById('fieldPhoneUpdate');
const fieldBodega = document.getElementById('fieldBodega');
const fieldModelo = document.getElementById('fieldModelo');
const fieldOrden = document.getElementById('fieldOrden');
const fieldPlaca = document.getElementById('fieldPlaca');
const alertInfoVh = document.getElementById('alertInfoVh');
const isYourVh = document.getElementById('isYourVh');

const btnUpdateInfo = document.getElementById('btnUpdateInfo');
const btn_env_encuesta = document.getElementById('btn_env_encuesta');
const btnisYourVh = document.getElementById('btnisYourVh');
const btnContinueInfo = document.getElementById('btnContinueInfo');

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
    fetch(base_url + "orden_salida/encuesta_by_placa", {
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
                paintInfoCliente(json);
                isYourVh.style.display = 'block';
                validarPropietario.style.display = 'none';
                updateInfo.style.display = 'none';
                card_encuesta.style.display = 'none';
            } else if (json['response'] === 'warning') {
                swal.fire({
                    title: 'Advertencia',
                    html: `${json['msm']}`,
                    icon: 'warning'
                });
                card_encuesta.style.display = 'none';
            } else {
                swal.fire({
                    title: 'Error',
                    html: `El vehículo de placa ${placa} no se encuentra disponible para generar la orden de salida.`,
                    icon: 'warning'
                });
                isYourVh.style.display = 'none';
                validarPropietario.style.display = 'none';
                updateInfo.style.display = 'none';
                card_encuesta.style.display = 'none';
            }
            cargando.style.display = 'none';
        })
        .catch(function (error) {
            swal.fire({
                icon: 'error',
                title: 'Error',
                html: `Ha ocurrido un error en la api de la intranet de postventa, intente nuevamente.`,
                confirmButtonText: 'OK',
            });
            cargando.style.display = 'none';
        });
}

btnisYourVh.addEventListener('click', function (event) {
    isYourVh.style.display = 'none';
    updateInfo.style.display = 'none';
    validarPropietario.style.display = 'block';
    card_encuesta.style.display = 'none';
});

btnIsOwner.addEventListener('click', function (event) {
    var selected = document.querySelector('input[type=radio][name=isOwner]:checked');
    if (selected === null) {
        swal.fire({
            title: 'ADVERTENCIA',
            html: `Seleccione una opción`,
            icon: 'warning',
            willClose: () => {
                isOwner[0].parentNode.parentNode.parentNode.animate(newspaperSpinning, newspaperTiming);
                isOwner[0].parentNode.parentNode.parentNode.focus();
            },
        });
    } else if (selected.value === '1') {
        updateInfo.style.display = 'block';
        validarPropietario.style.display = 'none';
        card_encuesta.style.display = 'none';
        isYourVh.style.display = 'none';
    } else if (selected.value === '0') {
        generar_orden_salida();
    }
});

function paintInfoCliente(data) {
    fieldNit.value = data['nit_comprador'];
    fieldName.value = data['nombres'];
    fieldMail.value = data['mail'];
    fieldPhone.value = data['celular'];
    fieldMailUpdate.value = data['mail'];
    fieldPhoneUpdate.value = data['celular'];
    fieldBodega.value = data['bodega'];
    fieldModelo.value = data['des_modelo'];
    fieldOrden.value = data['numero'];
    fieldPlaca.value = data['placa'];

    alertInfoVh.innerHTML = `MARCA: ${data['marca']}<br>DESCIPCIÓN: ${data['des_modelo']}<br>COLOR: ${data['color']}`;

}

fieldMail.addEventListener("blur", function () {
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!expr.test(fieldMail.value)) {
        Swal.fire({
            title: 'Advertencia',
            html: `Error: La dirección de correo <strong>${fieldMail.value}</strong> es incorrecta.`,
            icon: 'warning',
            confirmButtonText: 'Ok',
            allowOutsideClick: false,
            showCloseButton: true,
            willClose: () => {
                fieldMail.animate(newspaperSpinning, newspaperTiming);
                fieldMail.focus();
                fieldMail.value = "";
            }
        });
    }
});

btnUpdateInfo.addEventListener('click', function (event) {

    if (fieldMailUpdate.value === fieldMail.value && fieldPhoneUpdate.value === fieldPhone.value) {

    } else {
        if (fieldMailUpdate.value != "" && fieldPhoneUpdate.value != "") {
            cargando.style.display = 'block';
            const infoCliente = new FormData();
            infoCliente.append('fieldMailUpdate', fieldMailUpdate.value);
            infoCliente.append('fieldPhoneUpdate', fieldPhoneUpdate.value);
            infoCliente.append('fieldNit', fieldNit.value);
            infoCliente.append('fieldOrden', fieldOrden.value);

            fetch(base_url + "orden_salida/updateTerceros", {
                headers: {
                    "Content-type": "application/json",
                },
                mode: 'no-cors',
                method: "POST",
                body: infoCliente,
            })
                .then(function (response) {
                    // Transforma la respuesta. En este caso lo convierte a JSON
                    return response.json();
                })
                .then(function (json) {
                    if (json['response'] === 'success') {

                        swal.fire({
                            icon: 'success',
                            title: 'Exito',
                            html: `Desea realizar la Encuesta de satisfacción`,
                            showCloseButton: true,
                            showDenyButton: true,
                            showCloseButton: false,
                            focusConfirm: false,
                            confirmButtonText: '<i class="fa fa-thumbs-up"> SI</i>',
                            denyButtonText: '<i class="fa fa-thumbs-down"> NO</i>',
                            allowEnterKey: false,
                            allowEscapeKey: false,
                            allowOutsideClick: false,

                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                validarPropietario.style.display = 'none';
                                updateInfo.style.display = 'none';
                                card_encuesta.style.display = 'block';
                                isYourVh.style.display = 'none';
                            } else if (result.isDenied) {
                                validarPropietario.style.display = 'none';
                                updateInfo.style.display = 'none';
                                card_encuesta.style.display = 'none';
                                isYourVh.style.display = 'none';
                            }
                        });
                    }
                    cargando.style.display = 'none';
                })
                .catch(function (error) {
                    swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: `Ha ocurrido un error en la api de la intranet de postventa, intente nuevamente.`,
                        confirmButtonText: 'OK',
                    });
                    cargando.style.display = 'none';
                });

        } else {

            swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                html: `El formulario tiene campos vacios`,
                confirmButtonText: 'OK',
                willClose: () => {
                    fieldMailUpdate.value ? '' : fieldMailUpdate.animate(newspaperSpinning, newspaperTiming);
                    fieldPhoneUpdate.value ? '' : fieldPhoneUpdate.animate(newspaperSpinning, newspaperTiming);

                    fieldMailUpdate.value ? '' : fieldMailUpdate.focus();
                    fieldPhoneUpdate.value ? '' : fieldPhoneUpdate.focus();
                },
            });
        }
    }

});


function generar_orden_salida(propietario = 0) {
    const datos = new FormData();
    datos.append('numero', fieldOrden.value);
    datos.append('propietario', propietario);
    fetch(base_url + "orden_salida/isOwnerNot", {
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
                Swal.fire({
                    icon: 'success',
                    title: 'Exito',
                    html: `Ya puedes retirar el vehículo de las instalaciones`,
                    willClose: () => {
                        location.reload();
                    },
                });
            } else if (json['response'] === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Advertencia',
                    html: `Ha ocurrido un error en el sistema intente nuevamente`,
                    willClose: () => {
                        location.reload();
                    },
                });
            }
            cargando.style.display = 'none';
            updateInfo.style.display = 'none';
            validarPropietario.style.display = 'none';
            card_encuesta.style.display = 'none';
            isYourVh.style.display = 'none';
        })
        .catch(function (error) {
            swal.fire({
                icon: 'error',
                title: 'Error',
                html: `Ha ocurrido un error en la api de la intranet de postventa, intente nuevamente.`,
                confirmButtonText: 'OK',
            });
            cargando.style.display = 'none';
        });
}

btn_env_encuesta.addEventListener('click', function (event) {
    const datos = new FormData(form_encuesta);
    datos.append('placa', fieldPlaca.value);
    datos.append('bod', fieldBodega.value);
    datos.append('numero', fieldOrden.value);
    datos.append('fieldNit', fieldNit.value);
    fetch(base_url + "orden_salida/insert_respuesta_encuesta", {
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
                Swal.fire({
                    icon: 'success',
                    title: 'Exito',
                    html: `Gracias por contestar la encuesta, ya puedes retirar el vehículo`,
                    confirmButtonText: '<i class="fa fa-thumbs-up"> OK</i>',
                    willClose: () => {
                        location.reload();
                    }
                });
            }
            cargando.style.display = 'none';
        })
        .catch(function (error) {
            swal.fire({
                icon: 'error',
                title: 'Error',
                html: `Ha ocurrido un error en la api de la intranet de postventa, intente nuevamente.`,
                confirmButtonText: 'OK',
            });
            cargando.style.display = 'none';
        });

});

btnContinueInfo.addEventListener('click', function (event) {

    swal.fire({
        icon: 'success',
        title: 'Exito',
        html: `Desea realizar la Encuesta de satisfacción`,
        showCloseButton: true,
        showDenyButton: true,
        showCloseButton: false,
        focusConfirm: false,
        confirmButtonText: '<i class="fa fa-thumbs-up"> SI</i>',
        denyButtonText: '<i class="fa fa-thumbs-down"> NO</i>',
        allowEnterKey: false,
        allowEscapeKey: false,
        allowOutsideClick: false,

    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            validarPropietario.style.display = 'none';
            updateInfo.style.display = 'none';
            card_encuesta.style.display = 'block';
            isYourVh.style.display = 'none';
        } else if (result.isDenied) {

            generar_orden_salida(1);

        }
    });

});