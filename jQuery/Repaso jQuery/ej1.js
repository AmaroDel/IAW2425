/* Rellena este fichero */
$(document).ready(function () {
    // Cuando el sitio web se haya cargado por completo
    $("#btn-ocultar").click(function () {
        $("p").hide();
    })
    $("#btn-mostrar").click(function () {
        $("p").show();
    })
});