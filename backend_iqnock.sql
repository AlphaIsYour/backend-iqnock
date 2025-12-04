-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql302.infinityfree.com
-- Generation Time: Dec 02, 2025 at 01:35 AM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40430236_backend_iqnock`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin') NOT NULL DEFAULT 'admin',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@gmail.com', '$2y$12$Vc0Yyaen/nL1/RUUBMIQdOZkplXSkAHZMGmQlolODk7Xpu2ySQBdO', 'super_admin', 'jhfa69EzVKVEoqGT2bfmJECyEtYFkhyJZglHYaDhS7YfRcFqDcfzaK5vGULo', '2025-10-22 20:20:43', '2025-10-22 20:20:43'),
(2, 'Admin', 'admin2@gmail.com', '$2y$12$jWmUBKTyAweUXwq.EuEF7uNG9RooUsmL5D8b6z1FmUx7r0SEZvdqG', 'admin', NULL, '2025-10-22 20:20:44', '2025-10-22 20:20:44');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'bug',
  `message` text NOT NULL,
  `status` enum('pending','reviewed','resolved') NOT NULL DEFAULT 'pending',
  `admin_reply` text DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leaderboard`
--

CREATE TABLE `leaderboard` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total_score` int(11) NOT NULL DEFAULT 0,
  `levels_completed` int(11) NOT NULL DEFAULT 0,
  `rank` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leaderboard`
--

INSERT INTO `leaderboard` (`id`, `user_id`, `total_score`, `levels_completed`, `rank`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 0, NULL, '2025-12-02 00:21:19', '2025-12-02 00:21:19');

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `level_number` int(11) NOT NULL,
  `level_name` varchar(255) NOT NULL,
  `is_premium` tinyint(1) NOT NULL DEFAULT 0,
  `coin_price` int(11) NOT NULL DEFAULT 0,
  `reward_coins` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`id`, `level_number`, `level_name`, `is_premium`, `coin_price`, `reward_coins`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, '1', 0, 0, 10, 1, '2025-12-01 20:23:28', '2025-12-01 21:06:11'),
(2, 2, '1', 0, 0, 10, 1, '2025-12-01 20:23:47', '2025-12-01 21:06:00'),
(3, 3, '1', 0, 0, 10, 1, '2025-12-01 20:23:55', '2025-12-01 21:05:51'),
(4, 4, '1', 0, 0, 10, 1, '2025-12-01 20:24:02', '2025-12-01 21:05:44'),
(5, 5, '1', 0, 0, 10, 1, '2025-12-01 20:24:07', '2025-12-01 21:05:34'),
(6, 6, '1', 0, 0, 10, 1, '2025-12-01 20:24:12', '2025-12-01 21:05:24'),
(7, 7, '1', 0, 0, 10, 1, '2025-12-01 20:24:17', '2025-12-01 21:05:16'),
(8, 8, '1', 0, 0, 10, 1, '2025-12-01 20:24:22', '2025-12-01 21:04:55'),
(9, 9, '1', 0, 0, 10, 1, '2025-12-01 20:24:30', '2025-12-01 21:04:44'),
(10, 10, '1', 0, 0, 10, 1, '2025-12-01 20:24:36', '2025-12-01 21:01:30'),
(11, 11, 'Stage 2', 1, 80, 10, 1, '2025-12-01 20:50:49', '2025-12-01 21:07:41'),
(12, 12, 'Stage 2', 0, 0, 10, 1, '2025-12-01 20:51:00', '2025-12-01 21:07:32'),
(13, 13, 'Stage 2', 0, 0, 10, 1, '2025-12-01 20:51:09', '2025-12-01 21:07:22'),
(14, 14, 'Stage 2', 0, 0, 10, 1, '2025-12-01 20:51:16', '2025-12-01 21:07:12'),
(15, 15, 'Stage 2', 0, 0, 10, 1, '2025-12-01 20:51:23', '2025-12-01 21:07:03'),
(16, 16, 'Stage 2', 0, 0, 10, 1, '2025-12-01 20:51:34', '2025-12-01 21:06:54'),
(17, 17, 'Stage 2', 0, 0, 10, 1, '2025-12-01 20:51:40', '2025-12-01 21:06:43'),
(18, 18, 'Stage 2', 0, 0, 10, 1, '2025-12-01 20:51:47', '2025-12-01 21:06:34'),
(19, 19, 'Stage 2', 0, 0, 10, 1, '2025-12-01 20:51:55', '2025-12-01 21:06:25'),
(20, 20, 'Stage 2', 0, 0, 10, 1, '2025-12-01 20:52:02', '2025-12-01 21:01:40'),
(21, 21, 'Stage 3', 1, 80, 10, 1, '2025-12-01 20:53:11', '2025-12-01 21:08:06'),
(22, 22, 'Stage 3', 0, 0, 10, 1, '2025-12-01 20:53:19', '2025-12-01 21:08:25'),
(23, 23, 'Stage 3', 0, 0, 10, 1, '2025-12-01 20:53:25', '2025-12-01 21:08:40'),
(24, 24, 'Stage 3', 0, 0, 10, 1, '2025-12-01 20:53:44', '2025-12-01 21:08:52'),
(25, 25, 'Stage 3', 0, 0, 10, 1, '2025-12-01 20:53:49', '2025-12-01 21:09:05'),
(26, 26, 'Stage 3', 0, 0, 10, 1, '2025-12-01 20:53:55', '2025-12-01 21:09:19'),
(27, 27, 'Stage 3', 0, 0, 10, 1, '2025-12-01 20:54:02', '2025-12-01 21:09:34'),
(28, 28, 'Stage 3', 0, 0, 10, 1, '2025-12-01 20:54:08', '2025-12-01 21:09:56'),
(29, 29, 'Stage 3', 0, 0, 10, 1, '2025-12-01 20:54:14', '2025-12-01 21:10:09'),
(30, 30, 'Stage 3', 0, 0, 10, 1, '2025-12-01 20:54:20', '2025-12-01 21:10:23'),
(31, 31, 'stage 4', 1, 80, 10, 1, '2025-12-01 20:54:43', '2025-12-01 21:10:36'),
(32, 32, 'stage 4', 0, 0, 10, 1, '2025-12-01 20:54:49', '2025-12-01 21:10:49'),
(33, 33, 'stage 4', 0, 0, 10, 1, '2025-12-01 20:54:54', '2025-12-01 21:11:01'),
(34, 34, 'stage 4', 0, 0, 10, 1, '2025-12-01 20:55:00', '2025-12-01 21:11:15'),
(35, 35, 'stage 4', 0, 0, 10, 1, '2025-12-01 20:55:13', '2025-12-01 21:11:30'),
(36, 36, 'stage 4', 0, 0, 10, 1, '2025-12-01 20:55:48', '2025-12-01 21:11:42'),
(37, 37, 'stage 4', 0, 0, 10, 1, '2025-12-01 20:55:55', '2025-12-01 21:11:55'),
(38, 38, 'Stage 4', 0, 0, 10, 1, '2025-12-01 20:56:08', '2025-12-01 21:12:07'),
(39, 39, 'Stage 4', 0, 0, 10, 1, '2025-12-01 20:56:19', '2025-12-01 21:12:19'),
(40, 40, 'Stage 4', 0, 0, 10, 1, '2025-12-01 20:56:29', '2025-12-01 21:12:33'),
(41, 41, 'Stage 5', 1, 80, 10, 1, '2025-12-01 23:07:46', '2025-12-01 23:09:25'),
(42, 42, 'Stage 5', 0, 0, 10, 1, '2025-12-01 23:07:54', '2025-12-01 23:09:31'),
(43, 43, 'Stage 5', 0, 0, 10, 1, '2025-12-01 23:08:00', '2025-12-01 23:09:39'),
(44, 44, 'Stage 5', 0, 0, 10, 1, '2025-12-01 23:08:06', '2025-12-01 23:09:58'),
(45, 45, 'Stage 5', 0, 0, 10, 1, '2025-12-01 23:08:12', '2025-12-01 23:10:05'),
(46, 46, 'Stage 5', 0, 0, 10, 1, '2025-12-01 23:08:18', '2025-12-01 23:10:14'),
(47, 47, 'Stage 5', 0, 0, 10, 1, '2025-12-01 23:08:26', '2025-12-01 23:10:23'),
(48, 48, 'Stage 5', 0, 0, 10, 1, '2025-12-01 23:08:41', '2025-12-01 23:10:30'),
(49, 49, 'Stage 5', 0, 0, 10, 1, '2025-12-01 23:08:48', '2025-12-01 23:10:38'),
(50, 50, 'Stage 5', 0, 0, 10, 1, '2025-12-01 23:08:54', '2025-12-01 23:10:45');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_22_085237_modify_users_table', 2),
(5, '2025_10_22_085242_create_levels_table', 2),
(6, '2025_10_22_085248_create_questions_table', 2),
(7, '2025_10_22_085252_create_user_progress_table', 2),
(8, '2025_10_22_085257_create_leaderboard_table', 2),
(9, '2025_10_22_085303_create_feedback_table', 2),
(10, '2025_10_22_085308_create_admins_table', 2),
(11, '2025_10_22_085914_create_personal_access_tokens_table', 3),
(12, '2025_10_26_025151_add_type_to_feedback_table', 4),
(13, '2025_11_24_150229_add_highest_level_to_users_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', 'a5b7f612b3ecfaf1a8cd267e301f96528a8677799b471c9e122d621e26cf2d94', '[\"*\"]', NULL, NULL, '2025-12-02 00:21:19', '2025-12-02 00:21:19');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `level_id` bigint(20) UNSIGNED NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `correct_answer` varchar(255) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 10,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `level_id`, `image_url`, `correct_answer`, `points`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, '/storage/questions/ViQNnlN5IE5KeqdEdKG0TyUMa48GJL3RnIpinymF.png', 'SAYUR MAYUR', 10, 1, '2025-12-01 20:37:03', '2025-12-01 20:37:10'),
(2, 2, '/storage/questions/V6r3qfaOFPO6B6bUWtZU0uNgm2USbDSyKtV30C9Y.png', 'BELUT LISTRIK', 10, 1, '2025-12-01 20:37:28', '2025-12-01 20:37:28'),
(3, 3, '/storage/questions/2De7fYTCvzW0MAFAdui52BpLVcdOW913R7gFKsRX.png', 'BUAH TANGAN', 10, 1, '2025-12-01 20:37:43', '2025-12-01 20:37:43'),
(4, 4, '/storage/questions/CWvepGztH0zX6LTjCUsHONuBaJPjOmMsFGcBVsqL.png', 'TANGGAL TUA', 10, 1, '2025-12-01 20:38:04', '2025-12-01 20:38:04'),
(5, 5, '/storage/questions/9DUlw4M1XOCQmFiOgMxKR6z0wqxF85QlEh93QEGR.png', 'BUKU GAMBAR', 10, 1, '2025-12-01 20:38:19', '2025-12-01 20:38:19'),
(6, 6, '/storage/questions/s1ZlKoOIrw3KhfkSHbPjdOElI5HcdQVoKTfCRTiM.png', 'JALAN KAKI', 10, 1, '2025-12-01 20:38:40', '2025-12-01 20:38:40'),
(7, 7, '/storage/questions/LlQSm3ANFr0ZnS7p2g3FEti5bIeOvbv7d4SSQjfs.png', 'KUDA LAUT', 10, 1, '2025-12-01 20:38:55', '2025-12-01 20:38:55'),
(8, 8, '/storage/questions/fFWEfQ5ChCuc2MTL4AaPAhO4aHz8Pt53YUhnSR9p.png', 'POLISI TIDUR', 10, 1, '2025-12-01 20:39:10', '2025-12-01 20:39:10'),
(9, 9, '/storage/questions/KA0NT2LyGVTh36GHihCz4VUhN6ec3k0MHb7Y2c60.png', 'NASI PADANG', 10, 1, '2025-12-01 20:39:28', '2025-12-01 20:39:28'),
(10, 10, '/storage/questions/kBFX054yx7o5ev3bxpAzxcuAj7vYKDZfOteLxWPi.png', 'KUTU BUKU', 10, 1, '2025-12-01 20:39:41', '2025-12-01 20:39:41'),
(11, 11, '/storage/questions/wE8yIWep9VDV1Zmyt6nwodujhbAbLcrJEzqPcfC0.png', 'KALIBATA', 10, 1, '2025-12-01 20:50:58', '2025-12-01 20:51:27'),
(12, 12, '/storage/questions/VOqYQCXbT2bmPTjmsJZ3F7d6aywWPjf6OUpwz7p2.png', 'PASAR MINGGU', 10, 1, '2025-12-01 20:51:51', '2025-12-01 20:51:51'),
(13, 13, '/storage/questions/wlqT6ZyngrlrsQAoVn8jXFeONtOLE3gNLHsoCp9L.png', 'CAPUNG', 10, 1, '2025-12-01 20:52:13', '2025-12-01 20:52:13'),
(14, 14, '/storage/questions/Uwjuxofqz1k7yEnYpWWWwaCy8QCG5227KQpvI9Cl.png', 'KANGGURU', 10, 1, '2025-12-01 20:52:34', '2025-12-01 20:52:34'),
(15, 15, '/storage/questions/pJtyAsiNeDneySjAi14XE3Y7iUnjBu4FcILk0ckP.png', 'PIALA', 10, 1, '2025-12-01 20:52:54', '2025-12-01 20:52:54'),
(16, 16, '/storage/questions/aK0Yv97TxPkzuz2C50flLhD847DyVGOHCx7UBNbP.png', 'BATAM', 10, 1, '2025-12-01 20:53:26', '2025-12-01 20:53:26'),
(17, 17, '/storage/questions/rf50z39FqcAJckz9o3KSikkClHrExjbNUMGN6pKj.png', 'BANTEN', 10, 1, '2025-12-01 20:53:56', '2025-12-01 20:53:56'),
(18, 18, '/storage/questions/CTi872ebx2m6uqDOOm2NdZuNNmpxAUexgYab4fMX.png', 'SINGAPURA', 10, 1, '2025-12-01 20:54:22', '2025-12-01 20:54:22'),
(19, 19, '/storage/questions/DXxsk3ibPSg20RE14hbOPuISzMug1xYAZCGOZwXV.png', 'NASI PADANG', 10, 1, '2025-12-01 20:54:44', '2025-12-01 20:54:44'),
(20, 20, '/storage/questions/9eVn9stTHNc3FZ1NH1YhWn2ErPG0ISDA5I4xH97A.png', 'RUMAH KE RUMAH', 10, 1, '2025-12-01 20:55:07', '2025-12-01 20:55:07'),
(21, 21, '/storage/questions/Yu1WgluBtYxZt7SEOO1ugEK85OAYda1AfPdbiEhh.png', 'LAMPU BELAJAR', 10, 1, '2025-12-01 20:57:03', '2025-12-01 20:57:03'),
(22, 22, '/storage/questions/cr70X8ScVEE45Jj7WQ7ABJgKnY5ct7IDirdhhpll.png', 'LAPANG DADA', 10, 1, '2025-12-01 20:57:20', '2025-12-01 20:57:20'),
(23, 23, '/storage/questions/5xX8NpAaNo2CWrZw2KlqKFYeZLpdX9o0gsUWKX7u.png', 'BANK MANDIRI', 10, 1, '2025-12-01 20:57:45', '2025-12-01 20:57:45'),
(24, 24, '/storage/questions/6r4Fz9zgE0bb4U2yalYF1H1kTw6JMcRs7EUGNjsK.png', 'PARTAI POLITIK', 10, 1, '2025-12-01 20:58:10', '2025-12-01 20:58:10'),
(25, 25, '/storage/questions/fB5PHClgoEaq8YcI4bTZYv4Z1CjKRO9wRIOz3LHg.png', 'BUNGA BANGKAI', 10, 1, '2025-12-01 20:58:43', '2025-12-01 20:58:43'),
(26, 26, '/storage/questions/0rIT56kv7cUV0pUPbZdJJiKSmmIaVTEa9FRmi8pa.png', 'KACANG MERAH', 10, 1, '2025-12-01 20:59:05', '2025-12-01 20:59:05'),
(27, 27, '/storage/questions/pW9lKIaANgnRq99tvlW8DnOiDlXJx99PoEdoDoVa.png', 'KOLAM IKAN', 10, 1, '2025-12-01 20:59:35', '2025-12-01 20:59:35'),
(28, 28, '/storage/questions/IOeJVaKifwiCCE3qZZethlEj5rvrppc4rJj3QYPG.png', 'PASAR BURUNG', 10, 1, '2025-12-01 21:00:01', '2025-12-01 21:00:01'),
(29, 29, '/storage/questions/zXPgqugWSlYUrKYl8zPPsuynIIrgwM6XrKv7uQCw.png', 'PELATIH BOLA', 10, 1, '2025-12-01 21:00:46', '2025-12-01 21:00:46'),
(30, 30, '/storage/questions/NUImo4vJ8FpnzaB4XxlCNqn7EyvUlemwf7lFcSno.png', 'LASKAR PELANGI', 10, 1, '2025-12-01 21:01:12', '2025-12-01 21:01:12'),
(31, 31, '/storage/questions/oYdgov8UDCY1OJmTG4wkzjaq9VwmQkTX4xz6iRx6.png', 'SUSU BERUANG', 10, 1, '2025-12-01 21:05:18', '2025-12-01 21:05:18'),
(32, 32, '/storage/questions/rkBUuUJdzseshU5TtdOkp8tfeti610UMKmOD4R8X.png', 'EARPHONE', 10, 1, '2025-12-01 21:05:35', '2025-12-01 21:05:35'),
(33, 33, '/storage/questions/LxucpXmMit5ICtBuAaMCrxkHmmY20TFBJQYyBIJ0.png', 'BUKU TULIS', 10, 1, '2025-12-01 21:07:24', '2025-12-01 21:07:24'),
(34, 34, '/storage/questions/7WEV9Y4bhoO2T7GOT435b0OdfLZJrAPOIoPOY6x8.png', 'BELUT LISTRIK', 10, 1, '2025-12-01 21:07:44', '2025-12-01 21:07:44'),
(35, 35, '/storage/questions/e0K8c3rv1JmAOFnzOZjWFouyXyJeMAl13edpWheg.png', 'KANTONG BELANJA', 10, 1, '2025-12-01 21:08:05', '2025-12-01 21:08:05'),
(36, 36, '/storage/questions/eeSMok3JK74zkcUVgCd2RkWWq4EnGQLPl9rQtX0w.png', 'IBUKOTA', 10, 1, '2025-12-01 21:08:31', '2025-12-01 21:08:31'),
(37, 37, '/storage/questions/HIat2s18o1QeF5m3m9Dil07jIRCFZqGEgqmRyDLl.png', 'TISU WAJAH', 10, 1, '2025-12-01 21:08:56', '2025-12-01 21:08:56'),
(38, 38, '/storage/questions/F09We1MfXNmYxWXGI8vPuOYcF4JdeDRBWDVb4dmV.png', 'KABEL ROLL', 10, 1, '2025-12-01 21:09:15', '2025-12-01 21:09:15'),
(39, 39, '/storage/questions/A6oi9ejCUVn1CxKKkJLMU7gDveaatBmdrmaV29Ee.png', 'KOPERASI SEKOLAH', 10, 1, '2025-12-01 21:09:44', '2025-12-01 21:09:44'),
(40, 40, '/storage/questions/umRhslSH02anuIKJokLw8dCDLoIHDanMixPjS1Zw.png', 'HATI HATI DI JALAN', 10, 1, '2025-12-01 21:10:04', '2025-12-01 21:10:04'),
(41, 41, '/storage/questions/IvGBdWjY8COrjIPvw6VbWMsg21u2ShzTQue05TBe.png', 'BUAYA DARAT', 10, 1, '2025-12-01 23:17:10', '2025-12-01 23:17:10'),
(42, 42, '/storage/questions/B7ymaX9tFBHucw1upuoicl699f7JNJyEVZysSCkY.png', 'JAMBU BIJI', 10, 1, '2025-12-01 23:17:35', '2025-12-01 23:17:35'),
(43, 43, '/storage/questions/VLoOlkWLOc2K9tmdVdrBRjEjCsud7qv1f7EjNzHB.png', 'JATUH CINTA', 10, 1, '2025-12-01 23:18:00', '2025-12-01 23:18:00'),
(44, 44, '/storage/questions/kJtv6z7IG1moPC0xp44HNZ7KNtG8b4g8dvbiAyOV.png', 'KAPAL SELAM', 10, 1, '2025-12-01 23:18:23', '2025-12-01 23:18:23'),
(45, 45, '/storage/questions/fn6hsDgfsSf2P16DjZoeJGc6tblZW6iAApA2u6Rw.png', 'KERJA KERAS', 10, 1, '2025-12-01 23:18:46', '2025-12-01 23:18:46'),
(46, 46, '/storage/questions/nC4MRWs4gR2vr6kl1rvs1sPBaBTBFZuBqpDxOZ1D.png', 'KUNCI JAWABAN', 10, 1, '2025-12-01 23:19:05', '2025-12-01 23:19:05'),
(47, 47, '/storage/questions/6cgCmgAC5b01hQ9CrESAVvDZEjDPpuucja5kzyYk.png', 'OBAT NYAMUK', 10, 1, '2025-12-01 23:19:24', '2025-12-01 23:19:24'),
(48, 48, '/storage/questions/Xi338QPIwjQt7DR3HsVz7QOHtgrsHEADVE7vIkBv.png', 'MATA PELAJARAN', 10, 1, '2025-12-01 23:19:42', '2025-12-01 23:19:42'),
(49, 49, '/storage/questions/DbvxbrTjgcaT1gzPzTJX64Zon69tBDEEIY5E3hTy.png', 'KUPING GAJAH', 10, 1, '2025-12-01 23:19:57', '2025-12-01 23:19:57'),
(50, 50, '/storage/questions/RzKovUFPr3rBtxV7VHSRu9BBNMk7G9hQV3UsIkUM.png', 'BUAH BIBIR', 10, 1, '2025-12-01 23:20:10', '2025-12-01 23:20:10');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Mi9BEc5truYc0gTIAXqrysl9VYFgTnjNeOP1KsqT', NULL, '182.253.50.57', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOGtxc200ODlodTczelQ1Sk84QXRxVnlCREFNSEk2QlpaWXM4SnVoYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODc6Imh0dHBzOi8vaXFub2NrLmN0LndzL215LXN0b3JhZ2UvL3F1ZXN0aW9ucy9vWWRnb3Y4VURDWTFPSm1URzR3a3pqYXE5VndtUWtUWDR4ejZpUng2LnBuZyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1764650903),
('nrtGMn3hKPoacLbeQN90bSPZnVUXuYoMrvJxyKWo', 1, '118.99.84.18', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQWVDTG5lUjFsSGl0dGZaTFg3ZkFXa0R6THAxdzJhSnBaOTBlMFUzVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vaXFub2NrLmN0LndzL2FkbWluL3VzZXJzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1764647114);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `coins` int(11) NOT NULL DEFAULT 0,
  `hearts` int(11) NOT NULL DEFAULT 5,
  `hints` int(11) NOT NULL DEFAULT 5,
  `current_level` int(11) NOT NULL DEFAULT 1,
  `highest_level` int(11) NOT NULL DEFAULT 1,
  `total_score` int(11) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `coins`, `hearts`, `hints`, `current_level`, `highest_level`, `total_score`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ilham God', 'ilham@gmail.com', NULL, '$2y$12$fmCnjbeWDP47b2hOSghL7e0Ah9pf7iqnp427GTGQjRL9PrIxNDDMy', 0, 5, 5, 1, 1, 0, NULL, '2025-12-02 00:21:19', '2025-12-02 11:45:14');

-- --------------------------------------------------------

--
-- Table structure for table `user_progress`
--

CREATE TABLE `user_progress` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `level_id` bigint(20) UNSIGNED NOT NULL,
  `is_unlocked` tinyint(1) NOT NULL DEFAULT 0,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_progress`
--

INSERT INTO `user_progress` (`id`, `user_id`, `level_id`, `is_unlocked`, `is_completed`, `attempts`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 0, 0, NULL, '2025-12-02 00:21:19', '2025-12-02 00:21:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_user_id_foreign` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leaderboard_user_id_unique` (`user_id`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `levels_level_number_unique` (`level_number`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_level_id_foreign` (`level_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_progress_user_id_level_id_unique` (`user_id`,`level_id`),
  ADD KEY `user_progress_level_id_foreign` (`level_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leaderboard`
--
ALTER TABLE `leaderboard`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_progress`
--
ALTER TABLE `user_progress`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD CONSTRAINT `leaderboard_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD CONSTRAINT `user_progress_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
