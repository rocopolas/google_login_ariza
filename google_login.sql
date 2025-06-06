-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 06-06-2025 a las 02:05:55
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
-- Base de datos: `google_login`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigos_recuperacion`
--

CREATE TABLE `codigos_recuperacion` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `codigos_recuperacion`
--

INSERT INTO `codigos_recuperacion` (`id`, `usuario_id`, `codigo`) VALUES
(2, 3, 552149);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `fecha_registro`, `fecha_actualizacion`) VALUES
(1, 'Rocco', 'roccoperez2e@gmail.com', '2025-06-05 21:46:48', '2025-06-05 21:46:48'),
(2, 'rocopolas', 'foxigamer49@gmail.com', '2025-06-05 21:47:44', '2025-06-05 21:47:44'),
(4, 'Luca', 'lucamatiasbaratta@gmail.com', '2025-06-05 22:01:35', '2025-06-05 22:01:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_no_g`
--

CREATE TABLE `usuarios_no_g` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_no_g`
--

INSERT INTO `usuarios_no_g` (`id`, `nombre`, `correo`, `contraseña`, `fecha_registro`, `fecha_actualizacion`) VALUES
(1, 'Rocco', 'rocco@roco.com', '$2y$10$nQDUc8vY1g3gwXBDVFI9tOj2P0ZKGSDSKXnT3r37kmNKBTj7vsaAq', '2025-06-05 22:00:02', '2025-06-05 22:00:02'),
(2, 'Rudka', 'rukda@lara.com', '$2y$10$0wX7UofzLi.xGJ9Z7Kn6Meggh.FkOeh94KznxhFNzbSfQi/uxyEFy', '2025-06-05 22:06:17', '2025-06-05 22:06:17'),
(3, 'Chouna', 'chouna@chouna.com', '$2y$10$eE2TGCeEdnoGsDP.lI10ne1ENl5qJK.G66B0uFhGJ1.H62ES97Oo2', '2025-06-05 22:07:23', '2025-06-05 22:17:34'),
(4, 'Ariza', 'ariza@ariza.com', '$2y$10$BdsF.2r.z/sMW0BxsGIGLeUXBy7WD9G9nIOW9P9DgFTRIMRplh8.q', '2025-06-05 22:28:04', '2025-06-05 22:30:16');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `codigos_recuperacion`
--
ALTER TABLE `codigos_recuperacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `usuarios_no_g`
--
ALTER TABLE `usuarios_no_g`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `codigos_recuperacion`
--
ALTER TABLE `codigos_recuperacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios_no_g`
--
ALTER TABLE `usuarios_no_g`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `codigos_recuperacion`
--
ALTER TABLE `codigos_recuperacion`
  ADD CONSTRAINT `codigos_recuperacion_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios_no_g` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
