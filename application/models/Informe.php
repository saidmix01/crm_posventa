<?php

use Complex\Functions;

/**
 * 
 */
class Informe extends CI_Model
{

	public function inf_leads_agentes()
	{
		$sql = "SELECT t.nit,t.nombres,COUNT(*) AS n_leads FROM swcc_bancoleads l
		INNER JOIN w_sist_usuarios us ON us.id_usuario = l.idagente
		INNER JOIN terceros t  ON t.nit = us.nit_usuario
		WHERE CONVERT(DATE,l.fechahora_ing) BETWEEN CONVERT(DATE,DATEADD(month, DATEDIFF(month, -1, getdate()) - 2, 0)) 
		AND CONVERT(DATE,DATEADD(ss, -1, DATEADD(month, DATEDIFF(month, 0, getdate()), 0)))
		GROUP BY t.nit,t.nombres
		";
		return $this->db->query($sql);
	}

	public function get_total_dias()
	{
		$sql = "SELECT DAY(DATEADD(s,-1,DATEADD(mm, DATEDIFF(m,0,GETDATE())+1,0))) AS 'ultimo_dia'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_dias_actual()
	{
		$sql = "SELECT DAY(GETDATE()) AS 'dia'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_fecha_actual()
	{
		$sql = "SELECT CONVERT(VARCHAR,GETDATE(),22) AS 'fecha'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_fecha()
	{
		$sql = "SELECT CONVERT(VARCHAR,GETDATE(),23) AS 'fecha'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_nombre_mes_actual()
	{
		$sql = "SELECT DATENAME(month,GETDATE()) AS mes";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_primer_dia_mes()
	{
		$sql = "SELECT CONVERT(DATE,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0),103) AS fecha";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_nombre_mes($fecha)
	{
		$sql = "SELECT DATENAME(month,'$fecha') AS mes";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_mes_ano_actual()
	{
		$sql = "SELECT MONTH(GETDATE()) as mes, Year(GetDate()) as ano";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	/*CONSULTAS Informe PAC*/

	/*RETORNA LA INFO DEL LA SEDE DIA ACTUAL*/
	public function get_calificacion_sede($sede)
	{
		$sql = "SELECT DISTINCT * FROM NPS_sedes 
		WHERE CONVERT(DATE,Fecha) = CONVERT(DATE,GETDATE())
		AND Sede = '" . $sede . "'";
		return $this->db->query($sql);
	}

	/*RETORNA LA INFO DEL LA SEDE POR DIA*/
	public function get_calificacion_sede_by_dia($sede, $fecha)
	{
		$sql = "SELECT * FROM NPS_sedes 
		WHERE CONVERT(DATE,Fecha) = CONVERT(DATE,'" . $fecha . "')
		AND Sede = '" . $sede . "'";
		return $this->db->query($sql);
	}

	public function get_data_sedes()
	{
		$sql = "SELECT DISTINCT * FROM NPS_sedes 
		WHERE CONVERT(DATE,Fecha) = CONVERT(DATE,GETDATE())
		AND Sede != 'general'";
		return $this->db->query($sql);
	}
	public function get_nps_historico($mes)
	{
		$sql = "SELECT TOP 1 *,DateName(month,Fecha) + ' ' +DateName(year,Fecha) AS mes_ano FROM NPS_sedes 
		WHERE MONTH(Fecha) = MONTH(DATEADD(month, " . $mes . ", GETDATE()))
		AND Sede = 'general'
		ORDER BY Fecha DESC";
		return $this->db->query($sql);
	}

	//MACHETAZO PARA ARREGLAR, LA GRAFICA
	public function machetazo()
	{
		$sql = "SELECT TOP 1 *,DateName(month,Fecha) + ' ' +DateName(year,Fecha) AS mes_ano FROM NPS_sedes 
		WHERE CONVERT(DATE,Fecha) = CONVERT(DATE,'2020-04-30')
		AND Sede = 'general'
		ORDER BY Fecha DESC";
		return $this->db->query($sql);
	}

	public function get_nps_int_historico($mes)
	{
		$sql = "SELECT DateName(month,CONVERT(date,fecha)) + ' ' +DateName(year,CONVERT(date,fecha)) AS fecha, 
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 0 AND 6 THEN 'enc0a6' END) AS enc0a6,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 7 AND 8 THEN 'enc7A8' END) AS enc7a8,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 9 AND 10 THEN 'enc9A10' END) AS enc9a10
		FROM postv_encuesta_satisfaccion_qr pes
		
		WHERE  MONTH(CONVERT(DATE,pes.fecha)) = MONTH(DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()) " . $mes . ", 0))
		AND pes.bod IN(1,9,11,21,7,6,19,8,14,16,22)
		
		GROUP BY pes.fecha
		ORDER BY pes.fecha";
		return $this->db->query($sql);
	}

	public function get_nps_general_actual()
	{
		$sql = "SELECT TOP 1 *,DateName(month,Fecha) + ' ' +DateName(year,Fecha) AS mes_ano FROM NPS_sedes 
		WHERE MONTH(Fecha) = MONTH(DATEADD(month, -12, GETDATE()))
		AND Sede = 'general'
		ORDER BY Fecha ASC
		";
		return $this->db->query($sql);
	}

	public function get_nps_general_actual_int()
	{
		$sql = "SELECT TOP 1 * FROM NPS_sedes 
		WHERE MONTH(Fecha) = MONTH(DATEADD(month, -12, GETDATE()))
		AND Sede = 'general'
		ORDER BY Fecha ASC
		";
		return $this->db->query($sql);
	}
	//NUEVA FUNCION
	public function get_data_tecnicos($sede)
	{
		$sql = "SELECT nom_tecnico as nombres, COUNT(CASE
		WHEN recomendacion_concesionario BETWEEN 0 and 6 THEN 'enc0a6' end) as enc0a6,
		COUNT(CASE
		WHEN recomendacion_concesionario BETWEEN 7 and 8 THEN 'enc7a8' end) as enc7a8,
		COUNT(CASE
		WHEN recomendacion_concesionario BETWEEN 9 and 10 THEN 'enc9a10' end) as enc9a10
		From postv_encuestas_gm
		WHERE CONVERT(DATE,fecha_recibido_enc) BETWEEN CONVERT(DATE,DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)) AND CONVERT(DATE,GETDATE())
		AND sede = '" . $sede . "'
		GROUP BY nom_tecnico
		
		";
		return $this->db->query($sql);
	}

	public function get_nps_gm($sedes, $mes)
	{
		$sql = "SELECT nom_tecnico as nombres,sede,MONTH(fecha_recibido_enc) AS mes, COUNT(CASE
		WHEN recomendacion_concesionario BETWEEN 0 and 6 THEN 'enc0a6' end) as enc0a6,
		COUNT(CASE
		WHEN recomendacion_concesionario BETWEEN 7 and 8 THEN 'enc7a8' end) as enc7a8,
		COUNT(CASE
		WHEN recomendacion_concesionario BETWEEN 9 and 10 THEN 'enc9a10' end) as enc9a10
		From postv_encuestas_gm
		WHERE MONTH(CONVERT(DATE,fecha_recibido_enc)) = MONTH(DATEADD(MONTH, -" . $mes . ",GETDATE()))
		AND sede IN(" . $sedes . ")
		GROUP BY nom_tecnico,sede,fecha_recibido_enc";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function get_nps_tec_col($sede, $mes, $ano)
	{
		$sql = "SELECT nombres, COUNT(CASE
		WHEN calificacion BETWEEN 0 and 6 THEN 'enc0a6' end) as enc0a6,
		COUNT(CASE
		WHEN calificacion BETWEEN 7 and 8 THEN 'enc7a8' end) as enc7a8,
		COUNT(CASE
		WHEN calificacion BETWEEN 9 and 10 THEN 'enc9a10' end) as enc9a10
		From nps_tec
		WHERE MONTH(CONVERT(DATE,fecha_enc)) = " . $mes . " AND YEAR(CONVERT(DATE,fecha_enc)) = " . $ano . "
		AND sede = '" . $sede . "'
		GROUP BY nombres
		";
		return $this->db->query($sql);
	}

	public function get_nps_tec_col_all($mes, $ano)
	{
		$sql = "SELECT nombres, COUNT(CASE
		WHEN calificacion BETWEEN 0 and 6 THEN 'enc0a6' end) as enc0a6,
		COUNT(CASE
		WHEN calificacion BETWEEN 7 and 8 THEN 'enc7a8' end) as enc7a8,
		COUNT(CASE
		WHEN calificacion BETWEEN 9 and 10 THEN 'enc9a10' end) as enc9a10
		From nps_tec
		WHERE MONTH(CONVERT(DATE,fecha_enc)) IN( " . $mes . " ) AND YEAR(CONVERT(DATE,fecha_enc)) = " . $ano . "
		GROUP BY nombres
		";
		return $this->db->query($sql);
	}

	public function get_data_nps_by_tec($tec)
	{
		$sql = "SELECT nombres, COUNT(CASE
		WHEN calificacion BETWEEN 0 and 6 THEN 'enc0a6' end) as enc0a6,
		COUNT(CASE
		WHEN calificacion BETWEEN 7 and 8 THEN 'enc7a8' end) as enc7a8,
		COUNT(CASE
		WHEN calificacion BETWEEN 9 and 10 THEN 'enc9a10' end) as enc9a10
		From nps_tec
		WHERE CONVERT(DATE,fecha_enc) BETWEEN DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0) AND GETDATE()
		AND nit_tec = '" . $tec . "'
		GROUP BY nombres";
		return $this->db->query($sql);
	}

	public function get_data_nps_by_tec_graf($tec)
	{
		$sql = "SELECT nombres, COUNT(CASE
		WHEN calificacion BETWEEN 0 and 6 THEN 'enc0a6' end) as enc0a6,
		COUNT(CASE
		WHEN calificacion BETWEEN 7 and 8 THEN 'enc7a8' end) as enc7a8,
		COUNT(CASE
		WHEN calificacion BETWEEN 9 and 10 THEN 'enc9a10' end) as enc9a10
		From nps_tec
		WHERE CONVERT(DATE,fecha_enc) BETWEEN DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0) AND GETDATE()
		AND nit_tec = '" . $tec . "'
		GROUP BY nombres";
		return $this->db->query($sql);
	}

	public function get_data_nps_by_tec_buscar($tec, $mes, $ano)
	{
		$sql = "SELECT nombres, COUNT(CASE
		WHEN calificacion BETWEEN 0 and 6 THEN 'enc0a6' end) as enc0a6,
		COUNT(CASE
		WHEN calificacion BETWEEN 7 and 8 THEN 'enc7a8' end) as enc7a8,
		COUNT(CASE
		WHEN calificacion BETWEEN 9 and 10 THEN 'enc9a10' end) as enc9a10
		From nps_tec
		WHERE MONTH(CONVERT(DATE,fecha_enc)) = " . $mes . " AND YEAR(CONVERT(DATE,fecha_enc)) = " . $ano . "
		AND nit_tec = '" . $tec . "'
		GROUP BY nombres";
		return $this->db->query($sql);
	}

	public function get_data_tecnicos_by_dia($sede, $fecha)
	{
		$sql = "SELECT nom_tecnico as nombres, COUNT(CASE
		WHEN recomendacion_concesionario BETWEEN 0 and 6 THEN 'enc0a6' end) as enc0a6,
		COUNT(CASE
		WHEN recomendacion_concesionario BETWEEN 7 and 8 THEN 'enc7a8' end) as enc7a8,
		COUNT(CASE
		WHEN recomendacion_concesionario BETWEEN 9 and 10 THEN 'enc9a10' end) as enc9a10
		From postv_encuestas_gm
		WHERE MONTH(CONVERT(DATE,fecha_recibido_enc)) = MONTH(CONVERT(DATE,'$fecha'))
		AND sede = '" . $sede . "'
		GROUP BY nom_tecnico
		";
		return $this->db->query($sql);
	}

	/*FUNCION PARA CALCULO DE NPS INTERNO POR TECNICO*/

	public function get_data_nps_interno_tec($bod)
	{
		$sql = "SELECT t.nombres, 
		COUNT(CASE WHEN pes.pregunta2 BETWEEN 0 AND 6 THEN 'enc0a6' END) AS enc0a6,
		COUNT(CASE WHEN pes.pregunta2 BETWEEN 7 AND 8 THEN 'enc7A8' END) AS enc7a8,
		COUNT(CASE WHEN pes.pregunta2 BETWEEN 9 AND 10 THEN 'enc9A10' END) AS enc9a10
		FROM posv_encuesta_satisfaccion pes
		INNER JOIN tall_encabeza_orden teo ON teo.numero = pes.n_orden
		INNER JOIN terceros t ON t.nit_real = teo.vendedor
		WHERE CONVERT(DATE,pes.fecha) BETWEEN CONVERT(DATE,DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)) 
		AND CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm,0,DATEADD(mm,DATEDIFF(mm,0,GETDATE())+1,0))))
		AND teo.bodega IN(" . $bod . ")
		GROUP BY t.nombres
		";
		return $this->db->query($sql);
	}

	/*FUNCION PARA EL CALCULO DEL NPS INTERNO POR SEDE*/

	public function get_data_nps_interno_sedes($bod)
	{
		$sql = "SELECT  t.nombres as tecnico,pes.bod,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 0 AND 6 THEN 'enc0a6' END) AS enc0a6,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 7 AND 8 THEN 'enc7A8' END) AS enc7a8,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 9 AND 10 THEN 'enc9A10' END) AS enc9a10
		FROM postv_encuesta_satisfaccion_qr pes inner join referencias_imp r
		on pes.placa=r.placa
		inner join v_ultima_entrada_taller_datos uet on r.codigo=uet.uetd_serie
		inner join tall_encabeza_orden te on uet.uetd_numero=te.numero
		inner join terceros t on te.vendedor=t.nit
		where MONTH(CONVERT(DATE,pes.fecha)) = MONTH(DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-0, 0))
		and YEAR(CONVERT(DATE,pes.fecha)) = YEAR(GETDATE()) and pes.bod in (" . $bod . ")
		group by t.nombres,pes.bod
		";

		return $this->db->query($sql);
	}

	public function get_data_nps_interno_sedes_by_mes($bod, $mes)
	{
		$sql = "SELECT t.nombres, t.nit, pes.fecha,pes.n_orden,pes.id,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 0 AND 6 THEN 'enc0a6' END) AS enc0a6,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 7 AND 8 THEN 'enc7A8' END) AS enc7a8,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 9 AND 10 THEN 'enc9A10' END) AS enc9a10
		FROM posv_encuesta_satisfaccion pes
		INNER JOIN tall_encabeza_orden teo ON teo.numero = pes.n_orden
		INNER JOIN terceros t ON t.nit_real = teo.vendedor
		WHERE MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real)) IN (" . $mes . ")
		AND teo.bodega IN(" . $bod . ")
		GROUP BY t.nombres,t.nit,pes.fecha,pes.n_orden,pes.id
		";
		return $this->db->query($sql);
	}

	public function get_nps_by_tec($nit)
	{
		$sql = "SELECT t.nombres,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 0 AND 6 THEN 'enc0a6' END) AS enc0a6,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 7 AND 8 THEN 'enc7A8' END) AS enc7a8,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 9 AND 10 THEN 'enc9A10' END) AS enc9a10
		FROM posv_encuesta_satisfaccion pes
		INNER JOIN tall_encabeza_orden teo ON teo.numero = pes.n_orden
		INNER JOIN terceros t ON t.nit_real = teo.vendedor
		WHERE CONVERT(DATE,teo.fecha_hora_entrega_real) BETWEEN CONVERT(DATE,DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)) 
		AND CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm,0,DATEADD(mm,DATEDIFF(mm,0,GETDATE())+1,0))))
		AND t.nit_real = '" . $nit . "'
		GROUP BY t.nombres";
		return $this->db->query($sql);
	}

	public function get_nps_by_tec_gm_graf($nit, $mes, $ano)
	{
		$sql = "SELECT nombres, COUNT(CASE
		WHEN calificacion BETWEEN 0 and 6 THEN 'enc0a6' end) as enc0a6,
		COUNT(CASE
		WHEN calificacion BETWEEN 7 and 8 THEN 'enc7a8' end) as enc7a8,
		COUNT(CASE
		WHEN calificacion BETWEEN 9 and 10 THEN 'enc9a10' end) as enc9a10,
		mes_nom = case when MONTH(CONVERT(DATE,fecha_enc) ) = 1 then 'Enero'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 2 then 'Febrero'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 3 then 'Marzo'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 4 then 'Abril'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 5 then 'Mayo'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 6 then 'Junio'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 7 then 'Julio'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 8 then 'Agosto'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 9 then 'Septiembre'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 10 then 'Octubre'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 11 then 'Noviembre'
		else 'Diciembre'
		end
		From nps_tec
		WHERE MONTH(CONVERT(DATE,fecha_enc) ) = " . $mes . " AND YEAR(CONVERT(DATE,fecha_enc) ) = " . $ano . "
		AND nit_tec = '" . $nit . "'
		GROUP BY nombres,fecha_enc";
		return $this->db->query($sql);
	}

	public function get_nps_by_bod_gm_graf($sede, $mes, $ano)
	{
		$sql = "SELECT nombres, COUNT(CASE
		WHEN calificacion BETWEEN 0 and 6 THEN 'enc0a6' end) as enc0a6,
		COUNT(CASE
		WHEN calificacion BETWEEN 7 and 8 THEN 'enc7a8' end) as enc7a8,
		COUNT(CASE
		WHEN calificacion BETWEEN 9 and 10 THEN 'enc9a10' end) as enc9a10,
		mes_nom = case when MONTH(CONVERT(DATE,fecha_enc) ) = 1 then 'Enero'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 2 then 'Febrero'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 3 then 'Marzo'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 4 then 'Abril'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 5 then 'Mayo'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 6 then 'Junio'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 7 then 'Julio'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 8 then 'Agosto'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 9 then 'Septiembre'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 10 then 'Octubre'
		when MONTH(CONVERT(DATE,fecha_enc) ) = 11 then 'Noviembre'
		else 'Diciembre'
		end
		From nps_tec
		WHERE MONTH(CONVERT(DATE,fecha_enc) ) = " . $mes . " AND YEAR(CONVERT(DATE,fecha_enc) ) = " . $ano . "
		AND sede = '" . $sede . "'
		GROUP BY nombres,fecha_enc";
		//echo $sql; die;
		return $this->db->query($sql);
	}

	public function get_nps_by_tec_buscar($nit, $mes, $ano)
	{
		$sql = "SELECT t.nombres,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 0 AND 6 THEN 'enc0a6' END) AS enc0a6,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 7 AND 8 THEN 'enc7A8' END) AS enc7a8,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 9 AND 10 THEN 'enc9A10' END) AS enc9a10
		FROM posv_encuesta_satisfaccion pes
		INNER JOIN tall_encabeza_orden teo ON teo.numero = pes.n_orden
		INNER JOIN terceros t ON t.nit_real = teo.vendedor
		WHERE MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = " . $mes . " AND YEAR(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = " . $ano . "
		AND t.nit_real = '" . $nit . "'
		GROUP BY t.nombres";
		return $this->db->query($sql);
	}

	public function get_nps_int_bod_graf($bod, $mes, $ano)
	{
		$sql = "SELECT t.nombres,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 0 AND 6 THEN 'enc0a6' END) AS enc0a6,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 7 AND 8 THEN 'enc7A8' END) AS enc7a8,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 9 AND 10 THEN 'enc9A10' END) AS enc9a10,
		mes_nom = case when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 1 then 'Enero'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 2 then 'Febrero'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 3 then 'Marzo'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 4 then 'Abril'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 5 then 'Mayo'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 6 then 'Junio'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 7 then 'Julio'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 8 then 'Agosto'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 9 then 'Septiembre'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 10 then 'Octubre'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 11 then 'Noviembre'
		else 'Diciembre'
		end
		FROM posv_encuesta_satisfaccion pes
		INNER JOIN tall_encabeza_orden teo ON teo.numero = pes.n_orden
		INNER JOIN terceros t ON t.nit_real = teo.vendedor
		WHERE MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = " . $mes . " AND YEAR(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = " . $ano . "
		AND teo.bodega IN(" . $bod . ")
		GROUP BY t.nombres,teo.fecha_hora_entrega_real";
		//echo $sql;die;
		return $this->db->query($sql);
	}

	public function get_nps_int_tec_graf($nit, $mes, $ano)
	{
		$sql = "SELECT t.nombres,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 0 AND 6 THEN 'enc0a6' END) AS enc0a6,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 7 AND 8 THEN 'enc7A8' END) AS enc7a8,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 9 AND 10 THEN 'enc9A10' END) AS enc9a10,
		mes_nom = case when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 1 then 'Enero'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 2 then 'Febrero'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 3 then 'Marzo'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 4 then 'Abril'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 5 then 'Mayo'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 6 then 'Junio'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 7 then 'Julio'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 8 then 'Agosto'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 9 then 'Septiembre'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 10 then 'Octubre'
		when MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = 11 then 'Noviembre'
		else 'Diciembre'
		end
		FROM posv_encuesta_satisfaccion pes
		INNER JOIN tall_encabeza_orden teo ON teo.numero = pes.n_orden
		INNER JOIN terceros t ON t.nit_real = teo.vendedor
		WHERE MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = " . $mes . " AND YEAR(CONVERT(DATE,teo.fecha_hora_entrega_real) ) = " . $ano . "
		AND t.nit_real = '" . $nit . "'
		GROUP BY t.nombres,teo.fecha_hora_entrega_real";
		return $this->db->query($sql);
	}



	public function get_data_nps_interno_sedes_mes($bod)
	{
		$sql = "SELECT t.nombres, 
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 0 AND 6 THEN 'enc0a6' END) AS enc0a6,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 7 AND 8 THEN 'enc7A8' END) AS enc7a8,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 9 AND 10 THEN 'enc9A10' END) AS enc9a10
		FROM posv_encuesta_satisfaccion pes
		INNER JOIN tall_encabeza_orden teo ON teo.numero = pes.n_orden
		INNER JOIN terceros t ON t.nit_real = teo.vendedor
		WHERE CONVERT(DATE,teo.fecha_hora_entrega_real) BETWEEN CONVERT(DATE,DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)) 
		AND CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm,0,DATEADD(mm,DATEDIFF(mm,0,GETDATE())+1,0))))
		AND teo.bodega IN(" . $bod . ")
		GROUP BY t.nombres
		";
		return $this->db->query($sql);
	}

	/*public function get_data_tecnicos($sede){
		$sql = "SELECT t.nombres,SUM(nps.Enc_0_a_6) AS enc0a6, SUM(nps.Enc_7_a_8) AS enc7a8, SUM(nps.Enc_9_a_10) AS enc9a10 FROM NPS_tecnicos nps
				INNER JOIN terceros t ON t.nit_real = nps.tecnico
				WHERE CONVERT(DATE,fecha) BETWEEN CONVERT(DATE,DATEADD(dd,-(DAY(GETDATE())-1),GETDATE()),101)
				AND CONVERT(DATE,GETDATE())
				AND Sede = '".$sede."'
				GROUP BY t.nombres";
		return $this->db->query($sql);
	}*/

	public function Informe_presupuesto_by_sedes()
	{
		$sql = "
		SELECT  total = SUM(valor * -1), fecha = CONVERT(DATE,GETDATE()), sede='giron'
		FROM movimiento
		WHERE (cuenta LIKE '4135%' OR cuenta LIKE '4175%' OR cuenta LIKE '530535%') and centro in (4,40,33,45,3)
		AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
		AND CONVERT(date,GETDATE())
		AND
		tipo not IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL')
		UNION
		SELECT  total = SUM(valor * -1), fecha = CONVERT(DATE,GETDATE()), sede='rosita'
		FROM movimiento
		WHERE (cuenta LIKE '4135%' OR cuenta LIKE '4175%' OR cuenta LIKE '530535%') and centro in (16,17)
		AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
		AND CONVERT(date,GETDATE())
		AND
		tipo not IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL')
		UNION
		SELECT  total = SUM(valor * -1), fecha = CONVERT(DATE,GETDATE()), sede='barranca'
		FROM movimiento
		WHERE (cuenta LIKE '4135%' OR cuenta LIKE '4175%' OR cuenta LIKE '530535%') and centro in (13,70,11)
		AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
		AND CONVERT(date,GETDATE())
		AND
		tipo not IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL')
		UNION
		SELECT  total = SUM(valor * -1), fecha = CONVERT(DATE,GETDATE()), sede='bocono'
		FROM movimiento
		WHERE (cuenta LIKE '4135%' OR cuenta LIKE '4175%' OR cuenta LIKE '530535%') and centro in (29,80,31,46,28)
		AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
		AND CONVERT(date,GETDATE())
		AND
		tipo not IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL')
		UNION
		SELECT  total = SUM(valor * -1), fecha = CONVERT(DATE,GETDATE()), sede='solochevrolet'
		FROM movimiento
		WHERE (cuenta LIKE '4135%' OR cuenta LIKE '4175%' OR cuenta LIKE '530535%') and centro in (60)
		AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
		AND CONVERT(date,GETDATE())
		AND
		tipo not IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL')
		UNION
		SELECT  total = SUM(valor * -1), fecha = CONVERT(DATE,GETDATE()), sede='chevropartes'
		FROM movimiento
		WHERE (cuenta LIKE '4135%' OR cuenta LIKE '4175%' OR cuenta LIKE '530535%') and centro in (15)
		AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
		AND CONVERT(date,GETDATE())
		AND
		tipo not IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL')
		";
		return $this->db->query($sql);
	}

	public function Informe_inventario()
	{
		$sql = "select Promedio, stock, r.codigo,calificacion_abc from referencias r
		inner join v_referencias_cos v_r ON r.codigo = v_r.codigo
		inner join v_referencias_sto_hoy vr on vr.codigo = r.codigo
		where bodega in (1,3,4,6,7,8,13,23,25,94,95,96,97,98)
		and stock != 0 and v_r.ano=YEAR(CONVERT(date,getdate())) and v_r.mes=MONTH(CONVERT(date,getdate())) 
		and r.conversion != -1
		and r.contable in (100,105,110) 
		order by calificacion_abc";
		return $this->db->query($sql);
	}

	public function Informe_inventario_bod()
	{
		$sql = "select distinct Convert(int,cosh.promedio) as Promedio,stock, a.codigo,b.calificacion_abc,a.bodega,bd.descripcion
		from v_referencias_sto_hoy a 
		inner join v_referencias_cos cosh on a.codigo=cosh.codigo 
		inner join referencias b on a.codigo=b.codigo
		left join bodegas bd on a.bodega=bd.bodega
		where a.stock>0  and a.bodega in (1,3,4,6,7,8,13,23,25,94,95,96,97,98) 
		and cosh.ano=YEAR(CONVERT(date,getdate())) and cosh.mes=MONTH(CONVERT(date,getdate())) 
		and b.contable in (100,105,110)
		order by calificacion_abc";
		return $this->db->query($sql);
	}

	/*public function cant_enc_enviadas_satis($m1, $m2)
	{
		$sql = "SELECT COUNT(*)AS n_encuestas, CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . $m1 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110) AS fecha FROM postv_auditoria_mensajes
		WHERE fecha BETWEEN CONVERT(DATE,DATEADD(mm," . $m2 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)),110)
		AND CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . $m1 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)
		AND tipo = 'Encuesta de satisfaccion'";
		return $this->db->query($sql);
	}*/

	public function cant_enc_enviadas_satis($mes)
	{
		$sql = "SELECT COUNT(*)as n_encuestas,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm,1,DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110) AS fecha 
				FROM postv_encuesta_satisfaccion_qr WHERE MONTH(CONVERT(DATE,fecha)) = MONTH(DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()) -" . $mes . ", 0))";
		return $this->db->query($sql);
	}

	public function cant_enc_resp($mes)
	{
		$sql = "SELECT COUNT(*)as n_encuestas,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm,1,DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110) AS fecha 
				FROM postv_encuesta_satisfaccion_qr WHERE MONTH(CONVERT(DATE,fecha)) = MONTH(DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()) -" . $mes . ", 0))";
		return $this->db->query($sql);
	}
	public function cant_ord_finalizada($mes)
	{
		$sql = "SELECT TOP 1 COUNT(DISTINCT numero_orden) as ord_fin, 
				DATENAME (MONTH, DATEADD(MONTH, MONTH(fec) - 1, '1900-01-01')) as mes,YEAR(fec) as anio FROM tall_documentos_lin 
				WHERE MONTH(fec) = MONTH(DATEADD(MONTH, " . $mes . ",GETDATE())) 
				and bodega in (1,11,9,21,7,6,19,8,14,16,22)
				GROUP BY MONTH(fec),YEAR(fec)
				ORDER BY YEAR(fec) DESC";

		return $this->db->query($sql);
	}

	public function get_mant_prepagado()
	{
		$sql = "SELECT codigo,placa,z.nit,cliente,(cv.credito_niif-debito_niif) as valor_pagado, mto,revision_5000KM,revision_10000KM,revision_15000KM,revision_20000KM,revision_25000KM,revision_30000KM,revision_35000KM,revision_40000KM,revision_45000KM,revision_50000KM,
		valor_facturado=(revision_5000KM+revision_10000KM+revision_15000KM+revision_20000KM+revision_25000KM+revision_30000KM+revision_35000KM+revision_40000KM+revision_45000KM+revision_50000KM)
		from cuentas_val cv inner join
		(
		select distinct y.codigo,y.placa,y.nit,y.cliente,y.mto, y.revision_5000KM, y.revision_10000KM,y.revision_15000KM,y.revision_20000KM,y.revision_25000KM,y.revision_30000KM, y.revision_35000KM,y.revision_40000KM,y.revision_45000KM ,
		revision_50000KM=case when y.mto<>'MPC 50.000' then 0  else isnull(M11.valor_factura ,0)end  
		from

		(select x.codigo,x.placa, x.nit,x.cliente,x.mto, x.revision_5000KM, x.revision_10000KM,x.revision_15000KM,x.revision_20000KM, x.revision_25000KM,x.revision_30000KM, x.revision_35000KM,x.revision_40000KM,
		revision_45000KM=case when x.mto<>'MPC 50.000' then 0  else isnull(M10.valor_factura ,0)end from  

		(select w.codigo,w.placa,w.nit,w.cliente,w.mto, w.revision_5000KM, w.revision_10000KM,w.revision_15000KM,w.revision_20000KM, w.revision_25000KM,w.revision_30000KM,w.revision_35000KM,
		revision_40000KM=case when w.mto in ('MPC 20.000','MPC 30.000') then 0  else isnull(M9.valor_factura ,0)end  from

		(select v.codigo,v.placa,v.nit,v.cliente,v.mto, v.revision_5000KM, v.revision_10000KM,v.revision_15000KM,v.revision_20000KM,v.revision_25000KM,v.revision_30000KM,
		revision_35000KM=case when v.mto in ('MPC 20.000','MPC 30.000') then 0  else isnull(M8.valor_factura ,0)end  from

		(select u.codigo,u.placa,u.nit,u.cliente,u.mto, u.revision_5000KM, u.revision_10000KM,u.revision_15000KM,u.revision_20000KM,u.revision_25000KM,
		revision_30000KM=case when u.mto='MPC 20.000' then 0  else isnull(M7.valor_factura ,0)end  from

		(select t.codigo,t.placa,t.nit,t.cliente,t.mto, t.revision_5000KM, t.revision_10000KM,t.revision_15000KM,t.revision_20000KM,
		revision_25000KM=case when t.mto='MPC 20.000' then 0 else isnull(M6.valor_factura ,0) end from

		(select s.codigo,s.placa,s.nit,s.cliente,s.mto, s.revision_5000KM, s.revision_10000KM,s.revision_15000KM, isnull(M5.valor_factura ,0) as revision_20000KM   from

		(select r.codigo,r.placa,r.nit,r.cliente,r.mto, r.revision_5000KM, r.revision_10000KM,isnull(M4.valor_factura ,0) as revision_15000KM   from

		(select q.codigo,q.placa,q.nit,q.cliente, q.mto, q.revision_5000KM,isnull(M3.valor_factura ,0) as revision_10000KM   from


		(select ev.codigo,vv.placa,vv.nit_comprador as nit, t.nombres as cliente, ev.descripcion as mto,isnull(M2.valor_factura ,0) as revision_5000KM
		from v_vh_eventos_vehiculos ev inner join v_vh_vehiculos vv on ev.codigo=vv.codigo
		inner join terceros t on vv.nit_comprador=t.nit
		left join

		(select dl.serie,d.valor_total as valor_factura from  tall_documentos_lin dl  
		inner join tall_tempario tt on dl.operacion=tt.operacion
		inner join documentos d on dl.tipo=d.tipo and dl.numero=d.numero
		where (tt.descripcion like '% 5.000%' or tt.descripcion like '% 5000 %' or tt.descripcion like '% 5000')  and bloquear=0 and dl.clase_trabajo='C'
		)M2
		on ev.codigo=M2.serie
		where ev.evento in (455,460,465,470) ) q

		left join

		(select v.placa,dl.serie,t.nombres,d.valor_total as valor_factura, tt.descripcion from  tall_documentos_lin dl  left join v_vh_vehiculos v on dl.serie=v.codigo
		inner join tall_tempario tt on dl.operacion=tt.operacion
		inner join documentos d on dl.tipo=d.tipo and dl.numero=d.numero
		inner join terceros t on v.nit_comprador=t.nit
		where (tt.descripcion like '%10.000%' or tt.descripcion like '%10000 %' or tt.descripcion like '%10000') and bloquear=0 and dl.clase_trabajo='C'
		)M3
		on q.codigo=m3.serie) r

		left join

		(select v.placa,dl.serie,t.nombres,d.valor_total as valor_factura, tt.descripcion from  tall_documentos_lin dl  left join v_vh_vehiculos v on dl.serie=v.codigo
		inner join tall_tempario tt on dl.operacion=tt.operacion
		inner join documentos d on dl.tipo=d.tipo and dl.numero=d.numero
		inner join terceros t on v.nit_comprador=t.nit
		where (tt.descripcion like '%15.000%' or tt.descripcion like '%15000 %' or tt.descripcion like '%15000') and bloquear=0 and dl.clase_trabajo='C'
		)M4
		on r.codigo=M4.serie
		)s

		left join

		(select v.placa,dl.serie,t.nombres,d.valor_total as valor_factura, tt.descripcion from  tall_documentos_lin dl  left join v_vh_vehiculos v on dl.serie=v.codigo
		inner join tall_tempario tt on dl.operacion=tt.operacion
		inner join documentos d on dl.tipo=d.tipo and dl.numero=d.numero
		inner join terceros t on v.nit_comprador=t.nit
		where (tt.descripcion like '%20.000%' or tt.descripcion like '%20000 %' or tt.descripcion like '%20000') and bloquear=0 and dl.clase_trabajo='C'
		)M5
		on s.codigo=M5.serie
		)t

		left join

		(select v.placa,dl.serie,t.nombres,d.valor_total as valor_factura, tt.descripcion from  tall_documentos_lin dl  left join v_vh_vehiculos v on dl.serie=v.codigo
		inner join tall_tempario tt on dl.operacion=tt.operacion
		inner join documentos d on dl.tipo=d.tipo and dl.numero=d.numero
		inner join terceros t on v.nit_comprador=t.nit
		where (tt.descripcion like '%25.000%' or tt.descripcion like '%25000 %' or tt.descripcion like '%25000') and bloquear=0 and dl.clase_trabajo='C'
		)M6
		on t.codigo=M6.serie
		)u
		left join

		(select v.placa,dl.serie,t.nombres,d.valor_total as valor_factura, tt.descripcion from  tall_documentos_lin dl  left join v_vh_vehiculos v on dl.serie=v.codigo
		inner join tall_tempario tt on dl.operacion=tt.operacion
		inner join documentos d on dl.tipo=d.tipo and dl.numero=d.numero
		inner join terceros t on v.nit_comprador=t.nit
		where (tt.descripcion like '%30.000%' or tt.descripcion like '%30000 %' or tt.descripcion like '%30000') and bloquear=0 and dl.clase_trabajo='C'
		)M7
		on u.codigo=M7.serie
		)v

		left join

		(select v.placa,dl.serie,t.nombres,d.valor_total as valor_factura, tt.descripcion from  tall_documentos_lin dl  left join v_vh_vehiculos v on dl.serie=v.codigo
		inner join tall_tempario tt on dl.operacion=tt.operacion
		inner join documentos d on dl.tipo=d.tipo and dl.numero=d.numero
		inner join terceros t on v.nit_comprador=t.nit
		where (tt.descripcion like '%35.000%' or tt.descripcion like '%35000 %' or tt.descripcion like '%35000') and bloquear=0 and dl.clase_trabajo='C'
		)M8
		on v.codigo=M8.serie
		)w

		left join

		(select v.placa,dl.serie,t.nombres,d.valor_total as valor_factura, tt.descripcion from  tall_documentos_lin dl  left join v_vh_vehiculos v on dl.serie=v.codigo
		inner join tall_tempario tt on dl.operacion=tt.operacion
		inner join documentos d on dl.tipo=d.tipo and dl.numero=d.numero
		inner join terceros t on v.nit_comprador=t.nit
		where (tt.descripcion like '%35.000%' or tt.descripcion like '%35000 %' or tt.descripcion like '%35000') and bloquear=0 and dl.clase_trabajo='C'
		)M9
		on w.codigo=M9.serie
		)x

		left join

		(select v.placa,dl.serie,t.nombres,d.valor_total as valor_factura, tt.descripcion from  tall_documentos_lin dl  left join v_vh_vehiculos v on dl.serie=v.codigo
		inner join tall_tempario tt on dl.operacion=tt.operacion
		inner join documentos d on dl.tipo=d.tipo and dl.numero=d.numero
		inner join terceros t on v.nit_comprador=t.nit
		where (tt.descripcion like '%35.000%' or tt.descripcion like '%35000 %' or tt.descripcion like '%35000') and bloquear=0 and dl.clase_trabajo='C'
		)M10
		on x.codigo=M10.serie
		)y

		left join

		(select v.placa,dl.serie,t.nombres,d.valor_total as valor_factura, tt.descripcion from  tall_documentos_lin dl  left join v_vh_vehiculos v on dl.serie=v.codigo
		inner join tall_tempario tt on dl.operacion=tt.operacion
		inner join documentos d on dl.tipo=d.tipo and dl.numero=d.numero
		inner join terceros t on v.nit_comprador=t.nit
		where (tt.descripcion like '%35.000%' or tt.descripcion like '%35000 %' or tt.descripcion like '%35000') and bloquear=0 and dl.clase_trabajo='C'
		)M11
		on y.codigo=M11.serie
		)z
		on z.nit=cv.nit
		where cuenta='28050547' and (cv.credito<>0 and saldo_inicial_niif='0')";
		return $this->db->query($sql);
	}

	/*******************************************PQR Y NPS*******************************************************/

	public function info_vh_Fby_placa($placa)
	{
		$sql = "SELECT DISTINCT vhv.placa,vhv.serie,vhv.descripcion,vhv.nit_comprador,t.nombres,t.celular,t.mail 
		FROM v_vh_vehiculos vhv
	INNER JOIN tall_encabeza_orden teo ON vhv.codigo = teo.serie
	INNER JOIN terceros t ON t.nit = vhv.nit_comprador
	WHERE placa = '" . $placa . "'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function val_data_pqr_nps($tipo, $id, $op)
	{
		$condicional = $op == 1 ? " AND tipificacion_cierre IS NOT NULL and comentarios_final_caso IS NOT NULL AND tipificacion_encuesta IS NOT NULL and estado_caso IS NOT NULL" : "";
		//$condicional = $op == 2 && $op != 1 ? " AND tipificacion_cierre IS NOT NULL AND comentarios_final_caso IS NOT NULL" : "";
		$sql = "SELECT * FROM postv_pqr_nps WHERE fuente='" . $tipo . "' AND id_fuente=" . $id . $condicional;
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function data_list_verbs($id)
	{
		$sql = "SELECT * FROM postv_pqr_comentarios WHERE id_pqr_nps=" . $id;
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result;
		} else {
			return null;
		}
	}

	/*consultar encuestas segun el id y nit */

	public function taerEncuesta($nit, $mes, $orden, $bodega, $id)
	{
		$sql = "SELECT t.nombres, t.nit, pes.fecha,pes.n_orden,pes.pregunta5,pes.id,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 0 AND 6 THEN 'enc0a6' END) AS enc0a6,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 7 AND 8 THEN 'enc7A8' END) AS enc7a8,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 9 AND 10 THEN 'enc9A10' END) AS enc9a10
		FROM posv_encuesta_satisfaccion pes
		INNER JOIN tall_encabeza_orden teo ON teo.numero = pes.n_orden
		INNER JOIN terceros t ON t.nit_real = teo.vendedor
		WHERE MONTH(CONVERT(DATE,teo.fecha_hora_entrega_real)) IN (" . $mes . ") AND teo.bodega IN(" . $bodega . ") AND t.nit = '" . $nit . "' AND pes.n_orden = '" . $orden . "' AND pes.id = '" . $id . "'
		GROUP BY t.nombres,t.nit,pes.fecha,pes.n_orden,pes.pregunta5,pes.id";
		return $respuesta = $this->db->query($sql);
	}

	/*
	 * traer datos de encuesta de por de los tecnicos
	 */
	public function encuesta_nps()
	{
		$sql = "SELECT t.nit,t.nombres,pregunta1,pregunta2,pregunta3,pregunta4,pregunta5,pes.fecha,pes.n_orden
		FROM posv_encuesta_satisfaccion pes
		INNER JOIN tall_encabeza_orden teo ON pes.n_orden = teo.numero
		INNER JOIN terceros t ON teo.vendedor = t.nit_real
		";
		return $respuesta = $this->db->query($sql);
	}

	/*
	*taer datos de npsm por filtro de mes y sede
	*/

	public function taer_nps_por_sede_y_mes($mes, $sede)
	{
		$sql = "SELECT t.nit,t.nombres,pregunta1,pregunta2,pregunta3,pregunta4,pregunta5,pes.fecha,pes.n_orden,teo.bodega
		FROM posv_encuesta_satisfaccion pes
		INNER JOIN tall_encabeza_orden teo ON pes.n_orden = teo.numero
		INNER JOIN terceros t ON teo.vendedor = t.nit_real
		WHERE  MONTH(CONVERT(DATE,pes.fecha)) IN ($mes) AND  teo.bodega IN ($sede)
		";
		return $this->db->query($sql);
	}

	public function get_facs_sin_ot()
	{
		$sql = "SELECT * FROM v_documentos_tot  
		WHERE docu not in (SELECT tot FROM v_doctot WHERE Fecha_TOT=convert(date,GETDATE()))
		AND fecha=convert(date,GETDATE())";
		return $this->db->query($sql);
	}

	public function datos_de_Informe_nps($mes, $yearActual, $nit_tec)
	{
		$sql = "SELECT t.nit,t.nombres, pes.fecha, b.descripcion, 
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 0 AND 6 THEN 'enc0a6' END) AS enc0a6,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 7 AND 8 THEN 'enc7A8' END) AS enc7a8,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 9 AND 10 THEN 'enc9A10' END) AS enc9a10
		FROM posv_encuesta_satisfaccion pes 
		INNER JOIN tall_encabeza_orden teo ON pes.n_orden = teo.numero 
		INNER JOIN terceros t ON teo.vendedor = t.nit_real 
		INNER JOIN bodegas b ON teo.bodega = b.bodega 
		WHERE MONTH(CONVERT(DATE,pes.fecha)) =$mes
		AND YEAR(CONVERT(DATE,pes.fecha)) =$yearActual 
		AND t.nit=$nit_tec
		GROUP BY t.nit,t.nombres,pes.fecha,b.descripcion";
		return $this->db->query($sql);
	}

	public function obtener_tecnicos_nps($yearActual)
	{
		$sql = "SELECT DISTINCT t.nit,t.nombres FROM tall_operarios op
		INNER JOIN tall_encabeza_orden teo ON op.nit = teo.vendedor
		INNER JOIN posv_encuesta_satisfaccion pes ON teo.numero = pes.n_orden
		INNER JOIN terceros t ON t.nit = teo.vendedor
		WHERE t.nit NOT IN (300,305)
		AND YEAR(CONVERT(DATE,pes.fecha)) = " . $yearActual;
		return $this->db->query($sql);
	}

	/**
	 * METODO PARA LISTAR LOS HOARIOS DE INGRESO Y SALIDA SOLO MOBRE,NIT,SEDE
	 * ANDRES GOMEZ
	 * 30-12-2021
	 * NO SE ESTA UTILIZANDO
	 */
	public function listar_horas()
	{
		try {
			$sql = "SELECT i.id_reg_ingreso, i.empleado , t.nombres ,i.sede,i.accion,
			CONVERT(DATE,i.fecha_hora) AS fechas, 
			RIGHT (i.fecha_hora,7) AS horas
			FROM registro_ingreso i
			INNER JOIN w_sist_usuarios u ON i.empleado = u.nit_usuario 
			INNER JOIN terceros t ON i.empleado = t.nit
			where CONVERT(DATE,i.fecha_hora) = CONVERT(VARCHAR(10), GETDATE(), 23) AND i.empleado = i.empleado
			ORDER BY empleado,CONVERT(TIME,fecha_hora) ASC";
			return $this->db->query($sql);
		} catch (Exception $error) {
			echo 'Message' . $error->getMessage();
		}
	}



	/**
	 * METODO PARA REALIZAR FILTRO POR FECHA,NIT,SEDE
	 * ANDRES GOMEZ
	 * 03-01-2022
	 */

	public function filtar_horarios($where)
	{
		try {
			$sql = "SELECT i.id_reg_ingreso, i.empleado , t.nombres ,i.sede,i.accion,
			CONVERT(DATE,i.fecha_hora) AS fechas, 
			RIGHT (i.fecha_hora,7) AS horas
			FROM registro_ingreso i
			INNER JOIN w_sist_usuarios u ON i.empleado = u.nit_usuario 
			INNER JOIN terceros t ON i.empleado = t.nit
			WHERE accion IS NOT NULL " . $where . "
			ORDER BY empleado, CONVERT(DATE,fecha_hora) ASC
			";
			return $this->db->query($sql);
			//return $sql;
		} catch (Exception $error) {
			echo 'Message: ' . $error->getMessage();
		}
	}

	/**
	 * METODO PARA LISTAR HOARARIOS POR USUARIO Y SEDE
	 */
	public function listar_horarios_por_sede_user($usu)
	{
		try {
			$sql = "SELECT i.id_reg_ingreso, i.empleado , t.nombres ,i.sede,i.accion,
			CONVERT(DATE,i.fecha_hora) AS fechas, 
			RIGHT (i.fecha_hora,7) AS horas
			FROM registro_ingreso i
			INNER JOIN w_sist_usuarios u ON i.empleado = u.nit_usuario 
			INNER JOIN terceros t ON i.empleado = t.nit
			WHERE accion IS NOT NULL  AND empleado = $usu AND CONVERT(DATE,i.fecha_hora) = CONVERT(DATE,GETDATE()) ORDER BY empleado, CONVERT(TIME,fecha_hora)ASC
			";
			return $this->db->query($sql);
		} catch (Exception $error) {
			echo 'Message: ' . $error->getMessage();
		}
	}

	/**
	 * METODO PARA LISTAR HOARARIOS POR USUARIO Y SEDE
	 */
	public function filtro_listar_horarios_por_sede_user($usu, $fecha)
	{
		try {
			$sql = "SELECT i.id_reg_ingreso, i.empleado , t.nombres ,i.sede,i.accion,
			CONVERT(DATE,i.fecha_hora) AS fechas, 
			RIGHT (i.fecha_hora,7) AS horas
			FROM registro_ingreso i
			INNER JOIN w_sist_usuarios u ON i.empleado = u.nit_usuario 
			INNER JOIN terceros t ON i.empleado = t.nit
			WHERE accion IS NOT NULL  AND empleado = $usu AND CONVERT(DATE,i.fecha_hora) = CONVERT(DATE,'$fecha') ORDER BY empleado, CONVERT(TIME,fecha_hora) ASC
			";
			//return $sql;
			return $this->db->query($sql);
		} catch (Exception $error) {
			echo 'Message: ' . $error->getMessage();
		}
	}



	/**
	 * METODO PARA LISTAR NPS  INTERNO Y COLMOTORES
	 * ANDRES GOMEZ
	 * 11-01-2022
	 */
	public function filtro_nps_final($fecha, $mes, $sede)
	{
		$consulta = "";
		try {
			$sql = " SELECT a.mes, a.vendedor,a.nombres,a.enc0a6_interno,a.enc7a8_interno,a.enc9a10_interno,b.enc0a6_colmotores,b.enc7a8_colmotores,b.enc9a10_colmotores
			FROM
			(SELECT e.mes, e.vendedor,e.nombres,sum(e.enc0a6_interno) AS enc0a6_interno,sum(e.enc7a8_interno) AS enc7a8_interno,sum(e.enc9a10_interno) AS enc9a10_interno
			FROM (
			SELECT distinct teo.vendedor,t.nombres,pes.n_orden, MONTH(CONVERT(DATE,pes.fecha)) AS mes, 
			CASE WHEN pes.pregunta1 BETWEEN 0 AND 6 THEN '1' else 0 END AS enc0a6_interno,
			(CASE WHEN pes.pregunta1 BETWEEN 7 AND 8 THEN '1' else 0 END) AS enc7a8_interno,
			(CASE WHEN pes.pregunta1 BETWEEN 9 AND 10 THEN '1' else 0 END) AS enc9a10_interno
			
			FROM posv_encuesta_satisfaccion pes 
			INNER JOIN tall_encabeza_orden teo ON pes.n_orden = teo.numero 
			INNER JOIN terceros t ON teo.vendedor = t.nit 
			INNER JOIN bodegas b ON teo.bodega = b.bodega 
			WHERE MONTH(CONVERT(DATE,pes.fecha)) IN ($mes)
			AND YEAR(CONVERT(DATE,pes.fecha)) = $fecha
			)e			
			GROUP BY e.vendedor,e.nombres,e.mes
			)a
			
			INNER JOIN
			
			(SELECT  e.vendedor,e.nombres,sum(e.enc0a6_colmotores) AS enc0a6_colmotores,sum(e.enc7a8_colmotores) AS enc7a8_colmotores,
			sum(e.enc9a10_colmotores) AS enc9a10_colmotores
			FROM (
			SELECT pes.nit_tec AS vendedor,
			t.nombres, MONTH(CONVERT(DATE,pes.fecha_enc)) AS mes, 
			(CASE WHEN calificacion BETWEEN 0 and 6 THEN '1' else 0 END) AS enc0a6_colmotores,
			(CASE WHEN calificacion BETWEEN 7 and 8 THEN '1' else 0 END) AS enc7a8_colmotores,
			(CASE WHEN calificacion BETWEEN 9 and 10 THEN '1' else 0 END) AS enc9a10_colmotores
			FROM nps_tec pes 
			INNER JOIN terceros t ON pes.nit_tec = t.nit 
			
			WHERE MONTH(CONVERT(DATE,pes.fecha_enc)) IN ($mes)
			AND YEAR(CONVERT(DATE,pes.fecha_enc)) = $fecha
			AND pes.nit_tec <> 'ANONYMOUS'
			AND pes.sede  IN ($sede)
			)e			
			GROUP BY e.vendedor,e.nombres) b ON a.vendedor=b.vendedor";
			return $this->db->query($sql);
			/*
			$totales = $this->db->query($sql);
			if ($totales->num_rows() > 0) {
				return $this->db->query($sql);
			} else {
				return 0;
			}
			*/
		} catch (Exception $error) {
			die($error->getMessage());
		}
	}
	/**
	 * METODO PARA LISTAR Informe SEGUNDA ENTREGA
	 * SERGIO GALVIS
	 * 07-04-2022
	 */
	public function listar_segunda_entrega($fecha_actual, $fecha_inicio)
	{

		$sql = "
		select v.año, v.mes, v.dia, count(distinct v.codigo) as entregas, count (distinct e.id_cita) as agendas from
		(select distinct codigo, YEAR(fecha_hora_evento) as año,month(fecha_hora_evento) as mes,day(fecha_hora_evento) as dia  
		from vh_eventos_vehiculos where evento=115
		and CONVERT(DATE,fecha_hora_evento) BETWEEN CONVERT(DATE,'$fecha_inicio') AND CONVERT(DATE,'$fecha_actual')
		)v

		left join

		(select distinct c.id_cita,c.codigo_veh, YEAR(fecha_hora_creacion) as año,month(fecha_hora_creacion) as mes,day(fecha_hora_creacion) as dia
		from tall_citas c inner join tall_citas_operaciones o
		on c.id_cita=o.id_cita
		inner join tall_tempario tt on o.codigo_operacion=tt.operacion
		where tt.descripcion like '%SEGUNDA ENTREGA%'
		and CONVERT(DATE,fecha_hora_creacion) BETWEEN CONVERT (DATE,'$fecha_inicio') AND CONVERT(DATE,'$fecha_actual')
		)e
		on v.codigo=e.codigo_veh
		group by v.año, v.mes, v.dia
		";
		return $this->db->query($sql);
	}
	public function inf_detallado_segunda_entrega($fecha_actual, $fecha_inicio)
	{

		$sql = "
		select ev.año,ev.mes,ev.dia,ev.vehiculo,ev.sede,
		Agendado_por=case when a.agendó is null then 'Sin agendar' else a.agendó end
		from
		(select distinct a.codigo as vehiculo,SUBSTRING(tt.descripcion,30,60) as sede, YEAR(fecha_hora_evento) as año,
		month(fecha_hora_evento) as mes,day(fecha_hora_evento) as dia
		from vh_eventos_vehiculos a inner join documentos_lin dl
		on a.codigo=dl.codigo
		inner join tipo_transacciones tt on dl.tipo=tt.tipo
		where evento=115 and dl.sw=1
		and CONVERT(DATE,fecha_hora_evento) BETWEEN CONVERT(DATE,'$fecha_inicio') AND CONVERT(DATE,'$fecha_actual')
		) ev

		left join

		(select distinct c.codigo_veh as vh, u.des_usuario as agendó
		from tall_citas c inner join tall_citas_operaciones o
		on c.id_cita=o.id_cita
		inner join tall_tempario tt on o.codigo_operacion=tt.operacion
		inner join usuarios u on c.usuario=u.usuario
		where tt.descripcion like '%SEGUNDA ENTREGA%'  and CONVERT(DATE,fecha_hora_creacion) BETWEEN CONVERT(DATE,'$fecha_inicio') AND CONVERT(DATE,'$fecha_actual')
		) a
		on ev.vehiculo=a.vh
		";
		return $this->db->query($sql);
	}
	/* Informe ventas ultimos 72 meses */
	public function getVentas72()
	{
		$sql = "
		select a.tipo_vh, p0_12,a.e_0_12,
		p13_24,b.e_13_24,
		c.p25_36,c.e_25_36,
		d.p37_48,d.e_37_48,
		e.p49_60,e.e_49_60,
		f.p61_72,f.e_61_72
		
		from
		
		(select COUNT(codigo) as p0_12,SUM(ultima_entrada) as e_0_12, tipo_vh
		from v_datos_retencion_flotas
		where tipo_vh='Flota' and Meses<=12
		group by  tipo_vh
		)a
		join
		(select COUNT(codigo) as p13_24,SUM(ultima_entrada) as e_13_24, tipo_vh
		from v_datos_retencion_flotas
		where tipo_vh='Flota' and Meses between 13 and 24
		group by  tipo_vh
		)b
		on a.tipo_vh=b.tipo_vh
		join
		(select COUNT(codigo) as p25_36,SUM(ultima_entrada) as e_25_36, tipo_vh
		from v_datos_retencion_flotas
		where tipo_vh='Flota' and Meses between 25 and 36
		group by  tipo_vh
		)c
		on a.tipo_vh=c.tipo_vh
		join
		(select COUNT(codigo) as p37_48,SUM(ultima_entrada) as e_37_48, tipo_vh
		from v_datos_retencion_flotas
		where tipo_vh='Flota' and Meses between 37 and 48
		group by  tipo_vh
		)d
		on a.tipo_vh=d.tipo_vh
		join
		(select COUNT(codigo) as p49_60,SUM(ultima_entrada) as e_49_60, tipo_vh
		from v_datos_retencion_flotas
		where tipo_vh='Flota' and Meses between 49 and 60
		group by  tipo_vh
		)e
		on a.tipo_vh=e.tipo_vh
		join
		(select COUNT(codigo) as p61_72,SUM(ultima_entrada) as e_61_72, tipo_vh
		from v_datos_retencion_flotas
		where tipo_vh='Flota' and Meses>=61
		group by  tipo_vh
		)f
		on a.tipo_vh=f.tipo_vh
		
		union
		
		select a.tipo_vh, p0_12,a.e_0_12,
		p13_24,b.e_13_24,
		c.p25_36,c.e_25_36,
		d.p37_48,d.e_37_48,
		e.p49_60,e.e_49_60,
		f.p61_72,f.e_61_72
		
		from
		
		(select COUNT(codigo) as p0_12,SUM(ultima_entrada) as e_0_12, tipo_vh
		from v_datos_retencion_flotas
		where tipo_vh='Retail' and Meses<=12
		group by  tipo_vh
		)a
		join
		(select COUNT(codigo) as p13_24,SUM(ultima_entrada) as e_13_24, tipo_vh
		from v_datos_retencion_flotas
		where tipo_vh='Retail' and Meses between 13 and 24
		group by  tipo_vh
		)b
		on a.tipo_vh=b.tipo_vh
		join
		(select COUNT(codigo) as p25_36,SUM(ultima_entrada) as e_25_36, tipo_vh
		from v_datos_retencion_flotas
		where tipo_vh='Retail' and Meses between 25 and 36
		group by  tipo_vh
		)c
		on a.tipo_vh=c.tipo_vh
		join
		(select COUNT(codigo) as p37_48,SUM(ultima_entrada) as e_37_48, tipo_vh
		from v_datos_retencion_flotas
		where tipo_vh='Retail' and Meses between 37 and 48
		group by  tipo_vh
		)d
		on a.tipo_vh=d.tipo_vh
		join
		(select COUNT(codigo) as p49_60,SUM(ultima_entrada) as e_49_60, tipo_vh
		from v_datos_retencion_flotas
		where tipo_vh='Retail' and Meses between 49 and 60
		group by  tipo_vh
		)e
		on a.tipo_vh=e.tipo_vh
		join
		(select COUNT(codigo) as p61_72,SUM(ultima_entrada) as e_61_72, tipo_vh
		from v_datos_retencion_flotas
		where tipo_vh='Retail' and Meses>=61
		group by  tipo_vh
		)f
		on a.tipo_vh=f.tipo_vh
		";
		$result = $this->db->query($sql);
		if ($result != "") {
			return $result;
		} else {
			return $result;
		}
	}
	/* Funcion para traer la clasificacion(segmentos) de tipos= Autos  */
	public function dataAutos()
	{
		$sql = "select distinct (segmento) from v_datos_retencion_flotas where tipo = 'Autos'";
		$result = $this->db->query($sql);
		return $result;
	}
	/* Funcion para traer la clasificacion(segmentos) de tipos= B&C  */
	public function dataByC()
	{
		$sql = "select distinct segmento from v_datos_retencion_flotas where tipo = 'B&C'";
		$result = $this->db->query($sql);
		return $result;
	}
	public function dataAutosDetalle()
	{
		$sql = "select distinct segmento, familia from v_datos_retencion_flotas where tipo = 'Autos' order by segmento";
		$result = $this->db->query($sql);
		return $result;
	}
	public function dataByCDetalle()
	{
		$sql = "select distinct segmento, familia from v_datos_retencion_flotas where tipo = 'B&C' order by segmento";
		$result = $this->db->query($sql);
		return $result;
	}
	/* Funcion para filtrar por segmentos de los tipos Autos */
	public function dataAutosFiltro($filtro)
	{
		if ($filtro == "Autos" || $filtro == "B&C") {
			$result =  "select tipo,tipo_vh,isnull(sum(p0_12),0) as p0_12,
			isnull(sum(p13_24),0) as p13_24,
			isnull(sum(p25_36),0) as p25_36,
			isnull(sum(p37_48),0) as p37_48,
			isnull(sum(p49_60),0) as p49_60,
			isnull(sum(p61_72),0) as p61_72,
			isnull(sum(e_0_12),0) as e_0_12,
			isnull(sum(e_13_24),0) as e_13_24,
			isnull(sum(e_25_36),0) as e_25_36,
			isnull(sum(e_37_48),0) as e_37_48,
			isnull(sum(e_49_60),0) as e_49_60,
			isnull(sum(e_61_72),0) as e_61_72
			from
			(
			select distinct s.tipo, s.segmento,tipo_vh,
			p0_12=case when grupo='p0_12' then isnull(COUNT(codigo),0) end,
			p13_24=case when grupo='p13_24' then isnull(COUNT(codigo),0) end,
			p25_36=case when grupo='p25_36' then isnull(COUNT(codigo),0) end,
			p37_48=case when grupo='p37_48' then isnull(COUNT(codigo),0) end,
			p49_60=case when grupo='p49_60' then isnull(COUNT(codigo),0) end,
			p61_72=case when grupo='p61_72' then isnull(COUNT(codigo),0) end,
			e_0_12=case when grupo='p0_12' then isnull(sum(ultima_entrada),0) end,
			e_13_24=case when grupo='p13_24' then isnull(sum(ultima_entrada),0) end,
			e_25_36=case when grupo='p25_36' then isnull(sum(ultima_entrada),0) end,
			e_37_48=case when grupo='p37_48' then isnull(sum(ultima_entrada),0) end,
			e_49_60=case when grupo='p49_60' then isnull(sum(ultima_entrada),0) end,
			e_61_72=case when grupo='p61_72' then isnull(sum(ultima_entrada),0) end
			from postv_segmento_vh s left join
			(select tipo,segmento,codigo,tipo_vh,ultima_entrada,b.familia,
			grupo=case when Meses<=12 then 'p0_12'
			when Meses between '13' and '24' then 'p13_24'
			when Meses between '25' and '36' then 'p25_36'
			when Meses between '37' and '48' then 'p37_48'
			when Meses between '49' and '60' then 'p49_60'
			else 'p61_72' end
			from v_datos_retencion_flotas a inner join vh_familias b on a.familia=b.descripcion) f
			on s.tipo=f.tipo and s.segmento=f.segmento and s.familia=f.familia
			where tipo_vh is not null
			group by s.tipo, s.segmento,grupo,tipo_vh
			)p
			where tipo = '$filtro'
			group by tipo,tipo_vh
			order by tipo_vh, tipo";

			return $this->db->query($result);
		} else {
			$result =  "select tipo, segmento,tipo_vh,isnull(sum(p0_12),0) as p0_12,
			isnull(sum(p13_24),0) as p13_24,
			isnull(sum(p25_36),0) as p25_36,
			isnull(sum(p37_48),0) as p37_48,
			isnull(sum(p49_60),0) as p49_60,
			isnull(sum(p61_72),0) as p61_72,
			isnull(sum(e_0_12),0) as e_0_12,
			isnull(sum(e_13_24),0) as e_13_24,
			isnull(sum(e_25_36),0) as e_25_36,
			isnull(sum(e_37_48),0) as e_37_48,
			isnull(sum(e_49_60),0) as e_49_60,
			isnull(sum(e_61_72),0) as e_61_72
			from
			(
			select distinct s.tipo, s.segmento,tipo_vh,
			p0_12=case when grupo='p0_12' then isnull(COUNT(codigo),0) end,
			p13_24=case when grupo='p13_24' then isnull(COUNT(codigo),0) end,
			p25_36=case when grupo='p25_36' then isnull(COUNT(codigo),0) end,
			p37_48=case when grupo='p37_48' then isnull(COUNT(codigo),0) end,
			p49_60=case when grupo='p49_60' then isnull(COUNT(codigo),0) end,
			p61_72=case when grupo='p61_72' then isnull(COUNT(codigo),0) end,
			e_0_12=case when grupo='p0_12' then isnull(sum(ultima_entrada),0) end,
			e_13_24=case when grupo='p13_24' then isnull(sum(ultima_entrada),0) end,
			e_25_36=case when grupo='p25_36' then isnull(sum(ultima_entrada),0) end,
			e_37_48=case when grupo='p37_48' then isnull(sum(ultima_entrada),0) end,
			e_49_60=case when grupo='p49_60' then isnull(sum(ultima_entrada),0) end,
			e_61_72=case when grupo='p61_72' then isnull(sum(ultima_entrada),0) end
			from postv_segmento_vh s left join
			(select tipo,segmento,codigo,tipo_vh,ultima_entrada,b.familia,
			grupo=case when Meses<=12 then 'p0_12'
			when Meses between '13' and '24' then 'p13_24'
			when Meses between '25' and '36' then 'p25_36'
			when Meses between '37' and '48' then 'p37_48'
			when Meses between '49' and '60' then 'p49_60'
			else 'p61_72' end
			from v_datos_retencion_flotas a inner join vh_familias b on a.familia=b.descripcion) f
			on s.tipo=f.tipo and s.segmento=f.segmento and s.familia=f.familia
			where tipo_vh is not null
			group by s.tipo, s.segmento,grupo,tipo_vh
			)p
			where segmento = '$filtro'
			group by tipo, segmento,tipo_vh
			order by tipo_vh, tipo,segmento";
			return $this->db->query($result);
		}
	}

	public function dataByCFiltro($filtro)
	{

		if ($filtro == "B&C") {
			$result =  "select tipo,tipo_vh,isnull(sum(p0_12),0) as p0_12,
			isnull(sum(p13_24),0) as p13_24,
			isnull(sum(p25_36),0) as p25_36,
			isnull(sum(p37_48),0) as p37_48,
			isnull(sum(p49_60),0) as p49_60,
			isnull(sum(p61_72),0) as p61_72,
			isnull(sum(e_0_12),0) as e_0_12,
			isnull(sum(e_13_24),0) as e_13_24,
			isnull(sum(e_25_36),0) as e_25_36,
			isnull(sum(e_37_48),0) as e_37_48,
			isnull(sum(e_49_60),0) as e_49_60,
			isnull(sum(e_61_72),0) as e_61_72
			from
			(
			select distinct s.tipo, s.segmento,tipo_vh,
			p0_12=case when grupo='p0_12' then isnull(COUNT(codigo),0) end,
			p13_24=case when grupo='p13_24' then isnull(COUNT(codigo),0) end,
			p25_36=case when grupo='p25_36' then isnull(COUNT(codigo),0) end,
			p37_48=case when grupo='p37_48' then isnull(COUNT(codigo),0) end,
			p49_60=case when grupo='p49_60' then isnull(COUNT(codigo),0) end,
			p61_72=case when grupo='p61_72' then isnull(COUNT(codigo),0) end,
			e_0_12=case when grupo='p0_12' then isnull(sum(ultima_entrada),0) end,
			e_13_24=case when grupo='p13_24' then isnull(sum(ultima_entrada),0) end,
			e_25_36=case when grupo='p25_36' then isnull(sum(ultima_entrada),0) end,
			e_37_48=case when grupo='p37_48' then isnull(sum(ultima_entrada),0) end,
			e_49_60=case when grupo='p49_60' then isnull(sum(ultima_entrada),0) end,
			e_61_72=case when grupo='p61_72' then isnull(sum(ultima_entrada),0) end
			from postv_segmento_vh s left join
			(select tipo,segmento,codigo,tipo_vh,ultima_entrada,b.familia,
			grupo=case when Meses<=12 then 'p0_12'
			when Meses between '13' and '24' then 'p13_24'
			when Meses between '25' and '36' then 'p25_36'
			when Meses between '37' and '48' then 'p37_48'
			when Meses between '49' and '60' then 'p49_60'
			else 'p61_72' end
			from v_datos_retencion_flotas a inner join vh_familias b on a.familia=b.descripcion) f
			on s.tipo=f.tipo and s.segmento=f.segmento and s.familia=f.familia
			where tipo_vh is not null
			group by s.tipo, s.segmento,grupo,tipo_vh
			)p
			where tipo = 'B&C'
			group by tipo,tipo_vh
			order by tipo_vh, tipo";

			return $this->db->query($result);
		} else {
			$result =  "select tipo, segmento,tipo_vh,isnull(sum(p0_12),0) as p0_12,
			isnull(sum(p13_24),0) as p13_24,
			isnull(sum(p25_36),0) as p25_36,
			isnull(sum(p37_48),0) as p37_48,
			isnull(sum(p49_60),0) as p49_60,
			isnull(sum(p61_72),0) as p61_72,
			isnull(sum(e_0_12),0) as e_0_12,
			isnull(sum(e_13_24),0) as e_13_24,
			isnull(sum(e_25_36),0) as e_25_36,
			isnull(sum(e_37_48),0) as e_37_48,
			isnull(sum(e_49_60),0) as e_49_60,
			isnull(sum(e_61_72),0) as e_61_72
			from
			(
			select distinct s.tipo, s.segmento,tipo_vh,
			p0_12=case when grupo='p0_12' then isnull(COUNT(codigo),0) end,
			p13_24=case when grupo='p13_24' then isnull(COUNT(codigo),0) end,
			p25_36=case when grupo='p25_36' then isnull(COUNT(codigo),0) end,
			p37_48=case when grupo='p37_48' then isnull(COUNT(codigo),0) end,
			p49_60=case when grupo='p49_60' then isnull(COUNT(codigo),0) end,
			p61_72=case when grupo='p61_72' then isnull(COUNT(codigo),0) end,
			e_0_12=case when grupo='p0_12' then isnull(sum(ultima_entrada),0) end,
			e_13_24=case when grupo='p13_24' then isnull(sum(ultima_entrada),0) end,
			e_25_36=case when grupo='p25_36' then isnull(sum(ultima_entrada),0) end,
			e_37_48=case when grupo='p37_48' then isnull(sum(ultima_entrada),0) end,
			e_49_60=case when grupo='p49_60' then isnull(sum(ultima_entrada),0) end,
			e_61_72=case when grupo='p61_72' then isnull(sum(ultima_entrada),0) end
			from postv_segmento_vh s left join
			(select tipo,segmento,codigo,tipo_vh,ultima_entrada,b.familia,
			grupo=case when Meses<=12 then 'p0_12'
			when Meses between '13' and '24' then 'p13_24'
			when Meses between '25' and '36' then 'p25_36'
			when Meses between '37' and '48' then 'p37_48'
			when Meses between '49' and '60' then 'p49_60'
			else 'p61_72' end
			from v_datos_retencion_flotas a inner join vh_familias b on a.familia=b.descripcion) f
			on s.tipo=f.tipo and s.segmento=f.segmento and s.familia=f.familia
			where tipo_vh is not null
			group by s.tipo, s.segmento,grupo,tipo_vh
			)p
			where segmento = '$filtro'
			group by tipo, segmento,tipo_vh
			order by tipo_vh, tipo,segmento";
			return $this->db->query($result);
		}
	}
	/* Traer la familia de los segmentos */
	public function getFamilia($segmento)
	{
		$sql = "select distinct familia from v_datos_retencion_flotas where segmento = '$segmento'";
		$result = $this->db->query($sql);
		return $result;
	}
	/* Filtrar por familias-- suma total-- */
	public function filtroFamilia($familia)
	{
		$sql = "select tipo, segmento,tipo_vh,isnull(sum(p_0_12),0) as p_0_12,
		isnull(sum(p_13_24),0) as p_13_24,
		isnull(sum(p_25_36),0) as p_25_36,
		isnull(sum(p_37_48),0) as p_37_48,
		isnull(sum(p_49_60),0) as p_49_60,
		isnull(sum(p_61_72),0) as p_61_72,
		isnull(sum(e_0_12),0) as e_0_12,
		isnull(sum(e_13_24),0) as e_13_24,
		isnull(sum(e_25_36),0) as e_25_36,
		isnull(sum(e_37_48),0) as e_37_48,
		isnull(sum(e_49_60),0) as e_49_60,
		isnull(sum(e_61_72),0) as e_61_72
		from
		(
		select distinct s.tipo, s.segmento,tipo_vh,familia_vh,
		p_0_12=case when grupo='p_0_12' then isnull(COUNT(codigo),0) end,
		p_13_24=case when grupo='p_13_24' then isnull(COUNT(codigo),0) end,
		p_25_36=case when grupo='p_25_36' then isnull(COUNT(codigo),0) end,
		p_37_48=case when grupo='p_37_48' then isnull(COUNT(codigo),0) end,
		p_49_60=case when grupo='p_49_60' then isnull(COUNT(codigo),0) end,
		p_61_72=case when grupo='p_61_72' then isnull(COUNT(codigo),0) end,
		e_0_12=case when grupo='p_0_12' then isnull(sum(ultima_entrada),0) end,
		e_13_24=case when grupo='p_13_24' then isnull(sum(ultima_entrada),0) end,
		e_25_36=case when grupo='p_25_36' then isnull(sum(ultima_entrada),0) end,
		e_37_48=case when grupo='p_37_48' then isnull(sum(ultima_entrada),0) end,
		e_49_60=case when grupo='p_49_60' then isnull(sum(ultima_entrada),0) end,
		e_61_72=case when grupo='p_61_72' then isnull(sum(ultima_entrada),0) end
		from postv_segmento_vh s left join
		(select tipo,segmento,codigo,tipo_vh,ultima_entrada,b.familia,a.familia as familia_vh,
		grupo=case when Meses<=12 then 'p_0_12'
		when Meses between '13' and '24' then 'p_13_24'
		when Meses between '25' and '36' then 'p_25_36'
		when Meses between '37' and '48' then 'p_37_48'
		when Meses between '49' and '60' then 'p_49_60'
		else 'p_61_72' end
		from v_datos_retencion_flotas a inner join vh_familias b on a.familia=b.descripcion) f
		on s.tipo=f.tipo and s.segmento=f.segmento and s.familia=f.familia
		where tipo_vh is not null
		group by s.tipo, s.segmento,grupo,tipo_vh,familia_vh
		)p
		where familia_vh in ($familia)
		group by tipo, segmento,tipo_vh
		order by tipo_vh, tipo,segmento";

		$result = $this->db->query($sql);

		return $result;
	}
	public function filtroFamiliaGrafica($familia)
	{

		$sql = "select tipo, segmento,tipo_vh,isnull(sum(p_0_12),0) as p_0_12,
		isnull(sum(p_13_24),0) as p_13_24,
		isnull(sum(p_25_36),0) as p_25_36,
		isnull(sum(p_37_48),0) as p_37_48,
		isnull(sum(p_49_60),0) as p_49_60,
		isnull(sum(p_61_72),0) as p_61_72,
		isnull(sum(e_0_12),0) as e_0_12,
		isnull(sum(e_13_24),0) as e_13_24,
		isnull(sum(e_25_36),0) as e_25_36,
		isnull(sum(e_37_48),0) as e_37_48,
		isnull(sum(e_49_60),0) as e_49_60,
		isnull(sum(e_61_72),0) as e_61_72
		from
		(
		select distinct s.tipo, s.segmento,tipo_vh,familia_vh,
		p_0_12=case when grupo='p_0_12' then isnull(COUNT(codigo),0) end,
		p_13_24=case when grupo='p_13_24' then isnull(COUNT(codigo),0) end,
		p_25_36=case when grupo='p_25_36' then isnull(COUNT(codigo),0) end,
		p_37_48=case when grupo='p_37_48' then isnull(COUNT(codigo),0) end,
		p_49_60=case when grupo='p_49_60' then isnull(COUNT(codigo),0) end,
		p_61_72=case when grupo='p_61_72' then isnull(COUNT(codigo),0) end,
		e_0_12=case when grupo='p_0_12' then isnull(sum(ultima_entrada),0) end,
		e_13_24=case when grupo='p_13_24' then isnull(sum(ultima_entrada),0) end,
		e_25_36=case when grupo='p_25_36' then isnull(sum(ultima_entrada),0) end,
		e_37_48=case when grupo='p_37_48' then isnull(sum(ultima_entrada),0) end,
		e_49_60=case when grupo='p_49_60' then isnull(sum(ultima_entrada),0) end,
		e_61_72=case when grupo='p_61_72' then isnull(sum(ultima_entrada),0) end
		from postv_segmento_vh s left join
		(select tipo,segmento,codigo,tipo_vh,ultima_entrada,b.familia,a.familia as familia_vh,
		grupo=case when Meses<=12 then 'p_0_12'
		when Meses between '13' and '24' then 'p_13_24'
		when Meses between '25' and '36' then 'p_25_36'
		when Meses between '37' and '48' then 'p_37_48'
		when Meses between '49' and '60' then 'p_49_60'
		else 'p_61_72' end
		from v_datos_retencion_flotas a inner join vh_familias b on a.familia=b.descripcion) f
		on s.tipo=f.tipo and s.segmento=f.segmento and s.familia=f.familia
		where tipo_vh is not null
		group by s.tipo, s.segmento,grupo,tipo_vh,familia_vh
		)p
		where familia_vh in ($familia)
		group by tipo, segmento,tipo_vh
		order by tipo_vh, tipo,segmento";

		$result = $this->db->query($sql);
		return $result;
	}
	/* Informe general */
	public function getDataInformeGeneral()
	{
		$sql = "select * from v_detalle_Informe_flotas order by familia_vh, tipo_vh";
		$result = $this->db->query($sql);
		return $result;
	}

	public function get_nps_tec_gm($sede, $mes, $tec)
	{
		$sql = "SELECT te.tecnico,te.sede,te.nit_tecnico,te.mes,
		isnull(SUM(enc9a10),-1) AS enc9a10,isnull(SUM(enc7a8),-1) AS enc7a8,isnull(SUM(enc0a6),-1) AS enc0a6
		from (		
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -5,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		union
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -4,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		union
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -3,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		union
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -2,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		union
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -1,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		union
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -0,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		)te

		left join 

		(SELECT nit_tecnico,sede,MONTH(fecha_recibido_enc) AS mes, 
		COUNT(CASE WHEN recomendacion_concesionario BETWEEN 0 and 6 THEN 'enc0a6' end) as enc0a6,
		COUNT(CASE WHEN recomendacion_concesionario BETWEEN 7 and 8 THEN 'enc7a8' end) as enc7a8,
		COUNT(CASE WHEN recomendacion_concesionario BETWEEN 9 and 10 THEN 'enc9a10' end) as enc9a10
		From postv_encuestas_gm peg
		WHERE fecha_recibido_enc>= '20220101'
		AND sede IN('" . $sede . "')
		GROUP BY sede,fecha_recibido_enc,nit_tecnico)e
		on te.nit_tecnico=e.nit_tecnico and te.sede=e.sede and te.mes=e.mes
		where te.sede in ('" . $sede . "') and te.mes = " . $mes . " and te.nit_tecnico = " . $tec . "
		group by te.tecnico,te.sede,te.nit_tecnico,te.mes
		order by tecnico,sede,te.mes";
		$result = $this->db->query($sql);
		return $result;
	}

	public function get_nps_tec_gm_by_nit($sede, $mes, $tec)
	{
		$sql = "SELECT te.tecnico,te.sede,te.nit_tecnico,te.mes,
		isnull(SUM(enc9a10),-1) AS enc9a10,isnull(SUM(enc7a8),-1) AS enc7a8,isnull(SUM(enc0a6),-1) AS enc0a6
		from (		
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -5,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		union
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -4,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		union
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -3,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		union
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -2,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		union
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -1,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		union
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -0,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		)te

		left join 

		(SELECT nit_tecnico,sede,MONTH(fecha_recibido_enc) AS mes, 
		COUNT(CASE WHEN recomendacion_concesionario BETWEEN 0 and 6 THEN 'enc0a6' end) as enc0a6,
		COUNT(CASE WHEN recomendacion_concesionario BETWEEN 7 and 8 THEN 'enc7a8' end) as enc7a8,
		COUNT(CASE WHEN recomendacion_concesionario BETWEEN 9 and 10 THEN 'enc9a10' end) as enc9a10
		From postv_encuestas_gm peg
		WHERE fecha_recibido_enc>= '20220101'
		AND sede IN(" . $sede . ")
		GROUP BY sede,fecha_recibido_enc,nit_tecnico)e
		on te.nit_tecnico=e.nit_tecnico and te.sede=e.sede and te.mes=e.mes
		where te.sede in (" . $sede . ") and te.mes = " . $mes . " and te.nit_tecnico = " . $tec . "
		group by te.tecnico,te.sede,te.nit_tecnico,te.mes
		order by tecnico,sede,te.mes";
		//echo $sql;die;
		$result = $this->db->query($sql);
		return $result;
	}

	public function get_nps_tec_gm_all($sede, $mes)
	{
		$sql = "SELECT te.tecnico,te.sede,te.nit_tecnico,te.mes,
		isnull(SUM(enc9a10),0) AS enc9a10,isnull(SUM(enc7a8),0) AS enc7a8,isnull(SUM(enc0a6),0) AS enc0a6
		from (		
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -5,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		union
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -4,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		union
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -3,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		union
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -2,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		union
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -1,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		union
		select distinct nit_tecnico,t.nombres as tecnico,sede,MONTH(DATEADD(MONTH, -0,GETDATE())) as mes 
		from postv_encuestas_gm e inner join terceros t
		on e.nit_tecnico=t.nit 
		)te

		left join 

		(SELECT nit_tecnico,sede,MONTH(fecha_recibido_enc) AS mes, 
		COUNT(CASE WHEN recomendacion_concesionario BETWEEN 0 and 6 THEN 'enc0a6' end) as enc0a6,
		COUNT(CASE WHEN recomendacion_concesionario BETWEEN 7 and 8 THEN 'enc7a8' end) as enc7a8,
		COUNT(CASE WHEN recomendacion_concesionario BETWEEN 9 and 10 THEN 'enc9a10' end) as enc9a10
		From postv_encuestas_gm peg
		WHERE fecha_recibido_enc>= '20220101'
		AND sede IN('" . $sede . "')
		GROUP BY sede,fecha_recibido_enc,nit_tecnico)e
		on te.nit_tecnico=e.nit_tecnico and te.sede=e.sede and te.mes=e.mes
		where te.sede in ('" . $sede . "') and te.mes = " . $mes . "
		group by te.tecnico,te.sede,te.nit_tecnico,te.mes
		order by tecnico,sede,te.mes";
		$result = $this->db->query($sql);
		return $result;
	}

	public function get_tec_gm($sede = '')
	{
		$sql = "SELECT DISTINCT nit_tecnico,sede,t.nombres
		From postv_encuestas_gm peg
		INNER JOIN terceros t ON peg.nit_tecnico = t.nit
		WHERE sede IN('" . $sede . "')
		GROUP BY sede,fecha_recibido_enc,nit_tecnico,t.nombres";
		$result = $this->db->query($sql);
		return $result;
	}

	public function get_nps_gm_gra($sede, $mes)
	{
		$sql = "SELECT TOP 1 *, MONTH(Fecha) as mes FROM NPS_sedes 
		WHERE sede = '" . $sede . "' AND MONTH(CONVERT(DATE,Fecha)) = MONTH(DATEADD(MONTH, -" . $mes . ",GETDATE()))
		ORDER BY Fecha desc";
		return $this->db->query($sql);
	}

	public function get_nps_gm_gra_tabla($sede, $mes)
	{
		$sql = "SELECT TOP 1 *, MONTH(Fecha) as mes FROM NPS_sedes 
		WHERE sede = '" . $sede . "' AND MONTH(CONVERT(DATE,Fecha)) = " . $mes . "
		ORDER BY Fecha desc";
		return $this->db->query($sql);
	}

	public function get_to_enc_gm($sede, $mes)
	{
		$sql = "SELECT e.sede,SUM(e.enc0a6)as enc0a6,SUM(e.enc7a8)AS enc7a8,SUM(enc9a10) AS enc9a10 FROM
		(SELECT nom_tecnico as nombres,sede,MONTH(fecha_recibido_enc) AS mes, COUNT(CASE
		WHEN recomendacion_concesionario BETWEEN 0 and 6 THEN 'enc0a6' end) as enc0a6,
		COUNT(CASE
		WHEN recomendacion_concesionario BETWEEN 7 and 8 THEN 'enc7a8' end) as enc7a8,
		COUNT(CASE
		WHEN recomendacion_concesionario BETWEEN 9 and 10 THEN 'enc9a10' end) as enc9a10
		From postv_encuestas_gm
		WHERE MONTH(CONVERT(DATE,fecha_recibido_enc)) = MONTH(DATEADD(MONTH, -" . $mes . ",GETDATE()))
		AND sede IN(" . $sede . ")
		GROUP BY nom_tecnico,sede,fecha_recibido_enc)e
		GROUP BY e.sede";
		return $this->db->query($sql);
	}

	public function get_name_mes($fecha)
	{
		$sql = "select convert(varchar(10),datename(month, CONVERT(DATE,'" . $fecha . "'))) as nom_mes";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	/* Informe de comparación de graficas o Versus General */
	public function grafGeneralVs()
	{
		$sql = "select tipo,tipo_vh,isnull(sum(p0_12),0) as p0_12,
		isnull(sum(p13_24),0) as p13_24,
		isnull(sum(p25_36),0) as p25_36,
		isnull(sum(p37_48),0) as p37_48,
		isnull(sum(p49_60),0) as p49_60,
		isnull(sum(p61_72),0) as p61_72,
		isnull(sum(e_0_12),0) as e_0_12,
		isnull(sum(e_13_24),0) as e_13_24,
		isnull(sum(e_25_36),0) as e_25_36,
		isnull(sum(e_37_48),0) as e_37_48,
		isnull(sum(e_49_60),0) as e_49_60,
		isnull(sum(e_61_72),0) as e_61_72
		from
		(
		select distinct s.tipo, s.segmento,tipo_vh,
		p0_12=case when grupo='p0_12' then isnull(COUNT(codigo),0) end,
		p13_24=case when grupo='p13_24' then isnull(COUNT(codigo),0) end,
		p25_36=case when grupo='p25_36' then isnull(COUNT(codigo),0) end,
		p37_48=case when grupo='p37_48' then isnull(COUNT(codigo),0) end,
		p49_60=case when grupo='p49_60' then isnull(COUNT(codigo),0) end,
		p61_72=case when grupo='p61_72' then isnull(COUNT(codigo),0) end,
		e_0_12=case when grupo='p0_12' then isnull(sum(ultima_entrada),0) end,
		e_13_24=case when grupo='p13_24' then isnull(sum(ultima_entrada),0) end,
		e_25_36=case when grupo='p25_36' then isnull(sum(ultima_entrada),0) end,
		e_37_48=case when grupo='p37_48' then isnull(sum(ultima_entrada),0) end,
		e_49_60=case when grupo='p49_60' then isnull(sum(ultima_entrada),0) end,
		e_61_72=case when grupo='p61_72' then isnull(sum(ultima_entrada),0) end
		from postv_segmento_vh s left join
		(select tipo,segmento,codigo,tipo_vh,ultima_entrada,b.familia,
		grupo=case when Meses<=12 then 'p0_12'
		when Meses between '13' and '24' then 'p13_24'
		when Meses between '25' and '36' then 'p25_36'
		when Meses between '37' and '48' then 'p37_48'
		when Meses between '49' and '60' then 'p49_60'
		else 'p61_72' end
		from v_datos_retencion_flotas a inner join vh_familias b on a.familia=b.descripcion) f
		on s.tipo=f.tipo and s.segmento=f.segmento and s.familia=f.familia
		where tipo_vh is not null
		group by s.tipo, s.segmento,grupo,tipo_vh
		)p
		
		group by tipo,tipo_vh
		order by tipo";
		$result = $this->db->query($sql);

		if ($result != "") {
			return $result;
		} else {
			return false;
		}
	}
	/* Informe de comparación de Autos y B&C Graficas */
	public function GrafAutosyByCVs($filtro)
	{
		if ($filtro == "Autos" || $filtro == "B&C") {
			$sql = "select tipo, segmento,tipo_vh,isnull(sum(p_0_12),0) as p_0_12,
			isnull(sum(p_13_24),0) as p_13_24,
			isnull(sum(p_25_36),0) as p_25_36,
			isnull(sum(p_37_48),0) as p_37_48,
			isnull(sum(p_49_60),0) as p_49_60,
			isnull(sum(p_61_72),0) as p_61_72,
			isnull(sum(e_0_12),0) as e_0_12,
			isnull(sum(e_13_24),0) as e_13_24,
			isnull(sum(e_25_36),0) as e_25_36,
			isnull(sum(e_37_48),0) as e_37_48,
			isnull(sum(e_49_60),0) as e_49_60,
			isnull(sum(e_61_72),0) as e_61_72
			from
			(
			select distinct s.tipo, s.segmento,tipo_vh,familia_vh,
			p_0_12=case when grupo='p_0_12' then isnull(COUNT(codigo),0) end,
			p_13_24=case when grupo='p_13_24' then isnull(COUNT(codigo),0) end,
			p_25_36=case when grupo='p_25_36' then isnull(COUNT(codigo),0) end,
			p_37_48=case when grupo='p_37_48' then isnull(COUNT(codigo),0) end,
			p_49_60=case when grupo='p_49_60' then isnull(COUNT(codigo),0) end,
			p_61_72=case when grupo='p_61_72' then isnull(COUNT(codigo),0) end,
			e_0_12=case when grupo='p_0_12' then isnull(sum(ultima_entrada),0) end,
			e_13_24=case when grupo='p_13_24' then isnull(sum(ultima_entrada),0) end,
			e_25_36=case when grupo='p_25_36' then isnull(sum(ultima_entrada),0) end,
			e_37_48=case when grupo='p_37_48' then isnull(sum(ultima_entrada),0) end,
			e_49_60=case when grupo='p_49_60' then isnull(sum(ultima_entrada),0) end,
			e_61_72=case when grupo='p_61_72' then isnull(sum(ultima_entrada),0) end
			from postv_segmento_vh s left join
			(select tipo,segmento,codigo,tipo_vh,ultima_entrada,b.familia,a.familia as familia_vh,
			grupo=case when Meses<=12 then 'p_0_12'
			when Meses between '13' and '24' then 'p_13_24'
			when Meses between '25' and '36' then 'p_25_36'
			when Meses between '37' and '48' then 'p_37_48'
			when Meses between '49' and '60' then 'p_49_60'
			else 'p_61_72' end
			from v_datos_retencion_flotas a inner join vh_familias b on a.familia=b.descripcion) f
			on s.tipo=f.tipo and s.segmento=f.segmento and s.familia=f.familia
			where tipo_vh is not null
			group by s.tipo, s.segmento,grupo,tipo_vh,familia_vh
			)p
			where tipo = '$filtro'
			group by tipo, segmento,tipo_vh
			order by segmento,tipo_vh, tipo";
			$result = $this->db->query($sql);

			if ($result != "") {
				return $result;
			} else {
				return false;
			}
		} else {
			$sql = "select tipo, segmento,familia_vh,tipo_vh,isnull(sum(p_0_12),0) as p_0_12,
			isnull(sum(p_13_24),0) as p_13_24,
			isnull(sum(p_25_36),0) as p_25_36,
			isnull(sum(p_37_48),0) as p_37_48,
			isnull(sum(p_49_60),0) as p_49_60,
			isnull(sum(p_61_72),0) as p_61_72,
			isnull(sum(e_0_12),0) as e_0_12,
			isnull(sum(e_13_24),0) as e_13_24,
			isnull(sum(e_25_36),0) as e_25_36,
			isnull(sum(e_37_48),0) as e_37_48,
			isnull(sum(e_49_60),0) as e_49_60,
			isnull(sum(e_61_72),0) as e_61_72
			from
			(
			select distinct s.tipo, s.segmento,tipo_vh,familia_vh,
			p_0_12=case when grupo='p_0_12' then isnull(COUNT(codigo),0) end,
			p_13_24=case when grupo='p_13_24' then isnull(COUNT(codigo),0) end,
			p_25_36=case when grupo='p_25_36' then isnull(COUNT(codigo),0) end,
			p_37_48=case when grupo='p_37_48' then isnull(COUNT(codigo),0) end,
			p_49_60=case when grupo='p_49_60' then isnull(COUNT(codigo),0) end,
			p_61_72=case when grupo='p_61_72' then isnull(COUNT(codigo),0) end,
			e_0_12=case when grupo='p_0_12' then isnull(sum(ultima_entrada),0) end,
			e_13_24=case when grupo='p_13_24' then isnull(sum(ultima_entrada),0) end,
			e_25_36=case when grupo='p_25_36' then isnull(sum(ultima_entrada),0) end,
			e_37_48=case when grupo='p_37_48' then isnull(sum(ultima_entrada),0) end,
			e_49_60=case when grupo='p_49_60' then isnull(sum(ultima_entrada),0) end,
			e_61_72=case when grupo='p_61_72' then isnull(sum(ultima_entrada),0) end
			from postv_segmento_vh s left join
			(select tipo,segmento,codigo,tipo_vh,ultima_entrada,b.familia,a.familia as familia_vh,
			grupo=case when Meses<=12 then 'p_0_12'
			when Meses between '13' and '24' then 'p_13_24'
			when Meses between '25' and '36' then 'p_25_36'
			when Meses between '37' and '48' then 'p_37_48'
			when Meses between '49' and '60' then 'p_49_60'
			else 'p_61_72' end
			from v_datos_retencion_flotas a inner join vh_familias b on a.familia=b.descripcion) f
			on s.tipo=f.tipo and s.segmento=f.segmento and s.familia=f.familia
			where tipo_vh is not null
			group by s.tipo, s.segmento,grupo,tipo_vh,familia_vh
			)p
			where segmento = '$filtro'
			group by tipo, segmento,familia_vh,tipo_vh
			order by tipo,familia_vh,segmento,tipo_vh";
			$result = $this->db->query($sql);

			if ($result != "") {
				return $result;
			} else {
				return false;
			}
		}
	}
	/* Buscar segmento general */
	public function infGrafGeneralSegmento($filtro)
	{
		$sql = "select segmento,tipo_vh,isnull(sum(p0_12),0) as p0_12,
		isnull(sum(p13_24),0) as p13_24,
		isnull(sum(p25_36),0) as p25_36,
		isnull(sum(p37_48),0) as p37_48,
		isnull(sum(p49_60),0) as p49_60,
		isnull(sum(p61_72),0) as p61_72,
		isnull(sum(e_0_12),0) as e_0_12,
		isnull(sum(e_13_24),0) as e_13_24,
		isnull(sum(e_25_36),0) as e_25_36,
		isnull(sum(e_37_48),0) as e_37_48,
		isnull(sum(e_49_60),0) as e_49_60,
		isnull(sum(e_61_72),0) as e_61_72
		from
		(
		select distinct s.tipo, s.segmento,tipo_vh,
		p0_12=case when grupo='p0_12' then isnull(COUNT(codigo),0) end,
		p13_24=case when grupo='p13_24' then isnull(COUNT(codigo),0) end,
		p25_36=case when grupo='p25_36' then isnull(COUNT(codigo),0) end,
		p37_48=case when grupo='p37_48' then isnull(COUNT(codigo),0) end,
		p49_60=case when grupo='p49_60' then isnull(COUNT(codigo),0) end,
		p61_72=case when grupo='p61_72' then isnull(COUNT(codigo),0) end,
		e_0_12=case when grupo='p0_12' then isnull(sum(ultima_entrada),0) end,
		e_13_24=case when grupo='p13_24' then isnull(sum(ultima_entrada),0) end,
		e_25_36=case when grupo='p25_36' then isnull(sum(ultima_entrada),0) end,
		e_37_48=case when grupo='p37_48' then isnull(sum(ultima_entrada),0) end,
		e_49_60=case when grupo='p49_60' then isnull(sum(ultima_entrada),0) end,
		e_61_72=case when grupo='p61_72' then isnull(sum(ultima_entrada),0) end
		from postv_segmento_vh s left join
		(select tipo,segmento,codigo,tipo_vh,ultima_entrada,b.familia,
		grupo=case when Meses<=12 then 'p0_12'
		when Meses between '13' and '24' then 'p13_24'
		when Meses between '25' and '36' then 'p25_36'
		when Meses between '37' and '48' then 'p37_48'
		when Meses between '49' and '60' then 'p49_60'
		else 'p61_72' end
		from v_datos_retencion_flotas a inner join vh_familias b on a.familia=b.descripcion) f
		on s.tipo=f.tipo and s.segmento=f.segmento and s.familia=f.familia
		where tipo_vh is not null
		group by s.tipo, s.segmento,grupo,tipo_vh
		)p
		where segmento = '$filtro'
		group by segmento,tipo_vh
		order by segmento";
		$result = $this->db->query($sql);

		if ($result != "") {
			return $result;
		} else {
			return false;
		}
	}
	/* Consulta para obtener la inf de la tabla general */
	public function filtroTabla($filtro)
	{

		$sql = "select * from v_detalle_Informe_flotas
		where familia_vh in ($filtro)
		order by familia_vh, tipo_vh";

		$result = $this->db->query($sql);


		if ($result != "") {
			return $result;
		} else {
			return false;
		}
	}
	public function filtroTablaSegmentouFamilia($filtro)
	{
		if ($filtro == "B&C") {
			$sql = "select * from v_detalle_Informe_flotas
			where tipo = '$filtro'
			order by familia_vh, tipo_vh";

			$result = $this->db->query($sql);
		} elseif ($filtro == "Autos") {
			$sql = "select * from v_detalle_Informe_flotas
			where tipo = '$filtro'
			order by familia_vh, tipo_vh";

			$result = $this->db->query($sql);
		} else {
			$sql = "select * from v_detalle_Informe_flotas
			where segmento = '$filtro'
			order by familia_vh, tipo_vh";

			$result = $this->db->query($sql);
		}

		if ($result != "") {
			return $result;
		} else {
			return false;
		}
	}

	public function infVentas1a1($fecha, $asesor)
	{
		$sql = "select YEAR(a.fec) as año, dl.vendedor as nit_asesor, t.nombres as asesor,
		Venta_mano_obra=sum((case when a.sw in(1,2) and tipo_sal is null and clase_operacion<>'R' and clase_operacion='T'
		then Convert(money,((a.cantidad*a.valor_unidad*a.tiempo*a.porcen_apl/100)-(a.cantidad*a.valor_unidad*a.tiempo*a.porcen_dscto/100*a.porcen_apl/100)))
		*case when a.sw=1 then 1 else -1 end else 0 end)+(case when a.sw in(1,2) and tipo_sal is null and clase_operacion<>'R' and clase_operacion='O'
		then Convert(money,((a.valor_unidad*a.porcen_apl/100)-(a.valor_unidad*a.porcen_dscto/100*a.porcen_apl/100)))
		*case when a.sw=1 then 1 else -1 end else 0 end)),
		venta_rptos=sum(case when a.sw in(1,2) and clase_operacion='R'
		then Convert(money,((a.valor_unidad*a.cantidad*a.porcen_apl/100)-(a.valor_unidad*a.cantidad*a.porcen_dscto/100*a.porcen_apl/100)))
		*case when a.sw=1 then 1 else -1 end else 0 end),
		costo_rptos=sum(case when a.sw in(1,2) and clase_operacion='R'
		then Convert(money,((a.costo_promedio*a.cantidad*a.porcen_apl/100)))
		*case when a.sw=1 then 1 else -1 end else 0 end)
		from tall_documentos_lin a inner join v_vh_unoauno u
		on a.serie=u.codigo inner join
		(select codigo,Vendedor from documentos_lin where sw=1 and cantidad_devuelta is null
		and tipo in ('DVA','DVC','DVG','DVR','DVX','DWV','KDV','KV','VA','VC','VG','VR','VX','WV'))  dl
		on a.serie=dl.codigo
		inner join tall_encabeza_orden e on a.numero_orden=e.numero and a.serie=e.serie
		inner join terceros t on dl.vendedor=t.nit
		where  YEAR(a.fec)='$fecha'   and e.razon2 in (4,5) $asesor
		group by YEAR(a.fec), dl.vendedor, t.nombres
		order by  dl.vendedor,t.nombres,YEAR(a.fec)
		";
		$result = $this->db->query($sql);

		if ($result != "") {
			return $result;
		} else {
			return false;
		}
	}


	//Metodos para el Informe horario

	public function get_Informe_horario($fecha_ini, $fecha_fin, $sede = "", $empleado = "")
	{
		if ($empleado == "" && $sede == "") {
			$sql = "select h.*,au.inicio_ausentismo,au.fin_ausentismo, la.llegada_am,sa.salida_am,lp.llegada_pm,sp.salida_pm,
			dif_entrada_am=DATEDIFF(minute,h.horario_entrada_am,la.llegada_am),
			dif_salida_am= case when dia='Sábado' and salida_am is null then DATEDIFF(minute,h.horario_salida_am,sp.salida_pm)
			else DATEDIFF(minute,h.horario_salida_am,sa.salida_am) end,
			dif_entrada_pm=case when dia='Sábado' then '' else DATEDIFF(minute,h.horario_entrada_pm,lp.llegada_pm) end,
			dif_salida_pm=case when dia='Sábado' then '' else DATEDIFF(minute,h.horario_salida_pm,sp.salida_pm) end

			from (
			select distinct i.empleado,t.nombres,i.Sede,Dia,i.fecha,
			horario_entrada_am=case when dia='Sábado' then hora_ent_fds else hora_ent_sem_am end,
			horario_salida_am=case when dia='Sábado' then hora_sal_fds else hora_sal_sem_am  end,
			horario_entrada_pm=case when dia='Sábado' then '' else hora_ent_sem_pm end,
			horario_salida_pm=case when dia='Sábado' then '' else hora_sal_sem_pm end
			from postv_horarios_empleados e left join v_registro_ingreso i
			on e.nit_empleado=i.empleado
			left join terceros t on e.nit_empleado=t.nit
			where CONVERT(DATE,i.fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "')
			)h
			left join
			(select empleado,fecha, hora as llegada_am from v_registro_ingreso where CONVERT(DATE,fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "') and accion='Ingreso' and jornada='am')la
			on h.empleado=la.empleado and h.fecha=la.fecha
			left join
			(select empleado,fecha, hora as salida_am from v_registro_ingreso where CONVERT(DATE,fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "') and accion='salida' and jornada='am')sa
			on h.empleado=sa.empleado and h.fecha=sa.fecha
			left join
			(select empleado,fecha, hora as llegada_pm from v_registro_ingreso where CONVERT(DATE,fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "') and accion='Ingreso' and jornada='pm')lp
			on h.empleado=lp.empleado and h.fecha=lp.fecha
			left join
			(select empleado,fecha, hora as salida_pm from v_registro_ingreso where CONVERT(DATE,fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "') and accion='salida' and jornada='pm')sp
			on h.empleado=sp.empleado and h.fecha=sp.fecha
			left join
			(select empleado,fecha_ini as fecha, hora_ini as inicio_ausentismo, hora_fin as fin_ausentismo from postv_ausentismos where CONVERT(DATE,fecha_ini) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "') )au
			on h.empleado=au.empleado and h.fecha=au.fecha
			ORDER BY nombres, h.fecha";
		} elseif ($empleado == "" && $sede != "") {
			$sql = "select h.*,au.inicio_ausentismo,au.fin_ausentismo, la.llegada_am,sa.salida_am,lp.llegada_pm,sp.salida_pm,
			dif_entrada_am=DATEDIFF(minute,h.horario_entrada_am,la.llegada_am),
			dif_salida_am= case when dia='Sábado' and salida_am is null then DATEDIFF(minute,h.horario_salida_am,sp.salida_pm)
			else DATEDIFF(minute,h.horario_salida_am,sa.salida_am) end,
			dif_entrada_pm=case when dia='Sábado' then '' else DATEDIFF(minute,h.horario_entrada_pm,lp.llegada_pm) end,
			dif_salida_pm=case when dia='Sábado' then '' else DATEDIFF(minute,h.horario_salida_pm,sp.salida_pm) end

			from (
			select distinct i.empleado,t.nombres,i.Sede,Dia,i.fecha,
			horario_entrada_am=case when dia='Sábado' then hora_ent_fds else hora_ent_sem_am end,
			horario_salida_am=case when dia='Sábado' then hora_sal_fds else hora_sal_sem_am  end,
			horario_entrada_pm=case when dia='Sábado' then '' else hora_ent_sem_pm end,
			horario_salida_pm=case when dia='Sábado' then '' else hora_sal_sem_pm end
			from postv_horarios_empleados e left join v_registro_ingreso i
			on e.nit_empleado=i.empleado
			left join terceros t on e.nit_empleado=t.nit
			where CONVERT(DATE,i.fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "')
			)h
			left join
			(select empleado ,fecha, hora as llegada_am from v_registro_ingreso where CONVERT(DATE,fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "') and accion='Ingreso' and jornada='am')la
			on h.empleado=la.empleado and h.fecha=la.fecha
			left join 
			(select empleado ,fecha, hora as salida_am from v_registro_ingreso where CONVERT(DATE,fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "') and accion='salida' and jornada='am')sa
			on h.empleado=sa.empleado and h.fecha=sa.fecha
			left join
			(select empleado ,fecha, hora as llegada_pm from v_registro_ingreso where CONVERT(DATE,fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "') and accion='Ingreso' and jornada='pm')lp
			on h.empleado=lp.empleado and h.fecha=lp.fecha
			left join
			(select empleado ,fecha, hora as salida_pm from v_registro_ingreso where CONVERT(DATE,fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "') and accion='salida' and jornada='pm')sp
			on h.empleado=sp.empleado and h.fecha=sp.fecha
			left join
			(select empleado ,fecha_ini as fecha, hora_ini as inicio_ausentismo, hora_fin as fin_ausentismo from postv_ausentismos where CONVERT(DATE,fecha_ini) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "') )au
			on h.empleado=au.empleado and h.fecha=au.fecha
			WHERE h.sede = '" . $sede . "'
			ORDER BY nombres, h.fecha";
		} elseif ($empleado != "" && $sede != "") {
			$sql = "select h.*,au.inicio_ausentismo,au.fin_ausentismo, la.llegada_am,sa.salida_am,lp.llegada_pm,sp.salida_pm,
			dif_entrada_am=DATEDIFF(minute,h.horario_entrada_am,la.llegada_am),
			dif_salida_am= case when dia='Sábado' and salida_am is null then DATEDIFF(minute,h.horario_salida_am,sp.salida_pm)
			else DATEDIFF(minute,h.horario_salida_am,sa.salida_am) end,
			dif_entrada_pm=case when dia='Sábado' then '' else DATEDIFF(minute,h.horario_entrada_pm,lp.llegada_pm) end,
			dif_salida_pm=case when dia='Sábado' then '' else DATEDIFF(minute,h.horario_salida_pm,sp.salida_pm) end

			from (
			select distinct i.empleado,t.nombres,i.Sede,Dia,i.fecha,
			horario_entrada_am=case when dia='Sábado' then hora_ent_fds else hora_ent_sem_am end,
			horario_salida_am=case when dia='Sábado' then hora_sal_fds else hora_sal_sem_am  end,
			horario_entrada_pm=case when dia='Sábado' then '' else hora_ent_sem_pm end,
			horario_salida_pm=case when dia='Sábado' then '' else hora_sal_sem_pm end
			from postv_horarios_empleados e left join v_registro_ingreso i
			on e.nit_empleado=i.empleado
			left join terceros t on e.nit_empleado=t.nit
			where CONVERT(DATE,i.fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "')
			)h
			left join
			(select empleado ,fecha, hora as llegada_am from v_registro_ingreso where CONVERT(DATE,fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "') and accion='Ingreso' and jornada='am')la
			on h.empleado=la.empleado and h.fecha=la.fecha
			left join
			(select empleado ,fecha, hora as salida_am from v_registro_ingreso where CONVERT(DATE,fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "') and accion='salida' and jornada='am')sa
			on h.empleado=sa.empleado and h.fecha=sa.fecha
			left join
			(select empleado ,fecha, hora as llegada_pm from v_registro_ingreso where CONVERT(DATE,fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "') and accion='Ingreso' and jornada='pm')lp
			on h.empleado=lp.empleado and h.fecha=lp.fecha
			left join
			(select empleado ,fecha, hora as salida_pm from v_registro_ingreso where CONVERT(DATE,fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "') and accion='salida' and jornada='pm')sp
			on h.empleado=sp.empleado and h.fecha=sp.fecha
			left join
			(select empleado ,fecha_ini as fecha, hora_ini as inicio_ausentismo, hora_fin as fin_ausentismo from postv_ausentismos where CONVERT(DATE,fecha_ini) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "') )au
			on h.empleado=au.empleado and h.fecha=au.fecha
			WHERE h.empleado = " . $empleado . "
			ORDER BY nombres, h.fecha";
		}

		return $this->db->query($sql);
	}

	public function infVentas1a1Porcentaje($fecha, $asesor)
	{
		$sql = "SELECT dl.vendedor,t.nombres, COUNT(distinct dl.codigo) as ventas, COUNT(distinct e.serie) as entradas
		from documentos_lin dl
		inner join terceros t on dl.vendedor=t.nit
		left join
		(select distinct serie from tall_encabeza_orden where razon2 in (4,5)) e on dl.codigo=e.serie
		where dl.sw=1 and cantidad_devuelta is null and dl.tipo in ('DVA','DVC','DVG','DVR','DVX','DWV','KDV','KV','VA','VC','VG','VR','VX','WV')
		and YEAR(dl.fec)<='$fecha' $asesor
		group by  dl.vendedor,t.nombres";
		$result = $this->db->query($sql);

		if ($result != "") {
			return $result->row();
		} else {
			return false;
		}
	}
	public function getAsesoresVenta1a1()
	{
		$sql = "
		select dl.vendedor as nit_asesor, t.nombres as asesor
		from tall_documentos_lin a inner join v_vh_unoauno u
		on a.serie=u.codigo inner join
		(select codigo,Vendedor from documentos_lin where sw=1 and cantidad_devuelta is null
		and tipo in ('DVA','DVC','DVG','DVR','DVX','DWV','KDV','KV','VA','VC','VG','VR','VX','WV'))  dl
		on a.serie=dl.codigo
		inner join terceros t on dl.vendedor=t.nit
		
		group by  dl.vendedor, t.nombres
		order by  dl.vendedor,t.nombres
		";
		$result = $this->db->query($sql);

		if ($result != "") {
			return $result;
		} else {
			return false;
		}
	}

	public function get_data_nps_interno_sedes_filtro_mes($bod, $fecha)
	{

		$sql = "SELECT  te.nit, t.nombres as tecnico,pes.bod,pes.fecha,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 0 AND 6 THEN 'enc0a6' END) AS enc0a6,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 7 AND 8 THEN 'enc7A8' END) AS enc7a8,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 9 AND 10 THEN 'enc9A10' END) AS enc9a10
		FROM postv_encuesta_satisfaccion_qr pes inner join referencias_imp r
		on pes.placa=r.placa
		inner join v_ultima_entrada_taller_datos uet on r.codigo=uet.uetd_serie
		inner join tall_encabeza_orden te on uet.uetd_numero=te.numero
		inner join terceros t on te.vendedor=t.nit
		where MONTH(CONVERT(DATE,pes.fecha)) = MONTH(CONVERT(DATE,'$fecha'))
		and YEAR(CONVERT(DATE,pes.fecha)) = YEAR(CONVERT(DATE,'$fecha')) and pes.bod in ($bod)
		group by te.nit, t.nombres,pes.bod,pes.fecha
		order by pes.fecha desc
		";

		return $this->db->query($sql);
	}

	/* Obtener informacion de la entrada de vehiculos de los ultimos 12 meses */
	public function getVehiculos12Meses()
	{
		$sql = "SELECT placa, serie,codigo,familia,tipo_vh,
				cumple_retencion=case when ultima_entrada= 0 then 'NO' else 'SI' end
				from v_datos_retencion_flotas
				order by familia";
		$result = $this->db->query($sql);

		if ($result != "") {
			return $result;
		} else {
			return false;
		}
	}
	/* Obtener informacion de la entrada de vehiculos del año actual */
	public function getVehiculosYearActual()
	{
		$sql = "SELECT placa, serie,codigo,familia,tipo_vh,
				cumple_retencion=case when ultima_entrada= 0 then 'NO' else 'SI' end
				from v_datos_rete_ano_flotas
				order by familia";
		$result = $this->db->query($sql);

		if ($result != "") {
			return $result;
		} else {
			return false;
		}
	}
	/* Obtener todas las familias de todos los segmentos */
	public function getAllFamily()
	{
		$sql = "SELECT distinct familia from v_datos_retencion_flotas
				order by familia asc";
		$result = $this->db->query($sql);

		if ($result != "") {
			return $result;
		} else {
			return false;
		}
	}
	/* Sergio Galvis
	05/08/2022
	Informe Pac Detallado
	*/
	public function getAllBodegas(){
		$sql = "SELECT bodega, descripcion from bodegas
				where bodega in (1,11,9,21,7,6,19,8,14,16)";
		$result = $this->db->query($sql);
		// bodega 22-->LAMINA Y PINTURA CAMIONES-BOCONO Se oculto
		if ($result != "") {
			return $result;
		} else {
			return false;
		}

	}
	public function OrdenFinalizadasMes($año,$mes){
		$sql = "SELECT b.bodega,b.descripcion,isnull(dl.ord_fin,0) as ord_fin ,ISNULL(qr.encuestas,0) as total_encuestas
		from bodegas b 
		left join (select COUNT(DISTINCT numero_orden) as ord_fin, bodega 
				   FROM tall_documentos_lin dl 
				   WHERE MONTH(fec) = $mes
				   and YEAR(fec) = $año
				   and bodega in (1,11,9,21,7,6,19,8,14,16)
				   GROUP BY MONTH(fec),YEAR(fec), bodega) dl on b.bodega=dl.bodega
		left join (select bod,count(id) as encuestas from postv_encuesta_satisfaccion_qr where MONTH(convert(date,fecha)) = $mes and YEAR(convert(date,fecha)) = $año
		group by bod) qr 
		on b.bodega=qr.bod
		where b.bodega in (1,11,9,21,8,14,16,22,7,6,19)		
		order by b.bodega";
		// bodega 22-->LAMINA Y PINTURA CAMIONES-BOCONO Se oculto
		$result = $this->db->query($sql);
		
		if (!empty($result->result())) {
			return $result;
		} else {
			return false;
		}
	}

	public function numsEncuestas($año,$mes){
		$sql ="SELECT  
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 0 AND 6 THEN 'enc0a6' END) +
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 7 AND 8 THEN 'enc7A8' END) + 
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 9 AND 10 THEN 'enc9A10' END) AS num_encuestas
		FROM postv_encuesta_satisfaccion_qr pes inner join referencias_imp r
		on pes.placa=r.placa
		--inner join v_ultima_entrada_taller_datos uet on r.codigo=uet.uetd_serie
		--inner join tall_encabeza_orden te on uet.uetd_numero=te.numero
		
		where MONTH(CONVERT(DATE,pes.fecha)) = $mes
		and YEAR(CONVERT(DATE,pes.fecha)) = $año 
		and pes.bod in (1,11,9,21,8,14,16,22,7,6,19)";

		$result = $this->db->query($sql);

		if (!empty($result->result())) {
			return $result->row();
		} else {
			return false;
		}

	}
	/*FUNCION PARA EL CALCULO DEL NPS INTERNO POR SEDE*/

	public function get_data_nps_interno_sedesFecha($bod,$año,$mes)
	{
		$sql = "SELECT  t.nombres as tecnico,pes.bod,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 0 AND 6 THEN 'enc0a6' END) AS enc0a6,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 7 AND 8 THEN 'enc7A8' END) AS enc7a8,
		COUNT(CASE WHEN pes.pregunta1 BETWEEN 9 AND 10 THEN 'enc9A10' END) AS enc9a10
		FROM postv_encuesta_satisfaccion_qr pes 
		LEFT join referencias_imp r on pes.placa=r.placa
		LEFT join v_ultima_entrada_taller_datos uet on r.codigo=uet.uetd_serie
		LEFT join tall_encabeza_orden te on uet.uetd_numero=te.numero
		LEFT join terceros t on te.vendedor=t.nit
		where MONTH(CONVERT(DATE,pes.fecha)) = $mes
		and YEAR(CONVERT(DATE,pes.fecha)) = $año and pes.bod in (" . $bod . ")
		group by t.nombres,pes.bod
		";

		return $this->db->query($sql);
	}

	/* Obtener el presupuesto de una sede año y mes actual */
	public function getPresupuesto_sede($ano,$mes,$sede){
		$sql = "select * from presupuesto
		where id_bodega = $sede and Year(fecha_ini) = $ano and MONTH(fecha_ini) = $mes";
		return $this->db->query($sql);
	}
}
