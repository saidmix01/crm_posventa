<?php 
/*
 *
 * 
 */
class Admin extends CI_Controller
{
	/* Metodo que muestra los permisos de acceso a los menus del sistema va unido a la vista permisos_menu */
	public function listar_permisos_menus()
	{
		/* validamos si hay datos de session 
		 * si no hay se redireccion al login y se destruye la session
		 */
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//obtenemos el id del perfil que va por la url
			$id_perfil = $this->input->GET('id');
			//llamamos los modelos
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('permisos');
			//obtenemos el usuario en session
			$usu = $this->session->userdata('user');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus_user = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			$allmenus = $this->menus->getAllMenu();
			$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			
			//Creamos dos arrays para mostrar la tabla de permisos de acceso
			$data_table = array();
			$array1 = array(); 
			foreach ($allmenus->result() as $key) {
				$menu = $key->menu;
				$id_menu = $key->id_menu;
				$permisos_perfil = $this->permisos->validarPermisosUser($id_perfil,$id_menu);				
				$estado = '';
				if($permisos_perfil->numero == 1){
					$estado = "Activo";
				}else if($permisos_perfil->numero == 0){
					$estado = "Inactivo";
				}
				$array1 []= array('id_menu' => $id_menu,'menu' => $menu, 'estado' => $estado, 'perfil'=>$id_perfil);  
				$data_table = $array1;	
			}
			/* Validamos que tenga permisos el perfil para acceder a la vista*/
			$perfil = $this->session->userdata('perfil');
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			$validar_perfil = $this->menus->validarPermisosSubmenus('admin/permisos',$perfil);
			if ($validar_perfil->n == 0) {
				$this->session->sess_destroy();
				header("Location: " . base_url());
			}else{
				//Obtenemos el perfil por el id
				$perfil = $this->perfiles->getPerfilById($id_perfil);
				//llenamos el array para mandarlo a la vista
				$arr_user = array('userdata' => $userinfo, 'menus_user' => $allmenus_user, 'submenus' => $allsubmenus, 'perfiles' => $allperfiles, 'allmenus' => $data_table, 'nombreperfil' => $perfil->nom_perfil, 'menus' => $array1);
				//cargamos la vista
				$this->load->view('permisos_menus',$arr_user);
			}
		}
		
	}

 
	//crear permisos para acceder a los menus
	public function crear_permisos_menus()
	{	
		/* validamos si hay datos de session 
		 * si no hay se redireccion al login y se destruye la session
		 */
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//se llama el modelo
			$this->load->model('menus');
			//se obtiene el id del menu por el nombre
			$id_menu = $this->menus->getIdMenuByMenu($this->input->post('menu'));

			$id_perfil = $this->input->post('perfil');
			//se obtiene los valores que vienen de la tabla y se guarda en un array
			$registros = array('menu' => $id_menu->id_menu, 'perfil' => $id_perfil);
			$bool = $this->menus->insertMenuPerfil($registros);
			if($bool){
				header("Location: " . base_url() . "admin/listar_permisos_menus?id=".$id_perfil."&log=ok");
			}else{
				header("Location: " . base_url() . "admin/listar_permisos_menus?id=".$id_perfil."&log=err");
			}
		}
	}
	//eliminar permisos para acceder a los menus
	public function delete_permisos_menu()
	{
		/* validamos si hay datos de session 
		 * si no hay se redireccion al login y se destruye la session
		 */
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//se llama el modelo
			$this->load->model('menus');
			//se obtiene el id del menu por el nombre
			$id_menu = $this->menus->getIdMenuByMenu($this->input->post('menu'));
			$id_perfil = $this->input->post('perfil');
			if($this->menus->deleteMenuPerfil($id_perfil,$id_menu->id_menu)){
				//echo "bien";
				header("Location: " . base_url() . "admin/listar_permisos_menus?id=".$id_perfil."&log=ok");
			}else{
				//echo "mal";
				header("Location: " . base_url() . "admin/listar_permisos_menus?id=".$id_perfil."&log=err");
			}
		}	
	}

	//crear permisos para acceder a los submenus
	public function crear_permisos_submenus()
	{	
		/* validamos si hay datos de session 
		 * si no hay se redireccion al login y se destruye la session
		 */
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//se llama el modelo
			$this->load->model('menus');
			//se obtiene el id del menu por el nombre
			$id_submenu = $this->input->post('submenu');

			$id_perfil = $this->input->post('perfil');

			$id_menu = $this->input->post('menu');

			//se obtiene los valores que vienen de la tabla y se guarda en un array
			$registros = array('submenu' => $id_submenu, 'perfil' => $id_perfil);
			$bool = $this->menus->insertSubMenuPerfil($registros);
			if($bool){
				header("Location: " . base_url() . "admin/listar_permisos_submenus?menu=".$id_menu."&perfil=".$id_perfil."&log=ok");
			}else{
				header("Location: " . base_url() . "admin/listar_permisos_submenus?menu=".$id_menu."&perfil=".$id_perfil."&log=err");
			}
		}
	}
	//eliminar permisos para acceder a los submenus
	public function delete_permisos_submenu()
	{
		/* validamos si hay datos de session 
		 * si no hay se redireccion al login y se destruye la session
		 */
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//se llama el modelo
			$this->load->model('menus');
			//se obtiene el id del menu por el nombre
			$id_submenu = $this->input->post('submenu');
			$id_perfil = $this->input->post('perfil');
			$id_menu = $this->input->post('menu');
			if($this->menus->deleteSubMenuPerfil($id_perfil,$id_submenu)){
				//echo "bien";
				header("Location: " . base_url() . "admin/listar_permisos_submenus?menu=".$id_menu."&perfil=".$id_perfil."&log=ok");
			}else{
				//echo "mal";
				header("Location: " . base_url() . "admin/listar_permisos_submenus?menu=".$id_menu."&perfil=".$id_perfil."&log=err");
			}
		}
	}

	public function listar_permisos_submenus()
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
			$this->load->model('permisos');

			$id_menu = $this->input->get('menu');
			$id_perfil = $this->input->get('perfil');
			//obtenemos el usuario en session
			$usu = $this->session->userdata('user');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus_user = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			$allsubmenus = $this->menus->getAllSubmenusById($id_menu);
			$allperfiles = $this->perfiles->getAllPerfiles();
			//Creamos dos arrays para mostrar la tabla de permisos de acceso
			$data_table = array();
			$array1 = array(); 
			foreach ($allsubmenus->result() as $key) {
				$submenu = $key->submenu;
				$id_submenu = $key->id_submenu;
				$permisos_perfil = $this->permisos->validarPermisosSubmenu($id_perfil,$id_submenu);
				$estado = '';
				if($permisos_perfil->numero == 1){
					$estado = "Activo";
				}else if($permisos_perfil->numero == 0){
					$estado = "Inactivo";
				}
				$array1 []= array('id_menu' => $id_menu,'id_submenu' => $id_submenu,'submenu' => $submenu, 'estado' => $estado, 'perfil'=>$id_perfil);  
				$data_table = $array1;	
			}

			/* Validamos que tenga permisos el perfil para acceder a la vista*/
			$perfil = $this->session->userdata('perfil');
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			$validar_perfil = $this->menus->validarPermisosSubmenus('admin/permisos',$perfil);
			if ($validar_perfil->n == 0) {
				$this->session->sess_destroy();
				header("Location: " . base_url());
			}else{
				//Obtenemos el perfil por el id
				$perfil = $this->perfiles->getPerfilById($id_perfil);
				$arr_user = array('userdata' => $userinfo, 'menus_user' => $allmenus_user, 'perfiles' => $allperfiles, 'nombreperfil' => $perfil->nom_perfil,'submenus' => $array1, 'id_perfil' => $id_perfil);
				//cargamos la vista
				$this->load->view('permisos_submenus',$arr_user);
			}
		}
	}
 
	public function permisos()
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
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//print_r($allperfiles->result());die;
			//prueba de menu
			/* Validamos que tenga permisos el perfil para acceder a la vista*/
			$perfil = $this->session->userdata('perfil');
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			$validar_perfil = $this->menus->validarPermisosSubmenus('usuarios_admin',$perfil);
			if ($validar_perfil->n == 0) {
				$this->session->sess_destroy();
				header("Location: " . base_url());
			}else{
				$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles);
				//abrimos la vista
				$this->load->view('perfiles',$arr_user);
			}
		}
			
	}	
}


 ?>