<?php
session_start();

if (!isset($_SESSION["psicologos"])){
    header("location: ../login.php");
}

include ("../../controllers/connection.php");

// sorting experiment
$sortColumn = isset($_GET["sort"]) ? $_GET["sort"] : "citas.fecha"; //default sort by date
$sortOrder = isset($_GET["order"]) && strtolower($_GET["order"]) === "desc" ? "DESC" : "ASC";
// whitelist of allowed columns to sort by
$allowedColumns = ['citas.fecha', 'citas.hora', 'citas.modalidad', 'servicios.nombre', 'pacientes.nombre', 'pacientes.apellido', 'pacientes.email', 'pacientes.telefono'];

// Ensure the sort column is in the allowed list to prevent SQL injection
if (!in_array($sortColumn, $allowedColumns)) {
    $sortColumn = 'citas.fecha'; // Default to a safe value if not in the allowed list
}
    
//consulta 
$excludedAdmin = 75;
$citasQuery = "SELECT citas.fecha, citas.hora, citas.modalidad, servicios.nombre AS serviciosNombre, pacientes.nombre AS pacientesNombre, pacientes.apellido, pacientes.email, pacientes.telefono, pacientes.objetivo 
    FROM citas 
    LEFT JOIN pacientes ON citas.idPaciente = pacientes.idPaciente 
    LEFT JOIN servicios ON citas.idServicio = servicios.idServicio
    WHERE citas.idPaciente != ?
    ORDER BY $sortColumn $sortOrder";

$result = mysqli_prepare($connection, $citasQuery);

if ($result){
    // No need for bind_param as there are no parameters in the query
    mysqli_stmt_bind_param($result, "i", $excludedAdmin);
    mysqli_stmt_execute($result);    
    $resultado = mysqli_stmt_get_result($result);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../css/dashboard.css" rel="stylesheet">
    <link href="../../css/config.css" rel="stylesheet">
    <title>Belinda Chávez - Citas</title>
    <!-- el icono del titulo -->
    <link rel="shortcut icon" href="../../img/favicon.ico" />
</head>

<body>   
    <div class="contenido">
        <div class="contenido__title">
            <div><a href="../dashboard.php"><img src="../../img/back.png" alt="back" class="back-btn"></a></div>            
            <div><h1>Mis citas</h1></div>
        </div>        

        <div>      
            <table>
                <thead>
                    <tr>
                        <!-- headers -->
                        <th><a href="?sort=citas.fecha&order=<?php echo $sortColumn === 'citas.fecha' && $sortOrder === 'ASC' ? 'desc' : 'asc'; ?>">Fecha</a></th>
                        <th><a href="?sort=citas.hora&order=<?php echo $sortColumn === 'citas.hora' && $sortOrder === 'ASC' ? 'desc' : 'asc'; ?>">Hora</a></th>
                        <th><a href="?sort=citas.modalidad&order=<?php echo $sortColumn === 'citas.modalidad' && $sortOrder === 'ASC' ? 'desc' : 'asc'; ?>">Modalidad</a></th>
                        <th><a href="?sort=servicios.nombre&order=<?php echo $sortColumn === 'servicios.nombre' && $sortOrder === 'ASC' ? 'desc' : 'asc'; ?>">Servicio</a></th>
                        <th><a href="?sort=pacientes.nombre&order=<?php echo $sortColumn === 'pacientes.nombre' && $sortOrder === 'ASC' ? 'desc' : 'asc'; ?>">Nombre</a></th>
                        <th><a href="?sort=pacientes.nombre&order=<?php echo $sortColumn === 'pacientes.nombre' && $sortOrder === 'ASC' ? 'desc' : 'asc'; ?>">Email</a></th>
                        <th><a href="?sort=pacientes.nombre&order=<?php echo $sortColumn === 'pacientes.nombre' && $sortOrder === 'ASC' ? 'desc' : 'asc'; ?>">Teléfono</a></th>
                        <th><a href="?sort=pacientes.nombre&order=<?php echo $sortColumn === 'pacientes.nombre' && $sortOrder === 'ASC' ? 'desc' : 'asc'; ?>">Objetivo</a></th>
                    </tr>
                </thead>

                <tbody>
                    <?php 
                        // get the result
                        while ($row = mysqli_fetch_assoc($resultado)){ 
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($row['hora']); ?></td>
                            <td><?php echo htmlspecialchars($row['modalidad']); ?></td>
                            <td><?php echo htmlspecialchars($row['serviciosNombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['pacientesNombre'] . ' ' . $row['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($row['objetivo']); ?></td>
                        </tr>
                    <?php
                        }               
                        mysqli_stmt_close($result);
                        // frees up memory
                        mysqli_free_result($resultado); 
                    ?>
                </tbody>
            </table>  
        </div>
<!-- 
        <div class="container">
            <div class="datos">                -->
                    <!-- se llenan lo campos -->
                    <!-- Obtener el id -->                  
                    <!-- <div class="tabla__item-name"><a href="patient-data.php?idPaciente=<?php // echo $row["idPaciente"];?>"><?php // echo $row["nombre"];?></a></div>
                    <div class="tabla__item-lastName"><?php  // echo $row["apellido"];?></div>     -->
            <!-- </div>
            <div class="tabla__item-telephone"><?php //echo $row["telefono"];?></div>             
        </div>
                <div class="tabla__item"> -->
                    <!-- checar que funcione -->
                    <!-- <a href="update.php?id=<?php // echo $row["idCliente"];?>"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit btn-animated" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />
                    <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" />
                    <line x1="16" y1="5" x2="19" y2="8" />
                    </svg></a>
                    <p> - </p>
                    <a href="../model/delete-cliente.php?id=<?php //echo $row["idCliente"];?>" class="btn-eliminar"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash btn-animated" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <line x1="4" y1="7" x2="20" y2="7" />
                    <line x1="10" y1="11" x2="10" y2="17" />
                    <line x1="14" y1="11" x2="14" y2="17" />
                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg></a>                     -->
                <!-- </div>               
                     
        </div> -->
    </div> 

    <script src="../js/alertaDelete.js"></script>
</body>
</html>