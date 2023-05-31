<?php

class model_orden_salida extends CI_Model
{
    public function get_orden_salida_by_placa($placa)
    {
        $sql = "SELECT e.*, o.* FROM v_base__postv_encuesta_nps e
        left join postv_taller_jefe_ord_salida o on o.numero = e.numero_orden
        WHERE e.placa = '$placa'";
        return $this->db->query($sql);
    }

    public function insert_orden_salida($data)
    {

        return $this->db->insert('postv_taller_jefe_ord_salida', $data);
    }
    /* Update fecha_hora_entrega_real de tall_encabeza_orden */
    public function update_tall_encabeza_orden($numero, $fecha)
    {

        $sql = "update tall_encabeza_orden
        set fecha_hora_entrega_real = '$fecha'
        where numero = $numero";

        return $this->db->query($sql);
    }

    public function encuesta_by_placa($placa)
    {
        $sql = "SELECT top 1 v.nit_comprador, t.nombres,t.mail,t.celular, e.*, o.*, v.des_marca, v.descripcion as desc_modelo, v.des_color FROM v_base__postv_encuesta_nps e
        left join postv_taller_jefe_ord_salida o on o.numero = e.numero_orden
        left join v_vh_vehiculos v on v.placa = e.placa
        left join terceros t on t.nit = v.nit_comprador
        WHERE e.placa = '$placa' and o.encuesta is null
		ORDER BY o.fecha_solicitud desc";

        return $this->db->query($sql);
    }

    public function executeAlter($sql)
    {
        return $this->db->query($sql);
    }

    public function updateTerceros($nit, $data)
    {
        $this->db->where('nit', $nit);
        return $this->db->update('terceros', $data);
    }

    public function insert_encuesta_satisfaccion_qr($data)
    {
        return $this->db->insert('postv_encuesta_satisfaccion_qr', $data);
    }

    public function select_orden_salida($numero)
    {
        $this->db->where('numero', $numero);
        return $this->db->get('postv_taller_jefe_ord_salida');
    }

    public function update_orden_taller($numero, $data)
    {
        $this->db->where('numero', $numero);
        $this->db->update('postv_taller_jefe_ord_salida', $data,);
        return $this->db->affected_rows();
    }

    public function get_orden_salida_vh_porteria($bodega)
    {
        $sql = "SELECT * FROM [dbo].[postv_taller_jefe_ord_salida]
        where bodega_o in ($bodega) and salida = 1 and fecha_salida is null";
        
        return $this->db->query($sql);
    }

    public function select_orden_salida_by_numero($numero)
    {
        $sql = "SELECT e.*, o.* FROM v_base__postv_encuesta_nps e
        left join postv_taller_jefe_ord_salida o on o.numero = e.numero_orden
        WHERE e.numero_orden = $numero";

        return $this->db->query($sql);
    }

}
