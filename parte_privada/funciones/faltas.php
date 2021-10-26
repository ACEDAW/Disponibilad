<?php

function encabezado()//funcion de encabezado solo he cambiado unos enlaces dentro de la etiqueta <nav>
{
    
    echo "<!DOCTYPE html>
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
            <a href=ver_faltas.php>Ver Faltas</a>
            <a href=./subida_sql/registrar.php>Registrar Empleado</a>
            <a href=./eliminar_sql/eliminar_empleado.php>Reactivar/Eliminar Empleado<img src=../../img/propias_web/triste.png alt=iconito width=20 height=20></a>
            <a href=../index.php>Salir</a>  
            </nav>
</header>";
    
}


function pie()//la misma estructura del pie
{
    echo "<footer>
<h1> © Creado por Andrés Cortés Alumno 080 Daw</h1>
</footer>
</body>
</html>";
}
if (isset($_POST['presente']) && isset($_POST['addfalta']) || isset($_POST['presente']) && isset($_POST['dropfalta']) ||  isset($_POST['presente']) && isset($_POST['editar_usuario']) ||
isset($_POST['enviar_presente']) && isset($_POST['faltas']) || empty($_POST['presente']) && empty($_POST['faltas']))
{

 echo " 
 
<h2>Errores</h2>
    <p>
    1 -> No puedes seleccionar añadir falta , quitar falta o editar usuario sin previamente no haber seleccionado a ningún empleado, solamente puedes seleccionar a uno.
    <br>
    <br>
    2 -> No puede seleccionar enviar presentes sin haber seleccionado previamente algún empleado sin seleccionar en el campo presente a uno o varios empleados.
    <br>
    <br>
    3 -> No puedes seleccionar el campo presentes e ir a darle a añadir falta , quitar falta o editar usuario este campo solo pertenece al botón enviar presentes.
    <br>
    <br>
    4 -> No puedes seleccionar el campo seleccionar y darle a enviar presentes solo funciona con los botones añadir falta, quitar falta o editar usuario.
    <br>
    <br>
    5 -> No puedes seleccionar ambos campos (presentes y seleccionar ) y darle a alguno de los botones mencionados anteriormente.
    <br>
    <br>
    <a href=../index.php>retroceder</a>
    </p>
     ";
     
}else
{


//si en el index.php de la parte privada han seleccionado quitar falta entraran en este if 
if (isset($_POST['dropfalta'])) {
    
    function cuerpo()
    {
        
        /*recogemos la variable del post en $faltas_empleado que es la id del empleado seleccionado*/
        $faltas_empleado = $_POST['faltas'];
        require_once("../../conf/conf.php");//conexion base de datos
        echo "<form method='POST' action='eliminar_sql/insertar-quitar_faltas.php'  enctype='application/x-www--urlencoded'>";//formulario 
        echo '<input type="hidden" name="id" value=' . $faltas_empleado . '>'; // ocultamos este input que guarda la variable para pasarla a la siguiente pagina para la base de datos, para hacer una consulta 
        
        
        $sentencia_empleados = $bd->prepare("SELECT `id_empleados`,`imagen`,`alias`,`nombre`,`apellido1`,`apellido2`,`puntos` FROM `empleados` where `id_empleados` = $faltas_empleado ;");//seleccionamos todos los datos de id empleados
        $sentencia_empleados->bind_result($id, $imagen, $alias, $nombre, $apellido1, $apellido2, $puntos);//bindeamos las variables
        $sentencia_empleados->execute();
        $sentencia_empleados->fetch();
        $sentencia_empleados->close();
        echo '<input type="hidden" name="puntos_actuales" value=' . $puntos . '>'; //necesitamos saber los puntos y asi nos quitamos una conexion a la base de datos que haremos los calculos necesarios para los puntos
        
        
        
        //consulta para que muestre las faltas del usuario y las faltas que no esten borradas 
        $sentencia = $bd->prepare("SELECT `id_falta`,`fecha` FROM `faltas` where `id_empleados` = $faltas_empleado and `eliminada`=0 ;");
        //ejecutar la consulta
        $sentencia->bind_result($id_falta, $fecha);
        $sentencia->execute();
        
        echo "
    
    <body>
        <h2>Justificar falta</h2>
                    <div class=cabeza_tabla>empleados</div>
                    <table class=tabla_alumnos>
                        <tr>
                            <th>Imagen</th>
                            <th>Alias</th>
                            <th>Nombre</th>
                            <th>1º Apellido</th>
                            <th>2º Apellido</th>
                            <th>Días</th>
                            <th>Acción</th>
                        </tr>";
        
        echo "<tr>
                        <td><img class=imgpersona src=../../$imagen width='150' height='150'></td>
                        <td>$alias</td>
                        <td>$nombre</td>
                        <td>$apellido1</td>
                        <td>$apellido2</td>
                        <td><select name=fecha>";
        while ($sentencia->fetch()) {
            echo "<option value=$id_falta>$fecha</option>";
        }
        echo "</select></td>
                       <td><button type=submit name=enviar_eliminar>Enviar</button></td>
                        </tr>
                    </table>
                    </div>
            </form>";
        $bd->close();
    }
    //mostramos los resultados en una tabla para que lo vea el administrador la imagen,el alias, nombre, apellidos y las fechas que contiene falta con un select option
    
    
    
    echo encabezado();
    
    echo cuerpo();
    
    echo pie();
   
}


//si el administrador ha querido añadir una falta entrara en este if
if (isset($_POST['addfalta'])) {
    
    function cuerpo()
    {
        
        //recogemos la id seleccionada del empleado
        $faltas_empleado = $_POST['faltas'];
        require_once("../../conf/conf.php");//conexion a la base de datos
        echo "<form method='POST' action='eliminar_sql/insertar-quitar_faltas.php'  enctype='application/x-www--urlencoded'>";
        //en la siguiente pagina insertar-quitar_faltas.php llevara el formulario
        echo '<input type="hidden" name="id" value=' . $faltas_empleado . '>'; // ocultamos este input que guarda la variable para pasarla a la siguiente pagina para las siguientes consultas y nos ahorramos una conexion
        //hacemos un switch para cuando sea cada dia de la semana sacamos las horas que hay laborales
        $dia =date('l');
        // ocultamos este input que guarda la variable para pasarla a la siguiente pagina para las sigu>        $dia=utf8_encode($dia);//usamos el encode utf-8 para no tener problemas con las tildes
        //hacemos un switch para cuando sea cada dia de la semana sacamos las horas que hay laborales
        switch ($dia) {
            case 'Monday':
                $dia='Lunes';
                $horas = 6;
                break;
            case 'Tuesday':
                $dia='Martes';
                $horas = 5;
                break;
            case 'Wednesday':
                $dia='Miércoles';
                $horas = 6;
                break;
            case 'Thursday':
                $dia='Jueves';
                $horas = 6;
                break;
            case 'Friday':
                $dia='Viernes';
                $horas = 4;
                break;
            default:
                $dia   = 'no-laborable';
                $horas = 0;
                break;
        }
        
        echo '<input type="hidden" name="dia" value=' . $dia . '>'; //necesitamos saber el dia, para las siguientes consultas, por ello lo ocultamos por que necesitamos la variable
        echo '<input type="hidden" name="hora" value=' . $horas . '>'; //necesitamos saber las horas, por ello lo ocultamos por que necesitamos la variable
        //hacemos un array dentro de un for para obtener todas las horas desde 0 que no habria falta hasta el maximo de horas del dia
        for ($i = 0; $i <= $horas; $i++) {
            
            $ar[] = $i;
        }
        //obtenemos los datos de ese empleado seleccionado por el administrador
        $sentencia_empleados = $bd->prepare("SELECT `id_empleados`,`imagen`,`nombre`,`apellido1`,`apellido2`,`puntos` FROM `empleados` where `id_empleados` = $faltas_empleado ;");
        $sentencia_empleados->bind_result($id, $imagen, $nombre, $apellido1, $apellido2, $puntos);
        $sentencia_empleados->execute();
        $sentencia_empleados->fetch();
        $sentencia_empleados->close();
        echo '<input type="hidden" name="puntos_actuales" value=' . $puntos . '>'; //necesitamos saber los puntos del empleado para las siguientes consultas despues de que sea enviado el formulario como los demas inputs hidden
        
        echo "
    <h2>Añadir falta</h2>
                <div class=cabeza_tabla>empleados</div>
                <table class=tabla_alumnos>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>1º Apellido</th>
                        <th>2º Apellido</th>
                        <th>dia</th>
                        <th>Ausente[horas]</th>
                        <th>¿Llego tarde?</th>
                        <th>Acción</th>
                    </tr>";
        
        echo "<tr>
                    <td><img class=imgpersona src=../../$imagen width='150' height='150'></td>
                    <td>$nombre</td>
                    <td>$apellido1</td>
                    <td>$apellido2</td>
                    <td>$dia</td>";
        echo "<td><select name=faltas >";
        foreach ($ar as $valor) {
            echo "<option value=$valor>$valor</option>";
        }
        echo "</select></td>
                    <td><select name=retrasos >";
        foreach ($ar as $valor) {
            echo "<option value=$valor>$valor</option>";
        }
        echo "</select></td>
                   <td><button type=submit name=enviar_add>Enviar</button></td>
                    </tr>
                </table>
                </div>
        </form>";
        
        
    $bd->close();
    }
    //los foreach que hemos hecho son para obtener las faltas y los retrasos, ya que pueden haber varios retrasos
    
    echo encabezado();
    
    echo cuerpo();
    
    echo pie();
    
}
//si el administrador ha seleccionado editar usuario entrara en este if

if (isset($_POST['editar_usuario'])) {
    
    function cuerpo()
    {
        
        $faltas_empleado = $_POST['faltas'];//id del empleado seleccionado
        require_once("../../conf/conf.php");//conexion la base de datos
        echo "<form method='POST' action='editar_sql/editar_usuario.php'  enctype='application/x-www--urlencoded'>";
        echo '<input type="hidden" name="id" value=' . $faltas_empleado . '>'; //necesitamos saber el id para la edicion del usuario
        $sentencia_empleados = $bd->prepare("SELECT `imagen`,`nombre`,`alias`,`apellido1`,`apellido2`,`puntos`,`equipo` FROM `empleados` where `id_empleados` = $faltas_empleado ;");
        $sentencia_empleados->bind_result($imagen, $nombre, $alias, $apellido1, $apellido2, $puntos, $equipo);
        $sentencia_empleados->execute();
        $sentencia_empleados->fetch();
        $sentencia_empleados->close();
        
        echo '

        <h2>Editar usuario</h2>
                    <div class=cabeza_tabla>empleados</div>
                    <table class=tabla_alumnos>
                        <tr>
                            <th>imagen</th>
                            <th>alias</th>
                            <th>nombre</th>
                            <th>1º Apellido</th>
                            <th>2º Apellido</th>
                            <th>puntos</th>
                            <th>equipo</th>
                            <th>Acción</th>

                        </tr>
                            
                        
                        <tr>
                        <td><img class="imgpersona" src=../../' . $imagen . ' width="150" height="150"></td>
                        <td>' . $alias . '</td>
                        <td>' . $nombre . '</td>
                        <td>' . $apellido1 . '</td>
                        <td>' . $apellido2 . '</td>
                        <td>' . $puntos . '</td>
                        <td>' . $equipo . '</td>
                        <td></td>
                        </tr>
                        <tr>
                        <td><input type="radio" name="editar" value="imagen" required></td>
                        <td><input type="radio" name="editar" value="alias" required></td>
                        <td><input type="radio" name="editar" value="nombre" required></td>
                        <td><input type="radio" name="editar" value="apellido1" required></td>
                        <td><input type="radio" name="editar" value="apellido2" required></td>
                        <td><input type="radio" name="editar" value="puntos" required></td>
                        <td><input type="radio" name="editar" value="equipo" required></td>
                       <td><button type="submit" name="editar_usuario">Enviar</button></td>
                        </tr>
                    </table>
                    </div>
            </form>';
    $bd->close();
    // cuando el usuario haya elegido alguno de estos input significa que quiere editar ese campo del empleado y al presionar el boton lo llevara al archivo editar_usuario.php
    }
    
    echo encabezado();
    
    echo cuerpo();
    
    echo pie();
    
    }

    if(isset($_POST['enviar_presente']) && $_POST['presente'] )
    {
        //este caso para los alumnos que han estado todo el dia y no han tenido falta esos empleados
        require_once("../../conf/conf.php");//conexion la base de datos
        $id_empleado_puntos=$_POST['presente'];

        //hacemos un switch para cuando sea cada dia de la semana sacamos las horas que hay laborales
        $dia =date('l');
        // ocultamos este input que guarda la variable para pasarla a la siguiente pagina para las sigu>        $dia=utf8_encode($dia);//usamos el encode utf-8 para no tener problemas con las tildes
        //hacemos un switch para cuando sea cada dia de la semana sacamos las horas que hay laborales
        switch ($dia) {
            case 'Monday':
                $dia='Lunes';
                $horas = 6;
                break;
            case 'Tuesday':
                $dia='Martes';
                $horas = 5;
                break;
            case 'Wednesday':
                $dia='Miércoles';
                $horas = 6;
                break;
            case 'Thursday':
                $dia='Jueves';
                $horas = 6;
                break;
            case 'Friday':
                $dia='Viernes';
                $horas = 4;
                break;
            default:
                $dia   = 'no-laborable';
                $horas = 0;
                break;
        }

        foreach($id_empleado_puntos as  $valor)
        {
            list($id_recogido,$puntos_recogido)=explode(".",$valor);
            $id_recogido;
            $puntos=$puntos_recogido+$horas;
            $empleados_presentes = $bd->prepare(" UPDATE `empleados` SET `puntos`=$puntos   where `id_empleados`=$id_recogido;");
            $empleados_presentes->execute();
        }
        $empleados_presentes->close();
        $bd->close();
        header("location:../index.php");//redireccionar al index.php
        
    }
}