<?php

/**
 * hola
 */
class Admin_presupuesto extends CI_Controller
{

	public function notifi_prueba()
	{
		//$this->load->model('presupuesto'); 1116608553
		if ($this->input->get('var') == 1) {
			//echo "hola";
			$this->load->model('presupuesto');
			$to_presupuesto_dia = $this->presupuesto->get_presupuesto_dia();
			$to_dia = 0;
			foreach ($to_presupuesto_dia->result() as $key) {
				$to_dia = $key->total;
			}
			$cokie_val = $this->session->userdata('valor');
			$res = $to_dia - $cokie_val;
			if ($res > 1) {
				$res_format = number_format($res, 0, ",", ",");
				echo '
				<div class="info-box mb-3 alert alert-dismissible fade show" style="position: fixed; z-index: 100; top: 10%; right: 1%; width: 400px;">
		                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
							    <span aria-hidden="true">&times;</span>
						</button>

		              <div class="info-box-content" aling="center">
		                <span class="info-box-text" style="font-weight: 700;">Informacion</span>
		                <span class="info-box-text" style="font-size: 15px;">Presupuesto de taller CODIESEL subio $' . $res_format . '</span>
		                <span class="info-box-text"><a href="' . base_url() . 'admin_presupuesto/" class="btn btn-sm bg-gradient-primary" style="text-decoration: none">Ver Cambios</a></span>
		              </div>
		               <span class="info-box-icon bg-primary elevation-1"><i class="fab fa-facebook-messenger"></i></span>
		              <!-- /.info-box-content -->
		         </div>
				';
			}
			$user = $this->session->userdata('user');
			$perfil = $this->session->userdata('perfil');
			$login = $this->session->userdata('login');
			$data = array('user' => $user, 'perfil' => $perfil, 'login' => true, 'valor' => $to_dia);
			//enviamos los datos de session al navegador
			$this->session->set_userdata($data);
		}
	}

	public function index()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('permisos');
			$id_perfil = $this->session->userdata('perfil');
			$perfil_usu = $this->permisos->validar_perfil($id_perfil);
			$centros_costos = "4,40,33,45,3,16,17,13,70,11,29,80,31,46,28,60,15";
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('presupuesto');
			$this->load->model('nominas');
			$fecha_ini = $this->input->get('fec_ini');
			$fecha_fin = $this->input->get('fec_fin');
			if (isset($fecha_ini) && isset($fec_fin)) {
				//obtenemos primer y ultimo dia del mes actual
				//traemos el presupuesto al dia de hoy
				$to_presupuesto_dia = $this->presupuesto->buscar_presupuesto_dia($fecha_ini, $fecha_fin);
				//traemos el presupuesto del mes de la db
				//echo $fecha_ini." - ".$fecha_fin;
				$to_presupuesto_mes = $this->presupuesto->buscar_presupuesto_mes($fecha_ini, $fecha_fin, "CODIESEL");

				$to_dia = 0;
				$to_t = 0;
				foreach ($to_presupuesto_mes->result() as $key) {
					$to_t = $key->presupuesto;
				}
				foreach ($to_presupuesto_dia->result() as $key) {
					$to_dia = $key->total;
				}

				//echo $to_t." -aa ".$to_dia;

				$porcentaje = ($to_dia * 100) / $to_t;
				$porcentaje_restante = (100 - $porcentaje);
				//traemos el presupuesto del mes de la db
				//$to_presupuesto_mes = $this->presupuesto->get_presupuesto_mes("2019-12-01","2019-12-31","CODIESEL");
				//traemos el total presupuesto por fecha de la db

				$to_presupuesto = $this->presupuesto->get_total_presupuesto();
				//damos formato al numero
				$total_format = number_format($to_presupuesto->total, 0, ",", ",");
				foreach ($to_presupuesto_mes->result() as $key) {
					echo '	
				<div class="col-md-12">
					<div class="info-box mb-3">
					  <span class="info-box-icon bg-warning elevation-1" style="font-size: 30px"><i class="fas fa-dollar-sign"></i></span>
					    <div class="info-box-content">
					                <span class="info-box-text" style="font-size: 20px">PRESUPUESTO DEL ' . $fecha_ini . ' AL ' . $fecha_fin . '</span>			               
					 <span class="info-box-number" style="font-size: 30px">' . number_format($to_dia, 0, ",", ",") . '</span>
					                
					   	 </div>
					     	<!-- /.info-box-content -->
						     	 </div>
				           	 	<div class="progress-group">
			                 	 Presupuesto a dia de hoy 
			                   	 <span class="float-right"><b>$' . number_format($to_dia, 0, ",", ",") . '</b>/$' . number_format($key->presupuesto, 0, ",", ",") . '</span>
			                      <div class="progress" style="height: 50px">
			                     <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width: ' . $porcentaje . '%; font-size: 25px">' . number_format($porcentaje, 0, ",", ",") . '%</div>
			                      <div class="progress-bar bg-danger" role="progressbar" style="width: ' . $porcentaje_restante . '%;font-size: 25px" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">' . number_format($porcentaje_restante, 0, ",", ",") . '%</div>
			                </div>
			           </div>
			           <!-- /.progress-group -->
			           <!-- /.progress-group -->
			         </div>
			                  <!-- /.col -->';
				}
			} else {
				//obtenemos primer y ultimo dia del mes actual
				$fecha_ini = $this->nominas->obtener_primer_dia_mes();
				$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
				//traemos el presupuesto al dia de hoy
				$to_presupuesto_dia = $this->presupuesto->get_presupuesto_dia($centros_costos);
				//traemos el presupuesto del mes de la db

				$to_presupuesto_mes = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL");

				$to_dia = 0;
				$to_t = 0;
				foreach ($to_presupuesto_mes->result() as $key) {
					$to_t = $key->presupuesto;
				}
				$to_dia = $to_presupuesto_dia->total;

				$porcentaje = ($to_dia * 100) / $to_t;
				$porcentaje_restante = (100 - $porcentaje);
				//traemos el presupuesto del mes de la db
				//$to_presupuesto_mes = $this->presupuesto->get_presupuesto_mes("2019-12-01","2019-12-31","CODIESEL");
				//traemos el total presupuesto por fecha de la db
				$to_presupuesto = $this->presupuesto->get_total_presupuesto();
				//damos formato al numero
				$total_format = number_format($to_presupuesto->total, 0, ",", ",");

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
				//echo $id_usu;
				$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'to_presupuesto' => $total_format, 'presupuesto_mes' => $to_presupuesto_mes, 'porcentaje' => $porcentaje, 'valor_dia' => $to_dia, 'porcentaje_restante' => $porcentaje_restante);
				//abrimos la vista
				$this->load->view("presupuesto", $arr_user);
			}
		}
	}

	public function real_time()
	{
		if ($this->input->get('var') == 1) {
			$perfil = $this->session->userdata('perfil');
			//carga de modelos
			$this->load->model('presupuesto');
			$this->load->model('nominas');
			$this->load->model('Informe');
			$centros_costos = "4,40,33,45,3,16,17,13,70,11,29,80,31,46,28,60,15";
			$centros_costos_rep = "4,40,33,45,16,13,70,29,80,31,46";
			if ($perfil == 1 || $perfil == 20 || $perfil == 33) {
				//obtenemos primer y ultimo dia del mes actual
				$fecha_ini = $this->nominas->obtener_primer_dia_mes();
				$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
				//traemos el presupuesto al dia de hoy
				$to_presupuesto_dia = $this->presupuesto->get_presupuesto_dia($centros_costos);
				//traemos el presupuesto del mes de la db

				$to_presupuesto_mes = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL");


				/********************* R E P U E S T O S   T A L L E R ************************/

				$total_rep = $this->presupuesto->get_presupuesto_rep($centros_costos_rep);


				/**************************** T  O T ****************************/
				$total_tot = $this->presupuesto->get_presupuesto_tot($centros_costos);


				/********************* M A N O  D E  O B R A *********************/
				$total_mo = $this->presupuesto->get_presupuesto_mo($centros_costos);

				/****************R E P U E S T O S   M O S T R A D O R*********************/
				$pres_rep_mos_total = $this->presupuesto->get_repuestos_mostrador_total();
				/****************************************************************************/


				$to_dia = 0;
				$to_t = 0;
				foreach ($to_presupuesto_mes->result() as $key) {
					$to_t = $key->presupuesto;
				}
				$to_dia = $to_presupuesto_dia->total;

				/*********************************** calcular porcentaje ideal ********************************************/
				$to_dias_mes = $this->Informe->get_total_dias();
				$to_dias_hoy = $this->Informe->get_dias_actual();
				$n_to_dias = $to_dias_mes->ultimo_dia;
				$n_dias_hoy = $to_dias_hoy->dia;
				//LO QUE DEBEMOS LLEVAR A DIA DE HOY
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
				//traemos el presupuesto del mes de la db
				//$to_presupuesto_mes = $this->presupuesto->get_presupuesto_mes("2019-12-01","2019-12-31","CODIESEL");
				//traemos el total presupuesto por fecha de la db
				//$to_presupuesto = $this->presupuesto->get_total_presupuesto();
				//damos formato al numero
				//$total_format = number_format($to_presupuesto->total,0,",",",");

				foreach ($to_presupuesto_mes->result() as $key) {
					echo '	
					<div class="small-box bg-default" align="center">
		              <div class="inner">
		                <h3>$' . number_format($to_dia, 0, ",", ".") . '</h3>

		                <p>Total vendido posventa</p>
		              </div>
		              <div class="icon">
		                <i class="ion ion-stats-bars"></i>
		              </div>
		              <div class="container-fluid">
		              <div class="row" align="center">
		              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
								<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($to_dia, 0, ",", ",") . '</div>
								<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($to_objetivo, 0, ",", ",") . '</div>
						  </div>
		              <div class="progress">
						  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($porcentaje_a_hoy, 0, ",", ",") . '%" aria-valuenow="' . number_format($porcentaje_a_hoy, 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($porcentaje_a_hoy, 0, ",", ",") . '%</div>
						  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($porcentaje_objetivo_restante, 0, ",", ",") . '%" aria-valuenow="' . number_format($porcentaje_objetivo_restante, 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($porcentaje_objetivo_restante, 0, ",", ",") . '%</div>
					  </div>
					  		<div class="row" align="center">
					  		<div class="col-md-4">Meta a cumplir al mes</div>
								<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($to_dia, 0, ",", ",") . '</div>
								<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key->presupuesto, 0, ",", ",") . '</div>
						  </div>
						  <div class="progress">
						  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($porcentaje, 0, ",", ",") . '%" aria-valuenow="' . number_format($porcentaje, 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($porcentaje, 0, ",", ",") . '%</div>
						  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($porcentaje_restante, 0, ",", ",") . '%" aria-valuenow="' . number_format($porcentaje_restante, 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($porcentaje_restante, 0, ",", ",") . '%</div>
					  </div>
					  </div>
					  <hr/>
					  <div class="row">
				          <div class="col-md-3 col-sm-6 col-12">
				            <div class="info-box">
				              <span class="info-box-icon bg-info"><i class="far fa-hand-paper"></i></span>

				              <div class="info-box-content">
				                <span class="info-box-text">Mano de Obra</span>
				                <span class="info-box-number">$' . number_format($total_tot->total, 0, ",", ",") . '</span>
				              </div>
				              <!-- /.info-box-content -->
				            </div>
				            <!-- /.info-box -->
				          </div>
				          <!-- /.col -->
				          <div class="col-md-3 col-sm-6 col-12">
				            <div class="info-box">
				              <span class="info-box-icon bg-success"><i class="fas fa-industry"></i></span>

				              <div class="info-box-content">
				                <span class="info-box-text">TOT</span>
				                <span class="info-box-number">$' . number_format(($total_mo->total - $total_tot->total), 0, ",", ",") . '</span>
				              </div>
				              <!-- /.info-box-content -->
				            </div>
				            <!-- /.info-box -->
				          </div>
				          <!-- /.col -->
				          <div class="col-md-3 col-sm-6 col-12">
				            <div class="info-box">
				              <span class="info-box-icon bg-warning"><i class="fas fa-tools"></i></span>

				              <div class="info-box-content">
				                <span class="info-box-text">Repuestos taller</span>
				                <span class="info-box-number">$' . number_format($total_rep->total, 0, ",", ",") . '</span>
				              </div>
				              <!-- /.info-box-content -->
				            </div>
				            <!-- /.info-box -->
				          </div>
				          <!-- /.col -->
				          <div class="col-md-3 col-sm-6 col-12">
				            <div class="info-box">
				              <span class="info-box-icon bg-secondary"><i class="fas fa-store"></i></span>

				              <div class="info-box-content">
				                <span class="info-box-text">Repuestos mostrador</span>
				                <span class="info-box-number">$' . number_format($pres_rep_mos_total->total, 0, ",", ",") . '</span>
				              </div>
				              <!-- /.info-box-content -->
				            </div>
				            <!-- /.info-box -->
				          </div>
				          <!-- /.col -->
				        </div>
		              <a href="' . base_url() . 'admin_presupuesto/presupuesto_sedes?presupuesto=' . $to_dia . '" class="small-box-footer" style="color: black;">
		                Mas detalles <i class="fas fa-arrow-circle-right"></i>
		              </a>
		            </div>';
				}
			}
			if ($perfil != 1 && $perfil != 20 && $perfil != 33) {
				//obtenemos primer y ultimo dia del mes actual
				$fecha_ini = $this->nominas->obtener_primer_dia_mes();
				$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
				/****************************************************************************************************************************
				------------------------------------------------------CALCULAR TOTAL SEDES---------------------------------------------------
				 *****************************************************************************************************************************/
				$presupuesto_principal = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL PRINCIPAL");
				$presupuesto_bocono = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL VILLA DEL ROSARIO");
				$presupuesto_rosita = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL LA ROSITA");
				$presupuesto_barranca = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL BARRANCABERMEJA");
				$prin = $this->presupuesto->get_presupuesto_dia_principal();
				$boc = $this->presupuesto->get_presupuesto_dia_bocono();
				$ros = $this->presupuesto->get_presupuesto_dia_rosita();
				$barran = $this->presupuesto->get_presupuesto_dia_barranca();

				/*********************************** calcular porcentaje ideal giron********************************************/
				$to_dias_mes = $this->Informe->get_total_dias();
				$to_dias_hoy = $this->Informe->get_dias_actual();
				$n_to_dias = $to_dias_mes->ultimo_dia;
				$n_dias_hoy = $to_dias_hoy->dia;

				$to_objetivo_giron = ($presupuesto_principal->presupuesto / $n_to_dias) * $n_dias_hoy;

				$porcentaje_objetivo_giron = ($prin->total / $presupuesto_principal->presupuesto) * 100;
				$porcentaje_objetivo_restante_giron = (100 - $porcentaje_objetivo_giron);

				/*********************************** calcular porcentaje ideal rosita********************************************/
				$to_dias_mes = $this->Informe->get_total_dias();
				$to_dias_hoy = $this->Informe->get_dias_actual();
				$n_to_dias = $to_dias_mes->ultimo_dia;
				$n_dias_hoy = $to_dias_hoy->dia;

				$to_objetivo_rosita = ($presupuesto_rosita->presupuesto / $n_to_dias) * $n_dias_hoy;

				$porcentaje_objetivo_rosita = ($ros->total / $presupuesto_rosita->presupuesto) * 100;
				$porcentaje_objetivo_restante_rosita = (100 - $porcentaje_objetivo_rosita);
				/*********************************** calcular porcentaje ideal bocono********************************************/
				$to_dias_mes = $this->Informe->get_total_dias();
				$to_dias_hoy = $this->Informe->get_dias_actual();
				$n_to_dias = $to_dias_mes->ultimo_dia;
				$n_dias_hoy = $to_dias_hoy->dia;

				$to_objetivo_bocono = ($presupuesto_bocono->presupuesto / $n_to_dias) * $n_dias_hoy;

				$porcentaje_objetivo_bocono = ($boc->total / $presupuesto_bocono->presupuesto) * 100;
				$porcentaje_objetivo_restante_bocono = (100 - $porcentaje_objetivo_bocono);
				/*********************************** calcular porcentaje ideal barranca********************************************/
				$to_dias_mes = $this->Informe->get_total_dias();
				$to_dias_hoy = $this->Informe->get_dias_actual();
				$n_to_dias = $to_dias_mes->ultimo_dia;
				$n_dias_hoy = $to_dias_hoy->dia;

				$to_objetivo_barranca = ($presupuesto_barranca->presupuesto / $n_to_dias) * $n_dias_hoy;

				$porcentaje_objetivo_barranca = ($barran->total / $presupuesto_barranca->presupuesto) * 100;
				$porcentaje_objetivo_restante_barranca = (100 - $porcentaje_objetivo_barranca);

				/********************* R E P U E S T O S ************************/
				$pres_rep_te = $this->presupuesto->get_presupuesto_rep('TE', 'DTE');
				$pres_rep_tl = $this->presupuesto->get_presupuesto_rep('TL', 'DTL');
				$pres_rep_tp = $this->presupuesto->get_presupuesto_rep('TP', 'DTP');

				$pres_rep_we = $this->presupuesto->get_presupuesto_rep('WE', 'DWE');
				$pres_rep_wl = $this->presupuesto->get_presupuesto_rep('WL', 'DWL');
				$pres_rep_wt = $this->presupuesto->get_presupuesto_rep('WT', 'DWT');

				$pres_rep_eb = $this->presupuesto->get_presupuesto_rep('EB', 'DBE');
				$pres_rep_tk = $this->presupuesto->get_presupuesto_rep('TK', 'DTK');

				$pres_rep_tr = $this->presupuesto->get_presupuesto_rep('TR', 'DTR');

				$pres_bocono = $pres_rep_we->valor + $pres_rep_wl->valor + $pres_rep_wt->valor;
				$pres_codiesel = $pres_rep_te->valor + $pres_rep_tl->valor + $pres_rep_tp->valor;
				$pres_barranca = $pres_rep_eb->valor + $pres_rep_tk->valor;

				/**************************** T  O T ****************************/
				$pres_tot_te = $this->presupuesto->get_presupuesto_tot('TE', 'DTE');
				$pres_tot_tl = $this->presupuesto->get_presupuesto_tot('TL', 'DTL');
				$pres_tot_tp = $this->presupuesto->get_presupuesto_tot('TP', 'DTP');

				$pres_tot_we = $this->presupuesto->get_presupuesto_tot('WE', 'DWE');
				$pres_tot_wl = $this->presupuesto->get_presupuesto_tot('WL', 'DWL');
				$pres_tot_wt = $this->presupuesto->get_presupuesto_tot('WT', 'DWT');

				$pres_tot_eb = $this->presupuesto->get_presupuesto_tot('EB', 'DBE');
				$pres_tot_tk = $this->presupuesto->get_presupuesto_tot('TK', 'DTK');

				$pres_tot_tr = $this->presupuesto->get_presupuesto_tot('TR', 'DTR');

				$tot_bocono = $pres_tot_we->valor + $pres_tot_wl->valor + $pres_tot_wt->valor;
				$tot_codiesel = $pres_tot_te->valor + $pres_tot_tl->valor + $pres_tot_tp->valor;
				$tot_barranca = $pres_tot_eb->valor + $pres_tot_tk->valor;

				/********************* M A N O  D E  O B R A *********************/
				$pres_mo_te = $this->presupuesto->get_presupuesto_mo('TE', 'DTE');
				$pres_mo_tl = $this->presupuesto->get_presupuesto_mo('TL', 'DTL');
				$pres_mo_tp = $this->presupuesto->get_presupuesto_mo('TP', 'DTP');

				$pres_mo_we = $this->presupuesto->get_presupuesto_mo('WE', 'DWE');
				$pres_mo_wl = $this->presupuesto->get_presupuesto_mo('WL', 'DWL');
				$pres_mo_wt = $this->presupuesto->get_presupuesto_mo('WT', 'DWT');

				$pres_mo_eb = $this->presupuesto->get_presupuesto_mo('EB', 'DBE');
				$pres_mo_tk = $this->presupuesto->get_presupuesto_mo('TK', 'DTK');

				$pres_mo_tr = $this->presupuesto->get_presupuesto_mo('TR', 'DTR');

				$mo_bocono = $pres_mo_we->valor + $pres_mo_wl->valor + $pres_mo_wt->valor;
				$mo_codiesel = $pres_mo_te->valor + $pres_mo_tl->valor + $pres_mo_tp->valor;
				$mo_barranca = $pres_mo_eb->valor + $pres_mo_tk->valor;

				$porcentaje_principal = ($prin->total * 100) / $presupuesto_principal->presupuesto;
				$porcentaje_restante_principal = 100 - $porcentaje_principal;
				if ($porcentaje_principal > 100) {
					//$porcentaje_principal = 100;
					$porcentaje_restante_principal = 0;
				}
				$principal = array('sede' => "CODIESEL PRINCIPAL", 'total' => $presupuesto_principal->presupuesto, 'total_dia' => $prin->total, 'porcentaje_principal' => $porcentaje_principal, 'porcentaje_restante' => $porcentaje_restante_principal, 'pres_rep' => $pres_codiesel, 'pres_tot' => $tot_codiesel, 'pres_mo' => $mo_codiesel, 'porcentaje_objetivo' => $porcentaje_objetivo_giron, 'to_objetivo' => $to_objetivo_giron, 'porcen_obj_res' => $porcentaje_objetivo_restante_giron);

				$porcentaje_bocono = ($boc->total * 100) / $presupuesto_bocono->presupuesto;
				$porcentaje_restante_boc = 100 - $porcentaje_bocono;
				if ($porcentaje_bocono > 100) {
					//$porcentaje_bocono = 100;
					$porcentaje_restante_boc = 0;
				}
				$bocono = array('sede' => "CODIESEL VILLA DEL ROSARIO", 'total' => $presupuesto_bocono->presupuesto, 'total_dia' => $boc->total, 'porcentaje_principal' => $porcentaje_bocono, 'porcentaje_restante' => $porcentaje_restante_boc, 'pres_rep' => $pres_bocono, 'pres_tot' => $tot_bocono, 'pres_mo' => $mo_bocono, 'porcentaje_objetivo' => $porcentaje_objetivo_bocono, 'to_objetivo' => $to_objetivo_bocono, 'porcen_obj_res' => $porcentaje_objetivo_restante_bocono);

				$porcentaje_ros = ($ros->total * 100) / $presupuesto_rosita->presupuesto;
				$porcentaje_restante_ros = 100 - $porcentaje_ros;
				if ($porcentaje_ros > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_ros = 0;
				}
				$rosita = array('sede' => "CODIESEL LA ROSITA", 'total' => $presupuesto_rosita->presupuesto, 'total_dia' => $ros->total, 'porcentaje_principal' => $porcentaje_ros, 'porcentaje_restante' => $porcentaje_restante_ros, 'pres_rep' => $pres_rep_tr->valor, 'pres_tot' => $pres_tot_tr->valor, 'pres_mo' => $pres_mo_tr->valor, 'porcentaje_objetivo' => $porcentaje_objetivo_rosita, 'to_objetivo' => $to_objetivo_rosita, 'porcen_obj_res' => $porcentaje_objetivo_restante_rosita);

				$porcentaje_barranca = ($barran->total * 100) / $presupuesto_barranca->presupuesto;
				$porcentaje_restante_barranca = 100 - $porcentaje_barranca;
				if ($porcentaje_barranca > 100) {
					//$porcentaje_barranca = 100;
					$porcentaje_restante_barranca = 0;
				}
				$diesel_barranca = array('sede' => "CODIESEL BARRANCABERMEJA", 'total' => $presupuesto_barranca->presupuesto, 'total_dia' => $barran->total, 'porcentaje_principal' => $porcentaje_barranca, 'porcentaje_restante' => $porcentaje_restante_barranca, 'pres_rep' => $pres_barranca, 'pres_tot' => $tot_barranca, 'pres_mo' => $mo_barranca, 'porcentaje_objetivo' => $porcentaje_objetivo_barranca, 'to_objetivo' => $to_objetivo_barranca, 'porcen_obj_res' => $porcentaje_objetivo_restante_barranca);

				$sede_presupuesto = array();

				if ($perfil == 2) {
					$sede_presupuesto[] = array($principal);
				} elseif ($perfil == 3) {
					$sede_presupuesto[] = array($rosita);
				} elseif ($perfil == 4) {
					$sede_presupuesto[] = array($bocono);
				} elseif ($perfil == 5) {
					$sede_presupuesto[] = array($diesel_barranca);
				}

				foreach ($sede_presupuesto as $s) {
					foreach ($s as $key) {
						echo '
								<div class="col-md-6" id="nose" aling="center">
								 <div class="card card-info">
										 <div class="card-header">
										    <h3 class="card-title" style="font-size: 30px;"><i class="far fa-building"></i>' . $key['sede'] . '</h3>
										    <div class="card-tools">
										    </div>
										  </div>
										  <div class="card-body" align="center">
										    <div class="col-md-12">
										    	<div class="info-box mb-3">
									              <span class="info-box-icon bg-warning elevation-1" style="font-size: 30px"><i class="fas fa-dollar-sign"></i></span>

									              <div class="info-box-content">
									                <span class="info-box-text" style="font-size: 30px">TOTAL FACTURADO</span>
									                <span class="info-box-number" style="font-size: 30px">' . number_format($key['total_dia'], 0, ",", ",") . '</span>
									                
									              </div>
									              <!-- /.info-box-content -->
									            </div>
							                    <div class="progress-group">
							                      Presupuesto a dia de hoy 
							                      <span class="float-right"><b>$' . number_format($key['total_dia'], 0, ",", ",") . '</b>/$' . number_format($key['total'], 0, ",", ",") . '</span>
							                      <div class="progress" style="height: 50px">
							                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width:' . $key['porcentaje_principal'] . '%; font-size: 25px">' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%</div>
							                        <div class="progress-bar bg-danger" role="progressbar" style="width: ' . $key['porcentaje_restante'] . '%;font-size: 25px" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
							                      </div>
							                      
							                    </div>
												<div class="progress-group">
							                      Presupuesto a dia de hoy 
							                      <span class="float-right"><b>$' . number_format($key['to_objetivo'], 0, ",", ",") . '</b>/$' . number_format($key['total'], 0, ",", ",") . '</span>
							                      <div class="progress" style="height: 50px">
							                        <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" style="width:' . $key['porcentaje_objetivo'] . '%; font-size: 25px">' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%</div>
							                        <div class="progress-bar bg-danger" role="progressbar" style="width: ' . $key['porcen_obj_res'] . '%;font-size: 25px" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%</div>
							                      </div>
							                      
							                    </div>
												<br/>
							                      <div class="row" style="width: 100%" aling="center">
														<table class="table table-bordered table-sm" aling="center">
														  <thead>
														    <tr aling="center">
														      <th scope="col">TOT</th>
														      <th scope="col">MO</th>
														      <th scope="col">REP</th>
														    </tr>
														  </thead>
														  <tbody>
														    <tr>
														      <th scope="row">$' . number_format($key['pres_tot'], 0, ",", ",") . '</th>
														      <th scope="row">$' . number_format($key['pres_mo'], 0, ",", ",") . '</th>
														      <th scope="row">$' . number_format($key['pres_rep'], 0, ",", ",") . '</th>
														    </tr>
														  </tbody>
														</table>
							                        </div>
							                    <!-- /.progress-group -->
							                    <!-- /.progress-group -->
							                  </div>
							                  <!-- /.col -->
										  </div>
										  <!-- /.card-body -->
										  <div class="card-footer">
										    <a href="' . base_url() . 'admin_presupuesto/presupuesto_taller?sede=' . $key['sede'] . '&presupuesto=' . $key['total_dia'] . '" class="btn btn-block btn-outline-info btn-sm"><i class="fas fa-eye"></i> Ver Mas</a>
										  </div>
									</div>
								 </div>

							';
					}
				}
			}
		}
	}

	public function presupuesto_sedes()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			/*CENTRO DE COSTOS*/
			$centros_costos = "4,40,33,45,3,16,17,13,70,11,29,80,31,46,28,60,15";
			$centros_costos_rep = "4,40,33,45,16,13,70,29,80,31,46";
			$centros_costos_giron = "4,40,33,45,3";
			$centros_costos_giron_rep = "4,40,33,45";
			$centros_costos_rosita = "16,17";
			$centros_costos_rosita_rep = "16";
			$centros_costos_baranca = "13,70,11";
			$centros_costos_baranca_rep = "13,70";
			$centros_costos_bocono = "29,80,31,46,28";
			$centros_costos_bocono_rep = "29,80,31,46";
			$centros_costos_chevro = "15";
			$centros_costos_soloch = "60";
			/**********************************************************************/
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('sedes');
			$this->load->model('presupuesto');
			$this->load->model('nominas');

			$fecha_ini = $this->input->get('fec_ini');
			$fecha_fin = $this->input->get('fec_fin');
			if (isset($fecha_ini) && isset($fecha_fin)) {
				//echo $fecha_ini." <=> ".$fecha_fin;
				$presupuesto_principal = $this->presupuesto->buscar_presupuesto_mes_sedes($fecha_ini, $fecha_fin, "CODIESEL PRINCIPAL");
				$presupuesto_bocono = $this->presupuesto->buscar_presupuesto_mes_sedes($fecha_ini, $fecha_fin, "CODIESEL VILLA DEL ROSARIO");
				$presupuesto_rosita = $this->presupuesto->buscar_presupuesto_mes_sedes($fecha_ini, $fecha_fin, "CODIESEL LA ROSITA");
				$presupuesto_barranca = $this->presupuesto->buscar_presupuesto_mes_sedes($fecha_ini, $fecha_fin, "CODIESEL BARRANCABERMEJA");
				$prin = $this->presupuesto->buscar_presupuesto_dia_principal($fecha_ini, $fecha_fin);
				$boc = $this->presupuesto->buscar_presupuesto_dia_bocono($fecha_ini, $fecha_fin);
				$ros = $this->presupuesto->buscar_presupuesto_dia_rosita($fecha_ini, $fecha_fin);
				$barran = $this->presupuesto->buscar_presupuesto_dia_barranca($fecha_ini, $fecha_fin);
				/********************* R E P U E S T O S  T A L L E R************************/
				$pres_rep_gi = $this->presupuesto->get_presupuesto_rep($centros_costos_giron_rep);

				$pres_rep_ro = $this->presupuesto->get_presupuesto_rep($centros_costos_rosita_rep);

				$pres_rep_bo = $this->presupuesto->get_presupuesto_rep($centros_costos_bocono_rep);

				$pres_rep_ba = $this->presupuesto->get_presupuesto_rep($centros_costos_baranca_rep);

				$pres_rep_sol = $this->presupuesto->get_presupuesto_rep($centros_costos_soloch);

				$pres_rep_chev = $this->presupuesto->get_presupuesto_rep($centros_costos_chevro);

				$pres_bocono = $pres_rep_bo->total;
				$pres_rosita = $pres_rep_ro->total;
				$pres_codiesel = $pres_rep_gi->total;
				$pres_barranca = $pres_rep_ba->total;

				/**************************** T  O T ****************************/
				$pres_tot_gi = $this->presupuesto->get_presupuesto_tot($centros_costos_giron);

				$pres_tot_ro = $this->presupuesto->get_presupuesto_tot($centros_costos_rosita);

				$pres_tot_bo = $this->presupuesto->get_presupuesto_tot($centros_costos_bocono);

				$pres_tot_ba = $this->presupuesto->get_presupuesto_tot($centros_costos_baranca);

				$pres_tot_sol = $this->presupuesto->get_presupuesto_tot($centros_costos_soloch);

				$pres_tot_chev = $this->presupuesto->get_presupuesto_tot($centros_costos_chevro);

				$tot_bocono = $pres_tot_bo->total;
				$tot_rosita = $pres_tot_ro->total;
				$tot_codiesel = $pres_tot_gi->total;
				$tot_barranca = $pres_tot_ba->total;

				/********************* M A N O  D E  O B R A *********************/
				$pres_mo_gi = $this->presupuesto->get_presupuesto_mo($centros_costos_giron);

				$pres_mo_ro = $this->presupuesto->get_presupuesto_mo($centros_costos_rosita);

				$pres_mo_bo = $this->presupuesto->get_presupuesto_mo($centros_costos_bocono);

				$pres_mo_ba = $this->presupuesto->get_presupuesto_mo($centros_costos_baranca);

				$pres_mo_sol = $this->presupuesto->get_presupuesto_mo($centros_costos_soloch);

				$pres_mo_chev = $this->presupuesto->get_presupuesto_mo($centros_costos_chevro);


				$mo_bocono = ($pres_mo_bo->total - $tot_bocono);
				$mo_codiesel = ($pres_mo_gi->total - $tot_codiesel);
				$mo_barranca = ($pres_mo_ba->total - $tot_barranca);
				$mo_rosita = ($pres_mo_ro->total - $tot_rosita);
				

				$porcentaje_principal = ($prin->total * 100) / $presupuesto_principal->presupuesto;
				$porcentaje_restante_principal = 100 - $porcentaje_principal;
				if ($porcentaje_principal > 100) {
					//$porcentaje_principal = 100;
					$porcentaje_restante_principal = 0;
				}
				$principal = array('sede' => "CODIESEL PRINCIPAL", 'total' => $presupuesto_principal->presupuesto, 'total_dia' => $prin->total, 'porcentaje_principal' => $porcentaje_principal, 'porcentaje_restante' => $porcentaje_restante_principal, 'pres_rep' => $pres_codiesel, 'pres_tot' => $tot_codiesel, 'pres_mo' => $mo_codiesel);

				$porcentaje_bocono = ($boc->total * 100) / $presupuesto_bocono->presupuesto;
				$porcentaje_restante_boc = 100 - $porcentaje_bocono;
				if ($porcentaje_bocono > 100) {
					//$porcentaje_bocono = 100;
					$porcentaje_restante_boc = 0;
				}
				$bocono = array('sede' => "CODIESEL VILLA DEL ROSARIO", 'total' => $presupuesto_bocono->presupuesto, 'total_dia' => $boc->total, 'porcentaje_principal' => $porcentaje_bocono, 'porcentaje_restante' => $porcentaje_restante_boc, 'pres_rep' => $pres_bocono, 'pres_tot' => $tot_bocono, 'pres_mo' => $mo_bocono);

				$porcentaje_ros = ($ros->total * 100) / $presupuesto_rosita->presupuesto;
				$porcentaje_restante_ros = 100 - $porcentaje_ros;
				if ($porcentaje_ros > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_ros = 0;
				}
				$rosita = array('sede' => "CODIESEL LA ROSITA", 'total' => $presupuesto_rosita->presupuesto, 'total_dia' => $ros->total, 'porcentaje_principal' => $porcentaje_ros, 'porcentaje_restante' => $porcentaje_restante_ros, 'pres_rep' => $pres_rosita, 'pres_tot' => $tot_rosita, 'pres_mo' => $mo_rosita);

				$porcentaje_barranca = ($barran->total * 100) / $presupuesto_barranca->presupuesto;
				$porcentaje_restante_barranca = 100 - $porcentaje_barranca;
				if ($porcentaje_barranca > 100) {
					//$porcentaje_barranca = 100;
					$porcentaje_restante_barranca = 0;
				}
				$diesel_barranca = array('sede' => "CODIESEL BARRANCABERMEJA", 'total' => $presupuesto_barranca->presupuesto, 'total_dia' => $barran->total, 'porcentaje_principal' => $porcentaje_barranca, 'porcentaje_restante' => $porcentaje_restante_barranca, 'pres_rep' => $pres_barranca, 'pres_tot' => $tot_barranca, 'pres_mo' => $mo_barranca);

				$sede_presupuesto = array();

				$sede_presupuesto[] = array($principal, $rosita, $bocono, $diesel_barranca);

				foreach ($sede_presupuesto as $s) {
					foreach ($s as $key) {
						echo '
							<div class="col-md-12" id="nose" aling="center">
							 <div class="card card-info">
									 <div class="card-header">
									    <h3 class="card-title" style="font-size: 30px;"><i class="far fa-building"></i>' . $key['sede'] . '</h3>
									    <div class="card-tools">
									    </div>
									  </div>
									  <div class="card-body" align="center">
									    <div class="col-md-12">
									    	<div class="info-box mb-3">
								              <span class="info-box-icon bg-warning elevation-1" style="font-size: 30px"><i class="fas fa-dollar-sign"></i></span>

								              <div class="info-box-content">
								                <span class="info-box-text" style="font-size: 30px">TOTAL FACTURADO</span>
								                <span class="info-box-number" style="font-size: 30px">' . number_format($key['total_dia'], 0, ",", ",") . '</span>
								                
								              </div>
								              <!-- /.info-box-content -->
								            </div>
						                    <div class="progress-group">
						                      Meta a cumpli a dia de hoy 
						                      <span class="float-right"><b>$' . number_format($key['total_dia'], 0, ",", ",") . '</b>/$' . number_format($key['total'], 0, ",", ",") . '</span>
						                      <div class="progress" style="height: 30px">
						                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width:' . $key['porcentaje_principal'] . '%; font-size: 25px">' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%</div>
						                        <div class="progress-bar bg-danger" role="progressbar" style="width: ' . $key['porcentaje_restante'] . '%;font-size: 25px" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
						                      </div>
						                      <br/>
						                      <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
									  </div>
									  <!-- /.card-body -->
									  <div class="card-footer">
									    
									  </div>
								</div>
							 </div>

						';
					}
				}
			} else {
				//obtenemos primer y ultimo dia del mes actual
				$fecha_ini = $this->nominas->obtener_primer_dia_mes();
				$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
				$presupuesto_total = $this->input->get('presupuesto');

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
				$this->load->model('nominas');
				//obtenemos primer y ultimo dia del mes actual
				$fecha_ini = $this->nominas->obtener_primer_dia_mes();
				$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
				//traemos el presupuesto al dia de hoy
				$to_presupuesto_dia = $this->presupuesto->get_presupuesto_dia($centros_costos);
				/************************************************************************************************************************
				-------------------------------------------------CALCULAR TOTAL SEDES---------------------------------------------------
				 ************************************************************************************************************************/
				$presupuesto_principal = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL PRINCIPAL");
				$presupuesto_bocono = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL VILLA DEL ROSARIO");
				$presupuesto_rosita = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL LA ROSITA");
				$presupuesto_barranca = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL BARRANCABERMEJA");
				$prin = $this->presupuesto->get_presupuesto_dia_principal();
				$boc = $this->presupuesto->get_presupuesto_dia_bocono();
				$ros = $this->presupuesto->get_presupuesto_dia_rosita();
				$barran = $this->presupuesto->get_presupuesto_dia_barranca();

				$porcentaje_principal = ($prin->total * 100) / $presupuesto_principal->presupuesto;
				$porcentaje_restante_principal = 100 - $porcentaje_principal;
				$principal = array('sede' => "CODIESEL PRINCIPAL", 'total' => $presupuesto_principal->presupuesto, 'total_dia' => $prin->total, 'porcentaje_principal' => $porcentaje_principal, 'porcentaje_restante' => $porcentaje_restante_principal);

				$porcentaje_bocono = ($boc->total * 100) / $presupuesto_bocono->presupuesto;
				$porcentaje_restante_boc = 100 - $porcentaje_bocono;
				$bocono = array('sede' => "CODIESEL VILLA DEL ROSARIO", 'total' => $presupuesto_bocono->presupuesto, 'total_dia' => $boc->total, 'porcentaje_principal' => $porcentaje_bocono, 'porcentaje_restante' => $porcentaje_restante_boc);

				$porcentaje_ros = ($ros->total * 100) / $presupuesto_rosita->presupuesto;
				$porcentaje_restante_ros = 100 - $porcentaje_ros;
				$rosita = array('sede' => "CODIESEL LA ROSITA", 'total' => $presupuesto_rosita->presupuesto, 'total_dia' => $ros->total, 'porcentaje_principal' => $porcentaje_ros, 'porcentaje_restante' => $porcentaje_restante_ros);

				$porcentaje_barranca = ($barran->total * 100) / $presupuesto_barranca->presupuesto;
				$porcentaje_restante_barranca = 100 - $porcentaje_barranca;
				$diesel_barranca = array('sede' => "CODIESEL BARRANCABERMEJA", 'total' => $presupuesto_barranca->presupuesto, 'total_dia' => $barran->total, 'porcentaje_principal' => $porcentaje_barranca, 'porcentaje_restante' => $porcentaje_restante_barranca);

				$sede_presupuesto = array();



				$sede_presupuesto[] = array($principal, $rosita, $bocono, $diesel_barranca);
				/*foreach ($sede_presupuesto as $s) {
						foreach ($s as $key) {
							echo $key['sede']." ".$key['total'];
						}
					}*/

				$id_usu = "";
				foreach ($userinfo->result() as $key) {
					$id_usu = $key->id_usuario;
				}
				//echo $id_usu;

				$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'allsedes' => $sede_presupuesto, 'total_presu' => $presupuesto_total);
				//abrimos la vista
				$this->load->view("presupuesto_sedes", $arr_user);
			}
		}
	}

	public function real_time_sedes()
	{
		if ($this->input->get('var')) {
			$this->load->model('presupuesto');
			$this->load->model('nominas');
			$this->load->model('Informe');

			/*CENTRO DE COSTOS*/
			$centros_costos_giron = "4,40,33,45,3";
			$centros_costos_rosita = "16,17";
			$centros_costos_baranca = "13,70,11";
			$centros_costos_bocono = "29,80,31,46,28";
			$centros_costos_chevro = "15";
			$centros_costos_soloch = "60";
			/*MOSTRADOR*/
			$centros_costos_giron_mos = "3";
			$centros_costos_rosita_mos = "17";
			$centros_costos_baranca_mos = "11";
			$centros_costos_bocono_mos = "28";
			/*REP TALLER*/
			$centros_costos_giron_tall = "4,40,33,45";
			$centros_costos_rosita_tall = "16";
			$centros_costos_baranca_tall = "13,70";
			$centros_costos_bocono_tall = "29,80,31,46";
			/**********************/
			//obtenemos primer y ultimo dia del mes actual
			$fecha_ini = $this->nominas->obtener_primer_dia_mes();
			$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();

			/****************************************************************************************************************************
			------------------------------------------------------CALCULAR TOTAL SEDES---------------------------------------------------
			 *****************************************************************************************************************************/
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

			/********************* R E P U E S T O S  T A L L E R************************/
			$pres_rep_gi = $this->presupuesto->get_presupuesto_rep($centros_costos_giron_tall);

			$pres_rep_ro = $this->presupuesto->get_presupuesto_rep($centros_costos_rosita_tall);

			$pres_rep_bo = $this->presupuesto->get_presupuesto_rep($centros_costos_bocono_tall);

			$pres_rep_ba = $this->presupuesto->get_presupuesto_rep($centros_costos_baranca_tall);

			$pres_rep_sol = $this->presupuesto->get_presupuesto_rep($centros_costos_soloch);

			$pres_rep_chev = $this->presupuesto->get_presupuesto_rep($centros_costos_chevro);

			$pres_bocono = $pres_rep_bo->total;
			$pres_rosita = $pres_rep_ro->total;
			$pres_codiesel = $pres_rep_gi->total;
			$pres_barranca = $pres_rep_ba->total;

			/**************************** T  O T ****************************/
			$pres_tot_gi = $this->presupuesto->get_presupuesto_tot($centros_costos_giron);

			$pres_tot_ro = $this->presupuesto->get_presupuesto_tot($centros_costos_rosita);

			$pres_tot_bo = $this->presupuesto->get_presupuesto_tot($centros_costos_bocono);

			$pres_tot_ba = $this->presupuesto->get_presupuesto_tot($centros_costos_baranca);

			$pres_tot_sol = $this->presupuesto->get_presupuesto_tot($centros_costos_soloch);

			$pres_tot_chev = $this->presupuesto->get_presupuesto_tot($centros_costos_chevro);

			$tot_bocono = $pres_tot_bo->total;
			$tot_rosita = $pres_tot_ro->total;
			$tot_codiesel = $pres_tot_gi->total;
			$tot_barranca = $pres_tot_ba->total;

			/********************* M A N O  D E  O B R A *********************/
			$pres_mo_gi = $this->presupuesto->get_presupuesto_mo($centros_costos_giron);

			$pres_mo_ro = $this->presupuesto->get_presupuesto_mo($centros_costos_rosita);

			$pres_mo_bo = $this->presupuesto->get_presupuesto_mo($centros_costos_bocono);

			$pres_mo_ba = $this->presupuesto->get_presupuesto_mo($centros_costos_baranca);

			$pres_mo_sol = $this->presupuesto->get_presupuesto_mo($centros_costos_soloch);

			$pres_mo_chev = $this->presupuesto->get_presupuesto_mo($centros_costos_chevro);


			$mo_bocono = ($pres_mo_bo->total - $tot_bocono);
			$mo_codiesel = ($pres_mo_gi->total - $tot_codiesel);
			$mo_barranca = ($pres_mo_ba->total - $tot_barranca);
			$mo_rosita = ($pres_mo_ro->total - $tot_rosita);
			/*************R E P U E S T O S   M O S T R A D O R*************/
			$pres_rep_mos_g = $this->presupuesto->get_repuestos_mostrador($centros_costos_giron_mos);
			$pres_rep_mos_r = $this->presupuesto->get_repuestos_mostrador($centros_costos_rosita_mos);
			$pres_rep_mos_boc = $this->presupuesto->get_repuestos_mostrador($centros_costos_bocono_mos);
			$pres_rep_mos_ba = $this->presupuesto->get_repuestos_mostrador($centros_costos_baranca_mos);
			/******************************************************************/

			$porcentaje_principal = ($prin->total * 100) / $presupuesto_principal->presupuesto;
			$porcentaje_restante_principal = 100 - $porcentaje_principal;
			if ($porcentaje_principal > 100) {
				//$porcentaje_principal = 100;
				$porcentaje_restante_principal = 0;
			}
			$principal = array('sede' => "CODIESEL PRINCIPAL", 'total' => $presupuesto_principal->presupuesto, 'total_dia' => $prin->total, 'porcentaje_principal' => $porcentaje_principal, 'porcentaje_restante' => $porcentaje_restante_principal, 'pres_rep' => $pres_codiesel, 'pres_tot' => $tot_codiesel, 'pres_mo' => $mo_codiesel, 'porcentaje_objetivo' => $to_objetivo_dia_giron, 'to_objetivo' => $to_objetivo_giron, 'porcen_obj_res' => $porcentaje_restante_dia_giron, 'rep_mos' => $pres_rep_mos_g->total);

			$porcentaje_bocono = ($boc->total * 100) / $presupuesto_bocono->presupuesto;
			$porcentaje_restante_boc = 100 - $porcentaje_bocono;
			if ($porcentaje_bocono > 100) {
				//$porcentaje_bocono = 100;
				$porcentaje_restante_boc = 0;
			}
			$bocono = array('sede' => "CODIESEL VILLA DEL ROSARIO", 'total' => $presupuesto_bocono->presupuesto, 'total_dia' => $boc->total, 'porcentaje_principal' => $porcentaje_bocono, 'porcentaje_restante' => $porcentaje_restante_boc, 'pres_rep' => $pres_bocono, 'pres_tot' => $tot_bocono, 'pres_mo' => $mo_bocono, 'porcentaje_objetivo' => $to_objetivo_dia_bocono, 'to_objetivo' => $to_objetivo_bocono, 'porcen_obj_res' => $porcentaje_restante_dia_bocono, 'rep_mos' => $pres_rep_mos_boc->total);

			$porcentaje_ros = ($ros->total * 100) / $presupuesto_rosita->presupuesto;
			$porcentaje_restante_ros = 100 - $porcentaje_ros;
			if ($porcentaje_ros > 100) {
				//$porcentaje_ros = 100;
				$porcentaje_restante_ros = 0;
			}
			$rosita = array('sede' => "CODIESEL LA ROSITA", 'total' => $presupuesto_rosita->presupuesto, 'total_dia' => $ros->total, 'porcentaje_principal' => $porcentaje_ros, 'porcentaje_restante' => $porcentaje_restante_ros, 'pres_rep' => $pres_rosita, 'pres_tot' => $tot_rosita, 'pres_mo' => $mo_rosita, 'porcentaje_objetivo' => $to_objetivo_dia_rosita, 'to_objetivo' => $to_objetivo_rosita, 'porcen_obj_res' => $porcentaje_restante_dia_rosita, 'rep_mos' => $pres_rep_mos_r->total);

			$porcentaje_barranca = ($barran->total * 100) / $presupuesto_barranca->presupuesto;
			$porcentaje_restante_barranca = 100 - $porcentaje_barranca;
			if ($porcentaje_barranca > 100) {
				//$porcentaje_barranca = 100;
				$porcentaje_restante_barranca = 0;
			}
			$diesel_barranca = array('sede' => "CODIESEL BARRANCABERMEJA", 'total' => $presupuesto_barranca->presupuesto, 'total_dia' => $barran->total, 'porcentaje_principal' => $porcentaje_barranca, 'porcentaje_restante' => $porcentaje_restante_barranca, 'pres_rep' => $pres_barranca, 'pres_tot' => $tot_barranca, 'pres_mo' => $mo_barranca, 'porcentaje_objetivo' => $to_objetivo_dia_barranca, 'to_objetivo' => $to_objetivo_barranca, 'porcen_obj_res' => $porcentaje_restante_dia_barranca, 'rep_mos' => $pres_rep_mos_ba->total);
			/*************************************************  SOLOCHEVROLET  ***************************************************/
			$porcentaje_solochevr = ($solochevr->total * 100) / $presupuesto_solochevr->presupuesto;
			$porcentaje_restante_solochevr = 100 - $porcentaje_solochevr;
			if ($porcentaje_solochevr > 100) {
				//$porcentaje_barranca = 100;
				$porcentaje_restante_solochevr = 0;
			}
			$solochevr_arr = array('sede' => "SOLOCHEVROLET", 'total' => $presupuesto_solochevr->presupuesto, 'total_dia' => $solochevr->total, 'porcentaje_principal' => $porcentaje_solochevr, 'porcentaje_restante' => $porcentaje_restante_solochevr, 'pres_rep' => 0, 'pres_tot' => 0, 'pres_mo' => 0, 'porcentaje_objetivo' => $to_objetivo_dia_solochevr, 'to_objetivo' => $to_objetivo_solochevr, 'porcen_obj_res' => $porcentaje_restante_dia_solochevr, 'rep_mos' => 0);

			/****************************************************  CHEVROPARTES  **********************************************************/
			$porcentaje_chevrop = ($chevrp->total * 100) / $presupuesto_chevrop->presupuesto;
			$porcentaje_restante_chevrop = 100 - $porcentaje_chevrop;
			if ($porcentaje_chevrop > 100) {
				//$porcentaje_barranca = 100;
				$porcentaje_restante_chevrop = 0;
			}
			$chevrop_arr = array('sede' => "CHEVROPARTES", 'total' => $presupuesto_chevrop->presupuesto, 'total_dia' => $chevrp->total, 'porcentaje_principal' => $porcentaje_chevrop, 'porcentaje_restante' => $porcentaje_restante_chevrop, 'pres_rep' => 0, 'pres_tot' => 0, 'pres_mo' => 0, 'porcentaje_objetivo' => $to_objetivo_dia_chevrop, 'to_objetivo' => $to_objetivo_chevrop, 'porcen_obj_res' => $porcentaje_restante_dia_chevrop, 'rep_mos' => 0);

			$sede_presupuesto = array();

			$sede_presupuesto[] = array($principal, $rosita, $bocono, $diesel_barranca, $solochevr_arr, $chevrop_arr);

			foreach ($sede_presupuesto as $s) {
				foreach ($s as $key) {
					if ($key['sede'] == 'SOLOCHEVROLET' || $key['sede'] == 'CHEVROPARTES') {
						echo '<div class="col-md-4" id="nose" aling="center" style="font-size: 13px;">
										<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['sede'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcen_obj_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-success btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_principal'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									   <br>
									  </div>
							
						            </div>

						            </div>';
					} else {
						echo '
							<div class="col-md-4" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['sede'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcen_obj_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-success btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_principal'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													<table class="table table-bordered table-sm" aling="center">
													  <thead>
													    <tr aling="center">
													      <th scope="col">TOT</th>
													      <th scope="col">MO</th>
													      <th scope="col">REP TALL</th>
													      <th scope="col">REP MOS</th>
													    </tr>
													  </thead>
													  <tbody>
													    <tr>
													      <th scope="row">$' . number_format($key['pres_tot'], 0, ",", ",") . '</th>
													      <th scope="row">$' . number_format($key['pres_mo'], 0, ",", ",") . '</th>
													      <th scope="row">$' . number_format($key['pres_rep'], 0, ",", ",") . '</th>
													      <th scope="row">$' . number_format($key['rep_mos'], 0, ",", ",") . '</th>
													    </tr>
													  </tbody>
													</table>
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											<a href="' . base_url() . 'admin_presupuesto/presupuesto_taller?sede=' . $key['sede'] . '&presupuesto=' . $key['total_dia'] . '" class="small-box-footer" style="color: black;">
								                Mas detalles <i class="fas fa-arrow-circle-right"></i>
								              </a>
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						                      

						';
					}
				}
			}
		}
	}

	public function real_time_taller()
	{
		$this->load->model('presupuesto');
		$this->load->model('Informe');
		$sede = $this->input->get('sede');
		/*CENTROS DE COSTOS*/
		$centros_costos = "4,40,33,45,3,16,17,13,70,11,29,80,31,46,28,60,15";
		$centros_costos_giron = "4,40,33,45,3";
		$centros_costos_rosita = "16,17";
		$centros_costos_baranca = "13,70,11";
		$centros_costos_bocono = "29,80,31,46,28";
		$centros_costos_chevro = "15";
		$centros_costos_soloch = "60";
		/*GIRON*/
		$cc_tall_giron_gas = "4";
		$cc_tall_giron_dies = "40";
		$cc_tall_giron_col = "33,45";
		$cc_mostrador_giron = "3";
		/*ROSITA*/
		$cc_tall_rosita = "16";
		$cc_mostrador_rosita = "17";
		/*BARRANCA*/
		$cc_tall_barranca_gas = "13";
		$cc_tall_barranca_dies = "70";
		$cc_mostrador_barranca = "11";
		/*BOCONO*/
		$cc_tall_bocono_gas = "29";
		$cc_tall_bocono_dies = "80";
		$cc_tall_bocono_col = "31,46";
		$cc_mostrador_bocono = "28";

		/************************************************************************/
		$data = array();
		$diesel_principal = array();
		$lyp_principal = array();
		$gasolina_principal = array();
		$diesel_barranca = array();
		$chevy_principal = array();
		$diesel_bocono = array();
		$lyp_bocono = array();
		$gasolina_bocono = array();
		$this->load->model('nominas');
		//obtenemos primer y ultimo dia del mes actual
		$fecha_ini = $this->nominas->obtener_primer_dia_mes();
		$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
		$dia_actual = $this->nominas->get_dia_actual();
		$mes_actual = $this->nominas->get_mes_actual();
		$ano_actual = $this->nominas->get_ano_actual();
		$fecha_inicial = $ano_actual->ano . "-01-" . $mes_actual->mes;
		$fecha_final = $ano_actual->ano . "-" . $dia_actual->dia_actual . "-" . $mes_actual->mes;
		if ($sede == "CODIESEL PRINCIPAL") {
			$v_t_tall_di_g = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "GIRON DIESEL EXPRESS");
			$v_t_tall_gas_g = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "GIRON GASOLINA");
			$v_t_tall_lyp_g = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "GIRON LAMINA Y PINTURA");
			$v_t_tall_mos_g = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "GIRON REPUESTOS MOSTRADOR");

			/*$v_d_tall_gas_g = $this->presupuesto->get_total_presupuesto_by_tallerx2("01/03/2020","31/03/2020","TP","DTP");
				$v_d_tall_di_g = $this->presupuesto->get_total_presupuesto_by_tallerx2("01/03/2020","31/03/2020","TE","DTE");
				$v_d_tall_lyp_g = $this->presupuesto->get_total_presupuesto_by_tallerx2("01/03/2020","31/03/2020","TL","DTL");*/
			//PRUEBA
			$v_d_tall_di_g = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_giron_dies);
			$v_d_tall_gas_g = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_giron_gas);
			$v_d_tall_lyp_g = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_giron_col);
			$v_d_tall_mos_g = $this->presupuesto->get_repuestos_mostrador($cc_mostrador_giron);
			/***********************************************************************************************************************/
			/*********************************** calcular porcentaje ideal mostrador********************************************/
			$to_dias_mes = $this->Informe->get_total_dias();
			$to_dias_hoy = $this->Informe->get_dias_actual();
			$n_to_dias = $to_dias_mes->ultimo_dia;
			$n_dias_hoy = $to_dias_hoy->dia;
			//LO QUE DEBEMOS LLEBAR A DIA DE HOY
			$to_objetivo_mos = ($v_t_tall_mos_g->presupuesto / $n_to_dias) * $n_dias_hoy; //
			//PORCENTAJE IDEAL MES
			$porcentaje_mos_g = ($v_d_tall_mos_g->total / $v_t_tall_mos_g->presupuesto) * 100;
			$porcentaje_restante_mos_g = 100 - $porcentaje_mos_g;
			if ($porcentaje_mos_g > 100) {
				//$porcentaje_ros = 100;
				$porcentaje_restante_mos_g = 0;
			}
			//PORCENTAJE IDEAL DIA
			$porcentaje_objetivo_mos = ($v_d_tall_mos_g->total / $to_objetivo_mos) * 100;
			$porcentaje_objetivo_restante_mos = (100 - $porcentaje_objetivo_mos);
			if ($porcentaje_objetivo_restante_mos < 0) {
				$porcentaje_objetivo_restante_mos = 0;
			}
			/*********************************** calcular porcentaje ideal gasolina********************************************/
			$to_dias_mes = $this->Informe->get_total_dias();
			$to_dias_hoy = $this->Informe->get_dias_actual();
			$n_to_dias = $to_dias_mes->ultimo_dia;
			$n_dias_hoy = $to_dias_hoy->dia;
			//LO QUE DEBEMOS LLEBAR A DIA DE HOY
			$to_objetivo_gasolina = ($v_t_tall_gas_g->presupuesto / $n_to_dias) * $n_dias_hoy; //
			//PORCENTAJE IDEAL MES
			$porcentaje_gas_g = ($v_d_tall_gas_g->total / $v_t_tall_gas_g->presupuesto) * 100;
			$porcentaje_restante_gas_g = 100 - $porcentaje_gas_g;
			if ($porcentaje_gas_g > 100) {
				//$porcentaje_ros = 100;
				$porcentaje_restante_gas_g = 0;
			}
			//PORCENTAJE IDEAL DIA
			$porcentaje_objetivo_gasolina = ($v_d_tall_gas_g->total / $to_objetivo_gasolina) * 100;
			$porcentaje_objetivo_restante_gasolina = (100 - $porcentaje_objetivo_gasolina);
			if ($porcentaje_objetivo_restante_gasolina < 0) {
				$porcentaje_objetivo_restante_gasolina = 0;
			}
			/*********************************** calcular porcentaje ideal diesel********************************************/
			$to_dias_mes = $this->Informe->get_total_dias();
			$to_dias_hoy = $this->Informe->get_dias_actual();
			$n_to_dias = $to_dias_mes->ultimo_dia;
			$n_dias_hoy = $to_dias_hoy->dia;
			//LO QUE DEBEMOS LLEBAR A DIA DE HOY
			$to_objetivo_diesel = ($v_t_tall_di_g->presupuesto / $n_to_dias) * $n_dias_hoy;
			//PORCENTAJE IDEAL MES
			$porcentaje_di_g = ($v_d_tall_di_g->total / $v_t_tall_di_g->presupuesto) * 100;
			$porcentaje_restante_di_g = 100 - $porcentaje_di_g;
			if ($porcentaje_restante_di_g < 0) {
				//$porcentaje_ros = 100;
				$porcentaje_restante_di_g = 0;
			}

			$porcentaje_objetivo_diesel = ($v_d_tall_di_g->total / $to_objetivo_diesel) * 100;
			$porcentaje_objetivo_restante_diesel = (100 - $porcentaje_objetivo_diesel);
			if ($porcentaje_objetivo_restante_diesel < 0) {
				$porcentaje_objetivo_restante_diesel = 0;
			}
			/*********************************** calcular porcentaje ideal lyp********************************************/
			$to_dias_mes = $this->Informe->get_total_dias();
			$to_dias_hoy = $this->Informe->get_dias_actual();
			$n_to_dias = $to_dias_mes->ultimo_dia;
			$n_dias_hoy = $to_dias_hoy->dia;
			//LO QUE DEBEMOS LLEVAR A HOY
			$to_objetivo_lyp = ($v_t_tall_lyp_g->presupuesto / $n_to_dias) * $n_dias_hoy;
			//PORCENTAJE IDEAL MES
			$porcentaje_lyp_g = ($v_d_tall_lyp_g->total * 100) / $v_t_tall_lyp_g->presupuesto;
			$porcentaje_restante_lyp_g = 100 - $porcentaje_lyp_g;
			//echo $porcentaje_restante_lyp_g;
			if ($porcentaje_restante_lyp_g < 0) {
				//$porcentaje_lyp_g = "100";
				$porcentaje_restante_lyp_g = 0;
			}
			//PORCENTAJE IDEAL A HOY
			$porcentaje_objetivo_lyp = ($v_d_tall_lyp_g->total / $to_objetivo_lyp) * 100;
			$porcentaje_objetivo_restante_lyp = (100 - $porcentaje_objetivo_lyp);
			if ($porcentaje_objetivo_restante_lyp < 0) {
				$porcentaje_objetivo_restante_lyp = 0;
			}
			/***********************************************************************************************************************/
			$diesel_prin = array('sede' => "TALLER DIESEL GIRON", 'total' => $v_t_tall_di_g->presupuesto, 'total_dia' => $v_d_tall_di_g->total, 'porcentaje_principal' => $porcentaje_di_g, 'porcentaje_restante' => $porcentaje_restante_di_g, 'porcentaje_objetivo' => $porcentaje_objetivo_diesel, 'porcen_obj_res' => $porcentaje_objetivo_restante_diesel, 'to_objetivo' => $to_objetivo_diesel);
			/***********************************************************************************************************************/

			$gas_prin = array('sede' => "TALLER GASOLINA GIRON", 'total' => $v_t_tall_gas_g->presupuesto, 'total_dia' => $v_d_tall_gas_g->total, 'porcentaje_principal' => $porcentaje_gas_g, 'porcentaje_restante' => $porcentaje_restante_gas_g, 'porcentaje_objetivo' => $porcentaje_objetivo_gasolina, 'porcen_obj_res' => $porcentaje_objetivo_restante_gasolina, 'to_objetivo' => $to_objetivo_gasolina);
			/***********************************************************************************************************************/

			$lyp_principal = array('sede' => "TALLER LAMINA Y PINTURA GIRON", 'total' => $v_t_tall_lyp_g->presupuesto, 'total_dia' => $v_d_tall_lyp_g->total, 'porcentaje_principal' => $porcentaje_lyp_g, 'porcentaje_restante' => $porcentaje_restante_lyp_g, 'porcentaje_objetivo' => $porcentaje_objetivo_lyp, 'porcen_obj_res' => $porcentaje_objetivo_restante_lyp, 'to_objetivo' => $to_objetivo_lyp);
			/***********************************************************************************************************************/
			$mos_principal = array('sede' => "REPUESTOS MOSTRADOR GIRON", 'total' => $v_t_tall_mos_g->presupuesto, 'total_dia' => $v_d_tall_mos_g->total, 'porcentaje_principal' => $porcentaje_mos_g, 'porcentaje_restante' => $porcentaje_restante_mos_g, 'porcentaje_objetivo' => $porcentaje_objetivo_mos, 'porcen_obj_res' => $porcentaje_objetivo_restante_mos, 'to_objetivo' => $to_objetivo_mos);
			/***********************************************************************************************************************/

			$sede_presupuesto[] = array($diesel_prin, $gas_prin, $lyp_principal, $mos_principal);

			foreach ($sede_presupuesto as $s) {
				foreach ($s as $key) {
					if ($key['sede'] == 'REPUESTOS MOSTRADOR GIRON') {
						echo '
						<div class="col-md-6" id="nose" aling="center" style="font-size: 13px;">
										<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['sede'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcen_obj_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-success btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_principal'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									   <br>
									  </div>
							
						            </div>

						            </div>

					';
					} else {
						echo '
						<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['sede'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcen_obj_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-success btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_principal'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											<a href="' . base_url() . 'admin_presupuesto/presupuesto_tipo_operaciones?bodega=' . $key['sede'] . '&presupuesto=' . $key['total_dia'] . '" class="small-box-footer" style="color: black;">
								                Mas detalles <i class="fas fa-arrow-circle-right"></i>
								              </a>
									  </div>
									  <!-- /.card-body -->
									  
								</div>

					';
					}
				}
			}
		} elseif ($sede == "CODIESEL LA ROSITA") {
			$v_t_tall_di_g = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "ROSITA CHEVY ESPRES");
			$v_t_tall_mos_g = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "ROSITA REPUESTOS MOSTRADOR");


			//$v_d_tall_di_g = $this->presupuesto->get_total_presupuesto_by_tallerx2("01/03/2020","31/03/2020","TR","DTR");
			//PRUEBA
			$v_d_tall_di_g = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_rosita);
			$v_d_tall_mos_g = $this->presupuesto->get_repuestos_mostrador($cc_mostrador_rosita);

			/***********************************************************************************************************************/
			/*********************************** calcular porcentaje ideal mostrador********************************************/
			$to_dias_mes = $this->Informe->get_total_dias();
			$to_dias_hoy = $this->Informe->get_dias_actual();
			$n_to_dias = $to_dias_mes->ultimo_dia;
			$n_dias_hoy = $to_dias_hoy->dia;
			//LO QUE DEBEMOS LLEBAR A DIA DE HOY
			$to_objetivo_mos = ($v_t_tall_mos_g->presupuesto / $n_to_dias) * $n_dias_hoy; //
			//PORCENTAJE IDEAL MES
			$porcentaje_mos_g = ($v_d_tall_mos_g->total / $v_t_tall_mos_g->presupuesto) * 100;
			$porcentaje_restante_mos_g = 100 - $porcentaje_mos_g;
			if ($porcentaje_mos_g > 100) {
				//$porcentaje_ros = 100;
				$porcentaje_restante_mos_g = 0;
			}
			//PORCENTAJE IDEAL DIA
			$porcentaje_objetivo_mos = ($v_d_tall_mos_g->total / $to_objetivo_mos) * 100;
			$porcentaje_objetivo_restante_mos = (100 - $porcentaje_objetivo_mos);
			if ($porcentaje_objetivo_restante_mos < 0) {
				$porcentaje_objetivo_restante_mos = 0;
			}
			/*********************************** calcular porcentaje ideal rosita********************************************/
			$to_dias_mes = $this->Informe->get_total_dias();
			$to_dias_hoy = $this->Informe->get_dias_actual();
			$n_to_dias = $to_dias_mes->ultimo_dia;
			$n_dias_hoy = $to_dias_hoy->dia;
			//LO QUE DEBEMOS LLEVAR A HOY
			$to_objetivo_rosita = ($v_t_tall_di_g->presupuesto / $n_to_dias) * $n_dias_hoy;
			//PORCENTAJE IDEAL MES
			$porcentaje_r = ($v_d_tall_di_g->total * 100) / $v_t_tall_di_g->presupuesto;
			$porcentaje_restante_r = 100 - $porcentaje_r;
			if ($porcentaje_r > 100) {
				//$porcentaje_ros = 100;
				$porcentaje_restante_r = 0;
			}
			$porcentaje_objetivo_rosita = ($v_d_tall_di_g->total / $to_objetivo_rosita) * 100;
			$porcentaje_objetivo_restante_rosita = (100 - $porcentaje_objetivo_rosita);
			if ($porcentaje_objetivo_restante_rosita < 0) {
				$porcentaje_objetivo_restante_rosita = 0;
			}
			/**************************************************************************************************************************/

			$chevy_rosita = array('sede' => "ROSITA CHEVYEXPRESS", 'total' => $v_t_tall_di_g->presupuesto, 'total_dia' => $v_d_tall_di_g->total, 'porcentaje_principal' => $porcentaje_r, 'porcentaje_restante' => $porcentaje_restante_r, 'porcentaje_objetivo' => $porcentaje_objetivo_rosita, 'porcen_obj_res' => $porcentaje_objetivo_restante_rosita, 'to_objetivo' => $to_objetivo_rosita);
			/***********************************************************************************************************************/
			$mos_rosita = array('sede' => "REPUESTOS MOSTRADOR ROSITA", 'total' => $v_t_tall_mos_g->presupuesto, 'total_dia' => $v_d_tall_mos_g->total, 'porcentaje_principal' => $porcentaje_mos_g, 'porcentaje_restante' => $porcentaje_restante_mos_g, 'porcentaje_objetivo' => $porcentaje_objetivo_mos, 'porcen_obj_res' => $porcentaje_objetivo_restante_mos, 'to_objetivo' => $to_objetivo_mos);
			/***********************************************************************************************************************/
			/***********************************************************************************************************************/
			$sede_presupuesto[] = array($chevy_rosita, $mos_rosita);

			foreach ($sede_presupuesto as $s) {
				foreach ($s as $key) {
					if ($key['sede'] == 'REPUESTOS MOSTRADOR ROSITA') {
						echo '
						<div class="col-md-6" id="nose" aling="center" style="font-size: 13px;">
										<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['sede'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcen_obj_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-success btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_principal'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									   <br>
									  </div>
							
						            </div>

						            </div>

					';
					} else {
						echo '
						<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['sede'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcen_obj_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-success btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_principal'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											<a href="' . base_url() . 'admin_presupuesto/presupuesto_tipo_operaciones?bodega=' . $key['sede'] . '&presupuesto=' . $key['total_dia'] . '" class="small-box-footer" style="color: black;>
								                Mas detalles <i class="fas fa-arrow-circle-right"></i>
								              </a>
									  </div>
									  <!-- /.card-body -->
									  
								</div>
					';
					}
				}
			}
		} elseif ($sede == "CODIESEL VILLA DEL ROSARIO") {
			$v_t_tall_di_g = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "DIESEL EXPRESS BOCONO");
			$v_t_tall_gas_g = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "BOCONO GASOLINA");
			$v_t_tall_lyp_g = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "BOCONO LAMINA Y PINTURA");
			$v_t_tall_mos_g = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "BOCONO REPUESTOS MOSTRADOR");

			/*$v_d_tall_gas_g = $this->presupuesto->get_total_presupuesto_by_tallerx2("01/03/2020","31/03/2020","WT","DWT");
				$v_d_tall_di_g = $this->presupuesto->get_total_presupuesto_by_tallerx2("01/03/2020","31/03/2020","WE","DWE");
				$v_d_tall_lyp_g = $this->presupuesto->get_total_presupuesto_by_tallerx2("01/03/2020","31/03/2020","WL","DWL");*/
			//PRUEBA
			$v_d_tall_di_g = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_bocono_dies);
			$v_d_tall_gas_g = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_bocono_gas);
			$v_d_tall_lyp_g = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_bocono_col);
			$v_d_tall_mos_g = $this->presupuesto->get_repuestos_mostrador($cc_mostrador_bocono);
			/***********************************************************************************************************************/
			/*********************************** calcular porcentaje ideal mostrador********************************************/
			$to_dias_mes = $this->Informe->get_total_dias();
			$to_dias_hoy = $this->Informe->get_dias_actual();
			$n_to_dias = $to_dias_mes->ultimo_dia;
			$n_dias_hoy = $to_dias_hoy->dia;
			//LO QUE DEBEMOS LLEBAR A DIA DE HOY
			$to_objetivo_mos = ($v_t_tall_mos_g->presupuesto / $n_to_dias) * $n_dias_hoy; //
			//PORCENTAJE IDEAL MES
			$porcentaje_mos_g = ($v_d_tall_mos_g->total / $v_t_tall_mos_g->presupuesto) * 100;
			$porcentaje_restante_mos_g = 100 - $porcentaje_mos_g;
			if ($porcentaje_mos_g > 100) {
				//$porcentaje_ros = 100;
				$porcentaje_restante_mos_g = 0;
			}
			//PORCENTAJE IDEAL DIA
			$porcentaje_objetivo_mos = ($v_d_tall_mos_g->total / $to_objetivo_mos) * 100;
			$porcentaje_objetivo_restante_mos = (100 - $porcentaje_objetivo_mos);
			if ($porcentaje_objetivo_restante_mos < 0) {
				$porcentaje_objetivo_restante_mos = 0;
			}
			/*********************************** calcular porcentaje ideal gasolina********************************************/
			$to_dias_mes = $this->Informe->get_total_dias();
			$to_dias_hoy = $this->Informe->get_dias_actual();
			$n_to_dias = $to_dias_mes->ultimo_dia;
			$n_dias_hoy = $to_dias_hoy->dia;
			//LO QUE DEBEMOS LLEVAR A DIA DE HOY
			$to_objetivo_gasolina = ($v_t_tall_gas_g->presupuesto / $n_to_dias) * $n_dias_hoy;
			//PORCENTAJE IDEAL MES
			$porcentaje_gas_g = ($v_d_tall_gas_g->total * 100) / $v_t_tall_gas_g->presupuesto;
			$porcentaje_restante_gas_g = 100 - $porcentaje_gas_g;
			if ($porcentaje_gas_g > 100) {
				//$porcentaje_ros = 100;
				$porcentaje_restante_gas_g = 0;
			}
			//PORCENTAJE IDEAL DIA
			$porcentaje_objetivo_gasolina = ($v_d_tall_gas_g->total / $to_objetivo_gasolina) * 100;
			$porcentaje_objetivo_restante_gasolina = (100 - $porcentaje_objetivo_gasolina);
			if ($porcentaje_objetivo_restante_gasolina < 0) {
				$porcentaje_objetivo_restante_gasolina = 0;
			}
			/*********************************** calcular porcentaje ideal diesel********************************************/
			$to_dias_mes = $this->Informe->get_total_dias();
			$to_dias_hoy = $this->Informe->get_dias_actual();
			$n_to_dias = $to_dias_mes->ultimo_dia;
			$n_dias_hoy = $to_dias_hoy->dia;
			//LO QUE DEBEMOS LLEVAR A HOY
			$to_objetivo_diesel = ($v_t_tall_di_g->presupuesto / $n_to_dias) * $n_dias_hoy;
			//PORCENTAJE IDEAL MES
			$porcentaje_di_g = ($v_d_tall_di_g->total * 100) / $v_t_tall_di_g->presupuesto;
			//echo $porcentaje_di_g." ".$v_t_tall_di_g->presupuesto;
			$porcentaje_restante_di_g = 100 - $porcentaje_di_g;
			if ($porcentaje_di_g > 100) {
				//$porcentaje_ros = 100;
				$porcentaje_restante_di_g = 0;
			}
			//PORCENTAJE IDEAL DIA
			$porcentaje_objetivo_diesel = ($v_d_tall_di_g->total / $to_objetivo_diesel) * 100;
			$porcentaje_objetivo_restante_diesel = (100 - $porcentaje_objetivo_diesel);
			if ($porcentaje_objetivo_restante_diesel < 0) {
				$porcentaje_objetivo_restante_diesel = 0;
			}
			/*********************************** calcular porcentaje ideal lyp********************************************/
			$to_dias_mes = $this->Informe->get_total_dias();
			$to_dias_hoy = $this->Informe->get_dias_actual();
			$n_to_dias = $to_dias_mes->ultimo_dia;
			$n_dias_hoy = $to_dias_hoy->dia;
			//LO QUE DEBEMOS LLEVAR A DIA DE HOY
			$to_objetivo_lyp = ($v_t_tall_lyp_g->presupuesto / $n_to_dias) * $n_dias_hoy;
			//PORCENTAJE IDEAL MES
			$porcentaje_lyp_g = ($v_d_tall_lyp_g->total * 100) / $v_t_tall_lyp_g->presupuesto;
			$porcentaje_restante_lyp_g = 100 - $porcentaje_lyp_g;
			if ($porcentaje_lyp_g > 100) {
				//$porcentaje_ros = 100;
				$porcentaje_restante_lyp_g = 0;
			}
			//PORCENTAJE IDEAL DIA

			$porcentaje_objetivo_lyp = ($v_d_tall_lyp_g->total / $to_objetivo_lyp) * 100;
			$porcentaje_objetivo_restante_lyp = (100 - $porcentaje_objetivo_lyp);
			if ($porcentaje_objetivo_restante_lyp < 0) {
				$porcentaje_objetivo_restante_lyp = 0;
			}
			/***********************************************************************************************************************/
			$mos_bocono = array('sede' => "REPUESTOS MOSTRADOR BOCONO", 'total' => $v_t_tall_mos_g->presupuesto, 'total_dia' => $v_d_tall_mos_g->total, 'porcentaje_principal' => $porcentaje_mos_g, 'porcentaje_restante' => $porcentaje_restante_mos_g, 'porcentaje_objetivo' => $porcentaje_objetivo_mos, 'porcen_obj_res' => $porcentaje_objetivo_restante_mos, 'to_objetivo' => $to_objetivo_mos);
			/***********************************************************************************************************************/

			$diesel_prin = array('sede' => "TALLER DIESEL BOCONO", 'total' => $v_t_tall_di_g->presupuesto, 'total_dia' => $v_d_tall_di_g->total, 'porcentaje_principal' => $porcentaje_di_g, 'porcentaje_restante' => $porcentaje_restante_di_g, 'porcentaje_objetivo' => $porcentaje_objetivo_diesel, 'porcen_obj_res' => $porcentaje_objetivo_restante_diesel, 'to_objetivo' => $to_objetivo_diesel);
			/***********************************************************************************************************************/

			$gas_prin = array('sede' => "TALLER GASOLINA BOCONO", 'total' => $v_t_tall_gas_g->presupuesto, 'total_dia' => $v_d_tall_gas_g->total, 'porcentaje_principal' => $porcentaje_gas_g, 'porcentaje_restante' => $porcentaje_restante_gas_g, 'porcentaje_objetivo' => $porcentaje_objetivo_gasolina, 'porcen_obj_res' => $porcentaje_objetivo_restante_gasolina, 'to_objetivo' => $to_objetivo_gasolina);
			/***********************************************************************************************************************/

			$lyp_principal = array('sede' => "TALLER LAMINA Y PINTURA BOCONO", 'total' => $v_t_tall_lyp_g->presupuesto, 'total_dia' => $v_d_tall_lyp_g->total, 'porcentaje_principal' => $porcentaje_lyp_g, 'porcentaje_restante' => $porcentaje_restante_lyp_g, 'porcentaje_objetivo' => $porcentaje_objetivo_lyp, 'porcen_obj_res' => $porcentaje_objetivo_restante_lyp, 'to_objetivo' => $to_objetivo_lyp);
			/***********************************************************************************************************************/
			/***********************************************************************************************************************/
			$sede_presupuesto[] = array($diesel_prin, $gas_prin, $lyp_principal, $mos_bocono);

			foreach ($sede_presupuesto as $s) {
				foreach ($s as $key) {
					if ($key['sede'] == 'REPUESTOS MOSTRADOR BOCONO') {
						echo '
						<div class="col-md-6" id="nose" aling="center" style="font-size: 13px;">
										<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['sede'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcen_obj_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-success btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_principal'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									   <br>
									  </div>
							
						            </div>

						            </div>

					';
					} else {
						echo '
						<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['sede'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcen_obj_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-success btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_principal'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											<a href="' . base_url() . 'admin_presupuesto/presupuesto_tipo_operaciones?bodega=' . $key['sede'] . '&presupuesto=' . $key['total_dia'] . '" class="small-box-footer" style="color: black;>
								                Mas detalles <i class="fas fa-arrow-circle-right"></i>
								              </a>
									  </div>
									  <!-- /.card-body -->
									  
								</div>

					';
					}
				}
			}
		} elseif ($sede == "CODIESEL BARRANCABERMEJA") {
			$v_t_tall_di_g = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "BARRANCA DIESEL EXPRESS");
			$v_t_tall_gas_g = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "BARRANCA CHEVRY EXPRESS");
			$v_t_tall_mos_g = $this->presupuesto->get_presupuesto_mes_sedes($fecha_ini->fecha, $fecha_fin->fecha, "BARRANCA REPUESTOS MOSTRADOR");

			/*$v_d_tall_di_g = $this->presupuesto->get_total_presupuesto_by_tallerx2("01/03/2020","31/03/2020","EB","DBE");
				$v_d_tall_gas_g = $this->presupuesto->get_total_presupuesto_by_tallerx2("01/03/2020","31/03/2020","TK","DTK");*/
			//PRUEBA
			$v_d_tall_di_g = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_barranca_dies);
			$v_d_tall_gas_g = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_barranca_gas);
			$v_d_tall_mos_g = $this->presupuesto->get_repuestos_mostrador($cc_mostrador_barranca);

			/***********************************************************************************************************************/ /*********************************** calcular porcentaje ideal mostrador********************************************/
			$to_dias_mes = $this->Informe->get_total_dias();
			$to_dias_hoy = $this->Informe->get_dias_actual();
			$n_to_dias = $to_dias_mes->ultimo_dia;
			$n_dias_hoy = $to_dias_hoy->dia;
			//LO QUE DEBEMOS LLEBAR A DIA DE HOY
			$to_objetivo_mos = ($v_t_tall_mos_g->presupuesto / $n_to_dias) * $n_dias_hoy; //
			//PORCENTAJE IDEAL MES
			$porcentaje_mos_g = ($v_d_tall_mos_g->total / $v_t_tall_mos_g->presupuesto) * 100;
			$porcentaje_restante_mos_g = 100 - $porcentaje_mos_g;
			if ($porcentaje_mos_g > 100) {
				//$porcentaje_ros = 100;
				$porcentaje_restante_mos_g = 0;
			}
			//PORCENTAJE IDEAL DIA
			$porcentaje_objetivo_mos = ($v_d_tall_mos_g->total / $to_objetivo_mos) * 100;
			$porcentaje_objetivo_restante_mos = (100 - $porcentaje_objetivo_mos);
			if ($porcentaje_objetivo_restante_mos < 0) {
				$porcentaje_objetivo_restante_mos = 0;
			}
			/*********************************** calcular porcentaje ideal gasolina********************************************/
			$to_dias_mes = $this->Informe->get_total_dias();
			$to_dias_hoy = $this->Informe->get_dias_actual();
			$n_to_dias = $to_dias_mes->ultimo_dia;
			$n_dias_hoy = $to_dias_hoy->dia;
			//LO QUE DEBEMOS LLEVAR A HOY
			$to_objetivo_gasolina = ($v_t_tall_gas_g->presupuesto / $n_to_dias) * $n_dias_hoy;
			//PORCENTAJE IDEAL MES
			$porcentaje_gas_g = ($v_d_tall_gas_g->total * 100) / $v_t_tall_gas_g->presupuesto;
			$porcentaje_restante_gas_g = 100 - $porcentaje_gas_g;
			if ($porcentaje_restante_gas_g < 0) {
				$porcentaje_restante_gas_g = 0;
			}
			$porcentaje_objetivo_gasolina = ($v_d_tall_gas_g->total / $to_objetivo_gasolina) * 100;
			$porcentaje_objetivo_restante_gasolina = (100 - $porcentaje_objetivo_gasolina);
			if ($porcentaje_objetivo_restante_gasolina < 0) {
				$porcentaje_objetivo_restante_gasolina = 0;
			}
			/*********************************** calcular porcentaje ideal diesel********************************************/
			$to_dias_mes = $this->Informe->get_total_dias();
			$to_dias_hoy = $this->Informe->get_dias_actual();
			$n_to_dias = $to_dias_mes->ultimo_dia;
			$n_dias_hoy = $to_dias_hoy->dia;
			//LO QUE DEBEMOS LLEVAR A HOY
			$to_objetivo_diesel = ($v_t_tall_di_g->presupuesto / $n_to_dias) * $n_dias_hoy;
			//PORCENTAJE IDEAL MES
			$porcentaje_di_g = ($v_d_tall_di_g->total * 100) / $v_t_tall_di_g->presupuesto;
			$porcentaje_restante_di_g = 100 - $porcentaje_di_g;
			if ($porcentaje_di_g > 100) {
				//$porcentaje_ros = 100;
				$porcentaje_restante_di_g = 0;
			}
			//PORCENTAJE IDEAL DIA
			$porcentaje_objetivo_diesel = ($v_d_tall_di_g->total / $to_objetivo_diesel) * 100;
			$porcentaje_objetivo_restante_diesel = (100 - $porcentaje_objetivo_diesel);
			if ($porcentaje_objetivo_restante_diesel < 0) {
				$porcentaje_objetivo_restante_diesel = 0;
			}
			/*******************************************************************************************************************/

			$diesel_prin = array('sede' => "TALLER DIESEL BARRANCA", 'total' => $v_t_tall_di_g->presupuesto, 'total_dia' => $v_d_tall_di_g->total, 'porcentaje_principal' => $porcentaje_di_g, 'porcentaje_restante' => $porcentaje_restante_di_g, 'porcentaje_objetivo' => $porcentaje_objetivo_diesel, 'porcen_obj_res' => $porcentaje_objetivo_restante_diesel, 'to_objetivo' => $to_objetivo_diesel);
			/***********************************************************************************************************************/

			$gas_prin = array('sede' => "TALLER GASOLINA BARRANCA", 'total' => $v_t_tall_gas_g->presupuesto, 'total_dia' => $v_d_tall_gas_g->total, 'porcentaje_principal' => $porcentaje_gas_g, 'porcentaje_restante' => $porcentaje_restante_gas_g, 'porcentaje_objetivo' => $porcentaje_objetivo_gasolina, 'porcen_obj_res' => $porcentaje_objetivo_restante_gasolina, 'to_objetivo' => $to_objetivo_gasolina);
			/***********************************************************************************************************************/
			/***********************************************************************************************************************/
			$mos_barranca = array('sede' => "REPUESTOS MOSTRADOR BARRANCA", 'total' => $v_t_tall_mos_g->presupuesto, 'total_dia' => $v_d_tall_mos_g->total, 'porcentaje_principal' => $porcentaje_mos_g, 'porcentaje_restante' => $porcentaje_restante_mos_g, 'porcentaje_objetivo' => $porcentaje_objetivo_mos, 'porcen_obj_res' => $porcentaje_objetivo_restante_mos, 'to_objetivo' => $to_objetivo_mos);
			/***********************************************************************************************************************/

			$sede_presupuesto[] = array($diesel_prin, $gas_prin, $mos_barranca);

			foreach ($sede_presupuesto as $s) {
				foreach ($s as $key) {
					if ($key['sede'] == 'REPUESTOS MOSTRADOR BARRANCA') {
						echo '
						<div class="col-md-6" id="nose" aling="center" style="font-size: 13px;">
										<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['sede'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcen_obj_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-success btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_principal'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									   <br>
									  </div>
							
						            </div>

						            </div>

					';
					} else {
						echo '
						<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['sede'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_objetivo'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcen_obj_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcen_obj_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-success btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_principal'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_principal'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											<a href="' . base_url() . 'admin_presupuesto/presupuesto_tipo_operaciones?bodega=' . $key['sede'] . '&presupuesto=' . $key['total_dia'] . '" class="small-box-footer" style="color: black;>
								                Mas detalles <i class="fas fa-arrow-circle-right"></i>
								              </a>
									  </div>
									  <!-- /.card-body -->
									  
								</div>
					';
					}
				}
			}
		}
	}

	public function presupuesto_taller()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('sedes');
			$this->load->model('presupuesto');
			$this->load->model('nominas');
			//obtenemos primer y ultimo dia del mes actual
			$fecha_ini = $this->nominas->obtener_primer_dia_mes();
			$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
			$presupuesto_total = $this->input->get('presupuesto');

			$sede = $this->input->get('sede');
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
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}

			$sede = $this->input->get('sede');
			$data = array();
			$diesel_principal = array();
			$lyp_principal = array();
			$gasolina_principal = array();
			$diesel_barranca = array();
			$chevy_principal = array();
			$diesel_bocono = array();
			$lyp_bocono = array();
			$gasolina_bocono = array();
			if ($sede == "CODIESEL PRINCIPAL") {
				$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'presu' => $presupuesto_total, 'sede' => $sede);
				//abrimos la vista
				$this->load->view("presupuesto_taller", $arr_user);
			} elseif ($sede == "CODIESEL LA ROSITA") {


				$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'presu' => $presupuesto_total, 'sede' => $sede);
				//abrimos la vista
				$this->load->view("presupuesto_taller", $arr_user);
			} elseif ($sede == "CODIESEL VILLA DEL ROSARIO") {


				$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'presu' => $presupuesto_total, 'sede' => $sede);
				//abrimos la vista
				$this->load->view("presupuesto_taller", $arr_user);
			} elseif ($sede == "CODIESEL BARRANCABERMEJA") {


				$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'presu' => $presupuesto_total, 'sede' => $sede);
				//abrimos la vista
				$this->load->view("presupuesto_taller", $arr_user);
			}
		}
	}

	public function presupuesto_tipo_operaciones()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//obtenemos la bodega
			$bodega = $this->input->GET('bodega');
			$sede = $this->input->get('sede');
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('sedes');
			$this->load->model('presupuesto');
			$this->load->model('nominas');
			$bodega = $this->input->get('bodega');
			$presu = $this->input->get('presupuesto');
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
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presupuesto' => $presu);
			//abrimos la vista
			$this->load->view("presupuesto_tipo_operaciones", $arr_user);
		}
	}

	public function real_time_tipo_op()
	{
		$bodega = $this->input->get('bodega');

		$this->load->model('presupuesto');
		$this->load->model('nominas');
		$this->load->model('Informe');
		//echo $bodega;
		$fecha_ini = $this->nominas->obtener_primer_dia_mes();
		$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();

		$to_dias_mes = $this->Informe->get_total_dias();
		$to_dias_hoy = $this->Informe->get_dias_actual();
		$n_to_dias = $to_dias_mes->ultimo_dia;
		$n_dias_hoy = $to_dias_hoy->dia;
		/*CENTROS DE COSTOS*/
		/*GIRON*/
		$cc_giron_gasolina = "4";
		$cc_giron_diesel = "40";
		$cc_giron_colicion = "33,45";
		/*ROSITA*/
		$cc_rosita_gasolina = "16";
		/*BARRANCA*/
		$cc_barranca_gas = "13";
		$cc_barranca_die = "70";
		/*BOCONO*/
		$cc_bocono_gas = "29";
		$cc_bocono_die = "80";
		$cc_bocono_col = "31,46";
		switch ($bodega) {
			case 'TALLER GASOLINA GIRON':
				$presupuesto_to_mes_rep = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "REPUESTOS GASOLINA GIRON");
				$presupuesto_to_mes_tot = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "TOT GASOLINA GIRON");
				$presupuesto_to_mes_mo = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "MO GASOLINA GIRON");


				$presupuesto_dia_rep = $this->presupuesto->get_presupuesto_rep($cc_giron_gasolina);
				$presupuesto_dia_tot = $this->presupuesto->get_presupuesto_tot($cc_giron_gasolina);
				$presupuesto_dia_mo = $this->presupuesto->get_presupuesto_mo($cc_giron_gasolina);

				$presupuesto_dia_mo = ($presupuesto_dia_mo->total - $presupuesto_dia_tot->total);
				//echo $presupuesto_dia_mo->valor."\n";

				foreach ($presupuesto_to_mes_rep->result() as $key) {
					$to_rep_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_tot->result() as $key) {
					$to_tot_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_mo->result() as $key) {
					$to_mo_gas = $key->presupuesto;
				}

				//LO QUE DEBEMOS LLEVAR A DIA DE HOY
				$to_objetivo_mo = ($to_mo_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_tot = ($to_tot_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_rep = ($to_rep_gas / $n_to_dias) * $n_dias_hoy;
				//PROCENTAJE IDEAL DIA REPUESTOS
				$porcen_ideal_dia_repuesto = ($presupuesto_dia_rep->total / $to_objetivo_rep) * 100;
				$porcen_ideal_dia_repuesto_rest = 100 - $porcen_ideal_dia_repuesto;
				if ($porcen_ideal_dia_repuesto_rest < 0) {
					$porcen_ideal_dia_repuesto_rest = 0;
				}
				//PROCENTAJE IDEAL DIA TOT
				$porcen_ideal_dia_tot = ($presupuesto_dia_tot->total / $to_objetivo_tot) * 100;
				$porcen_ideal_dia_tot_rest = 100 - $porcen_ideal_dia_tot;
				if ($porcen_ideal_dia_tot_rest < 0) {
					$porcen_ideal_dia_tot_rest = 0;
				}
				//PROCENTAJE IDEAL DIA MO
				$porcen_ideal_dia_mo = ($presupuesto_dia_mo / $to_objetivo_mo) * 100;
				$porcen_ideal_dia_mo_rest = 100 - $porcen_ideal_dia_mo;
				if ($porcen_ideal_dia_mo_rest < 0) {
					$porcen_ideal_dia_mo_rest = 0;
				}
				//PORCENTAJE IDEAL MES
				$porcentaje_rep_gas = ($presupuesto_dia_rep->total * 100) / $to_rep_gas;
				$porcentaje_restante_rep_gas = 100 - $porcentaje_rep_gas;
				if ($porcentaje_rep_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_rep_gas = 0;
				}

				$arr_rep = array('operacion' => "REPUESTOS", 'porcentaje' => $porcentaje_rep_gas, 'porcentaje_restante' => $porcentaje_restante_rep_gas, 'total' => $to_rep_gas, 'total_dia' => $presupuesto_dia_rep->total, 'to_objetivo' => $to_objetivo_rep, 'porcentaje_dia' => $porcen_ideal_dia_repuesto, 'porcentaje_dia_res' => $porcen_ideal_dia_repuesto_rest);

				//echo $porcentaje_rep_gas." - ".$porcentaje_restante_rep_gas;

				//PORCENTAJE IDEAL MES
				$porcentaje_tot_gas = ($presupuesto_dia_tot->total * 100) / $to_tot_gas;
				$porcentaje_restante_tot_gas = 100 - $porcentaje_tot_gas;
				if ($porcentaje_tot_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_tot_gas = 0;
				}
				$arr_tot = array('operacion' => "TOT", 'porcentaje' => $porcentaje_tot_gas, 'porcentaje_restante' => $porcentaje_restante_tot_gas, 'total' => $to_tot_gas, 'total_dia' => $presupuesto_dia_tot->total, 'to_objetivo' => $to_objetivo_tot, 'porcentaje_dia' => $porcen_ideal_dia_tot, 'porcentaje_dia_res' => $porcen_ideal_dia_tot_rest);
				//echo $porcentaje_tot_gas." - ".$porcentaje_restante_tot_gas;
				//PORCENTAJE IDEAL MES
				$porcentaje_mo_gas = ($presupuesto_dia_mo * 100) / $to_mo_gas;
				$porcentaje_restante_mo_gas = 100 - $porcentaje_mo_gas;
				if ($porcentaje_mo_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_mo_gas = 0;
				}

				$arr_mo = array('operacion' => "MO", 'porcentaje' => $porcentaje_mo_gas, 'porcentaje_restante' => $porcentaje_restante_mo_gas, 'total' => $to_mo_gas, 'total_dia' => $presupuesto_dia_mo, 'to_objetivo' => $to_objetivo_mo, 'porcentaje_dia' => $porcen_ideal_dia_mo, 'porcentaje_dia_res' => $porcen_ideal_dia_mo_rest);

				$data[] = array($arr_rep, $arr_tot, $arr_mo);
				foreach ($data as $ope) {
					foreach ($ope as $key) {
						if ($key['operacion'] == "TOT") {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						} else {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						}
					}
				}
				//echo $porcentaje_mo_gas." - ".$porcentaje_restante_mo_gas;

				break;
			case 'TALLER DIESEL GIRON':
				$presupuesto_to_mes_rep = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "REPUESTOS DIESEL GIRON");
				$presupuesto_to_mes_tot = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "TOT DIESEL GIRON");
				$presupuesto_to_mes_mo = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "MO DIESEL GIRON");

				$presupuesto_dia_rep = $this->presupuesto->get_presupuesto_rep($cc_giron_diesel);
				$presupuesto_dia_tot = $this->presupuesto->get_presupuesto_tot($cc_giron_diesel);
				$presupuesto_dia_mo = $this->presupuesto->get_presupuesto_mo($cc_giron_diesel);

				$presupuesto_dia_mo = ($presupuesto_dia_mo->total - $presupuesto_dia_tot->total);


				//echo $presupuesto_dia_mo->valor."\n";

				foreach ($presupuesto_to_mes_rep->result() as $key) {
					$to_rep_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_tot->result() as $key) {
					$to_tot_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_mo->result() as $key) {
					$to_mo_gas = $key->presupuesto;
				}

				//LO QUE DEBEMOS LLEVAR A DIA DE HOY
				$to_objetivo_mo = ($to_mo_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_tot = ($to_tot_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_rep = ($to_rep_gas / $n_to_dias) * $n_dias_hoy;
				//PROCENTAJE IDEAL DIA REPUESTOS
				$porcen_ideal_dia_repuesto = ($presupuesto_dia_rep->total / $to_objetivo_rep) * 100;
				$porcen_ideal_dia_repuesto_rest = 100 - $porcen_ideal_dia_repuesto;
				if ($porcen_ideal_dia_repuesto_rest < 0) {
					$porcen_ideal_dia_repuesto_rest = 0;
				}
				//PROCENTAJE IDEAL DIA TOT
				$porcen_ideal_dia_tot = ($presupuesto_dia_tot->total / $to_objetivo_tot) * 100;
				$porcen_ideal_dia_tot_rest = 100 - $porcen_ideal_dia_tot;
				if ($porcen_ideal_dia_tot_rest < 0) {
					$porcen_ideal_dia_tot_rest = 0;
				}
				//PROCENTAJE IDEAL DIA MO
				$porcen_ideal_dia_mo = ($presupuesto_dia_mo / $to_objetivo_mo) * 100;
				$porcen_ideal_dia_mo_rest = 100 - $porcen_ideal_dia_mo;
				if ($porcen_ideal_dia_mo_rest < 0) {
					$porcen_ideal_dia_mo_rest = 0;
				}

				$porcentaje_rep_gas = ($presupuesto_dia_rep->total * 100) / $to_rep_gas;
				$porcentaje_restante_rep_gas = 100 - $porcentaje_rep_gas;
				if ($porcentaje_rep_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_rep_gas = 0;
				}
				$arr_rep = array('operacion' => "REPUESTOS", 'porcentaje' => $porcentaje_rep_gas, 'porcentaje_restante' => $porcentaje_restante_rep_gas, 'total' => $to_rep_gas, 'total_dia' => $presupuesto_dia_rep->total, 'to_objetivo' => $to_objetivo_rep, 'porcentaje_dia' => $porcen_ideal_dia_repuesto, 'porcentaje_dia_res' => $porcen_ideal_dia_repuesto_rest);

				//echo $porcentaje_rep_gas." - ".$porcentaje_restante_rep_gas;


				$porcentaje_tot_gas = ($presupuesto_dia_tot->total * 100) / $to_tot_gas;
				$porcentaje_restante_tot_gas = 100 - $porcentaje_tot_gas;
				if ($porcentaje_tot_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_tot_gas = 0;
				}
				$arr_tot = array('operacion' => "TOT", 'porcentaje' => $porcentaje_tot_gas, 'porcentaje_restante' => $porcentaje_restante_tot_gas, 'total' => $to_tot_gas, 'total_dia' => $presupuesto_dia_tot->total, 'to_objetivo' => $to_objetivo_tot, 'porcentaje_dia' => $porcen_ideal_dia_tot, 'porcentaje_dia_res' => $porcen_ideal_dia_tot_rest);
				//echo $porcentaje_tot_gas." - ".$porcentaje_restante_tot_gas;

				$porcentaje_mo_gas = ($presupuesto_dia_mo * 100) / $to_mo_gas;
				$porcentaje_restante_mo_gas = 100 - $porcentaje_mo_gas;
				if ($porcentaje_mo_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_mo_gas = 0;
				}

				$arr_mo = array('operacion' => "MO", 'porcentaje' => $porcentaje_mo_gas, 'porcentaje_restante' => $porcentaje_restante_mo_gas, 'total' => $to_mo_gas, 'total_dia' => $presupuesto_dia_mo, 'to_objetivo' => $to_objetivo_mo, 'porcentaje_dia' => $porcen_ideal_dia_mo, 'porcentaje_dia_res' => $porcen_ideal_dia_mo_rest);

				$data[] = array($arr_rep, $arr_tot, $arr_mo);
				foreach ($data as $ope) {
					foreach ($ope as $key) {
						if ($key['operacion'] == "TOT") {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						} else {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						}
					}
				}
				break;
			case 'TALLER LAMINA Y PINTURA GIRON':
				$presupuesto_to_mes_rep = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "REPUESTOS LYP GIRON");
				$presupuesto_to_mes_tot = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "TOT LYP GIRON");
				$presupuesto_to_mes_mo = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "MO LYP GIRON");

				$presupuesto_dia_rep = $this->presupuesto->get_presupuesto_rep($cc_giron_colicion);
				$presupuesto_dia_tot = $this->presupuesto->get_presupuesto_tot($cc_giron_colicion);
				$presupuesto_dia_mo = $this->presupuesto->get_presupuesto_mo($cc_giron_colicion);

				$presupuesto_dia_mo = ($presupuesto_dia_mo->total - $presupuesto_dia_tot->total);

				//echo $presupuesto_dia_mo->valor."\n";

				foreach ($presupuesto_to_mes_rep->result() as $key) {
					$to_rep_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_tot->result() as $key) {
					$to_tot_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_mo->result() as $key) {
					$to_mo_gas = $key->presupuesto;
				}

				//LO QUE DEBEMOS LLEVAR A DIA DE HOY
				$to_objetivo_mo = ($to_mo_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_tot = ($to_tot_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_rep = ($to_rep_gas / $n_to_dias) * $n_dias_hoy;
				//PROCENTAJE IDEAL DIA REPUESTOS
				$porcen_ideal_dia_repuesto = ($presupuesto_dia_rep->total / $to_objetivo_rep) * 100;
				$porcen_ideal_dia_repuesto_rest = 100 - $porcen_ideal_dia_repuesto;
				if ($porcen_ideal_dia_repuesto_rest < 0) {
					$porcen_ideal_dia_repuesto_rest = 0;
				}
				//PROCENTAJE IDEAL DIA TOT
				$porcen_ideal_dia_tot = ($presupuesto_dia_tot->total / $to_objetivo_tot) * 100;
				$porcen_ideal_dia_tot_rest = 100 - $porcen_ideal_dia_tot;
				if ($porcen_ideal_dia_tot_rest < 0) {
					$porcen_ideal_dia_tot_rest = 0;
				}
				//PROCENTAJE IDEAL DIA MO
				$porcen_ideal_dia_mo = ($presupuesto_dia_mo / $to_objetivo_mo) * 100;
				$porcen_ideal_dia_mo_rest = 100 - $porcen_ideal_dia_mo;
				if ($porcen_ideal_dia_mo_rest < 0) {
					$porcen_ideal_dia_mo_rest = 0;
				}

				$porcentaje_rep_gas = ($presupuesto_dia_rep->total * 100) / $to_rep_gas;
				$porcentaje_restante_rep_gas = 100 - $porcentaje_rep_gas;
				if ($porcentaje_rep_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_rep_gas = 0;
				}
				$arr_rep = array('operacion' => "REPUESTOS", 'porcentaje' => $porcentaje_rep_gas, 'porcentaje_restante' => $porcentaje_restante_rep_gas, 'total' => $to_rep_gas, 'total_dia' => $presupuesto_dia_rep->total, 'to_objetivo' => $to_objetivo_rep, 'porcentaje_dia' => $porcen_ideal_dia_repuesto, 'porcentaje_dia_res' => $porcen_ideal_dia_repuesto_rest);

				//echo $porcentaje_rep_gas." - ".$porcentaje_restante_rep_gas;


				$porcentaje_tot_gas = ($presupuesto_dia_tot->total * 100) / $to_tot_gas;
				$porcentaje_restante_tot_gas = 100 - $porcentaje_tot_gas;
				if ($porcentaje_tot_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_tot_gas = 0;
				}
				$arr_tot = array('operacion' => "TOT", 'porcentaje' => $porcentaje_tot_gas, 'porcentaje_restante' => $porcentaje_restante_tot_gas, 'total' => $to_tot_gas, 'total_dia' => $presupuesto_dia_tot->total, 'to_objetivo' => $to_objetivo_tot, 'porcentaje_dia' => $porcen_ideal_dia_tot, 'porcentaje_dia_res' => $porcen_ideal_dia_tot_rest);
				//echo $porcentaje_tot_gas." - ".$porcentaje_restante_tot_gas;

				$porcentaje_mo_gas = ($presupuesto_dia_mo * 100) / $to_mo_gas;
				$porcentaje_restante_mo_gas = 100 - $porcentaje_mo_gas;
				if ($porcentaje_mo_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_mo_gas = 0;
				}

				$arr_mo = array('operacion' => "MO", 'porcentaje' => $porcentaje_mo_gas, 'porcentaje_restante' => $porcentaje_restante_mo_gas, 'total' => $to_mo_gas, 'total_dia' => $presupuesto_dia_mo, 'to_objetivo' => $to_objetivo_mo, 'porcentaje_dia' => $porcen_ideal_dia_mo, 'porcentaje_dia_res' => $porcen_ideal_dia_mo_rest);

				$data[] = array($arr_rep, $arr_tot, $arr_mo);
				foreach ($data as $ope) {
					foreach ($ope as $key) {
						if ($key['operacion'] == "TOT") {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						} else {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						}
					}
				}
				break;
			case 'ROSITA CHEVYEXPRESS':
				$presupuesto_to_mes_rep = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "REPUESTOS ROSITA");
				$presupuesto_to_mes_tot = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "TOT ROSITA");
				$presupuesto_to_mes_mo = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "MO ROSITA");

				$presupuesto_dia_rep = $this->presupuesto->get_presupuesto_rep($cc_rosita_gasolina);
				$presupuesto_dia_tot = $this->presupuesto->get_presupuesto_tot($cc_rosita_gasolina);
				$presupuesto_dia_mo = $this->presupuesto->get_presupuesto_mo($cc_rosita_gasolina);

				$presupuesto_dia_mo = ($presupuesto_dia_mo->total - $presupuesto_dia_tot->total);


				//echo $presupuesto_dia_mo->valor."\n";

				foreach ($presupuesto_to_mes_rep->result() as $key) {
					$to_rep_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_tot->result() as $key) {
					$to_tot_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_mo->result() as $key) {
					$to_mo_gas = $key->presupuesto;
				}

				//LO QUE DEBEMOS LLEVAR A DIA DE HOY
				$to_objetivo_mo = ($to_mo_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_tot = ($to_tot_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_rep = ($to_rep_gas / $n_to_dias) * $n_dias_hoy;
				//PROCENTAJE IDEAL DIA REPUESTOS
				$porcen_ideal_dia_repuesto = ($presupuesto_dia_rep->total / $to_objetivo_rep) * 100;
				$porcen_ideal_dia_repuesto_rest = 100 - $porcen_ideal_dia_repuesto;
				if ($porcen_ideal_dia_repuesto_rest < 0) {
					$porcen_ideal_dia_repuesto_rest = 0;
				}
				//PROCENTAJE IDEAL DIA TOT
				$porcen_ideal_dia_tot = ($presupuesto_dia_tot->total / $to_objetivo_tot) * 100;
				$porcen_ideal_dia_tot_rest = 100 - $porcen_ideal_dia_tot;
				if ($porcen_ideal_dia_tot_rest < 0) {
					$porcen_ideal_dia_tot_rest = 0;
				}
				//PROCENTAJE IDEAL DIA MO
				$porcen_ideal_dia_mo = ($presupuesto_dia_mo / $to_objetivo_mo) * 100;
				$porcen_ideal_dia_mo_rest = 100 - $porcen_ideal_dia_mo;
				if ($porcen_ideal_dia_mo_rest < 0) {
					$porcen_ideal_dia_mo_rest = 0;
				}

				$porcentaje_rep_gas = ($presupuesto_dia_rep->total * 100) / $to_rep_gas;
				$porcentaje_restante_rep_gas = 100 - $porcentaje_rep_gas;
				if ($porcentaje_rep_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_rep_gas = 0;
				}
				$arr_rep = array('operacion' => "REPUESTOS", 'porcentaje' => $porcentaje_rep_gas, 'porcentaje_restante' => $porcentaje_restante_rep_gas, 'total' => $to_rep_gas, 'total_dia' => $presupuesto_dia_rep->total, 'to_objetivo' => $to_objetivo_rep, 'porcentaje_dia' => $porcen_ideal_dia_repuesto, 'porcentaje_dia_res' => $porcen_ideal_dia_repuesto_rest);

				//echo $porcentaje_rep_gas." - ".$porcentaje_restante_rep_gas;


				$porcentaje_tot_gas = ($presupuesto_dia_tot->total * 100) / $to_tot_gas;
				$porcentaje_restante_tot_gas = 100 - $porcentaje_tot_gas;
				if ($porcentaje_tot_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_tot_gas = 0;
				}
				$arr_tot = array('operacion' => "TOT", 'porcentaje' => $porcentaje_tot_gas, 'porcentaje_restante' => $porcentaje_restante_tot_gas, 'total' => $to_tot_gas, 'total_dia' => $presupuesto_dia_tot->total, 'to_objetivo' => $to_objetivo_tot, 'porcentaje_dia' => $porcen_ideal_dia_tot, 'porcentaje_dia_res' => $porcen_ideal_dia_tot_rest);
				//echo $porcentaje_tot_gas." - ".$porcentaje_restante_tot_gas;

				$porcentaje_mo_gas = ($presupuesto_dia_mo * 100) / $to_mo_gas;
				$porcentaje_restante_mo_gas = 100 - $porcentaje_mo_gas;
				if ($porcentaje_mo_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_mo_gas = 0;
				}

				$arr_mo = array('operacion' => "MO", 'porcentaje' => $porcentaje_mo_gas, 'porcentaje_restante' => $porcentaje_restante_mo_gas, 'total' => $to_mo_gas, 'total_dia' => $presupuesto_dia_mo, 'to_objetivo' => $to_objetivo_mo, 'porcentaje_dia' => $porcen_ideal_dia_mo, 'porcentaje_dia_res' => $porcen_ideal_dia_mo_rest);

				$data[] = array($arr_rep, $arr_tot, $arr_mo);
				foreach ($data as $ope) {
					foreach ($ope as $key) {
						if ($key['operacion'] == "TOT") {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						} else {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						}
					}
				}
				break;
			case 'TALLER DIESEL BOCONO':
				$presupuesto_to_mes_rep = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "REPUESTOS DIESEL  BOCONO");
				$presupuesto_to_mes_tot = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "TOT DIESEL BOCONO");
				$presupuesto_to_mes_mo = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "MO DIESEL BOCONO");

				$presupuesto_dia_rep = $this->presupuesto->get_presupuesto_rep($cc_bocono_die);
				$presupuesto_dia_tot = $this->presupuesto->get_presupuesto_tot($cc_bocono_die);
				$presupuesto_dia_mo = $this->presupuesto->get_presupuesto_mo($cc_bocono_die);

				$presupuesto_dia_mo = ($presupuesto_dia_mo->total - $presupuesto_dia_tot->total);
				//echo $presupuesto_dia_mo->valor."\n";

				foreach ($presupuesto_to_mes_rep->result() as $key) {
					$to_rep_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_tot->result() as $key) {
					$to_tot_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_mo->result() as $key) {
					$to_mo_gas = $key->presupuesto;
				}

				//LO QUE DEBEMOS LLEVAR A DIA DE HOY
				$to_objetivo_mo = ($to_mo_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_tot = ($to_tot_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_rep = ($to_rep_gas / $n_to_dias) * $n_dias_hoy;
				//PROCENTAJE IDEAL DIA REPUESTOS
				$porcen_ideal_dia_repuesto = ($presupuesto_dia_rep->total / $to_objetivo_rep) * 100;
				$porcen_ideal_dia_repuesto_rest = 100 - $porcen_ideal_dia_repuesto;
				if ($porcen_ideal_dia_repuesto_rest < 0) {
					$porcen_ideal_dia_repuesto_rest = 0;
				}
				//PROCENTAJE IDEAL DIA TOT
				$porcen_ideal_dia_tot = ($presupuesto_dia_tot->total / $to_objetivo_tot) * 100;
				$porcen_ideal_dia_tot_rest = 100 - $porcen_ideal_dia_tot;
				if ($porcen_ideal_dia_tot_rest < 0) {
					$porcen_ideal_dia_tot_rest = 0;
				}
				//PROCENTAJE IDEAL DIA MO
				$porcen_ideal_dia_mo = ($presupuesto_dia_mo / $to_objetivo_mo) * 100;
				$porcen_ideal_dia_mo_rest = 100 - $porcen_ideal_dia_mo;
				if ($porcen_ideal_dia_mo_rest < 0) {
					$porcen_ideal_dia_mo_rest = 0;
				}

				$porcentaje_rep_gas = ($presupuesto_dia_rep->total * 100) / $to_rep_gas;
				$porcentaje_restante_rep_gas = 100 - $porcentaje_rep_gas;
				if ($porcentaje_rep_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_rep_gas = 0;
				}
				$arr_rep = array('operacion' => "REPUESTOS", 'porcentaje' => $porcentaje_rep_gas, 'porcentaje_restante' => $porcentaje_restante_rep_gas, 'total' => $to_rep_gas, 'total_dia' => $presupuesto_dia_rep->total, 'to_objetivo' => $to_objetivo_rep, 'porcentaje_dia' => $porcen_ideal_dia_repuesto, 'porcentaje_dia_res' => $porcen_ideal_dia_repuesto_rest);

				//echo $porcentaje_rep_gas." - ".$porcentaje_restante_rep_gas;


				$porcentaje_tot_gas = ($presupuesto_dia_tot->total * 100) / $to_tot_gas;
				$porcentaje_restante_tot_gas = 100 - $porcentaje_tot_gas;
				if ($porcentaje_tot_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_tot_gas = 0;
				}
				$arr_tot = array('operacion' => "TOT", 'porcentaje' => $porcentaje_tot_gas, 'porcentaje_restante' => $porcentaje_restante_tot_gas, 'total' => $to_tot_gas, 'total_dia' => $presupuesto_dia_tot->total, 'to_objetivo' => $to_objetivo_tot, 'porcentaje_dia' => $porcen_ideal_dia_tot, 'porcentaje_dia_res' => $porcen_ideal_dia_tot_rest);
				//echo $porcentaje_tot_gas." - ".$porcentaje_restante_tot_gas;

				$porcentaje_mo_gas = ($presupuesto_dia_mo * 100) / $to_mo_gas;
				$porcentaje_restante_mo_gas = 100 - $porcentaje_mo_gas;
				if ($porcentaje_mo_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_mo_gas = 0;
				}

				$arr_mo = array('operacion' => "MO", 'porcentaje' => $porcentaje_mo_gas, 'porcentaje_restante' => $porcentaje_restante_mo_gas, 'total' => $to_mo_gas, 'total_dia' => $presupuesto_dia_mo, 'to_objetivo' => $to_objetivo_mo, 'porcentaje_dia' => $porcen_ideal_dia_mo, 'porcentaje_dia_res' => $porcen_ideal_dia_mo_rest);
				//echo $porcentaje_tot_gas." - ".$porcentaje_restante_tot_gas;




				$data[] = array($arr_rep, $arr_tot, $arr_mo);
				foreach ($data as $ope) {
					foreach ($ope as $key) {
						if ($key['operacion'] == "TOT") {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						} else {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						}
					}
				}
				break;
			case 'TALLER GASOLINA BOCONO':
				$presupuesto_to_mes_rep = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "REPUESTOS GASOLINA BOCONO");
				$presupuesto_to_mes_tot = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "TOT GASOLINA BOCONO");
				$presupuesto_to_mes_mo = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "MO GASOLINA BOCONO");

				$presupuesto_dia_rep = $this->presupuesto->get_presupuesto_rep($cc_bocono_gas);
				$presupuesto_dia_tot = $this->presupuesto->get_presupuesto_tot($cc_bocono_gas);
				$presupuesto_dia_mo = $this->presupuesto->get_presupuesto_mo($cc_bocono_gas);

				$presupuesto_dia_mo = ($presupuesto_dia_mo->total - $presupuesto_dia_tot->total);

				//echo $presupuesto_dia_mo->valor."\n";

				foreach ($presupuesto_to_mes_rep->result() as $key) {
					$to_rep_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_tot->result() as $key) {
					$to_tot_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_mo->result() as $key) {
					$to_mo_gas = $key->presupuesto;
				}
				//LO QUE DEBEMOS LLEVAR A DIA DE HOY
				$to_objetivo_mo = ($to_mo_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_tot = ($to_tot_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_rep = ($to_rep_gas / $n_to_dias) * $n_dias_hoy;
				//PROCENTAJE IDEAL DIA REPUESTOS
				$porcen_ideal_dia_repuesto = ($presupuesto_dia_rep->total / $to_objetivo_rep) * 100;
				$porcen_ideal_dia_repuesto_rest = 100 - $porcen_ideal_dia_repuesto;
				if ($porcen_ideal_dia_repuesto_rest < 0) {
					$porcen_ideal_dia_repuesto_rest = 0;
				}
				//PROCENTAJE IDEAL DIA TOT
				$porcen_ideal_dia_tot = ($presupuesto_dia_tot->total / $to_objetivo_tot) * 100;
				$porcen_ideal_dia_tot_rest = 100 - $porcen_ideal_dia_tot;
				if ($porcen_ideal_dia_tot_rest < 0) {
					$porcen_ideal_dia_tot_rest = 0;
				}
				//PROCENTAJE IDEAL DIA MO
				$porcen_ideal_dia_mo = ($presupuesto_dia_mo / $to_objetivo_mo) * 100;
				$porcen_ideal_dia_mo_rest = 100 - $porcen_ideal_dia_mo;
				if ($porcen_ideal_dia_mo_rest < 0) {
					$porcen_ideal_dia_mo_rest = 0;
				}

				$porcentaje_rep_gas = ($presupuesto_dia_rep->total * 100) / $to_rep_gas;
				$porcentaje_restante_rep_gas = 100 - $porcentaje_rep_gas;
				if ($porcentaje_rep_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_rep_gas = 0;
				}
				$arr_rep = array('operacion' => "REPUESTOS", 'porcentaje' => $porcentaje_rep_gas, 'porcentaje_restante' => $porcentaje_restante_rep_gas, 'total' => $to_rep_gas, 'total_dia' => $presupuesto_dia_rep->total, 'to_objetivo' => $to_objetivo_rep, 'porcentaje_dia' => $porcen_ideal_dia_repuesto, 'porcentaje_dia_res' => $porcen_ideal_dia_repuesto_rest);

				//echo $porcentaje_rep_gas." - ".$porcentaje_restante_rep_gas;


				$porcentaje_tot_gas = ($presupuesto_dia_tot->total * 100) / $to_tot_gas;
				$porcentaje_restante_tot_gas = 100 - $porcentaje_tot_gas;
				if ($porcentaje_tot_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_tot_gas = 0;
				}
				$arr_tot = array('operacion' => "TOT", 'porcentaje' => $porcentaje_tot_gas, 'porcentaje_restante' => $porcentaje_restante_tot_gas, 'total' => $to_tot_gas, 'total_dia' => $presupuesto_dia_tot->total, 'to_objetivo' => $to_objetivo_tot, 'porcentaje_dia' => $porcen_ideal_dia_tot, 'porcentaje_dia_res' => $porcen_ideal_dia_tot_rest);
				//echo $porcentaje_tot_gas." - ".$porcentaje_restante_tot_gas;

				$porcentaje_mo_gas = ($presupuesto_dia_mo * 100) / $to_mo_gas;
				$porcentaje_restante_mo_gas = 100 - $porcentaje_mo_gas;
				if ($porcentaje_mo_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_mo_gas = 0;
				}

				$arr_mo = array('operacion' => "MO", 'porcentaje' => $porcentaje_mo_gas, 'porcentaje_restante' => $porcentaje_restante_mo_gas, 'total' => $to_mo_gas, 'total_dia' => $presupuesto_dia_mo, 'to_objetivo' => $to_objetivo_mo, 'porcentaje_dia' => $porcen_ideal_dia_mo, 'porcentaje_dia_res' => $porcen_ideal_dia_mo_rest);

				$data[] = array($arr_rep, $arr_tot, $arr_mo);
				foreach ($data as $ope) {
					foreach ($ope as $key) {
						if ($key['operacion'] == "TOT") {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						} else {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						}
					}
				}
				break;
			case 'TALLER LAMINA Y PINTURA BOCONO':
				$presupuesto_to_mes_rep = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "REPUESTOS LYP BOCONO");
				$presupuesto_to_mes_tot = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "TOT LYP BOCONO");
				$presupuesto_to_mes_mo = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "MO LYP BOCONO");

				$presupuesto_dia_rep = $this->presupuesto->get_presupuesto_rep($cc_bocono_col);
				$presupuesto_dia_tot = $this->presupuesto->get_presupuesto_tot($cc_bocono_col);
				$presupuesto_dia_mo = $this->presupuesto->get_presupuesto_mo($cc_bocono_col);

				$presupuesto_dia_mo = ($presupuesto_dia_mo->total - $presupuesto_dia_tot->total);

				//echo $presupuesto_dia_mo->valor."\n";

				foreach ($presupuesto_to_mes_rep->result() as $key) {
					$to_rep_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_tot->result() as $key) {
					$to_tot_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_mo->result() as $key) {
					$to_mo_gas = $key->presupuesto;
				}

				//LO QUE DEBEMOS LLEVAR A DIA DE HOY
				$to_objetivo_mo = ($to_mo_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_tot = ($to_tot_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_rep = ($to_rep_gas / $n_to_dias) * $n_dias_hoy;
				//PROCENTAJE IDEAL DIA REPUESTOS
				$porcen_ideal_dia_repuesto = ($presupuesto_dia_rep->total / $to_objetivo_rep) * 100;
				$porcen_ideal_dia_repuesto_rest = 100 - $porcen_ideal_dia_repuesto;
				if ($porcen_ideal_dia_repuesto_rest < 0) {
					$porcen_ideal_dia_repuesto_rest = 0;
				}
				//PROCENTAJE IDEAL DIA TOT
				$porcen_ideal_dia_tot = ($presupuesto_dia_tot->total / $to_objetivo_tot) * 100;
				$porcen_ideal_dia_tot_rest = 100 - $porcen_ideal_dia_tot;
				if ($porcen_ideal_dia_tot_rest < 0) {
					$porcen_ideal_dia_tot_rest = 0;
				}
				//PROCENTAJE IDEAL DIA MO
				$porcen_ideal_dia_mo = ($presupuesto_dia_mo / $to_objetivo_mo) * 100;
				$porcen_ideal_dia_mo_rest = 100 - $porcen_ideal_dia_mo;
				if ($porcen_ideal_dia_mo_rest < 0) {
					$porcen_ideal_dia_mo_rest = 0;
				}

				$porcentaje_rep_gas = ($presupuesto_dia_rep->total * 100) / $to_rep_gas;
				$porcentaje_restante_rep_gas = 100 - $porcentaje_rep_gas;
				if ($porcentaje_rep_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_rep_gas = 0;
				}
				$arr_rep = array('operacion' => "REPUESTOS", 'porcentaje' => $porcentaje_rep_gas, 'porcentaje_restante' => $porcentaje_restante_rep_gas, 'total' => $to_rep_gas, 'total_dia' => $presupuesto_dia_rep->total, 'to_objetivo' => $to_objetivo_rep, 'porcentaje_dia' => $porcen_ideal_dia_repuesto, 'porcentaje_dia_res' => $porcen_ideal_dia_repuesto_rest);

				//echo $porcentaje_rep_gas." - ".$porcentaje_restante_rep_gas;


				$porcentaje_tot_gas = ($presupuesto_dia_tot->total * 100) / $to_tot_gas;
				$porcentaje_restante_tot_gas = 100 - $porcentaje_tot_gas;
				if ($porcentaje_tot_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_tot_gas = 0;
				}
				$arr_tot = array('operacion' => "TOT", 'porcentaje' => $porcentaje_tot_gas, 'porcentaje_restante' => $porcentaje_restante_tot_gas, 'total' => $to_tot_gas, 'total_dia' => $presupuesto_dia_tot->total, 'to_objetivo' => $to_objetivo_tot, 'porcentaje_dia' => $porcen_ideal_dia_tot, 'porcentaje_dia_res' => $porcen_ideal_dia_tot_rest);
				//echo $porcentaje_tot_gas." - ".$porcentaje_restante_tot_gas;

				$porcentaje_mo_gas = ($presupuesto_dia_mo * 100) / $to_mo_gas;
				$porcentaje_restante_mo_gas = 100 - $porcentaje_mo_gas;
				if ($porcentaje_mo_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_mo_gas = 0;
				}

				$arr_mo = array('operacion' => "MO", 'porcentaje' => $porcentaje_mo_gas, 'porcentaje_restante' => $porcentaje_restante_mo_gas, 'total' => $to_mo_gas, 'total_dia' => $presupuesto_dia_mo, 'to_objetivo' => $to_objetivo_mo, 'porcentaje_dia' => $porcen_ideal_dia_mo, 'porcentaje_dia_res' => $porcen_ideal_dia_mo_rest);

				$data[] = array($arr_rep, $arr_tot, $arr_mo);
				foreach ($data as $ope) {
					foreach ($ope as $key) {
						if ($key['operacion'] == "TOT") {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						} else {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						}
					}
				}
				break;
			case 'TALLER DIESEL BARRANCA':
				$presupuesto_to_mes_rep = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "REPUESTOS DIESEL BARRANCA");
				$presupuesto_to_mes_tot = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "TOT DIESEL BARRANCA");
				$presupuesto_to_mes_mo = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "MO DIESEL BARRANCA");

				$presupuesto_dia_rep = $this->presupuesto->get_presupuesto_rep($cc_barranca_die);
				$presupuesto_dia_tot = $this->presupuesto->get_presupuesto_tot($cc_barranca_die);
				$presupuesto_dia_mo = $this->presupuesto->get_presupuesto_mo($cc_barranca_die);

				$presupuesto_dia_mo = ($presupuesto_dia_mo->total - $presupuesto_dia_tot->total);

				//echo $presupuesto_dia_mo->valor."\n";

				foreach ($presupuesto_to_mes_rep->result() as $key) {
					$to_rep_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_tot->result() as $key) {
					$to_tot_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_mo->result() as $key) {
					$to_mo_gas = $key->presupuesto;
				}

				//LO QUE DEBEMOS LLEVAR A DIA DE HOY
				$to_objetivo_mo = ($to_mo_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_tot = ($to_tot_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_rep = ($to_rep_gas / $n_to_dias) * $n_dias_hoy;
				//PROCENTAJE IDEAL DIA REPUESTOS
				$porcen_ideal_dia_repuesto = ($presupuesto_dia_rep->total / $to_objetivo_rep) * 100;
				$porcen_ideal_dia_repuesto_rest = 100 - $porcen_ideal_dia_repuesto;
				if ($porcen_ideal_dia_repuesto_rest < 0) {
					$porcen_ideal_dia_repuesto_rest = 0;
				}
				//PROCENTAJE IDEAL DIA TOT
				$porcen_ideal_dia_tot = ($presupuesto_dia_tot->total / $to_objetivo_tot) * 100;
				$porcen_ideal_dia_tot_rest = 100 - $porcen_ideal_dia_tot;
				if ($porcen_ideal_dia_tot_rest < 0) {
					$porcen_ideal_dia_tot_rest = 0;
				}
				//PROCENTAJE IDEAL DIA MO
				$porcen_ideal_dia_mo = ($presupuesto_dia_mo / $to_objetivo_mo) * 100;
				$porcen_ideal_dia_mo_rest = 100 - $porcen_ideal_dia_mo;
				if ($porcen_ideal_dia_mo_rest < 0) {
					$porcen_ideal_dia_mo_rest = 0;
				}

				$porcentaje_rep_gas = ($presupuesto_dia_rep->total * 100) / $to_rep_gas;
				$porcentaje_restante_rep_gas = 100 - $porcentaje_rep_gas;
				if ($porcentaje_rep_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_rep_gas = 0;
				}
				$arr_rep = array('operacion' => "REPUESTOS", 'porcentaje' => $porcentaje_rep_gas, 'porcentaje_restante' => $porcentaje_restante_rep_gas, 'total' => $to_rep_gas, 'total_dia' => $presupuesto_dia_rep->total, 'to_objetivo' => $to_objetivo_rep, 'porcentaje_dia' => $porcen_ideal_dia_repuesto, 'porcentaje_dia_res' => $porcen_ideal_dia_repuesto_rest);

				//echo $porcentaje_rep_gas." - ".$porcentaje_restante_rep_gas;


				$porcentaje_tot_gas = ($presupuesto_dia_tot->total * 100) / $to_tot_gas;
				$porcentaje_restante_tot_gas = 100 - $porcentaje_tot_gas;
				if ($porcentaje_tot_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_tot_gas = 0;
				}
				$arr_tot = array('operacion' => "TOT", 'porcentaje' => $porcentaje_tot_gas, 'porcentaje_restante' => $porcentaje_restante_tot_gas, 'total' => $to_tot_gas, 'total_dia' => $presupuesto_dia_tot->total, 'to_objetivo' => $to_objetivo_tot, 'porcentaje_dia' => $porcen_ideal_dia_tot, 'porcentaje_dia_res' => $porcen_ideal_dia_tot_rest);
				//echo $porcentaje_tot_gas." - ".$porcentaje_restante_tot_gas;

				$porcentaje_mo_gas = ($presupuesto_dia_mo * 100) / $to_mo_gas;
				$porcentaje_restante_mo_gas = 100 - $porcentaje_mo_gas;
				if ($porcentaje_mo_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_mo_gas = 0;
				}

				$arr_mo = array('operacion' => "MO", 'porcentaje' => $porcentaje_mo_gas, 'porcentaje_restante' => $porcentaje_restante_mo_gas, 'total' => $to_mo_gas, 'total_dia' => $presupuesto_dia_mo, 'to_objetivo' => $to_objetivo_mo, 'porcentaje_dia' => $porcen_ideal_dia_mo, 'porcentaje_dia_res' => $porcen_ideal_dia_mo_rest);

				$data[] = array($arr_rep, $arr_tot, $arr_mo);
				foreach ($data as $ope) {
					foreach ($ope as $key) {
						if ($key['operacion'] == "TOT") {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						} else {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						}
					}
				}
				break;
			case 'TALLER GASOLINA BARRANCA':
				$presupuesto_to_mes_rep = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "REPUESTOS GASOLINA BARRANCA");
				$presupuesto_to_mes_tot = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "TOT GASOLINA BARRANCA");
				$presupuesto_to_mes_mo = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "MO GASOLINA BARRANCA");

				$presupuesto_dia_rep = $this->presupuesto->get_presupuesto_rep($cc_barranca_gas);
				$presupuesto_dia_tot = $this->presupuesto->get_presupuesto_tot($cc_barranca_gas);
				$presupuesto_dia_mo = $this->presupuesto->get_presupuesto_mo($cc_barranca_gas);

				$presupuesto_dia_mo = ($presupuesto_dia_mo->total - $presupuesto_dia_tot->total);

				//echo $presupuesto_dia_mo->valor."\n";

				foreach ($presupuesto_to_mes_rep->result() as $key) {
					$to_rep_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_tot->result() as $key) {
					$to_tot_gas = $key->presupuesto;
				}
				foreach ($presupuesto_to_mes_mo->result() as $key) {
					$to_mo_gas = $key->presupuesto;
				}

				//LO QUE DEBEMOS LLEVAR A DIA DE HOY
				$to_objetivo_mo = ($to_mo_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_tot = ($to_tot_gas / $n_to_dias) * $n_dias_hoy;
				$to_objetivo_rep = ($to_rep_gas / $n_to_dias) * $n_dias_hoy;
				//PROCENTAJE IDEAL DIA REPUESTOS
				$porcen_ideal_dia_repuesto = ($presupuesto_dia_rep->total / $to_objetivo_rep) * 100;
				$porcen_ideal_dia_repuesto_rest = 100 - $porcen_ideal_dia_repuesto;
				if ($porcen_ideal_dia_repuesto_rest < 0) {
					$porcen_ideal_dia_repuesto_rest = 0;
				}
				//PROCENTAJE IDEAL DIA TOT
				$porcen_ideal_dia_tot = ($presupuesto_dia_tot->total / $to_objetivo_tot) * 100;
				$porcen_ideal_dia_tot_rest = 100 - $porcen_ideal_dia_tot;
				if ($porcen_ideal_dia_tot_rest < 0) {
					$porcen_ideal_dia_tot_rest = 0;
				}
				//PROCENTAJE IDEAL DIA MO
				$porcen_ideal_dia_mo = ($presupuesto_dia_mo / $to_objetivo_mo) * 100;
				$porcen_ideal_dia_mo_rest = 100 - $porcen_ideal_dia_mo;
				if ($porcen_ideal_dia_mo_rest < 0) {
					$porcen_ideal_dia_mo_rest = 0;
				}

				$porcentaje_rep_gas = ($presupuesto_dia_rep->total * 100) / $to_rep_gas;
				$porcentaje_restante_rep_gas = 100 - $porcentaje_rep_gas;
				if ($porcentaje_rep_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_rep_gas = 0;
				}
				$arr_rep = array('operacion' => "REPUESTOS", 'porcentaje' => $porcentaje_rep_gas, 'porcentaje_restante' => $porcentaje_restante_rep_gas, 'total' => $to_rep_gas, 'total_dia' => $presupuesto_dia_rep->total, 'to_objetivo' => $to_objetivo_rep, 'porcentaje_dia' => $porcen_ideal_dia_repuesto, 'porcentaje_dia_res' => $porcen_ideal_dia_repuesto_rest);

				//echo $porcentaje_rep_gas." - ".$porcentaje_restante_rep_gas;


				$porcentaje_tot_gas = ($presupuesto_dia_tot->total * 100) / $to_tot_gas;
				$porcentaje_restante_tot_gas = 100 - $porcentaje_tot_gas;
				if ($porcentaje_tot_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_tot_gas = 0;
				}
				$arr_tot = array('operacion' => "TOT", 'porcentaje' => $porcentaje_tot_gas, 'porcentaje_restante' => $porcentaje_restante_tot_gas, 'total' => $to_tot_gas, 'total_dia' => $presupuesto_dia_tot->total, 'to_objetivo' => $to_objetivo_tot, 'porcentaje_dia' => $porcen_ideal_dia_tot, 'porcentaje_dia_res' => $porcen_ideal_dia_tot_rest);
				//echo $porcentaje_tot_gas." - ".$porcentaje_restante_tot_gas;

				$porcentaje_mo_gas = ($presupuesto_dia_mo * 100) / $to_mo_gas;
				$porcentaje_restante_mo_gas = 100 - $porcentaje_mo_gas;
				if ($porcentaje_mo_gas > 100) {
					//$porcentaje_ros = 100;
					$porcentaje_restante_mo_gas = 0;
				}

				$arr_mo = array('operacion' => "MO", 'porcentaje' => $porcentaje_mo_gas, 'porcentaje_restante' => $porcentaje_restante_mo_gas, 'total' => $to_mo_gas, 'total_dia' => $presupuesto_dia_mo, 'to_objetivo' => $to_objetivo_mo, 'porcentaje_dia' => $porcen_ideal_dia_mo, 'porcentaje_dia_res' => $porcen_ideal_dia_mo_rest);

				$data[] = array($arr_rep, $arr_tot, $arr_mo);
				foreach ($data as $ope) {
					foreach ($ope as $key) {
						if ($key['operacion'] == "TOT") {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						} else {
							echo '
							<div class="col-md-6" id="nose" aling="center"  style="font-size: 13px;">
							<div class="small-box bg-default" align="center">
						              <div class="inner">
						                <h3>$' . number_format($key['total_dia'], 0, ",", ".") . '</h3>

						                <p>Total ' . $key['operacion'] . '</p>
						              </div>
						              <div class="icon">
						                <i class="ion ion-stats-bars"></i>
						              </div>
						              <div class="container-fluid">
						              <div class="row" align="center">
						              			<div class="col-md-4">Meta a cumplir a dia de hoy</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['to_objetivo'], 0, ",", ",") . '</div>
										  </div>
						              <div class="progress">
										  <div class="progress-bar bg-info" role="progressbar" style="width: ' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_dia_res'], 0, ",", ",") . '%</div>
									  </div>
									  		<div class="row" align="center">
									  		<div class="col-md-4">Meta a cumplir al mes</div>
												<div class="col-md-4" align="center"><input class="btn btn-info btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total_dia'], 0, ",", ",") . '</div>
												<div class="col-md-4" align="center"><input class="btn btn-danger btn-sm" style="height: 3px;" type="button" value=""> $' . number_format($key['total'], 0, ",", ",") . '</div>
										  </div>
										  <div class="progress">
										  <div class="progress-bar bg-success" role="progressbar" style="width: ' . number_format($key['porcentaje'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje'], 0, ",", ",") . '%</div>
										  <div class="progress-bar bg-danger" role="progressbar" style="width: ' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%" aria-valuenow="' . number_format($key['porcentaje_restante'], 0, ",", ",") . '" aria-valuemin="0" aria-valuemax="100">' . number_format($key['porcentaje_restante'], 0, ",", ",") . '%</div>
									  </div>
									  <div class="row" style="width: 100%" aling="center">
													
						                        </div>
						                    <!-- /.progress-group -->
						                    <!-- /.progress-group -->
						                  </div>
						                  <!-- /.col -->
											
									  </div>
									  <!-- /.card-body -->
									  
								</div>
						';
						}
					}
				}
				break;

			default:
				# code...
				break;
		}
	}

	public function cargar_operarios()
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
			$bodega = $this->input->get('bodega');
			$pres = $this->input->get('presupuesto');
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
			switch ($bodega) {
				case 'TALLER GASOLINA GIRON':
					$operarios = $this->presupuesto->get_presupuesto_operarios('TP', 'DTP');
					break;
				case 'TALLER DIESEL GIRON':
					$operarios = $this->presupuesto->get_presupuesto_operarios('TE', 'DTE');
					break;
				case 'TALLER LAMINA Y PINTURA GIRON':
					$operarios = $this->presupuesto->get_presupuesto_operarios('TL', 'DTL');
					break;
				case 'ROSITA CHEVYEXPRESS':
					$operarios = $this->presupuesto->get_presupuesto_operarios('TR', 'DTR');
					break;
				case 'TALLER DIESEL BOCONO':
					$operarios = $this->presupuesto->get_presupuesto_operarios('WE', 'DWE');
					break;
				case 'TALLER GASOLINA BOCONO':
					$operarios = $this->presupuesto->get_presupuesto_operarios('WT', 'DWT');
					break;
				case 'TALLER LAMINA Y PINTURA BOCONO':
					$operarios = $this->presupuesto->get_presupuesto_operarios('WL', 'DWL');
					break;
				case 'TALLER DIESEL BARRANCA':
					$operarios = $this->presupuesto->get_presupuesto_operarios('EB', 'DBE');
					break;
				case 'TALLER GASOLINA BARRANCA':
					$operarios = $this->presupuesto->get_presupuesto_operarios('TK', 'DTK');
					break;
				default:
					# code...
					break;
			}

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "operarios" => $operarios, "bodega" => $bodega, 'presu' => $pres);
			//abrimos la vista
			$this->load->view("presupuesto_operaciones", $arr_user);
		}
	}

	public function cargar_mecanica()
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
			$bodega = $this->input->get('bodega');
			$pres = $this->input->get('presupuesto');
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
			$data_mecanica = "";
			switch ($bodega) {
				case 'TALLER GASOLINA GIRON':
					$mecanica = $this->presupuesto->get_presupuesto_mecanicas('TP', 'DTP');
					$presu_mr = 0;
					$presu_me = 0;
					$presu_cl = 0;
					$presu_cm = 0;
					$presu_cf = 0;
					$presu_acc = 0;
					$presu_gmc = 0;
					$presu_ap = 0;
					$presu_r = 0;
					$presu_in = 0;
					foreach ($mecanica->result() as $key) {
						if ($key->Razon == "MECANICA RAPIDA") {
							$presu_mr = $presu_mr + $key->valor;
						} elseif ($key->Razon == "MECANICA ESPECIALIZADA") {
							$presu_me = $presu_me + $key->valor;
						} elseif ($key->Razon == "COLISION LEVE") {
							$presu_cl = $presu_cl + $key->valor;
						} elseif ($key->Razon == "COLISION MEDIA") {
							$presu_cm = $presu_cm + $key->valor;
						} elseif ($key->Razon == "COLISION FUERTE") {
							$presu_cf = $presu_cf + $key->valor;
						} elseif ($key->Razon == "ACCESORIOS") {
							$presu_acc = $presu_acc + $key->valor;
						} elseif ($key->Razon == "GARANTIA G.M.C.") {
							$presu_gmc = $presu_gmc + $key->valor;
						} elseif ($key->Razon == "ALISTAMIENTO Y PERITAJE") {
							$presu_ap = $presu_ap + $key->valor;
						} elseif ($key->Razon == "RETORNO") {
							$presu_r = $presu_r + $key->valor;
						} elseif ($key->Razon == "INTERNO") {
							$presu_in = $presu_in + $key->valor;
						}
					}
					$data_mr = array('nombre' => "MECANICA RAPIDA", 'valor' => $presu_mr, 'bodega' => "TALLER GASOLINA GIRON");
					$data_me = array('nombre' => "MECANICA ESPECIALIZADA", 'valor' => $presu_me, 'bodega' => "TALLER GASOLINA GIRON");
					$data_cl = array('nombre' => "COLISION LEVE", 'valor' => $presu_cl, 'bodega' => "TALLER GASOLINA GIRON");
					$data_cm = array('nombre' => "COLISION MEDIA", 'valor' => $presu_cm, 'bodega' => "TALLER GASOLINA GIRON");
					$data_cf = array('nombre' => "COLISION FUERTE", 'valor' => $presu_cf, 'bodega' => "TALLER GASOLINA GIRON");
					$data_acc = array('nombre' => "ACCESORIOS", 'valor' => $presu_acc, 'bodega' => "TALLER GASOLINA GIRON");
					$data_gmc = array('nombre' => "GARANTIA G.M.C.", 'valor' => $presu_gmc, 'bodega' => "TALLER GASOLINA GIRON");
					$data_ap = array('nombre' => "ALISTAMIENTO Y PERITAJE", 'valor' => $presu_ap, 'bodega' => "TALLER GASOLINA GIRON");
					$data_r = array('nombre' => "RETORNO", 'valor' => $presu_r, 'bodega' => "TALLER GASOLINA GIRON");
					$data_in = array('nombre' => "INTERNO", 'valor' => $presu_in, 'bodega' => "TALLER GASOLINA GIRON");
					$data_mecanica[] = array($data_mr, $data_me, $data_cl, $data_cm, $data_cf, $data_acc, $data_gmc, $data_ap, $data_r, $data_in);
					break;
				case 'TALLER DIESEL GIRON':
					$mecanica = $this->presupuesto->get_presupuesto_mecanicas('TE', 'DTE');
					$presu_mr = 0;
					$presu_me = 0;
					$presu_cl = 0;
					$presu_cm = 0;
					$presu_cf = 0;
					$presu_acc = 0;
					$presu_gmc = 0;
					$presu_ap = 0;
					$presu_r = 0;
					$presu_in = 0;
					foreach ($mecanica->result() as $key) {
						if ($key->Razon == "MECANICA RAPIDA") {
							$presu_mr = $presu_mr + $key->valor;
						} elseif ($key->Razon == "MECANICA ESPECIALIZADA") {
							$presu_me = $presu_me + $key->valor;
						} elseif ($key->Razon == "COLISION LEVE") {
							$presu_cl = $presu_cl + $key->valor;
						} elseif ($key->Razon == "COLISION MEDIA") {
							$presu_cm = $presu_cm + $key->valor;
						} elseif ($key->Razon == "COLISION FUERTE") {
							$presu_cf = $presu_cf + $key->valor;
						} elseif ($key->Razon == "ACCESORIOS") {
							$presu_acc = $presu_acc + $key->valor;
						} elseif ($key->Razon == "GARANTIA G.M.C.") {
							$presu_gmc = $presu_gmc + $key->valor;
						} elseif ($key->Razon == "ALISTAMIENTO Y PERITAJE") {
							$presu_ap = $presu_ap + $key->valor;
						} elseif ($key->Razon == "RETORNO") {
							$presu_r = $presu_r + $key->valor;
						} elseif ($key->Razon == "INTERNO") {
							$presu_in = $presu_in + $key->valor;
						}
					}
					$data_mr = array('nombre' => "MECANICA RAPIDA", 'valor' => $presu_mr, 'bodega' => "TALLER DIESEL GIRON");
					$data_me = array('nombre' => "MECANICA ESPECIALIZADA", 'valor' => $presu_me, 'bodega' => "TALLER DIESEL GIRON");
					$data_cl = array('nombre' => "COLISION LEVE", 'valor' => $presu_cl, 'bodega' => "TALLER DIESEL GIRON");
					$data_cm = array('nombre' => "COLISION MEDIA", 'valor' => $presu_cm, 'bodega' => "TALLER DIESEL GIRON");
					$data_cf = array('nombre' => "COLISION FUERTE", 'valor' => $presu_cf, 'bodega' => "TALLER DIESEL GIRON");
					$data_acc = array('nombre' => "ACCESORIOS", 'valor' => $presu_acc, 'bodega' => "TALLER DIESEL GIRON");
					$data_gmc = array('nombre' => "GARANTIA G.M.C.", 'valor' => $presu_gmc, 'bodega' => "TALLER DIESEL GIRON");
					$data_ap = array('nombre' => "ALISTAMIENTO Y PERITAJE", 'valor' => $presu_ap, 'bodega' => "TALLER DIESEL GIRON");
					$data_r = array('nombre' => "RETORNO", 'valor' => $presu_r, 'bodega' => "TALLER DIESEL GIRON");
					$data_in = array('nombre' => "INTERNO", 'valor' => $presu_in, 'bodega' => "TALLER DIESEL GIRON");
					$data_mecanica[] = array($data_mr, $data_me, $data_cl, $data_cm, $data_cf, $data_acc, $data_gmc, $data_ap, $data_r, $data_in);
					break;
				case 'TALLER LAMINA Y PINTURA GIRON':
					$mecanica = $this->presupuesto->get_presupuesto_mecanicas('TL', 'DTL');
					$presu_mr = 0;
					$presu_me = 0;
					$presu_cl = 0;
					$presu_cm = 0;
					$presu_cf = 0;
					$presu_acc = 0;
					$presu_gmc = 0;
					$presu_ap = 0;
					$presu_r = 0;
					$presu_in = 0;
					foreach ($mecanica->result() as $key) {
						if ($key->Razon == "MECANICA RAPIDA") {
							$presu_mr = $presu_mr + $key->valor;
						} elseif ($key->Razon == "MECANICA ESPECIALIZADA") {
							$presu_me = $presu_me + $key->valor;
						} elseif ($key->Razon == "COLISION LEVE") {
							$presu_cl = $presu_cl + $key->valor;
						} elseif ($key->Razon == "COLISION MEDIA") {
							$presu_cm = $presu_cm + $key->valor;
						} elseif ($key->Razon == "COLISION FUERTE") {
							$presu_cf = $presu_cf + $key->valor;
						} elseif ($key->Razon == "ACCESORIOS") {
							$presu_acc = $presu_acc + $key->valor;
						} elseif ($key->Razon == "GARANTIA G.M.C.") {
							$presu_gmc = $presu_gmc + $key->valor;
						} elseif ($key->Razon == "ALISTAMIENTO Y PERITAJE") {
							$presu_ap = $presu_ap + $key->valor;
						} elseif ($key->Razon == "RETORNO") {
							$presu_r = $presu_r + $key->valor;
						} elseif ($key->Razon == "INTERNO") {
							$presu_in = $presu_in + $key->valor;
						}
					}
					$data_mr = array('nombre' => "MECANICA RAPIDA", 'valor' => $presu_mr, 'bodega' => "TALLER LAMINA Y PINTURA GIRON");
					$data_me = array('nombre' => "MECANICA ESPECIALIZADA", 'valor' => $presu_me, 'bodega' => "TALLER LAMINA Y PINTURA GIRON");
					$data_cl = array('nombre' => "COLISION LEVE", 'valor' => $presu_cl, 'bodega' => "TALLER LAMINA Y PINTURA GIRON");
					$data_cm = array('nombre' => "COLISION MEDIA", 'valor' => $presu_cm, 'bodega' => "TALLER LAMINA Y PINTURA GIRON");
					$data_cf = array('nombre' => "COLISION FUERTE", 'valor' => $presu_cf, 'bodega' => "TALLER LAMINA Y PINTURA GIRON");
					$data_acc = array('nombre' => "ACCESORIOS", 'valor' => $presu_acc, 'bodega' => "TALLER LAMINA Y PINTURA GIRON");
					$data_gmc = array('nombre' => "GARANTIA G.M.C.", 'valor' => $presu_gmc, 'bodega' => "TALLER LAMINA Y PINTURA GIRON");
					$data_ap = array('nombre' => "ALISTAMIENTO Y PERITAJE", 'valor' => $presu_ap, 'bodega' => "TALLER LAMINA Y PINTURA GIRON");
					$data_r = array('nombre' => "RETORNO", 'valor' => $presu_r, 'bodega' => "TALLER LAMINA Y PINTURA GIRON");
					$data_in = array('nombre' => "INTERNO", 'valor' => $presu_in, 'bodega' => "TALLER LAMINA Y PINTURA GIRON");
					$data_mecanica[] = array($data_mr, $data_me, $data_cl, $data_cm, $data_cf, $data_acc, $data_gmc, $data_ap, $data_r, $data_in);
					break;
				case 'ROSITA CHEVYEXPRESS':
					$mecanica = $this->presupuesto->get_presupuesto_mecanicas('TR', 'DTR');
					$presu_mr = 0;
					$presu_me = 0;
					$presu_cl = 0;
					$presu_cm = 0;
					$presu_cf = 0;
					$presu_acc = 0;
					$presu_gmc = 0;
					$presu_ap = 0;
					$presu_r = 0;
					$presu_in = 0;
					foreach ($mecanica->result() as $key) {
						if ($key->Razon == "MECANICA RAPIDA") {
							$presu_mr = $presu_mr + $key->valor;
						} elseif ($key->Razon == "MECANICA ESPECIALIZADA") {
							$presu_me = $presu_me + $key->valor;
						} elseif ($key->Razon == "COLISION LEVE") {
							$presu_cl = $presu_cl + $key->valor;
						} elseif ($key->Razon == "COLISION MEDIA") {
							$presu_cm = $presu_cm + $key->valor;
						} elseif ($key->Razon == "COLISION FUERTE") {
							$presu_cf = $presu_cf + $key->valor;
						} elseif ($key->Razon == "ACCESORIOS") {
							$presu_acc = $presu_acc + $key->valor;
						} elseif ($key->Razon == "GARANTIA G.M.C.") {
							$presu_gmc = $presu_gmc + $key->valor;
						} elseif ($key->Razon == "ALISTAMIENTO Y PERITAJE") {
							$presu_ap = $presu_ap + $key->valor;
						} elseif ($key->Razon == "RETORNO") {
							$presu_r = $presu_r + $key->valor;
						} elseif ($key->Razon == "INTERNO") {
							$presu_in = $presu_in + $key->valor;
						}
					}
					$data_mr = array('nombre' => "MECANICA RAPIDA", 'valor' => $presu_mr, 'bodega' => "ROSITA CHEVYEXPRESS");
					$data_me = array('nombre' => "MECANICA ESPECIALIZADA", 'valor' => $presu_me, 'bodega' => "ROSITA CHEVYEXPRESS");
					$data_cl = array('nombre' => "COLISION LEVE", 'valor' => $presu_cl, 'bodega' => "ROSITA CHEVYEXPRESS");
					$data_cm = array('nombre' => "COLISION MEDIA", 'valor' => $presu_cm, 'bodega' => "ROSITA CHEVYEXPRESS");
					$data_cf = array('nombre' => "COLISION FUERTE", 'valor' => $presu_cf, 'bodega' => "ROSITA CHEVYEXPRESS");
					$data_acc = array('nombre' => "ACCESORIOS", 'valor' => $presu_acc, 'bodega' => "ROSITA CHEVYEXPRESS");
					$data_gmc = array('nombre' => "GARANTIA G.M.C.", 'valor' => $presu_gmc, 'bodega' => "ROSITA CHEVYEXPRESS");
					$data_ap = array('nombre' => "ALISTAMIENTO Y PERITAJE", 'valor' => $presu_ap, 'bodega' => "ROSITA CHEVYEXPRESS");
					$data_r = array('nombre' => "RETORNO", 'valor' => $presu_r, 'bodega' => "ROSITA CHEVYEXPRESS");
					$data_in = array('nombre' => "INTERNO", 'valor' => $presu_in, 'bodega' => "ROSITA CHEVYEXPRESS");
					$data_mecanica[] = array($data_mr, $data_me, $data_cl, $data_cm, $data_cf, $data_acc, $data_gmc, $data_ap, $data_r, $data_in);
					break;
				case 'TALLER DIESEL BOCONO':
					$mecanica = $this->presupuesto->get_presupuesto_mecanicas('WE', 'DWE');
					$presu_mr = 0;
					$presu_me = 0;
					$presu_cl = 0;
					$presu_cm = 0;
					$presu_cf = 0;
					$presu_acc = 0;
					$presu_gmc = 0;
					$presu_ap = 0;
					$presu_r = 0;
					$presu_in = 0;
					foreach ($mecanica->result() as $key) {
						if ($key->Razon == "MECANICA RAPIDA") {
							$presu_mr = $presu_mr + $key->valor;
						} elseif ($key->Razon == "MECANICA ESPECIALIZADA") {
							$presu_me = $presu_me + $key->valor;
						} elseif ($key->Razon == "COLISION LEVE") {
							$presu_cl = $presu_cl + $key->valor;
						} elseif ($key->Razon == "COLISION MEDIA") {
							$presu_cm = $presu_cm + $key->valor;
						} elseif ($key->Razon == "COLISION FUERTE") {
							$presu_cf = $presu_cf + $key->valor;
						} elseif ($key->Razon == "ACCESORIOS") {
							$presu_acc = $presu_acc + $key->valor;
						} elseif ($key->Razon == "GARANTIA G.M.C.") {
							$presu_gmc = $presu_gmc + $key->valor;
						} elseif ($key->Razon == "ALISTAMIENTO Y PERITAJE") {
							$presu_ap = $presu_ap + $key->valor;
						} elseif ($key->Razon == "RETORNO") {
							$presu_r = $presu_r + $key->valor;
						} elseif ($key->Razon == "INTERNO") {
							$presu_in = $presu_in + $key->valor;
						}
					}
					$data_mr = array('nombre' => "MECANICA RAPIDA", 'valor' => $presu_mr, 'bodega' => "TALLER DIESEL BOCONO");
					$data_me = array('nombre' => "MECANICA ESPECIALIZADA", 'valor' => $presu_me, 'bodega' => "TALLER DIESEL BOCONO");
					$data_cl = array('nombre' => "COLISION LEVE", 'valor' => $presu_cl, 'bodega' => "TALLER DIESEL BOCONO");
					$data_cm = array('nombre' => "COLISION MEDIA", 'valor' => $presu_cm, 'bodega' => "TALLER DIESEL BOCONO");
					$data_cf = array('nombre' => "COLISION FUERTE", 'valor' => $presu_cf, 'bodega' => "TALLER DIESEL BOCONO");
					$data_acc = array('nombre' => "ACCESORIOS", 'valor' => $presu_acc, 'bodega' => "TALLER DIESEL BOCONO");
					$data_gmc = array('nombre' => "GARANTIA G.M.C.", 'valor' => $presu_gmc, 'bodega' => "TALLER DIESEL BOCONO");
					$data_ap = array('nombre' => "ALISTAMIENTO Y PERITAJE", 'valor' => $presu_ap, 'bodega' => "TALLER DIESEL BOCONO");
					$data_r = array('nombre' => "RETORNO", 'valor' => $presu_r, 'bodega' => "TALLER DIESEL BOCONO");
					$data_in = array('nombre' => "INTERNO", 'valor' => $presu_in, 'bodega' => "TALLER DIESEL BOCONO");
					$data_mecanica[] = array($data_mr, $data_me, $data_cl, $data_cm, $data_cf, $data_acc, $data_gmc, $data_ap, $data_r, $data_in);
					break;
				case 'TALLER GASOLINA BOCONO':
					$mecanica = $this->presupuesto->get_presupuesto_mecanicas('WT', 'DWT');
					$presu_mr = 0;
					$presu_me = 0;
					$presu_cl = 0;
					$presu_cm = 0;
					$presu_cf = 0;
					$presu_acc = 0;
					$presu_gmc = 0;
					$presu_ap = 0;
					$presu_r = 0;
					$presu_in = 0;
					foreach ($mecanica->result() as $key) {
						if ($key->Razon == "MECANICA RAPIDA") {
							$presu_mr = $presu_mr + $key->valor;
						} elseif ($key->Razon == "MECANICA ESPECIALIZADA") {
							$presu_me = $presu_me + $key->valor;
						} elseif ($key->Razon == "COLISION LEVE") {
							$presu_cl = $presu_cl + $key->valor;
						} elseif ($key->Razon == "COLISION MEDIA") {
							$presu_cm = $presu_cm + $key->valor;
						} elseif ($key->Razon == "COLISION FUERTE") {
							$presu_cf = $presu_cf + $key->valor;
						} elseif ($key->Razon == "ACCESORIOS") {
							$presu_acc = $presu_acc + $key->valor;
						} elseif ($key->Razon == "GARANTIA G.M.C.") {
							$presu_gmc = $presu_gmc + $key->valor;
						} elseif ($key->Razon == "ALISTAMIENTO Y PERITAJE") {
							$presu_ap = $presu_ap + $key->valor;
						} elseif ($key->Razon == "RETORNO") {
							$presu_r = $presu_r + $key->valor;
						} elseif ($key->Razon == "INTERNO") {
							$presu_in = $presu_in + $key->valor;
						}
					}
					$data_mr = array('nombre' => "MECANICA RAPIDA", 'valor' => $presu_mr, 'bodega' => "TALLER GASOLINA BOCONO");
					$data_me = array('nombre' => "MECANICA ESPECIALIZADA", 'valor' => $presu_me, 'bodega' => "TALLER GASOLINA BOCONO");
					$data_cl = array('nombre' => "COLISION LEVE", 'valor' => $presu_cl, 'bodega' => "TALLER GASOLINA BOCONO");
					$data_cm = array('nombre' => "COLISION MEDIA", 'valor' => $presu_cm, 'bodega' => "TALLER GASOLINA BOCONO");
					$data_cf = array('nombre' => "COLISION FUERTE", 'valor' => $presu_cf, 'bodega' => "TALLER GASOLINA BOCONO");
					$data_acc = array('nombre' => "ACCESORIOS", 'valor' => $presu_acc, 'bodega' => "TALLER GASOLINA BOCONO");
					$data_gmc = array('nombre' => "GARANTIA G.M.C.", 'valor' => $presu_gmc, 'bodega' => "TALLER GASOLINA BOCONO");
					$data_ap = array('nombre' => "ALISTAMIENTO Y PERITAJE", 'valor' => $presu_ap, 'bodega' => "TALLER GASOLINA BOCONO");
					$data_r = array('nombre' => "RETORNO", 'valor' => $presu_r, 'bodega' => "TALLER GASOLINA BOCONO");
					$data_in = array('nombre' => "INTERNO", 'valor' => $presu_in, 'bodega' => "TALLER GASOLINA BOCONO");
					$data_mecanica[] = array($data_mr, $data_me, $data_cl, $data_cm, $data_cf, $data_acc, $data_gmc, $data_ap, $data_r, $data_in);
					break;
				case 'TALLER LAMINA Y PINTURA BOCONO':
					$mecanica = $this->presupuesto->get_presupuesto_mecanicas('WL', 'DWL');
					$presu_mr = 0;
					$presu_me = 0;
					$presu_cl = 0;
					$presu_cm = 0;
					$presu_cf = 0;
					$presu_acc = 0;
					$presu_gmc = 0;
					$presu_ap = 0;
					$presu_r = 0;
					$presu_in = 0;
					foreach ($mecanica->result() as $key) {
						if ($key->Razon == "MECANICA RAPIDA") {
							$presu_mr = $presu_mr + $key->valor;
						} elseif ($key->Razon == "MECANICA ESPECIALIZADA") {
							$presu_me = $presu_me + $key->valor;
						} elseif ($key->Razon == "COLISION LEVE") {
							$presu_cl = $presu_cl + $key->valor;
						} elseif ($key->Razon == "COLISION MEDIA") {
							$presu_cm = $presu_cm + $key->valor;
						} elseif ($key->Razon == "COLISION FUERTE") {
							$presu_cf = $presu_cf + $key->valor;
						} elseif ($key->Razon == "ACCESORIOS") {
							$presu_acc = $presu_acc + $key->valor;
						} elseif ($key->Razon == "GARANTIA G.M.C.") {
							$presu_gmc = $presu_gmc + $key->valor;
						} elseif ($key->Razon == "ALISTAMIENTO Y PERITAJE") {
							$presu_ap = $presu_ap + $key->valor;
						} elseif ($key->Razon == "RETORNO") {
							$presu_r = $presu_r + $key->valor;
						} elseif ($key->Razon == "INTERNO") {
							$presu_in = $presu_in + $key->valor;
						}
					}
					$data_mr = array('nombre' => "MECANICA RAPIDA", 'valor' => $presu_mr, 'bodega' => "TALLER LAMINA Y PINTURA BOCONO");
					$data_me = array('nombre' => "MECANICA ESPECIALIZADA", 'valor' => $presu_me, 'bodega' => "TALLER LAMINA Y PINTURA BOCONO");
					$data_cl = array('nombre' => "COLISION LEVE", 'valor' => $presu_cl, 'bodega' => "TALLER LAMINA Y PINTURA BOCONO");
					$data_cm = array('nombre' => "COLISION MEDIA", 'valor' => $presu_cm, 'bodega' => "TALLER LAMINA Y PINTURA BOCONO");
					$data_cf = array('nombre' => "COLISION FUERTE", 'valor' => $presu_cf, 'bodega' => "TALLER LAMINA Y PINTURA BOCONO");
					$data_acc = array('nombre' => "ACCESORIOS", 'valor' => $presu_acc, 'bodega' => "TALLER LAMINA Y PINTURA BOCONO");
					$data_gmc = array('nombre' => "GARANTIA G.M.C.", 'valor' => $presu_gmc, 'bodega' => "TALLER LAMINA Y PINTURA BOCONO");
					$data_ap = array('nombre' => "ALISTAMIENTO Y PERITAJE", 'valor' => $presu_ap, 'bodega' => "TALLER LAMINA Y PINTURA BOCONO");
					$data_r = array('nombre' => "RETORNO", 'valor' => $presu_r, 'bodega' => "TALLER LAMINA Y PINTURA BOCONO");
					$data_in = array('nombre' => "INTERNO", 'valor' => $presu_in, 'bodega' => "TALLER LAMINA Y PINTURA BOCONO");
					$data_mecanica[] = array($data_mr, $data_me, $data_cl, $data_cm, $data_cf, $data_acc, $data_gmc, $data_ap, $data_r, $data_in);
					break;
				case 'TALLER DIESEL BARRANCA':
					$mecanica = $this->presupuesto->get_presupuesto_mecanicas('EB', 'DBE');
					$presu_mr = 0;
					$presu_me = 0;
					$presu_cl = 0;
					$presu_cm = 0;
					$presu_cf = 0;
					$presu_acc = 0;
					$presu_gmc = 0;
					$presu_ap = 0;
					$presu_r = 0;
					$presu_in = 0;
					foreach ($mecanica->result() as $key) {
						if ($key->Razon == "MECANICA RAPIDA") {
							$presu_mr = $presu_mr + $key->valor;
						} elseif ($key->Razon == "MECANICA ESPECIALIZADA") {
							$presu_me = $presu_me + $key->valor;
						} elseif ($key->Razon == "COLISION LEVE") {
							$presu_cl = $presu_cl + $key->valor;
						} elseif ($key->Razon == "COLISION MEDIA") {
							$presu_cm = $presu_cm + $key->valor;
						} elseif ($key->Razon == "COLISION FUERTE") {
							$presu_cf = $presu_cf + $key->valor;
						} elseif ($key->Razon == "ACCESORIOS") {
							$presu_acc = $presu_acc + $key->valor;
						} elseif ($key->Razon == "GARANTIA G.M.C.") {
							$presu_gmc = $presu_gmc + $key->valor;
						} elseif ($key->Razon == "ALISTAMIENTO Y PERITAJE") {
							$presu_ap = $presu_ap + $key->valor;
						} elseif ($key->Razon == "RETORNO") {
							$presu_r = $presu_r + $key->valor;
						} elseif ($key->Razon == "INTERNO") {
							$presu_in = $presu_in + $key->valor;
						}
					}
					$data_mr = array('nombre' => "MECANICA RAPIDA", 'valor' => $presu_mr, 'bodega' => "TALLER DIESEL BARRANCA");
					$data_me = array('nombre' => "MECANICA ESPECIALIZADA", 'valor' => $presu_me, 'bodega' => "TALLER DIESEL BARRANCA");
					$data_cl = array('nombre' => "COLISION LEVE", 'valor' => $presu_cl, 'bodega' => "TALLER DIESEL BARRANCA");
					$data_cm = array('nombre' => "COLISION MEDIA", 'valor' => $presu_cm, 'bodega' => "TALLER DIESEL BARRANCA");
					$data_cf = array('nombre' => "COLISION FUERTE", 'valor' => $presu_cf, 'bodega' => "TALLER DIESEL BARRANCA");
					$data_acc = array('nombre' => "ACCESORIOS", 'valor' => $presu_acc, 'bodega' => "TALLER DIESEL BARRANCA");
					$data_gmc = array('nombre' => "GARANTIA G.M.C.", 'valor' => $presu_gmc, 'bodega' => "TALLER DIESEL BARRANCA");
					$data_ap = array('nombre' => "ALISTAMIENTO Y PERITAJE", 'valor' => $presu_ap, 'bodega' => "TALLER DIESEL BARRANCA");
					$data_r = array('nombre' => "RETORNO", 'valor' => $presu_r, 'bodega' => "TALLER DIESEL BARRANCA");
					$data_in = array('nombre' => "INTERNO", 'valor' => $presu_in, 'bodega' => "TALLER DIESEL BARRANCA");
					$data_mecanica[] = array($data_mr, $data_me, $data_cl, $data_cm, $data_cf, $data_acc, $data_gmc, $data_ap, $data_r, $data_in);
					break;
				case 'TALLER GASOLINA BARRANCA':
					$mecanica = $this->presupuesto->get_presupuesto_mecanicas('TK', 'DTK');
					$presu_mr = 0;
					$presu_me = 0;
					$presu_cl = 0;
					$presu_cm = 0;
					$presu_cf = 0;
					$presu_acc = 0;
					$presu_gmc = 0;
					$presu_ap = 0;
					$presu_r = 0;
					$presu_in = 0;
					foreach ($mecanica->result() as $key) {
						if ($key->Razon == "MECANICA RAPIDA") {
							$presu_mr = $presu_mr + $key->valor;
						} elseif ($key->Razon == "MECANICA ESPECIALIZADA") {
							$presu_me = $presu_me + $key->valor;
						} elseif ($key->Razon == "COLISION LEVE") {
							$presu_cl = $presu_cl + $key->valor;
						} elseif ($key->Razon == "COLISION MEDIA") {
							$presu_cm = $presu_cm + $key->valor;
						} elseif ($key->Razon == "COLISION FUERTE") {
							$presu_cf = $presu_cf + $key->valor;
						} elseif ($key->Razon == "ACCESORIOS") {
							$presu_acc = $presu_acc + $key->valor;
						} elseif ($key->Razon == "GARANTIA G.M.C.") {
							$presu_gmc = $presu_gmc + $key->valor;
						} elseif ($key->Razon == "ALISTAMIENTO Y PERITAJE") {
							$presu_ap = $presu_ap + $key->valor;
						} elseif ($key->Razon == "RETORNO") {
							$presu_r = $presu_r + $key->valor;
						} elseif ($key->Razon == "INTERNO") {
							$presu_in = $presu_in + $key->valor;
						}
					}
					$data_mr = array('nombre' => "MECANICA RAPIDA", 'valor' => $presu_mr, 'bodega' => "TALLER GASOLINA BARRANCA");
					$data_me = array('nombre' => "MECANICA ESPECIALIZADA", 'valor' => $presu_me, 'bodega' => "TALLER GASOLINA BARRANCA");
					$data_cl = array('nombre' => "COLISION LEVE", 'valor' => $presu_cl, 'bodega' => "TALLER GASOLINA BARRANCA");
					$data_cm = array('nombre' => "COLISION MEDIA", 'valor' => $presu_cm, 'bodega' => "TALLER GASOLINA BARRANCA");
					$data_cf = array('nombre' => "COLISION FUERTE", 'valor' => $presu_cf, 'bodega' => "TALLER GASOLINA BARRANCA");
					$data_acc = array('nombre' => "ACCESORIOS", 'valor' => $presu_acc, 'bodega' => "TALLER GASOLINA BARRANCA");
					$data_gmc = array('nombre' => "GARANTIA G.M.C.", 'valor' => $presu_gmc, 'bodega' => "TALLER GASOLINA BARRANCA");
					$data_ap = array('nombre' => "ALISTAMIENTO Y PERITAJE", 'valor' => $presu_ap, 'bodega' => "TALLER GASOLINA BARRANCA");
					$data_r = array('nombre' => "RETORNO", 'valor' => $presu_r, 'bodega' => "TALLER GASOLINA BARRANCA");
					$data_in = array('nombre' => "INTERNO", 'valor' => $presu_in, 'bodega' => "TALLER GASOLINA BARRANCA");
					$data_mecanica[] = array($data_mr, $data_me, $data_cl, $data_cm, $data_cf, $data_acc, $data_gmc, $data_ap, $data_r, $data_in);
					break;
				default:
					# code...
					break;
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "mecanica" => $data_mecanica, "bodega" => $bodega, 'presu' => $pres);
			//abrimos la vista
			$this->load->view("presupuesto_mecanica", $arr_user);
		}
	}

	public function detalle_mecanica()
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
			$bodega = $this->input->get('bod');
			$razon = $this->input->get('razon');
			$pres = $this->input->get('presupuesto');
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

			switch ($bodega) {
				case 'TALLER GASOLINA GIRON':
					if ($razon == "COLISION LEVE") {
						$det_mec = $this->presupuesto->detalle_mecanica('TP', 'DTP', 1);
					} elseif ($razon == "COLISION MEDIA") {
						$det_mec = $this->presupuesto->detalle_mecanica('TP', 'DTP', 2);
					} elseif ($razon == "COLISION FUERTE") {
						$det_mec = $this->presupuesto->detalle_mecanica('TP', 'DTP', 3);
					} elseif ($razon == "MECANICA ESPECIALIZADA") {
						$det_mec = $this->presupuesto->detalle_mecanica('TP', 'DTP', 4);
					} elseif ($razon == "MECANICA RAPIDA") {
						$det_mec = $this->presupuesto->detalle_mecanica('TP', 'DTP', 5);
					} elseif ($razon == "ACCESORIOS") {
						$det_mec = $this->presupuesto->detalle_mecanica('TP', 'DTP', 6);
					} elseif ($razon == "GARANTIA G.M.C.") {
						$det_mec = $this->presupuesto->detalle_mecanica('TP', 'DTP', 7);
					} elseif ($razon == "ALISTAMIENTO Y PERITAJE") {
						$det_mec = $this->presupuesto->detalle_mecanica('TP', 'DTP', 8);
					} elseif ($razon == "RETORNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('TP', 'DTP', 9);
					} elseif ($razon == "INTERNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('TP', 'DTP', 10);
					}
					break;
				case 'TALLER DIESEL GIRON':
					if ($razon == "COLISION LEVE") {
						$det_mec = $this->presupuesto->detalle_mecanica('TE', 'DTE', 1);
					} elseif ($razon == "COLISION MEDIA") {
						$det_mec = $this->presupuesto->detalle_mecanica('TE', 'DTE', 2);
					} elseif ($razon == "COLISION FUERTE") {
						$det_mec = $this->presupuesto->detalle_mecanica('TE', 'DTE', 3);
					} elseif ($razon == "MECANICA ESPECIALIZADA") {
						$det_mec = $this->presupuesto->detalle_mecanica('TE', 'DTE', 4);
					} elseif ($razon == "MECANICA RAPIDA") {
						$det_mec = $this->presupuesto->detalle_mecanica('TE', 'DTE', 5);
					} elseif ($razon == "ACCESORIOS") {
						$det_mec = $this->presupuesto->detalle_mecanica('TE', 'DTE', 6);
					} elseif ($razon == "GARANTIA G.M.C.") {
						$det_mec = $this->presupuesto->detalle_mecanica('TE', 'DTE', 7);
					} elseif ($razon == "ALISTAMIENTO Y PERITAJE") {
						$det_mec = $this->presupuesto->detalle_mecanica('TE', 'DTE', 8);
					} elseif ($razon == "RETORNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('TE', 'DTE', 9);
					} elseif ($razon == "INTERNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('TE', 'DTE', 10);
					}
					break;
				case 'TALLER LAMINA Y PINTURA GIRON':
					if ($razon == "COLISION LEVE") {
						$det_mec = $this->presupuesto->detalle_mecanica('TL', 'DTL', 1);
					} elseif ($razon == "COLISION MEDIA") {
						$det_mec = $this->presupuesto->detalle_mecanica('TL', 'DTL', 2);
					} elseif ($razon == "COLISION FUERTE") {
						$det_mec = $this->presupuesto->detalle_mecanica('TL', 'DTL', 3);
					} elseif ($razon == "MECANICA ESPECIALIZADA") {
						$det_mec = $this->presupuesto->detalle_mecanica('TL', 'DTL', 4);
					} elseif ($razon == "MECANICA RAPIDA") {
						$det_mec = $this->presupuesto->detalle_mecanica('TL', 'DTL', 5);
					} elseif ($razon == "ACCESORIOS") {
						$det_mec = $this->presupuesto->detalle_mecanica('TL', 'DTL', 6);
					} elseif ($razon == "GARANTIA G.M.C.") {
						$det_mec = $this->presupuesto->detalle_mecanica('TL', 'DTL', 7);
					} elseif ($razon == "ALISTAMIENTO Y PERITAJE") {
						$det_mec = $this->presupuesto->detalle_mecanica('TL', 'DTL', 8);
					} elseif ($razon == "RETORNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('TL', 'DTL', 9);
					} elseif ($razon == "INTERNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('TL', 'DTL', 10);
					}
					break;
				case 'ROSITA CHEVYEXPRESS':
					if ($razon == "COLISION LEVE") {
						$det_mec = $this->presupuesto->detalle_mecanica('TR', 'DTR', 1);
					} elseif ($razon == "COLISION MEDIA") {
						$det_mec = $this->presupuesto->detalle_mecanica('TR', 'DTR', 2);
					} elseif ($razon == "COLISION FUERTE") {
						$det_mec = $this->presupuesto->detalle_mecanica('TR', 'DTR', 3);
					} elseif ($razon == "MECANICA ESPECIALIZADA") {
						$det_mec = $this->presupuesto->detalle_mecanica('TR', 'DTR', 4);
					} elseif ($razon == "MECANICA RAPIDA") {
						$det_mec = $this->presupuesto->detalle_mecanica('TR', 'DTR', 5);
					} elseif ($razon == "ACCESORIOS") {
						$det_mec = $this->presupuesto->detalle_mecanica('TR', 'DTR', 6);
					} elseif ($razon == "GARANTIA G.M.C.") {
						$det_mec = $this->presupuesto->detalle_mecanica('TR', 'DTR', 7);
					} elseif ($razon == "ALISTAMIENTO Y PERITAJE") {
						$det_mec = $this->presupuesto->detalle_mecanica('TR', 'DTR', 8);
					} elseif ($razon == "RETORNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('TR', 'DTR', 9);
					} elseif ($razon == "INTERNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('TR', 'DTR', 10);
					}
					break;
				case 'TALLER DIESEL BOCONO':
					if ($razon == "COLISION LEVE") {
						$det_mec = $this->presupuesto->detalle_mecanica('WE', 'DWE', 1);
					} elseif ($razon == "COLISION MEDIA") {
						$det_mec = $this->presupuesto->detalle_mecanica('WE', 'DWE', 2);
					} elseif ($razon == "COLISION FUERTE") {
						$det_mec = $this->presupuesto->detalle_mecanica('WE', 'DWE', 3);
					} elseif ($razon == "MECANICA ESPECIALIZADA") {
						$det_mec = $this->presupuesto->detalle_mecanica('WE', 'DWE', 4);
					} elseif ($razon == "MECANICA RAPIDA") {
						$det_mec = $this->presupuesto->detalle_mecanica('WE', 'DWE', 5);
					} elseif ($razon == "ACCESORIOS") {
						$det_mec = $this->presupuesto->detalle_mecanica('WE', 'DWE', 6);
					} elseif ($razon == "GARANTIA G.M.C.") {
						$det_mec = $this->presupuesto->detalle_mecanica('WE', 'DWE', 7);
					} elseif ($razon == "ALISTAMIENTO Y PERITAJE") {
						$det_mec = $this->presupuesto->detalle_mecanica('WE', 'DWE', 8);
					} elseif ($razon == "RETORNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('WE', 'DWE', 9);
					} elseif ($razon == "INTERNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('WE', 'DWE', 10);
					}
					break;
				case 'TALLER GASOLINA BOCONO':
					if ($razon == "COLISION LEVE") {
						$det_mec = $this->presupuesto->detalle_mecanica('WT', 'DWT', 1);
					} elseif ($razon == "COLISION MEDIA") {
						$det_mec = $this->presupuesto->detalle_mecanica('WT', 'DWT', 2);
					} elseif ($razon == "COLISION FUERTE") {
						$det_mec = $this->presupuesto->detalle_mecanica('WT', 'DWT', 3);
					} elseif ($razon == "MECANICA ESPECIALIZADA") {
						$det_mec = $this->presupuesto->detalle_mecanica('WT', 'DWT', 4);
					} elseif ($razon == "MECANICA RAPIDA") {
						$det_mec = $this->presupuesto->detalle_mecanica('WT', 'DWT', 5);
					} elseif ($razon == "ACCESORIOS") {
						$det_mec = $this->presupuesto->detalle_mecanica('WT', 'DWT', 6);
					} elseif ($razon == "GARANTIA G.M.C.") {
						$det_mec = $this->presupuesto->detalle_mecanica('WT', 'DWT', 7);
					} elseif ($razon == "ALISTAMIENTO Y PERITAJE") {
						$det_mec = $this->presupuesto->detalle_mecanica('WT', 'DWT', 8);
					} elseif ($razon == "RETORNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('WT', 'DWT', 9);
					} elseif ($razon == "INTERNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('WT', 'DWT', 10);
					}
					break;
				case 'TALLER LAMINA Y PINTURA BOCONO':
					if ($razon == "COLISION LEVE") {
						$det_mec = $this->presupuesto->detalle_mecanica('WL', 'DWL', 1);
					} elseif ($razon == "COLISION MEDIA") {
						$det_mec = $this->presupuesto->detalle_mecanica('WL', 'DWL', 2);
					} elseif ($razon == "COLISION FUERTE") {
						$det_mec = $this->presupuesto->detalle_mecanica('WL', 'DWL', 3);
					} elseif ($razon == "MECANICA ESPECIALIZADA") {
						$det_mec = $this->presupuesto->detalle_mecanica('WL', 'DWL', 4);
					} elseif ($razon == "MECANICA RAPIDA") {
						$det_mec = $this->presupuesto->detalle_mecanica('WL', 'DWL', 5);
					} elseif ($razon == "ACCESORIOS") {
						$det_mec = $this->presupuesto->detalle_mecanica('WL', 'DWL', 6);
					} elseif ($razon == "GARANTIA G.M.C.") {
						$det_mec = $this->presupuesto->detalle_mecanica('WL', 'DWL', 7);
					} elseif ($razon == "ALISTAMIENTO Y PERITAJE") {
						$det_mec = $this->presupuesto->detalle_mecanica('WL', 'DWL', 8);
					} elseif ($razon == "RETORNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('WL', 'DWL', 9);
					} elseif ($razon == "INTERNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('WL', 'DWL', 10);
					}
					break;
				case 'TALLER DIESEL BARRANCA':
					if ($razon == "COLISION LEVE") {
						$det_mec = $this->presupuesto->detalle_mecanica('EB', 'DBE', 1);
					} elseif ($razon == "COLISION MEDIA") {
						$det_mec = $this->presupuesto->detalle_mecanica('EB', 'DBE', 2);
					} elseif ($razon == "COLISION FUERTE") {
						$det_mec = $this->presupuesto->detalle_mecanica('EB', 'DBE', 3);
					} elseif ($razon == "MECANICA ESPECIALIZADA") {
						$det_mec = $this->presupuesto->detalle_mecanica('EB', 'DBE', 4);
					} elseif ($razon == "MECANICA RAPIDA") {
						$det_mec = $this->presupuesto->detalle_mecanica('EB', 'DBE', 5);
					} elseif ($razon == "ACCESORIOS") {
						$det_mec = $this->presupuesto->detalle_mecanica('EB', 'DBE', 6);
					} elseif ($razon == "GARANTIA G.M.C.") {
						$det_mec = $this->presupuesto->detalle_mecanica('EB', 'DBE', 7);
					} elseif ($razon == "ALISTAMIENTO Y PERITAJE") {
						$det_mec = $this->presupuesto->detalle_mecanica('EB', 'DBE', 8);
					} elseif ($razon == "RETORNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('EB', 'DBE', 9);
					} elseif ($razon == "INTERNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('EB', 'DBE', 10);
					}
					break;
				case 'TALLER GASOLINA BARRANCA':
					if ($razon == "COLISION LEVE") {
						$det_mec = $this->presupuesto->detalle_mecanica('TK', 'DTK', 1);
					} elseif ($razon == "COLISION MEDIA") {
						$det_mec = $this->presupuesto->detalle_mecanica('TK', 'DTK', 2);
					} elseif ($razon == "COLISION FUERTE") {
						$det_mec = $this->presupuesto->detalle_mecanica('TK', 'DTK', 3);
					} elseif ($razon == "MECANICA ESPECIALIZADA") {
						$det_mec = $this->presupuesto->detalle_mecanica('TK', 'DTK', 4);
					} elseif ($razon == "MECANICA RAPIDA") {
						$det_mec = $this->presupuesto->detalle_mecanica('TK', 'DTK', 5);
					} elseif ($razon == "ACCESORIOS") {
						$det_mec = $this->presupuesto->detalle_mecanica('TK', 'DTK', 6);
					} elseif ($razon == "GARANTIA G.M.C.") {
						$det_mec = $this->presupuesto->detalle_mecanica('TK', 'DTK', 7);
					} elseif ($razon == "ALISTAMIENTO Y PERITAJE") {
						$det_mec = $this->presupuesto->detalle_mecanica('TK', 'DTK', 8);
					} elseif ($razon == "RETORNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('TK', 'DTK', 9);
					} elseif ($razon == "INTERNO") {
						$det_mec = $this->presupuesto->detalle_mecanica('TK', 'DTK', 10);
					}
					break;
				default:
					# code...
					break;
			}

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "mecanica" => $det_mec, "bodega" => $bodega, 'presu' => $pres);
			//abrimos la vista
			$this->load->view("presupuesto_detalle_mecanica", $arr_user);
		}
	}

	public function cargar_clientes()
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
			$bodega = $this->input->get('bodega');
			$pres = $this->input->get('presupuesto');
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
			switch ($bodega) {
				case 'TALLER GASOLINA GIRON':
					$clientes = $this->presupuesto->presupuesto_clientes('TP', 'DTP');
					$presu_ase = 0;
					$presu_uau = 0;
					$presu_emp = 0;
					$presu_flo = 0;
					foreach ($clientes->result() as $key) {
						if ($key->Clase == "ASEGURADORA") {
							$presu_ase = $presu_ase + $key->valor;
						} elseif ($key->Clase == "CLIENTES UNO A UNO") {
							$presu_uau = $presu_uau + $key->valor;
						} elseif ($key->Clase == "EMPLEADO") {
							$presu_emp = $presu_emp + $key->valor;
						} elseif ($key->Clase == "FLOTA") {
							$presu_flo = $presu_flo + $key->valor;
						}
					}
					$data_ase = array('nombre' => "ASEGURADORA", 'valor' => $presu_ase, 'bodega' => "TALLER GASOLINA GIRON");
					$data_uau = array('nombre' => "CLIENTES UNO A UNO", 'valor' => $presu_uau, 'bodega' => "TALLER GASOLINA GIRON");
					$data_emp = array('nombre' => "EMPLEADO", 'valor' => $presu_emp, 'bodega' => "TALLER GASOLINA GIRON");
					$data_flo = array('nombre' => "FLOTA", 'valor' => $presu_flo, 'bodega' => "TALLER GASOLINA GIRON");
					$data_mecanica[] = array($data_ase, $data_uau, $data_emp, $data_flo);
					break;
				case 'TALLER DIESEL GIRON':
					$clientes = $this->presupuesto->presupuesto_clientes('TE', 'DTE');
					$presu_ase = 0;
					$presu_uau = 0;
					$presu_emp = 0;
					$presu_flo = 0;
					foreach ($clientes->result() as $key) {
						if ($key->Clase == "ASEGURADORA") {
							$presu_ase = $presu_ase + $key->valor;
						} elseif ($key->Clase == "CLIENTES UNO A UNO") {
							$presu_uau = $presu_uau + $key->valor;
						} elseif ($key->Clase == "EMPLEADO") {
							$presu_emp = $presu_emp + $key->valor;
						} elseif ($key->Clase == "FLOTA") {
							$presu_flo = $presu_flo + $key->valor;
						}
					}
					$data_ase = array('nombre' => "ASEGURADORA", 'valor' => $presu_ase, 'bodega' => "TALLER DIESEL GIRON");
					$data_uau = array('nombre' => "CLIENTES UNO A UNO", 'valor' => $presu_uau, 'bodega' => "TALLER DIESEL GIRON");
					$data_emp = array('nombre' => "EMPLEADO", 'valor' => $presu_emp, 'bodega' => "TALLER DIESEL GIRON");
					$data_flo = array('nombre' => "FLOTA", 'valor' => $presu_flo, 'bodega' => "TALLER DIESEL GIRON");
					$data_mecanica[] = array($data_ase, $data_uau, $data_emp, $data_flo);
					break;
				case 'TALLER LAMINA Y PINTURA GIRON':
					$clientes = $this->presupuesto->presupuesto_clientes('TL', 'DTL');
					$presu_ase = 0;
					$presu_uau = 0;
					$presu_emp = 0;
					$presu_flo = 0;
					foreach ($clientes->result() as $key) {
						if ($key->Clase == "ASEGURADORA") {
							$presu_ase = $presu_ase + $key->valor;
						} elseif ($key->Clase == "CLIENTES UNO A UNO") {
							$presu_uau = $presu_uau + $key->valor;
						} elseif ($key->Clase == "EMPLEADO") {
							$presu_emp = $presu_emp + $key->valor;
						} elseif ($key->Clase == "FLOTA") {
							$presu_flo = $presu_flo + $key->valor;
						}
					}
					$data_ase = array('nombre' => "ASEGURADORA", 'valor' => $presu_ase, 'bodega' => "TALLER LAMINA Y PINTURA GIRON");
					$data_uau = array('nombre' => "CLIENTES UNO A UNO", 'valor' => $presu_uau, 'bodega' => "TALLER LAMINA Y PINTURA GIRON");
					$data_emp = array('nombre' => "EMPLEADO", 'valor' => $presu_emp, 'bodega' => "TALLER LAMINA Y PINTURA GIRON");
					$data_flo = array('nombre' => "FLOTA", 'valor' => $presu_flo, 'bodega' => "TALLER LAMINA Y PINTURA GIRON");
					$data_mecanica[] = array($data_ase, $data_uau, $data_emp, $data_flo);
					break;
				case 'ROSITA CHEVYEXPRESS':
					$clientes = $this->presupuesto->presupuesto_clientes('TR', 'DTR');
					$presu_ase = 0;
					$presu_uau = 0;
					$presu_emp = 0;
					$presu_flo = 0;
					foreach ($clientes->result() as $key) {
						if ($key->Clase == "ASEGURADORA") {
							$presu_ase = $presu_ase + $key->valor;
						} elseif ($key->Clase == "CLIENTES UNO A UNO") {
							$presu_uau = $presu_uau + $key->valor;
						} elseif ($key->Clase == "EMPLEADO") {
							$presu_emp = $presu_emp + $key->valor;
						} elseif ($key->Clase == "FLOTA") {
							$presu_flo = $presu_flo + $key->valor;
						}
					}
					$data_ase = array('nombre' => "ASEGURADORA", 'valor' => $presu_ase, 'bodega' => "ROSITA CHEVYEXPRESS");
					$data_uau = array('nombre' => "CLIENTES UNO A UNO", 'valor' => $presu_uau, 'bodega' => "ROSITA CHEVYEXPRESS");
					$data_emp = array('nombre' => "EMPLEADO", 'valor' => $presu_emp, 'bodega' => "ROSITA CHEVYEXPRESS");
					$data_flo = array('nombre' => "FLOTA", 'valor' => $presu_flo, 'bodega' => "ROSITA CHEVYEXPRESS");
					$data_mecanica[] = array($data_ase, $data_uau, $data_emp, $data_flo);
					break;
				case 'TALLER DIESEL BOCONO':
					$clientes = $this->presupuesto->presupuesto_clientes('WE', 'DWE');
					$presu_ase = 0;
					$presu_uau = 0;
					$presu_emp = 0;
					$presu_flo = 0;
					foreach ($clientes->result() as $key) {
						if ($key->Clase == "ASEGURADORA") {
							$presu_ase = $presu_ase + $key->valor;
						} elseif ($key->Clase == "CLIENTES UNO A UNO") {
							$presu_uau = $presu_uau + $key->valor;
						} elseif ($key->Clase == "EMPLEADO") {
							$presu_emp = $presu_emp + $key->valor;
						} elseif ($key->Clase == "FLOTA") {
							$presu_flo = $presu_flo + $key->valor;
						}
					}
					$data_ase = array('nombre' => "ASEGURADORA", 'valor' => $presu_ase, 'bodega' => "TALLER DIESEL BOCONO");
					$data_uau = array('nombre' => "CLIENTES UNO A UNO", 'valor' => $presu_uau, 'bodega' => "TALLER DIESEL BOCONO");
					$data_emp = array('nombre' => "EMPLEADO", 'valor' => $presu_emp, 'bodega' => "TALLER DIESEL BOCONO");
					$data_flo = array('nombre' => "FLOTA", 'valor' => $presu_flo, 'bodega' => "TALLER DIESEL BOCONO");
					$data_mecanica[] = array($data_ase, $data_uau, $data_emp, $data_flo);
					break;
				case 'TALLER GASOLINA BOCONO':
					$clientes = $this->presupuesto->presupuesto_clientes('WT', 'DWT');
					$presu_ase = 0;
					$presu_uau = 0;
					$presu_emp = 0;
					$presu_flo = 0;
					foreach ($clientes->result() as $key) {
						if ($key->Clase == "ASEGURADORA") {
							$presu_ase = $presu_ase + $key->valor;
						} elseif ($key->Clase == "CLIENTES UNO A UNO") {
							$presu_uau = $presu_uau + $key->valor;
						} elseif ($key->Clase == "EMPLEADO") {
							$presu_emp = $presu_emp + $key->valor;
						} elseif ($key->Clase == "FLOTA") {
							$presu_flo = $presu_flo + $key->valor;
						}
					}
					$data_ase = array('nombre' => "ASEGURADORA", 'valor' => $presu_ase, 'bodega' => "TALLER GASOLINA BOCONO");
					$data_uau = array('nombre' => "CLIENTES UNO A UNO", 'valor' => $presu_uau, 'bodega' => "TALLER GASOLINA BOCONO");
					$data_emp = array('nombre' => "EMPLEADO", 'valor' => $presu_emp, 'bodega' => "TALLER GASOLINA BOCONO");
					$data_flo = array('nombre' => "FLOTA", 'valor' => $presu_flo, 'bodega' => "TALLER GASOLINA BOCONO");
					$data_mecanica[] = array($data_ase, $data_uau, $data_emp, $data_flo);
					break;
				case 'TALLER LAMINA Y PINTURA BOCONO':
					$clientes = $this->presupuesto->presupuesto_clientes('WL', 'DWL');
					$presu_ase = 0;
					$presu_uau = 0;
					$presu_emp = 0;
					$presu_flo = 0;
					foreach ($clientes->result() as $key) {
						if ($key->Clase == "ASEGURADORA") {
							$presu_ase = $presu_ase + $key->valor;
						} elseif ($key->Clase == "CLIENTES UNO A UNO") {
							$presu_uau = $presu_uau + $key->valor;
						} elseif ($key->Clase == "EMPLEADO") {
							$presu_emp = $presu_emp + $key->valor;
						} elseif ($key->Clase == "FLOTA") {
							$presu_flo = $presu_flo + $key->valor;
						}
					}
					$data_ase = array('nombre' => "ASEGURADORA", 'valor' => $presu_ase, 'bodega' => "TALLER LAMINA Y PINTURA BOCONO");
					$data_uau = array('nombre' => "CLIENTES UNO A UNO", 'valor' => $presu_uau, 'bodega' => "TALLER LAMINA Y PINTURA BOCONO");
					$data_emp = array('nombre' => "EMPLEADO", 'valor' => $presu_emp, 'bodega' => "TALLER LAMINA Y PINTURA BOCONO");
					$data_flo = array('nombre' => "FLOTA", 'valor' => $presu_flo, 'bodega' => "TALLER LAMINA Y PINTURA BOCONO");
					$data_mecanica[] = array($data_ase, $data_uau, $data_emp, $data_flo);
					break;
				case 'TALLER DIESEL BARRANCA':
					$clientes = $this->presupuesto->presupuesto_clientes('EB', 'DBE');
					$presu_ase = 0;
					$presu_uau = 0;
					$presu_emp = 0;
					$presu_flo = 0;
					foreach ($clientes->result() as $key) {
						if ($key->Clase == "ASEGURADORA") {
							$presu_ase = $presu_ase + $key->valor;
						} elseif ($key->Clase == "CLIENTES UNO A UNO") {
							$presu_uau = $presu_uau + $key->valor;
						} elseif ($key->Clase == "EMPLEADO") {
							$presu_emp = $presu_emp + $key->valor;
						} elseif ($key->Clase == "FLOTA") {
							$presu_flo = $presu_flo + $key->valor;
						}
					}
					$data_ase = array('nombre' => "ASEGURADORA", 'valor' => $presu_ase, 'bodega' => "TALLER DIESEL BARRANCA");
					$data_uau = array('nombre' => "CLIENTES UNO A UNO", 'valor' => $presu_uau, 'bodega' => "TALLER DIESEL BARRANCA");
					$data_emp = array('nombre' => "EMPLEADO", 'valor' => $presu_emp, 'bodega' => "TALLER DIESEL BARRANCA");
					$data_flo = array('nombre' => "FLOTA", 'valor' => $presu_flo, 'bodega' => "TALLER DIESEL BARRANCA");
					$data_mecanica[] = array($data_ase, $data_uau, $data_emp, $data_flo);
					break;
				case 'TALLER GASOLINA BARRANCA':
					$clientes = $this->presupuesto->presupuesto_clientes('TK', 'DTK');
					$clientes = $this->presupuesto->presupuesto_clientes('EB', 'DBE');
					$presu_ase = 0;
					$presu_uau = 0;
					$presu_emp = 0;
					$presu_flo = 0;
					foreach ($clientes->result() as $key) {
						if ($key->Clase == "ASEGURADORA") {
							$presu_ase = $presu_ase + $key->valor;
						} elseif ($key->Clase == "CLIENTES UNO A UNO") {
							$presu_uau = $presu_uau + $key->valor;
						} elseif ($key->Clase == "EMPLEADO") {
							$presu_emp = $presu_emp + $key->valor;
						} elseif ($key->Clase == "FLOTA") {
							$presu_flo = $presu_flo + $key->valor;
						}
					}
					$data_ase = array('nombre' => "ASEGURADORA", 'valor' => $presu_ase, 'bodega' => "TALLER GASOLINA BARRANCA");
					$data_uau = array('nombre' => "CLIENTES UNO A UNO", 'valor' => $presu_uau, 'bodega' => "TALLER GASOLINA BARRANCA");
					$data_emp = array('nombre' => "EMPLEADO", 'valor' => $presu_emp, 'bodega' => "TALLER GASOLINA BARRANCA");
					$data_flo = array('nombre' => "FLOTA", 'valor' => $presu_flo, 'bodega' => "TALLER GASOLINA BARRANCA");
					$data_mecanica[] = array($data_ase, $data_uau, $data_emp, $data_flo);
					break;
				default:
					# code...
					break;
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "clientes" => $clientes, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_mecanica);
			//abrimos la vista
			$this->load->view("presupuesto_clientes", $arr_user);
		}
	}

	public function cargar_cliente_total()
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
			$bodega = $this->input->get('bod');
			$cliente = $this->input->get('cliente');
			$pres = $this->input->get('presupuesto');
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
			$data_clientes[] = array();
			$aseguradoras = array();
			$uau = array();
			$emp = array();
			$flo = array();
			switch ($bodega) {
				case 'TALLER GASOLINA GIRON':

					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->cargar_clientes_total('TP', 'DTP');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->cargar_clientes_total('TP', 'DTP');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->cargar_clientes_total('TP', 'DTP');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->cargar_clientes_total('TP', 'DTP');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;

						default:
							# code...
							break;
					}

					break;
				case 'TALLER DIESEL GIRON':
					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->cargar_clientes_total('TE', 'DTE');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->cargar_clientes_total('TE', 'DTE');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->cargar_clientes_total('TE', 'DTE');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->cargar_clientes_total('TE', 'DTE');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;

						default:
							# code...
							break;
					}
					break;
				case 'TALLER LAMINA Y PINTURA GIRON':
					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->cargar_clientes_total('TL', 'DTL');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->cargar_clientes_total('TL', 'DTL');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->cargar_clientes_total('TL', 'DTL');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->cargar_clientes_total('TL', 'DTL');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;

						default:
							# code...
							break;
					}
					break;
				case 'ROSITA CHEVYEXPRESS':
					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->cargar_clientes_total('TR', 'DTR');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->cargar_clientes_total('TR', 'DTR');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->cargar_clientes_total('TR', 'DTR');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->cargar_clientes_total('TR', 'DTR');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;

						default:
							# code...
							break;
					}
					break;
				case 'TALLER DIESEL BOCONO':
					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->cargar_clientes_total('WE', 'DWE');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->cargar_clientes_total('WE', 'DWE');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->cargar_clientes_total('WE', 'DWE');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->cargar_clientes_total('WE', 'DWE');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;

						default:
							# code...
							break;
					}
					break;
				case 'TALLER GASOLINA BOCONO':
					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->cargar_clientes_total('WT', 'DWT');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->cargar_clientes_total('WT', 'DWT');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->cargar_clientes_total('WT', 'DWT');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->cargar_clientes_total('WT', 'DWT');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;

						default:
							# code...
							break;
					}
					break;
				case 'TALLER LAMINA Y PINTURA BOCONO':
					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->cargar_clientes_total('WL', 'DWL');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->cargar_clientes_total('WL', 'DWL');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->cargar_clientes_total('WL', 'DWL');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->cargar_clientes_total('WL', 'DWL');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;

						default:
							# code...
							break;
					}
					break;
				case 'TALLER DIESEL BARRANCA':
					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->cargar_clientes_total('EB', 'DEB');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->cargar_clientes_total('EB', 'DEB');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->cargar_clientes_total('EB', 'DEB');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->cargar_clientes_total('EB', 'DEB');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;

						default:
							# code...
							break;
					}
					break;
				case 'TALLER GASOLINA BARRANCA':
					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->cargar_clientes_total('TK', 'DTK');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->cargar_clientes_total('TK', 'DTK');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->cargar_clientes_total('TK', 'DTK');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->cargar_clientes_total('TK', 'DTK');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA") {
									$aseguradoras = array('nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit, 'clase' => $key->Clase);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_clientes_total", $arr_user);
							break;

						default:
							# code...
							break;
					}
					break;
				default:
					# code...
					break;
			}

			//$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu,"bodega" => $bodega,'presu' => $pres, 'arr_cliente' => $data_clientes);
			//abrimos la vista
			// $this->load->view("presupuesto_detalle_cliente",$arr_user);
		}
	}

	public function cargar_detalle_cliente()
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
			$bodega = $this->input->get('bod');
			$cliente = $this->input->get('cliente');
			$pres = $this->input->get('presupuesto');
			$nit = $this->input->get('nit');
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
			$data_clientes[] = array();
			$aseguradoras = array();
			$uau = array();
			$emp = array();
			$flo = array();
			switch ($bodega) {
				case 'TALLER GASOLINA GIRON':

					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->detalle_clientes('TP', 'DTP');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->detalle_clientes('TP', 'DTP');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->detalle_clientes('TP', 'DTP');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->detalle_clientes('TP', 'DTP');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;

						default:
							# code...
							break;
					}

					break;
				case 'TALLER DIESEL GIRON':
					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->detalle_clientes('TE', 'DTE');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->detalle_clientes('TE', 'DTE');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->detalle_clientes('TE', 'DTE');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->detalle_clientes('TE', 'DTE');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;

						default:
							# code...
							break;
					}
					break;
				case 'TALLER LAMINA Y PINTURA GIRON':
					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->detalle_clientes('TL', 'DTL');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->detalle_clientes('TL', 'DTL');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->detalle_clientes('TL', 'DTL');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->detalle_clientes('TL', 'DTL');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;

						default:
							# code...
							break;
					}
					break;
				case 'ROSITA CHEVYEXPRESS':
					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->detalle_clientes('TR', 'DTR');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->detalle_clientes('TR', 'DTR');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->detalle_clientes('TR', 'DTR');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->detalle_clientes('TR', 'DTR');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;

						default:
							# code...
							break;
					}
					break;
				case 'TALLER DIESEL BOCONO':
					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->detalle_clientes('WE', 'DWE');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->detalle_clientes('WE', 'DWE');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->detalle_clientes('WE', 'DWE');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->detalle_clientes('WE', 'DWE');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;

						default:
							# code...
							break;
					}
					break;
				case 'TALLER GASOLINA BOCONO':
					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->detalle_clientes('WT', 'DWT');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->detalle_clientes('WT', 'DWT');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->detalle_clientes('WT', 'DWT');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->detalle_clientes('WT', 'DWT');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;

						default:
							# code...
							break;
					}
					break;
				case 'TALLER LAMINA Y PINTURA BOCONO':
					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->detalle_clientes('WL', 'DWL');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->detalle_clientes('WL', 'DWL');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->detalle_clientes('WL', 'DWL');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->detalle_clientes('WL', 'DWL');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;

						default:
							# code...
							break;
					}
					break;
				case 'TALLER DIESEL BARRANCA':
					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->detalle_clientes('EB', 'DEB');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->detalle_clientes('EB', 'DEB');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->detalle_clientes('EB', 'DEB');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->detalle_clientes('EB', 'DEB');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;

						default:
							# code...
							break;
					}
					break;
				case 'TALLER GASOLINA BARRANCA':
					switch ($cliente) {
						case 'ASEGURADORA':
							$dat_client = $this->presupuesto->detalle_clientes('TK', 'DTK');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "ASEGURADORA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'CLIENTES UNO A UNO':
							$dat_client = $this->presupuesto->detalle_clientes('TK', 'DTK');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "CLIENTES UNO A UNO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'EMPLEADO':
							$dat_client = $this->presupuesto->detalle_clientes('TK', 'DTK');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "EMPLEADO" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;
						case 'FLOTA':
							$dat_client = $this->presupuesto->detalle_clientes('TK', 'DTK');
							foreach ($dat_client->result() as $key) {
								if ($key->Clase == "FLOTA" && $key->nit == $nit) {
									$aseguradoras = array('tipo' => $key->tipo, 'numero' => $key->numero, 'nombres' => $key->nombres, 'valor' => $key->valor, 'nit' => $key->nit);
									$data_clientes[] = array($aseguradoras);
								}
							}
							$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "bodega" => $bodega, 'presu' => $pres, 'arr_cliente' => $data_clientes);
							//abrimos la vista
							$this->load->view("presupuesto_detalle_cliente", $arr_user);
							break;

						default:
							# code...
							break;
					}
					break;
				default:
					# code...
					break;
			}

			//$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu,"bodega" => $bodega,'presu' => $pres, 'arr_cliente' => $data_clientes);
			//abrimos la vista
			// $this->load->view("presupuesto_detalle_cliente",$arr_user);
		}
	}

	public function ingresar_presupuesto()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Informe');
			$this->load->model('presupuesto');
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);

			if ($usu != "") {
				//cargamos la informacion del usuario y la pasamos a un array
				$userinfo = $this->usuarios->getUserByName($usu);
				$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
				//$allsubmenus = $this->menus->getAllSubmenus();
				$allperfiles = $this->perfiles->getAllPerfiles();
				$nombre_mes = $this->Informe->get_nombre_mes_actual();
				//prueba de menu

				$id_usu = "";
				foreach ($userinfo->result() as $key) {
					$id_usu = $key->id_usuario;
				}
				//echo $id_usu;
				$mes = $this->input->POST('mes');
				$year = $this->input->POST('year');
				$all_presu = null;
				if ($mes != "") {

					$all_presu = $this->presupuesto->listar_presupuesto_mes($mes, $year);
				} else {

					$all_presu = null;
				}


				$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'mes' => $nombre_mes->mes, 'all_presu' => $all_presu);
				//abrimos la vista
				$this->load->view('ingresar_presupuesto', $arr_user);
			} else {
				$this->session->sess_destroy();
				header("Location: " . base_url() . "");
			}
		}
	}

	public function detalle_operarios()
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
			$bodega = $this->input->get('bodega');
			$pres = $this->input->get('presu');
			$ope = $this->input->get('ope');
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
			switch ($bodega) {
				case 'TALLER GASOLINA GIRON':
					$operarios = $this->presupuesto->detalle_operarios('TP', 'DTP', $ope);
					break;
				case 'TALLER DIESEL GIRON':
					$operarios = $this->presupuesto->detalle_operarios('TE', 'DTE', $ope);
					break;
				case 'TALLER LAMINA Y PINTURA GIRON':
					$operarios = $this->presupuesto->detalle_operarios('TL', 'DTL', $ope);
					break;
				case 'ROSITA CHEVYEXPRESS':
					$operarios = $this->presupuesto->detalle_operarios('TR', 'DTR', $ope);
					break;
				case 'TALLER DIESEL BOCONO':
					$operarios = $this->presupuesto->detalle_operarios('WE', 'DWE', $ope);
					break;
				case 'TALLER GASOLINA BOCONO':
					$operarios = $this->presupuesto->detalle_operarios('WT', 'DWT', $ope);
					break;
				case 'TALLER LAMINA Y PINTURA BOCONO':
					$operarios = $this->presupuesto->detalle_operarios('WL', 'DWL', $ope);
					break;
				case 'TALLER DIESEL BARRANCA':
					$operarios = $this->presupuesto->detalle_operarios('EB', 'DBE', $ope);
					break;
				case 'TALLER GASOLINA BARRANCA':
					$operarios = $this->presupuesto->detalle_operarios('TK', 'DTK', $ope);
					break;
				default:
					# code...
					break;
			}

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "operarios" => $operarios, "bodega" => $bodega, 'presu' => $pres);
			//abrimos la vista
			$this->load->view("presupuesto_detalle_operaciones", $arr_user);
		}
	}
	/**
	 *METODO PARA INGRESAR EL PRESUPUESTO MENSUAL
	 *ANDRES GOMEZ
	 *2021-12-07
	 */

	public function crear_presupuesto()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('presupuesto');
			$this->load->model('Informe');
			$this->load->model('nominas');

			$fecha_ini = $this->nominas->obtener_primer_dia_mes();
			$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
			$mes = $this->Informe->get_nombre_mes_actual();

			/*sedes */
			$sede = $this->input->POST('sede');
			$sede_r_gasolina = $this->input->POST('sede_r_gasolina');
			$sede_t_gasolina = $this->input->POST('sede_t_gasolina');
			$sede_m_gasolina = $this->input->POST('sede_m_gasolina');
			$sede_r_diesel = $this->input->POST('sede_r_diesel');
			$sede_t_diesel = $this->input->POST('sede_t_diesel');
			$sede_m_diesel = $this->input->POST('sede_m_diesel');
			$sede_r_mostrador = $this->input->POST('sede_r_mostrador');
			$sede_t_lamina = $this->input->POST('sede_t_lamina');
			$sede_m_lamina = $this->input->POST('sede_m_lamina');
			$sede_r_lamina = $this->input->POST('sede_r_lamina');


			/*valor numericos */
			echo $m_gasolina = $this->input->POST('m_gasolina');
			echo "<br>";
			echo $t_gasolina = $this->input->POST('t_gasolina');
			echo "<br>";
			echo $r_gasolina = $this->input->POST('r_gasolina');
			echo "<br>";
			echo $m_diesel = $this->input->POST('m_diesel');
			echo "<br>";
			echo $t_diesel = $this->input->POST('t_diesel');
			echo "<br>";
			echo $r_diesel = $this->input->POST('r_diesel');
			echo "<br>";
			echo $r_mostrador = $this->input->POST('r_mostrador');
			echo "<br>";
			echo $r_lamina = $this->input->POST('r_lamina');
			echo "<br>";
			echo $m_lamina = $this->input->POST('t_lamina');
			echo "<br>";
			echo $t_lamina = $this->input->POST('m_lamina');
			echo "<br>";



			$valor_gasolina = "";
			$valor_diesel = "";
			$valor_lamina = "";
			$valor_total = "";

			if ($sede == 'BARRANCA') {

				$taller_gasolina = 'BARRANCA CHEVRY EXPRESS';
				$taller_diesel = 'BARRANCA DIESEL EXPRESS';
				$suma_total = 'CODIESEL BARRANCABERMEJA';
				$sedebarrancamostrador = 'BARRANCA REPUESTOS MOSTRADOR';

				$valor_gasolina = $r_gasolina + $t_gasolina + $m_gasolina;
				$valor_diesel = $r_diesel + $t_diesel + $m_diesel;
				$valor_total = $valor_gasolina + $valor_diesel + $r_mostrador;

				$arraynumerico = array($m_gasolina, $t_gasolina, $r_gasolina, $m_diesel, $t_diesel, $r_diesel, $r_mostrador, $valor_gasolina, $valor_diesel, $valor_total);
				$arraysedes = array(
					$sede_m_gasolina . ' ' . $sede, $sede_t_gasolina . ' ' .  $sede, $sede_r_gasolina . ' ' .  $sede, $sede_m_diesel . ' ' .  $sede, $sede_t_diesel . ' ' .  $sede,
					$sede_r_diesel . ' ' .  $sede, $sedebarrancamostrador, $taller_gasolina, $taller_diesel, $suma_total
				);

				for ($i = 0; $i < 10; $i++) {
					$this->presupuesto->insert_data_presupuesto($arraynumerico[$i], $arraysedes[$i], $fecha_ini->fecha, $fecha_fin->fecha, $mes->mes);
				}
			} else if ($sede == 'GIRON') {
				$taller_gasolina = 'GIRON GASOLINA';
				$taller_diesel = 'GIRON DIESEL EXPRESS';
				$taller_lamina = 'GIRON LAMINA Y PINTURA';
				$suma_total = 'CODIESEL PRINCIPAL';
				$repuestolamina = 'REPUESTOS LYP GIRON';
				$totlmgiron = 'TOT LYP GIRON';
				$molypgiron = 'MO LYP GIRON';

				$valor_gasolina = $r_gasolina + $t_gasolina + $m_gasolina;
				$valor_diesel = $r_diesel + $t_diesel + $m_diesel;
				$valor_lamina = $r_lamina + $t_lamina + $m_lamina;
				$valor_total = $valor_gasolina + $valor_diesel + $r_mostrador + $valor_lamina;

				$arrayvaloresgiron = array(
					$m_gasolina, $t_gasolina, $r_gasolina, $m_diesel, $t_diesel, $r_diesel, $r_mostrador, $m_lamina, $t_lamina, $r_lamina, $valor_gasolina,
					$valor_diesel, $valor_lamina, $valor_total
				);
				$arraysedesgiron = array(
					$sede_m_gasolina . ' ' . $sede, $sede_t_gasolina . ' ' . $sede, $sede_r_gasolina . ' ' . $sede, $sede_m_diesel . ' ' . $sede, $sede_t_diesel . ' ' . $sede,
					$sede_r_diesel . ' ' . $sede, $sede . ' ' . $sede_r_mostrador, $molypgiron, $totlmgiron, $repuestolamina,
					$taller_gasolina, $taller_diesel, $taller_lamina, $suma_total

				);

				for ($i = 0; $i < 14; $i++) {
					$this->presupuesto->insert_data_presupuesto($arrayvaloresgiron[$i], $arraysedesgiron[$i], $fecha_ini->fecha, $fecha_fin->fecha, $mes->mes);
				}
			} else if ($sede == 'BOCONO') {
				$taller_gasolina = 'BOCONO GASOLINA';
				$taller_diesel = 'DIESEL EXPRESS BOCONO';
				$taller_lamina = 'BOCONO LAMINA Y PINTURA';
				$suma_total = 'CODIESEL VILLA DEL ROSARIO';
				$Repuestobocono = 'REPUESTOS DIESEL  BOCONO';
				$Repuestolymbocono = 'REPUESTOS LYP BOCONO';
				$totlaminabocono = 'TOT LYP BOCONO';
				$molaminabocono = 'MO LYP BOCONO';

				$valor_gasolina = $r_gasolina + $t_gasolina + $m_gasolina;
				$valor_diesel = $r_diesel + $t_diesel + $m_diesel;
				$valor_lamina = $r_lamina + $t_lamina + $m_lamina;
				$valor_total = $valor_gasolina + $valor_diesel + $r_mostrador + $valor_lamina;

				$arrayvaloresbocono = array(
					$m_gasolina, $t_gasolina, $r_gasolina, $m_diesel, $t_diesel, $r_diesel, $r_mostrador, $m_lamina, $t_lamina, $r_lamina, $valor_gasolina,
					$valor_diesel, $valor_lamina, $valor_total
				);
				$arraysedesbocono = array(
					$sede_m_gasolina . ' ' . $sede, $sede_t_gasolina . ' ' . $sede, $sede_r_gasolina . ' ' . $sede, $sede_m_diesel . ' ' . $sede, $sede_t_diesel . ' ' . $sede,
					$Repuestobocono, $sede . ' ' . $sede_r_mostrador, $molaminabocono, $totlaminabocono, $Repuestolymbocono,
					$taller_gasolina, $taller_diesel, $taller_lamina, $suma_total

				);

				for ($i = 0; $i < 14; $i++) {
					$this->presupuesto->insert_data_presupuesto($arrayvaloresbocono[$i], $arraysedesbocono[$i], $fecha_ini->fecha, $fecha_fin->fecha, $mes->mes);
				}
			} else if ($sede == 'CHEVROPARTES') {
				$nombre_sede = "CHEVROPARTES MOSTRADOR";
				$this->presupuesto->insert_data_presupuesto($r_mostrador, $nombre_sede, $fecha_ini->fecha, $fecha_fin->fecha, $mes->mes);
			} else if ($sede == 'ROSITA') {
				$sede_tot_rosita = "TOT ROSITA";
				$sede_mo_rosita = "MO ROSITA";
				$sede_repuesto_rosita = "REPUESTOS ROSITA";
				$sede_mostrador_respuesto_rosita = "ROSITA REPUESTOS MOSTRADOR";
				$repuesto_rosita = 'ROSITA CHEVY ESPRES';
				$codiesel_rosita = "CODIESEL LA ROSITA";

				$valor_repuesto_chevy = $r_gasolina + $t_gasolina + $m_gasolina;
				$valor_rosita = $valor_repuesto_chevy + $r_mostrador;

				$arrayvaloresrosita = array($t_gasolina, $m_gasolina, $r_gasolina, $r_mostrador, $valor_repuesto_chevy, $valor_rosita);
				$arraysedesrosita = array($sede_tot_rosita, $sede_mo_rosita, $sede_repuesto_rosita, $sede_mostrador_respuesto_rosita, $repuesto_rosita, $codiesel_rosita);

				for ($i = 0; $i < 6; $i++) {
					$this->presupuesto->insert_data_presupuesto($arrayvaloresrosita[$i], $arraysedesrosita[$i], $fecha_ini->fecha, $fecha_fin->fecha, $mes->mes);
				}
			} else if ($sede == 'SOLOCHEVROLET') {
				$nombre_sede = "SOLOCHEVROLET MOSTRADOR";
				$this->presupuesto->insert_data_presupuesto($r_mostrador, $nombre_sede, $fecha_ini->fecha, $fecha_fin->fecha, $mes->mes);
			}
		}

		$datos = $this->presupuesto->traer_data_presupuesto($fecha_ini->fecha, $fecha_fin->fecha);
		$result = array();
		foreach ($datos->result() as $key) {
			$result[] = $key->presupuesto;
		}

		if (count($result) == 6) {
			$datos = $this->presupuesto->traer_data_presupuesto($fecha_ini->fecha, $fecha_fin->fecha);
			$ventas_total = 0;
			foreach ($datos->result() as $valor) {
				$valor->presupuesto;
				$ventas_total = $ventas_total + $valor->presupuesto;
			}
			$nombre = 'CODIESEL';
			$datos = $this->presupuesto->insertar_Presupuesto_general($nombre, $ventas_total, $fecha_ini->fecha, $fecha_fin->fecha, $mes->mes);
		} else {
			echo "Faltan datos";
		}
	}

	/************************************************ P R E S U P U E S T O   R E P U E S T O S *********************************************/

	public function repuestos_total()
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
			$fecha_ini = $this->input->get('fec_ini');
			$fecha_fin = $this->input->get('fec_fin');
			if (isset($fecha_ini) && isset($fecha_fin)) {
				//obtenemos primer y ultimo dia del mes actual
				//traemos el presupuesto al dia de hoy
				$to_presupuesto_dia = $this->presupuesto->buscar_presupuesto_dia($fecha_ini, $fecha_fin);
				//traemos el presupuesto del mes de la db
				//echo $fecha_ini." - ".$fecha_fin;
				$to_presupuesto_mes = $this->presupuesto->buscar_presupuesto_mes($fecha_ini, $fecha_fin, "CODIESEL");

				$to_dia = 0;
				$to_t = 0;
				foreach ($to_presupuesto_mes->result() as $key) {
					$to_t = $key->presupuesto;
				}
				foreach ($to_presupuesto_dia->result() as $key) {
					$to_dia = $key->total;
				}

				//echo $to_t." -aa ".$to_dia;

				$porcentaje = ($to_dia * 100) / $to_t;
				$porcentaje_restante = (100 - $porcentaje);
				//traemos el presupuesto del mes de la db
				//$to_presupuesto_mes = $this->presupuesto->get_presupuesto_mes("2019-12-01","2019-12-31","CODIESEL");
				//traemos el total presupuesto por fecha de la db
				$to_presupuesto = $this->presupuesto->get_total_presupuesto();
				//damos formato al numero
				$total_format = number_format($to_presupuesto->total, 0, ",", ",");
				foreach ($to_presupuesto_mes->result() as $key) {
					echo '	
				<div class="col-md-12">
					<div class="info-box mb-3">
					  <span class="info-box-icon bg-warning elevation-1" style="font-size: 30px"><i class="fas fa-dollar-sign"></i></span>
					    <div class="info-box-content">
					                <span class="info-box-text" style="font-size: 20px">PRESUPUESTO DEL ' . $fecha_ini . ' AL ' . $fecha_fin . '</span>			               
					 <span class="info-box-number" style="font-size: 30px">' . number_format($to_dia, 0, ",", ",") . '</span>
					                
					   	 </div>
					     	<!-- /.info-box-content -->
						     	 </div>
				           	 	<div class="progress-group">
			                 	 Presupuesto a dia de hoy 
			                   	 <span class="float-right"><b>$' . number_format($to_dia, 0, ",", ",") . '</b>/$' . number_format($key->presupuesto, 0, ",", ",") . '</span>
			                      <div class="progress" style="height: 50px">
			                     <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width: ' . $porcentaje . '%; font-size: 25px">' . number_format($porcentaje, 0, ",", ",") . '%</div>
			                      <div class="progress-bar bg-danger" role="progressbar" style="width: ' . $porcentaje_restante . '%;font-size: 25px" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">' . number_format($porcentaje_restante, 0, ",", ",") . '%</div>
			                </div>
			           </div>
			           <!-- /.progress-group -->
			           <!-- /.progress-group -->
			         </div>
			                  <!-- /.col -->';
				}
			} else {
				//obtenemos primer y ultimo dia del mes actual
				$fecha_ini = $this->nominas->obtener_primer_dia_mes();
				$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
				//traemos el presupuesto al dia de hoy
				$to_presupuesto_dia = $this->presupuesto->get_presupuesto_dia();
				//traemos el presupuesto del mes de la db

				$to_presupuesto_mes = $this->presupuesto->get_presupuesto_mes($fecha_ini->fecha, $fecha_fin->fecha, "CODIESEL");

				$to_dia = 0;
				$to_t = 0;
				foreach ($to_presupuesto_mes->result() as $key) {
					$to_t = $key->presupuesto;
				}
				foreach ($to_presupuesto_dia->result() as $key) {
					$to_dia = $key->total;
				}

				$porcentaje = ($to_dia * 100) / $to_t;
				$porcentaje_restante = (100 - $porcentaje);
				//traemos el presupuesto del mes de la db
				//$to_presupuesto_mes = $this->presupuesto->get_presupuesto_mes("2019-12-01","2019-12-31","CODIESEL");
				//traemos el total presupuesto por fecha de la db
				$to_presupuesto = $this->presupuesto->get_total_presupuesto();
				//damos formato al numero
				$total_format = number_format($to_presupuesto->total, 0, ",", ",");

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
				//echo $id_usu;

				$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'to_presupuesto' => $total_format, 'presupuesto_mes' => $to_presupuesto_mes, 'porcentaje' => $porcentaje, 'valor_dia' => $to_dia, 'porcentaje_restante' => $porcentaje_restante);
				//abrimos la vista
				$this->load->view("presupuesto_repuestos_total", $arr_user);
			}
		}
	}

	public function presupuesto_historico()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('sedes');
			$this->load->model('presupuesto');

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
			/*SE CALCULA EL PRESUPUESTO POR SEDES*/
			$point1 = array();
			$m1 = array(1, 0, -1, -2, -3, -4, -5, -6, -7, -8, -9, -10, -11);
			$m2 = array(0, -1, -2, -3, -4, -5, -6, -7, -8, -9, -10, -11, -12);
			//total vendido
			for ($i = 0; $i < 13; $i++) {
				$db_data = $this->presupuesto->get_presupuesto_historico1($m1[$i], $m2[$i]);
				$valor = 0;
				foreach ($db_data->result() as $key) {
					if ($key->total < 0) {
						$valor = ($key->total * -1);
					} else {
						$valor = $key->total;
					}
					$point1[] = array('label' => $key->fecha, 'y' => $valor);
				}
			}
			//meta a cumplir
			$presupuesto_def = $this->presupuesto->get_presupuesto_def_hist();
			foreach ($presupuesto_def->result() as $key) {
				$point2_aux[] = array('label' => $key->fecha, 'y' => $key->presupuesto);
			}
			for ($i = 12; $i >= 0; $i--) {
				$point2[] = array('label' => $point2_aux[$i]['label'], 'y' => $point2_aux[$i]['y']);
			}
			//diferencia ventas menos presupuesto
			for ($i = 0; $i < 13; $i++) {
				$diferencia = 0;
				$diferencia =  $point1[$i]['y'] - $point2[$i]['y'];
				if ($diferencia >= 0) {
					$color = "#D0FFD3";
				} else {
					$color = "#FFD0D0";
				}
				$point3[] = array('y' => $diferencia, 'color' => $color);
			}
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'presupuesto_his' => $point1, 'point2' => $point2, 'point3' => $point3);
			//abrimos la vista
			$this->load->view("presupuesto_historico", $arr_user);
		}
	}

	public function Informe_tcm_historico()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('presupuesto');

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
			//Obtenemos historico taller

			//$m1 = array(1,0,-1,-2,-3,-4,-5,-6,-7,-8,-9,-10,-11);
			//$m2 = array(0,-1,-2,-3,-4,-5,-6,-7,-8,-9,-10,-11,-12);
			$m1 = array(-11, -10, -9, -8, -7, -6, -5, -4, -3, -2, -1, 0, 1);
			$m2 = array(-12, -11, -10, -9, -8, -7, -6, -5, -4, -3, -2, -1, 0);
			for ($i = 0; $i < 13; $i++) {
				$db_data = $this->presupuesto->get_presupuesto_tall($m1[$i], $m2[$i]);
				$valor = 0;
				foreach ($db_data->result() as $key) {
					if ($key->total < 0) {
						$valor = ($key->total * -1);
					} else {
						$valor = $key->total;
					}
					$point1[] = array('label' => $key->fecha, 'y' => $valor);
				}
			}
			for ($i = 0; $i < 13; $i++) {
				$db_data = $this->presupuesto->get_presupuesto_colicion($m1[$i], $m2[$i]);
				$valor = 0;
				foreach ($db_data->result() as $key) {
					if ($key->total < 0) {
						$valor = ($key->total * -1);
					} else {
						$valor = $key->total;
					}
					$point2[] = array('label' => $key->fecha, 'y' => $valor);
				}
			}
			for ($i = 0; $i < 13; $i++) {
				$db_data = $this->presupuesto->get_presupuesto_mostrador($m1[$i], $m2[$i]);
				$valor = 0;
				foreach ($db_data->result() as $key) {
					if ($key->total < 0) {
						$valor = ($key->total * -1);
					} else {
						$valor = $key->total;
					}
					$point3[] = array('label' => $key->fecha, 'y' => $valor);
				}
			}
			for ($i = 0; $i < 13; $i++) {
				$db_data = $this->presupuesto->get_presupuesto_otros($m1[$i], $m2[$i]);
				$valor = 0;
				foreach ($db_data->result() as $key) {
					if ($key->total < 0) {
						$valor = ($key->total * -1);
					} else {
						$valor = $key->total;
					}
					$point4[] = array('label' => $key->fecha, 'y' => $valor);
				}
			}
			//TOTAL TCM HISTORICO
			for ($i = 0; $i < count($point4); $i++) {
				$fecha = $point1[$i]['label'];
				$total = $point1[$i]['y'] + $point2[$i]['y'] + $point3[$i]['y'];
				$line_tcm[] = array('label' => $fecha, 'y' => $total);
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'point1' => $point1, 'point2' => $point2, 'point3' => $point3, 'point4' => $point4, 'line_tcm' => $line_tcm);
			//abrimos la vista
			$this->load->view("Informe_presupuesto_tcm_hist", $arr_user);
		}
	}

	public function Informe_presupuesto_tot()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('presupuesto');

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
			/*CENTRO DE COSTOS*/
			$centros_costos_giron = "4,40,33,45,3";
			$centros_costos_rosita = "16,17";
			$centros_costos_baranca = "13,70,11";
			$centros_costos_bocono = "29,80,31,46,28";
			$m1 = array(-11, -10, -9, -8, -7, -6, -5, -4, -3, -2, -1, 0, 1);
			$m2 = array(-12, -11, -10, -9, -8, -7, -6, -5, -4, -3, -2, -1, 0);
			//tot giron
			for ($i = 0; $i < 13; $i++) {
				$db_data = $this->presupuesto->get_presupuesto_tot_hist($m1[$i], $m2[$i], $centros_costos_giron);
				$valor = 0;
				foreach ($db_data->result() as $key) {
					if ($key->total < 0) {
						$valor = ($key->total * -1);
					} else {
						$valor = $key->total;
					}
					$point1[] = array('label' => $key->fecha, 'y' => $valor);
				}
			}
			//tot rosita
			for ($i = 0; $i < 13; $i++) {
				$db_data = $this->presupuesto->get_presupuesto_tot_hist($m1[$i], $m2[$i], $centros_costos_rosita);
				$valor = 0;
				foreach ($db_data->result() as $key) {
					if ($key->total < 0) {
						$valor = ($key->total * -1);
					} else {
						$valor = $key->total;
					}
					$point2[] = array('label' => $key->fecha, 'y' => $valor);
				}
			}
			//tot bocono
			for ($i = 0; $i < 13; $i++) {
				$db_data = $this->presupuesto->get_presupuesto_tot_hist($m1[$i], $m2[$i], $centros_costos_bocono);
				$valor = 0;
				foreach ($db_data->result() as $key) {
					if ($key->total < 0) {
						$valor = ($key->total * -1);
					} else {
						$valor = $key->total;
					}
					$point3[] = array('label' => $key->fecha, 'y' => $valor);
				}
			}
			//tot barranca
			for ($i = 0; $i < 13; $i++) {
				$db_data = $this->presupuesto->get_presupuesto_tot_hist($m1[$i], $m2[$i], $centros_costos_baranca);
				$valor = 0;
				foreach ($db_data->result() as $key) {
					if ($key->total < 0) {
						$valor = ($key->total * -1);
					} else {
						$valor = $key->total;
					}
					$point4[] = array('label' => $key->fecha, 'y' => $valor);
				}
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'point1' => $point1, 'point2' => $point2, 'point3' => $point3, 'point4' => $point4);
			//abrimos la vista
			$this->load->view("Informe_presupuesto_tot", $arr_user);
		}
	}

	public function Informe_presupuesto_mo()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('presupuesto');

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
			/*CENTRO DE COSTOS*/
			$centros_costos_giron = "4,40,33,45,3";
			$centros_costos_rosita = "16,17";
			$centros_costos_baranca = "13,70,11";
			$centros_costos_bocono = "29,80,31,46,28";
			$m1 = array(-11, -10, -9, -8, -7, -6, -5, -4, -3, -2, -1, 0, 1);
			$m2 = array(-12, -11, -10, -9, -8, -7, -6, -5, -4, -3, -2, -1, 0);
			//MO giron
			for ($i = 0; $i < 13; $i++) {
				$db_data = $this->presupuesto->get_presupuesto_mo_hist($m1[$i], $m2[$i], $centros_costos_giron);
				$valor = 0;
				foreach ($db_data->result() as $key) {
					if ($key->total < 0) {
						$valor = ($key->total * -1);
					} else {
						$valor = $key->total;
					}
					$point_aux_mo_g[] = array('label' => $key->fecha, 'y' => $valor);
				}
			}
			//MO rosita
			for ($i = 0; $i < 13; $i++) {
				$db_data = $this->presupuesto->get_presupuesto_mo_hist($m1[$i], $m2[$i], $centros_costos_rosita);
				$valor = 0;
				foreach ($db_data->result() as $key) {
					if ($key->total < 0) {
						$valor = ($key->total * -1);
					} else {
						$valor = $key->total;
					}
					$point_aux_mo_ro[] = array('label' => $key->fecha, 'y' => $valor);
				}
			}
			//MO bocono
			for ($i = 0; $i < 13; $i++) {
				$db_data = $this->presupuesto->get_presupuesto_mo_hist($m1[$i], $m2[$i], $centros_costos_bocono);
				$valor = 0;
				foreach ($db_data->result() as $key) {
					if ($key->total < 0) {
						$valor = ($key->total * -1);
					} else {
						$valor = $key->total;
					}
					$point_aux_mo_bo[] = array('label' => $key->fecha, 'y' => $valor);
				}
			}
			//MO barranca
			for ($i = 0; $i < 13; $i++) {
				$db_data = $this->presupuesto->get_presupuesto_mo_hist($m1[$i], $m2[$i], $centros_costos_baranca);
				$valor = 0;
				foreach ($db_data->result() as $key) {
					if ($key->total < 0) {
						$valor = ($key->total * -1);
					} else {
						$valor = $key->total;
					}
					$point_aux_mo_ba[] = array('label' => $key->fecha, 'y' => $valor);
				}
			}
			//tot giron
			for ($i = 0; $i < 13; $i++) {
				$db_data = $this->presupuesto->get_presupuesto_tot_hist($m1[$i], $m2[$i], $centros_costos_giron);
				$valor = 0;
				foreach ($db_data->result() as $key) {
					if ($key->total < 0) {
						$valor = ($key->total * -1);
					} else {
						$valor = $key->total;
					}
					$point_aux_tot_g[] = array('label' => $key->fecha, 'y' => $valor);
				}
			}
			//tot rosita
			for ($i = 0; $i < 13; $i++) {
				$db_data = $this->presupuesto->get_presupuesto_tot_hist($m1[$i], $m2[$i], $centros_costos_rosita);
				$valor = 0;
				foreach ($db_data->result() as $key) {
					if ($key->total < 0) {
						$valor = ($key->total * -1);
					} else {
						$valor = $key->total;
					}
					$point_aux_tot_ro[] = array('label' => $key->fecha, 'y' => $valor);
				}
			}
			//tot bocono
			for ($i = 0; $i < 13; $i++) {
				$db_data = $this->presupuesto->get_presupuesto_tot_hist($m1[$i], $m2[$i], $centros_costos_bocono);
				$valor = 0;
				foreach ($db_data->result() as $key) {
					if ($key->total < 0) {
						$valor = ($key->total * -1);
					} else {
						$valor = $key->total;
					}
					$point_aux_tot_bo[] = array('label' => $key->fecha, 'y' => $valor);
				}
			}
			//tot barranca
			for ($i = 0; $i < 13; $i++) {
				$db_data = $this->presupuesto->get_presupuesto_tot_hist($m1[$i], $m2[$i], $centros_costos_baranca);
				$valor = 0;
				foreach ($db_data->result() as $key) {
					if ($key->total < 0) {
						$valor = ($key->total * -1);
					} else {
						$valor = $key->total;
					}
					$point_aux_tot_ba[] = array('label' => $key->fecha, 'y' => $valor);
				}
			}
			//MO giron
			for ($i = 0; $i < 13; $i++) {
				$valor = $point_aux_mo_g[$i]['y'] - $point_aux_tot_g[$i]['y'];
				$fecha = $point_aux_tot_g[$i]['label'];
				$point1[] = array('label' => $fecha, 'y' => $valor);
			}
			//MO rosita
			for ($i = 0; $i < 13; $i++) {
				$valor = $point_aux_mo_ro[$i]['y'] - $point_aux_tot_ro[$i]['y'];
				$fecha = $point_aux_tot_ro[$i]['label'];
				$point2[] = array('label' => $fecha, 'y' => $valor);
			}
			//MO bocono
			for ($i = 0; $i < 13; $i++) {
				$valor = $point_aux_mo_bo[$i]['y'] - $point_aux_tot_bo[$i]['y'];
				$fecha = $point_aux_tot_bo[$i]['label'];
				$point3[] = array('label' => $fecha, 'y' => $valor);
			}
			//MO barranca
			for ($i = 0; $i < 13; $i++) {
				$valor = $point_aux_mo_ba[$i]['y'] - $point_aux_tot_ba[$i]['y'];
				$fecha = $point_aux_tot_ba[$i]['label'];
				$point4[] = array('label' => $fecha, 'y' => $valor);
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'point1' => $point1, 'point2' => $point2, 'point3' => $point3, 'point4' => $point4);
			//abrimos la vista
			$this->load->view("Informe_presupuesto_mo", $arr_user);
		}
	}

	public function Informe_historico_mo_sedes()
	{
		$this->load->model('presupuesto');
		$m1 = array(-11, -10, -9, -8, -7, -6, -5, -4, -3, -2, -1, 0, 1);
		$m2 = array(-12, -11, -10, -9, -8, -7, -6, -5, -4, -3, -2, -1, 0);
		/*GIRON*/
		$cc_tall_giron_gas = "4";
		$cc_tall_giron_dies = "40";
		$cc_tall_giron_col = "33,45";
		/*ROSITA*/
		$cc_tall_rosita = "16";
		/*BARRANCA*/
		$cc_tall_barranca_gas = "13";
		$cc_tall_barranca_dies = "70";
		/*BOCONO*/
		$cc_tall_bocono_gas = "29";
		$cc_tall_bocono_dies = "80";
		$cc_tall_bocono_col = "31,46";
		//CALCULO PARA GIRON
		//MO giron GASOLINA
		for ($i = 0; $i < 13; $i++) {
			$db_data = $this->presupuesto->get_presupuesto_mo_hist($m1[$i], $m2[$i], $cc_tall_giron_gas);
			$valor = 0;
			foreach ($db_data->result() as $key) {
				if ($key->total < 0) {
					$valor = ($key->total * -1);
				} else {
					$valor = $key->total;
				}
				$point_aux_mo_g_gas[] = array('label' => $key->fecha, 'y' => $valor);
			}
		}
		//MO DIESEL
		for ($i = 0; $i < 13; $i++) {
			$db_data = $this->presupuesto->get_presupuesto_mo_hist($m1[$i], $m2[$i], $cc_tall_giron_dies);
			$valor = 0;
			foreach ($db_data->result() as $key) {
				if ($key->total < 0) {
					$valor = ($key->total * -1);
				} else {
					$valor = $key->total;
				}
				$point_aux_mo_g_die[] = array('label' => $key->fecha, 'y' => $valor);
			}
		}
		//MO COLISION
		for ($i = 0; $i < 13; $i++) {
			$db_data = $this->presupuesto->get_presupuesto_mo_hist($m1[$i], $m2[$i], $cc_tall_giron_col);
			$valor = 0;
			foreach ($db_data->result() as $key) {
				if ($key->total < 0) {
					$valor = ($key->total * -1);
				} else {
					$valor = $key->total;
				}
				$point_aux_mo_g_col[] = array('label' => $key->fecha, 'y' => $valor);
			}
		}
		//TOT GIRON
		//tot GASOLINA
		for ($i = 0; $i < 13; $i++) {
			$db_data = $this->presupuesto->get_presupuesto_tot_hist($m1[$i], $m2[$i], $cc_tall_giron_gas);
			$valor = 0;
			foreach ($db_data->result() as $key) {
				if ($key->total < 0) {
					$valor = ($key->total * -1);
				} else {
					$valor = $key->total;
				}
				$point_aux_tot_g_gas[] = array('label' => $key->fecha, 'y' => $valor);
			}
		}
		//tot DIESEL
		for ($i = 0; $i < 13; $i++) {
			$db_data = $this->presupuesto->get_presupuesto_tot_hist($m1[$i], $m2[$i], $cc_tall_giron_dies);
			$valor = 0;
			foreach ($db_data->result() as $key) {
				if ($key->total < 0) {
					$valor = ($key->total * -1);
				} else {
					$valor = $key->total;
				}
				$point_aux_tot_g_die[] = array('label' => $key->fecha, 'y' => $valor);
			}
		}
		//tot COLISION
		for ($i = 0; $i < 13; $i++) {
			$db_data = $this->presupuesto->get_presupuesto_tot_hist($m1[$i], $m2[$i], $cc_tall_giron_col);
			$valor = 0;
			foreach ($db_data->result() as $key) {
				if ($key->total < 0) {
					$valor = ($key->total * -1);
				} else {
					$valor = $key->total;
				}
				$point_aux_tot_g_col[] = array('label' => $key->fecha, 'y' => $valor);
			}
		}
		//MO CALCULO GASOLINA
		for ($i = 0; $i < 13; $i++) {
			$valor = $point_aux_mo_g_gas[$i]['y'] - $point_aux_tot_g_gas[$i]['y'];
			$fecha = $point_aux_mo_g_gas[$i]['label'];
			$point1_g[] = array('label' => $fecha, 'y' => $valor);
		}
		//MO CALCULO DIESEL
		for ($i = 0; $i < 13; $i++) {
			$valor = $point_aux_mo_g_die[$i]['y'] - $point_aux_tot_g_die[$i]['y'];
			$fecha = $point_aux_mo_g_die[$i]['label'];
			$point_g[] = array('label' => $fecha, 'y' => $valor);
		}
		//MO CALCULO COLISION
		for ($i = 0; $i < 13; $i++) {
			$valor = $point_aux_mo_g_col[$i]['y'] - $point_aux_tot_g_col[$i]['y'];
			$fecha = $point_aux_mo_g_col[$i]['label'];
			$point3_g[] = array('label' => $fecha, 'y' => $valor);
		}
		echo json_encode($point1_g);
		/**echo '<div class="row">
			    	<div class="col-md-6">
			    		<div id="graf_giron" style="height: 370px; width: 100%;"></div>
			    	</div>
			    	<div class="col-md-6">
			    		<div id="graf_rosita" style="height: 370px; width: 100%;"></div>
			    	</div>
			    </div>
			    <div class="row">
			    	<div class="col-md-6">
			    		<div id="graf_bocono" style="height: 370px; width: 100%;"></div>
			    	</div>
			    	<div class="col-md-6">
			    		<div id="graf_barranca" style="height: 370px; width: 100%;"></div>
			    	</div>
			    </div>';*/
	}
}
