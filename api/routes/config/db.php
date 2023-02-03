<?php
//** DATOS DE CONEXION A LA BASE DE DATOS */
class db{
  //DATOS LOCAL
  // private $dbHost = "localhost";
  // private $dbUser = "root";
  // private $dbPass = "";
  // private $dbName = "eventoshsl";

  // DB PROD

  private $dbHost = "181.31.167.201";
  private $dbUser = "comunicacion";
  private $dbPass = "Comunicacion_HSL";
  private $dbName = "eventos_hsl";
  // private $dbHost = "190.12.111.66";
  // port=14999;

  //** MIENTRAS LOS DATOS SEAN CORRECTOS, SE REALIZA LA CONEXION, DE LO CONTRARIO DEVUELVE UN ERROR */
  public function conecctionDB(){
    $mysqlConnect = "mysql:host=$this->dbHost;port=14999;dbname=$this->dbName;charset=UTF8";
    $dbConnecion = new PDO($mysqlConnect, $this->dbUser, $this->dbPass);
    $dbConnecion ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbConnecion;
    
  }
  
  
}
