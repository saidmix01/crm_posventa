<?php

/**
 * Controlador Posible_retorno
 * Se desarrolla con el fin de analizar los posibles retornos en los talleres
 * Power By: Sergio Ivan Galvis Esteban
 * Fecha: 11/04/2023
 */
class Posible_retorno extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuarios');
        $this->load->model('menus');
        $this->load->model('perfiles');
        $this->load->model('Model_Posible_retorno');
    }

    public function create_posible_retorno()
    {

        $placa = $this->input->post('placa');
        $tipo_retorno = $this->input->post('tipo_retorno');
        $observacion = $this->input->post('observacion');
        $bodega = $this->input->post('bodega');
        $fecha_creacion = date('Y-m-d') . 'T' . date('H:i:s');

        $dataInsert = array(
            'id_usuario' => $this->session->userdata('user'),
            'placa' => $placa,
            'fecha_creacion' => $fecha_creacion,
            'observacion' => $observacion,
            'tipo_retorno' => $tipo_retorno,
            'bodega' => $bodega,
        );
        $result = $this->Model_Posible_retorno->create_posible_retorno($dataInsert);

        if ($result) {
            $dataResponse = array(
                'response' => 'success',
                'msm' => 'Posible retorno creado exitosamente.',
                'data' => $result
            );
        } else {
            $dataResponse = array(
                'response' => 'error',
                'msm' => 'Error al crear el posible retorno, intente nuevamente.',
                'data' => $result
            );
        }

        echo json_encode($dataResponse);
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
            $tipo_retornos = $this->Model_Posible_retorno->getTiposRetornos();

            $getRazonRetorno = $this->Model_Posible_retorno->getRazonRetorno();
            $getSistemaIvn = $this->Model_Posible_retorno->getSistemaIvn();
            $getPlanAccion = $this->Model_Posible_retorno->getPlanAccion();
            $getCostos = $this->Model_Posible_retorno->getCostos();
            $getBodegas = $this->Model_Posible_retorno->getBodegas();
            //echo $id_usu;
            $arr_user = array(
                'userdata' => $userinfo,
                'menus' => $allmenus,
                'pass' => $pass,
                'id_usu' => $id_usu,
                'tipos_retornos' => $tipo_retornos,
                'getRazonRetorno' => $getRazonRetorno,
                'getSistemaIvn' => $getSistemaIvn,
                'getPlanAccion' => $getPlanAccion,
                'getCostos' => $getCostos,
                'getBodegas' => $getBodegas
            );
            //abrimos la vista
            $this->load->view('Retornos/posibles_retornos', $arr_user);
        }
    }


    public function load_data_retornos()
    {
        $tabla_data = $this->Model_Posible_retorno->getVistaPosibleRetornos($this->input->GET());

        $countall = count($tabla_data[0]->result());
        /* <!-- placa	des_modelo	origen --> */
        $arrayList = [];
        foreach ($tabla_data[1]->result() as $key) {
            $btnSolucion = "";
            if ($key->estado != 'POR DEFINIR') {
                $btnSolucion = '<button type="button" class="btn btn-warning" onclick="verSolucion(' . $key->numero . ')"><i class="fas fa-book"> SOLUCIÓN</i></button>';
            }
            $btnCerrar = "";
            if ($key->origen == 'BDC') {
                $btnCerrar = '<button type="button" class="btn btn-info" onclick="cerrarPosibleBDC(' . $key->numero . ')"><i class="fas fa-book"> CERRAR</i></button>';
            }

            $arrayList[] = [
                $key->rn,
                $key->numero,
                $key->placa,
                $key->des_modelo,
                $key->origen,
                $key->descripcion,
                $key->estado,
                '<button type="button" class="btn btn-primary" onclick="verDetalle(\'' . $key->placa . '\',' . $key->numero . ')"><i class="fas fa-eye"> VER</i></button>
                ' . $btnSolucion . '
                ' . $btnCerrar . '',
            ];
        }
        $output = array(
            "draw"                 => $this->input->GET('draw'),
            "recordsTotal"         => $countall,
            "recordsFiltered"      => $countall,
            "data"                 => $arrayList
        );

        echo json_encode($output);
    }

    public function load_data_detalle_retornos()
    {

        $placa = $this->input->post('placa');
        if ($placa != "") {
            $data_cliente = $this->Model_Posible_retorno->getDetalleClienteRetornoByPlaca($placa);
            $data_ordenes = $this->Model_Posible_retorno->getOrdenesByPlaca($placa);
            $data_tecnicos = $this->Model_Posible_retorno->getTecnicosRetornoByPlaca($placa);
            $bodyOrden = "";
            $arrayOrdenes = [];
            if ($data_ordenes->num_rows() > 0) {
                /* rnk	placa	numero	solicitud	respuesta */
                foreach ($data_ordenes->result() as $orden) {
                    $bodyOrden .= "<tr>
                        <td>$orden->rnk</td>
                        <td>$orden->placa</td>
                        <td>$orden->numero</td>
                        <td>$orden->solicitud</td>
                        <td>$orden->respuesta</td>
                    </tr>";
                    if (!in_array($orden->numero, $arrayOrdenes)) {
                        $arrayOrdenes[] = $orden->numero;
                    }
                }
            }

            $bodyTecnicos = "";
            $arrayTecnicos = [];
            if ($data_tecnicos->num_rows() > 0) {
                /* rnk	placa	numero	tecnicos */
                foreach ($data_tecnicos->result() as $ordenT) {
                    $bodyTecnicos .= "<tr>
                        <td>$ordenT->rnk</td>
                        <td>$ordenT->placa</td>
                        <td>$ordenT->numero</td>
                        <td>$ordenT->tecnicos</td>
                    </tr>";

                    if (!in_array($ordenT->tecnicos, $arrayTecnicos)) {
                        $arrayTecnicos[] = $ordenT->tecnicos;
                    }
                }
            }

            $dataResponse = array(
                'response' => 'success',
                'data_cliente' => $data_cliente->result(),
                'data_ordenes' => $bodyOrden,
                'data_tecnicos' => $bodyTecnicos,
                'array_ordenes' => $arrayOrdenes,
                'array_tecnicos' => $arrayTecnicos,
            );

            echo json_encode($dataResponse);
        } else {
            $dataResponse = array(
                'response' => 'error',
            );
        }
    }

    public function insertDefinicionRetorno()
    {

        $definicion = $this->input->POST('definicion');
        $selectRazon = $this->input->POST('selectRazon');
        $obs_razon = $this->input->POST('obs_razon');
        $select_sist_inv = $this->input->POST('select_sist_inv');
        $obs_sist_inv = $this->input->POST('obs_sist_inv');
        $ordenR = $this->input->POST('ordenR');
        $ordenR_origen = $this->input->POST('ordenR_origen');
        $tecnicoR = $this->input->POST('tecnicoR');
        $selectPlan = $this->input->POST('selectPlan');
        $obs_plan = $this->input->POST('obs_plan');

        $precio_costo_1 = $this->input->POST('precio_costo_1');
        $precio_costo_2 = $this->input->POST('precio_costo_2');
        $precio_costo_3 = $this->input->POST('precio_costo_3');
        $obs_costos = $this->input->POST('obs_costos');

        $fecha_creacion = date('Y-m-d') . 'T' . date('H:i:s');

        $dataInsert = array(
            'definicion' => $definicion != "" ? $definicion : NULL,
            'id_razon' => $selectRazon != "" ? $selectRazon : NULL,
            'obs_razon' => $obs_razon != "" ? $obs_razon : NULL,
            'id_sist_inv' => $select_sist_inv != "" ? $select_sist_inv : NULL,
            'obs_sist_inv' => $obs_sist_inv != "" ? $obs_sist_inv : NULL,
            'numero_retorno' => $ordenR != "" ? $ordenR : NULL,
            'numero' => $ordenR_origen != "" ? $ordenR_origen : NULL,
            'tecnico' => $tecnicoR != "" ? $tecnicoR : NULL,
            'id_plan' => $selectPlan != "" ? $selectPlan : NULL,
            'obs_plan' => $obs_plan != "" ? $obs_plan : NULL,
            'repuestos' => $precio_costo_1 != "" ? $precio_costo_1 : NULL,
            'mano_obra' => $precio_costo_2 != "" ? $precio_costo_2 : NULL,
            'tot' => $precio_costo_3 != "" ? $precio_costo_3 : NULL,
            'obs_costo' => $obs_costos != "" ? $obs_costos : NULL,
            'fecha_creacion' => $fecha_creacion,
            'usuario' => $this->session->userdata('user'),
        );


        if ($this->Model_Posible_retorno->insertDefinicionRetorno($dataInsert)) {

            $dataResponse = array(
                'response' => 'success',
            );
        } else {

            $dataResponse = array(
                'response' => 'error',
            );
        }


        echo json_encode($dataResponse);
    }

    public function loadPosibleRetornoSoluciones()
    {

        $orden = $this->input->post('numero');

        $data = $this->Model_Posible_retorno->getDataSoluciones($orden);
        $tbody = "";
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                /* id_return	definicion	id_razon	obs_razon	id_sist_inv	obs_sist_inv	id_plan	obs_plan	
                repuestos	mano_obra	tot	obs_costo	tecnico	numero	numero_retorno	fecha_creacion	usuario	id_plan	plan_accion	
                id_razon	razon	id_sistema_inv	sistema_inv	id_tipo_retorno	tipo_retorno */
                $tbody .= '<tr>
                    <td>' . ($row->numero != "" ? $row->numero : "--") . '</td>
                    <td>' . ($row->definicion == 1 ? "SI" : "NO") . '</td>
                    <td>' . ($row->razon != "" ? $row->razon : "--") . '</td>
                    <td>' . ($row->obs_razon != "" ? $row->obs_razon : "--") . '</td>
                    <td>' . ($row->sistema_inv != "" ? $row->sistema_inv : "--") . '</td>
                    <td>' . ($row->obs_sist_inv != "" ? $row->obs_sist_inv : "--") . '</td>
                    <td>' . ($row->plan_accion != "" ? $row->plan_accion : "--") . '</td>
                    <td>' . ($row->obs_plan != "" ? $row->obs_plan : "--") . '</td>
                    <td>' . ($row->repuestos != "" ? $row->repuestos : "--") . '</td>
                    <td>' . ($row->mano_obra != "" ? $row->mano_obra : "--") . '</td>
                    <td>' . ($row->tot != "" ? $row->tot : "--") . '</td>
                    <td>' . ($row->obs_costo != "" ? $row->obs_costo : "--") . '</td>
                    <td>' . ($row->tecnico != "" ? $row->tecnico : "--") . '</td>
                    <td>' . ($row->numero_retorno != "" ? $row->numero_retorno : "--") . '</td>
                    <td>' . ($row->fecha_creacion != "" ? $row->fecha_creacion : "--") . '</td>
                    <td>' . ($row->nombres != "" ? $row->nombres : "--") . '</td>
                </tr>';
            }

            $dataResponse = array(
                'response' => 'success',
                'tbody' => $tbody
            );
        } else {
            $dataResponse = array(
                'response' => 'error',
            );
        }

        echo json_encode($dataResponse);
    }


    public function cerrarPosiblesRetornosBDC()
    {
        $id_posible_bdc = $this->input->post('id_posible_bdc');

        $dataInsert = array(
            'id_posible_bdc' => $id_posible_bdc,
            'usuario' => $this->session->userdata('user'),
            'fecha' => date('Y-m-d') . 'T' . date('H:i:s'),
        );

        if ($this->Model_Posible_retorno->cerrarPosibleRetornoBDCById($dataInsert)) {
            $dataResponse = array(
                'response' => 'success',
            );
        } else {
            $dataResponse = array(
                'response' => 'error',
            );
        }

        echo json_encode($dataResponse);
    }


    /*
    * Autor: SERGIO IVAN GALVIS ESTEBAN
    * Tema: Funciones para el Informe de posibles retornos
    * Fecha:  2023-04-25 
    */

    public function informe()
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
            $all_tecnicos = $this->Model_Posible_retorno->getTecnicos();
            $getBodegas = $this->Model_Posible_retorno->getBodegas();
            //echo $id_usu;
            $arr_user = array(
                'userdata' => $userinfo,
                'menus' => $allmenus,
                'pass' => $pass,
                'id_usu' => $id_usu,
                'all_tecnicos' => $all_tecnicos,
                'getBodegas' => $getBodegas
            );
            //abrimos la vista
            $this->load->view('Retornos/inf_posible_retorno', $arr_user);
        }
    }

    public function loadGraph()
    {

        $year = $this->input->POST('year');
        $tecnico = $this->input->POST('tecnico');
        $sede = $this->input->POST('sede');

        $name_tecnico = "";
        if($tecnico != "" && $sede == ""){
            $name_tecnico = $this->Model_Posible_retorno->getNameTecnico($tecnico)->row(0)->nombres;
            $data = $this->Model_Posible_retorno->entrada_Vs_retornosByTecnico($year,$tecnico,$name_tecnico);
        }else if ($tecnico == "" && $sede != ""){
            $data = $this->Model_Posible_retorno->entrada_Vs_retornosBySede($year,$sede);
        }else {
            $data = $this->Model_Posible_retorno->entrada_Vs_retornos($year);
        }

        
        if ($data->num_rows() > 0) {
            $entradas  = array();
            $retornos = array();
            $posibles  = array();
            /* mes	entradas	posibles_retornos	retornos */
            $bandera = 0;
            $meses = [
                '',
                'ENERO',
                'FEBRERO',
                'MARZO',
                'ABRIL',
                'MAYO',
                'JUNIO',
                'JULIO',
                'AGOSTO',
                'SEPTIEMBRE',
                'NOVIEMBRE',
                'DICIEMBRE'
            ];
            foreach ($data->result() as $row) {

                $entradas[$bandera] = array("label" => $meses[$row->mes], "y" => $row->entradas);
                $retornos[$bandera] = array("label" => $meses[$row->mes], "y" => $row->retornos);
                $posibles[$bandera] = array("label" => $meses[$row->mes], "y" => $row->posibles_retornos);
                $bandera++;
            }

            $dataResponse = array(
                'response' => 'success',
                'entradas' => $entradas,
                'retornos' => $retornos,
                'posibles' => $posibles,
            );
        } else {

            $dataResponse = array(
                'response' => 'error'
            );
        }

        echo json_encode($dataResponse);
    }
}
