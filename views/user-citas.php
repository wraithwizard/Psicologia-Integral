<?php
session_start();
if (!isset($_SESSION["patients"])) {
    header("location: auth/login.php");
}

include ("../controllers/connection.php");

$email = $_GET["email"];

// get the name
$nameQuery = "SELECT nombre, apellido FROM pacientes WHERE email = ?";
$result = mysqli_prepare($connection, $nameQuery);
$letsgo = mysqli_stmt_bind_param($result, "s", $email);
$letsgo = mysqli_stmt_execute($result);
$letsgo = mysqli_stmt_bind_result($result, $userName, $apellido);
mysqli_stmt_fetch($result);
mysqli_stmt_close($result);

// get the citas
$citasQuery = "SELECT citas.fecha, citas.hora, citas.modalidad, servicios.nombre, servicios.precio
                FROM citas 
                LEFT JOIN pacientes ON citas.idPaciente = pacientes.idPaciente 
                LEFT JOIN servicios ON citas.idServicio = servicios.idServicio 
                WHERE pacientes.email = ?";
$result2 = mysqli_prepare($connection, $citasQuery);

if ($result2){
    mysqli_stmt_bind_param($result2, "s", $email);
    if ( mysqli_stmt_execute($result2)){
        // get the result
        $resultado = mysqli_stmt_get_result($result2);
    } 
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/favicon.ico">
    <title>Usuario - Citas</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/patient.css">
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
                <a href="../sobre-mi.html" class="header-menu__link">Sobre mi</a>
            </nav>       
        </div>

        <div class="main-div">
            <div class="app">    
                <div><h2 class="app-name">Bienvenido <?php echo $userName ?></h2></div>
                <div class="section">
                    <h2 class="login">Tus citas</h2>
                    <!-- services list -->             
                        <?php 
                            while ($row = mysqli_fetch_assoc($resultado)) {
                        ?>              
                        <div class="service">      
                            <div class="card-citas">                     
                                <div class="nombre renglon">Fecha: <span class="container-span"><?php echo ($row["fecha"]); ?></span></div>
                                <div class="nombre linea">Hora: <span class="container-span"><?php echo ($row["hora"]); ?></span></div>
                                <div class="nombre renglon">Modalidad: <span class="container-span"><?php echo ($row["modalidad"]); ?></span></div>
                                <div class="nombre linea">Servicio: <span class="container-span"><?php echo ($row["nombre"]); ?></span></div>
                                <div class="nombre renglon">Precio: <span class="container-span">$<?php echo ($row["precio"]); ?></span></div>
                            </div>                   
                        </div>  
                        <?php 
                        } // while ends
                            //libera la memoria
                            mysqli_stmt_free_result($result2); 
                            mysqli_stmt_close($result2);
                        ?>                          
                    </div>                  
            
            <div class="session_close">
                <h2 class="login"><a href="../controllers/close-session.php" class="session__close-txt">Cerrar Sesión</h2></a>
            </div>
    </main>
    
    <?php include("footer.php"); ?>

    <script src="../js/Burger.js"></script>
    <!-- pass the email to the JS -->
    <script>
        window.userEmail ="<?php echo $email; ?>"   
    </script>
    <script src="../js/DivAlert.js"></script>    
</body>
</html>