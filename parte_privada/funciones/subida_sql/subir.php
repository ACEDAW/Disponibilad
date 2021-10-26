<?php

//recoger por post la informacion del nuevo empleado
$nombre_nuevo_empleado    = $_POST['nombre']; // nombre del nuevo empleado
$alias_nuevo_empleado     = $_POST['alias']; //seleccionamos el alias del nuevo empleado registrado 
$apellido1_nuevo_empleado = $_POST['apellido1']; //1º apellido del nuevo empleado
$apellido2_nuevo_empleado = $_POST['apellido2']; //2º apellido del nuevo empleado
$equipo_nuevo_empleado    = $_POST['equipo']; //nombre en el equipo que esta
$nombre_imagen            = $_FILES['img']['name']; //obtiene el nombre
$tipo_imagen              = $_FILES['img']['type']; //contiene el archivo
$tamano_imagen            = $_FILES['img']['size']; //contiene el tamaño




require_once("../../../conf/conf.php");//conexion bd
//seleccionamos  si en la base de datos existe un alias igual al alias del nuevo empleado
$sentencia = $bd->prepare("SELECT `alias` FROM `empleados` where `alias`='$alias_nuevo_empleado';");
//ejecutar la consulta
$sentencia->execute();
$sentencia->bind_result($alias);
$sentencia->fetch();
//si son iguales dará un error de que no se puede registrar por que el alias es el mismo
if ($alias == $alias_nuevo_empleado) {
    echo 'Error alias repetido. 
   <a href=registrar.php>Volver';
} else {
    //HACEMOS UN IF  PARA COMPROBAR QUE SI NO ES UN GIF,JPEG,JPG  O PNG , QUE PESE COMO MAXIMO 200KB COMO MAXIMO  y que no haya espacios en el nombre
    if (((strpos($tipo_imagen, "gif") || strpos($tipo_imagen, "jpeg") || strpos($tipo_imagen, "jpg") || strpos($tipo_imagen, "png")) && ($tamano_imagen < 2000000)) && (!strpos($nombre_imagen, " "))) {
        
        //NOS CONECTAMOS A LA BASE DE DATOS 
        require_once("../../../conf/conf.php");
        $sentencia_conocerid = $bd->prepare("SELECT `id_empleados` FROM `empleados` ORDER BY `id_empleados` DESC;");
        //vincular columnas con variables
        $sentencia_conocerid->bind_result($id_empleados);
        $sentencia_conocerid->execute();
        $sentencia_conocerid->fetch();

        //si no tenemos nigun empleado registrado y es el primero nos dara el valor de null para la id por ello 
        //hacemos un if si es null le dara igual a 1  sino se le sumara mas 1 al resultado de la consulta
        if ($id_empleados == NULL) {
            $id_empleados = 1;
        } else {
            $id_empleados++;
        }
        
        
        $sentencia_conocerid->close();
    
        
        
        
        
        
        //RUTA DE LA CARPETA DEL SERVIDOR DONDE SE VAN A GUARDAR LAS IMAGENES
        $destino_dir= '/img/empleados/';//donde se guarda la imagen
        $nombre_nuevo_empleado_imagen = $nombre_nuevo_empleado . '-' . $apellido1_nuevo_empleado . '-' . $apellido2_nuevo_empleado . $id_empleados . '-';
        //sera el nuevo nombre que le daremos a la imagen
        
        move_uploaded_file($_FILES['img']['tmp_name'], '../../../' . $destino_dir . $nombre_imagen);
        //ponemos la direccion de la imagen que tiene ya subida y lo guardamos
    
        rename('../../../' . $destino_dir . $nombre_imagen, '../../../' . $destino_dir . $nombre_nuevo_empleado_imagen . $nombre_imagen);
        //luego le cambiamos el nombre a la imagen
        //PARTE PARA SUBIDA A SQL PARA GUARDAR LA RUTA DE LA IMAGEN CON EL NOMBRE DE LA IMAGEN
        $ruta = '.' . $destino_dir . $nombre_nuevo_empleado_imagen . $nombre_imagen; // para la subida de la ruta sql
        
        
        //consulta para hacer la insercion del nuevo empleado
        $sentencia = $bd->prepare("INSERT INTO `empleados`( `imagen`, `alias` ,`nombre`, `apellido1`, `apellido2`,`equipo`) VALUES (?,?,?,?,?,?);");
        //vincular columnas con variables
        $sentencia->bind_param('ssssss', $imagen, $alias, $nombre, $apellido1, $apellido2, $equipo);
        //damos las variables que hemos sacado
        $imagen= $ruta;
        $nombre= $nombre_nuevo_empleado;
        $alias     = $alias_nuevo_empleado;
        $apellido1 = $apellido1_nuevo_empleado;
        $apellido2 = $apellido2_nuevo_empleado;
        $equipo    = $equipo_nuevo_empleado;
        //ejecutamos las variables dadas para insertalas
        $sentencia->execute();
        $sentencia->close();
        //cerramos consulta
        
        
        header("location:../../index.php");//redireccionamos al index.php
    } else {
        //si no cumple con la imagen le dara el error para que sepa como tiene que subir la imagen
        echo '<b>Error. La extensión o el tamaño de los archivos no es correcta.<br/>
   - Se permiten archivos .gif, .jpg, .png. ,de 200 kb como máximo y que no contenga espacios usa guiones (-) o guiones bajos (_) .</b>';
    }
    
}