-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 13 jan. 2022 à 10:16
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `concerts`
--

-- --------------------------------------------------------

--
-- Structure de la table `band`
--

DROP TABLE IF EXISTS `band`;
CREATE TABLE IF NOT EXISTS `band` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Nom de groupe',
  `private` tinyint(1) NOT NULL,
  `city` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ma Ville',
  `region` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT 'Ma Region',
  `country` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Mon Pays',
  `bio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'A propos de nous. Ici vous pouvez ecrire un petit mot pour vous presenter.',
  `photo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '/graphics/placeholder.jpg',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_48DFA2EBA76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `band`
--

INSERT INTO `band` (`id`, `user_id`, `name`, `private`, `city`, `region`, `country`, `bio`, `photo`) VALUES
(1, 4, 'Richie Havens', 0, 'Brooklyn', 'New York', 'USA', 'Pour plus de trois décennies, Richie Havens se sert de sa musique pour transmettre un message de fraternité et de liberté.', '61dfe55959c21.jpg'),
(2, 5, 'Foxygen', 0, 'Westlake', 'California', 'USA', 'Ceci est un groupe', '61dfed6474262.jpg'),
(4, 6, 'Mac Demarco', 0, 'Toronto', 'Ontario', 'Canada', 'Ceci est un groupe', '61dfed8f016f8.jpg'),
(5, 7, 'Allah Las', 0, 'Los Angeles', 'California', 'USA', 'Ceci est un groupe', '61dfe58cd0652.jpg'),
(6, 8, 'Parquet Courts', 0, 'Brooklyn', 'New York', 'USA', 'Ceci est un groupe', '61dfedc3e482f.jpg'),
(7, 9, 'Cloud Nothings', 0, 'Cleveland', 'Ohio', 'USA', 'Ceci est un groupe', '61dfede9553a3.jpg'),
(8, 10, 'Juniore', 0, 'Paris', 'Paris', 'France', 'Ceci est un groupe', '61dfee12d9659.jpg'),
(9, 11, 'Stereolab', 0, 'Brooklyn', 'New York', 'USA', 'Ceci est un groupe', '61dfee3be450f.jpg'),
(10, 12, 'The Lumiñanas', 0, 'Cabestany', 'Pyrénées-Orientales,', 'France', 'Ceci est un groupe', '61dfee7ad5512.jpg'),
(11, 13, 'La Femme', 0, 'Biarritz/Paris', 'Partout', 'France', 'Ceci est un groupe', '61dfeeaf5756f.jpg'),
(12, 14, 'Clara Luciani', 0, 'Martigues', 'Bouches-du-Rhone', 'France', 'Ceci est un groupe', '61dfeedc149a7.jpg'),
(13, 15, 'Feu! Chatterton', 0, 'Paris', 'Ile de France', 'France', 'Ceci est un groupe', 'fc.jpg'),
(14, 16, 'Requin Chagrin', 0, 'Paris', NULL, 'France', 'Ceci est un groupe', ''),
(15, 17, 'The Beatles', 0, 'Liverpool', 'England', 'UK', 'The Beatles voilà', '61dff4b785ee3.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `bill`
--

DROP TABLE IF EXISTS `bill`;
CREATE TABLE IF NOT EXISTS `bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `concert_id` int(11) DEFAULT NULL,
  `band_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7A2119E383C97B2E` (`concert_id`),
  KEY `IDX_7A2119E349ABEB17` (`band_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `bill`
--

INSERT INTO `bill` (`id`, `concert_id`, `band_id`) VALUES
(1, 1, 11),
(2, 1, 8),
(3, 2, 2),
(4, 2, 7),
(5, 4, 1),
(6, 4, 10),
(7, 4, 5),
(8, 4, 14),
(9, 5, 4),
(10, 3, 4),
(11, 3, 7),
(12, 2, 5),
(13, 6, 15);

-- --------------------------------------------------------

--
-- Structure de la table `concert`
--

DROP TABLE IF EXISTS `concert`;
CREATE TABLE IF NOT EXISTS `concert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venue_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D57C02D240A73EBA` (`venue_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `concert`
--

INSERT INTO `concert` (`id`, `venue_id`, `name`, `date`) VALUES
(1, 2, 'La Femme et Juniore', '2022-01-13 19:30:00'),
(2, 2, 'Foxygen et Allah Las', '2022-01-14 18:25:00'),
(3, 2, 'Cloud Nothings et Mac Demarco', '2022-01-15 18:25:00'),
(4, 3, 'Fête du 13 Janvier !', '2022-01-13 16:26:00'),
(5, 3, 'Mac DeMarco', '2022-01-14 21:31:00'),
(6, 4, 'The Beatles', '2022-01-13 20:30:00');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `username`, `roles`, `password`) VALUES
(1, 'ianpherbert@email.com', 'ianpherbert', '[\"venue\"]', '$2y$13$yuR5ErIUzOlGTAfjWfEm.egTx2tHao1rnz7hmVsfxv.jqfFADIv8S'),
(2, 'firstuser@email.com', 'firstUser', '[\"venue\"]', '$2y$13$GD0ogqfbgOznDdHsDB8l0OY/tcnUTqMaMFxMHzCUr1B/M2S.NUcAK'),
(3, 'usertwo@email.com', 'userTwo', '[\"venue\"]', '$2y$13$c9KXjHCRNv2JA0AZFkZmTud.sSdrX2b/iBx0oVPEpJxQCD4CMEaR6'),
(4, 'userthree@email.com', 'userThree', '[\"band\"]', '$2y$13$SqkiYqKPHTRZOP/AQ1R5neLvkWamwmgGCwEMKMTH6zcsVKqF14Z8K'),
(5, 'user3@email.com', 'user3', '[\"band\"]', '$2y$13$GD0ogqfbgOznDdHsDB8l0OY/tcnUTqMaMFxMHzCUr1B/M2S.NUcAK'),
(6, 'user4@email.com', 'user4', '[\"band\"]', '$2y$13$GD0ogqfbgOznDdHsDB8l0OY/tcnUTqMaMFxMHzCUr1B/M2S.NUcAK'),
(7, 'user5@email.com', 'user5', '[\"band\"]', '$2y$13$GD0ogqfbgOznDdHsDB8l0OY/tcnUTqMaMFxMHzCUr1B/M2S.NUcAK'),
(8, 'user6@email.com', 'user6', '[\"band\"]', '$2y$13$GD0ogqfbgOznDdHsDB8l0OY/tcnUTqMaMFxMHzCUr1B/M2S.NUcAK'),
(9, 'user7@email.com', 'user7', '[\"band\"]', '$2y$13$GD0ogqfbgOznDdHsDB8l0OY/tcnUTqMaMFxMHzCUr1B/M2S.NUcAK'),
(10, 'user8@email.com', 'user8', '[\"band\"]', '$2y$13$GD0ogqfbgOznDdHsDB8l0OY/tcnUTqMaMFxMHzCUr1B/M2S.NUcAK'),
(11, 'user9@email.com', 'user9', '[\"band\"]', '$2y$13$GD0ogqfbgOznDdHsDB8l0OY/tcnUTqMaMFxMHzCUr1B/M2S.NUcAK'),
(12, 'user10@email.com', 'user10', '[\"band\"]', '$2y$13$GD0ogqfbgOznDdHsDB8l0OY/tcnUTqMaMFxMHzCUr1B/M2S.NUcAK'),
(13, 'user11@email.com', 'user11', '[\"band\"]', '$2y$13$GD0ogqfbgOznDdHsDB8l0OY/tcnUTqMaMFxMHzCUr1B/M2S.NUcAK'),
(14, 'user12@email.com', 'user12', '[\"band\"]', '$2y$13$GD0ogqfbgOznDdHsDB8l0OY/tcnUTqMaMFxMHzCUr1B/M2S.NUcAK'),
(15, 'user13@email.com', 'user13', '[\"band\"]', '$2y$13$GD0ogqfbgOznDdHsDB8l0OY/tcnUTqMaMFxMHzCUr1B/M2S.NUcAK'),
(16, 'user14@email.com', 'user14', '[\"band\"]', '$2y$13$GD0ogqfbgOznDdHsDB8l0OY/tcnUTqMaMFxMHzCUr1B/M2S.NUcAK'),
(17, 'thebeatles@email.com', 'thebeatles', '[\"band\"]', '$2y$13$vN1p1XQQt14U2LpX7lPELeZAxZRoltd/JBdbqyWYOADmOCHqzaIze'),
(18, 'beachland@email.com', 'beachland', '[\"venue\"]', '$2y$13$gXshdG.5aYVX/cNAHhNVzeP/PTSDOka42ULCx1IvslTcagfmpjov2');

-- --------------------------------------------------------

--
-- Structure de la table `venue`
--

DROP TABLE IF EXISTS `venue`;
CREATE TABLE IF NOT EXISTS `venue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ma Salle de Spectacle',
  `private` tinyint(1) NOT NULL,
  `address` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Mon Adresse',
  `city` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ma Ville',
  `region` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT 'Ma region',
  `country` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Mon Pays',
  `postal_code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '00000',
  `photo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_91911B0DA76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `venue`
--

INSERT INTO `venue` (`id`, `user_id`, `name`, `private`, `address`, `city`, `region`, `country`, `postal_code`, `photo`, `capacity`) VALUES
(1, 1, 'Steréolux', 0, '4 bd. Léon Bureau', 'Nantes', 'Pays de la Loire', 'France', '44200', '61dfe43b70c13.jpg', 0),
(2, 2, 'Le grand T', 0, '47-49 Rue du Coudray', 'Nantes', 'Pays de La loire', 'France', '44000', '61dfe49d18caa.jpg', 1000),
(3, 3, 'Point Éphémère', 0, '200 Quai de Valmy', 'Paris', 'Ile de France', 'France', '75010', '61dfe4f26eab6.jpg', 0),
(4, 18, 'Beachland', 0, '21 waterloo', 'Cleveland', 'Ohio', 'USA', '44100', '61dff51362749.jpg', 0);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `band`
--
ALTER TABLE `band`
  ADD CONSTRAINT `FK_48DFA2EBA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `FK_7A2119E349ABEB17` FOREIGN KEY (`band_id`) REFERENCES `band` (`id`),
  ADD CONSTRAINT `FK_7A2119E383C97B2E` FOREIGN KEY (`concert_id`) REFERENCES `concert` (`id`);

--
-- Contraintes pour la table `concert`
--
ALTER TABLE `concert`
  ADD CONSTRAINT `FK_D57C02D240A73EBA` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`id`);

--
-- Contraintes pour la table `venue`
--
ALTER TABLE `venue`
  ADD CONSTRAINT `FK_91911B0DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
