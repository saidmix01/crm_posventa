<?php $this->load->view('Auditoria_contact/header'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="alert alert-light col-lg-12 text-center" role="alert">
            <h4>Informe detallado de auditoría</h4>
        </div>
    </section>
    <!-- Main content -->
    <div class="loader" id="cargando"></div>
    <section class="content">

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md-4 col-sm-6">
                        <label>Seleccione el agente:</label>
                        <select class="form-control js-example-basic-single" id="nitAgente" name="nitAgente">
                            <option value="">Seleccione el agente</option>
                            <?php foreach ($agentes->result() as $key) {
                                echo '<option value="' . $key->nit . '">' . $key->nombres . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="col-6 col-md-4 col-sm-3">
                        <label>Seleccione un mes</label>
                        <input class="form-control" type="month" name="AuditoriaMes" id="AuditoriaMes">
                    </div>
                    <div class="col-6 col-md-4 col-sm-3 pt-2" style="align-self: flex-end; ">
                        <button class="btn btn-success" onclick="filtroAuditoriaMes();">Buscar</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive p-2" >
                    <table class="table">
                        <thead>
                            <th>Fecha</th>
                            <th>Puntos</th>
                            <th class="text-center">Opción</th>
                        </thead>
                        <tbody id="tablaInfo">
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

</div>
<?php $this->load->view('Auditoria_contact/footer'); ?>
<script>
    $("#buscar_items").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#menu_items li").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $(document).ready(function() {
        $('.js-example-basic-single').select2({
            placeholder: 'Seleccione un agente',
            width: '100%'
        });
    });

    function filtroAuditoriaMes() {
        var fecha = document.getElementById('AuditoriaMes').value;
        var nitAgente = document.getElementById('nitAgente').value;

        if (nitAgente != "" && fecha != "") {
            var info = new FormData();
            info.append('AuditoriaMes', fecha);
            info.append('nitAgente', nitAgente);
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            //xmlhttp.responseType = 'json'; 
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    document.getElementById('tablaInfo').innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/cargarInfDetalle", true);
            xmlhttp.send(info);
        } else {
            Swal.fire({
                title: 'Advertencia',
                text: 'Para realizar el filtro detallado de la auditoría, debe seleccionar un agente y una fecha (Año y Mes)',
                icon: 'error',
                confirmButtonText: 'Ok',
                allowOutsideClick: false,
                showCloseButton: false
            });
        }
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
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/verAuditoriaAgente", true);
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
</script>