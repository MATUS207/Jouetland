<?php
	$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;
	$tabla="";

	$campos="proveedor.proveedor_id,proveedor.proveedor_nombre,proveedor.proveedor_rzs,proveedor.proveedor_rfc,proveedor.proveedor_email,proveedor.proveedor_numero,proveedor.proveedor_direccion,proveedor.proveedor_ncontacto,proveedor.proovedor_nventas";

	if(isset($busqueda) && $busqueda!=""){

        $consulta_datos="SELECT * FROM proveedor WHERE ((proveedor_id!='".
        $_SESSION['id']."') AND (proveedor_nombre LIKE '%$busqueda%' OR proveedor_rfc LIKE '%$busqueda%' OR proveedor_email LIKE '%$busqueda%' OR proveedor_ncontacto LIKE '%$busqueda%' OR proveedor_nventas LIKE '%$busqueda%')) ORDER BY proveedor_nombre ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(proveedor_id) FROM proveedor WHERE ((proveedor_id!='".
        $_SESSION['id']."') AND (proveedor_nombre LIKE '%$busqueda%' OR proveedor_rfc LIKE '%$busqueda%' OR proveedor_email LIKE '%$busqueda%'))";


	}else{

		$consulta_datos="SELECT * FROM proveedor WHERE proveedor_id!='".$_SESSION['id']."' ORDER BY proveedor_nombre ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(proveedor_id) FROM proveedor WHERE proveedor_id!='".$_SESSION['id']."'";

    }

	$conexion=conexion();

	$datos = $conexion->query($consulta_datos);
	$datos = $datos->fetchAll();

	$total = $conexion->query($consulta_total);
	$total = (int) $total->fetchColumn();

	$Npaginas =ceil($total/$registros);

    $tabla.='

            <div class="table-container">
                    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                        <thead>
                            <tr class="has-text-centered">
                                <th>#</th>
                                <th>Nombre de la empresa</th>
                                <th>Razon Social</th>
                                <th>RFC</th>
                                <th>Email</th>
                                <th>Direccion</th>
                                <th>Numero de la empresa</th>
                                <th>Nombre del contacto</th>
                                <th>Numero del contacto</th>
                                
                                
                                <th colspan="2">Opciones</th>
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
                        <td>'.$rows['proveedor_nombre'].'</td>
                        <td>'.$rows['proveedor_rzs'].'</td>
                        <td>'.$rows['proveedor_rfc'].'</td>
                        <td>'.$rows['proveedor_email'].'</td>
                        <td>'.$rows['proveedor_direccion'].'</td>
                        <td>'.$rows['proveedor_numero'].'</td>
                        <td>'.$rows['proveedor_ncontacto'].'</td>
                        <td>'.$rows['proveedor_nventas'].'</td>
                        
                        
                        <td>
                            <a href="index.php?vista=provee_update&provee_id_up='.$rows['proveedor_id'].'"
                             class="button is-success is-rounded is-small">Actualizar</a>
                        </td>
                        <td>
                            <a href="'.$url.$pagina.'&provee_id_del='.$rows['proveedor_id'].'"
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
                <tr class="has-text-centered" >
                <td colspan="10">
                    <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                        Haga clic acá para recargar el listado
                    </a>
                </td>
            </tr>
        ';
    } else {
        $tabla.='
            <tr class="has-text-centered" >
                <td colspan="10">
                    No hay registros en el sistema
                </td>
            </tr>
        ';
    }
    
}

$tabla.=' </tbody> </table> </div>';

if($total>=1 && $pagina<=$Npaginas) {
    $tabla.='  <p class="has-text-right">Mostrando proveedores <strong>'.$pag_inicio.'</strong> 
    al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
}

$conexion=null;
echo $tabla;
if ($total>=1 && $pagina<=$Npaginas) {
    echo paginador_tablas($pagina,$Npaginas,$url,7);
}


