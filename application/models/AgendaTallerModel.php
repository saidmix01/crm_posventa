<?php 

class AgendaTallerModel extends CI_Model 
{
    public function getCitas($bod)
    {
        $sql = "SELECT co.*,tec.nombres,tc.estado_cita,tc.id_cita FROM postv_citas_operaciones co
        INNER JOIN terceros tec ON tec.nit = co.tecnico
		INNER JOIN tall_citas tc ON co.id_cita = tc.id_cita
        WHERE co.bodega = $bod 
        --AND tc.estado_cita != 'C'
        ";
        //echo $sql;die;
        return $this->db->query($sql);
    }

    public function getTecnicos($bod)
    {
        $sql = "SELECT DISTINCT t.nit,t.nombres FROM w_sist_usuarios usu
        INNER JOIN terceros t ON t.nit = usu.nit_usuario
        INNER JOIN postv_perfiles pp ON pp.id_perfil = usu.perfil_postventa
        INNER JOIN sw_usuariosede us ON usu.id_usuario = us.idusuario
        INNER JOIN bodegas b ON b.bodega = us.idsede
        WHERE b.bodega IN($bod)  AND LEN(t.nit) >= 5
        AND pp.id_perfil = 24";

        return $this->db->query($sql);
    }

    public function insertCita($data)
    {
        return $this->db->insert("tall_citas",$data);
    }

    /* public function getCitasPendientes($idCotizacion)
    {
        $sql = "SELECT * FROM tall_citas tc 
        INNER JOIN postv_cotizacion_mtto cm ON cm.id = tc.operacion
        INNER JOIN postv_cotizacion_contact cc ON cc.id_cotizacion = tc.cotizacion
        WHERE estado_cita = 'O' AND tc.bodega = 0 AND tc.cotizacion = $idCotizacion";
        return $this->db->query($sql);
    } */

    public function getCitasPendientes($idCotizacion)
    {
        $sql = "SELECT tc.nombre_cliente,co.hora,co.minutos,co.id_operacion,co.id_cotizacion,cm.mtto FROM postv_citas_operaciones co
        INNER JOIN  tall_citas tc ON tc.id_cita = co.id_cita
        INNER JOIN postv_cotizacion_mtto cm ON cm.id = co.id_operacion
        WHERE co.bodega = 0 AND co.estado = 'O' AND co.id_cotizacion = $idCotizacion";
        return $this->db->query($sql);
    }
   /*  public function getCitasPendientesOperacion($idCotizacion,$idOperacion)
    {
        $sql = "SELECT * FROM tall_citas tc 
        INNER JOIN postv_cotizacion_mtto cm ON cm.id = tc.operacion
        INNER JOIN postv_cotizacion_contact cc ON cc.id_cotizacion = tc.cotizacion
        WHERE estado_cita = 'O' AND tc.operacion = $idOperacion
        AND tc.cotizacion = $idCotizacion";
        return $this->db->query($sql);
    } */
    public function getCitasPendientesOperacion($idCotizacion,$idOperacion,$idCita)
    {
        $sql = "SELECT tc.nombre_cliente,co.hora,co.minutos,co.id_operacion,co.id_cotizacion,cm.mtto,co.duracion,co.fecha_hora_ini,co.fecha_hora_fin 
        FROM postv_citas_operaciones co
        INNER JOIN  tall_citas tc ON tc.id_cita = co.id_cita
        INNER JOIN postv_cotizacion_mtto cm ON cm.id = co.id_operacion
        WHERE tc.estado_cita = 'O' AND tc.bodega = 0 
        AND co.id_cotizacion = $idCotizacion AND co.id_operacion = $idOperacion
        AND tc.id_cita = $idCita";
        return $this->db->query($sql);
    }

    public function updateCita($data,$where)
    {
        return $this->db->update("tall_citas",$data,$where);
    }

    public function sumarMinutosFecha($fecha,$minutos)
    {
        $sql = "SELECT DATEADD(MINUTE, $minutos, CONVERT(datetime,'$fecha',127)) AS 'fecha_fin'";
        return $this->db->query($sql);
    }

    public function getHorarioTecnico($nit)
    {
        $sql = "SELECT * FROM postv_horario_tecnicos WHERE tecnico = $nit";
        return $this->db->query($sql);
    }

    public function getBodegas()
    {
        return $this->db->query("SELECT * FROM bodegas WHERE bodega IN (1,6,7,8,9,11,14,16,21,22)");
    }

    public function getCitaByCotizacion($idCotizacion)
    {
        return $this->db->query("SELECT * FROM tall_citas WHERE cotizacion = $idCotizacion");
    }

    public function crearCitaOperacion($data)
    {
        return $this->db->insert("postv_citas_operaciones",$data);
    }

    public function crearListaChequeo($data)
    {
        return $this->db->insert("tall_citas_lista_chequeo",$data);
    }

    public function updateCitaOperacion($data,$where)
    {
        return $this->db->update("postv_citas_operaciones",$data,$where);
    }

    public function deleteCitaOperacion($where)
    {
        return $this->db->delete("postv_citas_operaciones",$where);
    }

    public function getOperacionesByCita($id_cita)
    {
        $sql = "SELECT co.*,t.nombres as nom_tec FROM postv_citas_operaciones co
        INNER JOIN terceros t ON co.tecnico = t.nit
        WHERE id_cita = $id_cita AND estado = 'PE'
        ORDER BY co.fecha_hora_ini DESC";
        return $this->db->query($sql);
    }

    public function getOperacionesByCitaAll($id_cita)
    {
        $sql = "SELECT co.*,t.nombres as nom_tec FROM postv_citas_operaciones co
        INNER JOIN terceros t ON co.tecnico = t.nit
        WHERE id_cita = $id_cita ORDER BY co.fecha_hora_ini DESC";
        return $this->db->query($sql);
    }

    public function validarFechaRango($fecha,$fecha_ini,$fecha_fin)
    {
        $sql = "SELECT 
        (CASE
        When CONVERT(DATE,'$fecha') BETWEEN CONVERT(DATE,'$fecha_ini') AND CONVERT(DATE,'$fecha_fin') Then 'SI'
        else 'NO' end) as val";
        return $this->db->query($sql);
    }

    public function validarHoraFinal($idOperacion,$hora)
    {
        $sql = "SELECT 
        fecha_hora_ini, 
        DATEADD(HOUR, 02, fecha_hora_ini) AS fecha_hora_fin, 
        (CASE WHEN DATEADD(HOUR, $hora, fecha_hora_ini) > CONVERT(DATETIME,'2023-05-08T18:00:00') THEN 'SI' ELSE 'NO' END) AS resp 
        FROM postv_citas_operaciones WHERE id_operacion = $idOperacion";
        $result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
    }

    public function validarMinutosFinal($fechaLimite,$fechaIni,$minutos)
    {
        $sql = "SELECT (CASE WHEN CAST(DATEADD(MINUTE, $minutos, '$fechaIni') AS TIME) >= CAST(CONVERT(datetime,'$fechaLimite') AS TIME) THEN 'SI' ELSE 'NO' END) AS resp";
        $result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
    }

    public function getCitaById($id)
    {
        return $this->db->query("SELECT * FROM tall_citas WHERE id_cita = $id");
    }
    public function getCitaByFecha($tec,$fec_ini,$fec_fin)
    {
        return $this->db->query("SELECT * FROM postv_citas_operaciones 
        WHERE CONVERT(datetime,fecha_hora_ini) BETWEEN CONVERT(datetime,'$fec_ini') AND CONVERT(datetime,'$fec_fin') 
        AND CONVERT(datetime,fecha_hora_fin) BETWEEN CONVERT(datetime,'$fec_ini') AND CONVERT(datetime,'$fec_fin') 
        AND estado NOT IN ('C','O','PE')
        AND tecnico = $tec");
    }

    public function getFechaIniMin($idCita)
    {
        $result = $this->db->query("SELECT MIN(fecha_hora_ini) AS fecha_hora_ini FROM postv_citas_operaciones WHERE id_cita = $idCita");
        if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
    }
    public function getFechaIniMax($idCita)
    {
        $result = $this->db->query("SELECT MAX(fecha_hora_fin) AS fecha_hora_fin FROM postv_citas_operaciones WHERE id_cita = $idCita");
        if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
    }

    public function getDataManoObra($idCotizacion)
    {
        $sql = "SELECT * FROM postv_cotizacion_mtto WHERE id_cotizacion = $idCotizacion";
        return $this->db->query($sql);
    }

    public function getDataRepuestos($idCotizacion)
    {
        $sql = "SELECT * FROM postv_cotizacion_repuestos WHERE id_cotizacion = $idCotizacion";
        return $this->db->query($sql);
    }

    public function crear_tall_citas_operaciones($data)
    {
        return $this->db->insert("tall_citas_operaciones",$data);
    }

    public function getOperacionesById($id)
    {
        return $this->db->query("SELECT * FROM postv_citas_operaciones WHERE id_operacion=$id");
    }

    public function deleteOperacioById($where)
    {
        return $this->db->delete("postv_citas_operaciones",$where);
    }

    public function InfoCitaOperacion($idCita,$idOperacion)
    {
        $sql = "SELECT co.*,tec.nombres as nom_tec,usc.nombres as nom_usu_crea,b.descripcion as nom_bodega
        FROM postv_citas_operaciones co 
        INNER JOIN terceros tec ON tec.nit = co.tecnico
        INNER JOIN terceros usc ON usc.nit = co.id_usu_crea
        INNER JOIN bodegas b ON b.bodega = co.bodega
        WHERE id_operacion=$idOperacion and id_cita =$idCita";
        return $this->db->query($sql);
    }
}
