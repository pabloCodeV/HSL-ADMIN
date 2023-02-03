<?php

/********* POST LOGIN -> SE UTILIZA PARA EL INGRESO A LA PAGINA ***********/
$app->post('/customers_login/', function ($request, $response) {

  session_start();
  $db =  new db();                        
  $db = $db->conecctionDB();
  $inputData = $request->getParsedBody();

  if(is_null($inputData)){    
    return $response
      ->withStatus(400)
      ->withJson(array(
                        'code' => 400,
                        'msj' => "Bad Request"
    ));
  }
  $data['usuario'] = $inputData['usuario'];
  $data['clave'] = sanitize($inputData['clave']);
  $data['clave'] = MD5($inputData['clave']);

  $exist = $db->prepare("SELECT id, rules FROM usuarios WHERE usuario = :usuario AND CLAVE = :clave");
  $exist->bindParam(':usuario', $data['usuario'] , PDO::PARAM_STR);
  $exist->bindParam(':clave', $data['clave'] , PDO::PARAM_STR);
  $exist->execute();
  $resultados = $exist->fetchAll(PDO::FETCH_OBJ);

  if(empty($resultados)){
    return $response
    ->withStatus(401)
    ->withJson(array(
              'code' => 401,
              'msj' => "Bad Request",
              
    ));
  }
  return $response->withStatus(200)
                  ->withJson(array(
                            'code' => 200,
                            'data' => $resultados
  ));

});