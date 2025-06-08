<?php
    $current_page = basename($_SERVER['PHP_SELF']);
?>
<header>  
    <link rel="stylesheet" href="../elementos/css/css_busqueda.css">
    <link rel="stylesheet" href="../elementos/css/css_productos.css">
    <link rel="stylesheet" href="../elementos/css/css_footer.css">

    <div>
        <a href="/Harvey-s/layout/home.php">
            <img src="/Harvey-s/elementos/pics/Harveys_logo.png" alt="Logo de Harvey's">
        </a>

        <button class="secciones" type="button">
            <i class="fas fa-bars"></i>
            Secciones
        </button>

        <form id="busqueda" action="#" method="get">
            <input class="campo-busqueda" type="text" placeholder="Buscar en Harvey's" name="search" required autocomplete="off">
            <button class="btn-busqueda" type="submit">
                <i class="fas fa-search"></i>
            </button>
            <div id="sugerencias" class="lista-sugerencias"></div>
        </form>

        <form action="/Harvey-s/secciones/faq.php">
            <button class="btn-faq" type="submit">
                <i class="fas fa-question"></i>
            </button>
        </form>

        <form action="">
            <button class="btn-login" type="submit">
                <i class="fas fa-user"></i>
            </button>
        </form>

        <?php 
            if ($current_page !== 'detalle_compra.php') : 
        ?>
        <form action="">
            <button class="btn-carrito" type="submit">
                <i class="fas fa-shopping-cart"></i>
                Carrito
            </button>
        </form>
        <?php 
            endif; 
        ?>
    </div>

    <hr>

</header>
