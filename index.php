<?php require "./incluir/sesion_start.php"; ?>
<!DOCTYPE html>
<html>
    <head>
        <?php include "./incluir/head.php"; ?>
    </head>
    <body>
        <?php

            if(!isset($_GET['vista']) || $_GET['vista']==""){
                $_GET['vista']="login";
            }


            if(is_file("./vistas_html/".$_GET['vista'].".php") && $_GET['vista']!="login" && $_GET['vista']!="404"){

                /*== Cerrar sesion ==*/
                if((!isset($_SESSION['id']) || $_SESSION['id']=="") || (!isset($_SESSION['usuario']) || $_SESSION['usuario']=="")){
                    include "./vistas_html/logout.php";
                    exit();
                }

                include "./incluir/navbar.php";

                include "./vistas_html/".$_GET['vista'].".php";

                include "./incluir/scripts.php";

            }else{
                if($_GET['vista']=="login"){
                    include "./vistas_html/login.php";
                }else{
                    include "./vistas_html/404.php";
                }
            }
        ?>
    </body>
</html>
