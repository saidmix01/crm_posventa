<?php 

/**
 * 
 */
class Garantias extends CI_Controller
{
	public function ingresar_rel_envio_garantias()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
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

			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//echo $id_usu;

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu);
			//abrimos la vista
			$this->load->view('rel_envio_garantias',$arr_user);
		}
	}

	public function insert_rel_envio_garantias()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//llamamos los modelos necesarios
			$this->load->model('garantia');
			$transaccion = $this->input->POST('transaccion');
			$guia = $this->input->POST('guia');
			$fecha = $this->input->POST('fecha');
			$orden = $this->input->POST('orden');
			//print_r($transaccion);
			if ($this->garantia->insert_rel_env_garantias($transaccion,$guia,$fecha,$orden)) {
				header("Location: " . base_url() . "garantias/ingresar_rel_envio_garantias?log=ok");
			}else{
				header("Location: " . base_url() . "garantias/ingresar_rel_envio_garantias?log=err");
			}
		}
	}
}

 ?>