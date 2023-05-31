<?php $this->load->view('Informes_nomina/header'); ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
 <br>
 <!-- Main content -->
 <section class="content">

   <div class="card">
      <div class="card-body">
        <strong><h3>Selecciona un rango de fechas</h3></strong>

        <div class="row">

            <div class="col-md-8">
                <form class="form-inline" aling="center">
                 <div class="form-group mb-2" style="margin-left: 10px;">
                    <label for="staticEmail2" class="sr-only">Seleccione un mes</label>
                    <input type="month" class="form-control" id="mes" required="true">
                </div>
                <a href="#" class="btn btn-primary mb-2" style="margin-left: 10px;" onclick="generar_nomina_dir_flota();">Generar Nómina</a>
            </form>
        </div>
        <div class="col-md-2">
            <?php if ($perfil == 21 || $perfil == 20 || $perfil == 1) {
                // echo '<button type="submit" class="btn btn-secondary mb-2" title="Ingresar Valores" onclick="open_modal_valores_jefes();">Ingresar Valores</button>';
            } ?>
        </div>
        <div class="col-md-2">
            <!-- <button type="submit" class="btn btn-success mb-2" id="btn_excel" title="Descargar Excel" onclick="ResultsToTable6('Nomina Jefes CODIESEL');">Exportar a Excel</button> -->
        </div>
    </div>
    
    <hr>
    <div class="table-responsive" id="inf_dir_flota" style="display: none;">
        <!--  Tabla usuarios  -->
        <table id="example6" class="table table-sm table-bordered table-hover" align="center">
            <thead>
                <tr align="center" style="background-color: gray;color: white;">
                  <th>Concepto</th>
                  <th>Valor</th>
                  <th>Detalle</th>
              </tr>
          </thead>
          <tbody id="tabla_nomina_dir_flota" align="center">

          </tbody>
      </table>

  </div>
</div>
</div>
</section>
<!-- /.content -->
</div>

<!-- Modal detalle nomina-->
<div class="modal fade" id="det_nomina_dir_flota" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="max-width: 50%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detalle de la Comisión</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="loader" id="cargando"></div>
        <div id="capaResultado" class="table-responsive">
            
        </div>
      </div>
    </div>
  </div>
</div>


<?php $this->load->view('Informes_nomina/footer'); ?>

<script type="text/javascript">
    //Ingresar Valores
    function open_modal_valores_jefes() {
        $('#modal_val_jefes').modal('toggle');
    }
    function insert_val_jefes() {
        var fecha = document.getElementById('fecha').value;
        var jefe = document.getElementById('combo_jefes').value;
        var bono_nps = document.getElementById('bono_nps').value;
        var bono_uti = document.getElementById('bono_utilidad').value;
        if (fecha == "" || jefe == "" || bono_nps == "" || bono_uti == "") {
            Toast.fire({
                type: 'warning',
                title: ' Todos los campos son requeridos'
            });
        }else{
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
                            title: ' Valores Ingresados Correctamente'
                        });
                    }else if(resp == "err"){
                        Toast.fire({
                            type: 'error',
                            title: ' Error al Insertar los valores'
                        });
                    }
                }
            }
            xmlhttp.open("GET", "<?= base_url() ?>nomina/insert_val_jefes?fecha=" + fecha + "&jefe=" + jefe + "&bono_nps=" + bono_nps + "&bono_uti=" + bono_uti, true);
            xmlhttp.send();
        }
    }

    function abrir_modal_detalle_jefe(nit,mes,anio) {
        mostrar_detalle_nomina_jefes(nit,mes,anio);
        document.getElementById('exampleModalLabel').innerHTML='Detalle de Nomina Empleado '+nit;
        document.getElementById("cargando").style.display = "block";
    }
    function mostrar_detalle_nomina_jefes(nit,mes,anio) {
        var result = document.getElementById("tabla_detalle_jefe");
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    result.innerHTML = xmlhttp.responseText;
                    $('#det_nomina_jefes').modal('show');
                    document.getElementById("cargando").style.display = "none";
                }
            }
            xmlhttp.open("GET", "<?= base_url() ?>nomina/load_detalle_nomina_jefes?mes="+mes+"&anio="+anio+"&nit="+nit, true);
            xmlhttp.send();
    }

    function ver_detalle_dir_flota(concepto, ano, mes) {
		document.getElementById("cargando").style.display = "block";
		document.body.style.cursor = 'wait';

        var result = document.getElementById("capaResultado");
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.open("POST", "<?= base_url() ?>nomina/detalle_nomina_dir_flota", true);
        var params = "concepto=" + concepto + "&ano=" + ano + "&mes=" + mes;
        xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {	
                
                result.innerHTML = xmlhttp.responseText;
                document.getElementById("cargando").style.display = "none";
                document.body.style.cursor = 'default';
                
            }
        }
        xmlhttp.send(params);
	}
</script>