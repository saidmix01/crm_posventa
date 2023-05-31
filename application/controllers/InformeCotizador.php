<?php

class InformeCotizador extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->model('usuarios');
        $this->load->model('menus');
        $this->load->model('perfiles');
        $this->load->model('Cotizar');
        $this->load->model('InformeCotizadorModel', 'informe');
    }

    public function index()
    {
        if (!$this->session->userdata('login')) {
            $this->session->sess_destroy();
            header("Location: " . base_url());
        } else {
            //si ya hay datos de session los carga de nuevo


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
                'id_usu' => $id_usu
            );
            //abrimos la vista
            $this->load->view('Cotizador/informe', $arr_user);
        }
    }

    public function loadAllCountCotizaciones()
    {
        $allBodegas = '1,6,7,8';
        $inputDateInicial = $this->input->POST('inputDateInicial') != "" ? str_replace("-", "", $this->input->POST('inputDateInicial')) : '20220901';
        $inputDateFinal = $this->input->POST('inputDateFinal') != "" ? str_replace("-", "", $this->input->POST('inputDateFinal')) : Date('Ymd');
        $inputBodega = $this->input->POST('inputBodega') != "" ? $this->input->POST('inputBodega') : $allBodegas;

        $loadData = $this->informe->getAllCountCotizaciones($inputDateInicial, $inputDateFinal, $inputBodega);

        if (count($loadData->result()) > 0) {
            $dataResponse = array(
                'response' => 'success',
                'data' => $loadData->result(),
            );
        } else {
            $dataResponse = array(
                'response' => 'error',
                'data' => '',
            );
        }

        echo json_encode($dataResponse);
    }

    public function loadInfoValorTotalCotizaciones()
    {
        $allBodegas = '1,6,7,8';
        $inputDateInicial = $this->input->POST('inputDateInicial') != "" ? str_replace("-", "", $this->input->POST('inputDateInicial')) : '20220901';
        $inputDateFinal = $this->input->POST('inputDateFinal') != "" ? str_replace("-", "", $this->input->POST('inputDateFinal')) : Date('Ymd');
        $inputBodega = $this->input->POST('inputBodega') != "" ? $this->input->POST('inputBodega') : $allBodegas;

        $loadDataAsistidas = $this->informe->getInfoValorTotalCotizaciones($inputDateInicial, $inputDateFinal, $inputBodega);
        /* print_r($loadDataAsistidas->result()); */
        if ($loadDataAsistidas->num_rows() > 0) {
            $tbody = "";
            foreach ($loadDataAsistidas->result() as $key) {
                $tbody .= '<tr>
                <td class="text-right">' . number_format($key->total_agendado, 0, ",", ".") . '</td>
                <td class="text-right">' . number_format($key->total_facturado, 0, ",", ".") . '</td>
                <td class="text-right">' . number_format($key->items_cotizados, 0, ",", ".") . '</td>
                <td class="text-right">' . number_format($key->items_facturados, 0, ",", ".") . '</td>
                </tr>';
            }

            /* total_agendado	total_facturado	items_cotizados	items_facturados */
            $table = '<table class="table table-bordered">
             <thead>
                 <tr>
                     <th class="text-center">Total agendado</th>
                     <th class="text-center">Total facturado</th>
                     <th class="text-center">Total items cotizados</th>
                     <th class="text-center">Total items facturados</th>
                 </tr>
             </thead>
             <tbody>
                 ' . $tbody . '
             </tbody>
         </table>';

            $dataResponse = array(
                'response' => 'success',
                'data' => $table,
            );
        } else {
            $dataResponse = array(
                'response' => 'error',
                'data' => '',
            );
        }

        echo json_encode($dataResponse);
    }

    public function facturadoToCotizacion()
    {
        $allBodegas = '1,6,7,8';
        $inputDateInicial = $this->input->POST('inputDateInicial') != "" ? str_replace("-", "", $this->input->POST('inputDateInicial')) : '20220901';
        $inputDateFinal = $this->input->POST('inputDateFinal') != "" ? str_replace("-", "", $this->input->POST('inputDateFinal')) : Date('Ymd');
        $inputBodega = $this->input->POST('inputBodega') != "" ? $this->input->POST('inputBodega') : $allBodegas;

        $dataFacturadoToCotizacion = $this->informe->facturadoToCotizacion($inputDateInicial, $inputDateFinal, $inputBodega);

        if ($dataFacturadoToCotizacion->num_rows() > 0) {
            $tbody = "";
            /* id_cotizacion	numero	operacion	valor_facturado	codigo	valor_cotizado  */
            foreach ($dataFacturadoToCotizacion->result() as $key) {
                $tbody .= '<tr>
                <td class="text-left">' . $key->id_cotizacion . '</td>
                <td class="text-left">' . $key->numero . '</td>
                <td class="text-left">' . $key->operacion . '</td>
                <td class="text-right">' . number_format($key->valor_facturado, 0, ",", ".") . '</td>
                <td class="text-left">' . $key->codigo . '</td>
                <td class="text-right">' . number_format($key->valor_cotizado, 0, ",", ".") . '</td>
                </tr>';
            }
            $table = '<h3 class="text-center title">Facturado a Cotizado</h3>
            <table class="table table-bordered table-hover" id="FacturadoToCotizado">
             <thead class="thead-dark">
                 <tr>
                     <th class="text-center">Id</th>
                     <th class="text-center">Numero</th>
                     <th class="text-center">Operacion</th>
                     <th class="text-center">Valor Facturado</th>
                     <th class="text-center">Codigo</th>
                     <th class="text-center">Valor Cotizado</th>
                 </tr>
             </thead>
             <tbody>
                 ' . $tbody . '
             </tbody>
         </table>';

            $dataResponse = array(
                'response' => 'success',
                'data' => $table,
            );
        } else {
            $dataResponse = array(
                'response' => 'error',
                'data' => '',
            );
        }

        echo json_encode($dataResponse);
    }
    public function cotizacionToFacturado()
    {
        $allBodegas = '1,6,7,8';
        $inputDateInicial = $this->input->POST('inputDateInicial') != "" ? str_replace("-", "", $this->input->POST('inputDateInicial')) : '20220901';
        $inputDateFinal = $this->input->POST('inputDateFinal') != "" ? str_replace("-", "", $this->input->POST('inputDateFinal')) : Date('Ymd');
        $inputBodega = $this->input->POST('inputBodega') != "" ? $this->input->POST('inputBodega') : $allBodegas;

        $dataCotizacionToFacturado = $this->informe->cotizacionToFacturado($inputDateInicial, $inputDateFinal, $inputBodega);

        if ($dataCotizacionToFacturado->num_rows() > 0) {
            $tbody = "";
            /* id_cotizacion	numero	codigo	valor_cotizado	operacion	valor_facturado */
            foreach ($dataCotizacionToFacturado->result() as $key) {
                $tbody .= '<tr>
                <td class="text-left">' . $key->id_cotizacion . '</td>
                <td class="text-left">' . $key->numero . '</td>
                <td class="text-left">' . $key->codigo . '</td>
                <td class="text-right">' . number_format($key->valor_cotizado, 0, ",", ".") . '</td>
                <td class="text-left">' . $key->operacion . '</td>
                <td class="text-right">' . number_format($key->valor_facturado, 0, ",", ".") . '</td>
                
                </tr>';
            }
            $table = '<h3 class="text-center title">Cotizado a Facturado</h3>
            <table class="table table-bordered table-hover" id="CotizadoToFacturado">
             <thead class="thead-dark">
                 <tr>
                     <th class="text-center">Id</th>
                     <th class="text-center">Numero</th>
                     <th class="text-center">Codigo</th>
                     <th class="text-center">Valor Cotizado</th>
                     <th class="text-center">Operacion</th>
                     <th class="text-center">Valor Facturado</th>
                 </tr>
             </thead>
             <tbody>
                 ' . $tbody . '
             </tbody>
         </table>';

            $dataResponse = array(
                'response' => 'success',
                'data' => $table,
            );
        } else {
            $dataResponse = array(
                'response' => 'error',
                'data' => '',
            );
        }

        echo json_encode($dataResponse);
    }
}
