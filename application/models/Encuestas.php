<?php

/**
 * 
 */
class Encuestas extends CI_Model
{

	public function listar_preguntas_encuesta_satisfaccion()
	{
		return $this->db->query("SELECT * FROM posv_preguntas_encuesta_satisfaccion");
	}

	public function resp_satisfaccion($cliente, $pregunta, $respuesta, $fecha)
	{
		$sql = "INSERT INTO posv_respuestas_satisfaccion(pregunta,respuesta,fecha,ot)
				VALUES ($cliente,'$respuesta','$fecha',$pregunta)";
		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}

	public function insert_encuesta_satisfaccion($data)
	{
		$ot = $data['ot'];
		$fecha = $data['fecha'];
		$pregunta1 = $data['p1'];
		$pregunta2 = $data['p2'];
		$pregunta3 = $data['p3'];
		$pregunta4 = $data['p4'];
		$pregunta5 = $data['p5'];

		$sql = "INSERT INTO posv_encuesta_satisfaccion(n_orden,fecha,pregunta1,pregunta2,pregunta3,pregunta4,pregunta5)
				VALUES ('$ot','$fecha','$pregunta1','$pregunta2','$pregunta3','$pregunta4','$pregunta5')";

		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}

	public function insert_encuesta_satisfaccion_qr($data)
	{
		$ot = $data['ot'];
		$fecha = $data['fecha'];
		$pregunta1 = $data['p1'];
		$pregunta2 = $data['p2'];
		$pregunta3 = $data['p3'];
		$pregunta4 = $data['p4'];
		$pregunta5 = $data['p5'];
		$fuente = $data['fuente'];
		$bod = $data['bod'];
		$sql = "INSERT INTO postv_encuesta_satisfaccion_qr(placa,fecha,pregunta1,pregunta2,pregunta3,pregunta4,pregunta5,fuente,bod)
				VALUES ('$ot','$fecha','$pregunta1','$pregunta2','$pregunta3','$pregunta4','$pregunta5','$fuente','$bod')";

		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * METODO PARA INSERTAR ENCUESTA DE SATISFACION DE QR POR MEDIO DE VENTANILLA
	 * ANDRES GOMEZ
	 * 2022-25-01
	 */

	public function insert_encuesta_satisfaccion_qr_ventanilla($data)
	{
		$placa = $data['placa'];
		$fecha = $data['fecha'];
		$pregunta1 = $data['p1'];
		$pregunta2 = $data['p2'];
		$pregunta3 = $data['p3'];
		$pregunta4 = $data['p4'];
		$pregunta5 = $data['p5'];
		$fuente = $data['fuente'];
		$bodega = $data['bodega'];

		$sql = "INSERT INTO postv_encuesta_satisfaccion_qr(placa,fecha,pregunta1,pregunta2,pregunta3,pregunta4,pregunta5,fuente,bod)
				VALUES ('$placa','$fecha','$pregunta1','$pregunta2','$pregunta3','$pregunta4','$pregunta5','$fuente','$bodega')";

		if ($this->db->query($sql)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function insert_nps_tec($data)
	{
		$nit_tec = trim($data[0]);
		$nombres = trim($data[1]);
		$fecha_enc = trim($data[2]);
		$calificacion = trim($data[3]);
		$sede = trim($data[4]);

		$sql = "INSERT INTO nps_tec(nit_tec,nombres,fecha_enc,calificacion,sede)
				VALUES ('$nit_tec','$nombres','$fecha_enc',$calificacion,'$sede')";

		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}

	public function val_tec_nps($fecha, $nit)
	{
		$sql = $this->db->query("SELECT COUNT(*) AS n FROM nps_tec
				WHERE CONVERT(DATE,fecha_enc) = CONVERT(DATE,'" . $fecha . "')
				AND nit_tec = '" . $nit . "'");
		if ($sql->num_rows() > 0) {
			return $sql->row();
		} else {
			return null;
		}
	}

	public function Informe_encuesta_satisfaccion()
	{
		return $this->db->query("SELECT DISTINCT cli.nit_real, cli.nombres, teo.numero, vhv.placa, CONVERT(DATE,pes.fecha) as fecha FROM terceros cli
		INNER JOIN tall_encabeza_orden teo ON teo.nit = cli.nit_real
		INNER JOIN v_vh_vehiculos vhv ON teo.serie = vhv.codigo
		INNER JOIN posv_encuesta_satisfaccion pes ON pes.n_orden = teo.numero
		ORDER BY CONVERT(DATE,pes.fecha) DESC");
	}

	public function Informe_encuesta_satisfaccion_by_ot($ot)
	{
		return $this->db->query("SELECT DISTINCT client.nombres AS cliente,client.nit_real AS nit_client,tec.nombres AS tecnico,b.descripcion,teo.numero  FROM tall_encabeza_orden teo
			INNER JOIN terceros client ON client.nit_real = teo.nit
			INNER JOIN terceros tec ON tec.nit_real = teo.vendedor
			INNER JOIN bodegas b ON b.id = teo.bodega
			WHERE teo.numero = '" . $ot . "'");
	}

	public function Informe_encuesta_satisfaccion_by_nom($nombre)
	{
		return $this->db->query("SELECT  te.numero,c.nombres,pregunta1,pregunta2,pregunta3,pregunta4,pregunta5 
		FROM postv_encuesta_satisfaccion_qr pes inner join referencias_imp r
		on pes.placa=r.placa
		inner join v_ultima_entrada_taller_datos uet on r.codigo=uet.uetd_serie
		inner join tall_encabeza_orden te on uet.uetd_numero=te.numero
		inner join terceros t on te.vendedor=t.nit
		inner join terceros c on te.nit=c.nit
		where MONTH(CONVERT(DATE,pes.fecha)) = MONTH(DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-0, 0))
		and YEAR(CONVERT(DATE,pes.fecha)) = 2022 and pes.bod in (1,9,11,21,7,6,19,8,14,16,22) and t.nombres = '".$nombre."'");
	}

	public function Informe_encuesta_satisfaccion_by_cliente($nit)
	{
		return $this->db->query("SELECT pes.fecha,client.nombres AS cliente,client.nit_real AS nit_client,tec.nombres AS tecnico,b.descripcion,teo.numero,pes.pregunta1,pes.pregunta2,pes.pregunta3,pes.pregunta4,pes.pregunta5
			FROM tall_encabeza_orden teo
			INNER JOIN terceros client ON client.nit_real = teo.nit
			INNER JOIN terceros tec ON tec.nit_real = teo.vendedor
			INNER JOIN bodegas b ON b.id = teo.bodega
			INNER JOIN posv_encuesta_satisfaccion pes ON pes.n_orden = teo.numero
			WHERE tec.nit_real = '" . $nit . "'
			ORDER BY CONVERT(DATE,pes.fecha) DESC");
	}

	public function detallle_Informe_encuesta_satisfaccion($ot)
	{
		return $this->db->query("SELECT pregunta1,pregunta2,pregunta3,pregunta4,pregunta5 FROM posv_encuesta_satisfaccion WHERE n_orden = '" . $ot . "'");
	}

	public function detalle_Informe_nps_tecnico_pac($nombre)
	{
		$sql = "SELECT t.nombres,pregunta1,pregunta2,pregunta3,pregunta4,pregunta5 
				FROM posv_encuesta_satisfaccion pes
				INNER JOIN tall_encabeza_orden teo ON pes.n_orden = teo.numero
				INNER JOIN terceros t ON teo.vendedor = t.nit_real
				WHERE CONVERT(DATE,teo.fecha_hora_entrega_real) BETWEEN CONVERT(DATE,DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)) 
				AND CONVERT(DATE,DATEADD(ms,-3,DATEADD(mm,0,DATEADD(mm,DATEDIFF(mm,0,GETDATE())+1,0))))
				AND t.nombres = '" . $nombre . "'";
		return $this->db->query($sql);
	}

	public function val_encuesta_satisfaccion($n_orden)
	{
		$sql = $this->db->query("SELECT COUNT(*) AS n FROM posv_encuestas_enviadas
		WHERE n_orden = '" . $n_orden . "'
		AND fecha_envio >= DATEADD(DAY,-2,GETDATE())
		AND n_orden NOT IN (SELECT n_orden FROM posv_encuesta_satisfaccion)");
		if ($sql->num_rows() > 0) {
			return $sql->row();
		} else {
			return null;
		}
	}

	public function inf_gral_encuesta_satisfaccion($fecha_ini, $fecha_fin)
	{
		return $this->db->query("SELECT  t.nombres,teo.vendedor,AVG(pes.pregunta1) AS prom_p1,AVG(pes.pregunta2) AS prom_p2 
		FROM posv_encuesta_satisfaccion pes
		INNER JOIN tall_encabeza_orden teo ON teo.numero = pes.n_orden
		INNER JOIN terceros t ON t.nit_real = teo.vendedor
		WHERE CONVERT(DATE,pes.fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "')
		GROUP BY teo.vendedor, t.nombres");
	}

	public function inf_gral_encuesta_satisfaccion_bod($fecha_ini, $fecha_fin, $bod)
	{
		return $this->db->query("SELECT  t.nombres,teo.vendedor,AVG(pes.pregunta1) AS prom_p1,AVG(pes.pregunta2) AS prom_p2 
		FROM posv_encuesta_satisfaccion pes
		INNER JOIN tall_encabeza_orden teo ON teo.numero = pes.n_orden
		INNER JOIN terceros t ON t.nit_real = teo.vendedor
		INNER JOIN bodegas b ON b.bodega = teo.bodega
		WHERE CONVERT(DATE,pes.fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "')
		AND b.bodega = '" . $bod . "'
		GROUP BY teo.vendedor, t.nombres");
	}

	public function inf_gral_encuesta_satisfaccion_tec($fecha_ini, $fecha_fin, $tec)
	{
		return $this->db->query("SELECT  t.nombres,teo.vendedor,AVG(pes.pregunta1) AS prom_p1,AVG(pes.pregunta2) AS prom_p2 
		FROM posv_encuesta_satisfaccion pes
		INNER JOIN tall_encabeza_orden teo ON teo.numero = pes.n_orden
		INNER JOIN terceros t ON t.nit_real = teo.vendedor
		INNER JOIN bodegas b ON b.bodega = teo.bodega
		WHERE CONVERT(DATE,pes.fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "')
		AND teo.vendedor = '" . $tec . "'
		GROUP BY teo.vendedor, t.nombres");
	}
	public function inf_gral_encuesta_satisfaccion_cli($fecha_ini, $fecha_fin, $cli)
	{
		return $this->db->query("SELECT  t.nombres,teo.vendedor,AVG(pes.pregunta1) AS prom_p1,AVG(pes.pregunta2) AS prom_p2 
		FROM posv_encuesta_satisfaccion pes
		INNER JOIN tall_encabeza_orden teo ON teo.numero = pes.n_orden
		INNER JOIN terceros t ON t.nit_real = teo.vendedor
		INNER JOIN bodegas b ON b.bodega = teo.bodega
		WHERE CONVERT(DATE,pes.fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "')
		AND teo.nit = '" . $cli . "'
		GROUP BY teo.vendedor, t.nombres");
	}

	public function inf_gral_encuesta_satisfaccion_ot($fecha_ini, $fecha_fin, $ot)
	{
		return $this->db->query("SELECT  t.nombres,teo.vendedor,AVG(pes.pregunta1) AS prom_p1,AVG(pes.pregunta2) AS prom_p2 
		FROM posv_encuesta_satisfaccion pes
		INNER JOIN tall_encabeza_orden teo ON teo.numero = pes.n_orden
		INNER JOIN terceros t ON t.nit_real = teo.vendedor
		INNER JOIN bodegas b ON b.bodega = teo.bodega
		WHERE CONVERT(DATE,pes.fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "')
		AND teo.numero = '" . $ot . "'
		GROUP BY teo.vendedor, t.nombres");
	}

	public function inf_gral_encuesta_satisfaccion_ns($fecha_ini, $fecha_fin, $ns1, $ns2)
	{
		return $this->db->query("SELECT  t.nombres,teo.vendedor,AVG(pes.pregunta1) AS prom_p1,AVG(pes.pregunta2) AS prom_p2 
		FROM posv_encuesta_satisfaccion pes
		INNER JOIN tall_encabeza_orden teo ON teo.numero = pes.n_orden
		INNER JOIN terceros t ON t.nit_real = teo.vendedor
		INNER JOIN bodegas b ON b.bodega = teo.bodega
		WHERE CONVERT(DATE,pes.fecha) BETWEEN CONVERT(DATE,'" . $fecha_ini . "') AND CONVERT(DATE,'" . $fecha_fin . "')
		GROUP BY teo.vendedor, t.nombres 
		HAVING 
		AVG(pes.pregunta1) >= " . $ns1 . "
		AND AVG(pes.pregunta1) <= " . $ns2 . "
		OR AVG (pes.pregunta2) >= " . $ns1 . "
		AND AVG(pes.pregunta2) <= " . $ns2 . "");
	}
	/*METODO PARA INSERTAR DATOS, RECIBE UN ARRAY*/
	public function insert_nps_sedes($data)
	{
		$sede = $data['sede'];
		$fecha = $data['fecha'];
		$calificacion = $data['calificacion'];
		$cal06 = $data['cal06'];
		$cal78 = $data['cal78'];
		$cal910 = $data['cal910'];

		$sql = "INSERT INTO NPS_SEDES(sede,Fecha,Calificacion,Enc_0_a_6,Enc_7_a_8,Enc_9_a_10)
				VALUES ('$sede','$fecha',$calificacion,$cal06,$cal78,$cal910)";
		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}

	/*METODO PARA INSERTAR DATOS, RECIBE UN ARRAY*/
	public function insert_nps_tecnicos($data)
	{
		$sede = $data['sede'];
		$tecnico = $data['tecnico'];
		$fecha = $data['fecha'];
		$calificacion = $data['calificacion'];
		$placa = $data['placa'];
		$tipificacion = $data['tipificacion'];
		$encu06 = $data['encu06'];
		$encu78 = $data['encu78'];
		$encu910 = $data['encu910'];

		$sql = "INSERT INTO NPS_tecnicos(Sede,fecha,tecnico,Placa,Calificacion,Enc_0_a_6,Enc_7_a_8,Enc_9_a_10,Tipificacion)
	    VALUES ('$sede','$fecha','$tecnico','$placa',$calificacion,$encu06,$encu78,$encu910,'$tipificacion')";
		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}

	public function Informe_nps_general($f1, $f2)
	{
		$sql = "SELECT Fecha, Calificacion FROM NPS_sedes 
		WHERE sede = 'general'
		AND CONVERT(DATE,Fecha) BETWEEN CONVERT(DATE,'" . $f1 . "') AND CONVERT(DATE,'" . $f2 . "')
		ORDER BY CONVERT(DATE,Fecha) ASC";
		return $this->db->query($sql);
	}

	public function detalle_Informe_nps_general($f1, $f2)
	{
		$sql = "SELECT * FROM NPS_sedes 
		WHERE sede = 'general'
		AND CONVERT(DATE,Fecha) BETWEEN CONVERT(DATE,'" . $f1 . "') AND CONVERT(DATE,'" . $f2 . "')
		ORDER BY CONVERT(DATE,Fecha) ASC";
		return $this->db->query($sql);
	}

	public function Informe_nps_sede($sede)
	{
		$sql = "SELECT Fecha, Calificacion FROM NPS_sedes 
		WHERE sede = '" . $sede . "'
		AND CONVERT(DATE,Fecha) = CONVERT(DATE,GETDATE())
		ORDER BY CONVERT(DATE,Fecha) ASC";
		return $this->db->query($sql);
	}

	public function Informe_nps_sede_v2()
	{
		$sql = "SELECT * FROM
				(
					SELECT TOP 1 * FROM NPS_sedes 
					WHERE sede = 'giron'
					ORDER BY CONVERT(DATE,Fecha) DESC
				)a
				UNION
				SELECT * FROM
				(
					SELECT TOP 1 * FROM NPS_sedes 
					WHERE sede = 'rosita'
					ORDER BY CONVERT(DATE,Fecha) DESC 
				)b
				UNION
				SELECT * FROM
				(
					SELECT TOP 1 * FROM NPS_sedes 
					WHERE sede = 'barranca'
					ORDER BY CONVERT(DATE,Fecha) DESC 
				)c
				UNION
				SELECT * FROM
				(
					SELECT TOP 1 * FROM NPS_sedes 
					WHERE sede = 'bocono'
					ORDER BY CONVERT(DATE,Fecha) DESC 
				)d
				UNION
				SELECT * FROM
				(
					SELECT TOP 1 * FROM NPS_sedes 
					WHERE sede = 'general'
					ORDER BY CONVERT(DATE,Fecha) DESC 
				)d
				 ";
		return $this->db->query($sql);
	}

	public function detalle_Informe_nps_sede($sede)
	{
		$sql = "SELECT * FROM NPS_sedes 
		WHERE sede = '" . $sede . "'
		AND CONVERT(DATE,Fecha) = CONVERT(DATE,GETDATE())
		ORDER BY CONVERT(DATE,Fecha) ASC";
		return $this->db->query($sql);
	}

	/*public function Informe_nps_tecnico($f1,$f2,$tecnico){
		$sql="SELECT tec.Fecha, tec.Calificacion, t.nombres FROM NPS_tecnicos tec 
		INNER JOIN terceros t ON tec.tecnico = t.nit_real 
		WHERE tec.tecnico = '".$tecnico."'
		AND CONVERT(DATE,tec.Fecha) BETWEEN CONVERT(DATE,'".$f1."') AND CONVERT(DATE,'".$f2."')
		ORDER BY CONVERT(DATE,tec.Fecha) ASC";
		return $this->db->query($sql);
	}*/

	public function Informe_nps_tecnico_v2($sede)
	{
		$sql = "SELECT t.nombres, nps_tec.Calificacion FROM NPS_tecnicos nps_tec
				INNER JOIN terceros t ON t.nit_real = nps_tec.tecnico
				WHERE CONVERT(DATE,nps_tec.fecha) BETWEEN CONVERT(DATE,DATEADD(dd,-(DAY(GETDATE())-1),GETDATE()),101)
				AND CONVERT(DATE,DATEADD(dd,-(DAY(DATEADD(mm,1,GETDATE()))),DATEADD(mm,1,GETDATE())),101)
				AND nps_tec.Sede = '" . $sede . "'";
		return $this->db->query($sql);
	}

	/*public function detalle_Informe_nps_tecnico($f1,$f2,$tecnico){
		$sql="SELECT tec.Fecha, tec.Calificacion, t.nombres,tec.Enc_0_a_6,tec.Enc_7_a_8,tec.Enc_9_a_10 FROM NPS_tecnicos tec
		INNER JOIN terceros t ON tec.tecnico = t.nit_real 
		WHERE tec.tecnico = '".$tecnico."'
		AND CONVERT(DATE,tec.Fecha) BETWEEN CONVERT(DATE,'".$f1."') AND CONVERT(DATE,'".$f2."')
		ORDER BY CONVERT(DATE,tec.Fecha) ASC";
		return $this->db->query($sql);
	}*/

	public function detalle_Informe_nps_tecnico($sede)
	{
		$sql = "SELECT t.nombres,nps_tec.fecha,v.placa,nps_tec.Calificacion,nps_tec.Enc_0_a_6,nps_tec.Enc_7_a_8,nps_tec.Enc_9_a_10,nps_tec.Tipificacion 
				FROM NPS_tecnicos nps_tec
				INNER JOIN v_vh_vehiculos v ON v.serie = nps_tec.Placa 
				INNER JOIN terceros t ON t.nit_real = nps_tec.tecnico
				WHERE CONVERT(DATE,fecha) BETWEEN CONVERT(DATE,DATEADD(dd,-(DAY(GETDATE())-1),GETDATE()),101)
				AND CONVERT(DATE,DATEADD(dd,-(DAY(DATEADD(mm,1,GETDATE()))),DATEADD(mm,1,GETDATE())),101)
				AND Sede = '" . $sede . "'";
		return $this->db->query($sql);
	}

	public function get_fecha()
	{
		$sql = "SELECT distinct Fecha from NPS_sedes 
		where CONVERT(DATE,Fecha) between CONVERT(DATE,'01/12/2020') and CONVERT(DATE,'31/12/2020')
		order by Fecha asc";
		return $this->db->query($sql);
	}

	public function validar_nps_sede($fecha, $sede)
	{
		$sql = $this->db->query("SELECT COUNT(*) AS n FROM NPS_sedes
				WHERE CONVERT(DATE,Fecha) = CONVERT(DATE,'" . $fecha . "')
				AND sede = '" . $sede . "'");
		if ($sql->num_rows() > 0) {
			return $sql->row();
		} else {
			return null;
		}
	}

	public function validar_nps_tecnico($fecha, $tecnico)
	{
		$sql = $this->db->query("SELECT COUNT(*) AS n FROM NPS_tecnicos
				WHERE CONVERT(DATE,Fecha) = CONVERT(DATE,'" . $fecha . "')
				AND tecnico = " . $tecnico . "");
		if ($sql->num_rows() > 0) {
			return $sql->row();
		} else {
			return null;
		}
	}

	public function update_nps_sede($data, $condiciones)
	{
		$this->db->where($condiciones);

		if ($this->db->update('NPS_sedes', $data)) {
			return true;
		} else {
			return false;
		}
	}

	public function update_nps_tecnico($data, $condiciones)
	{
		$this->db->where($condiciones);

		if ($this->db->update('NPS_tecnicos', $data)) {
			return true;
		} else {
			return false;
		}
	}

	public function get_calificacion_tecnico($nit)
	{
		$sql = "SELECT * FROM nps_tec WHERE nit_tec='" . $nit . "'";
		return $this->db->query($sql);
	}

	/*INSERT PARA LA TABLA postv_encuestas_gm*/
	public function insert_encuestas_gm($id_encuesta, $sede, $nom_cliente, $nom_tecnico, $nit_tecnico, $VIN, $fecha_evento, $fecha_recibido_enc, $tipo_evento, $modelo_vh, $recomendacion_concesionario, $satisfaccion_concesionario, $satisfaccion_trabajo, $vh_reparado_ok, $recomendacion_marca, $comentarios)
	{
		//print_r($data);
		$sql = "INSERT INTO postv_encuestas_gm (id_encuesta,sede,nom_cliente,nom_tecnico,nit_tecnico,VIN,fecha_evento,fecha_recibido_enc,tipo_evento,modelo_vh,recomendacion_concesionario, satisfaccion_concesionario,satisfaccion_trabajo,vh_reparado_ok,recomendacion_marca,comentarios)
		VALUES('$id_encuesta','$sede','$nom_cliente','$nom_tecnico','$nit_tecnico','$VIN', '$fecha_evento','$fecha_recibido_enc','$tipo_evento','$modelo_vh','$recomendacion_concesionario','$satisfaccion_concesionario','$satisfaccion_trabajo','$vh_reparado_ok','$recomendacion_marca','$comentarios')";

		//print_r($sql);
		$insert = $this->db->query($sql);

		if ($insert) {
			$bien = "bien";
			return $bien;
		} else {
			return FALSE;
		}
	}


	/*INSERT PARA LA TABLA postv_encuestas_gm*/
	public function insert_encuestas_nps_tec($nit_tecnico, $nom_cliente, $fecha_recibido_enc, $recomendacion_concesionario, $sede)
	{
		$sql = "INSERT INTO nps_tec (nit_tec,nombres,fecha_enc,calificacion,sede) VALUES($nit_tecnico,'$nom_cliente','$fecha_recibido_enc',$recomendacion_concesionario,'$sede')";
		//print_r($sql);
		$insert = $this->db->query($sql);

		if ($insert) {
			$bien = "bien";
			return $bien;
		} else {
			return FALSE;
		}
	}


	public function get_postv_encuestas_gm($id)
	{
		$sql = "SELECT COUNT(*) AS n FROM postv_encuestas_gm WHERE id_encuesta = '".$id."'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function get_encuestas_gm($cerrado)

	{
		if ($cerrado == '') {
			return $this->db->query("SELECT vhv.placa,ut.uetd_numero,t.nombres,t.mail,t.celular,v.nombres as vendedor,* FROM  postv_encuestas_gm pgm 
								INNER JOIN v_vh_vehiculos vhv ON pgm.VIN = vhv.serie
								INNER JOIN v_ultima_entrada_taller_datos ut ON ut.uetd_serie = vhv.codigo
								INNER JOIN tall_encabeza_orden teo ON teo.numero = ut.uetd_numero
								INNER JOIN terceros t ON t.nit = teo.nit
								INNER JOIN terceros v ON v.nit = teo.vendedor
								LEFT JOIN postv_pqr_nps qn ON qn.id_fuente = pgm.id_encuesta
								WHERE recomendacion_concesionario <= 6 and (estado_caso = 'Abierto' or estado_caso is null)
								order by fecha_evento desc");
		} else {
			return $this->db->query("SELECT vhv.placa,ut.uetd_numero,t.nombres,t.mail,t.celular,v.nombres as vendedor,* FROM  postv_encuestas_gm pgm 
								INNER JOIN v_vh_vehiculos vhv ON pgm.VIN = vhv.serie
								INNER JOIN v_ultima_entrada_taller_datos ut ON ut.uetd_serie = vhv.codigo
								INNER JOIN tall_encabeza_orden teo ON teo.numero = ut.uetd_numero
								INNER JOIN terceros t ON t.nit = teo.nit
								INNER JOIN terceros v ON v.nit = teo.vendedor
								LEFT JOIN postv_pqr_nps qn ON qn.id_fuente = pgm.id_encuesta
								WHERE recomendacion_concesionario <= 6 and estado_caso in ($cerrado)
								order by fecha_evento desc");
		}
		
		
	}

	public function get_pqr_codiesel($cerrado)
	{
		if ($cerrado == '') {
			return $this->db->query("SELECT id_pqr,pqr.fuente,pqr.fecha,pqr.sede,pqr.placa,t.nombres as cliente,
			pqr.modelo_vh,pqr.ot,t.mail,t.celular as telef,pqr.comentarios,tec.nombres as tecnico,
			ppqr.tipificacion_encuesta,ppqr.comentarios_final_caso,ppqr.tipificacion_cierre,ppqr.estado_caso
			FROM postv_pqr pqr INNER JOIN terceros t ON t.nit = pqr.cliente
			INNER JOIN terceros tec ON tec.nit = pqr.tecnico
			LEFT JOIN postv_pqr_nps ppqr ON pqr.id_pqr = ppqr.id_fuente
			where estado_caso = 'Abierto' or estado_caso is null
			order by pqr.fecha desc
									");
		} else {

			return $this->db->query("SELECT id_pqr,pqr.fuente,pqr.fecha,pqr.sede,pqr.placa,t.nombres as cliente,
			pqr.modelo_vh,pqr.ot,t.mail,t.celular as telef,pqr.comentarios,tec.nombres as tecnico,
			ppqr.tipificacion_encuesta,ppqr.comentarios_final_caso,ppqr.tipificacion_cierre,ppqr.estado_caso
			FROM postv_pqr pqr INNER JOIN terceros t ON t.nit = pqr.cliente
			INNER JOIN terceros tec ON tec.nit = pqr.tecnico
			LEFT JOIN postv_pqr_nps ppqr ON pqr.id_pqr = ppqr.id_fuente
			where estado_caso in ($cerrado)
			order by pqr.fecha desc
			");
		}
		
	}

	public function get_encuestas_codi($cerrado)
	{
		if ($cerrado == '') {
			$sql = "SELECT pes.id,pes.n_orden,pes.fecha,vhv.placa,vhv.codigo,t.nombres as cliente,t.mail,t.celular as telef,pes.pregunta1,pes.pregunta2,pes.pregunta3,pes.pregunta4,pes.pregunta5,vhv.descripcion AS modelo_vh,
			tec.nombres as tecnico,ppqr.tipificacion_encuesta,ppqr.comentarios_final_caso,ppqr.tipificacion_cierre,ppqr.estado_caso,b.descripcion
			FROM posv_encuesta_satisfaccion pes
			INNER JOIN tall_encabeza_orden teo ON pes.n_orden = teo.numero
			INNER JOIN terceros tec ON teo.vendedor = tec.nit_real
			INNER JOIN terceros t ON teo.nit = t.nit_real
			INNER JOIN v_vh_vehiculos vhv ON vhv.codigo = teo.serie
			LEFT JOIN postv_pqr_nps ppqr ON ppqr.id_fuente = pes.id
			INNER JOIN bodegas b ON b.bodega = teo.bodega
			WHERE pes.pregunta1 <= 6 AND CONVERT(DATE,pes.fecha) >= CONVERT(DATE,'2021-11-01')
			and ppqr.estado_caso = 'Abierto'
			order by pes.fecha desc, ppqr.estado_caso asc";
		return $this->db->query($sql);
		} else {
			$sql = "SELECT pes.id,pes.n_orden,pes.fecha,vhv.placa,vhv.codigo,t.nombres as cliente,t.mail,t.celular as telef,pes.pregunta1,pes.pregunta2,pes.pregunta3,pes.pregunta4,pes.pregunta5,vhv.descripcion AS modelo_vh,
		tec.nombres as tecnico,ppqr.tipificacion_encuesta,ppqr.comentarios_final_caso,ppqr.tipificacion_cierre,ppqr.estado_caso,b.descripcion
		FROM posv_encuesta_satisfaccion pes
		INNER JOIN tall_encabeza_orden teo ON pes.n_orden = teo.numero
		INNER JOIN terceros tec ON teo.vendedor = tec.nit_real
		INNER JOIN terceros t ON teo.nit = t.nit_real
		INNER JOIN v_vh_vehiculos vhv ON vhv.codigo = teo.serie
		LEFT JOIN postv_pqr_nps ppqr ON ppqr.id_fuente = pes.id
		INNER JOIN bodegas b ON b.bodega = teo.bodega
		WHERE pes.pregunta1 <= 6 AND CONVERT(DATE,pes.fecha) >= CONVERT(DATE,'2021-11-01') and estado_caso in ($cerrado) 
		order by pes.fecha desc";
		return $this->db->query($sql);
			
		}
		
	}


	//nuevo
	public function get_encuesta_qr($cerrado)
	{
		if ($cerrado == '') {
			$sql = "SELECT pes.id,teo.numero,pes.fecha,vh.placa,vh.codigo,t.nombres as cliente,t.mail,t.celular as telef,pes.pregunta1,pes.pregunta2,pes.pregunta3,pes.pregunta4,pes.pregunta5,vh.descripcion AS modelo_vh,
		tec.nombres as tecnico,ppqr.tipificacion_encuesta,ppqr.comentarios_final_caso,ppqr.tipificacion_cierre,ppqr.estado_caso,b.descripcion
		FROM v_ultima_entrada_taller_datos a 
		inner join tall_encabeza_orden teo on uetd_numero=teo.numero AND uetd_serie=teo.serie
		inner join v_vh_vehiculos vh on a.uetd_serie=vh.serie
		inner join postv_encuesta_satisfaccion_qr pes on vh.placa=pes.placa
		INNER JOIN terceros tec ON teo.vendedor = tec.nit_real
		INNER JOIN terceros t ON teo.nit = t.nit_real
		LEFT JOIN postv_pqr_nps ppqr ON ppqr.id_fuente = pes.id
		INNER JOIN bodegas b ON b.bodega = teo.bodega
		WHERE pes.pregunta1 <= 6 AND CONVERT(DATE,pes.fecha) >= CONVERT(DATE,'2021-11-01') and estado_caso = 'Abierto' or estado_caso is null ";
		return $this->db->query($sql);
		} else {
			$sql = "SELECT pes.id,teo.numero,pes.fecha,vh.placa,vh.codigo,t.nombres as cliente,t.mail,t.celular as telef,pes.pregunta1,pes.pregunta2,pes.pregunta3,pes.pregunta4,pes.pregunta5,vh.descripcion AS modelo_vh,
		tec.nombres as tecnico,ppqr.tipificacion_encuesta,ppqr.comentarios_final_caso,ppqr.tipificacion_cierre,ppqr.estado_caso,b.descripcion
		FROM v_ultima_entrada_taller_datos a 
		inner join tall_encabeza_orden teo on uetd_numero=teo.numero AND uetd_serie=teo.serie
		inner join v_vh_vehiculos vh on a.uetd_serie=vh.serie
		inner join postv_encuesta_satisfaccion_qr pes on vh.placa=pes.placa
		INNER JOIN terceros tec ON teo.vendedor = tec.nit_real
		INNER JOIN terceros t ON teo.nit = t.nit_real
		LEFT JOIN postv_pqr_nps ppqr ON ppqr.id_fuente = pes.id
		INNER JOIN bodegas b ON b.bodega = teo.bodega
		WHERE pes.pregunta1 <= 6 AND CONVERT(DATE,pes.fecha) >= CONVERT(DATE,'2021-11-01') and estado_caso in ($cerrado)";
		return $this->db->query($sql);
		}
		
		
	}


	public function insert_pqr($data)
	{
		if ($this->db->insert('postv_pqr', $data)) {
			return true;
		} else {
			return false;
		}
	}

	public function insert_pqr_nps($data)
	{
		if ($this->db->insert('postv_pqr_nps', $data)) {
			return true;
		} else {
			return false;
		}
	}


	public function update_pqr_nps($id, $data)
	{
		$this->db->where('id', $id);

		if ($this->db->update('postv_pqr_nps', $data)) {
			return true;
		} else {
			return false;
		}
	}

	public function get_pqr_nps_id($data)
	{
		$this->db->select('id');
		$this->db->from('postv_pqr_nps');
		$this->db->where('fuente', $data['fuente']);
		$this->db->where('id_fuente', $data['id_fuente']);
		$res = $this->db->get();

		if ($res->num_rows() > 0) {
			return $res;
		} else {
			return null;
		}
	}

	public function insert_verbs($data)
	{
		if ($this->db->insert('postv_pqr_comentarios', $data)) {
			return true;
		} else {
			return false;
		}
	}


	/*metodo para listar listado de tecnicos */

	public  function traertecnicos()
	{
		return  $this->db->query("SELECT t.nit, t.nombres FROM tall_operarios t_o INNER JOIN terceros t ON t.nit = t_o.nit WHERE LEN(t.nit) > 5");
	}
	/*  */
	public function Informe_encuesta_satisfaccion_by_nomFecha($nombre,$año,$mes)
	{
		return $this->db->query("SELECT  te.numero,c.nombres,pregunta1,pregunta2,pregunta3,pregunta4,pregunta5 
		FROM postv_encuesta_satisfaccion_qr pes inner join referencias_imp r
		on pes.placa=r.placa
		inner join v_ultima_entrada_taller_datos uet on r.codigo=uet.uetd_serie
		inner join tall_encabeza_orden te on uet.uetd_numero=te.numero
		inner join terceros t on te.vendedor=t.nit
		inner join terceros c on te.nit=c.nit
		where MONTH(CONVERT(DATE,pes.fecha)) = $mes
		and YEAR(CONVERT(DATE,pes.fecha)) = $año and pes.bod in (1,9,11,21,7,6,19,8,14,16,22) and t.nombres = '".$nombre."'");
	}
}
