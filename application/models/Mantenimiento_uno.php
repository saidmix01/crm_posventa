<?php


/**
 * Modelo de para manipular datos de la tabla equipos
 */
class Mantenimiento_uno extends CI_Model
{
	//metodo ingresa datos a la tbla equipos
	public function agregar_registro($nombre, $bodega, $codigo, $estado, $area, $img, $aliasEquipo)
	{
		$sql = "INSERT INTO dbo.postv_equipos (nombre_equipo, bodega, codigo, estado, area, cv_equipo, alias_equipo) VALUES ('" . $nombre . "','" . $bodega . "','" . $codigo . "','" . $estado . "','" . $area . "','" . $img . "','".$aliasEquipo."')";
		return $this->db->query($sql);
	}
	//metodo traer datos segun el estado
	public function ver_equipos($where="")
	{
		return $this->db->query("SELECT * FROM  dbo.postv_equipos 
		WHERE estado != 'inactivo' $where
		order by id_equipo desc");
	}
	//metodo traer datos segun el estado
	public function ver_id()
	{
		return $this->db->query("SELECT * FROM dbo.postv_equipos ");
	}

	//metodo para cambiar el estado 
	public function nuevo_estado($id, $estado)
	{
		$update = "UPDATE dbo.postv_equipos SET estado='$estado' WHERE id_equipo = $id";
		return $this->db->query($update);
	}

	//metodo para cambiar a reparacion
	public function estadoreparacion($id, $estado)
	{
		$update = "UPDATE dbo.postv_equipos SET estado='$estado' WHERE id_equipo = $id";
		return $this->db->query($update);
	}


	//metodo traer datos segun el id
	public function ver_datos($id)
	{
		return $this->db->query("SELECT e.* FROM  dbo.postv_equipos e 
		 WHERE e.id_equipo = $id ");
	}

	//metodo para editar la informacion de equipos 

	public function editar_equipo($id, $nombre, $bodega, $codigo, $estado, $area, $img,$aliasEquipo)
	{

		if ($img != "") {
			$update = "UPDATE dbo.postv_equipos SET nombre_equipo='$nombre', bodega='$bodega', codigo='$codigo', estado='$estado',area='$area', cv_equipo='$img', alias_equipo='$aliasEquipo' WHERE id_equipo = $id";
		} else {
			$update = "UPDATE dbo.postv_equipos SET nombre_equipo='$nombre', bodega='$bodega', codigo='$codigo', estado='$estado',area='$area',alias_equipo='$aliasEquipo' WHERE id_equipo = $id";
		}
		return $this->db->query($update);
	}

	//metodo creado para crear nuevo mantenimiento preventivo en la tabla dbo.postv_mantenimineto 

	public function Correctivo($data)
	{
		return $this->db->insert("postv_mantenimientos", $data);
	}


	/*metodo para tarer listado de matenimiento realizados a un equipo por id del equipo */

	public function DatosMantenimientoid($codigo)
	{
		$consulta = "SELECT e.id_equipo,e.nombre_equipo,e.codigo,e.estado,
		tm.asignado,
		tm.fecha_solicitud,
		tm.fecha_requerida,
		tm.fecha_final,
		tm.fecha_inicio,
		tm.descripcion as descrip,
		tm.observaciones,
		tm.detalle_piezas,
		tm.id_mantenimientos,
		tp.tipo_mantenimiento,
		tp.id_mantenimiento,
		tm.estado as estadoMto,
		t.nombres as NameAsignado
		FROM dbo.postv_mantenimientos tm
		LEFT JOIN  dbo.postv_equipos e ON tm.codigo_equipo = e.codigo  
		LEFT JOIN dbo.postv_tipo_mantenimiento tp ON tp.id_mantenimiento = tm.id_tipo_mantenimiento  
		LEFT JOIN terceros t ON t.nit = tm.asignado
		where tm.codigo_equipo = '" . $codigo . "' 
		order by fecha_solicitud desc,fecha_requerida desc, fecha_inicio desc, fecha_final desc
		";

		if ($this->db->query($consulta)) {
			return $this->db->query($consulta);
		} else {
			return false;
		}
	}
	/* Traer detalles de historial de mantenimientos segun ID */
	public function historial_detallado($id)
	{
		$sql = $this->db->query('SELECT * FROM dbo.postv_mantenimientos WHERE id_mantenimientos=' . $id);
		return $sql;
	}

	/*metodo para traer todas la bodegas */
	public function TarerBodegas()
	{
		return $this->db->query("SELECT bodega,descripcion FROM bodegas ");
	}

	public function traer_equipos_por_sede_y_bodega($where)
	{

		$sql = "SELECT * FROM dbo.postv_equipos $where";
		return $this->db->query($sql);
	}
	/*************************************SOLICITUD DE MANTENIMIENTO ************************************************/
	/**Autor: Sergio Galvis 
	 * Fecha: 04 de Abril del 2022
	 */
	/*método para consultar las solicitudes perfil jefe */
	public function ver_solicitud_mantenimiento($id)
	{
		return $this->db->query("SELECT tj.nombres as nombreJ, te.nombres as nombreE,eq.codigo, sm.* FROM postv_solicitud_mantenimiento sm 
		INNER JOIN terceros tj ON tj.nit = sm.jefe
		LEFT JOIN terceros te ON te.nit = sm.encargado
		INNER JOIN w_sist_usuarios uj ON uj.nit_usuario = sm.jefe
		LEFT JOIN w_sist_usuarios ue ON ue.nit_usuario = sm.encargado
		LEFT JOIN dbo.postv_equipos eq ON eq.id_equipo = sm.id_equipo
		WHERE sm.jefe = $id
		order by estado asc");
	}
	/*método para consultar todas las solicitudes perfil Mantenimiento  */
	public function ver_solicitud_mantenimiento_td($sedes)
	{
		/* print_r($sedes);die; */
		$consulta = $this->db->query("SELECT tj.nombres as jefeN, te.nombres as encargadoN,eq.codigo, sm.*,
			dias_gest = DATEDIFF(DAY,CONVERT(DATE,fecha_solicitud), CONVERT(DATE,GETDATE()))
		 FROM postv_solicitud_mantenimiento sm 
		INNER JOIN terceros tj ON tj.nit = sm.jefe
		LEFT JOIN terceros te ON te.nit = sm.encargado
		INNER JOIN w_sist_usuarios uj ON uj.nit_usuario = sm.jefe
		LEFT JOIN w_sist_usuarios ue ON ue.nit_usuario = sm.encargado
		LEFT JOIN dbo.postv_equipos eq ON eq.id_equipo = sm.id_equipo
		WHERE sm.sede IN($sedes) 
		order by estado asc");

		return $consulta;
	}
	/*método para consultar todas las solicitudes perfil Admins  */
	public function ver_solicitud_mantenimiento_admins()
	{
		/* print_r($sedes);die; */
		$consulta = $this->db->query("SELECT tj.nombres as jefeN, te.nombres as encargadoN,eq.codigo, sm.*,
			dias_gest = DATEDIFF(DAY,CONVERT(DATE,fecha_solicitud), CONVERT(DATE,GETDATE()))
		 FROM postv_solicitud_mantenimiento sm 
		INNER JOIN terceros tj ON tj.nit = sm.jefe
		LEFT JOIN terceros te ON te.nit = sm.encargado
		INNER JOIN w_sist_usuarios uj ON uj.nit_usuario = sm.jefe
		LEFT JOIN w_sist_usuarios ue ON ue.nit_usuario = sm.encargado
		LEFT JOIN dbo.postv_equipos eq ON eq.id_equipo = sm.id_equipo
		order by estado asc");

		return $consulta;
	}
	/*método para consultar las solicitudes de mantenimiento segun id_solicitud */
	public function get_solicitud_mantenimiento_by_id($id)
	{
		$sql = "SELECT tj.nombres as nombreJ, te.nombres as nombreE,eq.codigo,eq.nombre_equipo, sm.* 
				FROM postv_solicitud_mantenimiento sm 
				INNER JOIN terceros tj ON tj.nit = sm.jefe
				LEFT JOIN terceros te ON te.nit = sm.encargado
				INNER JOIN w_sist_usuarios uj ON uj.nit_usuario = sm.jefe
				LEFT JOIN w_sist_usuarios ue ON ue.nit_usuario = sm.encargado
				LEFT JOIN postv_equipos eq ON eq.id_equipo = sm.id_equipo
				WHERE sm.id_solicitud = '" . $id . "'";
		return $this->db->query($sql);
	}
	/*método para crear nuevas solicitudes de mantenimiento */
	public function insert_solicitud_mto($data)
	{

		if ($this->db->insert('postv_solicitud_mantenimiento', $data)) {
			//echo "entro aqui verdadero";
			return true;
		} else {
			//echo "entro aqui falso";
			return false;
		}
	}
	/*método para iniciar las solicitudes de mantenimiento */
	public function iniciar_solicitud_mto($data, $id)
	{
		$this->db->where('id_solicitud', $id);
		return $this->db->update('postv_solicitud_mantenimiento', $data);
	}
	/*método para finalizar las solicitudes de mantenimiento */
	public function finalizar_solicitud_mto($data, $id)
	{
		$this->db->where('id_solicitud', $id);
		if ($this->db->update('postv_solicitud_mantenimiento', $data)) {
			return true;
		} else {
			return false;
		}
	}
	/* Se traeran las bodegas principales para el Mantenimiento de equipos */
	public function traer_bodegas_mto()
	{

		return $this->db->query("SELECT bodega, descripcion FROM bodegas WHERE bodega IN(1,3,4,6,7,8,23)");
	}
	/* Metodo para traer el nombre de las bodegas */
	public function traer_name_bodegas_mto($id)
	{
		$consulta =  $this->db->query("SELECT bodega, descripcion FROM bodegas WHERE bodega =" . $id);
		return $consulta;
	}
	/* Metodo para traer los datos de las solicitudes pendientes */
	public function s_pendientes($sedes)
	{
		if ($sedes == "") {
			$sql = $this->db->query("SELECT COUNT(estado) as pendientes FROM postv_solicitud_mantenimiento WHERE estado=1");
			if ($sql->num_rows() > 0) {
				return $sql->row();
			} else {
				return null;
			}
		} else {
			$sql = $this->db->query("SELECT COUNT(estado) as pendientes FROM postv_solicitud_mantenimiento WHERE estado=1 AND sede IN(" . $sedes . ")");
			if ($sql->num_rows() > 0) {
				return $sql->row();
			} else {
				return null;
			}
		}
	}
	/* Metodo para traer los datos de las solicitudes en proceso */
	public function s_proceso($sedes)
	{

		$sql = $this->db->query("SELECT COUNT(estado) as proceso FROM postv_solicitud_mantenimiento WHERE estado=2 AND sede IN(" . $sedes . ")");
		if ($sql->num_rows() > 0) {
			return $sql->row();
		} else {
			return null;
		}
	}
	/* Metodo para traer los datos de las solicitudes finalizadas */
	public function s_finalizadas($sedes)
	{

		$sql = $this->db->query("SELECT COUNT(estado) as finalizada FROM postv_solicitud_mantenimiento WHERE estado=3 AND sede IN(" . $sedes . ")");
		if ($sql->num_rows() > 0) {
			return $sql->row();
		} else {
			return null;
		}
	}
	/* Metodo para insertar las respuestas o chat de la solicitud */
	public function crear_chat_solocitud($data)
	{
		if ($this->db->insert('postv_solicitud_mantenimiento_respuestas', $data)) {
			//echo "entro aqui verdadero";
			return true;
		} else {
			//echo "entro aqui falso";
			return false;
		}
	}
	/* Metodo para obtener las respuestas o chat de la solicitud */
	public function get_respuestas_solicitud($id_solicitud)
	{
		$sql = $this->db->query("SELECT * FROM postv_solicitud_mantenimiento_respuestas WHERE id_solicitud =" . $id_solicitud);
		if ($sql) {
			return $sql;
		} else {
			return false;
		}
	}
	/*************************************SOLICITUD DE RETIRO DE EQUIPOS ************************************************/
	/**Autor: Sergio Galvis 
	 * Fecha: 11 de Abril del 2022
	 */
	public function consultar_retirar_equipo($equipo_id)
	{
		$consulta = ("SELECT top 1 * FROM postv_equipos_retirados WHERE equipo_id=$equipo_id
					order by fecha_solicitud desc, id desc");
		return $this->db->query($consulta);
	}
	public function solicitud_retirar_equipo($data)
	{

		if ($this->db->insert('postv_equipos_retirados', $data)) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	public function traer_datos_equipos($id)
	{

		return $this->db->query("SELECT e.id_equipo,e.nombre_equipo,e.codigo,e.estado,e.area,e.bodega,er.nit_usuario_solicita,er.fecha_solicitud,er.imagen
		FROM  dbo.postv_equipos e 
		LEFT JOIN postv_equipos_retirados er on e.id_equipo = er.equipo_id
		 WHERE e.id_equipo = $id ");

	}
	/* Traer datos de la tabla postv_jefes */
	public function traer_jefes()
	{
		return $this->db->query("SELECT tj.nombres, tj.nit, jf.correo FROM postv_jefes jf 
		INNER JOIN terceros tj ON tj.nit = jf.nit_jefe
		INNER JOIN w_sist_usuarios uj ON uj.nit_usuario = jf.nit_jefe");
	}
	/* Obtener correo del jefe por nit  */
	public function correo_jefe($jefe)
	{
		return $this->db->query("SELECT * FROM postv_jefes where nit_jefe =" . $jefe);
	}
	/* Autorizar la solicitud de retiro de equipos  */
	public function autorizar_retiro($id_equipo, $id_jefe)
	{
		$fecha_autoriza = date("Y-m-d");
		$update = "UPDATE dbo.postv_equipos_retirados SET nit_usuario_autoriza='$id_jefe', estado=2, fecha_autoriza='$fecha_autoriza' WHERE id = $id_equipo";
		if ($this->db->query($update)) {
			return true;
		} else {
			return false;
		}
	}
	/* Rechazar la solicitud de retiro de equipos   */
	public function rechazar_retiro($id_equipo, $id_jefe)
	{
		$fecha_autoriza = date("Y-m-d");
		$update = "UPDATE dbo.postv_equipos_retirados SET nit_usuario_autoriza='$id_jefe', estado=1 , fecha_autoriza='$fecha_autoriza' WHERE id = $id_equipo";
		if ($this->db->query($update)) {
			return true;
		} else {
			return false;
		}
	}
	/* Consultar el estado de la solicitud del retiro */
	public function consultar_estado_retiro($id_equipo)
	{
		return $this->db->query("SELECT * FROM postv_equipos_retirados  where id =" . $id_equipo);
	}
	/* Consultar el ID del ultimo registro realizado para enviar correo */
	public function buscar_id()
	{
		return $this->db->query("SELECT MAX(id) as ID FROM postv_equipos_retirados");
	}
	public function equipo_nombre_familia($id)
	{

		if ($id == "") {
			$consult = "select * from dbo.postv_equipos_familia";
			$sql = $this->db->query($consult);
			return $sql;
		} else {
			$consult = $this->db->query("SELECT nombre FROM dbo.postv_equipos_familia where codigo =" . $id);
			/* print_r($consult->row());die; */
			if ($consult->num_rows() > 0) {
				return $consult->row();
			} else {
				return null;
			}
		}
	}
	public function getNombreEquipos($codigoF, $codigoN)
	{
		if ($codigoN != "") {
			$data =  $this->db->query("SELECT * FROM dbo.postv_equipos_familia_nombres WHERE codigo_f = '" . $codigoF . "' AND codigo_equipo = '" . $codigoN . "'");
			if ($data->num_rows() > 0) {
				return $data->row();
			} else {
				return null;
			}
		} else {
			$data =  $this->db->query('SELECT * FROM dbo.postv_equipos_familia_nombres WHERE codigo_f = ' . $codigoF);
			if ($data) {
				return $data;
			} else {
				return false;
			}
		}
	}
	public function obtener_Codigo_Consecutivo($codigo)
	{
		$sql = "select  top (1)codigo from postv_equipos where codigo like '%$codigo%' 
		order by codigo desc";
		/* print_r($sql);die; */
		$consult = $this->db->query($sql);
		if ($consult->num_rows() > 0) {
			return $consult->row();
		} else {
			return null;
		}
	}
	/* Traer cronograma de mantenimiento */
	public function ObtenerCronograma($where="")
	{
		$sql = "SELECT id_mantenimientos
		,equi.codigo
		,equi.nombre_equipo
		,equi.area
		,equi.bodega
		,[responsable]
		,[asignado]
		,[fecha_solicitud]
		,fecha_requerida
		,fecha_inicio
		,fecha_final
		,descripcion
		,observaciones
		,detalle_piezas
		,id_tipo_mantenimiento
		,tipo_mantenimiento
		,mto.estado		
		FROM postv_mantenimientos as mto
		INNER JOIN 	dbo.postv_equipos as equi ON equi.codigo = mto.codigo_equipo
		INNER JOIN  dbo.postv_tipo_mantenimiento as tmto ON tmto.id_mantenimiento = mto.id_tipo_mantenimiento 
		$where 
		";
		$consulta = $this->db->query($sql);
		return $consulta;
	}
	/* Traer informacion para el listado de la tabla de mantenimientos preventivos */
	public function ObtenerCronogramaLista($where)
	{
		$sql = "SELECT id_mantenimientos
		,equi.codigo
		,equi.nombre_equipo
		,equi.area
		,equi.bodega
		,[responsable]
		,[asignado]
		,[fecha_solicitud]
		,fecha_requerida
		,fecha_inicio
		,fecha_final
		,descripcion
		,observaciones
		,detalle_piezas
		,id_tipo_mantenimiento
		,tipo_mantenimiento
		,mto.estado		
		FROM postv_mantenimientos as mto
		INNER JOIN 	dbo.postv_equipos as equi ON equi.codigo = mto.codigo_equipo
		INNER JOIN  dbo.postv_tipo_mantenimiento as tmto ON tmto.id_mantenimiento = mto.id_tipo_mantenimiento
		where mto.estado = 1 $where
		order by mto.fecha_requerida asc";
		
		$consulta = $this->db->query($sql);
		return $consulta;
	}
	/* Traer cronograma de mantenimiento segun ID */
	public function ObtenerCronogramaId($id)
	{
		$sql = "SELECT id_mantenimientos
		,equi.codigo
		,equi.nombre_equipo
		,equi.area
		,equi.bodega
		,[responsable]		tipo
		,[asignado]
		,[fecha_solicitud]
		,fecha_requerida
		,fecha_inicio
		,fecha_final
		,descripcion
		,observaciones
		,detalle_piezas
		,equi.id_equipo
		,id_tipo_mantenimiento
		,tipo_mantenimiento
		,te.nombres as [nombre_asignado]
		,tr.nombres as [nombre_responsable]
		,mto.estado
		
		
	FROM postv_mantenimientos as mto
	INNER JOIN 	dbo.postv_equipos as equi ON equi.codigo = mto.codigo_equipo
	INNER JOIN  dbo.postv_tipo_mantenimiento as tmto ON tmto.id_mantenimiento = mto.id_tipo_mantenimiento
	LEFT JOIN terceros te ON te.nit = mto.asignado
	INNER JOIN terceros tr ON tr.nit = mto.responsable
	WHERE id_mantenimientos=" . $id;

		$consulta = $this->db->query($sql);
		return $consulta;
	}
	/* Iniciar mantenimiento preventivo */
	/*método para iniciar la orden de mantenimiento */
	public function iniciar_orden_mtoP($data, $id)
	{
		$this->db->where('id_mantenimientos', $id);
		if ($this->db->update('postv_mantenimientos', $data)) {
			return true;
		} else {
			return false;
		}
	}
	/* Finalizar mantenimiento preventivo */
	/*método para finalizar la orden de mantenimiento */
	public function finalizar_orden_mtoP($data, $id)
	{
		$this->db->where('id_mantenimientos', $id);
		if ($this->db->update('postv_mantenimientos', $data)) {
			return true;
		} else {
			return false;
		}
	}
	/* Traer información del personale de Mantenimiento perfil 46  */
	public function getPersonalMantenimiento()
	{
		$sql = "SELECT u.id_usuario, p.nom_perfil, t.nombres, u.usuario, t.nit, u.estado, p.id_perfil, t.tipo_identificacion,u.pass 
		FROM w_sist_usuarios u 
		INNER JOIN terceros t ON t.nit = u.nit_usuario 
		INNER JOIN postv_perfiles p ON p.id_perfil = u.perfil_postventa
		where p.id_perfil = 46";

		return $this->db->query($sql);
	}
	/* Eliminar orden de mantenimiento---- */
	public function eliminarOrdenMto($id)
	{
		$this->db->where('id_mantenimientos', $id);
		$result = $this->db->delete('postv_mantenimientos');
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
	/* Verificar si el codigo ya esta creado en los equipos existe en los equipos */
	public function verificarEquipo($codigo)
	{
		$sql = "Select codigo from postv_equipos where codigo='" . $codigo . "'";

		$result = $this->db->query($sql);

		if ($result->num_rows() > 0) {

			return true;
		} else {

			return false;
		}
	}
	/* Informe fecha actual de las ordenes de mantenimiento preventivooo */
	public function s_finalizadasPre()
	{
		$fecha_actual = date('Y-m-d');
		$sql = $this->db->query("select count(estado) as finalizada from postv_mantenimientos where estado = 3 and fecha_requerida ='".$fecha_actual."'");
		if ($sql->num_rows() > 0) {
			return $sql->row();
		} else {
			return null;
		}
	}
	public function s_procesoPre()
	{
		$fecha_actual = date('Y-m-d');
		$sql = $this->db->query("select count(estado) as proceso from postv_mantenimientos where estado = 2 and fecha_requerida ='".$fecha_actual."'");
		if ($sql->num_rows() > 0) {
			return $sql->row();
		} else {
			return null;
		}
	}
	public function s_pendientesPre()
	{
		$fecha_actual = date('Y-m-d');
		$sql = $this->db->query("select count(estado) as pendientes from postv_mantenimientos where estado = 1 and fecha_requerida ='".$fecha_actual."'");
		if ($sql->num_rows() > 0) {
			return $sql->row();
		} else {
			return null;
		}
	}
	/* Informes de mantenimiento preventivo y correctivo */
	public function getInformeMttoPrev($where){
		$sql = "SELECT m.*, e.nombre_equipo,tr.nombres as NameResponsable, te.nombres as NameAsignado,e.bodega, e.area 
				from postv_mantenimientos m
				INNER JOIN postv_equipos e ON e.codigo = m.codigo_equipo
				LEFT JOIN terceros tr ON tr.nit = m.responsable
				LEFT JOIN terceros te ON te.nit = m.asignado
				$where
				order by m.estado asc";

		$result = $this->db->query($sql);

		if ($result->num_rows() > 0) {
			return $result;
		} else {
			return false;
		}

		
	}
	/* Informes de mantenimiento preventivo y correctivo */
	public function getInformeMttoCorrectivo($where){
		$sql = "SELECT s.*, tj.nombres as Njefe, te.nombres as Nencargado,b.descripcion, e.codigo, e.nombre_equipo
				from postv_solicitud_mantenimiento s
				LEFT JOIN terceros tj ON tj.nit = s.jefe
				LEFT JOIN terceros te ON te.nit = s.encargado
				INNER JOIN bodegas b ON b.bodega = s.sede
				LEFT JOIN postv_equipos e ON e.id_equipo = s.id_equipo
				$where
				order by s.estado asc";

		$result = $this->db->query($sql);

		if ($result->num_rows() > 0) {
			return $result;
		} else {
			return false;
		}

		
	}

	/* Metodo para obtener las solicitudes de mantenimiento Correctivo donde se evidencie un equipo de Mtto */
	/*método para consultar las solicitudes de mantenimiento segun id_solicitud */
	public function get_solicitud_mantenimientoXid($id)
	{
		$sql = "SELECT tj.nombres as nombreJ, te.nombres as nombreE,eq.codigo,eq.nombre_equipo, sm.* 
					FROM postv_solicitud_mantenimiento sm 
					INNER JOIN terceros tj ON tj.nit = sm.jefe
					LEFT JOIN terceros te ON te.nit = sm.encargado
					INNER JOIN w_sist_usuarios uj ON uj.nit_usuario = sm.jefe
					LEFT JOIN w_sist_usuarios ue ON ue.nit_usuario = sm.encargado
					LEFT JOIN postv_equipos eq ON eq.id_equipo = sm.id_equipo
					WHERE sm.id_equipo = $id
					ORDER BY fecha_solicitud desc, fecha_inicio desc, fecha_finalizacion desc
					";
		return $this->db->query($sql);
	}
	/* Funcion para verificar si un id solicitud cuenta con id_equipo */
	public function getIdEquipoIdSolicitudMttoCorrectivo($id)
	{
		$sql = "SELECT sm.id_equipo 
				FROM postv_solicitud_mantenimiento sm
				WHERE id_solicitud = $id";
			
		$result =  $this->db->query($sql);
		if (COUNT($result->result()) > 0) {
			return $result->row(0)->id_equipo;
		}else {
			return 0;
		}
	}
	/* 
		*	Autor: Sergio Galvis
		*	Fecha: 17/02/2023
		*	Asunto: Update al codigo registrado en los mantenimientos correctivos
	*/
	public function UpdateCodigoMttoCorrectivo($id_mtto,$id_equipo){

		$update = "UPDATE dbo.postv_solicitud_mantenimiento SET id_equipo=$id_equipo WHERE id_solicitud = $id_mtto";
		return $this->db->query($update);

	}

	public function UpdateDateMttoPreventivo($id_mtto,$date){

		$update = "UPDATE dbo.postv_mantenimientos 
		SET fecha_requerida = '$date'  
		WHERE id_mantenimientos=$id_mtto";

		return $this->db->query($update);
	
	}
/**
 * It takes three parameters, and returns the result of a query
 * 
 * @param id_usuario el id del usuario que solicitó el cambio
 * @param date_solicitud es la fecha en la que el usuario solicitó el cambio
 * @param date_old fecha antigua requerida del mantenimiento preventivo
 * @param date la fecha que el usuario ha seleccionado
 */
	public function InsertHistDateMtto($id_mtto,$id_user,$date_solicitud,$date_old,$date){

        $update = "INSERT INTO dbo.postv_mtto_pre_hist_fecha_requerida 
        (id_mtto,nit_user, fecha_solicitud, fecha_requerida_old, fecha_requerida_new)
        VALUES ($id_mtto,$id_user, '$date_solicitud', '$date_old', '$date')";

		return	$this->db->query($update);
	}
	
}