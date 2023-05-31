<?php

$dataG = $dataGeneral->row();
/* Añadir ceros a la izquierda :D */
$number = $dataG->id_cotizacion;
$numberS = "$number";

$tamaño = (int) strlen($numberS);
if ($tamaño <= 4) {
	$length = 4;
} else {
	$length = $tamaño + 1;
}
$Id_Cotizacion = substr(str_repeat(0, $length) . $number, -$length);
$dataTelefono = '';
if ($dataG->telAsesor != "") {
	$telfAsesor = $dataG->telAsesor;
	$dataTelefono = '<a class="ws" target="_blank" href="https://api.whatsapp.com/send/?phone=57' . $telfAsesor . '&text=Buen%20d%C3%ADa%0AMe%20puedes%20ayudar%3F%0AGracias.&type=phone_number&app_absent=0"><img src="' . base_url() . 'public/whatsapp.png" width="20px" /></a>';
} else {
	$telfAsesor = '';
	$dataTelefono = '<a class="ws" target="_blank" href="https://api.whatsapp.com/send/?phone=57' . $telfAsesor . '&text=Buen%20d%C3%ADa%0AMe%20puedes%20ayudar%3F%0AGracias.&type=phone_number&app_absent=0"><img src="' . base_url() . 'public/whatsapp.png" width="20px" /></a>';
}

?>

<head>
	<style type="text/css">
		.trColor {
			background-color: #a6a6a6;
		}

		.tdColor {
			background-color: #dbdbdb;
		}
	</style>
</head>

<table border="0" width="100%" cellpadding="0" cellspacing="0" style="font-size: 12px ;">
	<tr>
		<td align="left">
			<table style="font-size: 14px ;">
				<tr>
					<td>
						<?= $dataG->NomBodega ?>
					</td>
				</tr>
				<tr>
					<td>Dirección:
						<?= $dataG->direccion ?>
					</td>
				</tr>
				<tr>
					<td>Telf: 607
						<?= $dataG->telefono ?>
					</td>
				</tr>
			</table>
		</td>
		<td align="right">
			<table style="font-size: 12px ;">
				<tr>
					<td align="right"><span align="left">Número cotización: </span>
						<?= $Id_Cotizacion ?>
					</td>
				</tr>
				<tr>
					<td align="right"><span align="left">Fecha cotización: </span>
						<?= $dataG->fecha_creacion ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table border="0" width="100%" cellpadding="0" cellspacing="0" style="font-size: 12px ;">
	<tr>
		<td width="100%" colspan="2" align="center">
			<table width="100%" border="1" style="border-collapse:collapse; font-size: 12px ;">
				<tr>
					<td width="100%" colspan="7" align="center" class="trColor">Información del asesor</td>
				</tr>
				<tr class="tdColor">
					<td width="35%" colspan="2" align="center">Asesor</td>
					<td width="35%" colspan="3" align="center">Correo asesor</td>
					<td width="15%" align="center" colspan="2">Telefono</td>
				</tr>
				<tr>
					<td align="center" colspan="2">
						<?= $dataG->asesor ?>
					</td>
					<td align="center" colspan="3">
						<?= $dataG->correo ?>
					</td>
					<td border="0" align="center" colspan="1">
						<?= $telfAsesor ?>
					</td>
					<td border="0" align="center" colspan="1">
						<?= $dataTelefono ?>
					</td>

				</tr>

			</table>
		</td>
	</tr>

</table>
<br />
<table border="0" width="100%" cellpadding="0" cellspacing="0" style="font-size: 12px ;">
	<tr>
		<td width="100%" colspan="2" align="center">
			<table width="100%" border="1" style="border-collapse:collapse;font-size: 12px ;">
				<tr>
					<td width="100%" colspan="6" align="center" class="trColor">Vehículo / Cliente</td>
				</tr>
				<tr class="tdColor">
					<td width="35%" align="center">CC o Nit</td>
					<td width="35%" align="center">Cliente</td>
					<td width="10%" align="center">Placa</td>
					<!-- <td  width="30%" align="center" >Descripción</td> -->
					<td colspan="2" width="35%" align="center">Modelo</td>
					<!-- <td  width="10%" align="center" >Kilometraje</td> -->
					<td width="10%" align="center">Revisión</td>
				</tr>
				<tr>
					<td align="center">
						<?= $dataG->nitCliente ?>
					</td>
					<td align="center">
						<?= $dataG->nombreCliente ?>
					</td>
					<td align="center">
						<?= $dataG->placa ?>
					</td>
					<!-- <td  align="center"><?= $dataG->descripcion ?></td> -->
					<td align="center" colspan="2">
						<?= $dataG->des_modelo ?>
					</td>
					<!-- <td  align="center"><?= $dataG->kilometraje_cliente ?></td> -->
					<td align="center">
						<?= $dataG->revision ?>
					</td>
				</tr>

			</table>
		</td>
	</tr>
</table>
<br />
<table width="100%" border="1" style="border-collapse:collapse;font-size: 12px ;">
	<tr>
		<td width="100%" colspan="7" align="center" class="trColor">Repuestos</td>
	</tr>
	<tr class="tdColor">
		<td width="" colspan="2" align="center">Descripcion</td>
		<td width="20%" colspan="2" align="center">Categoria</td>
		<td width="25%" colspan="3" align="center">Estado</td>
		<!-- <td width="15%" colspan="3" align="center">Valor</td> -->
	</tr>
	<?php
	$sumaR = 0;
	foreach ($dataRepuestos->result() as $dataR) {
		if ($dataR->estado == 0) {
			$estado = 'No autorizado';
		} elseif ($dataR->estado == 1) {
			$estado = 'Autorizado';
			$sumaR += $dataR->valor;
		}
		?>
		<tr>
			<td align="left" colspan="2">
				<?= ucfirst(strtolower($dataR->descripcion)) ?>
			</td>
			<td align="center" colspan="2">
				<?= ($dataR->categoria != NULL ? ucfirst(strtolower($dataR->categoria)) : '--') ?>
			</td>
			<td align="center" colspan="3">
				<?= $estado ?>
			</td>
			<!-- <td align="right" colspan="3">$<?= number_format($dataR->valor, 0, ',', '.') ?></td> -->
		</tr>
	<?php } ?>
	<!-- <tr bgcolor="#E1E1E1" align="right">
					<td colspan="4" align="right">SubTotal Repuestos</td>
					<td colspan="3" align="right">$<?= number_format($sumaR, 0, ',', '.') ?></td>
				</tr> -->
</table>
<br />
<table width="100%" border="1" style="border-collapse:collapse;font-size: 12px ;">
	<tr>
		<td width="100%" colspan="7" align="center" class="trColor">Mantenimiento</td>
	</tr>
	<tr class="tdColor">

		<td width="50%" colspan="3" align="center">Descripcion</td>
		<td width="25%" colspan="4" align="center">Estado</td>
		<!-- <td width="25%" colspan="3" align="center">Valor</td> -->
	</tr>
	<?php
	$sumaM = 0;
	$sumTiempo = 0;
	foreach ($dataMtto->result() as $dataM) {
		if ($dataM->estado == 0) {
			$estado2 = 'No autorizado';
		} elseif ($dataM->estado == 1) {
			$estado2 = 'Autorizado';
			$sumaM += $dataM->valor;
			$sumTiempo += $dataM->cant_horas;
		}
		?>
		<tr>
		x
			<td colspan="3" align="left">
				<?= ucfirst(strtolower($dataM->descripcion)) ?>
			</td>
			<td colspan="4" align="center">
				<?= $estado2 ?>
			</td>
			<!-- <td colspan="3" align="right">$<?= number_format($dataM->valor, 0, ',', '.') ?></td> -->
		</tr>
	<?php } ?>
</table>
<br />
<table width="100%" border="1" style="border-collapse:collapse;font-size: 12px ;">
	<!-- <tr bgcolor="#E1E1E1" align="right">
					<td colspan="4" align="right">SubTotal Mtto</td>
					<td colspan="3" align="right">$<?= number_format($sumaM, 0, ',', '.') ?></td>
				</tr> -->
	<tr bgcolor="#999999" align="right">
		<td colspan="4" align="right">Tiempo estimado en el taller:</td>
		<td colspan="3" align="right"><?= $sumTiempo ?> horas </td>
	</tr>
	<tr bgcolor="#999999" align="right">
		<td colspan="4" align="right">Total cotización</td>
		<td colspan="3" align="right">$
			<?= number_format(($sumaM + $sumaR), 0, ',', '.') ?>
		</td>
	</tr>
</table>

<p style="font-size: 12px ;"><strong>Observaciones: </strong>
	<?= $dataG->observaciones ?>
</p>