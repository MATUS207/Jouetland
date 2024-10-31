<?php
    $modulo_buscador=limpiar_cadena($_POST['modulo_buscador']);

    $modulos=["usuario","categoria","producto","proveedor"]; ##modificar para proveedores

    if(in_array($modulo_buscador,$modulos)) {

        $modulos_url=[
            "usuario"=>"user_search",
            "categoria"=>"category_search",
            "producto"=>"product_search",
            "proveedor"=>"provee_search"
        ];

        $modulos_url= $modulos_url[$modulo_buscador];
        
        $modulo_buscador="busqueda_".$modulo_buscador;
// Iniciar la busqueda //

        if (isset($_POST['txt_buscador'])){
                $txt=limpiar_cadena($_POST['txt_buscador']);

            if ($txt==""){
                echo '
                    <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    INTRODUCE UN TERMINO DE BUSQUEDA
                    </div>';
        
            } else {
                if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}",$txt)) {
                    echo '
                    <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    EL TERMINO DE BUSQUEDA NO COINCIDE CON EL FORMATO SOLICITADO
                    </div>';
                } else {
                    $_SESSION[$modulo_buscador]=$txt;

                    header("location: index.php?vista=$modulos_url",true,303);
                    exit();
                    
                }
                
            }

        }

// Eliminar la busqueda //
        if (isset($_POST['eliminar_buscador'])) {
            unset($_SESSION[$modulo_buscador]);
                header("location: index.php?vista=$modulos_url",true,303);
                        exit();
            
        }

    } else {
        echo '
        <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        NO SE PUDO PROCESAR LA INFORMACION.
        </div>';
        
        
    }
    