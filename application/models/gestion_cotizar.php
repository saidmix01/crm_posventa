<?php

/**
 * Modelo de Gestión del modulo Cotizacion
 */
class gestion_cotizar extends CI_Model
{
    public function insertAdicionalRepuestos($data)
    {
        return $this->db->insert('dbo.postv_reptos_adicionales', $data);
    }

    public function insertAdicionalManoObra($data)
    {
        return $this->db->insert('dbo.postv_mo_adicionales', $data);
    }

    public function getAdicionalManoObra($operacion, $adicional, $clase)
    {
        $sql = "SELECT COUNT(*) as cantidad from dbo.postv_mo_adicionales
        where operacion = '$operacion' and adicional = '$adicional' and clase = '$clase'";
        return $this->db->query($sql);
    }

    public function getAdicionalRepuestos($codigo, $adicional, $clase)
    {
        $sql = "SELECT COUNT(*) AS cantidad from dbo.postv_reptos_adicionales
        where codigo = '$codigo' and adicional = '$adicional' and clase = '$clase'";
        return $this->db->query($sql);
    }

    public function getNameAdicional()
    {
        $sql = "SELECT distinct (adicional) as adicional from dbo.postv_mo_adicionales
        order by adicional asc";
        return $this->db->query($sql);
    }

    public function AllAdicionalReptos($where)
    {
        $sql = "SELECT * from dbo.postv_reptos_adicionales
                $where
                order by adicional asc, clase asc";
        return $this->db->query($sql);
    }
    public function AllAdicionalManoObra($where)
    {
        $sql = "SELECT * from dbo.postv_mo_adicionales
                $where
                order by adicional asc, clase asc";
        return $this->db->query($sql);
    }

    /* Autor: Sergio Galvis
	Fecha: 29/04/2023
	Asunto: Funciónes gestion adicionales */

    public function getClasesAdicionales()
    {
        $sql = "SELECT distinct v.clase, c.descripcion 
		from postv_mo_adicionales v left join referencias_cla c
		on v.clase=c.clase
		where c.descripcion is not null and v.clase not in ('COLORADO','TRAVERSE','TRB')
		ORDER BY c.descripcion";
        return $this->db->query($sql);
    }

    public function checkCodigoRepto($codigo)
    {
        $sql = "SELECT rf.codigo, al.codigo as alterno FROM referencias rf
        left join referencias_alt al on rf.codigo=al.alterno
        WHERE rf.codigo = '$codigo'";

        return $this->db->query($sql);
    }

    public function getAdicionalesDisponibles()
    {
        $sql = "select * from dbo.postv_adicionales_name
        order by adicional asc";
        return $this->db->query($sql);
    }

    public function exists_Adicional($data)
    {
        return $this->db->get_where('postv_adicionales_name', $data);
    }

    public function insertAdicionalName($data)
    {
        return $this->db->insert('postv_adicionales_name', $data);
    }

    public function deleteItemAdicionalR($seq,$codigo,$adicional,$user)
    {
        $sql = "delete dbo.postv_reptos_adicionales
        where seq = $seq and codigo = '$codigo' and adicional = '$adicional' and usuario = $user";
        
        return $this->db->query($sql);
    }

    public function deleteItemAdicionalM($id,$operacion,$adicional,$user)
    {
        $sql = "delete dbo.postv_mo_adicionales
        where id = $id  and operacion = '$operacion' and adicional = '$adicional' and usuario = $user";
        
        return $this->db->query($sql);
    }
}
