<h3 align="center">Informe Posventa actual</h3>
<table class="table">
	<thead align="center">
		<tr>
			<th scope="col" width="300">Sede</th>
			<th scope="col">Meta a cumplir</th>
			<th scope="col">Total Vendido</th>
			<th scope="col">Porcentaje</th>
		</tr>
	</thead>
	<tbody align="center">
		<?php 
				    //modelos
		$this->load->model('presupuesto');
		$centros_costos = "4,40,33,45,3,16,17,13,70,11,29,80,31,46,28,60,15";
		/*GIRON*/
		$cc_tall_giron_gas = "4";
		$cc_tall_giron_dies = "40";
		$cc_tall_giron_col = "33,45";
		$cc_mostrador_giron = "3";
		/*ROSITA*/
		$cc_tall_rosita = "16";
		$cc_mostrador_rosita = "17";
		/*BARRANCA*/
		$cc_tall_barranca_gas = "13";
		$cc_tall_barranca_dies = "70";
		$cc_mostrador_barranca = "11";
		/*BOCONO*/
		$cc_tall_bocono_gas = "29";
		$cc_tall_bocono_dies = "80";
		$cc_tall_bocono_col = "31,46";
		$cc_mostrador_bocono = "28";
					//fechas
		$fecha_ini = $this->nominas->obtener_primer_dia_mes();
		$fecha_fin = $this->nominas->obtener_ultimo_dia_mes();
					//presupuesto dia codiesel
		$to_presupuesto_dia_prin = $this->presupuesto->get_presupuesto_dia($centros_costos);
					//array para total de las sedes
		foreach ($graf_sedes->result() as $key) {
			$data_sedes [] = array('sede'=>$key->sede,'total'=>$key->total);
		}
				    //PORCENTAJE SEDES
		if ($porcen_giron >= 100) {
			$meta_giron = "#92F793";
		}else{
			$meta_giron = "#FD8181";
		}

		if ($porcen_rosita >= 100) {
			$meta_rosita = "#92F793";
		}else{
			$meta_rosita = "#FD8181";
		}

		if ($porcen_bocono >= 100) {
			$meta_bocono = "#92F793";
		}else{
			$meta_bocono = "#FD8181";
		}

		if ($porcen_barranca >= 100) {
			$meta_barranca = "#92F793";
		}else{
			$meta_barranca = "#FD8181";
		}

		if ($porcen_soloc >= 100) {
			$meta_soloc = "#92F793";
		}else{
			$meta_soloc = "#FD8181";
		}

		if ($porcen_chev >= 100) {
			$meta_chev = "#92F793";
		}else{
			$meta_chev = "#FD8181";
		}
					//array para el presupuesto de las sedes
		$to_presupuesto_mes = $this->presupuesto->get_presupuesto_mes_all($fecha_ini->fecha,$fecha_fin->fecha);

		foreach ($to_presupuesto_mes->result() as $key) {
			$porcen_general = ($to_presupuesto_dia_prin->total * 100)/$key->presupuesto;
			if ($porcen_general >= 100) {
				$meta_gen = "#92F793";
			}else{
				$meta_gen = "#FD8181";
			}
			$pres_codiesel [] = array('sede' => $key->sede,'presupuesto'=>$key->presupuesto,'porcen_general'=>$porcen_general,'meta'=>$meta_gen);
		}
				    //ARRAY PARA LOS TALLERES
				    //GIRON
		$v_d_tall_di_g = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_giron_dies);
		$v_d_tall_gas_g = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_giron_gas);
		$v_d_tall_lyp_g = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_giron_col);
		$v_d_tall_mos_g = $this->presupuesto->get_repuestos_mostrador($cc_mostrador_giron);
		$pres_tall_di_g = $pres_codiesel[8]['presupuesto'];
		$pres_tall_gas_g = $pres_codiesel[10]['presupuesto'];
		$pres_tall_lyp_g = $pres_codiesel[9]['presupuesto'];
		$pres_tall_mos_g = $pres_codiesel[11]['presupuesto'];

		$porcen_tall_di_g = ($v_d_tall_di_g->total * 100) / $pres_tall_di_g;
		$porcen_tall_gas_g = ($v_d_tall_gas_g->total * 100) / $pres_tall_gas_g;
		$porcen_tall_lyp_g = ($v_d_tall_lyp_g->total * 100) / $pres_tall_lyp_g;
		$porcen_tall_mos_g = ($v_d_tall_mos_g->total * 100) / $pres_tall_mos_g;

		if ($porcen_tall_di_g >= 100) {
			$meta_di_g = "#92F793";
		}else{
			$meta_di_g = "#FD8181";
		}
		if ($porcen_tall_gas_g >= 100) {
			$meta_gas_g = "#92F793";
		}else{
			$meta_gas_g = "#FD8181";
		}
		if ($porcen_tall_lyp_g >= 100) {
			$meta_lyp_g = "#92F793";
		}else{
			$meta_lyp_g = "#FD8181";
		}
		if ($porcen_tall_mos_g >= 100) {
			$meta_mos_g = "#92F793";
		}else{
			$meta_mos_g = "#FD8181";
		}

		$tall_di_g [] = array('sede'=>'Taller Diesel Girón','total'=>$v_d_tall_di_g->total,'porcen_general'=>$porcen_tall_di_g,'meta'=>$meta_di_g);

		$tall_gas_g [] = array('sede'=>'Taller Gasolina Girón','total'=>$v_d_tall_gas_g->total,'porcen_general'=>$porcen_tall_gas_g,'meta'=>$meta_gas_g);


		$tall_lyp_g [] = array('sede'=>'Taller Colisión Girón','total'=>$v_d_tall_lyp_g->total,'porcen_general'=>$porcen_tall_lyp_g,'meta'=>$meta_lyp_g);


		$tall_rep_g [] = array('sede'=>'Mostrador Girón','total'=>$v_d_tall_mos_g->total,'porcen_general'=>$porcen_tall_mos_g,'meta'=>$meta_mos_g);
				    //TALL GIRON GASOLINA
		$mo_gas_g = $this->presupuesto->get_presupuesto_rep($cc_tall_giron_gas);
		$tot_gas_g = $this->presupuesto->get_presupuesto_tot($cc_tall_giron_gas);
		$rep_gas_g = $this->presupuesto->get_presupuesto_mo($cc_tall_giron_gas);
				    //TALL GIRON DIESEL
		$mo_di_g = $this->presupuesto->get_presupuesto_rep($cc_tall_giron_dies);
		$tot_di_g = $this->presupuesto->get_presupuesto_tot($cc_tall_giron_dies);
		$rep_di_g = $this->presupuesto->get_presupuesto_mo($cc_tall_giron_dies);
					//TALL GIRON COLICION
		$mo_lyp_g = $this->presupuesto->get_presupuesto_rep($cc_tall_giron_col);
		$tot_lyp_g = $this->presupuesto->get_presupuesto_tot($cc_tall_giron_col);
		$rep_lyp_g = $this->presupuesto->get_presupuesto_mo($cc_tall_giron_col);

				    //ROSITA
		$v_d_tall_ro = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_rosita);
		$v_d_tall_mos_ro = $this->presupuesto->get_repuestos_mostrador($cc_mostrador_rosita);
		$pres_tall_gas_ro = $pres_codiesel[12]['presupuesto'];
		$pres_tall_mos_ro = $pres_codiesel[13]['presupuesto'];

		$porcen_tall_gas_ro = ($v_d_tall_ro->total * 100) / $pres_tall_gas_ro;
		$porcen_tall_mos_ro = ($v_d_tall_mos_ro->total * 100) / $pres_tall_mos_ro;

		if ($porcen_tall_gas_ro >= 100) {
			$meta_gas_ro = "#92F793";
		}else{
			$meta_gas_ro = "#FD8181";
		}
		if ($porcen_tall_mos_ro >= 100) {
			$meta_mos_ro = "#92F793";
		}else{
			$meta_mos_ro = "#FD8181";
		}

		$tall_ro [] = array('sede'=>'Taller Gasolina la Rosita','total'=>$v_d_tall_ro->total,'porcen_general'=>$porcen_tall_gas_ro,'meta'=>$meta_gas_ro);


		$tall_mos_ro [] = array('sede'=>'Mostrador la Rosita','total'=>$v_d_tall_mos_ro->total,'porcen_general'=>$porcen_tall_mos_ro,'meta'=>$meta_mos_ro);

				    //DETALLE TALLER GASOLINA ROSITA
		$mo_gas_ro = $this->presupuesto->get_presupuesto_rep($cc_tall_rosita);
		$tot_gas_ro = $this->presupuesto->get_presupuesto_tot($cc_tall_rosita);
		$rep_gas_ro = $this->presupuesto->get_presupuesto_mo($cc_tall_rosita);

				    //BOCONO
		$v_d_tall_di_bo = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_bocono_dies);
		$v_d_tall_gas_bo = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_bocono_gas);
		$v_d_tall_lyp_bo = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_bocono_col);
		$v_d_tall_mos_bo = $this->presupuesto->get_repuestos_mostrador($cc_mostrador_bocono);
		$pres_tall_di_bo = $pres_codiesel[14]['presupuesto'];
		$pres_tall_gas_bo = $pres_codiesel[15]['presupuesto'];
		$pres_tall_lyp_bo = $pres_codiesel[16]['presupuesto'];
		$pres_tall_mos_bo = $pres_codiesel[17]['presupuesto'];

		$porcen_tall_di_bo = ($v_d_tall_di_bo->total * 100) / $pres_tall_di_bo;
		$porcen_tall_gas_bo = ($v_d_tall_gas_bo->total * 100) / $pres_tall_gas_bo;
		$porcen_tall_lyp_bo = ($v_d_tall_lyp_bo->total * 100) / $pres_tall_lyp_bo;
		$porcen_tall_mos_bo = ($v_d_tall_mos_bo->total * 100) / $pres_tall_mos_bo;

					//TALL BOCONO GASOLINA
		$mo_gas_bo = $this->presupuesto->get_presupuesto_rep($cc_tall_bocono_gas);
		$tot_gas_bo = $this->presupuesto->get_presupuesto_tot($cc_tall_bocono_gas);
		$rep_gas_bo = $this->presupuesto->get_presupuesto_mo($cc_tall_bocono_gas);
				    //TALL BOCONO DIESEL
		$mo_di_bo = $this->presupuesto->get_presupuesto_rep($cc_tall_bocono_dies);
		$tot_di_bo = $this->presupuesto->get_presupuesto_tot($cc_tall_bocono_dies);
		$rep_di_bo = $this->presupuesto->get_presupuesto_mo($cc_tall_bocono_dies);
					//TALL BOCONO COLICION
		$mo_lyp_bo = $this->presupuesto->get_presupuesto_rep($cc_tall_bocono_col);
		$tot_lyp_bo = $this->presupuesto->get_presupuesto_tot($cc_tall_bocono_col);
		$rep_lyp_bo = $this->presupuesto->get_presupuesto_mo($cc_tall_bocono_col);

		if ($porcen_tall_di_bo >= 100) {
			$meta_di_bo = "#92F793";
		}else{
			$meta_di_bo = "#FD8181";
		}
		if ($porcen_tall_gas_bo >= 100) {
			$meta_gas_bo = "#92F793";
		}else{
			$meta_gas_bo = "#FD8181";
		}
		if ($porcen_tall_lyp_bo >= 100) {
			$meta_lyp_bo = "#92F793";
		}else{
			$meta_lyp_bo = "#FD8181";
		}
		if ($porcen_tall_mos_bo >= 100) {
			$meta_mos_bo = "#92F793";
		}else{
			$meta_mos_bo = "#FD8181";
		}

		$tall_di_bo [] = array('sede'=>'Taller Diesel Boconó','total'=>$v_d_tall_di_bo->total,'porcen_general'=>$porcen_tall_di_bo,'meta'=>$meta_di_bo);


		$tall_gas_bo [] = array('sede'=>'Taller Gasolina Boconó','total'=>$v_d_tall_gas_bo->total,'porcen_general'=>$porcen_tall_gas_bo,'meta'=>$meta_gas_bo);


		$tall_lyp_bo [] = array('sede'=>'Taller Colisión Boconó','total'=>$v_d_tall_lyp_bo->total,'porcen_general'=>$porcen_tall_lyp_bo,'meta'=>$meta_lyp_bo);


		$tall_mos_bo [] = array('sede'=>'Mostrador Boconó','total'=>$v_d_tall_mos_bo->total,'porcen_general'=>$porcen_tall_mos_bo,'meta'=>$meta_mos_bo);

					//BARRANCABERMEJA
		$v_d_tall_di_ba = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_barranca_dies);
		$v_d_tall_gas_ba = $this->presupuesto->get_total_presupuesto_by_tallerx2($cc_tall_barranca_gas);
		$v_d_tall_mos_ba = $this->presupuesto->get_repuestos_mostrador($cc_mostrador_barranca);
		$pres_tall_di_ba = $pres_codiesel[5]['presupuesto'];
		$pres_tall_gas_ba = $pres_codiesel[6]['presupuesto'];
		$pres_tall_mos_ba = $pres_codiesel[7]['presupuesto'];

		$porcen_tall_di_ba = ($v_d_tall_di_ba->total * 100) / $pres_tall_di_ba;
		$porcen_tall_gas_ba = ($v_d_tall_gas_ba->total * 100) / $pres_tall_gas_ba;
		$porcen_tall_mos_ba = ($v_d_tall_mos_ba->total * 100) / $pres_tall_mos_ba;

					//TALL BOCONO GASOLINA
		$mo_gas_ba = $this->presupuesto->get_presupuesto_rep($cc_tall_barranca_gas);
		$tot_gas_ba = $this->presupuesto->get_presupuesto_tot($cc_tall_barranca_gas);
		$rep_gas_ba = $this->presupuesto->get_presupuesto_mo($cc_tall_barranca_gas);
				    //TALL BOCONO DIESEL
		$mo_di_ba = $this->presupuesto->get_presupuesto_rep($cc_tall_barranca_dies);
		$tot_di_ba = $this->presupuesto->get_presupuesto_tot($cc_tall_barranca_dies);
		$rep_di_ba = $this->presupuesto->get_presupuesto_mo($cc_tall_barranca_dies);

		if ($porcen_tall_di_ba >= 100) {
			$meta_di_ba = "#92F793";
		}else{
			$meta_di_ba = "#FD8181";
		}
		if ($porcen_tall_gas_ba >= 100) {
			$meta_gas_ba = "#92F793";
		}else{
			$meta_gas_ba = "#FD8181";
		}
		if ($porcen_tall_mos_ba >= 100) {
			$meta_mos_ba = "#92F793";
		}else{
			$meta_mos_ba = "#FD8181";
		}

		$tall_di_ba [] = array('sede'=>'Taller Diesel Barrancabermeja','total'=>$v_d_tall_di_ba->total,'porcen_general'=>$porcen_tall_di_ba,'meta'=>$meta_di_ba);


		$tall_gas_ba [] = array('sede'=>'Taller Gasolina Barrancabermeja','total'=>$v_d_tall_gas_ba->total,'porcen_general'=>$porcen_tall_gas_ba,'meta'=>$meta_gas_ba);


		$tall_mos_ba [] = array('sede'=>'Mostrador Barrancabermeja','total'=>$v_d_tall_mos_ba->total,'porcen_general'=>$porcen_tall_mos_ba,'meta'=>$meta_mos_ba);

		?>
		<!--GENERAL-->
		<tr>
			<th style="text-align: left;"><i class="fas fa-arrow-down"></i> <a href="#" onclick="mostrar_sedes();"><?=$pres_codiesel[0]['sede']?></a></th>
			<td>$<?=number_format($pres_codiesel[0]['presupuesto'],0,",",",")?></td>
			<td>$<?=number_format($to_presupuesto_dia_prin->total,0,",",",")?></td>
			<td style="background-color: <?=$pres_codiesel[0]['meta']?>"><?=round($pres_codiesel[0]['porcen_general']);?>%</td>
		</tr>    
		<tr>
			<!--GIRON-->
			<tr id="t_sedes_giron" style="background-color: #ABB2B9;">
				<th style="text-align: left;">&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <a href="#" onclick="mostrar_tall_g();"><?=$data_sedes[0]['sede']?></a></th>
				<td>$<?=number_format($pres_codiesel[4]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($data_sedes[0]['total'],0,",",",")?></td>
				<td style="background-color: <?=$meta_giron?>"><?=round($porcen_giron);?>%</td>
			</tr>
			<!--TALLER DIESEL GIRON-->
			<tr id="tall_di_g" style="background-color: #ABB2B9;">
				<th style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <a href="#" onclick="mostrar_det_tall_di_g();"><?=$tall_di_g[0]['sede']?></a></th>
				<td>$<?=number_format($pres_codiesel[8]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($tall_di_g[0]['total'],0,",",",")?></td>
				<td style="background-color: <?=$tall_di_g[0]['meta']?>"><?=round($tall_di_g[0]['porcen_general']);?>%</td>
			</tr>
			<tr id="det_tall_di_g" style="background-color: #ABB2B9;">
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MO: $<?=number_format($mo_di_g->total,0,",",",")?></th>
				<td>TOT: $<?=number_format($tot_di_g->total,0,",",",")?></td>
				<td>REP: $<?=number_format($rep_di_g->total,0,",",",")?></td>
				<td></td>
			</tr>

			<!--TALLER GASOLINA GIRON-->
			<tr id="tall_gas_g" style="background-color: #ABB2B9;">
				<th style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <a href="#" onclick="mostrar_det_tall_gas_g();"><?=$tall_gas_g[0]['sede']?></a></th>
				<td>$<?=number_format($pres_codiesel[10]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($tall_gas_g[0]['total'],0,",",",")?></td>
				<td style="background-color: <?=$tall_gas_g[0]['meta']?>"><?=round($tall_gas_g[0]['porcen_general']);?>%</td>
			</tr>
			<tr id="det_tall_gas_g" style="background-color: #ABB2B9;">
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MO: $<?=number_format($mo_gas_g->total,0,",",",")?></th>
				<td>TOT: $<?=number_format($tot_gas_g->total,0,",",",")?></td>
				<td>REP: $<?=number_format($rep_gas_g->total,0,",",",")?></td>
				<td></td>
			</tr>

			<!--TALLER COLICION GIRON-->
			<tr id="tall_lyp_g" style="background-color: #ABB2B9;">
				<th style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <a href="#" onclick="mostrar_det_tall_lyp_g();"><?=$tall_lyp_g[0]['sede']?></a></th>
				<td>$<?=number_format($pres_codiesel[9]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($tall_lyp_g[0]['total'],0,",",",")?></td>
				<td style="background-color: <?=$tall_lyp_g[0]['meta']?>"><?=round($tall_lyp_g[0]['porcen_general']);?>%</td>
			</tr>
			<tr id="det_tall_lyp_g" style="background-color: #ABB2B9;">
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MO: $<?=number_format($mo_lyp_g->total,0,",",",")?></th>
				<td>TOT: $<?=number_format($tot_lyp_g->total,0,",",",")?></td>
				<td>REP: $<?=number_format($rep_lyp_g->total,0,",",",")?></td>
				<td></td>
			</tr>

			<!--MOSTRADOR TALLER GIRON-->
			<tr id="tall_mos_g" style="background-color: #ABB2B9;">
				<th style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <?=$tall_rep_g[0]['sede']?></th>
				<td>$<?=number_format($pres_codiesel[11]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($tall_rep_g[0]['total'],0,",",",")?></td>
				<td style="background-color: <?=$tall_rep_g[0]['meta']?>"><?=round($tall_rep_g[0]['porcen_general']);?>%</td>
			</tr>

			<!--ROSITA-->
			<tr id="t_sedes_rosita" style="background-color: #B2BABB;">
				<th style="text-align: left;">&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <a href="#" onclick="mostrar_tall_ro();"><?=$data_sedes[1]['sede']?></a></th>
				<td>$<?=number_format($pres_codiesel[3]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($data_sedes[1]['total'],0,",",",")?></td>
				<td style="background-color: <?=$meta_rosita?>"><?=round($porcen_rosita);?>%</td>
			</tr>
			<!--TALLER GASOLINA ROSITA-->
			<tr id="tall_gas_ro" style="background-color: #B2BABB;">
				<th style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <a href="#" onclick="mostrar_det_tall_ro();"><?=$tall_ro[0]['sede']?></a></th>
				<td>$<?=number_format($pres_codiesel[12]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($tall_ro[0]['total'],0,",",",")?></td>
				<td style="background-color: <?=$tall_ro[0]['meta']?>"><?=round($tall_ro[0]['porcen_general']);?>%</td>
			</tr>
			<tr id="det_tall_ro" style="background-color: #B2BABB;">
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MO: $<?=number_format($mo_gas_ro->total,0,",",",")?></th>
				<td>TOT: $<?=number_format($tot_gas_ro->total,0,",",",")?></td>
				<td>REP: $<?=number_format($rep_gas_ro->total,0,",",",")?></td>
				<td></td>
			</tr>

			<!--MOSTRADOR ROSITA-->
			<tr id="tall_mos_ro" style="background-color: #B2BABB;">
				<th style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <?=$tall_mos_ro[0]['sede']?></th>
				<td>$<?=number_format($pres_codiesel[13]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($tall_mos_ro[0]['total'],0,",",",")?></td>
				<td style="background-color: <?=$tall_mos_ro[0]['meta']?>"><?=round($tall_mos_ro[0]['porcen_general']);?>%</td>
			</tr>

			<!--BARRANCA-->
			<tr id="t_sedes_barranca" style="background-color: #EAECEE;">
				<th style="text-align: left;">&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <a href="#" onclick="mostrar_tall_ba();"><?=$data_sedes[2]['sede']?></a></th>
				<td>$<?=number_format($pres_codiesel[2]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($data_sedes[2]['total'],0,",",",")?></td>
				<td style="background-color: <?=$meta_barranca?>"><?=round($porcen_barranca);?>%</td>
			</tr>
			<!--TALLER DIESEL BARRANCA-->
			<tr id="tall_di_ba" style="background-color: #EAECEE;">
				<th style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <a href="#" onclick="mostrar_det_tall_di_ba();"><?=$tall_di_ba[0]['sede']?></a></th>
				<td>$<?=number_format($pres_codiesel[5]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($tall_di_ba[0]['total'],0,",",",")?></td>
				<td style="background-color: <?=$tall_di_ba[0]['meta']?>"><?=round($tall_di_ba[0]['porcen_general']);?>%</td>
			</tr>
			<tr id="det_tall_di_ba" style="background-color: #EAECEE;">
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MO: $<?=number_format($mo_di_ba->total,0,",",",")?></th>
				<td>TOT: $<?=number_format($tot_di_ba->total,0,",",",")?></td>
				<td>REP: $<?=number_format($rep_di_ba->total,0,",",",")?></td>
				<td></td>
			</tr>
			<!--TALLER GASOLINA BARRANCA-->
			<tr id="tall_gas_ba" style="background-color: #EAECEE;">
				<th style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <a href="#" onclick="mostrar_det_tall_gas_ba();"><?=$tall_gas_ba[0]['sede']?></a></th>
				<td>$<?=number_format($pres_codiesel[6]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($tall_gas_ba[0]['total'],0,",",",")?></td>
				<td style="background-color: <?=$tall_gas_ba[0]['meta']?>"><?=round($tall_gas_ba[0]['porcen_general']);?>%</td>
			</tr>
			<tr id="det_tall_gas_ba" style="background-color: #EAECEE;">
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MO: $<?=number_format($mo_gas_ba->total,0,",",",")?></th>
				<td>TOT: $<?=number_format($tot_gas_ba->total,0,",",",")?></td>
				<td>REP: $<?=number_format($rep_gas_ba->total,0,",",",")?></td>
				<td></td>
			</tr>
			<!--MOSTRADOR BARRANCA-->
			<tr id="tall_mos_ba" style="background-color: #EAECEE;">
				<th style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <?=$tall_mos_ba[0]['sede']?></th>
				<td>$<?=number_format($pres_codiesel[7]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($tall_mos_ba[0]['total'],0,",",",")?></td>
				<td style="background-color: <?=$tall_mos_ba[0]['meta']?>"><?=round($tall_mos_ba[0]['porcen_general']);?>%</td>
			</tr>

			<!--BOCONO-->
			<tr id="t_sedes_bocono" style="background-color: #D5DBDB;">
				<th style="text-align: left;">&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <a href="#" onclick="mostrar_tall_bo();"><?=$data_sedes[3]['sede']?></a></th>
				<td>$<?=number_format($pres_codiesel[1]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($data_sedes[3]['total'],0,",",",")?></td>
				<td style="background-color: <?=$meta_bocono?>"><?=round($porcen_bocono);?>%</td>
			</tr>

			<!--TALLER DIESEL BOCONO-->
			<tr id="tall_di_bo" style="background-color: #D5DBDB;">
				<th style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <a href="#" onclick="mostrar_det_tall_di_bo();"><?=$tall_di_bo[0]['sede']?></a></th>
				<td>$<?=number_format($pres_codiesel[14]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($tall_di_bo[0]['total'],0,",",",")?></td>
				<td style="background-color: <?=$tall_di_bo[0]['meta']?>"><?=round($tall_di_bo[0]['porcen_general']);?>%</td>
			</tr>
			<tr id="det_tall_di_bo" style="background-color: #D5DBDB;">
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MO: $<?=number_format($mo_di_bo->total,0,",",",")?></th>
				<td>TOT: $<?=number_format($tot_di_bo->total,0,",",",")?></td>
				<td>REP: $<?=number_format($rep_di_bo->total,0,",",",")?></td>
				<td></td>
			</tr>

			<!--TALLER GASOLINA BOCONO-->
			<tr id="tall_gas_bo" style="background-color: #D5DBDB;">
				<th style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <a href="#" onclick="mostrar_det_tall_gas_bo();"><?=$tall_gas_bo[0]['sede']?></a></th>
				<td>$<?=number_format($pres_codiesel[15]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($tall_gas_bo[0]['total'],0,",",",")?></td>
				<td style="background-color: <?=$tall_gas_bo[0]['meta']?>"><?=round($tall_gas_bo[0]['porcen_general']);?>%</td>
			</tr>
			<tr id="det_tall_gas_bo" style="background-color: #D5DBDB;">
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MO: $<?=number_format($mo_gas_bo->total,0,",",",")?></th>
				<td>TOT: $<?=number_format($tot_gas_bo->total,0,",",",")?></td>
				<td>REP: $<?=number_format($rep_gas_bo->total,0,",",",")?></td>
				<td></td>
			</tr>

			<!--TALLER COLICION BOCONO-->
			<tr id="tall_lyp_bo" style="background-color: #D5DBDB;">
				<th style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <a href="#" onclick="mostrar_det_tall_lyp_bo();"><?=$tall_lyp_bo[0]['sede']?></a></th>
				<td>$<?=number_format($pres_codiesel[16]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($tall_lyp_bo[0]['total'],0,",",",")?></td>
				<td style="background-color: <?=$tall_lyp_bo[0]['meta']?>"><?=round($tall_lyp_bo[0]['porcen_general']);?>%</td>
			</tr>
			<tr id="det_tall_lyp_bo" style="background-color: #D5DBDB;">
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MO: $<?=number_format($mo_lyp_bo->total,0,",",",")?></th>
				<td>TOT: $<?=number_format($tot_lyp_bo->total,0,",",",")?></td>
				<td>REP: $<?=number_format($rep_lyp_bo->total,0,",",",")?></td>
				<td></td>
			</tr>

			<!--MOSTRADOR BOCONO-->
			<tr id="tall_mos_bo" style="background-color: #D5DBDB;">
				<th style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <?=$tall_mos_bo[0]['sede']?></th>
				<td>$<?=number_format($pres_codiesel[17]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($tall_mos_bo[0]['total'],0,",",",")?></td>
				<td style="background-color: <?=$tall_mos_bo[0]['meta']?>"><?=round($tall_mos_bo[0]['porcen_general']);?>%</td>
			</tr>



			<!--SOLOCHEVROLET-->
			<tr id="t_sedes_soloch" style="background-color: #BFC9CA;">
				<th style="text-align: left;">&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <?=$data_sedes[4]['sede']?></th>
				<td>$<?=number_format($pres_codiesel[20]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($data_sedes[4]['total'],0,",",",")?></td>
				<td style="background-color: <?=$meta_soloc?>"><?=round($porcen_soloc);?>%</td>
			</tr>

			<!--CHEVROPARTES-->
			<tr id="t_sedes_chevr" style="background-color: #D7DBDD;">
				<th style="text-align: left;">&nbsp;&nbsp;<i class="fas fa-arrow-right"></i> <?=$data_sedes[5]['sede']?></th>
				<td>$<?=number_format($pres_codiesel[19]['presupuesto'],0,",",",")?></td>
				<td>$<?=number_format($data_sedes[5]['total'],0,",",",")?></td>
				<td style="background-color: <?=$meta_chev?>"><?=round($porcen_chev);?>%</td>
			</tr>

		</tr>
	</tbody>
</table>