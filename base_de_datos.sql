-- MySQL Dump
-- Generado el: 2026-06-08 02:19:48
SET FOREIGN_KEY_CHECKS=0;

-- ------------------------------------------------------
-- Estructura de tabla para `migrations`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR NOT NULL,
  `batch` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para la tabla `migrations`
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000010_create_niveles_table', 1),
(5, '2024_01_01_000011_create_grados_table', 1),
(6, '2024_01_01_000012_create_secciones_table', 1),
(7, '2024_01_01_000013_create_cursos_table', 1),
(8, '2024_01_01_000014_create_docente_asignaciones_table', 1),
(9, '2024_01_01_000015_create_archivos_table', 1),
(10, '2026_05_05_000618_add_deleted_at_to_archivos_table', 2),
(11, '2026_05_15_000001_add_bimestre_to_archivos_table', 3),
(12, '2026_05_18_000001_add_anio_to_archivos_table', 4),
(13, '2026_05_18_000002_create_anio_lectivos_table', 5),
(14, '2026_05_18_183132_add_username_to_users_table', 6),
(15, '2026_05_18_202537_add_password_plain_to_users_table', 7);

-- ------------------------------------------------------
-- Estructura de tabla para `users`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR NOT NULL,
  `apellido` VARCHAR NOT NULL,
  `email` VARCHAR NOT NULL,
  `email_verified_at` DATETIME NULL,
  `password` VARCHAR NOT NULL,
  `rol` VARCHAR NOT NULL DEFAULT 'docente',
  `activo` INT NOT NULL DEFAULT '1',
  `remember_token` VARCHAR NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `username` VARCHAR NULL,
  `password_plain` VARCHAR NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para la tabla `users`
INSERT INTO `users` (`id`, `nombre`, `apellido`, `email`, `email_verified_at`, `password`, `rol`, `activo`, `remember_token`, `created_at`, `updated_at`, `username`, `password_plain`) VALUES
(1, 'Administrador', 'Sistema', 'admin@colegio.com', NULL, '$2y$12$jqrBGta/2KY/efquTmRgEOBaau.F.kqCO8F67iIiNpKtS9PtglMCW', 'admin', '1', NULL, '2026-05-04 02:47:14', '2026-05-19 00:06:37', 'ADMIN', 'Admin1234'),
(11, 'Jhuliana', 'Arcela Rondoy', 'JARCELAR@docente.local', NULL, '$2y$12$gb8QCdmFINGc8hO4nHy4huxJuL2NjaiF7CDlGj.HtTEhIkmc9kIPq', 'docente', '1', NULL, '2026-06-02 22:07:36', '2026-06-02 22:11:59', 'JARCELAR', '10002000');

-- ------------------------------------------------------
-- Estructura de tabla para `password_reset_tokens`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR NOT NULL,
  `token` VARCHAR NOT NULL,
  `created_at` DATETIME NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para la tabla `password_reset_tokens`

-- ------------------------------------------------------
-- Estructura de tabla para `sessions`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` VARCHAR NOT NULL,
  `user_id` INT NULL,
  `ip_address` VARCHAR NULL,
  `user_agent` TEXT NULL,
  `payload` TEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para la tabla `sessions`
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('n1mdoAnLOKN8H2AWUUDZr3FmqZM3djOoMvP9M7XV', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.122.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiazhoRXpZcXo3VUJuREszdGJIWW1rMUVtSzgxT2oyVmdRMUd4Q2dvbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1780440290),
('9pS27XaAy5WyO8My4i6J39nc3IIwxiBQ3o4qdPjL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSVg5Uk1ZUmdoa2laaGxOa2Y1UVU0elRrd3hreXA5M3lXOTNhSVo1NCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1780444006),
('Yh0Lynbr9xDRqq33JklvCt126jrP4LuXqSrxzZiW', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMm43RnRqYmhlMjhSR3hEUkFQZXU2NTRLNTZ0djF0aUdVcGl5d0UzbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1780865262),
('GLveDIlLSDLVIzmPmT5xUiyrZQxTuMsNDkydxKsS', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVDVzTnVTbkNodTAzclh5REtDZEJIcDRhTk81WG1pV09Mam1JejdabiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9hcmNoaXZvcy8xMy9zdHJlYW0iO3M6NToicm91dGUiO3M6MjE6ImFkbWluLmFyY2hpdm9zLnN0cmVhbSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1780880343);

-- ------------------------------------------------------
-- Estructura de tabla para `cache`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` VARCHAR NOT NULL,
  `value` TEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para la tabla `cache`

-- ------------------------------------------------------
-- Estructura de tabla para `cache_locks`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` VARCHAR NOT NULL,
  `owner` VARCHAR NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para la tabla `cache_locks`

-- ------------------------------------------------------
-- Estructura de tabla para `jobs`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `queue` VARCHAR NOT NULL,
  `payload` TEXT NOT NULL,
  `attempts` INT NOT NULL,
  `reserved_at` INT NULL,
  `available_at` INT NOT NULL,
  `created_at` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para la tabla `jobs`

-- ------------------------------------------------------
-- Estructura de tabla para `job_batches`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` VARCHAR NOT NULL,
  `name` VARCHAR NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` TEXT NOT NULL,
  `options` TEXT NULL,
  `cancelled_at` INT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para la tabla `job_batches`

-- ------------------------------------------------------
-- Estructura de tabla para `failed_jobs`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` TEXT NOT NULL,
  `exception` TEXT NOT NULL,
  `failed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para la tabla `failed_jobs`

-- ------------------------------------------------------
-- Estructura de tabla para `niveles`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `niveles`;
CREATE TABLE `niveles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para la tabla `niveles`
INSERT INTO `niveles` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Inicial', '2026-05-04 02:47:12', '2026-05-04 02:47:12'),
(2, 'Primaria', '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(3, 'Secundaria', '2026-05-04 02:47:13', '2026-05-04 02:47:13');

-- ------------------------------------------------------
-- Estructura de tabla para `grados`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `grados`;
CREATE TABLE `grados` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR NOT NULL,
  `nivel_id` INT NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para la tabla `grados`
INSERT INTO `grados` (`id`, `nombre`, `nivel_id`, `created_at`, `updated_at`) VALUES
(1, '3 años', 1, '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(2, '4 años', 1, '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(3, '5 años', 1, '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(4, '1ro', 2, '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(5, '2do', 2, '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(6, '3ro', 2, '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(7, '4to', 2, '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(8, '5to', 2, '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(9, '6to', 2, '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(10, '1ro', 3, '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(11, '2do', 3, '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(12, '3ro', 3, '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(13, '4to', 3, '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(14, '5to', 3, '2026-05-04 02:47:13', '2026-05-04 02:47:13');

-- ------------------------------------------------------
-- Estructura de tabla para `secciones`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `secciones`;
CREATE TABLE `secciones` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para la tabla `secciones`
INSERT INTO `secciones` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'A', '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(2, 'B', '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(3, 'C', '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(4, 'D', '2026-05-04 02:47:13', '2026-05-04 02:47:13'),
(5, 'Única', '2026-05-14 16:43:24', '2026-05-14 16:43:24');

-- ------------------------------------------------------
-- Estructura de tabla para `cursos`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `cursos`;
CREATE TABLE `cursos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para la tabla `cursos`
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(12, 'Plan Lector', '2026-05-19 21:53:57', '2026-05-19 21:53:57'),
(13, 'Comunicación', '2026-05-19 21:54:06', '2026-05-19 21:54:06'),
(14, 'Educación Cristiana', '2026-05-19 21:54:24', '2026-05-19 21:59:09'),
(15, 'Física', '2026-05-19 21:54:34', '2026-05-19 21:54:34'),
(16, 'Tutoría', '2026-05-19 21:54:43', '2026-05-19 21:54:43'),
(17, 'Inglés', '2026-05-19 21:54:58', '2026-05-19 21:54:58'),
(18, 'Personal Social', '2026-05-19 21:55:22', '2026-05-19 21:55:22'),
(19, 'Matemática', '2026-05-19 21:55:32', '2026-05-19 21:55:32'),
(20, 'Razonamiento Verbal', '2026-05-19 21:57:04', '2026-05-19 21:57:04'),
(21, 'Ciencia y tecnologia', '2026-05-19 21:57:14', '2026-05-19 21:57:14'),
(22, 'Arte', '2026-05-19 21:57:23', '2026-05-19 21:57:23'),
(23, 'Razonamiento Matematico', '2026-05-19 22:03:30', '2026-05-19 22:03:30'),
(24, 'Literatura', '2026-05-20 01:38:47', '2026-05-20 01:38:47'),
(25, 'Computación', '2026-05-20 01:39:09', '2026-05-20 01:39:09'),
(26, 'Álgebra', '2026-05-20 01:39:28', '2026-05-20 01:39:28'),
(27, 'Geometría', '2026-05-20 01:39:42', '2026-05-20 01:39:42'),
(28, 'Matematica', '2026-06-02 22:07:36', '2026-06-02 22:07:36');

-- ------------------------------------------------------
-- Estructura de tabla para `docente_asignaciones`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `docente_asignaciones`;
CREATE TABLE `docente_asignaciones` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `curso_id` INT NOT NULL,
  `grado_id` INT NOT NULL,
  `seccion_id` INT NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para la tabla `docente_asignaciones`
INSERT INTO `docente_asignaciones` (`id`, `user_id`, `curso_id`, `grado_id`, `seccion_id`, `created_at`, `updated_at`) VALUES
(97, 11, 28, 4, 5, '2026-06-02 22:11:59', '2026-06-02 22:11:59'),
(98, 11, 13, 4, 5, '2026-06-02 22:11:59', '2026-06-02 22:11:59');

-- ------------------------------------------------------
-- Estructura de tabla para `archivos`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `archivos`;
CREATE TABLE `archivos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `nombre_original` VARCHAR NOT NULL,
  `nombre_archivo` VARCHAR NOT NULL,
  `ruta` VARCHAR NOT NULL,
  `tipo_archivo` VARCHAR NOT NULL,
  `tamanio` INT NOT NULL,
  `curso_id` INT NOT NULL,
  `grado_id` INT NOT NULL,
  `seccion_id` INT NOT NULL,
  `descripcion` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  `bimestre` INT NOT NULL DEFAULT '1',
  `anio` INT NOT NULL DEFAULT '2026',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para la tabla `archivos`
INSERT INTO `archivos` (`id`, `user_id`, `nombre_original`, `nombre_archivo`, `ruta`, `tipo_archivo`, `tamanio`, `curso_id`, `grado_id`, `seccion_id`, `descripcion`, `created_at`, `updated_at`, `deleted_at`, `bimestre`, `anio`) VALUES
(13, 11, 'Dialnet-GuiaParaRealizarEstudiosDeRevisionSistematicaCuant-9258000 (1).pdf', 'doc_6a1f5589471f51.91887182.pdf', 'docentes/11/doc_6a1f5589471f51.91887182.pdf', 'application/pdf', 1300064, 13, 4, 5, NULL, '2026-06-02 22:13:29', '2026-06-02 22:18:52', NULL, 1, 2026),
(14, 11, 'Automatización de procesos empresariales (RPA).pdf', 'doc_6a1f5721b3a104.16509459.pdf', 'docentes/11/doc_6a1f5721b3a104.16509459.pdf', 'application/pdf', 2209557, 28, 4, 5, NULL, '2026-06-02 22:20:17', '2026-06-02 22:20:17', NULL, 2, 2026);

-- ------------------------------------------------------
-- Estructura de tabla para `anio_lectivos`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `anio_lectivos`;
CREATE TABLE `anio_lectivos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `anio` INT NOT NULL,
  `activo` INT NOT NULL DEFAULT '0',
  `fecha_inicio` DATE NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para la tabla `anio_lectivos`
INSERT INTO `anio_lectivos` (`id`, `anio`, `activo`, `fecha_inicio`, `created_at`, `updated_at`) VALUES
(1, 2026, '1', '2026-01-03', '2026-05-18 14:40:09', '2026-05-18 14:40:09');

SET FOREIGN_KEY_CHECKS=1;
