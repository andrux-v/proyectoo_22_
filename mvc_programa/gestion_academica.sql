-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-02-2026 a las 15:10:03
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestion_academica`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ambiente`
--

CREATE TABLE `ambiente` (
  `amb_id` varchar(5) NOT NULL,
  `amb_nombre` varchar(45) DEFAULT NULL,
  `sede_sede_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ambiente`
--

INSERT INTO `ambiente` (`amb_id`, `amb_nombre`, `sede_sede_id`) VALUES
('A101', 'Biblioteca', 1),
('B102', 'Diseño Grafico', 1),
('C103', 'Taller de Reparación', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion`
--

CREATE TABLE `asignacion` (
  `asig_id` int(11) NOT NULL,
  `instructor_inst_id` int(11) DEFAULT NULL,
  `asig_fecha_ini` datetime DEFAULT NULL,
  `asig_fecha_fin` datetime DEFAULT NULL,
  `ficha_fich_id` int(11) DEFAULT NULL,
  `ambiente_amb_id` varchar(5) DEFAULT NULL,
  `competencia_comp_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignacion`
--

INSERT INTO `asignacion` (`asig_id`, `instructor_inst_id`, `asig_fecha_ini`, `asig_fecha_fin`, `ficha_fich_id`, `ambiente_amb_id`, `competencia_comp_id`) VALUES
(3, 1, '2026-03-02 00:00:00', '2026-03-20 00:00:00', 3115419, 'B102', 2),
(4, 2, '2026-03-02 00:00:00', '2026-03-27 00:00:00', 3142583, 'A101', 3),
(5, 4, '2026-04-06 00:00:00', '2026-05-15 00:00:00', 3145678, 'C103', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria_asignaciones`
--

CREATE TABLE `auditoria_asignaciones` (
  `id_auditoria` int(11) NOT NULL,
  `id_asignacion` int(11) DEFAULT NULL,
  `usuario_que_creo` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `detalles` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `centro_formacion`
--

CREATE TABLE `centro_formacion` (
  `cent_id` int(11) NOT NULL,
  `cent_nombre` varchar(100) NOT NULL,
  `cent_correo` varchar(100) DEFAULT NULL,
  `cent_password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `centro_formacion`
--

INSERT INTO `centro_formacion` (`cent_id`, `cent_nombre`, `cent_correo`, `cent_password`) VALUES
(1, 'Centro de la Industria, la Empresa y los Servicios\r\n', 'centro1@sena.edu.co', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(2, 'Centro de Programacion', 'centro2@sena.edu.co', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(3, 'CIES', 'cies@gmail.com', '$2y$10$.Jxwy9WsnnJyMVD.fYdyhO8ZYOMs3JCOBG366oOFOtz7uA.xNRenW'),
(4, 'CEDRUM', 'cedrum22@gmail.com', '$2y$10$o/X4nvL.7OIXhbgMk2eEa.4SXiBS3cBz9gzMrMRfdVtOywF1XibhG');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `competencia`
--

CREATE TABLE `competencia` (
  `comp_id` int(11) NOT NULL,
  `comp_nombre_corto` varchar(30) DEFAULT NULL,
  `comp_horas` int(11) DEFAULT NULL,
  `comp_nombre_unidad_competencia` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `competencia`
--

INSERT INTO `competencia` (`comp_id`, `comp_nombre_corto`, `comp_horas`, `comp_nombre_unidad_competencia`) VALUES
(2, 'Emprendimiento', 40, 'Emprender bien emprendido'),
(3, 'TIC', 40, 'Tik tok'),
(4, 'Lenguaje Extranjero', 150, 'Intengrar en los aprendices lenguas externas en su vocabulario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `competxprograma`
--

CREATE TABLE `competxprograma` (
  `programa_prog_id` int(11) NOT NULL,
  `competencia_comp_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coordinacion`
--

CREATE TABLE `coordinacion` (
  `coord_id` int(11) NOT NULL,
  `coord_descripcion` varchar(45) DEFAULT NULL,
  `centro_formacion_cent_id` int(11) DEFAULT NULL,
  `coord_nombre_coordinador` varchar(45) DEFAULT NULL,
  `coord_correo` varchar(45) DEFAULT NULL,
  `coord_password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `coordinacion`
--

INSERT INTO `coordinacion` (`coord_id`, `coord_descripcion`, `centro_formacion_cent_id`, `coord_nombre_coordinador`, `coord_correo`, `coord_password`) VALUES
(1, 'Coordinacion Cies', 1, NULL, NULL, NULL),
(5, 'Coordinacion CEDRUM', 4, 'Mario Cepeda', 'marioce@gmail.com', '$2y$10$MgwuhwZNpwq2Kd33BoI/seC9n/txeO37anPTytzGVktSIGf1ZgnSK');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_asignacion`
--

CREATE TABLE `detalle_asignacion` (
  `detasig_id` int(11) NOT NULL,
  `asignacion_asig_id` int(11) DEFAULT NULL,
  `detasig_hora_ini` datetime DEFAULT NULL,
  `detasig_hora_fin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ficha`
--

CREATE TABLE `ficha` (
  `fich_id` int(11) NOT NULL,
  `programa_prog_id` int(11) DEFAULT NULL,
  `instructor_inst_id_lider` int(11) DEFAULT NULL,
  `fich_jornada` varchar(20) DEFAULT NULL,
  `coordinacion_coord_id` int(11) DEFAULT NULL,
  `fich_fecha_ini_lectiva` date DEFAULT NULL,
  `fich_fecha_fin_lectiva` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ficha`
--

INSERT INTO `ficha` (`fich_id`, `programa_prog_id`, `instructor_inst_id_lider`, `fich_jornada`, `coordinacion_coord_id`, `fich_fecha_ini_lectiva`, `fich_fecha_fin_lectiva`) VALUES
(3115419, 23, 1, 'Diurna', 1, NULL, NULL),
(3142583, 3445708, 2, 'Diurna', 1, NULL, NULL),
(3145678, 228106, 4, 'Diurna', 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instructor`
--

CREATE TABLE `instructor` (
  `inst_id` int(11) NOT NULL,
  `inst_nombres` varchar(45) DEFAULT NULL,
  `inst_apellidos` varchar(45) DEFAULT NULL,
  `inst_correo` varchar(45) DEFAULT NULL,
  `inst_telefono` bigint(20) DEFAULT NULL,
  `centro_formacion_cent_id` int(11) DEFAULT NULL,
  `inst_password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `instructor`
--

INSERT INTO `instructor` (`inst_id`, `inst_nombres`, `inst_apellidos`, `inst_correo`, `inst_telefono`, `centro_formacion_cent_id`, `inst_password`) VALUES
(1, 'Mauricio', 'Puentes', 'mauriciop@gmail.com', 3143556784, 1, '$2y$10$azK8dSZfvvFUL.i/01i3H.QP35AeXKjLwofRHz'),
(2, 'Breyner ', 'Peña', 'breynerpena2@gmail.com', 3134565431, 2, '$2y$10$1D2cDQH86t5trR7sKlmZ3.EgJH4xxGnF4Bov2b3BIFJ.p48W.tQ/O'),
(3, 'Omar', 'Roa', 'omaroa@gmail.com', 3124567345, 1, '$2y$10$x4peiQd4xQP1CHGy98kmMerXnrKAzgKxKh3fv7kt/YEbBivu87a/S'),
(4, 'Mario', 'Yepes', 'marioye@gmail.com', 3154352345, 4, '$2y$10$2U4Hz1U9NF/2ZpZer7lHAeeY5DvBRurmryAqO.axSipkurJn2PEWy');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instru_competencia`
--

CREATE TABLE `instru_competencia` (
  `inscomp_id` int(11) NOT NULL,
  `instructor_inst_id` int(11) DEFAULT NULL,
  `competxprograma_programa_prog_id` int(11) DEFAULT NULL,
  `competxprograma_competencia_comp_id` int(11) DEFAULT NULL,
  `inscomp_vigencia` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programa`
--

CREATE TABLE `programa` (
  `prog_codigo` int(11) NOT NULL,
  `prog_denominacion` varchar(100) NOT NULL,
  `tit_programa_titpro_id` int(11) NOT NULL,
  `prog_tipo` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programa`
--

INSERT INTO `programa` (`prog_codigo`, `prog_denominacion`, `tit_programa_titpro_id`, `prog_tipo`) VALUES
(23, 'Cosmetologia', 3, 'Titulada'),
(228106, 'Reparación de Vehiculos Motorizados', 3, 'Titulada'),
(3445708, 'Diseño Grafico', 3, 'Titulada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sede`
--

CREATE TABLE `sede` (
  `sede_id` int(11) NOT NULL,
  `sede_nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sede`
--

INSERT INTO `sede` (`sede_id`, `sede_nombre`) VALUES
(1, 'Centro Industria y Comercio'),
(2, 'Calzado'),
(3, 'Pescadero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titulo_programa`
--

CREATE TABLE `titulo_programa` (
  `titpro_id` int(11) NOT NULL,
  `titpro_nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `titulo_programa`
--

INSERT INTO `titulo_programa` (`titpro_id`, `titpro_nombre`) VALUES
(1, 'Tecnologo'),
(2, 'Tecnico'),
(3, 'Auxiliar');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ambiente`
--
ALTER TABLE `ambiente`
  ADD PRIMARY KEY (`amb_id`),
  ADD KEY `fk_ambiente_sede` (`sede_sede_id`);

--
-- Indices de la tabla `asignacion`
--
ALTER TABLE `asignacion`
  ADD PRIMARY KEY (`asig_id`),
  ADD KEY `fk_asig_instructor` (`instructor_inst_id`),
  ADD KEY `fk_asig_ficha` (`ficha_fich_id`),
  ADD KEY `fk_asig_ambiente` (`ambiente_amb_id`),
  ADD KEY `fk_asig_competencia` (`competencia_comp_id`);

--
-- Indices de la tabla `auditoria_asignaciones`
--
ALTER TABLE `auditoria_asignaciones`
  ADD PRIMARY KEY (`id_auditoria`);

--
-- Indices de la tabla `centro_formacion`
--
ALTER TABLE `centro_formacion`
  ADD PRIMARY KEY (`cent_id`),
  ADD UNIQUE KEY `cent_correo` (`cent_correo`);

--
-- Indices de la tabla `competencia`
--
ALTER TABLE `competencia`
  ADD PRIMARY KEY (`comp_id`);

--
-- Indices de la tabla `competxprograma`
--
ALTER TABLE `competxprograma`
  ADD PRIMARY KEY (`programa_prog_id`,`competencia_comp_id`),
  ADD KEY `fk_cp_competencia` (`competencia_comp_id`);

--
-- Indices de la tabla `coordinacion`
--
ALTER TABLE `coordinacion`
  ADD PRIMARY KEY (`coord_id`),
  ADD KEY `fk_coordinacion_centro` (`centro_formacion_cent_id`);

--
-- Indices de la tabla `detalle_asignacion`
--
ALTER TABLE `detalle_asignacion`
  ADD PRIMARY KEY (`detasig_id`),
  ADD KEY `fk_detalle_asignacion` (`asignacion_asig_id`);

--
-- Indices de la tabla `ficha`
--
ALTER TABLE `ficha`
  ADD PRIMARY KEY (`fich_id`),
  ADD KEY `fk_ficha_programa` (`programa_prog_id`),
  ADD KEY `fk_ficha_instructor` (`instructor_inst_id_lider`),
  ADD KEY `fk_ficha_coordinacion` (`coordinacion_coord_id`);

--
-- Indices de la tabla `instructor`
--
ALTER TABLE `instructor`
  ADD PRIMARY KEY (`inst_id`),
  ADD KEY `fk_instructor_centro` (`centro_formacion_cent_id`);

--
-- Indices de la tabla `instru_competencia`
--
ALTER TABLE `instru_competencia`
  ADD PRIMARY KEY (`inscomp_id`),
  ADD KEY `fk_ic_instructor` (`instructor_inst_id`),
  ADD KEY `fk_ic_competxprograma` (`competxprograma_programa_prog_id`,`competxprograma_competencia_comp_id`);

--
-- Indices de la tabla `programa`
--
ALTER TABLE `programa`
  ADD PRIMARY KEY (`prog_codigo`),
  ADD KEY `fk_programa_titulo` (`tit_programa_titpro_id`);

--
-- Indices de la tabla `sede`
--
ALTER TABLE `sede`
  ADD PRIMARY KEY (`sede_id`);

--
-- Indices de la tabla `titulo_programa`
--
ALTER TABLE `titulo_programa`
  ADD PRIMARY KEY (`titpro_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignacion`
--
ALTER TABLE `asignacion`
  MODIFY `asig_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `auditoria_asignaciones`
--
ALTER TABLE `auditoria_asignaciones`
  MODIFY `id_auditoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `centro_formacion`
--
ALTER TABLE `centro_formacion`
  MODIFY `cent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `competencia`
--
ALTER TABLE `competencia`
  MODIFY `comp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `coordinacion`
--
ALTER TABLE `coordinacion`
  MODIFY `coord_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalle_asignacion`
--
ALTER TABLE `detalle_asignacion`
  MODIFY `detasig_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ficha`
--
ALTER TABLE `ficha`
  MODIFY `fich_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3145679;

--
-- AUTO_INCREMENT de la tabla `instructor`
--
ALTER TABLE `instructor`
  MODIFY `inst_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `instru_competencia`
--
ALTER TABLE `instru_competencia`
  MODIFY `inscomp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `programa`
--
ALTER TABLE `programa`
  MODIFY `prog_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3445709;

--
-- AUTO_INCREMENT de la tabla `sede`
--
ALTER TABLE `sede`
  MODIFY `sede_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `titulo_programa`
--
ALTER TABLE `titulo_programa`
  MODIFY `titpro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ambiente`
--
ALTER TABLE `ambiente`
  ADD CONSTRAINT `fk_ambiente_sede` FOREIGN KEY (`sede_sede_id`) REFERENCES `sede` (`sede_id`);

--
-- Filtros para la tabla `asignacion`
--
ALTER TABLE `asignacion`
  ADD CONSTRAINT `fk_asig_ambiente` FOREIGN KEY (`ambiente_amb_id`) REFERENCES `ambiente` (`amb_id`),
  ADD CONSTRAINT `fk_asig_competencia` FOREIGN KEY (`competencia_comp_id`) REFERENCES `competencia` (`comp_id`),
  ADD CONSTRAINT `fk_asig_ficha` FOREIGN KEY (`ficha_fich_id`) REFERENCES `ficha` (`fich_id`),
  ADD CONSTRAINT `fk_asig_instructor` FOREIGN KEY (`instructor_inst_id`) REFERENCES `instructor` (`inst_id`);

--
-- Filtros para la tabla `competxprograma`
--
ALTER TABLE `competxprograma`
  ADD CONSTRAINT `fk_cp_competencia` FOREIGN KEY (`competencia_comp_id`) REFERENCES `competencia` (`comp_id`),
  ADD CONSTRAINT `fk_cp_programa` FOREIGN KEY (`programa_prog_id`) REFERENCES `programa` (`prog_codigo`);

--
-- Filtros para la tabla `coordinacion`
--
ALTER TABLE `coordinacion`
  ADD CONSTRAINT `fk_coordinacion_centro` FOREIGN KEY (`centro_formacion_cent_id`) REFERENCES `centro_formacion` (`cent_id`);

--
-- Filtros para la tabla `detalle_asignacion`
--
ALTER TABLE `detalle_asignacion`
  ADD CONSTRAINT `fk_detalle_asignacion` FOREIGN KEY (`asignacion_asig_id`) REFERENCES `asignacion` (`asig_id`);

--
-- Filtros para la tabla `ficha`
--
ALTER TABLE `ficha`
  ADD CONSTRAINT `fk_ficha_coordinacion` FOREIGN KEY (`coordinacion_coord_id`) REFERENCES `coordinacion` (`coord_id`),
  ADD CONSTRAINT `fk_ficha_instructor` FOREIGN KEY (`instructor_inst_id_lider`) REFERENCES `instructor` (`inst_id`),
  ADD CONSTRAINT `fk_ficha_programa` FOREIGN KEY (`programa_prog_id`) REFERENCES `programa` (`prog_codigo`);

--
-- Filtros para la tabla `instructor`
--
ALTER TABLE `instructor`
  ADD CONSTRAINT `fk_instructor_centro` FOREIGN KEY (`centro_formacion_cent_id`) REFERENCES `centro_formacion` (`cent_id`);

--
-- Filtros para la tabla `instru_competencia`
--
ALTER TABLE `instru_competencia`
  ADD CONSTRAINT `fk_ic_competxprograma` FOREIGN KEY (`competxprograma_programa_prog_id`,`competxprograma_competencia_comp_id`) REFERENCES `competxprograma` (`programa_prog_id`, `competencia_comp_id`),
  ADD CONSTRAINT `fk_ic_instructor` FOREIGN KEY (`instructor_inst_id`) REFERENCES `instructor` (`inst_id`);

--
-- Filtros para la tabla `programa`
--
ALTER TABLE `programa`
  ADD CONSTRAINT `fk_programa_titulo` FOREIGN KEY (`tit_programa_titpro_id`) REFERENCES `titulo_programa` (`titpro_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
