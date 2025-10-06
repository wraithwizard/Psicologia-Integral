function divAlert(msg, type){
    // check if it already exists
    const prevAlert = document.querySelector(".alerta");
    if (prevAlert) {
        return;
    }

    const alert = document.createElement("DIV");
    alert.textContent = msg;
    alert.classList.add("alerta");
    alert.classList.add(type);

    const baseDiv = document.querySelector(".dropdown-btn");
    baseDiv.appendChild(alert);

    // remove after some time
    setTimeout(() =>{
        alert.remove();
    }, 10000);
}