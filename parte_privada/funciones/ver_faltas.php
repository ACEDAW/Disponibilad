<?php
function encabezado()//encabezado html etiqueta <nav> se han cambiado enlaces
{
    echo "
<!DOCTYPE html>
<html lang=es>
<head>
    <meta charset=UTF-8>
    <meta name=viewport content=width=device-width, initial-scale=1.0>
    <link rel=icon type=image/ico href=../../img/propias_web/ico.ico>
    <link rel=stylesheet href=../../css/style.css>
    <title>Empresa ABC </title>
</head>
<body>
<header>
    <a href=../index.php tittle=Inicio><h1 class=logo>Disponibilidad <img src=../../img/propias_web/icono.png alt=iconito width=50 height=50></h1></a>
            <nav class=navegador>  
            <a href=../index.php>Inicio</a>
            <a href=./subida_sql/registrar.php>Registrar Empleado</a>
            <a href=./eliminar_sql/eliminar_empleado.php>Reactivar/Eliminar Empleado<img src=../../img/propias_web/triste.png alt=iconito width=20 height=20></a>
            <a href=../index.php>Salir</a>  
            </nav>
</header>";
}

function cuerpo()//cuerpo html
{
    require_once("../../conf/conf.php");//conexion base de datos
    //buscador igual que en el index.php de la parte privada
    echo '
    <form action="" method="post" enctype="application/x-www--urlencoded">
        <input type="search" name="poralias" class="buscador" class="buscador"  placeholder="Busca por alias...">
        <input type="submit" name="enviar"  value="Buscar">

    <br><br>
    <button type="submit" name="informe">Ver informe sobre los puntos de los empleados de una fecha en concreto</button>
    </form>';
    
    
    echo '<br>';
    echo '<br>';
    
    if (isset($_POST['enviar'])) {
        if (isset($_POST['poralias'])) {
            $alias_recogido = $_POST['poralias'];
            
            
            //consulta para que muestre los empleados con lo que contenga lo que ha escrito el usuario en el alias de los empleados
            echo "<form method='post' action='' enctype='application/x-www--urlencoded'>";
            $sentencia = $bd->prepare("SELECT `id_empleados`,`imagen`,`nombre`,`alias`,`apellido1`,`apellido2`,`puntos`,`eliminado`,`equipo` FROM `empleados` where `alias` like '%$alias_recogido%' ;");
            $sentencia->bind_result($id, $imagen, $nombre, $alias, $apellido1, $apellido2, $puntos, $eliminado, $equipo);
            //ejecutar la consulta
            $sentencia->execute();
            //vincular columnas con variables
            
            
            echo "
    <h2>Estas viendo todos los alumnos con sus faltas y fecha</h2>
                <div class=cabeza_tabla>empleados</div>
                <table class=tabla_alumnos>
                    <tr>
                        <th>Imagen</th>
                        <th>ID</th>
                        <th>Alias</th>
                        <th>Nombre</th>
                        <th>1º Apellido</th>
                        <th>2º Apellido</th>
                        <th>Puntos</th>
                        <th>Disponible</th>
                        <th>Equipo</th>
                        <th>¿eliminado?</th>
                        <th>Seleccionar</th>
                        <th>Acciones</th>
                    </tr>
                    ";
            while ($sentencia->fetch()) {
                $disponibilidad = $puntos;
                if ($disponibilidad >= 100) {
                    $disponibilidad = "Si,sigue así! <img src=../../img/propias_web/love.png width=25 height=25>";
                } elseif ($disponibilidad >= 50) {
                    $disponibilidad = "Si,pero puedes mejorar! <img src=../../img/propias_web/like.png width=25 height=25>";
                } elseif ($disponibilidad < 50) {
                    $disponibilidad = "No,espabila <img src=../../img/propias_web/dislike.png width=25 height=25>";
                    $equipo         = "Ninguno";
                }
                if ($eliminado == 0) {
                    $eliminado = 'No';
                } else {
                    $eliminado = 'Si';
                }
                echo '<tr><td><img class="imgpersona" src=../../' . $imagen . ' width="120" height="120"</td><td>' . $id . '</td><td>' . $alias . '</td><td>' . $nombre . '</td>
                         <td>' . $apellido1 . '</td><td>' . $apellido2 . '</td><td>' . $puntos . '</td>
                         <td>' . $disponibilidad . '</td><td>' . $equipo . '</td><td>' . $eliminado . '</td><td><input type="radio" name="id" value=' . $id . ' required></td><td><button type="submit" name="falta_fecha">Ver falta de una fecha</button><br><button type="submit" name="ver_falta">Ver todas las faltas</button></td>' . '</tr>';
            }//nuevo if que se ve, es para ver si el empleado esta eliminado si es 0 en la base de datos significa que no, si esta en 1 significa que si
            "
                </table>
                </div>
        </form>";
            $sentencia->close();
            $bd->close();
            
        }
        
    }
    //si el administrador ha pulsado que quiere ver todas las faltas del empleado  entrara en este if
    if (isset($_POST['ver_falta'])) {
        //obtenemos la id de ese empleado seleccionado
        $id= $_POST['id'];
        //consulta de los empleados, donde un empleado tenga esa id 
        $sentencia = $bd->prepare("SELECT empleados.id_empleados,`imagen`,`nombre`,`alias`,`apellido1`,`apellido2`,`puntos`,`eliminado`,`equipo`,`puntos` from `empleados` where id_empleados = $id;");
        $sentencia->bind_result($id, $imagen, $nombre, $alias, $apellido1, $apellido2, $puntos, $eliminado, $equipo, $puntos);
        //ejecutar la consulta
        $sentencia->execute();
        $sentencia->fetch();
        
        if ($eliminado == 0) {
            $eliminado = 'No';
        } else {
            $eliminado = 'Si';
        }
        if ($puntos < 50) { // aqui la variable pertenece significa para ver si sigue perteneciendo a ese equipo por ello si tiene menos de 50 puntos no pertene, si es mayor de 50 puntos si pertenece
            $pertenece = 'No';
            $sueldo="0%+";
        } else {
            $pertenece = 'Si';
            $sueldo="66,67%+";
        }
        echo '
        <div class="informe">
        <h1>Total de faltas</h1>
        <h2>Datos personales</h2>
        <img src=../../' . $imagen . '>
        <br>
        <br>
        <ul>
        <li>Nombre completo:  <strong>' . $nombre . ' ' . $apellido1 . ' ' . $apellido2 . '</strong></li>
        <li>Alias: <strong>' . $alias . '</strong></li>
        <li>Eliminado: <strong>' . $eliminado . '</strong></li>
        </ul>
        <h3>Equipo : ' . $equipo . '</h3>
        <h3>Sueldo : ' . $sueldo . '</h3>
        <h3>¿Sigue perteneciendo al equipo? : ' . $pertenece . '</h3>
        <h3>Puntos : ' . $puntos . '</h3>
        <br><br>
        </div>';
        $sentencia->close();
        
        // en esta consulta hacemos un inner join paras obtener las faltas de ese empleado
        $sentencia_faltas = $bd->prepare("SELECT `fecha`,`dia`,`horas`,`retraso`,`optimas`,`eliminada`,`puntos`,`puntos_actuales` from `empleados` INNER JOIN `faltas` on empleados.id_empleados=faltas.id_empleados where empleados.id_empleados = $id ;");
        $sentencia_faltas->bind_result($fecha, $dia, $horas, $retraso, $optimas, $eliminada, $puntos, $puntos_falta);
        //ejecutar la consulta
        $sentencia_faltas->execute();
        echo '<div class="informe">
         <h2 text-align="center">Faltas</h2>';
        while ($sentencia_faltas->fetch()) {
            
            if ($eliminada == 0) { //aqui comprobamos si la falta esta justificada o no si esta en 0 significa que no esta justificada si esta en 1 significa que si
                $eliminada = 'no';
            } else {
                $eliminada = 'si';
            }
            
            
            echo '
            <ul>
            <li>Puntos: <strong>' . $puntos_falta . '</strong></li>
            <li>Dia: <strong>' . $dia . '</strong></li>
            <li>Fecha: <strong>' . $fecha . '</strong></li>
            <li>Horas faltadas: <strong>' . $horas . '</strong></li>
            <li>Retraso de ese día: <strong>' . $retraso . '</strong></li>
            <li>Horas optimas de ese día: <strong>' . $optimas . '</strong></li>
            <li>¿Esta justificada?: <strong>' . $eliminada . '</strong></li>
            </ul>
            <hr>';
        }
        echo '</div>';
        
        $sentencia_faltas->close();
        $bd->close();
        
        
    }
    //si el administrador ha seleccionado que quiere ver una fecha en concreto de ese usuario seleccionado entrara en este if
    if (isset($_POST['falta_fecha'])) {
        echo "<form method='post' action='' enctype='application/x-www--urlencoded'>";
        $id = $_POST['id'];
        echo '<input type="hidden" name="id" value=' . $id . '>';//ocultamos este input para que nos envie la id al otro archivo de php y no crear otra consulta 
        //consulta para obtener las faltas de un empleado 
        $sentencia = $bd->prepare("SELECT `id_falta`,`fecha` FROM `faltas` where `id_empleados` = $id;");
        //ejecutar la consulta
        $sentencia->bind_result($id_falta, $fecha);//obtenemos las variables
        $sentencia->execute();
        
        echo "
    
    <body>
        <h2>Selecciona la falta que quieres ver</h2>
                    <div class=cabeza_tabla>empleados</div>
                    <table class=tabla_alumnos>
                        <tr>
                            <th>Fecha</th>
                            <th>Acción</th>
                        </tr>";
        
        echo "<tr>
                        <td><select name=fecha>";
        while ($sentencia->fetch()) {  // aqui hacemos un select option para que el administrador eliga la fecha que quiere
            echo "<option value=$id_falta>$fecha</option>";
        }
        echo "</select></td>
                       <td><button type=submit name=ver_falta_fecha>Enviar</button></td>
                        </tr>
                    </table>
                    </div>
            </form>";
        $sentencia->close();
        $bd->close();
    }//entrara en este if si el administrador pulsa el boton ver informe sobre los puntos de los empleados en una fecha en concreto
    if (isset($_POST['informe'])) {
        
        echo "<form method='post' action='' enctype='application/x-www--urlencoded'>";
        //consulta para que muestre las faltas que hay las agrupamos para que no se repitan
        $sentencia = $bd->prepare("SELECT MAX(`id_falta`),`fecha` FROM `faltas` group by `fecha`;");
        //ejecutar la consulta
        $sentencia->bind_result($id_falta, $fecha);
        $sentencia->execute();
        
        echo "
    
    <body>
        <h2>Selecciona la falta que quieres ver</h2>
                    <div class=cabeza_tabla>empleados</div>
                    <table class=tabla_alumnos>
                        <tr>
                            <th>Fecha</th>
                            <th>Acción</th>
                        </tr>";
        
        echo "<tr>
                        <td><select name=fecha_informe>";
        while ($sentencia->fetch()) {//obtenemos las distintas fechas para que el admin eliga la fecha concreta para ver el informe
            
            echo "<option value=$fecha>$fecha</option>";
        }
        echo "</select></td>
                       <td><button type=submit name=ver_informe>Enviar</button></td>
                        </tr>
                    </table>
                    </div>
            </form>";
            $sentencia->close();
            $bd->close();
        
        
        
    }
    // cuando haya seleccionado la fecha en concreto del informe para ver las faltas de los empleados entrara en este if
    if (isset($_POST['ver_informe'])) {

        $fecha= $_POST['fecha_informe'];//obtenemos la fecha seleccionada del admin
        //hacemos una consulta de todos los campos de empleados y faltas, un inner join para ello y donde las faltas sean igual a la fecha seleccionada
        $sentencia = $bd->prepare("SELECT empleados.id_empleados,`imagen`,`nombre`,`alias`,`apellido1`,`apellido2`,`puntos`,`eliminado`,`fecha`,`dia`,`horas`,`retraso`,`optimas`,`eliminada`,`equipo`,`puntos_actuales` from `empleados` INNER JOIN `faltas` on empleados.id_empleados=faltas.id_empleados where  `fecha`='$fecha';");
        $sentencia->bind_result($id, $imagen, $nombre, $alias, $apellido1, $apellido2, $puntos, $eliminado, $fecha, $dia, $horas, $retraso, $optimas, $falta_eliminada, $equipo, $puntos_falta);
        //ejecutar la consulta
        $sentencia->execute();
        while ($sentencia->fetch()) {
            if ($eliminado == 0) {//para comprobar si el usuario esta eliminado
                $eliminado = 'No';
            } else {
                $eliminado = 'Si';
            }
            
            if ($falta_eliminada == 0) {//comprobar si la falta es justifica o no
                $falta_eliminada = 'No';
            } else {
                $falta_eliminada = 'Si';
            }
            if ($puntos < 50) { 
                $pertenece = 'No';  // comprobar si el empleado pertenece al equipo o no
                $sueldo="0%+";
            } else {
                $pertenece = 'Si';
                $sueldo="66,67%+";
            }
            
            echo '
        <div class="informe">
        <h1>Total de faltas</h1>
        <h2>Datos personales</h2>
        <img src=../../' . $imagen . '>
        <br>
        <br>
        <ul>
        <li>Nombre completo:  <strong>' . $nombre . ' ' . $apellido1 . ' ' . $apellido2 . '</strong></li>
        <li>Alias: <strong>' . $alias . '</strong></li>
        <li>Eliminado: <strong>' . $eliminado . '</strong></li>
        </ul>
        <h3>Equipo : ' . $equipo . '</h3>
        <h3>Sueldo : ' . $sueldo . '</h3>
        <h3>¿Sigue perteneciendo al equipo? : ' . $pertenece . '</h3>
        <h3>Puntos Actuales : ' . $puntos . '</h3>

        <br><br><br><br><br>
        <h2 text-align="center">Faltas</h2>
        <hr>
        <ul>
        <li>Puntos:<strong>' . $puntos_falta . '</strong></li>
        <li>Dia:<strong>' . $dia . '</strong></li>
        <li>Fecha:<strong>' . $fecha . '</strong></li>
        <li>Horas faltadas:<strong>' . $horas . '</strong></li>
        <li>Retraso de ese día:<strong>' . $retraso . '</strong></li>
        <li>Horas optimas de ese día:<strong>' . $optimas . '</strong></li>
        <li>¿Esta justificada?:<strong>' . $falta_eliminada . '</strong></li>
        </ul>
        <hr>
        </div>';
        }
        
        $sentencia->close();
        $bd->close();
        
    }
    //cuando el admin haya seleccionado al empleado y la fecha en concreto entrara en este if
    if (isset($_POST['ver_falta_fecha'])) {
        $id = $_POST['id'];//id obtenida al seleccionar al empleado
        $id_falta  = intval($_POST['fecha']);//obtenemos la id de la falta seleccionada
        //en este consulta hacemos un inner join de los empleados y de las faltas  donde sea igual al id del usuario selecciona y la id falta seleccionada
        $sentencia = $bd->prepare("SELECT empleados.id_empleados,`imagen`,`nombre`,`alias`,`apellido1`,`apellido2`,`puntos`,`eliminado`,`fecha`,`dia`,`horas`,`retraso`,`optimas`,`eliminada`,`equipo`,`puntos_actuales` from `empleados` INNER JOIN `faltas` on empleados.id_empleados=faltas.id_empleados where empleados.id_empleados = $id and `id_falta`=$id_falta;");
        $sentencia->bind_result($id, $imagen, $nombre, $alias, $apellido1, $apellido2, $puntos, $eliminado, $fecha, $dia, $horas, $retraso, $optimas, $falta_eliminada, $equipo, $puntos_falta);
        //ejecutar la consulta
        $sentencia->execute();
        $sentencia->fetch();
        
        if ($eliminado == 0) {// para obtener si el empleado esta eliminado o no
            $eliminado = 'No';
        } else {
            $eliminado = 'Si';
        }
        
        if ($falta_eliminada == 0) {//comprobar si la falta esta justificada o no
            $falta_eliminada = 'No';
        } else {
            $falta_eliminada = 'Si';
        }
        if ($puntos < 50) {// comprobar si pertenece al equipo o no
            $pertenece = 'No';
            $sueldo="0%+";
        } else {
            $sueldo="66,67%+";
            $pertenece = 'Si';
        }
        
        
        echo '
        <div class="informe">
        <h1>Total de faltas</h1>
        <h2>Datos personales</h2>
        <img src=../../' . $imagen . '>
        <br>
        <br>
        <ul>
        <li>Nombre completo:  <strong>' . $nombre . ' ' . $apellido1 . ' ' . $apellido2 . '</strong></li>
        <li>Alias: <strong>' . $alias . '</strong></li>
        <li>Eliminado: <strong>' . $eliminado . '</strong></li>
        </ul>
        <h3>Equipo : ' . $equipo . '</h3>
        <h3>Sueldo : ' . $sueldo . '</h3>
        <h3>¿Sigue perteneciendo al equipo? : ' . $pertenece . '</h3>
        <h3>Puntos : ' . $puntos . '</h3>
        <br><br><br><br><br>
        <h2 text-align="center">Faltas</h2>
        <hr>
        <ul>
        <li>Puntos:<strong>' . $puntos_falta . '</strong></li>
        <li>Dia:<strong>' . $dia . '</strong></li>
        <li>Fecha:<strong>' . $fecha . '</strong></li>
        <li>Horas faltadas:<strong>' . $horas . '</strong></li>
        <li>Retraso de ese día:<strong>' . $retraso . '</strong></li>
        <li>Horas optimas de ese día:<strong>' . $optimas . '</strong></li>
        <li>¿Esta justificada?:<strong>' . $falta_eliminada . '</strong></li>
        </ul>
        <hr>
        </div>';
        
        $sentencia->close();
        $bd->close();
    
    }
 
}

function pie()//pie html
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