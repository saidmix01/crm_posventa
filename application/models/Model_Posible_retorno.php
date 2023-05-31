<?php

/**
 * Modelo Posible_retorno
 * Se desarrolla con el fin de analizar los posibles retornos en los talleres
 * Power By: Sergio Ivan Galvis Esteban
 * Fecha: 11/04/2023
 */
class Model_Posible_retorno extends CI_Model
{
    public function getTiposRetornos()
    {
        $sql = "SELECT * FROM postv_posible_tipo_retorno";
        return $this->db->query($sql);
    }

    public function create_posible_retorno($dataInsert)
    {
        return $this->db->insert('postv_posible_retorno', $dataInsert);
    }

    public function getVistaPosibleRetornos($postData)
    {
        $where = "WHERE v.numero > 0";
        if ($postData['numero'] != "") {
            $numero = $postData['numero'];
            $where .= " AND v.numero = '$numero'";
        }
        if ($postData['placa'] != "") {
            $placa = $postData['placa'];
            $where .= " AND v.placa = '$placa'";
        }
        if ($postData['bodega'] != "") {
            $bodega = $postData['bodega'];
            if ($bodega != -1) {
                $where .= " AND v.bodega = '$bodega'";
            }
        }


        $sql = "SELECT ROW_NUMBER() OVER (order by v.numero desc ) as rn, v.*, b.descripcion FROM v_posibles_retornos v 
        left join bodegas b on b.bodega = v.bodega
        $where";

        $Query1 = $this->db->query($sql);
        /* print_r($Query1->result());die; */
        if ($postData['length'] == -1) {
            $postData['length'] = count($Query1->result());
        }

        $limiteFinal = (int) $postData['length'] + $postData['start'];

        $sQueryFilter = "SELECT x.*  FROM ( $sql ) as x WHERE x.rn > " . $postData['start'] . " AND x.rn <= " . $limiteFinal;

        $Query2 = $this->db->query($sQueryFilter);

        return [$Query1, $Query2];
    }

    public function getDetalleClienteRetornoByPlaca($placa)
    {
        $sql = "SELECT r.placa, r.des_modelo,t.nombres as cliente,repeticiones as cant_retornos
        FROM v_posibles_retornos r INNER JOIN v_vh_vehiculos v
        ON r.placa=v.placa
        INNER JOIN terceros t on v.nit_comprador=t.nit
        LEFT JOIN v_repeticiones rp on r.placa=rp.placa
        WHERE r.placa='$placa'";

        return $this->db->query($sql);
    }

    public function getOrdenesByPlaca($placa)
    {
        $sql = "SELECT a.*,tl.solicitud,tl.respuesta FROM 
        (
        SELECT rnk = ROW_NUMBER() OVER (PARTITION BY te.serie ORDER BY CONVERT(DATE,entrada) desc),r.placa,te.numero
        FROM v_posibles_retornos r INNER JOIN v_vh_vehiculos v
        ON r.placa=v.placa
        INNER JOIN tall_encabeza_orden te on v.codigo=te.serie
        WHERE r.placa='$placa'
        )a 
        INNER JOIN tall_lista_chequeo tl on a.numero=tl.numero
        WHERE rnk<=5
        ORDER BY a.rnk";
        return $this->db->query($sql);
    }

    public function getTecnicosRetornoByPlaca($placa)
    {
        $sql = "SELECT distinct a.*,t.nombres as tecnicos FROM 
        (
        SELECT rnk = ROW_NUMBER() OVER (PARTITION BY te.serie ORDER BY CONVERT(DATE,entrada) desc),r.placa,te.numero
        FROM v_posibles_retornos r INNER JOIN v_vh_vehiculos v
        ON r.placa=v.placa
        INNER JOIN tall_encabeza_orden te on v.codigo=te.serie
        WHERE r.placa='$placa'
        )a 
        INNER JOIN tall_detalle_orden td on a.numero=td.numero
        INNER JOIN terceros t on td.operario=t.nit
        WHERE rnk<=5 and td.clase_operacion='T'
        ORDER BY a.rnk";
        return $this->db->query($sql);
    }

    public function getRazonRetorno()
    {
        return $this->db->get('postv_posible_razon_retorno');
    }
    public function getSistemaIvn()
    {
        return $this->db->get('postv_posible_sistema_inv');
    }
    public function getPlanAccion()
    {
        return $this->db->get('postv_posible_plan_accion');
    }
    public function getCostos()
    {
        return $this->db->get('postv_posible_costos');
    }

    public function insertDefinicionRetorno($data)
    {
        return $this->db->insert('postv_posible_retorno_definido', $data);
    }

    public function getDataSoluciones($orden)
    {

        $sql = "SELECT top 1 d.*,pa.*,ra.*,si.*,tr.*,t.nombres from [dbo].[postv_posible_retorno_definido] d
        left join postv_posible_plan_accion pa on pa.id_plan = d.id_plan
        left join postv_posible_razon_retorno ra on ra.id_razon = d.id_razon
        left join postv_posible_sistema_inv si on si.id_sistema_inv = d.id_sist_inv
        left join postv_posible_tipo_retorno tr on tr.id_tipo_retorno = d.id_return
		left join terceros t on t.nit=d.usuario
        where d.numero = $orden
		order by id_return desc";

        return $this->db->query($sql);
    }

    public function getBodegas()
    {
        $sql = "SELECT bodega,descripcion FROM bodegas 
        WHERE bodega IN (1,6,7,8,9,10,11,14,16,19,21,22)";
        return $this->db->query($sql);
    }

    public function cerrarPosibleRetornoBDCById($data)
    {
        return $this->db->insert('postv_posible_retorno_bdc', $data);
    }

    /*
    * Autor: SERGIO IVAN GALVIS ESTEBAN
    * Tema: Funciones para el Informe de posibles retornos
    * Fecha:  2023-04-25 
    */

    public function entrada_Vs_retornos($year)
    {
        $sql = "SELECT et.mes, et.entradas, ISNULL(pr.posibles_retornos,0) as posibles_retornos, ISNULL(r.retornos,0) as retornos
        FROM
        (
        SELECT mes=MONTH(CONVERT(DATE,entrada)), entradas=COUNT(distinct id) FROM tall_encabeza_orden
        WHERE YEAR(CONVERT(DATE,entrada))=$year and anulada=0
        GROUP BY MONTH(CONVERT(DATE,entrada))
        )et
        LEFT JOIN
        (
        SELECT mes=MONTH(CONVERT(DATE,fecha)), posibles_retornos=COUNT(distinct numero) FROM v_posibles_retornos
        WHERE YEAR(CONVERT(DATE,fecha))=$year
        GROUP BY MONTH(CONVERT(DATE,fecha))
        )pr
        ON et.mes=pr.mes
        LEFT JOIN
        (
        SELECT mes=MONTH(CONVERT(DATE,fecha_creacion)), retornos=COUNT(distinct numero) FROM postv_posible_retorno_definido
        WHERE YEAR(CONVERT(DATE,fecha_creacion))=$year and definicion=1
        GROUP BY MONTH(CONVERT(DATE,fecha_creacion))
        )r
        ON et.mes=r.mes
        order by mes asc
        ";
        return $this->db->query($sql);
    }

    public function entrada_Vs_retornosByTecnico($year,$nit_tecnico,$name_tecnico){
        $sql="SELECT et.mes, et.entradas, ISNULL(pr.posibles_retornos,0) as posibles_retornos, ISNULL(r.retornos,0) as retornos
        FROM
        (
        SELECT mes=MONTH(CONVERT(DATE,entrada)), entradas=COUNT(distinct id) FROM tall_encabeza_orden
        WHERE YEAR(CONVERT(DATE,entrada))=$year and anulada=0 and vendedor=$nit_tecnico
        GROUP BY MONTH(CONVERT(DATE,entrada))
        )et
        LEFT JOIN
        (
        SELECT mes=MONTH(CONVERT(DATE,p.fecha)), posibles_retornos=COUNT(distinct p.numero)
        FROM v_posibles_retornos p INNER join tall_encabeza_orden te ON p.numero=te.numero
        WHERE YEAR(CONVERT(DATE,p.fecha))=$year and te.vendedor=$nit_tecnico
        GROUP BY MONTH(CONVERT(DATE,p.fecha))
        )pr
        ON et.mes=pr.mes
        LEFT JOIN
        (
        SELECT mes=MONTH(CONVERT(DATE,fecha_creacion)), retornos=COUNT(distinct numero) FROM postv_posible_retorno_definido
        WHERE YEAR(CONVERT(DATE,fecha_creacion))=$year and definicion=1 and tecnico='$name_tecnico'
        GROUP BY MONTH(CONVERT(DATE,fecha_creacion))
        )r
        ON et.mes=r.mes";

        return $this->db->query($sql);
    }

    public function entrada_Vs_retornosBySede($year,$sede){
        $sql="SELECT et.mes, et.entradas, ISNULL(pr.posibles_retornos,0) as posibles_retornos, ISNULL(r.retornos,0) as retornos
        FROM
        (
        SELECT mes=MONTH(CONVERT(DATE,entrada)), entradas=COUNT(distinct id) FROM tall_encabeza_orden
        WHERE YEAR(CONVERT(DATE,entrada))=$year and anulada=0 and bodega=$sede
        GROUP BY MONTH(CONVERT(DATE,entrada))
        )et
        LEFT JOIN
        (
        SELECT mes=MONTH(CONVERT(DATE,fecha)), posibles_retornos=COUNT(distinct numero)
        FROM v_posibles_retornos
        WHERE YEAR(CONVERT(DATE,fecha))=$year and bodega=$sede
        GROUP BY MONTH(CONVERT(DATE,fecha))
        )pr
        ON et.mes=pr.mes
        LEFT JOIN
        (
        SELECT mes=MONTH(CONVERT(DATE,fecha_creacion)), retornos=COUNT(distinct p.numero) FROM postv_posible_retorno_definido p
        INNER JOIN tall_encabeza_orden t on p.numero_retorno=t.numero
        WHERE YEAR(CONVERT(DATE,fecha_creacion))=$year and definicion=1 and t.bodega=$sede
        GROUP BY MONTH(CONVERT(DATE,fecha_creacion))
        )r
        ON et.mes=r.mes";
        return $this->db->query($sql);
    }



    public function getNameTecnico($nit){

        $sql="select nombres from terceros
        where nit=$nit";

        return $this->db->query($sql);
    }

    public function getTecnicos(){
        $sql="SELECT t.nombres, w.nit_usuario from w_sist_usuarios w
        inner join terceros t on t.nit=w.nit_usuario
        where perfil_postventa=24 and w.estado=1";
        return $this->db->query($sql);
    }
}
