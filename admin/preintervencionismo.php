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
            <h2>Hemodinamia / Pre Intervencionismo</h2>  
            <div>
                <table>
                    <thead>
                        <th style="width:3%">ID</th>
                        <th style="width:40%">Nombre</th>
                        <th style="width:auto">Dni</th>
                        <th style="width:auto">Correo</th>
                        <th style="width:auto">Telefono</th>
                        <th style="width:auto">Creado</th>
                        <th>Accion </th>
                    </thead>
                    <tbody>
                    </tbody>
                    
                </table>
            </div>
        </div>


<div id="modalAtributo" class="modal fade" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">Pre intervencionismo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="serviciosform" enctype="multipart/form-data">
          <div class="mb-3">
          <label for="nombre" class="col-form-label">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" disabled>
          </div>
          <div class="mb-3">
          <label for="nombre" class="col-form-label">Dni:</label>
            <input type="text" class="form-control" id="dni" name="dni" disabled>
          </div>          
          <div class="mb-3">
          <label for="nombre" class="col-form-label">Edad:</label>
            <input type="text" class="form-control" id="edad" name="edad" disabled>
          </div>          
          <div class="mb-3">
          <label for="nombre" class="col-form-label">Fecha de Nacimiento:</label>
            <input type="text" class="form-control" id="nacimiento" name="nacimiento" disabled>
          </div>          
          <div class="mb-3">
          <label for="nombre" class="col-form-label">Telefono:</label>
            <input type="text" class="form-control" id="telefono" name="telefono" disabled>
          </div>          
          <div class="mb-3">
          <label for="nombre" class="col-form-label">Correo:</label>
            <input type="text" class="form-control" id="correo" name="correo" disabled>
          </div>  


          <div class="form-row">
            <div class="form-group col-md-7">
              <label for="nombre" class="col-form-label">Direccion:</label>
              <input type="text" class="form-control" id="direccion" name="direccion" disabled>
            </div>
            <div class="form-group col-md-2">
              <label for="nombre" class="col-form-label">Piso:</label>
              <input type="text" class="form-control" id="piso" name="piso" disabled>
            </div>
            <div class="form-group col-md-2">
              <label for="nombre" class="col-form-label">Dpto:</label>
              <input type="text" class="form-control" id="dpto" name="dpto" disabled>
            </div>
          </div>
                  

        <div class="form-row">
          <div class="form-group col-md-5">
            <label for="nombre" class="col-form-label">Obra Social:</label>
            <input type="text" class="form-control" id="obrasocial" name="obrasocial" disabled>
          </div>
          <div class="form-group col-md-6">
            <label for="nombre" class="col-form-label">N de beneficiario:</label>
            <input type="text" class="form-control" id="beneficiario" name="beneficiario" disabled>
          </div>
        </div>

          <div class="mb-3">
          <label for="nombre" class="col-form-label">Antesedentes:</label>
          <textarea type="text" class="form-control" id="antesedentes" name="antesedentes" disabled> </textarea>
          </div>
          <div class="mb-3">
          <label for="nombre" class="col-form-label">Cirugia cardiaca previa:</label>
            <textarea  type="text" class="form-control" id="cardiaca" name="cardiaca" disabled></textarea >
          </div>
          <div class="mb-3">
          <label for="nombre" class="col-form-label">Cancer previo:</label>
            <textarea  type="text" class="form-control" id="cancer" name="cancer" disabled></textarea >
          </div>
          <div class="mb-3">
          <label for="nombre" class="col-form-label">Alergias:</label>
            <textarea  type="text" class="form-control" id="alergias" name="alergias" disabled></textarea >
          </div>
          <div class="mb-3">
          <label for="nombre" class="col-form-label">Medicamentos:</label>
            <textarea  type="text" class="form-control" id="medicamentos" name="medicamentos" disabled></textarea >
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
    </div>
  </div>
  </div>


  


</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>    
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script >
const ver = document.getElementById('modificarServicio');
// BOTONES DEL DOM ********************************************************************
const agregarServicio = document.getElementById('agregarServicio')
const form = document.getElementById("serviciosform")
// ************************************************************************************

// TABLA *******************************************************************************
const contenedor = document.querySelector('tbody')
let resultados  = ''
// *************************************************************************************

// COMPONENTE MODAL BOX DE BOOTSTRAP ***************************************************
const modalAtributo = new bootstrap.Modal(document.getElementById('modalAtributo'))
// *************************************************************************************

//CONSTANTES API ************************************************************************
const url_prod = 'https://hospitalsiriolibanes.ar/comunicacion/api/preintervencion/';
// const url_local="http://localhost/admin/api/preintervencion/";
// **************************************************************************************
// API GET   ****************************************************************************
axios.get(url_prod)
  .then(function (response) { 
    this.response = response
    response.data.forEach( atributo=> {
      var antecedentes = JSON.stringify(atributo.antecedentes);
    resultados+=`
                  <tr>
                    <td>${atributo.id}</td>
                    <td>${atributo.nombre}</td>
                    <td>${atributo.dni}</td>
                    <td>${atributo.correo}</td>
                    <td>${atributo.telefono}</td>
                    <td>${atributo.create_at}</td>
                    <td><button id="modificarServicio" class="modificar" onClick='data(${atributo.id})'>VER</button></td>
                </tr>` 
    })
    contenedor.innerHTML = resultados

  }.bind(this))
  .catch(function (error) {console.log(error);})
  .then(function () { });

// **************************************************************************************
 function data(id){

  axios.get(url_local + id + "/")
  .then(function (response) { 
    modalAtributo.show()
    antecedentes = JSON.parse(response.data[0].antecedentes)
    antecedentes_string = antecedentes.toString()

    document.getElementById('nombre').value = response.data[0].nombre
    document.getElementById('dni').value = response.data[0].dni
    document.getElementById('edad').value = response.data[0].edad
    document.getElementById('nacimiento').value = response.data[0].f_nacimiento
    document.getElementById('telefono').value = response.data[0].telefono
    document.getElementById('correo').value = response.data[0].correo
    document.getElementById('direccion').value = response.data[0].calle + " " + response.data[0].altura
    document.getElementById('piso').value = response.data[0].piso
    document.getElementById('dpto').value = response.data[0].dpto
    document.getElementById('obrasocial').value = response.data[0].obra_social
    document.getElementById('beneficiario').value = response.data[0].n_beneficiario
    document.getElementById('antesedentes').value = antecedentes_string
    document.getElementById('cardiaca').value = response.data[0].cirugia_cardiaca_previa
    document.getElementById('cancer').value = response.data[0].cancer_previa
    document.getElementById('alergias').value = response.data[0].alergias
    document.getElementById('medicamentos').value = response.data[0].medicamentos
  })
  .catch(function (error) {console.log(error);})
  .then(function () { });

 }

// ****************************************************************************************


// RECURSO PARA BORRAR UN ELEMENTO ********************************************************
on(document, 'click', '#borrarServicio', e => {
  const fila = e.target.parentNode.parentNode
  const id = fila.firstElementChild.innerHTML
    alertify.confirm("BORRAR","¿Desea borrar este Servicio?.",
    function(){
        fetch(url_prod + id + '/', {
            method: 'DELETE'
        })
        .then( res => res.json() )
        .then( () => location.reload())
    },
    function(){
        alertify.error('Cancel')
    })
})

// ***************************************************************************************


</script>
</body>
</html>