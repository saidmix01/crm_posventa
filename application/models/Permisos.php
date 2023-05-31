<?php 
/**
 * 
 */
class Permisos extends CI_Model
{
	
	public function getPermisosByUser($user='')
	{
		return $this->db->query("SELECT p.descripcion FROM permisos p INNER JOIN perfiles_permisos pp ON pp.permiso = p.id_permisos INNER JOIN perfiles pe ON pe.id_perfil = pp.perfil INNER JOIN usuarios u ON u.perfil = pe.id_perfil WHERE u.usuario = 'pepe'");
	}

	//
	public function validarPermisosUser($perfil='',$menu = '')
	{	
	   $result = $this->db->query("SELECT COUNT(*) AS 'numero' FROM dbo.postv_menu_perfil mp INNER JOIN postv_perfiles pe ON mp.perfil = pe.id_perfil INNER JOIN dbo.postv_menus m ON m.id_menu = mp.menu WHERE pe.id_perfil = ".$perfil." AND m.id_menu = ".$menu);
	   if($result->num_rows() > 0){
       return $result->row();
	   }else{
	      return null;
	   }
	}

	public function validarPermisosSubmenu($perfil='',$submenu = '')
	{
	   $result = $this->db->query("SELECT COUNT(*) AS 'numero' FROM dbo.postv_submenu_perfiles sup WHERE sup.submenu = ".$submenu." AND sup.perfil = ".$perfil);
	   if($result->num_rows() > 0){
       return $result->row();
	   }else{
	      return null;
	   }
	}

	public function validar_perfil($id_perfil)
	{
		$sql = "SELECT nom_perfil FROM postv_perfiles WHERE id_perfil = ".$id_perfil;
		$result = $this->db->query($sql);
	    if($result->num_rows() > 0){
          return $result->row();
	    }else{
	      return null;
	   }
	}
}

 ?>