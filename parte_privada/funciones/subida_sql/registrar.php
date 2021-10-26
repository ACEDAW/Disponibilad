<?php
function encabezado()//encabezado de html
{
    //aqui es el registros del usuario por lo que hay input que tiene que rellenar el administrador y se redirige a la pagina subir.php
    //multipart/form-data sirve para subir imagenes en el enctype del formulario
    echo "
<!DOCTYPE html>
<html lang=es>
<head>
    <meta charset=UTF-8>
    <meta name=viewport content=width=device-width, initial-scale=1.0>
    <link rel=icon type=image/ico href=../../../img/propias_web/ico.ico>
    <link rel=stylesheet href=../../../css/style.css>
    <title>Empresa ABC </title>
</head>
<body>
<header>
    
    <a href=../index.php tittle=Inicio><h1 class=logo>Disponibilidad <img src=../../../img/propias_web/icono.png alt=iconito width=50 height=50></h1></a>
            <nav class=navegador>  
                <a  class=login href=../../index.php> Inicio</a>
                <a href=../ver_faltas.php>Ver Faltas</a>
                <a href=../eliminar_sql/eliminar_empleado.php>Reactivar/Eliminar Empleado<img src=../../../img/propias_web/triste.png alt=iconito width=20 height=20></a>
                <a href=../../../index.php>Salir</a>        
            </nav>
</header>";
}

function cuerpo()//cuerpo del html
{
    echo "
            <h1>Registrar nuevo empleado</h1>
            <h2>SIN ESPACIOS EN LOS CAMPOS USA '-' O '_'</h2>
                <div class=cabeza_tabla>empleados</div>
                <table class=tabla_alumnos>
                    <tr>
                        <th>Imagen</th>
                        <th>Alias</th>
                        <th>Nombre</th>
                        <th>1º Apellido</th>
                        <th>2º Apellido</th>
                        <th>Equipo</th>
                        <th>Acción</th>
                    </tr>
                        <form method=post action=subir.php enctype=multipart/form-data>
                         <tr><td><input type=file name=img size=20 required></td>
                         <td><input type=text name=alias required></td>
                         <td><input type=text name=nombre required></td>
                         <td><input type=text name=apellido1 required></td>
                         <td><input type=text name=apellido2 required></td>
                         <td><input type=text name=equipo required></td>
                         <td><button name=enviar type=submit>Enviar</button></td>
                         </tr>
                    
                </table>
                </div>
            </form>";
}

function pie()//pie del html
{
    echo "<footer>
<h1> © Creado por Andrés Cortés Alumno 080 Daw</h1>
</footer>
</body>
</html>";
}


echo encabezado();

echo cuerpo();

echo pie();