<?php 

/**
 * 
 */
class Flotas extends CI_Controller
{
	
	public function index()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('flotasmodel');
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			$id_usu = 0;
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$flotas = $this->flotasmodel->get_flotas_resumen();
			$arr_user = array('userdata' => $userinfo,'pass'=>$pass,'id_usu'=>$id_usu, 'menus' => $allmenus, 'perfiles' => $allperfiles, "flotas" => $flotas);
			//abrimos la vista
			$this->load->view('Informe_flotas_vh/index',$arr_user);
		}
	}

	public function load_combo_asesores($idCombo='combo_asesor',$vendedor=null)
	{
		$this->load->model('usuarios');
		$asesores = $this->usuarios->get_asesores_activos();
		$coord    = $this->usuarios->get_coordinador_flotas();
		$coord    = $coord->row();
		if ($asesores != null) {
			echo '<select name="asesor" id="'.$idCombo.'" class="js-example-basic-single '.$idCombo.'">
				<option value="">Seleccione un asesor</option>
				<option value="'.$coord->nit.'">'.$coord->nombres.'</option>';
			foreach ($asesores->result() as $key) {
				$selected = $vendedor != null && $vendedor == $key->nit ? 'selected' : '';
				echo '<option value="'.$key->nit.'" '.$selected.'>'.$key->nombres.'</option>';
			}
			echo '</select>';
		} else {
			echo 'No hay datos';
		}
	}

	public function load_combo_flotas()
	{
		$this->load->model('flotasmodel');
		$nitCliente  = $this->input->POST('nitCliente');
		$flotas = $this->flotasmodel->contar_flotas_cliente($nitCliente,1);
		if ($flotas != null) {
			echo '<select name="flota" id="nombre_flota" class="js-example-basic-single-flota" onchange="actualizarAsesor();">';
			echo '<option value=""></option>';
			foreach ($flotas->result() as $key) {
				echo '<option value="'.$key->asesor.'">'.$key->nombre_flota.'</option>';
			}
			echo '</select>';
		} else {
			echo 'No hay datos';
		}
	}

	public function load_tabla_flotas_detallada()
	{
		$this->load->model('flotasmodel');
		$nit_cliente = $this->input->POST('nit_cliente');
		$flotas = $this->flotasmodel->get_flotas_detallado($nit_cliente);
		if ($flotas != null) {
			$count=0;
			echo '
				<table>
					<thead>
						<tr>
							<th>Nit Cliente</th>
							<th>Cliente</th>
							<th>Placa</th>
							<th>Vendedor</th>
							<th>Trabajador Codiesel</th>
							<th>Fecha Venta</th>
							<th>Fecha Última Entrada</th>
							<th>Nombre Flota</th>
							<th>Asesor</th>
							<th>Periodicidad</th>
							<th>Acción</th>
						</tr>
					</thead>
					<tbody>
			';
			foreach ($flotas->result() as $key) {
				$count++;
				echo '<tr>
						<td>'.$key->nit_cliente.'</td>
						<td>'.$key->cliente.'</td>
						<td class="placa_'.$key->nit_cliente.'">'.$key->placa.'</td>
						<td>'.$key->vendedor.'</td>
						<td>'.$key->trabaja_codiesel.'</td>
						<td>'.$key->fecha_venta.'</td>
						<td>'.$key->Fecha_Ultima_Entrada.'</td>
						<td><input type="text" id="nombreFlota_'.$key->placa.'"></td>
						<td>';
							echo $this->load_combo_asesores('combo_'.$key->placa);
						echo
						'</td>
						<td><input type="number" id="periodicidad_'.$key->placa.'" min="0" max="12" onkeyup="validarPeriodicidad(this);"></td>
						<td>
							<button id="asignar_'.$key->placa.'" type="button" class="btn btn-primary" onclick="asignarFlota('.$key->nit_cliente.',\''.$key->placa.'\');">Asignar</button>
							<button type="button" class="btn btn-danger" onclick="desactivarVehiculo('.$key->nit_cliente.',\''.$key->placa.'\');">Desactivar</button>
						</td>
					</tr>';
			}
			echo '</tbody>
			</table>';
		} else {
			echo 'No hay datos';
		}
	}

	public function asignar_flota()
	{
		$this->load->model('flotasmodel');
		$nitCliente   = $this->input->POST('nitCliente');
		$placa        = $this->input->POST('placa');
		$nombreFlota  = $this->input->POST('nombreFlota');
		$asesor       = $this->input->POST('asesor');
		$periodicidad = $this->input->POST('periodicidad');
		$res = $this->flotasmodel->insert_flota($nitCliente, $placa, $nombreFlota, $asesor, $periodicidad);
		echo $res;		
	}

	public function desactivar_vh_flota()
	{
		$this->load->model('flotasmodel');
		$nitCliente  = $this->input->GET('nitCliente');
		$placa       = $this->input->GET('placa');
		$nombreFlota = $this->input->GET('nombreFlota');
		$asesor      = $this->input->GET('asesor');
		$observacion = $this->input->GET('observacion');
		$res = $this->flotasmodel->desactivar_vehiculo($nitCliente, $placa, $nombreFlota, $asesor, $observacion);
		echo $res;
	}

	public function consultar_cliente()
	{
		$this->load->model('flotasmodel');
		$nitCliente  = $this->input->POST('nitCliente');
		$res = $this->flotasmodel->get_data_flota_cliente($nitCliente);
		echo $res;		
	}

	public function guardar_flota()
	{
		$this->load->model('flotasmodel');
		$placa        = $this->input->POST('placa');
		$nitCliente   = $this->input->POST('nitCliente');
		$nombreFlota  = $this->input->POST('nombreFlota');
		$asesor       = $this->input->POST('asesor');
		$periodicidad = $this->input->POST('periodicidad');
		$result = $this->flotasmodel->val_placa_flota($nitCliente,$placa);
		if ($result != null) {
			echo 2;
		}else {
			$res = $this->flotasmodel->insert_flota($nitCliente, $placa, $nombreFlota, $asesor, $periodicidad);
			echo $res;		
		}
	}

	public function load_tabla_vehiculos_flotas()
	{
		$this->load->model('flotasmodel');
		$nit_cliente = $this->input->POST('nit_cliente');
		$flotas = $this->flotasmodel->get_vehiculos_flotas_cliente($nit_cliente);
		if ($flotas != null) {
			foreach ($flotas->result() as $key) {
				echo '<tr>
						<td>'.$key->nombre_flota.'</td>
						<td>'.$key->placa.'</td>
						<td>
							<select id="combo_obs_'.$key->placa.'" onchange="val_observacion(2,\''.$key->placa.'\');">
								<option value="Perdida Total">Perdida Total</option>
								<option value="Vendido">Vendido</option>
								<option value="Cambio de Asesor">Cambio de Asesor</option>
								<option value="Otro">Otro</option>
							</select>
							<input class="d-none" type="text" id="observacion_'.$key->placa.'"></td>
						<td><button type="button" class="btn btn-danger" onclick="desvincularVehiculo('.$key->id_flota.',\''.$key->placa.'\');">Desvincular</button></td>
					</tr>';
			}
		} else {
			echo '';
		}
	}

	public function load_tabla_vehiculos_flotas_total()
	{
		$this->load->model('flotasmodel');
		$this->load->model('usuarios');
		$nit_cliente = $this->input->POST('nit_cliente');
		$flotas = $this->flotasmodel->get_vehiculos_flotas_cliente($nit_cliente,2);
		if ($flotas != null) {
			foreach ($flotas->result() as $key) {
				$row = $this->usuarios->getUserByNit($key->asesor);
				$comisiona = $key->comisiona == 1 ? 'Sí' : 'No';
				$estado = $key->activo == 1 ? 'Activo' : 'Inactivo';
				echo '<tr>
						<td>'.$key->nombre_flota.'</td>
						<td>'.$key->placa.'</td>
						<td>'.$key->observacion_desv.'</td>
						<td>'.$row->nombres.'</td>
						<td>'.$comisiona.'</td>
						<td>'.$estado.'</td>
						<td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalVerLogVehiculo" onclick="obtenerLogVehiculo('.$key->nit.',\''.$key->placa.'\');">Ver Log Estado</button></td>
					</tr>';
			}
		} else {
			echo '';
		}
	}

	public function load_tabla_flotas()
	{
		$this->load->model('flotasmodel');
		$nit_cliente = $this->input->POST('nit_cliente');
		$flotas = $this->flotasmodel->get_flotas_by_nit($nit_cliente);
		if ($flotas != null) {
			foreach ($flotas->result() as $key) {
				$verContactos = $key->contacto != 0 ? '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalVerContactosFlotas" onclick="obtenerContactosFlota('.$key->nit.',\''.$key->nombre_flota.'\');">Ver Contactos</button>' : '';
				echo '<tr>
						<td>'.$key->nombre_flota.'</td>
						<td>
							'.$verContactos.'
							<button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalAddContactosFlotas" onclick="asignarDataFlota('.$key->nit.',\''.$key->nombre_flota.'\');">Agregar Contactos</button>
						</td>
					</tr>';
			}
		} else {
			echo '';
		}
	}

	public function guardar_contactos_flota()
	{
		$this->load->model('flotasmodel');
		$nitFlota    = $this->input->POST('nitFlota');
		$flota       = $this->input->POST('flota');
		$nombres     = $this->input->POST('nombres');
		$cargos      = $this->input->POST('cargos');
		$correos     = $this->input->POST('correos');
		$telefonos   = $this->input->POST('telefonos');
		$direcciones = $this->input->POST('direcciones');
		$resp = $this->flotasmodel->insert_contactos_flota($nitFlota, $flota, $nombres, $cargos, $correos, $telefonos, $direcciones);
		echo $resp;
	}

	public function load_tabla_contactos_flota()
	{
		$this->load->model('flotasmodel');
		$nit = $this->input->POST('nit');
		$flota = $this->input->POST('flota');
		$flotas = $this->flotasmodel->get_contactos_flota($nit,$flota);
		if ($flotas != null) {
			foreach ($flotas->result() as $key) {
				echo '<tr>
						<td>'.$key->nit_cliente.'</td>
						<td>'.$key->nombre_flota.'</td>
						<td>'.$key->nombre.'</td>
						<td>'.$key->cargo.'</td>
						<td>'.$key->correo.'</td>
						<td>'.$key->telefono.'</td>
						<td>'.$key->direccion.'</td>
					</tr>';
			}
		} else {
			echo 'No hay contactos';
		}
	}

	public function load_tabla_log_estados_vh()
	{
		$this->load->model('flotasmodel');
		$this->load->model('usuarios');
		$nit = $this->input->POST('nit');
		$placa = $this->input->POST('placa');
		$flotas = $this->flotasmodel->get_log_estado_flota($nit,$placa);
		if ($flotas != null) {
			foreach ($flotas->result() as $key) {
				$row = $this->usuarios->getUserByNit($key->asesor);
				$estado = $key->activo == 1 ? 'Activo' : 'Inactivo';
				echo '<tr>
						<td>'.$key->nit.'</td>
						<td>'.$key->placa.'</td>
						<td>'.$row->nombres.'</td>
						<td>'.$key->observacion.'</td>
						<td>'.$estado.'</td>
						<td>'.$key->fecha_estado.'</td>
					</tr>';
			}
		} else {
			echo 'No hay datos para mostrar';
		}
	}

	public function load_tabla_flotas_aprobar()
	{
		$this->load->model('flotasmodel');
		$this->load->model('usuarios');

		// 256 : Jose Luis Arenas Pabon
		$usuario = $this->session->userdata('id_user');
		if ($usuario == 256) {
			$flotas = $this->flotasmodel->get_flotas_aprobar_dir_flotas();
			$opUser = 0;
		} else {
			$flotas = $this->flotasmodel->get_flotas_aprobar_postventa();
			$opUser = 1;
		}
		
		if ($flotas != null) {
			echo '
				<table id="tabla_flotas_aprob" class="table table-hover">
					<thead>
						<tr>
							<th>Nit Cliente</th>
							<th>Nombre Flota</th>
							<th>Placa</th>
							<th>Fecha Venta</th>
							<th>Fecha Última Entrada</th>
							<th>Asesor</th>
							<th>Comisiona</th>
							<th>Observación Comisión</th>';
							if ($this->session->userdata('perfil') != 53) {
								echo '<th>Acción</th>';
							}
							echo '
						</tr>
					</thead>
					<tbody>
			';
			$count = 0;
			foreach ($flotas->result() as $key) {
				$count++;
				$sigFila = $flotas->row($count);
				$checked = $key->comisiona == 1 ? 'checked' : '';

				$idFlotas[] = $key->id_flota;
				$btnAprobar = '';

				if ($sigFila->nit != $key->nit || $flotas->num_rows() == $count) {
					$ids_flotas = implode('_',$idFlotas);
					$btnAprobar = '<button type="button" class="btn btn-primary" onclick="aprobarFlota('.$key->nit.',\''.$ids_flotas.'\','.$opUser.');">Aprobar</button>
									<button type="button" class="btn btn-danger" onclick="rechazarFlota(\''.$ids_flotas.'\');">Rechazar</button>';
					unset($idFlotas);
				}

				echo '<tr>
						<td>'.$key->nit.'</td>
						<td>'.$key->nombre_flota.'</td>
						<td>'.strtoupper($key->placa).'</td>
						<td>'.$key->fecha_venta.'</td>
						<td>'.$key->fec_ultima_entrada.'</td>
						<td>';
						if ($usuario == 256) {
							echo $this->load_combo_asesores('aprob_combo_'.$key->nit,$key->asesor);
						} else {
							$asesor = $this->usuarios->getUserByNit($key->asesor);
							echo $asesor->nombres;
						}
						
						echo
						'</td>
						<td>';
						if ($usuario == 256 || $this->session->userdata('perfil') == 53) {
							$comision = $key->comisiona == 1 ? 'Sí' : 'No';
							echo $comision;
						} else {
							echo '<input type="checkbox" class="aprob_check_'.$key->nit.'" '.$checked.'>';
						}
						echo '
						</td>
						<td>'.$key->obs_comision.'</td>';
						if ($this->session->userdata('perfil') != 53) {
							echo '<td>
								'.$btnAprobar.'
								<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalVerContactosFlotas" onclick="obtenerContactosFlota('.$key->nit.',\''.$key->nombre_flota.'\');">Ver Contactos</button>
							</td>';
						}
						echo '
					</tr>';
			}
			echo '</tbody>
			</table>';
		} else {
			echo 'No hay datos';
		}
	}

	public function aprobar_flota()
	{
		$this->load->model('flotasmodel');
		$data = $this->input->POST();
		$resp = $this->flotasmodel->aprobar_flota($data);
		echo $resp;
	}

	public function rechazar_flota()
	{
		$this->load->model('flotasmodel');
		$data = $this->input->POST();
		$resp = $this->flotasmodel->rechazar_flota($data);
		echo $resp;
	}

	public function desvincular_vehiculo_flota()
	{
		$this->load->model('flotasmodel');
		$idFlota     = $this->input->POST('idFlota');
		$observacion = $this->input->POST('observacion');
		$resp = $this->flotasmodel->del_vehiculos_flotas_cliente($idFlota,$observacion);
		echo $resp;
	}

	public function buscar_nit_combo()
	{
		$this->load->model('flotasmodel');
		$cliente  = $this->input->GET('cliente');
		$result = $this->flotasmodel->get_clientes_by_nombre($cliente);
		echo json_encode(array('data'=>$result->result()));
	}

	public function flotas_actualizadas()
	{
		if (!$this->session->userdata('login')) 
		{
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}else{
			//si ya hay datos de session los carga de nuevo
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('flotasmodel');
			$usu = $this->session->userdata('user');
			$pass = $this->session->userdata('pass');
			//obtenemos el perfil del usuario
			$perfil_user = $this->perfiles->getPerfilByUser($usu);
			//cargamos la informacion del usuario y la pasamos a un array
			$userinfo = $this->usuarios->getUserByName($usu);
			$allmenus = $this->menus->getMenusByPerfil($perfil_user->id_perfil);
			$id_usu = 0;
			foreach ($userinfo->result() as $key) {
				$id_usu = $key->id_usuario;
			}
			//$allsubmenus = $this->menus->getAllSubmenus();
			$allperfiles = $this->perfiles->getAllPerfiles();
			$flotas = $this->flotasmodel->get_flotas_actualizadas();
			$arr_user = array('userdata' => $userinfo,'pass'=>$pass,'id_usu'=>$id_usu, 'menus' => $allmenus, 'perfiles' => $allperfiles, "flotas" => $flotas);
			//abrimos la vista
			$this->load->view('Informe_flotas_vh/flotas_comisiona',$arr_user);
		}
	}

	public function registrar_comision()
	{
		$this->load->model('flotasmodel');
		$data = $this->input->POST();
		$resp = $this->flotasmodel->fijar_comision($data);
		echo $resp;
	}

	public function load_tabla_flotas_aprobadas()
	{
		$this->load->model('flotasmodel');
		$this->load->model('usuarios');

		$flotas = $this->flotasmodel->get_flotas_aprobadas();
		
		if ($flotas != null) {
			echo '
				<table id="table_flotas_aprobadas" class="table table-hover">
					<thead>
						<tr>
							<th>Nit Cliente</th>
							<th>Cliente</th>
							<th>Nombre Flota</th>
							<th>Placa</th>
							<th>Fecha Aprobación</th>
							<th>Asesor</th>
							<th>Comisiona</th>
							<th>Observación Comisión</th>
						</tr>
					</thead>
					<tbody>
			';
			foreach ($flotas->result() as $key) {

				$fecha_aprobacion = $key->fecha_aprob_postventa != null ? $key->fecha_aprob_postventa : $key->fecha_aprob_ventas;

				echo '<tr>
						<td>'.$key->nit.'</td>
						<td>'.$key->cliente.'</td>
						<td>'.$key->nombre_flota.'</td>
						<td>'.strtoupper($key->placa).'</td>
						<td>'.$fecha_aprobacion.'</td>
						<td>';
						$asesor = $this->usuarios->getUserByNit($key->asesor);
						echo $asesor->nombres;
						
						echo
						'</td>
						<td>';
						$comision = $key->comisiona == 1 ? 'Sí' : 'No';
						echo $comision;
						echo '
						</td>
						<td>'.$key->obs_comision.'</td>
					</tr>';
			}
			echo '</tbody>
			</table>';
		} else {
			echo 'No hay datos';
		}
	}

	public function load_tabla_flotas_rechazadas()
	{
		$this->load->model('flotasmodel');
		$this->load->model('usuarios');

		$flotas = $this->flotasmodel->get_flotas_rechazadas();
		
		if ($flotas != null) {
			echo '
				<table id="table_flotas_rechazadas" class="table table-hover">
					<thead>
						<tr>
							<th>Nit Cliente</th>
							<th>Cliente</th>
							<th>Nombre Flota</th>
							<th>Placa</th>
							<th>Fecha Rechazo</th>
							<th>Asesor</th>
							<th>Comisiona</th>
							<th>Observación Comisión</th>
						</tr>
					</thead>
					<tbody>
			';
			foreach ($flotas->result() as $key) {

				$fecha_rechazo = $key->fecha_rechazo_postventa != null ? $key->fecha_rechazo_postventa : $key->fecha_rechazo_ventas;

				echo '<tr>
						<td>'.$key->nit.'</td>
						<td>'.$key->cliente.'</td>
						<td>'.$key->nombre_flota.'</td>
						<td>'.strtoupper($key->placa).'</td>
						<td>'.$fecha_rechazo.'</td>
						<td>';
						$asesor = $this->usuarios->getUserByNit($key->asesor);
						echo $asesor->nombres;
						echo
						'</td>
						<td>';
						$comision = $key->comisiona == 1 ? 'Sí' : 'No';
						echo $comision;
						echo '
						</td>
						<td>'.$key->obs_comision.'</td>
					</tr>';
			}
			echo '</tbody>
			</table>';
		} else {
			echo 'No hay datos';
		}
	}
	
}

?>
