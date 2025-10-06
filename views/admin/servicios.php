<?php
session_start();

if (!isset($_SESSION["psicologos"])){
    header("location: ../login.php");
}

include ("../../controllers/connection.php");
    
//consulta 
$serviciosQuery = "SELECT nombre, descripcion, precio FROM servicios";
$stmt = mysqli_prepare($connection, $serviciosQuery);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../css/dashboard.css" rel="stylesheet">
    <link href="../../css/config.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/patient.css">

    <title>Belinda Chávez - Servicios</title>
    <!-- el icono del titulo -->
    <link rel="shortcut icon" href="../../img/favicon.ico" />
</head>

<body>   
    <!--panel-->
    <div class="contenido">
        <div class="contenido__title">
            <div><a href="../dashboard.php"><img src="../../img/back.png" alt="back" class="back-btn"></a></div>            
            <div><h1>Mis Servicios</h1></div>
        </div>       
     
        <!--pido conexion, se pone primero la conexion, despues la variable con el query-->
        <?php              
            //cargar y mostrar datos
            while ($row = mysqli_fetch_assoc($result)){ 
        ?>
        <div>
            <div class="card-citas" >               
                    <!-- Obtener el id -->                  
                    <div class="tabla__item-lastName nombre renglon">Nombre: <span class="container-span"><?php echo htmlspecialchars($row["nombre"]); ?></span></div>    
                    <div class="tabla__item-lastName nombre linea">Descripción:  <span class="container-span"><?php echo htmlspecialchars ($row ["descripcion"]) ;?></span></div>    
                    <div class="tabla__item-lastName nombre linea">Precio $  <span class="container-span"><?php echo htmlspecialchars ($row["precio"]); ?></span></div>    
            </div>     
            <?php
                } //while ends
                mysqli_stmt_close($stmt);
                //free ups memory
                mysqli_free_result($result); 
            ?>             
        </div>
    </div> 

</body>
</html>