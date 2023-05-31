<?php $this->load->view('configuracion/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <br>
    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header" align="center"><h3>Administracion de Submenus</h3></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            <i class="far fa-plus-square"></i>Nuevo Submenu
                        </button>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="example1">
                        <thead>
                            <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Vista</th>
                                <th scope="col">Menú</th>
                                <th scope="col">Icono</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Se cargan los datos de la tabla -->
                            <?php
                                foreach ($submenus->result() as $key) {
                                    echo '
                                    <tr>
                                        <td>'.$key->submenu.'</td>
                                        <td>'.$key->vista.'</td>
                                        <td>'.$key->menu.'</td>
                                        <td>'.$key->icono.'<br><spam><i class="'.$key->icono.'"></i></spam></td>
                                        <td>'
                            ?>            
                                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModalA" id="<?=$key->id_submenu?>" onclick="consultar_submenu_data(<?=$key->id_submenu?>);"><i class="fas fa-edit"></i>Editar</button>
                                            <a role="button" class="btn btn-danger" href="<?=base_url()?>sub_menu/delete_submenu?idSubmenu=<?=$key->id_submenu?>"><i class="fas fa-edit"></i>Eliminar</a>
                            <?php                
                                        '</td>
                                    </tr>
                                    ';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- Modal -->
<!-- Modal para un Nuevo SubMenu -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Sub Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreSM">Nombre:</label>
                        <input type="text" class="form-control" id="nombreSM"
                            placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="Select1">Menú:</label>
                        <select class="form-control js-example-basic-single" style="width:100%" id="Select1">
                        <?php foreach ($menus_combo->result() as $key) {?>
                            <option value="<?=$key->id_menu?>"><?=$key->menu?></option>
                        <?php }?>
                        </select>
                    </div>
					<div class="form-group">
                        <label for="rutaVistaSM">Ruta Vista:</label>
                        <input type="text" class="form-control" id="rutaVistaSM"
                            placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="iconoSM">Icono:</label>
                        <input type="text" class="form-control" id="iconoSM"
                            placeholder="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="insert_submenu_data();">Agregar Sub Menu</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para actualizar SubMenu -->
<div class="modal fade" id="exampleModalA" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Actualizar Submenu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_edit">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="update_submenu_data();">Actualizar Submenu</button>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('configuracion/footer') ?>