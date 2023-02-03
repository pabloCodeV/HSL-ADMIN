<?php

use Verot\Upload\Upload;

/******* GET  ***************/
$app->get('/novedades/[{id}/]', function($request, $response, array $args){
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
                                  titulo,
                                  imagen,
                                  imagen1,
                                  imagen2,
                                  imagen3,
                                  video,
                                  video1,
                                  video2,
                                  video3,
                                  tipo,
                                  short_description,
                                  long_description,
                                  create_at
                                  FROM novedades 
                                  $where 
                                  ORDER BY create_at DESC 
                                  ");


    if(Isset($id)){
      $sql->bindParam(':id', $id );
    }

    $sql->execute();
    $resultados = $sql->fetchAll(PDO::FETCH_OBJ);
    // var_dump(nl2br($resultados[0]->long_description));exit;

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
$app->post('/novedades/[{id}/]', function( $request, $response, array $args){
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
    $file = $_FILES['imagen'];
    $foo  = new Upload($file);
    $path = '../images/'; // upload directory
    switch($inputData['size']){
      case 0:
        $size_x                  = 1600;
        $size_y                  = 900;
        break;
      case 1:
        $size_x                  = 800;
        $size_y                  = 800;
        break;
  
    }
    // cargamos imagenes
    if($_FILES['imagen']['name']){
      $foo->file_new_name_body = sprintf('%spx_%s', $size_x."x".$size_y, time());
      $foo->image_resize       = true;
      if($inputData['size'] != 2){
        $foo->image_x            = $size_x;
        $foo->image_y            = $size_y;
      }
      $foo->process($path);

      $extension = pathinfo($file['name'], PATHINFO_ALL);
      $inputData['imagen'] = $extension['basename'];

    }

    if(!empty($_FILES['imagen1']['name'])){
      $foo->file_new_name_body = sprintf('%spx_%s', $size_x."x".$size_y, time());
      $foo->image_resize       = true;
      $foo->image_x            = $size_x;
      $foo->image_y            = $size_y;
      $foo->process($path);

      $extension = pathinfo($file['name'], PATHINFO_ALL);
      $inputData['imagen1'] = $extension['basename'];
    }

    if(!empty($_FILES['imagen2']['name'])){
      $foo->file_new_name_body = sprintf('%spx_%s', $size_x."x".$size_y, time());
      $foo->image_resize       = true;
      $foo->image_x            = $size_x;
      $foo->image_y            = $size_y;
      $foo->process($path);

      $extension = pathinfo($file['name'], PATHINFO_ALL);
      $inputData['imagen2'] = $extension['basename'];
    }
    
    if(!empty($_FILES['imagen3']['name'])){
      $foo->file_new_name_body = sprintf('%spx_%s', $size_x."x".$size_y, time());
      $foo->image_resize       = true;
      $foo->image_x            = $size_x;
      $foo->image_y            = $size_y;
      $foo->process($path);

      $extension = pathinfo($file['name'], PATHINFO_ALL);
      $inputData['imagen3'] = $extension['basename'];
    }

// cargamos videos
    if(!empty($_FILES['video']['name'])){
      $file = $_FILES['video']['name'];
      $tmp_dir = $_FILES['video']['tmp_name'];
      $imgSize = $_FILES['video']['size'];
  
      $upload_dir = '../images/'; // upload directory
    
      $extension = pathinfo($file, PATHINFO_ALL);
      $inputData['video'] = $extension['basename'];
      move_uploaded_file($tmp_dir,$upload_dir.$file);
    }

    if(!empty($_FILES['video1']['name'])){
      $file = $_FILES['video1']['name'];
      $tmp_dir = $_FILES['video1']['tmp_name'];
      $imgSize = $_FILES['video1']['size'];
  
      $upload_dir = '../images/'; // upload directory
    
      $extension = pathinfo($file, PATHINFO_ALL);
      $inputData['video1'] = $extension['basename'];
      move_uploaded_file($tmp_dir,$upload_dir.$file);
    }
    if(!empty($_FILES['video2']['name'])){
      $file = $_FILES['video2']['name'];
      $tmp_dir = $_FILES['video2']['tmp_name'];
      $imgSize = $_FILES['video2']['size'];
  
      $upload_dir = '../images/'; // upload directory
    
      $extension = pathinfo($file, PATHINFO_ALL);
      $inputData['video2'] = $extension['basename'];
      move_uploaded_file($tmp_dir,$upload_dir.$file);
    }

    if(!empty($_FILES['video3']['name'])){
      $file = $_FILES['video3']['name'];
      $tmp_dir = $_FILES['video3']['tmp_name'];
      $imgSize = $_FILES['video3']['size'];
  
      $upload_dir = '../images/'; // upload directory
    
      $extension = pathinfo($file, PATHINFO_ALL);
      $inputData['video3'] = $extension['basename'];
      move_uploaded_file($tmp_dir,$upload_dir.$file);
    }

  }

    isset($inputData['titulo'])?$data['titulo'] = strtoupper(html_entity_decode($inputData['titulo'])):$data['titulo'] = "";
    !empty($inputData['imagen'])?$data['imagen'] = $inputData['imagen']: $data['imagen'] = null;
    !empty($inputData['imagen1'])?$data['imagen1'] = $inputData['imagen1']: $data['imagen1'] = null;
    !empty($inputData['imagen2'])?$data['imagen2'] = $inputData['imagen2']: $data['imagen2'] = null;
    !empty($inputData['imagen3'])?$data['imagen3'] = $inputData['imagen3']: $data['imagen3'] = null;
    !empty($inputData['video'])?$data['video'] = $inputData['video']: $data['video'] = null;
    !empty($inputData['video1'])?$data['video1'] = $inputData['video1']: $data['video1'] = null;
    !empty($inputData['video2'])?$data['video2'] = $inputData['video2']: $data['video2'] = null;
    !empty($inputData['video3'])?$data['video3'] = $inputData['video3']: $data['video3'] = null;
    isset($inputData['short_description'])?$data['short_description'] = nl2br(html_entity_decode($inputData['short_description'])):$data['short_description'] = "";
    isset($inputData['long_description'])?$data['long_description'] = nl2br(html_entity_decode($inputData['long_description']) ):$data['long_description'] = "";
    isset($inputData['tipo'])?$data['tipo'] = $inputData['tipo']:$data['tipo'] = 1;
    isset($inputData['create_at'])?$data['create_at'] = $inputData['create_at']:$data['create_at'] = 0000-00-00;

    //VALIDA QUE EL ID A MODIFICAR EXISTA EN LA BASE DE DATOS
    if(!empty($data['id'])){
       $validate = $db->prepare("SELECT id, imagen, imagen1, imagen2, imagen3, video, video1, video2, video3 FROM novedades WHERE id = :id ");
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

       if(empty($data['imagen']) || $data['imagen'] == '') $data['imagen'] = $resultados[0]->imagen;
       if(empty($data['imagen1']) || $data['imagen1'] == '') $data['imagen1'] = $resultados[0]->imagen1;
       if(empty($data['imagen2']) || $data['imagen2'] == '') $data['imagen2'] = $resultados[0]->imagen2;
       if(empty($data['imagen3']) || $data['imagen3'] == '') $data['imagen3'] = $resultados[0]->imagen3;

       if(empty($data['video']) || $data['video'] == '') $data['video'] = $resultados[0]->video;
       if(empty($data['video1']) || $data['video1'] == '') $data['video1'] = $resultados[0]->video1;
       if(empty($data['video2']) || $data['video2'] == '') $data['video2'] = $resultados[0]->video2;
       if(empty($data['video3']) || $data['video3'] == '') $data['video3'] = $resultados[0]->video3;

      $sql = "UPDATE novedades
                SET   titulo = :titulo,
                      imagen = :imagen,
                      imagen1 = :imagen1,
                      imagen2 = :imagen2,
                      imagen3 = :imagen3,
                      video = :video,
                      video1 = :video1,
                      video2 = :video2,
                      video3 = :video3,
                      tipo = :tipo,
                      short_description = :short_description,
                      long_description = :long_description,
                      create_at = :create_at
                      WHERE id = ".$data['id'];

    }else{

    $sql = "INSERT INTO novedades (titulo, imagen, imagen1, imagen2, imagen3, video, video1, video2, video3, short_description, long_description, tipo, create_at)
          VALUES                (:titulo, :imagen, :imagen1, :imagen2, :imagen3, :video, :video1, :video2, :video3, :short_description, :long_description, :tipo, :create_at) ";

    }

      $resultado = $db->prepare($sql);
      //SET DE DATOS LISTOS PARA SER INSERTADOS EN LA QUERY SQL
      $resultado->bindParam(':titulo', $data['titulo'] );
      $resultado->bindParam(':imagen', $data['imagen']);
      $resultado->bindParam(':imagen1', $data['imagen1']);
      $resultado->bindParam(':imagen2', $data['imagen2']);
      $resultado->bindParam(':imagen3', $data['imagen3']);
      $resultado->bindParam(':video', $data['video']);
      $resultado->bindParam(':video1', $data['video1']);
      $resultado->bindParam(':video2', $data['video2']);
      $resultado->bindParam(':video3', $data['video3']);
      $resultado->bindParam(':short_description', $data['short_description']);
      $resultado->bindParam(':long_description', $data['long_description'] );
      $resultado->bindParam(':tipo', $data['tipo'] );
      $resultado->bindParam(':create_at', $data['create_at'] );
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
$app->delete('/novedades/[{id}/]', function( $request, $response, array $args){
  $db =  new db();
  $db = $db->conecctionDB();
  $id = $request->getAttribute('id');

  //BORRA EL ELEMENTO SELECCIONADO SEGUN SU ID
  $sql = "DELETE FROM novedades WHERE id = $id";

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
