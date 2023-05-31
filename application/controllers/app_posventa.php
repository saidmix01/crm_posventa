<?php

/**
 * 
 */
class App_posventa extends CI_Controller
{

	public function validar_vh()
	{
		$this->load->model('app_codiesel');
		$placa = $this->input->get('placa');
		$n = $this->app_codiesel->validar_vh($placa)->n;
		$data[] = array('n' => $n);
		echo json_encode($data);
	}

	public function get_data_client()
	{
		$this->load->model('app_codiesel');
		$nit = $this->input->GET('nit');
		$nom_cli = $this->app_codiesel->get_nom_client_by_nit($nit);
		$data[] = array(
			'nombre' => $nom_cli->nombre,
			'nit' => $nom_cli->nit,
			'telefono' => $nom_cli->celular,
			'correo' => $nom_cli->mail,
			'direccion' => $nom_cli->direccion
		);
		echo json_encode($data);
	}

	public function get_data_vh()
	{
		$this->load->model('app_codiesel');
		$nit = $this->input->GET('nit');
		$data_vh = $this->app_codiesel->get_info_vh($nit);
		$placa = "";
		foreach ($data_vh->result() as $key) {
			$data = array(
				'placa' => $key->placa,
				'modelo' => $key->modelo,
				'serie' => $key->serie,
				'descripcion' => $key->descripcion,
				'cilindraje' => $key->cilindraje,
				'clase' => $key->clase,
				'motor' => $key->motor,
				'ciudad_placa' => $key->ciudad_placa,
				'img' => $key->img
			);
			$placa = $key->placa;
		}
		if ($placa != "") {
			$data_vh_v2 = $this->app_codiesel->get_info_vh_v2($placa);
			foreach ($data_vh_v2->result() as $key) {
				if ($key->aseguradora == null) {
					$aseguradora = "Sin Aseguradora";
				} else {
					$aseguradora = $key->aseguradora;
				}
				if ($key->fecha_venc_seguro == null) {
					$fecha_venc_seguro = "--";
				} else {
					$fecha_venc_seguro = $key->fecha_venc_seguro;
				}
				if ($key->fecha_soat == null) {
					$fecha_soat = "--";
				} else {
					$fecha_soat = $key->fecha_soat;
				}
				if ($key->fecha_tecno_mecanica == null) {
					$fecha_tecno_mecanica = "--";
				} else {
					$fecha_tecno_mecanica = $key->fecha_tecno_mecanica;
				}
				$data2[] = array(
					'placa' => $data['placa'],
					'modelo' => $data['modelo'],
					'serie' => $data['serie'],
					'descripcion' => $data['descripcion'],
					'cilindraje' => $data['cilindraje'],
					'clase' => $data['clase'],
					'motor' => $data['motor'],
					'ciudad_placa' => $data['ciudad_placa'],
					'img' => $data['img'],
					'fecha_venc_seguro' => $fecha_venc_seguro,
					'aseguradora' => $aseguradora,
					'fecha_soat' => $fecha_soat,
					'fecha_tecno_mecanica' => $fecha_tecno_mecanica
				);
			}
		}
		echo json_encode($data2);
	}

	public function get_vh_taller()
	{
		$this->load->model('app_codiesel');
		$nit = $this->input->GET('nit');
		$data_vh = $this->app_codiesel->get_vh_taller($nit);
		$num = count($data_vh->result());
		if ($num != 0) {
			foreach ($data_vh->result() as $key) {
				$data[] = array('placa' => $key->placa, 'img' => $key->img, 'descripcion' => $key->descripcion);
			}
		} else {
			$data[] = array('placa' => "", 'img' => "", 'descripcion' => "");
		}

		echo json_encode($data);
	}
	public function get_vh_taller_detalle()
	{
		$this->load->model('app_codiesel');
		$placa = $this->input->GET('placa');
		$data_vh = $this->app_codiesel->get_vh_taller_detalle($placa);
		$num = count($data_vh->result());
		if ($num != 0) {
			foreach ($data_vh->result() as $key) {
				$data[] = array('orden' => $key->orden, 'descripcion' => $key->Descripcion, 'valor' => $key->valor, 'cantidad' => $key->cantidad);
			}
		} else {
			$data[] = array('orden' => "", 'descripcion' => "", 'valor' => "", 'cantidad' => "");
		}


		echo json_encode($data);
	}

	public function actualizar_datos()
	{
		$this->load->model('usuarios');
		$nit = $this->input->POST('nit');
		$nombres = $this->input->POST('nombres');
		$telefono = $this->input->POST('telefono');
		$mail = $this->input->POST('correo');
		$direccion = $this->input->POST('direccion');
		//$data = array('nombres' => $nombres,'direccion'=>$direccion,'mail'=> $correo,'celular'=>$telefono);
		$msn = "Se ha generado una nueva Solicitud de actualizacion de datos del Tercero con cedula: " . $nit;
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

		$correo->addAddress("programador@codiesel.co");
		$correo->Username = "no-reply@codiesel.co";
		$correo->Password = "wrtiuvlebqopeknz";
		$correo->SetFrom("no-reply@codiesel.co", "CODIESEL S.A");
		$correo->Subject = "Solicitud Actualizacion Tercero";

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
				">Solicitud Actualizacion Tercero</h2>
				<p class="card-text" style="padding: 20px;

				font-size: 1rem;
				line-height: 1.5;
				word-wrap: break-word;
				margin-bottom: 35px;">
				' . $msn . '
				</p>
				<hr>
				<strong>Nuevos Datos<strong>
				<table class="table" border="1">
				  <tbody>
				    <tr>
				      <th scope="row">Nombres</th>
				      <td>' . $nombres . '</td>
				    </tr>
				    <tr>
				      <th scope="row">Teléfono</th>
				      <td>' . $telefono . '</td>
				    </tr>
				    <tr>
				      <th scope="row">Correo Electrónico</th>
				      <td>' . $mail . '</td>
				    </tr>
				     <tr>
				      <th scope="row">Dirección</th>
				      <td>' . $direccion . '</td>
				    </tr>
				  </tbody>
				</table>
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
		$url_img = "";		
		$archivo = base_url() . $url_img;
		$correo->AddAttachment($archivo, $archivo);
		$correo->MsgHTML($mensaje);
		if (!$correo->Send()) {
			echo "err";
		} else {
			echo "ok";
		}

	}

	public function enviar_msn_codigo()
	{
		//Traemos los modelos
		$cod = $this->input->GET('cod');
		$mail = $this->input->GET('correo');
		$msn = "Se ha generado un nuevo codigo de verificación: " . $cod;
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

		$correo->addAddress($mail);
		$correo->Username = "no-reply@codiesel.co";
		$correo->Password = "wrtiuvlebqopeknz";
		$correo->SetFrom("no-reply@codiesel.co", "CODIESEL S.A");
		$correo->Subject = "CODIESEL SA: Codigo de verificacion";

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
		">Codigo de verificacion Aplicación Movil</h2>
		<p class="card-text" style="padding: 20px;

		font-size: 1rem;
		line-height: 1.5;
		word-wrap: break-word;
		margin-bottom: 35px;">
		' . $msn . '
		</p>
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
		$correo->MsgHTML($mensaje);
		if (!$correo->Send()) {
			$datos[] = ['resp' => "err"];
		} else {
			$datos[] = ['resp' => "ok"];
		}
		echo json_encode($datos);

	}

	public function insert_usuarios()
	{
		//Cargamos los modelos
		$this->load->model('app_codiesel');
		//Parametros
		$datos = $this->input->POST();
		if ($this->app_codiesel->insert_usuarios($datos)) {
			echo "ok";
		} else {
			echo "err";
		}
	}

	public function update_usuarios()
	{
		//Cargamos los modelos
		$this->load->model('app_codiesel');
		//Parametros
		$datos = $this->input->POST();
		$nit = $this->input->POST('nit');
		if ($this->app_codiesel->update_usuarios($datos, $nit)) {
			echo "ok";
		} else {
			echo "err";
		}
	}

	public function validar_usuarios()
	{
		//Cargamos los modelos
		$this->load->model('app_codiesel');
		//Parametros
		$datos = $this->input->GET('nit');
		$n = $this->app_codiesel->validar_usuario($datos)->n;
		if ($n != 0) {
			$data[] = array('resp' => "exist");
		} else {
			$data[] = array('resp' => "err");
		}
		echo json_encode($data);
	}

	public function validar_codigo_verifi()
	{
		//llamamos los modelos
		$this->load->model('app_codiesel');
		//traemos los parametros de la url
		$nit = $this->input->GET('nit');
		$cod = $this->input->GET('cod');

		$n = $this->app_codiesel->validar_codigo_verifi($nit, $cod)->n;
		if ($n == 1) {
			$data[] = array('resp' => "ok");
		} else {
			$data[] = array('resp' => "err");
		}
		echo json_encode($data);
	}

	public function get_datos_usuario()
	{
		$this->load->model('app_codiesel');
		//traemos los parametros de la url
		$nit = $this->input->GET('nit');
		$data_usu = $this->app_codiesel->get_datos_usuario($nit);

		if (count($data_usu->result()) != 0) {
			foreach ($data_usu->result() as $key) {
				$data[] = array('nit' => $key->nit, 'nombres' => $key->nombres, 'celular' => $key->celular, 'mail' => $key->mail, 'direccion' => $key->direccion, 'fecha_nac' => $key->fecha_nac, 'pass' => $key->pass);
			}
		} else {
			$data[] = array('nit' => "", 'nombres' => "", 'celular' => "", 'mail' => "", 'direccion' => "", 'fecha_nac' => "", 'pass' => "");
		}
		echo json_encode($data);
	}

	public function desvincular_vh()
	{
		//cargamos los modelos
		$this->load->model('app_codiesel');
		//traemos los parametros de la url
		$placa = $this->input->GET('placa');
		//ejecutamos el update en la tabla referencias imp
		if ($this->app_codiesel->desvincular_vh($placa)) {
			echo "ok";
		} else {
			echo "err";
		}
	}

	public function get_aseguradoras()
	{
		//llamamos los modelos
		$this->load->model('app_codiesel');
		//ejecutamos la consulta
		$data = $this->app_codiesel->get_aseguradoras();
		foreach ($data->result() as $key) {
			$info[] = array('nit' => $key->nit, 'nombres' => $key->nombres);
		}
		echo json_encode($info);
	}

	public function update_referencias_imp()
	{
		$this->load->model('app_codiesel');
		//traemos los parametros de la url
		$placa = $this->input->POST('placa');
		$fec_seguro = $this->input->POST('fec_seguro');
		$fec_soat = $this->input->POST('fec_soat');
		$fec_tecno = $this->input->POST('fec_tecno');
		$aseguradora = $this->input->POST('aseguradora');
		//echo $fec_seguro;die;
		/*Obtenemos el nit de la aseguradora*/
		$data_ase = $this->app_codiesel->get_aseguradorasByName($aseguradora);
		$nit_aseguradora = "";
		foreach ($data_ase->result() as $key) {
			$nit_aseguradora = $key->nit;
		}
		try {
			$data = array(
				'fec_vencimiento_seg' => $fec_seguro,
				'fecha_obligatorio' => $fec_soat,
				'fecha_tecnico_mecanica' => $fec_tecno,
				'nit_aseguradora' => $nit_aseguradora
			);
			//ejecutamos el update en la tabla referencias imp
			if ($this->app_codiesel->update_referencias_imp($data, $placa)) {
				echo "ok";
			} else {
				echo "err";
			}
		} catch (Exception $e) {
			echo "err";
		}

	}
}

?>