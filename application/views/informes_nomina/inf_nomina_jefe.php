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
                <a href="#" class="btn btn-primary mb-2" style="margin-left: 10px;" onclick="generar_nomina_jefe();">Generar Nómina</a>
            </form>
        </div>
        <div class="col-md-2">
            <?php if ($perfil == 21 || $perfil == 20 || $perfil == 1) {
                echo '<button type="submit" class="btn btn-secondary mb-2" title="Ingresar Valores" onclick="open_modal_valores_jefes();">Ingresar Valores</button>';
            } ?>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success mb-2" id="btn_excel" title="Descargar Excel" onclick="ResultsToTable6('Nomina Jefes CODIESEL');">Exportar a Excel</button>
        </div>
    </div>
    
    <hr>
    <div class="table-responsive" id="inf_jefe" style="display: none;">
        <!--  Tabla usuarios  -->
        <table id="example6" class="table table-sm table-bordered table-hover" align="center">
            <thead>
                <tr align="center" style="background-color: gray;color: white;">
                  <th>Cedula</th>
                  <th>Nombres</th>
                  <th>Sede</th>
                  <th>Facturación POSVENTA</th>
                  <th>Internas</th>
                  <th>Comisión por Facturación</th>
                  <th>Utilidad Neta</th>
                  <th>Utilidad Bruta Repuestos</th>
                  <th>Comisión Utilidad Bruta</th>
                  <th>Bono NPS</th>
                  <th>Total</th>
                  <th>Acción</th>
              </tr>
          </thead>
          <tbody id="tabla_nomina_jefe" align="center">

          </tbody>
      </table>

  </div>
</div>
</div>
</section>
<!-- /.content -->
</div>

<!-- Modal Ingresar Valores Jefes-->
<div class="modal fade" id="modal_val_jefes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ingreso de valores</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
            <div class="form-row">
                <div class="col">
                    <label>Seleccione el Jefe</label>
                    <select class="form-control combo2" id="combo_jefes" required>
                        <option value="">Seleccione una Opción...</option>
                        <?php foreach ($jefes->result() as $key) { ?>
                            <option value="<?=$key->nit?>"><?=$key->nombres?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col">
                    <label>Seleccione Año y Mes</label>
                    <input type="month" name="fecha" id="fecha" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <label>Bono NPS</label>
                    <input type="number" name="bono_nps" id="bono_nps" class="form-control" required>
                </div>
                <div class="col">
                    <label>Bono Utilidad Neta</label>
                    <input type="number" name="bono_utilidad" id="bono_utilidad" class="form-control" required>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="insert_val_jefes();">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal detalle nomina-->
<div class="modal fade" id="det_nomina_jefes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="max-width: 50%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="loader" id="cargando"></div>
        <div class="table-responsive">
            <table class="table table-hover" id="example5">
                <thead>
                    <tr>
                        <th>NIT</th>
                        <th>Nombres</th>
                        <th>Sede</th>
                        <th>Repuestos</th>
                        <th>Mano de Obra</th>
                    </tr>
                </thead>
                <tbody id="tabla_detalle_jefe">
                    
                </tbody>
            </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success" id="btn_excel2" title="Descargar Excel" onclick="ResultsToTable5('Detalle Nomina Tecnicos CODIESEL');">Exportar a Excel</button>
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
</script>