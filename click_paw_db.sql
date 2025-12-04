-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-12-2025 a las 02:34:57
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
-- Base de datos: `click_paw_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`, `usuario`, `password`) VALUES
(1, 'admin', '$2y$10$Qj/6f.1J.M4/H5e.S2V/x.aP2E/7D.b8F/9A.g6h/5j.4k.3l.2o'),
(2, 'ana', '$2y$10$WpP6.jYx.Z7f4bE.Q5gK.OaP2E/7D.b8F/9A.g6h/5j.4k.3l.2o');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascotas`
--

CREATE TABLE `mascotas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `especie` varchar(50) NOT NULL,
  `raza` varchar(100) DEFAULT 'Mestizo',
  `edad` int(3) NOT NULL,
  `genero` enum('Macho','Hembra') NOT NULL,
  `tamano` enum('Pequeño','Mediano','Grande') NOT NULL,
  `descripcion` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `estado_salud` text NOT NULL,
  `estado` enum('Disponible','En Proceso','Adoptado') NOT NULL DEFAULT 'Disponible',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mascotas`
--

INSERT INTO `mascotas` (`id`, `nombre`, `especie`, `raza`, `edad`, `genero`, `tamano`, `descripcion`, `foto`, `estado_salud`, `estado`, `fecha_registro`) VALUES
(1, 'Fido', 'Perro', 'Mestizo', 3, 'Macho', 'Mediano', 'Fido es un perro muy juguetón y leal. Fue rescatado de la calle y ahora busca un hogar donde pueda correr. Se lleva bien con niños.', NULL, 'Vacunas al día, desparasitado y esterilizado.', 'Disponible', '2025-12-03 19:28:36'),
(2, 'Misha', 'Gato', 'Siamés', 2, 'Hembra', 'Pequeño', 'Misha es una gata tranquila y elegante. Le encanta tomar siestas al sol y que la acaricien. Ideal para un apartamento.', NULL, 'Todas las vacunas al día, esterilizada.', 'Disponible', '2025-12-03 19:28:36'),
(3, 'Rocky', 'Perro', 'Labrador', 5, 'Macho', 'Grande', 'Rocky es un perro adulto lleno de energía. Es ideal para una familia activa. Es muy inteligente y obediente.', NULL, 'Completo de vacunas, en perfecto estado de salud.', 'Adoptado', '2025-12-03 19:28:36'),
(4, 'Bella', 'Perro', 'Mestizo', 5, 'Hembra', 'Mediano', 'Bella es una perra noble y protectora. Se lleva bien con otros perros y es muy tranquila en casa. Perfecta para compañía.', NULL, 'Vacunas al día, esterilizada.', 'Disponible', '2025-12-03 19:28:36'),
(5, 'Simba', 'Gato', 'Común Europeo', 1, 'Macho', 'Pequeño', 'Simba es un gatito curioso y muy sociable. Le encanta jugar con pelotitas de papel y seguir a la gente por toda la casa.', NULL, 'Primeras vacunas completas, desparasitado.', 'En Proceso', '2025-12-03 19:28:36'),
(6, 'Nala', 'Gato', 'Mestizo', 4, 'Hembra', 'Mediano', 'Nala es una gata muy independiente pero cariñosa cuando quiere. Disfruta de su propio espacio pero también de una buena sesión de mimos.', NULL, 'Vacunas al día, esterilizada, con microchip.', 'Disponible', '2025-12-03 19:28:36'),
(7, 'Toby', 'Perro', 'Beagle', 2, 'Macho', 'Pequeño', 'Toby es un beagle lleno de energía y con un olfato increíble. Necesita un hogar con un patio cercado donde pueda explorar. Es muy amigable con todo el mundo.', NULL, 'Todas las vacunas, esterilizado.', 'Disponible', '2025-12-03 19:28:36'),
(8, 'Coco', 'Gato', 'Persa', 6, 'Hembra', 'Mediano', 'Coco es una gata persa muy calmada y señorial. Requiere cepillado diario. Es la compañera perfecta para una persona tranquila.', NULL, 'Salud excelente, vacunas al día.', 'Disponible', '2025-12-03 19:28:36'),
(9, 'Zeus', 'Perro', 'Pastor Alemán', 4, 'Macho', 'Grande', 'Zeus es un perro guardián por naturaleza, muy leal y protector con su familia. Necesita un dueño con experiencia en razas grandes.', NULL, 'Vacunas al día, esterilizado.', 'Disponible', '2025-12-03 19:28:36'),
(10, 'Cleo', 'Gato', 'Mestizo', 3, 'Hembra', 'Pequeño', 'Cleo fue rescatada junto a sus cachorros (que ya fueron adoptados). Es una madre increíblemente dulce y ahora busca su propio hogar para ser consentida.', NULL, 'Esterilizada y con todas sus vacunas.', 'Disponible', '2025-12-03 19:28:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `fecha_envio` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id` int(11) NOT NULL,
  `id_mascota` int(11) NOT NULL,
  `nombre_solicitante` varchar(255) NOT NULL,
  `email_solicitante` varchar(255) NOT NULL,
  `telefono_solicitante` varchar(20) NOT NULL,
  `direccion_solicitante` text NOT NULL,
  `motivo_adopcion` text NOT NULL,
  `estado_solicitud` enum('Recibida','En Revision','Aprobada','Rechazada') NOT NULL DEFAULT 'Recibida',
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudes`
--

INSERT INTO `solicitudes` (`id`, `id_mascota`, `nombre_solicitante`, `email_solicitante`, `telefono_solicitante`, `direccion_solicitante`, `motivo_adopcion`, `estado_solicitud`, `fecha_solicitud`) VALUES
(1, 1, 'Juan Pérez', 'juan.perez@email.com', '6677-8899', 'Calle Falsa 123, Ciudad de Panamá', 'Siempre he querido tener un perro compañero y Fido parece perfecto para mi estilo de vida. Tengo un patio grande donde podrá jugar.', 'Recibida', '2025-12-03 19:28:36'),
(2, 2, 'Maria Rodriguez', 'maria.r@email.com', '6555-4433', 'Avenida Central 45, Colón', 'Busco una gata tranquila que me haga compañía. Misha parece tener la personalidad ideal.', 'En Revision', '2025-12-03 19:28:36'),
(3, 5, 'Carlos González', 'carlos.g@email.com', '6222-1133', 'Barrio San Felipe, Casa 24', 'Mi familia y yo estamos buscando un gatito joven para llenar de alegría nuestro hogar. Simba se ve adorable.', 'Recibida', '2025-12-03 19:28:36'),
(4, 9, 'Edgar Lorenzo', 'hola@gmail.com', '1121-3131', 'Nigga', 'dsdd', 'Recibida', '2025-12-03 19:55:29'),
(5, 10, 'Charlie Kirk', 'hola@gmail.com', '1121-3133', 'Nigga2', 'Poto', 'Recibida', '2025-12-04 00:31:17'),
(6, 9, 'Edgar Lorenzo', 'hola@gmail.com', '1121-3137', 'Nigga3', 'Si', 'Recibida', '2025-12-04 01:15:46');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mascota` (`id_mascota`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `solicitudes_ibfk_1` FOREIGN KEY (`id_mascota`) REFERENCES `mascotas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
