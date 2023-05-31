<?php $this->load->view('Informe_pqr_nps/header'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<br>

	<style>
			.loader {
		position: fixed;
		/* left: 100px;
		top: 0px; */
		width: 100%;
		height: 100%;
		z-index: 9999;
		background: url('<?= base_url() ?>media/cargando7.gif') 50% 50% no-repeat rgb(249, 249, 249);
		opacity: .9;
		display: none;
	}
		.scrol {
			overflow: scroll;
			height: 65vh;
			width: 100%;
		}

		.scrol .cuerpo {
			height: 500px;
		}

		thead tr th {
			position: sticky;
			top: 0;
			z-index: 10;
			color: white;
		}

		.scrol table .fijar {
			position: sticky;
			background-color: white;
		}

		.scrol table .titulo {
			position: sticky;
			z-index: 20;
			color: blac;
		}

		.colun1 {
			width: 100px;
			min-width: 100px;
			max-width: 100px;
			left: 0px;
		}

		.colun2 {
			width: 100px;
			min-width: 100px;
			max-width: 100px;
			left: 100px;
		}

		.colun3 {
			width: 100px;
			min-width: 100px;
			max-width: 100px;
			left: 200px;
		}

		.tamaño {
			width: 110px;
			min-width: 100px;
			max-width: 180px;
		}
	</style>

	<section class="content">
	<div class="loader" id="cargando"></div>
		<div class="card">
			<div class="card-title text-center lead alert">
				<h3>Informe PQR</h3>
			</div>
			<div class="card-body">
				<div class="modal-footer">
					<span><a href="#" class="btn btn-info " onclick="open_new_pqr();"><i class="fas fa-plus-squares"></i>Agregar PQR </a></span>
					<span><a href="#" class="btn btn-success" onclick="bajar_excel();"><i class="fas fa-plus-squares"></i>Descargar Excel </a></span>
					<span><a href="#" class="btn btn-info" onclick="mostrarCerrados();"><i class="fas fa-plus-squares"></i>Mostrar Cerrados </a></span>
					<span><a href="#" class="btn btn-warning" onclick="window.location.reload();"><i class="fas fa-plus-squares"></i>Mostrar Abiertos</a></span>
				</div>
				<div class="table-responsive scrol">
					<table class="table table-hover table-bordered" align="center" id="tabla_data" style="font-size: 14px;">
						<thead align="center" class="thead-light">
							<tr>
								<th class="titulo colun1" scope="col">Accción</th>
								<th class="titulo colun2" scope="col">Fuente</th>
								<th class="titulo colun3" scope="col" class="fitwidth">ID encuesta</th>
								<th scope="col">SEDE</th>
								<th scope="col">Fecha</th>
								<th scope="col">Placa</th>
								<th scope="col">Cliente</th>
								<th scope="col" class="fitwidth">Modelo VH</th>
								<th scope="col"># ORDEN</th>
								<th scope="col">MAIL</th>
								<th scope="col">Telefono</th>
								<th scope="col" class="fitwidth" title="Recomendación del concesionario ">Servicio</th>
								<th scope="col" class="fitwidth" title="Satisfación General con el Concesionario (Mantenimiento o Reparación)">Vehiculo Reparado correctamente</th>
								<th scope="col" class="fitwidth">Satisfacción con el trabajo realizado</th>
								<th scope="col" class="fitwidth" title="Su vehículo fue reparado correctamente en esta visita de servicio?">visita de servicio</th>
								<th scope="col" class="fitwidth">Recomendación de la Marca</th>
								<th scope="col" class="fitwidth">Comentarios de los Clientes</th>
								<th scope="col">Tecnico</th>
								<th scope="col" class="fitwidth">Tipificación Encuesta</th>
								<th scope="col" class="fitwidth">Contacto con el cliente</th>
								<th scope="col" class="fitwidth">Estado del Caso</th>
								<th scope="col" class="fitwidth">Comentarios final caso</th>
								<th scope="col" class="fitwidth">Tipificación cierre</th>
							</tr>
						</thead>

						<tbody align="center">
							<?php foreach ($nps_gm->result() as $key) {
								$deshabilitar = $key->estado_caso == "Cerrado" ? ' disabled' : '';
								$deshabilitarVerb = $key->estado_caso == "Cerrado" || $key->estado_caso == "Abierto" ? '' : ' disabled';

								/*dar color segun el tiempo en contestar verde esta dentro de los tiempo correctos para contestar, amarillo esta por pasar de la fecha, rojo esta lleva varios dias sin g¿haber sido respondida */
								$colorPrioritario = "";
								$date1 = new DateTime(date('Y-m-d'));
								$date2 = new DateTime($key->fecha_evento);
								$dia = $date1->diff($date2);
								$dia = intval($dia->days);
								$colorPrioritario =  ($dia < 3 ? 'background-color:#ABEBC6;' : ($dia >= 3 && $dia <= 6 ? 'background-color:#EDBB99;' : ($dia > 6 ? 'background-color:#EC7063;'  : '')));


								$colorExiware = "";
								$Exiware =  'GM';
								$colorExiware = $Exiware == 'Exiware' ? 'background-color:#FBEEE6;' : 'background-color:#DBE6FF;';

								$cometarioFinal = trim($key->comentarios_final_caso);
								$comentario = trim($key->comentarios);
								$valor = $cometarioFinal == "" ? 'btn btn-secondary text-white' : "btn btn-info text-white";

								/*cambiar a color blanco  los pqr cerrados */
								$resuelt = $key->estado_caso == 'Cerrado' ? 'background-color:#FFFEFE; !important' : '';

								/*validar el valor que traer tipificacion */
								$tipificacion =  $key->tipificacion_encuesta == NULL ? 'Gestionar' : $key->tipificacion_encuesta;

								/*validar el valor del estado del caso */
								$estado_caso =  $key->estado_caso == NULL ? 'Gestionar' : $key->estado_caso;

								/*validar el valor que traer tipificacion de cierre */
								$tipificacion_de_cierre =  $key->tipificacion_cierre  == NULL ? 'Gestionar' : $key->tipificacion_cierre;

								/*validar el nombre de la sede, ques e meustre le nombre no el codigo */
								$sedes = $key->sede;
								$resulsede = $sedes == "00000260492" ? "barranca" : ($sedes == "00000266043" ? "bocono" : ($sedes == "00000260493" ? "rosita" : ($sedes == "00000232420" ? "giron" : $sedes)));


							?>
								<tr style="<?= $colorExiware ?>">
									<td class='fijar colun1' style="<?= $colorPrioritario, $resuelt ?>"><a id="<?= $key->id_encuesta ?>" href="#" class="btn btn-warning btn-sm <?= $deshabilitar ?>" onclick="modal_comentarios('GM',<?= $key->id_encuesta ?>);"><i class="fas fa-edit"></i></a></td>
									<td class="fijar colun2" style="<?= $colorPrioritario, $resuelt ?>">GM</td>
									<td class="fitwidth fijar colun3" style="<?= $colorPrioritario, $resuelt ?>"><?= $key->id_encuesta ?></td>
									<td class="fitwidth"><?= $resulsede ?></td>
									<td class="fitwidth font-weight-bold text-dark" data-toggle="popover" title="Este PQR tiene <?= $dia ?> Dias sin haber sido Gestionado" style="<?= $colorPrioritario, $resuelt ?>"> <?= $key->fecha_evento ?></td>
									<td class="fitwidth"><?= $key->placa ?></td>
									<td class="fitwidth"><?= $key->nom_cliente ?></td>
									<td class="fitwidth"><?= $key->modelo_vh ?></td>
									<td class="fitwidth"><?= $key->uetd_numero ?></td>
									<td class="fitwidth"><?= $key->mail ?></td>
									<td class="fitwidth"><?= $key->celular ?></td>
									<td class="fitwidth"><?= $key->recomendacion_concesionario ?></td>
									<td class="fitwidth"><?= $key->satisfaccion_concesionario ?></td>
									<td class="fitwidth"><?= $key->satisfaccion_trabajo ?></td>
									<td class="fitwidth"><?= $key->vh_reparado_ok ?></td>
									<td class="fitwidth"><?= $key->recomendacion_marca ?></td>
									<td class="fitwidth">
										<i class='d-none'><?= $key->comentarios ?></i><a class="btn btn-outline-info" onclick="comentario('<?= $comentario ?>');"><i class="fas fa-comments text-primary"></i></a>
									</td>
									<td class="fitwidth"><?= $key->nom_tecnico ?></td>
									<td class="fitwidth"><?= $tipificacion ?></td>
									<td class="fitwidth">
										<table style="display: none;" class="">
											<!-- <tbody id="SinComentario-<?= $key->id_encuesta ?>">
												<tr class="noExl">
													<td style="display: none;" class="noExl">Contacto</td>
													<td style="display: none;" class="noExl">Comentarios</td>
													<td style="display: none;" class="noExl">Fecha</td>
												</tr>
											</tbody> -->
											<tbody id="cuerpoTabla-<?= $key->id_encuesta ?>">
											</tbody>
										</table>
										<script>
											setTimeout(function() {
												open_list_verb_Comentarios(<?= $key->id_encuesta ?>);
											}, 2000);
										</script>
										<a href="#" class="btn btn-outline-info mr-3 <?= $deshabilitarVerb ?>" onclick="open_form_verb(<?= $key->id_encuesta ?>);">
											<i class="fas fa-edit"></i>
										</a>
										<a href="#" class="btn btn-outline-info" onclick="open_list_verb(<?= $key->id_encuesta ?>);">
											<i class="fas fa-eye"></i>
										</a>

									</td>
									<td class="fitwidth"><?= $estado_caso ?></td>
									<td class="fitwidth "><i class='d-none'><?= $cometarioFinal ?></i><a class="<?= $valor ?>" onclick="comentariofinal('<?= $cometarioFinal ?>');"><i class="fas fa-comments"></i></a></td>
									<td class="fitwidth"><?= $tipificacion_de_cierre ?></td>

								</tr>
							<?php } ?>
							<?php foreach ($pqr_cod->result() as $key) {
								$deshabilitar = $key->estado_caso == "Cerrado" ? ' disabled' : '';
								$deshabilitarVerb = $key->estado_caso == "Cerrado" || $key->estado_caso == "Abierto" ? '' : ' disabled';

								/*dar color segun el tiempo en contestar verde esta dentro de los tiempo correctos para contestar, amarillo esta por pasar de la fecha, rojo esta lleva varios dias sin g¿haber sido respondida */
								$colorPrioritario = "";
								$date1 = new DateTime(date('Y-m-d'));
								$date2 = new DateTime($key->fecha);
								$dia = $date1->diff($date2);
								$dia = intval($dia->days);
								$colorPrioritario =  ($dia < 3 ? 'background-color:#ABEBC6;' : ($dia >= 3 && $dia <= 6 ? 'background-color:#EDBB99;' : ($dia > 6 ? 'background-color:#EC7063;'  : '')));

								$colorExiware = "";
								$Exiware =  $key->fuente;
								$colorExiware = $Exiware == 'Exiware' ? 'background-color:#FBEEE6;' : 'background-color:#FEFFDB;';

								$cometarioFinal = trim($key->comentarios_final_caso);
								$comentario = trim($key->comentarios);
								$valor = $cometarioFinal == "" ? 'btn btn-secondary text-white' : "btn btn-info text-white";

								/*cambiar a color blanco  los pqr cerrados */
								$resuelt = $key->estado_caso == 'Cerrado' ? 'background-color:#FFFEFE; !important' : '';

								/*validar el valor que traer tipificacion */
								$tipificacion =  $key->tipificacion_encuesta == NULL ? 'Gestionar' : $key->tipificacion_encuesta;

								/*validar el valor del estado del caso */
								$estado_caso =  $key->estado_caso == NULL ? 'Gestionar' : $key->estado_caso;

								/*validar el valor que traer tipificacion de cierre */
								$tipificacion_de_cierre =  $key->tipificacion_cierre  == NULL ? 'Gestionar' : $key->tipificacion_cierre;

								//echo $key->fecha_contacto;



							?>
								<tr style="<?= $colorExiware ?>">
									<td class='fijar colun1' style="<?= $colorPrioritario, $resuelt ?>"><a id="<?= $key->id_pqr ?>" href="#" class="btn btn-warning btn-sm <?= $deshabilitar ?>" onclick="modal_comentarios('<?= $key->fuente ?>',<?= $key->id_pqr ?>);"><i class="fas fa-edit"></i></a></td>
									<td class="fitwidth fijar colun2" style="<?= $colorPrioritario, $resuelt ?>"> <?= $key->fuente ?></td>
									<td class="fitwidth fijar colun3" style="<?= $colorPrioritario, $resuelt ?>"><?= $key->id_pqr ?></td>
									<td class="fitwidth"><?= $key->sede ?></td>
									<td class="fitwidth  font-weight-bold text-dark " data-toggle="popover" title="Este PQR tiene <?= $dia ?> Dias sin haber sido Gestionado" style="<?= $colorPrioritario, $resuelt ?>"><?= $key->fecha ?> </td>
									<td class="fitwidth"><?= $key->placa ?></td>
									<td class="fitwidth"><?= $key->cliente ?></td>
									<td class="fitwidth"><?= $key->modelo_vh ?></td>
									<td class="fitwidth"><?= $key->ot ?></td>
									<td class="fitwidth"><?= $key->mail ?></td>
									<td class="fitwidth"><?= $key->telef ?></td>
									<td class="fitwidth">-</td>
									<td class="fitwidth">-</td>
									<td class="fitwidth">-</td>
									<td class="fitwidth">-</td>
									<td class="fitwidth">-</td>
									<td class="fitwidth">
										<i class='d-none'><?= $key->comentarios ?></i><a class="btn btn-outline-info" onclick="comentario('<?= $comentario ?>');"><i class="fas fa-comments text-primary"></i></a>
									</td>
									<td class="fitwidth"><?= $key->tecnico ?></td>
									<td class="fitwidth"><?= $tipificacion ?></td>
									<td class="fitwidth">
										<table style="display: none;" class="">
											<tbody id="cuerpoTabla-<?= $key->id_pqr ?>">
											</tbody>
										</table>
										<script>
											setTimeout(function() {
												open_list_verb_Comentarios(<?= $key->id_pqr ?>);
											}, 2000);
										</script>
										<a href="#" class="btn btn-outline-info mr-3 <?= $deshabilitarVerb ?>" onclick="open_form_verb(<?= $key->id_pqr ?>);"><i class="fas fa-edit"></i></a><a href="#" class="btn btn-outline-info" onclick="open_list_verb(<?= $key->id_pqr ?>);"><i class="fas fa-eye"></i></a>
									</td>
									<td class="fitwidth"><?= $estado_caso ?></td>
									<td class="fitwidth "><i class='d-none'><?= $cometarioFinal ?></i><a class="<?= $valor ?>" onclick="comentariofinal('<?= $cometarioFinal ?>');"><i class="fas fa-comments"></i></a></td>
									<td class="fitwidth"><?= $tipificacion_de_cierre ?></td>

								</tr>
							<?php } ?>
							<?php foreach ($pqr_int->result() as $key) {
								$deshabilitar = $key->estado_caso == "Cerrado" ? ' disabled' : '';
								$deshabilitarVerb = $key->estado_caso == "Cerrado" || $key->estado_caso == "Abierto" ? '' : ' disabled';

								/*dar color segun el tiempo en contestar verde esta dentro de los tiempo correctos para contestar, amarillo esta por pasar de la fecha, rojo esta lleva varios dias sin g¿haber sido respondida */
								$colorPrioritario = "";
								$date1 = new DateTime(date('Y-m-d'));
								$date2 = new DateTime($key->fecha);
								$dia = $date1->diff($date2);
								$dia = intval($dia->days);
								$colorPrioritario =  ($dia < 3 ? 'background-color:#ABEBC6;' : ($dia >= 3 && $dia <= 6 ? 'background-color:#EDBB99;' : ($dia > 6 ? 'background-color:#EC7063;'  : '')));


								$colorExiware = "";
								$Exiware =  'NPS Interno';
								$colorExiware = $Exiware == 'Exiware' ? 'background-color:#FBEEE6;' : 'background-color:#DBFEFF;';

								$cometarioFinal = trim($key->comentarios_final_caso);
								$valor = $cometarioFinal == "" ? 'btn btn-secondary text-white' : "btn btn-info text-white";

								/*cambiar a color blanco  los pqr cerrados */
								$resuelt = $key->estado_caso == 'Cerrado' ? 'background-color:#FFFEFE; !important' : '';

								/*validar el valor que traer tipificacion */
								$tipificacion =  $key->tipificacion_encuesta == NULL ? 'Gestionar' : $key->tipificacion_encuesta;

								/*validar el valor del estado del caso */
								$estado_caso =  $key->estado_caso == NULL ? 'Gestionar' : $key->estado_caso;

								/*validar el valor que traer tipificacion de cierre */
								$tipificacion_de_cierre =  $key->tipificacion_cierre  == NULL ? 'Gestionar' : $key->tipificacion_cierre;




							?>
								<!-- SELECT t.nit,t.telefono_1,t.mail,t.email2 FROM terceros t WHERE nombres = 'BAYONA PLATA REYNALDO ALBERTO' -->
								<tr style="<?= $colorExiware ?>">
									<td class='fijar colun1' style="<?= $colorPrioritario, $resuelt ?>"><a href="#" class="btn btn-warning btn-sm <?= $deshabilitar ?>" onclick="modal_comentarios('NPS Interno',<?= $key->id ?>);"><i class="fas fa-edit"></i></a></td>
									<td class="fitwidth fijar colun2" style="<?= $colorPrioritario, $resuelt ?>">NPS Interno</td>
									<td class="fitwidth fijar colun3" style="<?= $colorPrioritario, $resuelt ?>"><?= $key->id ?></td>
									<td class="fitwidth"><?= $key->descripcion ?></td>
									<td class="fitwidth font-weight-bold text-dark" data-toggle="popover" title="Este PQR tiene <?= $dia ?> Dias sin haber sido Gestionado" style="<?= $colorPrioritario, $resuelt ?>"> <?= $key->fecha ?></td>
									<td class="fitwidth"><?= $key->placa ?></td>
									<td class="fitwidth"><?= $key->cliente ?></td>
									<td class="fitwidth"><?= $key->modelo_vh ?></td>
									<td class="fitwidth"><?= $key->n_orden ?></td>
									<td class="fitwidth"><?= $key->mail ?></td>
									<td class="fitwidth"><?= $key->telef ?></td>
									<td class="fitwidth"><?= $key->pregunta1 ?></td>
									<td class="fitwidth"><?= $key->pregunta2 ?></td>
									<td class="fitwidth"><?= $key->pregunta3 ?></td>
									<td class="fitwidth"><?= $key->pregunta4 ?></td>
									<td class="fitwidth">-</td>
									<td class="fitwidth"><i class='d-none'><?= $key->pregunta5 ?></i><a class="btn btn-outline-info" onclick="comentario('<?= $key->pregunta5 ?>');"><i class="fas fa-comments text-primary"></i></a></td>
									<td class="fitwidth"><?= $key->tecnico ?></td>
									<td class="fitwidth"><?= $tipificacion  ?></td>
									<td class="fitwidth">

										<table style="display: none;" class="">
											<tbody id="cuerpoTabla-<?= $key->id ?>">
											</tbody>
										</table>
										<script>
											setTimeout(function() {
												open_list_verb_Comentarios(<?= $key->id ?>);
											}, 2000);
										</script>

										<a href="#" class="btn btn-outline-info mr-3  <?= $deshabilitarVerb ?>" onclick="open_form_verb(<?= $key->id ?>);">
											<i class="fas fa-edit"></i>
										</a>
										<a href="#" class="btn btn-outline-info" onclick="open_list_verb(<?= $key->id ?>);">
											<i class="fas fa-eye"></i>

										</a>

									</td>
									<td class="fitwidth"><?= $estado_caso  ?></td>
									<td class="fitwidth "><i class='d-none'><?= $cometarioFinal ?></i><a class="<?= $valor ?>" onclick="comentariofinal('<?= $cometarioFinal ?>');"><i class="fas fa-comments"></i></a></td>
									<td class="fitwidth"><?= $tipificacion_de_cierre ?></td>
								</tr>
							<?php } ?>

							<!--nueva tr nuevo-->
							<?php foreach ($qr->result() as $key) {
								$deshabilitar = $key->estado_caso == "Cerrado" ? ' disabled' : '';
								$deshabilitarVerb = $key->estado_caso == "Cerrado" || $key->estado_caso == "Abierto" ? '' : ' disabled';

								/*dar color segun el tiempo en contestar verde esta dentro de los tiempo correctos para contestar, amarillo esta por pasar de la fecha, rojo esta lleva varios dias sin g¿haber sido respondida */
								$colorPrioritario = "";
								$date1 = new DateTime(date('Y-m-d'));
								$date2 = new DateTime($key->fecha);
								$dia = $date1->diff($date2);
								$dia = intval($dia->days);
								$colorPrioritario =  ($dia < 3 ? 'background-color:#ABEBC6;' : ($dia >= 3 && $dia <= 6 ? 'background-color:#EDBB99;' : ($dia > 6 ? 'background-color:#EC7063;'  : '')));


								$colorExiware = "";
								$Exiware =  'QR';
								$colorExiware = $Exiware == 'QR' ? 'background-color:#BCB0F7;' : 'background-color:#DBFEFF;';

								$cometarioFinal = trim($key->comentarios_final_caso);
								$valor = $cometarioFinal == "" ? 'btn btn-secondary text-white' : "btn btn-info text-white";

								/*cambiar a color blanco  los pqr cerrados */
								$resuelt = $key->estado_caso == 'Cerrado' ? 'background-color:#FFFEFE; !important' : '';

								/*validar el valor que traer tipificacion */
								$tipificacion =  $key->tipificacion_encuesta == NULL ? 'Gestionar' : $key->tipificacion_encuesta;

								/*validar el valor del estado del caso */
								$estado_caso =  $key->estado_caso == NULL ? 'Gestionar' : $key->estado_caso;

								/*validar el valor que traer tipificacion de cierre */
								$tipificacion_de_cierre =  $key->tipificacion_cierre  == NULL ? 'Gestionar' : $key->tipificacion_cierre;


							?>
								<tr style="<?= $colorExiware ?>">
									<td class='fijar colun1' style="<?= $colorPrioritario, $resuelt ?>"><a href="#" class="btn btn-warning btn-sm <?= $deshabilitar ?>" onclick="modal_comentarios('NPS Interno',<?= $key->id ?>);"><i class="fas fa-edit"></i></a></td>
									<td class="fitwidth fijar colun2" style="<?= $colorPrioritario, $resuelt ?>">QR</td>
									<td class="fitwidth fijar colun3" style="<?= $colorPrioritario, $resuelt ?>"><?= $key->id ?></td>
									<td class="fitwidth"><?= $key->descripcion ?></td>
									<td class="fitwidth font-weight-bold text-dark" data-toggle="popover" title="Este PQR tiene <?= $dia ?> Dias sin haber sido Gestionado" style="<?= $colorPrioritario, $resuelt ?>"> <?= $key->fecha ?></td>
									<td class="fitwidth"><?= $key->placa ?></td>
									<td class="fitwidth"><?= $key->cliente ?></td>
									<td class="fitwidth"><?= $key->modelo_vh ?></td>
									<td class="fitwidth"><?= $key->numero ?></td>
									<td class="fitwidth"><?= $key->mail ?></td>
									<td class="fitwidth"><?= $key->telef ?></td>
									<td class="fitwidth"><?= $key->pregunta1 ?></td>
									<td class="fitwidth"><?= $key->pregunta2 ?></td>
									<td class="fitwidth"><?= $key->pregunta3 ?></td>
									<td class="fitwidth"><?= $key->pregunta4 ?></td>
									<td class="fitwidth">-</td>
									<td class="fitwidth"><i class='d-none'><?= $key->pregunta5 ?></i><a class="btn btn-outline-info" onclick="comentario('<?= $key->pregunta5 ?>');"><i class="fas fa-comments text-primary"></i></a></td>
									<td class="fitwidth"><?= $key->tecnico ?></td>
									<td class="fitwidth"><?= $tipificacion ?></td>
									<td class="fitwidth">
										<table style="display: none;" class="">
											<tbody id="cuerpoTabla-<?= $key->id ?>">
											</tbody>
										</table>
										<script>
											setTimeout(function() {
												open_list_verb_Comentarios(<?= $key->id ?>);
											}, 2000);
										</script>
										<a href="#" class="btn btn-outline-info mr-3  <?= $deshabilitarVerb ?>" onclick="open_form_verb(<?= $key->id ?>);"><i class="fas fa-edit"></i></a><a href="#" class="btn btn-outline-info" onclick="open_list_verb(<?= $key->id ?>);"><i class="fas fa-eye"></i></a>
									</td>
									<td class="fitwidth"><?= $estado_caso ?></td>
									<td class="fitwidth "><i class='d-none'><?= $cometarioFinal ?></i><a class="<?= $valor ?>" onclick="comentariofinal('<?= $cometarioFinal ?>');"><i class="fas fa-comments"></i></a></td>
									<td class="fitwidth"><?= $tipificacion_de_cierre ?></td>
								</tr>
							<?php } ?>

							<!--fin de nueva tr-->
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- modal comentarios pqr y nps -->
<!-- Modal -->
<div class="modal fade" id="modal_comentarios" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Comentarios del caso</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form_pqr_nps">
					<div class="form-row">
						<input type="hidden" name="id_fuente" id="id_fuente">
						<input type="hidden" name="fuentee" id="fuentee">

						<div class="col-lg-12">
							<label for="tipif_enc">Tipificación encuesta</label>
							<select class="form-control js-example-basic-single" id="tipificacion_encuesta" name="tipificacion_encuesta" style="width: 100%" required="true">
							<option value="">Seleccione una opción...</option>
								<option value="Anonimo">Anonimo</option>
								<option value="Calidad de producto">Calidad de producto</option>
								<option value="Calidad reparacion/retorno">Calidad reparacion/retorno</option>
								<option value="Demora en servicio">Demora en servicio</option>
								<option value="Demora repuestos">Demora repuestos</option>
								<option value="Encuesta OK">Encuesta OK</option>
								<option value="Falta comunicación con cliente">Falta comunicación con cliente</option>
								<option value="Horarios servicio">Horarios servicio</option>
								<option value="Informacion incompleta a cliente">Informacion incompleta a cliente</option>
								<option value="Mala atención JT">Mala atención JT</option>
								<option value="Mala atención técnico">Mala atención técnico</option>
								<option value="Precio MO">Precio MO</option>
								<option value="Precio repuestos">Precio repuestos</option>
								<option value="Mala Atención JT">Mala Atención JT</option>
								<option value="Mala Atención Tecnico">Mala Atención Tecnico</option>
								<option value="No hay repuestos">No hay repuestos</option>
								<option value="Precio Repuestos">Precio Repuestos</option>
								<option value="percepción servicio">percepción servicio</option>
							</select>
						</div>
						<div class="col-lg-12">
							<label for="estado_caso">Estado del caso</label>
							<select class="form-control js-example-basic-single" id="estado_caso" name="estado_caso" style="width: 100%" required="true">
							<option value="">Seleccione una opción...</option>
								<option value="Abierto">Abierto</option>
								<option value="Cerrado">Cerrado</option>
							</select>
						</div>
						<div class="col-lg-12">
							<label for="tipif_cierre">Tipificación Cierre</label>
							<select class="form-control js-example-basic-single" id="tipificacion_cierre" name="tipificacion_cierre" style="width: 100%" required="true">
							<option value="">Seleccione una opción...</option>
								<option value="Anonimo">Anonimo</option>
								<option value="Atencion comercial">Atencion comercial</option>
								<option value="Cambio vehículo">Cambio vehículo</option>
								<option value="Conciliación verbal">Conciliación verbal</option>
								<option value="Diagnostico incompleto/incorrecto">Diagnostico incompleto/incorrecto</option>
								<option value="Dificil diagnostico">Dificil diagnostico</option>
								<option value="Fallo SIC">Fallo SIC</option>
								<option value="Llegada repuesto">Llegada repuesto</option>
								<option value="Retoma">Retoma</option>
								<option value="Reparado">Reparado</option>
								<option value="Calidad Producto">Calidad De Producto</option>
								<option value="percepción servicio">percepción servicio</option>
							</select>
						</div>
						<div class="col-lg-12">
							<label for="comentarios_fina_caso">Comentarios final del caso</label>
							<textarea class="form-control" id="comentarios_final_caso" name="comentarios_final_caso" required="true"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="crear_pqr_nps();">Guardar</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal Verbalizaciones -->
<div class="modal fade" id="modal_verb" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Nueva Verbalización</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form_verb">
					<input type="hidden" name="id_pqr_nps" id="id_pqr_nps">
					<div class="form-group">
						<label for="contacto">Nombre del contacto</label>
						<input type="text" class="form-control" id="contacto" name="contacto" required>
					</div>
					<div class="form-group">
						<label for="verbalizacion">Verbalización</label>
						<textarea class="form-control" id="verbalizacion" name="verbalizacion" rows="3" required></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="add_verb();">Guardar</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Lista Verbalizaciones -->
<div class="modal fade" id="modal_list_verb" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-center " id="exampleModalLabel">Lista de Verbalizaciones</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<thead class='color-dark'>
							<tr>
								<th style="color:black;" class='text-center' scope="col">Contacto</th>
								<th style="color:black;" class='text-center' scope="col">Verbalización</th>
								<th style="color:black;" class='text-center' scope="col">Fecha Contacto</th>
							</tr>
						</thead>
						<tbody id="listaVerbs" class='text-center'>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Modal NEW PQR -->
<div class="modal fade" id="new_pqr" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Nueva PQR</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form_pqr">
					<div class="form-row">
						<div class="col-lg-6">
							<label>Seleccione una fuente</label>
							<select class="form-control js-example-basic-single manage_btn" id="fuente" name="fuente" style="width: 100%" class="manage_btn" required>
							<option value="">Seleccione una opción...</option>
								<option value="Exiware">Exiware</option>
								<option value="GM">GM</option>
								<option value="Interno">Interno</option>
								<option value="PQR Escrita">PQR Escrita</option>
								<option value="PQR Telefono">PQR Telefono</option>
								<option value="PQR Verbal">PQR Verbal</option>
								<option value="PQR Whatsapp">PQR Whatsapp</option>
							</select>
						</div>
						<div class="col-lg-6">
							<label>Seleccione una sede</label>
							<select class="form-control js-example-basic-single manage_btn" id="sede" name="sede" style="width: 100%" required>
							<option value="">Seleccione una opción...</option>
								<option value="Girón Gasolina">Girón Gasolina</option>
								<option value="Girón Diesel">Girón Diesel</option>
								<option value="Girón LyP">Girón LyP</option>
								<option value="La Rosita">La Rosita</option>
								<option value="Barrancabermeja">Barrancabermeja</option>
								<option value="Boconó Gasolina">Boconó Gasolina</option>
								<option value="Boconó Diesel">Boconó Diesel</option>
								<option value="Boconó LyP">Boconó LyP</option>
							</select>
						</div>

						<div class="col-lg-6">
							<label>Fecha de solicitud</label>
							<input type="date" name="fecha" class="manage_btn form-control" style="width: 100%" id="fecha" required>
						</div>
						<div class="col-lg-6">
							<label>Ingrese la placa</label>
							<input type="text" name="placa" class="manage_btn form-control" style="width: 100%" id="placa" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="cargar_info_vh(event);" required>
							<small id="emailHelp" class="form-text text-muted" style="color: orange;">Precione enter para cargar la información</small>
						</div>

						<div class="col-lg-6">
							<label>Ingrese el NIT o CC del cliente</label>
							<input type="text" name="cliente" class="manage_btn form-control" style="width: 100%" id="cliente" onchange="" required>
						</div>

						<div class="col-lg-6">
							<label>Modelo del vehiculo</label>
							<input type="text" name="modelo_vh" class="manage_btn form-control" style="width: 100%" id="mod_vh" required>
						</div>


						<div class="col-lg-6">
							<label>Ingrese el numero de Orden</label>
							<input type="text" name="ot" class="manage_btn form-control" style="width: 100%" id="ot" required>
						</div>
						<div class="col-lg-6">
							<label>Ingrese el correo del cliente</label>
							<input type="text" name="mail" class="manage_btn form-control" style="width: 100%" id="mail" required>
						</div>


						<div class="col-lg-6">
							<label>Ingrese el telefono del cliente</label>
							<input type="text" name="telef" class="manage_btn form-control" style="width: 100%" id="telef" required>
						</div>
						<div class="col-lg-6">
							<label>Ingrese el NIT CC del tecnico</label>
							<select class="form-control js-example-basic-single manage_btn" id="tecnico" name="tecnico" style="width: 100%" required>
								<option value="">Seleccione una opción...</option>
								<?php foreach ($tecnico->result() as $key) { ?>
									<option value="<?= $key->nit ?>"><?= $key->nombres ?> - <?= $key->nit ?> </option>
								<?php } ?>
							</select>
						</div>

						<hr>
						<div class="col-lg-12 col-md-12">
							<label>Comentarios del cliente</label>
							<textarea name="comentarios" class="manage_btn form-control" rows='4' id="comentarios_clientes" required></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger shadow" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary shadow" onclick="crear_pqr();">Guardar</button>
			</div>
		</div>
	</div>
</div>

<!--modal para pintar comentario-->
<div class="modal animate__animated animate__zoomIn" tabindex="-1" id="modalcomentario">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<p id="respuesta"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal" id="cerrarm">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<!--modal para pintar comentario final-->
<div class="modal animate__animated animate__zoomIn" tabindex="-1" id="modalcomentariofinal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<p id="respuestacometariofinal"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal" id="fin">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('Informe_pqr_nps/footer'); ?>

