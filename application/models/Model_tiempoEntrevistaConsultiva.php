<?php

/**
 * Modelo Informe Tiempo Entrevistas Consultivas
 */
class Model_tiempoEntrevistaConsultiva extends CI_Model
{
    public function getInfo($startDate,$endDate){
        $sql = "SELECT bodega,
        registros_citas=COUNT(distinct a.id_cita),
        citas_marcadas=SUM(CASE WHEN hora_llegada is null then 0 else 1 end),
        citas_no_marcadas=SUM(CASE WHEN hora_llegada is not null then 0 else 1 end),
        citas_cumplidas=SUM(CASE WHEN DATEDIFF(MINUTE,fecha_cita,hora_llegada)<=5 then 1 else 0 end),
        citas_no_cumplidas=SUM(CASE WHEN hora_llegada is not null and  DATEDIFF(MINUTE,fecha_cita,hora_llegada)<=5 then 0 
                                    WHEN hora_llegada is null then 0 else 1 end),
        no_asistieron=SUM(CASE WHEN hora_llegada is null and numero_orden_taller is null then 1 else 0 end),
        ot_abiertas=SUM(CASE WHEN numero_orden_taller is null then 0 else 1 end),
        tiempo_entrevista_consultiva=CONVERT(DECIMAL(5,2),(SUM(CASE WHEN ISNULL(a.tiempo_orden,0)<40 THEN a.tiempo_orden 
        else 0 end)))/CONVERT(DECIMAL(5,2),SUM(CASE WHEN numero_orden_taller is not null and tiempo_orden<40 then 1 else 0 end))
        --entrevistas_consultivas_ot=SUM(CASE WHEN numero_orden_taller is not null and tiempo_orden<40 then 1 else 0 end)
         FROM
        (
        SELECT tc.id_cita, tc.placa, tc.fecha_hora_ini as fecha_cita, tc.bodega,
        hora_llegada= CASE WHEN ev.fecha_hora<tc.fecha_hora_ini THEN tc.fecha_hora_ini else ev.fecha_hora end,
        tc.numero_orden_taller,te.entrada as hora_orden, tiempo_orden=DATEDIFF(MINUTE,ev.fecha_hora,te.entrada)
         FROM tall_citas tc
        LEFT JOIN postv_entrada_vh_taller ev ON tc.id_cita=ev.id_cita
        LEFT JOIN tall_encabeza_orden te on tc.numero_orden_taller=te.numero
        WHERE CONVERT(DATE,tc.fecha_hora_ini) between '$startDate' and '$endDate' and tc.bodega in (8,1,11,16)
        )a
        GROUP BY bodega";
        return $this->db->query($sql);
    }

    public function getInfoByBodega($bodega,$startDate,$endDate){

        $sql="SELECT tc.id_cita, tc.placa, tc.fecha_hora_ini as fecha_cita, tc.bodega,
        hora_llegada= CASE WHEN ev.fecha_hora<tc.fecha_hora_ini THEN tc.fecha_hora_ini else ev.fecha_hora end,
        tc.numero_orden_taller,te.entrada as hora_orden, tiempo_orden=DATEDIFF(MINUTE,ev.fecha_hora,te.entrada)
         FROM tall_citas tc
        LEFT JOIN postv_entrada_vh_taller ev ON tc.id_cita=ev.id_cita
        LEFT JOIN tall_encabeza_orden te on tc.numero_orden_taller=te.numero
        WHERE CONVERT(DATE,tc.fecha_hora_ini) between '$startDate' and '$endDate' and tc.bodega = $bodega";

        return $this->db->query($sql);
    } 
}