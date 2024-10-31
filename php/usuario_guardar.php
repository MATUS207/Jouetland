<?php
   require_once "main.php";

   /*== Almacenando datos ==*/
   $nombre=limpiar_cadena($_POST['usuario_nombre']);
   $apellido=limpiar_cadena($_POST['usuario_apellido']);

   $usuario=limpiar_cadena($_POST['usuario_usuario']);
   $email=limpiar_cadena($_POST['usuario_email']);

   $clave_1=limpiar_cadena($_POST['usuario_clave_1']);
   $clave_2=limpiar_cadena($_POST['usuario_clave_2']);
   $rol=limpiar_cadena($_POST['usuario_rol']);
   
  

   /*== Verificando campos obligatorios ==*/
   if($nombre=="" || $apellido=="" || $usuario=="" || $clave_1=="" || $clave_2=="" || $rol==""){
       echo '
           <div class="notification is-danger is-light">
               <strong>¡Ocurrio un error inesperado!</strong><br>
               No has llenado todos los campos que son obligatorios
           </div>
       ';
       exit();
   }

    # Verificando integridad de los datos#

    if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            EL NOMBRE NO COINCIDE CON EL FORMATO SOLICITADO
        </div>
    ';
    exit();

    };

    if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            EL APELLIDO NO COINCIDE CON EL FORMATO SOLICITADO
        </div>
    ';
    exit();

    };

    if(verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            EL USUARIO NO COINCIDE CON EL FORMATO SOLICITADO
        </div>
    ';
    exit();

    };


    if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1)|| verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_2)){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            LAS CLAVES NO COINCIDE CON EL FORMATO SOLICITADO
        </div>
    ';
    exit();

    };

    
    /*== Verificando categoria ==*/
    $check_rol=conexion();
    $check_rol=$check_rol->query("SELECT id_rol FROM roles WHERE id_rol='$rol'");
    if($check_rol->rowCount()<=0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                EL ROL seleccionada no existe
            </div>
        ';
        exit();
    }
    $check_rol=null;

    #verificando el email#
    if ($email!="") {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $check_email=conexion();
            $check_email=$check_email->query("SELECT usuario_email 
            FROM usuario WHERE usuario_email='$email'");

            if ($check_email->rowCount()>0) {
                echo '
                        <div class="notification is-danger is-light">
                            <strong>¡Ocurrio un error inesperado!</strong><br>
                            EL EMAIL INGRESADO YA SE ENCUENTRA REGISTRADO, POR FAVOR INTENTE CON UNO DISTINTO                        </div>
                 ';
                 exit();
            }
            $check_email=null;
        }else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    EL EMAIL INGRESADO NO ES VALIDO
                </div>
        ';
        exit();
        }
    }

    #Verificando el usuario#
            $check_usuario=conexion();
            $check_usuario=$check_usuario->query("SELECT usuario_usuario
            FROM usuario WHERE usuario_usuario='$usuario'");

           /*Devuelve cuantos registros hemos seleccionado */ 
           if ($check_usuario->rowCount()>0) {
                echo '
                        <div class="notification is-danger is-light">
                            <strong>¡Ocurrio un error inesperado!</strong><br>
                            EL USUARIO INGRESADO YA SE ENCUENTRA REGISTRADO, POR 
                            FAVOR INTENTE CON UNO DISTINTO.                       
                        </div>
                 ';
                 exit();
            }
            $check_usuario=null;

            #Validando contraseñas#

            if ($clave_1!=$clave_2) {
                echo '
                        <div class="notification is-danger is-light">
                            <strong>¡Ocurrio un error inesperado!</strong><br>
                            LAS CONTRASEÑAS INGRESADAS NO COINCIDEN                      
                        </div>
                 ';
                 exit();
            }else {
                $clave=password_hash($clave_1,PASSWORD_BCRYPT,["cost"=>10]);
            }

            #Guardando los datos#
            $guardar_usuario=conexion();
            $guardar_usuario=$guardar_usuario->prepare("INSERT INTO usuario(usuario_nombre,usuario_apellido,usuario_usuario,usuario_clave,usuario_email,id_rol) 
            VALUES(:nombre,:apellido,:usuario,:clave,:email,:rol)");
             #marcador y valor#
            $marcadores=[
                ":nombre"=>$nombre,
                ":apellido"=>$apellido,
                ":usuario"=>$usuario,
                ":clave"=>$clave,
                ":email"=>$email,
                ":rol"=>$rol
            ];
            #ejecuta la consulta#
            $guardar_usuario->execute($marcadores);

            if ($guardar_usuario->rowCount()==1) {
                echo '
                        <div class="notification is-info is-light">
                            <strong>¡USUARIO REGISTRADOR CON EXITO!</strong><br>
                            EL USUARIO SE REGISTRO CORRECTAMENTE.                   
                        </div>
                 ';
                
            } else {
                echo '
                        <div class="notification is-danger is-light">
                            <strong>¡Ocurrio un error inesperado!</strong><br>
                            NO SE PUDO REGISTRAR EL USUARIO, POR FAVOR INTENTE NUEVAMENTE                     
                        </div>
                 ';
            }
            
            
            $guardar_usuario=null;














