<!DOCTYPE html>
<html>
<head>
	<title></title>
	
	<style type="text/css">
	body{
		color: black;
		font-family: Arial;
	}
	strong{
		color: black;
		font-family: Arial;
	}
	th{
		color: black;
		font-family: Arial;
	}
	td{
		color: black;
		font-family: Arial;
	}
	h3{
		color: black;
		font-family: Arial;
	}
	h4{
		color: black;
		font-family: Arial;
	}
	h2{
		color: black;
		font-family: Arial;
	}
	p{
		font-family: Arial;
		margin: 0px;
	}
	td{
		font-size: 10px;
	}
</style>
</head>
<body style="background-color: #fff;">
	<div class="container-fluid">
		<table class="table">
			<tbody>
				<tr>
					<td><img style="width: 19%;" src="<?=base_url()?>media/logo_codiesel_sinfondo.png"></td>
					<td>
						<div align="center">
							<p style="font-size: 19px; text-align: center;">Recibo salida de TOT</p>
							<p style="text-align: center;">CODIESEL SA</p>
							<p style="text-align: center;">Kilometro 7 via Girón</p>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		
		<div class="row">
			<?php foreach ($info_tot->result() as $key) {?>
				<table class="table" border=1 cellspacing=0 cellpadding=2 style="width: 100%;">
					<tbody>
						<tr align="left" style="text-align: left;">
							<th scope="row">Autoriza</th>
							<td><?=$key->nombres?></td>
							<th scope="row">Numero de Orden</th>
							<td><?=$key->orden?></td>
							<th scope="row">Placa</th>
							<td><?=$key->placa?></td>
							<th scope="row">Fecha</th>
							<td><?=$key->fecha_salida?></td>
						</tr>
						<tr>
							<th scope="row">Proveedor</th>
							<td><?=$key->proveedor?></td>
							<th scope="row">Vehiculo</th>
							<td><?=$key->descripcion?></td>
							<th scope="row" colspan="2">Aseguradora</th>
							<td colspan="2"><?=$key->aseguradora?></td>
						</tr>
					</tbody>
				</table>
				<br>
				<p>Contiene</p>
				<table class="table" border=1 cellspacing=0 cellpadding=2 style="width: 100%;">
					<tbody>
						<tr align="left" style="text-align: left;">
							<th scope="row"><?=$key->contenido?></th>
						</tr>
					</tbody>
				</table>
			<?php } ?>
			<div align="center">
				<br>
				<p style="text-align: center;">____________________________________</p>
				<p style="text-align: center;">Firma del empleado</p>
			</div>
		</div>
	</div>
	<p style="text-align: center;">Original</p>
	<hr>
	<div class="container-fluid">
		<table class="table">
			<tbody>
				<tr>
					<td><img style="width: 19%;" src="<?=base_url()?>media/logo_codiesel_sinfondo.png"></td>
					<td>
						<div class="col-md-9" align="center">
							<p style="font-size: 19px; text-align: center;">Recibo salida de TOT</p>
							<p style="text-align: center;">CODIESEL SA</p>
							<p style="text-align: center;">Kilometro 7 via Girón</p>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		
		<div class="row">
			<?php foreach ($info_tot->result() as $key) {?>
				<table class="table" border=1 cellspacing=0 cellpadding=2 style="width: 100%;">
					<tbody>
						<tr align="left" style="text-align: left;">
							<th scope="row">Autoriza</th>
							<td><?=$key->nombres?></td>
							<th scope="row">Numero de Orden</th>
							<td><?=$key->orden?></td>
							<th scope="row">Placa</th>
							<td><?=$key->placa?></td>
							<th scope="row">Fecha</th>
							<td><?=$key->fecha_salida?></td>
						</tr>
						<tr>
							<th scope="row">Proveedor</th>
							<td><?=$key->proveedor?></td>
							<th scope="row">Vehiculo</th>
							<td><?=$key->descripcion?></td>
							<th scope="row" colspan="2">Aseguradora</th>
							<td colspan="2"><?=$key->aseguradora?></td>
						</tr>
					</tbody>
				</table>
				<br>
				<p>Contiene</p>
				<table class="table" border=1 cellspacing=0 cellpadding=2 style="width: 100%;">
					<tbody>
						<tr align="left" style="text-align: left;">
							<th scope="row"><?=$key->contenido?></th>
						</tr>
					</tbody>
				</table>
			<?php } ?>
			<div align="center">
				<br>
				<p style="text-align: center;">____________________________________</p>
				<p style="text-align: center;">Firma del empleado</p>
			</div>
		</div>
	</div>
	<p style="text-align: center;">Copia</p>
</body>
</html>