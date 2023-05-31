<?php

use Mpdf\Tag\Input;

/**
 * Clase controlador del modulo administracion by said
 */
class Administracion extends CI_Controller
{

	public function Ausentismo()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//se cargan los modelos
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('sedes');
			$this->load->model('ausentismos');
			$this->load->model('AdministracionCodiesel');
			/* Obtenemos la fecha y hora del servidor para validar, a la hora de crear un ausentismo */
			$fecha_actual = $this->ausentismos->getFecha();
			/* Valida si la fecha actual es festivo y devuelve 1 si es festivo... */
			$boolDiaEsFestivo = $this->ausentismos->diasFestivos($fecha_actual->fecha_actual);



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
			$anio = date('Y');
			$dataTiempoAusentismos = $this->AdministracionCodiesel->valTiempoAusentismos($anio, $usu)->result();
			$tiempo = 0;
			if (count($dataTiempoAusentismos) != 0) {
				foreach ($dataTiempoAusentismos as $key) {
					$tiempo = $key->minutos;
				}
			} else {
				$tiempo = -1;
			}
			//sedes bodegas
			$sedes_bod = $this->sedes->get_sedes_de_bodegas();
			//obtenemos los ausentismos por el usuario
			$ausentismos = $this->ausentismos->get_ausentismos_by_user($usu);
			//parseamos a json
			$data_ausen[] = array();
			foreach ($ausentismos->result() as $key) {
				$color = "";
				if ($key->autorizacion == "1") {
					$color = "#009C13";
				} elseif ($key->autorizacion == "2") {
					$color = "#FF0000";
				} else {
					$color = "#0064FF";
				}
				$data_ausen[] = array('id' => $key->id_ausen, 'title' => $key->hora_ini . '-' . $key->titulo, 'start' => $key->fecha_ini, 'end' => $key->fecha_fin, 'descripcion' => $key->descripcion, 'color' => $color);
			}
			//print_r($ausen_json);die;
			$arr_user = array(
				'userdata' => $userinfo,
				'menus' => $allmenus,
				'perfiles' => $allperfiles,
				'pass' => $pass,
				'id_usu' => $id_usu,
				'sedes_bod' => $sedes_bod,
				'ausentismos' => json_encode($data_ausen),
				'dia_actual' => $boolDiaEsFestivo,
				'fecha_actual' => $fecha_actual->fecha_actual,
				'tiempoAusen' => $tiempo
			);
			//abrimos la vista
			if (strtotime(date('G:i')) <= strtotime('6:30') || strtotime(date('G:i')) >= strtotime("20:00")) {
				$this->load->view("administracion/index", $arr_user);
			} else {
				$this->load->view("administracion/ausentismos", $arr_user);
			}
		}
	}

	public function HorasToMin($hora)
	{
		$arrHora = explode(":", $hora);
		return ($arrHora[0] * 60) + $arrHora[1];
	}

	public function MinToHoras($min)
	{
		$horas = $min / 60;
		$minutos = $min % 60;
		if ($minutos <= 9) {
			$minutos = "0" . $minutos;
		}
		return floor($horas) . " Horas con $minutos Minutos";
	}

	public function getMinDisponibles($nit)
	{
		$this->load->model('AdministracionCodiesel');
		$anio = date("Y");
		/* $nit = $this->session->userdata('user'); */
		$data = $this->AdministracionCodiesel->valTiempoAusentismos($anio, $nit);
		if (count($data->result())  != 0) {
			return $data->result();
		} else {
			$dataInsert = array(
				'empleado' => $nit,
				'anio' => $anio,
				'minutos' => 480
			);
			if ($this->AdministracionCodiesel->insertTiempoAsentismos($dataInsert)) {
				$data = $this->AdministracionCodiesel->valTiempoAusentismos($anio, $nit);
				return $data->result();
			}
		}
	}

	public function calcularTiempoRestante()
	{
		$horaAusen = $this->input->get('horaAusen');
		$nit = $this->input->get('nit');
		$dataMinDis = $this->getMinDisponibles($nit);
		foreach ($dataMinDis as $key) {
			$minUsu = $key->minutos;
		}
		$minSoli = $this->HorasToMin($horaAusen);

		$totalMin = $minUsu - $minSoli;
		if ($totalMin < 0) {
			echo "Has superdo el tiempo otorgado por CODIESEL, debes especificar como recuperarás el tiempo solicitado";
		} else {
			$horasTo = $this->MinToHoras($totalMin);
			echo "De la chequera de tiempo otorgada por CODIESEL te quedarian $horasTo";
		}
	}

	public function new_ausentismo()
	{
		//cargamos los modelos
		$this->load->model('ausentismos');
		$this->load->model('usuarios');
		$this->load->model('sedes');

		//TRAEMOS LAS VARIABLES
		$empleado = $this->session->userdata('user');
		$fecha_ini = $this->input->GET("fecha_ini");
		$hora_ini = $this->input->GET("hora_ini");
		$fecha_fin = $this->input->GET("fecha_fin");
		$hora_fin = $this->input->GET("hora_fin");
		$area = $this->input->GET("area");
		$cargo = $this->input->GET("cargo");
		$sede = $this->input->GET("sede");
		$motivo = $this->input->GET("motivo");
		$descripcion = $this->input->GET("descripcion");
		$horaAusen = $this->input->GET("horaAusen");
		$diaAusen = $this->input->GET("diaAusen");
		$recuperarTiempo = $this->input->get('recuperarTiempo');
		//obtenemos el nombre del empleado
		$nombre = $this->usuarios->getUserByNit($empleado)->nombres;
		/** VALIDAMOS SI ES PERMISO PERSONAL Y MENOS DE 1 DIA */
		/*if ($motivo == "Personal" && $diaAusen >= 1) {
			echo "war2";
			die;
		}*/
		/* if ($motivo == "Personal") {
			$dataMinDis = $this->getMinDisponibles();
			foreach ($dataMinDis as $key) {
				$minUsu = $key->minutos;
			}
			$minSoli = $this->HorasToMin($horaAusen);

			$totalMin = $minUsu - $minSoli;

			if ($totalMin < 0) {
				echo "war3";
				die;
			}
		} */
		//creamos un array con los datos recibidos
		$data = array(
			'empleado' => $empleado,
			'cargo_emp' => $cargo,
			'sede' => $sede,
			'area' => $area,
			'fecha_ini' => $fecha_ini,
			'hora_ini' => $hora_ini,
			'fecha_fin' => $fecha_fin,
			'hora_fin' => $hora_fin,
			'descripcion' => $descripcion,
			'autorizacion' => 0,
			'motivo' => $motivo,
			'titulo' => $motivo,
			'modo_recuperar_tiempo' => $recuperarTiempo
		);
		if ($this->ausentismos->insert_ausentismo($data)) {
			$msn = "El empleado " . $nombre . " con cedula " . $empleado . " de la sede " . $sede . " solicita un ausentismo por motivo  " .
				$motivo . " descripcion del motivo " . $descripcion . " desde " . $fecha_ini . " hora " . $hora_ini . " hasta " . $fecha_fin . " hora " . $hora_fin . ". ¿Autoriza usted el ausentismo?";
			//echo $mensaje;die;
			// Load PHPMailer library
			//obtenemos el id del ultimo ausentismo del usuario
			$id_ausen_usu = $this->ausentismos->get_ultimo_ausen_by_user($empleado)->id_ausen;
			$this->load->library('phpmailer_lib');
			$empleado_jefe = $this->ausentismos->get_empleado_jefes_by_empleado($empleado);
			foreach ($empleado_jefe->result() as $key) {
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
				$correo->addAddress($key->correo);
				//$correo->addAddress("saidandresmix01@gmail.com");
				//$correo->addAddress("programador3@codiesel.co");
				$correo->Username = "no-reply@codiesel.co";
				$correo->Password = "wrtiuvlebqopeknz";
				$correo->SetFrom("no-reply@codiesel.co", "CODIESEL S.A");
				$correo->Subject = "Solicitud ausentismo " . $nombre;

				$mensaje = '<!DOCTYPE html>
				<html>
				<head>
				<meta charset="utf-8">
				<title></title>
				<style type="text/css">
									#boton_aceptar{
				background-color: #199319;
				color: white;
				padding: 15px 25px;
				text-decoration: none;
				}
									#boton_rechasar{
					background-color: red;
					color: white;
					padding: 15px 25px;
					text-decoration: none;
				}
				</style>
				</head>
				<body>
				<div style="padding: 20px;">
				<strong>' . $msn . '</strong>
				</div>
				<div style="padding: 20px;display: inline-block;">
				<a href="' . base_url() . '/administracion/autorizar_ausen?id=' . $id_ausen_usu . '&nit_user_resp=' . $key->nit_jefe . '" id="boton_aceptar">Aceptar</a>
				<a href="' . base_url() . '/administracion/rechazar_ausen?id=' . $id_ausen_usu . '&nit_user_resp=' . $key->nit_jefe . '" id="boton_rechasar">Rechazar</a>
				</div>
				</body>
				</html>';
				$correo->MsgHTML($mensaje);
				if (!$correo->Send()) {
					echo "err";
				}
			}
			echo "ok";
			//die;
		} else {
			echo "err";
		}
	}

	public function ver_info_evento()
	{
		//cargamos los modelos
		$this->load->model('ausentismos');
		//traemos las variables
		$id = $this->input->GET('id');
		$ausentismos = $this->ausentismos->get_ausentismos_by_id($id);
		//print_r($ausentismos->result());die;
		foreach ($ausentismos->result() as $key) {
			echo '
		<form role="form">
		<div class="card-body">
		<div class="row">
		<div class="col">
		<div class="form-group">
		<label for="cargo">Fecha en la que se ausentará</label>
		<input type="text" class="form-control" id="fecha_ini_ver" value="' . $key->fecha_ini . '">
		</div>							
		</div>
		<div class="col">
		<div class="form-group">
		<label for="cargo">Hora inicio ausentismo</label>
		<input type="text" class="form-control" id="hora_ini_ver" value="' . $key->hora_ini . '">
		</div>
		</div>
		</div>
		<div class="row">
		<div class="col">
		<div class="form-group">
		<label for="cargo">Fecha En Que Termina El Ausentismo</label>
		<input type="text" class="form-control" id="fecha_fin_ver" value="' . $key->fecha_fin . '">
		</div>
		</div>
		<div class="col">
		<div class="form-group">
		<label for="cargo">Hora En Que Termina El Ausentismo</label>
		<input type="text" class="form-control" id="hora_fin_ver" value="' . $key->hora_fin . '">
		</div>
		</div>
		</div>
		<div class="row">
		<div class="col">
		<div class="form-group">
		<label for="cargo">Area donde labora</label>
		<input type="text" class="form-control" id="area_ver" value="' . $key->area . '">
		</div>
		</div>
		<div class="col">
		<div class="form-group">
		<label for="cargo">Cargo del empleado</label>
		<input type="text" class="form-control" id="cargo_emp_ver" value="' . $key->cargo_emp . '">
		</div>
		</div>
		</div>
		<div class="row">
		<div class="col">
		<div class="form-group">
		<label for="cargo">Sede</label>
		<input type="text" class="form-control" id="cargo_emp_ver" value="' . $key->sede . '">
		</div>
		</div>
		<div class="col">
		<div class="form-group">
		<label for="cargo">Motivo del permiso</label>
		<input type="text" class="form-control" id="motivo_ver" value="' . $key->motivo . '">
		</div>
		</div>
		</div>
		<div class="row">
		<label for="descrp">Describe el motivo del permiso</label>
		<textarea class="form-control" id="descripcion_ver">' . $key->descripcion . '</textarea>
		</div>

		</div>
		<!-- /.card-body -->
		</form>
		';
		}
	}

	public function autorizar_ausen()
	{
		//llamamos los modelos
		$this->load->model('ausentismos');
		//llamamos las variables
		$id_ausen = $this->input->GET('id');
		$id_user_resp = $this->input->GET('nit_user_resp');
		$data_ause = $this->ausentismos->get_ausen_by_id($id_ausen);

		$hi = new DateTime($data_ause->hora_ini);
		$hf = new DateTime($data_ause->hora_fin);
		$intervalo = $hi->diff($hf);
		$horaAusen = $intervalo->format('%H:%i');

		$motivo = $data_ause->motivo;
		$empleado = $data_ause->empleado;
		if ($data_ause->autorizacion != 0) {
			echo "<h3>Este Ausentismo ya tuvo respuesta</h3>
				<h4>Puedes cerrar la pestaña del navegador</h4>";
		} else {
			if ($this->ausentismos->autorizar_ausen($id_ausen, $id_user_resp)) {
				/* if ($motivo == "Personal") {
					$dataMinDis = $this->getMinDisponibles($empleado);
					foreach ($dataMinDis as $key) {
						$minUsu = $key->minutos;
					}
					$minSoli = $this->HorasToMin($horaAusen);

					$totalMin = $minUsu - $minSoli;

					if ($totalMin < 0) {
						echo "war3";
						die;
					} else {
						$anio = date("Y");
						$whereUpdateAusen = array(
							'empleado' => $empleado,
							'anio' => $anio,
						);
						$dataUpdateAusen = array(
							'minutos' => $totalMin
						);

						if (!$this->AdministracionCodiesel->updateTiempoAusentismos($dataUpdateAusen, $whereUpdateAusen)) {
							echo "err";
							die;
						}
					}
				} */
				echo "<h3>Ausentismo autorizado correctamente</h3>
			<h4>Puedes cerrar la pestaña del navegador</h4>";
			} else {
				echo "<h3>Ausentismo ya fue autorizado.</h3>
			<h4>Puedes cerrar la pestaña del navegador</h4>";
			}
		}
	}

	public function rechazar_ausen()
	{
		//llamamos los modelos
		$this->load->model('ausentismos');
		//llamamos las variables
		$id_ausen = $this->input->GET('id');
		$id_user_resp = $this->input->GET('nit_user_resp');
		$data_ause = $this->ausentismos->get_ausen_by_id($id_ausen)->autorizacion;
		if ($data_ause != 0) {
			echo "<h3>Este Ausentismo ya tuvo respuesta</h3>
		<h4>Puedes cerrar la pestaña del navegador</h4>";
		} else {
			if ($this->ausentismos->rechazar_ausen($id_ausen, $id_user_resp)) {
				echo "<h3>Ausentismo rechazado correctamente</h3>
			<h4>Puedes cerrar la pestaña del navegador</h4>";
			} else {
				echo "<h3>Ausentismo ya fue negado.</h3>
			<h4>Puedes cerrar la pestaña del navegador</h4>";
			}
		}
	}
	/* metodo para la manipulacion del Informe de ausentismo */
	public function Informe_ausentismo()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//se cargan los modelos
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('sedes');
			$this->load->model('ausentismos');

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
			//traer todos los ausentismos

			$datos = $this->ausentismos->taerDatosAusentismo($usu);
			//sedes bodegas
			$sedes_bod = $this->sedes->get_sedes_de_bodegas();
			//obtenemos los ausentismos por el usuario
			$ausentismos = $this->ausentismos->get_ausentismos_by_user($usu);
			//print_r($ausen_json);die;
			$arr_user = array('datos' => $datos, 'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'sedes_bod' => $sedes_bod);
			//abrimos la vista
			$this->load->view("administracion/Informe_ausentismo", $arr_user);
		}
	}



	public function traerausentimosSede()
	{
		//cargamos los modelos
		$this->load->model('ausentismos');
		//traemos las variables
		$codigo = $this->input->GET('codigo');
		$sedes = $this->input->GET('sedes');
		$fechauno = $this->input->GET('FechaUno');
		$fechados = $this->input->GET('FechaDos');
		$areas = $this->input->GET('areas');
		$datos = $this->ausentismos->TraerDatosSede($codigo, $sedes, $fechauno, $fechados, $areas);

		echo "
	<div class='card table-responsive'>
	<div class='card-body'>
	<table id='tbSede' class='table table-sm table-responsive-sm table-bordered tabla-hover' style='font-size: 12px;'>
	<label class='col-lg-12 text-center lead'></label>
	<a style=' text-shadow: 5px 3px 4px #000000' href='#' onclick='imprimedos()' class=' btn btn-success shadow'>Descargar &nbsp; <i class='far fa-file-excel'></i></a>
	<thead class='table-dark'>
	<tr>
	<th style='white-space: nowrap; width: 1px;' scope='col'>Nombre</th>
	<th style='white-space: nowrap; width: 1px;' scope='col'>Documento</th>
	<th style='white-space: nowrap; width: 1px;' scope='col'>Cargo</th>
	<th class='text-center' style='white-space: nowrap; width: 1px;' scope='col'>Sede</th>
	<th class='text-center' style='white-space: nowrap; width: 1px;' scope='col'>Area</th>
	<th class='text-center' style='white-space: nowrap; width: 1px;' scope='col'>Fecha inicio ausentismo</th>
	<th class='text-center' style='white-space: nowrap; width: 1px;' scope='col'>Hora inicio ausentismo</th>
	<th class='text-center' style='white-space: nowrap; width: 1px;' scope='col'>Fecha fin ausentismo</th>
	<th class='text-center' style='white-space: nowrap; width: 1px;' scope='col'>Hora fin ausentismo</th>
	<th style='white-space: nowrap; width: 1px;' scope='col'>Motivo</th>
	<th style='white-space: nowrap; width: 1px;' scope='col'>Descripcion</th>
	<th class='text-center' style='white-space: nowrap; width: 1px;' scope='col'>Estado</th>
	<th style='white-space: nowrap; width: 1px;' scope='col'>Quien autoriza</th>
	</tr>
	</thead>
	<tbody>
	";
		$colores = "";
		foreach ($datos->result() as $key) {
			if ($key->Estado == "Autorizado") {
				$colores = "#06C858";
			} else if ($key->Estado == "Negado") {
				$colores = "#BB3128";
			} else if ($key->Estado == 0) {
				$colores = "#E4D40F";
			}
			echo "
		<tr>
		<td class='text-left'style='font-weight:normal; white-space: nowrap; width: 1px;'>" . $key->nombres . "</td>
		<td class='text-left'style='font-weight:normal; white-space: nowrap; width: 1px;'>" . $key->empleado . "</td>
		<td class='text-left'style='font-weight:normal; white-space: nowrap; width: 1px;'>" . $key->cargo_emp . "</td>
		<td class='text-center'>" . $key->sede . "</td>
		<td class='text-center'>" . $key->area . "</td>
		<td class='text-center'>" . $key->fecha_ini . "</td>
		<td class='text-center'>" . $key->hora_ini . "</td>
		<td class='text-center'>" . $key->fecha_fin . "</td>
		<td class='text-center'>" . $key->hora_fin . "</td>
		<td>" . $key->motivo . "</td>
		<td>" . $key->descripcion . "</td>
		<td style = 'background-color:" . $colores . "' class='text-center'><strong>" . $key->Estado . "</strong></td>
		<td class='text-left'style='font-weight:normal; white-space: nowrap; width: 1px;'>" . $key->nombre_jefe . "</td>
		</tr>
		";
		}
	}


	/********************************************* Informe HORAS EXTRAS********************************************/

	public function Informe_TiempoSuplementario()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//se cargan los modelos
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('sedes');
			$this->load->model('administracionCodiesel');

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
			//traer todos los ausentismos
			$datos = $this->administracionCodiesel->listarHorasExtras($usu);
			$empleados = $this->usuarios->getUserActivos();
			/* print_r($empleados->result());die; */
			//sedes bodegas
			$sedes_bod = $this->sedes->get_sedes_de_bodegas();
			//print_r($ausen_json);die;
			$arr_user = array('empleados' => $empleados, 'datos' => $datos, 'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'sedes_bod' => $sedes_bod);
			//abrimos la vista
			$this->load->view("administracion/InformeTiempoSuplementario", $arr_user);
		}
	}

	public function filtroHoraExtra()
	{
		//cargamos los modelos
		$this->load->model('administracionCodiesel');
		//traemos las variables
		$codigo = $this->input->POST('userrExtra');
		$sedes = $this->input->POST('extraSede');
		$areas = $this->input->POST('extraAreas');
		$año = $this->input->POST('año');
		$mes = $this->input->POST('mes');
		$emp = $this->input->POST('emp');
		$datos = $this->administracionCodiesel->filtroHorasExtras($codigo, $sedes, $areas, $año, $mes, $emp);

		echo "
	<div class='card table-responsive'>
	<div class='card-body'>
	<table id='tbextrasdos' class='table table-sm table-responsive-sm table-bordered tabla-hover' style='font-size: 12px;'>
	<label class='col-lg-12 text-center lead'></label>
	<a style=' text-shadow: 5px 3px 4px #000000' href='#' onclick='infoextrados()' class=' btn btn-success shadow'>Descargar &nbsp; <i class='far fa-file-excel'></i></a>
	<thead class='table-dark'>
	<tr>
		<th style='white-space: nowrap; width: 1px;' scope='col'>Nombre del Jefe</th>
		<th class='text-center' style='white-space: nowrap; width: 1px;' scope='col'>Nombre del Empleado</th>
		<th class='text-center' style='white-space: nowrap; width: 1px;' scope='col'>Sede</th>
		<th class='text-center' style='white-space: nowrap; width: 1px;' scope='col'>Area</th>
		<th class='text-center' style='white-space: nowrap; width: 1px;' scope='col'>Cargo</th>
		<th class='text-center' style='white-space: nowrap; width: 1px;' scope='col'>Fecha de Inicio</th>
		<th class='text-center' style='white-space: nowrap; width: 1px;' scope='col'>Hora de Inicio</th>
		<th class='text-center' style='white-space: nowrap; width: 1px;' scope='col'>Hora de salida</th>
		<th class='text-center' style='white-space: nowrap; width: 1px;' scope='col'>Fecha de Solicitud</th>
		<th style='white-space: nowrap; width: 1px;' scope='col'>Descripción</th>
		<th class='text-center' style='white-space: nowrap; width: 1px;' scope='col'>Autorizacion</th>
	</tr>
	</thead>
	<tbody>
	";
		$color = "";
		foreach ($datos->result() as $key) {
			if ($key->autorizacion == 'Aprobado') {
				$color = "#82E0AA";
			} else {
				$color = "#E4320B";
			}
			echo "
		<tr>
		<td class='text-left'style='font-weight:normal; white-space: nowrap; width: 1px;'>" . $key->nombrejefe . "</td>
			<td class='text-left'style='font-weight:normal; white-space: nowrap; width: 1px;'>" . $key->nombreempleado . "</td>
			<td class='text-center'>" . $key->sede . "</td>
			<td class='text-center'>" . $key->area . "</td>
			<td class='text-center'>" . $key->cargo . "</td>
			<td class='text-center'>" . $key->fecha_ini . "</td>
			<td class='text-center'>" . $key->hora_ini . "</td>
			<td class='text-center'>" . $key->hora_fin . "</td>
			<td class='text-center'>" . $key->fecha_solicitud . "</td>
			<td class='text-left'style='font-weight:normal; white-space: nowrap; width: 1px;'>" . $key->descripcion . "</td>
			<td class='text-center text-white' style = 'background-color:" . $color . "'><strong> " . $key->autorizacion . " </strong></td>
		</tr>
		";
		}
	}

	/********************************************* HORAS EXTRA********************************************/

	public function solicitud_horas_extra()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('AdministracionCodiesel');

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
			/*TRAEMOS LAS SOLICITUDES DE LAS HORAS EXTRA*/
			$solicitudes_he = $this->AdministracionCodiesel->get_horas_extra_by_jefe($usu);
			$data_solicitudes[] = array();
			foreach ($solicitudes_he->result() as $key) {
				$color = "";
				if ($key->autorizacion == "1") {
					$color = "#009C13";
				} elseif ($key->autorizacion == "2") {
					$color = "#FF0000";
				} else {
					$color = "#0064FF";
				}
				$data_solicitudes[] = array('id' => $key->id_solicitud, 'title' => $key->hora_ini . '-' . 'Horas extra', 'start' => $key->fecha_ini, 'end' => $key->fecha_ini, 'descripcion' => $key->descripcion, 'color' => $color);
			}
			$emp_jefes = $this->AdministracionCodiesel->get_empleados_jefes($usu);
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'emp_jefes' => $emp_jefes, 'horas_extra' => json_encode($data_solicitudes));
			//abrimos la vista
			$this->load->view("administracion/solicitud_horas_extra", $arr_user);
		}
	}

	public function ver_info_horas_extra()
	{
		//cargamos los modelos
		$this->load->model('AdministracionCodiesel');
		//traemos las variables
		$id = $this->input->GET('id');
		$ausentismos = $this->AdministracionCodiesel->get_horas_extra_by_id($id);
		//print_r($ausentismos->result());die;
		foreach ($ausentismos->result() as $key) {
			echo '<form role="form">
					<div class="card-body">
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="cargo">Fecha de inicio jornada adicional</label>
									<input type="date" class="form-control" id="fecha_ini"  disabled value="' . $key->fecha_ini . '">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="cargo">Hora de inicio jornada adicional</label>
									<input type="text" class="form-control" id="hora_ini"  disabled value="' . $key->hora_ini . '">
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="cargo">Hora de fininalización jornada adicional</label>
									<input type="text" class="form-control" id="hora_fin"  disabled value="' . $key->hora_fin . '">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="exampleInputPassword1">Area donde labora</label>
									<input type="text" class="form-control" id="area"  disabled value="' . $key->area . '">
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="cargo">Cargo del empleado</label>
									<input type="text" class="form-control" id="cargo_emp" disabled value="' . $key->cargo . '">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="exampleInputPassword1">Sede</label>
									<input type="text" class="form-control" id="sede"  disabled value="' . $key->sede . '">
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="exampleInputPassword1">Empleado</label>
									<input type="text" class="form-control" id="empleado"  disabled value="' . $key->nombres . '">
								</div>
							</div>
						</div>
						<div class="row">
							<label for="descrp">Describe el motivo de la solicitud</label>
							<textarea class="form-control" id="descripcion" required="true">' . $key->descripcion . '</textarea>
						</div>

					</div>
					<!-- /.card-body -->
				</form>';
		}
	}

	public function add_horas_extra()
	{
		/*LLAMAMOS LOS MODELOS*/
		$this->load->model('AdministracionCodiesel');
		$this->load->model('usuarios');
		/*TRAEMOS LOS DATOS POR AJAX*/
		$fecha_ini = $this->input->GET('fecha_ini');
		$hora_ini = $this->input->GET('hora_ini');
		$hora_fin = $this->input->GET('hora_fin');
		$area = $this->input->GET('area');
		$cargo = $this->input->GET('cargo');
		$sede = $this->input->GET('sede');
		$descripcion = $this->input->GET('descripcion');
		$empleado = $this->input->GET('empleado');
		$jefe = $this->session->userdata('user');
		$fecha_solicitud = date('d-m-Y H:i:s');

		$nombre_emp = $this->usuarios->getUserByNit($empleado)->nombres;
		$nombre_jefe = $this->usuarios->getUserByNit($jefe)->nombres;
		$data = array('nit_empleado' => $empleado, 'nit_jefe' => $jefe, 'cargo' => $cargo, 'sede' => $sede, 'area' => $area, 'fecha_ini' => $fecha_ini, 'hora_ini' => $hora_ini, 'descripcion' => $descripcion, 'autorizacion' => 0, 'fecha_solicitud' => $fecha_solicitud, 'hora_fin' => $hora_fin);
		if ($this->AdministracionCodiesel->insert_horas_extra($data)) {
			$msn = "El Jefe " . $nombre_jefe . " Solicita que el trabajador " . $nombre_emp . " de la sede " . $sede . " trabaje en el horario de las " . $hora_ini . " hasta las " . $hora_fin . " en la fecha " . $fecha_ini . " por motivo de: " . $descripcion . " ¿Autoriza?";
			//echo $mensaje;die;
			// Load PHPMailer library
			//obtenemos el id del ultimo ausentismo del usuario
			$id_so = $this->AdministracionCodiesel->get_ultima_solicitud_empleado($empleado)->id_solicitud;
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
			//$correo->addAddress("programador3@codiesel.co");
			$correo->addAddress("personal@codiesel.co");
			$correo->Username = "no-reply@codiesel.co";
			$correo->Password = "wrtiuvlebqopeknz";
			$correo->SetFrom("no-reply@codiesel.co", "CODIESEL S.A");
			$correo->Subject = "Solicitud para trabajar en jornada adicional " . $nombre_jefe;

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
		">Solicitud para trabajar en jornada adicional</h2>
		<p class="card-text" style="padding: 20px;

		font-size: 1rem;
		line-height: 1.5;
		word-wrap: break-word;
		margin-bottom: 35px;">
		' . $msn . '
		</p>
		<a href="' . base_url() . 'administracion/autorizar_he?id=' . $id_so . '&auto=1" class="btn btn-warning" style=" padding: 10px 15px;
		text-decoration: none;
		background-color: #ffc107;
		color: rgb(71, 68, 68);">Aceptar</a>
		<a href="' . base_url() . 'administracion/autorizar_he?id=' . $id_so . '&auto=2" class="btn btn-dark" style=" padding: 10px 15px;
		text-decoration: none;
		background-color: #343a40;
		color: white;border-color: white;">Rechazar</a>
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
			}
			echo "ok";
			//die;
		} else {
			echo 'err';
		}
	}

	public function autorizar_he()
	{
		//llamamos los modelos
		$this->load->model('AdministracionCodiesel');
		//llamamos las variables
		$id_ausen = $this->input->GET('id');
		$autorizacion = $this->input->GET('auto');
		$id_so = $this->AdministracionCodiesel->get_ultima_solicitud($id_ausen)->autorizacion;
		$nit_emp = $this->AdministracionCodiesel->get_ultima_solicitud($id_ausen)->nit_empleado;
		$nit_jefe = $this->AdministracionCodiesel->get_ultima_solicitud($id_ausen)->nit_jefe;
		$mail_emp = $this->AdministracionCodiesel->get_mail_empleado($nit_emp)->mail;
		$mail_jefe = $this->AdministracionCodiesel->get_mail_jefe($nit_jefe)->correo;
		//echo $mail_emp;die;
		if ($id_so != 0) {
			echo "<h3>Esta Solicitud ya tuvo respuesta</h3>
		<h4>Puedes cerrar la pestaña del navegador</h4>";
		} else {
			if ($this->AdministracionCodiesel->autorizar_he($autorizacion, $id_ausen)) {
				if ($autorizacion == 1) {
					$msn = "Señor empleado, su Solicitud de trabajo en horario adicional fue APROBADA.";
					echo "<h3>Solicitud autorizada correctamente</h3>
				<h4>Puedes cerrar la pestaña del navegador</h4>";
				} elseif ($autorizacion == 2) {
					$msn = "Señor empleado, su Solicitud de trabajo en horario adicional fue RECHAZADA.";
					echo "<h3>Solicitud rechazada correctamente</h3>
				<h4>Puedes cerrar la pestaña del navegador</h4>";
				}
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
				$correo->addAddress($mail_emp);
				$correo->addAddress($mail_jefe);
				$correo->Username = "no-reply@codiesel.co";
				$correo->Password = "wrtiuvlebqopeknz";
				$correo->SetFrom("no-reply@codiesel.co", "CODIESEL S.A");
				$correo->Subject = "Respuesta Solicitud trabajo en jornada adicional ";

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
			">Solicitud para trabajar en jornada adicional</h2>
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
				}
			} else {
				echo "<h3>La Solicitud ya fue contestada.</h3>
			<h4>Puedes cerrar la pestaña del navegador</h4>";
			}
		}
	}
	/*------------------------------------------------controlador para visalizar listado de ausentismos en el dia------------------------------------------ */
	public function listado_ausentismo()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//se cargan los modelos
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('sedes');
			$this->load->model('ausentismos');

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
			//traer todos los ausentismos

			$datos = $this->ausentismos->taerDatosAusentismo($usu);
			//sedes bodegas
			$sedes_bod = $this->sedes->get_sedes_de_bodegas();
			//obtenemos los ausentismos por el usuario
			$sede = "";
			$perfil =  $this->session->userdata('perfil');
			$perfil;
			if ($perfil == 7) {
				$sede = 'Giron';
			} elseif ($perfil == 45) {
				$sede = 'Bocono';
			} else if ($perfil == 20) {
				$sede = 'Giron';
			}
			$ausentismos = $this->ausentismos->ausentimsodia($sede);
			//print_r($ausen_json);die;
			$arr_user = array('datos' => $datos, 'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'sedes_bod' => $sedes_bod, 'ausentismos' => $ausentismos);
			//abrimos la vista
			$this->load->view("ListaAusentismo/index", $arr_user);
		}
	}

	public function insetarrespuestaporteria()
	{
		$this->load->model('ausentismos');
		$respuesta = $this->input->POST('dato');
		$id = $this->input->POST('id');
		$datos = $this->ausentismos->insertarconfimacionportero($respuesta, $id);
		if ($datos) {
			echo "Ausentismo Exitoso";
		} else {
			echo "Error";
		}
	}

	/*------------------------------------------------controlador para visalizar listado de horas extras en el dia------------------------------------------ */

	public function listadodiahorasextras()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//se cargan los modelos
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('sedes');
			$this->load->model('AdministracionCodiesel');

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

			//sedes bodegas
			$sedes_bod = $this->sedes->get_sedes_de_bodegas();
			//obtenemos los ausentismos por el usuario
			$perfil =  $this->session->userdata('perfil');
			$sede = "";
			if ($perfil == 7) {
				$sede = 'Giron';
			} elseif ($perfil == 45) {
				$sede = 'Bocono';
			} else if ($perfil == 20) {
				$sede = 'Giron';
			}
			$extras = $this->AdministracionCodiesel->verhorasextrasdia($sede);

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'sedes_bod' => $sedes_bod, 'extras' => $extras);
			//abrimos la vista
			$this->load->view("listahorasExtras/index", $arr_user);
		}
	}



	public function respuestaPorteria()
	{
		$this->load->model('AdministracionCodiesel');
		$confirmar = $this->input->POST('dato');
		$id = $this->input->POST('id');
		$datos = $this->ausentismos->respuestaPorteria($confirmar, $id);
		if ($datos) {
			echo "Ausentismo Exitoso";
		} else {
			echo "Error";
		}
	}

	/********************************** EVALUACION JEFE A EMPLEADO ****************************************************************************************/

	public function evaluacion_jefe_empleado()
	{
		/* validamos si hay datos de session 
		 * si no hay se redireccion al login y se destruye la session
		 */
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('AdministracionCodiesel');
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			$allusers = $this->usuarios->getAllUsers();
			/*Obtenemos las preguntas de la evaluacion*/
			$data_preguntas = $this->AdministracionCodiesel->get_preguntas_evaluacion_jefe();
			/*Obtenemos los empleados del jefe*/
			$emp_jefes = $this->AdministracionCodiesel->get_empleados_jefes($usu);
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'alluser' => $allusers, 'preguntas' => $data_preguntas, 'empleados' => $emp_jefes);
			//abrimos la vista
			$this->load->view('administracion/evaluacion_jefe_empleado', $arr_user);
		}
	}

	public function insert_respuestas()
	{
		//cargamos los modelos
		$this->load->model('AdministracionCodiesel');
		//traemos la informacion 
		/*$cargo = $this->input->get('cargo');
		$empleado = $this->input->get('empleado');
		$sede = $this->input->get('sede');
		$area = $this->input->get('area');
		$data_insert = array('' => , );*/
		$empleado = $this->input->get('empleado');
		$pregunta = $this->input->get('respuesta');
		$respuesta = $this->input->get('respuesta');
		$jefe = $this->session->userdata('user');
		$fecha = date('Y-m-d');
		$data_insert = array('id_pregunta' => $pregunta, 'empleado' => $empleado, 'respuesta' => $respuesta, 'fecha_resp' => $fecha, 'jefe' => $jefe);
		if ($this->AdministracionCodiesel->insert_respuesta($data_insert)) {
			echo "ok";
		} else {
			echo "err";
		}
	}

	public function insert_encabeza_evaluacion()
	{
		//cargamos los modelos
		$this->load->model('AdministracionCodiesel');
		//traemos la informacion 
		$cargo = $this->input->get('cargo');
		$empleado = $this->input->get('empleado');
		$sede = $this->input->get('sede');
		$area = $this->input->get('area');
		$observaciones = $this->input->GET('observaciones');
		$jefe = $this->session->userdata('user');
		$fecha = date('Y-m-d');
		$data_insert = array('empleado' => $empleado, 'jefe' => $jefe, 'area' => $area, 'sede' => $sede, 'fecha' => $fecha, 'observaciones' => $observaciones);
		if ($this->AdministracionCodiesel->insert_encabeza_resp($data_insert)) {
			echo "err";
		} else {
			echo "ok";
		}
	}

	public function Informe_pausas_activas()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//se cargan los modelos
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
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

			$allusers = $this->usuarios->getAllUsers();
			$sede = $this->sedes->get_sedes_de_bodegas();

			//print_r($ausen_json);die;
			$arr_user = array(
				'sede' => $sede,
				'allUser' => $allusers,
				'userdata' => $userinfo,
				'menus' => $allmenus,
				'perfiles' => $allperfiles,
				'pass' => $pass,
				'id_usu' => $id_usu
			);
			//abrimos la vista
			$this->load->view("administracion/Informe_pausas_activas", $arr_user);
		}
	}

	public function info_pausas_activas()
	{

		//cargamos los modelos
		$this->load->model('Usuarios');

		$empleado = $this->input->POST('empleado');
		$sede = $this->input->POST('sede');
		$fechaDia = $this->input->POST('fechaDia');
		$fechaMes = $this->input->POST('fechaMes');
		if ($sede != "") {
			$sede = "where h.sede = '$sede'";
			$empleado = ($empleado != "") ? 'and h.nit_empleado = ' . $empleado . '' : '';
		} else {
			$sede = "";
			$empleado = ($empleado != "") ? 'where h.nit_empleado = ' . $empleado . '' : '';
		}
		if ($fechaDia != "") {
			$fecha = "(convert(date,fecha_am)='$fechaDia' or convert(date,fecha_pm)='$fechaDia')";
		}
		if ($fechaMes != "") {
			$porciones = explode("-", $fechaMes);
			$año = $porciones[0];
			$mes = $porciones[1];
			$fecha = "YEAR(fecha_am) = $año AND MONTH(fecha_am) = $mes
			or YEAR(fecha_pm) = $año AND MONTH(fecha_pm) = $mes";
		}

		$data = $this->Usuarios->getAllPausasActivas($empleado, $sede, $fecha);
		if ($data != false) {
			echo '<tbody>';
			foreach ($data->result() as $key) {

				$fechaAM = ($key->fechaAM != "") ? $key->fechaAM : '<i class="fas fa-times-circle"></i>';
				$fechaPM = ($key->fechaPM != "") ? $key->fechaPM : '<i class="fas fa-times-circle"></i>';

				echo
				'
						<tr>
							<td scope="col">' . $key->nit_empleado . '</td>
							<td scope="col">' . $key->nombres . '</td>
							<td scope="col">' . $key->sede . '</td>
							<td scope="col">' . $fechaAM . '</td>
							<td scope="col">' . $fechaPM . '</td>
						</tr>
					';
			}

			echo '</tbody></table></div>';
		} else {
			echo '<tr><td colspan="5">No se encontraron datos</td></tr></tbody></table></div>';
		}
	}

	public function informe_tiempo_ausentismos()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//se cargan los modelos
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
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
			//print_r($ausen_json);die;
			$arr_user = array(
				'userdata' => $userinfo,
				'menus' => $allmenus,
				'perfiles' => $allperfiles,
				'pass' => $pass,
				'id_usu' => $id_usu,
			);
			//abrimos la vista

			$this->load->view("administracion/informe_tiempo_ausentismos", $arr_user);
		}
	}

	public function loadDataTiempoAusentismos()
	{
		$this->load->model('ausentismos');

		$year = $this->input->POST('year');
		$month = $this->input->POST('month');

		$data = $this->ausentismos->getAusentismoInfoTiempo($year, $month);
		$tBody = "";
		if (count($data->result()) > 0) {

			foreach ($data->result() as $row) {
				$tBody .= "<tr>
					<td>$row->nit_empleado</td>	
					<td>$row->nombres</td>	
					<td>$row->motivo</td>	
					<td>$row->tiempo</td>	
				</tr>";
			}

			$dataTable = array(
				'response' => 'success',
				'tbody'	=> $tBody,
				'num_rows' => count($data->result())
			);
			echo json_encode($dataTable);
		} else {
			$dataTable = array(
				'response' => 'error',
				'tbody'	=> $tBody,
				'num_rows' => count($data->result())
			);
			echo json_encode($dataTable);
		}
	}
	public function inf_ausentismos()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//se cargan los modelos
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
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

			$usuariosActivos = $this->usuarios->getUserActivos();
			//print_r($ausen_json);die;
			$arr_user = array(
				'userdata' => $userinfo,
				'menus' => $allmenus,
				'perfiles' => $allperfiles,
				'pass' => $pass,
				'id_usu' => $id_usu,
				'usuariosActivos' => $usuariosActivos
			);
			//abrimos la vista

			$this->load->view("administracion/inf_ausentismos", $arr_user);
		}
	}

	public function loadDataInfAusentismos()
	{
		$this->load->model('ausentismos');

		$year = $this->input->POST('year');
		$month = $this->input->POST('month');
		$nit = $this->input->POST('nit_empleado');
		$whereNit = ($nit != "" ? "and nit_empleado=$nit" : '');
		$data = $this->ausentismos->getAusentismoInfo($year, $month,$whereNit);
		$tBody = "";
		if (count($data->result()) > 0) {
			foreach ($data->result() as $row) {
				$tBody .= "<tr>
					<td>$row->nit_empleado</td>	
					<td>$row->nombres</td>	
					<td>$row->motivo</td>	
					<td>$row->fecha_ini</td>
					<td>$row->fecha_fin</td>
					<td>$row->hora_ini</td>
					<td>$row->hora_fin</td>	
				</tr>";
			}

			$dataTable = array(
				'response' => 'success',
				'tbody'	=> $tBody,
				'num_rows' => count($data->result())
			);
			echo json_encode($dataTable);
		} else {
			$dataTable = array(
				'response' => 'error',
				'tbody'	=> $tBody,
				'num_rows' => count($data->result())
			);
			echo json_encode($dataTable);
		}
	}
}
