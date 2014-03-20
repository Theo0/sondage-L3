-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Sam 15 Mars 2014 à 17:37
-- Version du serveur: 5.6.14
-- Version de PHP: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `sondage`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE IF NOT EXISTS `commentaire` (
  `id` int(11) NOT NULL,
  `id_sondage` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `texte` varchar(250) DEFAULT NULL,
  `id_commentaire` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_commentaire` (`id_sondage`),
  KEY `idx_commentaire2` (`id_user`),
  KEY `idx_commentaire_0` (`id_commentaire`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

CREATE TABLE IF NOT EXISTS `groupe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `administrateur_id` int(11) DEFAULT NULL,
  `visibilite` enum('public','privé_visible','privé_caché') DEFAULT 'public',
  PRIMARY KEY (`id`),
  KEY `idx_groupe` (`administrateur_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Structure de la table `option`
--

CREATE TABLE IF NOT EXISTS `option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `texte` varchar(250) DEFAULT NULL,
  `id_sondage` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `sondage`
--

CREATE TABLE IF NOT EXISTS `sondage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(150) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `visibilite` enum('public','inscrits','groupe','privé') DEFAULT NULL,
  `administrateur_id` int(11) DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_fin` datetime DEFAULT NULL,
  `secret` enum('secret','secret_scrutin','public') DEFAULT NULL,
  `id_groupe` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sondage` (`administrateur_id`),
  KEY `idx_sondage_0` (`id_groupe`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `sous_groupe`
--

CREATE TABLE IF NOT EXISTS `sous_groupe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_groupe` int(11) DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sous_groupe` (`id_groupe`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(30) DEFAULT NULL,
  `nom` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `hash_validation` varchar(100) DEFAULT NULL,
  `administrateur_site` tinyint(1) DEFAULT '0',
  `date_inscription` datetime DEFAULT NULL,
  `compte_valide` tinyint(1) DEFAULT '0',
  `pseudo` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Structure de la table `user_commentaire_like`
--

CREATE TABLE IF NOT EXISTS `user_commentaire_like` (
  `id_user` int(11) NOT NULL,
  `id_commentaire` int(11) NOT NULL,
  PRIMARY KEY (`id_user`,`id_commentaire`),
  KEY `idx_user_commentaire_like_0` (`id_user`),
  KEY `idx_user_commentaire_like_1` (`id_commentaire`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `user_groupe_membre`
--

CREATE TABLE IF NOT EXISTS `user_groupe_membre` (
  `id_user` int(11) NOT NULL,
  `id_groupe` int(11) NOT NULL,
  `accepte` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_user`,`id_groupe`),
  KEY `idx_user_groupe_membre` (`id_groupe`),
  KEY `idx_user_groupe_membre2` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `user_groupe_moderateur`
--

CREATE TABLE IF NOT EXISTS `user_groupe_moderateur` (
  `id_user` int(11) NOT NULL,
  `id_groupe` int(11) NOT NULL,
  PRIMARY KEY (`id_user`,`id_groupe`),
  KEY `idx_user_groupe_moderateur_0` (`id_groupe`),
  KEY `idx_user_groupe_moderateur_1` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `user_sondage_delegue`
--

CREATE TABLE IF NOT EXISTS `user_sondage_delegue` (
  `id_user` int(11) NOT NULL,
  `id_user_delegue` int(11) NOT NULL,
  `id_sondage` int(11) NOT NULL,
  PRIMARY KEY (`id_user`,`id_user_delegue`,`id_sondage`),
  KEY `idx_user_sondage_delegue` (`id_user`),
  KEY `idx_user_sondage_delegue2` (`id_user_delegue`),
  KEY `idx_user_sondage_delegue3` (`id_sondage`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `user_sondage_moderateur`
--

CREATE TABLE IF NOT EXISTS `user_sondage_moderateur` (
  `id_user` int(11) NOT NULL,
  `id_sondage` int(11) NOT NULL,
  PRIMARY KEY (`id_user`,`id_sondage`),
  KEY `idx_user_sondage_moderateur` (`id_sondage`),
  KEY `idx_user_sondage_moderateur2` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `user_sondage_reponse`
--

CREATE TABLE IF NOT EXISTS `user_sondage_reponse` (
  `id_sondage` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_option` int(11) NOT NULL,
  `classement` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_sondage`,`id_user`,`id_option`),
  KEY `idx_user_sondage_reponse` (`id_sondage`),
  KEY `idx_user_sondage_reponse_0` (`id_user`),
  KEY `idx_user_sondage_reponse_1` (`id_option`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `user_sondage_votant`
--

CREATE TABLE IF NOT EXISTS `user_sondage_votant` (
  `id_sondage` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_sondage`,`id_user`),
  KEY `idx_user_sondage_votant` (`id_user`),
  KEY `idx_user_sondage_votant2` (`id_sondage`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `fk_commentaire_commentaire` FOREIGN KEY (`id_commentaire`) REFERENCES `commentaire` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_commentaire_sondage` FOREIGN KEY (`id_sondage`) REFERENCES `sondage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_commentaire_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `groupe`
--
ALTER TABLE `groupe`
  ADD CONSTRAINT `fk_groupe_user` FOREIGN KEY (`administrateur_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `option`
--
ALTER TABLE `option`
  ADD CONSTRAINT `fk_options_sondage` FOREIGN KEY (`id`) REFERENCES `sondage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `sondage`
--
ALTER TABLE `sondage`
  ADD CONSTRAINT `fk_sondage_groupe` FOREIGN KEY (`id_groupe`) REFERENCES `groupe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sondage_user` FOREIGN KEY (`administrateur_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `sous_groupe`
--
ALTER TABLE `sous_groupe`
  ADD CONSTRAINT `fk_sous_groupe_groupe` FOREIGN KEY (`id_groupe`) REFERENCES `groupe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_commentaire_like`
--
ALTER TABLE `user_commentaire_like`
  ADD CONSTRAINT `fk_user_commentaire_like` FOREIGN KEY (`id_commentaire`) REFERENCES `commentaire` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_commentaire_like_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_groupe_membre`
--
ALTER TABLE `user_groupe_membre`
  ADD CONSTRAINT `fk_user_groupe_membre_groupe` FOREIGN KEY (`id_groupe`) REFERENCES `groupe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_groupe_membre_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_groupe_moderateur`
--
ALTER TABLE `user_groupe_moderateur`
  ADD CONSTRAINT `fk_user_groupe_moderateur` FOREIGN KEY (`id_groupe`) REFERENCES `groupe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_groupe_moderateur_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_sondage_delegue`
--
ALTER TABLE `user_sondage_delegue`
  ADD CONSTRAINT `fk_user_sondage_delegue` FOREIGN KEY (`id_sondage`) REFERENCES `sondage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_sondage_delegue_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_sondage_delegue_user_delegue` FOREIGN KEY (`id_user_delegue`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_sondage_moderateur`
--
ALTER TABLE `user_sondage_moderateur`
  ADD CONSTRAINT `fk_user_sondage_moderateur` FOREIGN KEY (`id_sondage`) REFERENCES `sondage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_sondage_moderateur_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_sondage_reponse`
--
ALTER TABLE `user_sondage_reponse`
  ADD CONSTRAINT `fk_user_sondage_reponse` FOREIGN KEY (`id_sondage`) REFERENCES `sondage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_sondage_reponse_options` FOREIGN KEY (`id_option`) REFERENCES `option` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_sondage_reponse_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_sondage_votant`
--
ALTER TABLE `user_sondage_votant`
  ADD CONSTRAINT `fk_user_sondage_votant_sondage` FOREIGN KEY (`id_sondage`) REFERENCES `sondage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_sondage_votant_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
