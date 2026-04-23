-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-04-2026 a las 05:54:11
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
(1, '31114242', 'Alex', '0256', NULL, '2026-04-23 00:53:00'),
(2, '3114242', 'Alexr', '0256', NULL, '2026-04-23 00:56:38');

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
(1, 'Almasur', '12345678901234567890', '', '12345678901', 'aaaaa', '', '$');

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
(1, 1, 24, 3, 2.00, NULL),
(2, 2, 24, 3, 2.00, NULL),
(3, 3, 24, 1, 2.00, NULL);

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs_acceso`
--

CREATE TABLE `logs_acceso` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `logs_acceso`
--

INSERT INTO `logs_acceso` (`id`, `id_usuario`, `fecha_hora`) VALUES
(1, 1, '2026-04-22 20:41:55'),
(2, 8, '2026-04-22 20:43:11'),
(3, 1, '2026-04-22 20:47:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
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

INSERT INTO `productos` (`id`, `codigo`, `nombre`, `categoria`, `descripcion`, `precio`, `stock`, `id_usuario`, `creado_en`, `estado`) VALUES
(16, '1234567890123', 'Arroz', 'Alimentos', NULL, 2.00, 1, NULL, '2026-04-22 23:47:21', 1),
(18, '0001-0001-AAA', 'Jabon', 'Uso Diario', NULL, 3.00, 2, NULL, '2026-04-22 23:54:17', 1),
(19, '0001-0001-AAB', 'Pasta', 'Alimentos', NULL, 1.50, 0, NULL, '2026-04-23 01:00:12', 1),
(20, '0001-0001-AAC', 'Harina', 'Alimentos', NULL, 1.00, 3, NULL, '2026-04-23 01:00:41', 1),
(21, '0001-0001-AAD', 'Cepillo de dientes', 'General', NULL, 1.75, 4, NULL, '2026-04-23 01:01:13', 1),
(22, '0001-0001-AAE', 'Salsa de tomate', 'Alimentos', NULL, 3.00, 5, NULL, '2026-04-23 01:01:42', 1),
(23, '0001-0001-AAF', 'Mayonesa Mavesa', 'Alimentos', NULL, 5.00, 6, NULL, '2026-04-23 01:02:11', 1),
(24, '0001-0001-AAG', 'Crema dental Colgate', 'Uso Diario', NULL, 2.00, 0, NULL, '2026-04-23 02:01:07', 1);

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
(1, 'Jairo Balsa', 'admin', 'leonmontespc@gmail.com', '$2y$10$vfz5kZB9YkDg5EKsiLY.UuOHKIVaaG27lJiTNJ//IWxlko2LzAYj.', 'Administrador', '', 'uploads/almasur.png', 'uploads/jairo.png', 'Piritu', '04245556364', NULL, NULL, NULL, '2026-01-25 22:58:14'),
(3, 'Roboco', 'robo', 'jesus@gmail.com', '$2y$10$hWW4/4cd7trvScAnsIgSLO4llzDPH9yyE1oeyQqfEx.H3lkEsDHju', 'Empleado', 'Almasur', 'default_logo.png', 'uploads/jairo.png', 'Piritu', '04245556364', NULL, NULL, NULL, '2026-01-26 00:17:10'),
(4, 'Jairo', 'jairo', 'admin@gmail.com', '$2y$10$VCSr0EfaiDMVpQ1lWp5yDu3fo2ieHWIBKJdAKMeG0PusDzaGBTUEi', 'Empleado', 'Almasur', 'default_logo.png', 'uploads/jairo.png', 'Piritu', '04245556364', NULL, NULL, NULL, '2026-04-22 22:09:11'),
(8, 'Alex Rivero', 'Alex', 'alexgabrielrs@gmail.com', '$2y$10$vyoTn7i.H2g4kG8830HPcOzwEZMTIENV1fRSbnHLP.0iqREHyoUFy', 'Empleado', 'Almasur', 'default_logo.png', 'uploads/jairo.png', 'Piritu', '04245556364', NULL, NULL, NULL, '2026-04-23 00:42:41');

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
(1, 1, 1, 6, '2026-04-23 02:53:24', 6.96, 0.96),
(2, 1, 1, 6, '2026-04-23 02:57:38', 6.96, 0.96),
(3, 1, 1, 2, '2026-04-23 03:08:15', 2.32, 0.32);

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
-- Indices de la tabla `logs_acceso`
--
ALTER TABLE `logs_acceso`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `logs_acceso`
--
ALTER TABLE `logs_acceso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
