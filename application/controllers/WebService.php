<?php

/**
 * 
 */
class WebService extends CI_Controller
{

	public function test_ws()
	{
		$this->load->view('test_ws');
	}


	public function get_info_ordenes_taller()
	{
		//cargamos los modelos
		$this->load->model('taller');
		//traemos los parametros por url get
		//$orden = $this->input->GET('orden');
		//traemos los parametros por url post
		$orden = $this->input->POST('orden');

		//decodificamos el dato
		//$orden = base64_decode($orden);
		//validamos que el campo orden no este vacio
		if ($orden == "") {
			echo "The field orden is empty";
		} else {

			//ejecutamos la consulta que trae la info 
			//la consulta recibe como parametro el numero de la orden
			$result = $this->taller->get_info_ordenes_taller($orden);
			$num_rows = count($result->result());
			//validamos que la consulta traiga datos
			if ($num_rows != 0) {
				//recorremos los datos de la consulta
				foreach ($result->result() as $key) {
					//creamos las variables
					$orden = "$key->numero";
					$nombres = $key->nombres;
					$apellidos = $key->apellidos;
					$kilometraje = "$key->kilometraje";
					$nit = $key->nit;
					$mail = $key->mail;
					$celular = $key->celular;
					$placa = $key->placa;
					$modelo = $key->des_modelo;
					$marca = $key->des_marca;
					$ano = "$key->ano";
					$chasis = $key->serie;
					//variables ivvuo
					$accountId = "5c23d8c6afaa8b00043e0828";
					$centerId = "5eb9ac3d915f9b0004ee7332";
					$center_code = "CS038";
					$account_code = "AC001";

					if ($apellidos == "") {
						$apellidos = ".";
					}
					//array en el que se guarda la info
					$info_orden = array(
						"or" => $orden,
						"name" => $nombres,
						"last_name" => $apellidos,
						"kiolometers" => $kilometraje,
						"nit" => $nit,
						"email" => $mail,
						"telephone" => $celular,
						"plate" => $placa,
						"model" => $modelo,
						"brand" => $marca,
						"year" => $ano,
						"vin" => $chasis,
						"accountId" => $accountId,
						"centerId" => $centerId,
						"center_code" => $center_code,
						"account_code" => $account_code
					);
				}
				// retornamos los datos del array en un json
				echo json_encode($info_orden);
			} else {
				echo "The query didn't return data";
			}
		}
	}

	public function get_info_cotizaciones_taller()
	{
		//cargamos los modelos
		$this->load->model('taller');
		//traemos los parametros por url post
		$orden = $this->input->POST('orden');

		//decodificamos el dato
		//$orden = base64_decode($orden);
		//validamos que el campo orden no este vacio
		if ($orden == "") {
			echo "The field orden is empty";
		} else {

			//ejecutamos la consulta que trae la info 
			//la consulta recibe como parametro el numero de la orden
			$result = $this->taller->get_info_cotizaciones_taller($orden);
			$num_rows = count($result->result());
			//validamos que la consulta traiga datos
			if ($num_rows != 0) {
				//recorremos los datos de la consulta
				foreach ($result->result() as $key) {
					//creamos las variables
					$clasificaPor = $key->clasificarPor;
					$actividad = ".";
					$grupo = $key->grupo;
					$tipo = $key->tipo;
					$referencia = $key->referencia;
					$descripcion = $key->descripcion;
					$codigodeparte = $key->referencia;
					$cantidad = $key->cantidad;
					$valorUnitario = $key->valorUnitario;
					$valorTotal = $key->valorTotal;
					$orden = $key->orden;
					//variables ivvuo
					$accountId = "5c23d8c6afaa8b00043e0828";
					$centerId = "5eb9ac3d915f9b0004ee7332";
					$center_code = "CS038";
					$account_code = "AC001";
					//array en el que se guarda la info
					$info_orden[] = array(
						"clasificaPor" => $orden,
						"actividad" => $actividad,
						"grupo" => $grupo,
						"tipo" => $tipo,
						"referencia" => $referencia,
						"descripcion" => $descripcion,
						"codigoDeParte" => $codigodeparte,
						"cantidad" => $cantidad,
						"valorUnitario" => $valorUnitario,
						"valorTotal" => $valorTotal,
						"orden" => $orden,
						"accountId" => $accountId,
						"centerId" => $centerId,
						"center_code" => $center_code,
						"account_code" => $account_code
					);
				}
				// retornamos los datos del array en un json
				echo json_encode($info_orden);
			} else {
				echo "The query didn't return data";
			}
		}
	}


	/*WEB SERVICE QUE ENVIA Informe MENSUAL DEL ESTADO DE LOS AGENTES EN BANCODELEADS*/

	public function inf_leads_agentes()
	{
		//LLAMAMOS LOS MODELOS
		$this->load->model('Informe');
		//TRAEMOS LOS PARAMETROS DE LA URL
		$fecha = $this->input->GET('fecha');
		//PRIMER DIA DEL MES ACTUAL
		$primer_dia = $this->Informe->get_primer_dia_mes()->fecha;
		//$primer_dia = "2021-11-08";
		//NOMBRE DEL MES ACTUAL 
		$nom_mes = $this->Informe->get_nombre_mes_actual()->mes;


		if ($fecha == $primer_dia) {
			//INFORMACION DEL Informe
			$data_inf = $this->Informe->inf_leads_agentes();
			$msn = '<table border="1">
			<thead>
			<tr>
			<th scope="col">Asesor</th>
			<th scope="col"># Leads</th>
			</tr>
			</thead>
			<tbody>';
			foreach ($data_inf->result() as $key) {
				$val = $key->n_leads . " ";
				$msn .= '<tr>
				<th scope="row">' . $key->nombres . '</th>
				<td>' . $val . '</td>
				</tr>';
			}
			$msn .= ' </tbody>
			</table>';
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
			$correo->addAddress("personal@codiesel.co");
			$correo->addAddress("saidandresmix01@gmail.com");
			$correo->Username = "programador@codiesel.co";
			$correo->Password = "C0d13s3l2021";
			$correo->SetFrom("programador@codiesel.co", "Said Andres A.");
			$correo->Subject = "Informe Leads Agentes " . $nom_mes;

			$mensaje = $msn;
			$correo->MsgHTML($mensaje);
			if (!$correo->Send()) {
				echo "err";
			}
			echo "ok";
		} else {
			echo "err";
		}
	}

	/*****************WEB SERVICE QUE ENVIA UN CORREO SOLICITANDO APROBACION PARA CAMBIO DE TERCERO*********************/

	public function envio_mail_cambio_tercero()
	{
		//LLAMAMOS LOS MODELOS
		$this->load->model('comercial');
		$this->load->model('sedes');
		$this->load->model('usuarios');
		//TRAEMOS LOS PARAMETROS DE LA URL
		$idwf = $this->input->GET('idwf');
		//BUSCAMOS EL REGISTRO PARA TRAER LA INF
		$data_ter = $this->comercial->get_info_cambio_tercero($idwf);
		$msn = '';
		$idtercero = 0;
		$id_solicitud = 0;
		$id_asesor = 0;
		foreach ($data_ter->result() as $key) {
			$msn = "El Asesor <strong>" . $key->nomasesor . "</strong> Solicita El Cambio De Tercero De <strong>" . $key->nom_clie_ant . "</strong> A <strong>" . $key->nomterceroCambio . "</strong> ¿Autoriza?";
			$idtercero = $key->idtercero;
			$id_solicitud = $key->id;
			$id_asesor = $key->idasesor;
		}

		$nit_asesor = $this->usuarios->getUsuarioById($id_asesor);
		$nit = $nit_asesor->nit_usuario;
		$data_sedes = $this->sedes->get_sedes_user($nit);

		$sedes = array();
		foreach ($data_sedes->result() as $key) {
			$sedes[] = $key->descripcion;
		}
		$b_giron = ['CODIESEL VILLA DEL ROSARIO', 'CHEVYEXPRESS LA ROSITA', 'SOLOCHEVROLET', 'CODIESEL PRINCIPAL', 'CHEVYEXPRESS BARRANCA', 'CONTAC CENTER'];
		$b_cucuta = ['ACCESORIOS CUCUTA', 'SOLOCHEVROLET MALECON'];

		$receptor = "";
		for ($i = 0; $i < count($sedes); $i++) {
			if (in_array($sedes[$i], $b_giron)) {
				$receptor = "dir.byc@codiesel.co"; //correo José Luis - Girón- Rosita - Barrranca
			}
			if (in_array($sedes[$i], $b_cucuta)) {
				$receptor = "dir.cucuta@codiesel.co"; // correo Jorge Franco - Cucuta - Malecon - Bocono
			}
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
		//$correo->addAddress("personal@codiesel.co");
		//$correo->addAddress("programador3@codiesel.co");
		$correo->addAddress($receptor);
		$correo->Username = "programador@codiesel.co";
		$correo->Password = "C0d13s3l2021";
		$correo->SetFrom("programador@codiesel.co", "INTRANET CODIESEL");
		$correo->Subject = "Cambio de Tercero ";

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
		">Solicitud Cambio De Tercero</h2>
		<p class="card-text" style="padding: 20px;

		font-size: 1rem;
		line-height: 1.5;
		word-wrap: break-word;
		margin-bottom: 35px;">
		' . $msn . '
		</p>
		<a href="' . base_url() . 'WebService/autorizar_cambio_ter?idwf=' . $idwf . '&idtercero=' . $idtercero . '&id_solicitud=' . $id_solicitud . '" class="btn btn-warning" style=" padding: 10px 15px;
		text-decoration: none;
		background-color: #ffc107;
		color: rgb(71, 68, 68);">Aceptar</a>
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
		echo "ok";
	}

	public function autorizar_cambio_ter()
	{
		/*LLAMAMOS LOS MODELOS*/
		$this->load->model('comercial');
		/*TRAEMOS LAS VARIABLES POR URL*/
		$idwf = $this->input->get('idwf');
		$idtercero = $this->input->get('idtercero');
		$id_solicitud = $this->input->get('id_solicitud');


		if ($this->comercial->update_hist_ter_neg($id_solicitud)) {
			echo "<h3>Registro Guardado Exitosamente</h3>";
		} else {
			echo "Error 001";
		}
		echo "<br>";
		if ($this->comercial->update_wf_neg($idtercero, $idwf)) {
			echo "<strong>Ya Puedes Cerrar Esta Ventana</strong>";
		} else {
			echo "Error 002";
		}
	}

	/*FUNCION QUE ENVIA UN CORREO SOLICITANDO LA APROBACION DE CAMBIO DE AGENTE DE UN LEAD*/

	public function envio_mail_cambio_lead_agente()
	{
		//Llamamos los modelos
		$this->load->model('comercial');
		$this->load->model('usuarios');
		//Recibimos los parametos por URL
		$idlead = $this->input->POST('idlead');
		$idagente = $this->input->POST('idagente');
		//Traemos los datos de las consultas
		$data_lead = $this->comercial->get_lead_by_agente($idlead);
		//Data para el usuario que solicita
		$data_user_so = $this->usuarios->getUsuarioById($data_lead->idagente);
		//Data para el usuario que recibe
		$data_user_re = $this->usuarios->getUsuarioById($idagente);
		if ($this->comercial->update_swcc_bancodelead($idlead, $idagente)) {
			//Datos para el correo
			//echo "solicita ".$data_user_so->nombres." cliente ".$data_lead->nombres." recibe ".$data_user_re->nombres;die;
			$msn = "El Agente: <strong>" . $data_user_so->nombres . "</strong> Solicitó El Traslado Del Cliente: <strong>" . $data_lead->nombres . "</strong> A: <strong>" . $data_user_re->nombres . "</strong><br>";

			//echo $msn; die;
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
			//$correo->addAddress("saidandresmix01@gmail.com");
			//$correo->addAddress("programador3@codiesel.co");
			$correo->addAddress("coor.calidad@codiesel.co");
			$correo->Username = "programador@codiesel.co";
			$correo->Password = "C0d13s3l2021";
			$correo->SetFrom("programador@codiesel.co", "INTRANET CODIESEL");
			$correo->Subject = "Notificacion Cesion de Lead ";

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
			">Notificacion Cesion de Lead</h2>
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
			echo "ok";
		} else {
			echo "Error 001";
		}

		//echo "ok";
	}


	//Envia notificacion de flotas al coord de posventa y coor ventas

	public function envioNotifiFlotas()
	{
		$nomCli = $this->input->GET('nomCli');
		$mail = $this->input->GET('mail');
		//echo "solicita ".$data_user_so->nombres." cliente ".$data_lead->nombres." recibe ".$data_user_re->nombres;die;
		$msn = "Una nueva flota a nombre de: <strong>" . $nomCli . "</strong> Ha sido actualizada, favor ingresar a la intranet para revisarla.<br>";

		//echo $msn; die;
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
		//$correo->addAddress("saidandresmix01@gmail.com");
		//$correo->addAddress("programador3@codiesel.co");
		$correo->addAddress($mail);
		$correo->Username = "no-reply@codiesel.co";
		$correo->Password = "wrtiuvlebqopeknz";
		$correo->SetFrom("no-reply@codiesel.co", "CODIESEL S.A");
		$correo->Subject = "Notificacion Gestión Flota ";

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
			">Notificacion Cesion de Lead</h2>
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
		}else{
			echo "ok";
		}
		//

		//echo "ok";
	}

	public function autorizar_cesion_lead()
	{
		/*LLAMAMOS LOS MODELOS*/
		$this->load->model('comercial');
		/*TRAEMOS LAS VARIABLES POR URL*/
		$idlead = $this->input->get('idlead');
		$idagente = $this->input->get('idagente');

		if ($this->comercial->update_swcc_bancodelead($idlead, $idagente)) {
			echo "<h3>Lead Actualizado Exitosamente</h3>";
		} else {
			echo "Error 001";
		}
	}

	/*WEB SERVICE QUE ENVIA Informe MENSUAL DEL ESTADO DE LOS AGENTES EN BANCODELEADS*/

	public function inf_doc_sin_ot()
	{
		//LLAMAMOS LOS MODELOS
		$this->load->model('Informe');
		//INFORMACION DEL Informe
		$data_inf = $this->Informe->get_facs_sin_ot();
		if (count($data_inf->result()) != 0) {
			$msn = '<h3>Envio Reporte de Documentos Cargados a Cuenta Contable De TOT Y Materiales Que No Tienen Relacionada Una Orde De Taller<h3>
			<hr>
			<table border="1">
			<thead>
			<tr>
			<th scope="col">Documento</th>
			<th scope="col">Fecha</th>
			<th scope="col">Valor Cargado</th>
			<th scope="col">Usuario</th>
			<th scope="col">Notas</th>
			</tr>
			</thead>
			<tbody>';
			foreach ($data_inf->result() as $key) {
				$msn .= '
				<tr>
				<th scope="row">' . $key->docu . '</th>
				<td>' . $key->fecha . '</td>
				<td>$' . number_format($key->valor_cargado, 0, ",", ",") . '</td>
				<td>' . $key->des_usuario . '</td>
				<td>' . $key->notas . '</td>
				</tr>';
			}
			$msn .= ' </tbody>
			</table>';
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
			$correo->addAddress("ger.servicio@codiesel.co");
			//$correo->addAddress("saidandresmix01@gmail.com");
			//$correo->addAddress("sistemas@codiesel.co");
			$correo->Username = "programador@codiesel.co";
			$correo->Password = "C0d13s3l2021";
			$correo->SetFrom("programador@codiesel.co", "Intranet PosVenta");
			$correo->Subject = "Informe Facturas sin Orden ";

			$mensaje = $msn;
			$correo->MsgHTML($mensaje);
			if (!$correo->Send()) {
				echo "err";
			} else {
				echo "ok";
			}
		} else {
			echo "empty";
		}
	}

	/*WEB SERVICE QUE ENVIA Informe DE LAS FACTURAS DE GARANTIAS*/

	public function inf_fac_dmurcia()
	{
		//LLAMAMOS LOS MODELOS
		$this->load->model('talleres');
		//INFORMACION DEL Informe
		$data_inf = $this->talleres->get_doc_murcia();
		if (count($data_inf->result()) != 0) {
			$msn = '<h3>Envio Reporte Facturas de Garantias<h3>
			<hr>
			<table border="1">
			<thead>
			<tr>
			<th scope="col">Documento</th>
			<th scope="col">Fecha</th>
			<th scope="col">NIT</th>
			<th scope="col">Usuario</th>
			<th scope="col">Notas</th>
			</tr>
			</thead>
			<tbody>';
			foreach ($data_inf->result() as $key) {
				$msn .= '
				<tr>
				<th scope="row">' . $key->tipo . $key->numero . '</th>
				<td>' . $key->fecha . '</td>
				<td>' . $key->nit . '</td>
				<td>' . $key->nombres . '</td>
				<td>' . $key->descripcion . '</td>
				</tr>';
				$fechaa = date('Y-m-d');
				$data_insert_fac = array('tipo' => $key->tipo, 'doc' => $key->numero, 'fecha' => $fechaa);
				$this->talleres->insert_fac_dm($data_insert_fac);
			}
			$msn .= ' </tbody>
			</table>';
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
			$correo->addAddress("ger.servicio@codiesel.co");
			$correo->addAddress("saidandresmix01@gmail.com");
			$correo->addAddress("sistemas@codiesel.co");
			$correo->Username = "programador@codiesel.co";
			$correo->Password = "C0d13s3l2021";
			$correo->SetFrom("programador@codiesel.co", "Intranet PosVenta");
			$correo->Subject = "Informe Facturas garantias ";

			$mensaje = $msn;
			$correo->MsgHTML($mensaje);
			if (!$correo->Send()) {
				echo "err";
			} else {
				echo "ok";
			}
		}
	}

	public function validar_tercero()
	{
		//TRAEMOS LOS MODELOS 
		$this->load->model('AdministracionCodiesel');
		//TRAEMOS LOS PARAMETROS POR LA URL
		$nit = $this->input->GET('nit');
		//EJECUTAMOS LA CONSULTA
		$val_tercero = $this->AdministracionCodiesel->validar_tercero($nit)->n;
		if ($val_tercero != 0) {
			echo "ok";
		} else {
			echo "err";
		}
	}
}
