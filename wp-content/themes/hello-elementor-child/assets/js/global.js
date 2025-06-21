
jQuery(document).ready(function($) {
    //console.log("🎨 global.js cargado correctamente");

    // Animación básica de entrada para productos o artistas
    $(".cuadro-producto, .artista-card").addClass("fade-in");

    // Scroll suave al hacer clic en cualquier botón con .scroll-a
    $(".scroll-a").click(function(e) {
        e.preventDefault();
        const target = $(this).attr("href");
        $("html, body").animate({
            scrollTop: $(target).offset().top
        }, 800);
    });
});
