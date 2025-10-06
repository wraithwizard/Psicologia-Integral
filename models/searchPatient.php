<?php 
    //esta funccion debe estar en cada archivo de sesion
    session_start(); 

    //si la varialbe está vavcía
    if (!isset($_SESSION["psicologos"])) {
        //redirigir al loging
        header("location: ../views/auth/login.php");
    }

    include("../controllers/connection.php");   

    // variable necesaria para evitar errores
    $buscador = $_POST["buscador"]; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/dashboard.css" rel="stylesheet">
    <title>Belinda Chávez - Búsqueda</title>
</head>

<body>   
    <!--panel-->
    <div class="contenido">
    <div class="contenido__title-dashboard">
        <div><a href="../views/patients.php"><img src="../img/back.png" alt="back" class="back-btn"></a></div>            
        <div><h1>Búsqueda</h1></div>                    
    </div>       

    <!-- la tabla de clientes -->
    <div>           
    <!-- logica del buscador -->
    <?php 
        if (isset($_POST["buscador"])) {
        $busqueda = mysqli_real_escape_string($connection, $_POST["buscador"]);
        // buscar por nombre o apellido paterno
        $sql = "SELECT * FROM pacientes WHERE nombre LIKE '%$buscador%' OR apellido LIKE '%$buscador%'";
        $result = mysqli_query($connection, $sql);
        // checa que haya resultados
        $queryResult = mysqli_num_rows($result);

        if ($queryResult > 0) {
            while($row = mysqli_fetch_assoc($result)){ ?>    
                <div class="container">
                    <div class="datos">               
                            <!-- se llenan lo campos desde la tabla de la base datos-->
                            <!-- Obtener el id -->
                            <!-- <div class="tabla__item"><a href="poliza-cliente.php?idCliente=<?php echo $row["idCliente"];?>"><?php echo $row["idCliente"];?></a></div> -->
                            <div class="tabla__item-name"><a href="patient-data.php"><?php echo $row["nombre"];?></a></div>
                            <div class="tabla__item-lastName"><?php echo $row["apellido"];?></div>    
                    </div>
                    <div class="tabla__item-telephone"><?php echo $row["telefono"];?></div>             
                </div>                   
                    <!-- <div class="tabla__item">
                        <a href="update.php?id=<?php echo $row["idCliente"];?>">Editar</a>
                        <p> | </p>
                        <a href="../model/delete-cliente.php?id=<?php echo $row["idCliente"];?>" class="btn-eliminar">Eliminar</a>                     -->
                </div>  
            <?php 
                }
                //libera la memoria
                mysqli_free_result($result);     
        }else{
            echo "<script>alert('No existe el cliente'); window.history.go(-1);</script>";
        }
        }?>
    </div>       
    </div> 

    <!-- <script src="../js/alerta-eliminacion.js"></script> -->
</body>
</html>