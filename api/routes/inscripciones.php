<?php

/******* GET  ***************/
$app->get('/inscripciones/[{id}/]', function($request, $response, array $args){
    $db =  new db();
    $db = $db->conecctionDB();
    $get = $request->getQueryParams();
    $where = " WHERE 1=1 ";
  
      //FILTRO POR ID
      if(!empty($args['id'])){
        $where .= " AND id = $args[id] "; 
      }

      //FILTRO POR ID
      if(!empty($get['nombre'])){
        $where .= " AND nombre = '$get[nombre]' " ; 
      }


        $sql = $db->prepare(" SELECT nombre, apellido, telefono, correo, secundario, create_at 
                              FROM inscripciones  $where  
                              ORDER BY id DESC");
        
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


  /************ POST  ************/
$app->post('/inscripciones/[{id}/]', function( $request, $response, array $args){
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
    $data['telefono'] = intval($inputData['telefono']);
    $data['correo'] = sanitize(html_entity_decode($inputData['correo']));
    $data['secundario'] = intval($inputData['secundario']);

    
    $res  = $db->query('SELECT * FROM inscripciones');
    $res= $res->fetchAll();
  
  
      //SE COMPRUEBA QUE EL ID PASADO COMO ARGUMENTO SEA EXISTENTE
      if(!empty($data['id']) ){
        $exist = $db->query("SELECT COUNT(*) 
                            AS counter 
                            FROM inscripciones 
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
  
        $sql = "UPDATE inscripciones 
                  SET   nombre = :nombre,
                        apellido = :apellido,
                        telefono = :telefono,
                        correo = :correo,
                        secundario = :secundario,
                        create_at = now()
                      WHERE id = ".$data['id'];
      }
  
      if(empty($data["id"])){   

        $sql = "INSERT INTO inscripciones (nombre, apellido, telefono, correo, secundario, create_at) 
                    VALUES           (:nombre, :apellido, :telefono, :correo, :secundario, NOW()) ";
  
      }
  
    try{
      $resultado = $db->prepare($sql);
  
      //SET DE DATOS LISTOS PARA SER INSERTADOS EN LA QUERY SQL
      $resultado->bindParam(':nombre', $data['nombre'] );
      $resultado->bindParam(':apellido', $data['apellido']);
      $resultado->bindParam(':telefono', $data['telefono']);
      $resultado->bindParam(':correo', $data['correo']);
      $resultado->bindParam(':secundario', $data['secundario'] );
  
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