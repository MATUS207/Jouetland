<?php

	/*== Almacenando datos ==*/
    $user_id_del=limpiar_cadena($_GET['user_id_del']);

    /*== Verificando usuario ==*/
    $check_usuario=conexion();
    $check_usuario=$check_usuario->query("SELECT usuario_id FROM usuario WHERE usuario_id='$user_id_del'");
    
    if($check_usuario->rowCount()==1){

    	$check_productos=conexion();
    	$check_productos=$check_productos->query("SELECT usuario_id FROM productos WHERE usuario_id='$user_id_del' LIMIT 1");
 
    	if($check_productos->rowCount()<=0){
    		
	    	$eliminar_usuario=conexion();
	    	$eliminar_usuario=$eliminar_usuario->prepare("DELETE FROM usuario WHERE usuario_id=:id");

	    	$eliminar_usuario->execute([":id"=>$user_id_del]);

	    	if($eliminar_usuario->rowCount()==1){
		        echo '
		            <div class="notification is-info is-light">
		                <strong>¡USUARIO ELIMINADO!</strong><br>
		                LOS DATOS DEL USUARIO FUERON ELIMINADOS CON EXITO
		            </div>
		        ';
		    }else{
		        echo '
		            <div class="notification is-danger is-light">
		                <strong>¡Ocurrio un error inesperado!</strong><br>
		                NO SE PUDO ELIMINAR EL USUARIO, POR FAVOR INTENTELO NUEVAMENTE
		            </div>
		        ';
		    }
		    $eliminar_usuario=null;
    	}else{
    		echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
                    NO PODEMOS ELIMINAR EL USUARIO DEBIDO A QUE TIENE PRODUCTOS REGISTRADOR
	            </div>
	        ';
    	}
    	$check_productos=null;
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                EL USUARIO QUE INTENTA ELIMINAR NO EXISTE
            </div>
        ';
    }
    $check_usuario=null;