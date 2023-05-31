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

            <div class="col-md-10">
                <form class="form-inline" aling="center">
                 <div class="form-group mb-2" style="margin-left: 10px;">
                    <label for="staticEmail2" class="sr-only">Seleccione un mes</label>
                    <input type="month" class="form-control" id="mes" required="true">
                </div>
                <a href="#" class="btn btn-primary mb-2" style="margin-left: 10px;" onclick="generar_nomina_tecnicos();">Generar Nómina</a>
            </form>
        </div>
        <div class="col-md-2">
            
            <button type="submit" class="btn btn-success mb-2" id="btn_excel" title="Descargar Excel" onclick="ResultsToTable4('Nomina Tecnicos CODIESEL');">Exportar a Excel</button>
        </div>
    </div>
    
    <hr>
    <div class="table-responsive" id="inf_tec" style="display: none;">
        <!--  Tabla usuarios  -->
        <table id="example4" class="table table-sm table-bordered table-hover" align="center">
            <thead>
                <tr align="center" style="background-color: gray;color: white;">
                  <th>Cedula</th>
                  <th>Tecnico</th>
                  <th>Patio</th>
                  <th>Cargo</th>
                  <th>Venta Repuestos</th>
                  <th>Venta Mano de Obra</th>
                  <th>Comisión Repuestos</th>
                  <th>Comisión Mano de Obra</th>
                  <th>Segunda Entrega</th>
                  <th>Bono NPS</th>
                  <th>Instalacion Accesorios</th>
                  <th>Internas</th>
                  <th>Total</th>
                  <th>Ver detalle</th>
              </tr>
          </thead>
          <tbody id="tabla_nomina_tec" align="center">

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
            <table class="table table-hover" id="example5">
                <thead>
                    <tr>
                        <th>Factura</th>
                        <th>N Orden</th>
                        <th>Placa</th>
                        <th>Vehiculo</th>
                        <th>Operacion</th>
                        <th>Nombre operación</th>
                        <th>Venta Rptos</th>
                        <th>Ventan MO</th>
                        <th>Segunda Entrega</th>
                        <th>Instalacion Accesorios</th>
                        <th>Internas</th>
                    </tr>
                </thead>
                <tbody id="tabla_detalle">
                    
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
    function abrir_modal_detalle_tec(nit,mes,anio) {
        mostrar_detalle_nomina_tec(nit,mes,anio);
        document.getElementById('exampleModalLabel').innerHTML='Detalle de Nomina Empleado '+nit;
        document.getElementById("cargando").style.display = "block";
    }
    function mostrar_detalle_nomina_tec(nit,mes,anio) {
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
            xmlhttp.open("GET", "<?= base_url() ?>nomina/load_detalle_nomina_tec?mes="+mes+"&anio="+anio+"&nit="+nit, true);
            xmlhttp.send();
    }
</script>