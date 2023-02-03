<?php

include '../api/routes/config/session.php';

$api_url = 'https://hospitalsiriolibanes.ar/comunicacion/api/eventos/';
// $api_url = 'http://localhost/admin/api/eventos/';

// Read JSON file
$json_data = file_get_contents($api_url);
$response_data = json_decode($json_data);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
    <link rel="stylesheet" href="css/style.css">
    <title>Dasboard | HSL</title>
</head>
<body>
    <div class="contenedor">
        <?php include "head/header.php"?>

        <div class="segunda-seccion">
            <h2>Admin. Novedades</h2>
            <p>*Desde este sector, usted podra manejar el contenido de las novedades que se muestran en la pagina oficial del Hospital Sirio Libanes</p>
            <div>
            <table>
                <thead>
                    <th>ID</th>
                    <th>NOMBRE</th>
                    <th>LOCALIDAD</th>
                    <th>TELEFONO</th>
                    <th>CARGO - INSTITUCION</th>

                    <th>DNI</th>
                    <th >CORREO</th>
                    <th>PARTICIPACION</th>
                    <th>EVENTO</th>
                    <th>INSCRIPCION</th>
                </thead>
            <tbody>
                
        <?php
        foreach($response_data as $key => $value){
            if($value->PARTICIPACION == 0)$participacion = "Virtual" ;
            if($value->PARTICIPACION == 1)$participacion = "Presencial";
        ?>
        <tr>
                <td class='id'><?php echo $value->ID?></td>
                <td><?php echo $value->NOMBRE." ". $value->APELLIDO?></td>
                <td><?php echo $value->LOCALIDAD?></td>
                <td><?php echo $value->TELEFONO?></td>
                <td><?php echo $value->CARGO." - ".$value->INSTITUCION?></td>
                <td><?php echo $value->DNI?></td>
                <td><?php echo $value->CORREO?></td>
                <td><?php echo $participacion?></td>
                <td><?php echo $value->EVENTO?></td>
                <?php 
                    if($value->INSCRIPCION != "" || $value->INSCRIPCION != NULL){
                    if($value->INSCRIPCION == 0)$inscripcion = "<spam class='approved'>ACEPTADO</spam>" ;
                    if($value->INSCRIPCION == 1)$inscripcion = "<spam class='cancel'>CANCELADO</spam>";
                    ?>
                    <td style="text-align:center;"><?php echo $inscripcion?>
                    <?php
                    }else{?>
                    <td style="text-align: center;justify-content: center;display: flex;">
                        <p id="response0"></p>
                        <button  id=acept name="INSCRIPCION"  class="approvedbtn modificar" onclick="acept('0',<?php echo $value->ID?>)" >+</button>
                        <button  id=cancel name="INSCRIPCION" class="cancelbtn borrar" onclick="cancel('1',<?php echo $value->ID?>)" >X</button>
                    </td>
                    <?php
                    }?>

                </tr>
                <?php }?>
            </tbody>
    </table>
            </div>
        </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>    
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script  charset="utf-8">

 const url_testing = 'https://hospitalsiriolibanes.ar/inscripciones/cms/api/allow/';
 const cancel = 'https://hospitalsiriolibanes.ar/inscripciones/cms/api/cancelado/';

  function acept(inscripcion,id) {
        document.getElementById('acept').disabled= true
        document.getElementById('cancel').disabled = true
        document.getElementById('acept').style.background  = 'grey'
        document.getElementById('cancel').style.background  = 'grey'
          console.log(inscripcion)
                          fetch(url+id+"/", {
                    method:'POST',
                    headers: {
                        'Content-Type':'application/json'
                    },
                    body: JSON.stringify({
                      id:id
                    })
                })
                .then( response => {response.json()
                    console.log(response) 
                  
                  document.getElementById('acept').style.display = "none";
                  document.getElementById('cancel').style.display = "none";
                  window.location.reload()
                })
                .then(data => {console.log(data)});   
            }   

  function cancel(inscripcion,id) {
        document.getElementById('acept').disabled= true
        document.getElementById('cancel').disabled = true
        document.getElementById('acept').style.background  = 'grey'
        document.getElementById('cancel').style.background  = 'grey'
          console.log(inscripcion)
                          fetch(urlcancel+id+"/", {
                    method:'POST',
                    headers: {
                        'Content-Type':'application/json'
                    },
                    body: JSON.stringify({
                      id:id
                    })
                })
                .then( response => {response.json()
                    console.log(response) 
                  
                  document.getElementById('acept').style.display = "none";
                  document.getElementById('cancel').style.display = "none";
                  window.location.reload()
                })

                .then(data => {console.log(data)});   
            }  


</script>
</body>
</html>