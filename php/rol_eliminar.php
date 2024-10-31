<?php
    $rol_id_del=limpiar_cadena($_GET['rol_id_del']);

    $check_rol=conexion();
    $check_rol=$check_rol->query("SELECT *
    FROM roles WHERE id_rol='$rol_id_del'");

if ($check_rol->rowCount()==1) {
    $datos=$check_rol->fetch();

    $eliminar_rol=conexion();
    $eliminar_rol=$eliminar_rol->prepare("DELETE FROM roles WHERE id_rol=:id");
    $eliminar_rol->execute([":id"=>$rol_id_del]);

    if($eliminar_rol->rowCount()==1){
        echo '
            <div class="notification is-info is-light">
                <strong>¡PRODUCTO ELIMINADO!</strong><br>
                 EL ROL SE ELIMINO CON EXITO
            </div>
        '; 
    }else{ 
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                NO SE PUDO ELIMINAR EL ROL, POR FAVOR INTENTELO NUEVAMENTE
            </div>
        ';
    }
    $eliminar_rol=null;
}else {
    echo '
                <div class="notification is-info is-light">
                    <strong>¡PRODUCTO NO ELIMINADO!</strong><br>
                    EL ROL QUE INTENTA ELIMINAR NO EXISTE
                </div>
            ';
}
$check_rol=null;