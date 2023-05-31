<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Mpdf\Writer\BackgroundWriter;
use PhpOffice\PhpSpreadsheet\Worksheet\PageMargins;

/**
 *
 * Controller Sacyr
 *
 * This controller for ...
 *
 * @package   CodeIgniter
 * @category  Controller CI
 * @author    Setiawan Jodi <jodisetiawan@fisip-untirta.ac.id>
 * @author    Raul Guerrero <r.g.c@me.com>
 * @link      https://github.com/setdjod/myci-extension/
 * @param     ...
 * @return    ...
 *
 */

class Sacyr extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    if (!$this->session->userdata('login')) {
      $this->session->sess_destroy();
      header("Location: " . base_url());
    } else {
      //se cargan los modelos
      $this->load->model('usuarios');
      $this->load->model('menus');
      $this->load->model('perfiles');
      $this->load->model('sedes');
      $this->load->model('ausentismos');
      /* Obtenemos la fecha y hora del servidor para validar, a la hora de crear un ausentismo */
      $fecha_actual = $this->ausentismos->getFecha();
      /* Valida si la fecha actual es festivo y devuelve 1 si es festivo... */
      $boolDiaEsFestivo = $this->ausentismos->diasFestivos($fecha_actual->fecha_actual);



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
      //sedes bodegas
      $sedes_bod = $this->sedes->get_sedes_de_bodegas();
      //obtenemos los ausentismos por el usuario
      $ausentismos = $this->ausentismos->get_ausentismos_by_user($usu);
      //parseamos a json
      $data_ausen[] = array();
      foreach ($ausentismos->result() as $key) {
        $color = "";
        if ($key->autorizacion == "1") {
          $color = "#009C13";
        } elseif ($key->autorizacion == "2") {
          $color = "#FF0000";
        } else {
          $color = "#0064FF";
        }
        $data_ausen[] = array('id' => $key->id_ausen, 'title' => $key->hora_ini . '-' . $key->titulo, 'start' => $key->fecha_ini, 'end' => $key->fecha_fin, 'descripcion' => $key->descripcion, 'color' => $color);
      }
      //print_r($ausen_json);die;
      $arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'sedes_bod' => $sedes_bod, 'ausentismos' => json_encode($data_ausen), 'dia_actual' => $boolDiaEsFestivo, 'fecha_actual' => $fecha_actual->fecha_actual);
      //abrimos la vista

      $this->load->view("Sacyr/index", $arr_user);
    }
  }
  public function loadReportSacyr()
  {
    if (!$this->session->userdata('login')) {
      $this->session->sess_destroy();
      header("Location: " . base_url());
    } else {
      $this->load->model('Sacyr_model');
      $data = $this->Sacyr_model->loadReport();

      if ($data->num_rows() > 0) {
        foreach ($data->result() as $key) {
          //Cantidad minima
          $porcInv = ceil(number_format($key->cant_inventario, 0, ',', ' ') - (number_format($key->cant_inventario, 0, ',', ' ') * (80 / 100)));
          $porcApro = ceil(number_format($key->cant_aprobada, 0, ',', ' ') - (number_format($key->cant_aprobada, 0, ',', ' ') * (80 / 100)));
          //Sumatoria para la disponibilidad
          $dispInventario = number_format($key->cant_inventario, 0, ',', ' ') - (number_format($key->cant_facturada, 0, ',', ' ') + number_format($key->cant_ot, 0, ',', ' '));
          $dispAprobado = number_format($key->cant_aprobada, 0, ',', ' ') - (number_format($key->cant_facturada, 0, ',', ' ') + number_format($key->cant_ot, 0, ',', ' '));
          //Condicional si cumple con la cantidad minima en la sumatoria
          $colorInv = 'Style="background-color:#28a745;"';
          $colorApro = 'Style="background-color:#28a745;"';
          if ($dispInventario <= $porcInv) {
            $colorInv = 'Style="background-color:#dc3545;"';
          }
          if ($dispAprobado <= $porcApro) {
            $colorApro = 'Style="background-color:#dc3545;"';
          }
          $btnDisabled = "";
          if ($key->cant_facturada == 0 && $key->cant_ot == 0) {
            $btnDisabled = "disabled";
          }
          /* Se comenta la ultima columna de la tabla, por información inconsistente..
           <td data-toggle="tooltip" data-placement="top"  data-html="true"  title="'.$porcInv.'" '.$colorInv.'>'.number_format($dispInventario, 0, ',', ' ').'</td> */
          echo '
            <tr>
              <td><button ' . $btnDisabled . ' class="btn btn-info" onclick="loadReportDetailSacyr(' . $key->codigo . ')">' . $key->codigo . '</button></td>
              <td>' . $key->descripcion . '</td>
              <td>' . number_format($key->cant_aprobada, 0, ',', ' ') . '</td>
              <td>' . number_format($key->cant_inventario, 0, ',', ' ') . '</td>
              <td>' . number_format($key->cant_facturada, 0, ',', ' ') . '</td>
              <td>' . number_format($key->cant_ot, 0, ',', ' ') . '</td>
              <td data-toggle="tooltip" data-placement="top"  data-html="true"  title="' . $porcApro . '" ' . $colorApro . '>' . number_format($dispAprobado, 0, ',', ' ') . '</td>
            </tr>
          ';
        }
      } else {
        echo '
          <tr>
            <td colspan="5">No se encontraron datos para realizar el Informe.</td>
          </tr>
        ';
      }
    }
  }
  //Cargar Informe a detalle por codigo
  public function loadReportSacyrDetail()
  {
    if (!$this->session->userdata('login')) {
      $this->session->sess_destroy();
      header("Location: " . base_url());
    } else {
      $this->load->model('Sacyr_model');
      $codigo = $this->input->POST('codigo');
      $data = $this->Sacyr_model->loadReportDetail($codigo);
      if ($data->num_rows() > 0) {

        foreach ($data->result() as $key) {
          echo
          '
            <tr>
              <td>' . $key->codigo . '</td>
              <td>' . $key->descripcion . '</td>
              <td>' . $key->cant_facturada . '</td>
              <td>' . $key->ot_factura . '</td>
              <td>' . $key->cant_ot . '</td>
              <td>' . $key->numero_ot . '</td>
            </tr>
          ';
        }
      } else {

        echo
        '
          <tr><td colspan="6">No se ha encontrado información</td></tr>
        ';
      }
    }
  }
  //Cotizador Sacyr
  public function contizacion()
  {
    if (!$this->session->userdata('login')) {
      $this->session->sess_destroy();
      header("Location: " . base_url());
    } else {
      //se cargan los modelos
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

      //print_r($ausen_json);die;
      $arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu);
      //abrimos la vista

      $this->load->view("Sacyr/cotizador", $arr_user);
    }
  }

  public function loadCotizador()
  {
    if (!$this->session->userdata('login')) {
      $this->session->sess_destroy();
      header("Location: " . base_url());
    } else {
      $this->load->model('Sacyr_model');
      $data = $this->Sacyr_model->getCotizador();

      foreach ($data->result() as $key) {
        $v_unitario = number_format(ceil($key->valor_unitario), 0, ',', '.');
        $v_contrato = number_format(ceil($key->valor_contrato), 0, ',', '.');
        $unidades_disponibles = number_format($key->disponible, 0, ',', '');
        echo '<tr>
        <td>' . $key->codigo . '</td>
        <td>' . $key->descripcion . '</td>
        <td>' . $key->busqueda . '</td>
        <td>' . $v_unitario . '</td>
        <td>' . $v_contrato . '</td>
        <td>' . $key->cantidad_contrato . '</td>
        <td>' . $unidades_disponibles . '</td>
        <td>' . $key->bodega . '</td>
        <td><button class="btn btn-success" onclick="addItem(\'' . $key->codigo . '\',\'' . $key->descripcion . '\',\'' . $v_unitario . '\',\'' . $v_contrato . '\',\'' . $key->cantidad_contrato . '\',\'' . $unidades_disponibles . '\',\'' . $key->bodega . '\');">Agregar</button></td>
        </tr>';
      }
    }
  }


  public function guardarDatosCotizacion()
  {
    $this->load->model('Sacyr_model');

    $usu = $this->session->userdata('user');
    $placa = $this->input->POST('placa');
    $descModelo = $this->input->POST('descModelo');
    $km_actual = $this->input->POST('km_actual');
    $observacion = $this->input->POST('obs');
    $ordenT = $this->input->POST('ordenT');

    //Variable opción para verificar si es guardar o editar :XD

    $dataInsert = array(
      'usuario' => $usu,
      'placa_vh' => $placa,
      'desc_modelo_vh' => $descModelo,
      'km_actual' => $km_actual,
      'fecha_creacion' => date('Ymd H:i:s'),
      'observacion' => $observacion,
      'ordenT' => $ordenT
    );


    $id_cotizacion = $this->Sacyr_model->insertCotizacion($dataInsert);

    if ($id_cotizacion > 0) {


      //Repuestos
      $nItemsAdd = 0;
      $nItems = $this->input->POST('cantItems');
      $dataItems = [];
      if ($nItems > 0) {
        for ($i = 0; $i < $nItems; $i++) {
          $dataItems[$i] =  $this->input->POST('datosItems' . $i);
          if ($this->Sacyr_model->insertRespuestos($id_cotizacion, explode(",", $dataItems[$i]))) {
            $nItemsAdd++;
          }
        }
      }


      //Mano de Obra
      $nItemsManoObraAdd = 0;
      $nItemsManoObra = $this->input->POST('cantItemsManoObra');
      $dataItemsManoObra = [];
      if ($nItemsManoObra > 0) {
        for ($i = 0; $i < $nItemsManoObra; $i++) {
          $dataItemsManoObra[$i] =  $this->input->POST('datosItemsManoObra' . $i);
          if ($this->Sacyr_model->insertManoObra($id_cotizacion, explode(",", $dataItemsManoObra[$i]))) {
            $nItemsManoObraAdd++;
          }
        }
      }


      //ToT
      $nItemsToTAdd = 0;
      $nItemsToT = $this->input->POST('cantItemsToT');
      $dataItemsToT = [];
      if ($nItemsToT > 0) {
        for ($i = 0; $i < $nItemsToT; $i++) {
          $dataItemsToT[$i] =  $this->input->POST('datosItemsToT' . $i);
          if ($this->Sacyr_model->insertToT($id_cotizacion, explode(",", $dataItemsToT[$i]))) {
            $nItemsToTAdd++;
          }
        }
      }


      $dataCotizacion = array(
        'id_cotizacion' => $id_cotizacion,
        'nRepuestosAdd' => $nItemsAdd,
        'nManoObraAdd' => $nItemsManoObraAdd,
        'nToTAdd' => $nItemsToTAdd,
      );


      echo json_encode($dataCotizacion);
    } else {
      echo null;
    }
  }

  /* Visualizar cotizacion en PDF */
  public function verPdfCotizacion()
  {
    $this->load->model('Sacyr_model');
    $id = $this->input->post_get('id_cotizacion');
    $placa = $this->input->post_get('placa');

    $dataGeneral = $this->Sacyr_model->getCotizacion($id, $placa);
    /* Validar si existe información del id o número de cotizacion u Placa */
    if (!empty($dataGeneral->result())) {

      $dataRepuestos = $this->Sacyr_model->getRepuestosCoti($id);
      $dataManoObra = $this->Sacyr_model->getManoObraCoti($id);
      $dataToT = $this->Sacyr_model->getToTCoti($id);

      // Información para crear el PDF
      $dataCotizacion = array(
        'dataGeneral' => $dataGeneral,
        'dataRepuestos' => $dataRepuestos,
        'dataManoObra' => $dataManoObra,
        'dataToT' => $dataToT
      );
      /* Cargar vista web en el navegador */
      /* $this->load->view('Sacyr/pdfCotizacion', $dataCotizacion); */


      // Cargar libreria de PDF 
      $mpdfConfig = array(
        'mode' => 'utf-8',
        'format' => 'A4',    // format - A4, for example, default ''
        //'default_font_size' => 0,     // font size - default 0
        //'default_font' => 'Helveltica',    // default font family
        'margin_left' => 5,      // 15 margin_left
        'margin_right' => 5,      // 15 margin right
        'margin_top' =>  21,   // 16 margin top
        'margin_bottom' => 30,    // margin bottom contra el footer
        'margin_header' => 5,     // 9 margin header
        'margin_footer' => 5,     // 9 margin footer
        'orientation' => 'P'    // L - landscape, P - portrait
      );
      $mpdf = new \Mpdf\Mpdf($mpdfConfig);
      // $mpdf = new \Mpdf\Mpdf();  
      //Header
      $mpdf->SetHTMLHeader('<div style="position:absolute;" class=""><img src="' . base_url() . '/public/headerCotizacion2.png" width="100%" /></div>');
      // Footer 
      $mpdf->SetHTMLFooter('
				<p style="text-align:justify; font-size:12px"><b>*</b><i>Validez de la oferta en repuestos 5 días y 30 días en mano de obra y otros. </i></p>
				<p style="text-align:justify; font-size:12px"><b>*</b><i>Garantía 1 ańo o 20.000 Km a partir de la fecha de entrega del servicio en el taller, en repuestos que no correspondan a partes de desgaste.</i></p>
				<p style="text-align:justify; font-size:12px"><b>*</b><i>Según lo estipulado en el cupón de garantía, la no sustitución de repuestos considerados mandatorios, conlleva a la renuncia del beneficio de extensión de garantía y a la garantía de fábrica. </i></p>
			');
      //body			

      $html = $this->load->view('Sacyr/pdfCotizacion', $dataCotizacion, true);


      $mpdf->WriteHTML($html);

      // Contraseña 
      // $mpdf->SetProtection(array(), "$placa", "C0D13S3L");

      /* Añadir ceros a la izquierda :D */
      $numberS = "$id";

      $tamaño = (int) strlen($numberS);
      if ($tamaño <= 4) {
        $length = 4;
      } else {
        $length = $tamaño + 1;
      }
      $Id_Cotizacion = substr(str_repeat(0, $length) . $id, -$length);

      $mpdf->Output('Cotizacion-' . $Id_Cotizacion . '-' . $placa . '.pdf', 'I');
      exit;
    }
  }

  /* Informe de las cotizaciones realizadas... */
  public function Informe()
  {
    if (!$this->session->userdata('login')) {
      $this->session->sess_destroy();
      header("Location: " . base_url());
    } else {
      //se cargan los modelos
      $this->load->model('usuarios');
      $this->load->model('menus');
      $this->load->model('perfiles');
      $this->load->model('Sacyr_model');


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
      if ($perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 20) {
        $dataCotizacion = $this->Sacyr_model->getInf_Cotizacion();
      } else {
        $where = "where c.usuario = $usu";
        $dataCotizacion = $this->Sacyr_model->getInf_Cotizacion($where);
      }


      //print_r($ausen_json);die;
      $arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'dataCotizacion' => $dataCotizacion);
      //abrimos la vista

      $this->load->view("Sacyr/Informe", $arr_user);
    }
  }

  public function cargarInformeCotizacion()
  {
    if (!$this->session->userdata('login')) {
      $this->session->sess_destroy();
      header("Location: " . base_url());
    } else {
      //se cargan los modelos
      $this->load->model('Sacyr_model');
      $this->load->model('perfiles');


      //si ya hay datos de session los carga de nuevo
      $usu = $this->session->userdata('user');
      //obtenemos el perfil del usuario
      $perfil_user = $this->perfiles->getPerfilByUser($usu);

      if ($perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 20) {
        $dataCotizacion = $this->Sacyr_model->getInf_Cotizacion();
      } else {
        $where = "where c.usuario = $usu";
        $dataCotizacion = $this->Sacyr_model->getInf_Cotizacion($where);
      }
      if (count($dataCotizacion->result()) > 0) {
        foreach ($dataCotizacion->result() as $key) {
          $orden = $key->ordenT;
          if ($key->ordenT == "" || $key->orden < 0) {
            $orden = '<input type="hidden" id="id_cotizacion" name="id_cotizacion" value="' . $key->id . '">
            <input type="number" id="ordenT" name="ordenT" value="" placeholder="Número de orden">
            <button data-toggle="tooltip" data-placement="top" title="Guardar número de orden"  type="button" class="btn btn-warning" onclick="guardarOrdenT(' . $key->id . ');"><i class="fas fa-save"></i></button>';
          }


          echo '<tr scope="row">
            <td scope="col">' . $key->id . '</td>
            <td scope="col">' . $key->nombres . '</td>
            <td scope="col">' . $key->placa_vh . '</td>
            <td scope="col">' . $orden . '</td>
            <td scope="col">' . $key->desc_modelo_vh . '</td>
            <td scope="col">' . $key->km_actual . '</td>
            <td scope="col">' . $key->fecha_creacion . '</td>
            <td scope="col">
              <button data-toggle="tooltip" data-placement="top" title="Cotización en PDF"  type="button" class="btn btn-success" onclick="verCotizacion(' . $key->id . ',\'' . $key->placa_vh . '\');"><i class="fas fa-file-invoice"></i></button>
              <button data-toggle="tooltip" data-placement="top" title="Clonar o Duplicar Cotización" type="button" class="btn btn-danger" onclick="copy_Cotizacion(' . $key->id . ',\'' . $key->placa_vh . '\');"><i class="fas fa-clone"></i></button>
              <button data-toggle="tooltip" data-placement="top" title="Editar Cotización" type="button" class="btn btn-info" onclick="EditCotizacion(' . $key->id . ');"><i class="fas fa-pen"></i></button>
            </td>
          </tr>';
        }
      }
    }
  }

  /* Funcion para gestionar los repuestos que no contienen campo de busqueda */

  public function agregar_repuestos()
  {
    if (!$this->session->userdata('login')) {
      $this->session->sess_destroy();
      header("Location: " . base_url());
    } else {
      //se cargan los modelos
      $this->load->model('usuarios');
      $this->load->model('menus');
      $this->load->model('perfiles');
      $this->load->model('Sacyr_model');


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
      if ($perfil_user->id_perfil == 1 || $perfil_user->id_perfil == 20) {
        $dataCotizacion = $this->Sacyr_model->getInf_Cotizacion();
      } else {
        $where = "where c.usuario = $usu";
        $dataCotizacion = $this->Sacyr_model->getInf_Cotizacion($where);
      }


      //print_r($ausen_json);die;
      $arr_user = array('userdata' => $userinfo, 'menus' => $allmenus, 'perfiles' => $allperfiles, 'pass' => $pass, 'id_usu' => $id_usu, 'dataCotizacion' => $dataCotizacion);
      //abrimos la vista

      $this->load->view("Sacyr/addRepuestos", $arr_user);
    }
  }

  public function cargarRepuestos()
  {
    if (!$this->session->userdata('login')) {
      $this->session->sess_destroy();
      header("Location: " . base_url());
    } else {
      $this->load->model('Sacyr_model');
      $data = $this->Sacyr_model->getRepuestosBusquedaNull();
      $bandera = 0;
      foreach ($data->result() as $key) {

        $v_unitario = number_format(ceil($key->valor_unitario), 0, ',', '.');
        $v_contrato = number_format(ceil($key->valor_contrato), 0, ',', '.');
        $unidades_disponibles = number_format($key->disponible, 0, ',', '');

        echo '<tr>
        <td>' . $key->codigo . '</td>
        <td>' . $key->descripcion . '</td>
        <td><textarea class="form-control" id="fied' . $bandera . '" name="fied' . $bandera . '"></textarea></td>
        <td>' . $v_unitario . '</td>
        <td>' . $v_contrato . '</td>
        <td>' . $key->cantidad_contrato . '</td>
        <td>' . $unidades_disponibles . '</td>
        <td>' . $key->bodega . '</td>
        <td><button class="btn btn-success" onclick="addItem(\'' . $key->codigo . '\',\'fied' . $bandera . '\');">Agregar</button></td>
        </tr>';
        $bandera++;
      }
    }
  }
  /* Funcion para agregar el campo de busqueda a los respuestos a cotizar */
  public function addFieldSearch()
  {
    if (!$this->session->userdata('login')) {
      $this->session->sess_destroy();
      header("Location: " . base_url());
    } else {
      $this->load->model('Sacyr_model');

      $codigo = $this->input->POST('codigo');
      $fiedSearch = $this->input->POST('fiedSearch');

      $data = array(
        'codigo' => $codigo,
        'descripcion' => $fiedSearch
      );
      if ($this->Sacyr_model->insertfiedSearch($data)) {
        echo true;
      } else {
        echo false;
      }
    }
  }

  /* Funcion para cargar la vista de editar */
  public function CopyCotizacion()
  {
    if (!$this->session->userdata('login')) {
      $this->session->sess_destroy();
      header("Location: " . base_url());
    } else {
      $this->load->model('Sacyr_model');
      //se cargan los modelos
      $this->load->model('usuarios');
      $this->load->model('menus');
      $this->load->model('perfiles');
      $this->load->model('Sacyr_model');


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
      $id_cotizacion = $this->input->POST('id_cotizacion');

      $where = "WHERE c.id = $id_cotizacion";
      $dataCotizacion = $this->Sacyr_model->getInf_Cotizacion($where)->row(0);

      //print_r($ausen_json);die;
      $arr_user = array(
        'userdata' => $userinfo,
        'menus' => $allmenus,
        'perfiles' => $allperfiles,
        'pass' => $pass,
        'id_usu' => $id_usu,
        'id_cotizacion' => $id_cotizacion,
        'placa' => $dataCotizacion->placa_vh,
        'observacion' => $dataCotizacion->observacion,
      );
      //abrimos la vista

      $this->load->view("Sacyr/copy_cotizacion", $arr_user);
    }
  }

  /* Funcion para cargar los items o repuestos de una cotización para modificar */

  public function loadItemRepuestosCotizacion()
  {
    if (!$this->session->userdata('login')) {
      $this->session->sess_destroy();
      header("Location: " . base_url());
    } else {
      $this->load->model('Sacyr_model');
      //se cargan los modelos
      $id_cotizacion = $this->input->POST('id_cotizacion');
      $opcion = $this->input->POST('opcion');

      $dataRepuestos = $this->Sacyr_model->getRepuestosCoti($id_cotizacion);

      if (count($dataRepuestos->result()) > 0) {
        foreach ($dataRepuestos->result() as $key) {


          $infRepuesto = $this->Sacyr_model->getRepuestosCodigo($key->codigo);

          $bodega = $infRepuesto->row(0)->bodega;
          $unid_disp = number_format(($infRepuesto->row(0)->disponible), 0, ',', '.');


          $checked = "";
          if ($key->autorizado == 1) {
            $checked = "checked";
          }

          $valor_unidad = $key->valor_unidad;
          $cant = $key->cantidad;
          $p_desc = $key->descuento;
          $valor_cantidad =  $valor_unidad * $cant;
          $descuento = ceil($valor_cantidad * ($p_desc / 100));
          $cantContrato = ($key->cant_contrato == 0) ? 'N/A C' :  $key->cant_contrato;
          if ($opcion == 2) {
            echo '<tr id="' . $key->codigo . '_tr" class="fila_cotizador">
            <td>' . $key->codigo . '</td>
            <td>' . $key->descripcion . '</td>
            <td><input disabled type="number" id="' . $key->codigo . '" value="' . $key->cantidad . '" class="cantidadTotal form-control" min="1"  style="width: 70px; display: inline;"></td>
            <td><input disabled type="number" id="' . $key->codigo . '_d" value="' . $key->descuento . '" class="form-control" min="0" max="100" style="width: 70px; display: inline;"></td>
            <td id="' . $key->codigo . '_v">' . number_format($key->valor_unidad, 0, ',', '.') . '</td>
            <td id="' . $key->codigo . '_cto">' . $cantContrato . '</td>
            <td id="' . $key->codigo . '_desc" class="v_desc">' . number_format($descuento, 0, ',', '.') . '</td>
            <td id="' . $key->codigo . '_t" class="valor_total d-none">' . number_format($key->valor_total, 0, ',', '.') . '</td>
            <td>' . $unid_disp . '</td>
            <td>' . $bodega . '</td>
            <td id="' . $key->codigo . '_desc_T" class="v_desc_T">' . number_format($valor_cantidad, 0, ',', '.') . '</td>      
            <td><input ' . $checked . ' type="checkbox" id="' . $key->codigo . '_check" class="form-control" value="' . $key->autorizado . '" onclick="validar_check(\'' . $key->codigo . '\',' . $key->id . ')"></td>
            </tr>';
          } else {
            echo '<tr id="' . $key->codigo . '_tr" class="fila_cotizador">
            <td>' . $key->codigo . '</td>
            <td>' . $key->descripcion . '</td>
            <td><input type="number" id="' . $key->codigo . '" value="' . $key->cantidad . '" class="cantidadTotal form-control" min="1" onchange="calcularValorTotal(\'' . $key->codigo . '\')" style="width: 70px; display: inline;"></td>
            <td><input type="number" id="' . $key->codigo . '_d" value="' . $key->descuento . '" class="form-control" min="0" max="100" onchange="validarDescuento(\'' . $key->codigo . '\');calcularValorTotal(\'' . $key->codigo . '\')" style="width: 70px; display: inline;"></td>
            <td id="' . $key->codigo . '_v">' . number_format($key->valor_unidad, 0, ',', '.') . '</td>
            <td id="' . $key->codigo . '_cto">' . $cantContrato . '</td>
            <td id="' . $key->codigo . '_desc" class="v_desc">' . number_format($descuento, 0, ',', '.') . '</td>
            <td id="' . $key->codigo . '_t" class="valor_total d-none">' . number_format($key->valor_total, 0, ',', '.') . '</td>
            <td>' . $unid_disp . '</td>
            <td>' . $bodega . '</td>
            <td id="' . $key->codigo . '_desc_T" class="v_desc_T">' . number_format($valor_cantidad, 0, ',', '.') . '</td>      
            <td><input ' . $checked . ' type="checkbox" id="' . $key->codigo . '_check" class="form-control" value="' . $key->autorizado . '" onclick="validar_check(\'' . $key->codigo . '\')"></td>
            <td><button type="button" id="' . $key->codigo . '" class="btn btn-danger" onclick="eliminarItem(\'' . $key->codigo . '\')">Eliminar</button></td>
            </tr>';
          }
        }
      }
    }
  }

  /* Funcion para obtener los item de mano de obra de la cotizacion */
  public function loadItemManaObraCotizacion()
  {
    if (!$this->session->userdata('login')) {
      $this->session->sess_destroy();
      header("Location: " . base_url());
    } else {
      $this->load->model('Sacyr_model');
      //se cargan los modelos
      $id_cotizacion = $this->input->POST('id_cotizacion');
      $opcion = $this->input->POST('opcion');

      $dataManoObra = $this->Sacyr_model->getManoObraCoti($id_cotizacion);

      /* Campos de la busqueda::
       id	operacion	cant_horas	valor_total	autorizado	id_cotizacion 
      */

      if (count($dataManoObra->result()) > 0) {
        $bandera = 0;
        foreach ($dataManoObra->result() as $key) {
          $checked = "";
          if ($key->autorizado == 1) {
            $checked = "checked";
          }

          if ($opcion == 2) {
            echo '<tr id="fila_mano_obra' . $bandera . '" class="fila_mano">
            <td><textarea disabled id="textOperacionMano' . $bandera . '" class="form-control" placeholder="Escriba aquí la descripción de la operación" maxlength="300">' . $key->operacion . '</textarea></td>
            <td><input disabled type="number" id="cantHora_' . $bandera . '" value="' . $key->cant_horas . '" class="cantidadTotal_Horas form-control" min="0" step="0.25" style="width: 70px; display: inline;"></td>
            <td>65.000</td>
            <td id="v_hora_' . $bandera . '" class="valor_total_hora">' . number_format($key->valor_total, 0, ',', '.') . '</td>
            <td><input ' . $checked . ' type="checkbox" id="v_hora_' . $bandera . '_ck" class="form-control" value="' . $key->autorizado . '" onclick="validar_checkManoObra(' . $bandera . ',' . $key->id . ')"></td>
            </tr>';
          } else {
            echo '<tr id="fila_mano_obra' . $bandera . '" class="fila_mano">
            <td><textarea id="textOperacionMano' . $bandera . '" class="form-control" placeholder="Escriba aquí la descripción de la operación" maxlength="300">' . $key->operacion . '</textarea></td>
            <td><input type="number" id="cantHora_' . $bandera . '" value="' . $key->cant_horas . '" class="cantidadTotal_Horas form-control" min="0" step="0.25" onchange="calcularValorTotal_Hora_Mano(' . $bandera . ')" style="width: 70px; display: inline;"></td>
            <td>65.000</td>
            <td id="v_hora_' . $bandera . '" class="valor_total_hora">' . number_format($key->valor_total, 0, ',', '.') . '</td>
            <td><input ' . $checked . ' type="checkbox" id="v_hora_' . $bandera . '_ck" class="form-control" value="' . $key->autorizado . '" onclick="validar_checkManoObra(' . $bandera . ')"></td>
            <td><button type="button" id="' . $bandera . '" class="btn btn-danger" onclick="eliminarItemManoObra(' . $bandera . ')">Eliminar</button></td>
            </tr>';
          }


          $bandera++;
        }
      }
    }
  }

  /* Funcion para obtener los item de ToT de la cotizacion */
  public function loadItemToTCotizacion()
  {
    if (!$this->session->userdata('login')) {
      $this->session->sess_destroy();
      header("Location: " . base_url());
    } else {
      $this->load->model('Sacyr_model');
      //se cargan los modelos
      $id_cotizacion = $this->input->POST('id_cotizacion');
      $opcion = $this->input->POST('opcion');

      $dataToT = $this->Sacyr_model->getToTCoti($id_cotizacion);
      /* 
        campos de la consulta 
        id	operacion	valor_total	autorizado	id_cotizacion 
      */
      if (count($dataToT->result()) > 0) {
        $bandera = 0;
        foreach ($dataToT->result() as $key) {
          $checked = "";
          if ($key->autorizado == 1) {
            $checked = "checked";
          }
          if ($opcion == 2) {
            echo '<tr id="fila_ToT' . $bandera . '" class="fila_ToT">
            <td><textarea disabled id="" class="form-control" placeholder="Escriba aquí la descripción de la operación" maxlength="300">' . $key->operacion . '</textarea></td>
            <td><input disabled type="text" id="v_' . $bandera . '_ToT" value="' . number_format($key->valor_total, 0, ',', '.') . '" class="form-control valor_total_ToT" min="0" onkeyup="isNumero(this)" style="width: 120px; display: inline;"></td>
            <td><input ' . $checked . ' type="checkbox" id="v_tot_' . $bandera . '_ck" class="form-control" value="1" onclick="validar_checkToT(' . $bandera . ',' . $key->id . ')"></td>
            </tr>';
          } else {
            echo '<tr id="fila_ToT' . $bandera . '" class="fila_ToT">
            <td><textarea id="" class="form-control" placeholder="Escriba aquí la descripción de la operación" maxlength="300">' . $key->operacion . '</textarea></td>
            <td><input type="text" id="v_' . $bandera . '_ToT" value="' . number_format($key->valor_total, 0, ',', '.') . '" class="form-control valor_total_ToT" min="0" onkeyup="isNumero(this)" style="width: 120px; display: inline;"></td>
            <td><input ' . $checked . ' type="checkbox" id="v_tot_' . $bandera . '_ck" class="form-control" value="1" onclick="validar_checkToT(' . $bandera . ')"></td>
            <td><button type="button" id="' . $bandera . '" class="btn btn-danger" onclick="eliminarItemToT(' . $bandera . ')">Eliminar</button></td>
            </tr>';
          }
          $bandera++;
        }
      }
    }
  }

  public function insertOrdenT()
  {
    $this->load->model('Sacyr_model');
    $id = $this->input->post_get('id');
    $ordenT = $this->input->post_get('ordenT');

    if ($this->Sacyr_model->insertOrdenT($id, $ordenT)) {
      echo 'success';
    } else {
      echo 'error';
    }
  }


  /* Funcion para cargar la vista de editar */
  public function EditarCotizacion()
  {
    if (!$this->session->userdata('login')) {
      $this->session->sess_destroy();
      header("Location: " . base_url());
    } else {
      $this->load->model('Sacyr_model');
      //se cargan los modelos
      $this->load->model('usuarios');
      $this->load->model('menus');
      $this->load->model('perfiles');
      $this->load->model('Sacyr_model');


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
      $id_cotizacion = $this->input->POST('id_cotizacion');

      $where = "WHERE c.id = $id_cotizacion";
      $dataCotizacion = $this->Sacyr_model->getInf_Cotizacion($where)->row(0);

      //print_r($ausen_json);die;
      $arr_user = array(
        'userdata' => $userinfo,
        'menus' => $allmenus,
        'perfiles' => $allperfiles,
        'pass' => $pass,
        'id_usu' => $id_usu,
        'id_cotizacion' => $id_cotizacion,
        'placa' => $dataCotizacion->placa_vh,
        'observacion' => $dataCotizacion->observacion,
        'ordenT' => $dataCotizacion->ordenT,
      );
      //abrimos la vista

      $this->load->view("Sacyr/edit_cotizacion", $arr_user);
    }
  }

  public function UpdateCotiRepuestos()
  {
    $this->load->model('Sacyr_model');
    $id = $this->input->post('id');
    $autorizado = $this->input->post('autorizado');

    if ($this->Sacyr_model->UpdateCotiRepuestos($id, $autorizado)) {
      $data = array(
        'response' => 'success'
      );
    } else {
      $data = array(
        'response' => 'error'
      );
    }

    echo json_encode($data);
  }
  public function UpdateCotiManoObra()
  {
    $this->load->model('Sacyr_model');
    $id = $this->input->post('id');
    $autorizado = $this->input->post('autorizado');

    if ($this->Sacyr_model->UpdateCotiManoObra($id, $autorizado)) {
      $data = array(
        'response' => 'success'
      );
    } else {
      $data = array(
        'response' => 'error'
      );
    }

    echo json_encode($data);
  }
  public function UpdateCotiToT()
  {
    $this->load->model('Sacyr_model');
    $id = $this->input->post('id');
    $autorizado = $this->input->post('autorizado');

    if ($this->Sacyr_model->UpdateCotiToT($id, $autorizado)) {
      $data = array(
        'response' => 'success'
      );
    } else {
      $data = array(
        'response' => 'error'
      );
    }

    echo json_encode($data);
  }
}


/* End of file Sacyr.php */
/* Location: ./application/controllers/Sacyr.php */