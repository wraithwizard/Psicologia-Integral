<?php
session_start();
if (!isset($_SESSION["psicologos"])){
    header("location: auth/login.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/dashboard.css" rel="stylesheet">
    <title>Belinda Chávez - Dashboard</title>
    <!-- el icono del titulo -->
    <link rel="shortcut icon" href="../img/favicon.ico" />
</head>

<body>   
    <!--panel-->
    <div class="contenido">
        <div class="contenido__title-dashboard">
            <div><a href="auth/login.php"><img src="../img/back.png" alt="back" class="back-btn"></a></div>            
            <div><h1>Dashboard</h1></div>
        </div>         
    </div> 

    <main class="dashboard">
        <div class="citas"><a href="admin/admin-citas.php">Citas</a></div>
        <div class="pacientes"><a href="patients.php">Pacientes</a></div>
        <div class="admin"><a href="admin/servicios.php">Servicios</a></div>
        <div class="admin"><a href="admin/config.php">Configuración</a></div>

        <div class="cerrar">
            <h3><a href="../controllers/close-session.php" class="session__close-txt">Cerrar Sesión</h3></a>
        </div>        
    </main>
</body>
</html>