<?php
session_start();
if(Isset($_SESSION["token"])){
  if(!empty($_SESSION["token"] && !empty($_COOKIE["token"]) &&  $_SESSION["token"] === $_COOKIE["token"] ) && $_COOKIE['rules'] == "hemodinamia")  header("Location: preintervencionismo.php");
  if(!empty($_SESSION["token"] && !empty($_COOKIE["token"]) &&  $_SESSION["token"] === $_COOKIE["token"] && $_COOKIE['rules'] == "admin" ))  header("Location: inicio.php");
}

if(!empty($_POST['usuario']) ||  !empty($_POST['clave'])){

  $data = [
    'usuario' => $_POST['usuario'],
    'clave' => $_POST['clave']
  ];
  // $curl = curl_init('http://localhost/admin/api/customers_login/');
  $curl = curl_init('https://hospitalsiriolibanes.ar/comunicacion/api/customers_login/');
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($curl);
  $response = json_decode($response,true);

  switch($response['code']){
    case 200:
      $token = sha1(uniqid(rand(),true));
      $_SESSION["token"]  = $token;
      setcookie("token",$token,time()+(60*60*24*31),"/");
      setcookie("rules",$response['data'][0]['rules'],time()+(60*60*24*31),"/"); 
      header("Location: /comunicacion/admin/index.php?code=200",TRUE,301);
      break;
    case 400:
      header("Location: /comunicacion/admin/index.php?code=400",TRUE,301);
      break;
    case 401:
      header("Location: /comunicacion/admin/index.php?code=401",TRUE,301);
      break;
    case null:
      header("Location: /comunicacion/admin/index.php?code=401",TRUE,301);
      break;
  }
}
?>
<script>

const urlSearchParams = new URLSearchParams(window.location.search);
const code = urlSearchParams.get("code");
switch(code){
  case '200':
    window.location.replace('inicio.php')
    break;
  case '400':
    alert("Ocurrio un problema al intentar iniciar sesion, vuelva a intentarlo.")
    break;
  case '401':
    alert("Ocurrio un problema al intentar iniciar sesion, vuelva a intentarlo.")
    break;
}
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Dasboard | HSL</title>
</head>
<body >
    <div class="contenedor" id="index">
        <form class="loginAdmin" method="post">
                <h2>Login</h2>
                <label for="Usuario">Usuario
                    <input type="text" name="usuario" id="usuario">
                </label>
                <label for="Clave">Clave
                    <input type="password" name="clave" id="clave">
                </label>
                <button type="submit" id="btnLogin"  >Ingresar</button>
                
        </form>
    </div>
    
</body>
</html>