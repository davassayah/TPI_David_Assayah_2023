-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : db:3306
-- Généré le : jeu. 25 mai 2023 à 09:29
-- Version du serveur : 8.0.30
-- Version de PHP : 8.0.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `echangeDeCartesACollectionner`
--

-- --------------------------------------------------------

--
-- Structure de la table `t_card`
--

CREATE TABLE `t_card` (
  `idCard` int NOT NULL,
  `carName` varchar(45) NOT NULL,
  `carDate` year NOT NULL,
  `carCredits` int UNSIGNED NOT NULL,
  `carCondition` varchar(45) NOT NULL,
  `carDescription` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `carIsAvailable` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `carPhoto` varchar(45) NOT NULL,
  `fkUser` int NOT NULL,
  `fkOrder` int DEFAULT NULL,
  `fkCollection` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `t_card`
--

INSERT INTO `t_card` (`idCard`, `carName`, `carDate`, `carCredits`, `carCondition`, `carDescription`, `carIsAvailable`, `carPhoto`, `fkUser`, `fkOrder`, `fkCollection`) VALUES
(1, 'Raichu', 2009, 27, 'A', 'Grosse souris', 1, 'photo', 1, NULL, 1),
(2, 'Dracaufeu', 2002, 30, 'O', 'Petit dragon', 1, 'photo', 1, NULL, 1),
(3, 'Salamèche', 2002, 15, 'A', 'Petite Salamandre', 1, 'photo', 1, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `t_collection`
--

CREATE TABLE `t_collection` (
  `idCollection` int NOT NULL,
  `colName` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `t_collection`
--

INSERT INTO `t_collection` (`idCollection`, `colName`) VALUES
(2, 'One Piece'),
(1, 'Pokémon');

-- --------------------------------------------------------

--
-- Structure de la table `t_order`
--

CREATE TABLE `t_order` (
  `idOrder` int NOT NULL,
  `ordStatus` enum('pending','processed') NOT NULL,
  `fkUser` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `t_user`
--

CREATE TABLE `t_user` (
  `idUser` int NOT NULL,
  `useLogin` varchar(120) NOT NULL,
  `useEmail` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `useFirstName` varchar(120) NOT NULL,
  `useLastName` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `useLocality` varchar(120) NOT NULL,
  `usePostalCode` varchar(10) NOT NULL,
  `useStreetName` varchar(255) NOT NULL,
  `useStreetNumber` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `usePassword` varchar(64) NOT NULL,
  `useCredits` int UNSIGNED NOT NULL DEFAULT '100',
  `useRole` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `t_user`
--

INSERT INTO `t_user` (`idUser`, `useLogin`, `useEmail`, `useFirstName`, `useLastName`, `useLocality`, `usePostalCode`, `useStreetName`, `useStreetNumber`, `usePassword`, `useCredits`, `useRole`) VALUES
(1, 'pn46hbx', 'David@hotmail.com', 'David', 'Assayah', 'Paudex', '1094', 'Rue de la fontaine ', '12', 'etml', 100, 'admin'),
(2, 'Test', 'test@hotmail.com', 'Jean', 'Test', 'Pully', '1009', 'Rue du chemin', '2', 'Test', 50, 'user'),
(3, 'user3', 'mailDeUser3', 'U', 'Ser', '1000', '2050', 'rue de la rue', '23', 'user', 100, 'user');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `t_card`
--
ALTER TABLE `t_card`
  ADD PRIMARY KEY (`idCard`),
  ADD KEY `idUser_idx` (`fkUser`),
  ADD KEY `idOrder_idx` (`fkOrder`),
  ADD KEY `idCollection_idx` (`fkCollection`);

--
-- Index pour la table `t_collection`
--
ALTER TABLE `t_collection`
  ADD PRIMARY KEY (`idCollection`),
  ADD UNIQUE KEY `colName_UNIQUE` (`colName`);

--
-- Index pour la table `t_order`
--
ALTER TABLE `t_order`
  ADD PRIMARY KEY (`idOrder`),
  ADD KEY `idUser_idx` (`fkUser`);

--
-- Index pour la table `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `useLogin_UNIQUE` (`useLogin`),
  ADD UNIQUE KEY `useEmail_UNIQUE` (`useEmail`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `t_card`
--
ALTER TABLE `t_card`
  MODIFY `idCard` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `t_collection`
--
ALTER TABLE `t_collection`
  MODIFY `idCollection` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `t_order`
--
ALTER TABLE `t_order`
  MODIFY `idOrder` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `t_card`
--
ALTER TABLE `t_card`
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`fkOrder`) REFERENCES `t_order` (`idOrder`),
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`fkUser`) REFERENCES `t_user` (`idUser`),
  ADD CONSTRAINT `idCollection` FOREIGN KEY (`fkCollection`) REFERENCES `t_collection` (`idCollection`);

--
-- Contraintes pour la table `t_order`
--
ALTER TABLE `t_order`
  ADD CONSTRAINT `idUser` FOREIGN KEY (`fkUser`) REFERENCES `t_user` (`idUser`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
