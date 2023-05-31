<?php

/**
 * esta clase es el controlador de prueba creador por said
 */
class Mantenimiento extends CI_Controller
{

	public function index()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Mantenimiento_uno');


			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			/* Posible error al futuro */
			switch ($id_usu) {
				case 741:
					$where = "and bodega IN ('Barrancabermeja','Giron','Rosita')";
					break;
				case 548:
					$where = "and bodega IN ('Cucuta')";
					break;
				default:
					$where = "";
					break;
			}

			$datos = $this->Mantenimiento_uno->ver_equipos($where);
			$dto = $this->Mantenimiento_uno->ver_id();
			$bodegas =  $this->Mantenimiento_uno->TarerBodegas();
			$jefes = $this->Mantenimiento_uno->traer_jefes();
			$personalMantenimiento = $this->Mantenimiento_uno->getPersonalMantenimiento();
			$idFake = "";
			$equipos_f = $this->Mantenimiento_uno->equipo_nombre_familia($idFake);
			
			

			
			$arr_user = array(
				'pMantenimiento' => $personalMantenimiento, 'equiposF' => $equipos_f, 'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu,
				'data_tabla' => $datos, 'dato' => $dto, 'bodegas' => $bodegas, 'nit' => $usu, 'jefes' => $jefes
			);
			//abrimos la vista
			$this->load->view("mantenimiento/index", $arr_user);
		}
	}
	/* Agregar un equipo nuevo para sede o area */
	public function agregar_equipo()
	{

		$this->load->model('Mantenimiento_uno');
		$codigoF = $this->input->POST('nombreEquipo');
		$codigoN = $this->input->POST('nombreEquipo2');
		$bodega = $this->input->POST('nombreBodega');
		$codigoV = $this->input->POST('codigoE');
		$estado = $this->input->POST('estado');
		$area = $this->input->POST('nombrearea');
		$aliasEquipo = $this->input->POST('aliasEquipo');
		/* Agregar consecutivo al codigo... */
		$codigoV2 = $this->Mantenimiento_uno->obtener_Codigo_Consecutivo($codigoV);
		/* print_r($codigoV2->codigo);die; */
		if ($codigoV2 != "") {
			/* print_r("Entro al if del codigo");die; */
			$codigoV3 = $codigoV2->codigo;
			$codigo = ++$codigoV3;
		} else {
			/* print_r("Entro al else del codigo");die; */
			$codigo = $codigoV . "01";
			/* print_r($codigo);die; */
		}

		$nombre2 = $this->Mantenimiento_uno->getNombreEquipos($codigoF, $codigoN);

		$nombre = $nombre2->nombre_equipo;
		/* Obtener Nombre de Bodega */
		switch ($bodega) {
			case 'B':
				$bodega = "Barrancabermeja";
				break;
			case 'C':
				$bodega = "Cucuta";
				break;
			case 'G':
				$bodega = "Giron";
				break;
			case 'R':
				$bodega = "Rosita";
				break;

			default:
				$bodega = "No sirve";
				break;
		}
		/* Obtener nombre de [area] */
		switch ($area) {
			case 'L':
				$area = "Lamina y pintura";
				break;
			case 'M':
				$area = "Gasolina";
				break;
			case 'D':
				$area = "Mecanica diesel";
				break;
			case 'A':
				$area = "Alistamiento";
				break;

			default:
				$area = "Chevy express";
				break;
		}
		/*  */
		/**Autor: Sergio Galvis 
		 * Fecha: 18 de Abril del 2022
		 */
		/*CARGAR ARCHIVO*/
		$config['upload_path'] = './public/mantenimiento/cv_equipos';
		$config['allowed_types'] = '*';
		$config['max_size'] = '10240000000';
		$this->load->library('upload', $config);


		if (!$this->upload->do_upload('imagen_cv')) {
			header("Location: " . base_url() . "mantenimiento/index");
		}
		$datas = $this->upload->data();
		$img = $datas['file_name'];

		$res = $this->Mantenimiento_uno->agregar_registro($nombre, $bodega, $codigo, $estado, $area, $img,$aliasEquipo);
		if ($res) {
			header("Location: " . base_url() . "mantenimiento/index?log=okCrear");
		} else {
			header("Location: " . base_url() . "mantenimiento/index?log=errCrear");
		}
	}
	/* Listar equipos para crear equipos en sedes y areas */
	public function getCodigoEquipo()
	{
		$this->load->model("Mantenimiento_uno");
		$codigoF = $this->input->GET('codigo');
		$codigoN = "";
		$data = $this->Mantenimiento_uno->getNombreEquipos($codigoF, $codigoN);
		echo '
		<label for="">Nombre de Equipos</label>
		<select class=" form-control" id="nombreEquipo2" name="nombreEquipo2" required>
		';

		foreach ($data->result() as $key) {
			echo '
				<option autocapitalize="true" value="' . $key->codigo_equipo . '">' . $key->nombre_equipo . '</option>			
			';
		}
		echo '</select>';
	}
	public function agregar_nuevo_mantenimiento()
	{
		$this->load->model('Mantenimiento_uno');
		$nombre = $this->input->POST('nombre');
		$bodega = $this->input->POST('bodega');
		$sede = $this->input->POST('sede');
		$codigo = $this->input->POST('codigo');
		$estado = $this->input->POST('estado');
		$area = $this->input->POST('area');
		/**Autor: Sergio Galvis 
		 * Fecha: 18 de Abril del 2022
		 */
		$cv_equipo =  $this->input->POST('cv_equipo');
		/*CARGAR ARCHIVO*/
		$config['upload_path'] = './public/mantenimiento/cv_equipos';
		$config['allowed_types'] = '*';
		$config['max_size'] = '10240000000';
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload($cv_equipo)) {
			header("Location: " . base_url() . "mantenimiento/index");
		}
		$datas = $this->upload->data();
		$img = $datas['file_name'];
		print_r($img);
		die;

		$res = $this->Mantenimiento_uno->agregar_registro($nombre, $bodega, $sede, $codigo, $estado, $area, $img);
		if ($res) {
			echo 'Exito';
			header("Location: " . base_url() . "mantenimiento/index?log=ok");
		} else {
			echo 'Fallido';
			header("Location: " . base_url() . "mantenimiento/index?log=err");
		}
	}

	public function cambio_estado()
	{
		$this->load->model('Mantenimiento_uno');
		$estado = $this->input->GET('estado');
		$id = $this->input->GET('id');
		$result = $this->Mantenimiento_uno->nuevo_estado($id, $estado);
		if ($result) {
			echo "Exito";
		} else {
			echo "";
		}
	}


	public function traer_datos()
	{
		$this->load->model('Mantenimiento_uno');
		$id = $this->input->GET('id');
		$result = $this->Mantenimiento_uno->ver_datos($id);
		$url = base_url();
		foreach ($result->result() as $key) {
			echo
			' <form  action="' . $url . 'mantenimiento/editar_equipo" method="POST" enctype="multipart/form-data">
			<div class="form-row">
					<input value="' . $key->id_equipo . '" id="cdo" name="cdo" type="text" class="form-control d-none">
				<div class="col-lg-6 col-sm-6 col-xs-6">
					<label for="">Alias del equipo</label>
					<input value="'.$key->alias_equipo.'" class="form-control" type="text" id="aliasEquipo" name="aliasEquipo" placeholder="Escriba el alias del equipo aquí." required>
				</div>
				<div class="col-lg-6 col-sm-6 col-xs-6">
					<label for="">Nombre del Equipos</label>
					<input readonly value="' . $key->nombre_equipo . '" id="editarEquipo" name="editarEquipo" type="text" class="form-control">
				</div>
				<div class="col-lg-16 col-md-6 col-sm-6">
				<label for="">Bodega</label>
				<div class="form-group">
					<input readonly value="' . $key->bodega . '" id="editarbodega" name="editarbodega" type="text" class="form-control">
				</div>
			</div>
				
				<div class="col-lg-6 col-sm-6">
					<label for="codigo">Codigo</label>
					<input readonly value="' . $key->codigo . '" id="editarcodigo" name="editarcodigo" type="text" class="form-control">
				</div>
				<div class="col-lg-6 col-sm-6">
					<div class="form-group">
						<label for="estado">Estado</label>
						<input required readonly value="' . $key->estado . '" id="ediestado" name="ediestado" type="text" class="form-control">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
				<label for="">Area</label>
				<input readonly value="' . $key->area . '" id="ediarea" name="ediearea" type="text" class="form-control">

				</div>';
			if ($key->cv_equipo) {
				echo '
				<div class="col-lg-6 col-sm-6">
					<label for="">Seleccione la hoja de vida</label>
					<input value="" id="equipo_cv" name="equipo_cv" type="file" class="form-control">
					<hr>
					<label for="">Descargar hoja de vida</label>
					<a href="' . base_url() . 'public/mantenimiento/cv_equipos/' . $key->cv_equipo . '" target="_blank" class="btn btn-info">
					' . $key->cv_equipo . '
					</a>
				</div>';
			} else {
				echo '
				<div class="col-lg-6 col-sm-6">
					<label for="">Hoja de vida Equipo</label>
					<input id="equipo_cv" name="equipo_cv" type="file" class="form-control">
				</div>';
			}


			echo '</div>
			<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
					<button class="btn btn-success">Editar</button>
				</div>
		</form>';
		}
	}
	public function editar_equipo()
	{
		$this->load->model('Mantenimiento_uno');
		$id = $this->input->POST('cdo');
		$nombre = $this->input->POST('editarEquipo');
		$bodega = $this->input->POST('editarbodega');
		$codigo = $this->input->POST('editarcodigo');
		$estado = $this->input->POST('ediestado');
		$area = $this->input->POST('ediearea');
		$cv = $this->input->POST("equipo_cv");
		$aliasEquipo = $this->input->POST("aliasEquipo");

		/*CARGAR ARCHIVO*/
		$config['upload_path'] = './public/mantenimiento/cv_equipos';
		$config['allowed_types'] = '*';
		$config['max_size'] = '10240000000';
		$this->load->library('upload', $config);
		$img = "";
		if ($this->upload->do_upload('equipo_cv')) {
			$datas = $this->upload->data();
			$img = $datas['file_name'];
		}
		$result = $this->Mantenimiento_uno->editar_equipo($id, $nombre, $bodega, $codigo, $estado, $area, $img,$aliasEquipo);
		if ($result) {
			echo "Exito";
			header("Location: " . base_url() . "mantenimiento/index");
		} else {
			echo "";
		}
	}
	public function pintar_tabla_por_sede_y_bodega()
	{
		$this->load->model('Mantenimiento_uno');
		$sede = $this->input->POST('sede');
		$bodega = $this->input->POST('bodega');
		$estado = "Activo";
		if ($sede != "" && $bodega != "") {
			$where = "WHERE  bodega = '$sede' AND area = '$bodega' AND estado =  '$estado'";
		} elseif ($sede == "" && $bodega != "") {
			$where = "WHERE  area = '$bodega' AND estado =  '$estado'";
		} elseif ($sede != "" && $bodega == "") {
			$where = "WHERE  bodega = '$sede' AND estado =  '$estado'";
		}
		$datos = $this->Mantenimiento_uno->traer_equipos_por_sede_y_bodega($where);
		echo "
		<table class='table nowrap table-striped' id='tabla_uno'>
		<button type='button'id='nuevoEquipo' class='btn btn-success float-right' onclick='bajar_excel_tabla_dos()'>
		Descargar   &nbsp; <span><i class='fas fa-file-excel'></i></span>
		</button>
		<thead class='bg-dark'>
			<tr>
				<th style='vertical-align: middle'>Codigo</th>
				<th style='vertical-align: middle'>Familia del Equipo</th>
				<th style='vertical-align: middle'>Nombre del Equipo</th>
				<th style='vertical-align: middle' class='text-center'>Estado</th>
				<th style='vertical-align: middle' class='text-center'>Bodega</th>
				<th style='vertical-align: middle' class='text-center'>Area</th>
				<th style='vertical-align: middle' class='text-center'>Mantenimiento</th>
				<th style='vertical-align: middle' class='text-center'>CV Equipo</th>
				<th style='vertical-align: middle' class='text-center'>Historial</th>
				<th style='vertical-align: middle' class='text-center'>Editar</th>
				<th style='vertical-align: middle' class='text-center'>Retirar</th>
			</tr>
		</thead>
		<tbody>
		";

		foreach ($datos->result() as $key) {
			$color = '';
			if ($key->estado == 'Activo') {
				$color = '#56CC0A';
			} else if ($key->estado == 'Reparacion') {
				$color = '#F3DA0A ';
			}
			$bandera = "";
			if ($key->cv_equipo) {
				$bandera = '<td class="text-center"><a href="' . base_url() . 'public/mantenimiento/cv_equipos/' . $key->cv_equipo . '" target="_blank" type="button" class="btn btn-primary"><i class="fas fa-file-download"></i></a></td>';
			} else {
				$bandera = '<td class="text-center"><button disabled target="_blank" type="button" class="btn btn-primary"><i class="fas fa-file-excel"></i></button></td>';
			}

			$estado = '';
			if ($key->estado == 'Reparacion') {
				$estado = 'disabled';
			} else if ($key->estado == 'Activo') {
				$estado = 'enabled';
			}
			echo '
	
			<tr>
				<td class="text-center">' . $key->codigo . '</td>
				<td class="text-center">' . $key->nombre_equipo . '</td>
				<td class="text-center">' . ($key->alias_equipo != "" ? $key->alias_equipo : '--') . '</td>
				<td class="text-center" style = "color:' . $color . '" ><strong>' . $key->estado . ' </strong></td>  
				<td class="text-center"  >' . $key->bodega . '</td>               
				<td class="text-center"  >' . $key->area . '</td>  
				<td class="text-center"><button ' . $estado . ' class="tx btn btn-success shadow" data-toggle="modal" data-target="#modalMantenimiento" id="' . $key->id_equipo . '" onclick="traer(this.id);"><i class="fas fa-plus-square"></i></button></td>
				' . $bandera . '
				<td class="text-center"><button class="tx btn btn-info shadow"  id="' . $key->id_equipo . '" onclick="ver(this.id)"><i class="far fa-eye"></i></button></td>  
				<td class="text-center"><button class="tx btn btn-warning shadow" data-toggle="modal" data-target="#modaledtequipo" id="' . $key->id_equipo . '" onclick="pintar_datos(this.id)"><i class="text-white far fa-edit"></i></button></td>  
				<td class="text-center"><button class="tx btn btn-danger shadow" id="' . $key->id_equipo . '" onclick="estado(this.id)"><i class="far fa-times-circle"></i></button></td>              
			</tr>			
			';
		}
		echo "
		</tbody>
		</table>
		";
	}

	/************************************* SOLICITUD DE MANTENIMIENTO ************************************************/
	/**Autor: Sergio Galvis 
	 * Fecha: 04 de Abril del 2022
	 */
	/* Funciones para el perfil de jefes */
	public function solicitud_mantenimieto()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Mantenimiento_uno');
			$this->load->model('sedes');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);

			foreach ($userinfo->result() as $nombre) {
				$nomb = $nombre->nombres;
			}
			/* Bodegas enlazadas con el encargado de mantenimiento */
			$data_sedes = $this->sedes->get_sedes_user($usu);
			$sedes_usu = "";
			foreach ($data_sedes->result() as $key) {
				$sedes_usu .= $key->idsede . ",";
			}

			$sedes_usu = trim($sedes_usu, ",");
			/* print_r($sedes_usu);die; */

			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//print_r($perfil_user->id_perfil);die;
			//$allsubmenus = $this->menus->getAllSubmenus();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'pass' => $pass, 'id_usu' => $id_usu, 'nomb' => $nomb);
			/* Array con datos de la solictud */
			/* Nombre de las bodegas o sedes */
			$bodegas = $this->Mantenimiento_uno->traer_bodegas_mto();
			/* Validar perfil del usuario para mostrar las solicitudes de mantenimiento */
			/* Traer el nombre de los equipos de mantenimiento */
			$equiposMto = $this->Mantenimiento_uno->ver_equipos();
			if ($perfil_user->id_perfil === 46) {
				$solicitud_mto = $this->Mantenimiento_uno->ver_solicitud_mantenimiento_td($sedes_usu);
				/* print_r($solicitud_mto->result());die; */
				//parseamos a json
				$data_solicitud_mto[] = array();
				foreach ($solicitud_mto->result() as $key) {

					$color = "";
					if ($key->estado == "1") { //pendiente
						$color = "#0064FF";
					} elseif ($key->estado == "2") { //confirmado
						$color = "#009C13";
					} else {
						$color = "#ffc107"; //en proceso
					}
					$nombre_sede = $this->Mantenimiento_uno->traer_name_bodegas_mto($key->sede);
					foreach ($nombre_sede->result() as $valor) {
						$sede_name = $valor->descripcion;
					}

					$data_solicitud_mto[] = array('id' => $key->id_solicitud, 'title' => $key->jefeN . '-' . $sede_name, 'start' => $key->fecha_solicitud, 'end' => $key->fecha_solicitud, 'descripcion' => $key->respuesta, 'color' => $color);
				}
				//print_r($ausen_json);die;
				$arr_user = array('equiposMto' => $equiposMto,'userdata' => $userinfo, 'menus' => $allmenus, 'pass' => $pass, 'id_usu' => $id_usu, 'perfil' => $perfil_user->id_perfil, 'nomb' => $nomb, 'bodegas' => $bodegas, 'data_solicitud' => $solicitud_mto, 'solicitud_mto' => json_encode($data_solicitud_mto));
				//abrimos la vista
				$this->load->view("mantenimiento/solicitud_mto_encargados", $arr_user);
			} elseif ($perfil_user->id_perfil === 1 || $perfil_user->id_perfil === 20 || $perfil_user->id_perfil === 26) {
				$solicitud_mto = $this->Mantenimiento_uno->ver_solicitud_mantenimiento_admins();
				/* print_r($solicitud_mto->result());die; */
				//parseamos a json
				$data_solicitud_mto[] = array();
				foreach ($solicitud_mto->result() as $key) {
					$color = "";
					if ($key->estado == "1") { //pendiente
						$color = "#0064FF";
					} elseif ($key->estado == "2") { //confirmado
						$color = "#009C13";
					} else {
						$color = "#ffc107"; //en proceso
					}
					$nombre_sede = $this->Mantenimiento_uno->traer_name_bodegas_mto($key->sede);
					foreach ($nombre_sede->result() as $valor) {
						$sede_name = $valor->descripcion;
					}

					$data_solicitud_mto[] = array('id' => $key->id_solicitud, 'title' => $key->jefeN . '-' . $sede_name, 'start' => $key->fecha_solicitud, 'end' => $key->fecha_solicitud, 'descripcion' => $key->respuesta, 'color' => $color);
				}
				//print_r($ausen_json);die;
				$arr_user = array('equiposMto' => $equiposMto,'userdata' => $userinfo, 'menus' => $allmenus, 'pass' => $pass, 'id_usu' => $id_usu, 'perfil' => $perfil_user->id_perfil, 'nomb' => $nomb, 'bodegas' => $bodegas, 'data_solicitud' => $solicitud_mto, 'solicitud_mto' => json_encode($data_solicitud_mto));
				//abrimos la vista
				$this->load->view("mantenimiento/solicitud_mto_encargados", $arr_user);
			} else {
				$jefe = $this->session->userdata('user');
				$solicitud_mto = $this->Mantenimiento_uno->ver_solicitud_mantenimiento($jefe);
				//parseamos a json
				$data_solicitud_mto[] = array();
				foreach ($solicitud_mto->result() as $key) {
					$color = "";
					if ($key->estado == "1") { //pendiente
						$color = "#0064FF";
					} elseif ($key->estado == "2") { //confirmado
						$color = "#009C13";
					} else {
						$color = "#ffc107"; //en proceso
					}
					$nombre_sede = $this->Mantenimiento_uno->traer_name_bodegas_mto($key->sede);
					foreach ($nombre_sede->result() as $valor) {
						$sede_name2 = $valor->descripcion;
					}


					$data_solicitud_mto[] = array('id' => $key->id_solicitud, 'title' => $key->nombreJ . '-' . $sede_name2, 'start' => $key->fecha_solicitud, 'end' => $key->fecha_solicitud, 'descripcion' => $key->respuesta, 'color' => $color);
				}
				//print_r($ausen_json);die;
				$arr_user = array('equiposMto' => $equiposMto, 'userdata' => $userinfo, 'menus' => $allmenus, 'pass' => $pass, 'id_usu' => $id_usu, 'perfil' => $perfil_user->id_perfil, 'nomb' => $nomb, 'bodegas' => $bodegas, 'data_solicitud' => $solicitud_mto, 'solicitud_mto' => json_encode($data_solicitud_mto));
				//abrimos la vista
				$this->load->view("mantenimiento/solicitud_mantenimiento", $arr_user);
			}
		}
	}
	/* Funciones para el perfil de jefe */
	public function ver_info_evento()
	{
		//cargamos los modelos
		$this->load->model('Mantenimiento_uno');
		//traemos las variables
		$id = $this->input->GET('id');
		//$mantenimiento = $this->Mantenimiento_uno->ver_solicitud_mantenimiento($id);
		$mantenimiento = $this->Mantenimiento_uno->get_solicitud_mantenimiento_by_id($id);

		foreach ($mantenimiento->result() as $key) {

			if ($key->urgencia == 1) {
				$nivel = "Leve";
				$color = "background-color:green; color:white";
			} elseif ($key->urgencia == 2) {
				$nivel = "Moderada";
				$color = "background-color:#ffc107; color:white";
			} else {
				$nivel = "Urgente";
				$color = "background-color:red; color:white";
			}
			$nombre_sede = $this->Mantenimiento_uno->traer_name_bodegas_mto($key->sede);
			foreach ($nombre_sede->result() as $valor) {
				$sede_name2 = $valor->descripcion;
			}
			$codigoEquipoNombre = "";
			if ($key->codigo != "" && $key->nombre_equipo) {
				$codigoEquipoNombre = '
				<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="cargo">Codigo:</label>
									<input type="text" class="form-control" id="jefe" value="' . $key->codigo . '" readonly>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="cargo">Nombre equipo:</label>
									<input type="text" class="form-control" id="encargado" value="' . $key->nombre_equipo . '" readonly >
								</div>
							</div>
						</div>
				';
			}

			echo '
		
			<div class="card-body">
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="cargo">Fecha de solicitud:</label>
							<input type="text" class="form-control" id="fecha_ini_ver" value="' . $key->fecha_solicitud . '" readonly>
						</div>							
					</div>
					<div class="col">
						<div class="form-group">
							<label for="cargo">Fecha de inicio:</label>
								<input type="text" class="form-control" id="hora_ini_ver" placeholder="Aún no se ha iniciado" value="' . $key->fecha_inicio . '" readonly>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="cargo">Fecha de finalización:</label>
							<input type="text" class="form-control" id="hora_fin_ver" placeholder="Aún no se ha finalizado" value="' . $key->fecha_finalizacion . '" readonly>
						</div>
					</div>
				</div>
				<div class="row">		
					<div class="col">
						<div class="form-group">
							<label for="cargo">Sede:</label>
							<input type="text" class="form-control" id="fecha_ini_ver" value="' . $sede_name2 . '" readonly>
						</div>							
					</div>
					<div class="col">
						<div class="form-group">
							<label for="cargo">Urgencia:</label>
							<input style="' . $color . '" type="text" class="form-control" id="urgencia123" value="' . $nivel . '" readonly>
						</div>							
					</div>		
					<div class="col">
						<div class="form-group">
							<label for="cargo">Imagen:</label>
								<div class="form-group col-md-12">';
			if ($key->imagen) {
				echo '<a href="' . base_url() . 'public/mantenimiento/solicitudes/' . $key->imagen . '" target="_blank">
										<img style="display:block" width="80px" src="' . base_url() . 'public/mantenimiento/solicitudes/' . $key->imagen . '">
										Click en la imagen</a>									
										';
			} else {
				echo 'La solicitud no tiene imagen';
			}
			echo '
								</div>
						</div>							
					</div>				
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="cargo">Jefe</label>
								<input type="text" class="form-control" id="cargo_emp_ver" value="' . $key->nombreJ . '" readonly>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="cargo">Encargado</label>
								<input type="text" class="form-control" id="motivo_ver" placeholder="Aún no tiene encargado" value="' . $key->nombreE . '" readonly>
						</div>
					</div>
				</div>
				' . $codigoEquipoNombre . '
				<div class="row">
					<label for="descrp">Solicitud:</label>
						<textarea disabled class="form-control" >' . $key->solicitud  . '</textarea>
				</div>
				<div class="row">
							<div class="col">
								<div class="form-group">								
									<label for="cargo">Respuesta</label>
									<textarea disabled class="form-control" minlength="15" id="" required="true">' . $key->respuesta . '</textarea>
																
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="cargo">Imagen:</label>
										<div class="form-group col-md-12">';
			if ($key->imagen_respuesta) {
				echo '<a href="' . base_url() . 'public/mantenimiento/solicitudes/' . $key->imagen_respuesta . '" target="_blank">
												<img style="display:block" width="80px" src="' . base_url() . 'public/mantenimiento/solicitudes/' . $key->imagen_respuesta . '">
												Click en la imagen</a>									
												';
			} else {
				echo 'La solicitud no tiene imagen de evidencia';
			}
			$tiempo = "";
			if ($key->tiempo_estimado) {
				$tiempo = '<div class="col-md-6">
								<label>Tiempo estimado[Horas]</label>
								<input class="form-control" type="number" id="tiempo_estimado" name="tiempo_estimado" value="' . $key->tiempo_estimado  . '" readonly >
							</div>';
			}
			echo '
										</div>
								</div>
							</div>
						</div>
				
				<div class="loader" id="cargando"></div>
						<div class="row">
							<div class="col-md-6" style="align-self: end;">
								<a  class="btn btn-success" onclick="modal_msn(' . $key->id_solicitud  . ');">Ver mensajes</a>
							</div>
							' . $tiempo . '
						</div>
				
				';
			echo '
								<div class="row">
									<div class="col">
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cerrar</button>
										</div>	
									</div>
								</div>';

			echo '
				</div>
				<!-- /.card-body -->
				';
		}
	}
	public function new_solicitud()
	{

		//cargamos los modelos
		$this->load->model('Mantenimiento_uno');
		$this->load->model('usuarios');
		$this->load->model('uploadfile');

		//TRAEMOS LAS VARIABLES
		$jefe = $this->session->userdata('user');
		$fecha_solicitud = date('Y-m-d');
		$solicitud = $this->input->POST("solicitud");
		$urgencia = $this->input->POST('urgencia');
		$sedeBodega = $this->input->POST('sedeBodega');
		$equipoId = $this->input->POST("equipoId");
		$estado = 1;
		/*CARGAR ARCHIVO*/
		$config['upload_path'] = './public/mantenimiento/solicitudes';
		$config['allowed_types'] = '*';
		$config['max_size'] = '10240000000';
		$this->load->library('upload', $config);

		$this->upload->do_upload('archivo');
		/* if (!$this->upload->do_upload('archivo')) {
			header("Location: " . base_url() . "mantenimiento/index?log=img");
		} */
		$datas = $this->upload->data();
		$img = $datas['file_name'];
		//creamos un array con los datos recibidos
		if ($equipoId == "" || $equipoId == "N/A") {
			$data = array('jefe' => $jefe, 'fecha_solicitud' => $fecha_solicitud, 'solicitud' => $solicitud, 'estado' => $estado, 'urgencia' => $urgencia, 'sede' => $sedeBodega, 'imagen' => $img);
		} else {
			$data = array('jefe' => $jefe, 'fecha_solicitud' => $fecha_solicitud, 'solicitud' => $solicitud, 'estado' => $estado, 'urgencia' => $urgencia, 'sede' => $sedeBodega, 'imagen' => $img, 'id_equipo' => $equipoId);
		}


		if ($this->Mantenimiento_uno->insert_solicitud_mto($data)) {
			echo "ok";
			header("Location: " . base_url() . "mantenimiento/solicitud_mantenimieto");
		} else {
			echo "err";
			header("Location: " . base_url() . "mantenimiento/solicitud_mantenimieto");
		}
	}
	/* Funciones para el perfil de mantenimiento encargado */
	public function ver_info_evento_encargado()
	{
		//cargamos los modelos
		$this->load->model('Mantenimiento_uno');
		$this->load->model('perfiles');
		//traemos las variables
		$id = $this->input->GET('id');
		//$mantenimiento = $this->Mantenimiento_uno->ver_solicitud_mantenimiento($id);
		$mantenimiento = $this->Mantenimiento_uno->get_solicitud_mantenimiento_by_id($id);
		//si ya hay datos de session los carga de nuevo
		$usu = $this->session->userdata('user');
		//obtenemos el perfil del usuario
		$perfil_user = $this->perfiles->getPerfilByUser($usu);

		foreach ($mantenimiento->result() as $key) {
			/* Validacion para el campo de emergencia */
			if ($key->urgencia == 1) {
				$nivel = "Leve";
				$color = "background-color:green; color:white";
			} elseif ($key->urgencia == 2) {
				$nivel = "Moderada";
				$color = "background-color:#ffc107; color:white";
			} else {
				$nivel = "Urgente";
				$color = "background-color:red; color:white";
			}
			$nombre_sede = $this->Mantenimiento_uno->traer_name_bodegas_mto($key->sede);
			foreach ($nombre_sede->result() as $valor) {
				$sede_name2 = $valor->descripcion;
			}
			$codigoEquipoNombre = "";
			if ($key->codigo != "" && $key->nombre_equipo) {
				$codigoEquipoNombre = '
				<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="cargo">Codigo:</label>
									<input type="text" class="form-control" id="jefe" value="' . $key->codigo . '" readonly>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="cargo">Nombre equipo:</label>
									<input type="text" class="form-control" id="encargado" value="' . $key->nombre_equipo . '" readonly >
								</div>
							</div>
						</div>
				';
			}

			echo '
					<div class="card-body">
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="cargo">Fecha de solicitud</label>
										<input type="hidden" class="form-control" id="id_solicitud" value="' . $key->id_solicitud . '" readonly>
										<input type="text" class="form-control" id="fecha_solicitud-1" value="' . $key->fecha_solicitud . '" readonly>
								</div>							
							</div>

							<div class="col">
								<div class="form-group">
									<label for="cargo">Fecha de inicio</label>
									<input type="text" class="form-control" id="fecha_inicio" value="' . $key->fecha_inicio . '" readonly>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="cargo">Fecha de finalización</label>
									<input type="text" class="form-control" id="fecha_finalizacion" value="' . $key->fecha_finalizacion . '" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="cargo">Sede:</label>
									<input type="text" class="form-control" id="fecha_ini_ver" value="' . $sede_name2 . '" readonly>
								</div>							
							</div>
							<div class="col-3">
								<div class="form-group">
									<label for="cargo">Urgencia:</label>
									<input style="' . $color . '" type="text" class="form-control" id="urgencia123" value="' . $nivel . '" readonly>
								</div>							
							</div>
							<div class="col">
								<div class="form-group">
									<label for="cargo">Imagen:</label>
										<div class="form-group col-md-12">';
			if ($key->imagen) {
				echo '<a href="' . base_url() . 'public/mantenimiento/solicitudes/' . $key->imagen . '" target="_blank">
												<img style="display:block" width="80px" src="' . base_url() . 'public/mantenimiento/solicitudes/' . $key->imagen . '">
												Click en la imagen</a>									
												';
			} else {
				echo 'La solicitud no tiene imagen';
			}
			echo '
										</div>
								</div>							
							</div>
							
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="cargo">Jefe</label>
									<input type="text" class="form-control" id="jefe" value="' . $key->nombreJ . '" readonly>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="cargo">Encargado</label>
									<input type="hidden" class="form-control" id="encargadoNit" value="' . $key->encargado . '" readonly>
									<input type="text" class="form-control" id="encargado" value="' . $key->nombreE . '" readonly >
								</div>
							</div>
						</div>
						' . $codigoEquipoNombre . '
						<div class="row">
							<div class="col">
								<div class="form-group">								
									<label for="cargo">Solicitud</label>
									<textarea disabled class="form-control" minlength="15" id="respuesta" required="true">' . $key->solicitud . '</textarea>
																
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">								
									<label for="cargo">Respuesta</label>
									<textarea disabled class="form-control" minlength="15" id="" required="true">' . $key->respuesta . '</textarea>
																
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="cargo">Imagen:</label>
										<div class="form-group col-md-12">';
			if ($key->imagen_respuesta) {
				echo '<a href="' . base_url() . 'public/mantenimiento/solicitudes/' . $key->imagen_respuesta . '" target="_blank">
												<img style="display:block" width="80px" src="' . base_url() . 'public/mantenimiento/solicitudes/' . $key->imagen_respuesta . '">
												Click en la imagen</a>									
												';
			} else {
				echo 'La solicitud no tiene imagen de evidencia';
			}
			$tiempo = "";
			if ($key->tiempo_estimado) {
				$tiempo = '<div class="col-md-6">
								<label>Tiempo estimado[Horas]</label>
								<input class="form-control" type="number" id="tiempo_estimado" name="tiempo_estimado" value="' . $key->tiempo_estimado  . '" readonly >
							</div>';
			}
			echo '
										</div>
								</div>
							</div>
						</div>
						<div class="loader" id="cargando"></div>
						<div class="row">
							<div class="col-md-6" style="align-self: end;">
								<a  class="btn btn-success" onclick="modal_msn(' . $key->id_solicitud  . ');">Ver mensajes</a>
							</div>
							' . $tiempo . '
							
							
							
						</div>
				';

			if ($perfil_user->id_perfil == 1  || $perfil_user->id_perfil == 20) {
				echo '
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cerrar</button>
					</div>
				';
			} else {
				if ($key->fecha_inicio && $key->fecha_finalizacion) {
					echo '
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cerrar</button>
					</div>
				';
				} elseif ($key->fecha_inicio && !$key->fecha_finalizacion) {
					echo '
					<div class="modal-footer">
						<button type="button" id="btn_finalizar" class="btn btn-warning btn-flat" onclick="modal_finalizar_evento(' . $key->id_solicitud  . ');">Finalizar</button>
						<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cerrar</button>
					</div>
				';
				} elseif (!$key->fecha_inicio && !$key->fecha_finalizacion) {
					echo '
					<div class="modal-footer">
						<label>Tiempo estimado[Horas]:</label>
						<input type="number" min="1" name="tiempoEstimado" id="tiempoEstimado">
						<button type="button" id="btn_iniciar" class="btn btn-success btn-flat" onclick="iniciar_evento();">Iniciar</button>
						<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cerrar</button>
					</div>
				';
				}
			}
			echo '
				</div>
			';
		}
	}
	/* Agregar nuevo mensaje a la solicitud de mantenimiento */
	public function agregar_new_msm_jefe()
	{
		//cargamos los modelos
		$this->load->model('Mantenimiento_uno');
		$this->load->model('usuarios');

		//TRAEMOS LAS VARIABLES
		$emisor = $this->session->userdata('user');
		$id_solicitud = $this->input->POST("id_solicitud");
		$msm = $this->input->POST("new_msm");
		$nombre_emisor = $this->usuarios->getUserByNit($emisor);
		$n_emisor = $nombre_emisor->nombres;
		/* print_r($emisor . $id_solicitud . $msm . $nombre_emisor->nombres);die; */

		$data = array('mensaje' => $msm, 'emisor' => $emisor, 'id_solicitud' => $id_solicitud, 'nombre_emisor' => $n_emisor);
		$result = $this->Mantenimiento_uno->crear_chat_solocitud($data);

		if ($result) {
			echo 'Exito';
			header("Location: " . base_url() . "mantenimiento/solicitud_mantenimieto?log=n_msm");
		} else {
			echo 'Fallido';
			header("Location: " . base_url() . "mantenimiento/solicitud_mantenimieto?log=f_msm");
		}
	}
	/* Cargar Mensajes */
	public function load_msn_solicitudes()
	{
		$this->load->model('Mantenimiento_uno');
		//traemos los parametros
		$id_soli = $this->input->GET('id_soli');
		$data_solicitud = $this->Mantenimiento_uno->get_solicitud_mantenimiento_by_id($id_soli);
		foreach ($data_solicitud->result() as $key) {
			$jefe = $key->jefe;
			$encargado = $key->encargado;
			$f_solicitud = $key->fecha_solicitud;
			$f_incio = $key->fecha_inicio;
			$f_final = $key->fecha_finalizacion;
			$estado = $key->estado;
		}
		$data_msn = $this->Mantenimiento_uno->get_respuestas_solicitud($id_soli);
		echo '
			<table class="table table-bordered">
			<input type="hidden" name="id_prueba" id="id_prueba" value="' . $id_soli . '">
			<input type="hidden" name="jefe_s" id="jefe_s" value="' . $jefe . '">
			<input type="hidden" name="encargado_s" id="encargado_s" value="' . $encargado . '">
			<input type="hidden" name="f_soli" id="f_soli" value="' . $f_solicitud . '">
			<input type="hidden" name="f_inicio" id="f_inicio" value="' . $f_incio . '">
			<input type="hidden" name="f_final" id="f_final" value="' . $f_final . '">
			<input type="hidden" name="estado" id="estado" value="' . $estado . '">
			<thead>
				<th>Emisor</th>
				<th>Mensaje</th>
			</thead>
			<tbody>
			';
		foreach ($data_msn->result() as $key) {
			echo '
				<tr aling="center">
					<th scope="row" align="left">' . $key->nombre_emisor . '</th>
					<td scope="row">' . $key->mensaje . '</td>
				</tr>
				';
		}
		echo '
			</tbody>
			</table>
			';
	}
	public function agregar_new_msm()
	{
		//cargamos los modelos
		$this->load->model('Mantenimiento_uno');
		$this->load->model('usuarios');

		//TRAEMOS LAS VARIABLES
		$emisor = $this->session->userdata('user');
		$id_solicitud = $this->input->GET("id_soli_msn");
		$msm = $this->input->GET("msn");
		$nombre_emisor = $this->usuarios->getUserByNit($emisor);
		$n_emisor = $nombre_emisor->nombres;
		/* print_r($emisor . $id_solicitud . $msm . $nombre_emisor->nombres);die; */

		$data = array('mensaje' => $msm, 'emisor' => $emisor, 'id_solicitud' => $id_solicitud, 'nombre_emisor' => $n_emisor);
		$result = $this->Mantenimiento_uno->crear_chat_solocitud($data);

		if ($result) {
			echo 'ok';
		} else {
			echo 'err';
		}
	}

	public function iniciar_solicitud()
	{

		//cargamos los modelos
		$this->load->model('Mantenimiento_uno');
		$this->load->model('usuarios');
		//TRAEMOS LAS VARIABLES
		$encargado = $this->session->userdata('user');
		$id = $this->input->GET('id_solicitud');
		$tiempoEstimado = $this->input->GET('tiempoEstimado');
		$fecha_inicio = date("Y/m/d");
		$estado = 2;
		//creamos un array con los datos recibidos

		$data = array('encargado' => $encargado, 'fecha_inicio' => $fecha_inicio, 'estado' => $estado, 'tiempo_estimado' => $tiempoEstimado);
		//print_r($data);die;
		if ($this->Mantenimiento_uno->iniciar_solicitud_mto($data, $id)) {
			$estado = "Reparacion";
			$id_equipo = $this->Mantenimiento_uno->getIdEquipoIdSolicitudMttoCorrectivo($id);
			if ($id_equipo > 0) {
				$this->Mantenimiento_uno->estadoreparacion($id_equipo, $estado);
			}
			echo "ok";
		} else {
			echo "err";
		}
	}
	public function finalizar_solicitud()
	{

		//cargamos los modelos
		$this->load->model('Mantenimiento_uno');
		$this->load->model('usuarios');
		//TRAEMOS LAS VARIABLES
		$user = $this->session->userdata('user');
		$encargado_solicitud = $this->input->POST('nit');
		$id = $this->input->POST('id_soli_f');
		$respuesta = $this->input->POST('respuesta_solicitud');
		/*CARGAR ARCHIVO*/
		$config['upload_path'] = './public/mantenimiento/solicitudes';
		$config['allowed_types'] = '*';
		$config['max_size'] = '10240000000';
		$this->load->library('upload', $config);

		$this->upload->do_upload('archivo-resp');
		/* if (!$this->upload->do_upload('archivo')) {
			header("Location: " . base_url() . "mantenimiento/index?log=img");
		} */
		$datas = $this->upload->data();
		$arch_respuesta = $datas['file_name'];
		$fecha_final = date("Y/m/d");
		$estado = 3;
		//creamos un array con los datos recibidos	
		$data = array('respuesta' => $respuesta, 'fecha_finalizacion' => $fecha_final, 'estado' => $estado, "imagen_respuesta" => $arch_respuesta);

		if ($user == $encargado_solicitud) {
			if ($this->Mantenimiento_uno->finalizar_solicitud_mto($data, $id)) {
				$estado = "Activo";
				$id_equipo = $this->Mantenimiento_uno->getIdEquipoIdSolicitudMttoCorrectivo($id);
				if ($id_equipo > 0) {
					$this->Mantenimiento_uno->estadoreparacion($id_equipo, $estado);
				}
				echo "ok";
				header("Location: " . base_url() . "mantenimiento/solicitud_mantenimieto?log=ok");
			} else {
				echo "err";
				header("Location: " . base_url() . "mantenimiento/solicitud_mantenimieto?log=err");
			}
		} else {
			echo "use";
			header("Location: " . base_url() . "mantenimiento/solicitud_mantenimieto?log=use");
		}
	}
	/*************************************CAMBIOS SOLICITUD DE MANTENIMIENTO ************************************************/
	/**Autor: Sergio Galvis 
	 * Fecha: 25 de Abril del 2022
	 */
	/* Tabla para jefes  */
	public function getSolicitudes()
	{
		/* Traemos los modelos a usar */
		$this->load->model('usuarios');
		$this->load->model('menus');
		$this->load->model('perfiles');
		$this->load->model('Mantenimiento_uno');
		$this->load->model('sedes');

		$jefe = $this->session->userdata('user');
		$data_solicitud_mtto = $this->Mantenimiento_uno->ver_solicitud_mantenimiento($jefe);
		/* Recorremos la información obtenida de la consulta colores*/
		foreach ($data_solicitud_mtto->result() as $key) {
			if ($key->estado === 1) {
				$estado = "Pendiente";
				$fondo = "background-color:#3dc4f550;";
			} elseif ($key->estado === 2) {
				$estado = "En proceso";
				$fondo = "background-color:#f5c73d50";
			} else {
				$estado = "Finalizada";
				$fondo = "background-color: #3df55350";
			}
			$nombre_sede = $this->Mantenimiento_uno->traer_name_bodegas_mto($key->sede);
			foreach ($nombre_sede->result() as $valor) {
				$sede_name2 = $valor->descripcion;
			}
			if ($key->urgencia == 1) {
				$color_urgencia = "background-color:#28a745";
			} elseif ($key->urgencia == 2) {
				$color_urgencia = "background-color:#ffc107";
			} else {
				$color_urgencia = "background-color:#dc3545";
			}
			
			if ($key->codigo != "") {
				$codigo = $key->codigo;
			} else {
				$codigo = `N/A`;
			}
			echo
			'
					<tr style="' . $fondo . '">
						<td style="width:1% "><a href="#" class="btn btn-info btn-flat" onclick="ver_evento(' . $key->id_solicitud . ');">' . $key->id_solicitud . '</a></td>
						<td style="width:5%">' . $codigo . '</td>
						<td style="width:25% ">' . $key->solicitud . '</td>
						<td style="width:5% ">' . $estado . '</td>
						<td style="width:1% ;"><i id="circulo" style="' . $color_urgencia . '">' . $key->urgencia . '</i></td>						
						<td style="width:10% ">' . $sede_name2 . '</td>
						<td style="width:15% ">' . $key->nombreJ . '</td>
						<td style="width:15% ">' . $key->nombreE . '</td>					
						<td style="width:10% ">' . $key->fecha_inicio . '</td>
						<td style="width:10% ">' . $key->fecha_finalizacion . '</td>
						<td style="width:10% ">' . $key->dias_gest . '</td>
					</tr>
				';
		}
	}
	/* Tabla para mantenimiento encargado  */
	public function getSolicitudesMto()
	{
		/* Traemos los modelos a usar */
		$this->load->model('usuarios');
		$this->load->model('menus');
		$this->load->model('perfiles');
		$this->load->model('Mantenimiento_uno');
		$this->load->model('sedes');
		//si ya hay datos de session los carga de nuevo
		$usu = $this->session->userdata('user');
		/* Bodegas enlazadas con el encargado de mantenimiento */
		$data_sedes = $this->sedes->get_sedes_user($usu);
		$sedes_usu = "";
		foreach ($data_sedes->result() as $key) {
			$sedes_usu .= $key->idsede . ",";
		}
		$sedes_usu = trim($sedes_usu, ",");

		$perfil_user = $this->perfiles->getPerfilByUser($usu);

		if ($perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 20 || $perfil_user->id_perfil == 26) {
			$data_solicitud_mtto = $this->Mantenimiento_uno->ver_solicitud_mantenimiento_admins();
		} else {
			$data_solicitud_mtto = $this->Mantenimiento_uno->ver_solicitud_mantenimiento_td($sedes_usu);
		}

		/* Recorremos la información obtenida de la consulta */
		foreach ($data_solicitud_mtto->result() as $key) {
			if ($key->estado === 1) {
				$estado = "Pendiente";
				$fondo = "background-color:#3dc4f550;";
			} elseif ($key->estado === 2) {
				$estado = "En proceso";
				$fondo = "background-color:#f5c73d50";
			} else {
				$estado = "Finalizada";
				$fondo = "background-color: #3df55350;";
			}
			$nombre_sede = $this->Mantenimiento_uno->traer_name_bodegas_mto($key->sede);
			foreach ($nombre_sede->result() as $valor) {
				$sede_name2 = $valor->descripcion;
			}
			if ($key->urgencia == 1) {
				$color_urgencia = "background-color:#28a745; border-color:#28a745";
			} elseif ($key->urgencia == 2) {
				$color_urgencia = "background-color:#ffc107; border-color:#ffc107";
			} else {
				$color_urgencia = "background-color:#dc3545; border-color:#dc3545";
			}
			if ($key->codigo != "") {
				$codigoE = '<button class="btn bg-blue" onclick="updateCodigo('.$key->id_solicitud.','.$key->id_equipo.',\''.$key->codigo.'\')">'.$key->codigo.'</button>';
			} else {
				$codigoE = '<button class="btn bg-blue">LOCATIVO</button>';
			}
			echo
			'
					<tr style="' . $fondo . '">
						<td style="width:1% "><button class="btn bg-blue" onclick="ver_evento(' . $key->id_solicitud . ');">' . $key->id_solicitud . '</button></td>
						<td style="width:5% ">' . $codigoE . '</td>
						<td style="width:25% ">' . $key->solicitud . '</td>
						<td style="width:5% ">' . $estado . '</td>
						<td style="width:1% ;"><i id="circulo" style="' . $color_urgencia . '">' . $key->urgencia . '</i></td>						
						<td style="width:10% ">' . $sede_name2 . '</td>
						<td style="width:15% ">' . $key->jefeN . '</td>
						<td style="width:15% ">' . $key->encargadoN . '</td>					
						<td style="width:10% ">' . $key->fecha_inicio . '</td>
						<td style="width:10% ">' . $key->fecha_finalizacion . '</td>
						<td style="width:10% ">' . $key->dias_gest . '</td>
					</tr>
				';
		}
	}

	/*************************************SOLICITUD DE RETIRO DE EQUIPOS ************************************************/
	/**Autor: Sergio Galvis 
	 * Fecha: 11 de Abril del 2022
	 */

	public function retirar_equipo()
	{
		$this->load->model('Mantenimiento_uno');
		$this->load->model('uploadfile');
		$this->load->model('usuarios');
		$equipo_id = $this->input->POST('id_equipo');
		$nit_user_solicitud = $this->input->POST('nitUsiario');
		$motivo = $this->input->POST('motivo_solicitud');
		$fecha =  DATE('Y-m-d');
		$estado = 0; //pendiente, 1 rechazado y 2 es autorizado...
		$jefe = $this->input->POST('jefe');
		$correo_jefe = $this->Mantenimiento_uno->correo_jefe($jefe);
		foreach ($correo_jefe->result() as $key) {
			$correo_jefe_autorizado = $key->correo;
		}
		/*CARGAR ARCHIVO*/
		$config['upload_path'] = './public/mantenimiento/';
		$config['allowed_types'] = '*';
		$config['max_size'] = '10240000000';
		$this->load->library('upload', $config);


		if (!$this->upload->do_upload('imagen_solicitud')) {
			header("Location: " . base_url() . "mantenimiento/index");
		}
		$datas = $this->upload->data();
		$img = $datas['file_name'];
		$data = array('equipo_id' => $equipo_id, 'nit_usuario_solicita' => $nit_user_solicitud, 'motivo' => $motivo, 'imagen' => $img, 'estado' => $estado, 'fecha_solicitud' => $fecha);
		/* Nombre del usuario que realiza la solicitud */
		$usu = $this->session->userdata('user');
		$encargado = $this->usuarios->getUserByName($usu);
		foreach ($encargado->result() as $key) {
			$persona = $key->nombres;
		}
		/* Validar si el equipo ya tiene alguna solicitud creada */
		$validar = $this->Mantenimiento_uno->consultar_retirar_equipo($equipo_id);
		$bandera = 3;
		foreach ($validar->result() as $key) {
			$bandera = $key->estado;
		}
		/* 	print_r($bandera . " Estado de la solicitud");die; */

		if ($bandera == 3 || $bandera == 1) {
			$idSolicitud = $this->Mantenimiento_uno->solicitud_retirar_equipo($data);
			if ($idSolicitud > 0) {
				/* print_r($u_id);die; */
				//echo $mensaje;die;
				// Load PHPMailer library
				$this->load->library('phpmailer_lib');
				$equipoMto = $this->Mantenimiento_uno->traer_datos_equipos($idSolicitud);
				foreach ($equipoMto->result() as $key) {
					$msn = 'Buen día <br>
						La persona ' . $persona . ' ha solicitado realizar el retiro del inventario del activo fijo ' . $key->equipo_id . ' por motivo de:<strong> ' . $motivo . ' </strong><br>
						fecha de solicitud: ' . $key->fecha_solicitud . '<br>
						<a href="' . base_url() . '/public/mantenimiento/' . $key->imagen . '">Imagen del Activo fijo</a>		
						';

					/* echo $key->correo."<br>"; */
					// PHPMailer object
					$correo = $this->phpmailer_lib->load();

					// SMTP configuration
					$correo->IsSMTP();

					$correo->SMTPAuth = true;
					$correo->SMTPSecure = 'tls';
					$correo->Host = "smtp.gmail.com";
					$correo->Port = 587;
					$correo->SMTPOptions = array(
						'ssl' => array(
							'verify_peer' => false,
							'verify_peer_name' => false,
							'allow_self_signed' => true
						)
					);
					/* Email del jefe encargado */
					$correo->addAddress($correo_jefe_autorizado);
					$correo->addAddress("programador3@codiesel.com");
					$correo->Username = "no-reply@codiesel.co";
					$correo->Password = "wrtiuvlebqopeknz";
					$correo->SetFrom("no-reply@codiesel.co", "CODIESEL S.A");
					$correo->Subject = "Solicitud retiro de equipo: " . $key->nombre_equipo;

					$mensaje = '<!DOCTYPE html>
						<html>
						<head>
						<meta charset="utf-8">
						<title></title>
						<style type="text/css">
											#boton_aceptar{
						background-color: #199319;
						color: white;
						padding: 15px 25px;
						text-decoration: none;
						}
						#boton_rechasar{
						background-color: red;
						color: white;
						padding: 15px 25px;
						text-decoration: none;
						}
						</style>
						</head>
						<body>
						<div style="padding: 20px;">
						<strong>' . $msn . '</strong>
						</div>
						<div style="padding: 20px;display: inline-block;">
						<a href="' . base_url() . '/mantenimiento/autorizar_retiro?id=' . $idSolicitud . '&nit_user_resp=' . $jefe . '" id="boton_aceptar">Aceptar</a>
						<a href="' . base_url() . '/mantenimiento/rechazar_retiro?id=' . $idSolicitud . '&nit_user_resp=' . $jefe . '" id="boton_rechasar">Rechazar</a>
						</div>
						</body>
						</html>';
					$correo->MsgHTML($mensaje);
					if (!$correo->Send()) {
						echo "err";
					}
				}

				echo "ok";
				header("Location: " . base_url() . "mantenimiento/index?log=ok");
				//die;
			} else {
				echo "err";
				header("Location: " . base_url() . "mantenimiento/index?log=err");
			}
		} else if ($bandera == 0) {
			echo "Ya hay una solicitud en estado pendiente";
			header("Location: " . base_url() . "mantenimiento/index?log=0");
		} else if ($bandera == 2) {
			echo "Ya hay una solicitud en estado autorizado";
			header("Location: " . base_url() . "mantenimiento/index?log=2");
		}
	}

	public function autorizar_retiro()
	{
		//llamamos los modelos
		$this->load->model('Mantenimiento_uno');
		//llamamos las variables
		$id_equipo = $this->input->GET('id');
		$id_jefe = $this->input->GET('nit_user_resp');
		$bandera = $this->Mantenimiento_uno->consultar_estado_retiro($id_equipo);
		$estado = "";
		foreach ($bandera->result() as $key) {
			$estado = $key->estado;
		}
		if ($estado == 0) {
			$this->Mantenimiento_uno->autorizar_retiro($id_equipo, $id_jefe);
			$estadoR = "inactivo";
			$this->Mantenimiento_uno->estadoreparacion($id_equipo, $estadoR);
			echo "<h3>Solicitud autorizada correctamente</h3>
			<h4>Puedes cerrar la pestaña del navegador</h4>";
		} elseif ($estado == 1) {
			echo "<h3>Esta solicitud ya ha sido rechazada</h3>
		<h4>Puedes cerrar la pestaña del navegador</h4>";
		} elseif ($estado == 2) {
			echo "<h3>Esta solicitud ya fue autorizada.</h3>
			<h4>Puedes cerrar la pestaña del navegador</h4>";
		}
	}

	public function rechazar_retiro()
	{
		//llamamos los modelos
		$this->load->model('Mantenimiento_uno');
		//llamamos las variables
		$id_equipo = $this->input->GET('id');
		$id_jefe = $this->input->GET('nit_user_resp');
		$bandera = $this->Mantenimiento_uno->consultar_estado_retiro($id_equipo);
		$estado = "";
		foreach ($bandera->result() as $key) {
			$estado = $key->estado;
		}
		if ($estado === 0) {
			$this->Mantenimiento_uno->rechazar_retiro($id_equipo, $id_jefe);
			echo "<h3>Solicitud rechazada correctamente</h3>
			<h4>Puedes cerrar la pestaña del navegador</h4>";
		} elseif ($estado === 1) {
			echo "<h3>Esta solicitud ya ha sido rechazada</h3>
		<h4>Puedes cerrar la pestaña del navegador</h4>";
		} elseif ($estado === 2) {
			echo "<h3>Esta solicitud ya ha sido autorizada.</h3>
			<h4>Puedes cerrar la pestaña del navegador</h4>";
		}
	}
	/************************************* MANTENIMIENTO PREVENTIVO ************************************************/
	/* 
	 05 de Mayo del 2022
	autor: Sergio Galvis */
	public function getCronograma()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('sedes');
			$this->load->model('perfiles');
			$this->load->model('Mantenimiento_uno');
			$this->load->model('AdministracionCodiesel');


			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			$datos = $this->Mantenimiento_uno->ver_equipos();
			$dto = $this->Mantenimiento_uno->ver_id();
			$bodegas =  $this->Mantenimiento_uno->TarerBodegas();
			$jefes = $this->Mantenimiento_uno->traer_jefes();
			$idFake = "";
			$equipos_f = $this->Mantenimiento_uno->equipo_nombre_familia($idFake);
			/* Botones que solo se muestran al usuario de mantenimiento */
			$tablaMtoPre = "";
			$cargarArchivoPreMto = "";
			$descargar = "";

			if ($perfil_user->id_perfil == 46 || $perfil_user->id_perfil == 20) {
				$tablaMtoPre = '<a href="' . base_url() . 'mantenimiento/verListado" class="btn btn-info btn-lg"><i class="fas fa-eye"></i>  Ver listado Plantilla</a>';
				$cargarArchivoPreMto = '<a href="#" class="btn btn-success btn-lg" data-toggle="modal" data-target="#modal_archivo_mto"><i class="fas fa-file"></i> Subir cronograma</a>';/* Boton de agregar cronograma */
				$descargar = '<a href="' . base_url() . 'public/mantenimiento/PlantillaPlanDeMantenimientoPreventivo.xlsx" class="btn btn-warning btn-lg"><i class="fas fa-file-download"></i>  Descargar Plantilla</a>';
			}

			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$where = "";
			if ($perfil_user->id_perfil == 46) {
				if($id_usu == 752 || $id_usu == 741 ){
					$where = "WHERE equi.bodega in ('Giron','Barrancabermeja','Rosita')";
				}else if($id_usu == 566 || $id_usu == 548){
					$where = "WHERE equi.bodega in ('Cucuta')";
				}				
			}
			//741	SOTO RAMIREZ YEISON ESNEIDER	1005475148
			//566	PARODIS REALES FREDDY	1090445609
			//548	RAMIREZ AMAYA EDINSON FABIAN	1093800359
			//752	FERNANDEZ MOLINA JOHN ALEXANDER	1097302273	c

			$cronograma = $this->Mantenimiento_uno->ObtenerCronograma($where);
			$data_solicitudes[] = array();
			foreach ($cronograma->result() as $key) {
				$color = "";
				if ($key->estado == "1") {
					$color = "#0064FFDE";//Pendiente
				} elseif ($key->estado == "2") {
					$color = "#ffc107";//En proceso
				} else {
					$color = "#28a745";//terminada
				}
				$data_solicitudes[] = array('id' => $key->id_mantenimientos, 'codigo' => $key->codigo, 'title' => $key->nombre_equipo . '-' . $key->bodega, 'start' => $key->fecha_requerida, 'end' => $key->fecha_requerida, 'descripcion' => $key->descripcion, 'color' => $color);
			}
			$arr_user = array(
				'cargarArchivoPreMto' => $cargarArchivoPreMto, 'tablaMtoPrev' => $tablaMtoPre, 'descargar' => $descargar, 'equiposF' => $equipos_f, 'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu,
				'data_tabla' => $datos, 'dato' => $dto, 'bodegas' => $bodegas, 'nit' => $usu, 'jefes' => $jefes, 'mtoPreventivo' => json_encode($data_solicitudes)
			);
			$this->load->view("mantenimiento/cronograma", $arr_user);
		}
	}
	public function verListado()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Mantenimiento_uno');
			$this->load->model('AdministracionCodiesel');


			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();

			$idUsuario = $this->usuarios->getUserByNit($usu)->id_usuario;

			switch ($idUsuario) {
				case 741:
					$where = "and bodega IN ('Barrancabermeja','Giron','Rosita')";
					break;
				case 548:
					$where = "and bodega IN ('Cucuta')";
					break;

				default:
					$where = "";
					break;
			}

			$cronograma = $this->Mantenimiento_uno->ObtenerCronogramaLista($where);

			$arr_user = array(
				'cronograma' => $cronograma, 'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass,
				'nit' => $usu
			);
			$this->load->view("mantenimiento/tablaMantenimientoPre", $arr_user);
		}
	}
	/*metodo para pintar datos y crear formulario para datos de mantenimiento Preventivo en la vista de Index*/
	public function pintarDatosCorrectivo()
	{
		$this->load->model('Mantenimiento_uno');
		$codigo = $this->input->GET('codigo');
		$datos =  $this->Mantenimiento_uno->ver_datos($codigo);
		echo json_encode($datos->result());
	}
	/*metodo para pintar datos y crear formulario para datos de mantenimiento Preventivo Por ID*/
	public function pintarDatosCorrectivoById()
	{
		$this->load->model('Mantenimiento_uno');
		$this->load->model('perfiles');
		$usu = $this->session->userdata('user');
		//obtenemos el perfil del usuario
		$perfil_user = $this->perfiles->getPerfilByUser($usu);
		$id = $this->input->GET('id');
		$datos =  $this->Mantenimiento_uno->ObtenerCronogramaId($id);
		foreach ($datos->result() as $key) {
			$ocultar = "";
			if ($key->observaciones != "" && $key->detalle_piezas != "") {
				$ocultar = "disabled";
			}
			$readonlyFechaRequerida = 'readonly';
			$btnUpdateDate="";
			if ($key->estado == 1) {
				$n_estado = "Pendiente";
				$color_estado = "background-color: #0064FFDE;";
				$btnUpdateDate = '<button type="button" class="btn bg-red btn-xs" style="float:right;" onclick="updateDateMttoPreSave();"><i class="fas fa-sync-alt"></i></button>';
				$readonlyFechaRequerida="";
			} elseif ($key->estado == 2) {
				$n_estado = "En proceso";
				$color_estado = "background-color: #ffc107;";
			} elseif ($key->estado == 3) {
				$n_estado = "Realizado";
				$color_estado = "background-color: #009C13C7 ;";
			}


			echo '
			<form>
				<div class="modal-body">
			
					<div class="col-lg-6 col-sm-6 col-xs-6 d-none">
						<input type="hidden" name="idEquipo" id="idEquipo" value="' . $key->id_equipo . '">
						<input id="id_equipoMp" value="' . $key->id_mantenimientos . '" name="id_equipoMp" type="text" class="form-control" readonly>
					</div>
					<div class="form-row">

						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="">Codigo de Equipo</label>
							<input value="' . $key->codigo . '" id="codigoEquipoMp" name="codigoEquipoMp" type="text" class="form-control" readonly>
						</div>
						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="">Nombre del Equipo</label>
							<input value="' . $key->nombre_equipo . '" id="nombreMp" name="nombreMp" type="text" class="form-control" readonly>
						</div>
						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="">Mantenimiento</label>
							<input value="' . $key->tipo_mantenimiento . '" id="tipoMantenimientoMp" name="tipoMantenimientoMp"; type="text" class="form-control" readonly>
						</div>
						
					</div>
					<div class="form-row">
						<div class="col-lg-4 col-sm-6 col-xs-6 ">
							<label for="">Sede de Equipo</label>
							<input value="' . $key->bodega . '" id="sedeMp" name="sedeMp" type="text" class="form-control" readonly>
						</div>

						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="Select2">Área:</label>
							<input value="' . $key->area . '" id="solicitanteMp" class="form-control" name="solicitanteMp" readonly>
							
						</div>
						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="responsable">Creado por:</label>
							<input value="' . $key->nombre_responsable . '" type="text" name="responsable" id="responsable" class="form-control" value="" readonly>
						</div>
					</div>
					<div class="form-row">

						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="fecha_solicitud">Fecha Solicitud</label>
							<input value="' . $key->fecha_solicitud . '" type="date" name="fecha_solicitud" id="fecha_solicitud" class="form-control" readonly>
						</div>
						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="fecha_requerida">Fecha Requerida</label>
							'.$btnUpdateDate.'
							<input value="' . $key->fecha_requerida . '" type="date" name="fecha_requerida" id="fecha_requerida" class="form-control" '.$readonlyFechaRequerida.' >
							<input value="' . $key->fecha_requerida . '" type="hidden" name="fecha_requerida_old" id="fecha_requerida_old" class="form-control" '.$readonlyFechaRequerida.' >
						</div>
						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="estado">Estado:</label>
							<input value="' . $n_estado . '" type="text" name="estado" id="estado" class="form-control" readonly style="' . $color_estado . ' color:white;">
						</div>
					</div>
					<div class="form-row">';

			if ($key->nombre_asignado == "") {
				echo '
								<div class="col-lg-4 col-sm-6 col-xs-6">
										<label for="asignado">Asignado</label>
										
										<select id="asignadoMp" class="form-control " name="asignadoMp" placeholder="Aun no tiene asignado">
										<option value="*">Seleccione un asignado</option>
										<option value="1005475148">SOTO RAMIREZ YEISON ESNEIDER</option>
										<option value="1102774996">RICO BAUTISTA FRANKIN ALEXIS</option>
										<option value="1097302273">FERNANDEZ MOLINA JOHN ALEXANDER</option>
										<option value="1093800359">RAMIREZ AMAYA EDINSON FABIAN</option>
										</select>
								</div>';
			} else {
				echo '
								<div class="col-lg-4 col-sm-6 col-xs-6">
										<label for="asignado">Asignado</label>
										<input value="' . $key->nombre_asignado . '" id="asignadoMp" class="form-control " name="asignadoMp" readonly>
								</div>';
			}

			echo '<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="fecha_inicio">Fecha Inicio</label>
							<input value="' . $key->fecha_inicio . '" type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" readonly>
						</div>
						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="fecha_final">Fecha Finalizacion</label>
							<input value="' . $key->fecha_final . '" type="date" name="fecha_final" id="fecha_final" class="form-control" readonly>
						</div>
					</div>
					<div class="form-row">
						<div class="col-lg-12 col-sm-12" id="campo1">
							<label>Descripcion</label>
							<textarea name="descripcionMp" id="descripcionMp" rows="4" class="form-control" disabled>' . $key->descripcion . '</textarea>
						</div>';

			if ($key->fecha_inicio != "") {
				echo
				'<div class="col-lg-12 col-sm-12" id="campo3">
								<label>observacion</label>
								<textarea ' . $ocultar . ' name="observarMp" id="observarMp" rows="4" class="form-control " minlength="15" placeholder="Escriba aqui las observaciones" spellcheck="true" required>' . $key->observaciones . '</textarea>
							</div>

							<div class="col-lg-12 col-sm-12" id="campo4">
								<label>Detalle de Piezas</label>
								<textarea ' . $ocultar . ' name="piezasMp" id="piezasMp" rows="4" class="form-control " minlength="15" placeholder="Escriba aqui el detalle de piezas" spellcheck="true" required>' . $key->detalle_piezas . '</textarea>
							</div>
						</div>
						<div id="OrdenMantenimientoId"></div>';
			}
			'</div>
					
				</div>';

			if ($perfil_user->id_perfil != 46) {
				echo '
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cerrar</button>
					</div>
					';
			} else {
				if ($key->estado == 1 && $key->fecha_requerida <=  date('Y-m-d')) {
					echo '
					<div class="modal-footer">
						<button  type="button" id="btn_iniciar" class="btn btn-warning btn-flat" onclick="iniciar_orden(' . $key->id_mantenimientos . ');">Iniciar</button>
						<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cerrar</button>
					</div>
					';
				} elseif ($key->estado == 2) {
					echo '
					<div class="modal-footer">
						<button  type="button" id="btn_finalizar" class="btn btn-success btn-flat" onclick="finalizar_orden(' . $key->id_mantenimientos . ');">Finalizar</button>
						<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cerrar</button>
					</div>
					';
				} elseif ($key->estado == 3) {
					echo '
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cerrar</button>
					</div>
					';
				} else {
					echo '
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cerrar</button>
					</div>
					';
				}
			}


			echo '
			</form>		
			';
		}
	}
	/* metodo para pintar datos en la tablaMantenimiento Por ID */
	public function pintarDatosCorrectivoTableById()
	{
		$this->load->model('Mantenimiento_uno');
		$this->load->model('perfiles');
		$usu = $this->session->userdata('user');
		//obtenemos el perfil del usuario
		$perfil_user = $this->perfiles->getPerfilByUser($usu);
		$id = $this->input->GET('id');
		$datos =  $this->Mantenimiento_uno->ObtenerCronogramaId($id);
		foreach ($datos->result() as $key) {
			$ocultar = "";
			if ($key->observaciones != "" && $key->detalle_piezas != "") {
				$ocultar = "disabled";
			}
			if ($key->estado == 1) {
				$n_estado = "Pendiente";
				$color_estado = "background-color: #0064FFDE;";
			} elseif ($key->estado == 2) {
				$n_estado = "En proceso";
				$color_estado = "background-color: #ffc107;";
			} elseif ($key->estado == 3) {
				$n_estado = "Realizado";
				$color_estado = "background-color: #009C13C7 ;";
			}


			echo '
			<form>
				<div class="modal-body">
			
					<div class="col-lg-6 col-sm-6 col-xs-6 d-none">
						<input id="id_equipoMp" value="' . $key->id_mantenimientos . '" name="id_equipoMp" type="text" class="form-control" readonly>
					</div>
					<div class="form-row">

						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="">Codigo de Equipo</label>
							<input value="' . $key->codigo . '" id="codigoEquipoMp" name="codigoEquipoMp" type="text" class="form-control" readonly>
						</div>
						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="">Nombre del Equipo</label>
							<input value="' . $key->nombre_equipo . '" id="nombreMp" name="nombreMp" type="text" class="form-control" readonly>
						</div>
						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="">Mantenimiento</label>
							<input value="' . $key->tipo_mantenimiento . '" id="tipoMantenimientoMp" name="tipoMantenimientoMp"; type="text" class="form-control" readonly>
						</div>
						
					</div>
					<div class="form-row">
						<div class="col-lg-4 col-sm-6 col-xs-6 ">
							<label for="">Sede de Equipo</label>
							<input value="' . $key->bodega . '" id="sedeMp" name="sedeMp" type="text" class="form-control" readonly>
						</div>

						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="Select2">Área:</label>
							<input value="' . $key->area . '" id="solicitanteMp" class="form-control" name="solicitanteMp" readonly>
							
						</div>
						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="responsable">Creado por:</label>
							<input value="' . $key->nombre_responsable . '" type="text" name="responsable" id="responsable" class="form-control" value="" readonly>
						</div>
					</div>
					<div class="form-row">

						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="fecha_solicitud">Fecha Solicitud</label>
							<input value="' . $key->fecha_solicitud . '" type="date" name="fecha_solicitud" id="fecha_solicitud" class="form-control" readonly>
						</div>
						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="fecha_requerida">Fecha Requerida</label>
							<input value="' . $key->fecha_requerida . '" type="date" name="fecha_requerida" id="fecha_requerida" class="form-control" readonly>
						</div>
						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="estado">Estado:</label>
							<input value="' . $n_estado . '" type="text" name="estado" id="estado" class="form-control" readonly style="' . $color_estado . ' color:white;">
						</div>
					</div>
					<div class="form-row">';

			if ($key->nombre_asignado == "") {
				echo '
								<div class="col-lg-4 col-sm-6 col-xs-6">
										<label for="asignado">Asignado</label>
										
										<select id="asignadoMp" class="form-control " name="asignadoMp" placeholder="Aun no tiene asignado">
										<option value="*">Seleccione un asignado</option>
										<option value="1005475148">SOTO RAMIREZ YEISON ESNEIDER</option>
										<option value="1102774996">RICO BAUTISTA FRANKIN ALEXIS</option>
										<option value="1097302273">FERNANDEZ MOLINA JOHN ALEXANDER</option>
										<option value="1093800359">RAMIREZ AMAYA EDINSON FABIAN</option>
										</select>
								</div>';
			} else {
				echo '
								<div class="col-lg-4 col-sm-6 col-xs-6">
										<label for="asignado">Asignado</label>
										<input value="' . $key->nombre_asignado . '" id="asignadoMp" class="form-control " name="asignadoMp" readonly>
								</div>';
			}

			echo '<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="fecha_inicio">Fecha Inicio</label>
							<input value="' . $key->fecha_inicio . '" type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" readonly>
						</div>
						<div class="col-lg-4 col-sm-6 col-xs-6">
							<label for="fecha_final">Fecha Finalizacion</label>
							<input value="' . $key->fecha_final . '" type="date" name="fecha_final" id="fecha_final" class="form-control" readonly>
						</div>
					</div>
					<div class="form-row">
						<div class="col-lg-12 col-sm-12" id="campo1">
							<label>Descripcion</label>
							<textarea name="descripcionMp" id="descripcionMp" rows="4" class="form-control" disabled>' . $key->descripcion . '</textarea>
						</div>';

			if ($key->fecha_inicio != "") {
				echo
				'<div class="col-lg-12 col-sm-12" id="campo3">
								<label>observacion</label>
								<textarea ' . $ocultar . ' name="observarMp" id="observarMp" rows="4" class="form-control " minlength="15" placeholder="Escriba aqui las observaciones" spellcheck="true" required>' . $key->observaciones . '</textarea>
							</div>

							<div class="col-lg-12 col-sm-12" id="campo4">
								<label>Detalle de Piezas</label>
								<textarea ' . $ocultar . ' name="piezasMp" id="piezasMp" rows="4" class="form-control " minlength="15" placeholder="Escriba aqui el detalle de piezas" spellcheck="true" required>' . $key->detalle_piezas . '</textarea>
							</div>
						</div>
						<div id="OrdenMantenimientoId"></div>';
			}
			'</div>
					
				</div>';

			echo '
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cerrar</button>
					</div>
					';
		}


		echo '
			</form>		
			';
	}

	/* Agregar orden de mantenimiento Preventivo */
	public function orden_mto_preventivo()
	{
		/* Carmaos los modelos */
		$this->load->model('Mantenimiento_uno');
		/* Obtenos los datos con el metodo POST */
		$id_equipo = $this->input->POST('id_equipoMp');
		$codigo = $this->input->POST('codigoEquipoMp');
		$mantenimiento = $this->input->POST('tipoMantenimientoMp');
		$responsable = $this->session->userdata('user');
		$f_solicitud = $this->input->POST('f_solicitud');
		$f_requerida = $this->input->POST('f_requerida');
		$descripcion = $this->input->POST('descripcionMp');
		$tiempo_estimado = $this->input->POST('tiempo_estimado');
		$estado = 1;
		$data = array(
			'codigo_equipo' => $codigo,
			'id_tipo_mantenimiento' => $mantenimiento,
			'responsable' => $responsable,
			'fecha_solicitud' => $f_solicitud,
			'fecha_requerida' => $f_requerida,
			'descripcion' => $descripcion,
			'estado' => $estado,
			'tiempo_estimado' => $tiempo_estimado
		);
		$result = $this->Mantenimiento_uno->Correctivo($data);
		if ($result) {
			/* $estado = "Reparacion";
			$this->Mantenimiento_uno->estadoreparacion($id_equipo, $estado); */
			echo "Mantenimiento Agregado de manera correcta";
			header("Location: " . base_url() . "mantenimiento/index");
		} else {
			echo "Erro al cargar el mantenimiento preventivo";
			header("Location: " . base_url() . "mantenimiento/index");
		}
	}
	/* Metodo para iniciar la orden de mantenimiento preventivo */
	public function iniciar_orden()
	{
		//cargamos los modelos
		$this->load->model('Mantenimiento_uno');
		$this->load->model('usuarios');
		//TRAEMOS LAS VARIABLES
		$encargado = $this->input->GET('asignado');

		$id_equipo = $this->input->GET('id_equipo');
		$estado2 = "Reparacion";

		$id = $this->input->GET('id');
		$fecha_inicio = date("Y/m/d");
		$estado = 2;
		//creamos un array con los datos recibidos

		$data = array('asignado' => $encargado, 'fecha_inicio' => $fecha_inicio, 'estado' => $estado);
		//print_r($data);die;
		if ($this->Mantenimiento_uno->iniciar_orden_mtoP($data, $id)) {
			if ($this->Mantenimiento_uno->estadoreparacion($id_equipo, $estado2)) {
				echo "ok";
			}
		} else {
			echo "err";
		}
	}
	/* Metodo para finalizar la orden de mantenimiento preventivo */
	public function finalizar_orden()
	{
		//cargamos los modelos
		$this->load->model('Mantenimiento_uno');
		$this->load->model('usuarios');
		//TRAEMOS LAS VARIABLES
		$encargado = $this->session->userdata('user');
		$id = $this->input->GET('id');
		$observarMp = $this->input->GET('observarMp');
		$piezasMp = $this->input->GET('piezasMp');
		$id_equipo = $this->input->GET('id_equipo');
		$fecha_final = date("Y/m/d");
		$estado = 3;
		if ($observarMp != "" && $piezasMp != "") {
			//creamos un array con los datos recibidos
			$data = array('observaciones' => $observarMp, 'detalle_piezas' => $piezasMp, 'asignado' => $encargado, 'fecha_final' => $fecha_final, 'estado' => $estado);
			//print_r($data);die;
			if ($this->Mantenimiento_uno->finalizar_orden_mtoP($data, $id)) {
				$estado = "Activo";
				$this->Mantenimiento_uno->estadoreparacion($id_equipo, $estado);
				echo "ok";
			} else {
				echo "err";
			}
		} else {
			echo "Datos de observación y detalle de piezas vacio...";
		}
	}
	/* Eliminar orden Mantenimiento como responsable encargado de mantenimiento... */
	public function eliminarOrdenMto()
	{
		$this->load->model('Mantenimiento_uno');
		$id = $this->input->GET('id');
		$result = $this->Mantenimiento_uno->eliminarOrdenMto($id);
		if ($result) {
			echo 'Exito';
		} else {
			echo 'Error';
		}
	}
	/*metod para traer mantenimiento por equipo */
	public function dtoManteid()
	{
		$this->load->model('Mantenimiento_uno');
		$codigo = $this->input->GET('codigo');
		$infoEquipos = $this->Mantenimiento_uno->ver_datos($codigo);
		$DatosMttoCorrectivo = $this->Mantenimiento_uno->get_solicitud_mantenimientoXid($codigo);
		foreach ($infoEquipos->result() as $key) {
			$codigo = $key->codigo;
		}
		$datos = $this->Mantenimiento_uno->DatosMantenimientoid($codigo);
		echo '
				<table class="table" id="tabla_historial">
					<thead>
					<tr>
						<th>Codigo</th>
						<th>Equipo</th>
						<th>Tipo de Mantenimiento</th>
						<th>Estado</th>
						<th>Fecha solicitud</th>
						<th>fecha requerida</th>
						<th>fecha inicio</th>
						<th>fecha finalizacion</th>
						<th>Asignado</th>
						<th>Detalles</th>
					</tr>
					</thead>
					<tbody>
				
				';

		if (!empty($datos->result())) {

			foreach ($datos->result() as $key) {
				if ($key->estadoMto == 1) {
					$estado_mto = "Pendiente";
				} elseif ($key->estadoMto == 2) {
					$estado_mto = "En proceso";
				} else {
					$estado_mto = "Realizado";
				}



				echo '
					
					<tr style="background-color: #ffc10778" >
						<td>' . $key->codigo . '</td>
						<td>' . $key->nombre_equipo . '</td>
						<td>' . $key->tipo_mantenimiento . '</td>
						<td>' . $estado_mto . '</td>
						<td>' . $key->fecha_solicitud . '</td>
						<td>' . $key->fecha_requerida . '</td>
						<td>' . $key->fecha_inicio . '</td>
						<td>' . $key->fecha_final . '</td>
						<td>' . $key->NameAsignado . '</td>
						<td class="text-center"><button class="tx btn btn-info shadow"  id="' . $key->id_mantenimientos . '" onclick="ver_historial(this.id)"><i class="far fa-eye"></i></button></td>
					</tr>
					
					';
			}
			
		} else {
			echo '<tr style="background-color: #ffc10778" ><td class="text-center" colspan="10">Este equipo no cuenta con historial de Mantenimiento Preventivo</td></tr>';
		}
		if (COUNT($DatosMttoCorrectivo->result()) > 0) {
			foreach ($DatosMttoCorrectivo->result() as $correctivo) {
				if ($correctivo->estado == 1) {
					$estado_mtoC = "Pendiente";
				} elseif ($correctivo->estado == 2) {
					$estado_mtoC = "En proceso";
				} else {
					$estado_mtoC = "Realizado";
				}
				echo '<tr style="background-color: #0064006b">
						<td>' . $correctivo->codigo . '</td>
						<td>' . $correctivo->nombre_equipo . '</td>
						<td>CORRECTIVO</td>
						<td>' . $estado_mtoC . '</td>
						<td>' . $correctivo->fecha_solicitud . '</td>
						<td>N/A</td>
						<td>' . $correctivo->fecha_inicio . '</td>
						<td>' . $correctivo->fecha_finalizacion . '</td>
						<td>' . $correctivo->nombreE . '</td>
						<td class="text-center"><button class="tx btn btn-info shadow"  id="' . $correctivo->id_solicitud . '" onclick="ver_historialCorrectivo(this.id)"><i class="far fa-eye"></i></button></td>
					</tr>';
			}
		}
		else {
			echo '<tr style="background-color: #0064006b"><td class="text-center" colspan="10">Este equipo no cuenta con historial de Mantenimiento Correctivo</td></tr>';
		}
		echo '</tbody></table>';
	}
	/* Metodo para traer el historial de mantenimiento del equipo en detalle */
	public function detalle_historial_mantenimiento()
	{
		$this->load->model('Mantenimiento_uno');
		$codigo = $this->input->GET('codigo');
		$datos = $this->Mantenimiento_uno->historial_detallado($codigo);
		foreach ($datos->result() as $key) {

			if ($key->id_tipo_mantenimiento == 1) {
				echo '
					<div class="card">
						<div class="col-lg-12 col-sm-12" style="display: block;" id="campo1">
							<label>Descripcion</label>
							<textarea name="descripcionmc" id="descripcionmc" rows="4" class="form-control" disabled>' . $key->descripcion . '</textarea>
						</div>
						<div class="col-lg-12 col-sm-12" style="display: block;" id="campo1">
							<label>Detalle de piezas</label>
							<textarea name="descripcionmc" id="descripcionmc" rows="4" class="form-control" disabled>' . $key->detalle_piezas . '</textarea>
						</div>
						<div class="col-lg-12 col-sm-12" style="display: block;" id="campo1">
							<label>Observaciones</label>
							<textarea name="descripcionmc" id="descripcionmc" rows="4" class="form-control" disabled>' . $key->observaciones . '</textarea>
						</div>
					</div>
					';
			} else {
				echo '
					<div class="card">
						<div class="col-lg-12 col-sm-12" style="display: block;" id="campo1">
							<label>Descripcion</label>
							<textarea name="descripcionmc" id="descripcionmc" rows="4" class="form-control" disabled>' . $key->descripcion . '</textarea>
						</div>
						<div class="col-lg-12 col-sm-12" style="display: block;" id="campo1">
							<label>Diagnostico</label>
							<textarea name="descripcionmc" id="descripcionmc" rows="4" class="form-control" disabled>' . $key->diagnostico . '</textarea>
						</div>
						<div class="col-lg-12 col-sm-12" style="display: block;" id="campo1">
							<label>Detalle de piezas</label>
							<textarea name="descripcionmc" id="descripcionmc" rows="4" class="form-control" disabled>' . $key->detalle_piezas . '</textarea>
						</div>
						<div class="col-lg-12 col-sm-12" style="display: block;" id="campo1">
							<label>Trabajo realizado</label>
							<textarea name="descripcionmc" id="descripcionmc" rows="4" class="form-control" disabled>' . $key->trabajo_realizado . '</textarea>
						</div>
					</div>
					';
			}
		}
		echo '</tbody></table>';
	}
	/* Subir datos desde un excel a BD */
	public function upload_data()
	{

		$this->load->model('Mantenimiento_uno');
		$data_ok = array();
		$data_err_db = array();
		$data_err_empty = array();

		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
		$file_rtfte = $_FILES['excel']['tmp_name'];
		$tabla = $_POST['cert'];

		$inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_rtfte);
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
		$spreadsheet = $reader->load($file_rtfte);
		$cant = $spreadsheet->getActiveSheet()->toArray();

		unset($cant[0]);/*Eliminamos la primer fila que son los titulos...  */
		foreach ($cant as $key) {
			$codigo = $key[0];
			$fecha_requerida = $key[1];
			$descripcion = $key[2];
			$responsable = $this->session->userdata('user');
			$fecha_solicitud = date('Y-m-d');
			$estado = 1;
			$id_tipo_mantenimiento = 1;
			$tiempo_estimado = $key[3];
			$data = array('codigo_equipo' => $codigo, 'responsable' => $responsable, 'fecha_solicitud' => $fecha_solicitud, 'fecha_requerida' => $fecha_requerida, 'descripcion' => $descripcion, 'estado' => $estado, 'id_tipo_mantenimiento' => $id_tipo_mantenimiento, 'tiempo_estimado' => $tiempo_estimado);

			$bandera = $this->Mantenimiento_uno->verificarEquipo($codigo);
			if ($bandera == false) {
				$codigo = "";
			}
			if ($codigo == '' || $responsable == '' || $fecha_solicitud == '' || $fecha_requerida == '' || $descripcion == '' || $estado == '' || $id_tipo_mantenimiento == '' || $tiempo_estimado == "") {
			} else {
				$res = $this->Mantenimiento_uno->Correctivo($data);
				//echo $res;
				if ($res) {
					$data_ok[] = array(1);
				} else {
					$data_err_db[] = array(1);
				}
			}
		}
		$data_response = array('ok' => count($data_ok), 'err_db' => count($data_err_db));
		echo json_encode($data_response);
	}
	/* Informes realizado por Sergio Galvis el 02 de Septiembre del 2022 */
	//Cargar la vista de Informe preventivo
	public function Informe_preventivo()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Mantenimiento_uno');


			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array(
				'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu
			);
			//abrimos la vista
			$this->load->view("mantenimiento/Informe_preventivo", $arr_user);
		}
	}
	//Cargar la información del Informe preventivo...
	public function getInfPreventivo()
	{

		$this->load->model('Mantenimiento_uno');

		$estado = $this->input->POST('estado');
		$bodega = $this->input->POST('bodega');

		if ($estado != "" && $bodega != "") {
			$where = "WHERE e.bodega ='$bodega' AND m.estado=$estado";
		} elseif ($estado != "" && $bodega == "") {
			$where = "WHERE m.estado=$estado";
		} elseif ($estado == "" && $bodega != "") {
			$where = "WHERE e.bodega ='$bodega'";
		} else {
			$where = "";
		}

		$dataInforme = $this->Mantenimiento_uno->getInformeMttoPrev($where);

		if ($dataInforme != false) {
			foreach ($dataInforme->result() as $key) {

				if ($key->estado == 1) {
					$n_estado = "Pendiente";
					$color_estado = "btn-primary";
				} elseif ($key->estado == 2) {
					$n_estado = "En proceso";
					$color_estado = "btn-warning";
				} elseif ($key->estado == 3) {
					$n_estado = "Realizado";
					$color_estado = "btn-success";
				}

				echo '
					<tr>
						<td scope="col">' . $key->codigo_equipo . '</td>
						<td scope="col">' . $key->nombre_equipo . '</td>
						<td scope="col">' . $key->area . '</td>
						<td scope="col">' . $key->bodega . '</td>
						<td scope="col">' . $key->descripcion . '</td>
						<td scope="col">' . $key->observaciones . '</td>
						<td scope="col">' . $key->detalle_piezas . '</td>
						<td scope="col">' . $key->NameResponsable . '</td>
						<td scope="col">' . $key->NameAsignado . '</td>
						<td scope="col">
							<button class="btn ' . $color_estado . '">' . $n_estado . '</button>
						</td>
						<td scope="col">' . $key->tiempo_estimado . '</td>
						<td scope="col">' . $key->fecha_solicitud . '</td>
						<td scope="col">' . $key->fecha_requerida . '</td>
						<td scope="col">' . $key->fecha_inicio . '</td>
						<td scope="col">' . $key->fecha_final . '</td>
					</tr>
				';
			}
		}
	}
	public function Informe_correctivo()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('Mantenimiento_uno');


			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);

			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			$bodegas = $this->Mantenimiento_uno->traer_bodegas_mto();
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array(
				'bodegas' => $bodegas,
				'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu
			);
			//abrimos la vista
			$this->load->view("mantenimiento/Informe_correctivo", $arr_user);
		}
	}
	public function getInfcorrectivo()
	{

		$this->load->model('Mantenimiento_uno');

		$estado = $this->input->POST('estado');
		$bodega = $this->input->POST('bodega');

		if ($estado != "" && $bodega != "") {
			$where = "WHERE s.sede ='$bodega' AND s.estado=$estado";
		} elseif ($estado != "" && $bodega == "") {
			$where = "WHERE s.estado=$estado";
		} elseif ($estado == "" && $bodega != "") {
			$where = "WHERE s.sede ='$bodega'";
		} else {
			$where = "";
		}

		$dataInforme = $this->Mantenimiento_uno->getInformeMttoCorrectivo($where);


		if ($dataInforme != false) {
			foreach ($dataInforme->result() as $key) {

				if ($key->estado === 1) {
					$estado = "Pendiente";
					$fondo = "background-color:#3dc4f550;";
				} elseif ($key->estado === 2) {
					$estado = "En proceso";
					$fondo = "background-color:#f5c73d50";
				} else {
					$estado = "Finalizada";
					$fondo = "background-color: #3df55350";
				}


				if ($key->urgencia == 1) {
					$nivel = "Leve";
					$color = "background-color:green; color:white";
				} elseif ($key->urgencia == 2) {
					$nivel = "Moderada";
					$color = "background-color:#ffc107; color:white";
				} elseif ($key->urgencia == 3) {
					$nivel = "Urgente";
					$color = "background-color:red; color:white";
				}

				echo '
					<tr>
						<td scope="col">' . $key->codigo . '</td>
						<td scope="col">' . $key->nombre_equipo . '</td>
						<td scope="col">' . $key->descripcion . '</td>
						<td scope="col">' . $key->Njefe . '</td>
						<td scope="col">' . $key->solicitud . '</td>
						<!-- <td scope="col">
							<img style="display:block" width="80px" src="' . base_url() . 'public/mantenimiento/solicitudes/' . $key->imagen . '"> 
						</td>-->
						<td scope="col" style="' . $color . '">' . $nivel . '</td>
						<td scope="col">' . $key->Nencargado . '</td>
						<td scope="col">' . $key->respuesta . '</td>
						<!--<td scope="col">
							 <img style="display:block" width="80px" src="' . base_url() . 'public/mantenimiento/solicitudes/' . $key->imagen_respuesta . '"> 
						</td>-->
						<td scope="col" style="' . $fondo . '">' . $estado . '</td>
						<td scope="col">' . $key->fecha_solicitud . '</td>
						<td scope="col">' . $key->fecha_inicio . '</td>
						<td scope="col">' . $key->fecha_finalizacion . '</td>
					</tr>
				';
			}
		}
	}

	public function detalle_historial_mantenimientoCorrectivo()
	{
		$this->load->model('Mantenimiento_uno');
		$codigo = $this->input->GET('codigo');
		$datos = $this->Mantenimiento_uno->get_solicitud_mantenimiento_by_id($codigo);
		foreach ($datos->result() as $key) {

			echo '
					<div class="card">
						<div class="col-lg-12 col-sm-12 pb-2" style="display: block;" id="campo1">
							<label>Respuesta</label>
							<textarea name="descripcionmc" id="descripcionmc" rows="4" class="form-control" disabled pb-2>' . $key->respuesta . '</textarea>
						</div>
						
					</div>
					';
		}
		echo '</tbody></table>';
	}

	/* 
		*	Autor: Sergio Galvis
		*	Fecha: 17/02/2023
		*	Asunto: Update al codigo registrado en los mantenimientos preventivos
	 */
	public function UpdateCodigoMttoCorrectivo(){

		$this->load->model('Mantenimiento_uno');

		$id_mtto = $this->input->POST('id_mtto');
		$id_equipo = $this->input->POST('id_equipo');

		if($this->Mantenimiento_uno->UpdateCodigoMttoCorrectivo($id_mtto,$id_equipo)){
			echo 1;
		}else {
			echo 0;
		}

	}

	/**
	 * Actualizar la fecha requerida del mantenimiento preventiva
	 * Solo para mantenimientos en estado pendiente
	 */
	public function updateDateMttoPre(){
		$this->load->model('Mantenimiento_uno');

		$id_mtto = $this->input->POST('id_mtto');
		$date = $this->input->POST('date');
		$date_old = $this->input->POST('date_old');
		$date_solicitud = date('Y-m-d');

		if($this->Mantenimiento_uno->UpdateDateMttoPreventivo($id_mtto,$date)){
			$id_user = $this->session->userdata('user');
			echo $this->Mantenimiento_uno->InsertHistDateMtto($id_mtto,$id_user,$date_solicitud,$date_old,$date);
		}else{
			echo 0;
		}
	}
}
