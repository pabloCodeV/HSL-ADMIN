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
            <h2>Sliders para las diferentes secciones</h2>
            <p>*Desde este sector, usted podra cambiar los banners que se muestran en la portada de cada pagina<br/>
               *Es necesario que se respete la medida de <b>1920x380</b> a menos que el departamento de comunicaciones decida redimencionar las imagenes</p>
            <div>
                <table>
                    <thead>
                        <th id="id" style="width:3%">id</th>
                        <th style="width:5%">Imagenes</th>
                        <th style="width:70%">Ubicacion</th>
                        <th style="width:auto">Accion <button type="button" id="agregar" class="agregar" data-bs-toggle="modal"  data-bs-target="#modalAtributo" onclick=data()>Nuevo</button></th>

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
        <h5 class="modal-title" id="exampleModalLabel">Articulo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form" enctype="multipart/form-data">
          <div class="mb-3">
              <label for="direccion" class="col-form-label">Cargar Imagenes:</label>
              <input type="file" class="form-control" id="imagenes" name="imagenes" multiple="multiple">
          </div>
          <div class="mb-3">
            <label for="Tipo" class="col-form-label">Ubicacion:</label>
            <select class="form-select" name="ubicacion" id="ubicacion">
                <option selected>--SELECCIONE--</option>
                <option value="0">HOME</option>
                <option value="1">HEMODINAMIA</option>
                <option value="2">V. DEVOTO</option>
                <option value="3">A. PALERMO</option>
                <option value="4">R. GRANDE</option>
            </select>
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

// BOTONES DEL DOM 
const form = document.getElementById('form')
const agregar = document.getElementById('agregar')

// ELEMENTOS DEL FORM 
const id = document.getElementById('id')
const imagen = document.getElementById('imagen')

// TABLA 
const contenedor = document.querySelector('tbody')
let resultados  = ''

// COMPONENTE MODAL BOX DE BOOTSTRAP 
const modalAtributo = new bootstrap.Modal(document.getElementById('modalAtributo'))

//CONSTANTES API 
const url_prod = 'https://hospitalsiriolibanes.ar/comunicacion/api/sliders/';
// const url_prod="http://localhost/admin/api/sliders/";



// API GET   
axios.get(url_prod)
  .then(function (response) { 
    console.log(response)
    response.data.forEach( atributo=> {
        imagenes = JSON.parse(atributo.imagenes)
        if(imagenes[0] == undefined){
            imagenes[0] = "noimg.jpg"
        }
      resultados+=`
                    <tr>
                      <td>${atributo.id}</td>
                      <td><img src="../sliders/${imagenes[0]}" /></td>
                      <td>${atributo.ubicacion}</td>
                      <td><button id="borrar" class="borrar" onclick='borrar(${atributo.id})' >Borrar</button><button id="modificar" class="modificar" onclick='data(${atributo.id})'>Modificar</button></td>
                  </tr>` 
      })
      contenedor.innerHTML = resultados})
  .catch(function (error) {console.log(error);})
  .then(function () { });

function data(id){
    if(id){
      axios.get(url_prod + id + "/")
    .then(function (response) {
      modalAtributo.show()
      document.getElementById('id').value = response.data[0].id
      document.getElementById('ubicacion').value = response.data[0].ubicacion
    })
    .catch(function (error) {console.log(error);})
    .then(function () { });
    }
}

function borrar(id_delete){
  alertify.confirm("BORRAR","??Desea borrar esta noticia?.",
    function(){
        fetch(url_prod + id_delete + '/', {
            method: 'DELETE'
        })
        .then( res => res.json() )
        .then( () => location.reload())
    },
    function(){
        alertify.error('Cancel')
    })
}


form.addEventListener('submit', (e) => {
  e.preventDefault()
  const formData = new FormData(form)
  if(document.getElementById('id').value){
    idForm = document.getElementById('id').value
    axios.post(url_prod + idForm + "/", formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
  })
  .then(function (response) {location.reload()})
  .catch(function (error) {console.log(error);});

  }else{
    e.preventDefault()
    // carga las fotos
      var imagefile = document.querySelector('#imagen');
      formData.append("imagen", imagefile.files[0]);

        axios.post(url_prod, formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
        })
        .then(function (response) {location.reload()})
        .catch(function (error) {console.log(error);});
  }
  })



</script>
</body>
</html>