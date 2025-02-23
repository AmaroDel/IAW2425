document.addEventListener("DOMContentLoaded", function () {
    const botonModoOscuro = document.getElementById("modo-oscuro");
    const modoOscuroActivado = localStorage.getItem("modoOscuro") === "true";

    // Aplicar el modo oscuro si está activado en localStorage
    if (modoOscuroActivado) {
        document.body.classList.add("modo-oscuro");
        botonModoOscuro.textContent = "Desactivar Modo Oscuro";
    } else {
        botonModoOscuro.textContent = "Activar Modo Oscuro";
    }

    // Alternar el modo oscuro cuando se haga clic en el botón
    botonModoOscuro.addEventListener("click", function (e) {
        e.preventDefault(); // Evitar la acción por defecto del enlace

        document.body.classList.toggle("modo-oscuro"); // Alternar la clase en el body
        const nuevoEstado = document.body.classList.contains("modo-oscuro");

        // Guardar el estado en localStorage
        localStorage.setItem("modoOscuro", nuevoEstado);

        // Actualizar el texto del botón
        botonModoOscuro.textContent = nuevoEstado
            ? "Desactivar Modo Oscuro"
            : "Activar Modo Oscuro";
    });
});

