-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour cinéma
CREATE DATABASE IF NOT EXISTS `cinéma` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `cinéma`;

-- Listage de la structure de table cinéma. acteur
CREATE TABLE IF NOT EXISTS `acteur` (
  `id_acteur` int NOT NULL AUTO_INCREMENT,
  `id_personne` int NOT NULL,
  PRIMARY KEY (`id_acteur`),
  UNIQUE KEY `id_personne` (`id_personne`),
  CONSTRAINT `acteur_ibfk_1` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinéma.acteur : ~12 rows (environ)
INSERT INTO `acteur` (`id_acteur`, `id_personne`) VALUES
	(1, 1),
	(2, 2),
	(3, 3),
	(4, 4),
	(5, 5),
	(6, 6),
	(7, 7),
	(8, 8),
	(9, 9),
	(10, 10),
	(11, 11),
	(12, 12);

-- Listage de la structure de table cinéma. contenir
CREATE TABLE IF NOT EXISTS `contenir` (
  `id_film` int NOT NULL,
  `id_genre` int NOT NULL,
  PRIMARY KEY (`id_film`,`id_genre`),
  KEY `id_genre` (`id_genre`),
  CONSTRAINT `contenir_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `film` (`id_film`),
  CONSTRAINT `contenir_ibfk_2` FOREIGN KEY (`id_genre`) REFERENCES `genre` (`id_genre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinéma.contenir : ~10 rows (environ)
INSERT INTO `contenir` (`id_film`, `id_genre`) VALUES
	(1, 1),
	(8, 1),
	(2, 2),
	(7, 2),
	(1, 3),
	(6, 3),
	(7, 3),
	(2, 4),
	(6, 4),
	(8, 4);

-- Listage de la structure de table cinéma. film
CREATE TABLE IF NOT EXISTS `film` (
  `id_film` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(50) NOT NULL,
  `date_sortie` date NOT NULL,
  `duree_minute` int NOT NULL,
  `affiche` varchar(255) DEFAULT NULL,
  `note` int NOT NULL,
  `synopsis` text,
  `id_realisateur` int NOT NULL,
  PRIMARY KEY (`id_film`),
  KEY `id_realisateur` (`id_realisateur`),
  CONSTRAINT `film_ibfk_1` FOREIGN KEY (`id_realisateur`) REFERENCES `realisateur` (`id_realisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinéma.film : ~5 rows (environ)
INSERT INTO `film` (`id_film`, `titre`, `date_sortie`, `duree_minute`, `affiche`, `note`, `synopsis`, `id_realisateur`) VALUES
	(1, 'World War Z', '2022-06-15', 135, 'affiche_wwz.jpg', 5, 'Un homme lutte contreles zombie dans la ville.', 1),
	(2, 'Rire à Gogo', '2023-03-28', 110, 'affiche_wwz.jpg', 4, 'Une comédie hilarante sur la vie quotidienne.', 2),
	(6, 'Labyrinthe Obscur', '2023-07-20', 120, 'affiche_wwz.jpg', 4, 'Un thriller mystérieux dans un labyrinthe.', 1),
	(7, 'Les Rêves Éveillés', '2023-05-10', 105, 'affiche_wwz.jpg', 3, 'Des rêves étranges deviennent réalité pour un groupe d\'amis.', 2),
	(8, 'L\'Épopée Stellaire', '2023-09-05', 150, 'affiche_wwz.jpg', 2, 'Un voyage épique à travers les étoiles pour sauver la galaxie.', 1);

-- Listage de la structure de table cinéma. genre
CREATE TABLE IF NOT EXISTS `genre` (
  `id_genre` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) NOT NULL,
  PRIMARY KEY (`id_genre`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinéma.genre : ~6 rows (environ)
INSERT INTO `genre` (`id_genre`, `libelle`) VALUES
	(1, 'Action'),
	(2, 'Comédie'),
	(3, 'Drame'),
	(4, 'Science-Fiction'),
	(5, 'Horreur'),
	(22, 'Kiwi');

-- Listage de la structure de table cinéma. jouer
CREATE TABLE IF NOT EXISTS `jouer` (
  `id_film` int NOT NULL,
  `id_acteur` int NOT NULL,
  `id_role` int NOT NULL,
  PRIMARY KEY (`id_film`,`id_acteur`,`id_role`),
  KEY `id_acteur` (`id_acteur`),
  KEY `id_role` (`id_role`),
  CONSTRAINT `jouer_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `film` (`id_film`),
  CONSTRAINT `jouer_ibfk_2` FOREIGN KEY (`id_acteur`) REFERENCES `acteur` (`id_acteur`),
  CONSTRAINT `jouer_ibfk_3` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinéma.jouer : ~15 rows (environ)
INSERT INTO `jouer` (`id_film`, `id_acteur`, `id_role`) VALUES
	(1, 1, 1),
	(6, 2, 2),
	(8, 3, 3),
	(2, 4, 1),
	(2, 5, 2),
	(2, 6, 3),
	(6, 7, 1),
	(6, 8, 2),
	(8, 8, 1),
	(6, 9, 3),
	(7, 10, 1),
	(8, 10, 2),
	(7, 11, 2),
	(7, 12, 3),
	(8, 12, 3);

-- Listage de la structure de table cinéma. personne
CREATE TABLE IF NOT EXISTS `personne` (
  `id_personne` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `sexe` varchar(50) NOT NULL,
  `date_de_naissance` date NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `biographie` text NOT NULL,
  PRIMARY KEY (`id_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinéma.personne : ~12 rows (environ)
INSERT INTO `personne` (`id_personne`, `nom`, `prenom`, `sexe`, `date_de_naissance`, `photo`, `biographie`) VALUES
	(1, 'Pitt', 'Brad', 'Homme', '1980-01-15', 'brad_pitt.webp', 'William Bradley Pitt, dit Brad Pitt, est un acteur et producteur de cinéma\naméricain, né le 18 décembre 1963 à Shawnee. Sex-symbol des années\n1990, Brad Pitt est le premier acteur élu deux fois « Homme le plus sexy du monde »\npar le magazine People en 1995 et en 2000.'),
	(2, 'Johnson', 'Emily', 'Femme', '1995-05-02', NULL, ''),
	(3, 'Brown', 'Michael', 'Homme', '1972-11-25', NULL, ''),
	(4, 'Davis', 'Sarah', 'Femme', '1988-07-10', NULL, ''),
	(5, 'Williams', 'David', 'Homme', '1985-03-20', NULL, ''),
	(6, 'Jones', 'Emma', 'Femme', '1990-09-18', NULL, ''),
	(7, 'Miller', 'Liam', 'Homme', '1982-08-10', NULL, ''),
	(8, 'Clark', 'Olivia', 'Femme', '1993-04-22', NULL, ''),
	(9, 'Robinson', 'Daniel', 'Homme', '1978-12-05', NULL, ''),
	(10, 'Hall', 'Sophia', 'Femme', '1987-06-15', NULL, ''),
	(11, 'Lee', 'Matthew', 'Homme', '1991-02-25', NULL, ''),
	(12, 'Young', 'Ava', 'Femme', '1998-11-17', NULL, '');

-- Listage de la structure de table cinéma. realisateur
CREATE TABLE IF NOT EXISTS `realisateur` (
  `id_realisateur` int NOT NULL AUTO_INCREMENT,
  `id_personne` int NOT NULL,
  PRIMARY KEY (`id_realisateur`),
  UNIQUE KEY `id_personne` (`id_personne`),
  CONSTRAINT `realisateur_ibfk_1` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinéma.realisateur : ~4 rows (environ)
INSERT INTO `realisateur` (`id_realisateur`, `id_personne`) VALUES
	(1, 3),
	(2, 5),
	(3, 6),
	(4, 12);

-- Listage de la structure de table cinéma. role
CREATE TABLE IF NOT EXISTS `role` (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `role_jouer` varchar(50) NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinéma.role : ~3 rows (environ)
INSERT INTO `role` (`id_role`, `role_jouer`) VALUES
	(1, 'Lane Gerry'),
	(2, 'Antagoniste'),
	(3, 'Secondaire');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
