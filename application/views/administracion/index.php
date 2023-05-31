<?php $this->load->view('administracion/header') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <br>
    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <?php
                $diasSemana = array ('No existe','Lunes', 'Martes', 'Miércoles', 'Jueves','Viernes','Sébado','Domingo');
                $meses = array ('No existe','Enero', 'Febrero', 'Marzo', 'Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
                $dia = $diasSemana[date('N')];
                $mes = $meses[date('n')];
                ?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h3 class="title">No se puede crear ausentismos en horarios no laborales</h3>
                        <span class="text-muted"><?php echo "Fecha: $dia ".date('j')." de  $mes del".  date('Y');?></span>
                        <br/>
                        <span class="text-muted"><?php echo "Hora: " . date('g a : i : s');?></span>
                    </div>
                </div>
            </div>
            <div class="card-footer">

            </div>
        </div>
    </section>
    <!-- /.content -->
</div>




<?php $this->load->view('administracion/footer') ?>