<?php 

/**
 * 
 */
class Perfiles extends CI_Model
{
	/*
	 *Metodo que obtiene el perfil del usuario
	 *parametros: id del usuario
	 *retorna el perfil y el id del perfil
	 */	
	public function getPerfilByUser($user = '')
	{
	   $result = $this->db->query("SELECT p.nom_perfil, p.id_perfil, u.url_img_user_postv FROM w_sist_usuarios u INNER JOIN dbo.postv_perfiles p ON u.perfil_postventa = p.id_perfil WHERE u.nit_usuario LIKE '".$user."'");
	   if($result->num_rows() > 0){
       return $result->row();
	   }else{
	      return null;
	   }
	}
	/*
	 *Metodo que retorna todos los perfiles
	 *sin parametros
	 *retorna todos los perfiles
	 */
	public function getAllPerfiles(){
		$sql = "SELECT * FROM postv_perfiles";
		//echo $sql;die;
		return $this->db->query($sql);
	}
	/*
	 *Metodo que obtiene los perfiles por id
	 *parametros: id del perfil
	 *retorna el id del perfil y el perfil
	 */
	public function getPerfilById($id='')
	{
	   $result = $this->db->query("SELECT id_perfil,nom_perfil FROM dbo.postv_perfiles WHERE id_perfil='".$id."'");
	   if($result->num_rows() > 0){
       		return $result->row();
	   }else{
	      	return null;
	   }
	}
	/*
	 *Metodo que obtiene el id del perfil por el nombre
	 *parametros: nombre del perfil
	 *retorna: id del perfil y el nombre
	 */
	public function getPerfilByNomPerfil($perfil='')
	{
	   $result = $this->db->query("SELECT id_perfil,perfil FROM perfiles WHERE perfil='".$perfil."'");
	   if($result->num_rows() > 0){
       		return $result->row();
	   }else{
	      	return null;
	   }
	}
	/*
	 *Metodo que crea perfiles
	 *parametros: nombre del perfil
	 *retorna un booleano
	 */
	public function insert($registros=null)
	{
		if ($registros != null) {
			$perfil = $registros['nombre_perfil'];
			$sql = "INSERT INTO perfiles(id_perfil,perfil) VALUES(null,'$perfil')";
			if($this->db->query($sql)){
				return true;
			}else{
				return false;
			}
		}
	}
	/*
	 *Metodo que elimina un perfil
	 *parametros: id del perfil
	 *retorna un booleano
	 */
	public function delete($id_perfil)
	{
		$sql = "DELETE FROM perfiles WHERE id_perfil=".$id_perfil;
		if($this->db->query($sql)){
			return true;
		}else{
			return false;
		}
	}

	/*
	 *Metodo que actualiza un perfil
	 *parametros: id del perfil, nombre del perfil
	 *no retorna
	 */
	public function update($id_perfil,$nombre_perfil)
	{
		$datos = array('perfil' => $nombre_perfil);
		$this->db->WHERE('id_perfil',$id_perfil);
		$this->db->update('perfiles',$datos);
	}
}

 ?>