<div class="container-fluid">
  <h3>Informe diario Taller</h3>
  <div>
    <div class="row">
      <div class="col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-success elevation-1"><i class="fas fa-dollar-sign"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Total Vendido</span>
            <span class="info-box-number" style="font-size: 23px;" id="total_ven">$<?=number_format($total_ventas,0,",",",")?></span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
      <div class="col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon elevation-1" style="background-color: #FF6C00;color: #fff;"><i class="fas fa-dollar-sign"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Total M.O</span>
            <span class="info-box-number" style="font-size: 23px;" id="total_ven">$<?=number_format($mo,0,",",",")?></span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
      <div class="col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon elevation-1" style="background-color: #A569BD;color: #fff;"><i class="fas fa-dollar-sign"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Total TOT</span>
            <span class="info-box-number" style="font-size: 23px;" id="total_ven">$<?=number_format($tot,0,",",",")?></span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
      <div class="col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon elevation-1" style="background-color: #00FFEA;"><i class="fas fa-dollar-sign"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Total Rptos</span>
            <span class="info-box-number" style="font-size: 23px;" id="total_ven">$<?=number_format($rep,0,",",",")?></span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
    </div>
    <div class="row" >
      <div class="col-md-4" style="width: 10%;">
        <div class="info-box mb-3">
          <span class="info-box-icon elevation-1" style="background-color: #001AFF;color: #fff;"><i class="far fa-clock"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Horas Facturadas</span>
            <span class="info-box-number" style="font-size: 23px;" id="horas_fac">$<?=$horas_fac?></span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
      <div class="col-md-4" style="width: 15rem;">
        <div class="info-box">
          <span class="info-box-icon bg-info elevation-1"><i class="fas fa-chart-line"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">NPS Interno</span>
            <span class="info-box-number" style="font-size: 23px;" id="nps_interno">
              <?=round($nps_int)?>
              <small>%</small>
            </span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
      <div class="col-md-4">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-chart-line"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">NPS COLMOTORES</span>
            <span class="info-box-number" style="font-size: 23px;" id="nps_col"> 
              <?=round($nps_col)?> %
            </span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <div id="chartContainer" style="height: 370px; width: 100%;"></div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <div id="charthoras" style="height: 370px; width: 100%;"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <div id="chartnpsint" style="height: 370px; width: 100%;"></div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <div id="chartnpsgm" style="height: 370px; width: 100%;"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>