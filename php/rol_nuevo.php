<?php
   require_once "main.php";

   /*== Almacenando datos ==*/
   $nombre=limpiar_cadena($_POST['rol_nombre']);
  

   /*== Verificando campos obligatorios ==*/
   if($nombre=="" ){
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
    #Verificando el usuario#
            $check_rol=conexion();
            $check_rol=$check_rol->query("SELECT rol_nombre
            FROM roles WHERE rol_nombre='$nombre'");

           /*Devuelve cuantos registros hemos seleccionado */ 
           if ($check_rol->rowCount()>0) {
                echo '
                        <div class="notification is-danger is-light">
                            <strong>¡Ocurrio un error inesperado!</strong><br>
                            EL ROL INGRESADO YA SE ENCUENTRA REGISTRADO, POR 
                            FAVOR INTENTE CON UNO DISTINTO.                       
                        </div>
                 ';
                 exit();
            }
            $check_rol=null;

            #Guardando los datos#
            $guardar_rol=conexion();
            $guardar_rol=$guardar_rol->prepare("INSERT INTO roles (rol_nombre) 
            VALUES(:nombre)");
             #marcador y valor#
            $marcadores=[
                ":nombre"=>$nombre,
               
            ];
            #ejecuta la consulta#
            $guardar_rol->execute($marcadores);

            if ($guardar_rol->rowCount()==1) {
                echo '
                        <div class="notification is-info is-light">
                            <strong>¡USUARIO REGISTRADOR CON EXITO!</strong><br>
                            EL ROL SE REGISTRO CORRECTAMENTE.                   
                        </div>
                 ';
                
            } else {
                echo '
                        <div class="notification is-danger is-light">
                            <strong>¡Ocurrio un error inesperado!</strong><br>
                            NO SE PUDO REGISTRAR EL ROL, POR FAVOR INTENTE NUEVAMENTE                     
                        </div>
                 ';
            }
            $guardar_rol=null;






