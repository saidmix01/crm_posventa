<?php

$nitCliente = $_POST['iDCliente'];
$tipoTabla = $_POST['iTipoTabla'];
$iFuente = $_POST['iFuente'];
//$asesor = $_POST['asesor'];
$asesor = 613;
$iRegVitrina = 0;
$iRegProspecto = 0;


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

//Se obtiene el id del cliente en la tabla de terceros
$sqlIdCliente = "SELECT id FROM terceros WHERE nit=".$nitCliente;

$result_cl = sqlsrv_query($link, $sqlIdCliente, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
$idCl = 0;
while($row = sqlsrv_fetch_array($result_cl, SQLSRV_FETCH_ASSOC)) {
    $idCl = $row['id'];
}

//Se verifica si hay negocios con el cliente
$sqlWorkFlowCliente = "SELECT idworkflownegocio,idasesor FROM swcrm_workflow_negocio WHERE idtercero=".$idCl;

$result_WFC = sqlsrv_query($link, $sqlWorkFlowCliente, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
$idWorkflow = 0;
$idAsesor = 0;
while($row = sqlsrv_fetch_array($result_WFC, SQLSRV_FETCH_ASSOC)) {
    $idWorkflow = $row['idworkflownegocio'];
    $idAsesor = $row['idasesor'];
}

if ($idWorkflow == 0) {
    $sqlWorkFlow = "INSERT INTO swcrm_workflow_negocio (idworkflow, idtercero, idregvitrina, idregprospecto, idasesor, fecharegistro, nit_fuente_prospecto ) 
       VALUES(1, '".$idCl."', '".$iRegVitrina."', '".$iRegProspecto."', '".$asesor."', GETDATE(), '".$iFuente."')";

    $result_WorkFlow = sqlsrv_query($link, $sqlWorkFlow, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
    if ($result_WorkFlow === false) {
        die(print_r(sqlsrv_errors(), true));
    }else{
        // se obtiene el id del worflowneg que se acaba de crear
        $sqlWorkFlowId = "SELECT idworkflownegocio FROM swcrm_workflow_negocio WHERE idtercero=".$idCl;

        $result_WFId = sqlsrv_query($link, $sqlWorkFlowId, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        $idWorkflowNeg = 0;
        while($row = sqlsrv_fetch_array($result_WFId, SQLSRV_FETCH_ASSOC)) {
            $idWorkflowNeg = $row['idworkflownegocio'];
        }

        $sqlEtapa = "INSERT INTO swcrm_historial_etapas(idworkflownegocio, idetapa, idusuario, fechahora ) VALUES ($idWorkflowNeg, 14, '".$asesor."', GETDATE())";

        $result_Etapa = sqlsrv_query($link, $sqlEtapa, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        if ($result_Etapa === false) {
            die(print_r(sqlsrv_errors(), true));
        }else{
            echo 1;
        }
    }
}else{
    //Se verifica las etapas del negocio con el cliente
    $sqlWorkFlowCliente = "SELECT idetapa FROM swcrm_historial_etapas WHERE idworkflownegocio=".$idWorkflow;

    $result_WFC = sqlsrv_query($link, $sqlWorkFlowCliente, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
    $finalizado = 0;
    
    while($row = sqlsrv_fetch_array($result_WFC, SQLSRV_FETCH_ASSOC)) {
        //$idEtapa = $row['idetapa'];
        if ($row['idetapa'] == 12 || $row['idetapa'] == 13) {
            $finalizado = 1;
        }        
    }    

    if ($finalizado == 0) {
        //Se obtiene el nombre del asesor que está asignado al negocio
        $sqlNomAsesor = "SELECT nombres FROM terceros WHERE id=".$idAsesor;

        $result_asesor = sqlsrv_query($link, $sqlNomAsesor, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        $nombre = '';
        while($row = sqlsrv_fetch_array($result_asesor, SQLSRV_FETCH_ASSOC)) {
            $nombre = $row['nombres'];
        }
        echo $nombre;
    } else {
        $sqlWorkFlow = "INSERT INTO swcrm_workflow_negocio (idworkflow, idtercero, idregvitrina, idregprospecto, idasesor, fecharegistro, nit_fuente_prospecto ) 
       VALUES(1, '".$idCl."', '".$iRegVitrina."', '".$iRegProspecto."', '".$asesor."', GETDATE(), '".$iFuente."')";

        $result_WorkFlow = sqlsrv_query($link, $sqlWorkFlow, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        if ($result_WorkFlow === false) {
            die(print_r(sqlsrv_errors(), true));
        }else{
            // se obtiene el id del worflowneg que se acaba de crear
            $sqlWorkFlowId = "SELECT idworkflownegocio FROM swcrm_workflow_negocio WHERE idtercero=".$idCl;

            $result_WFId = sqlsrv_query($link, $sqlWorkFlowId, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
            $idWorkflowNeg = 0;
            while($row = sqlsrv_fetch_array($result_WFId, SQLSRV_FETCH_ASSOC)) {
                $idWorkflowNeg = $row['idworkflownegocio'];
            }

            $sqlEtapa = "INSERT INTO swcrm_historial_etapas(idworkflownegocio, idetapa, idusuario, fechahora ) VALUES ($idWorkflowNeg, 14, '".$asesor."', GETDATE())";

            $result_Etapa = sqlsrv_query($link, $sqlEtapa, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
            if ($result_Etapa === false) {
                die(print_r(sqlsrv_errors(), true));
            }else{
                echo 1;
            }
        }
    }
    
    
}

?>