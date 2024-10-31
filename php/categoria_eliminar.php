<?php
/*== Almacenando datos ==*/
$category_id_del=limpiar_cadena($_GET['category_id_del']);

$check_categoria=conexion();
$check_categoria=$check_categoria->query("SELECT categoria_id FROM categoria WHERE categoria_id='$category_id_del'");
 
if ($check_categoria->rowCount()==1) {
    $check_productos=conexion();
    $check_productos=$check_productos->query("SELECT categoria_id 
    FROM productos WHERE categoria_id='$category_id_del' LIMIT 1");

    if ($check_productos->rowCount()<=0) {
        $eliminar_categoria=conexion();
	    $eliminar_categoria=$eliminar_categoria->prepare("DELETE FROM categoria WHERE categoria_id=:id");
	    $eliminar_categoria->execute([":id"=>$category_id_del]);


        if($eliminar_categoria->rowCount()==1){
            echo '
                <div class="notification is-info is-light">
                    <strong>¡CATEGORIA ELIMINADA!</strong><br>
                    LOS DATOS DE LA CATEGORIA SE ELIMINARON CON EXITO
                </div>
            ';
        }else{
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    NO SE PUDO ELIMINAR LA CATEGORIA, POR FAVOR INTENTELO NUEVAMENTE
                </div>
            ';
        }
        $eliminar_categoria=null;


        
    } else {
        echo '
		<div class="notification is-info is-light">
	    <strong>¡USUARIO ELIMINADO!</strong><br>
		NO PODEMOS SE PUEDE ELIMINAR LA CATEGORIA YA QUE TIENE PRODUCTOS ASOCIADOS
		</div>
		';
    }


    $check_productos=null;
    
} else {
    echo '
		<div class="notification is-info is-light">
	    <strong>¡USUARIO ELIMINADO!</strong><br>
		LA CATEGORIA QUE INTENTA ELIMINAR NO EXISTE
		</div>
		';
}

$check_categoria=null;
