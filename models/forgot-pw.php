<?php
include ("../controllers/Email.php");
include ("../controllers/connection.php");
include ("../controllers/alerts.php");

$email = $_POST["email"];
//echo "email: " . $email;

// is there token on db?
$checkForNullToken = "SELECT email, nombre, token  FROM pacientes WHERE email = '$email'";
$result = mysqli_query($connection, $checkForNullToken);
$dbToken = null;
$dbName = null;

if ($row = mysqli_fetch_assoc($result)){
    //echo "token: " . $row['token'];
    $dbToken = $row["token"];
    $dbName = $row["nombre"];
}

$correo = new Email($email, $dbName, $dbToken);
$emailSent = false; // checks if the email has been sent
$alert = new Alerta();

// if token is not null , send this token
if ($dbToken !== null) {
    //echo "should've sent email with actual token";
    $correo->PasswordReestablishment();
    $alert->standardAlert("../views/auth/olvide-pw.php", "Email enviado");
    $emailSent = true;
} else {
    // create new token
    $dbToken = uniqid();
    // and send the new token via email and 
    //echo "<br> new token is: " . $dbToken . "should send email";
    // update DB
    try{
        $updateToken = "UPDATE pacientes SET token='$dbToken' WHERE email = '$email'";
        $updatedResult = mysqli_query($connection, $updateToken);
        $correo->PasswordReestablishment();
        $alert->standardAlert("../views/auth/olvide-pw.php", "Email enviado");
        $emailSent = true;
    } catch (mysqli_sql_exception $f){
        throw $f;
    }
}

mysqli_close($connection);