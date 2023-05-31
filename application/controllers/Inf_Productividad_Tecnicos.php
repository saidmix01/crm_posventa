<?php

class Inf_Productividad_Tecnicos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuarios');
        $this->load->model('menus');
        $this->load->model('perfiles');
        $this->load->model('Productividad_Tecnicos');
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
            $this->load->view('tecnicos/productividad_tecnicos', $arr_user);
        }
    }

    public function load_data()
    {
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        $patio = $this->input->post('patio');

        if($patio == ""){
            $where_patio = "tt.patio in (1,2,3,4,5,6,7,8,9) ";
        }else {
            $where_patio = "tt.patio in ($patio) ";
        }

        $data = $this->Productividad_Tecnicos->getData($year, $month,$where_patio);

        if ($data->num_rows() > 0) {
            $tbody = "";
            foreach ($data->result() as $row) {
                /* nit	nombres	patio	horas_cliente	horas_garantia	horas_servicio	horas_interno	total_horas	horas_disp */
                $productividad = ($row->total_horas / $row->horas_disp) * 100;
                $backgraund = 'style="background: lightpink;"';
                if($productividad >= 100){
                    $backgraund = 'style="background: lightgreen;"';
                }

                $tbody .= "<tr>
                <td scope='col' class='text-center'>$row->nit</td>
                <td scope='col' class='text-center'>$row->nombres</td>
                <td scope='col' class='text-center'>$row->patio</td>
                <td scope='col' class='text-center'>".number_format($row->horas_cliente,2,",",".")."</td>
                <td scope='col' class='text-center'>".number_format($row->horas_garantia,2,",",".")."</td>
                <td scope='col' class='text-center'>".number_format($row->horas_servicio,2,",",".")."</td>
                <td scope='col' class='text-center'>".number_format($row->horas_interno,2,",",".")."</td>
                <td scope='col' class='text-center'>".number_format($row->total_horas,2,",",".")."</td>
                <td scope='col' class='text-center'>$row->horas_disp</td>
                <td $backgraund scope='col' class='text-right'>".number_format($productividad,"2",",",".")."%</td>
                </tr>";
            }

            $tbody_2 = $this->load_data_all($year, $month,$where_patio);

            $dataResponse = array(
                'response' => 'success',
                'data' => $tbody,
                'data2' => $tbody_2,
            );
        } else {
            $dataResponse = array(
                'response' => 'error',
            );
        }

        echo json_encode($dataResponse);
    }

    public function load_data_all($year, $month,$patio)
    {
        $data = $this->Productividad_Tecnicos->getDataAll($year, $month,$patio);
        $tbody = "";
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                /* nit	nombres	patio	horas_cliente	horas_garantia	horas_servicio	horas_interno	total_horas	horas_disp */
                $productividad = ($row->total_horas / $row->horas_disp) * 100;
                $backgraund = 'style="background: lightpink;"';
                if($productividad >= 100){
                    $backgraund = 'style="background: lightgreen;"';
                }
                $tbody .= "<tr>
                <td scope='col' class='text-center'>$row->nit</td>
                <td scope='col' class='text-center'>$row->nombres</td>
                <td scope='col' class='text-center'>$row->patio</td>
                <td scope='col' class='text-center'>".number_format($row->horas_cliente,2,",",".")."</td>
                <td scope='col' class='text-center'>".number_format($row->horas_garantia,2,",",".")."</td>
                <td scope='col' class='text-center'>".number_format($row->horas_servicio,2,",",".")."</td>
                <td scope='col' class='text-center'>".number_format($row->horas_interno,2,",",".")."</td>
                <td scope='col' class='text-center'>".number_format($row->total_horas,2,",",".")."</td>
                <td scope='col' class='text-center'>$row->horas_disp</td>
                <td $backgraund scope='col' class='text-right'>".number_format($productividad,"2",",",".")."%</td>
                </tr>";
            }
        }

        return $tbody;
    }
}
