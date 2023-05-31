<?php
//controladro para la creacion de menu - Andres Gomez - 14-09-21
class perfil extends CI_Controller
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
            $datos = $this->menus->ver_perfil();
            foreach ($userinfo->result() as $key) {
                $id_usu = $key->id_usuario;
            }
            $arr_user = array(
                'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles,
                'pass' => $pass, 'id_usu' => $id_usu, 'data_tabla' => $datos
            );
            //abrimos la vista
            $this->load->view("configuracion/perfil", $arr_user);
        }
    }



    public function agregar_nuevo_perfil() //controlador para  agregar los datos a la tabla postv_perfiles Andres gomez 16-09-21 
    {
        $this->load->model('menus');
        $nombre = $this->input->GET('nombre');
        $res = $this->menus->getPerfil($nombre);
        if ($res) {
            echo 'Perfil Agrgado de manera correcta';
        } else {
            echo '';
        }
    }

    public function eliminar_perfil() //controlador para eliminar datos de la tabla postv_perfiles Andres gomez 16-09-21 
    {
        $this->load->model('menus');
        $id = $this->input->GET('id');
        $resultado = $this->menus->deletePerfil($id);
        if ($resultado) {
            echo "perfil eliminado de manera correcta";
        } else {
            echo "";
        }
    }

    public function ver_perfil()//controlador para  pintar los datos a la tabla postv_perfiles Andres gomez 16-09-21 
    {
        $this->load->model('menus');
        $id = $this->input->GET('id');
        $datos = $this->menus->traer_datos_perfil($id);
        foreach ($datos->result() as $key) {
            echo '
			<form method="post" id="formulario_perfil_update">
                                    <div class="form-group">
                                        <input type="text" class="form-control d-none" name="codigo_editar_perfil" id="codigo_editar_perfil" value="' . $id . '" aria-describedby="emailHelp">
                                    </div>
                                    <div class="form-group">
                                        <label for="nombreMenu">Nombre del Menu</label>
                                        <input type="text" class="form-control" name="nombrePerfil_editar" id="nombrePerfil_editar" value="' . $key->nom_perfil . '" aria-describedby="emailHelp">
                                    </div>
									
                                </form>
			';
        }
    }

    public function editar_perfil() //controlador para  editar los datos a la tabla postv_perfiles Andres gomez 16-09-21 
    {
        $this->load->model('menus');
        $id = $this->input->GET('id');
        $nombre = $this->input->GET('nombre');
        $datos = $this->menus->updateperfil($id, $nombre);
        if ($datos) {
            echo 'Se guardaron los datos';
        } else {
            echo '';
        }
    }
}
