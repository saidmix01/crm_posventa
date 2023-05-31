<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Post Venta</title>
	<!-- Le dice al navegador que la web es responsiva -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Estilos Font Awesome -->
	<link rel="stylesheet" href="<?=base_url()?>plugins/fontawesome-free/css/all.min.css">
    <!-- Pack de Iconos Ionicons -->
	  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	  <!-- Estilos Tempusdominus Bootstrap 4 -->
	  <link rel="stylesheet" href="<?=base_url()?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	  <!-- Theme style -->
	  <link rel="stylesheet" href="<?=base_url()?>dist/css/adminlte.min.css">
	  <!-- overlayScrollbars -->
	  <link rel="stylesheet" href="<?=base_url()?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	  <!-- Google Font: Source Sans Pro -->
	  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	  <link rel="shortcut icon" href="<?=base_url()?>media/logo/logo_codiesel_sinfondo.png" />
	  <!-- libreria del select2 -->
	  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
	    <!-- SweetAlert2 -->
  	<link rel="stylesheet" href="<?=base_url()?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  	    <!-- Toastr -->
  	<link rel="stylesheet" href="<?=base_url()?>plugins/toastr/toastr.min.css">
	  
</head>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<!-- Navbar -->
	  <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: #2F3C4F;">
	    <!-- Left navbar links -->
	    <ul class="navbar-nav">
	      <li class="nav-item">
	        <a class="nav-link" data-widget="pushmenu" style="color: #fff;" href="#"><i class="fas fa-bars"></i></a>
	      </li>
	      <li class="nav-item d-none d-sm-inline-block">
	        <a href="<?=base_url()?>login/iniciar" style="color: #fff;" class="nav-link"><i class="fas fa-home"></i>&nbsp;&nbsp; Inicio</a>
	      </li>
	    </ul>
	    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <img src="<?=base_url()?>media/img/user-img.png" alt="User Avatar" class="img-size-50 mr-3 img-circle" style="position: relative; left: 25px; top: 0px; height: 35px; width: 35px;">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" style="color: #fff;">
          <?php 
	          foreach ($userdata->result() as $key) {
	       ?>
	       <?=$key->nombres?>
	       
        </a>
        	<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
	          <span class="dropdown-item dropdown-header"><?=$key->nombres?></span>
	          <?php 
		       	}
		        ?>
	          <div class="dropdown-divider" style=""></div>
	          <a href="#" class="dropdown-item" data-toggle="modal" data-target="#pass-modal2">
	            <i class="fas fa-key mr-2"></i>Cambiar Contraseña
	          </a>
	          <div class="dropdown-divider"></div>
	          <a href="#" class="dropdown-item" data-toggle="modal" data-target="#logoutModal">
	            <i class="fas far fa-sign-out-alt"></i> Cerrar Sesion
	          </a>
	        </div>
	      </li>
	    </ul>

	    
	  </nav>
	  <!-- /.navbar -->

	  <!-- Main Sidebar Container -->
	  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #3D3D3D; ">
	    <!-- Brand Logo -->
	    <br>

	    <!-- Sidebar -->
	    <div class="sidebar">
	      <!-- Sidebar user panel (optional) -->
	      <div class="">
	        <div class="image">
	          <img src="<?=base_url()?>media/logo/logo_codiesel.png" class="img-fluid" alt="Responsive image">
	        </div>
	      </div>
	      	    <a href="#" class="brand-link">
	      
	    </a>

	      <!-- Sidebar Menu -->
	      <nav class="mt-2">
	      	<div class="input-group input-group-sm">
		        <input class="form-control form-control-navbar" id="buscar_items" type="search" placeholder="Buscar" aria-label="Search" style="background-color: #3D3D3D; color: #fff; border-top: 0; border-left: 0; border-right: 0 border-color: gray;">
		        <div class="input-group-append">
		          <button class="btn btn-navbar" type="submit">
		            <i class="fas fa-search" style="color: #fff;"></i>
		          </button>
		        </div>
		      </div>
	      	<!--  Menus dinamicos  -->
	        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" id="menu_items">
	        	<?php 
	        		foreach ($menus->result() as $menu) {

	        	 ?>
	          <li class="nav-item has-treeview">
	            <a href="#" class="nav-link">
	              <i class="nav-icon <?=$menu->icono?>"></i>
	              <p>
	                <?=$menu->menu?>
	                <i class="right fas fa-angle-left"></i>
	              </p>
	            </a>
	            <ul class="nav nav-treeview">
	            	<?php 
	            		$this->load->model('menus');
	            		$submenus = $this->menus->getSubmenusByPerfil($menu->id_menu,$menu->id_perfil);
	            		foreach ($submenus->result() as $submenu) {
	            	 ?>
	              <li class="nav-item">
	                <a href="<?=base_url()?><?=$submenu->vista?>" class="nav-link">
	                  <i class="<?=$submenu->icono?> nav-icon"></i>
	                  <p><?=$submenu->submenu?></p>
	                </a>
	              </li>
	              <?php 
	              	}
	               ?>
	            </ul>
	          </li>
	          <?php 
	      		}
	           ?>
	           
	        </ul>
	      </nav>
	      <!-- /.sidebar-menu -->
	    </div>
	    <!-- /.sidebar -->
	  </aside>

	  <!-- Content Wrapper. Contains page content -->
	  <div class="content-wrapper">
	   <br>
	    <!-- Main content -->
	    <section class="content">
	         <div class="container-fluid" align="center">
	         	<div class="card">
				  <div class="card-header">
				    <h3><strong>Ingreso NPS Colmotores</strong></h3>
				  </div>
				  <div class="card-body">
				    <div class="row">
				    	<div class="col-md-6">
				    		<button type="button" class="btn btn-outline-primary" onclick="mostrar_nps_sede();">Ingresar NPS por sede</button>
				    	</div>
				    	<div class="col-md-6">
				    		<button type="button" class="btn btn-outline-primary" onclick="mostrar_nps_tecnico();">Ingresar NPS por tecnico</button>
				    	</div>
				    </div>
				  </div>
				</div>
				<div class="card" id="nps_sede">
				  <div class="card-header">
				    <h3><strong>Ingreso NPS por sede</strong></h3>
				  </div>
				  <div class="card-body">
				    <form action="<?=base_url()?>encuesta/insert_nps_sedes" method="POST" id="form_sedes">
					  <div class="row">
					    <div class="col">
					      <label>Selecciona una sede(*)</label>
					      <select class="form-control" id="combo_sedes" name="combo_sedes_all" id="combo_sedes_all" required="true">
					      	<option value="">Seleccione una sede</option>
					      	<option value="general">General</option>
					      	<option value="prueba">prueba</option>
					      	<option value="giron">Sede Giron</option>
					      	<option value="rosita">Sede La Rosita</option>
					      	<option value="barranca">Sede Barrancabermeja</option>
					      	<option value="bocono">Sede Cucuta Bocono</option>
					      </select>
					    </div>
					    <div class="col">
					      <label>Seleccione la fecha(*)</label>
					      <input type="date" class="form-control" required="true" name="fecha_all" id="fecha_all">
					    </div>
					  </div>
					  <div class="row">
					  	<div class="col">
					      <label>Calificacion(*)</label>
					      <input type="decimal" class="form-control" required="true" name="calificacion_all" id="cant_encuesta_all" onkeypress="">
					      <small id="passwordHelpBlock" class="form-text text-muted">
							  No usar la coma (,) para numeros decimales solo usar el punto (.)
						  </small>
					    </div>
					    <div class="col" align="center">
					      <label>Tipos de calificacion(*)</label>
					      <br>
					       <div class="form-check form-check-inline">
							  <input class="form-check-input form-control-lg" type="checkbox" value="option1" id="chk_1" name="test[]" onclick="mostrar_califi_0_6(this);">
							  <label class="form-check-label" for="inlineCheckbox1">0-6</label>
							</div>
							<div class="form-check form-check-inline">
							  <input class="form-check-input form-control-lg" type="checkbox" value="option2" id="chk_2" name="test[]" onclick="mostrar_califi_7_8(this);">
							  <label class="form-check-label" for="inlineCheckbox2">7-8</label>
							</div>
							<div class="form-check form-check-inline">
							  <input class="form-check-input form-control-lg" type="checkbox" value="option3" id="chk_3" name="test[]" onclick="mostrar_califi_9_10(this);">
							  <label class="form-check-label" for="inlineCheckbox3">9-10</label>
							</div>
					    </div>
					    <div class="col" id="txt1">
					    	<label>Cantidad de encuestas 0-6</label>
					    	<input type="number" name="cal06" class="form-control" value="0">
					    </div>
					    <div class="col" id="txt2">
					    	<label>Cantidad de encuestas 7-8</label>
					    	<input type="number" name="cal78" class="form-control" value="0">
					    </div>
					    <div class="col" id="txt3">
					    	<label>Cantidad de encuestas 9-10</label>
					    	<input type="number" name="cal910" class="form-control" value="0">
					    </div>
					  </div>
					  <hr>
					  <div class="row" align="center">
					  	<div class="col" align="center">
					  		<button type="submit" class="btn btn-primary mb-2 btn-large" id="submit">Guardar datos</button>
					  	</div>
					  </div>
					</form>
				  </div>
				</div>
				<div class="card" id="nps_tecnico">
				  <div class="card-header">
				    <h3><strong>Ingreso NPS por Tecnico</strong></h3>
				  </div>
				<div class="card-body">
				    <form id="form_tec">
					  <div class="row">
					    <div class="col">
					      <label>Selecciona una sede(*)</label>
					      <select class="form-control" id="select_sedes" name="combo_sedes">
					      	<option value="">Seleccione una sede</option>
					      	<option value="giron">Sede Giron</option>
					      	<option value="rosita">Sede La Rosita</option>
					      	<option value="barranca">Sede Barrancabermeja</option>
					      	<option value="bocono">Sede Cucuta Bocono</option>
					      </select>
					    </div>
					    <div class="col">
					      <label>Ingrese la cedula del tecnico(*)</label>
					       <select id="combo_tecnicos" class="form-control combo_tecnicos" name="combo_tecnicos" id="combo_tecnicos">
					       	<option value="">Seleccione un tecnico</option>
					       	<?php foreach ($tecnicos->result() as $key) { ?>
					       		<option value="<?=$key->nit?>"><?=$key->nit?>-><?=$key->nombre?></option>
					       	<?php } ?>
					       </select>
					    </div>
					    <div class="col">
					      <label>Seleccione la fecha(*)</label>
					      <input type="date" class="form-control" name="fecha" id="fecha">
					    </div>	    
					    
					    <div class="col">
					      <label>VIN(*)</label>
					      <input type="text" class="form-control" name="placa_v" id="placa_v">
					    </div>
					  </div>
					  <br>
					  <div class="row">
					    
					    <div class="col">
					      <label>Ingrese la Calificacion(*)</label>
					      <input type="text" class="form-control" name="calificacion" id="calificacion" onkeypress="return check(event)">
					      <small id="passwordHelpBlock" class="form-text text-muted">
							  No usar la coma (,) para numeros decimales solo usar el punto (.)
						  </small>
					    </div>
					    <div class="col">
					      <label>Tipificacion(*)</label>
					      <select class="form-control" name="combo_tipificacion" id="combo_tipificacion">
					      	<option value="">Seleccione una opcion</option>
					      	<option value="Ninguno">Ninguno</option>
					      	<option value="Cumplimiento en cita">Cumplimiento en cita</option>
					      	<option value="Tiempos de entrega">Tiempos de entrega</option>
					      	<option value="Precios acordados">Precios acordados</option>
					      	<option value="Atencion">Atencion</option>
					      	<option value="Demora en entrega">Demora en entrega</option>
					      	<option value="Calidad del producto">Calidad del producto</option>
					      	<option value="Calidad de la reparación">Calidad de la reparación</option>
					      	<option value="Disponibilidad de repuestos">Disponibilidad de repuestos</option>
					      	<option value="Instalaciones">Instalaciones</option>
					      	<option value="Horarios">Horarios</option>
					      	<option value="Costos">Costos</option>
					      </select>
					    </div>
					    <div class="col" align="center">
					      <label>Tipos de calificacion(*)</label>
					      <select class="form-control" id="combo_calif" name="combo_calif">
					      	<option value="">Seleccione una opcion...</option>
					      	<option value="0a6">0 a 6</option>
					      	<option value="7a8">7 a 8</option>
					      	<option value="9a10">9 a 10</option>
					      </select>
					      <br>
					      <div id="pu"></div>
					     	 <!--<div class="form-check form-check-inline">
							  <input class="form-check-input" type="radio" name="rango" id="inlineRadio1" value="op1" onclick="habilitar_input(this);">
							  <label class="form-check-label" for="inlineRadio1">0 a 6</label>
							</div>
							<div class="form-check form-check-inline">
							  <input class="form-check-input" type="radio" name="rango" id="inlineRadio2" value="op2" onclick="habilitar_input(this);">
							  <label class="form-check-label" for="inlineRadio2">7 a 8</label>
							</div>
							<div class="form-check form-check-inline">
							  <input class="form-check-input" type="radio" name="rango" id="inlineRadio3" value="op3" onclick="habilitar_input(this);">
							  <label class="form-check-label" for="inlineRadio3">9 a 10</label>
							</div>
					    </div>-->
					    <!--<div class="col">
					    	<label>Cantidad de encuestas(*)</label>
					    	<input type="text" name="can_encu" class="form-control" id="can_encu" required="true">
					    </div>-->
						</div>
					  </div>
					  <hr>
					  <div class="row" align="center">
					  	<div class="col" align="center">
					  		<input type="button" class="btn btn-primary mb-2 btn-large" onclick="val_form_nps_tecv2();" value="Guardar datos"></input>
					  	</div>
					  </div>
					</form>
				  </div>
				</div>

	         </div>
	    </section>
	    <!-- /.content -->
	  </div>
	  <!-- /.content-wrapper -->
	  <footer class="main-footer">
	    <strong>Copyright &copy; 2020 <a href="http://adminlte.io">CODIESEL</a>.</strong>
	    Todos los derechos reservados.
	    <div class="float-right d-none d-sm-inline-block">
	      <b>Version</b> 1.0.0-pre
	    </div>
	  </footer>

	  <!-- Control Sidebar -->
	  <aside class="control-sidebar control-sidebar-dark">
	    <!-- Control sidebar content goes here -->
	  </aside>
	  <!-- /.control-sidebar -->
	</div>


	<!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">'¿Has terminado ya?'</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Estas seguro que deseas cerrar sesion</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">No</button>
            <a class="btn btn-primary" href="<?=base_url()?>login/logout">Si</a>
          </div>
        </div>
      </div>
    </div>

	<!-- PASS Modal-->
	<div class="modal" tabindex="-1" id="pass-modal" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Es necesario que cambies tu contraseña</h5>
				</div>
				<div class="modal-body">
					<!-- <form method="POST" action="<?= base_url() ?>usuarios_admin/changepass"> -->
					<form>
						<div class="row">
							<div class="col">
								<label for="pass1">Ingrese la nueva contraseña</label>
								<input type="password" id="pass1_one" name="pass2_one" class="form-control" placeholder="Ingrese nueva contraseña">
							</div>
							<div class="col">
								<label for="pass2">Confirme la contraseña</label>
								<input type="password" id="pass2_one" name="pass1_one" class="form-control" placeholder="Confirma la contraseña">
								<?php
								foreach ($userdata->result() as $key) {
								?>
									<input type="hidden" id="id_usu_one" name="id_usu" value="<?= $key->id_usuario ?>">
								<?php
								}
								?>
							</div>
						</div>

				</div>
				<div class="modal-footer">
					<a href="<?= base_url() ?>login/logout" class="btn btn-secondary">Cerrar</a>
					<button type="button" class="btn btn-primary" onclick="cambiarPass_One();">Cambiar</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- PASS Modal-->
	<div class="modal" tabindex="-1" id="pass-modal2" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Cambio de Contraseña</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<!-- <form method="POST" action="<?= base_url() ?>usuarios_admin/changepass"> -->
					<form>
						<div class="row">
							<div class="col">
								<label for="pass1">Ingrese la nueva contraseña</label>
								<input type="password" id="pass1_two" name="pass2" class="form-control" placeholder="Ingrese nueva contraseña">
							</div>
							<div class="col">
								<label for="pass2">Confirme la contraseña</label>
								<input type="password" id="pass2_two" name="pass1" class="form-control" placeholder="Confirma la contraseña">
								<?php
								foreach ($userdata->result() as $key) {
								?>
									<input type="hidden" id="id_usu_two" name="id_usu" value="<?= $key->id_usuario ?>">
								<?php
								}
								?>
							</div>
						</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="cambiarPass_Two();">Cambiar</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- MENSAJE FLOTANTE-->
	<?php 
		$log = $this->input->get('log');
		if($log == "err_p"){
	 ?>
	 <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert_err" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
		 Error... Las contraseñas no coinciden
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    <span aria-hidden="true">&times;</span>
		</button>
	 </div>
	 <?php 
	 	}
	  ?>
	  <div id="notifi">

	  </div>
	 <!-- alertas -->
	<?php 
	$log = $this->input->get('log');
	if($log == "ok"){

	 ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert" id="alert_ok" style="position: fixed; z-index: 100; top: 93%; right: 1%;">
		Operacion realizada con exito
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<?php 
		}
	 ?>

	 <div class="alert alert-warning alert-dismissible fade show" role="alert" id="alert_chk" style="position: fixed; z-index: 100; top: 93%; right: 1%; display: none;">
		Debes seleccionar una opcion
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<!-- jQuery -->
	<script src="<?=base_url()?>plugins/jquery/jquery.min.js"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="<?=base_url()?>plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
	  $.widget.bridge('uibutton', $.ui.button)
	</script>
	<!-- Bootstrap 4 -->
	<script src="<?=base_url()?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- Summernote -->
	<script src="<?=base_url()?>plugins/summernote/summernote-bs4.min.js"></script>
	<!-- overlayScrollbars -->
	<script src="<?=base_url()?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?=base_url()?>dist/js/adminlte.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="<?=base_url()?>dist/js/pages/dashboard.js"></script>
	<!-- select 2 -->
	<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
	<!-- js propios -->
	
	<!-- SweetAlert2 -->
    <script src="<?=base_url()?>plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
	<script src="<?=base_url()?>plugins/toastr/toastr.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
		  $("#buscar_items").on("keyup", function() {
		    var value = $(this).val().toLowerCase();
		    $("#menu_items li").filter(function() {
		      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		    });
		  });
		  $("#combo_tecnicos").select2({
		  	width: 'resolve'
		  });
		  setInterval('notificacion()',60000);
		  document.getElementById("nps_sede").style.display = "none";
		  document.getElementById("nps_tecnico").style.display = "none";
		  document.getElementById("txt1").style.display = "none";
		  document.getElementById("txt2").style.display = "none";
		  document.getElementById("txt3").style.display = "none";
		  //document.getElementById("can_encu").disabled = true;
		  document.getElementById("alert_chk").style.display = "none";
		  
		  

		});
		setTimeout(function(){ 
			$('#alert_ok').alert('close');
		 }, 1500);

		/*setTimeout(function(){ 
			$("#alert_chk").alert('close');
		 }, 1500);*/
		function notificacion() {
			var result = document.getElementById("notifi");
			var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    result.innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "<?=base_url()?>admin_presupuesto/notifi_prueba?var=1", true);
            xmlhttp.send();
		}

		function mostrar_califi_0_6(checkbox){
			if (!checkbox.checked) {
				 document.getElementById("txt1").style.display = "none";
			}else{
				 document.getElementById("txt1").style.display = "block";
			}
		}

		function mostrar_califi_7_8(checkbox){
			if (!checkbox.checked) {
				 document.getElementById("txt2").style.display = "none";
			}else{
				 document.getElementById("txt2").style.display = "block";
			}
		}
		function mostrar_califi_9_10(checkbox){
			if (!checkbox.checked) {
				 document.getElementById("txt3").style.display = "none";
			}else{
				 document.getElementById("txt3").style.display = "block";
			}
		}

		function habilitar_input(radio){
			var input = document.getElementById("can_encu");
			if (!radio.checked) {
				input.disabled = true;
			}else{
				input.disabled = false;
			}
		}
	</script>
	<script type="text/javascript">
		function val_form_nps_tecv2(){
	    var sede = $('#select_sedes option:selected').val();
	    var tecnico = document.getElementById('combo_tecnicos').value;
	    var fecha = document.getElementById('fecha').value;
	    //var vin_p = document.getElementById('vin_p').value;
	    var placa_v = $('#placa_v').val();
	    var calificacion = document.getElementById('calificacion').value;
	    var tipificacion = document.getElementById('combo_tipificacion').value;
	    var tipo_calif = document.getElementById('combo_calif').value;
	    console.log(tecnico);
	    var aux = placa_v;
	    const Toast = Swal.mixin({
	      toast: true,
	      position: 'top-end',
	      showConfirmButton: false,
	      timer: 3000
	    });
	    if (sede == "") {
	        //alert("sede vacia");
	         Toast.fire({
	        type: 'warning',
	        title: 'No puedes dejar campos vacios'
	      });
	    }
	    else if (tecnico == "") {   
	        Toast.fire({
	        type: 'warning',
	        title: 'No puedes dejar campos vacios'
	      });
	    }
	    else if (fecha == "") {
	        Toast.fire({
	        type: 'warning',
	        title: 'No puedes dejar campos vacios'
	      });
	    }
	    else if (placa_v = "") {
	        Toast.fire({
	        type: 'warning',
	        title: 'No puedes dejar campos vacios'
	      });
	    }
	    else if (calificacion == "") {
	        Toast.fire({
	        type: 'warning',
	        title: 'No puedes dejar campos vacios'
	      });
	    }
	    else if (tipificacion == "") {
	        Toast.fire({
	        type: 'warning',
	        title: 'No puedes dejar campos vacios'
	      });
	    }
	    else if (tipo_calif == "") {
	        Toast.fire({
	        type: 'warning',
	        title: 'No puedes dejar campos vacios'
	       });
	    }else{
	            var result = document.getElementById("pu");
	            var xmlhttp;
	            if (window.XMLHttpRequest) {
	                xmlhttp = new XMLHttpRequest();
	            } else {
	                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	            }
	            xmlhttp.onreadystatechange = function () {
	                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
	                	result.innerHTML = xmlhttp.responseText;
	                }
	            }
	            console.log(aux);
	            var datos = "sede="+sede+"&tecnico="+tecnico+"&fecha="+fecha+"&calificacion="+calificacion+"&tipif="+tipificacion+"&tipo_cal="+tipo_calif+"&placa_v="+aux;
	            xmlhttp.open("GET", "<?=base_url()?>encuesta/insert_nps_tecnicos?"+datos, true);
	            xmlhttp.send();
	            //console.log("vien");
	    }
	    
	     document.getElementById("form_tec").reset();

	}
	</script>
	<script type="text/javascript">
		function mostrar_nps_sede() {
		    var x = document.getElementById("nps_sede");
		    document.getElementById("nps_tecnico").style.display = "none";
		   
		    if (x.style.display === "none") {
		       x.style.display = "block";
			} else {
			   x.style.display = "none";
			}
		    
		    
		}

		function mostrar_nps_tecnico() {
		    var x = document.getElementById("nps_tecnico");
		    document.getElementById("nps_sede").style.display = "none";
		    var fecha_all = $('#fecha_all').val();
		    //var combo_sedes_all = $('#combo_sedes_all:selected').val();
		    var calificacion_all = $('#calificacion_all').val();
		    //alert(combo_sedes_all);
		    if (fecha_all != "" ) {
		    	alert("no se puede");
		    }else{
			    if (x.style.display === "none") {
			        x.style.display = "block";
			    } else {
			        x.style.display = "none";
			    }
			}
		}


		$("#submit").on("click",function(){
		    if (($("input[name*='test']:checked").length)<=0) {
		        alert("You must check at least 1 box");
		    }
		    return true;
		});


		function validar_datos_sedes(){
			var chk1 = document.getElementById('chk_1');
			var chk2 = document.getElementById('chk_2');
			var chk3 = document.getElementById('chk_3');
			//alert("bien");
			if (!chk1.checked || !chk2.checked || !chk3.checked) {
				//event.preventDefault();
				document.getElementById("alert_chk").style.display = "block";
				//alert("debe seleccionar un tipo de calificacion");
				//
			}
			
			
		}

		function check(e) {
		    tecla = (document.all) ? e.keyCode : e.which;
		    //Tecla de retroceso para borrar, siempre la permite
		    /*if (tecla == 8) {
		        return true;
		    }*/
		    // Patron de entrada, en este caso solo acepta numeros y letras
		    patron = /[0-9.]/;
		    tecla_final = String.fromCharCode(tecla);
		    return patron.test(tecla_final);
		}
	</script>
	<script src="<?=base_url()?>dist/js/demo.js"></script>
	<?php 
		$this->load->model('usuarios');
		$usu = $this->usuarios->getUserById($id_usu);
		$p="";
		$nit="";
		foreach ($usu->result() as $key) {
			$p = $key->pass;
			$nit = $key->nit;
		}
		//echo $p." ".$nit;
		$this->load->library('encrypt');
		$pass_desencript = $this->encrypt->decode($p);
	    if($pass_desencript == $nit){
	 ?>
		<script type="text/javascript">
			$('#pass-modal').show('true')
		</script>
	<?php 
		}
	 ?>
	 <script type="text/javascript">
		setTimeout(function(){ 
			$('#alert_err').alert('close');
		 }, 1500);
	</script>
	<script src="<?= base_url() ?>dist/js/md5.js"></script>
	<script>
		function cambiarPass_One() {
			console.log('Cambiando contraseña');
			let pass1 = document.getElementById('pass1_one').value;
			let pass2 = document.getElementById('pass2_one').value;
			let id_usuario = document.getElementById('id_usu_one').value;
			let clave = hex_md5(pass1);
			console.log(pass1 + "=" + pass2);
			if (pass1 === pass2 && pass1 != "" && pass2 != "") {
				let form = new FormData();
				/* 
					$pass1 = $this->input->POST('pass1');
					$pass2 = $this->input->POST('pass2');
					$id_usu = $this->input->POST('id_usu');
					$clave = $this->input->POST('clave'); 
				*/
				form.append('pass1', pass1);
				form.append('pass2', pass2);
				form.append('id_usu', id_usuario);
				form.append('clave', clave);
				var xmlhttp;
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						var resp = xmlhttp.responseText;
						if (resp == 1) {
							Swal.fire({
								title: 'Exito!',
								text: 'Se ha cambiado con exito la contraseña',
								icon: 'success',
								confirmButtonText: 'Ok',
								allowOutsideClick: false
							}).then((result) => {
								/* Read more about isConfirmed, isDenied below */
								if (result.isConfirmed) {
									window.location.reload();
								}
							});
						} else if (resp == 2) {
							Swal.fire({
								title: 'Error!',
								text: 'No se ha actualizado la contraseña.',
								icon: 'error',
								confirmButtonText: 'Ok',
								allowOutsideClick: false
							}).then((result) => {
								/* Read more about isConfirmed, isDenied below */
								if (result.isConfirmed) {
									location.reload();
								}
							});
						}

					}
				}
				xmlhttp.open("POST", "<?= base_url() ?>usuarios_admin/changepass", true);
				xmlhttp.send(form);
			} else {
				Swal.fire({
					title: 'Error!',
					text: 'Las contraseñas no coinciden',
					icon: 'error',
					confirmButtonText: 'Cerrar'
				});
			}
		}

		function cambiarPass_Two() {
			console.log('Cambiando contraseña');
			let pass1 = document.getElementById('pass1_two').value;
			let pass2 = document.getElementById('pass2_two').value;
			let id_usuario = document.getElementById('id_usu_two').value;
			let clave = hex_md5(pass1);
			console.log(pass1 + "=" + pass2);
			if (pass1 === pass2 && pass1 != "" && pass2 != "") {
				let form = new FormData();
				form.append('pass1', pass1);
				form.append('pass2', pass2);
				form.append('id_usu', id_usuario);
				form.append('clave', clave);
				var xmlhttp;
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						var resp = xmlhttp.responseText;
						if (resp == 1) {
							Swal.fire({
								title: 'Exito!',
								text: 'Se ha actualizado la contraseña.',
								icon: 'success',
								confirmButtonText: 'Ok',
								allowOutsideClick: false
							}).then((result) => {
								/* Read more about isConfirmed, isDenied below */
								if (result.isConfirmed) {
									location.reload();
								}
							});
						} else if (resp == 2) {
							Swal.fire({
								title: 'Error!',
								text: 'No se ha actualizado la contraseña.',
								icon: 'error',
								confirmButtonText: 'Ok',
								allowOutsideClick: false
							}).then((result) => {
								/* Read more about isConfirmed, isDenied below */
								if (result.isConfirmed) {
									location.reload();
								}
							});
						}

					}
				}
				xmlhttp.open("POST", "<?= base_url() ?>usuarios_admin/changepass", true);
				xmlhttp.send(form);
			} else {
				Swal.fire({
					title: 'Error!',
					text: 'Las contraseñas no coinciden',
					icon: 'error',
					confirmButtonText: 'Cerrar'
				});
			}



		}
	</script>
</body>
</html>