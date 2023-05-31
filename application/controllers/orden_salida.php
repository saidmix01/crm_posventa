<?php

class orden_salida extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuarios');
        $this->load->model('menus');
        $this->load->model('perfiles');
        $this->load->model('sedes');
        $this->load->model('model_orden_salida', 'ord_salida');
        $this->load->model('encuestas');
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

            $arr_user = array(
                'userdata' => $userinfo,
                'menus' => $allmenus,
                'pass' => $pass,
                'id_usu' => $id_usu,
            );
            //abrimos la vista
            $this->load->view('taller/crear_orden_salida', $arr_user);
        }
    }

    public function get_orden_salida_by_placa()
    {
        $placa = $this->input->POST('placa');
        $data = $this->ord_salida->get_orden_salida_by_placa($placa);

        if ($data->num_rows() > 0) {
            $tbody = '';
            foreach ($data->result() as $row) {
                /* numero_orden	bodega	placa	des_modelo	fecha */
                $buttonEstado="";
                if ($row->estado === NULL) {
                    $buttonEstado = '<button class="btn btn-success btn-lg" onclick="gestionar_reporte(' . $row->numero_orden . ',0,\'' . $row->placa . '\',' . $row->bodega . ');">&#128077;</button>
                    <button class="btn btn-danger btn-lg" onclick="gestionar_reporte(' . $row->numero_orden . ',1,\'' . $row->placa . '\',' . $row->bodega . ');">&#128078;</button>';
                }
                if ($row->encuesta !== NULL) {
                    $buttonSalida = '<button type="button" onclick="generar_orden_salida(' . $row->numero_orden . ');" class="btn btn-primary m-2">ORDEN DE SALIDA</button>';
                } else {
                    $buttonSalida = '<button disabled class="btn btn-success btn-sm m-2">ENCUESTA PENDIENTE</button>';
                }
                $tbody .= '<tr>
                    <td style="vertical-align: middle;" class="text-center"><button class="btn btn-info btn-sm orden_salida">' . $row->numero_orden . '</button></td>
                    <td style="vertical-align: middle;" class="text-center">' . $row->bodega . '</td>
                    <td style="vertical-align: middle;" class="text-center">' . $row->placa . '</td>
                    <td style="vertical-align: middle;" class="text-center">' . $row->des_modelo . '</td>
                    <td style="vertical-align: middle;" class="text-center">' . $row->fecha . '</td>
                    <td style="vertical-align: middle;" class="text-center">' . $buttonEstado . $buttonSalida  . '</td>
                </tr>';
            }

            $data_response = array(
                'response' => 'success',
                'tbody' => $tbody
            );
        } else {
            $data_response = array(
                'response' => 'error',
            );
        }

        echo json_encode($data_response);
    }

    public function insert_orden_salida()
    {
        $fecha_registro = date('Y-m-d') . 'T' . date('H:i:s');

        $data = array(
            'numero' => $this->input->POST('numero'),
            'estado' => $this->input->POST('estado'),
            'observacion' => $this->input->POST('observacion'),
            'jefe_estado' => $this->session->userdata('user'),
            'fecha_solicitud' => $fecha_registro,
            'placa_vh' => $this->input->POST('placa'),
            'bodega_o' => $this->input->POST('bodega'),
        );
        
        if ($this->ord_salida->select_orden_salida($this->input->POST('numero'))->num_rows() > 0) {

            $data_update = array(
                'estado' => $this->input->POST('estado'),
                'observacion' => $this->input->POST('observacion'),
                'jefe_estado' => $this->session->userdata('user'),
                'fecha_solicitud' => $fecha_registro,
                'placa_vh' => $this->input->POST('placa'),
                'bodega_o' => $this->input->POST('bodega'),
            );

            if ($this->ord_salida->update_orden_taller($this->input->POST('numero'), $data_update)) {
                $data_response = array(
                    'response' => 'success',
                );
            }
        } else {
            if ($this->ord_salida->insert_orden_salida($data)) {
                $data_response = array(
                    'response' => 'success',
                );
            } else {
                $data_response = array(
                    'response' => 'error',
                );
            }
        }

        echo json_encode($data_response);
    }

    public function update_orden_salida()
    {
        $fecha_registro = date('Y-m-d') . 'T' . date('H:i:s');
        $numero = $this->input->POST('numero');

        $data_numero_order = $this->ord_salida->select_orden_salida_by_numero($numero);
        if ($this->ord_salida->update_tall_encabeza_orden($numero, $fecha_registro)) {
            $dataOrder = array(
                'salida' => 1,
                'usuario_salida' => $this->session->userdata('user'),
                'placa_vh' => $data_numero_order->row(0)->placa,
                'bodega_o' => $data_numero_order->row(0)->bodega,
            );
            if ($this->ord_salida->update_orden_taller($numero, $dataOrder)) {
                $data_response = array(
                    'response' => 'success',
                );
            }
        } else {
            $data_response = array(
                'response' => 'error',
            );
        }
        echo json_encode($data_response);
    }

    public function encuesta()
    {
        $preguntas = $this->encuestas->listar_preguntas_encuesta_satisfaccion();
        $data = array('preguntas' => $preguntas);
        $this->load->view('taller/encuesta', $data);
    }

    public function encuesta_by_placa()
    {
        $placa = $this->input->POST('placa');
        $data = $this->ord_salida->encuesta_by_placa($placa);
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                /* nit_comprador	nombres	mail	celular	numero_orden	bodega	placa	des_modelo	fecha	id	numero	estado	observacion	jefe	fecha_solicitud	encuesta	propietario */
                $data_response = array(
                    'response' => 'success',
                    'numero' => $row->numero_orden,
                    'bodega' => $row->bodega,
                    'placa' => $row->placa,
                    'marca' => $row->des_marca,
                    'des_modelo' => $row->desc_modelo,
                    'color' => $row->des_color,
                    'nit_comprador' => $row->nit_comprador,
                    'nombres' => $row->nombres,
                    'mail' => $row->mail,
                    'celular' => $row->celular,
                );
            }
        } else {
            $data_response = array(
                'response' => 'error',
            );
        }

        echo json_encode($data_response);
    }

    public function updateTerceros()
    {

        $fieldNit = $this->input->POST('fieldNit');
        $fieldMailUpdate = $this->input->POST('fieldMailUpdate');
        $fieldPhoneUpdate = $this->input->POST('fieldPhoneUpdate');

        $data = array(
            'mail' => $fieldMailUpdate,
            'celular' => $fieldPhoneUpdate,
        );


        if ($this->ord_salida->executeAlter("Alter table terceros disable trigger all")) {

            if ($this->ord_salida->updateTerceros($fieldNit, $data)) {
                $array_response = array(
                    'response' => 'success',
                );
            } else {
                $array_response = array(
                    'response' => 'error',
                );
            }
            $this->ord_salida->executeAlter("Alter table terceros enable trigger all");
        } else {
            $array_response = array(
                'response' => 'error',
            );
        }

        echo json_encode($array_response);
    }


    public function insert_respuesta_encuesta()
    {
        $data = array(
            'placa' => $this->input->POST('placa'),
            'fecha' => date('Y-m-d'),
            'pregunta1' => $this->input->POST('pregunta1'),
            'pregunta2' => 0,
            'pregunta3' => $this->input->POST('pregunta4'),
            'pregunta4' => $this->input->POST('pregunta5'),
            'pregunta5' => $this->input->POST('pregunta7'),
            'fuente' => 'QR',
            'bod' => $this->input->POST('bod'),
        );

        if ($this->ord_salida->insert_encuesta_satisfaccion_qr($data)) {

            $fecha_encuesta = date('Y-m-d') . 'T' . date('H:i:s');

            $dataOrder = array(
                'encuesta' => 1,
                'propietario' => 1,
                'fecha_encuesta' => $fecha_encuesta
            );

            if ($this->ord_salida->update_orden_taller($this->input->POST('numero'), $dataOrder)) {
                $data_response = array(
                    'response' => 'success',
                );
            }

            if ($this->input->POST('pregunta1') == 6) {
                $data_t = array(
                    'concepto_7' => 2,
                );
                if ($this->ord_salida->executeAlter("Alter table terceros disable trigger all")) {
                    $fieldNit = $this->input->POST('fieldNit');
                    $this->ord_salida->updateTerceros($fieldNit, $data_t);
                    $this->ord_salida->executeAlter("Alter table terceros enable trigger all");
                    $data_response = array(
                        'response' => 'success',
                    );
                } else {
                    $data_response = array(
                        'response' => 'error',
                    );
                }
            }
        } else {
            $data_response = array(
                'response' => 'error',
            );
        }

        echo json_encode($data_response);
    }
    
    
    public function isOwnerNot()
    {
        $fecha_encuesta = date('Y-m-d') . 'T' . date('H:i:s');

        $dataOrder = array(
            'encuesta' => 0,
            'propietario' => $this->input->POST('propietario'),
            'fecha_encuesta' => $fecha_encuesta
        );

        if ($this->ord_salida->select_orden_salida($this->input->POST('numero'))->num_rows() > 0) {
            if ($this->ord_salida->update_orden_taller($this->input->POST('numero'), $dataOrder)) {
                $data_response = array(
                    'response' => 'success',
                );
            }
        } else {

            $data = array(
                'numero' => $this->input->POST('numero'),
                'encuesta' => 0,
                'propietario' => 0,
                'fecha_encuesta' => $fecha_encuesta
            );

            if ($this->ord_salida->insert_orden_salida($data)) {
                $data_response = array(
                    'response' => 'success',
                );
            } else {
                $data_response = array(
                    'response' => 'error',
                );
            }
        }

        echo json_encode($data_response);
    }


    public function vehiculos()
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

            $arr_user = array(
                'userdata' => $userinfo,
                'menus' => $allmenus,
                'pass' => $pass,
                'id_usu' => $id_usu,
            );
            //abrimos la vista
            $this->load->view('taller/salida_vh_porteria', $arr_user);
        }
    }

    public function get_orden_salida_vh_porteria()
    {

        /*SEDES*/
        $data_bod = $this->sedes->get_sedes_user($this->session->userdata('user'));
        $bods = "";
        foreach ($data_bod->result() as $key) {
            $bods .= $key->idsede . ",";
        }
        $bods = trim($bods, ',');

        $html = "";
        $data = $this->ord_salida->get_orden_salida_vh_porteria($bods);
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $html .= '<div class="col-auto" class="card">
                    <div class="card-body text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M135.2 117.4L109.1 192H402.9l-26.1-74.6C372.3 104.6 360.2 96 346.6 96H165.4c-13.6 0-25.7 8.6-30.2 21.4zM39.6 196.8L74.8 96.3C88.3 57.8 124.6 32 165.4 32H346.6c40.8 0 77.1 25.8 90.6 64.3l35.2 100.5c23.2 9.6 39.6 32.5 39.6 59.2V400v48c0 17.7-14.3 32-32 32H448c-17.7 0-32-14.3-32-32V400H96v48c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32V400 256c0-26.7 16.4-49.6 39.6-59.2zM128 288a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm288 32a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"/></svg>
                    </div>
                    <div class="card-footer">
                    <button type="button" class="btn btn-success" onclick="vh_salida(' . $row->numero . ',\'' . $row->placa_vh . '\');">' . $row->placa_vh . '</button>
                    </div>
                </div>';
            }
            $arr_response = array(
                'response' => 'success',
                'html' => $html
            );
        } else {
            $arr_response = array(
                'response' => 'error',
                'html' => '<h5 class="card-title">NO SE HAN ENCONTRADO VEHÍCULOS CON ORDEN DE SALIDA</h5>',
            );
        }

        echo json_encode($arr_response);
    }

    public function update_salida_vh()
    {
        $fecha_registro = date('Y-m-d') . 'T' . date('H:i:s');
        $data = array(
            'fecha_salida' => $fecha_registro,
        );

        if ($this->ord_salida->update_orden_taller($this->input->POST('numero'), $data)) {
            $array_response = array('response' => 'success');
        } else {
            $array_response = array('response' => 'error');
        }

        echo json_encode($array_response);
    }
}
