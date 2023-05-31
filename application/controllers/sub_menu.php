<?php
/**
 * esta clase es el controlador de SUB MENU creador por Cristian
 */
class Sub_menu extends CI_Controller{

    public function index()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
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
			//Cargamos los submenus para mostrar en la tabla
			$submenusTabla = $this->menus->getAllSubmenusOrdenedByMenuId();
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";

			//$bodegas = $this->pruebaa->get_bodegas();
			//se envia a la vista el combobox con los menús
			$menus_combo = $this->menus->getAllMenuOrdenedById();
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 
			'pass' => $pass, 'id_usu' => $id_usu, 'submenus' => $submenusTabla,'menus_combo'=>$menus_combo);
			//abrimos la vista
			$this->load->view("configuracion/sub_menu",$arr_user);
		}
	}

	/*
	 *Metodo que obtiene los datos del submenu seleccionado
	 *parametros: recibe el id del submenu a listar
	 *retorna la informaciòn del submenu
	 *by Cristian
	 */

	public function get_submenu_data()
	{
		$this->load->model('menus');
		$idSubmenu = $this->input->GET('idSubmenu');
		$res = $this->menus->getSubmenuById($idSubmenu);
		$menus_cb = $this->menus->getAllMenuOrdenedById();
		foreach ($res->result() as $key) {
			echo '
			<form>
				<div class="form-group">
					<input id="idSubmenu"  type="hidden" value="'.$key->id_submenu.'">
					<label for="nombreSM_update">Nombre:</label>
					<input type="text" class="form-control" id="nombreSM_update"
						placeholder="" value="'.$key->submenu.'">
				</div>
				<div class="form-group">
					<label for="Select2">Menú:</label>
					<select class="form-control js-example-basic-single" style="width:100%" id="Select2" onclick="">';
					echo '<option value="'.$key->id_menu.'" selected>'.$key->menu.'</option>';
					foreach ($menus_cb->result() as $keym) {
						echo '<option value="'.$keym->id_menu.'">'.$keym->menu.'</option>';
					}
			echo '
					</select>
				</div>
				<div class="form-group">
					<label for="rutaVistaSM_update">Ruta Vista:</label>
					<input type="text" class="form-control" id="rutaVistaSM_update"
						placeholder="" value="'.$key->vista.'">
				</div>
				<div class="form-group">
					<label for="iconoSM_update">Icono:</label>
					<input type="text" class="form-control" id="iconoSM_update"
						placeholder="" value="'.$key->icono.'">
				</div>
			</form>
			';
		}
	}

	/*
	 *Metodo que agrega un nuevo submenu a la BD
	 *parametros: recibe el nombre, vista, icono del submenu y el id del menu al que pertenece
	 *retorna un booleano
	 *by Cristian
	 */

	public function insert_submenu()
	{
		$this->load->model('menus');
		$submenu = $this->input->GET('submenu');
		$menu_id = $this->input->GET('menu_id');
		$vista = $this->input->GET('vista');
		$icono = $this->input->GET('icono');
		$res = $this->menus->insertSubMenu($submenu,$vista,$menu_id,$icono);
		if ($res) {
			echo 'Se insertaron los datos';
		}else{
			echo '';
		}
	}

	/*
	 *Metodo que actualiza un submenu en la BD
	 *parametros: recibe el nombre, vista, icono del submenu y el id del menu al que pertenece
	 *retorna un booleano
	 *by Cristian
	 */

	public function update_submenu()
	{
		$this->load->model('menus');
		$idSubmenu = $this->input->GET('idSubmenu');
		$submenu = $this->input->GET('submenu');
		$menu_id = $this->input->GET('menu_id');
		$vista = $this->input->GET('vista');
		$icono = $this->input->GET('icono');
		$res = $this->menus->updateSubMenu($idSubmenu,$submenu,$vista,$menu_id,$icono);
		if ($res) {
			echo 'Se insertaron los datos';
		}else{
			echo '';
		}
	}

	/*
	 *Metodo que elimina un submenu de la BD
	 *parametros: recibe el id del submenu que se eliminará
	 *retorna un booleano
	 *by Cristian
	 */

	public function delete_submenu()
	{
		$this->load->model('menus');
		$submenu_id = $this->input->GET('idSubmenu');
		$perfil = $this->session->userdata('perfil');
		//se elimina primero de la tabla submenu_perfil para poder eliminarlo de la tabla submenus
		$res = $this->menus->deleteSubMenuPerfil($perfil,$submenu_id);
		if ($res) {
			//se elimina de la tabla submenu
			$res1 = $this->menus->deleteSubMenu($submenu_id);
			if ($res1) {
				header("Location: " . base_url() . "sub_menu");
			}else{
				echo 'Error al eliminar el submenu';
			}
		}else{
			echo 'Error al eliminar el submenu del perfil';
		}
		
	}
}


?>