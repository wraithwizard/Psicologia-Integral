<?php
// Start output buffering
ob_start();

include ("../controllers/connection.php");
include ("../controllers/alerts.php");

$email = $_POST["email"];
$password = $_POST["password"];
$confirmado = null;

// regex
// expresions CONSTANTS
const EMAILEXPRESS = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

// email query
$adminQuery = "SELECT * FROM usuarioweb WHERE email = ?";
$result = mysqli_prepare($connection, $adminQuery);
// prepare query
$ok = mysqli_stmt_bind_param($result, "s", $email);
$ok = mysqli_stmt_execute($result); // can only execute once, not both at the same time
$ok = mysqli_stmt_bind_result($result, $idUsuarioWeb, $email, $contrasena, $rol);

$alert = new Alerta();
$confirmationResult = null;

// regex first
if (empty($email) || strlen($email) <= 2 || strlen($email) > 50 || !preg_match(EMAILEXPRESS, $email) || empty($password)){    
    $alert->regexError("Favor de no dejar campos vacíos");
}else{
    if (mysqli_stmt_fetch($result) && $ok){    
        // needs to be here because of the hashed pw
        if ($rol == 1){                 
            // create session for admins
            session_start();
            $_SESSION["psicologos"] = "$email";
            header("location: ../views/dashboard.php");
            exit();
        }
        //verify user hashed password
        if (password_verify($password, $contrasena)){                
                if ($rol == 2){
                    // close the execute 1 so that execute 2 works
                    mysqli_stmt_free_result($result);
                    mysqli_stmt_close($result);

                    // query for confirmation
                    $confirmationQuery = "SELECT confirmado FROM pacientes WHERE email = ?";
                    $confirmationResult = mysqli_prepare($connection, $confirmationQuery);
                    // execute query 2
                    $confirmation = mysqli_stmt_bind_param($confirmationResult, "s", $email);
                    $confirmation = mysqli_stmt_execute($confirmationResult);
                    $confirmation = mysqli_stmt_bind_result($confirmationResult, $confirmado);
                    
                    if (mysqli_stmt_fetch($confirmationResult) && $confirmation){
                        if ($confirmado == 1){
                            // patients session
                            session_start();
                            $_SESSION["patients"] = "$email";
                            header("location: ../views/patient-view.php?email=" ."$email");
                            exit();
                        }else if ($confirmado == 0){
                            $alert->cuentaNoConfirmada();
                        }
                    }else{
                        echo "Error fetching confirmation results <br> " . mysqli_error($connection);
                    }

                    // free up memory
                    if ($confirmationResult == null){
                        return;
                    }else{
                        mysqli_stmt_free_result($confirmationResult);                 
                    }
                }                      
        }else{ 
            $alert->authenticationError();          
        }    
    }else{
        $alert->authenticationError();
    }
}

// close
mysqli_close($connection);

// Flush the output buffer and send it to the browser
ob_end_flush();
?>

<!-- HTML content -->
<!DOCTYPE html>
<html>
<!-- librerias para la alerta -->
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>    
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> 
    <link href="../views/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Rest of your HTML -->
</body>
</html>