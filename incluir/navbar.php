
<nav class="navbar" role="navigation" aria-label="main navigation">

    <div class="navbar-brand">
        <a class="navbar-item" href="index.php?vista=home">
        <img src="./img/logo_rojo.png" width="65" height="28">
        </a>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">
        <?php if (isset($_SESSION['rol_nombre']) && $_SESSION['rol_nombre'] === 'administrador') { 
            ?>
                
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">Usuarios</a>
                    <div class="navbar-dropdown">
                        <a class="navbar-item" href="index.php?vista=user_new">Nuevo</a>
                        <a class="navbar-item" href="index.php?vista=user_list">Lista</a>
                        <a class="navbar-item" href="index.php?vista=user_search">Buscar</a>
                    </div>
                </div>

                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">Roles de usuario</a>
                    <div class="navbar-dropdown">
                        <a href="index.php?vista=rol_new" class="navbar-item">Nuevo Rol</a>
                        <a href="index.php?vista=list_rol" class="navbar-item">Lista de roles</a>
                    </div>
                </div>
            <?php } ?>
           
        
                <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Productos</a>
                <div class="navbar-dropdown">
                    <a href="index.php?vista=product_new" class="navbar-item">Nuevo</a>
                    <a href="index.php?vista=product_list" class="navbar-item">Lista</a>
                    <a href="index.php?vista=product_search" class="navbar-item">Buscar</a>
                    <a href="index.php?vista=product_category" class="navbar-item">Por categoría</a>
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Categoría</a>
                <div class="navbar-dropdown">
                    <a href="index.php?vista=category_new" class="navbar-item">Nueva</a>
                    <a href="index.php?vista=category_list" class="navbar-item">Lista</a>
                    <a href="index.php?vista=category_search" class="navbar-item">Buscar</a>
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Baja de productos</a>
                <div class="navbar-dropdown">
                    <a href="index.php?vista=product_new_down" class="navbar-item">Nueva baja</a>
                    <a href="index.php?vista=product_list_down" class="navbar-item">Lista de bajas</a>
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Proveedores</a>
                <div class="navbar-dropdown">
                    <a href="index.php?vista=provee_new" class="navbar-item">Nuevo</a>
                    <a href="index.php?vista=provee_list" class="navbar-item">Lista</a>
                    <a href="index.php?vista=provee_search" class="navbar-item">Buscar</a>
                </div>
            </div>
           

        </div>

      <div class="navbar-end">
          <div class="navbar-item">
        <div class="buttons">
          <a href="index.php?vista=user_update&user_id_up=<?php echo $_SESSION['id'];?>" class="button is-primary is-rounded">
            Mi cuenta
          </a>
          <a href="index.php?vista=logout" class="button is-link is-rounded">
            Salir
          </a>
        </div>
      </div> 
    </div>
  </div>
</nav>
