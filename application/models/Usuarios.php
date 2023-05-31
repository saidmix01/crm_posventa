<?php

/**
 * 
 */
class Usuarios extends CI_Model
{
	/********USUARIOS**********/

	/*
	 *Metodo que valida si el usuario ya existe
	 *parametros: nombre del usuario (nick)
	 *retorna: todos los datos de ese usuario
	 */
	public function validar_usu($user = '')
	{
		$result = $this->db->query("SELECT * FROM w_sist_usuarios u INNER JOIN terceros t ON t.nit = u.nit_usuario WHERE u.nit_usuario LIKE '" . $user . "'");
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}
	/*
	 *Metodo que valida si el usuario ya existe
	 *parametros: nombre del usuario (nick)
	 *retorna: todos los datos de ese usuario
	 */
	public function getUserByName($user = '')
	{
		return $this->db->query("SELECT * FROM w_sist_usuarios u INNER JOIN terceros t ON t.nit = u.nit_usuario WHERE u.nit_usuario = '" . $user . "'");
	}
	/*
	 *Metodo que valida si el usuario ya existe
	 *parametros: nombre del id_usuario
	 *retorna: todos los datos de ese usuario
	 */
	public function getUsuarioById($id)
	{
		$sql = "SELECT * FROM w_sist_usuarios u INNER JOIN terceros t ON t.nit = u.nit_usuario WHERE u.id_usuario = " . $id;
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}
	/*
	 *Metodo que crea un usuario
	 *parametros: usuario, contraseña, id perfil, nit
	 *retorna un booleando
	 */
	public function insert($data)
	{
		$nit = $data['nit'];
		$estado = $data['estado'];
		$pass = $data['pass'];
		$num_intentos = $data['num_intentos'];
		$perfil_posv = $data['perfil_postv'];
		$clave = "0";
		$tipo_tercero = "1";
		$fid_perfil = "1";

		$sql = "INSERT INTO w_sist_usuarios(nit_usuario,estado,pass,num_intentos,perfil_postventa,clave,tipo_tercero,fid_perfil) 
		VALUES('$nit','$estado','$pass','$num_intentos','$perfil_posv','$clave','$tipo_tercero','$fid_perfil')";
		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}
	/*
	 *Metodo que actualiza los datos del usuario (hace un update)
	 *parametros: nit del usuario
	 *retorna un booleano
	 */
	public function update_terceros($nit, $data)
	{
		$this->db->where('nit', $nit);
		if ($this->db->update('terceros', $data)) {
			return true;
		} else {
			return false;
		}
	}
	/*
	 *Metodo que valida si existe un usuario
	 *parametros: nit del usuario
	 *retorna un numero
	 */
	public function validarUsuarios($nit)
	{
		$result = $this->db->query("SELECT COUNT(*) AS 'n' FROM w_sist_usuarios u WHERE u.nit_usuario =" . $nit);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}
	/*
	 *Metodo que obtiene datos del usuario
	 *sin parametros
	 *retorna todos los datos de todos los usuarios
	 */
	public function getAllUsers()
	{
		return $this->db->query("SELECT u.id_usuario, p.nom_perfil, t.nombres, u.usuario, t.nit, u.estado 
		FROM w_sist_usuarios u 
		INNER JOIN terceros t ON t.nit = u.nit_usuario 
		LEFT JOIN postv_perfiles p ON p.id_perfil = u.perfil_postventa --WHERE u.estado = 1");
	}
	public function getUserActivos()
	{
		$sql = "SELECT u.id_usuario, t.nombres as nombres, u.usuario, t.nit as nit, u.estado 
				FROM w_sist_usuarios u INNER JOIN terceros t ON t.nit = u.nit_usuario 
				WHERE estado = 1";
		return $this->db->query($sql);
	}
	/*
	 *Metodo que obtiene informacion del usuario por el id
	 *parametros: id del usuario
	 *retorna: toda la informacion del usuario con ese id
	 */
	public function getUserById($id_usu)
	{
		return $this->db->query("SELECT u.id_usuario, p.nom_perfil, t.nombres, u.usuario, t.nit, u.estado, p.id_perfil, t.tipo_identificacion,u.pass FROM w_sist_usuarios u INNER JOIN terceros t ON t.nit = u.nit_usuario INNER JOIN postv_perfiles p ON p.id_perfil = u.perfil_postventa WHERE u.id_usuario = " . $id_usu);
	}
	/*
	 *Metodo que obtiene informacion del usuario por la cedula
	 *parametros: cedula del usuario
	 *retorna: toda la informacion del usuario con ese id
	 */
	public function getUserByNit($nit)
	{
		$result = $this->db->query("SELECT u.id_usuario, p.nom_perfil, t.nombres, u.usuario, t.nit, u.estado, p.id_perfil, t.tipo_identificacion,u.pass 
			FROM w_sist_usuarios u INNER JOIN terceros t ON t.nit = u.nit_usuario 
			INNER JOIN postv_perfiles p ON p.id_perfil = u.perfil_postventa WHERE t.nit =" . $nit);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}
	/*
	 *Metodo que obitiene el numero de intentos fallidos de logeo
	 *parametros: usuario
	 *retorna: numero de intentos, id del usuario
	 */
	public function obtenerIntentos($usuario)
	{
		$result = $this->db->query("SELECT u.num_intentos,u.id_usuario FROM w_sist_usuarios u WHERE u.nit_usuario LIKE '%" . $usuario . "%'");
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}
	/*
	 *Metodo que desactiva el usuario (hace un update)
	 *parametros: id del usuario
	 *no retorna nada
	 */
	public function desactivarUser($id_usu)
	{
		$estado = 0;
		$datos = array('estado' => $estado);
		$this->db->where('id_usuario', $id_usu);
		$this->db->update('w_sist_usuarios', $datos);
	}
	/*
	 *Metodo que activa el usuario (hace un update)
	 *parametros: id del usuario
	 *no retorna nada
	 */
	public function activarUser($id_usu)
	{
		$estado = 1;
		$datos = array('estado' => $estado, 'num_intentos' => 0);
		$this->db->where('id_usuario', $id_usu);
		$this->db->update('w_sist_usuarios', $datos);
	}
	/*
	 *Metodo que actualiza el usuario incluyendo la contraseña
	 *parametros: id del usuario, un array con->id del perfil, la nueva contraseña, el numero de intentos
	 *no retorna nada
	 */
	public function updateWithPass($data, $id)
	{
		$datos = array('perfil_postventa' => $data['id_perfil'], 'pass' => $data['pass'], 'num_intentos' => 0);
		$this->db->where('id_usuario', $id);
		$this->db->update('w_sist_usuarios', $datos);
	}
	/*
	 *Metodo que actualiza el usuario incluyendo la contraseña
	 *parametros: id del usuario, un array con->id del perfil, la nueva contraseña, el numero de intentos
	 *no retorna nada
	 */
	public function updatePass_req($data, $id)
	{
		$datos = array('pass' => $data['pass'], 'num_intentos' => 0, 'estado' => 1);
		$this->db->where('id_usuario', $id);

		if ($this->db->update('w_sist_usuarios', $datos)) {
			return true;
		} else {
			return false;
		}
	}
	/*
	 *Metodo que actualiza el usuario incluyendo la contraseña
	 *parametros: id del usuario, un array con->id del perfil, la nueva contraseña, el numero de intentos
	 *no retorna nada
	 */
	public function updatePass_reqVentas($data, $id)
	{
		$datos = array('clave' => $data, 'num_intentos' => 0, 'estado' => 1);
		$this->db->where('id_usuario', $id);

		if ($this->db->update('w_sist_usuarios', $datos)) {
			return true;
		} else {
			return false;
		}
	}
	/*
	 *Metodo que actualiza el usuario sin la contraseña
	 *parametros: id del usuario, un array con->id del perfil
	 *no retorna nada
	 */
	public function updateWithOutPass($data, $id)
	{
		$datos = array('perfil_postventa' => $data['id_perfil']);
		$this->db->where('id_usuario', $id);
		$this->db->update('w_sist_usuarios', $datos);
	}
	/*
	 *Metodo que actualiza el usuario solo la contraseña
	 *parametros: id del usuario, un array con->la nueva contraseña
	 *no retorna nada
	 */
	public function updatePassword($id_usu, $data)
	{
		$datos = array('pass' => $data['pass']);
		$this->db->where('id_usuario', $id_usu);
		$this->db->update('w_sist_usuarios', $datos);
	}
	public function updatePasswordVentas($id_usu, $data)
	{
		$datos = array('clave' => $data);
		$this->db->where('id_usuario', $id_usu);
		$this->db->update('w_sist_usuarios', $datos);
	}
	/*
	 *Metodo que actualiza el numero de intentos de logeo
	 *parametros: id del usuario, un array con el numero de intentos
	 *no retorna nada
	 */
	public function updateIntentos($id_usu, $data)
	{
		$datos = array('num_intentos' => $data['intentos']);
		$this->db->where('id_usuario', $id_usu);
		$this->db->update('w_sist_usuarios', $datos);
	}

	/*
	 *Metodo que obtiene el perfil del usuario
	 *parametros: id del usuario
	 *retorna el perfil del usuario
	 */
	public function obtenerPerfil($id_user)
	{
		$result = $this->db->query("SELECT p.id_perfil, u.url_img_user_postv FROM w_sist_usuarios u INNER JOIN postv_perfiles  p ON u.perfil_postventa = p.id_perfil WHERE u.nit_usuario = '" . $id_user . "'");
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}



	/********TERCEROS********/

	/*
	 *Metodo que valida si existe el tercero
	 *parametros: nit
	 *retorna: un numero
	 */
	public function validarTerceros($nit)
	{
		$result = $this->db->query("SELECT COUNT(*) AS 'n' FROM terceros t WHERE t.nit =" . $nit);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}


	/**metodos para ingresar nit  a la tabla postv_empreados  */

	public function IngresarNitEmpleado($nit)
	{
		$sql = "INSERT INTO postv_empleados VALUES (" . $nit . ")";

		if ($this->bd->query($sql)) {

			$id = $this->bd->query("SELECT id_empleado where nit_empleado=" . $nit);
			if ($id->num_rows() > 0) {
				return $id->row();
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	/**metodos para ter los dtaso de los jefes de la tabla terceros y postv_jefes */

	public function ConsultaJefes($nit)
	{
		$sql = "INSERT INTO postv_empleados VALUES ( $nit )";

		if ($this->bd->query($sql)) {

			$id = $this->bd->query("SELECT id_empleado where nit_empleado=" . $nit);
			if ($id->num_rows() > 0) {
				return $id->row();
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function update_cod_verifi($nit, $codigo)
	{

		$datos = array('cod_verificacion' => $codigo);
		$this->db->where('nit_usuario', $nit);
		if ($this->db->update('w_sist_usuarios', $datos)) {
			return true;
		} else {
			return false;
		}
	}

	public function get_mail_by_cedula($cedula)
	{
		$result = $this->db->query("SELECT distinct(c.e_mail) as mail FROM CRM_contactos c WHERE nit=$cedula");
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}

	public function validar_cod_verifi($nit, $codigo)
	{
		$result = $this->db->query("SELECT COUNT(*) AS n,id_usuario FROM w_sist_usuarios WHERE nit_usuario = '" . $nit . "' AND cod_verificacion = '" . $codigo . "' GROUP BY id_usuario");
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return null;
		}
	}


	public function get_asesores_activos()
	{
		$result = $this->db->query("SELECT t.nit,t.nombres from terceros t inner join w_sist_usuarios u on u.nit_usuario = t.nit where u.fid_perfil=11 and u.estado=1");
		return $result->num_rows() > 0 ? $result : null;
	}

	public function get_coordinador_flotas()
	{
		$result = $this->db->query("SELECT t.nit,t.nombres from terceros t inner join w_sist_usuarios u on u.nit_usuario = t.nit where u.perfil_postventa=53 and u.estado=1");
		return $result->num_rows() > 0 ? $result : null;
	}

	public function get_jefes()
	{
		$sql = "SELECT t.nit, t.nombres FROM terceros t WHERE t.concepto_12 IN(11,15,16)";
		return $this->db->query($sql);
	}

	/*METODO QUE OBTIENE LOS USUARIOS POR SEDES
	Y POR PERFIL*/
	public function get_user_by_sedes_perfil($sedes, $perfil)
	{
		$sql = "select us.nit_usuario,t.nombres from w_sist_usuarios us 
				INNER JOIN terceros t ON t.nit = us.nit_usuario
				INNER JOIN sw_usuariosede used ON used.idusuario = us.id_usuario
				where us.perfil_postventa IN (" . $perfil . ") AND us.estado = 1 and used.idsede IN(" . $sedes . ")";
		return $this->db->query($sql);
	}
	/* Metodo para traer los jefes del empleado */
	public function getJefesAll($nit)
	{
		$sql = "SELECT jf.id_jefe, jf.nit_jefe,t.nombres,emp.id_empleado from postv_empleados emp
				inner join postv_empleado_jefe ej ON ej.empleado=emp.id_empleado
				inner join postv_jefes jf ON jf.id_jefe = ej.jefe
				inner join w_sist_usuarios ws ON ws.nit_usuario = jf.nit_jefe
				inner join terceros t on t.nit = ws.nit_usuario
				where emp.nit_empleado = $nit";

		$result =  $this->db->query($sql);

		if ($result->num_rows() > 0) {
			return $result;
		} else {
			return null;
		}
	}
	/* Metodo para eliminar la relación de empleado - jefe */
	public function deleteEmpleadoJefe($jefe, $empleado)
	{

		$sql = "DELETE FROM postv_empleado_jefe
				where jefe = $jefe and empleado = $empleado";
		$result =  $this->db->query($sql);
		if ($this->db->affected_rows() > 0) {
			return $result;
		} else {
			return null;
		}
	}

	/*
	*metodo para insertar jefes a empleado en la tabla postv_empleado_jefe
	*Andres Gomez Modificado por Sergio Galvis
	*21-12-2021 Modificado 04/Agosto/2022
	*/

	public function listar_id_usuarios($nit)
	{
		$sql = "SELECT id_empleado FROM postv_empleados WHERE nit_empleado = $nit";
		$ejecutar = $this->db->query($sql);
		if ($ejecutar->num_rows() > 0) {
			$data = $ejecutar->row();
			return $data->id_empleado;
		} else {
			$sql = "INSERT INTO postv_empleados VALUES ($nit)";

			if ($this->db->query($sql)) {
				return $this->db->insert_id();
			}
		}
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

	/* Agregar pausa activa Am */
	public function aggPausaActivaAm($nit)
	{
		$sql = "INSERT INTO postv_pausas_activas(nit,fecha_am) 
		VALUES($nit,SYSDATETIME())";

		$ejecutar = $this->db->query($sql);

		if ($ejecutar) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	/* Agregar pausa activa Am */
	public function aggPausaActivaPm($nit)
	{
		/* Traemos el id del registro si existe en AM */
		$sql = "SELECT top 1 id from postv_pausas_activas
				where nit = $nit
				and
				CONVERT(date,fecha_am ) = CONVERT(date,GETDATE())
				order by id desc";

		$validar = $this->db->query($sql);

		/* Validamos el numero de filas que nos trae la consulta
		* Fila > 0 : Actualizamos la fecha_pm al registro encontrado (ID) con la anterior consulta.
		* Fila < 0 : Insertamos un registro con registro en fecha_pm...
		 */
		if ($validar->num_rows() > 0) {
			$idValidar = $validar->row();
			$sql = "UPDATE postv_pausas_activas SET fecha_pm = SYSDATETIME() WHERE id = $idValidar->id";

			$ejecutar = $this->db->query($sql);

			if ($ejecutar) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			$sql = "INSERT INTO postv_pausas_activas(nit,fecha_pm) 
			VALUES($nit,SYSDATETIME())";

			$ejecutar = $this->db->query($sql);

			if ($ejecutar) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}
	/* validar Pausa Activa Am */
	public function validarPausaActivaAm($user)
	{

		$sql = "SELECT top 1 * from postv_pausas_activas
				where nit = $user and 
				CONVERT(date,fecha_am ) = CONVERT(date,GETDATE())
				order by id desc";

		$validar = $this->db->query($sql);

		if ($validar->num_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	/* validar Pausa Activa Pm */
	public function validarPausaActivaPm($user)
	{

		$sql = "SELECT top 1 * from postv_pausas_activas
				where nit = $user and 
				CONVERT(date,fecha_pm ) = CONVERT(date,GETDATE())
				order by id desc";
		$validar = $this->db->query($sql);

		if ($validar->num_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	/* Obtener todas las pausas activas en la fecha especifica */
	public function getAllPausasActivas($empleado, $sede, $fecha)
	{

		$sql = "SELECT h.nit_empleado, t.nombres,CONVERT(varchar,ps.fecha_am,20) as fechaAM,CONVERT(varchar,ps.fecha_pm,20) as fechaPM, h.sede 
				from postv_horarios_empleados h 
				left join (select * from postv_pausas_activas where $fecha )ps 
				on h.nit_empleado=ps.nit
				LEFT JOIN terceros t ON t.nit = h.nit_empleado	
				/* WHERE */ $sede $empleado 	
				order by fecha_am desc,fecha_pm desc";
		$result = $this->db->query($sql);

		if ($result->num_rows() > 0) {
			return $result;
		} else {
			return false;
		}
	}
	/* Agregar usuarios como jefes */
	public function insertJefe($jefe, $mail)
	{

		$sql = "insert into postv_jefes (nit_jefe,correo) values ($jefe,'$mail')";

		if ($this->db->query($sql)) {
			return true;
		} else {
			return false;
		}
	}
	public function getClaveIntranetVentas($nit)
	{
		$sql = "SELECT pass from w_sist_usuarios
				where nit_usuario = $nit";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}
	public function updateImgUser($img,$nit){
		$sql = "UPDATE dbo.w_sist_usuarios 
				SET url_img_user_postv = '$img'
				WHERE nit_usuario = $nit";

			$ejecutar = $this->db->query($sql);

			return $ejecutar;
	}
	/* Funcion creada por Sergio 27/12/2022 */
	public function v_lealtad_repuestos(){
		$sql ="SELECT * FROM  v_lealtad_repuestos";
		return $this->db->query($sql);
	}
}
