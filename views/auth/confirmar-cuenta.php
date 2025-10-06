<?php
// manage token and send to user view
include ("../../controllers/connection.php");

// get the token and sanitize
$token = $_GET["token"];
filter_input(INPUT_GET, $token, FILTER_SANITIZE_SPECIAL_CHARS);

if (!$token){
    die("Token no proporcionado");
}

// checks to who belongs this token
$tokenQuery = "SELECT * FROM pacientes WHERE token = ?";
$stmt = mysqli_prepare($connection, $tokenQuery);
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

try{
    if ($result){ 
        $updateConfirmation = "UPDATE pacientes SET confirmado='1' WHERE token= ?";
        $stmt2 = mysqli_prepare($connection, $updateConfirmation);
        mysqli_stmt_bind_param($stmt2, "s", $token);
        $updatedUserResult = mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);
        
        if ($updatedUserResult){
            // $nullifyToken = "UPDATE pacientes SET token='null' WHERE token='$token'";
            $nullifyToken = "UPDATE pacientes SET token='null' WHERE token = ?";
            //$updateToken = mysqli_query($connection, $nullifyToken);
            $stmt3 = mysqli_prepare($connection, $nullifyToken);
            mysqli_stmt_bind_param($stmt3, "s", $token);
            $updateToken = mysqli_stmt_execute($stmt3);
            mysqli_stmt_close($stmt3);

            if ($updateToken){
                // redirect user
                header("location: cuenta-confirmada.php");
            }         
        }else{
            echo "No se pudo verificar la cuenta, contactar al administrador";
        }
    }else{
        echo "usuario no encontrado";
    }
}catch (mysqli_sql_exception $f){
    echo "Error: " . $e->getMessage();
}

mysqli_close($connection);