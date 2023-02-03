<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// ********************** POST  ************************************
$app->post('/correo/[{id}/]', function( $request, $response, array $args){
    $inputData = $request->getParsedBody(); 
    $correo_raiz = 'pgonzalez@hospitalsiriolibanes.org';
    $correo_receptor = "info@hospitalsiriolibanes.org";
    
  
    //VALIDA QUE LOS DATOS NO LLEGUEN CORRUPTOS
    if(is_null($inputData)){                      
      return $response
            ->withStatus(400)
            ->withJson(array(
                      'code' => 400,
                      'msj' => "Bad Request"
                      ));
    }

    $nombre = $inputData['nombre'];
    $correo = $inputData['correo'];
    $telefono = $inputData['telefono'];
    $especialidad = $inputData['especialidad'];
    $mensaje = $inputData['mensaje'];
  
     $mail = new PHPMailer();
    try{
       $mail->SMTPDebug = 1;                      //Enable verbose debug output
       $mail->isSMTP();                                            //Send using SMTP
       $mail->Host       = 'mail.hospitalsiriolibanes.org';       //Set the SMTP server to send through
       $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
       $mail->Username   =  $correo_raiz;                     //SMTP username
       $mail->Password   = '*26424781L*';                               //SMTP password
       $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
       $mail->Port       = 465;     
       //Recipients
       $mail->setFrom( $correo_raiz, 'Hospital Sirio Libanes || CONSULTAS');
       $mail->addAddress($correo_receptor, $nombre);     //Add a recipient
  
       //Content
       $mail->isHTML(true); 
       $mail->CharSet = 'UTF-8';                                 //Set email format to HTML
       $mail->Subject = 'HSL CONSULTAS';
       $shtml = file_get_contents("consulta.html");
       $incss  = str_replace('<p id="nombre"></p>','<p style="font-weight: 600;"> Nombre: <span style="font-weight: 400;">'.$nombre.'</span></p>',$shtml);
       $incss = str_replace('<p id="correo"></p>','<p style="font-weight: 600;"> Correo:  <span style="font-weight: 400;">'.$correo.'</span></p>', $incss);
       $incss = str_replace('<p id="telefono"></p>','<p style="font-weight: 600;"> Telefono:  <span style="font-weight: 400;">'.$telefono.'</span></p>', $incss);
       $incss = str_replace('<p id="especialidades"></p>','<p style="font-weight: 600;"> Especialidad:  <span style="font-weight: 400;">'.$especialidad.'</span></p>', $incss);
       $cuerpo = str_replace('<p id="mensaje"></p>','<p style="font-weight: 600;"> Mensaje: <span style="font-weight: 400;">'.$mensaje.'</span></p>', $incss);
       $mail->Body = $cuerpo; //cuerpo del mensaje
        $mail->smtpConnect([
          'ssl' => [
              'verify_peer' => false,
              'verify_peer_name' => false,
              'allow_self_signed' => true
          ]
      ]);
       $mail->send();
      //  echo 'CORREO ENVIADO';
  

        return $response
        ->withStatus(200)
        ->withJson(array(
                  'code' => 200,
                  'msj' => "CORREO ENVIADO CORRECTAMENTE"
                  ));
  
    }catch(PDOException $e){
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  
  });
  
  