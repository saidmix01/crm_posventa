<?php 

/**
 * Modelo creado para ejecutar consultas de la intranet comercial
 * se debe migrar tan pronto este terminada la nueva intranet
 * Hecha por said 17-11-2021
 */
class Comercial extends CI_Model
{
	public function get_info_cambio_tercero($idwf)
    {
        $sql = "SELECT a.*,t.nombres as nomterceroCambio,CONVERT(VARCHAR,a.fecha_reg) AS fecha1,
                t2.nombres as nomasesor
                FROM swcrm_historial_terceros_neg a 
                LEFT JOIN terceros t on t.id=a.idtercero  
                LEFT JOIN w_sist_usuarios usu ON usu.id_usuario=a.idasesor
                LEFT JOIN terceros t2 on t2.nit=usu.nit_usuario
                LEFT JOIN sw_usuariosede sed on sed.idusuario=a.idasesor AND sed.idsede NOT IN (3)
                WHERE a.idwf = ".$idwf."
                ORDER BY a.estado ASC";
        return $this->db->query($sql);
    }

    public function update_wf_neg($idtercero,$idwf)
    {
        $datos = array('idtercero' => $idtercero,'idregprospecto'=>0);
        $this->db->where('idworkflownegocio', $idwf);
        
        if ($this->db->update('swcrm_workflow_negocio', $datos)) {
            return true;
        }else{
            return false;
        }
    }

    public function update_hist_ter_neg($id_solicitud)
    {
        $datos = array('estado' => 1);
        $this->db->where('id', $id_solicitud);
        if ($this->db->update('swcrm_historial_terceros_neg', $datos)) {
            return true;
        }else{
            return false;
        }
    }

    public function update_swcc_bancodelead($idlead,$idagente)
    {
        $datos = array('idagente' => $idagente);
        $this->db->where('idcontactlead', $idlead);
        if ($this->db->update('swcc_bancoleads', $datos)) {
            return true;
        }else{
            return false;
        }
    }

    public function get_lead_by_agente($idlead)
    {
        $sql = $this->db->query("SELECT * FROM swcc_bancoleads l
                                WHERE idcontactlead = ".$idlead);
        if ($sql->num_rows() > 0) {
            return $sql->row();
        } else {
            return null;
        }
    }
	
}

 ?>