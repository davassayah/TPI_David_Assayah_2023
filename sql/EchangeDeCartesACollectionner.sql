-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : db:3306
-- Généré le : ven. 26 mai 2023 à 14:30
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
(18, 'Salamèche', 2023, 50, 'N', 'Inflige 10 dégâts multipliés par le nombre de marqueurs de dégât sur Salamèche.', 1, '\\img\\photos\\Img_1.jpg', 4, NULL, 1),
(19, 'Bulbizarre NIV.13', 2007, 60, 'O', 'Au matin de sa vie, la graine sur son dos lui fournit les éléments\r\ndont il a besoin pour grandir.', 1, '\\img\\photos\\Img_19.jpg', 4, NULL, 1),
(20, 'Luffy', 2023, 500, 'A', '﻿\r\n5000打\r\n起動メイン ターン\r\n(コストエリアのドンを指定のレスドにで\r\nさる) 自分のコスト5以下の特徴(超新星) (麦わらの一味を持つ 「キャラ1枚までを、アクティブに、そのキャラを、このターン中、パワー\r\n1000\r\nPLEADER\r\nモンキー・D・ルフィ\r\n超新星/麦わらの一味\r\n(NESISC\r\nライブ\r\nOP01-003', 1, '\\img\\photos\\Img_20.jpg', 4, NULL, 2),
(21, 'Pharamp', 2002, 4, 'N', 'Renvoi d\'énergie Si vous avez des\r\nPokémon sur votre Banc et s\'il y a des\r\ncartes Énergie de base attachées à\r\nPharamp, prenez une de ces cartes Énergie et attachez-la à l\'un de ces Pokémon.', 1, '\\img\\photos\\Img_21.jpg', 4, NULL, 1),
(22, 'Démolosse', 2002, 30, 'N', 'Feu d\'artifice Lancez une\r\npièce. Si c\'est pile, défaussez-\r\n30\r\nvous d\'une carte Énergie\r\nattachée à Démolosse.\r\nSombre impact Le Pokémon\r\nDéfenseur ne peut pas utiliser de\r\n40\r\nPoké-Powers jusqu\'à la fin du\r\nprochain tour de votre adversaire.', 1, '\\img\\photos\\Img_22.jpg', 6, NULL, 1),
(23, 'Dracaufeu', 2007, 80, 'A', 'Vous pouvez défausser une carte Energie attachée à Dracaufeu Choisissez alors dans votre pile de défausse une carte Energie (celle que vous venez de defausser exclue) et attachez-la à Dracaufeu', 1, '\\img\\photos\\Img_23.jpg', 6, NULL, 1),
(24, 'Zorro', 2022, 15, 'O', '¥5000 新\r\nとうじょう\r\n速攻>(このカードは登場したターンにアタックできる)\r\nCHARACTER\r\nロロノア・ゾロ\r\n超新星/麦わらの一味\r\nOP01-0251\r\nAKIRA EGAWA', 1, '\\img\\photos\\Img_24.jpg', 6, NULL, 2),
(25, 'Yamato', 2023, 7, 'N', 'カウンター+ 100\r\nじょう\r\n5\r\nルール上、このカードはカード名を「光月おでん」としても扱う。\r\nダブルアタック(このカードが与えるダメージは2になる)、 [あた] バニッシュ (このカードがダメージを与えた場合、トリガーは発動せず そのカードはトラッシュに置かれる)\r\nばあい\r\n「はつどう\r\nCHARACTER\r\nヤマト\r\nワノ国', 1, '\\img\\photos\\Img_25.jpg', 7, NULL, 2),
(26, 'Ace', 2021, 60, 'O', 'ぶ\r\n¥7000 特\r\n持つと\r\nSAMPLE\r\nとちょう あいて\r\nちゅう\r\n登場時 相手のキャラ2枚までを、このターン中、パワー 3000。 その後、\r\nじぶん\r\n自分のリーダーが「白ひげ海賊団」を含む特徴を持つ場合、このキャラ!! は、このターン中、速攻を得る。\r\n(このカードは登場したターンにアタックできる)\r\nQ\r\nOP02-0\r\nCHARACTER\r\nポートガス・D・エース\r\n白ひげ海賊団', 1, '\\img\\photos\\Img_26.jpg', 7, NULL, 2),
(27, 'Nami', 2023, 40, 'A', '5000知\r\nSAMPL\r\nルール上、自分のデッキが0枚になった場合、自分は敗する代わりに\r\nしょうり、\r\n勝利する。\r\nドン!!×1 このリーダーのアタックによって、相手のライフにダメージを\r\nあ\r\nきじん\r\n与えた時、自分のデッキの上から1枚をトラッシュに置いてもよい。\r\nLEADER\r\nナミ\r\n東の海', 1, '\\img\\photos\\Img_27.jpg', 7, NULL, 2),
(28, 'Trafalgar Law', 2022, 12, 'O', '¥6000(斬\r\n「たいしょう\r\nブロッカー(相手のアタックの後、このカードをレストにし、アタック の対象をこのカードにできる)\r\n登場時 自分のキャラ1枚を持ち主の手札に戻すことができる:自分 の手札からコスト3以下のキャラカード1枚までを、登場させる。\r\nどうじょう\r\nCHARACTER\r\nトラファルガー・ロー\r\n超新星/ハートの海賊団', 1, '\\img\\photos\\Img_28.jpg', 7, NULL, 2);

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
(4, 'Admin', 'Admin@admin', 'A', 'Dmin', 'Lausanne', '1002', 'Route', '5', '$2y$10$IPyw59X.cn/fG6lX5wiEKusJ.oHBGC05b8Ivv8oBn99j7a5sXpaz.', 100, 'admin'),
(6, 'Admin2', 'Admin2', 'Admin2', 'Admin2', 'Admin2', 'Admin2', 'Admin2', '2', '$2y$10$w.nxXTyuirj.PC0CqwLAkeCGjHwkn/XptaHtAca2yHyBYdbQoq9NO', 100, 'admin'),
(7, 'Admin3', 'Admin3@hotmail.com', 'Admin3', 'Admin3', 'Admin3', 'Admin3', 'Admin3', '3', '$2y$10$G19qjG8ZURgSbWb.9EcwHu9c7vTlubQXIr3Q1m/.5D0VSKrEngaDK', 100, 'user');

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
  MODIFY `idCard` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

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
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
