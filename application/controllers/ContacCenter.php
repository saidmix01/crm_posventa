<?php 	

/**
 * Controlador del modulo contac center
 */
class ContacCenter extends CI_Controller
{
	
	public function distribucion()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Contac_Center');
			
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
			/*Obtener agentes del contaccenter*/
			$agentes = $this->Contac_Center->get_agentes();
			/*Obtener las bodegas*/
			$bodegas = $this->Contac_Center->get_bodegas();
			
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu,'agentes'=>$agentes,'bodegas'=>$bodegas);
			//abrimos la vista
			$this->load->view("distribucion_cc",$arr_user);
		}
	}

	public function distribucion_agente()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Contac_Center');
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
			$ga_actuales = $this->Contac_Center->get_ga_actuales($usu);
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu,'ga_actuales'=>$ga_actuales);
			//abrimos la vista
			$this->load->view("distribucion_agente_cc",$arr_user);
		}
	}

	public function insert_distribucion()
	{
		$this->load->model('Contac_Center');
		$fecha = $this->Contac_Center->get_mes_anio();
		$agente = $this->input->GET('agente');
		$bodega = $this->input->GET('sede');
		$data = array('bodega'=>$bodega,'agente' => $agente,'distribucion'=>0,'mes'=>$fecha->mes,'anio'=>$fecha->anio);
		if ($this->Contac_Center->insert_distribucion($data)) {
			echo "ok";
		}else{
			echo "err";
		}
	}

	public function update_distribucion()
	{
		$this->load->model('Contac_Center');
		$fecha = $this->Contac_Center->get_mes_anio();
		$agente = $this->input->GET('agente');
		$bodega = $this->input->GET('sede');
		$dist = $this->input->GET('dist');
		$where = array('bodega'=>$bodega,'agente' => $agente,'mes'=>$fecha->mes,'anio'=>$fecha->anio);
		$data = array('distribucion'=>$dist);
		$sum_dist = $this->Contac_Center->validar_suma_distribucion($bodega)->dist_sede + $dist;

		/*LOGIGA DE DISTRIBUCION*/
		/*$arr_data_dis = array('mes'=>$fecha->mes,'anio'=>$fecha->anio,'bodega'=>$bodega);
		$data_db = $this->Contac_Center->get_total_db_distri($arr_data_dis);
		$tota_reg = $data_db->n;
		$reg = ($tota_reg * $dist) / 100;
		$data_reg = array('bodega'=>$bodega,'reg'=>round($reg));
		$list_reg_data = $this->Contac_Center->get_reg_dist($data_reg);
		foreach ($list_reg_data->result() as $key) {
			$this->Contac_Center->update_maestro_posventa($agente,$key->id_mp);
		}*/
		if ($sum_dist > 100) {
			echo "err_sum";
		}else{
			if ($this->Contac_Center->update_distribucion($data,$where)) {
				echo "ok";
			}else{
				echo "err";
			}
		}
		
	}

	public function delete_distribucion()
	{
		$this->load->model('Contac_Center');
		$fecha = $this->Contac_Center->get_mes_anio();
		$agente = $this->input->GET('agente');
		$bodega = $this->input->GET('sede');
		$data = array('bodega'=>$bodega,'agente' => $agente,'mes'=>$fecha->mes,'anio'=>$fecha->anio);
		if ($this->Contac_Center->detele_distribucion($data)) {
			echo "ok";
		}else{
			echo "err";
		}
	}

	public function validar_agente()
	{
		$this->load->model('Contac_Center');
		$fecha = $this->Contac_Center->get_mes_anio();
		$agente = $this->input->GET('agente');
		$bodega = $this->input->GET('sede');
		$data = array('bodega'=>$bodega,'agente' => $agente,'mes'=>$fecha->mes,'anio'=>$fecha->anio);
		$val_agente = $this->Contac_Center->validar_agente($data);
		if ($val_agente->n == 1) {
			echo 1;
		}else{
			echo 0;
		}
	}

	public function cargar_totales()
	{
		$this->load->model('Contac_Center');
		$data = $this->Contac_Center->cargar_totales();
		foreach ($data->result() as $key) {
			echo '
			<td>'.$key->dist_sede.'</td>
			';
		}
	}

	public function change_estado()
	{
		$this->load->model('Contac_Center');
		$user=$this->session->userdata('user');
		$estado=$this->input->GET('estado');
		if ($this->Contac_Center->validar_estado($user)->n != 0) {
			$data = array('estado' => $estado);
			if ($this->Contac_Center->update_estado($user,$data)) {
				echo "ok";
			}else{
				echo "err";
			}
		}else{
			$data = array('agente' => $user,'estado' => $estado);
			if ($this->Contac_Center->insert_estado($data)) {
				echo "ok";
			}else{
				echo "err";
			}
		}
	}
}


?>