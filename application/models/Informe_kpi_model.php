<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Informe_kpi_model extends CI_Model
{
    public function getDataIndicadorKpi($ind_kpi)
    {

        switch ($ind_kpi) {
            /* case 95:
                $sql = "";
                return $this->db->query($sql);
                break; */
            case 96:
                $sql = "select * from v_kpi_ot_mtoprev";
                return $this->db->query($sql);
                break;
            case 97:
                $sql = "select * from v_kpi_ot_cargo_cliente";
                return $this->db->query($sql);
                break;
            case 98:
                $sql = "select * from v_kpi_facturacion_total";
                return $this->db->query($sql);
                break;
            case 99:
                $sql = "select * from v_kpi_facturacion_tecnico";
                return $this->db->query($sql);
                break;
            case 100:
                $sql = "SELECT * from v_kip_ot_tecnico";
                return $this->db->query($sql);
                break;
        }

    }
}

/* End of file Informe_kpi_model.php and path \application\models\Informe_kpi_model.php */