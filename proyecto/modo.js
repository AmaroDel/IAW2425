document.getElementById("flexSwitchCheckChecked").addEventListener("change", function () {
    if (this.checked) {
        localStorage.setItem("fondoDocumento", "black");
        localStorage.setItem("colorLetra", "white");
        localStorage.setItem("switchSel", "true");
    } else {
        localStorage.setItem("fondoDocumento", "white");
        localStorage.setItem("colorLetra", "black");
        localStorage.setItem("switchSel", "false");
    }
    cargar();
});

function cargar() {
    let fondoDocumento = localStorage.getItem("fondoDocumento");
    let colorLetra = localStorage.getItem("colorLetra");
    let seleccionado = localStorage.getItem("switchSel");

    if (fondoDocumento && colorLetra) {
        document.body.style.backgroundColor = fondoDocumento;
        document.body.style.color = colorLetra;
        document.getElementById("flexSwitchCheckChecked").checked = JSON.parse(seleccionado);
    }
}

cargar();
