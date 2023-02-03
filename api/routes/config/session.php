<?php
//archivo para no reescribir codigo!
//cada vez que el sitio tenga una nueva seccion, incluir este archivo!
session_start();
  if(!isset($_SESSION["token"])) header("Location:index.php");
  if(!isset($_COOKIE["token"])) header("Location: index.php");
  if($_SESSION["token"] !=  $_COOKIE["token"]) header("Location: index.php");
