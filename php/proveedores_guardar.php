<?php
	require_once "../incluir/sesion_start.php";

	require_once "main.php";

	/*== Almacenando datos ==*/
    $nombre=limpiar_cadena($_POST['provee_nombre']);
	$rzs=limpiar_cadena($_POST['provee_rzs']);
	$rfc=limpiar_cadena($_POST['provee_rfc']);
	$email=limpiar_cadena($_POST['provee_email']);
	$direccion=limpiar_cadena($_POST['provee_direccion']);
    $numero=limpiar_cadena($_POST['provee_numero']);
    $ncontacto=limpiar_cadena($_POST['provee_ncontacto']);
    $nventas=limpiar_cadena($_POST['provee_nventas']);

    

	/*== Verificando campos obligatorios ==*/
    if($nombre=="" || $rzs=="" || $rfc=="" || $direccion=="" || $numero==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }


    /*== Verificando integridad de los datos ==*/
    if(verificar_datos("[a-zA-Z- ]{1,70}",$nombre)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El nombre de la empresa no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if(verificar_datos("[a-zA-Z-. ]{1,70}",$rzs)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La razon social no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if(verificar_datos("[A-Z0-9]{1,13}",$rfc)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El rfc no coincide con el formato solicitado
            </div>
        ';
        exit();
    }


    if(verificar_datos("[a-zA-Z0-9- ]{1,250}",$direccion)){
        
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El direccion no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if(verificar_datos("[0-9]{1,250}",$numero)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El numero no coincide con el formato solicitado
            </div>
        ';
        exit();
    }
    if(verificar_datos("[a-zA-Z- ]{1,70}",$ncontacto)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El nombre de el contacto no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if(verificar_datos("[0-9]{1,250}",$nventas)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El numero no coincide con el formato solicitado
            </div>
        ';
        exit();
    }
    

    /*== Verificando rfc ==*/
    $check_rfc=conexion();
    $check_rfc=$check_rfc->query("SELECT proveedor_rfc FROM proveedor WHERE proveedor_rfc='$rfc'");
    if($check_rfc->rowCount()>0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El RFC ingresado ya se encuentra registrado, por favor elija otro
            </div>
        ';
        exit();
    }
    $check_rfc=null;


    /*== Verificando nombre ==*/
    $check_nombre=conexion();
    $check_nombre=$check_nombre->query("SELECT proveedor_nombre FROM proveedor WHERE proveedor_nombre='$nombre'");
    if($check_nombre->rowCount()>0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE ingresado ya se encuentra registrado, por favor elija otro
            </div>
        ';
        exit();
    }
    $check_nombre=null;

	/*== Guardando datos ==*/
    $guardar_proveedores=conexion();
    $guardar_proveedores=$guardar_proveedores->prepare("INSERT INTO proveedor(proveedor_nombre,proveedor_rzs,proveedor_rfc,proveedor_email,proveedor_direccion,proveedor_numero,proveedor_ncontacto,proveedor_nventas) VALUES(:nombre,:rzs,:rfc,:email,:direccion,:numero,:ncontacto,:nventas)");

    $marcadores=[
        ":nombre"=>$nombre,
        ":rzs"=>$rzs,
        ":rfc"=>$rfc,
        ":email"=>$email,
        ":direccion"=>$direccion,
        ":numero"=>$numero,
        ":ncontacto"=>$ncontacto,
        ":nventas"=>$nventas
    ];
 
    $guardar_proveedores->execute($marcadores);

    if($guardar_proveedores->rowCount()==1){
        echo '
            <div class="notification is-info is-light">
                <strong>¡PRODUCTO REGISTRADO!</strong><br>
                EL PROVEEDOR SE REGISTRO CON EXITO
            </div>
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo registrar EL PROVEEDOR, por favor intente nuevamente
            </div>
        ';
    }
    $guardar_proveedores=null;

