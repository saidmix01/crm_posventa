<?php

/**
 * Controlador Posible_retorno
 * Se desarrolla con el fin de analizar el tiempo de las entrevistas consultivas
 * Power By: Sergio Ivan Galvis Esteban
 * Fecha: 24/04/2023
 */
class InfTiempoEntrevistaConsultiva extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuarios');
        $this->load->model('menus');
        $this->load->model('perfiles');
        $this->load->model('Model_tiempoEntrevistaConsultiva');
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
            $this->load->view('informe_entrevistas/tiempo_entrevistas', $arr_user);
        }
    }

    public function load_data_tiempo()
    {
        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');
        $data = $this->Model_tiempoEntrevistaConsultiva->getInfo($startDate,$endDate);

        if ($data->num_rows() > 0) {
            $tbody = "";
            foreach ($data->result() as $row) {
                /* bodega	registros_citas	citas_marcadas	citas_no_marcadas	citas_cumplidas	citas_no_cumplidas	no_asistieron	ot_abiertas	tiempo_entrevista_consultiva */
                $tbody .= "<tr>
                <td scope='col' class='text-center'>$row->bodega</td>
                <td scope='col' class='text-center'>$row->registros_citas</td>
                <td scope='col' class='text-center'>$row->citas_marcadas</td>
                <td scope='col' class='text-center'>$row->citas_no_marcadas</td>
                <td scope='col' class='text-center'>$row->citas_cumplidas</td>
                <td scope='col' class='text-center'>$row->citas_no_cumplidas</td>
                <td scope='col' class='text-center'>$row->no_asistieron</td>
                <td scope='col' class='text-center'>$row->ot_abiertas</td>
                <td scope='col' class='text-center'>$row->tiempo_entrevista_consultiva</td>
                <td scope='col' class='text-center'><button class='btn btn-warning' onclick='verDetalleBodega($row->bodega);'>DETALLE</button></td>
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

    public function load_data_tiempo_detail(){
        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');
        $data = $this->Model_tiempoEntrevistaConsultiva->getInfoByBodega($this->input->POST('bodega'),$startDate,$endDate);

        if ($data->num_rows() > 0) {
            $tbody = "";
            foreach ($data->result() as $row) {
                /* <!-- id_cita	placa	fecha_cita	bodega	hora_llegada	numero_orden_taller	hora_orden	tiempo_orden--> */
                $tbody .= "<tr>
                <td scope='col' class='text-center'>$row->id_cita</td>
                <td scope='col' class='text-center'>$row->placa</td>
                <td scope='col' class='text-center'>$row->fecha_cita</td>
                <td scope='col' class='text-center'>$row->bodega</td>
                <td scope='col' class='text-center'>$row->hora_llegada</td>
                <td scope='col' class='text-center'>$row->numero_orden_taller</td>
                <td scope='col' class='text-center'>$row->hora_orden</td>
                <td scope='col' class='text-center'>$row->tiempo_orden</td>
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
