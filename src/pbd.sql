-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-06-2022 a las 14:03:20
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pbd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listas`
--

CREATE TABLE `listas` (
  `id` int(10) NOT NULL,
  `tema` varchar(15) NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `item0` varchar(30) NOT NULL,
  `item1` varchar(30) NOT NULL,
  `item2` varchar(30) NOT NULL,
  `item3` varchar(30) NOT NULL,
  `item4` varchar(30) NOT NULL,
  `item5` varchar(30) NOT NULL,
  `item6` varchar(30) NOT NULL,
  `item7` varchar(30) NOT NULL,
  `item8` varchar(30) NOT NULL,
  `item9` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `email` varchar(50) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `phone` int(9) NOT NULL,
  `password` char(32) CHARACTER SET latin1 NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `token` char(32) NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`email`, `nombre`, `phone`, `password`, `reg_date`, `token`, `date_update`) VALUES
('eric.casanova@cesi.info', 'eric', 666, '050248cd2efad770e194ca0e12d44264', '2022-06-07 07:34:33', 'fe00d7785be656cb113a51ffcab56b22', '2022-06-13 12:00:17'),
('manolo@gmail.com', 'manolo', 667, '050248cd2egad770e194ca0e12d44264', '2022-07-07 07:34:33', '', '2022-06-13 10:50:41'),
('pedro@hotmail.es', 'pedro', 668, '050248cd2efad771e194ca0e12d44264', '2022-06-07 17:34:33', '', '2022-06-13 10:50:41'),
('pepito@cesi.info			', 'pepito', 677, '980ac217c6b51e7dc41040bec1edfec8', '2022-06-10 10:23:00', '', '2022-06-13 10:50:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_temp`
--

CREATE TABLE `usuarios_temp` (
  `id` int(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `phone` int(9) NOT NULL,
  `password` char(32) CHARACTER SET latin1 NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios_temp`
--

INSERT INTO `usuarios_temp` (`id`, `email`, `nombre`, `phone`, `password`, `reg_date`) VALUES
(0, 'eric.casanova@cesi.info', 'eric', 666, '050248cd2efad770e194ca0e12d44264', '2022-06-10 11:47:10'),
(1, 'manolo@gmail.com', 'manolo', 667, '050248cd2egad770e194ca0e12d44264', '2022-07-07 07:34:33'),
(2, 'pedro@hotmail.es', 'pedro', 668, '050248cd2efad771e194ca0e12d44264', '2022-06-07 17:34:33');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `listas`
--
ALTER TABLE `listas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `usuarios_temp`
--
ALTER TABLE `usuarios_temp`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `listas`
--
ALTER TABLE `listas`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios_temp`
--
ALTER TABLE `usuarios_temp`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
