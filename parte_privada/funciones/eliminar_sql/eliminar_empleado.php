<?php
function encabezado()//encabezado html
{
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
    <a href=../../index.php tittle=Inicio><h1 class=logo>Disponibilidad <img src=../../../img/propias_web/icono.png alt=iconito width=50 height=50></h1></a>
            <nav class=navegador>  
            <a  class=login href=../../index.php> Inicio</a>
            <a href=../ver_faltas.php>Ver Faltas</a>
            <a href=../subida_sql/registrar.php>Registrar Empleado</a>
            <a href=../../../index.php>Salir</a>        
            </nav>
</header>";
    
}

function cuerpo()//cuerpo del html 
{
    
    
    
    // cuando haya presionado alguno de los botones entrara entrara en esta if 
    if (isset($_POST['si'])) {
        // le mostrará el buscador para que seleccione la id que quiera 
        echo '
            <form action="" method="post">
               <input class="buscador" type="search" name="poralias" placeholder="Busca por alias...">
                <input type="submit" name="enviar" value="Buscar">
            </form>';
    } elseif (isset($_POST['no'])) {
        //si selecciona que no lo redirreciona a youtube con la ayuda de la funcion de header
        header("location:https://www.youtube.com/watch?v=nOyv4Yhj2_E");
        
        
    } elseif (isset($_POST['enviar'])) {
        //si ha presionado el boton de enviar del buscador entrara aqui
        echo '
            <form action="" method="post">
                <input class="buscador" type="search" name="poralias" placeholder="Busca por alias...">
                <input type="submit" name="enviar" value="Buscar">
            </form>';
        
        if (isset($_POST['poralias'])) {
            //si al haber presionado al boton del buscador entrara aqui
            $alias_recogido = $_POST['poralias']; //recogemos la variable de lo que ha insertado el administrador  
            
            require_once("../../../conf/conf.php");//conectamos la base de datos 
            echo "<form method='post' action='' enctype='application/x-www--urlencoded'>";
            //consulta para que muestre la informacion del empleado donde el alias contenga algo de lo que haya escrito el administrador
            $sentencia = $bd->prepare("SELECT `id_empleados`,`imagen`,`nombre`,`alias`,`apellido1`,`apellido2`,`puntos`,`equipo`,`eliminado` FROM `empleados` where `alias` like '%$alias_recogido%';");
            //ejecutar la consulta
            $sentencia->execute();
            //vincular columnas con variables
            $sentencia->bind_result($id, $imagen, $nombre, $alias, $apellido1, $apellido2, $puntos, $equipo, $eliminado);
            
            echo "
        <body>
            <h2>Todos los empleados</h2>
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
                                <th>Equipo</th>
                                <th>Eliminado</th>
                                <th>Seleccionar</th>
                                <th>Acciones</th>
                            </tr>
                            ";
            while ($sentencia->fetch()) {
                $disponibilidad = $puntos;
                if ($disponibilidad >= 100) {
                    $disponibilidad = "Si,sigue así! <img src=../../../img/propias_web/love.png width=25 height=25>";
                } elseif ($disponibilidad >= 50) {
                    $disponibilidad = "Si,pero puedes mejorar! <img src=../../../img/propias_web/like.png width=25 height=25>";
                } elseif ($disponibilidad < 50) {
                    $disponibilidad = "No,espabila <img src=../../../img/propias_web/dislike.png width=25 height=25>";
                    $equipo         = 'Ninguno';
                }
                if ($eliminado == 0) {
                    $eliminado = 'No';
                } else {
                    $eliminado = 'Si';
                }
                echo '<tr><td><img class="imgpersona" src=../../../' . $imagen . ' width="120" height="120"></td><td>' . $id . '</td><td>' . $alias . '</td><td>' . $nombre . '</td><td>' . $apellido1 . '</td><td>' . $apellido2 . '</td>
                                 <td >' . $disponibilidad . '</td><td>' . $puntos . '</td><td>' . $equipo . '</td><td>' . $eliminado . '</td>
                                 <td><input type="radio" name="id" value=' . $id . ' required></td>
                                 <td><button type="submit" name="eliminar">Eliminar usuario</button><button type="submit" name="activar">Reactivar  usuario</button></td></tr>';
            }
            "
                            </div>
                        </table>
                        
            </form>";
            //$disponibilidad --> segun los puntos que tenga mostrara una informacion u otra, $equipo --> si tiene menos de 50 puntos no tenga ningun equipo, $eliminado --> mostrara si esta eliminado el empleado
            $sentencia->close();
            $bd->close();
        }
    } elseif (isset($_POST['eliminar'])) {
        // si presiona el boton de eliminar empleado 
        
        $id = $_POST['id'];//id recogida del empleado seleccionado
        require_once("../../../conf/conf.php");//ocnexion bd
        //consulta para que atualice el empleado a eliminado
        $sentencia = $bd->prepare("UPDATE `empleados` SET `eliminado`=1 where `id_empleados`=$id;");
        //ejecutar la consulta
        $sentencia->execute();
        $sentencia->close();
        //todas las falta de ese empleado selecciona se eliminan
        $borrar_falta = $bd->prepare(" UPDATE `faltas` SET `eliminada`=1   where `id_empleados`=$id;");
        $borrar_falta->execute();
        //damos las variables que hemos sacado
        $borrar_falta->close();
        $bd->close();
        header("location:../../index.php");// tras al haber hecho las consultas redirige al index 
        
    } elseif (isset($_POST['activar'])) {
        //si presiona el boton de reactivar empleado
        $id = $_POST['id'];//id recogida del empleado seleccionado
        require_once("../../../conf/conf.php");//conexion bd
        //consulta para que muestre las tareas pendientes ordenadas por prioridad en ascendentes
        //acualizar a empleado a no eliminado
        $sentencia = $bd->prepare("UPDATE `empleados` SET `eliminado`=0 where `id_empleados`=$id;");
        //ejecutar la consulta
        $sentencia->execute();
        $sentencia->close();
        //todas las faltas no estan eliminadas
        $borrar_falta = $bd->prepare(" UPDATE `faltas` SET `eliminada`=0   where `id_empleados`=$id;");
        $borrar_falta->execute();
        $borrar_falta->close();
        $bd->close();
        header("location:../../index.php");
    }
    
    else {
        //al entrar en esta página entrará en este apartado 
        echo "
            <form method='post' action='' enctype='application/x-www--urlencoded'>
            <h2>Admin ¿Estas seguro de eliminar a un empleado?</h2>
            <div class='botones'>
            <button type='submit' name='si'>Si, soy una mala persona</button>
            <button type='submit' name='no'>Me lo he pensado mejor, hay que pensarlo en frío</button>
            <button type='submit' name='si'>Solo, quiero reactivar a un usuario</button>
            </div>
            <img src=../../../img/propias_web/trovald.png alt=iconito class='trovald'>            
            </form>";
    }
    //depende de lo que haya seleccionado entrará en los if que hay mas arriba
}

function pie()//pie de html
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