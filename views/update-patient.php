<?php 
    session_start(); 

    if (!isset($_SESSION["psicologos"])) {
        header("location: ../views/auth/login.php");
    }

    include("../controllers/connection.php");      
    //recibir variable id
    $id = $_GET["id"];

    //consulta para editar el idCliente
    $pacientes = "SELECT * FROM pacientes WHERE pacientes.idPaciente='$id'";   
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/dashboard.css" rel="stylesheet">
    <title>Actualizar datos-paciente</title>
    <link rel="shortcut icon" href="../img/favicon.ico" />
</head>

<body>   
    <!--panel-->
    <div class="contenido">
        <div class="contenido__title-dashboard">
            <div><a href="../views/patients.php"><img src="../img/back.png" alt="back" class="back-btn"></a></div>         
            <div><h1>Edición paciente</h1></div>
        </div>       

        <?php              
                $resultado =  mysqli_query($connection, $pacientes); 
                while ($row = mysqli_fetch_assoc($resultado)){ 
            ?>
        <!-- throws id of patient -->
        <form class="form-updater" action="../models/updating-patients-data.php?idUpdatedPatient=<?php echo $row["idPaciente"];?>" method="post" onsubmit="return validarActualizacionClientes();">    
            <div class="container">
                <div class="datos-update">               
                    <!-- se llenan lo campos desde la tabla de la base datos-->     
                    <div class="tabla__item-name">
                        <input type="hidden" value="<?php echo $row["idPaciente"];?>" name="updateId">
                    </div>          
                    <div class="tabla__item-name">
                        <input type="text" value="<?php echo $row["nombre"];?>" id="updateName" name="updateName">
                    </div>
                    <div class="tabla__item-lastName">
                        <input type="text" class="tabla__item" value="<?php echo $row["apellido"];?>" id="updateLastName" name="updateLastName">
                    </div>    
                </div>
                <div class="tabla__item-telephone">
                    <input type="tel" class="tabla__item" value="<?php echo $row["telefono"];?>" id="updateTelephone" name="updateTelephone">       
                </div>             
            </div>
            <div class="container">
                <div class="datos__secondary">
                    <div class="tabla__item-title">Email</div>
                    <div class="tabla__item-txt"> 
                        <input type="email" class="tabla__item" value="<?php echo $row["email"];?>" id="updateEmail" name="updateEmail">                    
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="datos__secondary">
                    <div class="tabla__item-title">Género</div>
                    <div class="tabla__item-txt">
                        <input type="text" value="<?php echo $row["genero"];?>"id="updateGenero" name="updateGenero">                       
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="datos__secondary">
                    <div class="tabla__item-title">Fecha de nacimiento</div>
                    <div class="tabla__item-txt">
                        <input type="date" value="<?php echo $row["fechaNacimiento"];?>" id="updateDate" name="updateDate">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="datos__secondary">
                    <div class="tabla__item-title">Antecedentes Médicos</div>
                    <div class="tabla__item-txt">
                        <input type="text" value="<?php echo $row["antecedentesMedicos"];?>"id="updateAntecedentes" name="updateAntecedentes">     
                 </div>
                </div>
            </div>
            <div class="container">
                <div class="datos__secondary">
                    <div class="tabla__item-title">Objetivo</div>
                    <div class="tabla__item-txt">
                        <input type="text" value="<?php echo $row["objetivo"];?>"id="updateOjetivo" name="updateOjetivo">     
                    </div>
                </div>
            </div>      
            <div class="container">
                <div class="datos__secondary">
                    <div class="tabla__item-title">Disponibilidad Horaria</div>
                    <div class="tabla__item-txt">
                        <input type="time" value="<?php echo $row["disponibilidadHoraria"];?>"id="updateHour" name="updateHour">     
                    </div>
                </div>
            </div>      
    </div>
    <!-- actualizar btn -->
     <div class="reg">
        <input type="submit" value="Actualizar" class="form-updater-btn" name="updatePatient">
     </div>
            <?php          
                } 
                //libera la memoria
                mysqli_free_result($resultado); 
            ?>        
        </form>  
    
    <script src="../js/validacionFormClientes.js"></script>
</body>
</html>