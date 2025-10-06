<?php
session_start();

if (!isset($_SESSION["psicologos"])){
    header("location: ../views/auth/login.php");
}

include ("../controllers/connection.php");
include ("../controllers/alerts.php");

// confirmacion para cambiar horario
// recibir datos del post
$day = $_GET["date"];
$todoElDia = $_GET["allDay"];

$alert = new Alerta();

if (empty($_GET["hour"])){
    // query to check if the day is already blocked
    $queryToAvoidDuplicates = "SELECT COUNT(fecha) AS count  FROM diasBloqueados WHERE fecha = ?";
    $resultado = mysqli_prepare($connection, $queryToAvoidDuplicates);
    if ($resultado) {
        $checks = mysqli_stmt_bind_param($resultado, "s", $day);
        $checks = mysqli_stmt_execute($resultado);
        $checks = mysqli_stmt_bind_result($resultado, $count);
        $checks = mysqli_stmt_fetch($resultado);
        mysqli_stmt_close($resultado);

        if ($count > 0){
            $alert->standardAlert("config-logic.php", "El dia ya esta bloqueado");
        }else{
            //  query for inserting all day in diasBloqueados
            $bloqueado = 1;
            $blockDayQuery = "INSERT INTO diasBloqueados (fecha, bloqueado) VALUES (?, ?)"; 
            $result = mysqli_prepare($connection, $blockDayQuery);
            if ($result){
                $ok = mysqli_stmt_bind_param($result, "si", $day, $bloqueado);
                $ok = mysqli_stmt_execute($result);
                mysqli_stmt_close($result);
                $alert->standardAlert("../views/admin/config.php", "Horarios del día: $day bloqueados");
            }
        }  
    }
}else{
    $hour = $_GET["hour"];
    // insert query por hora y fecha en la tabla citas
    $pacienteAdmin = 75;
    $servicioAdmin = 1;
    $modalidad = "online";
    $blockHourQuery = "INSERT INTO citas (fecha, hora, modalidad, idPaciente, idServicio) VALUES (?, ?, ?, ?, ?)";
    $result2 = mysqli_prepare($connection, $blockHourQuery);
    if ($result2) {
        $go = mysqli_stmt_bind_param($result2, "sssii", $day, $hour, $modalidad, $pacienteAdmin, $servicioAdmin);
        $go = mysqli_stmt_execute($result2);
        mysqli_stmt_close($result2);
        $alert->standardAlert("../views/admin/config.php", "Hora bloqueada exitosamente");
    }else{
        $alert->standardAlert("../views/admin/config-logic.php", "Error al bloquear hora");
    }
}

?>