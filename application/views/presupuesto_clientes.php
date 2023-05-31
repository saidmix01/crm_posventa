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
	  <!-- iCheck -->
	  <link rel="stylesheet" href="<?=base_url()?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	  <!-- JQVMap -->
	  <link rel="stylesheet" href="<?=base_url()?>plugins/jqvmap/jqvmap.min.css">
	  <!-- Theme style -->
	  <link rel="stylesheet" href="<?=base_url()?>dist/css/adminlte.min.css">
	  <!-- overlayScrollbars -->
	  <link rel="stylesheet" href="<?=base_url()?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	  <!-- Daterange picker -->
	  <link rel="stylesheet" href="<?=base_url()?>plugins/daterangepicker/daterangepicker.css">
	  <!-- summernote -->
	  <link rel="stylesheet" href="<?=base_url()?>plugins/summernote/summernote-bs4.css">
	  <!-- Google Font: Source Sans Pro -->
	  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	  
	  <link rel="shortcut icon" href="<?=base_url()?>media/logo/logo_codiesel_sinfondo.png" />
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
	          <div class="dropdown-divider"></div>
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
	         <div class="container-fluid" align="center" style="margin-top: 0%;">
				<div class="card">
				  <div class="card-body">
				    <div class="row">
						<div class="col-md-6" align="left">
							<a href="javascript:history.go(-1)" class="btn btn-default btn-sm"><i class="fas fa-arrow-alt-circle-left"></i> Atras</a>
						</div>
						<div class="col-md-6" align="right">	
							<a href="javascript:history.go(1)" class="btn btn-default btn-sm" align="right">Adelante <i class="fas fa-arrow-alt-circle-right"></i></a>
						</div>
					</div>
				  </div>
				</div>

				<div class="card">
					  <div class="card-body">
					  	<div class="col-lg-12">
			            <div class="info-box" style="width: 100%">
			              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-user-friends"></i></span>

			              <div class="info-box-content">
			                <span class="info-box-text" style="font-size: 25px;">CLIENTES <?=$bodega?></span>
			                <span class="info-box-number" style="font-size: 20px;">
			                  $<?=number_format($presu,0,",",",")?>
			                  
			                </span>
			              </div>
			              <!-- /.info-box-content -->
			            </div>
			            <!-- /.info-box -->
			          </div>
			          <div class="dropdown-divider"></div>
					  	<!-- <div class="row" id="real_time"> -->
					    <div class="row container-fluid" id="">
			      			<?php 
			      				foreach ($arr_cliente as $cli) {
			      					foreach ($cli as $key) {
			      						if ($key['valor'] != 0) {
			      			 ?>
			      			 	<div class="col-md-6" id="nose">
								 <div class="card card-info">
										 <div class="card-header">
										    <h3 class="card-title" style="font-size: 30px;"><i class="far fa-building"></i><?=$key['nombre']?></h3>
										    <div class="card-tools">
										    </div>
										  </div>
										  <div class="card-body" align="center">
										    <div class="col-md-12">
										    	<div class="info-box mb-3">
									              <span class="info-box-icon bg-warning elevation-1" style="font-size: 30px"><i class="fas fa-dollar-sign"></i></span>

									              <div class="info-box-content">
									                <span class="info-box-text" style="font-size: 30px">TOTAL FACTURADO</span>
									                <span class="info-box-number" style="font-size: 30px">
									                	<?=number_format($key['valor'],0,",",",")?>
									                </span>
									                
									              </div>
									              <!-- /.info-box-content -->
									            </div>
							                   
							                    <!-- /.progress-group -->
							                    <!-- /.progress-group -->
							                  </div>

							                  <!-- /.col -->
										  </div>
										  <!-- /.card-body -->
										  <div class="card-footer">
										    	<a href="<?=base_url()?>admin_presupuesto/cargar_cliente_total?bod=<?=$key['bodega']?>&cliente=<?=$key['nombre']?>&presupuesto=<?=$key['valor']?>" class="btn btn-block btn-outline-info btn-sm"><i class="fas fa-eye"></i> Ver Mas</a>
										  </div>
									</div>
								 </div>
			      			 <?php 
			      					}
			      			 	}
			      			 }
			      			  ?>
			     		</div>
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
	<!-- overlayScrollbars -->
	<script src="<?=base_url()?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?=base_url()?>dist/js/adminlte.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="<?=base_url()?>dist/js/pages/dashboard.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="<?=base_url()?>dist/js/demo.js"></script>
		<!-- DataTables -->
	<script src="<?=base_url()?>plugins/datatables/jquery.dataTables.js"></script>
	<script src="<?=base_url()?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>

<script type="text/javascript">
		$(document).ready(function(){
		     $('#example1').DataTable({
		      "paging": false,
		      "pageLength": 5,
		      "lengthChange": true,
		      "searching": true,
		      "ordering": true,
		      "info": true,
		      "autoWidth": false,
		      "language":{
    			"sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla =(",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Visibilidad"
                }
			}
		    });
		});
	</script>

	<script type="text/javascript">
		$(document).ready(function(){
		  $("#buscar_items").on("keyup", function() {
		    var value = $(this).val().toLowerCase();
		    $("#menu_items li").filter(function() {
		      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		    });
		  });
		});
	</script>


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