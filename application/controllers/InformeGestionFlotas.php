<?php

class InformeGestionFlotas extends CI_Controller
{
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
            $this->load->view('informe_flotas/inf_gestion_flotas', $arr_user);
        }
    }

    public function load_informe()
    {
        $this->load->model('FlotasModel');

        $dataInf = $this->FlotasModel->getDataInforme();

        if (count($dataInf->result()) != 0) {
            foreach ($dataInf->result() as $key) {


                $porcenClientAntesCoord = round(($key->asistian_clientes * 100) / $key->total_clientes);
                $porcenPlacasAntCoord = round(($key->asistian_placas * 100) / $key->total_placas);

                $porcenClientDespCoord = round(($key->asisten_clientes_despues_antiguos * 100) / $key->total_clientes);
                $porcenClientNuevosCoord = round(($key->asisten_clientes_despues_nuevos * 100) / $key->total_clientes);
                $porcenPlacasDespCoord = round(($key->asisten_placas_despues * 100) / $key->total_placas);
                //clientes gestionados
                if ($key->asisten_clientes_gestionados == 0) {
                    $porcenClientDespCoordGest = 0;
                } else {
                    $porcenClientDespCoordGest = round(($key->asisten_clientes_gestionados * 100) / $key->clientes_gestionados);
                }
                if ($key->asisten_placas_gestionadas == 0) {
                    $porcenPlacasDespCoordGest = 0;
                } else {
                    $porcenPlacasDespCoordGest = round(($key->asisten_placas_gestionadas * 100) / $key->placas_gestionadas);
                }
                //clientes actualizados
                if ($key->asisten_clientes_actualizados != 0) {
                    $porcenClientDespCoordGestAct = 0;
                } else {
                    $porcenClientDespCoordGestAct = round(($key->asisten_clientes_actualizados * 100) / $key->clientes_gestionados);
                }
                if ($key->asisten_placas_actualizadas != 0) {
                    $porcenPlacasDespCoordGestAct = 0;
                } else {
                    $porcenPlacasDespCoordGestAct = round(($key->asisten_placas_actualizadas * 100) / $key->placas_gestionadas);
                }
                

                



                $valAntCoord = $this->FlotasModel->getValorAntCoord()->ventas_antes;
                $valDespCoord = $this->FlotasModel->getValorDespCoord()->ventas_despues;
                $valGestCoord = $this->FlotasModel->getValorGestionCoord()->ventas_gestion;
                $valActCoord = $this->FlotasModel->getValorActualizadoCoord()->ventas_gestion;

                echo '<div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title">GENERAL</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table style="max-width: 100%;" class="table table-sm table-borderless">
                                <tbody align="center">
                                    <tr>
                                        <td>
                                            <h5>Clientes:</h5>
                                        </td>
                                        <td>
                                            <h5>' . $key->total_clientes . '</h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Placas:</h5>
                                        </td>
                                        <td>
                                            <h5>' . $key->total_placas . '</h5>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- CARD GENERAL ADMIN FLOTAS -->
                </div>
                <div class="col-md-4"></div>
            </div>
            <div class="row">

                <div class="col-md-6">
                    <!-- CARD GENERAL ADMIN FLOTAS -->
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">ANTES DEL COORDINADOR DE FLOTAS</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table style="max-width: 100%;" class="table table-sm table-borderless">
                                <tbody align="center">
                                    <tr>
                                        <td>
                                            <h5>Clientes:</h5>
                                        </td>
                                        <td>
                                            <h5>' . $key->asistian_clientes . ' (' . $porcenClientAntesCoord . '%)</h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Placas:</h5>
                                        </td>
                                        <td>
                                            <h5>' . $key->asistian_placas . ' (' . $porcenPlacasAntCoord . '%)</h5>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer" align="center">
                            <h4>$' . number_format($valAntCoord, 0, ",", ",") . '</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- CARD GENERAL ADMIN FLOTAS -->
                    <div class="card card-outline card-warning">
                        <div class="card-header">
                            <h3 class="card-title">DESPUES DEL COORDINADOR DE FLOTAS</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table style="max-width: 100%;" class="table table-sm table-borderless">
                                <tbody align="center">
                                    <tr>
                                        <td>
                                            <h5>Clientes Antiguos:</h5>
                                        </td>
                                        <td>
                                            <h5>' . $key->asisten_clientes_despues_antiguos . ' (' . $porcenClientDespCoord . '%)</h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Clientes Nuevos:</h5>
                                        </td>
                                        <td>
                                            <h5>' . $key->asisten_clientes_despues_nuevos . ' (' . $porcenClientNuevosCoord . '%)</h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Placas:</h5>
                                        </td>
                                        <td>
                                            <h5>' . $key->asisten_placas_despues . ' (' . $porcenPlacasDespCoord . '%)</h5>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer" align="center">
                            <h4>$' . number_format($valDespCoord, 0, ",", ",") . '</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6"></div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-outline card-warning">
                        <div class="card-header">
                            <h3 class="card-title">CLIENTES ACTUALIZADOS</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table style="max-width: 100%;" class="table table-sm table-borderless">
                                <tbody align="center">
                                    <tr>
                                        <td>
                                            <h5>Clientes:</h5>
                                        </td>
                                        <td>
                                            <h5>' . $key->clientes_actualizados . ' </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Placas:</h5>
                                        </td>
                                        <td>
                                            <h5>' . $key->placas_actualizadas . ' </h5>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-outline card-warning">
                        <div class="card-header">
                            <h3 class="card-title">CLIENTES GESTIONADOS</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table style="max-width: 100%;" class="table table-sm table-borderless">
                                <tbody align="center">
                                    <tr>
                                        <td>
                                            <h5>Clientes:</h5>
                                        </td>
                                        <td>
                                            <h5>' . $key->clientes_gestionados . ' </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Placas:</h5>
                                        </td>
                                        <td>
                                            <h5>' . $key->placas_gestionadas . ' </h5>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-outline card-warning">
                        <div class="card-header">
                            <h3 class="card-title">DETALLE CLIENTES ACTUALIZADOS</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table style="max-width: 100%;" class="table table-sm table-borderless">
                                <tbody align="center">
                                    <tr>
                                        <td>
                                            <h5>Clientes:</h5>
                                        </td>
                                        <td>
                                            <h5>' . $key->asisten_clientes_actualizados . ' (' . $porcenClientDespCoordGestAct . '%)</h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Placas:</h5>
                                        </td>
                                        <td>
                                            <h5>' . $key->asisten_placas_actualizadas . ' (' . $porcenPlacasDespCoordGestAct . '%)</h5>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <!-- /.card-body -->
                     <div class="card-footer" align="center">
                        <h4>$' . number_format($valActCoord, 0, ",", ",") . '</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-outline card-warning">
                        <div class="card-header">
                            <h3 class="card-title">DETALLE CLIENTES GESTIONADOS</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table style="max-width: 100%;" class="table table-sm table-borderless">
                                <tbody align="center">
                                    <tr>
                                        <td>
                                            <h5>Clientes:</h5>
                                        </td>
                                        <td>
                                            <h5>' . $key->asisten_clientes_gestionados . ' (' . $porcenClientDespCoordGest . '%)</h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Placas:</h5>
                                        </td>
                                        <td>
                                            <h5>' . $key->asisten_placas_gestionadas . ' (' . $porcenPlacasDespCoordGest . '%)</h5>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <!-- /.card-body -->
                    <div class="card-footer" align="center">
                    <h4>$' . number_format($valGestCoord, 0, ",", ",") . '</h4>
                    </div>
                    </div>
                </div>
            </div>';
            }
        } else {
            echo "No hay datos";
        }
    }
}
