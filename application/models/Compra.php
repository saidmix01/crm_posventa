<?php 	

/**
 * Modelo creado para el modulo de gestion de compras
 * Creado por said el 25/03/2022
 */
class Compra extends CI_Model
{
	public function insert_solicitud($data)
    {
        if ($this->db->insert('postv_gestion_compras',$data)) {
            return true;
        }else{
            return false;
        }
    }

    public function get_solicitudes($nit='')
    {
        if ($nit == "") {
            $sql = "SELECT gc.*,us.nombres as usuario_reg,us.nit as nit_usu_reg, ga.nombres as gerente,ga.nit as nit_gerente,
                    dias_gest = DATEDIFF(DAY,CONVERT(DATE,fecha_solicitud), CONVERT(DATE,GETDATE()))
                    FROM postv_gestion_compras gc 
                    INNER JOIN terceros us ON us.nit = gc.usu_solicita
                    INNER JOIN terceros ga ON ga.nit = gc.gerente_autoriza
                    ORDER BY gc.con_factura,gc.estado ASC";
        }else{
            $sql = "SELECT gc.*,us.nombres as usuario_reg,us.nit as nit_usu_reg, ga.nombres as gerente,ga.nit as nit_gerente,
                    dias_gest = DATEDIFF(DAY,CONVERT(DATE,fecha_solicitud), CONVERT(DATE,GETDATE()))
                    FROM postv_gestion_compras gc 
                    INNER JOIN terceros us ON us.nit = gc.usu_solicita
                    INNER JOIN terceros ga ON ga.nit = gc.gerente_autoriza
                    WHERE us.nit = ".$nit."
                    ORDER BY gc.con_factura,gc.estado ASC";
        }
        //echo $sql;die;
        return $this->db->query($sql);
    }

    public function get_solicitudes_By_id($id)
    {
        $sql = "SELECT gc.*,us.nombres as usuario_reg,us.nit as nit_usu_reg, ga.nombres as gerente,ga.nit as nit_gerente FROM postv_gestion_compras gc 
                    INNER JOIN terceros us ON us.nit = gc.usu_solicita
                    INNER JOIN terceros ga ON ga.nit = gc.gerente_autoriza
                    WHERE gc.id_solicitud = ".$id."";
        //echo $sql;die;
        return $this->db->query($sql);
    }

    public function get_solicitud_aprobada($id)
    {
        $sql = "SELECT * FROM postv_cotizaciones_gest_compras WHERE id_compra = ".$id." AND estado = 1";
        return $this->db->query($sql);
    }

    public function update_solicitid($id,$data)
    {
        $this->db->where('id_solicitud', $id);
        if ($this->db->update('postv_gestion_compras', $data)) {
            return true;
        }else{
            return false;
        }
    }

    public function insert_solicitudes($data)
    {
        if ($this->db->insert('postv_cotizaciones_gest_compras',$data)) {
            return true;
        }else{
            return false;
        }
    }

    public function update_solicitid_compra($data,$id)
    {
        $this->db->where('id_coti',$id);
        if ($this->db->update('postv_cotizaciones_gest_compras',$data)) {
            return true;
        }else{
            return false;
        }
    }

    public function get_solicitudes_compra($id_soli)
    {
        $sql = "SELECT * FROM postv_cotizaciones_gest_compras WHERE id_compra = ".$id_soli." AND estado NOT IN(1,2)";
        return $this->db->query($sql);
    }

     public function get_solicitudes_compra_aprobada($id_soli)
    {
        $sql = "SELECT * FROM postv_cotizaciones_gest_compras WHERE id_compra = ".$id_soli." AND estado = 1";
        return $this->db->query($sql);
    }

    public function get_cant_solicitudes($estado)
    {
        $sql = "SELECT COUNT(*) AS n FROM postv_gestion_compras WHERE estado IN (".$estado.")";
        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    public function insert_msn_gest_compras($data)
    {
        if ($this->db->insert('postv_msn_gestion_compras',$data)) {
            return true;
        }else{
            return false;
        }
    }

    public function get_msn_solicitud_compra($id_soli='')
    {
        if ($id_soli == "") {
            $sql = "SELECT msn.*,t.nombres FROM postv_msn_gestion_compras msn INNER JOIN terceros t ON msn.nit_usu = t.nit ORDER BY msn.id_msn DESC";
        }else{
            $sql = "SELECT msn.*,t.nombres FROM postv_msn_gestion_compras msn INNER JOIN terceros t ON msn.nit_usu = t.nit WHERE solicitud_compra = ".$id_soli ." ORDER BY msn.id_msn DESC";
        }
        return $this->db->query($sql);
    }
    /* Get Solicitudes para descargar */
    public function get_solicitudes_descargar($nit='',$opt)
    {
        $opt = ($opt == 0) ? '(1,2,3,4,5)' : "($opt)";
        if ($nit == "") {
            $sql = "SELECT gc.*,us.nombres as usuario_reg,us.nit as nit_usu_reg, ga.nombres as gerente,ga.nit as nit_gerente,
                    dias_gest = DATEDIFF(DAY,CONVERT(DATE,fecha_solicitud), CONVERT(DATE,GETDATE()))
                    FROM postv_gestion_compras gc 
                    INNER JOIN terceros us ON us.nit = gc.usu_solicita
                    INNER JOIN terceros ga ON ga.nit = gc.gerente_autoriza
                    WHERE gc.estado IN $opt
                    ORDER BY gc.con_factura,gc.estado ASC";
        }else{
            $sql = "SELECT gc.*,us.nombres as usuario_reg,us.nit as nit_usu_reg, ga.nombres as gerente,ga.nit as nit_gerente,
                    dias_gest = DATEDIFF(DAY,CONVERT(DATE,fecha_solicitud), CONVERT(DATE,GETDATE()))
                    FROM postv_gestion_compras gc 
                    INNER JOIN terceros us ON us.nit = gc.usu_solicita
                    INNER JOIN terceros ga ON ga.nit = gc.gerente_autoriza
                    WHERE us.nit = ".$nit." and gc.estado IN $opt
                    ORDER BY gc.con_factura,gc.estado ASC";
        }
        //echo $sql;die;
        return $this->db->query($sql);
    }
}

 ?>