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
				<a class="btn btn-primary" href="<?= base_url() ?>login/logout">Si</a>
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
if ($log == "err_p") {
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
<script src="<?= base_url() ?>plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= base_url() ?>plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
	$.widget.bridge('uibutton', $.ui.button)
</script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="<?= base_url() ?>plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="<?= base_url() ?>plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?= base_url() ?>plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?= base_url() ?>plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?= base_url() ?>plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?= base_url() ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url() ?>plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= base_url() ?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?= base_url() ?>plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url() ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= base_url() ?>dist/js/pages/dashboard.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://rawgit.com/unconditional/jquery-table2excel/master/src/jquery.table2excel.js"></script>
<!-- SweetAlert2 -->
<script src="<?= base_url() ?>plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="<?= base_url() ?>plugins/toastr/toastr.min.js"></script>

<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script type="text/javascript">
	$(document).ready(function() {
		$("#buscar_items").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#menu_items li").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		document.getElementById("btn_excel").setAttribute("disabled", "true");
		$('.combo2').select2({
			theme: "classic",
			width: "100%"
		});
	});
</script>
<script type="text/javascript">
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 5000
	});

	function generar_nomina_tecnicos() {
		var mes = document.getElementById("mes").value;
		document.getElementById("cargando").style.display = "block";
		document.body.style.cursor = 'wait';
		if (mes != "") {
			var result = document.getElementById("tabla_nomina_tec");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {	
					var resp = xmlhttp.responseText;
					if (resp == "err") {
						Toast.fire({
									type: 'error',
									title: ' No se puede cargar esta fecha'
								});
						document.getElementById("cargando").style.display = "none";
						document.getElementById("btn_excel").disabled = false;
						document.body.style.cursor = 'default';
					}else{
						result.innerHTML = xmlhttp.responseText;
						document.getElementById('inf_tec').style.display = "block";
						document.getElementById("cargando").style.display = "none";
						document.body.style.cursor = 'default';
						document.getElementById("btn_excel").disabled = false;
					}	
					
				}
			}
			xmlhttp.open("GET", "<?= base_url() ?>nomina/load_nomina_tecnicos?mes=" + mes, true);
			xmlhttp.send();
		}else{
			Toast.fire({
				type: 'error',
				title: ' Debes escoger un mes y un año'
			});
			document.getElementById("cargando").style.display = "none";
		}
	}

	function mostrar_rep_giron(fecha_ini,fecha_fin) {
        var result = document.getElementById("to_giron");
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    result.innerHTML = xmlhttp.responseText;
                    document.getElementById("cargando").style.display = "none";
                }
            }
            xmlhttp.open("GET", "<?= base_url() ?>nomina/get_to_rep?desde="+fecha_ini+"&hasta="+fecha_fin+"&sede=1", true);
            xmlhttp.send();
    }
    function mostrar_rep_bocono(fecha_ini,fecha_fin) {
        var result = document.getElementById("to_bocono");
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    result.innerHTML = xmlhttp.responseText;
                    document.getElementById("cargando").style.display = "none";
                }
            }
            xmlhttp.open("GET", "<?= base_url() ?>nomina/get_to_rep?desde="+fecha_ini+"&hasta="+fecha_fin+"&sede=2", true);
            xmlhttp.send();
    }

	function generar_nomina() {
		
		var desde = document.getElementById("desde").value;
		var hasta = document.getElementById("hasta").value;
		var combo_inf = document.getElementById('combo_inf').value;
		document.getElementById("cargando").style.display = "block";
		document.body.style.cursor = 'wait';
		if (desde < hasta || (desde != "" && hasta != "")) {
			if (combo_inf != "") {
				//Informe nuevo
				if (combo_inf == 1) {
					var result = document.getElementById("tabla_nomina_nueva");
					var xmlhttp;
					if (window.XMLHttpRequest) {
						xmlhttp = new XMLHttpRequest();
					} else {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {	
							var resp = xmlhttp.responseText;
							if (resp == "No tiene permisos para ver este Informe") {
								document.getElementById("cargando").style.display = "none";
								Toast.fire({
									type: 'error',
									title: ' No tiene permisos para ver este Informe'
								});
							}else{
								result.innerHTML = xmlhttp.responseText;
								document.getElementById('inf_nuevo').style.display = "block";
								document.getElementById('btn_excel_nvo').style.display = "block";
								document.getElementById('btn_excel').style.display = "none";
								document.getElementById('inf_viejo').style.display = "none";
								document.getElementById("cargando").style.display = "none";
								document.body.style.cursor = 'default';
								document.getElementById("btn_excel").disabled = false;
								mostrar_rep_giron(desde,hasta);
								mostrar_rep_bocono(desde,hasta);
							}
							//alert("ok");
							document.getElementById('inf_nuevo').style.display = "block";
							document.getElementById('inf_viejo').style.display = "none";
							document.getElementById("cargando").style.display = "none";
							document.body.style.cursor = 'default';
							document.getElementById("btn_excel").disabled = false;
						}
					}
					xmlhttp.open("GET", "<?= base_url() ?>nomina/load_nomina_nuevo?desde=" + desde + "&hasta=" + hasta, true);
					xmlhttp.send();
					//Informe viejo
				}else if(combo_inf == 2){
					var result = document.getElementById("tabla_nomina");
					var xmlhttp;
					if (window.XMLHttpRequest) {
						xmlhttp = new XMLHttpRequest();
					} else {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
							result.innerHTML = xmlhttp.responseText;
							//alert("ok");
							document.getElementById('btn_excel_nvo').style.display = "none";
							document.getElementById('btn_excel').style.display = "block";
							document.getElementById('inf_viejo').style.display = "block";
							document.getElementById('inf_nuevo').style.display = "none";
							document.getElementById("cargando").style.display = "none";
							document.body.style.cursor = 'default';
							document.getElementById("btn_excel").disabled = false;
						}
					}
					xmlhttp.open("GET", "<?= base_url() ?>nomina/load_nomina?desde=" + desde + "&hasta=" + hasta, true);
					xmlhttp.send();
				}
				
			}else{
				Toast.fire({
					type: 'warning',
					title: ' Debes seleccionar un tipo de Informe'
				});
				document.getElementById("cargando").style.display = "none";
			}
			
		} else {
			Toast.fire({
				type: 'error',
				title: ' La rango de fechas incorrecto'
			});
			document.getElementById("cargando").style.display = "none";
		}

	}

	function generar_nomina_nit() {
		var result = document.getElementById("tabla_nomina");
		var desde = document.getElementById("desde").value;
		var hasta = document.getElementById("hasta").value;
		document.getElementById("cargando").style.display = "block";
		document.body.style.cursor = 'wait';
		if (desde < hasta) {
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					result.innerHTML = xmlhttp.responseText;
					//alert("ok");
					document.getElementById("cargando").style.display = "none";
					document.body.style.cursor = 'default';
					document.getElementById("btn_excel").disabled = false;
				}
			}
			xmlhttp.open("GET", "<?= base_url() ?>nomina/load_nomina_nit?desde=" + desde + "&hasta=" + hasta, true);
			xmlhttp.send();
		} else {
			Toast.fire({
				type: 'error',
				title: ' La rango de fechas incorrecto'
			});
		}

	}

	function generar_nomina_jefe() {
		var mes = document.getElementById("mes").value;
		document.getElementById("cargando").style.display = "block";
		document.body.style.cursor = 'wait';
		if (mes != "") {
			var result = document.getElementById("tabla_nomina_jefe");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {	
					
					result.innerHTML = xmlhttp.responseText;
					document.getElementById('inf_jefe').style.display = "block";
					document.getElementById("cargando").style.display = "none";
					document.body.style.cursor = 'default';
					document.getElementById("btn_excel").disabled = false;
					
				}
			}
			xmlhttp.open("GET", "<?= base_url() ?>nomina/load_nomina_jefe?mes=" + mes, true);
			xmlhttp.send();
		}else{
			Toast.fire({
				type: 'error',
				title: ' Debes escoger un mes y un año'
			});
			document.getElementById("cargando").style.display = "none";
		}
	}

	function generar_nomina_dir_flota() {
		var mes = document.getElementById("mes").value;
		document.getElementById("cargando").style.display = "block";
		document.body.style.cursor = 'wait';
		if (mes != "") {
			var result = document.getElementById("tabla_nomina_dir_flota");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.open("POST", "<?= base_url() ?>nomina/load_nomina_dir_flota", true);
			var params = "mes=" + mes;
			xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {	
					
					result.innerHTML = xmlhttp.responseText;
					document.getElementById('inf_dir_flota').style.display = "block";
					document.getElementById("cargando").style.display = "none";
					document.body.style.cursor = 'default';
					document.getElementById("btn_excel").disabled = false;
					
				}
			}
			xmlhttp.send(params);
		}else{
			Toast.fire({
				type: 'error',
				title: ' Debes escoger un mes y un año'
			});
			document.getElementById("cargando").style.display = "none";
		}
	}
</script>
<script type="text/javascript">
	function exportTableToExcel(tableID, filename = '') {
		var downloadLink;
		var dataType = 'application/vnd.ms-excel';
		var tableSelect = document.getElementById(tableID);
		var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

		// Specify file name
		filename = filename ? filename + '.xls' : 'excel_data.xls';

		// Create download link element
		downloadLink = document.createElement("a");

		document.body.appendChild(downloadLink);

		if (navigator.msSaveOrOpenBlob) {
			var blob = new Blob(['ufeff', tableHTML], {
				type: dataType
			});
			navigator.msSaveOrOpenBlob(blob, filename);
		} else {
			// Create a link to the file
			downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

			// Setting the file name
			downloadLink.download = filename;

			//triggering the function
			downloadLink.click();
		}
	}

</script>
<script type="text/javascript">
  function ResultsToTable(nom_inf){    
        $("#example1").table2excel({
            exclude: ".noExl",
            name: "Results",
            filename: nom_inf
        });
    }
    function ResultsToTable2(nom_inf){    
        $("#example2").table2excel({
            exclude: ".noExl",
            name: "Results",
            filename: nom_inf
        });
    }
    function ResultsToTable3(nom_inf){    
        $("#example3").table2excel({
            exclude: ".noExl",
            name: "Results",
            filename: nom_inf
        });
    }
    function ResultsToTable4(nom_inf){    
        $("#example4").table2excel({
            exclude: ".noExl",
            name: "Results",
            filename: nom_inf
        });
    }
    function ResultsToTable5(nom_inf){    
        $("#example5").table2excel({
            exclude: ".noExl",
            name: "Results",
            filename: nom_inf
        });
    }
    function ResultsToTable6(nom_inf){    
        $("#example6").table2excel({
            exclude: ".noExl",
            name: "Results",
            filename: nom_inf
        });
    }
</script>


<!---------------------------------------------------------inicion de funcion el manejo de comion de asesor LyT-------------------------->
<script>
	//funcion para validar y enviar campos del de la culsta comision de asesores lyt.  Andres Gomez .16-09-21
	function getval(sel) {
		var mes = document.getElementById('mes').value;
		var ano = document.getElementById('ano').value;
		var dto = document.getElementById('datos_nomina');
		document.getElementById("cargando").style.display = "block";

		var xmlhttp1;
		if (window.XMLHttpRequest) {
			xmlhttp1 = new XMLHttpRequest();
		} else {
			xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp1.onreadystatechange = function() {
			if (xmlhttp1.readyState === 4 && xmlhttp1.status === 200) {
				var flag1 = xmlhttp1.responseText;
				if (flag1 == "") {
					Toast.fire({
						type: 'error',
						title: ' No hay datos'
					});
				} else {
					dto.innerHTML = xmlhttp1.responseText;
					document.getElementById("cargando").style.display = "none";
					document.getElementById('datos_filtro').style.display = "none";

				}
			}
		}

		xmlhttp1.open("GET", "<?= base_url() ?>nomina/consulta_typ?mes=" + mes + "&ano=" + ano, true);
		xmlhttp1.send();
	}

	function filtrolyp() {
		var campofiltro = document.getElementById('datos_filtro');
		document.getElementById("cargando").style.display = "block";
		//definir ruta
		var url = '<?= base_url() ?>nomina/Resultadofiltro_typ';
		//definir el id del formulario para recoger los datos
		var formulario = document.getElementById("formulario_lyt");
		//crear objeto de la clase XMLHttpRequest
		var request = new XMLHttpRequest();
		//valioras respuesta de la peticion HTTP
		request.onreadystatechange = function() {
			if (request.readyState === 4 && request.status === 200) {
				//pinta datos en la vista por medio de innerhtml 
				campofiltro.innerHTML = request.responseText;
				campofiltro.style.display = "block";
				document.getElementById("cargando").style.display = "none";
			}
		}
		//definiendo metodo y ruta
		request.open("POST", url);
		//envar los dattos creando un objeto de clse fordata del formulario
		request.send(new FormData(formulario));
	}
</script>

<script type="text/javascript">
	//funcion para convetir tabla en documento de excel

	var f = new Date();
	fecha = f.getDate() + "-" + (f.getMonth() + 1) + "-" + f.getFullYear();

	function ver_excel() {
		$("#tnl").table2excel({
			exclude: ".noExl",
			name: "Worksheet Name",
			filename: "Informe-asesor-LYP-" + "-" + fecha, //do not include extension
			fileext: ".xlsx" // file extension
		});
	}
</script>


<!--datatable par aver filtro de nomina lyo-->

<script>
	window.onload="myFunction()";{
		$('#filtronominalypd').DataTable({
			"paging": false,
			"scrollX": true,
			"scrollY": "200px",
			"pageLength": 25,
			"lengthChange": true,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": true,
			"language": {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_ registros",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla =(",
				"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
				"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix": "",
				"sSearch": "Buscar:",
				"sUrl": "",
				"sInfoThousands": ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst": "Primero",
					"sLast": "Último",
					"sNext": "Siguiente",
					"sPrevious": "Anterior"
				},
				"oAria": {
					"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				},
				"buttons": {
					"copy": "Copiar",
					"colvis": "Visibilidad"
				}
			}

		});
	};
</script>



<script src="<?= base_url() ?>dist/js/demo.js"></script>
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