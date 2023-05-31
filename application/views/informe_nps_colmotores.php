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
	    <!-- Select2 -->
	  <link rel="stylesheet" href="<?=base_url()?>plugins/select2/css/select2.min.css">
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
	         <div class="container-fluid" align="center" style="">
	         	<div class="card">
				  <div class="card-header">
				    <h3><strong>Informe de NPS Colmotores</strong></h3>
				  </div>
				  <div class="card-body">
				  	<form>
				  		<div class="row">
						    <div class="col">
						      <label>Seleccione(*)</label>
						      <div class="container">
						    	<div class="form-check form-check-inline">
								  <input class="form-check-input" type="radio" name="chk_tipo" id="chk_t2" value="sede">
								  <label class="form-check-label" for="inlineRadio1">Ver por sede</label>
								</div>
								<div class="form-check form-check-inline">
								  <input class="form-check-input" type="radio" name="chk_tipo" id="tec_radio" value="tecnico">
								  <label class="form-check-label" for="inlineRadio2">Ver por tecnico</label>
								</div>
						      </div>
						    </div>
						</div>
						<div class="row">
						    <div id="filtro_gral" class="col">
						    	<div class="row">
							    	<div class="col">
								      <label>Fecha inicial(*)</label>
								      <input type="date" name="desde_gra" id="desde_gral" class="form-control">
								    </div>
								    <div class="col">
								    	<label>Fecha final(*)</label>
								    	<input type="date" name="hasta_gra" id="hasta_gral" class="form-control">
								    </div>
								    <div class="col">
								    	<input type="button" name="btn" class="btn btn-primary btn-lg" id="graficar_gral" value="Generar Informe">
								    </div>
							    </div>
						    </div>
						    <div id="filtro_sedes" class="col">
						    	<div class="row">
							    	<div class="col">
								      <label>Fecha inicial(*)</label>
								      <input type="date" name="desde_gra" id="desde_sede" class="form-control">
								    </div>
								    <div class="col">
								    	<label>Fecha final(*)</label>
								    	<input type="date" name="hasta_gra" id="hasta_sede" class="form-control">
								    </div>
								    <div class="col">
								    	<label>Seleccione una sede(*)</label>
								    	<select class="js-example-basic-multiple" name="sedes[]" multiple="multiple" style="width: 100%" id="select_sedes">
											  <option value="Giron">Giron</option>
											  <option value="La Rosita">La Rosita</option>
											  <option value="Barrancabermeja">Barrancabermeja</option>
											  <option value="Cucuta Bocono">Cucuta Bocono</option>
										</select>
								    </div>
								    <div class="col">
								    	<input type="button" name="btn" class="btn btn-primary btn-lg" id="graficar_sede" value="Generar Informe">
								    </div>
							    </div>
						    </div>
						    <div id="filtro_tec" class="col">
						    	<div class="row">
							    	<div class="col">
								      <label>Fecha inicial(*)</label>
								      <input type="date" name="desde_gra" id="desde_tec" class="form-control">
								    </div>
								    <div class="col">
								    	<label>Fecha final(*)</label>
								    	<input type="date" name="hasta_gra" id="hasta_tec" class="form-control">
								    </div>
								    <div class="col">
								    	<label>Seleccione un tecnico(*)</label>
								    	<select class="form-control" id="combo_tec">
								    		<option value="">Seleccione un tecnico</option>
								    		<?php foreach ($tecnicos->result() as $key) { ?>
								    			<option value="<?=$key->nit?>"><?=$key->nit?>-><?=$key->nombre?></option>
								    		<?php } ?>
								    	</select>
								    </div>
								    <div class="col">
								    	<input type="button" name="btn" class="btn btn-primary btn-lg" id="graficar_tecnico" value="Generar Informe">
								    </div>
							    </div>
						    </div>
						  </div>
				    </form>
				  </div>
				</div>
				<div id="mostrar_Informe">
					<div class="card">
					  <div class="card-body">
					    <div class="card">
			              <div class="card-header border-0">
			                <div class="d-flex justify-content-between">
			                  <h3 class="card-title">Registro de NPS general</h3>
			                  <a href="javascript:void(0);">Ver Reporte</a>
			                </div>
			              </div>
			              <div class="card-body">
			                <div class="d-flex">
			                  <p class="d-flex flex-column">
			                  </p>
			                  <p class="ml-auto d-flex flex-column text-right">
			                  </p>
			                </div>
			                <!-- /.d-flex -->
			                <div class="position-relative mb-4">
			                  <canvas id="myChart" height="70"></canvas>
			                </div>
			              </div>
			            </div>
					  </div>
					</div>
					<div class="card">
		              <div class="card-header border-0">
		                <h3 class="card-title">Detalles NPS General</h3>
		                <div class="card-tools">
		                  <a href="#" class="btn btn-tool btn-sm">
		                    <i class="fas fa-download"></i>
		                  </a>
		                  <a href="#" class="btn btn-tool btn-sm">
		                    <i class="fas fa-bars"></i>
		                  </a>
		                </div>
		              </div>
		              <div class="card-body table-responsive p-0">
		                <table class="table table-striped table-valign-middle">
		                  <thead align="center">
		                  <tr>
		                    <th>Fecha</th>
		                    <th>Calificacion</th>
		                    <th>No Encuestas 0 a 6</th>
		                    <th>No Encuestas 7 a 8</th>
		                    <th>No Encuestas 9 a 10</th>
		                  </tr>
		                  </thead>
		                  <tbody id="detalle_nps_gral" align="center">

		                  </tbody>
		                </table>
		              </div>
		            </div>
				</div>
				<div id="mostrar_Informe_tecnicos_v2">
					<div class="row">
						<div class="col-md-6">
							<div class="card">
							  <div class="card-body">
							    <div class="card">
					              <div class="card-header border-0">
					                <div class="d-flex justify-content-between">
					                  <h3 class="card-title">Registro de NPS Sede Giron</h3>
					                </div>
					              </div>
					              <div class="card-body">
					                <div class="d-flex">
					                  <p class="d-flex flex-column">
					                  </p>
					                  <p class="ml-auto d-flex flex-column text-right">
					                  </p>
					                </div>
					                <!-- /.d-flex -->
					                <div class="position-relative mb-4">
					                  <canvas id="graf_tec_g" height="100"></canvas>
					                </div>
					              </div>
					            </div>
							  </div>
							</div>
							<div class="card">
				              <div class="card-header border-0">
				                <h3 class="card-title">Detalles NPS Sede Giron</h3>
				                <div class="card-tools">
				                  <a href="#" class="btn btn-tool btn-sm">
				                    <i class="fas fa-download"></i>
				                  </a>
				                  <a href="#" class="btn btn-tool btn-sm">
				                    <i class="fas fa-bars"></i>
				                  </a>
				                </div>
				              </div>
				              <div class="card-body table-responsive p-0">
				                <table class="table table-striped table-valign-middle">
				                  <thead align="center">
				                  <tr>
				                  	<th>Nombres</th>
				                  	<th>Fecha</th>
				                    <th>No Encuestas 0 a 6</th>
				                    <th>No Encuestas 7 a 8</th>
				                    <th>No Encuestas 9 a 10</th>
				                    <th>NPS</th>
				                  </tr>
				                  </thead>
				                  <tbody id="tabla_tec_g" align="center">

				                  </tbody>
				                </table>
				              </div>
				            </div>
						</div>
						<div class="col-md-6">
							<div class="card">
							  <div class="card-body">
							    <div class="card">
					              <div class="card-header border-0">
					                <div class="d-flex justify-content-between">
					                  <h3 class="card-title">Registro de NPS Sede Rosita</h3>
					                </div>
					              </div>
					              <div class="card-body">
					                <div class="d-flex">
					                  <p class="d-flex flex-column">
					                  </p>
					                  <p class="ml-auto d-flex flex-column text-right">
					                  </p>
					                </div>
					                <!-- /.d-flex -->
					                <div class="position-relative mb-4">
					                  <canvas id="graf_tec_r" height="100"></canvas>
					                </div>
					              </div>
					            </div>
							  </div>
							</div>
							<div class="card">
				              <div class="card-header border-0">
				                <h3 class="card-title">Detalles NPS Sede rosita</h3>
				                <div class="card-tools">
				                  <a href="#" class="btn btn-tool btn-sm">
				                    <i class="fas fa-download"></i>
				                  </a>
				                  <a href="#" class="btn btn-tool btn-sm">
				                    <i class="fas fa-bars"></i>
				                  </a>
				                </div>
				              </div>
				              <div class="card-body table-responsive p-0">
				                <table class="table table-striped table-valign-middle">
				                  <thead align="center">
				                  <tr>
				                  	<th>Nombres</th>
				                  	<th>Fecha</th>
				                    <th>No Encuestas 0 a 6</th>
				                    <th>No Encuestas 7 a 8</th>
				                    <th>No Encuestas 9 a 10</th>
				                    <th>NPS</th>
				                  </tr>
				                  </thead>
				                  <tbody id="tabla_tec_r" align="center">

				                  </tbody>
				                </table>
				              </div>
				            </div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="card">
							  <div class="card-body">
							    <div class="card">
					              <div class="card-header border-0">
					                <div class="d-flex justify-content-between">
					                  <h3 class="card-title">Registro de NPS Sede Barrancabermeja</h3>
					                </div>
					              </div>
					              <div class="card-body">
					                <div class="d-flex">
					                  <p class="d-flex flex-column">
					                  </p>
					                  <p class="ml-auto d-flex flex-column text-right">
					                  </p>
					                </div>
					                <!-- /.d-flex -->
					                <div class="position-relative mb-4">
					                  <canvas id="graf_tec_ba" height="100"></canvas>
					                </div>
					              </div>
					            </div>
							  </div>
							</div>
							<div class="card">
				              <div class="card-header border-0">
				                <h3 class="card-title">Detalles NPS Sede Barrancabermeja</h3>
				                <div class="card-tools">
				                  <a href="#" class="btn btn-tool btn-sm">
				                    <i class="fas fa-download"></i>
				                  </a>
				                  <a href="#" class="btn btn-tool btn-sm">
				                    <i class="fas fa-bars"></i>
				                  </a>
				                </div>
				              </div>
				              <div class="card-body table-responsive p-0">
				                <table class="table table-striped table-valign-middle">
				                  <thead align="center">
				                  <tr>
				                  	<th>Nombres</th>
				                  	<th>Fecha</th>
				                    <th>No Encuestas 0 a 6</th>
				                    <th>No Encuestas 7 a 8</th>
				                    <th>No Encuestas 9 a 10</th>
				                    <th>NPS</th>
				                  </tr>
				                  </thead>
				                  <tbody id="tabla_tec_ba" align="center">

				                  </tbody>
				                </table>
				              </div>
				            </div>
						</div>
						<div class="col-md-6">
							<div class="card">
							  <div class="card-body">
							    <div class="card">
					              <div class="card-header border-0">
					                <div class="d-flex justify-content-between">
					                  <h3 class="card-title">Registro de NPS Sede Bocono</h3>
					                </div>
					              </div>
					              <div class="card-body">
					                <div class="d-flex">
					                  <p class="d-flex flex-column">
					                  </p>
					                  <p class="ml-auto d-flex flex-column text-right">
					                  </p>
					                </div>
					                <!-- /.d-flex -->
					                <div class="position-relative mb-4">
					                  <canvas id="graf_tec_bo" height="100"></canvas>
					                </div>
					              </div>
					            </div>
							  </div>
							</div>
							<div class="card">
				              <div class="card-header border-0">
				                <h3 class="card-title">Detalles NPS Sede Bocono</h3>
				                <div class="card-tools">
				                  <a href="#" class="btn btn-tool btn-sm">
				                    <i class="fas fa-download"></i>
				                  </a>
				                  <a href="#" class="btn btn-tool btn-sm">
				                    <i class="fas fa-bars"></i>
				                  </a>
				                </div>
				              </div>
				              <div class="card-body table-responsive p-0">
				                <table class="table table-striped table-valign-middle">
				                  <thead align="center">
				                  <tr>
				                  	<th>Nombres</th>
				                  	<th>Fecha</th>
				                    <th>No Encuestas 0 a 6</th>
				                    <th>No Encuestas 7 a 8</th>
				                    <th>No Encuestas 9 a 10</th>
				                    <th>NPS</th>
				                  </tr>
				                  </thead>
				                  <tbody id="tabla_tec_bo" align="center">

				                  </tbody>
				                </table>
				              </div>
				            </div>
						</div>
					</div>
				</div> 

				<div id="mostrar_Informe_sedesv2">
					<div class="card">
					  <div class="card-body">
					    <div class="card">
			              <div class="card-header border-0">
			                <div class="d-flex justify-content-between">
			                  <h3 class="card-title">Registro de NPS del mes</h3>
			                </div>
			              </div>
			              <div class="card-body">
			                <div class="d-flex">
			                  <p class="d-flex flex-column">
			                  </p>
			                  <p class="ml-auto d-flex flex-column text-right">
			                  </p>
			                </div>
			                <!-- /.d-flex -->
			                <div class="position-relative mb-4">
			                  <canvas id="graf_sedev2" height="70"></canvas>
			                </div>
			                <div class="row"  align="center">
			                	<div class="col-md-12">
			                		<p>
									  <a class="btn btn-link" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
									    Filtrar
									  </a>
									</p>
									<div class="collapse" id="collapseExample">
									  <div class="card card-body">
									    <form id="form_sedes">
										  <div class="row">
										    <div class="col">
										      <label>Selecciona una sede(*)</label>
										      <select class="form-control" id="combo_sedes" name="combo_sedes" id="combo_sedes_all" required="true">
										      	<option value="">Seleccione una sede</option>
										      	<option value="general">General</option>
										      	<option value="giron">Sede Giron</option>
										      	<option value="rosita">Sede La Rosita</option>
										      	<option value="barranca">Sede Barrancabermeja</option>
										      	<option value="bocono">Sede Cucuta Bocono</option>
										      </select>
										    </div>
										    <div class="col">
										      <label>Fecha inicial(*)</label>
										      <input type="date" class="form-control" required="true" name="fecha_ini" id="fecha_ini">
										    </div>
										    <div class="col">
										      <label>Fecha final(*)</label>
										      <input type="date" class="form-control" required="true" name="fecha_fin" id="fecha_fin">
										    </div>
										    <div class="col">
										    	<label></label>
										    	<input type="button" name="btn_list_sedes" id="btn_list_sedes" value="Ver detalles" class="btn btn-secondary form-control" onclick="generar_Informe_sedes();">
										    </div>
										  </div>
										</form>
									  </div>
									</div>
			                		
			                	</div>
			                </div>
			                <hr>
			                <div class="row">
			                	<div class="col-md-12">
			                		<div class="card">
						              <div class="card-header border-0">
						                <h3 class="card-title">Detalles NPS General</h3>
						                <div class="card-tools">
						                  <a href="#" class="btn btn-tool btn-sm" onclick="tableToExcel('detalle_nps_sedev2','Informe');">
						                    <i class="fas fa-download"></i>
						                  </a>
						                </div>
						              </div>
						              <div class="card-body table-responsive p-0">
						                <table class="table table-valign-middle">
						                  <thead align="center">
						                  <tr >
						                  	<th>Sede</th>
						                    <th>Fecha</th>
						                    <th>Calificacion</th>
						                    <th>No Encuestas 0 a 6</th>
						                    <th>No Encuestas 7 a 8</th>
						                    <th>No Encuestas 9 a 10</th>
						                  </tr>
						                  </thead>
						                  <tbody id="detalle_nps_sedev2" align="center">

						                  </tbody>
						                </table>
						              </div>
						            </div>
			                	</div>
			                </div>
			              </div>
			            </div>
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
	  <div id="notifi">

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
	<!-- overlayScrollbars -->
	<script src="<?=base_url()?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?=base_url()?>dist/js/adminlte.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="<?=base_url()?>dist/js/pages/dashboard.js"></script>
	<!-- AdminLTE for demo purposes -->
	<!-- OPTIONAL SCRIPTS -->
	<script src="<?=base_url()?>plugins/chart.js/Chart.min.js"></script>
	<script src="<?=base_url()?>dist/js/demo.js"></script>
	<script src="<?=base_url()?>dist/js/pages/dashboard3.js"></script>
		<!-- Select2 -->
	<script src="<?=base_url()?>plugins/select2/js/select2.full.min.js"></script>
	<!-- DataTables -->
	<script src="<?=base_url()?>plugins/datatables/jquery.dataTables.js"></script>
	<script src="<?=base_url()?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
	
	

	<script type="text/javascript">
		$(document).ready(function(){
		  $("#buscar_items").on("keyup", function() {
		    var value = $(this).val().toLowerCase();
		    $("#menu_items li").filter(function() {
		      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		    });
		  });
		  setInterval('notificacion()',60000);
		  document.getElementById('filtro_gral').style.display = "none";
		  document.getElementById('filtro_sedes').style.display = "none";
		  document.getElementById('filtro_tec').style.display = "none";
		  document.getElementById('mostrar_Informe').style.display = "none";
		  //document.getElementById('mostrar_Informe_sedes').style.display = "none";
		  document.getElementById('mostrar_Informe_tecnicos_v2').style.display = "none";
		  document.getElementById('mostrar_Informe_sedesv2').style.display = "none";

		  /*document.getElementById('rep_giron').style.display = "none";
		  document.getElementById('rep_rosita').style.display = "none";
		  document.getElementById('rep_barranca').style.display = "none";
		  document.getElementById('rep_bocono').style.display = "none";*/
		  $('.js-example-basic-multiple').select2();
		  $('#combo_tec').select2({ width: '100%' });
		});

		/*function mostrar_Informe(){
			var rad1 = document.getElementById("chk_t1");
			var rad2 = document.getElementById("chk_t2");
			var rad3 = document.getElementById("chk_t3");
			if(rad2.checked){
				document.getElementById('filtro_gral').style.display = "none";
				document.getElementById('filtro_sedes').style.display = "block";
				document.getElementById('filtro_tec').style.display = "none";
			}else if(rad3.checked){
				document.getElementById('filtro_gral').style.display = "none";
				document.getElementById('filtro_sedes').style.display = "none";
				document.getElementById('filtro_tec').style.display = "block";
			}
		}*/

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
	</script>
	<script>
			$('#chk_t2').click(function(){
				//document.getElementById('mostrar_Informe').style.display = "none";
				document.getElementById('mostrar_Informe_tecnicos_v2').style.display = "none";
				document.getElementById('mostrar_Informe_sedesv2').style.display = "block";

				var tecnico = $('#combo_tec').val();
				$.post("<?=base_url()?>Informes/Informe_nps_sedes",
					function(data){

						var obj = JSON.parse(data);

						var s_g = [];
						var c_g = [];
						var s_r = [];
						var c_r = [];
						var s_ba = [];
						var c_ba = [];
						var s_bo = [];
						var c_bo = [];
						var s_ge = [];
						var c_ge = [];

						for (var i = 0; i < obj.giron.length; i++) {
							s_g.push(obj.giron[i].giron);
							c_g.push(obj.giron[i].calificacion_g);
						}

						for (var i = 0; i < obj.rosita.length; i++) {
							s_r.push(obj.rosita[i].rosita);
							c_r.push(obj.rosita[i].calificacion_r);
						}

						for (var i = 0; i < obj.barranca.length; i++) {
							s_ba.push(obj.barranca[i].barranca);
							c_ba.push(obj.barranca[i].calificacion_ba);
						}

						for (var i = 0; i < obj.bocono.length; i++) {
							s_bo.push(obj.bocono[i].bocono);
							c_bo.push(obj.bocono[i].calificacion_bo);
						}

						for (var i = 0; i < obj.general.length; i++) {
							s_ge.push(obj.general[i].general);
							c_ge.push(obj.general[i].calificacion_ge);
						}

						sedes_aux = [s_g[0],s_r[0],s_ba[0],s_bo[0],s_ge[0]];
						calificacion_aux = [c_g[0],c_r[0],c_ba[0],c_bo[0],c_ge[0]];

						console.log(calificacion_aux);

						
						var ctx = document.getElementById('graf_sedev2');
						var myChart = new Chart(ctx, {
						    type: 'bar',
						    data: {
						        labels: sedes_aux,
						        datasets: [{
						            label: 'Meta a Cumplir',
						            data: [80, 80, 80, 80,80],
						            borderColor: [
						                'rgba(255, 51, 0, 1)'
						            ],
						            backgroundColor: [
						                'rgba(255, 51, 0, 0)'
						            ],

						            // Changes this dataset to become a line
						            type: 'line'
						        },{
						            label: "Sedes",
						            data: calificacion_aux,
						            
						            backgroundColor: [
						                'rgba(255, 102, 204, 0.7)',
						                'rgba(255, 153, 0, 0.7)',
						                'rgba(102, 204, 255, 0.7)',
						                'rgba(153, 255, 102, 0.7)',
						                'rgba(102, 153, 153,0.7)'
						            ],
						            borderWidth: 2
						        }]
						    },
						    options: {
						        scales: {
						            yAxes: [{
						                ticks: {
						                    beginAtZero: true
						                }
						            }]
						        }
						    }
						});
					});
				//aca
				var desde = $("#fecha_ini").val();
				var hasta = $("#fecha_fin").val();
				var sede = $("#combo_sedes").val();
				var resulta = document.getElementById('detalle_nps_sedev2');
				var xmlhttp;
	            if (window.XMLHttpRequest) {
	                xmlhttp = new XMLHttpRequest();
	            } else {
	                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	            }
	            xmlhttp.onreadystatechange = function () {
	                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
	                    resulta.innerHTML = xmlhttp.responseText;
	                }
	            }
	            xmlhttp.open("GET", "<?=base_url()?>Informes/detalle_nps_sedes", true);
	            xmlhttp.send();
				/**/
			});

			function generar_Informe_sedes(){
				var desde = $("#fecha_ini").val();
				var hasta = $("#fecha_fin").val();
				var sede = $("#combo_sedes").val();
				var resulta = document.getElementById('detalle_nps_sedev2');
				var xmlhttp;
	            if (window.XMLHttpRequest) {
	                xmlhttp = new XMLHttpRequest();
	            } else {
	                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	            }
	            xmlhttp.onreadystatechange = function () {
	                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
	                    resulta.innerHTML = xmlhttp.responseText;
	                }
	            }
	            xmlhttp.open("GET", "<?=base_url()?>Informes/detalle_nps_sedes?desde="+desde+"&hasta="+hasta+"&sede="+sede, true);
	            xmlhttp.send();
			}
			
	</script>

	

	<script>
			$('#tec_radio').click(function(){
				//document.getElementById('mostrar_Informe').style.display = "none";
				document.getElementById('mostrar_Informe_tecnicos_v2').style.display = "block";
				document.getElementById('mostrar_Informe_sedesv2').style.display = "none";
				//alert("hola");
				//var desde_tec = $('#desde_tec').val();
				//var hasta_tec = $('#hasta_tec').val();
				//var tecnico = $('#combo_tec').val();
				$.post("<?=base_url()?>Informes/Informe_nps_tecnico",
					function(data){

						var obj = JSON.parse(data);
						console.log(obj);
						console.log(obj.barranca);
						console.log(obj.barranca[1]);
						console.log(obj.barranca[1][0].nom_tec);
						var s_g = [];
						var s_r = [];
						var s_ba = [];
						var s_bo = [];

						var t_r = [];
						var t_g = [];
						var t_ba = [];
						var t_bo = [];
						//console.log(obj);

						/*FOR DE GIRON*/
						for (var i = 0; i < obj.giron.length; i++) {
							s_g.push(obj.giron[i][0].nom_tec);
							t_g.push(obj.giron[i][0].calificacion);
						}

						/*FOR DE ROSITA*/
						for (var i = 0; i < obj.rosita.length; i++) {
							s_r.push(obj.rosita[i][0].nom_tec);
							t_r.push(obj.rosita[i][0].calificacion);
						}

						/*FOR DE BOCONO*/
						for (var i = 0; i < obj.bocono.length; i++) {
							s_bo.push(obj.bocono[i][0].nom_tec);
							t_bo.push(obj.bocono[i][0].calificacion);
						}

						/*FOR DE BARRANCA*/
						for (var i = 0; i < obj.barranca.length; i++) {
							s_ba.push(obj.barranca[i][0].nom_tec);
							t_ba.push(obj.barranca[i][0].calificacion);
						}
						
						//GRAFICA GIRON
						var ctx = document.getElementById('graf_tec_g');
						var myChart = new Chart(ctx, {
						    type: 'bar',
						    data: {
						        labels: s_g,
						        datasets: [{
						            label: 'Meta a Cumplir',
						            data: [8,8],
						            borderColor: [
						                'rgba(255, 51, 0, 1)'
						            ],
						            backgroundColor: [
						                'rgba(255, 51, 0, 0)'
						            ],

						            // Changes this dataset to become a line
						            type: 'line'
						        },{
						            label: "Tecnicos",
						            data: t_g,
						            
						            backgroundColor: [
						                'rgba(255, 102, 204, 0.7)',
						                'rgba(255, 153, 0, 0.7)',
						                'rgba(102, 204, 255, 0.7)',
						                'rgba(153, 255, 102, 0.7)',
						                'rgba(102, 153, 153,0.7)'
						            ],
						            borderWidth: 2
						        }]
						    },
						    options: {
						        scales: {
						            yAxes: [{
						                ticks: {
						                    beginAtZero: true
						                }
						            }]
						        }
						    }
						});

						//GRAFICA BOCONO
						var ctx = document.getElementById('graf_tec_bo');
						var myChart = new Chart(ctx, {
						    type: 'bar',
						    data: {
						        labels: s_bo,
						        datasets: [{
						            label: 'Meta a Cumplir',
						            data: [8,8],
						            borderColor: [
						                'rgba(255, 51, 0, 1)'
						            ],
						            backgroundColor: [
						                'rgba(255, 51, 0, 0)'
						            ],

						            // Changes this dataset to become a line
						            type: 'line'
						        },{
						            label: "Tecnicos",
						            data: t_bo,
						            
						            backgroundColor: [
						                'rgba(255, 102, 204, 0.7)',
						                'rgba(255, 153, 0, 0.7)',
						                'rgba(102, 204, 255, 0.7)',
						                'rgba(153, 255, 102, 0.7)',
						                'rgba(102, 153, 153,0.7)'
						            ],
						            borderWidth: 2
						        }]
						    },
						    options: {
						        scales: {
						            yAxes: [{
						                ticks: {
						                    beginAtZero: true
						                }
						            }]
						        }
						    }
						});

						//GRAFICA ROSITA
						var ctx = document.getElementById('graf_tec_r');
						var myChart = new Chart(ctx, {
						    type: 'bar',
						    data: {
						        labels: s_r,
						        datasets: [{
						            label: 'Meta a Cumplir',
						            data: [8,8],
						            borderColor: [
						                'rgba(255, 51, 0, 1)'
						            ],
						            backgroundColor: [
						                'rgba(255, 51, 0, 0)'
						            ],

						            // Changes this dataset to become a line
						            type: 'line'
						        },{
						            label: "Tecnicos",
						            data: t_r,
						            
						            backgroundColor: [
						                'rgba(255, 102, 204, 0.7)',
						                'rgba(255, 153, 0, 0.7)',
						                'rgba(102, 204, 255, 0.7)',
						                'rgba(153, 255, 102, 0.7)',
						                'rgba(102, 153, 153,0.7)'
						            ],
						            borderWidth: 2
						        }]
						    },
						    options: {
						        scales: {
						            yAxes: [{
						                ticks: {
						                    beginAtZero: true
						                }
						            }]
						        }
						    }
						});

						//GRAFICA BARRANCA
						var ctx = document.getElementById('graf_tec_ba');
						var myChart = new Chart(ctx, {
						    type: 'bar',
						    data: {
						        labels: s_ba,
						        datasets: [{
						            label: 'Meta a Cumplir',
						            data: [8,8],
						            borderColor: [
						                'rgba(255, 51, 0, 1)'
						            ],
						            backgroundColor: [
						                'rgba(255, 51, 0, 0)'
						            ],

						            // Changes this dataset to become a line
						            type: 'line'
						        },{
						            label: "Tecnicos",
						            data: t_ba,
						            
						            backgroundColor: [
						                'rgba(255, 102, 204, 0.7)',
						                'rgba(255, 153, 0, 0.7)',
						                'rgba(102, 204, 255, 0.7)',
						                'rgba(153, 255, 102, 0.7)',
						                'rgba(102, 153, 153,0.7)'
						            ],
						            borderWidth: 2
						        }]
						    },
						    options: {
						        scales: {
						            yAxes: [{
						                ticks: {
						                    beginAtZero: true
						                }
						            }]
						        }
						    }
						});
					});	
					//TABLA GIRON
					var t_giron = document.getElementById('tabla_tec_g');
					var xmlhttp1;
		            if (window.XMLHttpRequest) {
		                xmlhttp1 = new XMLHttpRequest();
		            } else {
		                xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
		            }
		            xmlhttp1.onreadystatechange = function () {
		                if (xmlhttp1.readyState === 4 && xmlhttp1.status === 200) {
		                    t_giron.innerHTML = xmlhttp1.responseText;
		                }
		            }
		            xmlhttp1.open("GET", "<?=base_url()?>Informes/detalle_nps_tecnico?sede=giron", true);
		            xmlhttp1.send();

		            //TABLA ROSITA
					var t_rosita = document.getElementById('tabla_tec_r');
					var xmlhttp2;
		            if (window.XMLHttpRequest) {
		                xmlhttp2 = new XMLHttpRequest();
		            } else {
		                xmlhttp2 = new ActiveXObject("Microsoft.XMLHTTP");
		            }
		            xmlhttp2.onreadystatechange = function () {
		                if (xmlhttp2.readyState === 4 && xmlhttp2.status === 200) {
		                    t_rosita.innerHTML = xmlhttp2.responseText;
		                }
		            }
		            xmlhttp2.open("GET", "<?=base_url()?>Informes/detalle_nps_tecnico?sede=rosita", true);
		            xmlhttp2.send();

		            //TABLA BARRANCA
					var t_barranca = document.getElementById('tabla_tec_ba');
					var xmlhttp3;
		            if (window.XMLHttpRequest) {
		                xmlhttp3 = new XMLHttpRequest();
		            } else {
		                xmlhttp3 = new ActiveXObject("Microsoft.XMLHTTP");
		            }
		            xmlhttp3.onreadystatechange = function () {
		                if (xmlhttp3.readyState === 4 && xmlhttp3.status === 200) {
		                    t_barranca.innerHTML = xmlhttp3.responseText;
		                }
		            }
		            xmlhttp3.open("GET", "<?=base_url()?>Informes/detalle_nps_tecnico?sede=barranca", true);
		            xmlhttp3.send();

		            //TABLA BOCONO
					var t_bocono = document.getElementById('tabla_tec_bo');
					var xmlhttp4;
		            if (window.XMLHttpRequest) {
		                xmlhttp4 = new XMLHttpRequest();
		            } else {
		                xmlhttp4 = new ActiveXObject("Microsoft.XMLHTTP");
		            }
		            xmlhttp4.onreadystatechange = function () {
		                if (xmlhttp4.readyState === 4 && xmlhttp4.status === 200) {
		                    t_bocono.innerHTML = xmlhttp4.responseText;
		                }
		            }
		            xmlhttp4.open("GET", "<?=base_url()?>Informes/detalle_nps_tecnico?sede=bocono", true);
		            xmlhttp4.send();
				});	
				//aca
				/*var resulta = document.getElementById('detalle_nps_tecnico');
				var xmlhttp;
	            if (window.XMLHttpRequest) {
	                xmlhttp = new XMLHttpRequest();
	            } else {
	                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	            }
	            xmlhttp.onreadystatechange = function () {
	                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
	                    resulta.innerHTML = xmlhttp.responseText;
	                }
	            }
	            xmlhttp.open("GET", "<?=base_url()?>Informes/detalle_nps_tecnico?desde="+desde_tec+"&hasta="+hasta_tec+"&tecnico="+tecnico, true);
	            xmlhttp.send();*/

			
	</script>
	<script type="text/javascript">
		/*$('#chk_t22').click(function(){
				document.getElementById('mostrar_Informe_sedesv2').style.display = "block";
				document.getElementById('mostrar_Informe').style.display = "none";
				document.getElementById('mostrar_Informe_tecnicos').style.display = "none";

				document.getElementById('rep_giron').style.display = "none";
			    document.getElementById('rep_rosita').style.display = "none";
			    document.getElementById('rep_barranca').style.display = "none";
			    document.getElementById('rep_bocono').style.display = "none";
						$.post("<?=base_url()?>Informes/Informe_nps_sedes",
						function(data1){
							var obj1 = JSON.parse(data1);
							var s_g = [];
							var c_g = [];
							var s_r = [];
							var c_r = [];
							var s_ba = [];
							var c_ba = [];
							var s_bo = [];
							var c_bo = [];
							var s_ge = [];
							var c_ge = [];
							console.log(obj1);
						/*for (var i = 0; i < obj1.giron.length; i++) {
			  				f_g.push(obj1.giron[i].fecha_g );
			  				c_g.push(obj1.giron[i].calificacion_g);
			  			}

							var data1 ={
								label: "Calificacion Giron",
								data:c_g,
							            borderColor: [
							                'rgba(255, 99, 132, 1)',
							                'rgba(54, 162, 235, 1)',
							                'rgba(255, 206, 86, 1)',
							                'rgba(75, 192, 192, 1)',
							                'rgba(153, 102, 255, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)'
							            ],
							            borderWidth: 2
							}
							
							var datat ={
								labels: f_g,
								datasets: [data1]
							}*/
							/*var ctx = document.getElementById('graf_giron');
							var myChart = new Chart(ctx, {
							    type: 'line',
							    data: datat,
							    options: {
							        scales: {
							            yAxes: [{
							                ticks: {
							                    beginAtZero: true
							                }
							            }]
							        }
							    }
							});*/
							/*var tab_giron = document.getElementById('tab_giron');
							var xmlhttp;
				            if (window.XMLHttpRequest) {
				                xmlhttp = new XMLHttpRequest();
				            } else {
				                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				            }
				            xmlhttp.onreadystatechange = function () {
				                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				                    tab_giron.innerHTML = xmlhttp.responseText;
				                }
				            }
				            xmlhttp.open("GET", "<?=base_url()?>Informes/detalle_nps_sedes?desde="+desde_sede+"&hasta="+hasta_sede+"&sede=giron", true);
				            xmlhttp.send();*/
						
				//	}/*else if (sedes[x] == "La Rosita") {
		  				/*document.getElementById('rep_rosita').style.display = "flex";
						$.post("<?=base_url()?>Informes/Informe_nps_sedes?des_sede="+desde_sede+"&has_sede="+hasta_sede+"&sedes=La Rosita",
						function(data1){
							var obj1 = JSON.parse(data1);
							var f_g = [];
							var c_g = [];
							//console.log(obj1.giron);
						for (var i = 0; i < obj1.rosita.length; i++) {
			  				f_g.push(obj1.rosita[i].fecha_r );
			  				c_g.push(obj1.rosita[i].calificacion_r);
			  			}

							var data1 ={
								label: "Calificacion Rosita",
								data:c_g,
							            borderColor: [
							                'rgba(255, 99, 132, 1)',
							                'rgba(54, 162, 235, 1)',
							                'rgba(255, 206, 86, 1)',
							                'rgba(75, 192, 192, 1)',
							                'rgba(153, 102, 255, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)'
							            ],
							            borderWidth: 2
							}
							
							var datat ={
								labels: f_g,
								datasets: [data1]
							}
							var ctx = document.getElementById('graf_rosita');
							var myChart = new Chart(ctx, {
							    type: 'line',
							    data: datat,
							    options: {
							        scales: {
							            yAxes: [{
							                ticks: {
							                    beginAtZero: true
							                }
							            }]
							        }
							    }
							});
							var tab_rosita = document.getElementById('tab_rosita');
							var xmlhttp;
				            if (window.XMLHttpRequest) {
				                xmlhttp = new XMLHttpRequest();
				            } else {
				                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				            }
				            xmlhttp.onreadystatechange = function () {
				                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				                    tab_rosita.innerHTML = xmlhttp.responseText;
				                }
				            }
				            xmlhttp.open("GET", "<?=base_url()?>Informes/detalle_nps_sedes?desde="+desde_sede+"&hasta="+hasta_sede+"&sede=rosita", true);
				            xmlhttp.send();
						});
						
					}else if (sedes[x] == "Barrancabermeja") {
					    document.getElementById('rep_barranca').style.display = "flex";
						$.post("<?=base_url()?>Informes/Informe_nps_sedes?des_sede="+desde_sede+"&has_sede="+hasta_sede+"&sedes=Barrancabermeja",
						function(data1){
							var obj1 = JSON.parse(data1);
							var f_g = [];
							var c_g = [];
							//console.log(obj1.giron);
						for (var i = 0; i < obj1.barranca.length; i++) {
			  				f_g.push(obj1.barranca[i].fecha_ba );
			  				c_g.push(obj1.barranca[i].calificacion_ba);
			  			}

							var data1 ={
								label: "Calificacion Barrancabermeja",
								data:c_g,
							            borderColor: [
							                'rgba(255, 99, 132, 1)',
							                'rgba(54, 162, 235, 1)',
							                'rgba(255, 206, 86, 1)',
							                'rgba(75, 192, 192, 1)',
							                'rgba(153, 102, 255, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)'
							            ],
							            borderWidth: 2
							}
							
							var datat ={
								labels: f_g,
								datasets: [data1]
							}
							var ctx = document.getElementById('graf_barranca');
							var myChart = new Chart(ctx, {
							    type: 'line',
							    data: datat,
							    options: {
							        scales: {
							            yAxes: [{
							                ticks: {
							                    beginAtZero: true
							                }
							            }]
							        }
							    }
							});
							var tab_barranca = document.getElementById('tab_barranca');
							var xmlhttp;
				            if (window.XMLHttpRequest) {
				                xmlhttp = new XMLHttpRequest();
				            } else {
				                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				            }
				            xmlhttp.onreadystatechange = function () {
				                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				                    tab_barranca.innerHTML = xmlhttp.responseText;
				                }
				            }
				            xmlhttp.open("GET", "<?=base_url()?>Informes/detalle_nps_sedes?desde="+desde_sede+"&hasta="+hasta_sede+"&sede=barranca", true);
				            xmlhttp.send();
						});
						
					}else if(sedes[x] == "Cucuta Bocono"){
		                document.getElementById('rep_bocono').style.display = "flex";
						$.post("<?=base_url()?>Informes/Informe_nps_sedes?des_sede="+desde_sede+"&has_sede="+hasta_sede+"&sedes=Cucuta Bocono",
						function(data1){
							var obj1 = JSON.parse(data1);
							var f_g = [];
							var c_g = [];
							//console.log(obj1.giron);
						for (var i = 0; i < obj1.bocono.length; i++) {
			  				f_g.push(obj1.bocono[i].fecha_bo);
			  				c_g.push(obj1.bocono[i].calificacion_bo);
			  			}

							var data1 ={
								label: "Calificacion Bocono",
								data:c_g,
							            borderColor: [
							                'rgba(255, 99, 132, 1)',
							                'rgba(54, 162, 235, 1)',
							                'rgba(255, 206, 86, 1)',
							                'rgba(75, 192, 192, 1)',
							                'rgba(153, 102, 255, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)',
							                'rgba(255, 159, 64, 1)',
							                'rgba(153, 159, 64, 1)'
							            ],
							            borderWidth: 2
							}
							
							var datat ={
								labels: f_g,
								datasets: [data1]
							}
							var ctx = document.getElementById('graf_bocono');
							var myChart = new Chart(ctx, {
							    type: 'line',
							    data: datat,
							    options: {
							        scales: {
							            yAxes: [{
							                ticks: {
							                    beginAtZero: true
							                }
							            }]
							        }
							    }
							});
							var tab_bocono = document.getElementById('tab_bocono');
							var xmlhttp;
				            if (window.XMLHttpRequest) {
				                xmlhttp = new XMLHttpRequest();
				            } else {
				                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				            }
				            xmlhttp.onreadystatechange = function () {
				                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				                    tab_bocono.innerHTML = xmlhttp.responseText;
				                }
				            }
				            xmlhttp.open("GET", "<?=base_url()?>Informes/detalle_nps_sedes?desde="+desde_sede+"&hasta="+hasta_sede+"&sede=bocono", true);
				            xmlhttp.send();
						});
						
					}*/
			//	}
			//	
				
			//});
	</script>
	<script type="text/javascript">
		var tableToExcel = (function() {
		var uri = 'data:application/vnd.ms-excel;base64,'
		    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
		    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
		    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
		  return function(table, name) {
		    if (!table.nodeType) table = document.getElementById(table)
		    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
		    window.location.href = uri + base64(format(template, ctx))
		  }
		})()
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