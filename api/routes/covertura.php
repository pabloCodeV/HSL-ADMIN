<?php

/******* GET  COVERTURA***************/
$app->get('/covertura/[{id}/]', function($request, $response, array $args){
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
                                  nombre,
                                  tipo
                                  FROM covertura $where ORDER BY id DESC");
      
      if(Isset($id)){
        $sql->bindParam(':id', $id );
      }

      $sql->execute();
      $resultados = $sql->fetchAll(PDO::FETCH_OBJ);

      foreach($resultados as $key => $value){
        $resultados[$key]->tipo == 0 ?  $resultados[$key]->tipo = 'Obra Social' : '';
        $resultados[$key]->tipo == 1 ?  $resultados[$key]->tipo = 'ART' : '';
      }

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
$app->post('/covertura/[{id}/]', function( $request, $response, array $args){
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

    isset($inputData['nombre'])?$data['nombre'] = sanitize(strtoupper(html_entity_decode($inputData['nombre']))):$data['nombre'] = "";
    isset($inputData['tipo'])?$data['tipo'] = sanitize(html_entity_decode($inputData['tipo'])):$data['tipo'] = "";
    
  

    //VALIDA QUE EL ID A MODIFICAR EXISTA EN LA BASE DE DATOS
    if(!empty($data['id'])){
       $validate = $db->prepare("SELECT id FROM covertura WHERE id = :id ");
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

      $sql = "UPDATE covertura 
                SET   nombre = :nombre,
                      tipo = :tipo
                      WHERE id = ".$data['id'];
                
    }else{

    $sql = "INSERT INTO covertura (nombre, tipo) 
          VALUES                (:nombre, :tipo) ";

    }
    
      $resultado = $db->prepare($sql);
      //SET DE DATOS LISTOS PARA SER INSERTADOS EN LA QUERY SQL
      $resultado->bindParam(':nombre', $data['nombre'] );
      $resultado->bindParam(':tipo', $data['tipo']);
      $resultado->execute();

      if(!empty($data['id'])){
        return $response
          ->withStatus(200)
          ->withJson(array(
                    'code' => 200,
                    'msj' => "This coverage was modified correctly"
                    ));
      }

      if(empty($data['id'])){
        return $response
          ->withStatus(200)
          ->withJson(array(
                    'code' => 200,
                    'msj' => "New Coverage"
                    ));
      }

});


/************************ DELETE ************************/
$app->delete('/covertura/[{id}/]', function( $request, $response, array $args){
  $db =  new db();
  $db = $db->conecctionDB();
  $id = $request->getAttribute('id');

  //BORRA EL ELEMENTO SELECCIONADO SEGUN SU ID
  $sql = "DELETE FROM covertura WHERE id = $id";

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
