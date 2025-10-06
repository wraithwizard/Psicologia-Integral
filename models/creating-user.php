<?php
include ("../controllers/connection.php");
include ("../controllers/alerts.php");
include ("../controllers/Email.php");

$nombre = mysqli_real_escape_string($connection, $_POST["name"]);
$apellido = mysqli_real_escape_string($connection, $_POST["lastName"]);
$telefono = mysqli_real_escape_string($connection, $_POST["telephone"]);
$email = mysqli_real_escape_string($connection, $_POST["email"]);
$password = mysqli_real_escape_string($connection, $_POST["password"]);
$passwordConfirmation = mysqli_real_escape_string($connection, $_POST["passwordConfirmation"]);
//$gender = $_POST["gender"];
//$birthday = $_POST["birthday"];
//$medicHistory = $_POST["medicalHistory"];
$objective = mysqli_real_escape_string($connection, $_POST["objective"]);
//$hour = $_POST["hour"];
$rol = 2;

// hash pw
$hash = password_hash($password, PASSWORD_DEFAULT);
// token
$token = uniqid();

// // verifies that email is written correctly
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
strtolower($email);

$alert = new Alerta();
$emailSent = false; // checks if the email has been sent

// echo "nombre: " , $nombre, "<br>apellido: ", $apellido, "<br>telefono: ", $telefono, "<br>email: ", $email, "<br>password: ", $password, "<br>confirmacion: ", $passwordConfirmation, "<br>objetivo: ", $objective;

if ($password !== $passwordConfirmation) {
    $alert->standardAlert("../views/auth/crear-cuenta.php", "Las contraseñas no coinciden");
    exit();
}

// checks if telefono is not numbers only
if (!ctype_digit($telefono)){
    $alert->standardAlert("../views/auth/crear-cuenta.php", "El teléfono debe contener sólo números");
    exit();
}

// sanitize input
$telefono = filter_var($telefono, FILTER_SANITIZE_NUMBER_INT);

if (!empty($nombre) && !empty($apellido) && !empty($telefono) && !empty($email) && !empty($password) && !empty($passwordConfirmation)){
    // validates email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)){
        // checks for duplicates
        // $checkQuery = "SELECT COUNT(email) as count FROM pacientes WHERE email = '$email' UNION SELECT COUNT(email) as count FROM usuarioweb WHERE email = '$email'";
        
        $checkQuery = "SELECT COUNT(email) AS count FROM pacientes WHERE email = ? UNION SELECT COUNT(email) as count FROM usuarioweb WHERE email = ?";
        $result = mysqli_prepare($connection, $checkQuery);

        try{
            if ($result){
                $ok = mysqli_stmt_bind_param($result, "ss", $email, $email);
                $ok = mysqli_stmt_execute($result);
                $ok = mysqli_stmt_bind_result($result, $count);
                $row = mysqli_stmt_fetch($result);
                mysqli_stmt_close($result);

                // checks if user exist in DB
                if ($count > 0){
                    $alert->standardAlert("../views/auth/crear-cuenta.php", "El usuario ya existe");
                }else{
                    // user inserts himself in DB
                    $insertUser = "INSERT INTO pacientes (nombre, apellido, email, telefono, objetivo, token) VALUES (?, ?, ?, ?, ?, ?)";
                    $result2 = mysqli_prepare($connection, $insertUser);   
                    // get the id
                    $idUser = mysqli_insert_id($connection);
                    
                    if ($result2){
                        $ok2 = mysqli_stmt_bind_param($result2, "ssssss", $nombre, $apellido, $email, $telefono, $objective, $token);
                        $ok2 = mysqli_stmt_execute($result2);
                        mysqli_stmt_close($result2);

                        // insert into DB, rol and hash pw
                        $userInsert = "INSERT INTO usuarioweb (email, contrasena, rol) VALUES (?, ?, ?)";
                        $result3 = mysqli_prepare($connection, $userInsert);
                        // gets PK from pacientes
                        $idPacientes = mysqli_insert_id($connection);

                        if ($result3){
                            $ok3 = mysqli_stmt_bind_param($result3, "ssi", $email, $hash, $rol);
                            $ok3 = mysqli_stmt_execute($result3);
                            mysqli_stmt_close($result3);       

                            // verification email, must send only 1 email
                            $correo = new Email($email, $nombre, $token);
                            $emailSent = true;

                            // succesful alert
                            //revisar direccion al subir al host, cambiar a: windows.location=/view/dashboard'
                            $alert->standardAlert("../views/auth/mensaje.php", "Registro de usuario exitoso");      

                            // $updateKey = "UPDATE pacientes SET idUsuarioWeb = ? WHERE idPaciente = ?";
                            // $result4 = mysqli_prepare($connection, $updateKey);
                            
                            // if ($result4) {
                            //     $ok4 = mysqli_stmt_bind_param($result4, "ii", $idPacientes, $idUser);
                            //     $ok4 = mysqli_stmt_execute($result4);
                            //     mysqli_stmt_close($result4);

                                                       
                            //}
                        }
                    }else{
                        $alert->standardAlert("../views/auth/crear-cuenta.php", "No se pudo registrar");
                    }
                }
            }else{
                $alert->standardAlert("../views/auth/crear-cuenta.php", "Error de base de datos, no se pudo checar los duplicados");
            }
        }catch (mysqli_sql_exception $f) {
            throw $f;   
        }
    }else{
        $alert->standardAlert("../views/auth/crear-cuenta.php", "Favor de ingresar un email válido");
    }
}else{
    $alert->standardAlert("../views/auth/crear-cuenta.php", "Favor de llenar los campos necesarios");
}     

//send email only once
if ($emailSent && $correo !== null){
    $correo->SendVerification();                    
}