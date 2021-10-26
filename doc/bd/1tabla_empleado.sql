CREATE TABLE `empleados`
(
    `id_empleados`int AUTO_INCREMENT,
    `imagen` longtext,
    `nombre` varchar(20) not null,
    `alias` varchar(20) not null,
    `apellido1` varchar(20) not null,
    `apellido2` varchar (20) not null,
    `equipo` varchar(200),
    `puntos` int default 100,
    `eliminado` int(1) default 0,
    PRIMARY KEY (`id_empleados`)
);
