/* Adding an event listener to the window object. The event listener is listening for the load event.
When the load event occurs, the function is called. The function is called loadSelect. */

window.addEventListener('load', function () {
    loadSelect();
    $('.sidebar-mini').addClass('sidebar-collapse');
});


const titlePage = document.title;

// Cuando el usuario pierde el foco o sale de tu pestaña (sitio web)
window.addEventListener("blur", () => {
    document.title = "Seguir cotizando";
});

// Cuando el enfoque del usuario vuelve a tu pestaña (sitio web) nuevamente
window.addEventListener("focus", () => {
    document.title = `${titlePage}`;
});
const cargando = document.getElementById('cargando');
/* Variables de contenedores */
let containerCotizador = document.getElementById("container_table_body");
let containerTotales = document.getElementById("container_table_footer");
let tablaAC = document.getElementById("tabla_ACDelco");
let tablaGM = document.getElementById("tabla_GM");
let container_buttons = document.getElementById("container_buttons");

/* Variables de clases para recorrer por filas */

const rptos_pesados_ACdelco = 'rptos_pesados_ACDelco';
const mano_pesados_ACdelco = 'mano_pesados_ACDelco';
const rptos_pesados_GM = 'rptos_pesados_GM';
const mano_pesados_GM = 'mano_pesados_GM';

/* Variables contenedores de totales */

const subToReptoAC = document.getElementById('subToReptoAC');
const subToManoAC = document.getElementById('subToManoAC');
const totalCotizacionAC = document.getElementById('totalCotizacionAC');

const subToReptoGM = document.getElementById('subToReptoGM');
const subToManoGM = document.getElementById('subToManoGM');
const totalCotizacionGM = document.getElementById('totalCotizacionGM');

/* It's a variable declaration. */
/* Variables del formulario */
let inputPrepagado = document.getElementById("inputPrepagado");
let inputPlaca = document.getElementById('inputPlaca');
let inputDocCliente = document.getElementById('inputDocCliente');
let inputPhone = document.getElementById('inputPhone');
let inputName = document.getElementById('inputName');
let inputMail = document.getElementById('inputMail');
let inputClase = document.getElementById('inputClase');
let inputDescripcion = document.getElementById('inputDescripcion');
let inputModelo = document.getElementById('inputModelo');
let inputYear = document.getElementById('inputYear');
let inputKmActual = document.getElementById('inputKmActual');
let inputKmEstimado = document.getElementById('inputKmEstimado');
let inputKmCliente = document.getElementById('inputKmCliente');
let inputBodega = document.getElementById('inputBodega');
let inputRevision = document.getElementById('inputRevision');
let inputComentarios = document.getElementById('inputComentarios');


/* Array de variables de formulario */

const arrayInputs = [
    inputPlaca,
    inputDocCliente,
    inputPhone,
    inputName,
    inputMail,
    inputClase,
    inputDescripcion,
    inputModelo,
    inputYear,
    inputKmCliente,
    inputBodega,
    inputRevision,
    inputComentarios
]

const newspaperSpinning = [{ background: "yellow" }, { background: "none" }];
const newspaperTiming = { duration: 500, iterations: 5, };

/**
 * It's a function that is adding the data to the input fields.
 * @param placa - The license plate number
 * @returns The data that is returned from the server, formatted according to the dataType parameter or
 * the dataFilter callback function, if specified.
 */
function loadInfoClient(placa) {
    if (placa.length >= 6) {
        document.getElementById('cargando').style.display = 'block';
        const form = new FormData();
        form.append("placa", placa);
        fetch(base_url + "CotizadorPesados/getInfoClient", {
            headers: {
                "Content-type": "application/json",
            },
            mode: 'no-cors',
            method: "POST",
            body: form,
        })
            .then(function (response) {
                // Transforma la respuesta. En este caso lo convierte a JSON
                return response.json();
            })
            .then(function (json) {
                if (json['response'] == 'success') {
                    Swal.fire({
                        title: 'Exito',
                        text: 'La placa ingresada se encuentra registrada en la base de datos',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });
                    clearInputInfoClient();
                    addInfoClient(json['data']);
                    addRevisionVh(json['data_revision']);
                    inputPrepagado.value = json['isPrepagado'];
                } else if (json['response'] == 'warning') {
                    Swal.fire({
                        title: 'Advertencia',
                        html: `La placa ingresada no cuenta con revision de mantenimiento disponible en este modulo
                        <br/><strong>Nota:</strong> Este cotizador es para vehículos pesados.`,
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
                    clearInputInfoClient();
                }
                else if (json['response'] == 'error') {
                    Swal.fire({
                        title: 'Error',
                        text: 'La placa ingresada no se encuentra registrada en la base de datos',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                    clearInputInfoClient();
                }

            })
            .catch(function (error) {
                Swal.fire({
                    title: 'Advertencia',
                    html: `Ha ocurrido un error al realizar la peticion a la api de la Intranet de Posventa
                    <br/><strong>Nota:</strong> Este cotizador es para vehículos pesados.`,
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
            });

    } else {
        Swal.fire({
            title: 'Advertencia!',
            text: 'El campo se encuentra vacio o debe tener al menos 6 caracteres',
            icon: 'warning',
            confirmButtonText: 'Ok'
        });
    }
}
/**
 * It's a function that is adding the data to the input fields.
 * @param data - The data that is returned from the server, formatted according to the dataType
 * parameter or the dataFilter callback function, if specified.
 */
function addInfoClient(data) {
    inputDocCliente.value = data[0].nit;
    inputPhone.value = data[0].celular;
    inputName.value = data[0].cliente;
    inputMail.value = data[0].mail;
    inputClase.value = data[0].clase;

    /* A jQuery function that is selecting the option with the value of the data[0].clase and the
    innerText of the data[0].descripcion. */
    $('#inputDescripcion option').map(function () {
        if (this.innerText == `${data[0].descripcion}` && this.value == `${data[0].clase}`) {
            this.selected = true;
        }
    });
    /* inputModelo.value = data[0].des_modelo; */

    let optModelo = document.createElement('option');
    optModelo.value = data[0].des_modelo;
    optModelo.innerHTML = data[0].des_modelo;
    inputModelo.appendChild(optModelo);

    inputYear.value = data[0].year;
    inputKmActual.value = data[0].kilometraje;
    inputKmEstimado.value = data[0].km_estimado;
    inputKmCliente.min = Number.parseInt(data[0].kilometraje) + 1;

    inputKmCliente.disabled = false;
    inputBodega.disabled = false;
    inputRevision.disabled = false;

    loadSelect();
}
/**
 * Clear all the input fields and enable the ones that are disabled.
 */

function clearInputInfoClient() {

    inputDocCliente.value = "";
    inputPhone.value = "";
    inputName.value = "";
    inputMail.value = "";
    inputClase.value = "";
    inputDescripcion.value = "";
    inputModelo.value = "";
    inputYear.value = "";
    inputKmActual.value = "";
    inputKmEstimado.value = "";
    inputKmCliente.value = "";
    inputBodega.value = "";
    inputRevision.value = "";
    inputPrepagado.value = "";


    inputDocCliente.disabled = false;
    inputPhone.disabled = false;
    inputName.disabled = false;
    inputMail.disabled = false;
    inputClase.disabled = true;
    inputDescripcion.disabled = false;
    inputYear.disabled = false;
    inputKmActual.disabled = true;
    inputKmEstimado.disabled = true;
    inputKmCliente.disabled = false;
    inputBodega.disabled = false;
    $('#inputModelo option').remove();
    $('#inputRevision option').remove();
    loadSelect();

    tablaAC.innerHTML = "";
    tablaGM.innerHTML = "";

    /* Variables contenedores de totales */
    subToReptoAC.innerText = "";
    subToManoAC.innerText = "";
    totalCotizacionAC.innerText = "";
    subToReptoGM.innerText = "";
    subToManoGM.innerText = "";
    totalCotizacionGM.innerText = "";

    container_buttons.innerHTML = "";
}


/**
 * This function will load the select2 plugin on all elements with the class 'js-example-basic-single'
 * and apply the following settings: width: 100%, placeholder: '', theme: 'classic'.
 */

function loadSelect() {
    $('.js-example-basic-single-desc').select2({
        width: '100%',
        placeholder: 'Seleccione la descripción',
        theme: "classic",
        allowClear: true
    });
    $('.js-example-basic-single-model').select2({
        width: '100%',
        placeholder: 'Seleccione el modelo',
        theme: "classic",
        allowClear: true
    });
    $('.js-example-basic-single-year').select2({
        width: '100%',
        placeholder: 'Año del modelo',
        theme: "classic",
        allowClear: true
    });
    $('.js-example-basic-single-bodega').select2({
        width: '100%',
        placeholder: 'Seleccione una sede',
        theme: "classic",
        allowClear: true
    });
    $('.js-example-basic-single-revision').select2({
        width: '100%',
        placeholder: 'Seleccione una revisión',
        theme: "classic",
        allowClear: true
    });
    $('.js-example-basic-single').select2({
        width: '100%',
        placeholder: 'Seleccione una opción',
        theme: "classic",
        allowClear: true
    });
    document.getElementById('cargando').style.display = 'none';
}

/**
 * It takes a string as an argument, sends it to a PHP file, and then displays a message based on the
 * response from the PHP file.
 * @param clase - the class of the vehicle
 * @returns {
 *     "response": "success",
 *     "data": {
 *         "id": "1",
 *         "clase": "C",
 *         "modelo": "C-HR",
 *         "precio": "0",
 *         "precio_dolar": "0",
 *         "precio_euro": "0",
 */
function loadModelVh(clase) {
    document.getElementById('cargando').style.display = 'block';
    /* La siguiente linea agrega la clase al input  */
    inputClase.value = clase;

    const form = new FormData();
    form.append("clase", clase);
    fetch(base_url + "CotizadorPesados/getInfoModelVh", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
        body: form,
    })
        .then(function (response) {
            // Transforma la respuesta. En este caso lo convierte a JSON
            return response.json();
        })
        .then(function (json) {
            if (json['response'] == 'success') {
                clearTable();
                addModelVh(json['data_model']);
                addRevisionVh(json['data_revision']);
            } else if (json['response'] == 'error') {
                Swal.fire({
                    title: 'Advertencia',
                    text: 'La clase ingresada no se encuentra registrada en la base de datos',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
                $('#inputModelo option').remove();
                $('#inputRevision option').remove();

            }
            document.getElementById('cargando').style.display = 'none';

        })
        .catch(function (error) {
            Swal.fire({
                title: 'Advertencia',
                html: `Ha ocurrido un error al realizar la peticion a la api de la Intranet de Posventa
                    <br>Error: ${error}
                    <br/><strong>Nota:</strong> Este cotizador es para vehículos pesados.`,
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
            document.getElementById('cargando').style.display = 'none';
        });
}

/**
 * It takes an array of objects, creates an option element for each object, and appends each option to
 * the select element.
 * @param data - the data you want to add to the select
 */
function addModelVh(data) {

    $('#inputModelo option').remove();

    let opt = document.createElement('option');
    opt.value = '';
    opt.innerHTML = 'Seleccione un modelo';
    inputModelo.appendChild(opt);

    data.map(function (d) {
        let opt = document.createElement('option');
        opt.value = d.descripcion;
        opt.innerHTML = d.descripcion;
        inputModelo.appendChild(opt);
    });
    inputModelo.disabled = false;

}

function addRevisionVh(data) {

    $('#inputRevision option').remove();

    let optRevision = document.createElement('option');
    optRevision.value = '';
    optRevision.innerHTML = 'Seleccione una revisión';
    inputRevision.appendChild(optRevision);

    data.map(function (d) {
        optRevision = document.createElement('option');
        optRevision.value = d.revision;
        optRevision.innerHTML = d.revision;
        inputRevision.appendChild(optRevision);
    });
    inputRevision.disabled = false;
}
function validarEmail(email) {
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!expr.test(email)) {
        Swal.fire({
            title: 'Advertencia',
            text: "Error: La dirección de correo " + email + " es incorrecta.",
            icon: 'warning',
            confirmButtonText: 'Ok',
            allowOutsideClick: false,
            showCloseButton: true,
            willClose: () => {

                document.getElementById('inputMail').animate(newspaperSpinning, newspaperTiming);
                document.getElementById('inputMail').focus();
                document.getElementById('inputMail').value = "";
            }
        });
    }
}
function validarKmVh(kmCliente) {
    if (Number.parseInt(inputKmActual.value) != "" && Number.parseInt(kmCliente) < Number.parseInt(inputKmActual.value)) {
        Swal.fire({
            title: 'Advertencia',
            html: `<strong>Error:</strong> El Kilometraje indicado por el cliente <strong>${kmCliente}</strong> debe ser mayor al actual: <strong>${inputKmActual.value}</strong>`,
            icon: 'warning',
            confirmButtonText: 'Ok',
            allowOutsideClick: false,
            willClose: () => {
                inputKmCliente.animate(newspaperSpinning, newspaperTiming);
                inputKmCliente.focus();
                inputKmCliente.value = "";
            }
        });


    }
}
function loadInfoMtto() {
    const inputsVoid = arrayInputs.filter(function (input) {
        if (input.tagName != 'TEXTAREA') {
            return input.value == '';
        }
    });
    if (inputsVoid.length == 0) {
        getInfoMtto();
    } else {
        const nameInput = inputsVoid.map(function (input) {
            return input.previousElementSibling.innerText;
        }).join(", ");

        Swal.fire({
            title: 'Advertencia',
            html: `Para cargar la información del mantenimiento, debe completar todos los campos del formulario: <strong>${nameInput}</strong>`,
            icon: 'warning',
            confirmButtonText: 'Ok',
            willClose: () => {
                alertFieldsVoids(inputsVoid);
            }
        });
    }
}

function alertFieldsVoids(arrayInputs) {
    arrayInputs.map(function (input) {

        if ((input.value == "")) {
            if (input.type == 'select-one') {
                let idSelect = `select2-${input.id}-container`;
                let selectSpan = document.getElementById(idSelect);
                selectSpan.animate(newspaperSpinning, newspaperTiming);
                selectSpan.focus();
            } else {
                input.animate(newspaperSpinning, newspaperTiming);
                input.focus();
                input.value = "";
            }
        }
    });
}

function getInfoMtto() {
    cargando.style.display = 'block';
    const formulario = new FormData();
    formulario.append("clase", inputClase.value);
    formulario.append("revision", inputRevision.value);
    formulario.append("bodega", inputBodega.value);
    formulario.append("yearModel", inputYear.value);

    fetch(base_url + "CotizadorPesados/getInfoMtto", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
        body: formulario,
    }).then(function (response) {
        // Transforma la respuesta. En este caso lo convierte a JSON
        return response.json();
    }).then(function (json) {
        /* containerRepuestos.style.display = 'block'; */
        if (json['response'] == 'success') {
            Swal.fire({
                title: 'Exito',
                text: 'Cargando la revision del mantenimiento seleccionado',
                icon: 'success',
                confirmButtonText: 'Ok'
            });

            /*Agregamos la información de los repuestos y mano de obra en las tablas asignadas*/
            clearTable();
            paintTable(json['tableAC'], json['tableGM']);
            /* Habilitar los botones para guardar la cotización */
            container_buttons.innerHTML = '<div class="col-12"><button type="button" class="btn btn-warning m-1" onclick="agendarCotizacion(1);">Guardar</button><button type="button" class="btn btn-success m-1" onclick="agendarCotizacion(2);">Agendar</button></div>';
        } else if (json['response'] == 'error') {
            Swal.fire({
                title: 'Error',
                html: `No se ha encontrado información para la <strong>revisión de ${inputRevision.value}</strong> seleccionada. `,
                icon: 'error',
                confirmButtonText: 'Ok'
            });
            clearTable();
        }

    }).catch(function (error) {
        Swal.fire({
            title: 'Advertencia',
            html: `Ha ocurrido un error al realizar la peticion a la api de la Intrante de Posventa
                    <br/>Error: ${error}
                    <br/><strong>Nota:</strong> Este cotizador es para vehículos pesados.`,
            icon: 'warning',
            confirmButtonText: 'Ok'
        });
        clearTable();
        cargando.style.display = 'none';
    });
}
function clearTable() {
    tablaGM.style.display = 'block';
    tablaAC.style.display = 'block';
    tablaAC.innerHTML = "";
    tablaGM.innerHTML = "";
    tablaAC.removeAttribute('class');
    tablaAC.classList.add("col-xl-6", "col-lg-6", "col-auto", "table-responsive");
    tablaGM.removeAttribute('class');
    tablaGM.classList.add("col-xl-6", "col-lg-6", "col-auto", "table-responsive");

    /* Variables contenedores de totales */
    subToReptoAC.innerText = "";
    subToManoAC.innerText = "";
    totalCotizacionAC.innerText = "";
    subToReptoGM.innerText = "";
    subToManoGM.innerText = "";
    totalCotizacionGM.innerText = "";

    /* Deshabilitar botones de guardar y agendar */
    container_buttons.innerHTML = "";

    cargando.style.display = "none";
}
function paintTable(tableAC, tableGM) {

    if (tableAC.tableIsEmptyRACDelco > 0) {
        tablaAC.innerHTML = tableAC.tableRACDelco;
    }
    if (tableAC.tableIsEmptyMACDelco > 0) {
        tablaAC.innerHTML += tableAC.tableMACDelco;
    }
    if (tableGM.tableIsEmptyRGM > 0) {
        tablaGM.innerHTML = tableGM.tableRGM;
    }
    if (tableGM.tableIsEmptyMGM > 0) {
        tablaGM.innerHTML += tableGM.tableMGM;
    }

    if (tableGM.tableIsEmptyRGM == 0 && tableGM.tableIsEmptyMGM == 0) {
        tablaGM.style.display = 'none';
        tablaAC.classList.remove("col-xl-6", "col-lg-6");
        tablaAC.classList.add("col-xl-12", "col-lg-12", "col-auto", "table-responsive");
    }
    if (tableAC.tableIsEmptyRACDelco == 0 && tableAC.tableIsEmptyMACDelco == 0) {
        tablaAC.style.display = 'none';
        tablaGM.classList.remove("col-xl-6", "col-lg-6");
        tablaGM.classList.add("col-xl-12", "col-lg-12", "col-auto", "table-responsive");
    }

    updateStatusPrices();

}

function updateStatusPrices(seqrpto = "", clase = "", grupo = "") {
    if (seqrpto) {
        const inputCheck1 = $(`#rpto_${seqrpto.getAttribute('seqrpto')}_ACDelco`);
        const inputCheck2 = $(`#mano_${seqrpto.getAttribute('seqrpto')}_ACDelco`);
        const inputCheck3 = $(`#rpto_${seqrpto.getAttribute('seqrpto')}_GM`);
        const inputCheck4 = $(`#mano_${seqrpto.getAttribute('seqrpto')}_GM`);
        if (grupo == "ACDelco") {
            if (clase == 'R') {
                checkedEstado(inputCheck1, 'R', inputCheck2);
            } else {
                checkedEstado(inputCheck2, 'M', inputCheck1);
            }
        } else {
            if (clase == 'R') {
                checkedEstado(inputCheck3, 'R', inputCheck4);
            } else {
                checkedEstado(inputCheck4, 'M', inputCheck3);
            }
        }
    } else {
        cargando.style.display = 'none';
    }
    sumTablaACdelco();
    sumTablaGM();
}

function checkedEstado(inputCheck1, grupo = "", inputCheck2) {
    if (grupo == 'R') {
        inputCheck1.map(function () {
            if (this.children[7].children[0].checked) {
                this.children[5].innerText = 'Autorizado';

                inputCheck2.map(function () {
                    this.children[6].children[0].checked = true;
                    this.children[4].innerText = 'Autorizado';
                });

            } else {
                this.children[5].innerText = 'No autorizado';

                inputCheck2.map(function () {
                    this.children[6].children[0].checked = false;
                    this.children[4].innerText = 'No autorizado';
                });

            }
        });
    } else {
        inputCheck1.map(function () {
            if (this.children[6].children[0].checked) {
                this.children[4].innerText = 'Autorizado';
                inputCheck2.map(function () {
                    this.children[7].children[0].checked = true;
                    this.children[5].innerText = 'Autorizado';
                });
            } else {
                this.children[4].innerText = 'No autorizado';
                inputCheck2.map(function () {
                    this.children[7].children[0].checked = false;
                    this.children[5].innerText = 'No autorizado';
                });
            }
        });
    }
}


function sumRepuestos(classValores, numNode) {
    let sumR = 0;
    $(`.${classValores}`).map(function () {
        if (this.children[numNode].children[0].checked == true) {
            sumR += Number.parseInt(formatDeleteDots(this.children[numNode - 1].innerText));
        }
    });
    return sumR;
}
function sumHoras(classValores) {
    let sumR = 0;
    $(`.${classValores}`).map(function () {
        if (this.children[6].children[0].checked == true) {
            sumR += Number.parseFloat(this.children[3].innerText);
        }
    });
    return sumR;
}

function sumTablaACdelco() {

    document.getElementById('HorasACDelco').innerText = sumHoras(mano_pesados_ACdelco);

    const sumR_AC = sumRepuestos(rptos_pesados_ACdelco, 7);
    const sumM_AC = sumRepuestos(mano_pesados_ACdelco, 6);

    subToReptoAC.innerText = formatAddDots(sumR_AC);
    subToManoAC.innerText = formatAddDots(sumM_AC);

    totalCotizacionAC.innerText = formatAddDots(sumR_AC + sumM_AC);

}
function sumTablaGM() {

    document.getElementById('HorasGM').innerText = sumHoras(mano_pesados_GM);

    const sumR_GM = sumRepuestos(rptos_pesados_GM, 7);
    const sumM_GM = sumRepuestos(mano_pesados_GM, 6);

    subToReptoGM.innerText = formatAddDots(sumR_GM);
    subToManoGM.innerText = formatAddDots(sumM_GM);

    totalCotizacionGM.innerText = formatAddDots(sumR_GM + sumM_GM);
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

/* Función para guardar la información */
function agendarCotizacion(opcion) {
    let inputRadioGrupo = document.querySelector('input[name="inputRadioGrupo"]:checked');

    const inputsVoid = arrayInputs.filter(function (input) {
        return input.value == '';
    });

    if (!inputsVoid.length == 0) {

        const nameInput = inputsVoid.map(function (input) {
            return input.previousElementSibling.innerText;
        }).join(", ");

        Swal.fire({
            title: 'Advertencia',
            html: `Para guardar la información de la cotización, debe completar todos los campos del formulario: <strong>${nameInput}</strong>`,
            icon: 'warning',
            confirmButtonText: 'Ok',
            willClose: () => {
                alertFieldsVoids(inputsVoid);
            }
        });
    } else if (inputRadioGrupo) {
        validarInfoCotizacion(inputRadioGrupo.value, opcion);
    } else {
        Swal.fire({
            title: 'Advertencia',
            html: `Para guardar la información de la cotización, debe seleccionar el grupo: <strong>ACDelco o GM</strong> el cual desea cotizar`,
            icon: 'warning',
            confirmButtonText: 'Ok',
            willClose: () => {
                $('.radioGrupo').map(function () {
                    this.animate(newspaperSpinning, newspaperTiming);
                    this.focus();
                });
            }
        });
    }
}

function validarInfoCotizacion(grupo, opcion) {

    const bodyReptosACDelco = document.getElementById('bodyReptosACDelco');
    const bodyManoACDelco = document.getElementById('bodyManoACDelco');
    const bodyReptosGM = document.getElementById('bodyReptosGM');
    const bodyManoGM = document.getElementById('bodyManoGM');

    if (grupo == 'ACDelco') {
        if (bodyReptosACDelco != null || bodyManoACDelco != null) {
            saveInfoCotizacion(bodyReptosACDelco, bodyManoACDelco, opcion, grupo);
        } else {
            swal.fire({
                title: 'Error',
                html: `El grupo seleccionado no cuenta con cotización`,
                icon: 'warning',
                confirmButtonText: 'OK',
            });
        }
    } else if (grupo == 'GM') {
        if (bodyReptosGM != null || bodyManoGM != null) {
            saveInfoCotizacion(bodyReptosGM, bodyManoGM, opcion, grupo);
        } else {
            swal.fire({
                title: 'Error',
                html: `El grupo seleccionado no cuenta con cotización`,
                icon: 'warning',
                confirmButtonText: 'OK',
            });
        }
    } else {
        swal.fire({
            title: 'Adventencia',
            html: `Para guardar la información de la cotización, debe seleccionar el grupo: <strong>ACDelco o GM</strong> el cual desea cotizar`,
            icon: 'warning',
            confirmButtonText: 'OK',
        });
    }

}

function saveInfoCotizacion(bodyR, BodyM, opcion, grupo) {
    cargando.style.display = 'block';
    const datos = new FormData();
    datos.append('nombreCliente', inputName.value);
    datos.append('nitCliente', inputDocCliente.value);
    datos.append('telfCliente', inputPhone.value);
    datos.append('placa', inputPlaca.value);
    datos.append('clase', inputClase.value);
    datos.append('descripcion', inputDescripcion.value);
    datos.append('des_modelo', inputModelo.value);
    datos.append('kilometraje_actual', inputKmActual.value);
    datos.append('kilometraje_estimado', inputKmEstimado.value);
    datos.append('kilometraje_cliente', inputKmCliente.value);
    datos.append('bodega', inputBodega.value);
    datos.append('revision', inputRevision.value);
    datos.append('emailCliente', inputMail.value);
    datos.append('observaciones', inputComentarios.value);
    datos.append('estado', opcion);
    datos.append('grupo_reptos', grupo);

    if (bodyR.childElementCount > 0) {
        const idTrR = Object.values(bodyR.childNodes).map(function (nodes) {
            return nodes.id;
        });
        datos.append('CantidadDatosRepuesto', idTrR.length);
        const datosRepuesto = [];
        for (let index = 0; index < idTrR.length; index++) {
            datosRepuesto.push($(`#${idTrR[index]} td`).map(function () {
                return this.innerText;
            }).get());
            datos.append('datosRepuesto' + index, datosRepuesto[index]);
        }
    }
    if (BodyM.childElementCount > 0) {
        const idTrM = Object.values(BodyM.childNodes).map(function (nodes) {
            return nodes.id;
        });
        datos.append('CantidadDatosMano', idTrM.length);
        const datosRepuesto = [];
        for (let index = 0; index < idTrM.length; index++) {
            datosRepuesto.push($(`#${idTrM[index]} td`).map(function () {
                return this.innerText;
            }).get());
            datos.append('datosMano' + index, datosRepuesto[index]);
        }
    }

    fetch(base_url + "CotizadorPesados/saveInfoCotizacion", {
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
            cargando.style.display = 'none';
            if (json['result'] === 'success') {
                sendEmailCotizacion(json);
            } else if (json['result'] === 'error') {
                swal.fire({
                    icon: 'error',
                    title: 'Advertencia',
                    html: 'No se pudo guardar la cotización',
                    confirmButtonText: 'OK',
                });
            }

        })
        .catch(function (error) {
            swal.fire({
                icon: 'error',
                title: 'Error',
                html: 'Ha ocurrido un error en la api de la intranet de postventa, intente nuevamente.',
                confirmButtonText: 'OK',
            });
        });
}

function sendEmailCotizacion(data) {
    cargando.style.display = 'block';
    const infoEmail = new FormData();
    infoEmail.append('id_cotizacion', data['id_cotizacion']);
    infoEmail.append('placa_vh', data['placa_vh']);
    infoEmail.append('estado', data['estado']);

    fetch(base_url + "CotizadorPesados/sendEmailCotizacion", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
        body: infoEmail,
    })
        .then(function (response) {
            // Transforma la respuesta. En este caso lo convierte a JSON
            return response.json();
        })
        .then(function (json) {
            cargando.style.display = 'none';
            if (json['result'] === 'success') {
                Swal.fire({
                    title: 'Exito',
                    text: 'Se ha guardado la cotización y se ha enviado el correo con exito',
                    icon: 'success',
                    confirmButtonText: 'Ver cotizacion',
                    denyButtonText: 'Cerrar',
                    allowOutsideClick: false,
                    showCloseButton: false,
                    showDenyButton: true,
                }).then((result) => {
                    /* Confirmar*/
                    if (result.isConfirmed) {
                        verCotizacion(json);
                        document.location.reload();
                    } else if (result.isDenied) {
                        document.location.reload();
                    }
                });
            } else if (json['result'] === 'error') {
                swal.fire({
                    icon: 'error',
                    title: 'Advertencia',
                    html: 'No se pudo guardar la cotización',
                    confirmButtonText: 'OK',
                });
            }

        })
        .catch(function (error) {
            swal.fire({
                icon: 'error',
                title: 'Error',
                html: 'Ha ocurrido un error en la api de la intranet de postventa, intente nuevamente.',
                confirmButtonText: 'OK',
            });
        });
}


function verCotizacion(data) {
    /* Creamos un elemento tipo form->Formulario con metodo post y accion */
    var mapForm = document.createElement("form");
    mapForm.target = "Cotizacion";
    mapForm.method = "POST";
    mapForm.action = base_url + "CotizadorPesados/verPdfCotizacion";
    /* Creamos los input dentro del formulario creado anteriormente */
    var varId = document.createElement("input");
    varId.type = "hidden";
    varId.name = "id";
    varId.value = data['id_cotizacion'];
    mapForm.appendChild(varId);

    var varPlaca = document.createElement("input");
    varPlaca.type = "hidden";
    varPlaca.name = "placa";
    varPlaca.value = data['placa_vh'];
    mapForm.appendChild(varPlaca);

    /* Agregamos el formulario creado al body */
    document.body.appendChild(mapForm);
    /* Script para abrir una nueva ventana */
    map = window.open("", "Cotizacion", "status=0,title=0,height=600,width=800,scrollbars=1");

    if (map) {
        mapForm.submit();
    }
}


function FnPosibleRetorno() {
    document.getElementById('placaPosibleRetorno').value = inputPlaca.value;
    $('#modalPosibleRetorno').modal('show');
}

function aggPosibleRetorno() {

    const placa = document.getElementById('placaPosibleRetorno');
    const tipo_retorno = document.getElementById('tipoPosibleRetorno');
    const observacion = document.getElementById('obsPosibleRetorno');
    const bodega = document.getElementById('bodegaPosibleRetorno');

    if (placa.value != "" && tipo_retorno.value > 0 && observacion.value != "" && observacion.value.length > 30 && bodega.value != "") {

        const formPosibleRetorno = new FormData();
        formPosibleRetorno.append("placa", placa.value);
        formPosibleRetorno.append("tipo_retorno", tipo_retorno.value);
        formPosibleRetorno.append("observacion", observacion.value);
        formPosibleRetorno.append("bodega", bodega.value);


        fetch(base_url+"Posible_retorno/create_posible_retorno", {
                headers: {
                    "Content-type": "application/json",
                },
                mode: 'no-cors',
                method: "POST",
                body: formPosibleRetorno,
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(json) {
                console.log(json);
                if (json['response'] == 'success') {

                    Swal.fire({
                        title: 'Exito',
                        html: `${json['msm']}`,
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });

                    placa.value = "";
                    tipo_retorno.value = "";
                    observacion.value = "";

                    $('#modalPosibleRetorno').modal('hide');

                } else if (json['response'] == 'error') {
                    Swal.fire({
                        title: 'Error',
                        html: `${json['msm']}`,
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
                }
            })
            .catch(function(error) {
                Swal.fire({
                    title: 'Error',
                    html: `Ha ocurrido un error al realizar la peticion a la api de la Intranet de Posventa, intente nuevamente`,
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
            });
    } else {
        Swal.fire({
            title: 'Advertencia',
            html: `Los siguientes campos se encuentran vacios:`,
            icon: 'warning',
            confirmButtonText: 'Ok',
            willClose: () => {
                if (placa.value == "") {
                    placa.animate(newspaperSpinning, newspaperTiming);
                    placa.focus();
                }
                if (tipo_retorno.value == "") {
                    tipo_retorno.parentElement.children[2].children[0].children[0].children[0].animate(newspaperSpinning, newspaperTiming);
                    tipo_retorno.parentElement.children[2].children[0].children[0].children[0].focus();
                }
                if (observacion.value == "" || observacion.length < 30) {
                    observacion.animate(newspaperSpinning, newspaperTiming);
                    observacion.focus();
                }
                if (bodega.value == "") {
                    bodega.animate(newspaperSpinning, newspaperTiming);
                    bodega.focus();
                }
            }
        });
    }
}






