<header>     
    <div>
        <a href="/Harvey-s/layout/home_logged.php">
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

        <form action="/Harvey-s/secciones/secciones_logged/faq.php">
            <button class="btn-faq" type="submit">
                <i class="fas fa-question"></i>
            </button>
        </form>

        <form action="">
            <button class="btn-login" type="submit">
                <i class="fas fa-user"></i>
            </button>
        </form>

        <form action="">
            <button class="btn-carrito" type="submit">
                <i class="fas fa-shopping-cart"></i>
                Carrito
            </button>
        </form>
    </div>

    <hr>

</header>
