<?php
function encabezado()//encabezado de html
{
    
    echo "<!DOCTYPE html>
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
                <a href=../../index.php>Inicio</a>
                <a href=../ver_faltas.php>Ver Faltas</a>
                <a href=../subida_sql/registrar.php>Registrar Empleado</a>
                <a href=../eliminar_sql/eliminar_empleado.php>Reactivar/Eliminar Empleado<img src=../../../img/propias_web/triste.png alt=iconito width=20 height=20></a>
                <a href=../index.php>Salir</a>          
            </nav>
</header>";
    
}


function pie()//pie de html
{
    echo "<footer>
<h1> © Creado por Andrés Cortés Alumno 080 Daw</h1>
</footer>
</body>
</html>";
}


function cuerpo()//cuerpo de html
{
    require_once("../../../conf/conf.php");//conexion de la conexion
    $id_empleado_recogido = $_POST['id'];//recoge la id del empleado seleccionado
    $editar= $_POST['editar']; // para saber que ha elegido el admin, que campo quiere actualizar del empleado seleccionado
    echo "<form method='POST' action='../subida_sql/subir_edicion_usuario.php'  enctype='multipart/form-data'>";//en el enctype tenemos que poner multipart/form-data para poder poner imagenes

    //consulta de los datos de un empleado con la id  recogido anteriormente
    $sentencia_empleados = $bd->prepare("SELECT `imagen`,`nombre`,`alias`,`apellido1`,`apellido2`,`puntos`,`equipo` FROM `empleados` where `id_empleados` = $id_empleado_recogido ;");
    echo '<input type="hidden" name="id" value=' . $id_empleado_recogido . '>'; //volver a enviar la id del empleado recogido para el siguiente archivo subir_edicion_usuario.php
    $sentencia_empleados->bind_result($imagen, $nombre, $alias, $apellido1, $apellido2, $puntos, $equipo);//recogemos las variables del select
    $sentencia_empleados->execute();//ejecutamos 
    $sentencia_empleados->fetch();//lanzamos la consulta para que tengamos las variables con cada campo
    $sentencia_empleados->close();
    /*en los diferentes if que hay mas abajo segun lo que haya seleccionado el campo que quiere actualizara entra en ese if y habra un input para que rellene ese campo*/
    echo '

    <body>
        <h2>Editar usuario, por favor sin espacios usa guiones o guiones bajos</h2>
                    <div class=cabeza_tabla>empleados</div>
                    <table class=tabla_alumnos>
                        <tr>
                            <th>imagen</th>
                            <th>alias</th>
                            <th>nombre</th>
                            <th>1º Apellido</th>
                            <th>2º Apellido</th>
                            <th>puntos</th>
                            <th>Equipo</th>
                            <th>Acción</th>

                        </tr>';
    if ($editar == "imagen") {
        
        echo "<tr>
                            <td><input type=file name=img size=20 required></td>
                            <td>$alias</td>
                            <td>$nombre</td>
                            <td>$apellido1</td>
                            <td>$apellido2</td>
                            <td>$puntos</td>
                            <td>$equipo</td>
                            <td><button type=submit name=enviar>Enviar</button></td>
                            </tr>
                        </table>
                        </div>
                        </form>";
        
        
    }
    if ($editar == "nombre") {
        
        echo '<tr>
                            <td><img class="imgpersona" src=../../../' . $imagen . ' width="150" height="150"></td>
                            <td>' . $alias . '</td>
                            <td><input type=text name=nombre required></td>
                            <td>' . $apellido1 . '</td>
                            <td>' . $apellido2 . '</td>
                            <td>' . $puntos . '</td>
                            <td>' . $equipo . '</td>
                            <td><button type=submit name=enviar>Enviar</button></td>
                            </tr>
                        </table>
                        </div>
                        </div>
                        </div>
                        </form>';
    }
    if ($editar == "alias") {
        echo '<tr>
                            <td><img class="imgpersona" src=../../../' . $imagen . ' width="150" height="150"></td>
                            <td><input type=text name=alias required></td>
                            <td>' . $nombre . '</td>
                            <td>' . $apellido1 . '</td>
                            <td>' . $apellido2 . '</td>
                            <td>' . $puntos . '</td>
                            <td>' . $equipo . '</td>
                            <td><button type=submit name=enviar>Enviar</button></td>
                            </tr>
                        </table>
                        </div>
                        </div>
                        </div>
                        </form>';
        
    }
    if ($editar == "apellido1") {
        echo '<tr>
                            <td><img class="imgpersona" src=../../../' . $imagen . ' width="150" height="150"></td>
                            <td>' . $alias . '</td>
                            <td>' . $nombre . '</td>
                            <td><input type=text name=apellido1 required></td>
                            <td>' . $apellido2 . '</td>
                            <td>' . $puntos . '</td>
                            <td>' . $equipo . '</td>
                            <td><button type=submit name=enviar>Enviar</button></td>
                            </tr>
                        </table>
                        </div>
                        </div>
                        </div>
                        </form>';
        
    }
    if ($editar == "apellido2") {
        
        echo '<tr>
                            <td><img class="imgpersona" src=../../../' . $imagen . ' width="150" height="150"></td>
                            <td>' . $alias . '</td>
                            <td>' . $nombre . '</td>
                            <td>' . $apellido1 . '</td>
                            <td><input type=text name=apellido2 required></td>
                            <td>' . $puntos . '</td>
                            <td>' . $equipo . '</td>
                            <td><button type=submit name=enviar>Enviar</button></td>
                            </tr>
                        </table>
                        </div>
                        </div>
                        </div>
                        </form>';
    }
    if ($editar == "puntos") {
        echo '<tr>
                            <td><img class="imgpersona" src=../../../' . $imagen . ' width="150" height="150"></td>
                            <td>' . $alias . '</td>
                            <td>' . $nombre . '</td>
                            <td>' . $apellido1 . '</td>
                            <td>' . $apellido2 . '</td>
                            <td><input type=text name=puntos required></td>
                            <td>' . $equipo . '</td>
                            <td><button type=submit name=enviar>Enviar</button></td>
                            </tr>
                        </table>
                        </div>
                        </div>
                        </div>
                        </form>';
        
    }
    if ($editar == "equipo") {
        echo '<tr>
                            <td><img class="imgpersona" src=../../../' . $imagen . ' width="150" height="150"></td>
                            <td>' . $alias . '</td>
                            <td>' . $nombre . '</td>
                            <td>' . $apellido1 . '</td>
                            <td>' . $apellido2 . '</td>
                            <td>' . $puntos . '</td>
                            <td><input type=text name=equipo required></td>
                            <td><button type=submit name=enviar>Enviar</button></td>
                            </tr>
                        </table>
                        </div>
                        </div>
                        </div>
                        </form>';
        
    }
    
}

echo encabezado();
echo cuerpo();
echo pie();




?>