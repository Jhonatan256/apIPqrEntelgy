-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 08-11-2024 a las 06:18:23
-- Versión del servidor: 8.2.0
-- Versión de PHP: 8.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pqrentelgy`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

DROP TABLE IF EXISTS `area`;
CREATE TABLE IF NOT EXISTS `area` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `idEncargado` int NOT NULL,
  `ans` int NOT NULL,
  `eliminado` varchar(2) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `area`
--

INSERT INTO `area` (`id`, `nombre`, `idEncargado`, `ans`, `eliminado`) VALUES
(1, 'Proceso de Dirección ', 0, 0, 'N'),
(2, 'Proceso administración del SGC ', 0, 0, 'N'),
(3, 'Proceso Delivery servicios STP\'S DBP', 0, 0, 'N'),
(4, 'Proceso Delivery servicios STP\'S Ciberseguridad', 0, 0, 'N'),
(5, 'Proceso Gestión de proyectos ', 0, 0, 'N'),
(6, 'Proceso implementación de la solución ', 0, 0, 'N'),
(7, 'Proceso servicio de ciberseguridad', 0, 0, 'N'),
(8, 'Proceso Gestión Humana ', 0, 0, 'N'),
(9, 'Proceso Gestión de Infraestructura Tecnológica ', 0, 0, 'N'),
(10, 'Proceso Gestión de Compras ', 0, 0, 'N'),
(11, 'Proceso de preventa DBP', 0, 0, 'N'),
(12, 'Proceso de Preventa Ciberseguridad', 0, 0, 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caso`
--

DROP TABLE IF EXISTS `caso`;
CREATE TABLE IF NOT EXISTS `caso` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idInformador` int NOT NULL,
  `idResponsable` int NOT NULL,
  `tipoSolicitud` int NOT NULL,
  `idArea` int NOT NULL,
  `idPrioridad` int NOT NULL,
  `idGravedad` int NOT NULL,
  `idEstado` int NOT NULL,
  `asunto` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `fechaCreacion` varchar(20) NOT NULL,
  `porcentaje` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `caso`
--

INSERT INTO `caso` (`id`, `idInformador`, `idResponsable`, `tipoSolicitud`, `idArea`, `idPrioridad`, `idGravedad`, `idEstado`, `asunto`, `descripcion`, `fechaCreacion`, `porcentaje`) VALUES
(8, 13, 13, 0, 0, 1, 1, 1, 'PRUEBA DE CASO', 'descripcion', '2024-11-08 04:51:19', '0'),
(9, 13, 13, 0, 0, 1, 1, 1, 'PRUEBA DE CASO', 'descripcion', '2024-11-08 05:36:49', '0'),
(6, 13, 13, 1, 1, 1, 1, 1, 'PRUEBA DE CASO', 'descripcion', '2024-11-08 02:48:55', '0'),
(5, 13, 13, 1, 1, 1, 1, 1, 'PRUEBA DE CASO', 'descripcion', '2024-11-08 02:47:40', '0'),
(10, 13, 13, 0, 0, 1, 1, 1, 'PRUEBA DE CASO', 'descripcion', '2024-11-08 06:14:16', '0'),
(11, 13, 13, 0, 0, 1, 1, 1, 'PRUEBA DE CASO', 'descripcion', '2024-11-08 06:14:43', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

DROP TABLE IF EXISTS `estado`;
CREATE TABLE IF NOT EXISTS `estado` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `eliminado` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id`, `nombre`, `eliminado`) VALUES
(1, 'Pendiente', 'N'),
(2, 'Solucionado', 'N'),
(3, 'Anulado', 'N'),
(4, 'Aplazado', 'N'),
(5, 'Reabrierto', 'N'),
(6, 'Reasignado', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gravedad`
--

DROP TABLE IF EXISTS `gravedad`;
CREATE TABLE IF NOT EXISTS `gravedad` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `eliminado` varchar(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `gravedad`
--

INSERT INTO `gravedad` (`id`, `nombre`, `eliminado`) VALUES
(1, 'Fallo', 'N'),
(2, 'Bloqueo', 'N'),
(3, 'Normal', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial`
--

DROP TABLE IF EXISTS `historial`;
CREATE TABLE IF NOT EXISTS `historial` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idCaso` int NOT NULL,
  `idResponsable` int NOT NULL,
  `idEncargado` int NOT NULL,
  `cambioEstado` int NOT NULL,
  `descripcion` text NOT NULL,
  `accionesRealizadas` text NOT NULL,
  `porcentaje` varchar(20) NOT NULL,
  `fecha` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `historial`
--

INSERT INTO `historial` (`id`, `idCaso`, `idResponsable`, `idEncargado`, `cambioEstado`, `descripcion`, `accionesRealizadas`, `porcentaje`, `fecha`) VALUES
(2, 5, 13, 0, 1, 'Asignación automática del sistema.', 'Asignación', '0', '2024-11-08 02:47:40'),
(3, 6, 13, 13, 1, 'Asignación automática del sistema.', 'Asignación', '0', '2024-11-08 02:48:55'),
(4, 8, 13, 13, 1, 'Asignación automática del sistema.', 'Asignación', '0', '2024-11-08 04:51:19'),
(5, 9, 13, 13, 1, 'Asignación automática del sistema.', 'Asignación', '0', '2024-11-08 05:36:49'),
(6, 10, 13, 13, 1, 'Asignación automática del sistema.', 'Asignación', '0', '2024-11-08 06:14:16'),
(7, 11, 13, 13, 1, 'Asignación automática del sistema.', 'Asignación', '0', '2024-11-08 06:14:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prioridad`
--

DROP TABLE IF EXISTS `prioridad`;
CREATE TABLE IF NOT EXISTS `prioridad` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `eliminado` varchar(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `prioridad`
--

INSERT INTO `prioridad` (`id`, `nombre`, `eliminado`) VALUES
(1, 'Baja', 'N'),
(2, 'Normal', 'N'),
(3, 'Alta', 'N'),
(4, 'Urgente', 'N'),
(5, 'Inmediata', 'N'),
(6, 'Ninguna', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `soporte`
--

DROP TABLE IF EXISTS `soporte`;
CREATE TABLE IF NOT EXISTS `soporte` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idCaso` int DEFAULT NULL,
  `idHistorial` int DEFAULT NULL,
  `nombre` varchar(200) NOT NULL,
  `eliminado` varchar(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocaso`
--

DROP TABLE IF EXISTS `tipocaso`;
CREATE TABLE IF NOT EXISTS `tipocaso` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `eliminado` varchar(2) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tipocaso`
--

INSERT INTO `tipocaso` (`id`, `nombre`, `eliminado`) VALUES
(1, 'Solicitud', 'N'),
(2, 'Queja', 'N'),
(3, 'Reclamo', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipousuario`
--

DROP TABLE IF EXISTS `tipousuario`;
CREATE TABLE IF NOT EXISTS `tipousuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `eliminado` varchar(2) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tipousuario`
--

INSERT INTO `tipousuario` (`id`, `nombre`, `eliminado`) VALUES
(1, 'Administrativo', 'N'),
(2, 'Empleado', 'N'),
(3, 'Administrador', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `identificacion` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `celular` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` text NOT NULL,
  `tipoUsuario` int NOT NULL,
  `cargo` varchar(50) NOT NULL,
  `area` int NOT NULL,
  `genero` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fechaCreacion` varchar(20) NOT NULL,
  `fechaUltimoAcceso` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `eliminado` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'N',
  `fechaEliminacion` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombres`, `apellidos`, `identificacion`, `email`, `celular`, `password`, `tipoUsuario`, `cargo`, `area`, `genero`, `fechaCreacion`, `fechaUltimoAcceso`, `eliminado`, `fechaEliminacion`) VALUES
(13, 'JHONATAN', 'RONCANCIO', '1049654252', 'jhot.256@gmail.com', '3166215124', '$2y$10$7wXFhpEmuHcdmeJElP4N8OtvjU0GFizzkCGRFwvzBbdUJxR7FWdTa', 1, 'DESARROLLADOR', 1, 'M', '2024-11-02 05:58:01', '2024-11-08 06:14:02', 'N', NULL),
(16, 'JESUS', 'SANCHEZ', '1007306773', 'jesus@gmail.com', '3105265656', '$2y$10$m4knxoufP7hVLufqfBV3XOvOtdZ7xORaWVaA1wCNUr4yZLuBNJkcq', 1, 'DESARROLLADOR', 1, 'M', '2024-11-08 06:00:09', NULL, 'N', NULL),
(17, 'DAVID', 'PINZON', '1007306773', 'jesus2@gmail.com', '3105265656', '$2y$10$F0UI0DnIpm/1MTJibEbbceBrWeYGoUzXbu.NCZKw.JENK67h9Y/bG', 1, 'DESARROLLADOR', 1, 'M', '2024-11-08 06:15:01', NULL, 'N', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
