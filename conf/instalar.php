<?php


echo '<form method="post" action="" enctype="application/x-www--urlencoded">';
echo "<fieldset>";
echo "Rellena los datos para instalar la aplicación en la base de datos, suponiendo que usas el puerto predeterminado de sql<br>";
echo '<label>Servidor: <input placeholder="Inserte servidor de tu bd" name="servidor" ></label><br>';
echo "<br>";
echo '<label>Usuario: <input placeholder="usuario base de datos" name="usuario" ></label><br>';
echo "<br>";
echo "<p> si no hay contraseña no lo rellenes <br>";
echo '<label>contraseña: <input placeholder="contraeña de tu usuario" name="contrasena" ></label><br>';
echo "</fieldset>";
echo "<br>";
echo "<button type='submit' name='enviar'>Enviar</button>"; //boton de pasar a la siguiente lista
echo "</form>";


if(isset($_POST['enviar']))
{

    $servidor = $_POST['servidor']; //le damos a la variable nuestra ruta donde esta la base de datos
    $user = $_POST['usuario']; // guardamos en la variable nuestro usuario
    $password = $_POST['contrasena']; // guardamos en la variable nuestra contraseña en este caso no tiene
    $database = "abc"; // aqui el nombre de la base de datos


    //conexiones que vamos a necesitar con nuestra base de datos, aqui ponemos las variables  anteriores
    $bd = new mysqli($servidor, $user, $password);
  

    if ($bd->connect_error)
    {
        die("la conexion con la bd ha fallado. Error: " . $bd->connect_errno . ":" . $bd->connect_error);
    }else
    {
        echo "funsiona </br>";
    }

    $sentencia = $bd->prepare("DROP DATABASE IF EXISTS `abc`;");
    $sentencia->execute();
    $sentencia->close();
  
    $sentencia1=$bd->prepare("CREATE DATABASE `abc`;");
    $sentencia1->execute();
    $sentencia1->close();
    $bd1 = new mysqli($servidor, $user, $password, $database);
    $sentencia2=$bd1->prepare("CREATE TABLE `empleados`
    (
        `id_empleados` int AUTO_INCREMENT,
        `imagen` longtext,
        `nombre` varchar(20) not null,
        `alias` varchar(20) not null,
        `apellido1` varchar(20) not null,
        `apellido2` varchar (20) not null,
        `equipo` varchar(200),
        `puntos` int default 100,
        `eliminado` int(1) default 0,
        PRIMARY KEY (`id_empleados`)
    );");
    $sentencia2->execute();
    $sentencia2->close();


    $sentencia3=$bd1->prepare("INSERT INTO `empleados` (`id_empleados`, `imagen`, `nombre`, `alias`, `apellido1`, `apellido2`, `equipo`, `puntos`, `eliminado`) VALUES
    (1, './img/empleados/Andres-Gonzalez-Escubido1-1.jpg', 'Andres', 'Hackersito', 'Gonzalez', 'Escubido', 'rojofuego', 46, 0),
    (2, './img/empleados/Manuel-loquito-loco2-2.jpg', 'Manuel', 'Manolon100', 'loquito', 'loco', 'rojo', 19, 0),
    (3, './img/empleados/mario-Cortes-Escobedo3-3.jpg', 'mario', 'Moises100', 'Cortes', 'Escobedo', 'rojo', 116, 0),
    (4, './img/empleados/prueba-prueba-prueba4-4.jpg', 'prueba', 'pruebesita', 'prueba', 'prueba', 'rojofuego', -12, 0),
    (5, './img/empleados/pepon-pepito-cobe5-5.jpg', 'pepon', 'pepe', 'pepito', 'cobe', 'cd', 91, 0);");
    $sentencia3->execute();
    $sentencia3->close();

    $sentencia4=$bd1->prepare("CREATE table `faltas` 
    (
        `id_falta` int AUTO_INCREMENT,
        `fecha` date,
        `dia` varchar(15),
        `horas`int default 0,
        `retraso` int,
        `optimas` int default 0,
        `puntos_actuales` int default 0,
        `id_empleados` int,
        `eliminada` int default 0,
        PRIMARY KEY (`id_falta`),
        FOREIGN KEY (`id_empleados`) REFERENCES `empleados`(`id_empleados`)
    );
    
    ");
    $sentencia4->execute();
    $sentencia4->close();

    $sentencia5=$bd1->prepare("INSERT INTO `faltas` (`id_falta`, `fecha`, `dia`, `horas`, `retraso`, `optimas`, `puntos_actuales`, `id_empleados`, `eliminada`) VALUES
    (1, '2021-02-01', 'lunes', 2, 1, 4, 79, 1, 0),
    (2, '2021-02-01', 'lunes', 4, 4, 2, 42, 2, 0),
    (3, '2021-02-01', 'lunes', 0, 0, 6, 106, 3, 0),
    (4, '2021-02-01', 'lunes', 6, 6, 0, 10, 4, 0),
    (5, '2021-02-01', 'lunes', 1, 3, 5, 80, 5, 0),
    (6, '2021-02-04', 'jueves', 3, 2, 2, 41, 1, 0),
    (7, '2021-02-04', 'jueves', 3, 0, 2, 14, 2, 0),
    (8, '2021-02-04', 'jueves', 0, 0, 5, 111, 3, 0),
    (9, '2021-02-04', 'jueves', 0, 0, 5, 15, 4, 0),
    (10, '2021-02-04', 'jueves', 0, 0, 5, 85, 5, 0),
    (11, '2021-02-03', 'miércoles', 0, 0, 5, 46, 1, 0),
    (12, '2021-02-03', 'miércoles', 0, 0, 5, 19, 2, 0),
    (13, '2021-02-03', 'miércoles', 0, 0, 5, 116, 3, 0),
    (14, '2021-02-03', 'miércoles', 3, 0, 3, -12, 4, 0),
    (15, '2021-02-03', 'miércoles', 0, 0, 6, 91, 5, 0);
    ");
    $sentencia5->execute();
    $sentencia5->close();


$bd->close();
$bd1->close();
echo "se ha instalado la base de datos -
Puedes cerrar la ventana";
}

