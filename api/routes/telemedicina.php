<?php

/******* GET ***************/
$app->get('/telemedicina/[{id}/]', function($request, $response, array $args){
  $db =  new db();
  $db = $db->conecctionDB();
  $get = $request->getQueryParams();
  $where = " WHERE 1=1 ";
  
    //FILTRO POR ID
    if(!empty($args['id'])){
      $where .= " AND id = :id "; 
      $id = $args['id'];
    }

      $sql = $db->prepare("SELECT id,
                                  nombre
                                  FROM telemedicina $where ORDER BY id DESC");
      
      if(Isset($id)){
        $sql->bindParam(':id', $id );
      }

      $sql->execute();
      $resultados = $sql->fetchAll(PDO::FETCH_OBJ);
      $db = null;
      
      if(!empty($resultados)){
        $response->getBody()->write(json_encode($resultados));
          return $response
              ->withHeader('content-type', 'application/json')
              ->withStatus(200);
      }else{
        $response->getBody()->write(json_encode(array('code'=> 400, 'msj'=> 'Resourse not found')));
        return $response
            ->withHeader('content-type', 'application/json')
            ->whitStatus(400);
      }
});

/************ POST  ************/
$app->post('/telemedicina/[{id}/]', function( $request, $response, array $args){
  $db =  new db();
  $db = $db->conecctionDB();
  $inputData = $request->getParsedBody();
  !empty($args['id'])?$data['id'] = intval($args['id']): NUll;

  //VALIDA QUE LOS DATOS NO LLEGUEN CORRUPTOS
  if(is_null($inputData)){                      
    return $response
          ->withStatus(400)
          ->withJson(array(
                    'code' => 400,
                    'msj' => "Bad Request"
                    ));
  }

    isset($inputData['nombre'])?$data['nombre'] = sanitize_space(html_entity_decode($inputData['nombre'])):$data['nombre'] = "";
    
    var_dump($data);

    //VALIDA QUE EL ID A MODIFICAR EXISTA EN LA BASE DE DATOS
    if(!empty($data['id'])){
       $validate = $db->prepare("SELECT id FROM telemedicina WHERE id = :id ");
       $validate->bindParam(':id', $data['id'] );
       $validate->execute();
       $resultados = $validate->fetchAll(PDO::FETCH_OBJ);
       if(!$resultados){
          return $response
            ->withStatus(500)
            ->withJson(array(
                      'code' => 500,
                      'msj' => "ID not found in the database"
                      ));
       }

      $sql = "UPDATE telemedicina 
                SET   nombre = :nombre
                      WHERE id = ".$data['id'];
                
    }else{

    $sql = "INSERT INTO telemedicina (nombre) 
          VALUES                (:nombre) ";

    }
    
      $resultado = $db->prepare($sql);
      //SET DE DATOS LISTOS PARA SER INSERTADOS EN LA QUERY SQL
      $resultado->bindParam(':nombre', $data['nombre'] );
      $resultado->execute();

      if(!empty($data['id'])){
        return $response
          ->withStatus(200)
          ->withJson(array(
                    'code' => 200,
                    'msj' => "This especiality of telemedicina was modified correctly"
                    ));
      }

      if(empty($data['id'])){
        return $response
          ->withStatus(200)
          ->withJson(array(
                    'code' => 200,
                    'msj' => "New especiality on telemedicina"
                    ));
      }

});


/************************ DELETE ************************/
$app->delete('/telemedicina/[{id}/]', function( $request, $response, array $args){
  $db =  new db();
  $db = $db->conecctionDB();
  $id = $request->getAttribute('id');

  //BORRA EL ELEMENTO SELECCIONADO SEGUN SU ID
  $sql = "DELETE FROM telemedicina WHERE id = $id";

  try{
    $resultado = $db->prepare($sql);
    $resultado->execute();

    if ($resultado->rowCount() > 0) {
      return $response
        ->withStatus(200)
        ->withJson(array(
                  'code' => 200,
                  'msj' => "Ok"
                  ));
    }else {
      return $response
        ->withStatus(500)
        ->withJson(array(
                  'code' => 500,
                  'msj' => "No existe en la DB"
                  ));
    }

    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});
