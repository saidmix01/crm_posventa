<?php
defined( 'BASEPATH' ) or exit( 'No direct script access allowed' );

class Mpc extends CI_Controller {
    public function index() {
        if ( !$this->session->userdata( 'login' ) ) {

            $this->session->sess_destroy();

            header( 'Location: ' . base_url() );
        } else {
            $this->load->model( 'usuarios' );
            $this->load->model( 'menus' );
            $this->load->model( 'perfiles' );
            //si ya hay datos de session los carga de nuevo
            $usu = $this->session->userdata( 'user' );
            //obtenemos el perfil del usuario
            $perfil_user = $this->perfiles->getPerfilByUser( $usu );
            //cargamos la informacion del usuario y la pasamos a un array
            $userinfo = $this->usuarios->getUserByName( $usu );
            $allmenus = $this->menus->getMenusByPerfil( $perfil_user->id_perfil );
            //$allsubmenus = $this->menus->getAllSubmenus();
            $id_usu = '';
            foreach ( $userinfo->result() as $key ) {
                $id_usu = $key->id_usuario;
            }

            $arr_user = array(
                'userdata' => $userinfo,
                'menus' => $allmenus,
                'id_usu' => $id_usu,
                'nit' => $usu,
                'page' => 'Informe Mantenimiento Prepagado'
            );
            //abrimos la vista
            $this->load->view( 'MPC/Informe_cotizacion', $arr_user );
        }
    }

    public function getDataInforme() {
        if ( !$this->session->userdata( 'login' ) ) {

            $this->session->sess_destroy();

            header( 'Location: ' . base_url() );
        } else {
            //Llamamos los modelos
            $this->load->model( 'Mpc_model' );
            //traemos la info de la consulta
            $dataInforme = $this->Mpc_model->getInformeMpc();
            //fecha_registro	nit	placa	des_modelo	plan_vendido	valor_mpc	val_redimido	vendido_por
            if ( $dataInforme->num_rows() > 0 ) {

                foreach ( $dataInforme->result() as $key ) {

                    $saldo_mpc =  ( $key->valor_mpc - $key->val_redimido );

                    echo '<tr>
                    <td>' . $key->fecha_registro . '</td>
                    <td>' . $key->placa . '</td>
                    <td>' . $key->des_modelo . '</td>
                    <td>' . $key->plan_vendido . '</td>
                    <td>' . number_format( $key->valor_mpc, 0, ',', '.' ) . '</td>
                    <td>' . number_format( $key->val_redimido, 0, ',', '.' ) . '</td>
                    <td>' . number_format( $saldo_mpc, 0, ',', '.' ) . '</td>
                    <td>' . $key->vendido_por . '</td>
                    </tr>';
                }
            } else {
                echo '<tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    </tr>';
            }
        }
    }
}
    