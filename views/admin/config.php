<?php
session_start();

if (!isset($_SESSION["psicologos"])){
    header("location: ../auth/login.php");
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
    <title>Belinda Chávez - Configuración</title>
    <!-- el icono del titulo -->
    <link rel="shortcut icon" href="../../img/favicon.ico" />
</head>

<body>   
    <!--panel-->
    <div class="contenido">
        <div class="contenido__title">
            <div><a href="../dashboard.php"><img src="../../img/back.png" alt="back" class="back-btn"></a></div>            
            <div><h1>Configuración</h1></div>
        </div> 

        <main>
            <form class="login" method="post" action="../../models/config-logic.php">
                <div><h2>Selecciona el horario a cancelar</div>
                <div class="section-field">
                    <label for="date">Selecciona el día</label>
                    <input id="date" type="date" min="<?php echo date('Y-m-d', strtotime('+1 hour')); ?>" name ="date" required/>
                </div>

                <div class="section-field">
                    <label for="hour">Selecciona la hora</label>
                    <input id="hour" type="time" min="11:00" max="18:00" name="hour"/>
                </div>

                <div class="section-field config-checkbox" >
                    <input id="allDay" type="checkbox" name="allDay" value="yes"/>
                    <label for="todo-el-dia">Todo el día</label>
                </div>
                <button type="submit" class="buscador-btn config-send">Enviar</button>
                <p>&#128064 Recuerda que es cancelación de horario, no de cita.</p>
            </form>
        </main>      
    </div> 
</body>
</html>