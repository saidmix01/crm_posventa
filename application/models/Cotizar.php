<?php

/**
 * Modelo del modulo Cotizacion
 */
class Cotizar extends CI_Model
{
	public function getInfoCotizacion()
	{

		/* Costos de mano de obra  */
		$sql = "select distinct m.descripcion_operacion, m.valor_unitario
				from v_vh_vehiculos v inner join postv_trabajos_mto m
				on v.clase=m.clase
				where v.clase='SPARK 1.2'
				order by descripcion_operacion desc";
		/* Revisiones dependiendo del kilometraje */
		$sql = "select distinct Revision from Postv_repuestos_mto
		where Clase = 'BEAT'";
		/* Revisión a detalle....  */
		$sql = "select distinct r.Codigo, rf.descripcion,r.Categoria,r.Cantidad,
		Valor= convert(decimal(10,2),((p.precio_1*r.cantidad)+(p.precio_1*r.cantidad*rf.porcentaje_iva/100))),
		isnull(s.stock,0) as unidades_disponibles
		from v_vh_vehiculos v inner join Postv_repuestos_mto r on v.clase=r.clase
		inner join referencias rf on r.Codigo=rf.codigo
		left join referencias_pre p on r.Codigo=p.codigo
		left join (select codigo, stock from v_referencias_sto where bodega=1 and ano=YEAR(getdate()) and mes=MONTH(getdate())
		) s on r.Codigo=s.codigo
		where v.clase='BEAT' and r.Revision=30000";
	}
	public function getClasePlaca($placa)
	{
		/* Buscar clase por placa */
		$sql = "SELECT v.nit_comprador as nit, t.nombres as cliente, t.mail, t.celular, placa, v.clase, c.descripcion,v.ano as year, v.des_modelo,v.kilometraje, kp.uetd_entrada,kp.km_promedio,
				((datediff(day,kp.uetd_entrada,convert(date,getdate()))*kp.km_promedio) + v.kilometraje) as km_estimado				
				from v_vh_vehiculos v inner join referencias_cla c
				on v.clase=c.clase
				left join v_km_promedio_dias kp on v.codigo=kp.codigo
				inner join terceros t on v.nit_comprador=t.nit	
				where v.clase not in ('*','GENERICO')
				and placa = '$placa'";
		return $this->db->query($sql);
	}

	public function getClasesForm()
	{
		/* Seleccionar la clase, en caso de no existir la placa */
		$sql = "SELECT distinct v.clase, c.descripcion 
				from Postv_repuestos_mto v inner join referencias_cla c
				on v.clase=c.clase
				ORDER BY c.descripcion";
				/* Proximo cambio, que aparezacan todas las clases */
		/* $sql ="SELECT distinct m.clase,c.descripcion from postv_mo_adicionales m
		left join referencias_cla c on m.clase=c.clase
		where c.descripcion is not null and c.clase is not null
		ORDER BY c.descripcion"; */

		return $this->db->query($sql);
	}

	public function getBodegas()
	{
		$sql = "SELECT bodega, descripcion from dbo.bodegas
		where bodega IN (select distinct bodega from v_referencias_sto)
		order by bodega";
		return $this->db->query($sql);
	}

	public function getRevisionSelect($clase)
	{
		$sql = "SELECT distinct Revision from Postv_repuestos_mto
				where Clase = '$clase'
				order by Revision asc";
		return $this->db->query($sql);
	}
	public function getDescModelSelect($desc)
	{
		$sql = "SELECT distinct v.des_modelo 
				from v_vh_vehiculos v inner join referencias_cla c
				on v.clase=c.clase
				where v.clase not in ('*','GENERICO')
				and c.descripcion = '$desc'
				order by v.des_modelo  asc";
		return $this->db->query($sql);
	}
	public function revisionDetalle($bodega, $clase, $revision)
	{
		/* Revisión a detalle.... rf.porcentaje_iva/100  */
		$sql = "SELECT distinct r.seq,
		codigo=case when a.codigo is null then r.Codigo else a.codigo end, 
		r.descripcion,
		r.Categoria,r.Cantidad,
						Valor=case when a.codigo is null then convert(decimal(10,2),((p.precio_1*r.cantidad)+(p.precio_1*r.cantidad*0.19)))
						else convert(decimal(10,2),((p2.precio_1*r.cantidad)+(p2.precio_1*r.cantidad*0.19))) end,
						unidades_disponibles=case when a.codigo is null then isnull(s.stock,0) else ISNULL(s2.stock,0) end
						from v_vh_vehiculos v inner join Postv_repuestos_mto r on v.clase=r.clase
						left join referencias_alt a on r.Codigo=a.alterno
						left join referencias_pre p on r.Codigo=p.codigo
						left join referencias_pre p2 on a.codigo=p2.codigo
						left join (select codigo, stock from v_referencias_sto_hoy where bodega=$bodega and ano=YEAR(getdate()) and mes=MONTH(getdate())) s on r.Codigo=s.codigo
						left join (select codigo, stock from v_referencias_sto_hoy where bodega=$bodega and ano=YEAR(getdate()) and mes=MONTH(getdate())) s2 on a.Codigo=s2.codigo
						where v.clase='$clase' and r.Revision=$revision";

		return $this->db->query($sql);
	}
	public function getManoObraMtto($clase,$year)
	{
		$sql = "SELECT distinct m.descripcion_operacion, m.valor_unitario,m.operacion,m.valor_mas_5anos, m.cant_horas
		from v_vh_vehiculos v inner join postv_trabajos_mto m
		on v.clase=m.clase
		where v.clase='$clase'
		order by descripcion_operacion desc";
		return $this->db->query($sql);
	}

	public function insertCotizacion($dataCotizar)
	{
		if ($this->db->insert('dbo.postv_cotizacion_contact', $dataCotizar)) {
			return $this->db->insert_id();
		} else {
			return 0;
		}
	}
	public function addRepuestosCoti($dataR)
	{
		return $this->db->insert('postv_cotizacion_repuestos', $dataR);
	}

	public function addMTTOCoti($dataM)
	{
		return $this->db->insert('postv_cotizacion_mtto', $dataM);
		
	}
	/* Listar las cotizaciones realizadas por los asesores */
	public function getCotizacionesAll($where)
	{
		$sql = "SELECT CT.*, b.descripcion as NomBodega, b.direccion, b.telefono, Crm.nombre as asesor, Crm.e_mail as correo,
				(SELECT DATEADD(DAY,30,CONVERT(DATE,CT.fecha_creacion,23))) as caducidad, Crm.tel_ofi1 as telAsesor
				FROM dbo.postv_cotizacion_contact CT
				LEFT JOIN bodegas b ON b.bodega = CT.bodega
				LEFT JOIN (select * from CRM_contactos
				where contacto = 1) Crm ON Crm.nit = CT.usuario
				$where
				order by fecha_creacion desc";
		
		return $this->db->query($sql);
	}
	/* Seleccionar la cotizacion por id y placa*/
	public function getCotizacion($id, $placa)
	{
		$sql = "SELECT CT.*, b.descripcion as NomBodega, b.direccion, b.telefono, Crm.nombre as asesor, Crm.e_mail as correo,
				(SELECT DATEADD(DAY,30,CONVERT(DATE,CT.fecha_creacion,23))) as caducidad,
				case when Crm.tel_celular is not NULL then Crm.tel_celular else t.celular end as telAsesor
				FROM dbo.postv_cotizacion_contact CT
				LEFT JOIN bodegas b ON b.bodega = CT.bodega
				LEFT JOIN terceros t On t.nit = CT.usuario
				LEFT JOIN (select * from CRM_contactos
				where contacto = 1) Crm ON Crm.nit = CT.usuario
				WHERE id_cotizacion = $id and placa = '$placa' ";
		return $this->db->query($sql);
	}
	/**OBTIENE LA COTIZACION SOLO POR EL ID 
	 * BY SAID
	*/
	public function getCotizacionById($id)
	{
		$sql = "SELECT CT.*,vhv.codigo, b.descripcion as NomBodega, b.direccion, b.telefono, Crm.nombre as asesor, Crm.e_mail as correo,
		(SELECT DATEADD(DAY,30,CONVERT(DATE,CT.fecha_creacion,23))) as caducidad,
		case when Crm.tel_celular is not NULL then Crm.tel_celular else t.celular end as telAsesor
		FROM dbo.postv_cotizacion_contact CT
		LEFT JOIN bodegas b ON b.bodega = CT.bodega
		LEFT JOIN terceros t On t.nit = CT.usuario
		LEFT JOIN v_vh_vehiculos vhv ON vhv.placa = CT.placa 
		LEFT JOIN (select * from CRM_contactos
		where contacto = 1) Crm ON Crm.nit = CT.usuario
		WHERE id_cotizacion =$id ";
		return $this->db->query($sql);
	}
	/* Seleccionar los repuestos para la cotizacion por id */
	public function getRepuestosCoti($id)
	{
		$sql = "SELECT * FROM dbo.postv_cotizacion_repuestos
				WHERE id_cotizacion = $id
				order by estado desc";
		return $this->db->query($sql);
	}
	/* Seleccionar los mtto de la cotizacion por id */
	public function getMttoCoti($id)
	{
		$sql = "SELECT * FROM dbo.postv_cotizacion_mtto
				WHERE id_cotizacion = $id
				order by estado desc";
		return $this->db->query($sql);
	}
	/* Seleccionar los mtto de la cotizacion por id */
	public function getMttoCotiAgenda($id)
	{
		$sql = "SELECT * FROM dbo.postv_cotizacion_mtto
		WHERE id_cotizacion = $id
		AND id NOT IN (SELECT id_operacion FROM postv_citas_operaciones)
		order by estado desc";
		return $this->db->query($sql);
	}
	/* Funcion para obtener los repuestos en 0 a la hora de realizar la cotización */
	public function getRepuestosCotizacionesAll($fecha_inicio, $fecha_final, $bodega)
	{
		$sql = "SELECT b.descripcion as bodega, count(cr.codigo) as cant_codigo, cr.codigo, cr.descripcion,cr.uni_disponibles
				from postv_cotizacion_contact cc 
				inner join postv_cotizacion_repuestos cr on cc.id_cotizacion=cr.id_cotizacion
				inner join bodegas b on b.bodega = cc.bodega
				where cr.uni_disponibles = 0 and cr.estado = 1 and convert(date,fecha_creacion) between '$fecha_inicio' and '$fecha_final' 
				$bodega
				group by b.descripcion, cr.codigo,cr.descripcion,cr.uni_disponibles";
		return $this->db->query($sql);
	}
	/* Funcion para obtener el mtto prepago del vehiculo por placa */
	public function getMttoPrepagado($placa)
	{
		$sql = "select r.placa,e.descripcion as prepagado from vh_eventos_vehiculos ev inner join referencias_imp r
				on ev.codigo=r.codigo
				inner join vh_eventos e on ev.evento=e.evento
				where ev.evento in (455,460,465,470)
				and r.placa = '$placa'";
		return $this->db->query($sql);
	}
	/* Obtener correo encargado de Bodega */
	public function getEmailBodega($nit)
	{
		$sql = "SELECT e_mail from crm_contactos
				where nit = $nit";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row(0)->e_mail;
		} else {
			return 'programador3@codiesel.co';
		}
	}
	/* Actualizar estado de la cotización */
	public function updateEstado($id)
	{
		$data = array(
			"estado" => 1,
			"fecha_agenda" => date('Ymd H:i:s'),
		);
		$this->db->where('id_cotizacion', $id);
		$this->db->update('postv_cotizacion_contact', $data);
	}
	/* Actualizar estado de agenda de la cotización */
	public function actualizarEstadoCoti($id){
		$sql = "UPDATE postv_cotizacion_contact 
				SET estado = 1, fecha_agenda = SYSDATETIME()
				WHERE id_cotizacion = $id";
		$result = $this->db->query($sql);
		
		return $result;

	}
	/* Obtener el control de repuestos por cotizaciones */
	public function getControlRepuestos(){
		$sql = "SELECT * FROM v_control_rep_cotiza_minymax";
		return $this->db->query($sql);
	}

	/* Obtener la mano de obra de mantenimiento */
	public function getAdicionalesMtto($clase,$revision){

		$sql = "select * from postv_adicionales_mto
		where clase = '$clase' and revision= $revision";

		return $this->db->query($sql);
	}

	public function get_rept_dependientes($clase){
		$sql = "select * from postv_reptos_depende_mto
		where clase = '$clase'";
		return $this->db->query($sql);
	}
	/* Agregar adicionales en la cotización */
	
	public function getRepuestosAdicionales($clase,$bodega,$adicional){
		
		$sql = "SELECT
		v.adicional,
		codigo= case when a.codigo is null then v.codigo else a.codigo end,  
		v.descripcion,
		v.cantidad,
		Valor= case when a.codigo is null then convert(decimal(10,2),((p.precio_1*v.cantidad)+(p.precio_1*v.cantidad*0.19)))
				else convert(decimal(10,2),((p2.precio_1*v.cantidad)+(p2.precio_1*v.cantidad*0.19))) end,
		unidades_disponibles=case when a.codigo is null then isnull(s.stock,0) else ISNULL(s2.stock,0) end
		from postv_reptos_adicionales v
		left join referencias rf on v.codigo=rf.codigo
		left join referencias_alt a on v.codigo=a.alterno
		left join referencias_pre p on v.codigo=p.codigo
		left join referencias_pre p2 on a.codigo=p2.codigo
		left join (select codigo, stock from v_referencias_sto_hoy where bodega=$bodega and ano=YEAR(getdate()) and mes=MONTH(getdate())) s on v.codigo=s.codigo
		left join (select codigo, stock from v_referencias_sto_hoy where bodega=$bodega and ano=YEAR(getdate()) and mes=MONTH(getdate())) s2 on a.codigo=s2.codigo
		where v.clase = '$clase' and v.adicional='$adicional'";

		return $this->db->query($sql);
	}

	public function getManoObraAdicional($clase,$adicional,$operacion){
		$sql = "SELECT * FROM postv_mo_adicionales
				WHERE clase = '$clase' and adicional = '$adicional' $operacion";
		return $this->db->query($sql);
	}

	public function getNameAdicionales()
	{
		$sql = "SELECT distinct(adicional) FROM postv_mo_adicionales";

		return $this->db->query($sql);
	}

}
