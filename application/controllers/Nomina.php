<?php


/**
 * 
 */
class Nomina extends CI_Controller
{

	public function index()
	{
		/* validamos si hay datos de session 
		 * si no hay se redireccion al login y se destruye la session
		 */
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos necesarios
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('nominas');
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//$allempleados = $this->nominas->listar_empleados();
			$allempleados = null;
			$bodegas = $this->nominas->listar_bodegas();
			//prueba de menu
			$nom_bod = "COMISIONES";
			$fecha = "";
			$id_usu = "";
			$msg = "0";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//echo $id_usu;

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'allempleados' => $allempleados, 'bodegas' => $bodegas, 'titulo' => $nom_bod, 'fecha' => $fecha, 'msg' => $msg);
			//abrimos la vista
			$this->load->view('comisiones', $arr_user);
		}
	}

	public function cargar_info_emp()
	{
		$this->load->model('nominas');
		$this->load->model('usuarios');
		$this->load->model('menus');
		$this->load->model('perfiles');
		$bod = $this->input->post('combo_bodega');
		$nom_bod = "";
		//echo "<script>alert('".$bod."')</script>";
		if ($bod == 1) {
			$nom_bod = "GASOLINA GIRON";
			$bod = "1";
		} elseif ($bod == 3) {
			$nom_bod = "DIESEL GIRON";
			$bod = "3";
		} elseif ($bod == 5) {
			$nom_bod = "GASOLINA BARRANCA";
			$bod = "5";
		}elseif ($bod == 6) {
			$nom_bod = "GASOLINA BARRANCA";
			$bod = "6";
		} elseif ($bod == 4) {
			$nom_bod = "ROSITA";
			$bod = "4";
		} elseif ($bod == 7) {
			$nom_bod = "GASOLINA BOCONO";
			$bod = "7";
		}elseif ($bod == 8) {
			$nom_bod = "DIESEL BOCONO";
			$bod = "8";
		} elseif ($bod == "all") {
			$nom_bod = "TODOS";
			$bod = "1,3,5,6,4,7,8";
		} else {
			$nom_bod = "COMISIONES";
		}
		//echo $nom_bod;
		$fecha_mes_ini = $this->nominas->obtener_primer_dia_mes_dma();
		$val_mes_ano = $this->nominas->validar_mes_ano($bod);
		//echo "<script>alert('".$val_mes_ano->num."')</script>";
		$usu = $this->session->userdata('user');
		$pass = $this->session->userdata('pass');
		//obtenemos el perfil del usuario
		$perfil_user = $this->perfiles->getPerfilByUser($usu);
		//cargamos la informacion del usuario y la pasamos a un array
		$userinfo = $this->usuarios->getUserByName($usu);
		$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
		//$allsubmenus = $this->menus->getAllSubmenus();
		$allperfiles = $this->perfiles->getAllPerfiles();
		$allempleados = $this->nominas->listar_empleados_fecha($fecha_mes_ini->fecha, $bod);
		$tall_operarios_intranet = $this->nominas->get_tall_operarios_intranet($bod);
		//prueba de menu
		$flag = 2;
		$id_usu = "";
		$fecha_emp = $fecha_mes_ini->fecha;
		foreach ($userinfo->result() as $key) {
			$id_usu = $key->id_usuario;
		}
		//echo $id_usu;

		if ($val_mes_ano->num != 0) {
			$msg = 0;
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'allempleados' => $tall_operarios_intranet, 'titulo' => $nom_bod, 'fecha' => $fecha_emp, 'msg' => $msg, 'bod' => $bod);
			$this->load->view('comisiones', $arr_user);
		} else if ($val_mes_ano->num == 0) {
			//print_r($empleados->result());
			$msg = 0;
			foreach ($allempleados->result() as $emp) {
				$data = array('nit' => $emp->operario, 'Presupuesto' => "0", 'comision' => "0", 'otros' => "0", 'cargo' => "null", 'contrato' => $emp->contrato, 'nombres' => $emp->nombres, 'bodega' => $emp->bodega, 'patio' => $emp->patio);
				if ($this->nominas->insert_comision($data)) {
					$msg = 2;
				} else {
					$msg = 1;
				}
			}
			$tall_operarios_intranet = $this->nominas->get_tall_operarios_intranet($bod);
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'allempleados' => $tall_operarios_intranet, 'titulo' => $nom_bod, 'fecha' => $fecha_emp, 'msg' => $msg, 'bod' => $bod);
			$this->load->view('comisiones', $arr_user);
		}
	}


	public function cargar_datos()
	{
		$idu = $this->input->get('idu');
		$bod = $this->input->get('bod');
		echo '
		<form method="POST" action="' . base_url() . 'nomina/insert_nomina">
	        <div class="form-group">
				<label for="comision">Comision</label>
				<input type="number" class="form-control" name="comision" placeholder="Ingrese un valor">
				<input type="text" style="display: none;" class="form-control" name="idu" value="' . $idu . '">
				<input type="text" style="display: none;" class="form-control" name="bod" value="' . $bod . '">
			</div>
			<div class="form-group">
				<label for="presupuesto">Presupuesto</label>
				<input type="number" class="form-control" name="presupuesto" placeholder="Ingrese un valor">
			</div>
			<div class="form-group">
				<label for="otro">Otro</label>
				<input type="number" class="form-control" name="otro" placeholder="Ingrese un valor">
			</div>
			<div class="border-top my-3"></div>
			<button class="btn btn-primary btn-sm btn-block"><i class="far fa-save"></i> Guardar</button>
	    </form>

		';
		//echo $bod;
	}


	public function update_all_nomina()
	{
		$bod = $this->input->get('bod');
		//echo $bod;
		echo '
		<form method="POST" action="' . base_url() . 'nomina/update_nomina">
	        <div class="form-group">
				<label for="comision">Comision</label>
				<input type="number" class="form-control" name="comision" placeholder="Ingrese un valor">
				<input type="text" style="display: none;" class="form-control" name="bod" value="' . $bod . '">
			</div>
			<div class="form-group">
				<label for="presupuesto">Presupuesto</label>
				<input type="number" class="form-control" name="presupuesto" placeholder="Ingrese un valor">
			</div>
			<div class="form-group">
				<label for="otro">Otro</label>
				<input type="number" class="form-control" name="otro" placeholder="Ingrese un valor">
			</div>
			<div class="border-top my-3"></div>
			<button class="btn btn-primary btn-sm btn-block"><i class="far fa-save"></i> Guardar</button>
	    </form>

		';
	}



	public function insert_nomina()
	{
		/* validamos si hay datos de session 
		 * si no hay se redireccion al login y se destruye la session
		 */
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos
			$this->load->model('nominas');
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			//traemos los datos para el update
			$idu = $this->input->post('idu');
			$bod = $this->input->post('bod');
			//echo "<script>alert(".$bod.")</script>";
			//echo $bod;
			$nom_bod = "";
			if ($bod == 1) {
				$nom_bod = "GASOLINA GIRON";
				$bod = "1,13";
			} elseif ($bod == 3) {
				$nom_bod = "DIESEL GIRON";
				$bod = "3";
			} elseif ($bod == 11) {
				$nom_bod = "ELECTRICISTAS";
			} elseif ($bod == 5) {
				$nom_bod = "GASOLINA BARRANCA";
			} elseif ($bod == 6) {
				$nom_bod = "DIESEL BARRANCA";
			} elseif ($bod == 4) {
				$nom_bod = "GASOLINA ROSITA";
			} elseif ($bod == 7) {
				$nom_bod = "GASOLINA BOCONO";
				$bod = "7,14";
			} elseif ($bod == 12) {
				$nom_bod = "ALINEADORES";
			} elseif ($bod == 8) {
				$nom_bod = "DIESEL BOCONO";
			} elseif ($bod == "all") {
				$nom_bod = "TODOS";
				$bod = "1,13,3,11,5,6,4,7,12,8,14";
			} else {
				$nom_bod = "COMISIONES";
			}
			$fecha_mes_ini = $this->nominas->obtener_primer_dia_mes_dma();
			$comision = $this->input->post('comision');
			$presupuesto = $this->input->post('presupuesto');
			$otro = $this->input->post('otro');
			//echo $bod;
			//echo $idu." ".$comision." ".$presupuesto." ".$otro." mes".$mes." ano".$ano;
			$data = array('Comision' => $comision, 'Presupuesto' => $presupuesto, 'Otros' => $otro);
			$msg = 0;
			if ($bool = $this->nominas->update_comision($data, $idu)) {
				$msg = 1;
			} else {
				$msg = 2;
			}
			//$fecha_emp = $this->nominas->obtener_primer_dia_mes();
			//$fecha_emp = "01/".$mes."/".$ano;
			//echo $mes." ".$ano;
			$val_mes_ano = $this->nominas->validar_mes_ano($bod);
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$allempleados = $this->nominas->listar_empleados_fecha($fecha_mes_ini->fecha, $bod);
			$bodegas = $this->nominas->listar_bodegas();
			$tall_operarios_intranet = $this->nominas->get_tall_operarios_intranet($bod);
			//prueba de menu
			$flag = 2;
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//echo $id_usu;

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'allempleados' => $tall_operarios_intranet, 'bodegas' => $bodegas, 'titulo' => $nom_bod, 'fecha' => $fecha_mes_ini->fecha, 'msg' => $msg, 'bod' => $bod);

			$this->load->view('comisiones', $arr_user);
		}
	}

	public function update_nomina()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos
			$this->load->model('nominas');
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$bod = $this->input->post('bod');
			$pres = $this->input->post('presupuesto');
			$com = $this->input->post('comision');
			$otro = $this->input->post('otro');
			//echo $mes." ".$ano." ".$bod." ".$pres." ".$com." ".$otro;
			$fecha_emp = $this->nominas->obtener_primer_dia_mes_dma();
			$allempleados = $this->nominas->listar_empleados_fecha($fecha_emp->fecha, $bod);
			$nom_bod = "";
			if ($bod == 1) {
				$nom_bod = "GASOLINA GIRON";
				$bod = "1,13";
			} elseif ($bod == 3) {
				$nom_bod = "DIESEL GIRON";
			} elseif ($bod == 11) {
				$nom_bod = "ELECTRICISTAS";
			} elseif ($bod == 5) {
				$nom_bod = "GASOLINA BARRANCA";
			} elseif ($bod == 6) {
				$nom_bod = "DIESEL BARRANCA";
			} elseif ($bod == 4) {
				$nom_bod = "GASOLINA ROSITA";
			} elseif ($bod == 7) {
				$nom_bod = "GASOLINA BOCONO";
				$bod = "7,14";
			} elseif ($bod == 12) {
				$nom_bod = "ALINEADORES";
			} elseif ($bod == 8) {
				$nom_bod = "DIESEL BOCONO";
			} elseif ($bod == "all") {
				$nom_bod = "TODOS";
				$bod = "1,13,3,11,5,6,4,7,12,8,14";
			} else {
				$nom_bod = "COMISIONES";
			}
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$tall_operarios_intranet = $this->nominas->get_tall_operarios_intranet($bod);
			//prueba de menu
			$flag = 2;
			$id_usu = "";
			$msg = 0;
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			foreach ($allempleados->result() as $key) {
				$data = array('Presupuesto' => $pres, 'Comision' => $com, 'Otros' => $otro);
				if ($this->nominas->update_comision_all($data, $key->bodega)) {
					$msg = 1;
				} else {
					$msg = 2;
				}
			}
			if ($fecha_emp->fecha) {
			}
			$fechaa = "..::" . $fecha_emp->fecha . "::..";
			$tall_operarios_intranet = $this->nominas->get_tall_operarios_intranet($bod);
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'allempleados' => $tall_operarios_intranet, 'titulo' => $nom_bod, 'fecha' => $fechaa, 'msg' => $msg, 'bod' => $bod);
			$this->load->view('comisiones', $arr_user);
		}
	}

	public function nomina_accesorios()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos necesarios
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('nominas');
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();

			$mes = null !== $this->input->POST('mes') ? $this->input->POST('mes') : null;
			$ano = null !== $this->input->POST('ano') ? $this->input->POST('ano') : null;

			$nomina_acc = $this->nominas->get_nomina_accesorios("10,17",$ano,$mes);

			$to_acc = 0;
			$to_rep = 0;
			$to_tot = 0;
			foreach ($nomina_acc->result() as $key) {
				$to_rep += $key->REPUESTOS;
				$to_acc += $key->ACCESORIOS;
				$to_tot += ($key->REPUESTOS + $key->ACCESORIOS);
			}

			$data_tot[] = array('total' => "Totales", 'rep' => $to_rep, 'acc' => $to_acc, 'to' => $to_tot);


			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'nomina_acc' => $nomina_acc, 'totales' => $data_tot);
			//abrimos la vista
			$this->load->view('nomina_accesorios', $arr_user);
		}
	}

	public function nomina_serv_rep()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos necesarios
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('nominas');
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();

			$mes = null !== $this->input->POST('mes') ? $this->input->POST('mes') : null;
			$ano = null !== $this->input->POST('ano') ? $this->input->POST('ano') : null;

			$nomina_acc = $this->nominas->get_nomina_serv_rep("8,14,16,17,22",$ano,$mes);

			$to_acc = 0;
			$to_rep = 0;
			$to_lub = 0;
			$to_tot = 0;
			foreach ($nomina_acc->result() as $key) {
				$to_rep += $key->REPUESTOS;
				$to_acc += $key->ACCESORIOS;
				$to_lub += $key->LUBRICANTES;
				$to_tot += ($key->REPUESTOS + $key->ACCESORIOS + $key->LUBRICANTES);
			}
			$data_tot[] = array('total' => "Totales", 'rep' => $to_rep, 'acc' => $to_acc, 'lub' => $to_lub, 'to' => $to_tot);


			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'nomina_acc' => $nomina_acc, 'totales' => $data_tot);
			//abrimos la vista
			$this->load->view('nomina_serv_rep', $arr_user);
		}
	}

	public function nomina_repuestos()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos necesarios
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('nominas');
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();

			/*CALCULO DE COMISIONES*/
			//$com_rep_mos = $this->nominas->get_comision_rep_mostrador();
			//$com_rep_mos_m = $this->nominas->get_comision_rep_mostrados_mayor();
			//$com_rep_tal = $this->nominas->get_comision_rep_taller();


			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu);
			//abrimos la vista
			$this->load->view('nomina_rep_giron', $arr_user);
			/*if ($com_rep_mos_arr[$i]['nombre'] == $com_rep_mos_arr[$j]['nombre']) {
							$nombre = $com_rep_mos_arr[$i]["nombre"];
							$valor = $com_rep_mos_arr[$i]["valor"] + $com_rep_mos_arr[$j]["valor"];
							$utilidad = $com_rep_mos_arr[$i]["utilidad"];
							$margen = ($valor/$utilidad)*100;

							$arr_aux1 [] = array("nombre"=>$nombre,"venta_neta"=>$valor,"utilidad"=>$utilidad,"margen"=>$margen);
						}*/
		}
	}

	public function cargar_tabla_comisiones_asesores()
	{
		$mes = $this->input->GET('mes');
		$ano = $this->input->GET('ano');
		/*if ($mes == 1) {
			$mes = 12;
			$ano = $ano - 1;
		}elseif ($mes != 1) {
			$mes = $mes - 1;
		}*/


		$this->load->model('nominas');
		/*CALCULO DE COMISIONES*/
		//$com_rep_mos = $this->nominas->get_comision_rep_mostrador();
		//$com_rep_mos_m = $this->nominas->get_comision_rep_mostrados_mayor();
		//$com_rep_tal = $this->nominas->get_comision_rep_taller();
		$data_tabla = array();
		$arr_aux1 = array();
		$arr_aux2 = array();
		/*ARRAY ASESORES*/
		$asesores[] = array('nombre' => "QUIÑONEZ NAVAS DIEGO ALONSO", 'sede' => "MOSTRADOR");
		$asesores[] = array('nombre' => "QUIÑONEZ NAVAS DIEGO ALONSO", 'sede' => "TALLER");
		$asesores[] = array('nombre' => "CASTRO BLANCO LUIS EDUARDO", 'sede' => "SOLOCHEVROLET");
		$asesores[] = array('nombre' => "OLAYA CALDERON JOSE ALLENDY", 'sede' => "MOSTRADOR-MAYOR");
		$asesores[] = array('nombre' => "OLAYA CALDERON JOSE ALLENDY", 'sede' => "TALLER");
		$asesores[] = array('nombre' => "CARRILLO ANGARITA FIDEL", 'sede' => "CUCUTA ASEGURADORA");
		$asesores[] = array('nombre' => "RANGEL REYES CRISTIAN ORLANDO", 'sede' => "CUCUTA MOSTRADOR");
		$asesores[] = array('nombre' => "LOPEZ  JUAN MANUEL", 'sede' => "CUCUTA TALLER");
		$asesores[] = array('nombre' => "CADENA RAMIREZ FERNANDO ANTONIO", 'sede' => "GIRON ASEGURADORA");
		$asesores[] = array('nombre' => "ABRIL RAMIREZ LEONARDO", 'sede' => "GIRON TALLER");
		$asesores[] = array('nombre' => "ARDILA SANCHEZ JOSUE", 'sede' => "GIRON MOSTRADOR");
		$asesores[] = array('nombre' => "ARDILA SANCHEZ JOSUE", 'sede' => "GIRON ASEGURADORA-TALLER");
		$asesores[] = array('nombre' => "MEJIA VARGAS OSCAR ALFONSO", 'sede' => "GIRON ASEGURADORA");
		$asesores[] = array('nombre' => "OCHOA RUEDA JHON FREDDY", 'sede' => "CHEVROPARTES MAYOR");
		$asesores[] = array('nombre' => "OCHOA RUEDA JHON FREDDY", 'sede' => "CHEVROPARTES MOSTRADOR");
		$asesores[] = array('nombre' => "OCHOA RUEDA JHON FREDDY", 'sede' => "CHEVROPARTES ACEITE GRANEL");

		for ($i = 0; $i < count($asesores); $i++) {
			$nom = $asesores[$i]["nombre"];
			$sede = $asesores[$i]["sede"];
			$venta_neta = 0;
			$margen_bruto = 0;
			$utilidad_bruta = 0;
			$comision = 0;
			$valor_comision = 0;
			$total_comision = 0;
			switch ($nom) {
				case 'QUIÑONEZ NAVAS DIEGO ALONSO':
					$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
					$data_tall = $this->nominas->get_comision_rep_taller('QDIEGO', $mes, $ano);
					$utilidad = 0;
					if ($sede == "MOSTRADOR") {
						foreach ($data_mos->result() as $key) {
							$venta_neta = $key->venta_neta;
							$margen_bruto = $key->margen;
							$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
							$comision = 12.0;
							$valor_comision = $utilidad_bruta * ($comision / 100);
							$total_comision = $valor_comision;
						}
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
						$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => $margen_bruto, "utilidad_bruta" => $utilidad_bruta, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => 0, "valor_comision_v" => 0, "total_comision" => $total_comision, "sede" => $sede, "color" => "#D8FFD4");
					}
					break;

				case 'CASTRO BLANCO LUIS EDUARDO':
					$data_mos = $this->nominas->get_comision_rep_mostrador_luis_e($nom, $mes, $ano);
					$utilidad = 0;
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
					$utilidad = 0;
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
					$utilidad = 0;
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
					$utilidad = 0;
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
				case 'LOPEZ  JUAN MANUEL':
					$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
					$data_tall = $this->nominas->get_comision_rep_taller('JMANUEL', $mes, $ano);
					$utilidad = 0;
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
					$utilidad = 0;
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
					$utilidad = 0;
					foreach ($data_mos->result() as $key) {
						$venta_neta = $key->venta_neta;
						$utilidad = $key->utilidad;
					}
					foreach ($data_tall->result() as $key) {
						$venta_neta += $key->venta_neta;
						//$margen_bruto = $key->margen;
						$utilidad += $key->utilidad;
					}
				
					foreach ($data_tall_M->result() as $key) {
						$venta_neta += $key->venta_neta;
						$utilidad += $key->utilidad;
					}
					
					$margen_bruto = ($utilidad / $venta_neta) * 100;
					//$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
					$comision = 2.0;
					$valor_comision = $utilidad * ($comision / 100);
					$total_comision = $valor_comision;
					$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => number_format($margen_bruto, 2, ",", ","), "utilidad_bruta" => $utilidad, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => 0, "valor_comision_v" => 0, "total_comision" => $total_comision, "sede" => $sede, "color" => "#CEFEF8");
					break;
				case 'ARDILA SANCHEZ JOSUE':
					$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
					$data_tall = $this->nominas->get_comision_rep_taller('JARDILA', $mes, $ano);
					$utilidad = 0;
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
					$utilidad = 0;
					$valor_comision_v = 0;
					if ($sede == "CHEVROPARTES MAYOR") {
						foreach ($data_mos_m->result() as $key) {
							$venta_neta = $key->venta_neta;
							$margen_bruto = $key->margen;
							$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
							$comision = 0;
							$comision_v = 0;
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
					//print_r($data_tabla);
					//die;
					break;
				case 'MEJIA VARGAS OSCAR ALFONSO':
					$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
					$data_tall = $this->nominas->get_comision_rep_taller('OMEJIA', $mes, $ano);
					$utilidad = 0;
					$valor_comision_v = 0;
					foreach ($data_mos->result() as $key) {
						$venta_neta = $key->venta_neta;
						//$margen_bruto = $key->margen;	
						$utilidad = $key->utilidad;
					}
					foreach ($data_tall->result() as $key) {
						$venta_neta += $key->venta_neta;
						//$margen_bruto = $key->margen;
						$utilidad += $key->utilidad;
					}
					$margen_bruto = ($utilidad / $venta_neta) * 100;
					$utilidad_bruta = $venta_neta * ($margen_bruto / 100);
					$comision = 12.0;
					$comision_v = 0.01;
					$valor_comision_v = $venta_neta * $comision_v;
					$valor_comision = $utilidad_bruta * ($comision / 100);
					$total_comision = $valor_comision + $valor_comision_v;
					$data_tabla[] = array("nombre" => $nom, "venta_neta" => $venta_neta, "margen_bruto" => round($margen_bruto), "utilidad_bruta" => $utilidad_bruta, "comision" => $comision, "valor_comision" => $valor_comision, "comision_v" => $comision_v, "valor_comision_v" => $valor_comision_v, "total_comision" => $total_comision, "sede" => $sede, "color" => "#CEFEF8");
					break;
			}
		}
		foreach ($data_tabla as $key) {
			echo '
					<tr style="background-color: ' . $key["color"] . ';font-size: 14px;">
	                      <td><strong>' . $key["sede"] . '</strong></td>
	                      <td style="width: 10px;"><strong>' . $key['nombre'] . '</strong></td>
	                      <td>$' . number_format($key['venta_neta'], 0, ",", ",") . '</td>
	                      <td><span class="badge bg-warning">' . $key['margen_bruto'] . '%</span></td>
	                      <td>$' . number_format($key['utilidad_bruta'], 0, ",", ",") . '</td>
	                      <td><span class="badge bg-success">' . $key["comision"] . '%</span></td>
	                      <td>$' . number_format($key["valor_comision"], 0, ",", ",") . '</td>
	                      <td><span class="badge bg-info">' . ($key["comision_v"] * 100) . '%</span> | $' . number_format($key["valor_comision_v"], 0, ",", ",") . '</td>
	                      <td>$' . number_format($key["total_comision"], 0, ",", ",") . '</td>
	                      <td>
	                      	<form action="' . base_url() . 'nomina/detalle_comisiones_asesores" method="POST">
	                      		<input type="text" name="nom" style="display: none;" value="' . $key["nombre"] . '"/>
	                      		<input type="text" name="mes" style="display: none;" value="' . $mes . '"/>
	                      		<input type="text" name="ano" style="display: none;" value="' . $ano . '"/>
	                      		<input type="text" name="sede" style="display: none;" value="' . $key["sede"] . '"/>
	                      		<button class="btn btn-info btn-sm"><i class="fas fa-eye"></i></i></button>
	                      	</form>
	                      </td>
	                    </tr>
				';
			//echo $mes." ".$ano."\n";
		}
	}

	public function detalle_comisiones_asesores()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			//llamamos los modelos necesarios
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('nominas');
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();

			/*CALCULO DE DETALLE COMISIONES*/
			$nom = $this->input->POST('nom');
			$mes = $this->input->POST('mes');
			$ano = $this->input->POST('ano');
			$sede = $this->input->POST('sede');
			$venta_neta = 0;
			$margen_bruto = 0;
			$utilidad_bruta = 0;
			$comision = 0;
			$valor_comision = 0;
			$total_comision = 0;
			switch ($nom) {
				case 'QUIÑONEZ NAVAS DIEGO ALONSO':
					$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
					$data_tall = $this->nominas->get_comision_rep_taller('QDIEGO', $mes, $ano);
					if ($sede == "MOSTRADOR") {
						foreach ($data_mos->result() as $key) {
							$subtotal = $key->subtotal;
							$descuento = $key->descuento;
							$venta_neta = $key->venta_neta;
							$costo_neto = $key->costo_neto;
							$margen_bruto = $key->margen;
							$utilidad = $key->utilidad;
							$tipo = $sede;
						}
						$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					} elseif ($sede == "TALLER") {
						foreach ($data_tall->result() as $key) {
							$subtotal = $key->subtotal;
							$descuento = $key->descuento;
							$venta_neta = $key->venta_neta;
							$costo_neto = $key->costo_neto;
							$margen_bruto = $key->margen;
							$utilidad = $key->utilidad;
							$tipo = $sede;
						}
						$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					}
					break;

				case 'CASTRO BLANCO LUIS EDUARDO':
					$data_mos = $this->nominas->get_comision_rep_mostrador_luis_e($nom, $mes, $ano);
					foreach ($data_mos->result() as $key) {
						$subtotal = $key->subtotal;
						$descuento = $key->descuento;
						$venta_neta = $key->venta_neta;
						$costo_neto = $key->costo_neto;
						$margen_bruto = $key->margen;
						$utilidad = $key->utilidad;
						$tipo = $sede;
					}
					$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					break;
				case 'OLAYA CALDERON JOSE ALLENDY':
					$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
					$data_tall = $this->nominas->get_comision_rep_taller('JOLAYA', $mes, $ano);
					if ($sede == "MOSTRADOR-MAYOR") {
						foreach ($data_mos->result() as $key) {
							$subtotal = $key->subtotal;
							$descuento = $key->descuento;
							$venta_neta = $key->venta_neta;
							$costo_neto = $key->costo_neto;
							$margen_bruto = $key->margen;
							$utilidad = $key->utilidad;
							$tipo = $sede;
						}
						$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					} elseif ($sede == "TALLER") {
						foreach ($data_tall->result() as $key) {
							$subtotal = $key->subtotal;
							$descuento = $key->descuento;
							$venta_neta = $key->venta_neta;
							$costo_neto = $key->costo_neto;
							$margen_bruto = $key->margen;
							$utilidad = $key->utilidad;
							$tipo = $sede;
						}
						$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					}
					break;
				case 'CARRILLO ANGARITA FIDEL':
					$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
					$data_tall = $this->nominas->get_comision_rep_taller('FIDEL', $mes, $ano);
					foreach ($data_mos->result() as $key) {
						$subtotal = $key->subtotal;
						$descuento = $key->descuento;
						$venta_neta = $key->venta_neta;
						$costo_neto = $key->costo_neto;
						$margen_bruto = $key->margen;
						$utilidad = $key->utilidad;
						$tipo = "MOSTRADOR";
					}
					$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					foreach ($data_tall->result() as $key) {
						$subtotal = $key->subtotal;
						$descuento = $key->descuento;
						$venta_neta = $key->venta_neta;
						$costo_neto = $key->costo_neto;
						$margen_bruto = $key->margen;
						$utilidad = $key->utilidad;
						$tipo = "TALLER";
					}
					$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);

					break;
				case 'RANGEL REYES CRISTIAN ORLANDO':
					$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
					$data_tall = $this->nominas->get_comision_rep_taller('CRANGEL', $mes, $ano);
					foreach ($data_mos->result() as $key) {
						$subtotal = $key->subtotal;
						$descuento = $key->descuento;
						$venta_neta = $key->venta_neta;
						$costo_neto = $key->costo_neto;
						$margen_bruto = $key->margen;
						$utilidad = $key->utilidad;
						$tipo = "MOSTRADOR";
					}
					$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					foreach ($data_tall->result() as $key) {
						$subtotal = $key->subtotal;
						$descuento = $key->descuento;
						$venta_neta = $key->venta_neta;
						$costo_neto = $key->costo_neto;
						$margen_bruto = $key->margen;
						$utilidad = $key->utilidad;
						$tipo = "TALLER";
					}
					$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					break;
				case 'LOPEZ  JUAN MANUEL':
					$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
					$data_tall = $this->nominas->get_comision_rep_taller('JMANUEL', $mes, $ano);
					foreach ($data_mos->result() as $key) {
						$subtotal = $key->subtotal;
						$descuento = $key->descuento;
						$venta_neta = $key->venta_neta;
						$costo_neto = $key->costo_neto;
						$margen_bruto = $key->margen;
						$utilidad = $key->utilidad;
						$tipo = "MOSTRADOR";
					}
					$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					foreach ($data_tall->result() as $key) {
						$subtotal = $key->subtotal;
						$descuento = $key->descuento;
						$venta_neta = $key->venta_neta;
						$costo_neto = $key->costo_neto;
						$margen_bruto = $key->margen;
						$utilidad = $key->utilidad;
						$tipo = "TALLER";
					}
					$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					break;
				case 'CADENA RAMIREZ FERNANDO ANTONIO':
					$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
					$data_tall = $this->nominas->get_comision_rep_taller('FERNANDO', $mes, $ano);
					foreach ($data_mos->result() as $key) {
						$subtotal = $key->subtotal;
						$descuento = $key->descuento;
						$venta_neta = $key->venta_neta;
						$costo_neto = $key->costo_neto;
						$margen_bruto = $key->margen;
						$utilidad = $key->utilidad;
						$tipo = "MOSTRADOR";
					}
					$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					foreach ($data_tall->result() as $key) {
						$subtotal = $key->subtotal;
						$descuento = $key->descuento;
						$venta_neta = $key->venta_neta;
						$costo_neto = $key->costo_neto;
						$margen_bruto = $key->margen;
						$utilidad = $key->utilidad;
						$tipo = "TALLER";
					}
					$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					break;
				case 'ABRIL RAMIREZ LEONARDO':
					$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
					$data_tall_M = $this->nominas->get_comision_rep_taller('M-ABRIL', $mes, $ano);
					$data_tall = $this->nominas->get_comision_rep_taller('LEONARDO', $mes, $ano);
					foreach ($data_mos->result() as $key) {
						$subtotal = $key->subtotal;
						$descuento = $key->descuento;
						$venta_neta = $key->venta_neta;
						$costo_neto = $key->costo_neto;
						$margen_bruto = $key->margen;
						$utilidad = $key->utilidad;
						$tipo = "MOSTRADOR";
					}
					$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					foreach ($data_tall->result() as $key) {
						$subtotal = $key->subtotal;
						$descuento = $key->descuento;
						$venta_neta = $key->venta_neta;
						$costo_neto = $key->costo_neto;
						$margen_bruto = $key->margen;
						$utilidad = $key->utilidad;
						$tipo = "TALLER";
					}
					$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					foreach ($data_tall_M->result() as $key) {
						$subtotal = $key->subtotal;
						$descuento = $key->descuento;
						$venta_neta = $key->venta_neta;
						$costo_neto = $key->costo_neto;
						$margen_bruto = $key->margen;
						$utilidad = $key->utilidad;
						$tipo = "MOSTRADOR-MAYOR";
					}
					$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					break;
				case 'ARDILA SANCHEZ JOSUE':
					$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
					$data_tall = $this->nominas->get_comision_rep_taller('JARDILA', $mes, $ano);
					if ($sede == "GIRON MOSTRADOR") {
						foreach ($data_mos->result() as $key) {
							$subtotal = $key->subtotal;
							$descuento = $key->descuento;
							$venta_neta = $key->venta_neta;
							$costo_neto = $key->costo_neto;
							$margen_bruto = $key->margen;
							$utilidad = $key->utilidad;
							$tipo = "MOSTRADOR";
						}
						$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					} elseif ($sede == "GIRON ASEGURADORA-TALLER") {
						foreach ($data_tall->result() as $key) {
							$subtotal = $key->subtotal;
							$descuento = $key->descuento;
							$venta_neta = $key->venta_neta;
							$costo_neto = $key->costo_neto;
							$margen_bruto = $key->margen;
							$utilidad = $key->utilidad;
							$tipo = "ASEGURADORA-TALLER";
						}
						$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					}
					break;
				case 'OCHOA RUEDA JHON FREDDY':
					$data_mos = $this->nominas->get_comision_rep_mostrador_sin_mayor($nom, $mes, $ano);
					$data_mos_m = $this->nominas->get_comision_rep_mostrados_mayor($nom, $mes, $ano);
					if ($sede == "CHEVROPARTES MAYOR") {
						foreach ($data_mos_m->result() as $key) {
							$subtotal = $key->subtotal;
							$descuento = $key->descuento;
							$venta_neta = $key->venta_neta;
							$costo_neto = $key->costo_neto;
							$margen_bruto = $key->margen;
							$utilidad = $key->utilidad;
							$tipo = "CHEVROPARTES-MAYOR";
						}
						$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					} elseif ($sede == "CHEVROPARTES MOSTRADOR") {
						foreach ($data_mos->result() as $key) {
							$subtotal = $key->subtotal;
							$descuento = $key->descuento;
							$venta_neta = $key->venta_neta;
							$costo_neto = $key->costo_neto;
							$margen_bruto = $key->margen;
							$utilidad = $key->utilidad;
							$tipo = "CHEVROPARTES-MOSTRADOR";
						}
						$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					}
					break;
				case 'MEJIA VARGAS OSCAR ALFONSO':
					$data_mos = $this->nominas->get_comision_rep_mostrador($nom, $mes, $ano);
					foreach ($data_mos->result() as $key) {
						$subtotal = $key->subtotal;
						$descuento = $key->descuento;
						$venta_neta = $key->venta_neta;
						$costo_neto = $key->costo_neto;
						$margen_bruto = $key->margen;
						$utilidad = $key->utilidad;
						$tipo = "CHEVROPARTES-MOSTRADOR";
					}
					$data_tabla[] = array("nombre" => $nom, "subtotal" => $subtotal, "descuento" => $descuento, "venta_neta" => $venta_neta, "costo_neto" => $costo_neto, "margen_bruto" => $margen_bruto, "utilidad" => $utilidad, "tipo" => $tipo);
					break;
			}


			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, "data_tabla" => $data_tabla, "nombre" => $nom);
			//abrimos la vista
			$this->load->view('detalle_nomina_rep_giron', $arr_user);
		}
	}


	/*******************************    N O M I N A   L   Y   P    ***********************************************/

	public function nomina_lyp()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu);
			//abrimos la vista
			$this->load->view("Informes_nomina/inf_nomina_lyp", $arr_user);
		}
	}

	public function load_nomina()
	{
		//cargar modelos
		$this->load->model('nominas');
		//traemos las variables
		$desde = $this->input->GET('desde');
		$hasta = $this->input->GET('hasta');
		$data_nomina = $this->nominas->get_nomina_lyp($desde, $hasta);

		foreach ($data_nomina->result() as $key) {
			echo '
				<tr aling="center" style="font-size: 12px;">
	                <td>' . $key->operario . '</td>
	                <td>' . $key->nombres . '</td>
	                <td>' . $key->descripcion . '</td>
	                <td><span class="badge bg-primary">' . $key->productividad . '%</span></td>
	                <td>' . $key->horas_trabajadas . '</td>
	                <td>' . $key->horas_productivas_mes . '</td>
	                <td><span class="badge bg-warning">' . $key->porcentaje_liquidacion . '%</span></td>
	                <td>$ ' . number_format($key->materiales, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->base_comision, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->internas, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->comsion_sin_internas, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->Comision_a_pagar, 0, ",", ",") . '</td>
	                <td><a href="#" class="btn btn-info btn-sm" onclick="abrir_modal_detalle('.$key->operario.','."'".$desde."'".','."'".$hasta."'".')">Ver</td>
	            </tr>
			';
		}
	}

	public function load_nomina_nuevo()
	{
		//<td>$ ' . number_format($key->Comision_a_pagar, 0, ",", ",") . '</td>

		//cargar modelos
		$this->load->model('nominas');
		$this->load->model('perfiles');
		//traemos las variables
		$desde = $this->input->GET('desde');
		$hasta = $this->input->GET('hasta');
		$usu = $this->session->userdata('user');
		$perfil = $this->perfiles->getPerfilByUser($usu)->id_perfil;
		if ($perfil == 1 || $perfil == 20) {
			$data_nomina = $this->nominas->get_nomina_lyp_nueva($desde, $hasta);
			foreach ($data_nomina->result() as $key) {
				echo '
					<tr aling="center" style="font-size: 12px;">
		                <td>' . $key->operario . '</td>
		                <td>' . $key->nombres . '</td>
		                <td>' . $key->descripcion . '</td>
		                <td><span class="badge bg-primary">' . $key->productividad . '%</span></td>
		                <td>' . $key->horas_trabajadas . '</td>
		                <td>' . $key->horas_productivas_mes . '</td>
		                <td><span class="badge bg-warning">' . $key->porcentaje_liquidacion . '%</span></td>
		                <td>$ ' . number_format($key->materiales, 0, ",", ",") . '</td>
		                <td>$ ' . number_format($key->base_comision_mo, 0, ",", ",") . '</td>
		                <td>$ ' . number_format($key->internas, 0, ",", ",") . '</td>
		                <td>$ ' . number_format($key->comsion_sin_internas_mo, 0, ",", ",") . '</td>
		                <td>$ ' . number_format($key->Base_Rptos, 0, ",", ",") . '</td>
		                <td> ' . number_format($key->porc_fac_total, 2, ",", ",") . '%</td>
		                <td>$ ' . number_format(($key->Base_Rptos * ($key->porc_fac_total / 100)), 0, ",", ",") . '</td>
		                <td>$ ' . number_format($key->Bono_NPS, 0, ",", ",") . '</td>
		                <td>$ ' . number_format(($key->comsion_sin_internas_mo + ($key->Base_Rptos * ($key->porc_fac_total / 100)) + $key->Bono_NPS + $key->internas), 0, ",", ",") . '</td>
		                
		                <td><a href="#" class="btn btn-info btn-sm" onclick="abrir_modal_detalle('.$key->operario.','."'".$desde."'".','."'".$hasta."'".')">Ver</td>
		            </tr>
				';
			}
		}else if($perfil == 24){
			$data_nomina = $this->nominas->get_nomina_lyp_nueva($desde, $hasta,$usu);
			foreach ($data_nomina->result() as $key) {
				echo '
					<tr aling="center" style="font-size: 12px;">
		                <td>' . $key->operario . '</td>
		                <td>' . $key->nombres . '</td>
		                <td>' . $key->descripcion . '</td>
		                <td><span class="badge bg-primary">' . $key->productividad . '%</span></td>
		                <td>' . $key->horas_trabajadas . '</td>
		                <td>' . $key->horas_productivas_mes . '</td>
		                <td><span class="badge bg-warning">' . $key->porcentaje_liquidacion . '%</span></td>
		                <td>$ ' . number_format($key->materiales, 0, ",", ",") . '</td>
		                <td>$ ' . number_format($key->base_comision_mo, 0, ",", ",") . '</td>
		                <td>$ ' . number_format($key->internas, 0, ",", ",") . '</td>
		                <td>$ ' . number_format($key->comsion_sin_internas_mo, 0, ",", ",") . '</td>
		                <td>$ ' . number_format($key->Base_Rptos, 0, ",", ",") . '</td>
		                <td> ' . number_format($key->porc_fac_total, 2, ",", ",") . '%</td>
		                <td>$ ' . number_format(($key->Base_Rptos * ($key->porc_fac_total / 100)), 0, ",", ",") . '</td>
		                <td>$ ' . number_format($key->Bono_NPS, 0, ",", ",") . '</td>
		                <td>$ ' . number_format(($key->comsion_sin_internas_mo + ($key->Base_Rptos * ($key->porc_fac_total / 100)) + $key->Bono_NPS + $key->internas), 0, ",", ",") . '</td>
		                
		                <td><a href="#" class="btn btn-info btn-sm" onclick="abrir_modal_detalle('.$key->operario.','."'".$desde."'".','."'".$hasta."'".')">Ver</td>
		            </tr>
				';
			}
		}else{
			echo "No tiene permisos para ver este Informe";
		}
		
	}

	public function load_nomina_nit()
	{
		//cargar modelos
		$this->load->model('nominas');
		//traemos las variables
		$desde = $this->input->GET('desde');
		$hasta = $this->input->GET('hasta');
		$usu = $this->session->userdata('user');
		$data_nomina = $this->nominas->get_nomina_lyp_by_nit($desde, $hasta, $usu);

		foreach ($data_nomina->result() as $key) {
			echo '
				<tr aling="center" style="font-size: 12px;">
	                <td>' . $key->operario . '</td>
	                <td>' . $key->nombres . '</td>
	                <td>' . $key->descripcion . '</td>
	                <td><span class="badge bg-primary">' . $key->productividad . '%</span></td>
	                <td>' . $key->horas_trabajadas . '</td>
	                <td>' . $key->horas_productivas_mes . '</td>
	                <td><span class="badge bg-warning">' . $key->porcentaje_liquidacion . '%</span></td>
	                <td>$ ' . number_format($key->materiales, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->base_comision, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->internas, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->comsion_sin_internas, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->Comision_a_pagar, 0, ",", ",") . '</td>
	                <td><a href="#" class="btn btn-info btn-sm" onclick="abrir_modal_detalle('.$key->operario.','."'".$desde."'".','."'".$hasta."'".')">Ver</td>
	            </tr>
			';
		}
	}

	/*Detalle nomina tecnicos no lyp*/
	public function load_detalle_nomina_tec()
	{
		//cargar modelos
		$this->load->model('nominas');
		//traemos las variables
		$mes = $this->input->GET('mes');
		$anio = $this->input->GET('anio');
		$nit = $this->input->GET('nit');
		$data_nomina = $this->nominas->get_detalle_nomina_tec($mes, $anio, $nit);
		foreach ($data_nomina->result() as $key) {
			
			echo '<tr aling="center" style="font-size: 16px;">
	                <td>' . $key->tipo .' '.$key->numero. '</td>
	                <td>' . $key->numero_orden . '</td>
	                <td>' . $key->placa . '</td>
	                <td>' . $key->vh . '</td>
	                <td>' . $key->operacion . '</td>
					<td>' . $key->nombre_operacion . '</td>
	                <td>$ ' . number_format($key->venta_repuestos, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->venta_mano_obra, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->segunda_entrega, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->Instalacion_accesorios, 0, ",", ",") . '</td>
					<td>$ ' . number_format($key->internas, 0, ",", ",") . '</td>
	            </tr>';
		}
	}

	/*Detalle nomina tecnicos no lyp*/
	public function load_detalle_nomina_jefes()
	{
		//cargar modelos
		$this->load->model('nominas');
		//traemos las variables
		$mes = $this->input->GET('mes');
		$anio = $this->input->GET('anio');
		$nit = $this->input->GET('nit');
		$data_nomina = $this->nominas->get_detalle_nomina_jefes($mes, $anio, $nit);
		foreach ($data_nomina->result() as $key) {
			echo '<tr aling="center" style="font-size: 16px;">
	                <td>' . $key->nit. '</td>
	                <td>' . $key->nombres . '</td>
	                <td>' . $key->sede . '</td>
	                <td>$ ' . number_format($key->repuestos, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->mano_de_obra, 0, ",", ",") . '</td>
	            </tr>';
		}
	}

	/*Detalle nomina*/
	public function load_detalle_nomina()
	{
		//cargar modelos aca
		$this->load->model('nominas');
		//traemos las variables
		$desde = $this->input->GET('desde');
		$hasta = $this->input->GET('hasta');
		$desde = str_replace("-","",$desde);
		$hasta = str_replace("-","",$hasta);
		$nit = $this->input->GET('nit');
		//$data_nomina = $this->nominas->get_detalle_nomina_tec($mes, $anio, $nit);
		$data_nomina = $this->nominas->get_detalle_nomina_lyp_nit($desde, $hasta, $nit);
		foreach ($data_nomina->result() as $key) {
			if($nit == '1093140589'){
				if($key->base_comision == 0){
					//$productividad = 0;
					$procen_liquidacion = $key->porc_liquida;
				}else{
					$procen_liquidacion = round(($key->Comision_a_pagar / $key->base_comision) * 100);
				}
			}else{
				$procen_liquidacion = $key->porc_liquida;
			}
			echo '<tr aling="center" style="font-size: 16px;">
	                <td>' . $key->tipo .' '.$key->numero. '</td>
	                <td>' . $key->numero_orden . '</td>
	                <td>' . $key->placa . '</td>
	                <td>' . $key->vehiculo . '</td>
	                <td><span class="badge bg-primary">' . $key->productividad . '%</span></td>
	                <td><span class="badge bg-info">' . $procen_liquidacion . '%</span></td>
	                <td>' . $key->tiempo_facturado . '</td>
	                <td>$ ' . number_format($key->base_comision, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->materiales, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->internas, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->Comision_a_pagar, 0, ",", ",") . '</td>
	            </tr>';
		}
	}

	public function nomina_lyp_nit()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu);
			//abrimos la vista
			$this->load->view("Informes_nomina/inf_nomina_lyp_nit", $arr_user);
		}
	}
	/*
	*controlador creado para la manipulacion del modelo nomina metodo traer_datos_nominalyt
	*Andres Gomez 16-09-21
	 */


	public function comision_accesor_lyp()
	{

		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');

			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$id_usu = "";

			//$bodegas = $this->pruebaa->get_bodegas();
			$datos = $this->menus->getAllMenu();
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			$arr_user = array(
				'userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles,
				'pass' => $pass, 'id_usu' => $id_usu, 'data_tabla' => $datos
			);
			//abrimos la vista
			$this->load->view("Informes_nomina/comision_accesor_lyp", $arr_user);
		}
	}

	/**
	 * controlador creado para visualizar y mostrar los datos
	 * Andres Gomez  16-09-21
	 */

	public function consulta_typ()
	{
		//cragar modelo
		$this->load->model('nominas');
		$mes = $this->input->GET('mes');
		$ano = $this->input->GET('ano');
		//consumir metodo del modelo
		$datos = $this->nominas->traer_datos_nominalyt($mes, $ano);
		//extracion de campos a utilizar
		$hts = 0;
		$cts = 0;
		$hte = 0;
		$cte = 0;

		foreach ($datos->result() as $key) {
			$nit = $key->nit_ase;
			$nombre = $key->asesor;
			$hora = $key->horas;
			$comision = $key->comision;
			$tipo = $key->tipo;
			//validar si es tipo S o E
			if ($tipo == 'S') {
				$hts = $hora + $hts;
				$cts = $cts + $comision;
			} elseif ($tipo == 'E') {
				$hte = $hora + $hte;
				$cte = $cte + $comision;
			}
		}
		//definir valores todales
		$to_h = $hte + $hts;
		$to_c = $cte + $cts;
		//pasar a un arreglo todos los datos exptraidos
		$data_tabla[] = array('nit' => $nit, 'nombre' => $nombre, 'hts' => $hts, 'cts' => $cts, 'hte' => $hte, 'cte' => $cte, 'to_h' => $to_h, 'to_c' => $to_c);
		//validar si la variable trae datos y se crea la tabla
		//foreach ($data_tabla as $key=>$dto){
		//<th class='text-center'>$dto</th>	
		if ($datos) {
			echo "
			<table class='table table-bordered bg-white table-sm ' id='tnl' style='font-size: 12px;'>
				<thead>
					<tr>
					<div class='col-lg-12'><label class='alert col-lg-12  text-center'>SEÑOR $nombre</label><button onclick='ver_excel()' id='imprime'  class='py-2 btn btn-success'><spam> Descargar en excel <spam> </button></div>
					<hr>
						<th scope='col'>Nit</th>
						<th scope='col'>Nombre</th>
						<th scope='col'>Horas Tipo S</th>
						<th scope='col'>Comision Tipos S</th>
						<th scope='col'>Horas Tipo E</th>
						<th scope='col'>Comision Tipos E</th>
						<th scope='col'>Total Horas</th>
						<th scope='col'>Total Comision</th>
						<th scope='col'>Detalle</th>
					</tr>
				</thead>
				<tbody >
				<tr>
				";
			foreach ($data_tabla as $dta) {
				echo "
					<td>$dta[nit]</td>
					<td>$dta[nombre]</td>
					<td style='background:#39D195;' class='text-center'>$dta[hts]</td>
					<td style='background:#39D195;' class='text-center'>$" . number_format($dta['cts'], 0, ",", ",") . "</td>
					<td style='background:#F05364;' class='text-center'>$dta[hte]</td>
					<td style='background:#F05364;' class='text-center'>$" . number_format($dta['cte'], 0, ",", ",") . "</td>
					<td style='background:#14ABED;' class='text-center'>$dta[to_h]</td>
					<td style='background:#14ABED;' class='text-center'>$" . number_format($dta['to_c'], 0, ",", ",") . "</td>
					<td class='text-center'><button class='btn btn-info shadow text-white' onclick='filtrolyp() '><i class='fas fa-info-circle'></i></button></td>
				
					";
			}
			echo "
				
				</tr>
				</tbody>
			</table>
			";
		} else {
			echo "";
		}
	}


	public function Resultadofiltro_typ()
	{
		//cragar modelo
		$this->load->model('nominas');
		$mes = $this->input->POST('mes');
		$ano = $this->input->POST('ano');
		//consumir metodo del modelo
		$detalle = $this->nominas->fistrarNominalyp($mes, $ano);
		$actual = "";

		if ($mes == 1) {
			$actual = "Enero";
		} elseif ($mes == 2) {
			$actual = "Febrero";
		} elseif ($mes == 3) {
			$actual = "Marzo";
		} elseif ($mes == 4) {
			$actual = "Abril";
		} elseif ($mes == 5) {
			$actual = "Mayo";
		} elseif ($mes == 6) {
			$actual = "Junio";
		} elseif ($mes == 7) {
			$actual = "Julio";
		} elseif ($mes == 8) {
			$actual = "Agosto";
		} elseif ($mes == 9) {
			$actual = "Septiembre";
		} elseif ($mes == 10) {
			$actual = "Octubre";
		} elseif ($mes == 11) {
			$actual = "Nomviembre";
		} elseif ($mes == 12) {
			$actual = "Dicimebre";
		}

		echo "
	
		<table class='table table-bordered table-sm table-responsive-lg  bg-white' id='filtronominalypd' style='font-size: 13px;' >
			<thead>
				<tr>
				<div class='col-lg-12'><label class='alert col-lg-12  text-center'>Detalle de " . $actual . " del " . $ano . "</div>
						<th scope='col'>Cliente</th>
						<th scope='col'>N° de Orden</th>
						<th scope='col'>DTL</th>
						<th scope='col'>Numero</th>
						<th scope='col'>sw</th>
						<th scope='col'>Fecha</th>
						<th scope='col'>Horas</th>
						<th scope='col'>Operacion</th>
						<th scope='col'>Bodega</th>
						<th scope='col'>Deducible</th>
						<th scope='col'>Concepto</th>
						<th scope='col'>Tipo</th>
						<th scope='col'>Concepto</th>
						<th scope='col'>Descripcion</th>	
						<th scope='col'>Total</th>	
				</tr>
			</thead>
			<tbody >
			";

		foreach ($detalle->result() as $key) {

			echo "
			<tr>
			<td class='text-left'>$key->nombres</td>
			<td class='text-center'>$key->numero_orden</td>
			<td class='text-center'>$key->dtl</td>
			<td class='text-center'>$key->numero</td>
			<td class='text-center'>$key->sw</td>
			<td class='text-center'>$key->fec</td>
			<td class='text-center'>$key->horas</td>
			<td class='text-center'>$key->clase_operacion</td>
			<td class='text-center'>$key->bodega</td>
			<td class='text-center'>$key->deducible</td>
			<td class='text-center'>$key->concepto</td>
			<td class='text-center'>$key->tipo</td>
			<td class='text-center'>$key->concepto_12</td>
			<td class='text-center'>$key->descripcion</td>
			<td class='text-center'>$key->comision</td>
			
			</tr>
			";
		}
		echo "
			</tbody>
			</table>
			";
	}

	public function comisiones_tecnicos()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//llamamos los modelos necesarios
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');			
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//$allempleados = $this->nominas->listar_empleados();

			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//echo $id_usu;

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu);
			//abrimos la vista
			$this->load->view('Informes_nomina/inf_nomina_tec',$arr_user);
		}
	}

	public function load_nomina_tecnicos()
	{
		//cargar modelos
		$this->load->model('nominas');
		$this->load->model('perfiles');	
		//traemos las variables
		$mes = $this->input->GET('mes');
		$fecha = explode("-", $mes);
		$usu = $this->session->userdata('user');
		if ($mes."-01" < "2022-04-01") {
			echo "err";
		}else{
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu)->id_perfil;
			if ($perfil_user == 24) {
				$data_nomina = $this->nominas->get_inf_nomina_tec($fecha[1], $fecha[0],$usu);
			}else{
				$data_nomina = $this->nominas->get_inf_nomina_tec($fecha[1], $fecha[0]);
			}
			

			foreach ($data_nomina->result() as $key) {
				echo '
					<tr aling="center" style="font-size: 12px;">
		                <td>' . $key->nit . '</td>
		                <td>' . $key->tecnico . '</td>
		                <td>' . $key->patio . '</td>
		                <td>' . $key->cargo . '</td>
		                <td>$ ' . number_format($key->venta_repuestos, 0, ",", ",") . '</td>
		                <td>$ ' . number_format($key->venta_mano_obra, 0, ",", ",") . '</td>
		                <td>$ ' . number_format($key->comision_repuestos, 0, ",", ",") . '</td>
		                <td>$ ' . number_format($key->comision_mano_obra, 0, ",", ",") . '</td>
		                <td>$ ' . number_format($key->segunda_entrega, 0, ",", ",") . '</td>
		                <td>$ ' . number_format($key->bono_NPS, 0, ",", ",") . '</td>
		                <td>$ ' . number_format($key->Instalacion_accesorios, 0, ",", ",") . '</td>
						<td>$ ' . number_format($key->internas, 0, ",", ",") . '</td>
		                <td>$ ' . number_format(($key->comision_repuestos + $key->comision_mano_obra + $key->bono_NPS+$key->segunda_entrega + $key->Instalacion_accesorios+$key->internas), 0, ",", ",") . '</td>
		                <td><a href="#" class="btn btn-info btn-sm" onclick="abrir_modal_detalle_tec('.$key->nit.','."'".$fecha[1]."'".','."'".$fecha[0]."'".')">Ver</td>
		            </tr>
				';
			}
		}
		
	}

	public function comisiones_jefes()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//llamamos los modelos necesarios
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');			
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			//traemos los jefes
			$jefes = $this->usuarios->get_jefes();

			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//echo $id_usu;

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfil' => $perfil_user->id_perfil, 'pass' => $pass, 'id_usu' => $id_usu,'jefes'=>$jefes);
			//abrimos la vista
			$this->load->view('Informes_nomina/inf_nomina_jefe',$arr_user);
		}
	}

	public function load_nomina_jefe()
	{
		//cargar modelos
		$this->load->model('nominas');
		$this->load->model('perfiles');
		//traemos las variables
		$mes = $this->input->GET('mes');
		$fecha = explode("-", $mes);
		$usu = $this->session->userdata('user');
		$perfil_user = $this->perfiles->getPerfilByUser($usu);
		if ($perfil_user->id_perfil != 1 && $perfil_user->id_perfil != 20) {
			$data_nomina = $this->nominas->get_comisiones_jefes($fecha[1], $fecha[0],$usu);
			
		}else{
			
			$data_nomina = $this->nominas->get_comisiones_jefes($fecha[1], $fecha[0]);
		}
		

		foreach ($data_nomina->result() as $key) {
			echo '
				<tr aling="center" style="font-size: 12px;">
	                <td>' . $key->nit . '</td>
	                <td>' . $key->nombres . '</td>
	                <td>' . $key->sede . '</td>
	                <td>$ ' . number_format($key->facturacion_posventa, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->internas, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->comision_por_facturacion, 0, ",", ",") . '</td>          
	                <td>$ ' . number_format($key->bono_utilidad, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->utilidad_repuestos, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->comision_utilidad_bruta, 0, ",", ",") . '</td>
	                <td>$ ' . number_format($key->Bono_NPS, 0, ",", ",") . '</td>
	                <td>$ ' . number_format(($key->comision_por_facturacion + $key->bono_utilidad  + $key->comision_utilidad_bruta + $key->Bono_NPS), 0, ",", ",") . '</td>
	                <td><a href="#" class="btn btn-info btn-sm" onclick="abrir_modal_detalle_jefe(' . $key->nit . ','.$fecha[1].','.$fecha[0].')">Ver</a></td>
	            </tr>
			';
		}
	}

	public function insert_val_jefes()
	{
		//cargar modelos
		$this->load->model('nominas');

		//Traemos los parametros del formulario
		$arr_fecha = $this->input->GET('fecha');
		$fecha = explode("-", $arr_fecha);
		$jefe = $this->input->GET('jefe');
		$bono_nps = $this->input->GET('bono_nps');
		$bono_uti = $this->input->GET('bono_uti');
		//Array Para el Insert de los valores
		$data_insert = array('nit'=>$jefe,'anio'=>$fecha[0],'mes'=>$fecha[1],'bono_nps'=>$bono_nps,'bono_utilidad'=>$bono_uti);
		$data_update = array('bono_nps'=>$bono_nps,'bono_utilidad'=>$bono_uti);
		
		$update = $this->nominas->consultar_registro($fecha[0],$fecha[1],$jefe);

		if ($this->nominas->update_val_jefes($data_insert,$data_update)) {
			echo "ok";
		}else{
			echo "err";
		}
		
		// if ($update == 1) {
		// 	if ($this->nominas->update_val_jefes($data_insert,$data_update)) {
		// 		echo "ok";
		// 	}else{
		// 		echo "err";
		// 	}
		// } else {
		// 	if ($this->nominas->insert_val_jefes($data_insert)) {
		// 		echo "ok";
		// 	}else{
		// 		echo "err";
		// 	}
		// }
	
		
	}

	public function get_to_rep()
	{
		//cargar modelos
		$this->load->model('nominas');
		//Traemos los parametros por la url
		$fecha_ini = $this->input->GET('desde');
		$fecha_fin = $this->input->GET('hasta');
		$sede = $this->input->GET('sede');
		//traemos el valor
		$valor_rep = $this->nominas->get_to_rep_sedes($fecha_ini,$fecha_fin,$sede)->Base_Rptos;
		echo "$".number_format($valor_rep, 0, ",", ",");
	}


	/*******************************    N O M I N A   D I R		F L O T A S    ***********************************************/

	public function nomina_dir_flotas()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//llamamos los modelos necesarios
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');			
			//si ya hay datos de session los carga de nuevo
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfil' => $perfil_user->id_perfil, 'pass' => $pass);
			//abrimos la vista
			$this->load->view('Informes_nomina/inf_nomina_dir_flota',$arr_user);
		}
	}
	
	public function load_nomina_dir_flota()
	{
		//cargar modelos
		$this->load->model('nominas');
		$this->load->model('perfiles');
		//traemos las variables
		$mes = $this->input->POST('mes');
		$fecha = explode("-", $mes);
		$data_nomina = $this->nominas->get_comisiones_dir_flota($fecha[0], $fecha[1]);
		
		$conceptos = array('BONO PLACAS NUEVAS', 'COMISION POSTVENTA X VENTA REP FLOTA COMPARTIDA', 'COMISION POSTVENTA X VENTA REP FLOTA DIRECTA', 'COMISION VENTAS FLOTAS CREADAS', 'COMISION VENTAS ACOMPAÑAMIENTO FLOTAS');

		$count = 0;
		$total = 0;
		foreach ($data_nomina->result() as $key) {
			echo '
				<tr aling="center" style="font-size: 12px;">
	                <td>' . $conceptos[$count] . '</td>
	                <td>$ ' . number_format($key->comision) . '</td>
	                <td><a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#det_nomina_dir_flota" onclick="ver_detalle_dir_flota(' . $count . ','.$fecha[0].','.$fecha[1].')">Ver Detalle</a></td>
	            </tr>
			';
			$count++;
			$total += $key->comision;
		}
		echo '
			<tr aling="center" style="font-size: 12px;">
				<td>TOTAL COMISION</td>
				<td>$ ' . number_format($total) . '</td>
				<td></td>
			</tr>
		';
	}
	
	public function detalle_nomina_dir_flota()
	{
		//cargar modelos
		$this->load->model('nominas');
		//traemos las variables
		$concepto = $this->input->POST('concepto');
		$ano      = $this->input->POST('ano');
		$mes      = $this->input->POST('mes');
		$data_nomina = $this->nominas->get_detalle_comision_dir_flota($concepto, $ano, $mes);

		$cabeceras = '';
		$colspan   = 0;
		$total     = 0;
		switch ($concepto) {
			case 0:
				$colspan = 11;
				$cabeceras = '<tr>
						<th>Año Entrada</th>
						<th>Mes Entrada</th>
						<th>Año</th>
						<th>Mes</th>
						<th>Orden</th>
						<th>Placa</th>
						<th>Fecha Aprobación</th>
						<th>Última Entrada</th></th>
						<th>Primer Entrada</th></th>
						<th>Días</th>
						<th>Venta</th>
						<th>Comisiona</th>
					</tr>';
				break;
			case 1:
			case 2:
				$colspan = 10;
				$cabeceras = '<tr>
						<th>Año</th>
						<th>Mes</th>
						<th>Placa</th>
						<th>Orden</th>
						<th>Operación</th>
						<th>Descripción</th>
						<th>Venta</th>
						<th>Costo</th>
						<th>Utilidad</th>
						<th>Comisión</th>
					</tr>';
				break;
			case 3:
			case 4:
				$colspan = 9;
				$cabeceras = '<tr>
						<th>Año</th>
						<th>Mes</th>
						<th>Tipo</th>
						<th>Número</th>
						<th>Nit</th>
						<th>Nombres</th>
						<th>Nombre Flota</th>
						<th>Valor Antes Impuestos</th>
						<th>Comisión</th>
					</tr>';
				break;
		}

		echo '
			<table class="table table-hover table-bordered" id="example5">
                <thead>'.$cabeceras.'</thead>
                <tbody>';
		if ($data_nomina != null) {
			foreach ($data_nomina->result() as $key) {
				if ($concepto == 0) {
					$comisiona = $key->comisiona == 0 ? 'No' : 'Sí';
					echo '
						<tr aling="center" style="font-size: 12px;">
							<td>' . $key->ano_entrada . '</td>
							<td>' . $key->Mes_entrada . '</td>
							<td>' . $key->ano . '</td>
							<td>' . $key->Mes . '</td>
							<td>' . $key->orden . '</td>
							<td>' . $key->placa . '</td>
							<td>' . $key->fecha_aprob_postventa . '</td>
							<td>' . $key->ultima_entrada . '</td>
							<td>' . $key->primer_entrada . '</td>
							<td>' . $key->dias . '</td>
							<td>$ ' . number_format($key->venta) . '</td>
							<td>' . $comisiona . '</td>
						</tr>
					';
					$total += $key->venta;
				}
				if ($concepto == 1 || $concepto == 2) {
					echo '
						<tr aling="center" style="font-size: 12px;">
							<td>' . $key->ano . '</td>
							<td>' . $key->Mes . '</td>
							<td>' . $key->placa . '</td>
							<td>' . $key->orden . '</td>
							<td>' . $key->operacion . '</td>
							<td>' . $key->descripcion . '</td>
							<td>$ ' . number_format($key->venta) . '</td>
							<td>$ ' . number_format($key->costo) . '</td>
							<td>$ ' . number_format($key->Utilidad) . '</td>
							<td>$ ' . number_format($key->comision_venta_compartida) . '</td>
						</tr>
					';
					$total += $key->comision_venta_compartida;
				}
				if ($concepto == 3 || $concepto == 4) {
					echo '
						<tr aling="center" style="font-size: 12px;">
							<td>' . $key->ano . '</td>
							<td>' . $key->mes . '</td>
							<td>' . $key->tipo . '</td>
							<td>' . $key->numero . '</td>
							<td>' . $key->nit . '</td>
							<td>' . $key->nombres . '</td>
							<td>' . $key->nombre_flota . '</td>
							<td>$ ' . number_format($key->valor_antes_impuestos) . '</td>
							<td>$ ' . number_format($key->comision) . '</td>
						</tr>
					';
					$total += $key->comision;
				}
			}
			$ancho = $colspan-1;
			echo '
				<tr aling="center">
					<td colspan="'.$ancho.'">Total</td>
					<td>$'.number_format($total).'</td>
				</tr>
			';
		} else {
			$colspan++;
			echo '
				<tr aling="center">
					<td colspan="'.$colspan.'">No hay datos para mostrar</td>
				</tr>
			';
		}
		
		echo '</tbody>
			</table>';
	}

}
