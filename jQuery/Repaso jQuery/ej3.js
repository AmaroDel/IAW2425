/* Rellena este fichero */
$(document).ready(function () {
    // Cuando el sitio web se haya cargado por completo
    $("#btn-aumentar").click(function () {
        $(".pares,h1").css({"color": "red", "font-size": "200%"});
    })
    $("#btn-disminuir").click(function () {
        $(".pares,h1").css({"color": "black", "font-size": "50%"});
    })
});