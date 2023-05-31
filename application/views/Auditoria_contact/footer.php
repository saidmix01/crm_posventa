	<footer class="main-footer">
		<strong>Copyright &copy; 2022 <a href="http://adminlte.io">CODIESEL</a>.</strong>
		Todos los derechos reservados.
		<div class="float-right d-none d-sm-inline-block">
			<b>Version</b> 1.0.0-pre
		</div>
	</footer>
	<!-- /.content-wrapper -->
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
	<!-- Modal para subir archivos al finalizar la auditoria -->
	<!-- Modal -->
	<div class="modal fade" id="cargarArchivoAuditoria" tabindex="-1" role="dialog" aria-labelledby="cargarArchivoAuditoria" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="cargarArchivoAuditoriaTitle">Subir archivos de evidencia</h5>
					<!-- <button type="button" onclick="location.reload();" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button> -->
				</div>
				<div class="modal-body">
					<input type="file" multiple id="archivosAuditoria" name="archivosAuditoria">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="cargarArchivos();">Cargar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal para subir archivos de forma libre -->
	<!-- Modal -->
	<div class="modal fade" id="cargarArchivoAuditoriaLibre" tabindex="-1" role="dialog" aria-labelledby="cargarArchivoAuditoriaLibre" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="cargarArchivoAuditoriaLibreTitle">Subir archivos de evidencia</h5>
					<!-- <button type="button" onclick="location.reload();" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button> -->
				</div>
				<div class="modal-body">
					<input type="file" multiple id="archivosAuditoriaLibre" name="archivosAuditoriaLibre">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="cargarArchivosLibre();">Cargar</button>
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
	<!-- AdminLTE for demo purposes -->
	<!-- Toastr -->
	<script src="<?= base_url() ?>plugins/toastr/toastr.min.js"></script>
	<!-- SweetAlert2 -->
	<!-- <script src="<?= base_url() ?>plugins/sweetalert2/sweetalert2.min.js"></script> -->
	<!--table2excel -->
	<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
	<!-- DataTables -->
	<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
	<script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
	<!-- overlayScrollbars -->
	<script src="<?= base_url() ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 3000,
			timerProgressBar: true,
			didOpen: (toast) => {
				toast.addEventListener('mouseenter', Swal.stopTimer)
				toast.addEventListener('mouseleave', Swal.resumeTimer)
			}
		})


		function finalizarAuditoria() {

			var id_auditoria = document.getElementById('id_auditoria').value;

			var sumaSi = 0;
			$("input[class='si']").each(function() {
				if (this.checked == true) {
					sumaSi++;
				}
			});
			var sumaNo = 0;
			$("input[class='no']").each(function() {
				if (this.checked == true) {
					sumaNo++;
				}
			});
			var sumaNA = 0;
			$("input[class='noAplica']").each(function() {
				if (this.checked == true) {
					sumaNA++;
				}
			});
			var obsAuditor = document.getElementById('obsAuditor').value;
			var tSuma = sumaSi + sumaNo + sumaNA;
			if (tSuma == parseInt(document.getElementById('cantPreguntas').value)  && obsAuditor != ""  ) {
				var info = new FormData();
				info.append('id_auditoria', id_auditoria);
				info.append('obsAuditor', obsAuditor);
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				//xmlhttp.responseType = 'json'; 
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						if (xmlhttp.responseText > 0) {
							Swal.fire({
								title: 'Exito',
								text: 'La auditoría se ha finalizado con exito.',
								icon: 'success',
								confirmButtonText: 'Ok',
								allowOutsideClick: false,
								showCloseButton: false
							}).then((result) => {
								
								if (result.isConfirmed) {
									$('#cargarArchivoAuditoria').modal({
										backdrop: 'static',
										keyboard: false
									});
								}
							});
						} else {
							Swal.fire({
								title: 'Advertencia',
								text: 'Ha ocurrido un error al finalizar la auditoría, intente nuevamente.',
								icon: 'error',
								confirmButtonText: 'Ok',
								allowOutsideClick: false,
								showCloseButton: false
							});
						}


					}
				}
				xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/finalizarAuditoria", true);
				xmlhttp.send(info);
			} else {
				Swal.fire({
					title: 'Advertencia',
					text: 'Para finalizar la auditoría debe completar el formulario...',
					icon: 'error',
					confirmButtonText: 'Ok',
					allowOutsideClick: false,
					showCloseButton: false
				});
			}


		}
		// UpdateRespuesta Si = 1 o NO = 0  
		function updateResp(item, opt) {
			id_auditoria = document.getElementById('id_auditoria').value;
			var info = new FormData();
			info.append('item', item);
			info.append('opt', opt);
			info.append('id_auditoria', id_auditoria);
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			//xmlhttp.responseType = 'json';
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					if (xmlhttp.responseText == 'OK') {
						Toast.fire({
							icon: 'success',
							title: 'Respuesta registrada con exito.'
						})
					} else {
						Toast.fire({
							icon: 'error',
							title: 'Ha ocurrido un error, intente nuevamente.'
						})
					}


				}
			}
			xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/updateRespuesta", true);
			xmlhttp.send(info);

		}

		function cargarArchivos() {
			id_auditoria = document.getElementById('id_auditoria').value;
			var archivo = document.getElementById("archivosAuditoria").value;
			if (archivo.length == 0) {
				Swal.fire({
					title: 'Advertencia!',
					text: 'Por favor seleccione un archivo',
					icon: 'warning',
					confirmButtonText: 'Ok'
				});
				document.getElementById("archivosAuditoria").value = "";
			} else {
				document.getElementById("cargando").style.display = "block";
				let formData = new FormData();
				var cantArchivos = $('#archivosAuditoria')[0].files.length;
				jQuery.each(jQuery('#archivosAuditoria')[0].files, function(i, file) {
					formData.append('file-' + i, file);
				});
				formData.append('id_auditoria', id_auditoria);
				formData.append('cantArchivos', cantArchivos);

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.responseType = 'json';
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						$('#cargarArchivoAuditoria').modal('hide');
						var data = xmlhttp.response;
						if (data == 0) {
							Swal.fire({
								title: 'Advertencia',
								text: 'Ha ocurrido un error al subir los archivos, intente nuevamente.',
								icon: 'error',
								confirmButtonText: 'Ok',
								allowOutsideClick: false,
								showCloseButton: false
							});
							document.getElementById("cargando").style.display = "none";
						} else if (data["cantSaveFile"] != "") {
							var arraySI = data["cantSaveFile"];
							var text = "Los siguientes archivos fueron registrado en la base de datos:<br><ol style='text-align: start;'>";
							for (let index = 0; index < data["cantSaveFile"].length; index++) {
								text += "<li>" + arraySI[index] + '</li>';
							}
							text += "</ol>";

							var arraySI = data["cantNotSaveFile"];
							if (arraySI != "") {
								text += "Los siguientes archivos no fueron registrado en la base de datos:<br><ol style='text-align: start;'>";
								for (let index = 0; index < data["cantNotSaveFile"].length; index++) {
									text += "<li>" + arraySI[index] + '</li>';
								}
								text += "</ol>";
							}
							Swal.fire({
								title: 'Exito',
								html: text,
								icon: 'success',
								confirmButtonText: 'Ok',
								allowOutsideClick: false,
								showCloseButton: false
							}).then((result) => {
								// Read more about isConfirmed, isDenied below 
								if (result.isConfirmed) {
									sendEmailAuditoria(id_auditoria);
								}
							});
							document.getElementById("cargando").style.display = "none";
						}

					}

				}
				xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/upload_data", true);
				xmlhttp.send(formData);

			}
		}

		function cargarArchivosLibre() {
			id_auditoria = document.getElementById('id_auditoria').value;
			var archivo = document.getElementById("archivosAuditoriaLibre").value;
			if (archivo.length == 0) {
				Swal.fire({
					title: 'Advertencia!',
					text: 'Por favor seleccione un archivo',
					icon: 'warning',
					confirmButtonText: 'Ok'
				});
				document.getElementById("archivosAuditoria").value = "";
			} else {
				document.getElementById("cargando").style.display = "block";
				let formData = new FormData();
				var cantArchivos = $('#archivosAuditoriaLibre')[0].files.length;
				jQuery.each(jQuery('#archivosAuditoriaLibre')[0].files, function(i, file) {
					formData.append('file-' + i, file);
				});
				formData.append('id_auditoria', id_auditoria);
				formData.append('cantArchivos', cantArchivos);

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.responseType = 'json';
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
						$('#cargarArchivoAuditoria').modal().hide();
						var data = xmlhttp.response;
						if (data == 0) {
							Swal.fire({
								title: 'Advertencia',
								text: 'Ha ocurrido un error al subir los archivos, intente nuevamente.',
								icon: 'error',
								confirmButtonText: 'Ok',
								allowOutsideClick: false,
								showCloseButton: false
							});
							document.getElementById("cargando").style.display = "none";
						} else if (data["cantSaveFile"] != "") {
							var arraySI = data["cantSaveFile"];
							var text = "Los siguientes archivos fueron registrado en la base de datos:<br><ol style='text-align: start;'>";
							for (let index = 0; index < data["cantSaveFile"].length; index++) {
								text += "<li>" + arraySI[index] + '</li>';
							}
							text += "</ol>";

							var arraySI = data["cantNotSaveFile"];
							if (arraySI != "") {
								text += "Los siguientes archivos no fueron registrado en la base de datos:<br><ol style='text-align: start;'>";
								for (let index = 0; index < data["cantNotSaveFile"].length; index++) {
									text += "<li>" + arraySI[index] + '</li>';
								}
								text += "</ol>";
							}
							Swal.fire({
								title: 'Exito',
								html: text,
								icon: 'success',
								confirmButtonText: 'Ok',
								allowOutsideClick: false,
								showCloseButton: false
							}).then((result) => {
								// Read more about isConfirmed, isDenied below 
								if (result.isConfirmed) {
									location.reload();
								}
							});
							document.getElementById("cargando").style.display = "none";
						}
						

					}

				}
				xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/upload_data", true);
				xmlhttp.send(formData);

			}
		}

		function sendEmailAuditoria(id_auditoria) {
			document.getElementById("cargando").style.display = "block";
			let emailData = new FormData();
			emailData.append('id_auditoria', id_auditoria);
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
					if (xmlhttp.responseText == 'Exito') {
						Swal.fire({
							title: 'Exito',
							html: 'Se ha enviado un correo al agente',
							icon: 'success',
							confirmButtonText: 'Ok',
							allowOutsideClick: false,
							showCloseButton: false
						}).then((result) => {
							// Read more about isConfirmed, isDenied below 
							if (result.isConfirmed) {
								location.reload();
							}
						});
					} else {
						Swal.fire({
							title: 'Advertencia',
							html: 'Ha ocurrido un error al enviar el correo electronico al agente',
							icon: 'warning',
							confirmButtonText: 'Ok',
							allowOutsideClick: false,
							showCloseButton: false
						})
					}
					document.getElementById("cargando").style.display = "none";
				}
			}
			xmlhttp.open("POST", "<?= base_url() ?>auditoria_contact/sendEmail", true);
			xmlhttp.send(emailData);
		}
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