<?php


function encabezado()// encabezado igual que el index anterior solo cambian los enlaces que hay dentro de la etiqueta <nav>
{
    echo "
<!DOCTYPE html>
<html lang=es>
<head>
    <meta charset=UTF-8>
    <meta name=viewport content=width=device-width, initial-scale=1.0>
    <link rel=icon type=image/ico href=../img/propias_web/ico.ico>
    <link rel=stylesheet href=../css/style.css>
    <title>Empresa ABC </title>
</head>
<body>
<header>
    
    <a href=$_SERVER[PHP_SELF] tittle=Inicio><h1 class=logo>Disponibilidad <img src=../img/propias_web/icono.png alt=iconito width=50 height=50></h1></a>
            <nav class=navegador>  
                <a href=./funciones/ver_faltas.php>Ver Faltas</a>
                <a href=./funciones/subida_sql/registrar.php>Registrar Empleado</a>
                <a href=./funciones/eliminar_sql/eliminar_empleado.php>Reactivar/Eliminar Empleado<img src=../img/propias_web/triste.png alt=iconito width=20 height=20></a>
                <a href=../index.php>Salir</a>          
            </nav>
 
</header>";
    
}

function cuerpo()
{
    require_once("../conf/conf.php");//para conectar la base datos

    /*aqui he implementado un buscador, para que el administrador busque por alias a los empleados, por metodo post
    luego si haya escrito o no y le de al boton de buscar , con nombre enviar, si presiona, hace una consulta donde el alias contenga lo que ha insertado el usuario ,
    por cada empleado habra tres botones si queremos añadir falta, quitar falta o editar usuario, dentro de este habrá otro formulario,
    que lo redirreciona a otra página*/
    echo '
<form action="" method="post">
     <input class="buscador" type="search" name="poralias" placeholder="Busca por alias...">
    <input type="submit" name="enviar" value="Buscar">
</form>';
    
    if (isset($_POST['enviar'])) {
        if (isset($_POST['poralias'])) {
            $alias_recogido = $_POST['poralias'];
            
            
            echo "<form method='post' action='funciones/faltas.php' enctype='application/x-www--urlencoded'>
            <br>
            <button name=enviar_presente>Enviar presentes</button>";
            
            //consulta para que muestre los datos de los emmpleados que no estan eliminados y que contengan lo que haya escrito el administrador
            $sentencia = $bd->prepare("SELECT `id_empleados`,`imagen`,`nombre`,`alias`,`apellido1`,`apellido2`,`puntos`,`equipo` FROM `empleados` where `alias` like '%$alias_recogido%' and `eliminado`= 0;");
            //ejecutar la consulta
            $sentencia->execute();
            //vincular columnas con variables
            $sentencia->bind_result($id, $imagen, $nombre, $alias, $apellido1, $apellido2, $puntos, $equipo);
            echo '<br>';
            echo '<br>';
            
            echo "
                <div class=cabeza_tabla>empleados</div>
                <table class=tabla_alumnos>
                    <tr>
                        <th>Imagen</th>
                        <th>ID</th>
                        <th>Alias</th>
                        <th>Nombre</th>
                        <th>1º Apellido</th>
                        <th>2º Apellido</th>
                        <th>Disponible</th>
                        <th>Puntos</th>
                        <th>Sueldo</th>
                        <th>Equipo</th>
                        <th>Presentes</th>
                        <th>Seleccionar</th>
                        <th>Acciones</th>
                    </tr>
                    ";
            while ($sentencia->fetch()) {
                $disponibilidad = $puntos;
                if ($disponibilidad >= 100) {
                    $disponibilidad = "Si,sigue así! <img src=../img/propias_web/love.png width=25 height=25>";
                    $sueldo ="66,67%+";
                } elseif ($disponibilidad >= 50) {
                    $disponibilidad = "Si,pero puedes mejorar! <img src=../img/propias_web/like.png width=25 height=25>";
                    $sueldo ="66,67%+";
                } elseif ($disponibilidad < 50) {
                    $disponibilidad = "No,espabila <img src=../img/propias_web/dislike.png width=25 height=25>";
                    $equipo         = "Ninguno";
                    $sueldo ="0%+";
                }
                echo '<tr><td><img class="imgpersona" src=../' . $imagen . ' width="120" height="120"></td><td>' . $id . '</td><td>' . $alias . '</td><td>' . $nombre . '</td><td>' . $apellido1 . '</td><td>' . $apellido2 . '</td>
                         <td >' . $disponibilidad . '</td><td>' . $puntos . '</td><td>'.$sueldo.'</td><td>' . $equipo . '</td>
                         <td><input type="checkbox" name="presente[]" value=' . $id.'.'.$puntos . ' ></td>
                         <td><input type="radio" name="faltas" value=' . $id . ' ></td>
                         <td><button type="submit" name="addfalta">Añadir falta</button><button type="submit" name="dropfalta">Quitar falta</button><button type="submit" name="editar_usuario">Editar usuario</button></td></tr>';
            }
            "
                </table>
            </div>  
    </form>";
    $bd->close();
        }
    }
}
//el checkbox es para los alumnos que estan presentes y no han tenido falta
//al mostrar los datos en la tabla hago una comprobacion de los datos y si tiene 100 puntos o mas mostrara una imagen si tiene 50 o mas mostrara una imagen y si tiene menos 50 mostrara otra,
//luego el equipo  si tiene menos de 50 puntos le dira que esta en ninguno


function pie()//lo mismo que el anterior index.php
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