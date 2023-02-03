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
            <p>*Desde este sector, usted podra manejar el contenido de las novedades que se muestran en la pagina oficial del Hospital Sirio Libanes<br/>
              *El tamañoo se podra modificar desde el formulario, los formatos predeterminados son de 1600x900, en la medida de lo posible, es necesario que la imagen que se suba ya sea de esas proporciones para que la imagen no sufra alguna deformacion</p>
            <div>
                <table>
                    <thead>
                        <th id="id" style="width:3%">Fecha</th>
                        <th style="width:5%">Imagen</th>
                        <th style="width:70%">Titulo</th>
                        <th style="width:auto">Accion <button type="button" id="agregarNovedades" class="agregar" data-bs-toggle="modal"  data-bs-target="#modalAtributo" onclick=data()>Nuevo</button></th>
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
        <form id="novedadesform" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="fecha" class="col-form-label">Fecha:</label>
            <input type="date" class="form-control" id="create_at" name="create_at">
          </div>
          <div class="mb-3">
          <label for="nombre" class="col-form-label">Titulo:</label>
            <input type="text" class="form-control" id="titulo" name="titulo">
          </div>
            
          <div class="mb-3">
            <label for="direccion" class="col-form-label">Presentacion:</label>
            <textarea class="form-control" id="short_description" name="short_description" style="height:250px"></textarea>
          </div>
          <div style="width: 130px;height: 34px;text-align-last: center;background: blue;color: white;position: relative;padding-top: 5px;margin-bottom: 6px;" onclick=link(1)>Insertar Link</div>
            <div style="display:flex;">
            <input type="text" class="form-control" id="link" name="link" placeholder="inserte link">
            <input type="text" class="form-control" id="mascara" name="mascara" placeholder="inserte mascara del link">
          </div>
          <br>
          <div class="mb-3">
            <label for="direccion" class="col-form-label">Nota Completa:</label>
            <textarea class="form-control" id="long_description" name="long_description" style="height:250px"></textarea>
          </div>
          <div style="width: 130px;height: 34px;text-align-last: center;background: blue;color: white;position: relative;padding-top: 5px;margin-bottom: 6px;" onclick=link(2)>Insertar Link</div>
            <div style="display:flex;">
            <input type="text" class="form-control" id="link2" name="link2" placeholder="inserte link">
            <input type="text" class="form-control" id="mascara2" name="mascara2" placeholder="inserte mascara del link">
          </div>
          <div class="mb-3">
            <label for="direccion" class="col-form-label">Cargar Imagen:</label>
            <br>
            <label for="Tipo" class="col-form-label">Resolucion:</label>
            <select class="form-select" name="size" id="size">
                <option selected>--SELECCIONE--</option>
                <option value="0">1600x900</option>
                <option value="1">800x800</option>
                <option value="2">Original</option>
            </select>
            <label for="Tipo" class="col-form-label">Tipo:</label>
            <select class="form-select" name="tipo" id="tipo">
                <option selected>--SELECCIONE--</option>
                <option value="0">SLIDER</option>
                <option value="1">INDIVIDUAL</option>
            </select>
            <br>
            <input type="file" class="form-control" id="imagen" name="imagen">
          </div>
          <div class="mb-3">
            <button type="button" id="button-ver-mas1" onclick="ver_mas(1)">Ver mas</button>
            <div id="ver-mas1" class="display">
              <input type="file" class="form-control" id="imagen1" name="imagen1">
            </div>
          </div>
          <div class="mb-3">
            <button type="button" id="button-ver-mas2" onclick="ver_mas(2)">Ver mas</button>
            <div id="ver-mas2" class="display">
              <input type="file" class="form-control" id="imagen2" name="imagen2">
            </div>
          </div>
          <div class="mb-3">
            <button type="button" id="button-ver-mas3" onclick="ver_mas(3)">Ver mas</button>
              <div id="ver-mas3" class="display">
                <input type="file" class="form-control" id="imagen3" name="imagen3">
              </div>
          </div>

          
          <div class="mb-3">
            <label for="video" class="col-form-label">Cargar Video:</label>
            <input type="file" class="form-control" id="video" name="video">
          </div>
          <div class="mb-3">
            <button type="button" id="button-ver-mas4" onclick="ver_mas(4)">Ver mas</button>
            <div id="ver-mas4" class="display">
              <input type="file" class="form-control" id="video1" name="video1">
            </div>
          </div>
          <div class="mb-3">
            <button type="button" id="button-ver-mas5" onclick="ver_mas(5)">Ver mas</button>
            <div id="ver-mas5" class="display">
              <input type="file" class="form-control" id="video2" name="video2">
            </div>
          </div>
          <div class="mb-3">
            <button type="button" id="button-ver-mas6" onclick="ver_mas(6)">Ver mas</button>
              <div id="ver-mas6" class="display">
                <input type="file" class="form-control" id="video3" name="video3">
              </div>
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
const formAtributo = document.getElementById('novedadesform')
const agregarNovedades = document.getElementById('agregarNovedades')
// ************************************************************************************

// ELEMENTOS DEL FORM *****************************************************************
const id = document.getElementById('id')
const create_at = document.getElementById('create_at')
const imagen = document.getElementById('imagen')
const titulo = document.getElementById('titulo')
const short_description = document.getElementById('short_description')
const long_description = document.getElementById('long_description')
const tipo = document.getElementById('tipo')
const size = document.getElementById('size')
const form = document.getElementById("novedadesform")
// *************************************************************************************

// TABLA *******************************************************************************
const contenedor = document.querySelector('tbody')
let resultados  = ''
// *************************************************************************************

// COMPONENTE MODAL BOX DE BOOTSTRAP ***************************************************
const modalAtributo = new bootstrap.Modal(document.getElementById('modalAtributo'))
// *************************************************************************************
//CONSTANTES API ************************************************************************
const url_prod = 'https://hospitalsiriolibanes.ar/comunicacion/api/novedades/';
// const url_prod="http://localhost/admin/api/novedades/";
// **************************************************************************************


// API GET   ****************************************************************************
axios.get(url_prod)
  .then(function (response) { 
    response.data.forEach( atributo=> {
      resultados+=`
                    <tr>
                      <td>${atributo.create_at}</td>
                      <td><img src="../images/${atributo.imagen}" /></td>
                      <td>${atributo.titulo}</td>
                      <td><button id="borrarNovedades" class="borrar" onclick='borrar(${atributo.id})' >Borrar</button><button id="modificarNovedades" class="modificar" onclick='data(${atributo.id})'>Modificar</button></td>
                  </tr>` 
      })
      contenedor.innerHTML = resultados})
  .catch(function (error) {console.log(error);})
  .then(function () { });

function data(id){
    if(!id){
      titulo.value  = ''
      create_at.value  = ''
    short_description.value  = ''
    long_description.value  = ''
    imagen.value  = ''
    }else{
      axios.get(url_prod + id + "/")
    .then(function (response) {
      let long_description = response.data[0].long_description.replace(/<br\s*[\/]?>/gi, "\n");
      let short_description = response.data[0].short_description.replace(/<br\s*[\/]?>/gi, "\n");

      modalAtributo.show()
      document.getElementById('id').value = response.data[0].id
      document.getElementById('create_at').value = response.data[0].create_at
      // document.getElementById('imagen').value = response.data[0].imagen
      document.getElementById('titulo').value = response.data[0].titulo
      document.getElementById('short_description').value = short_description
      document.getElementById('long_description').value = long_description
      document.getElementById('tipo').value = response.data[0].tipo
    })
    .catch(function (error) {console.log(error);})
    .then(function () { });
    }
}

function borrar(id_delete){
  alertify.confirm("BORRAR","¿Desea borrar esta noticia?.",
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


formAtributo.addEventListener('submit', (e) => {
  e.preventDefault()
  const formData = new FormData(formAtributo)
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
      var imagefile1 = document.querySelector('#imagen1');
      var imagefile2 = document.querySelector('#imagen2');
      var imagefile3 = document.querySelector('#imagen3');
      formData.append("imagen", imagefile.files[0]);
      formData.append("imagen1", imagefile1.files[0]);
      formData.append("imagen2", imagefile2.files[0]);
      formData.append("imagen3", imagefile3.files[0]);

      // carga los videos
      var video = document.querySelector('#video');
      var video1 = document.querySelector('#video1');
      var video2 = document.querySelector('#video2');
      var video3 = document.querySelector('#video3');
      formData.append("video", video.files[0]);
      formData.append("video1", video1.files[0]);
      formData.append("video2", video2.files[0]);
      formData.append("video3", video3.files[0]);
        axios.post(url_prod, formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
        })
        .then(function (response) {location.reload()})
        .catch(function (error) {console.log(error);});
  }
  })

  function link(value){
    if(value == 1){
      document.getElementById('short_description').value 
      texto = document.getElementById('short_description').value
      document.getElementById('short_description').value  = texto + '<a href="'+ document.getElementById('link').value+ '">' + document.getElementById('mascara').value +'</a>'
    }
    if(value == 2){
      document.getElementById('long_description').value 
      texto = document.getElementById('long_description').value
      document.getElementById('long_description').value  = texto + '<a href="'+ document.getElementById('link2').value+ '">' + document.getElementById('mascara2').value +'</a>'

    }
  }

  function ver_mas(value){
    switch(value){
      case 1:
        document.getElementById('button-ver-mas1').classList.add('display')
        document.getElementById('ver-mas1').classList.remove('display')
        break;
      case 2:
        document.getElementById('button-ver-mas2').classList.add('display')
        document.getElementById('ver-mas2').classList.remove('display')
        break;
      case 3:
        document.getElementById('button-ver-mas3').classList.add('display')
        document.getElementById('ver-mas3').classList.remove('display')
        break;
      case 4:
        document.getElementById('button-ver-mas4').classList.add('display')
        document.getElementById('ver-mas4').classList.remove('display')
        break;
      case 5:
        document.getElementById('button-ver-mas5').classList.add('display')
        document.getElementById('ver-mas5').classList.remove('display')
        break;
      case 6:
        document.getElementById('button-ver-mas6').classList.add('display')
        document.getElementById('ver-mas6').classList.remove('display')
        break;
    }

  }

</script>
</body>
</html>