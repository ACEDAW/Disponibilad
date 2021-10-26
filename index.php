<?php
function encabezado()//función del encabezado  que sera el encabezado de html.
{
    echo "
<!DOCTYPE html>
<html lang=es>
<head>
    <meta charset=UTF-8>
    <meta name=viewport content=width=device-width, initial-scale=1.0>
    <link rel=icon type=image/ico href=img/propias_web/ico.ico>
    <link rel=stylesheet href=css/style.css>
    <title>Empresa ABC </title>
</head>
<body>
<header>
    <a href=$_SERVER[PHP_SELF] tittle=Inicio> <h1 class=logo>Disponibilidad <img src=img/propias_web/icono.png alt=iconito width=50 height=50></h1></a>
            <nav class=navegador>  
                <a  class=login href=.\parte_privada\index.php> Login</a>
            </nav>
</header>";
}

//$_SERVER[PHP_SELF] lo uso con la etiqueta <a>  para cuando el usuario haga clic ahi recarga la página 

function cuerpo()
{
    //añadirlo a conf
    require_once("conf/conf.php");//lo usamos para hacer la conexion a la base de datos  y recoger la variable $bd
    // que es la que hace la conexión para luego ejecutar las consultas

    //consulta para que muestre la imagen, alias y el equipo de los empleados que no estan eliminados
    $sentencia = $bd->prepare("SELECT `imagen`,`alias`,`puntos`,`equipo` FROM `empleados` where `eliminado` =0;");
    //selecciona los campos imagen, alias, puntos y equipo de empleados que no esten eliminados
    
    //ejecutar la consulta
    $sentencia->execute();

    //vincular columnas con variables
    $sentencia->bind_result($imagen, $alias, $puntos, $equipo);
    
    echo "
                <div class=cabeza_tabla>Empleados</div>
                <table class=tabla_alumnos>
                    <tr>
                        <th>Imagen</th>
                        <th>Alias</th>
                        <th>Puntos</th>
                        <th>Disponibilidad</th>
                        <th>Sueldo</th>
                        <th>Equipo</th>
                    </tr>
                    ";
    while ($sentencia->fetch()) {
        $disponibilidad = $puntos;
        if ($disponibilidad >= 100) {
            $disponibilidad = "Si,sigue así! <img src=./img/propias_web/love.png width=25 height=25>";
            $sueldo ="66,67%+";
        } elseif ($disponibilidad >= 50) {
            $disponibilidad = "Si,pero puedes mejorar! <img src=./img/propias_web/like.png width=25 height=25>";
            $sueldo ="66,67%+";
        } elseif ($disponibilidad < 50) {
            $disponibilidad = "No,espabila <img src=./img/propias_web/dislike.png width=25 height=25>";
            $equipo         = "Ninguno";
            $sueldo ="0%+";
            
        }
        echo '<tr>' . '<td><img class="imgpersona" src=' . $imagen . ' width="150" height="150"></td><td>' . $alias . '</td>
                         <td>' . $puntos . '</td><td>' . $disponibilidad . '</td><td>'.$sueldo.'</td><td>' . $equipo . '</td></tr>';
    }
    "
                </table>
            </div>";
            $bd->close();
}
// funcion del cuerpo  es mostrar una tabla a los empleados sobre sus puntos , es el footer de html
//$disponibilidad  que son los puntos del empleado si son mayores que 100 le dira al usuario  un mensaje , si es menos que 100 
//pero  mayor o igual que 50 le dira otro mensaje y si es menos que 50 le dara otro mensaje, y $equipo que nos dice el equipo que pertenece 
//cada empleado, si es menos que 50 puntos no pertecenera a ninguno.
/* $imagen = nos da la ruta donde esta la imagen, $puntos = puntos totales de cada empleado, $alias = alias de cada empleado*/ 


function pie()
{
    echo "<footer>
<h1> © Creado por Andrés Cortés Alumno 080 Daw</h1>
</footer>
</body>
</html>";
}

//funcion pie, va a ser siempre la misma que sera el pie de html

echo encabezado();

echo cuerpo();

echo pie();