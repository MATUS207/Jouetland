<?php
	$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;
	$tabla="";
 

  
	$campos="bajas.baja_id,bajas.baja_motivo,bajas.baja_descripcion,bajas.fecha,usuario.usuario_nombre,productos.producto_nombre";
    	 

	if(isset($busqueda) && $busqueda!=""){
        $consulta_datos="SELECT  $campos FROM bajas INNER JOIN usuario ON bajas.usuario_nombre=usuario.usuario_nombre
        WHERE  bajas.fecha LIKE '%$busqueda%' OR usuario.usuario_nombre LIKE '%$busqueda%' OR productos.producto_nombre LIKE '%$busqueda%'
          ORDER BY bajas.fecha ASC LIMIT $inicio,$registros";

        $consulta_total="SELECT COUNT(baja_id) FROM bajas WHERE echa LIKE '%$busqueda%' OR usuario_nombre LIKE '%$busqueda%' OR producto_nombre LIKE '%$busqueda%'";
	}else{

        $consulta_datos="SELECT  $campos FROM bajas INNER JOIN productos ON bajas.producto_nombre=producto_nombre 
         ORDER BY bajas.fecha ASC LIMIT $inicio,$registros";



		$consulta_datos="SELECT * FROM bajas WHERE baja_id!='".$_SESSION['id']."' ORDER BY fecha ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(baja_id) FROM bajas";

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
                                
                                <th>Num.</th>
                                <th>ID</th>
                                <th>Nombre Producto</th> 
                                <th>Cantidad</th>
                                <th>Motivo </th>
                                <th>Descripcion</th>
                                <th>Fecha</th>
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
                        <td>'.$rows['producto_id'].'</td>
                        <td>'.$rows['baja_nombre'].'</td>
                        <td>'.$rows['baja_cantidad'].'</td>
                        <td>'.$rows['baja_motivo'].'</td>
                        <td>'.$rows['baja_descripcion'].'</td>
                        <td>'.$rows['fecha'].'</td>
                        <td>
                         <a href="index.php?vista=product_down_update&productb_id_up='.$rows['baja_id'].'"
                             class="button is-success is-rounded is-small">Actualizar</a>
                        </td>
                        
                  <td>
                            <a href="'.$url.$pagina.'&baja_id_del='.$rows['baja_id'].'"
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
                <td colspan="7">
                    <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                        Haga clic acá para recargar el listado
                    </a>
                </td>
            </tr>
        ';
    } else {
        $tabla.='
            <tr class="has-text-centered" >
                <td colspan="7">
                    No hay registros en el sistema
                </td>
            </tr>
        ';
    }
    
}

 
 
$tabla.=' </tbody> </table> </div>';

if($total>=1 && $pagina<=$Npaginas) {
    $tabla.='  <p class="has-text-right">Mostrando bajas de productos <strong>'.$pag_inicio.'</strong> 
    al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
}
 
$conexion=null;
echo $tabla;
if ($total>=1 && $pagina<=$Npaginas) {
    echo paginador_tablas($pagina,$Npaginas,$url,7);
} 
?> 

<div class="text-right mb-2">
    <a href="vistas_html/fpdf/reporteBajas.php" target="_blank" class="btn btn-success"><i class="fa-solid fa-file-pdf"></i>Generar reportes</a>
</div>

