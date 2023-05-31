<?php 

$vendedor = $_GET['vendedor'];
$nit_flota = $_GET['nit_flota'];

$base_url = "http://190.242.117.122/Informe_flotas/";
//Conexion a la base de datos
$userdb = "intranet_postventa_cod";
$db = "codiesel2k";
$auth = 'C0d12020';
$serv = "192.168.0.239";
/* --- SE CREA LA CONEXION PARA QUE SEA GLOBAL EN EL DOCUMENTO --- */
$connectionInfo = array("Database" => $db, "UID" => $userdb, "PWD" => $auth, "CharacterSet" => "UTF-8");
$link = sqlsrv_connect($serv, $connectionInfo);
// Check connection
if (!$link) {
	echo "Failed to connect to SQLSVR: " . $link;
	exit();
}

$sql_tercero = "SELECT t.nit,t.nombres,t.celular,t.mail FROM terceros t WHERE nit = '".$nit_flota."'";
$result_tercero = sqlsrv_query($link, $sql_tercero, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
while ($row = sqlsrv_fetch_array($result_tercero)) {
	$data_tercero = ['nit'=>$row['nit'],'nombres'=>$row['nombres'],'celular'=>$row['celular'],'mail'=>$row['mail']];
}

$sql_asesor = "SELECT us.id_usuario FROM w_sist_usuarios us WHERE nit_usuario = '".$vendedor."'";
$result_asesor = sqlsrv_query($link, $sql_asesor, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
while ($row = sqlsrv_fetch_array($result_asesor)) {
	$data_asesor = ['id_usuario'=>$row['id_usuario']];
}

$sql_vh = "SELECT vm.idmodelo,vm.modelo,vm.precio_dcto,ma.ano
        FROM sw_com_vh_modelos vm
        JOIN vh_modelo m ON (vm.vh_modelo_id = m.modelo)
        JOIN vh_modelo_ano ma ON (ma.id_modano = vm.vh_modelo_ano_id)
        LEFT JOIN  sw_com_vh_lineas LIN  ON LIN.idlinea=vm.idlinea
        WHERE estado=1
        AND  LIN.activo=1 and LIN.img<>''";
$result_vh = sqlsrv_query($link, $sql_vh, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));


$data_total = array_merge($data_tercero,$data_asesor);



//print_r($data_total);
for ($i=0; $i < 1; $i++) { 
	echo '<form method="POST" action="">
		        	<div class="row">
		        		<div class="col">
		        			<label>Nombre Cliente</label>
		        			<input type="text" name="nom_cli" class="form-control" id="nom_cli" value="'.$data_total['nombres'].'" disabled="true">
		        		</div>
		        		<div class="col">
		        			<label>Nit Cliente</label>
		        			<input type="text" name="nit_cli" class="form-control" id="nit_cli" value="'.$data_total['nit'].'" readonly>
		        			<input type="hidden" name="id_usu" id="id_usu" value="'.$data_total['id_usuario'].'">
		        		</div>
		        	</div>
		        	<div class="row">
		        		<div class="col">
		        			<label>Telefono Cliente</label>
		        			<input type="text" name="tel_cli" class="form-control" id="tel_cli" value="'.$data_total['celular'].'" disabled="true">
		        		</div>
		        		<div class="col">
		        			<label>Correo Cliente</label>
		        			<input type="text" name="mail_cli" class="form-control" id="mail_cli" value="'.$data_total['mail'].'" disabled="true">
		        		</div>
		        	</div>
		        	
		        	<div class="row">
		        		<div class="col">
		        			<label>Selecciones un Vehículo</label>
		        			<select class="form-control select2" name="combo_vh" id="combo_vh">';

		        	while ($row = sqlsrv_fetch_array($result_vh)) {
						echo '<option value="'.$row['precio_dcto'].'">'.$row['modelo'].' - $'.number_format($row['precio_dcto'],0,",",",").'</option>';
					}
					echo '
							</select>
				        		</div>
		        	</div>
		        	<div class="row mt-2">
			       		<div class="col">
			       			<label>Numero de cuotas</label>
			       			<input type="number" class="form-control" name="num_cuotas" id="num_cuotas">
			       		</div>
			       		<div class="col">
			       			<label>Valor cuotas</label>
			       			<input type="text" class="form-control" name="val_cuotas" id="val_cuotas" value="">
			       			<small id="emailHelp" class="form-text text-muted">La taza es del 1.5%</small>
			       		</div>
			       	</div>
		        	<hr>
		        	<div class="row" aling="center">
		        		<div class="col">
		        			<input type="button" name="btn_enviar" id="btn_enviar" class="btn btn-success" value="Iniciar Nuevo Negocio" onclick="iniciarNegocio();">
		        		</div>
		        		<div class="col">
		        			<input type="button" name="no_int" id="no_int" class="btn btn-secondary" value="Cliente no Interesado" onclick="clienteNoInteresado();">
		        		</div>
		        	</div>
		        </form>';
}




//echo $vendedor." ".$nit_flota;
 ?>