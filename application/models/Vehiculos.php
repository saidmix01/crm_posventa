<?php

/**
 * 
 */
class Vehiculos extends CI_Model
{

	public function crear_vehiculo($placa, $id_usu, $orden, $tipo)
	{
		$sql = "INSERT INTO postv_vehiculos(placa,fecha_ingreso,orden,autorizacion,usuario,tipo) 
		VALUES('$placa',SYSDATETIME(),'$orden','SI','$id_usu','$tipo')";
		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}

	public function crear_ord_gral($serial, $id_usu, $descripcion, $tipo)
	{
		$sql = "INSERT INTO postv_vehiculos(placa,fecha_ingreso,autorizacion,usuario,tipo,contenido,orden) 
		VALUES('$serial',SYSDATETIME(),'SI','$id_usu','$tipo','$descripcion','sin orden')";
		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}

	public function crear_tot($placa, $id_usu, $orden, $tipo, $proveedor, $contenido)
	{
		$sql = "INSERT INTO postv_vehiculos(placa,fecha_ingreso,orden,autorizacion,usuario,fecha_salida,tipo,proveedor,contenido) 
		VALUES('$placa',SYSDATETIME(),'$orden','SI','$id_usu',SYSDATETIME(),'$tipo','$proveedor','$contenido')";
		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}

	/*Vehiculos*/

	/*public function listar_vehiculo()
	{
		return $this->db->query("
			SELECT a.bodega, a.numero, b.placa, bo.descripcion, pv.fecha_ingreso
			FROM tall_encabeza_orden a 
			INNER JOIN v_vh_vehiculos b ON a.serie = b.serie
			LEFT JOIN postv_vehiculos pv ON a.numero = pv.orden
			INNER JOIN bodegas bo ON bo.bodega = a.bodega
			WHERE a.facturada = 0 AND fecha >= '01/01/2020'
			AND a.numero NOT IN(SELECT orden from postv_vehiculos)
			ORDER BY a.numero DESC
			");
		}*/

	public function listar_vehiculo($usu)
	{
		return $this->db->query("
				SELECT orden, placa,fecha_ingreso, fecha_salida, fecha_reingreso FROM postv_vehiculos
				WHERE usuario = '$usu'
				AND autorizacion = 'SI'
				AND tipo = 'vehiculo'
				AND fecha_salida is NULL
				");
	}

	public function listar_vehiculo_all()
	{
		return $this->db->query("
				SELECT orden, placa FROM postv_vehiculos
				WHERE autorizacion = 'SI'
				AND tipo = 'vehiculo'
				");
	}

	public function listar_vehiculo_bodega($bodega)
	{
		return $this->db->query("
				SELECT a.bodega, a.numero, b.placa, bo.descripcion, pv.fecha_ingreso
				FROM tall_encabeza_orden a 
				INNER JOIN v_vh_vehiculos b ON a.serie = b.serie
				LEFT JOIN postv_vehiculos pv ON a.numero = pv.orden
				INNER JOIN bodegas bo ON bo.bodega = a.bodega
				WHERE a.facturada = 0 AND fecha >= '01/01/2020'
				AND a.numero NOT IN(SELECT orden from postv_vehiculos)
				AND a.bodega = $bodega
				ORDER BY a.numero DESC
				");
	}

	public function busca_vehiculo($orden)
	{
		return $this->db->query("
				SELECT a.bodega, a.numero, b.placa, bo.descripcion, pv.fecha_ingreso
				FROM tall_encabeza_orden a 
				INNER JOIN v_vh_vehiculos b ON a.serie = b.serie
				LEFT JOIN postv_vehiculos pv ON a.numero = pv.orden
				INNER JOIN bodegas bo ON bo.bodega = a.bodega
				WHERE a.facturada = 0 AND fecha >= '01/01/2020'
				AND a.numero NOT IN(SELECT orden from postv_vehiculos)
				AND a.numero = $orden
				ORDER BY a.numero DESC
				");
	}

	/*TOT*/
	/*public function listar_tot()
	{
		return $this->db->query("
			SELECT a.numero, b.placa, a.operacion, bo.descripcion, pv.fecha_ingreso
			FROM tall_documentos_lin a INNER JOIN v_vh_vehiculos b on a.serie = b.codigo
                           INNER JOIN tall_tempario c  ON c.operacion = a.operacion
                           LEFT JOIN postv_vehiculos pv ON a.numero = pv.orden
                           INNER JOIN bodegas bo ON bo.bodega = a.bodega
			WHERE   a.fec >= '01/01/2020' AND c.descripcion LIKE ('%TOT%')
			AND a.numero NOT IN(SELECT orden from postv_vehiculos)
			ORDER BY a.numero DESC
			");
		}*/

	public function listar_tot($bod,$tipo)
	//$FechaActual  = date('Y-m-d');

	{
		$sql = "";
		if ($tipo == 1) {
			$sql = "SELECT usede.idsede, pv.orden, vhv.placa,vhv.descripcion,
				CONVERT(CHAR(10),pv.fecha_ingreso,111) AS  fecha_ingreso,
				CONVERT(CHAR(10),pv.fecha_salida,111) AS fecha_salida, 
				CONVERT(CHAR(10),pv.fecha_reingreso,111) AS fecha_reingreso,
				proveedor,pv.id_vehiculo,pv.contenido FROM postv_vehiculos pv
				
				INNER JOIN tall_encabeza_orden teo ON teo.numero = pv.orden
				INNER JOIN v_vh_vehiculos vhv ON vhv.codigo = teo.serie
				INNER JOIN w_sist_usuarios us ON us.id_usuario = pv.usuario
				INNER JOIN sw_usuariosede usede ON us.id_usuario = usede.idusuario AND teo.bodega = usede.idsede
				
				WHERE teo.bodega IN(" . $bod . ") AND pv.autorizacion = 'SI' AND pv.tipo = 'tot' 
			  	AND fecha_reingreso is null";
		}elseif($tipo == 2){
			$sql = "SELECT usede.idsede, pv.orden, vhv.placa,vhv.descripcion,
				CONVERT(CHAR(10),pv.fecha_ingreso,111) AS  fecha_ingreso,
				CONVERT(CHAR(10),pv.fecha_salida,111) AS fecha_salida, 
				CONVERT(CHAR(10),pv.fecha_reingreso,111) AS fecha_reingreso,
				proveedor,pv.id_vehiculo,pv.contenido FROM postv_vehiculos pv
				
				INNER JOIN tall_encabeza_orden teo ON teo.numero = pv.orden
				INNER JOIN v_vh_vehiculos vhv ON vhv.codigo = teo.serie
				INNER JOIN w_sist_usuarios us ON us.id_usuario = pv.usuario
				INNER JOIN sw_usuariosede usede ON us.id_usuario = usede.idusuario AND teo.bodega = usede.idsede
				
				WHERE teo.bodega IN(" . $bod . ") AND pv.autorizacion = 'SI' AND pv.tipo = 'tot'";
		}
		//echo $sql;
		return $this->db->query($sql);
	}
	/*
	SELECT usede.idsede, pv.orden, vhv.placa,vhv.descripcion,
		pv.fecha_ingreso,
		 pv.fecha_salida,
		 pv.fecha_reingreso,proveedor,
		 
		 pv.id_vehiculo,pv.contenido FROM postv_vehiculos pv
		INNER JOIN tall_encabeza_orden teo ON teo.numero = pv.orden
		INNER JOIN v_vh_vehiculos vhv ON vhv.codigo = teo.serie
		INNER JOIN w_sist_usuarios us ON us.id_usuario = pv.usuario
		INNER JOIN sw_usuariosede usede ON us.id_usuario = usede.idusuario AND teo.bodega = usede.idsede
		WHERE teo.bodega IN(" . $bod . ") AND pv.autorizacion = 'SI' AND pv.tipo = 'tot' 
		
			--AND fecha_reingreso is NULL
			");
	 */

	public function listar_tot_all()
	{
		return $this->db->query("
				SELECT orden, placa FROM postv_vehiculos
				WHERE autorizacion = 'SI'
				AND tipo = 'tot'
				");
	}

	/*public function listar_tot_bodega($bodega)
	{
		return $this->db->query("
			SELECT a.numero, b.placa, a.operacion, bo.descripcion, pv.fecha_ingreso
			FROM tall_documentos_lin a INNER JOIN v_vh_vehiculos b on a.serie = b.codigo
                           INNER JOIN tall_tempario c  ON c.operacion = a.operacion
                           LEFT JOIN postv_vehiculos pv ON a.numero = pv.orden
                           INNER JOIN bodegas bo ON bo.bodega = a.bodega
			WHERE   a.fec >= '01/01/2020' AND c.descripcion LIKE ('%TOT%')
			AND a.numero NOT IN(SELECT orden from postv_vehiculos)
			AND a.bodega = $bodega
			ORDER BY a.numero DESC
			");
		}*/

	/*public function busca_tot($orden)
	{
		return $this->db->query("
			SELECT a.numero, b.placa, a.operacion, bo.descripcion, pv.fecha_ingreso
			FROM tall_documentos_lin a INNER JOIN v_vh_vehiculos b on a.serie = b.codigo
                           INNER JOIN tall_tempario c  ON c.operacion = a.operacion
                           LEFT JOIN postv_vehiculos pv ON a.numero = pv.orden
                           INNER JOIN bodegas bo ON bo.bodega = a.bodega
			WHERE   a.fec >= '01/01/2020' AND c.descripcion LIKE ('%TOT%')
			AND a.numero NOT IN(SELECT orden from postv_vehiculos)
			AND a.numero = $orden
			ORDER BY a.numero DESC
			");
		}*/

	/*Ordenes General*/
	public function listar_ord_gral($usu)
	{
		return $this->db->query("
				SELECT contenido, placa, fecha_ingreso, fecha_salida, fecha_reingreso FROM postv_vehiculos
				WHERE usuario = '$usu'
				AND autorizacion = 'SI'
				AND tipo = 'Orden General'
				AND fecha_salida is NULL
				");
	}

	public function listar_ord_gral_all()
	{
		return $this->db->query("
				SELECT orden, placa FROM postv_vehiculos
				WHERE autorizacion = 'SI'
				AND tipo = 'Orden General'
				");
	}

	/*public function listar_ord_gral_bodega($bodega)
	{
		return $this->db->query("
			SELECT a.numero, b.placa, a.operacion, bo.descripcion, pv.fecha_ingreso
			FROM tall_documentos_lin a INNER JOIN v_vh_vehiculos b on a.serie = b.codigo
                           INNER JOIN tall_tempario c  ON c.operacion = a.operacion
                           LEFT JOIN postv_vehiculos pv ON a.numero = pv.orden
                           INNER JOIN bodegas bo ON bo.bodega = a.bodega
			WHERE   a.fec >= '01/01/2020' AND c.descripcion LIKE ('%TOT%')
			AND a.numero NOT IN(SELECT orden from postv_vehiculos)
			AND a.bodega = $bodega
			ORDER BY a.numero DESC
			");
		}*/

	/*public function busca_ord_gral($orden)
	{
		return $this->db->query("
			SELECT a.numero, b.placa, a.operacion, bo.descripcion, pv.fecha_ingreso
			FROM tall_documentos_lin a INNER JOIN v_vh_vehiculos b on a.serie = b.codigo
                           INNER JOIN tall_tempario c  ON c.operacion = a.operacion
                           LEFT JOIN postv_vehiculos pv ON a.numero = pv.orden
                           INNER JOIN bodegas bo ON bo.bodega = a.bodega
			WHERE   a.fec >= '01/01/2020' AND c.descripcion LIKE ('%TOT%')
			AND a.numero NOT IN(SELECT orden from postv_vehiculos)
			AND a.numero = $orden
			ORDER BY a.numero DESC
			");
		}*/
	/************/

	/*Repuestos*/

	public function listar_repuestos()
	{
		return $this->db->query("
				SELECT a.numero, b.placa, bo.descripcion, pv.fecha_ingreso
				FROM tall_documentos_lin a INNER JOIN v_vh_vehiculos b on a.serie = b.codigo
				INNER JOIN referencias c ON c.codigo = a.operacion
				LEFT JOIN postv_vehiculos pv ON a.numero = pv.orden
				INNER JOIN bodegas bo ON bo.bodega = a.bodega
				WHERE  a.fec >= '01/04/2020'
				AND a.numero NOT IN(SELECT orden from postv_vehiculos)
				ORDER BY a.numero DESC
				");
	}

	public function listar_repuestos_bodega($bodega)
	{
		return $this->db->query("
				SELECT a.numero, b.placa, bo.descripcion, pv.fecha_ingreso
				FROM tall_documentos_lin a INNER JOIN v_vh_vehiculos b on a.serie = b.codigo
				INNER JOIN referencias c ON c.codigo = a.operacion
				LEFT JOIN postv_vehiculos pv ON a.numero = pv.orden
				INNER JOIN bodegas bo ON bo.bodega = a.bodega
				WHERE  a.fec >= '01/04/2020'
				AND a.numero NOT IN(SELECT orden from postv_vehiculos)
				AND a.bodega = $bodega
				ORDER BY a.numero DESC
				");
	}

	public function busca_repuestos($orden)
	{
		return $this->db->query("
				SELECT a.numero, b.placa, bo.descripcion, pv.fecha_ingreso
				FROM tall_documentos_lin a INNER JOIN v_vh_vehiculos b on a.serie = b.codigo
				INNER JOIN referencias c ON c.codigo = a.operacion
				LEFT JOIN postv_vehiculos pv ON a.numero = pv.orden
				INNER JOIN bodegas bo ON bo.bodega = a.bodega
				WHERE  a.fec >= '01/04/2020'
				AND a.numero NOT IN(SELECT orden from postv_vehiculos)
				AND a.numero = $orden
				ORDER BY a.numero DESC
				");
	}

	/*public function marcar_salida($orden, $fecha)
	{	$estado = "SI";
		$datos = array('fecha_salida' => 'SYSDATETIME()', 'autorizacion' => $estado);
		$this->db->where('orden',$orden);
		$this->db->update('postv_vehiculos',$datos);
	}*/

	public function marcar_salida($orden, $fecha)
	{
		return $this->db->query("UPDATE postv_vehiculos SET fecha_salida = SYSDATETIME(), autorizacion = 'SI' WHERE id_vehiculo = '$orden'");
	}

	public function marcar_reingreso($orden)
	{
		return $this->db->query("UPDATE postv_vehiculos SET fecha_reingreso = SYSDATETIME() WHERE id_vehiculo = '$orden'");
	}

	public function listat_bodegas()
	{
		return $this->db->query("SELECT bodega, descripcion FROM bodegas");
	}

	public function ver_info_vehiculo($orden)
	{
		return $this->db->query("	
			SELECT t.nombres, pv.placa, CONVERT(VARCHAR,pv.fecha_ingreso,22) AS 'fecha_ingreso', pv.orden
			FROM postv_vehiculos pv
			INNER JOIN w_sist_usuarios u ON pv.usuario = u.id_usuario
			INNER JOIN terceros t ON t.nit = u.nit_usuario
			WHERE pv.autorizacion = 'SI'
			AND pv.tipo = 'vehiculo'
			AND pv.fecha_salida is NULL
			AND pv.orden = '$orden'
			ORDER BY fecha_ingreso
			");
	}

	public function ver_info_vehiculo_placa($placa)
	{
		return $this->db->query("	
			SELECT t.nombres, pv.placa, CONVERT(VARCHAR,pv.fecha_ingreso,22) AS 'fecha_ingreso', pv.orden
			FROM postv_vehiculos pv
			INNER JOIN w_sist_usuarios u ON pv.usuario = u.id_usuario
			INNER JOIN terceros t ON t.nit = u.nit_usuario
			WHERE pv.autorizacion = 'SI'
			AND pv.tipo = 'vehiculo'
			AND pv.fecha_salida is NULL
			AND pv.placa = '$placa'
			ORDER BY fecha_ingreso DESC
			");
	}

	public function ver_info_tot($orden)
	{
		return $this->db->query("	
			SELECT t.nombres, pv.placa, CONVERT(VARCHAR,pv.fecha_ingreso,22) AS 'fecha_ingreso', pv.orden, pv.proveedor, pv.contenido, pv.fecha_salida
			FROM postv_vehiculos pv
			INNER JOIN w_sist_usuarios u ON pv.usuario = u.id_usuario
			INNER JOIN terceros t ON t.nit = u.nit_usuario
			WHERE pv.autorizacion = 'SI'
			AND pv.tipo = 'tot'
			AND pv.fecha_reingreso is NULL
			AND pv.orden = '$orden'
			ORDER BY fecha_ingreso DESC
			");
	}

	public function ver_info_tot_placa($placa)
	{
		return $this->db->query("	
			SELECT t.nombres, pv.placa, CONVERT(VARCHAR,pv.fecha_ingreso,22) AS 'fecha_ingreso', pv.orden, pv.proveedor, pv.contenido, pv.fecha_salida
			FROM postv_vehiculos pv
			INNER JOIN w_sist_usuarios u ON pv.usuario = u.id_usuario
			INNER JOIN terceros t ON t.nit = u.nit_usuario
			WHERE pv.autorizacion = 'SI'
			AND pv.tipo = 'tot'
			AND pv.fecha_reingreso is NULL
			AND pv.placa = '$placa'
			ORDER BY fecha_ingreso DESC
			");
	}

	public function ver_info_repuesto($orden)
	{
		return $this->db->query("	
			SELECT t.nombres, pv.placa, CONVERT(VARCHAR,pv.fecha_ingreso,22) AS 'fecha_ingreso', pv.orden
			FROM postv_vehiculos pv
			INNER JOIN w_sist_usuarios u ON pv.usuario = u.id_usuario
			INNER JOIN terceros t ON t.nit = u.nit_usuario
			WHERE pv.orden = '$orden'
			AND pv.autorizacion = 'NO'
			AND pv.tipo = 'repuesto'
			ORDER BY fecha_ingreso DESC
			");
	}

	public function info_vehiculo()
	{
		return $this->db->query("	
			SELECT t.nombres, pv.placa, CONVERT(VARCHAR,pv.fecha_ingreso,22) AS 'fecha_ingreso', pv.orden, pv.id_vehiculo
			FROM postv_vehiculos pv
			INNER JOIN w_sist_usuarios u ON pv.usuario = u.id_usuario
			INNER JOIN terceros t ON t.nit = u.nit_usuario
			WHERE pv.autorizacion = 'SI'
			AND pv.tipo = 'vehiculo'
			AND pv.fecha_salida is NULL
			AND u.perfil_postventa IN ('1','2','8','9')
			ORDER BY fecha_ingreso DESC
			");
	}

	public function info_ord_gral()
	{
		return $this->db->query("	
			SELECT t.nombres, pv.placa, CONVERT(VARCHAR,pv.fecha_ingreso,22) AS 'fecha_ingreso', pv.contenido, pv.id_vehiculo
			FROM postv_vehiculos pv
			INNER JOIN w_sist_usuarios u ON pv.usuario = u.id_usuario
			INNER JOIN terceros t ON t.nit = u.nit_usuario
			WHERE pv.autorizacion = 'SI'
			AND pv.tipo = 'Orden General'
			AND pv.fecha_salida is NULL
			AND u.perfil_postventa IN ('1','2','8','9')
			ORDER BY fecha_ingreso DESC
			");
	}

	public function info_tot($bod)
	{
		return $this->db->query("	
			SELECT t.nombres, vhv.placa, CONVERT(VARCHAR,pv.fecha_ingreso,22) AS 'fecha_ingreso', pv.orden, pv.proveedor, pv.contenido, pv.fecha_salida, pv.id_vehiculo
			FROM postv_vehiculos pv
			INNER JOIN w_sist_usuarios u ON pv.usuario = u.id_usuario
			INNER JOIN terceros t ON t.nit = u.nit_usuario
			INNER JOIN tall_encabeza_orden teo ON teo.numero = pv.orden
			INNER JOIN v_vh_vehiculos vhv ON vhv.codigo = teo.serie
			INNER JOIN w_sist_usuarios us ON us.id_usuario = pv.usuario
			INNER JOIN sw_usuariosede usede ON us.id_usuario = usede.idusuario 
			AND teo.bodega = usede.idsede
			WHERE pv.autorizacion = 'SI'
			AND teo.bodega IN(" . $bod . ")
			AND pv.tipo = 'tot'
			AND pv.fecha_reingreso is NULL
			ORDER BY fecha_ingreso DESC
			");
	}

	public function info_tot_recibo($id_v)
	{
		return $this->db->query("	
			SELECT TOP 1 t.nombres, vhv.placa,vhv.descripcion, CONVERT(VARCHAR,pv.fecha_ingreso,22) AS 'fecha_ingreso', pv.orden, pv.proveedor, pv.contenido, pv.fecha_salida, pv.id_vehiculo, ase.nombres AS aseguradora
			FROM postv_vehiculos pv
			INNER JOIN w_sist_usuarios u ON pv.usuario = u.id_usuario
			INNER JOIN terceros t ON t.nit = u.nit_usuario
			INNER JOIN tall_encabeza_orden teo ON teo.numero = pv.orden
			INNER JOIN terceros ase ON ase.nit = teo.aseguradora
			INNER JOIN v_vh_vehiculos vhv ON vhv.codigo = teo.serie
			WHERE pv.autorizacion = 'SI'
			AND pv.tipo = 'tot'
			--AND pv.fecha_reingreso is NULL
			AND pv.id_vehiculo IN (" . $id_v . ")
			ORDER BY fecha_ingreso DESC
			");
	}

	public function get_ultimo_id_vh($orden)
	{
		$sql = "SELECT TOP 1 id_vehiculo FROM postv_vehiculos WHERE orden = '" . $orden . "' ORDER BY id_vehiculo DESC";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function info_repuesto()
	{
		return $this->db->query("	
			SELECT t.nombres, pv.placa, CONVERT(VARCHAR,pv.fecha_ingreso,22) AS 'fecha_ingreso', pv.orden
			FROM postv_vehiculos pv
			INNER JOIN w_sist_usuarios u ON pv.usuario = u.id_usuario
			INNER JOIN terceros t ON t.nit = u.nit_usuario
			WHERE pv.autorizacion = 'SI'
			AND pv.tipo = 'repuesto'
			ORDER BY fecha_ingreso
			");
	}




	/*public function cambiar_estado_no($id_vehi)
	{
		$estado = "NO";
		$datos = array('autorizacion' => $estado);
		$this->db->where('id_vehiculo',$id_vehi);
		$this->db->update('postv_vehiculos',$datos);
	}*/
}
