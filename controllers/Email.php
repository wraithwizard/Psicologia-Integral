<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpMailer/Exception.php';
require '../vendor/phpMailer/PHPMailer.php';
require '../vendor/phpMailer/SMTP.php';

class Email{
    public $email;
    public $name;
    public $token;

    public function __construct($email, $name, $token){
        $this->email = $email;
        $this->name = $name;
        $this->token = $token;
    }

    public function SendVerification(){
        $mail = new PHPMailer();

        try{        
            $mail->SMTPDebug = 0;
            $mail->isSMTP(); // protocol
            $mail->Host       = 'smtp.hostinger.com';                      // antes smtp.gmail.com
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = '';            
            $mail->Password   = $_SERVER["EMAIL_PW"];                       
            $mail->SMTPSecure = 'tls';							
            $mail->Port       = 587;                                  

            // who sends
            $mail->setFrom("@psicoterapia-integral.mx", "Psicoterapia Integral");
            // to who?
            $mail->addAddress($this->email, $this->name);
            $mail->Subject = "Verificación de cuenta";

            // set HTML
            $mail->isHTML(true);
            $mail->CharSet = "UTF-8";

            $content = "<html>";
            $content .= "<p>Hola <strong>" . $this->name . "</strong>. Bienvenido a la web de Psicoterapia Integral - Belinda, confirma tu cuenta al darle click en el siguiente enlace: </p>";  
            $content .= "<p><a href=https://psicoterapia-integral.mx/views/auth/confirmar-cuenta.php?token=" . $this->token . ">Confirmar cuenta</a></p>";
            $content .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar este mensaje.</p>";
            $content .= "</html>";

            $mail->Body = $content;    

            //echo  $mail->Body;
            
            if($mail->send()){
                //echo 'Mensaje enviado';
            }else{
                echo 'El mensaje no puede ser enviado';
                echo 'PHPMailer Error: ' . $mail->ErrorInfo;
            }
        }catch (Exception $e){

        }        
    }

    public function PasswordReestablishment(){
           $mail = new PHPMailer();

        try{        
            $mail->SMTPDebug = 0;
            $mail->isSMTP(); // protocol
            $mail->Host       = 'smtp.hostinger.com';                      
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = '';            //SMTP username
            $mail->Password   = $_SERVER["EMAIL_PW"];                          //SMTP password
            $mail->SMTPSecure = 'tls';							
            $mail->Port       = 587;     

            // who sends
            $mail->setFrom("@psicoterapia-integral.mx", "Psicoterapia Integral");
            // to who?
            $mail->addAddress($this->email, $this->name);
            $mail->Subject = "Reestablecer contraseña";

            // set HTML
            $mail->isHTML(true);
            $mail->CharSet = "UTF-8";

            $content = "<html>";
            $content .= "<p>Hola <strong>" . $this->name . "</strong>. Has solicitado restablecer tu contraseña, has click en el siguiente enlace para hacerlo. </p>"; 
            $content .= "<p><a href=https://psicoterapia-integral.mx/views/auth/recuperar-pw.php?token=" .$this->token . ">Restablecer contraseña</a></p>";
            $content .= "<p>Si tu no solicitaste este cambio, puedes ignorar este mensaje.</p>";
            $content .= "</html>";

            $mail->Body = $content;              
            
            if($mail->send()){
                //echo 'Mensaje enviado';
            }else{
                echo 'El mensaje no puede ser enviado';
                echo 'PHPMailer Error: ' . $mail->ErrorInfo;
            }
        }catch (Exception $e){

        }        
    }

    public function SendReservationDetails($date, $hour, $service, $modality){
        $mail =  new PHPMailer();
        try{
            $mail->SMTPDebug = 0;
            $mail->isSMTP(); // protocol
            $mail->Host       = 'smtp.hostinger.com';                      
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = '@psicoterapia-integral.mx';            //SMTP username
            $mail->Password   = $_SERVER["EMAIL_PW"];                          //SMTP password
            $mail->SMTPSecure = 'tls';							
            $mail->Port       = 587;     

            // who sends
            $mail->setFrom("@psicoterapia-integral.mx", "Psicoterapia Integral");
            // to who?
            $mail->addAddress($this->email, $this->name);
            $mail->Subject = "Confirmación de su cita de Psicoterapia con Belinda Chávez";

            // set HTML
            $mail->isHTML(true);
            $mail->CharSet = "UTF-8";

            //$content = "<html>";
            $content = '
            <!DOCTYPE html>
            <html lang="es">
            <!DOCTYPE html>
            <html lang="es">
                <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Confirmación de Cita</title>
                </head>
                <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
                <header style="background-color: #4a90e2; color: white; text-align: center; padding: 20px;">
                <h1 style="margin: 0;">Confirmación de Cita</h1>
                </header>
    
                <main style="padding: 20px;">
                    <p>Estimado/a <strong>' . $this->name . '</strong>,</p>
                    
                    <p>¡Gracias por reservar una cita con nosotros! Le confirmamos que su sesión de psicoterapia ha sido programada con éxito. A continuación, encontrará los detalles de su cita:</p>
                    
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Fecha:</strong></td>
                            <td style="padding: 10px; border-bottom: 1px solid #ddd;">' . $date . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Hora:</strong></td>
                            <td style="padding: 10px; border-bottom: 1px solid #ddd;">' . $hour . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Terapeuta:</strong></td>
                            <td style="padding: 10px; border-bottom: 1px solid #ddd;">Belinda</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Tipo de sesión:</strong></td>
                            <td style="padding: 10px; border-bottom: 1px solid #ddd;">' . $service . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Duración:</strong></td>
                            <td style="padding: 10px; border-bottom: 1px solid #ddd;">50 - 60 minutos</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Modalidad:</strong></td>
                            <td style="padding: 10px; border-bottom: 1px solid #ddd;">' . $modality . '</td>
                        </tr>
                    </table>
                    
                    <p><strong>Dirección:</strong> Zona Centro, Tijuana, B.C.</p>
                    <p><strong>Enlace:</strong> El enlace se te enviará por Whatsapp o Email 10 minutos antes de la sesión, en caso de ser online.</p>
                    
                    <h2 style="color: #4a90e2;">Recordatorios importantes:</h2>
                    <ol style="padding-left: 20px;">
                        <li>Por favor, llegue 5-10 minutos antes de su cita si es presencial, o conéctese unos minutos antes si es en línea.</li>
                        <li>Si necesita cancelar o reprogramar su cita, hágalo con al menos 24 horas de anticipación.</li>
                        <li>Prepare cualquier pregunta o tema que desee abordar durante la sesión.</li>
                        <li>Asegúrese de estar en un lugar tranquilo y privado para su sesión, en caso de ser en línea.</li>
                    </ol>
                    
                    <p>Si tiene alguna pregunta o necesita más información, no dude en contactarnos respondiendo a este correo o llamando al .</p>
                    
                    <p>Esperamos poder ayudarle pronto en su camino hacia el bienestar emocional.</p>
                    
                    <p>Saludos cordiales,</p>
                    
                    <p><strong>Mtra. en Psicoterapia Belinda</strong></p>
                </main>
    
            <footer style="background-color: #f4f4f4; text-align: center; padding: 10px; font-size: 0.8em;">
                <p>&copy; 2024 Psicoterapia Integral. Todos los derechos reservados.</p>
            </footer>
        </body>
        </html></html>
        ';

        $mail->Body = $content;  

        if($mail->send()){
            //echo 'Email enviado correctamente';
        }else{
            echo 'El mensaje no puede ser enviado';
            echo 'PHPMailer Error: ' . $mail->ErrorInfo;
        }
        }catch(Exception $e){

        }
    }
}