$(document).ready(function(){
    switch (true) {
        case window.location.href.includes("bebidas.php"):
            $('h1:has(a[href*="bebidas.php"])').hide();
            break;
        case window.location.href.includes("carnes.php"):
            $('h1:has(a[href*="carnes.php"])').hide();
            break;
        case window.location.href.includes("conservas.php"):
            $('h1:has(a[href*="conservas.php"])').hide();
            break;
        case window.location.href.includes("desayunos.php"):
            $('h1:has(a[href*="desayunos.php"])').hide();
            break;
        case window.location.href.includes("frutas_verduras.php"):
            $('h1:has(a[href*="frutas_verduras.php"])').hide();
            break;
        case window.location.href.includes("lacteos.php"):
            $('h1:has(a[href*="lacteos.php"])').hide();
            break;
        case window.location.href.includes("panaderia.php"):
            $('h1:has(a[href*="panaderia.php"])').hide();
            break;
    }
});
