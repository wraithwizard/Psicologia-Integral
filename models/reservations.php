<?php
    session_start();

    if (!isset($_SESSION["patients"])) {
        header("location: auth/login.php");
        exit();
    }

    include ("../controllers/connection.php");   

    // Habilitar el informe de errores para depuración
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Establecer el tipo de contenido a JSON
    header('Content-Type: application/json');

    try{
        // get data from FETCH POST
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

            // Verificar que los datos se hayan recibido correctamente
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
            exit;
        }

        if ($data) {          
            $hour = $data["hora"];
            $date = $data["fecha"];                

            $checkDateAndHourDuplicatesQuery = "SELECT 
            (SELECT COUNT(*) FROM citas WHERE fecha = ? AND hora = ?) AS count,
            (SELECT COUNT(*) FROM diasBloqueados WHERE fecha = ? AND bloqueado = 1) AS blocked";

            // prepare
            $result = mysqli_prepare($connection, $checkDateAndHourDuplicatesQuery);
            if ($result){
                // bind
                $ok = mysqli_stmt_bind_param($result, "sss", $date, $hour, $date);
                // execute
                $ok = mysqli_stmt_execute($result);
                $ok = mysqli_stmt_bind_result($result, $count, $blocked);
                $ok = mysqli_stmt_fetch($result);          
                mysqli_stmt_close($result);

                if ($count > 0) {
                    echo json_encode(["status" => "duplicate", "message" => "La fecha y hora ya están reservadas."]);
                    exit();
                }else if($blocked > 0){
                    echo json_encode(["status" => "blocked", "message" => "El día está bloqueado."]);
                    exit();
                }else{
                    echo json_encode(["status" => "success", "message" => "Todo bien."]);
                    exit();
                }
            }
        }
    }catch (Exception $e) {
        $response = ["status" => "error", "message" => $e->getMessage()];
    }   

mysqli_close($connection);

?>

