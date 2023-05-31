<?php

class Productividad_Tecnicos extends CI_Model
{
    public function getData($year,$month,$patio)
    {
        $sql = "SELECT tt.nit, t.nombres, tt.patio,
        isnull(horas_cliente,0) as horas_cliente,
        isnull(horas_garantia,0) as horas_garantia,
        ISNULL(horas_servicio,0) as horas_servicio,
        ISNULL(horas_interno,0) as horas_interno,
        total_horas=isnull(horas_cliente,0)+isnull(horas_garantia,0)+ISNULL(horas_servicio,0)+ISNULL(horas_interno,0),
        horas_disp=(select sum(horas_produccion) from v_cod_tall_calendario where ano=$year and mes=$month and dias_habiles = 1)
        FROM tall_operarios tt INNER JOIN tall_operarios_intranet ti
        ON tt.nit=ti.nit
        INNER JOIN terceros t
        ON tt.nit=t.nit
        LEFT OUTER JOIN (SELECT Año, mes,operario, SUM(horas) as horas_cliente FROM v_informe_tecnico WHERE clase_trabajo='C' GROUP BY Año, Mes, operario) hc
        ON tt.nit=hc.operario
        LEFT OUTER JOIN (SELECT Año, mes,operario, SUM(horas) as horas_garantia FROM v_informe_tecnico WHERE clase_trabajo='G' GROUP BY Año, Mes, operario) hg
        ON tt.nit=hg.operario and hc.Año=hg.Año and hc.Mes=hg.Mes
        LEFT OUTER JOIN (SELECT Año, mes,operario, SUM(horas) as horas_servicio FROM v_horas_internas WHERE cliente=102 GROUP BY Año, Mes, operario) hs
        ON tt.nit=hs.operario and hc.Año=hs.Año and hc.Mes=hs.Mes
        LEFT OUTER JOIN (SELECT Año, mes,operario, SUM(horas) as horas_interno FROM v_horas_internas WHERE cliente<>102 GROUP BY Año, Mes, operario) hi
        ON tt.nit=hi.operario and hc.Año=hi.Año and hi.Mes=hg.Mes
        WHERE $patio and hc.Año=$year and hc.Mes=$month
        order by tt.patio asc, t.nombres asc
        ";
        return $this->db->query($sql);
    }
    public function getDataAll($year,$month,$patio)
    {
        $sql = "SELECT tt.nit, t.nombres, tt.patio,
        isnull(sum(horas_cliente),0) as horas_cliente,
        isnull(sum(horas_garantia),0) as horas_garantia,
        ISNULL(sum(horas_servicio),0) as horas_servicio,
        ISNULL(sum(horas_interno),0) as horas_interno,
        total_horas=SUM(isnull(horas_cliente,0))+SUM(isnull(horas_garantia,0))+SUM(ISNULL(horas_servicio,0))+SUM(ISNULL(horas_interno,0)),
        horas_disp=(select sum(horas_produccion) from v_cod_tall_calendario where ano=$year and mes<=$month and dias_habiles = 1)
        FROM tall_operarios tt INNER JOIN tall_operarios_intranet ti
        ON tt.nit=ti.nit
        INNER JOIN terceros t
        ON tt.nit=t.nit
        LEFT OUTER JOIN (SELECT Año, mes,operario, SUM(horas) as horas_cliente FROM v_informe_tecnico WHERE clase_trabajo='C' GROUP BY Año, Mes, operario) hc
        ON tt.nit=hc.operario
        LEFT OUTER JOIN (SELECT Año, mes,operario, SUM(horas) as horas_garantia FROM v_informe_tecnico WHERE clase_trabajo='G' GROUP BY Año, Mes, operario) hg
        ON tt.nit=hg.operario and hc.Año=hg.Año and hc.Mes=hg.Mes
        LEFT OUTER JOIN (SELECT Año, mes,operario, SUM(horas) as horas_servicio FROM v_horas_internas WHERE cliente=102 GROUP BY Año, Mes, operario) hs
        ON tt.nit=hs.operario and hc.Año=hs.Año and hc.Mes=hs.Mes
        LEFT OUTER JOIN (SELECT Año, mes,operario, SUM(horas) as horas_interno FROM v_horas_internas WHERE cliente<>102 GROUP BY Año, Mes, operario) hi
        ON tt.nit=hi.operario and hc.Año=hi.Año and hi.Mes=hg.Mes
        WHERE $patio and hc.Año=$year and hc.Mes<=$month
        GROUP BY tt.nit, t.nombres, tt.patio
        order by tt.patio asc, t.nombres asc";
        return $this->db->query($sql);
    }
}
