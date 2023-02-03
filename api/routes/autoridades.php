<?php
/******* GET  ***************/
$app->get('/autoridades/[{id}/]', function($request, $response, array $args){
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
                                  puesto,
                                  nombre,
                                  sector
                                  FROM autoridades 
                                  $where 
                                  ORDER BY id ASC 
                                  ");


    if(Isset($id)){
      $sql->bindParam(':id', $id );
    }

    $sql->execute();
    $resultados = $sql->fetchAll(PDO::FETCH_OBJ);

    foreach($resultados as $key => $value){
      $resultados[$key]->sector == 0 ?  $resultados[$key]->sector = 'COMISIÓN DIRECTIVA' : '';
      $resultados[$key]->sector == 1 ?  $resultados[$key]->sector = 'DIRECCIÓN MÉDICA' : '';
      $resultados[$key]->sector == 2 ?  $resultados[$key]->sector = 'GERENCIA' : ''; 
      $resultados[$key]->sector == 3 ?  $resultados[$key]->sector = 'AUDITORES MÉDICOS' : '';
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
$app->post('/autoridades/[{id}/]', function( $request, $response, array $args){
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

    isset($inputData['puesto'])?$data['puesto'] = html_entity_decode($inputData['puesto']):$data['puesto'] = "";
    isset($inputData['nombre'])?$data['nombre'] = html_entity_decode($inputData['nombre']):$data['nombre'] = "";
    isset($inputData['sector'])?$data['sector'] = html_entity_decode($inputData['sector']):$data['sector'] = "";
    
  

    //VALIDA QUE EL ID A MODIFICAR EXISTA EN LA BASE DE DATOS
    if(!empty($data['id'])){
       $validate = $db->prepare("SELECT id FROM autoridades WHERE id = :id ");
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

      $sql = "UPDATE autoridades 
                SET   puesto = :puesto,
                      nombre = :nombre,
                      sector = :sector
                      WHERE id = ".$data['id'];
                
    }else{

    $sql = "INSERT INTO autoridades (puesto, nombre, sector) 
          VALUES                (:puesto, :nombre, :sector) ";

    }
    
      $resultado = $db->prepare($sql);
      //SET DE DATOS LISTOS PARA SER INSERTADOS EN LA QUERY SQL
      $resultado->bindParam(':puesto', $data['puesto'] );
      $resultado->bindParam(':nombre', $data['nombre']);
      $resultado->bindParam(':sector', $data['sector']);
      $resultado->execute();

      if(!empty($data['id'])){
        return $response
          ->withStatus(200)
          ->withJson(array(
                    'code' => 200,
                    'msj' => "This medical service was modified correctly"
                    ));
      }

      if(empty($data['id'])){
        return $response
          ->withStatus(200)
          ->withJson(array(
                    'code' => 200,
                    'msj' => "New Medical Servide"
                    ));
      }

});



/************************ DELETE ************************/
$app->delete('/autoridades/[{id}/]', function( $request, $response, array $args){
  $db =  new db();
  $db = $db->conecctionDB();
  $id = $request->getAttribute('id');

  //BORRA EL ELEMENTO SELECCIONADO SEGUN SU ID
  $sql = "DELETE FROM autoridades WHERE id = $id";

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
