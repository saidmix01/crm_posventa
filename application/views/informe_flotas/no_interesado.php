<?php

$nitCliente = $_POST['iDCliente'];


$base_url = "http://intranet.codiesel.co/Informe_flotas/";
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

    $sql = "INSERT INTO swcrm_clientes_flotas_interes (nit_flota, interesado) VALUES (".$nitCliente.",1)";

    $result = sqlsrv_query($link, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
    if ($result === false) {
        die(print_r(sqlsrv_errors(), true));
    }else{
        echo 1;
    }

?>