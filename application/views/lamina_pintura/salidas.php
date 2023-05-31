<?php $this->load->view('lamina_pintura/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section>
        <div class="card">
            <div class="container">
                <div class="card">
                    <div class="card-title"><label class='alert text-center bg-info col-lg-12 lead'>Buscar Registro por Numero de Orden</label></div>
                    <div class="card-body">
                        <form method='POST' id="formalrioorden">
                            <div class='form-row'>
                                <div class='col-lg-2'>
                                    <label>Numero d Orden</label>
                                    <input onchange="buscrainfocarro()" type='text' id='numeroorden' name='numeroorden' class='form-control' placeholder='Numero de Orden'>
                                </div>
                                <div id="respuestanumerooreden"></div>

                                <div class='col-lg-2'>
                                    <label>Presupuestro</label>
                                    <input type='text' id='presupuesto' name='presupuesto' class='form-control' placeholder='Presupuesto'>
                                </div>
                            </div>
                            <div class='modal-footer'>
                                <button onclick='registarorden();tablaproductoslistados();traertablavalores()' class='btn btn-info for' type='button'>Cargar &nbsp; <span><i class="fas fa-save"></i></span></button>
                            </div>
                            <div id="validaresp"></div>
                        </form>

                        <form method="POST" id="formver" name="formver">
                            <hr>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal">
                                Listar insumos
                            </button>
                            <hr>
                            <!-- Modal -->
                            <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='exampleModalLabel'>Modal title</h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <div class='modal-body'>
                                            <form action="" id='formver' method="POST">
                                                <div class='form-row'>
                                                    <div class='col d-none'>
                                                        <input class='form-control' id='idrefreencia' name="idrefreencia">
                                                    </div>
                                                    <div class='col'>
                                                        <label for=''>Referencia</label>
                                                        <select class='form-control' id='valor' name="valor" onchange="vertablaAgrgar()">
                                                            <option class='d-none'>Seleccione un Producto</option>
                                                            <?php foreach ($producto->result() as $key) { ?>
                                                                <option value='<?= $key->id_producto ?>'><?= $key->nombre_producto ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class='col-lg-6 col-sm-6 col-xs-6 d-none'>
                                                        <input disabled value='<?= $fechaActual = date('d-m-Y'); ?>' id='requefecha' name='requefecha' type='text' class='form-control' require>
                                                    </div>
                                                    <div class='col-lg-6 col-sm-6 col-xs-6 d-none'>
                                                        <input disabled value='1' id='estado' name='estado' type='text' class='form-control' require>
                                                    </div>
                                                    <div id="formagrgargastos"></div>
                                                    <div id="inforequeri" class='col-lg-12'></div>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div id="listacompras"></div>
                            <style>
                                .scrol {
                                    overflow: scroll;
                                    height: 200px;
                                    width: 100%;
                                }

                                .scrol table {
                                    width: 100%;
                                }
                            </style>
                        </form>
                        <div id="sumadevalores"></div>

                    </div>
                </div>
                <!---listar productos a gastar--->







            </div>
        </div>
    </section>
    <!--
    <section class="content">
        <div class="card">
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        <label class="alert text-center alert-info col-lg-12 " style="font-size: 100%;" for="">Listado de Productos en Inventario Lamina y Pintura</label>
                        <?php foreach ($datos->result() as $key) { ?>
                            <div class="card p-2 m-2" style="width: 20rem;">
                                <img src="<?= base_url() ?>media/pintura.png"  class="card-img-top" alt="...">
                                <div class="card-body">
                                    <div class="d-flex justify-content-start">
                                        <p class="col-12"><b style="color: #222;">Producto :&nbsp; </b><?php echo $key->nombre_producto ?><span class=" text-center"></span></p>
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        <p class="col-12"><b style="color: #222;">Disponible :&nbsp; </b><?php echo $key->cantidad_producto . '&nbsp;' . $key->medida ?><span class=" text-center"></span></p>
                                    </div>
                                </div>
                                <div class="bottom  justify-content-start">
                                   <?php echo "<button onclick='(this.id)' data-toggle='modal' data-target='#modalRetiros' name='" . $key->id_producto . "'   class=' btn btn-info form-control shadow' id='" . $key->id_producto . "' >Retirar</button> " ?>          
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>

    </section>

                        -->
    <!--------------------------------------------------------- modal para generar retiro de productpo-------------------------------- -->
    <div class="modal fade bd-example-modal-lg" id="modalRetiros" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title alert text-center col-lg-12" id="exampleModalLabel">Editar infomacion de Producto</h5>
                </div>
                <div class="modal-body">
                    <form id='formularioeditarlaminapintura' method='POST' enctype='multipart/form-data'>
                        <div class='form-row'>

                            <div class='col-lg-6 col-sm-6 col-xs-6'>
                                <label for=''>Nombre del Producto</label>
                                <input id='' name='' type='text' class='form-control' require>
                            </div>
                            <div class='col-lg-6 col-sm-6 col-xs-6'>
                                <label for=''>Nombre del Producto</label>
                                <input id='' name='' type='text' class='form-control' require>
                            </div>
                            <div class='col-lg-6 col-sm-6 col-xs-6'>
                                <label for=''>Nombre del Producto</label>
                                <input id='' name='' type='text' class='form-control' require>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button onclick="EditarProductoLaminaPintura()" type="button" class="btn btn-success">Editar</button>
                </div>
            </div>
        </div>
    </div>




</div>
<?php $this->load->view('lamina_pintura/footer') ?>