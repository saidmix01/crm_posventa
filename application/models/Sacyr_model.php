<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Sacyr_model extends CI_Model
{

  // ------------------------------------------------------------------------

  public function __construct()
  {
    parent::__construct();
  }

  // ------------------------------------------------------------------------


  // ------------------------------------------------------------------------
  public function index()
  {
    // 
  }
  public function loadReport()
  {
    $query = $this->db->get('v_sacyr');
    return $query;
  }

  public function loadReportDetail($codigo)
  {
    $this->db->where('codigo', "$codigo");
    $query = $this->db->get('v_sacyr_detalle');
    return $query;
  }

  // ---------------------------------MODULO COTIZACION SACYR----------------------- //
  /* Función para optener los datos de los repuestos o items */
  public function getCotizador()
  {
    $query = $this->db->get('v_cotizaciones_sacyr');
    return $query;
  }
  /* Funcion para insertar los datos principales de la cotizacion y devolver el id de la cotizacion */
  public function insertCotizacion($data)
  {

    if ($this->db->insert('dbo.sacyr_cotizacion ', $data)) {
      return $this->db->insert_id();
    } else {
      return 0;
    }
  }
  /* Funcion para insertar los datos de repuestos en la cotizacion sacyr */
  public function insertRespuestos($idCotizacion, $data)
  {

    if (!is_numeric($data[5])) {
      $data[5] = 0;
    }


    $dataInsert = array(
      'codigo' => $data[0],
      'descripcion' => $data[1],
      'cantidad' => $data[2],
      'descuento' => $data[3],
      'valor_unidad' => str_replace('.', '', $data[4]),
      'cant_contrato' => $data[5],
      'valor_total' => str_replace('.', '', $data[10]),
      'autorizado' => $data[11],
      'id_cotizacion' => $idCotizacion,
    );

    return $this->db->insert('dbo.sacyr_cotizacion_repuestos', $dataInsert);
  }

  /* Funcion para insertar los datos de Mano de Obra en la cotizacion sacyr */
  public function insertManoObra($idCotizacion, $data)
  {

    $dataInsert = array(
      'operacion' => $data[0],
      'cant_horas' => $data[1],
      'valor_total' => str_replace('.', '', $data[3]),
      'autorizado' => $data[4],
      'id_cotizacion' => $idCotizacion,
    );
    return $this->db->insert('dbo.sacyr_cotizacion_mano_obra ', $dataInsert);
  }

  /* Funcion para insertar los datos de ToT en la cotizacion sacyr */
  public function insertToT($idCotizacion, $data)
  {

    $dataInsert = array(
      'operacion' => $data[0],
      'valor_total' => str_replace('.', '', $data[1]),
      'autorizado' => $data[2],
      'id_cotizacion' => $idCotizacion,
    );

    return $this->db->insert('dbo.sacyr_cotizacion_tot  ', $dataInsert);
  }

  /* Funcion para traer los datos principales de la cotizacion con el id y la placa */
  public function getCotizacion($id_cotizacion, $placa)
  {
    //where id = 11 and placa_vh = 'GLX583'
    $this->db->where('id', $id_cotizacion);
    $this->db->where('placa_vh', $placa);
    return $this->db->get('dbo.sacyr_cotizacion');
  }
  /* Funcion para trer los repuestos de la cotizacion */
  public function getRepuestosCoti($id_cotizacion)
  {
    $this->db->where('id_cotizacion', $id_cotizacion);
    return $this->db->get('dbo.sacyr_cotizacion_repuestos');
  }
  /* Funcion para trer los Items de Mano de Obra de la cotizacion */
  public function getManoObraCoti($id_cotizacion)
  {
    $this->db->where('id_cotizacion', $id_cotizacion);
    return $this->db->get('dbo.sacyr_cotizacion_mano_obra');
  }
  /* Funcion para trer los items ToT de la cotizacion */
  public function getToTCoti($id_cotizacion)
  {
    $this->db->where('id_cotizacion', $id_cotizacion);
    return $this->db->get('dbo.sacyr_cotizacion_tot');
  }
  /* Funcion para traer la información de la cotización */
  public function getInf_Cotizacion($where = "")
  {
    $sql = "SELECT c.*, t.nombres, DATEADD(DAY,+3,c.fecha_creacion) as plazo_edit FROM sacyr_cotizacion c
          LEFT JOIN terceros t ON t.nit = c.usuario
          $where
          ORDER BY c.fecha_creacion DESC";
    return $this->db->query($sql);
  }
  /* Función para optener los datos de los repuestos o items que no cuentan con el campo de busqueda */
  public function getRepuestosBusquedaNull()
  {
    $this->db->where('busqueda', null);
    $query = $this->db->get('v_cotizaciones_sacyr');
    return $query;
  }
  /* Funcion para agregar los campos de busqueda a los repuestos */
  public function insertfiedSearch($data)
  {
    return $this->db->insert('dbo.sacyr_repuestos_notas', $data);
  }
  /* Funcion para obtener información del repuesto */
  public function getRepuestosCodigo($codigo)
  {
    $this->db->where('codigo', $codigo);
    $query = $this->db->get('v_cotizaciones_sacyr');
    return $query;
  }

  /* Funcion para insertar el número de orden en la cotización */
  public function insertOrdenT($id, $ordenT)
  {
    $sql = "UPDATE dbo.sacyr_cotizacion
    SET ordenT = $ordenT
    WHERE id = $id";

    return  $this->db->query($sql);
  }

  /* funciones para actualizar las cotizaciónes de repuestos , mano de obra y tot*/

  public function UpdateCotiRepuestos($id,$autorizado){
    $sql ="UPDATE dbo.sacyr_cotizacion_repuestos
    SET autorizado = $autorizado
    WHERE id = $id";
    return  $this->db->query($sql);
  }
  public function UpdateCotiManoObra($id,$autorizado){
    $sql ="UPDATE dbo.sacyr_cotizacion_mano_obra
    SET autorizado = $autorizado
    WHERE id = $id";
    return  $this->db->query($sql);
  }
  public function UpdateCotiToT($id,$autorizado){
    $sql ="UPDATE dbo.sacyr_cotizacion_tot
    SET autorizado = $autorizado
    WHERE id = $id";
    return  $this->db->query($sql);
  }

  /* Funcion para obtener las cotizaciónes de sacyr enlazadas a un número de Orden */

  public function getCantCotizacionesByOrdenT($ordenT){
    $sql = "select id from sacyr_cotizacion
            where ordenT = $ordenT";
    return $this->db->query($sql);
    
  }

  public function getCotizacionByOrden($ordenT){
    $this->db->where('ordenT', $ordenT);
    return $this->db->get('sacyr_cotizacion');
  }
}

