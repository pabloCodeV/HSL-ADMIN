<?php

/******* GET  ***************/
$app->get('/preintervencion/[{id}/]', function($request, $response, array $args){
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


        $sql = $db->prepare("SELECT * FROM pre_intervencionismo  $where  ORDER BY id DESC");
        
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
$app->post('/preintervencion/[{id}/]', function( $request, $response, array $args){
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
    $data['dni'] = intval($inputData['dni']);   
    $data['edad'] = intval($inputData['edad']);
    $data['f_nacimiento'] = $inputData['f_nacimiento'];
    $data['telefono'] = intval($inputData['telefono']);
    $data['correo'] = sanitize(html_entity_decode($inputData['correo']));
    $data['calle'] = sanitize(html_entity_decode($inputData['calle']));
    $data['altura'] = sanitize(html_entity_decode($inputData['altura']));
    $data['piso'] = intval($inputData['piso']);
    $data['dpto'] = intval($inputData['dpto']);
    $data['obra_social'] = sanitize(html_entity_decode($inputData['obra_social']));
    $data['n_beneficiario'] = sanitize($inputData['n_beneficiario']);
    $data['antecedentes'] = sanitize($inputData['antecedentes']);
    $data['cirugia_cardiaca_previa'] = sanitize(html_entity_decode($inputData['cirugia_cardiaca_previa']));
    $data['cancer_previa'] = sanitize(html_entity_decode($inputData['cancer_previa']));
    $data['alergias'] = sanitize(html_entity_decode($inputData['alergias']));
    $data['medicamentos'] = sanitize(html_entity_decode($inputData['medicamentos']));
    
    $res  = $db->query('SELECT * FROM pre_intervencionismo');
    $res= $res->fetchAll();
  
  
      //SE COMPRUEBA QUE EL ID PASADO COMO ARGUMENTO SEA EXISTENTE
      if(!empty($data['id']) ){
        $exist = $db->query("SELECT COUNT(*) 
                            AS counter 
                            FROM pre_intervencionismo 
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
  
        $sql = "UPDATE pre_intervencionismo 
                  SET   nombre = :nombre,
                        dni = :dni,
                        edad = :edad,
                        f_nacimiento = :f_nacimiento,
                        telefono = :telefono,
                        correo = :correo,
                        calle = :calle,
                        altura = :altura,
                        piso = :piso,
                        dpto = :dpto,
                        obra_social = :obra_social,
                        n_beneficiario = :n_beneficiario,
                        antecedentes = :antecedentes,
                        cirugia_cardiaca_previa = :cirugia_cardiaca_previa,
                        cancer_previa = :cancer_previa,
                        alergias = :alergias,
                        medicamentos = :medicamentos,
                        create_at = now()
                      WHERE id = ".$data['id'];
      }
  
      if(empty($data["id"])){   

        $sql = "INSERT INTO pre_intervencionismo (nombre, dni, edad, f_nacimiento, telefono, correo, calle,
                                      altura, piso, dpto, obra_social, n_beneficiario, antecedentes, cirugia_cardiaca_previa, 
                                      cancer_previa, alergias, medicamentos, create_at) 
                    VALUES           (:nombre, :dni, :edad, :f_nacimiento, :telefono, :correo, :calle,
                                      :altura, :piso, :dpto, :obra_social, :n_beneficiario, :antecedentes, :cirugia_cardiaca_previa, 
                                      :cancer_previa, :alergias, :medicamentos, NOW()) ";
  
      }
  
    try{
      $resultado = $db->prepare($sql);
  
      //SET DE DATOS LISTOS PARA SER INSERTADOS EN LA QUERY SQL
      $resultado->bindParam(':nombre', $data['nombre'] );
      $resultado->bindParam(':dni', $data['dni']);
      $resultado->bindParam(':edad', $data['edad']);
      $resultado->bindParam(':f_nacimiento', $data['f_nacimiento']);
      $resultado->bindParam(':telefono', $data['telefono'] );
      $resultado->bindParam(':correo', $data['correo']);
      $resultado->bindParam(':calle', $data['calle']);
      $resultado->bindParam(':altura', $data['altura']);
      $resultado->bindParam(':piso', $data['piso']);
      $resultado->bindParam(':dpto', $data['dpto']);
      $resultado->bindParam(':obra_social', $data['obra_social'] );
      $resultado->bindParam(':n_beneficiario', $data['n_beneficiario']);
      $resultado->bindParam(':antecedentes', $data['antecedentes']);
      $resultado->bindParam(':cirugia_cardiaca_previa', $data['cirugia_cardiaca_previa']);
      $resultado->bindParam(':cancer_previa', $data['cancer_previa'] );
      $resultado->bindParam(':alergias', $data['alergias'] );
      $resultado->bindParam(':medicamentos', $data['medicamentos']);
  
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
  
  
/************************ DELETE ************************/
$app->delete('/preintervencion/[{id}/]', function( $request, $response, array $args){
    $db =  new db();
    $db = $db->conecctionDB();
    $id = $request->getAttribute('id');
  
    //BORRA EL ELEMENTO SELECCIONADO SEGUN SU ID
    $sql = "DELETE FROM pre_intervencionismo WHERE id = $id";
  
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