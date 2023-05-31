<?php
/*
 *
 * 
 */
class Usuarios_admin extends CI_Controller
{
	/*Metodo que carga la interfaz de la vista gestion_usuarios*/
	public function index()
	{
		/*VALIDAMOS SI HAY DATOS DE SESSION*/
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$perfil = $this->session->userdata('perfil');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//obtenemos todos los USUARIOS
			$allusers = $this->usuarios->getAllUsers();
			/* Validamos que tenga permisos el perfil para acceder a la vista*/
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			$validar_perfil = $this->menus->validarPermisosSubmenus('usuarios_admin', $perfil);
			
			$id_jefes = $this->menus->traer_perfil_jefes();
			if ($validar_perfil->n == 0) {
				$this->session->sess_destroy();
				header("Location: " . base_url());
			} else {
				$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'allusers' => $allusers, 'listadejefes' => $id_jefes);
				//abrimos la vista
				$this->load->view('gestion_usuarios', $arr_user);
			}
		}
	}

	/*Metodo que crea el usuario*/
	public function crear_usuario()
	{
		/*VALIDAMOS SI HAY DATOS DE SESSION*/
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {

			//llamamos los modelos
			$this->load->model('perfiles');
			$this->load->model('usuarios');
			//cargamos los datos del form
			$nit = $this->input->POST('cedula');
			$id_perfil = $this->input->POST('perfil');

			//consulta para validar que no exista el usuario
			$num_terceros = $this->usuarios->validarTerceros($nit);
			$num_usuarios = $this->usuarios->validarUsuarios($nit);

			//encriptacion de password
			$this->load->library('encrypt');
			$pass_encrypt = $this->encrypt->encode($nit);

			//validamos si existe el usuario
			if ($num_terceros->n == 0) {
				echo 0;// No ha sido Creado en la tabla de terceros...
				/* header("Location: " . base_url() . "Usuarios_admin?log=err_nonexist"); */
			} else if ($num_terceros->n == 1) {
				if ($num_usuarios->n == 0) {
					$data = array('nit' => $nit, 'estado' => '1', 'pass' => $pass_encrypt, 'num_intentos' => '0', 'perfil_postv' => $id_perfil);
					if ($this->usuarios->insert($data)) {
						echo 1; // Usuario registrado con exito en w_sist_usuarios
						/* header("Location: " . base_url() . "Usuarios_admin?log=ok"); */
					} else {
						echo 2; // Error al tratar de crear el usuario en w_sist_usuarios
						/* header("Location: " . base_url() . "Usuarios_admin?log=err_exist"); */
					}
				} else if ($num_usuarios->n == 1) {
					echo 3; // El usuario ya ha sido registrado...
					/* header("Location: " . base_url() . "Usuarios_admin?log=err_exist"); */
				}
			}
		}
	}

	/*
	 * METODO PARA INSERTAR JEFES A EMPELADOS
	 * ANDRES GOMEZ
	 * 21-12-2021
	 */
	public function insertar_empleados_a_jefes()
	{
		$this->load->model('usuarios');

		$nit_empleado = $this->input->POST('id_empleado');
		$id_jefes = $this->input->POST('id_jefes');

		$id_empleado = $this->usuarios->listar_id_usuarios($nit_empleado);

		$jefes =  explode(',', $id_jefes);

		for ($i = 0; $i < count($jefes); $i++) {
			$datos = $this->usuarios->insetar_empleado_jefe($id_empleado, $jefes[$i]);
		}
		echo 1;
	}

	/*Metodo que activa o desactiva el usuario*/
	public function activar_OR_desactivar()
	{
		/*VALIDAMOS SI HAY DATOS DE SESSION*/
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos
			$this->load->model('usuarios');
			//traemos los parametros de la url
			$action = $this->input->GET('act');
			$id_usu = $this->input->GET('id_usu');
			if ($action == 0) {
				$this->usuarios->desactivarUser($id_usu);
				//	echo "bien desc";
			} elseif ($action == 1) {
				$this->usuarios->activarUser($id_usu);
				//	echo "bien act";
			}
			header("Location: " . base_url() . "Usuarios_admin?log=ok");
		}
	}

	/*Metodo que carga la infomacion para actualizar*/
	public function info_update()
	{
		/*VALIDAMOS SI HAY DATOS DE SESSION*/
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//obtenemos el id del usuario
			$id_usu = $this->input->get('idu');
			//llamamos los modelos necesarios
			$this->load->model('usuarios');
			$this->load->model('perfiles');
			$allperfiles = $this->perfiles->getAllPerfiles();
			$data = $this->usuarios->getUserById($id_usu);

			foreach ($data->result() as $key) {
				echo '
				<label>Desea cambias la Contraseña</label>
					<div class="row" style="left : 100px; position: relative;">
						<div class="form-check form-check-inline col" align="center">
							<input class="form-check-input" type="radio" name="passwd" id="exampleRadios1" value="NO" checked>
							<label for="paswd">NO</label>
						</div>
						<div class="form-check form-check-inline col" align="center">
							<input class="form-check-input" type="radio" name="passwd" id="exampleRadios1" value="SI">
							<label for="paswd">SI</label>
						</div>	
				    </div>
				';

				echo '
					<div class="row" align="center">
				    	<label for="combo_perfil">Seleccione un Perfil</label>
					    <select class="form-control form-control-sm js-example-basic-single" id="combo_perfil" name="combo_perfil" required="true">
					    	<option value="' . $key->id_perfil . '">' . $key->nom_perfil . '</option>';
				foreach ($allperfiles->result() as $p) {
					echo '<option value="' . $p->id_perfil . '">' . $p->nom_perfil . '</option>';
				}
				echo '
					</select>
				</div>	
				';

				echo '
					<input type="hidden" value="' . $key->id_usuario . '" name="id_usu">
					<input type="hidden" value="' . $key->nit . '" name="nit_usuario">
				';
			}
		}
	}

	/*Metodo que actualizar los datos del usuario*/
	public function actualizarUsuario()
	{
		/*VALIDAMOS SI HAY DATOS DE SESSION*/
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos
			$this->load->model('usuarios');
			//obtenemos los datos del form
			$radio = $this->input->post('passwd');
			$nit = $this->input->post('nit_usuario');
			$id_perfil = $this->input->post('combo_perfil');
			$id_usu = $this->input->post('id_usu');
			$this->load->library('encrypt');
			$pass_encript = $this->encrypt->encode($nit);
			if ($radio == "SI") {
				$data = array('id_perfil' => $id_perfil, 'pass' => $pass_encript);
				//echo $id_perfil." ".$nit;
				$this->usuarios->updateWithPass($data, $id_usu);
				header("Location: " . base_url() . "Usuarios_admin?log=ok");
			} elseif ($radio == "NO") {
				$data = array('id_perfil' => $id_perfil);
				//echo $id_perfil;
				$this->usuarios->updateWithOutPass($data, $id_usu);
				header("Location: " . base_url() . "Usuarios_admin?log=ok");
			} else {
			}
		}
	}

	/*Metodo que actualiza la contraseña del usuario*/
	public function changepass()
	{
		/*VALIDAMOS SI HAY DATOS DE SESSION*/
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//obtenemos los datos del formulario
			$pass1 = $this->input->POST('pass1');
			$pass2 = $this->input->POST('pass2');
			$id_usu = $this->input->POST('id_usu');
			$clave = $this->input->POST('clave');
			/* echo $pass1 . " " . $pass2 . " " . $id_usu; */
			$this->load->library('encrypt');
			//llamamos los modelos necesarions
			$this->load->model('usuarios');
			//validamos que las contraseñas coincidan
			if ($pass1 == $pass2) {
				$pass_encript = $this->encrypt->encode($pass2);
				$data = array('pass' => $pass_encript);
				$this->usuarios->updatePassword($id_usu, $data);
				$this->usuarios->updatePasswordVentas($id_usu, $clave);//Contraseña de Ventas
				echo 1;
				/* header("Location: " . base_url() . "login/iniciar"); */
			} else {
				/* header("Location: " . base_url() . "login/iniciar?log=err_p"); */
				echo 2;
			}
		}
	}

	/*Metodo que no hace nada xD*/
	public function prueba($value = '')
	{
		echo "bien";
	}

	/**************************** SEDES *************************************/

	/*Metodo que muestra todas la sedes dispobibles
	 *y nos indica cuales estan activas para el 
	 *usuario seleccionado
	*/
	public function add_sedes()
	{
		/*VALIDAMOS SI HAY DATOS DE SESSION*/
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//obtenemos el id del usuario
			$id_usu = $this->input->get('id_usu');
			//llamamos los modelos
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('sedes');
			$usu = $this->session->userdata('user');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);

			//obtenemos las sedes
			$allsedes = $this->sedes->getAllSedes();
			$data = array();
			foreach ($allsedes->result() as $key) {
				$nom_sede = $key->descripcion;
				$id_sede = $key->bodega;
				$val = $this->sedes->validarPermisos($id_usu, $id_sede);
				$estado = "";
				if ($val->n == 0) {
					$estado = "inactivo";
				} elseif ($val->n == 1) {
					$estado = "activo";
				}
				$data[] = array('id_sede' => $id_sede, 'nom_sede' => $nom_sede, 'estado' => $estado, 'id_usu' => $id_usu);
			}
			/* Validamos que tenga permisos el perfil para acceder a la vista*/
			$perfil = $this->session->userdata('perfil');
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			$validar_perfil = $this->menus->validarPermisosSubmenus('usuarios_admin', $perfil);
			if ($validar_perfil->n == 0) {
				$this->session->sess_destroy();
				header("Location: " . base_url());
			} else {
				$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'sedes' => $data);
				//abrimos la vista
				$this->load->view('agregar_sedes', $arr_user);
			}
		}
	}

	/*Metodo que agrega un usuario a una sede*/
	public function crear_usu_sedes()
	{
		/*VALIDAMOS SI HAY DATOS DE SESSION*/
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos el modelo
			$this->load->model('sedes');
			//traemos las variables de la url
			$id_usu = $this->input->get('id_usu');
			$id_sede = $this->input->get('id_sede');
			if ($this->sedes->insert_usu_sedes($id_usu, $id_sede)) {
				header("Location: " . base_url() . "usuarios_admin/add_sedes?id_usu=" . $id_usu . "&log=ok");
			}
		}
	}

	/*Metodo que elimina un usuario de una sede*/
	public function eliminar_usu_sedes()
	{
		/*VALIDAMOS SI HAY DATOS DE SESSION*/
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('sedes');
			//traemos las variables de la url
			$id_usu = $this->input->get('id_usu');
			$id_sede = $this->input->get('id_sede');
			if ($this->sedes->delete_usu_sedes($id_usu, $id_sede)) {
				header("Location: " . base_url() . "usuarios_admin/add_sedes?id_usu=" . $id_usu . "&log=ok");
			}
		}
	}


	public function insertarNitUser()
	{
		$this->load->model('usuarios');
		$nit = $this->input->POST('id');
		$result = $this->IngresarNitEmpleado->ver_datos($nit);
		$jefes = $this->IngresarNitEmpleado->ConsultaJefes();
		foreach ($result->result() as $key) {
			echo "
		<div class='form-group'>
		<input type='text' value='" . $key->id_empleado . "' class='form-control' id='NitEmpleadoCreado' name='NitEmpleadoCreado'>
	</div>
	";
		}
		foreach ($jefes->result() as $ke) {
			echo "
	<div class='form-group'>
		<label for='exampleInputPassword1'>Listado de Jefes</label>
		<select class='form-control' id='exampleFormControlSelect1'>
			<option class = 'd-none'>Seleciones los jefes</option>
			<option value= '" . $ke->nit_jefes . "'>'" . $ke->nombres . "'</option>
		</select>
	</div>";
		}
	}
	/*MODULO PARA AGREGAR Y QUITAR JEFES ASIGNADOS AL EMPLEADO  */
	public function ViewUpdateEmpleadoJefe()
	{

		$this->load->model('usuarios');
		$nit = $this->input->POST('id');
		$data = $this->usuarios->getJefesAll($nit);
		if ($data == "") {
			echo '<div class="table-responsive">
				<!--  Tabla Jefes  -->
				<table id="tablaJefe" class="table table-bordered table-hover text-center">
					<thead>
						<tr align="center">
							<th>ID JEFE</th>
							<th>NIT JEFE</th>
							<th>NOMBRE JEFE</th>
							<th>ACCIÓN</th>
						</tr>
					</thead>
					<tbody>
					<tr ><td  colspan="4">El empleado no tiene jefes asignados</td></tr>
					</tbody>
				</table>';
		} else {
			echo '<div class="table-responsive">
				<!--  Tabla Jefes  -->
				<table id="tablaJefe" class="table table-bordered table-hover text-center">
					<thead>
						<tr align="center">
							<th>ID JEFE</th>
							<th>NIT JEFE</th>
							<th>NOMBRE JEFE</th>
							<th>ACCIÓN</th>
						</tr>
					</thead>
					<tbody>';
			foreach ($data->result() as $key) {

				echo
				'
					<tr>
						<td scope="col">' . $key->id_jefe . '</td>
						<td scope="col">' . $key->nit_jefe . '</td>
						<td scope="col">' . $key->nombres . '</td>
						<td scope="col"><button class="btn btn-warning" type="button" onclick="eliminarJefeEmpleado(' . $key->id_jefe . ',' . $key->id_empleado . ')">Eliminar</button></td>
					</tr>
					';
			}

			echo '</tbody>
			</table>
			</div>';
		}
	}
	public function EliminarEmpleadoJefe()
	{
		$this->load->model('usuarios');
		$jefe = $this->input->POST('jefe');
		$empleado = $this->input->POST('empleado');

		$data = $this->usuarios->deleteEmpleadoJefe($jefe, $empleado);

		if ($data == "") {
			echo 'Error';
		} else {
			echo 'Ok';
		}
	}

	public function insertJefe(){
		$this->load->model('usuarios');
		$jefe = $this->input->POST('nitJefe');
		$mail = $this->input->POST('mailJefe');


		if ($this->usuarios->insertJefe($jefe, $mail)) {
			echo 'Ok';
		} else {
			echo 'Error';
		}
	}
	/* Funcion para cambiar la imagen de perfil del Usuario */
	public function changeImgUser(){
		$this->load->model('usuarios');
		$nit = $this->session->userdata('user');
		/*CARGAR ARCHIVO*/

		$config['upload_path'] = './public/usuarios/img_user';
		$config['allowed_types'] = 'jpeg|jpg|png|gif';
		$config['max_size'] = '10240000000';
		$config['file_name'] = $nit;
		$this->load->library('upload', $config);


		if (!$this->upload->do_upload('url_img_user_postv')) {
			/* header("Location: " . base_url() . "home"); */
		}
		$datas = $this->upload->data();
		$img = $datas['file_name'] ;
		if($this->usuarios->updateImgUser($img,$nit)){
			echo 1;
		}else {
			echo 0;
		}

	}
}
