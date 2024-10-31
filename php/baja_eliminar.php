<?php
/*== Almacenando datos ==*/
$baja_id_del = limpiar_cadena($_GET['baja_id_del']);

/*== Verificando si existe la baja ==*/
$check_baja = conexion();
$check_baja = $check_baja->query("SELECT baja_cantidad, producto_id FROM bajas WHERE baja_id='$baja_id_del'");

if ($check_baja->rowCount() == 1) {
    $datos = $check_baja->fetch();
    
    // Obtener la cantidad de la baja y el ID del producto
    $cantidad_baja = $datos['baja_cantidad']; 
    $producto_id = $datos['producto_id']; // Usamos el ID del producto desde bajas

    /*== Actualizar el stock del producto ==*/
    $conexion = conexion();
    $consulta_stock = $conexion->query("SELECT producto_stock FROM productos WHERE producto_id='$producto_id'");
    
    if ($consulta_stock->rowCount() == 1) {
        $producto = $consulta_stock->fetch();
        $stock_actual = $producto['producto_stock'];

        // Sumar la cantidad de la baja al stock actual
        $nuevo_stock = $stock_actual + $cantidad_baja;

        // Actualizar el stock del producto
        $actualizar_stock = $conexion->prepare("UPDATE productos SET producto_stock=:nuevo_stock WHERE producto_id=:producto_id");
        $actualizar_stock->execute([":nuevo_stock" => $nuevo_stock, ":producto_id" => $producto_id]);

        if ($actualizar_stock->rowCount() == 1) {
            /*== Eliminando la baja ==*/
            $eliminar_baja = conexion();
            $eliminar_baja = $eliminar_baja->prepare("DELETE FROM bajas WHERE baja_id=:id");
            $eliminar_baja->execute([":id" => $baja_id_del]);

            if ($eliminar_baja->rowCount() == 1) {
                echo '
                    <div class="notification is-info is-light">
                        <strong>¡PRODUCTO ELIMINADO!</strong><br>
                        EL PRODUCTO SE ELIMINÓ CON ÉXITO Y SE HA ACTUALIZADO EL STOCK.
                    </div>
                ';
            } else {
                echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrió un error inesperado!</strong><br>
                        NO SE PUDO ELIMINAR EL PRODUCTO, POR FAVOR INTÉNTELO NUEVAMENTE.
                    </div>
                ';
            }
        } else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Error al actualizar stock!</strong><br>
                    NO SE PUDO ACTUALIZAR EL STOCK DEL PRODUCTO.
                </div>
            ';
        }
        
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Error!</strong><br>
                NO SE PUDO OBTENER EL STOCK DEL PRODUCTO.
            </div>
        ';
    }
    $eliminar_baja=null;
        }   else {
    echo '
        <div class="notification is-info is-light">
            <strong>¡PRODUCTO NO ELIMINADO!</strong><br>
            EL PRODUCTO QUE INTENTA ELIMINAR NO EXISTE.
        </div>
    ';
}
$check_baja = null;
