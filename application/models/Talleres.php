<?php


class Talleres extends CI_Model
{
	public function get_info_ordenes_taller($n_orden)
	{
		$sql = "SELECT 
		teo.numero,
		nombres= case when tn.primer_nombre is not null then tn.primer_nombre+' '+tn.segundo_nombre else t.nombres end,
		tn.primer_apellido+' '+tn.segundo_apellido AS apellidos,
		teo.kilometraje,t.nit,t.mail,t.celular,vh.placa,vh.des_modelo,vh.des_marca,vh.ano,vh.serie
		FROM tall_encabeza_orden teo
		left JOIN terceros_nombres tn ON tn.nit = teo.nit
		INNER JOIN v_vh_vehiculos vh ON teo.serie = vh.codigo
		INNER JOIN terceros t ON teo.nit = t.nit_real					
		WHERE CONVERT(DATE,teo.fecha) >= CONVERT(DATE,'2021-01-01') 
		AND teo.numero = " . $n_orden . "
		ORDER BY teo.numero DESC
		";
		return $this->db->query($sql);
	}

	public function get_info_cotizaciones_taller($n_orden)
	{
		$sql = "SELECT DISTINCT clasificarPor='0', actividad='', 
		tipo=case when td.clase_operacion='R' then 'REPUESTOS' else 'MANO DE OBRA' end,
		grupo=case when r.subgrupo3=null then 'SIN GRUPO' else sub.descripcion end,
		referencia=case when td.clase_operacion='R' then r.codigo else t.operacion end, 
		descripcion=case when td.clase_operacion='R' then r.descripcion else t.descripcion end,
		codigoDeParte='',
		cantidad=case when td.clase_operacion='T' then td.tiempo else td.cantidad end,
		valorUnitario=td.valor_unidad,
		valorTotal=convert (int,case when td.clase_operacion='T' then ((td.tiempo*td.valor_unidad-(td.tiempo*td.valor_unidad*td.descuento/100))*0.19+(td.tiempo*td.valor_unidad-(td.tiempo*td.valor_unidad*td.descuento/100)))
		else ((td.cantidad*td.valor_unidad-(td.cantidad*td.valor_unidad*td.descuento/100))*0.19+(td.cantidad*td.valor_unidad-(td.cantidad*td.valor_unidad*td.descuento/100))) end),
		orden=eo.numero 
		from tall_cot_encabezado te inner join tall_cot_detalle td
		on te.id=td.id_tall_cot_encabezado
		inner join tall_encabeza_orden eo 
		on te.id_tall_encabeza_orden=eo.id
		left join referencias r
		on td.operacion=r.codigo
		left join tall_tempario t
		on td.operacion=t.operacion
		left join referencias_sub3 sub
		on r.subgrupo3=sub.subgrupo3
		where eo.fecha>='20210501'
		order by eo.numero";
		return $this->db->query($sql);
	}

	public function get_ventas_tec($nit, $mes, $ano)
	{
		$sql = "SELECT Año, Mes, operario, tecnico,
		rptos=SUM(venta_rptos), MO=SUM(Venta_mano_obra), horas_facturadas=SUM(horas)
		from v_Informe_tecnico 
		WHERE Año=" . $ano . " AND Mes=" . $mes . " AND (venta_rptos<>'0' or Venta_mano_obra<>'0')
		AND operario = '" . $nit . "'
		group by Año, Mes, operario, tecnico";
		return $this->db->query($sql);
	}

	public function get_ventas_tec_ranking($bod, $mes, $ano)
	{
		$sql = "SELECT TOP 4 Año, Mes, operario, (tn.primer_nombre+' '+tn.primer_apellido) AS tecnico,
				rptos=SUM(venta_rptos), MO=SUM(Venta_mano_obra), horas_facturadas=SUM(horas),
				suma_todo = SUM(venta_rptos) + SUM(Venta_mano_obra)
				FROM v_Informe_tecnico inf
				INNER JOIN bodegas b ON inf.sede = b.descripcion
				INNER JOIN terceros_nombres tn ON tn.nit = inf.operario
				WHERE Año=" . $ano . " AND Mes=" . $mes . " AND (venta_rptos<>'0' or Venta_mano_obra<>'0')
				AND b.bodega NOT IN(21,9,14,22)
				AND b.bodega IN (" . $bod . ")
				GROUP BY Año, Mes, operario, tecnico,tn.primer_nombre,tn.primer_apellido
				ORDER BY suma_todo DESC";
		return $this->db->query($sql);
	}

	public function get_ventas_tec_graf($nit, $mes, $ano)
	{
		$sql = "SELECT Año, operario, tecnico, TOT=SUM(venta_TOT),
		rptos=SUM(venta_rptos), MO=SUM(Venta_mano_obra), horas_facturadas=SUM(horas),
		mes_nom = case when Mes = 1 then 'Enero'
		when Mes = 2 then 'Febrero'
		when Mes = 3 then 'Marzo'
		when Mes = 4 then 'Abril'
		when Mes = 5 then 'Mayo'
		when Mes = 6 then 'Junio'
		when Mes = 7 then 'Julio'
		when Mes = 8 then 'Agosto'
		when Mes = 9 then 'Septiembre'
		when Mes = 10 then 'Octubre'
		when Mes = 11 then 'Noviembre'
		else 'Diciembre'
		end
		from v_posv_Informe_tecnicos 
		where Año=" . $ano . " and Mes=" . $mes . " and (venta_rptos<>'0' or Venta_mano_obra<>'0' or venta_TOT<>'0')
		and operario = '" . $nit . "'
		group by Año, Mes, operario, tecnico";
		return $this->db->query($sql);
	}

	public function get_ventas_tec_detalle($nit, $mes, $ano)
	{
		$sql = "select Año, Mes, operario, tecnico,numero_orden,cliente,
		rptos=SUM(venta_rptos), MO=SUM(Venta_mano_obra), horas_facturadas=SUM(horas)
		from v_Informe_tecnico 
		where Año=" . $ano . " and Mes=" . $mes . " and (venta_rptos<>'0' or Venta_mano_obra<>'0')
		and operario = '" . $nit . "'
		group by Año, Mes, operario, tecnico,numero_orden,cliente";
		return $this->db->query($sql);
	}

	public function get_ventas_bod($bod, $mes, $ano)
	{
		$sql = "select Año, Mes,
		rptos=SUM(venta_rptos), MO=SUM(Venta_mano_obra), horas_facturadas=SUM(horas), TOT=SUM(venta_TOT)
		from v_posv_Informe_tecnicos 
		where Año=" . $ano . " and Mes=" . $mes . " and (venta_rptos<>'0' or Venta_mano_obra<>'0' or venta_TOT<>'0')
		and sede IN(" . $bod . ")
		group by Año, Mes";
		return $this->db->query($sql);
	}

	public function get_ventas_bod_graf($bod, $mes, $ano)
	{
		$sql = "select Año,
		rptos=SUM(venta_rptos), MO=SUM(Venta_mano_obra), horas_facturadas=SUM(horas), TOT=SUM(venta_TOT),
		mes_nom = case when Mes = 1 then 'Enero'
		when Mes = 2 then 'Febrero'
		when Mes = 3 then 'Marzo'
		when Mes = 4 then 'Abril'
		when Mes = 5 then 'Mayo'
		when Mes = 6 then 'Junio'
		when Mes = 7 then 'Julio'
		when Mes = 8 then 'Agosto'
		when Mes = 9 then 'Septiembre'
		when Mes = 10 then 'Octubre'
		when Mes = 11 then 'Noviembre'
		else 'Diciembre'
		end
		from v_posv_Informe_tecnicos 
		where Año=" . $ano . " and Mes=" . $mes . " and (venta_rptos<>'0' or Venta_mano_obra<>'0' or venta_TOT<>'0')
		and sede IN(" . $bod . ")
		group by Año, Mes";
		return $this->db->query($sql);
	}

	public function get_ventas_bod_detalle($bod, $mes, $ano)
	{
		$sql = "select Año, Mes, operario, tecnico,numero_orden,cliente,
		rptos=SUM(venta_rptos), MO=SUM(Venta_mano_obra), horas_facturadas=SUM(horas)
		from v_posv_Informe_tecnicos 
		where Año=" . $ano . " and Mes=" . $mes . " and (venta_rptos<>'0' or Venta_mano_obra<>'0')
		and sede IN(" . $bod . ")
		group by Año, Mes, operario, tecnico,numero_orden,cliente";
		return $this->db->query($sql);
	}

	/*OBTIENE TODA LA INFORMACION DE LAS ORDENES POR POR BODEGA*/
	public function get_info_ot_bod($bod)
	{
		$sql = "SELECT DISTINCT b.descripcion as bodega,teo.numero,
		notas= case when hist.ot = teo.numero then (select top 1 notas from postv_historial_ot_tall where ot = teo.numero order by id_hist desc) else '' end,
		estado=case when hist.ot = teo.numero then (select top 1 estado from postv_historial_ot_tall where ot = teo.numero order by id_hist desc) else '' end,
		proceso=case when hist.ot = teo.numero then (select top 1 proceso from postv_historial_ot_tall where ot = teo.numero order by id_hist desc) else '' end,
		fecha_prom_ent=case when hist.ot = teo.numero then (select top 1 fec_promesa_entrega from postv_historial_ot_tall where ot = teo.numero order by id_hist desc) else null end,
		aseguradora=case when teo.aseguradora != 0 then ase.nombres else 'SIN ASEGURADORA' end,
		c.nombres as cliente,teo.fecha,a.nombres as asesor,
		teo.kilometraje,teo.rombo,vhv.descripcion,teo.razon2,teo.razon,vhv.placa,teo.rombo,
		dias_ot_abierta = DATEDIFF(DAY, CONVERT(DATE,teo.entrada),CONVERT(DATE,GETDATE()))
		FROM tall_encabeza_orden teo 
		LEFT JOIN tall_detalle_orden tdo ON teo.numero = tdo.numero
		INNER JOIN bodegas b ON b.bodega = teo.bodega
		INNER JOIN terceros c ON c.nit = teo.nit
		INNER JOIN terceros a ON a.nit = teo.vendedor
		INNER JOIN v_vh_vehiculos vhv ON vhv.codigo = teo.serie
		LEFT JOIN postv_historial_ot_tall hist ON hist.ot = teo.numero
		LEFT JOIN terceros ase ON ase.nit_real = teo.aseguradora
		WHERE teo.bodega IN(" . $bod . ")
		AND teo.facturada = 0 
		AND teo.anulada = 0 
		AND teo.anulada = 0
		ORDER BY teo.numero DESC";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function get_info_ot_bod_hist($ot)
	{
		$sql = "SELECT DISTINCT hist.id_hist, hist.fecha as fecha_hist, b.descripcion as bodega,teo.numero,hist.notas,hist.estado,c.nombres as cliente,teo.fecha,a.nombres as asesor,teo.kilometraje,teo.rombo,vhv.descripcion--,tlc.solicitud 
		FROM tall_encabeza_orden teo 
		LEFT JOIN tall_detalle_orden tdo ON teo.numero = tdo.numero
		INNER JOIN bodegas b ON b.bodega = teo.bodega
		INNER JOIN terceros c ON c.nit = teo.nit
		INNER JOIN terceros a ON a.nit = teo.vendedor
		INNER JOIN v_vh_vehiculos vhv ON vhv.codigo = teo.serie
		LEFT JOIN postv_historial_ot_tall hist ON hist.ot = teo.numero
		INNER JOIN tall_lista_chequeo tlc ON tlc.numero = teo.numero
		--WHERE CONVERT(DATE,entrada) >= CONVERT(DATE,'2021-01-01')
		AND teo.facturada = 0
		AND teo.anulada = 0
		AND teo.numero = " . $ot . "
		AND teo.anulada = 0
		ORDER BY hist.id_hist DESC";
		return $this->db->query($sql);
	}

	public function get_estado_ot_tall($where = "")
	{
		return $this->db->query("SELECT * FROM postv_estado_ot_tall $where");
	}

	public function get_proceso_ot_tall()
	{
		return $this->db->query("SELECT * FROM postv_proceso_ot_tall");
	}

	public function add_evento($data)
	{
		if ($this->db->insert('postv_historial_ot_tall', $data)) {
			return true;
		} else {
			return false;
		}
	}

	public function get_diff_dias_fecha($f1)
	{
		$sql = "SELECT DATEDIFF(DAY, CONVERT(DATE,GETDATE()),CONVERT(DATE,'" . $f1 . "')) AS ndias";
		//echo $sql;die;
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_ot_abiertas($bods)
	{
		$sql = "SELECT DISTINCT b.bodega, teo.numero,b.descripcion,c.nombres as cliente,ase.nombres as asesor,teo.fecha,vhv.descripcion as vh FROM tall_encabeza_orden teo 
		INNER JOIN bodegas b ON teo.bodega = b.bodega
		INNER JOIN terceros c ON c.nit = teo.nit
		INNER JOIN terceros ase ON ase.nit = teo.vendedor
		INNER JOIN v_vh_vehiculos vhv ON vhv.codigo = teo.serie
		WHERE b.bodega IN(" . $bods . ")
		AND teo.facturada = 0 
		AND teo.anulada = 0
		ORDER BY b.bodega";

		return $this->db->query($sql);
	}

	public function get_count_ot_abiertas($bods)
	{
		$sql = "SELECT b.descripcion,b.bodega, COUNT(*) AS n FROM tall_encabeza_orden teo 
		INNER JOIN bodegas b ON teo.bodega = b.bodega
		INNER JOIN terceros c ON c.nit = teo.nit
		INNER JOIN terceros ase ON ase.nit = teo.vendedor
		WHERE teo.facturada = 0
		AND teo.anulada = 0
		AND b.bodega IN (" . $bods . ")
		AND teo.anulada = 0
		GROUP BY b.descripcion,b.bodega
		ORDER BY b.bodega ";
		return $this->db->query($sql);
	}

	public function get_count_ot_abiertas_tec($bod)
	{
		$sql = "SELECT ase.nombres, COUNT(*) AS n FROM tall_encabeza_orden teo 
		INNER JOIN bodegas b ON teo.bodega = b.bodega
		INNER JOIN terceros c ON c.nit = teo.nit
		INNER JOIN terceros ase ON ase.nit = teo.vendedor
		INNER JOIN v_vh_vehiculos vhv ON vhv.codigo = teo.serie
		WHERE  teo.facturada = 0
		AND teo.anulada = 0
		AND b.bodega IN (" . $bod . ")
		GROUP BY ase.nombres";
		return $this->db->query($sql);
	}

	public function get_val_ot_abiertas($orden)
	{
		$sql = "SELECT  COUNT(*) AS n FROM tall_encabeza_orden teo 
		WHERE  teo.facturada = 0
		AND teo.anulada = 0
		AND teo.numero = " . $orden;
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}
	public function get_val_placa($placa)
	{
		$sql = "SELECT COUNT(*) AS n FROM v_vh_vehiculos vhv WHERE placa = '" . $placa . "'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_fec_prom_entrega($orden)
	{
		$sql = "SELECT TOP 1 fec_promesa_entrega FROM postv_historial_ot_tall WHERE ot = '" . $orden . "' ORDER BY id_hist DESC";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_ranking_ventas($sedes)
	{
		$sql = "SELECT usede.idsede,rtec.tecnico,rtec.r_to_vendido, ROW_NUMBER() OVER(ORDER by rtec.r_to_vendido DESC) AS ranking  FROM sw_usuariosede usede 
			INNER JOIN w_sist_usuarios su ON usede.idusuario = su.id_usuario
			INNER JOIN terceros t ON t.nit_real = su.nit_usuario
			INNER JOIN bodegas b ON usede.idsede = b.bodega
			INNER JOIN posv_ranking_tec rtec ON rtec.tecnico = su.nit_usuario
			WHERE usede.idsede IN(" . $sedes . ")
			AND CONVERT(DATE,rtec.fecha) = CONVERT(DATE,GETDATE())
			ORDER BY rtec.r_to_vendido DESC";
		return $this->db->query($sql);
	}
	public function get_ranking_nps($sedes)
	{
		$sql = "SELECT usede.idsede,rtec.tecnico,rtec.r_nps, ROW_NUMBER() OVER(ORDER by rtec.r_nps DESC) AS ranking  FROM sw_usuariosede usede 
			INNER JOIN w_sist_usuarios su ON usede.idusuario = su.id_usuario
			INNER JOIN terceros t ON t.nit_real = su.nit_usuario
			INNER JOIN bodegas b ON usede.idsede = b.bodega
			INNER JOIN posv_ranking_tec rtec ON rtec.tecnico = su.nit_usuario
			WHERE usede.idsede IN(" . $sedes . ")
			AND CONVERT(DATE,rtec.fecha) = CONVERT(DATE,GETDATE())
			ORDER BY rtec.r_nps DESC";
		return $this->db->query($sql);
	}
	/*FUNCION PARA CARGAR LA INFO DE LAS ENTRADAS DEL VH BY SAID*/
	public function get_info_entradas_vh($bod)
	{
		$sql = "SELECT x.* FROM
				(select a.id_cita,b.descripcion as nom_bodega,a.bodega,descripcion_estado, convert(varchar, fecha_hora_ini, 0) as fecha_cita,fecha_hora_ini, a.placa,f.descripcion as vh, nombre_cliente, nombre_encargado, descripcion_bahia,
				(select top 1 solicitud from tall_citas_lista_chequeo ch where a.id_cita=ch.Id_Cita) notas
				from tall_citas a inner join bodegas b
				on a.bodega=b.bodega
				left join v_vh_vehiculos v on a.placa=v.placa
				left join vh_modelo m on v.modelo=m.modelo
				left join vh_familias f on m.familia=f.familia
				where CONVERT (date,fecha_hora_ini)=CONVERT (date,GETDATE()) 
				and a.bodega in(" . $bod . ")
				and id_cita NOT IN(select id_cita from postv_entrada_vh_taller)
				)x
				where notas<>'Tiempo Adicional' and x.placa<>''
				ORDER BY fecha_hora_ini ASC
			";
		return $this->db->query($sql);
	}

	/*FUNCION PARA CARGAR LA INFO DE LAS CITAS ATENDIDAS BY SAID*/
	public function get_info_entradas_vh_atendidas($bod)
	{
		$sql = "select x.* from 
				(select a.id_cita,b.descripcion as nom_bodega,a.bodega,descripcion_estado, convert(varchar, fecha_hora_ini, 0) as fecha_cita,fecha_hora_ini, a.placa,f.descripcion as vh, nombre_cliente, nombre_encargado, descripcion_bahia,
				(select top 1 solicitud from tall_citas_lista_chequeo ch where a.id_cita=ch.Id_Cita) notas
				from tall_citas a inner join bodegas b
				on a.bodega=b.bodega
				left join v_vh_vehiculos v on a.placa=v.placa
				left join vh_modelo m on v.modelo=m.modelo
				left join vh_familias f on m.familia=f.familia
				where CONVERT (date,fecha_hora_ini)=CONVERT (date,GETDATE()) 
				and a.bodega in(" . $bod . ") 
				)x
				where notas<>'Tiempo Adicional' and x.placa<>''
				ORDER BY fecha_hora_ini ASC
			";
		return $this->db->query($sql);
	}
	/*METODO PARA BUSCAR POR PLACA*/
	public function get_info_entradas_vh_placa($bod, $placa)
	{
		$sql = "select top 1 x.* from 
		(select a.id_cita,b.descripcion as nom_bodega,a.bodega,descripcion_estado, convert(varchar, fecha_hora_ini, 0) as fecha_cita,fecha_hora_ini, a.placa,f.descripcion as vh, nombre_cliente, nombre_encargado, descripcion_bahia,
		(select top 1 solicitud from tall_citas_lista_chequeo ch where a.id_cita=ch.Id_Cita) notas
		from tall_citas a inner join bodegas b
		on a.bodega=b.bodega
		left join v_vh_vehiculos v on a.placa=v.placa
		left join vh_modelo m on v.modelo=m.modelo
		left join vh_familias f on m.familia=f.familia
		where 
		a.bodega in($bod) 
		and a.placa = '$placa'
		
		)x
		where notas<>'Tiempo Adicional'
		ORDER BY fecha_hora_ini DESC
			";
		return $this->db->query($sql);
	}
	/*METODO PARA BUSCAR POR FECHA*/
	public function get_info_entradas_vh_fecha($bod, $fecha)
	{
		$sql = "select x.* from 
				(select a.id_cita,b.descripcion as nom_bodega,a.bodega,descripcion_estado, convert(varchar, fecha_hora_ini, 0) as fecha_cita,fecha_hora_ini, a.placa,f.descripcion as vh, nombre_cliente, nombre_encargado, descripcion_bahia,
				(select top 1 solicitud from tall_citas_lista_chequeo ch where a.id_cita=ch.Id_Cita) notas
				from tall_citas a inner join bodegas b
				on a.bodega=b.bodega
				left join v_vh_vehiculos v on a.placa=v.placa
				left join vh_modelo m on v.modelo=m.modelo
				left join vh_familias f on m.familia=f.familia
				where CONVERT (date,fecha_hora_ini)=CONVERT (date,'" . $fecha . "') 
				and a.bodega in(" . $bod . ")
				AND a.estado_cita != 'C'
				and id_cita NOT IN(select id_cita from postv_entrada_vh_taller)
				)x
				where notas<>'Tiempo Adicional' and x.placa<>''
				ORDER BY fecha_hora_ini ASC
			";
		return $this->db->query($sql);
	}

	public function get_info_vh_sin_ot($bod)
	{
		$sql = "SELECT DISTINCT tc.fecha_hora_ini,convert(varchar, tc.fecha_hora_ini, 0) as fecha_cita,tc.placa,f.descripcion as vh,
		tc.nombre_cliente,tc.nombre_encargado,tc.descripcion_bahia,tc.codigo_veh
		FROM postv_entrada_vh_taller vht 
		INNER JOIN tall_citas tc ON vht.id_cita = tc.id_cita
		LEFT JOIN v_vh_vehiculos vhv ON vhv.placa = tc.placa
		INNER JOIN bodegas b ON b.bodega = tc.bodega
		LEFT JOIN vh_modelo m on vhv.modelo=m.modelo
		LEFT JOIN vh_familias f ON m.familia=f.familia
		LEFT JOIN tall_encabeza_orden teo ON teo.serie = vhv.codigo
		WHERE CONVERT(DATE,tc.fecha_hora_ini) = CONVERT(DATE,GETDATE())
		AND tc.estado_cita != 'C'
		AND b.bodega IN(" . $bod . ") AND teo.serie NOT IN (select serie from tall_encabeza_orden WHERE CONVERT(DATE,entrada) = GETDATE())";
		//echo $sql;die;
		return $this->db->query($sql);
	}

	public function insert_postv_entrada_vh_taller($id_cita)
	{
		$sql = "INSERT INTO postv_entrada_vh_taller(id_cita,fecha_hora) 
		VALUES('$id_cita',SYSDATETIME())";
		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}

	public function get_placas_busqueda($placa)
	{
		$sql = "select placa from tall_citas where placa like '" . $placa . "%'";
		return $this->db->query($sql);
	}

	public function insert_vh_sin_cita($placa, $cliente, $motivo, $sedes)
	{
		$sql = "INSERT INTO postv_vh_sin_cita VALUES ('" . $placa . "','" . $cliente . "','" . $motivo . "',SYSDATETIME(),'" . $sedes . "')";
		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}

	public function get_vh_sin_cita($bods)
	{
		$sql = "SELECT *,convert(varchar, fecha, 0) as fecha_cita 
				FROM  postv_vh_sin_cita 
				WHERE bodegas IN(".$bods.") 
				AND MONTH(CONVERT(DATE,fecha)) = MONTH(GETDATE()) 
				AND YEAR(CONVERT(DATE,fecha)) = YEAR(GETDATE()) 
				AND DAY(CONVERT(DATE,fecha)) = DAY(GETDATE())
				ORDER BY fecha_cita DESC";
		return $this->db->query($sql);
	}

	/* CONSULTAS PARA EL Informe DE ENTRADA DE VH AL TALLER */

	public function get_citas_agendadas($bods,$mes,$anio)
	{
		$sql = "SELECT COUNT(*) AS citas_agendadas FROM tall_citas tc 
				WHERE MONTH(CONVERT(DATE,fecha_hora_ini)) =". $mes."
				AND YEAR(CONVERT(DATE,fecha_hora_ini)) =". $anio."
				AND estado_cita = 'A'
				AND tc.bodega IN (" . $bods . ")";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_citas_asistidas($bods,$mes,$anio)
	{
		$sql = "SELECT COUNT(*) AS citas_asistidas FROM tall_citas tc 
				INNER JOIN postv_entrada_vh_taller et ON et.id_cita = tc.id_cita
				WHERE MONTH(CONVERT(DATE,fecha_hora_ini)) =". $mes."
				AND YEAR(CONVERT(DATE,fecha_hora_ini)) = ".$anio."
				AND estado_cita = 'A'
				AND tc.bodega IN (" . $bods . ")";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_estados_cita($bods,$mes,$anio)
	{
		$sql = "SELECT Estado_cita= case when DATEDIFF(minute,tc.fecha_hora_ini,et.fecha_hora)>=-5 and DATEDIFF(minute,tc.fecha_hora_ini,et.fecha_hora)<=5 then 'A_tiempo' 
				when DATEDIFF(minute,tc.fecha_hora_ini,et.fecha_hora)>5 then 'Llegó tarde' 
				when DATEDIFF(minute,tc.fecha_hora_ini,et.fecha_hora)<-5 then 'Llegó Antes de Tiempo' else 'No registra' end FROM tall_citas tc 
				INNER JOIN postv_entrada_vh_taller et ON et.id_cita = tc.id_cita
				WHERE MONTH(CONVERT(DATE,fecha_hora_ini)) =". $mes."
				AND YEAR(CONVERT(DATE,fecha_hora_ini)) = ".$anio."
				AND estado_cita = 'A'
				AND tc.bodega IN (" . $bods . ")";
		return $this->db->query($sql);
	}

	public function get_vh_sin_citas($bods,$mes,$anio)
	{
		$sql = "SELECT * FROM postv_vh_sin_cita vhs 
				WHERE MONTH(CONVERT(DATE,vhs.fecha)) =". $mes."
				AND YEAR(CONVERT(DATE,vhs.fecha)) = ".$anio."
				AND vhs.bodegas IN(" . $bods . ")";
		return $this->db->query($sql);
	}


	/*InformeS KPI*/

	public function get_kpi_mant_prev()
	{
		$sql = "select Sede,sum(x.enero) as enero, sum(x.febrero) as febrero, sum(x.marzo) as marzo, sum(x.abril) as abril, sum(x.mayo) as mayo, sum(x.junio) as junio
				, sum (x.julio) as julio, sum(x.agosto) as agosto, sum(x.septiembre) as septiembre, sum(x.octubre) as octubre, sum(x.noviembre) as noviembre,
				sum(x.diciembre) as diciembre  from
				(select descripcion as sede,
				Enero= case when mes=1 then ordenes else 0 end,
				Febrero= case when mes=2 then ordenes else 0 end,
				Marzo= case when mes=3 then ordenes else 0 end,
				Abril=case when mes=4 then ordenes else 0 end,
				Mayo=case when mes=5 then ordenes else 0 end,
				Junio=case when mes=6 then ordenes else 0 end,
				Julio=case when mes=7 then ordenes else 0 end,
				Agosto=case when mes=8 then ordenes else 0 end,
				Septiembre=case when mes=9 then ordenes else 0 end,
				Octubre=case when mes=10 then ordenes else 0 end,
				Noviembre=case when mes=11 then ordenes else 0 end,
				Diciembre=case when mes=12 then ordenes else 0 end
				from v_kpi_ot_mto
				)x
				group by x.sede";
		return $this->db->query($sql);
	}

	public function get_kpi_car_cli()
	{
		$sql = "select Sede,sum(x.enero) as enero, sum(x.febrero) as febrero, sum(x.marzo) as marzo, sum(x.abril) as abril, sum(x.mayo) as mayo, sum(x.junio) as junio
			, sum (x.julio) as julio, sum(x.agosto) as agosto, sum(x.septiembre) as septiembre, sum(x.octubre) as octubre, sum(x.noviembre) as noviembre,
			sum(x.diciembre) as diciembre  from
			(select descripcion as sede,
			Enero= case when mes=1 then ot else 0 end,
			Febrero= case when mes=2 then ot else 0 end,
			Marzo= case when mes=3 then ot else 0 end,
			Abril=case when mes=4 then ot else 0 end,
			Mayo=case when mes=5 then ot else 0 end,
			Junio=case when mes=6 then ot else 0 end,
			Julio=case when mes=7 then ot else 0 end,
			Agosto=case when mes=8 then ot else 0 end,
			Septiembre=case when mes=9 then ot else 0 end,
			Octubre=case when mes=10 then ot else 0 end,
			Noviembre=case when mes=11 then ot else 0 end,
			Diciembre=case when mes=12 then ot else 0 end
			from v_kpi_ot_cliente
			)x
			group by x.sede";
		return $this->db->query($sql);
	}

	public function inf_ot_tec()
	{
		$sql = "select operario, tecnico,sum(x.enero) as enero, sum(x.febrero) as febrero, sum(x.marzo) as marzo, sum(x.abril) as abril, sum(x.mayo) as mayo, sum(x.junio) as junio
			, sum (x.julio) as julio, sum(x.agosto) as agosto, sum(x.septiembre) as septiembre, sum(x.octubre) as octubre, sum(x.noviembre) as noviembre,
			sum(x.diciembre) as diciembre  from
			(select operario, tecnico,
			Enero= case when mes=1 then ot else 0 end,
			Febrero= case when mes=2 then ot else 0 end,
			Marzo= case when mes=3 then ot else 0 end,
			Abril=case when mes=4 then ot else 0 end,
			Mayo=case when mes=5 then ot else 0 end,
			Junio=case when mes=6 then ot else 0 end,
			Julio=case when mes=7 then ot else 0 end,
			Agosto=case when mes=8 then ot else 0 end,
			Septiembre=case when mes=9 then ot else 0 end,
			Octubre=case when mes=10 then ot else 0 end,
			Noviembre=case when mes=11 then ot else 0 end,
			Diciembre=case when mes=12 then ot else 0 end
			from v_kpi_factur_tecnico
			)x
			group by x.operario, x.tecnico";
		return $this->db->query($sql);
	}

	public function inf_rep_tec()
	{
		$sql = "select operario, tecnico,convert (int,sum(x.enero)) as enero, convert (int,sum(x.febrero)) as febrero, convert (int,sum(x.marzo)) as marzo,
				convert (int,sum(x.abril)) as abril, convert (int,sum(x.mayo)) as mayo, convert (int,sum(x.junio)) as junio
				,convert (int,sum (x.julio)) as julio, convert (int,sum(x.agosto)) as agosto, convert (int,sum(x.septiembre)) as septiembre,
				convert (int,sum(x.octubre)) as octubre, convert (int,sum(x.noviembre)) as noviembre,
				convert (int,sum(x.diciembre)) as diciembre  from
				(select operario, tecnico,
				Enero= case when mes=1 then repuestos else 0 end,
				Febrero= case when mes=2 then repuestos else 0 end,
				Marzo= case when mes=3 then repuestos else 0 end,
				Abril=case when mes=4 then repuestos else 0 end,
				Mayo=case when mes=5 then repuestos else 0 end,
				Junio=case when mes=6 then repuestos else 0 end,
				Julio=case when mes=7 then repuestos else 0 end,
				Agosto=case when mes=8 then repuestos else 0 end,
				Septiembre=case when mes=9 then repuestos else 0 end,
				Octubre=case when mes=10 then repuestos else 0 end,
				Noviembre=case when mes=11 then repuestos else 0 end,
				Diciembre=case when mes=12 then repuestos else 0 end
				from v_kpi_factur_tecnico
				)x
				group by x.operario, x.tecnico";
		return $this->db->query($sql);
	}

	public function inf_mo_tec()
	{
		$sql = "SELECT operario, tecnico,convert (int,sum(x.enero)) as enero, convert (int,sum(x.febrero)) as febrero, convert (int,sum(x.marzo)) as marzo,
				convert (int,sum(x.abril)) as abril, convert (int,sum(x.mayo)) as mayo, convert (int,sum(x.junio)) as junio
				,convert (int,sum (x.julio)) as julio, convert (int,sum(x.agosto)) as agosto, convert (int,sum(x.septiembre)) as septiembre,
				convert (int,sum(x.octubre)) as octubre, convert (int,sum(x.noviembre)) as noviembre,
				convert (int,sum(x.diciembre)) as diciembre  from
				(select operario, tecnico,
				Enero= case when mes=1 then mano_obra else 0 end,
				Febrero= case when mes=2 then mano_obra else 0 end,
				Marzo= case when mes=3 then mano_obra else 0 end,
				Abril=case when mes=4 then mano_obra else 0 end,
				Mayo=case when mes=5 then mano_obra else 0 end,
				Junio=case when mes=6 then mano_obra else 0 end,
				Julio=case when mes=7 then mano_obra else 0 end,
				Agosto=case when mes=8 then mano_obra else 0 end,
				Septiembre=case when mes=9 then mano_obra else 0 end,
				Octubre=case when mes=10 then mano_obra else 0 end,
				Noviembre=case when mes=11 then mano_obra else 0 end,
				Diciembre=case when mes=12 then mano_obra else 0 end
				from v_kpi_factur_tecnico
				)x
				group by x.operario, x.tecnico
				";
		return $this->db->query($sql);
	}

	/**
	 *METODO PARA LISTAR TODOS LOS TECNICOS Y VENTAS
	 *METODO PARA FILTRAR TODOS LOS TECNICOS Y VENTAS POR MES
	 *ANDRES GOMEZ
	 *2022-01-05 
	 */

	public function ventas_tec_ranking($bod, $mes, $ano)
	{
		$sql = "SELECT  Año, Mes, operario, (tn.primer_nombre+' '+tn.primer_apellido) AS tecnico,
				rptos=SUM(venta_rptos), MO=SUM(Venta_mano_obra), horas_facturadas=SUM(horas),
				suma_todo = SUM(venta_rptos) + SUM(Venta_mano_obra)
				FROM v_Informe_tecnico inf
				INNER JOIN bodegas b ON inf.sede = b.descripcion
				INNER JOIN terceros_nombres tn ON tn.nit = inf.operario
				WHERE Año=" . $ano . "
				AND Mes=" . $mes . "
				AND (venta_rptos<>'0' or Venta_mano_obra<>'0')
				AND b.bodega NOT IN(21,9,14,22)
				AND b.bodega IN (" . $bod . ")
				GROUP BY Año, Mes, operario, tecnico,tn.primer_nombre,tn.primer_apellido
				ORDER BY suma_todo DESC";
		return $this->db->query($sql);
	}

	/**
	 *METODO PARA LISTAR TODOS LOS TECNICOS Y VENTAS TRIMESTRAL
	 *ANDRES GOMEZ
	 *2022-01-05 
	 */

	public function ventas_tec_ranking_trimestral($bod, $mes, $ano)
	{
		$sql = "SELECT  Año, Mes, operario, (tn.primer_nombre+' '+tn.primer_apellido) AS tecnico, horas_facturadas=SUM(horas),
				suma_todo = SUM(venta_rptos) + SUM(Venta_mano_obra)
				FROM v_Informe_tecnico inf
				INNER JOIN bodegas b ON inf.sede = b.descripcion
				INNER JOIN terceros_nombres tn ON tn.nit = inf.operario
				WHERE Año=" . $ano . "
				AND Mes IN (" . $mes . ")
				AND (venta_rptos<>'0' or Venta_mano_obra<>'0')
				AND b.bodega NOT IN(21,9,14,22)
				AND b.bodega IN (" . $bod . ")
				GROUP BY operario,Año, Mes, tecnico,tn.primer_nombre,tn.primer_apellido
				ORDER BY suma_todo DESC";
		return $this->db->query($sql);
	}

	/*METODO PARA TRAER LOS DOC QUE HACE EL USUARIO DE DMURCIA*/
	public function get_doc_murcia()
	{
		$sql = "select tt.descripcion, a.tipo, numero,convert (date,a.fecha) as fecha, a.nit, nombres
				from documentos a inner join terceros t on a.nit=t.nit
				inner join tipo_transacciones tt on a.tipo=tt.tipo
				where fecha>='20220126' and a.usuario='DMURCIA'
				and a.tipo not in(select f.tipo from fac_dm f) and numero not in (select doc from fac_dm)";
		return $this->db->query($sql);
	}

	//insert fac_dm
	public function insert_fac_dm($data)
	{
		$this->db->insert('fac_dm', $data);
	}

	/*FUNCION PARA CARGAR LA INFO DE LAS CITAS ATENDIDAS BY SAID*/
	public function get_info_entradas_vh_atendidas_placa($bod,$placa)
	{
		$sql = "select top 1 x.* from 
		(select a.id_cita,b.descripcion as nom_bodega,a.bodega,descripcion_estado, convert(varchar, fecha_hora_ini, 0) as fecha_cita,fecha_hora_ini, a.placa,f.descripcion as vh, nombre_cliente, nombre_encargado, descripcion_bahia,
		(select top 1 solicitud from tall_citas_lista_chequeo ch where a.id_cita=ch.Id_Cita) notas
		from tall_citas a inner join bodegas b
		on a.bodega=b.bodega
		left join v_vh_vehiculos v on a.placa=v.placa
		left join vh_modelo m on v.modelo=m.modelo
		left join vh_familias f on m.familia=f.familia
		where 
		--CONVERT (date,fecha_hora_ini)=CONVERT (date,GETDATE()) 
		a.bodega in($bod)
		and a.placa = '$placa'				
		)x
		where notas<>'Tiempo Adicional'
		ORDER BY fecha_hora_ini DESC
			";
		return $this->db->query($sql);
	}

	public function get_info_vh_sin_ot_placa($bod,$placa)
	{
		$sql = "SELECT DISTINCT top 1 tc.fecha_hora_ini,convert(varchar, tc.fecha_hora_ini, 0) as fecha_cita,tc.placa,f.descripcion as vh,
		tc.nombre_cliente,tc.nombre_encargado,tc.descripcion_bahia,tc.codigo_veh
		FROM postv_entrada_vh_taller vht 
		INNER JOIN tall_citas tc ON vht.id_cita = tc.id_cita
		LEFT JOIN v_vh_vehiculos vhv ON vhv.placa = tc.placa
		INNER JOIN bodegas b ON b.bodega = tc.bodega
		LEFT JOIN vh_modelo m on vhv.modelo=m.modelo
		LEFT JOIN vh_familias f ON m.familia=f.familia
		LEFT JOIN tall_encabeza_orden teo ON teo.serie = vhv.codigo
		WHERE 
		--CONVERT(DATE,tc.fecha_hora_ini) = CONVERT(DATE,GETDATE())
		tc.estado_cita != 'C'
		and tc.placa = '$placa'
		AND b.bodega IN($bod) AND teo.serie NOT IN (select serie from tall_encabeza_orden WHERE CONVERT(DATE,entrada) = GETDATE())
		order by fecha_cita desc";
		//echo $sql;die;
		return $this->db->query($sql);
	}

	public function get_vh_sin_cita_placa($bods,$placa)
	{
		$sql = "SELECT *,convert(varchar, fecha, 0) as fecha_cita 
		FROM  postv_vh_sin_cita 
		WHERE bodegas IN($bods) 
		AND MONTH(CONVERT(DATE,fecha)) = MONTH(GETDATE()) 
		AND YEAR(CONVERT(DATE,fecha)) = YEAR(GETDATE()) 
		AND DAY(CONVERT(DATE,fecha)) = DAY(GETDATE())
		AND placa = '$placa'
		ORDER BY fecha_cita DESC";
		return $this->db->query($sql);
	}

	
}
