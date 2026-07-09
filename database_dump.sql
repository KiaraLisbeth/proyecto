-- Dump de la base de datos SQLite a SQL

CREATE TABLE "migrations" ("id" integer primary key autoincrement not null, "migration" varchar not null, "batch" integer not null);

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2024_01_01_000010_create_niveles_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2024_01_01_000011_create_grados_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('6', '2024_01_01_000012_create_secciones_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('7', '2024_01_01_000013_create_cursos_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('8', '2024_01_01_000014_create_docente_asignaciones_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('9', '2024_01_01_000015_create_archivos_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('10', '2026_05_05_000618_add_deleted_at_to_archivos_table', '2');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('11', '2026_05_15_000001_add_bimestre_to_archivos_table', '3');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('12', '2026_05_18_000001_add_anio_to_archivos_table', '4');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('13', '2026_05_18_000002_create_anio_lectivos_table', '5');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('14', '2026_05_18_183132_add_username_to_users_table', '6');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('15', '2026_05_18_202537_add_password_plain_to_users_table', '7');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('16', '2026_06_17_000001_add_dni_to_users_table', '8');

CREATE TABLE "users" ("id" integer primary key autoincrement not null, "nombre" varchar not null, "apellido" varchar not null, "email" varchar not null, "email_verified_at" datetime, "password" varchar not null, "rol" varchar check ("rol" in ('admin', 'docente')) not null default 'docente', "activo" tinyint(1) not null default '1', "remember_token" varchar, "created_at" datetime, "updated_at" datetime, "username" varchar, "password_plain" varchar, "dni" varchar);

INSERT INTO `users` (`id`, `nombre`, `apellido`, `email`, `email_verified_at`, `password`, `rol`, `activo`, `remember_token`, `created_at`, `updated_at`, `username`, `password_plain`, `dni`) VALUES ('1', 'Administrador', 'Sistema', 'admin@colegio.com', NULL, '$2y$12$jqrBGta/2KY/efquTmRgEOBaau.F.kqCO8F67iIiNpKtS9PtglMCW', 'admin', '1', NULL, '2026-05-04 02:47:14', '2026-05-19 00:06:37', 'ADMIN', 'Admin1234', NULL);
INSERT INTO `users` (`id`, `nombre`, `apellido`, `email`, `email_verified_at`, `password`, `rol`, `activo`, `remember_token`, `created_at`, `updated_at`, `username`, `password_plain`, `dni`) VALUES ('13', 'Kiara Lisbeth', 'Namuche Flores', 'KNAMUCHEFL@docente.local', NULL, '$2y$12$nEVoPfv55moKWtsHLh9WAe3AWaJsYqigUGa/NObyXraHKMB8aJ0jG', 'docente', '1', NULL, '2026-06-17 15:17:12', '2026-06-18 14:30:14', 'KNAMUCHEFL', NULL, '75186680');
INSERT INTO `users` (`id`, `nombre`, `apellido`, `email`, `email_verified_at`, `password`, `rol`, `activo`, `remember_token`, `created_at`, `updated_at`, `username`, `password_plain`, `dni`) VALUES ('14', 'MARIA DEL PILAR', 'FLORES CRUZ', 'MARIA@docente.local', NULL, '$2y$12$BlGMXR8Q92a5zjoBicFJfOPBLYIuUcLHZrVDKVmr9IcUjLO.QN8Eq', 'docente', '1', NULL, '2026-06-21 03:39:30', '2026-06-21 03:39:30', 'MARIA', NULL, '80475480');
INSERT INTO `users` (`id`, `nombre`, `apellido`, `email`, `email_verified_at`, `password`, `rol`, `activo`, `remember_token`, `created_at`, `updated_at`, `username`, `password_plain`, `dni`) VALUES ('15', 'ANA ZARAIT', 'NAMUCHE FLORES', 'ANAZARAIT@docente.local', NULL, '$2y$12$We2SAYT28VhJ6atlnUyU5ubkBGltEfdMmDrI6kqCVqA98TSuhFEye', 'docente', '1', NULL, '2026-06-24 02:42:06', '2026-06-24 02:56:47', 'ANAZARAIT', 'eyJpdiI6IkFGODNxdENJT20yZ2tySE5RNkpqOUE9PSIsInZhbHVlIjoidnA5SnNtY2RnUVRiZUJlYy9rVGpiQT09IiwibWFjIjoiZTVlZjlkOTk3ODBlMmIxY2E3Yzk1NzZjOGJlMDNmOWM4OGNlNDY0Y2NmNzk2ODE1OTBkM2VjMzVhM2NkZDg5MiIsInRhZyI6IiJ9', '75186681');

CREATE TABLE "password_reset_tokens" ("email" varchar not null, "token" varchar not null, "created_at" datetime, primary key ("email"));


CREATE TABLE "sessions" ("id" varchar not null, "user_id" integer, "ip_address" varchar, "user_agent" text, "payload" text not null, "last_activity" integer not null, primary key ("id"));

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('QdGi83Ykcjo2Zc1M2LTiLWaOaIFk8EHVROdqKyGB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiQ2ZFM1V6ZkN0V2ZkN0xPaDhVT29yS2Q1V0JkSkpGbFZEdTFJTThqMyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', '1782254055');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('3oITlI2Y99zgo7BWcN4yaS6w0kmsr0FWHCYHZ8UG', '13', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVkFueFJHZ0hpWGZlQm1zQ0x3Z2Z4N3psek1pdEFiZGNLenBOYUxGSSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kb2NlbnRlL2FyY2hpdm9zIjtzOjU6InJvdXRlIjtzOjIyOiJkb2NlbnRlLmFyY2hpdm9zLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTM7fQ==', '1782258078');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('CNsbKI4Hv4He4mUveoXjW1ZCIkJNoGqF0ogw0wjM', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQUlieFhJdXNieXlTa1d0U0FKemQ1aTVHWUxTUjIzTGc0VHhuUEtRYyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kb2NlbnRlcy8xNSI7czo1OiJyb3V0ZSI7czoxOToiYWRtaW4uZG9jZW50ZXMuc2hvdyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', '1782269808');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('ruIu0GSSeDuHKE96tYs358kkh1opmreAuvl7kWTq', '13', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid3FnQVB3MnNyUW44a3ZVVlVzdkVoNkkyQjMzOG1lMUhGdWR5aG13RiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kb2NlbnRlL2FyY2hpdm9zL3N1YmlyIjtzOjU6InJvdXRlIjtzOjIzOiJkb2NlbnRlLmFyY2hpdm9zLmNyZWF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEzO30=', '1782405076');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('BuBcyUmAbrCwIxAacrOFzl6lSjkY4ylbBr41DG3f', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoia1hjNlQ1NnpJMHR6Zmd3SmpnZzZPU01qR3Q1QWVzRkZLenVoS3ZabSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9hcmNoaXZvcyI7czo1OiJyb3V0ZSI7czoyMDoiYWRtaW4uYXJjaGl2b3MuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', '1782439144');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('ynJZAJisMLfMW6jQixD4b6qaf4gQa2zg7RRfCLgG', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSjFSNzZ2V3FVUUNYVzJHMHZqcm0yemFGd1ZpSVA2SHJlTDN2NmhaRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kb2NlbnRlcyI7czo1OiJyb3V0ZSI7czoyMDoiYWRtaW4uZG9jZW50ZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', '1782524734');

CREATE TABLE "cache" ("key" varchar not null, "value" text not null, "expiration" integer not null, primary key ("key"));

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('laravel-cache-reniec_dni_75186682', 'a:2:{s:6:"nombre";s:6:"KARINA";s:8:"apellido";s:14:"NAMUCHE FLORES";}', '2097603467');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('laravel-cache-reniec_dni_75186681', 'a:2:{s:6:"nombre";s:10:"ANA ZARAIT";s:8:"apellido";s:14:"NAMUCHE FLORES";}', '2097628687');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1782268929;', '1782268929');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', '1782268929');

CREATE TABLE "cache_locks" ("key" varchar not null, "owner" varchar not null, "expiration" integer not null, primary key ("key"));


CREATE TABLE "jobs" ("id" integer primary key autoincrement not null, "queue" varchar not null, "payload" text not null, "attempts" integer not null, "reserved_at" integer, "available_at" integer not null, "created_at" integer not null);


CREATE TABLE "job_batches" ("id" varchar not null, "name" varchar not null, "total_jobs" integer not null, "pending_jobs" integer not null, "failed_jobs" integer not null, "failed_job_ids" text not null, "options" text, "cancelled_at" integer, "created_at" integer not null, "finished_at" integer, primary key ("id"));


CREATE TABLE "failed_jobs" ("id" integer primary key autoincrement not null, "uuid" varchar not null, "connection" text not null, "queue" text not null, "payload" text not null, "exception" text not null, "failed_at" datetime not null default CURRENT_TIMESTAMP);


CREATE TABLE "niveles" ("id" integer primary key autoincrement not null, "nombre" varchar not null, "created_at" datetime, "updated_at" datetime);

INSERT INTO `niveles` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('1', 'Inicial', '2026-05-04 02:47:12', '2026-05-04 02:47:12');
INSERT INTO `niveles` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('2', 'Primaria', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `niveles` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('3', 'Secundaria', '2026-05-04 02:47:13', '2026-05-04 02:47:13');

CREATE TABLE "grados" ("id" integer primary key autoincrement not null, "nombre" varchar not null, "nivel_id" integer not null, "created_at" datetime, "updated_at" datetime, foreign key("nivel_id") references "niveles"("id") on delete cascade);

INSERT INTO `grados` (`id`, `nombre`, `nivel_id`, `created_at`, `updated_at`) VALUES ('1', '3 años', '1', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `grados` (`id`, `nombre`, `nivel_id`, `created_at`, `updated_at`) VALUES ('2', '4 años', '1', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `grados` (`id`, `nombre`, `nivel_id`, `created_at`, `updated_at`) VALUES ('3', '5 años', '1', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `grados` (`id`, `nombre`, `nivel_id`, `created_at`, `updated_at`) VALUES ('4', '1ro', '2', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `grados` (`id`, `nombre`, `nivel_id`, `created_at`, `updated_at`) VALUES ('5', '2do', '2', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `grados` (`id`, `nombre`, `nivel_id`, `created_at`, `updated_at`) VALUES ('6', '3ro', '2', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `grados` (`id`, `nombre`, `nivel_id`, `created_at`, `updated_at`) VALUES ('7', '4to', '2', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `grados` (`id`, `nombre`, `nivel_id`, `created_at`, `updated_at`) VALUES ('8', '5to', '2', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `grados` (`id`, `nombre`, `nivel_id`, `created_at`, `updated_at`) VALUES ('9', '6to', '2', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `grados` (`id`, `nombre`, `nivel_id`, `created_at`, `updated_at`) VALUES ('10', '1ro', '3', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `grados` (`id`, `nombre`, `nivel_id`, `created_at`, `updated_at`) VALUES ('11', '2do', '3', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `grados` (`id`, `nombre`, `nivel_id`, `created_at`, `updated_at`) VALUES ('12', '3ro', '3', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `grados` (`id`, `nombre`, `nivel_id`, `created_at`, `updated_at`) VALUES ('13', '4to', '3', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `grados` (`id`, `nombre`, `nivel_id`, `created_at`, `updated_at`) VALUES ('14', '5to', '3', '2026-05-04 02:47:13', '2026-05-04 02:47:13');

CREATE TABLE "secciones" ("id" integer primary key autoincrement not null, "nombre" varchar not null, "created_at" datetime, "updated_at" datetime);

INSERT INTO `secciones` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('1', 'A', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `secciones` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('2', 'B', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `secciones` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('3', 'C', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `secciones` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('4', 'D', '2026-05-04 02:47:13', '2026-05-04 02:47:13');
INSERT INTO `secciones` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('5', 'Única', '2026-05-14 16:43:24', '2026-05-14 16:43:24');

CREATE TABLE "cursos" ("id" integer primary key autoincrement not null, "nombre" varchar not null, "created_at" datetime, "updated_at" datetime);

INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('12', 'Plan Lector', '2026-05-19 21:53:57', '2026-05-19 21:53:57');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('13', 'Comunicación', '2026-05-19 21:54:06', '2026-05-19 21:54:06');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('14', 'Educación Cristiana', '2026-05-19 21:54:24', '2026-05-19 21:59:09');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('16', 'Tutoría', '2026-05-19 21:54:43', '2026-05-19 21:54:43');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('17', 'Inglés', '2026-05-19 21:54:58', '2026-05-19 21:54:58');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('18', 'Personal Social', '2026-05-19 21:55:22', '2026-05-19 21:55:22');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('19', 'Matemática', '2026-05-19 21:55:32', '2026-05-19 21:55:32');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('20', 'Razonamiento Verbal', '2026-05-19 21:57:04', '2026-05-19 21:57:04');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('24', 'Literatura', '2026-05-20 01:38:47', '2026-05-20 01:38:47');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('25', 'Computación', '2026-05-20 01:39:09', '2026-05-20 01:39:09');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('26', 'Álgebra', '2026-05-20 01:39:28', '2026-05-20 01:39:28');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('27', 'Geometría', '2026-05-20 01:39:42', '2026-05-20 01:39:42');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('29', 'Ciencias Naturales', '2026-06-18 02:46:25', '2026-06-18 02:46:25');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('30', 'Historia, Geografía y Economía', '2026-06-18 02:46:25', '2026-06-18 02:46:25');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('31', 'Arte y Cultura', '2026-06-18 02:46:25', '2026-06-18 02:46:25');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('32', 'Educación Física', '2026-06-18 02:46:25', '2026-06-18 02:46:25');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('33', 'Educación Religiosa', '2026-06-18 02:46:25', '2026-06-18 02:46:25');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('34', 'Ciencia y Tecnología', '2026-06-18 02:46:25', '2026-06-18 02:46:25');
INSERT INTO `cursos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('35', 'Razonamiento Matemático', '2026-06-18 03:14:00', '2026-06-18 03:14:00');

CREATE TABLE "docente_asignaciones" ("id" integer primary key autoincrement not null, "user_id" integer not null, "curso_id" integer not null, "grado_id" integer not null, "seccion_id" integer not null, "created_at" datetime, "updated_at" datetime, foreign key("user_id") references "users"("id") on delete cascade, foreign key("curso_id") references "cursos"("id") on delete cascade, foreign key("grado_id") references "grados"("id") on delete cascade, foreign key("seccion_id") references "secciones"("id") on delete cascade);

INSERT INTO `docente_asignaciones` (`id`, `user_id`, `curso_id`, `grado_id`, `seccion_id`, `created_at`, `updated_at`) VALUES ('108', '13', '31', '3', '5', '2026-06-18 14:30:14', '2026-06-18 14:30:14');
INSERT INTO `docente_asignaciones` (`id`, `user_id`, `curso_id`, `grado_id`, `seccion_id`, `created_at`, `updated_at`) VALUES ('109', '13', '34', '3', '5', '2026-06-18 14:30:14', '2026-06-18 14:30:14');
INSERT INTO `docente_asignaciones` (`id`, `user_id`, `curso_id`, `grado_id`, `seccion_id`, `created_at`, `updated_at`) VALUES ('110', '13', '13', '3', '5', '2026-06-18 14:30:14', '2026-06-18 14:30:14');
INSERT INTO `docente_asignaciones` (`id`, `user_id`, `curso_id`, `grado_id`, `seccion_id`, `created_at`, `updated_at`) VALUES ('111', '13', '14', '3', '5', '2026-06-18 14:30:14', '2026-06-18 14:30:14');
INSERT INTO `docente_asignaciones` (`id`, `user_id`, `curso_id`, `grado_id`, `seccion_id`, `created_at`, `updated_at`) VALUES ('112', '13', '32', '3', '5', '2026-06-18 14:30:14', '2026-06-18 14:30:14');
INSERT INTO `docente_asignaciones` (`id`, `user_id`, `curso_id`, `grado_id`, `seccion_id`, `created_at`, `updated_at`) VALUES ('113', '13', '19', '3', '5', '2026-06-18 14:30:14', '2026-06-18 14:30:14');
INSERT INTO `docente_asignaciones` (`id`, `user_id`, `curso_id`, `grado_id`, `seccion_id`, `created_at`, `updated_at`) VALUES ('114', '13', '18', '3', '5', '2026-06-18 14:30:14', '2026-06-18 14:30:14');
INSERT INTO `docente_asignaciones` (`id`, `user_id`, `curso_id`, `grado_id`, `seccion_id`, `created_at`, `updated_at`) VALUES ('115', '14', '30', '10', '5', '2026-06-21 03:39:30', '2026-06-21 03:39:30');
INSERT INTO `docente_asignaciones` (`id`, `user_id`, `curso_id`, `grado_id`, `seccion_id`, `created_at`, `updated_at`) VALUES ('116', '14', '26', '10', '5', '2026-06-21 03:39:30', '2026-06-21 03:39:30');
INSERT INTO `docente_asignaciones` (`id`, `user_id`, `curso_id`, `grado_id`, `seccion_id`, `created_at`, `updated_at`) VALUES ('117', '14', '32', '14', '5', '2026-06-21 03:39:30', '2026-06-21 03:39:30');
INSERT INTO `docente_asignaciones` (`id`, `user_id`, `curso_id`, `grado_id`, `seccion_id`, `created_at`, `updated_at`) VALUES ('118', '14', '33', '14', '5', '2026-06-21 03:39:30', '2026-06-21 03:39:30');
INSERT INTO `docente_asignaciones` (`id`, `user_id`, `curso_id`, `grado_id`, `seccion_id`, `created_at`, `updated_at`) VALUES ('122', '15', '25', '10', '5', '2026-06-24 02:56:47', '2026-06-24 02:56:47');
INSERT INTO `docente_asignaciones` (`id`, `user_id`, `curso_id`, `grado_id`, `seccion_id`, `created_at`, `updated_at`) VALUES ('123', '15', '27', '11', '5', '2026-06-24 02:56:47', '2026-06-24 02:56:47');
INSERT INTO `docente_asignaciones` (`id`, `user_id`, `curso_id`, `grado_id`, `seccion_id`, `created_at`, `updated_at`) VALUES ('124', '15', '32', '11', '5', '2026-06-24 02:56:47', '2026-06-24 02:56:47');
INSERT INTO `docente_asignaciones` (`id`, `user_id`, `curso_id`, `grado_id`, `seccion_id`, `created_at`, `updated_at`) VALUES ('125', '15', '24', '10', '5', '2026-06-24 02:56:47', '2026-06-24 02:56:47');

CREATE TABLE "archivos" ("id" integer primary key autoincrement not null, "user_id" integer not null, "nombre_original" varchar not null, "nombre_archivo" varchar not null, "ruta" varchar not null, "tipo_archivo" varchar not null, "tamanio" integer not null, "curso_id" integer not null, "grado_id" integer not null, "seccion_id" integer not null, "descripcion" text, "created_at" datetime, "updated_at" datetime, "deleted_at" datetime, "bimestre" integer not null default '1', "anio" integer not null default '2026', foreign key("user_id") references "users"("id") on delete cascade, foreign key("curso_id") references "cursos"("id") on delete cascade, foreign key("grado_id") references "grados"("id") on delete cascade, foreign key("seccion_id") references "secciones"("id") on delete cascade);

INSERT INTO `archivos` (`id`, `user_id`, `nombre_original`, `nombre_archivo`, `ruta`, `tipo_archivo`, `tamanio`, `curso_id`, `grado_id`, `seccion_id`, `descripcion`, `created_at`, `updated_at`, `deleted_at`, `bimestre`, `anio`) VALUES ('17', '13', 'Recibo_Digital_67646.pdf', 'doc_6a3b1989ad2cd5.32345244.pdf', 'docentes/13/doc_6a3b1989ad2cd5.32345244.pdf', 'application/pdf', '4303', '14', '3', '5', NULL, '2026-06-23 23:40:57', '2026-06-23 23:40:57', NULL, '2', '2026');
INSERT INTO `archivos` (`id`, `user_id`, `nombre_original`, `nombre_archivo`, `ruta`, `tipo_archivo`, `tamanio`, `curso_id`, `grado_id`, `seccion_id`, `descripcion`, `created_at`, `updated_at`, `deleted_at`, `bimestre`, `anio`) VALUES ('18', '13', 'Recibo_Digital_67646.pdf', 'doc_6a3b199d8154e4.59182699.pdf', 'docentes/13/doc_6a3b199d8154e4.59182699.pdf', 'application/pdf', '4303', '14', '3', '5', NULL, '2026-06-23 23:41:17', '2026-06-23 23:41:17', NULL, '2', '2026');

CREATE TABLE "anio_lectivos" ("id" integer primary key autoincrement not null, "anio" integer not null, "activo" tinyint(1) not null default '0', "fecha_inicio" date, "created_at" datetime, "updated_at" datetime);

INSERT INTO `anio_lectivos` (`id`, `anio`, `activo`, `fecha_inicio`, `created_at`, `updated_at`) VALUES ('1', '2026', '1', '2026-01-03', '2026-05-18 14:40:09', '2026-05-18 14:40:09');

