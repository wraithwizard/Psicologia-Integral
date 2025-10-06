<!-- librerias para la alerta -->
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>    
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="../css/alert.css" rel="stylesheet">
</head>

<?php
class Alerta{
    function updateSuccess($location, $message, $idPaciente){        
        echo '<script type="text/javascript"> $(document).ready(function(){
            swal({
                icon: "success",
                text: "' . $message . '",
                buttons: {
                    ok: "OK",
                },
                background: "#262626",
            }).then(function(value){
                if (value === "ok") {
                    window.location.href = "' . $location . '?idPaciente=' . urlencode($idPaciente) . '";
                }
            });
        }); 
        </script>';
    }

    function standardAlert($location, $message){        
        echo '<script type="text/javascript"> $(document).ready(function(){
            swal({
                icon: "success",
                text: "' . $message . '",
                buttons: {
                    ok: "OK",
                },
                background: "#262626",
            }).then(function(value){
                if (value === "ok") {
                    window.location.href = "'. $location .'";
                }
            });
        }); 
        </script>';
    }

    function authenticationError(){
        echo
        '<script type="text/javascript"> $(document).ready(function(){
            swal({
                icon: "error",
                text: "Error de autenticación, favor de intentar de nuevo",
                button: true,
                button: "Regresar",
                background: "#262626",
            }).then(function(){
                window.location.href="../views/auth/login.php";
            })
        }); 
        </script>';
    }

    function cuentaNoConfirmada(){
        echo
        '<script type="text/javascript"> $(document).ready(function(){
            swal({
                icon: "error",
                text: "Favor de confirmar cuenta",
                button: true,
                button: "Regresar",
                background: "#262626",
            }).then(function(){
                window.location.href="../views/auth/login.php";
            })
        }); 
        </script>';
    }

    function regexError($message){
        echo
        '<script type="text/javascript"> $(document).ready(function(){
            swal({
                icon: "error",
                text: "' . $message . '",
                button: true,
                button: "Regresar",
                background: "#262626",
            }).then(function(){
                window.location.href="../views/auth/login.php";
            })
        }); 
        </script>';
    }   
}