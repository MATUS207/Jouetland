<?php
// Iniciar la sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el rol del usuario es administrador
if (!isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'administrador') {
    // Si no es administrador, redirigir a una página de acceso denegado o a la página de inicio
    header("Location: index.php?vista=home"); // O a una página de acceso denegado
    exit();
}
?>
