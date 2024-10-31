<?php
	require_once "main.php";
  
	/*== Almacenando id ==*/
    $id=limpiar_cadena($_POST['proveedor_id']);


    /*== Verificando producto ==*/
	$check_proveedor=conexion();
	$check_proveedor=$check_proveedor->query("SELECT * FROM proveedor WHERE proveedor_id='$id'");

    if($check_proveedor->rowCount()<=0){
    	echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                EL PROVEEDOR NO EXISTE EN EL SISTEMA
            </div>
        ';
        exit();
    }else{
    	$datos=$check_proveedor->fetch();
    }
    $check_proveedor=null;


    /*== Almacenando datos ==*/
    $nombre=limpiar_cadena($_POST['provee_nombre']);
    $numero=limpiar_cadena($_POST['provee_numero']);
	$rzs=limpiar_cadena($_POST['provee_rzs']);
	$rfc=limpiar_cadena($_POST['provee_rfc']);
	$email=limpiar_cadena($_POST['provee_email']);
	$direccion=limpiar_cadena($_POST['provee_direccion']);
    $contacto=limpiar_cadena($_POST['provee_ncontacto']);
    $ventas=limpiar_cadena($_POST['provee_nventas']);


	/*== Verificando campos obligatorios ==*/
    if($nombre=="" || $numero=="" || $rzs=="" || $rfc=="" || $email==""|| $direccion==""|| $contacto==""|| $ventas==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                NO HAS LLENADO LOS CAMPOS OBLIGATORIOS
            </div>
        ';
        exit();
    }


    /*== Verificando integridad de los datos ==*/
    

    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,70}",$nombre)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE NO COINCIDE CON EL FORMATO SOLICITADO
            </div>
        ';
        exit();
    }

    if(verificar_datos("[0-9]{1,10}",$numero)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NUMERO DE TELEFONO DE LA EMPRESA NO COINCIDE CON EL FORMATO SOLICITADO
            </div>
        ';
        exit();
    }
    

    if(verificar_datos("[a-zA-Z0-9\-., ]{1,100}",$rzs)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                LA RAZON SOCIAL NO COINCIDE CON EL FORMATO SOLICITADO
            </div>
        ';
        exit();
    }
    if(verificar_datos("[A-Z0-9]{1,13}",$rfc)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                LA RFC NO COINCIDE CON EL FORMATO SOLICITADO
            </div>
        ';
        exit();
    }
     
    if(verificar_datos("[a-zA-Z0-9\@.]{1,200}",$email)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                LA EMAIL NO COINCIDE CON EL FORMATO SOLICITADO
            </div>
        ';
        exit();
    }
    if(verificar_datos("[a-zA-Z0-9\-# ]{1,250}",$direccion)){
        
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El direccion no coincide con el formato solicitado
            </div>
        ';
        exit();
    }
    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,70}",$contacto)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                EL NOMBRE DE CONTACTO NO COINCIDE CON EL FORMATO SOLICITADO
            </div>
        ';
        exit();
    }
    if(verificar_datos("[0-9]{1,10}",$ventas)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NUMERO DE TELEFONO DEL CONTACTO NO COINCIDE CON EL FORMATO SOLICITADO
            </div>
        ';
        exit();
    }

    /*== Verificando nombre ==*/
    if($nombre!=$datos['proveedor_nombre']){
	    $check_nom=conexion();
	    $check_nom=$check_nom->query("SELECT proveedor_nombre FROM proveedor WHERE proveedor_nombre='$nombre'");
	    if($check_nom->rowCount()>0){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
                    EL NOMBRE INGRESADO YA SE ENCUENTRA REGISTRADO, POR FAVOR ELIJA OTRO
	            </div>
	        ';
	        exit();
	    }
	    $check_nom=null;
    }
  
    /*== Actualizando datos ==*/
    $actualizar_proveedor=conexion();
    $actualizar_proveedor=$actualizar_proveedor->prepare("UPDATE proveedor
    SET proveedor_nombre=:nombre,proveedor_rzs=:rzs,proveedor_rfc=:rfc,proveedor_email=:email,proveedor_direccion=:direccion,proveedor_numero=:numero,proveedor_ncontacto=:contacto,proveedor_nventas=:ventas WHERE proveedor_id=:id");
    $marcadores=[
    ":nombre"=>$nombre,
    ":rzs"=>$rzs,
    ":rfc"=>$rfc,
    ":email"=>$email,
    ":direccion"=>$direccion,
    ":numero"=>$numero,
    ":contacto"=>$contacto,
    ":ventas"=>$ventas,
    ":id" => $id 
];

if($actualizar_proveedor->execute($marcadores)){
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
$actualizar_proveedor=null;
