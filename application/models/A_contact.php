<?php

use Mpdf\Tag\IndexInsert;

/**
 * Modelo del modulo Auditoria_contact
 */
class A_contact extends CI_Model
{
	/* Obtener la información de la evaluacion o auditoria */
	public function getAuditoriaAll()
	{
		$sql = "SELECT ai.nombres, ii.concepto, io.observacion from dbo.postv_auditoria_indicador ai
                LEFT JOIN postv_auditoria_indicador_item ii ON ii.id_indicador=ai.id
                LEFT JOIN postv_auditoria_item_obs io ON io.id_item=ii.id";
		return $this->db->query($sql);
	}
	/* Obtener los usuarios o agentes de contact center para evaluar */
	public function getAllUserAgente()
	{
		$sql = "SELECT u.id_usuario, p.nom_perfil, t.nombres, u.usuario, t.nit, u.estado 
				FROM w_sist_usuarios u 
				INNER JOIN terceros t ON t.nit = u.nit_usuario 
				LEFT JOIN postv_perfiles p ON p.id_perfil = u.perfil_postventa
				where p.id_perfil=31";
		return $this->db->query($sql);
	}
	/* Obtenemos los indicadores: */
	public function getIndicador()
	{
		$sql = "SELECT * FROM dbo.postv_auditoria_indicador";
		return $this->db->query($sql);
	}
	/* Obtenemos los item por indicador */
	public function getItem($id_indicador)
	{
		$sql = "SELECT * FROM dbo.postv_auditoria_indicador_item
				WHERE id_indicador = $id_indicador";
		return $this->db->query($sql);
	}
	/* Obtenemos los item por indicador y Estado Habilitado */
	public function getItemHabilitados($id_indicador)
	{
		$sql = "SELECT * FROM dbo.postv_auditoria_indicador_item
				WHERE id_indicador = $id_indicador and estado = 2";
		return $this->db->query($sql);
	}
	/* Obtener la cantidad de preguntas getCantPreguntas */
	public function getCantPreguntas()
	{
		$sql = "SELECT COUNT(*) as cantidad FROM dbo.postv_auditoria_indicador_item";
		$respuesta = $this->db->query($sql);
		return $respuesta->row()->cantidad;
	}
	/* Insertar auditoria X agente */
	public function insertAuditoria($nitAgente,$nitEncargado)
	{
		$sql = "INSERT INTO dbo.postv_auditoria_agente (nit_agente,fecha_creacion,nit_encargado) VALUES ($nitAgente,SYSDATETIME(),$nitEncargado)";
		$result = $this->db->query($sql);
		if ($result) {
			return $this->db->insert_id();
		} else {
			return 0;
		}
	}
	/* Update Respuestas Auditoria X ID */
	public function updateResp($id_auditoria, $id_item, $opt)
	{

		$data = array(
			"$id_item" => $opt,
		);

		$this->db->where('id_auditoria', $id_auditoria);

		if ($this->db->update('postv_auditoria_agente', $data)) {
			return true;
		} else {
			return false;
		}
	}
	/* Finalizar la auditoria X ID */
	public function finalizarAuditoria($id_auditoria, $puntuacion,$obsAuditor)
	{
		$sql = "UPDATE postv_auditoria_agente 
				SET puntuacion=$puntuacion, fecha_finalizacion=SYSDATETIME(),observaciones='$obsAuditor' 
				WHERE id_auditoria = $id_auditoria";

		return $this->db->query($sql);
	}
	/* Funcion para obtener todas la auditorías realizadas */
	public function getAuditoriaAgentesAll($where)
	{

		$sql = "SELECT ae.id_auditoria, ae.nit_agente, t.nombres, ae.fecha_creacion, ae.fecha_finalizacion,ae.puntuacion,ae.compromiso
				FROM dbo.postv_auditoria_agente ae
				INNER JOIN terceros t ON t.nit = ae.nit_agente
				$where
				order by id_auditoria desc";
		return $this->db->query($sql);
	}
	/* Función para obtener la auditoria por ID */
	public function getAuditoriaId($id_auditoria)
	{
		$sql = "SELECT ae.id_auditoria, ae.nit_agente, t.nombres, ae.fecha_creacion, ae.fecha_finalizacion,ae.puntuacion, ae.*
				FROM dbo.postv_auditoria_agente ae
				INNER JOIN terceros t ON t.nit = ae.nit_agente
				WHERE ae.id_auditoria= $id_auditoria
				";
		return $this->db->query($sql);
	}
	/* Función para agregar archivos a la auditoria X ID */
	public function addFilesAuditoria($id_auditoria, $url_file)
	{
		$sql = "INSERT INTO dbo.postv_auditoria_agente_files (id_auditoria,url_file,fecha_registro) VALUES ($id_auditoria, '$url_file',SYSDATETIME())";
		return  $this->db->query($sql);
	}
	/* Funcion para obtener los archivos agregados  a la auditoria por ID */
	public function getAllFilesAuditoriaId($id_auditoria)
	{
		$sql = "SELECT * FROM dbo.postv_auditoria_agente_files
		where id_auditoria = $id_auditoria";
		return  $this->db->query($sql);
	}
	/* Funcion para Actualizar los indicadores */
	public function updateIndicadores($id_indicador, $puntos)
	{

		$sql = "UPDATE postv_auditoria_indicador 
				SET puntuacion = $puntos
				WHERE id_indicador =$id_indicador";

		if ($this->db->query($sql)) {
			return  true;
		} else {
			return  false;
		}
	}
	/* Insertar un nuevo indicador */
	public function insertIndicador($nombre, $puntos)
	{

		$sql = "INSERT INTO postv_auditoria_indicador 
				(nombres,puntuacion,estado)
				VALUES ('$nombre',$puntos,2);";
		if ($this->db->query($sql)) {
			return  true;
		} else {
			return  false;
		}
	}
	/* Insertar un nuevo item */
	public function insertItemXind($id_indicador, $concepto)
	{

		$sql = "INSERT INTO postv_auditoria_indicador_item 
				(id_indicador,concepto,estado)
				VALUES ($id_indicador,'$concepto',2);";
		if ($this->db->query($sql)) {
			return  $this->db->insert_id();
		} else {
			return  0;
		}
	}
	/* Agregar una nueva columna a las preguntas o auditoria x Agente */
	public function addPreguntaAuditoria($id_item)
	{

		$columna = "item_$id_item";
		$sql = "ALTER TABLE postv_auditoria_agente 
				ADD $columna tinyint NULL";
		if ($this->db->query($sql)) {
			return  true;
		} else {
			return  false;
		}
	}
	/* Función para obtener las observaciones por item */
	public function getObsXitem($id_item)
	{
		$sql = "SELECT * from postv_auditoria_item_obs
				WHERE id_item = $id_item";
		return $this->db->query($sql);
	}
	/* Insertar una nueva observacion */
	public function insertObsXitem($id_item, $obs)
	{

		$sql = "INSERT INTO postv_auditoria_item_obs 
				(id_item,observacion,estado)
				VALUES ($id_item,'$obs',2);";
		if ($this->db->query($sql)) {
			return  true;
		} else {
			return  false;
		}
	}
	/* ACtualizar el estado del indicador */
	public function estadoIndicador($id_indicador, $estado)
	{
		$sql = "UPDATE postv_auditoria_indicador 
				SET estado = $estado
				WHERE id_indicador =$id_indicador";

		if ($this->db->query($sql)) {
			return  true;
		} else {
			return  false;
		}
	}
	/* ACtualizar el estado del Item */
	public function estadoItem($id_item, $estado)
	{
		$sql = "UPDATE postv_auditoria_indicador_item 
				SET estado = $estado
				WHERE id_item =$id_item";

		if ($this->db->query($sql)) {
			return  true;
		} else {
			return  false;
		}
	}
	/* ACtualizar el estado de la Observación */
	public function estadoObservacion($id_obs, $estado)
	{
		$sql = "UPDATE postv_auditoria_item_obs 
				SET estado = $estado
				WHERE id_obs =$id_obs";

		if ($this->db->query($sql)) {
			return  true;
		} else {
			return  false;
		}
	}
	/* Funcion para obtener el correo del agente */
	public function getAuditoriaEmail($id)
	{
		$sql = "SELECT t.primer_nombre,e.e_mail as mailEncargado, c.nombre, c.e_mail,a.* FROM postv_auditoria_agente a
				LEFT JOIN (select * from CRM_contactos where contacto = 1) c ON c.nit = a.nit_agente
				LEFT JOIN terceros_nombres t ON t.nit = a.nit_agente
				LEFT JOIN (select * from CRM_contactos where contacto = 1) e ON e.nit = a.nit_encargado
				WHERE id_auditoria = $id";
		$result = $this->db->query($sql);
		if($result){
			return $result->row();
		}else {

		}
	}
	/* Funcion para agregar el compromiso del agente respecto a la auditoría ID */
	public function insertCompromisoAuditoria($id_auditoria,$compromisos)
	{
		$sql = "UPDATE postv_auditoria_agente 
				SET compromiso = '$compromisos'
				WHERE id_auditoria =$id_auditoria";

		if ($this->db->query($sql)) {
			return  true;
		} else {
			return  false;
		}
	}
	/* Funcion para agregar el compromiso del agente respecto a la auditoría ID */
	public function getDetalleAuditoria($nitAgente,$year,$month)
	{
		$sql = "SELECT CONVERT(varchar,fecha_creacion,103) as fecha_c, * FROM postv_auditoria_agente
				WHERE YEAR(fecha_creacion)=$year 
				AND MONTH(fecha_creacion) =$month
				AND nit_agente = $nitAgente
				ORDER BY fecha_creacion DESC";

		return $this->db->query($sql);
	}
	/* Función para obtener las observaciones por item Activo*/
	public function getObsXitemActivos($id_item)
	{
		$sql = "SELECT * from postv_auditoria_item_obs
				WHERE id_item = $id_item and estado = 2";
		return $this->db->query($sql);
	}
	/* Funcion para validar si hay auditorías pendientes por Finalizar */
	public function countAuditorias()
	{
		$sql = "SELECT COUNT(*) AS cantidad FROM postv_auditoria_agente a
				WHERE a.fecha_finalizacion is NULL";
		return $this->db->query($sql);
	}
}
