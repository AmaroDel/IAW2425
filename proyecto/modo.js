// Verificar si el modo oscuro está activado en localStorage
if (localStorage.getItem('modoOscuro') === 'true') {
    document.body.classList.add('modo-oscuro');
}

// Alternar el modo oscuro cuando el usuario haga clic en el botón o enlace
document.getElementById('modo-oscuro').addEventListener('click', function(e) {
    e.preventDefault(); // Prevenir la acción por defecto del enlace

    // Alternar la clase 'modo-oscuro' en el body
    document.body.classList.toggle('modo-oscuro');

    // Guardar o eliminar la preferencia de modo oscuro en localStorage
    if (document.body.classList.contains('modo-oscuro')) {
        localStorage.setItem('modoOscuro', 'true');
        this.textContent = "Desactivar Modo Oscuro"; // Cambiar el texto del botón
    } else {
        localStorage.setItem('modoOscuro', 'false');
        this.textContent = "Activar Modo Oscuro";
    }
});

// Al cargar la página, ajustar el texto del enlace según el estado
document.addEventListener('DOMContentLoaded', function() {
    if (document.body.classList.contains('modo-oscuro')) {
        document.getElementById('modo-oscuro').textContent = "Desactivar Modo Oscuro";
    }
});
