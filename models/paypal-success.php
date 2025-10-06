<?php
session_start();

if (!isset($_SESSION["patients"])) {
    header("location: auth/login.php");
    exit();
}

// Set content type to JSON
header('Content-Type: application/json');

// get data from FETCH POST
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if ($data === null) {
    echo json_encode(["status" => "error", "message" => "Datos vacíos o no válidos"]);
    exit();
}

//get the data
$email = $data["email"];
$hour = $data["hora"];
$service = $data["servicios"];
$date = $data["fecha"];
$id = $data["id"]; 
$modalidad = $data["modalidad"];

// Verificar si las variables importantes están definidas
if (!$email || !$hour || !$date || !$id || !$modalidad) {
    echo json_encode(["status" => "error", "message" => "Faltan datos requeridos"]);
    exit();
}

// Si todo está bien, devuelve una respuesta exitosa
echo json_encode([
    "status" => "success",
    "redirectUrl" => "../views/paypal-confirmation.php?email=$email&hora=$hour&fecha=$date&id=$id&modalidad=$modalidad"
]);

exit();