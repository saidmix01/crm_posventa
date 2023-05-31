<?php $this->load->view('administracion/header') ?>
	  <!-- Content Wrapper. Contains page content -->
	  <div class="content-wrapper">
	   <br>
	    <!-- Main content -->
	    <section class="content">
	        <div class="card">
			  <div class="card-body">
			  	<div id="divBtnModal" class="row mb-3">
			  		<div class="col-md-12">
			  			<a id="btnModal" href="#" class="btn btn-success" onclick="mostrarModalNewAusentismo();">Nuevo Ausentismo</a>
			  		</div>
			  	</div>
			    <div class="row">
			    	<div class="col-md-12">
			    		<div class="card card-primary">
			              <div class="card-body p-0">
			                <div class="row container" align="center">
			                	<div class="col-md-12">
			                		<!-- THE CALENDAR -->
			                		<div id="calendar"></div>
			                	</div>
			                </div>
			              </div>
			              <!-- /.card-body -->
			            </div>
			    	</div>
			    </div>
			  </div>
			</div>
	    </section>
	    <!-- /.content -->
	<input type="hidden" name="diaActualEsFestivo" id="diaActualEsFestivo" value="<?=$dia_actual?>">
	<input type="hidden" name="fecha_actual" id="fecha_actual" value="<?=$fecha_actual?>">
	  </div>
	  
<?php 
$data = array('tiempoAusen' => $tiempoAusen);
	$this->load->view('administracion/footer',$data)
?>


