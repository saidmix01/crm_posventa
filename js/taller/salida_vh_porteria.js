document.addEventListener("DOMContentLoaded", () => {
    load_data();

    setInterval(() => {
        console.log('Loading...');
        load_data();
    }, "60000");

});


const cargando = document.getElementById('cargando');
const contenedor_vh = document.getElementById('contenedor_vh');


function load_data() {
    cargando.style.display = 'block';

    fetch(base_url + "orden_salida/get_orden_salida_vh_porteria", {
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
            if (json['response'] === 'success') {

                contenedor_vh.innerHTML = json['html'];

            } else if (json['response'] === 'error') {
                contenedor_vh.innerHTML = json['html'];
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

function vh_salida(numero, placa) {
    cargando.style.display = 'block';
    const datos = new FormData();
    datos.append('numero', numero);
    fetch(base_url + "orden_salida/update_salida_vh", {
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
                swal.fire({
                    icon: 'success',
                    title: 'Exito',
                    html: `Se registro con exito la salid del vehículo con placa ${placa}`,
                    confirmButtonText: 'OK',
                    willClose: () => {
                        location.reload();
                    },
                });
            } else if (json['response'] === 'error') {
                swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    html: `Ha ocurrido un error en el sistema, intente nuevamente el registro de salida para el vehículo con placa: ${placa}`,
                    confirmButtonText: 'OK',
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




