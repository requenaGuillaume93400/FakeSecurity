-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : Dim 29 août 2021 à 12:16
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
-- Base de données : `conception`
--

-- --------------------------------------------------------

--
-- Structure de la table `agents`
--

DROP TABLE IF EXISTS `agents`;
CREATE TABLE IF NOT EXISTS `agents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `matricule` varchar(13) DEFAULT NULL,
  `grade` varchar(40) DEFAULT NULL,
  `lastName` varchar(50) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `postalCode` varchar(5) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastLogin` datetime DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `confirmed` int(1) NOT NULL DEFAULT '0',
  `admin` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `agents`
--

INSERT INTO `agents` (`id`, `matricule`, `grade`, `lastName`, `firstName`, `mail`, `phone`, `address`, `postalCode`, `city`, `country`, `createdAt`, `lastLogin`, `password`, `confirmed`, `admin`) VALUES
(1, 'BOSS000001', 'Parrain', 'Requin', 'Guillaume', 'requin@lol.ptdr', '1234567891', '3 rue meles toi de tes affaires', '99999', 'Californie', 'Frankistan', '2021-08-10 23:27:13', '2021-08-12 00:01:31', '$2y$10$3Ve75b86H40pZ8O4q1FMC.zhAJAIOcZBlfYP7VZpklV0L7LZ77dBS', 1, 1),
(2, 'ETHH9972322', 'Energivore', 'Pinard', 'Guillaume', 'pinard@jaibujaipusoif.laflemme', '1111111111', NULL, NULL, NULL, NULL, '2021-08-10 23:28:01', '2021-08-15 19:04:05', '$2y$10$XR7TBf0PLsgRaNejWiNLLuokLWwNdQlx0zJFCauoHnpMugiOIBXZm', 0, 0),
(3, 'TACH89033345', 'Great D', 'Dujardin', 'Grégoire', 'oss117@agtsecret.ledipas', '1294567891', NULL, NULL, NULL, NULL, '2021-08-10 23:29:59', NULL, '$2y$10$vGPbcggFb3rRiGCrR50OfuF3xF//EjcjLaUvu5n2X9sNDgw727YcG', 0, 0),
(4, 'CAMA77000', 'Caption Master', 'D\'astreinte', 'Quentin', 'dastreinte@live.xd', '0600660606', NULL, NULL, NULL, NULL, '2021-08-10 23:31:00', NULL, '$2y$10$F6l5VWVSGLPgIxnQWUBW1uS1k5qizC0966qnCjDG5RxL26yuMOrNC', 0, 0),
(5, 'DAME66783', 'Yametekudasai', 'Tir-o-flanc', 'Thomas', 'yametekudasai@senpai.kun', '1234567891', NULL, NULL, NULL, NULL, '2021-08-10 23:32:03', NULL, '$2y$10$b4jgh3oNFtO3X4appyVsdutPv2jkoM1n0CkXRncltZnX6on4F1FMu', 0, 0),
(6, 'JEGE123466', 'Connaisseur', 'Girofar', 'Jérémie', 'jeje@gege.gg', '1234567891', NULL, NULL, NULL, NULL, '2021-08-10 23:33:13', NULL, '$2y$10$7Y54bK/7DzWuf./4ZCWsi.IAC7JjRHuAq7YoJOT0qPvACfy//yVOC', 0, 0),
(7, 'SURV993167', 'El Yoyoz', 'Auchan', 'Yoann', 'moinsdixpourcent@lesnouveaux.commercants', '1234567891', NULL, NULL, NULL, NULL, '2021-08-10 23:34:19', NULL, '$2y$10$FBSH24wavLh.v9.IWX5Hdevhpr6fZ0FMJKSEEnlp/QfXiPZS8aGzi', 0, 0),
(8, 'BIER883288', 'Gros Tommy', 'Cartier', 'Tommy', 'tommy_vercitti@gta.alcolo', '1234567891', NULL, NULL, NULL, NULL, '2021-08-10 23:35:23', NULL, '$2y$10$aMVHq45pbE7D9o6TIEsynuyDdzgi3V44HWizwtSFbNqH9F6VW7Vqu', 0, 0),
(9, 'CRYP702113', 'Fouine', 'Keu-bim', 'Salah', 'alakeuleuleu@outlook.php', '1234567891', NULL, NULL, NULL, NULL, '2021-08-10 23:36:48', NULL, '$2y$10$7EIlq.eKBLRmy5f2O7r.yuikx4phlWQV6VQQ6I9YU8/JQLRnykzOy', 0, 0),
(10, 'SOJA0021777', 'Soja Boy', 'Man', 'Soja', 'soja_man@superheros.zero', '0600660606', 'La ou il y a du soja', NULL, 'Soja Town', 'Soja Land', '2021-08-10 23:37:42', NULL, '$2y$10$M1ymuWnFWZAJHNeK6Nah8OYkrciVwkcEkDgc.fouS4AGflu39Vj/W', 0, 0),
(11, NULL, NULL, 'lasticot', 'Coco', 'lasticot@hotmail.fr', '0600660606', '55 Rue du Faubourg Saint-Honoré', '75008', 'Barbes', 'Francistan', '2021-08-12 00:45:01', '2021-08-29 13:38:48', '$2y$10$v6TT4oc2tbANZzJpCbIkaui7xtcl1nrA3xNpcdgBtwVrGietygFCm', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteName` varchar(50) DEFAULT NULL,
  `siteType` varchar(50) DEFAULT NULL,
  `superficie` int(11) DEFAULT NULL,
  `society` varchar(255) DEFAULT NULL,
  `lastName` varchar(50) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `postalCode` varchar(6) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastLogin` datetime DEFAULT NULL,
  `confirmed` int(1) NOT NULL DEFAULT '0',
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `siteName`, `siteType`, `superficie`, `society`, `lastName`, `firstName`, `mail`, `phone`, `address`, `postalCode`, `city`, `country`, `createdAt`, `lastLogin`, `confirmed`, `password`) VALUES
(0, NULL, NULL, NULL, NULL, 'non inscrit', 'non inscrit', 'fakesecurity@gmail.com', '0606060606', NULL, NULL, NULL, NULL, '2021-08-29 13:53:58', NULL, 0, 'password'),
(1, 'Elysee', 'autre', 15000, 'En Manché', 'Maquereau', 'Emmanuel', 'em@hotmail.fr', '0606060606', '55 Rue du Faubourg Saint-Honoré', '75008', 'Paris', 'France', '2021-08-10 22:58:40', '2021-08-26 13:45:25', 1, '$2y$10$nXpAVhDqzuD87MTmrnHJBOaQttalszO3IjjsUOUi4sPJQR8V/c4Fq'),
(2, NULL, NULL, NULL, 'Ressemblement National', 'La Peine', 'Marine', 'marine@live.fr', '+33606060606', NULL, NULL, NULL, NULL, '2021-08-10 23:06:56', NULL, 0, '$2y$10$DEmgRURrLz4.mI5TR8OB3.P5S9mmwDWRo1fZe/8YYLygzkE71nW.u'),
(3, NULL, NULL, NULL, 'Bistro', 'Le Sale', 'Jean', 'lesale@gmail.com', '0606060606', NULL, NULL, NULL, NULL, '2021-08-10 23:08:27', NULL, 0, '$2y$10$tFeYu4n9IPK4VStLhgkA6uUNnzh.ooYVp498tscCVLKhWAVLv40TC'),
(4, NULL, NULL, NULL, 'LFS', 'Méchant Tonton', 'Jean-Luc', 'jl@cheguevara.fr', '0606060606', NULL, NULL, NULL, NULL, '2021-08-10 23:10:44', NULL, 0, '$2y$10$UufLCW5FnNB85KIJdDSkKezeZcy5TyXawblS.wF22r/y3DTrQYPFS'),
(5, NULL, NULL, NULL, 'EELV', 'L\'adot', 'Yannick', 'gretaforever@eelv.pave', '0636363636', NULL, NULL, NULL, NULL, '2021-08-10 23:11:57', NULL, 0, '$2y$10$CDiKhZhhuZKceTVQ8aZ3geJgiAa.KN.gAv87ZpOyRjMwrOYyApJvu'),
(6, NULL, NULL, NULL, 'En Manché', 'Latex', 'Jean', 'latex@bdsm.fr', '+22111111111', NULL, NULL, NULL, NULL, '2021-08-10 23:12:44', NULL, 0, '$2y$10$Z8p8SL2nSWzO8ziNZrZpyug8Vs6ML6kBZeYIPf5PMIixyEyWQgZPW'),
(7, NULL, NULL, NULL, 'Les Ripoux Blicains', 'Traitresse', 'Valérie', 'traitresse@live.fr', '+33606060606', NULL, NULL, NULL, NULL, '2021-08-10 23:13:40', NULL, 0, '$2y$10$GQpN9wEDQwOL7i0zEjUdmOV29LAcb4d3P96HNiy9KOx.9hVAyvG6C'),
(8, NULL, NULL, NULL, 'Les Ripoux Blicains', 'Bear Gland', 'Xavier', 'xavierjeseraispresidentlol@hotmail.fr', '0636363636', NULL, NULL, NULL, NULL, '2021-08-10 23:15:04', NULL, 0, '$2y$10$snu6HMIwuteCnpRjgmtRLOPl74AcCwUd9n/7EMnCG3sXmy4FftA1u'),
(9, NULL, NULL, NULL, 'UPR', 'Ass et Lino', 'François', 'assetlino@live.fr', '0636363636', NULL, NULL, NULL, NULL, '2021-08-10 23:17:15', NULL, 0, '$2y$10$RWpuxnvmjoNwBcO6EvV3WOxi5x5v/9x9VghHc94yYMCqSpvas3BRK'),
(10, NULL, NULL, NULL, 'En Manché', 'Ndiaye', 'Si bete', 'sibete@vraimentbete.fr', '0606060606', NULL, NULL, NULL, NULL, '2021-08-10 23:17:54', NULL, 0, '$2y$10$2ZguWQFnoeE2FvV2E40Ug.EPfwMZvQkkrXZKNVyKpc8xUOLC8ThlC'),
(11, NULL, NULL, NULL, 'En Manché', 'Dard Malin', 'Gérald', 'dard_malin@perv.com', '+33606060606', '3 avenue des pervers', NULL, NULL, NULL, '2021-08-10 23:19:26', NULL, 0, '$2y$10$7LQtS5zVPDtLLG1YZeMNEeycf7yJRQRPp9bc19hRGQufDh8hIF596');

-- --------------------------------------------------------

--
-- Structure de la table `ordersdetails`
--

DROP TABLE IF EXISTS `ordersdetails`;
CREATE TABLE IF NOT EXISTS `ordersdetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idClient` int(11) DEFAULT NULL,
  `cds` int(11) NOT NULL DEFAULT '0',
  `cdp` int(11) NOT NULL DEFAULT '0',
  `acdp` int(11) NOT NULL DEFAULT '0',
  `opv` int(11) NOT NULL DEFAULT '0',
  `ads` int(11) NOT NULL DEFAULT '0',
  `accd` int(11) NOT NULL DEFAULT '0',
  `beginDate` varchar(10) NOT NULL,
  `endDate` varchar(10) NOT NULL,
  `priceTTC` decimal(22,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idClient` (`idClient`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ordersdetails`
--

INSERT INTO `ordersdetails` (`id`, `idClient`, `cds`, `cdp`, `acdp`, `opv`, `ads`, `accd`, `beginDate`, `endDate`, `priceTTC`) VALUES
(1, 0, 0, 0, 0, 0, 1, 0, '2021-07-01', '2021-07-02', '12.12'),
(2, 0, 0, 0, 0, 0, 2, 0, '2021-07-01', '2021-07-02', '22.11'),
(3, 1, 0, 0, 0, 0, 10, 0, '2021-07-01', '2021-07-02', '1320.99'),
(4, 2, 0, 0, 0, 0, 5, 0, '2021-07-01', '2021-07-02', '54.20'),
(5, 3, 0, 0, 0, 0, 1, 0, '2021-07-01', '2021-07-02', '13.87'),
(6, 4, 0, 0, 0, 0, 2000, 0, '2021-07-01', '2021-07-02', '2157000.55'),
(7, 5, 0, 0, 0, 0, 20, 0, '2021-07-01', '2021-07-02', '2007.00'),
(8, 6, 0, 0, 0, 0, 1, 0, '2021-07-01', '2021-07-02', '99.00'),
(9, 7, 0, 0, 0, 0, 3, 0, '2021-07-01', '2021-07-02', '33.33'),
(10, 8, 0, 0, 0, 0, 200, 0, '2021-07-01', '2021-07-02', '200999.99'),
(11, 9, 0, 0, 0, 0, 25, 0, '2021-07-01', '2021-07-02', '2500.22'),
(12, 10, 0, 0, 0, 0, 1, 0, '2021-07-01', '2021-07-02', '400000093.00'),
(13, 11, 0, 0, 0, 0, 17, 0, '2021-07-01', '2021-07-02', '1753.18');

-- --------------------------------------------------------

--
-- Structure de la table `sanctions`
--

DROP TABLE IF EXISTS `sanctions`;
CREATE TABLE IF NOT EXISTS `sanctions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entitled` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `sanctions`
--

INSERT INTO `sanctions` (`id`, `entitled`) VALUES
(1, 'Rappel à l\'ordre'),
(2, 'Avertissement'),
(3, 'Blâme'),
(4, 'Mutation'),
(5, 'Mise à pied'),
(6, 'Licenciement'),
(7, 'Mise à mort');

-- --------------------------------------------------------

--
-- Structure de la table `sanctionsagents`
--

DROP TABLE IF EXISTS `sanctionsagents`;
CREATE TABLE IF NOT EXISTS `sanctionsagents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idAgent` int(11) NOT NULL,
  `idSanction` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `dateSummon` varchar(10) NOT NULL,
  `dateNotification` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idAgent` (`idAgent`),
  KEY `idSanction` (`idSanction`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `sanctionsagents`
--

INSERT INTO `sanctionsagents` (`id`, `idAgent`, `idSanction`, `reason`, `dateSummon`, `dateNotification`) VALUES
(1, 1, 1, 'Beaucoup trop drole', '2021-07-01', '2021-07-02'),
(2, 2, 1, 'Bouffe trop d\'energie', '2021-07-03', '2021-07-14'),
(3, 3, 3, 'Fait trop le malin', '2021-07-20', '2021-07-29'),
(4, 4, 4, 'Il est en vacance', '2021-07-13', '2021-07-24'),
(5, 5, 2, 'Il a pas yametekudasayé', '2021-07-01', '2021-07-02'),
(6, 6, 1, 'Il a dit non un jour', '2021-07-13', '2021-07-23'),
(7, 7, 1, 'Il m\'a pas rammené de pain au chocolat', '2021-07-20', '2021-07-24'),
(8, 8, 2, 'Est méchant', '2021-07-01', '2021-07-02'),
(9, 9, 1, 'Il a mangé le soja de sojaman', '2021-07-01', '2021-07-08'),
(10, 10, 7, 'Est un délateur', '2021-07-01', '2021-07-02');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `ordersdetails`
--
ALTER TABLE `ordersdetails`
  ADD CONSTRAINT `ordersdetails_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `sanctionsagents`
--
ALTER TABLE `sanctionsagents`
  ADD CONSTRAINT `sanctionsagents_ibfk_1` FOREIGN KEY (`idAgent`) REFERENCES `agents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sanctionsagents_ibfk_2` FOREIGN KEY (`idSanction`) REFERENCES `sanctions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
