-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-12-2021 a las 22:12:51
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`nombre`) VALUES
('Cámaras'),
('Discos Duros'),
('Gráficas'),
('Impresoras'),
('Portátiles'),
('Sobremesa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `filtro`
--

CREATE TABLE `filtro` (
  `Nombre` varchar(50) NOT NULL,
  `codigo` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `filtro`
--

INSERT INTO `filtro` (`Nombre`, `codigo`) VALUES
('Todos', 0),
('5', 5),
('10', 10),
('15', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `codigo` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `categoria` varchar(20) DEFAULT NULL,
  `precio` int(11) NOT NULL,
  `imagen` varchar(100) NOT NULL,
  `activo` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`codigo`, `nombre`, `descripcion`, `stock`, `categoria`, `precio`, `imagen`, `activo`) VALUES
(14, 'Portátil Hp Pavilion', NULL, 23, 'Portátiles', 499, 'hp.png', 1),
(16, 'Samsung Evo 980', NULL, 76, 'Gráficas', 2499, 'ssd.png', 1),
(17, 'Nvidia RTX 2080', NULL, 12, 'Gráficas', 2499, 'grafica.png', 1),
(18, 'Impresora Epson', NULL, 200, 'Gráficas', 99, 'impresora.png', 1),
(19, 'Canon 200D', NULL, 65, 'Gráficas', 99, 'camara.png', 1),
(20, 'Pc Sobremesa Gaming', NULL, 32, 'Gráficas', 2499, 'gaming.png', 1),
(43, 'PS5', NULL, 15, 'Portátiles', 500, 'ps5.png', 1),
(44, 'PS4', NULL, 156, 'Impresoras', 100, 'ps4.png', 1),
(45, 'PS3', NULL, 7, 'Gráficas', 10, 'ps3.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `codigo` int(10) UNSIGNED NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `apellidos` varchar(40) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `telefono` int(9) DEFAULT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `Jefe` varchar(2) DEFAULT 'No',
  `activo` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`codigo`, `email`, `nombre`, `apellidos`, `direccion`, `telefono`, `contraseña`, `Jefe`, `activo`) VALUES
(15, 'profesor@gmail.com', 'Ricardo', 'Profesor', 'IES Vegas de Mijas', 542453123, '$2y$10$aFhttwrAzngIe.8Jr3nY3.mygJ/nzCcjDt702h/5ISN8K602PtKEe', 'Sí', 1),
(16, 'user@gmail.com', 'Usuario', 'Normal', 'IES Vegas de Mijas', 332435654, '$2y$10$AYdFSiDtIEP.Y5Gwdx4gDuE47keXEtD7QmMhSiGAFpCiWde9swmq6', 'No', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`nombre`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `fk_categoria` (`categoria`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`codigo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `codigo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `codigo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_categoria` FOREIGN KEY (`categoria`) REFERENCES `categorias` (`nombre`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
