<?php $this->load->view('Auditoria_contact/header'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="alert alert-light col-lg-12 text-center" role="alert">
            <h4>Auditoría</h4>
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
                    <div class="col-6 col-md-4 col-sm-3 pt-2" style="align-self: flex-end; ">
                        <button class="btn btn-success" onclick="crearAuditoria();">Auditar</button>
                    </div>
                    <div class="col-6 col-md-4 col-sm-3 pt-2" style="align-self: flex-end; text-align: end;">
                        <a href="<?= base_url() ?>auditoria_contact/configuracion" target="_blank" style="align-self: flex-end; text-align: end;" class="btn btn-warning"><i class="fas fa-cogs">&#160;Configuración</i></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive" id="CargarFormAud">

                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-8 col-md-8 col-sm-8 pt-2">
                        <textarea class="form-control" rows="5" id="obsAuditor" name="obsAuditor" placeholder="Espacio para ser llenado por el auditor(Observaciones)"></textarea>
                    </div>
                    <div class="col-4 col-md-4 col-sm-4 pt-2" style="align-self: center;">
                        <button disabled style="float: right;" id="btnAuditoriaF" class="btn btn-primary" onclick="finalizarAuditoria();">Finalizar auditoría</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

</div>
<script>
    function crearAuditoria() {

        var nitAgente = document.getElementById('nitAgente').value;
        if (nitAgente != "") {
            document.getElementById('cargando').style.display = 'block';
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
                    if (xmlhttp.responseText > 0) {
                        CargarFormAud(xmlhttp.responseText);
                    } else if (xmlhttp.responseText == 0) {
                        document.getElementById('cargando').style.display = 'none';
                    }

                }
            }
            xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/crearAuditoria", true);
            xmlhttp.send(info);
        } else {
            Swal.fire({
                title: 'Advertencia',
                text: 'Para iniciar una auditoria, debe seleccionar un asessor.',
                icon: 'warning',
                confirmButtonText: 'Ok',
                allowOutsideClick: false,
                showCloseButton: false
            });
        }


    }

    function CargarFormAud(id) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        /* xmlhttp.responseType = 'json'; */
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                document.getElementById('CargarFormAud').innerHTML = xmlhttp.responseText;
                setTimeout(function() {
                    document.getElementById('id_auditoria').value = id;
                    document.getElementById('btnAuditoriaF').disabled = false;
                    document.getElementById('cargando').style.display = 'none';
                }, 2000);

            }
        }
        xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/CargarFormAud", true);
        xmlhttp.send();

    }
    /* Sumar puntuación de la auditoría */
    /* function sumarPuntuacion() {
        // Inicializamos sumaP para guardar la sumatoria de los input['radio'] de opción SI 
        var sumaP = 0;
        $(".si").map(function() {
            // Verificamos que el input este checkeado para sumarlo! 
            if (this.checked) {
                sumaP += parseFloat(this.value);
            }
        });
        console.log(sumaP);
        document.getElementById('sumaP').innerText = Math.round(sumaP);

    } */
</script>
<?php $this->load->view('Auditoria_contact/footer'); ?>
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2({
            placeholder: 'Seleccione un agente',
            width: '100%'
        });
    });
</script>