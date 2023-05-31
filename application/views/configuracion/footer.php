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
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
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


<!-- jQuery -->
<script src="<?= base_url() ?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jQuery UI -->
<script src="<?= base_url() ?>plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>dist/js/adminlte.min.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- SweetAlert2 -->
<script src="<?= base_url() ?>plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="<?= base_url() ?>plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- overlayScrollbars -->
<script src="<?=base_url()?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$("#buscar_items").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#menu_items li").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
			});
		});

		$('.js-example-basic-single').select2({
			theme: "classic"
		});


	});
</script>

<script>
	$('#example1').DataTable({
		"paging": true,
		"pageLength": 25,
		"lengthChange": true,
		"searching": true,
		"ordering": false,
		"info": false,
		"autoWidth": false,
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

	$('.js-example-basic-single').select2({
		theme: "classic"
	});
</script>

<script type="text/javascript">
	function consultar_submenu_data(id) {
		var result2 = document.getElementById("modal_edit");
		//alert(id);
		const str = document.getElementById("nombreSM").value;
		var menu_id = document.getElementById("Select1").value;
		var vista = document.getElementById("rutaVistaSM").value;
		var icono = document.getElementById("iconoSM").value;

		var xmlhttp2;
		if (window.XMLHttpRequest) {
			xmlhttp2 = new XMLHttpRequest();
		} else {
			xmlhttp2 = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp2.onreadystatechange = function() {
			if (xmlhttp2.readyState === 4 && xmlhttp2.status === 200) {
				var flag2 = xmlhttp2.responseText;
				if (flag2 == "") {
					alert('No se insertaron los datos!');
				} else {
					result2.innerHTML = xmlhttp2.responseText;
				}


			}
		}
		xmlhttp2.open("GET", "<?= base_url() ?>sub_menu/get_submenu_data?idSubmenu=" + id, true);
		xmlhttp2.send();

	}

	function insert_submenu_data() {
		const str = document.getElementById("nombreSM").value;
		var menu_id = document.getElementById("Select1").value;
		var vista = document.getElementById("rutaVistaSM").value;
		var icono = document.getElementById("iconoSM").value;

		const submenu = str.charAt(0).toUpperCase() + str.slice(1);

		if (submenu == "" || menu_id == "" || vista == "" || icono == "") {
			Swal.fire(
				'Oops...',
				'Por favor llene todos los campos!',
				'error'
			);
		} else {
			var xmlhttp2;
			if (window.XMLHttpRequest) {
				xmlhttp2 = new XMLHttpRequest();
			} else {
				xmlhttp2 = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp2.onreadystatechange = function() {
				if (xmlhttp2.readyState === 4 && xmlhttp2.status === 200) {
					var flag2 = xmlhttp2.responseText;
					if (flag2 == "") {
						Swal.fire(
							'Oops...',
							'No se insertaron los datos!',
							'error'
						);
					} else {
						Swal.fire(
							'!Genial!',
							xmlhttp2.responseText,
							'sucess'
						);
						//result2.innerHTML = xmlhttp2.responseText;
					}


				}
			}
			xmlhttp2.open("GET", "<?= base_url() ?>sub_menu/insert_submenu?submenu=" + submenu + "&menu_id=" + menu_id +
				"&vista=" + vista + "&icono=" + icono, true);
			xmlhttp2.send();
		}

	}

	function update_submenu_data() {
		var idSubmenu = document.getElementById("idSubmenu").value;
		const str = document.getElementById("nombreSM_update").value;
		var menu_id = document.getElementById("Select2").value;
		var vista = document.getElementById("rutaVistaSM_update").value;
		var icono = document.getElementById("iconoSM_update").value;

		const submenu = str.charAt(0).toUpperCase() + str.slice(1);

		if (idSubmenu == "" || submenu == "" || menu_id == "" || vista == "" || icono == "") {
			Swal.fire(
				'Oops...',
				'Por favor llene todos los campos!',
				'error'
			);
		} else {
			var xmlhttp2;
			if (window.XMLHttpRequest) {
				xmlhttp2 = new XMLHttpRequest();
			} else {
				xmlhttp2 = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp2.onreadystatechange = function() {
				if (xmlhttp2.readyState === 4 && xmlhttp2.status === 200) {
					var flag2 = xmlhttp2.responseText;
					if (flag2 == "") {
						Swal.fire(
							'Oops...',
							'Por favor llene todos los campos!',
							'error'
						);
					} else {
						Swal.fire(
							'¡Genial!',
							'Se actualizó el submenu!',
							'sucess'
						);
						//result2.innerHTML = xmlhttp2.responseText;
					}


				}
			}
			xmlhttp2.open("GET", "<?= base_url() ?>sub_menu/update_submenu?idSubmenu=" + idSubmenu + "&submenu=" + submenu +
				"&menu_id=" + menu_id +
				"&vista=" + vista + "&icono=" + icono, true);
			xmlhttp2.send();
		}

	}

	function delete_submenu() {
		var result1 = document.getElementById("Select1");
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
					Toast.fire({
						type: 'success',
						title: ' bien!'
					});
					result1.innerHTML = xmlhttp1.responseText;
				}


			}
		}
		xmlhttp1.open("POST", "<?= base_url() ?>sub_menu/load_select", true);
		xmlhttp1.send();
	}
</script>



<?php
$eveny = array('title' => "Prueba1", 'start' => '2021-09-12', 'end' => "2021-09-13");
?>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		var calendarEl = document.getElementById('calendar');
		//import interactionPlugin from '@fullcalendar/interaction';
		var calendar = new FullCalendar.Calendar(calendarEl, {
			initialView: 'dayGridMonth',
			//plugins: [ interactionPlugin ],
			selectable: true,
			select: function(arg) {
				//$('#modal_au').modal('show');
				calendar.addEvent({
					title: "Prueba",
					date: arg.startStr,
					descripcion: "Esto es una prueba"
				});
				//alert(arg.startStr);
				//console.log(arg);
				calendar.unselect();
			},
			eventClick: function(arg) {
				//console.log(arg.event);
				console.log(arg.event.title);
				console.log(arg.event.startStr);
				console.log(arg.event.extendedProps.descripcion);
				/*if (confirm('Se va a eliminar el evento del dia: '+arg.startStr)) {
				  arg.event.remove();
				}*/
			},
			headerToolbar: {
				left: 'prev,next,today',
				center: 'title',
				right: 'Miboton'
			},
			customButtons: {
				Miboton: {
					text: "Nuevo Ausentismo",
					click: function() {
						$('#modal_au').modal('show');
					}
				}
			}
		});
		calendar.setOption('locale', 'Es');
		calendar.render();


	});
</script>

<script src="<?= base_url() ?>dist/js/demo.js"></script>
<?php
$this->load->model('usuarios');
$usu = $this->usuarios->getUserById($id_usu);
$p = "";
$nit = "";
foreach ($usu->result() as $key) {
	$p = $key->pass;
	$nit = $key->nit;
}
//echo $p." ".$nit;
$this->load->library('encrypt');
$pass_desencript = $this->encrypt->decode($p);
if ($pass_desencript == $nit) {
?>
	<script type="text/javascript">
		$('#pass-modal').show('true')
	</script>
<?php
}
?>
<script type="text/javascript">
	setTimeout(function() {
		$('#alert_err').alert('close');
	}, 1500); //soy un comentario
</script>

<script type="text/javascript">
	//funcionar para dar posicion y timepo a sweealert
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 5000
	});
	//funcion para taer infomacion de la tabla menus
	$(document).ready(function() {
		var result = document.getElementById("load_data");
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				var flag = xmlhttp.responseText;
				if (flag == "") {
					Toast.fire({
						type: 'error',
						title: ' No hay datos'
					});
				} else {
					//result.innerHTML = xmlhttp.responseText;
				}
			}
		}
		xmlhttp.open("POST", "<?= base_url() ?>menu/cargar_tabla_menu", true);
		xmlhttp.send();

	})
</script>

<script>
	//funcion para validar y enviar campos del formulario a la tabla dbo.postv_menus.  Andres Gomez .14-09-21
	function recoger_datos_menu() {
		var nombre = document.getElementById('nombreMenu').value;
		var icon = document.getElementById('nombre_icono').value;
		var vista = document.getElementById('vista_menu').value;

		if (nombre == "" || icon == "" || vista == "") {
			Toast.fire({
				type: 'error',
				title: 'Todos los campos deben ser Completados'
			});

		} else {
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
						document.getElementById('formulario_menu').reset()
						Toast.fire({
							type: 'success',
							title: xmlhttp1.responseText
						});
						//$("#load_data").html(xmlhttp1.responseText);
						location.reload();
					}
				}
			}

			xmlhttp1.open("GET", "<?= base_url() ?>menu/agregar_nuevo_menu?nombre=" + nombre + "&icono=" + icon + "&vista=" + vista, false);
			xmlhttp1.send();

		}
	}
</script>

<script>
	//funcion para eliminar menus en la tabla dbo.postv_menus .  Andres Gomez .15-09-21
	function eliminar(id) {
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				var flag = xmlhttp.responseText;
				if (flag == "") {
					Toast.fire({
						type: 'error',
						title: ' No hay datos'
					});
				} else {
					Toast.fire({
						type: 'success',
						title: xmlhttp.responseText
					});
					//$("#load_data").html(xmlhttp.responseText);
					location.reload();

				}
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>menu/eliminar_menu?id=" + id, true);
		xmlhttp.send();
	}
</script>

<script>
	//funcion para editar menus en la tabla dbo.postv_menus .  Andres Gomez .15-09-21
	function pintar_datos(id) {
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				var flag = xmlhttp.responseText;
				if (flag == "") {
					Toast.fire({
						type: 'error',
						title: ' No hay datos'
					});
				} else {
					document.getElementById('edi_modal').innerHTML = xmlhttp.responseText;

				}
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>menu/ver_datos?id=" + id, true);
		xmlhttp.send();
	}
</script>

<script>
	//funcion para editar menus en la tabla dbo.postv_menus .  Andres Gomez .15-09-21
	function editarDatos() {
		var id = document.getElementById('codigo_editar').value;
		var nombre = document.getElementById('nombreMenu_editar').value;
		var vista_menu = document.getElementById('vista_menu_editar').value;
		var icono = document.getElementById('nombre_icono_editar').value;
		var xmlhttp;

		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				var flag = xmlhttp.responseText;
				if (flag == "") {
					Toast.fire({
						type: 'error',
						title: ' No hay datos'
					});
				} else {
					Toast.fire({
						type: 'success',
						title: xmlhttp.responseText
					});
					location.reload();

				}
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>menu/editar_menu?id=" + id + "&nombre=" + nombre + "&vista=" + vista_menu + "&icono=" + icono, true);
		xmlhttp.send();
	}
</script>





<script>
	//funcion para cargar datatable
	$(document).ready(function() {
		$('#tabla_menu').DataTable({
			"paging": true,
			"pageLength": 25,
			"lengthChange": true,
			"searching": true,
			"ordering": false,
			"info": false,
			"autoWidth": false,
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
	});
</script>
<!-- --------------------------------------------seccion para las funciones de taer ,agregar, editar y eliminar informacion de perfiles--- -->
<script>
	/*
//funcion para taer infomacion de la tabla postv_perfiles
	$(document).ready(function() {
		var result = document.getElementById("load_perfil");
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				var flag = xmlhttp.responseText;
				if (flag == "") {
					Toast.fire({
						type: 'error',
						title: ' No hay datos'
					});
				} else {
					
				}
			}
		}
		xmlhttp.open("POST", "<?= base_url() ?>menu/cargar_tabla_menu", true);
		xmlhttp.send();

	})
	*/
</script>

<script>
	//funcion para validar y enviar campos del formulario a la tabla postv_perfiles.  Andres Gomez .16-09-21
	function recoger_datos_perfil() {
		var nombre = document.getElementById('nombrePerfil').value;

		if (nombre == "") {
			Toast.fire({
				type: 'error',
				title: 'Todos los campos deben ser Completados'
			});

		} else {
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
						document.getElementById('formulario_perfil').reset()
						Toast.fire({
							type: 'success',
							title: xmlhttp1.responseText
						});
						//$("#load_data").html(xmlhttp1.responseText);
						location.reload();
					}
				}
			}

			xmlhttp1.open("GET", "<?= base_url() ?>perfil/agregar_nuevo_perfil?nombre=" + nombre, true);
			xmlhttp1.send();

		}
	}
</script>

<script>
	//funcion para eliminar menus en la tabla postv_perfiles.  Andres Gomez .16-09-21
	function eliminar_perfil(id) {
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				var flag = xmlhttp.responseText;
				if (flag == "") {
					Toast.fire({
						type: 'error',
						title: ' No hay datos'
					});
				} else {
					Toast.fire({
						type: 'success',
						title: xmlhttp.responseText
					});
					location.reload();
				}
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>perfil/eliminar_perfil?id=" + id, true);
		xmlhttp.send();
	}
</script>

<script>
	//funcion para pintar  los datos de la  tabla postv_perfiles.  Andres Gomez .16-09-21
	function pintar_datos_perfil(id) {
		var xmlhttp;
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				var flag = xmlhttp.responseText;
				if (flag == "") {
					Toast.fire({
						type: 'error',
						title: ' No hay datos'
					});
				} else {
					document.getElementById('edi_modal_perfil').innerHTML = xmlhttp.responseText;

				}
			}
		}
		xmlhttp.open("GET", "<?= base_url() ?>perfil/ver_perfil?id=" + id, true);
		xmlhttp.send();
	}
</script>

<script>
	//funcion para editar los datos del perfil en la tabla  postv_perfiles.  Andres Gomez .16-09-21
	function editarperil() {
		var id = document.getElementById('codigo_editar_perfil').value;
		var nombre = document.getElementById('nombrePerfil_editar').value;
		var xmlhttp;

		if (id == "" || nombre == "") {
			Toast.fire({
				type: 'error',
				title: 'Campos Vacios'
			});

		} else {
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					var flag = xmlhttp.responseText;
					if (flag == "") {
						Toast.fire({
							type: 'error',
							title: ' No hay datos'
						});
					} else {
						Toast.fire({
							type: 'success',
							title: xmlhttp.responseText
						});
						location.reload();

					}
				}
			}
			xmlhttp.open("GET", "<?= base_url() ?>perfil/editar_perfil?id=" + id + "&nombre=" + nombre, true);
			xmlhttp.send();
		}
	}
</script>


<script>
	//funcion para cargar datatable
	$(document).ready(function() {
		$('#tabla_perfil').DataTable({
			"paging": true,
			"pageLength": 25,
			"lengthChange": true,
			"searching": true,
			"ordering": false,
			"info": false,
			"autoWidth": false,
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
	});
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