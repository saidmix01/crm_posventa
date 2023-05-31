<!-- Navbar -->
<!--
    Autor: Sergio Ivan Galvis Esteban
    Fecha: 11 de Octubre del 2022
    Actualizar 14/10/2022
-->
<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: #2F3C4F;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" style="color: #fff;" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= base_url() ?>login/iniciar" style="color: #fff;" class="nav-link"><i class="fas fa-home"></i>&nbsp;&nbsp; Inicio</a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <?php
        if ($img_user != "") {
            echo '<img src="' . base_url() . 'public/usuarios/img_user/' . $img_user . '" alt="User Avatar" class="img-size-50 mr-3 img-circle" style="position: relative; left: 25px; top: 0px; height: 35px; width: 35px;">';
        } else {
            echo '<img src="' . base_url() . 'media/img/user-img.png" alt="User Avatar" class="img-size-50 mr-3 img-circle" style="position: relative; left: 25px; top: 0px; height: 35px; width: 35px;">';
        }
        ?>


        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" style="color: #fff;">
                <?php
                foreach ($userdata->result() as $key) {
                ?>
                    <?= $key->nombres ?>

            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header"><?= $key->nombres ?></span>
            <?php
                }
            ?>
            <div class="dropdown-divider" style=""></div>
            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#pass-modal2">
                <i class="fas fa-key mr-2"></i>Cambiar Contraseña
            </a>
            <div class="dropdown-divider" style=""></div>
            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#change_img">
                <i class="fas fa-user mr-2"></i>Cambiar Imagen de Perfil
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
<!-- Modales nav_user.php -->
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

<!-- Cambio de Imagen de perfil Modal-->
<div class="modal" tabindex="-1" id="change_img" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar imagen de perfil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <label for="img_user">Cargar imagen:</label>
                            <input type="file" id="seleccionArchivos" name="seleccionArchivos" class="form-control" accept="image/*" placeholder="">
                        </div>
                        <div class="col-12 col-sm-12">
                            <!-- La imagen que vamos a usar para previsualizar lo que el usuario selecciona -->
                            <label for="img_prev">Vista previa:</label>
                            <style>
                                .img_prev {
                                    margin: auto;
                                    display: block;
                                    width: 200px;
                                    height: auto;
                                    padding: .375rem .75rem;
                                    border: 1px solid #ced4da;
                                    border-radius: .25rem;
                                    box-shadow: inset 0 0 0 transparent;
                                    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
                                }
                            </style>
                            <?php
                            if ($img_user != "") {
                                echo '<img src="' . base_url() . 'public/usuarios/img_user/' . $img_user . '" alt="User Avatar" class="img_prev" id="imagenPrevisualizacion" width="50px">';
                            } else {
                                echo '<img src="' . base_url() . 'media/img/user-img.png" alt="User Avatar" class="img_prev" id="imagenPrevisualizacion" width="50px">';
                            }
                            ?>
                        </div>
                    </div>




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="cambiarImgPefil();">Cambiar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modales nav_user.php -->

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

    // Obtener referencia al input y a la imagen

    const $seleccionArchivos = document.querySelector("#seleccionArchivos"),
        $imagenPrevisualizacion = document.querySelector("#imagenPrevisualizacion");

    // Escuchar cuando cambie
    $seleccionArchivos.addEventListener("change", () => {
        // Los archivos seleccionados, pueden ser muchos o uno
        const archivos = $seleccionArchivos.files;
        // Si no hay archivos salimos de la función y quitamos la imagen
        if (!archivos || !archivos.length) {
            $imagenPrevisualizacion.src = "";
            return;
        }
        // Ahora tomamos el primer archivo, el cual vamos a previsualizar
        const primerArchivo = archivos[0];
        // Lo convertimos a un objeto de tipo objectURL
        const objectURL = URL.createObjectURL(primerArchivo);
        // Y a la fuente de la imagen le ponemos el objectURL
        $imagenPrevisualizacion.src = objectURL;
    });

    function cambiarImgPefil() {
        console.log('Cambiando imagen de perfil');
        const archivos = document.getElementById('seleccionArchivos');

        if (archivos.files.length > 0) {
            let form = new FormData();
            form.append('url_img_user_postv', archivos.files[0]);
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
                            text: 'Se ha actualizado la imagen de perfil.',
                            icon: 'success',
                            confirmButtonText: 'Ok',
                            allowOutsideClick: false
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else if (resp == 0) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'No se ha actualizado la imagen de perfil.',
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
            xmlhttp.open("POST", "<?= base_url() ?>usuarios_admin/changeImgUser", true);
            xmlhttp.send(form);
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'No se ha cargado la imagen a cambiar',
                icon: 'error',
                confirmButtonText: 'Cerrar'
            });
        }
    }
</script>