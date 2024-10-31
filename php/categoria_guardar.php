<?php
    require_once "main.php";

    /*== Almacenando datos ==*/
   $nombre=limpiar_cadena($_POST['categoria_nombre']);
   $ubicacion=limpiar_cadena($_POST['categoria_ubicacion']);

   if($nombre==""){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
    exit();
}

 # Verificando integridad de los datos#

 if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}",$nombre)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        EL NOMBRE NO COINCIDE CON EL FORMATO SOLICITADO
    </div>
';
exit();

};
//campo opcional//
if ($ubicacion!="") {
    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}",$ubicacion)){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            LA UBICACION NO COINCIDE CON EL FORMATO SOLICITADO
        </div>
    ';
    exit();
    }
    
}

#Verificando el nombre categoria#
$check_nombre=conexion();
$check_nombre=$check_nombre->query("SELECT categoria_nombre
FROM categoria WHERE categoria_nombre='$nombre'");

/*Devuelve cuantos registros hemos seleccionado */ 
if ($check_nombre->rowCount()>0) {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                LA CATEGORIA YA SE ENCUENTRA REGISTRADA, POR 
                FAVOR INTENTE CON UNO DISTINTO.                       
            </div>
     ';
     exit();
}
$check_usuario=null;

#Guardando los datos#
$guardar_categoria=conexion();
$guardar_categoria=$guardar_categoria->prepare("INSERT INTO categoria(categoria_nombre,categoria_ubicacion) 
VALUES(:nombre,:ubicacion)");

 #marcador y valor#
 $marcadores=[
    ":nombre"=>$nombre,
    ":ubicacion"=>$ubicacion
];

$guardar_categoria->execute($marcadores);

if ($guardar_categoria->rowCount()==1) {
    echo '
            <div class="notification is-info is-light">
                <strong>¡CATEGORIA REGISTRADA!</strong><br>
                LA CATEGORIA SE REGISTRO CON EXITO                  
            </div>
     ';

} else {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                NO SE PUDO REGISTRAR LA CATEGORIA, POR FAVOR INTENTE NUEVAMENTE                        
            </div>
     ';
    
}
$guardar_categoria=null;

