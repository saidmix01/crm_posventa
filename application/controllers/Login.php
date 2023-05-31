<?php

/**
 * 
 */
class Login extends CI_Controller
{
	/*Redirije al login*/
	public function index()
	{
		//va a la vista login
		$this->load->view('login');
	}
	/*Metodo que se encarga de validar si las credenciales del usuario son correctas
	 * y de ser asi crear la session
	 */
	public function iniciar()
	{
		//llamamos los modelos
		$this->load->model('usuarios');
		$this->load->model('menus');
		$this->load->model('perfiles');
		$this->load->model('presupuesto');
		$this->load->model('Informe');
		$this->load->model('nominas');
		$this->load->library('encrypt');
		$this->load->model('Contac_Center');
		$this->load->model('mantenimiento_uno'); //Home MTO->Sergio Galvis->20/04/2022
		$this->load->model('sedes'); //Home MTO->Sergio Galvis->20/04/2022


		$this->load->model('ausentismos'); // Sergio Galvis 06/08/2022


		/* 06/08/2022 Script para Pausas activas
		Autor: Sergio Galvis XD */
		/* Obtenemos la fecha y hora del servidor para validar, a la hora de crear un ausentismo */
		$fecha_actual = $this->ausentismos->getFecha();
		/* Valida si la fecha actual es festivo y devuelve 1 si es festivo... */
		$boolDiaEsFestivo = $this->ausentismos->diasFestivos($fecha_actual->fecha_actual);


		//validarmos si hay datos de session activos
		if (!$this->session->userdata('login')) {
			//se obtienen los datos de las cajas de texto
			$usu = $this->input->post('usu');
			$pass = $this->input->post('pass');


			//se llama la funcion del modelo para validar el usuario
			$datauser = $this->usuarios->validar_usu($usu);
			$pass_desencript = $this->input->post('op') != 	NULL ? $datauser->pass : $this->encrypt->decode($datauser->pass);
			//validamos si no llega vacia la consulta
			if ($datauser != null) {
				//validamos si esta activo
				if ($datauser->estado == 1) {
					//comparamos los datos ingresados con los que hay en la base de datos
					if ($pass_desencript == $pass) {
						$perfil = $this->usuarios->obtenerPerfil($usu);
						/* Informe JEFES DE TALLEER */
						if ($perfil->id_perfil == 33) {
							$this->load->model('usuarios');
							$this->load->model('menus');
							$this->load->model('perfiles');
							$this->load->model('Informe');
							$this->load->model('talleres');
							$this->load->model('encuestas');
							$this->load->model('sedes');

							$data = array('user' => $usu, 'perfil' => $perfil->id_perfil, 'id_user' => $datauser->id_usuario, 'nit_user' => $datauser->nit_usuario, 'login' => true);
							//enviamos los datos de session al navegador
							$this->session->set_userdata($data);
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
							/*OBTENER BODEGAS DEL USUARIO*/
							$data_sedes = $this->sedes->get_sedes_user($usu);
							$sedes_usu = "";


							foreach ($data_sedes->result() as $key) {
								$sedes_usu .= $key->idsede . ",";
							}

							$date = $this->Informe->get_mes_ano_actual();
							$mes = $date->mes;
							$ano = $date->ano;
							$sedes_usu = trim($sedes_usu, ",");
							//TOTAL VENDIDO
							$to_rep = 0;
							$to_mo = 0;
							$to_tot = 0;
							$total_v = 0;
							$total_horas = 0;
							$data_ventas_vendedor = $this->talleres->get_ventas_bod($sedes_usu, $mes, $ano);
							//print_r($data_ventas_vendedor);die;
							foreach ($data_ventas_vendedor->result() as $key) {
								$to_mo = $key->MO;
								$to_rep = $key->rptos;
								$to_tot = $key->TOT;
								$total_v = $key->rptos + $key->MO + $key->TOT;
								$total_horas = $key->horas_facturadas;
							}
							//NPS interno
							$data_nps_tec = $this->Informe->get_data_nps_interno_sedes_mes($sedes_usu);
							$nps_int = 0;
							$to_enc = 0;
							foreach ($data_nps_tec->result() as $key) {
								$to_enc = $key->enc9a10 + $key->enc0a6 + $key->enc7a8;
								$nps_int = (($key->enc9a10 - $key->enc0a6) / $to_enc) * 100;
							}
							//CALIFICACION COLMOTORES
							$sede = "";
							switch ($sedes_usu) {
								case '1':
									$sede = "giron";
									break;
								case '6':
									$sede = "barranca";
									break;
								case '8,14,16,22':
									$sede = "bocono";
									break;
								case '8':
									$sede = "bocono";
									break;
								case '7':
									$sede = "rosita";
									break;

								default:
									// code...
									break;
							}
							$data_nps = $this->Informe->get_calificacion_sede($sede);
							$nps = 0;
							foreach ($data_nps->result() as $key) {
								$total_encu = $key->Enc_0_a_6 + $key->Enc_7_a_8 + $key->Enc_9_a_10;
								$nps = (($key->Enc_9_a_10 - $key->Enc_0_a_6) / $total_encu) * 100;
							}
							/*detalle bodega*/
							$data_bodegas = $this->talleres->get_ventas_bod_detalle($sedes_usu, $mes, $ano);
							$img_url = ($perfil_user->url_img_user_postv) != '' ? $perfil_user->url_img_user_postv : '';
							$arr_user = array(
								'dia_actual' => $boolDiaEsFestivo, 'fecha_actual' => $fecha_actual->fecha_actual,
								'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass,
								'id_usu' => $id_usu, 'nps_int' => $nps_int, 'total_ventas' => $total_v, 'nps_col' => $nps,
								'horas_fac' => $total_horas, 'mo' => $to_mo, 'rep' => $to_rep, 'data_bodegas' => $data_bodegas,
								'tot' => $to_tot, 'bod' => $sedes_usu, 'img_user' => $img_url
							);
							//abrimos la vista
							$this->load->view("admin", $arr_user);
						}
						/* FIN Informe JEFES DE TALLEER */
						/* INICIO Informe TECNICOS*/ elseif ($perfil->id_perfil == 24) {
							$this->load->model('usuarios');
							$this->load->model('menus');
							$this->load->model('perfiles');
							$this->load->model('Informe');
							$this->load->model('talleres');
							$this->load->model('encuestas');
							$this->load->model('sedes');

							$date = $this->Informe->get_mes_ano_actual();
							$mes = $date->mes;
							$ano = $date->ano;
							$data = array('user' => $usu, 'perfil' => $perfil->id_perfil, 'id_user' => $datauser->id_usuario, 'nit_user' => $datauser->nit_usuario, 'login' => true);
							//enviamos los datos de session al navegador
							$this->session->set_userdata($data);
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
							$data_sedes = $this->sedes->get_sedes_user($usu);
							$sedes_usu = "";
							foreach ($data_sedes->result() as $key) {
								$sedes_usu .= $key->idsede . ",";
							}
							$sedes_usu = trim($sedes_usu, ", ");

							//TOTAL VENDIDO
							$to_rep = 0;
							$to_mo = 0;
							$total_v = 0;
							$total_horas = 0;
							$data_ventas_vendedor = $this->talleres->get_ventas_tec($usu, $mes, $ano);
							foreach ($data_ventas_vendedor->result() as $key) {
								$to_mo = $key->MO;
								$to_rep = $key->rptos;
								$total_v = $key->rptos + $key->MO;
								$total_horas = $key->horas_facturadas;
							}
							//NPS interno
							$data_nps_tec = $this->Informe->get_nps_by_tec($usu, $mes, $ano);
							$nps_int = 0;
							$to_enc = 0;
							foreach ($data_nps_tec->result() as $key) {
								$to_enc = $key->enc9a10 + $key->enc0a6 + $key->enc7a8;
								$nps_int = (($key->enc9a10 - $key->enc0a6) / $to_enc) * 100;
							}
							//CALIFICACION COLMOTORES
							$tecnicos = $this->Informe->get_data_nps_by_tec($usu, $mes, $ano);
							$nps = 0;
							foreach ($tecnicos->result() as $key) {
								$total_encu = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
								$nps = (($key->enc9a10 - $key->enc0a6) / $total_encu) * 100;
							}
							/*CALCULO DEL RANKING TALLER*/
							$data_ranking_ventas = $this->talleres->get_ranking_ventas($sedes_usu);
							$data_ranking_nps = $this->talleres->get_ranking_nps($sedes_usu);
							$rankig_taller_ventas = 0;
							$rankig_taller_nps = 0;
							foreach ($data_ranking_ventas->result() as $key) {
								if ($key->tecnico == $usu) {
									$rankig_taller_ventas = $key->ranking;
								}
							}
							foreach ($data_ranking_nps->result() as $key) {
								if ($key->tecnico == $usu) {
									$rankig_taller_nps = $key->ranking;
								}
							}
							$data_rankig_talleres = array('ran_vendido' => $rankig_taller_ventas, 'ran_nps' => $rankig_taller_nps);
							/*CALCULO DEL RANKING AREA*/

							$sede = "";
							$rankig_taller_ventas_sede = 0;
							$rankig_taller_nps_sede = 0;
							$data_ran_tec_to_vend = null;
							$tope_ran_pres = 0;
							switch ($sedes_usu) {
								case '1':
									//RANKING TO VENDIDO REP + MO
									$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking($sedes_usu, $mes, $ano);
									$tope_ran_pres = 20000000;
									$sede = "GASOLINA";
									break;
								case '7':
									$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking($sedes_usu, $mes, $ano);
									$tope_ran_pres = 24000000;
									$sede = "GASOLINA";
									break;
								case '6,19':
									$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking("6,19", $mes, $ano);
									$tope_ran_pres = 28000000;
									$sede = "GASOLINA";
									break;
								case '18':
									$sede = "GASOLINA";
									break;
								case '19,6':
									$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking("6,19", $mes, $ano);
									$tope_ran_pres = 28000000;
									$sede = "DIESEL";
									break;
								case '11':
									$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking($sedes_usu, $mes, $ano);
									$tope_ran_pres = 30000000;
									$sede = "DIESEL";
									break;
								case '16':
									$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking("8,16", $mes, $ano);
									$tope_ran_pres = 30000000;
									$sede = "DIESEL";
									break;
								case '8':
									$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking("8,16", $mes, $ano);
									$tope_ran_pres = 30000000;
									$sede = "GASOLINA";
								case '16,8':
									$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking("8,16", $mes, $ano);
									$tope_ran_pres = 30000000;
									$sede = "GASOLINA";
								case '8,16':
									$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking("8,16", $mes, $ano);
									$tope_ran_pres = 30000000;
									$sede = "GASOLINA";
									break;
								case '21,9':
									$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking($sedes_usu, $mes, $ano);
									$tope_ran_pres = 15000000;
									$sede = "LYP";
									break;
								case '9,21':
									$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking($sedes_usu, $mes, $ano);
									$tope_ran_pres = 15000000;
									$sede = "LYP";
									break;
								case '14,22':
									$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking($sedes_usu, $mes, $ano);
									$tope_ran_pres = 15000000;
									$sede = "LYP";
									break;
								default:
									$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking($sedes_usu, $mes, $ano);
									$tope_ran_pres = 15000000;
									$sede = "";
									break;
							}
							//echo $sede;die;
							if ($sede == "GASOLINA") {
								$data_ranking_ventas_sede = $this->talleres->get_ranking_ventas("1,7,6,18,19");
								$data_ranking_nps_sede = $this->talleres->get_ranking_nps("1,7,6,18,19");
								foreach ($data_ranking_ventas_sede->result() as $key) {
									if ($key->tecnico == $usu) {
										$rankig_taller_ventas_sede = $key->ranking;
									}
								}
								foreach ($data_ranking_nps_sede->result() as $key) {
									if ($key->tecnico == $usu) {
										$rankig_taller_nps_sede = $key->ranking;
									}
								}
								//print_r($data_ranking_ventas_sede->result());die;
							} elseif ($sede == "DIESEL") {
								$data_ranking_ventas_sede = $this->talleres->get_ranking_ventas("11,16");
								$data_ranking_nps_sede = $this->talleres->get_ranking_nps("11,16");
								foreach ($data_ranking_ventas_sede->result() as $key) {
									if ($key->tecnico == $usu) {
										$rankig_taller_ventas_sede = $key->ranking;
									}
								}
								foreach ($data_ranking_nps_sede->result() as $key) {
									if ($key->tecnico == $usu) {
										$rankig_taller_nps_sede = $key->ranking;
									}
								}
							} elseif ($sede == "LYP") {
								$data_ranking_ventas_sede = $this->talleres->get_ranking_ventas("9,14,21,22");
								$data_ranking_nps_sede = $this->talleres->get_ranking_nps("9,14,21,22");
								foreach ($data_ranking_ventas_sede->result() as $key) {
									if ($key->tecnico == $usu) {
										$rankig_taller_ventas_sede = $key->ranking;
									}
								}
								foreach ($data_ranking_nps_sede->result() as $key) {
									if ($key->tecnico == $usu) {
										$rankig_taller_nps_sede = $key->ranking;
									}
								}
								//echo "Ok"; die;
							}
							$fecha_actual = $this->ausentismos->getFecha();
							/* Valida si la fecha actual es festivo y devuelve 1 si es festivo... */
							$boolDiaEsFestivo = $this->ausentismos->diasFestivos($fecha_actual->fecha_actual);

							$data_rankig_sedes = array('ran_vendido' => $rankig_taller_ventas_sede, 'ran_nps' => $rankig_taller_nps_sede);
							//print_r($data_rankig_sedes);die;
							$img_url = ($perfil_user->url_img_user_postv) != '' ? $perfil_user->url_img_user_postv : '';
							$arr_user = array(
								'dia_actual' => $boolDiaEsFestivo, 'fecha_actual' => $fecha_actual->fecha_actual, 'img_user' => $img_url,
								'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'nps_int' => $nps_int, 'total_ventas' => $total_v, 'nps_col' => $nps, 'horas_fac' => $total_horas, 'mo' => $to_mo, 'rep' => $to_rep, 'bod_usu' => $sedes_usu, 'ranking_talleres' => $data_rankig_talleres, 'ranking_sedes' => $data_rankig_sedes, 'ranking_presupuesto' => $data_ran_tec_to_vend, 'tope_ran_pres' => $tope_ran_pres
							);
							//abrimos la vista
							$this->load->view("Informe_tecnicos", $arr_user);
							/* F I N    I N F O R M E    T E C N C O S*/
						} else {
							//echo "<script>alert(".$perfil->id_perfil.");</script>";
							//guardamos en un arreglo los datos de session
							$data = array('user' => $usu, 'perfil' => $perfil->id_perfil, 'id_user' => $datauser->id_usuario, 'nit_user' => $datauser->nit_usuario, 'login' => true);
							//enviamos los datos de session al navegador
							$this->session->set_userdata($data);
							//obtenemos el perfil del usuario
							$perfil_user = $this->perfiles->getPerfilByUser($usu);
							//consultamos el perfil del usuario
							//$perfil_user = $this->perfiles->getPerfilByUser($usu);
							//cargamos la informacion del usuario y los perfiles y la pasamos a un array
							$userinfo = $this->usuarios->getUserByName($usu);
							$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
							$allperfiles = $this->perfiles->getAllPerfiles();
							$id_usu = "";
							foreach ($userinfo->result() as $key) {
								$id_usu = $key->id_usuario;
							}

							/*ESTADO AGENTES*/
							$data_estado = $this->Contac_Center->get_estado($usu);
							/*InformeS*/


							$graf_sedes = $this->Informe->Informe_presupuesto_by_sedes();
							/*CENTRO DE COSTOS*/
							$centros_costos_giron = "4,40,33,45,3";
							$centros_costos_rosita = "16,17";
							$centros_costos_baranca = "13,70,11";
							$centros_costos_bocono = "29,80,31,46,28";
							$centros_costos_chevro = "15";
							$centros_costos_soloch = "60";
							//obtenemos primer y ultimo dia del mes actual
							$fecha_ini = $this->nominas->obtener_primer_dia_mes();
							$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
							/*PRESUPUESTO SEDES*/
							$presupuesto_principal = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL PRINCIPAL");
							$presupuesto_bocono = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL VILLA DEL ROSARIO");
							$presupuesto_rosita = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL LA ROSITA");
							$presupuesto_barranca = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL BARRANCABERMEJA");
							$presupuesto_solochevr = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "SOLOCHEVROLET MOSTRADOR");
							$presupuesto_chevrop = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CHEVROPARTES MOSTRADOR");
							/*PRESUPUESTO A DIA DE HOY*/
							$prin = $this->presupuesto->get_presupuesto_dia($centros_costos_giron);
							$boc = $this->presupuesto->get_presupuesto_dia($centros_costos_bocono);
							$ros = $this->presupuesto->get_presupuesto_dia($centros_costos_rosita);
							$barran = $this->presupuesto->get_presupuesto_dia($centros_costos_baranca);
							$solochevr = $this->presupuesto->get_presupuesto_dia($centros_costos_soloch);
							$chevrp = $this->presupuesto->get_presupuesto_dia($centros_costos_chevro);
							/*********************************** calcular porcentaje ideal giron********************************************/
							$to_dias_mes = $this->Informe->get_total_dias();
							$to_dias_hoy = $this->Informe->get_dias_actual();
							$n_to_dias = $to_dias_mes->ultimo_dia;
							$n_dias_hoy = $to_dias_hoy->dia;

							//LO QUEBEMOS LLEGAR A DIA DE HOY
							$to_objetivo_giron = ($presupuesto_principal->presupuesto / $n_to_dias) * $n_dias_hoy;
							//PORCENTAJE IDEAL DEL MES
							$porcentaje_objetivo_giron = ($prin->total / $presupuesto_principal->presupuesto) * 100;
							$porcentaje_objetivo_restante_giron = (100 - $porcentaje_objetivo_giron);
							//PORCENTAJE IDEAL DEL DIA
							$to_objetivo_dia_giron = ($prin->total / $to_objetivo_giron) * 100;
							$porcentaje_restante_dia_giron = 100 - $to_objetivo_dia_giron;
							if ($porcentaje_restante_dia_giron < 0) {
								$porcentaje_restante_dia_giron = 0;
							}

							/*********************************** calcular porcentaje ideal rosita********************************************/
							$to_dias_mes = $this->Informe->get_total_dias();
							$to_dias_hoy = $this->Informe->get_dias_actual();
							$n_to_dias = $to_dias_mes->ultimo_dia;
							$n_dias_hoy = $to_dias_hoy->dia;
							//PROCENTAJE IDEAL MES
							$to_objetivo_rosita = ($presupuesto_rosita->presupuesto / $n_to_dias) * $n_dias_hoy;
							$porcentaje_objetivo_rosita = ($ros->total / $presupuesto_rosita->presupuesto) * 100;
							$porcentaje_objetivo_restante_rosita = (100 - $porcentaje_objetivo_rosita);
							//PORCENTAJE IDEAL DEL DIA
							$to_objetivo_dia_rosita = ($ros->total / $to_objetivo_rosita) * 100;
							$porcentaje_restante_dia_rosita = 100 - $to_objetivo_dia_rosita;
							if ($porcentaje_restante_dia_rosita < 0) {
								$porcentaje_restante_dia_rosita = 0;
							}
							/*********************************** calcular porcentaje ideal bocono********************************************/
							$to_dias_mes = $this->Informe->get_total_dias();
							$to_dias_hoy = $this->Informe->get_dias_actual();
							$n_to_dias = $to_dias_mes->ultimo_dia;
							$n_dias_hoy = $to_dias_hoy->dia;
							//PROCENTAJE IDEAL DE MES
							$to_objetivo_bocono = ($presupuesto_bocono->presupuesto / $n_to_dias) * $n_dias_hoy;
							$porcentaje_objetivo_bocono = ($boc->total / $presupuesto_bocono->presupuesto) * 100;
							$porcentaje_objetivo_restante_bocono = (100 - $porcentaje_objetivo_bocono);
							//PORCENTAJE IDEAL DEL DIA
							$to_objetivo_dia_bocono = ($boc->total / $to_objetivo_bocono) * 100;
							$porcentaje_restante_dia_bocono = 100 - $to_objetivo_dia_bocono;
							if ($porcentaje_restante_dia_bocono < 0) {
								$porcentaje_restante_dia_bocono = 0;
							}
							/*********************************** calcular porcentaje ideal barranca********************************************/
							$to_dias_mes = $this->Informe->get_total_dias();
							$to_dias_hoy = $this->Informe->get_dias_actual();
							$n_to_dias = $to_dias_mes->ultimo_dia;
							$n_dias_hoy = $to_dias_hoy->dia;
							//PORCENTAJE IDEAL MES
							$to_objetivo_barranca = ($presupuesto_barranca->presupuesto / $n_to_dias) * $n_dias_hoy;
							$porcentaje_objetivo_barranca = ($barran->total / $presupuesto_barranca->presupuesto) * 100;
							$porcentaje_objetivo_restante_barranca = (100 - $porcentaje_objetivo_barranca);
							//PORCENTAJE IDEAL DEL DIA
							$to_objetivo_dia_barranca = ($barran->total / $to_objetivo_barranca) * 100;
							$porcentaje_restante_dia_barranca = 100 - $to_objetivo_dia_barranca;
							if ($porcentaje_restante_dia_barranca < 0) {
								$porcentaje_restante_dia_barranca = 0;
							}
							/*********************************** calcular porcentaje ideal solochevrolet*****************************************/
							$to_dias_mes = $this->Informe->get_total_dias();
							$to_dias_hoy = $this->Informe->get_dias_actual();
							$n_to_dias = $to_dias_mes->ultimo_dia;
							$n_dias_hoy = $to_dias_hoy->dia;
							//PORCENTAJE IDEAL MES
							$to_objetivo_solochevr = ($presupuesto_solochevr->presupuesto / $n_to_dias) * $n_dias_hoy;
							$porcentaje_objetivo_solochevr = ($solochevr->total / $presupuesto_solochevr->presupuesto) * 100;
							$porcentaje_objetivo_restante_solochevr = (100 - $porcentaje_objetivo_solochevr);
							//PORCENTAJE IDEAL DEL DIA
							$to_objetivo_dia_solochevr = ($solochevr->total / $to_objetivo_solochevr) * 100;
							$porcentaje_restante_dia_solochevr = 100 - $to_objetivo_dia_solochevr;
							if ($porcentaje_restante_dia_solochevr < 0) {
								$porcentaje_restante_dia_solochevr = 0;
							}
							/*********************************** calcular porcentaje ideal chevropartes******************************************/
							$to_dias_mes = $this->Informe->get_total_dias();
							$to_dias_hoy = $this->Informe->get_dias_actual();
							$n_to_dias = $to_dias_mes->ultimo_dia;
							$n_dias_hoy = $to_dias_hoy->dia;
							//PORCENTAJE IDEAL MES
							$to_objetivo_chevrop = ($presupuesto_chevrop->presupuesto / $n_to_dias) * $n_dias_hoy;
							$porcentaje_objetivo_chevrop = ($chevrp->total / $presupuesto_chevrop->presupuesto) * 100;
							$porcentaje_objetivo_restante_chevrop = (100 - $porcentaje_objetivo_chevrop);
							//PORCENTAJE IDEAL DEL DIA
							$to_objetivo_dia_chevrop = ($chevrp->total / $to_objetivo_chevrop) * 100;
							$porcentaje_restante_dia_chevrop = 100 - $to_objetivo_dia_chevrop;
							if ($porcentaje_restante_dia_chevrop < 0) {
								$porcentaje_restante_dia_chevrop = 0;
							}
							/*TOTAL VENDIDO POSVENTA*/
							/*CENTROS DE COSTOS*/
							$centros_costos = "4,40,33,45,3,16,17,13,70,11,29,80,31,46,28,60,15";
							$to_presupuesto_dia = $this->presupuesto->get_presupuesto_dia($centros_costos);
							$to_presupuesto_dia = $to_presupuesto_dia->total;
							/*Calificacion PAC*/
							$calificacion_pac = $this->Informe->get_calificacion_sede("general");
							/*TOTAL INVENTARIO*/
							$inventario = $this->Informe->Informe_inventario();
							$val_to_inv = 0;
							foreach ($inventario->result() as $key) {
								$val_to_inv += ($key->Promedio * $key->stock);
							}
							/*CALCULO NPS INTERNO GENERAL*/
							$bod = "1,9,11,21,7,6,19,8,14,16,22";
							$nps_int = 0;
							$enc0a6 = 0;
							$enc7a8 = 0;
							$enc9a10 = 0;
							$nps_int_gen = $this->Informe->get_data_nps_interno_sedes($bod);
							foreach ($nps_int_gen->result() as $key) {
								$enc0a6 += $key->enc0a6;
								$enc7a8 += $key->enc7a8;
								$enc9a10 += $key->enc9a10;
								$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
								$nps_int = ((($enc9a10 - $enc0a6) / $to_enc) * 100);
							}
							/* Informe de solicitudes de mantenimiento para el perfil de MTO */
							/* Bodegas enlazadas con el encargado de mantenimiento */
							$pendientes = "";
							$proceso = "";
							$finalizadas = "";
							$pendientesPre = "";
							$procesoPre = "";
							$finalizadasPre = "";
							if ($this->session->userdata('perfil') == 46) {
								$data_sedes = $this->sedes->get_sedes_user($usu);
								$sedes_usu = "";
								foreach ($data_sedes->result() as $key) {
									$sedes_usu .= $key->idsede . ",";
								}

								$sedes_usu = trim($sedes_usu, ",");
								/* print_r($sedes_usu);die; */
								$mto_pendiente = $this->mantenimiento_uno->s_pendientes($sedes_usu);
								$pendientes = $mto_pendiente->pendientes;
								$mto_proceso = $this->mantenimiento_uno->s_proceso($sedes_usu);
								$proceso = $mto_proceso->proceso;
								$mto_finalizada = $this->mantenimiento_uno->s_finalizadas($sedes_usu);
								$finalizadas = $mto_finalizada->finalizada;
							}
							if ($this->session->userdata('perfil') == 46) {

								$mto_pendientePre = $this->mantenimiento_uno->s_pendientesPre();
								$pendientesPre = $mto_pendientePre->pendientes;

								$mto_procesPre = $this->mantenimiento_uno->s_procesoPre();
								$procesoPre = $mto_procesPre->proceso;

								$mto_finalizadasPre = $this->mantenimiento_uno->s_finalizadasPre();
								$finalizadasPre = $mto_finalizadasPre->finalizada;
							}


							/********************************************************************************************************************************************/
							$img_url = ($perfil_user->url_img_user_postv) != '' ? $perfil_user->url_img_user_postv : '';
							$arr_user = array(
								'dia_actual' => $boolDiaEsFestivo, 'fecha_actual' => $fecha_actual->fecha_actual,
								'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles,
								'pass' => $pass, 'id_usu' => $id_usu, 'graf_sedes' => $graf_sedes, 'porcen_giron' => $porcentaje_objetivo_giron,
								'porcen_rosita' => $porcentaje_objetivo_rosita, 'porcen_barranca' => $porcentaje_objetivo_barranca,
								'porcen_bocono' => $porcentaje_objetivo_bocono, 'porcen_soloc' => $porcentaje_objetivo_solochevr,
								'porcen_chev' => $porcentaje_objetivo_chevrop, 'to_posv' => $to_presupuesto_dia, 'cal_pac' => $calificacion_pac,
								'to_inv' => $val_to_inv, 'nps_int' => $nps_int, 'pendientes' => $pendientes, 'proceso' => $proceso, 'finalizadas' => $finalizadas,
								'data_estado' => $data_estado, 'pendientesPre' => $pendientesPre, 'procesoPre' => $procesoPre, 'finalizadasPre' => $finalizadasPre,
								'img_user' => $img_url
							);
							//reseteamos los intentos
							/* print_r($arr_user);die; */
							$intentos = $this->usuarios->obtenerIntentos($usu);
							$data = array('intentos' => 0);
							$this->usuarios->updateIntentos($intentos->id_usuario, $data);
							//abrimos la vista
							$this->load->view('admin', $arr_user);
						}
					} else {
						//se destruye la sessions
						$this->session->sess_destroy();
						//se aumenta el contador de intentos
						$intentos = $this->usuarios->obtenerIntentos($usu);
						$n_intentos = $intentos->num_intentos;
						if ($n_intentos != 3) {
							$flag = $n_intentos + 1;
							$data = array('intentos' => $flag);
							$this->usuarios->updateIntentos($intentos->id_usuario, $data);
						} elseif ($n_intentos == 3) {
							$this->usuarios->desactivarUser($intentos->id_usuario);
						}

						header("Location: " . base_url() . "Login?log=pass_err");
					}
				} else {
					$this->session->sess_destroy();
					header("Location: " . base_url() . "Login?log=err");
				}
			} else {
				//se destruye la session
				$this->session->sess_destroy();
				header("Location: " . base_url() . "Login?log=non_user");
			}
		} else {
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			$perfil = $this->session->userdata('perfil');

			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			/* Informe JEFES DE TALLEER */
			if ($perfil == 33) {
				$this->load->model('usuarios');
				$this->load->model('menus');
				$this->load->model('perfiles');
				$this->load->model('Informe');
				$this->load->model('talleres');
				$this->load->model('encuestas');
				$this->load->model('sedes');

				$date = $this->Informe->get_mes_ano_actual();
				$mes = $date->mes;
				$ano = $date->ano;

				//$data = array('user' => $usu, 'perfil' => $perfil_user->id_perfil, 'login' =>true);
				//enviamos los datos de session al navegador
				//$this->session->set_userdata($data);
				//obtenemos el perfil del usuario
				//$perfil_user = $this->perfiles->getPerfilByUser($usu);
				//cargamos la informacion del usuario y la pasamos a un array
				$userinfo = $this->usuarios->getUserByName($usu);
				$allmenus = $this->menus->getMenusByPerfil($perfil);
				//$allsubmenus = $this->menus->getAllSubmenus();
				$allperfiles = $this->perfiles->getAllPerfiles();
				$id_usu = "";
				foreach ($userinfo->result() as $key) {
					$id_usu = $key->id_usuario;
				}
				/*OBTENER BODEGAS DEL USUARIO*/
				$data_sedes = $this->sedes->get_sedes_user($usu);
				$sedes_usu = "";
				foreach ($data_sedes->result() as $key) {
					$sedes_usu .= $key->idsede . ",";
				}
				$sedes_usu = trim($sedes_usu, ", ");
				//TOTAL VENDIDO
				$to_rep = 0;
				$to_mo = 0;
				$to_tot = 0;
				$total_v = 0;
				$total_horas = 0;
				$data_ventas_vendedor = $this->talleres->get_ventas_bod($sedes_usu, $mes, $ano);
				//print_r($data_ventas_vendedor);die;
				foreach ($data_ventas_vendedor->result() as $key) {
					$to_mo = $key->MO;
					$to_rep = $key->rptos;
					$to_tot = $key->TOT;
					$total_v = $key->rptos + $key->MO + $key->TOT;
					$total_horas = $key->horas_facturadas;
				}
				//NPS interno
				$data_nps_tec = $this->Informe->get_data_nps_interno_sedes_mes($sedes_usu);
				$nps_int = 0;
				$to_enc = 0;
				foreach ($data_nps_tec->result() as $key) {
					$to_enc = $key->enc9a10 + $key->enc0a6 + $key->enc7a8;
					$nps_int = (($key->enc9a10 - $key->enc0a6) / $to_enc) * 100;
				}
				//CALIFICACION COLMOTORES
				$sede = "";
				switch ($sedes_usu) {
					case '1':
						$sede = "giron";
						break;
					case '6':
						$sede = "barranca";
						break;
					case '8,14,16,22':
						$sede = "bocono";
						break;
					case '8':
						$sede = "bocono";
						break;
					case '7':
						$sede = "rosita";
						break;

					default:
						// code...
						break;
				}
				$data_nps = $this->Informe->get_calificacion_sede($sede);
				$nps = 0;
				foreach ($data_nps->result() as $key) {
					$total_encu = $key->Enc_0_a_6 + $key->Enc_7_a_8 + $key->Enc_9_a_10;
					$nps = (($key->Enc_9_a_10 - $key->Enc_0_a_6) / $total_encu) * 100;
				}
				/*detalle bodega*/
				$data_bodegas = $this->talleres->get_ventas_bod_detalle($sedes_usu, $mes, $ano);
				$img_url = ($perfil_user->url_img_user_postv) != '' ? $perfil_user->url_img_user_postv : '';
				$arr_user = array(
					'dia_actual' => $boolDiaEsFestivo, 'fecha_actual' => $fecha_actual->fecha_actual, 'img_user' => $img_url,
					'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'nps_int' => $nps_int, 'total_ventas' => $total_v, 'nps_col' => $nps, 'horas_fac' => $total_horas, 'mo' => $to_mo, 'rep' => $to_rep, 'data_bodegas' => $data_bodegas, 'tot' => $to_tot, 'bod' => $sedes_usu
				);
				//abrimos la vista
				$this->load->view("admin", $arr_user);
			}
			/* FIN Informe JEFES DE TALLEER */
			/* INICIO Informe TECNICOS */ elseif ($perfil == 24) {
				$this->load->model('usuarios');
				$this->load->model('menus');
				$this->load->model('perfiles');
				$this->load->model('Informe');
				$this->load->model('talleres');
				$this->load->model('encuestas');
				$this->load->model('sedes');
				//obtenemos el perfil del usuario
				//$perfil_user = $this->perfiles->getPerfilByUser($usu);
				//cargamos la informacion del usuario y la pasamos a un array
				$userinfo = $this->usuarios->getUserByName($usu);
				$allmenus = $this->menus->getMenusByPerfil($perfil);
				//$allsubmenus = $this->menus->getAllSubmenus();
				$allperfiles = $this->perfiles->getAllPerfiles();
				$id_usu = "";
				foreach ($userinfo->result() as $key) {
					$id_usu = $key->id_usuario;
				}
				$data_sedes = $this->sedes->get_sedes_user($usu);
				$sedes_usu = "";
				foreach ($data_sedes->result() as $key) {
					$sedes_usu .= $key->idsede . ",";
				}
				$sedes_usu = trim($sedes_usu, ", ");
				$date = $this->Informe->get_mes_ano_actual();
				$mes = $date->mes;
				$ano = $date->ano;
				//TOTAL VENDIDO
				$to_rep = 0;
				$to_mo = 0;
				$to_tot = 0;
				$total_v = 0;
				$total_horas = 0;
				$data_ventas_vendedor = $this->talleres->get_ventas_tec($usu, $mes, $ano);
				foreach ($data_ventas_vendedor->result() as $key) {
					$to_mo = $key->MO;
					$to_rep = $key->rptos;
					$total_v = $key->rptos + $key->MO;
					$total_horas = $key->horas_facturadas;
				}
				//NPS interno
				$data_nps_tec = $this->Informe->get_nps_by_tec($usu, $mes, $ano);
				$nps_int = 0;
				$to_enc = 0;
				foreach ($data_nps_tec->result() as $key) {
					$to_enc = $key->enc9a10 + $key->enc0a6 + $key->enc7a8;
					$nps_int = (($key->enc9a10 - $key->enc0a6) / $to_enc) * 100;
				}
				//CALIFICACION COLMOTORES
				$tecnicos = $this->Informe->get_data_nps_by_tec($usu, $mes, $ano);
				$nps = 0;
				foreach ($tecnicos->result() as $key) {
					$total_encu = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
					$nps = (($key->enc9a10 - $key->enc0a6) / $total_encu) * 100;
				}
				/*CALCULO DEL RANKING TALLER*/
				$data_ranking_ventas = $this->talleres->get_ranking_ventas($sedes_usu);
				$data_ranking_nps = $this->talleres->get_ranking_nps($sedes_usu);
				$rankig_taller_ventas = 0;
				$rankig_taller_nps = 0;
				foreach ($data_ranking_ventas->result() as $key) {
					if ($key->tecnico == $usu) {
						$rankig_taller_ventas = $key->ranking;
					}
				}
				foreach ($data_ranking_nps->result() as $key) {
					if ($key->tecnico == $usu) {
						$rankig_taller_nps = $key->ranking;
					}
				}
				$data_rankig_talleres = array('ran_vendido' => $rankig_taller_ventas, 'ran_nps' => $rankig_taller_nps);
				/*CALCULO DEL RANKING AREA*/

				$sede = "";
				$rankig_taller_ventas_sede = 0;
				$rankig_taller_nps_sede = 0;
				$data_ran_tec_to_vend = null;
				$tope_ran_pres = 0;
				//echo $sedes_usu; die;
				switch ($sedes_usu) {
					case '1':
						//RANKING TO VENDIDO REP + MO
						$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking($sedes_usu, $mes, $ano);
						$tope_ran_pres = 15000000;
						$sede = "GASOLINA";
						break;
					case '7':
						$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking($sedes_usu, $mes, $ano);
						$tope_ran_pres = 15000000;
						$sede = "GASOLINA";
						break;
					case '6,19':
						$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking("6,19", $mes, $ano);
						$tope_ran_pres = 20000000;
						$sede = "GASOLINA";
						break;
					case '18':
						$sede = "GASOLINA";
						break;
					case '19,6':
						$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking("6,19", $mes, $ano);
						$tope_ran_pres = 20000000;
						$sede = "DIESEL";
						break;
					case '11':
						$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking($sedes_usu, $mes, $ano);
						$tope_ran_pres = 24000000;
						$sede = "DIESEL";
						break;
					case '16':
						$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking("8,16", $mes, $ano);
						$tope_ran_pres = 20000000;
						$sede = "DIESEL";
						break;
					case '8':
						$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking("8,16", $mes, $ano);
						$tope_ran_pres = 20000000;
						$sede = "GASOLINA";
					case '16,8':
						$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking("8,16", $mes, $ano);
						$tope_ran_pres = 20000000;
						$sede = "GASOLINA";
					case '8,16':
						$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking("8,16", $mes, $ano);
						$tope_ran_pres = 20000000;
						$sede = "GASOLINA";
						break;
					case '21,9':
						$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking($sedes_usu, $mes, $ano);
						$tope_ran_pres = 15000000;
						$sede = "LYP";
						break;
					case '9,21':
						$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking($sedes_usu, $mes, $ano);
						$tope_ran_pres = 15000000;
						$sede = "LYP";
						break;
					case '14,22':
						$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking($sedes_usu, $mes, $ano);
						$tope_ran_pres = 15000000;
						$sede = "LYP";
						break;
					default:
						$data_ran_tec_to_vend = $this->talleres->get_ventas_tec_ranking($sedes_usu, $mes, $ano);
						$tope_ran_pres = 15000000;
						$sede = "";
						break;
				}
				//echo $sede;die;
				if ($sede == "GASOLINA") {
					$data_ranking_ventas_sede = $this->talleres->get_ranking_ventas("1,7,6,18,19");
					$data_ranking_nps_sede = $this->talleres->get_ranking_nps("1,7,6,18,19");
					foreach ($data_ranking_ventas_sede->result() as $key) {
						if ($key->tecnico == $usu) {
							$rankig_taller_ventas_sede = $key->ranking;
						}
					}
					foreach ($data_ranking_nps_sede->result() as $key) {
						if ($key->tecnico == $usu) {
							$rankig_taller_nps_sede = $key->ranking;
						}
					}
					//print_r($data_ranking_ventas_sede->result());die;
				} elseif ($sede == "DIESEL") {
					$data_ranking_ventas_sede = $this->talleres->get_ranking_ventas("11,16");
					$data_ranking_nps_sede = $this->talleres->get_ranking_nps("11,16");
					foreach ($data_ranking_ventas_sede->result() as $key) {
						if ($key->tecnico == $usu) {
							$rankig_taller_ventas_sede = $key->ranking;
						}
					}
					foreach ($data_ranking_nps_sede->result() as $key) {
						if ($key->tecnico == $usu) {
							$rankig_taller_nps_sede = $key->ranking;
						}
					}
				} elseif ($sede == "LYP") {
					$data_ranking_ventas_sede = $this->talleres->get_ranking_ventas("9,14,21,22");
					$data_ranking_nps_sede = $this->talleres->get_ranking_nps("9,14,21,22");
					foreach ($data_ranking_ventas_sede->result() as $key) {
						if ($key->tecnico == $usu) {
							$rankig_taller_ventas_sede = $key->ranking;
						}
					}
					foreach ($data_ranking_nps_sede->result() as $key) {
						if ($key->tecnico == $usu) {
							$rankig_taller_nps_sede = $key->ranking;
						}
					}
					//echo "Ok"; die;
				}
				$fecha_actual = $this->ausentismos->getFecha();
				/* Valida si la fecha actual es festivo y devuelve 1 si es festivo... */
				$boolDiaEsFestivo = $this->ausentismos->diasFestivos($fecha_actual->fecha_actual);
				//echo $rankig_taller_ventas_sede; die;
				$data_rankig_sedes = array('ran_vendido' => $rankig_taller_ventas_sede, 'ran_nps' => $rankig_taller_nps_sede);
				//print_r($data_rankig_sedes);die;
				$img_url = ($perfil_user->url_img_user_postv) != '' ? $perfil_user->url_img_user_postv : '';
				$arr_user = array('dia_actual' => $boolDiaEsFestivo, 'fecha_actual' => $fecha_actual->fecha_actual, 'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'nps_int' => $nps_int, 'total_ventas' => $total_v, 'nps_col' => $nps, 'horas_fac' => $total_horas, 'mo' => $to_mo, 'rep' => $to_rep, 'bod_usu' => $sedes_usu, 'ranking_talleres' => $data_rankig_talleres, 'ranking_sedes' => $data_rankig_sedes, 'ranking_presupuesto' => $data_ran_tec_to_vend, 'tope_ran_pres' => $tope_ran_pres, 'img_user' => $img_url);
				//abrimos la vista
				$this->load->view("Informe_tecnicos", $arr_user);
			} else {
				if ($usu != "") {
					//cargamos la informacion del usuario y la pasamos a un array
					$userinfo = $this->usuarios->getUserByName($usu);
					$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
					//$allsubmenus = $this->menus->getAllSubmenus();
					$allperfiles = $this->perfiles->getAllPerfiles();
					//prueba de menu

					/*ESTADO AGENTES*/
					$data_estado = $this->Contac_Center->get_estado($usu);
					$id_usu = "";
					foreach ($userinfo->result() as $key) {
						$id_usu = $key->id_usuario;
					}
					//echo $id_usu;
					/*InformeS*/
					$graf_sedes = $this->Informe->Informe_presupuesto_by_sedes();
					/*CENTRO DE COSTOS*/
					$centros_costos_giron = "4,40,33,45,3";
					$centros_costos_rosita = "16,17";
					$centros_costos_baranca = "13,70,11";
					$centros_costos_bocono = "29,80,31,46,28";
					$centros_costos_chevro = "15";
					$centros_costos_soloch = "60";
					//obtenemos primer y ultimo dia del mes actual
					$fecha_ini = $this->nominas->obtener_primer_dia_mes();
					$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
					/*PRESUPUESTO SEDES*/
					$presupuesto_principal = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL PRINCIPAL");
					$presupuesto_bocono = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL VILLA DEL ROSARIO");
					$presupuesto_rosita = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL LA ROSITA");
					$presupuesto_barranca = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL BARRANCABERMEJA");
					$presupuesto_solochevr = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "SOLOCHEVROLET MOSTRADOR");
					$presupuesto_chevrop = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CHEVROPARTES MOSTRADOR");
					/*PRESUPUESTO A DIA DE HOY*/
					$prin = $this->presupuesto->get_presupuesto_dia($centros_costos_giron);
					$boc = $this->presupuesto->get_presupuesto_dia($centros_costos_bocono);
					$ros = $this->presupuesto->get_presupuesto_dia($centros_costos_rosita);
					$barran = $this->presupuesto->get_presupuesto_dia($centros_costos_baranca);
					$solochevr = $this->presupuesto->get_presupuesto_dia($centros_costos_soloch);
					$chevrp = $this->presupuesto->get_presupuesto_dia($centros_costos_chevro);
					/*********************************** calcular porcentaje ideal giron********************************************/
					$to_dias_mes = $this->Informe->get_total_dias();
					$to_dias_hoy = $this->Informe->get_dias_actual();
					$n_to_dias = $to_dias_mes->ultimo_dia;
					$n_dias_hoy = $to_dias_hoy->dia;

					//LO QUEBEMOS LLEGAR A DIA DE HOY
					$to_objetivo_giron = ($presupuesto_principal->presupuesto / $n_to_dias) * $n_dias_hoy;
					//PORCENTAJE IDEAL DEL MES
					$porcentaje_objetivo_giron = ($prin->total / $presupuesto_principal->presupuesto) * 100;
					$porcentaje_objetivo_restante_giron = (100 - $porcentaje_objetivo_giron);
					//PORCENTAJE IDEAL DEL DIA
					$to_objetivo_dia_giron = ($prin->total / $to_objetivo_giron) * 100;
					$porcentaje_restante_dia_giron = 100 - $to_objetivo_dia_giron;
					if ($porcentaje_restante_dia_giron < 0) {
						$porcentaje_restante_dia_giron = 0;
					}

					/*********************************** calcular porcentaje ideal rosita********************************************/
					$to_dias_mes = $this->Informe->get_total_dias();
					$to_dias_hoy = $this->Informe->get_dias_actual();
					$n_to_dias = $to_dias_mes->ultimo_dia;
					$n_dias_hoy = $to_dias_hoy->dia;
					//PROCENTAJE IDEAL MES
					$to_objetivo_rosita = ($presupuesto_rosita->presupuesto / $n_to_dias) * $n_dias_hoy;
					$porcentaje_objetivo_rosita = ($ros->total / $presupuesto_rosita->presupuesto) * 100;
					$porcentaje_objetivo_restante_rosita = (100 - $porcentaje_objetivo_rosita);
					//PORCENTAJE IDEAL DEL DIA
					$to_objetivo_dia_rosita = ($ros->total / $to_objetivo_rosita) * 100;
					$porcentaje_restante_dia_rosita = 100 - $to_objetivo_dia_rosita;
					if ($porcentaje_restante_dia_rosita < 0) {
						$porcentaje_restante_dia_rosita = 0;
					}
					/*********************************** calcular porcentaje ideal bocono********************************************/
					$to_dias_mes = $this->Informe->get_total_dias();
					$to_dias_hoy = $this->Informe->get_dias_actual();
					$n_to_dias = $to_dias_mes->ultimo_dia;
					$n_dias_hoy = $to_dias_hoy->dia;
					//PROCENTAJE IDEAL DE MES
					$to_objetivo_bocono = ($presupuesto_bocono->presupuesto / $n_to_dias) * $n_dias_hoy;
					$porcentaje_objetivo_bocono = ($boc->total / $presupuesto_bocono->presupuesto) * 100;
					$porcentaje_objetivo_restante_bocono = (100 - $porcentaje_objetivo_bocono);
					//PORCENTAJE IDEAL DEL DIA
					$to_objetivo_dia_bocono = ($boc->total / $to_objetivo_bocono) * 100;
					$porcentaje_restante_dia_bocono = 100 - $to_objetivo_dia_bocono;
					if ($porcentaje_restante_dia_bocono < 0) {
						$porcentaje_restante_dia_bocono = 0;
					}
					/*********************************** calcular porcentaje ideal barranca********************************************/
					$to_dias_mes = $this->Informe->get_total_dias();
					$to_dias_hoy = $this->Informe->get_dias_actual();
					$n_to_dias = $to_dias_mes->ultimo_dia;
					$n_dias_hoy = $to_dias_hoy->dia;
					//PORCENTAJE IDEAL MES
					$to_objetivo_barranca = ($presupuesto_barranca->presupuesto / $n_to_dias) * $n_dias_hoy;
					$porcentaje_objetivo_barranca = ($barran->total / $presupuesto_barranca->presupuesto) * 100;
					$porcentaje_objetivo_restante_barranca = (100 - $porcentaje_objetivo_barranca);
					//PORCENTAJE IDEAL DEL DIA
					$to_objetivo_dia_barranca = ($barran->total / $to_objetivo_barranca) * 100;
					$porcentaje_restante_dia_barranca = 100 - $to_objetivo_dia_barranca;
					if ($porcentaje_restante_dia_barranca < 0) {
						$porcentaje_restante_dia_barranca = 0;
					}
					/*********************************** calcular porcentaje ideal solochevrolet*****************************************/
					$to_dias_mes = $this->Informe->get_total_dias();
					$to_dias_hoy = $this->Informe->get_dias_actual();
					$n_to_dias = $to_dias_mes->ultimo_dia;
					$n_dias_hoy = $to_dias_hoy->dia;
					//PORCENTAJE IDEAL MES
					$to_objetivo_solochevr = ($presupuesto_solochevr->presupuesto / $n_to_dias) * $n_dias_hoy;
					$porcentaje_objetivo_solochevr = ($solochevr->total / $presupuesto_solochevr->presupuesto) * 100;
					$porcentaje_objetivo_restante_solochevr = (100 - $porcentaje_objetivo_solochevr);
					//PORCENTAJE IDEAL DEL DIA
					$to_objetivo_dia_solochevr = ($solochevr->total / $to_objetivo_solochevr) * 100;
					$porcentaje_restante_dia_solochevr = 100 - $to_objetivo_dia_solochevr;
					if ($porcentaje_restante_dia_solochevr < 0) {
						$porcentaje_restante_dia_solochevr = 0;
					}
					/*********************************** calcular porcentaje ideal chevropartes******************************************/
					$to_dias_mes = $this->Informe->get_total_dias();
					$to_dias_hoy = $this->Informe->get_dias_actual();
					$n_to_dias = $to_dias_mes->ultimo_dia;
					$n_dias_hoy = $to_dias_hoy->dia;
					//PORCENTAJE IDEAL MES
					$to_objetivo_chevrop = ($presupuesto_chevrop->presupuesto / $n_to_dias) * $n_dias_hoy;
					$porcentaje_objetivo_chevrop = ($chevrp->total / $presupuesto_chevrop->presupuesto) * 100;
					$porcentaje_objetivo_restante_chevrop = (100 - $porcentaje_objetivo_chevrop);
					//PORCENTAJE IDEAL DEL DIA
					$to_objetivo_dia_chevrop = ($chevrp->total / $to_objetivo_chevrop) * 100;
					$porcentaje_restante_dia_chevrop = 100 - $to_objetivo_dia_chevrop;
					if ($porcentaje_restante_dia_chevrop < 0) {
						$porcentaje_restante_dia_chevrop = 0;
					}
					/*TOTAL VENDIDO POSVENTA*/
					/*CENTROS DE COSTOS*/
					$centros_costos = "4,40,33,45,3,16,17,13,70,11,29,80,31,46,28,60,15";
					$to_presupuesto_dia = $this->presupuesto->get_presupuesto_dia($centros_costos);
					$to_presupuesto_dia = $to_presupuesto_dia->total;
					/*Calificacion PAC*/
					$calificacion_pac = $this->Informe->get_calificacion_sede("general");
					/*TOTAL INVENTARIO*/
					$inventario = $this->Informe->Informe_inventario();
					$val_to_inv = 0;
					foreach ($inventario->result() as $key) {
						$val_to_inv += ($key->Promedio * $key->stock);
					}
					/*CALCULO NPS INTERNO GENERAL*/
					$bod = "1,9,11,21,7,6,19,8,14,16,22";
					$nps_int = 0;
					$enc0a6 = 0;
					$enc7a8 = 0;
					$enc9a10 = 0;
					$nps_int_gen = $this->Informe->get_data_nps_interno_sedes($bod);
					foreach ($nps_int_gen->result() as $key) {
						$enc0a6 += $key->enc0a6;
						$enc7a8 += $key->enc7a8;
						$enc9a10 += $key->enc9a10;
						$to_enc = $enc0a6 + $enc7a8 + $enc9a10;
						$nps_int = ((($enc9a10 - $enc0a6) / $to_enc) * 100);
					}
					/* Informe de solicitudes de mantenimiento para el perfil de MTO */
					/* Bodegas enlazadas con el encargado de mantenimiento */
					$pendientes = "";
					$proceso = "";
					$finalizadas = "";
					$pendientesPre = "";
					$procesoPre = "";
					$finalizadasPre = "";
					if ($this->session->userdata('perfil') == 46) {
						$data_sedes = $this->sedes->get_sedes_user($usu);
						$sedes_usu = "";
						foreach ($data_sedes->result() as $key) {
							$sedes_usu .= $key->idsede . ",";
						}

						$sedes_usu = trim($sedes_usu, ",");
						/* print_r($sedes_usu);die; */
						$mto_pendiente = $this->mantenimiento_uno->s_pendientes($sedes_usu);
						$pendientes = $mto_pendiente->pendientes;
						$mto_pendiente = $this->mantenimiento_uno->s_proceso($sedes_usu);
						$proceso = $mto_pendiente->proceso;
						$mto_pendiente = $this->mantenimiento_uno->s_finalizadas($sedes_usu);
						$finalizadas = $mto_pendiente->finalizada;
					}
					if ($this->session->userdata('perfil') == 46) {

						$mto_pendientePre = $this->mantenimiento_uno->s_pendientesPre();
						$pendientesPre = $mto_pendientePre->pendientes;

						$mto_procesPre = $this->mantenimiento_uno->s_procesoPre();
						$procesoPre = $mto_procesPre->proceso;

						$mto_finalizadasPre = $this->mantenimiento_uno->s_finalizadasPre();
						$finalizadasPre = $mto_finalizadasPre->finalizada;
					}



					/********************************************************************************************************************************************/
					$img_url = ($perfil_user->url_img_user_postv) != '' ? $perfil_user->url_img_user_postv : '';
					$arr_user = array(
						'dia_actual' => $boolDiaEsFestivo, 'fecha_actual' => $fecha_actual->fecha_actual,
						'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'graf_sedes' => $graf_sedes, 'porcen_giron' => $porcentaje_objetivo_giron, 'porcen_rosita' => $porcentaje_objetivo_rosita, 'porcen_barranca' => $porcentaje_objetivo_barranca, 'porcen_bocono' => $porcentaje_objetivo_bocono, 'porcen_soloc' => $porcentaje_objetivo_solochevr, 'porcen_chev' => $porcentaje_objetivo_chevrop, 'to_posv' => $to_presupuesto_dia, 'cal_pac' => $calificacion_pac, 'to_inv' => $val_to_inv, 'nps_int' => $nps_int, 'pendientes' => $pendientes, 'proceso' => $proceso, 'finalizadas' => $finalizadas,
						'pendientesPre' => $pendientesPre, 'procesoPre' => $procesoPre, 'finalizadasPre' => $finalizadasPre, 'data_estado' => $data_estado,
						'img_user' => $img_url
					);
					//abrimos la vista

					$this->load->view('admin', $arr_user);
				} else {
					$this->session->sess_destroy();
					header("Location: " . base_url() . "");
				}
			}
		}
	}

	/* Metodo que destruye la session y redirecciona al login */
	public function logout()
	{
		//se destruye la sessions
		$this->session->sess_destroy();
		header("Location: " . base_url());
	}

	public function enviar_correo_verifi()
	{
		//traemos los modelos
		$this->load->model('usuarios');
		//traemos los datos de la vista
		$cedula = $this->input->GET('nit');

		/*CODIGO PARA GENERAR EL CODIGO DE VERIFICACION*/
		$permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$codigo = substr(str_shuffle($permitted_chars), 0, 4);
		/*FIN*/


		//echo $codigo;die;
		$val_tercero = $this->usuarios->validarUsuarios($cedula)->n;
		if ($val_tercero == 1) {
			/*hacemos el update en la tabla w_sist_usuarios en el campo cod_verifi*/
			if ($this->usuarios->update_cod_verifi($cedula, $codigo)) {
				// se envia el correo
				$mail = $this->usuarios->get_mail_by_cedula($cedula)->mail;
				$this->load->library('phpmailer_lib');
				/* echo $key->correo."<br>"; */
				// PHPMailer object
				$correo = $this->phpmailer_lib->load();

				// SMTP configuration
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
				$correo->addAddress($mail);
				$correo->Username = "no-reply@codiesel.co";
				$correo->Password = "wrtiuvlebqopeknz";
				$correo->SetFrom("no-reply@codiesel.co", "CODIESEL S.A");
				$correo->Subject = "Codigo de verificacion INTRANET POSVENTA";
				$msn = "Su Código de verificación es: " . $codigo;
				$mensaje = '<!DOCTYPE html>
				<html lang="en">
				<head>
				<meta charset="UTF-8" />
				<meta http-equiv="X-UA-Compatible" content="IE=edge" />
				<meta name="viewport" content="width=device-width, initial-scale=1.0" />
				<title>Document</title>
				</head>
				<body>
				<div class="content" style="height: 100%; display: flex; align-items: center; justify-content: center;">
				<div id="tarjeta" class="card text-center w-75 mx-auto h-50" align="center" style="width: 75%;
				background-color: #f8f9fa;
				-webkit-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);
				-moz-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);
				box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);">
				<div id="cab-tarjeta" class="card-header">

				</div>
				<div class="card-body">
				<h2 class="card-title" style="font-weight: 700!important;
				font-size: 2rem;
				">Código de verificación INTRANET POSVENTA</h2>
				<p class="card-text" style="padding: 20px;

				font-size: 1rem;
				line-height: 1.5;
				word-wrap: break-word;
				margin-bottom: 35px;">
				' . $msn . '
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
				<div class="">

				</div>
				<div class="contacto" style="display: flex;
				flex-direction: column;
				justify-content: space-evenly;">

				</div>
				</div>
				</div>
				</div>
				</body>
				</html>
				';
				$correo->MsgHTML($mensaje);
				if (!$correo->Send()) {
					echo "err";
				} else {
					echo "ok";
				}
				//echo "ok";
			} else {
				echo "err";
			}
		} elseif ($val_tercero == 0) {
			echo "err";
		}
	}
	public function validar_cod_verifi()
	{
		//traemos los modelos
		$this->load->model('usuarios');
		//traemos las variables de la vista
		$codigo = $this->input->GET('codigo');
		$cedula = $this->input->GET('nit');
		$clave = $this->input->GET('clave');
		$infoUsuario = $this->usuarios->validar_cod_verifi($cedula, $codigo);
		$id_usu = $infoUsuario->id_usuario;
		$this->load->library('encrypt');
		$pass_encript = $this->encrypt->encode($cedula);
		//echo $pass_encript;
		if ($infoUsuario->n == 1) {
			$data = array('pass' => $pass_encript);
			//echo $id_perfil." ".$nit;	
			if ($this->usuarios->updatePass_req($data, $id_usu)) {
				if ($clave != "") {
					$this->usuarios->updatePass_reqVentas($clave, $id_usu);
				}

				echo "ok";
			} else {
				echo "err1";
			}
		} else {
			echo "err";
		}
	}



	/**
	 * METODO PAR VER RANKIN DE LOS TECNICOS
	 * ANDRES GOMEZ 
	 * 2022-01-05
	 */
	public function traer_rankig_tecnicos()
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

			$mes_actual = date('m');
			$ano_actual = date('Y');

			$rankin_diesel_giron = $this->talleres->ventas_tec_ranking("11", $mes_actual, $ano_actual);
			$rankin_gasolina_giron = $this->talleres->ventas_tec_ranking("1", $mes_actual, $ano_actual);
			$rankin_diesel_cucuta = $this->talleres->ventas_tec_ranking("16,8", $mes_actual, $ano_actual);
			$rankin_barranca = $this->talleres->ventas_tec_ranking("19,6", $mes_actual, $ano_actual);
			$rankin_rosita = $this->talleres->ventas_tec_ranking("7", $mes_actual, $ano_actual);




			$arr_user = array(
				'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass,
				'rankin_d_g' => $rankin_diesel_giron, 'rankin_g_g' => $rankin_gasolina_giron, 'rankin_d_c' => $rankin_diesel_cucuta,
				'rankin_b' => $rankin_barranca, 'rankin_r' => $rankin_rosita
			);
			//abrimos la vista
			$this->load->view("ranking/index", $arr_user);
		}
	}

	/**
	 * METODO PARA FILTRAR RANKING DE TECNICOS POR  MES
	 * ANDRES GOMEZ
	 * 2022-01-05
	 */
	public function filtro_ranking_tecnico()
	{
		$this->load->model('talleres');
		$mes_seleccionado = $this->input->POST('mes');
		$ano_actual = $this->input->POST('ano');

		$rankin_diesel_giron = $this->talleres->ventas_tec_ranking("11", $mes_seleccionado, $ano_actual);
		$rankin_gasolina_giron = $this->talleres->ventas_tec_ranking("1", $mes_seleccionado, $ano_actual);
		$rankin_diesel_cucuta = $this->talleres->ventas_tec_ranking("16,8", $mes_seleccionado, $ano_actual);
		$rankin_barranca = $this->talleres->ventas_tec_ranking("19,6", $mes_seleccionado, $ano_actual);
		$rankin_rosita = $this->talleres->ventas_tec_ranking("7", $mes_seleccionado, $ano_actual);




		echo "
		<div class='row align-items-center justify-content-center'>
		<br>

		<div class='responsive p-4 padretabla'>
		<table class='table table-bordered table-striped tabladatos'>



		<thead>
		<tr>
		<th colspan='4'><label class='text-center col-lg-12'>Giron Gasolina</label></th>
		</tr>
		<tr>
		<th scope='col'>Tecnico</th>
		<th scope='col'>Ventas Repuesto</th>
		<th scope='col'>Ventas Trabajo</th>
		<th scope='col'>Total Ventas</th>
		</tr>
		</thead>
		<tbody>
		";

		foreach ($rankin_gasolina_giron->result() as $gg) {
			echo "
			<tr>
			<th> $gg->tecnico</th>
			<td>$" . number_format($gg->rptos, 0, ",", ",") . "</td>
			<td>$" . number_format($gg->MO, 0, ",", ",") . "</td>
			<td>$" . number_format($gg->suma_todo, 0, ",", ",") . "</td>
			</tr>
			";
		}
		echo "
		</tbody>
		</table>
		</div>
		";
		echo "
		<div class='responsive p-4 padretabla'>
		<table class='table table-bordered table-striped'>
		<thead>
		<tr>
		<td colspan='4'><label class='text-center col-lg-12'>Giron Diesel</label></td>
		</tr>
		<tr>
		<th scope='col'>Tecnico</th>
		<th scope='col'>Ventas Repuesto</th>
		<th scope='col'>Ventas Trabajo</th>
		<th scope='col'>Total Ventas</th>
		</tr>
		</thead>
		<tbody>
		";
		foreach ($rankin_diesel_giron->result() as $gd) {
			echo "
			<tr>
			<th>$gd->tecnico</th>
			<td>$" . number_format($gd->rptos, 0, ",", ",") . "</td>
			<td>$" . number_format($gd->MO, 0, ",", ",") . "</td>
			<td>$" . number_format($gd->suma_todo, 0, ",", ",") . "</td>

			</tr>
			";
		}
		echo "
		</tbody>
		</table>
		</div>
		";
		echo "
		<div class='responsive p-4 padretabla'>
		<table class='table table-bordered table-striped'>
		<thead>
		<tr>
		<td colspan='4'><label class='text-center col-lg-12'>Cucuta</label></td>
		</tr>
		<tr>
		<th scope='col'>Tecnico</th>
		<th scope='col'>Ventas Repuesto</th>
		<th scope='col'>Ventas Trabajo</th>
		<th scope='col'>Total Ventas</th>
		</tr>

		</thead>
		<tbody>
		";
		foreach ($rankin_diesel_cucuta->result() as $dc) {
			echo "
			<tr>
			<th> $dc->tecnico</th>
			<td>$" . number_format($dc->rptos, 0, ",", ",") . "</td>
			<td>$" . number_format($dc->MO, 0, ",", ",") . "</td>
			<td>$" . number_format($dc->suma_todo, 0, ",", ",") . "</td>
			</tr>
			";
		}
		echo "
		</tbody>
		</table>
		</div>
		";
		echo "
		<div class='responsive p-4 padretabla'>
		<table class='table table-bordered table-striped'>
		<thead>
		<tr>
		<td colspan='4'><label class='text-center col-lg-12'>La Rosita</label></td>
		</tr>
		<tr>
		<th scope='col'>Tecnico</th>
		<th scope='col'>Ventas Repuesto</th>
		<th scope='col'>Ventas Trabajo</th>
		<th scope='col'>Total Ventas</th>
		</tr>
		</thead>
		<tbody>
		";
		foreach ($rankin_rosita->result() as $rc) {
			echo "
			<tr>
			<th> $rc->tecnico</th>
			<td>$" . number_format($rc->rptos, 0, ",", ",") . "</td>
			<td>$" . number_format($rc->MO, 0, ",", ",") . "</td>
			<td>$" . number_format($rc->suma_todo, 0, ",", ",") . "</td>
			</tr>
			";
		}
		echo "
		</tbody>
		</table>
		</div>
		";
		echo "
		<div class='responsive p-4 padretabla'>
		<table class='table table-bordered table-striped'>
		<thead>
		<tr>
		<td colspan='4'><label class='text-center col-lg-12'>Barrancabermeja</label></td>
		</tr>
		<tr>
		<th scope='col'>Tecnico</th>
		<th scope='col'>Ventas Repuesto</th>
		<th scope='col'>Ventas Trabajo</th>
		<th scope='col'>Total Ventas</th>
		</tr>
		</thead>
		<tbody>
		";
		foreach ($rankin_barranca->result() as $bc) {
			echo "
			<tr>
			<th> $bc->tecnico</th>
			<td>$" . number_format($bc->rptos, 0, ",", ",") . "</td>
			<td>$" . number_format($bc->MO, 0, ",", ",") . "</td>
			<td>$" . number_format($bc->suma_todo, 0, ",", ",") . "</td>
			</tr>
			";
		}
		echo "
		</tbody>
		</table>
		</div>

		</div>
		";
	}


	/**
	 * METODO PARA VALIDAR LAS PAUSAS ACTIVAS
	 * Sergio Galvis
	 * 06/08/2022
	 */

	public function addPausaActivaAm()
	{

		$this->load->model('Usuarios');
		$user = $this->input->POST('user');

		if ($this->Usuarios->validarPausaActivaAm($user)) {
			echo 1;
		} else {
			$this->Usuarios->aggPausaActivaAm($user);
			echo 2;
		}
	}
	public function addPausaActivaPm()
	{

		$this->load->model('Usuarios');
		$user = $this->input->POST('user');

		if ($this->Usuarios->validarPausaActivaPm($user)) {
			echo 1;
		} else {
			$this->Usuarios->aggPausaActivaPm($user);
			echo 2;
		}
	}

	public function getClaveIntranetVentas()
	{
		$this->load->model('Usuarios');
		$nit = $this->session->userdata('user');
		$clave = $this->Usuarios->getClaveIntranetVentas($nit)->pass;

		$this->load->library('encrypt');

		$clave = $this->encrypt->decode($clave);


		echo $clave;
	}
}
