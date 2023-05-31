<?php

$dataG = $dataGeneral->row();
/* Añadir ceros a la izquierda :D */
/* $number = $dataG->id;
$numberS = "$number";

$tamaño = (int) strlen($numberS);
if ($tamaño <= 4) {
	$length = 4;
} else {
	$length = $tamaño + 1;
}
$Id_Cotizacion = substr(str_repeat(0, $length) . $number, -$length); */
/* Valor Hora */
$valorHora = 65000;

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

<table border="0" width="100%" cellpadding="0" cellspacing="0" style="font-family: Helvetica, sans-serif; font-size: 12px ;overflow: wrap;">
	<tr>
		<td align="left">
			<table style="font-family: Helvetica, sans-serif; font-size: 13px ;overflow: wrap;">
				<tr>
					<td>COMPAÑIA AUTOMOTRIZ DIESEL S.A.</td>
				</tr>
				<tr>
					<td>Dirección: Km 7 vía Girón – Junto al Palenque</td>
				</tr>
				<tr>
					<td>Telf: 607 6380123</td>
				</tr>
			</table>
		</td>
		<td align="right">
			<table style="font-family: Helvetica, sans-serif; font-size: 12px ;overflow: wrap;">
				<tr>
					<td align="right"><span align="left">Número cotización: </span><?= $dataG->id ?></td>
				</tr>
				<tr>
					<td align="right"><span align="left">Fecha cotización: </span><?= $dataG->fecha_creacion ?></td>
				</tr>
		</td>
	</tr>

</table>
</td>

</tr>

<tr>
	<td>&nbsp;</td>
</tr>

<tr>
	<td width="100%" colspan="2" align="center">
		<table width="100%" border="1" style="border-collapse:collapse;font-family: Helvetica, sans-serif; font-size: 12px ;overflow: wrap;">
			<tr>
				<td width="100%" colspan="3" align="center" class="trColor">Vehículo / Cliente</td>
			</tr>
			<tr class="tdColor">
				<td width="10%" align="center">Placa</td>
				<td width="35%" align="center">Modelo</td>
				<td width="10%" align="center">Kilometraje</td>
			</tr>
			<tr>
				<td align="center"><?= $dataG->placa_vh ?></td>
				<td align="center"><?= $dataG->desc_modelo_vh ?></td>
				<td align="center"><?= $dataG->km_actual ?></td>
			</tr>

		</table>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>

<tr>
	<td width="100%" colspan="2" align="center">
		<table width="100%" border="1" style="border-collapse:collapse;font-family: Helvetica, sans-serif; font-size: 12px ;overflow: wrap;">
			<tr>
				<td colspan="9" width="100%" align="center" class="trColor">Repuestos</td>
			</tr>
			<tr class="tdColor">
				<td width="15%" align="center">Código</td>
				<td width="30%" align="center">Descripción</td>
				<td width="5%" align="center">Cant</td>
				<td width="5%" align="center">% Desc</td>
				<td width="5%" align="center">Cant Cto</td>
				<td width="10%" align="center">Autorizado</td>
				<td width="10%" align="center">Valor Unitario</td>
				<td width="10%" align="center">Valor Total</td>
				<td width="10%" align="center">Valor Total Desc</td>

			</tr>
			<?php
			$sumaR = 0;
			$sumaR_Iva = 0;
			$sumaR_desc = 0;
			$totalDescuento = 0;
			$cant_valor = 0;
			foreach ($dataRepuestos->result() as $dataR) {
				$autorizado = "";
				$cant_valor = ($dataR->cantidad * $dataR->valor_unidad);
				if ($dataR->autorizado == 1) {
					$autorizado = "SI";
					$sumaR_desc += $dataR->valor_total;
					
					$sumaR += ($dataR->cantidad * $dataR->valor_unidad);
					$totalDescuento_unidad = $cant_valor - ceil(($dataR->cantidad * $dataR->valor_unidad) * ($dataR->descuento / 100));
					$totalDescuento += ceil(($dataR->cantidad * $dataR->valor_unidad) * ($dataR->descuento / 100));
				} else {
					$autorizado = "NO";
				}

				$cant_contrato = ($dataR->cant_contrato != 0) ? $dataR->cant_contrato : 'N/A C';
			?>
				<tr>
					<td><?= $dataR->codigo ?></td>
					<td align="left"><?= $dataR->descripcion ?></td>
					<td><?= $dataR->cantidad ?></td>
					<td><?= $dataR->descuento ?></td>
					<td><?= $cant_contrato ?></td>
					<td><?= $autorizado ?></td>
					<td align="right">$<?= number_format($dataR->valor_unidad, 0, ',', '.') ?></td>
					<td align="right">$<?= number_format($cant_valor, 0, ',', '.') ?></td>
					<td align="right">$<?= number_format($totalDescuento_unidad, 0, ',', '.') ?></td>
				</tr>
			<?php }
			$subTotalRepuestos = $sumaR_desc-$totalDescuento;
			$sumaR_Iva = $sumaR + ceil($sumaR * (0.19)); ?>
			<tr bgcolor="#E1E1E1" align="right">
				<td colspan="4" align="right">Subtotal Repuestos</td>
				<td colspan="5" align="right">$<?= number_format($sumaR, 0, ',', '.')  ?></td>
			</tr>
			<tr bgcolor="#E1E1E1" align="right">
				<td colspan="4" align="right">Subtotal Descuentos</td>
				<td colspan="5" align="right">- $<?= number_format($totalDescuento, 0, ',', '.')  ?></td>
			</tr>
			<tr bgcolor="#E1E1E1" align="right">
				<td colspan="4" align="right">Subtotal Repuestos</td>
				<td colspan="5" align="right">$<?= number_format(($subTotalRepuestos), 0, ',', '.')  ?></td>
			</tr>
			<tr>
				<td width="100%" colspan="9" align="center" class="trColor">Mano de Obra</td>
			</tr>
			<tr class="tdColor">

				<td colspan="3" align="center">Operación</td>
				<td colspan="2" align="center">Cantidad Horas</td>
				<td colspan="2" align="center">Autorizado</td>
				<td colspan="1" align="center">Valor*Hora</td>
				<td colspan="1" align="center">Valor Total</td>
			</tr>
			<?php
			$sumaM = 0;
			foreach ($dataManoObra->result() as $dataM) {
				$autorizadoMano = "";
				if ($dataM->autorizado == 1) {
					$autorizadoMano = "SI";
					$sumaM += $dataM->valor_total;
				} else {
					$autorizadoMano = "NO";
				}


			?>
				<tr>
					<td colspan="3" align="left"><?= $dataM->operacion ?></td>
					<td colspan="2" align="center"><?= $dataM->cant_horas ?></td>
					<td colspan="2" align="center"><?= $autorizadoMano ?></td>
					<td colspan="1" align="center">$<?= number_format($valorHora, 0, ',', '.') ?></td>
					<td colspan="1" align="right">$<?= number_format($dataM->valor_total, 0, ',', '.')  ?></td>
				</tr>
			<?php } ?>
			<tr bgcolor="#E1E1E1" align="right">
				<td colspan="4" align="right">Subtotal Mano de Obra</td>
				<td colspan="5" align="right">$<?= number_format($sumaM, 0, ',', '.') ?></td>
			</tr>
			<tr>
				<td width="100%" colspan="9" align="center" class="trColor">ToT</td>
			</tr>
			<tr class="tdColor">
				<td colspan="3" align="center">Operación</td>
				<td colspan="3" align="center">Autorizado</td>
				<td colspan="3" align="center">Valor</td>
			</tr>
			<?php
			$sumaT = 0;
			foreach ($dataToT->result() as $dataT) {
				$autorizadoToT = "";
				if ($dataT->autorizado == 1) {
					$autorizadoToT = "SI";
					$sumaT += $dataT->valor_total;
				} else {
					$autorizadoToT = "NO";
				}

			?>
				<tr>
					<td colspan="3" align="left"><?= $dataT->operacion ?></td>
					<td colspan="3" align="center"><?= $autorizadoToT ?></td>
					<td colspan="3" align="right">$<?= number_format($dataT->valor_total, 0, ',', '.') ?></td>
				</tr>
			<?php } ?>
			<tr bgcolor="#E1E1E1" align="right">
				<td colspan="4" align="right">Subtotal ToT</td>
				<td colspan="5" align="right">$<?= number_format($sumaT, 0, ',', '.')  ?></td>
			</tr>
		</table>
	</td>
</tr>
<br>
<tr>
	<td colspan="2">
		<table width="100%" border="1" style="border-collapse:collapse;font-family: Helvetica, sans-serif; font-size: 12px ;overflow: wrap;">
			<tr bgcolor="#E1E1E1" align="right">
				<td colspan="4" align="right">Subtotal repuestos</td>
				<td colspan="5" align="right">$<?= number_format($subTotalRepuestos, 0, ',', '.')  ?></td>
			</tr>
			<tr bgcolor="#E1E1E1" align="right">
				<td colspan="4" align="right">Subtotal Mano de Obra</td>
				<td colspan="5" align="right">$<?= number_format($sumaM, 0, ',', '.') ?></td>
			</tr>
			<tr bgcolor="#E1E1E1" align="right">
				<td colspan="4" align="right">Subtotal ToT</td>
				<td colspan="5" align="right">$<?= number_format($sumaT, 0, ',', '.')  ?></td>
			</tr>
			<?=
			$sub_total = ceil($subTotalRepuestos + $sumaM + $sumaT);
			$iva = ceil($sub_total * 0.19);
			$totalCotizacion = $sub_total  + $iva;
			?>


			<tr class="trColor" bgcolor="#E1E1E1" align="right">
				<td colspan="4" align="right">Subtotal</td>
				<td colspan="5" align="right">$<?= number_format($sub_total, 0, ',', '.') ?></td>
			</tr>

			<tr bgcolor="#E1E1E1" align="right">
				<td colspan="4" align="right">IVA</td>
				<td colspan="5" align="right">$<?= number_format($iva, 0, ',', '.') ?></td>
			</tr>

			<tr class="trColor" bgcolor="#E1E1E1" align="right">
				<td colspan="4" align="right">Total</td>
				<td colspan="5" align="right">$<?= number_format($totalCotizacion, 0, ',', '.') ?></td>
			</tr>
		</table>
	</td>
</tr>

</table>

<p style="font-size: 14px ;"><strong>Observaciones: </strong><?= $dataG->observacion ?></p>