<?php 

/**
 * 
 */
class Inventario extends CI_Controller
{
	/* Metodo que carga la interfaz de repuestos 
	 * usa la vista repuestos
	 */
	public function repuestos()
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
			$this->load->model('inventarios');
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->perfil);
			$allusers = $this->usuarios->getAllUsers();

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'alluser' => $allusers);
				//abrimos la vista
			$this->load->view('repuestos',$arr_user);
		}
	}


	/*Metodo que busca de acuerdo a un rango de fecha 
	 * y un usuario
	 */
	public function buscar_fecha()
	{
		/* validamos si hay datos de session 
		 * si no hay se redireccion al login y se destruye la session
		 */
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//traemos las fechas del formulario
			$fecha_ini = $this->input->get('fecha_ini');
			$fecha_fin = $this->input->get('fecha_fin');
			$usu = $this->input->get('usu');
			//echo $fecha_ini." ".$fecha_fin." ".$usu;
			//llamamos los modelos
			$this->load->model('inventarios');
			$registros = $this->inventarios->getRepuestosByFechas($fecha_ini,$fecha_fin,$usu);
			/*imprimimos los resultado en la tabla*/
			foreach ($registros->result() as $key) {
				echo '
					<tr>
						<td>'.$key->descripcion.'</td>
						<td>'.$key->sede.'</td>
						<td>'.$key->fecha_creacion.'</td>
					</tr>
				';
			}
		}
	}
}

 ?>