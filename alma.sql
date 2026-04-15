-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-04-2026 a las 03:27:12
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `alma`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `identidad` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `identidad`, `nombre`, `telefono`, `direccion`, `fecha_registro`) VALUES
(1, '123435', 'jhonaike', '', NULL, '2026-02-04 22:59:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `nombre_negocio` varchar(100) DEFAULT 'Almasur',
  `ruc` varchar(20) DEFAULT '00000000',
  `direccion` varchar(255) DEFAULT '',
  `telefono` varchar(20) DEFAULT '',
  `mensaje_factura` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT '',
  `moneda` varchar(5) DEFAULT '$'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `nombre_negocio`, `ruc`, `direccion`, `telefono`, `mensaje_factura`, `logo`, `moneda`) VALUES
(1, 'Almasur', '456457456', 'Turen', '', '¡Gracias por su compra!', '', '$');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id`, `id_venta`, `id_producto`, `cantidad`, `precio`, `precio_unitario`) VALUES
(1, 15, 2, 1, 3.00, NULL),
(2, 16, 3, 1, 4.00, NULL),
(3, 17, 2, 1, 3.01, NULL),
(4, 18, 7, 1, 567.00, NULL),
(5, 19, 7, 1, 567.00, NULL),
(6, 20, 7, 1, 567.00, NULL),
(7, 21, 2, 5, 3.00, NULL),
(8, 21, 3, 5, 4.00, NULL),
(9, 21, 7, 8, 567.00, NULL),
(10, 22, 4, 2, 3.00, NULL),
(11, 23, 2, 2, 3.00, NULL),
(12, 23, 3, 2, 4.00, NULL),
(13, 23, 4, 6, 3.00, NULL),
(14, 24, 7, 2, 567.00, NULL),
(15, 25, 7, 1, 567.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_operaciones`
--

CREATE TABLE `historial_operaciones` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `accion` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_operaciones`
--

INSERT INTO `historial_operaciones` (`id`, `id_usuario`, `accion`, `fecha`) VALUES
(0, 1, 'Agregó producto: harina', '2026-01-27 23:01:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `nombre`, `descripcion`, `precio`, `stock`, `id_usuario`, `creado_en`, `estado`) VALUES
(2, NULL, 'arroz', NULL, 3.00, 10, 1, '2026-01-25 23:24:09', 1),
(3, NULL, 'pasta', NULL, 4.00, 10, 1, '2026-01-25 23:24:26', 1),
(4, NULL, 'harina', NULL, 3.00, 41, 1, '2026-01-27 23:01:23', 1),
(5, NULL, 'leche', NULL, 3.00, 49, 1, '2026-01-27 23:01:42', 0),
(6, NULL, 'jabon', NULL, 6.00, 60, 1, '2026-01-27 23:28:02', 1),
(7, NULL, 'hifs', NULL, 567.00, 74, 1, '2026-01-27 23:30:26', 1),
(8, NULL, 'fg', NULL, 45.00, 78, 1, '2026-01-28 00:43:55', 1),
(9, NULL, 'rt', NULL, 45.00, 78, NULL, '2026-01-28 00:49:16', 1),
(10, NULL, 'jabon', NULL, 5.00, 60, NULL, '2026-01-30 16:29:23', 1),
(11, NULL, 'pepsi', NULL, 1.00, 10, NULL, '2026-01-30 17:45:08', 1),
(12, NULL, 'cocacola', NULL, 2.00, 15, NULL, '2026-01-30 17:45:33', 1),
(13, NULL, 'viagra', NULL, 50.00, 21, NULL, '2026-01-30 17:45:57', 1),
(14, NULL, 'champu', NULL, 2.00, 30, NULL, '2026-01-30 17:46:20', 1),
(15, NULL, 'asdasd', NULL, 0.01, 0, NULL, '2026-04-14 23:43:10', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('Administrador','Empleado') DEFAULT 'Empleado',
  `nombre_negocio` varchar(100) DEFAULT 'Almasur',
  `logo` varchar(255) DEFAULT 'default_logo.png',
  `foto_perfil` varchar(255) DEFAULT 'uploads/jairo.png',
  `direccion` text DEFAULT 'Piritu',
  `telefono` varchar(20) DEFAULT '04245556364',
  `codigo_recuperacion` varchar(6) DEFAULT NULL,
  `codigo_verificacion` varchar(10) DEFAULT NULL,
  `codigo_expira` datetime DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `email`, `password`, `rol`, `nombre_negocio`, `logo`, `foto_perfil`, `direccion`, `telefono`, `codigo_recuperacion`, `codigo_verificacion`, `codigo_expira`, `creado_en`) VALUES
(1, 'Jairo Balsa', 'admin', 'leonmontespc@gmail.com', '$2y$10$HVej.907cNQZ3/6KdKeCNeuZDnuDlQrSv1mk7J/7LsbCG7HerHM2i', 'Administrador', '', 'uploads/almasur.png', 'uploads/jairo.png', 'Piritu', '04245556364', NULL, NULL, NULL, '2026-01-25 22:58:14'),
(2, 'Jairo', 'jairo', 'adimn@gmail.com', '2345', 'Empleado', 'Almasur', 'uploads/almasur.png', 'uploads/jairo.png', 'Piritu', '04245556364', NULL, NULL, NULL, '2026-01-25 22:58:14'),
(3, 'Roboco', 'robo', 'jesus@gmail.com', '$2y$10$hWW4/4cd7trvScAnsIgSLO4llzDPH9yyE1oeyQqfEx.H3lkEsDHju', 'Empleado', 'Almasur', 'default_logo.png', 'uploads/jairo.png', 'Piritu', '04245556364', NULL, NULL, NULL, '2026-01-26 00:17:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `subtotal` float DEFAULT 0,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `impuesto` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `id_usuario`, `id_cliente`, `subtotal`, `fecha`, `total`, `impuesto`) VALUES
(1, 1, NULL, 0, '2026-01-26 01:17:58', 4.64, 0),
(2, 1, NULL, 0, '2026-01-26 01:18:12', 8.12, 0),
(3, 1, NULL, 0, '2026-01-26 01:18:13', 8.12, 0),
(4, 1, NULL, 0, '2026-01-26 01:19:18', 8.12, 0),
(5, 1, NULL, 0, '2026-01-26 01:19:36', 8.12, 0),
(6, 1, NULL, 0, '2026-01-26 01:19:40', 8.12, 0),
(7, 1, NULL, 7, '2026-01-26 01:22:57', 8.12, 1.12),
(8, 1, NULL, 7, '2026-01-26 01:23:09', 8.12, 1.12),
(9, 1, NULL, 7, '2026-01-26 01:23:10', 8.12, 1.12),
(10, 1, NULL, 7, '2026-01-26 01:27:20', 8.12, 1.12),
(11, 1, NULL, 7, '2026-01-26 01:27:25', 8.12, 1.12),
(12, 1, NULL, 7, '2026-01-26 01:31:25', 8.12, 1.12),
(13, 1, NULL, 7, '2026-01-26 01:31:39', 8.12, 1.12),
(14, 1, NULL, 3, '2026-01-26 01:32:44', 3.48, 0.48),
(15, 1, NULL, 3, '2026-01-26 01:35:38', 3.48, 0.48),
(16, 1, NULL, 4, '2026-01-27 22:37:11', 4.64, 0.64),
(17, 1, NULL, 3.01, '2026-01-28 00:54:01', 3.49, 0.4816),
(18, 1, NULL, 567, '2026-01-30 00:50:12', 657.72, 90.72),
(19, 1, NULL, 567, '2026-01-30 00:50:45', 657.72, 90.72),
(20, 1, NULL, 567, '2026-01-30 00:56:07', 657.72, 90.72),
(21, 1, NULL, 4571, '2026-01-30 14:32:54', 5302.36, 731.36),
(22, 1, NULL, 6, '2026-01-30 16:26:52', 6.96, 0.96),
(23, 1, NULL, 32, '2026-01-30 17:44:01', 37.12, 5.12),
(24, 1, NULL, 1134, '2026-02-04 23:00:46', 1315.44, 181.44),
(25, 1, 1, 567, '2026-02-04 23:17:58', 657.72, 90.72);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identidad` (`identidad`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `detalle_ventas_ibfk_2` (`id_producto`);

--
-- Indices de la tabla `historial_operaciones`
--
ALTER TABLE `historial_operaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
