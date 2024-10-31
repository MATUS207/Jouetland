<?php
$servername = "localhost";
$username = "root";
$password = "superadministrador";
$dbname = "bdjouetland";
 


$conn = new mysqli($servername, $username, $password, $dbname);
 
// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}   



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto_id = intval($_POST["producto_nombre"]);
    $cantidad_baja = intval($_POST["baja_cantidad"]);
    $motivo = $conn->real_escape_string($_POST["baja_motivo"]);
    $descripcion = $conn->real_escape_string($_POST["baja_descripcion"]);

    // Obtener información del producto
    $sql_info_producto = "SELECT producto_id, producto_nombre, producto_stock FROM productos WHERE producto_id = $producto_id";
    $result_info_producto = $conn->query($sql_info_producto);
 
    if ($result_info_producto->num_rows > 0) {
        $row_info_producto = $result_info_producto->fetch_assoc();
        $nombre_producto = $row_info_producto["producto_nombre"];
        $cantidad_actual = $row_info_producto["producto_stock"];

        if ($cantidad_actual >= $cantidad_baja) {
            // Actualizar la cantidad del producto
            $nueva_cantidad = $cantidad_actual - $cantidad_baja;
            $sql_update = "UPDATE productos SET producto_stock = $nueva_cantidad WHERE producto_id = $producto_id";
            
            if ($conn->query($sql_update) === TRUE) {
                // Registrar la baja
                $sql_insert = "INSERT INTO bajas (producto_id, baja_nombre, baja_cantidad, baja_motivo, baja_descripcion) VALUES ($producto_id, '$nombre_producto', $cantidad_baja, '$motivo','$descripcion')";
                
                if ($conn->query($sql_insert) === TRUE) {
                    echo '
                    <div class="notification is-info is-light">
                        <strong>¡PRODUCTO ACTUALIZADO!</strong><br>
                        BAJA DEL PRODUCTO REGISTRADO EXITOSAMENTE
                    </div>
                ';
                 
                } else {
                    echo "Error al registrar la baja: " . $conn->error;
                }
            } else {
                echo "Error al actualizar el producto: " . $conn->error;
            }
        } else {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                NO HAY SUFICIENTE DANTIDAD DE PRODUCTOS PARA DAR DE BAJA
            </div>
        ';
        
        }
    } else {
        echo "Producto no encontrado.";
    }
}






