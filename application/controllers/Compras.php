<?php 

/**
 * Cotrolador creado para el modulo de compras
 * hecho por said el 24/03/2022
 */
class Compras extends CI_Controller
{
	
	public function gestion_compras()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('compra');

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
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$all_empleados = $this->usuarios->getAllUsers();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array('perfil'=>$perfil_user->id_perfil,'userdata' => $userinfo, 'menus' => $allmenus, 'pass' => $pass, 'id_usu' => $id_usu, 'nomb' => $nomb,'all_emp'=>$all_empleados);
			//abrimos la vista
			$this->load->view("compras/index", $arr_user);
		}
	}


	/*
	* Estados de Autorizacion
	* 1 -> sin autorizacion
	* 2-> pendiente de autorizacion
	* 3-> autorizado
	* 4-> no autorizado
	*-------------------------------
	* Estados de la compra
	* 1-> sin revisar
	* 2-> en proceso
	* 5-> en transito 3
	* 3-> despachada 4
	* 4-> negada 5
	*/
	public function insert_solicitud()
	{
		//Traemos los modelos
		$this->load->model('compra');
		$this->load->model('AdministracionCodiesel');
		//Traemos los datos de la vista
		//print_r($this->input->POST());die;
		//$arr_data = $this->input->GET('data');
		//separamos los datos
		//$data = explode(".", $arr_data);
		//traemos la cedula del usuario
		$usu = $this->session->userdata('user');
		//traemos la fecha actual
		$dtz = new DateTimeZone("America/Bogota");
		$dt = new DateTime("now", $dtz);
		$fecha = date('Y-m-d') . 'T' . $dt->format('H:i:s');
		$data_insert = array('fecha_solicitud' => $fecha,
							 'area' => $this->input->POST('combo_area'),
							 'sede' => $this->input->POST('combo_sede'),
							 'usu_solicita' => $usu,
							 'cargo_usu_solicita' => $this->input->POST('cargo'),
							 'gerente_autoriza' => $this->input->POST('combo_gerente'),
							 'descri_prod' => $this->input->POST('descripcion_prod'),
							 'caracteristicas' => "",
							 'proveedor' => $this->input->POST('proveedor'),
							 'area_cargar' => $this->input->POST('area_cargar'),
							 'urgencia' => $this->input->POST('urgencia'),
							 'fecha_tentativa' => $this->input->POST('fecha_tentativa'),
							 'estado_autorizacion'=>1,
							 'estado'=>1,
							 'con_factura'=>"No");
		if ($this->compra->insert_solicitud($data_insert)) {
			//$mail_emp = $this->AdministracionCodiesel->get_mail_empleado($nit_emp)->mail;
			$msn = "Se ha generado una nueva Solicitud de Compra";
			$this->load->library('phpmailer_lib');
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
				/* $correo->addAddress("programador3@codiesel.co");   */
				$correo->addAddress("compras@codiesel.co");
				$correo->Username = "no-reply@codiesel.co";
				$correo->Password = "wrtiuvlebqopeknz";
				$correo->SetFrom("no-reply@codiesel.co", "CODIESEL S.A");
				$correo->Subject = "Nueva Solicitud de Compra ";
				$mensaje = '<!DOCTYPE html>
				<html lang="en">
				<head>
				<meta charset="UTF-8" />
				<meta http-equiv="X-UA-Compatible" content="IE=edge" />
				<meta name="viewport" content="width=device-width, initial-scale=1.0" />
				<title>Document</title>
				</head>
				<body>
				<div class="content" style="height: 100%; display: flex; align-items: center; justify-content: center;">
				<div id="tarjeta" class="card text-center w-75 mx-auto h-50" align="center" style="width: 75%;
				background-color: #f8f9fa;
				-webkit-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);
				-moz-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);
				box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);">
				<div id="cab-tarjeta" class="card-header">

				</div>
				<div class="card-body">
				<h2 class="card-title" style="font-weight: 700!important;
				font-size: 2rem;
				">Nueva Solicitud de Compra</h2>
				<p class="card-text" style="padding: 20px;

				font-size: 1rem;
				line-height: 1.5;
				word-wrap: break-word;
				margin-bottom: 35px;">
				' . $msn . '
				</p>
				</div>
				<div class="card-footer bg-dark text-white" style="padding: 10px;
				position: relative;
				top: 20px;
				background-color: #343a40;
				color: white;
				display: flex;
				flex-direction: row;
				justify-content: space-around;">
				<div class="">

				</div>
				<div class="contacto" style="display: flex;
				flex-direction: column;
				justify-content: space-evenly;">

				</div>
				</div>
				</div>
				</div>
				</body>
				</html>
				';
				$correo->MsgHTML($mensaje);
				if (!$correo->Send()) {
					echo "err";
				}
		}else{
			echo "err";
		}
	}

	public function get_solicitudes()
	{
		//Traemos los modelos
		$this->load->model('compra');
		$this->load->model('perfiles');
		//obtenemos el perfil del usuario
		$usu = $this->session->userdata('user');
		$perfil_user = $this->perfiles->getPerfilByUser($usu);
		$tipo_usu = "";
		$estado_inp = "0";
		if ($perfil_user->id_perfil == 20 || $perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 28) {
			$data_solicitudes = $this->compra->get_solicitudes();
		}else{
			$data_solicitudes = $this->compra->get_solicitudes($usu);
			$est = 'disabled="disabled"';
		}
		foreach ($data_solicitudes->result() as $key) {
			//echo $key->estado_autorizacion;
			$styleColor="";
			if ($key->estado_autorizacion == 1 && ($perfil_user->id_perfil == 20 || $perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 28)) {
				$style_btn_au = "btn btn-secondary btn-sm";
				$estado_au = "Enviar Autorizacion";
				$est_au = "";
			}else if ($key->estado_autorizacion == 1 && ($perfil_user->id_perfil != 20 || $perfil_user->id_perfil != 1 || $perfil_user->id_perfil != 28)) {
				$style_btn_au = "btn btn-secondary btn-sm";
				$estado_au = "Sin Autorizar";
				$est_au = 'disabled="disabled"';
			}else if($key->estado_autorizacion == 2){
				$style_btn_au = "btn btn-warning btn-sm";
				$estado_au = "Autorizacion En Proceso";
				$est_au = 'disabled="disabled"';
			}else if($key->estado_autorizacion == 3 && ($perfil_user->id_perfil == 20 || $perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 28)){
				$style_btn_au = "btn btn-success btn-sm";
				$estado_au = "Autorizacion Aprobada";
				$est_au = '';
			}else if($key->estado_autorizacion == 3 && ($perfil_user->id_perfil == 20 || $perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 28)){
				$style_btn_au = "btn btn-success btn-sm";
				$estado_au = "Autorizacion Aprobada";
				$est_au = 'disabled="disabled"';
			}else if($key->estado_autorizacion == 4 && ($perfil_user->id_perfil == 20 || $perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 28)){
				$style_btn_au = "btn btn-danger btn-sm";
				$estado_au = "Autorizacion Negada";
				$est_au = '';
			}else if($key->estado_autorizacion == 4 && ($perfil_user->id_perfil != 20 || $perfil_user->id_perfil != 1 || $perfil_user->id_perfil != 28)){
				$style_btn_au = "btn btn-danger btn-sm";
				$estado_au = "Autorizacion Negada";
				$est_au = 'disabled="disabled"';
			}
			/***********************************************/
			
			if ($key->estado == 1 && ($perfil_user->id_perfil == 20 || $perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 28)) {
				$style_btn_estado = "btn btn-secondary btn-sm";
				$estado = "Sin Revisar";
				$est = "";
			}else if ($key->estado == 1 && ($perfil_user->id_perfil != 20 || $perfil_user->id_perfil != 1 || $perfil_user->id_perfil != 28)) {
				$style_btn_estado = "btn btn-secondary btn-sm";
				$estado = "Sin Revisar";
				$est = 'disabled="disabled"';
				$chk = 'disabled';
			}else if($key->estado == 2 && ($perfil_user->id_perfil == 20 || $perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 28)){
				$style_btn_estado = "btn btn-warning btn-sm";
				$estado = "En Proceso";
				$est = '';
			}else if($key->estado == 2 && ($perfil_user->id_perfil != 20 || $perfil_user->id_perfil != 1 || $perfil_user->id_perfil != 28)){
				$style_btn_estado = "btn btn-warning btn-sm";
				$estado = "En Proceso";
				$est = 'disabled="disabled"';
			}else if($key->estado == 3 && ($perfil_user->id_perfil == 20 || $perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 28)){
				$style_btn_estado = "btn btn-sm bg-orange";
				$styleColor = 'style="color:white !important;"';
				$estado = "En transito";
				$est = '';
				}
			else if($key->estado == 3 && ($perfil_user->id_perfil != 20 || $perfil_user->id_perfil != 1 || $perfil_user->id_perfil != 28)){
				$style_btn_estado = "btn btn-sm bg-orange";
				$styleColor = 'style="color:white !important;"';
				$estado = "En transito";
				$est = 'disabled="disabled"';
			}
			else if($key->estado == 4){
				$style_btn_estado = "btn btn-success btn-sm";
				$estado = "Despachada";
				$est = 'disabled="disabled"';
				$estado_inp = "1";
			}
			else if($key->estado == 5){
				$style_btn_estado = "btn btn-danger btn-sm";
				$estado = "Negada";
				$est = 'disabled="disabled"';
				$estado_inp = "1";
			}
			/****************************************************/
			if ($key->urgencia == 1) {
				$color = "#D7FFCE";
			}else if($key->urgencia == 2){
				$color = "#FFFECE";
			}else if($key->urgencia == 3){
				$color = "#FFCECE";
			}
			/**************************************************/


			
			if ($perfil_user->id_perfil != 20 && $perfil_user->id_perfil != 1 && $perfil_user->id_perfil != 28 ) {
				$chk = 'disabled';
			}else{
				$chk = '';
			}
			if (($perfil_user->id_perfil == 20 || $perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 28) && $key->estado == 5) {
				$chk = 'disabled';
			}else if (($perfil_user->id_perfil == 20 || $perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 28) && $key->estado == 1) {
				$chk = 'disabled';
			}else if (($perfil_user->id_perfil == 20 || $perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 28) && $key->estado == 2) {
				$chk = 'disabled';
			}
			if ($key->con_factura == "Si") {
				$chk.='checked="" disabled';
				$color = "#DFDFDF";
			}
			if($key->estado == 5){
				$color = "#DFDFDF";
			}

			echo '<tr style="background-color: '.$color.'">
					<th scope="row" style="width: 5%"><button type="button" class="btn btn-info btn-sm"  onclick="cargar_detalles('.$key->id_solicitud.');">'.$key->id_solicitud.'</button></th>
					<td style="width: 30%">'.$key->descri_prod.'</td>
					<td style="width: 5%"><a href="#" class="btn btn-sm btn-primary" onclick="modal_msn('.$key->id_solicitud.','.$estado_inp.');"><i class="fab fa-facebook-messenger"></i></a></td>
					
					<td>
						<div class="icheck-success d-inline">
	                        <input type="checkbox" '.$chk.' id="chk_'.$key->id_solicitud.'" onchange="con_factura('.$key->id_solicitud.')">
	                        <label for="chk_'.$key->id_solicitud.'"></label>
                      	</div>
                    </td>
					<td style="width: 10%"><button type="button"  class="'.$style_btn_estado.'" '.$est.' onclick="modal_estado('.$key->id_solicitud.');" '.$styleColor.'>'.$estado.'</button></td>
					<td style="width: 10%"><button type="button" class="'.$style_btn_au.'" '.$est_au.' onclick="modal_solicitar_autorizacion('.$key->id_solicitud.');">'.$estado_au.'</button></td>
					<td style="width: 30%">'.$key->usuario_reg.'</td>
					<td style="width: 30%">'.$key->gerente.'</td>
					<td style="width: 10%">'.$key->fecha_solicitud.'</td>
				    <td style="width: 10%">'.$key->fecha_autorizacion.'</td>
				    <td style="width: 10%">'.$key->dias_gest.'</td>
				 </tr>';
		}
	}

	public function consultar_autorizacion()
	{
		//Traemos los modelos
		$this->load->model('compra');
		//Traemos los parametros por url
		$id_soli = $this->input->GET('id_soli');
		$data_soli = $this->compra->get_solicitudes_By_id($id_soli);
		$est = "";
		foreach ($data_soli->result() as $key) {
			$est = $key->estado_autorizacion;
		}
		if ($est==3) {
			echo "ok_aut";
		}elseif($est==4 || $est==2 || $est== 1){
			echo "no_aut";
		}
	}

	public function ver_autorizacion_aprobada()
	{
		//Traemos los modelos
		$this->load->model('compra');
		//Traemos los parametros por url
		$id_soli = $this->input->GET('id_soli');
		$data = $this->compra->get_solicitud_aprobada($id_soli);
		$url = "";
		foreach ($data->result() as $key) {
			$url = $key->url;
		}
		echo '<a href="'.base_url().$url.'" target="_blank"><h3>Ver Cotización</h3></a>';
	}

	public function detalle_solicitudes()
	{
		//Traemos los modelos
		$this->load->model('compra');
		//Traemos los parametros por url
		$id_soli = $this->input->GET('id_soli');
		//TRaemos los datos de la solicitud
		$data_solicitud = $this->compra->get_solicitudes_By_id($id_soli);
		foreach ($data_solicitud->result() as $key) {
			echo '
			<div class="form-row">
       			<div class="col">
       				<label>Area que solicita la compra</label>
       				<input type="text" name="nombre" id="nom" class="form-control" value="'.$key->area.'" disabled>
       			</div>
       			<div class="col">
       				<label>Seleccione la Sede</label>
       				<input type="text" name="nombre" id="nom" class="form-control" value="'.$key->sede.'" disabled>
       			</div>
       		</div>
       		<div class="form-row">
       			<div class="col">
       				<label>Nombre de la persona que realiza la solicitud</label>
       				<input type="text" name="nombre" id="nom" class="form-control" value="'.$key->usuario_reg.'" disabled>
       			</div>
       			<div class="col">
       				<label>Cargo de la persona que esta solicitando la compra</label>
       				<input type="text" name="cargo" id="cargo" class="form-control" value="'.$key->cargo_usu_solicita.'" disabled>
       			</div>
       		</div>
       		<div class="form-row">
       			<div class="col">
       				<label>Nombre del Gerente de Area que autoriza la compra</label>
       				<input type="text" name="nombre" id="nom" class="form-control" value="'.$key->gerente.'" disabled>
       			</div>
       			<div class="col">
       				<label>Proveedores o Contratistas sugeridos</label>
       				<input type="text" name="proveedor" id="proveedor" class="form-control" value="'.$key->proveedor.'" disabled>
       			</div>
       		</div>
       		<div class="form-row">
       			<div class="col">
       				<label>Nivel de urgencia de la compra</label>
       				<input type="text" name="nombre" id="nom" class="form-control" value="'.$key->urgencia.'" disabled>
       			</div>
       			<div class="col">
       				<label>Fecha Tentativa de la compra</label>
       				<input type="text" name="fecha_tentativa" id="fecha_tentativa" value="'.$key->fecha_tentativa.'" class="form-control" disabled>
       			</div>
       		</div>
       		<div class="form-row">
       			<div class="col">
       				<label>Area y % a la que se debe cargar la compra</label>
       				<textarea class="form-control" id="area_cargar" rows="3" disabled>'.$key->area_cargar.'</textarea>
       			</div>
       		</div>
       		<div class="form-row">
       			<div class="col">
       				<label>Descripción de producto o servicio</label>
       				<textarea class="form-control" id="descripcion_prod" rows="3" disabled>'.$key->descri_prod.'</textarea>
       			</div>
       		</div>
       		<div class="form-row" style="display: none;">
       			<div class="col">
       				<label>Especificaciones Técnicas de la compra</label>
       				<textarea class="form-control" id="espesificacion_prod" rows="3" disabled>'.$key->caracteristicas.'</textarea>
       			</div>
       		</div>
			';
		}
	}

	public function solicitar_autorizacion()
	{
		//TRaemos los modelos
		//Traemos los modelos
		$this->load->model('compra');
		$this->load->model('usuarios');
		$id_soli = $this->input->POST('id_soli1');
		$comentarios = $this->input->POST('comentarios');
		$usu = $this->session->userdata('user');
		$directorio = './public/compras';
		foreach ($_FILES['file1']['tmp_name'] as $key => $tmp_name) {
			if ($_FILES['file1']['name'][$key]) {
				$filename = $_FILES['file1']['name'][$key];
				$temporal = $_FILES['file1']['tmp_name'][$key];
				$dir = opendir($directorio);
				$url = $directorio."/".$filename;
				if (move_uploaded_file($temporal, $url)) {
					$data = array('id_compra' => $id_soli,'url'=>substr($url, 1),'estado'=>0);
					$this->compra->insert_solicitudes($data);
				}
				
			}
		}
		$data_solicitud = array('estado_autorizacion'=>2,'estado' => 2);
		$this->compra->update_solicitid($id_soli,$data_solicitud);
		$data_solicitudes = $this->compra->get_solicitudes_By_id($id_soli);
		$userinfo = $this->usuarios->getUserByName($usu);
		foreach ($userinfo->result() as $nombre) {
			$nomb = $nombre->nombres;
		}
		foreach ($data_solicitudes->result() as $key) {
			$msn = "Usted ha recibido una nueva Solicitud de compra por motivo de: ".$key->descri_prod.".<br>
			Solicita: ".$nomb."<br> Fecha de Solicitud: ".$key->fecha_solicitud;
			$url_img = $key->cotizacion_file;
		}

		/*Tabla solicitudes*/

		$data_soli = $this->compra->get_solicitudes_compra($id_soli);

		$tabla = '<table class="table" border="1" style="width: 100%">
				  <thead>
				    <tr>
				      <th scope="col">Id</th>
				      <th scope="col">Ver Cotización</th>
				      <th scope="col">Aprobar</th>
				      <th scope="col">Rechazar</th>
				    </tr>
				  </thead>
				  <tbody>';
		foreach ($data_soli->result() as $key) {
			$tabla .= '<tr>
				      <th scope="row">'.$key->id_coti.'</th>
				      <td><a href="'.base_url().$key->url.'">Ver Cotización</a></td>
				      <td><a href="'.base_url().'compras/autorizar_cotizacion?id_coti='.$key->id_coti.'&id_soli='.$key->id_compra.'">Aprobar</a></td>
				      <td><a href="'.base_url().'compras/rechazar_cotizacion?id_coti='.$key->id_coti.'&id_soli='.$key->id_compra.'">Rechazar</a></td>
				    </tr>';
		}
		$tabla .= '</tbody>
				</table>';
	 	
		$this->load->library('phpmailer_lib');
		$correo = $this->phpmailer_lib->load();
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
		//Correos a enviar el mensaje
		$correo->addAddress("personal@codiesel.co");
		$correo->addAddress("gerencia@codiesel.co");
		$correo->addAddress("ger.servicio@codiesel.co");
		/* $correo->addAddress("programador3@codiesel.co"); */

		$correo->Username = "no-reply@codiesel.co";
		$correo->Password = "wrtiuvlebqopeknz";
		$correo->SetFrom("no-reply@codiesel.co", "CODIESEL S.A");
		$correo->Subject = "Nueva Solicitud de Compra ";

		$mensaje = '<!DOCTYPE html>
		<html lang="en">
		<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Document</title>
		</head>
		<body>
		<div class="content" style="height: 100%; display: flex; align-items: center; justify-content: center;">
		<div id="tarjeta" class="card text-center w-75 mx-auto h-50" align="center" style="width: 75%;
		background-color: #f8f9fa;
		-webkit-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);
		-moz-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);
		box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);">
		<div id="cab-tarjeta" class="card-header">

		</div>
		<div class="card-body">
		<h2 class="card-title" style="font-weight: 700!important;
		font-size: 2rem;
		">Nueva Solicitud de Compra</h2>
		<p class="card-text" style="font-size: 1rem; line-height: 1.5; word-wrap: break-word;">
		' . $msn . '
		</p>
		<strong><h2>Notas:<h2></strong><p style="font-size: 1rem; line-height: 1.5; word-wrap: break-word;">'. $comentarios .'</p>
		<hr>
		'.$tabla.'
		<hr>
		</div>
		<div class="card-footer bg-dark text-white" style="padding: 10px;
		position: relative;
		top: 20px;
		background-color: #343a40;
		color: white;
		display: flex;
		flex-direction: row;
		justify-content: space-around;">
		<div class="">

		</div>
		<div class="contacto" style="display: flex;
		flex-direction: column;
		justify-content: space-evenly;">

		</div>
		</div>
		</div>
		</div>
		</body>
		</html>
		';
		$archivo = base_url().$url_img;
		$correo->AddAttachment($archivo,$archivo);
		$correo->MsgHTML($mensaje);
		
		
		if (!$correo->Send() ) {
			echo "err";
		} else {
			echo "ok";
		}
		

	}

	public function autorizar_cotizacion()
	{
		//Traemos los modelos
		$this->load->model('compra');
		//Traemos los parametros de la url
		$id_coti = $this->input->GET('id_coti');
		$id_soli = $this->input->GET('id_soli');
		/*GENERAMOS LA FECHA*/
		$dtz = new DateTimeZone("America/Bogota");
		$dt = new DateTime("now", $dtz);
		$fecha = date('Y-m-d') . 'T' . $dt->format('H:i:s');
		$data_solicitud = $this->compra->get_solicitudes_By_id($id_soli);

		$estado = 0;
		foreach ($data_solicitud->result() as $key) {
			$estado = $key->estado_autorizacion;
		}
		if ($estado == 3) {
			echo "<h3>La Solicitud ya fue contestada.</h3>
			<h4>Puedes cerrar la pestaña del navegador</h4>";
		}else{
			$data_aut = array('estado' => 1);	
			if ($this->compra->update_solicitid_compra($data_aut,$id_coti)) {
				$info_coti = $this->compra->get_solicitudes_compra($id_soli);
				foreach ($info_coti->result() as $key) {
					$data = array('estado' => 2);
					$this->compra->update_solicitid_compra($data,$key->id_coti);
				}
			}
			$soli_apro = $this->compra->get_solicitudes_compra_aprobada($id_soli);
			$url_coti = "";
			foreach ($soli_apro->result() as $key) {
				$url_coti = $key->url;
			}
			$data_compra = array('estado_autorizacion' =>3,
				'fecha_autorizacion'=>$fecha,'cotizacion_file'=>$url_coti);
			if ($this->compra->update_solicitid($id_soli,$data_compra)) {
				echo "<h3>Solicitud de compra autorizada correctamente</h3>
				<h4>Puedes cerrar la pestaña del navegador</h4>";
			}else{
				echo "error";
			}
		}
	}

	public function rechazar_cotizacion()
	{
		//Traemos los modelos
		$this->load->model('compra');
		//Traemos los parametros de la url
		$id_coti = $this->input->GET('id_coti');
		$id_soli = $this->input->GET('id_soli');
		/*GENERAMOS LA FECHA*/
		$dtz = new DateTimeZone("America/Bogota");
		$dt = new DateTime("now", $dtz);
		$fecha = date('Y-m-d') . 'T' . $dt->format('H:i:s');
		$estado = 0;
		$data_solicitud = $this->compra->get_solicitudes_By_id($id_soli);
		foreach ($data_solicitud->result() as $key) {
			$estado = $key->estado_autorizacion;
		}
		if ($estado == 3) {
			echo "<h3>La Solicitud ya fue contestada.</h3>
			<h4>Puedes cerrar la pestaña del navegador</h4>";
		}else{
			$info_coti = $this->compra->get_solicitudes_compra($id_soli);
			foreach ($info_coti->result() as $key) {
				$data = array('estado' => 2);
				$this->compra->update_solicitid_compra($data,$key->id_coti);
			}
			$data_compra = array('estado_autorizacion' =>4,
				'fecha_autorizacion'=>$fecha);
			if ($this->compra->update_solicitid($id_soli,$data_compra)) {
				echo "<h3>Solicitud de compra rechazada correctamente</h3>
				<h4>Puedes cerrar la pestaña del navegador</h4>";
			}else{
				echo "error";
			}
		}
	}

	public function autorizar_compra_old()
	{
		$this->load->model('compra');
		//variables por la url
		$accion = $this->input->GET('auto');
		$id = $this->input->GET('id');
		//traemos la fecha actual con formato
		$dtz = new DateTimeZone("America/Bogota");
		$dt = new DateTime("now", $dtz);
		$fecha = date('Y-m-d') . 'T' . $dt->format('H:i:s');
		$data_solicitud = $this->compra->get_solicitudes_By_id($id);
		$estado = 0;
		foreach ($data_solicitud->result() as $key) {
			$estado = $key->estado_autorizacion;
		}
		if ($estado == 4 || $estado == 3) {
			echo "<h3>La Solicitud ya fue contestada.</h3>
			<h4>Puedes cerrar la pestaña del navegador</h4>";
		}else{
			if (isset($accion)) {
				if ($accion == 1) {
					$data_compra = array('estado_autorizacion' =>3,
										 'fecha_autorizacion'=>$fecha);
					if ($this->compra->update_solicitid($id,$data_compra)) {
						echo "<h3>Solicitud de compra autorizada correctamente</h3>
					<h4>Puedes cerrar la pestaña del navegador</h4>";
					}else{
						echo "error";
					}
				}else if($accion == 2){
					$data_compra = array('estado_autorizacion' =>4,
										 'estado'=>5);
					if ($this->compra->update_solicitid($id,$data_compra)) {
						echo "<h3>Solicitud de compra negada correctamente</h3>
					<h4>Puedes cerrar la pestaña del navegador</h4>";
					}else{
						echo "error";
					}
				}
			}else {
				echo "error";
			}
		}
	}

	public function cambiar_estado_compra()
	{
		$this->load->model('compra');
		//variables por la url
		$accion = $this->input->GET('auto');
		$id = $this->input->GET('id_soli');
		$estado = $this->input->GET('est');
		$data_compra = array('estado' => $estado);
		if ($this->compra->update_solicitid($id,$data_compra)) {
			echo "ok";
		}else{
			echo "err";
		}
	}

	public function get_can_solicitudes()
	{
		//Traemos los modelos
		$this->load->model('compra');
		//Traemos los parametros por url
		$tipo = $this->input->GET('tipo');
		if ($tipo == 1) {
			$cant_soli = $this->compra->get_cant_solicitudes("1")->n;
		}elseif($tipo == 2){
			$cant_soli = $this->compra->get_cant_solicitudes("2")->n;
		}elseif($tipo == 3){
			$cant_soli = $this->compra->get_cant_solicitudes("3,4")->n;
		}
		echo $cant_soli;
	}

	public function con_factura()
	{
		$this->load->model('compra');
		//variables por la url
		$id_soli = $this->input->GET('id_soli');
		$accion = $this->input->GET('accion');
		$data = array('con_factura' => $accion);
		if ($this->compra->update_solicitid($id_soli,$data)) {
			echo "ok";
		}else{
			echo "err";
		}
	}

	public function insert_msn_gest_compra()
	{
		//Se cargan los modelos
		$this->load->model('compra');
		//traemos los parametros
		$msn = $this->input->GET('msn');
		$id_soli = $this->input->GET('id_soli_msn');
		$usu = $this->session->userdata('user');
		$dtz = new DateTimeZone("America/Bogota");
		$dt = new DateTime("now", $dtz);
		$fecha = date('Y-m-d') . 'T' . $dt->format('H:i:s');
		$data = array('nit_usu' => $usu,'mensaje'=>$msn,'fecha'=>$fecha,'solicitud_compra'=>$id_soli);
		if ($this->compra->insert_msn_gest_compras($data)) {
			echo "ok";
		}else{
			echo "err";
		}
	}

	public function load_msn_solicitudes()
	{
		$this->load->model('compra');
		//traemos los parametros
		$id_soli = $this->input->GET('id_soli');
		$data_msn = $this->compra->get_msn_solicitud_compra($id_soli);
		foreach ($data_msn->result() as $key) {
			echo '<table class="table table-borderless">
						  <tbody>
						    <tr aling="center">
						      <th scope="row" align="left">'.$key->nombres.'</th>
						      <td scope="row">'.$key->mensaje.'</td>
						    </tr>
						  </tbody>
						</table>';
		}
	}

	/* Funcion para Descargar el listado de solicitudes por estado */
	public function get_solicitudes_descarga()
	{
		//Traemos los modelos
		$this->load->model('compra');
		$this->load->model('perfiles');
		//traemos los parametros
		$opt = $this->input->POST('opt');
		//obtenemos el perfil del usuario
		$usu = $this->session->userdata('user');
		$perfil_user = $this->perfiles->getPerfilByUser($usu);
		if ($perfil_user->id_perfil == 20 || $perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 28) {
			$data_solicitudes = $this->compra->get_solicitudes_descargar($usu = "",$opt);
		}else{
			$data_solicitudes = $this->compra->get_solicitudes_descargar($usu,$opt);
		}
		foreach ($data_solicitudes->result() as $key) {
			//echo $key->estado_autorizacion;
			if ($key->estado_autorizacion == 1) {
				$estado_au = "Sin autorización";
			}else if($key->estado_autorizacion == 2){
				$estado_au = "Pendiente de autorización";
			}else if($key->estado_autorizacion == 3 ){
				$estado_au = "Autorizado";
			}else if($key->estado_autorizacion == 4 ){
				$estado_au = "No autorizado";
			}
			/***********************************************/
			
			if ($key->estado == 1 ) {
				$estado = "Sin Revisar";
			}else if($key->estado == 2){
				$estado = "En Proceso";
			}else if($key->estado == 3){
				$estado = "En transito";
			}else if($key->estado == 4){
				$estado = "Despachada";
			}
			else if($key->estado == 5){
				$estado = "Negada";
			}		

			$data_msn = $this->compra->get_msn_solicitud_compra($key->id_solicitud);
			$msm = "";
			if ($data_msn->num_rows() > 0) {
				foreach ($data_msn->result() as $data) {
					$msm = '<table class="table table-borderless">
							  <tbody>
								<tr aling="center" class="noExl">
								  <th scope="row" align="left" class="noExl">' . $data->nombres . '</th>
								  <td scope="row" class="noExl">' . $data->mensaje . '</td>
								</tr>
							  </tbody>
							</table>';
				}
			}
			$f_autorizacion = ($key->fecha_autorizacion != "") ? $key->fecha_autorizacion : 'N/A';
			echo '<tr>
					<th scope="row" style="width: 5%">'.$key->id_solicitud.'</th>
					<td style="width: 30%">'.$key->descri_prod.'</td>
					<td style="width: 5%">
					'.$msm.'
					</td>
					<td>
					 '.$key->con_factura.'
                    </td>
					<td style="width: 10%">'.$estado.'</td>
					<td style="width: 10%">'.$estado_au.'</td>
					<td style="width: 30%">'.$key->usuario_reg.'</td>
					<td style="width: 30%">'.$key->gerente.'</td>
					<td style="width: 10%">'.$key->fecha_solicitud.'</td>
				    <td style="width: 10%">'.$f_autorizacion.'</td>
				    <td style="width: 10%">'.$key->dias_gest.'</td>
				 </tr>';
		}
	}
}
