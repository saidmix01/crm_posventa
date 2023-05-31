<?php 

/**
 * Modelo del modulo contac center
 */
class Contac_Center extends CI_Model
{
	
	public function get_agentes()
	{
		$sql = "SELECT t.nombres,t.nit_real FROM w_sist_usuarios wsu
		INNER JOIN terceros t ON t.nit_real = wsu.nit_usuario
		WHERE wsu.perfil_postventa = 31
		--CAMBIAR CUANDO ESTE EN PRODUCCION
		--AND t.nit_real IN(SELECT agente FROM postv_estado_agente WHERE estado = 'Activo')";

		return $this->db->query($sql);
	}

	public function get_bodegas()
	{
		$sql = "SELECT * FROM bodegas WHERE bodega IN (1,6,7,8,11,16,19)";
		return $this->db->query($sql);
	}

	public function insert_distribucion($data)
	{
		if ($this->db->insert('postv_distribucion_agentes_cc',$data)) {
			return true;
		}else{
			return false;
		}
	}

	public function detele_distribucion($data)
	{
		$this->db->where('agente', $data['agente']);
		$this->db->where('bodega', $data['bodega']);
		$this->db->where('mes', $data['mes']);
		$this->db->where('anio', $data['anio']);
		
		if ($this->db->delete('postv_distribucion_agentes_cc')) {
			return true;
		}else{
			return false;
		}
	}

	public function update_distribucion($data,$where)
	{
		$this->db->where('agente', $where['agente']);
		$this->db->where('bodega', $where['bodega']);
		$this->db->where('mes', $where['mes']);
		$this->db->where('anio', $where['anio']);
		
		if ($this->db->update('postv_distribucion_agentes_cc',$data)) {
			return true;
		}else{
			return false;
		}
	}

	public function validar_agente($data)
	{
		$sql = "SELECT COUNT(*) AS n FROM postv_distribucion_agentes_cc
		WHERE agente = ".$data['agente']." AND bodega = ".$data['bodega']." AND mes = ".$data['mes']."
		AND anio = ".$data['anio'];
		$result = $this->db->query($sql);
		if($result->num_rows() > 0){
			return $result->row();
		}else{
			return null;
		}
	}

	public function get_distribucion_agentes($data)
	{
		$sql = "SELECT distribucion FROM postv_distribucion_agentes_cc d 
		WHERE d.agente = ".$data['agente']."
		AND d.mes = ".$data['mes']."
		AND d.anio = ".$data['anio']."
		AND d.bodega = ".$data['bodega']."";
		//echo $sql;
		$result = $this->db->query($sql);
		if($result->num_rows() > 0){
			return $result->row();
		}else{
			return null;
		}
	}

	public function validar_suma_distribucion($bod)
	{
		$sql = "SELECT SUM(distribucion) AS dist_sede FROM postv_distribucion_agentes_cc WHERE bodega = ".$bod;
		$result = $this->db->query($sql);
		if($result->num_rows() > 0){
			return $result->row();
		}else{
			return null;
		}
	}

	public function cargar_totales()
	{
		$sql = "
		SELECT SUM(d.distribucion) AS dist_sede,b.bodega FROM bodegas b
		LEFT JOIN postv_distribucion_agentes_cc d ON b.bodega = d.bodega
		WHERE b.bodega IN (1,6,7,8,11,16,18)
		GROUP BY b.bodega ORDER BY b.bodega";
		return $this->db->query($sql);
	}

	public function get_total_db_distri($data)
	{
		$sql = "SELECT COUNT(*) AS n,bodega FROM postv_maestro_posventa WHERE mes = ".$data['mes']." AND anio = ".$data['anio']." AND bodega = ".$data['bodega']." GROUP BY bodega";
		$result = $this->db->query($sql);
		if($result->num_rows() > 0){
			return $result->row();
		}else{
			return null;
		}
	}

	public function get_reg_dist($data)
	{
		$sql = "SELECT TOP ".$data['reg']." * FROM postv_maestro_posventa WHERE agente IS NULL AND bodega = ".$data['bodega']." ORDER BY NEWID()";
		return $this->db->query($sql);
	}

	public function update_maestro_posventa($agente,$id)
	{
		$this->db->where('id_mp', $id);
		$data = array('agente' => $agente);
		if ($this->db->update('postv_maestro_posventa',$data)) {
			return true;
		}else{
			return false;
		}
	}

	public function validar_estado($agente)
	{
		$sql = "SELECT count(*) AS n FROM postv_estado_agente WHERE agente=".$agente;
		$result = $this->db->query($sql);
		if($result->num_rows() > 0){
			return $result->row();
		}else{
			return null;
		}
	}

	public function get_estado($agente)
	{
		$sql = "SELECT estado FROM postv_estado_agente WHERE agente=".$agente;
		return $this->db->query($sql);
	}

	public function insert_estado($data)
	{
		if ($this->db->insert('postv_estado_agente',$data)) {
			return true;
		}else{
			return false;
		}
	}

	public function update_estado($agente,$data)
	{
		$this->db->where('agente', $agente);
		
		if ($this->db->update('postv_estado_agente',$data)) {
			return true;
		}else{
			return false;
		}
	}

	public function get_ga_actuales($agente)
	{
		$sql = "SELECT * FROM postv_maestro_posventa WHERE agente = ".$agente." AND CONVERT(DATE,fecha_estimada) BETWEEN DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0) AND GETDATE()";
		return $this->db->query($sql);
	}

	/**********************************************CONSULTAS UTILES********************************************************/
	public function get_mes_anio()
	{
		$sql = "SELECT MONTH(DATEADD(mm,1,DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))) AS mes,YEAR(DATEADD(mm,1,DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))) AS anio";
		$result = $this->db->query($sql);
		if($result->num_rows() > 0){
			return $result->row();
		}else{
			return null;
		}
	}
}

?>