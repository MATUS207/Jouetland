<?php
	require_once "main.php";
  
	/*== Almacenando id ==*/
    $id=limpiar_cadena($_POST['categoria_id']);


    /*== Verificando producto ==*/
	$check_categoria=conexion();
	$check_categoria=$check_categoria->query("SELECT * FROM categoria WHERE categoria_id='$id'");

    if($check_categoria->rowCount()<=0){
    	echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                LA CATEGORIA NO EXISTE EN EL SISTEMA
            </div>
        ';
        exit();
    }else{
    	$datos=$check_categoria->fetch();
    }
    $check_categoria=null;

    $nombre=limpiar_cadena($_POST['categoria_nombre']);
    $ubicacion=limpiar_cadena($_POST['categoria_ubicacion']);
  

    
    if($nombre=="" || $ubicacion==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,70}",$nombre)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE NO COINCIDE CON EL FORMATO SOLICITADO
            </div>
        ';
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,200}",$ubicacion)){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            LA UBICACION NO COINCIDE CON EL FORMATO SOLICITADO
        </div>
    ';
    exit();
    
    };
   
    if($nombre!=$datos['categoria_nombre']){
	    $check_nombre=conexion();
	    $check_nombre=$check_nombre->query("SELECT categoria_nombre FROM categoria WHERE categoria_nombre='$nombre'");
	    if($check_nombre->rowCount()>0){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
                    LA CATEGORIA INGRESADO YA SE ENCUENTRA REGISTRADO, POR FAVOR ELIJA OTRO
	            </div>
	        ';
	        exit();
	    }
	    $check_nombre=null;
    }
  
    /*== Actualizando datos ==*/
    $actualizar_categoria=conexion();
    $actualizar_categoria=$actualizar_categoria->prepare("UPDATE categoria
    SET categoria_nombre=:nombre,categoria_ubicacion=:ubicacion WHERE categoria_id=:id");
    $marcadores=[
    ":nombre"=>$nombre,
    ":ubicacion"=>$ubicacion,
    ":id" => $id 
];

if($actualizar_categoria->execute($marcadores)){
    echo '
        <div class="notification is-info is-light">
            <strong>¡PRODUCTO ACTUALIZADO!</strong><br>
           LA CATEGORIA SE ACTUALIZÓ CON ÉXITO
        </div>
    ';
}else{
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            NO SE PUDO ACTUALIZAR LA CATEGORIA, POR FAVOR INTENTE NUEVAMENTE
        </div>
    ';
}
$actualizar_categoria=null;
