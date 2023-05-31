<?php

/**
 * Modelo de para la tabla de ausentismos by said
 */
class Ausentismos extends CI_Model
{

	public function insert_ausentismo($data)
	{
		if ($this->db->insert('postv_ausentismos', $data)) {
			return true;
		} else {
			return false;
		}
	}

	public function get_ausentismos_by_user($user)
	{
		$sql = "SELECT * FROM postv_ausentismos WHERE empleado = '" . $user . "'";
		return $this->db->query($sql);
	}

	public function get_ausentismos_by_id($id)
	{
		$sql = "SELECT *  FROM postv_ausentismos au 
				WHERE id_ausen = " . $id;
		return $this->db->query($sql);
	}

	public function get_info_ausen_correo($id)
	{
		$sql = "SELECT t.nit,t.nombres,au.fecha_ini,au.fecha_fin,au.hora_ini,au.hora_fin,au.motivo,au.sede as sede FROM postv_ausentismos au 
			INNER JOIN terceros t ON t.nit = au.empleado
			WHERE id_ausen = " . $id;
		return $this->db->query($sql);
	}

	//metodo para autorizar ausentismo
	public function autorizar_ausen($id, $nit_user_resp)
	{
		$autorizacion = 1;
		$datos = array('autorizacion' => $autorizacion, 'nit_usuario_resp' => $nit_user_resp);
		$this->db->where('id_ausen', $id);
		$this->db->where('autorizacion', 0);
		if ($this->db->update('postv_ausentismos', $datos)) {
			return true;
		} else {
			return false;
		}
	}

	//metodo para rechazar ausentismo
	public function rechazar_ausen($id, $nit_user_resp)
	{
		$autorizacion = 2;
		$datos = array('autorizacion' => $autorizacion, 'nit_usuario_resp' => $nit_user_resp);
		$this->db->where('id_ausen', $id);
		$this->db->where('autorizacion', 0);
		if ($this->db->update('postv_ausentismos', $datos)) {
			return true;
		} else {
			return false;
		}
	}

	public function get_ultimo_ausen_by_user($nit)
	{
		$result = $this->db->query("SELECT * FROM postv_ausentismos WHERE empleado='" . $nit . "' ORDER BY id_ausen DESC");
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_ausen_by_id($id)
	{
		$result = $this->db->query("SELECT * FROM postv_ausentismos WHERE id_ausen=" . $id . "");
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_empleado_jefes_by_empleado($nit)
	{
		$sql = "SELECT * FROM postv_empleado_jefe ej 
				INNER JOIN postv_jefes j ON ej.jefe = j.id_jefe
				INNER JOIN postv_empleados e ON ej.empleado = e.id_empleado
				WHERE e.nit_empleado = '" . $nit . "'";
		return $this->db->query($sql);
	}


	/*metodo para traer ausentimo de pendiendo del jefe*/
	public function taerDatosAusentismo($uso)
	{
		if ($uso == 63369607 || $this->session->userdata('perfil') == 20 || $uso == 1102384458) {
			$consulta1 = "SELECT a.empleado,a.sede,a.cargo_emp, a.area, b.nombres,a.fecha_ini,a.hora_ini, a.fecha_fin,nit_jefe,j.nit,j.nombres AS nombre_jefe, a.hora_fin,
			a.nit_usuario_resp, motivo, descripcion, 
			 Estado= CASE WHEN a.autorizacion=0 THEN 'Pendiente'
			 WHEN a.autorizacion=1 THEN 'Autorizado' ELSE 'Negado' END
			 FROM postv_ausentismos a INNER JOIN terceros b ON a.empleado=b.nit INNER JOIN terceros j ON a.nit_usuario_resp = j.nit 
			 INNER JOIN postv_jefes ON postv_jefes.nit_jefe in(63369607) ORDER BY CONVERT(DATE,a.fecha_ini) DESC";
			//echo $consulta1;die;
			return $this->db->query($consulta1);
		} else {
			$consulta2 =  "SELECT a.empleado,a.sede,a.cargo_emp, a.area, b.nombres,a.fecha_ini,a.hora_ini, a.fecha_fin,j.nit,j.nombres AS nombre_jefe, a.hora_fin,
			a.nit_usuario_resp, motivo, descripcion, 
			Estado= CASE WHEN a.autorizacion=0 THEN 'Pendiente' WHEN a.autorizacion=1 THEN 'Autorizado' ELSE 'Negado' END
			FROM postv_ausentismos a INNER JOIN terceros b ON a.empleado=b.nit INNER JOIN terceros j ON a.nit_usuario_resp = j.nit
			WHERE a.nit_usuario_resp = $uso ORDER BY CONVERT(DATE,a.fecha_ini) DESC ";

			return $this->db->query($consulta2);
		}
	}

	/*modelo para filtrar datos de la tabla ausentimso por  sede */
	public function TraerDatosSede($codigo, $sedes, $fechauno, $fechados, $areas)
	{
		$whereSedes = $sedes == "" ? '' : " AND a.sede= '" . $sedes . "'";
		$whereFechas = $fechauno == "" && $fechados == "" ? '' : " a.fecha_ini >= '" . $fechauno . "' AND a.fecha_fin <= '" . $fechados . "'";
		$whereAreas = $areas == "" ? '' : " AND a.area = '" . $areas . "'";

		$valWhere = $whereSedes != "" || $whereFechas != "" || $whereAreas != "" ? " WHERE " . $whereFechas . $whereAreas  . $whereSedes : "";
		if ($codigo == 63369607 || $this->session->userdata('perfil') == 20 || $this->session->userdata('user') == 1095816030) {
			$sql = "SELECT a.empleado,a.sede,a.area, b.nombres, a.cargo_emp,
			CONVERT(VARCHAR(10), a.fecha_ini, 103) as fecha_ini,
			CONVERT(VARCHAR(10), a.hora_ini, 108) as hora_ini, 
			CONVERT(VARCHAR(10), a.fecha_fin, 103) as fecha_fin,nit_jefe,
			j.nit,j.nombres AS nombre_jefe,
			CONVERT(VARCHAR(10), a.hora_fin, 108) as hora_fin,
			a.nit_usuario_resp, motivo, descripcion, 
			 Estado = CASE WHEN a.autorizacion=0 THEN 'Pendiente' WHEN a.autorizacion = 1 THEN 'Autorizado' ELSE 'Negado' END
			 FROM postv_ausentismos a INNER JOIN terceros b ON a.empleado=b.nit INNER JOIN terceros j ON a.nit_usuario_resp = j.nit 
			 INNER JOIN postv_jefes ON postv_jefes.nit_jefe = 63369607 " . $valWhere;
			return $this->db->query($sql);
		} else {
			$sql = "SELECT a.empleado,a.sede,a.area,a.cargo_emp, b.nombres,
				CONVERT(VARCHAR(10), a.fecha_ini, 103) as fecha_ini,
				CONVERT(VARCHAR(10), a.hora_ini, 108) as hora_ini, 
				CONVERT(VARCHAR(10), a.fecha_fin, 103) as fecha_fin,nit_jefe,j.nit,j.nombres AS nombre_jefe,
				CONVERT(VARCHAR(10), a.hora_fin, 108) as hora_fin,
				a.nit_usuario_resp, motivo, descripcion, 
				Estado= CASE WHEN a.autorizacion=0 THEN 'Pendiente de Autorizar' WHEN a.autorizacion=1 THEN 'Autorizado' ELSE 'Negado' END
				FROM postv_ausentismos a INNER JOIN terceros b ON a.empleado=b.nit INNER JOIN terceros j ON a.nit_usuario_resp = j.nit
				WHERE a.nit_usuario_resp = $codigo " . $whereAreas . $whereFechas . $whereSedes;
			return $this->db->query($sql);
		}
	}

	/*metodo para taer lista de ausentimos del dia actual */

	public function ausentimsodia($sede)
	{

		$sql = "SELECT a.id_ausen,a.confirmaporteria,a.empleado,a.confirmaporteria,a.cargo_emp,a.sede, a.area, b.nombres,a.fecha_ini,a.hora_ini, a.fecha_fin,j.nit,j.nombres AS nombre_jefe, a.hora_fin,
		a.nit_usuario_resp, motivo, descripcion, 
		CONVERT(varchar, a.fecha_ini,113) AS  fechainicial,
		CONVERT(varchar, a.fecha_fin,113) AS  fechafinal,
		 Estado= CASE WHEN a.autorizacion=0 THEN 'Pendiente'
		 WHEN a.autorizacion=1 THEN 'Autorizado' ELSE 'Negado' END
		 FROM postv_ausentismos a INNER JOIN terceros b ON a.empleado=b.nit INNER JOIN terceros j ON a.nit_usuario_resp = j.nit 
		 where a.autorizacion <> 2 AND CONVERT(date,a.fecha_ini ) = CONVERT(date,GETDATE()) AND a.sede = '" . $sede . "' AND a.confirmaporteria is NULL
		";
		return $this->db->query($sql);
	}

	public function insertarconfimacionportero($confirmar, $id)
	{

		$sql =  "UPDATE postv_ausentismos SET confirmaporteria = '" . $confirmar . "' where id_ausen = '" . $id . "' ";
		$ejecutar =  $this->db->query($sql);
		if ($ejecutar) {
			return true;
		} else {
			return false;
		}
	}

	public function diasFestivos($fecha_actual)
	{
		$sql = "SELECT CONVERT(date,fecha) as fecha, 
				fiesta= case when (domingo=1 or festivo=1) then 1 end 
				from y_calendario
				where CONVERT(date,fecha)='$fecha_actual' and (festivo=1)";
		$result =  $this->db->query($sql);
		if ($result->num_rows() >= 1) {
			return 1;
		} else {
			return 0;
		}
	}

	public function getFecha()
	{
		$sql = "declare @Existingdate datetime
		Set @Existingdate=GETDATE()
		Select CONVERT(varchar,@Existingdate,126) as [fecha_actual]";
		$result = $this->db->query($sql);
		return $result->row();
	}

	public function getListAusentismo($where = "")
	{

		$sql = "SELECT a.*, t.nombres from dbo.postv_ausentismos a 
				left join terceros t ON t.nit = a.empleado
				$where
				order by id_ausen desc";

		$result = $this->db->query($sql);

		return $result;
	}

	public function updateAusentismo($id, $data)
	{

		$this->db->where('id_ausen', $id);
		return $this->db->update('dbo.postv_ausentismos', $data);
	}

	public function deleteAusentismo($id)
	{
		$this->db->where('id_ausen', $id);
		return $this->db->delete('dbo.postv_ausentismos');
	}

	public function getAusentismoInfoTiempo($año, $mes)
	{
		$sql = "SELECT au.nit_empleado, au.nombres, motivo, 
		tiempo=case when fecha_ini=fecha_fin and dia_inicio<>'Sábado' and hora_ini=hora_ent_sem_am and hora_fin=hora_sal_sem_pm 
					then  SUM(DATEDIFF(MINUTE,hora_ent_sem_am,hora_sal_sem_am)+ DATEDIFF(MINUTE,hora_ent_sem_pm,hora_sal_sem_pm))
					
		when fecha_ini=fecha_fin and dia_inicio<>'sábado' and hora_ini>=hora_ent_sem_am and hora_fin<=hora_sal_sem_am 
					then  SUM(DATEDIFF(MINUTE,hora_ini,hora_sal_sem_am))
					
		when fecha_ini=fecha_fin and dia_inicio<>'sábado' and hora_ini>=hora_ent_sem_pm and hora_fin<=hora_sal_sem_pm 
					then  SUM(DATEDIFF(MINUTE,hora_ent_sem_pm,hora_fin))
					
		when fecha_ini=fecha_fin and dia_inicio<>'sábado' and hora_ini>=hora_ent_sem_am and hora_fin>hora_sal_sem_am and hora_fin>=hora_ent_sem_pm  and hora_fin<hora_sal_sem_pm
					then  SUM(DATEDIFF(MINUTE,hora_ini,hora_sal_sem_am)+ DATEDIFF(MINUTE,hora_ent_sem_pm,hora_fin))
					
		when fecha_ini=fecha_fin and dia_inicio<>'sábado' and hora_ini>=hora_ent_sem_am and hora_fin>hora_sal_sem_am and hora_ini>=hora_ent_sem_pm  and hora_fin<=hora_sal_sem_pm
					then DATEDIFF(MINUTE,hora_ini,hora_fin)
					
		when fecha_ini=fecha_fin and dia_inicio='Sábado' and hora_ini<hora_ent_fds and hora_fin<=hora_sal_fds 
					then  SUM(DATEDIFF(MINUTE,hora_ent_fds,hora_fin)) 
					
		when fecha_ini=fecha_fin and dia_inicio='Sábado' and hora_ini>=hora_ent_fds and hora_fin>=hora_sal_fds 
					then  SUM(DATEDIFF(MINUTE,hora_ini,hora_sal_fds))
					
		when fecha_ini=fecha_fin and dia_inicio='Sábado' and hora_ini>=hora_ent_fds and hora_fin<=hora_sal_fds 
					then SUM(DATEDIFF(MINUTE,hora_ini,hora_fin))  
					
		else  SUM(DATEDIFF(MINUTE,hora_ini,hora_fin)) 
		 end  
		FROM (
					SELECT e.nit_empleado,t.nombres,a.motivo,CONVERT(DATETIME,fecha_ini)+CONVERT(DATETIME,hora_ini) as inicio,
					CONVERT(DATETIME,fecha_fin)+CONVERT(DATETIME,hora_fin) as fin, 			 
					fecha_ini, fecha_fin, DATENAME(dw,fecha_ini) as dia_inicio,DATENAME(dw,fecha_fin) as dia_fin,hora_ini, hora_fin,
					he.hora_ent_sem_am, he.hora_ent_sem_pm,he.hora_sal_sem_am, he.hora_sal_sem_pm, hora_ent_fds, hora_sal_fds		
					FROM postv_ausentismos a 
					LEFT JOIN postv_empleados e on a.empleado=e.nit_empleado
					LEFT JOIN terceros t on e.nit_empleado=t.nit
					LEFT JOIN postv_horarios_empleados he on a.empleado=he.nit_empleado
					WHERE autorizacion=1
					---and a.empleado=91233925  
					--order by fecha_ini
					)au
					WHERE year(fecha_ini) = $año  and month(fecha_ini) = $mes
					GROUP BY au.nit_empleado,au.nombres,motivo,dia_inicio,hora_ini,hora_ent_sem_am,hora_fin,hora_ent_sem_pm,hora_ent_fds,hora_sal_fds,hora_ent_sem_am,hora_sal_sem_pm,hora_sal_sem_am, fecha_ini,fecha_fin";

		return $this->db->query($sql);
	}

	public function getAusentismoInfo($año, $mes,$whereNit)
	{

		$sql = "SELECT DISTINCT * FROM
			(SELECT e.nit_empleado,t.nombres,a.motivo,fecha_ini, fecha_fin,hora_ini, hora_fin
			---he.hora as hora_ingreso			
			FROM postv_ausentismos a 
			LEFT JOIN postv_empleados e on a.empleado=e.nit_empleado
			LEFT JOIN terceros t on e.nit_empleado=t.nit
			WHERE autorizacion=0 
			)au
		WHERE year(fecha_ini)=$año  and month(fecha_ini)=$mes $whereNit";
		return $this->db->query($sql);
	}
}
