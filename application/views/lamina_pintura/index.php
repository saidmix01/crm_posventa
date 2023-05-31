<?php $this->load->view('lamina_pintura/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<br>
	<style>
		.tx {
			transition: 0.4s ease;
			-moz-transition: 0.4s ease;
			-webkit-transition: 0.4s ease;
			-o-transition: 0.4s ease;
		}

		.tx:hover {
			transform: scale(1.3);
			-moz-transform: scale(1.3);
			-webkit-transform: scale(1.3);
			-o-transform: scale(1.3);
			-ms-transform: scale(1.3);
		}
	</style>
	<!-- Main content -->
	<section class="content">
		<div class="card">
			<div class="card-body">
				<div><label class="col-lg-12 text-center lead">Gestion Productos de Lamina y Pintura</label></div>
				<hr>
				<button type="button" onclick="PintarFormualrioAgregar() " id="nuevoEquipo" class="btn btn-primary" data-toggle='modal' data-target='#modalEquipos'>
					Nuevo Producto &nbsp; <span><i class='fas fa-plus-square'></i></span>
				</button>
				<hr>
				<!-- inicio de la tbla para visalizar campos de la base de datos-->
				<div class="table-responsive">
					<table class="table  table-hover table-bordered nowrap  " id="TablaLamina" style="width:100%;">
						<thead class="bg-dark">
							<tr>
								<th class='text-center'>Producto</th>
								<th class='text-center'>Marca</th>
								<th class='text-center'>Referencia</th>
								<th class='text-center'>Fecha de Ingreso</th>
								<th class='text-center'>Cantidad</th>
								<th class='text-center'>Medida</th>
								<th>Proveedor</th>
								<th>Costo</th>
								<th>Precio</th>
								<th class="text-center">Editar</th>
								<th class="text-center">Retirar</th>
							</tr>
						</thead>
						<tbody>

							<?php foreach ($datos->result() as $key) {

								echo "
                        <tr>
							<td class='text-center' >$key->nombre_producto</td>
                            <td class='text-center' >$key->marca_producto</td>
							<td class='text-center' >$key->descripcion</td>
							<td class='text-center' >$key->fecha_ingreso_producto</td>
							<td class='text-center' >$key->cantidad_producto</td>     
							<td class='text-center' >$key->medida</td>   
							<td >$key->nombre_proveedor</td>     
							<td >$key->costo</td>    
							<td >$key->precio</td>                    
							<td class='text-center'><button onclick='PintarFormualrioEditar(this.id)' class='tx btn btn-warning shadow' data-toggle='modal' data-target='#modaledtequipo' id='" . $key->id_producto . "'><i class='text-white far fa-edit'></i></button></td>  
							<td class='text-center'><button onclick='EliminarProductoLaminaPintura(this.id)' name='" . $key->id_producto . "'   class='tx btn btn-danger shadow' id='" . $key->id_producto . "' ><i class='far fa-times-circle'></i></button></td>              
                        </tr>
                        ";
							} ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
	<!-- modal para agrgar un servio a la tabla equipo -->
	<div class="modal fade bd-example-modal-lg" id="modalEquipos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title col-lg-12 text-center" id="exampleModalLabel">Ingreso de Nuevo Producto</h5>
				</div>
				<div class="modal-body">
					<div id="verFormulario"></div>
					<div id="validaRespuesta"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
						<button type="button" onclick="RegistrarProductoLaminaPintura()" class="btn btn-success">Registrar</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--------------------------------------------------------- Modal para editar informacion de equipo--------------------------------- -->
	<div class="modal fade bd-example-modal-lg" id="modaledtequipo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title alert text-center col-lg-12" id="exampleModalLabel">Editar infomacion de Producto</h5>
				</div>
				<div class="modal-body">
					<div id="FormEditar"></div>
					<div id="validarespuestaEditar"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
					<button onclick="EditarProductoLaminaPintura()" type="button" class="btn btn-success">Editar</button>
				</div>
			</div>
		</div>
	</div>




	<!-- /.content -->
</div>
<?php $this->load->view('lamina_pintura/footer') ?>