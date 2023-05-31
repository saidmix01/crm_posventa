<?php

/**
 * 
 */
class Presupuesto extends CI_Model
{

	/*
	*metodo para insetar presupuestos  
	 */

	public function insert_data_presupuesto($mo_gasolina, $m_gasolina, $fec_ini, $fec_fin, $mes)
	{
		$sql = "INSERT INTO presupuesto(presupuesto,sede,fecha_ini,fecha_fin,mes)VALUES('$mo_gasolina','$m_gasolina','$fec_ini','$fec_fin','$mes')";
		$this->db->query($sql);
	}
	public function traer_data_presupuesto($fecha_ini, $fecha_fin)
	{
		$sql = "SELECT * FROM presupuesto WHERE sede IN('CODIESEL VILLA DEL ROSARIO','SOLOCHEVROLET MOSTRADOR','CHEVROPARTES MOSTRADOR','CODIESEL LA ROSITA','CODIESEL PRINCIPAL','CODIESEL BARRANCABERMEJA')
		 AND fecha_ini = '$fecha_ini' 
		 AND fecha_fin = '$fecha_fin'";
		return $this->db->query($sql);
	}
	public function insertar_Presupuesto_general($nombre, $ventas_total, $fec_ini, $fec_fin, $mes)
	{
		$sql = "INSERT INTO presupuesto(presupuesto,sede,fecha_ini,fecha_fin,mes)VALUES('$ventas_total','$nombre','$fec_ini','$fec_fin','$mes')";
		$this->db->query($sql);
	}

	public function listar_presupuesto_mes($mes, $year)
	{

		$sql = "SELECT * FROM presupuesto WHERE mes = '$mes' AND  YEAR(fecha_ini) = '$year'";
		return $this->db->query($sql);
	}

	public function get_presupuesto_mes($fecha_ini, $fecha_fin, $s)
	{
		$sql = "SELECT * FROM presupuesto WHERE CONVERT(date,fecha_ini) =  CONVERT(date,'" . $fecha_ini . "') 
		AND CONVERT(date,fecha_fin) =  CONVERT(date,'" . $fecha_fin . "') AND sede ='" . $s . "'";
		return $this->db->query($sql);
	}

	public function get_presupuesto_mes_all($fecha_ini, $fecha_fin)
	{
		$sql = "SELECT * FROM presupuesto WHERE CONVERT(date,fecha_ini) =  CONVERT(date,'" . $fecha_ini . "') 
		AND CONVERT(date,fecha_fin) =  CONVERT(date,'" . $fecha_fin . "')";
		return $this->db->query($sql);
	}

	public function buscar_presupuesto_mes($fecha_ini, $fecha_fin, $s)
	{
		$sql = "SELECT * FROM presupuesto 
			WHERE CONVERT(date,fecha_ini) =  CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, '" . $fecha_ini . "T00:00:00'), 0) ,23) 
			AND CONVERT(date,fecha_fin) =  CONVERT(date,DATEADD(d, -1, DATEADD(m, DATEDIFF(m, 0, '" . $fecha_fin . "T00:00:00') + 1, 0)),23)
			AND sede ='" . $s . "'";
		return $this->db->query($sql);
	}

	public function buscar_presupuesto_mes_sedes($fecha_ini, $fecha_fin, $s)
	{
		$sql = "SELECT * FROM presupuesto 
			WHERE CONVERT(date,fecha_ini) =  CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, '" . $fecha_ini . "T00:00:00'), 0) ,23) 
			AND CONVERT(date,fecha_fin) =  CONVERT(date,DATEADD(d, -1, DATEADD(m, DATEDIFF(m, 0, '" . $fecha_fin . "T00:00:00') + 1, 0)),23)
			AND sede ='" . $s . "'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_presupuesto_mes_sedes($fecha_ini, $fecha_fin, $s)
	{
		$sql = "SELECT * FROM presupuesto WHERE CONVERT(date,fecha_ini) =  CONVERT(date,'" . $fecha_ini . "') 
		AND CONVERT(date,fecha_fin) =  CONVERT(date,'" . $fecha_fin . "') AND sede ='" . $s . "'
		order by id_presupuesto desc";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}



	//PRUEBAS
	public function get_presupuesto_dia($centro_costos)
	{
		$sql = "
				SELECT  total = SUM(valor * -1)
				FROM movimiento
				WHERE (cuenta LIKE '4135%' OR cuenta LIKE '4175%' OR cuenta LIKE '530535%') and centro in (" . $centro_costos . ")
				AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
					AND CONVERT(date,GETDATE())
					 AND
		      tipo not IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL')
		";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}



	public function get_presupuesto_dia_by_mes($fecha)
	{
		$sql = "
			SELECT  total = SUM(valor * -1),DATENAME(month, '" . $fecha . "' ) AS Mes
		FROM movimiento
		WHERE (cuenta LIKE '4135%' OR cuenta LIKE '417510%') AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, '" . $fecha . "'), 0)) 
			AND CONVERT(date,DATEADD(d, -1, DATEADD(m, DATEDIFF(m, 0, '" . $fecha . "') + 1, 0)),23) AND
      tipo IN  ('EB','DBE','TE','DTE','TK','DTK','TL','DTL','TP','DTP','TR','DTR','WE','DWE','WL','DWL','WT','DWT')
		";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function buscar_presupuesto_dia($fecha_ini, $fecha_fin)
	{
		$sql = "
			SELECT  total = SUM(valor * -1)
		FROM movimiento
		WHERE (cuenta LIKE '4135%' OR cuenta LIKE '417510%') AND fec BETWEEN CONVERT(date,'" . $fecha_ini . "')
			AND CONVERT(date,'" . $fecha_fin . "') AND
      tipo IN  ('EB','DBE','TE','DTE','TK','DTK','TL','DTL','TP','DTP','TR','DTR','WE','DWE','WL','DWL','WT','DWT')
		";
		return $this->db->query($sql);
	}
	/*
	Obtiene el total de presupuesto por fecha
	*/
	public function get_total_presupuesto()
	{
		$sql = "
		SELECT  (SUM(valor) * -1) AS 'total' FROM movimiento 
		WHERE (cuenta LIKE '4135%' OR cuenta LIKE '417510%') 
		AND fec BETWEEN '01/03/2020' AND '31/03/2020' 
		AND tipo IN ('EB','DBE','TE','DTE','TK','DTK','TL','DTL','TP','DTP','TR','DTR','WE','DWE','WL','DWL','WT','DWT')";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_total_presupuesto_by_taller($fecha_ini, $fecha_fin, $docs)
	{
		$sql = "SELECT  (SUM(valor) * -1) AS 'total' FROM movimiento WHERE cuenta LIKE '413504%' AND fec BETWEEN '" . $fecha_ini . "' AND '" . $fecha_fin . "' AND tipo IN ('" . $docs . "')";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}
	public function get_total_presupuesto_by_tallerx2($centro_costos)
	{
		$sql = "SELECT  total = SUM(valor * -1)
				FROM movimiento
				WHERE (cuenta LIKE '4135%' OR cuenta LIKE '4175%' OR cuenta LIKE '530535%') and centro in (" . $centro_costos . ")
				AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
					AND CONVERT(date,GETDATE())
					 AND
		      tipo not IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL')";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	//PRUEBA
	public function get_presupuesto_dia_principal()
	{
		$sql = "
			SELECT  total = SUM(valor * -1)
			FROM movimiento
			WHERE (cuenta LIKE '4135%' OR cuenta LIKE '4175%') AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
			AND CONVERT(date,GETDATE()) AND
      tipo IN  ('TE','TP','TL','DTP','DTL','DTE','RP','DV','RRP','DRP') 
      
		";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}


	public function buscar_presupuesto_dia_principal($fecha_ini, $fecha_fin)
	{
		$sql = "
			SELECT  total = SUM(valor * -1)
			FROM movimiento
			WHERE (cuenta LIKE '4135%' OR cuenta LIKE '4175%') AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
			AND CONVERT(date,GETDATE()) AND
      tipo IN  ('TE','TP','TL','DTP','DTL','DTE','RP','DV','RRP','DRP') 
      
		";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}


	//PRUEBA
	public function get_presupuesto_dia_rosita()
	{
		$sql = "SELECT  total = SUM(valor * -1)
		FROM movimiento
		WHERE (cuenta LIKE '4135%' OR cuenta LIKE '4175%') AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
			AND CONVERT(date,GETDATE()) AND
      tipo IN  ('TR','DTR','RR','DRR')";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}


	public function buscar_presupuesto_dia_rosita($fecha_ini, $fecha_fin)
	{
		$sql = "
			SELECT  total = SUM(valor * -1)
			FROM movimiento
			WHERE (cuenta LIKE '4135%' OR cuenta LIKE '417510%') AND fec BETWEEN CONVERT(date,'" . $fecha_ini . "T00:00:00') 
			AND CONVERT(date,'" . $fecha_fin . "T00:00:00') 
			AND tipo IN  ('TR','DTR')
      
		";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}


	//PRUEBA
	public function get_presupuesto_dia_barranca()
	{
		$sql = "
		SELECT  total = SUM(valor * -1)
		FROM movimiento
		WHERE (cuenta LIKE '4135%' OR cuenta LIKE '4175%') AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
			AND CONVERT(date,GETDATE()) AND
      tipo IN  ('EB','TK','DTK','DBE','KR','KDR')
		";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_presupuesto_dia_solochevr()
	{
		$sql = "
		SELECT  total = SUM(valor * -1)
		FROM movimiento
		WHERE (cuenta LIKE '4135%' OR cuenta LIKE '4175%') AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
			AND CONVERT(date,GETDATE()) AND
      tipo IN  ('RY','DRY')
		";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_presupuesto_dia_chevrop()
	{
		$sql = "
		SELECT  total = SUM(valor * -1)
		FROM movimiento
		WHERE (cuenta LIKE '4135%' OR cuenta LIKE '4175%') AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
			AND CONVERT(date,GETDATE()) AND
      tipo IN  ('RQ','DQ')
		";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}


	public function buscar_presupuesto_dia_barranca($fecha_ini, $fecha_fin)
	{
		$sql = "
			SELECT  total = SUM(valor * -1)
			FROM movimiento
			WHERE (cuenta LIKE '4135%' OR cuenta LIKE '417510%') AND fec BETWEEN CONVERT(date,'" . $fecha_ini . "T00:00:00') 
			AND CONVERT(date,'" . $fecha_fin . "T00:00:00') 
			AND tipo IN  ('EB','TK','DTK','DBE')
      
		";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}


	//PRUEBA
	public function get_presupuesto_dia_bocono()
	{
		$sql = "SELECT  total = SUM(valor * -1)
		FROM movimiento
		WHERE (cuenta LIKE '4135%' OR cuenta LIKE '4175%') AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
			AND CONVERT(date,GETDATE()) AND
      tipo IN  ('WE','WT','WL','DWE','DWT','DWL','WR','DWR')";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}




	public function buscar_presupuesto_dia_bocono($fecha_ini, $fecha_fin)
	{
		$sql = "
			SELECT  total = SUM(valor * -1)
			FROM movimiento
			WHERE (cuenta LIKE '4135%' OR cuenta LIKE '417510%') AND fec BETWEEN CONVERT(date,'" . $fecha_ini . "T00:00:00') 
			AND CONVERT(date,'" . $fecha_fin . "T00:00:00') 
			AND tipo IN  ('WE','WT','WL','DWE','DWT','DWL')
      
		";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}


	//PRUEBA
	public function get_presupuesto_rep($centro_costos)
	{
		$sql = "
			SELECT total = SUM(valor * -1)
			FROM movimiento 
			WHERE (cuenta LIKE '413506%' OR cuenta LIKE '417520%' OR cuenta LIKE '53053580%') 
			and centro in (" . $centro_costos . ")
			AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
				AND CONVERT(date,GETDATE())
				 AND
	      tipo NOT  IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL')
		";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}


	//PRUEBA
	public function get_presupuesto_tot($centro_costos)
	{
		$sql = "
			SELECT total = SUM(valor * -1)
			FROM movimiento 
			WHERE (cuenta LIKE '41350410201040%' OR cuenta LIKE '41350410202030%' OR cuenta LIKE '41350410502040%'  OR cuenta LIKE '41350410503030%' OR cuenta LIKE '41350410602040%' OR cuenta LIKE '41350410605030%' OR cuenta LIKE '413504107020%'
			OR cuenta LIKE '413504107050%' OR cuenta LIKE '41350410707030%' OR cuenta in ('417510101073','417510101074','417510501035','417510501036','417510503020','417510601035','417510601036','417510601037','530535601060')) 
			and centro in (" . $centro_costos . ")
			AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
				AND CONVERT(date,GETDATE())
				 AND
	      tipo not IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL')
		";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}


	//PRUEBA
	public function get_presupuesto_mo($centro_costos)
	{
		$sql = "
			SELECT total = SUM(valor * -1)
			FROM movimiento 
			WHERE (cuenta LIKE '413504%' OR cuenta LIKE '417510%' OR cuenta LIKE '53053560%') 
			and centro in (" . $centro_costos . ")
			AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
				AND CONVERT(date,GETDATE())
				 AND
	      tipo not IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL')
		";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}



	//CONSULTAS PARA OBTENER REPUESTOS MOSTRADOR DE LAS SEDES
	public function get_repuestos_mostrador($centro_costos)
	{
		$sql = "SELECT total = SUM(valor * -1)
		FROM movimiento 
		WHERE (cuenta LIKE '413506%' OR cuenta LIKE '417520%' OR cuenta LIKE '53053580%') 
		and centro in (" . $centro_costos . ")
		AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
			AND CONVERT(date,GETDATE())
			 AND
      tipo NOT  IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL')";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}



	public function get_repuestos_mostrador_total()
	{
		$sql = "
				SELECT total = SUM(valor * -1)
				FROM movimiento 
				WHERE (cuenta LIKE '413506%' OR cuenta LIKE '417520%' OR cuenta LIKE '53053580%') 
				and centro in (3,17,11,28,60,15)
				AND fec BETWEEN CONVERT(date,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)) 
					AND CONVERT(date,GETDATE())
					 AND
		      tipo NOT  IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI')
		      ";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	/*FUNCION PARA CARGAR EL PRESUPUESTO HISTORICO 
 *RECIBE NUMEROS ENTRE 0 Y -11 PARA TRAER LOS MESES ANTERIORES
*/
	public function get_presupuesto_historico($m1, $m2)
	{
		$sql = "
			SELECT total = SUM(valor), DateName(month,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . ($m2 + 1) . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)) + ' ' +DateName(year,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . ($m2 + 1) . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)) AS fecha
			FROM movimiento
			WHERE (cuenta LIKE '4135%' OR cuenta LIKE '4175%' OR cuenta LIKE '530535%') and centro in (4,40,33,45,3,16,17,13,70,11,29,80,31,46,28,60,15)
			AND fec BETWEEN 
			CONVERT(DATE,DATEADD(mm," . $m2 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)),110)
			AND 
			CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . $m1 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)
			AND tipo not IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL','Z1','DIE')
		";
		//
		return $this->db->query($sql);
	}

	public function get_presupuesto_historico1($m1, $m2)
	{
		$sql = "
			SELECT total = SUM(valor), DateName(month,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . $m2 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)) + ' ' +DateName(year,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . $m2 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)) AS fecha
			FROM movimiento
			WHERE (cuenta LIKE '4135%' OR cuenta LIKE '4175%' OR cuenta LIKE '530535%') and centro in (4,40,33,45,3,16,17,13,70,11,29,80,31,46,28,60,15)
			AND fec BETWEEN 
			CONVERT(DATE,DATEADD(mm," . ($m2 - 1) . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)),110)
			AND 
			CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . ($m1 - 1) . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)
			AND tipo not IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL','Z1','DIE')
		";
		//
		return $this->db->query($sql);
	}

	public function get_presupuesto_def_hist()
	{
		$sql = "SELECT TOP 13 presupuesto,DateName(month,fecha_fin) + ' ' +DateName(year,fecha_fin) AS fecha FROM presupuesto WHERE sede = 'CODIESEL' 
				AND fecha_ini != DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)
				ORDER BY id_presupuesto DESC";
		return $this->db->query($sql);
	}

	public function get_meta_presupuesto_historico($m1, $m2)
	{
		$sql = "
			SELECT TOP 1 * FROM presupuesto 
			WHERE CONVERT(date,fecha_ini) BETWEEN CONVERT(DATE,DATEADD(mm," . $m2 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)),110)
			AND  CONVERT(DATE,DATEADD(ms," . $m1 . ",DATEADD(mm,-3,DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)
			AND sede ='CODIESEL'
		";
		return $this->db->query($sql);
	}

	public function get_presupuesto_tall($m1, $m2)
	{
		$sql = "SELECT total = SUM(valor * -1),
			DateName(month,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . ($m1) . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)) + ' ' +DateName(year,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . ($m1) . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)) as fecha
			FROM movimiento a inner join cuentas b on a.cuenta=b.cuenta
			WHERE (a.cuenta LIKE '413506%' OR a.cuenta LIKE '417520%' OR a.cuenta LIKE '53053580%')
			and (descripcion not like '%ACCESORIO%' and descripcion NOT like '%GARANT%' and descripcion not like '%gtia%'
			and descripcion not like '%gtía%')
			and centro in (4,40,16,13,70,29,80)
			and fec BETWEEN 
			CONVERT(DATE,DATEADD(mm," . $m2 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)),110)
			AND 
			CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . $m1 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)
			and tipo NOT  IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL','Z1','DIE')";
		return $this->db->query($sql);
	}

	public function get_presupuesto_colicion($m1, $m2)
	{
		$sql = "SELECT total = SUM(valor * -1),
				DateName(month,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . ($m1) . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)) + ' ' +DateName(year,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . ($m1) . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)) as fecha
				FROM movimiento a inner join cuentas b on a.cuenta=b.cuenta
				WHERE (a.cuenta LIKE '413506%' OR a.cuenta LIKE '417520%' OR a.cuenta LIKE '53053580%')
				and (descripcion not like '%ACCESORIO%' and descripcion NOT like '%GARANT%' and descripcion not like '%gtia%'
				and descripcion not like '%gtía%')
				and centro in (33,45,31,46)
				and fec BETWEEN 
				CONVERT(DATE,DATEADD(mm," . $m2 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)),110)
				AND 
				CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . $m1 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)
				and tipo NOT  IN('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL','Z1','DIE')";
		return $this->db->query($sql);
	}

	public function get_presupuesto_mostrador($m1, $m2)
	{
		$sql = "SELECT total = SUM(valor * -1),
DateName(month,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . ($m1) . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)) + ' ' +DateName(year,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . ($m1) . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)) as fecha
FROM movimiento a inner join cuentas b on a.cuenta=b.cuenta
WHERE (a.cuenta LIKE '413506%' OR a.cuenta LIKE '417520%' OR a.cuenta LIKE '53053580%')
and (descripcion not like '%ACCESORIO%' and descripcion NOT like '%GARANT%' and descripcion not like '%gtia%'
and descripcion not like '%gtía%')
and centro in (3,17,11,28,15,60)
and fec BETWEEN CONVERT(DATE,DATEADD(mm," . $m2 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)),110)
AND CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . $m1 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)
and tipo NOT  IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL','Z1','DIE')";
		return $this->db->query($sql);
	}

	public function get_presupuesto_otros($m1, $m2)
	{
		$sql = "SELECT total = SUM(valor * -1),
DateName(month,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . ($m1) . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)) + ' ' +DateName(year,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . ($m1) . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)) as fecha
FROM movimiento a inner join cuentas b on a.cuenta=b.cuenta
WHERE (a.cuenta LIKE '413506%' OR a.cuenta LIKE '417520%' OR a.cuenta LIKE '53053580%')
and (descripcion like '%ACCESORIO%' OR descripcion like '%GARANT%' OR descripcion like '%gtia%'
OR descripcion like '%gtía%')
and fec BETWEEN CONVERT(DATE,DATEADD(mm," . $m2 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)),110)
AND CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . $m1 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)
and tipo NOT  IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL','Z1','DIE')";
		return $this->db->query($sql);
	}

	//tot historico
	public function get_presupuesto_tot_hist($m1, $m2, $centro_costos)
	{
		$sql = "
			SELECT total = SUM(valor * -1),
DateName(month,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . ($m1) . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)) + ' ' +DateName(year,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . ($m1) . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)) as fecha
			FROM movimiento 
			WHERE (cuenta LIKE '41350410201040%' OR cuenta LIKE '41350410202030%' OR cuenta LIKE '41350410502040%'  OR cuenta LIKE '41350410503030%' OR cuenta LIKE '41350410602040%' OR cuenta LIKE '41350410605030%' OR cuenta LIKE '413504107020%'
			OR cuenta LIKE '413504107050%' OR cuenta LIKE '41350410707030%' OR cuenta in ('417510101073','417510101074','417510501035','417510501036','417510503020','417510601035','417510601036','417510601037','530535601060')) 
			and centro in (" . $centro_costos . ")
			AND fec BETWEEN CONVERT(DATE,DATEADD(mm," . $m2 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)),110)
AND CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . $m1 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)
				 AND
	      tipo not IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL','Z1')
		";
		return $this->db->query($sql);
	}

	public function get_presupuesto_mo_hist($m1, $m2, $centro_costos)
	{
		$sql = "
			SELECT total = SUM(valor * -1),
DateName(month,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . ($m1) . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)) + ' ' +DateName(year,CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . ($m1) . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)) as fecha
			FROM movimiento 
			WHERE (cuenta LIKE '413504%' OR cuenta LIKE '417510%' OR cuenta LIKE '53053560%') 
			and centro in (" . $centro_costos . ")
			AND fec BETWEEN CONVERT(DATE,DATEADD(mm," . $m2 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)),110)
AND CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm," . $m1 . ",DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0))),110)
				 AND
	      tipo not IN  ('SIR','IT','BC','SIK','IK','SIQ','SIL','IL','SIT','SIW','WI','DIT','DIK','DIW','DIL','Z1')
		";
		return $this->db->query($sql);
	}
}
