-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 17 Février 2017 à 10:06
-- Version du serveur :  5.7.11
-- Version de PHP :  7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `camagru`
--
CREATE DATABASE IF NOT EXISTS `camagru` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `camagru`;

-- --------------------------------------------------------

--
-- Structure de la table `photos`
--

CREATE TABLE `photos` (
  `Id` int(11) NOT NULL,
  `Id_owner` int(11) NOT NULL,
  `Name_img` varchar(100) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `photos`
--

INSERT INTO `photos` (`Id`, `Id_owner`, `Name_img`, `Date`) VALUES
(2, 2, '1487261170', '2017-02-16 16:06:10'),
(3, 1, '1487264490', '2017-02-16 17:01:30'),
(4, 1, '1487324144', '2017-02-17 09:35:44');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_camagru`
--

CREATE TABLE `tbl_camagru` (
  `Id` int(11) NOT NULL,
  `Nom` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Prenom` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Confirm` int(11) NOT NULL DEFAULT '0',
  `Keyuser` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Cpt_reinit` int(11) NOT NULL DEFAULT '5',
  `Questionsecrete` int(11) NOT NULL,
  `Reponsesecrete` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Info` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tbl_camagru`
--

INSERT INTO `tbl_camagru` (`Id`, `Nom`, `Prenom`, `email`, `Password`, `Confirm`, `Keyuser`, `Cpt_reinit`, `Questionsecrete`, `Reponsesecrete`, `Info`) VALUES
(1, 'LIEVRE', 'Dominique', 'dominique@lievre.net', 'b913d5bbb8e461c2c5961cbe0edcdadfd29f068225ceb37da6defcf89849368f8c6c2eb6a4c4ac75775d032a0ecfdfe8550573062b653fe92fc7b8fb3b7be8d6', 1, '589b460c5244c', 5, 4, 'vÃ©lo', 'sans'),
(2, 'PASQUALINI', 'thierry', 'te42pe@gmail.com', 'b913d5bbb8e461c2c5961cbe0edcdadfd29f068225ceb37da6defcf89849368f8c6c2eb6a4c4ac75775d032a0ecfdfe8550573062b653fe92fc7b8fb3b7be8d6', 1, '589b3c7428e19', 5, 4, 'cheval', 'info'),
(3, 'dupond', 'louis', 'dupond@lievre.net', 'test', 0, '', 5, 0, '', 'free'),
(4, 'DURAND', 'robert', 'durand@lievre.net', 'test', 0, '', 5, 0, '', 'free'),
(5, 'PINGUET', 'Dominique', 'dominique@photeam.com', 'b913d5bbb8e461c2c5961cbe0edcdadfd29f068225ceb37da6defcf89849368f8c6c2eb6a4c4ac75775d032a0ecfdfe8550573062b653fe92fc7b8fb3b7be8d6', 0, '589a0199da59a', 5, 0, '', 'info'),
(6, 'PASQUALI', 'thierry', 'tpasqual@student.42.fr', 'b913d5bbb8e461c2c5961cbe0edcdadfd29f068225ceb37da6defcf89849368f8c6c2eb6a4c4ac75775d032a0ecfdfe8550573062b653fe92fc7b8fb3b7be8d6', 1, 'sdfgsdhf', 5, 0, '', 'info'),
(7, 'lievre', 'Dominique', 'portable@photeam.com', 'b913d5bbb8e461c2c5961cbe0edcdadfd29f068225ceb37da6defcf89849368f8c6c2eb6a4c4ac75775d032a0ecfdfe8550573062b653fe92fc7b8fb3b7be8d6', 0, 'sdfgsdhf', 5, 0, '', 'info'),
(10, '', '', 'test@lievre.net', '', 0, 'sdfgsdhf', 5, 0, '', 'info'),
(12, 'BERTRAND', 'merci', 'merci@adopteunvieux.com', 'test', 0, '5899e67abfeb9', 5, 0, '', 'Info'),
(17, 'UTF8', 'titi', 'utf8@yopmail.com', 'b913d5bbb8e461c2c5961cbe0edcdadfd29f068225ceb37da6defcf89849368f8c6c2eb6a4c4ac75775d032a0ecfdfe8550573062b653fe92fc7b8fb3b7be8d6', 0, '589b548c55076', 5, 4, 'vÃ©lo', 'Info'),
(18, 'nom', 'prenom', 'prenom.nom@free.fr', 'fd9d94340dbd72c11b37ebb0d2a19b4d05e00fd78e4e2ce8923b9ea3a54e900df181cfb112a8a73228d1f3551680e2ad9701a4fcfb248fa7fa77b95180628bb2', 0, '58a333f4303d1', 5, 1, 'aucun', 'Info'),
(19, 'ivan', 'strum', 'ivan.strum@laposte.net', 'fd9d94340dbd72c11b37ebb0d2a19b4d05e00fd78e4e2ce8923b9ea3a54e900df181cfb112a8a73228d1f3551680e2ad9701a4fcfb248fa7fa77b95180628bb2', 0, '58a33533447a0', 5, 1, 'nom', 'Info'),
(20, 'Kohn-Hue', 'Alain', 'alain.kohn-hue@laposte.net', 'fd9d94340dbd72c11b37ebb0d2a19b4d05e00fd78e4e2ce8923b9ea3a54e900df181cfb112a8a73228d1f3551680e2ad9701a4fcfb248fa7fa77b95180628bb2', 1, '58a464b769c9f', 5, 3, 'Kohn-Hue', 'Info');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`Id`);

--
-- Index pour la table `tbl_camagru`
--
ALTER TABLE `tbl_camagru`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `photos`
--
ALTER TABLE `photos`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `tbl_camagru`
--
ALTER TABLE `tbl_camagru`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
