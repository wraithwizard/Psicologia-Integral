<?php 
    session_start(); 

    if (!isset($_SESSION["psicologos"])) {
        header("location: auth/login.php");
    }

    include("../controllers/connection.php");      
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/dashboard.css" rel="stylesheet">
    <title>Belinda Chávez - crear paciente</title>
    <link rel="shortcut icon" href="../img/favicon.ico" />
</head>

<body>  
    <div class="contenido">
        <div class="contenido__title">
            <div><a href="dashboard.php"><img src="../img/back.png" alt="back" class="back-btn"></a></div> 
            <div><h2>Creación de Paciente</h2></div>
        </div>        
          
    <div class="formulario-cliente">
        <!--Formulario crear -->
        <form method="post" action="../models/creating-patient.php" onsubmit="return "> <!-- validarUser(); -->
        <div class="container">
                <div class="datos-update">              
                    <div class="datos-__secondary">
                        <div class="tabla__item-title">Nombre</div>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="tabla__item-lastName">
                        <div class="tabla__item-title">Apellido</div>
                        <input type="text" class="tabla__item" id="lastName" name="lastName" required>
                    </div>    
                </div>
                <div class="tabla__item-telephone">
                    <div class="tabla__item-title">Teléfono</div>
                    <input type="tel" class="tabla__item" id="telephone" name="telephone" required>       
                </div>             
            </div>
            <div class="container">
                <div class="datos__secondary">
                    <div class="tabla__item-title">Email</div>
                    <div class="tabla__item-txt"> 
                        <input type="email" class="tabla__item" id="email" name="email" required>                    
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="datos__secondary">
                    <div class="tabla__item-title">Género</div>
                    <div class="tabla__item-txt">
                        <select name="gender" type="text"id="gender" class="tabla__item">
                            <option hidden disabled selected value>------</option>
                            <option value="H">H</option>     
                            <option value="M">M</option>     
                        </select>                                    
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="datos__secondary">
                    <div class="tabla__item-title">Fecha de nacimiento</div>
                    <div class="tabla__item-txt">
                        <input type="date" id="birthday" name="birthday" required>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="datos__secondary">
                    <div class="tabla__item-title">Antecedentes Médicos</div>
                    <div class="tabla__item-txt">
                        <input type="text" id="medicalHistory" name="medicalHistory">     
                 </div>
                </div>
            </div>
            <div class="container">
                <div class="datos__secondary">
                    <div class="tabla__item-title">Objetivo</div>
                    <div class="tabla__item-txt">
                        <input type="text" id="objective" name="objective" required>     
                    </div>
                </div>
            </div>      
            <div class="container">
                <div class="datos__secondary">
                    <div class="tabla__item-title">Disponibilidad Horaria</div>
                    <div class="tabla__item-txt">
                        <input type="time" id="hour" name="hour">     
                    </div>
                </div>
            </div>      
            <div class="reg">
                <input type="submit" name="insert" value="Registrar" class="form-updater-btn">                      
            </div>
        </form>      
    </div>

    <script src="../js/validacionFormClientes.js"></script> 
</body>
</html>
  

