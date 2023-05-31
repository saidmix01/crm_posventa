<?php

class Inf_Control_Compras extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuarios');
        $this->load->model('menus');
        $this->load->model('perfiles');
        $this->load->model('control_compras');
    }

    public function index()
    {
        if (!$this->session->userdata('login')) {
            $this->session->sess_destroy();
            header("Location: " . base_url());
        } else {

            $usu = $this->session->userdata('user');
            $pass = $this->session->userdata('pass');
            //obtenemos el perfil del usuario
            $perfil_user = $this->perfiles->getPerfilByUser($usu);
            //cargamos la informacion del usuario y la pasamos a un array
            $userinfo = $this->usuarios->getUserByName($usu);
            $allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);

            $id_usu = "";
            foreach ($userinfo->result() as $key) {
                $id_usu = $key->id_usuario;
            }
            //echo $id_usu;
            $arr_user = array(
                'userdata' => $userinfo,
                'menus' => $allmenus,
                'pass' => $pass,
                'id_usu' => $id_usu,
            );
            //abrimos la vista
            $this->load->view('compras/inf_control_compras', $arr_user);
        }
    }

    public function load_data()
    {
        $orden = $this->input->post('orden');
        $data = $this->control_compras->getData($orden);

        if ($data->num_rows() > 0) {
            $tbody = "";
            foreach ($data->result() as $row) {
                /* numero	fecha	codigo	descripcion	cantidad	valor_unitario	valor_total	calificacion_abc	ultima_compra	ultima_venta	Giron	Chevropartes	Barranca	Rosita	Villa	Solochevrolet */
                $tbody .= "<tr>
                <td scope='col' class='text-center'>$row->codigo</td>
                <td scope='col' class='text-center'>$row->descripcion</td>
                <td scope='col' class='text-center'>$row->cantidad</td>
                <td scope='col' class='text-center'>" . number_format(ceil($row->valor_unitario), '0', ',', '.') . "</td>
                <td scope='col' class='text-center'>" . number_format(ceil($row->valor_total), '0', ',', '.') . "</td>
                <td scope='col' class='text-center'>$row->calificacion_abc</td>
                <td scope='col' class='text-center'>$row->ultima_compra</td>
                <td scope='col' class='text-center'>$row->ultima_venta</td>
                <td scope='col' class='text-center'>" . number_format($row->Giron, "0", ",", ".") . "</td>
                <td scope='col' class='text-center'>" . number_format($row->Chevropartes, "0", ",", ".") . "</td>
                <td scope='col' class='text-center'>" . number_format($row->Barranca, "0", ",", ".") . "</td>
                <td scope='col' class='text-center'>" . number_format($row->Rosita, "0", ",", ".") . "</td>
                <td scope='col' class='text-center'>" . number_format($row->Villa, "0", ",", ".") . "</td>
                <td scope='col' class='text-center'>" . number_format($row->Solochevrolet, "0", ",", ".") . "</td>
                </tr>";
            }

            $dataResponse = array(
                'response' => 'success',
                'data' => $tbody
            );
        } else {
            $dataResponse = array(
                'response' => 'error',
            );
        }

        echo json_encode($dataResponse);
    }
}
