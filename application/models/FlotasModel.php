<?php 

/**
 * 
 */
class FlotasModel extends CI_Model
{
	public $dtz;
	public $dt;

	function __construct()
	{
		$this->dtz = new DateTimeZone("America/Bogota");
		$this->dt = new DateTime("now", $this->dtz);
	}
	
	public function get_flotas_resumen()
	{
		$sql="SELECT nit_cliente, cliente, sum(total_vehiculos) as vehiculos, SUM(total_vendedores) as asesores, SUM(trabaja_codiesel) as trabaja_codiesel
			from (
			select nit_cliente, cliente, COUNT(cliente) as total_vehiculos, COUNT(distinct vendedor) as total_vendedores,
			trabaja_codiesel=case when trabaja_codiesel=1 then count(distinct vendedor) else 0 end
			from v_detalle_flotas
			group by nit_cliente, cliente,trabaja_codiesel
			)c
			group by nit_cliente, cliente";
		$result = $this->db->query($sql);
		return $result->num_rows() > 0 ? $result : null;
	}

	public function get_flotas_detallado($nit_cliente)
	{
		$sql = "SELECT * FROM v_detalle_flotas WHERE nit_cliente=".$nit_cliente;
		$result = $this->db->query($sql);
	    return $result->num_rows() > 0 ? $result : null;
	}

	public function insert_flota($nitCliente, $placa, $nombreFlota, $asesor, $periodicidad, $op=1)
	{
		if ($op==1) {
			$data = array(
				'nit' => $nitCliente,
				'placa' => $placa,
				'asesor' => $asesor,
				'nombre_flota' => $nombreFlota,
				'periodicidad' => $periodicidad,
				'activo' => 1
			);
			
			$result = $this->db->insert('flotas_intranet', $data);
			$this->insert_registro_estado_vehiculo($nitCliente, $placa, "asignación del vehículo a la flota", 1);
		} else {
			$data = array(
				'observacion_desv' => '',
				'activo' => 1
			);
			
			$where = array(
				'nit'=> $nitCliente,
				'placa'=> $placa
			);
			$result = $this->db->update('flotas_intranet', $data, $where);
			$this->insert_registro_estado_vehiculo($nitCliente, $placa, "se activa el vehículo", 1);
		}
		
	    return $result ? 1 : 0;
	}

	public function insert_registro_estado_vehiculo($nitCliente, $placa, $observacion, $activo)
	{
		$data = array(
			'nit' => $nitCliente,
			'placa' => $placa,
			'asesor' => $this->session->userdata('nit_user'),
			'observacion' => $observacion,
			'activo' => $activo
		);
		
		$result = $this->db->insert('flotas_intranet_log_estados', $data);
	    return $result ? 1 : 0;
	}

	public function get_nombres_flotas()
	{
		$sql = "SELECT id_flota,nombre_flota FROM flotas_intranet";
		$result = $this->db->query($sql);
	    return $result->num_rows() > 0 ? $result : null;
	}

	public function val_placa_flota($nitCliente,$placa)
	{
		$sql = "SELECT * FROM flotas_intranet WHERE placa ='$placa' AND nit=$nitCliente AND activo=1";
		$result = $this->db->query($sql);
	    return $result->num_rows() > 0 ? 1 : null;
	}

	public function get_nombre_cliente($nitCliente)
	{
		$sql = "SELECT nombres FROM terceros WHERE nit=".$nitCliente;
		$result = $this->db->query($sql);
		$row = $result->row(0);
	    return $result->num_rows() > 0 ? $row->nombres : null;
	}

	public function get_clientes_by_nombre($cliente)
	{
		$sql = "SELECT nit as 'id',nombres as 'text' FROM terceros WHERE nombres like '%".$cliente."%'";
		$result = $this->db->query($sql);
	    return $result->num_rows() > 0 ? $result : null;
	}

	public function get_data_flota($nitCliente)
	{
		$sql = "SELECT asesor,nombre_flota FROM flotas_intranet WHERE nit=".$nitCliente;
		$result = $this->db->query($sql);
	    return $result->num_rows() > 0 ? $result : null;
	}

	public function contar_flotas_cliente($nitCliente,$op = 0)
	{
		$sql = "SELECT asesor,nombre_flota FROM flotas_intranet WHERE nit=$nitCliente GROUP BY asesor,nombre_flota";
		$result = $this->db->query($sql);
	    return $op == 0 ? $result->num_rows() : $result;
	}	

	public function get_data_flota_cliente($nitCliente)
	{
		$nombre = $this->get_nombre_cliente($nitCliente);
		if ($nombre != null) {
			$res = $this->get_data_flota($nitCliente);
			$asesor = 0;
			$nombre_flota = 0;
			if ($res != null) {
				$numFlotas = $this->contar_flotas_cliente($nitCliente);
				$row = $res->row(0);
				$asesor       = $numFlotas < 2 ? $row->asesor : 1;
				$nombre_flota = $numFlotas < 2 ? $row->nombre_flota : 1;
			}
			return $res != null ? json_encode(array(1,$nombre,$asesor,$nombre_flota)) : json_encode(array(2,$nombre,$asesor,$nombre_flota));
		}
	    return 3;
	}

	public function get_vehiculos_flotas_cliente($nit_cliente,$op=1)
	{
		$and = $op==1 ? 'AND activo=1' : 'ORDER BY activo DESC';
		$sql = "SELECT * FROM flotas_intranet WHERE nit=$nit_cliente $and";
		$result = $this->db->query($sql);
	    return $result->num_rows() > 0 ? $result : null;
	}

	public function del_vehiculos_flotas_cliente($id_flota,$observacion)
	{
		$data = array(
			'observacion_desv' => $observacion,
			'activo' => 0
		);
		
		$this->db->where('id_flota', $id_flota);
		$result = $this->db->update('flotas_intranet', $data);

		$this->db->select('nit, placa, asesor');
		$this->db->from('flotas_intranet');
		$this->db->where('id_flota', $id_flota);
		$res = $this->db->get();
		$row = $res->row();
		$this->insert_registro_estado_vehiculo($row->nit, $row->placa, "se inactiva el vehículo \nmotivo: ".$observacion, 0);
	    return $result ? 1 : 0;
	}

	public function desactivar_vehiculo($nitCliente, $placa, $nombreFlota, $asesor, $observacion)
	{		
		$data = array(
			'nit' => $nitCliente,
			'placa' => $placa,
			'asesor' => $asesor,
			'comisiona' => 0,
			'nombre_flota' => $nombreFlota,
			'observacion_desv' => $observacion,
			'activo' => 0
		);
		
		$result = $this->db->insert('flotas_intranet', $data);
		$this->insert_registro_estado_vehiculo($nitCliente, $placa, "se inactiva el vehículo \nmotivo: ".$observacion, 0);
	    return $result ? 1 : 0;
	}

	public function get_flotas_by_nit($nitCliente)
	{
		$sql = "SELECT nit,nombre_flota,ISNULL((select COUNT(nit_cliente) from flotas_intranet_contactos where nit_cliente=$nitCliente),0) as contacto
			FROM flotas_intranet 
			WHERE nit=$nitCliente
			GROUP BY nit,nombre_flota ";
		$result = $this->db->query($sql);
	    return $result->num_rows() > 0 ? $result : null;
	}

	public function insert_contactos_flota($nitFlota, $flota, $nombres, $cargos, $correos, $telefonos, $direcciones)
	{
		$nombre = explode('_',$nombres);
		$cargo  = explode('_',$cargos);
		$correo = explode('_',$correos);
		$telefono = explode('_',$telefonos);
		$direccion = explode('_',$direcciones);
		$flag = 0;
		for ($i=0; $i < count($nombre); $i++) {
			$data = array(
				'nombre_flota' => $flota,
				'nit_cliente'  => $nitFlota,
				'nombre'       => $nombre[$i],
				'cargo'        => $cargo[$i],
				'direccion'    => $direccion[$i],
				'telefono'     => $telefono[$i],
				'correo'       => $correo[$i]
			);
			
			$result = $this->db->insert('flotas_intranet_contactos', $data);
			$result ? $flag++ : $flag;
		}
	    return $flag == count($nombre) ? 1 : 0;
	}

	public function get_contactos_flota($nitCliente, $flota)
	{
		$sql = "SELECT * FROM flotas_intranet_contactos WHERE nit_cliente=$nitCliente AND nombre_flota='$flota'";
		$result = $this->db->query($sql);
	    return $result->num_rows() > 0 ? $result : null;
	}

	public function get_log_estado_flota($nitCliente, $placa)
	{
		$sql = "SELECT *,convert(varchar, fecha, 100) as fecha_estado FROM flotas_intranet_Log_estados WHERE nit=$nitCliente AND placa='$placa' ORDER BY fecha DESC";
		$result = $this->db->query($sql);
	    return $result->num_rows() > 0 ? $result : null;
	}

	public function get_flotas_ingresadas()
	{
		$sql = "SELECT nombre_flota, COUNT(nit) as cant_vh FROM flotas_intranet GROUP BY nombre_flota";
		$result = $this->db->query($sql);
	    return $result->num_rows() > 0 ? $result : null;
	}

	public function get_flotas_ingresada_detallada($flota)
	{
		$sql = "SELECT f.*,t.nombres FROM flotas_intranet f INNER JOIN terceros t ON t.nit=f.asesor WHERE f.nombre_flota='$flota'";
		$result = $this->db->query($sql);
	    return $result->num_rows() > 0 ? $result : null;
	}

	public function get_flotas_aprobar_dir_flotas()
	{
		$sql = "SELECT f.*,CONVERT(DATE,ue.Fecha_Ultima_Entrada) as fec_ultima_entrada, d.fecha_venta from flotas_intranet f  
		INNER JOIN v_vh_vehiculos vh on f.placa=vh.placa
		LEFT JOIN v_ultima_entrada_4_5 ue ON vh.codigo=ue.serie
		LEFT JOIN (SELECT codigo, CONVERT(date,dl.fec) as fecha_venta FROM documentos_lin dl where sw=1 and tipo in ('KV','VA','VC','VG','VR','VX','WV') and cantidad_devuelta is null)d
		ON vh.codigo=d.codigo
		where f.placa in (SELECT v.placa
					FROM vh_documentos_ped dp 
					INNER JOIN (SELECT codigo, CONVERT(date,fecha_hora_evento) as fecha_matricula from vh_eventos_vehiculos where evento=55) m
					ON dp.codigo=m.codigo
					INNER JOIN v_vh_vehiculos v on m.codigo=v.codigo
					INNER JOIN flotas_intranet fi ON fi.placa = v.placa
					LEFT JOIN (SELECT codigo, CONVERT(date,dl.fec) as fecha_venta FROM documentos_lin dl where sw=1 and tipo in ('KV','VA','VC','VG','VR','VX','WV') 
					and cantidad_devuelta is null)d ON v.codigo=d.codigo
					WHERE dp.facturar=1 and anulado=0  and DATEDIFF(day, m.fecha_matricula, fi.fecha_asignacion) <= 15) and activo = 1 AND fecha_aprob_ventas IS NULL AND fecha_rechazo_ventas IS NULL";
		$result = $this->db->query($sql);
	    return $result->num_rows() > 0 ? $result : null;
	}

	public function get_flotas_aprobar_postventa()
	{
		$sql = "SELECT distinct f.*, CONVERT(DATE,ue.Fecha_Ultima_Entrada) as fec_ultima_entrada, d.fecha_venta,t.nombres as cliente FROM flotas_intranet f LEFT JOIN v_vh_vehiculos vh ON f.placa=vh.placa 
			LEFT JOIN v_ultima_entrada_4_5 ue
			ON vh.codigo=ue.serie
			LEFT JOIN (SELECT codigo, CONVERT(date,dl.fec) as fecha_venta FROM documentos_lin dl where sw=1 and tipo in ('KV','VA','VC','VG','VR','VX','WV') and cantidad_devuelta is null)d
			ON vh.codigo=d.codigo 
			INNER JOIN terceros t on t.nit = f.nit
			WHERE fecha_solicitud IS NOT NULL AND activo = 1 AND fecha_aprob_postventa IS NULL AND fecha_rechazo_postventa IS NULL ORDER BY nombre_flota ASC";
		$result = $this->db->query($sql);
	    return $result->num_rows() > 0 ? $result : null;
	}

	public function aprobar_flota($data)
	{
		// 256 : Jose Luis Arenas Pabon
		$campo = $this->session->userdata('id_user') == 256 ? 'fecha_aprob_ventas' : 'fecha_aprob_postventa';

		$idsFlotas  = explode('_',$data['idsFlotas']);
		$campoMod   = $this->session->userdata('id_user') == 256 ? 'asesor' :'comisiona';
		$valorMod   = $this->session->userdata('id_user') == 256 ? explode('_',$data['asesores']) : explode('_',$data['comisiones']);

		$flag = 0;
		for ($i=0; $i < count($idsFlotas); $i++) {
			$dataArray = array(
				$campoMod => $valorMod[$i],
				$campo    => $this->dt->format('Y-m-d')
			);

			$this->db->where('id_flota', $idsFlotas[$i]);
			$result = $this->db->update('flotas_intranet', $dataArray);

			$result ? $flag++ : $flag;
		}

	    return $flag == count($idsFlotas) ? 1 : 0;
	}

	public function get_flotas_actualizadas()
	{
		$sql = "SELECT f.*,t.nombres as cliente FROM flotas_intranet f INNER JOIN terceros t ON t.nit = f.nit WHERE comisiona IS NULL AND fecha_aprob_postventa is null and fecha_aprob_ventas is null and fecha_rechazo_postventa is null and fecha_rechazo_ventas is null ORDER BY nombre_flota ASC";
		$result = $this->db->query($sql);
	    return $result->num_rows() > 0 ? $result : null;
	}

	public function fijar_comision($data)
	{
		$dataArray = array(
			'comisiona'       => $data['comisiona'],
			'obs_comision'    => $data['observacion'],
			'fecha_solicitud' => $this->dt->format('Y-m-d')
		);

		$this->db->where('id_flota', $data['idFlota']);
		$result = $this->db->update('flotas_intranet', $dataArray);

	    return $result ? 1 : 0;
	}

	public function rechazar_flota($data)
	{
		// 256 : Jose Luis Arenas Pabon

		$idsFlotas  = str_replace('_',',',$data['idsFlotas']);
		$campos = $this->session->userdata('id_user') == 256 ? 'fecha_rechazo_ventas = GETDATE()' : 'comisiona = NULL, fecha_solicitud = NULL, obs_comision = NULL, fecha_rechazo_postventa = GETDATE()';

		$sql = "UPDATE flotas_intranet SET $campos WHERE id_flota IN ($idsFlotas)";
		$result = $this->db->query($sql);

	    return $result ? 1 : 0;
	}

	public function get_flotas_aprobadas()
	{
		$sql = "SELECT f.*,t.nombres as cliente FROM flotas_intranet f INNER JOIN terceros t ON t.nit = f.nit WHERE fecha_aprob_postventa is not null or fecha_aprob_ventas is not null ORDER BY nombre_flota ASC";
		$result = $this->db->query($sql);
	    return $result->num_rows() > 0 ? $result : null;
	}

	public function get_flotas_rechazadas()
	{
		$sql = "SELECT f.*,t.nombres as cliente FROM flotas_intranet f INNER JOIN terceros t ON t.nit = f.nit WHERE fecha_rechazo_postventa is not null or fecha_rechazo_ventas is not null ORDER BY nombre_flota ASC";
		$result = $this->db->query($sql);
	    return $result->num_rows() > 0 ? $result : null;
	}
	

	/**************************************************************INFORMES GESTION FLOTAS ************************************************************ */
	public function getDataInforme()
	{
		return $this->db->query("SELECT * FROM v_gestion_coord_flotas");
	}

	public function getValorDespCoord()
	{
		$result = $this->db->query("----DESPUES DE LLEGADA DE JORGE---
		SELECT SUM(it.venta_rptos+it.Venta_mano_obra+it.venta_TOT) as ventas_despues
		FROM v_flotas_tres_vh v INNER JOIN v_primer_entrada_mto te
		on v.codigo=te.serie
		INNER JOIN tall_encabeza_orden e on te.serie=e.serie
		LEFT JOIN
		v_informe_tecnico it
		on e.numero=it.numero_orden
		where CONVERT(date,primer_entrada)>=(SELECT fecha_ini FROM swcrm_personal where nit=91080030 and estado_contrato='A')
		and CONVERT(date,e.entrada)>=(SELECT fecha_ini FROM swcrm_personal where nit=91080030 and estado_contrato='A')
		and v.nit_comprador not in (900939798,811011779,900265584,901361064)");
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function getValorAntCoord()
	{
		$result = $this->db->query("SELECT SUM(it.venta_rptos+it.Venta_mano_obra+it.venta_TOT) as ventas_antes
		FROM v_flotas_tres_vh v INNER JOIN tall_encabeza_orden te
		on v.codigo=te.serie
		LEFT JOIN
		v_informe_tecnico it
		on te.numero=it.numero_orden
		where CONVERT(date,entrada)<=(SELECT fecha_ini FROM swcrm_personal where nit=91080030 and estado_contrato='A')
		and razon2 in (4,5) and DATEDIFF(DAY,CONVERT(DATE,'20211121'),CONVERT(date,entrada)) between 0 and 365
		and v.nit_comprador not in (900939798,811011779,900265584,901361064)");
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function getValorGestionCoord()
	{
		$result = $this->db->query("SELECT SUM(it.venta_rptos+it.Venta_mano_obra+it.venta_TOT) as ventas_gestion
		FROM flotas_intranet v INNER JOIN v_vh_vehiculos vh on v.placa=vh.placa
		INNER JOIN tall_encabeza_orden te
		on vh.codigo=te.serie
		LEFT JOIN
		v_informe_tecnico it
		on te.numero=it.numero_orden
		where v.activo=1 and CONVERT(date,entrada)>=v.fecha_aprob_postventa
		and razon2 in (4,5) and v.nit not in (900939798,811011779,900265584,901361064) and fecha_aprob_postventa is not null");
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function getValorActualizadoCoord()
	{
		$result = $this->db->query("SELECT SUM(it.venta_rptos+it.Venta_mano_obra+it.venta_TOT) as ventas_gestion
		FROM flotas_intranet v INNER JOIN v_vh_vehiculos vh on v.placa=vh.placa
		INNER JOIN tall_encabeza_orden te
		on vh.codigo=te.serie
		LEFT JOIN
		v_informe_tecnico it
		on te.numero=it.numero_orden
		where v.activo=1 and CONVERT(date,entrada)>=v.fecha_asignacion
		and razon2 in (4,5) and v.nit not in (900939798,811011779,900265584,901361064)");
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	
}
