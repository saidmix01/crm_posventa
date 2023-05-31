<?php 
/**
 * 
 */
class Inventarios extends CI_Model
{
	/*
	 *Metodo que obtiene los prepuestos
	 *parametros de entrada: fecha inicial, fecha final, usuario
	 *retorna: todo los repuestos de acuerdo al rango de fecha y el usuario
	 */
	public function getRepuestosByFechas($fecha_ini,$fecha_fin,$user)
	{
		return $this->db->query("SELECT s.descripcion as 'sede', r.descripcion, DATE_FORMAT(r.fecha_creacion,'%d/%m/%Y') AS 'fecha_creacion' FROM repuestos r INNER JOIN repuesto_sede rs ON rs.repuesto = r.id_repuestos INNER JOIN sedes s ON s.id_sede = rs.sede  INNER JOIN usuario_sedes us ON us.sede = s.id_sede WHERE r.fecha_creacion BETWEEN '".$fecha_ini."' AND '".$fecha_fin."' AND us.usuario = ".$user." ORDER BY s.descripcion");
	}
}

 ?>