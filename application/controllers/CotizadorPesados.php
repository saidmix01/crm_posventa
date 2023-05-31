<?php
/*
 * Controlador del modulo Cotizador de Pesados
 */
class CotizadorPesados extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuarios');
        $this->load->model('menus');
        $this->load->model('perfiles');
        $this->load->model('Cotizar');
        $this->load->model('CotizadorPesadosModel', 'Pesados');
        $this->load->model('Model_Posible_retorno');
    }


    public function index()
    {
        if (!$this->session->userdata('login')) {
            $this->session->sess_destroy();
            header("Location: " . base_url());
        } else {
            //si ya hay datos de session los carga de nuevo
            $this->load->model('usuarios');
            $this->load->model('menus');
            $this->load->model('perfiles');
            $this->load->model('Cotizar');
            $this->load->model('CotizadorPesadosModel', 'Pesados');

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

            $descripciones = $this->Pesados->getDescriptionAll();
            $tipo_retornos = $this->Model_Posible_retorno->getTiposRetornos();
            //echo $id_usu;
            $arr_user = array(
                'userdata' => $userinfo,
                'menus' => $allmenus,
                'pass' => $pass,
                'id_usu' => $id_usu,
                'descripciones' => $descripciones,
                'tipos_retornos'=>$tipo_retornos
            );
            //abrimos la vista
            $this->load->view('Cotizador/cotizacionPesados', $arr_user);
        }
    }
    /**
     * Obtiene la informaciónb del cliente y vehiculo registrados en la base de datos
     * @param placa se resive por medio de POST la placa
     * si la placa se encuentra registrada en la base de datos obtiene la información del cliente y vehiculo es enviada en el array $result en formato JSON
     * Obteniendo la clase del vehiculo podemos obtener el nombre de las revisiones que cuenta la clase del vehiculo enviada en el array $dataRevision en formato JSON
     */
    public function getInfoClient()
    {
        $this->load->model('Cotizar');
        $this->load->model('CotizadorPesadosModel', 'Pesados');
        $placa = $this->input->POST('placa');
        $result = $this->Cotizar->getClasePlaca($placa)->result();

        if (count($result) > 0) {
            $dataRevision = $this->Pesados->getRevisionClase($result[0]->clase)->result();
            $prepagado = $this->Cotizar->getMttoPrepagado($placa);

            if (!empty($prepagado->result())) {
                $isPrepagado = $prepagado->row(0)->prepagado;
            } else {
                $isPrepagado = 'N/A';
            }
            if (count($dataRevision) > 0) {
                $response = array('response' => 'success', 'data' => $result, 'data_revision' => $dataRevision, 'isPrepagado' => $isPrepagado);
                echo json_encode($response);
            } else {
                $response = array('response' => 'warning', 'data' => '');
                echo  json_encode($response);
            }
        } else {
            $response = array('response' => 'error', 'data' => '');
            echo  json_encode($response);
        }
    }
    /* 
    * Obtiene los modelos de la clase seleccionada cuando el vehiculo (placa ingresada por el usuario) no existe en la base de datos
    * @param POST $clase 
    * @return array con informacion modelos de la clase y nombre de las revisiones cargadas a esa clase de Vehículo
    */
    public function getInfoModelVh()
    {
        $this->load->model('CotizadorPesadosModel', 'Pesados');
        $clase = $this->input->POST('clase');
        $dataModel = $this->Pesados->getModelVhByClase($clase)->result();
        $dataRevision = $this->Pesados->getRevisionClase($clase)->result();

        if (count($dataModel) > 0) {
            if (count($dataRevision)) {
                $response = array('response' => 'success', 'data_model' => $dataModel, 'data_revision' => $dataRevision);
                echo json_encode($response);
            } else {
                $response = array('response' => 'success', 'data_model' => $dataModel, 'data_revision' => '');
                echo json_encode($response);
            }
        } else {
            $response = array('response' => 'error', 'data_model' => '', 'data_revision' => '');
            echo  json_encode($response);
        }
    }
    /* 
    * Funcion para obtener los repuestos y mano de obra cargados a la clase
    * @param $clase
    * @param $revision
    * @param $bodega
    */
    public function getInfoMtto()
    {
        $this->load->model('CotizadorPesadosModel', 'Pesados');
        $grupoAC = "ACDelco";
        $grupoGM = "GM";

        $clase = $this->input->POST('clase');
        $revision = $this->input->POST('revision');
        $bodega = $this->input->POST('bodega');
        $yearModel = $this->input->POST('yearModel');


        /* Script para obtener los repuestos de mantenimieto */
        $dataMttoRepuestosAC = $this->Pesados->getDataMttoRep($clase, $revision, $bodega, $grupoAC)->result();
        $dataMttoRepuestosGM = $this->Pesados->getDataMttoRep($clase, $revision, $bodega, $grupoGM)->result();
        /* Script para obtener la mano de obra del mantenimiento */
        $dataMttoToTAC = $this->Pesados->getDataMttoToT($clase, $revision, $grupoAC)->result();
        $dataMttoToTGM = $this->Pesados->getDataMttoToT($clase, $revision, $grupoGM)->result();

        $dataManoObraAC = $this->Pesados->getManoObraPesados($clase, $grupoAC, $bodega);
        $dataManoObraGM = $this->Pesados->getManoObraPesados($clase, $grupoGM, $bodega);

        $tableRepuestosAC = $this->designsPartTable($dataMttoRepuestosAC, $grupoAC, $yearModel, $dataMttoToTAC, $dataManoObraAC);
        $tableRepuestosGM = $this->designsPartTable($dataMttoRepuestosGM, $grupoGM, $yearModel, $dataMttoToTGM, $dataManoObraGM);

        if ($tableRepuestosAC['tableIsEmptyRACDelco'] == 0 && $tableRepuestosGM['tableIsEmptyRGM'] == 0 && $tableRepuestosAC['tableIsEmptyMACDelco'] == 0 && $tableRepuestosGM['tableIsEmptyMGM'] == 0) {
            $json = array(
                'response' => 'error',
                'tableAC' => $tableRepuestosAC,
                'tableGM' => $tableRepuestosGM,
            );
            echo json_encode($json);
        } else {
            $json = array(
                'response' => 'success',
                'tableAC' => $tableRepuestosAC,
                'tableGM' => $tableRepuestosGM,
            );
            echo json_encode($json);
        }
    }



    function designsPartTable($dataMttoRepuestos, $grupo, $yearModel, $dataMttoToT, $dataManoObra)
    {
        /* Repuestos */

        $tablaR = '<table class="table table-sm table-bordered table-striped table-hover">
            <thead class="thead-light">
                <tr class="text-center">
                    <th colspan="8">' . $grupo . ' - Repuestos </th>
                </tr>
                <tr class="text-center">
                    <th style="vertical-align: middle;">CÓDIGO</th>
                    <th style="vertical-align: middle;">DESCRIPCIÓN</th>
                    <th style="vertical-align: middle;">CANT</th>
                    <th style="vertical-align: middle;">CATEGORIA</th>
                    <th style="vertical-align: middle;">UDS DISP</th>
                    <th style="vertical-align: middle;">ESTADO</th>
                    <th style="vertical-align: middle;">VALOR</th>
                    <th style="vertical-align: middle;"></th>
                </tr>
            </thead>
            <tbody id="bodyReptos' . $grupo . '">';
        $seq_rpto = [];
        $tableIsEmptyR = 0;
        if (count($dataMttoRepuestos) > 0) {

            foreach ($dataMttoRepuestos as $data) {
                if ($yearModel >= $data->ano_inicio && $yearModel <= $data->ano_fin) {
                    $estado = $data->Categoria == 'Mandatorio' ? 'Autorizado' : 'No autorizado';
                    $valor = $data->Codigo == '52135420' ? 0 : number_format(ceil($data->Valor), 0, ',', '.');

                    $tablaR .= '<tr id="rpto_' . $data->seq . '_' . $grupo . '" class="rptos_pesados_' . $grupo . ' "><td>' . $data->Codigo . '</td>
                        <td>' . str_replace(',', '-', $data->descripcion)  . '</td>
                        <td class="text-center">' . $data->Cantidad . '</td>
                        <td class="text-center">' . $data->Categoria . '</td>
                        <td class="text-center">' . number_format($data->unidades_disponibles, 0, ',', '.') . '</td>
                        <td class="text-center">' . $estado . '</td>
                        <td class="text-right valor_reptos_' . $grupo . '">' . $valor . '</td>
                        <td class="text-center"><input value="1" checked type="checkbox" seqrpto="' . $data->seq . '" name="estado_' . $data->seq . '" onchange="updateStatusPrices(this,\'R\',\'' . $grupo . '\');"/></td>
                    </tr>';
                    $seq_rpto[] = $data->seq;
                    $tableIsEmptyR++;
                }
            }
        }


        $tablaR .= '</tbody></table>';

        /* Mano de Obra */
        $tablaM = '<table class="table table-sm table-bordered table-striped table-hover">
        <thead class="thead-light">
            <tr class="text-center"><th colspan="8">' . $grupo . ' - Mano de Obra</th></tr>
                <th class="text-center">CODIGO</th>
                <th class="text-center">OPERACION</th>
                <th class="text-center">CANT</th>
                <th class="text-center">HORAS</th>
                <th class="text-center">ESTADO</th>
                <th class="text-center">VALOR</th>
                <th class="text-center"></th>
            </tr>
        </thead><tbody id="bodyMano' . $grupo . '">';
        /* seq_rpto	clase	revision	codigo	cantidad	tiempo_adicional	valor	nombre_operacion	grupo */
        $tableIsEmptyM = 0;
        if (count($dataMttoToT) > 0) {
            if ($dataManoObra->num_rows() > 0) {
                /* seq	clase	operacion	horas	descrpcion	valor seq,operacion,descrpcion,horas,*/
                foreach ($dataManoObra->result() as $mano) {
                    $tablaM .= '<tr id="mano_' . $mano->seq . '_' . $grupo . '" class="mano_pesados_' . $grupo . ' ">
                    <td>' . str_replace(',', '-', $mano->operacion) . '</td>
                    <td>' . str_replace(',', '-', $mano->descrpcion) . '</td>
                    <td class="text-center"></td>
                    <td class="text-center"> ' . $mano->horas . ' </td>
                    <td class="text-center">Autorizado</td>
                    <td class="text-right">' . number_format(ceil($mano->valor), 0, ',', '.') . '</td>
                    <td class="text-center"><input disabled value="1" checked type="checkbox" seqrpto="' . $mano->seq . '" /></td>
                </tr>';
                }
                /* <td class="text-center"><input disabled value="1" checked type="checkbox" seqrpto="' . $mano->seq . '" onchange="updateStatusPrices(this,\'M\',\'' . $grupo . '\');" /></td> */
            }

            foreach ($dataMttoToT as $key) {
                if (in_array($key->seq_rpto, $seq_rpto)) {
                    $tablaM .= '<tr id="mano_' . $key->seq_rpto . '_' . $grupo . '" class="mano_pesados_' . $grupo . ' ">
                    <td>' . $key->codigo . '</td>
                    <td>' . str_replace(',', '-', $key->nombre_operacion) . '</td>
                    <td class="text-center">' . $key->cantidad . '</td>
                    <td class="text-center">' . $key->tiempo_adicional . '</td>
                    <td class="text-center">Autorizado</td>
                    <td class="text-right">' . number_format(ceil($key->valor), 0, ',', '.') . '</td>
                    <td class="text-center"><input value="1" checked type="checkbox" seqrpto="' . $key->seq_rpto . '" onchange="updateStatusPrices(this,\'M\',\'' . $grupo . '\');" /></td>
                </tr>';
                    $tableIsEmptyM++;
                }
            }
        }

        $tablaM .= '</tbody></table>';

        $tableArray = array(
            'tableR' . $grupo => $tablaR,
            'tableM' . $grupo => $tablaM,
            'tableIsEmptyR' . $grupo => $tableIsEmptyR,
            'tableIsEmptyM' . $grupo => $tableIsEmptyM,
        );

        return $tableArray;
    }

    /*  ALTER TABLE postv_cotizacion_contact
ADD tipo_vh tinyint NULL

ALTER TABLE postv_cotizacion_contact
ADD grupo_reptos varchar(10) NULL
 */

    function saveInfoCotizacion()
    {

        $this->load->model('CotizadorPesadosModel', 'Pesados');

        $usu = $this->session->userdata('user');
        $estado = $this->input->POST('estado');
        $grupo = $this->input->POST('grupo_reptos');

        /* Informacion general de la cotización */
        $fecha = date('Ymd H:i:s');
        if ($estado == 0 || $estado == 1) {
            $estadoCotizacion = 0;
            $fechaAgenda = NULL;
        } else if ($estado == 2) {
            $estadoCotizacion = 1;
            $fechaAgenda = date('Ymd H:i:s');
        }

        $dataCotizar = array(
            'nombreCliente' => $this->input->POST('nombreCliente'),
            'nitCliente' => $this->input->POST('nitCliente'),
            'telfCliente' => $this->input->POST('telfCliente'),
            'placa' => $this->input->POST('placa'),
            'clase' => $this->input->POST('clase'),
            'descripcion' => $this->input->POST('descripcion'),
            'des_modelo' => $this->input->POST('des_modelo'),
            'kilometraje_actual' => $this->input->POST('kilometraje_actual'),
            'kilometraje_estimado' => $this->input->POST('kilometraje_estimado'),
            'kilometraje_cliente' => $this->input->POST('kilometraje_cliente'),
            'bodega' => $this->input->POST('bodega'),
            'revision' => $this->input->POST('revision'),
            'emailCliente' => $this->input->POST('emailCliente'),
            'usuario' => $usu,
            'observaciones' => $this->input->POST('observaciones'),
            'fecha_creacion' => $fecha,
            'estado' => $estadoCotizacion,
            'fecha_agenda' => $fechaAgenda,
        );

        /* Insertamos info general cotizacion*/
        $idCotizacion = $this->Pesados->insertCotizacion($dataCotizar);
        $cant_mano = 0;
        $cant_reptos = 0;
        if ($idCotizacion > 0) {

            $cantReptos = $this->input->POST('CantidadDatosRepuesto');
            $cantMano = $this->input->POST('CantidadDatosMano');

            for ($i = 0; $i < $cantReptos; $i++) {
                $dataR[$i] = $this->input->POST('datosRepuesto' . $i);

                $fila = explode(",", $dataR[$i]);
                // [0] => 'CODIGO',[1] => 'DESCRIPCION', [2] => 'CANT', [3] => 'CATEGORIA', [4] => 'UND DISP',[5] => 'ESTADO', [6] => 'VALOR', [7] => 'CHECKED' 

                if ($fila[5] == 'Autorizado') {
                    $estado = 1; // 1 es Autorizado 
                } else {
                    $estado = 0; // 0 es No autorizado 
                }

                $dataInsert = array(
                    'id_cotizacion' => $idCotizacion,
                    'codigo' => $fila[0],
                    'descripcion' => $fila[1],
                    'cantidad' => $fila[2],
                    'categoria' => $fila[3],
                    'uni_disponibles' => $fila[4],
                    'valor' => str_replace('.', '', $fila[6]),
                    'estado' => $estado,
                    'grupo' => $grupo
                );

                if ($this->Pesados->addRepuestosCoti($dataInsert)) {
                    $cant_reptos++;
                }
            }
            /* Mantenimiento */
            $dataM = [];
            for ($i = 0; $i < $cantMano; $i++) {
                $dataM[$i] = $this->input->POST('datosMano' . $i);
                $fila_m = explode(",", $dataM[$i]);
                //Array ( [0] => 52135420 [1] => Engrase General [2] => 1 [3] => 0.2 [4] => Autorizado [5] => 20.600 [6] => )
                if ($fila_m[4] == 'Autorizado') {
                    $estado = 1;
                } else {
                    $estado = 0;
                }

                $dataInsert_m = array(
                    'id_cotizacion' => $idCotizacion,
                    'codigo' => $fila_m[0],
                    'descripcion' => $fila_m[1],
                    'cant_unidades' => $fila_m[2],
                    'cant_horas' => $fila_m[3],
                    'estado' => $estado,
                    'valor' => str_replace('.', '', $fila_m[5]),
                    'grupo' => $grupo
                );
                if ($this->Pesados->addMTTOCoti($dataInsert_m)) {
                    $cant_mano++;
                }
            }



            $json = array(
                'result' => 'success',
                'id_cotizacion' => $idCotizacion,
                'placa_vh' => $this->input->POST('placa'),
                'cant_reptos' => $cant_reptos,
                'cant_mano' => $cant_mano,
                'estado' => $estado
            );

            echo json_encode($json);
        } else {
            $json = array(
                'result' => 'error',
                'id_cotizacion' => '',
                'placa_vh' => $this->input->POST('placa'),
                'cant_reptos' => $cant_reptos,
                'cant_mano' => $cant_mano,
                'estado' => $estado
            );
            echo json_encode($json);
        }
    }

    function sendEmailCotizacion()
    {

        $this->load->model('CotizadorPesadosModel', 'Pesados');

        $id_cotizacion = $this->input->POST('id_cotizacion');
        $placa = $this->input->POST('placa_vh');
        $estado = $this->input->POST('estado');
        $actualizarEstado = $this->input->POST('actualizarEstado');

        $dataGeneral = $this->Pesados->getCotizacion($id_cotizacion, $placa);

        if ($actualizarEstado != "") {
            $this->Pesados->updateEstado($id_cotizacion);
        }

        /* Cargar datos para el envio de correo electronico */
        foreach ($dataGeneral->result() as $key) {
            $placa = $key->placa;
            $correoCliente = $key->emailCliente;
            $correoAsesor = $key->correo;
            $nomCliente = $key->nombreCliente;
            $bodega = $key->bodega;
        }

        $this->load->library('phpmailer_lib');
        $correo = $this->phpmailer_lib->load();
        $correo->IsSMTP();
        $correo->SMTPAuth = true;
        $correo->SMTPSecure = 'tls';
        $correo->Host = "smtp.gmail.com";
        $correo->Port = 587;
        $correo->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        /* Correo Bodega */
        /* 
        DIAZ RODRIGUEZ NELSON JOSE
        1014178302
        Giron disel

        RUEDA ROMERO IRENE ISABEL
        37579713
        Barranca


        FERRER CASTRO OSCAR FERNANDO
        1095932191
        ROMERO URBINA OSCAR EMILIO
        1094532250
        cucuta
        */
        //$correo->addBCC('copia_oculta@outlook.com');
        if ($estado == 2) {
            switch ($bodega) {
                case 11:
                    $nit = 1014178302;
                    $correo->addBCC($this->Pesados->getEmailBodega($nit));
                    break;
                case 19:
                    $nit = 37579713;
                    $correo->addBCC($this->Pesados->getEmailBodega($nit));
                    break;
                case 16:
                    $nit = 1095932191;
                    $nit2 = 1094532250;
                    $correo->addBCC($this->Pesados->getEmailBodega($nit));
                    $correo->addBCC($this->Pesados->getEmailBodega($nit2));
                    break;
                default:
                    # code...
                    break;
            }
        }

        /* Correo Receptor */
        if ($estado == 1 || $estado == 2) {
            $correo->addAddress("$correoCliente");
        }
        /* Correo del asesor - password */
        $correo->addAddress("$correoAsesor");
        /* Correo Emisor */
        $correo->Username = "no-reply@codiesel.co";
        $correo->Password = "wrtiuvlebqopeknz";

        $correo->SetFrom("no-reply@codiesel.co", "CODIESEL S.A");
        /* Asunto del correo */
        $correo->Subject = "Cotizacion Mantenimiento Pesados - #$id_cotizacion";
        /* Cuerpo del correo  */
        $mensaje = '<!DOCTYPE html>
		<html lang="en">
			<head>
				<meta charset="UTF-8" />
				<meta http-equiv="X-UA-Compatible" content="IE=edge" />
				<meta name="viewport" content="width=device-width, initial-scale=1.0" />
				<title>Cotizacion para mantenimiento</title>
			</head>
		<body>
			<div class="content" style="height: 100%; display: flex; align-items: center; justify-content: center;">
				<div id="tarjeta" class="card text-center w-75 mx-auto h-50 p-2" align="center" style="width: 100%; max-width:800px;
					background-color: #f8f9fa;
					-webkit-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);
					-moz-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);
					box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);">
						<div id="cab-tarjeta" class="card-header">
							<img src="' . base_url() . 'public/headerEmailCotizacion.png" width="100%">
						</div>
						<div class="card-body" style="text-align:justify;  ">	
							<p class="card-text" style="font-size: 1rem; line-height: 1.5; word-wrap: break-word; padding:10px">
								¡Hola ' . $nomCliente . '!
								<br><br>
								¡Gracias por darle a tu Chevrolet el servicio que merece! 
								<br><br>
								En los talleres Codiesel contarás siempre con técnicos especializados, calidez en el servicio, transparencia en nuestros procesos y la garantía sobre el trabajo realizado. 
								<br><br>
								A continuación encontrarás la cotización de los servicios solicitados.
							</p>							
						</div>
						<div class="card-footer bg-dark text-white" style="padding: 10px;
						position: relative;
						top: 20px;
						background-color: #343a40;
						color: white;
						display: flex;
						flex-direction: row;
						justify-content: space-around;">		
								<p style="font-size: 1rem; line-height: 1.5; word-wrap: break-word;">
									<a style="color:#ffff;" href="' . base_url() . 'CotizadorPesados/verPdfCotizacion?id=' . $id_cotizacion . '&placa=' . $placa . '" >Descargar cotización</a>
								</p>
		
							<div class="contacto" style="display: flex;	flex-direction: column;justify-content: space-evenly;">
							</div>
						</div>
				</div>
			</div>
		</body>
		</html>
		';

        $correo->MsgHTML($mensaje);


        if (!$correo->Send()) {
            $json = array(
                'result' => 'error',
                'id_cotizacion' => $id_cotizacion,
                'placa_vh' => $placa
            );
            echo json_encode($json);
        } else {
            $json = array(
                'result' => 'success',
                'id_cotizacion' => $id_cotizacion,
                'placa_vh' => $placa
            );
            echo json_encode($json);
        }
    }
    /* Ver la cotizacion realizada en un PDF */
    public function verPdfCotizacion()
    {
        $id = $this->input->post_get('id');
        $placa = $this->input->post_get('placa');
        $dataGeneral = $this->Pesados->getCotizacion($id, $placa);
        /* Validar si existe información del id o número de cotizacion u Placa */
        if (empty($dataGeneral->result())) {
            echo '<!DOCTYPE html>
			<html lang="en">
				<head>
					<meta charset="UTF-8" />
					<meta http-equiv="X-UA-Compatible" content="IE=edge" />
					<meta name="viewport" content="width=device-width, initial-scale=1.0" />
					<title>Cotizacion para mantenimiento</title>
				</head>
				<style>				
					/* Parpadear */
					#adv {
						position: relative;
						animation-name: example;
						animation-duration: 1s;
						animation-iteration-count: infinite;
					  }
					  
					  @keyframes example {

						from {color: white;}
  						to {color: red;}
					  }					
						
				</style>
				<script>

					window.onload = function () 
					{
						/* function parpadeo(){
							var bandera = document.getElementById("adv").style.color;
							if (bandera == "white"){
								document.getElementById("adv").style = "color: red";
								
							}else {
								document.getElementById("adv").style = "color: white"
								
							}

							
						} */
						/* setTimeout(funcionConRetraso, 3000); */
						/* setInterval(parpadeo, 50); */
					}			
				
				</script>
			<body>
				<div class="content" style="height: 100%; display: flex; align-items: center; justify-content: center;">
					<div id="tarjeta" class="card text-center w-75 mx-auto h-50" align="center" style="width: 95%;
						background-color: #f8f9fa;
						-webkit-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);
						-moz-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);
						box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);">
							<div id="cab-tarjeta" class="card-header">
								<h2 id="adv" class="card-title" style="font-weight: 700!important;font-size: 2rem;">
								<strong>
									Advertencía 
								</strong>
									
								</h2>
							</div>
							<hr>
							<div class="card-body">	
							<h2>
								<strong>
									El número de cotizacion o placa no existe...
								</strong>
							</h2>								
								<p style="font-size: 1rem; line-height: 1.5; word-wrap: break-word;">
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet dignissim dolor.
									Morbi commodo nisi eget tortor volutpat, nec facilisis ex varius. Sed eu euismod erat.
									Nulla tristique vulputate lectus, at vulputate mauris pellentesque quis. Quisque lacinia mi velit,
									et scelerisque massa dignissim et. Curabitur non tincidunt tortor, eu placerat dui.
									Nunc vitae felis non ante blandit congue pharetra id est. Maecenas nec orci libero.
									Donec vel elementum augue. Integer erat ipsum, egestas facilisis urna eget,
									tristique posuere nisi. Sed egestas augue quis velit dictum, eu pretium risus semper.
									Fusce elementum rutrum posuere. Cras vel tortor diam. Donec pharetra.
								</p>								
							</div>
							<div class="card-footer bg-dark text-white" style="padding: 10px;
							position: relative;
							top: 20px;
							background-color: #343a40;
							color: white;
							display: flex;
							flex-direction: row;
							justify-content: space-around;">		
								Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet dignissim dolor.
								Morbi commodo nisi eget tortor volutpat, nec facilisis ex varius. Sed eu euismod erat.
								Nulla tristique vulputate lectus, at vulputate mauris pellentesque quis. Quisque lacinia mi velit,
								et scelerisque massa dignissim et. Curabitur non tincidunt tortor, eu placerat dui.
								Nunc vitae felis non ante blandit congue pharetra id est. Maecenas nec orci libero.
								Donec vel elementum augue. Integer erat ipsum, egestas facilisis urna eget,
								tristique posuere nisi. Sed egestas augue quis velit dictum, eu pretium risus semper.
								Fusce elementum rutrum posuere. Cras vel tortor diam. Donec pharetra.
			
								<div class="contacto" style="display: flex;	flex-direction: column;justify-content: space-evenly;">
								</div>
							</div>
					</div>
				</div>
			</body>
			</html>';
        } else {
            $dataRepuestos = $this->Pesados->getRepuestosCoti($id);
            $dataMtto = $this->Pesados->getMttoCoti($id);
            // Información para crear el PDF
            $data = array(
                'dataGeneral' => $dataGeneral,
                'dataRepuestos' => $dataRepuestos,
                'dataMtto' => $dataMtto
            );
            /* Cargar vista web en el navegador */
            /* $this->load->view('Cotizador/pdfCotizacion', $data); */


            // Cargar libreria de PDF 
            $mpdfConfig = array(
                'mode' => 'utf-8',
                'format' => 'A4',
                // format - A4, for example, default ''
                'default_font_size' => 12,
                // font size - default 0
                'default_font' => 'overpass',
                // default font family
                'margin_left' => 5,
                // 15 margin_left
                'margin_right' => 5,
                // 15 margin right
                'margin_top' => 21,
                // 16 margin top
                'margin_bottom' => 30,
                // margin bottom contra el footer
                'margin_header' => 5,
                // 9 margin header
                'margin_footer' => 5,
                // 9 margin footer
                'orientation' => 'P' // L - landscape, P - portrait
            );
            $mpdf = new \Mpdf\Mpdf($mpdfConfig);

            //Header
            $mpdf->SetHTMLHeader('<div style="position:absolute;" class=""><img src="' . base_url() . '/public/headerCotizacion2.png" width="100%" /></div>');
            // Footer 
            $mpdf->SetHTMLFooter('
				<p style="text-align:justify; font-size:12px"><b>*</b><i>Validez de la oferta en repuestos 5 días y 30 días en mano de obra y otros. </i></p>
				<p style="text-align:justify; font-size:12px"><b>*</b><i>Garantía 1 ańo o 20.000 Km a partir de la fecha de entrega del servicio en el taller, en repuestos que no correspondan a partes de desgaste.</i></p>
				<p style="text-align:justify; font-size:12px"><b>*</b><i>Según lo estipulado en el cupón de garantía, la no sustitución de repuestos considerados mandatorios, conlleva a la renuncia del beneficio de extensión de garantía y a la garantía de fábrica. </i></p>
			');
            //body
            $usu = $this->session->userdata('user');
            if ($usu != "") {
                $html = $this->load->view('Cotizador/pdfCotizacionTallerPesados', $data, true);
            } else {
                $html = $this->load->view('Cotizador/pdfCotizacionPesados', $data, true);
            }

            $mpdf->WriteHTML($html);

            // Contraseña 
            // $mpdf->SetProtection(array(), "$placa", "C0D13S3L");

            $mpdf->Output('Cotizacion-.pdf', 'I');
            exit;
        }
    }


    public function informeCotizacionPesados()
    {
        //si ya hay datos de session los carga de nuevo
        $this->load->model('usuarios');
        $this->load->model('menus');
        $this->load->model('sedes');
        $this->load->model('perfiles');
        $this->load->model('Cotizar');

        $usu = $this->session->userdata('user');

        //obtenemos el perfil del usuario
        $perfil_user = $this->perfiles->getPerfilByUser($usu);
        /*SEDES*/
        $data_bod = $this->sedes->get_sedes_user($usu);
        $bods = "";
        foreach ($data_bod->result() as $key) {
            $bods .= $key->idsede . ",";
        }
        $bods = trim($bods, ',');

        if ($perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 20) {
            $where = "";
        } elseif ($perfil_user->id_perfil == 33) {
            $where = "where CT.bodega IN ($bods)";
        } else if ($perfil_user->id_perfil == 34) {
            $where = "where CT.bodega IN ($bods)";
        } else {
            $where = "WHERE usuario = $usu";
        }
        $dataCotizaciones = $this->Pesados->getCotizacionesAll($where);

        foreach ($dataCotizaciones->result() as $key) {
            if ($perfil_user->id_perfil == 33) {
                $btnOpcion = '<td class="noExl" scope="col"><button type="button" class="btn btn-success" onclick="verCotizacionP(' . $key->id_cotizacion . ',\'' . $key->placa . '\');"><i class="fas fa-file-invoice"></i></button></td>';
            } else {
                $btnOpcion = '<td class="noExl" scope="col"><button type="button" class="btn btn-success" onclick="verCotizacionP(' . $key->id_cotizacion . ',\'' . $key->placa . '\');"><i class="fas fa-file-invoice"></i></button></td>';
            }
            if ($perfil_user->id_perfil == 33 || $perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 34) {
                $btnEnviar = '<td class="noExl" scope="col"><button disabled type="button" class="btn btn-success"><i class="fas fa-paper-plane"></i></button></td>';
                $btnEstado = '<td class="noExl" scope="col"><button disabled type="button" class="btn btn-danger"><i class="fas fa-calendar-check"></i></button></td>';
            } else {
                $fecha = date('Y-m-d');
                if ($key->caducidad >= $fecha) {
                    if ($key->estado == 0) {

                        $btnEnviar = '<td class="noExl" scope="col">
																<button type="button" class="btn btn-success" onclick="enviarEmailCotizacionP(' . $key->id_cotizacion . ',\'' . $key->placa . '\');">
																	<i class="fas fa-paper-plane"></i>
																</button>
														</td>';

                        $btnEstado = '<td class="noExl" scope="col">
															<button type="button" class="btn btn-danger" onclick="actualizarEstadoP(' . $key->id_cotizacion . ');">
																<i class="fas fa-calendar-check"></i>
															</button>
														</td>';
                    } else {
                        $btnEnviar = '<td class="noExl" scope="col">
																<button type="button" class="btn btn-success" onclick="enviarEmailCotizacionP(' . $key->id_cotizacion . ',\'' . $key->placa . '\');">
																	<i class="fas fa-paper-plane"></i>
																</button>
														</td>';
                        $btnEstado = '<td class="noExl" scope="col"><button disabled type="button" class="btn btn-danger"><i class="fas fa-calendar-check"></i></button></td>';
                    }
                } else {
                    $btnEnviar = '<td class="noExl" scope="col">
																<button type="button" class="btn btn-success" onclick="enviarEmailCotizacionP(' . $key->id_cotizacion . ',\'' . $key->placa . '\');">
																	<i class="fas fa-paper-plane"></i>
																</button>
														</td>';
                    $btnEstado = '<td class="noExl" scope="col"><button disabled type="button" class="btn btn-danger"><i class="fas fa-calendar-check"></i></button></td>';
                }
            }
            $estadoCotizacion = 'SIN AGENDAR';
            if ($key->estado == 1) {
                $estadoCotizacion = 'AGENDADA';
            }

            echo '<tr scope="row">
				<td scope="col">' . $key->id_cotizacion . '</td>
				<td scope="col">' . $key->asesor . '</td>
				<td scope="col">' . $key->placa . '</td>
				<td scope="col">' . $key->clase . '</td>
				<td scope="col">' . $key->des_modelo . '</td>
				<td scope="col">' . $key->kilometraje_cliente . '</td>
				<td scope="col">' . $key->revision . '</td>
				<td scope="col">' . $key->NomBodega . '</td>
				<td scope="col" class="text-center">' . $estadoCotizacion . '</td>
				<td scope="col">' . $key->fecha_creacion . '</td>
				' . $btnOpcion . '
				' . $btnEnviar . '
				' . $btnEstado . '
			</tr>';
        }
    }


    public function actualizarEstado()
    {
        $id = $this->input->POST('id');

        if ($this->Pesados->actualizarEstadoCoti($id)) {
            echo 'Exito';
        } else {
            echo 'Error';
        }
    }
}
