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
                <div class="row">
                    <div class="col-12 col-md-4 col-sm-12">
                        <label>Seleccione el agente:</label>
                        <select class="form-control js-example-basic-single" id="nitAgente" name="nitAgente">
                            <option value="">Seleccione el agente</option>
                            <?php foreach ($agentes->result() as $key) {
                                echo '<option value="' . $key->nit . '">' . $key->nombres . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 col-sm-6 pt-2" style="align-self: flex-end;">
                        <button class="btn btn-success" onclick="filtrarAuditoria();">Buscar</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive p-2" id="CargarFormAud">
                    <table class="table" id="tablaAuditoria">
                        <thead>
                            <th class="text-center" width="10%" scope="col">ID</th>
                            <th class="text-center" width="10%" scope="col">NIT</th>
                            <th class="text-center" width="30%" scope="col">NOMBRE</th>
                            <th class="text-center" width="10%" scope="col">PUNTUACIÓN / 100</th>
                            <th class="text-center" width="15%" scope="col">FECHA CREACIÓN</th>
                            <th class="text-center" width="15%" scope="col">FECHA FINALIZACIÓN</th>
                            <th class="text-center" width="10%" scope="col">OPCIÓN</th>
                            <th class="text-center" width="10%" scope="col">EMAIL</th>
                            
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
                    <div class="table-responsive p-2" id="InfoVerAuditoria">

                    </div>
                </div>
                <div class="modal-footer">


                </div>
            </div>
        </div>
    </div>

    <!-- / .Modal para visualizar la auditoría-->
    <!-- Modal para Editar la auditoría-->
    <div class="modal fade" id="editarAuditoriaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="TeditarAuditoriaModal"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive p-2" id="InfoEditarAuditoria">

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-8 col-md-8 col-sm-8 pt-2" >
                        <textarea class="form-control" rows="5" id="obsAuditor" name="obsAuditor" placeholder="Espacio para ser llenado por el auditor(Observaciones)"></textarea>
                    </div>
                    <div class="col-4 col-md-4 col-sm-4 pt-2">
                        <button style="float: right;" id="btnAuditoriaF" class="btn btn-primary" onclick="finalizarAuditoria();">Finalizar auditoría</button>
                    </div>

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
                setTimeout(function() {
                    document.getElementById('cargando').style.display = 'none';
                }, 2000);

            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/CargarAuditoriaAgentes", true);
        xmlhttp.send();
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
        document.getElementById('InfoVerAuditoria').innerHTML = "";
        document.getElementById('InfoEditarAuditoria').innerHTML = "";


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
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/verAuditoriaAgente", true);
        xmlhttp.send(info);


    }

    function EditarAuditoria(id) {

        document.getElementById('InfoVerAuditoria').innerHTML = "";
        document.getElementById('InfoEditarAuditoria').innerHTML = "";

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
</script>