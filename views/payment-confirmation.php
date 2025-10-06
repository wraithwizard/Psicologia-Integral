<?php
session_start();

if (!isset($_SESSION["patients"])) {
    header("location: auth/login.php");
    exit();
}

include("../controllers/connection.php");
include("../controllers/alerts.php");
include("../controllers/Email.php");

// get hour, date, service id and email
$hora = $_GET["hour"];    
$timestamp = strtotime($hora);

$time = date("H:i", $timestamp);
$fecha = $_GET["fecha"];
$serviceId = $_GET["serviceId"];
$email = $_GET["email"];
$modalidad = $_GET["modalidad"];

// echo ("la fecha: " . $fecha);
// echo $time;
// echo ("servicio: " . $serviceId);
// echo ("email: " . $email);
// echo ("<br>modalidad: " . $modalidad);

$alert = new Alerta();

//si los valores no son nulos, continuar. Esto para evitar que el usuario spamee la DB
if (isset($time) && isset($fecha) && isset($serviceId) && isset($email)) {
    // check for duplicates inside citas table, again, this is to avoid user spamming
    $duplicatesCheckQuery = "SELECT COUNT(fecha AND hora) as count FROM citas WHERE fecha = ? AND hora = ?";
    // prepare
    $result = mysqli_prepare($connection, $duplicatesCheckQuery);
    if ($result){
        // bind & execute
        $ok = mysqli_stmt_bind_param($result, "ss", $fecha, $time);
        $ok = mysqli_stmt_execute($result);
        $ok = mysqli_stmt_bind_result($result, $count);
        $ok = mysqli_stmt_fetch($result);
        mysqli_stmt_close($result);

        if ($count > 0) {
           $alert->standardAlert("#", "Duplicado encontado, contactar al administrador");
        }else{
            //get the idPaciente from email so i can get all data with another query
            $knowPacienteId = "SELECT idPaciente FROM pacientes WHERE email = ?";
            // prepare
            $emailResult = mysqli_prepare($connection, $knowPacienteId);
            if ($emailResult){
                // bind & execute
                $ok3 = mysqli_stmt_bind_param($emailResult, "s", $email);
                $ok3 = mysqli_stmt_execute($emailResult);
                $ok3 = mysqli_stmt_bind_result($emailResult, $idPaciente);
                // fetch the result so I can know the idPaciente
                $ok3 = mysqli_stmt_fetch($emailResult);             
                mysqli_stmt_close($emailResult);
            }    

            // insertar valores en DB
            $insertQuery = "INSERT INTO citas (fecha, hora, idPaciente, idServicio, modalidad) VALUES (?, ?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($connection, $insertQuery);
            if ($stmt2){
                $ok2 = mysqli_stmt_bind_param($stmt2, "ssiis", $fecha, $time, $idPaciente, $serviceId, $modalidad); 
                $ok2 = mysqli_stmt_execute($stmt2);
                // get the idCita
                $idCita = mysqli_insert_id($connection);
                mysqli_stmt_close($stmt2);  
                
                // receive email data form db so i can send an email with its data
                $retrieveBuyerData = "SELECT citas.fecha, citas.hora, citas.modalidad, servicios.nombre AS serviceName, pacientes.nombre AS pacienteNombre, pacientes.apellido, pacientes.email 
                FROM citas 
                LEFT JOIN pacientes ON citas.idPaciente = pacientes.idPaciente 
                LEFT JOIN servicios ON citas.idServicio = servicios.idServicio 
                WHERE citas.idCita = ? LIMIT 1";
                // prepare
                $stmt = mysqli_prepare($connection, $retrieveBuyerData);
                // bind & execute
                if ($stmt){
                    $retriever = mysqli_stmt_bind_param($stmt, "i", $idCita);
                    $retriever = mysqli_stmt_execute($stmt);   

                    $resultado = mysqli_stmt_get_result($stmt);     
                    
                    // get patient name for email
                    if ($row = mysqli_fetch_assoc($resultado)){
                        $patientName = $row["pacienteNombre"];
                        $serviceName = $row["serviceName"];
                    }
                    
                    // gets me all the query values
                    // if ($row = mysqli_fetch_assoc($resultado)) {
                    //     // process and display results
                    //     foreach ($row as $column_name => $value) {
                    //         echo $column_name . ": " . $value . "<br>";
                    //     }
                    // }else{
                    //     echo ("<br>no results found of idCita");
                    // }
                }else{
                    echo ("error preparing statements:  ");
                }

                mysqli_stmt_close($stmt);

                // enviar email con detalles de reservacion
                $tokenForConstructor = 0;
                $phpMailer = new Email($email, $patientName, $tokenForConstructor);
                $phpMailer->SendReservationDetails($fecha, $time, $serviceName, $modalidad);
                
                // echo ("datos para el email: " . $email . "<br>nombre: " . $patientName . "<br>token: " . $tokenForConstructor . "<br>fecha: " . $fecha . "<br>hora: " . $time . "<br>servicio: " . $serviceName . "<br> modalidad: " . $modalidad);
            }   
        }
    }
}else{
    echo ("error, datos incompletos");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/favicon.ico">
    <title>Maestra en Psicoterapia Belinda Chávez</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans&display=swap" rel="stylesheet">  
</head>
<body>
    <header>
        <div class="top-div main-nav">    
            <a href="https://www.facebook.com/constelacionesfamiliarestijuana" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-facebook" width="44" height="44" viewBox="0 0 24 24" stroke-width="2" stroke="#F0E2EE" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
                  </svg>
            </a>
            <a href="#" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-instagram" width="44" height="44" viewBox="0 0 24 24" stroke-width="2" stroke="#F0E2EE" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M4 4m0 4a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
                    <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                    <path d="M16.5 7.5l0 .01" />
                </svg>
            </a>
            <!-- BURGER TIME -->
            <ul class="nav-links">               
                <li><a class="link" href="../index.html">Inicio</a></li><hr>
                <li><a class="link" href="../sobre-mi.html">Sobre mi</a></li><hr>      
                <li><a class="link" href="auth/login.php">Pide una cita</a></li><hr>         
            </ul>
            <!-- burger -->
            <div class="burger" id="burger">
                <div class="line line1"></div>
                <div class="line line2"></div>
                <div class="line line3"></div>
            </div>     
        </div>
    </header>    
   
    <main>
        <div class="stars">
            <img class="logo" src="../img/mainLogo.jpg" alt="logo">
            <nav class="header-menu">
                <a href="sobre-mi.html" class="header-menu__link">Sobre mi</a>              
                <a href="views/auth/login.php" class="header-menu__link">Pide una cita</a>            
            </nav>
        
            <div class="description">
                <h1 class="name">Mtra. en Psicoterapia Belinda Chávez</h1>
                <h2 class="description-phrase">Muévete... <br>y que se mueva lo que se tenga que mover</h2>
                <p class="description_txt">Psicoterapia Clínica <br> Constelaciones Familiares </p>
            </div>
        </div>

        <div class="main-div">
            </div cllass="main-text">
                <p class="main-text__comprado">¡Gracias por tu compra! Te hemos enviado un correo electrónico con los detalles.</p>              
            </div>
        </div>                 
    </main>

    <?php include("footer.php"); ?>

    <script src="../js/Burger.js"></script>
</body>
</html>