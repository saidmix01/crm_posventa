<?php 

/**
 * 
 */
class ListaFlotas extends CI_Controller
{
	
	public function index()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('flotasmodel');
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			$id_usu = 0;
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$flotas = $this->flotasmodel->get_flotas_ingresadas();
			$arr_user = array('userdata' => $userinfo,'pass'=>$pass,'id_usu'=>$id_usu, 'menus' => $allmenus, 'perfiles' => $allperfiles, "flotas" => $flotas);
			//abrimos la vista
			$this->load->view('Informe_lista_flotas/index',$arr_user);
		}
	}

	public function load_tabla_flota_detallada()
	{
		$this->load->model('flotasmodel');
		$flota = $this->input->POST('flota');
		$flotas = $this->flotasmodel->get_flotas_ingresada_detallada($flota);
		if ($flotas != null) {
			$count=0;
			echo '
				<table style="width: 100%;">
					<thead>
						<tr>
							<th>Placa</th>
							<th>Asesor</th>
							<th>Comisiona</th>
							<th>Periodicidad</th>
							<th>Observación Desv.</th>
							<th>Estado</th>
						</tr>
					</thead>
					<tbody>
			';
			foreach ($flotas->result() as $key) {
				$comisiona = $key->comisiona == 1 ? 'Sí' : 'No';
				$estado    = $key->activo == 1 ? 'Activo' : 'Inactivo';
				$count++;
				echo '<tr>
						<td>'.$key->placa.'</td>
						<td>'.$key->nombres.'</td>
						<td>'.$comisiona.'</td>
						<td>'.$key->periodicidad.' Meses</td>
						<td>'.$key->observacion_desv.'</td>
						<td>'.$estado.'</td>
					</tr>';
			}
			echo '</tbody>
			</table>';
		} else {
			echo 'No hay datos';
		}
	}
	
}

?>
