<?php
$servidor = "localhost"; //le damos a la variable nuestra ruta donde esta la base de datos
$user = "root"; // guardamos en la variable nuestro usuario
$password = null; // guardamos en la variable nuestra contraseña en este caso no tiene
$database = "abc"; // aqui el nombre de la base de datos


//conexiones que vamos a necesitar con nuestra base de datos, aqui ponemos las variables  anteriores
$bd = new mysqli($servidor, $user, $password, $database);
//comprobar la conexion
if ($bd->connect_error)
{
    die("la conexion con la bd ha fallado. Error: " . $bd->connect_errno . ":" . $bd->connect_error);
}
