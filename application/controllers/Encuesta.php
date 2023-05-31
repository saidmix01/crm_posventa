<?php

use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Counts;

require '././vendor/autoload.php';

/**
 * 
 */
class Encuesta extends CI_Controller
{

	public function encuesta_satisfaccion()
	{
		$this->load->model('encuestas');
		$this->load->model('Informe');
		$preguntas = $this->encuestas->listar_preguntas_encuesta_satisfaccion();
		$fecha = $this->Informe->get_fecha();
		$bod = $this->input->GET('bod');
		$data = array('preguntas' => $preguntas, 'data' => "1", "fecha" => $fecha, "bod" => $bod);
		$this->load->view('satisfaccion_postv', $data);
	}

	public function resp_satisf_qr()
	{
		$this->load->model('encuestas');
		$this->load->model('Informe');
		$pregunta1 = $this->input->POST('2');
		$pregunta2 = $this->input->POST('3');
		$pregunta3 = $this->input->POST('4');
		$pregunta4 = $this->input->POST('5');
		$pregunta5 = $this->input->POST('7');
		$n_orden = $this->input->POST('n_orden');
		$fuente = 'QR';
		$bod = $this->input->POST('bod');
		$fecha = $this->Informe->get_fecha();
		$data = array(
			'ot' => $n_orden, 'fecha' => $fecha->fecha, 'p1' => $pregunta1, 'p2' => $pregunta2, 'p3' => $pregunta3, 'p4' => $pregunta4, 'p5' => $pregunta5, 'fuente' => $fuente, 'bod' => $bod
		);
		//print_r($data);die;
		$this->encuestas->insert_encuesta_satisfaccion_qr($data);

		header("Location: " . base_url() . "encuesta/encuestasatisfaccion?data=0");
	}

	/**
	 * METODO PARA RSIVIR DATOS DE ENCUESTA QR CARGADA POR VENTANILLA
	 * ANDRES GOEZ
	 * 2022-25-01
	 */
	public function resp_satisf_qr_ventanilla()
	{
		$this->load->model('encuestas');
		$this->load->model('Informe');
		$pregunta1 = $this->input->POST('2');
		$pregunta2 = $this->input->POST('3');
		$pregunta3 = $this->input->POST('4');
		$pregunta4 = $this->input->POST('5');
		$pregunta5 = $this->input->POST('7');
		$placa = $this->input->POST('placa');
		$fecha = $this->Informe->get_fecha();
		$bodega = $this->input->POST('bodega');
		$fuente = 'QR';
		$data = array(
			'placa' => $placa, 'fecha' => $fecha->fecha, 'p1' => $pregunta1, 'p2' => $pregunta2, 'p3' => $pregunta3, 'p4' => $pregunta4, 'p5' => $pregunta5, 'fuente' => $fuente, 'bodega' => $bodega
		);
		//print_r($data);die;
		$datosqr = $this->encuestas->insert_encuesta_satisfaccion_qr_ventanilla($data);
		if ($datosqr == TRUE) {
			echo "
			<div class='alert alert-success text-center col-lg-12' role='alert'>Encuesta Cargada de Manera Exitosa <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
			<span aria-hidden='true'>&times;</span></button></div>
			";
		} else {
			echo "
			<div class='alert alert-danger text-center col-lg-12' role='alert'<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
			<span aria-hidden='true'>&times;</span></button>>Error al Cargar La Encuesta</div>
			";
		}
	}


	public function encuestasatisfaccion()
	{
		$this->load->model('encuestas');
		$n_orden = $this->input->GET('data');
		$val_encuesta = $this->encuestas->val_encuesta_satisfaccion($n_orden);
		if ($val_encuesta->n >= 1) {
			$preguntas = $this->encuestas->listar_preguntas_encuesta_satisfaccion();
			$data = array('preguntas' => $preguntas, 'data' => "");
			$this->load->view('encuesta_satisfaccion', $data);
		} elseif ($val_encuesta == NULL) {
			echo "ENCUESTA NO EXISTE";
			$data = $data = array('data' => "403");
			$this->load->view('encuesta_satisfaccion', $data);
		} elseif ($val_encuesta->n == 0) {
			$data = $data = array('data' => "404");
			$this->load->view('encuesta_satisfaccion', $data);
		}
	}

	public function resp_satisf()
	{
		$this->load->model('encuestas');
		$this->load->model('Informe');
		$pregunta1 = $this->input->POST('2');
		$pregunta2 = $this->input->POST('3');
		$pregunta3 = $this->input->POST('4');
		$pregunta4 = $this->input->POST('5');
		$pregunta5 = $this->input->POST('7');
		$n_orden = $this->input->POST('n_orden');
		$fecha = $this->Informe->get_fecha();
		$data = array(
			'ot' => $n_orden, 'fecha' => $fecha->fecha, 'p1' => $pregunta1, 'p2' => $pregunta2, 'p3' => $pregunta3, 'p4' => $pregunta4, 'p5' => $pregunta5
		);
		//print_r($data);
		$this->encuestas->insert_encuesta_satisfaccion($data);

		header("Location: " . base_url() . "encuesta/encuestasatisfaccion?data=0");
	}

	public function Informe_encuesta_satisfaccion()
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
			$respuestas = $this->encuestas->Informe_encuesta_satisfaccion();
			//prueba de menu

			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//echo $id_usu;

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'respuestas' => $respuestas);
			//abrimos la vista
			$this->load->view('Informe_encuesta_satisfaccion', $arr_user);
		}
	}

	public function detalle_encuesta_satisfaccion()
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
			$ot = $this->input->GET('ot');
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
			$data_respuestas = $this->encuestas->detallle_Informe_encuesta_satisfaccion($ot);
			//datos de cliente y tecnico
			$data_detalle_orden = $this->encuestas->Informe_encuesta_satisfaccion_by_ot($ot);
			//id usuario
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//array para la vits
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'data_respuestas' => $data_respuestas, 'data_Informe' => $data_detalle_orden);
			//abrimos la vista
			$this->load->view('detalle_Informe_encuesta_satisfaccion', $arr_user);
		}
	}

	public function detalle_encuesta_satisfaccion_v()
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
			$nit = $this->input->GET('nit');
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
			//datos del Informe
			$data_Informe = $this->encuestas->Informe_encuesta_satisfaccion_by_cliente($nit);
			//id usuario
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//array para la vits
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'data_Informe' => $data_Informe);
			//abrimos la vista
			$this->load->view('detalle_Informe_encuesta_satisfaccion_v2', $arr_user);
		}
	}

	public function inf_det_encuesta_satisfaccion()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos necesarios
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
			//$allempleados = $this->nominas->listar_empleados();
			$allempleados = null;
			$bodegas = $this->sedes->getAllSedes();
			//prueba de menu
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//echo $id_usu;

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'bodegas' => $bodegas);
			//abrimos la vista
			$this->load->view('inf_det_encuesta_satisfaccion', $arr_user);
		}
	}

	public function llenar_combo_tecnicos()
	{
		$this->load->model('Nominas');
		$patios = "";
		$bod = $this->input->get('bod');
		switch ($bod) {
			case '1':
				$patios = "'1','13','12'";
				break;
			case '2':
				$patios = "'3'";
				break;
			case '3':
				$patios = "'11'";
				break;
			case '4':
				$patios = "'5'";
				break;
			case '5':
				$patios = "'6'";
				break;
			case '6':
				$patios = "'4'";
				break;
			case '7':
				$patios = "'7'";
				break;
			case '8':
				$patios = "'12'";
				break;
			case '9':
				$patios = "'8'";
				break;
		}
		$tecnicos = $this->Nominas->get_tall_operarios_intranet($patios);
		foreach ($tecnicos->result() as $key) {
			echo '<option value="all">Todos</option>
				  <option value="' . $key->nit . '">' . $key->nombre . '</option>';
		}
	}

	/*CARGA Informe ENCUESTA DE SATISFACCION*/
	public function generar_Informe_encuesta_satisfaccion()
	{
		$this->load->model('encuestas');
		//se traen las variables desde el formulario
		$fi = $this->input->get("fi");
		$ff = $this->input->get("ff");
		$bod = $this->input->get("bode");
		$tec = $this->input->get("tec");
		$err = $this->input->get("err");
		$cli = $this->input->get("cli");
		$ot = $this->input->get("ot");
		$ns = $this->input->get("n_s");
		//echo $bod."".$tec;
		//se ejecuta la consulta
		//$data_inf = null; 
		if ($err == "err") {
			echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
					  <strong>Atencion!</strong> Debe seleccionar un rango de fechas.
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					    <span aria-hidden="true">&times;</span>
					  </button>
					</div>';
		}
		if ($bod == "todas" && $tec == "all" && $cli == "" && $ot == "" && $ns == 0) {
			$data_inf = $this->encuestas->inf_gral_encuesta_satisfaccion($fi, $ff);
		} elseif ($bod != "todas" && $tec == "all" && $cli == "" && $ot == "" && $ns == 0) {
			$data_inf = $this->encuestas->inf_gral_encuesta_satisfaccion_bod($fi, $ff, $bod);
		} elseif ($bod != "todas" && $tec != "all" && $cli == "" && $ot == "" && $ns == 0) {
			$data_inf = $this->encuestas->inf_gral_encuesta_satisfaccion_tec($fi, $ff, $tec);
		} elseif ($bod != "todas" && $tec != "all" && $cli != "" && $ot == "" && $ns == 0) {
			$data_inf = $this->encuestas->inf_gral_encuesta_satisfaccion_cli($fi, $ff, $cli);
		} elseif ($bod == "todas" && $tec == "all" && $cli != "" && $ot == "" && $ns == 0) {
			$data_inf = $this->encuestas->inf_gral_encuesta_satisfaccion_cli($fi, $ff, $cli);
		} elseif ($bod == "todas" && $tec == "all" && $ot != "" && $cli == "" && $ns == 0) {
			$data_inf = $this->encuestas->inf_gral_encuesta_satisfaccion_ot($fi, $ff, $ot);
		} elseif ($bod != "todas" && $tec == "all" && $ot != "" && $cli == "" && $ns == 0) {
			$data_inf = $this->encuestas->inf_gral_encuesta_satisfaccion_ot($fi, $ff, $ot);
		} elseif ($bod != "todas" && $tec == "all" && $ot == "" && $ns != 0) {
			if ($ns == 10) {
				$data_inf = $this->encuestas->inf_gral_encuesta_satisfaccion_ns($fi, $ff, 10, 10);
			} elseif ($ns == 8) {
				$data_inf = $this->encuestas->inf_gral_encuesta_satisfaccion_ns($fi, $ff, 8, 9);
			} elseif ($ns == 7) {
				$data_inf = $this->encuestas->inf_gral_encuesta_satisfaccion_ns($fi, $ff, 6, 8);
			} elseif ($ns == 6) {
				$data_inf = $this->encuestas->inf_gral_encuesta_satisfaccion_ns($fi, $ff, 0, 5);
			}
		} elseif ($bod == "todas" && $tec == "all" && $ot == "" && $ns != 0) {
			if ($ns == 10) {
				$data_inf = $this->encuestas->inf_gral_encuesta_satisfaccion_ns($fi, $ff, 10, 10);
			} elseif ($ns == 8) {
				$data_inf = $this->encuestas->inf_gral_encuesta_satisfaccion_ns($fi, $ff, 8, 9);
			} elseif ($ns == 7) {
				$data_inf = $this->encuestas->inf_gral_encuesta_satisfaccion_ns($fi, $ff, 6, 8);
			} elseif ($ns == 6) {
				$data_inf = $this->encuestas->inf_gral_encuesta_satisfaccion_ns($fi, $ff, 0, 5);
			}
		} else {
			echo '<div class="alert alert-default alert-dismissible fade show" role="alert">
					  <strong>Atencion!</strong> No hay datos disponibles para mostrar.
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					    <span aria-hidden="true">&times;</span>
					  </button>
					</div>';
		}

		$estilo = "";
		$estilo2 = "";
		foreach ($data_inf->result() as $key) {

			if (number_format($key->prom_p1, 2, ",", ",") > number_format(8, 2, ",", ",") || number_format($key->prom_p1, 2, ",", ",") <= number_format(10, 2, ",", ",")) {
				$estilo = "bg-success";
			} else if (number_format($key->prom_p1, 2, ",", ",") >= number_format(7, 2, ",", ",") || number_format($key->prom_p1, 2, ",", ",") <= number_format(8, 2, ",", ",")) {
				$estilo = "bg-warning";
			}

			if (number_format($key->prom_p2, 2, ",", ",") > number_format(8, 2, ",", ",") || number_format($key->prom_p2, 2, ",", ",") <= number_format(10, 2, ",", ",")) {
				$estilo2 = "bg-success";
			} else if (number_format($key->prom_p2, 2, ",", ",") >= number_format(7, 2, ",", ",") || number_format($key->prom_p2, 2, ",", ",") <= number_format(8, 2, ",", ",")) {
				$estilo2 = "bg-warning";
			}

			echo '<div class="card">
				<h3 class="card-header" align="center">' . $key->nombres . ' NIT: ' . $key->vendedor . '</h3>
			  <div class="card-body">
			    <div class="row" align="center">
			    	<div class="col-md-6">
			    		<div class="card text-dark ' . $estilo . ' mb-3" style="max-width: 18rem;">
						  <div class="card-header">Satisfaccion con el concesionario (Promedio)</div>
						  <div class="card-body" align="center">
						    <h1 class="" align="center">' . $key->prom_p1 . '</h1>
						  </div>
						</div>
			    	</div>
			    	<div class="col-md-6">
			    		<div class="card text-dark ' . $estilo2 . ' mb-3" style="max-width: 18rem;">
						  <div class="card-header">Satisfaccion con el trabajo realizado (Promedio)</div>
						  <div class="card-body" align="center">
						    <h1 class="" align="center">' . $key->prom_p2 . '</h1>
						  </div>
						</div>
			    	</div>
			    	
			    </div>
			  </div>
			  <div class="card-footer text-muted" align="center">
			    <a href="' . base_url() . 'encuesta/detalle_encuesta_satisfaccion_v?nit=' . $key->vendedor . '" class="btn btn-default">Ver mas Informacion</a>
			  </div>
			</div>';
		}
	}
	/*FUNCION PARA CARGAR LA VISTA DE INGRESO DEL NPS DE COLMOTORES*/
	public function ingreso_nps_colmotores()
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
			$this->load->view('nps_colmotores', $arr_user);
		}
	}

	/*FUNCION QUE REALIZA EL INSERT NPS SEDES*/
	public function insert_nps_sedes()
	{
		//Cargamos el modelo
		$this->load->model('encuestas');
		//traemos los datos del formulario
		$sede = $this->input->POST('combo_sedes_all');
		$fecha = $this->input->POST('fecha_all');
		$calificacion = $this->input->POST('calificacion_all');
		$cal06 = $this->input->POST('cal06');
		$cal78 = $this->input->POST('cal78');
		$cal910 = $this->input->POST('cal910');

		$n = $this->encuestas->validar_nps_sede($fecha, $sede);
		if ($n->n > 0) {
			$condiciones = array('Fecha' => $fecha, 'Sede' => $sede);
			$data = array('Calificacion' => $calificacion, 'Enc_0_a_6' => $cal06, 'Enc_7_a_8' => $cal78, 'Enc_9_a_10' => $cal910);
			if ($this->encuestas->update_nps_sede($data, $condiciones)) {
				header("Location: " . base_url() . "encuesta/ingreso_nps_colmotores?log=ok");
			} else {
				header("Location: " . base_url() . "encuesta/ingreso_nps_colmotores?log=err");
			}
		} else {
			//llenamos un array para el insert
			$data = array('sede' => $sede, 'fecha' => $fecha, 'calificacion' => $calificacion, 'cal06' => $cal06, 'cal78' => $cal78, 'cal910' => $cal910);
			//ejecutamos el insert y validamos si queda ok
			if ($this->encuestas->insert_nps_sedes($data)) {
				header("Location: " . base_url() . "encuesta/ingreso_nps_colmotores?log=ok");
			} else {
				header("Location: " . base_url() . "encuesta/ingreso_nps_colmotores?log=err");
			}
		}
	}

	/*FUNCION QUE REALIZA EL INSERT NPS TECNICOS*/
	public function insert_nps_tecnicos()
	{
		//Cargamos el modelo
		$this->load->model('encuestas');
		//traemos los datos del formulario
		$sede = $this->input->GET('sede');
		$tecnico = $this->input->GET('tecnico');
		$fecha = $this->input->GET('fecha');
		$calificacion = $this->input->GET('calificacion');
		$vin_p = $this->input->GET('placa_v');
		$tipificacion = $this->input->GET('tipif');
		$tipo_cal = $this->input->GET('tipo_cal');
		echo $tecnico;
		$rango06 = 0;
		$rango78 = 0;
		$rango910 = 0;

		switch ($tipo_cal) {
			case '0a6':
				$rango06 = 1;
				break;
			case '7a8':
				$rango78 = 1;
				break;
			case '9a10':
				$rango910 = 1;
				break;
		}

		$n = $this->encuestas->validar_nps_tecnico($fecha, $tecnico);
		if ($n->n == 1) {
			$data = array('Calificacion' => $calificacion, 'tecnico' => $tecnico, 'Placa' => $vin_p, 'Enc_0_a_6' => $rango06, 'Enc_7_a_8' => $rango78, 'Enc_9_a_10' => $rango910, 'Tipificacion' => $tipificacion);
			$condiciones = array('tecnico' => $tecnico, 'fecha' => $fecha);
			if ($this->encuestas->update_nps_tecnico($data, $condiciones)) {
				//header("Location: " . base_url() . "encuesta/ingreso_nps_colmotores?log=ok");
				//echo "bien";
				echo '<div class="alert alert-success alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
						Operacion realizada con exito
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>';
			} else {
				//header("Location: " . base_url() . "encuesta/ingreso_nps_colmotores?log=err");
				//echo "mal";
				echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
						Error al realizar la operacion
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>';
			}
		} elseif ($n->n == 0) {
			//llenamos un array para el insert
			$data = array('sede' => $sede, 'fecha' => $fecha, 'calificacion' => $calificacion, 'tecnico' => $tecnico, 'placa' => $vin_p, 'encu06' => $rango06, 'encu78' => $rango78, 'encu910' => $rango910, 'tipificacion' => $tipificacion);
			//ejecutamos el insert y validamos si queda ok
			if ($this->encuestas->insert_nps_tecnicos($data)) {
				//header("Location: " . base_url() . "encuesta/ingreso_nps_colmotores?log=ok");
				//echo "bien";
				echo '<div class="alert alert-success alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
						Operacion realizada con exito
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>';
			} else {
				//header("Location: " . base_url() . "encuesta/ingreso_nps_colmotores?log=err");
				//echo "mal";
				echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
						Error al realizar la operacion
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>';
			}
		}
	}

	public function llenar_combo_tecnicos_nps()
	{
		$this->load->model('Nominas');
		$patios = "";
		$bod = $this->input->get('bod');
		switch ($bod) {
			case 'giron':
				$patios = "'1','13','12','3'";
				break;
			case 'rosita':
				$patios = "'4'";
				break;
			case 'barranca':
				$patios = "'5','6'";
				break;
			case 'bocono':
				$patios = "'8','7'";
				break;
		}
		$tecnicos = $this->Nominas->get_tall_operarios_intranet($patios);
		foreach ($tecnicos->result() as $key) {
			echo '<option value="all">Todos</option>
				  <option value="' . $key->nit . '">' . $key->nombre . '</option>';
		}
	}

	public function cargar_nps_tecnicos()
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
			//datos del Informe
			//id usuario
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//array para la vits
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu);
			//abrimos la vista
			$this->load->view('cargar_nps_tecnicos', $arr_user);
		}
	}

	public function upload_csv_tec_nps_pc()
	{
		$this->load->model('encuestas');
		$file_tec = $_FILES['fileContacts'];
		$file_tec = file_get_contents($file_tec['tmp_name']);
		$file_tec = explode("\n", $file_tec);
		$file_tec = array_filter($file_tec);
		foreach ($file_tec as $key) {
			$arr_tec[] = explode(";", $key);
		}
		foreach ($arr_tec as $key) {
			$nit = $key[0];
			$nombres = $key[1];
			$fecha = $key[2];
			$calificacion = $key[3];
			$sede = $key[4];
			$n = $this->encuestas->val_tec_nps($fecha, $nit);
			if ($n->n != 0) {
				$log = array("2");
			} else {
				$data = array($nit, $nombres, $fecha, $calificacion, $sede);
				if ($this->encuestas->insert_nps_tec($data)) {
				}
			}
		}
		echo "bien";
	}

	public function upload_csv_tec_nps_c()
	{
		$this->load->model('encuestas');
		$file_tec = $_FILES['fileContacts'];
		$file_tec = file_get_contents($file_tec['tmp_name']);
		$file_tec = explode("\n", $file_tec);
		$file_tec = array_filter($file_tec);
		foreach ($file_tec as $key) {
			$arr_tec[] = explode(",", $key);
		}
		foreach ($arr_tec as $key) {
			$nit = $key[0];
			$nombres = $key[1];
			$fecha = $key[2];
			$calificacion = $key[3];
			$sede = $key[4];
			$n = $this->encuestas->val_tec_nps($fecha, $nit);
			if ($n->n != 0) {
				$log = array("2");
			} else {
				$data = array($nit, $nombres, $fecha, $calificacion, $sede);
				if ($this->encuestas->insert_nps_tec($data)) {
				}
			}
		}
		echo "bien";
	}

	public function upload_nps_tec()
	{
		$this->load->model('encuestas');
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
		$file_rtfte = $_FILES['fileContacts']['tmp_name'];

		$inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_rtfte);
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
		$spreadsheet = $reader->load($file_rtfte);
		$sheet_data = $spreadsheet->getActiveSheet()->toArray();
		
		$contador = 0;
		$contador2 = 0;
		foreach ($sheet_data as $key) {
			//$contador++;
			$sedes = $key[1];
			$resulsede = $sedes == "00000260492" ? "barranca" : ($sedes == "00000266043" ? "bocono" : ($sedes == "00000260493" ? "rosita" : ($sedes == "00000232420" ? "giron" : "sin sede")));
			$id_encuesta = $key[0];
			$sede = $resulsede;
			$nom_cliente = $key[2];
			$tecnicos =  explode("_", $key[3]);
			$nom_tecnico = "";
			$nit_tecnico = "";
			if ( !empty($tecnicos) && COUNT($tecnicos) > 1) {
				$nom_tecnico = $tecnicos[1] == 'ANONYMOUS' ? 'ANONYMOUS' : $tecnicos[1];
				$nit_tecnico = $tecnicos[0];
			} 			
			$VIN = $key[4];
			$fecha_evento = $key[5];
			$fecha_recibido_enc = $key[6];
			$tipo_evento = $key[7];
			$modelo_vh = $key[8];
			$recomendacion_concesionario = $key[9];
			$valor = explode(("-"), $key[10]);
			$satisfaccion_concesionario = "";
			if ( !empty($valor) && COUNT($valor) > 0) {
				$satisfaccion_concesionario = $valor[0];
			}			
			$trabajo = explode(("-"), $key[11]);
			$satisfaccion_trabajo = "";
			if ( !empty($trabajo) && COUNT($trabajo) > 0) {
				$satisfaccion_trabajo = $trabajo[0];
			}			
			$vh_reparado_ok = $key[12];
			$recomendacion_marca = $key[13];
			$comentarios = $key[14];
			
			$data = array(
				'id_encuesta' => $id_encuesta, 'sede' => $sede, 'nom_cliente' => $nom_cliente, 'nom_tecnico' => $nom_tecnico, 'nit_tecnico' => $nit_tecnico,
				'VIN' => $VIN, 'fecha_evento' => $fecha_evento, 'fecha_recibido_enc' => $fecha_recibido_enc, 'tipo_evento' => $tipo_evento, 'modelo_vh' => $modelo_vh,
				'recomendacion_concesionario' => $recomendacion_concesionario, 'satisfaccion_concesionario' => $satisfaccion_concesionario,
				'satisfaccion_trabajo' => $satisfaccion_trabajo, 'vh_reparado_ok' => $vh_reparado_ok, 'recomendacion_marca' => $recomendacion_marca, 'comentarios' => $comentarios
			);
			/* print_r($data); */
			/* $nuevos_dato = array('nit_tec' => $nit_tecnico, 'nombres' => $nom_cliente, 'fecha_enc' => $fecha_recibido_enc, 'calificacion' => $recomendacion_concesionario, 'sede' => $sede);  */

			if (
				$id_encuesta == "" || $sede == "" || $nom_cliente == "" || $nom_tecnico == "" || $nit_tecnico == "" || $VIN == "" || $fecha_evento == ""
				|| $fecha_recibido_enc == "" || $tipo_evento == "" || $modelo_vh == "" || $recomendacion_concesionario == ""
				|| $satisfaccion_concesionario == "" || $satisfaccion_trabajo == "" || $recomendacion_marca == "" || ($vh_reparado_ok == "" && $vh_reparado_ok < 0)
			) {
				// echo 'No se hace nada'; 
			} else {
				$data_enc = $this->encuestas->get_postv_encuestas_gm($id_encuesta);
				if ($data_enc->n == 0) {
					$primero = $this->encuestas->insert_encuestas_gm($id_encuesta, $sede, $nom_cliente, $nom_tecnico, $nit_tecnico, $VIN, $fecha_evento, $fecha_recibido_enc, $tipo_evento, $modelo_vh, $recomendacion_concesionario, $satisfaccion_concesionario, $satisfaccion_trabajo, $vh_reparado_ok, $recomendacion_marca, $comentarios);
					$segunda = $this->encuestas->insert_encuestas_nps_tec($nit_tecnico, $nom_cliente, $fecha_recibido_enc, $recomendacion_concesionario, $sede);
					if ($primero == "bien") {
						$contador++;
						//echo "bien";
					} else {
						$contador2++;
						//echo "bien";
					}
				}
			}
			
			
		}
		if ($contador != 0) {
			echo "Se insertaron ".$contador." registros, no se insertaron ".$contador2." registros";
		}elseif($contador2 != 0 && $contador == 0){
			echo "err";
		}elseif($contador == 0 && $contador2 == 0){
			echo "No se insertaron registros";
		}
	}

	/**
	 * METODO PARA CARGAR LAS VISTA PARA Informe DE CODIGOS QR POR TALLER
	 * ANDRES GOMEZ
	 * 2022-21-01
	 */
	public function Informe_qr_por_taller()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('encuestas');

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
			$preguntas = $this->encuestas->listar_preguntas_encuesta_satisfaccion();
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'preguntas' => $preguntas);
			//abrimos la vista
			$this->load->view("Informe_qr_taller/index", $arr_user);
		}
	}
}
