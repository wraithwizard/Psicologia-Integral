<?php
session_start(); 

if (!isset($_SESSION["psicologos"])) {
    header("location: ../views/auth/login.php");
}

include("../controllers/connection.php");
include("../controllers/alerts.php");

//data
$id = $_POST["updateId"];
$name = $_POST["updateName"];
$apellido = $_POST["updateLastName"];
$telephone = $_POST["updateTelephone"];
$email = $_POST["updateEmail"];
$gender = $_POST["updateGenero"];
$birthDate = $_POST["updateDate"];
$medicalHistory = $_POST["updateAntecedentes"];
$objective = $_POST["updateOjetivo"];
$hour = $_POST["updateHour"];
//gets the thrown id
$fetchedId = $_GET["idUpdatedPatient"];

try {
    if (isset($id)) {
        $update = "UPDATE pacientes SET nombre='$name', apellido='$apellido', email='$email', telefono='$telephone', genero='$gender', fechaNacimiento='$birthDate', antecedentesMedicos='$medicalHistory', objetivo='$objective', disponibilidadHoraria='$hour' WHERE idPaciente='$id'";
        $result = mysqli_query($connection, $update);   
    }
    if($result){      
        //my alert
        $alerta = new Alerta();  
        $alerta->updateSuccess("../views/patient-data.php", "Actualización exitosa", $fetchedId);
    }else{
        // go back
        echo '<script type="text/javascript"> $(document).ready(function(){
            swal({
                icon: "error",
                text: "No se pudo actualizar",
                button: true,
                button: "Regresar",
                background: "#262626",
            }).then(function(){
                window.history.go(-1)";
            })
        }); 
        </script>';
    }
} catch (mysqli_sql_exception $e) {
    throw $e;   
}