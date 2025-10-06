<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../img/favicon.ico">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/login.css">
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
                <li><a class="link" href="../../index.html">Inicio</a></li><hr>
                <li><a class="link" href="../../sobre-mi.html">Sobre mi</a></li><hr>     
                <li><a class="link" href="">Pide una cita</a></li><hr>         
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
            <img class="logo" src="../../img/mainLogo.jpg" alt="logo">
            <nav class="header-menu">
                <a href="../../sobre-mi.html" class="header-menu__link">Sobre mi</a>
                <a href="citas.html" class="header-menu__link">Pide una cita</a>            
            </nav>
        
            <div class="description">
                <h1 class="name">Mtra. en Psicoterapia Belinda Chávez</h1>
                <h2 class="description-phrase">Muévete... <br>y que se mueva lo que se tenga que mover</h2>               
            </div>
        </div>

        <div class="main-div">
            <div class="app">    
                <div class="ap__invitation">
                    <p class="main-text__txt">¿La estás pasando mal y no entiendes qué es lo que está causando está molestia?<br><br>
                    ¿Sientes que este dolor que presentas no es tuyo?<br><br>
                    ¿No encuentras alivio tu tristeza?<br><br>
                    ¿Se te dificulta relacionarte efectivamente con los demás?<br><br>
                    ¿Has tenido que guardar tu afecto y emociones hacia los demás por temor a que te lastimen?<br><br>
                    ¿Sientes que algunas cosas que suceden en tu entorno te sobrepasan?
                    </p>
                </div>

                <div class="form-login">
                    <form class="login" id="loginID" action="../../models/loginValidation.php" method="post" onsubmit="return loginValidate();">
                        <h2>Accede a tu cuenta</h2>
                        <!-- el codigo del placeholder es de https://unicode-table.com/es/-->
                        <input type="email" placeholder="&#64; Correo electrónico" name="email" required>
                        <input type="password" placeholder="&#9919; Contraseña" name="password" required>                                       
                        <div class="checker">
                            <input type="checkbox" class="check-box" id="recuerdame">
                            <label for="recuerdame">Recordar usuario</label>
                        </div>  
                        <input class="btn-animated" type="submit" value="Iniciar sesión">                      
                        <a href="olvide-pw.php" class="login-recuperar">He olvidado mi contraseña</a>
                        <div class="form-box__hr">
                            <div><hr></div>
                            <!-- <div class="form-box-hrtxt"><p>     o     </p></div> -->
                            <div><hr></div>
                        </div>                       

                        <p class="form-login__sincuenta">¿Todavía sin cuenta?</p><a href="crear-cuenta.php">Quiero Registrarme</a>       
                    </form>                     
                </div>                 
            </div>  
        </div>            
    </main>
    
    <?php include("../footer.php"); ?>

    <script src="../../js/Burger.js"></script>
    <script src="../../js/login-validation.js"></script>
</body>
</html>