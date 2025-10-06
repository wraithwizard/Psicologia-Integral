<?php

include ("../controllers/connection.php");
include ("../controllers/alerts.php");

// get token
$token = $_POST["token"];

$newPassword = $_POST["password"];

$alert = new Alerta();

// find user by token
$userQuery = "SELECT token, email FROM pacientes WHERE token = ?";
$result = mysqli_prepare($connection, $userQuery);
// prepare query
$ok = mysqli_stmt_bind_param($result, "s", $token);
$ok = mysqli_stmt_execute($result);
$ok = mysqli_stmt_bind_result($result, $dbToken, $email);


if (mysqli_stmt_fetch($result) && $token == $dbToken){   
    // out of sync commands fix
    mysqli_stmt_free_result($result);

    // checks input
    if ($newPassword == null || strlen($newPassword) < 3 || strlen($newPassword) > 20) {
        // Display an error alert and go back to input
        $alert->standardAlert("../views/auth/recuperar-pw.php?token=" .$token, "La contraseña debe tener más de 3 y menos de 20 caracteres, y no puede ir vacía");      
    }else {
        // hash the valid input pw
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);

        // updates new password in db       
        $changePasswordOnDatabaseQuery = "UPDATE usuarioweb SET contrasena = ? WHERE email IN (SELECT email FROM pacientes WHERE token =  ?)";        
        $stmt = mysqli_prepare($connection, $changePasswordOnDatabaseQuery);
        mysqli_stmt_bind_param($stmt, "ss", $hash, $token);
        $result = mysqli_stmt_execute($stmt);        
    
        // debugging
        // if ($stmt) {
        //     echo " stmt es true <br> ";
        // } else {
        //     echo "stmt es false <br>";
        // identify the error
        //     die('Error in preparing statement: ' . mysqli_error($connection));
        // }        
    
        if ($stmt){            
            $alert->standardAlert("../views/auth/login.php", "Contraseña actualizada");
        } else if (!$stmt){
            $alert->standardAlert("../views/auth/recuperar-pw.php?token=" .$token, "Error al querer actualizar");
        }
    }
} else if (empty($token) || $token != $dbToken){
    $alert ->standardAlert("../views/auth/recuperar-pw.php", "token no válido");
}

mysqli_close($connection);