<?php

use Mpdf\Writer\BackgroundWriter;
use PhpOffice\PhpSpreadsheet\Worksheet\PageMargins;

/**
 * Controlador del modulo Cotizador
 */
class Cotizador extends CI_Controller
{
	/* Carga la vista para crear cotizaciones */
	public function cotizar()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Cotizar');
			$this->load->model('Model_Posible_retorno');

			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);

			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}

			$desc = $this->Cotizar->getClasesForm();
			$bodegas = $this->Cotizar->getBodegas();
			$nameAdicionales = $this->Cotizar->getNameAdicionales();
			$tipo_retornos = $this->Model_Posible_retorno->getTiposRetornos();
			//echo $id_usu;
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'pass' => $pass, 'id_usu' => $id_usu, 'descClase' => $desc, 'bodegas' => $bodegas, 'nameAdicionales' => $nameAdicionales, 'tipos_retornos'=>$tipo_retornos);
			//abrimos la vista
			$this->load->view('Cotizador/cotizacion', $arr_user);
		}
	}
	/* Obtenemos la información del vehiculo por la placa */
	public function cargarClasePlaca()
	{
		$this->load->model('Cotizar');

		$placa = $this->input->POST('placa');

		$clase = $this->Cotizar->getClasePlaca($placa);
		$vClase = $clase->result();
		if (!empty($vClase)) {
			echo json_encode($clase->result());
		} else {
			echo 'error';
		}
	}
	/* Obtenemos las revisiónes a cotizar*/
	public function cargarRevisionSelect()
	{
		$this->load->model('Cotizar');

		$clase = $this->input->POST('clase');

		$revision = $this->Cotizar->getRevisionSelect($clase);
		$vRevision = $revision->result();
		if (!empty($vRevision)) {
			echo json_encode($vRevision);
		} else {
			echo 'error';
		}
	}
	/* Obtenemos la descripción del modelo segun la clase seleccionada  */
	public function cargarDescModelSelect()
	{
		$this->load->model('Cotizar');

		$desc = $this->input->POST('desc');

		$descM = $this->Cotizar->getDescModelSelect($desc);
		$vdescM = $descM->result();
		if (!empty($vdescM)) {
			echo json_encode($vdescM);
		} else {
			echo 'error';
		}
	}
	/* Obtenemos la información de la revision seleccionada */
	public function cargarCotizacionDetalle()
	{
		$this->load->model('Cotizar');

		$clase = $this->input->POST('clase');
		$year = $this->input->POST('year');
		$bodega = $this->input->POST('bodega');
		$revision = $this->input->POST('revision');

		$data = $this->Cotizar->revisionDetalle($bodega, $clase, $revision);
		$dataAdicionales = $this->Cotizar->getAdicionalesMtto($clase, $revision);
		$mtto = $this->Cotizar->getManoObraMtto($clase, $year);
		/* Script para los repuestos que llevan llaves */
		$reptDependientes = $this->Cotizar->get_rept_dependientes($clase);
		$codigoInicial = 0;
		$codigoDepen = 0;
		$seqInicial = 0;
		$seqDepen = 0;
		if (count($reptDependientes->result()) > 0) {

			$codigoInicial = $reptDependientes->row(0)->codigo_ini;
			$codigoDepen = $reptDependientes->row(0)->codigo_depende;
			$seqInicial = $reptDependientes->row(0)->seq;
			$seqDepen = $reptDependientes->row(0)->seq_depende;
		}

		echo '<table class="table table-bordered" id="tableCotizacion">
			<thead>
			  <tr>
				<th scope="col">Codigo</th>
				<th scope="col">Descripción</th>
				<th scope="col">Cantidad</th>
				<th scope="col">Categoria</th>
				<th scope="col">Unidades disponibles</th>
				<th scope="col">Estado</th>
				<th scope="col">Valor</th>
			  </tr>
			</thead>
			<tbody id="tableCotizacion_body">
		';
		$bandera = 0;
		$totalR = 0;
		$totalM = 0;
		$arrayRepuestos = [];
		foreach ($data->result() as $key) {
			$checked = "";
			$estado = 'No autorizado';
			if ($key->Categoria === 'MANDATORIO') {
				$checked = "checked='true'";
				$totalR += round($key->Valor, 0, PHP_ROUND_HALF_UP);
				$estado = "Autorizado";
				array_push($arrayRepuestos, $key->codigo);
			}

			if ($codigoInicial == $key->codigo && $codigoDepen != $key->codigo) {
				$ValidarCheckList = 'onclick="ValidarCheckListDep_1(\'' . $key->Categoria . '\',\'' . $key->seq . '\',\'' . $key->codigo . '\',\'' . $seqDepen . '\',\'' . $codigoDepen . '\');"';
			} elseif ($codigoInicial != $key->codigo && $codigoDepen == $key->codigo) {
				$ValidarCheckList = 'onclick="ValidarCheckListDep_1(\'' . $key->Categoria . '\',\'' . $key->seq . '\',\'' . $key->codigo . '\',\'' . $seqInicial . '\',\'' . $codigoInicial . '\');"';
			} else {
				$ValidarCheckList = 'onclick="ValidarCheckList(\'' . $key->Categoria . '\',\'' . $key->seq . '\',\'' . $key->codigo . '\');"';
			}



			echo '
				<tr id="' . $bandera . '">
				<td scope="col">' . $key->codigo . '</td>
				<td scope="col">' . $key->descripcion . '</td>
				<td style="text-align: center;" scope="col">' . $key->Cantidad . '</td>
				<td scope="col">' . $key->Categoria . '</td>
				<td style="text-align: center;" scope="col">' . number_format($key->unidades_disponibles, 0, ',', '') . '</td>
				<td id="E' . $key->codigo . '" scope="col">' . $estado . '</td>
				<td scope="col" id="R' . $bandera . '"  class="valor">' . number_format(round($key->Valor, 0, PHP_ROUND_HALF_UP), 0, ',', '.') . '</td>
				<td scope="col"><input type="checkbox" class="check" id="check' . $key->seq . '" name="check" value="' . $bandera . '" ' . $checked . ' ' . $ValidarCheckList . ' /></td>
				</tr>
			';
			$bandera++;
		}

		echo '<tr>
			<th scope="col" colspan="6" class="valor">Subtotal</th>
			<td scope="col" colspan="1" id="subTotal2" class="valor">' . number_format($totalR, 0, ',', '.') . '</td>
		</tr>';
		/* Mantenimiento o mano de obra===> Precios */
		echo
		'<tr>
				<th scope="col" colspan="8">Mano de obra</th>
			</tr>
			<tr>
				<th scope="col" colspan="1">Código</th>
				<th scope="col" colspan="3">Descripcion</th>
				<th scope="col" colspan="1">Tiempo</th>
				<th scope="col" colspan="1">Estado</th>
				<th scope="col" colspan="1">Valor</th>
				<th scope="col" colspan="1"></th>
			</tr>';
		$bandera2 = 0;
		$tHorasAgenda = 0;
		foreach ($mtto->result() as $key) {
			$estado2 = 'No autorizado';
			$checked = '';
			$validarYear = Date('Y') - $year;
			if ($key->descripcion_operacion === 'MANTENIMIENTO') {
				$checked = 'checked';
				$estado2 = 'Autorizado';
				$tHorasAgenda += $key->cant_horas;

				if ($validarYear <= 5) {
					$totalM += round($key->valor_unitario, 0, PHP_ROUND_HALF_UP);
					$costo = $key->valor_unitario;
				} else {
					$totalM += round($key->valor_mas_5anos, 0, PHP_ROUND_HALF_UP);
					$costo = $key->valor_mas_5anos;
				}
			}else {
				if ($validarYear <= 5) {
					$costo = $key->valor_unitario;
				} else {
					$costo = $key->valor_mas_5anos;
				}
			}
			
			echo
			'<tr id="' . $key->operacion . '">
				<td class="Ad_' . $key->operacion . '" scope="col" colspan="1">' . $key->operacion . '</td>
				<td scope="col" colspan="3">' . $key->descripcion_operacion . '</td>
				<td style="text-align: center;" scope="col" colspan="1" class="cantHorasAgenda">' . $key->cant_horas . '</td>
				<td id="E' . $key->operacion . '" scope="col" colspan="1">' . $estado2 . '</td>
				<td scope="col" colspan="1" id="V' . $key->operacion . '"  class="valor">' . number_format(round($costo, 0, PHP_ROUND_HALF_UP), 0, ',', '.') . '</td>
				<td scope="col"><input type="checkbox" class="check2" id="check2' . $bandera2 . '" name="check2" value="' . $key->operacion . '" ' . $checked . ' onclick="ValidarCheckList(\'' . $key->descripcion_operacion . '\',\'check2' . $bandera2 . '\',\'' . $key->operacion . '\');" /></td>
				</tr>';
			$bandera2++;
		}

		echo '<input type="hidden" id="bander_2" value="' . $bandera2 . '">';

		$totalAdicionales = 0;
		if (COUNT($dataAdicionales->result()) > 0) {
			# seq_rpto	clase	revision	codigo	cantidad	tiempo_adicional	valor_mas_5anos	valor_menos_5anos	nombre_operacion
			$bandera3 = 0;
			$validarYearTwo = Date('Y') - $year;
			foreach ($dataAdicionales->result() as $key) {

				if ($validarYearTwo <= 5) {
					$costoAdicional = $key->valor_menos_5anos;
				} else {
					$costoAdicional = $key->valor_mas_5anos;
				}
				/* Script para validar dependencias */
				if ($codigoInicial == $key->codigo && $codigoDepen != $key->codigo) {
					$ValidarCheckList2 = 'onclick="ValidarCheckListDep_2(\'MANDATORIO\',\'' . $key->seq_rpto . '\',\'' . $key->codigo . '\',\'' . $seqDepen . '\',\'' . $codigoDepen . '\');"';
				} elseif ($codigoInicial != $key->codigo && $codigoDepen == $key->codigo) {
					$ValidarCheckList2 = 'onclick="ValidarCheckListDep_2(\'MANDATORIO\',\'' . $key->seq_rpto . '\',\'' . $key->codigo . '\',\'' . $seqInicial . '\',\'' . $codigoInicial . '\');"';
				} else {
					$ValidarCheckList2 = 'onclick="ValidarCheckList3(\'MANDATORIO\',\'' . $key->seq_rpto . '\',\'' . $key->codigo . '\');"';
				}

				$tHorasAgenda += $key->tiempo_adicional;


				echo '<tr id="' . $key->seq_rpto . '">
				<td scope="col" colspan="1">' . $key->codigo . '</td>
				<td scope="col" colspan="3">' . $key->nombre_operacion . '</td>
				<td style="text-align: center;" scope="col" colspan="1" class="cantHorasAgenda">' . $key->tiempo_adicional . '</td>
				<td scope="col" id="A' . $key->codigo . '" colspan="1">Autorizado</td>
				<td scope="col" colspan="1" id="V' . $key->seq_rpto . '" class="valor">' . number_format(round($costoAdicional, 0, PHP_ROUND_HALF_UP), 0, ',', '.') . '</td>
				<td scope="col"><input type="checkbox" class="check2" id="check3' . $key->seq_rpto . '" name="check2" value="' . $key->seq_rpto . '" checked="true" ' . $ValidarCheckList2 . '/></td>
				</tr>';

				$totalAdicionales += $costoAdicional;
			}
		}



		echo '</tbody>
		<tfoot><tr>
			<th scope="col" colspan="3" class="valor"></th>
			<th scope="col" colspan="1" class="valor ">Total horas agendadas</th>
			<th scope="col" colspan="1" class="text-center" id="tHorasAgenda">' . $tHorasAgenda . '</th>
			<th scope="col" colspan="1" class="valor">Total</th>
			<td scope="col" colspan="1" id="totalM" class="valor">' . number_format(($totalR + $totalM + $totalAdicionales), 0, ',', '.') . '</td>
			<th scope="col" colspan="1" class="valor"></th>
		</tr>';

		echo '</tfoot></table>';
	}
	/* Guardar datos seleccionados en la cotización */
	public function guardarDatosCotizacion()
	{
		$this->load->model('Cotizar');
		$nitCliente = $this->input->POST('docCliente');
		$nomCliente = $this->input->POST('nomCliente');
		$telfCliente = $this->input->POST('telCliente');
		$placa = $this->input->POST('placa');
		$clase = $this->input->POST('clase');
		$desc = $this->input->POST('desc');
		$descModelo = $this->input->POST('descModelo');
		$kmA = $this->input->POST('kmA');
		$kmE = $this->input->POST('kmE');
		$kmC = $this->input->POST('kmC');
		$bodega = $this->input->POST('bodega');
		$revision = $this->input->POST('revision');
		$emailCliente = $this->input->POST('email');
		$Observacion = $this->input->POST('obs');
		$estado = $this->input->POST('estado');

		if ($estado == 0 || $estado == 1) {
			$estadoCotizacion = 0;
			$fechaAgenda = NULL;
		} else if ($estado == 2) {
			$estadoCotizacion = 1;
			$fechaAgenda = date('Ymd H:i:s');
		}



		$usu = $this->session->userdata('user');
		/* Informacion general de la cotización */
		$fecha = date('Ymd H:i:s');
		$dataCotizar = array(
			'nombreCliente' => $nomCliente,
			'nitCliente' => $nitCliente,
			'telfCliente' => $telfCliente,
			'placa' => $placa,
			'clase' => $clase,
			'descripcion' => $desc,
			'des_modelo' => $descModelo,
			'kilometraje_actual' => $kmA,
			'kilometraje_estimado' => $kmE,
			'kilometraje_cliente' => $kmC,
			'bodega' => $bodega,
			'revision' => $revision,
			'emailCliente' => $emailCliente,
			'usuario' => $usu,
			'observaciones' => $Observacion,
			'fecha_creacion' => "$fecha",
			'estado' => $estadoCotizacion,
			'fecha_agenda' => $fechaAgenda
		);

		/* print_r($dataCotizar);die; */


		/* Insertamos info general cotizacion*/
		$idCotizacion = $this->Cotizar->insertCotizacion($dataCotizar);

		if ($idCotizacion > 0) {
			/* Informacion de Repuestos y Mtto de la cotizacion */
			/* Repuestos */
			$nRepuestos = $this->input->POST('CantidadDatosRepuesto');
			$nMtto = $this->input->POST('CantidadDatosMtto');
			$dataR = [];
			for ($i = 0; $i < $nRepuestos; $i++) {
				$dataR[$i] = $this->input->POST('datosRepuesto' . $i);

				$fila = explode(",", $dataR[$i]);

				if ($fila[5] == 'Autorizado') {
					$estado = 1; /* 1 es Autorizado */
				} else {
					$estado = 0; /* 0 es No autorizado */
				}


				$dataInsert = array(
					'id_cotizacion' => $idCotizacion,
					'codigo' => $fila[0],
					'descripcion' => $fila[1],
					'cantidad' => $fila[2],
					'categoria' => $fila[3],
					'uni_disponibles' => $fila[4],
					'valor' => str_replace('.', '', $fila[6]),
					'estado' => $estado,
				);
				$this->Cotizar->addRepuestosCoti($dataInsert);
			}

			/* Mantenimiento */
			$dataM = [];
			for ($i = 0; $i < $nMtto; $i++) {
				$dataM[$i] = $this->input->POST('datosMtto' . $i);
				$fila_m = explode(",", $dataM[$i]);
				$cant_horas = $fila_m[2] != "" ? $fila_m[2] : 'NULL';
				if ($fila_m[3] == 'Autorizado') {
					$estado = 1;
				} else {
					$estado = 0;
				}

				$dataInsert_m = array(
					'id_cotizacion' => $idCotizacion,
					'mtto' => $fila_m[1],
					'valor' => str_replace('.', '', $fila_m[4]),
					'estado' => $estado,
					'cant_horas' => $cant_horas,
				);
				$this->Cotizar->addMTTOCoti($dataInsert_m);
			}
			echo "$idCotizacion";
			/* echo json_encode($idCotizacion); */
		} else {
			echo 'Error';
		}
	}
	/* Informe de las cotizaciones realizadas... */
	public function index()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('sedes');
			$this->load->model('perfiles');
			$this->load->model('Cotizar');

			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);

			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			

			//echo $id_usu;
			$arr_user = array(
				'userdata' => $userinfo,
				'menus' => $allmenus,
				'pass' => $pass,
				'id_usu' => $id_usu,
				'perfil' => $perfil_user->id_perfil
			);
			//abrimos la vista
			$this->load->view('Cotizador/index', $arr_user);
		}
	}
	/* Ver la cotizacion realizada en un PDF */
	public function verPdfCotizacion()
	{
		$this->load->model('Cotizar');
		$id = $this->input->post_get('id');
		$placa = $this->input->post_get('placa');
		$dataGeneral = $this->Cotizar->getCotizacion($id, $placa);
		/* Validar si existe información del id o número de cotizacion u Placa */
		if (empty($dataGeneral->result())) {
			echo '<!DOCTYPE html>
			<html lang="en">
				<head>
					<meta charset="UTF-8" />
					<meta http-equiv="X-UA-Compatible" content="IE=edge" />
					<meta name="viewport" content="width=device-width, initial-scale=1.0" />
					<title>Cotizacion para mantenimiento</title>
				</head>
				<style>				
					/* Parpadear */
					#adv {
						position: relative;
						animation-name: example;
						animation-duration: 1s;
						animation-iteration-count: infinite;
					  }
					  
					  @keyframes example {

						from {color: white;}
  						to {color: red;}
					  }					
						
				</style>
				<script>

					window.onload = function () 
					{
						/* function parpadeo(){
							var bandera = document.getElementById("adv").style.color;
							if (bandera == "white"){
								document.getElementById("adv").style = "color: red";
								
							}else {
								document.getElementById("adv").style = "color: white"
								
							}

							
						} */
						
										

						/* setTimeout(funcionConRetraso, 3000); */
						/* setInterval(parpadeo, 50); */

						
					}			
				
				</script>
			<body>
				<div class="content" style="height: 100%; display: flex; align-items: center; justify-content: center;">
					<div id="tarjeta" class="card text-center w-75 mx-auto h-50" align="center" style="width: 95%;
						background-color: #f8f9fa;
						-webkit-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);
						-moz-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);
						box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);">
							<div id="cab-tarjeta" class="card-header">
								<h2 id="adv" class="card-title" style="font-weight: 700!important;font-size: 2rem;">
								<strong>
									Advertencía 
								</strong>
									
								</h2>
							</div>
							<hr>
							<div class="card-body">	
							<h2>
								<strong>
									El número de cotizacion o placa no existe...
								</strong>
							</h2>								
								<p style="font-size: 1rem; line-height: 1.5; word-wrap: break-word;">
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet dignissim dolor.
									Morbi commodo nisi eget tortor volutpat, nec facilisis ex varius. Sed eu euismod erat.
									Nulla tristique vulputate lectus, at vulputate mauris pellentesque quis. Quisque lacinia mi velit,
									et scelerisque massa dignissim et. Curabitur non tincidunt tortor, eu placerat dui.
									Nunc vitae felis non ante blandit congue pharetra id est. Maecenas nec orci libero.
									Donec vel elementum augue. Integer erat ipsum, egestas facilisis urna eget,
									tristique posuere nisi. Sed egestas augue quis velit dictum, eu pretium risus semper.
									Fusce elementum rutrum posuere. Cras vel tortor diam. Donec pharetra.
								</p>								
							</div>
							<div class="card-footer bg-dark text-white" style="padding: 10px;
							position: relative;
							top: 20px;
							background-color: #343a40;
							color: white;
							display: flex;
							flex-direction: row;
							justify-content: space-around;">		
								Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet dignissim dolor.
								Morbi commodo nisi eget tortor volutpat, nec facilisis ex varius. Sed eu euismod erat.
								Nulla tristique vulputate lectus, at vulputate mauris pellentesque quis. Quisque lacinia mi velit,
								et scelerisque massa dignissim et. Curabitur non tincidunt tortor, eu placerat dui.
								Nunc vitae felis non ante blandit congue pharetra id est. Maecenas nec orci libero.
								Donec vel elementum augue. Integer erat ipsum, egestas facilisis urna eget,
								tristique posuere nisi. Sed egestas augue quis velit dictum, eu pretium risus semper.
								Fusce elementum rutrum posuere. Cras vel tortor diam. Donec pharetra.
			
								<div class="contacto" style="display: flex;	flex-direction: column;justify-content: space-evenly;">
								</div>
							</div>
					</div>
				</div>
			</body>
			</html>';
		} else {
			$dataRepuestos = $this->Cotizar->getRepuestosCoti($id);
			$dataMtto = $this->Cotizar->getMttoCoti($id);

			// Información para crear el PDF
			$data = array(
				'dataGeneral' => $dataGeneral,
				'dataRepuestos' => $dataRepuestos,
				'dataMtto' => $dataMtto
			);
			/* Cargar vista web en el navegador */
			/* $this->load->view('Cotizador/pdfCotizacion', $data); */


			// Cargar libreria de PDF 
			$mpdfConfig = array(
				'mode' => 'utf-8',
				'format' => 'A4',
				// format - A4, for example, default ''
				'default_font_size' => 12,
				// font size - default 0
				'default_font' => 'overpass',
				// default font family
				'margin_left' => 5,
				// 15 margin_left
				'margin_right' => 5,
				// 15 margin right
				'margin_top' => 21,
				// 16 margin top
				'margin_bottom' => 30,
				// margin bottom contra el footer
				'margin_header' => 5,
				// 9 margin header
				'margin_footer' => 5,
				// 9 margin footer
				'orientation' => 'P' // L - landscape, P - portrait
			);
			$mpdf = new \Mpdf\Mpdf($mpdfConfig);

			//Header
			$mpdf->SetHTMLHeader('<div style="position:absolute;" class=""><img src="' . base_url() . '/public/headerCotizacion2.png" width="100%" /></div>');
			// Footer 
			$mpdf->SetHTMLFooter('
				<p style="text-align:justify; font-size:12px"><b>*</b><i>Validez de la oferta en repuestos 5 días y 30 días en mano de obra y otros. </i></p>
				<p style="text-align:justify; font-size:12px"><b>*</b><i>Garantía 1 ańo o 20.000 Km a partir de la fecha de entrega del servicio en el taller, en repuestos que no correspondan a partes de desgaste.</i></p>
				<p style="text-align:justify; font-size:12px"><b>*</b><i>Según lo estipulado en el cupón de garantía, la no sustitución de repuestos considerados mandatorios, conlleva a la renuncia del beneficio de extensión de garantía y a la garantía de fábrica. </i></p>
			');
			//body
			$usu = $this->session->userdata('user');
			if ($usu != "") {
				$html = $this->load->view('Cotizador/pdfCotizacionTaller', $data, true);
			} else {
				$html = $this->load->view('Cotizador/pdfCotizacion', $data, true);
			}

			$mpdf->WriteHTML($html);

			// Contraseña 
			// $mpdf->SetProtection(array(), "$placa", "C0D13S3L");

			$mpdf->Output('Cotizacion-.pdf', 'I');
			exit;
		}
	}
	/* Enviar un correo con el enlace de descarga de la cotización */
	public function enviarEmailCotizacion()
	{
		$this->load->model('Cotizar');
		$id = $this->input->POST('id');
		$placa = $this->input->POST('placa');
		$estado = $this->input->POST('estado');
		$actualizarEstado = $this->input->POST('actualizarEstado');
		$dataGeneral = $this->Cotizar->getCotizacion($id, $placa);

		if ($actualizarEstado != "") {
			$this->Cotizar->updateEstado($id);
		}

		/* Cargar datos para el envio de correo electronico */
		foreach ($dataGeneral->result() as $key) {
			$placa = $key->placa;
			$correoCliente = $key->emailCliente;
			$correoAsesor = $key->correo;
			$nomCliente = $key->nombreCliente;
			$bodega = $key->bodega;
		}





		$this->load->library('phpmailer_lib');
		$correo = $this->phpmailer_lib->load();
		$correo->IsSMTP();
		$correo->SMTPAuth = true;
		$correo->SMTPSecure = 'tls';
		$correo->Host = "smtp.gmail.com";
		$correo->Port = 587;
		$correo->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);
		/* Correo Bodega */
		//$correo->addBCC('copia_oculta@outlook.com');
		if ($estado == 2) {
			switch ($bodega) {
				case 1:
					$nit = 1095931604;
					//$nit = 1097304901; programador3
					$correo->addBCC($this->Cotizar->getEmailBodega($nit));
					break;
				case 6:
					$nit = 37579713;
					$correo->addBCC($this->Cotizar->getEmailBodega($nit));
					break;
				case 7:
					$nit = 91274670;
					$correo->addBCC($this->Cotizar->getEmailBodega($nit));
					break;
				case 8:
					$nit = 1094532250;
					$correo->addBCC($this->Cotizar->getEmailBodega($nit));
					break;

				default:
					# code...
					break;
			}
		}

		/* Correo Receptor */
		if ($estado == 1 || $estado == 2) {
			$correo->addAddress("$correoCliente");
		}
		$correo->addBCC("programador3@codiesel.co");
		/* Correo del asesor - password */
		$correo->addAddress("$correoAsesor");
		/* Correo Emisor */
		$correo->Username = "no-reply@codiesel.co";
		$correo->Password = "wrtiuvlebqopeknz";

		$correo->SetFrom("no-reply@codiesel.co", "CODIESEL S.A");
		/* Asunto del correo */
		$correo->Subject = "Cotizacion mantenimiento - #$id";
		/* Cuerpo del correo  */
		$mensaje = '<!DOCTYPE html>
		<html lang="en">
			<head>
				<meta charset="UTF-8" />
				<meta http-equiv="X-UA-Compatible" content="IE=edge" />
				<meta name="viewport" content="width=device-width, initial-scale=1.0" />
				<title>Cotizacion para mantenimiento</title>
			</head>
		<body>
			<div class="content" style="height: 100%; display: flex; align-items: center; justify-content: center;">
				<div id="tarjeta" class="card text-center w-75 mx-auto h-50 p-2" align="center" style="width: 100%; max-width:800px;
					background-color: #f8f9fa;
					-webkit-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);
					-moz-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);
					box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);">
						<div id="cab-tarjeta" class="card-header">
							<img src="' . base_url() . 'public/headerEmailCotizacion.png" width="100%">
						</div>
						<div class="card-body" style="text-align:justify;  ">	
							<p class="card-text" style="font-size: 1rem; line-height: 1.5; word-wrap: break-word; padding:10px">
								¡Hola ' . $nomCliente . '!
								<br><br>
								¡Gracias por darle a tu Chevrolet el servicio que merece! 
								<br><br>
								En los talleres Codiesel contarás siempre con técnicos especializados, calidez en el servicio, transparencia en nuestros procesos y la garantía sobre el trabajo realizado. 
								<br><br>
								A continuación encontrarás la cotización de los servicios solicitados.
							</p>							
						</div>
						<div class="card-footer bg-dark text-white" style="padding: 10px;
						position: relative;
						top: 20px;
						background-color: #343a40;
						color: white;
						display: flex;
						flex-direction: row;
						justify-content: space-around;">		
								<p style="font-size: 1rem; line-height: 1.5; word-wrap: break-word;">
									<a style="color:#ffff;" href="' . base_url() . 'Cotizador/verPdfCotizacion?id=' . $id . '&placa=' . $placa . '" >Descargar cotización</a>
								</p>
		
							<div class="contacto" style="display: flex;	flex-direction: column;justify-content: space-evenly;">
							</div>
						</div>
				</div>
			</div>
		</body>
		</html>
		';

		$correo->MsgHTML($mensaje);


		if (!$correo->Send()) {
			echo 'Error';
		} else {
			echo 'Exito';
		}
	}
	/* Informe para obtener los repuestos con unidades disponibles en 0 a la hora de realizar la cotización */
	public function repuestosUndDisp()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Cotizar');

			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);

			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}

			//echo $id_usu;
			$arr_user = array(
				'userdata' => $userinfo,
				'menus' => $allmenus,
				'pass' => $pass,
				'id_usu' => $id_usu,
			);
			//abrimos la vista
			$this->load->view('Cotizador/repuestosUnidadesDisponibles', $arr_user);
		}
	}
	/* Informe para obtener los repuestos con unidades disponibles en 0 a la hora de realizar la cotización */
	public function repuestosUndDispCargar()
	{

		$this->load->model('Cotizar');
		$f_inicio = $this->input->POST('f_inicio');
		$f_final = $this->input->POST('f_final');
		$bodega = $this->input->POST('bodega');
		if ($bodega != "") {
			$bodega = ' and cc.bodega = ' . $bodega . '';
		} else {
			$bodega = '';
		}

		$dataRepuestos = $this->Cotizar->getRepuestosCotizacionesAll($f_inicio, $f_final, $bodega);

		if (!empty($dataRepuestos->result())) {
			foreach ($dataRepuestos->result() as $key) {
				echo
				'
						<tr>
							<td scope="col">' . $key->bodega . '</td>
							<td scope="col">' . $key->cant_codigo . '</td>
							<td scope="col">' . $key->codigo . '</td>
							<td scope="col">' . $key->descripcion . '</td>
						</tr>
					';
			}
		} else {
		}


		//abrimos la vista


	}

	public function mttoPrepagado()
	{
		$this->load->model('Cotizar');
		$placa = $this->input->POST('placa');

		$dataMttoPre = $this->Cotizar->getMttoPrepagado($placa);

		if (!empty($dataMttoPre->result())) {
			$datos = $dataMttoPre->row();
			echo $datos->prepagado;
		} else {
			echo 'N/A';
		}
	}

	public function actualizarEstado()
	{
		$this->load->model('Cotizar');
		$id = $this->input->POST('id');

		if ($this->Cotizar->actualizarEstadoCoti($id)) {
			echo 'Exito';
		} else {
			echo 'Error';
		}
	}
	/*
	06/10/2022
	Sergio Galvis
	Informe de control de repuestos por Cotización y Citas
	*/

	public function control_rep_cotiza()
	{

		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Cotizar');

			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);

			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$dataCotizacion = $this->Cotizar->getControlRepuestos();
			//echo $id_usu;
			$arr_user = array(
				'userdata' => $userinfo,
				'menus' => $allmenus,
				'pass' => $pass,
				'id_usu' => $id_usu,
				'dataCotizaciones' => $dataCotizacion,
			);
			//abrimos la vista
			$this->load->view('Cotizador/control_repuestos', $arr_user);
		}
	}

	/* Autor: Sergio Galvis
	Fecha: 01/02/2023
	Asunto: Funciónes para agregar repuestos y mtto adicional a la cotización de vehiculos livianos */

	public function getRepuestosAdicionales()
	{

		$this->load->model('Cotizar');

		$clase = $this->input->POST('clase');
		$year = $this->input->POST('year');
		$bodega = $this->input->POST('bodega');
		$adicional = $this->input->POST('adicional');

		$dataRepuestosAd = $this->Cotizar->getRepuestosAdicionales($clase, $bodega, $adicional);
		$dataManoObraAd = $this->Cotizar->getManoObraAdicional($clase, $adicional, $operacion = "");

		if (count($dataRepuestosAd->result()) > 0) {
			//adicional	codigo	descripcion	cantidad	Valor	unidades_disponibles
			$tdAdicionalH="";
			if($adicional === 'HIGIENIZACION'){
				$tdAdicionalH = '<th>CHECK</th>';
			}

			echo '<table id="tRepuestos" class="table table-bordered text-center">
			<thead>
				<tr class="text-center"><th colspan="5">Repuestos</th></tr>
				<tr>
					<th>CÓDIGO</th>
					<th>DESCRIPCIÓN</th>
					<th>CANTIDAD</th>
					<th>VALOR</th>
					<th>UND DISPONIBLES</th>
					'.$tdAdicionalH.'
				</tr>
			</thead>
			<tbody>';
			$total = 0;
			foreach ($dataRepuestosAd->result() as $key) {
				
				if($key->adicional == 'HIGIENIZACION'){
					if($key->codigo != 'AIRLIFE' && $key->codigo != 'HIGIENIZADOR*/'){
						echo '<tr class="filaRptos">
						<td>' . $key->codigo . '</td>
						<td>' . $key->descripcion . '</td>
						<td>' . $key->cantidad . '</td>
						<td>' . number_format(ceil($key->Valor), 0, ',', '.') . '</td>
						<td>' . number_format($key->unidades_disponibles,0,',','.') . '</td>
						<td><input class="form-control" type="checkbox" id="rH'.$key->codigo.'" name="rptoH" value="0" onchange="checkHigienizador(this);"></td>
						</tr>';
					}else {
						echo '<tr class="filaRptos">
						<td>' . $key->codigo . '</td>
						<td>' . $key->descripcion . '</td>
						<td>' . $key->cantidad . '</td>
						<td>' . number_format(ceil($key->Valor), 0, ',', '.') . '</td>
						<td>' . number_format($key->unidades_disponibles,0,',','.') . '</td>
						<td><input class="form-control" type="radio" id="'.$key->codigo.'" value="'.$key->codigo.'" checked name="TotH" onchange="totalBeforeAddAdicional();"></td>
						</tr>';
						$total = ceil($key->Valor);
					}
				}else {
					$total += ceil($key->Valor);
					echo '<tr>
						<td>' . $key->codigo . '</td>
						<td>' . $key->descripcion . '</td>
						<td>' . $key->cantidad . '</td>
						<td>' . number_format(ceil($key->Valor), 0, ',', '.') . '</td>
						<td>' . $key->unidades_disponibles . '</td>
					</tr>';
				}
				
			}
			echo '</tbody>
			<tfoot>
				<tr>
					<th colspan="3">Total</th>
					<td class="text-left" colspan="2" id="totalBeforeAddAdicional">$'.number_format($total, 0, ',', '.').'</td>
				</tr>
			</tfoot>
			</table>';
		}

		if (count($dataManoObraAd->result()) > 0) {

			echo '<table id="tMano" class="table table-bordered text-center">
			<thead>
				<tr class="text-center"><th colspan="4">Mano de Obra</th></tr>
				<tr>
					<th>OPERACIÓN</th>
					<th>TIEMPO</th>
					<th>VALOR</th>
					<th>ADICIONAL</th>
				</tr>
			</thead>
			<tbody>';
			$validarYearTwo = Date('Y') - $year;
			foreach ($dataManoObraAd->result() as $key) {
				//id	clase	operacion	tiempo	valor_menos_5anos	valor_mas_5anos	adicional
				if ($validarYearTwo <= 5) {
					$costoAdicional = $key->valor_menos_5anos;
				} else {
					$costoAdicional = $key->valor_mas_5anos;
				}
				$btnAddAdicional = '';
				if ($key->adicional === 'DIAGNOSTICO') {
					$btnAddAdicional = '<button class="btn btn-success" onclick="addOperacionMttoAdicional(\'' . $key->adicional . '\',\'' . $key->operacion . '\');">Agregar</button>';
				}

				echo '<tr class="filaMano">
					<td>' . $key->operacion . '</td>
					<td>' . $key->tiempo . '</td>
					<td>' . number_format(ceil($costoAdicional), 0, ',', '.') . '</td>
					<td>' . $key->adicional . $btnAddAdicional . '</td>
					</tr>';
			}
			echo '</tbody></table>';
		}
	}

	public function cargarCotizacionDetalleAdicionalesR()
	{
		$this->load->model('Cotizar');

		$clase = $this->input->POST('clase');
		$year = $this->input->POST('year');
		$bodega = $this->input->POST('bodega');
		$adicional = $this->input->POST('adicional');
		$bandera_2 = $this->input->POST('bandera');

		$dataRepuestosAd = $this->Cotizar->getRepuestosAdicionales($clase, $bodega, $adicional);

		$totalR = 0;
		//codigo	descripcion	cantidad	Valor	unidades_disponibles
		if (count($dataRepuestosAd->result()) > 0) {
			$text = $adicional;
			$class_1 = str_replace(' ', '_', $text);
			$bandera = 0;
			foreach ($dataRepuestosAd->result() as $key) {
				$totalR += round($key->Valor, 0, PHP_ROUND_HALF_UP);

				echo '<tr name="fila_adicional_R" class="' . $class_1 . '" id="ADR' . $bandera_2 . '">
					<td scope="col">' . $key->codigo . '</td>
					<td scope="col">' . $key->descripcion . '</td>
					<td style="text-align: center;" scope="col">' . $key->cantidad . '</td>
					<td style="text-align: center;" scope="col">' . number_format($key->unidades_disponibles, 0, ',', '') . '</td>
					<td scope="col" id="R_ad' . $bandera_2 . '"  class="valor_ad text-right">' . number_format(round($key->Valor, 0, PHP_ROUND_HALF_UP), 0, ',', '.') . '</td>
					<td class="tipo_adicional d-none">' . $adicional . '</td>';
				if ($bandera == 0) {
					echo '<td style="vertical-align: middle !important;" rowspan="' . count($dataRepuestosAd->result()) . '" scope="col" class="text-center"><button class="btn btn-danger" onclick="deleteAdicional(\'' . $class_1 . '\');">Eliminar</button></td>';
				}
				echo '</tr>';
				$bandera_2++;
				$bandera++;
			}
		}
	}


	public function cargarCotizacionDetalleAdicionalesM()
	{
		$this->load->model('Cotizar');

		$clase = $this->input->POST('clase');
		$year = $this->input->POST('year');
		$bodega = $this->input->POST('bodega');
		$adicional = $this->input->POST('adicional');
		$operacion = $this->input->POST('operacion');
		$bandera_2 = $this->input->POST('bandera');

		if ($bandera_2 != '0') {
			$arrId = explode("_", $bandera_2);
			$newId = ($arrId[1] + 1);
			$bandera_2 = $newId;
		}


		$operacion_where = "";
		if ($operacion != "") {
			$operacion_where = "AND operacion='$operacion'";
		}

		$dataManoObraAd = $this->Cotizar->getManoObraAdicional($clase, $adicional, $operacion_where);
		$totalM = 0;
		/* Mantenimiento o mano de obra===> Precios */
		if (count($dataManoObraAd->result()) > 0) {
			$text = $adicional;
			$class_2 = str_replace(' ', '_', $text);
			//id	clase	operacion	tiempo	valor_menos_5anos	valor_mas_5anos	adicional
			$bandera2 = 0;
			foreach ($dataManoObraAd->result() as $key) {
				$validarYear = Date('Y') - $year;
				if ($validarYear <= 5) {
					$costo = $key->valor_menos_5anos;
					$totalM += ceil($key->valor_menos_5anos);
				} else {
					$costo = $key->valor_mas_5anos;
					$totalM += ceil($key->valor_mas_5anos);
				}
				echo
				'<tr name="fila_adicional_M" class="' . $class_2 . '" id="ADM_' . $bandera_2 . '">
				<td scope="col" colspan="3">' . $key->operacion . '</td>
				<td style="text-align: center;" scope="col" colspan="1" class="cantHorasAgenda_ad">' . $key->tiempo . '</td>
				<td scope="col" colspan="1" id="V' . $key->operacion . '"  class="valor_ad_M text-right">' . number_format(round($costo, 0, PHP_ROUND_HALF_UP), 0, ',', '.') . '</td>
				<td class="tipo_adicional d-none">' . $adicional . '</td>';

				if ($bandera2 == 0) {
					echo '<td style="vertical-align: middle !important;" rowspan="' . count($dataManoObraAd->result()) . '" scope="col" class="text-center"><button class="btn btn-danger" onclick="deleteAdicional(\'' . $class_2 . '\');">Eliminar</button></td>';
				}
				echo '</tr>';
				$bandera2++;
			}
		}
	}

	public function cargarCotizacionDetalleM()
	{
		$this->load->model('Cotizar');

		$clase = $this->input->POST('clase');
		$year = $this->input->POST('year');
		$bodega = $this->input->POST('bodega');
		$adicional = $this->input->POST('adicional');
		$operacion = $this->input->POST('operacion');

		$operacion_where = "";
		if ($operacion != "") {
			$operacion_where = "AND operacion='$operacion'";
		}

		$dataManoObraAd = $this->Cotizar->getManoObraAdicional($clase, $adicional, $operacion_where);

		if (count($dataManoObraAd->result()) > 0) {

			$validarYear = Date('Y') - $year;
			if ($validarYear <= 5) {
				$costo = $dataManoObraAd->row(0)->valor_menos_5anos;
			} else {
				$costo = $dataManoObraAd->row(0)->valor_mas_5anos;
			}

			$dataSend = array(
				'adicional' => $dataManoObraAd->row(0)->adicional,
				'clase' => $dataManoObraAd->row(0)->clase,
				'id' => $dataManoObraAd->row(0)->id,
				'operacion' => $dataManoObraAd->row(0)->operacion,
				'tiempo' => $dataManoObraAd->row(0)->tiempo,
				'costo' => number_format(ceil($costo), 0, ',', '.'),
			);
		}

		echo json_encode($dataSend);
	}


	/* Guardar datos seleccionados en la cotización */
	public function guardarDatosCotizacionAdicional()
	{
		$this->load->model('Cotizar');
		$nitCliente = $this->input->POST('docCliente');
		$nomCliente = $this->input->POST('nomCliente');
		$telfCliente = $this->input->POST('telCliente');
		$placa = $this->input->POST('placa');
		$clase = $this->input->POST('clase');
		$desc = $this->input->POST('desc');
		$descModelo = $this->input->POST('descModelo');
		$kmA = $this->input->POST('kmA');
		$kmE = $this->input->POST('kmE');
		$kmC = $this->input->POST('kmC');
		$bodega = $this->input->POST('bodega');
		$revision = $this->input->POST('revision');
		$emailCliente = $this->input->POST('email');
		$Observacion = $this->input->POST('obs');
		$estado = $this->input->POST('estado');

		if ($estado == 0 || $estado == 1) {
			$estadoCotizacion = 0;
			$fechaAgenda = NULL;
		} else if ($estado == 2) {
			$estadoCotizacion = 1;
			$fechaAgenda = date('Ymd H:i:s');
		}



		$usu = $this->session->userdata('user');
		/* Informacion general de la cotización */
		$fecha = date('Ymd H:i:s');
		$dataCotizar = array(
			'nombreCliente' => $nomCliente,
			'nitCliente' => $nitCliente,
			'telfCliente' => $telfCliente,
			'placa' => $placa,
			'clase' => $clase,
			'descripcion' => $desc,
			'des_modelo' => $descModelo,
			'kilometraje_actual' => $kmA,
			'kilometraje_estimado' => $kmE,
			'kilometraje_cliente' => $kmC,
			'bodega' => $bodega,
			'revision' => $revision,
			'emailCliente' => $emailCliente,
			'usuario' => $usu,
			'observaciones' => $Observacion,
			'fecha_creacion' => "$fecha",
			'estado' => $estadoCotizacion,
			'fecha_agenda' => $fechaAgenda,
			'tipo_mtto' => 1,
		);


		/* Insertamos info general cotizacion*/
		$idCotizacion = $this->Cotizar->insertCotizacion($dataCotizar);

		if ($idCotizacion > 0) {
			/* Informacion de Repuestos y Mtto de la cotizacion */
			/* Repuestos */
			$nRepuestos = $this->input->POST('CantidadDatosRepuesto');
			$nMtto = $this->input->POST('CantidadDatosMtto');
			$dataR = [];
			for ($i = 0; $i < $nRepuestos; $i++) {
				$dataR[$i] = $this->input->POST('datosRepuesto' . $i);
				$row = explode(",", $dataR[$i]);
				$dataInsertR = array(
					'id_cotizacion' => $idCotizacion,
					'codigo' => $row[0],
					'descripcion' => $row[1],
					'cantidad' => $row[2],
					//'categoria' => '',
					'uni_disponibles' => $row[3],
					'valor' => str_replace('.', '', $row[4]),
					'estado' => 1,
					'adicional' => $row[5],
				);
				$this->Cotizar->addRepuestosCoti($dataInsertR);
			}

			/* Mantenimiento */
			$dataM = [];
			for ($i = 0; $i < $nMtto; $i++) {
				$dataM[$i] = $this->input->POST('datosMtto' . $i);

				$row_2 = explode(",", $dataM[$i]);

				$dataInsertM = array(
					'id_cotizacion' => $idCotizacion,
					'mtto' => $row_2[0],
					'cant_horas' => $row_2[1],
					'valor' => str_replace('.', '', $row_2[2]),
					'estado' => 1,
					'adicional' => $row_2[3],

				);
				$this->Cotizar->addMTTOCoti($dataInsertM);
			}
			echo "$idCotizacion";
			/* echo json_encode($idCotizacion); */
		} else {
			echo 'Error';
		}
	}


	public function paintTableInfoCotizacion()
	{
		//si ya hay datos de session los carga de nuevo
		$this->load->model('usuarios');
		$this->load->model('menus');
		$this->load->model('sedes');
		$this->load->model('perfiles');
		$this->load->model('Cotizar');

		$usu = $this->session->userdata('user');
		
		//obtenemos el perfil del usuario
		$perfil_user = $this->perfiles->getPerfilByUser($usu);
		/*SEDES*/
		$data_bod = $this->sedes->get_sedes_user($usu);
		$bods = "";
		foreach ($data_bod->result() as $key) {
			$bods .= $key->idsede . ",";
		}
		$bods = trim($bods, ',');

		if ($perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 20) {
			$where = "";
		} elseif ($perfil_user->id_perfil == 33 || $perfil_user->id_perfil == 34 || $perfil_user->id_perfil == 56) {
			$where = "where CT.bodega IN ($bods)";
		} else {
			$where = "WHERE usuario = $usu";
		}
		$dataCotizaciones = $this->Cotizar->getCotizacionesAll($where);

		foreach ($dataCotizaciones->result() as $key) {
			if ($perfil_user->id_perfil == 33) {
				$btnOpcion = '<td class="noExl" scope="col"><button type="button" class="btn btn-success" onclick="verCotizacion(' . $key->id_cotizacion . ',\'' . $key->placa . '\');"><i class="fas fa-file-invoice"></i></button></td>';
			} else {
				$btnOpcion = '<td class="noExl" scope="col"><button type="button" class="btn btn-success" onclick="verCotizacion(' . $key->id_cotizacion . ',\'' . $key->placa . '\');"><i class="fas fa-file-invoice"></i></button></td>';
			}
			if ($perfil_user->id_perfil == 33 || $perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 34 || $perfil_user->id_perfil == 56) {
				$btnEnviar = '<td class="noExl" scope="col"><button disabled type="button" class="btn btn-success"><i class="fas fa-paper-plane"></i></button></td>';
				$btnEstado = '<td class="noExl" scope="col"><button disabled type="button" class="btn btn-danger"><i class="fas fa-calendar-check"></i></button></td>';
			} else {
				$fecha = date('Y-m-d');
				if ($key->caducidad >= $fecha) {
					if ($key->estado == 0) {

						$btnEnviar = '<td class="noExl" scope="col">
																<button type="button" class="btn btn-success" onclick="enviarEmailCotizacion(' . $key->id_cotizacion . ',\'' . $key->placa . '\');">
																	<i class="fas fa-paper-plane"></i>
																</button>
														</td>';

						$btnEstado = '<td class="noExl" scope="col">
															<button type="button" class="btn btn-danger" onclick="actualizarEstado(' . $key->id_cotizacion . ');">
																<i class="fas fa-calendar-check"></i>
															</button>
														</td>';
					} else {
						$btnEnviar = '<td class="noExl" scope="col">
																<button type="button" class="btn btn-success" onclick="enviarEmailCotizacion(' . $key->id_cotizacion . ',\'' . $key->placa . '\');">
																	<i class="fas fa-paper-plane"></i>
																</button>
														</td>';
						$btnEstado = '<td class="noExl" scope="col"><button disabled type="button" class="btn btn-danger"><i class="fas fa-calendar-check"></i></button></td>';
					}
				} else {
					$btnEnviar = '<td class="noExl" scope="col">
																<button type="button" class="btn btn-success" onclick="enviarEmailCotizacion(' . $key->id_cotizacion . ',\'' . $key->placa . '\');">
																	<i class="fas fa-paper-plane"></i>
																</button>
														</td>';
					$btnEstado = '<td class="noExl" scope="col"><button disabled type="button" class="btn btn-danger"><i class="fas fa-calendar-check"></i></button></td>';
				}
			}
			$estadoCotizacion = 'SIN AGENDAR';
			if ($key->estado == 1) {
				$estadoCotizacion = 'AGENDADA';
			}

			echo '<tr scope="row">
				<td scope="col">' . $key->id_cotizacion . '</td>
				<td scope="col">' . $key->asesor . '</td>
				<td scope="col">' . $key->placa . '</td>
				<td scope="col">' . $key->clase . '</td>
				<td scope="col">' . $key->des_modelo . '</td>
				<td scope="col">' . $key->kilometraje_cliente . '</td>
				<td scope="col">' . $key->revision . '</td>
				<td scope="col">' . $key->NomBodega . '</td>
				<td scope="col" class="text-center">' . $estadoCotizacion . '</td>
				<td scope="col">' . $key->fecha_creacion . '</td>
				' . $btnOpcion . '
				' . $btnEnviar . '
				' . $btnEstado . '
			</tr>';
		}
	}
}
