<?php
     session_start();

     if (!isset($_SESSION["patients"])) {
         header("location: auth/login.php");
         exit();
     }

     // Enable error reporting for debugging
    error_reporting(E_ALL); 
    ini_set('display_errors', 1);

    // Set content type to JSON
    header('Content-Type: application/json');

    // get data from FETCH POST
    if (isset($_POST)) {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
    }    

    if ($data === null) {
        echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
        exit();
    }

    // get the id and all other data of service and put in button the proper stripe link
    $nombre = $data["nombre"];
    $servicio = $data["servicios"];
    $hora = $data["hora"];
    $fecha = $data["fecha"];
    $id = $data["id"]; 
    $email = $data["email"];
    $modalidad = $data["modalidad"];
    // el link desde stripe dashboard
    $url = "";

    switch ($id) {
        case "1":
            // mandar todos los datos hasta el confirmation.php (success url), y en confirmation.php recibir con un $_GET los datos para insertar en la db
            $url = "../views/checkout.php?email=$email&hora=$hora&fecha=$fecha&id=$id&modalidad=$modalidad"; 
            break;

        case "2":
            $url = "../views/checkout.php?email=$email&hora=$hora&fecha=$fecha&id=$id&modalidad=$modalidad";
            break;

        case "3":
            $url = "../views/checkout.php?email=$email&hora=$hora&fecha=$fecha&id=$id&modalidad=$modalidad";
            break;

        case "4":
            $url = "../views/checkout.php?email=$email&hora=$hora&fecha=$fecha&id=$id&modalidad=$modalidad";
            break;

        case "5":
            // stripe code
            //$url = "../views/checkout.php?email=$email&hora=$hora&fecha=$fecha&id=$id&modalidad=$modalidad";
            break;

        case "6":
            $url = "../views/checkout.php?email=$email&hora=$hora&fecha=$fecha&id=$id&modalidad=$modalidad";
            break;
        
        default:
            echo json_encode(["status" => "error", "message" => "Service not found"]);
            exit();
            break;
    }

    // Return the URL in JSON format
echo json_encode(["status" => "success", "url" => $url]);
exit();