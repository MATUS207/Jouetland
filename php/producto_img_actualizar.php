<?php
  
  require_once "main.php";

  /*== Almacenando datos ==*/
  $product_id=limpiar_cadena($_POST['img_up_id']);

  /*== Verificando producto ==*/
  $check_producto=conexion();
  $check_producto=$check_producto->query("SELECT * FROM productos WHERE producto_id='$product_id'");

  if($check_producto->rowCount()==1){
      $datos=$check_producto->fetch();
  }else{
      echo '
          <div class="notification is-danger is-light">
              <strong>¡Ocurrio un error inesperado!</strong><br>
              LA IMAGEN DEL PRODCUTO QUE INETENTA ACTUALIZAR NO EXISTE
          </div>
      ';
      exit();
  }
  $check_producto=null;


  /*== Comprobando si se ha seleccionado una imagen ==*/
  if($_FILES['producto_foto']['name']=="" || $_FILES['producto_foto']['size']==0){
      echo '
          <div class="notification is-danger is-light">
              <strong>¡Ocurrio un error inesperado!</strong><br>
              NO HA SELECCIONADO NINGUNA IMAGEN O FOTO 
          </div>
      ';
      exit();
  }


  /* Directorios de imagenes */
  $img_dir='../img/productoS/';


  /* Creando directorio de imagenes */
  if(!file_exists($img_dir)){
      if(!mkdir($img_dir,0777)){
          echo '
              <div class="notification is-danger is-light">
                  <strong>¡Ocurrio un error inesperado!</strong><br>
                  ERROR AL CREAR EL DIRECTORIO DE IMAGENES
              </div>
          ';
          exit();
      }
  }


  /* Cambiando permisos al directorio */
  chmod($img_dir, 0777);


  /* Comprobando formato de las imagenes */
  if(mime_content_type($_FILES['producto_foto']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['producto_foto']['tmp_name'])!="image/png"){
      echo '
          <div class="notification is-danger is-light">
              <strong>¡Ocurrio un error inesperado!</strong><br>
              LA IMAGEN QUE HA SELECCIONADO ES DE UN FORMATO QUE NO ESTA PERMITIDO.
          </div>
      ';
      exit();
  }


  /* Comprobando que la imagen no supere el peso permitido */
  if(($_FILES['producto_foto']['size']/1024)>3072){
      echo '
          <div class="notification is-danger is-light">
              <strong>¡Ocurrio un error inesperado!</strong><br>
              LA IMAGEN QUE HA SELECCIONADO ES DE UN FORMATO QUE NO ESTA PERMITIDO.
          </div>
      ';
      exit();
  }


  /* extencion de las imagenes */
  switch(mime_content_type($_FILES['producto_foto']['tmp_name'])){
      case 'image/jpeg':
        $img_ext=".jpg";
      break;
      case 'image/png':
        $img_ext=".png";
      break;
  }

  /* Nombre de la imagen */
  $img_nombre=renombrar_fotos($datos['producto_nombre']);

  /* Nombre final de la imagen */
  $foto=$img_nombre.$img_ext;

  /* Moviendo imagen al directorio */
  if(!move_uploaded_file($_FILES['producto_foto']['tmp_name'], $img_dir.$foto)){
      echo '
          <div class="notification is-danger is-light">
              <strong>¡Ocurrio un error inesperado!</strong><br>
              NO PODEMOS SUBIR LA IMAGEN AL SISTEMA EN ESTE MOMENTO, POR FAVOR INTENTE NUEVAMENTE
          </div>
      ';
      exit();
  }


  /* Eliminando la imagen anterior */
  if(is_file($img_dir.$datos['producto_foto']) && $datos['producto_foto']!=$foto){

      chmod($img_dir.$datos['producto_foto'], 0777);
      unlink($img_dir.$datos['producto_foto']);
  }


  /*== Actualizando datos ==*/
  $actualizar_producto=conexion();
  $actualizar_producto=$actualizar_producto->prepare("UPDATE productos SET producto_foto=:foto WHERE producto_id=:id");

  $marcadores=[
      ":foto"=>$foto,
      ":id"=>$product_id
  ];

  if($actualizar_producto->execute($marcadores)){
      echo '
          <div class="notification is-info is-light">
              <strong>¡IMAGEN O FOTO ACTUALIZADA!</strong><br>
              LA IMAGEN DEL PRODUCTO HA SIDO ACTUALIZADA EXITOSAMENTE, PULSE ACEPTAR PARA RECARGAR LOS CAMBIOS.

              <p class="has-text-centered pt-5 pb-5">
                  <a href="index.php?vista=product_img&product_id_up='.$product_id.'" class="button is-link is-rounded">Aceptar</a>
              </p">
          </div>
      ';
  }else{

      if(is_file($img_dir.$foto)){
          chmod($img_dir.$foto, 0777);
          unlink($img_dir.$foto);
      }

      echo '
          <div class="notification is-warning is-light">
              <strong>¡Ocurrio un error inesperado!</strong><br>
              NO PODEMOS SUBIR LA IMAGEN AL SISTEMA EN ESTE MOMENTO, POR FAVOR INTENTE NUEVAMENTE.
          </div>
      ';
  }
  $actualizar_producto=null;