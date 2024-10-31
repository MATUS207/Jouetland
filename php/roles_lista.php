 <?php
$inicio = ($pagina>0) ? (($pagina*$registros)-$registros) : 0;
$tabla="";
 
if (isset($busqueda) && $busqueda!="") {

    $consulta_datos="SELECT * FROM roles WHERE rol_nombre LIKE '%$busqueda%'
    ORDER BY rol_nombre ASC LIMIT $inicio,$registros";
    
    
    $consulta_total="SELECT COUNT(i_rold) FROM roles 
    WHERE rol_nombre LIKE '%$busqueda%'";
    
}else {
    $consulta_datos="SELECT * FROM roles ORDER BY rol_nombre ASC LIMIT $inicio,$registros";
    
    $consulta_total="SELECT COUNT(id_rol) FROM roles";
} 

$conexion=conexion();

$datos=$conexion->query($consulta_datos);
$datos=$datos->fetchAll();

$total=$conexion->query($consulta_total);
$total=(int) $total->fetchColumn();

$Npaginas=ceil($total/$registros);
 
$tabla.='
            <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                    <tr class="has-text-centered">
                    <th>Num</th>
                    <th>Nombres</th>
                     <th colspan="2">Opcion</th>
                   
                    </tr>
                </thead>
                <tbody> 
';

if($total>=1 && $pagina<=$Npaginas) {
    $contador=$inicio+1;
    $pag_inicio=$inicio+1;
    foreach($datos as $rows){
        $tabla.='
            <tr class="has-text-centered" >
                        <td>'.$contador.'</td>
                        <td>'.$rows['rol_nombre'].'</td>

                     
                          <td>
                            <a href="'.$url.$pagina.'&rol_id_del='.$rows['id_rol'].'"
                             class="button is-danger is-rounded is-small">Eliminar</a>
                        </td>
                        
                     
                        
                    </tr>
        ';
        $contador++;
    }
    $pag_final=$contador-1;
} else{
    if ($total>=1) {
        $tabla.='
                <tr class="has-text-centered " >
                <td colspan="5">
                    <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                        Haga clic acá para recargar el listado
                    </a>
                </td>
            </tr>
        ';
    } else {
        $tabla.='
            <tr class="has-text-centered" >
                <td colspan="6">
                    No hay registros en el sistema
                </td>
            </tr>
        ';   
    }   
}
$tabla.=' </tbody> </table>';

if($total>=1 && $pagina<=$Npaginas) {
    $tabla.='  <p class="has-text-right">Mostrando roles <strong>'.$pag_inicio.'</strong> 
    al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
}

$conexion=null;
echo $tabla;
if ($total>=1 && $pagina<=$Npaginas) {
    echo paginador_tablas($pagina,$Npaginas,$url,7);
}
?>



