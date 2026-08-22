-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2026 at 09:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `a`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_achievement`
--

CREATE TABLE `tb_achievement` (
  `id_achievement` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `objective` text NOT NULL,
  `fk_id_item_category` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `achievement_icon` mediumblob NOT NULL,
  `mime_type_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_box_reward`
--

CREATE TABLE `tb_box_reward` (
  `id_box_reward` int(11) NOT NULL,
  `fk_id_item_category` int(11) NOT NULL,
  `quantity_min` int(11),
  `quantity_max` int(11),
  `weight_chance` int(11) NOT NULL,
  `fk_id_box` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_box_type`
--

CREATE TABLE `tb_box_type` (
  `id_box_type` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `box_icon` mediumblob NOT NULL,
  `mime_type_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_difficulty`
--

CREATE TABLE `tb_difficulty` (
  `id_difficulty` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_enemy_type`
--

CREATE TABLE `tb_enemy_type` (
  `id_enemy_type` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_game_mode`
--

CREATE TABLE `tb_game_mode` (
  `id_game_mode` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_game_version`
--

CREATE TABLE `tb_game_version` (
  `id_game_version` int(11) NOT NULL,
  `version_code` varchar(255) NOT NULL,
  `version_log` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_item_poll`
--

CREATE TABLE `tb_item_poll` (
  `id_item_poll` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `concept_art` mediumblob NOT NULL,
  `mime_type_image` varchar(255) NOT NULL,
  `fk_id_poll` int(11) NOT NULL,
  `fk_id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_item_category`
--

CREATE TABLE `tb_item_category` (
  `id_item_category` int(11) NOT NULL,
  `item_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_modifier`
--

CREATE TABLE `tb_modifier` (
  `id_modifier` int(11) NOT NULL,
  `modifier_description` varchar(255) NOT NULL,
  `sprite_modifier` mediumblob NOT NULL,
  `mime_type_sprite` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_objective`
--

CREATE TABLE `tb_objective` (
  `id_objective` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_paddle`
--

CREATE TABLE `tb_paddle` (
  `id_paddle` int(11) NOT NULL,
  `description1` text NOT NULL,
  `description2` text NOT NULL,
  `description3` text NOT NULL,
  `description4` text NOT NULL,
  `description5` text NOT NULL,
  `name` varchar(100) NOT NULL,
  `fk_unlockable_in_territory` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_paddle_skin`
--

CREATE TABLE `tb_paddle_skin` (
  `id_paddle_skin` int(11) NOT NULL,
  `fk_id_paddle` int(11) NOT NULL,
  `fk_id_skin` int(11) NOT NULL,
  `sprite_paddle_skin` mediumblob NOT NULL,
  `mime_type_sprite` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_particle`
--

CREATE TABLE `tb_particle` (
  `id_particle` int(11) NOT NULL,
  `sprite_particle` mediumblob NOT NULL,
  `mime_type_sprite` varchar(255) NOT NULL,
  `name` varchar(67) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_poll`
--

CREATE TABLE `tb_poll` (
  `id_poll` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `finish_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

--
-- Table structure for table `tb_skin`
--

CREATE TABLE `tb_skin` (
  `id_skin` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_stage`
--

CREATE TABLE `tb_stage` (
  `id_stage` int(11) NOT NULL,
  `fk_id_paddle` int(11) NOT NULL,
  `fk_id_ult` int(11) NULL,
  `fk_id_particle` int(11) NULL,
  `fk_id_skin` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `fk_modifier1` int(11),
  `fk_modifier2` int(11),
  `fk_modifier3` int(11),
  `fk_difficulty` int(11) NOT NULL,
  `reward` varchar(255) NOT NULL,
  `reward_quantity` int(11) NOT NULL,
  `fk_objective` int(11) NOT NULL,
  `objective_quantity` int(11) NOT NULL,
  `paddle_stage` int(11) NOT NULL,
  `fk_enemy_type` int(11) NOT NULL,
  `fk_id_territory` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_territory`
--

CREATE TABLE `tb_territory` (
  `id_territory` int(11) NOT NULL,
  `name` varchar(67) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_ultimate`
--

CREATE TABLE `tb_ultimate` (
  `id_ultimate` int(11) NOT NULL,
  `description` text NOT NULL,
  `ultimate_sprite` mediumblob NOT NULL,
  `mime_type_sprite` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `fk_unlockable_in_territory` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `fk_id_item_poll` int(11) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_achievement`
--
ALTER TABLE `tb_achievement`
  ADD PRIMARY KEY (`id_achievement`),
  ADD KEY `fk_id_item_category` (`fk_id_item_category`);

--
-- Indexes for table `tb_box_reward`
--
ALTER TABLE `tb_box_reward`
  ADD PRIMARY KEY (`id_box_reward`),
  ADD KEY `fk_id_item_category` (`fk_id_item_category`);

--
-- Indexes for table `tb_box_type`
--
ALTER TABLE `tb_box_type`
  ADD PRIMARY KEY (`id_box_type`);

--
-- Indexes for table `tb_difficulty`
--
ALTER TABLE `tb_difficulty`
  ADD PRIMARY KEY (`id_difficulty`);

--
-- Indexes for table `tb_enemy_type`
--
ALTER TABLE `tb_enemy_type`
  ADD PRIMARY KEY (`id_enemy_type`);

--
-- Indexes for table `tb_game_mode`
--
ALTER TABLE `tb_game_mode`
  ADD PRIMARY KEY (`id_game_mode`);

--
-- Indexes for table `tb_game_version`
--
ALTER TABLE `tb_game_version`
  ADD PRIMARY KEY (`id_game_version`);

--
-- Indexes for table `tb_item_poll`
--
ALTER TABLE `tb_item_poll`
  ADD PRIMARY KEY (`id_item_poll`);

--
-- Indexes for table `tb_item_category`
--
ALTER TABLE `tb_item_category`
  ADD PRIMARY KEY (`id_item_category`);

--
-- Indexes for table `tb_modifier`
--
ALTER TABLE `tb_modifier`
  ADD PRIMARY KEY (`id_modifier`);

--
-- Indexes for table `tb_objective`
--
ALTER TABLE `tb_objective`
  ADD PRIMARY KEY (`id_objective`);

--
-- Indexes for table `tb_paddle`
--
ALTER TABLE `tb_paddle`
  ADD PRIMARY KEY (`id_paddle`),
  ADD KEY `fk_unlockable_in_territory` (`fk_unlockable_in_territory`);

--
-- Indexes for table `tb_paddle_skin`
--
ALTER TABLE `tb_paddle_skin`
  ADD PRIMARY KEY (`id_paddle_skin`);

--
-- Indexes for table `tb_particle`
--
ALTER TABLE `tb_particle`
  ADD PRIMARY KEY (`id_particle`);

--
-- Indexes for table `tb_poll`
--
ALTER TABLE `tb_poll`
  ADD PRIMARY KEY (`id_poll`);
--
-- Indexes for table `tb_skin`
--
ALTER TABLE `tb_skin`
  ADD PRIMARY KEY (`id_skin`);

--
-- Indexes for table `tb_stage`
--
ALTER TABLE `tb_stage`
  ADD PRIMARY KEY (`id_stage`),
  ADD KEY `fk_id_paddle` (`fk_id_paddle`),
  ADD KEY `fk_id_ult` (`fk_id_ult`),
  ADD KEY `fk_id_particle` (`fk_id_particle`),
  ADD KEY `fk_id_skin` (`fk_id_skin`),
  ADD KEY `fk_modifier1` (`fk_modifier1`),
  ADD KEY `fk_modifier2` (`fk_modifier2`),
  ADD KEY `fk_modifier3` (`fk_modifier3`),
  ADD KEY `fk_difficulty` (`fk_difficulty`),
  ADD KEY `fk_objective` (`fk_objective`),
  ADD KEY `fk_enemy_type` (`fk_enemy_type`),
  ADD KEY `fk_id_territory` (`fk_id_territory`);

--
-- Indexes for table `tb_territory`
--
ALTER TABLE `tb_territory`
  ADD PRIMARY KEY (`id_territory`);

--
-- Indexes for table `tb_ultimate`
--
ALTER TABLE `tb_ultimate`
  ADD PRIMARY KEY (`id_ultimate`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_achievement`
--
ALTER TABLE `tb_achievement`
  MODIFY `id_achievement` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_box_reward`
--
ALTER TABLE `tb_box_reward`
  MODIFY `id_box_reward` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_box_type`
--
ALTER TABLE `tb_box_type`
  MODIFY `id_box_type` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_difficulty`
--
ALTER TABLE `tb_difficulty`
  MODIFY `id_difficulty` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_enemy_type`
--
ALTER TABLE `tb_enemy_type`
  MODIFY `id_enemy_type` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_game_mode`
--
ALTER TABLE `tb_game_mode`
  MODIFY `id_game_mode` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_game_version`
--
ALTER TABLE `tb_game_version`
  MODIFY `id_game_version` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_item_poll`
--
ALTER TABLE `tb_item_poll`
  MODIFY `id_item_poll` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_item_category`
--
ALTER TABLE `tb_item_category`
  MODIFY `id_item_category` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_modifier`
--
ALTER TABLE `tb_modifier`
  MODIFY `id_modifier` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_objective`
--
ALTER TABLE `tb_objective`
  MODIFY `id_objective` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_paddle`
--
ALTER TABLE `tb_paddle`
  MODIFY `id_paddle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_paddle_skin`
--
ALTER TABLE `tb_paddle_skin`
  MODIFY `id_paddle_skin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_particle`
--
ALTER TABLE `tb_particle`
  MODIFY `id_particle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_poll`
--
ALTER TABLE `tb_poll`
  MODIFY `id_poll` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_skin`
--
ALTER TABLE `tb_skin`
  MODIFY `id_skin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_stage`
--
ALTER TABLE `tb_stage`
  MODIFY `id_stage` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_territory`
--
ALTER TABLE `tb_territory`
  MODIFY `id_territory` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_ultimate`
--
ALTER TABLE `tb_ultimate`
  MODIFY `id_ultimate` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_achievement`
--
ALTER TABLE `tb_achievement`
  ADD CONSTRAINT `fk_achievement_item_category` FOREIGN KEY (`fk_id_item_category`) REFERENCES `tb_item_category` (`id_item_category`);

--
-- Constraints for table `tb_box_reward`
--
ALTER TABLE `tb_box_reward`
  ADD CONSTRAINT `fk_box_reward_item_category` FOREIGN KEY (`fk_id_item_category`) REFERENCES `tb_item_category` (`id_item_category`);

--
-- Constraints for table `tb_paddle`
--
ALTER TABLE `tb_paddle`
  ADD CONSTRAINT `fk_unlockable_in_territory` FOREIGN KEY (`fk_unlockable_in_territory`) REFERENCES `tb_paddle` (`id_paddle`);

--
-- Constraints for table `tb_stage`
--
ALTER TABLE `tb_stage`
  ADD CONSTRAINT `tb_stage_ibfk_1` FOREIGN KEY (`fk_id_paddle`) REFERENCES `tb_paddle` (`id_paddle`),
  ADD CONSTRAINT `tb_stage_ibfk_10` FOREIGN KEY (`fk_objective`) REFERENCES `tb_objective` (`id_objective`),
  ADD CONSTRAINT `tb_stage_ibfk_11` FOREIGN KEY (`fk_enemy_type`) REFERENCES `tb_enemy_type` (`id_enemy_type`),
  ADD CONSTRAINT `tb_stage_ibfk_12` FOREIGN KEY (`fk_id_territory`) REFERENCES `tb_territory` (`id_territory`),
  ADD CONSTRAINT `tb_stage_ibfk_2` FOREIGN KEY (`fk_id_ult`) REFERENCES `tb_ultimate` (`id_ultimate`),
  ADD CONSTRAINT `tb_stage_ibfk_3` FOREIGN KEY (`fk_id_particle`) REFERENCES `tb_particle` (`id_particle`),
  ADD CONSTRAINT `tb_stage_ibfk_4` FOREIGN KEY (`fk_id_skin`) REFERENCES `tb_skin` (`id_skin`),
  ADD CONSTRAINT `tb_stage_ibfk_5` FOREIGN KEY (`fk_modifier1`) REFERENCES `tb_modifier` (`id_modifier`),
  ADD CONSTRAINT `tb_stage_ibfk_6` FOREIGN KEY (`fk_modifier2`) REFERENCES `tb_modifier` (`id_modifier`),
  ADD CONSTRAINT `tb_stage_ibfk_7` FOREIGN KEY (`fk_modifier3`) REFERENCES `tb_modifier` (`id_modifier`),
  ADD CONSTRAINT `tb_stage_ibfk_8` FOREIGN KEY (`fk_difficulty`) REFERENCES `tb_difficulty` (`id_difficulty`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


