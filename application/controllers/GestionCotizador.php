<?php

class GestionCotizador extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuarios');
        $this->load->model('menus');
        $this->load->model('perfiles');
        $this->load->model('Cotizar');
        $this->load->model('Gestion_cotizar', 'gestion_cotizar');
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

            $array_clases_vh = $this->gestion_cotizar->getClasesAdicionales();


            $arr_user = array(
                'userdata' => $userinfo,
                'menus' => $allmenus,
                'pass' => $pass,
                'id_usu' => $id_usu,
                'selectClases' => $array_clases_vh,
            );
            //abrimos la vista
            $this->load->view('Cotizador/addAdicionales', $arr_user);
        }
    }

    public function addAdicionalDB()
    {

        $clase = $this->input->POST('clase');
        $adicional = $this->input->POST('adicional');

        $cant_repuestos = $this->input->POST('CantidadDatosRepuesto');
        $cant_mano_obra = $this->input->POST('CantidadDatosMano');

        $info_rptos_add = 0;
        $info_rptos_fail = 0;
        $info_mano_add = 0;
        $info_mano_fail = 0;
        $clases = explode(',', $clase);
        if (count($clases) > 0) {
            foreach ($clases as $clase) {
                /* Repuestos */
                if ($cant_repuestos > 0) {
                    for ($i = 0; $i < $cant_repuestos; $i++) {
                        $dataRepuestos = explode(',', $this->input->POST('datosRepuesto' . $i));

                        $dataInsertR = array(
                            'clase' => $clase,
                            'codigo' => trim($dataRepuestos[0]),
                            'descripcion' => trim($dataRepuestos[1]),
                            'cantidad' => $dataRepuestos[2],
                            'clase_operacion' => 'R',
                            'adicional' => $adicional,
                            'usuario' => $this->session->userdata('user'),
                            'fecha' => date('Y-m-d') . 'T' . date('H:i:s')
                        );
                        if (($this->gestion_cotizar->getAdicionalRepuestos($dataRepuestos[0], $adicional, $clase)->row(0)->cantidad) == 0) {
                            if ($this->gestion_cotizar->insertAdicionalRepuestos($dataInsertR)) {
                                $info_rptos_add++;
                            } else {
                                $info_rptos_fail++;
                            }
                        } else {
                            $info_rptos_fail++;
                        }
                    }
                }
                /* Mano de Obra */
                if ($cant_mano_obra > 0) {
                    for ($i = 0; $i < $cant_mano_obra; $i++) {
                        $dataManoObra = explode(',', $this->input->POST('datosMano' . $i));

                        $dataInsertM = array(
                            'clase' => $clase,
                            'operacion' => trim($dataManoObra[0]),
                            'tiempo' => $dataManoObra[1],
                            'valor_menos_5anos' => $dataManoObra[2],
                            'valor_mas_5anos' => $dataManoObra[3],
                            'adicional' => $adicional,
                            'usuario' => $this->session->userdata('user'),
                            'fecha' => date('Y-m-d') . 'T' . date('H:i:s')
                        );

                        if (($this->gestion_cotizar->getAdicionalManoObra($dataManoObra[0], $adicional, $clase)->row(0)->cantidad) == 0) {
                            if ($this->gestion_cotizar->insertAdicionalManoObra($dataInsertM)) {
                                $info_mano_add++;
                            } else {
                                $info_mano_fail++;
                            }
                        } else {
                            $info_mano_fail++;
                        }
                    }
                }
            }

            $array_response = array(
                'response' => 'success',
                'repuestos_add' => $info_rptos_add,
                'repuestos_fail' => $info_rptos_fail,
                'mano_add' => $info_mano_add,
                'mano_fail' => $info_mano_fail,
                'msm' => 'Se realizo con exito los siguientes registros:'
            );
        } else {
            $array_response = array(
                'response' => 'error',
                'repuestos_add' => $info_rptos_add,
                'repuestos_fail' => $info_rptos_fail,
                'mano_add' => $info_mano_add,
                'mano_fail' => $info_mano_fail,
                'msm' => 'No se encontraron clases para realizar el registro:'
            );
        }

        echo json_encode($array_response);
    }


    public function getNameAdicionales()
    {

        $data = $this->gestion_cotizar->getNameAdicional();
        $bodySelcet = '<option value="">Seleccione un adicional</option>';

        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $bodySelcet .= '<option value="' . $row->adicional . '">' . $row->adicional . '</option>';
            }
            $array_response = array(
                'response' => 'success',
                'data' => $bodySelcet
            );
        } else {
            $array_response = array(
                'response' => 'error',
                'data' => $bodySelcet
            );
        }

        echo json_encode($array_response);
    }

    public function getListAdicionalesAll()
    {
        $clases = $this->input->POST('clases');
        $adicional = $this->input->POST('adicional');

        if ($clases != "") {
            $array_clase = explode(',', $clases);
            $in_clases = "";
            for ($i = 0; $i < count($array_clase); $i++) {
                if (count($array_clase) == $i + 1) {
                    $in_clases .= "'" . $array_clase[$i] . "'";
                } else {
                    $in_clases .= "'" . $array_clase[$i] . ",'";
                }
            }
        }

        if ($clases != "" && $adicional != "") {
            $where_clase = "where adicional = '$adicional' and clase in ($in_clases)";
        } else if ($clases != "" && $adicional == "") {
            $where_clase = "where clase in ($in_clases)";
        } else if ($clases == "" && $adicional != "") {
            $where_clase = "where adicional = '$adicional'";
        } else {
            $where_clase = "";
        }

        $dataRepuestos = $this->gestion_cotizar->AllAdicionalReptos($where_clase);
        $dataManoObra = $this->gestion_cotizar->AllAdicionalManoObra($where_clase);

        $tbodyRepuestos = "";
        if ($dataRepuestos->num_rows() > 0) {
            foreach ($dataRepuestos->result() as $row) {

                $btnDelete = "";
                if ($row->usuario == $this->session->userdata('user')) {
                    $btnDelete = '<button id="btnDelete" name="btnDelete" class="btn btn-danger" onclick="deleteItemAdicionalR(' . $row->seq . ',\'' . $row->codigo . '\',\'' . $row->adicional . '\');"><i class="fas fa-trash"></i></button>';
                }

                $tbodyRepuestos .= '<tr>
                <td class="text-center">' . $row->clase . '</td>
                <td class="text-center">' . $row->codigo . '</td>
                <td class="text-center">' . $row->descripcion . '</td>
                <td class="text-right">' . $row->cantidad . '</td>
                <td class="text-center">' . $row->adicional . '</td>
                <td class="text-center">' . $btnDelete . '</td>
                </tr>';
            }
        }

        $tbodyMano = "";
        if ($dataManoObra->num_rows() > 0) {
            foreach ($dataManoObra->result() as $row) {

                $btnDelete = "";
                if ($row->usuario == $this->session->userdata('user')) {
                    $btnDelete = '<button id="btnDelete" name="btnDelete" class="btn btn-danger" onclick="deleteItemAdicionalM(' . $row->id . ',\'' . $row->operacion . '\',\'' . $row->adicional . '\');"><i class="fas fa-trash"></i></button>';
                }

                $tbodyMano .= '<tr>
                <td class="text-center">' . $row->clase . '</td>
                <td class="text-center">' . $row->operacion . '</td>
                <td class="text-right">' . $row->tiempo . '</td>
                <td class="text-right">' . number_format($row->valor_menos_5anos, "0", ",", ".") . '</td>
                <td class="text-right">' . number_format($row->valor_mas_5anos, "0", ",", ".") . '</td>
                <td class="text-center">' . $row->adicional . '</td>
                <td class="text-center">' . $btnDelete . '</td>
                </tr>';
            }
        }

        $array_response = array(
            'tbodyRepuestos' => $tbodyRepuestos,
            'tbodyMano' => $tbodyMano
        );

        echo json_encode($array_response);
    }

    public function checkCodigoRepto()
    {
        $codigo = $this->input->post('codigo');
        $data = $this->gestion_cotizar->checkCodigoRepto($codigo);

        if ($data->num_rows() > 0) {
            $array_response = array(
                'response' => 'success',
                'codigo' => $data->row(0)->codigo,
                'alterno' => $data->row(0)->alterno != NULL ? $data->row(0)->alterno : "",
            );
        } else {
            $array_response = array(
                'response' => 'error'
            );
        }

        echo json_encode($array_response);
    }

    public function createNewAdicional()
    {
        $adicional = $this->input->POST('adicional');

        $data = array(
            'adicional' => trim($adicional),
        );

        if ($this->gestion_cotizar->exists_Adicional($data)->num_rows() > 0) {
            $array_response = array(
                'response' => 'existe',
            );
        } else {
            if ($this->gestion_cotizar->insertAdicionalName($data)) {
                $array_response = array(
                    'response' => 'success',
                );
            } else {
                $array_response = array(
                    'response' => 'error',
                );
            }
        }

        echo json_encode($array_response);
    }

    public function paintAdicionalesDisponible()
    {

        $array_adicionales = $this->gestion_cotizar->getAdicionalesDisponibles();
        $tbody = "";
        $select = '<option value="">SELECCIONE UN ADICIONAL</option>';
        foreach ($array_adicionales->result() as $row) {
            $tbody .= '<tr>
            <td>' . $row->id . '</td>
            <td>' . $row->adicional . '</td>
            </tr>';

            $select .= '<option value="' . $row->adicional . '">' . $row->adicional . '</option>';
        }

        $array_response = array(
            'tabla' => $tbody,
            'select' => $select,
        );

        echo json_encode($array_response);
    }

    public function deleteItemAdicionalR()
    {

        $seq = $this->input->POST('seq');
        $codigo = $this->input->POST('codigo');
        $adicional = $this->input->POST('adicional');

        if ($this->gestion_cotizar->deleteItemAdicionalR($seq, $codigo, $adicional, $this->session->userdata('user'))) {
            $array_response = array(
                'response' => 'success',
            );
        } else {
            $array_response = array(
                'response' => 'error',
            );
        }

        echo json_encode($array_response);
    }

    public function deleteItemAdicionalM()
    {
        $id = $this->input->POST('id');
        $operacion = $this->input->POST('operacion');
        $adicional = $this->input->POST('adicional');

        if ($this->gestion_cotizar->deleteItemAdicionalM($id, $operacion, $adicional, $this->session->userdata('user'))) {
            $array_response = array(
                'response' => 'success',
            );
        } else {
            $array_response = array(
                'response' => 'error',
            );
        }

        echo json_encode($array_response);
    }
}
