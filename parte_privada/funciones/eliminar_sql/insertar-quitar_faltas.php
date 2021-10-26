<?php
if (isset($_POST['enviar_add'])) {
    //si ha presionado el administrador añadir falta
    require_once("../../../conf/conf.php");//conexion bd
    $id_empleado_recogido = $_POST['id'];//recoge id del empleado
    $dia_recogido         = $_POST['dia'];//recoge el dia que es
    $puntos_actuales      = intval($_POST['puntos_actuales']);//recoge los puntos actuales que tiene
    $falta                = intval($_POST['faltas']);//recoge las horas faltadas seleccionadas
    $retrasos             = intval($_POST['retrasos']);//recoge las horas que ha llegado tarde
    $horas_hoy            = $_POST['hora'];//el total de horas laborables
    
    $horas_optimas = $horas_hoy - $falta;//para obtener las horas optimas restando el total de horas - las horas faltadas
    //consulta para insertar la nueva falta 
    $fecha_con_date=date('Y-m-d');
    $sentencia = $bd->prepare("INSERT INTO `faltas`( `fecha`,`dia`, `horas`, `retraso`, `id_empleados`,`optimas`,`puntos_actuales`) VALUES (?,?,?,?,?,?,?)");
    $sentencia->bind_param('ssisiii',$fecha, $dia, $horas, $retraso, $id_empleado, $optimas, $puntos_faltas);
    $fecha               =$fecha_con_date;
    $dia                 = $dia_recogido;
    $horas               = $falta;
    $retraso             = $retrasos;
    $id_empleado         = $id_empleado_recogido;
    $optimas             = $horas_optimas;
    //quitar puntos 
    $total_puntos_quitar = $horas_optimas + ($puntos_actuales - (($falta * 10) + ($retrasos * 5)));// para hacer  los puntos totales  que se quedarían al empleado tras la falta
    
    $puntos_faltas = $total_puntos_quitar;//con los puntos totales que se guardarian en la falta para luego mostrarlo en el informe
    $sentencia->execute();
    
    //calculo para restar los puntos
    $sentencia->close();
    
    
    
    
    //en este se actualiza los puntos del empleado
    $insertar_falta = $bd->prepare("UPDATE `empleados` SET `puntos`=$total_puntos_quitar  where `id_empleados` = $id_empleado_recogido;");
    $insertar_falta->execute();
    $insertar_falta->close();
    $bd->close();
    header("location:../../index.php");//redireccionarlo al index.php
    
}



if (isset($_POST['enviar_eliminar'])) {
    //si ha seleccionado quitar faltas del empleado
    require_once("../../../conf/conf.php");//conexion bd
    $id_empleado_recogido  = $_POST['id'];//recoger id del empleado
    $id_fecha_quitar_falta = $_POST['fecha'];//recoger la id de la falta seleccionada
    $puntos_actuales       = $_POST['puntos_actuales'];//recoge los puntos actuales que tiene ahora el empleado
    
    //sacamos las consulta de la id del empleado y la id de la falta 
    $sentencia = $bd->prepare("SELECT `horas`,`retraso` FROM `faltas` where `id_empleados`=$id_empleado_recogido and `id_falta`='$id_fecha_quitar_falta';");
    $sentencia->bind_result($horas, $retraso);//sacamos las horas y retraso que haya obtenido 
    $sentencia->execute();
    $sentencia->fetch();
    $puntos_add = ($puntos_actuales + (($horas * 10) + ($retraso * 5)));//volvemos a hacer los calculos de los puntos quen tendria si no se le hubiese quitado la falta, ya que si la borramos esta justificada
    $sentencia->close();
    
    
    //consulta para actualizar las falta y eliminarlas'justificada'
    $borrar_falta = $bd->prepare(" UPDATE `faltas` SET `eliminada`=1   where `id_empleados`=$id_empleado_recogido and `id_falta`=$id_fecha_quitar_falta;");
    $borrar_falta->execute();
    $borrar_falta->close();
    //consulta para actualizar los puntos del empleado seleccionado al justificar la falta
    $insertar_falta = $bd->prepare("UPDATE `empleados` SET `puntos`=$puntos_add  where `id_empleados` =$id_empleado_recogido;");
    $insertar_falta->execute();
    $insertar_falta->close();
    $bd->close();
    
    
    header("location:../../index.php");//redireccionar al index.php
}