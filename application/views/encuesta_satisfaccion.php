<!DOCTYPE html>
<html>
<head>
	<title>Encuesta de satisfaccion - CODIESEL</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<style type="text/css">
		@media only screen and (min-width: 1366px) {
			#cuerpo{
				background: url(<?=base_url()?>/media/img/fondo_encuesta.png);
				background-repeat: no-repeat;
				background-size: cover;
				margin-top: 150px;
			}
 			#contenido{
 				background: rgba(255, 255, 255, 0.5)!important;		
 			}
		}
		@media only screen and (min-width: 414px) {
			body{
				background-color: #E7E7E7;
				--background-repeat: no-repeat;
				--background-size: cover;
			}
 			#contenido{
 				margin-top: 30px;
 				background: rgba(255, 255, 255, 0.5)!important;
 			}
		}
		@media only screen and (min-width: 360px) {
			body{
				background-color: #E7E7E7;
				--background-repeat: no-repeat;
				--background-size: cover;
			}
 			#contenido{
 				margin-top: 10px;
 				background: rgba(255, 255, 255, 0.5)!important;
 			}
		}
		@media only screen and (min-width: 320px) {
			body{
				background-color: #E7E7E7;
				--background-repeat: no-repeat;
				--background-size: cover;
			}
 			#contenido{
 				margin-top: 10px;
 				background: rgba(255, 255, 255, 0.5)!important;
 			}
		}
	</style>
</head>
<body id="cuerpo">
	<?php if ($this->input->get('data') == 0) { ?>
	<div class="container">
		<div class="card" id="contenido">
		  <div class="card-body">
		  	<h1 align="center">Gracias por contestar la encuesta</h1>
		  </div>
		</div>
	</div>
<?php }elseif($this->input->get('data') != 0 && $data != 404){ ?>
	<div class="container">
		<div class="card" id="contenido">
		  <div class="card-body">
		    <h1 align="center">Encuesta de satisfacción CODIESEL</h1>
		    <hr>
		
		    <p style="font-style: oblique;font-size: 12px;">A continuación  elige una respuesta entre 1 y 10 donde 1 es muy insatisfecho y 10 muy satisfecho.</p>
		    <div class="container">
		    	<form method="POST" action="<?=base_url()?>encuesta/resp_satisf">
		    		<input type="hidden" name="n_orden" value="<?=$this->input->get('data')?>">
		    		<?php foreach ($preguntas->result() as $key) {?>
			    	 <div class="form-group">
					    <label for="exampleInputPassword1"><?=$key->pregunta?></label>
					    <?php if ($key->tipo == "1-10") {?>
		    				 	<select class="form-control form-control-sm" id="<?=$key->id?>" name="<?=$key->id?>" required>
		    				 		<option value="">Seleccione un valor</option>
		    				 		<option value="1">1</option>
		    				 		<option value="2">2</option>
		    				 		<option value="3">3</option>
		    				 		<option value="4">4</option>
		    				 		<option value="5">5</option>
		    				 		<option value="6">6</option>
		    				 		<option value="7">7</option>
		    				 		<option value="8">8</option>
		    				 		<option value="9">9</option>
		    				 		<option value="10">10</option>
		    				 	</select>
		    			<?php }elseif($key->tipo == "sn"){ ?>
		    				<select class="form-control form-control-sm" name="<?=$key->id?>" id="<?=$key->id?>" required>
		    				 		<option value="">Seleccione una opción</option>
		    				 		<option value="SI">SI</option>
		    				 		<option value="NO">NO</option>
		    				 	</select>
		    			<?php }elseif ($key->tipo == "op") {?>
		    				<td>
						  	<textarea id="<?=$key->id?>" name="<?=$key->id?>" class="form-control form-control-sm"></textarea>
						  </td>
		    			<?php 
		    				}
		    			}
		    			?>
					  </div>
					<div align="center"><button type="submit" class="btn btn-warning btn-lg">Enviar Respuestas</button></div>
				</form>
		    </div>
		  </div>
		</div>
	</div>
<?php }elseif($this->input->get('data') == "error"){ ?>
	<div class="container">
		<div class="card" id="contenido">
		  <div class="card-body">
		  	<h1 align="center">Se ha producido un error</h1>
		  </div>
		</div>
	</div>
<?php }elseif ($data == "404" || $data == "403") {
	?>
	<div class="container">
		<div class="card" id="contenido">
		  <div class="card-body">
		  	<h1 align="center">ENCUESTA NO EXISTE O HA CADUCADO</h1>
		  </div>
		</div>
	</div>
	<?php 
		}
	 ?>
</body>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
</html>
