<?php

class control_compras extends CI_Model
{
    public function getData($orden)
    {
        $sql = "SELECT numero, fecha, codigo, descripcion, cantidad, valor_unitario, valor_total, calificacion_abc,ultima_compra, ultima_venta,
        SUM(Giron) as Giron, SUM(Chevropartes) as Chevropartes, SUM(Barranca) as Barranca,
        SUM(Rosita) as Rosita, SUM(Villa) as Villa, SUM(Solochevrolet) as Solochevrolet
         FROM
        (
        SELECT DISTINCT dp.numero, CONVERT(DATE,dp.fecha) as fecha, dl.codigo, r.descripcion, dl.cantidad, dl.valor_unitario,
        valor_total=((dl.cantidad*dl.valor_unitario)-(dl.cantidad*dl.valor_unitario*dl.porc_dcto_2/100)),
        r.calificacion_abc,CONVERT(DATE,uc.ultima_compra) as ultima_compra,CONVERT(DATE,uv.ultima_venta) as ultima_venta,
        Giron=CASE WHEN st.bodega=1 then st.stock else 0 end,
        Chevropartes=CASE WHEN st.bodega=4 then st.stock else 0 end,
        Barranca=CASE WHEN st.bodega=6 then st.stock else 0 end,
        Rosita=CASE WHEN st.bodega=7 then st.stock else 0 end,
        Villa=CASE WHEN st.bodega=8 then st.stock else 0 end,
        Solochevrolet=CASE WHEN st.bodega=23 then st.stock else 0 end
        FROM documentos_ped dp INNER JOIN documentos_lin_ped dl
        ON dp.numero=dl.numero
        INNER JOIN referencias r ON dl.codigo=r.codigo
        LEFT JOIN (SELECT bodega, codigo, stock FROM v_referencias_sto_hoy)st ON dl.codigo=st.codigo
        LEFT JOIN (select codigo, MAX(fec) as ultima_compra from documentos_lin where sw=3 and cantidad_devuelta is null 
        and bodega=1 group by codigo) uc ON dl.codigo=uc.codigo
        LEFT JOIN ( select codigo, ultima_venta from (
        select rnk = ROW_NUMBER() OVER (PARTITION BY d.codigo ORDER BY convert(date,ultima_venta) desc), d.codigo,d.ultima_venta from
        (select codigo, MAX(fec) as ultima_venta from documentos_lin where sw=1 and cantidad_devuelta is null 
        group by codigo
        union
        select operacion as codigo, MAX(fec) as ultima_venta from tall_documentos_lin where sw=1 and clase_operacion='R'
        group by operacion ) d
        )e where rnk=1
        ) uv ON dl.codigo=uv.codigo
        WHERE anulado=0 and despacho is null and fecha>='20230101' and dp.sw=3 and dp.numero=$orden
        ) oc
        GROUP BY numero, fecha, codigo, descripcion, cantidad, valor_unitario, valor_total, calificacion_abc,ultima_compra, ultima_venta";
        return $this->db->query($sql);
    }
}
