<?php
session_start();

if (!isset($_SESSION["psicologos"])){
    header("location: ../views/auth/login.php");
}

include ("../controllers/connection.php");
include ("../controllers/alerts.php");

// recibir datos del post
$day = $_POST["date"];
$hour = $_POST["hour"];
$dayCheckbox = "no";
$todoElDIa = "no";

$alert = new Alerta();

// set minutos to 00
if ($hour){
    // subtraction starts from 0 position to 3 char, then concatenates the 00's or something like that
    $hour = substr($hour, 0, 3) . "00";
    echo "hora sin minutos = " . $hour;
}

if (isset($_POST["allDay"]) && $_POST["allDay"] === "yes"){
    $dayCheckbox = "yes";
    $todoElDIa = $dayCheckbox;
}

// variables for dynamically change the query of hour is selected
$params = [$day];
$type = "s";

// si la hora o el dia no estan seleccionados , enviar alerta de error
if (empty($day) || (empty($hour) && ($dayCheckbox === "no")) 
    || (!empty($day) && (!empty($hour) && $dayCheckbox === "yes")) 
    || (empty($day) && !empty($hour) && $dayCheckbox === "yes") 
    || (empty($day) && !empty($hour) && $dayCheckbox === "no")){
        $alert->standardAlert("../views/admin/config.php", "Favor de introducir datos válidos");
}else if (!empty($day) || (empty($hour) && $dayCheckbox === "yes")){
    //consulta 
    $citasQuery = "SELECT citas.fecha, citas.hora, citas.modalidad, pacientes.idPaciente, pacientes.nombre AS pacienteNombre, pacientes.apellido, pacientes.email, pacientes.telefono, servicios.nombre 
        FROM citas 
        LEFT JOIN pacientes ON citas.idPaciente = pacientes.idPaciente 
        LEFT JOIN servicios ON citas.idServicio = servicios.idServicio 
        WHERE citas.fecha = ?";

    // if user selelcted hour...
    if (!empty($hour)){
        // concatenate the hour to the query
        $citasQuery .= " AND citas.hora = ?";
        $params[] = $hour;
        // concatenate the type of data
        $type .= "s";
    }

    $result = mysqli_prepare($connection, $citasQuery);
    if ($result){
        $go = mysqli_stmt_bind_param($result, $type, ...$params);
        $go = mysqli_stmt_execute($result);
        $go = mysqli_stmt_get_result($result);
    }    
} 

if (!empty($hour)){
    $todoElDIa = "";    
}else{
    $todoElDIa = "Todo el día";
    $hour = "";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../css/dashboard.css" rel="stylesheet">
    <link href="../../css/patients.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/login.css">
    <link rel="stylesheet" href="../../css/config.css">
    <title>Belinda Chávez - Configuración de Horario</title>
    <!-- el icono del titulo -->
    <link rel="shortcut icon" href="../../img/favicon.ico" />
</head>

<body>   
    <!--panel-->
    <div class="contenido">
        <div class="contenido__title">
            <div><a href="../views/admin/config.php"><img src="../../img/back.png" alt="back" class="back-btn"></a></div>            
            <div><h1>Configuración</h1></div>
        </div> 

        <main>
                <div><h2>Tu horario elegido</div>
                <div><p><?php echo "Día: " . $day; ?></p></div>
                <div><p><?php echo "Hora: ". $hour; ?></p></div>
                <div><p><?php echo $todoElDIa; ?></p></div>
            <!-- show if there are reservations in the selected shedule -->
            <!-- if there aren't, proceed to cancel and insert data in DB -->
            <form class="login" method="post" action="schedule-changer.php?allDay=<?php echo urlencode($todoElDIa); ?>&date=<?php echo urlencode($day); ?>&hour=<?php echo urlencode($hour); ?>">
                <div><h2>Citas en el horario elegido</div>           
                <!-- citas list -->             
                <?php 
                    // checa si hay citas
                    if (mysqli_num_rows($go) > 0){
                        while ($row = mysqli_fetch_assoc($go)) {
                ?>              
                    <div class="service">      
                        <div class="card-citas">                     
                            <div class="nombre renglon">Fecha: <span class="container-span"><?php echo ($row["fecha"]); ?></span></div>
                            <div class="nombre linea">Hora: <span class="container-span"><?php echo ($row["hora"]); ?></span></div>
                            <div class="nombre renglon">Modalidad: <span class="container-span"><?php echo ($row["modalidad"]); ?></span></div>
                            <div class="nombre linea">Servicio: <span class="container-span"><?php echo ($row["nombre"]); ?></span></div>
                            <div class="nombre renglon">Nombre: <span class="container-span"><?php echo ($row["pacienteNombre"]); ?></span></div>
                            <div class="nombre renglon">Apellido: <span class="container-span"><?php echo ($row["apellido"]); ?></span></div>
                            <div class="nombre renglon">Email: <span class="container-span"><?php echo ($row["email"]); ?></span></div>
                            <div class="nombre renglon">Teléfono: <span class="container-span"><?php echo ($row["telefono"]); ?></span></div>
                        </div>                   
                    </div>  
                <?php 
                        } // while ends
                    }else{
                        // si no hay citas, mosrrar ese mensaje
                        echo "no hay citas";
                    }
                    //libera la memoria
                    mysqli_stmt_free_result($result); 
                    mysqli_stmt_close($result);
                ?>              
                <button type="submit" class="buscador-btn config-send">Confirmar</button>
            </form>
        </main>      
    </div> 
</body>
</html>
