<?php 

 /*
 *CONTROLADOR DE LOS TICKETS
 */

 class Tickets extends CI_Controller
 {
 	
 	public function index(){
 		if (!$this->session->userdata('login')) 
 		{
 			$this->session->sess_destroy();
 			header("Location: " . base_url());
 		}else{
 			/*MODELOS*/
 			$this->load->model('ticket');
 			$this->load->model('usuarios');
 			$this->load->model('menus');
 			$this->load->model('perfiles');


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
 			$admin = 0;
 			switch($usu){
 				case '1232892531':
 				$area = "sistemas";
 				break;
 				case '1232892531':
 				$area = "sistemas";
 				break;
 				
 				case '1098758579':
 				$area = "sistemas";
 				break;
 				case '1098758579':
 				$area = "sistemas";
 				break;
 				case '1098625558':
 				$area = "sistemas";
 				break;
 				case '1110602826':
 				$area = "sistemas";
 				break;
 				case '1096219894':
 				$area = "sistemas";
 				break;
 				case '1097304901':
 				$area = "sistemas";
 				break;
 				case '63369607':
 				$area = "nomina";
 				break;
 				case '1102384458':
 				$area = "gestion humana";
 				break;
 				case '63344288':
 				$area = "tesoreria";
 				break;
 				case '27882542':
 				$area = "salud ocupacional";
 				break;
 				case '63552277':
 				$area = "contabilidad";
 				break;
 				case '1128465895':
 				$area = "compras";
 				break;
 				case '63308540':
 				$area = "asistente de gerencia";
 				break;
 				default:
 				$area = "otro";
 				break;
 			}
 			foreach ($userinfo->result() as $key) {
 				$id_usu = $key->id_usuario;
 			}
 			$p = $perfil_user->id_perfil;
 			if ($p == 20 || $p == 1 || $p == 25 || $p == 26|| $p == 27|| $p == 28|| $p == 29) {
 				$all_tickets = $this->ticket->get_all_tickets($area,$usu);
 			}else{
 				$all_tickets = $this->ticket->get_all_tickets(0,$usu);
 				$admin = 1;
 			}	
 			$mis_tickets = $this->ticket->get_all_tickets_by_user($usu);
 			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu,'all_tickets'=>$all_tickets,'admin' => $admin,'usu'=>$usu,'mis_tickets'=>$mis_tickets);
			//abrimos la vista
 			$this->load->view("tickets",$arr_user);
 		}
 	}

 	public function new_ticket()
 	{
 		/*MODELOS*/
 		$this->load->model('ticket');
 		$this->load->model('uploadfile');
 		/*DATOS DEL FORMULARIO*/
 		$tipo_soporte = $this->input->POST('combo_tipo_soporte');
 		$anydesk2 = $this->input->POST('anydesk');
 		$usuario = $this->session->userdata('user');
 		$descripcion2 = $this->input->POST('descripcion');
		$archivo = $this->input->POST('archivo');
		$descripcion = str_replace("'","", $descripcion2);
		$anydesk = preg_replace('/[\@\.\;\"\' "]+/', ' ', $anydesk2);
		/* print_r($descripcion);die; */
 		switch($tipo_soporte){
 			case 'Hardware':
 			$encargado = 1232892531;
 			$area = "sistemas";
 			break;
 			case 'Software':
 			$encargado = 1232892531;
 			$area = "sistemas";
 			break;
 			case 'Insumos de Impresora(Toner)':
 			$encargado = 1232892531;
 			$area = "sistemas";
 			break;
 			case 'CRM Comercial':
 			$encargado = 1110602826;
 			$area = "sistemas";
 			break;
 			case 'CRM PosVenta':
 			$encargado = 1097304901;
 			$area = "sistemas";
 			break;
 			case 'CRM DMS':
 			$encargado = 1098625558;
 			$area = "sistemas";
 			break;
 			case 'DMS':
 			$encargado = 1098625558;
 			$area = "sistemas";
 			break;
 			/*fin sistemas*/
 			case 'Nomina':
 			$encargado = 63369607;
 			$area = "nomina";
 			break;
 			case 'Gestion Humana':
 			$encargado = 1102384458;
 			$area = "gestion humana";
 			break;
 			case 'Tesoreria':
 			$encargado = 63344288;
 			$area = "tesoreria";
 			break;
 			case 'Salud Ocupacional':
 			$encargado = 27882542;
 			$area = "salud ocupacional";
 			break;
 			case 'Contabilidad':
 			$encargado = 63552277;
 			$area = "contabilidad";
 			break;
 			case 'Compras':
 			$encargado = 1128465895;
 			$area = "compras";
 			break;
 			case 'Asistente de Gerencia':
 			$encargado = 63308540;
 			$area = "asistente de gerencia";
 			break;
 		}
 		$estado = "activo";
 		$fecha_respuesta = "NULL";

 		/*CARGAR ARCHIVO*/
 		$config['upload_path'] = './public/tickets/';
 		$config['allowed_types'] = '*';
 		$config['max_size'] = '10240000000';
 		$this->load->library('upload',$config);
		if ($archivo != "undefined") {
			
			if (!$this->upload->do_upload('archivo')) {
			   echo "error imagen";
			}
	
		} 		
 		
 		$data = $this->upload->data();
 		$img = $data['file_name'];
 		/*if (empty($img)) {
 			$img = "paila";
 		}*/
 		$data = array('tipo_soporte' => $tipo_soporte, 'anydesk' => $anydesk, 'usuario' => $usuario, 'descripcion' => $descripcion,'encargado' => $encargado,'estado' => $estado,'fecha_respuesta' => $fecha_respuesta,'img'=>$img,'area'=>$area);
 		/*INSERT*/
		/* print_r($data);die; */
 		if ($this->ticket->insert($data)) {
			echo "ok";
 		}else{
			echo "error";
 		}
 	}

 	public function asignar_ticket(){
 		/*MODELOS*/
 		$this->load->model('ticket');
 		/*DATOS FORMULARIO*/
 		$encargado = $this->input->POST('encargado');
 		$prioridad = $this->input->POST('prioridad');
 		$ticket = $this->input->POST('ticket');
 		$estado = "En Proceso";
 		/*UPDATE*/
 		$data = array('prioridad' => $prioridad, 'encargado' => $encargado,'estado'=>$estado);
 		if ($this->ticket->update_ticket($data,$ticket)) {
 			header("Location: " . base_url() . "tickets?log=ok");
 		}else{
 			header("Location: " . base_url() . "tickets?log=err");
 		}
 	}

 	public function get_ticket_by_id()
 	{
 		/*MODELOS*/
 		$this->load->model('ticket');
 		/*DATOS DEL FORMULARIO*/
 		$ticket = $this->input->GET('ticket');
 		$data = $this->ticket->get_ticket_by_id($ticket);
 		/*CARGA DE LA TABLA*/
 		$nom_usu = "";
 		foreach ($data->result() as $key) {
 			$respuestas = explode(",", $key->respuesta);
 			$nom_usu = $key->encargado;
 			$dat = array_pop($respuestas);
 			if (!empty($key->img)) {
 				echo '
 				<table class="table table-bordered">
 				<thead>
 				<tr>
 				<th>Usuario</th>
 				<th>Anydesk</th>
 				<th>Soporte</th>
 				<th>Fecha</th>
 				<th>Adjustos</th>
 				</tr>
 				</thead>
 				<tbody>
 				<tr>
 				<td>'.$key->usuario.'</td>
 				<td>'.$key->anydesk.'</td>
 				<td>'.$key->tipo_soporte.'</td>
 				<td>'.$key->fecha_creacion.'</td>
 				<td><a href="'.base_url().'public/tickets/'.$key->img.'" target="_blank">Ver</a></td>
 				</tr>
 				</tbody>
 				</table>
 				<table class="table table-bordered">
 				<thead>
 				<tr>
 				<th>Descripción</th>
 				</tr>
 				</thead>
 				<tbody>
 				<tr>
 				<td>'.$key->descripcion.'</td>
 				</tr>
 				</tbody>
 				</table>
 				
 				<strong>Respuestas</strong>
 				';
 			}else{
 				echo '
 				<table class="table table-bordered">
 				<thead>
 				<tr>
 				<th>Usuario</th>
 				<th>Anydesk</th>
 				<th>Soporte</th>
 				<th>Fecha</th>
 				</tr>
 				</thead>
 				<tbody>
 				<tr>
 				<td>'.$key->usuario.'</td>
 				<td>'.$key->anydesk.'</td>
 				<td>'.$key->tipo_soporte.'</td>
 				<td>'.$key->fecha_creacion.'</td>
 				</tr>
 				</tbody>
 				</table>
 				<table class="table table-bordered">
 				<thead>
 				<tr>
 				<th>Descripción</th>
 				</tr>
 				</thead>
 				<tbody>
 				<tr>
 				<td>'.$key->descripcion.'</td>
 				</tr>
 				</tbody>
 				</table>
 				
 				<strong>Respuestas</strong>
 				';
 			}
 			
 			foreach ($respuestas as $resp) {
 				echo '
 				<div class="card" style="width: 100%;">
 				<div class="card-body" align="left">
 				'.$resp.'
 				</div>
 				</div>
 				';
 			}
 		}
 	}

 	public function resp_ticket()
 	{
 		/*MODELOS*/
 		$this->load->model('ticket');
 		$this->load->model('usuarios');
 		/*NOMBRE USUARIO*/
 		$usu = $this->session->userdata('user');
 		$userinfo = $this->usuarios->getUserByName($usu);
 		$nom_usu = "";
 		foreach ($userinfo->result() as $key) {
 			$nom_usu = $key->nombres;
 		}
 		/*DATOS DEL FORMULARIO*/
 		$cerrar = $this->input->GET('cerrar');
 		$resp1 = $this->input->GET('resp');
 		$resp1 = $nom_usu.": ".$resp1;
 		$ticket = $this->input->GET('ticket');
 		$t_u = $this->input->GET('t_u');
 		if ($t_u=="admin") {
 			$user = $this->session->userdata('user');
 			$resp_anterior = $this->ticket->get_respuesta($ticket);
 			if ($cerrar == "on") {
 				$respuesta = $resp1.",".$resp_anterior->respuesta;
 				$data = array('estado' => 'Cerrado', 'respuesta'=>$respuesta,'encargado'=>$user);
 				if ($this->ticket->update_ticket($data,$ticket)) {
					echo 1;
				}else{
				   echo 0;
				}
 			}else{
 				$respuesta = $resp1.",".$resp_anterior->respuesta;
 				$data = array('respuesta'=>$respuesta,'encargado'=>$user,'estado'=>'En Proceso');
 				if ($this->ticket->update_ticket($data,$ticket)) {
					echo 1;
				}else{
				   	echo 0;
				}
 			}
 		}elseif ($t_u=="user") {
 			$resp_anterior = $this->ticket->get_respuesta($ticket);
 			if ($cerrar == "on") {
 				$respuesta = $resp1.",".$resp_anterior->respuesta;
 				$data = array('estado' => 'Cerrado', 'respuesta'=>$respuesta);
 				if ($this->ticket->update_ticket($data,$ticket)) {
					echo 1;
				}else{
				   echo 0;
				}
 			}else{
 				$respuesta = $resp1.",".$resp_anterior->respuesta;
 				$data = array('respuesta'=>$respuesta,'estado'=>'En Proceso');
			
 				if ($this->ticket->update_ticket($data,$ticket)) {
 					echo 1;
 				}else{
					echo 0;
 				}
 			}
 		}

 		
 	}

 	public function asig_ticket()
 	{
 		/*MODELOS*/
 		$this->load->model('ticket');
 		/*DATOS FORMULARIO*/
 		$prioridad = "Media";
 		$ticket = $this->input->GET('ticket');
 		$estado = "En Proceso";
 		/*UPDATE*/
 		$data = array('prioridad' => $prioridad,'estado'=>$estado);
 		if ($this->ticket->update_ticket($data,$ticket)) {
 			header("Location: " . base_url() . "tickets?log=ok");
 		}else{
 			header("Location: " . base_url() . "tickets?log=err");
 		}
 	}

	public function sendEmailRespuestas(){
		/*MODELOS*/
		$this->load->model('ticket');

		$ticket = $this->input->POST('id_ticket');
		$user = $this->input->POST('user');
		$infoTicket = $this->ticket->get_ticket_by_id($ticket);
		foreach ($infoTicket->result() as $key) {
			$tipoSoporte = $key->tipo_soporte;
			$asunto = $key->descripcion;
			$correoU = $key->correo_u;
			$correoE = $key->correo_enc;
			$respuestas = explode(",", $key->respuesta);
		}
		$msm = "";
		foreach ($respuestas as $resp) {
			$msm .= '
			<div class="card" style="width: 100%;">
			<div class="card-body" align="left">
			'.$resp.'
			</div>
			</div>
			';
		}
		




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
		if($user == 'admin'){
			$correo->addAddress($correoU);
		}else if ($user == 'user') {
			$correo->addAddress($correoE);
		}
		
		$correo->Username = "no-reply@codiesel.co";
		$correo->Password = "wrtiuvlebqopeknz";
		$correo->SetFrom("no-reply@codiesel.co", "CODIESEL S.A");
		$correo->Subject = "Tickets: Nuevo mensaje";
		$mensaje = '<!DOCTYPE html>
				<html lang="en">
				<head>
				<meta charset="UTF-8" />
				<meta http-equiv="X-UA-Compatible" content="IE=edge" />
				<meta name="viewport" content="width=device-width, initial-scale=1.0" />
				<title>Tickets</title>
				</head>
				<body>
				<div class="content" style="height: 100%; display: flex; align-items: center; justify-content: center;">
					<div id="tarjeta" class="card text-center w-75 mx-auto h-50" align="center" style="width: 90%; background-color: #f8f9fa; -webkit-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75); -moz-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);	box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.75);">
						<div id="cab-tarjeta" class="card-header" style="padding:5px">
							<h2>'.$asunto.'</h2>
						</div>
						<div class="card-body">
							<hr>
							'.$msm.'
						</div>
						<div class="card-footer bg-dark text-white" style="padding: 10px; position: relative; top: 20px; background-color: #343a40; color: white; justify-content: space-around;">
							<p style="color:white">Agradecemos no responder a este correo, pues es solo de carácter informativo.</p>
							<div  class="text-center">
								<a style="color:white" href="'.base_url().'tickets">Ingresar a la intranet para ver el ticket</a>
							</div>
						</div>
					</div>
				</div>
				</body>
				</html>
				';
		$correo->MsgHTML($mensaje);
		if ($correo->Send()) {
			echo "ok";
		}else {
			echo 'error';
		}
		
	}
 }

?>
