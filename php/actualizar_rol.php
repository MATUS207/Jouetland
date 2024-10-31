<?php
    require_once "main.php";

    /*== Almacenando id ==*/
    $id = limpiar_cadena($_POST['id_rol']);
    
    /*== Verificando rol ==*/
    $check_rol = conexion();
    $check_rol = $check_rol->query("SELECT * FROM roles WHERE id_rol='$id'");

    if ($check_rol->rowCount() <= 0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                EL ROL NO EXISTE EN EL SISTEMA
            </div>
        ';
        exit();
    } else {
        $datos = $check_rol->fetch();
    }
    $check_rol = null;

    $nombre = limpiar_cadena($_POST['rol_nombre']);

    /*== Validación del nombre del rol ==*/
    if ($nombre == "") {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,70}", $nombre)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                EL NOMBRE DEL ROL NO COINCIDE CON EL FORMATO SOLICITADO
            </div>
        ';
        exit();
    }

    /*== Verificando duplicados ==*/
    if ($nombre != $datos['rol_nombre']) {
        $check_rol = conexion();
        $check_rol = $check_rol->query("SELECT rol_nombre FROM roles WHERE rol_nombre='$nombre'");
        if ($check_rol->rowCount() > 0) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    EL NOMBRE DEL ROL YA SE ENCUENTRA REGISTRADO
                </div>
            ';
            exit();
        }
        $check_rol = null;
    }

    /*== Actualizando datos ==*/
    $actualizar_rol = conexion();
    $actualizar_rol = $actualizar_rol->prepare("UPDATE roles SET rol_nombre=:nombre WHERE id_rol=:id");

    $marcadores = [
        ":nombre" => $nombre,
        ":id" => $id
    ];

    if ($actualizar_rol->execute($marcadores)) {
        echo '
            <div class="notification is-info is-light">
                <strong>¡ROL ACTUALIZADO!</strong><br>
                EL ROL SE ACTUALIZÓ CON ÉXITO
            </div>
        ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                NO SE PUDO ACTUALIZAR EL ROL
            </div>
        ';
    }
    $actualizar_rol = null;
?>
