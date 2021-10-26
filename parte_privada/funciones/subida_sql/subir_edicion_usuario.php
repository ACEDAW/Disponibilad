<?php
//tras que haya seleccionado el administrador  el campo que quiere editar entrará en esta página
function cuerpo()
{
    
    require_once("../../../conf/conf.php");//conexion bd
    $id_empleado_recogido = $_POST['id'];
    
    
    
    if (isset($_POST['nombre'])) {
        //si ha seleccionado el nombre recogemos la variable y hacemos la consulta y actualizamos al empleado
        $nombre = $_POST['nombre'];
        
        $sentencia_empleados = $bd->prepare("UPDATE `empleados` SET `nombre`='$nombre'   where `id_empleados`=$id_empleado_recogido;");
        $sentencia_empleados->execute();
        $sentencia_empleados->close();
        $bd->close();
        header("location:../../index.php");//redireccion al index.php
        
    }
    if (isset($_POST['alias'])) {// si ha selecciona el alias hariamos lo mismo que en el anterior pero en este caso comprobamos el alias si existe ya en la base de datos le dara un error 
        $alias_recogido = $_POST['alias'];
        $sentencia      = $bd->prepare("SELECT `alias` FROM `empleados` where `alias`='$alias_recogido';");
        //ejecutar la consulta
        $sentencia->execute();
        //vincular columnas con variables
        $sentencia->bind_result($alias);
        $sentencia->fetch();
        $sentencia->close();
        
        if ($alias == $alias_recogido) {// si es igual el alias dara error
            echo 'Error alias repetido. 
           <a href=../../index.php>Volver';
        } else {
            //sino actualiza al empleado y cambia el alias
            $alias               = $_POST['alias'];
            $sentencia_empleados = $bd->prepare("UPDATE `empleados` SET `alias`='$alias'   where `id_empleados`=$id_empleado_recogido;");
            $sentencia_empleados->execute();
            $sentencia_empleados->close();
            $bd->close();
            header("location:../../index.php");
        }
        
    }
    if (isset($_POST['apellido1'])) {//igual que el nombre se actualiza el campo
        $apellido1           = $_POST['apellido1'];
        $sentencia_empleados = $bd->prepare("UPDATE `empleados` SET `apellido1`='$apellido1'   where `id_empleados`=$id_empleado_recogido;");
        $sentencia_empleados->execute();
        $sentencia_empleados->close();
        $bd->close();
        header("location:../../index.php");
        
    }
    if (isset($_POST['apellido2'])) {//igual que el nombre se actualiza el campo
        $apellido2           = $_POST['apellido2'];
        $sentencia_empleados = $bd->prepare("UPDATE `empleados` SET `apellido2`='$apellido2'   where `id_empleados`=$id_empleado_recogido;");
        $sentencia_empleados->execute();
        $sentencia_empleados->close();
        $bd->close();
        header("location:../../index.php");
        
    }
    if (isset($_POST['puntos'])) {//igual que el nombre se actualiza el campo
        $puntos              = $_POST['puntos'];
        $sentencia_empleados = $bd->prepare("UPDATE `empleados` SET `puntos`=$puntos   where `id_empleados`=$id_empleado_recogido;");
        $sentencia_empleados->execute();
        $sentencia_empleados->close();
        $bd->close();
        header("location:../../index.php");
        
    }
    if (isset($_FILES['img'])) {//aqui volvemos hacer lo mismo que en el archivo de subir.php
        $nombre_imagen = $_FILES['img']['name']; //obtiene el nombre
        $tipo_imagen   = $_FILES['img']['type']; //contiene el archivo
        $tamano_imagen = $_FILES['img']['size']; //contiene el tamaño
        //si contiene la extension gif,jpeg,jpg,png pese menos 200kb y no contenga espacios  entrara en el if y se actualizara la imagen
        if (((strpos($tipo_imagen, "gif") || strpos($tipo_imagen, "jpeg") || strpos($tipo_imagen, "jpg") || strpos($tipo_imagen, "png")) && ($tamano_imagen < 2000000)) && (!strpos($nombre_imagen, " "))) {
            
            $sentencia_empleados = $bd->prepare("SELECT `id_empleados`,`nombre`,`apellido1`,`apellido2` FROM `empleados` where `id_empleados` = $id_empleado_recogido ;");
            $sentencia_empleados->bind_result($id, $nombre, $apellido1, $apellido2);
            $sentencia_empleados->execute();
            $sentencia_empleados->fetch();
            $sentencia_empleados->close();
            
            $destino_dir                  = '/img/empleados/';
            $nombre_nuevo_empleado_imagen = $nombre . '-' . $apellido1 . '-' . $apellido2 . $id . '-';
            
            move_uploaded_file($_FILES['img']['tmp_name'], '../../../' . $destino_dir . $nombre_imagen);
            rename('../../../' . $destino_dir . $nombre_imagen, '../../../' . $destino_dir . $nombre_nuevo_empleado_imagen . $nombre_imagen);
            $ruta = '.' . $destino_dir . $nombre_nuevo_empleado_imagen . $nombre_imagen; // para la subida de la ruta sql
            
            $sentencia_empleados = $bd->prepare("UPDATE `empleados` SET `imagen`='$ruta'   where `id_empleados`=$id_empleado_recogido;");
            $sentencia_empleados->execute();
            $sentencia_empleados->close();
            $bd->close();
            header("location:../../index.php");
            
        }else//si no lo cumple le mostrara el error
        {
            echo '<b>Error. La extensión o el tamaño de los archivos no es correcta.<br/>
            - Se permiten archivos .gif, .jpg, .png. ,de 200 kb como máximo y que no contenga espacios usa guiones (-) o guiones bajos (_) .</b>';
        }
        
    }
    if (isset($_POST['equipo'])) {//igual que el nombre se actualiza el campo
        $equipo              = $_POST['equipo'];
        $sentencia_empleados = $bd->prepare("UPDATE `empleados` SET `equipo`='$equipo'   where `id_empleados`=$id_empleado_recogido;");
        $sentencia_empleados->execute();
        $sentencia_empleados->close();
        $bd->close();
        header("location:../../index.php");
        
    }
    
    
}

echo cuerpo();


