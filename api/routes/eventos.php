<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
//*********************** GET COUNT*/
$app->get('/counter/[{id}/]', function($request, $response, array $args){
  $db =  new db();
  $db = $db->conecctionDB();
  $get = $request->getQueryParams();
  $where = " WHERE 1=1 ";
  $where.= " AND evento = 'taller' ";
  $where.= " AND participacion = 1 ";

  
    //FILTRO POR ID
    if(!empty($args['id'])){
      $where .= " AND id = :id "; 
      $id = $args['id'];
    }

      $sql = $db->prepare("SELECT COUNT(participacion) as count FROM eventos".$where);
      
  try{
    $sql->execute();
    $resultados = $sql->fetchAll(PDO::FETCH_OBJ);
    
    $db = null;
    $response->getBody()->write(json_encode($resultados));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);

  }catch(PDOException $e){
      $error = array(
      "message"=>$e->getMessage()
      );
      $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->whitStatus(500);
        }
});

/******* GET  ***************/
$app->get('/eventos/[{id}/]', function($request, $response, array $args){
    $db =  new db();
    $db = $db->conecctionDB();
    $get = $request->getQueryParams();
    $where = " WHERE 1=1 ";
    // $where.=" AND evento = 'taller' ";
  
      //FILTRO POR ID
      if(!empty($args['id'])){
        $where .= " AND id = :id "; 
        $id = $args['id'];
      }

      if(!empty($get['evento'])){
        $evento = $get['evento'];
        $where .= " AND evento = '$evento' "; 
      }
  
        $sql = $db->prepare("SELECT * FROM eventos  $where  ORDER BY id DESC");
        
    try{
      $sql->execute();
      $resultados = $sql->fetchAll(PDO::FETCH_OBJ);
      foreach($resultados as $key => $value){
        $resultados[$key]->NOMBRE= utf8_decode($resultados[$key]->NOMBRE);
        $resultados[$key]->APELLIDO= utf8_decode($resultados[$key]->APELLIDO);
        // $resultados[$key]->LOCALIDAD= utf8_decode($resultados[$key]->LOCALIDAD);
        $resultados[$key]->CARGO= utf8_decode($resultados[$key]->CARGO);
        $resultados[$key]->INSTITUCION= utf8_decode($resultados[$key]->INSTITUCION);
        $resultados[$key]->CORREO= utf8_decode($resultados[$key]->CORREO);
      }
      
      $db = null;
      $response->getBody()->write(json_encode($resultados));
          return $response
              ->withHeader('content-type', 'application/json')
              ->withStatus(200);
  
    }catch(PDOException $e){
        $error = array(
        "message"=>$e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
          return $response
              ->withHeader('content-type', 'application/json')
              ->whitStatus(500);
          }
  });


  /************ POST  ************/
$app->post('/evento/[{id}/]', function( $request, $response, array $args){
    $db =  new db();
    $db = $db->conecctionDB();
    $inputData = $request->getParsedBody();     
  
    //VALIDA QUE LOS DATOS NO LLEGUEN CORRUPTOS
    if(is_null($inputData)){                      
      return $response
            ->withStatus(400)
            ->withJson(array(
                      'code' => 400,
                      'msj' => "Bad Request"
                      ));
    }
  
    //SE COMPRUEBA SI RECIBE ID COMO ARGUMENTO EN LA URL
    if(!empty($args['id'])){
      $data['id'] = $args['id'];  
    }
  
    //SET DE DATOS RECIBIDOS
    $data['nombre'] = sanitize(html_entity_decode($inputData['nombre']));
    $data['apellido'] = sanitize(html_entity_decode($inputData['apellido']));   
    $data['localidad'] = sanitize(html_entity_decode($inputData['localidad']));
    $data['telefono'] = sanitize($inputData['telefono']);
    $data['cargo'] = sanitize_space(html_entity_decode($inputData['cargo']));
    $data['institucion'] = sanitize_space(html_entity_decode($inputData['institucion']));
    $data['dni'] = sanitize($inputData['dni']);
    $data['dni'] = preg_replace( '/$[.,-]/', '', $data['dni']);
    $data['correo'] = sanitize(html_entity_decode($inputData['correo']));
    $data['participacion'] = sanitize($inputData['participacion']);
    $data['evento'] = sanitize($inputData['evento']);
    
    $res  = $db->query('SELECT * FROM Eventos');
    $res= $res->fetchAll();
  
  
      //SE COMPRUEBA QUE EL ID PASADO COMO ARGUMENTO SEA EXISTENTE
      if(!empty($data['id']) ){
        $exist = $db->query("SELECT COUNT(*) 
                            AS counter 
                            FROM Eventos 
                            WHERE id = ".$data['id']."  LIMIT 1");
        $exist = $exist->fetchAll();
  
        //EN CASO DE BUSCAR UN ID INEXISTENTE
        if($exist[0]["counter"]==0){
                return $response->withStatus(500)
                                ->withJson(array(
                                                "code" => 500,
                                                "msj" => "error"
                                            ));
        }   
  
        $sql = "UPDATE Eventos 
                  SET   nombre = :nombre
                        apellido = :apellido
                        localidad = :localidad
                        telefono = :telefono
                        cargo = :cargo
                        institucion = :institucion
                        dni = :dni
                        correo = :correo
                        participacion = :participacion
                        evento = :evento
                      WHERE id = ".$data['id'];
      }
  
      if(strlen($data['dni']) > 10 || strlen($data['dni']) < 7){
        $res= null;
              return $response
                ->withStatus(410)
                ->withJson(array(
                          'code' => 410,
                          'msj' => "conflict"
                          ));
  
      }
      if(empty($data["ID"])){   
        // foreach($res AS $key){
        //   if($key['DNI'] == $data['dni'] ){
        //     $res= null;
        //       return $response
        //         ->withStatus(409)
        //         ->withJson(array(
        //                   'code' => 409,
        //                   'msj' => "conflict"
        //                   ));
        //     }
        // }
  
        $sql = "INSERT INTO Eventos (nombre, apellido, localidad, telefono, cargo, institucion, dni,
                                      correo, participacion, evento) 
                    VALUES           (:nombre, :apellido, :localidad, :telefono, :cargo, :institucion, :dni,
                                      :correo, :participacion, :evento) ";
  
      }
  
    try{
      $resultado = $db->prepare($sql);
  
      //SET DE DATOS LISTOS PARA SER INSERTADOS EN LA QUERY SQL
      $resultado->bindParam(':nombre', $data['nombre'] );
      $resultado->bindParam(':apellido', $data['apellido']);
      $resultado->bindParam(':localidad', $data['localidad']);
      $resultado->bindParam(':telefono', $data['telefono']);
      $resultado->bindParam(':cargo', $data['cargo'] );
      $resultado->bindParam(':institucion', $data['institucion']);
      $resultado->bindParam(':dni', $data['dni']);
      $resultado->bindParam(':correo', $data['correo']);
      $resultado->bindParam(':participacion', $data['participacion']);
      $resultado->bindParam(':evento', $data['evento']);
  
      $resultado->execute();
  
      if($resultado){
        return $response
        ->withStatus(200)
        ->withJson(array(
                  'code' => 200,
                  'msj' => "OK"
                  ));
      }
  
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  
  });
  
  
  // ********************** POST  ************************************
  $app->post('/allow/[{id}/]', function( $request, $response, array $args){
    $db =  new db();
    $db = $db->conecctionDB();
    $inputData = $request->getParsedBody(); 
    $correo = 'pgonzalez@hospitalsiriolibanes.org';   
    
  
    //VALIDA QUE LOS DATOS NO LLEGUEN CORRUPTOS
    if(is_null($inputData)){                      
      return $response
            ->withStatus(400)
            ->withJson(array(
                      'code' => 400,
                      'msj' => "estoy aca"
                      ));
    }
  
    //SE COMPRUEBA SI RECIBE ID COMO ARGUMENTO EN LA URL
    if(!empty($args['id'])){
      $data['id'] = $args['id'];  
    }
  
      $sql = $db->prepare("SELECT * FROM eventos where id = :id");
      $sql->bindParam(':id', $data['id'] );
      $sql->execute();
      $resultados = $sql->fetchAll(PDO::FETCH_OBJ);
      $destino = $resultados[0]->CORREO;
      $nombre_destino = $resultados[0]->NOMBRE." ".$resultados[0]->APELLIDO;
      $asistencia = "";
      if($resultados[0]->PARTICIPACION == 0){
        $asistencia = "virtual.html";
      }
  
      if($resultados[0]->PARTICIPACION == 1){
        $asistencia = "presencial.html";
      }
      $resultado = $db->prepare("UPDATE eventos SET  INSCRIPCION = 0 WHERE id = ".$data['id']);
      $resultado->execute();
  
      $mail = new PHPMailer();
    
    try{
       $mail->SMTPDebug = 1;                      //Enable verbose debug output
       $mail->isSMTP();                                            //Send using SMTP
       $mail->Host       = 'mail.hospitalsiriolibanes.org';       //Set the SMTP server to send through
       $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
       $mail->Username   =  $correo;                     //SMTP username
       $mail->Password   = '*26424781L*';                               //SMTP password
       $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
       $mail->Port       = 465;     
       //Recipients
       $mail->setFrom( $correo, 'Hospital Sirio Libanes');
       $mail->addAddress($destino, $nombre_destino);     //Add a recipient
  
       //Content
       $mail->isHTML(true); 
       $mail->CharSet = 'UTF-8';                                 //Set email format to HTML
       $mail->Subject = 'Confirmamos su inscripcion!';
       $mail->msgHTML(file_get_contents($asistencia), __DIR__);
        $mail->smtpConnect([
          'ssl' => [
              'verify_peer' => false,
              'verify_peer_name' => false,
              'allow_self_signed' => true
          ]
      ]);
       $mail->send();
      //  echo 'CORREO ENVIADO';
  
  
  
       if($resultado){
        return $response
        ->withStatus(200)
        ->withJson(array(
                  'code' => 200,
                  'msj' => "CORREO ENVIADO CORRECTAMENTE"
                  ));
      }
  
    }catch(PDOException $e){
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  
  });
  
  
  //*****RECURSO DE CANCELACION */
  $app->post('/cancelado/[{id}/]', function( $request, $response, array $args){
    $db =  new db();
    $db = $db->conecctionDB();
    $inputData = $request->getParsedBody();
  
      //VALIDA QUE LOS DATOS NO LLEGUEN CORRUPTOS
      if(is_null($inputData)){                      
        return $response
              ->withStatus(400)
              ->withJson(array(
                        'code' => 400,
                        'msj' => "Bad Request"
                        ));
      }
    
      //SE COMPRUEBA SI RECIBE ID COMO ARGUMENTO EN LA URL
      if(!empty($args['id'])){
        $data['id'] = $args['id'];  
      }
    
    $resultado = $db->prepare("UPDATE eventos SET  INSCRIPCION = 1 WHERE id = ".$data['id']);
    $resultado->execute();
  
    if($resultado){
      return $response
      ->withStatus(200)
      ->withJson(array(
                'code' => 200,
                'msj' => "Solicitud cancelada"
                ));
    }else{
      return $response
      ->withStatus(400)
      ->withJson(array(
                'code' => 200,
                'msj' => "ERROR"
                ));
    }
  });