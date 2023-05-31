<!DOCTYPE html>
<html>
<head>
	<title>test ws</title>
</head>
<body>

	<div>
		<h1 align="center">TEST WEBSERVISE</h1>

		<h3>Ordenes</h3>
		<form method="POST" action="<?=base_url()?>webservice/get_info_ordenes_taller">
			<label>Ingrese el numero de la Orden</label>
			<input type="text" name="orden">
			<button>Consumir</button>
		</form>

		<h3>Cotizaciones</h3>
		<form method="POST" action="<?=base_url()?>webservice/get_info_cotizaciones_taller">
			<label>Ingrese el numero de la Orden</label>
			<input type="text" name="orden">
			<button>Consumir</button>
		</form>
	</div>

</body>
</html>