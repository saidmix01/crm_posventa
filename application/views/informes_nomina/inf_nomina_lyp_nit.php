<?php 
$this->load->view("Informes_nomina/header");
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
 <br>
 <!-- Main content -->
 <section class="content">

   <div class="card">
      <div class="card-body">
        <strong><h3>Selecciona un rango de fechas</h3></strong>

        <div class="row">

            <div class="col-md-10">
                <form class="form-inline" aling="center">
                 <div class="form-group mb-2" style="margin-left: 10px;">
                    <label for="staticEmail2" class="sr-only">Fecha inicial</label>
                    <input type="date" class="form-control" id="desde" required="true">
                </div>
                <div class="form-group mb-2" style="margin-left: 10px;">
                    <label for="staticEmail2" class="sr-only">Fecha final</label>
                    <input type="date" class="form-control" id="hasta" required="true">
                </div>
                <a href="#" class="btn btn-primary mb-2" style="margin-left: 10px;" onclick="generar_nomina_nit();">Generar Nómina</a>
            </form>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success mb-2" id="btn_excel" title="Descargar Excel" onclick="exportTableToExcel('example1','Nomina Lamina y Pintura');">Exportar a Excel</button>
        </div>
    </div>


    
    <hr>
    <div class="table-responsive">
        <!--  Tabla usuarios  -->
        <table id="example1" class="table table-sm table-bordered table-hover" align="center">
            <thead>
                <tr align="center" style="background-color: gray;color: white;">
                  <th>Cedula</th>
                  <th>Operario</th>
                  <th>Cargo</th>
                  <th>Productividad</th>
                  <th>Horas Trabajadas</th>
                  <th>Horas Productivas Mes</th>
                  <th>Porcentaje Liquidación</th>
                  <th>Materiales</th>
                  <th>Base Comisión</th>
                  <th>Internas</th>
                  <th>Comisión sin Internas</th>
                  <th>Comisión a Pagar</th>
                  <th>Accion</th>
              </tr>
          </thead>
          <tbody id="tabla_nomina" align="center">

          </tbody>
      </table>

  </div>
</div>
</div>
</section>
<!-- /.content -->
</div>

<!-- Modal detalle nomina-->
<div class="modal fade" id="det_nomina" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="max-width: 80%;">
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
            <table class="table table-hover" id="example2">
                <thead>
                    <tr>
                        <th>Factura</th>
                        <th>N Orden</th>
                        <th>Placa</th>
                        <th>Vehiculo</th>
                        <th>Productividad</th>
                        <th>% Liquidacion</th>
                        <th>Tiempo Facturado</th>
                        <th>Base Comision</th>
                        <th>Materiales</th>
                        <th>Internas</th>
                        <th>Comision a Pagar</th>
                    </tr>
                </thead>
                <tbody id="tabla_detalle">
                    
                </tbody>
            </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success" id="btn_excel2" title="Descargar Excel" onclick="ResultsToTable2('Detalle Nomina Lamina y Pintura');">Exportar a Excel</button>
      </div>
    </div>
  </div>
</div>

<?php 
$this->load->view("Informes_nomina/footer");
?>
<script type="text/javascript">
    function abrir_modal_detalle(nit,fecha_ini,fecha_fin) {
        mostrar_detalle_nomina_lyp(nit,fecha_ini,fecha_fin);
        document.getElementById('exampleModalLabel').innerHTML='Detalle de Nomina Empleado '+nit;
        
        document.getElementById("cargando").style.display = "block";
    }
    function mostrar_detalle_nomina_lyp(nit,fecha_ini,fecha_fin) {
        var result = document.getElementById("tabla_detalle");
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    result.innerHTML = xmlhttp.responseText;
                    $('#det_nomina').modal('show');
                    document.getElementById("cargando").style.display = "none";
                }
            }
            xmlhttp.open("GET", "<?= base_url() ?>nomina/load_detalle_nomina?desde="+fecha_ini+"&hasta="+fecha_fin+"&nit="+nit, true);
            xmlhttp.send();
    }
</script>