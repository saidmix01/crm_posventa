<?php

/**
 * esta clase es el controlador de prueba creador por said
 */
class LaminaPintura extends CI_Controller
{

	public function index()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('lamina');


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
			$datos = $this->lamina->TraerDatos();


			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'datos' => $datos);
			//abrimos la vista
			$this->load->view("lamina_pintura/index", $arr_user);
		}
	}

	public function PintarFormulario()
	{
		$this->load->model('lamina');
		$color = $this->lamina->TraerColores();
		$medida = $this->lamina->TraerMedida();
		$prove = $this->lamina->TraerProveedro();
		$fechaActual = date('d-m-Y');
		echo "
			<form id='formProducto'>
			<div class='form-row'>
				<div class='col-lg-6 col-sm-6 col-xs-6'>
					<label for=''>Nombre del Producto</label>
					<input id='nombreProducto' name='nombreProducto' type='text' class='form-control' require>
				</div>
				<div class='col-lg-6 col-sm-6'>
					<label for=''>Marca del Producto</label>
					<input id='MarcaProducto' name='MarcaProducto' type='text' class='form-control'>
				</div>
				<div class='col-lg-6 col-sm-6'>
					<div class='form-group'>
						<label for='nombreSede'>Color del Producto</label>
						<select class='form-control' id='colorProducto' name='colorProducto'>
						";
		foreach ($color->result() as $key) {
			echo "<option value=" . $key->color . ">" . $key->descripcion . "</option>";
		}
		echo "
						</select>
					</div>
				</div>
				<div class='col-lg-6 col-sm-6 col-xs-6 d-none'>
					<input id='usuarioResponsable' name='usuarioResponsable' value='" . $usu = $this->session->userdata('user') . "' type='text' class='form-control' require>
				</div>
				
				<div class='col-lg-6 col-sm-6 col-xs-6 d-none'>
					<input id='fechaHoy' name='fechaHoy' value='" . $fechaActual . "' type='text' class='form-control' require>
				</div>
				<div class='col-lg-6 col-sm-6'>
					<label for=''>Cantidad del Producto</label>
					<input id='catidadProducto' name='catidadProducto' type='text' class='form-control'>
				</div>
				<div class='col-lg-6 col-sm-6'>
					<div class='form-group'>
						<label for='nombreSede'>Medida del Producto</label>
						<select class='form-control' id='medidaProducto' name='medidaProducto'>
						";

		foreach ($medida->result() as $key) {
			echo "<option value=" . $key->id_medida . ">" . $key->medida . "</option>";
		}
		echo "
						</select>
					</div>
				</div>
				<div class='col-lg-6 col-sm-6'>
					<div class='form-group'>
						<label for='nombreSede'>Proveedor del Producto</label>
						<select class='form-control' id='nombreProveedor' name='nombreProveedor'>
						";

		foreach ($prove->result() as $key) {
			echo "<option value=" . $key->id_proveedor . ">" . $key->nombre_proveedor . "</option>";
		}
		echo "
						</select>
					</div>
				</div>

				<div class='col-lg-6 col-sm-6 col-xs-6'>
					<label for=''>Costo del Producto</label>
					<input id='costoProducto' name='costoProducto' type='text' class='form-control' require>
				</div>
				<div class='col-lg-6 col-sm-6 col-xs-6'>
					<label for=''>Precio del Producto</label>
					<input id='precioProducto' name='precioProducto' type='text' class='form-control' require>
				</div>

			</div>
		</form>
			
			";
	}

	public function AgregarProductoLaminaPintura()
	{
		$this->load->model('lamina');
		$producto = $this->input->POST('nombreProducto');
		$marca = $this->input->POST('MarcaProducto');
		$color = $this->input->POST('colorProducto');
		$usuario = $this->input->POST('usuarioResponsable');
		$fecha = $this->input->POST('fechaHoy');
		$cantidad = $this->input->POST('catidadProducto');
		$medida = $this->input->POST('medidaProducto');
		$proveedor = $this->input->POST('nombreProveedor');
		$costo = $this->input->POST('costoProducto');
		$precio = $this->input->POST('precioProducto');

		$Datos = $this->lamina->Regsitrarproducto($producto, $marca, $color, $usuario, $fecha, $cantidad, $medida, $proveedor, $costo, $precio);
		if ($Datos) {
			echo "exito";
		} else {
			echo "no Exito";
		}
	}

	public function EliminarProductoLaminaPintura()
	{
		$this->load->model('lamina');
		$codigo = $this->input->GET('codigo');
		$Datos = $this->lamina->Eliminarproducto($codigo);
		if ($Datos) {
			echo "Exito";
		} else {
			echo "No Exito";
		}
	}


	public function PintarFormualrioEditar()
	{
		$this->load->model('lamina');
		$codigoProducto = $this->input->GET('codigoProducto');
		$Codigo = $this->lamina->PintarProductoEditar($codigoProducto);
		$color = $this->lamina->TraerColores();
		$medida = $this->lamina->TraerMedida();
		$prove = $this->lamina->TraerProveedro();
		$fechaActual = date('d-m-Y');
		foreach ($Codigo->result() as $dto) {
			echo "
			<form id='formularioeditarlaminapintura' method='POST' enctype = 'multipart/form-data'>
			<div class='form-row'>

			<div class='col-lg-6 col-sm-6 col-xs-6 d-none'>
				<input value='" . $dto->id_producto . "' id='editarCodigo' name='editarCodigo' type='text' class='form-control' require>
			</div>
				<div class='col-lg-6 col-sm-6 col-xs-6'>
					<label for=''>Nombre del Producto</label>
					<input value='" . $dto->nombre_producto . "' id='editarnombreProducto' name='editarnombreProducto' type='text' class='form-control' require>
				</div>
				<div class='col-lg-6 col-sm-6'>
					<label for=''>Marca del Producto</label>
					<input value='" . $dto->marca_producto . "' id='editarMarcaProducto' name='editarMarcaProducto' type='text' class='form-control'>
				</div>
				<div class='col-lg-6 col-sm-6'>
					<div class='form-group'>
						<label for='nombreSede'>Color del Producto</label>
						<select class='form-control' id='editarcolorProducto' name='editarcolorProducto'>
						<option value=" . $dto->color . ">" . $dto->descripcion . "</option>
						";
			foreach ($color->result() as $key) {
				echo "<option value=" . $key->color . ">" . $key->descripcion . "</option>";
			}
			echo "
						</select>
					</div>
				</div>
				<div class='col-lg-6 col-sm-6 col-xs-6 d-none'>
					<input id='editarusuarioResponsable' name='editarusuarioResponsable' value='" . $usu = $this->session->userdata('user') . "' type='text' class='form-control' require>
				</div>
				
				<div class='col-lg-6 col-sm-6 col-xs-6 d-none'>
					<input id='editarfechaHoy' name='editarfechaHoy' value='" . $fechaActual . "' type='text' class='form-control' require>
				</div>
				<div class='col-lg-6 col-sm-6'>
					<label for=''>Cantidad del Producto</label>
					<input  value='" . $dto->cantidad_producto . "' id='editarcatidadProducto' name='editarcatidadProducto' type='text' class='form-control'>
				</div>
				<div class='col-lg-6 col-sm-6'>
					<div class='form-group'>
						<label for='nombreSede'>Medida del Producto</label>
						<select class='form-control' id='editarmedidaProducto' name='editarmedidaProducto'>
						<option value=" . $dto->id_medida . ">" . $dto->medida . "</option>
						";
			foreach ($medida->result() as $key) {
				echo "<option value=" . $key->id_medida . ">" . $key->medida . "</option>";
			}
			echo "
						</select>
					</div>
				</div>
				<div class='col-lg-6 col-sm-6'>
					<div class='form-group'>
						<label for='nombreSede'>Proveedor del Producto</label>
						<select class='form-control' id='editarnombreProveedor' name='editarnombreProveedor'>
						<option value=" . $dto->id_proveedor . ">" . $dto->nombre_proveedor . "</option>
						";
			foreach ($prove->result() as $key) {
				echo "<option value=" . $key->id_proveedor . ">" . $key->nombre_proveedor . "</option>";
			}
			echo "
						</select>
					</div>
				</div>
				<div class='col-lg-6 col-sm-6 col-xs-6'>
				<label for=''>Costo del Producto</label>
				<input value=" . $dto->costo . " id='editarcostoProducto' name='editarcostoProducto' type='text' class='form-control' require>
			</div>
			<div class='col-lg-6 col-sm-6 col-xs-6'>
				<label for=''>Precio del Producto</label>
				<input value=" . $dto->precio . " id='editarprecioProducto' name='editarprecioProducto' type='text' class='form-control' require>
			</div>

			</div>
			
		
		</form>
		";
		}
	}

	public function AgregarFormualrioEditar()
	{
		$this->load->model('lamina');
		$producto = $this->input->POST('editarnombreProducto');
		$marca = $this->input->POST('editarMarcaProducto');
		$color = $this->input->POST('editarcolorProducto');
		$usuario = $this->input->POST('editarusuarioResponsable');
		$fecha = $this->input->POST('editarfechaHoy');
		$cantidad = $this->input->POST('editarcatidadProducto');
		$medida = $this->input->POST('editarmedidaProducto');
		$proveedor = $this->input->POST('editarnombreProveedor');
		$costo = $this->input->POST('editarcostoProducto');
		$precio = $this->input->POST('editarprecioProducto');
		$codigo = $this->input->POST('editarCodigo');
		$Datos = $this->lamina->EditarProductoEditar($producto, $marca, $color, $usuario, $fecha, $cantidad, $medida, $proveedor, $costo, $precio, $codigo);
		if ($Datos) {
			echo "Exito";
		} else {
			echo "No Exito";
		}
	}

	/**pintada datos en la vista con formato jsom
	 	$arregloDatos = [];

		if ($Datos != false) {
			foreach ($Datos->result() as $key) {
				$arregloDatos = array(
					'id' => $key->id_producto, 'nombre' => $key->nombre_producto, 'marca' => $key->marca_producto, 'fecha' => $key->fecha_ingreso_producto,
					'cantidad' => $key->cantidad_producto, 'medida' => $key->medida, 'color' => $key->color, 'proveedor' => $key->nombre_proveedor, 'usuario' => $key->nombres
				);
			}
			echo json_encode($arregloDatos);
		}
	 */


	public function salida()
	{
		if (!$this->session->userdata('login')) {
			$this->session->sess_destroy();
			header("Location: " . base_url());
		} else {
			$this->load->model('usuarios');
			$this->load->model('menus');
			$this->load->model('perfiles');
			$this->load->model('lamina');


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
			$datos = $this->lamina->TraerDatos();
			$producto = $this->lamina->TraerProducto();
			$orden = $this->lamina->TraerRefrencia();

			$arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'datos' => $datos, 'producto' => $producto, 'orden' => $orden);
			//abrimos la vista
			$this->load->view("lamina_pintura/salidas", $arr_user);
		}
	}


	/*metodo para ter infomacion de vehiculo segun el numero de orden */
	public function datosvehiculo()
	{
		$this->load->model('lamina');
		$codigo = $this->input->POST('numeroorden');
		$datos = $this->lamina->traerdatospororden($codigo);
		foreach ($datos->result() as $key) {
			echo "
			<div class='form-row'>
				<div class='col'>
					<label>Placa del vehiculo</label>
					<input value='" . $key->placa . "' disabled  type='text' id='ordenplaca' name='ordenplaca' class='form-control' placeholder='Placa'>
				</div>
				<div class='col'>
					<label>Cliente</label>
					<input value='" . $key->nombres . "' disabled  type='text' id='ordencliente' name='ordencliente' class='form-control' placeholder='Clientes'>
				</div>
				<div class='col'>
					<label>Color del vehiculo</label>
					<input value='" . $key->color . "' disabled  type='text' id='ordencolor' name='ordencolor' class='form-control' placeholder='Color'>
				</div>
				</div>
			";
		}
	}


	/*metodo para traer el fomrulario para listar productos a gastar */
	public function datosinventario()
	{
		$this->load->model('lamina');
		$id = $this->input->POST('valor');
		$producto = $this->lamina->TraerP($id);
		foreach ($producto->result() as $key) {
			
			echo "
		<div class='form-row'>
			<div class='col-lg-6 col-sm-6 col-xs-6'>
				<label for=''>Descripcion</label>
				<input disabled value='" . $key->descripcion . "' id='requecolor' name='requecolor' type='text' class='form-control' require>
			</div>
			<div class='col-lg-6 col-sm-6 col-xs-6'>
				<label for=''>Medida</label>
				<input disabled value='" . $key->medida . "' id='requemedia' name='requemedia' type='text' class='form-control' require>
			</div>
			<div class='col-lg-6 col-sm-6 col-xs-6'>
				<label for=''>Disponibles</label>
				<input disabled value='" . $key->cantidad_producto . "' id='requedisponible' name='requedisponible' type='text' class='form-control' require>
			</div>
			<div class='col-lg-6 col-sm-6 col-xs-6'>
				<label for=''>Cantidad</label>
				<input id='requecantidad' name='requecantidad' type='text' class='form-control' require>
			</div>
			<div class='col-lg-6 col-sm-6 col-xs-6'>
				<label for=''>Requerdio por</label>
				<input id='requeuser' name='requeuser' type='text' class='form-control' require>
			</div>
			<div class='col-lg-6 col-sm-6 col-xs-6'>
				<label for=''>Precio</label>
				<input disabled value='" . $key->precio . "' id='requeprecio' name='requeprecio' type='text' class='form-control' require>
			</div>
		
		</div>
		
		<div class='modal-footer'>
			<button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>
			<button type='button' onclick='registroproductorequerido()' class='btn btn-success'>Agregar &nbsp; <span><i class='fas fa-save'></i></span></button>
		</div>
		
			";
		}
	}


	/*metodo para isnertar regsitro de numero de orden */
	public function resgitarnumerooreden()
	{
		$this->load->model('lamina');
		$numeroorden = $this->input->POST('numeroorden');
		$presupuesto = $this->input->POST('presupuesto');
		$estado = $this->input->POST('estado');
		$datos = $this->lamina->listarorden($numeroorden, $presupuesto, $estado);
		var_dump($datos);

		if ($datos) {
			echo "Orden Registrada de Manera Exitosa";
		} else {
			echo "El numero de orden " . $numeroorden . "  se encuentar registrado. ";
		}
	}

	/*metodo para regsitar productos requeridos en la tbala dbo.postv_producto_requerido  */
	public function registrarrequerido()
	{
		$this->load->model('lamina');
		$orden = $this->input->POST('orden');
		$idproducto = $this->input->POST('idproducto');
		$catidad = $this->input->POST('cantidad');
		$requerido = $this->input->POST('requerido');
		$color = $this->input->POST('color');
		$medida = $this->input->POST('medida');
		$disponible = $this->input->POST('disponible');
		$precio = $this->input->POST('precio');
		$fecha = $this->input->POST('fecha');
		$datos = $this->lamina->agrgarproductosrequerido($orden, $idproducto, $catidad, $requerido, $color, $medida, $disponible, $precio, $fecha);
		if ($datos) {
			return true;
		} else {
			return false;
		}
	}

	/* */

	public function traertableproductos()
	{
		$this->load->model('lamina');
		$numeroorden = $this->input->POST('idrefreencia');
		$datos = $this->lamina->traerlistaproductordeorden($numeroorden);
		$totales = $this->lamina->valortotalcolorconsumible($numeroorden);

		if ($datos) {
			echo "
			<div class='scrol'>
			<table class='table table-hover  table-bordered'>
			<thead>
				<tr>
					<th scope='col'>Producto</th>
					<th scope='col'>Descripcion</th>
					<th class='text-center' scope='col'>Cantidad</th>
					<th class='text-center' scope='co'>medida</th>
					<th class='text-center' scope='col'>precio</th>
					<th class='text-center' scope='col'>Editar</th>
					<th class='text-center' scope='col'>Eliminar</th>
				</tr>
			</thead>
			<tbody>
			";
			foreach ($datos->result() as $key) {
				echo "
				<tr>
					<td>$key->nombre_producto</td>
					<td>$key->color</td>
					<td class='text-center'>$key->cantidad_requerida</td>
					<td class='text-center'>$key->medida</td>
					<td class='text-center'>$key->precio</td>
					<td class='text-center'><button class='btn btn-warning text-white'><i class='text-white far fa-edit'></i></button></td>
					<td class='text-center'><button class='btn btn-danger text-white'><i class='far fa-times-circle'></i></button></td>
				</tr>
				";
			}
			echo "
			</tbody>
		</table>
		</div>
			";
		} else {
			echo "no exito";
		}
	}

	/*metodo para traer  la suma de valores totales */
	public function pintartablatotal()
	{
		$this->load->model('lamina');
		$numeroorden = $this->input->POST('orden');
		$valortotal = $this->lamina->valortotal($numeroorden);

		foreach ($valortotal->result() as $valor) {
			echo "
	<table class=''>
	<thead>
		<tr>
			<th class='text-center' scope='col'>Costo Total</th>
			<th class='text-center' scope='col'>Total Color</th>
			<th class='text-center' scope='col'>Total no se</th>
			<th class='text-center' scope='col'>Total Consumibles</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><input value='$valor->precio' id='totalcosto' name='totalcosto' type='text' class='form-control text-center font-weight-bold' disabled></td>
			<td><input id='totalcolor' name='totalcolor' type='text' class='form-control' disabled></td>
			<td><input id='totalnose' name='totalnose' type='text' class='form-control' disabled></td>
			<td><input id='totalconsumo' name='totalconsumo' type='text' class='form-control' disabled></td>
			<td><button type='button' class='btn btn-success form-control col-lg-12'> Cerrar Orden</button> </td>
		</tr>
	</tbody>
</table>
	";
		}
	}
}
