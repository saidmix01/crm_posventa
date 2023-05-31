<?php 
/*
 *
 * 
 */
class Perfiles_admin extends CI_Controller
{
	/*Metodo que carga la interfaz de la vista perfiles_admin*/
	public function index()
	{
		/* validamos si hay datos de session 
		 * si no hay se redireccion al login y se destruye la session
		 */
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{

			//llamamos los modelos
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//prueba de menu
			/* Validamos que tenga permisos el perfil para acceder a la vista*/
			$perfil = $this->session->userdata('perfil');
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			$validar_perfil = $this->menus->validarPermisosSubmenus('perfiles_admin',$perfil);
			if ($validar_perfil->n == 0) {
				$this->session->sess_destroy();
				header("Location: " . base_url());
			}else{
				$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles);
				//abrimos la vista
				$this->load->view('Perfiles_admin',$arr_user);
			}
		}
	}

	/*Metodo que crea los perfiles*/
	public function crear_pefil()
	{
		/* validamos si hay datos de session 
		 * si no hay se redireccion al login y se destruye la session
		 */
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//llamamos los modelos
			$this->load->model('perfiles');
			//traemos los datos del formulario
			$registros = $this->input->post();
			//creamos y validamos
			if($this->perfiles->insert($registros)){
				header("Location: " . base_url() . "perfiles_admin?log=ok");
			}else{
				header("Location: " . base_url() . "perfiles_admin?log=err");
			}
		}
	}

	/*Metodo que elimina los perfiles*/
	public function delete()
	{
		/* validamos si hay datos de session 
		 * si no hay se redireccion al login y se destruye la session
		 */
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//traemos el id_perfil 
			$id_perfil = $this->input->get('id_perfil');
			//llamamos los modelos
			$this->load->model('perfiles');
			//eliminamos y validamos
			if($this->perfiles->delete($id_perfil)){
				header("Location: " . base_url() . "perfiles_admin?log=ok");
			}else{
				header("Location: " . base_url() . "perfiles_admin?log=err");
			}
		}
	}	

	/*Metodo que carga la informacion para ejecutar el metodo actualizar*/
	public function info_update()
	{
		/* validamos si hay datos de session 
		 * si no hay se redireccion al login y se destruye la session
		 */
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//obtenemos el id de la url
			$id_perfil = $this->input->GET('idp');
			//llamamos los modelos requeridos
			$this->load->model('perfiles');
			$data = $this->perfiles->getPerfilById($id_perfil);
			echo '<input type="text" name="nombre_perfil" required="true" value="'.$data->perfil.'" placeholder="Escriba algo" class="form-control">';
			echo '<input type="hidden" name="id_perfil" value="'.$data->id_perfil.'">';
		}	
	}

	/*Metodo que actualiza el perfil*/
	public function actualizar_perfil()
	{
		/* validamos si hay datos de session 
		 * si no hay se redireccion al login y se destruye la session
		 */
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//traemos el id del perfil
			$id_perfil = $this->input->post('id_perfil');
			$nombre_perfil = $this->input->post('nombre_perfil');
			//llamamos los modelos
			$this->load->model('perfiles');
			$this->perfiles->update($id_perfil,$nombre_perfil);
			header("Location: " . base_url() . "perfiles_admin?log=ok");
		}
	}

}

?>