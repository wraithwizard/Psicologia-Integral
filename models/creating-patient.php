<?php
session_start(); 

if (!isset($_SESSION["psicologos"])) {
    header("location: ../views/auth/login.php");
}

include ("../controllers/connection.php");
include ("../controllers/alerts.php");

$nombre = $_POST["name"];
$apellido = $_POST["lastName"];
$telefono = $_POST["telephone"];
$email = $_POST["email"];
$gender = $_POST["gender"];
$birthday = $_POST["birthday"];
$medicHistory = $_POST["medicalHistory"];
$objective = $_POST["objective"];
$hour = $_POST["hour"];

// checks for duplicates
$checkQuery = "SELECT COUNT(*) as count FROM pacientes WHERE email = '$email'";
$result = mysqli_query($connection, $checkQuery);

$alert = new Alerta;

try{
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $userExists = $row["count"] > 0;     

        if ($userExists) {
            echo '<script type="text/javascript"> $(document).ready(function(){
                swal({
                    icon: "error",
                    text: "El usuario ya existe",
                    button: true,
                    button: "Regresar",
                    background: "#262626",
                }).then(function(){
                    window.location.href="../views/create-patient.php";
                })
            }); 
            </script>';     
        }else{
            // insert new user in DB
            $insertPatient = "INSERT INTO pacientes (nombre, apellido, email, telefono, genero, fechaNacimiento, antecedentesMedicos, objetivo, disponibilidadHoraria) VALUES ('$nombre', '$apellido', '$email', '$telefono', '$gender', '$birthday','$medicHistory', '$objective', '$hour')";

            $patientCreation = mysqli_query($connection, $insertPatient);
            if ($patientCreation) {
                // succesful alert
                $alert->standardAlert("../views/patients.php", "Registro de cliente exitoso");
            }else{
                // go back 
                echo "<script>alert('No se pudo registrar al cliente'); window.history.go(-1);</script>";
            }
        }           
    }    
} catch (mysqli_sql_exception $f) {
    throw $f;   
}


