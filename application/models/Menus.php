<?php

/**
 * 
 */
class Menus extends CI_Model
{
	/*
	 *Metodo que obtiene los menus por perfil de usuario
	 *parametros: el id de la tabla perfil
	 *retorna: menu, vista, id_perfil, id_menu de acuerdo al perfil
	 */
	public function getMenusByPerfil($perfil = '')
	{
		return $this->db->query("SELECT m.menu, m.vista_menu, pe.id_perfil, m.id_menu, m.icono 
FROM postv_menus m 
INNER JOIN postv_menu_perfil mp ON m.id_menu = mp.menu 
INNER JOIN postv_perfiles pe ON mp.perfil = pe.id_perfil WHERE pe.id_perfil = " . $perfil . " ORDER BY m.menu ASC");
	}

	/*
	 *Metodo que obtiene todos los submenus
	 *sin parametros
	 *retorna todos los submenus
	 */
	public function getAllSubmenus()
	{
		return $this->db->query("SELECT * FROM dbo.postv_submenu");
	}

	/*
	 *Metodo que obtiene todos los submenus ordenados de manera ascendente
	 *sin parametros
	 *retorna todos los submenus
	 *by Cristian
	 */
	public function getAllSubmenusOrdenedByMenuId()
	{
		return $this->db->query("SELECT s.id_submenu ,s.submenu, s.vista,m.menu, s.icono FROM postv_submenu s JOIN postv_menus m ON s.menu_id = m.id_menu ORDER BY m.id_menu ASC");
	}

	/*
	 *Metodo que obtiene todos los submenus de un menu
	 *parametros: id_menu
	 *retorna: todos los submenus de acuerdo al id del menu
	 */
	public function getSubmenuById($id = '')
	{
		return $this->db->query("SELECT s.id_submenu ,s.submenu, s.vista,m.id_menu,m.menu, s.icono FROM postv_submenu s JOIN postv_menus m ON s.menu_id = m.id_menu WHERE id_submenu =" . $id . " ORDER BY m.id_menu ASC");
	}

	/*
	 *Metodo que obtiene todos los submenus de un menu
	 *parametros: id_menu
	 *retorna: todos los submenus de acuerdo al id del menu
	 */
	public function getAllSubmenusById($id = '')
	{
		return $this->db->query("SELECT * FROM dbo.postv_submenu WHERE menu_id =" . $id);
	}
	/*
     *Metodo que valida si un perfil tiene acceso a un submenu
     *parametros: id_menu, id_perfil
     *retorna todos los submenus de un menu y un perfil especifico
	 */
	public function getSubmenusByPerfil($menu = '', $perfil = '')
	{
		return $this->db->query("SELECT su.submenu,su.vista, su.icono FROM postv_submenu_perfiles sp INNER JOIN postv_submenu su ON su.id_submenu = sp.submenu WHERE sp.perfil = '" . $perfil . "' AND su.menu_id = '" . $menu . "' ORDER BY su.submenu ASC");
	}
	/*
	 *Metodo que retorna todos los menus
	 *sin parametros
	 *retorna todos los menus
	 */
	public function getAllMenus()
	{
		$result = $this->db->query("SELECT menu FROM dbo.postv_menus");
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}
	/*
	 *Metodo que retorna el id del menu por el nombre de este
	 *parametros: nombre del menu
	 *retorna: id del menu
	 */

	public function getIdMenuByMenu($menu = '')
	{
		$result = $this->db->query("SELECT id_menu FROM dbo.postv_menus WHERE menu ='" . $menu . "'");
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}
	/*
	 *Metodo que retorna todos los menus
	 *sin parametros
	 *retorna todos los menus
	 */
	/* modificado para traer el campo icono Andres Gomez 14-09-21 */
	public function getAllMenu()
	{
		return $this->db->query("SELECT menu, id_menu, icono FROM dbo.postv_menus");
	}

	/*
	 *Metodo que retorna todos los menus ordenados de manera ascendente
	 *sin parametros
	 *retorna todos los menus
	 *by Cristian
	 */
	public function getAllMenuOrdenedById()
	{
		return $this->db->query("SELECT menu, id_menu FROM dbo.postv_menus ORDER BY menu ASC");
	}

	/*
	 *Metodo que da acceso a los menus (crea una relacion de menus_perfiles)
	 *parametros: id del menu, id del perfil
	 *retorna un booleano
	 */
	public function insertMenuPerfil($registros)
	{
		if ($registros != null) {
			$menu = $registros['menu'];
			$perfil = $registros['perfil'];
			$sql = "INSERT INTO postv_menu_perfil(menu,perfil) VALUES ($menu,$perfil)";
			if ($this->db->query($sql)) {
				return true;
			} else {
				return false;
			}
		}
	}
	/*
     *Metodo que quita el acceso a los menus (elimina una relacion de menus_perfiles)
	 *parametros: id del perfil, id del menu
	 *retorna un booleano
	 */
	public function deleteMenuPerfil($perfil = '', $menu = '')
	{
		$sql = "DELETE FROM postv_menu_perfil WHERE perfil = '" . $perfil . "' AND menu ='" . $menu . "'";
		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}
	/*
	 *Metodo que da acceso a los submenus (crea una relacion de submenus_perfiles)
	 *parametros: id del submenu, id del perfil
	 *retorna un booleano
	 */
	public function insertSubMenuPerfil($registros)
	{
		if ($registros != null) {
			$menu = $registros['submenu'];
			$perfil = $registros['perfil'];
			$sql = "INSERT INTO postv_submenu_perfiles(submenu,perfil) VALUES('$menu','$perfil')";
			if ($this->db->query($sql)) {
				return true;
			} else {
				return false;
			}
		}
	}
	/*
     *Metodo que quita el acceso a los submenus (elimina una relacion de submenus_perfiles)
	 *parametros: id del perfil, id del submenu
	 *retorna un booleano
	 */
	public function deleteSubMenuPerfil($perfil = '', $submenu = '')
	{
		$sql = "DELETE FROM postv_submenu_perfiles WHERE perfil = '" . $perfil . "' AND submenu ='" . $submenu . "'";
		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}
	/*
	 *Metodo que valida si un perfil tiene acceso a una vista
	 *parametros: nombre de la vista y el id del perfil
	 *retorna un numero
	 */
	public function validarPermisosSubmenus($vista, $perfil)
	{
		$result = $this->db->query("SELECT COUNT(*) AS n FROM dbo.postv_submenu_perfiles sp INNER JOIN postv_submenu sm ON sm.id_submenu = sp.submenu WHERE sm.vista = '" . $vista . "' AND sp.perfil = " . $perfil);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	/*
	 *Metodo que inserta un nuevo submenu a la base de datos
	 *parametros: submenu,vista,menu_id,icono
	 *retorna un booleano
	 *by Cristian
	 */
	public function insertSubMenu($submenu, $vista, $menu, $icono)
	{
		$sql = "INSERT INTO postv_submenu (submenu,vista,menu_id,icono) VALUES ('" . $submenu . "','" . $vista . "'," . $menu . ",'" . $icono . "')";
		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}

	/*
	 *Metodo que actualiza un submenu en la base de datos
	 *parametros: id_submenu, submenu,vista,menu_id,icono
	 *retorna un booleano
	 *by Cristian
	 */
	public function updateSubMenu($idSubmenu, $submenu, $vista, $menu, $icono)
	{
		$sql = "UPDATE postv_submenu SET submenu = '" . $submenu . "',vista = '" . $vista . "',menu_id = " . $menu . " ,icono = '" . $icono . "' WHERE id_submenu=" . $idSubmenu;
		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}

	/*
     *Metodo que elimina el submenu de la BD
	 *parametros: id del submenu
	 *retorna un booleano
	 *by Cristian
	 */
	public function deleteSubMenu($submenuId)
	{
		$sql = "DELETE FROM postv_submenu WHERE id_submenu = " . $submenuId;
		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}


	/*
	*Metodo que registra un nuevo menu
	*cerado por Andres Gomez
	* 14-09-21 
	*/

	public function getMenu($nombre, $vista, $icono)
	{

		$inser = "INSERT INTO dbo.postv_menus (menu, vista_menu, icono) VALUES ('" . $nombre . "','" . $vista . "','" . $icono . "')";
		if ($this->db->query($inser)) {
			return true;
		} else {
			return false;
		}
	}
	/*Metodo para eliminar un menu
	*cerado por Andres Gomez 
	*15-09-21
	 */

	public function deleteMenu($id)
	{
		$delete = "DELETE FROM dbo.postv_menus WHERE id_menu =" . $id;
		if ($this->db->query($delete)) {
			return true;
		} else {
			return false;
		}
	}
	/*Metodo para tarer todos mlos campos segun el id un menu
	cerado por Andres Gomez 15-09-21 */

	public function traer_datos_menu($id)
	{
		$traer = "SELECT * FROM dbo.postv_menus WHERE id_menu =" . $id;
		return $this->db->query($traer);
	}

	/*Metodo para editar  menu
	cerado por Andres Gomez 14-09-21 */

	public function updatemenu($id, $nombre, $vista, $icono)
	{

		$update = "UPDATE dbo.postv_menus SET  menu='$nombre',vista_menu = '$vista', icono = '$icono' WHERE id_menu = $id";
		if ($this->db->query($update)) {
			return true;
		} else {
			return false;
		}
	}


	/*
	 *Metodo que retorna todos los perfiles
	 *de la tabla postv_perfiles
	 * modificado para traer el campo icono Andres Gomez 14-09-21
	*/
	public function ver_perfil()
	{
		return $this->db->query("SELECT id_perfil,nom_perfil FROM postv_perfiles");
	}

	/*
		*Metodo que registra un nuevo perfil en la tabla postv_perfiles
		*cerado por Andres Gomez 16-09-21
		 */

	public function getPerfil($nombre)
	{

		$inser = "INSERT INTO postv_perfiles (nom_perfil) VALUES ('" . $nombre . "')";
		if ($this->db->query($inser)) {
			return true;
		} else {
			return false;
		}
	}

	/*
		*Metodo para eliminar perfiles de la tabla postv_perfiles
		*cerado por Andres Gomez 16-09-21
		*/

	public function deletePerfil($id)
	{
		$delete = "DELETE FROM postv_perfiles WHERE id_perfil =" . $id;
		if ($this->db->query($delete)) {
			return true;
		} else {
			return false;
		}
	}

	/*Metodo para tarer todos mlos campos segun el id un menu
	cerado por Andres Gomez 15-09-21 */

	public function traer_datos_perfil($id)
	{
		$traer = "SELECT * FROM postv_perfiles WHERE id_perfil =" . $id;
		return $this->db->query($traer);
	}
	/*Metodo para editar  menu
	cerado por Andres Gomez 16-09-21 */

	public function updateperfil($id, $nombre)
	{

		$update = "UPDATE postv_perfiles SET  nom_perfil='$nombre' WHERE id_perfil = $id";
		if ($this->db->query($update)) {
			return true;
		} else {
			return false;
		}
	}

	/*
	 * metodo para traer todos los perfiles de los jefes
	 * Andres Gomez
	 * 21-12-2021
	 */
	public function traer_perfil_jefes()
	{
		$sql = "SELECT j.id_jefe,j.nit_jefe,t.nombres 
		FROM postv_jefes j
		INNER JOIN terceros t ON j.nit_jefe = t.nit";
		return $this->db->query($sql);
	}
	/*
	*metodo para insertar jefes a empleado en la tabla postv_empleado_jefe
	*Andres Gomez 
	*21-12-2021
	*/

	public function listar_id_usuarios($nit)
	{
		$sql = "SELECT id_empleado FROM postv_empleados WHERE nit_empleado = $nit";
		$ejecutar = $this->db->query($sql);
		return $ejecutar->row();

	
	}
	public function insetar_empleado_jefe($id_empleado, $jefes)
	{
		$sql = "INSERT INTO postv_empleado_jefe VALUES ($jefes,$id_empleado)";
		$ejecutar = $this->db->query($sql);

		if ($sql) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
