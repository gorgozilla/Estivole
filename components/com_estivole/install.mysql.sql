-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 12 Octobre 2015 à 20:13
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `joom_estivole`
--

-- --------------------------------------------------------

--
-- Structure de la table `pt5z3_estivole_calendars`
--

DROP TABLE IF EXISTS `pt5z3_estivole_calendars`;
CREATE TABLE IF NOT EXISTS `pt5z3_estivole_calendars` (
  `calendar_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `published` tinyint(2) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`calendar_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf16 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `pt5z3_estivole_daytimes`
--

DROP TABLE IF EXISTS `pt5z3_estivole_daytimes`;
CREATE TABLE IF NOT EXISTS `pt5z3_estivole_daytimes` (
  `daytime_id` int(11) NOT NULL AUTO_INCREMENT,
  `calendar_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `daytime_day` date NOT NULL,
  `daytime_hour_start` datetime NOT NULL,
  `daytime_hour_end` datetime NOT NULL,
  `quota` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` tinyint(2) DEFAULT '1',
  PRIMARY KEY (`daytime_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf16 AUTO_INCREMENT=53 ;

-- --------------------------------------------------------

--
-- Structure de la table `pt5z3_estivole_members`
--

DROP TABLE IF EXISTS `pt5z3_estivole_members`;
CREATE TABLE IF NOT EXISTS `pt5z3_estivole_members` (
  `member_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `lastname` varchar(150) DEFAULT NULL,
  `firstname` varchar(150) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(150) DEFAULT NULL,
  `npa` int(11) DEFAULT NULL,
  `tshirtsize` varchar(5) DEFAULT NULL,
  `availibility` varchar(50) DEFAULT NULL,
  `friendgroup` varchar(100) DEFAULT NULL,
  `favchoices` varchar(5) DEFAULT NULL,
  `comment` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf16 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Structure de la table `pt5z3_estivole_members_daytimes`
--

DROP TABLE IF EXISTS `pt5z3_estivole_members_daytimes`;
CREATE TABLE IF NOT EXISTS `pt5z3_estivole_members_daytimes` (
  `member_daytime_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `daytime_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  PRIMARY KEY (`member_daytime_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `pt5z3_estivole_services`
--

DROP TABLE IF EXISTS `pt5z3_estivole_services`;
CREATE TABLE IF NOT EXISTS `pt5z3_estivole_services` (
  `service_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf16 DEFAULT NULL,
  `summary` text CHARACTER SET utf16,
  `image` varchar(255) CHARACTER SET utf16 DEFAULT NULL,
  `published` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Structure de la table `pt5z3_estivole_services_daytimes`
--

DROP TABLE IF EXISTS `pt5z3_estivole_services_daytimes`;
CREATE TABLE IF NOT EXISTS `pt5z3_estivole_services_daytimes` (
  `id_daytime` int(11) NOT NULL,
  `id_service` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
