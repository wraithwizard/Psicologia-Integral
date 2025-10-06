// handles the clicks on buttons
let step = 1;
// page nav buttons
const previousPage = document.querySelector("#go-back");
const nextPage = document.querySelector("#go-forward");
const firstStep = 1;
const lastStep = 3;

let cards= document.querySelectorAll(".card-container");

const cesta = {
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

const servicesDetails = {
    id: null, name: null, price: null
};

let selectedDay, day = null;
const selectedHour = document.querySelector("#hour");

// get book btn 
const pageDiv = document.querySelector(".page");
const bookBtn = document.createElement("BUTTON");

let correo;
let modalidadSelect = document.querySelector("#modalidad");
const selectDate = document.querySelector("#date");
let isSunday = false;


// wait for document to be loaded
document.addEventListener("DOMContentLoaded", function() {
    correo = window.userEmail;
    startApp();    
})

function startApp(){
    showSection();
    tabs();
    pageNav();
    goNextPage();
    goPreviousPage();   

    // show descriptions
    getCard(); 
    getServicesAndChangeToDate();
    getUserName();

    selectingDate()   
    selectHour();  
    showCart();
    getModalidad();
}

// changes section when pressed and works as the Update
function tabs(){
    // gets all the buttons under class
    const buttons = document.querySelectorAll(".tabs button");

    buttons.forEach (buton => {
        buton.addEventListener("click", function (e) {
            e.preventDefault();
            step = parseInt(e.target.dataset.step);

            showSection();
            pageNav();
        });
    })
}

function showSection(){
    // hide section
    const beforeSection = document.querySelector(".show");
    if (beforeSection) {
        beforeSection.classList.remove("show");
    }

    // select section with step
    const stepSelector = "#step" + step;
    const section = document.querySelector(stepSelector);
    section.classList.add("show");
}

function pageNav(){ 
    // page nav buttons
    const previousPage = document.querySelector("#go-back");
    const nextPage = document.querySelector("#go-forward");

    if (step === 1){
        previousPage.classList.add("hidePageBtn");
        nextPage.classList.remove("hidePageBtn");
        // removes reservation button to avoid duplicates
        if (bookBtn && pageDiv.contains(bookBtn)){
            bookBtn.remove();
        }

        //console.log("paso 1")
    }else if (step === 3){
        previousPage.classList.remove("hidePageBtn");
        nextPage.classList.add("hidePageBtn");
        showCart();
        //console.log("paso 3")

    }else{
        previousPage.classList.remove("hidePageBtn");
        nextPage.classList.remove("hidePageBtn");
        // removes reservation button to avoid duplicates       
        if (bookBtn && pageDiv.contains(bookBtn)){
            bookBtn.remove();
        }

        //console.log("paso 2")
    }

    showSection();   
}

function goPreviousPage(){
    previousPage.addEventListener("click", function() {
        if (step <= firstStep) return;

        step--;
        pageNav();
    })
}

function goNextPage(){
    nextPage.addEventListener("click", function() {
        if (step >= lastStep) return;

        step++;
        pageNav();
    })
}

// must toggle the dropdown content
function getCard(){
    var acc = document.getElementsByClassName("card-container");  
   
    for (let i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {    
        /* Toggle between hiding and showing the active panel */
        var panel = this.parentNode.querySelector(".dropdown");

        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            // show corresponding description
            panel.style.display = "block";
        }
      });      
    }         
}

// idService, nombre & precio gets the data as parameters
function getServicesAndChangeToDate(idService, nombre, precio){  
    // get the views
    const dateView = document.getElementById("step2");
    const servicesView = document.getElementById("step1");  

    // for creating the divs on data view
    const serviceInfoDiv = document.createElement("div");
    const dateLabel = document.getElementById("step2__services-info");
    const divForm = document.querySelector(".login");

    // change the views
    dateView.classList.toggle("show");   
    servicesView.classList.remove("show");  

    // get the service data    
    servicesDetails.id = idService;
    servicesDetails.name = nombre;
    servicesDetails.price = precio;

    // updates the div and checks that are not undefined/null
    if  (servicesDetails.name || servicesDetails.price){
        cesta.servicios = [{
            nombre: servicesDetails.name,
            precio: servicesDetails.price
        }]
        // Handle presentation separately
        serviceInfoDiv.innerHTML = `
        Servicio: <span class='normal'>${servicesDetails.name}</span><br>
        Precio: <span class='normal'>$${servicesDetails.price}</span>`;    
        
        // appends the div
        dateLabel.innerHTML = ""; // clears the content
        dateLabel.appendChild(serviceInfoDiv);    
        serviceInfoDiv.classList.add("cart");

        // Replace the old service with the new one
        cesta.servicios = [serviceInfoDiv.innerHTML];    

        // avoid duplicates
        const newService = serviceInfoDiv.innerHTML;
        if (!cesta.servicios.includes(newService)) {
            cesta.servicios = [...servicios, newService];
        }

        // disables option tag second option
        showOnlyPresencialOptionForConstelacionesFamiliares(idService);

        getServiceForPayPal(servicesDetails.id, servicesDetails.name)
    }     

    const btns = document.querySelectorAll(".dropdown-btn");
    btns.forEach((btn) => {
        btn.addEventListener("click", () => {                
        });
    })     
}

function getUserName(){
    cesta.nombre = document.querySelector("#name").value;
}

//function selectingDate(){
function regularDateHandler(e){ 
        // get the day as number of the week
        day = new Date(e.target.value).getUTCDay();
        // working days (0 is sunday)
        if ([6, 0, 1, 5].includes(day)) {
            e.target.value = "";
            showAlert("Por favor elije un día de Martes a Jueves.", "#step2", ".modalidad");
            // reset hour if day changes
        }else if (selectedDay !== null && day !== selectedDay){          
            selectedHour.value = "";
        }

        cesta.fecha = selectDate.value;   
        // lets get the day
        selectedDay = day;      
        
        checarDuplicadosPorHoraYFecha(e.target);        
}

function selectHour(){   
    selectedHour.addEventListener("input", function(e) {
        let horaCita = e.target.value;
        // splits the hour txt and gives only the hour
        //const hour = horaCita.split(":")[0];        
        let [hour, minute] = horaCita.split(":");
        // make minutes :00 always
        if (minute != "00") {
            minute = "00";
            horaCita = `${hour}:${minute}`;
            e.target.value = horaCita;
        }

        // schedule
        if (day === 2 && (hour < 11 || hour > 18)){
            e.target.value = ""; // resets the hour input
            showAlert("Hora no válida, elija una hora entre 11AM a 6PM.", "#step2", ".modalidad");     
        }else if (day === 3 && (hour < 16 || hour > 18)){
            e.target.value = "";
            showAlert("Hora no válida, elija una hora entre 4PM a 6PM.", "#step2", ".modalidad");   
        }else if (day === 4 && (hour < 11 || hour > 14)){
            e.target.value = "";
            showAlert("Hora no válida, elija una hora entre 11AM a 2PM.", "#step2", ".modalidad"); 
        }else if (day == 0 && (hour < 10 || hour > 17)){ // constelaciones familiares grupales
            e.target.value = "";
            showAlert("Hora no válida, elija una hora entre 10AM a 5PM.", "#step2", ".modalidad"); 
        }else{
            cesta.hora = e.target.value;

            checarDuplicadosPorHoraYFecha(e.target);
        }           
    })   
}

function showCart(formattedDate){
    const cart = document.querySelector(".summary");
    const paypalBtn = document.querySelector(".paypal-container");

    // if cart is empty, show alert
    if ((Object.values(cesta).includes("") || cesta.servicios.length === 0) && step == 3){
        showAlert("Faltan datos de servicio, fecha u hora.", "#step3");
        return;
    }

    // clear the cart to avoid duplicates
    cart.innerHTML = "";

    // creates the span elements
    const cartName = document.createElement("P");
    cartName.innerHTML =`<span>Nombre:</span><span class="normal"> ${cesta.nombre}</span>`;
    // annexes the css class
    cartName.classList.add("cart");
    const cartDate = document.createElement("P");

    // formats the date
    formattedDate = dateFormat();
    if (formattedDate !== null){
        cartDate.innerHTML =`<span>Fecha:</span><span class="normal"> ${formattedDate}</span>`;
    }

    cartDate.classList.add("cart");
    const cartHour = document.createElement("P");
    cartHour.innerHTML =`<span>Hora:</span><span class="normal"> ${cesta.hora}</span><br><span>Zona horaria:</span><span class="normal"> Pacífico</span>`;
    cartHour.classList.add("cart");

    // modalidad
    const cartModality = document.createElement("P");
    cartModality.innerHTML = `<span>Modalidad:</span><span class="normal"> ${modalidadSelect.value}</span>`;
    cartModality.classList.add("cart");
    
    if (cesta.servicios === ""  || cesta.nombre === "" || cesta.hora === ""){
        return;
    }

    bookBtn.classList.add("btn", "page", "reservar");   
    bookBtn.textContent = "Reservar Cita";
    bookBtn.onclick = sendToStripe;

    let idForPaypal = getServiceForPayPal()

    // avoids duplicates of btn on other pages and shows respective buttons (paypal or stripe)
    if (step === 3 && idForPaypal[0] !== 5) {
        pageDiv.appendChild(bookBtn);      
    } else if (step === 3 && idForPaypal[0] === 5) {
        //append paypay btn
        paypalBtn.style.display = "block";
    }

    cart.appendChild(cartName);
    cart.appendChild(cartDate);
    cart.appendChild(cartHour);
    cart.appendChild(cartModality);

    // makes cart to accept only 1 service
    if (cesta.servicios.length > 0){
        const cartService = document.createElement("P");
        cesta.servicios = [{
            nombre: servicesDetails.name,
            precio: servicesDetails.price
        }]     

        // Handle presentation separately and avoids json html text
        cartService.innerHTML = `
                Servicio: <span class="normal">${servicesDetails.name}</span><br>
                Precio: <span class="normal">$${servicesDetails.price}</span>
        `;      
        cartService.classList.add("cart");
        cart.appendChild(cartService);        
    }       
}

// makes date span more friendly
function dateFormat(){
    if (cesta.fecha !== null) {
        const date = new Date(cesta.fecha);
        const month = date.getMonth();
        const day = date.getDate() + 2;
        const year = date.getFullYear();
        const dateUTC=  new Date(Date.UTC(year, month, day));
        const options = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
        const formattedDate = dateUTC.toLocaleDateString('es-MX', options);
        return formattedDate;
    }else{
        console.log("date is null")
    } 
}

function getModalidad(){    
    modalidadSelect.addEventListener('change', function() {
    });    

    return modalidadSelect.value;
}

async function sendToStripe(){
    const data = {
        nombre: cesta.nombre,
        servicios: cesta.servicios.map(servicio  => servicio.nombre),
        hora: cesta.hora,
        fecha: cesta.fecha,
        id: servicesDetails.id,
        email: correo, 
        modalidad: modalidadSelect.value
    }
    
    try{
        // post request options
        const requestOptions = {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(data)
        };
        
        // make the post request
        const response = await fetch("../controllers/Stripe.php", requestOptions)
        //check if request was successful
        if (!response.ok){
            throw new Error("Network response was not ok");
        }
        // parse the JSON response
        const datos = await response.json();

        // handles redirection
        if (datos.url){
            window.location.href = datos.url;
        }else{
            //console.log("Post requestresponse:  " +datos);
        }       

        // handle the data returned from the server
    } catch(error) {
        alert("Hubo un problema al comunicarse con el servidor. Por favor, inténtalo de nuevo.");
    }
}

async function checarDuplicadosPorHoraYFecha(element){
    const data = {         
        hora: cesta.hora,
        fecha: cesta.fecha,
    }      

    try{
        // post request options
        const requestOptions = {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(data)
        };
        
        const response = await fetch("../models/reservations.php", requestOptions); 
        
        if (!response.ok){
            throw new Error("Network response was not ok");
        }

        const result = await response.json()
        //console.log("respuesta del body com json: ", result );

        // Handle different responses
        if (result.status === "success" && cesta.hora !== null && cesta.fecha !== null) {
            return;
        } else if (result.status === "duplicate") {
            showAlert("Lo siento, esa hora ya está reservada. Por favor, elige otra.", "#step2", ".modalidad");
            //reset hour
            element.value = "";
            cesta.hora =  null;
        }else if (result.status === "blocked") {
            showAlert("Lo siento, ese día no laboramos.", "#step2", ".modalidad")
            // reset day
            element.value = "";
            cesta.fecha = null;
        } else {
            //showAlert("Hubo un error al reservar la cita. Por favor, inténtalo de nuevo.", "#step2", ".modalidad");
        }      
    } catch(error) {
            console.error("error", error);
            showAlert("Hubo un problema al comunicarse con el servidor. Por favor, inténtalo más tarde.", "#step2", ".modalidad");         
    }   
}

function showAlert(txt, element){
    // avoids duplicates
    const alertaPrevia = document.querySelector(".alerta");
    if (alertaPrevia) {
        return;
    }

    // creates the alert
    const alert = document.createElement("DIV");
    alert.textContent = txt;
    alert.classList.add("alerta");
    const form = document.querySelector(element);
    form.append(alert);
    // deletes the alert
    setTimeout(() => {
        alert.remove();
    }, 5000);    
}

function showOnlyPresencialOptionForConstelacionesFamiliares(idServicio){
    let optionToDisable = modalidadSelect.options[1];

    resetDataForSundays();

    // if service is constelaciones grupales familiares
    if (idServicio === 5 && isSunday === false) {
        modalidadSelect.value = "presencial";
        optionToDisable.disabled = true;

        console.log("id seleccionado: " +idServicio);

        isSunday = true;
    }else{
        optionToDisable.disabled = false;

        isSunday = false;        
    }

    selectingDate();
    
    //console.log("isSunday?: " +isSunday);
}

function resetDataForSundays(){
    // must reset the hour if other service was previously selected
    if (selectedHour) {
        selectedHour.value = "";
    }

    if (selectDate){
        selectDate.value = "";
    } 
    cesta.fecha = null;
    cesta.hora = null;
}

function sundayOnlyHandler(e){
    // get the day as number of the week
    day = new Date(e.target.value).getUTCDay();

    if (day !== 0){
        e.target.value = "";
        showAlert("Constelaciones Familiares Grupales son sólo los domingos.", "#step2", ".modalidad");
    }else{
        // If the day is valid, proceed with the rest of the logic
        cesta.fecha = selectDate.value;   
        // lets get the day
        selectedDay = day;   
        checarDuplicadosPorHoraYFecha(e.target);  
    }           
}     

function selectingDate(){
    // remove any listeners
    selectDate.removeEventListener("input", regularDateHandler);
    selectDate.removeEventListener("input", sundayOnlyHandler);

    if (!isSunday){
        selectDate.addEventListener("input", regularDateHandler);
    }else{
        selectDate.addEventListener("input", sundayOnlyHandler);
    }
}

function getServiceForPayPal(serviceId, serviceName){
    serviceId = servicesDetails.id;
    serviceName = servicesDetails.name;    
    return [serviceId, serviceName];
}

async function handlePayPalSuccess(){
    const paypalDataPayment = {
        nombre: cesta.nombre,
        servicios: cesta.servicios.map(servicio  => servicio.nombre),
        hora: cesta.hora,
        fecha: cesta.fecha,
        id: servicesDetails.id,
        email: correo, 
        modalidad: modalidadSelect.value
    }
    
    try{
        // post request options
        const requestParams = {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(paypalDataPayment)
        };
        
        // make the post request
        const respuesta = await fetch("../models/paypal-success.php", requestParams);

        //check if request was successful
        if (!respuesta.ok){
            throw new Error("Network response was not ok");
        }
        // parse the JSON response
        const datos = await respuesta.json();             

        // handles redirection
        if (datos.status === "success"){
            // redirect user
            window.location.href = datos.redirectUrl;
        } else {
            alert("Error al enviar los datos: " + datos.message);
        }       
        // handle the data returned from the server
    } catch(error){
        alert("Hubo un problema al comunicarse con el servidor. Por favor, inténtalo de nuevo.");
    }
}