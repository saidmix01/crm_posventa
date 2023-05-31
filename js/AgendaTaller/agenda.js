$(document).ready(function () {
    getBodegas();
    $('#comboSede').select2({ theme: "classic" });
    //loadCalendario();
    captureIdCoti();
});


const clientSocket = io.connect('http://localhost:8081', {
    transports: ["websocket"]
});




clientSocket.on('connect', () => {
    console.log('conectado');
    loadCalendario();
});

clientSocket.on('disconnect', () => {
    console.log('conectado');
    Swal.fire({
        title: 'Advertencia!',
        text: 'Servidor Desconectado, contactar a sistemas ',
        icon: 'warning',
        allowEscapeKey: false,
        allowOutsideClick: false
    });
});


clientSocket.on('recibir_mensaje', (data) => {
    console.log(data);
    if (data == "si") {
        loadCalendario();
    }
});


function confirmarAgendaGral() {
    const params = new URLSearchParams(location.search);
    const flag = params.get('update');
    if (flag) {
        const bod = document.getElementById('comboSede').value;
        updateCitaReprogramacion(document.getElementById('idCita').value, bod);
    }else{
        openModalListaChequeo();
    }
}


function captureIdCoti() {
    const params = new URLSearchParams(location.search);
    const idCotizacion = params.get('idCotizacion');
    if (idCotizacion) {
        crearCitaV2(idCotizacion);
        getCotizacionesPendientes(idCotizacion);
        document.getElementById('btnConfirmarAgenda').style.display = "block";
    }
}

function getBodegas() {
    var result = document.getElementById("comboSede");
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            result.innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", baseURL + "AgendaTaller/getBoedegas", true);
    xmlhttp.send();
}

function getCotizacionesPendientes(idCotizacion) {
    var result = document.getElementById("external-events");
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            result.innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", baseURL + "AgendaTaller/getCotizacionesPendientes?idCotizacion=" + idCotizacion, true);
    xmlhttp.send();
}

function getNombreCliente(idCita) {
    var result = document.getElementById("nomEncargado");
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            result.value = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", baseURL + "AgendaTaller/getNombreEncargado?idCita=" + idCita, true);
    xmlhttp.send();
}

function openModalListaChequeo() {
    $('#modalListaChequeo').modal('show');
    getNombreCliente(document.getElementById('idCita').value);
}

function confirmarAgenda() {
    const idCita = document.getElementById('idCita').value;
    const listaChequeo = document.getElementById('listaChequeo').value;
    const nomEncargado = document.getElementById('nomEncargado').value;
    const bod = document.getElementById('comboSede').value;
    if (!idCita || !listaChequeo || !nomEncargado || !bod) {
        Swal.fire({
            title: 'Error!',
            text: 'No se ha generado el id de la cita',
            icon: 'error',
            confirmButtonText: 'Ok'
        });
    } else {
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                const resp = xmlhttp.responseText;
                if (resp == "ok") {
                    Swal.fire({
                        title: 'Exito!',
                        text: 'Cita Agendada Correctamente...',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            clientSocket.emit('enviar_mensaje', { from: "si" });
                            location.href = baseURL + 'AgendaTaller';
                        }
                    });

                } else if (resp == "exist") {
                    Swal.fire({
                        title: 'Advertencia!',
                        text: 'No se puede agendar la cita en esta fecha',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
                }
                else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error al Agendar la cita',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            }
        }
        xmlhttp.open("GET", baseURL + "AgendaTaller/confirmarAgenda?idCita=" + idCita + "&listaChequeo=" + listaChequeo + "&nomEncargado=" + nomEncargado + "&bod=" + bod, true);
        xmlhttp.send();
    }

}

function eliminarOperacionAgenda(idCitaOperacion, idCotizacion, estado) {
    console.log(estado);
    if (estado != "PE") {
        Swal.fire({
            title: 'Error!',
            text: 'Ya no se puede eliminar la cita',
            icon: 'error',
            confirmButtonText: 'Ok'
        });
    } else {
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                const resp = xmlhttp.responseText;
                if (resp == "ok") {
                    Swal.fire({
                        title: 'Exito!',
                        text: 'Cita Eliminada Correctamente...',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            clientSocket.emit('enviar_mensaje', { from: "si" });
                            location.reload();
                        }
                    });

                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error al Eliminada la cita',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            }
        }
        xmlhttp.open("GET", baseURL + "AgendaTaller/eliminarOperacionAgenda?idCitaOperacion=" + idCitaOperacion, true);
        xmlhttp.send();
    }

}

/* function getOperacionesCotizacion() {
    const params = new URLSearchParams(location.search);
    const idCotizacion = params.get('idCotizacion');
    var result = document.getElementById("formsOpe");
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            result.innerHTML = xmlhttp.responseText;
            $('#cargarOperacionesModal').modal('show');
        }
    }
    xmlhttp.open("GET", baseURL + "AgendaTaller/getOperacionesCotizacion?idCotizacion=" + idCotizacion, true);
    xmlhttp.send();
} */

function crearCita(idOperacion, idCotizacion) {
    const duracion = document.getElementById('horaDuracion' + idOperacion).value;
    const operacion = document.getElementById('operacion' + idOperacion).value;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    //xmlhttp.responseType = 'json';
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            const resp = xmlhttp.responseText;
            if (resp == "ok") {
                Swal.fire({
                    title: 'Exito!',
                    text: 'Cita Generada Correctamente...',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        getCotizacionesPendientes(idCotizacion);
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error al Generar la cita',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        }
    }
    xmlhttp.open("GET", baseURL + "AgendaTaller/crearCita?idCotizacion=" + idCotizacion + "&idOperacion=" + idOperacion + "&duracion=" + duracion + "&operacion=" + operacion, true);
    xmlhttp.send();
}

function crearCitaV2(idCotizacion) {
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            const resp = xmlhttp.responseText;
            if (resp == "err") {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error al Generarrrr la cita',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            }
            const idCita = document.getElementById('idCita').value = resp;
            crearCitaOperacion(idCotizacion, idCita);

        }
    }
    xmlhttp.open("GET", baseURL + "AgendaTaller/crearCitaV2?idCotizacion=" + idCotizacion, true);
    xmlhttp.send();
}

function crearCitaOperacion(idCotizacion, idCita) {
    const bod = document.getElementById("comboSede").value;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            const resp = xmlhttp.responseText;
            getCotizacionesPendientes(idCotizacion);
        }
    }
    xmlhttp.open("GET",
        baseURL + "AgendaTaller/crearCitaOperacion?idCotizacion=" + idCotizacion + "&idCita=" + idCita + "&bod=" + bod,
        true);
    xmlhttp.send();
}

function agendarCita(data) {
    const bod = document.getElementById("comboSede").value;
    const nitTec = data.resource.id;
    let fechaIni = data.dateStr;
    const idCita = document.getElementById('idCita').value;
    fechaIni = fechaIni.replace('Z', '');
    console.log(fechaIni);
    const { idOperacion, idCotizacion } = (JSON.parse(data.draggedEl.dataset.event));
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            const resp = xmlhttp.responseText;
            if (resp == "ok") {
                Swal.fire({
                    title: 'Exito!',
                    text: 'Cita Agendada Correctamente...',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        clientSocket.emit('enviar_mensaje', { from: "si" });
                    }
                });
            } else if (resp == "war") {
                Swal.fire({
                    title: 'Advertencia!',
                    text: 'No puedes Crear la cita en esta fecha',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
            }
            else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error al crear la cita',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
            getCotizacionesPendientes(idCotizacion);
            loadCalendario();
        }
    }
    xmlhttp.open("GET",
        baseURL + "AgendaTaller/agendarCitaV2?idOperacion=" + idOperacion + "&fechaIni=" + fechaIni + "&tec=" + nitTec + "&idCotizacion=" + idCotizacion + "&idCita=" + idCita + "&bod=" + bod,
        true);
    xmlhttp.send();
}


function loadCalendario() {
    let sede = document.getElementById("comboSede").value;
    if (!sede) {
        sede = 1;
    }
    arrCitas = getCitas(sede);
}

function reprogramarCita(idCita, idOperacion, idCotizacion) {
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            const resp = xmlhttp.responseText;
            if (resp == "ok") {
                Swal.fire({
                    title: 'Exito!',
                    text: 'Cita Reprogramada Correctamente...',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = baseURL + 'AgendaTaller?idCotizacion=' + idCotizacion + '&update=1';
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error al Reprogramar la cita',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        }
    }
    xmlhttp.open("GET", baseURL + "AgendaTaller/reprogramarCita?idCita=" + idCita + "&idOperacion=" + idOperacion, true);
    xmlhttp.send();
}

function updateCitaReprogramacion(idCita, bod) {
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            const resp = xmlhttp.responseText;
            if (resp == "ok") {
                Swal.fire({
                    title: 'Exito!',
                    text: 'Cita Reprogramada Correctamente...',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        clientSocket.emit('enviar_mensaje', { from: "si" });
                        location.href = baseURL + 'AgendaTaller';
                    }
                });
            } else if (resp == "exist") {
                Swal.fire({
                    title: 'Advertencia!',
                    text: 'Ya hay una cita agendada para esta fecha',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error al Reprogramar la cita',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        }
    }
    xmlhttp.open("GET", baseURL + "AgendaTaller/updateReprogramarCita?idCita=" + idCita + "&bod=" + bod, true);
    xmlhttp.send();
}

function cancelarCita(idCita) {
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            const resp = xmlhttp.responseText;
            if (resp == "ok") {
                Swal.fire({
                    title: 'Exito!',
                    text: 'Cita Cancelada Correctamente...',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                        clientSocket.emit('enviar_mensaje', { from: "si" });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error al Cancelar la cita',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        }
    }
    xmlhttp.open("GET", baseURL + "AgendaTaller/cancelarCita?idCita=" + idCita, true);
    xmlhttp.send();
}

function verInfoCitaOperacion(idCita,idOperacion){
    var result = document.getElementById("formInfoOpe");
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            result.innerHTML = xmlhttp.responseText;
            $('#verInfoCitaOperacionModal').modal('show');
        }
    }
    xmlhttp.open("GET", baseURL + "AgendaTaller/verInfoCitaOperacion?idCita=" + idCita+"&idOperacion="+idOperacion, true);
    xmlhttp.send();
}

function pintarCalendario(getTecnicos, getCitas) {
    var calendarEl = document.getElementById('calendar');
    var containerEl = document.getElementById('external-events');

    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendar.Draggable;





    new Draggable(containerEl, {
        itemSelector: '.fc-event',
        eventData: function (eventEl) {
            const data = eventEl.getAttribute('data-event');
            return JSON.parse(data);
        },
    });

    let calendar = new Calendar(calendarEl, {
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        timeZone: 'UTC',
        //initialView: 'resourceTimeGridDay',
        initialView: 'resourceTimelineWeek',
        aspectRatio: 2.5,
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            //right: 'resourceTimeGridDay,resourceTimeGridWeek'
            right: 'resourceTimelineDay,resourceTimelineWeek'
        },
        selectable: true,
        editable: false,
        resourceAreaHeaderContent: 'Técnicos',
        resources: getTecnicos,
        events: getCitas,
        hiddenDays: [0],

        businessHours: [{
            daysOfWeek: [1, 2, 3, 4, 5],
            startTime: '06:00',
            endTime: '12:00'
        },
        {
            daysOfWeek: [1, 2, 3, 4, 5],
            startTime: '14:00',
            endTime: '18:00'
        },
        {
            daysOfWeek: [6],
            startTime: '06:00',
            endTime: '12:00'
        },
        ],
        selectConstraint: 'businessHours',
        eventConstraint: "businessHours",
        allDaySlot: false,
        slotDuration: '00:10',
        slotMinTime: "06:00:00",
        slotMaxTime: "19:00:00",
        slotEventOverlap: false,
        eventOverlap: false,

        drop: function (info) {
            const params = new URLSearchParams(location.search);
            const idCotizacion = params.get('idCotizacion');

            const now = new Date();
            //console.log(now);

            const f = new Date(info.dateStr.replace("Z", ""));

            if (f < now) {
                Swal.fire({
                    title: 'Advertencia!',
                    text: 'No se puede agendar la cita en una fecha/hora anterior a la actual -> ' + now.toLocaleString(),
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
                loadCalendario();
            } else {
                Swal.fire({
                    title: '¿Desea Programar esta cita?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, agendar',
                    allowEscapeKey: false,
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        agendarCita(info);
                        info.draggedEl.parentNode.removeChild(info.draggedEl);


                    } else if (result.isDismissed) {
                        console.log("perra");
                        getCotizacionesPendientes(idCotizacion);
                        clientSocket.emit('enviar_mensaje', { from: "si" });

                    } else {
                        console.log("sdnasjdbsakd");
                        clientSocket.emit('enviar_mensaje', { from: "si" });
                    }
                });
            }
        },
        eventClick: function (arg) {
            //TODO: Modal ver info evento
            verInfoCitaOperacion(arg.event.extendedProps.idCita,arg.event.extendedProps.idOperacion);
        },
        eventDidMount: function (info) {

            info.el.addEventListener('contextmenu', function (ev) {
                ev.preventDefault();
                console.log(info.event.extendedProps);
                const estado = info.event.extendedProps.estado;
                const usu_act = info.event.extendedProps.id_usu_crea;
                if (estado == "PE") {
                    if (usu_act == usu) {
                        Swal.fire({
                            title: '¿Desea Eliminar esta Agenda?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, Eliminar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                eliminarOperacionAgenda(info.event.id, info.event.extendedProps.idCotizacion, info.event.extendedProps.estado);
                            }

                        });
                    } else {
                        Swal.fire({
                            title: 'Advertencia!',
                            text: 'Esta cita no es tuya...',
                            icon: 'warning',
                            confirmButtonText: 'Ok'
                        });
                    }

                } else if (estado == "P" || estado == "R") {
                    Swal.fire({
                        title: '¿Qué desea hacer?',
                        icon: 'warning',
                        showDenyButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Reprogramar Cita',
                        denyButtonText: 'Cancelar Cita'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            /**
                             * Metodo para reprogramar cita
                             * no olvidar la auditoria -- aun no se ha creado esta funcionalidad
                             */
                            reprogramarCita(info.event.extendedProps.idCita, info.event.extendedProps.idOperacion, info.event.extendedProps.idCotizacion);
                        }
                        if (result.isDenied) {
                            /**
                             * TODO: Metodo para cancelar cita
                             * no olvidar la auditoria -- aun no se ha creado esta funcionalidad
                             */
                            cancelarCita(info.event.extendedProps.idCita);
                        }
                    });
                }

            });
        }
        /*select: function (arg) {
            console.log(arg);
        } */
    });

    var columnCount = $('.fc-agendaDay-view th.fc-resource-cell').length;
    var viewWidth = $('.fc-view-container').width();
    var minViewWidth = 18 + columnCount * 100;
    if (minViewWidth > viewWidth) {
        $('.fc-view.fc-agendaDay-view.fc-agenda-view').css('width', minViewWidth + 'px');
    }

    calendar.render();



}


/* function getCitas(bod) {
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.responseType = 'json';
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            const respCitas = xmlhttp.response;
            console.log(respCitas);
            getTecnicos(respCitas, bod);
        }
    }

    xmlhttp.open("GET", baseURL + "AgendaTaller/getCitas?bod=" + bod, true);
    xmlhttp.send();
} */

function getCitas(bod) {
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.responseType = 'json';
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            const respCitas = xmlhttp.response;
            console.log(respCitas);
            getTecnicos(respCitas, bod);
        }
    }

    xmlhttp.open("GET", "http://localhost:8081/api/agenda/getcitas?bod=" + bod, true);
    xmlhttp.send();
}

function getTecnicos(citas, bod) {
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.responseType = 'json';
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            const respTec = xmlhttp.response;
            pintarCalendario(respTec, citas);
        }
    }

    xmlhttp.open("GET", baseURL + "AgendaTaller/getTecnicos?bod=" + bod, true);
    xmlhttp.send();
}