<?php 
/**
 * 
 */
class Sedes extends CI_Model
{
	/*
	 *Metodo que registra un usuario a una sede (Crea una relacion usuarios sedes)
	 *parametros: id del usuario, id de la sede
	 *retorna un booleano
	 */
	public function insert_usu_sedes($id_usu,$id_sede)
	{
		$sql = "INSERT INTO sw_usuariosede(idusuario,idsede) VALUES('$id_usu','$id_sede')";
		if($this->db->query($sql)){
			return true;
		}else{
			return false;
		}
	}
	/*
	 *Metodo que desvincula un usuario de una sede (hace un delete a la tabla usuario_sedes)
	 *parametros: id del usuario, id de la sede
	 *retorna un booleano 
	 */
	public function delete_usu_sedes($id_usu,$id_sede)
	{
		$sql = "DELETE FROM sw_usuariosede WHERE idusuario=".$id_usu." AND idsede= ".$id_sede;
		if($this->db->query($sql)){
			return true;
		}else{
			return false;
		}
	}
	/*
	 *Metodo que obtiene todas las sedes
	 *sin parametros
	 *retorna todas las sedes
	 */
	public function getAllSedes()
	{
		return $this->db->query("SELECT * FROM bodegas");
	}
	/*
   	 *Metodo que valida si el usuario pertenece a x sede	
	 *parametros: id del usuario, id de la sede
 	 *retorna un numero
	 */
	public function validarPermisos($id_usu,$sede)
	{
		$sql="SELECT COUNT(*) AS n FROM sw_usuariosede us WHERE us.idsede = ".$sede." AND us.idusuario = ".$id_usu;
		$result = $this->db->query($sql);
		if($result->num_rows() > 0){
			return $result->row();
		}else{
			return null;
		}
	}

	/* Metodo para obtener las bodegas de un usuario
	*  Parametros: nit usuario
	*  retorna: array
	*/
	public function get_sedes_user($user)
	{
		$sql = "SELECT usede.idsede,nombres,descripcion,CONVERT(VARCHAR,usede.idsede) AS idsede_v FROM sw_usuariosede usede 
		INNER JOIN w_sist_usuarios su ON usede.idusuario = su.id_usuario
		INNER JOIN terceros t ON t.nit_real = su.nit_usuario
		INNER JOIN bodegas b ON usede.idsede = b.bodega
		WHERE nit_real = '".$user."'";
		return $this->db->query($sql);
	}


	//Metodo que obtiene las sedes de las bodegas
	public function get_sedes_de_bodegas()
	{
		$sql = "SELECT * FROM SedesDeBodegas";
		return $this->db->query($sql);
	}

	//Metodo que obtiene las sedes de las bodegas por id
	public function get_sedes_de_bodegas_by_id($id)
	{
		$sql = "SELECT * FROM SedesDeBodegas WHERE id=".$id;
		return $this->db->query($sql);
	}
}

?>