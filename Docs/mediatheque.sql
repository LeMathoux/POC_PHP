-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 28 sep. 2025 à 19:55
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mediatheque`
--

-- --------------------------------------------------------

--
-- Structure de la table `album`
--

DROP TABLE IF EXISTS `album`;
CREATE TABLE IF NOT EXISTS `album` (
  `id` int NOT NULL AUTO_INCREMENT,
  `track_number` int NOT NULL,
  `editor` varchar(255) NOT NULL,
  `media_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `media_id` (`media_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `album`
--

INSERT INTO `album` (`id`, `track_number`, `editor`, `media_id`) VALUES
(1, 5, 'Epic Records', 1),
(2, 5, 'Atlantic Records', 2),
(3, 5, 'Harvest Records', 3),
(4, 5, 'Apple Records', 4),
(5, 5, 'DGC Records', 5);

-- --------------------------------------------------------

--
-- Structure de la table `book`
--

DROP TABLE IF EXISTS `book`;
CREATE TABLE IF NOT EXISTS `book` (
  `id` int NOT NULL AUTO_INCREMENT,
  `page_number` int NOT NULL,
  `media_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `media_id` (`media_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `book`
--

INSERT INTO `book` (`id`, `page_number`, `media_id`) VALUES
(1, 328, 6),
(2, 96, 7),
(3, 309, 8),
(4, 1463, 9),
(5, 123, 10);

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE IF NOT EXISTS `media` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `available` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `media`
--

INSERT INTO `media` (`id`, `title`, `author`, `available`) VALUES
(1, 'Thriller', 'Michael Jackson', 1),
(2, 'Back in Black', 'AC/DC', 1),
(3, 'The Dark Side of the Moon', 'Pink Floyd', 1),
(4, 'Abbey Road', 'The Beatles', 1),
(5, 'Nevermind', 'Nirvana', 1),
(6, '1984', 'George Orwell', 1),
(7, 'Le Petit Prince', 'Antoine de Saint-Exupéry', 1),
(8, 'Harry Potter à l école des sorciers', 'J.K. Rowling', 1),
(9, 'Les Misérables', 'Victor Hugo', 1),
(10, 'L\'Étranger', 'Albert Camus', 1),
(11, 'Inception', 'Christopher Nolan', 0),
(12, 'The Matrix', 'Lana &amp; Lilly Wachowski', 1),
(13, 'Pulp Fiction', 'Quentin Tarantino', 1),
(14, 'Le Seigneur des Anneaux', 'Peter Jackson', 1),
(15, 'Avatar', 'James Cameron', 1);

-- --------------------------------------------------------

--
-- Structure de la table `movie`
--

DROP TABLE IF EXISTS `movie`;
CREATE TABLE IF NOT EXISTS `movie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `duration` int NOT NULL,
  `genre` varchar(255) NOT NULL,
  `media_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `media_id` (`media_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `movie`
--

INSERT INTO `movie` (`id`, `duration`, `genre`, `media_id`) VALUES
(1, 148, 'Aventure', 11),
(2, 138, 'Aventure', 12),
(3, 154, 'Policier', 13),
(4, 178, 'Aventure', 14),
(5, 168, 'Animation', 15);

-- --------------------------------------------------------

--
-- Structure de la table `song`
--

DROP TABLE IF EXISTS `song`;
CREATE TABLE IF NOT EXISTS `song` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `note` int NOT NULL,
  `duration` int NOT NULL,
  `album_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `album_id` (`album_id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `song`
--

INSERT INTO `song` (`id`, `title`, `note`, `duration`, `album_id`) VALUES
(1, 'Wanna Be Startin Somethin', 10, 362, 1),
(2, 'Thriller', 10, 358, 1),
(3, 'Beat It', 10, 258, 1),
(4, 'Billie Jean', 10, 294, 1),
(5, 'Human Nature', 8, 245, 1),
(6, 'Hells Bells', 9, 312, 2),
(7, 'Shoot to Thrill', 8, 327, 2),
(8, 'Back in Black', 10, 255, 2),
(9, 'You Shook Me All Night Long', 9, 210, 2),
(10, 'Rock and Roll Ain t Noise Pollution', 7, 252, 2),
(11, 'Speak to Me', 7, 90, 3),
(12, 'Time', 10, 412, 3),
(13, 'Us and Them', 9, 462, 3),
(14, 'Eclipse', 8, 123, 3),
(15, 'Money', 9, 382, 3),
(16, 'Come Together', 10, 259, 4),
(17, 'Something', 9, 183, 4),
(18, 'Here Comes the Sun', 10, 185, 4),
(19, 'Because', 8, 164, 4),
(20, 'Golden Slumbers', 9, 91, 4),
(21, 'Smells Like Teen Spirit', 10, 301, 5),
(22, 'In Bloom', 9, 255, 5),
(23, 'Come as You Are', 10, 219, 5),
(24, 'Lithium', 9, 257, 5),
(25, 'Polly', 8, 177, 5);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `created_at`, `updated_at`) VALUES
(8, 'Charles Haller', 'charleshaller60@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$WUpId0YwZi5seDBxdjc0Zw$EzQfqRR2d4l2pcLbcYEOeLJscvW6gE3O5IUUU6zHXRA', '2025-09-28', '2025-09-28');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
