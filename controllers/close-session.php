<?php 
session_start();
 
//comprobar que un usuario registrado es el que accede al archivo
if (!isset($_SESSION["psicologos"]) || !isset($_SESSION["patients"])) {
    header("location: ../views/auth/login.php"); 
}
 
//session_unset() libera la variable de sesión que se encuentra registrada 
session_unset();
 
// Destruye la información de la sesión
session_destroy();
 
//volver al login
header("location: ../views/auth/login.php"); 
?>