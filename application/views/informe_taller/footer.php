<!-- /.content-wrapper -->
<footer class="main-footer">
    <strong>Copyright &copy; 2020 <a href="http://adminlte.io">CODIESEL</a>.</strong>
    Todos los derechos reservados.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0.0-pre
    </div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>


<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">'¿Has terminado ya?'</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Estas seguro que deseas cerrar sesion</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">No</button>
                <a class="btn btn-primary" href="<?= base_url() ?>login/logout">Si</a>
            </div>
        </div>
    </div>
</div>

<!-- PASS Modal-->
<div class="modal" tabindex="-1" id="pass-modal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Es necesario que cambies tu contraseña</h5>
            </div>
            <div class="modal-body">
                <!-- <form method="POST" action="<?= base_url() ?>usuarios_admin/changepass"> -->
                <form>
                    <div class="row">
                        <div class="col">
                            <label for="pass1">Ingrese la nueva contraseña</label>
                            <input type="password" id="pass1_one" name="pass2_one" class="form-control" placeholder="Ingrese nueva contraseña">
                        </div>
                        <div class="col">
                            <label for="pass2">Confirme la contraseña</label>
                            <input type="password" id="pass2_one" name="pass1_one" class="form-control" placeholder="Confirma la contraseña">
                            <?php
                            foreach ($userdata->result() as $key) {
                            ?>
                                <input type="hidden" id="id_usu_one" name="id_usu" value="<?= $key->id_usuario ?>">
                            <?php
                            }
                            ?>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <a href="<?= base_url() ?>login/logout" class="btn btn-secondary">Cerrar</a>
                <button type="button" class="btn btn-primary" onclick="cambiarPass_One();">Cambiar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- PASS Modal-->
<div class="modal" tabindex="-1" id="pass-modal2" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambio de Contraseña</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <form method="POST" action="<?= base_url() ?>usuarios_admin/changepass"> -->
                <form>
                    <div class="row">
                        <div class="col">
                            <label for="pass1">Ingrese la nueva contraseña</label>
                            <input type="password" id="pass1_two" name="pass2" class="form-control" placeholder="Ingrese nueva contraseña">
                        </div>
                        <div class="col">
                            <label for="pass2">Confirme la contraseña</label>
                            <input type="password" id="pass2_two" name="pass1" class="form-control" placeholder="Confirma la contraseña">
                            <?php
                            foreach ($userdata->result() as $key) {
                            ?>
                                <input type="hidden" id="id_usu_two" name="id_usu" value="<?= $key->id_usuario ?>">
                            <?php
                            }
                            ?>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="cambiarPass_Two();">Cambiar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalIngresoVehSinCita" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ingreso Vehículo sin Cita</h5>
                <button id="btnCloseModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="placa">Placa del Vehiculo:</label>
                            <input type="text" class="form-control" id="placaVeh">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nombreCliente">Nombre del Cliente::</label>
                            <input type="text" class="form-control" id="nombreCliente">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="motivoVisita">Motivo de la Visita:</label>
                            <textarea class="form-control" id="motivoVisita" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="guardarVehSinCita();">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MENSAJE FLOTANTE-->
<?php
$log = $this->input->get('log');
if ($log == "err_p") {
?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert_err" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
        Error... Las contraseñas no coinciden
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php
}
?>
<div id="notifi">

</div>

<!-- jQuery -->
<script src="<?= base_url() ?>plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= base_url() ?>plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="<?= base_url() ?>plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="<?= base_url() ?>plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?= base_url() ?>plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?= base_url() ?>plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?= base_url() ?>plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?= base_url() ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url() ?>plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= base_url() ?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?= base_url() ?>plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url() ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= base_url() ?>dist/js/pages/dashboard.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- SweetAlert2 -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Toastr -->
<script src="<?= base_url() ?>plugins/toastr/toastr.min.js"></script>

<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<!-- AdminLTE for demo purposes -->

<script>
    /*  $(document).ready(function() {
        $("#placa").keyup(function() {
            $("#placa").val(this.value.toUpperCase());
            if (this.value == '') {
                $("#sugerencias").html('');
                $("#sugerencias").css('display', 'none');
            } else {
                var xmlhttp;
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                        var resp = xmlhttp.responseText;
                        $("#sugerencias").css('display', 'block');
                        $("#sugerencias").html(resp);
                    }
                }
                xmlhttp.open("GET", "<?= base_url() ?>taller/buscarByPlaca?placa=" + this.value, true);
                xmlhttp.send();
            }
        });
    }); */
    $(document).ready(function() {
        $("#placaVeh").keyup(function() {
            $("#placaVeh").val(this.value.toUpperCase());

            var filtroNum = '1234567890'; //Caracteres validos
            var filtroLetras = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; //Caracteres validos
            var string = '';
            for (var i = 0; i < this.value.length; i++) {
                if (i <= 2 && i <= 5) {
                    if (filtroLetras.indexOf(this.value.charAt(i)) != -1) {
                        string += this.value.charAt(i);
                    } else {
                        this.value = string;
                    }
                } else if (i > 2 && i <= 5) {
                    if (filtroNum.indexOf(this.value.charAt(i)) != -1) {
                        string += this.value.charAt(i);
                    } else {
                        this.value = string;
                    }
                } else {
                    this.value = string;
                }
            }
        });
    });
</script>
<script>
    function guardarVehSinCita() {
        var placa = $("#placaVeh").val();
        var cliente = $("#nombreCliente").val();
        var motivo = $("#motivoVisita").val();
        var bod = $("#bod").val();
        if (placa == '' || cliente == '' || motivo == '') {
            Swal.fire({
                title: 'Error!',
                text: '¡Por favor llene todos los campos!',
                icon: 'error',
                confirmButtonText: 'Ok'
            });
        } else {
            if (placa.length == 6) {
                var xmlhttp;
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                        var resp = xmlhttp.responseText;
                        if (resp == 1) {
                            Swal.fire({
                                title: 'Exito!',
                                text: 'Vehiculo añadido exitosamente',
                                icon: 'success',
                                confirmButtonText: 'Ok'

                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $("#btnCloseModal").click();
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: '¡No se insertó el vehículo!',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        }
                        location.reload();
                    }
                }
                xmlhttp.open("GET", "<?= base_url() ?>taller/insertVehSinCita?placa=" + placa + "&cliente=" + cliente
                    .toUpperCase() + "&motivo=" + motivo + "&bod=" + bod, true);
                xmlhttp.send();
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: '¡El número de digitos de la placa no coincide!',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }

        }

    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#buscar_items").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#menu_items li").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        //$('#estado').select2();
        $('#estado').select2({
            theme: "classic",
            width: '100%'
        });
        $('#combo_bod').select2({
            theme: "classic",
            placeholder: "Selecciona una bodega"
        });
        $('#proceso').select2({
            theme: "classic",
            width: '100%'
        });
    });
</script>
<!-- Funcion que cambia el lenguaje al datatable -->
<script>
    $(document).ready(function() {
        $('#tabla_data').DataTable({
            "paging": true,
            "pageLength": 10,
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
    });
</script>
<script>
    $(document).ready(function() {
        $('#tabla_ot_abiertas').DataTable({
            "paging": true,
            "pageLength": 10,
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
    });
</script>
<script>
    $(document).ready(function() {
        $('#tabla_ot_tec').DataTable({
            "paging": true,
            "pageLength": 10,
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
    });
</script>
<script>
    $(document).ready(function() {
        $('#tabla_mant_prep').DataTable({
            "paging": false,
            "pageLength": 10,
            "lengthChange": true,
            "searching": true,
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
    });
</script>
<script src="<?= base_url() ?>dist/js/demo.js"></script>
<?php
$this->load->model('usuarios');
$usu = $this->usuarios->getUserById($id_usu);
$p = "";
$nit = "";
foreach ($usu->result() as $key) {
    $p = $key->pass;
    $nit = $key->nit;
}
//echo $p." ".$nit;
$this->load->library('encrypt');
$pass_desencript = $this->encrypt->decode($p);
if ($pass_desencript == $nit) {
?>
    <script type="text/javascript">
        $('#pass-modal').show('true')
    </script>
<?php
}
?>
<script type="text/javascript">
    setTimeout(function() {
        $('#alert_err').alert('close');
    }, 1500);
</script>

<script type="text/javascript">
    function open_form_addev(ot) {
        $("#ot").val(ot);
        document.getElementById('titulo_modal_ev').innerText = ot;
        $('#moda_add_evento').modal('show');
    }
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
    });

    function add_evento() {
        var estado = document.getElementById("estado").value;
        var notas = document.getElementById("notas").value;
        var ot = document.getElementById("ot").value;
        if (estado == "" || notas == "" || ot == "") {
            Toast.fire({
                type: 'error',
                title: 'Todos los Campos deben ser completados!'
            });
        } else {
            var fec_prom_entrega = document.getElementById("fec_prom_entrega").value;
            //alert(fec_prom_entrega);
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    var resp = xmlhttp.responseText;
                    if (resp == "ok") {
                        Toast.fire({
                            type: 'success',
                            title: ' Evento agregado correctamente...'
                        });
                        location.reload();
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: ' Ha ocurriod un error inesperado.'
                        });
                    }
                }
            }
            xmlhttp.open("GET", "<?= base_url() ?>taller/add_evento?ot=" + ot + "&notas=" + notas + "&estado=" + estado +
                "&fec_prom_entrega=" + fec_prom_entrega, true);
            xmlhttp.send();
        }

    }
</script>
<script type="text/javascript">
    function open_form_hist(ot) {
        $("#ot").val(ot);
        document.getElementById('titulo_modal_his').innerText = ot;
        $('#modal_historial').modal('show');
        var result = document.getElementById("id_tabla_hist");
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                result.innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "<?= base_url() ?>taller/cargar_hist_ot?ot=" + ot, true);
        xmlhttp.send();
    }

    function cargar_tabla() {
        var bod = document.getElementById("combo_bod").value;
        var result = document.getElementById("data_tabla_fil");
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                result.innerHTML = xmlhttp.responseText;
                total_ot_abiertas();
            }
        }
        xmlhttp.open("GET", "<?= base_url() ?>taller/cargar_tabla_filtro?bod=" + bod, true);
        xmlhttp.send();
    }

    function reload() {
        location.reload();
    }
</script>
<script type="text/javascript">
    function total_ot_abiertas() {
        var bod = document.getElementById("combo_bod").value;
        var result = document.getElementById("ot_abiertas");
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                result.innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "<?= base_url() ?>taller/get_total_ot_abiertas?bod=" + bod, true);
        xmlhttp.send();
    }
</script>
<script type="text/javascript">
    function marcar_entrada(id_cita, fecha_ini) {
        var f = new Date();
        fecha = f.getFullYear() + "-" + (f.getMonth() + 1) + "-" + f.getDate();

        var f1 = new Date(fecha_ini);
        var fecha_v = f1.getFullYear() + "-" + (f1.getMonth() + 1) + "-" + f1.getDate();


        console.log(fecha_v + " === " + fecha);

        if (fecha_v === fecha) {
            document.getElementById("cargando").style.display = "block";

            //alert(id_cita);
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    var resp = xmlhttp.responseText;
                    if (resp == "ok") {
                        Toast.fire({
                            type: 'success',
                            title: ' Registro agregado correctamente...'
                        });
                        location.reload();
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: ' Error al crear el registro'
                        });
                    }
                    document.getElementById("cargando").style.display = "none";
                }
            }
            xmlhttp.open("GET", "<?= base_url() ?>taller/marcar_entrada_vh?id_cita=" + id_cita, true);
            xmlhttp.send();
        } else {
            Toast.fire({
                type: 'error',
                title: 'No puede marcar la entrada si la fecha no corresponde, a la fecha programada!'
            });
        }


    }
</script>

<script>
    /* $(document).ready(function() {
        $("#placa").keyup(function() {
            $("#placa").val(this.value.toUpperCase());
            if (this.value == '') {
                document.location.reload();
            } else if ($("#placa").val().length == 6) {
                $("#res").html('');
                var xmlhttp;
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                        var resp = xmlhttp.responseText;
                        $("#res").html(resp);
                    }
                }
                xmlhttp.open("GET", "<?= base_url() ?>taller/buscarByPlaca?placa=" + this.value, true);
                xmlhttp.send();
            }
        });
    }); */
    /*  $(document).ready(function() {
         $("#fecha_b").blur(function() {
             if (this.value == '') {
                 document.location.reload();
             } else {
                 $("#res").html('');
                 var xmlhttp;
                 if (window.XMLHttpRequest) {
                     xmlhttp = new XMLHttpRequest();
                 } else {
                     xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                 }
                 xmlhttp.onreadystatechange = function() {
                     if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                         var resp = xmlhttp.responseText;
                         $("#res").html(resp);
                     }
                 }
                 xmlhttp.open("GET", "<?= base_url() ?>taller/buscarByFecha?fecha=" + this.value, true);
                 xmlhttp.send();
             }
         });
     }); */

    function buscar_placa() {
        var placa = $("#placa").val();
        if (placa == '') {
            /* document.location.reload(); */
        } else if ($("#placa").val().length >= 6) {
            $("#res").html('');
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    var resp = xmlhttp.responseText;
                    /* $("#res").html(resp); */
                    $("#cambiosBuscar").html(resp);

                }
            }
            xmlhttp.open("GET", "<?= base_url() ?>taller/buscarByPlaca?placa=" + placa, true);
            xmlhttp.send();
        } else {
            Toast.fire({
                type: 'error',
                title: 'Verifique que sea una placa valida'
            });
        }
    }



    //funcion para convetir tabla en documento de excel

    var f = new Date();
    fecha = f.getDate() + "-" + (f.getMonth() + 1) + "-" + f.getFullYear();

    function toexcel() {
        $("#tabla_data").table2excel({
            exclude: ".noExl",
            name: "Worksheet Name",
            filename: "Informe-" + "-" + fecha, //do not include extension
            fileext: ".xlsx" // file extension
        });
    }


    function buscar_fecha() {
        var fecha = $("#fecha_b").val();
        if (fecha == '') {

        } else {
            $("#res").html('');
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    var resp = xmlhttp.responseText;
                    $("#res").html(resp);
                }
            }
            xmlhttp.open("GET", "<?= base_url() ?>taller/buscarByFecha?fecha=" + fecha, true);
            xmlhttp.send();
        }
    }
</script>
<script src="<?= base_url() ?>dist/js/md5.js"></script>
<script>
    function cambiarPass_One() {
        console.log('Cambiando contraseña');
        let pass1 = document.getElementById('pass1_one').value;
        let pass2 = document.getElementById('pass2_one').value;
        let id_usuario = document.getElementById('id_usu_one').value;
        let clave = hex_md5(pass1);
        console.log(pass1 + "=" + pass2);
        if (pass1 === pass2 && pass1 != "" && pass2 != "") {
            let form = new FormData();
            /* 
            	$pass1 = $this->input->POST('pass1');
            	$pass2 = $this->input->POST('pass2');
            	$id_usu = $this->input->POST('id_usu');
            	$clave = $this->input->POST('clave'); 
            */
            form.append('pass1', pass1);
            form.append('pass2', pass2);
            form.append('id_usu', id_usuario);
            form.append('clave', clave);
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    var resp = xmlhttp.responseText;
                    if (resp == 1) {
                        Swal.fire({
                            title: 'Exito!',
                            text: 'Se ha cambiado con exito la contraseña',
                            icon: 'success',
                            confirmButtonText: 'Ok',
                            allowOutsideClick: false
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    } else if (resp == 2) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'No se ha actualizado la contraseña.',
                            icon: 'error',
                            confirmButtonText: 'Ok',
                            allowOutsideClick: false
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    }

                }
            }
            xmlhttp.open("POST", "<?= base_url() ?>usuarios_admin/changepass", true);
            xmlhttp.send(form);
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Las contraseñas no coinciden',
                icon: 'error',
                confirmButtonText: 'Cerrar'
            });
        }
    }

    function cambiarPass_Two() {
        console.log('Cambiando contraseña');
        let pass1 = document.getElementById('pass1_two').value;
        let pass2 = document.getElementById('pass2_two').value;
        let id_usuario = document.getElementById('id_usu_two').value;
        let clave = hex_md5(pass1);
        console.log(pass1 + "=" + pass2);
        if (pass1 === pass2 && pass1 != "" && pass2 != "") {
            let form = new FormData();
            form.append('pass1', pass1);
            form.append('pass2', pass2);
            form.append('id_usu', id_usuario);
            form.append('clave', clave);
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    var resp = xmlhttp.responseText;
                    if (resp == 1) {
                        Swal.fire({
                            title: 'Exito!',
                            text: 'Se ha actualizado la contraseña.',
                            icon: 'success',
                            confirmButtonText: 'Ok',
                            allowOutsideClick: false
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else if (resp == 2) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'No se ha actualizado la contraseña.',
                            icon: 'error',
                            confirmButtonText: 'Ok',
                            allowOutsideClick: false
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    }

                }
            }
            xmlhttp.open("POST", "<?= base_url() ?>usuarios_admin/changepass", true);
            xmlhttp.send(form);
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Las contraseñas no coinciden',
                icon: 'error',
                confirmButtonText: 'Cerrar'
            });
        }
    }

    function loadCotizaciones(ordenT, arrayId) {
        document.getElementById('cargando').style.display = 'block';

        const arrayIds = arrayId.split('-');
        document.getElementById('ordenTSacyr').innerText = ordenT;
        const bodyCoti = document.getElementById('bodyCoti');

        bodyCoti.innerHTML = "";

        for (let index = 0; index < arrayIds.length; index++) {

            let element = `<button class="btn btn-success btn-sm m-2" onclick="EditCotizacion(${arrayIds[index]})">${arrayIds[index]}</button>`;
            bodyCoti.insertAdjacentHTML('afterbegin', element);

        }
        $('#CotizacionesByOrden').modal('show');

        document.getElementById('cargando').style.display = 'none';
    }

    function EditCotizacion(id) {
        /* Creamos un elemento tipo form->Formulario con metodo post y accion */
        var mapForm = document.createElement("form");
        mapForm.target = "EditarCotizacion";
        mapForm.method = "POST";
        mapForm.action = "<?= base_url() ?>Sacyr/EditarCotizacion";
        /* Creamos los input dentro del formulario creado anteriormente */
        var varId = document.createElement("input");
        varId.type = "hidden";
        varId.name = "id_cotizacion";
        varId.value = id;
        mapForm.appendChild(varId);

        /* Agregamos el formulario creado al body */
        document.body.appendChild(mapForm);
        /* Script para abrir una nueva ventana */
        /* map = window.open('http://webdesign.about.com/','open_window', 'menubar, toolbar, location, directories, status, scrollbars, resizable,dependent, width = 640, height = 480, left = 0, top = 0 ') */
        map = window.open("", "EditarCotizacion", "menubar, toolbar, location, directories, status, scrollbars, resizable,dependent,,title=editar cotizacion,height=auto,width=auto,scrollbars=1");

        if (map) {
            mapForm.submit();
        }
    }

    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    });
</script>
</body>

</html>