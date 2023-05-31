<?php
//Logica del asesor de repuestos
/*MODELOS*/
$this->load->model('nominas');
$this->load->model('usuarios');
$this->load->model('Informe');
$this->load->model('sedes');
/* VARIABLES */
$nit = $this->session->userdata('user');
$nom_usu = $this->usuarios->getUserByNit($nit)->nombres;
$mes = $this->Informe->get_mes_ano_actual()->mes;
$ano = $this->Informe->get_mes_ano_actual()->ano;
$data_tabla = array();
$arr_aux1 = array();
$arr_aux2 = array();

/* Scrip x Sergio Galvis 10 de Abril del 2023 */
/*OBTENER BODEGAS DEL USUARIO*/
$data_sedes = $this->sedes->get_sedes_user($nit);
$sedes_usu = [];
$div_presupuesto = "";
foreach ($data_sedes->result() as $key) {
	/*OBTENER PRESUPUESTOS DE LAS SEDES*/
	/* id_presupuesto	presupuesto	sede	fecha_ini	fecha_fin	mes	id_bodega */
	$presupuesto_sede = $this->Informe->getPresupuesto_sede($ano, $mes, $key->idsede);
	if ($presupuesto_sede->num_rows() > 0) {
		$div_presupuesto .= '<div class="info-box mb-3">
					<span class="info-box-icon bg-info elevation-1"><i class="fas fa-dollar-sign"></i></span>
	
					<div class="info-box-content">
						<span class="info-box-text" style="font-size: 20px;">Presupuesto: ' . $presupuesto_sede->row(0)->sede . '</span>
						<span class="info-box-number" style="font-size: 18px;">' . number_format($presupuesto_sede->row(0)->presupuesto, 0, ",", ",") . '</span>
					</div>
				</div>';
	}
}






/*ARRAY ASESORES*/
$asesores[] = array('nombre' => "QUIÑONEZ NAVAS DIEGO ALONSO", 'sede' => "MOSTRADOR");
$asesores[] = array('nombre' => "QUIÑONEZ NAVAS DIEGO ALONSO", 'sede' => "TALLER");
$asesores[] = array('nombre' => "CASTRO BLANCO LUIS EDUARDO", 'sede' => "SOLOCHEVROLET");
$asesores[] = array('nombre' => "OLAYA CALDERON JOSE ALLENDY", 'sede' => "MOSTRADOR-MAYOR");
$asesores[] = array('nombre' => "OLAYA CALDERON JOSE ALLENDY", 'sede' => "TALLER");
$asesores[] = array('nombre' => "CARRILLO ANGARITA FIDEL", 'sede' => "CUCUTA ASEGURADORA");
$asesores[] = array('nombre' => "RANGEL REYES CRISTIAN ORLANDO", 'sede' => "CUCUTA MOSTRADOR");
$asesores[] = array('nombre' => "LOPEZ JUAN MANUEL", 'sede' => "CUCUTA TALLER");
$asesores[] = array('nombre' => "CADENA RAMIREZ FERNANDO ANTONIO", 'sede' => "GIRON ASEGURADORA");
$asesores[] = array('nombre' => "ABRIL RAMIREZ LEONARDO", 'sede' => "GIRON TALLER");
$asesores[] = array('nombre' => "ARDILA SANCHEZ JOSUE", 'sede' => "GIRON MOSTRADOR");
$asesores[] = array('nombre' => "ARDILA SANCHEZ JOSUE", 'sede' => "GIRON ASEGURADORA-TALLER");
$asesores[] = array('nombre' => "MEJIA VARGAS OSCAR ALFONSO", 'sede' => "GIRON ASEGURADORA");
$asesores[] = array('nombre' => "OCHOA RUEDA JHON FREDDY", 'sede' => "CHEVROPARTES MAYOR");
$asesores[] = array('nombre' => "OCHOA RUEDA JHON FREDDY", 'sede' => "CHEVROPARTES MOSTRADOR");
$asesores[] = array('nombre' => "OCHOA RUEDA JHON FREDDY", 'sede' => "CHEVROPARTES ACEITE GRANEL");

/*LOGICA PARA CALCULAR EL ESTADO DEL DIA ACTUAL*/
$to = 0;
for ($i = 0; $i < count($asesores); $i++) {
	$nom = $asesores[$i]["nombre"];
	$sede = $asesores[$i]["sede"];
	if ($nom_usu == $nom) {
		$venta_neta = 0;
		$margen_bruto = 0;
		$utilidad_bruta = 0;
		$comision = 0;
		$valor_comision = 0;
		$total_comision = 0;
		$comision_v = 0;
		$valor_comision_v = 0;
		switch ($nom) {
			case 'QUIÑONEZ NAVAS DIEGO ALONSO':
				$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
				$data_tall = $this->nominas->get_comision_rep_taller('QDIEGO', $mes, $ano);
				if ($sede == "MOSTRADOR") {
					foreach ($data_mos->result() as $key) {
						$venta_neta = $key->venta_neta;
						$margen_bruto = $key->margen;
						$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
						$comision = 12.0;
						$valor_comision = $utilidad_bruta * ($comision / 100);
						$total_comision = $valor_comision;
					}
					$to = $to + $venta_neta;
					$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => $margen_bruto, "utilidad_bruta" => $utilidad_bruta, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => 0, "valor_comision_v" => 0, "total_comision" => $total_comision, "sede" => $sede, "color" => "#D8FFD4");
				} elseif ($sede == "TALLER") {
					foreach ($data_tall->result() as $key) {
						$venta_neta = $key->venta_neta;
						$margen_bruto = $key->margen;
						$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
						$comision = 8.0;
						$valor_comision = $utilidad_bruta * ($comision / 100);
						$total_comision = $valor_comision;
					}
					$to = $to + $venta_neta;
					$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => $margen_bruto, "utilidad_bruta" => $utilidad_bruta, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => 0, "valor_comision_v" => 0, "total_comision" => $total_comision, "sede" => $sede, "color" => "#D8FFD4");
				}
				break;

			case 'CASTRO BLANCO LUIS EDUARDO':
				$data_mos = $this->nominas->get_comision_rep_mostrador_luis_e($nom, $mes, $ano);
				foreach ($data_mos->result() as $key) {
					$venta_neta = $key->venta_neta;
					$margen_bruto = $key->margen;
					$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
					$comision = 10.0;
					$valor_comision = $utilidad_bruta * ($comision / 100);
					$total_comision = $valor_comision;
				}
				$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => $margen_bruto, "utilidad_bruta" => $utilidad_bruta, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => 0, "valor_comision_v" => 0, "total_comision" => $total_comision, "sede" => $sede, "color" => "#FEFEB7");
				break;
			case 'OLAYA CALDERON JOSE ALLENDY':
				$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
				$data_tall = $this->nominas->get_comision_rep_taller('JOLAYA', $mes, $ano);
				if ($sede == "MOSTRADOR-MAYOR") {
					foreach ($data_mos->result() as $key) {
						$venta_neta = $key->venta_neta;
						$margen_bruto = $key->margen;
						$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
						$comision = 12.0;
						$valor_comision = $utilidad_bruta * ($comision / 100);
						$total_comision = $valor_comision;
					}
					$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => $margen_bruto, "utilidad_bruta" => $utilidad_bruta, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => 0, "valor_comision_v" => 0, "total_comision" => $total_comision, "sede" => $sede, "color" => "#FECECE");
				} elseif ($sede == "TALLER") {
					foreach ($data_tall->result() as $key) {
						$venta_neta = $key->venta_neta;
						$margen_bruto = $key->margen;
						$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
						$comision = 4.0;
						$valor_comision = $utilidad_bruta * ($comision / 100);
						$total_comision = $valor_comision;
					}
					$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => $margen_bruto, "utilidad_bruta" => $utilidad_bruta, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => 0, "valor_comision_v" => 0, "total_comision" => $total_comision, "sede" => $sede, "color" => "#FECECE");
				}
				break;
			case 'CARRILLO ANGARITA FIDEL':
				$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
				$data_tall = $this->nominas->get_comision_rep_taller('FIDEL', $mes, $ano);
				foreach ($data_mos->result() as $key) {
					$venta_neta = $key->venta_neta;
					$utilidad = $key->utilidad;
				}
				foreach ($data_tall->result() as $key) {
					$venta_neta += $key->venta_neta;
					$utilidad += $key->utilidad;
				}
				$margen_bruto = ($utilidad / $venta_neta) * 100;
				$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
				$comision = 4.0;
				$comision_v = 0.0037;
				$valor_comision_v = $venta_neta * $comision_v;
				$valor_comision = $utilidad_bruta * ($comision / 100);
				$total_comision = $valor_comision + $valor_comision_v;
				$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => number_format($margen_bruto, 2, ",", ","), "utilidad_bruta" => $utilidad_bruta, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => $comision_v, "valor_comision_v" => $valor_comision_v, "total_comision" => $total_comision, "sede" => $sede, "color" => "#CED6FE");
				break;
			case 'RANGEL REYES CRISTIAN ORLANDO':
				$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
				$data_tall = $this->nominas->get_comision_rep_taller('CRANGEL', $mes, $ano);
				foreach ($data_mos->result() as $key) {
					$venta_neta = $key->venta_neta;
					$utilidad = $key->utilidad;
				}
				foreach ($data_tall->result() as $key) {
					$venta_neta += $key->venta_neta;
					$utilidad += $key->utilidad;
				}
				$margen_bruto = ($utilidad / $venta_neta) * 100;
				$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
				$comision = 7.5;
				$valor_comision = $utilidad_bruta * ($comision / 100);
				$total_comision = $valor_comision;
				$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => number_format($margen_bruto, 2, ",", ","), "utilidad_bruta" => $utilidad_bruta, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => 0, "valor_comision_v" => 0, "total_comision" => $total_comision, "sede" => $sede, "color" => "#CED6FE");
				break;
			case 'LOPEZ JUAN MANUEL':
				$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
				$data_tall = $this->nominas->get_comision_rep_taller('JMANUEL', $mes, $ano);
				foreach ($data_mos->result() as $key) {
					$venta_neta = $key->venta_neta;
					$utilidad = $key->utilidad;
				}
				foreach ($data_tall->result() as $key) {
					$venta_neta += $key->venta_neta;
					$utilidad += $key->utilidad;
				}
				$margen_bruto = ($utilidad / $venta_neta) * 100;
				$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
				$comision = 2.0;
				$valor_comision = $utilidad_bruta * ($comision / 100);
				$total_comision = $valor_comision;
				$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => number_format($margen_bruto, 2, ",", ","), "utilidad_bruta" => $utilidad_bruta, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => 0, "valor_comision_v" => 0, "total_comision" => $total_comision, "sede" => $sede, "color" => "#CED6FE");
				break;
			case 'CADENA RAMIREZ FERNANDO ANTONIO':
				$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
				$data_tall = $this->nominas->get_comision_rep_taller('FERNANDO', $mes, $ano);
				foreach ($data_mos->result() as $key) {
					$venta_neta = $key->venta_neta;
					$utilidad = $key->utilidad;
				}
				foreach ($data_tall->result() as $key) {
					$venta_neta += $key->venta_neta;
					$utilidad += $key->utilidad;
				}
				$margen_bruto = ($utilidad / $venta_neta) * 100;
				$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
				$comision = 4.0;
				$comision_v = 0.0037;
				$valor_comision_v = $venta_neta * $comision_v;
				$valor_comision = $utilidad_bruta * ($comision / 100);
				$total_comision = $valor_comision + $valor_comision_v;
				$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => number_format($margen_bruto, 2, ",", ","), "utilidad_bruta" => $utilidad_bruta, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => $comision_v, "valor_comision_v" => $valor_comision_v, "total_comision" => $total_comision, "sede" => $sede, "color" => "#CEFEF8");
				break;
			case 'ABRIL RAMIREZ LEONARDO':
				$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
				$data_tall_M = $this->nominas->get_comision_rep_taller('M-ABRIL', $mes, $ano);
				$data_tall = $this->nominas->get_comision_rep_taller('LEONARDO', $mes, $ano);
				foreach ($data_mos->result() as $key) {
					$venta_neta = $key->venta_neta;
					$utilidad = $key->utilidad;
				}
				foreach ($data_tall->result() as $key) {
					$venta_neta += $key->venta_neta;
					$margen_bruto = $key->margen;
					$utilidad += $key->utilidad;
				}
				foreach ($data_tall_M->result() as $key) {
					$venta_neta += $key->venta_neta;
					$utilidad += $key->utilidad;
				}
				$margen_bruto = ($utilidad / $venta_neta) * 100;
				$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
				$comision = 2.0;
				$valor_comision = $utilidad_bruta * ($comision / 100);
				$total_comision = $valor_comision;
				$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => number_format($margen_bruto, 2, ",", ","), "utilidad_bruta" => $utilidad_bruta, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => 0, "valor_comision_v" => 0, "total_comision" => $total_comision, "sede" => $sede, "color" => "#CEFEF8");
				break;
			case 'ARDILA SANCHEZ JOSUE':
				$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
				$data_tall = $this->nominas->get_comision_rep_taller('JARDILA', $mes, $ano);
				if ($sede == "GIRON MOSTRADOR") {
					foreach ($data_mos->result() as $key) {
						$venta_neta = $key->venta_neta;
						$margen_bruto = $key->margen;
						$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
						$comision = 7.5;
						$valor_comision = $utilidad_bruta * ($comision / 100);
						$total_comision = $valor_comision;
					}
					$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => $margen_bruto, "utilidad_bruta" => $utilidad_bruta, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => 0, "valor_comision_v" => 0, "total_comision" => $total_comision, "sede" => $sede, "color" => "#CEFEF8");
				} elseif ($sede == "GIRON ASEGURADORA-TALLER") {
					foreach ($data_tall->result() as $key) {
						$venta_neta = $key->venta_neta;
						$margen_bruto = $key->margen;
						$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
						$comision = 3.5;
						$valor_comision = $utilidad_bruta * ($comision / 100);
						$total_comision = $valor_comision;
					}
					$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => $margen_bruto, "utilidad_bruta" => $utilidad_bruta, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => 0, "valor_comision_v" => 0, "total_comision" => $total_comision, "sede" => $sede, "color" => "#CEFEF8");
				}
				break;
			case 'OCHOA RUEDA JHON FREDDY':
				$data_mos = $this->nominas->get_comision_rep_mostrador_sin_mayor($nom, $mes, $ano);
				$data_mos_m = $this->nominas->get_comision_rep_mostrados_mayor($nom, $mes, $ano);
				if ($sede == "CHEVROPARTES MAYOR") {
					foreach ($data_mos_m->result() as $key) {
						$venta_neta = $key->venta_neta;
						$margen_bruto = $key->margen;
						$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
						$comision = 0;
						$comision_v = 0.0060;
						$valor_comision_v = $venta_neta * $comision_v;
						$valor_comision = $utilidad_bruta * ($comision / 100);
						$total_comision = $valor_comision + $valor_comision_v;
					}
					$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => $margen_bruto, "utilidad_bruta" => $utilidad_bruta, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => $comision_v, "valor_comision_v" => $valor_comision_v, "total_comision" => $total_comision, "sede" => $sede, "color" => "#FACEFE");
				} elseif ($sede == "CHEVROPARTES MOSTRADOR") {
					foreach ($data_mos->result() as $key) {
						$venta_neta = $key->venta_neta;
						$margen_bruto = $key->margen;
						$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
						$comision = 10.0;
						$valor_comision = $utilidad_bruta * ($comision / 100);
						$total_comision = $valor_comision;
					}
					$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => $margen_bruto, "utilidad_bruta" => $utilidad_bruta, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => 0, "valor_comision_v" => 0, "total_comision" => $total_comision, "sede" => $sede, "color" => "#FACEFE");
				}
				break;
			case 'MEJIA VARGAS OSCAR ALFONSO':
				$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
				foreach ($data_mos->result() as $key) {
					$venta_neta = $key->venta_neta;
					$margen_bruto = $key->margen;
				}
				$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
				$comision = 8.0;
				$comision_v = 0.0040;
				$valor_comision_v = $venta_neta * $comision_v;
				$valor_comision = $utilidad_bruta * ($comision / 100);
				$total_comision = $valor_comision + $valor_comision_v;
				$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => $margen_bruto, "utilidad_bruta" => $utilidad_bruta, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => $comision_v, "valor_comision_v" => $valor_comision_v, "total_comision" => $total_comision, "sede" => $sede, "color" => "#CEFEF8");
				break;
		}
	}
}


?>
<div class="container-fluid" align="center">
	<?php foreach ($data_tabla as $key) {
		$sede_m = $key['sede']; ?>
		<div class="row">
			<div class="col-md-4">
				<div class="info-box mb-3">
					<span class="info-box-icon bg-warning elevation-1"><i class="fas fa-dollar-sign"></i></span>

					<div class="info-box-content">
						<span class="info-box-text" style="font-size: 20px;">Total Vendido <?= $key['sede'] ?></span>
						<span class="info-box-number" style="font-size: 18px;">$<?= number_format($key['venta_neta'], 0, ",", ",") ?></span>
					</div>
					<!-- /.info-box-content -->
				</div>
			</div>
			<div class="col-md-4">
				<div class="info-box mb-3">
					<span class="info-box-icon bg-warning elevation-1"><i class="fas fa-dollar-sign"></i></span>

					<div class="info-box-content">
						<span class="info-box-text" style="font-size: 20px;">Margen</span>
						<span class="info-box-number" style="font-size: 18px;"><?= $key['margen_bruto'] ?>%</span>
					</div>
					<!-- /.info-box-content -->
				</div>
			</div>
			<div class="col-md-4">
				<div class="info-box mb-3">
					<span class="info-box-icon bg-warning elevation-1"><i class="fas fa-dollar-sign"></i></span>

					<div class="info-box-content">
						<span class="info-box-text" style="font-size: 20px;">Comisión</span>
						<span class="info-box-number" style="font-size: 18px;">$<?= number_format($key["total_comision"], 0, ",", ",") ?></span>
					</div>
					<!-- /.info-box-content -->
				</div>
			</div>
		</div>
	<?php } ?>
	<div class="row">
		<div class="col-md-8">
			<div class="card">
				<div class="card-body">
					<div id="chart_ase_rep" style="height: 370px; width: 100%;"></div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="info-box mb-3">
				<span class="info-box-icon bg-info elevation-1"><i class="fas fa-dollar-sign"></i></span>

				<div class="info-box-content">
					<span class="info-box-text" style="font-size: 20px;">Total Vendido</span>
					<span class="info-box-number" style="font-size: 18px;">$<?= number_format($to, 0, ",", ",") ?></span>
				</div>
				<!-- /.info-box-content -->
			</div>
			<?php echo $div_presupuesto ?>
		</div>
	</div>
</div>