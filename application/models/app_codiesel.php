<?php 

/**
 * 
 */
class App_codiesel extends CI_Model
{
	
	public function validar_vh($placa)
	{
		$result = $this->db->query("SELECT COUNT(*) as n FROM v_vh_vehiculos 
			WHERE placa = '".$placa."'");
		if($result->num_rows() > 0){
			return $result->row();
		}else{
			return null;
		}
	}

	public function get_nom_client($placa)
	{
		$sql = "SELECT TOP 1 tn.primer_nombre + ' '+ tn.primer_apellido  as nombre FROM tall_encabeza_orden teo 
		INNER JOIN terceros c ON c.nit = teo.nit
		INNER JOIN terceros_nombres tn ON tn.nit = c.nit
		INNER JOIN v_vh_vehiculos vhv ON vhv.codigo = teo.serie
		WHERE vhv.placa = '".$placa."'";
		$result = $this->db->query($sql);
		if($result->num_rows() > 0){
			return $result->row();
		}else{
			return null;
		}
	}

	public function get_nom_client_by_nit($nit)
	{
		$sql = "SELECT DISTINCT TOP 1 tn.primer_nombre + ' '+ tn.segundo_nombre+' '+tn.primer_apellido  as nombre,teo.nit,c.celular,c.mail,c.direccion FROM tall_encabeza_orden teo 
		INNER JOIN terceros c ON c.nit = teo.nit
		INNER JOIN terceros_nombres tn ON tn.nit = c.nit
		INNER JOIN v_vh_vehiculos vhv ON vhv.codigo = teo.serie
		WHERE teo.nit = ".$nit;
		$result = $this->db->query($sql);
		if($result->num_rows() > 0){
			return $result->row();
		}else{
			return null;
		}
	}

	public function get_info_vh($nit)
	{
		$sql = "SELECT DISTINCT teo.serie,vhv.descripcion,vhv.cilindraje,vhv.clase,vhv.modelo_ano as anio,vhv.motor,vhv.placa,vhv.ciudad_placa,vhv.modelo,vhv.tipo,li.img
			FROM referencias_imp teo
			INNER JOIN v_vh_vehiculos vhv ON vhv.codigo = teo.codigo
			LEFT JOIN sw_com_vh_lineas li ON li.linea = vhv.clase
			WHERE teo.nit_comprador = ".$nit;
		return  $this->db->query($sql);
	}

	public function get_info_vh_v2($placa)
	{
		$sql = "select placa,codigo, convert (date,fec_vencimiento_seg) as fecha_venc_seguro, t.nombres as aseguradora,
				convert (date,fecha_obligatorio) as fecha_soat, 
				convert (date,fecha_tecnico_mecanica) as fecha_tecno_mecanica
				from referencias_imp r left join terceros t
				on r.nit_aseguradora=t.nit
				where placa='".$placa."'
				order by fecha_obligatorio desc";
		return  $this->db->query($sql);
	}

	public function get_vh_taller($nit)
	{
		$sql = "SELECT DISTINCT teo.serie,vhv.descripcion,vhv.cilindraje,vhv.clase,vhv.modelo_ano as anio,vhv.motor,vhv.placa,vhv.ciudad_placa,vhv.modelo,vhv.tipo,li.img
			FROM tall_encabeza_orden teo
			INNER JOIN v_vh_vehiculos vhv ON vhv.codigo = teo.serie
			LEFT JOIN sw_com_vh_lineas li ON li.linea = vhv.clase
			WHERE teo.nit = ".$nit." AND teo.facturada = 0";
		return  $this->db->query($sql);
	}

	public function get_vh_taller_detalle($placa)
	{
		$sql = "select d.numero as orden,tipo=case when clase_operacion='R' then 'Repuesto' 
				when clase_operacion='T' then 'Mano de Obra' else 'TOT' end,
				d.operacion, 
				Descripcion=case when clase_operacion='R' then r.descripcion else isnull(tt.descripcion,'') end,
				valor=
				case when clase_operacion='R' then ((cantidad*valor_unidad)-(cantidad*valor_unidad*porcen_dscto/100))
					 when clase_operacion<>'R' and cantidad='0' then ((d.tiempo*valor_unidad)-(d.tiempo*valor_unidad*porcen_dscto/100))
					 when clase_operacion<>'R' and d.tiempo='0' then ((d.cantidad*valor_unidad)-(d.cantidad*valor_unidad*porcen_dscto/100))
					 else ((cantidad*d.tiempo*valor_unidad)-(cantidad*d.tiempo*valor_unidad*porcen_dscto/100)) end,d.cantidad
				from tall_detalle_orden d left join referencias r
				on d.operacion=r.codigo
				left join tall_tempario tt on d.operacion=tt.operacion and d.id_tall_tempario=tt.id
				inner join tall_encabeza_orden teo on d.numero = teo.numero
				inner join v_vh_vehiculos vhv on teo.serie = vhv.codigo
				where vhv.placa = '".$placa."' and teo.facturada = 0";
		return  $this->db->query($sql);
	}

	public function insert_usuarios($data)
	{
		if ($this->db->insert('app_movil_usuarios',$data)) {
			return true;
		}else{
			return false;
		}
	}

	public function update_usuarios($data,$nit)
	{
		$this->db->where('nit',$nit);
		if ($this->db->update('app_movil_usuarios',$data)) {
			return true;
		}else{
			return false;
		}
	}

	public function validar_usuario($nit)
	{
		$sql = "SELECT COUNT(*) AS n FROM app_movil_usuarios WHERE nit = ".$nit;
		$result = $this->db->query($sql);
		if($result->num_rows() > 0){
			return $result->row();
		}else{
			return 0;
		}
	}

	public function validar_codigo_verifi($nit,$cod)
	{
		$sql = "SELECT COUNT(*) AS n FROM app_movil_usuarios WHERE nit = ".$nit." AND cod_verifi = ".$cod;
		$result = $this->db->query($sql);
		if($result->num_rows() > 0){
			return $result->row();
		}else{
			return 0;
		}
	}

	public function get_datos_usuario($nit)
	{
		$sql = "SELECT * FROM app_movil_usuarios WHERE nit = ".$nit;
		return $this->db->query($sql);
	}

	public function desvincular_vh($placa)
	{
		$data = array('nit_comprador' => '309');
		$this->db->where('placa',$placa);
		if ($this->db->update('referencias_imp',$data)) {
			return true;
		}else{
			return false;
		}
	}

	public function get_aseguradoras()
	{
		$sql = "SELECT nit, nombres from terceros where concepto_1=2 order by nit";
		return $this->db->query($sql);
	}
	public function get_aseguradorasByName($name)
	{
		$sql = "SELECT nit, nombres from terceros where concepto_1=2  AND nombres ='".$name."'";
		return $this->db->query($sql);
	}

	public function update_referencias_imp($data,$placa)
	{
		$this->db->where('placa',$placa);
		if ($this->db->update('referencias_imp',$data)) {
			return true;
		}else{
			return false;
		}
	}
}


?>