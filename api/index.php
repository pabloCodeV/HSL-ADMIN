<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: text/html;charset=utf-8");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}


//ARCHIVOS REQUERIDOS PARA EL CORRECTO FUNCIONAMIENTO
require '../vendor/autoload.php';
require 'routes/config/db.php';
require 'routes/config/common.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

$config = ['settings' => ['displayErrorDetails' => true]]; 
$app = new Slim\App($config);

//RUTAS DE LAS APIS A UTILIZAR
require 'routes/novedades.php';
require 'routes/covertura.php';
require 'routes/telemedicina.php';
require 'routes/servicios-medicos.php';
require 'routes/especialidadesExterno.php';
require 'routes/eventos.php';
require 'routes/pre_intervencion.php';
require 'routes/inscripciones.php';
require 'routes/banner_section.php';
require 'routes/correo.php';
require 'routes/sliders.php';
require 'routes/autoridades.php';
require 'routes/usuarios.php';


$app->run();