<?php
  include '../api/routes/config/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <h2>Inscripciones de Escuela de enfermeria</h2>
            <p>*</p>
            <div>
                <table>
                    <thead>
                        <th style="width:auto">Nombre y Apellido</th>
                        <th style="width:20%">Telefono</th>
                        <th style="width:20%">Correo</th>
                        <th style="width:20%">Secundario completo</th>
                        <th style="width:20%">Creado en</th>
                        <!-- <th style="width:auto">Accion <button type="button" id="agregarNovedades" class="agregar" data-bs-toggle="modal"  data-bs-target="#modalAtributo" onclick=data()>Nuevo</button></th> -->
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>    
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script >

//CONSTANTES API ************************************************************************
const url_prod = 'https://hospitalsiriolibanes.ar/comunicacion/api/inscripciones/';
// const url_prod = "http://localhost/admin/api/inscripciones/";
// **************************************************************************************
// TABLA *******************************************************************************
const contenedor = document.querySelector('tbody')
let resultados  = ''
// *************************************************************************************

// API GET   ****************************************************************************
axios.get(url_prod)
  .then(function (response) { 
    console.log(response.data)
    response.data.forEach( atributo=> {
      resultados+=`
                    <tr>
                      <td>${atributo.nombre + " " + atributo.apellido }</td>
                      <td>${atributo.telefono}</td>
                      <td>${atributo.correo}</td>
                      <td>${atributo.secundario}</td>
                      <td>${atributo.create_at}</td>
                  </tr>` 
      })
      contenedor.innerHTML = resultados})
  .catch(function (error) {console.log(error);})
  .then(function () { });

</script>
</body>
</html>