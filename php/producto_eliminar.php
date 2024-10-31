<?php 
$product_id_del = limpiar_cadena($_GET['product_id_del']);

/*== Verificando si existe el producto ==*/
$check_producto = conexion();
$check_producto = $check_producto->query("SELECT * FROM productos WHERE producto_id='$product_id_del'");

if ($check_producto->rowCount() == 1) {
    $datos = $check_producto->fetch();
    
    /*== Verificar si el producto tiene bajas asociadas ==*/
    $check_baja = conexion();
    $check_baja = $check_baja->query("SELECT baja_id FROM bajas WHERE producto_id='$product_id_del' LIMIT 1");

    if ($check_baja->rowCount() > 0) {
        echo '
            <div class="notification is-info is-light">
                <strong>¡Eliminación no permitida!</strong><br>
                NO SE PUEDE ELIMINAR ESTE PRODUCTO YA QUE TIENE BAJAS ASOCIADAS.
            </div>
        ';
    } else {
        /*== Proceder con la eliminación del producto ==*/
        $eliminar_producto = conexion();
        $eliminar_producto = $eliminar_producto->prepare("DELETE FROM productos WHERE producto_id=:id");
        $eliminar_producto->execute([":id" => $product_id_del]);

        if ($eliminar_producto->rowCount() == 1) {
            if (is_file("./img/productos/" . $datos['producto_foto'])) {
                chmod("./img/productos/" . $datos['producto_foto'], 0777);
                unlink("./img/productos/" . $datos['producto_foto']);
            }
            echo '
                <div class="notification is-info is-light">
                    <strong>¡PRODUCTO ELIMINADO!</strong><br>
                    EL PRODUCTO SE ELIMINÓ CON ÉXITO.
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
        $eliminar_producto = null;
    }
    $check_baja = null;
} else {
    echo '
        <div class="notification is-info is-light">
            <strong>¡PRODUCTO NO ENCONTRADO!</strong><br>
            EL PRODUCTO QUE INTENTA ELIMINAR NO EXISTE.
        </div>
    ';
}

$check_producto = null;





