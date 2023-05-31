<!-- Modal historial-->
<div class="modal fade" id="modal_historial" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Historial de la orden-># </h5><h5 class="modal-title"><strong id="titulo_modal_his"></strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div class="table-responsive">
      		<table class="table table-hover">
					  <thead>
					    <tr style="font-size: 12px;">
					      <th scope="col">Orden N</th>
					      <th scope="col">Asesor</th>
					      <th scope="col">Estado</th>
					      <th scope="col">Notas</th>
					      <th scope="col">Fecha</th>
					    </tr>
					  </thead>
					  <tbody id="id_tabla_hist">

					  </tbody>
					</table>
      	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
	<!-- Modal add evento-->
<div class="modal fade" id="moda_add_evento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar evento a la orden-># </h5><h5 class="modal-title"><strong id="titulo_modal_ev"></strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
        	<input type="hidden" name="ot" id="ot">
        	<div class="form-group">
				    <label for="estado">Estado</label>
				    <select class="form-control" id="estado">
				      <option value="">Seleccione una opcion</option>
				      <?php foreach ($estados_ot_tall->result() as $key) { ?>
				      	<option value="<?=$key->estado?>"><?=$key->estado?></option>
				      <?php } ?>
				    </select>
				  </div>
				  <div class="form-group">
				  	<label for="fecha_promesa_entrega">Fecha Promesa entrega</label>
				  	<input type="date" class="form-control" name="fec_prom_entrega" id="fec_prom_entrega">
				  </div>
				  <div class="form-group">
				    <label for="notas">Notas</label>
				    <textarea class="form-control" rows="3" id="notas"></textarea>
				  </div>
				  <button type="submit" class="btn btn-primary" onclick="add_evento()">Guardar</button>
				</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Cotizaciones Sacyr by ID -->
<div class="modal fade" id="CotizacionesByOrden" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cotizaciones Sacyr - Número de Orden: <span id="ordenTSacyr"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row" >
			<div class="col-auto" id="bodyCoti">

			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>