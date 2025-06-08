$(document).ready(function(){
    $(".campo-busqueda").on("keyup", function(){
        let query = $(this).val();

        if (query.length >= 2) {
            $.ajax({
                url: "/Harvey-s/elementos/busqueda/buscar_categoria.php",
                type: "GET",
                data: { search: query },
                success: function(data){
                    let categorias = JSON.parse(data);
                    let listaSugerencias = "";

                    categorias.forEach(function(item) {
                        listaSugerencias += `<div class="sugerencia-item">
                            <a href="${item.url}">${item.nombre}</a>
                        </div>`;
                    });

                    $("#sugerencias").html(listaSugerencias).show();
                }
            });
        } else {
            $("#sugerencias").hide();
        }
    });

    $(document).on("click", function(e) {
        if (!$(e.target).closest("#busqueda").length) {
            $("#sugerencias").hide();
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const inputBusqueda = document.querySelector(".campo-busqueda");
    const listaSugerencias = document.querySelector(".lista-sugerencias");

    listaSugerencias.style.display = "none";

    inputBusqueda.addEventListener("input", function () {
        if (inputBusqueda.value.trim() === "") {
            listaSugerencias.style.display = "none";
        } else {
            listaSugerencias.style.display = "block";
        }
    });
});