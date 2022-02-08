-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2021 at 04:48 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gdplyr`
--

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE `ads` (
  `id` int(5) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` varchar(25) DEFAULT NULL,
  `code` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ads`
--

INSERT INTO `ads` (`id`, `title`, `type`, `code`) VALUES
(20, 'popad', 'popad', '');

-- --------------------------------------------------------

--
-- Table structure for table `drive_auth`
--

CREATE TABLE `drive_auth` (
  `id` int(11) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `client_secret` varchar(255) NOT NULL,
  `refresh_token` varchar(255) NOT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `status` tinyint(4) DEFAULT 0 COMMENT '0 = active, 1 = failed',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `drive_auth`
--

INSERT INTO `drive_auth` (`id`, `client_id`, `client_secret`, `refresh_token`, `access_token`, `email`, `status`, `updated_at`, `created_at`) VALUES
(5, '173523965030-crq1koatnpg1kipdqd6o17am0mgtf51u.apps.googleusercontent.com', 'lAp9HKn0JgACFuqd0YBNgpu4', '1//04Zqel0J2w3yhCgYIARAAGAQSNwF-L9Irqv9VEGqnY1AP390ZjpICBOQhkeRQ4yaNpLdnZh6xoYJwnE18fv_7CkA5sg1DO_i0B-g', '{\"access_token\":\"ya29.a0AfH6SMAUiPPnN_B210ON6YzfqLj2VMAfjEXDmbV02Bpnmv8LZsy05sQqwRz9TgzYiTw0p_CAmFdikHuNJ6M_7fVf4PAEGxGrl_o0x_UHfn8rOf6-wwINpt1fwGcVk0TSQ8NMSSLdnlKloUGw2UZou6y_7kHcRi2SlaYpdskYBQOh\",\"token_type\":\"Bearer\"}', 'demonlord@tekniksipil.info', 0, '2021-01-18 03:46:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `main_link` varchar(255) NOT NULL,
  `alt_link` varchar(255) DEFAULT NULL,
  `preview_img` varchar(255) DEFAULT NULL,
  `data` text DEFAULT NULL,
  `type` varchar(50) DEFAULT 'direct',
  `subtitles` text DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `downloads` int(25) DEFAULT 0,
  `is_alt` tinyint(4) DEFAULT 0,
  `slug` varchar(255) NOT NULL,
  `status` tinyint(4) DEFAULT 0 COMMENT '0 = active,\r\n1 = inactive,\r\n2 = broken',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `servers`
--

CREATE TABLE `servers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `domain` varchar(255) NOT NULL,
  `playbacks` int(11) DEFAULT 0,
  `is_broken` tinyint(4) DEFAULT 0,
  `status` int(11) DEFAULT 1 COMMENT '0 = active,\r\n1 = inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `config` varchar(50) NOT NULL,
  `var` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`config`, `var`) VALUES
('version', '2.2'),
('proxyUser', ''),
('proxyPass', ''),
('timezone', 'Asia/Colombo'),
('dark_theme', '1'),
('adminId', '1'),
('sublist', '[\"sinhala\",\"english\",\"hindi\",\"french\"]'),
('logo', 'gdplyr-logo.png'),
('favicon', 'favicon.ico'),
('player', 'jw'),
('playerSlug', 'v'),
('showServers', '1'),
('adminId', '29'),
('default_video', 'https://cdn1.kccmacs.lk/files/videos/no-content.mp4'),
('default_banner', NULL),
('last_backup', '2021-01-17 18:53:08'),
('jw_license', 'https://content.jwplatform.com/libraries/Jq6HIbgz.js'),
('isAdblocker', '1'),
('v_preloader', '1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `role` varchar(100) NOT NULL,
  `status` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `img`, `role`, `status`) VALUES
(29, 'admin', '$2y$10$zh4Jfuol7MOelfOWwoOUtu.3D/vfr1ROZdonfcblW2Sl7pC3.Gd0m', 'profile-img-codyseller.jpg', 'admin', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drive_auth`
--
ALTER TABLE `drive_auth`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `servers`
--
ALTER TABLE `servers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ads`
--
ALTER TABLE `ads`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `drive_auth`
--
ALTER TABLE `drive_auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `servers`
--
ALTER TABLE `servers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
