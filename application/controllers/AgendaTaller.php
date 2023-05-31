<?php

class AgendaTaller extends CI_Controller
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
            $id_usu = "";
            foreach ($userinfo->result() as $key) {
                $id_usu = $key->id_usuario;
            }
            $arr_user = array(
                'userdata' => $userinfo,
                'menus' => $allmenus,
                'pass' => $pass,
                'id_usu' => $id_usu
            );
            //abrimos la vista
            $this->load->view('AgendaTaller/index', $arr_user);
        }
    }

    public function getCitas()
    {
        $this->load->model('AgendaTallerModel');
        $bod = $this->input->GET('bod');
        $data = $this->AgendaTallerModel->getCitas($bod);
        if (count($data->result()) != 0) {
            foreach ($data->result() as $key) {
                /**COLOR CITAS AGENDA
                 * GRIS -> PENDIENTE POR AGENDAR
                 * AZUL -> PROGRAMADA
                 * AZUL CLARO -> REPROGRAMADA
                 * VERDE -> ASISTIDA
                 * ROJO -> CANCELADA
                 */
                $color = "";
                if ($key->estado_cita == "O") {
                    $color = "#BBBBBB";
                } elseif ($key->estado_cita == "A") {
                    $color = "#1C8EFF";
                }else if($key->estado_cita == "P"){
                    $color = "#0074FF";
                }else if($key->estado_cita == "R"){
                    $color = "#8CC0FF";
                }else if($key->estado_cita == "C"){
                    $color = "#FF0000";
                }else{
                    $color = "#FF00EC";
                }
                $arrCitas[] = [
                    "resourceId" => $key->tecnico,
                    "title" => $key->nombres,
                    "estado" => $key->estado,
                    "estado_cita" => $key->estado_cita,
                    "id" => $key->id,
                    "idCotizacion" => $key->id_cotizacion,
                    "idOperacion" => $key->id_operacion,
                    "idCita" => $key->id_cita,
                    "start" => str_replace(" ", "T", $key->fecha_hora_ini),
                    "end" => str_replace(" ", "T", $key->fecha_hora_fin),
                    "color" => $color
                ];
            }
        }
        echo json_encode($arrCitas);
    }

    public function getBoedegas()
    {
        $this->load->model('AgendaTallerModel');
        $dataBodegas = $this->AgendaTallerModel->getBodegas();
        if (count($dataBodegas->result()) != 0) {
            foreach ($dataBodegas->result() as $key) {
                echo '<option value="' . $key->bodega . '">' . $key->bodega . ' - ' . $key->descripcion . '</option>';
            }
        }
    }

    public function getTecnicos()
    {
        $this->load->model('AgendaTallerModel');
        $bod = $this->input->GET('bod');
        $data = $this->AgendaTallerModel->getTecnicos($bod);
        if (count($data->result()) != 0) {
            foreach ($data->result() as $key) {
                $dataTiemo = [];
                $dataHorario = $this->AgendaTallerModel->getHorarioTecnico($key->nit);
                if (count($dataHorario->result()) != 0) {
                    foreach ($dataHorario->result() as $row) {
                        $dataTiemo[] = [
                            "daysOfWeek" => [1, 2, 3, 4, 5],
                            "startTime" => $row->hora_ini,
                            "endTime" => $row->hora_fin
                        ];
                    }
                    $arrTec[] = [
                        "id" => $key->nit,
                        "title" => $key->nombres,
                        "eventColor" => "green",
                        "businessHours" => $dataTiemo
                    ];
                } else {
                    $arrTec[] = [
                        "id" => $key->nit,
                        "title" => $key->nombres,
                        "eventColor" => "green"
                    ];
                }
            }
        }
        echo json_encode($arrTec);
    }

    public function getOperacionesCotizacion()
    {
        //Cargamos los modelos
        $this->load->model('Cotizar');
        //traemos el id de la cotizacion que viene por la URL
        $idCoti = $this->input->GET('idCotizacion');
        //traemos las operaciones cargadas en dicha cotizacion
        $dataCoti = $this->Cotizar->getMttoCotiAgenda($idCoti);
        $formsHTML = "";
        if (count($dataCoti->result()) != 0) {
            foreach ($dataCoti->result() as $key) {
                $checkAutoriza = "";
                if ($key->estado == 1) {
                    $checkAutoriza = "checked";
                }
                $formsHTML .= '
            <form id="formNuevaCita' . $key->id . '">
                <div class="form-row">
                    <div class="col">
                        <label for="">Operación</label>
                        <input type="text" name="operacion' . $key->id . '" id="operacion' . $key->id . '" class="form-control" value="' . $key->mtto . '">
                    </div>
                    <div class="col">
                        <label for="">Duración(Horas)</label>
                        <input type="number" name="horaDuracion' . $key->id . '" id="horaDuracion' . $key->id . '" class="form-control" value="' . $key->cant_horas . '">
                    </div>
                    <div class="col">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="checkAutoriza' . $key->id . '" name="checkAutoriza' . $key->id . '" ' . $checkAutoriza . '>
                        <label for="checkAutoriza' . $key->id . '">
                            Autoriza
                        </label>
                    </div>
                    </div>
                    <div class="col">
                        <button type="button" id="btnConfirmar' . $key->id . '" class="btn btn-success" onclick="crearCitaOperacion(' . $key->id . ',' . $idCoti . ');">Confirmar</button>
                    </div>
                </div>
            </form>
            <hr>';
            }
            echo $formsHTML;
        } else {
            echo "No hay datos para mostrar";
        }
    }

    public function crearCitaOperacion()
    {
        //Obtenemos la fecha actual
        $dtz = new DateTimeZone("America/Bogota");
        $dt = new DateTime("now", $dtz);
        $fechaActual = date('Y-m-d') . 'T' . $dt->format('H:i:s');
        //Traemos los modelos
        $this->load->model('AgendaTallerModel');
        $this->load->model('Cotizar');
        //parametros de la vista
        $idCoti = $this->input->GET('idCotizacion');
        $idCita = $this->input->GET('idCita');
        $bodega = $this->input->GET('bod');
        $duracion = $this->input->GET('duracion');
        $user = $this->session->userdata('user');

        /**OBTENEMOS LAS OPERACIONES DE LA COTIZACION */
        $dataOperacionesCotizacion = $this->Cotizar->getMttoCotiAgenda($idCoti);

        if (count($dataOperacionesCotizacion->result()) != 0) {
            foreach ($dataOperacionesCotizacion->result() as $key) {
                
                if ($key->mtto == "MANTENIMIENTO") {
                    $duracion = "2.0";
                } elseif ($key->mtto == "ALINEACION Y BALANCEO") {
                    $duracion = "1.0";
                } elseif ($key->mtto == "AIRLIFE") {
                    $duracion = "1.0";
                } else {
                    $duracion = $key->cant_horas;
                }
                /**CALCULO DE LA DURACION */
                $partDuracion = explode(".", $duracion);
                $horas = "";
                $min = "";
                //HORAS
                if ($partDuracion[0] <= 9) {
                    $horas = "0" . $partDuracion[0];
                } else {
                    $horas = $partDuracion[0];
                }
                //MINUTOS
                $min = (($partDuracion[1] / 10) * 60);

                $dataInsert = array(
                    "id_cita" => $idCita,
                    "id_operacion" => $key->id,
                    "id_cotizacion" => $idCoti,
                    "bodega" => 0,
                    "tecnico" => 0,
                    "fecha_creacion" => $fechaActual,
                    "fecha_hora_ini" => $fechaActual,
                    "fecha_hora_fin" => $fechaActual,
                    "duracion" => ($duracion * 60),
                    "hora" => $horas,
                    "minutos" => $min,
                    "id_usu_crea" => $user,
                    "estado" => "O",
                    "descripcion_estado" => "Otro"
                );
                //print_r($dataInsert);
                $this->AgendaTallerModel->crearCitaOperacion($dataInsert);
            }
            echo "ok";
        }else{
            echo "err";
        }
    }

    public function crearCita()
    {
        //Obtenemos la fecha actual
        $dtz = new DateTimeZone("America/Bogota");
        $dt = new DateTime("now", $dtz);
        $fechaActual = date('Y-m-d') . 'T' . $dt->format('H:i:s');
        //Traemos los modelos
        $this->load->model('AgendaTallerModel');
        $this->load->model('Cotizar');
        //Obtenemos los parametros de la vista
        $idCoti = $this->input->GET('idCotizacion');
        $idOpe = $this->input->GET('idOperacion');
        $duracion = $this->input->GET('duracion');
        $nameOperacion = $this->input->GET('operacion');
        //Obtener info de la cita*/

        //Obtener info del cliente y vh
        $dataCoti = $this->Cotizar->getCotizacionById($idCoti);
        /**INFO CLIENTE */
        $nombreCliente = "";
        $nitCliente = "";
        $telCliente = "";
        /**INFO VH */
        $codVH = "";
        $placaVH = "";
        $modVH = "";
        $anioVH = "";
        if (count($dataCoti->result()) != 0) {
            foreach ($dataCoti->result() as $key) {
                /**INFO CLIENTE */
                $nombreCliente = $key->nombreCliente;
                $nitCliente = $key->nitCliente;
                $telCliente = $key->telfCliente;
                /**INFO VH */
                $codVH = $key->codigo;
                $placaVH = $key->placa;
                $modVH = $key->descripcion;
            }
        } else {
            echo "err";
            die;
        }
        //Crear Registro
        $dataInsertCita = array(
            "fecha_hora_creacion" => $fechaActual . ".000",
            "bodega" => 0,
            "fecha_hora_ini" => $fechaActual . ".000",
            "fecha_hora_fin" => $fechaActual . ".000",
            "hora" => 0,
            "minutos" => 0,
            "estado_cita" => "O",
            "codigo_veh" => $codVH,
            "placa" => $placaVH,
            "nit" => $nitCliente,
            "nombre_cliente" => $nombreCliente,
            "nit_nuevo" => 0,
            "nombre_encargado" => $nombreCliente,
            "telefonos" => $telCliente,
            "modelo_veh" => $modVH,
            "cotizacion" => $idCoti,
            "duracion_minutos" => ($duracion * 60),
            "numeroEspacios" => 0,
            "Facturado" => "NO",
            "numeroComfrimaciones" => 0,
            "operacion" => $idOpe
        );

        if ($this->AgendaTallerModel->insertCita($dataInsertCita)) {

            $id_cita = $this->db->insert_id();

            $partDuracion = explode(".", $duracion);
            $horas = "";
            $min = "";
            //HORAS
            if ($partDuracion[0] <= 9) {
                $horas = "0" . $partDuracion[0];
            } else {
                $horas = $partDuracion[0];
            }
            //MINUTOS
            $min = (($partDuracion[1] / 10) * 60);

            $data = array(
                "hora" => $horas,
                "minutos" => $min
            );
            $where = array("id_cita" => $id_cita);
            if ($this->AgendaTallerModel->updateCita($data, $where)) {
                echo "ok";
            } else {
                echo "err";
            }
        } else {
            echo "err";
        }
    }

    public function crearCitaV2()
    {
        //Obtenemos la fecha actual
        $dtz = new DateTimeZone("America/Bogota");
        $dt = new DateTime("now", $dtz);
        $fechaActual = date('Y-m-d') . 'T' . $dt->format('H:i:s');
        //Traemos los modelos
        $this->load->model('AgendaTallerModel');
        $this->load->model('Cotizar');
        //Obtenemos los parametros de la vista
        $idCoti = $this->input->GET('idCotizacion');
        //Obtener info de la cita*/
        //Validamos si existe la cotizacion en la cita
        $dataCitaCoti = $this->AgendaTallerModel->getCitaByCotizacion($idCoti);
        if (count($dataCitaCoti->result()) == 0) {
            //Obtener info del cliente y vh
            $dataCoti = $this->Cotizar->getCotizacionById($idCoti);
            /**INFO CLIENTE */
            $nombreCliente = "";
            $nitCliente = "";
            $telCliente = "";
            /**INFO VH */
            $codVH = "";
            $placaVH = "";
            $modVH = "";
            if (count($dataCoti->result()) != 0) {
                foreach ($dataCoti->result() as $key) {
                    /**INFO CLIENTE */
                    $nombreCliente = $key->nombreCliente;
                    $nitCliente = $key->nitCliente;
                    $telCliente = $key->telfCliente;
                    /**INFO VH */
                    $codVH = $key->codigo;
                    $placaVH = $key->placa;
                    $modVH = $key->descripcion;
                }
            } else {
                echo "err";
                die;
            }
            $user = $this->session->userdata('user');
            //Crear Registro
            $dataInsertCita = array(
                "fecha_hora_creacion" => $fechaActual . ".000",
                "bodega" => 0,
                "fecha_hora_ini" => $fechaActual . ".000",
                "fecha_hora_fin" => $fechaActual . ".000",
                "hora" => 0,
                "minutos" => 0,
                "estado_cita" => "O",
                "codigo_veh" => $codVH,
                "placa" => $placaVH,
                "nit" => $nitCliente,
                "nombre_cliente" => $nombreCliente,
                "nit_nuevo" => 0,
                "nombre_encargado" => $nombreCliente,
                "telefonos" => $telCliente,
                "modelo_veh" => $modVH,
                "cotizacion" => $idCoti,
                "duracion_minutos" => 0,
                "numeroEspacios" => 0,
                "Facturado" => "NO",
                "numeroComfrimaciones" => 0,
                "usuario" => $user

            );

            if ($this->AgendaTallerModel->insertCita($dataInsertCita)) {
                echo $this->db->insert_id();
            } else {
                echo "err";
            }
        } else {
            foreach ($dataCitaCoti->result() as $key) {
                echo $key->id_cita;
            }
        }
    }

    public function getCotizacionesPendientes()
    {
        $this->load->model('AgendaTallerModel');
        $idCoti = $this->input->GET('idCotizacion');
        $dataCitas = $this->AgendaTallerModel->getCitasPendientes($idCoti);
        if (count($dataCitas->result()) != 0) {
            foreach ($dataCitas->result() as $key) {
                $duracion = $key->hora . ":" . $key->minutos;
                echo '
                <div class="card card-success collapsed-card fc-event" draggable="true" data-event=\'{ "title": "' . $key->nombre_cliente . '", "duration": "' . $duracion . '","idOperacion":"' . $key->id_operacion . '","idCotizacion":"' . $key->id_cotizacion . '" }\'>
                    <div class="card-header">
                        <h3 class="card-title">' . $key->mtto . '</h3>
            
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="display: none;">
                        <p>Cliente: ' . $key->nombre_cliente . '</p>
                        <p>Duración: ' . $duracion . ' Minutos</p>
                    </div>
                    <!-- /.card-body -->
                </div>
            ';
            }
        } else {
            echo "No hay datos para mostrar";
        }
    }

    public function agendarCita()
    {
        $this->load->model('AgendaTallerModel');

        $idOperacion = $this->input->GET('idOperacion');
        $tec = $this->input->GET('tec');
        $fechaIni = $this->input->GET('fechaIni');
        $idCotizacion = $this->input->GET('idCotizacion');
        //Obtenemos los datos de la cita
        $dataCita = $this->AgendaTallerModel->getCitasPendientesOperacion($idCotizacion, $idOperacion);
        $duracionCita = 0;
        if (count($dataCita->result()) != 0) {
            foreach ($dataCita->result() as $key) {
                
                $duracionCita = $key->duracion_minutos;
            }
        }
        //generamos la fecha final
        $FechaFinal = $this->AgendaTallerModel->sumarMinutosFecha($fechaIni, $duracionCita)->result()[0]->fecha_fin;
        //print_r($FechaFinal);die;

        $where = array("cotizacion" => $idCotizacion, "operacion" => $idOperacion);
        $data = array(
            "fecha_hora_ini" => $fechaIni,
            "fecha_hora_fin" => $FechaFinal,
            "bodega" => 1,
            "estado_cita" => "P",
            "id_tecnico_Tecnico" => $tec
        );
        if ($this->AgendaTallerModel->updateCita($data, $where)) {
            echo "ok";
        } else {
            echo "err";
        }
    }

    public function agendarCitaV2()
    {
        $this->load->model('AgendaTallerModel');
        $dtz = new DateTimeZone("America/Bogota");
        $dt = new DateTime("now", $dtz);
        $fechaLimite = date('Y-m-d') . 'T19:00:00';
        $idOperacion = $this->input->GET('idOperacion');
        $tec = $this->input->GET('tec');
        $fechaIni = $this->input->GET('fechaIni');
        $idCotizacion = $this->input->GET('idCotizacion');
        $idCita = $this->input->GET('idCita');
        $bod = $this->input->GET('bod');
        //Obtenemos los datos de la cita
        $dataCita = $this->AgendaTallerModel->getCitasPendientesOperacion($idCotizacion, $idOperacion, $idCita);
        $duracionCita = 0;
        if (count($dataCita->result()) != 0) {
            foreach ($dataCita->result() as $key) {
                /* $validarFechaRango = $this->AgendaTallerModel->validarFechaRango($fechaIni,$key->fecha_hora_ini,$key->fecha_hora_fin);
                foreach ($validarFechaRango->result() as $row) {
                    if($row->val == "SI"){
                        echo "war";die;
                    }
                } */
                $valtiempoCita = $this->AgendaTallerModel->validarMinutosFinal($fechaLimite,$fechaIni,$key->duracion);
                if($valtiempoCita->resp == "SI"){
                    echo "war";die;
                }
                $duracionCita = $key->duracion;
            }
        }
        //generamos la fecha final
        $FechaFinal = $this->AgendaTallerModel->sumarMinutosFecha($fechaIni, $duracionCita)->result()[0]->fecha_fin;
        //print_r($FechaFinal);die;
        $FechaFinal = explode(".", $FechaFinal);

        $where = array(
            "id_cotizacion" => $idCotizacion,
            "id_operacion" => $idOperacion,
            "id_cita" => $idCita
        );
        $data = array(
            "fecha_hora_ini" => $fechaIni . "Z",
            "fecha_hora_fin" => $FechaFinal[0] . "Z",
            "estado" => "PE",
            "bodega" => $bod,
            "descripcion_estado" => "Pendiente",
            "tecnico" => $tec
        );
        if ($this->AgendaTallerModel->updateCitaOperacion($data, $where)) {
            echo "ok";
        } else {
            echo "err";
        }
    }

    public function getNombreEncargado()
    {
        $this->load->model('AgendaTallerModel');
        $idCita = $this->input->GET('idCita');
        $dataCita = $this->AgendaTallerModel->getCitaById($idCita);
        $nomCliente= "";
        if(count($dataCita->result()) != 0){
            foreach ($dataCita->result() as $key ) {
                $nomCliente = $key->nombre_cliente;
            }
        }
        echo $nomCliente;
    }

    public function eliminarOperacionAgenda()
    {
        $this->load->model('AgendaTallerModel');
        $idCitaOperacion = $this->input->GET('idCitaOperacion');
        $where = array("id" => $idCitaOperacion);
        if ($this->AgendaTallerModel->deleteCitaOperacion($where)) {
            echo "ok";
        } else {
            echo "err";
        }
    }

    public function minToHoras($minutes)
    {
        $time = ['days' => 0, 'hours' => 0, 'minutes' => 0];

        while ($minutes >= 60) {
    
            if ($minutes >= 1440) {
    
                $time['days']++;
                $minutes = $minutes - 1440;
            } else if ($minutes >= 60) {
    
                $time['hours']++;
                $minutes = $minutes - 60;
            }
        }
    
        $time['minutes'] = $minutes;
    
        return $time;
    }

    public function confirmarAgenda()
    {
        $this->load->model('AgendaTallerModel');
        $idCita = $this->input->GET('idCita');
        $listaChequeo = $this->input->GET('listaChequeo');
        $nomEncargado = $this->input->GET('nomEncargado');
        $bod = $this->input->GET('bod');
        $dataCitaOperaciones = $this->AgendaTallerModel->getOperacionesByCita($idCita);
        $idOperacion = 0;
        $idCotizacion = 0;
        $duracionTotal = 0;
        $nom_tecnico = "";
        if (count($dataCitaOperaciones->result()) != 0) {
            foreach ($dataCitaOperaciones->result() as $key) {
                $idOperacion = $key->id_operacion;
                $idCotizacion = $key->id_cotizacion;
                $duracionTotal += $key->duracion;
                $nom_tecnico = $key->nom_tec;
                $whereOperaciones = array("id" => $key->id);
                $dataUpdateOperaciones = array(
                    "estado" => "P",
                    "descripcion_estado" => "Programada"
                );
                if($this->validarExisteCita($key->tecnico,$key->fecha_hora_ini,str_replace(" ", "T", $key->fecha_hora_fin))){
                    $this->AgendaTallerModel->updateCitaOperacion($dataUpdateOperaciones,$whereOperaciones);
                }else{
                    echo "exist";die;
                }   
            }
            $dataDuracionTotal = $this->minToHoras($duracionTotal);
            $horasTotales = 0;
            if($dataDuracionTotal['hours'] <= 9){
                $horasTotales = "0".$dataDuracionTotal['hours'];
            }else{
                $horasTotales = $dataDuracionTotal['hours'];
            }
            $minTotales = $dataDuracionTotal['minutes'];
            $fecha_hora_ini_final = $this->AgendaTallerModel->getFechaIniMin($idCita)->fecha_hora_ini;
            $fecha_hora_fin_final = $this->AgendaTallerModel->getFechaIniMax($idCita)->fecha_hora_fin;
            /* $datafecha_hora_fin_final = $this->AgendaTallerModel->sumarMinutosFecha($fecha_hora_ini_final,$duracionTotal);
            foreach ($datafecha_hora_fin_final->result() as $key ) {
                $fecha_hora_fin_final =$key->fecha_fin;
            }*/

            $fecha_hora_fin_final = str_replace(" ", "T", $fecha_hora_fin_final); 


            $dataUpdateCita = array(
                "bodega" => $bod,
                "estado_cita" => "P",
                "fecha_hora_ini" => $fecha_hora_ini_final,
                "fecha_hora_fin" => $fecha_hora_fin_final,
                "hora" => $horasTotales,
                "minutos" => $minTotales,
                "duracion_minutos" => $duracionTotal,
                "nombre_encargado" => $nomEncargado,
                "notas" => $listaChequeo,
                "ListaChequeo" => $listaChequeo,
                "fecha_hora_ini_Original" => $fecha_hora_ini_final,
                "fecha_hora_fin_Original" => $fecha_hora_fin_final,
                "duracion_minutos_Original" => $duracionTotal,
                "hora_Original" => $horasTotales,
                "minutos_Original" => $minTotales,
                "fecha_hora_ini_Original_Agendado" => $fecha_hora_ini_final,
                "fecha_hora_fin_Original_Agendado" => $fecha_hora_fin_final,
                "duracion_minutos_Original_Agendado" => $duracionTotal,
                "hora_Original_Agendado" => $horasTotales,
                "minutos_Original_Agendado" => $minTotales,
                "FueraTiempo" => 0,
                "tipo_llamada" => 2,
                "pc" => "PC-WEB",
                "EstadoReal" => "P",
                "alistamiento" => 0,
                "rotado" => 0,
                "descripcion_bahia" => $nom_tecnico
            );
            $whereUpdateCita = array("id_cita" => $idCita);
             /* Actualizar el estado
             */
            if($this->AgendaTallerModel->updateCita($dataUpdateCita,$whereUpdateCita)){
                /**Actualizar la ListaChequeo */
                $dataListaChequeo = array("Id_Cita" => $idCita, "Solicitud" => $listaChequeo);
                if($this->AgendaTallerModel->crearListaChequeo($dataListaChequeo)){
                    //OK: Buscar por id de cotizacion los repuestos y mas manos de obra para crearlos en tall_citas_operaciones
                    //id_PLAN_MTTO Dato por confirmar
                    //1-Repuestos
                    $dataRepuestos = $this->AgendaTallerModel->getDataRepuestos($idCotizacion);
                    if(count($dataRepuestos->result()) != 0){
                        foreach ($dataRepuestos->result() as $key ) {
                            $arrTallCitasOperaciones = array(
                                "id_cita" => $idCita,
                                "codigo_operacion" => $key->codigo,
                                "tipo_operacion" => "R",
                                "cantidad" => $key->cantidad,
                                "tiempo_minutos" => 0,
                                "cliente_campana" => "",
                                "id_camp" => $idOperacion,
                                "id_Plan_Mto" => $idCotizacion
                            );
                            if(!$this->AgendaTallerModel->crear_tall_citas_operaciones($arrTallCitasOperaciones)){
                                echo "err";die;
                            }
                        }
                    }
                    //2. mano de obra
                    $dataMo = $this->AgendaTallerModel->getDataManoObra($idCotizacion);
                    if(count($dataMo->result()) != 0){
                        foreach ($dataMo->result() as $key ) {
                            if ($key->mtto == "MANTENIMIENTO") {
                                $duracion_ope = "2.0";
                            } elseif ($key->mtto == "ALINEACION Y BALANCEO") {
                                $duracion_ope = "1.0";
                            } elseif ($key->mtto == "AIRLIFE") {
                                $duracion_ope = "1.0";
                            } else {
                                $duracion_ope = $key->cant_horas;
                            }
                            $partDuracion = explode(".", $duracion_ope);
                            $horas = "";
                            $min = "";
                            //HORAS
                            $horas = $partDuracion[0] * 60;
                            //MINUTOS
                            $min = (($partDuracion[1] / 10) * 60);
                            $arrTallCitasOperaciones = array(
                                "id_cita" => $idCita,
                                //"codigo_operacion" => $key->codigo,
                                "codigo_operacion" => $key->mtto,
                                "tipo_operacion" => "T",
                                "cantidad" => "1",
                                "tiempo_minutos" => ($horas+$min),
                                "id_tall_tempario" => 123,
                                "cliente_campana" => "",
                                "id_camp" => $idOperacion,
                                "id_Plan_Mto" => $idCotizacion
                            );
                            if(!$this->AgendaTallerModel->crear_tall_citas_operaciones($arrTallCitasOperaciones)){
                                echo "err";die;
                            }
                        }
                    }
                    echo "ok";
                }else{
                    echo "err";
                }
            }else{
                echo "err";
            }
        } else {
            echo "err";
        }
    }

    public function reprogramarCita()
    {
        $this->load->model('AgendaTallerModel');
        $idCita = $this->input->GET('idCita');
        $idOperacion = $this->input->GET('idOperacion');
        $dataCita = $this->AgendaTallerModel->getCitaById($idCita);
        $dataOperacion = $this->AgendaTallerModel->getOperacionesById($idOperacion);
        if(count($dataCita->result()) != 0 && count($dataOperacion->result())!= 0){
            //TODO: Actualizar operaciones
            $arrwhereOperacion = array("id_operacion" => $idOperacion);
            if($this->AgendaTallerModel->deleteCitaOperacion($arrwhereOperacion)){
                //TODO: actualizar cita a estado O
                $arrWhereCita = array("id_cita" => $idCita);
                $arrCita = array(
                    "estado_cita" => "O",
                    "bodega"=> 0
                );
                if($this->AgendaTallerModel->updateCita($arrCita,$arrWhereCita)){
                    echo "ok";
                }else{
                    echo "err";
                }
            }else{
                echo "err";
            }
            
        }else{
            echo "err";
        }
    }

    public function updateReprogramarCita()
    {
        $this->load->model('AgendaTallerModel');
        $idCita = $this->input->GET('idCita');
        $bod = $this->input->GET('bod');

        $dataCitaOperaciones = $this->AgendaTallerModel->getOperacionesByCitaAll($idCita);
        $duracionTotal = 0;
        $nom_tecnico = "";
        if (count($dataCitaOperaciones->result()) != 0) {
            foreach ($dataCitaOperaciones->result() as $key) {
                $duracionTotal += $key->duracion;
                $nom_tecnico = $key->nom_tec;
                $whereOperaciones = array("id" => $key->id);
                if($key->estado == "PE"){
                    $dataUpdateOperaciones = array(
                        "estado" => "R",
                        "descripcion_estado" => "Reprogramada"
                    );
                    if($this->validarExisteCita($key->tecnico,$key->fecha_hora_ini,$key->fecha_hora_fin)){
                        $this->AgendaTallerModel->updateCitaOperacion($dataUpdateOperaciones,$whereOperaciones);
                    }else{
                        echo "exist";die;
                    }
                }  
            }
            $dataDuracionTotal = $this->minToHoras($duracionTotal);
            $horasTotales = 0;
            if($dataDuracionTotal['hours'] <= 9){
                $horasTotales = "0".$dataDuracionTotal['hours'];
            }else{
                $horasTotales = $dataDuracionTotal['hours'];
            }
            $minTotales = $dataDuracionTotal['minutes'];
            $fecha_hora_ini_final = $this->AgendaTallerModel->getFechaIniMin($idCita)->fecha_hora_ini;
            $fecha_hora_fin_final = $this->AgendaTallerModel->getFechaIniMax($idCita)->fecha_hora_fin;
            /* $fecha_hora_ini_final = $this->AgendaTallerModel->getFechaIniMin($idCita)->fecha_hora_ini;
            $datafecha_hora_fin_final = $this->AgendaTallerModel->sumarMinutosFecha($fecha_hora_ini_final,$duracionTotal);
            foreach ($datafecha_hora_fin_final->result() as $key ) {
                $fecha_hora_fin_final =$key->fecha_fin;
            } */

            $fecha_hora_fin_final = str_replace(" ", "T", $fecha_hora_fin_final); 

            $dataUpdateCita = array(
                "bodega" => $bod,
                "estado_cita" => "R",
                "fecha_hora_ini" => $fecha_hora_ini_final,
                "fecha_hora_fin" => $fecha_hora_fin_final,
                "hora" => $horasTotales,
                "minutos" => $minTotales,
                "duracion_minutos" => $duracionTotal,
                "FueraTiempo" => 0,
                "tipo_llamada" => 2,
                "pc" => "PC-WEB",
                "EstadoReal" => "P",
                "alistamiento" => 0,
                "rotado" => 0,
                "descripcion_bahia" => $nom_tecnico
            );
            $whereUpdateCita = array("id_cita" => $idCita);
             /* Actualizar el estado
             */
            if($this->AgendaTallerModel->updateCita($dataUpdateCita,$whereUpdateCita)){
                /**Actualizar la ListaChequeo */
                echo "ok";
            }else{
                echo "err";
            }
        } else {
            echo "err";
        }
    }

    public function cancelarCita()
    {
        $this->load->model('AgendaTallerModel');
        $idCita = $this->input->GET('idCita');
        $dataCitaOperaciones = $this->AgendaTallerModel->getOperacionesByCitaAll($idCita);
        if(count($dataCitaOperaciones->result()) != 0){
            $whereOperacion = array("id_cita" => $idCita);
            $dataOperacion = array(
                "estado" => "C",
                "descripcion_estado" => "Cancelada"
            );
            if($this->AgendaTallerModel->updateCitaOperacion($dataOperacion,$whereOperacion)){
                $dataCita = array(
                    "estado_cita" => "C",
                    "EstadoReal" => "C"
                );
                if($this->AgendaTallerModel->updateCita($dataCita,$whereOperacion)){
                    echo "ok";
                }else{
                    echo "err";
                }
            }else{
                echo "err";
            }
        }else{
            echo "err";
        }
    }

    public function validarExisteCita($tec,$fec_ini,$fec_fin)
    {
        $this->load->model('AgendaTallerModel');
        if(count($this->AgendaTallerModel->getCitaByFecha($tec,$fec_ini,str_replace(" ", "T", $fec_fin))->result()) != 0){
            return false;
        }else{
            return true;
        }
    }

    public function verInfoCitaOperacion()
    {
        $this->load->model('AgendaTallerModel');
        $idCita = $this->input->GET('idCita');
        $idOperacion = $this->input->GET('idOperacion');
        $dataOperacionCita = $this->AgendaTallerModel->InfoCitaOperacion($idCita,$idOperacion);
        if(count($dataOperacionCita->result()) != 0){
            foreach ($dataOperacionCita->result() as $key ) {
                echo '
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td class="table-active" >Usuario Registra</td>
                            <td>'.$key->nom_usu_crea.'</td>
                        </tr>
                        <tr>
                            <td class="table-active" >Tecnico</td>
                            <td>'.$key->nom_tec.'</td>
                        </tr>
                        <tr>
                            <td class="table-active" >Bodega</td>
                            <td>'.$key->nom_bodega.'</td>
                        </tr>
                        <tr>
                            <td class="table-active" >Estado</td>
                            <td>'.$key->descripcion_estado.'</td>
                        </tr>
                        <tr>
                            <td class="table-active" >Duración(Minutos)</td>
                            <td>'.$key->duracion.'</td>
                        </tr>
                    </tbody>
                </table>';
            }
        }else{
            echo "No hay datos para mostrar";
        }
    }
}
