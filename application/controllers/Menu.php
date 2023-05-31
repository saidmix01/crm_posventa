<?php
//controladro para la creacion de menu - Andres Gomez - 14-09-21
class menu extends CI_Controller
{

	public function index()
	{

		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
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
			$id_usu = "";

			//$bodegas = $this->pruebaa->get_bodegas();
			$datos = $this->menus->getAllMenu();
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 
			'pass' => $pass, 'id_usu' => $id_usu, 'data_tabla' => $datos);
			//abrimos la vista
			$this->load->view("configuracion/menu.php", $arr_user);
		}
	}

	/* public function cargar_tabla_menu() //controlador para traer los datos de la tabla dbo.postv_menus Andres gomez 15-09-21 
	{

		$this->load->model('menus');
		$datos = $this->menus->getAllMenu();
		foreach ($datos->result() as $key) {
			echo "
			<tr>
			<td>$key->menu</td>
			<td>$key->icono</td>
			<td><button data-toggle='modal' data-target='#updatemodal' class='fas fa-edit btn btn-warning shadow text-white'id='$key->id_menu' onclick='pintar_datos(this.id)'> </button></td>
			<td><button class='fas fa-trash-alt btn btn-danger shadow' id='$key->id_menu' onclick='eliminar(this.id)'></button></td>
			</tr>
			";
		}
	} */

	public function agregar_nuevo_menu() //controlador para  agregar los datos a la tabla dbo.postv_menus Andres gomez 15-09-21 
	{
		$this->load->model('menus');
		$nombre = $this->input->GET('nombre');
		$vista = $this->input->GET('vista');
		$icono = $this->input->GET('icono');
		$res = $this->menus->getMenu($nombre, $vista, $icono);
		if ($res) {
			echo 'Se guardaron los datos';
		} else {
			echo '';
		}
	}

	public function eliminar_menu() //controlador para eliminar datos de la tabla dbo.postv_menus Andres gomez 15-09-21 
	{
		$this->load->model('menus');
		$id = $this->input->GET('id');
		//echo $id; die;
		$resultado = $this->menus->deleteMenu($id);
		if ($resultado) {
			echo "Menu eliminado de manera correcta";
		} else {
			echo "";
		}
	}

	public function ver_datos()
	{
		$this->load->model('menus');
		$id = $this->input->GET('id');
		$datos = $this->menus->traer_datos_menu($id);
		foreach($datos->result() as $key){
			echo '
			<form method="post" id="formulario_menu_update">
                                    <div class="form-group">
                                        <input type="text" class="form-control d-none" name="codigo_editar" id="codigo_editar" value="'.$id.'" aria-describedby="emailHelp">
                                    </div>
                                    <div class="form-group">
                                        <label for="nombreMenu">Nombre del Menu</label>
                                        <input type="text" class="form-control" name="nombreMenu_editar" id="nombreMenu_editar" value="'.$key->menu.'" aria-describedby="emailHelp">
                                    </div>
                                    <div class="form-group">
                                        <label for="nombre_icono">Icono del menu</label>
                                        <input type="text" class="form-control" name="nombre_icono_editar" value="'.$key->icono.'" id="nombre_icono_editar">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control d-none" name="vista_menu_editar" value="'.$key->vista_menu.'" id="vista_menu_editar">
                                    </div>
									
                                </form>
			';


		}
	}

	public function editar_menu() //controlador para  editar los datos a la tabla dbo.postv_menus Andres gomez 15-09-21 
	{
		$this->load->model('menus');
		$id = $this->input->GET('id');
		$nombre = $this->input->GET('nombre');
		$vista = $this->input->GET('vista');
		$icono = $this->input->GET('icono');
		$datos = $this->menus->updatemenu($id, $nombre, $vista, $icono);
		if ($datos) {
			echo 'Se guardaron los datos';
		} else {
			echo '';
		}
	}
}
