<?php

class lamina extends CI_Model
{

    //metodo traer datos de la tabla lamina y pintura
    public function TraerDatos()
    {
        return $this->db->query("SELECT pr.id_producto,pr.nombre_producto,pr.marca_producto,pr.fecha_ingreso_producto,pr.cantidad_producto,pr.costo,pr.precio,
        md.medida,md.id_medida,
        c.color,c.descripcion,
        pv.nombre_proveedor,pv.id_proveedor,
        t.nombres
        FROM dbo.postv_productos_laminaypintura pr
        INNER JOIN dbo.postv_medidas md ON pr.fk_unidad_medida = md.id_medida
        INNER JOIN vh_colores c ON pr.fk_color = c.color
        INNER JOIN dbo.postv_proveedores_laminaypintura pv ON pr.fk_proveedor = pv.id_proveedor
        INNER JOIN terceros t ON pr.usuario = t.nit");
    }

    /*metodo para traer todos los colores de la tablas colores equipo */

    public function TraerColores()
    {

        return $this->db->query("SELECT * FROM vh_colores");
    }
    public function TraerRefrencia()
    {

        return $this->db->query("SELECT numero_orden FROM dbo.postv_registro_orden");
    }

    /*metodo para traer todos los colores de la tablas medias */

    public function TraerMedida()
    {

        return $this->db->query("SELECT * FROM dbo.postv_medidas");
    }
    /*metodo para traer todos los colores de la tablas proveedorees */

    public function TraerProveedro()
    {

        return $this->db->query("SELECT * FROM dbo.postv_proveedores_laminaypintura");
    }
    /*metodo para traer todos los productos de la tabla productos */
    public function TraerProducto()
    {

        return $this->db->query("SELECT  p.id_producto,p.nombre_producto,p.fk_color,p.cantidad_producto,p.precio,
        m.medida,
        v.descripcion
        FROM dbo.postv_productos_laminaypintura p
        INNER JOIN dbo.postv_medidas m ON p.fk_unidad_medida = m.id_medida
        INNER JOIN vh_colores v ON p.fk_color = v.color
        ");
    }
    /*tarer datos de la tabla producto segun el id */
    public function TraerP($id)
    {

        return $this->db->query("SELECT  p.id_producto,p.nombre_producto,p.fk_color,p.cantidad_producto,p.precio,
        m.medida,
        v.descripcion
        FROM dbo.postv_productos_laminaypintura p
        INNER JOIN dbo.postv_medidas m ON p.fk_unidad_medida = m.id_medida
        INNER JOIN vh_colores v ON p.fk_color = v.color WHERE p.id_producto = '" . $id . "'
        ");
    }

    /*metodo para registrar producto en la tabla productoslaminaypintura */

    public function Regsitrarproducto($producto, $marca, $color, $usuario, $fecha, $cantidad, $medida, $proveedor, $costo, $precio)
    {
        $sql = "INSERT INTO dbo.postv_productos_laminaypintura (nombre_producto,marca_producto,fk_color,usuario,fecha_ingreso_producto,cantidad_producto,fk_unidad_medida,fk_proveedor,costo,precio) 
        VALUES('" . $producto . "','" . $marca . "','" . $color . "','" . $usuario . "','" . $fecha . "','" . $cantidad . "','" . $medida . "','" . $proveedor . "','" . $costo . "','" . $precio .    "')";
        if ($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    /*metodo para eliminar registro de producto de la tabla productoslaminaypintura  */

    public function Eliminarproducto($codigo)
    {
        $sql = "DELETE FROM dbo.postv_productos_laminaypintura WHERE id_producto = '" . $codigo . "' ";
        if ($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }
    /*metodo para pintar datos en fomrulario de la tabla  dbo.postv_productos_laminaypintura  */

    public function PintarProductoEditar($codigoProducto)
    {
        $sql = "SELECT pr.id_producto,pr.nombre_producto,pr.marca_producto,pr.cantidad_producto,pr.costo,pr.precio,
        md.medida,md.id_medida,
        c.color,c.descripcion,
        pv.nombre_proveedor,pv.id_proveedor,
        t.nombres
        FROM dbo.postv_productos_laminaypintura pr
        INNER JOIN dbo.postv_medidas md ON pr.fk_unidad_medida = md.id_medida
        INNER JOIN vh_colores c ON pr.fk_color = c.color
        INNER JOIN dbo.postv_proveedores_laminaypintura pv ON pr.fk_proveedor = pv.id_proveedor
        INNER JOIN terceros t ON pr.usuario = t.nit WHERE pr.id_producto = '" . $codigoProducto . "'";
        $valor = $this->db->query($sql);
        if ($valor) {
            return $valor;
        } else {
            return false;
        }
    }

    /*metodo para editar los datos de la tabla dbo.postv_productos_laminaypintura  */
    public function EditarProductoEditar($producto, $marca, $color, $usuario, $fecha, $cantidad, $medida, $proveedor, $costo, $precio, $codigo)
    {
        $sql =  "UPDATE  dbo.postv_productos_laminaypintura SET nombre_producto = '" . $producto . "', marca_producto = '" . $marca . "',fk_color = '" . $color . "',
        usuario = '" . $usuario . "', fecha_ingreso_producto = '" . $fecha . "', cantidad_producto = '" . $cantidad . "',fk_unidad_medida = '" . $medida . "', fk_proveedor = '" . $proveedor . "',costo = '" . $costo . "', precio = '" . $precio . "'
          WHERE id_producto = '" . $codigo . "' ";
        $ejecutar =  $this->db->query($sql);
        if ($ejecutar) {
            return true;
        } else {
            return false;
        }
    }

    /*metodo para ter datos segun el numero de orden lamina y pintura */

    public function traerdatospororden($codigo)
    {

        $sql = "SELECT co.descripcion as color, t.nombres,vhv.placa FROM tall_encabeza_orden teo
        INNER JOIN terceros t ON t.nit = teo.nit
        INNER JOIN  v_vh_vehiculos vhv ON teo.serie = vhv.codigo
        INNER JOIN vh_colores co ON co.color = vhv.color 
        WHERE teo.numero = '" . $codigo . "'
        ";
        return $this->db->query($sql);
    }

    /*metdod par insertar datos en la tabla numero deorden */

    public function listarorden($numeroorden, $presupuesto, $estado)
    {
        $sql = "IF NOT EXISTS (SELECT numero_orden FROM dbo.postv_registro_orden WHERE numero_orden = '" . $numeroorden . "')
        INSERT INTO dbo.postv_registro_orden  (numero_orden,presupuesto,estado) VALUES ('" . $numeroorden . "','" . $presupuesto . "','" . $estado . "')";
        if ($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    /*metodo para registar insumos requierdos en la tabla dbo.postv_producto_requerido */

    public function agrgarproductosrequerido($orden, $idproducto, $catidad, $requerido, $color, $medida, $disponible, $precio, $fecha)
    {
        $sql = "INSERT INTO dbo.postv_producto_requerido (fk_numero_orden,id_producto,cantidad_requerida,solicitante,color,medida,disponibles,precio,fecharegistro) VALUES ('" . $orden . "', '" . $idproducto . "', '" . $catidad . "','" . $requerido . "','" . $color . "', '" . $medida . "', '" . $disponible . "', '" . $precio . "', '" . $fecha . "')";
        if ($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    /*metodo para visualizar prodtuctos requeridos en la tabla dbo.postv_producto_requerido  */

    public function traerlistaproductordeorden($numeroorden)
    {

        $sql = "SELECT ro.id_registro_orde, ro.numero_orden,ro.presupuesto,ro.estado,
        pr.fk_numero_orden,pr.id_producto,pr.cantidad_requerida,pr.solicitante,pr.precio,pr.medida,pr.color,
        pl.nombre_producto
        
        FROM dbo.postv_registro_orden ro
        
        INNER JOIN dbo.postv_producto_requerido pr ON ro.numero_orden = pr.fk_numero_orden
        INNER JOIN dbo.postv_productos_laminaypintura pl  ON  pl.id_producto = pr.id_producto
        
        WHERE ro.numero_orden = '" . $numeroorden . "'
         ";
        return $this->db->query($sql);
    }

    /*metodo para trer el valor todal  */
    public function valortotal($orden)
    {
        $sql = "SELECT SUM (CAST(precio AS int)) precio FROM dbo.postv_producto_requerido WHERE fk_numero_orden = '" . $orden . "'";
        return $this->db->query($sql);
    }

    /*metodo para tarer valor total de color y consumibles */

    public function valortotalcolorconsumible($orden)
    {
        $sql = "SELECT SUM (cast(cantidad_requerida as int)) total FROM dbo.postv_producto_requerido where fk_numero_orden = '" . $orden . "'  group by medida
        ";
        return $this->db->query($sql);
    }
}
