<?php 
//Ruta del proyecto

$base_url = "http://intranet.codiesel.co/postventa/info_flota/"; 
$base_url_style = "http://intranet.codiesel.co/codiesel/Informe_flotas/"; //enlace para los estilos
$base_url_anterior = "http://intranet.codiesel.co/postventa/index.php?key=fb2df33efde00f720e98166f62b44d15";
$base_url_comercial = "http://intranet.codiesel.co/postventa/index.php"; 

/* $base_url = "http://localhost/postventa/info_flota/";
$base_url_style = "http://intranet.codiesel.co/codiesel/Informe_flotas/"; //enlace para los estilos
$base_url_anterior = "http://localhost/postventa/info_flota/index";
$base_url_comercial = "http://localhost/postventa/home"; */

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

$sql_inf_agrupado = "select y.vendedor,y.asesor, y.flota,y.nit_flota, y.vehiculo,
y.primer_Entrada_Taller, y.ultima_entrada_taller, y.fecha_venta,
COUNT (fg.descripcion) as unidades_vendidas
from
(select x.asesor, x.flota, x.descripcion as vehiculo, x.primer_Entrada_Taller, x.ultima_entrada_taller, x.fecha_venta,x.vendedor,x.nit_flota
  from (select c.*,
               row_number() over (
                   partition by c.flota
                       order by isnull(fecha_venta,'1900-01-01') desc) as rn
          from v_flotas_gestionar c) x
 where x.rn = 1
)
y
inner join v_flotas_gestionar fg on y.flota=fg.flota
where y.nit_flota not in (select nit_flota from swcrm_clientes_flotas_interes)
group by y.asesor, y.flota, y.vehiculo,y.nit_flota,
y.primer_Entrada_Taller, y.ultima_entrada_taller, y.fecha_venta,y.vendedor
order by y.flota";

$result_inf_agrupado = sqlsrv_query($link, $sql_inf_agrupado, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
while ($row = sqlsrv_fetch_array($result_inf_agrupado)) {
	$data_Informe[] = ['asesor' => $row['asesor'],'flota'=>$row['flota'],'vh'=>$row['vehiculo'],'pet'=>$row['primer_Entrada_Taller'],'uta'=>$row['ultima_entrada_taller'],'fecha'=>$row['fecha_venta'],'und_vend'=>$row['unidades_vendidas'],'vendedor'=>$row['vendedor'],'nit_flota'=>$row['nit_flota']];
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="<?=$base_url_style?>styles/bootstrap.min.css">
	
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	
	<style type="text/css">
		@media only screen and (min-width: 768px) {
			.tamano_letra{
				font-size: 25px;
			}
		}
		@media only screen and (min-width: 720px) {
			.tamano_letra{
				font-size: 25px;
			}
		}
		.select2-container--open .select2-dropdown {
		  z-index: 1070;
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
		<li class="breadcrumb-item"><a href="<?=$base_url_comercial?>">Intranet Comercial</a></li>
		<li class="breadcrumb-item"><a href="<?=$base_url_anterior?>">Informe general de flotas</a></li>
		<li class="breadcrumb-item active">Informe agrupado de flotas</li>
	</ol>
	<br>
	<section class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card border-light mb-3">
				  <div class="card-body">
				    <div align="center"><h4 class="card-title">Informe de Flotas Agrupado</h4>
				    <hr>					
				    <div class="table-responsive">
						<div class="row">
										<div class="col-md-3" align="left">
											<button class="btn btn-success btn-sm" onclick="toexcel();"><i class="far fa-file-excel"></i> Exportar a Excel</button>
										</div>
									</div>
									<br>
									<div class="row">
										<table class="table table-hover" style="font-size: 13px;" id="inf_agrupado">
											<thead>
												<tr align="center">
													<th scope="col">Asesor</th>
													<th scope="col">Flota</th>
													<th scope="col">Vehículo</th>
													<th scope="col">Primera Entrada al Taller</th>
													<th scope="col">Ultima Entrada al Taller</th>
													<th scope="col">Fecha de Venta</th>
													<th scope="col">Unidades Vendidas</th>
													<th scope="col">Gestionar</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($data_Informe as $key) { ?>
													<tr align="center">
														<th scope="row" align="left"><?=$key['asesor']?></th>
														<td><?=$key['flota']?></td>
														<td><?=$key['vh']?></td>
														<td><?php 
														if (isset($key['pet'])) {
															echo $key['pet']->format('d/m/Y');
														}else{
															echo "";
														}
													?></td>
													<td><?php 
													if (isset($key['uet'])) {
														echo $key['uet']->format('d/m/Y');
													}else{
														echo "";
													}
												?></td>
												<td><?php 
												if (isset($key['fecha'])) {
													echo $key['fecha']->format('d/m/Y');
												}else{
													echo "";
												}
											?></td>
											<td><?=$key['und_vend']?></td>
											<td><a href="#" class="btn btn-sm btn-success" onclick="openmodal('<?=$key['vendedor']?>','<?=$key['nit_flota']?>')"><i class="fas fa-plus-circle"></i></a></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
				    </div>
				  </div>
				</div>
			</div>
			</div>


		</section>

		<!-- Modal Neg-->
		<div class="modal fade" id="moda_neg" tabindex="1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body" id="contenido">

		      </div>
		    </div>
		  </div>
		</div>

		
		<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.select2').select2();
			});
			//CalcularCuota  //SelVehiculo
			$(document).on('change', '#combo_vh', function () {
				calcularCuota();
			});
			$(document).on('keyup', '#num_cuotas', function () {
				calcularCuota();
			});
			function calcularCuota(){
				var SelVehiculo = $("#combo_vh option:selected").val();
				//var ValorPeritajeOcul = $("#ValorPeritajeOcul").val();
				var ValorPeritajeOcul = 0;
				var NroCuotas = $("#num_cuotas").val();
			
				// Formula excel PAGO  con el mes 0 inicial
				//Cuota = (Monto * (%MV x (1 + %MV) ^ n)) / ((1 + %MV) ^ n) - 1)
				//=+PAGO(1,5%;48;(-R9+G3);;0)
				// =-PAGO(B4;C4;D4;;0)

				var VPresente = SelVehiculo; // A calcular vlr vehiculo nuevo
				var VFuturo = ValorPeritajeOcul;  // vlr Retoma
				var residuo =   VPresente-VFuturo;
		
				var D4=residuo;
				var B4=0.015;  // Taza del 1,5
				var C4=NroCuotas;   // periodo cuota meses , 4 años
			
				var formulaCompl= (D4*(B4*(1+B4) **C4))/((1+B4) ** C4-1);
								
				$("#val_cuotas").val(formatNumber(formulaCompl));
			}
			function formatNumber(num) {
				if (!num || num == 'NaN') return '-';
				if (num == 'Infinity') return '&#x221e;';
				num = num.toString().replace(/\$|\,/g, '');
				if (isNaN(num))
					num = "0";
				sign = (num == (num = Math.abs(num)));
				num = Math.floor(num * 100 + 0.50000000001);
				cents = num % 100;
				num = Math.floor(num / 100).toString();
				if (cents < 10)
					cents = "0" + cents;
				for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3) ; i++)
					num = num.substring(0, num.length - (4 * i + 3)) + '.' + num.substring(num.length - (4 * i + 3));
				return (((sign) ? '' : '-') + num + ',' + cents);
			}
			function openmodal(vendedor,nit_flota) {
				var result = document.getElementById('contenido');
				$('#moda_neg').modal('show');
				var xmlhttp;
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						$(document).ready(function(){
							$('.select2').select2();
						});
						result.innerHTML = xmlhttp.responseText;
						
					}
				}
				xmlhttp.open("GET", "<?=$base_url?>modal?vendedor="+vendedor+"&nit_flota="+nit_flota, true);
				xmlhttp.send();
			}
			function iniciarNegocio() {
				var nitCliente = document.getElementById('nit_cli').value;
				var asesor = document.getElementById('id_usu').value;

				var xmlhttp;

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				var url = "<?=$base_url?>nuevoNegocio";
    			var params = "iDCliente="+nitCliente + "&iTipoTabla="+3 + "&iFuente="+600 + "&asesor="+asesor;
				xmlhttp.open("POST", url, true);

				xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				
				//xmlhttp.responseType = 'json';
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						var res = xmlhttp.responseText;
						if (res == 1) {
							Swal.fire({
								title: 'Exito!',
								text: 'Negocio creado exitosamente',
								icon: 'success',
								confirmButtonText: 'Ok'
							}).then((result) => {
								if (result.isConfirmed) {
									document.location.reload();
								}
							});
						} else {
							Swal.fire({
								title: 'Advertencia!',
								text: 'El cliente ya tiene un negocio sin terminar con el asesor ' + res,
								icon: 'warning',
								confirmButtonText: 'Ok'
							});
						}						
					}
				}				
				xmlhttp.send(params);
			}
			function clienteNoInteresado() {
				var nitCliente = document.getElementById('nit_cli').value;

				var xmlhttp;
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				var url = "<?=$base_url?>noInteresado";
    			var params = "iDCliente="+nitCliente;
				xmlhttp.open("POST", url, true);

				xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
								
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						var res = xmlhttp.responseText;
						if (res == 1) {
							Swal.fire({
								title: 'Exito!',
								text: 'Actualizado exitosamente',
								icon: 'success',
								confirmButtonText: 'Ok'
							}).then((result) => {
								if (result.isConfirmed) {
									document.location.reload();
								}
							});
						} else {
							Swal.fire({
								title: 'Advertencia!',
								text: 'Error ' + res,
								icon: 'error',
								confirmButtonText: 'Ok'
							});
						}						
					}
				}				
				xmlhttp.send(params);
			}
		</script>
		<script type="text/javascript">
			function toexcel() {
				$("#inf_agrupado").table2excel({
					exclude:".noExl",
					name:"Worksheet Name",
			    filename:"Informe Agrupado Flotas CODIESEL",//do not include extension
			    fileext:".xlsx" // file extension
			});
			}
		</script>

</body>
</html>