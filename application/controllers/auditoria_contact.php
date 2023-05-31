<?php

use Mpdf\Writer\BackgroundWriter;
use PhpOffice\PhpSpreadsheet\Worksheet\PageMargins;

/**
 * Controlador del modulo auditoria_contact
 * 1=SI 2=NO 3=N/A
 */
class auditoria_contact extends CI_Controller
{
	public function index()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('A_contact');

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

			$getAgentes = $this->A_contact->getAllUserAgente();
			$cantPreguntas = $this->A_contact->getCantPreguntas();

			if ($perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 20 || $perfil_user->id_perfil == 54) {
				//echo $id_usu;
				$arr_user = array(
					'userdata' => $userinfo,
					'menus' => $allmenus,
					'pass' => $pass,
					'id_usu' => $id_usu,
					'agentes' => $getAgentes,
					'cantPreguntas' => $cantPreguntas
				);
				//abrimos la vista
				$this->load->view('Auditoria_contact/index', $arr_user);
			} else {
				header('Location:' . base_url() . '');
				exit;
			}
		}
	}
	/* Crear auditoria para X asesor conctac Center */
	public function crearAuditoria()
	{
		$this->load->model('A_contact');
		$nitAgente = $this->input->POST('nitAgente');
		$nitEncargado = $this->session->userdata('user');

		$id_auditoria = $this->A_contact->insertAuditoria($nitAgente,$nitEncargado);
		if ($id_auditoria > 0) {
			echo $id_auditoria;
		} else {
			echo 0;
		}
	}
	/* Cargar formulario para la Vista previa */
	public function CargarFormAudVistaPrevia()
	{
		//Cargamos el modelo
		$this->load->model('A_contact');
		$indicador = $this->A_contact->getIndicador();
		$opcion = $this->input->POST('opcion');
		$btnResp = "";
		if ($opcion != "") {
			$btnResp = "disabled";
		}

		echo '<table class="table">
			<thead>
				<tr>
					<th width="30%" class="text-center">Indicador</th>
					<th width="50%" class="text-center">Item</th>
					<th width="10%" class="text-center">Si</th>
					<th width="10%" class="text-center">No</th>
					<th width="10%" class="text-center">No Aplica</th>
				</tr>
			</thead>
			<tbody>';
		$cantPreguntas = 0;
		$bandera = 0;
		foreach ($indicador->result() as $ind) {
			$item = $this->A_contact->getItemHabilitados($ind->id_indicador);
			
			if ($ind->estado == 2 ) {
				$numFilas = $item->num_rows();
				if($numFilas > 0){
					$cantPreguntas += $numFilas;
				}else{
					continue;
				}
							
				echo
					'<tr>
						<th width="30%" style="vertical-align: middle; text-align:center" rowspan="' . ($numFilas + 1) . '">' . $ind->nombres . '</th>
					</tr>';
				if (COUNT($item->result()) > 0) {
					foreach ($item->result() as $item) {
						echo '<tr name="fila' . $bandera . '">
								<td width="50%">' . $item->concepto . '</td>
		
								<td width="10%" class="text-center">
									<input ' . $btnResp . ' class="si" type="radio" name="item' . $item->id_item . '" id="inlineRadio1" value="' . ($ind->puntuacion / ($numFilas)) . '" onclick="updateResp(' . $item->id_item . ',1);">
								</td>
								<td width="10%" class="text-center">
									<input ' . $btnResp . ' class="no" type="radio" name="item' . $item->id_item . '" id="inlineRadio1" value="0"  onclick="updateResp(' . $item->id_item . ',2);">
								</td>
								<td width="10%" class="text-center">
									<input ' . $btnResp . ' class="noAplica" type="radio" name="item' . $item->id_item . '" id="inlineRadio1" value="0"  onclick="updateResp(' . $item->id_item . ',3);">
								</td>
							</tr>';
						$bandera++;
					}
				}

			}
		}

		echo '
		<input type="hidden" name="cantPreguntas" id="cantPreguntas" value="' . $cantPreguntas . '">
		<input type="hidden" name="id_auditoria" id="id_auditoria" value="">
		</tbody>
		</table>';
	}
	public function CargarFormAud()
	{
		//Cargamos el modelo
		$this->load->model('A_contact');
		$indicador = $this->A_contact->getIndicador();
		$opcion = $this->input->POST('opcion');
		$btnResp = "";
		if ($opcion != "") {
			$btnResp = "disabled";
		}

		echo '<table class="table">
			<thead>
				<tr>
					<th width="30%" class="text-center">Indicador</th>
					<th width="50%" class="text-center">Item</th>
					<th width="10%" class="text-center">Si</th>
					<th width="10%" class="text-center">No</th>
					<th width="10%" class="text-center">No Aplica</th>
				</tr>
			</thead>
			<tbody>';
		$cantPreguntas = 0;
		$bandera = 0;
		foreach ($indicador->result() as $ind) {

			$item = $this->A_contact->getItemHabilitados($ind->id_indicador);
			$numFilas = $item->num_rows();
			if ($ind->estado == 1) {
				continue;
			}elseif ($numFilas == 0){
				continue;
			}else {
				$cantPreguntas += $numFilas;
			}
			echo
				'<tr>
					<th width="30%" style="vertical-align: middle; text-align:center" rowspan="' . ($numFilas + 1) . '">' . $ind->nombres . '</th>
			    </tr>';
			if (COUNT($item->result()) > 0) {
				foreach ($item->result() as $item) {
					echo '<tr name="fila' . $bandera . '">
							<td width="50%">' . $item->concepto . '</td>
	
							<td width="10%" class="text-center">
								<input ' . $btnResp . ' class="si" type="radio" name="item' . $item->id_item . '" id="inlineRadio1" value="' . ($ind->puntuacion / ($numFilas)) . '" onclick="updateResp(' . $item->id_item . ',1);">
							</td>
							<td width="10%" class="text-center">
								<input ' . $btnResp . ' class="no" type="radio" name="item' . $item->id_item . '" id="inlineRadio1" value="0"  onclick="updateResp(' . $item->id_item . ',2);">
							</td>
							<td width="10%" class="text-center">
								<input ' . $btnResp . ' class="noAplica" type="radio" name="item' . $item->id_item . '" id="inlineRadio1" value="0"  onclick="updateResp(' . $item->id_item . ',3);">
							</td>
						</tr>';
					$bandera++;
				}
			}
		}

		echo '
		<input type="hidden" name="cantPreguntas" id="cantPreguntas" value="' . $cantPreguntas . '">
		<input type="hidden" name="id_auditoria" id="id_auditoria" value="">
		</tbody>
		</table>';
	}

	public function updateRespuesta()
	{
		$this->load->model('A_contact');
		$id_auditoria = $this->input->POST('id_auditoria');
		$item = $this->input->POST('item');
		$opt = $this->input->POST('opt');
		$id_item = "item_$item";

		$updateResp = $this->A_contact->updateResp($id_auditoria, $id_item, $opt);

		if ($updateResp) {
			echo "OK";
		} else {
			echo "ERROR";
		}
	}
	public function finalizarAuditoria()
	{
		$this->load->model('A_contact');
		$id_auditoria = $this->input->POST('id_auditoria');
		$obsAuditor = $this->input->POST('obsAuditor');


		// Despues de agregar todas las respuestas, se debe guardar la puntuación obtenida...

		$data = $this->A_contact->getAuditoriaId($id_auditoria);

		$indicador = $this->A_contact->getIndicador();
		$sumaPuntos = 0;
		foreach ($indicador->result() as $ind) {
			if($ind->estado == 1){
				continue;
			}
			$item = $this->A_contact->getItemHabilitados($ind->id_indicador);
			$puntuacion = $ind->puntuacion;

			$sumaSi = 0;
			$sumaNo = 0;
			$sumaNA = 0;
			$cant_item = $item->num_rows();
			foreach ($item->result() as $item) {


				$campo = "item_" . $item->id_item;
				$resp = $data->row()->$campo;
				if ($resp == 1) {
					$sumaSi++;
				} else if ($resp == 2) {
					$sumaNo++;
				} else if ($resp == 3) {
					$sumaNA++;
				}
				//Puntuacion -> 5
				//Cantidad de Item -> 3
				//suma de no aplica -> 1
				//Suma de SI -> 1
				//Punto por pregunta es igual a la puntuacion dividad por la resta de cantidad de itemp menos la suma de no aplicas..

			}

			if ($sumaNA == $cant_item) {
				$sumaPuntos += $puntuacion;
			} else {
				$dividir = ($cant_item - $sumaNA);
				$puntosXInd = ($puntuacion / $dividir);
				$sumaPuntos += ($puntosXInd * $sumaSi);
			}
		}

		if ($this->A_contact->finalizarAuditoria($id_auditoria, $sumaPuntos, $obsAuditor)) {
			echo $id_auditoria;
		} else {
			echo 0;
		}
	}
	public function listAuditoria()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('A_contact');

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

			$getAgentes = $this->A_contact->getAllUserAgente();
			//echo $id_usu;
			$arr_user = array(
				'usu' => $usu,
				'userdata' => $userinfo,
				'menus' => $allmenus,
				'pass' => $pass,
				'id_usu' => $id_usu,
				'agentes' => $getAgentes,
			);
			//abrimos la vista segun el perfil del usuario
			if ($perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 20 || $perfil_user->id_perfil == 54) {
				$this->load->view('Auditoria_contact/auditorias', $arr_user);
			} else {
				$this->load->view('Auditoria_contact/auditoriasAgente', $arr_user);
			}
		}
	}
	/* Cargar información de las auditorias realizadas a los agentes Vista Admin */
	public function CargarAuditoriaAgentes()
	{
		$this->load->model('A_contact');
		$nitAgente = $this->input->POST('nitAgente');
		if ($nitAgente != "") {
			$where = "WHERE ae.nit_agente = $nitAgente";
		} else {
			$where = "";
		}

		$data = $this->A_contact->getAuditoriaAgentesAll($where);
		if ($data->num_rows() > 0) {

			foreach ($data->result() as $key) {
				if ($key->fecha_finalizacion == "") {
					$color = "style='background-color: lightgoldenrodyellow'";
					$btnEditar = '<button class="btn btn-warning" onclick="EditarAuditoria(' . $key->id_auditoria . ')">Editar</button>';
					$btnVer = '';
					$btnEnviar = 'disabled';
				} else {
					$btnEnviar = '';
					$color = "style='background-color: lightblue'";
					$btnEditar = '';
					$btnVer = '<button class="btn btn-success" onclick="VerAuditoria(' . $key->id_auditoria . ')">Ver</button>';
				}
				echo '<tr ' . $color . '>
					<td class="text-center" scope="col">' . $key->id_auditoria . '</td>
					<td class="text-center" scope="col">' . $key->nit_agente . '</td>
					<td class="text-center" scope="col">' . $key->nombres . '</td>
					<td class="text-center" scope="col">' . $key->puntuacion . '</td>
					<td class="text-center" scope="col">' . $key->fecha_creacion . '</td>
					<td class="text-center" scope="col">' . $key->fecha_finalizacion . '</td>
					<td class="text-center" scope="col">
					' . $btnEditar . '
					' . $btnVer . '
					</td>
					<td class="text-center" scope="col"><button '.$btnEnviar.' class="btn btn-info" onclick="sendEmailAuditoria(' . $key->id_auditoria . ')"><i class="fas fa-paper-plane"> Enviar</i></button></td>
					</tr>';
			}
		} else {
		}
	}
	/* Cargar información de las auditorias realizadas a los agentes vista por agente */
	public function CargarAuditoriaAgentesXusu()
	{
		$this->load->model('A_contact');
		$nitAgente = $this->input->POST('nitAgente');
		$where = "WHERE ae.nit_agente = $nitAgente";

		$data = $this->A_contact->getAuditoriaAgentesAll($where);
		if ($data->num_rows() > 0) {

			foreach ($data->result() as $key) {
				if ($key->fecha_finalizacion != "") {
					$color = "style='background-color: lightblue'";
					if ($key->compromiso == "") {
						$color = "style='background-color: lightyellow'";
					}

					echo '<tr ' . $color . '>
					<td class="text-center" scope="col">' . $key->id_auditoria . '</td>
					<td class="text-center" scope="col">' . $key->nit_agente . '</td>
					<td class="text-center" scope="col">' . $key->nombres . '</td>
					<td class="text-center" scope="col">' . $key->puntuacion . '</td>
					<td class="text-center" scope="col">' . $key->fecha_creacion . '</td>
					<td class="text-center" scope="col">' . $key->fecha_finalizacion . '</td>
					<td class="text-center" scope="col">
					<button class="btn btn-success" onclick="VerAuditoria(' . $key->id_auditoria . ')">Ver</button>
					</td>
					</tr>';
				}
			}
		}
	}
	/* Funcion para cargar información sobre la auditoria x Agente Vista Agente */
	public function verAuditoriaAgenteXusu()
	{
		$this->load->model('A_contact');
		$id_auditoria = $this->input->POST('id_auditoria');

		$data = $this->A_contact->getAuditoriaId($id_auditoria);

		if ($data->num_rows() > 0) {
			$indicador = $this->A_contact->getIndicador();

			echo '<div class="row pb-2">
					<div class="col-sm-6">
						<label>Nombre:</label>
						<input class="form-control" type="text" id="nameAgente" name="nameAgente" value="' . $data->row()->nombres . '" readonly>
					</div>
					<div class="col-sm-6">
						<label>Puntuación:</label>
						<input class="form-control" type="text" id="puntosA" name="puntosA" value="' . $data->row()->puntuacion . '" readonly>
					</div>
			</div>
			';

			$files = $this->A_contact->getAllFilesAuditoriaId($id_auditoria);
			$btn = "";
			$readOnlyComp = "readonly";
			if ($data->row()->compromiso == "") {
				$btn = '<button style="float: right;" class="btn btn-info mt-2" onclick="aggCompromiso(' . $id_auditoria . ');">Agregar compromiso</button>';
				$readOnlyComp = "";
			}


			echo '<div class="row pb-2">
				<div class="col-6">
					<div class="row">
						<div class="col">
							<label>Compromiso:</label>
							<textarea ' . $readOnlyComp . '  id="compromiso" name="compromiso" class="form-control">' . $data->row()->compromiso . '</textarea>
							' . $btn . '
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label>Observaciones:</label>
							<textarea placeholder="" readonly  id="obsAuditoria" name="obsAuditoria" class="form-control">' . $data->row()->observaciones . '</textarea>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<label>Descargar archivos:</label>
					<ol>';
			if (count($files->result()) > 0) {
				foreach ($files->result() as $file) {
					echo '<li><a Target="_blank" href="' . base_url() . 'public/auditoria_contact/' . $file->url_file . '">' . $file->url_file . '</a></li>';
				}
			}
			echo '</ol>
				</div>';


			echo '<table class="table">
			<thead>
				<tr>
					<th width="30%" class="text-center">Indicador</th>
					<th width="50%" class="text-center">Item</th>
					<th width="10%" class="text-center">Si</th>
					<th width="10%" class="text-center">No</th>
					<th width="10%" class="text-center">No Aplica</th>
				</tr>
			</thead>
			<tbody>';
			$cantPreguntas = 0;
			$bandera = 0;
			foreach ($indicador->result() as $ind) {

				$item = $this->A_contact->getItem($ind->id_indicador);
				$numFilas = $item->num_rows();
				if ($numFilas <= 0) {
					continue; //Saltar la iteración si el indicador no cuenta con Items
				}
				$cantPreguntas += $numFilas;

				echo
				'<tr>
					<th width="30%" style="vertical-align: middle; text-align:center" rowspan="' . ($numFilas + 1) . '">' . $ind->nombres . '</th>
			    </tr>';

				foreach ($item->result() as $item) {

					$campo = "item_" . $item->id_item;
					/* if($data->row()->$campo == NULL){
						continue;//Saltar iteración si los item son NULL, esto indica que a la hora de realizar la auditoria no se encontraba este item, por lo tanto no debería ser tomado en cuenta...
					} */
					switch ($data->row()->$campo) {
						case 1:
							$checkedSi = "checked";
							$checkedNo = "";
							$checkedNA = "";
							$disabledSI = "";
							$disabledNO = "disabled";
							$disabledNA = "disabled";
							break;
						case 2:

							$checkedNo = "checked";
							$checkedNA = "";
							$checkedSi = "";
							$disabledNO = "";
							$disabledSI = "disabled";
							$disabledNA = "disabled";
							break;
						case 3:
							$checkedNA = "checked";
							$checkedNo = "";
							$checkedSi = "";
							$disabledNA = "";
							$disabledSI = "disabled";
							$disabledNO = "disabled";
							break;

						case ("" || 0):
							$checkedNA = "";
							$checkedNo = "";
							$checkedSi = "";
							$disabledSI = "disabled";
							$disabledNO = "disabled";
							$disabledNA = "disabled";
							break;
					}
					$updateRespSI = "";
					$updateRespNO = "";
					$updateRespNA = "";
					$classSI = "";
					$classNO = "";
					$classNA = "";
					echo '<tr name="fila' . $bandera . '">
						<td width="50%">' . $item->concepto . '</td>

						<td width="10%" class="text-center">
							<input ' . $disabledSI . '  ' . $checkedSi . '  ' . $classSI . ' type="radio" name="item' . $item->id_item . '" id="inlineRadio1" value="' . ($ind->puntuacion / ($numFilas)) . '" ' . $updateRespSI . '>
						</td>
						<td width="10%" class="text-center">
							<input ' . $disabledNO . ' ' . $checkedNo . ' ' . $classNO . ' type="radio" name="item' . $item->id_item . '" id="inlineRadio1" value="0"  ' . $updateRespNO . '>
						</td>
						<td width="10%" class="text-center">
							<input ' . $disabledNA . '  ' . $checkedNA . ' ' . $classNA . '  type="radio" name="item' . $item->id_item . '" id="inlineRadio1" value="0"  ' . $updateRespNA . '>
						</td>
					</tr>';
					$bandera++;
				}
			}

			echo '
			</tbody>
			</table>';
		} else {
		}
	}
	/* Funcion para cargar información sobre la auditoria x Agente Admin*/
	public function verAuditoriaAgente()
	{
		$this->load->model('A_contact');
		$id_auditoria = $this->input->POST('id_auditoria');

		$data = $this->A_contact->getAuditoriaId($id_auditoria);

		if ($data->num_rows() > 0) {
			$indicador = $this->A_contact->getIndicador();

			echo '<div class="row pb-2">
					<div class="col-sm-6">
						<label>Nombre:</label>
						<input class="form-control" type="text" id="nameAgente" name="nameAgente" value="' . $data->row()->nombres . '" readonly>
					</div>
					<div class="col-sm-6">
						<label>Puntuación:</label>
						<input class="form-control" type="text" id="puntosA" name="puntosA" value="' . $data->row()->puntuacion . '" readonly>
					</div>
			</div>
			';

			$files = $this->A_contact->getAllFilesAuditoriaId($id_auditoria);

			echo '<div class="row pb-2">
						<div class="col-6">
							<div class="row pl-2">
								<label>Compromiso:</label>
								<textarea placeholder="" readonly  id="compromiso" name="compromiso" class="form-control">' . $data->row()->compromiso . '</textarea>
							</div>
							<div class="row pl-2">
								<label>Observaciones:</label>
								<textarea placeholder="" readonly  id="obsAuditoria" name="obsAuditoria" class="form-control">' . $data->row()->observaciones . '</textarea>
							</div>
						</div>
						<div class="col-6">
							<label>Descargar archivos:</label>
							<ol>';
			if (count($files->result()) > 0) {
				foreach ($files->result() as $file) {
					echo '<li><a Target="_blank" href="' . base_url() . 'public/auditoria_contact/' . $file->url_file . '">' . $file->url_file . '</a></li>';
				}
			}
			echo '		</ol>
							<button style="float:right;" type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#cargarArchivoAuditoriaLibre">Agregar archivos</button>
						</div>
					</div>';



			echo '<table class="table">
			<thead>
				<tr>
					<th width="30%" class="text-center">Indicador</th>
					<th width="50%" class="text-center">Item</th>
					<th width="10%" class="text-center">Si</th>
					<th width="10%" class="text-center">No</th>
					<th width="10%" class="text-center">No Aplica</th>
				</tr>
			</thead>
			<tbody>';
			$cantPreguntas = 0;
			$bandera = 0;
			foreach ($indicador->result() as $ind) {

				$item = $this->A_contact->getItem($ind->id_indicador);
				$numFilas = $item->num_rows();
				if ($numFilas <= 0) {
					continue; //Saltar la iteración si el indicador no cuenta con Items
				}
				$cantPreguntas += $numFilas;

				echo
				'<tr>
					<th width="30%" style="vertical-align: middle; text-align:center" rowspan="' . ($numFilas + 1) . '">' . $ind->nombres . '</th>
			    </tr>';

				foreach ($item->result() as $item) {

					$campo = "item_" . $item->id_item;
					/* if($data->row()->$campo == NULL){
						continue;//Saltar iteración si los item son NULL, esto indica que a la hora de realizar la auditoria no se encontraba este item, por lo tanto no debería ser tomado en cuenta...
					} */
					switch ($data->row()->$campo) {
						case 1:
							$checkedSi = "checked";
							$checkedNo = "";
							$checkedNA = "";
							$disabledSI = "";
							$disabledNO = "disabled";
							$disabledNA = "disabled";
							break;
						case 2:

							$checkedNo = "checked";
							$checkedNA = "";
							$checkedSi = "";
							$disabledNO = "";
							$disabledSI = "disabled";
							$disabledNA = "disabled";
							break;
						case 3:
							$checkedNA = "checked";
							$checkedNo = "";
							$checkedSi = "";
							$disabledNA = "";
							$disabledSI = "disabled";
							$disabledNO = "disabled";
							break;

						case ("" || 0):
							$checkedNA = "";
							$checkedNo = "";
							$checkedSi = "";
							$disabledSI = "disabled";
							$disabledNO = "disabled";
							$disabledNA = "disabled";
							break;
					}
					$updateRespSI = "";
					$updateRespNO = "";
					$updateRespNA = "";
					$inputHiddenId = '<input type="hidden" name="id_auditoria" id="id_auditoria" value="' . $id_auditoria . '">';
					$classSI = "";
					$classNO = "";
					$classNA = "";
					//En la vista de ver no se deberia realizar actualizaciones
					/* if ($data->row()->puntuacion == "") {
						$updateRespSI = 'onclick="updateResp(' . $item->id_item . ',1);"';
						$updateRespNO = 'onclick="updateResp(' . $item->id_item . ',2);"';
						$updateRespNA = 'onclick="updateResp(' . $item->id_item . ',3);"';
						$disabledSI = "";
						$disabledNO = "";
						$disabledNA = "";

						$classSI = 'class="si"';
						$classNO = 'class="no"';
						$classNA = 'class="noAplica"';
					} */
					echo '<tr name="fila' . $bandera . '">
						<td width="50%">' . $item->concepto . '</td>

						<td width="10%" class="text-center">
							<input ' . $disabledSI . '  ' . $checkedSi . '  ' . $classSI . ' type="radio" name="item' . $item->id_item . '" id="inlineRadio1" value="' . ($ind->puntuacion / ($numFilas)) . '" ' . $updateRespSI . '>
						</td>
						<td width="10%" class="text-center">
							<input ' . $disabledNO . ' ' . $checkedNo . ' ' . $classNO . ' type="radio" name="item' . $item->id_item . '" id="inlineRadio1" value="0"  ' . $updateRespNO . '>
						</td>
						<td width="10%" class="text-center">
							<input ' . $disabledNA . '  ' . $checkedNA . ' ' . $classNA . '  type="radio" name="item' . $item->id_item . '" id="inlineRadio1" value="0"  ' . $updateRespNA . '>
						</td>
					</tr>';
					$bandera++;
				}
			}

			echo '<input type="hidden" name="cantPreguntas" id="cantPreguntas" value="' . $cantPreguntas . '">
			' . $inputHiddenId . '
			</tbody>
			</table>';
		} else {
		}
	}
	/* Funcion para cargar información sobre la auditoria x Agente */
	public function editarAuditoriaAgente()
	{
		$this->load->model('A_contact');
		$id_auditoria = $this->input->POST('id_auditoria');

		$data = $this->A_contact->getAuditoriaId($id_auditoria);

		if ($data->num_rows() > 0) {
			$indicador = $this->A_contact->getIndicador();
			echo '<div class="row pb-2">
					<div class="col-sm-6">
						<label>Nombre:</label>
						<input class="form-control" type="text" id="nameAgente" name="nameAgente" value="' . $data->row()->nombres . '" readonly>
					</div>
					<div class="col-sm-6">
						<label>Puntuación:</label>
						<input class="form-control" type="text" id="puntosA" name="puntosA" value="' . $data->row()->puntuacion . '" readonly>
					</div>
			</div>
			';

			$files = $this->A_contact->getAllFilesAuditoriaId($id_auditoria);



			echo '<div class="row pb-2">
						<div class="col-6">
							<div class="row pl-2">
								<label>Compromiso:</label>
								<textarea placeholder="" readonly  id="compromiso" name="compromiso" class="form-control">' . $data->row()->compromiso . '</textarea>
							</div>
							<div class="row pl-2">
								<label>Observaciones:</label>
								<textarea placeholder="" readonly  id="obsAuditoria" name="obsAuditoria" class="form-control">' . $data->row()->observaciones . '</textarea>
							</div>
						</div>
						<div class="col-6">
							<label>Descargar archivos:</label>
							<ol>';
			if (count($files->result()) > 0) {
				foreach ($files->result() as $file) {
					echo '<li><a Target="_blank" href="' . base_url() . 'public/auditoria_contact/' . $file->url_file . '">' . $file->url_file . '</a></li>';
				}
			}
			echo '		</ol>
							<button style="float:right;" type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#cargarArchivoAuditoriaLibre">Agregar archivos</button>
						</div>
					</div>';


			echo '<table class="table">
			<thead>
				<tr>
					<th width="30%" class="text-center">Indicador</th>
					<th width="50%" class="text-center">Item</th>
					<th width="10%" class="text-center">Si</th>
					<th width="10%" class="text-center">No</th>
					<th width="10%" class="text-center">No Aplica</th>
				</tr>
			</thead>
			<tbody>';
			$cantPreguntas = 0;
			$bandera = 0;
			foreach ($indicador->result() as $ind) {
				if ($ind->estado == 1) {
					continue;
				}
				$item = $this->A_contact->getItemHabilitados($ind->id_indicador);
				$numFilas = $item->num_rows();
				$cantPreguntas += $numFilas;
				echo
				'<tr>
					<th width="30%" style="vertical-align: middle; text-align:center" rowspan="' . ($numFilas + 1) . '">' . $ind->nombres . '</th>
			    </tr>';

				foreach ($item->result() as $item) {
					if ($item->estado == 1) {
						continue;
					}
					$campo = "item_" . $item->id_item;
					switch ($data->row()->$campo) {
						case 1:
							$checkedSi = "checked";
							$checkedNo = "";
							$checkedNA = "";
							$disabledSI = "";
							$disabledNO = "disabled";
							$disabledNA = "disabled";
							break;
						case 2:

							$checkedNo = "checked";
							$checkedNA = "";
							$checkedSi = "";
							$disabledNO = "";
							$disabledSI = "disabled";
							$disabledNA = "disabled";
							break;
						case 3:
							$checkedNA = "checked";
							$checkedNo = "";
							$checkedSi = "";
							$disabledNA = "";
							$disabledSI = "disabled";
							$disabledNO = "disabled";
							break;

						case ("" || 0):
							$checkedNA = "";
							$checkedNo = "";
							$checkedSi = "";
							$disabledSI = "";
							$disabledNO = "";
							$disabledNA = "";
							break;
					}
					$updateRespSI = "";
					$updateRespNO = "";
					$updateRespNA = "";
					$inputHiddenId = '<input type="hidden" name="id_auditoria" id="id_auditoria" value="' . $id_auditoria . '">';
					$classSI = "";
					$classNO = "";
					$classNA = "";
					if ($data->row()->puntuacion == "") {
						$updateRespSI = 'onclick="updateResp(' . $item->id_item . ',1);"';
						$updateRespNO = 'onclick="updateResp(' . $item->id_item . ',2);"';
						$updateRespNA = 'onclick="updateResp(' . $item->id_item . ',3);"';
						$disabledSI = "";
						$disabledNO = "";
						$disabledNA = "";

						$classSI = 'class="si"';
						$classNO = 'class="no"';
						$classNA = 'class="noAplica"';
					}
					echo '<tr name="fila' . $bandera . '">
						<td width="50%">' . $item->concepto . '</td>

						<td width="10%" class="text-center">
							<input ' . $disabledSI . '  ' . $checkedSi . '  ' . $classSI . ' type="radio" name="item' . $item->id_item . '" id="inlineRadio1" value="' . ($ind->puntuacion / ($numFilas)) . '" ' . $updateRespSI . '>
						</td>
						<td width="10%" class="text-center">
							<input ' . $disabledNO . ' ' . $checkedNo . ' ' . $classNO . ' type="radio" name="item' . $item->id_item . '" id="inlineRadio1" value="0"  ' . $updateRespNO . '>
						</td>
						<td width="10%" class="text-center">
							<input ' . $disabledNA . '  ' . $checkedNA . ' ' . $classNA . '  type="radio" name="item' . $item->id_item . '" id="inlineRadio1" value="0"  ' . $updateRespNA . '>
						</td>
					</tr>';
					$bandera++;
				}
			}

			echo '<input type="hidden" name="cantPreguntas" id="cantPreguntas" value="' . $cantPreguntas . '">
			' . $inputHiddenId . '
			</tbody>
			</table>';
		} else {
		}
	}
	/* Funcion para cargar archivos a la auditoria X ID */
	public function upload_data()
	{
		$this->load->model('A_contact');
		$id_auditoria = $this->input->POST('id_auditoria');
		$cantFiles = $this->input->POST('cantArchivos');

		$config['upload_path'] = './public/auditoria_contact';
		$config['allowed_types'] = '*';
		$config['max_size'] = '0';
		$this->load->library('upload', $config);
		$cantSaveFile = array();
		$cantNotSaveFile = array();

		for ($i = 0; $i < $cantFiles; $i++) {

			$this->upload->do_upload('file-' . $i);
			$datas = $this->upload->data();
			$url_file = $datas['file_name'];
			if ($this->A_contact->addFilesAuditoria($id_auditoria, $url_file)) {
				$cantSaveFile[] = "$url_file";
			} else {
				$cantNotSaveFile[] = "$url_file";
			}
		}
		$arr_user = array('cantSaveFile' => $cantSaveFile, 'cantNotSaveFile' => $cantNotSaveFile);

		if ($cantSaveFile > 0) {
			echo json_encode($arr_user);
		} else {
			echo 0;
		}
	}

	public function configuracion()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('A_contact');

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
			$indicador = $this->A_contact->getIndicador();
			//echo $id_usu;
			if ($perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 20 || $perfil_user->id_perfil == 54) {
				$arr_user = array(
					'userdata' => $userinfo,
					'menus' => $allmenus,
					'pass' => $pass,
					'id_usu' => $id_usu,
					'indicadores' => $indicador,
				);
				//abrimos la vista
				$this->load->view('Auditoria_contact/conf_auditoria', $arr_user);
			} else {
				header('Location:' . base_url() . '');
				exit;
			}
		}
	}
	public function cargarIndicadores()
	{
		$this->load->model('A_contact');
		$indicadores = $this->A_contact->getIndicador();
		echo '<table class="table" id="tablaprueba">
					<thead>
						<th scope="col">#</th>
						<th scope="col">Indicador</th>
						<th scope="col">Puntuación</th>
					</thead>
					<tbody id="tablapruebaBody">';
		$sumaPuntos = 0;
		$bandera = 0;
		foreach ($indicadores->result() as  $ind) {
			
			//Estado del indicador 1 Inhabilitado y 2 Habilitado
			if ($ind->estado == 2) {
				$estado = 'Inhabilitar';
				$btnEstado = 'btn-warning';
				$estadoInd = 1;
				$sumaPuntos += $ind->puntuacion;
			} else {
				$estado = 'Habilitar';
				$btnEstado = 'btn-success';
				$estadoInd = 2;
			}

			echo '<tr class="filas">
					<td scope="col">' . $ind->id_indicador . '</td>
					<td scope="col">' . $ind->nombres . '</td>
					<td scope="col"><input disabled class="indPuntos" type="number" id="ind_' . $ind->id_indicador . '" name="indPuntos" value="' . $ind->puntuacion . '" onchange="sumarPuntos(' . $ind->puntuacion . ',this.id);"></td>
					<!--<td scope="col" class="btnIndHabilitar"><button class="btn ' . $btnEstado . '"  type="button" id="estado_' . $ind->id_indicador . '" name="btnEstado" value="' . $ind->estado . '" onclick="validateCantAuditorias(' . $ind->id_indicador . ',' . $estadoInd . ');">' . $estado . '</td>-->
					<td scope="col" class="btnIndHabilitar"><button class="btn ' . $btnEstado . '"  type="button" id="estado_' . $ind->id_indicador . '" name="btnEstado" value="' . $ind->estado . '" onclick="cambiarEstado(' . $ind->id_indicador . ',' . $estadoInd . ');">' . $estado . '</td>
					</tr>';
			$bandera++;
		}

		echo 	'
					</tbody>
					<tfooter>
						<tr>
							<th colspan="2" style="text-align: end;">Puntuación</th>
							<td>
								<input disabled id="sumaPuntos" type="number" value="' . $sumaPuntos . '">
								<input id="cantIndicadores" type="hidden" value="' . $bandera . '">
							</td>
						</tr>
					</tfooter>
				</table>';
	}

	public function cargarIndicadoresPuntos()
	{
		$this->load->model('A_contact');
		$indicador = $this->input->POST('id_indicador');
		$estado = $this->input->POST('estado');



		$indicadores = $this->A_contact->getIndicador();
		echo '<table class="table" id="tablaprueba">
					<thead>
						<th scope="col">#</th>
						<th scope="col">Indicador</th>
						<th scope="col">Puntuación</th>
					</thead>
					<tbody id="tablapruebaBody">';
		$sumaPuntos = 0;
		$bandera = 0;
		foreach ($indicadores->result() as  $ind) {
			
			//Estado del indicador 1 Inhabilitado y 2 Habilitado
			if ($ind->id_indicador == $indicador) {
				if ($estado == 1) {
					echo '<tr class="">
					<td scope="col">' . $ind->id_indicador . '</td>
					<td scope="col">' . $ind->nombres . '</td>
					<td scope="col"><input disabled class="indPuntos" type="number" id="ind_' . $ind->id_indicador . '" name="indPuntos" value="0" onchange="sumarPuntos(' . $ind->puntuacion . ',this.id);"></td>
					</tr>';
				}elseif($estado == 2){
					echo '<tr class="filas">
					<td scope="col">' . $ind->id_indicador . '</td>
					<td scope="col">' . $ind->nombres . '</td>
					<td scope="col"><input class="indPuntos" type="number" id="ind_' . $ind->id_indicador . '" name="indPuntos" value="' . $ind->puntuacion . '" onchange="sumarPuntos(' . $ind->puntuacion . ',this.id);"></td>
					</tr>';
					$sumaPuntos += $ind->puntuacion;
				}
			}else {
				echo '<tr class="filas">
					<td scope="col">' . $ind->id_indicador . '</td>
					<td scope="col">' . $ind->nombres . '</td>
					<td scope="col"><input class="indPuntos" type="number" id="ind_' . $ind->id_indicador . '" name="indPuntos" value="' . $ind->puntuacion . '" onchange="sumarPuntos(' . $ind->puntuacion . ',this.id);"></td>
					</tr>';
					$sumaPuntos += $ind->puntuacion;
			}
			
			$bandera++;
		}

		echo 	'
					</tbody>
					<tfooter>
						<tr>
							<th colspan="2" style="text-align: end;">Puntuación</th>
							<td>
								<input disabled id="sumaPuntos" type="number" value="' . $sumaPuntos . '">
								<input id="cantIndicadores" type="hidden" value="' . $bandera . '">
								<input id="estadoIndCambiar" type="hidden" value="' . $estado . '">
								<input id="idIndicadorCambiar" type="hidden" value="' . $indicador . '">
							</td>
						</tr>
					</tfooter>
				</table>';
	}

	public function updateIndEstado()
	{
		$this->load->model('A_contact');
		$arrayIndicadores = $this->input->POST('datosInd');
		$idIndicador = $this->input->POST('idIndicador');
		$estado = $this->input->POST('estado');
		$arrayIndicadores = explode(",", $arrayIndicadores);

		for ($i = 0; $i < COUNT($arrayIndicadores); $i += 3) {

			$this->A_contact->updateIndicadores($arrayIndicadores[$i], $arrayIndicadores[$i + 2]);
		}

		if ($this->A_contact->estadoIndicador($idIndicador, $estado)) {

			echo 1;

		} else {

			echo 0;
		}
	}

	public function updateInd()
	{
		$this->load->model('A_contact');
		$arrayIndicadores = $this->input->POST('datosInd');
		$newInd = $this->input->POST('newInd');
		$newIndPuntos = $this->input->POST('newIndPuntos');
		$arrayIndicadores = explode(",", $arrayIndicadores);

		for ($i = 0; $i < COUNT($arrayIndicadores); $i += 3) {

			$this->A_contact->updateIndicadores($arrayIndicadores[$i], $arrayIndicadores[$i + 2]);
		}

		if ($this->A_contact->insertIndicador($newInd, $newIndPuntos)) {

			echo "OK";
		} else {

			echo "ERROR";
		}
	}
	/* Funcion para realizar el cambio de estado del indicador */
	public function estadoIndicador()
	{
		$this->load->model('A_contact');
		$id_indicador = $this->input->POST('id_indicador');
		$estado = $this->input->POST('estado');

		$getCant = $this->A_contact->countAuditorias();
	
		if (!$getCant->row()->cantidad > 0){
			if ($this->A_contact->estadoIndicador($id_indicador, $estado)) {
				echo $estado;
			} else {
	
				echo 0;
			}
		}else{
			echo 3;//No se puede realizar el cambio de estado, ya que hay auditorias pendientes por finalizar
		}
		
	}
	/* Cargar Items por indicadores */
	public function getItemXind()
	{
		$this->load->model('A_contact');
		$id_indicador = $this->input->POST('id_indicador');
		$item = $this->A_contact->getItem($id_indicador);
		if (COUNT($item->result()) > 0) {

			foreach ($item->result() as $item) {
				if ($item->estado == 2) {
					$estado = 'Inhabilitar';
					$estadoItem = 1;
					$btnEstadoItem = "btn-warning";
				} else {
					$estado = 'Habilitar';
					$estadoItem = 2;
					$btnEstadoItem = "btn-success";
				}
				echo '<tr>
						<td>' . $item->concepto . '</td>
						<td>
						<button type="button" class="btn ' . $btnEstadoItem . '" id="item_' . $item->id_item . '" name="btnItemEstado" onclick="cambiarEstadoItem(' . $item->id_item . ',' . $estadoItem . ');">' . $estado . '</button>
						</td>
					</tr>';
			}
		} else {
			echo '<tr>
					<td class="text-center" colspan="2">Este indicador al momento no cuenta con items</td>
				</tr>';
		}
	}
	//Funcion para agregar nuevos items
	public function addItemXind()
	{
		//Cargamos el modelo
		$this->load->model('A_contact');
		$id_indicador = $this->input->POST('id_indicador');
		$concepto = $this->input->POST('concepto');
		$id_item = $this->A_contact->insertItemXind($id_indicador, $concepto);
		if ($id_item > 0) {
			if ($this->A_contact->addPreguntaAuditoria($id_item)) {
				echo $id_item; //Envia 1 si se guarda correctamente el item y se crea la columna en auditoria_agente que son las preguntas o encuesta
			} else {
				echo 2; //Enviar 2 si se guarda el item pero no la columna
			}
		} else {
			echo 0; //Envia 0 si no se realiza nada!
		}
	}
	/* Funcion para realizar el cambio de estado del Item */
	public function estadoItem()
	{
		$this->load->model('A_contact');
		$id_item = $this->input->POST('id_item');
		$estado = $this->input->POST('estado');

		$getCant = $this->A_contact->countAuditorias();

		if (!$getCant->row()->cantidad > 0){
			if ($this->A_contact->estadoItem($id_item, $estado)) {
				echo $estado;
			} else {
	
				echo 0;
			}
		}else{
			echo 3;//No se puede realizar el cambio de estado, ya que hay auditorias pendientes por finalizar
		}

		
	}
	/* Cargar Items por indicadores para las observaciones */
	public function getItemXindObs()
	{
		$this->load->model('A_contact');
		$id_indicador = $this->input->POST('id_indicador');
		$item = $this->A_contact->getItem($id_indicador);
		if (COUNT($item->result())) {
			echo json_encode($item->result());
		} else {
			echo 'ERROR';
		}
	}
	/* Cargar Observaciones por item */
	public function getObsXitem()
	{
		$this->load->model('A_contact');
		$id_item = $this->input->POST('id_item');
		$obsvaciones = $this->A_contact->getObsXitem($id_item);
		if (COUNT($obsvaciones->result()) > 0) {

			foreach ($obsvaciones->result() as $item) {

				if ($item->estado == 2) {
					$estado = 'Inhabilitar';
					$estadoItem = 1;
					$btnEstadoItem = "btn-warning";
				} else {
					$estado = 'Habilitar';
					$estadoItem = 2;
					$btnEstadoItem = "btn-success";
				}

				echo '<tr>
						<td>' . $item->observacion . '</td>
						<td><button type="button" class="btn ' . $btnEstadoItem . '" onclick="cambiarEstadoObs(' . $item->id_obs . ',' . $estadoItem . ');">' . $estado . '</button></td>
					</tr>';
			}
		} else {
			echo '<tr>
					<td class="text-center" colspan="2">Este Item al momento no cuenta con observaciones</td>
				</tr>';
		}
	}
	//Funcion para agregar nuevos observaciones
	public function addObsXitem()
	{
		//Cargamos el modelo
		$this->load->model('A_contact');
		$id_item = $this->input->POST('id_item');
		$obs = $this->input->POST('obs');
		if ($this->A_contact->insertObsXitem($id_item, $obs)) {
			echo 1;
		} else {
			echo 0;
		}
	}
	/* Funcion para realizar el cambio de estado del Observaciones */
	public function estadoObs()
	{
		$this->load->model('A_contact');
		$id_obs = $this->input->POST('id_obs');
		$estado = $this->input->POST('estado');
		$getCant = $this->A_contact->countAuditorias();
		if (!$getCant->row()->cantidad > 0){
			if ($this->A_contact->estadoObservacion($id_obs, $estado)) {
				echo $estado;
			} else {
	
				echo 0;
			}
		}else{
			echo 3;//No se puede realizar el cambio de estado, ya que hay auditorias pendientes por finalizar
		}
		
	}

	/* Funcion para enviar un correo electronico al agente del contact center */
	public function sendEmail()
	{
		$this->load->model('A_contact');
		$id_auditoria = $this->input->POST('id_auditoria');

		$infoAuditoria = $this->A_contact->getAuditoriaEmail($id_auditoria);

		$data = $this->A_contact->getAuditoriaId($id_auditoria);
		$observacion = "<ol>";
		if ($data->num_rows() > 0) {

			$indicador = $this->A_contact->getIndicador();

			foreach ($indicador->result() as $ind) {

				$item = $this->A_contact->getItem($ind->id_indicador);

				foreach ($item->result() as $item) {

					$campo = "item_" . $item->id_item;
					if ($data->row()->$campo == 2) {
						$obs = $this->A_contact->getObsXitemActivos($item->id_item);
						if (COUNT($obs->result()) > 0) {
							foreach ($obs->result() as $obs) {
								$observacion .= "<li><strong>No $item->concepto: </strong>";
								$observacion .= "$obs->observacion</li>";
							}
						}
					}
				}
			}
		}
		$observacion .='</ol>';



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
		
		$correo->addBCC("programador3@codiesel.co");
		$correo->addAddress($infoAuditoria->mailEncargado);
		$correo->addAddress($infoAuditoria->e_mail);//Correo del Agente del Contact
		/* Correo del asesor - password */
		$correo->Username = "no-reply@codiesel.co";
		$correo->Password = "wrtiuvlebqopeknz";

		$correo->SetFrom("no-reply@codiesel.co", "CODIESEL S.A");
		$correo->CharSet = 'UTF-8';
		/* Asunto del correo */
		$correo->Subject = "Auditoría Contac Center";
		/* Cuerpo del correo  */
		$mensaje = '<!DOCTYPE html>
		<html lang="es">
			<head>
				<meta charset="UTF-8"/>
				<meta http-equiv="X-UA-Compatible" content="IE=edge" />
				<meta name="viewport" content="width=device-width, initial-scale=1.0" />
				<title>Auditoría Contact Center</title>
			</head>
		<body>
			<div class="content" style="height: 100%; display: flex; align-items: center; justify-content: center;">
				<div id="tarjeta" class="card text-center w-75 mx-auto h-50 p-2" align="center" style="width: 100%;
					background-color: #f8f9fa;
					-webkit-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);
					-moz-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);
					box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);">
						<div class="card-body" style="text-align:justify;  ">	
							<p class="card-text" style="font-size: 1rem; line-height: 1.5; word-wrap: break-word; padding:10px">
								¡Hola ' . $infoAuditoria->primer_nombre . '!
								<br><br>
								Tiene una nueva auditoría, acontinuación encontraras las observaciones por cada Item el cual no fue ejecutado correctamente.
								<br>
							</p>
							<p class="card-text" style="font-size: 1rem; line-height: 1.5; word-wrap: break-word; padding:10px">
								Observaciones:
								<br>
								' . $observacion . '
							</p>
							<p class="card-text" style="font-size: 1rem; line-height: 1.5; word-wrap: break-word; padding:10px">
								<strong>Observacion realizada por el encargado de la auditoría:</strong>
								<br>
								' . $infoAuditoria->observaciones . '
							</p>
							<p class="card-text" style="font-size: 1rem; line-height: 1.5; word-wrap: break-word; padding:10px">
								Ingresa en el siguiente enlace para conocer a detalle la auditoría realizada, y diligenciar el compromiso para está.
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
							<div class="contacto" style="display: flex;	flex-direction: column;justify-content: space-evenly;">
								<a style="color:white" href="' . base_url() . 'auditoria_contact/listAuditoria" target="_blank">Ver auditorías</a>
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

	public function compromisoAgente()
	{
		$this->load->model('A_contact');
		$id_auditoria = $this->input->POST('id_auditoria');
		$compromisos = $this->input->POST('compromisos');

		if ($compromisos != "" && $id_auditoria != "") {
			if ($this->A_contact->insertCompromisoAuditoria($id_auditoria, $compromisos)) {
				echo "OK";
			} else {
				echo "ERROR";
			}
		}
	}
	/*********************************************************************Informe DETALLE DE AUDITORIA*******************************************************/
	/* AUTOR: SERGIO GALVIS FECHA: 20/09/2022 */
	public function inf_det_auditoria()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('A_contact');

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

			$getAgentes = $this->A_contact->getAllUserAgente();
			//echo $id_usu;
			$arr_user = array(
				'usu' => $usu,
				'userdata' => $userinfo,
				'menus' => $allmenus,
				'pass' => $pass,
				'id_usu' => $id_usu,
				'agentes' => $getAgentes,
			);
			//abrimos la vista segun el perfil del usuario

			$this->load->view('Auditoria_contact/Informe_detalle', $arr_user);
		}
	}
	/* Funcion para cargar la información o auditoria por agente y mes */
	public function cargarInfDetalle()
	{
		$this->load->model('A_contact');
		$fecha = $this->input->POST('AuditoriaMes');
		$nitAgente = $this->input->POST('nitAgente');
		$fecha = explode('-', $fecha);


		$data = $this->A_contact->getDetalleAuditoria($nitAgente, $fecha[0], $fecha[1]);

		if (COUNT($data->result()) > 0) {

			$bandera = 0;
			$sumaPuntos = 0;
			foreach ($data->result() as $key) {
				if($key->puntuacion != "" || $key->puntuacion == '0'){
					$btnOpcion = '<button class="btn btn-info" onclick="VerAuditoria(' . $key->id_auditoria . ')">Ver</button>';
				}elseif ($key->puntuacion == NULL) {
					$btnOpcion = '<button class="btn btn-warning" onclick="EditarAuditoria(' . $key->id_auditoria . ')">Editar</button>';
				}
				echo '<tr>
						<td>' . $key->fecha_c . '</td>
						<td>' . $key->puntuacion . '</td>	
						<td class="text-center">'.$btnOpcion.'</td>	
				</tr>';
				$sumaPuntos += $key->puntuacion;
				$bandera++;
			}

			$promPuntos = ($sumaPuntos / $bandera);

			echo '
				<tr style="background: lightgray;">
					<th style="text-align: center;" colspan="1">Promedio de puntos: <strong>' . $sumaPuntos . ' / ' . $bandera . '</strong></th>
					<td>' . number_format($promPuntos, 0, '.', '') . '</td>
					<td></td>
				</tr>
				';
		} else {
			echo '<tr style="background: lightcoral;">
					<td class="text-center" colspan="3"><strong>No se encontraron auditorias en la fecha indicada al agente seleccionado.</strong></td>
				</tr>';
		}
	}

	public function validateCantAuditorias(){
		$this->load->model('A_contact');
		$getCant = $this->A_contact->countAuditorias();
		if (!$getCant->row()->cantidad > 0){
			echo $getCant->row()->cantidad;
		}else{
			echo $getCant->row()->cantidad;//No se puede realizar el cambio de estado, ya que hay auditorias pendientes por finalizar
		}
	}
}
