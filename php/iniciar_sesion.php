<?php

/*== Almacenando datos ==*/
$usuario = limpiar_cadena($_POST['login_usuario']);
$clave = limpiar_cadena($_POST['login_clave']);

/*== Verificando campos obligatorios ==*/
if ($usuario == "" || $clave == "") {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
    exit();
}

/*== Verificar la integridad de los datos del usuario ==*/
if (verificar_datos("[a-zA-Z0-9]{4,20}", $usuario)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El usuario no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[a-zA-Z0-9$@.\-]{7,100}", $clave)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            La clave no coincide con el formato solicitado
        </div>
    ';
    exit();
}

/*== Consulta para verificar el usuario ==*/
$check_user = conexion();
$check_user = $check_user->query("SELECT * FROM usuario WHERE usuario_usuario='$usuario'");

if ($check_user->rowCount() == 1) {
    $check_user = $check_user->fetch();

    if ($check_user['usuario_usuario'] == $usuario && password_verify($clave, $check_user['usuario_clave'])) {
        /*== Almacenando información del usuario en la sesión ==*/
        $_SESSION['id'] = $check_user['usuario_id'];
        $_SESSION['nombre'] = $check_user['usuario_nombre'];
        $_SESSION['apellido'] = $check_user['usuario_apellido'];
        $_SESSION['usuario'] = $check_user['usuario_usuario'];

        /*== Obtener el rol del usuario ==*/
        $id_rol = $check_user['id_rol'];
        $consulta_rol = conexion();
        $consulta_rol = $consulta_rol->query("SELECT rol_nombre FROM roles WHERE id_rol='$id_rol'");
        
        if ($consulta_rol->rowCount() == 1) {
            $rol_data = $consulta_rol->fetch();
            $_SESSION['rol_nombre'] = $rol_data['rol_nombre']; // Guardar el nombre del rol en la sesión
        }

        /*== Redirigir al usuario ==*/
        if (headers_sent()) {
            echo "<script>window.location.href='index.php?vista=home';</script>";
        } else {
            header("Location: index.php?vista=home");
        }
        exit();
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                Usuario o clave incorrectos
            </div>
        ';
    }
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            Usuario o clave incorrectos
        </div>
    ';
}

$check_user = null;
$consulta_rol = null;

?>
