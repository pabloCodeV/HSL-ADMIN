<?php
/******* GET  ***************/
$app->get('/sliders/[{id}/]', function($request, $response, array $args){
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
                                    imagenes,
                                    ubicacion
                                    FROM sliders 
                                    $where 
                                    ORDER BY id DESC 
                                    ");
  
  
      if(Isset($id)){
        $sql->bindParam(':id', $id );
      }
  
      $sql->execute();
      $resultados = $sql->fetchAll(PDO::FETCH_OBJ);

      foreach($resultados as $key => $value){
        $resultados[$key]->ubicacion == 0 ?  $resultados[$key]->ubicacion = 'HOME' : '';
        $resultados[$key]->ubicacion == 1 ?  $resultados[$key]->ubicacion = 'HEMODINAMI' : '';
        $resultados[$key]->ubicacion == 2 ?  $resultados[$key]->ubicacion = 'V. DEVOTO' : ''; 
        $resultados[$key]->ubicacion == 3 ?  $resultados[$key]->ubicacion = 'A. PALERMO' : '';
        $resultados[$key]->ubicacion == 4 ?  $resultados[$key]->ubicacion = 'R. GRANDE' : ''; 
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
$app->post('/sliders/[{id}/]', function( $request, $response, array $args){
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
  
    if(!empty($args['id'])){
      $data['id'] = $args['id'];  
    }
    if(!empty($_FILES['imagenes']['name'])){
        $file = $_FILES['imagenes']['name'];
        // ['name']
        foreach($file as $key => $value){
            var_dump($value);
            $tmp_dir = $_FILES['imagenes']['tmp_name'][$key];
            $imgSize = $_FILES['imagenes']['size'][$key];

            $upload_dir = '../sliders/'; // upload directory
            $extension = pathinfo($value, PATHINFO_ALL);
            $inputData['imagenes'] = $extension['basename'];
            move_uploaded_file($tmp_dir,$upload_dir.$value);

        }
      }
    $data['imagenes'] = $_FILES['imagenes']['name'];
    $data['ubicacion'] = $inputData['ubicacion'];

    $res  = $db->query('SELECT * FROM sliders');
    $res= $res->fetchAll();
  
  
      //SE COMPRUEBA QUE EL ID PASADO COMO ARGUMENTO SEA EXISTENTE
      if(!empty($data['id']) ){
        $exist = $db->query("SELECT COUNT(*) 
                            AS counter, imagenes
                            FROM sliders 
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
        $imagenes = json_decode($exist[0]["imagenes"]);
        $data['imagenes'] = json_encode(array_merge($imagenes, $data['imagenes']));
  
        $sql = "UPDATE sliders 
                  SET   imagenes = :imagenes, ubicacion = :ubicacion
                      WHERE id = ".$data['id'];
      }else{
        $sql = "INSERT INTO sliders (imagen,  ubicacion)
                VALUES     (:imagenes,  :ubicacion) ";
    
        }
  
    try{
      $resultado = $db->prepare($sql);
  
      //SET DE DATOS LISTOS PARA SER INSERTADOS EN LA QUERY SQL
      $resultado->bindParam(':imagenes', $data['imagenes'] );
      $resultado->bindParam(':ubicacion', $data['ubicacion'] );
  
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



//   DELETE IMAGENES *********************************************************

$app->delete('/sliders/[{id}/]', function( $request, $response, array $args){
  $db =  new db();
  $db = $db->conecctionDB();
  $id = $request->getAttribute('id');

  //BORRA EL ELEMENTO SELECCIONADO SEGUN SU ID
  $sql = "DELETE FROM sliders WHERE id = $id";

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