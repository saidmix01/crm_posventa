<?php $this->load->view('administracion/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<br>
	<!-- Main content -->
	<div class="loader2" id="cargando-3"></div>
	<section class="content">
		<div class="card">
			<div class="card-header" align="center">
				<h3><strong>GESTIÓN DE COMPRAS</strong></h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-3">
						<a href="#" class="btn btn-success btn-lg" data-toggle="modal" data-target="#modal_solicitud" onclick="mostrar_btn();"><i class="fas fa-file"></i> Nueva Solicitud</a>
					</div>
					<div class="col-md-9">
						<div class="row">
							<div class="col-md-4">
								<div class="icheck-success d-inline">
									<input type="radio" id="radioPrimary1" name="r1" checked="">
									<label for="radioPrimary1">Urgencia 1</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="icheck-warning d-inline">
									<input type="radio" id="radioPrimary2" name="r2" checked="">
									<label for="radioPrimary1">Urgencia 2</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="icheck-danger d-inline">
									<input type="radio" id="radioPrimary3" name="r3" checked="">
									<label for="radioPrimary1">Urgencia 3</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 container table-responsive">
						<table class="table table-hover" id="tab_soli">
							<?php if ($perfil == 1 || $perfil == 28 || $perfil == 20) {

								echo '<button type="button" id="nuevoEquipo" class="btn btn-success float-right " data-toggle="modal" data-target="#descargarExcelEstados">
								Descargar Excel &nbsp; <span><i class="fas fa-file-download"></i></span>
							</button>';
							} ?>

							<thead>
								<tr align="center">
									<th scope="col">#</th>
									<th scope="col">Descripción</th>
									<th scope="col">Mensajes</th>
									<th scope="col">Con Factura</th>
									<th scope="col">Estado</th>
									<th scope="col">Estado Autorización</th>
									<th scope="col">Usuario que Solicita</th>
									<th scope="col">Gerente que Autoriza</th>
									<th scope="col">Fecha de solicitud</th>
									<th scope="col">Fecha de autorizacion</th>
									<th scope="col">Dias en Gestion</th>
								</tr>
							</thead>
							<tbody id="tabla_solicitudes" align="center">

							</tbody>
						</table>
					</div>
				</div>
				<div class="row d-none" id="tabla_descargas">
					<div class="col-md-12 container table-responsive">
						<table class="table table-hover" id="tab_soli_descargas">
							<thead>
								<tr align="center">
									<th scope="col">#</th>
									<th scope="col">Descripción</th>
									<th scope="col">Mensajes</th>
									<th scope="col">Con Factura</th>
									<th scope="col">Estado</th>
									<th scope="col">Estado Autorización</th>
									<th scope="col">Usuario que Solicita</th>
									<th scope="col">Gerente que Autoriza</th>
									<th scope="col">Fecha de solicitud</th>
									<th scope="col">Fecha de autorizacion</th>
									<th scope="col">Dias en Gestion</th>
								</tr>
							</thead>
							<tbody id="tabla_solicitudes_descargar" align="center">

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>



<!-- Modal nueva solicitud-->
<div class="modal fade" id="modal_solicitud" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Nueva Solicitud</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="loader" id="cargando"></div>
			<div class="modal-body">
				<form id="form_solicitud">
					<div class="form-row">
						<div class="col">
							<label>Area que solicita la compra</label>
							<select class="form-control js-example-basic-single" id="combo_area" name="combo_area" style="width: 100%;">
								<option value="">Seleccione una opción...</option>
								<option value="administracion">Administración</option>
								<option value="contaccenter">Contac Center</option>
								<option value="vhnuevos">Vehículos Nuevos</option>
								<option value="vhusados">Vehículos Usados</option>
								<option value="alistamiento">Alistamiento</option>
								<option value="mecanica_gasolina">Mecánica Gasolina</option>
								<option value="mecanica_diesel">Mecánica Diesel</option>
								<option value="lyp">Lámina y Pintura</option>
								<option value="accesorios">Accesorios</option>
								<option value="repuestos">Repuestos</option>
								<option value="sistemas">Sistemas</option>
								<option value="Negocios">Negocios</option>
							</select>
						</div>
						<div class="col">
							<label>Seleccione la Sede</label>
							<select class="form-control js-example-basic-single" id="combo_sede" name="combo_sede" style="width: 100%;">
								<option value="">Seleccione una sede...</option>
								<option value="giron">Girón</option>
								<option value="rosita">Rosita</option>
								<option value="chevropartes">Chevropartes</option>
								<option value="solochevrolet">Solochevrolet</option>
								<option value="barranca">Barrancabermeja</option>
								<option value="malecon">Malecon</option>
								<option value="Bocono">Boconó</option>
							</select>
						</div>
					</div>
					<div class="form-row">
						<div class="col">
							<label>Nombre de la persona que realiza la solicitud</label>
							<input type="text" name="nom" id="nom" class="form-control" value="<?= $nomb ?>" disabled>
						</div>
						<div class="col">
							<label>Cargo de la persona que esta solicitando la compra</label>
							<input type="text" name="cargo" id="cargo" class="form-control">
						</div>
					</div>
					<div class="form-row">
						<div class="col">
							<label>Nombre del Gerente de Area que autoriza la compra</label>
							<select class="form-control js-example-basic-single" id="combo_gerente" name="combo_gerente" style="width: 100%;">
								<option value="">Seleccione un gerente...</option>
								<?php foreach ($all_emp->result() as $key) { ?>
									<option value="<?= $key->nit ?>"><?= $key->nombres ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col">
							<label>Proveedores o Contratistas sugeridos</label>
							<input type="text" name="proveedor" id="proveedor" class="form-control">
						</div>
					</div>
					<div class="form-row">
						<div class="col">
							<label>Nivel de urgencia de la compra, siendo 3 mas urgente</label>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="urgencia" id="inlineRadio1" value="1">
								<label class="form-check-label" for="inlineRadio1">1</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="urgencia" id="inlineRadio2" value="2">
								<label class="form-check-label" for="inlineRadio2">2</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="urgencia" id="inlineRadio3" value="3">
								<label class="form-check-label" for="inlineRadio2">3</label>
							</div>
						</div>
						<div class="col">
							<input type="hidden" name="fecha_tentativa" id="fecha_tentativa" class="form-control" value="2022-01-01">
						</div>
					</div>
					<div class="form-row">
						<div class="col">
							<label>Area y % a la que se debe cargar la compra</label>
							<textarea class="form-control" id="area_cargar" name="area_cargar" rows="3"></textarea>
						</div>
					</div>
					<div class="form-row">
						<div class="col">
							<label>Descripción de producto o servicio</label>
							<textarea class="form-control" id="descripcion_prod" name="descripcion_prod" rows="3"></textarea>
						</div>
					</div>
					<div class="form-row" style="display:none;">
						<div class="col">
							<label>Especificaciones Técnicas de la compra</label>
							<textarea class="form-control" id="espesificacion_prod" rows="3"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" id="btn_gen_soli" onclick="generar_solicitud();">Enviar Solicitud</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal ver solicitud-->
<div class="modal fade" id="modal_ver_solicitud" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Ver Solicitud</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="loader" id="cargando"></div>
			<div class="modal-body">
				<form id="form_solicitud_ver">
					<div class="form-row">
						<div class="col">
							<label>Area que solicita la compra</label>
							<select class="form-control js-example-basic-single" id="combo_area" style="width: 100%;">
								<option value="">Seleccione una opción...</option>
								<option value="administracion">Administración</option>
								<option value="contaccenter">Contac Center</option>
								<option value="vhnuevos">Vehículos Nuevos</option>
								<option value="vhusados">Vehículos Usados</option>
								<option value="alistamiento">Alistamiento</option>
								<option value="mecanica_gasolina">Mecánica Gasolina</option>
								<option value="mecanica_diesel">Mecánica Diesel</option>
								<option value="lyp">Lámina y Pintura</option>
								<option value="accesorios">Accesorios</option>
								<option value="repuestos">Repuestos</option>
								<option value="sistemas">Sistemas</option>
								<option value="Negocios">Negocios</option>
							</select>
						</div>
						<div class="col">
							<label>Seleccione la Sede</label>
							<select class="form-control js-example-basic-single" id="combo_sede" style="width: 100%;">
								<option value="">Seleccione una sede...</option>
								<option value="giron">Girón</option>
								<option value="rosita">Rosita</option>
								<option value="chevropartes">Chevropartes</option>
								<option value="solochevrolet">Solochevrolet</option>
								<option value="barranca">Barrancabermeja</option>
								<option value="malecon">Malecon</option>
								<option value="Bocono">Boconó</option>
							</select>
						</div>
					</div>
					<div class="form-row">
						<div class="col">
							<label>Nombre de la persona que realiza la solicitud</label>
							<input type="text" name="nombre" id="nom" class="form-control" value="<?= $nomb ?>" disabled>
						</div>
						<div class="col">
							<label>Cargo de la persona que esta solicitando la compra</label>
							<input type="text" name="cargo" id="cargo" class="form-control">
						</div>
					</div>
					<div class="form-row">
						<div class="col">
							<label>Nombre del Gerente de Area que autoriza la compra</label>
							<select class="form-control js-example-basic-single" id="combo_gerente" style="width: 100%;">
								<option value="">Seleccione un gerente...</option>
								<?php foreach ($all_emp->result() as $key) { ?>
									<option value="<?= $key->nit ?>"><?= $key->nombres ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col">
							<label>Proveedores o Contratistas sugeridos</label>
							<input type="text" name="proveedor" id="proveedor" class="form-control">
						</div>
					</div>
					<div class="form-row">
						<div class="col">
							<label>Nivel de urgencia de la compra, siendo 3 mas urgente</label>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="urgencia" id="inlineRadio1" value="1">
								<label class="form-check-label" for="inlineRadio1">1</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="urgencia" id="inlineRadio2" value="2">
								<label class="form-check-label" for="inlineRadio2">2</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="urgencia" id="inlineRadio3" value="3">
								<label class="form-check-label" for="inlineRadio2">3</label>
							</div>
						</div>
						<div class="col">
							<input type="hidden" name="fecha_tentativa" id="fecha_tentativa" class="form-control" value="2022-01-01">
						</div>
					</div>
					<div class="form-row">
						<div class="col">
							<label>Area y % a la que se debe cargar la compra</label>
							<textarea class="form-control" id="area_cargar" rows="3"></textarea>
						</div>
					</div>
					<div class="form-row">
						<div class="col">
							<label>Descripción de producto o servicio</label>
							<textarea class="form-control" id="descripcion_prod" rows="3"></textarea>
						</div>
					</div>
					<div class="form-row" style="display:none;">
						<div class="col">
							<label>Especificaciones Técnicas de la compra</label>
							<textarea class="form-control" id="espesificacion_prod" rows="3"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<!--  <button type="button" class="btn btn-primary" id="btn_gen_soli" onclick="generar_solicitud();">Enviar Solicitud</button> -->
			</div>
		</div>
	</div>
</div>


<!-- Modal Enviar autolizacion-->
<div class="modal fade" id="modal_autorizacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="titulo_modal"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="loader" id="cargando-1"></div>
			<div class="modal-body">
				<form id="data_autolizacion" enctype="multipart/form-data">
					<div class="form-row">
						<div class="col">
							<label>Seleccione una cotización 1</label>
							<input type="file" name="file1[]" id="file1[]" class="form-control-file" multiple="" required>
							<input type="hidden" name="id_soli1" id="id_soli1" class="form-control">
						</div>
					</div>
					<div class="form-row">
						<div class="col">
							<label>Comentarios adicionales</label>
							<textarea class="form-control" id="comentarios" name="comentarios" minlength="15" required></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="enviar_solicitud();">Solicitar Autorizacion</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal estado-->
<div class="modal fade" id="modal_estado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="titulo_modal">Cambiar Estado</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="data_autolizacion">
					<div class="form-row">
						<div class="col">
							<label>Seleccione un estado</label>
							<select class="form-control js-example-basic-single" id="combo_estado">
								<option value="">Selecciona un estado</option>
								<option value="2">En Proceso</option>
								<option value="3">En transito</option>
								<option value="4">Despachada</option>
								<option value="5">Negada</option>

							</select>
							<input type="hidden" name="id_soli_est" id="id_soli_est" class="form-control">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="cambiar_estado_compra();">Cambiar Estado</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal estado-->
<div class="modal fade" id="modal_auto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="titulo_modal">Autorización Aprobada</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="ver_soli_apro">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="cambiar_estado_compra();">Cambiar Estado</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal estado-->
<div class="modal fade" id="modal_msn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="titulo_modal">Mensajes</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form_msn">
					<div class="form-row">
						<div class="col">
							<label><?php foreach ($userdata->result() as $key) {
										echo $key->nombres . " DICE...";
									} ?></label>
							<textarea class="form-control" rows="2" id="msn_soli" name="msn_soli"></textarea>
							<input type="hidden" name="id_soli_msn" id="id_soli_msn">
						</div>
					</div>
					<br>
					<div class="form-row">
						<div class="col">
							<a href="#" class="btn btn-success btn-sm" id="btn_msn" onclick="enviar_msn();">Enviar</a>
						</div>
					</div>
				</form>
				<hr>
				<div class="table-responsive" id="tabla_msn">

				</div>
			</div>
		</div>
	</div>
</div>
<!-- * Estados de la compra
	* 1-> sin revisar
	* 2-> en proceso
	* 5-> en transito 3
	* 3-> despachada 4
	* 4-> negada 5 -->

<!-- Modal descargar excel con filtro de estados -->
<div class="modal fade" id="descargarExcelEstados" tabindex="-1" aria-labelledby="descargarExcelEstados" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="titulo_modal">Opción descargar por Estado</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-row">
					<div class="col">
						<label>Seleccione el estado:</label>
						<select class="form-control" name="estadoDescarga" id="estadoDescarga">
							<option value="0">Todos</option>
							<option value="1">Sin revisar</option>
							<option value="2">En proceso</option>
							<option value="3">En transito</option>
							<option value="4">Despachada</option>
							<option value="5">Negada</option>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="bajar_excel_tabla_uno()">Descargar</button>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('compras/footer') ?>
<script type="text/javascript">
	function generar_solicitud() {
		var area = document.getElementById('combo_area').value;
		var sede = document.getElementById('combo_sede').value;
		var cargo = document.getElementById('cargo').value;
		var gerente = document.getElementById('combo_gerente').value;
		var proveedor = document.getElementById('proveedor').value;
		var urgencia = document.querySelector('input[name="urgencia"]:checked').value
		var fecha_ten = document.getElementById('fecha_tentativa').value;
		var descrp = document.getElementById('descripcion_prod').value;
		var espesifi = "null";
		var area_cargar = document.getElementById('area_cargar').value;
		if (area == "" || sede == "" || cargo == "" || gerente == "" || proveedor == "" || urgencia == "" || fecha_ten == "" || descrp == "" || espesifi == "" || area_cargar == "") {
			Toast.fire({
				type: 'warning',
				title: ' No puedes dejar campos vacios...'
			});
		} else {
			document.getElementById("cargando").style.display = "block";
			$('#tab_soli').dataTable().fnDestroy();
			//definir ruta
			var url = '<?= base_url() ?>compras/insert_solicitud';
			//definir el id del formulario para recoger los datos
			var formulario = document.getElementById("form_solicitud");
			//crear objeto de la clase XMLHttpRequest
			var request = new XMLHttpRequest();
			//valioras respuesta de la peticion HTTP
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					//campoformulario.innerHTML = request.responseText;
					location.reload();
				}
			}
			//definiendo metodo y ruta
			request.open("POST", url);
			//envar los dattos creando un objeto de clse fordata del formulario
			request.send(new FormData(formulario));
		}
	}

	function mostrar_btn() {
		document.getElementById('btn_gen_soli').style.display = "block";
	}
	/* Sergio Galvis
	23/05/2022 */
	var f = new Date();
	fecha = f.getDate() + "-" + (f.getMonth() + 1) + "-" + f.getFullYear();

	function bajar_excel_tabla_uno() {

		var opt = document.getElementById('estadoDescarga').value;

		if (opt != "") {
			document.getElementById("cargando-3").style.display = "block";
			var datos = new FormData();
			datos.append('opt', opt);
			$('#tab_soli_descargas').dataTable().fnDestroy();
			//definir ruta
			var url = '<?= base_url() ?>compras/get_solicitudes_descarga';
			//definir el id del formulario para recoger los datos
			var formulario = document.getElementById("form_solicitud");
			//crear objeto de la clase XMLHttpRequest
			var request = new XMLHttpRequest();
			//valioras respuesta de la peticion HTTP
			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					document.getElementById('tabla_solicitudes_descargar').innerHTML = request.responseText;
					document.getElementById('tabla_descargas').style.display = 'block';

					$("#tab_soli_descargas").table2excel({
						exclude: ".noExl",
						name: "Worksheet Name",
						filename: "Gestion-compras" + "-" + fecha, //do not include extension
						fileext: ".xls" // file extension
					});
					document.getElementById('tabla_descargas').style.display = 'none';
					document.getElementById("cargando-3").style.display = "none";
					$('#descargarExcelEstados').modal('hide')
				}
			}
			//definiendo metodo y ruta
			request.open("POST", url);
			//envar los dattos creando un objeto de clse fordata del formulario
			request.send(datos);
		} else {
			Toast.fire({
				type: 'warning',
				title: 'Debe seleccionar una opción de estado'
			});
		}


	}
</script>