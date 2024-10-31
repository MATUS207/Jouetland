<?php
   require_once "main.php";

   /*== Almacenando id ==*/
   $product_id=limpiar_cadena($_POST['img_del_id']);


   /*== Verificando producto ==*/
   $check_producto=conexion();
   $check_producto=$check_producto->query("SELECT * FROM productos WHERE producto_id='$product_id'");

   if($check_producto->rowCount()==1){
    $datos=$check_producto->fetch();
       
   }else{
    echo '
           <div class="notification is-danger is-light">
               <strong>¡Ocurrio un error inesperado!</strong><br>
               LA IMAGEN DEL PRODUCTO QUE INTENTA ELIMINAR NO EXISTE EN EL SISTEMA
           </div>
       ';
       exit();
       
   }
   $check_producto=null;
   $img_dir='../img/productos/';
   chmod($img_dir,0777);

   if (is_file($img_dir.$datos['producto_foto'])) {
        chmod($img_dir.$datos['producto_foto'],0777);

            if (!unlink($img_dir.$datos['producto_foto'])) {
                echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        ERRROR AL INTENTAR ELIMINAR LA IMAGEN DEL PRODUCTO,
                        POR FAVOR INTENTE NUEVAMENTE
                    </div>
                    ';
                    exit();
            }
   }

   $actualizar_producto=conexion();
    $actualizar_producto=$actualizar_producto->prepare("UPDATE productos 
    SET producto_foto=:foto WHERE producto_id=:id");
     $marcadores=[
        ":foto"=>"",
        ":id"=>$product_id
    ];


    if($actualizar_producto->execute($marcadores)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡IMAGEN O FOTO ELIMINADA!</strong><br>
                LA IMAGEN DEL PRODUCTO SE ELIMINO CON EXITO, PULSE ACEPTAR PARA CARGAR LOS CAMBIOS.

                <p class="has-text-centered pt-5 pb-5">
                    <a href="index.php?vista=product_img&product_id_up='.$product_id.'" class="button is-link is-rounded">Aceptar</a>
                </p">
            </div>
        ';
    }else{
        echo '
            <div class="notification is-warning is-light">
                <strong>¡IMAGEN O FOTO ELIMINADA!</strong><br>
                OCURRIERON ALGUNOS INCONVENIENTES, SIN EMBARGO LA IMAGEN DEL PRODUCTO SE ELIMINO, PULSE ACEPTAR PARA RECARGAR LOS CAMBIOS.
                <p class="has-text-centered pt-5 pb-5">
                    <a href="index.php?vista=product_img&product_id_up='.$product_id.'" class="button is-link is-rounded">Aceptar</a>
                </p">
            </div>
        ';
    }
    $actualizar_producto=null;