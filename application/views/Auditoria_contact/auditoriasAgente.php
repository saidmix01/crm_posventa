<?php $this->load->view('Auditoria_contact/header'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="alert alert-light col-lg-12 text-center" role="alert">
            <h4>Informe de auditoría</h4>
        </div>
    </section>
    <!-- Main content -->
    <div class="loader" id="cargando"></div>
    <section class="content">

        <div class="card">

            <div class="card-header">

            </div>
            <div class="card-body">
                <div class="table-responsive" id="CargarFormAud">
                    <table class="table" id="tablaAuditoria">
                        <thead>
                            <th class="text-center" width="10%" scope="col">ID</th>
                            <th class="text-center" width="10%" scope="col">NIT</th>
                            <th class="text-center" width="30%" scope="col">NOMBRE</th>
                            <th class="text-center" width="10%" scope="col">PUNTUACIÓN / 100</th>
                            <th class="text-center" width="15%" scope="col">FECHA CREACIÓN</th>
                            <th class="text-center" width="15%" scope="col">FECHA FINALIZACIÓN</th>
                            <th class="text-center" width="10%" scope="col">OPCIÓN</th>
                        </thead>
                        <tbody id="cargarAuditoriaAgentes">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
    <!-- Modal para visualizar la auditoría-->
    <div class="modal fade" id="verAuditoriaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="TverAuditoriaModal"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" id="InfoVerAuditoria">

                    </div>
                </div>
                <div class="modal-footer">


                </div>
            </div>
        </div>
    </div>

    <!-- / .Modal para visualizar la auditoría-->
    

</div>

<?php $this->load->view('Auditoria_contact/footer'); ?>
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2({
            placeholder: 'Seleccione un agente',
            width: '100%'
        });
        cargarAuditoriaAgentes();
    });

    function cargarAuditoriaAgentes() {
        var nitAgente = <?= $usu ?>;
        var info = new FormData();
        info.append('nitAgente', nitAgente);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        /* xmlhttp.responseType = 'json'; */
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                document.getElementById('cargarAuditoriaAgentes').innerHTML = xmlhttp.responseText;
                cargarDataTable();
                document.getElementById('cargando').style.display = 'none';


            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/CargarAuditoriaAgentesXusu", true);
        xmlhttp.send(info);
    }

    function cargarDataTable() {
        $('#tablaAuditoria').DataTable({
            "paging": true,
            "pageLength": 25,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
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

    function VerAuditoria(id) {
        document.getElementById('cargando').style.display = 'block';
        document.getElementById('TverAuditoriaModal').innerText = 'Ver Id Auditoría: ' + id;

        var info = new FormData();
        info.append('id_auditoria', id);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        /* xmlhttp.responseType = 'json'; */
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                document.getElementById('InfoVerAuditoria').innerHTML = xmlhttp.responseText;
                setTimeout(function() {
                    document.getElementById('cargando').style.display = 'none';
                    $('#verAuditoriaModal').modal('show');
                }, 2000);

            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/verAuditoriaAgenteXusu", true);
        xmlhttp.send(info);


    }

    function EditarAuditoria(id) {
        document.getElementById('cargando').style.display = 'block';
        document.getElementById('TeditarAuditoriaModal').innerText = 'Editar Id Auditoría: ' + id;
        var info = new FormData();
        info.append('id_auditoria', id);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        /* xmlhttp.responseType = 'json'; */
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                document.getElementById('InfoEditarAuditoria').innerHTML = xmlhttp.responseText;
                setTimeout(function() {
                    document.getElementById('cargando').style.display = 'none';
                    $('#editarAuditoriaModal').modal('show');
                }, 2000);

            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/editarAuditoriaAgente", true);
        xmlhttp.send(info);

    }

    function filtrarAuditoria() {
        var nitAgente = document.getElementById('nitAgente').value;

        if (nitAgente != "") {
            var info = new FormData();
            info.append('nitAgente', nitAgente);
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            /* xmlhttp.responseType = 'json'; */
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    $('#tablaAuditoria').dataTable().fnDestroy();
                    document.getElementById('cargarAuditoriaAgentes').innerHTML = xmlhttp.responseText;
                    cargarDataTable();
                    setTimeout(function() {
                        document.getElementById('cargando').style.display = 'none';
                    }, 2000);

                }
            }
            xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/CargarAuditoriaAgentes", true);
            xmlhttp.send(info);
        } else {
            Swal.fire({
                title: 'Advertencia',
                text: 'Debe seleccionar un agente para buscar!',
                icon: 'warning',
                confirmButtonText: 'Ok',
                allowOutsideClick: false,
                showCloseButton: false
            });
        }
    }

    function aggCompromiso(id) {
        var compromiso = document.getElementById('compromiso').value;
        if (compromiso != "") {
            document.getElementById('cargando').style.display = 'block';
            var info = new FormData();
            info.append('compromisos', compromiso);
            info.append('id_auditoria', id);
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            /* xmlhttp.responseType = 'json'; */
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    if (xmlhttp.responseText == "OK") {
                        Swal.fire({
                            title: 'Exito',
                            text: 'Se ha agregado el compromiso con exito.',
                            icon: 'success',
                            confirmButtonText: 'Ok',
                            allowOutsideClick: false,
                            showCloseButton: false
                        }).then((result) => {
								// Read more about isConfirmed, isDenied below 
								if (result.isConfirmed) {
									location.reload();
								}
							});
                    } else if (xmlhttp.responseText == "ERROR") {
                        Swal.fire({
                            title: 'Advertencia',
                            text: 'Ha ocurrido un error al agregar el compromiso, intente nuevamente.',
                            icon: 'warning',
                            confirmButtonText: 'Ok',
                            allowOutsideClick: false,
                            showCloseButton: false
                        });
                    }
                    document.getElementById('cargando').style.display = 'none';

                }
            }
            xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/compromisoAgente", true);
            xmlhttp.send(info);
        } else {
            Swal.fire({
                title: 'Advertencia',
                text: 'Debe agregar una descripción en la caja de texto compromiso!',
                icon: 'warning',
                confirmButtonText: 'Ok',
                allowOutsideClick: false,
                showCloseButton: false
            });
        }
    }
</script>