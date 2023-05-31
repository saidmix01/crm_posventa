<?php 

/**
 * 
 */
class Tecnicos extends CI_Controller
{
	
	public function valor_vendido_tec()
	{
		$this->load->model('talleres');
		$this->load->model('Informe');
		//OBTENER FECHA
		$date = $this->Informe->get_mes_ano_actual();
		$mes = $date->mes;
		$ano = $date->ano;
		//TOTAL VENDIDO
		$total_v = 0;
		$usu = $this->session->userdata('user');
		$data_ventas_vendedor = $this->talleres->get_ventas_tec($usu,$mes,$ano);
		foreach ($data_ventas_vendedor->result() as $key) {
			$total_v = $key->rptos + $key->MO;
		}
		echo "$".number_format($total_v,0,",",",");
	}

	public function horas_fac_tec()
	{
		$this->load->model('talleres');
		$this->load->model('Informe');
		//OBTENER FECHA
		$date = $this->Informe->get_mes_ano_actual();
		$mes = $date->mes;
		$ano = $date->ano;
		//TOTAL VENDIDO
		$horas_fac = 0;
		$usu = $this->session->userdata('user');
		$data_ventas_vendedor = $this->talleres->get_ventas_tec($usu,$mes,$ano);
		foreach ($data_ventas_vendedor->result() as $key) {
			$horas_fac = $key->horas_facturadas;
		}
		echo $horas_fac;
	}

	public function nps_interno()
	{
		$this->load->model('Informe');
		$this->load->model('Informe');
		//OBTENER FECHA
		$date = $this->Informe->get_mes_ano_actual();
		$mes = $date->mes;
		$ano = $date->ano;
		//NPS interno
		$usu = $this->session->userdata('user');
		$data_nps_tec = $this->Informe->get_nps_by_tec($usu,$mes,$ano);
		$nps_int = 0;
		$to_enc = 0;
		foreach ($data_nps_tec->result() as $key) {
			$to_enc = $key->enc9a10 + $key->enc0a6 + $key->enc7a8;
			$nps_int = (($key->enc9a10 - $key->enc0a6) / $to_enc) * 100;
		}
		echo round($nps_int)."%";
	}

	public function calficacion_tec_colmotores()
	{
		$this->load->model('encuestas');
		$this->load->model('Informe');
		//OBTENER FECHA
		$date = $this->Informe->get_mes_ano_actual();
		$mes = $date->mes;
		$ano = $date->ano;
		//CALIFICACION COLMOTORES
		$usu = $this->session->userdata('user');
		$tecnicos = $this->Informe->get_data_nps_by_tec($usu,$mes,$ano);
		$nps = 0;
		foreach ($tecnicos->result() as $key) {
			$total_encu = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
			$nps = (($key->enc9a10 - $key->enc0a6) / $total_encu) * 100;
		}
		echo $nps." %";
	}

	public function ventas_tec_detalle()
	{
		$this->load->model('talleres');
		$this->load->model('Informe');
		//OBTENER FECHA
		$date = $this->Informe->get_mes_ano_actual();
		$mes = $date->mes;
		$ano = $date->ano;
		$usu = $this->session->userdata('user');
		$data_ventas_vendedor = $this->talleres->get_ventas_tec_detalle($usu,$mes,$ano);
		foreach ($data_ventas_vendedor->result() as $key) {
			echo '<tr>
			<td><a href="pages/examples/invoice.html">'.$key->numero_orden.'</a></td>
			<td>'.$key->cliente.'</td>
			<td>$'.number_format($key->rptos,0,",",",").'</td>
			<td>$'.number_format($key->MO,0,",",",").'</td>
			<td>'.$key->horas_facturadas.' horas</td>
			</tr>';
		}
		
	}

	public function valor_vendido_tec_buscar()
	{
		$this->load->model('talleres');
		$this->load->model('Informe');
		//OBTENER FECHA
		$fecha = $this->input->GET('fecha');
		$data_fecha = explode("-", $fecha);
		$mes = $data_fecha[1];
		$ano = $data_fecha[0];
		//TOTAL VENDIDO
		$total_v = 0;
		$usu = $this->session->userdata('user');
		$data_ventas_vendedor = $this->talleres->get_ventas_tec($usu,$mes,$ano);
		foreach ($data_ventas_vendedor->result() as $key) {
			$total_v = $key->rptos + $key->MO;
		}
		echo "$".number_format($total_v,0,",",",");
	}

	public function valor_vendido_rep_buscar()
	{
		$this->load->model('talleres');
		$this->load->model('Informe');
		//OBTENER FECHA
		$fecha = $this->input->GET('fecha');
		$data_fecha = explode("-", $fecha);
		$mes = $data_fecha[1];
		$ano = $data_fecha[0];
		//TOTAL VENDIDO
		$total_v = 0;
		$usu = $this->session->userdata('user');
		$data_ventas_vendedor = $this->talleres->get_ventas_tec($usu,$mes,$ano);
		foreach ($data_ventas_vendedor->result() as $key) {
			$total_v = $key->rptos;
		}
		echo "$".number_format($total_v,0,",",",");
	}

	public function valor_vendido_mo_buscar()
	{
		$this->load->model('talleres');
		$this->load->model('Informe');
		//OBTENER FECHA
		$fecha = $this->input->GET('fecha');
		$data_fecha = explode("-", $fecha);
		$mes = $data_fecha[1];
		$ano = $data_fecha[0];
		//TOTAL VENDIDO
		$total_v = 0;
		$usu = $this->session->userdata('user');
		$data_ventas_vendedor = $this->talleres->get_ventas_tec($usu,$mes,$ano);
		foreach ($data_ventas_vendedor->result() as $key) {
			$total_v = $key->MO;
		}
		echo "$".number_format($total_v,0,",",",");
	}

	public function horas_fac_tec_buscar()
	{
		$this->load->model('talleres');
		$this->load->model('Informe');
		//OBTENER FECHA
		$fecha = $this->input->GET('fecha');
		$data_fecha = explode("-", $fecha);
		$mes = $data_fecha[1];
		$ano = $data_fecha[0];
		//TOTAL VENDIDO
		$horas_fac = 0;
		$usu = $this->session->userdata('user');
		$data_ventas_vendedor = $this->talleres->get_ventas_tec($usu,$mes,$ano);
		foreach ($data_ventas_vendedor->result() as $key) {
			$horas_fac = $key->horas_facturadas;
		}
		echo $horas_fac;
	}

	public function nps_interno_buscar()
	{
		$this->load->model('Informe');
		$this->load->model('Informe');
		//OBTENER FECHA
		$fecha = $this->input->GET('fecha');
		$data_fecha = explode("-", $fecha);
		$mes = $data_fecha[1];
		$ano = $data_fecha[0];
		//NPS interno
		$usu = $this->session->userdata('user');
		$data_nps_tec = $this->Informe->get_nps_by_tec_buscar($usu,$mes,$ano);
		$nps_int = 0;
		$to_enc = 0;
		foreach ($data_nps_tec->result() as $key) {
			$to_enc = $key->enc9a10 + $key->enc0a6 + $key->enc7a8;
			$nps_int = (($key->enc9a10 - $key->enc0a6) / $to_enc) * 100;
		}
		echo round($nps_int)."%";
	}

	public function calficacion_tec_colmotores_buscar()
	{
		$this->load->model('encuestas');
		$this->load->model('Informe');
		//OBTENER FECHA
		$fecha = $this->input->GET('fecha');
		$data_fecha = explode("-", $fecha);
		$mes = $data_fecha[1];
		$ano = $data_fecha[0];
		//CALIFICACION COLMOTORES
		$usu = $this->session->userdata('user');
		$tecnicos = $this->Informe->get_data_nps_by_tec_buscar($usu,$mes,$ano);
		$nps = 0;
		foreach ($tecnicos->result() as $key) {
			$total_encu = $key->enc0a6 + $key->enc7a8 + $key->enc9a10;
			$nps = (($key->enc9a10 - $key->enc0a6) / $total_encu) * 100;
		}
		echo $nps." %";
	}

	public function ventas_tec_detalle_buscar()
	{
		$this->load->model('talleres');
		$this->load->model('Informe');
		//OBTENER FECHA
		$fecha = $this->input->GET('fecha');
		$data_fecha = explode("-", $fecha);
		$mes = $data_fecha[1];
		$ano = $data_fecha[0];
		$usu = $this->session->userdata('user');
		$data_ventas_vendedor = $this->talleres->get_ventas_tec_detalle($usu,$mes,$ano);
		foreach ($data_ventas_vendedor->result() as $key) {
			echo '<tr>
			<td><a href="pages/examples/invoice.html">'.$key->numero_orden.'</a></td>
			<td>'.$key->cliente.'</td>
			<td>$'.number_format($key->rptos,0,",",",").'</td>
			<td>$'.number_format($key->MO,0,",",",").'</td>
			<td>'.$key->horas_facturadas.' horas</td>
			</tr>';
		}
		
	}

	public function semaforo(){
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('mantenimiento_uno');


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
			$datos = $this->mantenimiento_uno->ver_equipos();
			$dto = $this->mantenimiento_uno->ver_id();
			$bodegas =  $this->mantenimiento_uno->TarerBodegas();
			$jefes = $this->mantenimiento_uno->traer_jefes();
			$personalMantenimiento = $this->mantenimiento_uno->getPersonalMantenimiento();
			$idFake = "";
			$equipos_f = $this->mantenimiento_uno->equipo_nombre_familia($idFake);
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array(
				'pMantenimiento' => $personalMantenimiento, 'equiposF' => $equipos_f, 'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu,
				'data_tabla' => $datos, 'dato' => $dto, 'bodegas' => $bodegas, 'nit' => $usu, 'jefes' => $jefes
			);
			//abrimos la vista
			$this->load->view("tecnicos/semaforo", $arr_user);
	}
}

}
