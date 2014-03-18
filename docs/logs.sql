-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 18 mrt 2014 om 19:21
-- Serverversie: 5.5.35
-- PHP-Versie: 5.4.4-14+deb7u8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `logs`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `p1_data`
--

CREATE TABLE IF NOT EXISTS `p1_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_created` datetime DEFAULT NULL,
  `t1_usage` varchar(20) DEFAULT NULL,
  `t2_usage` varchar(20) DEFAULT NULL,
  `t1_restitution` varchar(20) DEFAULT NULL,
  `t2_restitution` varchar(20) DEFAULT NULL,
  `rate` int(5) DEFAULT NULL,
  `current_usage` varchar(20) DEFAULT NULL,
  `current_restitution` varchar(20) DEFAULT NULL,
  `gas_usage` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4321 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `p1_data_daily`
--

CREATE TABLE IF NOT EXISTS `p1_data_daily` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_created` date NOT NULL,
  `t1_usage` varchar(10) NOT NULL,
  `t2_usage` varchar(10) NOT NULL,
  `t1_restitution` varchar(10) NOT NULL,
  `t2_restitution` varchar(10) NOT NULL,
  `gas_usage` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `date_created` (`date_created`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `p1_user`
--

CREATE TABLE IF NOT EXISTS `p1_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `date_last_login` datetime DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `zipcode` char(7) NOT NULL,
  `email` varchar(255) NOT NULL,
  `hash` varchar(25) NOT NULL,
  `costs_energy_high` char(10) NOT NULL DEFAULT '0',
  `costs_energy_low` char(10) NOT NULL DEFAULT '0',
  `costs_gas` char(10) NOT NULL DEFAULT '0',
  `cookie_hash` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
