<?php

/**
 * 
 */
class Taller extends CI_Controller
{

	public function val_movil()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos necesarios
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
			//$allempleados = $this->nominas->listar_empleados();
			$allempleados = null;
			//prueba de menu
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//echo $id_usu;

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu);
			//abrimos la vista
			$this->load->view('validar_movil', $arr_user);
		}
	}

	public function val_doc()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos necesarios
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
			//$allempleados = $this->nominas->listar_empleados();
			$allempleados = null;
			//prueba de menu
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//echo $id_usu;

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu);
			//abrimos la vista
			$this->load->view('validar_movil', $arr_user);
		}
	}

	/*METODO QUE CARGA LA VISTA DEL Informe DE TALLER*/
	public function estado_taller()
	{
		//Verificar estado_taller
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos necesarios
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('talleres');
			$this->load->model('sedes');
			$this->load->model('Sacyr_model');
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
			//prueba de menu
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			/******************************************************************************/
			$data_bod = $this->sedes->get_sedes_user($usu);
			$bods = "";
			foreach ($data_bod->result() as $key) {
				$bods .= $key->idsede . ",";
			}
			$bods = trim($bods, ',');
			$arr_bods = explode(",",$bods);
			if(in_array(25,$arr_bods)){
				$where = " WHERE id_estado IN(23,59,56,60,61,62,63,64,65,66,67,68,69)";
				$estados_ot_tall = $this->talleres->get_estado_ot_tall($where);
			}else{
				$estados_ot_tall = $this->talleres->get_estado_ot_tall();
			}
			$data_ot = $this->talleres->get_info_ot_bod($bods);
			$estados_ot_tall = $this->talleres->get_estado_ot_tall();
			$arr_user = array(
				'userdata' => $userinfo,
				'menus' => $allmenus,
				'perfiles' => $allperfiles,
				'pass' => $pass,
				'id_usu' => $id_usu,
				'data_ot' => $data_ot,
				'estados_ot_tall' => $estados_ot_tall,
				'data_bod' => $data_bod,
				'bods' => $bods
			);
			//abrimos la vista
			$this->load->view('Informe_taller/index', $arr_user);
		}
	}

	public function add_evento()
	{
		$this->load->model('talleres');
		$ot = $this->input->GET('ot');
		$estado = $this->input->GET('estado');
		$proceso = $this->input->GET('proceso');
		$notas = $this->input->GET('notas');
		$fec_prom_entrega = $this->input->GET('fec_prom_entrega');
		if ($fec_prom_entrega == "") {
			$fec_prom_entrega = $this->talleres->get_fec_prom_entrega($ot)->fec_promesa_entrega;
		}

		$arr_fecha = getdate();
		$fecha_hist = $arr_fecha['year'] . "-" . $arr_fecha['mon'] . "-" . $arr_fecha['mday'];
		$data = array('ot' => $ot, 'notas' => $notas, 'estado' => $estado, 'fecha' => $fecha_hist, 'proceso' => $proceso, 'fec_promesa_entrega' => $fec_prom_entrega);
		if ($this->talleres->add_evento($data)) {
			echo "ok";
		} else {
			echo "err";
		}
	}

	public function cargar_hist_ot()
	{
		$this->load->model('talleres');
		$ot = $this->input->GET('ot');
		$data_hist = $this->talleres->get_info_ot_bod_hist($ot);
		foreach ($data_hist->result() as $key) {
			echo '<tr style="font-size: 12px;">
			<td>' . $key->numero . '</td>
			<td>' . $key->asesor . '</td>
			<td>' . $key->estado . '</td>
			<td>' . $key->notas . '</td>
			<td>' . $key->fecha_hist . '</td>
			</tr>';
		}
	}

	public function cargar_tabla_filtro()
	{
		$this->load->model('talleres');
		$this->load->model('sedes');
		$bod = $this->input->GET('bod');
		$usu = $this->session->userdata('user');
		$data_bod = $this->sedes->get_sedes_user($usu);
		$bods = "";
		foreach ($data_bod->result() as $key) {
			$bods .= $key->idsede . ",";
		}
		if ($bod == "todas") {
			$bods = trim($bods, ',');
			$data_ot = $this->talleres->get_info_ot_bod($bods);
		} else {
			$data_ot = $this->talleres->get_info_ot_bod($bod);
		}

		if (!empty($bod)) {

			foreach ($data_ot->result() as $key) {
				/*BORDE EN ESTADO DE ESPERA*/
				$estado = $key->estado;
				if (
					$estado == 'EN ESPERA DE  RTOS G.M.'
					|| $estado == 'EN ESPERA POR ASIGNACION DE MO'
					|| $estado == 'EN ESPERA DE RPTOS G.M.'
					|| $estado == 'EN ESPERA AUTORIZACIÓN'
					|| $estado == 'EN ESPERA DIAGNÓSTICO'
				) {
					$border = 'border: solid red 5px;';
				} else {
					$border = 'border: solid transparent 1px;';
				}
				if($key->fecha_prom_ent != ''){
					$diff = $this->talleres->get_diff_dias_fecha($key->fecha_prom_ent)->ndias;
				}else{
					$diff = '';
				}
				
				$color = "";
				if ($bods = "9,21") {
					if ($key->razon2 == 1) {
						if ($diff > 0 && $diff <= 3) {
							$color = "table-danger";
						} elseif ($diff > 4 && $diff <= 6) {
							$color = "table-warning";
						} elseif ($diff > 6 && $diff <= 7) {
							$color = "table-success";
						}
					} elseif ($key->razon2 == 2) {
						if ($diff > 0 && $diff <= 4) {
							$color = "table-danger";
						} elseif ($diff > 4 && $diff <= 8) {
							$color = "table-warning";
						} elseif ($diff > 8 && $diff <= 10) {
							$color = "table-success";
						}
					} elseif ($key->razon2 == 3) {
						if ($diff > 0 && $diff <= 6) {
							$color = "table-danger";
						} elseif ($diff > 6 && $diff <= 11) {
							$color = "table-warning";
						} elseif ($diff > 11 && $diff <= 16) {
							$color = "table-success";
						}
					}
				}
				$razon2 = "";
				if ($key->razon2 == 1) {
					$razon2 = "Colisión Leve";
				} elseif ($key->razon2 == 2) {
					$razon2 = "Colisión Media";
				} elseif ($key->razon2 == 3) {
					$razon2 = "Colisión Fuerte";
				} elseif ($key->razon2 == 4) {
					$razon2 = "Mecanica Rapida";
				} elseif ($key->razon2 == 5) {
					$razon2 = "Mecanica Especializada";
				} elseif ($key->razon2 == 6) {
					$razon2 = "Accesorios";
				} elseif ($key->razon2 == 7) {
					$razon2 = "Garantia G.M.C";
				} elseif ($key->razon2 == 8) {
					$razon2 = "Alistamiento y Peritaje";
				} elseif ($key->razon2 == 9) {
					$razon2 = "Retorno";
				} elseif ($key->razon2 == 10) {
					$razon2 = "Interno";
				} else {
					$razon2 = $key->razon2;
				}
				$razon = "";
				if ($key->razon == 1) {
					$razon = "Colisión Leve";
				} elseif ($key->razon == 2) {
					$razon = "Colisión Media";
				} elseif ($key->razon == 3) {
					$razon = "Colisión Fuerte";
				} elseif ($key->razon == 4) {
					$razon = "Mecanica Rapida";
				} elseif ($key->razon == 5) {
					$razon = "Mecanica Especializada";
				} elseif ($key->razon == 6) {
					$razon = "Accesorios";
				} elseif ($key->razon == 7) {
					$razon = "Garantia G.M.C";
				} elseif ($key->razon == 8) {
					$razon = "Alistamiento y Peritaje";
				} elseif ($key->razon == 9) {
					$razon = "Retorno";
				} elseif ($key->razon == 10) {
					$razon = "Interno";
				} else {
					$razon = $key->razon;
				}
				echo '<tr class="' . $color . '" style="' . $border . '">
			<td >
			<div class="row">
			<div class="col-md-6">
			<a href="#" class="btn btn-outline-primary btn-sm" style="font-size: 12px;" onclick="open_form_addev(' . $key->numero . ')"' . '><i class="fas fa-plus-square"></i></a>
			</div>
			<div class="col-md-6">
			<a href="#" class="btn btn-outline-success btn-sm" style="font-size: 12px;" onclick="open_form_hist(' . $key->numero . ')"' . '><i class="fas fa-book-medical"></i></a>											
			</div>
			</div>
			</td>
			<td>' . $key->bodega . '</td>
			<td><strong>' . $key->numero . '</strong></td>
			<td style="background-color: #E5E5E5;">' . $key->estado . '</td>
			<td style="background-color: #E5E5E5;">' . $key->notas . '</td>
			<td style="background-color: #E5E5E5;">' . $key->fecha_prom_ent . '</td>
			<td>' . $key->fecha . '</td>
			<td>' . $key->cliente . '</td>
			<td><strong>' . $key->placa . '</strong></td>
			<td><strong>' . $key->rombo . '</strong></td>
			<td>' . $key->aseguradora . '</td>
			<td>' . $razon . '</td>
			<td>' . $razon2 . '</td>

			<td>' . $key->asesor . '</td>
			<td>' . $key->kilometraje . '</td>
			<td>' . $key->descripcion . '</td>
			<td>' . $key->dias_ot_abierta . '</td>
			</tr>';
			}
		}
	}
	public function Informe_ot_taller()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos necesarios
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
			$allperfiles = $this->perfiles->getAllPerfiles();
			$allempleados = null;
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//ORDENES ABIERTAS
			/*BODEGAS*/
			$giron = "1,9,11,21";
			$rosita = "7";
			$barranca = "6,19";
			$bocono  = "8,14,16,22";

			$todas_bods = $giron . "," . $rosita . "," . $barranca . "," . $bocono;

			$ot_abiertas = $this->talleres->get_ot_abiertas($todas_bods);
			$ot_giron = $this->talleres->get_ot_abiertas($giron);
			$ot_rosita = $this->talleres->get_ot_abiertas($rosita);
			$ot_barranca = $this->talleres->get_ot_abiertas($barranca);
			$ot_bocono = $this->talleres->get_ot_abiertas($bocono);
			$total_registros = count($ot_abiertas->result());
			$total_giron = count($ot_giron->result());
			$total_rosita = count($ot_rosita->result());
			$total_barranca = count($ot_barranca->result());
			$total_bocono = count($ot_bocono->result());
			$data_to_sedes = array('giron' => $total_giron, 'rosita' => $total_rosita, 'barranca' => $total_barranca, 'bocono' => $total_bocono, 'total' => $total_registros);
			$tipo_inf = "general";

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'ot_abiertas' => $ot_abiertas, 'total_sedes' => $data_to_sedes, 'tipo_inf' => $tipo_inf);
			//abrimos la vista
			$this->load->view('Informe_taller/inf_ot_abiertas', $arr_user);
		}
	}

	public function Informe_ot_taller_by_sede()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//traemos los parametros por url
			$sede = $this->input->GET('sede');
			//llamamos los modelos necesarios
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
			$allperfiles = $this->perfiles->getAllPerfiles();
			$allempleados = null;
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//ORDENES ABIERTAS
			/*BODEGAS*/
			$bod = "";
			//$data_talleres
			$giron = "1,9,11,21";
			$rosita = "7";
			$barranca = "6,19";
			$bocono  = "8,14,16,22";
			switch ($sede) {
				case 'giron':
					$data_talleres = $this->talleres->get_count_ot_abiertas($giron);
					$ot_abiertas = $this->talleres->get_ot_abiertas($giron);
					break;
				case 'rosita':
					$data_talleres = $this->talleres->get_count_ot_abiertas($rosita);
					$ot_abiertas = $this->talleres->get_ot_abiertas($rosita);
					break;
				case 'barranca':
					$data_talleres = $this->talleres->get_count_ot_abiertas($barranca);
					$ot_abiertas = $this->talleres->get_ot_abiertas($barranca);
					break;
				case 'bocono':
					$data_talleres = $this->talleres->get_count_ot_abiertas($bocono);
					$ot_abiertas = $this->talleres->get_ot_abiertas($bocono);
					break;
				default:
					header("Location: " . base_url() . "taller/Informe_ot_taller");
					break;
			}

			$tipo_inf = "sedes";

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'n_ot' => $data_talleres, 'tipo_inf' => $tipo_inf, 'ot_abiertas' => $ot_abiertas);
			//abrimos la vista
			$this->load->view('Informe_taller/inf_ot_abiertas', $arr_user);
		}
	}

	public function Informe_ot_taller_by_taller()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//traemos los parametros por url
			$tall = $this->input->GET('taller');
			//llamamos los modelos necesarios
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
			$allperfiles = $this->perfiles->getAllPerfiles();
			$allempleados = null;
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//ORDENES ABIERTAS
			/*BODEGAS*/
			$data_ot_tec = $this->talleres->get_count_ot_abiertas_tec($tall);

			$tipo_inf = "tecnico";

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'data_ot_tec' => $data_ot_tec, 'tipo_inf' => $tipo_inf);
			//abrimos la vista
			$this->load->view('Informe_taller/inf_ot_abiertas', $arr_user);
		}
	}

	public function get_total_ot_abiertas()
	{
		$this->load->model('talleres');
		$this->load->model('sedes');
		$bod = $this->input->GET('bod');
		$usu = $this->session->userdata('user');
		$data_bod = $this->sedes->get_sedes_user($usu);
		$bods = "";
		foreach ($data_bod->result() as $key) {
			$bods .= $key->idsede . ",";
		}
		if ($bod == "todas") {
			$bods = trim($bods, ',');
			$data_ot = $this->talleres->get_count_ot_abiertas($bods);
		} else {
			$data_ot = $this->talleres->get_count_ot_abiertas($bod);
		}
		$ot = 0;
		foreach ($data_ot->result() as $key) {
			$ot = $ot + $key->n;
		}
		echo $ot;
	}

	public function val_orden()
	{
		$this->load->model('talleres');
		$orden = $this->input->GET('orden');
		if ($this->talleres->get_val_ot_abiertas($orden)->n == 0) {
			echo "Error, orden no existe";
		} else {
			echo "Bien, la orden existe";
		}
	}

	public function val_placa()
	{
		$this->load->model('talleres');
		$orden = $this->input->GET('placa');
		if ($this->talleres->get_val_placa($orden)->n == 0) {
			echo "Error, La Placa No Existe";
		} else {
			echo "Bien, La Placa Existe";
		}
	}

	public function generar_recibo_tot()
	{
		$id_v = $this->input->GET('id_v');
		$this->load->model('vehiculos');
		$info_tot = $this->vehiculos->info_tot_recibo($id_v);
		$data_pdf = array('info_tot' => $info_tot);
		$mpdf = new \Mpdf\Mpdf();
		$nom_pdf = $id_v . ".pdf";
		$html = $this->load->view('pdfView', $data_pdf, true);
		$mpdf->WriteHTML($html);
		$mpdf->Output($nom_pdf, 'I');
	}

	public function ver_pdf()
	{
		$this->load->model('vehiculos');
		$info_tot = $this->vehiculos->info_tot_recibo('533564');
		$data = array('info_tot' => $info_tot);
		$this->load->view('pdfView', $data);
	}

	public function entrada_vehiculo()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('talleres');
			$this->load->model('sedes');

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

			/*LOGICA PARA OBTENER LAS SEDES DEL USUARIO*/
			$usu_sede = $this->sedes->get_sedes_user($usu);
			$ussede = "";
			foreach ($usu_sede->result() as $key) {
				$ussede = $ussede . $key->idsede_v . ",";
			}
			$sedes_us = substr($ussede, 0, -1);
			//$bode = substr($ussede, 0, -1);
			//$sedes_us =1;



			/*LOGICA PARA OBTENER LOS DATOS DE LAS CITAS*/
			$data_citas = $this->talleres->get_info_entradas_vh($sedes_us);
			$data_citas_atendidas = $this->talleres->get_info_entradas_vh_atendidas($sedes_us);
			foreach ($data_citas_atendidas->result() as $key) {
				if ($key->descripcion_estado == "Atendida") {
					$citas_atendidas[] = array(
						'id_cita' => $key->id_cita, 'descripcion' => $key->nom_bodega,
						'estado' => $key->descripcion_estado, 'fecha_cita' => $key->fecha_cita, 'placa' => $key->placa,
						'vehiculo' => $key->vh, 'cliente' => $key->nombre_cliente, 'encargado' => $key->nombre_encargado,
						'bahia' => $key->descripcion_bahia, 'notas' => $key->notas, 'bod' => $key->bodega
					);

					//print_r($citas_atendidas);
				}
			}
			foreach ($data_citas->result() as $key) {
				if ($key->descripcion_estado == "Atendida") {
				} elseif ($key->descripcion_estado == "Programada" || $key->descripcion_estado == "Reprogramada") {
					$citas[] = array(
						'id_cita' => $key->id_cita, 'descripcion' => $key->nom_bodega,
						'estado' => $key->descripcion_estado, 'fecha_cita' => $key->fecha_cita, 'fecha_cita_v' => $key->fecha_hora_ini, 'placa' => $key->placa,
						'vehiculo' => $key->vh, 'cliente' => $key->nombre_cliente, 'encargado' => $key->nombre_encargado,
						'bahia' => $key->descripcion_bahia, 'notas' => $key->notas, 'bod' => $key->bodega
					);
				}
			}

			/*CITAS SIN ORDEN*/
			$citas_sin_ot = $this->talleres->get_info_vh_sin_ot($sedes_us);
			$citas_s_ot = array();
			foreach ($citas_sin_ot->result() as $key) {
				$citas_s_ot[] = array(
					'fecha' => $key->fecha_cita, 'placa' => $key->placa, 'cliente' => $key->nombre_cliente,
					'encargado' => $key->nombre_encargado, 'bahia' => $key->descripcion_bahia,
					'vh' => $key->vh
				);
			}
			/*VEHICULOS SIN CITA*/
			$vh_sin_cita = $this->talleres->get_vh_sin_cita($sedes_us);

			//print_r($citas_atendidas);die;
			//print_r($citas_s_ot);die;
			//print_r($citas);
			$arr_user = array(
				'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu,
				'citas_atendidas' => $citas_atendidas, 'citas' => $citas, 'citas_sin_ot' => $citas_s_ot, 'sedes' => $usu_sede, 'vh_sin_cita' => $vh_sin_cita
			);
			//abrimos la vista
			$this->load->view("Informe_taller/entrada_vehiculo", $arr_user);
		}
	}

	public function marcar_entrada_vh()
	{
		$this->load->model('talleres');
		$id_cita = $this->input->GET('id_cita');
		if ($this->talleres->insert_postv_entrada_vh_taller($id_cita)) {
			echo "ok";
		} else {
			echo "err";
		}
	}

	/*BUSCAR POR PLACA BY CRISTIAN*/
	public function buscarByPlaca()
	{
		$this->load->model('usuarios');
		$this->load->model('talleres');
		$this->load->model('sedes');

		//si ya hay datos de session los carga de nuevo
		$usu = $this->session->userdata('user');
		//cargamos la informacion del usuario y la pasamos a un array
		$userinfo = $this->usuarios->getUserByName($usu);
		$id_usu = "";
		foreach ($userinfo->result() as $key) {
			$id_usu = $key->id_usuario;
		}

		/*LOGICA PARA OBTENER LAS SEDES DEL USUARIO*/
		$usu_sede = $this->sedes->get_sedes_user($usu);
		$ussede = "";
		foreach ($usu_sede->result() as $key) {
			$ussede = $ussede . $key->idsede_v . ",";
		}
		$sedes_us = substr($ussede, 0, -1);
		//$bode = substr($ussede, 0, -1);
		//$sedes_us =1;

		/*LOGICA PARA OBTENER LOS DATOS DE LAS CITAS PROGRAMADAS FILTRADAS POR PLACA Y BODEGA*/
		$placa = $this->input->GET('placa');
		$data_citas = $this->talleres->get_info_entradas_vh_placa($sedes_us, $placa);


		/*LOGICA PARA OBTENER LOS DATOS DE LAS CITAS*/
		$data_citas_atendidas = $this->talleres->get_info_entradas_vh_atendidas_placa($sedes_us, $placa);
		foreach ($data_citas_atendidas->result() as $key) {
			if ($key->descripcion_estado == "Atendida") {
				$citas_atendidas[] = array(
					'id_cita' => $key->id_cita, 'descripcion' => $key->nom_bodega,
					'estado' => $key->descripcion_estado, 'fecha_cita' => $key->fecha_cita, 'placa' => $key->placa,
					'vehiculo' => $key->vh, 'cliente' => $key->nombre_cliente, 'encargado' => $key->nombre_encargado,
					'bahia' => $key->descripcion_bahia, 'notas' => $key->notas, 'bod' => $key->bodega
				);

				//print_r($citas_atendidas);
			}
		}
		foreach ($data_citas->result() as $key) {
			if ($key->descripcion_estado == "Atendida") {
			} elseif ($key->descripcion_estado == "Programada" || $key->descripcion_estado == "Reprogramada") {
				$citas[] = array(
					'id_cita' => $key->id_cita, 'descripcion' => $key->nom_bodega,
					'estado' => $key->descripcion_estado, 'fecha_cita' => $key->fecha_cita, 'fecha_cita_v' => $key->fecha_hora_ini, 'placa' => $key->placa,
					'vehiculo' => $key->vh, 'cliente' => $key->nombre_cliente, 'encargado' => $key->nombre_encargado,
					'bahia' => $key->descripcion_bahia, 'notas' => $key->notas, 'bod' => $key->bodega
				);
			}
		}

		/*CITAS SIN ORDEN*/
		$citas_sin_ot = $this->talleres->get_info_vh_sin_ot_placa($sedes_us, $placa);
		$citas_s_ot = array();
		foreach ($citas_sin_ot->result() as $key) {
			$citas_s_ot[] = array(
				'fecha' => $key->fecha_cita, 'placa' => $key->placa, 'cliente' => $key->nombre_cliente,
				'encargado' => $key->nombre_encargado, 'bahia' => $key->descripcion_bahia,
				'vh' => $key->vh
			);
		}
		/*VEHICULOS SIN CITA*/
		$vh_sin_cita = $this->talleres->get_vh_sin_cita_placa($sedes_us, $placa);

		/* $arr_user = array(
				'userdata' => $userinfo, 'id_usu' => $id_usu,
				'citas_atendidas' => $citas_atendidas, 'citas' => $citas, 'citas_sin_ot' => $citas_s_ot, 'sedes' => $usu_sede, 'vh_sin_cita' => $vh_sin_cita
			); */

		echo '
			<div class="accordion" id="accordionExample">
                    <div class="card"> <!--  Vehículos con cita programada -->
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Vehículos con cita programada
                                </button>
                            </h2>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">
                                <div id="res">';
		if (empty($citas)) {
		} else {
			foreach ($citas as $key) {
				$color = "";
				$fondo = "";
				$img = "";
				if ($key['bod'] == 1 || $key['bod'] == 8) {
					$color = "card-primary";
					$img = "silueta-vhs.png";
					$fondo = "bg-primary";
				} elseif ($key['bod'] == 11 || $key['bod'] == 16) {
					$color = "card-success";
					$img = "silueta-camion.png";
					$fondo = "bg-success";
				} elseif ($key['bod'] == 9 || $key['bod'] == 21 || $key['bod'] == 14 || $key['bod'] == 22) {
					$color = "card-danger";
					$img = "silueta-vh-colision.png";
					$fondo = "bg-warning";
				}

				echo '
											<div class="card card-outline ' . $color . '">
												<div class="card-body">
	
													<div class="row">
														<div class="col-md-2 ' . $fondo . '">
															<div class="row">
																<div class="col-md-12" style="display: table; height: 200px;">
																	<div class="contenedor" style="display: table-cell; vertical-align: middle;">
																		<img src="' . base_url() . 'media/' . $img . '" style="width: 90%;" />
																		<div class="centrado"> ' . $key["placa"] . ' </div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-10">
															<blockquote class="quote-secondary">
																<div class="table-responsive">
																	<table class="table table-sm" align="left" style="font-size: 13px;">
																		<tr>
																			<th>Cliente:</th>
																			<td>' . $key['cliente'] . '</td>
																			<th>Encargado:</th>
																			<td>' . $key['encargado'] . '</td>
																		</tr>
																		<tr>
																			<th>Vehículo:</th>
																			<td>' . $key['vehiculo'] . '</td>
																			<th>Fecha/Hora cita:</th>
																			<td>' . $key['fecha_cita'] . '</td>
																		</tr>
																		<tr>
																			<th>Bahia/Tecnico:</th>
																			<td>' . $key['bahia'] . '</td>
																			<th>Notas:</th>
																			<td>' . $key['notas'] . '</td>
																		</tr>
																	</table>
																</div>
	
																<div class="row" align="center">
																	<div class="col-md-12"><a href="#" onclick="marcar_entrada(' . $key['id_cita'] . ',\'' . $key['fecha_cita_v'] . '\');" class="btn btn-sm btn-info">Marcar entrada</a></div>
																</div>
															</blockquote>
														</div>
													</div>
												</div>
											</div>';
			}
		}

		echo '
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card"> <!-- Vehículos sin orden de trabajo -->
                        <div class="card-header" id="headingThree"> 
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                    Vehículos sin orden de trabajo
                                </button>
                            </h2>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                            <div class="card-body">';
		if (empty($citas_s_ot)) {
		} else {
			foreach ($citas_s_ot as $key) {
				echo '<div class="card">
										 <div class="card-body">
 
											 <div class="row">
												 <div class="col-md-2">
													 <div class="row">
														 <div class="col-md-12" style="display: table; height: 200px;">
															 <div class="contenedor" style="display: table-cell; vertical-align: middle;">
																 <img src="' . base_url() . 'media/silueta-vhs.png" style="width: 90%;" />
																 <div class="centrado">' . $key['placa'] . '</div>
															 </div>
														 </div>
													 </div>
												 </div>
												 <div class="col-md-10">
													 <blockquote class="quote-secondary">
														 <div class="table-responsive">
															 <table class="table table-sm" align="left" style="font-size: 13px;">
																 <tr>
																	 <th>Cliente:</th>
																	 <td>' . $key['cliente'] . '</td>
																	 <th>Encargado:</th>
																	 <td>' . $key['encargado'] . '</td>
																 </tr>
																 <tr>
																	 <th>Vehículo:</th>
																	 <td>' . $key['vh'] . '</td>
																	 <th>Fecha/Hora cita:</th>
																	 <td>' . $key['fecha'] . '</td>
																 </tr>
																 <tr>
																	 <th>Bahia/Tecnico:</th>
																	 <td>' . $key['bahia'] . '</td>
																 </tr>
															 </table>
														 </div>
													 </blockquote>
 
												 </div>
											 </div>
										 </div>
									 </div>';
			}
		}

		echo '
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card"><!--  Vehiculos con cita atendida -->
                    <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                Vehiculos con cita atendida
                            </button>
                        </h2>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">';
		if (empty($citas_atendidas)) {
		} else {
			foreach ($citas_atendidas as $key) {
				echo '<div class="card">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-md-12" style="display: table; height: 200px;">
                                                        <div class="contenedor" style="display: table-cell; vertical-align: middle;">
                                                            <img src="' . base_url() . 'media/silueta-vhs.png" style="width: 90%;" />
                                                            <div class="centrado">' . $key['placa'] . '</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <blockquote class="quote-secondary">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm" align="left" style="font-size: 13px;">
                                                            <tr>
                                                                <th>Cliente:</th>
                                                                <td>' . $key['cliente'] . '</td>
                                                                <th>Encargado:</th>
                                                                <td>' . $key['encargado'] . '</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Vehículo:</th>
                                                                <td>' . $key['vehiculo'] . '</td>
                                                                <th>Fecha/Hora cita:</th>
                                                                <td>' . $key['fecha_cita'] . '</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Bahia/Tecnico:</th>
                                                                <td>' . $key['bahia'] . '</td>
                                                                <th>Notas:</th>
                                                                <td>' . $key['notas'] . '</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </blockquote>

                                            </div>
                                        </div>
                                    </div>
                                </div>';
			}
		}

		echo '
                        </div>
                    </div>
                </div>

                <div class="card">  <!-- Vehiculos sin cita -->
                    <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#vh_sin_cita" aria-expanded="true" aria-controls="vh_sin_cita">
                                Vehiculos sin cita
                            </button>
                        </h2>
                    </div>
                    <div id="vh_sin_cita" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">';
		if (empty($vh_sin_cita->result())) {
			# code...
		} else {
			foreach ($vh_sin_cita->result() as $key) {
				echo '<div class="card">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-md-12" style="display: table; height: 200px;">
                                                        <div class="contenedor" style="display: table-cell; vertical-align: middle;">
                                                            <img src="' . base_url() . 'media/silueta-vhs.png" style="width: 90%;" />
                                                            <div class="centrado">' . $key->placa . '</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <blockquote class="quote-secondary">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm" align="left" style="font-size: 13px;">
                                                            <tr>
                                                                <th>Cliente:</th>
                                                                <td>' . $key->nombre_cliente . '</td>
                                                                <th>Placa:</th>
                                                                <td>' . $key->placa . '</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Fecha/Hora cita:</th>
                                                                <td>' . $key->fecha_cita . '</td>
                                                                <th>Motivo:</th>
                                                                <td>' . $key->motivo_visita . '</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </blockquote>

                                            </div>
                                        </div>
                                    </div>
                                </div>';
			}
		}

		echo '
                        </div>
                    </div>
                </div>';
	}






	/*BUSCAR POR FECHA BY CRISTIAN*/
	public function buscarByFecha()
	{
		$this->load->model('talleres');
		$this->load->model('sedes');

		$usu = $this->session->userdata('user');
		/*LOGICA PARA OBTENER LAS SEDES DEL USUARIO*/
		$usu_sede = $this->sedes->get_sedes_user($usu);
		$ussede = "";
		foreach ($usu_sede->result() as $key) {
			$ussede = $ussede . $key->idsede_v . ",";
		}
		$sedes_us = substr($ussede, 0, -1);



		/*LOGICA PARA OBTENER LOS DATOS DE LAS CITAS PROGRAMADAS FILTRADAS POR PLACA Y BODEGA*/
		$fecha = $this->input->GET('fecha');
		$data_citas = $this->talleres->get_info_entradas_vh_fecha($sedes_us, $fecha);

		//$res = $this->talleres->get_placas_busqueda($placa);
		foreach ($data_citas->result() as $key) {
			$color = "";
			$fondo = "";
			$img = "";
			if ($key->bodega == 1) {
				$color = "card-primary";
				$img = "silueta-vhs.png";
				$fondo = "bg-primary";
			} elseif ($key->bodega == 11) {
				$color = "card-success";
				$img = "silueta-camion.png";
				$fondo = "bg-success";
			} elseif ($key->bodega == 9 || $key->bodega == 21) {
				$color = "card-danger";
				$img = "silueta-vh-colision.png";
				$fondo = "bg-warning";
			}
			echo '
		<div class="card card-outline ' . $color . '">
		<div class="card-body">

		<div class="row">
		<div class="col-md-2 ' . $fondo . '">
		<div class="row">
		<div class="col-md-12" style="display: table; height: 200px;">
		<div class="contenedor"
		style="display: table-cell; vertical-align: middle;">
		<img src="' . base_url() . 'media/' . $img . '"
		style="width: 90%;" />
		<div class="centrado">' . $key->placa . '</div>
		</div>
		</div>
		</div>
		</div>
		<div class="col-md-10">
		<blockquote class="quote-secondary">
		<div class="table-responsive">
		<table class="table table-sm" align="left"
		style="font-size: 13px;">
		<tr>
		<th>Cliente:</th>
		<td>' . $key->nombre_cliente . '</td>
		<th>Encargado:</th>
		<td>' . $key->nombre_encargado . '</td>
		</tr>
		<tr>
		<th>Vehículo:</th>
		<td>' . $key->vh . '</td>
		<th>Fecha/Hora cita:</th>
		<td>' . $key->fecha_cita . '</td>
		</tr>
		<tr>
		<th>Bahia/Tecnico:</th>
		<td>' . $key->descripcion_bahia . '</td>
		<th>Notas:</th>
		<td>' . $key->notas . '</td>
		</tr>
		</table>
		</div>
		<div class="row" align="center">
		<div class="col-md-12"><a href="#"
		onclick="marcar_entrada(' . $key->id_cita . ',\'' . $key->fecha_hora_ini . '\');"
		class="btn btn-sm btn-info">Marcar entrada</a></div>
		</div>
		</blockquote>

		</div>
		</div>
		</div>
		</div>
		';
		}
	}

	public function insertVehSinCita()
	{
		$this->load->model('talleres');
		$placa = $this->input->GET('placa');
		$cliente = $this->input->GET('cliente');
		$motivo = $this->input->GET('motivo');
		$sede = $this->input->GET('bod');

		$op = $this->talleres->insert_vh_sin_cita($placa, $cliente, $motivo, $sede);
		if ($op) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * METODO PAR VER RANKIN DE LOS TECNICOS
	 * ANDRES GOMEZ 
	 * 2022-01-05
	 */
	public function traer_rankig_tecnicos_trimestral()
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

			$mes_actual = "10,11,12";
			//$ano_actual = date('Y');
			$ano_actual = "2021";
			$bodega_actual = "11,1,16,8,19,6,7";

			$rankin_ventas_trimestral = $this->talleres->ventas_tec_ranking_trimestral($bodega_actual, $mes_actual, $ano_actual);


			$arr_user = array(
				'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass,
				'rankin' => $rankin_ventas_trimestral,

			);
			//abrimos la vista
			$this->load->view("ranking/Informe_trimestral", $arr_user);
		}
	}
}
