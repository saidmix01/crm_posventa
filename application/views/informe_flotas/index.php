<?php
//Ruta del proyecto

/* $base_url_style = "http://intranet.codiesel.co/codiesel/Informe_flotas/"; //enlace para los estilos
$base_url = "http://localhost/postventa/info_flota/index";
$base_url_comercial = "http://localhost/postventa/home";
$base_url_agrupado = "http://localhost/postventa/info_flota/info_agrupado"; */

$base_url_style = "http://intranet.codiesel.co/codiesel/Informe_flotas/"; //enlace para los estilos
$base_url = "http://intranet.codiesel.co/postventa/info_flota/index";
$base_url_comercial = "http://intranet.codiesel.co/postventa/home";
$base_url_agrupado = "http://intranet.codiesel.co/postventa/info_flota/info_agrupado";


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
/*VALIDAMOS QUE EXISTAN LAS VARIABLES POST*/
if (isset($_POST['combo_asesor'])) {
	$combo_asesor = $_POST['combo_asesor'];
}
if (isset($_POST['combo_flota'])) {
	$combo_flota = $_POST['combo_flota'];
}
if (isset($_POST['fecha_ini_ven']) && isset($_POST['fecha_fin_ven'])) {
	$fecha_ini_ven = $_POST['fecha_ini_ven'];
	$fecha_fin_ven = $_POST['fecha_fin_ven'];
}
if (isset($_POST['fecha_ini_pent']) && isset($_POST['fecha_fin_pent'])) {
	$fecha_ini_pent = $_POST['fecha_ini_pent'];
	$fecha_fin_pent = $_POST['fecha_fin_pent'];
}
if (isset($_POST['fecha_ini_uent']) && isset($_POST['fecha_fin_uent'])) {
	$fecha_ini_uent = $_POST['fecha_ini_uent'];
	$fecha_fin_uent = $_POST['fecha_fin_uent'];
}
if (isset($_POST['combo_tipo'])) {
	$combo_tipo = $_POST['combo_tipo'];
}

if (isset($combo_tipo)) {
	if ($combo_tipo == 'agrupado') {
		//header('Location:' . $base_url_agrupado);
		echo '<script> window.location.replace("' . $base_url_agrupado . '"); </script>';
	}
}

//Listar Asesores
$sql_asesores = "SELECT va.vendedor AS nit,va.nombres FROM v_asesores_vh_total va";
$result_asesores = sqlsrv_query($link, $sql_asesores, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
while ($row = sqlsrv_fetch_array($result_asesores)) {
	$data_asesores[] = ['nit' => $row['nit'], 'nombres' => $row['nombres']];
}
/*Datos Informe General*/
$sql_inf_gral = "";
if (isset($combo_asesor)) {
	$sql_inf_gral = "SELECT * FROM v_flotas_gestionar WHERE vendedor = '" . $combo_asesor . "'";
	$sql_nom_asesores = "SELECT va.vendedor AS nit,va.nombres FROM v_asesores_vh_total va WHERE va.vendedor = " . $combo_asesor;
	$result_nom_asesores = sqlsrv_query($link, $sql_nom_asesores, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
	while ($row = sqlsrv_fetch_array($result_nom_asesores)) {
		$sub_titulo = $row['nombres'];
	}
	//$sub_titulo = $combo_asesor;
} elseif (isset($combo_flota)) {
	$sql_inf_gral = "SELECT * FROM v_flotas_gestionar WHERE tipo_flota = '" . $combo_flota . "'";
	$sub_titulo = $combo_flota;
} elseif (isset($fecha_ini_ven) && isset($fecha_fin_ven)) {
	$sql_inf_gral = "SELECT * FROM v_flotas_gestionar WHERE CONVERT(DATE,fecha_venta) BETWEEN CONVERT(DATE,'" . $fecha_ini_ven . "') AND CONVERT(DATE,'" . $fecha_fin_ven . "')";
	$sub_titulo = " Ventas desde el: " . $fecha_ini_ven . " hasta el: " . $fecha_fin_ven;
} elseif (isset($fecha_ini_pent) && isset($fecha_fin_pent)) {
	$sql_inf_gral = "SELECT * FROM v_flotas_gestionar WHERE CONVERT(DATE,primer_Entrada_Taller) BETWEEN CONVERT(DATE,'" . $fecha_ini_pent . "') AND CONVERT(DATE,'" . $fecha_fin_pent . "')";
	$sub_titulo = " Primera Entrada al Taller desde el: " . $fecha_ini_pent . " hasta el: " . $fecha_fin_pent;
} elseif (isset($fecha_ini_uent) && isset($fecha_fin_uent)) {
	$sql_inf_gral = "SELECT * FROM v_flotas_gestionar WHERE CONVERT(DATE,ultima_entrada_taller) BETWEEN CONVERT(DATE,'" . $fecha_ini_uent . "') AND CONVERT(DATE,'" . $fecha_fin_uent . "')";
	$sub_titulo = " Ultima Entrada al Taller desde el: " . $fecha_ini_uent . " hasta el: " . $fecha_fin_uent;
} else {
	$sql_inf_gral = "SELECT * FROM v_flotas_gestionar";
	$sub_titulo = "Histórico";
}
//echo $sql_inf_gral;
//$sql_inf_gral = "SELECT * FROM v_flotas_gestionar";
$vh_data[] = ['vh' => ""];
$vh_data1[] = ['vh' => "", 'flota' => "	"];
//$data_inf_gral[] = ['nit' => "",'asesor'=>"",'nit_cliente'=>"",'cliente'=>"",'flota'=>"",'vh'=>"",'placa'=>"",'fecha_p_e_t'=>"",'fecha_u_e_t'=>"",'fecha_venta'=>"",'tipo_flota'=>""];
$result_inf_gral = sqlsrv_query($link, $sql_inf_gral, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
while ($row = sqlsrv_fetch_array($result_inf_gral)) {
	$data_inf_gral[] = [
		'nit' => $row['vendedor'], 'asesor' => $row['asesor'], 'nit_cliente' => $row['nit'], 'cliente' => $row['cliente'], 'flota' => $row['flota'],
		'vh' => $row['descripcion'], 'placa' => $row['placa'], 'fecha_p_e_t' => $row['primer_Entrada_Taller'], 'fecha_u_e_t' => $row['ultima_entrada_taller'],
		'fecha_venta' => $row['fecha_venta'], 'tipo_flota' => $row['tipo_flota']
	];
	$vh_data[] = ['vh' => $row['descripcion']];
	$vh_data1[] = ['vh' => $row['descripcion'], 'flota' => $row['tipo_flota']];
}

//print_r($data_inf_gral);die;

/*CONTAR VH POR FLOTA */
$arr_vh = array_map('unserialize', array_unique(array_map('serialize', $vh_data1)));
$flota_integral = 0;
$flota_ventas = 0;
$flota_posventa = 0;
$flota_revisar = 0;
foreach ($data_inf_gral as $key) {
	if ($key['tipo_flota'] == 'Flota Integral') {
		$flota_integral++;
	} elseif ($key['tipo_flota'] == 'Flota Ventas') {
		$flota_ventas++;
	} elseif ($key['tipo_flota'] == 'Revisar Flota') {
		$flota_revisar++;
	} elseif ($key['tipo_flota'] == 'Flota Posventa') {
		$flota_posventa++;
	}
}

$graf_flotas = array(
	array("label" => "Flota Integral", "y" => $flota_integral),
	array("label" => "Flota Ventas", "y" => $flota_ventas),
	array("label" => "Revisar Flotas", "y" => $flota_revisar),
	array("label" => "Flota Posventa", "y" => $flota_posventa)
);

foreach ($arr_vh  as $row) {
	$vh = $row['vh'];
	$flota = $row['flota'];
	$conta = 0;
	foreach ($vh_data1 as $key) {
		if ($key['vh'] == $vh && $key['flota'] == $flota) {
			$conta++;
		}
	}
	$data_vh_rep[] = ['flota' => $flota, 'vh' => $vh, 'n' => $conta];
	//echo $vh." repetido ".$conta."<br>";
}
$arr_int[] = ['flota' => "", 'vh' => "", 'n' => ""];
$arr_ven[] = ['flota' => "", 'vh' => "", 'n' => ""];
$arr_rev[] = ['flota' => "", 'vh' => "", 'n' => ""];
$arr_pos[] = ['flota' => "", 'vh' => "", 'n' => ""];

$arr_int_aux[] = ['n' => ""];
$arr_ven_aux[] = ['n' => ""];
$arr_rev_aux[] = ['n' => ""];
$arr_pos_aux[] = ['n' => ""];
foreach ($data_vh_rep as $key) {
	if ($key['flota'] == 'Flota Integral') {
		$arr_int[] = ['flota' => $key['flota'], 'vh' => $key['vh'], 'n' => $key['n']];
		$arr_int_aux[] = ['n' => $key['n']];
	} elseif ($key['flota'] == 'Flota Ventas') {
		$arr_ven[] = ['flota' => $key['flota'], 'vh' => $key['vh'], 'n' => $key['n']];
		$arr_ven_aux[] = ['n' => $key['n']];
	} elseif ($key['flota'] == 'Revisar Flota') {
		$arr_rev[] = ['flota' => $key['flota'], 'vh' => $key['vh'], 'n' => $key['n']];
		$arr_rev_aux[] = ['n' => $key['n']];
	} elseif ($key['flota'] == 'Flota Posventa') {
		$arr_pos[] = ['flota' => $key['flota'], 'vh' => $key['vh'], 'n' => $key['n']];
		$arr_pos_aux[] = ['n' => $key['n']];
	}
}
if (isset($arr_int_aux) && isset($arr_int)) {
	array_multisort($arr_int_aux, SORT_DESC, $arr_int);
}
if (isset($arr_ven_aux) && isset($arr_ven)) {
	array_multisort($arr_ven_aux, SORT_DESC, $arr_ven);
}
if (isset($arr_rev_aux) && isset($arr_rev)) {
	array_multisort($arr_rev_aux, SORT_DESC, $arr_rev);
}
if (isset($arr_pos_aux) && isset($arr_pos)) {
	array_multisort($arr_pos_aux, SORT_DESC, $arr_pos);
}





/*Graficas de las flotas*/
foreach ($arr_int as $key) {
	$arr_graf_int[] = ['label' => $key['vh'], 'y' => $key['n']];
}
foreach ($arr_ven as $key) {
	$arr_graf_ven[] = ['label' => $key['vh'], 'y' => $key['n']];
}
foreach ($arr_rev as $key) {
	$arr_graf_rev[] = ['label' => $key['vh'], 'y' => $key['n']];
}
foreach ($arr_pos as $key) {
	$arr_graf_pos[] = ['label' => $key['vh'], 'y' => $key['n']];
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="<?= $base_url_style ?>styles/bootstrap.min.css">
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

	<style type="text/css">
		@media only screen and (min-width: 768px) {
			.tamano_letra {
				font-size: 25px;
			}
		}

		@media only screen and (min-width: 720px) {
			.tamano_letra {
				font-size: 25px;
			}
		}
	</style>
	<title>Informe Flotas</title>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
		<div class="container-fluid">
			<a class="navbar-brand" href="#">Informe Flotas</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarColor01">
				<ul class="navbar-nav me-auto">
					<li class="nav-item">
						<a class="nav-link active" href="#">
							<span class="visually-hidden">(current)</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= $base_url_comercial ?>">Intranet Comercial</a></li>
		<li class="breadcrumb-item active">Informe general de flotas</li>
	</ol>
	<br>
	<section class="container-fluid">
		<div class="row">
			<div class="col-md-9">
				<div class="card mb-3">
					<div class="card-body">
						<div align="center">
							<h4 class="card-title">Informe de Flotas CODIESEL SA.</h4>
						</div>
						<div align="center">
							<h5 class="card-title"><?= $sub_titulo ?></h5>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-3">
								<div class="card  mb-3" style="background-color: #FFDBFC;">
									<div class="card-body" align="center">
										<h4 class="card-title">Total Flotas Ventas</h4>
										<div class="row">
											<div class="col-md-3">
												<i class="fas fa-truck" style="font-size: 40px;"></i>
											</div>
											<div class="col-md-9">
												<div class="row" align="left">
													<div class="col-md-12"><strong style="font-size: 20px;" class="tamano_letra"><?= $flota_ventas ?></strong></div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p>Vehículo mas Vendido</p> <strong><?= $arr_ven[0]['vh'] ?></strong>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="card mb-3" style="background-color: #FFEFDB;">
									<div class="card-body" align="center">
										<h5 class="card-title">Total Flotas PosVenta</h5>
										<div class="row">
											<div class="col-md-3">
												<i class="fas fa-truck" style="font-size: 40px;"></i>
											</div>
											<div class="col-md-9">
												<div class="row" align="left">
													<div class="col-md-12">
														<strong style="font-size: 20px;" class="tamano_letra"><?= $flota_posventa ?></strong>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p>Vehículo mas Vendido</p> <strong><?= $arr_pos[0]['vh'] ?></strong>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="card mb-3" style="background-color: #E2FFDB;">
									<div class="card-body" align="center">
										<h4 class="card-title">Total Flota Integral</h4>
										<div class="row">
											<div class="col-md-3">
												<i class="fas fa-truck" style="font-size: 40px;"></i>
											</div>
											<div class="col-md-9">
												<div class="row" align="left">
													<div class="col-md-12"><strong style="font-size: 20px;" class="tamano_letra"><?= $flota_integral ?></strong></div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p>Vehículo mas Vendido</p> <strong><?= $arr_int[0]['vh'] ?></strong>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="card mb-3" style="background-color: #FFDBE4;">
									<div class="card-body" align="center">
										<h4 class="card-title">Total Flotas Revisar</h4>
										<div class="row">
											<div class="col-md-3">
												<i class="fas fa-truck" style="font-size: 40px;"></i>
											</div>
											<div class="col-md-9">
												<div class="row" align="left">
													<div class="col-md-12"><strong style="font-size: 20px;" class="tamano_letra"><?= $flota_revisar ?></strong></div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p>Vehículo mas Vendido</p> <strong><?= $arr_rev[0]['vh'] ?></strong>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div id="graf_flotas1" style="height: 370px; width: 100%;"></div>
							</div>
							<div class="col-md-6">
								<div id="graf_flotas2" style="height: 370px; width: 100%;"></div>
							</div>
						</div>
						<hr>

						<div class="row table-responsive" align="center">
							<h4>Tabla detalle</h4>
							<div class="row">
								<div class="col-md-3" align="left">
									<button class="btn btn-success btn-sm" onclick="toexcel();"><i class="far fa-file-excel"></i> Exportar a Excel</button>
								</div>
							</div>
							<br>
							<div class="row">
								<hr>
								<table class="table table-hover" id="tabla_gral">
									<thead class="">
										<tr style="font-size: 15px;">
											<th scope="col">Asesor</th>
											<th scope="col">Nit Cliente</th>
											<th scope="col">Cliente</th>
											<th scope="col">Flota</th>
											<th scope="col">Vehículo</th>
											<th scope="col">Placa</th>
											<th scope="col">Primera entrada taller</th>
											<th scope="col">Última entrada taller</th>
											<th scope="col">Fecha Venta</th>
											<th scope="col">Tipo Flota</th>
										</tr>
									</thead>
									<tbody>

										<?php foreach ($data_inf_gral as $key) { ?>
											<tr style="font-size: 13px;">
												<th scope="row"><?= $key['asesor'] ?></th>
												<td><?= $key['nit_cliente'] ?></td>
												<td><?= $key['cliente'] ?></td>
												<td><?= $key['flota'] ?></td>
												<td><?= $key['vh'] ?></td>
												<td><?= $key['placa'] ?></td>
												<td><?php
													if (isset($key['fecha_p_e_t'])) {
														echo $key['fecha_p_e_t']->format('d/m/Y');
													} else {
														echo "";
													}
													?></td>
												<td><?php
													if (isset($key['fecha_u_e_t'])) {
														echo $key['fecha_u_e_t']->format('d/m/Y');
													} else {
														echo "";
													}
													?></td>
												<td><?php
													if (isset($key['fecha_venta'])) {
														echo $key['fecha_venta']->format('d/m/Y');
													} else {
														echo "";
													}
													?></td>
												<td><?= $key['tipo_flota'] ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card border-primary mb-3" align="center">
					<div class="card-header">Filtros</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="card mb-3">
									<div class="card-body">
										<form method="POST" action="<?= $base_url ?>">
											<label>Tipo de Informe</label>
											<div class="row">
												<div class="col-md-10">
													<!-- <form method="POST" action=""> -->
													<select class="form-control form-control-sm select2" name="combo_tipo">
														<option value="general">General</option>
														<option value="agrupado">Agrupado Por Flota</option>
													</select>
												</div>
												<div class="col-md-2">
													<button type="submint" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="card mb-3">
									<div class="card-body">
										<form method="POST" action="<?= $base_url ?>">
											<label>Filtrar por Asesor</label>
											<div class="row">
												<div class="col-md-10">
													<select class="form-control form-control-sm select2" name="combo_asesor">
														<option value="">Seleccione un Asesor...</option>
														<?php foreach ($data_asesores as $key) { ?>
															<option value="<?= $key['nit'] ?>"><?= $key['nombres'] ?></option>
														<?php } ?>
													</select>
												</div>
												<div class="col-md-2">
													<button type="submint" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="card mb-3">
									<div class="card-body">
										<form method="POST" action="<?= $base_url ?>">
											<label>Filtrar tipo de Flota</label>
											<div class="row">
												<div class="col-md-10">
													<select class="form-control form-control-sm select2" name="combo_flota">
														<option value="">Seleccione una Flota...</option>
														<option value="Flota Integral">Flota Integral</option>
														<option value="Flota Ventas">Flota de Ventas</option>
														<option value="Flota PosVenta">Flota de PosVenta</option>
														<option value="Revisar Flota">Revisar Flota</option>
													</select>
												</div>
												<div class="col-md-2">
													<button type="submint" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="card mb-3">
									<div class="card-header">Fecha de venta</div>
									<div class="card-body">
										<form method="POST" action="<?= $base_url ?>">
											<div class="row">
												<div class="col-md-6">
													<label>Fecha Inicial</label>
													<input type="date" name="fecha_ini_ven" class="form-control form-control-sm">
												</div>
												<div class="col-md-6">
													<label>Fecha Final</label>
													<input type="date" name="fecha_fin_ven" class="form-control form-control-sm">
												</div>
											</div>
											<br>
											<div class="row">
												<button type="submint" class="btn btn-primary btn-sm" style="width: 100%"><i class="fas fa-search"></i></button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="card mb-3">
									<div class="card-header">Fecha Primera entrada al taller</div>
									<div class="card-body">
										<form method="POST" action="<?= $base_url ?>">
											<div class="row">
												<div class="col-md-6">
													<label>Fecha Inicial</label>
													<input type="date" name="fecha_ini_pent" class="form-control form-control-sm">
												</div>
												<div class="col-md-6">
													<label>Fecha Final</label>
													<input type="date" name="fecha_fin_pent" class="form-control form-control-sm">
												</div>
											</div>
											<br>
											<div class="row">
												<button type="submint" class="btn btn-primary btn-sm" style="width: 100%"><i class="fas fa-search"></i></button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="card mb-3">
									<div class="card-header">Fecha última entrada al taller</div>
									<div class="card-body">
										<form method="POST" action="<?= $base_url ?>">
											<div class="row">
												<div class="col-md-6">
													<label>Fecha Inicial</label>
													<input type="date" name="fecha_ini_uent" class="form-control form-control-sm">
												</div>
												<div class="col-md-6">
													<label>Fecha Final</label>
													<input type="date" name="fecha_fin_uent" class="form-control form-control-sm">
												</div>
											</div>
											<br>
											<div class="row">
												<button type="submint" class="btn btn-primary btn-sm" style="width: 100%"><i class="fas fa-search"></i></button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>


	</section>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('.select2').select2();
		});
	</script>

	<script>
		window.onload = function() {
			var chart = new CanvasJS.Chart("graf_flotas1", {
				title: {
					text: "Gráfica de flotas por Vehículo"
				},
				theme: "light2",
				animationEnabled: true,
				exportEnabled: true,
				toolTip: {
					shared: true,
					reversed: true
				},
				axisY: {
					title: "Cantidad",
					suffix: " #"
				},
				legend: {
					cursor: "pointer",
					itemclick: toggleDataSeries
				},
				data: [{
					type: "stackedColumn100",
					name: "Flota Integral",
					showInLegend: true,
					yValueFormatString: "",
					dataPoints: <?php echo json_encode($arr_graf_int, JSON_NUMERIC_CHECK); ?>
				}, {
					type: "stackedColumn100",
					name: "Flota Ventas",
					showInLegend: true,
					yValueFormatString: "",
					dataPoints: <?php echo json_encode($arr_graf_ven, JSON_NUMERIC_CHECK); ?>
				}, {
					type: "stackedColumn100",
					name: "Flota Posventa",
					showInLegend: true,
					yValueFormatString: "",
					dataPoints: <?php echo json_encode($arr_graf_pos, JSON_NUMERIC_CHECK); ?>
				}, {
					type: "stackedColumn100",
					name: "Revisar Flota",
					showInLegend: true,
					yValueFormatString: "",
					dataPoints: <?php echo json_encode($arr_graf_rev, JSON_NUMERIC_CHECK); ?>
				}]
			});

			chart.render();

			function toggleDataSeries(e) {
				if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
					e.dataSeries.visible = false;
				} else {
					e.dataSeries.visible = true;
				}
				e.chart.render();
			}
			var chart = new CanvasJS.Chart("graf_flotas2", {
				animationEnabled: true,
				title: {
					text: "Gráfica flotas"
				},
				subtitles: [{
					text: ""
				}],
				data: [{
					type: "pie",
					yValueFormatString: "#",
					indexLabel: "{label} ({y})",
					dataPoints: <?php echo json_encode($graf_flotas, JSON_NUMERIC_CHECK); ?>
				}]
			});
			chart.render();
		}
	</script>

	<script type="text/javascript">
		function load_general() {
			var tipo_inf = document.getElementById('tipo_inf').value;
			//alert(tipo_inf);
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					result.innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "<?= $base_url ?>index.php?tipo_inf=" + tipo_inf, true);
			xmlhttp.send();
		}
	</script>
	<script type="text/javascript">
		function toexcel() {
			$("#tabla_gral").table2excel({
				exclude: ".noExl",
				name: "Worksheet Name",
				filename: "Informe General Flotas CODIESEL", //do not include extension
				fileext: ".xlsx" // file extension
			});
		}
	</script>
</body>

</html>