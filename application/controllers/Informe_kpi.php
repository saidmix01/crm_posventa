<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
        
class Informe_kpi extends CI_Controller
{
 
    public function index(){
        if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('compra');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			foreach ($userinfo->result() as $nombre) {
				$nomb = $nombre->nombres;
			}
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$all_empleados = $this->usuarios->getAllUsers();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array('perfil'=>$perfil_user->id_perfil,'userdata' => $userinfo, 'menus' => $allmenus, 'pass' => $pass, 'id_usu' => $id_usu, 'nomb' => $nomb,'all_emp'=>$all_empleados);
			//abrimos la vista
			$this->load->view('Informe_kpi/indicador_kpi',$arr_user);
		}
        
        
    }

    public function cargarTablaKPI(){
        $this->load->model('Informe_kpi_model');

        $kpi = $this->input->POST('ind_kpi');

        $dataIndicador = $this->Informe_kpi_model->getDataIndicadorKpi($kpi);
        //bodega	kpi

        if(count($dataIndicador->result()) > 0){

            echo '<table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th>BODEGA</th>
                        <th>CANTIDAD</th>
                    </tr>
                    </thead>
                    <tbody>';
                    
            foreach ($dataIndicador->result() as $key) {
                echo '<tr>
                <td>' . $key->bodega . '</td>
                <td>' . number_format(ceil($key->kpi),0,',','.') . '</td>
                </tr>';
            }

            echo '</tbody></table>';

        }
    }

}


/* End of file Informe_kpi.php and path \application\controllers\Informe_kpi.php */
