const cargando = document.getElementById('cargando')
const year = document.getElementById('year');
const tecnico = document.getElementById('tecnico');
const sede = document.getElementById('sede');
document.addEventListener("DOMContentLoaded", function (){
    
    $("#year").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true,
        startDate: '2023',
    });

    $('.js-example-basic-single').select2({
        width: '100%',
        placeholder: 'Seleccione una opción',
        theme: "classic",
        allowClear: true
    });
    
    loadGraphic(year.value);
});

const btnGenerar = document.getElementById("loadGraph");
btnGenerar.addEventListener("click", function () {

    if (year.value != "") {
        loadGraphic(year.value);
    }


}, false);

function loadGraphic(year) {
    cargando.style.display = 'block';

    const data = new FormData();
    data.append('year', year);
    data.append('tecnico', tecnico.value);
    data.append('sede', sede.value);



    fetch(baseURL + "Posible_retorno/loadGraph", {
        headers: {
            "Content-type": "application/json",
        },
        mode: 'no-cors',
        method: "POST",
        body: data,
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (json) {
            if (json['response'] == 'success') {

                graph(json['entradas'], json['retornos'], json['posibles']);


            } else if (json['response'] == 'error') {
                Swal.fire({
                    title: 'Error',
                    html: `No se ha encontrado información.`,
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
                cargando.style.display = 'none';
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
            cargando.style.display = 'block';
        });

}


function graph(entradas, retornos, posibles) {


    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        theme: "light2",
        title: {
            text: "Entradas Vs. Retornos"
        },
        axisX: {
            title: "Meses del año",
            valueFormatString: ''
        },
        axisY: {
            prefix: "",
            /* labelFormatter: addSymbols */
        },
        toolTip: {
            shared: true
        },
        legend: {
            cursor: "pointer",
            itemclick: toggleDataSeries
        },
        data: [
            {
                type: "column",
                name: "Entradas",
                showInLegend: true,
                yValueFormatString: "#,##0",
                dataPoints: entradas
            },
            {
                type: "line",
                name: "Retornos",
                showInLegend: true,
                yValueFormatString: "#,##0",
                dataPoints: retornos
            },
            {
                type: "area",
                name: "Posibles Retornos",
                markerBorderColor: "white",
                markerBorderThickness: 2,
                showInLegend: true,
                yValueFormatString: "#,##0",
                dataPoints: posibles
            }]
    });
    chart.render();

    function addSymbols(e) {
        var suffixes = ["", "K", "M", "B"];
        var order = Math.max(Math.floor(Math.log(Math.abs(e.value)) / Math.log(1000)), 0);

        if (order > suffixes.length - 1)
            order = suffixes.length - 1;

        var suffix = suffixes[order];
        return CanvasJS.formatNumber(e.value / Math.pow(1000, order)) + suffix;
    }

    function toggleDataSeries(e) {
        if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
            e.dataSeries.visible = false;
        } else {
            e.dataSeries.visible = true;
        }
        e.chart.render();
    }


    cargando.style.display = 'none';

}

function changeSelectSede(){
    
        $('#sede').val(null).trigger('change');

}

function changeSelectTecncio(){

    $('#tecnico').val(null).trigger('change');
    /* $0.parentElement.children[2].children[0].children[0].children[1] */
}