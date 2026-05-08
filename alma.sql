-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-05-2026 a las 04:29:58
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
(1, 'Almasur', '12345678901234567890', '', '12345678901', '', 'uploads/logo_sistema_1778195354.jpeg', '$');

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
(3, 3, 24, 1, 2.00, NULL),
(4, 4, 19, 3, 1.50, NULL),
(5, 4, 16, 1, 2.00, NULL);

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
(3, 1, '2026-04-22 20:47:14'),
(4, 1, '2026-04-26 19:40:18'),
(5, 1, '2026-05-07 19:12:20'),
(6, 9, '2026-05-07 19:15:50'),
(7, 1, '2026-05-07 19:27:20'),
(8, 9, '2026-05-07 21:50:11'),
(9, 1, '2026-05-07 21:51:01'),
(10, 9, '2026-05-07 21:56:57'),
(11, 1, '2026-05-07 21:57:40'),
(12, 9, '2026-05-07 21:58:23'),
(13, 1, '2026-05-07 22:01:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
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

INSERT INTO `productos` (`id`, `codigo`, `nombre`, `categoria`, `marca`, `descripcion`, `precio`, `stock`, `id_usuario`, `creado_en`, `estado`) VALUES
(30, 'PROD-263335', 'Arroz Tradicional', 'ALIMENTOS', 'mary', NULL, 2.00, 50, NULL, '2026-05-08 02:07:12', 1),
(31, 'PROD-269351', 'Arroz Superior', 'ALIMENTOS', 'mary', NULL, 2.50, 50, NULL, '2026-05-08 02:10:54', 1),
(32, 'PROD-268804', 'Harina de maíz blanco', 'ALIMENTOS', 'mary', NULL, 1.50, 50, NULL, '2026-05-08 02:11:38', 1),
(33, 'PROD-266433', 'Pasta', 'ALIMENTOS', 'mary', NULL, 3.00, 50, NULL, '2026-05-08 02:12:42', 1),
(34, 'PROD-264330', 'Harina de trigo', 'ALIMENTOS', 'mary', NULL, 5.00, 50, NULL, '2026-05-08 02:14:24', 1),
(35, 'PROD-264559', 'Aceite de oliva', 'ALIMENTOS', 'mary', NULL, 4.00, 50, NULL, '2026-05-08 02:14:53', 1),
(36, 'PROD-262259', 'Arroz', 'ALIMENTOS', 'Amanecer', NULL, 2.00, 50, NULL, '2026-05-08 02:17:06', 1),
(37, 'PROD-268702', 'Azúcar 1kg', 'ALIMENTOS', 'Amanecer', NULL, 3.00, 50, NULL, '2026-05-08 02:19:34', 1),
(38, 'PROD-266342', 'Leche  400 g', 'ALIMENTOS', 'Amanecer', NULL, 5.00, 50, NULL, '2026-05-08 02:21:19', 1),
(39, 'PROD-265869', 'Aceite 500ml', 'ALIMENTOS', 'Amanecer', NULL, 3.00, 50, NULL, '2026-05-08 02:22:47', 1),
(40, 'PROD-261329', 'Aceite 828ml', 'ALIMENTOS', 'Amanecer', NULL, 5.00, 50, NULL, '2026-05-08 02:24:01', 1);

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

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `email`, `password`, `rol`, `nombre_negocio`, `foto_perfil`, `direccion`, `telefono`, `codigo_recuperacion`, `codigo_verificacion`, `codigo_expira`, `creado_en`) VALUES
(1, 'fran', 'admin', 'leonmontespc@gmail.com', '$2y$10$v9CntFFgZhuEGhOT1.kvBuZ6jN9uChB4BPEVlWuWRAjNgb7aYAxly', 'Administrador', '', 'uploads/perfil_1_1778205469.jpg', 'Piritu', '04245556364', NULL, NULL, NULL, '2026-01-25 22:58:14'),
(9, 'Alex rivero', 'blaster', 'alexgabrielrs@gmail.com', '$2y$10$4jz23cHMo3eaLjN82YoJNua/Kd/9u434X0y/HXUN7P5/uefZHNu/6', 'Empleado', 'Almasur', 'uploads/perfil_9_1778205447.jpg', 'Piritu', '04245556364', NULL, NULL, NULL, '2026-05-07 23:14:39');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `logs_acceso`
--
ALTER TABLE `logs_acceso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
