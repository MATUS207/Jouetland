<?php
    $provee_id_del=limpiar_cadena($_GET['provee_id_del']);

    $check_producto=conexion();
$check_producto=$check_producto->query("SELECT *
FROM proveedor WHERE proveedor_id='$provee_id_del'");

if ($check_producto->rowCount()==1) {
    $datos=$check_producto->fetch();

    $eliminar_proveedor=conexion();
    $eliminar_proveedor=$eliminar_proveedor->prepare("DELETE FROM proveedor WHERE proveedor_id=:id");
    $eliminar_proveedor->execute([":id"=>$provee_id_del]);

    if($eliminar_proveedor->rowCount()==1){
        echo '
            <div class="notification is-info is-light">
                <strong>¡PRODUCTO ELIMINADO!</strong><br>
                 EL PROVEEDOR SE ELIMINO CON EXITO
            </div>
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                NO SE PUDO ELIMINAR EL PROVEEDOR, POR FAVOR INTENTELO NUEVAMENTE
            </div>
        ';
    }
    $eliminar_proveedor=null;
}else {
    echo '
                <div class="notification is-info is-light">
                    <strong>¡PRODUCTO NO ELIMINADO!</strong><br>
                    EL PROVEEDOR QUE INTENTA ELIMINAR NO EXISTE
                </div>
            ';
}


$check_proveedor=null;