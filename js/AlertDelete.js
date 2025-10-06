function confirmacion(evento){
    //mostar alerta
    if (confirm("¿Seguro que desea eliminar?")){
        return true;
    }else{
        //cancelar evento
        evento.preventDefault();
    }
}

let claseABorrar = document.querySelectorAll(".btn-eliminar");

//detectar por cada click
for (let i = 0; i < claseABorrar.length; i++) {
   claseABorrar[i].addEventListener('click', confirmacion);    
}