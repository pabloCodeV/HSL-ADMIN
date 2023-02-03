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
            <h2>Admin. Novedades</h2>
            <p>*Desde este sector, usted podra manejar el contenido de las novedades que se muestran en la pagina oficial del Hospital Sirio Libanes</p>
            <div>
                <table>
                    <thead>
                        <th style="width:3%">ID</th>
                        <th style="width:70%">Servicio de Telemedicina</th>
                        <th>Accion <button type="button" id="agregarTelemedicina" class="agregar" data-bs-toggle="modal"  data-bs-target="#modalAtributo">Nuevo</button></th>
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
        <h5 class="modal-title" id="exampleModalLabel">Telemedicina</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="telemedicinaform" enctype="multipart/form-data">
          <div class="mb-3">
          <label for="nombre" class="col-form-label">Servicio de Telemedicina:</label>
            <input type="text" class="form-control" id="nombreTelemedicina" name="nombre">
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

// BOTONES DEL DOM ********************************************************************

const agregarTelemedicina = document.getElementById('agregarTelemedicina')
const form = document.getElementById("telemedicinaform")
// ************************************************************************************

// ELEMENTOS DEL FORM *****************************************************************
const id = document.getElementById('id')
const nombre = document.getElementById('nombreTelemedicina')
// *************************************************************************************

// TABLA *******************************************************************************
const contenedor = document.querySelector('tbody')
let resultados  = ''
// *************************************************************************************

// COMPONENTE MODAL BOX DE BOOTSTRAP ***************************************************
const modalAtributo = new bootstrap.Modal(document.getElementById('modalAtributo'))
// *************************************************************************************

//CONSTANTES API ************************************************************************
const url_prod = 'https://hospitalsiriolibanes.ar/comunicacion/api/telemedicina/';
// const url_local="http://localhost/admin/api/telemedicina/";
// **************************************************************************************


// API GET   ****************************************************************************
axios.get(url_prod)
  .then(function (response) { 
    response.data.forEach( atributo=> {
  resultados+=`
                <tr>
                  <td>${atributo.id}</td>
                  <td>${atributo.nombre}</td>
                  <td><button id="borrarTelemedicina" class="borrar">Borrar</button><button id="modificarTelemedicina" class="modificar">Modificar</button></td>
              </tr>` 
  })
  contenedor.innerHTML = resultados
  })
  .catch(function (error) {console.log(error);})
  .then(function () { });

// **************************************************************************************

// EVENTO PARA DEJAR EL FORMULARIO EN BLANCO AL ABRIRLO O TRAER LOS DATOS DE UN ID ******
agregarTelemedicina.addEventListener('click',()=>{
  nombre.value  = ''
  modalAtributo.show()
  opcion = 'crear'
})

const on = (element, event, selector, handler) => {
  element.addEventListener(event, e => {
      if(e.target.closest(selector)){
          handler(e)
      }
  })
}

let idForm = 0
on(document, 'click', '#modificarTelemedicina', e => {    
  const fila = e.target.parentNode.parentNode
  idForm = fila.children[0].innerHTML
  const nombreTelemedicina = fila.children[1].innerHTML
  nombre.value =  nombreTelemedicina
  opcion = 'editar'
  modalAtributo.show()
})
// ****************************************************************************************


// RECURSO PARA BORRAR UN ELEMENTO ********************************************************
on(document, 'click', '#borrarTelemedicina', e => {
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

// RECURSO PARA CREAR UN ELEMENTO ********************************************************
form.addEventListener('submit', (e) => {
  e.preventDefault()
  const formData = new FormData(form)
  if(opcion == 'crear'){        
    axios.post(url_prod, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
    })
    .then(function (response) {location.reload()})
    .catch(function (error) {console.log(error);});
  }
   //**********************************************************************************
  
  // RECURSO PARA EDITAR UN ELEMENTO **************************************************
  if(opcion == 'editar'){    
    axios.post(url_prod + idForm + "/", formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
  })
  .then(function (response) {location.reload()})
  .catch(function (error) {console.log(error);});
  }
  //**********************************************************************************
  modalAtributo.hide()
})

</script>
</body>
</html>