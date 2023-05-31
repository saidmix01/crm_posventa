<?php 

/**
 * 
 */
class Admin_vehiculos extends CI_Controller
{
	
	public function registrar_vehiculo()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('permisos');
			$this->load->model('vehiculos');
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//obtener los vehiculos
			
			$bodegas = $this->vehiculos->listat_bodegas();
			$tipo = "vehiculo";
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//echo $id_usu;
			$all_vehiculos = $this->vehiculos->listar_vehiculo($id_usu);
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "vehiculos" => $all_vehiculos, "bodegas" => $bodegas,'tipo' => $tipo);
			//abrimos la vista
			$this->load->view('ingresar_vehiculo',$arr_user);
		}
	}

	public function registrar_tot()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('permisos');
			$this->load->model('vehiculos');
			$this->load->model('sedes');
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//obtener los vehiculos
			
			
			$bodegas = $this->vehiculos->listat_bodegas();
			$tipo = "tot";
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			/*SEDES*/
			$data_bod = $this->sedes->get_sedes_user($usu);
			$bods = "";
			foreach ($data_bod->result() as $key) {
				$bods .=$key->idsede.",";
			}
			$bods = trim($bods, ',');
			
			
			$all_vehiculos = $this->vehiculos->listar_tot($bods,1);

			//echo $id_usu;

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "vehiculos" => $all_vehiculos, "bodegas" => $bodegas,'tipo' => $tipo);
			//abrimos la vista
			$this->load->view('ingresar_vehiculo',$arr_user);
		}
	}

	public function load_tabla_reg_tot()
	{
		$this->load->model('vehiculos');
		$this->load->model('sedes');
		$val = $this->input->GET('var');
		$usu = $this->session->userdata('user');
		$pass = $this->session->userdata('pass');
		$data_bod = $this->sedes->get_sedes_user($usu);
		$bods = "";
			foreach ($data_bod->result() as $key) {
				$bods .=$key->idsede.",";
			}
		$bods = trim($bods, ',');
		$vehiculos_all = $this->vehiculos->listar_tot($bods,$val);
		$manage_btn = "";
		if ($val == 2) {
			$manage_btn = "disabled";
		}
		foreach ($vehiculos_all->result() as $key) {
			$color = '';
			if ($key->fecha_salida == NULL) {
				$color = "table-warning";
			}elseif ($key->fecha_reingreso == NULL) {
				$color = "table-danger";
			}elseif ($key->fecha_reingreso != NULL) {
				$color = "table-success";
			}
			echo '<tr align="" class="'.$color.'">
					<td align="center">'.$key->orden.'</td>
					<td align="center">'.$key->placa.'</td>
					<td>'.$key->descripcion.'</td>
					<td>'.$key->proveedor.'</td>
					<td>'.$key->contenido.'</td>
					<td>'.$key->fecha_salida.'</td>
					<td>'.$key->fecha_reingreso.'</td>
					<td>
					<a href="'.base_url().'taller/generar_recibo_tot?id_v='.$key->id_vehiculo.'" target="_blank" class="btn btn-success btn-sm '.$manage_btn.'" aria-disabled="true"><i class="fas fa-print"></i></a>
					<a href="#" onclick="marcar_reingreso('.$key->id_vehiculo.')" class="btn btn-info btn-sm '.$manage_btn.'" aria-disabled="true" data-toggle="tooltip" data-placement="top" title="Marcar Reingreso"><i class="far fa-arrow-alt-circle-up"></i></a>
					</td>
				</tr>';
		}
	}
	public function registrar_ord_gral()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('permisos');
			$this->load->model('vehiculos');
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//obtener los vehiculos
			
			
			$bodegas = $this->vehiculos->listat_bodegas();
			$tipo = "Orden General";
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$all_vehiculos = $this->vehiculos->listar_ord_gral($id_usu);
			//echo $id_usu;

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "vehiculos" => $all_vehiculos, "bodegas" => $bodegas,'tipo' => $tipo);
			//abrimos la vista
			$this->load->view('ingresar_vehiculo',$arr_user);
		}
	}

	public function registrar_repuestos()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('permisos');
			$this->load->model('vehiculos');
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//obtener los vehiculos
			$all_vehiculos = $this->vehiculos->listar_repuestos();
			$bodegas = $this->vehiculos->listat_bodegas();
			$tipo = "repuesto";
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//echo $id_usu;

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "vehiculos" => $all_vehiculos, "bodegas" => $bodegas,'tipo' => $tipo);
			//abrimos la vista
			$this->load->view('ingresar_vehiculo',$arr_user);
		}
	}

	public function buscar_vehiculo()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('permisos');
			$this->load->model('vehiculos');
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			$perfil = $this->session->userdata('perfil');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//obtener los vehiculos
			$all_vehiculos = $this->vehiculos->listar_vehiculo_all();
			$info_vehiculo = $this->vehiculos->info_vehiculo();
			$info_tot = $this->vehiculos->info_tot($perfil);
			$info_repuesto = $this->vehiculos->info_repuesto();
			$bodegas = $this->vehiculos->listat_bodegas();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//echo $id_usu;

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "vehiculos" => $all_vehiculos, "bodegas" => $bodegas, "info_vehiculo" => $info_vehiculo,'info_tot' => $info_tot,'info_repuesto' => $info_repuesto);
			//abrimos la vista
			$this->load->view('buscar_vehiculo',$arr_user);
		}
	}

	public function real_time_vehiculo()
	{
		$val = $this->input->GET('var');
		if ($val == 1) {
			$this->load->model('vehiculos');
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			$info_vehiculo = $this->vehiculos->info_vehiculo();
			foreach ($info_vehiculo->result() as $key) {
				echo '
				<div class="col-md-12">
				<div class="card card-warning">
				<div class="card-header" style="align-content: left;">
				<h2 class="card-title"><strong>PLACA:'. $key->placa.'</strong></h2>

				<!-- /.card-tools -->
				</div>
				<!-- /.card-header -->
				<div class="card-body" align="left">
				<h6>AUTORIZA: <strong>'.$key->nombres.'</strong></h6>
				<h6>ORDEN N°: <strong>'.$key->orden.'</strong></h6>
				<h6>FECHA: <strong>'.$key->fecha_ingreso.'</strong></h6>
				<hr>
				<a class="btn btn-warning mb-2" href="'.base_url().'admin_vehiculos/cambiar_estado_si?orden='.$key->id_vehiculo.'">Confirmar Salida</a>
				</div>
				<!-- /.card-body -->
				</div>
				<!-- /.card -->
				</div>
				';
			}
		}else{
			echo "ERROR";
		}
		
	}

	public function real_time_ord_gral()
	{
		$val = $this->input->GET('val');
		if ($val == 1) {
			$this->load->model('vehiculos');
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			$info_vehiculo = $this->vehiculos->info_ord_gral();
			foreach ($info_vehiculo->result() as $key) {
				echo '
				<div class="col-md-12">
				<div class="card card-primary">
				<div class="card-header" style="align-content: left;">
				<h2 class="card-title"><strong>SERIAL:'. $key->placa.'</strong></h2>

				<!-- /.card-tools -->
				</div>
				<!-- /.card-header -->
				<div class="card-body" align="left">
				<h6>AUTORIZA: <strong>'.$key->nombres.'</strong></h6>
				<h6>DESCRIPCION: <strong>'.$key->contenido.'</strong></h6>
				<h6>FECHA: <strong>'.$key->fecha_ingreso.'</strong></h6>
				<hr>
				<a class="btn btn-primary mb-2" href="'.base_url().'admin_vehiculos/cambiar_estado_si?orden='.$key->id_vehiculo.'">Confirmar Salida</a>
				</div>
				<!-- /.card-body -->
				</div>
				<!-- /.card -->
				</div>
				';
			}
		}else{
			echo "ERROR";
		}
		
	}

	public function real_time_tot()
	{
		$val = $this->input->GET('val');
		if ($val == 1) {
			$this->load->model('vehiculos');
			$this->load->model('sedes');
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			$perfil = $this->session->userdata('perfil');
			/*SEDES*/
			$data_bod = $this->sedes->get_sedes_user($usu);
			$bods = "";
			foreach ($data_bod->result() as $key) {
				$bods .=$key->idsede.",";
			}
			$bods = trim($bods, ',');
			$info_tot = $this->vehiculos->info_tot($bods );
			foreach ($info_tot->result() as $key) {
				if ($key->fecha_salida == "") {
					echo '
					<div class="col-md-12">
					<div class="card card-info">
					<div class="card-header" style="align-content: center;">
					<h2 class="card-title"><strong>PLACA: '.$key->placa.'</strong></h2>

					<!-- /.card-tools -->
					</div>
					<!-- /.card-header -->
					<div class="card-body">
					<h6>AUTORIZA: <strong>'.$key->nombres.'</strong></h6>
					<h6>PROVEEDOR: <strong>'.$key->proveedor.'</strong></h6>
					<h6>ORDEN N°: <strong>'.$key->orden.'</strong></h6>
					<h6>FECHA: <strong>'.$key->fecha_ingreso.'</strong></h6>
					<h6>CONTIENE: <strong>'.$key->contenido.'</strong></h6>
					<hr>
					<a class="btn btn-info mb-2" href="'.base_url().'admin_vehiculos/cambiar_estado_si?orden='.$key->id_vehiculo.'">Confirmar Salida</a>
					</div>
					<!-- /.card-body -->
					</div>
					<!-- /.card -->
					</div>
					';
				}else{
					echo '
					<div class="col-md-12">
					<div class="card card-info">
					<div class="card-header" style="align-content: center;">
					<h2 class="card-title"><strong>PLACA: '.$key->placa.'</strong></h2>

					<!-- /.card-tools -->
					</div>
					<!-- /.card-header -->
					<div class="card-body">
					<h6>AUTORIZA: <strong>'.$key->nombres.'</strong></h6>
					<h6>PROVEEDOR: <strong>'.$key->proveedor.'</strong></h6>
					<h6>ORDEN N°: <strong>'.$key->orden.'</strong></h6>
					<h6>FECHA: <strong>'.$key->fecha_ingreso.'</strong></h6>
					<h6>CONTIENE: <strong>'.$key->contenido.'</strong></h6>
					<hr>
					<a class="btn btn-info mb-2" href="'.base_url().'admin_vehiculos/cambiar_estado_reingreso?orden='.$key->id_vehiculo.'">Confirmar Reingreso</a>
					</div>
					<!-- /.card-body -->
					</div>
					<!-- /.card -->
					</div>
					';
				}
				
			}
		}else{
			echo "ERROR";
		}
		
	}

	public function cambiar_estado_si()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{

			$this->load->model('vehiculos');
			$this->load->model('Informes');

			$orden = $this->input->GET("orden");
			$fecha = $this->Informes->get_fecha_actual();

			if (!$this->vehiculos->marcar_salida($orden,$fecha->fecha)) {
				header("Location: " . base_url()."admin_vehiculos/buscar_vehiculo?log=ok");
			}else{
				header("Location: " . base_url()."admin_vehiculos/buscar_vehiculo?log=err");
			}

			
		}
	}

	public function cambiar_estado_reingreso()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{

			$this->load->model('vehiculos');
			$this->load->model('Informes');

			$id = $this->input->GET("id");
			

			if ($this->vehiculos->marcar_reingreso($id)) {
				echo "ok";
			}else{
				echo "err";
			}

			
		}
	}

	public function crear_vehiculo()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			$this->load->model('vehiculos');
			$this->load->model('usuarios');
			$this->load->model('Informes');
			$usu = $this->session->userdata('user');
			$placa = $this->input->POST('placa');
			$orden = $this->input->POST('orden');
			$tipo = "vehiculo";
			$usuario = $this->usuarios->validar_usu($usu);

			$id_usu = $usuario->id_usuario;



			if ($this->vehiculos->crear_vehiculo($placa,$id_usu,$orden,$tipo)) {
				header("Location: " . base_url()."admin_vehiculos/registrar_vehiculo?log=ok");
			}else{
				header("Location: " . base_url()."admin_vehiculos/registrar_vehiculo?log=err");
			}
		}
	}

	public function crear_ord_gral()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			$this->load->model('vehiculos');
			$this->load->model('usuarios');
			$this->load->model('Informes');
			$usu = $this->session->userdata('user');
			$serial = $this->input->POST('serial');
			$descripcion = $this->input->POST('descripcion');
			$tipo = "Orden General";
			$usuario = $this->usuarios->validar_usu($usu);

			$id_usu = $usuario->id_usuario;



			if ($this->vehiculos->crear_ord_gral($serial,$id_usu,$descripcion,$tipo)) {
				header("Location: " . base_url()."admin_vehiculos/registrar_ord_gral?log=ok");
			}else{
				header("Location: " . base_url()."admin_vehiculos/registrar_ord_gral?log=err");
			}
		}
	}

	public function crear_tot()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			$this->load->model('vehiculos');
			$this->load->model('usuarios');
			$this->load->model('Informes');
			$usu = $this->session->userdata('user');
			$placa = $this->input->POST('placa');
			$orden = $this->input->POST('orden');
			$proveedor = $this->input->POST('proveedor');
			$contenido = $this->input->POST('contenido');
			$tipo = "tot";
			$usuario = $this->usuarios->validar_usu($usu);

			$id_usu = $usuario->id_usuario;



			if ($this->vehiculos->crear_tot($placa,$id_usu,$orden,$tipo,$proveedor,$contenido)) {
				$id_vh = $this->vehiculos->get_ultimo_id_vh($orden)->id_vehiculo;
				if (isset($id_vh)) {
					$info_tot = $this->vehiculos->info_tot_recibo($id_vh);
					$data_pdf =array('info_tot' => $info_tot);
					$mpdf = new \Mpdf\Mpdf();
					$nom_pdf = $orden + ".pdf";
					$html = $this->load->view('pdfView',$data_pdf,true);
					$mpdf->WriteHTML($html);
					$mpdf->Output($nom_pdf, 'I');
				}
				
				//header("Location: " . base_url()."admin_vehiculos/registrar_tot?log=ok");
			}else{
				header("Location: " . base_url()."admin_vehiculos/registrar_tot?log=err");
			}
		}
	}

	public function crear_repuesto()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			$this->load->model('vehiculos');
			$this->load->model('usuarios');
			$this->load->model('Informes');
			$usu = $this->session->userdata('user');
			$placa = $this->input->POST('placa');
			$orden = $this->input->POST('orden');
			$tipo = "repuesto";
			$usuario = $this->usuarios->validar_usu($usu);

			$id_usu = $usuario->id_usuario;



			if ($this->vehiculos->crear_vehiculo($placa,$id_usu,$orden,$tipo)) {
				header("Location: " . base_url()."admin_vehiculos/registrar_repuestos?log=ok");
			}else{
				header("Location: " . base_url()."admin_vehiculos/registrar_repuestos?log=err");
			}
		}
	}

	public function buscar_por_bodega()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('permisos');
			$this->load->model('vehiculos');
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			$bod = $this->input->POST("combo_bodega");
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//obtener los vehiculos
			$all_vehiculos = $this->vehiculos->listar_vehiculo_bodega($bod);
			$bodegas = $this->vehiculos->listat_bodegas();

			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//echo $id_usu;
			

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "vehiculos" => $all_vehiculos, "bodegas" => $bodegas);
			//abrimos la vista
			$this->load->view('ingresar_vehiculo',$arr_user);
		}
	}

	public function cargar_info_vehiculo()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('permisos');
			$this->load->model('vehiculos');
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//obtener los vehiculos
			$all_vehiculos = $this->vehiculos->listar_vehiculo_all();
			$bodegas = $this->vehiculos->listat_bodegas();
			$orden = $this->input->POST('orden');
			$info_vehiculo = $this->vehiculos->ver_info_vehiculo($orden);
			$info_tot = $this->vehiculos->ver_info_tot($orden);
			$info_repuesto = $this->vehiculos->ver_info_repuesto($orden);
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//echo $id_usu;

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "vehiculos" => $all_vehiculos, "bodegas" => $bodegas, "info_vehiculo" => $info_vehiculo, 'info_tot' => $info_tot, 'info_repuesto' => $info_repuesto);
			//abrimos la vista
			$this->load->view('buscar_vehiculo',$arr_user);
		}
	}

	public function cargar_info_vehiculo_placa()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('permisos');
			$this->load->model('vehiculos');
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//obtener los vehiculos
			$all_vehiculos = $this->vehiculos->listar_vehiculo_all();
			$bodegas = $this->vehiculos->listat_bodegas();
			$placa = $this->input->POST('placa');
			$info_vehiculo = $this->vehiculos->ver_info_vehiculo_placa($placa);
			$info_tot = $this->vehiculos->ver_info_tot_placa($placa);
			$info_repuesto = $this->vehiculos->ver_info_repuesto($placa);
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//echo $id_usu;

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "vehiculos" => $all_vehiculos, "bodegas" => $bodegas, "info_vehiculo" => $info_vehiculo, 'info_tot' => $info_tot, 'info_repuesto' => $info_repuesto);
			//abrimos la vista
			$this->load->view('buscar_vehiculo',$arr_user);
		}
	}

}

?>
