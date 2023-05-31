<?php

class CotizadorPesadosModel extends CI_Model
{
    /* 
    *Obtener todas las descripciones de los vehículo que cuentan con repuestos de mantenimiento
    */
    public function getDescriptionAll()
    {

        $sql = "SELECT distinct v.clase, c.descripcion
        from postv_reptos_mto_pesados p
		left join v_vh_vehiculos v on v.clase = p.clase
		left join referencias_cla c	on v.clase=c.clase
		order by c.descripcion asc";

        return $this->db->query($sql);
    }
    /* 
    * Obtener los modelos de la clase
    * @param $clase, Se pasa el parametro con la clase que vamos a obtener los modelos
    */
    public function getModelVhByClase($clase)
    {

        $sql = "SELECT distinct v.des_modelo as descripcion 
        from postv_reptos_mto_pesados p
		left join v_vh_vehiculos v on v.clase = p.clase
		WHERE v.des_modelo is not null and v.clase = '$clase'
		order by v.des_modelo asc";

        return $this->db->query($sql);
    }
    /* 
    * Obtener el nombre de las revisiones con las que cuenta la clase del vehículo
    * @param $clase, Se pasa el parametro con la clase que vamos a obtener las revisiones
    */
    public function getRevisionClase($clase)
    {
        $sql = "select distinct revision from postv_reptos_mto_pesados
        where clase = '$clase'";
        return $this->db->query($sql);
    }

    public function getDataMttoRep($clase, $revision, $bodega, $grupo)
    {
        $sql = "SELECT distinct r.seq, 
        Codigo=case when al.alterno is Not null then al.codigo else r.codigo end, 
        r.descripcion,
        r.Categoria,r.Cantidad,r.grupo,r.ano_inicio, ISNULL(r.ano_fin,YEAR(GETDATE())) as ano_fin,
        Valor= case when al.alterno is not null then convert(decimal(10,2),((p1.precio_1*r.cantidad)+(p1.precio_1*r.cantidad*q.porcentaje_iva/100))) 
        else convert(decimal(10,2),((p.precio_1*r.cantidad)+(p.precio_1*r.cantidad*rf.porcentaje_iva/100))) end,
        case when al.alterno is not null then isnull(s1.stock,0) else isnull(s.stock,0) end as unidades_disponibles
                from v_vh_vehiculos v 
                inner join postv_reptos_mto_pesados r on v.clase=r.clase
                left join referencias rf on r.Codigo=rf.codigo
                left join referencias_alt al on r.codigo=al.alterno
                left join referencias q on al.codigo=q.codigo
                left join referencias_pre p on r.Codigo=p.codigo
                left join referencias_pre p1 on al.codigo=p1.codigo
                left join (select codigo, stock from v_referencias_sto where bodega=$bodega and ano=YEAR(getdate()) and mes=MONTH(getdate())) s on r.Codigo=s.codigo
                left join (select codigo, stock from v_referencias_sto where bodega=$bodega and ano=YEAR(getdate()) and mes=MONTH(getdate())) s1 on al.codigo=s1.codigo
                where v.clase='$clase' and r.Revision=$revision and r.grupo='$grupo'
                order by r.ano_inicio asc, descripcion asc";
        return $this->db->query($sql);
    }

    public function getDataMttoToT($clase, $revision, $grupo)
    {
        $sql = "SELECT * from postv_adicionales_mto_pesados
		where clase = '$clase' and revision=$revision and grupo = '$grupo'";
        return $this->db->query($sql);
    }

    public function insertCotizacion($dataCotizar)
    {
        if ($this->db->insert('dbo.postv_cotizacion_contact_p', $dataCotizar)) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }
    public function addRepuestosCoti($dataR)
    {
        return $this->db->insert('postv_cotizacion_repuestos_p', $dataR);
    }

    public function addMTTOCoti($dataM)
    {

        return $this->db->insert('postv_cotizacion_mtto_p', $dataM);
    }

    public function getCotizacion($id, $placa)
    {
        $sql = "SELECT CT.*, b.descripcion as NomBodega, b.direccion, b.telefono, Crm.nombre as asesor, Crm.e_mail as correo,
				(SELECT DATEADD(DAY,30,CONVERT(DATE,CT.fecha_creacion,23))) as caducidad,
				case when Crm.tel_celular is not NULL then Crm.tel_celular else t.celular end as telAsesor
				FROM dbo.postv_cotizacion_contact_p CT
				LEFT JOIN bodegas b ON b.bodega = CT.bodega
				LEFT JOIN terceros t On t.nit = CT.usuario
				LEFT JOIN (select * from CRM_contactos
				where contacto = 1) Crm ON Crm.nit = CT.usuario
				WHERE id_cotizacion = $id and placa = '$placa' ";
                
        return $this->db->query($sql);
    }
    /* Seleccionar los repuestos para la cotizacion por id */
	public function getRepuestosCoti($id)
	{
		$sql = "SELECT * FROM dbo.postv_cotizacion_repuestos_p
				WHERE id_cotizacion = $id
				order by estado desc";
		return $this->db->query($sql);
	}
	/* Seleccionar los mtto de la cotizacion por id */
	public function getMttoCoti($id)
	{
		$sql = "SELECT * FROM dbo.postv_cotizacion_mtto_p
				WHERE id_cotizacion = $id
				order by estado desc";
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

    public function getCotizacionesAll($where)
	{
		$sql = "SELECT CT.*, b.descripcion as NomBodega, b.direccion, b.telefono, Crm.nombre as asesor, Crm.e_mail as correo,
				(SELECT DATEADD(DAY,30,CONVERT(DATE,CT.fecha_creacion,23))) as caducidad, Crm.tel_ofi1 as telAsesor
				FROM dbo.postv_cotizacion_contact_p CT
				LEFT JOIN bodegas b ON b.bodega = CT.bodega
				LEFT JOIN (select * from CRM_contactos
				where contacto = 1) Crm ON Crm.nit = CT.usuario
				$where
				order by fecha_creacion desc";
		
		return $this->db->query($sql);
	}

    /* Actualizar estado de agenda de la cotización */
	public function actualizarEstadoCoti($id){
		$sql = "UPDATE postv_cotizacion_contact_p
				SET estado = 1, fecha_agenda = SYSDATETIME()
				WHERE id_cotizacion = $id";
		$result = $this->db->query($sql);
		
		return $result;
	}
    /* Obtener mano de obra - Pesados */
    public function getManoObraPesados($clase, $grupo, $bodega){
		$sql = "SELECT seq,operacion,descrpcion,horas,
        valor= horas*(select valor_hora+(valor_hora*0.19) from tall_tarifas_taller where bodega=$bodega)
        FROM postv_trabajo_mto_pesados where clase='$clase' and grupo='$grupo'";
		$result = $this->db->query($sql);
		
		return $result;
	}
}
