<?php $this->load->view('configuracion/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper shadow">
    <br>
    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-body">

            <label class="col-lg-12 text-center lead h3">Lista de Perfiles</label>
            <hr>
                <!-- Button trigger modal -->
                <div class="row">
                    <button style=" text-shadow: 2px 2px 4px #000000;"  type="button" class="btn btn-info" data-toggle="modal" data-target="#perfil"> Nuevo Perfil <span><i class="fas fa-plus-circle px-1"></i></span></button>
                </div>

                <!-- Modal agregar -->
                <div class="modal fade" id="perfil" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5  class="modal-title label col-lg-12 text-center font-bold" id="exampleModalLongTitle">Agregar Nuevo Perfil</h5>
                            </div>
                            <div class="modal-body">
                                <form method="post" id="formulario_perfil">
                                    <div class="form-group">
                                        <label for="nombrePerfil">Nombre del Perfil</label>
                                        <input type="text" class="form-control" name="nombrePerfil" id="nombrePerfil" aria-describedby="emailHelp" placeholder="Nombre">
                                    </div>
                                    <div class="modal-footer"> 
                                        <button type="button" class="btn btn-danger shadow" data-dismiss="modal">Cerrar</button>
                                        <button onclick="recoger_datos_perfil() " type="button" id="enviar_perfil" class="btn btn-success shadow">Guardar</button>
                                    </div>

                                </form>

                            </div>

                        </div>
                    </div>
                </div>

                <!-- Modal editar perfil     -->
            
                <div class="modal fade" id="pintarperfil" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title label col-lg-12 text-center font-bold" id="exampleModalLongTitle">Editar Perfil</h5>
                            </div>
                            <div class="modal-body" id="edi_modal_perfil"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger shadow" data-dismiss="modal">Cerrar</button>
                                <button onclick="editarperil();" type="button" id="enviar_perfil_update" class="btn btn-success shadow">Editar</button>
                            </div>

                        </div>
                    </div>
                </div>
            
                <table class="table  table-hover table-bordered stripe " id="tabla_perfil">
                    <thead class="bg-dark">
                        <tr>
                            <th style=" text-shadow: 2px 2px 4px #000000;" scope="col">Nombre del Perfil</th>
                            <th style=" text-shadow: 2px 2px 4px #000000;" class="text-center" scope="col">Editar</th>
                            <th style=" text-shadow: 2px 2px 4px #000000;" class="text-center" scope="col">Eliminar</th>
                            
                        </tr>
                    </thead>
                    <tbody class="" id="load_perfil">

                    <?php foreach ($data_tabla->result() as $key) { 
                        echo "
                        <tr>
                            <td >$key->nom_perfil</td>
                            <td style='text-shadow: 2px 2px 4px #000000;' class='text-center'><button data-toggle='modal' data-target='#pintarperfil' class='fas fa-edit btn btn-warning shadow text-white'id='$key->id_perfil' onclick='pintar_datos_perfil(this.id)'> </button></td>
                            <td style='text-shadow: 2px 2px 4px #000000;' class='text-center'><button class='fas fa-trash-alt btn btn-danger shadow' id='$key->id_perfil' onclick='eliminar_perfil(this.id)'></button></td>
                        </tr>
                        ";
		                } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<?php $this->load->view('configuracion/footer') ?>