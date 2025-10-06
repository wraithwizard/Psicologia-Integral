<?php
session_start();
if (!isset($_SESSION["patients"])) {
    header("location: auth/login.php");
    exit;   
}


include("../controllers/connection.php");

// select 
$email = $_GET["email"];

// query
$nameQuery = "SELECT nombre, apellido FROM pacientes WHERE email = ?";
$result = mysqli_prepare($connection, $nameQuery);
$letsgo = mysqli_stmt_bind_param($result, "s", $email);
$letsgo = mysqli_stmt_execute($result);
$letsgo = mysqli_stmt_bind_result($result, $userName, $apellido);
mysqli_stmt_fetch($result);
mysqli_stmt_close($result);

// get services
$constelacionesFamiliaresGrupales = 5;
//$servicesQuery = "SELECT idServicio, nombre, descripcion, precio FROM servicios WHERE idServicio != ?";
$servicesQuery = "SELECT idServicio, nombre, descripcion, precio FROM servicios";
$serviciosResult = mysqli_prepare($connection, $servicesQuery);

if ($serviciosResult) {
    //mysqli_stmt_bind_param($serviciosResult, "i", $constelacionesFamiliaresGrupales);
    $serviciosGo = mysqli_stmt_execute($serviciosResult);
    $serviciosGo = mysqli_stmt_bind_result($serviciosResult, $idServicio, $nombre, $descripcion, $precio);
}

// price for constelaciones familiares
$paypalPrice = 1000.00;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../img/favicon.ico">
    <title>Usuario</title>
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
                <li><a class="link" href="user-citas.php?email=<?php echo urlencode($email); ?>">Mis citas</a></li><hr>         
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
                <nav class="tabs">
                    <button type="button" class="btn" data-step="1">Servicios</button>
                    <button type="button" class="btn" data-step="2">Información Cita</button>
                    <button type="button" class="btn" data-step="3">Resumen</button>
                </nav>

                <div id="step1" class="section active">
                    <h2 class="login">Servicios</h2>
                    <p class="section_choose">Elije tu servicio</p><br>         
                    <!-- services list -->             
                        <?php 
                            while (mysqli_stmt_fetch($serviciosResult)) {
                        ?>              
                        <div class="service">      
                            <div class="card-container">                     
                                <div class="arrow"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-badge-right-filled" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 6l-.112 .006a1 1 0 0 0 -.669 1.619l3.501 4.375l-3.5 4.375a1 1 0 0 0 .78 1.625h6a1 1 0 0 0 .78 -.375l4 -5a1 1 0 0 0 0 -1.25l-4 -5a1 1 0 0 0 -.78 -.375h-6z" stroke-width="0" fill="currentColor" /></svg></div>
                                <div class="nombre"><?php echo $nombre; ?> </div>
                                <div class="precio"><?php echo $precio; ?> </div>
                            </div>                    
                            <!-- descriptions -->
                            <div class="dropdown" id="description-1">
                                <div class="descripcion"><?php echo nl2br($descripcion); ?> </div>
                                <!-- nombre must be inside '' because it's a string -->
                                <div><button class="dropdown-btn" 
                                onclick="getServicesAndChangeToDate(<?php echo $idServicio; ?>, '<?php echo $nombre; ?>', <?php echo $precio; ?>)">Horarios</button></div>
                            </div>
                        </div>  
                        <?php 
                        } // while ends
                            //libera la memoria
                            mysqli_stmt_free_result($serviciosResult); 
                        ?>                          
                    </div>
                    
                </div id="services" class="services-list"></div>       
                
                <div id="step2" class="section active">
                    <h2 class="login">Tus datos y cita</h2>
                    <p  class="section_choose">Coloca la fecha y hora de tu cita</p><br>   
                    <div id="step2__services-info"></div>
                    <form class="login">
                        <div class="section-field">
                            <label for="name">Nombre</label>
                            <input disabled id="name" type="text" 
                                placeholder="<?php echo $userName . " ". $apellido ?>"
                                value="<?php echo $userName . " ". $apellido ?>"/>
                        </div>
                        <div class="section-field" id="fecha">
                            <label for="date">Fecha</label>
                            <input id="date" type="date" min="<?php echo date('Y-m-d', strtotime('+1 hour')); ?>"/>
                        </div>
                        <div class="section-field">
                            <label for="hour">Hora</label>
                            <input id="hour" type="time" min="11:00" max="18:00"/>
                        </div>
                        <div class="section-field">
                            <label for="modalidad">Modalidad</label>
                        </div>
                        <select class="modalidad" id="modalidad">
                                <option value="presencial" selected>Presencial</option>
                                <option value="online">Online</option>
                        </select>
                    </form>
                </div>

                <div id="step3" class="section active summary">
                    <h2 class="login" >Resumen</h2>
                    <p>Verifica que la información sea correcta</p><br>   
                </div>

                <div class="page">
                    <button id="go-back" class="btn">&laquo; Atrás</button>
                    <button id="go-forward" class="btn">Siguiente &raquo;</button>
                </div>            
            </div>   

            <!------------------------------ Paypal -------------------------------->
            <div id="paypal-button-container" class="paypal-container login">
                <p id="result-message"></p>    
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
    <script src="../js/app.js"></script> 
    <script src="../js/DivAlert.js"></script>    

    <!-- paypal -->
    <script src="https://www.paypal.com/sdk/js?client-id=AWBW1rJ8Aprlhn9DAT738oMholDsppS09AftK4UeDB_Tx1_yahfqNZ39LCfJt-w1eitzHSezaU72M6FA&currency=MXN" data-sdk-integration-source="integrationbuilder_sc"></script>
    <script> 
        paypal.Buttons({
        style: {
            shape: "pill",
            layout: "vertical",
            color: "white",
            label: "pay",
        },
        // order
        createOrder:function(data, actions){
            return actions.order.create({
                purchase_units:[{
                    amount: {
                        // protected value
                        value: <?php echo json_encode($paypalPrice); ?>
                    }
                }]
            });
        },
        onCancel:function(data_cancel){
            //console.log(data_cancel);
        },
        onApprove:function(data, actions){
            actions.order.capture().then(function(details){
                if (details.status === 'COMPLETED'){
                    handlePayPalSuccess();
                }
            });
        }
        }).render("#paypal-button-container")
    </script>
</body>
</html>