<?php
	require_once "main.php";
 
	/*== Almacenando id ==*/ 

    $id=limpiar_cadena($_POST['baja_id']);

	$check_producto=conexion();
	$check_producto=$check_producto->query("SELECT * FROM bajas WHERE baja_id='$id'");

    if($check_producto->rowCount()<=0){
    	echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                EL PRODUCTO DADO DE BAJA NO EXISTE EN EL SISTEMA
            </div>
        ';
        exit();
    }else{
    	$datos=$check_producto->fetch();
    }
    $check_producto=null;

    /*== Almacenando datos ==*/
    
	$cantidad=limpiar_cadena($_POST['baja_cantidad']);
	$motivo=limpiar_cadena($_POST['baja_motivo']);
    $descripcion=limpiar_cadena($_POST['baja_descripcion']);



	/*== Verificando campos obligatorios ==*/
    if($cantidad=="" || $motivo=="" || $descripcion==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                NO HAS LLENADO LOS CAMPOS OBLIGATORIOS
            </div>
        ';
        exit();
    }
 

    /*== Verificando integridad de los datos ==*/
    if(verificar_datos("[0-9 ]{1,20}",$cantidad)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                LA CANTIDAD NO COINCIDE CON EL FORMATO SOLICITADO
            </div>
        ';
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ()., ]{1,500}",$motivo)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El MOTIVO NO COINCIDE CON EL FORMATO SOLICITADO
            </div>
        ';
        exit();
    }
    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ()., ]{1,500}",$descripcion)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                LA DESCRIPCION NO COINCIDE CON EL FORMATO SOLICITADO
            </div>
        ';
        exit();
    }

  
    if($cantidad!=$datos['baja_cantidad']){
	    $check_cantidad=conexion();
	    $check_cantidad=$check_cantidad->query("SELECT baja_cantidad FROM bajas WHERE baja_cantidad='$cantidad'");
	    if($check_cantidad->rowCount()>0){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                INSERTE UN NUMERO MAYOR A CERO
	            </div>
	        ';
	        exit();
	    }
	    $check_cantidad=null;
    }


   
    if($motivo!=$datos['baja_motivo']){
	    $check_motivo=conexion();
	    $check_motivo=$check_motivo->query("SELECT baja_motivo FROM bajas WHERE baja_motivo='$motivo'");
	    if($check_motivo->rowCount()>0){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
                    XXXX
	            </div>
	        ';
	        exit();
	    }
	    $check_motivo=null;
    }

     
     if($descripcion!=$datos['baja_descripcion']){
	    $check_descripcion=conexion();
	    $check_descripcion=$check_descripcion->query("SELECT baja_descripcion FROM bajas WHERE baja_descripcion='$descripcion'");
	    if($check_descripcion->rowCount()>0){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
                    XXXX
	            </div>
	        ';
	        exit();
	    }
	    $check_descripcion=null;
    }
    
$actualizar_productobajas=conexion();
$actualizar_productobajas=$actualizar_productobajas->prepare("UPDATE bajas
 SET baja_cantidad=:cantidad,baja_motivo=:motivo,baja_descripcion=:descripcion, fecha=NOW() WHERE baja_id=:id");

$marcadores=[
    ":cantidad"=>$cantidad,
    ":motivo"=>$motivo,
    ":descripcion"=>$descripcion,
    ":id"=>$id
];

if($actualizar_productobajas->execute($marcadores)){
    echo '
        <div class="notification is-info is-light">
            <strong>¡PRODUCTO ACTUALIZADO!</strong><br>
            EL PRODUCTO SE ACTUALIZÓ CON ÉXITO
        </div>
    ';
}else{
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            NO SE PUDO ACTUALIZAR EL PRODUCTO, POR FAVOR INTENTE NUEVAMENTE
        </div>
    ';
}
$actualizar_productobajas=null;
