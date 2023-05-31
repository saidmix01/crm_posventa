<!--
    Autor:Sergio Galvis
    Fecha: 08/11/2022
-->
<?php $this->load->view('Sacyr/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <div class="loader" id="cargando"></div>
    <section class="content">
        <div class="card">
            <div class="card-header" align="center">
                <h3><strong>Informe Sacyr</strong></h3>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-12 container table-responsive">
                        <table id="reportSacyr" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">CODIGO</th>
                                    <th scope="col">DESCRIPCION</th>
                                    <th scope="col">CANT APRO</th>
                                    <th scope="col">CANT INV</th>
                                    <th scope="col">CANT FACT</th>
                                    <th scope="col">CANT OT</th>
                                    <th scope="col">DISP APRO</th>
                                    <!-- <th scope="col">DISP INV</th> -->

                                </tr>
                            </thead>
                            <tbody id="bodyTableInformeSacyr">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- Modal Informe Detallado -->
<!-- Modal -->
<div class="modal fade" id="reportDetailSacyr" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="reportDetailSacyrTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col-md-12 container table-responsive">
                        <table id="reportDetailSacyr" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">CODIGO</th>
                                    <th scope="col">DESCRIPCION</th>
                                    <th scope="col">CANT FACT</th>
                                    <th scope="col">OT FACT</th>
                                    <th scope="col">CANT OT</th>
                                    <th scope="col">NUMERO OT</th>
                                </tr>
                            </thead>
                            <tbody id="bodyTableInformeDetailSacyr">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('Sacyr/footer'); ?>

<script>
    $(document).ready(function() {
        $("#buscar_items").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#menu_items li").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        loadReportSacyr();


        $('[data-toggle="tooltip"]').tooltip({
            delay: {
                "show": 500,
                "hide": 100
            },
            html: true,
            template: '<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
        });

    });

    function loadReportSacyr() {
        document.getElementById('cargando').style.display = 'block';
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                document.getElementById('bodyTableInformeSacyr').innerHTML = xmlhttp.responseText;
                loadDataTable("reportSacyr");
                document.getElementById('cargando').style.display = 'none';
            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>Sacyr/loadReportSacyr", true);
        xmlhttp.send();
    }

    function loadReportDetailSacyr(id) {

        document.getElementById('cargando').style.display = 'block';
        let form = new FormData();
        form.append('codigo', id);
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                document.getElementById('bodyTableInformeDetailSacyr').innerHTML = xmlhttp.responseText;
                $('#reportDetailSacyr').modal('show');
                document.getElementById("reportDetailSacyrTitle").innerText = "Código buscado: "+id;
                document.getElementById('cargando').style.display = 'none';
            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>Sacyr/loadReportSacyrDetail", true);
        xmlhttp.send(form);

    }

    function loadDataTable(name) {
        $('#'+name).DataTable({
            "paging": true,
			"pageLength": 10,
			"lengthChange": true,
			"searching": true,
			"ordering": true,
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