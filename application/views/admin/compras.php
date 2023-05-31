<div class="container-fluid" align="center">
	<!--<img src="<?=base_url()?>media/logo/logo_codiesel_sinfondo.png" class="img-fluid" alt="Responsive image">-->
	<div class="card">
	   <div class="card-body">
	   	<h3>Resumen Solicitudes</h3>
		    <div class="row">
		    	<div class="col-md-4">
		    		<div class="info-box">
		              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-shopping-cart"></i></span>

		              <div class="info-box-content">
		                <span class="info-box-text">Solicitudes Pendientes</span>
		                <span class="info-box-number" id="soli_pen">
		                  10
		                  <small>%</small>
		                </span>
		              </div>
		              <!-- /.info-box-content -->
		            </div>
		    	</div>
		    	<div class="col-md-4">
		    		<div class="info-box">
		              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-shopping-cart"></i></span>

		              <div class="info-box-content">
		                <span class="info-box-text">Solicitudes en Proceso</span>
		                <span class="info-box-number" id="soli_pro">
		                  10
		                  <small>%</small>
		                </span>
		              </div>
		              <!-- /.info-box-content -->
		            </div>
		    	</div>
		    	<div class="col-md-4">
		    		<div class="info-box">
		              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

		              <div class="info-box-content">
		                <span class="info-box-text">Solicitudes Finalizadas</span>
		                <span class="info-box-number" id="soli_fin">
		                  10
		                  <small>%</small>
		                </span>
		              </div>
		              <!-- /.info-box-content -->
		            </div>
		    	</div>
		    </div>
	   </div>
	</div>
</div>
<script type="text/javascript">
			load_solicitudes_pendientes();
			load_solicitudes_finalizadas();
			load_solicitudes_en_proceso();
	function load_solicitudes_pendientes() {
		var result = document.getElementById("soli_pen");
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				result.innerHTML = xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>compras/get_can_solicitudes?tipo=1", true);
		xmlhttp.send();
	}
	function load_solicitudes_en_proceso() {
		var result = document.getElementById("soli_pro");
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				result.innerHTML = xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>compras/get_can_solicitudes?tipo=2", true);
		xmlhttp.send();
	}
	function load_solicitudes_finalizadas() {
		var result = document.getElementById("soli_fin");
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				result.innerHTML = xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>compras/get_can_solicitudes?tipo=3", true);
		xmlhttp.send();
	}
</script>