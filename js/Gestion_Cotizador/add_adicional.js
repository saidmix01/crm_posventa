/* Variables */
const table_rptos = document.getElementById('table_respuestos');
const table_mano = document.getElementById('table_mano_obra');
const tBody_rptos = document.getElementById('tBodyRepuestos');
const tBody_mano = document.getElementById('tBody_mano_obra');

const btnAllClass = document.getElementById('btnAllClass');
const btnAllClassClear = document.getElementById('btnAllClassClear');
const btnAllClassClearList = document.getElementById('btnAllClassClearList');
const btnAddFilaRepto = document.getElementById('btnAddFilaRepto')
const btnAddFilaManoObra = document.getElementById('btnAddFilaManoObra')
const btnSaveAdicional = document.getElementById('btnSaveAdicional')
const btnListAdicional = document.getElementById('btnListAdicional');
const btnAddAdicional = document.getElementById('btnAddAdicional');
const btnCreateAdicional = document.getElementById('btnCreateAdicional');
const btnCreateAdicionalDB = document.getElementById('btnCreateAdicionalDB');
const inputNameAdicional = document.getElementById('inputNameAdicional')
const selectClases = document.getElementById('selectClases');
const selectClasesList = document.getElementById('selectClasesList');
const formAddAdicional = document.getElementById('formAddAdicional');
const formCreateAdicional = document.getElementById('formCreateAdicional');
const card_padre = document.getElementById('card-padre');


const newspaperSpinning = [{ background: "yellow" }, { background: "none" }];
const newspaperTiming = { duration: 500, iterations: 5, };

const cargando = document.getElementById('cargando');

/* LISTAR ADICIONALES EN UN DATATABLE */
/* Variables */
const btnBuscarAdicional = document.getElementById('btnBuscarAdicional');
const selectAdicional = document.getElementById('selectAdicional');
const tableListAdicionales = document.getElementById('tableListAdicionales');
const table_reptos_list = document.getElementById('table_reptos_list');
const table_mano_list = document.getElementById('table_mano_list');

document.addEventListener("DOMContentLoaded", () => {
    $('.js-example-basic-single').select2({
        minimumResultsForSearch: 1,
        closeOnSelect: false,
        width: '100%',
        allowClear: true,
        placeholder: 'Seleccione una opción',
        closeOnSelect: false,
        scrollAfterSelect: false,
    });

    $('.js-example-basic-single-clases-list').select2({
        minimumResultsForSearch: 1,
        closeOnSelect: false,
        width: '100%',
        allowClear: true,
        placeholder: 'Seleccione una opción',
        closeOnSelect: false,
        scrollAfterSelect: false,
    });

    paintAdicionalesDisponible();
});

btnAllClass.addEventListener("click", function () {

    const array_clases = Object.values(selectClases.options);

    const clasesAll = array_clases.map(function (option) {
        if (option.value != "") {
            return option.value;
        }
    });

    $('.js-example-basic-single').val(clasesAll).trigger("change");
});


btnAllClassClear.addEventListener("click", function () {

    $('.js-example-basic-single').val(null).trigger("change");

});

btnAllClassClearList.addEventListener("click", function () {

    $('.js-example-basic-single-clases-list').val(null).trigger("change");

});

btnAddFilaRepto.addEventListener("click", function () {

    var bandera = validarFilasManoObra();

    if (bandera === false) {
        Swal.fire({
            title: 'Advertencia!',
            text: 'Para agregar una nueva fila o item de repuesto debe completar los campos de la anterior fila.',
            icon: 'warning',
            confirmButtonText: 'Ok'
        });
    } else {
        addRptoTable(bandera);
    }

});



/* FUNCIONES PARA LA TABLA DE MANO DE OBRA */

btnAddFilaManoObra.addEventListener("click", function () {
    const opcion = 1;
    var bandera = validarFilasManoObra(opcion);

    if (bandera === false) {
        Swal.fire({
            title: 'Advertencia!',
            text: 'Para agregar una nueva fila o item de mano de obra debe completar los campos de la anterior fila.',
            icon: 'warning',
            confirmButtonText: 'Ok'
        });
    } else {
        addManoObraTable(bandera);
    }

});

function validarFilasManoObra(opcion = "") {

    var classFila = "";
    if (opcion == 1) {
        classFila = 'fila_mano';
    } else {
        classFila = 'fila_rpto';
    }
    var total = [];

    $(`.${classFila}`).map(function () {

        return total.push(this.id);

    }).get();

    var bandera = total.length;

    if (total.length > 0) {
        var filas_mano_obra = new Array();
        filas_mano_obra.push($(`#${total[total.length - 1]} td`).map(function () {
            if (this.innerText == "") {
                return this.firstChild.value;
            } else {
                return this.innerText;
            }
        }).get());
        var filas_mano_obra_array = filas_mano_obra[0];
        if (filas_mano_obra_array[0] == "" || filas_mano_obra_array[1] == "" || filas_mano_obra_array[2] == "" || filas_mano_obra_array[3] == "") {
            return false;
        } else {
            const t = document.getElementById(`${total[total.length - 1]}`);
            bandera = parseInt(t.getAttribute('num_fila')) + 1;
            return bandera;
        }
    } else {
        return bandera;
    }

}

function addManoObraTable(bandera) {

    var fila = document.createElement("tr");
    fila.id = 'fila_mano_obra' + bandera;
    fila.classList.add('fila_mano');
    fila.setAttribute('num_fila', bandera)

    var column_1 = document.createElement("td");
    var column_2 = document.createElement("td");
    var column_3 = document.createElement("td");
    var column_4 = document.createElement("td");
    var column_5 = document.createElement("td");

    /* Creando Input para agregar el codigo de mano de obra */
    var textOperacion = document.createElement('textarea');
    textOperacion.id = 'textOperacionMano' + bandera;
    textOperacion.classList.add('form-control');
    textOperacion.placeholder = 'Escriba aquí la descripción de la operación';
    textOperacion.maxLength = 300;
    textOperacion.rows = 1;
    textOperacion.setAttribute("onKeyPress", `evitarCarateres()`);
    textOperacion.setAttribute("oninput", "this.value = this.value.toUpperCase()");




    column_1.appendChild(textOperacion);
    fila.appendChild(column_1);

    /* Creando Input para agregar la cantidad */
    var inputHoras = document.createElement('input');
    inputHoras.type = 'number';
    inputHoras.id = `cantHora_${bandera}`;
    inputHoras.classList.add('form-control');
    inputHoras.min = 0;
    inputHoras.step = 0.25;
    inputHoras.style.width = '100%';
    inputHoras.style.display = 'inline';

    column_2.appendChild(inputHoras);
    fila.appendChild(column_2);

    /* Creando Input para agregar la cantidad */
    var input5Years_mas = document.createElement('input');
    input5Years_mas.type = 'number';
    input5Years_mas.id = `mas5_${bandera}`;
    input5Years_mas.classList.add('form-control');
    input5Years_mas.style.width = '100%';
    input5Years_mas.style.display = 'inline';
    input5Years_mas.style.textAlign = 'right';
    input5Years_mas.setAttribute("onkeydown", `noPuntoComaE()`);


    column_3.appendChild(input5Years_mas);
    fila.appendChild(column_3);


    /* Creando Input para agregar la cantidad */
    var input5Years_menos = document.createElement('input');
    input5Years_menos.type = 'number';
    input5Years_menos.id = `menos5${bandera}`;
    input5Years_menos.classList.add('form-control');
    input5Years_menos.style.width = '100%';
    input5Years_menos.style.display = 'inline';
    input5Years_menos.style.textAlign = 'right';
    input5Years_menos.setAttribute("onkeydown", `noPuntoComaE()`);

    column_4.appendChild(input5Years_menos);
    column_4.id = `v_hora_${bandera}`;
    column_4.classList.add('valor_total_hora');
    fila.appendChild(column_4);

    /* Creando boton para eliinar la fila seleccionada */
    var btnEliminar = document.createElement('button');
    btnEliminar.type = 'button';
    btnEliminar.id = `delete_mano_${bandera}`;
    btnEliminar.classList.add('btn');
    btnEliminar.classList.add('btn-danger');
    btnEliminar.textContent = 'Eliminar';
    btnEliminar.setAttribute("onclick", `eliminarItemManoObra("${bandera}")`);

    column_5.appendChild(btnEliminar);
    fila.appendChild(column_5);

    tBody_mano.appendChild(fila);

}

/* Funcion para eliminar o quitar las filas en caso de error */
function eliminarItemManoObra(codigo) {
    $(`#fila_mano_obra${codigo}`).closest("tr").remove();
}

function addRptoTable(bandera) {
    cargando.style.display = "block";
    var fila = document.createElement("tr");
    fila.id = 'fila_repuesto' + bandera;
    fila.classList.add('fila_rpto');
    fila.setAttribute('num_fila', bandera)

    var column_1 = document.createElement("td");
    var column_2 = document.createElement("td");
    var column_3 = document.createElement("td");
    var column_4 = document.createElement("td");

    /* Creando Input para agregar el codigo de mano de obra */
    var codigoRpto = document.createElement('input');
    codigoRpto.type = 'text';
    codigoRpto.id = 'codigoRpto' + bandera;
    codigoRpto.classList.add('form-control');
    codigoRpto.placeholder = 'Escriba aquí el código del repuesto';
    codigoRpto.maxLength = 300;
    codigoRpto.setAttribute("onkeydown", `noPuntoComa()`);
    codigoRpto.setAttribute("onfocusout", `checkCodigo(this)`);
    codigoRpto.setAttribute("oninput", "this.value = this.value.toUpperCase()");



    column_1.appendChild(codigoRpto);
    fila.appendChild(column_1);

    /* Creando Input para agregar el codigo de mano de obra */
    var textDesc = document.createElement('textarea');
    textDesc.id = 'textDescRpto' + bandera;
    textDesc.classList.add('form-control');
    textDesc.placeholder = 'Escriba aquí la descripción del repuesto';
    textDesc.maxLength = 300;
    textDesc.rows = 1;
    textDesc.setAttribute("onKeyPress", `evitarCarateres()`);
    textDesc.setAttribute("oninput", "this.value = this.value.toUpperCase()");

    column_2.appendChild(textDesc);
    fila.appendChild(column_2);

    /* Creando Input para agregar la cantidad */
    var cantidad_repto = document.createElement('input');
    cantidad_repto.type = 'number';
    cantidad_repto.id = `cant_repto_${bandera}`;
    cantidad_repto.classList.add('form-control');
    cantidad_repto.style.width = '100%';
    cantidad_repto.style.display = 'inline';
    cantidad_repto.style.textAlign = 'right';
    cantidad_repto.setAttribute("onkeydown", `noPuntoComaE()`);

    column_3.appendChild(cantidad_repto);
    fila.appendChild(column_3);

    /* Creando boton para eliinar la fila seleccionada */
    var btnEliminar = document.createElement('button');
    btnEliminar.type = 'button';
    btnEliminar.id = `year5menos_${bandera}`;
    btnEliminar.classList.add('btn');
    btnEliminar.classList.add('btn-danger');
    btnEliminar.textContent = 'Eliminar';
    btnEliminar.setAttribute("onclick", `eliminarItemRpto("${bandera}")`);

    column_4.appendChild(btnEliminar);
    fila.appendChild(column_4);

    tBody_rptos.appendChild(fila);
    cargando.style.display = "none";
}

/* Funcion para eliminar o quitar las filas en caso de error */
function eliminarItemRpto(codigo) {
    $(`#fila_repuesto${codigo}`).closest("tr").remove();
}

function noPuntoComaE(event) {

    var e = event || window.event;
    var key = e.keyCode || e.which;

    if (key === 110 || key === 190 || key === 188 || key === 69) {

        e.preventDefault();
    }
}

function noPuntoComa(event) {

    var e = event || window.event;
    var key = e.keyCode || e.which;

    if (key === 110 || key === 190 || key === 188) {

        e.preventDefault();
    }
}

btnSaveAdicional.addEventListener("click", function () {

    const array_clases_select = $('#selectClases').val();

    if (tBody_mano.childElementCount > 0 && array_clases_select.length > 0 && inputNameAdicional.value != "") {

        const datos = new FormData();
        datos.append('adicional', inputNameAdicional.value);
        datos.append('clase', array_clases_select);
        const idTrR_Mano = Object.values(tBody_mano.children).map(function (nodes) {
            return nodes.id;
        });
        datos.append('CantidadDatosMano', idTrR_Mano.length);
        const datosManoObra = [];
        const datosVaciosManoObra = [];
        for (let index = 0; index < idTrR_Mano.length; index++) {
            var contador = 0;
            datosManoObra.push($(`#${idTrR_Mano[index]} td`).map(function () {
                if (contador != 4) {
                    if (this.children[0].value == "") {
                        datosVaciosManoObra.push(this.children[0].id);
                    }
                    contador += 1;
                    return this.children[0].value;
                }
            }).get());
            datos.append('datosMano' + index, datosManoObra[index]);
        }

        datosVaciosManoObra.map(function (id) {
            document.getElementById(id).animate(newspaperSpinning, newspaperTiming);
            document.getElementById(id).focus();
        });



        const idTrR_rpto = Object.values(tBody_rptos.children).map(function (nodes) {
            return nodes.id;
        });
        datos.append('CantidadDatosRepuesto', idTrR_rpto.length);
        const datosRepuesto = [];
        const datosVaciosRpto = [];
        for (let index = 0; index < idTrR_rpto.length; index++) {
            var contador = 0;
            datosRepuesto.push($(`#${idTrR_rpto[index]} td`).map(function () {
                if (contador != 3) {
                    if (this.children[0].value == "") {
                        datosVaciosRpto.push(this.children[0].id);
                    }
                    contador += 1;
                    return this.children[0].value;
                }
            }).get());
            datos.append('datosRepuesto' + index, datosRepuesto[index]);
        }

        datosVaciosRpto.map(function (id) {
            document.getElementById(id).animate(newspaperSpinning, newspaperTiming);
            document.getElementById(id).focus();
        });

        if (datosVaciosRpto.length == 0 && datosVaciosManoObra.length == 0) {
            cargando.style.display = "block";
            addAdicionalDB(datos);

        } else {
            Swal.fire({
                title: 'Advertencia',
                html: `Tiene campos vacios en el formulario:`,
                icon: 'warning',
                confirmButtonText: 'Cerrar',
            });
        }


    } else {
        Swal.fire({
            title: 'Advertencia',
            html: `Tiene campos vacios en el formulario:`,
            icon: 'warning',
            confirmButtonText: 'Cerrar',
            willClose: () => {
                if (tBody_mano.childElementCount == 0) {
                    tBody_mano.parentElement.animate(newspaperSpinning, newspaperTiming);
                    tBody_mano.parentElement.focus();
                }
                if (array_clases_select.length == 0) {
                    selectClases.parentElement.children[4].children[0].children[0].animate(newspaperSpinning, newspaperTiming);
                    selectClases.parentElement.children[4].children[0].children[0].children[0].focus();
                    selectClases.parentElement.children[4].children[0].children[0].children[1].children[0].animate(newspaperSpinning, newspaperTiming);
                    selectClases.parentElement.children[4].children[0].children[0].children[0].focus();
                }
                if (inputNameAdicional.value == "") {
                    inputNameAdicional.parentElement.children[2].children[0].children[0].animate(newspaperSpinning, newspaperTiming);
                    inputNameAdicional.parentElement.children[2].children[0].children[0].focus();
                }
            },
        });
    }
});

function addAdicionalDB(datos) {

    fetch(base_url + "GestionCotizador/addAdicionalDB", {
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
            if (json['response'] === 'success') {
                swal.fire({
                    icon: 'success',
                    title: 'Exito',
                    html: json['msm'] + `<br>
                    <strong>Registros exitosos:</strong>
                    </br>Repuestos:  ${json['repuestos_add']} 
                    </br>Mano de Obra:  ${json['mano_add']}
                    </br><strong>Registros fallidos:</strong>
                    </br>Repuestos:  ${json['repuestos_fail']} 
                    </br>Mano de Obra:  ${json['mano_fail']}`,
                    confirmButtonText: 'OK',
                });

            } else if (json['response'] === 'error') {
                swal.fire({
                    icon: 'error',
                    title: 'Advertencia',
                    html: json['msm'],
                    confirmButtonText: 'OK',
                });
            }
            console.table(json['mano_add'])
            console.table(json['repuestos_add'])
            cargando.style.display = "none";
        })
        .catch(function (error) {
            swal.fire({
                icon: 'error',
                title: 'Error',
                html: 'Ha ocurrido un error en la api de la intranet de postventa, intente nuevamente.',
                confirmButtonText: 'OK',
            });
            cargando.style.display = "none";
        });
}
/* NAVEGACION */
btnAddAdicional.addEventListener('click', function () {
    tableListAdicionales.style.display = 'none';
    formCreateAdicional.style.display = 'none';
    formAddAdicional.style.display = 'block';
});

btnListAdicional.addEventListener('click', function () {

    getListAdicionales();
    formAddAdicional.style.display = 'none';
    formCreateAdicional.style.display = 'none';
    tableListAdicionales.style.display = 'block';

});
btnCreateAdicional.addEventListener('click', function () {
    paintAdicionalesDisponible();
    formAddAdicional.style.display = 'none';
    tableListAdicionales.style.display = 'none';
    formCreateAdicional.style.display = 'block';

});
/* LISTAR ADICIONALES */
function getListAdicionales() {
    cargando.style.display = 'block';
    fetch(base_url + "GestionCotizador/getNameAdicionales", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
    })
        .then(function (response) {
            // Transforma la respuesta. En este caso lo convierte a JSON
            return response.json();
        })
        .then(function (json) {
            cargando.style.display = 'none';
            if (json['response'] === 'success') {

                selectAdicional.innerHTML = json['data'];

                swal.fire({
                    icon: 'success',
                    title: 'Exito',
                    html: '',
                    confirmButtonText: 'OK',
                });

                $('.js-example-basic-single-list-ad').select2({
                    width: '100%',
                    allowClear: true,
                    theme: 'classic',
                    placeholder: 'Seleccione un adicional',
                    closeOnSelect: false,
                    scrollAfterSelect: false,
                });

            } else if (json['response'] === 'error') {
                swal.fire({
                    icon: 'error',
                    title: 'Advertencia',
                    html: '',
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
            cargando.style.display = 'none';
        });
}

btnBuscarAdicional.addEventListener('click', function () {

    cargando.style.display = 'block';
    const array_clases_select_list = $('#selectClasesList').val();
    const dataListAdicional = new FormData();
    dataListAdicional.append('adicional', selectAdicional.value);
    dataListAdicional.append('clases', array_clases_select_list);

    fetch(base_url + "GestionCotizador/getListAdicionalesAll", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
        body: dataListAdicional,
    })
        .then(function (response) {
            // Transforma la respuesta. En este caso lo convierte a JSON
            return response.json();
        })
        .then(function (json) {
            $('#' + table_reptos_list.id).dataTable().fnDestroy();
            $('#' + table_mano_list.id).dataTable().fnDestroy();

            table_reptos_list.tBodies[0].innerHTML = json['tbodyRepuestos'];
            table_mano_list.tBodies[0].innerHTML = json['tbodyMano'];

            loadDatatable(table_reptos_list.id);
            loadDatatable(table_mano_list.id);

            cargando.style.display = 'none';

        })
        .catch(function (error) {
            swal.fire({
                icon: 'error',
                title: 'Error',
                html: 'Ha ocurrido un error en la api de la intranet de postventa, intente nuevamente.',
                confirmButtonText: 'OK',
            });
            cargando.style.display = 'none';
        });
});



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

function checkCodigo(codigo) {

    if (codigo.value != "") {
        console.log("Realizando check al codigo del repuesto...", codigo.value);
        cargando.style.display = 'block';

        const formCodigo = new FormData();
        formCodigo.append('codigo', codigo.value);
        fetch(base_url + "GestionCotizador/checkCodigoRepto", {
            headers: {
                "Content-type": "application/json",
            },
            mode: 'no-cors',
            method: "POST",
            body: formCodigo
        })
            .then(function (response) {
                // Transforma la respuesta. En este caso lo convierte a JSON
                return response.json();
            })
            .then(function (json) {
                if (json['response'] === 'success' && json['alterno'] !== '') {
                    swal.fire({
                        icon: 'error',
                        title: 'Advertencia',
                        html: 'El codigo ingresado existe en la base de datos de repuestos y tiene un codigo alterno',
                        confirmButtonText: json['codigo'],
                        showDenyButton: true,
                        denyButtonText: json['alterno'],
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        permitirEnterKey: false
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            codigo.value = json['codigo'];
                        } else if (result.isDenied) {
                            codigo.value = json['alterno'];
                        }
                    });
                } else if (json['response'] === 'error') {
                    swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: `El codigo ingresado: ${codigo.value} no existe en la base de datos de repuestos`,
                        confirmButtonText: 'OK',
                        willClose: () => {
                            codigo.value = "";
                            codigo.animate(newspaperSpinning, newspaperTiming);
                            codigo.focus();
                        },
                    });
                }
                cargando.style.display = 'none';
            })
            .catch(function (error) {
                swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: 'Ha ocurrido un error en la api de la intranet de postventa, intente nuevamente.',
                    confirmButtonText: 'OK',
                });
                cargando.style.display = 'none';
            });
    }

}

card_padre.addEventListener("focusin", (event) => {
    event.target.style.background = "lightblue";
});

card_padre.addEventListener("focusout", (event) => {
    event.target.style.background = "";
});

/* SCRIPT PARA EVITAR QUE SE AGREGUEN CARACTERES ESPECIALES */
inputCreateAdicional.addEventListener('keypress', function (event) {

    var e = event || window.event;
    var key = e.keyCode || e.which;

    if ((key >= 97 && key <= 122) || (key >= 65 && key <= 90) || key === 32 || key === 209 || key === 241) {

    } else {
        e.preventDefault();
    }

});

btnCreateAdicionalDB.addEventListener('click', function (event) {
    if (inputCreateAdicional.value != "" && inputCreateAdicional.value.length > 5) {
        saveNewAdicional(inputCreateAdicional.value);
    } else {
        swal.fire({
            title: 'Advertencia',
            html: `El campo para crear el adicional se encuentra vacio o cuenta con menos de 5 caracteres`,
            icon: 'warning',
            willClose: () => {
                inputCreateAdicional.animate(newspaperSpinning, newspaperTiming);
                inputCreateAdicional.focus();
            },
        });
    }
});


function saveNewAdicional(adicional) {
    cargando.style.display = 'block';
    const adicionalData = new FormData();
    adicionalData.append('adicional', adicional);
    fetch(base_url + "GestionCotizador/createNewAdicional", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
        body: adicionalData
    })
        .then(function (response) {
            // Transforma la respuesta. En este caso lo convierte a JSON
            return response.json();
        })
        .then(function (json) {
            if (json['response'] === 'success') {
                swal.fire({
                    icon: 'success',
                    title: 'Error',
                    html: 'Se ha creado con exito el nuevo adicional',
                    confirmButtonText: 'OK',
                });
                paintAdicionalesDisponible();
            } else if (json['response'] === 'error') {
                swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: 'Ha ocurrido un error, y no se ha realizado el registro',
                    confirmButtonText: 'OK',
                });
            } else if (json['response'] === 'existe') {
                swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    html: 'El adicional que estas ingresando como nuevo ya existe',
                    confirmButtonText: 'OK',
                });
                inputCreateAdicional.value = "";
            }
            cargando.style.display = 'none';
        })
        .catch(function (error) {
            swal.fire({
                icon: 'error',
                title: 'Error',
                html: 'Ha ocurrido un error en la api de la intranet de postventa, intente nuevamente.',
                confirmButtonText: 'OK',
            });
            cargando.style.display = 'none';
        });
}

function paintAdicionalesDisponible() {
    cargando.style.display = 'block';
    fetch(base_url + "GestionCotizador/paintAdicionalesDisponible", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
    })
        .then(function (response) {
            // Transforma la respuesta. En este caso lo convierte a JSON
            return response.json();
        })
        .then(function (json) {
            document.getElementById('tableListAdicionalesBodyCreate').innerHTML = json['tabla'];
            document.getElementById('inputNameAdicional').innerHTML = json['select'];
            $('.select-adicional-disp').select2({
                closeOnSelect: true,
                width: '100%',
                allowClear: true,
                placeholder: 'Seleccione una opción',
                scrollAfterSelect: false,
            });
            cargando.style.display = 'none';
        })
        .catch(function (error) {
            swal.fire({
                icon: 'error',
                title: 'Error',
                html: 'Ha ocurrido un error en la api de la intranet de postventa, intente nuevamente.',
                confirmButtonText: 'OK',
            });
            cargando.style.display = 'none';
        });
}


function deleteItemAdicionalR(seq, codigo, adicional) {
    cargando.style.display = 'block';

    const itemsR = new FormData();
    itemsR.append('seq', seq);
    itemsR.append('codigo', codigo);
    itemsR.append('adicional', adicional);


    fetch(base_url + "GestionCotizador/deleteItemAdicionalR", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
        body: itemsR,
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
                    html: 'Se ha realizado con exito la eliminación del item seleccionado',
                    confirmButtonText: 'OK',
                    willClose: () => {
                        btnBuscarAdicional.click();
                    },

                });
            } else if (json['response'] === 'error') {
                swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: 'Ha ocurrido un error, intente nuevamente',
                    confirmButtonText: 'OK',
                });
            }
            cargando.style.display = 'none';
        })
        .catch(function (error) {
            swal.fire({
                icon: 'error',
                title: 'Error',
                html: 'Ha ocurrido un error en la api de la intranet de postventa, intente nuevamente.',
                confirmButtonText: 'OK',
            });
            cargando.style.display = 'none';
        });
}

function deleteItemAdicionalM(id, operacion, adicional) {
    cargando.style.display = 'block';

    const itemsM = new FormData();
    itemsM.append('id', id);
    itemsM.append('operacion', operacion);
    itemsM.append('adicional', adicional);

    fetch(base_url + "GestionCotizador/deleteItemAdicionalM", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
        body: itemsM,
    })
        .then(function (response) {
            // Transforma la respuesta. En este caso lo convierte a JSON
            return response.json();
        })
        .then(function (json) {
            if (json['response'] === 'success') {
                swal.fire({
                    title: 'Exito',
                    icon: 'success',
                    html: 'Se ha realizado con exito la eliminación del item seleccionado',
                    confirmButtonText: 'OK',
                    willClose: () => {
                        btnBuscarAdicional.click();
                    },

                });
            } else if (json['response'] === 'error') {
                swal.fire({
                    title: 'Error',
                    icon: 'error',
                    html: 'Ha ocurrido un error, intente nuevamente',
                    confirmButtonText: 'OK',
                });
            }
            cargando.style.display = 'none';
        })
        .catch(function (error) {
            swal.fire({
                icon: 'error',
                title: 'Error',
                html: 'Ha ocurrido un error en la api de la intranet de postventa, intente nuevamente.',
                confirmButtonText: 'OK',
            });
            cargando.style.display = 'none';
        });
}


/* SCRIPT PARA EVITAR QUE SE AGREGUEN CARACTERES ESPECIALES */
function evitarCarateres (event) {

    var e = event || window.event;
    var key = e.keyCode || e.which;
    if ((key >= 97 && key <= 122) || (key >= 65 && key <= 90) || (key >= 48 && key <= 57) || key === 32 || key === 209 || key === 241) {

    } else {
        e.preventDefault();
    }

}