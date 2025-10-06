<?php
 session_start(); 

 if (!isset($_SESSION["psicologos"])) {
    header("location: ../views/auth/login.php");
 }

include("../controllers/connection.php");

$idPatient = $_GET["id"];

//delete data
$deletePatient = "DELETE FROM pacientes WHERE pacientes.idPaciente='$idPatient'";

try {    
    $wipeResult = mysqli_query($connection, $deletePatient);
    if ($wipeResult) {
        header("location: ../views/patients.php");
    } else {
        echo "<script>alert ('No se pudo eliminar'); window.history.go(-1); </script>";
    }    
 } catch (mysqli_sql_exception $e) {
     throw $e;
}