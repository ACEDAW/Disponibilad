CREATE table `faltas` 
(
    `id_falta` int AUTO_INCREMENT,
    `fecha` date default now(),
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
