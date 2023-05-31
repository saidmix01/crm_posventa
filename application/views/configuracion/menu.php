<?php $this->load->view('configuracion/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper shadow">
    <br>
    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-body">
            <label class="col-lg-12 text-center lead h3">Lista de Menus</label>
            <hr>

                <!-- Button trigger modal -->
                <div class="row">
                    <button style=" text-shadow: 2px 2px 4px #000000;"  type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModalCenter"> Nuevo Menu <span><i class="fas fa-plus-circle px-1"></i></span></button>
                </div>

                <!-- Modal agregar -->
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title label col-lg-12 text-center font-bold" id="exampleModalLongTitle">Agregar Nuevo Menu</h5>
                            </div>
                            <div class="modal-body">
                                <form method="post" id="formulario_menu">
                                    <div class="form-group">
                                        <label for="nombreMenu">Nombre del Menu</label>
                                        <input type="text" class="form-control" name="nombreMenu" id="nombreMenu" aria-describedby="emailHelp" placeholder="Nombre">
                                    </div>
                                    <div class="form-group">
                                        <label for="nombre_icono">Icono del menu</label>
                                        <input type="text" class="form-control" name="nombre_icono" id="nombre_icono" placeholder="Icono">
                                    </div>
                                    <div class="form-group d-none">
                                        <input type="text" class="form-control" name="vista_menu" id="vista_menu" value="a" placeholder="Icono">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger shadow" data-dismiss="modal">Cerrar</button>
                                        <button onclick="recoger_datos_menu()" type="button" id="enviar_menu" class="btn btn-success shadow">Gurardar</button>
                                    </div>

                                </form>

                            </div>

                        </div>
                    </div>
                </div>

                <!-- Modal editar -->
            
                <div class="modal fade" id="updatemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title label col-lg-12 text-center font-bold" id="exampleModalLongTitle">Agregar Nuevo Menu</h5>
                            </div>
                            <div class="modal-body" id="edi_modal">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger shadow" data-dismiss="modal">Cerrar</button>
                                <button onclick="editarDatos();" type="button" id="enviar_menu_update" class="btn btn-success shadow">Editar</button>
                            </div>

                        </div>
                    </div>
                </div>
                <table class="table  table-hover table-bordered  " id="tabla_menu">
                    <thead class="bg-dark">
                        <tr>
                            <th style=" text-shadow: 2px 2px 4px #000000;"  scope="col">Nombre del Menu</th>
                            <th style=" text-shadow: 2px 2px 4px #000000;"  scope="col">Nombre del icono</th>
                            <th style=" text-shadow: 2px 2px 4px #000000;"  class="text-center" scope="col">icono</th>
                            <th style=" text-shadow: 2px 2px 4px #000000;"  class="text-center" scope="col">Editar</th>
                            <th style=" text-shadow: 2px 2px 4px #000000;"  class="text-center" scope="col">Eliminar</th>
                            
                        </tr>
                    </thead>
                    <tbody class="" id="load_data">

                    <?php foreach ($data_tabla->result() as $key) { 
                        echo "
                        <tr>
                            <td>$key->menu</td>
                            <td>$key->icono</td>
                            <td class='text-center'><spam><i class='$key->icono'></i></spam></td>
                            <td class='text-center'><button data-toggle='modal' data-target='#updatemodal' class='fas fa-edit btn btn-warning shadow text-white'id='$key->id_menu' onclick='pintar_datos(this.id)'> </button></td>
                            <td class='text-center'><button class='fas fa-trash-alt btn btn-danger shadow' id='$key->id_menu' onclick='eliminar(this.id)'></button></td>
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