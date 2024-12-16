-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 16 déc. 2024 à 13:52
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `academie_magique`
--

-- --------------------------------------------------------

--
-- Structure de la table `creature`
--

DROP TABLE IF EXISTS `creature`;
CREATE TABLE IF NOT EXISTS `creature` (
  `id_creature` int NOT NULL AUTO_INCREMENT,
  `nom_creature` varchar(100) NOT NULL,
  `description_creature` text,
  `img_creature` varchar(255) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `type_id` int DEFAULT NULL,
  PRIMARY KEY (`id_creature`),
  KEY `user_id` (`user_id`),
  KEY `type_id` (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `creature`
--

INSERT INTO `creature` (`id_creature`, `nom_creature`, `description_creature`, `img_creature`, `user_id`, `type_id`) VALUES
(1, 'elementaire aquatique', 'Une entité aquatique créée par magie, protectrice et dévastatrice dans son élément.', 'img/uploads/elementaire_d\'eau.jpg', 4, 1),
(2, 'kappa', 'Une créature mi-humaine mi-tortue, connue pour son ingéniosité et sa ruse dans les eaux calmes.', 'img/uploads/kappa.jpg', 4, 1),
(3, 'kirrin', 'Un animal aquatique légendaire, symbole de chance et de prospérité.', 'img/uploads/kirin.jpg', 4, 1),
(4, 'cerbère', 'Un chien à trois têtes gardant les portes des enfers, féroce et infatigable.', 'img/uploads/cerbere.jpg', 4, 2),
(5, 'seigneur des abîmes', 'Un démon puissant, commandant des hordes infernales.', 'img/uploads/seigneur des abimes.jpg', 4, 2),
(6, 'succube', 'Une créature démoniaque séductrice, manipulant les esprits et les cœurs.', 'img/uploads/succube.jpg', 4, 2),
(7, 'tourmenteur', 'Un esprit démoniaque semant la terreur dans les rêves de ses victimes.', 'img/uploads/tourmenteur.jpg', 4, 2),
(8, 'centaure', 'Une créature mi-homme mi-cheval, sage et redoutable au combat.', 'img/uploads/centaure.jpg', 4, 3),
(9, 'cyclope', 'Un géant monoculaire, connu pour sa force brute et sa simplicité.', 'img/uploads/cyclope.jpg', 4, 3),
(10, 'harpie', 'Une créature ailée, mi-femme mi-oiseau, crainte pour ses cris perçants.', 'img/uploads/harpie.jpg', 4, 3),
(11, 'minotaure', 'Un être puissant et sauvage, mi-homme mi-taureau, errant dans les labyrinthes.', 'img/uploads/minotaure.png', 4, 3),
(12, 'fantôme', 'Une âme errante, hantant les lieux de son passé tragique.', 'img/uploads/fantome.jpg', 4, 4),
(13, 'lamasu', 'Un gardien ailé, mi-lion mi-homme, protecteur des temples oubliés.', 'img/uploads/lamasu.jpg', 4, 4),
(14, 'liche', 'Un sorcier immortel, ayant sacrifié son âme pour un pouvoir éternel.', 'img/uploads/liche.jpg', 4, 4),
(15, 'squelette', 'Une marionnette macabre animée par la magie noire.', 'img/uploads/squelette.jpg', 4, 4);

-- --------------------------------------------------------

--
-- Structure de la table `element`
--

DROP TABLE IF EXISTS `element`;
CREATE TABLE IF NOT EXISTS `element` (
  `element_id` int NOT NULL AUTO_INCREMENT,
  `noms_element` varchar(50) NOT NULL,
  PRIMARY KEY (`element_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `element`
--

INSERT INTO `element` (`element_id`, `noms_element`) VALUES
(1, 'air'),
(2, 'eau'),
(3, 'feu'),
(4, 'lumière'),
(5, 'administrateur');

-- --------------------------------------------------------

--
-- Structure de la table `sort`
--

DROP TABLE IF EXISTS `sort`;
CREATE TABLE IF NOT EXISTS `sort` (
  `id_sort` int NOT NULL AUTO_INCREMENT,
  `noms_sort` varchar(100) NOT NULL,
  `img_sort` varchar(255) DEFAULT NULL,
  `description_sort` text,
  `element_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id_sort`),
  KEY `element_id` (`element_id`),
  KEY `user_id` (`user_id`)
) ;

--
-- Déchargement des données de la table `sort`
--

INSERT INTO `sort` (`id_sort`, `noms_sort`, `img_sort`, `description_sort`, `element_id`, `user_id`) VALUES
(1, 'éclaire', NULL, 'Un sort fulgurant invoquant la foudre pour frapper un adversaire avec une précision divine.', 1, 1),
(2, 'elementaire d\'air', NULL, 'Une invocation permettant de faire apparaître une créature constituée d\'air pur, rapide et insaisissable.', 1, 1),
(3, 'vent violent', NULL, 'Une bourrasque capable de désarçonner les ennemis et de briser les défenses légères.', 1, 1),
(4, 'armure de glace', NULL, 'Une protection glaciale qui enveloppe le lanceur, rendant les coups plus difficiles à porter.', 2, 1),
(5, 'blizzard', NULL, 'Une tempête glaciale dévastatrice qui gèle tout sur son passage.', 2, 1),
(6, 'cercle de l\'hiver', NULL, 'Un sort qui crée une zone glacée, ralentissant et blessant tous les ennemis à l\'intérieur.', 2, 1),
(7, 'elementaire d\'eau', NULL, 'Une invocation qui fait apparaître une créature fluide, capable de manipuler l\'eau autour d\'elle.', 2, 1),
(8, 'mur de glace', NULL, 'Une barrière infranchissable faite de glace impénétrable.', 2, 1),
(9, 'bouclier de feu', NULL, 'Une aura de flammes protectrices qui inflige des dégâts aux attaquants proches.', 3, 1),
(10, 'boule de feu', NULL, 'Une explosion de flammes projetée vers l\'ennemi, causant des dégâts massifs.', 3, 1),
(11, 'elementaire de feu', NULL, 'Une invocation d\'un être ardent, destructeur et incontrôlable.', 3, 1),
(12, 'immolation', NULL, 'Un sort qui enflamme le lanceur et tout ce qui l\'entoure dans une danse mortelle.', 3, 1),
(13, 'tempête de feu', NULL, 'Une pluie de flammes qui s\'abat sur une large zone, réduisant tout en cendres.', 3, 1),
(14, 'soin', NULL, 'Un sort de lumière pure qui guérit les blessures les plus graves.', 4, 1),
(15, 'armure céleste', NULL, 'Une bénédiction qui renforce la défense et protège contre les ténèbres.', 4, 1),
(16, 'elementaire de lumière', NULL, 'Une invocation d\'une entité radieuse, porteuse de vie et de justice.', 4, 1),
(17, 'purification', NULL, 'Un sort qui dissipe les malédictions et bannit les ténèbres.', 4, 1),
(18, 'rétribution', NULL, 'Un éclat lumineux qui inflige des dégâts aux ennemis en fonction de leur malveillance.', 4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE IF NOT EXISTS `type` (
  `type_id` int NOT NULL AUTO_INCREMENT,
  `noms_type` varchar(50) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `type`
--

INSERT INTO `type` (`type_id`, `noms_type`) VALUES
(1, 'aquatique'),
(2, 'démonique'),
(3, 'mi-bête'),
(4, 'mort vivant');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `noms_user` varchar(100) NOT NULL,
  `mot_de_pass` varchar(255) NOT NULL,
  `element_id` int DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `element_id` (`element_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`user_id`, `noms_user`, `mot_de_pass`, `element_id`) VALUES
(4, 'Catherine', '$argon2i$v=19$m=65536,t=4,p=1$LkNRZmpadjFESkhNWHdRdg$mZjtqwuaXb4XK3syhs5FVoJIunt1i00EAXMGtCNsx98', 5);

-- --------------------------------------------------------

--
-- Structure de la table `user_element`
--

DROP TABLE IF EXISTS `user_element`;
CREATE TABLE IF NOT EXISTS `user_element` (
  `user_id` int NOT NULL,
  `element_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`element_id`),
  KEY `element_id` (`element_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
