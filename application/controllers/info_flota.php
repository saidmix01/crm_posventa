<?php

/**
 * 
 */
class info_flota extends CI_Controller
{
    /* Metodo que carga la interfaz de repuestos 
	 * usa la vista repuestos
	 */
    public function index()
    {
        /* validamos si hay datos de session 
		 * si no hay se redireccion al login y se destruye la session
		 */
        if (!$this->session->userdata('login')) {
            $this->session->sess_destroy();
            header("Location: " . base_url());
        } else {
            //llamamos los modelos
            /* $this->load->model('usuarios');
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
			$allusers = $this->usuarios->getAllUsers(); */

            //$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'alluser' => $allusers);
            //abrimos la vista
            $this->load->view('Informe_flotas/index');
        }
    }

    public function info_agrupado()
    {
        $this->load->view('Informe_flotas/inf_agrupado');
    }

    public function modal()
    {
        $this->load->view('Informe_flotas/modal_neg');        
    }
    public function noInteresado()
    {
        $this->load->view('Informe_flotas/no_interesado');        
    }
    public function nuevoNegocio()
    {
        $this->load->view('Informe_flotas/crear_negocio');        
    }
}
