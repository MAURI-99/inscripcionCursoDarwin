-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-05-2025 a las 05:16:27
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
-- Base de datos: `course_registration_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `instructor` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `category`, `duration`, `start_date`, `end_date`, `instructor`) VALUES
(1, 'Desarrollo Web', 'Curso completo de desarrollo web con PHP y MySQL', 'Tecnología', '3 meses', '2025-06-01', '2025-08-31', 'Juan Pérez'),
(2, 'Marketing Digital', 'Aprende las estrategias más efectivas de marketing digital', 'Marketing', '2 meses', '2025-07-01', '2025-08-31', 'Ana Gómez'),
(3, 'Introducción a la Inteligencia Artificial', 'Conceptos básicos de IA y aprendizaje automático', 'Tecnología', '2 meses', '2025-06-15', '2025-08-15', 'Carlos Sánchez'),
(4, 'Diseño Gráfico', 'Fundamentos del diseño gráfico con herramientas modernas', 'Arte y Diseño', '3 meses', '2025-07-01', '2025-09-30', 'Laura Torres'),
(5, 'Finanzas Personales', 'Aprende a administrar tu dinero y planificar tus finanzas', 'Economía', '1 mes', '2025-06-10', '2025-07-10', 'Pedro Ramírez'),
(6, 'Programación en Python', 'Curso práctico de Python desde cero', 'Tecnología', '2 meses', '2025-06-20', '2025-08-20', 'María López'),
(7, 'Fotografía Digital', 'Técnicas y composición para fotografía profesional', 'Arte y Diseño', '1.5 meses', '2025-07-05', '2025-08-20', 'Andrés Molina'),
(8, 'Gestión de Proyectos', 'Metodologías ágiles y tradicionales para liderar proyectos', 'Administración', '2 meses', '2025-06-25', '2025-08-25', 'Sofía Herrera'),
(9, 'Inglés Básico', 'Curso de inglés para principiantes enfocado en conversación', 'Idiomas', '2 meses', '2025-06-01', '2025-07-31', 'Emily Brown'),
(10, 'Excel para Negocios', 'Domina Excel desde funciones básicas hasta tablas dinámicas', 'Ofimática', '1.5 meses', '2025-06-15', '2025-07-31', 'Diego Castro'),
(11, 'Power BI Básico', 'Aprende a crear dashboards interactivos y visualizaciones de datos', 'Ofimática', '1 mes', '2025-07-01', '2025-07-31', 'Carla Vega');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `course_registrations`
--

CREATE TABLE `course_registrations` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `course_registrations`
--

INSERT INTO `course_registrations` (`id`, `student_id`, `course_id`, `registration_date`) VALUES
(38, 16, 1, '2025-05-19 03:09:59'),
(39, 16, 4, '2025-05-19 03:10:59'),
(40, 16, 2, '2025-05-19 03:11:06'),
(41, 17, 1, '2025-05-19 03:13:13'),
(42, 17, 4, '2025-05-19 03:13:13'),
(43, 17, 11, '2025-05-19 03:13:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` enum('Masculino','Femenino','Otro') DEFAULT 'Otro',
  `address` varchar(255) DEFAULT NULL,
  `education_level` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `students`
--

INSERT INTO `students` (`id`, `full_name`, `email`, `birthdate`, `gender`, `address`, `education_level`, `password`) VALUES
(16, 'DARWIN MAURICIO ARMIJOS TORRES', 'mauriarmyaz.1999@gmail.com', '2000-02-16', 'Masculino', 'Cutuglahua', 'SUPERIOR', '$2y$10$/ptbg/xeqTZF4DEK9LX1YODne21orKXVgh2jPHTG6hKIwm9ECezrG'),
(17, 'MANUEL DARWIN ARMIJOS CALERO', 'dmanuel_73@gmail.com', '1973-02-20', 'Masculino', 'SANGOLQUI', 'SUPERIOR', '$2y$10$EbmixxJZwVkN.Ks5YtzxW.dmJcNQXlgZMYKE.VlMu6wMrgOneOgeu');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `course_registrations`
--
ALTER TABLE `course_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indices de la tabla `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `course_registrations`
--
ALTER TABLE `course_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `course_registrations`
--
ALTER TABLE `course_registrations`
  ADD CONSTRAINT `course_registrations_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_registrations_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
