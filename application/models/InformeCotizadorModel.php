<?php

class InformeCotizadorModel extends CI_Model
{
    public function getAllCountCotizaciones($desde, $hasta, $bodega)
    {
        $sql = "SELECT 
        total_cotizaciones=(SELECT COUNT(id_cotizacion) FROM postv_cotizacion_contact where fecha_creacion between '$desde' and '$hasta' and bodega in ($bodega))
        ,env_sin_agenda=(SELECT COUNT(id_cotizacion) FROM postv_cotizacion_contact where estado=0 and
                         fecha_creacion between '$desde' and '$hasta' and bodega in ($bodega))
        ,env_agendadas=(SELECT COUNT(id_cotizacion) FROM postv_cotizacion_contact where estado=1 and
                         fecha_creacion between '$desde' and '$hasta' and bodega in ($bodega))
        , asistidas= (SELECT COUNT(id_cotizacion) FROM
        (
        SELECT rnk = ROW_NUMBER() OVER (PARTITION BY id_cotizacion ORDER BY razon2 asc),*
        FROM (SELECT DISTINCT 
        id_cotizacion,a.placa,CONVERT(date, fecha_agenda) as fecha_Agenda, convert(date,c.fecha_hora_creacion) as fecha_crea_cita, c.estado_cita,
        CONVERT(date,c.fecha_hora_ini) as fecha_cita,CONVERT(date,e.fecha) as fecha_apertura_ot,e.numero,razon2
        FROM postv_cotizacion_contact a INNER JoIN tall_citas c
        ON a.placa=c.placa and CONVERT(date, a.fecha_Agenda)=CONVERT(date,c.fecha_hora_creacion)
        INNER JOIN v_vh_vehiculos v ON a.placa=v.placa
        INNER JOIN tall_encabeza_orden e on e.serie=v.codigo and CONVERT(date,e.fecha)=CONVERT(date,c.fecha_hora_ini)
        where a.estado=1 AND e.anulada=0 and CONVERT(date,e.fecha) between '$desde' and '$hasta' and e.bodega in ($bodega)
        )ct)t where rnk=1)";

        return $this->db->query($sql);
    }

    public function getInfoValorTotalCotizaciones($desde,$hasta,$bodega)
    {
        $sql = "SELECT sum(isnull(repuestos,0)+isnull(mano_obra,0)) as total_agendado,
        SUM(isnull(rptos,0)+isnull(mo,0)) as total_facturado, sum(isnull(items_cotizados,0)) as items_cotizados, sum(isnull(items_facturados,0)) as items_facturados
         FROM (
        SELECT * FROM
        (
        SELECT rnk = ROW_NUMBER() OVER (PARTITION BY id_cotizacion ORDER BY razon2 asc),*
        FROM (
        SELECT DISTINCT 
        id_cotizacion,a.placa,CONVERT(date, fecha_agenda) as fecha_Agenda, convert(date,c.fecha_hora_creacion) as fecha_crea_cita, c.estado_cita,
        CONVERT(date,c.fecha_hora_ini) as fecha_cita,CONVERT(date,e.fecha) as fecha_apertura_ot,e.numero,razon2,e.bodega
        FROM postv_cotizacion_contact a INNER JoIN tall_citas c
        ON a.placa=c.placa and CONVERT(date, a.fecha_Agenda)=CONVERT(date,c.fecha_hora_creacion)
        INNER JOIN v_vh_vehiculos v ON a.placa=v.placa
        INNER JOIN tall_encabeza_orden e on e.serie=v.codigo and CONVERT(date,e.fecha)=CONVERT(date,c.fecha_hora_ini)
        where a.estado=1 AND e.anulada=0 and CONVERT(date,e.fecha) between '$desde' and '$hasta' and e.bodega in ($bodega)
        )ct
        )t
        where rnk=1)cm
        LEFT JOIN (select id_cotizacion, SUM(valor) as repuestos from postv_cotizacion_repuestos where estado=1 group by id_cotizacion) m on cm.id_cotizacion=m.id_cotizacion
        LEFT JOIN (select id_cotizacion, SUM(valor) as mano_obra from postv_cotizacion_mtto where estado=1 group by id_cotizacion) mo on cm.id_cotizacion=mo.id_cotizacion
        LEFT JOIN (select id_cotizacion, COUNT(codigo) as items_cotizados from postv_cotizacion_repuestos where estado=1 group by id_cotizacion) ic on cm.id_cotizacion=ic.id_cotizacion
        LEFT JOIN (select numero_orden, convert(int,SUM(venta_rptos+(venta_rptos*0.19))) as rptos from v_informe_tecnico group by numero_orden) d on cm.numero=d.numero_orden
        LEFT JOIN (select numero_orden, convert(int,SUM(Venta_mano_obra+(Venta_mano_obra*0.19))) as mo  from v_informe_tecnico group by numero_orden) d1 on cm.numero=d1.numero_orden
        LEFT JOIN (select numero_orden, COUNT (operacion) as items_facturados from v_informe_tecnico it inner join tall_encabeza_orden te on it.numero_orden=te.numero and te.anulada=0
        where venta_rptos>0 group by numero_orden)as ir on cm.numero=ir.numero_orden";
        return $this->db->query($sql);

    }

    public function facturadoToCotizacion($desde,$hasta,$bodega){
        $sql= "SELECT cm.id_cotizacion, cm.numero,d.operacion, isnull(d.valor_rep,0) as valor_facturado,isnull(m.codigo,'No cotizado') as codigo,
        isnull(m.valor,0) as valor_cotizado
         FROM (
        SELECT * FROM
        (
        SELECT rnk = ROW_NUMBER() OVER (PARTITION BY id_cotizacion ORDER BY razon2 asc),*
        FROM (
        SELECT DISTINCT 
        id_cotizacion,a.placa,CONVERT(date, fecha_agenda) as fecha_Agenda, convert(date,c.fecha_hora_creacion) as fecha_crea_cita, c.estado_cita,
        CONVERT(date,c.fecha_hora_ini) as fecha_cita,CONVERT(date,e.fecha) as fecha_apertura_ot,e.numero,razon2,e.bodega
        FROM postv_cotizacion_contact a INNER JoIN tall_citas c
        ON a.placa=c.placa and CONVERT(date, a.fecha_Agenda)=CONVERT(date,c.fecha_hora_creacion)
        INNER JOIN v_vh_vehiculos v ON a.placa=v.placa
        INNER JOIN tall_encabeza_orden e on e.serie=v.codigo and CONVERT(date,e.fecha)=CONVERT(date,c.fecha_hora_ini)
        where a.estado=1 AND e.anulada=0 and CONVERT(date,e.fecha) between '$desde' and '$hasta' and e.bodega in ($bodega)
        )ct
        )t
        where rnk=1)cm
        INNER JOIN (select numero_orden,operacion,convert(int,SUM(venta_rptos+(venta_rptos*0.19))) as valor_rep from v_informe_tecnico group by numero_orden, operacion) d 
                    on cm.numero=d.numero_orden 
        LEFT JOIN (select id_cotizacion, codigo,sum(valor) as valor from postv_cotizacion_repuestos where estado=1
        group by id_cotizacion, codigo) m on cm.id_cotizacion=m.id_cotizacion and m.codigo=d.operacion
        
        UNION
        
        SELECT cm.id_cotizacion, cm.numero,operacion, mo as valor_facturado,isnull(mo.codigo,'No cotizado') as codigo, isnull(mo.valor_mano_obra,0) as valor_cotizado
         FROM (
        SELECT * FROM
        (
        SELECT rnk = ROW_NUMBER() OVER (PARTITION BY id_cotizacion ORDER BY razon2 asc),*
        FROM (
        SELECT DISTINCT 
        id_cotizacion,a.placa,CONVERT(date, fecha_agenda) as fecha_Agenda, convert(date,c.fecha_hora_creacion) as fecha_crea_cita, c.estado_cita,
        CONVERT(date,c.fecha_hora_ini) as fecha_cita,CONVERT(date,e.fecha) as fecha_apertura_ot,e.numero,razon2,e.bodega
        FROM postv_cotizacion_contact a INNER JoIN tall_citas c
        ON a.placa=c.placa and CONVERT(date, a.fecha_Agenda)=CONVERT(date,c.fecha_hora_creacion)
        INNER JOIN v_vh_vehiculos v ON a.placa=v.placa
        INNER JOIN tall_encabeza_orden e on e.serie=v.codigo and CONVERT(date,e.fecha)=CONVERT(date,c.fecha_hora_ini)
        where a.estado=1 AND e.anulada=0 and CONVERT(date,e.fecha) between '$desde' and '$hasta' and e.bodega in ($bodega)
        )ct
        )t
        where rnk=1)cm
        INNER JOIN (select numero_orden,operacion='mano de obra', convert(int,SUM(Venta_mano_obra+(Venta_mano_obra*0.19))) as mo  from v_informe_tecnico where bodega in (1,6,7,8)
        group by numero_orden) d1 on cm.numero=d1.numero_orden 
        LEFT JOIN (select id_cotizacion,codigo='Mano de obra' ,SUM(valor) as valor_mano_obra from postv_cotizacion_mtto where estado=1 group by id_cotizacion) mo on cm.id_cotizacion=mo.id_cotizacion and mo.codigo=d1.operacion";
        return $this->db->query($sql);
    }

    
    public function cotizacionToFacturado($desde,$hasta,$bodega){
        $sql= "SELECT cm.id_cotizacion, cm.numero,m.codigo,m.valor as valor_cotizado,isnull(d.operacion,'No facturada') as operacion, isnull(d.valor_rep,0) as valor_facturado
        FROM (
       SELECT * FROM
       (
       SELECT rnk = ROW_NUMBER() OVER (PARTITION BY id_cotizacion ORDER BY razon2 asc),*
       FROM (
       SELECT DISTINCT 
       id_cotizacion,a.placa,CONVERT(date, fecha_agenda) as fecha_Agenda, convert(date,c.fecha_hora_creacion) as fecha_crea_cita, c.estado_cita,
       CONVERT(date,c.fecha_hora_ini) as fecha_cita,CONVERT(date,e.fecha) as fecha_apertura_ot,e.numero,razon2,e.bodega
       FROM postv_cotizacion_contact a INNER JoIN tall_citas c
       ON a.placa=c.placa and CONVERT(date, a.fecha_Agenda)=CONVERT(date,c.fecha_hora_creacion)
       INNER JOIN v_vh_vehiculos v ON a.placa=v.placa
       INNER JOIN tall_encabeza_orden e on e.serie=v.codigo and CONVERT(date,e.fecha)=CONVERT(date,c.fecha_hora_ini)
       where a.estado=1 AND e.anulada=0 and CONVERT(date,e.fecha) between '$desde' and '$hasta' and e.bodega in ($bodega)
       )ct
       )t
       where rnk=1)cm
       INNER JOIN (select id_cotizacion, codigo,sum(valor) as valor from postv_cotizacion_repuestos where estado=1 group by id_cotizacion, codigo) m on cm.id_cotizacion=m.id_cotizacion
       LEFT JOIN (select numero_orden,operacion,convert(int,SUM(venta_rptos+(venta_rptos*0.19))) as valor_rep from v_informe_tecnico group by numero_orden, operacion) d 
                   on cm.numero=d.numero_orden and m.codigo=d.operacion
       
       
       
       UNION
       
       SELECT cm.id_cotizacion, cm.numero,mO.codigo,mo.valor_mano_obra as valor_cotizado,isnull(d1.operacion,'No facturada') as operacion, isnull(d1.mo,0) as valor_facturado
        FROM (
       SELECT * FROM
       (
       SELECT rnk = ROW_NUMBER() OVER (PARTITION BY id_cotizacion ORDER BY razon2 asc),*
       FROM (
       SELECT DISTINCT 
       id_cotizacion,a.placa,CONVERT(date, fecha_agenda) as fecha_Agenda, convert(date,c.fecha_hora_creacion) as fecha_crea_cita, c.estado_cita,
       CONVERT(date,c.fecha_hora_ini) as fecha_cita,CONVERT(date,e.fecha) as fecha_apertura_ot,e.numero,razon2,e.bodega
       FROM postv_cotizacion_contact a INNER JoIN tall_citas c
       ON a.placa=c.placa and CONVERT(date, a.fecha_Agenda)=CONVERT(date,c.fecha_hora_creacion)
       INNER JOIN v_vh_vehiculos v ON a.placa=v.placa
       INNER JOIN tall_encabeza_orden e on e.serie=v.codigo and CONVERT(date,e.fecha)=CONVERT(date,c.fecha_hora_ini)
       where a.estado=1 AND e.anulada=0 and CONVERT(date,e.fecha) between '$desde' and '$hasta' and e.bodega in ($bodega)
       )ct
       )t
       where rnk=1)cm
       INNER JOIN (select id_cotizacion,codigo='Mano de obra' ,SUM(valor) as valor_mano_obra from postv_cotizacion_mtto where estado=1 group by id_cotizacion) mo on cm.id_cotizacion=mo.id_cotizacion
       LEFT JOIN (select numero_orden,operacion='mano de obra', convert(int,SUM(Venta_mano_obra+(Venta_mano_obra*0.19))) as mo  from v_informe_tecnico where bodega in (1,6,7,8)
       group by numero_orden) d1 on cm.numero=d1.numero_orden  and mo.codigo=d1.operacion";
        return $this->db->query($sql);
    }
}
