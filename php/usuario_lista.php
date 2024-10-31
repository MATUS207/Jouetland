<h1 class="title">Usuarios</h1>
<h2 class="subtitle">Lista de usuarios</h2>
<?php
session_start(); // Asegúrate de que la sesión esté iniciada

// Verifica si el usuario ha iniciado sesión y si su rol es 'administrador'
if (!isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'administrador') {
    // Redirige a una página de error o a la página de inicio
    header("Location: http://localhost/inventariojouetland/index.php?vista=home"); // Cambia 'error.php' por la página que desees
    exit();
}
  

$inicio = ($pagina>0) ? (($pagina*$registros)-$registros) : 0;

$tabla = "";

if (isset($busqueda) && $busqueda != "") {
    // Consulta con búsqueda
    $consulta_datos = "
        SELECT usuario.*, roles.rol_nombre FROM usuario
        JOIN roles ON usuario.usuario_rol = roles.id_rol
        WHERE usuario_id != '".$_SESSION['id']."' 
        AND (usuario_nombre LIKE '%$busqueda%' OR usuario_apellido LIKE '%$busqueda%' 
        OR usuario_usuario LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%')
        ORDER BY usuario_nombre ASC LIMIT $inicio, $registros";
    
    $consulta_total = "
        SELECT COUNT(usuario_id) FROM usuario
        WHERE usuario_id != '".$_SESSION['id']."' 
        AND (usuario_nombre LIKE '%$busqueda%' OR usuario_apellido LIKE '%$busqueda%' 
        OR usuario_usuario LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%')";
} else {
    // Consulta sin búsqueda
    $consulta_datos = "
        SELECT usuario.*, roles.rol_nombre FROM usuario
        JOIN roles ON usuario.id_rol = roles.id_rol
        WHERE usuario_id != '".$_SESSION['id']."'
        ORDER BY usuario_nombre ASC LIMIT $inicio, $registros";
    
    $consulta_total = "
        SELECT COUNT(usuario_id) FROM usuario 
        WHERE usuario_id != '".$_SESSION['id']."'";
}

$conexion = conexion();

$datos = $conexion->query($consulta_datos);
$datos = $datos->fetchAll();

$total = $conexion->query($consulta_total);
$total = (int) $total->fetchColumn();

$Npaginas = ceil($total / $registros);

$tabla .= '
    <div class="table-container">
        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr class="has-text-centered">
                    <th>#</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol asignado</th>
                    <th colspan="3">Opciones</th>
                </tr>
            </thead>
            <tbody>
';

if ($total >= 1 && $pagina <= $Npaginas) {
    $contador = $inicio + 1;
    $pag_inicio = $inicio + 1;
    foreach ($datos as $rows) {
        $tabla .= '  
            <tr class="has-text-centered">
                <td>'.$contador.'</td>
                <td>'.$rows['usuario_nombre'].'</td>
                <td>'.$rows['usuario_apellido'].'</td>
                <td>'.$rows['usuario_usuario'].'</td>
                <td>'.$rows['usuario_email'].'</td>
                <td>'.$rows['rol_nombre'].'</td> <!-- Aquí mostramos el nombre del rol -->
                <td>
                    <a href="index.php?vista=user_update&user_id_up='.$rows['usuario_id'].'" class="button is-success is-rounded is-small">Actualizar</a>
                </td>
                <td>
                    <a href="'.$url.$pagina.'&user_id_del='.$rows['usuario_id'].'" class="button is-danger is-rounded is-small">Eliminar</a>
                </td>
            </tr>
        ';
        $contador++;
    }
    $pag_final = $contador - 1;
} else {
    if ($total >= 1) {
        $tabla .= '
            <tr class="has-text-centered">
                <td colspan="7">
                    <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                        Haga clic acá para recargar el listado
                    </a>
                </td>
            </tr>
        ';
    } else {
        $tabla .= '
            <tr class="has-text-centered">
                <td colspan="7">
                    No hay registros en el sistema
                </td>
            </tr>
        ';
    }
}

$tabla .= ' </tbody> </table> </div>';

if ($total >= 1 && $pagina <= $Npaginas) {
    $tabla .= '<p class="has-text-right">Mostrando usuarios <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
}

$conexion = null;
echo $tabla;

if ($total >= 1 && $pagina <= $Npaginas) {
    echo paginador_tablas($pagina, $Npaginas, $url, 7);
}
