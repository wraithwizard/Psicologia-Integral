<?php
session_start();

if (!isset($_SESSION["psicologos"])){
    header("location: auth/login.php");
}

include ("../controllers/connection.php");

//id var
$id = $_GET["idPaciente"];
    
//consulta 
$pacientes = "SELECT * FROM pacientes WHERE pacientes.idPaciente = '$id'";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/dashboard.css" rel="stylesheet">
    <title>Belinda Chávez - Datos paciente</title>
    <!-- el icono del titulo -->
    <link rel="shortcut icon" href="../img/favicon.ico" />
</head>

<body>   
    <!--panel-->
    <div class="contenido">
        <div class="contenido__title-dashboard">
            <div><a href="patients.php"><img src="../img/back.png" alt="back" class="back-btn"></a></div>            
            <div><h1>Datos Paciente</h1></div>                 
        </div>  

        <!-- pacientes data -->
        <!--pido conexion, se pone primero la conexion, despues la variable con el query-->
        <?php              
            $resultado =  mysqli_query($connection, $pacientes); 
            //cargar y mostrar datos
            while ($row = mysqli_fetch_assoc($resultado)){ 
        ?>
        <div class="container">
            <div class="datos">               
                <!-- se llenan lo campos desde la tabla de la base datos-->               
                <div class="tabla__item-name"><?php echo $row["nombre"];?></div>
                <div class="tabla__item-lastName"><?php echo $row["apellido"];?></div>    
            </div>
            <div class="tabla__item-telephone"><?php echo $row["telefono"];?></div>             
        </div>
        <div class="container">
            <div class="datos__secondary">
                <div class="tabla__item-title">Email</div>
                <div class="tabla__item-txt"><?php echo $row["email"];?></div>
            </div>
        </div>
        <div class="container">
            <div class="datos__secondary">
                <div class="tabla__item-title">Género</div>
                <div class="tabla__item-txt"><?php echo $row["genero"];?></div>
            </div>
        </div>
        <div class="container">
            <div class="datos__secondary">
                <div class="tabla__item-title">Fecha de nacimiento</div>
                <div class="tabla__item-txt"><?php echo $row["fechaNacimiento"];?></div>
            </div>
        </div>
        <div class="container">
            <div class="datos__secondary">
                <div class="tabla__item-title">Antecedentes Médicos</div>
                <div class="tabla__item-txt"><?php echo $row["antecedentesMedicos"];?></div>
            </div>
        </div>
        <div class="container">
            <div class="datos__secondary">
                <div class="tabla__item-title">Objetivo</div>
                <div class="tabla__item-txt"><?php echo $row["objetivo"];?></div>
            </div>
        </div>      
        <div class="container">
            <div class="datos__secondary">
                <div class="tabla__item-title">Disponibilidad Horaria</div>
                <div class="tabla__item-txt"><?php echo $row["disponibilidadHoraria"];?></div>
            </div>
        </div>      
    </div>
    <div class="contenido__btn">
        <!-- update btn -->
        <a href="update-patient.php?id=<?php echo $row["idPaciente"];?>"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit btn-animated" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />
        <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" />
        <line x1="16" y1="5" x2="19" y2="8" />
        </svg></a>
        <!--  delete btn -->
        <a href="../models/delete-patient.php?id=<?php echo $row["idPaciente"];?>" class="btn-eliminar"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash btn-animated" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <line x1="4" y1="7" x2="20" y2="7" />
        <line x1="10" y1="11" x2="10" y2="17" />
        <line x1="14" y1="11" x2="14" y2="17" />
        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
        </svg></a>                    
    </div>  
        <?php
            } //while ends

            //free ups memory
            mysqli_free_result($resultado); 
            ?>             
    </div> 
   
    <script src="../js/AlertDelete.js"></script>
</body>
</html>