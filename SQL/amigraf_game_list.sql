-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 29 avr. 2022 à 11:03
-- Version du serveur : 5.7.36
-- Version de PHP : 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `amigraf_game_list`
--

-- --------------------------------------------------------

--
-- Structure de la table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE IF NOT EXISTS `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_UNIQUE` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `company`
--

INSERT INTO `company` (`id`, `name`, `slug`, `logo`) VALUES
(1, 'Ubisoft', 'ubisoft', NULL),
(2, 'Epic Games', 'epic-games', NULL),
(3, 'Electronic Arts', 'electronic-arts', NULL),
(4, 'Mediatonic', 'mediatonic', NULL),
(5, 'Hazelight Studios', 'hazelight-studios', NULL),
(6, 'Respawn Entertainment', 'respawn-entertainment', NULL),
(7, 'Pine Studio', 'pine-studio', NULL),
(8, 'LucasArts', 'lucas-arts', NULL),
(9, 'Raven Software', 'raven software', NULL),
(10, 'Vicarious Visions', 'vicarious-vsions', NULL),
(11, 'BioWare', 'bioware', NULL),
(12, 'Valve', 'valve', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `developer`
--

DROP TABLE IF EXISTS `developer`;
CREATE TABLE IF NOT EXISTS `developer` (
  `game_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  PRIMARY KEY (`game_id`,`company_id`),
  KEY `fk_game_has_company_company1_idx` (`company_id`),
  KEY `fk_game_has_company_game1_idx` (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `developer`
--

INSERT INTO `developer` (`game_id`, `company_id`) VALUES
(1, 4),
(2, 5),
(3, 6),
(4, 7),
(5, 9),
(5, 10),
(6, 11),
(8, 12);

-- --------------------------------------------------------

--
-- Structure de la table `game`
--

DROP TABLE IF EXISTS `game`;
CREATE TABLE IF NOT EXISTS `game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(80) NOT NULL,
  `slug` varchar(90) NOT NULL,
  `released_at` date DEFAULT NULL,
  `description` text,
  `poster` varchar(255) DEFAULT NULL,
  `main_id` int(11) DEFAULT NULL,
  `editor_id` int(11) DEFAULT NULL,
  `licence_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_UNIQUE` (`slug`),
  KEY `fk_game_game_idx` (`main_id`),
  KEY `fk_game_company1_idx` (`editor_id`),
  KEY `fk_game_licence1_idx` (`licence_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `game`
--

INSERT INTO `game` (`id`, `title`, `slug`, `released_at`, `description`, `poster`, `main_id`, `editor_id`, `licence_id`) VALUES
(1, 'Fall Guys', 'fall-guys', '2020-08-04', NULL, NULL, NULL, 2, NULL),
(2, 'It Takes Two', 'it-takes-two', '2021-03-26', NULL, NULL, NULL, 3, NULL),
(3, 'Star Wars Jedi: Fallen Order', 'star-wars-jedi-fallen-order', '2019-11-15', NULL, NULL, NULL, 3, 1),
(4, 'Escape Simulator', 'escape-simulator', '2021-10-19', NULL, NULL, NULL, NULL, NULL),
(5, 'Star Wars Jedi Knight: Jedi Academy', 'star-wars-jedi-knight-jedi-academy', '2003-09-13', NULL, NULL, NULL, 8, 1),
(6, 'Mass Effect', 'mass-effect', '2008-05-28', NULL, NULL, NULL, 11, 3),
(7, 'Star Citizen', 'star-citizen', NULL, 'Star Citizen est un jeu vidéo massivement multijoueur de simulation spatiale du type science-fiction, en cours de développement, édité et développé par Cloud Imperium Games (CIG). Le jeu est en développement ouvert, c\'est-à-dire que les joueurs peuvent y jouer dans sa phase alpha de', NULL, NULL, NULL, NULL),
(8, 'Counter-Strike : Global Offensive', 'counter-strike-global-offensive', '2012-08-21', NULL, NULL, NULL, 12, 8);

-- --------------------------------------------------------

--
-- Structure de la table `game_genre`
--

DROP TABLE IF EXISTS `game_genre`;
CREATE TABLE IF NOT EXISTS `game_genre` (
  `game_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL,
  PRIMARY KEY (`game_id`,`genre_id`),
  KEY `fk_game_has_genre_genre1_idx` (`genre_id`),
  KEY `fk_game_has_genre_game1_idx` (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `game_genre`
--

INSERT INTO `game_genre` (`game_id`, `genre_id`) VALUES
(2, 1),
(3, 1),
(2, 2),
(3, 3),
(5, 3),
(6, 3),
(8, 3),
(1, 4),
(2, 4),
(4, 5),
(5, 6),
(8, 6),
(6, 7);

-- --------------------------------------------------------

--
-- Structure de la table `game_platform`
--

DROP TABLE IF EXISTS `game_platform`;
CREATE TABLE IF NOT EXISTS `game_platform` (
  `game_id` int(11) NOT NULL,
  `platform_id` int(11) NOT NULL,
  PRIMARY KEY (`game_id`,`platform_id`),
  KEY `fk_game_has_platform_platform1_idx` (`platform_id`),
  KEY `fk_game_has_platform_game1_idx` (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `game_platform`
--

INSERT INTO `game_platform` (`game_id`, `platform_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(8, 1),
(2, 2),
(2, 3),
(1, 4),
(5, 4),
(1, 5),
(8, 6),
(8, 7);

-- --------------------------------------------------------

--
-- Structure de la table `game_tag`
--

DROP TABLE IF EXISTS `game_tag`;
CREATE TABLE IF NOT EXISTS `game_tag` (
  `tag_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  PRIMARY KEY (`tag_id`,`game_id`),
  KEY `fk_tag_has_game_game1_idx` (`game_id`),
  KEY `fk_tag_has_game_tag1_idx` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `game_tag`
--

INSERT INTO `game_tag` (`tag_id`, `game_id`) VALUES
(1, 1),
(2, 3),
(1, 5),
(2, 5),
(1, 8);

-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

DROP TABLE IF EXISTS `genre`;
CREATE TABLE IF NOT EXISTS `genre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `slug` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_UNIQUE` (`slug`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `genre`
--

INSERT INTO `genre` (`id`, `name`, `slug`) VALUES
(1, 'Aventure', 'aventure'),
(2, 'Plateforme', 'plateforme'),
(3, 'Action', 'action'),
(4, 'Adresse', 'adresse'),
(5, 'Puzzle', 'puzzle'),
(6, 'FPS', 'fps'),
(7, 'RPG', 'rpg');

-- --------------------------------------------------------

--
-- Structure de la table `library`
--

DROP TABLE IF EXISTS `library`;
CREATE TABLE IF NOT EXISTS `library` (
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`game_id`),
  KEY `fk_user_has_game_game1_idx` (`game_id`),
  KEY `fk_user_has_game_user1_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `licence`
--

DROP TABLE IF EXISTS `licence`;
CREATE TABLE IF NOT EXISTS `licence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `slug` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_UNIQUE` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `licence`
--

INSERT INTO `licence` (`id`, `name`, `slug`) VALUES
(1, 'Star Wars', 'star-wars'),
(2, 'Harry Potter', 'harry-potter'),
(3, 'Mass Effect', 'mass-effect'),
(4, 'Call of Duty', 'call-of-duty'),
(5, 'Pokemon', 'pokemon'),
(6, 'Zelda', 'zelda'),
(7, 'Final Fantasy', 'final-fantasy'),
(8, 'Counter-Strike', 'counter-strike');

-- --------------------------------------------------------

--
-- Structure de la table `platform`
--

DROP TABLE IF EXISTS `platform`;
CREATE TABLE IF NOT EXISTS `platform` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `slug` varchar(40) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `platform`
--

INSERT INTO `platform` (`id`, `name`, `slug`, `icon`) VALUES
(1, 'PC', 'pc', 'fa-solid fa-computer'),
(2, 'Playstation 5', 'ps5', 'fa-brands fa-playstation'),
(3, 'Xbox Series', 'xbox-series', 'fa-brands fa-xbox'),
(4, 'Nintendo Switch', 'switch', 'fa-solid fa-gamepad'),
(5, 'Gameboy Color', 'gameboy-color', 'fa-solid fa-ghost'),
(6, 'Playstation 3', 'ps3', 'fa-brands fa-playstation'),
(7, 'Xbox 360', 'xbox-360', 'fa-brands fa-xbox');

-- --------------------------------------------------------

--
-- Structure de la table `profile`
--

DROP TABLE IF EXISTS `profile`;
CREATE TABLE IF NOT EXISTS `profile` (
  `user_id` int(11) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `favorite_game_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_profile_user1_idx` (`user_id`),
  KEY `fk_profile_game1_idx` (`favorite_game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `review`
--

DROP TABLE IF EXISTS `review`;
CREATE TABLE IF NOT EXISTS `review` (
  `game_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_recommanded` tinyint(1) NOT NULL,
  `comment` text,
  PRIMARY KEY (`game_id`,`user_id`),
  KEY `fk_game_has_user_user1_idx` (`user_id`),
  KEY `fk_game_has_user_game1_idx` (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

DROP TABLE IF EXISTS `tag`;
CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `slug` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_UNIQUE` (`slug`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tag`
--

INSERT INTO `tag` (`id`, `name`, `slug`) VALUES
(1, 'Multijoueur', 'multiplayer'),
(2, 'Solo', 'solo'),
(3, 'Ecran partagé', 'split-screen');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `roles` json NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `developer`
--
ALTER TABLE `developer`
  ADD CONSTRAINT `fk_game_has_company_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_game_has_company_game1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `game`
--
ALTER TABLE `game`
  ADD CONSTRAINT `fk_game_company1` FOREIGN KEY (`editor_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_game_game` FOREIGN KEY (`main_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_game_licence1` FOREIGN KEY (`licence_id`) REFERENCES `licence` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `game_genre`
--
ALTER TABLE `game_genre`
  ADD CONSTRAINT `fk_game_has_genre_game1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_game_has_genre_genre1` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `game_platform`
--
ALTER TABLE `game_platform`
  ADD CONSTRAINT `fk_game_has_platform_game1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_game_has_platform_platform1` FOREIGN KEY (`platform_id`) REFERENCES `platform` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `game_tag`
--
ALTER TABLE `game_tag`
  ADD CONSTRAINT `fk_tag_has_game_game1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tag_has_game_tag1` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `library`
--
ALTER TABLE `library`
  ADD CONSTRAINT `fk_user_has_game_game1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_has_game_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `fk_profile_game1` FOREIGN KEY (`favorite_game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_profile_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_game_has_user_game1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_game_has_user_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
