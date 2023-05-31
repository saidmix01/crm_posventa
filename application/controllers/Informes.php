<?php

use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Counts;

use function PHPSTORM_META\type;

/**
 * 
 */
class Informes extends CI_Controller
{

	public function index()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			foreach ($userinfo->result() as $nombre) {
				$nomb = $nombre->nombres;
			}
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'nomb' => $nomb);
			//abrimos la vista
			$this->load->view("Informes", $arr_user);
		}
	}

	public function Informe_nps_general()
	{
		$desde = $this->input->GET('des_gral');
		$hasta = $this->input->GET('has_gral');
		$this->load->model('encuestas');
		$result = $this->encuestas->Informe_nps_general($desde, $hasta);
		$data = array();
		foreach ($result->result() as $key) {
			$res = array('fecha' => $key->Fecha, 'calificacion' => round($key->Calificacion, 2));
			$data[] = array($res);
		}
		echo json_encode($data);
	}

	public function detalle_nps_gral()
	{
		$desde = $this->input->GET('desde');
		$hasta = $this->input->GET('hasta');
		$this->load->model('encuestas');
		$result = $this->encuestas->detalle_Informe_nps_general($desde, $hasta);
		foreach ($result->result() as $key) {
			echo '<tr>
			<td>
			' . $key->Fecha . '
			</td>
			<td>
			' . round($key->Calificacion, 2) . '
			</td>
			<td>
			' . $key->Enc_0_a_6 . '
			</td>
			<td>
			' . $key->Enc_7_a_8 . '
			</td>
			<td>
			' . $key->Enc_9_a_10 . '
			</td>
			</tr>';
		}
	}

	public function detalle_nps_tecnico()
	{
		$sede = $this->input->GET('sede');
		$this->load->model('encuestas');
		$result = $this->encuestas->detalle_Informe_nps_tecnico($sede);


		foreach ($result->result() as $key) {
			echo '<tr>
			<td>
			' . $key->nombres . '
			</td>
			<td>
			' . $key->fecha . '
			</td>
			<td>
			' . $key->Enc_0_a_6 . '
			</td>
			<td>
			' . $key->Enc_7_a_8 . '
			</td>
			<td>
			' . $key->Enc_9_a_10 . '
			</td>
			<td>
			' . round($key->Calificacion, 2) . '
			</td>
			</tr>';
		}
	}

	public function Informe_nps_tecnico()
	{
		$this->load->model('encuestas');
		$giron_data = $this->encuestas->Informe_nps_tecnico_v2('giron');
		$rosita_data = $this->encuestas->Informe_nps_tecnico_v2('rosita');
		$barranca_data = $this->encuestas->Informe_nps_tecnico_v2('barranca');
		$bocono_data = $this->encuestas->Informe_nps_tecnico_v2('bocono');
		$arr_giron = array();
		$arr_rosita = array();
		$arr_barranca = array();
		$arr_bocono = array();
		foreach ($giron_data->result() as $key) {
			$data_aux = array('nom_tec' => $key->nombres, 'calificacion' => round($key->Calificacion, 2));
			$arr_giron[] = array($data_aux);
		}

		foreach ($rosita_data->result() as $key) {
			$data_aux = array('nom_tec' => $key->nombres, 'calificacion' => round($key->Calificacion, 2));
			$arr_rosita[] = array($data_aux);
		}

		foreach ($barranca_data->result() as $key) {
			$data_aux = array('nom_tec' => $key->nombres, 'calificacion' => round($key->Calificacion, 2));
			$arr_barranca[] = array($data_aux);
		}

		foreach ($bocono_data->result() as $key) {
			$data_aux = array('nom_tec' => $key->nombres, 'calificacion' => round($key->Calificacion, 2));
			$arr_bocono[] = array($data_aux);
		}
		$data_inf = array('giron' => $arr_giron, 'rosita' => $arr_rosita, 'barranca' => $arr_barranca, 'bocono' => $arr_bocono);
		echo json_encode($data_inf);
	}

	public function Informe_nps_sedes()
	{
		$this->load->model('encuestas');
		$s_giron = array();
		$s_rosita = array();
		$s_bocono = array();
		$s_barran = array();
		$sedes = array('Giron', 'La Rosita', 'Barrancabermeja', 'Cucuta Bocono', 'General');
		for ($i = 0; $i < count($sedes); $i++) {
			switch ($sedes[$i]) {
				case 'Giron':
					$result = $this->encuestas->Informe_nps_sede("giron");
					foreach ($result->result() as $key) {
						$s_giron[] = array('giron' => "Giron", 'calificacion_g' => round($key->Calificacion, 2));
					}
					break;
				case 'La Rosita':
					$result = $this->encuestas->Informe_nps_sede("rosita");
					foreach ($result->result() as $key) {
						$s_rosita[] = array('rosita' => "La Rosita", 'calificacion_r' => round($key->Calificacion, 2));
					}
					break;
				case 'Barrancabermeja':
					$result = $this->encuestas->Informe_nps_sede("barranca");
					foreach ($result->result() as $key) {
						$s_barran[] = array('barranca' => "Barrancabermeja", 'calificacion_ba' => round($key->Calificacion, 2));
					}
					break;
				case 'Cucuta Bocono':
					$result = $this->encuestas->Informe_nps_sede("bocono");
					foreach ($result->result() as $key) {
						$s_bocono[] = array('bocono' => "Cucuta Bocono", 'calificacion_bo' => round($key->Calificacion, 2));
					}
					break;
				case 'General':
					$result = $this->encuestas->Informe_nps_sede("general");
					foreach ($result->result() as $key) {
						$s_general[] = array('general' => "General", 'calificacion_ge' => round($key->Calificacion, 2));
					}
					break;
			}
		}

		$data = array('giron' => $s_giron, 'rosita' => $s_rosita, 'bocono' => $s_bocono, 'barranca' => $s_barran, 'general' => $s_general);
		echo json_encode($data);
	}


	public function detalle_nps_sedes()
	{
		$this->load->model('encuestas');
		$data_giron = $this->encuestas->detalle_Informe_nps_sede("giron");
		$data_rosita = $this->encuestas->detalle_Informe_nps_sede("rosita");
		$data_barranca = $this->encuestas->detalle_Informe_nps_sede("barranca");
		$data_bocono = $this->encuestas->detalle_Informe_nps_sede("bocono");
		$data_general = $this->encuestas->detalle_Informe_nps_sede("general");
		$arr_giron = array();
		$arr_rosita = array();
		$arr_barranca = array();
		$arr_bocono = array();
		$arr_general = array();
		$data = array();
		foreach ($data_giron->result() as $key) {
			$arr_giron = array('sede' => $key->sede, 'fecha' => $key->Fecha, 'calificacion' => $key->Calificacion, 'enc06' => $key->Enc_0_a_6, 'enc78' => $key->Enc_7_a_8, 'enc910' => $key->Enc_9_a_10);
		}
		foreach ($data_rosita->result() as $key) {
			$arr_rosita = array('sede' => $key->sede, 'fecha' => $key->Fecha, 'calificacion' => $key->Calificacion, 'enc06' => $key->Enc_0_a_6, 'enc78' => $key->Enc_7_a_8, 'enc910' => $key->Enc_9_a_10);
		}
		foreach ($data_barranca->result() as $key) {
			$arr_barranca = array('sede' => $key->sede, 'fecha' => $key->Fecha, 'calificacion' => $key->Calificacion, 'enc06' => $key->Enc_0_a_6, 'enc78' => $key->Enc_7_a_8, 'enc910' => $key->Enc_9_a_10);
		}
		foreach ($data_bocono->result() as $key) {
			$arr_bocono = array('sede' => $key->sede, 'fecha' => $key->Fecha, 'calificacion' => $key->Calificacion, 'enc06' => $key->Enc_0_a_6, 'enc78' => $key->Enc_7_a_8, 'enc910' => $key->Enc_9_a_10);
		}
		foreach ($data_general->result() as $key) {
			$arr_general  = array('sede' => $key->sede, 'fecha' => $key->Fecha, 'calificacion' => $key->Calificacion, 'enc06' => $key->Enc_0_a_6, 'enc78' => $key->Enc_7_a_8, 'enc910' => $key->Enc_9_a_10);
		}
		$data = array($arr_giron, $arr_rosita, $arr_barranca, $arr_bocono, $arr_general);
		foreach ($data as $s) {
			if ($s['sede'] == "general") {
				echo '<tr style="background-color: #D6D6D6;">
				<td>
				' . strtoupper($s['sede']) . '
				</td>
				<td>
				' . $s['fecha'] . '
				</td>
				<td>
				' . round($s['calificacion'], 2) . '
				</td>
				<td>
				' . $s['enc06'] . '
				</td>
				<td>
				' . $s['enc78'] . '
				</td>
				<td>
				' . $s['enc910'] . '
				</td>
				</tr>';
			} else {
				echo '<tr>
				<td class="table-info">
				' . strtoupper($s['sede']) . '
				</td>
				<td>
				' . $s['fecha'] . '
				</td>
				<td>
				' . round($s['calificacion'], 2) . '
				</td>
				<td>
				' . $s['enc06'] . '
				</td>
				<td>
				' . $s['enc78'] . '
				</td>
				<td>
				' . $s['enc910'] . '
				</td>
				</tr>';
			}
		}
	}

	public function get_presupuesto()
	{
		$n = $this->input->get('mes');
		$jsonString = $this->input->get('jsonString');
		$arr_meses = json_decode($jsonString, true);
		$meses_selec = array();
		for ($i = 0; $i < count($arr_meses); $i++) {
			$meses_selec[] = $arr_meses[$i];
		}
		//print_r($meses_selec);
		//print_r($data);
		//echo json_encode($dat);
		//printf($dat);
		$this->load->model('presupuesto');
		$dat = array();
		for ($i = 0; $i < count($arr_meses); $i++) {
			switch ($arr_meses[$i]) {
				case 'Enero':
					$facturado = $this->presupuesto->get_presupuesto_dia_by_mes("01/01/2020");

					$data = array('to' => $facturado->total, 'mes' => $facturado->Mes);
					$dat[] = array($data);
					break;
				case 'Febrero':
					$facturado = $this->presupuesto->get_presupuesto_dia_by_mes("01/02/2020");
					$data = array('to' => $facturado->total, 'mes' => $facturado->Mes);
					$dat[] = array($data);
					break;
				case 'Marzo':
					$facturado = $this->presupuesto->get_presupuesto_dia_by_mes("01/03/2020");
					$data = array('to' => $facturado->total, 'mes' => $facturado->Mes);
					$dat[] = array($data);
					break;
				case 'Abril':
					$facturado = $this->presupuesto->get_presupuesto_dia_by_mes("01/04/2020");
					$data = array('to' => $facturado->total, 'mes' => $facturado->Mes);
					$dat[] = array($data);
					break;
				case 'Mayo':
					$facturado = $this->presupuesto->get_presupuesto_dia_by_mes("01/05/2020");
					$data = array('to' => $facturado->total, 'mes' => $facturado->Mes);
					$dat[] = array($data);
					break;
				case 'Junio':
					$facturado = $this->presupuesto->get_presupuesto_dia_by_mes("01/06/2020");
					$data = array('to' => $facturado->total, 'mes' => $facturado->Mes);
					$dat[] = array($data);
					break;
				case 'Julio':
					$facturado = $this->presupuesto->get_presupuesto_dia_by_mes("01/07/2020");
					$data = array('to' => $facturado->total, 'mes' => $facturado->Mes);
					$dat[] = array($data);
					break;
				case 'Agosto':
					$facturado = $this->presupuesto->get_presupuesto_dia_by_mes("01/08/2020");
					$data = array('to' => $facturado->total, 'mes' => $facturado->Mes);
					$dat[] = array($data);
					break;
				case 'Septiembre':
					$facturado = $this->presupuesto->get_presupuesto_dia_by_mes("01/09/2020");
					$data = array('to' => $facturado->total, 'mes' => $facturado->Mes);
					$dat[] = array($data);
					break;
				case 'Octubre':
					$facturado = $this->presupuesto->get_presupuesto_dia_by_mes("01/10/2020");
					$data = array('to' => $facturado->total, 'mes' => $facturado->Mes);
					$dat[] = array($data);
					break;
				case 'Noviembre':
					$facturado = $this->presupuesto->get_presupuesto_dia_by_mes("01/11/2020");
					$data = array('to' => $facturado->total, 'mes' => $facturado->Mes);
					$dat[] = array($data);
					break;
				case 'Diciembre':
					$facturado = $this->presupuesto->get_presupuesto_dia_by_mes("01/12/2020");
					$data = array('to' => $facturado->total, 'mes' => $facturado->Mes);
					$dat[] = array($data);
					break;
			}
			//$meses_selec[] = $arr_meses[$i];
		}

		echo json_encode($dat);
	}


	public function Informe_nps_colmotores()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos necesarios
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('encuestas');
			$this->load->model('Nominas');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			/*se obtienen los tecnicos*/
			$tecnicos = $this->Nominas->get_tall_operarios_intranet_all();
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			/*OBTIENE EL ID DEL USUARIO*/
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}

			/*ARRAY DE DATOS PARA LA VISTA*/
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'tecnicos' => $tecnicos);
			//abrimos la vista
			$this->load->view('Informe_nps_colmotores', $arr_user);
		}
	}

	public function Informe_pac()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos necesarios
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('encuestas');
			$this->load->model('Informe');
			$this->load->model('presupuesto');
			$this->load->model('nominas');

			/*CENTROS DE COSTOS*/
			$centros_costos = "4,40,33,45,3,16,17,13,70,11,29,80,31,46,28,60,15";
			/*PRESUPUESTO A HOY*/
			$to_presupuesto_dia = $this->presupuesto->get_presupuesto_dia($centros_costos);
			/*PRESUPUESTO DEL MES*/
			$fecha_ini = $this->nominas->obtener_primer_dia_mes();
			$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
			$to_presupuesto_mes = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL");
			$to_t = 0;
			foreach ($to_presupuesto_mes->result() as $key) {
				$to_t = $key->presupuesto;
			}
			/*CALCULAR PORCENTAJES*/
			$to_dias_mes = $this->Informe->get_total_dias();
			$to_dias_hoy = $this->Informe->get_dias_actual();
			$n_to_dias = $to_dias_mes->ultimo_dia;
			$n_dias_hoy = $to_dias_hoy->dia;
			//LO QUE DEBEMOS LLEVAR A DIA DE HOY
			$to_dia = $to_presupuesto_dia->total;
			$to_objetivo = ($to_t / $n_to_dias) * $n_dias_hoy;
			//PORCENTAJE QUE LLEVAMOS CON RESPECTO AL MES
			$porcentaje_objetivo = ($to_dia / $to_t) * 100;

			//PORCENTAJE QUE DEBEMOS LLEVAR A DIA DE HOY
			$porcentaje_a_hoy = ($to_dia / $to_objetivo) * 100;

			//$porcentaje_objetivo = ($to_objetivo * 100) / $to_t;
			$porcentaje_objetivo_restante = (100 - $porcentaje_a_hoy);
			if ($porcentaje_objetivo_restante < 0) {
				$porcentaje_objetivo_restante = 0;
			}

			$porcentaje = ($to_dia * 100) / $to_t;
			$porcentaje_restante = (100 - $porcentaje);
			if ($porcentaje_restante < 0) {
				$porcentaje_restante = 0;
			}

			/*Informe INVENTARIOS*/
			$inventario = $this->Informe->Informe_inventario();
			$val_to_inv = 0;
			$val_ref_a = 0;
			$val_ref_b = 0;
			$val_ref_c = 0;
			$val_ref_d = 0;
			$val_ref_e = 0;
			$val_ref_f = 0;
			$val_ref_g = 0;
			$val_ref_null = 0;
			foreach ($inventario->result() as $key) {
				$val_to_inv += ($key->Promedio * $key->stock);
				if ($key->calificacion_abc == "A") {
					$val_ref_a += ($key->Promedio * $key->stock);
				} elseif ($key->calificacion_abc == "B") {
					$val_ref_b += ($key->Promedio * $key->stock);
				} elseif ($key->calificacion_abc == "C") {
					$val_ref_c += ($key->Promedio * $key->stock);
				} elseif ($key->calificacion_abc == "D") {
					$val_ref_d += ($key->Promedio * $key->stock);
				} elseif ($key->calificacion_abc == "E") {
					$val_ref_e += ($key->Promedio * $key->stock);
				} elseif ($key->calificacion_abc == "F") {
					$val_ref_f += ($key->Promedio * $key->stock);
				} elseif ($key->calificacion_abc == "G") {
					$val_ref_g += ($key->Promedio * $key->stock);
				} elseif ($key->calificacion_abc == null) {
					$val_ref_null += ($key->Promedio * $key->stock);
				}
			}
			$data_referencias = array('A' => $val_ref_a, 'B' => $val_ref_b, 'C' => $val_ref_c, 'D' => $val_ref_d, 'E' => $val_ref_e, 'F' => $val_ref_f, 'G' => $val_ref_g, 'NULL' => $val_ref_null);
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			/*OBTIENE EL ID DEL USUARIO*/
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}

			/*Calificacion PAC*/
			$calificacion_pac = $this->Informe->get_calificacion_sede("general");

			/*CALCULO DEL NUMERO DE ENCUESTAS*/
			$total_encuestas = "";
			$enc06 = "";
			$enc78 = "";
			$enc910 = "";
			foreach ($calificacion_pac->result() as $key) {
				$total_encuestas = $key->Enc_0_a_6 + $key->Enc_7_a_8 + $key->Enc_9_a_10;
				$enc06 = $key->Enc_0_a_6;
				$enc78 = $key->Enc_7_a_8;
				$enc910 = $key->Enc_9_a_10;
			}
			/*calculo del procentaje de encuestas*/
			$porcen_06 = ($enc06 * 100) / $total_encuestas;
			$porcen_78 = ($enc78 * 100) / $total_encuestas;
			$porcen_910 = ($enc910 * 100) / $total_encuestas;

			/*CALCULO NPS INTERNO GENERAL*/
			$bod = "1,9,11,21,7,6,19,8,14,16,22";
			$nps_int = 0;
			$enc0a6 = 0;
			$enc7a8 = 0;
			$enc9a10 = 0;
			$nps_int_gen = $this->Informe->get_data_nps_interno_sedes($bod);
			$data_nps_int = array();
			$to_enc = 0;
			foreach ($nps_int_gen->result() as $key) {
				$enc0a6 += $key->enc0a6;
				$enc7a8 += $key->enc7a8;
				$enc9a10 += $key->enc9a10;
				$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
			}
			if ($enc0a6 != 0 || $enc7a8 != 0 || $enc9a10 != 0) {
				$porcen_int_06 = ($enc0a6 * 100) / $to_enc;
				$porcen_int_78 = ($enc7a8 * 100) / $to_enc;
				$porcen_int_910 = ($enc9a10 * 100) / $to_enc;
				$nps_int = ((($enc9a10 - $enc0a6) / $to_enc) * 100);
			} else {
				$porcen_int_06 = 0;
				$porcen_int_78 = 0;
				$porcen_int_910 = 0;
				$nps_int = 0;
			}



			$data_nps_int[] = array('nps' => $nps_int, 'enc06' => $enc0a6, 'enc78' => $enc7a8, 'enc910' => $enc9a10, 'to_enc' => $to_enc, "porcen_06" => $porcen_int_06, "porcen_78" => $porcen_int_78, "porcen_910" => $porcen_int_910);
			/*ARRAY DE DATOS PARA LA VISTA*/
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'calificacion_pac' => $calificacion_pac, 'enc06' => $enc06, 'enc78' => $enc78, 'enc910' => $enc910, 'porcen_06' => $porcen_06, 'porcen_78' => $porcen_78, 'porcen_910' => $porcen_910, 'to_dia' => $to_dia, 'to' => $to_t, 'porcen_hoy' => $porcentaje_a_hoy, 'porcen_hoy_res' => $porcentaje_objetivo_restante, 'to_mes' => $porcentaje, 'to_mes_res' => $porcentaje_restante, 'val_to_inv' => $val_to_inv, 'data_ref' => $data_referencias, 'arr_nps_int' => $data_nps_int);
			//abrimos la vista
			$this->load->view('Informe_pac', $arr_user);
		}
	}

	public function Informe_pac_sedes()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos necesarios
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('encuestas');
			$this->load->model('Informe');
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			/*OBTIENE EL ID DEL USUARIO*/
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			/*DATA SEDES*/
			$data_sedes = $this->Informe->get_data_sedes();
			/*Calificacion PAC*/
			$calificacion_pac = $this->Informe->get_calificacion_sede("general");
			/*CALCULO DEL NUMERO DE ENCUESTAS*/
			$total_encuestas = "";
			$enc06 = "";
			$enc78 = "";
			$enc910 = "";
			foreach ($calificacion_pac->result() as $key) {
				$total_encuestas = $key->Enc_0_a_6 + $key->Enc_7_a_8 + $key->Enc_9_a_10;
				$enc06 = $key->Enc_0_a_6;
				$enc78 = $key->Enc_7_a_8;
				$enc910 = $key->Enc_9_a_10;
			}
			/*calculo del procentaje de encuestas*/
			$porcen_06 = ($enc06 * 100) / $total_encuestas;
			$porcen_78 = ($enc78 * 100) / $total_encuestas;
			$porcen_910 = ($enc910 * 100) / $total_encuestas;

			/*ARRAY DE DATOS PARA LA VISTA*/
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'sedes' => $data_sedes, 'calificacion_pac' => $calificacion_pac, 'enc06' => $enc06, 'enc78' => $enc78, 'enc910' => $enc910, 'porcen_06' => $porcen_06, 'porcen_78' => $porcen_78, 'porcen_910' => $porcen_910);
			//abrimos la vista
			$this->load->view('Informe_pac_sedes', $arr_user);
		}
	}

	public function Informe_pac_detalle_sede()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			/*OBTENEMOS LA SEDE*/
			$sede = $this->input->GET('sede');
			//llamamos los modelos necesarios
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('encuestas');
			$this->load->model('Informe');
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			/*OBTIENE EL ID DEL USUARIO*/
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			/*NPS SEDE*/
			$dat_sede = $this->Informe->get_calificacion_sede($sede);

			/*DATA TECNICOS*/
			$tecnicos = $this->Informe->get_data_tecnicos($sede);
			/*ARRAY DE DATOS PARA LA VISTA*/
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'tecnicos' => $tecnicos, 'sede' => $sede, 'data_sede' => $dat_sede);
			//abrimos la vista
			$this->load->view('Informe_pac_detalle_sede', $arr_user);
		}
	}

	public function detalle_Informe_ref_pac()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('presupuesto');
			$this->load->model('nominas');
			$this->load->model('sedes');
			$this->load->model('Informe');

			//obtenemos primer y ultimo dia del mes actual
			$fecha_ini = $this->nominas->obtener_primer_dia_mes();
			$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//obtener todas las sedes
			$allsedes = $this->sedes->getAllSedes();
			//se obtienen los operarios por tipo de operacion
			//$operarios="";
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			/*Informe INVENTARIO*/
			/*Informe INVENTARIOS*/
			$inventario = $this->Informe->Informe_inventario();
			$val_to_inv = 0;
			$val_ref_a = 0;
			$val_ref_b = 0;
			$val_ref_c = 0;
			$val_ref_d = 0;
			$val_ref_e = 0;
			$val_ref_f = 0;
			$val_ref_g = 0;
			$val_ref_null = 0;
			foreach ($inventario->result() as $key) {
				$val_to_inv += ($key->Promedio * $key->stock);
				if ($key->calificacion_abc == "A") {
					$val_ref_a += ($key->Promedio * $key->stock);
				} elseif ($key->calificacion_abc == "B") {
					$val_ref_b += ($key->Promedio * $key->stock);
				} elseif ($key->calificacion_abc == "C") {
					$val_ref_c += ($key->Promedio * $key->stock);
				} elseif ($key->calificacion_abc == "D") {
					$val_ref_d += ($key->Promedio * $key->stock);
				} elseif ($key->calificacion_abc == "E") {
					$val_ref_e += ($key->Promedio * $key->stock);
				} elseif ($key->calificacion_abc == "F") {
					$val_ref_f += ($key->Promedio * $key->stock);
				} elseif ($key->calificacion_abc == "G") {
					$val_ref_g += ($key->Promedio * $key->stock);
				} elseif ($key->calificacion_abc == null) {
					$val_ref_null += ($key->Promedio * $key->stock);
				}
			}
			$porce_a = ($val_ref_a * 100) / $val_to_inv;
			$porce_b = ($val_ref_b * 100) / $val_to_inv;
			$porce_c = ($val_ref_c * 100) / $val_to_inv;
			$porce_d = ($val_ref_d * 100) / $val_to_inv;
			$porce_e = ($val_ref_e * 100) / $val_to_inv;
			$porce_f = ($val_ref_f * 100) / $val_to_inv;
			$porce_g = ($val_ref_g * 100) / $val_to_inv;
			$porce_null = ($val_ref_null * 100) / $val_to_inv;

			$data_referencias_porcen = array('A' => $porce_a, 'B' => $porce_b, 'C' => $porce_c, 'D' => $porce_d, 'E' => $porce_e, 'F' => $porce_f, 'G' => $porce_g, 'NULL' => $porce_null);

			$data_referencias = array('A' => $val_ref_a, 'B' => $val_ref_b, 'C' => $val_ref_c, 'D' => $val_ref_d, 'E' => $val_ref_e, 'F' => $val_ref_f, 'G' => $val_ref_g, 'NULL' => $val_ref_null);
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'inf_inv' => $inventario, 'porcen_ref' => $data_referencias_porcen, 'data_ref' => $data_referencias);
			//abrimos la vista
			$this->load->view("Informe_referencias_inv", $arr_user);
		}
	}
	public function detalle_Informe_ref_pac_bod()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('presupuesto');
			$this->load->model('nominas');
			$this->load->model('sedes');
			$this->load->model('Informe');

			//obtenemos primer y ultimo dia del mes actual
			$fecha_ini = $this->nominas->obtener_primer_dia_mes();
			$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//obtener todas las sedes
			$allsedes = $this->sedes->getAllSedes();
			//se obtienen los operarios por tipo de operacion
			//$operarios="";
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			/*Informe INVENTARIO*/
			/*Informe INVENTARIOS*/
			$inventario = $this->Informe->Informe_inventario_bod();
			$val_to_inv = 0;
			$val_ref_ggas = 0;
			$val_ref_ros = 0;
			$val_ref_barranca = 0;
			$val_ref_bocono = 0;
			$val_ref_chevr = 0;
			$val_ref_solochev = 0;
			$val_ref_remisiones = 0;
			$val_ref_camp_giron = 0;
			$val_ref_camp_rosita = 0;
			$val_ref_camp_bocono = 0;
			$val_ref_camp_barranca = 0;
			//1,7,6,8,4,23
			foreach ($inventario->result() as $key) {
				$val_to_inv += ($key->Promedio * $key->stock);
				if ($key->bodega == "1") {
					$val_ref_ggas += ($key->Promedio * $key->stock);
				} elseif ($key->bodega == "7") {
					$val_ref_ros += ($key->Promedio * $key->stock);
				} elseif ($key->bodega == "6") {
					$val_ref_barranca += ($key->Promedio * $key->stock);
				} elseif ($key->bodega == "8") {
					$val_ref_bocono += ($key->Promedio * $key->stock);
				} elseif ($key->bodega == "4") {
					$val_ref_chevr += ($key->Promedio * $key->stock);
				} elseif ($key->bodega == "23") {
					$val_ref_solochev += ($key->Promedio * $key->stock);
				} elseif ($key->bodega == "98") {
					$val_ref_remisiones += ($key->Promedio * $key->stock);
				} elseif ($key->bodega == "97") {
					$val_ref_camp_giron += ($key->Promedio * $key->stock);
				} elseif ($key->bodega == "96") {
					$val_ref_camp_rosita += ($key->Promedio * $key->stock);
				} elseif ($key->bodega == "95") {
					$val_ref_camp_barranca += ($key->Promedio * $key->stock);
				} elseif ($key->bodega == "94") {
					$val_ref_camp_bocono += ($key->Promedio * $key->stock);
				}
			}
			$porce_ggas = ($val_ref_ggas * 100) / $val_to_inv;
			$porce_ros = ($val_ref_ros * 100) / $val_to_inv;
			$porce_barranca = ($val_ref_barranca * 100) / $val_to_inv;
			$porce_bocono = ($val_ref_bocono * 100) / $val_to_inv;
			$porce_chevr = ($val_ref_chevr * 100) / $val_to_inv;
			$porce_solochev = ($val_ref_solochev * 100) / $val_to_inv;
			$porce_remisiones = ($val_ref_remisiones * 100) / $val_to_inv;
			$porce_camp_giron = ($val_ref_camp_giron * 100) / $val_to_inv;
			$porce_camp_rosita = ($val_ref_camp_rosita * 100) / $val_to_inv;
			$porce_camp_barranca = ($val_ref_camp_barranca * 100) / $val_to_inv;
			$porce_camp_bocono = ($val_ref_camp_bocono * 100) / $val_to_inv;

			$data_referencias_porcen = array('giron' => $porce_ggas, 'rosita' => $porce_ros, 'barranca' => $porce_barranca, 'bocono' => $porce_bocono, 'chevro' => $porce_chevr, 'solochev' => $porce_solochev, 'remisiones' => $porce_remisiones, 'cam_giron' => $porce_camp_giron, 'cam_rosita' => $porce_camp_rosita, 'cam_barranca' => $porce_camp_barranca, 'cam_bocono' => $porce_camp_bocono);

			$data_referencias = array('giron' => $val_ref_ggas, 'rosita' => $val_ref_ros, 'barranca' => $val_ref_barranca, 'bocono' => $val_ref_bocono, 'chevro' => $val_ref_chevr, 'solochev' => $val_ref_solochev, 'remisiones' => $val_ref_remisiones, 'cam_giron' => $val_ref_camp_giron, 'cam_rosita' => $val_ref_camp_rosita, 'cam_barranca' => $val_ref_camp_barranca, 'cam_bocono' => $val_ref_camp_bocono);
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'inf_inv' => $inventario, 'porcen_ref' => $data_referencias_porcen, 'data_ref' => $data_referencias);
			//abrimos la vista
			$this->load->view("Informe_referencias_inv_bod", $arr_user);
		}
	}

	public function Informe_nps_interno_historico()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos necesarios
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('encuestas');
			$this->load->model('Informe');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			for ($i = 3; $i >= 0; $i--) {
				//$i = $i + 1;
				$ii = ($i * -1);
				$m = "-" . $i;
				$nps_int = $this->Informe->get_nps_int_historico($m);
				//print_r($nps_int->result());
				$enc0a6 = 0;
				$enc7a8 = 0;
				$enc9a10 = 0;
				$fecha = "";
				$to_enc = 0;
				$nps_int_gene = 0;
				$porcen_int_06 = 0;
				$porcen_int_78 = 0;
				$porcen_int_910 = 0;
				$porcen_int_06 = 0;
				$porcen_int_78 = 0;
				$porcen_int_910 = 0;
				if (count($nps_int->result()) != 0) {
					foreach ($nps_int->result() as $key) {
						$enc0a6 += $key->enc0a6;
						$enc7a8 += $key->enc7a8;
						$enc9a10 += $key->enc9a10;
						$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
						$fecha = $key->fecha;
					}
					if ($enc0a6 == 0 && $enc7a8 == 0 && $enc9a10 == 0) {
						$nps_int_gene = 0;
						$porcen_int_06 = 0;
						$porcen_int_78 = 0;
						$porcen_int_910 = 0;
					} else {
						$nps_int_gene = ((($enc9a10 - $enc0a6) / $to_enc) * 100);
						$porcen_int_06 = ($enc0a6 * 100) / $to_enc;
						$porcen_int_78 = ($enc7a8 * 100) / $to_enc;
						$porcen_int_910 = ($enc9a10 * 100) / $to_enc;
					}
				}



				$data_nps_int[] = array('fecha' => $fecha, 'nps' => $nps_int_gene);
				$point1[] = array('label' => $fecha, 'y' => $enc0a6);
				$point2[] = array('label' => $fecha, 'y' => $enc7a8);
				$point3[] = array('label' => $fecha, 'y' => $enc9a10);
				$trampa[] = array('label' => $fecha, 'y' => (100 - $to_enc));
				$line_nps[] = array('label' => $fecha, 'y' => $nps_int_gene);
			}
			//die;
			$allperfiles = $this->perfiles->getAllPerfiles();
			//CANTIDAD DE ENCUESTSAS ENVIADAS POR MES
			$m1 = array(-3, -2, -1, 0);
			$m2 = array(0, 1, 2, 3);
			//total vendido
			for ($i = 3; $i >= 0; $i--) {
				$db_data = $this->Informe->cant_enc_enviadas_satis($m2[$i]);
				foreach ($db_data->result() as $key) {
					$cant_enc_env[] = array('label' => $key->fecha, 'y' => $key->n_encuestas);
				}
			}
			for ($i = 3; $i >= 0; $i--) {
				$db_data = $this->Informe->cant_enc_resp($m2[$i]);
				foreach ($db_data->result() as $key) {
					$cant_enc_resp[] = array('label' => $key->fecha, 'y' => $key->n_encuestas);
				}
			}
			for ($i = 0; $i < 4; $i++) {
				$db_data = $this->Informe->cant_ord_finalizada($m1[$i], $m2[$i]);
				foreach ($db_data->result() as $key) {
					$fecha = $key->mes . " " . $key->anio;
					$cant_ord_fin[] = array('label' => $fecha, 'y' => $key->ord_fin);
				}
			}
			/*OBTIENE EL ID DEL USUARIO*/
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			/*ARRAY DE DATOS PARA LA VISTA*/
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'data_nps' => $data_nps_int, 'point1' => $point1, 'point2' => $point2, 'point3' => $point3, 'line_nps' => $line_nps, 'cant_enc_env' => $cant_enc_env, 'cant_enc_resp' => $cant_enc_resp, 'cant_ord_fin' => $cant_ord_fin);
			//abrimos la vista
			$this->load->view('Informe_nps_interno_historico', $arr_user);
		}
	}

	public function Informe_nps_colmotores_hist()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('presupuesto');
			$this->load->model('nominas');
			$this->load->model('Informe');

			//obtenemos primer y ultimo dia del mes actual
			$fecha_ini = $this->nominas->obtener_primer_dia_mes();
			$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//se obtienen los operarios por tipo de operacion
			//$operarios="";
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			/*CARGAR CONTENIDO DE LA TABLA NPS*/
			$point1 = array();
			$point2 = array();
			$point3 = array();
			$encuestade0a6 = "";
			$encuestade9a10 = "";
			$total_encu = "";
			$point11 = array();
			$point22 = array();
			$point33 = array();
			$data_table_nps = array();
			$data_tabla = array();
			for ($i = 0; $i <= 11; $i++) {
				$ii = (($i * -1));
				$nps_historico = $this->Informe->get_nps_historico($ii);
				foreach ($nps_historico->result() as $key) {
					$total_encu = $key->Enc_0_a_6 + $key->Enc_7_a_8 + $key->Enc_9_a_10;
					$nps = (($key->Enc_9_a_10 - $key->Enc_0_a_6) / $total_encu) * 100;
					$porcen_0a6 = ($key->Enc_0_a_6 * $nps) / $total_encu;
					$porcen_7a8 = ($key->Enc_7_a_8 * $nps) / $total_encu;
					$porcen_9a10 = ($key->Enc_9_a_10 * $nps) / $total_encu;
					$point1[] = array('label' => $key->mes_ano, 'y' => $key->Enc_0_a_6);
					$point2[] = array('label' => $key->mes_ano, 'y' => $key->Enc_7_a_8);
					$point3[] = array('label' => $key->mes_ano, 'y' => $key->Enc_9_a_10);
					$data_table_nps[] = array('mes_ano' => $key->mes_ano, 'nps' => $key->Calificacion);
				}
			}

			$nps_hoy = $this->Informe->get_nps_general_actual();
			foreach ($nps_hoy->result() as $key) {
				$total_encu = $key->Enc_0_a_6 + $key->Enc_7_a_8 + $key->Enc_9_a_10;
				$encuestade0a6 =  $key->Enc_0_a_6;
				$encuestade9a10 = $key->Enc_9_a_10;

				if ($encuestade0a6 == 0 || $encuestade9a10 == 0) {
					/**
					 * quitar
					 */
					$nps = 0;
					$porcen_0a6 = 0;
					$porcen_7a8 = 0;
					$porcen_9a10 = 0;
					$point1[] = array('label' => $key->mes_ano, 'y' => $key->Enc_0_a_6);
					$point2[] = array('label' => $key->mes_ano, 'y' => $key->Enc_7_a_8);
					$point3[] = array('label' => $key->mes_ano, 'y' => $key->Enc_9_a_10);
					$data_table_nps[] = array('mes_ano' => $key->mes_ano, 'nps' => $key->Calificacion);
					/**
					 * quitar
					 */
				} else {
					$nps = (($key->Enc_9_a_10 - $key->Enc_0_a_6) / $total_encu) * 100;
					$porcen_0a6 = ($key->Enc_0_a_6 * $nps) / $total_encu;
					$porcen_7a8 = ($key->Enc_7_a_8 * $nps) / $total_encu;
					$porcen_9a10 = ($key->Enc_9_a_10 * $nps) / $total_encu;
					$point1[] = array('label' => $key->mes_ano, 'y' => $key->Enc_0_a_6);
					$point2[] = array('label' => $key->mes_ano, 'y' => $key->Enc_7_a_8);
					$point3[] = array('label' => $key->mes_ano, 'y' => $key->Enc_9_a_10);
					$data_table_nps[] = array('mes_ano' => $key->mes_ano, 'nps' => $key->Calificacion);
				}
				for ($i = 12; $i >= 0; $i--) {
					$data_tabla[] = array('mes_ano' => $data_table_nps[$i]['mes_ano'], 'nps' => $data_table_nps[$i]['nps']);
					$point11[] = array('label' => $point1[$i]['label'], 'y' => $point1[$i]['y']);
					$point22[] = array('label' => $point2[$i]['label'], 'y' => $point2[$i]['y']);
					$point33[] = array('label' => $point3[$i]['label'], 'y' => $point3[$i]['y']);
				}
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'tabla_nps' => $data_tabla, 'point11' => $point11, 'point22' => $point22, 'point33' => $point33);
			//print_r($arr_user);
			$this->load->view("Informe_nps_colmotores_historico", $arr_user);
		}
	}

	public function detalle_nps_int_sede()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('presupuesto');
			$this->load->model('nominas');
			$this->load->model('Informe');

			//obtenemos primer y ultimo dia del mes actual
			$fecha_ini = $this->nominas->obtener_primer_dia_mes();
			$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//se obtienen los operarios por tipo de operacion
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			/*CALCULO DEL PORCENTAJE DE NPS POR SEDES*/
			$data_nps_int = array();
			$bod = "1,9,11,21,7,6,19,8,14,16,22";
			$bod_giron = "1,9,11,21";
			$bod_rosita = "7";
			$bod_barranca = "6,19";
			$bod_bocono = "8,14,16,22";
			/*GENERAL*/
			$nps_int = 0;
			$enc0a6 = 0;
			$enc7a8 = 0;
			$enc9a10 = 0;
			$nps_int_gen = $this->Informe->get_data_nps_interno_sedes($bod);
			$data_nps_int = array();
			$to_enc = 0;
			foreach ($nps_int_gen->result() as $key) {
				$enc0a6 += $key->enc0a6;
				$enc7a8 += $key->enc7a8;
				$enc9a10 += $key->enc9a10;
				$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
			}
			if ($enc0a6 != 0 || $enc7a8 != 0 || $enc9a10 != 0) {
				$porcen_int_06 = ($enc0a6 * 100) / $to_enc;
				$porcen_int_78 = ($enc7a8 * 100) / $to_enc;
				$porcen_int_910 = ($enc9a10 * 100) / $to_enc;
				$nps_int_gene = ((($enc9a10 - $enc0a6) / $to_enc) * 100);
			} else {
				$porcen_int_06 = 0;
				$porcen_int_78 = 0;
				$porcen_int_910 = 0;
				$nps_int_gene = 0;
			}

			$data_nps_int_gen[] = array('nps' => $nps_int_gene, 'enc06' => $enc0a6, 'enc78' => $enc7a8, 'enc910' => $enc9a10, 'to_enc' => $to_enc, "porcen_06" => $porcen_int_06, "porcen_78" => $porcen_int_78, "porcen_910" => $porcen_int_910);
			/*GIRON*/
			$nps_int = 0;
			$enc0a6 = 0;
			$enc7a8 = 0;
			$enc9a10 = 0;
			$nps_int_gen = $this->Informe->get_data_nps_interno_sedes($bod_giron);

			foreach ($nps_int_gen->result() as $key) {
				$enc0a6 += $key->enc0a6;
				$enc7a8 += $key->enc7a8;
				$enc9a10 += $key->enc9a10;
				$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
			}

			if ($enc0a6 != 0 || $enc7a8 != 0 || $enc9a10 != 0) {
				$porcen_int_06 = ($enc0a6 * 100) / $to_enc;
				$porcen_int_78 = ($enc7a8 * 100) / $to_enc;
				$porcen_int_910 = ($enc9a10 * 100) / $to_enc;
				$nps_int = ((($enc9a10 - $enc0a6) / $to_enc) * 100);
			} else {
				$porcen_int_06 = 0;
				$porcen_int_78 = 0;
				$porcen_int_910 = 0;
				$nps_int = 0;
			}
			$data_nps_int[] = array('nps' => $nps_int, 'enc06' => $enc0a6, 'enc78' => $enc7a8, 'enc910' => $enc9a10, 'to_enc' => $to_enc, "porcen_06" => $porcen_int_06, "porcen_78" => $porcen_int_78, "porcen_910" => $porcen_int_910, "sede" => "Giron");
			/*ROSITA*/
			$nps_int = 0;
			$enc0a6 = 0;
			$enc7a8 = 0;
			$enc9a10 = 0;
			$nps_int_gen = $this->Informe->get_data_nps_interno_sedes($bod_rosita);

			foreach ($nps_int_gen->result() as $key) {
				$enc0a6 += $key->enc0a6;
				$enc7a8 += $key->enc7a8;
				$enc9a10 += $key->enc9a10;
				$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
			}

			if ($enc0a6 != 0 || $enc7a8 != 0 || $enc9a10 != 0) {
				$porcen_int_06 = ($enc0a6 * 100) / $to_enc;
				$porcen_int_78 = ($enc7a8 * 100) / $to_enc;
				$porcen_int_910 = ($enc9a10 * 100) / $to_enc;
				$nps_int = ((($enc9a10 - $enc0a6) / $to_enc) * 100);
			} else {
				$porcen_int_06 = 0;
				$porcen_int_78 = 0;
				$porcen_int_910 = 0;
				$nps_int = 0;
			}
			$data_nps_int[] = array('nps' => $nps_int, 'enc06' => $enc0a6, 'enc78' => $enc7a8, 'enc910' => $enc9a10, 'to_enc' => $to_enc, "porcen_06" => $porcen_int_06, "porcen_78" => $porcen_int_78, "porcen_910" => $porcen_int_910, "sede" => "La Rosita");
			/*BARRANCA*/
			$nps_int = 0;
			$enc0a6 = 0;
			$enc7a8 = 0;
			$enc9a10 = 0;
			$nps_int_gen = $this->Informe->get_data_nps_interno_sedes($bod_barranca);

			foreach ($nps_int_gen->result() as $key) {
				$enc0a6 += $key->enc0a6;
				$enc7a8 += $key->enc7a8;
				$enc9a10 += $key->enc9a10;
				$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
			}

			if ($enc0a6 != 0 || $enc7a8 != 0 || $enc9a10 != 0) {
				$porcen_int_06 = ($enc0a6 * 100) / $to_enc;
				$porcen_int_78 = ($enc7a8 * 100) / $to_enc;
				$porcen_int_910 = ($enc9a10 * 100) / $to_enc;
				$nps_int = ((($enc9a10 - $enc0a6) / $to_enc) * 100);
			} else {
				$porcen_int_06 = 0;
				$porcen_int_78 = 0;
				$porcen_int_910 = 0;
				$nps_int = 0;
			}


			$data_nps_int[] = array('nps' => $nps_int, 'enc06' => $enc0a6, 'enc78' => $enc7a8, 'enc910' => $enc9a10, 'to_enc' => $to_enc, "porcen_06" => $porcen_int_06, "porcen_78" => $porcen_int_78, "porcen_910" => $porcen_int_910, "sede" => "Barrancabermeja");
			/*BOCONO*/
			$nps_int = 0;
			$enc0a6 = 0;
			$enc7a8 = 0;
			$enc9a10 = 0;
			$nps_int_gen = $this->Informe->get_data_nps_interno_sedes($bod_bocono);

			foreach ($nps_int_gen->result() as $key) {
				$enc0a6 += $key->enc0a6;
				$enc7a8 += $key->enc7a8;
				$enc9a10 += $key->enc9a10;
				$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
			}

			if ($enc0a6 != 0 || $enc7a8 != 0 || $enc9a10 != 0) {
				$porcen_int_06 = ($enc0a6 * 100) / $to_enc;
				$porcen_int_78 = ($enc7a8 * 100) / $to_enc;
				$porcen_int_910 = ($enc9a10 * 100) / $to_enc;
				$nps_int = ((($enc9a10 - $enc0a6) / $to_enc) * 100);
			} else {
				$porcen_int_06 = 0;
				$porcen_int_78 = 0;
				$porcen_int_910 = 0;
				$nps_int = 0;
			}

			$data_nps_int[] = array('nps' => $nps_int, 'enc06' => $enc0a6, 'enc78' => $enc7a8, 'enc910' => $enc9a10, 'to_enc' => $to_enc, "porcen_06" => $porcen_int_06, "porcen_78" => $porcen_int_78, "porcen_910" => $porcen_int_910, "sede" => "Cucuta Boconó");
			//ARRAY DE INFO PARA LA VISTA
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "data_nps_int" => $data_nps_int, "data_nps_gen" => $data_nps_int_gen);
			//abrimos la vista
			$this->load->view("Informe_nps_int_sede", $arr_user);
		}
	}

	public function detalle_nps_int_tecnicos()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('presupuesto');
			$this->load->model('nominas');
			$this->load->model('Informe');
			$sede = $this->input->GET('sede');
			$fecha = "";
			//obtenemos primer y ultimo dia del mes actual
			$fecha_ini = $this->nominas->obtener_primer_dia_mes();
			$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}

			/*CALCULO DE NPS INTERNO POR TECNICO*/
			$bod_giron = "1,9,11,21";
			$bod_rosita = "7";
			$bod_barranca = "6,19";
			$bod_bocono = "8,14,16,22";
			//$data[] = null;
			switch ($sede) {
				case 'giron':
					$nps_int_gen = $this->Informe->get_data_nps_interno_sedes($bod_giron);
					foreach ($nps_int_gen->result() as $key) {
						$data[] = array('nombres' => $key->tecnico, 'enc06' => $key->enc0a6, 'enc78' => $key->enc7a8, 'enc910' => $key->enc9a10);
					}
					$nps_int = 0;
					$enc0a6 = 0;
					$enc7a8 = 0;
					$enc9a10 = 0;
					$to_enc = 0;
					foreach ($nps_int_gen->result() as $key) {
						$enc0a6 += $key->enc0a6;
						$enc7a8 += $key->enc7a8;
						$enc9a10 += $key->enc9a10;
						$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
					}
					if ($enc0a6 != 0 || $enc7a8 != 0 || $enc9a10 != 0) {
						$porcen_int_06 = ($enc0a6 * 100) / $to_enc;
						$porcen_int_78 = ($enc7a8 * 100) / $to_enc;
						$porcen_int_910 = ($enc9a10 * 100) / $to_enc;
						$nps_int = ((($enc9a10 - $enc0a6) / $to_enc) * 100);
					} else {
						$porcen_int_06 = 0;
						$porcen_int_78 = 0;
						$porcen_int_910 = 0;
						$nps_int = 0;
					}
					$data_nps_int[] = array('nps' => $nps_int, 'enc06' => $enc0a6, 'enc78' => $enc7a8, 'enc910' => $enc9a10, 'to_enc' => $to_enc, "porcen_06" => $porcen_int_06, "porcen_78" => $porcen_int_78, "porcen_910" => $porcen_int_910, "sede" => $sede);
					break;
				case 'rosita':
					$nps_int_gen = $this->Informe->get_data_nps_interno_sedes($bod_rosita);
					foreach ($nps_int_gen->result() as $key) {
						$data[] = array('nombres' => $key->tecnico, 'enc06' => $key->enc0a6, 'enc78' => $key->enc7a8, 'enc910' => $key->enc9a10);
					}
					$nps_int = 0;
					$enc0a6 = 0;
					$enc7a8 = 0;
					$enc9a10 = 0;

					$to_enc = 0;
					foreach ($nps_int_gen->result() as $key) {
						$enc0a6 += $key->enc0a6;
						$enc7a8 += $key->enc7a8;
						$enc9a10 += $key->enc9a10;
						$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
					}


					if ($enc0a6 != 0 || $enc7a8 != 0 || $enc9a10 != 0) {
						$porcen_int_06 = ($enc0a6 * 100) / $to_enc;
						$porcen_int_78 = ($enc7a8 * 100) / $to_enc;
						$porcen_int_910 = ($enc9a10 * 100) / $to_enc;
						$nps_int = ((($enc9a10 - $enc0a6) / $to_enc) * 100);
					} else {
						$porcen_int_06 = 0;
						$porcen_int_78 = 0;
						$porcen_int_910 = 0;
						$nps_int = 0;
					}

					$data_nps_int[] = array('nps' => $nps_int, 'enc06' => $enc0a6, 'enc78' => $enc7a8, 'enc910' => $enc9a10, 'to_enc' => $to_enc, "porcen_06" => $porcen_int_06, "porcen_78" => $porcen_int_78, "porcen_910" => $porcen_int_910, "sede" => $sede);
					break;
				case 'barranca':
					$nps_int_gen = $this->Informe->get_data_nps_interno_sedes($bod_barranca);
					foreach ($nps_int_gen->result() as $key) {
						$data[] = array('nombres' => $key->tecnico, 'enc06' => $key->enc0a6, 'enc78' => $key->enc7a8, 'enc910' => $key->enc9a10);
					}
					$nps_int = 0;
					$enc0a6 = 0;
					$enc7a8 = 0;
					$enc9a10 = 0;

					$to_enc = 0;
					foreach ($nps_int_gen->result() as $key) {
						$enc0a6 += $key->enc0a6;
						$enc7a8 += $key->enc7a8;
						$enc9a10 += $key->enc9a10;
						$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
					}


					if ($enc0a6 != 0 || $enc7a8 != 0 || $enc9a10 != 0) {
						$porcen_int_06 = ($enc0a6 * 100) / $to_enc;
						$porcen_int_78 = ($enc7a8 * 100) / $to_enc;
						$porcen_int_910 = ($enc9a10 * 100) / $to_enc;
						$nps_int = ((($enc9a10 - $enc0a6) / $to_enc) * 100);
					} else {
						$porcen_int_06 = 0;
						$porcen_int_78 = 0;
						$porcen_int_910 = 0;
						$nps_int = 0;
					}

					$data_nps_int[] = array('nps' => $nps_int, 'enc06' => $enc0a6, 'enc78' => $enc7a8, 'enc910' => $enc9a10, 'to_enc' => $to_enc, "porcen_06" => $porcen_int_06, "porcen_78" => $porcen_int_78, "porcen_910" => $porcen_int_910, "sede" => $sede);
					break;
				case 'bocono':
					$nps_int_gen = $this->Informe->get_data_nps_interno_sedes($bod_bocono);

					foreach ($nps_int_gen->result() as $key) {
						$data[] = array('nombres' => $key->tecnico, 'enc06' => $key->enc0a6, 'enc78' => $key->enc7a8, 'enc910' => $key->enc9a10);
					}
					$nps_int = 0;
					$enc0a6 = 0;
					$enc7a8 = 0;
					$enc9a10 = 0;

					$to_enc = 0;
					foreach ($nps_int_gen->result() as $key) {
						$enc0a6 += $key->enc0a6;
						$enc7a8 += $key->enc7a8;
						$enc9a10 += $key->enc9a10;
						$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
					}


					if ($enc0a6 != 0 || $enc7a8 != 0 || $enc9a10 != 0) {
						$porcen_int_06 = ($enc0a6 * 100) / $to_enc;
						$porcen_int_78 = ($enc7a8 * 100) / $to_enc;
						$porcen_int_910 = ($enc9a10 * 100) / $to_enc;
						$nps_int = ((($enc9a10 - $enc0a6) / $to_enc) * 100);
					} else {
						$porcen_int_06 = 0;
						$porcen_int_78 = 0;
						$porcen_int_910 = 0;
						$nps_int = 0;
					}

					$data_nps_int[] = array('nps' => $nps_int, 'enc06' => $enc0a6, 'enc78' => $enc7a8, 'enc910' => $enc9a10, 'to_enc' => $to_enc, "porcen_06" => $porcen_int_06, "porcen_78" => $porcen_int_78, "porcen_910" => $porcen_int_910, "sede" => $sede);
					break;
			}

			//ARRAY DE LA INFO DE LA VISTA
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'data_tabla' => $data, 'data_nps_int' => $data_nps_int, 'sede' => $sede, 'data_tec' => $nps_int_gen, 'fecha' => $fecha);
			//abrimos la vista
			$this->load->view("detalle_nps_int_tec", $arr_user);
		}
	}

	public function detalle_nps_tecnico_pac()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("location: " . base_url());
		} else {
			//llamamos los modelos necesarios
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('encuestas');
			//PARAMETROS POR URL
			$nombre = $this->input->GET('nombre');
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//DATOS DEL DETALLE DE LA ORDEN
			$data_respuestas = $this->encuestas->Informe_encuesta_satisfaccion_by_nom($nombre);
			//datos de cliente y tecnico
			//id usuario
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//array para la vits
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'data_respuestas' => $data_respuestas);
			//abrimos la vista
			$this->load->view('detalle_encuesta_satisfaccion_tec', $arr_user);
		}
	}

	public function Informe_pac_detalle_tecnico_byFecha()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("location: " . base_url());
		} else {
			//llamamos los modelos necesarios
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Informe');
			//PARAMETROS POR URL
			$fecha = $this->input->GET('fecha');
			$sede = $this->input->GET('sede');
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			/*DATA TECNICOS*/
			$tecnicos = $this->Informe->get_data_tecnicos_by_dia($sede, $fecha);
			$point1 = array();
			$point2 = array();
			$point3 = array();
			foreach ($tecnicos->result() as $key) {
				$total_encu = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
				$nps = (($key->enc9a10 - $key->enc0a6) / $total_encu) * 100;
				//$nps1 = (($key->Enc_9_a_10 - $key->Enc_0_a_6) / $total_encu)*100;
				$porcen_0a6 = ($key->enc0a6 * $nps) / $total_encu;
				$porcen_7a8 = ($key->enc7a8 * $nps) / $total_encu;
				$porcen_9a10 = ($key->enc9a10 * $nps) / $total_encu;
				$point1[] = array('label' => $key->nombres, 'y' => $porcen_0a6);
				$point2[] = array('label' => $key->nombres, 'y' => $porcen_7a8);
				$point3[] = array('label' => $key->nombres, 'y' => $porcen_9a10);
			}
			/*NPS SEDE*/
			$dat_sede = $this->Informe->get_calificacion_sede_by_dia($sede, $fecha);
			//id usuario
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//array para la vits
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'point1' => $point1, 'point2' => $point2, 'point3' => $point3, 'sede' => $sede, 'fecha' => $fecha, 'data_sede' => $dat_sede, 'tecnicos' => $tecnicos);
			//abrimos la vista
			$this->load->view('Informe_pac_detalle_tecnico', $arr_user);
		}
	}

	public function nps_det_tec()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu);
			//abrimos la vista
			$this->load->view("Informe_nps_detalle_tec", $arr_user);
		}
	}

	public function listar_nps_tec()
	{
		$this->load->model('Informe');
		//BODEGAS
		$bod_todas = "1,9,11,21,7,6,19,8,14,16,22";
		$bod_giron = "1,9,11,21";
		$bod_rosita = "7";
		$bod_barranca = "6,19";
		$bod_bocono = "8,14,16,22";
		$var = $this->input->GET('var');
		$mes = $this->input->GET('mes');
		$nom_mes = "";
		if ($mes == "0") {
			$mes = "1,2,3,4,5,6,7,8,9,10,11,12";
			$nom_mes = "Todos";
		}
		if ($mes == "1") {
			$nom_mes = "Enero";
		}
		if ($mes == "2") {
			$nom_mes = "Febrero";
		}
		if ($mes == "3") {
			$nom_mes = "Marzo";
		}
		if ($mes == "4") {
			$nom_mes = "Abril";
		}
		if ($mes == "5") {
			$nom_mes = "Mayo";
		}
		if ($mes == "6") {
			$nom_mes = "Junio";
		}
		if ($mes == "7") {
			$nom_mes = "Julio";
		}
		if ($mes == "8") {
			$nom_mes = "Agosto";
		}
		if ($mes == "9") {
			$nom_mes = "Septiembre";
		}
		if ($mes == "10") {
			$nom_mes = "Octubre";
		}
		if ($mes == "11") {
			$nom_mes = "Noviembre";
		}
		if ($mes == "12") {
			$nom_mes = "Diciembre";
		}
		switch ($var) {
			case '1':
				$nps_int_gen = $this->Informe->get_data_nps_interno_sedes_by_mes($bod_todas, $mes);
				foreach ($nps_int_gen->result() as $key) {
					$to_enc = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
					$nps = ((($key->enc9a10 - $key->enc0a6) / $to_enc) * 100);
					$color = "";
					if ($nps < 0) {
						$color = "table-danger";
					} elseif ($nps > 0 && $nps < 75) {
						$color = "table-warning";
					} elseif ($nps >= 75 && $nps <= 100) {
						$color = "table-success";
					}
					echo '
				<tr class="' . $color . '">
				<th scope="row">' . $key->nombres . '</th>
				<td>' . round($nps) . '%</td>
				<td>' . $key->enc0a6 . '</td>
				<td>' . $key->enc7a8 . '</td>
				<td>' . $key->enc9a10 . '</td>
				<td>' . $nom_mes . '</td>
				<td>
				<a href="' . base_url() . 'encuesta/detalle_encuesta_satisfaccion?ot=' . $key->n_orden . '" class="btn btn-outline-info btn-sm"><i class="fas fa-eye"></i></a>
			
				</tr>
				';
				}
				break;
			case '2':
				$nps_int_gen = $this->Informe->get_data_nps_interno_sedes_by_mes($bod_giron, $mes);
				foreach ($nps_int_gen->result() as $key) {
					$to_enc = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
					$nps = ((($key->enc9a10 - $key->enc0a6) / $to_enc) * 100);
					$color = "";
					if ($nps < 0) {
						$color = "table-danger";
					} elseif ($nps > 0 && $nps < 75) {
						$color = "table-warning";
					} elseif ($nps >= 75 && $nps <= 100) {
						$color = "table-success";
					}
					echo '
				<tr class="' . $color . '">
				<th scope="row">' . $key->nombres . '</th>
				<td>' . round($nps) . '%</td>
				<td>' . $key->enc0a6 . '</td>
				<td>' . $key->enc7a8 . '</td>
				<td>' . $key->enc9a10 . '</td>
				<td>' . $nom_mes . '</td>
				<td><a href="' . base_url() . 'encuesta/detalle_encuesta_satisfaccion?ot=' . $key->n_orden . '" class="btn btn-outline-info btn-sm"><i class="fas fa-eye"></i></a></td>
				</tr>
				';
				}
				//<td><button type="button" onclick="traerdatos(' . $key->id . ',' . $key->nit . ',' . $key->n_orden . ')" class= "btn btn-outline-info" data-toggle="modal" data-target=".bd-example-modal-xl"> <spam><i class="fas fa-eye"></i> </spam></button></td>
				break;
			case '3':
				$nps_int_gen = $this->Informe->get_data_nps_interno_sedes_by_mes($bod_rosita, $mes);
				foreach ($nps_int_gen->result() as $key) {
					$to_enc = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
					$nps = ((($key->enc9a10 - $key->enc0a6) / $to_enc) * 100);
					$color = "";
					if ($nps < 0) {
						$color = "table-danger";
					} elseif ($nps > 0 && $nps < 75) {
						$color = "table-warning";
					} elseif ($nps >= 75 && $nps <= 100) {
						$color = "table-success";
					}
					echo '
				<tr class="' . $color . '">
				<th scope="row">' . $key->nombres . '</th>
				<td>' . round($nps) . '%</td>
				<td>' . $key->enc0a6 . '</td>
				<td>' . $key->enc7a8 . '</td>
				<td>' . $key->enc9a10 . '</td>
				<td>' . $nom_mes . '</td>
				<td><a href="' . base_url() . 'encuesta/detalle_encuesta_satisfaccion?ot=' . $key->n_orden . '" class="btn btn-outline-info btn-sm"><i class="fas fa-eye"></i></a></td>
				</tr>
				';
				}
				break;
			case '4':
				$nps_int_gen = $this->Informe->get_data_nps_interno_sedes_by_mes($bod_bocono, $mes);
				foreach ($nps_int_gen->result() as $key) {
					$to_enc = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
					$nps = ((($key->enc9a10 - $key->enc0a6) / $to_enc) * 100);
					$color = "";
					if ($nps < 0) {
						$color = "table-danger";
					} elseif ($nps > 0 && $nps < 75) {
						$color = "table-warning";
					} elseif ($nps >= 75 && $nps <= 100) {
						$color = "table-success";
					}
					echo '
				<tr class="' . $color . '">
				<th scope="row">' . $key->nombres . '</th>
				<td>' . round($nps) . '%</td>
				<td>' . $key->enc0a6 . '</td>
				<td>' . $key->enc7a8 . '</td>
				<td>' . $key->enc9a10 . '</td>
				<td>' . $nom_mes . '</td>	
				<td><a href="' . base_url() . 'encuesta/detalle_encuesta_satisfaccion?ot=' . $key->n_orden . '" class="btn btn-outline-info btn-sm"><i class="fas fa-eye"></i></a></td>
				</tr>
				';
				}
				break;
			case '5';
				$nps_int_gen = $this->Informe->get_data_nps_interno_sedes_by_mes($bod_barranca, $mes);
				foreach ($nps_int_gen->result() as $key) {
					$to_enc = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
					$nps = ((($key->enc9a10 - $key->enc0a6) / $to_enc) * 100);
					$color = "";
					if ($nps < 0) {
						$color = "table-danger";
					} elseif ($nps > 0 && $nps < 75) {
						$color = "table-warning";
					} elseif ($nps >= 75 && $nps <= 100) {
						$color = "table-success";
					}
					echo '
				<tr class="' . $color . '">
				<th scope="row">' . $key->nombres . '</th>
				<td>' . round($nps) . '%</td>
				<td>' . $key->enc0a6 . '</td>
				<td>' . $key->enc7a8 . '</td>
				<td>' . $key->enc9a10 . '</td>
				<td>' . $nom_mes . '</td>
				<td><a href="' . base_url() . 'encuesta/detalle_encuesta_satisfaccion?ot=' . $key->n_orden . '" class="btn btn-outline-info btn-sm"><i class="fas fa-eye"></i></a></td>
				</tr>
				';
				}
				break;
			case '6';

			default:
				# code...
				break;
		}
	}



	public function listar_nps_tec_col()
	{
		$this->load->model('Informe');
		/*Se obtiene el anio*/
		$year = date('Y');
		//echo $year;die;
		$var = $this->input->GET('var');
		$mes = $this->input->GET('mes');
		$nom_mes = "";
		if ($mes == "0") {
			$mes = "1,2,3,4,5,6,7,8,9,10,11,12";
			$nom_mes = "Todos";
		}
		if ($mes == "1") {
			$nom_mes = "Enero";
		}
		if ($mes == "2") {
			$nom_mes = "Febrero";
		}
		if ($mes == "3") {
			$nom_mes = "Marzo";
		}
		if ($mes == "4") {
			$nom_mes = "Abril";
		}
		if ($mes == "5") {
			$nom_mes = "Mayo";
		}
		if ($mes == "6") {
			$nom_mes = "Junio";
		}
		if ($mes == "7") {
			$nom_mes = "Julio";
		}
		if ($mes == "8") {
			$nom_mes = "Agosto";
		}
		if ($mes == "9") {
			$nom_mes = "Septiembre";
		}
		if ($mes == "10") {
			$nom_mes = "Octubre";
		}
		if ($mes == "11") {
			$nom_mes = "Noviembre";
		}
		if ($mes == "12") {
			$nom_mes = "Diciembre";
		}
		switch ($var) {
			case '1':
				$nps_int_gen = $this->Informe->get_nps_tec_col_all($mes, $year);
				foreach ($nps_int_gen->result() as $key) {
					$to_enc = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
					$nps = ((($key->enc9a10 - $key->enc0a6) / $to_enc) * 100);
					$color = "";
					if ($nps < 0) {
						$color = "table-danger";
					} elseif ($nps > 0 && $nps < 75) {
						$color = "table-warning";
					} elseif ($nps >= 75 && $nps <= 100) {
						$color = "table-success";
					}
					echo '
				<tr class="' . $color . '">
				<th scope="row">' . $key->nombres . '</th>
				<td>' . round($nps) . '%</td>
				<td>' . $key->enc0a6 . '</td>
				<td>' . $key->enc7a8 . '</td>
				<td>' . $key->enc9a10 . '</td>
				<td>' . $nom_mes . '</td>
				<td> <button class= "btn btn-outline-info" disabled data-toggle="modal" data-target=".bd-example-modal-xl"> <spam><i class="fas fa-eye"></i> </spam></button> </td>
				</tr>
				';
				}
				break;
			case '2':
				$nps_int_gen = $this->Informe->get_nps_tec_col('giron', $mes, $year);
				foreach ($nps_int_gen->result() as $key) {
					$to_enc = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
					$nps = ((($key->enc9a10 - $key->enc0a6) / $to_enc) * 100);
					$color = "";
					if ($nps < 0) {
						$color = "table-danger";
					} elseif ($nps > 0 && $nps < 75) {
						$color = "table-warning";
					} elseif ($nps >= 75 && $nps <= 100) {
						$color = "table-success";
					}
					echo '
				<tr class="' . $color . '">
				<th scope="row">' . $key->nombres . '</th>
				<td>' . round($nps) . '%</td>
				<td>' . $key->enc0a6 . '</td>
				<td>' . $key->enc7a8 . '</td>
				<td>' . $key->enc9a10 . '</td>
				<td>' . $nom_mes . '</td>
				<td> <button disabled class= "btn btn-outline-info" data-toggle="modal" data-target=".bd-example-modal-xl"> <spam><i class="fas fa-eye"></i> </spam></button> </td>
				</tr>
				';
				}
				break;
			case '3':
				$nps_int_gen = $this->Informe->get_nps_tec_col('rosita', $mes, $year);
				foreach ($nps_int_gen->result() as $key) {
					$to_enc = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
					$nps = ((($key->enc9a10 - $key->enc0a6) / $to_enc) * 100);
					$color = "";
					if ($nps < 0) {
						$color = "table-danger";
					} elseif ($nps > 0 && $nps < 75) {
						$color = "table-warning";
					} elseif ($nps >= 75 && $nps <= 100) {
						$color = "table-success";
					}
					echo '
				<tr class="' . $color . '">
				<th scope="row">' . $key->nombres . '</th>
				<td>' . round($nps) . '%</td>
				<td>' . $key->enc0a6 . '</td>
				<td>' . $key->enc7a8 . '</td>
				<td>' . $key->enc9a10 . '</td>
				<td>' . $nom_mes . '</td>
				<td> <button disabled class= "btn btn-outline-info" data-toggle="modal" data-target=".bd-example-modal-xl"> <spam><i class="fas fa-eye"></i> </spam></button> </td>
				</tr>
				';
				}
				break;
			case '4':
				$nps_int_gen = $this->Informe->get_nps_tec_col('bocono', $mes, $year);
				foreach ($nps_int_gen->result() as $key) {
					$to_enc = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
					$nps = ((($key->enc9a10 - $key->enc0a6) / $to_enc) * 100);
					$color = "";
					if ($nps < 0) {
						$color = "table-danger";
					} elseif ($nps > 0 && $nps < 75) {
						$color = "table-warning";
					} elseif ($nps >= 75 && $nps <= 100) {
						$color = "table-success";
					}
					echo '
				<tr class="' . $color . '">
				<th scope="row">' . $key->nombres . '</th>
				<td>' . round($nps) . '%</td>
				<td>' . $key->enc0a6 . '</td>
				<td>' . $key->enc7a8 . '</td>
				<td>' . $key->enc9a10 . '</td>
				<td>' . $nom_mes . '</td>
				<td> <button disabled class= "btn btn-outline-info" data-toggle="modal" data-target=".bd-example-modal-xl"> <spam><i class="fas fa-eye"></i> </spam></button> </td>
				</tr>
				';
				}
				break;
			case '5';
				$nps_int_gen = $this->Informe->get_nps_tec_col('barranca', $mes, $year);
				foreach ($nps_int_gen->result() as $key) {
					$to_enc = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
					$nps = ((($key->enc9a10 - $key->enc0a6) / $to_enc) * 100);
					$color = "";
					if ($nps < 0) {
						$color = "table-danger";
					} elseif ($nps > 0 && $nps < 75) {
						$color = "table-warning";
					} elseif ($nps >= 75 && $nps <= 100) {
						$color = "table-success";
					}
					echo '
				<tr class="' . $color . '">
				<th scope="row">' . $key->nombres . '</th>
				<td>' . round($nps) . '%</td>
				<td>' . $key->enc0a6 . '</td>
				<td>' . $key->enc7a8 . '</td>
				<td>' . $key->enc9a10 . '</td>
				<td>' . $nom_mes . '</td>
				<td> <button disabled class= "btn btn-outline-info" data-toggle="modal" data-target=".bd-example-modal-xl"> <spam><i class="fas fa-eye"></i> </spam></button> </td>
				</tr>
				';
				}
				break;

			default:
				# code...
				break;
		}
	}

	public function Informe_mantenimiento_prepagado()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Informe');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}

			/*LOGICA MANTENIMENTO PREPAGADO*/

			$mant_prep = $this->Informe->get_mant_prepagado();


			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'mant_prep' => $mant_prep);
			//abrimos la vista
			$this->load->view("Informe_taller/mant_prepagado", $arr_user);
		}
	}

	/*****************************************************Informe NPS Y PQR*****************************************************/

	public function Informe_pqr_nps()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('encuestas');
			//$this->load->model('Informe');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			/*ENCUESTAS DE GM*/
			$cerrado = "";
			$tecnico = $this->encuestas->traertecnicos();
			$gm_enc = $this->encuestas->get_encuestas_gm($cerrado);
			$pqr_cod = $this->encuestas->get_pqr_codiesel($cerrado);
			$pqr_intt = $this->encuestas->get_encuestas_codi($cerrado);
			//nuevo
			$pqr_qr = $this->encuestas->get_encuesta_qr($cerrado);
			//print_r($pqr_intt->result());die;
			$arr_user = array(
				'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'nps_gm' => $gm_enc,
				'pqr_cod' => $pqr_cod, 'pqr_int' => $pqr_intt, 'tecnico' => $tecnico, 'qr' => $pqr_qr
			);
			//abrimos la vista
			$this->load->view("Informe_pqr_nps/Informe_pqr_nps", $arr_user);
		}
	}

	public function crear_pqr()
	{
		//Llamamos los modelos
		$this->load->model('encuestas');
		$data = $this->input->POST();
		if ($this->encuestas->insert_pqr($data)) {
			echo 1;
		} else {
			echo 'err';
		}
	}

	public function crear_pqr_nps()
	{
		//Llamamos los modelos
		$this->load->model('encuestas');
		$fuente = $this->input->POST('fuentee');
		$id_fuente = $this->input->POST('id_fuente');
		$tecnico = $this->input->POST('tecnico');
		$tipificacion_encuesta = $this->input->POST('tipificacion_encuesta');
		$estado_caso = $this->input->POST('estado_caso');
		$comentarios_final_caso = $this->input->POST('comentarios_final_caso');
		$tipificacion_cierre = $this->input->POST('tipificacion_cierre');
		$data_pqr = array(
			'fuente' => $fuente, 'id_fuente' => $id_fuente, 'tecnico' => $tecnico, 'tipificacion_encuesta' => $tipificacion_encuesta, 'estado_caso' => $estado_caso,
			'comentarios_final_caso' => $comentarios_final_caso, 'tipificacion_cierre' => $tipificacion_cierre
		);
		//print_r($data);
		$res = $this->encuestas->get_pqr_nps_id($data_pqr);
		if ($res != null) {
			foreach ($res->result() as $row) {
				if ($this->encuestas->update_pqr_nps($row->id, $data_pqr)) {
					echo 1;
				} else {
					echo 'err';
				}
			}
		} else {
			if ($this->encuestas->insert_pqr_nps($data_pqr)) {
				echo 1;
			} else {
				echo 'err';
			}
		}
	}

	public function validar_cliente()
	{
		//Traemos los modelos
		$this->load->model('usuarios');
		//traemos los parametros de la vista
		$nit = $this->input->GET('nit');
		$data_cli = $this->usuarios->getUserByNit($nit)->nombres;
		echo $data_cli;
	}

	public function cargar_info_vh()
	{
		//Traemos los modelos
		$this->load->model('Informe');
		//traemos los parametros de la vista
		$placa = $this->input->GET('placa');
		$data_vh = $this->Informe->info_vh_Fby_placa($placa);
		$data[] = array('serie' => $data_vh->serie, 'modelo' => $data_vh->descripcion, 'nombres' => $data_vh->nombres, 'nit' => $data_vh->nit_comprador, 'mail' => $data_vh->mail, 'celular' => $data_vh->celular);
		echo json_encode($data);
	}

	public function crear_verb_pqr_nps()
	{
		//traemos los modelos
		$this->load->model('encuestas');
		//traemos los datos del formulario
		$data = $this->input->POST();
		//print_r($data);		
		if ($this->encuestas->insert_verbs($data)) {
			echo 1;
		} else {
			echo 0;
		}
	}

	public function get_data_pqr_nps()
	{
		//traemos los modelos
		$this->load->model('Informe');

		//traemos los datos del formulario
		$tipo = $this->input->POST('tipo');
		$id = $this->input->POST('id');

		$res = $this->Informe->val_data_pqr_nps($tipo, $id, 0);
		$resFull = $this->Informe->val_data_pqr_nps($tipo, $id, 1);

		if ($res != null || $resFull != null) {
			$arr_data[] = array(
				'fuente' => $res->fuente, 'estado' => $res->estado_caso,
				'tipificacion_encuesta' => $res->tipificacion_encuesta,
				'tipificacion_cierre' => $res->tipificacion_cierre,
				'comentarios_final_caso' => $res->comentarios_final_caso
			);
			echo json_encode($arr_data);
		} else {
			echo 0;
		}
	}

	public function get_list_verbs()
	{
		//traemos los modelos
		$this->load->model('Informe');

		//traemos los datos del formulario
		$id = $this->input->POST('id');

		$res = $this->Informe->data_list_verbs($id);

		if ($res != null) {
			foreach ($res->result() as $key) {
				$arr_data[] = array(
					'contacto' => $key->contacto, 'verbalizacion' => $key->verbalizacion,
					'fecha_contacto' => $key->fecha_contacto
				);
			}
			echo json_encode($arr_data);
		} else {
			echo 0;
		}
	}

	/*metodos para Informes de gestion de ventas */
	public function gestionventa()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu);
			//abrimos la vista
			$this->load->view("gestionVentas/index", $arr_user);
		}
	}



	/*Informe ENTRADA VH TALLER */


	public function Informe_entrada_vh()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('talleres');
			$mes = $this->input->POST("mes");
			$anio = $this->input->POST("anio");
			if (isset($mes) && isset($anio)) {
			} else {
				$mes = date('m');
				$anio = date('Y');
			}
			//echo $mes." ".$anio;
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$bods = "1,11,9,21";
			/*CALCULOS CITAS CUMPLIDAS*/
			$citas_asistidas = $this->talleres->get_citas_asistidas($bods, $mes, $anio)->citas_asistidas;
			$citas_agendadas = $this->talleres->get_citas_agendadas($bods, $mes, $anio)->citas_agendadas;
			$porcen_citas_cump = ($citas_asistidas / $citas_agendadas) * 100;
			$graf_citas_cumplidas[] = array("label" => "Citas Asistidas", "symbol" => "Asistidas", "y" => $citas_asistidas);
			$graf_citas_cumplidas[] = array("label" => "Citas Agendadas", "symbol" => "Agendadas", "y" => $citas_agendadas);
			/*CALCULO CUMPLIMIENTO CITAS*/
			$data_estado_citas = $this->talleres->get_estados_cita($bods, $mes, $anio);
			$atiempo = 0;
			$temprano = 0;
			$tarde = 0;
			foreach ($data_estado_citas->result() as $key) {
				if ($key->Estado_cita == 'A_tiempo') {
					$atiempo++;
				} else if ($key->Estado_cita == 'Llegó tarde') {
					$tarde++;
				} else if ($key->Estado_cita == 'Llegó Antes de Tiempo') {
					$temprano++;
				}
			}
			$porcen_atiempo = ($atiempo / $citas_asistidas) * 100;
			$porcen_temprano = ($temprano / $citas_asistidas) * 100;
			$porcen_tarde = ($tarde / $citas_asistidas) * 100;
			$graf_estado_cita[] = array("label" => "Temprano", "symbol" => "Temprano", "y" => $temprano);
			$graf_estado_cita[] = array("label" => "A Tiempo", "symbol" => "A Tiempo", "y" => $atiempo);
			$graf_estado_cita[] = array("label" => "Llegó Tarde", "symbol" => "Llegó Tarde", "y" => $tarde);
			/*CALCULO VH AGENDADOS*/
			$n_vh_sin_cita = count($this->talleres->get_vh_sin_citas($bods, $mes, $anio)->result());
			$to_ingresos = $n_vh_sin_cita + $citas_asistidas;
			$porcen_vh_agendados = ($citas_agendadas / $to_ingresos) * 100;
			$graf_vh_agen[] = array("label" => "Vh Sin Cita", "symbol" => "Sin Cita", "y" => $n_vh_sin_cita);
			$graf_vh_agen[] = array("label" => "Citas Agendadas", "symbol" => "Agendadas", "y" => $citas_agendadas);
			$graf_vh_agen[] = array("label" => "Citas Asistidas", "symbol" => "Asistidas", "y" => $citas_asistidas);
			/*ARRAY DATA PORCENTAJES*/
			$data_porcentajes = array('porcen_citas_cumplidas' => $porcen_citas_cump, 'atiempo' => $porcen_atiempo, 'temprano' => $porcen_temprano, 'tarde' => $porcen_tarde, 'porcen_vh_agendados' => $porcen_vh_agendados);

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'dat_porcen' => $data_porcentajes, 'graf_citas_cumplidas' => $graf_citas_cumplidas, 'graf_estado_cita' => $graf_estado_cita, 'graf_vh_agen' => $graf_vh_agen, 'mes' => $mes, 'anio' => $anio);
			//abrimos la vista
			$this->load->view("Informe_ent_taller/index", $arr_user);
		}
	}

	public function inf_citas_cumplidas()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('talleres');
			//Traemos los parametros de la url
			$mes = $this->input->GET("mes");
			$anio = $this->input->GET("anio");
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_bods = [1, 11, 9, 21];
			for ($i = 0; $i < count($arr_bods); $i++) {

				/*CALCULOS CITAS CUMPLIDAS*/
				$citas_asistidas = $this->talleres->get_citas_asistidas($arr_bods[$i], $mes, $anio)->citas_asistidas;
				$citas_agendadas = $this->talleres->get_citas_agendadas($arr_bods[$i], $mes, $anio)->citas_agendadas;
				if ($citas_asistidas == 0 || $citas_agendadas == 0) {
					$porcen_citas_cump = 0;
				} else {
					$porcen_citas_cump = ($citas_asistidas / $citas_agendadas) * 100;
				}
				//$porcen_citas_cump = ($citas_asistidas/$citas_agendadas)*100;
				$graf_citas_cumplidas[] = array("label" => "Citas Asistidas", "symbol" => "Asistidas", "y" => $citas_asistidas);
				$graf_citas_cumplidas[] = array("label" => "Citas Agendadas", "symbol" => "Agendadas", "y" => $citas_agendadas);
				/*CALCULO CUMPLIMIENTO CITAS*/
				$data_estado_citas = $this->talleres->get_estados_cita($arr_bods[$i], $mes, $anio);
				$atiempo = 0;
				$temprano = 0;
				$tarde = 0;
				foreach ($data_estado_citas->result() as $key) {
					if ($key->Estado_cita == 'A_tiempo') {
						$atiempo++;
					} else if ($key->Estado_cita == 'Llegó tarde') {
						$tarde++;
					} else if ($key->Estado_cita == 'Llegó Antes de Tiempo') {
						$temprano++;
					}
				}
				if ($citas_asistidas == 0) {
				} else {
					$porcen_atiempo = ($atiempo / $citas_asistidas) * 100;
					$porcen_temprano = ($temprano / $citas_asistidas) * 100;
					$porcen_tarde = ($tarde / $citas_asistidas) * 100;
				}

				$graf_estado_cita[] = array("label" => "Temprano", "symbol" => "Temprano", "y" => $temprano);
				$graf_estado_cita[] = array("label" => "A Tiempo", "symbol" => "A Tiempo", "y" => $atiempo);
				$graf_estado_cita[] = array("label" => "Llegó Tarde", "symbol" => "Llegó Tarde", "y" => $tarde);
				/*CALCULO VH AGENDADOS*/
				$n_vh_sin_cita = count($this->talleres->get_vh_sin_citas($arr_bods[$i], $mes, $anio)->result());
				$to_ingresos = $n_vh_sin_cita + $citas_asistidas;
				$porcen_vh_agendados = ($citas_agendadas / $to_ingresos) * 100;
				$graf_vh_agen[] = array("label" => "Vh Sin Cita", "symbol" => "Sin Cita", "y" => $n_vh_sin_cita);
				$graf_vh_agen[] = array("label" => "Citas Agendadas", "symbol" => "Agendadas", "y" => $citas_agendadas);
				$graf_vh_agen[] = array("label" => "Citas Asistidas", "symbol" => "Asistidas", "y" => $citas_asistidas);
				/*ARRAY DATA PORCENTAJES*/
				$data_porcentajes[] = array('porcen_citas_cumplidas' => $porcen_citas_cump, 'atiempo' => $porcen_atiempo, 'temprano' => $porcen_temprano, 'tarde' => $porcen_tarde, 'porcen_vh_agendados' => $porcen_vh_agendados, 'bod' => $arr_bods[$i], 'graf_citas_cumplidas' => $graf_citas_cumplidas, 'graf_estado_cita' => $graf_estado_cita, 'graf_vh_agen' => $graf_vh_agen);
				$graf_citas_cumplidas = null;
			}
			//print_r($data_porcentajes);die;
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'data' => $data_porcentajes, 'var' => "c");
			//abrimos la vista
			$this->load->view("Informe_ent_taller/citas_cumplidas", $arr_user);
		}
	}

	public function inf_cumplimiento_citas()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('talleres');
			//Traemos los parametros de la url
			$mes = $this->input->GET("mes");
			$anio = $this->input->GET("anio");
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_bods = [1, 11, 9, 21];
			for ($i = 0; $i < count($arr_bods); $i++) {

				/*CALCULOS CITAS CUMPLIDAS*/
				$citas_asistidas = $this->talleres->get_citas_asistidas($arr_bods[$i], $mes, $anio)->citas_asistidas;
				$citas_agendadas = $this->talleres->get_citas_agendadas($arr_bods[$i], $mes, $anio)->citas_agendadas;
				if ($citas_asistidas == 0 || $citas_agendadas == 0) {
					$porcen_citas_cump = 0;
				} else {
					$porcen_citas_cump = ($citas_asistidas / $citas_agendadas) * 100;
				}
				//$porcen_citas_cump = ($citas_asistidas/$citas_agendadas)*100;
				$graf_citas_cumplidas[] = array("label" => "Citas Asistidas", "symbol" => "Asistidas", "y" => $citas_asistidas);
				$graf_citas_cumplidas[] = array("label" => "Citas Agendadas", "symbol" => "Agendadas", "y" => $citas_agendadas);
				/*CALCULO CUMPLIMIENTO CITAS*/
				$data_estado_citas = $this->talleres->get_estados_cita($arr_bods[$i], $mes, $anio);
				$atiempo = 0;
				$temprano = 0;
				$tarde = 0;
				foreach ($data_estado_citas->result() as $key) {
					if ($key->Estado_cita == 'A_tiempo') {
						$atiempo++;
					} else if ($key->Estado_cita == 'Llegó tarde') {
						$tarde++;
					} else if ($key->Estado_cita == 'Llegó Antes de Tiempo') {
						$temprano++;
					}
				}
				if ($citas_asistidas == 0) {
				} else {
					$porcen_atiempo = ($atiempo / $citas_asistidas) * 100;
					$porcen_temprano = ($temprano / $citas_asistidas) * 100;
					$porcen_tarde = ($tarde / $citas_asistidas) * 100;
				}

				$graf_estado_cita[] = array("label" => "Temprano", "symbol" => "Temprano", "y" => $temprano);
				$graf_estado_cita[] = array("label" => "A Tiempo", "symbol" => "A Tiempo", "y" => $atiempo);
				$graf_estado_cita[] = array("label" => "Llegó Tarde", "symbol" => "Llegó Tarde", "y" => $tarde);
				/*CALCULO VH AGENDADOS*/
				$n_vh_sin_cita = count($this->talleres->get_vh_sin_citas($arr_bods[$i], $mes, $anio)->result());
				$to_ingresos = $n_vh_sin_cita + $citas_asistidas;
				$porcen_vh_agendados = ($citas_agendadas / $to_ingresos) * 100;
				$graf_vh_agen[] = array("label" => "Vh Sin Cita", "symbol" => "Sin Cita", "y" => $n_vh_sin_cita);
				$graf_vh_agen[] = array("label" => "Citas Agendadas", "symbol" => "Agendadas", "y" => $citas_agendadas);
				$graf_vh_agen[] = array("label" => "Citas Asistidas", "symbol" => "Asistidas", "y" => $citas_asistidas);
				/*ARRAY DATA PORCENTAJES*/
				$data_porcentajes[] = array('porcen_citas_cumplidas' => $porcen_citas_cump, 'atiempo' => $porcen_atiempo, 'temprano' => $porcen_temprano, 'tarde' => $porcen_tarde, 'porcen_vh_agendados' => $porcen_vh_agendados, 'bod' => $arr_bods[$i], 'graf_estado_cita' => $graf_estado_cita, 'graf_vh_agen' => $graf_vh_agen);
				$graf_estado_cita = null;
			}
			//print_r($data_porcentajes);die;
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'data' => $data_porcentajes, 'var' => "b");
			//abrimos la vista
			$this->load->view("Informe_ent_taller/cumplimiento_citas", $arr_user);
		}
	}

	public function inf_vh_agendados()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('talleres');
			//Traemos los parametros de la url
			$mes = $this->input->GET("mes");
			$anio = $this->input->GET("anio");
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_bods = [1, 11, 9, 21];
			for ($i = 0; $i < count($arr_bods); $i++) {

				/*CALCULOS CITAS CUMPLIDAS*/
				$citas_asistidas = $this->talleres->get_citas_asistidas($arr_bods[$i], $mes, $anio)->citas_asistidas;
				$citas_agendadas = $this->talleres->get_citas_agendadas($arr_bods[$i], $mes, $anio)->citas_agendadas;
				if ($citas_asistidas == 0 || $citas_agendadas == 0) {
					$porcen_citas_cump = 0;
				} else {
					$porcen_citas_cump = ($citas_asistidas / $citas_agendadas) * 100;
				}
				//$porcen_citas_cump = ($citas_asistidas/$citas_agendadas)*100;
				$graf_citas_cumplidas[] = array("label" => "Citas Asistidas", "symbol" => "Asistidas", "y" => $citas_asistidas);
				$graf_citas_cumplidas[] = array("label" => "Citas Agendadas", "symbol" => "Agendadas", "y" => $citas_agendadas);
				/*CALCULO CUMPLIMIENTO CITAS*/
				$data_estado_citas = $this->talleres->get_estados_cita($arr_bods[$i], $mes, $anio);
				$atiempo = 0;
				$temprano = 0;
				$tarde = 0;
				foreach ($data_estado_citas->result() as $key) {
					if ($key->Estado_cita == 'A_tiempo') {
						$atiempo++;
					} else if ($key->Estado_cita == 'Llegó tarde') {
						$tarde++;
					} else if ($key->Estado_cita == 'Llegó Antes de Tiempo') {
						$temprano++;
					}
				}
				if ($citas_asistidas == 0) {
				} else {
					$porcen_atiempo = ($atiempo / $citas_asistidas) * 100;
					$porcen_temprano = ($temprano / $citas_asistidas) * 100;
					$porcen_tarde = ($tarde / $citas_asistidas) * 100;
				}

				$graf_estado_cita[] = array("label" => "Temprano", "symbol" => "Temprano", "y" => $temprano);
				$graf_estado_cita[] = array("label" => "A Tiempo", "symbol" => "A Tiempo", "y" => $atiempo);
				$graf_estado_cita[] = array("label" => "Llegó Tarde", "symbol" => "Llegó Tarde", "y" => $tarde);
				/*CALCULO VH AGENDADOS*/
				$n_vh_sin_cita = count($this->talleres->get_vh_sin_citas($arr_bods[$i], $mes, $anio)->result());
				$to_ingresos = $n_vh_sin_cita + $citas_asistidas;
				$porcen_vh_agendados = ($citas_agendadas / $to_ingresos) * 100;
				$graf_vh_agen[] = array("label" => "Vh Sin Cita", "symbol" => "Sin Cita", "y" => $n_vh_sin_cita);
				$graf_vh_agen[] = array("label" => "Citas Agendadas", "symbol" => "Agendadas", "y" => $citas_agendadas);
				$graf_vh_agen[] = array("label" => "Citas Asistidas", "symbol" => "Asistidas", "y" => $citas_asistidas);
				/*ARRAY DATA PORCENTAJES*/
				$data_porcentajes[] = array('porcen_citas_cumplidas' => $porcen_citas_cump, 'atiempo' => $porcen_atiempo, 'temprano' => $porcen_temprano, 'tarde' => $porcen_tarde, 'porcen_vh_agendados' => $porcen_vh_agendados, 'bod' => $arr_bods[$i], 'graf_vh_agen' => $graf_vh_agen, 'graf_citas_cumplidas' => $graf_citas_cumplidas, 'graf_estado_cita' => $graf_estado_cita);
				$graf_vh_agen = null;
			}
			//print_r($data_porcentajes);die;
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'data' => $data_porcentajes, 'var' => "a");
			//abrimos la vista
			$this->load->view("Informe_ent_taller/vh_agendados", $arr_user);
		}
	}


	/*Informe KPI*/

	public function Informe_kpi()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('talleres');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$data_inf1 = $this->talleres->get_kpi_mant_prev();
			/*GRAF 1*/
			foreach ($data_inf1->result() as $key) {
				if ($key->Sede == "BOCONO DIESEL EXPRESS") {
					$data_graf11[] = array("label" => "Enero", "y" => $key->enero);
					$data_graf11[] = array("label" => "Febrero", "y" => $key->febrero);
					$data_graf11[] = array("label" => "Marzo", "y" => $key->marzo);
					$data_graf11[] = array("label" => "Abril", "y" => $key->abril);
					$data_graf11[] = array("label" => "Mayo", "y" => $key->mayo);
					$data_graf11[] = array("label" => "Junio", "y" => $key->junio);
					$data_graf11[] = array("label" => "Julio", "y" => $key->julio);
					$data_graf11[] = array("label" => "Agosto", "y" => $key->agosto);
					$data_graf11[] = array("label" => "Septiembre", "y" => $key->septiembre);
					$data_graf11[] = array("label" => "Octubre", "y" => $key->octubre);
					$data_graf11[] = array("label" => "Noviembre", "y" => $key->noviembre);
					$data_graf11[] = array("label" => "Diciembre", "y" => $key->diciembre);
				} else if ($key->Sede == "CHEVYEXPRESS BARRANCA") {
					$data_graf12[] = array("label" => "Enero", "y" => $key->enero);
					$data_graf12[] = array("label" => "Febrero", "y" => $key->febrero);
					$data_graf12[] = array("label" => "Marzo", "y" => $key->marzo);
					$data_graf12[] = array("label" => "Abril", "y" => $key->abril);
					$data_graf12[] = array("label" => "Mayo", "y" => $key->mayo);
					$data_graf12[] = array("label" => "Junio", "y" => $key->junio);
					$data_graf12[] = array("label" => "Julio", "y" => $key->julio);
					$data_graf12[] = array("label" => "Agosto", "y" => $key->agosto);
					$data_graf12[] = array("label" => "Septiembre", "y" => $key->septiembre);
					$data_graf12[] = array("label" => "Octubre", "y" => $key->octubre);
					$data_graf12[] = array("label" => "Noviembre", "y" => $key->noviembre);
					$data_graf12[] = array("label" => "Diciembre", "y" => $key->diciembre);
				} else if ($key->Sede == "CHEVYEXPRESS LA ROSITA") {
					$data_graf13[] = array("label" => "Enero", "y" => $key->enero);
					$data_graf13[] = array("label" => "Febrero", "y" => $key->febrero);
					$data_graf13[] = array("label" => "Marzo", "y" => $key->marzo);
					$data_graf13[] = array("label" => "Abril", "y" => $key->abril);
					$data_graf13[] = array("label" => "Mayo", "y" => $key->mayo);
					$data_graf13[] = array("label" => "Junio", "y" => $key->junio);
					$data_graf13[] = array("label" => "Julio", "y" => $key->julio);
					$data_graf13[] = array("label" => "Agosto", "y" => $key->agosto);
					$data_graf13[] = array("label" => "Septiembre", "y" => $key->septiembre);
					$data_graf13[] = array("label" => "Octubre", "y" => $key->octubre);
					$data_graf13[] = array("label" => "Noviembre", "y" => $key->noviembre);
					$data_graf13[] = array("label" => "Diciembre", "y" => $key->diciembre);
				} else if ($key->Sede == "CODIESEL PRINCIPAL") {
					$data_graf14[] = array("label" => "Enero", "y" => $key->enero);
					$data_graf14[] = array("label" => "Febrero", "y" => $key->febrero);
					$data_graf14[] = array("label" => "Marzo", "y" => $key->marzo);
					$data_graf14[] = array("label" => "Abril", "y" => $key->abril);
					$data_graf14[] = array("label" => "Mayo", "y" => $key->mayo);
					$data_graf14[] = array("label" => "Junio", "y" => $key->junio);
					$data_graf14[] = array("label" => "Julio", "y" => $key->julio);
					$data_graf14[] = array("label" => "Agosto", "y" => $key->agosto);
					$data_graf14[] = array("label" => "Septiembre", "y" => $key->septiembre);
					$data_graf14[] = array("label" => "Octubre", "y" => $key->octubre);
					$data_graf14[] = array("label" => "Noviembre", "y" => $key->noviembre);
					$data_graf14[] = array("label" => "Diciembre", "y" => $key->diciembre);
				} else if ($key->Sede == "CODIESEL VILLA DEL ROSARIO") {
					$data_graf15[] = array("label" => "Enero", "y" => $key->enero);
					$data_graf15[] = array("label" => "Febrero", "y" => $key->febrero);
					$data_graf15[] = array("label" => "Marzo", "y" => $key->marzo);
					$data_graf15[] = array("label" => "Abril", "y" => $key->abril);
					$data_graf15[] = array("label" => "Mayo", "y" => $key->mayo);
					$data_graf15[] = array("label" => "Junio", "y" => $key->junio);
					$data_graf15[] = array("label" => "Julio", "y" => $key->julio);
					$data_graf15[] = array("label" => "Agosto", "y" => $key->agosto);
					$data_graf15[] = array("label" => "Septiembre", "y" => $key->septiembre);
					$data_graf15[] = array("label" => "Octubre", "y" => $key->octubre);
					$data_graf15[] = array("label" => "Noviembre", "y" => $key->noviembre);
					$data_graf15[] = array("label" => "Diciembre", "y" => $key->diciembre);
				} else if ($key->Sede == "DIESEL EXPRESS BARRANCA") {
					$data_graf16[] = array("label" => "Enero", "y" => $key->enero);
					$data_graf16[] = array("label" => "Febrero", "y" => $key->febrero);
					$data_graf16[] = array("label" => "Marzo", "y" => $key->marzo);
					$data_graf16[] = array("label" => "Abril", "y" => $key->abril);
					$data_graf16[] = array("label" => "Mayo", "y" => $key->mayo);
					$data_graf16[] = array("label" => "Junio", "y" => $key->junio);
					$data_graf16[] = array("label" => "Julio", "y" => $key->julio);
					$data_graf16[] = array("label" => "Agosto", "y" => $key->agosto);
					$data_graf16[] = array("label" => "Septiembre", "y" => $key->septiembre);
					$data_graf16[] = array("label" => "Octubre", "y" => $key->octubre);
					$data_graf16[] = array("label" => "Noviembre", "y" => $key->noviembre);
					$data_graf16[] = array("label" => "Diciembre", "y" => $key->diciembre);
				} else if ($key->Sede == "DIESEL EXPRESS GIRON") {
					$data_graf17[] = array("label" => "Enero", "y" => $key->enero);
					$data_graf17[] = array("label" => "Febrero", "y" => $key->febrero);
					$data_graf17[] = array("label" => "Marzo", "y" => $key->marzo);
					$data_graf17[] = array("label" => "Abril", "y" => $key->abril);
					$data_graf17[] = array("label" => "Mayo", "y" => $key->mayo);
					$data_graf17[] = array("label" => "Junio", "y" => $key->junio);
					$data_graf17[] = array("label" => "Julio", "y" => $key->julio);
					$data_graf17[] = array("label" => "Agosto", "y" => $key->agosto);
					$data_graf17[] = array("label" => "Septiembre", "y" => $key->septiembre);
					$data_graf17[] = array("label" => "Octubre", "y" => $key->octubre);
					$data_graf17[] = array("label" => "Noviembre", "y" => $key->noviembre);
					$data_graf17[] = array("label" => "Diciembre", "y" => $key->diciembre);
				}
			}
			$data_inf2 = $this->talleres->get_kpi_car_cli();
			/*GRAFICA INF 2*/
			foreach ($data_inf2->result() as $key) {
				if ($key->Sede == "BOCONO DIESEL EXPRESS") {
					$data_graf21[] = array("label" => "Enero", "y" => $key->enero);
					$data_graf21[] = array("label" => "Febrero", "y" => $key->febrero);
					$data_graf21[] = array("label" => "Marzo", "y" => $key->marzo);
					$data_graf21[] = array("label" => "Abril", "y" => $key->abril);
					$data_graf21[] = array("label" => "Mayo", "y" => $key->mayo);
					$data_graf21[] = array("label" => "Junio", "y" => $key->junio);
					$data_graf21[] = array("label" => "Julio", "y" => $key->julio);
					$data_graf21[] = array("label" => "Agosto", "y" => $key->agosto);
					$data_graf21[] = array("label" => "Septiembre", "y" => $key->septiembre);
					$data_graf21[] = array("label" => "Octubre", "y" => $key->octubre);
					$data_graf21[] = array("label" => "Noviembre", "y" => $key->noviembre);
					$data_graf21[] = array("label" => "Diciembre", "y" => $key->diciembre);
				} else if ($key->Sede == "CHEVYEXPRESS BARRANCA") {
					$data_graf22[] = array("label" => "Enero", "y" => $key->enero);
					$data_graf22[] = array("label" => "Febrero", "y" => $key->febrero);
					$data_graf22[] = array("label" => "Marzo", "y" => $key->marzo);
					$data_graf22[] = array("label" => "Abril", "y" => $key->abril);
					$data_graf22[] = array("label" => "Mayo", "y" => $key->mayo);
					$data_graf22[] = array("label" => "Junio", "y" => $key->junio);
					$data_graf22[] = array("label" => "Julio", "y" => $key->julio);
					$data_graf22[] = array("label" => "Agosto", "y" => $key->agosto);
					$data_graf22[] = array("label" => "Septiembre", "y" => $key->septiembre);
					$data_graf22[] = array("label" => "Octubre", "y" => $key->octubre);
					$data_graf22[] = array("label" => "Noviembre", "y" => $key->noviembre);
					$data_graf22[] = array("label" => "Diciembre", "y" => $key->diciembre);
				} else if ($key->Sede == "CHEVYEXPRESS LA ROSITA") {
					$data_graf23[] = array("label" => "Enero", "y" => $key->enero);
					$data_graf23[] = array("label" => "Febrero", "y" => $key->febrero);
					$data_graf23[] = array("label" => "Marzo", "y" => $key->marzo);
					$data_graf23[] = array("label" => "Abril", "y" => $key->abril);
					$data_graf23[] = array("label" => "Mayo", "y" => $key->mayo);
					$data_graf23[] = array("label" => "Junio", "y" => $key->junio);
					$data_graf23[] = array("label" => "Julio", "y" => $key->julio);
					$data_graf23[] = array("label" => "Agosto", "y" => $key->agosto);
					$data_graf23[] = array("label" => "Septiembre", "y" => $key->septiembre);
					$data_graf23[] = array("label" => "Octubre", "y" => $key->octubre);
					$data_graf23[] = array("label" => "Noviembre", "y" => $key->noviembre);
					$data_graf23[] = array("label" => "Diciembre", "y" => $key->diciembre);
				} else if ($key->Sede == "CODIESEL PRINCIPAL") {
					$data_graf24[] = array("label" => "Enero", "y" => $key->enero);
					$data_graf24[] = array("label" => "Febrero", "y" => $key->febrero);
					$data_graf24[] = array("label" => "Marzo", "y" => $key->marzo);
					$data_graf24[] = array("label" => "Abril", "y" => $key->abril);
					$data_graf24[] = array("label" => "Mayo", "y" => $key->mayo);
					$data_graf24[] = array("label" => "Junio", "y" => $key->junio);
					$data_graf24[] = array("label" => "Julio", "y" => $key->julio);
					$data_graf24[] = array("label" => "Agosto", "y" => $key->agosto);
					$data_graf24[] = array("label" => "Septiembre", "y" => $key->septiembre);
					$data_graf24[] = array("label" => "Octubre", "y" => $key->octubre);
					$data_graf24[] = array("label" => "Noviembre", "y" => $key->noviembre);
					$data_graf24[] = array("label" => "Diciembre", "y" => $key->diciembre);
				} else if ($key->Sede == "CODIESEL VILLA DEL ROSARIO") {
					$data_graf25[] = array("label" => "Enero", "y" => $key->enero);
					$data_graf25[] = array("label" => "Febrero", "y" => $key->febrero);
					$data_graf25[] = array("label" => "Marzo", "y" => $key->marzo);
					$data_graf25[] = array("label" => "Abril", "y" => $key->abril);
					$data_graf25[] = array("label" => "Mayo", "y" => $key->mayo);
					$data_graf25[] = array("label" => "Junio", "y" => $key->junio);
					$data_graf25[] = array("label" => "Julio", "y" => $key->julio);
					$data_graf25[] = array("label" => "Agosto", "y" => $key->agosto);
					$data_graf25[] = array("label" => "Septiembre", "y" => $key->septiembre);
					$data_graf25[] = array("label" => "Octubre", "y" => $key->octubre);
					$data_graf25[] = array("label" => "Noviembre", "y" => $key->noviembre);
					$data_graf25[] = array("label" => "Diciembre", "y" => $key->diciembre);
				} else if ($key->Sede == "DIESEL EXPRESS BARRANCA") {
					$data_graf26[] = array("label" => "Enero", "y" => $key->enero);
					$data_graf26[] = array("label" => "Febrero", "y" => $key->febrero);
					$data_graf26[] = array("label" => "Marzo", "y" => $key->marzo);
					$data_graf26[] = array("label" => "Abril", "y" => $key->abril);
					$data_graf26[] = array("label" => "Mayo", "y" => $key->mayo);
					$data_graf26[] = array("label" => "Junio", "y" => $key->junio);
					$data_graf26[] = array("label" => "Julio", "y" => $key->julio);
					$data_graf26[] = array("label" => "Agosto", "y" => $key->agosto);
					$data_graf26[] = array("label" => "Septiembre", "y" => $key->septiembre);
					$data_graf26[] = array("label" => "Octubre", "y" => $key->octubre);
					$data_graf26[] = array("label" => "Noviembre", "y" => $key->noviembre);
					$data_graf26[] = array("label" => "Diciembre", "y" => $key->diciembre);
				} else if ($key->Sede == "DIESEL EXPRESS GIRON") {
					$data_graf27[] = array("label" => "Enero", "y" => $key->enero);
					$data_graf27[] = array("label" => "Febrero", "y" => $key->febrero);
					$data_graf27[] = array("label" => "Marzo", "y" => $key->marzo);
					$data_graf27[] = array("label" => "Abril", "y" => $key->abril);
					$data_graf27[] = array("label" => "Mayo", "y" => $key->mayo);
					$data_graf27[] = array("label" => "Junio", "y" => $key->junio);
					$data_graf27[] = array("label" => "Julio", "y" => $key->julio);
					$data_graf27[] = array("label" => "Agosto", "y" => $key->agosto);
					$data_graf27[] = array("label" => "Septiembre", "y" => $key->septiembre);
					$data_graf27[] = array("label" => "Octubre", "y" => $key->octubre);
					$data_graf27[] = array("label" => "Noviembre", "y" => $key->noviembre);
					$data_graf27[] = array("label" => "Diciembre", "y" => $key->diciembre);
				}
			}
			/*INFORMACION Informe 3*/
			$data_inf31 = $this->talleres->inf_ot_tec();
			$data_inf32 = $this->talleres->inf_rep_tec();
			$data_inf33 = $this->talleres->inf_mo_tec();
			$arr_user = array(
				'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'data_inf1' => $data_inf1, 'data_inf2' => $data_inf2, 'data_inf31' => $data_inf31, 'data_inf32' => $data_inf32, 'data_inf33' => $data_inf33, 'data_graf11' => $data_graf11, 'data_graf12' => $data_graf12, 'data_graf13' => $data_graf12, 'data_graf13' => $data_graf13, 'data_graf14' => $data_graf14, 'data_graf15' => $data_graf15, 'data_graf16' => $data_graf16, 'data_graf17' => $data_graf17,
				'data_graf21' => $data_graf21, 'data_graf22' => $data_graf22, 'data_graf23' => $data_graf22, 'data_graf23' => $data_graf23, 'data_graf24' => $data_graf24, 'data_graf25' => $data_graf25, 'data_graf26' => $data_graf26, 'data_graf27' => $data_graf27
			);
			//abrimos la vista
			$this->load->view("Informe_kpi/index", $arr_user);
		}
	}

	/* public function inf_mant_prev_kpi()
	{
		//Traemos los modelos
		$this->load->model('talleres');
		//ejecutamos la consulta

		echo "";
		foreach ($data->result() as $key) {
		}
	} */


	/*metodo para traer encuesta de satisfacion segun mes y nit */

	public function traerEncuesta()
	{
		$this->load->model('Informe');
		$nit = $this->input->POST('nit');
		$mes = $this->input->POST('mes');
		$orden = $this->input->POST('orden');
		$bodega = $this->input->POST('bodega');
		$id = $this->input->POST('id');
		$datos = $this->Informe->taerEncuesta($nit, $mes, $orden, $bodega, $id);
		//echo ($datos);
		foreach ($datos->result() as $key) {
			$to_enc = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
			$valor = ((($key->enc9a10 - $key->enc0a6) / $to_enc) * 100);
			$color =  ($valor == 100 ? 'background-color:#ABEBC6;' : ($valor == -100 ? 'background-color:#f58478;' : ($valor == 0 ? 'background-color: #f5f5a9;' : '')));

			echo "
							<div class='header'>
							<p class='col-lg-12 text-center lead'>Informe NPS por tecnicos</p>
								<hr>
								<div class='row justify-content-center'>
									<p class='col-lg-12'>NUMERO DE ORDEN: " . $key->n_orden . "</p>
									<p class = 'col-lg-12' >TECNICO RESPONSABLE: " . $key->nombres . "</p>
								</div>
								<hr>
								<div class='row justify-content-center'>
									<div class='card m-2 p-4 shadow' style='width: 18rem;$color'>
										<div class='card-body'>
											<h5 class='card-title col-lg-12 text-center'>Total Encuestas de 0 - 6 </h5>
											<p class='card-text col-lg-12 text-center font-weight-bold'><strom>" . $key->enc0a6 . "</strom></p>
										</div>
									</div>
									<div class='card m-2 p-4 shadow' style='width: 18rem;$color'>
										<div class='card-body'>
											<h5 class='card-title col-lg-12 text-center'>Total Encuestas de 7 - 8</h5>
											<p class='card-text col-lg-12 text-center font-weight-bold'><strom>" . $key->enc7a8 . "</strom></p>
										</div>
									</div>
									<div class='card m-2 p-4 shadow' style='width: 18rem;$color'>
										<div class='card-body'>
											<h5 class='card-title col-lg-12 text-center'>Total Encuestas de 9 - 10</h5>
											<p class='card-text col-lg-12 text-center font-weight-bold'><strom>" . $key->enc9a10 . "</strom></p>
										</div>
									</div>
								</div>
								<hr>
								<p class='card-title text-justify'> " . $key->pregunta5 . " </p> 	
							</div>									
			";
		}
	}


	public function Informe_nps()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Informe');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$datosencuesta = $this->Informe->encuesta_nps();
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'datosencuesta' => $datosencuesta);
			//abrimos la vista
			$this->load->view("Informe_nps/index", $arr_user);
		}
	}

	public function buscar_nps()
	{
		$this->load->model('Informe');
		$mes = $this->input->POST('mes');
		$sede = $this->input->POST('sede');

		if ($mes == 0) {
			$mes = "1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12";
		} else {
			$mes;
		}

		if ($sede == 'todas') {
			$sede = "1, 9, 11, 21, 7, 6, 19, 8, 14, 16, 22";
		} else if ($sede == 'giron') {
			$sede = "1, 9, 11, 21";
		} else if ($sede == 'rosita') {
			$sede = "7";
		} else if ($sede == 'barranca') {
			$sede = "6, 19";
		} else if ($sede == 'bocono') {
			$sede = "8, 14, 16, 22";
		}
		$data = $this->Informe->taer_nps_por_sede_y_mes($mes, $sede);
		if ($data) {


			echo "
				<table class='table table-bordered table-hover' id='tablafiltro' style='font-size:14px;width:100%;'>
				<div class='card-header' align='right'>
				<a href='' class='btn btn-success' onclick='bajar_excel_filtro();'><i class='far fa-file-excel'></i> Exportar a excel</a>
			</div>
                                <thead class='thead-dark' align='center' id='fjo'>
                                    <tr>
                                        <th scope='col'>Documento</th>
                                        <th scope='col'>Tecnico</th>
                                        <th scope='col'>Satisfaccion con el concesionario</th>
                                        <th scope='col'>Satisfaccion con el trabajo</th>
                                        <th scope='col'>Explicacion todo el trabajo realizado</th>
                                        <th scope='col'>Se cumplieron los compromisos pactados</th>
                                        <th scope='col'>Verbalizacion</th>
                                    </tr>
                                </thead>
								<tbody align='center'>
								";
			foreach ($data->result() as $datos) {
				echo "
				
					<tr>
                    	<td>$datos->nit</td>
                        <td>$datos->nombres</td>
                        <td>$datos->pregunta1</td>
                        <td>$datos->pregunta2</td>
                        <td>$datos->pregunta3</td>
                        <td>$datos->pregunta4</td>
                        <td class='d-none'>$datos->pregunta5</td>
                        <td class='fitwidth' data-toggle='modal' data-target='#comentario'><a href='#' class='btn btn-outline-info mr-3 >' onclick='verbalizacion(\" " . $datos->pregunta5 . " \");' id='$datos->n_orden>'> <i class='fas fa-eye'></i></a></td>
                    </tr>
			
			";
			}
			echo "
		</tbody>
		</table>";
		}
	}

	/**
	 * NUEVO Informe NPS
	 * ANDRES GOMEZ
	 * 19-12-21
	 */

	public function nuevo_Informe_nps()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Informe');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//$datosencuesta = $this->Informe->datos_de_Informe_nps();
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu/* , 'datosencuesta' => $datosencuesta */);
			//abrimos la vista
			$this->load->view("Informe_nps/Informe_nps", $arr_user);
		}
	}

	/**
	 * METODO PARA GESTIONAR HORAS DE ENTRADA Y SALIDA 
	 * ANDRES GOMEZ
	 * 30-12-2021
	 */

	public function horas_salida_y_entrda()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Informe');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//TRaremos los empleados para el select
			$data_emp = $this->usuarios->getUserActivos();
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'data_emp' => $data_emp);
			//abrimos la vista
			$this->load->view("Informe_hora_laboral/horario", $arr_user);
		}
	}

	/**
	 * METODO PARA FILTRAR PR FECHA,SEDE,NIT
	 * ANDRES GOMEZ
	 * 03-01-2022
	 */

	public function filtros_horas_laborales()
	{
		$this->load->model('Informe');
		$sede = $this->input->POST('sede');
		$fecha_ini = $this->input->POST('fecha_ini');
		$fecha_fin = $this->input->POST('fecha_fin');
		$nit = $this->input->POST('nit');
		if ($fecha_ini != "" && $fecha_fin != "" && $nit == "") {
			$where = "AND CONVERT(DATE,i.fecha_hora) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "') AND sede ='" . $sede . "'";
		} elseif ($fecha_ini != "" && $fecha_fin != "" && $nit != "") {
			$where = "AND CONVERT(DATE,i.fecha_hora) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "') AND i.empleado = " . $nit . "AND sede = '" . $sede . "'";
		}
		$enviar = $this->Informe->filtar_horarios($where);
		//print_r($enviar);

		echo "
			<div class='table-responsive' id=''>
				<table class='table  table-hover table-bordered nowrap' id='tablahorarios' style='width:100%;'>
					<thead class='bg-dark'>
						<tr>
							<th class='text-center'>Documento</th>
							<th class='text-center'>Nombre</th>
							<th class='text-center'>Sede</th>
							<th class='text-center'>Fecha</th>
							<th class='text-center'>Hora</th>
							<th class='text-center'>Accion</th>
						</tr>
					</thead>
					<input type='button' class='btn btn-success' value='Exportar a Excel' onclick='bajar_excel_filtro();'>
					<hr>
					<tbody>
					";
		foreach ($enviar->result() as $key) :

			$registros = $key->horas;
			$accion = $key->accion;
			$estado = $accion == 'Ingreso' ? "#AAE8FA" : "#FAAAB1";

			echo "
								<tr style='background:$estado;'>
									<td class='text-center' >$key->empleado</td>
									<td class='text-center' >$key->nombres</td>
									<td class='text-center' >$key->sede</td>
									<td class='text-center ' >$key->fechas</td>
									<td class='text-center' >$key->horas</td>
									<td class='text-center' >$key->accion</td     
								</tr>
									";
		endforeach;
		echo "
					</tbody>
				</table>
			</div>
			";
	}
	/**METODO PARA CARGAR LA VISTA PRINCIPAL DEL  Informe NPS FINAL
	 * ANDRES GOMEZ
	 * 2022-11-01
	 */

	public function Informe_nps_final()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu);
			//abrimos la vista
			$this->load->view("Informe_nps/nps_final.php", $arr_user);
		}
	}
	/**
	 * METODO PARA CARGAR LOS DATOS DEL Informe NPS FINAL EN UNA TABLA GENERAL Y EN TABLAS INDIVIDUALES POR SEDE
	 * ANDRES GOMEZ
	 * 022-11-01
	 */
	public function filtro_nps_final()
	{
		$this->load->model('Informe');
		$cantidad = array();
		$fecha = $this->input->POST('fecha');
		$mes = $this->input->POST('mes');
		$sede = $this->input->POST('sede');
		$tipoenps = $this->input->POST('nps');
		$vista = $this->input->POST('vista');
		$arrayMeses = explode(",", $mes);
		$nombreMeses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Obtubre', 'Noviembre', 'Diciembre');
		$nps_int = $tipoenps == "nps_int" ? '' : 'd-none';
		$nps_exten = $tipoenps == "nps_col" ? '' : 'd-none';

		function array_value_recursive($valorBusqueda, array $arr)
		{
			$posicion = array_search($valorBusqueda, array_column($arr, 'nombre'));
			for ($i = $posicion; $i < count($arr); $i++) {
				if ($arr[$i]['nombre'] == $valorBusqueda) {
					$dataMesValor[] = ['mes' => $arr[$i]['mes'], 'npsI' => $arr[$i]['npsI'], 'npsC' => $arr[$i]['npsC'], 'npsi06' => $arr[$i]['npsi06'], 'npsi78' => $arr[$i]['npsi78'], 'npsi910' => $arr[$i]['npsi910'], 'npscl06' => $arr[$i]['npscl06'], 'npscl78' => $arr[$i]['npscl78'], 'npscl910' => $arr[$i]['npscl910']];
				} else {
					break;
				}
			}
			return array($posicion, $dataMesValor);
		}


		if ($vista == 'info_tabla') {
			$cantidad = explode(',', $sede);
			for ($t = 0; $t < count($cantidad); $t++) {
				$nuevasedes =  "'" . $cantidad[$t] . "'";
				$datos = $this->Informe->filtro_nps_final($fecha, $mes, $nuevasedes);

				//calcular total encuestas nps interno
				$valorTotalEncuestasNpsi = 0;
				$sumatoria06 = 0;
				$sumatoria910 = 0;
				foreach ($datos->result() as $total) {
					$encuest0a6 = $total->enc0a6_interno;
					$encuest7a8 = $total->enc7a8_interno;
					$encuest9a10 = $total->enc9a10_interno;
					$totalencuestas[] = $encuest0a6 + $encuest7a8 + $encuest9a10;
					$sumatoriaencuest0a6[] = $encuest0a6;
					$sumatoriaencuest9a10[] = $encuest9a10;
				}
				for ($i = 0; $i < count($totalencuestas); $i++) {
					$valorTotalEncuestasNpsi = $valorTotalEncuestasNpsi + $totalencuestas[$i];
					$sumatoria06 = $sumatoria06 + $sumatoriaencuest0a6[$i];
					$sumatoria910 = $sumatoria910 + $sumatoriaencuest9a10[$i];
				}

				$totallnpsi = ((($sumatoria910 - $sumatoria06) / $valorTotalEncuestasNpsi) * 100);
				$totallnpsi = round($totallnpsi);

				//calcular total encuestas nps colmotores
				$valorTotalEncuestasclm = 0;
				$sumatoriaclm06 = 0;
				$sumatoriaclm910 = 0;
				foreach ($datos->result() as $total) {
					$encuest0a6 = $total->enc0a6_colmotores;
					$encuest7a8 = $total->enc7a8_colmotores;
					$encuest9a10 = $total->enc9a10_colmotores;
					$totalencuestasclm[] = $encuest0a6 + $encuest7a8 + $encuest9a10;
					$sumatoriaencuestclm0a6[] = $encuest0a6;
					$sumatoriaencuestclm9a10[] = $encuest9a10;
				}
				for ($j = 0; $j < count($totalencuestasclm); $j++) {
					$valorTotalEncuestasclm = $valorTotalEncuestasclm + $totalencuestasclm[$j];
					$sumatoriaclm06 = $sumatoriaclm06 +  $sumatoriaencuestclm0a6[$j];
					$sumatoriaclm910 = $sumatoriaclm910 + $sumatoriaencuestclm9a10[$j];
				}
				$totallclm = ((($sumatoriaclm910 - $sumatoriaclm06) / $valorTotalEncuestasclm) * 100);
				$totallclm = round($totallclm);

				echo "
				<div class='table-responsive padretabla' id='padretabla'>
				<table class='table table-striped table-bordered tabladatos'>
				<hr>
					<thead>
					<tr>
					<label class='text-center col-lg-12 cabeza " . $nps_int . "'>NPS interno en la sede  " . strtoupper($nuevasedes) . " es de " . $totallnpsi . "</label>
					<label class='text-center col-lg-12 cabeza " . $nps_exten . "'>NPS Colmotores en la sede  " . strtoupper($nuevasedes) . " es de " . $totallclm . "</label>

					</tr>
						<tr class='thead-dark'>
							<th scope='col'>Nombre</th>
							";
				for ($i = 1; $i <= count($arrayMeses); $i++) {
					$month = $arrayMeses[$i - 1];
					echo "<th>" . $nombreMeses[$month] . "</th>";
				}
				echo "
					</tr>
					</thead>
					<tbody>
					";
				$arrayNombres = array();
				foreach ($datos->result() as $key) {


					//NPS INTERNO
					$to_enc = $key->enc0a6_interno + $key->enc7a8_interno + $key->enc9a10_interno;
					$nps = ((($key->enc9a10_interno - $key->enc0a6_interno) / $to_enc) * 100);
					$nps = round($nps);

					//NPS COLMOTORES
					$colm_enc = $key->enc0a6_colmotores + $key->enc7a8_colmotores + $key->enc9a10_colmotores;
					$nps_colmotores = ((($key->enc9a10_colmotores - $key->enc0a6_colmotores) / $colm_enc) * 100);
					$nps_colmotores = round($nps_colmotores);

					$dataTabla[] = ['mes' => $key->mes, 'nombre' => $key->nombres, 'npsI' => $nps, 'npsC' => $nps_colmotores, 'npsi06' => $key->enc0a6_interno, 'npsi78' => $key->enc7a8_interno, 'npsi910' => $key->enc9a10_interno, 'npscl06' => $key->enc0a6_colmotores, 'npscl78' => $key->enc7a8_colmotores, 'npscl910' => $key->enc9a10_colmotores];
					if (!in_array($key->nombres, $arrayNombres)) {
						array_push($arrayNombres, trim($key->nombres));
					}
				}

				for ($i = 0; $i < count($arrayNombres); $i++) {
					echo "
						<tr>
	
							<td scope='row'>$arrayNombres[$i]</td>";
					$valores =  array_value_recursive($arrayNombres[$i], $dataTabla);
					$datos = $valores[1];
					for ($j = 1; $j <= count($arrayMeses); $j++) {
						if (in_array($j, array_column($datos, 'mes'))) {
							$pos = array_search($j, array_column($datos, 'mes'));
							echo "
							<td class='$nps_int text-center' data-toggle='popover'  title='Encuestas de 0 a 6 son un total de : " . $datos[$pos]['npsi06'] . " Encuestas de 7 a 8 son un total de :" . $datos[$pos]['npsi78'] . "  Encuestas de 9 a 10 son un total de :" . $datos[$pos]['npsi910'] . "'>" . $datos[$pos]['npsI'] . "</td>
							<td class = '$nps_exten text-center' data-toggle='popover' title='Encuestas de 0 a 6 son un total de :  " . $datos[$pos]['npscl06'] . " Encuestas de 7 a 8 son un total de : " . $datos[$pos]['npscl78'] . " Encuestas de 9 a 10 son un total de : " . $datos[$pos]['npscl910'] . " '>" . $datos[$pos]['npsC'] . "</td>";
						} else {
							echo "
							<td class='$nps_int text-center'>0</td>
							<td class = '$nps_exten text-center'>0</td>";
						}
					}
				}
				echo "
			</tbody>
			</table>
			</div>
			";
			}
			//SE COMIENZA LA CERACION DE LAS GRAFICAS
		} else if ($vista == 'info_grafica') {

			$arrayNombres =  array();
			$sede = "'" . $sede . "'";
			$datos = $this->Informe->filtro_nps_final($fecha, $mes, $sede);
			$dataPoints = array();
			foreach ($datos->result() as $key) {
				//NPS INTERNO
				$to_enc = $key->enc0a6_interno + $key->enc7a8_interno + $key->enc9a10_interno;
				$nps = ((($key->enc9a10_interno - $key->enc0a6_interno) / $to_enc) * 100);
				$nps = round($nps);

				//NPS COLMOTORES
				$colm_enc = $key->enc0a6_colmotores + $key->enc7a8_colmotores + $key->enc9a10_colmotores;
				$nps_colmotores = ((($key->enc9a10_colmotores - $key->enc0a6_colmotores) / $colm_enc) * 100);
				$nps_colmotores = round($nps_colmotores);

				$valor = $tipoenps == "nps_int" ? $nps : $nps_colmotores;

				$dataPoints[] = array("y" => $valor, "label" => $key->nombres);
			}

			echo json_encode($dataPoints);
		}
	}


	/**
	 * METODO PARA GESTIONAR HORAS DE ENTRADA Y SALIDA 
	 * ANDRES GOMEZ
	 * 30-12-2021
	 */

	public function horas_salida_y_entrda_personal()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Informe');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			//datos para la consulta de horarios
			$sede = 'giron';
			$datos = $this->Informe->listar_horarios_por_sede_user($usu, $sede);
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'datos' => $datos);
			//abrimos la vista
			$this->load->view("Informe_hora_laboral/hora_ingreso", $arr_user);
		}
	}

	/**
	 * METODO PARA FILTARA HORARIO DE INGRESO Y SALIDA DE EMPLEADOS POR FECHA
	 * ANDRES GOMEZ
	 * 2022-28-01
	 */

	public function filtro_ingreso_salida_por_fecha()
	{
		$this->load->model('Informe');

		$documento = $this->session->userdata('user');
		$fecha = $this->input->POST('fecha_filtro');
		$enviar = $this->Informe->filtro_listar_horarios_por_sede_user($documento, $fecha);
		//print_r($enviar);


		echo "
				<table class='table  table-hover table-bordered nowrap' id='tablahorarios' style='width:100%;'>
					<thead class='bg-dark'>
						<tr>
							<th class='text-center'>Documento</th>
							<th class='text-center'>Nombre</th>
							<th class='text-center'>Sede</th>
							<th class='text-center'>Fecha</th>
							<th class='text-center'>Hora</th>
							<th class='text-center'>Accion</th>
						</tr>
					</thead>
					<tbody>
					";
		foreach ($enviar->result() as $key) :

			$estado = $key->accion == 'Ingreso' ? "#AAE8FA" : "#FAAAB1";

			echo "
								<tr style='background:$estado;'>
									<td class='text-center' >$key->empleado</td>
									<td class='text-center' >$key->nombres</td>
									<td class='text-center' >$key->sede</td>
									<td class='text-center ' >$key->fechas</td>
									<td class='text-center' >$key->horas</td>
									<td class='text-center' >$key->accion</td     
								</tr>
									";
		endforeach;
		echo "
					</tbody>
				</table>
			";
	}

	/* Informe SEGUNDA ENTREGA */

	public function inf_seg_entrega()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Informe');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";


			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			/* Obtenemos fecha actual y restamos un mes para mostrar datos de un mes atras de la fecha actual */

			$fecha_actual = date('Y-m-d');
			$fecha_inicio = date("Y-m-d", strtotime($fecha_actual . "- 1 day"));

			/* Obtenemos los datos de la consulta en la base de datos */
			$datos = $this->Informe->listar_segunda_entrega($fecha_actual, $fecha_inicio);
			$detalle = $this->Informe->inf_detallado_segunda_entrega($fecha_actual, $fecha_inicio);
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'datos' => $datos, 'detalles' => $detalle, 'fecha_inicio' => $fecha_inicio, 'fecha_final' => $fecha_actual);
			//abrimos la vista
			$this->load->view("Informe_segunda_entrega/segunda_entrega", $arr_user);
		}
	}
	public function inf_seg_entrega_fechas()
	{
		$this->load->model('Informe');

		$f_final = $this->input->POST('f_final');
		$f_inicial = $this->input->POST('f_inicial');

		/* Obtenemos los datos de la consulta en la base de datos */
		$data = $this->Informe->listar_segunda_entrega($f_final, $f_inicial);
		$detalle = $this->Informe->inf_detallado_segunda_entrega($f_final, $f_inicial);
		//abrimos la vista
		if ($data) {


			echo "
				<table class='table table-bordered table-hover' id='tablafiltro' style='font-size:14px;width:100%;'>
				<div class='card-header' align='right'>
				<a href='#' class='btn btn-success' onclick='bajar_excel_filtro();'><i class='far fa-file-excel'></i> Exportar a excel</a>
			</div>
                                <thead class='thead-dark' align='center' id='fjo'>
								<tr><td scope='col' colspan='5'><strong>Informe de Segunda Entrega desde:$f_final hasta:$f_inicial</strong></td></tr>
                                    <tr>
                                        <th scope='col'><strong>Año</strong></th>
                                        <th scope='col'><strong>Mes</strong></th>
                                        <th scope='col'><strong>Día</strong></th>
                                        <th scope='col'><strong>Entregas</strong></th>
                                        <th scope='col'><strong>Agendas</strong></th>
                                    </tr>
                                </thead>
								<tbody align='center'>
								";
			foreach ($data->result() as $datos) {
				echo "
				
					<tr>
                    	<td>$datos->año</td>
                        <td>$datos->mes</td>
                        <td>$datos->dia</td>
                        <td>$datos->entregas</td>
                        <td>$datos->agendas</td>
                     </tr>
			
			";
			}
			echo "
		</tbody>";
		}
		if ($detalle) {
			echo "
				                <thead class='thead-dark' align='center' id='fjo'>
								<tr><td scope='col' colspan='6'><strong>Informe Detallado de Segunda Entrega desde:$f_inicial hasta:$f_final</strong></td></tr>
                                    <tr>
                                        <th scope='col'><strong>Año</strong></th>
                                        <th scope='col'><strong>Mes</strong></th>
                                        <th scope='col'><strong>Día</strong></th>
                                        <th scope='col'><strong>Vehiculo</strong></th>
                                        <th scope='col'><strong>Sede</strong></th>
										<th scope='col'><strong>Agendado por</strong></th>
                                    </tr>
                                </thead>
								<tbody align='center'>
								
								";
			foreach ($detalle->result() as $key) {
				echo "
				
					<tr>
                    	<td>$key->año</td>
                        <td>$key->mes</td>
                        <td>$key->dia</td>
                        <td>$key->vehiculo</td>
                        <td>$key->sede</td>
						<td>$key->Agendado_por</td>
                     </tr>
			
			";
			}
			echo "
		</tbody>
		</table>";
		}
	}
	/******************************************************** Informe ventas ultimos 72 meses *******************************************************************************
	Autor: Sergio Galvis
	Fecha:  16/05/2022*/
	/* Carga del modulo! */
	public function InformeVentas72()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Informe');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";


			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}

			/* Obtenemos los datos de la consulta en la base de datos */



			$dataAutos = $this->Informe->dataAutos();
			$dataByC = $this->Informe->dataByC();
			$dataDetalleAutos = $this->Informe->dataAutosDetalle();
			$dataDetalleByC = $this->Informe->dataByCDetalle();

			$arr_user = array(
				'dataAutos' => $dataAutos, 'dataByC' => $dataByC, 'dataDetalleAutos' => $dataDetalleAutos, 'dataDetalleByC' => $dataDetalleByC,
				'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu
			);
			//abrimos la vista
			$this->load->view("gestionVentas/InformeVentas72", $arr_user);
		}
	}
	/* Carga del modulo! */
	public function InformeVentas72PostCarga()
	{
		$this->load->model('Informe');

		/* Obtenemos los datos de la consulta en la base de datos */
		/* Iniciamos las variables en 0 XD donde se realizara la suma para el total del Informe */
		$var12e = 0;
		$var12p = 0;
		$var24e = 0;
		$var24p = 0;
		$var36e = 0;
		$var36p = 0;
		$var48e = 0;
		$var48p = 0;
		$var60e = 0;
		$var60p = 0;
		$var72e = 0;
		$var72p = 0;
		$varP12 = 0;
		$varP24 = 0;
		$varP36 = 0;
		$varP48 = 0;
		$varP60 = 0;
		$varP72 = 0;
		$datos = $this->Informe->getVentas72();
		if ($datos != "") {
			/* Iniciamos las variables en 0 XD donde se realizara la suma para el total del Informe */
			$var12e = 0;
			$var12p = 0;
			$var24e = 0;
			$var24p = 0;
			$var36e = 0;
			$var36p = 0;
			$var48e = 0;
			$var48p = 0;
			$var60e = 0;
			$var60p = 0;
			$var72e = 0;
			$var72p = 0;
			$varP12 = 0;
			$varP24 = 0;
			$varP36 = 0;
			$varP48 = 0;
			$varP60 = 0;
			$varP72 = 0;

			foreach ($datos->result() as $key) {
				/* Informacion de sumas y porcentajes */
				$var12e += $key->e_0_12;
				$var12p += $key->p0_12;

				$var24e += $key->e_13_24;
				$var24p += $key->p13_24;

				$var36e += $key->e_25_36;
				$var36p += $key->p25_36;

				$var48e += $key->e_37_48;
				$var48p += $key->p37_48;

				$var60e += $key->e_49_60;
				$var60p += $key->p49_60;

				$var72e += $key->e_61_72;
				$var72p += $key->p61_72;
			}

			if ($var12p != 0) {
				$varP12 += round(($var12e / $var12p) * 100, 0);
				$dataPorcentajeTotal[0] = array("y" => $varP12, "label" => "12 - 0");
			} else {
				$dataPorcentajeTotal[0] = array("y" => 0, "label" => "12 - 0");
			}

			if ($var24p != 0) {
				$varP24 += round(($var24e / $var24p) * 100, 0);
				$dataPorcentajeTotal[1] = array("y" => $varP24, "label" => "24 - 12");
			} else {
				$dataPorcentajeTotal[1] = array("y" => 0, "label" => "24 - 12");
			}

			if ($var36p != 0) {
				$varP36 += round(($var36e / $var36p) * 100, 0);
				$dataPorcentajeTotal[2] = array("y" => $varP36, "label" => "36 - 24");
			} else {
				$dataPorcentajeTotal[2] = array("y" => 0, "label" => "36 - 24");
			}

			if ($var48p != 0) {
				$varP48 += round(($var48e / $var48p) * 100, 0);
				$dataPorcentajeTotal[3] = array("y" => $varP48, "label" => "48 - 36");
			} else {
				$dataPorcentajeTotal[3] = array("y" => 0, "label" => "48 - 36");
			}

			if ($var60p != 0) {
				$varP60 += round(($var60e / $var60p) * 100, 0);
				$dataPorcentajeTotal[4] = array("y" => $varP60, "label" => "60 - 48");
			} else {
				$dataPorcentajeTotal[4] = array("y" => 0, "label" => "60 - 48");
			}

			if ($var72p != 0) {
				$varP72 += round(($var72e / $var72p) * 100, 0);
				$dataPorcentajeTotal[5] = array("y" => $varP72, "label" => "72 - 60");
			} else {
				$dataPorcentajeTotal[5] = array("y" => 0, "label" => "72 - 60");
			}

			$dataTotal = (array) [
				'var12e' => $var12e,
				'var12p' => $var12p,
				'varP12' => $varP12,
				'var24e' => $var24e,
				'var24p' => $var24p,
				'varP24' => $varP24,
				'var36e' => $var36e,
				'var36p' => $var36p,
				'varP36' => $varP36,
				'var48e' => $var48e,
				'var48p' => $var48p,
				'varP48' => $varP48,
				'var60e' => $var60e,
				'var60p' => $var60p,
				'varP60' => $varP60,
				'var72e' => $var72e,
				'var72p' => $var72p,
				'varP72' => $varP72
			];
			/* Encabezado de la tabla */
			echo '
				<div><label class="col-lg-12 text-center lead">Informe general</label></div>
				<table class="table nowrap table-striped tabla1" id="tabla_uno">
					<thead class="bg-dark">
						<tr>
							<th width="10%" class="text-center">Parque</th>
							<th width="10%" class="text-center">Entrada Única Último Año 4-5</th>
							<th width="10%" class="text-center">Total Ventas</th>
							<th width="10%" class="text-center">Retención Propia</th>
							<th width="60%" class="text-center">Tendencia</th>
						</tr>
					</thead>
					<tbody>
					';

			foreach ($datos->result() as $key) {
				$retVal1 = ($key->p0_12 != 0) ? round(($key->e_0_12 / $key->p0_12) * 100, 0) : 0;
				$retVal2 = ($key->p13_24 != 0) ? round(($key->e_13_24 / $key->p13_24) * 100, 0) : 0;
				$retVal3 = ($key->p25_36 != 0) ? round(($key->e_25_36 / $key->p25_36) * 100, 0) : 0;
				$retVal4 = ($key->p37_48 != 0) ? round(($key->e_37_48 / $key->p37_48) * 100, 0) : 0;
				$retVal5 = ($key->p49_60 != 0) ? round(($key->e_49_60 / $key->p49_60) * 100, 0) : 0;
				$retVal6 = ($key->p61_72 != 0) ? round(($key->e_61_72 / $key->p61_72) * 100, 0) : 0;


				echo '	<tr>
										<th colspan="5" class="text-center" style="background-color: darkgray; color:white;"><h3>' . $key->tipo_vh . '<h3></th>
									</tr>
									<tr>
										<th class="text-center">12-0</th>
										<td class="text-center">' . $key->e_0_12 . '</td>
										<td class="text-center">' . $key->p0_12 . '</td>	
										
										<td class="text-center">' . $retVal1 . '%</td>
										<td rowspan="6" class="text-center">
										<div class="card card-info" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
											<div class="card-header">
												<h3 class="card-title">' . $key->tipo_vh . '</h3>
	
												<div class="card-tools">
													<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
													</button>
												</div>
												<!-- /.card-tools -->
											</div>
											<!-- /.card-header -->
											<div class="card-body">
											<div id="chartContainer' . $key->tipo_vh . '" style="height: 250px; width: 80%;"></div>
											</div>
											<!-- /.card-body -->
										</div>
										</td>
									</tr>
									<tr>
										<th class="text-center">24-12</th>
										<td class="text-center">' . $key->e_13_24 . '</td>
										<td class="text-center">' . $key->p13_24 . '</td>
										<td class="text-center">' . $retVal2 . '%</td>
										
									</tr>
									<tr>
										<th class="text-center">36-24</th>
										<td class="text-center">' . $key->e_25_36 . '</td>
										<td class="text-center">' . $key->p25_36 . '</td>
										<td class="text-center">' . $retVal3  . '%</td>
										
									</tr>
									<tr>
										<th class="text-center">48-36</th>
										<td class="text-center">' . $key->e_37_48 . '</td>
										<td class="text-center">' . $key->p37_48 . '</td>
										<td class="text-center">' . $retVal4  . '%</td>
										
									</tr>
									<tr>
										<th class="text-center">60-48</th>
										<td class="text-center">' . $key->e_49_60 . '</td>
										<td class="text-center">' . $key->p49_60 . '</td>
										<td class="text-center">' . $retVal5  . '%</td>
										
									</tr>
									<tr>
										<th class="text-center">72-60</th>
										<td class="text-center">' . $key->e_61_72 . '</td>
										<td class="text-center">' . $key->p61_72 . '</td>
										<td class="text-center">' . $retVal6  . '%</td>
										
									</tr>									
	
									';
			}
			/* Datos para el total */
			echo '
							<tr>
								<th colspan="5" class="text-center" style="background-color: darkgray; color:white;"><h3>Total</h3></th>
							</tr>
							<tr>
										<th class="text-center">12-0</th>
										<td class="text-center parque-12">' . $dataTotal['var12e'] . '</td>
										<td class="text-center parque-12">' . $dataTotal['var12p'] . '</td>											
										<td class="text-center parque-12">' . $dataTotal['varP12'] . '%</td>
										<td rowspan="6" class="text-center">
										<div class="card card-info" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
											<div class="card-header">
												<h3 class="card-title">Total</h3>
	
												<div class="card-tools">
													<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
													</button>
												</div>
												<!-- /.card-tools -->
											</div>
											<!-- /.card-header -->
											<div class="card-body">
											<div id="chartContainerTotal" style="height: 250px; width: 80%;"></div>
											</div>
											<!-- /.card-body -->
										</div>
										</td>
									</tr>
									<tr>
										<th class="text-center">24-12</th>
										<td class="text-center parque-24">' . $dataTotal['var24e'] . '</td>
										<td class="text-center parque-24">' . $dataTotal['var24p'] . '</td>											
										<td class="text-center parque-24">' . $dataTotal['varP24'] . '%</td>
										
									</tr>
									<tr>
										<th class="text-center">36-24</th>
										<td class="text-center parque-36">' . $dataTotal['var36e'] . '</td>
										<td class="text-center parque-36">' . $dataTotal['var36p'] . '</td>											
										<td class="text-center parque-36">' . $dataTotal['varP36'] . '%</td>		
									</tr>
									<tr>
										<th class="text-center">48-36</th>
										<td class="text-center parque-48">' . $dataTotal['var48e'] . '</td>
										<td class="text-center parque-48">' . $dataTotal['var48p'] . '</td>											
										<td class="text-center parque-48">' . $dataTotal['varP48'] . '%</td>											
									</tr>
									<tr>
										<th class="text-center">60-48</th>
										<td class="text-center parque-60">' . $dataTotal['var60e'] . '</td>
										<td class="text-center parque-60">' . $dataTotal['var60p'] . '</td>											
										<td class="text-center parque-60">' . $dataTotal['varP60'] . '%</td>
										
									</tr>
									<tr>
										<th class="text-center">72-60</th>
										<td class="text-center parque-72">' . $dataTotal['var72e'] . '</td>
										<td class="text-center parque-72">' . $dataTotal['var72p'] . '</td>											
										<td class="text-center parque-72">' . $dataTotal['varP72'] . '%</td>
										
									</tr>
							
							';

			echo '
	
					</tbody>
				</table>
				';
		} else {
			echo "Error";
		}
	}
	/* Carga los datos para la grafica general del modulo! */
	public function GrafInformeVentas72PostCarga()
	{
		$this->load->model('Informe');

		/* Obtenemos los datos de la consulta en la base de datos */
		$datos = $this->Informe->getVentas72();
		/* Iniciamos las variables en 0 XD donde se realizara la suma para el total del Informe */
		$var12e = 0;
		$var12p = 0;
		$var24e = 0;
		$var24p = 0;
		$var36e = 0;
		$var36p = 0;
		$var48e = 0;
		$var48p = 0;
		$var60e = 0;
		$var60p = 0;
		$var72e = 0;
		$var72p = 0;
		$varP12 = 0;
		$varP24 = 0;
		$varP36 = 0;
		$varP48 = 0;
		$varP60 = 0;
		$varP72 = 0;
		$dataFlotaGraf = array();
		$dataRetailGraf = array();
		foreach ($datos->result() as $key) {
			if ($key->tipo_vh == 'Flota') {
				$var12P = round(($key->e_0_12 / $key->p0_12) * 100, 0);
				$dataFlotaGraf[0] = array("y" => $var12P, "label" => "12 - 0");
				$var12P = round(($key->e_13_24 / $key->p13_24) * 100, 0);
				$dataFlotaGraf[1] = array("y" => $var12P, "label" => "24 - 12");
				$var12P = round(($key->e_25_36 / $key->p25_36) * 100, 0);
				$dataFlotaGraf[2] = array("y" => $var12P, "label" => "36 - 24");
				$var12P = round(($key->e_37_48 / $key->p37_48) * 100, 0);
				$dataFlotaGraf[3] = array("y" => $var12P, "label" => "48 - 36");
				$var12P = round(($key->e_49_60 / $key->p49_60) * 100, 0);
				$dataFlotaGraf[4] = array("y" => $var12P, "label" => "60 - 48");
				$var12P = round(($key->e_61_72 / $key->p61_72) * 100, 0);
				$dataFlotaGraf[5] = array("y" => $var12P, "label" => "72 - 60");
			} elseif ($key->tipo_vh == 'Retail') {
				$var12P = round(($key->e_0_12 / $key->p0_12) * 100, 0);
				$dataRetailGraf[0] = array("y" => $var12P, "label" => "12 - 0");
				$var12P = round(($key->e_13_24 / $key->p13_24) * 100, 0);
				$dataRetailGraf[1] = array("y" => $var12P, "label" => "24 - 12");
				$var12P = round(($key->e_25_36 / $key->p25_36) * 100, 0);
				$dataRetailGraf[2] = array("y" => $var12P, "label" => "36 - 24");
				$var12P = round(($key->e_37_48 / $key->p37_48) * 100, 0);
				$dataRetailGraf[3] = array("y" => $var12P, "label" => "48 - 36");
				$var12P = round(($key->e_49_60 / $key->p49_60) * 100, 0);
				$dataRetailGraf[4] = array("y" => $var12P, "label" => "60 - 48");
				$var12P = round(($key->e_61_72 / $key->p61_72) * 100, 0);
				$dataRetailGraf[5] = array("y" => $var12P, "label" => "72 - 60");
			}
			$var12e += $key->e_0_12;
			$var12p += $key->p0_12;

			$var24e += $key->e_13_24;
			$var24p += $key->p13_24;

			$var36e += $key->e_25_36;
			$var36p += $key->p25_36;

			$var48e += $key->e_37_48;
			$var48p += $key->p37_48;

			$var60e += $key->e_49_60;
			$var60p += $key->p49_60;

			$var72e += $key->e_61_72;
			$var72p += $key->p61_72;
		}
		$varP12 += round(($var12e / $var12p) * 100, 0);
		$varP24 += round(($var24e / $var24p) * 100, 0);
		$varP36 += round(($var36e / $var36p) * 100, 0);
		$varP48 += round(($var48e / $var48p) * 100, 0);
		$varP60 += round(($var60e / $var60p) * 100, 0);
		$varP72 += round(($var72e / $var72p) * 100, 0);

		$dataPorcentajeTotal[0] = array("y" => $varP12, "label" => "12 - 0");
		$dataPorcentajeTotal[1] = array("y" => $varP24, "label" => "24 - 12");
		$dataPorcentajeTotal[2] = array("y" => $varP36, "label" => "36 - 24");
		$dataPorcentajeTotal[3] = array("y" => $varP48, "label" => "48 - 36");
		$dataPorcentajeTotal[4] = array("y" => $varP60, "label" => "60 - 48");
		$dataPorcentajeTotal[5] = array("y" => $varP72, "label" => "72 - 60");

		$arr_user = array('dataPorcentajeTotal' => $dataPorcentajeTotal, 'dataFlotaGraf' => $dataFlotaGraf, 'dataRetailGraf' => $dataRetailGraf);

		echo json_encode($arr_user);
	}
	/* Enviar tabla con datos dependiendo del filtro autos */
	public function filtroInforme72Auto()
	{
		$this->load->model('Informe');
		$filtro = $this->input->GET('filtro');
		$dataFiltro = $this->Informe->dataAutosFiltro($filtro);
		if ($dataFiltro != "") {
			/* Iniciamos las variables en 0 XD donde se realizara la suma para el total del Informe */
			$var12e = 0;
			$var12p = 0;
			$var24e = 0;
			$var24p = 0;
			$var36e = 0;
			$var36p = 0;
			$var48e = 0;
			$var48p = 0;
			$var60e = 0;
			$var60p = 0;
			$var72e = 0;
			$var72p = 0;
			$varP12 = 0;
			$varP24 = 0;
			$varP36 = 0;
			$varP48 = 0;
			$varP60 = 0;
			$varP72 = 0;

			foreach ($dataFiltro->result() as $key) {
				/* Informacion de sumas y porcentajes */
				$var12e += $key->e_0_12;
				$var12p += $key->p0_12;

				$var24e += $key->e_13_24;
				$var24p += $key->p13_24;

				$var36e += $key->e_25_36;
				$var36p += $key->p25_36;

				$var48e += $key->e_37_48;
				$var48p += $key->p37_48;

				$var60e += $key->e_49_60;
				$var60p += $key->p49_60;

				$var72e += $key->e_61_72;
				$var72p += $key->p61_72;
			}

			if ($var12p != 0) {
				$varP12 += round(($var12e / $var12p) * 100, 0);
				$dataPorcentajeTotal[0] = array("y" => $varP12, "label" => "12 - 0");
			} else {
				$dataPorcentajeTotal[0] = array("y" => 0, "label" => "12 - 0");
			}

			if ($var24p != 0) {
				$varP24 += round(($var24e / $var24p) * 100, 0);
				$dataPorcentajeTotal[1] = array("y" => $varP24, "label" => "24 - 12");
			} else {
				$dataPorcentajeTotal[1] = array("y" => 0, "label" => "24 - 12");
			}

			if ($var36p != 0) {
				$varP36 += round(($var36e / $var36p) * 100, 0);
				$dataPorcentajeTotal[2] = array("y" => $varP36, "label" => "36 - 24");
			} else {
				$dataPorcentajeTotal[2] = array("y" => 0, "label" => "36 - 24");
			}

			if ($var48p != 0) {
				$varP48 += round(($var48e / $var48p) * 100, 0);
				$dataPorcentajeTotal[3] = array("y" => $varP48, "label" => "48 - 36");
			} else {
				$dataPorcentajeTotal[3] = array("y" => 0, "label" => "48 - 36");
			}

			if ($var60p != 0) {
				$varP60 += round(($var60e / $var60p) * 100, 0);
				$dataPorcentajeTotal[4] = array("y" => $varP60, "label" => "60 - 48");
			} else {
				$dataPorcentajeTotal[4] = array("y" => 0, "label" => "60 - 48");
			}

			if ($var72p != 0) {
				$varP72 += round(($var72e / $var72p) * 100, 0);
				$dataPorcentajeTotal[5] = array("y" => $varP72, "label" => "72 - 60");
			} else {
				$dataPorcentajeTotal[5] = array("y" => 0, "label" => "72 - 60");
			}

			$dataTotal = (array) [
				'var12e' => $var12e,
				'var12p' => $var12p,
				'varP12' => $varP12,
				'var24e' => $var24e,
				'var24p' => $var24p,
				'varP24' => $varP24,
				'var36e' => $var36e,
				'var36p' => $var36p,
				'varP36' => $varP36,
				'var48e' => $var48e,
				'var48p' => $var48p,
				'varP48' => $varP48,
				'var60e' => $var60e,
				'var60p' => $var60p,
				'varP60' => $varP60,
				'var72e' => $var72e,
				'var72p' => $var72p,
				'varP72' => $varP72
			];
			/* Encabezado de la tabla */
			echo '
			<div class="table-responsive" id="tablatotal">
			<div><label class="col-lg-12 text-center lead">' . $filtro . '</label></div>
			<table class="table nowrap table-striped tabla2" id="tabla_uno">
				<thead class="bg-dark">
					<tr>
						<th width="10%" class="text-center">Parque</th>
						<th width="10%" class="text-center">Entrada Única Último Año 4-5</th>
						<th width="10%" class="text-center">Total Ventas</th>
						<th width="10%" class="text-center">Retención Propia</th>
						<th width="60%" class="text-center">Tendencia</th>
					</tr>
				</thead>
				<tbody>
				';

			foreach ($dataFiltro->result() as $key) {
				$retVal1 = ($key->p0_12 != 0) ? round(($key->e_0_12 / $key->p0_12) * 100, 0) : 0;
				$retVal2 = ($key->p13_24 != 0) ? round(($key->e_13_24 / $key->p13_24) * 100, 0) : 0;
				$retVal3 = ($key->p25_36 != 0) ? round(($key->e_25_36 / $key->p25_36) * 100, 0) : 0;
				$retVal4 = ($key->p37_48 != 0) ? round(($key->e_37_48 / $key->p37_48) * 100, 0) : 0;
				$retVal5 = ($key->p49_60 != 0) ? round(($key->e_49_60 / $key->p49_60) * 100, 0) : 0;
				$retVal6 = ($key->p61_72 != 0) ? round(($key->e_61_72 / $key->p61_72) * 100, 0) : 0;


				echo '	<tr>
									<th colspan="5" class="text-center" style="background-color: darkgray; color:white;"><h3>' . $key->tipo_vh . '</h3></th>
								</tr>
								<tr>
									<th class="text-center">12-0</th>
									<td class="text-center">' . $key->e_0_12 . '</td>
									<td class="text-center">' . $key->p0_12 . '</td>	
									
									<td class="text-center">' . $retVal1 . '%</td>
									<td rowspan="6" class="text-center">
									<div class="card card-info" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
										<div class="card-header">
											<h3 class="card-title">' . $key->tipo_vh . '</h3>

											<div class="card-tools">
												<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
												</button>
											</div>
											<!-- /.card-tools -->
										</div>
										<!-- /.card-header -->
										<div class="card-body">
										<div id="chartContainer' . $key->tipo_vh . '2" style="height: 250px; width: 80%;"></div>
										</div>
										<!-- /.card-body -->
									</div>
									</td>
								</tr>
								<tr>
									<th class="text-center">24-12</th>
									<td class="text-center">' . $key->e_13_24 . '</td>
									<td class="text-center">' . $key->p13_24 . '</td>
									<td class="text-center">' . $retVal2 . '%</td>
									
								</tr>
								<tr>
									<th class="text-center">36-24</th>
									<td class="text-center">' . $key->e_25_36 . '</td>
									<td class="text-center">' . $key->p25_36 . '</td>
									<td class="text-center">' . $retVal3  . '%</td>
									
								</tr>
								<tr>
									<th class="text-center">48-36</th>
									<td class="text-center">' . $key->e_37_48 . '</td>
									<td class="text-center">' . $key->p37_48 . '</td>
									<td class="text-center">' . $retVal4  . '%</td>
									
								</tr>
								<tr>
									<th class="text-center">60-48</th>
									<td class="text-center">' . $key->e_49_60 . '</td>
									<td class="text-center">' . $key->p49_60 . '</td>
									<td class="text-center">' . $retVal5  . '%</td>
									
								</tr>
								<tr>
									<th class="text-center">72-60</th>
									<td class="text-center">' . $key->e_61_72 . '</td>
									<td class="text-center">' . $key->p61_72 . '</td>
									<td class="text-center">' . $retVal6  . '%</td>
									
								</tr>									

								';
			}
			echo '
						<tr>
							<th colspan="5" class="text-center" style="background-color: darkgray; color:white;"><h3>Total</h3></th>
						</tr>
						<tr>
									<th class="text-center">12-0</th>
									<td class="text-center parque-12">' . $dataTotal['var12e'] . '</td>
									<td class="text-center parque-12">' . $dataTotal['var12p'] . '</td>											
									<td class="text-center parque-12">' . $dataTotal['varP12'] . '%</td>
									<td rowspan="6" class="text-center">
									<div class="card card-info" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
										<div class="card-header">
											<h3 class="card-title">Total</h3>

											<div class="card-tools">
												<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
												</button>
											</div>
											<!-- /.card-tools -->
										</div>
										<!-- /.card-header -->
										<div class="card-body">
										<div id="chartContainerTotal2" style="height: 250px; width: 80%;"></div>
										</div>
										<!-- /.card-body -->
									</div>
									</td>
								</tr>
								<tr>
									<th class="text-center">24-12</th>
									<td class="text-center parque-24">' . $dataTotal['var24e'] . '</td>
									<td class="text-center parque-24">' . $dataTotal['var24p'] . '</td>											
									<td class="text-center parque-24">' . $dataTotal['varP24'] . '%</td>
									
								</tr>
								<tr>
									<th class="text-center">36-24</th>
									<td class="text-center parque-36">' . $dataTotal['var36e'] . '</td>
									<td class="text-center parque-36">' . $dataTotal['var36p'] . '</td>											
									<td class="text-center parque-36">' . $dataTotal['varP36'] . '%</td>		
								</tr>
								<tr>
									<th class="text-center">48-36</th>
									<td class="text-center parque-48">' . $dataTotal['var48e'] . '</td>
									<td class="text-center parque-48">' . $dataTotal['var48p'] . '</td>											
									<td class="text-center parque-48">' . $dataTotal['varP48'] . '%</td>											
								</tr>
								<tr>
									<th class="text-center">60-48</th>
									<td class="text-center parque-60">' . $dataTotal['var60e'] . '</td>
									<td class="text-center parque-60">' . $dataTotal['var60p'] . '</td>											
									<td class="text-center parque-60">' . $dataTotal['varP60'] . '%</td>
									
								</tr>
								<tr>
									<th class="text-center">72-60</th>
									<td class="text-center parque-72">' . $dataTotal['var72e'] . '</td>
									<td class="text-center parque-72">' . $dataTotal['var72p'] . '</td>											
									<td class="text-center parque-72">' . $dataTotal['varP72'] . '%</td>
									
								</tr>
						
						';

			echo '

				</tbody>
			</table>

		</div>
			';
		} else {
			echo "Error";
		}
	}
	/* Enviar info con datos dependiendo del filtro autos para las graficas */
	public function filtroInforme72ByC()
	{
		$this->load->model('Informe');
		$filtro = $this->input->POST('filtro');
		/* print_r($filtro);die; */
		$dataFiltro = $this->Informe->dataByCFiltro($filtro);
		if ($dataFiltro != "") {
			/* Iniciamos las variables en 0 XD donde se realizara la suma para el total del Informe */
			$var12e = 0;
			$var12p = 0;
			$var24e = 0;
			$var24p = 0;
			$var36e = 0;
			$var36p = 0;
			$var48e = 0;
			$var48p = 0;
			$var60e = 0;
			$var60p = 0;
			$var72e = 0;
			$var72p = 0;
			$varP12 = 0;
			$varP24 = 0;
			$varP36 = 0;
			$varP48 = 0;
			$varP60 = 0;
			$varP72 = 0;

			foreach ($dataFiltro->result() as $key) {
				/* Informacion de sumas y porcentajes */
				$var12e += $key->e_0_12;
				$var12p += $key->p0_12;

				$var24e += $key->e_13_24;
				$var24p += $key->p13_24;

				$var36e += $key->e_25_36;
				$var36p += $key->p25_36;

				$var48e += $key->e_37_48;
				$var48p += $key->p37_48;

				$var60e += $key->e_49_60;
				$var60p += $key->p49_60;

				$var72e += $key->e_61_72;
				$var72p += $key->p61_72;
			}

			if ($var12p != 0) {
				$varP12 += round(($var12e / $var12p) * 100, 0);
				$dataPorcentajeTotal[0] = array("y" => $varP12, "label" => "12 - 0");
			} else {
				$dataPorcentajeTotal[0] = array("y" => 0, "label" => "12 - 0");
			}

			if ($var24p != 0) {
				$varP24 += round(($var24e / $var24p) * 100, 0);
				$dataPorcentajeTotal[1] = array("y" => $varP24, "label" => "24 - 12");
			} else {
				$dataPorcentajeTotal[1] = array("y" => 0, "label" => "24 - 12");
			}

			if ($var36p != 0) {
				$varP36 += round(($var36e / $var36p) * 100, 0);
				$dataPorcentajeTotal[2] = array("y" => $varP36, "label" => "36 - 24");
			} else {
				$dataPorcentajeTotal[2] = array("y" => 0, "label" => "36 - 24");
			}

			if ($var48p != 0) {
				$varP48 += round(($var48e / $var48p) * 100, 0);
				$dataPorcentajeTotal[3] = array("y" => $varP48, "label" => "48 - 36");
			} else {
				$dataPorcentajeTotal[3] = array("y" => 0, "label" => "48 - 36");
			}

			if ($var60p != 0) {
				$varP60 += round(($var60e / $var60p) * 100, 0);
				$dataPorcentajeTotal[4] = array("y" => $varP60, "label" => "60 - 48");
			} else {
				$dataPorcentajeTotal[4] = array("y" => 0, "label" => "60 - 48");
			}

			if ($var72p != 0) {
				$varP72 += round(($var72e / $var72p) * 100, 0);
				$dataPorcentajeTotal[5] = array("y" => $varP72, "label" => "72 - 60");
			} else {
				$dataPorcentajeTotal[5] = array("y" => 0, "label" => "72 - 60");
			}

			$dataTotal = (array) [
				'var12e' => $var12e,
				'var12p' => $var12p,
				'varP12' => $varP12,
				'var24e' => $var24e,
				'var24p' => $var24p,
				'varP24' => $varP24,
				'var36e' => $var36e,
				'var36p' => $var36p,
				'varP36' => $varP36,
				'var48e' => $var48e,
				'var48p' => $var48p,
				'varP48' => $varP48,
				'var60e' => $var60e,
				'var60p' => $var60p,
				'varP60' => $varP60,
				'var72e' => $var72e,
				'var72p' => $var72p,
				'varP72' => $varP72
			];
			/* Encabezado de la tabla */
			echo '
			<div class="table-responsive" id="tablatotal">
			<div><label class="col-lg-12 text-center lead">' . $filtro . '</label></div>
			<table class="table nowrap table-striped tabla2" id="tabla_uno">
				<thead class="bg-dark">
					<tr>
						<th width="10%" class="text-center">Parque</th>
						<th width="10%" class="text-center">Entrada Única Último Año 4-5</th>
						<th width="10%" class="text-center">Total Ventas</th>
						<th width="10%" class="text-center">Retención Propia</th>
						<th width="60%" class="text-center">Tendencia</th>
					</tr>
				</thead>
				<tbody>
				';

			foreach ($dataFiltro->result() as $key) {
				$retVal1 = ($key->p0_12 != 0) ? round(($key->e_0_12 / $key->p0_12) * 100, 0) : 0;
				$retVal2 = ($key->p13_24 != 0) ? round(($key->e_13_24 / $key->p13_24) * 100, 0) : 0;
				$retVal3 = ($key->p25_36 != 0) ? round(($key->e_25_36 / $key->p25_36) * 100, 0) : 0;
				$retVal4 = ($key->p37_48 != 0) ? round(($key->e_37_48 / $key->p37_48) * 100, 0) : 0;
				$retVal5 = ($key->p49_60 != 0) ? round(($key->e_49_60 / $key->p49_60) * 100, 0) : 0;
				$retVal6 = ($key->p61_72 != 0) ? round(($key->e_61_72 / $key->p61_72) * 100, 0) : 0;

				echo '	<tr>
								<th colspan="5" class="text-center" style="background-color: darkgray; color:white;"><h3>' . $key->tipo_vh . '</h3></th>
							</tr>
							<tr>
								<th class="text-center">12-0</th>
								<td class="text-center">' . $key->e_0_12 . '</td>
								<td class="text-center">' . $key->p0_12 . '</td>	
								
								<td class="text-center">' . $retVal1 . '%</td>
								<td rowspan="6" class="text-center">
								<div class="card card-info" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
									<div class="card-header">
										<h3 class="card-title">' . $key->tipo_vh . '</h3>

										<div class="card-tools">
											<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
											</button>
										</div>
										<!-- /.card-tools -->
									</div>
									<!-- /.card-header -->
									<div class="card-body">
									<div id="chartContainer' . $key->tipo_vh . '2" style="height: 250px; width: 80%;"></div>
									</div>
									<!-- /.card-body -->
								</div>
								</td>
							</tr>
							<tr>
								<th class="text-center">24-12</th>
								<td class="text-center">' . $key->e_13_24 . '</td>
								<td class="text-center">' . $key->p13_24 . '</td>
								<td class="text-center">' . $retVal2 . '%</td>
								
							</tr>
							<tr>
								<th class="text-center">36-24</th>
								<td class="text-center">' . $key->e_25_36 . '</td>
								<td class="text-center">' . $key->p25_36 . '</td>
								<td class="text-center">' . $retVal3  . '%</td>
								
							</tr>
							<tr>
								<th class="text-center">48-36</th>
								<td class="text-center">' . $key->e_37_48 . '</td>
								<td class="text-center">' . $key->p37_48 . '</td>
								<td class="text-center">' . $retVal4  . '%</td>
								
							</tr>
							<tr>
								<th class="text-center">60-48</th>
								<td class="text-center">' . $key->e_49_60 . '</td>
								<td class="text-center">' . $key->p49_60 . '</td>
								<td class="text-center">' . $retVal5  . '%</td>
								
							</tr>
							<tr>
								<th class="text-center">72-60</th>
								<td class="text-center">' . $key->e_61_72 . '</td>
								<td class="text-center">' . $key->p61_72 . '</td>
								<td class="text-center">' . $retVal6  . '%</td>
								
							</tr>									

							';
			}
			echo '
						<tr>
							<th colspan="5" class="text-center" style="background-color: darkgray; color:white;"><h3>Total</h3></th>
						</tr>
						<tr>
									<th class="text-center">12-0</th>
									<td class="text-center parque-12">' . $dataTotal['var12e'] . '</td>
									<td class="text-center parque-12">' . $dataTotal['var12p'] . '</td>											
									<td class="text-center parque-12">' . $dataTotal['varP12'] . '%</td>
									<td rowspan="6" class="text-center">
									<div class="card card-info" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
										<div class="card-header">
											<h3 class="card-title">Total</h3>

											<div class="card-tools">
												<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
												</button>
											</div>
											<!-- /.card-tools -->
										</div>
										<!-- /.card-header -->
										<div class="card-body">
										<div id="chartContainerTotal2" style="height: 250px; width: 80%;"></div>
										</div>
										<!-- /.card-body -->
									</div>
									</td>
								</tr>
								<tr>
									<th class="text-center">24-12</th>
									<td class="text-center parque-24">' . $dataTotal['var24e'] . '</td>
									<td class="text-center parque-24">' . $dataTotal['var24p'] . '</td>											
									<td class="text-center parque-24">' . $dataTotal['varP24'] . '%</td>
									
								</tr>
								<tr>
									<th class="text-center">36-24</th>
									<td class="text-center parque-36">' . $dataTotal['var36e'] . '</td>
									<td class="text-center parque-36">' . $dataTotal['var36p'] . '</td>											
									<td class="text-center parque-36">' . $dataTotal['varP36'] . '%</td>		
								</tr>
								<tr>
									<th class="text-center">48-36</th>
									<td class="text-center parque-48">' . $dataTotal['var48e'] . '</td>
									<td class="text-center parque-48">' . $dataTotal['var48p'] . '</td>											
									<td class="text-center parque-48">' . $dataTotal['varP48'] . '%</td>											
								</tr>
								<tr>
									<th class="text-center">60-48</th>
									<td class="text-center parque-60">' . $dataTotal['var60e'] . '</td>
									<td class="text-center parque-60">' . $dataTotal['var60p'] . '</td>											
									<td class="text-center parque-60">' . $dataTotal['varP60'] . '%</td>
									
								</tr>
								<tr>
									<th class="text-center">72-60</th>
									<td class="text-center parque-72">' . $dataTotal['var72e'] . '</td>
									<td class="text-center parque-72">' . $dataTotal['var72p'] . '</td>											
									<td class="text-center parque-72">' . $dataTotal['varP72'] . '%</td>
									
								</tr>
						
						';

			echo '

				</tbody>
			</table>

		</div>
			';
		} else {
			echo "Error";
		}
	}
	/* Enviar tabla con datos dependiendo del filtro B&C */
	public function getDatosGraficasAuto()
	{
		$this->load->model('Informe');
		$filtro = $this->input->GET('filtro');

		if ($filtro == "Autos") {
			$nameColumnsSecond = "General";
			$dataFiltrosGeneral =  $this->Informe->getVentas72();
			/* Iniciamos las variables en 0 XD donde se realizara la suma para el total del Informe */
			$var12e = 0;
			$var12p = 0;
			$var24e = 0;
			$var24p = 0;
			$var36e = 0;
			$var36p = 0;
			$var48e = 0;
			$var48p = 0;
			$var60e = 0;
			$var60p = 0;
			$var72e = 0;
			$var72p = 0;
			$varP12 = 0;
			$varP24 = 0;
			$varP36 = 0;
			$varP48 = 0;
			$varP60 = 0;
			$varP72 = 0;
			$dataFlotaGrafG = array();
			$dataRetailGrafG = array();
			foreach ($dataFiltrosGeneral->result() as $key) {
				if ($key->tipo_vh == 'Flota') {
					$var12P = round(($key->e_0_12 / $key->p0_12) * 100, 0);
					$dataFlotaGrafG[0] = array("y" => $var12P, "label" => "12 - 0");
					$var12P = round(($key->e_13_24 / $key->p13_24) * 100, 0);
					$dataFlotaGrafG[1] = array("y" => $var12P, "label" => "24 - 12");
					$var12P = round(($key->e_25_36 / $key->p25_36) * 100, 0);
					$dataFlotaGrafG[2] = array("y" => $var12P, "label" => "36 - 24");
					$var12P = round(($key->e_37_48 / $key->p37_48) * 100, 0);
					$dataFlotaGrafG[3] = array("y" => $var12P, "label" => "48 - 36");
					$var12P = round(($key->e_49_60 / $key->p49_60) * 100, 0);
					$dataFlotaGrafG[4] = array("y" => $var12P, "label" => "60 - 48");
					$var12P = round(($key->e_61_72 / $key->p61_72) * 100, 0);
					$dataFlotaGrafG[5] = array("y" => $var12P, "label" => "72 - 60");
				} elseif ($key->tipo_vh == 'Retail') {
					$var12P = round(($key->e_0_12 / $key->p0_12) * 100, 0);
					$dataRetailGrafG[0] = array("y" => $var12P, "label" => "12 - 0");
					$var12P = round(($key->e_13_24 / $key->p13_24) * 100, 0);
					$dataRetailGrafG[1] = array("y" => $var12P, "label" => "24 - 12");
					$var12P = round(($key->e_25_36 / $key->p25_36) * 100, 0);
					$dataRetailGrafG[2] = array("y" => $var12P, "label" => "36 - 24");
					$var12P = round(($key->e_37_48 / $key->p37_48) * 100, 0);
					$dataRetailGrafG[3] = array("y" => $var12P, "label" => "48 - 36");
					$var12P = round(($key->e_49_60 / $key->p49_60) * 100, 0);
					$dataRetailGrafG[4] = array("y" => $var12P, "label" => "60 - 48");
					$var12P = round(($key->e_61_72 / $key->p61_72) * 100, 0);
					$dataRetailGrafG[5] = array("y" => $var12P, "label" => "72 - 60");
				}
				$var12e += $key->e_0_12;
				$var12p += $key->p0_12;

				$var24e += $key->e_13_24;
				$var24p += $key->p13_24;

				$var36e += $key->e_25_36;
				$var36p += $key->p25_36;

				$var48e += $key->e_37_48;
				$var48p += $key->p37_48;

				$var60e += $key->e_49_60;
				$var60p += $key->p49_60;

				$var72e += $key->e_61_72;
				$var72p += $key->p61_72;
			}
			/* Script para capturar el porcentaje del total */
			if ($var12p != 0) {
				$varP12 += round(($var12e / $var12p) * 100, 0);
				$dataPorcentajeTotalG[0] = array("y" => $varP12, "label" => "12 - 0");
			} else {
				$dataPorcentajeTotalG[0] = array("y" => 0, "label" => "12 - 0");
			}

			if ($var24p != 0) {
				$varP24 += round(($var24e / $var24p) * 100, 0);
				$dataPorcentajeTotalG[1] = array("y" => $varP24, "label" => "24 - 12");
			} else {
				$dataPorcentajeTotalG[1] = array("y" => 0, "label" => "24 - 12");
			}

			if ($var36p != 0) {
				$varP36 += round(($var36e / $var36p) * 100, 0);
				$dataPorcentajeTotalG[2] = array("y" => $varP36, "label" => "36 - 24");
			} else {
				$dataPorcentajeTotalG[2] = array("y" => 0, "label" => "36 - 24");
			}

			if ($var48p != 0) {
				$varP48 += round(($var48e / $var48p) * 100, 0);
				$dataPorcentajeTotalG[3] = array("y" => $varP48, "label" => "48 - 36");
			} else {
				$dataPorcentajeTotalG[3] = array("y" => 0, "label" => "48 - 36");
			}

			if ($var60p != 0) {
				$varP60 += round(($var60e / $var60p) * 100, 0);
				$dataPorcentajeTotalG[4] = array("y" => $varP60, "label" => "60 - 48");
			} else {
				$dataPorcentajeTotalG[4] = array("y" => 0, "label" => "60 - 48");
			}

			if ($var72p != 0) {
				$varP72 += round(($var72e / $var72p) * 100, 0);
				$dataPorcentajeTotalG[5] = array("y" => $varP72, "label" => "72 - 60");
			} else {
				$dataPorcentajeTotalG[5] = array("y" => 0, "label" => "72 - 60");
			}
		} else {
			$autos = "Autos";
			$nameColumnsSecond = $autos;
			$dataFiltrosGeneral =  $this->Informe->dataAutosFiltro($autos);
			/* Iniciamos las variables en 0 XD donde se realizara la suma para el total del Informe */
			$var12e = 0;
			$var12p = 0;
			$var24e = 0;
			$var24p = 0;
			$var36e = 0;
			$var36p = 0;
			$var48e = 0;
			$var48p = 0;
			$var60e = 0;
			$var60p = 0;
			$var72e = 0;
			$var72p = 0;
			$varP12 = 0;
			$varP24 = 0;
			$varP36 = 0;
			$varP48 = 0;
			$varP60 = 0;
			$varP72 = 0;
			$dataFlotaGrafG = array();
			$dataRetailGrafG = array();
			foreach ($dataFiltrosGeneral->result() as $key) {
				if ($key->tipo_vh == 'Flota') {
					$var12P = round(($key->e_0_12 / $key->p0_12) * 100, 0);
					$dataFlotaGrafG[0] = array("y" => $var12P, "label" => "12 - 0");
					$var12P = round(($key->e_13_24 / $key->p13_24) * 100, 0);
					$dataFlotaGrafG[1] = array("y" => $var12P, "label" => "24 - 12");
					$var12P = round(($key->e_25_36 / $key->p25_36) * 100, 0);
					$dataFlotaGrafG[2] = array("y" => $var12P, "label" => "36 - 24");
					$var12P = round(($key->e_37_48 / $key->p37_48) * 100, 0);
					$dataFlotaGrafG[3] = array("y" => $var12P, "label" => "48 - 36");
					$var12P = round(($key->e_49_60 / $key->p49_60) * 100, 0);
					$dataFlotaGrafG[4] = array("y" => $var12P, "label" => "60 - 48");
					$var12P = round(($key->e_61_72 / $key->p61_72) * 100, 0);
					$dataFlotaGrafG[5] = array("y" => $var12P, "label" => "72 - 60");
				} elseif ($key->tipo_vh == 'Retail') {
					$var12P = round(($key->e_0_12 / $key->p0_12) * 100, 0);
					$dataRetailGrafG[0] = array("y" => $var12P, "label" => "12 - 0");
					$var12P = round(($key->e_13_24 / $key->p13_24) * 100, 0);
					$dataRetailGrafG[1] = array("y" => $var12P, "label" => "24 - 12");
					$var12P = round(($key->e_25_36 / $key->p25_36) * 100, 0);
					$dataRetailGrafG[2] = array("y" => $var12P, "label" => "36 - 24");
					$var12P = round(($key->e_37_48 / $key->p37_48) * 100, 0);
					$dataRetailGrafG[3] = array("y" => $var12P, "label" => "48 - 36");
					$var12P = round(($key->e_49_60 / $key->p49_60) * 100, 0);
					$dataRetailGrafG[4] = array("y" => $var12P, "label" => "60 - 48");
					$var12P = round(($key->e_61_72 / $key->p61_72) * 100, 0);
					$dataRetailGrafG[5] = array("y" => $var12P, "label" => "72 - 60");
				}
				$var12e += $key->e_0_12;
				$var12p += $key->p0_12;

				$var24e += $key->e_13_24;
				$var24p += $key->p13_24;

				$var36e += $key->e_25_36;
				$var36p += $key->p25_36;

				$var48e += $key->e_37_48;
				$var48p += $key->p37_48;

				$var60e += $key->e_49_60;
				$var60p += $key->p49_60;

				$var72e += $key->e_61_72;
				$var72p += $key->p61_72;
			}
			/* Script para capturar el porcentaje del total */
			if ($var12p != 0) {
				$varP12 += round(($var12e / $var12p) * 100, 0);
				$dataPorcentajeTotalG[0] = array("y" => $varP12, "label" => "12 - 0");
			} else {
				$dataPorcentajeTotalG[0] = array("y" => 0, "label" => "12 - 0");
			}

			if ($var24p != 0) {
				$varP24 += round(($var24e / $var24p) * 100, 0);
				$dataPorcentajeTotalG[1] = array("y" => $varP24, "label" => "24 - 12");
			} else {
				$dataPorcentajeTotalG[1] = array("y" => 0, "label" => "24 - 12");
			}

			if ($var36p != 0) {
				$varP36 += round(($var36e / $var36p) * 100, 0);
				$dataPorcentajeTotalG[2] = array("y" => $varP36, "label" => "36 - 24");
			} else {
				$dataPorcentajeTotalG[2] = array("y" => 0, "label" => "36 - 24");
			}

			if ($var48p != 0) {
				$varP48 += round(($var48e / $var48p) * 100, 0);
				$dataPorcentajeTotalG[3] = array("y" => $varP48, "label" => "48 - 36");
			} else {
				$dataPorcentajeTotalG[3] = array("y" => 0, "label" => "48 - 36");
			}

			if ($var60p != 0) {
				$varP60 += round(($var60e / $var60p) * 100, 0);
				$dataPorcentajeTotalG[4] = array("y" => $varP60, "label" => "60 - 48");
			} else {
				$dataPorcentajeTotalG[4] = array("y" => 0, "label" => "60 - 48");
			}

			if ($var72p != 0) {
				$varP72 += round(($var72e / $var72p) * 100, 0);
				$dataPorcentajeTotalG[5] = array("y" => $varP72, "label" => "72 - 60");
			} else {
				$dataPorcentajeTotalG[5] = array("y" => 0, "label" => "72 - 60");
			}
		}

		$dataFiltro = $this->Informe->dataAutosFiltro($filtro);
		if ($dataFiltro != "") {
			/* Iniciamos las variables en 0 XD donde se realizara la suma para el total del Informe */
			$var12e = 0;
			$var12p = 0;
			$var24e = 0;
			$var24p = 0;
			$var36e = 0;
			$var36p = 0;
			$var48e = 0;
			$var48p = 0;
			$var60e = 0;
			$var60p = 0;
			$var72e = 0;
			$var72p = 0;
			$varP12 = 0;
			$varP24 = 0;
			$varP36 = 0;
			$varP48 = 0;
			$varP60 = 0;
			$varP72 = 0;
			/* Iniciamos los array donde guardaremos la información para las graficas */
			$dataFlotaGraf = array();
			$dataRetailGraf = array();
			foreach ($dataFiltro->result() as $key) {
				if ($key->tipo_vh == 'Flota') {
					if ($key->p0_12 != 0) {
						$var12Pe = round(($key->e_0_12 / $key->p0_12) * 100, 0);
						$dataFlotaGraf[0] = array("y" => $var12Pe, "label" => "12 - 0");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[0] = array("y" => $var12Pe, "label" => "12 - 0");
					}
					if ($key->p13_24 != 0) {
						$var12Pe = round(($key->e_13_24 / $key->p13_24) * 100, 0);
						$dataFlotaGraf[1] = array("y" => $var12Pe, "label" => "24 - 12");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[1] = array("y" => $var12Pe, "label" => "24 - 12");
					}
					if ($key->p25_36 != 0) {
						$var12Pe = round(($key->e_25_36 / $key->p25_36) * 100, 0);
						$dataFlotaGraf[2] = array("y" => $var12Pe, "label" => "36 - 24");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[2] = array("y" => $var12Pe, "label" => "36 - 24");
					}
					if ($key->p37_48 != 0) {
						$var12Pe = round(($key->e_37_48 / $key->p37_48) * 100, 0);
						$dataFlotaGraf[3] = array("y" => $var12Pe, "label" => "48 - 36");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[3] = array("y" => $var12Pe, "label" => "48 - 36");
					}
					if ($key->p49_60 != 0) {
						$var12Pe = round(($key->e_49_60 / $key->p49_60) * 100, 0);
						$dataFlotaGraf[4] = array("y" => $var12Pe, "label" => "60 - 48");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[4] = array("y" => $var12Pe, "label" => "60 - 48");
					}
					if ($key->p61_72 != 0) {
						$var12Pe = round(($key->e_61_72 / $key->p61_72) * 100, 0);
						$dataFlotaGraf[5] = array("y" => $var12Pe, "label" => "72 - 60");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[5] = array("y" => $var12Pe, "label" => "72 - 60");
					}
				} elseif ($key->tipo_vh == 'Retail') {
					if ($key->p0_12 != 0) {
						$var12Pe = round(($key->e_0_12 / $key->p0_12) * 100, 0);
						$dataRetailGraf[0] = array("y" => $var12Pe, "label" => "12 - 0");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[0] = array("y" => $var12Pe, "label" => "12 - 0");
					}
					if ($key->p13_24 != 0) {
						$var12Pe = round(($key->e_13_24 / $key->p13_24) * 100, 0);
						$dataRetailGraf[1] = array("y" => $var12Pe, "label" => "24 - 12");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[1] = array("y" => $var12Pe, "label" => "24 - 12");
					}
					if ($key->p25_36 != 0) {
						$var12Pe = round(($key->e_25_36 / $key->p25_36) * 100, 0);
						$dataRetailGraf[2] = array("y" => $var12Pe, "label" => "36 - 24");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[2] = array("y" => $var12Pe, "label" => "36 - 24");
					}
					if ($key->p37_48 != 0) {
						$var12Pe = round(($key->e_37_48 / $key->p37_48) * 100, 0);
						$dataRetailGraf[3] = array("y" => $var12Pe, "label" => "48 - 36");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[3] = array("y" => $var12Pe, "label" => "48 - 36");
					}
					if ($key->p49_60 != 0) {
						$var12Pe = round(($key->e_49_60 / $key->p49_60) * 100, 0);
						$dataRetailGraf[4] = array("y" => $var12Pe, "label" => "60 - 48");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[4] = array("y" => $var12Pe, "label" => "60 - 48");
					}
					if ($key->p61_72 != 0) {
						$var12Pe = round(($key->e_61_72 / $key->p61_72) * 100, 0);
						$dataRetailGraf[5] = array("y" => $var12Pe, "label" => "72 - 60");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[5] = array("y" => $var12Pe, "label" => "72 - 60");
					}
				}
				/* Informacion de sumas y porcentajes */
				$var12e += $key->e_0_12;
				$var12p += $key->p0_12;

				$var24e += $key->e_13_24;
				$var24p += $key->p13_24;

				$var36e += $key->e_25_36;
				$var36p += $key->p25_36;

				$var48e += $key->e_37_48;
				$var48p += $key->p37_48;

				$var60e += $key->e_49_60;
				$var60p += $key->p49_60;

				$var72e += $key->e_61_72;
				$var72p += $key->p61_72;
			}

			if ($var12p != 0) {
				$varP12 += round(($var12e / $var12p) * 100, 0);
				$dataPorcentajeTotal[0] = array("y" => $varP12, "label" => "12 - 0");
			} else {
				$dataPorcentajeTotal[0] = array("y" => 0, "label" => "12 - 0");
			}

			if ($var24p != 0) {
				$varP24 += round(($var24e / $var24p) * 100, 0);
				$dataPorcentajeTotal[1] = array("y" => $varP24, "label" => "24 - 12");
			} else {
				$dataPorcentajeTotal[1] = array("y" => 0, "label" => "24 - 12");
			}

			if ($var36p != 0) {
				$varP36 += round(($var36e / $var36p) * 100, 0);
				$dataPorcentajeTotal[2] = array("y" => $varP36, "label" => "36 - 24");
			} else {
				$dataPorcentajeTotal[2] = array("y" => 0, "label" => "36 - 24");
			}

			if ($var48p != 0) {
				$varP48 += round(($var48e / $var48p) * 100, 0);
				$dataPorcentajeTotal[3] = array("y" => $varP48, "label" => "48 - 36");
			} else {
				$dataPorcentajeTotal[3] = array("y" => 0, "label" => "48 - 36");
			}

			if ($var60p != 0) {
				$varP60 += round(($var60e / $var60p) * 100, 0);
				$dataPorcentajeTotal[4] = array("y" => $varP60, "label" => "60 - 48");
			} else {
				$dataPorcentajeTotal[4] = array("y" => 0, "label" => "60 - 48");
			}

			if ($var72p != 0) {
				$varP72 += round(($var72e / $var72p) * 100, 0);
				$dataPorcentajeTotal[5] = array("y" => $varP72, "label" => "72 - 60");
			} else {
				$dataPorcentajeTotal[5] = array("y" => 0, "label" => "72 - 60");
			}

			$dataTotal = (array) [
				'var12e' => $var12e,
				'var12p' => $var12p,
				'varP12' => $varP12,
				'var24e' => $var24e,
				'var24p' => $var24p,
				'varP24' => $varP24,
				'var36e' => $var36e,
				'var36p' => $var36p,
				'varP36' => $varP36,
				'var48e' => $var48e,
				'var48p' => $var48p,
				'varP48' => $varP48,
				'var60e' => $var60e,
				'var60p' => $var60p,
				'varP60' => $varP60,
				'var72e' => $var72e,
				'var72p' => $var72p,
				'varP72' => $varP72
			];

			$arr_user = array(
				'dataPorcentajeTotal' => ($dataPorcentajeTotal),
				'dataRetailGraf' => ($dataRetailGraf),
				'dataFlotaGraf' => ($dataFlotaGraf),
				'dataPorcentajeTotalG' => ($dataPorcentajeTotalG),
				'dataRetailGrafG' => ($dataRetailGrafG),
				'dataFlotaGrafG' => ($dataFlotaGrafG),
				'nameColumnsSecond' => ($nameColumnsSecond)

			);

			echo json_encode($arr_user);
		} else {
			echo "Error";
		}
	}
	/* Enviar info con datos dependiendo del filtro B&C para las graficas */
	public function getDatosGraficasByC()
	{
		$this->load->model('Informe');
		$filtro = $this->input->POST('filtro');

		if ($filtro == "B&C") {
			$nameColumnsSecond = "General";
			$dataFiltrosGeneral =  $this->Informe->getVentas72();
			/* Iniciamos las variables en 0 XD donde se realizara la suma para el total del Informe */
			$var12e = 0;
			$var12p = 0;
			$var24e = 0;
			$var24p = 0;
			$var36e = 0;
			$var36p = 0;
			$var48e = 0;
			$var48p = 0;
			$var60e = 0;
			$var60p = 0;
			$var72e = 0;
			$var72p = 0;
			$varP12 = 0;
			$varP24 = 0;
			$varP36 = 0;
			$varP48 = 0;
			$varP60 = 0;
			$varP72 = 0;
			$dataFlotaGrafG = array();
			$dataRetailGrafG = array();
			foreach ($dataFiltrosGeneral->result() as $key) {
				if ($key->tipo_vh == 'Flota') {
					$var12P = round(($key->e_0_12 / $key->p0_12) * 100, 0);
					$dataFlotaGrafG[0] = array("y" => $var12P, "label" => "12 - 0");
					$var12P = round(($key->e_13_24 / $key->p13_24) * 100, 0);
					$dataFlotaGrafG[1] = array("y" => $var12P, "label" => "24 - 12");
					$var12P = round(($key->e_25_36 / $key->p25_36) * 100, 0);
					$dataFlotaGrafG[2] = array("y" => $var12P, "label" => "36 - 24");
					$var12P = round(($key->e_37_48 / $key->p37_48) * 100, 0);
					$dataFlotaGrafG[3] = array("y" => $var12P, "label" => "48 - 36");
					$var12P = round(($key->e_49_60 / $key->p49_60) * 100, 0);
					$dataFlotaGrafG[4] = array("y" => $var12P, "label" => "60 - 48");
					$var12P = round(($key->e_61_72 / $key->p61_72) * 100, 0);
					$dataFlotaGrafG[5] = array("y" => $var12P, "label" => "72 - 60");
				} elseif ($key->tipo_vh == 'Retail') {
					$var12P = round(($key->e_0_12 / $key->p0_12) * 100, 0);
					$dataRetailGrafG[0] = array("y" => $var12P, "label" => "12 - 0");
					$var12P = round(($key->e_13_24 / $key->p13_24) * 100, 0);
					$dataRetailGrafG[1] = array("y" => $var12P, "label" => "24 - 12");
					$var12P = round(($key->e_25_36 / $key->p25_36) * 100, 0);
					$dataRetailGrafG[2] = array("y" => $var12P, "label" => "36 - 24");
					$var12P = round(($key->e_37_48 / $key->p37_48) * 100, 0);
					$dataRetailGrafG[3] = array("y" => $var12P, "label" => "48 - 36");
					$var12P = round(($key->e_49_60 / $key->p49_60) * 100, 0);
					$dataRetailGrafG[4] = array("y" => $var12P, "label" => "60 - 48");
					$var12P = round(($key->e_61_72 / $key->p61_72) * 100, 0);
					$dataRetailGrafG[5] = array("y" => $var12P, "label" => "72 - 60");
				}
				$var12e += $key->e_0_12;
				$var12p += $key->p0_12;

				$var24e += $key->e_13_24;
				$var24p += $key->p13_24;

				$var36e += $key->e_25_36;
				$var36p += $key->p25_36;

				$var48e += $key->e_37_48;
				$var48p += $key->p37_48;

				$var60e += $key->e_49_60;
				$var60p += $key->p49_60;

				$var72e += $key->e_61_72;
				$var72p += $key->p61_72;
			}
			/* Script para capturar el porcentaje del total */
			if ($var12p != 0) {
				$varP12 += round(($var12e / $var12p) * 100, 0);
				$dataPorcentajeTotalG[0] = array("y" => $varP12, "label" => "12 - 0");
			} else {
				$dataPorcentajeTotalG[0] = array("y" => 0, "label" => "12 - 0");
			}

			if ($var24p != 0) {
				$varP24 += round(($var24e / $var24p) * 100, 0);
				$dataPorcentajeTotalG[1] = array("y" => $varP24, "label" => "24 - 12");
			} else {
				$dataPorcentajeTotalG[1] = array("y" => 0, "label" => "24 - 12");
			}

			if ($var36p != 0) {
				$varP36 += round(($var36e / $var36p) * 100, 0);
				$dataPorcentajeTotalG[2] = array("y" => $varP36, "label" => "36 - 24");
			} else {
				$dataPorcentajeTotalG[2] = array("y" => 0, "label" => "36 - 24");
			}

			if ($var48p != 0) {
				$varP48 += round(($var48e / $var48p) * 100, 0);
				$dataPorcentajeTotalG[3] = array("y" => $varP48, "label" => "48 - 36");
			} else {
				$dataPorcentajeTotalG[3] = array("y" => 0, "label" => "48 - 36");
			}

			if ($var60p != 0) {
				$varP60 += round(($var60e / $var60p) * 100, 0);
				$dataPorcentajeTotalG[4] = array("y" => $varP60, "label" => "60 - 48");
			} else {
				$dataPorcentajeTotalG[4] = array("y" => 0, "label" => "60 - 48");
			}

			if ($var72p != 0) {
				$varP72 += round(($var72e / $var72p) * 100, 0);
				$dataPorcentajeTotalG[5] = array("y" => $varP72, "label" => "72 - 60");
			} else {
				$dataPorcentajeTotalG[5] = array("y" => 0, "label" => "72 - 60");
			}
		} else {
			$ByC = "B&C";
			$nameColumnsSecond = "B&C";
			$dataFiltrosGeneral =  $this->Informe->dataByCFiltro($ByC);
			/* Iniciamos las variables en 0 XD donde se realizara la suma para el total del Informe */
			$var12e = 0;
			$var12p = 0;
			$var24e = 0;
			$var24p = 0;
			$var36e = 0;
			$var36p = 0;
			$var48e = 0;
			$var48p = 0;
			$var60e = 0;
			$var60p = 0;
			$var72e = 0;
			$var72p = 0;
			$varP12 = 0;
			$varP24 = 0;
			$varP36 = 0;
			$varP48 = 0;
			$varP60 = 0;
			$varP72 = 0;
			$dataFlotaGrafG = array();
			$dataRetailGrafG = array();
			foreach ($dataFiltrosGeneral->result() as $key) {
				if ($key->tipo_vh == 'Flota') {
					$var12P = round(($key->e_0_12 / $key->p0_12) * 100, 0);
					$dataFlotaGrafG[0] = array("y" => $var12P, "label" => "12 - 0");
					$var12P = round(($key->e_13_24 / $key->p13_24) * 100, 0);
					$dataFlotaGrafG[1] = array("y" => $var12P, "label" => "24 - 12");
					$var12P = round(($key->e_25_36 / $key->p25_36) * 100, 0);
					$dataFlotaGrafG[2] = array("y" => $var12P, "label" => "36 - 24");
					$var12P = round(($key->e_37_48 / $key->p37_48) * 100, 0);
					$dataFlotaGrafG[3] = array("y" => $var12P, "label" => "48 - 36");
					$var12P = round(($key->e_49_60 / $key->p49_60) * 100, 0);
					$dataFlotaGrafG[4] = array("y" => $var12P, "label" => "60 - 48");
					$var12P = round(($key->e_61_72 / $key->p61_72) * 100, 0);
					$dataFlotaGrafG[5] = array("y" => $var12P, "label" => "72 - 60");
				} elseif ($key->tipo_vh == 'Retail') {
					$var12P = round(($key->e_0_12 / $key->p0_12) * 100, 0);
					$dataRetailGrafG[0] = array("y" => $var12P, "label" => "12 - 0");
					$var12P = round(($key->e_13_24 / $key->p13_24) * 100, 0);
					$dataRetailGrafG[1] = array("y" => $var12P, "label" => "24 - 12");
					$var12P = round(($key->e_25_36 / $key->p25_36) * 100, 0);
					$dataRetailGrafG[2] = array("y" => $var12P, "label" => "36 - 24");
					$var12P = round(($key->e_37_48 / $key->p37_48) * 100, 0);
					$dataRetailGrafG[3] = array("y" => $var12P, "label" => "48 - 36");
					$var12P = round(($key->e_49_60 / $key->p49_60) * 100, 0);
					$dataRetailGrafG[4] = array("y" => $var12P, "label" => "60 - 48");
					$var12P = round(($key->e_61_72 / $key->p61_72) * 100, 0);
					$dataRetailGrafG[5] = array("y" => $var12P, "label" => "72 - 60");
				}
				$var12e += $key->e_0_12;
				$var12p += $key->p0_12;

				$var24e += $key->e_13_24;
				$var24p += $key->p13_24;

				$var36e += $key->e_25_36;
				$var36p += $key->p25_36;

				$var48e += $key->e_37_48;
				$var48p += $key->p37_48;

				$var60e += $key->e_49_60;
				$var60p += $key->p49_60;

				$var72e += $key->e_61_72;
				$var72p += $key->p61_72;
			}
			/* Script para capturar el porcentaje del total */
			if ($var12p != 0) {
				$varP12 += round(($var12e / $var12p) * 100, 0);
				$dataPorcentajeTotalG[0] = array("y" => $varP12, "label" => "12 - 0");
			} else {
				$dataPorcentajeTotalG[0] = array("y" => 0, "label" => "12 - 0");
			}

			if ($var24p != 0) {
				$varP24 += round(($var24e / $var24p) * 100, 0);
				$dataPorcentajeTotalG[1] = array("y" => $varP24, "label" => "24 - 12");
			} else {
				$dataPorcentajeTotalG[1] = array("y" => 0, "label" => "24 - 12");
			}

			if ($var36p != 0) {
				$varP36 += round(($var36e / $var36p) * 100, 0);
				$dataPorcentajeTotalG[2] = array("y" => $varP36, "label" => "36 - 24");
			} else {
				$dataPorcentajeTotalG[2] = array("y" => 0, "label" => "36 - 24");
			}

			if ($var48p != 0) {
				$varP48 += round(($var48e / $var48p) * 100, 0);
				$dataPorcentajeTotalG[3] = array("y" => $varP48, "label" => "48 - 36");
			} else {
				$dataPorcentajeTotalG[3] = array("y" => 0, "label" => "48 - 36");
			}

			if ($var60p != 0) {
				$varP60 += round(($var60e / $var60p) * 100, 0);
				$dataPorcentajeTotalG[4] = array("y" => $varP60, "label" => "60 - 48");
			} else {
				$dataPorcentajeTotalG[4] = array("y" => 0, "label" => "60 - 48");
			}

			if ($var72p != 0) {
				$varP72 += round(($var72e / $var72p) * 100, 0);
				$dataPorcentajeTotalG[5] = array("y" => $varP72, "label" => "72 - 60");
			} else {
				$dataPorcentajeTotalG[5] = array("y" => 0, "label" => "72 - 60");
			}
		}
		$dataFiltro = $this->Informe->dataByCFiltro($filtro);
		if ($dataFiltro != "") {
			/* Iniciamos las variables en 0 XD donde se realizara la suma para el total del Informe */
			$var12e = 0;
			$var12p = 0;
			$var24e = 0;
			$var24p = 0;
			$var36e = 0;
			$var36p = 0;
			$var48e = 0;
			$var48p = 0;
			$var60e = 0;
			$var60p = 0;
			$var72e = 0;
			$var72p = 0;
			$varP12 = 0;
			$varP24 = 0;
			$varP36 = 0;
			$varP48 = 0;
			$varP60 = 0;
			$varP72 = 0;
			/* Iniciamos los array donde guardaremos la información para las graficas */
			$dataFlotaGraf = array();
			$dataRetailGraf = array();
			foreach ($dataFiltro->result() as $key) {
				if ($key->tipo_vh == 'Flota') {
					if ($key->p0_12 != 0) {
						$var12Pe = round(($key->e_0_12 / $key->p0_12) * 100, 0);
						$dataFlotaGraf[0] = array("y" => $var12Pe, "label" => "12 - 0");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[0] = array("y" => $var12Pe, "label" => "12 - 0");
					}
					if ($key->p13_24 != 0) {
						$var12Pe = round(($key->e_13_24 / $key->p13_24) * 100, 0);
						$dataFlotaGraf[1] = array("y" => $var12Pe, "label" => "24 - 12");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[1] = array("y" => $var12Pe, "label" => "24 - 12");
					}
					if ($key->p25_36 != 0) {
						$var12Pe = round(($key->e_25_36 / $key->p25_36) * 100, 0);
						$dataFlotaGraf[2] = array("y" => $var12Pe, "label" => "36 - 24");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[2] = array("y" => $var12Pe, "label" => "36 - 24");
					}
					if ($key->p37_48 != 0) {
						$var12Pe = round(($key->e_37_48 / $key->p37_48) * 100, 0);
						$dataFlotaGraf[3] = array("y" => $var12Pe, "label" => "48 - 36");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[3] = array("y" => $var12Pe, "label" => "48 - 36");
					}
					if ($key->p49_60 != 0) {
						$var12Pe = round(($key->e_49_60 / $key->p49_60) * 100, 0);
						$dataFlotaGraf[4] = array("y" => $var12Pe, "label" => "60 - 48");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[4] = array("y" => $var12Pe, "label" => "60 - 48");
					}
					if ($key->p61_72 != 0) {
						$var12Pe = round(($key->e_61_72 / $key->p61_72) * 100, 0);
						$dataFlotaGraf[5] = array("y" => $var12Pe, "label" => "72 - 60");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[5] = array("y" => $var12Pe, "label" => "72 - 60");
					}
				} elseif ($key->tipo_vh == 'Retail') {
					if ($key->p0_12 != 0) {
						$var12Pe = round(($key->e_0_12 / $key->p0_12) * 100, 0);
						$dataRetailGraf[0] = array("y" => $var12Pe, "label" => "12 - 0");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[0] = array("y" => $var12Pe, "label" => "12 - 0");
					}
					if ($key->p13_24 != 0) {
						$var12Pe = round(($key->e_13_24 / $key->p13_24) * 100, 0);
						$dataRetailGraf[1] = array("y" => $var12Pe, "label" => "24 - 12");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[1] = array("y" => $var12Pe, "label" => "24 - 12");
					}
					if ($key->p25_36 != 0) {
						$var12Pe = round(($key->e_25_36 / $key->p25_36) * 100, 0);
						$dataRetailGraf[2] = array("y" => $var12Pe, "label" => "36 - 24");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[2] = array("y" => $var12Pe, "label" => "36 - 24");
					}
					if ($key->p37_48 != 0) {
						$var12Pe = round(($key->e_37_48 / $key->p37_48) * 100, 0);
						$dataRetailGraf[3] = array("y" => $var12Pe, "label" => "48 - 36");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[3] = array("y" => $var12Pe, "label" => "48 - 36");
					}
					if ($key->p49_60 != 0) {
						$var12Pe = round(($key->e_49_60 / $key->p49_60) * 100, 0);
						$dataRetailGraf[4] = array("y" => $var12Pe, "label" => "60 - 48");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[4] = array("y" => $var12Pe, "label" => "60 - 48");
					}
					if ($key->p61_72 != 0) {
						$var12Pe = round(($key->e_61_72 / $key->p61_72) * 100, 0);
						$dataRetailGraf[5] = array("y" => $var12Pe, "label" => "72 - 60");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[5] = array("y" => $var12Pe, "label" => "72 - 60");
					}
				}
				/* Informacion de sumas y porcentajes */
				$var12e += $key->e_0_12;
				$var12p += $key->p0_12;

				$var24e += $key->e_13_24;
				$var24p += $key->p13_24;

				$var36e += $key->e_25_36;
				$var36p += $key->p25_36;

				$var48e += $key->e_37_48;
				$var48p += $key->p37_48;

				$var60e += $key->e_49_60;
				$var60p += $key->p49_60;

				$var72e += $key->e_61_72;
				$var72p += $key->p61_72;
			}

			if ($var12p != 0) {
				$varP12 += round(($var12e / $var12p) * 100, 0);
				$dataPorcentajeTotal[0] = array("y" => $varP12, "label" => "12 - 0");
			} else {
				$dataPorcentajeTotal[0] = array("y" => 0, "label" => "12 - 0");
			}

			if ($var24p != 0) {
				$varP24 += round(($var24e / $var24p) * 100, 0);
				$dataPorcentajeTotal[1] = array("y" => $varP24, "label" => "24 - 12");
			} else {
				$dataPorcentajeTotal[1] = array("y" => 0, "label" => "24 - 12");
			}

			if ($var36p != 0) {
				$varP36 += round(($var36e / $var36p) * 100, 0);
				$dataPorcentajeTotal[2] = array("y" => $varP36, "label" => "36 - 24");
			} else {
				$dataPorcentajeTotal[2] = array("y" => 0, "label" => "36 - 24");
			}

			if ($var48p != 0) {
				$varP48 += round(($var48e / $var48p) * 100, 0);
				$dataPorcentajeTotal[3] = array("y" => $varP48, "label" => "48 - 36");
			} else {
				$dataPorcentajeTotal[3] = array("y" => 0, "label" => "48 - 36");
			}

			if ($var60p != 0) {
				$varP60 += round(($var60e / $var60p) * 100, 0);
				$dataPorcentajeTotal[4] = array("y" => $varP60, "label" => "60 - 48");
			} else {
				$dataPorcentajeTotal[4] = array("y" => 0, "label" => "60 - 48");
			}

			if ($var72p != 0) {
				$varP72 += round(($var72e / $var72p) * 100, 0);
				$dataPorcentajeTotal[5] = array("y" => $varP72, "label" => "72 - 60");
			} else {
				$dataPorcentajeTotal[5] = array("y" => 0, "label" => "72 - 60");
			}
			$arr_user = array(
				'dataPorcentajeTotal' => ($dataPorcentajeTotal),
				'dataRetailGraf' => ($dataRetailGraf),
				'dataFlotaGraf' => ($dataFlotaGraf),
				'dataPorcentajeTotalG' => ($dataPorcentajeTotalG),
				'dataRetailGrafG' => ($dataRetailGrafG),
				'dataFlotaGrafG' => ($dataFlotaGrafG),
				'nameColumnsSecond' => ($nameColumnsSecond)

			);

			echo json_encode($arr_user);
		} else {
			echo "Error";
		}
	}
	/* Traer la familia de acuerdo al segmento */
	public function getFamilia()
	{
		$this->load->model('Informe');
		$segmento = $this->input->GET('segmento');
		$data = $this->Informe->getFamilia($segmento);
		echo '<label for="">Seleccione la familia</label>
		<select class="js-example-basic-multiple" multiple="multiple" style="width: 100%" name="segmento" id="familia">';

		foreach ($data->result() as $key) {

			echo '<option value="' . $key->familia . '">' . $key->familia . '</option>';
		}

		echo '</select>';
	}
	/*Informacion Filtro por familias suma total */
	public function filtroInformeFamilia()
	{
		$this->load->model('Informe');
		$familia = $this->input->GET('selected');

		/* Lo siguiente se realizo para eliminar los caracteres especiales que venian en el string XD */
		$familia2 = str_replace('"', '\'', $familia);
		$familia3 = str_replace('[', '', $familia2);
		$familia4 = str_replace(']', '', $familia3);
		$familia5 = str_replace('\'', ' ', $familia4);

		$dataFiltro = $this->Informe->filtroFamilia($familia4);
		if ($dataFiltro != "") {
			/* Iniciamos las variables en 0 XD donde se realizara la suma para el total del Informe */
			$var12e = 0;
			$var12p = 0;
			$var24e = 0;
			$var24p = 0;
			$var36e = 0;
			$var36p = 0;
			$var48e = 0;
			$var48p = 0;
			$var60e = 0;
			$var60p = 0;
			$var72e = 0;
			$var72p = 0;
			$varP12 = 0;
			$varP24 = 0;
			$varP36 = 0;
			$varP48 = 0;
			$varP60 = 0;
			$varP72 = 0;
			/* Script para tener los datos totales suma(Flota+Retail) */
			foreach ($dataFiltro->result() as $key) {
				/* Informacion de sumas y porcentajes */
				$var12e += $key->e_0_12;
				$var12p += $key->p_0_12;

				$var24e += $key->e_13_24;
				$var24p += $key->p_13_24;

				$var36e += $key->e_25_36;
				$var36p += $key->p_25_36;

				$var48e += $key->e_37_48;
				$var48p += $key->p_37_48;

				$var60e += $key->e_49_60;
				$var60p += $key->p_49_60;

				$var72e += $key->e_61_72;
				$var72p += $key->p_61_72;
			}

			if ($var12p != 0) {
				$varP12 += round(($var12e / $var12p) * 100, 0);
				$dataPorcentajeTotal[0] = array("y" => $varP12, "label" => "12 - 0");
			} else {
				$dataPorcentajeTotal[0] = array("y" => 0, "label" => "12 - 0");
			}

			if ($var24p != 0) {
				$varP24 += round(($var24e / $var24p) * 100, 0);
				$dataPorcentajeTotal[1] = array("y" => $varP24, "label" => "24 - 12");
			} else {
				$dataPorcentajeTotal[1] = array("y" => 0, "label" => "24 - 12");
			}

			if ($var36p != 0) {
				$varP36 += round(($var36e / $var36p) * 100, 0);
				$dataPorcentajeTotal[2] = array("y" => $varP36, "label" => "36 - 24");
			} else {
				$dataPorcentajeTotal[2] = array("y" => 0, "label" => "36 - 24");
			}

			if ($var48p != 0) {
				$varP48 += round(($var48e / $var48p) * 100, 0);
				$dataPorcentajeTotal[3] = array("y" => $varP48, "label" => "48 - 36");
			} else {
				$dataPorcentajeTotal[3] = array("y" => 0, "label" => "48 - 36");
			}

			if ($var60p != 0) {
				$varP60 += round(($var60e / $var60p) * 100, 0);
				$dataPorcentajeTotal[4] = array("y" => $varP60, "label" => "60 - 48");
			} else {
				$dataPorcentajeTotal[4] = array("y" => 0, "label" => "60 - 48");
			}

			if ($var72p != 0) {
				$varP72 += round(($var72e / $var72p) * 100, 0);
				$dataPorcentajeTotal[5] = array("y" => $varP72, "label" => "72 - 60");
			} else {
				$dataPorcentajeTotal[5] = array("y" => 0, "label" => "72 - 60");
			}

			$dataTotal = (array) [
				'var12e' => $var12e,
				'var12p' => $var12p,
				'varP12' => $varP12,
				'var24e' => $var24e,
				'var24p' => $var24p,
				'varP24' => $varP24,
				'var36e' => $var36e,
				'var36p' => $var36p,
				'varP36' => $varP36,
				'var48e' => $var48e,
				'var48p' => $var48p,
				'varP48' => $varP48,
				'var60e' => $var60e,
				'var60p' => $var60p,
				'varP60' => $varP60,
				'var72e' => $var72e,
				'var72p' => $var72p,
				'varP72' => $varP72
			];
			/* Encabezado de la tabla */
			echo '
			<div class="table-responsive" id="tablatotal">
			<div><label class="col-lg-12 text-center lead">' . $familia5 . '</label></div>
			<table class="table nowrap table-striped tabla2" id="tabla_uno">
				<thead class="bg-dark">
					<tr>
						<th width="10%" class="text-center">Parque</th>
						<th width="10%" class="text-center">Entrada Única Último Año 4-5</th>
						<th width="10%" class="text-center">Total Ventas</th>
						<th width="10%" class="text-center">Retención Propia</th>
						<th width="60%" class="text-center">Tendencia</th>
					</tr>
				</thead>
				<tbody>
				';

			foreach ($dataFiltro->result() as $key) {
				$retVal1 = ($key->p_0_12 != 0) ? round(($key->e_0_12 / $key->p_0_12) * 100, 0) : 0;
				$retVal2 = ($key->p_13_24 != 0) ? round(($key->e_13_24 / $key->p_13_24) * 100, 0) : 0;
				$retVal3 = ($key->p_25_36 != 0) ? round(($key->e_25_36 / $key->p_25_36) * 100, 0) : 0;
				$retVal4 = ($key->p_37_48 != 0) ? round(($key->e_37_48 / $key->p_37_48) * 100, 0) : 0;
				$retVal5 = ($key->p_49_60 != 0) ? round(($key->e_49_60 / $key->p_49_60) * 100, 0) : 0;
				$retVal6 = ($key->p_61_72 != 0) ? round(($key->e_61_72 / $key->p_61_72) * 100, 0) : 0;

				/* Tabla para Flota y Retail */


				echo '	<tr>
									<th colspan="5" class="text-center" style="background-color: darkgray; color:white;"><h3>' . $key->tipo_vh . '</h3></th>
								</tr>
								<tr>
									<th class="text-center">12-0</th>
									<td class="text-center">' . $key->e_0_12 . '</td>
									<td class="text-center">' . $key->p_0_12 . '</td>	
									
									<td class="text-center">' . $retVal1 . '%</td>
									<td rowspan="6" class="text-center">
									<div class="card card-info" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
										<div class="card-header">
											<h3 class="card-title">' . $key->tipo_vh . '</h3>

											<div class="card-tools">
												<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
												</button>
											</div>
											<!-- /.card-tools -->
										</div>
										<!-- /.card-header -->
										<div class="card-body">
										<div id="chartContainer' . $key->tipo_vh . '2" style="height: 250px; width: 80%;"></div>
										</div>
										<!-- /.card-body -->
									</div>
									</td>
								</tr>
								<tr>
									<th class="text-center">24-12</th>
									<td class="text-center">' . $key->e_13_24 . '</td>
									<td class="text-center">' . $key->p_13_24 . '</td>
									<td class="text-center">' . $retVal2 . '%</td>
									
								</tr>
								<tr>
									<th class="text-center">36-24</th>
									<td class="text-center">' . $key->e_25_36 . '</td>
									<td class="text-center">' . $key->p_25_36 . '</td>
									<td class="text-center">' . $retVal3  . '%</td>
									
								</tr>
								<tr>
									<th class="text-center">48-36</th>
									<td class="text-center">' . $key->e_37_48 . '</td>
									<td class="text-center">' . $key->p_37_48 . '</td>
									<td class="text-center">' . $retVal4  . '%</td>
									
								</tr>
								<tr>
									<th class="text-center">60-48</th>
									<td class="text-center">' . $key->e_49_60 . '</td>
									<td class="text-center">' . $key->p_49_60 . '</td>
									<td class="text-center">' . $retVal5  . '%</td>
									
								</tr>
								<tr>
									<th class="text-center">72-60</th>
									<td class="text-center">' . $key->e_61_72 . '</td>
									<td class="text-center">' . $key->p_61_72 . '</td>
									<td class="text-center">' . $retVal6  . '%</td>
									
								</tr>									

								';
			}
			/* Tabla para el total! */
			echo '
						<tr>
							<th colspan="5" class="text-center" style="background-color: darkgray; color:white"><h3>Total</h3></th>
						</tr>
						<tr>
									<th class="text-center">12-0</th>
									<td class="text-center parque-12">' . $dataTotal['var12e'] . '</td>
									<td class="text-center parque-12">' . $dataTotal['var12p'] . '</td>											
									<td class="text-center parque-12">' . $dataTotal['varP12'] . '%</td>
									<td rowspan="6" class="text-center">
									<div class="card card-info" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
										<div class="card-header">
											<h3 class="card-title">Total</h3>

											<div class="card-tools">
												<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
												</button>
											</div>
											<!-- /.card-tools -->
										</div>
										<!-- /.card-header -->
										<div class="card-body">
										<div id="chartContainerTotal2" style="height: 250px; width: 80%;"></div>
										</div>
										<!-- /.card-body -->
									</div>
									</td>
								</tr>
								<tr>
									<th class="text-center">24-12</th>
									<td class="text-center parque-24">' . $dataTotal['var24e'] . '</td>
									<td class="text-center parque-24">' . $dataTotal['var24p'] . '</td>											
									<td class="text-center parque-24">' . $dataTotal['varP24'] . '%</td>
									
								</tr>
								<tr>
									<th class="text-center">36-24</th>
									<td class="text-center parque-36">' . $dataTotal['var36e'] . '</td>
									<td class="text-center parque-36">' . $dataTotal['var36p'] . '</td>											
									<td class="text-center parque-36">' . $dataTotal['varP36'] . '%</td>		
								</tr>
								<tr>
									<th class="text-center">48-36</th>
									<td class="text-center parque-48">' . $dataTotal['var48e'] . '</td>
									<td class="text-center parque-48">' . $dataTotal['var48p'] . '</td>											
									<td class="text-center parque-48">' . $dataTotal['varP48'] . '%</td>											
								</tr>
								<tr>
									<th class="text-center">60-48</th>
									<td class="text-center parque-60">' . $dataTotal['var60e'] . '</td>
									<td class="text-center parque-60">' . $dataTotal['var60p'] . '</td>											
									<td class="text-center parque-60">' . $dataTotal['varP60'] . '%</td>
									
								</tr>
								<tr>
									<th class="text-center">72-60</th>
									<td class="text-center parque-72">' . $dataTotal['var72e'] . '</td>
									<td class="text-center parque-72">' . $dataTotal['var72p'] . '</td>											
									<td class="text-center parque-72">' . $dataTotal['varP72'] . '%</td>
									
								</tr>
						
						';

			echo '

				</tbody>
			</table>

		</div>
			';
		} else {
			echo "Error";
		}
	}
	/* Enviar info con datos dependiendo del filtro Familia para las graficas */
	public function getDatosGraficasFamilia()
	{
		$this->load->model('Informe');
		$filtro = $this->input->GET('filtro');


		/* Lo siguiente se realizo para eliminar los caracteres especiales que venian en el string XD */
		$familia2 = str_replace('"', '\'', $filtro);
		$familia3 = str_replace('[', '', $familia2);
		$familia4 = str_replace(']', '', $familia3);

		$dataFiltro = $this->Informe->filtroFamiliaGrafica($familia4);
		if ($dataFiltro != "") {
			/* Iniciamos las variables en 0 XD donde se realizara la suma para el total del Informe */
			$var12e = 0;
			$var12p = 0;
			$var24e = 0;
			$var24p = 0;
			$var36e = 0;
			$var36p = 0;
			$var48e = 0;
			$var48p = 0;
			$var60e = 0;
			$var60p = 0;
			$var72e = 0;
			$var72p = 0;
			$varP12 = 0;
			$varP24 = 0;
			$varP36 = 0;
			$varP48 = 0;
			$varP60 = 0;
			$varP72 = 0;
			/* Iniciamos los array donde guardaremos la información para las graficas */
			$dataFlotaGraf = array();
			$dataRetailGraf = array();
			foreach ($dataFiltro->result() as $key) {
				$nameColumnsSecond = $key->tipo;
				if ($key->tipo_vh == 'Flota') {
					if ($key->p_0_12 != 0) {
						$var12Pe = round(($key->e_0_12 / $key->p_0_12) * 100, 0);
						$dataFlotaGraf[0] = array("y" => $var12Pe, "label" => "12 - 0");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[0] = array("y" => $var12Pe, "label" => "12 - 0");
					}
					if ($key->p_13_24 != 0) {
						$var12Pe = round(($key->e_13_24 / $key->p_13_24) * 100, 0);
						$dataFlotaGraf[1] = array("y" => $var12Pe, "label" => "24 - 12");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[1] = array("y" => $var12Pe, "label" => "24 - 12");
					}
					if ($key->p_25_36 != 0) {
						$var12Pe = round(($key->e_25_36 / $key->p_25_36) * 100, 0);
						$dataFlotaGraf[2] = array("y" => $var12Pe, "label" => "36 - 24");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[2] = array("y" => $var12Pe, "label" => "36 - 24");
					}
					if ($key->p_37_48 != 0) {
						$var12Pe = round(($key->e_37_48 / $key->p_37_48) * 100, 0);
						$dataFlotaGraf[3] = array("y" => $var12Pe, "label" => "48 - 36");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[3] = array("y" => $var12Pe, "label" => "48 - 36");
					}
					if ($key->p_49_60 != 0) {
						$var12Pe = round(($key->e_49_60 / $key->p_49_60) * 100, 0);
						$dataFlotaGraf[4] = array("y" => $var12Pe, "label" => "60 - 48");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[4] = array("y" => $var12Pe, "label" => "60 - 48");
					}
					if ($key->p_61_72 != 0) {
						$var12Pe = round(($key->e_61_72 / $key->p_61_72) * 100, 0);
						$dataFlotaGraf[5] = array("y" => $var12Pe, "label" => "72 - 60");
					} else {
						$var12Pe = 0;
						$dataFlotaGraf[5] = array("y" => $var12Pe, "label" => "72 - 60");
					}
				} elseif ($key->tipo_vh == 'Retail') {
					if ($key->p_0_12 != 0) {
						$var12Pe = round(($key->e_0_12 / $key->p_0_12) * 100, 0);
						$dataRetailGraf[0] = array("y" => $var12Pe, "label" => "12 - 0");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[0] = array("y" => $var12Pe, "label" => "12 - 0");
					}
					if ($key->p_13_24 != 0) {
						$var12Pe = round(($key->e_13_24 / $key->p_13_24) * 100, 0);
						$dataRetailGraf[1] = array("y" => $var12Pe, "label" => "24 - 12");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[1] = array("y" => $var12Pe, "label" => "24 - 12");
					}
					if ($key->p_25_36 != 0) {
						$var12Pe = round(($key->e_25_36 / $key->p_25_36) * 100, 0);
						$dataRetailGraf[2] = array("y" => $var12Pe, "label" => "36 - 24");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[2] = array("y" => $var12Pe, "label" => "36 - 24");
					}
					if ($key->p_37_48 != 0) {
						$var12Pe = round(($key->e_37_48 / $key->p_37_48) * 100, 0);
						$dataRetailGraf[3] = array("y" => $var12Pe, "label" => "48 - 36");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[3] = array("y" => $var12Pe, "label" => "48 - 36");
					}
					if ($key->p_49_60 != 0) {
						$var12Pe = round(($key->e_49_60 / $key->p_49_60) * 100, 0);
						$dataRetailGraf[4] = array("y" => $var12Pe, "label" => "60 - 48");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[4] = array("y" => $var12Pe, "label" => "60 - 48");
					}
					if ($key->p_61_72 != 0) {
						$var12Pe = round(($key->e_61_72 / $key->p_61_72) * 100, 0);
						$dataRetailGraf[5] = array("y" => $var12Pe, "label" => "72 - 60");
					} else {
						$var12Pe = 0;
						$dataRetailGraf[5] = array("y" => $var12Pe, "label" => "72 - 60");
					}
				}
				/* Informacion de sumas y porcentajes */
				$var12e += $key->e_0_12;
				$var12p += $key->p_0_12;

				$var24e += $key->e_13_24;
				$var24p += $key->p_13_24;

				$var36e += $key->e_25_36;
				$var36p += $key->p_25_36;

				$var48e += $key->e_37_48;
				$var48p += $key->p_37_48;

				$var60e += $key->e_49_60;
				$var60p += $key->p_49_60;

				$var72e += $key->e_61_72;
				$var72p += $key->p_61_72;
			}

			if ($var12p != 0) {
				$varP12 += round(($var12e / $var12p) * 100, 0);
				$dataPorcentajeTotal[0] = array("y" => $varP12, "label" => "12 - 0");
			} else {
				$dataPorcentajeTotal[0] = array("y" => 0, "label" => "12 - 0");
			}

			if ($var24p != 0) {
				$varP24 += round(($var24e / $var24p) * 100, 0);
				$dataPorcentajeTotal[1] = array("y" => $varP24, "label" => "24 - 12");
			} else {
				$dataPorcentajeTotal[1] = array("y" => 0, "label" => "24 - 12");
			}

			if ($var36p != 0) {
				$varP36 += round(($var36e / $var36p) * 100, 0);
				$dataPorcentajeTotal[2] = array("y" => $varP36, "label" => "36 - 24");
			} else {
				$dataPorcentajeTotal[2] = array("y" => 0, "label" => "36 - 24");
			}

			if ($var48p != 0) {
				$varP48 += round(($var48e / $var48p) * 100, 0);
				$dataPorcentajeTotal[3] = array("y" => $varP48, "label" => "48 - 36");
			} else {
				$dataPorcentajeTotal[3] = array("y" => 0, "label" => "48 - 36");
			}

			if ($var60p != 0) {
				$varP60 += round(($var60e / $var60p) * 100, 0);
				$dataPorcentajeTotal[4] = array("y" => $varP60, "label" => "60 - 48");
			} else {
				$dataPorcentajeTotal[4] = array("y" => 0, "label" => "60 - 48");
			}

			if ($var72p != 0) {
				$varP72 += round(($var72e / $var72p) * 100, 0);
				$dataPorcentajeTotal[5] = array("y" => $varP72, "label" => "72 - 60");
			} else {
				$dataPorcentajeTotal[5] = array("y" => 0, "label" => "72 - 60");
			}

			$dataTotal = (array) [
				'var12e' => $var12e,
				'var12p' => $var12p,
				'varP12' => $varP12,
				'var24e' => $var24e,
				'var24p' => $var24p,
				'varP24' => $varP24,
				'var36e' => $var36e,
				'var36p' => $var36p,
				'varP36' => $varP36,
				'var48e' => $var48e,
				'var48p' => $var48p,
				'varP48' => $varP48,
				'var60e' => $var60e,
				'var60p' => $var60p,
				'varP60' => $varP60,
				'var72e' => $var72e,
				'var72p' => $var72p,
				'varP72' => $varP72
			];

			if ($nameColumnsSecond) {
				$dataFiltrosGeneral =  $this->Informe->dataAutosFiltro($nameColumnsSecond);
				/* Iniciamos las variables en 0 XD donde se realizara la suma para el total del Informe */
				$var12e = 0;
				$var12p = 0;
				$var24e = 0;
				$var24p = 0;
				$var36e = 0;
				$var36p = 0;
				$var48e = 0;
				$var48p = 0;
				$var60e = 0;
				$var60p = 0;
				$var72e = 0;
				$var72p = 0;
				$varP12 = 0;
				$varP24 = 0;
				$varP36 = 0;
				$varP48 = 0;
				$varP60 = 0;
				$varP72 = 0;
				$dataFlotaGrafG = array();
				$dataRetailGrafG = array();
				foreach ($dataFiltrosGeneral->result() as $key) {

					if ($key->tipo_vh == 'Flota') {
						$var12P = ($key->p0_12 != 0) ? round(($key->e_0_12 / $key->p0_12) * 100, 0) : 0;
						$dataFlotaGrafG[0] = array("y" => $var12P, "label" => "12 - 0");
						$var12P = ($key->p13_24 != 0) ? round(($key->e_13_24 / $key->p13_24) * 100, 0) : 0;
						$dataFlotaGrafG[1] = array("y" => $var12P, "label" => "24 - 12");
						$var12P = ($key->p25_36 != 0) ? round(($key->e_25_36 / $key->p25_36) * 100, 0) : 0;
						$dataFlotaGrafG[2] = array("y" => $var12P, "label" => "36 - 24");
						$var12P = ($key->p37_48 != 0) ? round(($key->e_37_48 / $key->p37_48) * 100, 0) : 0;
						$dataFlotaGrafG[3] = array("y" => $var12P, "label" => "48 - 36");
						$var12P = ($key->p49_60 != 0) ? round(($key->e_49_60 / $key->p49_60) * 100, 0) : 0;
						$dataFlotaGrafG[4] = array("y" => $var12P, "label" => "60 - 48");
						$var12P = ($key->p61_72 != 0) ? round(($key->e_61_72 / $key->p61_72) * 100, 0) : 0;
						$dataFlotaGrafG[5] = array("y" => $var12P, "label" => "72 - 60");
					} elseif ($key->tipo_vh == 'Retail') {
						$var12P = ($key->p0_12 != 0) ? round(($key->e_0_12 / $key->p0_12) * 100, 0) : 0;
						$dataRetailGrafG[0] = array("y" => $var12P, "label" => "12 - 0");
						$var12P = ($key->p13_24 != 0) ? round(($key->e_13_24 / $key->p13_24) * 100, 0) : 0;
						$dataRetailGrafG[1] = array("y" => $var12P, "label" => "24 - 12");
						$var12P = ($key->p25_36 != 0) ? round(($key->e_25_36 / $key->p25_36) * 100, 0) : 0;
						$dataRetailGrafG[2] = array("y" => $var12P, "label" => "36 - 24");
						$var12P = ($key->p37_48 != 0) ? round(($key->e_37_48 / $key->p37_48) * 100, 0) : 0;
						$dataRetailGrafG[3] = array("y" => $var12P, "label" => "48 - 36");
						$var12P = ($key->p49_60 != 0) ? round(($key->e_49_60 / $key->p49_60) * 100, 0) : 0;
						$dataRetailGrafG[4] = array("y" => $var12P, "label" => "60 - 48");
						$var12P = ($key->p61_72 != 0) ? round(($key->e_61_72 / $key->p61_72) * 100, 0) : 0;
						$dataRetailGrafG[5] = array("y" => $var12P, "label" => "72 - 60");
					}
					$var12e += $key->e_0_12;
					$var12p += $key->p0_12;

					$var24e += $key->e_13_24;
					$var24p += $key->p13_24;

					$var36e += $key->e_25_36;
					$var36p += $key->p25_36;

					$var48e += $key->e_37_48;
					$var48p += $key->p37_48;

					$var60e += $key->e_49_60;
					$var60p += $key->p49_60;

					$var72e += $key->e_61_72;
					$var72p += $key->p61_72;
				}
				/* Script para capturar el porcentaje del total */
				if ($var12p != 0) {
					$varP12 += round(($var12e / $var12p) * 100, 0);
					$dataPorcentajeTotalG[0] = array("y" => $varP12, "label" => "12 - 0");
				} else {
					$dataPorcentajeTotalG[0] = array("y" => 0, "label" => "12 - 0");
				}

				if ($var24p != 0) {
					$varP24 += round(($var24e / $var24p) * 100, 0);
					$dataPorcentajeTotalG[1] = array("y" => $varP24, "label" => "24 - 12");
				} else {
					$dataPorcentajeTotalG[1] = array("y" => 0, "label" => "24 - 12");
				}

				if ($var36p != 0) {
					$varP36 += round(($var36e / $var36p) * 100, 0);
					$dataPorcentajeTotalG[2] = array("y" => $varP36, "label" => "36 - 24");
				} else {
					$dataPorcentajeTotalG[2] = array("y" => 0, "label" => "36 - 24");
				}

				if ($var48p != 0) {
					$varP48 += round(($var48e / $var48p) * 100, 0);
					$dataPorcentajeTotalG[3] = array("y" => $varP48, "label" => "48 - 36");
				} else {
					$dataPorcentajeTotalG[3] = array("y" => 0, "label" => "48 - 36");
				}

				if ($var60p != 0) {
					$varP60 += round(($var60e / $var60p) * 100, 0);
					$dataPorcentajeTotalG[4] = array("y" => $varP60, "label" => "60 - 48");
				} else {
					$dataPorcentajeTotalG[4] = array("y" => 0, "label" => "60 - 48");
				}

				if ($var72p != 0) {
					$varP72 += round(($var72e / $var72p) * 100, 0);
					$dataPorcentajeTotalG[5] = array("y" => $varP72, "label" => "72 - 60");
				} else {
					$dataPorcentajeTotalG[5] = array("y" => 0, "label" => "72 - 60");
				}
			}

			$arr_user = array(
				'dataPorcentajeTotal' => ($dataPorcentajeTotal),
				'dataRetailGraf' => ($dataRetailGraf),
				'dataFlotaGraf' => ($dataFlotaGraf),
				'dataPorcentajeTotalG' => ($dataPorcentajeTotalG),
				'dataRetailGrafG' => ($dataRetailGrafG),
				'dataFlotaGrafG' => ($dataFlotaGrafG),
				'nameColumnsSecond' => ($nameColumnsSecond)

			);

			echo json_encode($arr_user);
		} else {
			echo "Error";
		}
	}
	public function tablaInfVentas72()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Informe');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";


			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}

			$dataAutos = $this->Informe->dataAutos();
			$dataByC = $this->Informe->dataByC();
			$dataDetalleAutos = $this->Informe->dataAutosDetalle();
			$dataDetalleByC = $this->Informe->dataByCDetalle();
			/* Obtenemos los datos de la consulta en la base de datos XD Verfiicar */


			/* Obtenemos los datos de la consulta en la base de datos */
			$dataInforme = $this->Informe->getDataInformeGeneral();
			$infoFamilia = $this->Informe->getAllFamily();

			$arr_user = array(
				'allFamilia' => $infoFamilia,
				'dataInforme' => $dataInforme,
				'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'dataAutos' => $dataAutos, 'dataByC' => $dataByC, 'dataDetalleAutos' => $dataDetalleAutos, 'dataDetalleByC' => $dataDetalleByC
			);
			//abrimos la vista
			$this->load->view("gestionVentas/tablaInformeVentas72", $arr_user);
		}
	}

	public function panel_indicadores_nps()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Informe');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";


			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu);
			//abrimos la vista
			$this->load->view("Informe_nps/inf_pac_nps", $arr_user);
		}
	}

	public function load_panel_indicadores()
	{
		//traemos los modelos
		$this->load->model('Informe');
		//traemoos la info de la base de datos
		$sedes = "'giron','barranca','bocono','rosita'";
		$data_npsLv = null;
		$data_giron = null;
		$data_barranca = null;
		$data_rosita = null;
		$data_bocono = null;
		// NPS GENERAL

		for ($i = 5; $i >= 0; $i--) {
			$data_nps_gra = $this->Informe->get_nps_gm_gra('general', $i);
			foreach ($data_nps_gra->result() as $key) {
				$npsLv = $key->Calificacion;
			}
			$data_npsLv[] = array('mes' => $key->mes, 'nps' => number_format($npsLv, 1, ",", ","));
		}
		//giron nps
		for ($i = 5; $i >= 0; $i--) {
			$data_nps_gra = $this->Informe->get_nps_gm_gra('giron', $i);
			foreach ($data_nps_gra->result() as $key) {
				$npsLv = $key->Calificacion;
			}
			$data_giron[] = array('mes' => $key->mes, 'nps' => number_format($npsLv, 1, ",", ","));
		}
		//nps rosita
		for ($i = 5; $i >= 0; $i--) {
			$data_nps_gra = $this->Informe->get_nps_gm_gra('rosita', $i);
			foreach ($data_nps_gra->result() as $key) {
				$npsLv = $key->Calificacion;
			}
			$data_rosita[] = array('mes' => $key->mes, 'nps' => number_format($npsLv, 1, ",", ","));
		}
		//nps barranca
		for ($i = 5; $i >= 0; $i--) {
			$data_nps_gra = $this->Informe->get_nps_gm_gra('barranca', $i);
			foreach ($data_nps_gra->result() as $key) {
				$npsLv = $key->Calificacion;
			}
			$data_barranca[] = array('mes' => $key->mes, 'nps' => number_format($npsLv, 1, ",", ","));
		}
		//print_r($data_barranca);die;
		//nps bocono 
		for ($i = 5; $i >= 0; $i--) {
			$data_nps_gra = $this->Informe->get_nps_gm_gra('bocono', $i);
			foreach ($data_nps_gra->result() as $key) {
				$npsLv = $key->Calificacion;
			}
			$data_bocono[] = array('mes' => $key->mes, 'nps' => number_format($npsLv, 1, ",", ","));
		}
		/*NPS TECNICOS POR SEDES*/

		echo '<h3 style="margin-top: 10px;">Panel de Indicadores NPS</h3>
			<div class="timeline">
							<!-- timeline item -->
							<div>
								<i class="fas fa-car bg-blue"></i>
								<div class="timeline-item">
									<span class="time">
										<div class="row">
										<table style="width: 100%;">
										<tr>';
		foreach ($data_npsLv as $key) {
			if ($key['nps'] >= 80 && $key['nps'] <= 100) {
				$color = "success";
			} elseif ($key['nps'] < 80) {
				$color = "danger";
			}
			echo '<td width="85" nowrap>
												<div class="col-md-2" >
													<div class="icheck-' . $color . ' d-inline">
								                        <input type="radio" name="r' . $key['mes'] . '" checked="" id="radioSuccess1">
								                        <label for="radioSuccess1" onclick="load_info_all_sede(' . $key['mes'] . ')">' . $key['nps'] . '%</label>
								                     </div>
												</div>
												</td>
											';
		}
		echo '
										</tr>
										</table>
										</div>
									</span>
									<h3 class="timeline-header no-border">NPS Livianos</h3>
								</div>
							</div>
							<!-- END timeline item -->
							<!-- timeline item -->
							<div style="margin-left: 40px;">
								<i class="fas fa-car bg-purple"></i>
								<div class="timeline-item">
									<span class="time">
										<div class="row">
										<table style="width: 100%;">
										<tr>';
		foreach ($data_giron as $key) {
			if ($key['nps'] >= 80 && $key['nps'] <= 100) {
				$color = "success";
			} elseif ($key['nps'] < 80) {
				$color = "danger";
			}
			$s = "'giron'";
			echo '<td width="85" nowrap>
												<div class="col-md-2" onclick="load_info_sede(' . $s . ',' . $key['mes'] . ');">
													<div class="icheck-' . $color . ' d-inline">
								                        <input type="radio" name="rg' . $key['mes'] . '" checked="" id="radioSuccess1">
								                        <label for="radioSuccess1">' . $key['nps'] . '%</label>
								                     </div>
												</div>
												</td>
											';
		}

		echo '
										</tr>
										</table>
										</div>
									</span>
									<h3 class="timeline-header no-border">NPS Giron</h3>
								</div>
							</div>';
		$fecha_actual = date("d-m-Y");
		$data_tec_giron = $this->Informe->get_tec_gm('giron');
		foreach ($data_tec_giron->result() as $key) {
			echo '<div style="margin-left: 80px;">
								<i class="fas fa-user bg-orange"></i>
								<div class="timeline-item">
									<span class="time">
										<div class="row">
										<table style="width: 100%;">
										<tr width="100">';
			for ($i = 5; $i >= 0; $i--) {
				$mes = (date("m", strtotime($fecha_actual . "- " . $i . " month")) * 1);
				$data_tec_g = $this->Informe->get_nps_tec_gm('giron', $mes, $key->nit_tecnico);
				if (count($data_tec_g->result()) == 0) {
					$color = "default";
					$nps = "-";
				}
				//print_r($data_tec_g->result());
				foreach ($data_tec_g->result() as $key1) {
					if ($key1->enc0a6 == -1 && $key1->enc7a8 == -1 && $key1->enc9a10 == -1) {

						echo '<td width="85" nowrap>
													<div class="col-md-2">
														<div class="icheck-default d-inline">
									                        <input type="radio" name="gr11' . $key1->nit_tecnico . $mes . $i . '" checked="" id="radioSuccess1">
									                        <label for="radioSuccess1">-</label>
									                     </div>
													</div>
													</td>';
					} else {
						$to_enc = $key1->enc0a6 + $key1->enc7a8 + $key1->enc9a10;
						if ($to_enc != 0) {
							$nps = number_format((($key1->enc9a10 - $key1->enc0a6) / $to_enc) * 100, 1, ",", ",");
							$npsr = round((($key1->enc9a10 - $key1->enc0a6) / $to_enc) * 100);
							if ($npsr >= 80 && $npsr <= 100) {
								$color = "success";
							} else {
								$color = "danger";
							}
						}
						$sede = "'giron'";
						echo '<td width="85" nowrap>
													<div class="col-md-2" onclick="load_info_tec(' . $key1->nit_tecnico . ',' . $mes . ',' . $sede . ');">
														<div class="icheck-' . $color . ' d-inline">
									                        <input type="radio" name="gr11' . $key1->nit_tecnico . $mes . $i . '" checked="" id="radioSuccess1">
									                        <label for="radioSuccess1">' . $nps . '%</label>
									                     </div>
													</div>
													</td>';
					}
				}
			}
			echo '
										</tr>
										</table>
										</div>
									</span>
									<h3 class="timeline-header no-border">' . $key->nombres . '</h3>
								</div>
							</div>';
		}
		echo '<!-- timeline item -->
							<div style="margin-left: 40px;">
								<i class="fas fa-car bg-purple"></i>
								<div class="timeline-item">
									<span class="time">
										<div class="row">
										<table>
										<tr>';
		foreach ($data_rosita as $key) {
			if ($key['nps'] >= 80 && $key['nps'] <= 100) {
				$color = "success";
			} elseif ($key['nps'] < 80) {
				$color = "danger";
			}
			$s = "'rosita'";
			echo '<td width="85" nowrap>
												<div class="col-md-2" onclick="load_info_sede(' . $s . ',' . $key['mes'] . ');">
													<div class="icheck-' . $color . ' d-inline">
								                        <input type="radio" name="rs' . $key['mes'] . '" checked="" id="radioSuccess1">
								                        <label for="radioSuccess1">' . $key['nps'] . '%</label>
								                     </div>
												</div>
												</td>
											';
		}

		echo '
										</tr>
										</table>
										</div>
									</span>
									<h3 class="timeline-header no-border">NPS Rosita</h3>
								</div>
							</div>';
		$data_tec_rosita = $this->Informe->get_tec_gm('rosita');
		foreach ($data_tec_rosita->result() as $key) {
			echo '<div style="margin-left: 80px;">
								<i class="fas fa-user bg-orange"></i>
								<div class="timeline-item">
									<span class="time">
										<div class="row">
										<table>
										<tr>';
			for ($i = 5; $i >= 0; $i--) {
				$mes = (date("m", strtotime($fecha_actual . "- " . $i . " month")) * 1);
				$data_tec_g = $this->Informe->get_nps_tec_gm('rosita', $mes, $key->nit_tecnico);
				if (count($data_tec_g->result()) == 0) {
					$color = "default";
					$nps = "-";
				}
				//print_r($data_tec_g->result());
				foreach ($data_tec_g->result() as $key1) {
					if ($key1->enc0a6 == -1 && $key1->enc7a8 == -1 && $key1->enc9a10 == -1) {

						echo '<td width="85" nowrap>
													<div class="col-md-2">
														<div class="icheck-default d-inline">
									                        <input type="radio" name="rr11' . $key1->nit_tecnico . $mes . $i . '" checked="" id="radioSuccess1">
									                        <label for="radioSuccess1">-</label>
									                     </div>
													</div>
													</td>';
					} else {
						$to_enc = $key1->enc0a6 + $key1->enc7a8 + $key1->enc9a10;
						if ($to_enc != 0) {
							$nps = number_format((($key1->enc9a10 - $key1->enc0a6) / $to_enc) * 100, 1, ",", ",");
							$npsr = round((($key1->enc9a10 - $key1->enc0a6) / $to_enc) * 100);
							if ($npsr >= 80 && $npsr <= 100) {
								$color = "success";
							} else {
								$color = "danger";
							}
						}
						$sede = "'rosita'";
						echo '<td width="85" nowrap>
													<div class="col-md-2" onclick="load_info_tec(' . $key1->nit_tecnico . ',' . $mes . ',' . $sede . ');">
														<div class="icheck-' . $color . ' d-inline">
									                        <input type="radio" name="rr11' . $key1->nit_tecnico . $mes . $i . '" checked="" id="radioSuccess1">
									                        <label for="radioSuccess1">' . $nps . '%</label>
									                     </div>
													</div>
													</td>';
					}
				}
			}
			echo '
										</tr>
										</table>
										</div>
									</span>
									<h3 class="timeline-header no-border">' . $key->nombres . '</h3>
								</div>
							</div>';
		}
		echo '<!-- timeline item SEDES-->
							<div style="margin-left: 40px;">
								<i class="fas fa-car bg-purple"></i>
								<div class="timeline-item">
									<span class="time">
										<div class="row">
										<table>
										<tr>';
		foreach ($data_barranca as $key) {
			if ($key['nps'] >= 80 && $key['nps'] <= 100) {
				$color = "success";
			} elseif ($key['nps'] < 80) {
				$color = "danger";
			}
			if ($key['nps'] == 80) {
				$color = "success";
			}
			$s = "'barranca'";
			echo '<td width="85" nowrap>
												<div class="col-md-2" onclick="load_info_sede(' . $s . ',' . $key['mes'] . ');">
													<div class="icheck-' . $color . ' d-inline">
								                        <input type="radio" name="rb' . $key['mes'] . '" checked="" id="radioSuccess1">
								                        <label for="radioSuccess1">' . $key['nps'] . '%</label>
								                     </div>
												</div>
												</td>
											';
		}
		echo '
										</tr>
										</table>
										</div>
									</span>
									<h3 class="timeline-header no-border">NPS Barranca</h3>
								</div>
							</div>';
		$data_tec_barranca = $this->Informe->get_tec_gm('barranca');
		foreach ($data_tec_barranca->result() as $key) {
			echo '<div style="margin-left: 80px;">
								<i class="fas fa-user bg-orange"></i>
								<div class="timeline-item">
									<span class="time">
										<div class="row">
										<table>
										<tr>';
			for ($i = 5; $i >= 0; $i--) {
				$mes = (date("m", strtotime($fecha_actual . "- " . $i . " month")) * 1);
				$data_tec_g = $this->Informe->get_nps_tec_gm('barranca', $mes, $key->nit_tecnico);
				if (count($data_tec_g->result()) == 0) {
					$color = "default";
					$nps = "-";
				}
				//print_r($data_tec_g->result());
				foreach ($data_tec_g->result() as $key1) {
					if ($key1->enc0a6 == -1 && $key1->enc7a8 == -1 && $key1->enc9a10 == -1) {

						echo '<td width="85" nowrap>
													<div class="col-md-2">
														<div class="icheck-default d-inline">
									                        <input type="radio" name="br11' . $key1->nit_tecnico . $mes . $i . '" checked="" id="radioSuccess1">
									                        <label for="radioSuccess1">-</label>
									                     </div>
													</div>
													</td>';
					} else {
						$to_enc = $key1->enc0a6 + $key1->enc7a8 + $key1->enc9a10;
						if ($to_enc != 0) {
							$nps = number_format((($key1->enc9a10 - $key1->enc0a6) / $to_enc) * 100, 1, ",", ",");
							$npsr = round((($key1->enc9a10 - $key1->enc0a6) / $to_enc) * 100);
							if ($npsr >= 80 && $npsr <= 100) {
								$color = "success";
							} else {
								$color = "danger";
							}
						}
						$sede = "'barranca'";
						echo '<td width="85" nowrap>
													<div class="col-md-2" onclick="load_info_tec(' . $key1->nit_tecnico . ',' . $mes . ',' . $sede . ');">
														<div class="icheck-' . $color . ' d-inline">
									                        <input type="radio" name="br11' . $key1->nit_tecnico . $mes . $i . '" checked="" id="radioSuccess1">
									                        <label for="radioSuccess1">' . $nps . '%</label>
									                     </div>
													</div>
													</td>';
					}
				}
			}
			echo '
										</tr>
										</table>
										</div>
									</span>
									<h3 class="timeline-header no-border">' . $key->nombres . '</h3>
								</div>
							</div>';
		}
		echo '<!-- timeline item -->
							<div style="margin-left: 40px;">
								<i class="fas fa-car bg-purple"></i>
								<div class="timeline-item">
									<span class="time">
										<div class="row">
										<table>
										<tr>';
		foreach ($data_bocono as $key) {
			if ($key['nps'] >= 80 && $key['nps'] <= 100) {
				$color = "success";
			} elseif ($key['nps'] < 80) {
				$color = "danger";
			}
			$s = "'bocono'";
			echo '<td width="85" nowrap>
												<div class="col-md-2" onclick="load_info_sede(' . $s . ',' . $key['mes'] . ');">
													<div class="icheck-' . $color . ' d-inline">
								                        <input type="radio" name="rbb' . $key['mes'] . '" checked="" id="radioSuccess1">
								                        <label for="radioSuccess1">' . $key['nps'] . '%</label>
								                     </div>
												</div>
												</td>
											';
		}
		echo '
										</td>
										</table>
										</div>
									</span>
									<h3 class="timeline-header no-border">NPS Bocono</h3>
								</div>
							</div>';
		$data_tec_bocono = $this->Informe->get_tec_gm('bocono');
		foreach ($data_tec_bocono->result() as $key) {
			echo '<div style="margin-left: 80px;">
								<i class="fas fa-user bg-orange"></i>
								<div class="timeline-item">
									<span class="time">
										<div class="row">
										<table>
										<tr>';
			for ($i = 5; $i >= 0; $i--) {
				$mes = (date("m", strtotime($fecha_actual . "- " . $i . " month")) * 1);
				$data_tec_g = $this->Informe->get_nps_tec_gm('bocono', $mes, $key->nit_tecnico);
				if (count($data_tec_g->result()) == 0) {
					$color = "default";
					$nps = "-";
				}
				//print_r($data_tec_g->result());
				foreach ($data_tec_g->result() as $key1) {
					if ($key1->enc0a6 == -1 && $key1->enc7a8 == -1 && $key1->enc9a10 == -1) {

						echo '<td width="85" nowrap>
													<div class="col-md-2">
														<div class="icheck-default d-inline">
									                        <input type="radio" name="bbr11' . $key1->nit_tecnico . $mes . $i . '" checked="" id="radioSuccess1">
									                        <label for="radioSuccess1">-</label>
									                     </div>
													</div>
													</td>';
					} else {
						$to_enc = $key1->enc0a6 + $key1->enc7a8 + $key1->enc9a10;
						if ($to_enc != 0) {
							$nps = number_format((($key1->enc9a10 - $key1->enc0a6) / $to_enc) * 100, 1, ",", ",");
							$npsr = round((($key1->enc9a10 - $key1->enc0a6) / $to_enc) * 100);
							if ($npsr >= 80 && $npsr <= 100) {
								$color = "success";
							} else {
								$color = "danger";
							}
						}
						$sede = "'bocono'";
						echo '<td width="85" nowrap>
													<div class="col-md-2" onclick="load_info_tec(' . $key1->nit_tecnico . ',' . $mes . ',' . $sede . ');">
														<div class="icheck-' . $color . ' d-inline">
									                        <input type="radio" name="bbr11' . $key1->nit_tecnico . $mes . $i . '" checked="" id="radioSuccess1">
									                        <label for="radioSuccess1">' . $nps . '%</label>
									                     </div>
													</div>
													</td>';
					}
				}
			}
			echo '
										</tr>
										</table>
										</div>
									</span>
									<h3 class="timeline-header no-border">' . $key->nombres . '</h3>
								</div>
							</div>';
		}
		echo '<!-- timeline item -->
							<div>
								<i class="fas fa-truck bg-pink"></i>
								<div class="timeline-item">
									<span class="time">
										<div class="row">
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="r11" checked="" id="radioSuccess1">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="r22" checked="" id="radioSuccess2">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="r33" checked="" id="radioSuccess3">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="r44" checked="" id="radioSuccess4">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="r55" checked="" id="radioSuccess5">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="r66" checked="" id="radioSuccess6">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
										</div>
									</span>
									<h3 class="timeline-header no-border">NPS Livianos</h3>
								</div>
							</div>
							<!-- timeline item -->
							<div style="margin-left: 40px;">
								<i class="fas fa-truck bg-green"></i>
								<div class="timeline-item">
									<span class="time">
										<div class="row">
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="gr11" checked="" id="radioSuccess1">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="gr22" checked="" id="radioSuccess2">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="gr33" checked="" id="radioSuccess3">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="gr44" checked="" id="radioSuccess4">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="gr55" checked="" id="radioSuccess5">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="gr66" checked="" id="radioSuccess6">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
										</div>
									</span>
									<h3 class="timeline-header no-border">NPS Giron</h3>
								</div>
							</div>
							<!-- timeline item -->
							<div style="margin-left: 40px;">
								<i class="fas fa-truck bg-green"></i>
								<div class="timeline-item">
									<span class="time">
										<div class="row">
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="rr11" checked="" id="radioSuccess1">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="rr22" checked="" id="radioSuccess2">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="rr33" checked="" id="radioSuccess3">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="rr44" checked="" id="radioSuccess4">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="rr55" checked="" id="radioSuccess5">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="rr66" checked="" id="radioSuccess6">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
										</div>
									</span>
									<h3 class="timeline-header no-border">NPS Rosita</h3>
								</div>
							</div>
							<!-- timeline item SEDES-->
							<div style="margin-left: 40px;">
								<i class="fas fa-truck bg-green"></i>
								<div class="timeline-item">
									<span class="time">
										<div class="row">
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="br11" checked="" id="radioSuccess1">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="br22" checked="" id="radioSuccess2">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="br33" checked="" id="radioSuccess3">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="br44" checked="" id="radioSuccess4">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="br55" checked="" id="radioSuccess5">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="br66" checked="" id="radioSuccess6">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
										</div>
									</span>
									<h3 class="timeline-header no-border">NPS Barranca</h3>
								</div>
							</div>
							<!-- timeline item -->
							<div style="margin-left: 40px;">
								<i class="fas fa-truck bg-green"></i>
								<div class="timeline-item">
									<span class="time">
										<div class="row">
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="bbr11" checked="" id="radioSuccess1">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="bbr22" checked="" id="radioSuccess2">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="bbr33" checked="" id="radioSuccess3">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="bbr44" checked="" id="radioSuccess4">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="bbr55" checked="" id="radioSuccess5">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
											<div class="col-md-2">
												<div class="icheck-success d-inline">
							                        <input type="radio" name="bbr66" checked="" id="radioSuccess6">
							                        <label for="radioSuccess1">85%</label>
							                     </div>
											</div>
										</div>
									</span>
									<h3 class="timeline-header no-border">NPS Bocono</h3>
								</div>
							</div>
							<!-- timeline item -->
						</div>
		';
	}

	public function load_tabla_indicadores()
	{
		//traemos los modelos
		$this->load->model('Informe');
		$mes = Date('m') * 1;
		$sedes = "general";
		$data_enc_sedes = $this->Informe->get_nps_gm_gra_tabla($sedes, $mes);
		$enc9a10 = 0;
		$enc7a8 = 0;
		$enc0a6 = 0;
		$calificacion = 0;
		foreach ($data_enc_sedes->result() as $key) {
			$enc0a6 += $key->Enc_0_a_6;
			$enc7a8 += $key->Enc_7_a_8;
			$enc9a10 += $key->Enc_9_a_10;
			$calificacion = $key->Calificacion;
		}
		$meta = 0.8;
		$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
		$to = (($to_enc * $meta) - ($enc9a10 - $enc0a6)) / (1 - $meta);
		//echo "((".$to_enc ." * ".$meta .") - (".$enc9a10 ." - ". $enc0a6 .")) / (1"." - ".$meta.")"; die;
		if ($to > 0) {
			$to = $to;
		} else {
			$to = 0;
		}

		echo
		'
		<tr>
			<td style="background-color: red; color: #fff;">' . $enc0a6 . '</td>
			<td style="background-color: yellow; color: black;">' . $enc7a8 . '</td>
			<td style="background-color: green; color: black;">' . $enc9a10 . '</td>
			<td style="background-color: cyan; color: black;">' . $to . '</td>
			<td style="background-color: #C4D9FF;">' . number_format($calificacion, 1, ",", ",") . '%</td>
			<td style="background-color: #C4D9FF;">80%</td>
		</tr>
		';
		//giron
		$sedes = "giron";
		$data_enc_sedes = $this->Informe->get_nps_gm_gra_tabla($sedes, $mes);
		$enc9a10 = 0;
		$enc7a8 = 0;
		$enc0a6 = 0;
		$calificacion = 0;
		foreach ($data_enc_sedes->result() as $key) {
			$enc0a6 += $key->Enc_0_a_6;
			$enc7a8 += $key->Enc_7_a_8;
			$enc9a10 += $key->Enc_9_a_10;
			$calificacion = $key->Calificacion;
		}
		$meta = 0.8;
		$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
		$to = (($to_enc * $meta) - ($enc9a10 - $enc0a6)) / (1 - $meta);

		//echo $to;die;
		if ($to > 0) {
			$to = $to;
		} else {
			$to = 0;
		}
		//echo $to_enc ." * ".$meta ." - ".$enc9a10 ." + ". $enc0a6 ." / 1"." - ".$meta; die;
		echo
		'
		<tr>
			<td style="background-color: red; color: #fff;">' . $enc0a6 . '</td>
			<td style="background-color: yellow; color: black;">' . $enc7a8 . '</td>
			<td style="background-color: green; color: black;">' . $enc9a10 . '</td>
			<td style="background-color: cyan; color: black;">' . $to . '</td>
			<td style="background-color: #E1C4FF;">' . number_format($calificacion, 1, ",", ",") . '%</td>
			<td style="background-color: #E1C4FF;">80%</td>
		</tr>
		';

		$data_tec_g = $this->Informe->get_nps_tec_gm_all('giron', $mes);
		foreach ($data_tec_g->result() as $key) {
			$to_enc = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
			$meta = 0.8;
			$to = (($to_enc * $meta) - ($enc9a10 - $enc0a6)) / (1 - $meta);
			//echo $to_enc ." * ".$meta ." - ".$enc9a10 ." + ". $enc0a6 ." / 1"." - ".$meta; die;
			if ($to > 0) {
				$to = $to;
			} else {
				$to = 0;
			}
			if ($to_enc == 0) {
				$nps = 0;
			} else {
				$nps = round((($key->enc9a10 - $key->enc0a6) / $to_enc) * 100);
			}

			echo
			'
		<tr>
			<td style="background-color: red; color: #fff;">' . $key->enc0a6 . '</td>
			<td style="background-color: yellow; color: black;">' . $key->enc7a8 . '</td>
			<td style="background-color: green; color: black;">' . $key->enc9a10 . '</td>
			<td style="background-color: cyan; color: black;">' . $to . '</td>
			<td style="background-color: #FFECC4;">' . $nps . '%</td>
			<td style="background-color: #FFECC4;">80%</td>
		</tr>
		';
		}
		//rosita
		$sede = "rosita";
		$data_enc_sedes = $this->Informe->get_nps_gm_gra_tabla("rosita", $mes);
		$enc9a10 = 0;
		$enc7a8 = 0;
		$enc0a6 = 0;
		$calificacion = 0;
		foreach ($data_enc_sedes->result() as $key) {
			$enc0a6 += $key->Enc_0_a_6;
			$enc7a8 += $key->Enc_7_a_8;
			$enc9a10 += $key->Enc_9_a_10;
			$calificacion = $key->Calificacion;
		}
		$meta = 0.8;
		$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
		$to = (($to_enc * $meta) - ($enc9a10 - $enc0a6)) / (1 - $meta);
		//echo $to_enc ." * ".$meta ." - ".$enc9a10 ." + ". $enc0a6 ." / 1"." - ".$meta; die;
		if ($to > 0) {
			$to = $to;
		} else {
			$to = 0;
		}
		//echo "((".$to_enc ." * ".$meta .") - (".$enc9a10 ." - ". $enc0a6 .")) / (1"." - ".$meta.")"; die;
		echo
		'
		<tr>
			<td style="background-color: red; color: #fff;">' . $enc0a6 . '</td>
			<td style="background-color: yellow; color: black;">' . $enc7a8 . '</td>
			<td style="background-color: green; color: black;">' . $enc9a10 . '</td>
			<td style="background-color: cyan; color: black;">' . $to . '</td>
			<td style="background-color: #E1C4FF;">' . number_format($calificacion, 1, ",", ",") . '%</td>
			<td style="background-color: #E1C4FF;">80%</td>
		</tr>
		';

		$data_tec_g = $this->Informe->get_nps_tec_gm_all('rosita', $mes);
		foreach ($data_tec_g->result() as $key) {
			$to_enc = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
			$meta = 0.8;

			$to = (($to_enc * $meta) - ($enc9a10 - $enc0a6)) / (1 - $meta);

			if ($to > 0) {
				$to = $to;
			} else {
				$to = 0;
			}
			if ($to_enc == 0) {
				$nps = 0;
			} else {
				$nps = round((($key->enc9a10 - $key->enc0a6) / $to_enc) * 100);
			}
			echo
			'
		<tr>
			<td style="background-color: red; color: #fff;">' . $key->enc0a6 . '</td>
			<td style="background-color: yellow; color: black;">' . $key->enc7a8 . '</td>
			<td style="background-color: green; color: black;">' . $key->enc9a10 . '</td>
			<td style="background-color: cyan; color: black;">' . $to . '</td>
			<td style="background-color: #FFECC4;">' . $nps . '%</td>
			<td style="background-color: #FFECC4;">80%</td>
		</tr>
		';
		}
		//barranca

		$data_enc_sedes = $this->Informe->get_nps_gm_gra_tabla("barranca", $mes);
		$enc9a10 = 0;
		$enc7a8 = 0;
		$enc0a6 = 0;
		$calificacion = 0;
		foreach ($data_enc_sedes->result() as $key) {
			$enc0a6 += $key->Enc_0_a_6;
			$enc7a8 += $key->Enc_7_a_8;
			$enc9a10 += $key->Enc_9_a_10;
			$calificacion = $key->Calificacion;
		}
		$meta = 0.8;
		$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
		$to = (($to_enc * $meta) - ($enc9a10 - $enc0a6)) / (1 - $meta);
		//echo $to_enc ." * ".$meta ." - ".$enc9a10 ." + ". $enc0a6 ." / 1"." - ".$meta; die;
		if ($to > 0) {
			$to = $to;
		} else {
			$to = 0;
		}
		echo
		'
		<tr>
			<td style="background-color: red; color: #fff;">' . $enc0a6 . '</td>
			<td style="background-color: yellow; color: black;">' . $enc7a8 . '</td>
			<td style="background-color: green; color: black;">' . $enc9a10 . '</td>
			<td style="background-color: cyan; color: black;">' . $to . '</td>
			<td style="background-color: #E1C4FF;">' . number_format($calificacion, 1, ",", ",") . '%</td>
			<td style="background-color: #E1C4FF;">80%</td>
		</tr>
		';

		$data_tec_g = $this->Informe->get_nps_tec_gm_all('barranca', $mes);
		foreach ($data_tec_g->result() as $key) {
			$to_enc = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
			$meta = 0.8;
			$to = (($to_enc * $meta) - ($enc9a10 - $enc0a6)) / (1 - $meta);
			//echo $to_enc ." * ".$meta ." - ".$enc9a10 ." + ". $enc0a6 ." / 1"." - ".$meta; die;
			if ($to > 0) {
				$to = $to;
			} else {
				$to = 0;
			}
			if ($to_enc == 0) {
				$nps = 0;
			} else {
				$nps = round((($key->enc9a10 - $key->enc0a6) / $to_enc) * 100);
			}
			echo
			'
		<tr>
			<td style="background-color: red; color: #fff;">' . $key->enc0a6 . '</td>
			<td style="background-color: yellow; color: black;">' . $key->enc7a8 . '</td>
			<td style="background-color: green; color: black;">' . $key->enc9a10 . '</td>
			<td style="background-color: cyan; color: black;">' . $to . '</td>
			<td style="background-color: #FFECC4;">' . $nps . '%</td>
			<td style="background-color: #FFECC4;">80%</td>
		</tr>
		';
		}
		//bocono
		$data_enc_sedes = $this->Informe->get_nps_gm_gra_tabla("bocono", $mes);
		$enc9a10 = 0;
		$enc7a8 = 0;
		$enc0a6 = 0;
		$calificacion = 0;
		foreach ($data_enc_sedes->result() as $key) {
			$enc0a6 += $key->Enc_0_a_6;
			$enc7a8 += $key->Enc_7_a_8;
			$enc9a10 += $key->Enc_9_a_10;
			$calificacion = $key->Calificacion;
		}
		$meta = 0.8;
		$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
		$to = (($to_enc * $meta) - ($enc9a10 - $enc0a6)) / (1 - $meta);
		//echo "((".$to_enc ." * ".$meta .") - (".$enc9a10 ." - ". $enc0a6 .")) / (1"." - ".$meta.")"; die;
		if ($to > 0) {
			$to = $to;
		} else {
			$to = 0;
		}

		echo
		'
		<tr>
			<td style="background-color: red; color: #fff;">' . $enc0a6 . '</td>
			<td style="background-color: yellow; color: black;">' . $enc7a8 . '</td>
			<td style="background-color: green; color: black;">' . $enc9a10 . '</td>
			<td style="background-color: cyan; color: black;">' . $to . '</td>
			<td style="background-color: #E1C4FF;">' . number_format($calificacion, 1, ",", ",") . '%</td>
			<td style="background-color: #E1C4FF;">80%</td>
		</tr>
		';

		$data_tec_g = $this->Informe->get_nps_tec_gm_all('bocono', $mes);
		foreach ($data_tec_g->result() as $key) {
			$to_enc = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
			$meta = 0.8;
			$to = (($to_enc * $meta) - ($enc9a10 - $enc0a6)) / (1 - $meta);
			//echo $to_enc ." * ".$meta ." - ".$enc9a10 ." + ". $enc0a6 ." / 1"." - ".$meta; die;
			if ($to > 0) {
				$to = $to;
			} else {
				$to = 0;
			}
			if ($to_enc == 0) {
				$nps = 0;
			} else {
				$nps = round((($key->enc9a10 - $key->enc0a6) / $to_enc) * 100);
			}
			echo
			'
		<tr>
			<td style="background-color: red; color: #fff;">' . $key->enc0a6 . '</td>
			<td style="background-color: yellow; color: black;">' . $key->enc7a8 . '</td>
			<td style="background-color: green; color: black;">' . $key->enc9a10 . '</td>
			<td style="background-color: cyan; color: black;">' . $to . '</td>
			<td style="background-color: #FFECC4;">' . $nps . '%</td>
			<td style="background-color: #FFECC4;">80%</td>
		</tr>
		';
		}
	}

	public function load_info_sedes_nps()
	{
		//traemos los modelos
		$this->load->model('Informe');
		//traemos los parametros por url
		$sede = $this->input->GET('sede');
		$mes = $this->input->GET('mes');
		$data_tec = $this->Informe->get_nps_gm_gra_tabla($sede, $mes);
		$enc06 = 0;
		$enc78 = 0;
		$enc910 = 0;
		$nombres = "";
		foreach ($data_tec->result() as $key) {
			$enc06 += $key->Enc_0_a_6;
			$enc78 += $key->Enc_7_a_8;
			$enc910 += $key->Enc_9_a_10;
		}
		//se obtiene el nombre del mes
		if (strlen($mes) == 1) {
			$mes = "0" . $mes;
		} else {
			$mes = $mes;
		}
		$mes = "2022" . $mes . "01";
		$data_mes = $this->Informe->get_name_mes($mes);
		echo '
		<div class="row">
			<div class="col-md-12">
				<table style="width: 100%">
					<tr aling="left">
						<td>Sede: </td>
						<td><strong>' . $sede . '</strong></td>
						<td>Mes: </td>
						<td><strong>' . $data_mes->nom_mes . '</strong></td>
					</tr>
				</table>
			</div>
		</div>
			<div class="row">
					<div class="col-md-4">
						<div class="small-box bg-danger">
							<div class="inner">
								<h3>' . $enc06 . '</h3>

								<p>Numero de encuestas de 0 a 6</p>
							</div>
							<div class="icon">
								<i class="ion ion-stats-bars"></i>
							</div>
							
						</div>
					</div>
					<div class="col-md-4">
						<div class="small-box bg-warning">
							<div class="inner">
								<h3>' . $enc78 . '</h3>

								<p>Numero de encuestas de 7 a 8</p>
							</div>
							<div class="icon">
								<i class="ion ion-stats-bars"></i>
							</div>
							
						</div>
					</div>
					<div class="col-md-4">
						<div class="small-box bg-success">
							<div class="inner">
								<h3>' . $enc910 . '</h3>

								<p>Numero de encuestas de 9 a 10</p>
							</div>
							<div class="icon">
								<i class="ion ion-stats-bars"></i>
							</div>
							
						</div>
					</div>
				</div>

		';
	}

	public function load_info_tec_nps()
	{
		//traemos los modelos
		$this->load->model('Informe');
		//traemos los parametros por url
		$tec = $this->input->GET('nit');
		$mes = $this->input->GET('mes');
		$sede = $this->input->GET('sede');
		$sede = "'" . $sede . "'";
		//traemos la info del tecnico
		//$sedes = "'giron','rosita','barranca','bocono'";
		$data_tec = $this->Informe->get_nps_tec_gm_by_nit($sede, $mes, $tec);
		$enc06 = 0;
		$enc78 = 0;
		$enc910 = 0;
		$nombres = "";
		foreach ($data_tec->result() as $key) {
			if ($key->enc0a6 >= 0 || $key->enc7a8 >= 0 || $key->enc9a10 >= 0) {
				$enc06 += $key->enc0a6;
				$enc78 += $key->enc7a8;
				$enc910 += $key->enc9a10;
				$nombres = $key->tecnico;
			} else {
				$enc06 += 0;
				$enc78 += 0;
				$enc910 += 0;
				$nombres = 0;
			}
		}
		//se obtiene el nombre del mes
		if (strlen($mes) == 1) {
			$mes = "0" . $mes;
		} else {
			$mes = $mes;
		}
		$mes = "2022" . $mes . "01";
		$data_mes = $this->Informe->get_name_mes($mes);
		echo '
		<div class="row">
			<div class="col-md-12">
				<table style="width: 100%">
					<tr aling="left">
						<td>Tecnico: </td>
						<td><strong>' . $nombres . '</strong></td>
						<td>Mes: </td>
						<td><strong>' . $data_mes->nom_mes . '</strong></td>
					</tr>
				</table>
			</div>
		</div>
			<div class="row">
					<div class="col-md-4">
						<div class="small-box bg-danger">
							<div class="inner">
								<h3>' . $enc06 . '</h3>

								<p>Numero de encuestas de 0 a 6</p>
							</div>
							<div class="icon">
								<i class="ion ion-stats-bars"></i>
							</div>
							
						</div>
					</div>
					<div class="col-md-4">
						<div class="small-box bg-warning">
							<div class="inner">
								<h3>' . $enc78 . '</h3>

								<p>Numero de encuestas de 7 a 8</p>
							</div>
							<div class="icon">
								<i class="ion ion-stats-bars"></i>
							</div>
							
						</div>
					</div>
					<div class="col-md-4">
						<div class="small-box bg-success">
							<div class="inner">
								<h3>' . $enc910 . '</h3>

								<p>Numero de encuestas de 9 a 10</p>
							</div>
							<div class="icon">
								<i class="ion ion-stats-bars"></i>
							</div>
							
						</div>
					</div>
				</div>

		';
	}

	public function load_info_all_nps_gm()
	{
		//traemos los modelos
		$this->load->model('Informe');
		//traemos los parametros por url
		$sede = "general";
		$mes = $this->input->GET('mes');
		$data_tec = $this->Informe->get_nps_gm_gra_tabla($sede, $mes);
		$enc06 = 0;
		$enc78 = 0;
		$enc910 = 0;
		$nombres = "";
		foreach ($data_tec->result() as $key) {
			$enc06 += $key->Enc_0_a_6;
			$enc78 += $key->Enc_7_a_8;
			$enc910 += $key->Enc_9_a_10;
		}
		//se obtiene el nombre del mes
		if (strlen($mes) == 1) {
			$mes = "0" . $mes;
		} else {
			$mes = $mes;
		}
		$mes = "2022" . $mes . "01";
		$data_mes = $this->Informe->get_name_mes($mes);
		echo '
		<div class="row">
			<div class="col-md-12">
				<table style="width: 100%">
					<tr aling="left">
						<td>Sede: </td>
						<td><strong>Todas</strong></td>
						<td>Mes: </td>
						<td><strong>' . $data_mes->nom_mes . '</strong></td>
					</tr>
				</table>
			</div>
		</div>
			<div class="row">
					<div class="col-md-4">
						<div class="small-box bg-danger">
							<div class="inner">
								<h3>' . $enc06 . '</h3>

								<p>Numero de encuestas de 0 a 6</p>
							</div>
							<div class="icon">
								<i class="ion ion-stats-bars"></i>
							</div>
							
						</div>
					</div>
					<div class="col-md-4">
						<div class="small-box bg-warning">
							<div class="inner">
								<h3>' . $enc78 . '</h3>

								<p>Numero de encuestas de 7 a 8</p>
							</div>
							<div class="icon">
								<i class="ion ion-stats-bars"></i>
							</div>
							
						</div>
					</div>
					<div class="col-md-4">
						<div class="small-box bg-success">
							<div class="inner">
								<h3>' . $enc910 . '</h3>

								<p>Numero de encuestas de 9 a 10</p>
							</div>
							<div class="icon">
								<i class="ion ion-stats-bars"></i>
							</div>
							
						</div>
					</div>
				</div>

		';
	}

	/* PostCarga de la Tabla */
	public function PostCargaTablaInfVentas72()
	{

		$this->load->model('Informe');


		$dataInforme = $this->Informe->getDataInformeGeneral();
		echo '
		<thead align="center" class="thead-light">
		<tr>
			<th class="titulo colun1 fijar" scope="col"></th>
			<th colspan="7" class="titulo colun1 fijar" scope="col">Total</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">12. - 0</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">24 - 12</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">36 - 24</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">48 - 36</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">60 - 48</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">72 - 60</th>

		</tr>
		<tr>
			<th class="titulo2 fijar colun1" scope="col">Familia vh</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
		</tr>
	</thead>
	<tbody align="center">';

		$ban = 0;
		foreach ($dataInforme->result() as $key) {
			/* retVal se calcula el porcentaje de retención propia validando que las ventas no sean 0 */
			$retVal1 = ($key->p_0_12 != 0) ? round(($key->e_0_12 / $key->p_0_12) * 100, 0) : 0;
			$retVal2 = ($key->p_13_24 != 0) ? round(($key->e_13_24 / $key->p_13_24) * 100, 0) : 0;
			$retVal3 = ($key->p_25_36 != 0) ? round(($key->e_25_36 / $key->p_25_36) * 100, 0) : 0;
			$retVal4 = ($key->p_37_48 != 0) ? round(($key->e_37_48 / $key->p_37_48) * 100, 0) : 0;
			$retVal5 = ($key->p_49_60 != 0) ? round(($key->e_49_60 / $key->p_49_60) * 100, 0) : 0;
			$retVal6 = ($key->p_61_72 != 0) ? round(($key->e_61_72 / $key->p_61_72) * 100, 0) : 0;
			/* Suma total de Entradas y ventas */
			$tP_72_0 = $key->p_0_12 + $key->p_13_24 + $key->p_25_36 + $key->p_37_48 + $key->p_49_60 + $key->p_61_72;
			$tE_72_0 = $key->e_0_12 + $key->e_13_24 + $key->e_25_36 + $key->e_37_48 + $key->e_49_60 + $key->e_61_72;
			$total_72_0 = ($tP_72_0 != 0) ? round(($tE_72_0 / $tP_72_0) * 100, 0) : 0;
			/* Suma de tickets */
			$sSumaT = ($key->ft_0_12 + $key->ft_13_24 + $key->ft_25_36 + $key->ft_37_48 + $key->ft_49_60 + $key->ft_61_72);
			/* Total factura dividida por el total de entradas */
			$tFactura = ($tE_72_0 == 0) ? 0 : ($sSumaT / $tE_72_0);

			$disabled1 = ($key->p_0_12 == 0 || $key->e_0_12 == $key->p_0_12) ? 'disabled' : '';
			$disabled2 = ($key->p_13_24 == 0 || $key->e_13_24 == $key->p_13_24) ? 'disabled' : '';
			$disabled3 = ($key->p_25_36 == 0 || $key->e_25_36 == $key->p_25_36) ? 'disabled' : '';
			$disabled4 = ($key->p_37_48 == 0 || $key->e_37_48 == $key->p_37_48) ? 'disabled' : '';
			$disabled5 = ($key->p_49_60 == 0 || $key->e_49_60 == $key->p_49_60) ? 'disabled' : '';
			$disabled6 = ($key->p_61_72 == 0 || $key->e_61_72 == $key->p_61_72) ? 'disabled' : '';


			echo '
			<tr>
				<!-- Total -->
				<td class="color-1 fijar colun1">' . $key->familia_vh . " " . $key->tipo_vh . '</td>
				<td class="color-1 text-center tc1 totalVE_' . $ban . '">' . $tE_72_0 . '</td>
				<td class="color-1 text-center tc2 totalVE_' . $ban . '">' . $tP_72_0 . '</td>
				<td class="color-1 text-center">' . $total_72_0 . '%</td>
				<td class="color-1 text-center tc3" id="entradaAtotalVE_' . $ban . '">0</td>
				<td class="color-1 text-center" id="pEstimadoTtotalVE_' . $ban . '">0</td>
				<p class="color-1 text-center tc4 d-none" id="facturaTtotalVE_' . $ban . '">' . $tFactura . '</p>
				<td class="color-1 text-center">' . number_format($tFactura, 0, ",", ".") . '</td>
				<td class="color-1 text-center" id="fAdicionaltotalVE_' . $ban . '">0</td>
				<p class="color-1 text-center d-none tc5" id="fAdicional-totalVE_' . $ban . '">0</p>
				<!-- 12 - 0  -->

				<td class="color-2 text-center tc6 totalVE_' . $ban . '12">' . $key->e_0_12 . '</td>
				<td class="color-2 text-center tc7 totalVE_' . $ban . '12">' . $key->p_0_12 . '</td>
				<td class="color-2 text-center">' . $retVal1 . '%</td>				
				<td class="color-2 text-center" id="entradasAdicionalesT"><input value="0" ' . $disabled1 . ' class="tc8 numberT limpiar-totalVE_' . $ban . '12" type="number" min="1" id="entradaAtotalVE_' . $ban . '12" onchange="entradaAdicional(\'totalVE_' . $ban . '12\',' . $ban . ');"></td>
				<td class="color-2 text-center" id="pEstimadoTtotalVE_' . $ban . '12">0</td>
				<p class="color-2 text-center tc9 d-none" id="facturaTtotalVE_' . $ban . '12">' . $key->f_0_12 . '</p>
				<td class="color-2 text-center">' . number_format($key->f_0_12, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc10 d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '12">0</p>
				<td class="color-2 text-center " id="fAdicionaltotalVE_' . $ban . '12">0</td>

				<!-- 24 - 12 -->

				<td class="color-3 text-center tc11 totalVE_' . $ban . '24">' . $key->e_13_24 . '</td>
				<td class="color-3 text-center tc12 totalVE_' . $ban . '24">' . $key->p_13_24 . '</td>
				<td class="color-3 text-center">' . $retVal2 . '%</td>
				
				<td class="color-3 text-center" id="entradasAdicionalesT"><input value="0" ' . $disabled2 . ' class="tc13 numberT limpiar-totalVE_' . $ban . '24" type="number" min="1" id="entradaAtotalVE_' . $ban . '24" onchange="entradaAdicional(\'totalVE_' . $ban . '24\',' . $ban . ');"></td>
				<td class="color-3 text-center" id="pEstimadoTtotalVE_' . $ban . '24">0</td>
				<p class="color-3 text-center tc14 d-none" id="facturaTtotalVE_' . $ban . '24">' . $key->f_13_24 . '</p>
				<td class="color-3 text-center">' . number_format($key->f_13_24, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc15 d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '24">0</p>
				<td class="color-3 text-center " id="fAdicionaltotalVE_' . $ban . '24">0</td>

				<!-- 36 - 24 -->

				<td class="color-4 text-center tc16 totalVE_' . $ban . '36">' . $key->e_25_36 . '</td>
				<td class="color-4 text-center tc17 totalVE_' . $ban . '36">' . $key->p_25_36 . '</td>
				<td class="color-4 text-center">' . $retVal3 . '%</td>
				
				<td class="color-4 text-center " id="entradasAdicionalesT"><input value="0" ' . $disabled3 . ' class="tc18 numberT limpiar-totalVE_' . $ban . '36" type="number" min="1" id="entradaAtotalVE_' . $ban . '36" onchange="entradaAdicional(\'totalVE_' . $ban . '36\',' . $ban . ');"></td>
				<td class="color-4 text-center" id="pEstimadoTtotalVE_' . $ban . '36">0</td>
				<p class="color-4 text-center tc19 d-none" id="facturaTtotalVE_' . $ban . '36">' . $key->f_25_36 . '</p>
				<td class="color-4 text-center">' . number_format($key->f_25_36, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc20  d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '36">0</p>
				<td class="color-4 text-center " id="fAdicionaltotalVE_' . $ban . '36">0</td>

				<!-- 48 - 36 -->
				<td class="color-5 text-center tc21 totalVE_' . $ban . '48">' . $key->e_37_48 . '</td>
				<td class="color-5 text-center tc22 totalVE_' . $ban . '48">' . $key->p_37_48 . '</td>
				<td class="color-5 text-center">' . $retVal4 . '%</td>
				
				<td class="color-5 text-center " id="entradasAdicionalesT"><input value="0" ' . $disabled4 . ' class="tc23 numberT limpiar-totalVE_' . $ban . '48" type="number" min="1" id="entradaAtotalVE_' . $ban . '48" onchange="entradaAdicional(\'totalVE_' . $ban . '48\',' . $ban . ');"></td>
				<td class="color-5 text-center" id="pEstimadoTtotalVE_' . $ban . '48">0</td>
				<p class="color-5 text-center tc24 d-none" id="facturaTtotalVE_' . $ban . '48">' . $key->f_37_48 . '</p>
				<td class="color-5 text-center">' . number_format($key->f_37_48, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc25 d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '48">0</p>
				<td class="color-5 text-center " id="fAdicionaltotalVE_' . $ban . '48">0</td>

				<!-- 60 - 48  -->
				<td class="color-6 text-center tc26 totalVE_' . $ban . '60">' . $key->e_49_60 . '</td>
				<td class="color-6 text-center tc27 totalVE_' . $ban . '60">' . $key->p_49_60 . '</td>
				<td class="color-6 text-center">' . $retVal5 . '%</td>
				
				<td class="color-6 text-center " id="entradasAdicionalesT"><input value="0" ' . $disabled5 . ' class="tc28 numberT limpiar-totalVE_' . $ban . '60" type="number" min="1" id="entradaAtotalVE_' . $ban . '60" onchange="entradaAdicional(\'totalVE_' . $ban . '60\',' . $ban . ');"></td>
				<td class="color-6 text-center" id="pEstimadoTtotalVE_' . $ban . '60">0</td>
				<p class="color-6 text-center tc29 d-none" id="facturaTtotalVE_' . $ban . '60">' . $key->f_49_60 . '</p>
				<td class="color-6 text-center">' . number_format($key->f_49_60, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc30 d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '60">0</p>
				<td class="color-6 text-center " id="fAdicionaltotalVE_' . $ban . '60">0</td>

				<!-- 72 - 60 -->
				<td class="color-7 text-center tc31 totalVE_' . $ban . '72">' . $key->e_61_72 . '</td>
				<td class="color-7 text-center tc32 totalVE_' . $ban . '72">' . $key->p_61_72 . '</td>
				<td class="color-7 text-center">' . $retVal6 . '%</td>
				
				<td class="color-7 text-center " id="entradasAdicionalesT"><input value="0" ' . $disabled6 . ' class="tc33 numberT limpiar-totalVE_' . $ban . '72" type="number" min="1" id="entradaAtotalVE_' . $ban . '72" onchange="entradaAdicional(\'totalVE_' . $ban . '72\',' . $ban . ');"></td>
				<td class="color-7 text-center" id="pEstimadoTtotalVE_' . $ban . '72">0</td>
				<p class="color-7 text-center tc34 d-none" id="facturaTtotalVE_' . $ban . '72">' . $key->f_61_72 . '</p>
				<td class="color-7 text-center">' . number_format($key->f_61_72, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc35 d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '72">0</p>
				<td class="color-7 text-center " id="fAdicionaltotalVE_' . $ban . '72">0</td>
			</tr>';


			$ban++;
		}
		echo '
	</tbody>
	<tfoot>
		<tr>
			<!-- Total -->
			<th class="text-center fijar2 colun1">Total</th>
			<th class="text-center fijar2 colun1" id="th1">EUU</th>
			<th class="text-center fijar2 colun1" id="th2">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th3">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th4">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th5">FACTURACIÓN</th>

			<!-- 12 - 0 -->

			<th class="text-center fijar2 colun1" id="th6">EUU</th>
			<th class="text-center fijar2 colun1" id="th7">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th8">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th9">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th10">FACTURACIÓN</th>
			<!-- 24 - 12 -->
			<th class="text-center fijar2 colun1" id="th11">EUU</th>
			<th class="text-center fijar2 colun1" id="th12">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th13">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th14">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th15">FACTURACIÓN</th>
			<!-- 36 - 24 -->
			<th class="text-center fijar2 colun1" id="th16">EUU</th>
			<th class="text-center fijar2 colun1" id="th17">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th18">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th19">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th20">FACTURACIÓN</th>
			<!-- 48 - 36 -->
			<th class="text-center fijar2 colun1" id="th21">EUU</th>
			<th class="text-center fijar2 colun1" id="th22">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th23">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th24">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th25">FACTURACIÓN</th>
			<!-- 60 - 48 -->
			<th class="text-center fijar2 colun1" id="th26">EUU</th>
			<th class="text-center fijar2 colun1" id="th27">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th28">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th29">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th30">FACTURACIÓN</th>
			<!-- 72 - 60 -->

			<th class="text-center fijar2 colun1" id="th31">EUU</th>
			<th class="text-center fijar2 colun1" id="th32">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th33">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th34">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th35">FACTURACIÓN</th>

		</tr>
	</tfoot>
		';
	}

	/* Tabla general de Informe retención de 72 - 0 */
	public function tablaFiltros()
	{
		$this->load->model('Informe');
		$familia = $this->input->GET('selected');
		/* Lo siguiente se realizo para eliminar los caracteres especiales que venian en el string XD */
		$familia2 = str_replace('"', '\'', $familia);
		$familia3 = str_replace('[', '', $familia2);
		$familia4 = str_replace(']', '', $familia3);

		$dataInforme = $this->Informe->filtroTabla($familia4);

		echo '
		<thead align="center" class="thead-light">
		<tr>
			<th class="titulo colun1 fijar" scope="col"></th>
			<th colspan="7" class="titulo colun1 fijar" scope="col">Total</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">12. - 0</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">24 - 12</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">36 - 24</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">48 - 36</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">60 - 48</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">72 - 60</th>

		</tr>
		<tr>
			<th class="titulo2 fijar colun1" scope="col">Familia vh</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
		</tr>
	</thead>
	<tbody align="center">';

		$ban = 0;
		foreach ($dataInforme->result() as $key) {
			/* retVal se calcula el porcentaje de retención propia validando que las ventas no sean 0 */
			$retVal1 = ($key->p_0_12 != 0) ? round(($key->e_0_12 / $key->p_0_12) * 100, 0) : 0;
			$retVal2 = ($key->p_13_24 != 0) ? round(($key->e_13_24 / $key->p_13_24) * 100, 0) : 0;
			$retVal3 = ($key->p_25_36 != 0) ? round(($key->e_25_36 / $key->p_25_36) * 100, 0) : 0;
			$retVal4 = ($key->p_37_48 != 0) ? round(($key->e_37_48 / $key->p_37_48) * 100, 0) : 0;
			$retVal5 = ($key->p_49_60 != 0) ? round(($key->e_49_60 / $key->p_49_60) * 100, 0) : 0;
			$retVal6 = ($key->p_61_72 != 0) ? round(($key->e_61_72 / $key->p_61_72) * 100, 0) : 0;
			/* Suma total de Entradas y ventas */
			$tP_72_0 = $key->p_0_12 + $key->p_13_24 + $key->p_25_36 + $key->p_37_48 + $key->p_49_60 + $key->p_61_72;
			$tE_72_0 = $key->e_0_12 + $key->e_13_24 + $key->e_25_36 + $key->e_37_48 + $key->e_49_60 + $key->e_61_72;
			$total_72_0 = ($tP_72_0 != 0) ? round(($tE_72_0 / $tP_72_0) * 100, 0) : 0;
			/* Suma de tickets */
			$sSumaT = ($key->ft_0_12 + $key->ft_13_24 + $key->ft_25_36 + $key->ft_37_48 + $key->ft_49_60 + $key->ft_61_72);
			/* Total factura dividida por el total de entradas */
			$tFactura = ($tE_72_0 == 0) ? 0 : ($sSumaT / $tE_72_0);

			$disabled1 = ($key->p_0_12 == 0 || $key->e_0_12 == $key->p_0_12) ? 'disabled' : '';
			$disabled2 = ($key->p_13_24 == 0 || $key->e_13_24 == $key->p_13_24) ? 'disabled' : '';
			$disabled3 = ($key->p_25_36 == 0 || $key->e_25_36 == $key->p_25_36) ? 'disabled' : '';
			$disabled4 = ($key->p_37_48 == 0 || $key->e_37_48 == $key->p_37_48) ? 'disabled' : '';
			$disabled5 = ($key->p_49_60 == 0 || $key->e_49_60 == $key->p_49_60) ? 'disabled' : '';
			$disabled6 = ($key->p_61_72 == 0 || $key->e_61_72 == $key->p_61_72) ? 'disabled' : '';


			echo '
			<tr>
				<!-- Total -->
				<td class="color-1 fijar colun1">' . $key->familia_vh . " " . $key->tipo_vh . '</td>
				<td class="color-1 text-center tc1 totalVE_' . $ban . '">' . $tE_72_0 . '</td>
				<td class="color-1 text-center tc2 totalVE_' . $ban . '">' . $tP_72_0 . '</td>
				<td class="color-1 text-center">' . $total_72_0 . '%</td>
				<td class="color-1 text-center tc3" id="entradaAtotalVE_' . $ban . '">0</td>
				<td class="color-1 text-center" id="pEstimadoTtotalVE_' . $ban . '">0</td>
				<p class="color-1 text-center tc4 d-none" id="facturaTtotalVE_' . $ban . '">' . $tFactura . '</p>
				<td class="color-1 text-center">' . number_format($tFactura, 0, ",", ".") . '</td>
				<td class="color-1 text-center" id="fAdicionaltotalVE_' . $ban . '">0</td>
				<p class="color-1 text-center d-none tc5" id="fAdicional-totalVE_' . $ban . '">0</p>
				<!-- 12 - 0  -->

				<td class="color-2 text-center tc6 totalVE_' . $ban . '12">' . $key->e_0_12 . '</td>
				<td class="color-2 text-center tc7 totalVE_' . $ban . '12">' . $key->p_0_12 . '</td>
				<td class="color-2 text-center">' . $retVal1 . '%</td>				
				<td class="color-2 text-center" id="entradasAdicionalesT"><input value="0" ' . $disabled1 . ' class="tc8 numberT limpiar-totalVE_' . $ban . '12" type="number" min="1" id="entradaAtotalVE_' . $ban . '12" onchange="entradaAdicional(\'totalVE_' . $ban . '12\',' . $ban . ');"></td>
				<td class="color-2 text-center" id="pEstimadoTtotalVE_' . $ban . '12">0</td>
				<p class="color-2 text-center tc9 d-none" id="facturaTtotalVE_' . $ban . '12">' . $key->f_0_12 . '</p>
				<td class="color-2 text-center">' . number_format($key->f_0_12, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc10 d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '12">0</p>
				<td class="color-2 text-center " id="fAdicionaltotalVE_' . $ban . '12">0</td>

				<!-- 24 - 12 -->

				<td class="color-3 text-center tc11 totalVE_' . $ban . '24">' . $key->e_13_24 . '</td>
				<td class="color-3 text-center tc12 totalVE_' . $ban . '24">' . $key->p_13_24 . '</td>
				<td class="color-3 text-center">' . $retVal2 . '%</td>
				
				<td class="color-3 text-center" id="entradasAdicionalesT"><input value="0" ' . $disabled2 . ' class="tc13 numberT limpiar-totalVE_' . $ban . '24" type="number" min="1" id="entradaAtotalVE_' . $ban . '24" onchange="entradaAdicional(\'totalVE_' . $ban . '24\',' . $ban . ');"></td>
				<td class="color-3 text-center" id="pEstimadoTtotalVE_' . $ban . '24">0</td>
				<p class="color-3 text-center tc14 d-none" id="facturaTtotalVE_' . $ban . '24">' . $key->f_13_24 . '</p>
				<td class="color-3 text-center">' . number_format($key->f_13_24, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc15 d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '24">0</p>
				<td class="color-3 text-center " id="fAdicionaltotalVE_' . $ban . '24">0</td>

				<!-- 36 - 24 -->

				<td class="color-4 text-center tc16 totalVE_' . $ban . '36">' . $key->e_25_36 . '</td>
				<td class="color-4 text-center tc17 totalVE_' . $ban . '36">' . $key->p_25_36 . '</td>
				<td class="color-4 text-center">' . $retVal3 . '%</td>
				
				<td class="color-4 text-center " id="entradasAdicionalesT"><input value="0" ' . $disabled3 . ' class="tc18 numberT limpiar-totalVE_' . $ban . '36" type="number" min="1" id="entradaAtotalVE_' . $ban . '36" onchange="entradaAdicional(\'totalVE_' . $ban . '36\',' . $ban . ');"></td>
				<td class="color-4 text-center" id="pEstimadoTtotalVE_' . $ban . '36">0</td>
				<p class="color-4 text-center tc19 d-none" id="facturaTtotalVE_' . $ban . '36">' . $key->f_25_36 . '</p>
				<td class="color-4 text-center">' . number_format($key->f_25_36, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc20  d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '36">0</p>
				<td class="color-4 text-center " id="fAdicionaltotalVE_' . $ban . '36">0</td>

				<!-- 48 - 36 -->
				<td class="color-5 text-center tc21 totalVE_' . $ban . '48">' . $key->e_37_48 . '</td>
				<td class="color-5 text-center tc22 totalVE_' . $ban . '48">' . $key->p_37_48 . '</td>
				<td class="color-5 text-center">' . $retVal4 . '%</td>
				
				<td class="color-5 text-center " id="entradasAdicionalesT"><input value="0" ' . $disabled4 . ' class="tc23 numberT limpiar-totalVE_' . $ban . '48" type="number" min="1" id="entradaAtotalVE_' . $ban . '48" onchange="entradaAdicional(\'totalVE_' . $ban . '48\',' . $ban . ');"></td>
				<td class="color-5 text-center" id="pEstimadoTtotalVE_' . $ban . '48">0</td>
				<p class="color-5 text-center tc24 d-none" id="facturaTtotalVE_' . $ban . '48">' . $key->f_37_48 . '</p>
				<td class="color-5 text-center">' . number_format($key->f_37_48, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc25 d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '48">0</p>
				<td class="color-5 text-center " id="fAdicionaltotalVE_' . $ban . '48">0</td>

				<!-- 60 - 48  -->
				<td class="color-6 text-center tc26 totalVE_' . $ban . '60">' . $key->e_49_60 . '</td>
				<td class="color-6 text-center tc27 totalVE_' . $ban . '60">' . $key->p_49_60 . '</td>
				<td class="color-6 text-center">' . $retVal5 . '%</td>
				
				<td class="color-6 text-center " id="entradasAdicionalesT"><input value="0" ' . $disabled5 . ' class="tc28 numberT limpiar-totalVE_' . $ban . '60" type="number" min="1" id="entradaAtotalVE_' . $ban . '60" onchange="entradaAdicional(\'totalVE_' . $ban . '60\',' . $ban . ');"></td>
				<td class="color-6 text-center" id="pEstimadoTtotalVE_' . $ban . '60">0</td>
				<p class="color-6 text-center tc29 d-none" id="facturaTtotalVE_' . $ban . '60">' . $key->f_49_60 . '</p>
				<td class="color-6 text-center">' . number_format($key->f_49_60, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc30 d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '60">0</p>
				<td class="color-6 text-center " id="fAdicionaltotalVE_' . $ban . '60">0</td>

				<!-- 72 - 60 -->
				<td class="color-7 text-center tc31 totalVE_' . $ban . '72">' . $key->e_61_72 . '</td>
				<td class="color-7 text-center tc32 totalVE_' . $ban . '72">' . $key->p_61_72 . '</td>
				<td class="color-7 text-center">' . $retVal6 . '%</td>
				
				<td class="color-7 text-center " id="entradasAdicionalesT"><input value="0" ' . $disabled6 . ' class="tc33 numberT limpiar-totalVE_' . $ban . '72" type="number" min="1" id="entradaAtotalVE_' . $ban . '72" onchange="entradaAdicional(\'totalVE_' . $ban . '72\',' . $ban . ');"></td>
				<td class="color-7 text-center" id="pEstimadoTtotalVE_' . $ban . '72">0</td>
				<p class="color-7 text-center tc34 d-none" id="facturaTtotalVE_' . $ban . '72">' . $key->f_61_72 . '</p>
				<td class="color-7 text-center">' . number_format($key->f_61_72, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc35 d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '72">0</p>
				<td class="color-7 text-center " id="fAdicionaltotalVE_' . $ban . '72">0</td>
			</tr>';


			$ban++;
		}
		echo '
	</tbody>
	<tfoot>
		<tr>
			<!-- Total -->
			<th class="text-center fijar2 colun1">Total</th>
			<th class="text-center fijar2 colun1" id="th1">EUU</th>
			<th class="text-center fijar2 colun1" id="th2">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th3">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th4">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th5">FACTURACIÓN</th>

			<!-- 12 - 0 -->

			<th class="text-center fijar2 colun1" id="th6">EUU</th>
			<th class="text-center fijar2 colun1" id="th7">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th8">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th9">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th10">FACTURACIÓN</th>
			<!-- 24 - 12 -->
			<th class="text-center fijar2 colun1" id="th11">EUU</th>
			<th class="text-center fijar2 colun1" id="th12">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th13">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th14">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th15">FACTURACIÓN</th>
			<!-- 36 - 24 -->
			<th class="text-center fijar2 colun1" id="th16">EUU</th>
			<th class="text-center fijar2 colun1" id="th17">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th18">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th19">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th20">FACTURACIÓN</th>
			<!-- 48 - 36 -->
			<th class="text-center fijar2 colun1" id="th21">EUU</th>
			<th class="text-center fijar2 colun1" id="th22">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th23">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th24">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th25">FACTURACIÓN</th>
			<!-- 60 - 48 -->
			<th class="text-center fijar2 colun1" id="th26">EUU</th>
			<th class="text-center fijar2 colun1" id="th27">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th28">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th29">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th30">FACTURACIÓN</th>
			<!-- 72 - 60 -->

			<th class="text-center fijar2 colun1" id="th31">EUU</th>
			<th class="text-center fijar2 colun1" id="th32">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th33">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th34">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th35">FACTURACIÓN</th>

		</tr>
	</tfoot>
		';
	}
	public function tablaFiltroSegmentoFamilia()
	{
		$this->load->model('Informe');
		$filtro = $this->input->POST('filtro');

		$dataInforme = $this->Informe->filtroTablaSegmentouFamilia($filtro);

		echo '
		<thead align="center" class="thead-light">
		<tr>
			<th class="titulo colun1 fijar" scope="col"></th>
			<th colspan="7" class="titulo colun1 fijar" scope="col">Total</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">12. - 0</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">24 - 12</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">36 - 24</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">48 - 36</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">60 - 48</th>

			<th colspan="7" class="titulo colun1 fijar" scope="col">72 - 60</th>

		</tr>
		<tr>
			<th class="titulo2 fijar colun1" scope="col">Familia vh</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
			<th class="titulo2 fijar colun1" scope="col">EUU</th>
			<th class="titulo2 fijar colun1" scope="col">TV</th>
			<th class="titulo2 fijar colun1" scope="col">RP</th>
			<th class="titulo2 fijar colun1" scope="col">EUA</th>
			<th class="titulo2 fijar colun1" scope="col">RPE</th>
			<th class="titulo2 fijar colun1" scope="col">$TICKET</th>
			<th class="titulo2 fijar colun1" scope="col">Facturación adicional</th>
		</tr>
	</thead>
	<tbody align="center">';

		$ban = 0;
		foreach ($dataInforme->result() as $key) {
			/* retVal se calcula el porcentaje de retención propia validando que las ventas no sean 0 */
			$retVal1 = ($key->p_0_12 != 0) ? round(($key->e_0_12 / $key->p_0_12) * 100, 0) : 0;
			$retVal2 = ($key->p_13_24 != 0) ? round(($key->e_13_24 / $key->p_13_24) * 100, 0) : 0;
			$retVal3 = ($key->p_25_36 != 0) ? round(($key->e_25_36 / $key->p_25_36) * 100, 0) : 0;
			$retVal4 = ($key->p_37_48 != 0) ? round(($key->e_37_48 / $key->p_37_48) * 100, 0) : 0;
			$retVal5 = ($key->p_49_60 != 0) ? round(($key->e_49_60 / $key->p_49_60) * 100, 0) : 0;
			$retVal6 = ($key->p_61_72 != 0) ? round(($key->e_61_72 / $key->p_61_72) * 100, 0) : 0;
			/* Suma total de Entradas y ventas */
			$tP_72_0 = $key->p_0_12 + $key->p_13_24 + $key->p_25_36 + $key->p_37_48 + $key->p_49_60 + $key->p_61_72;
			$tE_72_0 = $key->e_0_12 + $key->e_13_24 + $key->e_25_36 + $key->e_37_48 + $key->e_49_60 + $key->e_61_72;
			$total_72_0 = ($tP_72_0 != 0) ? round(($tE_72_0 / $tP_72_0) * 100, 0) : 0;
			/* Suma de tickets */
			$sSumaT = ($key->ft_0_12 + $key->ft_13_24 + $key->ft_25_36 + $key->ft_37_48 + $key->ft_49_60 + $key->ft_61_72);
			/* Total factura dividida por el total de entradas */
			$tFactura = ($tE_72_0 == 0) ? 0 : ($sSumaT / $tE_72_0);

			$disabled1 = ($key->p_0_12 == 0 || $key->e_0_12 == $key->p_0_12) ? 'disabled' : '';
			$disabled2 = ($key->p_13_24 == 0 || $key->e_13_24 == $key->p_13_24) ? 'disabled' : '';
			$disabled3 = ($key->p_25_36 == 0 || $key->e_25_36 == $key->p_25_36) ? 'disabled' : '';
			$disabled4 = ($key->p_37_48 == 0 || $key->e_37_48 == $key->p_37_48) ? 'disabled' : '';
			$disabled5 = ($key->p_49_60 == 0 || $key->e_49_60 == $key->p_49_60) ? 'disabled' : '';
			$disabled6 = ($key->p_61_72 == 0 || $key->e_61_72 == $key->p_61_72) ? 'disabled' : '';


			echo '
			<tr>
				<!-- Total -->
				<td class="color-1 fijar colun1">' . $key->familia_vh . " " . $key->tipo_vh . '</td>
				<td class="color-1 text-center tc1 totalVE_' . $ban . '">' . $tE_72_0 . '</td>
				<td class="color-1 text-center tc2 totalVE_' . $ban . '">' . $tP_72_0 . '</td>
				<td class="color-1 text-center">' . $total_72_0 . '%</td>
				<td class="color-1 text-center tc3" id="entradaAtotalVE_' . $ban . '">0</td>
				<td class="color-1 text-center" id="pEstimadoTtotalVE_' . $ban . '">0</td>
				<p class="color-1 text-center tc4 d-none" id="facturaTtotalVE_' . $ban . '">' . $tFactura . '</p>
				<td class="color-1 text-center">' . number_format($tFactura, 0, ",", ".") . '</td>
				<td class="color-1 text-center" id="fAdicionaltotalVE_' . $ban . '">0</td>
				<p class="color-1 text-center d-none tc5" id="fAdicional-totalVE_' . $ban . '">0</p>
				<!-- 12 - 0  -->

				<td class="color-2 text-center tc6 totalVE_' . $ban . '12">' . $key->e_0_12 . '</td>
				<td class="color-2 text-center tc7 totalVE_' . $ban . '12">' . $key->p_0_12 . '</td>
				<td class="color-2 text-center">' . $retVal1 . '%</td>				
				<td class="color-2 text-center" id="entradasAdicionalesT"><input value="0" ' . $disabled1 . ' class="tc8 numberT limpiar-totalVE_' . $ban . '12" type="number" min="1" id="entradaAtotalVE_' . $ban . '12" onchange="entradaAdicional(\'totalVE_' . $ban . '12\',' . $ban . ');"></td>
				<td class="color-2 text-center" id="pEstimadoTtotalVE_' . $ban . '12">0</td>
				<p class="color-2 text-center tc9 d-none" id="facturaTtotalVE_' . $ban . '12">' . $key->f_0_12 . '</p>
				<td class="color-2 text-center">' . number_format($key->f_0_12, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc10 d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '12">0</p>
				<td class="color-2 text-center " id="fAdicionaltotalVE_' . $ban . '12">0</td>

				<!-- 24 - 12 -->

				<td class="color-3 text-center tc11 totalVE_' . $ban . '24">' . $key->e_13_24 . '</td>
				<td class="color-3 text-center tc12 totalVE_' . $ban . '24">' . $key->p_13_24 . '</td>
				<td class="color-3 text-center">' . $retVal2 . '%</td>
				
				<td class="color-3 text-center" id="entradasAdicionalesT"><input value="0" ' . $disabled2 . ' class="tc13 numberT limpiar-totalVE_' . $ban . '24" type="number" min="1" id="entradaAtotalVE_' . $ban . '24" onchange="entradaAdicional(\'totalVE_' . $ban . '24\',' . $ban . ');"></td>
				<td class="color-3 text-center" id="pEstimadoTtotalVE_' . $ban . '24">0</td>
				<p class="color-3 text-center tc14 d-none" id="facturaTtotalVE_' . $ban . '24">' . $key->f_13_24 . '</p>
				<td class="color-3 text-center">' . number_format($key->f_13_24, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc15 d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '24">0</p>
				<td class="color-3 text-center " id="fAdicionaltotalVE_' . $ban . '24">0</td>

				<!-- 36 - 24 -->

				<td class="color-4 text-center tc16 totalVE_' . $ban . '36">' . $key->e_25_36 . '</td>
				<td class="color-4 text-center tc17 totalVE_' . $ban . '36">' . $key->p_25_36 . '</td>
				<td class="color-4 text-center">' . $retVal3 . '%</td>
				
				<td class="color-4 text-center " id="entradasAdicionalesT"><input value="0" ' . $disabled3 . ' class="tc18 numberT limpiar-totalVE_' . $ban . '36" type="number" min="1" id="entradaAtotalVE_' . $ban . '36" onchange="entradaAdicional(\'totalVE_' . $ban . '36\',' . $ban . ');"></td>
				<td class="color-4 text-center" id="pEstimadoTtotalVE_' . $ban . '36">0</td>
				<p class="color-4 text-center tc19 d-none" id="facturaTtotalVE_' . $ban . '36">' . $key->f_25_36 . '</p>
				<td class="color-4 text-center">' . number_format($key->f_25_36, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc20  d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '36">0</p>
				<td class="color-4 text-center " id="fAdicionaltotalVE_' . $ban . '36">0</td>

				<!-- 48 - 36 -->
				<td class="color-5 text-center tc21 totalVE_' . $ban . '48">' . $key->e_37_48 . '</td>
				<td class="color-5 text-center tc22 totalVE_' . $ban . '48">' . $key->p_37_48 . '</td>
				<td class="color-5 text-center">' . $retVal4 . '%</td>
				
				<td class="color-5 text-center " id="entradasAdicionalesT"><input value="0" ' . $disabled4 . ' class="tc23 numberT limpiar-totalVE_' . $ban . '48" type="number" min="1" id="entradaAtotalVE_' . $ban . '48" onchange="entradaAdicional(\'totalVE_' . $ban . '48\',' . $ban . ');"></td>
				<td class="color-5 text-center" id="pEstimadoTtotalVE_' . $ban . '48">0</td>
				<p class="color-5 text-center tc24 d-none" id="facturaTtotalVE_' . $ban . '48">' . $key->f_37_48 . '</p>
				<td class="color-5 text-center">' . number_format($key->f_37_48, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc25 d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '48">0</p>
				<td class="color-5 text-center " id="fAdicionaltotalVE_' . $ban . '48">0</td>

				<!-- 60 - 48  -->
				<td class="color-6 text-center tc26 totalVE_' . $ban . '60">' . $key->e_49_60 . '</td>
				<td class="color-6 text-center tc27 totalVE_' . $ban . '60">' . $key->p_49_60 . '</td>
				<td class="color-6 text-center">' . $retVal5 . '%</td>
				
				<td class="color-6 text-center " id="entradasAdicionalesT"><input value="0" ' . $disabled5 . ' class="tc28 numberT limpiar-totalVE_' . $ban . '60" type="number" min="1" id="entradaAtotalVE_' . $ban . '60" onchange="entradaAdicional(\'totalVE_' . $ban . '60\',' . $ban . ');"></td>
				<td class="color-6 text-center" id="pEstimadoTtotalVE_' . $ban . '60">0</td>
				<p class="color-6 text-center tc29 d-none" id="facturaTtotalVE_' . $ban . '60">' . $key->f_49_60 . '</p>
				<td class="color-6 text-center">' . number_format($key->f_49_60, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc30 d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '60">0</p>
				<td class="color-6 text-center " id="fAdicionaltotalVE_' . $ban . '60">0</td>

				<!-- 72 - 60 -->
				<td class="color-7 text-center tc31 totalVE_' . $ban . '72">' . $key->e_61_72 . '</td>
				<td class="color-7 text-center tc32 totalVE_' . $ban . '72">' . $key->p_61_72 . '</td>
				<td class="color-7 text-center">' . $retVal6 . '%</td>
				
				<td class="color-7 text-center " id="entradasAdicionalesT"><input value="0" ' . $disabled6 . ' class="tc33 numberT limpiar-totalVE_' . $ban . '72" type="number" min="1" id="entradaAtotalVE_' . $ban . '72" onchange="entradaAdicional(\'totalVE_' . $ban . '72\',' . $ban . ');"></td>
				<td class="color-7 text-center" id="pEstimadoTtotalVE_' . $ban . '72">0</td>
				<p class="color-7 text-center tc34 d-none" id="facturaTtotalVE_' . $ban . '72">' . $key->f_61_72 . '</p>
				<td class="color-7 text-center">' . number_format($key->f_61_72, 0, ",", ".") . '</td>
				<p class="color-2 text-center tc35 d-none fAdicionales' . $ban . '" id="fAdicional-totalVE_' . $ban . '72">0</p>
				<td class="color-7 text-center " id="fAdicionaltotalVE_' . $ban . '72">0</td>
			</tr>';


			$ban++;
		}
		echo '
	</tbody>
	<tfoot>
		<tr>
			<!-- Total -->
			<th class="text-center fijar2 colun1">Total</th>
			<th class="text-center fijar2 colun1" id="th1">EUU</th>
			<th class="text-center fijar2 colun1" id="th2">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th3">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th4">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th5">FACTURACIÓN</th>

			<!-- 12 - 0 -->

			<th class="text-center fijar2 colun1" id="th6">EUU</th>
			<th class="text-center fijar2 colun1" id="th7">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th8">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th9">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th10">FACTURACIÓN</th>
			<!-- 24 - 12 -->
			<th class="text-center fijar2 colun1" id="th11">EUU</th>
			<th class="text-center fijar2 colun1" id="th12">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th13">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th14">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th15">FACTURACIÓN</th>
			<!-- 36 - 24 -->
			<th class="text-center fijar2 colun1" id="th16">EUU</th>
			<th class="text-center fijar2 colun1" id="th17">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th18">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th19">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th20">FACTURACIÓN</th>
			<!-- 48 - 36 -->
			<th class="text-center fijar2 colun1" id="th21">EUU</th>
			<th class="text-center fijar2 colun1" id="th22">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th23">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th24">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th25">FACTURACIÓN</th>
			<!-- 60 - 48 -->
			<th class="text-center fijar2 colun1" id="th26">EUU</th>
			<th class="text-center fijar2 colun1" id="th27">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th28">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th29">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th30">FACTURACIÓN</th>
			<!-- 72 - 60 -->

			<th class="text-center fijar2 colun1" id="th31">EUU</th>
			<th class="text-center fijar2 colun1" id="th32">TV</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th33">EUA</th>
			<th class="text-center fijar2 colun1">N/A</th>
			<th class="text-center fijar2 colun1" id="th34">$TICKET</th>
			<th class="text-center fijar2 colun1" id="th35">FACTURACIÓN</th>

		</tr>
	</tfoot>
		';
	}



	/* 1 de junio del 2022
	autor: Sergio Galvis */
	/* ********************************************** Comparación o versus ****************************************************** */
	public function InformeVentasVs()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Informe');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";


			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}

			/* Obtenemos los datos de la consulta en la base de datos */



			$dataAutos = $this->Informe->dataAutos();
			$dataByC = $this->Informe->dataByC();
			$dataDetalleAutos = $this->Informe->dataAutosDetalle();
			$dataDetalleByC = $this->Informe->dataByCDetalle();

			$arr_user = array(
				'dataAutos' => $dataAutos, 'dataByC' => $dataByC, 'dataDetalleAutos' => $dataDetalleAutos, 'dataDetalleByC' => $dataDetalleByC,
				'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu
			);
			//abrimos la vista
			$this->load->view("gestionVentas/InformeVentasVs", $arr_user);
		}
	}
	/* Función para traer los datos para la graficas de Autos y B&C General */
	public function GrafInformeVentas72Vs()
	{
		$this->load->model('Informe');

		/* Obtenemos los datos de la consulta en la base de datos */
		$datos = $this->Informe->grafGeneralVs();
		/* Iniciamos las variables para la suma de autos en 0 XD donde se realizara la suma para el total del Informe */
		$var12e = 0;
		$var12p = 0;
		$var24e = 0;
		$var24p = 0;
		$var36e = 0;
		$var36p = 0;
		$var48e = 0;
		$var48p = 0;
		$var60e = 0;
		$var60p = 0;
		$var72e = 0;
		$var72p = 0;
		$varP12 = 0;
		$varP24 = 0;
		$varP36 = 0;
		$varP48 = 0;
		$varP60 = 0;
		$varP72 = 0;
		/* Iniciamos las variables para la suma de B&C en 0 XD donde se realizara la suma para el total del Informe */
		$var12e_b = 0;
		$var12p_b = 0;
		$var24e_b = 0;
		$var24p_b = 0;
		$var36e_b = 0;
		$var36p_b = 0;
		$var48e_b = 0;
		$var48p_b = 0;
		$var60e_b = 0;
		$var60p_b = 0;
		$var72e_b = 0;
		$var72p_b = 0;
		$varP12_b = 0;
		$varP24_b = 0;
		$varP36_b = 0;
		$varP48_b = 0;
		$varP60_b = 0;
		$varP72_b = 0;
		/* Inicializamos los array donde se guardaran los datos para las graficas */
		$dataGrafAutos = array();
		$dataGrafByC = array();
		foreach ($datos->result() as $key) {
			if ($key->tipo == "Autos") {

				$var12e += $key->e_0_12;
				$var12p += $key->p0_12;

				$var24e += $key->e_13_24;
				$var24p += $key->p13_24;

				$var36e += $key->e_25_36;
				$var36p += $key->p25_36;

				$var48e += $key->e_37_48;
				$var48p += $key->p37_48;

				$var60e += $key->e_49_60;
				$var60p += $key->p49_60;

				$var72e += $key->e_61_72;
				$var72p += $key->p61_72;
			} elseif ($key->tipo == "B&C") {
				$var12e_b += $key->e_0_12;
				$var12p_b += $key->p0_12;

				$var24e_b += $key->e_13_24;
				$var24p_b += $key->p13_24;

				$var36e_b += $key->e_25_36;
				$var36p_b += $key->p25_36;

				$var48e_b += $key->e_37_48;
				$var48p_b += $key->p37_48;

				$var60e_b += $key->e_49_60;
				$var60p_b += $key->p49_60;

				$var72e_b += $key->e_61_72;
				$var72p_b += $key->p61_72;
			}
		}
		/* Autos */
		$varP12 += round(($var12e / $var12p) * 100, 0);
		$varP24 += round(($var24e / $var24p) * 100, 0);
		$varP36 += round(($var36e / $var36p) * 100, 0);
		$varP48 += round(($var48e / $var48p) * 100, 0);
		$varP60 += round(($var60e / $var60p) * 100, 0);
		$varP72 += round(($var72e / $var72p) * 100, 0);

		$dataGrafAutos[0] = array("y" => $varP12, "label" => "12 - 0");
		$dataGrafAutos[1] = array("y" => $varP24, "label" => "24 - 12");
		$dataGrafAutos[2] = array("y" => $varP36, "label" => "36 - 24");
		$dataGrafAutos[3] = array("y" => $varP48, "label" => "48 - 36");
		$dataGrafAutos[4] = array("y" => $varP60, "label" => "60 - 48");
		$dataGrafAutos[5] = array("y" => $varP72, "label" => "72 - 60");
		/* B&C */
		$varP12_b += round(($var12e_b / $var12p_b) * 100, 0);
		$varP24_b += round(($var24e_b / $var24p_b) * 100, 0);
		$varP36_b += round(($var36e_b / $var36p_b) * 100, 0);
		$varP48_b += round(($var48e_b / $var48p_b) * 100, 0);
		$varP60_b += round(($var60e_b / $var60p_b) * 100, 0);
		$varP72_b += round(($var72e_b / $var72p_b) * 100, 0);

		$dataGrafByC[0] = array("y" => $varP12_b, "label" => "12 - 0");
		$dataGrafByC[1] = array("y" => $varP24_b, "label" => "24 - 12");
		$dataGrafByC[2] = array("y" => $varP36_b, "label" => "36 - 24");
		$dataGrafByC[3] = array("y" => $varP48_b, "label" => "48 - 36");
		$dataGrafByC[4] = array("y" => $varP60_b, "label" => "60 - 48");
		$dataGrafByC[5] = array("y" => $varP72_b, "label" => "72 - 60");



		$arr_user = array('dataGrafByC' => $dataGrafByC, 'dataGrafAutos' => $dataGrafAutos);

		echo json_encode($arr_user);
	}
	/* Función para traer los datos para la graficas de Autos y B&C */
	public function GrafInformeVentas72VsFiltro()
	{
		$this->load->model('Informe');
		/* Obtenemos los datos de la consulta en la base de datos */
		$filtro = $this->input->POST('filtro');
		$tipo = $this->input->POST('tipo');

		if ($filtro != 'Autos' && $filtro != 'B&C') {
			$datos_segmento = $this->Informe->infGrafGeneralSegmento($filtro);
			/* Iniciamos las variables para la suma de autos en 0 XD donde se realizara la suma para el total del Informe */
			$var12e = 0;
			$var12p = 0;
			$var24e = 0;
			$var24p = 0;
			$var36e = 0;
			$var36p = 0;
			$var48e = 0;
			$var48p = 0;
			$var60e = 0;
			$var60p = 0;
			$var72e = 0;
			$var72p = 0;
			$varP12 = 0;
			$varP24 = 0;
			$varP36 = 0;
			$varP48 = 0;
			$varP60 = 0;
			$varP72 = 0;
			/* Inicializamos los array donde se guardaran los datos para las graficas */
			foreach ($datos_segmento->result() as $key) {

				$var12e += $key->e_0_12;
				$var12p += $key->p0_12;

				$var24e += $key->e_13_24;
				$var24p += $key->p13_24;

				$var36e += $key->e_25_36;
				$var36p += $key->p25_36;

				$var48e += $key->e_37_48;
				$var48p += $key->p37_48;

				$var60e += $key->e_49_60;
				$var60p += $key->p49_60;

				$var72e += $key->e_61_72;
				$var72p += $key->p61_72;
			}
			/* Autos */
			$varP12 += ($var12p == 0) ? 0 : round(($var12e / $var12p) * 100, 0);
			$varP24 += ($var24p == 0) ? 0 : round(($var24e / $var24p) * 100, 0);
			$varP36 += ($var36p == 0) ? 0 : round(($var36e / $var36p) * 100, 0);
			$varP48 += ($var48p == 0) ? 0 : round(($var48e / $var48p) * 100, 0);
			$varP60 += ($var60p == 0) ? 0 : round(($var60e / $var60p) * 100, 0);
			$varP72 += ($var72p == 0) ? 0 : round(($var72e / $var72p) * 100, 0);
			$arr_total[0] = $filtro;
			$arr_total[1] = $varP12;
			$arr_total[2] = $varP24;
			$arr_total[3] = $varP36;
			$arr_total[4] = $varP48;
			$arr_total[5] = $varP60;
			$arr_total[6] = $varP72;

			$arr_total[7] = $var12e;
			$arr_total[8] = $var12p;
			$arr_total[9] = $var24e;
			$arr_total[10] = $var24p;
			$arr_total[11] = $var36e;
			$arr_total[12] = $var36p;
			$arr_total[13] = $var48e;
			$arr_total[14] = $var48p;
			$arr_total[15] = $var60e;
			$arr_total[16] = $var60p;
			$arr_total[17] = $var72e;
			$arr_total[18] = $var72p;
		} else {
			if ($tipo == "Autos") {
				/* Obtenemos los datos de la consulta en la base de datos */
				$datos_tipo = $this->Informe->grafGeneralVs();
				/* Iniciamos las variables para la suma de autos en 0 XD donde se realizara la suma para el total del Informe */
				$var12e = 0;
				$var12p = 0;
				$var24e = 0;
				$var24p = 0;
				$var36e = 0;
				$var36p = 0;
				$var48e = 0;
				$var48p = 0;
				$var60e = 0;
				$var60p = 0;
				$var72e = 0;
				$var72p = 0;
				$varP12 = 0;
				$varP24 = 0;
				$varP36 = 0;
				$varP48 = 0;
				$varP60 = 0;
				$varP72 = 0;
				/* Inicializamos los array donde se guardaran los datos para las graficas */
				foreach ($datos_tipo->result() as $key) {
					if ($key->tipo == "Autos") {

						$var12e += $key->e_0_12;
						$var12p += $key->p0_12;

						$var24e += $key->e_13_24;
						$var24p += $key->p13_24;

						$var36e += $key->e_25_36;
						$var36p += $key->p25_36;

						$var48e += $key->e_37_48;
						$var48p += $key->p37_48;

						$var60e += $key->e_49_60;
						$var60p += $key->p49_60;

						$var72e += $key->e_61_72;
						$var72p += $key->p61_72;
					}
				}
				/* Autos */
				$varP12 += ($var12p == 0) ? 0 : round(($var12e / $var12p) * 100, 0);
				$varP24 += ($var24p == 0) ? 0 : round(($var24e / $var24p) * 100, 0);
				$varP36 += ($var36p == 0) ? 0 : round(($var36e / $var36p) * 100, 0);
				$varP48 += ($var48p == 0) ? 0 : round(($var48e / $var48p) * 100, 0);
				$varP60 += ($var60p == 0) ? 0 : round(($var60e / $var60p) * 100, 0);
				$varP72 += ($var72p == 0) ? 0 : round(($var72e / $var72p) * 100, 0);
				$arr_total[0] = $tipo;
				$arr_total[1] = $varP12;
				$arr_total[2] = $varP24;
				$arr_total[3] = $varP36;
				$arr_total[4] = $varP48;
				$arr_total[5] = $varP60;
				$arr_total[6] = $varP72;
				$arr_total[7] = $var12e;
				$arr_total[8] = $var12p;
				$arr_total[9] = $var24e;
				$arr_total[10] = $var24p;
				$arr_total[11] = $var36e;
				$arr_total[12] = $var36p;
				$arr_total[13] = $var48e;
				$arr_total[14] = $var48p;
				$arr_total[15] = $var60e;
				$arr_total[16] = $var60p;
				$arr_total[17] = $var72e;
				$arr_total[18] = $var72p;
			} elseif ($tipo == "B&C") {
				/* Obtenemos los datos de la consulta en la base de datos */
				$datos_tipo = $this->Informe->grafGeneralVs();
				/* Iniciamos las variables para la suma de autos en 0 XD donde se realizara la suma para el total del Informe */
				$var12e = 0;
				$var12p = 0;
				$var24e = 0;
				$var24p = 0;
				$var36e = 0;
				$var36p = 0;
				$var48e = 0;
				$var48p = 0;
				$var60e = 0;
				$var60p = 0;
				$var72e = 0;
				$var72p = 0;
				$varP12 = 0;
				$varP24 = 0;
				$varP36 = 0;
				$varP48 = 0;
				$varP60 = 0;
				$varP72 = 0;
				/* Inicializamos los array donde se guardaran los datos para las graficas */
				foreach ($datos_tipo->result() as $key) {
					if ($key->tipo == "B&C") {

						$var12e += $key->e_0_12;
						$var12p += $key->p0_12;

						$var24e += $key->e_13_24;
						$var24p += $key->p13_24;

						$var36e += $key->e_25_36;
						$var36p += $key->p25_36;

						$var48e += $key->e_37_48;
						$var48p += $key->p37_48;

						$var60e += $key->e_49_60;
						$var60p += $key->p49_60;

						$var72e += $key->e_61_72;
						$var72p += $key->p61_72;
					}
				}
				/* B&C */
				$varP12 += ($var12p == 0) ? 0 : round(($var12e / $var12p) * 100, 0);
				$varP24 += ($var24p == 0) ? 0 : round(($var24e / $var24p) * 100, 0);
				$varP36 += ($var36p == 0) ? 0 : round(($var36e / $var36p) * 100, 0);
				$varP48 += ($var48p == 0) ? 0 : round(($var48e / $var48p) * 100, 0);
				$varP60 += ($var60p == 0) ? 0 : round(($var60e / $var60p) * 100, 0);
				$varP72 += ($var72p == 0) ? 0 : round(($var72e / $var72p) * 100, 0);
				$arr_total[] = $tipo;
				$arr_total[] = $varP12;
				$arr_total[] = $varP24;
				$arr_total[] = $varP36;
				$arr_total[] = $varP48;
				$arr_total[] = $varP60;
				$arr_total[] = $varP72;

				$arr_total[] = $var12e;
				$arr_total[] = $var12p;
				$arr_total[] = $var24e;
				$arr_total[] = $var24p;
				$arr_total[] = $var36e;
				$arr_total[] = $var36p;
				$arr_total[] = $var48e;
				$arr_total[] = $var48p;
				$arr_total[] = $var60e;
				$arr_total[] = $var60p;
				$arr_total[] = $var72e;
				$arr_total[] = $var72p;
			}
		}


		$datos_familia_vh = $this->Informe->GrafAutosyByCVs($filtro);
		$arr_data = $datos_familia_vh->result();
		$contador1 = 0;
		$contador2 = 1;
		for ($i = 0; $i < (count($arr_data) / 2); $i++) {
			$rowActual = $datos_familia_vh->row($contador1);
			$rowSiguiente = $datos_familia_vh->row($contador2);

			if ($rowActual->segmento == $rowSiguiente->segmento && ($rowActual->tipo_vh == 'Flota' && $rowSiguiente->tipo_vh == 'Retail')) {
				if ($filtro == "Autos" || $filtro == "B&C") {
					$arr_total[] = $rowActual->segmento;
				} else {
					$arr_total[] = $rowActual->familia_vh;
				}

				$arr_total[] = (($rowActual->p_0_12  + $rowSiguiente->p_0_12) == 0) ? 0 : (round((($rowActual->e_0_12  + $rowSiguiente->e_0_12)  / ($rowActual->p_0_12  + $rowSiguiente->p_0_12)) * 100, 0));
				$arr_total[] = (($rowActual->p_13_24 + $rowSiguiente->p_13_24) == 0) ? 0 : (round((($rowActual->e_13_24 + $rowSiguiente->e_13_24) / ($rowActual->p_13_24 + $rowSiguiente->p_13_24)) * 100, 0));
				$arr_total[] = (($rowActual->p_25_36 + $rowSiguiente->p_25_36) == 0) ? 0 : (round((($rowActual->e_25_36 + $rowSiguiente->e_25_36) / ($rowActual->p_25_36 + $rowSiguiente->p_25_36)) * 100, 0));
				$arr_total[] = (($rowActual->p_37_48 + $rowSiguiente->p_37_48) == 0) ? 0 : (round((($rowActual->e_37_48 + $rowSiguiente->e_37_48) / ($rowActual->p_37_48 + $rowSiguiente->p_37_48)) * 100, 0));
				$arr_total[] = (($rowActual->p_49_60 + $rowSiguiente->p_49_60) == 0) ? 0 : (round((($rowActual->e_49_60 + $rowSiguiente->e_49_60) / ($rowActual->p_49_60 + $rowSiguiente->p_49_60)) * 100, 0));
				$arr_total[] = (($rowActual->p_61_72 + $rowSiguiente->p_61_72) == 0) ? 0 : (round((($rowActual->e_61_72 + $rowSiguiente->e_61_72) / ($rowActual->p_61_72 + $rowSiguiente->p_61_72)) * 100, 0));

				$arr_total[] = ($rowActual->e_0_12  + $rowSiguiente->e_0_12);
				$arr_total[] = ($rowActual->p_0_12  + $rowSiguiente->p_0_12);

				$arr_total[] = ($rowActual->e_13_24  + $rowSiguiente->e_13_24);
				$arr_total[] = ($rowActual->p_13_24  + $rowSiguiente->p_13_24);

				$arr_total[] = ($rowActual->e_25_36  + $rowSiguiente->e_25_36);
				$arr_total[] = ($rowActual->p_25_36  + $rowSiguiente->p_25_36);

				$arr_total[] = ($rowActual->e_37_48  + $rowSiguiente->e_37_48);
				$arr_total[] = ($rowActual->p_37_48  + $rowSiguiente->p_37_48);

				$arr_total[] = ($rowActual->e_49_60  + $rowSiguiente->e_49_60);
				$arr_total[] = ($rowActual->p_49_60  + $rowSiguiente->p_49_60);

				$arr_total[] = ($rowActual->e_61_72  + $rowSiguiente->e_61_72);
				$arr_total[] = ($rowActual->p_61_72  + $rowSiguiente->p_61_72);
			} else {

				if ($filtro == "Autos" || $filtro == "B&C") {
					$arr_total[] = $rowActual->segmento;
				} else {
					$arr_total[] = $rowActual->familia_vh;
				}

				$arr_total[] = (($rowActual->p_0_12) == 0) ? 0 : (round((($rowActual->e_0_12) / ($rowActual->p_0_12)) * 100, 0));
				$arr_total[] = (($rowActual->p_13_24) == 0) ? 0 : (round((($rowActual->e_13_24) / ($rowActual->p_13_24)) * 100, 0));
				$arr_total[] = (($rowActual->p_25_36) == 0) ? 0 : (round((($rowActual->e_25_36) / ($rowActual->p_25_36)) * 100, 0));
				$arr_total[] = (($rowActual->p_37_48) == 0) ? 0 : (round((($rowActual->e_37_48) / ($rowActual->p_37_48)) * 100, 0));
				$arr_total[] = (($rowActual->p_49_60) == 0) ? 0 : (round((($rowActual->e_49_60) / ($rowActual->p_49_60)) * 100, 0));
				$arr_total[] = (($rowActual->p_61_72) == 0) ? 0 : (round((($rowActual->e_61_72) / ($rowActual->p_61_72)) * 100, 0));

				$arr_total[] = ($rowActual->e_0_12);
				$arr_total[] = ($rowActual->p_0_12);

				$arr_total[] = ($rowActual->e_13_24);
				$arr_total[] = ($rowActual->p_13_24);

				$arr_total[] = ($rowActual->e_25_36);
				$arr_total[] = ($rowActual->p_25_36);

				$arr_total[] = ($rowActual->e_37_48);
				$arr_total[] = ($rowActual->p_37_48);

				$arr_total[] = ($rowActual->e_49_60);
				$arr_total[] = ($rowActual->p_49_60);

				$arr_total[] = ($rowActual->e_61_72);
				$arr_total[] = ($rowActual->p_61_72);
				/* Verificar si el segmento siguiente es diferente... */
				$rowNextValidar = $datos_familia_vh->row($contador2 + 1);
				if ($rowSiguiente->familia_vh  != $rowNextValidar->familia_vh) {
					if ($filtro == "Autos" || $filtro == "B&C") {
						$arr_total[] = $rowSiguiente->segmento;
					} else {
						$arr_total[] = $rowSiguiente->familia_vh;
					}

					$arr_total[] = (($rowSiguiente->p_0_12) == 0) ? 0 : (round((($rowSiguiente->e_0_12) / ($rowSiguiente->p_0_12)) * 100, 0));
					$arr_total[] = (($rowSiguiente->p_13_24) == 0) ? 0 : (round((($rowSiguiente->e_13_24) / ($rowSiguiente->p_13_24)) * 100, 0));
					$arr_total[] = (($rowSiguiente->p_25_36) == 0) ? 0 : (round((($rowSiguiente->e_25_36) / ($rowSiguiente->p_25_36)) * 100, 0));
					$arr_total[] = (($rowSiguiente->p_37_48) == 0) ? 0 : (round((($rowSiguiente->e_37_48) / ($rowSiguiente->p_37_48)) * 100, 0));
					$arr_total[] = (($rowSiguiente->p_49_60) == 0) ? 0 : (round((($rowSiguiente->e_49_60) / ($rowSiguiente->p_49_60)) * 100, 0));
					$arr_total[] = (($rowSiguiente->p_61_72) == 0) ? 0 : (round((($rowSiguiente->e_61_72) / ($rowSiguiente->p_61_72)) * 100, 0));

					$arr_total[] = ($rowSiguiente->e_0_12);
					$arr_total[] = ($rowSiguiente->p_0_12);

					$arr_total[] = ($rowSiguiente->e_13_24);
					$arr_total[] = ($rowSiguiente->p_13_24);

					$arr_total[] = ($rowSiguiente->e_25_36);
					$arr_total[] = ($rowSiguiente->p_25_36);

					$arr_total[] = ($rowSiguiente->e_37_48);
					$arr_total[] = ($rowSiguiente->p_37_48);

					$arr_total[] = ($rowSiguiente->e_49_60);
					$arr_total[] = ($rowSiguiente->p_49_60);

					$arr_total[] = ($rowSiguiente->e_61_72);
					$arr_total[] = ($rowSiguiente->p_61_72);
				}
			}
			if ($filtro == "Autos" || $filtro == "B&C") {
				$contador1 += 2;
				$contador2 += 2;
			} else {
				$rowNextValidar = $datos_familia_vh->row($contador2 + 1);
				if ($rowSiguiente->familia_vh  != $rowNextValidar->familia_vh) {
					$contador1 += 2;
					$contador2 += 2;
				} else {
					$contador1 += 1;
					$contador2 += 1;
				}
			}
		}
		/* $data = (array_chunk($arr_total,6,true));	 */
		$datos = [
			"arr_total" => $arr_total
		];
		echo json_encode($datos);
	}


	/***********************************   Informe Ventas 1 a 1 ************************************************************/
	public function InformeVentas1a1()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Informe');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";


			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}

			/* Obtenemos los datos de la consulta en la base de datos */
			$infAsesores = $this->Informe->getAsesoresVenta1a1();

			$arr_user = array(
				'infAsesores' => $infAsesores,
				'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu
			);
			//abrimos la vista
			$this->load->view("gestionVentas/InformeVentas1a1", $arr_user);
		}
	}

	/**METODOS PARA EL Informe HORARIO MEJORADO*/
	public function Informe_horario()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Informe');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//TRaremos los empleados para el select
			$data_emp = $this->usuarios->getAllUsers();
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'data_emp' => $data_emp);
			//abrimos la vista
			$this->load->view("Informe_hora_laboral/Informe_horario", $arr_user);
		}
	}

	public function load_tabla_reg_ingreso()
	{
		//TRaemos los modelos
		$this->load->model('Informe');
		//TRaemos los parametros por url
		$sede = $this->input->POST('sede');
		$fecha_ini = $this->input->POST('fecha_ini');
		$fecha_fin = $this->input->POST('fecha_fin');
		$nit = $this->input->POST('nit');
		if ($sede == "" && $nit == "") {
			$data_tabla = $this->Informe->get_Informe_horario($fecha_ini, $fecha_fin);
		} elseif ($nit == "" && $sede != "") {
			$data_tabla = $this->Informe->get_Informe_horario($fecha_ini, $fecha_fin, $sede);
		} elseif ($sede != "" && $nit != "") {
			$data_tabla = $this->Informe->get_Informe_horario($fecha_ini, $fecha_fin, $sede, $nit);
		}
		foreach ($data_tabla->result() as $key) {
			echo '
				<tr align="center">
					<td>' . $key->empleado . '</td>
					<td>' . $key->nombres . '</td>
					<td>' . $key->Sede . '</td>
					<td>' . $key->Dia . '</td>
					<td>' . $key->fecha . '</td>
					<td>' . $key->horario_entrada_am . '</td>
					<td>' . $key->horario_salida_am . '</td>
					<td>' . $key->horario_entrada_pm . '</td>
					<td>' . $key->horario_salida_pm . '</td>
					<td>' . $key->inicio_ausentismo . '</td>
					<td>' . $key->fin_ausentismo . '</td>
					<td>' . $key->llegada_am . '</td>
					<td>' . $key->salida_am . '</td>
					<td>' . $key->llegada_pm . '</td>
					<td>' . $key->salida_pm . '</td>
					<td>' . $key->dif_entrada_am . '</td>
					<td>' . $key->dif_salida_am . '</td>
					<td>' . $key->dif_entrada_pm . '</td>
					<td>' . $key->dif_salida_pm . '</td>
				</tr>
			';
		}
	}

	/***********************************   Informe Ventas 1 a 1 ************************************************************/
	public function cargarInformeVentas()
	{

		$this->load->model('Informe');

		$asesorC = $this->input->POST('asesor');
		$year = $this->input->POST('year');

		if ($asesorC == "") {
			$asesor = "";
		} else {
			$asesor = "and dl.vendedor = $asesorC";
		}

		$dataInforme = $this->Informe->infVentas1a1($year, $asesor);

		echo '
	<thead align="center" class="thead-light">

		<tr>
			<th class="titulo colun1 fijar" scope="col">#</th>
			<th class="titulo colun1 fijar" scope="col">Año</th>
			<th class="titulo colun1 fijar" scope="col">Nit Asesor</th>
			<th class="titulo colun1 fijar" scope="col">Asesor</th>
			<th class="titulo colun1 fijar" scope="col">Mano de obra</th>
			<th class="titulo colun1 fijar" scope="col">Venta de repuestos</th>
			<th class="titulo colun1 fijar" scope="col">Costo de repuestos</th>
			<th class="titulo colun1 fijar" scope="col">Utilidad</th>
			<th class="titulo colun1 fijar" scope="col">Porcentaje</th>

		</tr>

	</thead>
	<tbody align="center">
	';
		$contador = 1;
		foreach ($dataInforme->result() as $key) {
			$asesor = "and dl.vendedor = " . $key->nit_asesor;
			$dataPorcentaje = $this->Informe->infVentas1a1Porcentaje($key->año, $asesor);

			echo '
									<tr>
									<td>' . $contador . '</td>
									<td>' . $key->año . '</td>
									<td>' . $key->nit_asesor . '</td>
									<td>' . $key->asesor . '</td>
									<td>' . number_format($key->Venta_mano_obra, 0, ",", ".") . '</td>
									<td>' . number_format($key->venta_rptos, 0, ",", ".") . '</td>
									<td>' . number_format($key->costo_rptos, 0, ",", ".") . '</td>
									<td>' . number_format(round(($key->venta_rptos) - ($key->costo_rptos)), 0, ",", ".") . '</td>
									<td> <button type="button" tabindex="0" class="btn btn-sm btn-warning" role="button" data-toggle="popover" data-trigger="focus" title="" data-content="Entradas: ' . $dataPorcentaje->entradas . ' / Ventas: ' . $dataPorcentaje->ventas . '">' . round(($dataPorcentaje->entradas / $dataPorcentaje->ventas) * 100, 2) . '%</button ></td>
									</tr>
									';

			$contador++;
		}
		echo '</tbody>
	</table>
	</div>';
	}

	/* Cargar Informe de PQR sin importar el estado */
	public function mostrarPqrCerrados()
	{

		$cerrado = 'Abierto';
		$this->load->model('encuestas');
		$cerrado = "'Cerrado'";
		$gm_enc = $this->encuestas->get_encuestas_gm($cerrado);
		$pqr_cod = $this->encuestas->get_pqr_codiesel($cerrado);
		$pqr_intt = $this->encuestas->get_encuestas_codi($cerrado);
		//nuevo
		$pqr_qr = $this->encuestas->get_encuesta_qr($cerrado);


		echo '<thead align="center" class="thead-light">
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
			';
		foreach ($gm_enc->result() as $key) {
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



			echo '
			<tr style="' . $colorExiware . '">
				<td class="fijar colun1" style="' . $colorPrioritario, $resuelt . '"><a id="' . $key->id_encuesta . '" href="#" class="btn btn-warning btn-sm ' . $deshabilitar . '" onclick="modal_comentarios(\'GM\',' . $key->id_encuesta . ');"><i class="fas fa-edit"></i></a></td>
				<td class="fijar colun2" style="' . $colorPrioritario, $resuelt . '">GM</td>
				<td class="fitwidth fijar colun3" style="' . $colorPrioritario, $resuelt . '">' . $key->id_encuesta . '</td>
				<td class="fitwidth">' . $resulsede . '</td>
				<td class="fitwidth font-weight-bold text-dark" data-toggle="popover" title="Este PQR tiene ' . $dia . ' Dias sin haber sido Gestionado" style="' . $colorPrioritario, $resuelt . '"> ' . $key->fecha_evento . '</td>
				<td class="fitwidth">' . $key->placa . '</td>
				<td class="fitwidth">' . $key->nom_cliente . '</td>
				<td class="fitwidth">' . $key->modelo_vh . '</td>
				<td class="fitwidth">' . $key->uetd_numero . '</td>
				<td class="fitwidth">' . $key->mail . '</td>
				<td class="fitwidth">' . $key->celular . '</td>
				<td class="fitwidth">' . $key->recomendacion_concesionario . '</td>
				<td class="fitwidth">' . $key->satisfaccion_concesionario . '</td>
				<td class="fitwidth">' . $key->satisfaccion_trabajo . '</td>
				<td class="fitwidth">' . $key->vh_reparado_ok . '</td>
				<td class="fitwidth">' . $key->recomendacion_marca . '</td>
				<td class="fitwidth">
					<i class="d-none">' . $key->comentarios . '</i><a class="btn btn-outline-info" onclick="comentario(\'' . $comentario . '\');"><i class="fas fa-comments text-primary"></i></a>
				</td>
				<td class="fitwidth">' . $key->nom_tecnico . '</td>
				<td class="fitwidth">' . $tipificacion . '</td>
				<td class="fitwidth">
					<table style="display: none;" class="">
						<!-- <tbody id="SinComentario-' . $key->id_encuesta . '">
							<tr class="noExl">
								<td style="display: none;" class="noExl">Contacto</td>
								<td style="display: none;" class="noExl">Comentarios</td>
								<td style="display: none;" class="noExl">Fecha</td>
							</tr>
						</tbody> -->
						<tbody id="cuerpoTabla-' . $key->id_encuesta . '">
						</tbody>
					</table>
					<script>
						setTimeout(function() {
							open_list_verb_Comentarios(' . $key->id_encuesta . ');
						}, 2000);
					</script>
					<a href="#" class="btn btn-outline-info mr-3 ' . $deshabilitarVerb . '" onclick="open_form_verb(' . $key->id_encuesta . ');">
						<i class="fas fa-edit"></i>
					</a>
					<a href="#" class="btn btn-outline-info" onclick="open_list_verb(' . $key->id_encuesta . ');">
						<i class="fas fa-eye"></i>
					</a>
				</td>
				<td class="fitwidth">' . $estado_caso . '</td>
				<td class="fitwidth "><i class="d-none">' . $cometarioFinal . '</i><a class="' . $valor . '" onclick="comentariofinal(\'' . $cometarioFinal . '\');"><i class="fas fa-comments"></i></a></td>
				<td class="fitwidth">' . $tipificacion_de_cierre . '</td>

			</tr>';
		}
		foreach ($pqr_cod->result() as $key) {
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



			echo '
			<tr style="' . $colorExiware . '">
				<td class="fijar colun1" style="' . $colorPrioritario . $resuelt . '"><a id="' . $key->id_pqr . '" href="#" class="btn btn-warning btn-sm ' . $deshabilitar . '" onclick="modal_comentarios(\'' . $key->fuente . '\',' . $key->id_pqr . ');"><i class="fas fa-edit"></i></a></td>
				<td class="fitwidth fijar colun2" style="' . $colorPrioritario . $resuelt . '"> ' . $key->fuente . '</td>
				<td class="fitwidth fijar colun3" style="' . $colorPrioritario . $resuelt . '">' . $key->id_pqr . '</td>
				<td class="fitwidth">' . $key->sede . '</td>
				<td class="fitwidth  font-weight-bold text-dark " data-toggle="popover" title="Este PQR tiene ' . $dia . ' Dias sin haber sido Gestionado" style="' . $colorPrioritario, $resuelt . '">' . $key->fecha . ' </td>
				<td class="fitwidth">' . $key->placa . '</td>
				<td class="fitwidth">' . $key->cliente . '</td>
				<td class="fitwidth">' . $key->modelo_vh . '</td>
				<td class="fitwidth">' . $key->ot . '</td>
				<td class="fitwidth">' . $key->mail . '</td>
				<td class="fitwidth">' . $key->telef . '</td>
				<td class="fitwidth">-</td>
				<td class="fitwidth">-</td>
				<td class="fitwidth">-</td>
				<td class="fitwidth">-</td>
				<td class="fitwidth">-</td>
				<td class="fitwidth">
					<i class="d-none">' . $key->comentarios . '</i><a class="btn btn-outline-info" onclick="comentario(\'' . $comentario . '\');"><i class="fas fa-comments text-primary"></i></a>
				</td>
				<td class="fitwidth">' . $key->tecnico . '</td>
				<td class="fitwidth">' . $tipificacion . '</td>
				<td class="fitwidth">
					<table style="display: none;" class="">
						<tbody id="cuerpoTabla-' . $key->id_pqr . '">
						</tbody>
					</table>
					<script>
						setTimeout(function() {
							open_list_verb_Comentarios(' . $key->id_pqr . ');
						}, 2000);
					</script>
					<a href="#" class="btn btn-outline-info mr-3 ' . $deshabilitarVerb . '" onclick="open_form_verb(' . $key->id_pqr . ');"><i class="fas fa-edit"></i></a><a href="#" class="btn btn-outline-info" onclick="open_list_verb(' . $key->id_pqr . ');"><i class="fas fa-eye"></i></a>
				</td>
				<td class="fitwidth">' . $estado_caso . '</td>
				<td class="fitwidth "><i class="d-none">' . $cometarioFinal . '</i><a class="' . $valor . '" onclick="comentariofinal(\'' . $cometarioFinal . '\');"><i class="fas fa-comments"></i></a></td>
				<td class="fitwidth">' . $tipificacion_de_cierre . '</td>

			</tr>';
		}

		foreach ($pqr_intt->result() as $key) {
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




			echo '
			
			<tr style="' . $colorExiware . '">
				<td class="fijar colun1" style="' . $colorPrioritario, $resuelt . '"><a href="#" class="btn btn-warning btn-sm ' . $deshabilitar . '" onclick="modal_comentarios(\'NPS Interno\',' . $key->id . ');"><i class="fas fa-edit"></i></a></td>
				<td class="fitwidth fijar colun2" style="' . $colorPrioritario, $resuelt . '">NPS Interno</td>
				<td class="fitwidth fijar colun3" style="' . $colorPrioritario, $resuelt . '">' . $key->id . '</td>
				<td class="fitwidth">' . $key->descripcion . '</td>
				<td class="fitwidth font-weight-bold text-dark" data-toggle="popover" title="Este PQR tiene ' . $dia . ' Dias sin haber sido Gestionado" style="' . $colorPrioritario, $resuelt . '"> ' . $key->fecha . '</td>
				<td class="fitwidth">' . $key->placa . '</td>
				<td class="fitwidth">' . $key->cliente . '</td>
				<td class="fitwidth">' . $key->modelo_vh . '</td>
				<td class="fitwidth">' . $key->n_orden . '</td>
				<td class="fitwidth">' . $key->mail . '</td>
				<td class="fitwidth">' . $key->telef . '</td>
				<td class="fitwidth">' . $key->pregunta1 . '</td>
				<td class="fitwidth">' . $key->pregunta2 . '</td>
				<td class="fitwidth">' . $key->pregunta3 . '</td>
				<td class="fitwidth">' . $key->pregunta4 . '</td>
				<td class="fitwidth">-</td>
				<td class="fitwidth"><i class="d-none">' . $key->pregunta5 . '</i><a class="btn btn-outline-info" onclick="comentario(\'' . $key->pregunta5 . '\');"><i class="fas fa-comments text-primary"></i></a></td>
				<td class="fitwidth">' . $key->tecnico . '</td>
				<td class="fitwidth">' . $tipificacion  . '</td>
				<td class="fitwidth">

					<table style="display: none;" class="">
						<tbody id="cuerpoTabla-' . $key->id . '">
						</tbody>
					</table>
					<script>
						setTimeout(function() {
							open_list_verb_Comentarios(' . $key->id . ');
						}, 2000);
					</script>

					<a href="#" class="btn btn-outline-info mr-3  ' . $deshabilitarVerb . '" onclick="open_form_verb(' . $key->id . ');">
						<i class="fas fa-edit"></i>
					</a>
					<a href="#" class="btn btn-outline-info" onclick="open_list_verb(' . $key->id . ');">
						<i class="fas fa-eye"></i>

					</a>

				</td>
				<td class="fitwidth">' . $estado_caso  . '</td>
				<td class="fitwidth "><i class="d-none">' . $cometarioFinal . '</i><a class="' . $valor . '" onclick="comentariofinal(\'' . $cometarioFinal . '\');"><i class="fas fa-comments"></i></a></td>
				<td class="fitwidth">' . $tipificacion_de_cierre . '</td>
			</tr>';
		}


		foreach ($pqr_qr->result() as $key) {
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


			echo '
			<tr style="' . $colorExiware . '">
				<td class="fijar colun1" style="' . $colorPrioritario . $resuelt . '"><a href="#" class="btn btn-warning btn-sm ' . $deshabilitar . '" onclick="modal_comentarios(\'NPS Interno\',' . $key->id . ');"><i class="fas fa-edit"></i></a></td>
				<td class="fitwidth fijar colun2" style="' . $colorPrioritario . $resuelt . '">QR</td>
				<td class="fitwidth fijar colun3" style="' . $colorPrioritario . $resuelt . '">' . $key->id . '</td>
				<td class="fitwidth">' . $key->descripcion . '</td>
				<td class="fitwidth font-weight-bold text-dark" data-toggle="popover" title="Este PQR tiene ' . $dia . ' Dias sin haber sido Gestionado" style="' . $colorPrioritario, $resuelt . '"> ' . $key->fecha . '</td>
				<td class="fitwidth">' . $key->placa . '</td>
				<td class="fitwidth">' . $key->cliente . '</td>
				<td class="fitwidth">' . $key->modelo_vh . '</td>
				<td class="fitwidth">' . $key->numero . '</td>
				<td class="fitwidth">' . $key->mail . '</td>
				<td class="fitwidth">' . $key->telef . '</td>
				<td class="fitwidth">' . $key->pregunta1 . '</td>
				<td class="fitwidth">' . $key->pregunta2 . '</td>
				<td class="fitwidth">' . $key->pregunta3 . '</td>
				<td class="fitwidth">' . $key->pregunta4 . '</td>
				<td class="fitwidth">-</td>
				<td class="fitwidth"><i class="d-none">' . $key->pregunta5 . '</i><a class="btn btn-outline-info" onclick="comentario(\'' . $key->pregunta5 . '\');"><i class="fas fa-comments text-primary"></i></a></td>
				<td class="fitwidth">' . $key->tecnico . '</td>
				<td class="fitwidth">' . $tipificacion . '</td>
				<td class="fitwidth">
					<table style="display: none;" class="">
						<tbody id="cuerpoTabla-' . $key->id . '">
						</tbody>
					</table>
					<script>
						setTimeout(function() {
							open_list_verb_Comentarios(' . $key->id . ');
						}, 2000);
					</script>
					<a href="#" class="btn btn-outline-info mr-3  ' . $deshabilitarVerb . '" onclick="open_form_verb(' . $key->id . ');"><i class="fas fa-edit"></i></a><a href="#" class="btn btn-outline-info" onclick="open_list_verb(' . $key->id . ');"><i class="fas fa-eye"></i></a>
				</td>
				<td class="fitwidth">' . $estado_caso . '</td>
				<td class="fitwidth "><i class="d-none">' . $cometarioFinal . '</i><a class="' . $valor . '" onclick="comentariofinal(\'' . $cometarioFinal . '\');"><i class="fas fa-comments"></i></a></td>
				<td class="fitwidth">' . $tipificacion_de_cierre . '</td>
			</tr>';
		}
	}

	public function validar_detalle_nps_int_tecnicos_mes()
	{
		$this->load->model('Informe');
		$fecha = $this->input->GET('fecha');
		$sede = $this->input->GET('sede');



		$bod_giron = "1,9,11,21";
		$bod_rosita = "7";
		$bod_barranca = "6,19";
		$bod_bocono = "8,14,16,22";

		switch ($sede) {
			case 'giron':
				$nps_int_gen = $this->Informe->get_data_nps_interno_sedes_filtro_mes($bod_giron, $fecha);
				$isEmpty = empty($nps_int_gen->result());
				if ($isEmpty) {
					echo "ok";
				} else {
					echo "error";
				}

				break;
			case 'rosita':
				$nps_int_gen = $this->Informe->get_data_nps_interno_sedes_filtro_mes($bod_rosita, $fecha);

				$isEmpty = empty($nps_int_gen->result());
				if ($isEmpty) {
					echo "ok";
				} else {
					echo "error";
				}

				break;
			case 'barranca':
				$nps_int_gen = $this->Informe->get_data_nps_interno_sedes_filtro_mes($bod_barranca, $fecha);

				$isEmpty = empty($nps_int_gen->result());
				if ($isEmpty) {
					echo "ok";
				} else {
					echo "error";
				}

				break;
			case 'bocono':
				$nps_int_gen = $this->Informe->get_data_nps_interno_sedes_filtro_mes($bod_bocono, $fecha);

				$isEmpty = empty($nps_int_gen->result());
				if ($isEmpty) {
					echo "ok";
				} else {
					echo "error";
				}

				break;
		}
	}

	public function detalle_nps_int_tecnicos_mes()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('presupuesto');
			$this->load->model('nominas');
			$this->load->model('Informe');
			$sede = $this->input->GET('sede');
			$fecha = $this->input->GET('fecha');/* Mes o fecha del filtro */
			//obtenemos primer y ultimo dia del mes actual
			$fecha_ini = $this->nominas->obtener_primer_dia_mes();
			$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			/*CALCULO DE NPS INTERNO POR TECNICO*/
			$bod_giron = "1,9,11,21";
			$bod_rosita = "7";
			$bod_barranca = "6,19";
			$bod_bocono = "8,14,16,22";
			//$data[] = null;
			switch ($sede) {
				case 'giron':
					$nps_int_gen = $this->Informe->get_data_nps_interno_sedes_filtro_mes($bod_giron, $fecha);

					$isEmpty = empty($nps_int_gen->result());
					if ($isEmpty) {
					} else {
						foreach ($nps_int_gen->result() as $key) {
							$data[] = array('nombres' => $key->tecnico, 'enc06' => $key->enc0a6, 'enc78' => $key->enc7a8, 'enc910' => $key->enc9a10);
						}
						$nps_int = 0;
						$enc0a6 = 0;
						$enc7a8 = 0;
						$enc9a10 = 0;
						$to_enc = 0;
						foreach ($nps_int_gen->result() as $key) {
							$enc0a6 += $key->enc0a6;
							$enc7a8 += $key->enc7a8;
							$enc9a10 += $key->enc9a10;
							$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
						}
						if ($enc0a6 != 0 || $enc7a8 != 0 || $enc9a10 != 0) {
							$porcen_int_06 = ($enc0a6 * 100) / $to_enc;
							$porcen_int_78 = ($enc7a8 * 100) / $to_enc;
							$porcen_int_910 = ($enc9a10 * 100) / $to_enc;
							$nps_int = ((($enc9a10 - $enc0a6) / $to_enc) * 100);
						} else {
							$porcen_int_06 = 0;
							$porcen_int_78 = 0;
							$porcen_int_910 = 0;
							$nps_int = 0;
						}
						$data_nps_int[] = array('nps' => $nps_int, 'enc06' => $enc0a6, 'enc78' => $enc7a8, 'enc910' => $enc9a10, 'to_enc' => $to_enc, "porcen_06" => $porcen_int_06, "porcen_78" => $porcen_int_78, "porcen_910" => $porcen_int_910, "sede" => $sede);
					}

					break;
				case 'rosita':
					$nps_int_gen = $this->Informe->get_data_nps_interno_sedes_filtro_mes($bod_rosita, $fecha);
					foreach ($nps_int_gen->result() as $key) {
						$data[] = array('nombres' => $key->tecnico, 'enc06' => $key->enc0a6, 'enc78' => $key->enc7a8, 'enc910' => $key->enc9a10);
					}
					$nps_int = 0;
					$enc0a6 = 0;
					$enc7a8 = 0;
					$enc9a10 = 0;

					$to_enc = 0;
					foreach ($nps_int_gen->result() as $key) {
						$enc0a6 += $key->enc0a6;
						$enc7a8 += $key->enc7a8;
						$enc9a10 += $key->enc9a10;
						$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
					}


					if ($enc0a6 != 0 || $enc7a8 != 0 || $enc9a10 != 0) {
						$porcen_int_06 = ($enc0a6 * 100) / $to_enc;
						$porcen_int_78 = ($enc7a8 * 100) / $to_enc;
						$porcen_int_910 = ($enc9a10 * 100) / $to_enc;
						$nps_int = ((($enc9a10 - $enc0a6) / $to_enc) * 100);
					} else {
						$porcen_int_06 = 0;
						$porcen_int_78 = 0;
						$porcen_int_910 = 0;
						$nps_int = 0;
					}

					$data_nps_int[] = array('nps' => $nps_int, 'enc06' => $enc0a6, 'enc78' => $enc7a8, 'enc910' => $enc9a10, 'to_enc' => $to_enc, "porcen_06" => $porcen_int_06, "porcen_78" => $porcen_int_78, "porcen_910" => $porcen_int_910, "sede" => $sede);
					break;
				case 'barranca':
					$nps_int_gen = $this->Informe->get_data_nps_interno_sedes_filtro_mes($bod_barranca, $fecha);
					foreach ($nps_int_gen->result() as $key) {
						$data[] = array('nombres' => $key->tecnico, 'enc06' => $key->enc0a6, 'enc78' => $key->enc7a8, 'enc910' => $key->enc9a10);
					}
					$nps_int = 0;
					$enc0a6 = 0;
					$enc7a8 = 0;
					$enc9a10 = 0;

					$to_enc = 0;
					foreach ($nps_int_gen->result() as $key) {
						$enc0a6 += $key->enc0a6;
						$enc7a8 += $key->enc7a8;
						$enc9a10 += $key->enc9a10;
						$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
					}


					if ($enc0a6 != 0 || $enc7a8 != 0 || $enc9a10 != 0) {
						$porcen_int_06 = ($enc0a6 * 100) / $to_enc;
						$porcen_int_78 = ($enc7a8 * 100) / $to_enc;
						$porcen_int_910 = ($enc9a10 * 100) / $to_enc;
						$nps_int = ((($enc9a10 - $enc0a6) / $to_enc) * 100);
					} else {
						$porcen_int_06 = 0;
						$porcen_int_78 = 0;
						$porcen_int_910 = 0;
						$nps_int = 0;
					}

					$data_nps_int[] = array('nps' => $nps_int, 'enc06' => $enc0a6, 'enc78' => $enc7a8, 'enc910' => $enc9a10, 'to_enc' => $to_enc, "porcen_06" => $porcen_int_06, "porcen_78" => $porcen_int_78, "porcen_910" => $porcen_int_910, "sede" => $sede);
					break;
				case 'bocono':
					$nps_int_gen = $this->Informe->get_data_nps_interno_sedes_filtro_mes($bod_bocono, $fecha);
					foreach ($nps_int_gen->result() as $key) {
						$data[] = array('nombres' => $key->tecnico, 'enc06' => $key->enc0a6, 'enc78' => $key->enc7a8, 'enc910' => $key->enc9a10);
					}
					$nps_int = 0;
					$enc0a6 = 0;
					$enc7a8 = 0;
					$enc9a10 = 0;

					$to_enc = 0;
					foreach ($nps_int_gen->result() as $key) {
						$enc0a6 += $key->enc0a6;
						$enc7a8 += $key->enc7a8;
						$enc9a10 += $key->enc9a10;
						$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
					}


					if ($enc0a6 != 0 || $enc7a8 != 0 || $enc9a10 != 0) {
						$porcen_int_06 = ($enc0a6 * 100) / $to_enc;
						$porcen_int_78 = ($enc7a8 * 100) / $to_enc;
						$porcen_int_910 = ($enc9a10 * 100) / $to_enc;
						$nps_int = ((($enc9a10 - $enc0a6) / $to_enc) * 100);
					} else {
						$porcen_int_06 = 0;
						$porcen_int_78 = 0;
						$porcen_int_910 = 0;
						$nps_int = 0;
					}

					$data_nps_int[] = array('nps' => $nps_int, 'enc06' => $enc0a6, 'enc78' => $enc7a8, 'enc910' => $enc9a10, 'to_enc' => $to_enc, "porcen_06" => $porcen_int_06, "porcen_78" => $porcen_int_78, "porcen_910" => $porcen_int_910, "sede" => $sede);
					break;
			}

			//ARRAY DE LA INFO DE LA VISTA
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'data_tabla' => $data, 'data_nps_int' => $data_nps_int, 'sede' => $sede, 'data_tec' => $nps_int_gen, 'fecha' => $fecha);
			//abrimos la vista
			$this->load->view("detalle_nps_int_tec", $arr_user);
		}
	}

	public function validar_Informe_pac_detalle_tecnico_byFecha()
	{

		//llamamos los modelos necesarios
		$this->load->model('Informe');
		//PARAMETROS POR URL
		$fecha = $this->input->GET('fecha');
		$sede = $this->input->GET('sede');
		/*DATA TECNICOS*/
		/* 	$tecnicos = $this->Informe->get_data_tecnicos_by_dia($sede, $fecha); */
		/*NPS SEDE*/
		$dat_sede = $this->Informe->get_calificacion_sede_by_dia($sede, $fecha);


		$isEmpty = empty($dat_sede->result());
		if ($isEmpty) {
			echo "ok";
		} else {
			echo "error";
		}
	}
	public function cargarInformeVentasTotal()
	{
		$this->load->model('Informe');

		$Year = date("Y");
		$asesor = "";

		$dataInforme = $this->Informe->infVentas1a1($Year, $asesor);

		echo '
		<div class="table-responsive">
						<span><a href="#" class="btn btn-success" onclick="bajar_excel();"><i class="fas fa-plus-squares"></i>Descargar Excel </a></span>
						<table class="table table-hover table-bordered" align="center" id="tabla_data" style="font-size: 14px;">
		<thead align="center" class="thead-light">

			<tr>
				<th class="titulo colun1 fijar" scope="col">#</th>
				<th class="titulo colun1 fijar" scope="col">Año</th>
				<th class="titulo colun1 fijar" scope="col">Nit Asesor</th>
				<th class="titulo colun1 fijar" scope="col">Asesor</th>
				<th class="titulo colun1 fijar" scope="col">Mano de obra</th>
				<th class="titulo colun1 fijar" scope="col">Venta de repuestos</th>
				<th class="titulo colun1 fijar" scope="col">Costo de repuestos</th>
				<th class="titulo colun1 fijar" scope="col">Utilidad</th>
				<th class="titulo colun1 fijar" scope="col">Porcentaje</th>

			</tr>

		</thead>
		<tbody align="center">
		';
		$contador = 1;
		foreach ($dataInforme->result() as $key) {
			$asesor = "and dl.vendedor = " . $key->nit_asesor;
			$dataPorcentaje = $this->Informe->infVentas1a1Porcentaje($key->año, $asesor);

			echo '
										<tr>
										<td>' . $contador . '</td>
										<td>' . $key->año . '</td>
										<td>' . $key->nit_asesor . '</td>
										<td>' . $key->asesor . '</td>
										<td>' . number_format($key->Venta_mano_obra, 0, ",", ".") . '</td>
										<td>' . number_format($key->venta_rptos, 0, ",", ".") . '</td>
										<td>' . number_format($key->costo_rptos, 0, ",", ".") . '</td>
										<td>' . number_format(round(($key->venta_rptos) - ($key->costo_rptos)), 0, ",", ".") . '</td>
										<td> <button type="button" tabindex="0" class="btn btn-sm btn-warning" role="button" data-toggle="popover" data-trigger="focus" title="" data-content="Entradas: ' . $dataPorcentaje->entradas . ' / Ventas: ' . $dataPorcentaje->ventas . '">' . round(($dataPorcentaje->entradas / $dataPorcentaje->ventas) * 100, 2) . '%</button ></td>
										
										
										</tr>
										';

			$contador++;
		}
		echo '</tbody>
		</table>
		</div>';
	}

	public function getVehiculos12meses()
	{
		$this->load->model('Informe');

		$data = $this->Informe->getVehiculos12Meses();

		if (empty($data->result())) {
			echo 'error';
		} else {
			echo '
		<div class="table-responsive">
		<table class="table table-hover table-bordered" align="center" id="tablaExcel" style="font-size: 14px;">
		<thead align="center" class="thead-light">

			<tr>
				<th class="titulo colun1 fijar" scope="col">#</th>
				<th class="titulo colun1 fijar" scope="col">PLACA</th>
				<th class="titulo colun1 fijar" scope="col">SERIE</th>
				<th class="titulo colun1 fijar" scope="col">CODIGO</th>
				<th class="titulo colun1 fijar" scope="col">FAMILIA</th>
				<th class="titulo colun1 fijar" scope="col">TIPO VEHÍCULO</th>
				<th class="titulo colun1 fijar" scope="col">CUMPLE RETENCIÓN</th>
			</tr>

		</thead>
		<tbody align="center">
		';
			$contador = 1;
			foreach ($data->result() as $key) {
				echo '
					<tr>
						<td>' . $contador . '</td>
						<td>' . $key->placa . '</td>
						<td>' . $key->serie . '</td>
						<td>' . $key->codigo . '</td>
						<td>' . $key->familia . '</td>
						<td>' . $key->tipo_vh . '</td>
						<td>' . $key->cumple_retencion . '</td>									
					</tr>
					';
				$contador++;
			}
			echo '</tbody>
		</table>
		</div>';
		}
	}
	/* ************************************************ */
	public function getVehiculosYearActual()
	{
		$this->load->model('Informe');

		$data = $this->Informe->getVehiculosYearActual();


		if (empty($data->result())) {
			echo 'error';
		} else {
			echo '
					<div class="table-responsive">
					<table class="table table-hover table-bordered" align="center" id="tablaExcel" style="font-size: 14px;">
						<thead align="center" class="thead-light">

							<tr>
								<th class="titulo colun1 fijar" scope="col">#</th>
								<th class="titulo colun1 fijar" scope="col">PLACA</th>
								<th class="titulo colun1 fijar" scope="col">SERIE</th>
								<th class="titulo colun1 fijar" scope="col">CODIGO</th>
								<th class="titulo colun1 fijar" scope="col">FAMILIA</th>
								<th class="titulo colun1 fijar" scope="col">TIPO VEHÍCULO</th>
								<th class="titulo colun1 fijar" scope="col">CUMPLE RETENCIÓN</th>
							</tr>

						</thead>
						<tbody align="center">
							';
			$contador = 1;
			foreach ($data->result() as $key) {
				echo '
								<tr>
									<td>' . $contador . '</td>
									<td>' . $key->placa . '</td>
									<td>' . $key->serie . '</td>
									<td>' . $key->codigo . '</td>
									<td>' . $key->familia . '</td>
									<td>' . $key->tipo_vh . '</td>
									<td>' . $key->cumple_retencion . '</td>									
								</tr>
								';
				$contador++;
			}
			echo '</tbody>
			</table>
		</div>';
		}
	}
	/*
	* Autor: Sergio Galvis
	*Fecha: 05/08/2022 
	*/

	/* Informe PAC NPS Interno Detallado */
	public function PacNpsInternoDetallado()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Informe');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			foreach ($userinfo->result() as $nombre) {
				$nomb = $nombre->nombres;
			}
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			/* Información a mostrar en la vista */

			$arr_user = array(
				'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'nomb' => $nomb
			);
			//abrimos la vista
			$this->load->view("Informe_nps/InformeNPSDetallado", $arr_user);
		}
	}
	public function PacNpsInternoDetalladoCargar()
	{
		$this->load->model('Informe');
		$fecha = $this->input->POST('fecha');

		$infoDate = explode('-',$fecha);
		$año = $infoDate[0];
		$mes= $infoDate[1];

		$bodegas = $this->Informe->getAllBodegas();

		$cant_ord_fin =  $this->Informe->OrdenFinalizadasMes($año,$mes);
		$cantOrden_fin = 0;
		foreach ($cant_ord_fin->result() as $key) {
			$cantOrden_fin += $key->ord_fin;
		}

		$numEncuestas = $this->Informe->numsEncuestas($año,$mes);

		$htmlTabla =
			'
			<table class="table table-bordered">
				<thead>';

		foreach ($bodegas->result() as $key) {
			$htmlTabla .= '<th scope="col"> ' . $key->descripcion . ' </th>';
		}

		$htmlTabla .=	'</thead>
				<tbody>
					<tr>';

		foreach ($cant_ord_fin->result() as $key) {
			if ($key->bodega != 22) {
				$htmlTabla .= '<td class="text-center" ><button class="btn btn-success" Onclick="verEncuestasBodega(' . $key->bodega . ',' . $key->ord_fin . ')">' . $key->ord_fin . ' / ' . $key->total_encuestas . ' </button></td>';
			}
		}


		$htmlTabla .= '</tr>

				</tbody>
			</table>
			';

		$arr_user = array(
			'htmlTabla' => $htmlTabla,
			'cantOrdenes' => $cantOrden_fin,
			'cantEncuestas' => $numEncuestas->num_encuestas,
		);
		//abrimos la vista
		echo json_encode($arr_user);
	}
	public function verEncuestasBodega()
	{
		$this->load->model('Informe');
		$bodega = $this->input->POST('bodega');
		$fecha = $this->input->POST('fecha');
		$infoDate = explode('-',$fecha);
		$año = $infoDate[0];
		$mes= $infoDate[1];

		$nps_int_gen = $this->Informe->get_data_nps_interno_sedesFecha($bodega,$año,$mes);
		echo '<div class="card-body table-responsive p-0">
		<table class="table table-striped table-valign-middle">
			<thead align="center">
				<tr>
					<th>Tecnico</th>
					<th>No Encuestas 0 a 6</th>
					<th>No Encuestas 7 a 8</th>
					<th>No Encuestas 9 a 10</th>
					<th>Ver detalle</th>
				</tr>
			</thead>
			<tbody id="detalle_nps_gral" align="center">';
		foreach ($nps_int_gen->result() as $key) {

			echo '					<tr align="center">
										<td>' . $key->tecnico . '</td>
										<td>' . $key->enc0a6 . '</td>
										<td>' . $key->enc7a8 . '</td>
										<td>' . $key->enc9a10 . '</td>
										<td><button class="btn btn-warning" Onclick="verEncuestas(\'' . $key->tecnico . '\');">Ver</button></td>
									</tr>';
		}
		echo '	</tbody>
				</table>
			</div>';
	}

	public function detalle_encuesta_tecnico()
	{
		$this->load->model('encuestas');
		$nombre = $this->input->POST('nombre');
		$fecha = $this->input->POST('fecha');
		$infoDate = explode('-',$fecha);
		$año = $infoDate[0];
		$mes= $infoDate[1];

		$data_respuestas = $this->encuestas->Informe_encuesta_satisfaccion_by_nomFecha($nombre,$año,$mes);
		if (!empty($data_respuestas->result())) {
			echo '<div class="card">	
				  <div class="card-body">
				  	<div class="row">';
		foreach ($data_respuestas->result() as $data) {
			$estadop1 = "";
			$estadop2 = "";
			$estadop3 = "";
			$estadop4 = "";
			$estadop5 = "bg-primary";
			if ($data->pregunta1 >= 9) {
				$estadop1 = "bg-success";
			} elseif ($data->pregunta1 == 8) {
				$estadop1 = "bg-warning";
			} elseif ($data->pregunta1 == 7) {
				$estadop1 = "bg-warning";
			} elseif ($data->pregunta1 <= 6) {
				$estadop1 = "bg-danger";
			}

			if ($data->pregunta2 >= 9) {
				$estadop2 = "bg-success";
			} elseif ($data->pregunta2 == 8) {
				$estadop2 = "bg-warning";
			} elseif ($data->pregunta2 == 7) {
				$estadop2 = "bg-warning";
			} elseif ($data->pregunta2 <= 6) {
				$estadop2 = "bg-danger";
			}

			if ($data->pregunta3 == 'SI') {
				$estadop3 = "bg-success";
			} elseif ($data->pregunta3 == 'NO') {
				$estadop3 = "bg-danger";
			}

			if ($data->pregunta4 == 'SI') {
				$estadop4 = "bg-success";
			} elseif ($data->pregunta4 == 'NO') {
				$estadop4 = "bg-danger";
			}

			echo '
						<div class="col-md-4" style="font-size: 20px;">
							<strong>Cliente: ' . $data->nombres . '</strong>
						</div>
						<div class="col-md-4" style="font-size: 20px;">
							<span>Numero de Orden: ' . $data->numero . '</span>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-3">
								<div class="card text-white ' . $estadop1 . ' mb-3" style="max-width: 18rem;">
								  <div class="card-header">Satisfaccion con el concesionario (Mantenimiento Reparacion)</div>
								  <div class="card-body" align="center">
								    <h1>' . $data->pregunta1 . '</h1>
								  </div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="card text-white ' . $estadop2 . ' mb-3" style="max-width: 18rem;">
								  <div class="card-header">Satisfaccion con el trabajo realizado</div>
								  <div class="card-body" align="center">
								    <h1>' . $data->pregunta2 . '</h1>
								  </div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="card text-white ' . $estadop3 . ' mb-3" style="max-width: 18rem;">
								  <div class="card-header">Explicacion todo el trabajo realizado</div>
								  <div class="card-body" align="center">
								    <h1>' . $data->pregunta3 . '</h1>
								  </div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="card text-white ' . $estadop4 . ' mb-3" style="max-width: 18rem;">
								  <div class="card-header">Se cumplieron los compromisos pactados (Tiempo Porceso)</div>
								  <div class="card-body" align="center">
								    <h1>' . $data->pregunta4 . '</h1>
								  </div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="card text-white ' . $estadop5 . '">
							  <div class="card-header">Para nosotros es importante conocer tu opinion</div>
							  <div class="card-body" align="center">
							    <h1>' . $data->pregunta5 . '</h1>
							  </div>
							</div>
						</div>';
		}
		echo '</div>
					</div>
					</div>';
		} else {
			echo 'No se encontro información.';
		}
		
	}
}
