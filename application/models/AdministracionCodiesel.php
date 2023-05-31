<?php

/**
 * CLASE DE MODELOS DB PARA EL MODULO ADMINISTRACION 
 * 08-10-2021 
 * BY SAID
 */
class AdministracionCodiesel extends CI_Model
{
	public function get_empleados_jefes($jefe)
	{
		$sql = "SELECT DISTINCT emp.nombres,emp.nit FROM postv_jefes j
				INNER JOIN postv_empleado_jefe ej ON ej.jefe = j.id_jefe
				INNER JOIN postv_empleados e ON e.id_empleado = ej.empleado
				INNER JOIN terceros emp ON emp.nit = e.nit_empleado
				WHERE j.nit_jefe = '" . $jefe . "'";
		return $this->db->query($sql);
	}

	public function insert_horas_extra($data)
	{
		if ($this->db->insert('postv_solicitud_hora_extra', $data)) {
			return true;
		} else {
			return false;
		}
	}

	public function get_horas_extra_by_jefe($jefe)
	{
		$sql = "SELECT * FROM postv_solicitud_hora_extra WHERE nit_jefe = '" . $jefe . "'";
		return $this->db->query($sql);
	}

	public function get_horas_extra_by_id($id)
	{
		$sql = "SELECT he.*,em.nombres FROM postv_solicitud_hora_extra he 
				INNER JOIN terceros em ON em.nit = he.nit_empleado
				WHERE id_solicitud = ".$id;
		return $this->db->query($sql);
	}

	public function get_ultima_solicitud($id)
	{
		$sql = "SELECT TOP 1 * FROM postv_solicitud_hora_extra WHERE id_solicitud = '" . $id . "' ORDER BY id_solicitud DESC";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}
	public function get_ultima_solicitud_empleado($empleado)
	{
		$sql = "SELECT TOP 1 * FROM postv_solicitud_hora_extra WHERE nit_empleado = '" . $empleado . "' ORDER BY id_solicitud DESC";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function autorizar_he($autorizacion, $id_solicitud)
	{
		$datos = array('autorizacion' => $autorizacion);
		$this->db->where('id_solicitud', $id_solicitud);
		$this->db->where('autorizacion', 0);
		if ($this->db->update('postv_solicitud_hora_extra', $datos)) {
			return true;
		} else {
			return false;
		}
	}

	public function get_mail_empleado($nit)
	{
		$sql = "SELECT distinct (t.e_mail) as mail FROM postv_empleados em
				INNER JOIN CRM_contactos t ON em.nit_empleado = t.nit
				WHERE t.nit = '" . $nit . "'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_mail_jefe($nit)
	{
		$sql = "SELECT j.correo FROM postv_jefes j
				INNER JOIN terceros t ON j.nit_jefe = t.nit
				WHERE t.nit = '" . $nit . "'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function val_jefe($nit)
	{
		$sql = "SELECT COUNT(*) AS n FROM postv_jefes WHERE nit_jefe = '" . $nit . "'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	/*metodo para traer todas las solicitudes de horas extras */
	public function listarHorasExtras($usu)
	{
		if ($usu == 63369607 || $this->session->userdata('perfil') == 20) {
			$sql = "SELECT he.id_solicitud,he.nit_jefe,he.nit_empleado,he.fecha_ini,he.hora_ini,he.hora_fin,he.fecha_solicitud,
			he.area,j.nombres AS nombrejefe,he.cargo,he.sede,he.descripcion,t.nit,t.nombres AS nombreempleado,
			autorizacion = CASE WHEN he.autorizacion=0 THEN 'Negado' WHEN he.autorizacion = 1 THEN 'Aprobado' END
			from postv_solicitud_hora_extra he INNER JOIN terceros t ON he.nit_empleado = t.nit INNER JOIN terceros j ON he.nit_jefe = j.nit
			order by fecha_solicitud desc";
			return $this->db->query($sql);
		} else {
			$sqldos = "SELECT  he.id_solicitud,he.nit_jefe,he.nit_empleado,he.fecha_ini,he.hora_ini,he.hora_fin,he.fecha_solicitud,
			he.area,j.nombres,he.cargo,he.sede,he.descripcion,t.nit,t.nombres,
			autorizacion = CASE WHEN he.autorizacion=0 THEN 'Negado' WHEN he.autorizacion = 1 THEN 'Aprobado' END
			from postv_solicitud_hora_extra he 
			INNER JOIN terceros t ON he.nit_empleado = t.nit
		    INNER JOIN terceros j ON he.nit_jefe = j.nit
			 WHERE he.nit_jefe = '" . $usu . "'
			 ";
			return $this->db->query($sqldos);
		}
	}


	public function filtroHorasExtras($codigo, $sedes, $areas,$año,$mes,$emp)
	{
		$consutAreas = $areas == "" ? '' : " AND he.area= '" . $areas . "' ";
		$consulEmpl = $emp == "" ? '' : " AND he.nit_empleado =" . $emp . "";
		$consutSedes = $sedes == "" ? '' : " AND he.sede= '" . $sedes . "' ";
		$consutfecha = $año == "" ? '' : "MONTH (he.fecha_ini)  = $mes AND YEAR(he.fecha_ini) = $año";
		$consultGeneral = $consutAreas != "" || $consutSedes != "" || $consutfecha != "" ?  $consutfecha . $consulEmpl .$consutAreas . $consutSedes  : "";

		


		if ($codigo == 63369607 || $this->session->userdata('perfil') == 20) {
			
			$consulta = " SELECT  he.id_solicitud,he.nit_jefe,he.nit_empleado,he.fecha_ini,he.hora_ini,he.hora_fin,he.fecha_solicitud,
			he.area,j.nombres as nombrejefe,he.cargo,he.sede,he.descripcion,t.nit,t.nombres as nombreempleado,
			autorizacion = CASE WHEN he.autorizacion=0 THEN 'Negado' WHEN he.autorizacion = 1 THEN 'Aprobado' END
			from postv_solicitud_hora_extra he 
			INNER JOIN terceros t ON he.nit_empleado = t.nit
		    INNER JOIN terceros j ON he.nit_jefe = j.nit
			WHERE " . $consultGeneral . 
			"order by fecha_solicitud desc";
			
			return $this->db->query($consulta);
			
		} else {
			$consu = " SELECT  he.id_solicitud,he.nit_jefe,he.nit_empleado,he.fecha_ini,he.hora_ini,he.hora_fin,he.fecha_solicitud,
			he.area,j.nombres as nombrejefe,he.cargo,he.sede,he.descripcion,t.nit,t.nombres as nombreempleado,
			autorizacion = CASE WHEN he.autorizacion=0 THEN 'Negado' WHEN he.autorizacion = 1 THEN 'Aprobado' END
			from postv_solicitud_hora_extra he 
			INNER JOIN terceros t ON he.nit_empleado = t.nit
		    INNER JOIN terceros j ON he.nit_jefe = j.nit
			WHERE he.nit_jefe=".$codigo ."AND". $consutAreas . $consutSedes . $consutfecha;
			return $this->db->query($consu);
		}
	}


	/*metodo para ver listado de horas extras para el dia  */
	public function verhorasextrasdia($sede)
	{
		$sql = "SELECT he.id_solicitud,he.nit_jefe,he.nit_empleado,he.hora_ini,he.hora_fin,he.fecha_solicitud,
		he.area,j.nombres as nombrejefe,he.cargo,he.sede,he.descripcion,t.nit,t.nombres as nombreempleado,
		CONVERT(VARCHAR,he.fecha_ini,113) as fechainical,
		autorizacion = CASE WHEN he.autorizacion = 0 THEN 'Negado' WHEN he.autorizacion = 1 THEN 'Aprobado' WHEN he.autorizacion = 2 THEN 'Espera' END
		from postv_solicitud_hora_extra he 
		INNER JOIN terceros t ON he.nit_empleado = t.nit
		INNER JOIN terceros j ON he.nit_jefe = j.nit 
		WHERE he.autorizacion <> 0 AND CONVERT(DATE,GETDATE()) = CONVERT(DATE,he.fecha_ini) AND he.sede = '" . $sede . "'
		";
		return $this->db->query($sql);
	}

	public function respuestaPorteria($id, $confirmar)
	{
		$sql =  "UPDATE postv_solicitud_hora_extra SET confirmaporteria = '" . $confirmar . "' WHERE id_solicitud = '" . $id . "' ";
		$ejecutar =  $this->db->query($sql);
		if ($ejecutar) {
			return true;
		} else {
			return false;
		}
	}

	/*********************************************** EVALUACION JEFES EMPLEADOS *****************************************************************/


	public function get_preguntas_evaluacion_jefe()
	{
		$sql = "SELECT * FROM postv_preguntas_evaluacion_jefes";
		return $this->db->query($sql);
	}

	public function insert_respuesta($data)
	{
		$this->db->insert('postv_resp_evaluacion_jefes', $data);
	}

	public function insert_encabeza_resp($data)
	{
		$this->db->insert('postv_encabeza_resp_jefes', $data);
	}

	/*********************************************** VALIDAR TERCEROS *************************************************************************/

	public function validar_tercero($nit)
	{
		$sql = "SELECT COUNT(*) AS n FROM terceros WHERE nit = '".$nit."'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	/*********************************************TIEMPO AUSENTISMOS PERSONALES **************************************************************** */

	public function valTiempoAusentismos($anio,$empleado)
	{
		return $this->db->query("SELECT * FROM postv_tiempo_ausentismos WHERE empleado = $empleado AND anio = $anio");
	}
	public function insertTiempoAsentismos($data)
	{
		return $this->db->insert('postv_tiempo_ausentismos',$data);
	}
	public function updateTiempoAusentismos($data,$where)		
	{
		return $this->db->update('postv_tiempo_ausentismos',$data,$where);
	}
}
