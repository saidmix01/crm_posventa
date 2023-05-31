<?php $this->load->view('mantenimiento/header') ?>
<style>
    #tabla_uno {
        overflow: scroll;
        height: 75vh;
        width: 100%;
    }
    #tablatotal {
			width: 100%;
		}
        

    thead tr th {
        vertical-align: middle !important;
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #c1bfbf;
        color: black;
        vertical-align: middle;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="alert alert-light col-lg-12 text-center" role="alert">
            <h4>Informe de mantenimiento preventivo</h4>
        </div>
    </section>
    <!-- Main content -->
    <div class="loader" id="cargando"></div>
    <section class="content">

        <div class="card">

            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md-4 col-sm-6">
                        <label>Sede:</label>
                        <select class="form-control js-example-basic-single" name="filtroBodega" id="filtroBodega">
                                <option value="">Todas</option>
								<option value="Barrancabermeja">Barrancabermeja</option>
								<option value="Cucuta">Cúcuta</option>
								<option value="Giron">Girón</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4 col-sm-6">
                        <label>Estado:</label>
                        <select class="form-control js-example-basic-single"  name="filtroEstado" id="filtroEstado">
                            <option value="">Todos</option>
                            <option value="1">Pendiente</option>
                            <option value="2">En proceso</option>
                            <option value="3">Realizados</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4 col-sm-6" style="align-self: end;">
                        <button class="btn btn-info" onclick="BuscarFiltro();">Cargar</button>
                        <button class="btn btn-danger" onclick="location.reload();">Refrescar</button>
                        <button class="btn btn-success" onclick="descargarExcel();"><i class="fas fa-file-download">&nbsp;Descargar</i></button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive" id="tabla_uno">
                        <table class="table table-bordered" aria-describedby="descTable" id="tablatotal">
                            <thead>
                                <th scope="col">CODIGO EQUIPO</th>
                                <th scope="col">EQUIPO</th>
                                <th scope="col">ÁREA</th>
                                <th scope="col">BODEGA</th>
                                <th scope="col">DESCRIPCIÓN</th>
                                <th scope="col">OBSERVACIONES</th>
                                <th scope="col">DETALLE PIEZAS</th>
                                <th scope="col">RESPONSABLE</th>
                                <th scope="col">ENCARGADO</th>
                                <th scope="col">ESTADO</th>
                                <th scope="col">TIEMPO ESTIMADO[Horas]</th>
                                <th scope="col">FECHA SOLICITADA</th>
                                <th scope="col">FECHA REQUERIDA</th>
                                <th scope="col">FECHA INICIO</th>
                                <th scope="col">FECHA FINALIZADA</th>
                            </thead>
                            <tbody id="cargarTabla">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">

            </div>
        </div>
    </section>
    <!-- /.content -->

</div>
<?php $this->load->view('mantenimiento/footer') ?>
<script>
    $(document).ready(function() {
        $("#buscar_items").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#menu_items li").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        $('.js-example-basic-single').select2({
            theme: "classic",
            placeholder: "Seleccione una opción",
            width: "100%"
        });
        cargarInformePreventivo();


    });


    function cargarInformePreventivo() {
        var url = "<?= base_url() ?>mantenimiento/getInfPreventivo";
        document.getElementById('cargando').style.display = 'block';

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                $('#tablatotal').dataTable().fnDestroy();
                document.getElementById('cargarTabla').innerHTML = xmlhttp.responseText;
                loadDataTable();
                document.getElementById('cargando').style.display = 'none';
            }
        }

        xmlhttp.open("POST", url, true);
        xmlhttp.send();
    }

    function BuscarFiltro(){
        var estado = document.getElementById('filtroEstado').value;
        var bodega = document.getElementById('filtroBodega').value;
        if (estado != "" || bodega != "") {
            document.getElementById('cargando').style.display = 'block';
            var url = "<?= base_url() ?>mantenimiento/getInfPreventivo";
            var datos = new FormData();
            datos.append('estado',estado);
            datos.append('bodega',bodega);
            if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                $('#tablatotal').dataTable().fnDestroy();
                document.getElementById('cargarTabla').innerHTML = xmlhttp.responseText;
                loadDataTable();
                document.getElementById('cargando').style.display = 'none';
            }
        }

        xmlhttp.open("POST", url, true);
        xmlhttp.send(datos);

        } else {
            Swal.fire('Para realizar una busqueda por filtro, debe al menos seleccionar una bodega o estado.');
        }
        
    }

 

	function descargarExcel() {
        var f = new Date();
	    fecha = f.getDate() + "-" + (f.getMonth() + 1) + "-" + f.getFullYear();

		$("#tablatotal").table2excel({
			exclude: ".noExl",
			name: "Preventivo",
			filename: "Informe-Mantenimiento-" + "-" + fecha, //do not include extension
			fileext: ".xlsx" // file extension
		});
	}

    function loadDataTable() {
        $('#tablatotal').DataTable({
            "paging": false,
            "pageLength": Infinity,
            "lengthChange": true,
            "searching": false,
            "ordering": false,
            "info": true,
            "autoWidth": true,
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
</script>