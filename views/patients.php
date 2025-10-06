<?php
session_start();

if (!isset($_SESSION["psicologos"])){
    header("location: ../login.php");
}

include ("../controllers/connection.php");
    
//consulta 
$excludedAdmin = 75;
$pacientes = "SELECT idPaciente, nombre, apellido, telefono FROM pacientes WHERE idPaciente != ? ORDER BY nombre ASC";
$stmt = mysqli_prepare($connection, $pacientes);
mysqli_stmt_bind_param($stmt, "i", $excludedAdmin);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/dashboard.css" rel="stylesheet">
    <title>Belinda Chávez - Pacientes</title>
    <!-- el icono del titulo -->
    <link rel="shortcut icon" href="../img/favicon.ico" />
</head>

<body>   
    <!--panel-->
    <div class="contenido">
        <div class="contenido__title">
            <div><a href="dashboard.php"><img src="../img/back.png" alt="back" class="back-btn"></a></div>            
            <div><h1>Mis pacientes</h1></div>
            <div><a href="create-patient.php"><img src="../img/plus.png" alt="plus" class="plus"></a></div>            
        </div> 
        
        <!-- buscador -->
        <form class="buscador" action="../models/searchPatient.php" method="POST">
            <input type="text" class="buscador-input" name="buscador" id="buscador" placeholder="Buscar por nombre o apellido...">
            <button class="buscador-btn" type="submit" name="buscar">Buscar</button>
        </form>

        <!-- pacientes data -->
        <!--pido conexion, se pone primero la conexion, despues la variable con el query-->
        <?php              
            //cargar y mostrar datos
            while ($row = mysqli_fetch_assoc($result)){ 
        ?>
        <div class="container">
            <div class="datos">               
                    <!-- se llenan lo campos -->
                    <!-- Obtener el id -->                  
                    <div class="tabla__item-name"><a href="patient-data.php?idPaciente=<?php echo $row["idPaciente"];?>"><?php echo $row["nombre"];?></a></div>
                    <div class="tabla__item-lastName"><?php echo $row["apellido"];?></div>    
            </div>
            <div class="tabla__item-telephone"><?php echo $row["telefono"];?></div>             
        </div>
                <div class="tabla__item">
                    <!-- checar que funcione -->
                    <!-- <a href="update.php?id=<?php echo $row["idCliente"];?>"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit btn-animated" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />
                    <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" />
                    <line x1="16" y1="5" x2="19" y2="8" />
                    </svg></a>
                    <p> - </p>
                    <a href="../model/delete-cliente.php?id=<?php echo $row["idCliente"];?>" class="btn-eliminar"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash btn-animated" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <line x1="4" y1="7" x2="20" y2="7" />
                    <line x1="10" y1="11" x2="10" y2="17" />
                    <line x1="14" y1="11" x2="14" y2="17" />
                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg></a>                     -->
                </div>               
            <?php
                } //while ends
                mysqli_stmt_close($stmt);
                //free ups memory
                mysqli_free_result($result); 
            ?>             
        </div>
    </div> 

    <script src="../js/alertaDelete.js"></script>
</body>
</html>