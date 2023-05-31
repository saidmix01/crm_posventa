<?php

/**
 * 
 */
class Nominas extends CI_Model
{

	public function insert_comision($data)
	{
		$nit = $data['nit'];
		$presupuesto = $data['Presupuesto'];
		$comision = $data['comision'];
		$otros = $data['otros'];
		$cargo = $data['cargo'];
		$contrato = $data['contrato'];
		$nombres = $data['nombres'];
		$bodega = $data['bodega'];
		$patio = $data['patio'];
		$sql = "INSERT INTO tall_operarios_intranet(nit,Presupuesto,Comision,Otros,cargo,contrato,nombre,bodega,patio) VALUES('$nit','$presupuesto','$comision','$otros','$cargo','$contrato','$nombres','$bodega','$patio')";
		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}


	public function update_comision($data, $idu)
	{
		$this->db->where('nit', $idu);
		$this->db->update('tall_operarios_intranet', $data);
	}

	public function update_comision_all($data, $bod)
	{
		$this->db->where('bodega', $bod);
		$this->db->update('tall_operarios_intranet', $data);
	}

	public function update_comision_all_x2($data, $bod1, $bod2)
	{
		$this->db->where('bodega', $bod1);
		$this->db->where('bodega', $bod2);
		$this->db->update('tall_operarios_intranet', $data);
	}

	public function listar_bodegas()
	{
		$sql = "SELECT * FROM bodegas";
		return $this->db->query($sql);
	}

	public function obtener_primer_dia_mes()
	{
		$sql = "SELECT CONVERT(varchar,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0) ,23) AS 'fecha'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function obtener_primer_dia_mes_dma()
	{
		$sql = "SELECT CONVERT(varchar,DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0) ,103) AS 'fecha'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_dia_actual()
	{
		$sql = "SELECT CONVERT(VARCHAR(2),GETDATE(),103) AS 'dia_actual'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_mes_actual()
	{
		$sql = "SELECT CONVERT(VARCHAR(2),GETDATE(),110) AS 'mes'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_ano_actual()
	{
		$sql = "SELECT CONVERT(VARCHAR(4),GETDATE(),102) AS 'ano'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}



	public function obtener_ultimo_dia_mes()
	{
		$sql = "SELECT CONVERT(varchar,DATEADD(d, -1, DATEADD(m, DATEDIFF(m, 0, GETDATE()) + 1, 0)),23) AS 'fecha'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function obtener_ultimo_dia_mes_dma()
	{
		$sql = "SELECT CONVERT(varchar,DATEADD(d, -1, DATEADD(m, DATEDIFF(m, 0, GETDATE()) + 1, 0)),103) AS 'fecha'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function obtener_fecha_actual()
	{
		$sql = "SELECT CONVERT(varchar,DATEADD(d, -1, DATEADD(m, DATEDIFF(m, 0, GETDATE()) + 1, 0)),103) AS 'fecha'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	/*public function listar_empleados_fecha($fecha,$bodega)
	{
		$sql = "
			SELECT DISTINCT a.operario, presupuesto_def = 50000, Comision =50000, Otros = 0, id = '',d.patio,  contrato = MAX(b.contrato),
 bodega = (CASE WHEN MAX(d.patio) IN (1,13) THEN 1
                WHEN MAX(d.patio) = 3 THEN 11 
                WHEN MAX(d.patio) = 11 THEN 2
                WHEN MAX(d.patio) IN (5,6) THEN 5
                WHEN MAX(d.patio) = 4 THEN 4
                WHEN MAX(d.patio) = 7 THEN 7
                WHEN MAX(d.patio) = 12 THEN 12
                WHEN MAX(d.patio) = 8 THEN 8 END), c.nombres,
                
 bodega_des = (CASE WHEN MAX(d.patio) IN (1,13) THEN 'GASOLINA GIRON'
                WHEN MAX(d.patio) = 3 THEN 'DIESEL GIRON'
                WHEN MAX(d.patio) = 11 THEN 'ELECTRICISTAS'
                WHEN MAX(d.patio) IN (5,6) THEN 'GASOLINA BARRANCA'
                WHEN MAX(d.patio) = 4 THEN 'GASOLINA ROSITA'
                WHEN MAX(d.patio) = 7 THEN 'GASOLINA BOCONO'
                WHEN MAX(d.patio) = 12 THEN 'ALINEADORES'
                WHEN MAX(d.patio) = 8 THEN 'MASTER' END)
                
                
FROM tall_documentos_lin a INNER JOIN y_personal_contratos b ON a.operario = b.nit
                           INNER JOIN terceros c on a.operario = c.nit
                           INNER JOIN tall_operarios d ON b.nit = d.nit
                           INNER JOIN y_plantas e ON b.planta = e.planta
WHERE a.fec >= '".$fecha."' AND d.patio  IN (".$bodega.")  -- AND a.operario = 91519317 
GROUP BY a.operario, c.nombres, d.patio




UNION ALL

SELECT DISTINCT a.operario, presupuesto_def = 50000, Comision =50000, Otros = 0, id = '', d.patio,  contrato = 0,
 bodega = (CASE WHEN d.patio IN (1,13) THEN 1
                WHEN d.patio = 3 THEN 11 
                WHEN d.patio = 11 THEN 2
                WHEN d.patio IN (5,6) THEN 5
                WHEN d.patio = 4 THEN 4
                WHEN d.patio = 7 THEN 7
                WHEN d.patio = 12 THEN 12
                WHEN d.patio = 8 THEN 8 END), c.nombres, 
                
 bodega_des = (CASE WHEN d.patio IN (1,13) THEN 'GASOLINA GIRON'
                WHEN d.patio = 3 THEN 'DIESEL GIRON'
                WHEN d.patio = 3 THEN 'DIESEL GIRON'
                WHEN d.patio = 11 THEN 'ELECTRICISTAS'
                WHEN d.patio IN (5,6) THEN 'GASOLINA BARRANCA'
                WHEN d.patio = 4 THEN 'GASOLINA ROSITA'
                WHEN d.patio = 7 THEN 'GASOLINA BOCONO'
                WHEN d.patio = 12 THEN 'ALINEADORES'
                WHEN d.patio = 8 THEN 'DIESEL BOCONO' END)
FROM tall_documentos_lin a INNER JOIN terceros c on a.operario = c.nit
                           INNER JOIN tall_operarios d ON c.nit = d.nit
WHERE a.fec >= '".$fecha."' AND a.operario NOT IN (SELECT nit FROM y_personal_contratos)  AND patio NOT IN ('09,21')   AND a.operario > '10000' 
AND  d.patio  IN (".$bodega.")      
GROUP BY a.operario,  c.nombres, d.patio

		";
		return $this->db->query($sql);
	}*/

	public function listar_empleados_fecha($fecha, $bodega)
	{
		$sql = "
		SELECT DISTINCT a.operario, c.nombres,  fec = '', presupuesto_def = 50000, Comision =50000, Otros = 0, id = '', mes ='3', 
		Ano = '2020',  contrato = MAX(b.contrato),
		bodega = (CASE WHEN MAX(d.patio) IN (1,13) THEN 1
		WHEN MAX(d.patio) = 3 THEN 11 
		WHEN MAX(d.patio) = 11 THEN 2
		WHEN MAX(d.patio) IN (5) THEN 5
		WHEN MAX(d.patio) IN (6) THEN 6
		WHEN MAX(d.patio) = 4 THEN 4
		WHEN MAX(d.patio) IN (7,14) THEN 7
		WHEN MAX(d.patio) = 12 THEN 12
		WHEN MAX(d.patio) = 8 THEN 8 END),


		bodega_des = (CASE WHEN MAX(d.patio) IN (1,13) THEN 'GASOLINA GIRON'
		WHEN MAX(d.patio) = 3 THEN 'DIESEL GIRON'
		WHEN MAX(d.patio) = 11 THEN 'ELECTRICISTAS'
		WHEN MAX(d.patio) IN (5) THEN 'GASOLINA BARRANCA'
		WHEN MAX(d.patio) IN (6) THEN 'DIESEL BARRANCA'
		WHEN MAX(d.patio) = 4 THEN 'GASOLINA ROSITA'
		WHEN MAX(d.patio) IN (7,14) THEN 'GASOLINA BOCONO'
		WHEN MAX(d.patio) = 12 THEN 'ALINEADORES'
		WHEN MAX(d.patio) = 8 THEN 'MASTER' END), d.patio


		FROM tall_documentos_lin a INNER JOIN y_personal_contratos b ON a.operario = b.nit
		INNER JOIN terceros c on a.operario = c.nit
		INNER JOIN tall_operarios d ON b.nit = d.nit
		INNER JOIN y_plantas e ON b.planta = e.planta
		WHERE a.fec >= '" . $fecha . "' AND d.patio  IN (" . $bodega . ") 
		GROUP BY a.operario, c.nombres,  d.patio




		UNION ALL

		SELECT DISTINCT a.operario, c.nombres, fec = '', presupuesto_def = 50000, Comision =50000, Otros = 0, id = '', mes ='3',  Ano = '', contrato = 0,
		bodega = (CASE WHEN d.patio IN (1,13) THEN 1
		WHEN d.patio = 3 THEN 11 
		WHEN d.patio = 11 THEN 2
		WHEN d.patio IN (5) THEN 5
		WHEN d.patio IN (6) THEN 5
		WHEN d.patio = 4 THEN 4
		WHEN d.patio IN (7,14) THEN 7
		WHEN d.patio = 12 THEN 12
		WHEN d.patio = 8 THEN 8 END),

		bodega_des = (CASE WHEN d.patio IN (1,13) THEN 'GASOLINA GIRON'
		WHEN d.patio = 3 THEN 'DIESEL GIRON'
		WHEN d.patio = 3 THEN 'DIESEL GIRON'
		WHEN d.patio = 11 THEN 'ELECTRICISTAS'
		WHEN d.patio IN (5) THEN 'GASOLINA BARRANCA'
		WHEN MAX(d.patio) IN (6) THEN 'DIESEL BARRANCA'
		WHEN d.patio = 4 THEN 'GASOLINA ROSITA'
		WHEN d.patio IN (7,14) THEN 'GASOLINA BOCONO'
		WHEN d.patio = 12 THEN 'ALINEADORES'
		WHEN d.patio = 8 THEN 'DIESEL BOCONO' END), d.patio
		FROM tall_documentos_lin a INNER JOIN terceros c on a.operario = c.nit
		INNER JOIN tall_operarios d ON c.nit = d.nit
		WHERE a.fec >= '" . $fecha . "' AND a.operario NOT IN (SELECT nit FROM y_personal_contratos)  AND patio NOT IN ('09,21')   AND a.operario > '10000' 
		AND  d.patio  IN (" . $bodega . ")       
		GROUP BY a.operario,  c.nombres, d.patio	
		";
		return $this->db->query($sql);
	}



	public function listar_empleados($fecha, $ano, $mes)
	{
		$sql = "
		SELECT DISTINCT a.operario, c.nombres,  fec = '" . $fecha . "', presupuesto_def = 50000, Comision =50000, Otros = 0, id = '', mes =" . $mes . ", 
		Ano = " . $ano . ", contrato = MAX(b.contrato),
		bodega = (CASE WHEN MAX(d.patio) IN (1,13) THEN 1
		WHEN MAX(d.patio) = 3 THEN 11 
		WHEN MAX(d.patio) = 11 THEN 2
		WHEN MAX(d.patio) IN (5,6) THEN 5
		WHEN MAX(d.patio) = 4 THEN 4
		WHEN MAX(d.patio) = 7 THEN 7
		WHEN MAX(d.patio) = 12 THEN 12
		WHEN MAX(d.patio) = 8 THEN 8 END),

		bodega_des = (CASE WHEN MAX(d.patio) IN (1,13) THEN 'GASOLINA GIRON'
		WHEN MAX(d.patio) = 3 THEN 'DIESEL GIRON'
		WHEN MAX(d.patio) = 11 THEN 'ELECTRICISTAS'
		WHEN MAX(d.patio) IN (5,6) THEN 'GASOLINA BARRANCA'
		WHEN MAX(d.patio) = 4 THEN 'GASOLINA ROSITA'
		WHEN MAX(d.patio) = 7 THEN 'GASOLINA BOCONO'
		WHEN MAX(d.patio) = 12 THEN 'ALINEADORES'
		WHEN MAX(d.patio) = 8 THEN 'MASTER' END)


		FROM tall_documentos_lin a INNER JOIN y_personal_contratos b ON a.operario = b.nit
		INNER JOIN terceros c on a.operario = c.nit
		INNER JOIN tall_operarios d ON b.nit = d.nit
		INNER JOIN y_plantas e ON b.planta = e.planta
		WHERE a.fec >= '" . $fecha . "' AND d.patio  IN (1,13,3,11,5,6,4,7,12,8)   
		GROUP BY a.operario, c.nombres




		UNION ALL

		SELECT DISTINCT a.operario, c.nombres, fec = '" . $fecha . "', presupuesto_def = 50000, Comision =50000, Otros = 0, id = '', mes =" . $mes . ",  Ano = " . $ano . ", contrato = 0,
		bodega = (CASE WHEN d.patio IN (1,13) THEN 1
		WHEN d.patio = 3 THEN 11 
		WHEN d.patio = 11 THEN 2
		WHEN d.patio IN (5,6) THEN 5
		WHEN d.patio = 4 THEN 4
		WHEN d.patio = 7 THEN 7
		WHEN d.patio = 12 THEN 12
		WHEN d.patio = 8 THEN 8 END),

		bodega_des = (CASE WHEN d.patio IN (1,13) THEN 'GASOLINA GIRON'
		WHEN d.patio = 3 THEN 'DIESEL GIRON'
		WHEN d.patio = 3 THEN 'DIESEL GIRON'
		WHEN d.patio = 11 THEN 'ELECTRICISTAS'
		WHEN d.patio IN (5,6) THEN 'GASOLINA BARRANCA'
		WHEN d.patio = 4 THEN 'GASOLINA ROSITA'
		WHEN d.patio = 7 THEN 'GASOLINA BOCONO'
		WHEN d.patio = 12 THEN 'ALINEADORES'
		WHEN d.patio = 8 THEN 'DIESEL BOCONO' END)
		FROM tall_documentos_lin a INNER JOIN terceros c on a.operario = c.nit
		INNER JOIN tall_operarios d ON c.nit = d.nit
		WHERE a.fec >= '" . $fecha . "' AND a.operario NOT IN (SELECT nit FROM y_personal_contratos)  AND patio NOT IN ('09,21')   AND a.operario > '10000' 
		AND  d.patio  IN (1,13,3,11,5,6,4,7,12,8)       
		GROUP BY a.operario,  c.nombres, d.patio
		";
		return $this->db->query($sql);
	}


	public function validar_mes_ano($bod)
	{
		$sql = "SELECT COUNT(*) AS 'num' FROM tall_operarios_intranet WHERE patio IN(" . $bod . ")";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_tall_operarios_intranet($bod)
	{
		$sql = "SELECT 
		patio = (CASE WHEN patio=1 THEN 'GASOLINA GIRON'
		WHEN  patio = 3 THEN 'DIESEL GIRON'
		WHEN  patio = 5 THEN 'GASOLINA BARRANCA'
		WHEN  patio  = 6 THEN 'DIESEL BARRANCA'
		WHEN  patio = 4 THEN 'GASOLINA ROSITA'
		WHEN  patio = 7 THEN 'GASOLINA BOCONO'
		WHEN  patio = 8 THEN 'DIESEL BOCONO'
		WHEN patio=2 THEN 'LYP GIRON'
		WHEN patio=9 then 'LYP BOCONO'
		END
		),
		nit, nombre, presupuesto, Comision, bodega,patio
		FROM tall_operarios_intranet
		WHERE patio IN(" . $bod . ")
		";
		return $this->db->query($sql);
	}

	public function get_tall_operarios_intranet_all()
	{
		$sql = "
		SELECT 
		patio = (CASE WHEN patio IN (1,13,12) THEN 'GASOLINA GIRON'
		WHEN  patio = 3 THEN 'DIESEL GIRON'
		WHEN  patio = 11 THEN 'ELECTRICISTAS'
		WHEN  patio = 5 THEN 'GASOLINA BARRANCA'
		WHEN  patio  = 6 THEN 'DIESEL BARRANCA'
		WHEN  patio = 4 THEN 'GASOLINA ROSITA'
		WHEN  patio = 7 THEN 'GASOLINA BOCONO'
		WHEN  patio = 12 THEN 'ALINEADORES'
		WHEN  patio = 8 THEN 'DIESEL BOCONO'
		END
		),
		nit, nombre, presupuesto, Comision, bodega,patio
		FROM tall_operarios_intranet
			--WHERE patio IN()
			";
		return $this->db->query($sql);
	}

	public function get_nomina_accesorios($bod,$ano=null,$mes=null)
	{
		$anio = $ano == null ? "YEAR(GETDATE())" : $ano ;
		$month = $mes == null ? "MONTH(GETDATE())" : $mes ;
		$sql = "
			select vendedor_detalle,SUM(accesorios) as ACCESORIOS, SUM(repuestos) as REPUESTOS, SUM(lubricantes) as LUBRICANTES
			from (
			select vendedor_detalle,
			SUM(case when des_contable='ACCESORIOS' then (subtotal-descuento) else 0 end) as ACCESORIOS,
			SUM(case when des_contable='REPUESTOS' then (subtotal-descuento) else 0 end) as REPUESTOS,
			SUM(case when des_contable='LUBRICANTES' then (subtotal-descuento) else 0 end) as LUBRICANTES
			from v_rep_base_nomina_AMDR
			where ano=".$anio." and mes=".$month." and bodega in (" . $bod . ")
			group by vendedor_detalle,des_contable
			) a
			group by vendedor_detalle
			";
		return $this->db->query($sql);
	}

	public function get_nomina_serv_rep($bod,$ano=null,$mes=null)
	{
		$anio = $ano == null ? "YEAR(GETDATE())" : $ano ;
		$month = $mes == null ? "MONTH(GETDATE())" : $mes ;
		$sql = "select vendedor_detalle,SUM(convert(money,accesorios)) as ACCESORIOS, SUM(convert(money,repuestos)) as REPUESTOS, SUM(convert(money,lubricantes)) as LUBRICANTES
			from (
			select vendedor_detalle,
			ACCESORIOS=(case when des_contable='ACCESORIOS' THEN SUM(subtotal-descuento) else 0 end),
			REPUESTOS=(case when des_contable='REPUESTOS' THEN SUM(subtotal-descuento) else 0 end),
			LUBRICANTES=(case when des_contable='LUBRICANTES' THEN SUM(subtotal-descuento) else 0 end)
			from v_rep_base_nomina_AMDR
			where ano=".$anio." and mes=".$month." and bodega in (" . $bod . ")
			GROUP BY vendedor_detalle,des_contable
			) a
			group by vendedor_detalle";
		return $this->db->query($sql);
	}

	/*public function get_comision_rep_mostrador()
	{
		$sql = "
			select CASE 
			WHEN usuario = 'CRANGEL' THEN 'RANGEL REYES CRISTIAN ORLANDO' 
			WHEN usuario = 'FERNANDO' THEN 'CADENA RAMIREZ FERNANDO ANTONIO'
			WHEN usuario = 'FIDEL' THEN 'CARRILLO ANGARITA FIDEL'
			WHEN usuario = 'JMANUEL' THEN 'LOPEZ JUAN MANUEL'
			WHEN usuario = 'JOLAYA' THEN 'OLAYA CALDERON JOSE ALLENDY'
			WHEN usuario = 'LEONARDO' THEN 'ABRIL RAMIREZ LEONARDO'
			WHEN usuario = 'M-ABRIL' THEN 'ABRIL RAMIREZ LEONARDO'
			WHEN usuario = 'QDIEGO' THEN 'QUIÑONEZ NAVAS DIEGO ALONSO' 
			END AS vendedor_detalle,
			SUM(convert(money,subtotal)) as subtotal, SUM(convert(money,descuento)) as descuento, SUM(convert(money,[Subtotal-Descuento])) as venta_neta, 
			SUM(convert(money,costo)) as costo_neto, sum(convert(money,[Subtotal-Descuento]-costo)) as utilidad, 
			convert(decimal(10,2),(sum([Subtotal-Descuento]-costo)/sum([Subtotal-Descuento]))* 100) as margen

			from v_rep_base_nomina_AMDR_base_usuarios_traslados

			where ano=2021 and mes=1 and tipo_venta='TALLER' and usuario<>'CRANGEL' 
			Group by usuario
			union 
			select 
			CASE 
			WHEN usuario = 'CRANGEL' THEN 'RANGEL REYES CRISTIAN ORLANDO' 
			WHEN usuario = 'FERNANDO' THEN 'CADENA RAMIREZ FERNANDO ANTONIO'
			WHEN usuario = 'FIDEL' THEN 'CARRILLO ANGARITA FIDEL'
			WHEN usuario = 'JMANUEL' THEN 'LOPEZ JUAN MANUEL'
			WHEN usuario = 'JOLAYA' THEN 'OLAYA CALDERON JOSE ALLENDY'
			WHEN usuario = 'LEONARDO' THEN 'ABRIL RAMIREZ LEONARDO'
			WHEN usuario = 'M-ABRIL' THEN 'ABRIL RAMIREZ LEONARDO'
			WHEN usuario = 'QDIEGO' THEN 'QUIÑONEZ NAVAS DIEGO ALONSO' 
			END AS vendedor_detalle,
			SUM(convert(money,subtotal)) as subtotal, SUM(convert(money,descuento)) as descuento, SUM(convert(money,[Subtotal-Descuento])) as venta_neta, 
			SUM(convert(money,costo)) as costo_neto, sum(convert(money,[Subtotal-Descuento]-costo)) as utilidad, 
			convert(decimal(10,2),(sum([Subtotal-Descuento]-costo)/sum([Subtotal-Descuento]))* 100) as margen
			from v_rep_base_nomina_AMDR_base_usuarios_traslados

			where ano=2021 and mes=1 and tipo_venta='TALLER' and usuario='CRANGEL' and des_contable<>'ACCESORIOS'
			Group by usuario
			UNION
			select vendedor_detalle,SUM(convert(money,subtotal)) as subtotal, SUM(convert(money,descuento)) as descuento, SUM(convert(money,[Subtotal-Descuento])) as venta_neta, 
			SUM(convert(money,costo)) as costo_neto, sum(convert(money,[Subtotal-Descuento]-costo)) as utilidad, 
			convert(decimal(10,2),(sum([Subtotal-Descuento]-costo)/sum([Subtotal-Descuento]))* 100) as margen
			from v_rep_base_nomina_AMDR

			where ano=2021 and mes=1 and tipo_venta='MOSTRADOR'
			Group by vendedor_detalle
		";
		return $this->db->query($sql);
	}*/

	public function get_comision_rep_mostrados_mayor($nom, $mes, $ano)
	{
		$sql = "select vendedor_detalle,SUM(convert(money,subtotal)) as subtotal, SUM(convert(money,descuento)) as descuento, SUM(convert(money,[Subtotal-Descuento])) as venta_neta, 
		SUM(convert(money,costo)) as costo_neto, sum(convert(money,[Subtotal-Descuento]-costo)) as utilidad, 
		case
		when sum([Subtotal-Descuento]) = 0 then 0
		when sum([Subtotal-Descuento]) > 0 then (convert(decimal(10,2),(sum([Subtotal-Descuento]-costo)/sum([Subtotal-Descuento]))* 100))
		end as margen
		from v_rep_base_nomina_AMDR

		where ano=" . $ano . " and mes=" . $mes . " and tipo_venta='MOSTRADOR' and usuario like 'M-%'
		AND vendedor_detalle = '" . $nom . "'
		Group by vendedor_detalle
		";
		return $this->db->query($sql);
	}

	public function get_comision_rep_mostrador_sin_mayor($nombre, $mes, $ano)
	{
		$sql = "
		select vendedor_detalle,SUM(convert(money,subtotal)) as subtotal, SUM(convert(money,descuento)) as descuento, SUM(convert(money,[Subtotal-Descuento])) as venta_neta, 
		SUM(convert(money,costo)) as costo_neto, sum(convert(money,[Subtotal-Descuento]-costo)) as utilidad,
		case
		when sum([Subtotal-Descuento]) = 0 then 0
		when sum([Subtotal-Descuento]) > 0 then (convert(decimal(10,2),(sum([Subtotal-Descuento]-costo)/sum([Subtotal-Descuento]))* 100))
		end  as margen 		
		from v_rep_base_nomina_AMDR

		where ano=" . $ano . " and mes=" . $mes . " and tipo_venta='MOSTRADOR' and usuario not like 'M-%'
		AND vendedor_detalle = '" . $nombre . "'
		Group by vendedor_detalle
		";
		return $this->db->query($sql);
	}

	public function get_comision_rep_mostrador($nombre, $mes, $ano)
	{
		$sql = "
		select vendedor_detalle,SUM(convert(money,subtotal)) as subtotal, SUM(convert(money,descuento)) as descuento, SUM(convert(money,[Subtotal-Descuento])) as venta_neta, 
		SUM(convert(money,costo)) as costo_neto, sum(convert(money,[Subtotal-Descuento]-costo)) as utilidad, 
		case
		when sum([Subtotal-Descuento]) = 0 then 0
		when sum([Subtotal-Descuento]) > 0 then (convert(decimal(10,2),(sum([Subtotal-Descuento]-costo)/sum([Subtotal-Descuento]))* 100)) 
		end as margen
		from v_rep_base_nomina_AMDR

		where ano=" . $ano . " and mes=" . $mes . " and tipo_venta='MOSTRADOR' 
		AND vendedor_detalle = '" . $nombre . "'
		Group by vendedor_detalle
		";
		return $this->db->query($sql);
	}

	public function get_comision_rep_mostrador_luis_e($nombre, $mes, $ano)
	{
		$sql = "
		select vendedor_detalle,SUM(convert(money,subtotal)) as subtotal, SUM(convert(money,descuento)) as descuento, SUM(convert(money,[Subtotal-Descuento])) as venta_neta, 
		SUM(convert(money,costo)) as costo_neto, sum(convert(money,[Subtotal-Descuento]-costo)) as utilidad,
		case
		when sum([Subtotal-Descuento]) = 0 then 0
		when sum([Subtotal-Descuento]) > 0 then (convert(decimal(10,2),(sum([Subtotal-Descuento]-costo)/sum([Subtotal-Descuento]))* 100))
		end  as margen
		
		from v_rep_base_nomina_AMDR

		where ano=" . $ano . " and mes=" . $mes . "
		AND vendedor_detalle = '" . $nombre . "'
		Group by vendedor_detalle
		";
		return $this->db->query($sql);
	}

	public function get_comision_rep_taller($nom, $mes, $ano)
	{
		$sql = "
		select usuario,SUM(convert(money,subtotal)) as subtotal, SUM(convert(money,descuento)) as descuento, SUM(convert(money,[Subtotal-Descuento])) as venta_neta, 
		SUM(convert(money,costo)) as costo_neto, sum(convert(money,[Subtotal-Descuento]-costo)) as utilidad,
		case
		when sum([Subtotal-Descuento]) = 0 then 0
		when sum([Subtotal-Descuento]) > 0 then (convert(decimal(10,2),(sum([Subtotal-Descuento]-costo)/sum([Subtotal-Descuento]))* 100))
		end as margen 
		
		from v_rep_base_nomina_AMDR_base_usuarios_traslados

		where ano=" . $ano . " and mes=" . $mes . " and tipo_venta='TALLER' and usuario<>'CRANGEL' and usuario = '" . $nom . "'
		Group by usuario
		union 
		select usuario,SUM(convert(money,subtotal)) as subtotal, SUM(convert(money,descuento)) as descuento, SUM(convert(money,[Subtotal-Descuento])) as venta_neta, 
		SUM(convert(money,costo)) as costo_neto, sum(convert(money,[Subtotal-Descuento]-costo)) as utilidad, 
		convert(decimal(10,2),(sum([Subtotal-Descuento]-costo)/sum([Subtotal-Descuento]))* 100) as margen
		from v_rep_base_nomina_AMDR_base_usuarios_traslados

		where ano=" . $ano . " and mes=" . $mes . " and tipo_venta='TALLER' and usuario='CRANGEL' and des_contable<>'ACCESORIOS' and usuario = '" . $nom . "'
		Group by usuario
		";
		return $this->db->query($sql);
	}


	/* NOMINA DE LAMINA Y PINTURA */

	public function get_nomina_lyp($desde, $hasta)
	{
		$sql = "select a.operario, a.nombres,t.descripcion,
		productividad=convert (decimal (10,2),(SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))*100),
		horas_trabajadas=SUM(tiempo_facturado), 
		horas_productivas_mes=(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')),
		porcentaje_liquidacion=case when a.concepto='4' then '20' 
		when a.concepto='5' and o.patio='2' then '40' 
		when a.concepto='5' and o.patio='9' then '60'
		when a.concepto in ('7','8') then '24'
		when a.concepto='9' and (SUM(a.facturado)+SUM(internas))<=4000000 then '20'
		when a.concepto='9' and (SUM(a.facturado)+SUM(internas))<=5000000 then '22'
		when a.concepto='9' and (SUM(a.facturado)+SUM(internas))<=7000000 then '24'
		when a.concepto='9' and (SUM(a.facturado)+SUM(internas))<=9000000 then '26'
		when a.concepto='9' and (SUM(a.facturado)+SUM(internas))<=11000000 then '29' 
		when a.concepto='9' and (SUM(a.facturado)+SUM(internas))>11000000 then '31' 
		when a.concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.4 then '23'
		when a.concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.5 then '24'
		when a.concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.6 then '25' 
		when a.concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))>1.6 then '26' else 0 end,
		materiales=sum(materiales),
		base_comision=case when a.concepto<>'9' then convert (int,sum(a.facturado))  else convert (int,(sum(a.facturado)+SUM(internas))) end,
		internas=sum(internas),
		comsion_sin_internas=  case when a.concepto='4' then convert (int,(sum(a.facturado)*0.2)+(sum(materiales)*0.04))
		when a.concepto='5' and o.patio='2' then convert (int,(sum(a.facturado)*0.4)) 
		when a.concepto='5' and o.patio='9' then convert (int,(sum(a.facturado)*0.6)) 
		when a.concepto in ('7','8') then convert (int,(sum(a.facturado)*0.24)) 
		when a.concepto='9' then (CONVERT (int,(sum(a.facturado)+SUM(internas))*case when (SUM(a.facturado)+SUM(internas))<=4000000 then 0.20
		when (SUM(a.facturado)+SUM(internas))<=5000000 then 0.22
		when (SUM(a.facturado)+SUM(internas))<=7000000 then 0.24
		when (SUM(a.facturado)+SUM(internas))<=9000000 then 0.26
		when (SUM(a.facturado)+SUM(internas))<=11000000 then 0.29 else 0.31 end))
		else (CONVERT (int,(sum(a.facturado))*case	when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.4 then 0.23
		when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.5 then 0.24
		when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.6 then 0.25 else 0.26 end))end,
		Comision_a_pagar=  case when a.concepto='4' then convert (int,(sum(a.facturado)*0.2)+sum(internas)+(sum(materiales)*0.04))
		when a.concepto='5' and o.patio='2' then convert (int,(sum(a.facturado)*0.4)+sum(internas)) 
		when a.concepto='5' and o.patio='9' then convert (int,(sum(a.facturado)*0.6)+sum(internas)) 
		when a.concepto in ('7','8') then convert (int,(sum(a.facturado)*0.24)+sum(internas)) 
		when a.concepto='9' then (CONVERT (int,(sum(a.facturado)+SUM(internas))*case when (SUM(a.facturado)+SUM(internas))<=4000000 then 0.20
		when (SUM(a.facturado)+SUM(internas))<=5000000 then 0.22
		when (SUM(a.facturado)+SUM(internas))<=7000000 then 0.24
		when (SUM(a.facturado)+SUM(internas))<=9000000 then 0.26
		when (SUM(a.facturado)+SUM(internas))<=11000000 then 0.29 else 0.31 end)+(CONVERT(int,sum(internas))))
		else (CONVERT (int,(sum(a.facturado))*case	when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.4 then 0.23
		when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.5 then 0.24
		when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.6 then 0.25 else 0.26 end)+(CONVERT(int,sum(internas))))	 end	  
		from v_comisiones_lyp a inner join v_cod_tall_calendario_produccion b on a.ano=b.ano and a.mes=b.mes 
		inner join tall_operarios o on a.operario=o.nit
		inner join terceros_12 t on a.concepto=t.concepto_12
		where CONVERT(DATE,fec) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')
		group by a.operario, a.nombres, a.concepto,o.patio, t.descripcion
		order by nombres";
		//echo $sql;die;
		return $this->db->query($sql);
	}

	public function get_nomina_lyp_nueva($desde, $hasta,$usu="")
	{
		if ($usu == "") {
			$sql = "select a.operario, a.nombres,t.descripcion,sede=i.patio,
			productividad=convert (decimal (18,2),(SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between  CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))*100),
			horas_trabajadas=SUM(tiempo_facturado),
			horas_productivas_mes=(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between  CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')),
			porcentaje_liquidacion=case when a.concepto='4' then '20'
			when a.concepto='5' then '60'
			when a.concepto in ('7','8') then '24'
			when a.concepto='9' and (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=4000000 then '20'
			when a.concepto='9' and (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=5000000 then '22'
			when a.concepto='9' and (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=7000000 then '24'
			when a.concepto='9' and (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=9000000 then '26'
			when a.concepto='9' and (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=11000000 then '29'
			when a.concepto='9' and (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))>11000000 then '31'
			when a.concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between  CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.4 then '23'
			when a.concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between  CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.5 then '24'
			when a.concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between  CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.6 then '25'
			when a.concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between  CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))>1.6 then '26' else 0 end,
			materiales=sum(materiales),
			base_comision_mo=case when a.concepto<>'9' then convert (int,(sum(a.facturado)+SUM(fact_pulido)))  else convert (int,(sum(a.facturado)+SUM(internas)+SUM(fact_pulido))) end,
			internas=sum(internas),
			
			comsion_sin_internas_mo=  case when a.concepto='4' then convert (int,((sum(a.facturado)++SUM(fact_pulido))*0.2)+(sum(materiales)*0.04))
			when a.concepto='5' then convert (int,((sum(a.facturado)+SUM(fact_pulido))*0.6))
			when a.concepto in ('7','8') then convert (int,((sum(a.facturado)+SUM(fact_pulido))*0.24))
			when a.concepto='9' and operario<>5165002 then (CONVERT (int,(sum(a.facturado)+SUM(internas)+SUM(fact_pulido))*case when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=4000000 then 0.20
			when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=5000000 then 0.22
			when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=7000000 then 0.24
			when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=9000000 then 0.26
			when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=11000000 then 0.29 else 0.31 end))
			
			when operario=5165002 then ((CONVERT (int,(sum(a.facturado)+SUM(internas))*case when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=4000000 then 0.20
			when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=5000000 then 0.22
			when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=7000000 then 0.24
			when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=9000000 then 0.26
			when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=11000000 then 0.29 else 0.31 end))+(CONVERT (int,(sum(fact_pulido))*0.2))+(sum(materiales)*0.04))
			
			when a.operario<>1093140589 then (CONVERT (int,(sum(a.facturado+a.fact_pulido))*case	when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.4 then 0.23
			when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.5 then 0.24
			when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.6 then 0.25 else 0.26 end))
			
			when a.operario=1093140589 then ((CONVERT (int,(sum(a.facturado))*case	when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.4 then 0.23
			when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.5 then 0.24
			when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.6 then 0.25 else 0.26 end))+(CONVERT (int,(sum(fact_pulido))*0.6)))
			else 0 end,

			Base_Rptos=case when a.operario = 7214506 then 0 when o.patio=2 then convert(int,(select SUM(valor_niif)*-1 from movimiento where centro in (33,45) and tipo<>'Z1' and (cuenta like '413506%' or cuenta like '417520%' or cuenta like '53053580%') and YEAR(CONVERT(DATE,fec)) = YEAR(CONVERT(DATE,'".$desde."'))AND MONTH(CONVERT(DATE,fec)) = MONTH(CONVERT(DATE,'".$desde."')))*0.01)
			else convert(int,(select SUM(valor_niif)*-1 from movimiento where centro in (31,46) and tipo<>'Z1' and (cuenta like '413506%' or cuenta like '417520%' or cuenta like '53053580%') and YEAR(CONVERT(DATE,fec)) = YEAR(CONVERT(DATE,'".$desde."')) AND MONTH(CONVERT(DATE,fec)) = MONTH(CONVERT(DATE,'".$desde."')))*0.02) end,
			
			porc_fac_total= case when a.operario = 7214506 then 0 else convert (decimal (18,2),(SUM(facturado)+SUM(fact_pulido))/(case when o.patio=2 and a.operario != 7214506 then convert(decimal(18,2),(select (SUM(facturado)+SUM(fact_pulido)) from v_comisiones_lyp x inner join tall_operarios y on x.operario=y.nit where y.patio=2 and x.operario != 7214506 and CONVERT(DATE,x.fec) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))
			else convert(decimal(18,2),(select SUM(facturado+fact_pulido) from v_comisiones_lyp x inner join tall_operarios y on x.operario=y.nit where y.patio=9 and CONVERT(DATE,x.fec) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."'))) end)*100) end,
			
			isnull(bn.bono_nps,0) as Bono_NPS
			from v_comisiones_lyp a inner join v_cod_tall_calendario_produccion b on a.ano=b.ano and a.mes=b.mes
			  inner join tall_operarios o on a.operario=o.nit
			  inner join terceros_12 t on a.concepto=t.concepto_12
			  inner join tall_operarios_intranet i on a.operario=i.nit
			  inner join postv_bono_nps_tecnicos bn on a.operario=bn.nit and bn.ano=YEAR(CONVERT(DATE,'".$desde."')) and bn.mes=month(CONVERT(DATE,'".$desde."'))
			where CONVERT(DATE,fec) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."') 
			group by a.operario, a.nombres, a.concepto,i.patio, t.descripcion,o.patio,bn.bono_nps
			order by i.patio,nombres";
			
		}else{
			$sql = "select a.operario, a.nombres,t.descripcion,sede=i.patio,
			productividad=convert (decimal (18,2),(SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between  CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))*100),
			horas_trabajadas=SUM(tiempo_facturado),
			horas_productivas_mes=(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between  CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')),
			porcentaje_liquidacion=case when a.concepto='4' then '20'
			when a.concepto='5' then '60'
			when a.concepto in ('7','8') then '24'
			when a.concepto='9' and (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=4000000 then '20'
			when a.concepto='9' and (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=5000000 then '22'
			when a.concepto='9' and (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=7000000 then '24'
			when a.concepto='9' and (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=9000000 then '26'
			when a.concepto='9' and (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=11000000 then '29'
			when a.concepto='9' and (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))>11000000 then '31'
			when a.concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between  CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.4 then '23'
			when a.concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between  CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.5 then '24'
			when a.concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between  CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.6 then '25'
			when a.concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between  CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))>1.6 then '26' else 0 end,
			materiales=sum(materiales),
			base_comision_mo=case when a.concepto<>'9' then convert (int,(sum(a.facturado)+SUM(fact_pulido)))  else convert (int,(sum(a.facturado)+SUM(internas)+SUM(fact_pulido))) end,
			internas=sum(internas),
			
			comsion_sin_internas_mo=  case when a.concepto='4' then convert (int,((sum(a.facturado)++SUM(fact_pulido))*0.2)+(sum(materiales)*0.04))
			when a.concepto='5' then convert (int,((sum(a.facturado)+SUM(fact_pulido))*0.6))
			when a.concepto in ('7','8') then convert (int,((sum(a.facturado)+SUM(fact_pulido))*0.24))
			when a.concepto='9' and operario<>5165002 then (CONVERT (int,(sum(a.facturado)+SUM(internas)+SUM(fact_pulido))*case when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=4000000 then 0.20
			when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=5000000 then 0.22
			when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=7000000 then 0.24
			when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=9000000 then 0.26
			when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=11000000 then 0.29 else 0.31 end))
			
			when operario=5165002 then ((CONVERT (int,(sum(a.facturado)+SUM(internas))*case when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=4000000 then 0.20
			when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=5000000 then 0.22
			when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=7000000 then 0.24
			when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=9000000 then 0.26
			when (SUM(a.facturado)+SUM(internas)+SUM(fact_pulido))<=11000000 then 0.29 else 0.31 end))+(CONVERT (int,(sum(fact_pulido))*0.2))+(sum(materiales)*0.04))
			
			when a.operario<>1093140589 then (CONVERT (int,(sum(a.facturado+a.fact_pulido))*case	when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.4 then 0.23
			when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.5 then 0.24
			when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.6 then 0.25 else 0.26 end))
			
			when a.operario=1093140589 then ((CONVERT (int,(sum(a.facturado))*case	when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.4 then 0.23
			when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.5 then 0.24
			when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.6 then 0.25 else 0.26 end))+(CONVERT (int,(sum(fact_pulido))*0.6)))
			else 0 end,

			Base_Rptos=case when a.operario = 7214506 then 0 when o.patio=2 then convert(int,(select SUM(valor_niif)*-1 from movimiento where centro in (33,45) and tipo<>'Z1' and (cuenta like '413506%' or cuenta like '417520%' or cuenta like '53053580%') and YEAR(CONVERT(DATE,fec)) = YEAR(CONVERT(DATE,'".$desde."'))AND MONTH(CONVERT(DATE,fec)) = MONTH(CONVERT(DATE,'".$desde."')))*0.01)
			else convert(int,(select SUM(valor_niif)*-1 from movimiento where centro in (31,46) and tipo<>'Z1' and (cuenta like '413506%' or cuenta like '417520%' or cuenta like '53053580%') and YEAR(CONVERT(DATE,fec)) = YEAR(CONVERT(DATE,'".$desde."')) AND MONTH(CONVERT(DATE,fec)) = MONTH(CONVERT(DATE,'".$desde."')))*0.02) end,
			
			porc_fac_total= case when a.operario = 7214506 then 0 else convert (decimal (18,2),(SUM(facturado)+SUM(fact_pulido))/(case when o.patio=2 and a.operario != 7214506 then convert(decimal(18,2),(select (SUM(facturado)+SUM(fact_pulido)) from v_comisiones_lyp x inner join tall_operarios y on x.operario=y.nit where y.patio=2 and x.operario != 7214506 and CONVERT(DATE,x.fec) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))
			else convert(decimal(18,2),(select SUM(facturado+fact_pulido) from v_comisiones_lyp x inner join tall_operarios y on x.operario=y.nit where y.patio=9 and CONVERT(DATE,x.fec) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."'))) end)*100) end,
			
			isnull(bn.bono_nps,0) as Bono_NPS
			from v_comisiones_lyp a inner join v_cod_tall_calendario_produccion b on a.ano=b.ano and a.mes=b.mes
			  inner join tall_operarios o on a.operario=o.nit
			  inner join terceros_12 t on a.concepto=t.concepto_12
			  inner join tall_operarios_intranet i on a.operario=i.nit
			  inner join postv_bono_nps_tecnicos bn on a.operario=bn.nit and bn.ano=YEAR(CONVERT(DATE,'".$desde."')) and bn.mes=month(CONVERT(DATE,'".$desde."'))
			where CONVERT(DATE,fec) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."') AND a.operario = '".$usu."'
			group by a.operario, a.nombres, a.concepto,i.patio, t.descripcion,o.patio,bn.bono_nps
			order by i.patio,nombres";

		}
		//echo $sql;die;
		return $this->db->query($sql);
	}

	/*METODO PARA CALCULAR EL TO DE REP POR SEDE
	*GIRON = 1
	*BOCONO = 2
	*/
	public function get_to_rep_sedes($desde,$hasta,$sede)
	{
		if ($sede == 1) {
			$sql = "select Base_Rptos=SUM(valor_niif)*-1 from movimiento 
				where centro in (33,45) and (cuenta like '413506%' or cuenta like '417520%' or cuenta like '53053580%') and tipo <> 'Z1'
				and YEAR(CONVERT(DATE,fec)) = YEAR(CONVERT(DATE,'".$desde."'))AND MONTH(CONVERT(DATE,fec)) = MONTH(CONVERT(DATE,'".$desde."'))";
		}else if ($sede == 2) {
			$sql = "select Base_Rptos=SUM(valor_niif)*-1 from movimiento where centro in (31,46) and tipo <> 'Z1'
					and (cuenta like '413506%' or cuenta like '417520%' or cuenta like '53053580%') and YEAR(CONVERT(DATE,fec)) = YEAR(CONVERT(DATE,'".$desde."')) 
					AND MONTH(CONVERT(DATE,fec)) = MONTH(CONVERT(DATE,'".$desde."'))";
		}
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_nomina_lyp_by_nit($desde, $hasta, $nit)
	{
		$sql = "select a.operario, a.nombres,t.descripcion,
		productividad=convert (decimal (10,2),(SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))*100),
		horas_trabajadas=SUM(tiempo_facturado), 
		horas_productivas_mes=(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')),
		porcentaje_liquidacion=case when a.concepto='4' then '20' 
		when a.concepto='5' and o.patio='2' then '40' 
		when a.concepto='5' and o.patio='9' then '60'
		when a.concepto in ('7','8') then '24'
		when a.concepto='9' and (SUM(a.facturado)+SUM(internas))<=4000000 then '20'
		when a.concepto='9' and (SUM(a.facturado)+SUM(internas))<=5000000 then '22'
		when a.concepto='9' and (SUM(a.facturado)+SUM(internas))<=7000000 then '24'
		when a.concepto='9' and (SUM(a.facturado)+SUM(internas))<=9000000 then '26'
		when a.concepto='9' and (SUM(a.facturado)+SUM(internas))<=11000000 then '29' 
		when a.concepto='9' and (SUM(a.facturado)+SUM(internas))>11000000 then '31' 
		when a.concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.4 then '23'
		when a.concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.5 then '24'
		when a.concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.6 then '25' 
		when a.concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))>1.6 then '26' else 0 end,
		materiales=sum(materiales),
		base_comision=case when a.concepto<>'9' then convert (int,sum(a.facturado))  else convert (int,(sum(a.facturado)+SUM(internas))) end,
		internas=sum(internas),
		comsion_sin_internas=  case when a.concepto='4' then convert (int,(sum(a.facturado)*0.2)+(sum(materiales)*0.04))
		when a.concepto='5' and o.patio='2' then convert (int,(sum(a.facturado)*0.4)) 
		when a.concepto='5' and o.patio='9' then convert (int,(sum(a.facturado)*0.6)) 
		when a.concepto in ('7','8') then convert (int,(sum(a.facturado)*0.24)) 
		when a.concepto='9' then (CONVERT (int,(sum(a.facturado)+SUM(internas))*case when (SUM(a.facturado)+SUM(internas))<=4000000 then 0.20
		when (SUM(a.facturado)+SUM(internas))<=5000000 then 0.22
		when (SUM(a.facturado)+SUM(internas))<=7000000 then 0.24
		when (SUM(a.facturado)+SUM(internas))<=9000000 then 0.26
		when (SUM(a.facturado)+SUM(internas))<=11000000 then 0.29 else 0.31 end))
		else (CONVERT (int,(sum(a.facturado))*case	when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.4 then 0.23
		when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.5 then 0.24
		when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.6 then 0.25 else 0.26 end))end,
		Comision_a_pagar=  case when a.concepto='4' then convert (int,(sum(a.facturado)*0.2)+sum(internas)+(sum(materiales)*0.04))
		when a.concepto='5' and o.patio='2' then convert (int,(sum(a.facturado)*0.4)+sum(internas)) 
		when a.concepto='5' and o.patio='9' then convert (int,(sum(a.facturado)*0.6)+sum(internas)) 
		when a.concepto in ('7','8') then convert (int,(sum(a.facturado)*0.24)+sum(internas)) 
		when a.concepto='9' then (CONVERT (int,(sum(a.facturado)+SUM(internas))*case when (SUM(a.facturado)+SUM(internas))<=4000000 then 0.20
		when (SUM(a.facturado)+SUM(internas))<=5000000 then 0.22
		when (SUM(a.facturado)+SUM(internas))<=7000000 then 0.24
		when (SUM(a.facturado)+SUM(internas))<=9000000 then 0.26
		when (SUM(a.facturado)+SUM(internas))<=11000000 then 0.29 else 0.31 end)+(CONVERT(int,sum(internas))))
		else (CONVERT (int,(sum(a.facturado))*case	when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.4 then 0.23
		when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.5 then 0.24
		when (SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario where CONVERT(DATE,fecha) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')))<=1.6 then 0.25 else 0.26 end)+(CONVERT(int,sum(internas))))	 end	  
		from v_comisiones_lyp a inner join v_cod_tall_calendario_produccion b on a.ano=b.ano and a.mes=b.mes 
		inner join tall_operarios o on a.operario=o.nit
		inner join terceros_12 t on a.concepto=t.concepto_12
		where CONVERT(DATE,fec) between CONVERT(DATE,'" . $desde . "') and CONVERT(DATE,'" . $hasta . "')
		and a.operario = '" . $nit . "'
		group by a.operario, a.nombres, a.concepto,o.patio, t.descripcion
		order by nombres";
		return $this->db->query($sql);
	}

	public function get_detalle_nomina_lyp_nit($desde,$hasta,$nit)
	{
		$sql = "select a.operario, a.nombres,t.descripcion,a.tipo, a.numero,dl.numero as numero_orden, r.placa,f.descripcion as vehiculo,
		pro.productividad,(pro.porcentaje_liquidacion*100) as porc_liquida,
		a.tiempo_facturado,
		base_comision=case when a.concepto<>'9' then convert (int,a.facturado+a.fact_pulido) else convert (int,(a.facturado+internas+a.fact_pulido)) end,
		materiales,internas,
		Comision_a_pagar=  case when a.concepto='4' then convert (int,(((a.facturado+a.fact_pulido)*0.2)+internas+(materiales*0.04)))
		when a.concepto='5' and o.patio='2' then convert (int,((a.facturado+a.fact_pulido)*0.4)+internas)
		when a.concepto='5' and o.patio='9' then convert (int,((a.facturado+a.fact_pulido)*0.6)+internas)
		when a.concepto in ('7','8') then convert (int,((a.facturado+a.fact_pulido)*0.24)+internas)
		when a.concepto='9' and a.operario<>5165002 and convert (int,(a.facturado+internas+a.fact_pulido))=CONVERT(int,internas) then CONVERT(int,internas)
		when a.concepto='9' and a.operario<>5165002 and convert (int,(a.facturado+internas+a.fact_pulido))<>CONVERT(int,internas) then (CONVERT (int,(a.facturado+internas+a.fact_pulido)*porcentaje_liquidacion))+CONVERT(int,internas)
		when a.concepto='9' and a.operario=5165002 and convert (int,(a.facturado+internas+a.fact_pulido+materiales))=CONVERT(int,internas) then CONVERT(int,internas)
		when a.concepto='9' and a.operario=5165002 and convert (int,(a.facturado+internas+a.fact_pulido+materiales))<>CONVERT(int,internas)
			then (CONVERT (int,(a.facturado+internas)*porcentaje_liquidacion))+CONVERT(int,internas)+(CONVERT(int,fact_pulido)*0.2)+(CONVERT(int,materiales)*0.04)
		when a.operario=1093140589 then ((CONVERT (int,a.facturado)*case when pro.productividad<=140 then 0.23
		when pro.productividad<=150 then 0.24
		when pro.productividad<=160 then 0.25 else 0.26 end)+(CONVERT(int,internas))+(CONVERT (int,(sum(fact_pulido))*0.6)))		
		when a.operario<>1093140589 and a.concepto not in (5,7,8,9) then (CONVERT (int,(a.facturado+a.fact_pulido))*case when pro.productividad<=140 then 0.23
		when pro.productividad<=150 then 0.24
		when pro.productividad<=160 then 0.25 else 0.26 end)+(CONVERT(int,internas))else 0 end  
		
FROM v_comisiones_lyp a inner join	 
		 (select operario,
		productividad=convert (decimal (10,2),(SUM(tiempo_facturado)/(select sum(horas_produccion) from v_cod_tall_calendario 
		where fecha between  CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))*100),
		porcentaje_liquidacion=case when concepto='4' then 0.2
		when concepto='5' and o.patio='2' then 0.4
		when concepto='5' and o.patio='9' then 0.6
		when concepto in ('7','8') then 0.24
		when concepto='9' and (SUM(facturado)+SUM(internas)+SUM(fact_pulido))<=4000000 then 0.20
		when concepto='9' and (SUM(facturado)+SUM(internas)+SUM(fact_pulido))<=5000000 then 0.22
		when concepto='9' and (SUM(facturado)+SUM(internas)+SUM(fact_pulido))<=7000000 then 0.24
		when concepto='9' and (SUM(facturado)+SUM(internas)+SUM(fact_pulido))<=9000000 then 0.26
		when concepto='9' and (SUM(facturado)+SUM(internas)+SUM(fact_pulido))<=11000000 then 0.29
		when concepto='9' and (SUM(facturado)+SUM(internas)+SUM(fact_pulido))>11000000 then 0.31
		when concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) 
			from v_cod_tall_calendario where fecha between  CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.40 then 0.23
		when concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) 
			from v_cod_tall_calendario where fecha between  CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.50 then 0.24
		when concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) 
			from v_cod_tall_calendario where fecha between  CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))<=1.60 then 0.25
		when concepto='6' and (SUM(tiempo_facturado)/(select sum(horas_produccion) 
			from v_cod_tall_calendario where fecha between  CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')))>1.60 then 0.26 
		else 0 end
		from v_comisiones_lyp a inner join tall_operarios o on a.operario=o.nit
		where CONVERT(DATE,fec) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."')
		group by operario,concepto,o.patio
		)pro
		on a.operario=pro.operario
			inner join terceros_12 t on a.concepto=t.concepto_12
			inner join tall_operarios o on a.operario=o.nit
		  inner join tall_encabeza_orden dl on a.numero_orden=dl.numero
		  inner join v_vh_vehiculos r on dl.serie=r.codigo
		  left join vh_modelo m on r.modelo=m.modelo
		  left join vh_familias f on m.familia=f.familia
		 where CONVERT(DATE,a.fec) between CONVERT(DATE,'".$desde."') and CONVERT(DATE,'".$hasta."') and a.operario='".$nit."'
		group by a.operario, a.nombres, a.concepto,o.patio, t.descripcion,pro.productividad,r.placa,dl.numero,a.tipo,a.numero,a.tiempo_facturado
		, porcentaje_liquidacion,materiales,internas,facturado,f.descripcion,fact_pulido
		order by nombres
		";
		/*Update->05/01/2023 */

		return $this->db->query($sql);
	}


	/*
	 * Metodo creado para traer datos
	 * Andres Gomez 16-09-21
	 */

	public function traer_datos_nominalyt($mes, $ano)

	{
		if ($this->session->userdata('perfil') == 20 || $this->session->userdata('user') == 91525308 || $this->session->userdata('perfil') == 1) {

			$traer = "SELECT d.tipo, d.numero,d.sw, d.fec, d.nit,horas= convert (decimal (10,2),case when sw=1 then d.tiempo else d.tiempo*-1 end), d.clase_operacion,
			 d.bodega, d.deducible,tt.concepto, t.nit as nit_ase, t.nombres as asesor, tc.tipo, tc.nombres as cliente, t12.concepto_12, t12.descripcion,
			 comision=convert (decimal (10,2),case when (tc.tipo='E' OR tc.tipo='S') and d.bodega in (9,14,22,21) THEN d.tiempo*455* case when d.sw='1' then 1 else -1 end 
			 else d.tiempo*3121* case when d.sw='1' then 1 else -1 end end)
		 
		 FROM
			v_cod_tall_doclin_documenlin d INNER JOIN terceros t ON d.vendedor_ot = t.nit INNER JOIN v_cod_terceros_tipo_cliente tc ON d.nit = tc.nit INNER JOIN terceros cl ON
				 d.nit = cl.nit INNER JOIN tall_tempario tt ON d.operacion = tt.operacion INNER JOIN terceros_12 t12 ON t.concepto_12 = t12.concepto_12
		 WHERE
			 t12.concepto_12 = '11' AND (tc.tipo = 'S' OR tc.tipo = 'E') AND MONTH(d.fec) = $mes AND YEAR(d.fec) = $ano AND d.clase_operacion = 'T' AND d.deducible = 'N' AND tt.concepto not in ('68','67','66','53','65')
		 ORDER BY
			 d.numero ASC";

			return $this->db->query($traer);
		}
	}


	/*metodo para ver detalle  */

	public function fistrarNominalyp($mes, $ano)
	{
		if ($this->session->userdata('perfil') == 20 || $this->session->userdata('user') == 91525308 || $this->session->userdata('perfil') == 1)  {
			$consulta = " SELECT d.tipo as dtl, d.numero, d.numero_orden, d.sw, d.fec, d.nit, horas= convert (decimal (10,2),case when sw=1 then d.tiempo else d.tiempo*-1 end), 
			d.clase_operacion, d.bodega, d.deducible,tt.concepto, t.nit, t.nombres as nomb, tc.tipo, tc.nombres, t12.concepto_12, t12.descripcion,
			comision=convert (decimal (10,2),case when (tc.tipo='E' OR tc.tipo='S') and d.bodega in (9,14,22,21) THEN d.tiempo*455* case when d.sw='1' then 1 else -1 end 
	else d.tiempo*3121* case when d.sw='1' then 1 else -1 end end)
		
		FROM v_cod_tall_doclin_documenlin d 
	   
	   INNER JOIN terceros t ON d.vendedor_ot = t.nit
	   INNER JOIN v_cod_terceros_tipo_cliente tc ON d.nit = tc.nit
	   INNER JOIN terceros cl ON d.nit = cl.nit
	   INNER JOIN tall_tempario tt ON d.operacion = tt.operacion
	   INNER JOIN terceros_12 t12 ON t.concepto_12 = t12.concepto_12
	   
	   WHERE  t12.concepto_12 = '11' AND (tc.tipo = 'S' OR tc.tipo = 'E') AND MONTH(d.fec) = $mes AND YEAR(d.fec) = $ano AND d.clase_operacion = 'T' AND d.deducible = 'N' AND tt.concepto not in ('68','67','66','53','65')
	   ORDER BY d.numero ASC ";
			$respuesta = $this->db->query($consulta);
			if ($respuesta) {
				return $respuesta;
			} else {
				return false;
			}
		}
	}

	public function get_inf_nomina_tec($mes,$anio,$usu="")
	{
		if ($usu == "") {
			$sql = "SELECT DISTINCT t.nit, v.tecnico,
			patio = CASE WHEN t.patio=1 THEN 'GASOLINA GIRON'
			WHEN  t.patio = 3 THEN 'DIESEL GIRON'
			WHEN  t.patio in (6, 5) THEN 'BARRANCA'
			WHEN  t.patio = 4 THEN 'ROSITA'
			else 'BOCONO' end,
			cargo= case when o.escalafon=1 then 'TECNICO 1'
			when o.escalafon=2 then 'TECNICO 2'
			when o.escalafon=3 then 'TECNICO 3'
			when o.escalafon=4 then 'ELECTRICISTA 1'
			when o.escalafon=5 then 'ELECTRICISTA 2'
			when o.escalafon=6 then 'TECNICO 1+'
			when o.escalafon=8 then 'TECNICO 1master'
			when o.escalafon=7 then 'ALINEADOR' end,
			convert (int,SUM(venta_rptos)) as venta_repuestos,					
			convert (int,SUM(venta_mano_obra)+(isnull(internas,0)*18824)+(isnull(intalista,0)*18824)+(isnull(alineacion,0)*isnull(tf.valor_hora,0))) as venta_mano_obra,					
			comision_repuestos=convert (int,SUM(venta_rptos)*c.porcen_rep) ,					
			comision_mano_obra=convert (int,(SUM(venta_mano_obra)+(isnull(internas,0)*18824)+(isnull(intalista,0)*18824)+(isnull(alineacion,0)*isnull(tf.valor_hora,0)))*porcen_mo),					
			segunda_entrega=CONVERT(int,(isnull(e.seg_entrega,0)*10000)),
			Instalacion_accesorios=CONVERT(int,(isnull(acc.accesorios,0)*8200)),
			internas= CONVERT(int,(isnull(hc.colision,0)+isnull(intcolision,0)+isnull(intventas,0)+isnull(intpagas,0))*8200),
			bono_NPS=b.bono_nps					
			FROM v_informe_tecnico v inner join tall_operarios_intranet t
			on v.operario=t.nit
			inner join postv_bono_nps_tecnicos b on v.operario=b.nit and v.Año=b.ano and v.Mes=b.mes					
			left join (select operario, SUM(horas) as internas from v_horas_internas a inner join tall_tempario b
			on a.operacion=b.operacion
			where Año=".$anio." and Mes=".$mes." and bodega not in (9,14,21,22) and cliente not in (92,100,106) and (b.descripcion not like 'segunda entrega%' and a.operacion not in ('PromoAlineacion','30011INSTALACION'))
			group by operario)i
			on v.operario=i.operario
			left join (select operario, SUM(horas) as intcolision from v_horas_internas a inner join tall_tempario b
			on a.operacion=b.operacion
			where Año=".$anio." and Mes=".$mes." and bodega in (9,14,21,22) and (b.descripcion not like 'segunda entrega%' and a.operacion not in ('PromoAlineacion','30011INSTALACION'))
			group by operario)ic
			on v.operario=ic.operario
			left join (select operario, SUM(horas) as intpagas from v_horas_internas a inner join tall_tempario b
			on a.operacion=b.operacion
			where Año=".$anio." and Mes=".$mes." and bodega not in (9,14,21,22) and cliente in (92,106) and (b.descripcion not like 'segunda entrega%' and a.operacion not in ('PromoAlineacion','30011INSTALACION'))
			group by operario)ip
			on v.operario=ip.operario
			left join (select operario, SUM(horas) as intventas from v_horas_internas a inner join tall_tempario b
			on a.operacion=b.operacion
			where Año=".$anio." and Mes=".$mes." and bodega not in (9,14,21,22,11) and cliente=100 and (b.descripcion not like 'segunda entrega%' and a.operacion not in ('PromoAlineacion','30011INSTALACION'))
			group by operario)iv
			on v.operario=iv.operario
			left join (select operario, SUM(horas) as intalista from v_horas_internas a inner join tall_tempario b
			on a.operacion=b.operacion
			where Año=".$anio." and Mes=".$mes." and bodega=11 and cliente=100 and (b.descripcion not like 'segunda entrega%' and a.operacion not in ('PromoAlineacion','30011INSTALACION'))
			group by operario)ia
			on v.operario=ia.operario
			left join (select operario, count(distinct numero) as seg_entrega from v_horas_internas a inner join tall_tempario b
			on a.operacion=b.operacion
			where Año=".$anio." and Mes=".$mes." and b.descripcion like 'segunda entrega%'
			group by operario)e
			on v.operario=e.operario
			left join (select operario, SUM(horas) as accesorios from v_horas_internas 
			where Año=".$anio." and Mes=".$mes." and operacion='30011INSTALACION'
			group by operario)acc
			on v.operario=acc.operario
			left join (select bodega,operario, count(distinct numero) as alineacion from v_horas_internas
			where Año=".$anio." and Mes=".$mes." and operacion in ('PromoAlineacion')
			group by operario,bodega)al
			on v.operario=al.operario
			left join (select operario, SUM(horas) as colision from v_informe_tecnico where Año=".$anio." and Mes=".$mes."and bodega in (9,14,21,22)
			group by operario) hc
			on v.operario=hc.operario
			left join tall_tarifas_taller tf on al.bodega=tf.bodega
			inner join tall_operarios o on v.operario=o.nit
			inner join postv_comisiones_rep c on o.patio=c.patio and o.escalafon=c.escalafon
			where t.patio in (1,3,4,5,6,7,8) and v.Año=".$anio." and v.Mes=".$mes." and v.bodega not in (9,14,21,22)
			group by t.nit, v.tecnico,t.patio,i.operario,i.internas,o.escalafon,c.porcen_rep,c.porcen_mo,b.bono_nps,e.seg_entrega
			,al.alineacion,tf.valor_hora,acc.accesorios,colision, intcolision,intalista,intpagas,intventas
			order by patio,v.tecnico";
		}else{
			$sql = "SELECT DISTINCT t.nit, v.tecnico,
			patio = CASE WHEN t.patio=1 THEN 'GASOLINA GIRON'
			WHEN  t.patio = 3 THEN 'DIESEL GIRON'
			WHEN  t.patio in (6, 5) THEN 'BARRANCA'
			WHEN  t.patio = 4 THEN 'ROSITA'
			else 'BOCONO' end,
			cargo= case when o.escalafon=1 then 'TECNICO 1'
			when o.escalafon=2 then 'TECNICO 2'
			when o.escalafon=3 then 'TECNICO 3'
			when o.escalafon=4 then 'ELECTRICISTA 1'
			when o.escalafon=5 then 'ELECTRICISTA 2'
			when o.escalafon=6 then 'TECNICO 1+'
			when o.escalafon=8 then 'TECNICO 1master'
			when o.escalafon=7 then 'ALINEADOR' end,
			convert (int,SUM(venta_rptos)) as venta_repuestos,					
			convert (int,SUM(venta_mano_obra)+(isnull(internas,0)*18824)+(isnull(intalista,0)*18824)+(isnull(alineacion,0)*isnull(tf.valor_hora,0))) as venta_mano_obra,					
			comision_repuestos=convert (int,SUM(venta_rptos)*c.porcen_rep) ,					
			comision_mano_obra=convert (int,(SUM(venta_mano_obra)+(isnull(internas,0)*18824)+(isnull(intalista,0)*18824)+(isnull(alineacion,0)*isnull(tf.valor_hora,0)))*porcen_mo),					
			segunda_entrega=CONVERT(int,(isnull(e.seg_entrega,0)*10000)),
			Instalacion_accesorios=CONVERT(int,(isnull(acc.accesorios,0)*8200)),
			internas= CONVERT(int,(isnull(hc.colision,0)+isnull(intcolision,0)+isnull(intventas,0)+isnull(intpagas,0))*8200),
			bono_NPS=b.bono_nps					
     		FROM v_informe_tecnico v inner join tall_operarios_intranet t
			on v.operario=t.nit
			inner join postv_bono_nps_tecnicos b on v.operario=b.nit and v.Año=b.ano and v.Mes=b.mes					
			left join (select operario, SUM(horas) as internas from v_horas_internas a inner join tall_tempario b
			on a.operacion=b.operacion
			where Año=".$anio." and Mes=".$mes." and bodega not in (9,14,21,22) and cliente not in (92,100,106) and (b.descripcion not like 'segunda entrega%' and a.operacion not in ('PromoAlineacion','30011INSTALACION'))
			group by operario)i
			on v.operario=i.operario
			left join (select operario, SUM(horas) as intcolision from v_horas_internas a inner join tall_tempario b
			on a.operacion=b.operacion
			where Año=".$anio." and Mes=".$mes." and bodega in (9,14,21,22) and (b.descripcion not like 'segunda entrega%' and a.operacion not in ('PromoAlineacion','30011INSTALACION'))
			group by operario)ic
			on v.operario=ic.operario
			left join (select operario, SUM(horas) as intpagas from v_horas_internas a inner join tall_tempario b
			on a.operacion=b.operacion
			where Año=".$anio." and Mes=".$mes." and bodega not in (9,14,21,22) and cliente in (92,106) and (b.descripcion not like 'segunda entrega%' and a.operacion not in ('PromoAlineacion','30011INSTALACION'))
			group by operario)ip
			on v.operario=ip.operario
			left join (select operario, SUM(horas) as intventas from v_horas_internas a inner join tall_tempario b
			on a.operacion=b.operacion
			where Año=".$anio." and Mes=".$mes." and bodega not in (9,14,21,22,11) and cliente=100 and (b.descripcion not like 'segunda entrega%' and a.operacion not in ('PromoAlineacion','30011INSTALACION'))
			group by operario)iv
			on v.operario=iv.operario
			left join (select operario, SUM(horas) as intalista from v_horas_internas a inner join tall_tempario b
			on a.operacion=b.operacion
			where Año=".$anio." and Mes=".$mes." and bodega=11 and cliente=100 and (b.descripcion not like 'segunda entrega%' and a.operacion not in ('PromoAlineacion','30011INSTALACION'))
			group by operario)ia
			on v.operario=ia.operario
			left join (select operario, count(distinct numero) as seg_entrega from v_horas_internas a inner join tall_tempario b
			on a.operacion=b.operacion
			where Año=".$anio." and Mes=".$mes." and b.descripcion like 'segunda entrega%'
			group by operario)e
			on v.operario=e.operario
			left join (select operario, SUM(horas) as accesorios from v_horas_internas 
			where Año=".$anio." and Mes=".$mes." and operacion='30011INSTALACION'
			group by operario)acc
			on v.operario=acc.operario
			left join (select bodega,operario, count(distinct numero) as alineacion from v_horas_internas
			where Año=".$anio." and Mes=".$mes." and operacion in ('PromoAlineacion')
			group by operario,bodega)al
			on v.operario=al.operario
			left join (select operario, SUM(horas) as colision from v_informe_tecnico where Año=".$anio." and Mes=".$mes."and bodega in (9,14,21,22)
			group by operario) hc
			on v.operario=hc.operario
			left join tall_tarifas_taller tf on al.bodega=tf.bodega
			inner join tall_operarios o on v.operario=o.nit
			inner join postv_comisiones_rep c on o.patio=c.patio and o.escalafon=c.escalafon
			where t.patio in (1,3,4,5,6,7,8) and v.Año=".$anio." and v.Mes=".$mes." and v.bodega not in (9,14,21,22) and t.nit = ".$usu."
			group by t.nit, v.tecnico,t.patio,i.operario,i.internas,o.escalafon,c.porcen_rep,c.porcen_mo,b.bono_nps,e.seg_entrega
			,al.alineacion,tf.valor_hora,acc.accesorios,colision, intcolision,intalista,intpagas,intventas
			order by patio,v.tecnico";
		}
		
				//echo $sql;die;
		return $this->db->query($sql);
	}

	public function get_detalle_nomina_tec($mes,$anio,$nit)	
	{
		$sql = "select cc.nit, cc.tecnico,cc.operacion,
		nombre_operacion= case when d.clase_operacion='R' then r.descripcion 
						  when d.clase_operacion<>'R' and d.texto is null then tt.descripcion else d.texto end,
		cc.tipo,cc.numero,cc.numero_orden,cc.placa,cc.descripcion as vh, cc.venta_repuestos,cc.venta_mano_obra,cc.segunda_entrega,cc.Instalacion_accesorios,cc.internas
		 from 
		
		(
		select t.nit, v.tecnico,v.operacion,v.seq_orden,v.tipo, v.numero,v.numero_orden,vh.placa,vh.descripcion,
				case when v.bodega in (9,14,21,22) then 0 
				when v.tipo in ('IT','DIT','IK','DIK','WI','DIW','IL','DIL','IR','DIR') then 0 
				else convert (int,SUM(venta_rptos)) end as venta_repuestos,
				case when v.bodega in (9,14,21,22) then 0 else convert (int,SUM(venta_mano_obra)+(isnull(alineacion,0))+(isnull(internas,0)*18824)+(isnull(intalista,0)*18824)) end as venta_mano_obra,
				segunda_entrega=CONVERT(int,(isnull(en.seg_entrega,0)*10000)),
				Instalacion_accesorios=CONVERT(int,(isnull(acc.accesorios,0)*8200)),
				internas=CONVERT(int,SUM(isnull(ic.intcolision,0)+isnull(hc.colision,0)+isnull(ip.intpagas,0)+isnull(iv.intventas,0))*8200)	
				
				from tall_operarios_intranet t 
				LEFT JOIN  (select Año, Mes, bodega, numero_orden,tipo,numero,operario,tecnico,operacion,seq,seq_orden, venta_rptos, Venta_mano_obra 
				from v_factu_tecnico where Año=".$anio." and mes=".$mes.")v 
				on v.operario=t.nit
				
				LEFT  JOIN (select a.operario,a.operacion,a.seq,tipo,numero, SUM(horas) as internas from v_horas_internas a inner join tall_tempario b
				on a.operacion=b.operacion
				where Año=".$anio." and mes=".$mes." and bodega not in (9,14,21,22) and cliente not in (92,100,106) and (b.descripcion not like 'segunda entrega%' and a.operacion not in ('PromoAlineacion','30011INSTALACION'))
				group by operario,tipo,numero,a.operacion,a.seq)i
				on v.operario=i.operario and v.tipo=i.tipo and v.numero=i.numero and v.operacion=i.operacion and v.seq=i.seq				
				
				LEFT JOIN (select a.operario,a.operacion,a.seq,tipo,numero, SUM(horas) as intcolision from v_horas_internas a inner join tall_tempario b
				on a.operacion=b.operacion
				where Año=".$anio." and mes=".$mes." and bodega in (9,14,21,22) and (b.descripcion not like 'segunda entrega%' and a.operacion not in ('PromoAlineacion','30011INSTALACION'))
				group by operario,tipo,numero,a.operacion,a.seq)ic
				on v.operario=ic.operario and v.tipo=ic.tipo and v.numero=ic.numero and v.operacion=ic.operacion and v.seq=ic.seq
				
				LEFT JOIN (select a.operario,a.operacion,a.seq,tipo,numero, SUM(horas) as intpagas from v_horas_internas a inner join tall_tempario b
				on a.operacion=b.operacion
				where Año=".$anio." and mes=".$mes." and bodega not in (9,14,21,22) and cliente in (92,106) and (b.descripcion not like 'segunda entrega%' and a.operacion not in ('PromoAlineacion','30011INSTALACION'))
				group by operario,tipo,numero,a.operacion,a.seq)ip
				on v.operario=ip.operario and v.tipo=ip.tipo and v.numero=ip.numero and v.operacion=ip.operacion and v.seq=ip.seq
				
				LEFT JOIN (select a.operario,a.operacion,a.seq,tipo,numero, SUM(horas) as intventas from v_horas_internas a inner join tall_tempario b
				on a.operacion=b.operacion
				where Año=".$anio." and mes=".$mes." and bodega not in (9,14,21,22,11) and cliente=100 and (b.descripcion not like 'segunda entrega%' and a.operacion not in ('PromoAlineacion','30011INSTALACION'))
				group by operario,tipo,numero,a.operacion,a.seq)iv
				on v.operario=iv.operario and v.tipo=iv.tipo and v.numero=iv.numero and v.operacion=iv.operacion and v.seq=iv.seq
				
				LEFT JOIN (select a.operario,a.operacion,a.seq,tipo,numero, SUM(horas) as intalista from v_horas_internas a inner join tall_tempario b
				on a.operacion=b.operacion
				where Año=".$anio." and mes=".$mes." and bodega=11 and cliente=100 and (b.descripcion not like 'segunda entrega%' and a.operacion not in ('PromoAlineacion','30011INSTALACION'))
				group by operario,tipo,numero,a.operacion,a.seq)ia
				on v.operario=ia.operario and v.tipo=ia.tipo and v.numero=ia.numero and v.operacion=ia.operacion and v.seq=ia.seq
				
				LEFT JOIN (select operario,a.operacion,a.seq,tipo,numero, count(distinct numero) as seg_entrega
				from v_horas_internas a inner join tall_tempario b
				on a.operacion=b.operacion
				where Año=".$anio." and mes=".$mes." and b.descripcion like 'segunda entrega%'
				group by operario,tipo,numero,a.operacion,a.seq)en
				on v.operario=en.operario and v.tipo=en.tipo and v.numero=en.numero and v.operacion=en.operacion and v.seq=en.seq
				
				LEFT JOIN (select operario,operacion,seq,tipo,numero, SUM(horas) as accesorios
				from v_horas_internas where Año=".$anio." and mes=".$mes." and operacion='30011INSTALACION'
				group by operario,tipo,numero,operacion,seq)acc
				on v.operario=acc.operario and v.tipo=acc.tipo and v.numero=acc.numero and v.operacion=acc.operacion and v.seq=acc.seq
				
				LEFT JOIN (select x.bodega,operario,operacion,seq,tipo,numero, convert (int,(count(distinct numero)*tf.valor_hora)) as alineacion from v_horas_internas x
				left join tall_tarifas_taller tf on x.bodega=tf.bodega
				where Año=".$anio." and mes=".$mes." and operacion in ('PromoAlineacion')
				group by operario,x.bodega,operacion,tipo,numero,tf.valor_hora,seq)al
				on v.operario=al.operario and v.tipo=al.tipo and v.numero=al.numero and v.operacion=al.operacion and v.seq=al.seq
				
				LEFT JOIN (select a.operario,a.operacion,tipo,numero, SUM(horas) as colision from v_informe_tecnico a inner join tall_tempario b
				on a.operacion=b.operacion
				where Año=".$anio." and mes=".$mes." and bodega in (9,14,21,22) and (b.descripcion not like 'segunda entrega%' and a.operacion not in ('PromoAlineacion','30011INSTALACION'))
				group by operario,tipo,numero,a.operacion)hc
				on v.operario=hc.operario and v.tipo=hc.tipo and v.numero=hc.numero and v.operacion=hc.operacion 
				
				LEFT JOIN tall_encabeza_orden e on v.numero_orden=e.numero
				
				LEFT JOIN v_vh_vehiculos vh on e.serie=vh.codigo
				
				WHERE t.patio in (1,3,4,5,6,7,8) and v.Año=".$anio." and v.mes=".$mes." and t.nit =".$nit."
				group by t.nit, v.tecnico,t.patio,i.operario,i.internas,v.numero_orden,v.operacion,seq_orden,
				v.tipo,v.numero,en.seg_entrega,acc.accesorios,intcolision, colision,
				vh.placa,vh.descripcion,al.alineacion,v.bodega, ia.intalista
		)cc
		LEFT JOIN tall_tempario tt on cc.operacion=tt.operacion
		LEFT JOIN referencias r ON cc.operacion=r.codigo
		LEFT JOIN tall_detalle_orden d on cc.seq_orden=d.seq
		
				
				order by cc.tecnico";
		return $this->db->query($sql);
	}


	public function get_comisiones_jefes($mes,$anio,$nit="")
	{
		if ($nit == "") {
			$sql = "select j.nit, nombres,j.sede, 
			facturacion_posventa= CASE WHEN j.nit=1094532250 THEN (facturacion_posventa-sacyr) else facturacion_posventa end,
			i.internas,
			comision_por_facturacion= CASE WHEN j.nit=1094532250 then convert (int,((facturacion_posventa+internas-sacyr)*p.Porc_facturacion))
			else convert (int,((facturacion_posventa+internas)*p.Porc_facturacion)) end,
			bono_utilidad=isnull(d.bono_utilidad,0), 
			utilidad_repuestos= CASE WHEN j.nit=1094532250 then (utilidad-utilidad_sacyr) else utilidad end,
			comision_utilidad_bruta=CASE WHEN j.nit=1094532250 then convert (int,((utilidad-utilidad_sacyr)*p.porc_utilidad)) else
			convert (int,(utilidad*p.porc_utilidad)) end,
			Bono_NPS=isnull(d.bono_nps,0)
			from (
			select nit, nombres,
			sede= case when apartado_aereo=4 then 'Gasolina Giron'
			when apartado_aereo=40 then 'Diesel Giron'
			when apartado_aereo=33 then 'Colision Giron'
			when apartado_aereo=29 then 'Bocono'
			when apartado_aereo=31 then 'Colision Bocono'
			when apartado_aereo=13 then 'Barranca' else 'Rosita' end
			from terceros where concepto_12 in (11,15,16) )j
			
			inner join (select f.sede, facturacion_posventa=SUM(facturacion_sede)-sum(isnull(rptos_politicas,0))
			from (
			select sede,SUM(facturacion_sede) as facturacion_sede
			FROM (
			select año=YEAR(fec), Mes=month(fec),
			sede= case when centro=4 then 'Gasolina Giron'
			when centro=40 then 'Diesel Giron'
			when centro in (33,45) then 'Colision Giron'
			when centro in (29,80) then 'Bocono'
			when centro in (31,46) then 'Colision Bocono'
			when centro in (13,70) then 'Barranca' else 'Rosita' end,
			  convert(int,sum(valor_niif)*-1) as facturacion_sede
			  from movimiento where YEAR(fec)=" . $anio . " and  month(fec)=" . $mes . " and (cuenta like '41%' or cuenta like '530535%') 
			  and tipo not in ('Z1','IT','DIT','IK','DIK','WI','DIW','IL','DIL','IR','DIR','IPG','IPV','DIPG','DIPV')
			  and centro in (4,40,33,45,29,80,31,46,13,70,16)
			  group by YEAR(fec), month(fec),centro ) g
			  group by sede
			  )f  
			  left join
			 (select sede,SUM(rptos_politicas) as rptos_politicas
			from (
			select sede= case when centro=4 then 'Gasolina Giron' when centro=40 then 'Diesel Giron' when centro in (33,45)
			then 'Colision Giron' when centro in (29,80) then 'Bocono' when centro in (31,46) then 'Colision Bocono' when centro in (13,70)
			then 'Barranca' else 'Rosita' end,  
			convert (int,SUM(venta_rptos)) as rptos_politicas from v_factu_tecnico a
			inner join movimiento m on a.tipo=m.tipo and a.numero=m.numero where a.operario='232' and a.Año=" . $anio . " and a.Mes=" . $mes . "
			and (m.cuenta like '413506%' or cuenta like '417520%' or cuenta like '53053580%') group by centro)q
			group by sede)p  
			on f.sede=p.sede group by f.sede) c
			   on j.sede=c.sede
			   inner join (select sede,sum(utilidad) as utilidad from (select año=YEAR(fec),
			   Mes=month(fec), sede= case when centro=4 then 'Gasolina Giron' when centro=40 then 'Diesel Giron'
			   when centro in (33,45) then 'Colision Giron' when centro in (29,80) then 'Bocono' when centro in (31,46)
			   then 'Colision Bocono' when centro in (13,70) then 'Barranca' else 'Rosita' end, convert(int,sum(valor_niif)*-1) as utilidad
			   from movimiento where YEAR(fec)=" . $anio . " and  month(fec)=" . $mes . "
			   and (cuenta like '413506%' or cuenta like '417520%' or cuenta like '530535%'
			   or cuenta like '613506%') and tipo not in ('Z1','IT','DIT','IK','DIK','WI','DIW','IL','DIL','IR','DIR','IPG','IPV','DIPG','DIPV') and centro in (4,40,33,45,29,80,31,46,13,70,16)
			   group by YEAR(fec), month(fec),centro ) g
			   group by g.sede )u on j.sede=u.sede 
			   inner join postv_porc_jefes p on j.nit=p.Nit
			   left join postv_comisiones_jefes d on j.nit=d.nit 
			   left join (select sede,SUM(internas) as internas
			   from ( select convert(int,(SUM(horas)*18824)) as internas, sede=case when bodega=1 then 'Gasolina Giron'
			   when bodega=11 then 'Diesel Giron' when bodega in (9,21) then 'Colision Giron' when bodega in (8,16) then 'Bocono'
			   when bodega in (14,22) then 'Colision Bocono' when bodega in (6,19) then 'Barranca' else 'Rosita' end
			   from v_horas_internas where Año=" . $anio . " and Mes=" . $mes . " and bodega in (1,11,9,21,8,16,14,22,6,19,7)
			   group by bodega )r group by sede )i on j.sede=i.sede
			   left join (select isnull(SUM(valor_niif)*-1,0) as sacyr, sede='Bocono' from movimiento where YEAR(fec)=" . $anio . " and  month(fec)=" . $mes . "
			   and nit=901361064 and (cuenta like '41%' or cuenta like '530535%') and centro in (29,80) and tipo not in ('Z1','IT','DIT','IK','DIK','WI','DIW','IL','DIL','IR','DIR','IPG','IPV','DIPG','DIPV'))sc
			   on j.sede=sc.sede
			   left join (select isnull(SUM(valor_niif)*-1,0) as utilidad_sacyr, sede='Bocono' from movimiento where YEAR(fec)=" . $anio . " and  month(fec)=" . $mes . "
			   and nit=901361064 and (cuenta like '413506%' or cuenta like '417520%' or cuenta like '53053580%' or cuenta like '613506%')
			   and centro in (29,80) and tipo not in ('Z1','IT','DIT','IK','DIK','WI','DIW','IL','DIL','IR','DIR','IPG','IPV','DIPG','DIPV'))us
			   on j.sede=us.sede      
			   WHERE d.anio = " . $anio . " and d.mes = " . $mes . " ORDER BY nombres";
		}else{
			$sql = "select j.nit, nombres,j.sede, 
			facturacion_posventa= CASE WHEN j.nit=1094532250 THEN (facturacion_posventa-sacyr) else facturacion_posventa end,
			i.internas,
			comision_por_facturacion= CASE WHEN j.nit=1094532250 then convert (int,((facturacion_posventa+internas-sacyr)*p.Porc_facturacion))
			else convert (int,((facturacion_posventa+internas)*p.Porc_facturacion)) end,
			bono_utilidad=isnull(d.bono_utilidad,0), 
			utilidad_repuestos= CASE WHEN j.nit=1094532250 then (utilidad-utilidad_sacyr) else utilidad end,
			comision_utilidad_bruta=CASE WHEN j.nit=1094532250 then convert (int,((utilidad-utilidad_sacyr)*p.porc_utilidad)) else
			convert (int,(utilidad*p.porc_utilidad)) end,
			Bono_NPS=isnull(d.bono_nps,0)
			from (
			select nit, nombres,
			sede= case when apartado_aereo=4 then 'Gasolina Giron'
			when apartado_aereo=40 then 'Diesel Giron'
			when apartado_aereo=33 then 'Colision Giron'
			when apartado_aereo=29 then 'Bocono'
			when apartado_aereo=31 then 'Colision Bocono'
			when apartado_aereo=13 then 'Barranca' else 'Rosita' end
			from terceros where concepto_12 in (11,15,16) )j
			
			inner join (select f.sede, facturacion_posventa=SUM(facturacion_sede)-sum(isnull(rptos_politicas,0))
			from (
			select sede,SUM(facturacion_sede) as facturacion_sede
			FROM (
			select año=YEAR(fec), Mes=month(fec),
			sede= case when centro=4 then 'Gasolina Giron'
			when centro=40 then 'Diesel Giron'
			when centro in (33,45) then 'Colision Giron'
			when centro in (29,80) then 'Bocono'
			when centro in (31,46) then 'Colision Bocono'
			when centro in (13,70) then 'Barranca' else 'Rosita' end,
			  convert(int,sum(valor_niif)*-1) as facturacion_sede
			  from movimiento where YEAR(fec)=" . $anio . " and  month(fec)=" . $mes . " and (cuenta like '41%' or cuenta like '530535%') 
			  and tipo not in ('Z1','IT','DIT','IK','DIK','WI','DIW','IL','DIL','IR','DIR','IPG','IPV','DIPG','DIPV')
			  and centro in (4,40,33,45,29,80,31,46,13,70,16)
			  group by YEAR(fec), month(fec),centro ) g
			  group by sede
			  )f  
			  left join
			 (select sede,SUM(rptos_politicas) as rptos_politicas
			from (
			select sede= case when centro=4 then 'Gasolina Giron' when centro=40 then 'Diesel Giron' when centro in (33,45)
			then 'Colision Giron' when centro in (29,80) then 'Bocono' when centro in (31,46) then 'Colision Bocono' when centro in (13,70)
			then 'Barranca' else 'Rosita' end,  
			convert (int,SUM(venta_rptos)) as rptos_politicas from v_factu_tecnico a
			inner join movimiento m on a.tipo=m.tipo and a.numero=m.numero where a.operario='232' and a.Año=" . $anio . " and a.Mes=" . $mes . "
			and (m.cuenta like '413506%' or cuenta like '417520%' or cuenta like '53053580%') group by centro)q
			group by sede)p  
			on f.sede=p.sede group by f.sede) c
			   on j.sede=c.sede
			   inner join (select sede,sum(utilidad) as utilidad from (select año=YEAR(fec),
			   Mes=month(fec), sede= case when centro=4 then 'Gasolina Giron' when centro=40 then 'Diesel Giron'
			   when centro in (33,45) then 'Colision Giron' when centro in (29,80) then 'Bocono' when centro in (31,46)
			   then 'Colision Bocono' when centro in (13,70) then 'Barranca' else 'Rosita' end, convert(int,sum(valor_niif)*-1) as utilidad
			   from movimiento where YEAR(fec)=" . $anio . " and  month(fec)=" . $mes . "
			   and (cuenta like '413506%' or cuenta like '417520%' or cuenta like '530535%'
			   or cuenta like '613506%') and tipo not in ('Z1','IT','DIT','IK','DIK','WI','DIW','IL','DIL','IR','DIR','IPG','IPV','DIPG','DIPV') and centro in (4,40,33,45,29,80,31,46,13,70,16)
			   group by YEAR(fec), month(fec),centro ) g
			   group by g.sede )u on j.sede=u.sede 
			   inner join postv_porc_jefes p on j.nit=p.Nit
			   left join postv_comisiones_jefes d on j.nit=d.nit 
			   left join (select sede,SUM(internas) as internas
			   from ( select convert(int,(SUM(horas)*18824)) as internas, sede=case when bodega=1 then 'Gasolina Giron'
			   when bodega=11 then 'Diesel Giron' when bodega in (9,21) then 'Colision Giron' when bodega in (8,16) then 'Bocono'
			   when bodega in (14,22) then 'Colision Bocono' when bodega in (6,19) then 'Barranca' else 'Rosita' end
			   from v_horas_internas where Año=" . $anio . " and Mes=" . $mes . " and bodega in (1,11,9,21,8,16,14,22,6,19,7)
			   group by bodega )r group by sede )i on j.sede=i.sede
			   left join (select isnull(SUM(valor_niif)*-1,0) as sacyr, sede='Bocono' from movimiento where YEAR(fec)=" . $anio . " and  month(fec)=" . $mes . "
			   and nit=901361064 and (cuenta like '41%' or cuenta like '530535%') and centro in (29,80) and tipo not in ('Z1','IT','DIT','IK','DIK','WI','DIW','IL','DIL','IR','DIR','IPG','IPV','DIPG','DIPV'))sc
			   on j.sede=sc.sede
			   left join (select isnull(SUM(valor_niif)*-1,0) as utilidad_sacyr, sede='Bocono' from movimiento where YEAR(fec)=" . $anio . " and  month(fec)=" . $mes . "
			   and nit=901361064 and (cuenta like '413506%' or cuenta like '417520%' or cuenta like '53053580%' or cuenta like '613506%')
			   and centro in (29,80) and tipo not in ('Z1','IT','DIT','IK','DIK','WI','DIW','IL','DIL','IR','DIR','IPG','IPV','DIPG','DIPV'))us
			   on j.sede=us.sede      
			   WHERE d.anio = " . $anio . " and d.mes = " . $mes . " and j.nit =".$nit." ORDER BY nombres";
		}
		return $this->db->query($sql);
	}

	public function get_detalle_nomina_jefes($mes,$anio,$nit)
	{
		$sql = "select mo.nit,mo.nombres,mo.sede,
						mano_de_obra= CASE WHEN mo.nit=1094532250 then (mo.mano_de_obra-isnull(mo_sacyr,0)) else mo.mano_de_obra end
						,CASE WHEN mo.nit=1094532250 then (SUM(repuestos)-isnull(repuestos_sacyr,0)) else SUM(repuestos) end as repuestos from
						(select nit,nombres,j.sede,SUM(mano_obra) as mano_de_obra from
						(select nit, nombres,
						sede= case when apartado_aereo=4 then 'Gasolina Giron'
						  when apartado_aereo=40 then 'Diesel Giron'
						  when apartado_aereo=33 then 'Colision Giron'
						  when apartado_aereo=29 then 'Bocono'
						  when apartado_aereo=31 then 'Colision Bocono'
						  when apartado_aereo=13 then 'Barranca'
						  else 'Rosita' end
						from terceros
						where concepto_12 in (11,15,16)
						)j
						inner join
						(select año=YEAR(fec), Mes=month(fec),
						sede= case when centro=4 then 'Gasolina Giron'
						  when centro=40 then 'Diesel Giron'
						  when centro in (33,45) then 'Colision Giron'
						  when centro in (29,80) then 'Bocono'
						  when centro in (31,46) then 'Colision Bocono'
						  when centro in (13,70) then 'Barranca'
						  else 'Rosita' end,
						convert(int,sum(valor_niif)*-1) as mano_obra
						from movimiento where YEAR(fec)=" . $anio . " and  month(fec)=" . $mes . " and
						(cuenta like '413504%' or cuenta like '417510%' or cuenta like '53053560%') 
						and centro in (4,40,33,45,29,80,31,46,13,70,16)  and tipo not in ('Z1','IT','DIT','IK','DIK','WI','DIW','IL','DIL','IR','DIR','IPG','IPV','DIPG','DIPV')
						group by YEAR(fec), month(fec),centro) f
						on j.sede=f.sede
						group by nit,nombres,j.sede
						)mo
						inner join
						(select año=YEAR(fec), Mes=month(fec),
						sede= case when centro=4 then 'Gasolina Giron'
						  when centro=40 then 'Diesel Giron'
						  when centro in (33,45) then 'Colision Giron'
						  when centro in (29,80) then 'Bocono'
						  when centro in (31,46) then 'Colision Bocono'
						  when centro in (13,70) then 'Barranca'
						  else 'Rosita' end,
						convert(int,sum(valor_niif)*-1) as repuestos
						from movimiento  where YEAR(fec)=" . $anio . " and  month(fec)=" . $mes . " and
						(cuenta like '413506%' or cuenta like '417520%' or cuenta like '53053580%') 
						and centro in (4,40,33,45,29,80,31,46,13,70,16) and tipo not in ('Z1','IT','DIT','IK','DIK','WI','DIW','IL','DIL','IR','DIR','IPG','IPV','DIPG','DIPV')
						group by YEAR(fec), month(fec),centro) r
						on mo.sede=r.sede
						LEFT join
						(select año=YEAR(fec), Mes=month(fec),
						sede= 'Bocono',convert(int, sum(valor_niif)*-1) as repuestos_sacyr
						from movimiento  where YEAR(fec)=" . $anio . " and  month(fec)=" . $mes . " and nit=901361064 and 
						(cuenta like '413506%' or cuenta like '417520%' or cuenta like '53053580%') and centro in (29,80) and tipo not in ('Z1','IT','DIT','IK','DIK','WI','DIW','IL','DIL','IR','DIR','IPG','IPV','DIPG','DIPV')
						group by YEAR(fec), month(fec),centro) rs
						on mo.sede=rs.sede
						left join
						(select año=YEAR(fec), Mes=month(fec),
						sede= 'Bocono',convert(int,sum(valor_niif)*-1) as mo_sacyr
						from movimiento  where YEAR(fec)=" . $anio . " and  month(fec)=" . $mes . " and nit=901361064 and
						(cuenta like '413504%' or cuenta like '417510%' or cuenta like '53053560%') and centro in (29,80) and tipo not in ('Z1','IT','DIT','IK','DIK','WI','DIW','IL','DIL','IR','DIR','IPG','IPV','DIPG','DIPV')
						group by YEAR(fec), month(fec),centro) ms
						on mo.sede=ms.sede
						WHERE nit = " . $nit . "
						group by mo.nit,mo.nombres,mo.sede,mo.mano_de_obra, mo_sacyr,repuestos_sacyr";
		return $this->db->query($sql);
	}

	public function consultar_registro($anio,$mes,$nit)
	{
		$sql = "select * from postv_comisiones_jefes where anio=".$anio." and  mes=".$mes." and nit=".$nit;
		//return $this->db->query($sql);
		if ($this->db->query($sql)) {
			return true;
		}else {
			return false;
		}
	}

	public function insert_val_jefes($data)
	{
		if ($this->db->insert('postv_comisiones_jefes',$data)) {
			return true;
		}else{
			return false;
		}
	}

	public function update_val_jefes($data,$update){

		$this->db->where('nit', $data['nit']);
		$this->db->where('anio', $data['anio']);
		$this->db->where('mes', $data['mes']);
		if ($this->db->update('postv_comisiones_jefes', $update)) {
			return true;
		}else{
			return false;
		}
	}

	public function get_comisiones_dir_flota($anio,$mes)
	{
		$sql = "SELECT id=1, CASE WHEN SUM(COMISIONA) >= 15 THEN 1500000 ELSE 0 END AS comision FROM v_placas_flotas_ingreso_taller WHERE ano=$anio AND Mes=$mes
			UNION
			SELECT id=2, ISNULL(SUM(comision_venta_compartida),0) AS comision FROM v_venta_reptos_compartida_flotas WHERE ano=$anio AND mes=$mes
			UNION
			select id=3, ISNULL(SUM(comision_venta_compartida),0) AS comision FROM v_venta_reptos_directa_flotas WHERE ano=$anio AND mes=$mes
			UNION
			SELECT id=4, ISNULL(SUM(comision),0) FROM v_comision_ventas_flotas_creadas_dirflotas WHERE ano=$anio AND mes=$mes
			UNION
			SELECT id=5, ISNULL(SUM(comision),0) FROM v_comision_ventas_flotas_apoyo WHERE ano=$anio and mes=$mes
			ORDER BY id ASC";
		$res = $this->db->query($sql);
		
		return $res->num_rows() > 0 ? $res : null;
	}

	public function get_detalle_comision_dir_flota($concepto, $anio,$mes)
	{
		$tablas = array('v_placas_flotas_ingreso_taller', 'v_venta_reptos_compartida_flotas', 'v_venta_reptos_directa_flotas', 'v_comision_ventas_flotas_creadas_dirflotas', 'v_comision_ventas_flotas_apoyo');
		$sql = "SELECT * FROM ". $tablas[$concepto] ." WHERE ano=$anio and mes=$mes";
		$res = $this->db->query($sql);
		
		return $res->num_rows() > 0 ? $res : null;
	}
}
