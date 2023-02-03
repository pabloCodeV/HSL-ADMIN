<?php
/******* GET  ***************/
$app->get('/banner_section/[{id}/]', function($request, $response, array $args){
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
                                  imagen,
                                  section
                                  FROM banner_section 
                                  $where 
                                  ORDER BY id DESC 
                                  ");


    if(Isset($id)){
      $sql->bindParam(':id', $id );
    }

    $sql->execute();
    $resultados = $sql->fetchAll(PDO::FETCH_OBJ);

    foreach($resultados as $key => $value){
      $resultados[$key]->section == 0 ?  $resultados[$key]->section = 'EL HOSPITAL' : '';
      $resultados[$key]->section == 1 ?  $resultados[$key]->section = 'CUERPO MEDICO' : '';
      $resultados[$key]->section == 2 ?  $resultados[$key]->section = 'SERVICIOS MEDICOS' : ''; 
      $resultados[$key]->section == 3 ?  $resultados[$key]->section = 'DIAGNOSTICO POR IMAGENES' : '';
      $resultados[$key]->section == 4 ?  $resultados[$key]->section = 'SERVICIO DE LABORATORIO' : ''; 
      $resultados[$key]->section == 5 ?  $resultados[$key]->section = 'COBERTURA' : '';
      $resultados[$key]->section == 6 ?  $resultados[$key]->section = 'ESCUELA DE ENFERMERIA' : '';
      $resultados[$key]->section == 7 ?  $resultados[$key]->section = 'INSCRIPCION DE ENFERMERIA' : ''; 
      $resultados[$key]->section == 8 ?  $resultados[$key]->section = 'SALIDA LABORAL' : '';
      $resultados[$key]->section == 9 ?  $resultados[$key]->section = 'RESIDENCIA' : '';
      $resultados[$key]->section == 10 ?  $resultados[$key]->section = 'NOVEDADES' : ''; 
      $resultados[$key]->section == 11 ?  $resultados[$key]->section = 'CONTACTO' : '';
      $resultados[$key]->section == 12 ?  $resultados[$key]->section = 'TELEMEDICINA' : ''; 
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
$app->post('/banner_section/[{id}/]', function( $request, $response, array $args){
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

  //VALIDA LA EXISTENCIA DE UN ARCHIVO
  if(!empty($request->getUploadedFiles())){
    // cargamos imagenes
    if($_FILES['imagen']['name']){
      $file = $_FILES['imagen']['name'];
      $tmp_dir = $_FILES['imagen']['tmp_name'];
      $imgSize = $_FILES['imagen']['size'];
  
      $upload_dir = '../banners/'; // upload directory
    
      $extension = pathinfo($file, PATHINFO_ALL);
      $inputData['imagen'] = $extension['basename'];
      move_uploaded_file($tmp_dir,$upload_dir.$file);
    }

  }

    !empty($inputData['imagen'])?$data['imagen'] = $inputData['imagen']: $data['imagen'] = null;
    isset($inputData['section'])?$data['section'] = $inputData['section']:$data['section'] = null;

    //VALIDA QUE EL ID A MODIFICAR EXISTA EN LA BASE DE DATOS
    if(!empty($data['id'])){
       $validate = $db->prepare("SELECT id, imagen FROM banner_section WHERE id = :id ");
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

       if(!empty($data['imagen']) || $data['imagen'] == '') $data['imagen'] = $resultados[0]->imagen;

      $sql = "UPDATE banner_section
                SET   imagen = :imagen,
                      section = :section
                      WHERE id = ".$data['id'];

    }else{

    $sql = "INSERT INTO banner_section (imagen,  section)
            VALUES                     (:imagen,  :section) ";

    }

      $resultado = $db->prepare($sql);
      //SET DE DATOS LISTOS PARA SER INSERTADOS EN LA QUERY SQL
      $resultado->bindParam(':imagen', $data['imagen'] );
      $resultado->bindParam(':section', $data['section']);
      $resultado->execute();

      if(!empty($data['id'])){
        return $response
          ->withStatus(200)
          ->withJson(array(
                    'code' => 200,
                    'msj' => "This news was modified correctly"
                    ));
      }

      if(empty($data['id'])){
        return $response
          ->withStatus(200)
          ->withJson(array(
                    'code' => 200,
                    'msj' => "New news "
                    ));
      }

});


/************************ DELETE ************************/
$app->delete('/banner_section/[{id}/]', function( $request, $response, array $args){
  $db =  new db();
  $db = $db->conecctionDB();
  $id = $request->getAttribute('id');

  //BORRA EL ELEMENTO SELECCIONADO SEGUN SU ID
  $sql = "DELETE FROM banner_section WHERE id = $id";

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
